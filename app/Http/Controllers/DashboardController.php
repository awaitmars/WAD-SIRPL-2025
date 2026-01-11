<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Lab;
use App\Models\SesiKelas;
use App\Models\SesiPraktikum;
use App\Models\AcademicCalendar;
use App\Models\PracticumBudget;
use App\Models\Berita;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalMk = MataKuliah::count();
        $totalAnggaran = PracticumBudget::all()->sum(function ($item) {
            return $item->jumlah * $item->estimasi_harga;
        });
                            
        $mkAktif = MataKuliah::all();
        $bentrokIbadah = SesiKelas::where('status_validasi_ibadah', 'bentrok')->get();
        
        $isuTerbaru = Berita::latest()->limit(5)->get();
        
        return view('dashboard.index', compact
        ('totalMk', 
        'totalAnggaran', 
        'mkAktif', 
        'bentrokIbadah',
        'isuTerbaru'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
