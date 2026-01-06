@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="form-group mb-2">
                <a href="{{url('kategori/form/new')}}" class="btn btn-secondary">+ Kategori Baru</a>
            </div>
            <div class="card">
                <div class="card-header">Daftar Kategori Items</div>

                <div class="card-body">
                    @include('kategori.index.filter')
                    @include('kategori.index.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@include('kategori.index.js')
@endsection

