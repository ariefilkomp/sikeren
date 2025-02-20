<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function import()
    {
        return view('user.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,txt',
        ]);

        $inputFileName = storage_path('app/public/poro2.txt');
        $data = [];
        $bidang = [];
        $handle = fopen($inputFileName, "r");
        while (($line = fgets($handle)) !== false) { 
            $args = explode('|', $line);
            if(empty($args[6]))
                dd($args);
            $data[] = [
                'name' => $args[2],
                'no_hp' => '0'.substr(trim($args[6]),2),
                'password' => bcrypt('setdapass'),
                'kode_opd' => '01000000', 
                'nip' => $args[1],
                'bidang_id' => $args[3]
            ];
            $bidang[$args[3]] = $args[3];
        }
        fclose($handle); // Close the file handle
        foreach ($bidang as $key => $value) {
            $id = Bidang::create([
                'name' => $key,
                'kode_opd' => '01000000'
            ])->id;
            $bidang[$key] = $id;
        }
        
        foreach($data as $user) {
            $user['bidang_id'] = $bidang[$user['bidang_id']];
            User::create($user);
        }
        
        echo "OKEE";
    }
}
