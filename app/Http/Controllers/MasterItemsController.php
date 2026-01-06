<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use Illuminate\Http\Request;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin)) $data_search = $data_search->where('harga_beli', '>=', $hargamin);
        if (!empty($hargamax)) $data_search = $data_search->where('harga_beli', '<=', $hargamax);

        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = MasterItem::with('kategoris')->find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategoris'] = \App\Models\Kategori::orderBy('nama')->get();
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::with('kategoris')->where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $request->validate([
            'nama' => 'required',
            'harga_beli' => 'required|numeric',
            'laba' => 'required|numeric',
            'supplier' => 'required',
            'jenis' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        if ($request->hasFile('foto')) {
            if ($data_item->foto && file_exists(public_path('uploads/items/' . $data_item->foto))) {
                unlink(public_path('uploads/items/' . $data_item->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/items'), $filename);
            $data_item->foto = $filename;
        }

        $data_item->save();

        // Sync kategori (many-to-many)
        if ($request->has('kategoris')) {
            $data_item->kategoris()->sync($request->kategoris);
        } else {
            $data_item->kategoris()->detach();
        }

        return redirect('master-items')->with('success', 'Data berhasil disimpan');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach ($data as $item) {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100, 1000000);
            $item->laba = rand(10, 99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi', 'Bukulapuk', 'TokoBagas', 'E Commurz', 'Blublu'];
        $random = rand(0, 4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat', 'Alkes', 'Matkes', 'Umum', 'ATK'];
        $random = rand(0, 4);
        return $array[$random];
    }

    // Export Excel
    public function exportExcel()
    {
        $items = MasterItem::with('kategoris')->orderBy('id')->get();

        $filename = 'master-items-' . date('Y-m-d-His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($items) {
            $file = fopen('php://output', 'w');
            
            // Add BOM untuk support UTF-8 di Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header
            fputcsv($file, ['No', 'Nama Kategori (terpisah koma)', 'Nama Items', 'Nama Supplier', 'Harga', 'Laba', 'Harga Jual']);

            // Data
            $no = 1;
            foreach ($items as $item) {
                // Get kategori names
                $kategori_names = $item->kategoris->pluck('nama')->implode(', ');
                if (empty($kategori_names)) {
                    $kategori_names = '-';
                }

                // Calculate harga jual
                $harga_jual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);

                fputcsv($file, [
                    $no++,
                    $kategori_names,
                    $item->nama,
                    $item->supplier,
                    $item->harga_beli,
                    $item->laba,
                    $harga_jual
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
