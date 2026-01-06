<form method="POST">
    @csrf

    <div class="form-group">
        <label>Kode Kategori <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="kode" required value="{{old('kode', $kategori->kode ?? '')}}">
        <small class="form-text text-muted">Kode unik untuk kategori</small>
    </div>

    <div class="form-group">
        <label>Nama Kategori <span class="text-danger">*</span></label>
        <input type="text" class="form-control" name="nama" required value="{{old('nama', $kategori->nama ?? '')}}">
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>

