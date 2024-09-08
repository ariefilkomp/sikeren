<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Disposisi;
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
        $lastMonday = new Carbon('last Monday of '.$sm->subMonth()->format('F Y'));
        $period = $start->format('l') == 'Monday' ? CarbonPeriod::create($start->format('Y-m-d'), 42) : CarbonPeriod::create($lastMonday->format('Y-m-d'), 42);
        $currentMonth = $start->format('m');
        $currentMonthStr = $start->isoFormat('MMMM Y');
        //get libur use Illuminate\Support\Facades\Http;
        $liburs = [];
        $liburUrl = 'https://dayoffapi.vercel.app/api?month='. $ym->format('m').'&year='. $ym->format('Y');
        $libur = Http::get($liburUrl)->json();

        $aktivitas = Aktivitas::with('disposisi')
            ->select(DB::raw('DATE(waktu_mulai) as date'), DB::raw('count(*) as jumlah_aktivitas'))
            ->whereMonth('waktu_mulai', $ym->format('m'))
            ->groupBy('date')
            ->get()->keyBy('date')->toArray();

        if(count($libur) > 0)
        {
            foreach($libur as $lib)
            {
                $liburs[] = $lib['tanggal'];
            }
        }

        return view('aktivitas.per-bulan', compact('yearMonth','period', 'currentMonth', 'currentMonthStr', 'liburs', 'aktivitas'));
    }

    public function create(Request $request)
    {
        $users = User::all();
        return view('aktivitas.create', compact('users'));
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
        if(count($request->disposisi) ) {
            foreach ($request->disposisi as $user_id) {
                Disposisi::create([
                    'user_id' => $user_id,
                    'aktivitas_id' => $aktivitas->id
                ]);
            }    
        }

        if($aktivitas) {
            $disp = User::whereIn('id', $request->disposisi)->get();
            foreach ($disp as $d) {
                $this->sendWa($d->no_hp, 'Anda dapat disposisi');
            }
        }
        return redirect()->route('dashboard')->with('success', 'Berhasil menambahkan Aktivitas.');
    }

    private function sendWa($no_hp, $message)
    {
        $url = 'http://localhost:5001/send-message';
        $waSession = 'mysession';
        
        if (substr($no_hp, 0, 1) == '0') {
            $no_hp = '62' . substr($no_hp, 1);
        }
        
        $pp = [
            'session' => $waSession,
            'to' => $no_hp,
            'text' => $message,
        ];
        $response = Http::post($url, $pp);

        if($response->successful()) {
            return true;
        } else {
            return false;
        }
    }

    public function edit($id)
    {
        $aktivitas = Aktivitas::findOrFail($id);
        $users = User::all();
        $disposisi = $aktivitas->disposisi->pluck('user_id')->toArray();
        return view('aktivitas.edit', compact('aktivitas', 'users', 'disposisi'));
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
            'disposisi' => 'nullable',
            'file' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',
            'catatan' => 'nullable',
        ]);
        
        if ($request->hasFile('file')) {
            $validated['file'] = basename($request->file->store('public/files'));
        }
        $validated['user_id'] = auth()->user()->id;
        Aktivitas::where('id', $request->id)->update($validated);
        if(count($request->disposisi) ) {
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
