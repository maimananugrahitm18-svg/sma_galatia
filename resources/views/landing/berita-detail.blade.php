@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-6">
        <a href="{{ route('home') }}#berita" class="inline-flex items-center gap-2 text-xs font-semibold text-primary-600 hover:text-primary-700">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-sm border border-slate-200/80">
        <div class="flex items-center gap-3 mb-4">
            <span class="px-2.5 py-1 text-xs font-bold rounded-lg uppercase bg-primary-100 text-primary-800">{{ $berita->kategori }}</span>
            <span class="text-xs text-slate-400">• Diterbitkan {{ $berita->created_at->format('d F Y') }}</span>
            <span class="text-xs text-slate-400">• <i class="fa-regular fa-eye mr-1"></i>{{ $berita->views }} views</span>
        </div>

        <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 leading-tight mb-6">
            {{ $berita->judul }}
        </h1>

        @if($berita->thumbnail)
            <div class="rounded-2xl overflow-hidden mb-8 shadow-sm">
                <img src="{{ $berita->thumbnail }}" alt="{{ $berita->judul }}" class="w-full max-h-[450px] object-cover">
            </div>
        @endif

        <div class="prose max-w-none text-slate-700 leading-relaxed text-sm sm:text-base space-y-4">
            {!! nl2br(e($berita->konten)) !!}
        </div>
    </div>
</div>
@endsection
