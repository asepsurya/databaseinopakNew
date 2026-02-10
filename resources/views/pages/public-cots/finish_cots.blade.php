@extends('layouts.public')

@section('title', 'Pendaftaran Berhasil - Database INOPAK')

@section('content')
<div class="text-center py-5">
    <div class="mb-4">
        <img src="{{ asset('assets/images/checkmark.png') }}" alt="Success" style="width: 100px;" onerror="this.style.display='none';this.parentElement.innerHTML='<i class=\'mdi mdi-check-circle text-success\' style=\'font-size: 100px;\'></i>';">
        <i class="mdi mdi-check-circle text-success" style="font-size: 100px; display: none;"></i>
    </div>
    <h3 class="text-success mb-3">Pendaftaran Berhasil!</h3>
    <p class="text-muted mb-4">Terima kasih telah填写 data COTS Anda.<br>Data Anda akan segera diproses oleh tim kami.</p>
    <div class="d-flex justify-content-center gap-3">
        <a href="/cots" class="btn btn-outline-primary">
            <i class="mdi mdi-plus"></i> Tambah Lagi
        </a>
        <a href="/" class="btn btn-primary">
            <i class="mdi mdi-home"></i> Beranda
        </a>
    </div>
</div>
@endsection
