<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
    public function index()
    {
        return view('kategori.index.index');
    }

    public function search(Request $request)
    {
        $nama = $request->nama;
        $kode = $request->kode;

        $data_search = Kategori::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', 'LIKE', '%' . $kode . '%');
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        $data_search = $data_search->select('id', 'kode', 'nama')->orderBy('id', 'desc')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $kategori = [];
        } else {
            $kategori = Kategori::find($id);
        }
        $data['kategori'] = $kategori;
        $data['method'] = $method;
        return view('kategori.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = Kategori::with('items')->where('kode', $kode)->first();
        return view('kategori.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        // Validasi
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|unique:kategoris,kode,' . $id,
        ]);

        if ($method == 'new') {
            $data_kategori = new Kategori;
        } else {
            $data_kategori = Kategori::find($id);
        }

        $data_kategori->nama = $request->nama;
        $data_kategori->kode = $request->kode;
        $data_kategori->save();

        return redirect('kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    public function delete($id)
    {
        Kategori::find($id)->delete();
        return redirect('kategori')->with('success', 'Data kategori berhasil dihapus');
    }

    // Export PDF
    public function exportPdf($kode)
    {
        $kategori = Kategori::with('items')->where('kode', $kode)->first();
        
        $pdf = Pdf::loadView('kategori.pdf', ['kategori' => $kategori]);
        
        return $pdf->download('kategori-' . $kode . '-' . date('Y-m-d') . '.pdf');
    }
}

