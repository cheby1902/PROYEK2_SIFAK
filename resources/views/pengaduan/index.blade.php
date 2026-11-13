@extends('layouts.app')
@section('title', 'Kelola Pengaduan - SIFAK')
@section('content')
<h1>Kelola Pengaduan</h1>
<p class="sub">Lihat detail pengaduan dan ubah status pengaduan.</p>
@include('partials.tabel-pengaduan', ['pengaduan' => $pengaduan])
@endsection