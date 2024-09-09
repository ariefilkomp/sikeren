<?php

namespace App\Console\Commands;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendWa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wa:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirim pesan yang ada di tabel message ke wa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messages = Message::whereNotNull('to')->where('sent_time', null)->get();
        if ($messages->count() == 0) {
            echo "Tidak ada pesan yang perlu dikirim";
            return;
        }

        foreach ($messages as $message) {

            if ($message->sending_time == null) {
                $this->send($message->to, $message);
            } else {
                $now = Carbon::now()->setTimezone('Asia/Jakarta');
                if($now->gte($message->sending_time)) {
                    $this->send($message->to, $message);
                }
            }
            
        }
    }

    private function send(String $no_hp, Message $message)
    {
        $url = 'http://localhost:5001/send-message';
        $waSession = 'mysession';

        if (substr($no_hp, 0, 1) == '0') {
            $no_hp = '62' . substr($no_hp, 1);
        }

        $pp = [
            'session' => $waSession,
            'to' => $no_hp,
            'text' => $message->message,
        ];
        $response = Http::post($url, $pp);

        if ($response->successful()) {
            $message->sent_time = now();
            $message->save();
            echo "Berhasil send message to $no_hp";
        } else {
            echo "Gagal send message to $no_hp";
        }
    }
}
