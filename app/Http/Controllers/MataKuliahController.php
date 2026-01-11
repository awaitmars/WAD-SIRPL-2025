<?php

namespace App\Http\Controllers;
use App\Models\MataKuliah;
use App\Models\Lab;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
    $filter = $request->query('filter');
    $search = $request->query('search');

    $mkQuery = MataKuliah::with('labs');
    if ($search) {
        $mkQuery->where(function($query) use ($search) {
            $query->where('kode_mk', 'like', "%$search%")
                  ->orWhere('nama_mk', 'like', "%$search%");
        });
    }

    $labQuery = Lab::whereDoesntHave('mataKuliah');
    if ($search) {
        $labQuery->where('nama_lab', 'like', "%$search%");
    }

    if ($filter === 'mk') {
        $data = $mkQuery->get();
    } elseif ($filter === 'lab') {
        $data = $labQuery->get();
    } else {
        $mk = $mkQuery->get();
        $lab = $labQuery->get();
        $data = $mk->concat($lab);
    }

    return view('master-mk.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required_if:type,mk_only,both|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required_if:type,mk_only,both',
            'sks' => 'required_if:type,mk_only,both|integer|min:1',
            'nama_lab' => 'required_if:type,lab_only,both',
        ], [
            'kode_mk.unique' => 'Gagal! Kode Mata Kuliah sudah terdaftar.',
            'nama_mk.required_if' => 'Nama Mata Kuliah wajib diisi.',
            'nama_lab.required_if' => 'Nama Laboratorium wajib diisi.',
            'sks.max' => 'SKS maksimal adalah 3.',
        ]);
        
        $type = $request->input('type');

        if ($type === 'mk_only') {
            MataKuliah::create($request->only(['kode_mk', 'nama_mk', 'sks']));
        } 
        elseif ($type === 'lab_only') {
            Lab::create([
                'nama_lab' => $request->input('nama_lab'),
                'kapasitas' => $request->input('kapasitas'),
                'provinsi' => $request->input('provinsi'),
                'kota' => $request->input('kota'),
            ]);
        } else {
            $mk = MataKuliah::create($request->only(['kode_mk', 'nama_mk', 'sks']));
            $mk->labs()->create([
                'nama_lab' => $request->input('nama_lab'),
                'kapasitas' => $request->input('kapasitas'),
                'provinsi' => $request->input('provinsi'),
                'kota' => $request->input('kota'),
            ]);
    }
        return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
{
    // 1. Validasi dasar (Opsional tapi disarankan)
    $request->validate([
        'type' => 'required',
    ]);

    if ($request->type === 'mk') {
        $mk = MataKuliah::findOrFail($id);
        $mk->update($request->only('kode_mk', 'nama_mk', 'sks'));

        $mk->labs()->updateOrCreate(
            ['mata_kuliah_id' => $id], // Key pencari
            [
                'nama_lab'  => $request->input('nama_lab'),
                'kapasitas' => $request->input('kapasitas'),
                'provinsi'  => $request->input('provinsi'),
                'kota'      => $request->input('kota'),
            ]
        );

    } else {
        
        $lab = Lab::findOrFail($id);
        $lab->update([
            'nama_lab'  => $request->input('nama_lab'),
            'kapasitas' => $request->input('kapasitas'),
            'provinsi'  => $request->input('provinsi'),
            'kota'      => $request->input('kota'),
        ]);
    }

    return redirect()->back()->with('success', 'Data berhasil diupdate.');
}

    public function destroy(Request $request, $id)
    {
        if ($request->input('type') === 'mk') {
            $item = MataKuliah::findOrFail($id);
        } else {
            $item = Lab::findOrFail($id);
        }
        $item->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function downloadPdf()
    {
        $mataKuliah = MataKuliah::with('labs')->get();
        $lab = Lab::whereDoesntHave('mataKuliah')->get();

        $data = $mataKuliah->concat($lab);
        $pdf = Pdf::loadView('master-mk.pdf', compact('data'));
        return $pdf->download('Master-Data-MataKuliah.pdf');
    }
}
