@extends('layouts.app')
@section('title', 'Dashboard Sarpras - SIFAK')
@section('content')
<h1>Dashboard Sarpras</h1>
<p class="sub">Ringkasan pengaduan fasilitas kampus.</p>

<div class="cards">
    @foreach($stats as $label => $jumlah)
        <div class="card"><small>{{ $label }}</small><b>{{ $jumlah }}</b></div>
    @endforeach
</div>

<div class="section-head"><h2>Pengaduan Terbaru</h2></div>
@include('partials.tabel-pengaduan', ['pengaduan' => $pengaduan])
@endsection