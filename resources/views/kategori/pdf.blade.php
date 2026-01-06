<!DOCTYPE html>
<html>
<head>
    <title>Kategori - {{$kategori->nama}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .info-table {
            width: 50%;
            margin-bottom: 20px;
        }
        .info-table th {
            text-align: left;
            padding: 5px;
            width: 150px;
        }
        .info-table td {
            padding: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .items-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <h1>Laporan Kategori Items</h1>
    
    <table class="info-table">
        <tr>
            <th>Kode Kategori</th>
            <td>: {{$kategori->kode}}</td>
        </tr>
        <tr>
            <th>Nama Kategori</th>
            <td>: {{$kategori->nama}}</td>
        </tr>
    </table>

    <h3>Daftar Item ({{$kategori->items->count()}} item)</h3>

    @if($kategori->items->count() > 0)
    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
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
            @foreach($kategori->items as $index => $item)
            <tr>
                <td>{{$index + 1}}</td>
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
    <p><em>Tidak ada item untuk kategori ini.</em></p>
    @endif

    <div class="footer">
        Dicetak pada: {{date('d F Y H:i:s')}}
    </div>
</body>
</html>

