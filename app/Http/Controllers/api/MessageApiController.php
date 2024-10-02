<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Aktivitas;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessageApiController extends Controller
{
    public function store(Request $request)
    {
        $token = $request->bearerToken();
        if($token !== env('API_TOKEN')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'message' => 'required',
            'message_id' => 'required',
            'remote_jid' => 'required',
            'from_me' => 'nullable',
        ]);

        $args = explode('@', $request->remote_jid);
        $from = $args[0];
        $fromToSave = '0'.substr($from, 2);

        $words = explode(' ', $request->message);

        if(strtolower($words[0]) == 'info') {
            if(count($words) > 1) 
            {
                if(strtolower($words[1]) == 'besok') {

                } elseif(strtolower($words[1]) == 'lusa') {
                    
                } else {
                    $tgl = Carbon::parse($words[1]);
                }
            } 
            else {
                $tgl = Carbon::now();
                $aktivitas = Aktivitas::whereDate('waktu_mulai', $tgl->format('Y-m-d'))->get();
                $message = 'Kegiatan Hari ini: ' . $tgl->isoFormat('dddd, D MMMM Y').'

';
                foreach ($aktivitas as $a) {
                    $mulai = Carbon::parse($a->waktu_mulai)->format('H:i');
                    $selesai = !empty($a->waktu_selesai) ? Carbon::parse($a->waktu_selesai)->format('H:i') : 'Selesai';
                    $message .= '* '.$a->aktivitas . '( ' . $mulai . ' - ' . $selesai . ' )
';
                }

                Message::create([
                    'to' => $fromToSave,
                    'message' => $message,
                ]);
            }
        }

        $message = new Message();
        $message->message = $request->message;
        $message->message_id = $request->message_id;
        $message->remote_jid = $request->remote_jid;
        $message->from = $fromToSave;
        $message->save();

        return response()->json(['message' => 'OK']);
    }

    public function anomanReceive(Request $request)
    {
        $commandLists = ['agendaku', 'info', 'info<spasi>yyyy-mm-dd'];
        $footer = '
Lihat seluruh rincian kegiatan di '.url('/');
        $token = $request->bearerToken();
        if($token !== env('ANOMAN_APP_TOKEN')) {
            return response()->json(['message' => 'Unauthorized '], 401);
        }

        $request->validate([
            'no_hp' => 'required',
            'pesan' => 'required',
        ]); 

        $fromToSave = '0'.substr($request->no_hp, 2);
        $words = explode(' ', strtolower(trim($request->pesan)));

        if(in_array($words[0], $commandLists)) {
            if($words[0] == 'info') 
            {
                if(isset($words[1]) && Carbon::hasFormat($words[1], 'Y-m-d')) {
                    $tgl = Carbon::createFromFormat('Y-m-d', $words[1]);
                } else {
                    $tgl = Carbon::now();
                }

                $aktivitas = Aktivitas::whereDate('waktu_mulai', $tgl->format('Y-m-d'))->get();
                $message = 'Kegiatan Hari ' . $tgl->isoFormat('dddd, D MMMM Y').' :

';
                foreach ($aktivitas as $a) {
                    $mulai = Carbon::parse($a->waktu_mulai)->format('H:i');
                    $selesai = !empty($a->waktu_selesai) ? Carbon::parse($a->waktu_selesai)->format('H:i') : 'Selesai';
                    $message .= '* '.$a->aktivitas . '( ' . $mulai . ' - ' . $selesai . ' )
';
                }

                $message .= $footer;

                Message::create([
                    'to' => $fromToSave,
                    'message' => $message,
                ]);
            } elseif($words[0] == 'agendaku') {
                $user = User::where('no_hp', $fromToSave)->first();
                if(empty($user)) {
                    $message = 'Kamu bukan anak sini, silahkan hubungi admin.';
                    Message::create([
                        'to' => $fromToSave,
                        'message' => $message,
                    ]);    
                } else {
                    $tgl = Carbon::now();
                    $aktivitas = $user->nextAgenda();
                    if($aktivitas->count() == 0) {
                        $message = 'Yeaayy, '.$user->name.' tidak punya agenda kedepan!';
                        Message::create([
                            'to' => $fromToSave,
                            'message' => $message,
                        ]);
                    } else {
                        $message = 'Kegiatan '.$user->name.' :

';
                        foreach ($aktivitas as $a) {
                            $wm = Carbon::parse($a->waktu_mulai);
                            $iso = $wm->isoFormat('dddd, D MMMM Y');
                            $mulai = $wm->format('H:i');
                            $selesai = !empty($a->waktu_selesai) ? Carbon::parse($a->waktu_selesai)->format('H:i') : 'Selesai';
                            $message .= '* '.$iso.' ( ' . $mulai . ' - ' . $selesai . ' ) : '.$a->aktivitas.'
';
                        }
        
                        $message .= $footer;

                        Message::create([
                            'to' => $fromToSave,
                            'message' => $message,
                        ]);
                        
                    }

                }
            } else {
                Message::create([
                    'to' => $fromToSave,
                    'message' => 'perintah tidak ada, silahkan cek kembali.',
                ]);
            }
        } else {
            $message = 'Layanan Aplikasi SiKeren pada ANOMAN:
';
            foreach($commandLists as $c) {
                $message .= '* '.$c.'
';
            }

            Message::create([
                'to' => $fromToSave,
                'message' => $message,
            ]);

        }

        $message = new Message();
        $message->message = $request->pesan;
        $message->from = $fromToSave;
        $message->save();

        return response()->json(['message' => 'OK']);

    }
}
