<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Foto Produk</label>
        @if($method == 'edit' && isset($item->foto) && $item->foto)
        <div class="mb-2">
            <img src="{{asset('uploads/items/' . $item->foto)}}" alt="Foto" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
        </div>
        @endif
        <input type="file" class="form-control" name="foto" accept="image/*">
        <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Max 2MB</small>
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <optio @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = $item->jenis ?? ''; @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <option @if($selected == 'Umum') selected @endif>Umum</option>
            <option @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    <div class="form-group">
        <label>Kategori</label>
        <div class="border p-3 rounded">
            @if(isset($kategoris) && $kategoris->count() > 0)
                @php
                    $selected_kategoris = [];
                    if(isset($item->kategoris)) {
                        $selected_kategoris = $item->kategoris->pluck('id')->toArray();
                    }
                @endphp
                @foreach($kategoris as $kategori)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="kategoris[]" value="{{$kategori->id}}" 
                        id="kategori{{$kategori->id}}"
                        @if(in_array($kategori->id, $selected_kategoris)) checked @endif>
                    <label class="form-check-label" for="kategori{{$kategori->id}}">
                        {{$kategori->nama}} ({{$kategori->kode}})
                    </label>
                </div>
                @endforeach
            @else
                <em class="text-muted">Belum ada kategori. <a href="{{url('kategori/form/new')}}">Tambah kategori</a></em>
            @endif
        </div>
        <small class="form-text text-muted">Pilih satu atau lebih kategori</small>
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>