<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SahamAlternatif;
use Illuminate\Http\Request;

class SahamAlternatifController extends Controller
{
    public function index()
    {
        $sahams = SahamAlternatif::all();
        return view('admin.saham.index', compact('sahams'));
    }

    public function create()
    {
        return view('admin.saham.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_saham' => 'required|unique:saham_alternatifs|max:10',
            'nama_perusahaan' => 'required|max:255',
            'sektor' => 'required|max:100',
        ]);

        SahamAlternatif::create($validated);
        return redirect()->route('admin.saham.index')->with('success', 'Data Saham berhasil ditambahkan.');
    }

    public function edit(SahamAlternatif $saham)
    {
        return view('admin.saham.edit', compact('saham'));
    }

    public function update(Request $request, SahamAlternatif $saham)
    {
        $validated = $request->validate([
            'kode_saham' => 'required|max:10|unique:saham_alternatifs,kode_saham,' . $saham->id,
            'nama_perusahaan' => 'required|max:255',
            'sektor' => 'required|max:100',
        ]);

        $saham->update($validated);
        return redirect()->route('admin.saham.index')->with('success', 'Data Saham berhasil diperbarui.');
    }

    public function destroy(SahamAlternatif $saham)
    {
        $saham->delete();
        return redirect()->route('admin.saham.index')->with('success', 'Data Saham berhasil dihapus.');
    }
}
