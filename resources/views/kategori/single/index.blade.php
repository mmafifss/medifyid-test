@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="form-group mb-2">
                <a href="{{url('kategori')}}" class="btn btn-secondary">Kembali ke Daftar Kategori</a>
            </div>
            <div class="card">
                <div class="card-header">Detail Kategori</div>

                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Kode Kategori</th>
                            <td>:</td>
                            <td>{{$data->kode}}</td>
                        </tr>
                        <tr>
                            <th>Nama Kategori</th>
                            <td>:</td>
                            <td>{{$data->nama}}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Tanggal</th>
                            <td>:</td>
                            <td>{{date('d F Y H:i', strtotime($data->created_at))}}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a class="btn btn-info" href="{{url('kategori/form/edit')}}/{{$data->id}}">Edit</a>
                        <a class="btn btn-danger" href="{{url('kategori/delete')}}/{{$data->id}}" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">Hapus</a>
                        <a class="btn btn-success" href="{{url('kategori/export-pdf')}}/{{$data->kode}}">
                            <i class="bi bi-file-pdf"></i> Download PDF
                        </a>
                    </div>

                    <hr class="my-4">

                    <h5>Item yang Memiliki Kategori Ini ({{$data->items->count()}})</h5>
                    
                    @if($data->items->count() > 0)
                    <table class="table table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Item</th>
                                <th>Jenis</th>
                                <th>Harga Beli</th>
                                <th>Laba (%)</th>
                                <th>Harga Jual</th>
                                <th>Supplier</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->items as $item)
                            <tr>
                                <td>{{$item->kode}}</td>
                                <td>{{$item->nama}}</td>
                                <td>{{$item->jenis}}</td>
                                <td>Rp {{number_format($item->harga_beli, 0, ',', '.')}}</td>
                                <td>{{$item->laba}}%</td>
                                <td>Rp {{number_format($item->harga_beli + ($item->harga_beli * $item->laba / 100), 0, ',', '.')}}</td>
                                <td>{{$item->supplier}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-info mt-3">
                        Belum ada item untuk kategori ini.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection

