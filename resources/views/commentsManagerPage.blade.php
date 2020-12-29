@extends('layouts.back')

@section('content')
    <!-- Main Content -->

    @if (isset($comments))
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <h2 class="section-heading">Commentaires en attente :</h2>
                <hr>               
                <br />

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session.error }}
                    </div>    
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-striped table-bordered table-hover table-responsive-lg" id="posts">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Autheur</th>
                            <th scope="col">Commentaire</th>
                            <th scope="col">Article parent</th>
                            <th scope="col">Ajouté le</th>
                            <th scope="col" colspan=2 style="text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $comment)
                        <tr>
                            <td>{{ $comment->id }}</td>
                            <td>{{ $comment->user->name }}</td>
                            <td>{{ $comment->content }}</td>
                            <td>{{ $comment->post->title }}</td>
                            <td class="td-lastupdate">{{ $comment->updated_at }}</td>
                            <td><a href="{{ route('app_admin_comment_approve', [ 'id' => $comment->id ]) }}">Approuver</a></td>
                            <td><a href="{{ route('app_admin_comment_refuse', [ 'id' => $comment->id ]) }}">Refuser</a></td>
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