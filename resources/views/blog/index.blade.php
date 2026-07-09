@extends('layouts.landing')

@section('content')
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold text-center mb-12">Blog Venresto</h1>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @foreach($posts as $post)
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h2 class="text-2xl font-bold mb-3">
                            <a href="{{ route('blog.show', $post['slug']) }}" class="text-blue-600 hover:underline">
                                {{ $post['title'] }}
                            </a>
                        </h2>
                        <p class="text-gray-500 text-sm mb-3">{{ $post['date'] }}</p>
                        <p class="text-gray-600 mb-4">{{ $post['excerpt'] }}</p>
                        <a href="{{ route('blog.show', $post['slug']) }}" class="text-blue-600 font-semibold hover:underline">
                            Baca Selengkapnya →
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection