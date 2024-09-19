<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WaBlast extends Controller
{
    public function blast(Request $request)
    {
        $file = resource_path(). '/pegawai_kominfo.csv';
        $content = file_get_contents($file);

        // Explode the content by new line
        $lines = explode("\n", $content);
        $now = Carbon::now();
        foreach ($lines as $line) {
            $parts = explode(',', $line);
            if(count($parts) > 0) {
                $message = "Hi " . $parts[1] . "!";
                
                Message::create([
                    'to' => $parts[2],
                    'message' => $message,
                    'sending_time' => $now->addSecond()->timestamp,
                ]);
            }

        }
    }
}
