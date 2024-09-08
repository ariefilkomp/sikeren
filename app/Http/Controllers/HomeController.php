<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        $today = Carbon::createFromFormat('Y-m-d', $date)->isoFormat('dddd, D MMMM Y');
        $labelHari = Carbon::now()->format('Y-m-d') == $date ? 'Hari ini' : 'Pada Hari';

        $aktivitas = Aktivitas::with('disposisi')->whereDate('waktu_mulai', $date)->orderBy('waktu_mulai', 'asc')->get();
        return view('home', compact('today', 'labelHari', 'date', 'aktivitas'));
    }
}
