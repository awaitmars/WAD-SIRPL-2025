<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AcademicCalendarController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        try {
            if (AcademicCalendar::whereYear('tanggal', $year)->count() == 0) {
                $response = Http::get("https://dayoffapi.vercel.app/api?year=$year");
                if ($response->successful()) {
                    foreach ($response->json() as $holiday) {
                        AcademicCalendar::firstOrCreate(
                            ['tanggal' => $holiday['tanggal']],
                            ['nama_kegiatan' => $holiday['keterangan'], 'tipe' => 'libur']
                        );
                    }
                }
            }
        } catch (\Exception $e) { }

        $events = AcademicCalendar::orderBy('tanggal', 'asc')->get();
        // Mengambil semua data untuk dipetakan ke kalender
        $eventDates = AcademicCalendar::pluck('tipe', 'tanggal')->toArray();
        
        return view('Academic.index', compact('events', 'eventDates', 'month', 'year'));
    }

    public function store(Request $request)
    {
        AcademicCalendar::create($request->all());
        return redirect()->back()->with('success', 'Data berhasil ditambah');
    }

    public function update(Request $request, $id)
    {
        $event = AcademicCalendar::findOrFail($id);
        $event->update($request->all());
        return redirect()->back();
    }

    public function destroy($id)
    {
        AcademicCalendar::destroy($id);
        return redirect()->back();
    }
}