<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Opd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        $kodeOpd = $request->get('kode_opd');
        $opds = Opd::where('kode', 'like', '%000000')->get();
        $today = Carbon::createFromFormat('Y-m-d', $date)->isoFormat('dddd, D MMMM Y');
        $labelHari = Carbon::now()->format('Y-m-d') == $date ? 'Hari ini' : 'Pada Hari';

        if($kodeOpd) {
            $aktivitas = Aktivitas::with('disposisi')->where('published', 1)->where('kode_opd', $kodeOpd)->whereDate('waktu_mulai', $date)->orderBy('waktu_mulai', 'asc')->get();
        } else {
            $aktivitas = Aktivitas::with('disposisi')->where('published', 1)->whereDate('waktu_mulai', $date)->orderBy('waktu_mulai', 'asc')->get();
        }
        return view('home', compact('today', 'labelHari', 'date', 'aktivitas', 'opds', 'kodeOpd'));
    }

    public function xlsx()
    {

        $inputFileType = 'Xlsx';
        $inputFileName = storage_path('app/public/formatuser.xlsx');
        
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray();
        dd($data);
    }
}
