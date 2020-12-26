@extends('layouts.back')

@section('content')
    <!-- Main Content -->

    @if (isset($posts))
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <h2 class="section-heading">Articles :</h2>
                <hr> 
                <a class="btn btn-primary left" href="/admin/newpost">Ecrire un Article</a>
                <br />                
                <br />

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session.error }}
                    </div>    
                @endif

                <table class="table table-striped table-bordered table-hover table-responsive-lg" id="posts">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Titre</th>
                            <th scope="col">Chapo</th>
                            <th scope="col">Auteur</th>
                            <th scope="col">Date de modification</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->chapo }}</td>
                            <td>{{ $post->user->name }}</td>
                            <td class="td-lastupdate">{{ $post->updated_at }}</td>
                            <td><a href="/admin/post/{{ $post->postId }}">Modifier</a></td>
                        </tr> 
                        @endforeach                       
                    </tbody>
                </table>

                <!-- Pager 
                <div class="clearfix"></div>
                {% include 'BackOffice/PaginationPostsPage.twig' with {
                    currentPage: currentPage,
                    lastPage: totalPages
                } only %}
                -->
            </div>
        </div>
    </div>
    @else
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">   
                <h2 class="section-heading">Articles :</h2>
                <hr>                                          
                <div class="post-preview">
                    <p>
                        Il n'y a pas d'article pour le moment !
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection