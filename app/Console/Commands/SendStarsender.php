<?php

namespace App\Console\Commands;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendStarsender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starsender:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirim pesan yang ada di tabel message ke wa Dengan Starsender';

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
        $url = env('STARSENDER_URL','https://api.starsender.online/api/send');
        $apiKey = env('STARSENDER_API_KEY','db1b99ba-4d34-4caa-808f-7141165d9644');

        if (substr($no_hp, 0, 2) == '62') {
            $no_hp = '0' . substr($no_hp, 2);
        } elseif (substr($no_hp, 0, 1) == '+') {
            $no_hp = '0' . substr($no_hp, 1);
        }

        $pp = [
            "messageType" => "text",
            "to" => $no_hp,
            "body" => $message->message,
            "delay" => rand(5, 15),
        ];
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => $apiKey
        ])->accept('application/json')->post($url, $pp);
        // echo 'Url: '.$url.'\n';
        // echo 'Token: '.$token.'\n';
        // echo 'Pesan: '.$message->message.'\n';
        // echo 'No HP: '.$no_hp.'\n';
        // echo $response->body();
        if ($response->successful()) {
            $message->sent_time = now();
            $message->save();
            echo "Berhasil send message to $no_hp. status: ".$response->status()."\n";
        } else {
            echo "Gagal send message to $no_hp. status: ".$response->status()."\n";
        }
    }
}
