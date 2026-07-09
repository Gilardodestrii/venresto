@extends('layouts.landing')

@section('content')
    <section class="py-16">
        <div class="container mx-auto px-4 max-w-3xl">
            <h1 class="text-4xl font-bold mb-4">{{ $post['title'] }}</h1>
            <p class="text-gray-500 text-sm mb-8">{{ $post['date'] }}</p>

            <div class="prose max-w-none">
                {!! $post['content'] !!}
            </div>

            <div class="mt-12">
                <a href="{{ route('blog.index') }}" class="text-blue-600 hover:underline">
                    ← Kembali ke Blog
                </a>
            </div>
        </div>
    </section>
@endsection