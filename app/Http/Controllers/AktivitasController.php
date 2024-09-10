<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Bidang;
use App\Models\Disposisi;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AktivitasController extends Controller
{
    public function perBulan(Request $request)
    {
        $yearMonth = $request->get('ym', Carbon::now()->format('Y-m'));
        $ym = Carbon::createFromFormat('Y-m', $yearMonth);
        $sm = Carbon::createFromFormat('Y-m', $yearMonth);
        $start = new Carbon('first day of ' . $ym->format('F Y'));
        $lastMonday = new Carbon('last Monday of ' . $sm->subMonth()->format('F Y'));
        $period = $start->format('l') == 'Monday' ? CarbonPeriod::create($start->format('Y-m-d'), 42) : CarbonPeriod::create($lastMonday->format('Y-m-d'), 42);
        $currentMonth = $start->format('m');
        $currentMonthStr = $start->isoFormat('MMMM Y');
        //get libur use Illuminate\Support\Facades\Http;
        $liburs = [];
        $liburUrl = 'https://dayoffapi.vercel.app/api?month=' . $ym->format('m') . '&year=' . $ym->format('Y');
        $libur = Http::get($liburUrl)->json();

        $aktivitas = Aktivitas::with('disposisi')
            ->select(DB::raw('DATE(waktu_mulai) as date'), DB::raw('count(*) as jumlah_aktivitas'))
            ->whereMonth('waktu_mulai', $ym->format('m'))
            ->groupBy('date')
            ->get()->keyBy('date')->toArray();

        if (count($libur) > 0) {
            foreach ($libur as $lib) {
                $liburs[] = $lib['tanggal'];
            }
        }

        return view('aktivitas.per-bulan', compact('yearMonth', 'period', 'currentMonth', 'currentMonthStr', 'liburs', 'aktivitas'));
    }

    public function create(Request $request)
    {
        $users = User::all();
        $bidangs = Bidang::all();
        return view('aktivitas.create', compact('users', 'bidangs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aktivitas' => 'required',
            'penyelenggara' => 'required',
            'waktu_mulai' => 'required|date_format:"Y-m-d H:i"',
            'waktu_selesai' => 'nullable|after:waktu_mulai',
            'tempat' => 'required',
            'file' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',
            'catatan' => 'nullable',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = basename($request->file->store('public/files'));
        }
        $validated['user_id'] = auth()->user()->id;
        $aktivitas = Aktivitas::create($validated);
        if (count($request->disposisi)) {
            foreach ($request->disposisi as $user_id) {
                Disposisi::create([
                    'user_id' => $user_id,
                    'aktivitas_id' => $aktivitas->id
                ]);
            }
        }

        if ($aktivitas) {
            $disp = User::whereIn('id', $request->disposisi)->get();
            $waktu_mulai = Carbon::parse($aktivitas->waktu_mulai);
            $waktu_selesai = !empty($aktivitas->waktu_selesai) ? Carbon::parse($aktivitas->waktu_selesai)->format('H:i') : 'Selesai';
            $waktu = $waktu_mulai->isoFormat('dddd, D MMMM Y HH:mm') . ' - ' . $waktu_selesai;
            // Input pesan 
            $namaUserDisposisi = [];
            $atasanIds = [];
            foreach ($disp as $d) {
                $disposisiTemplate = file_get_contents(resource_path() . '/disposisi_template.txt');
                $disposisiTemplate = str_replace(
                    [
                        '{{nama}}',
                        '{{rincian_kegiatan}}',
                        '{{penyelenggara}}',
                        '{{waktu}}',
                        '{{tempat}}',
                        '{{catatan}}',
                        '{{hari}}',
                        '{{url}}'
                    ],
                    [
                        $d->name,
                        $aktivitas->aktivitas,
                        $aktivitas->penyelenggara,
                        $waktu,
                        $aktivitas->tempat,
                        $aktivitas->catatan,
                        $waktu_mulai->isoFormat('dddd, D MMMM Y'),
                        url('/?date=' . $waktu_mulai->format('Y-m-d'))
                    ],
                    $disposisiTemplate
                );

                $namaUserDisposisi[] = $d->name;
                if (!empty($d->atasan_id)) {
                    $atasanIds[$d->atasan_id] = $d->atasan_id;
                }

                Message::create([
                    'to' => $d->no_hp,
                    'message' => $disposisiTemplate,
                    'aktivitas_id' => $aktivitas->id,
                ]);

                // scheduled pengingat
                $pengingatTemplate = file_get_contents(resource_path() . '/pengingat_template.txt');
                $pengingatTemplate = str_replace(
                    [
                        '{{rincian_kegiatan}}',
                        '{{hari}}',
                        '{{url}}'
                    ],
                    [
                        $aktivitas->aktivitas,
                        $waktu_mulai->isoFormat('dddd, D MMMM Y'),
                        url('/?date=' . $waktu_mulai->format('Y-m-d'))
                    ],
                    $pengingatTemplate
                );
                Message::create([
                    'to' => $d->no_hp,
                    'message' => $pengingatTemplate,
                    'aktivitas_id' => $aktivitas->id,
                    'sending_time' => Carbon::parse($aktivitas->waktu_mulai)->subHour(2),
                ]);
            }

            if (count($atasanIds) > 0) {
                $atasan = User::whereIn('id', $atasanIds)->get();
                $atasanTemplate = file_get_contents(resource_path() . '/atasan_notif_template.txt');
                $atasanTemplate = str_replace(
                    [
                        '{{rincian_kegiatan}}',
                        '{{waktu}}',
                        '{{disposisi}}',
                    ],
                    [
                        $aktivitas->aktivitas,
                        $waktu,
                        implode(', ', $namaUserDisposisi)
                    ],
                    $atasanTemplate
                );
                foreach ($atasan as $a) {
                    Message::create([
                        'to' => $a->no_hp,
                        'message' => $atasanTemplate,
                        'aktivitas_id' => $aktivitas->id,
                    ]);

                    // pengingat
                    $pengingatAtasanTemplate = file_get_contents(resource_path() . '/pengingat_atasan_template.txt');
                    $pengingatAtasanTemplate = str_replace(
                        [
                            '{{rincian_kegiatan}}',
                            '{{disposisi}}',
                            '{{hari}}',
                            '{{url}}'
                        ],
                        [
                            $aktivitas->aktivitas,
                            implode(', ', $namaUserDisposisi),
                            $waktu_mulai->isoFormat('dddd, D MMMM Y'),
                            url('/?date=' . $waktu_mulai->format('Y-m-d'))
                        ],
                        $pengingatAtasanTemplate
                    );
                    Message::create([
                        'to' => $a->no_hp,
                        'message' => $pengingatAtasanTemplate,
                        'aktivitas_id' => $aktivitas->id,
                        'sending_time' => Carbon::parse($aktivitas->waktu_mulai)->subHour(2),
                    ]);
                }

                // ke kadin
                $kadin = User::role('kadin')->first();
                if($kadin) {
                    Message::create([
                        'to' => $kadin->no_hp,
                        'message' => $pengingatAtasanTemplate,
                        'aktivitas_id' => $aktivitas->id,
                        'sending_time' => Carbon::parse($aktivitas->waktu_mulai)->subHour(2),
                    ]);
                }
            }
        }
        return redirect()->route('dashboard')->with('success', 'Berhasil menambahkan Aktivitas.');
    }

    public function edit($id)
    {
        $aktivitas = Aktivitas::findOrFail($id);
        $users = User::all();
        $bidangs = Bidang::all();
        $disposisi = $aktivitas->disposisi->pluck('user_id')->toArray();
        return view('aktivitas.edit', compact('aktivitas', 'users', 'disposisi', 'bidangs'));
    }

    public function update($id, Request $request)
    {
        $aktivitas = Aktivitas::findOrFail($id);
        $validated = $request->validate([
            'aktivitas' => 'required',
            'penyelenggara' => 'required',
            'waktu_mulai' => 'required|date_format:"Y-m-d H:i"',
            'waktu_selesai' => 'nullable|after:waktu_mulai',
            'tempat' => 'required',
            'file' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',
            'catatan' => 'nullable',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = basename($request->file->store('public/files'));
        }
        $validated['user_id'] = auth()->user()->id;
        Aktivitas::where('id', $request->id)->update($validated);
        if (count($request->disposisi) > 0) {
            Disposisi::where('aktivitas_id', $request->id)->delete();
            foreach ($request->disposisi as $user_id) {
                Disposisi::create([
                    'user_id' => $user_id,
                    'aktivitas_id' => $aktivitas->id
                ]);
            }
        }
        return redirect()->back()->with('success', 'Data Berhasil Disimpan.');
    }

    public function destroy(Request $request)
    {
        $aktivitas = Aktivitas::findOrFail($request->id);
        $aktivitas->delete();
        return redirect()->route('dashboard')->with('success', 'Berhasil Menghapus Aktivitas.');
    }
}
