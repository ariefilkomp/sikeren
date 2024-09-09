<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MessageApiController extends Controller
{
    public function store(Request $request)
    {
        $token = $request->bearerToken();
        if(!$token && $token !== env('API_TOKEN')) {
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
}
