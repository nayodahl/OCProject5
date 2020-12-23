@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                @isset($session->error)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session.error }}
                    </div>    
                @endisset 
                <h2 class="section-heading" id="posts">Tous les articles</h2>
                <hr>

                @foreach ($posts as $post)
                    <article class="post-preview">
                        <a href="/post/{{ $post->id }}">
                        <h2 class="post-title">
                            {{ $post->title }}
                        </h2>
                        <h3 class="post-subtitle">
                            {{ $post->chapo }}
                        </h3>
                        </a>
                        <p class="post-meta">Publié par {{ $post->user->name }} le {{ $post->updated_at->format('j/m/Y, à H:i') }}</p>
                    </article>
                    <div class="clearfix">
                    <a class="btn btn-primary float-right" href="/post/{{ $post->id }}">Lire la suite &rarr;</a>
                </div>
                    <hr>
                @endforeach

                
                <div class="clearfix"></div>
                <!-- Pager -->
                {{ $posts->links() }}
                <!-- Pager end -->
                
            </div>
        </div>
    </div>
@endsection