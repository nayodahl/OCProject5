@extends('layouts.back')

@section('content')
    <!-- Main Content -->

    @if (isset($users))
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mx-auto">
                <h2 class="section-heading">Membres :</h2>
                <hr>                
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
                            <th scope="col">Login</th>
                            <th scope="col">Email</th>
                            <th scope="col">Inscrit le</th>
                            <th scope="col">Profil</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="td-lastupdate">{{ $user->created_at }}</td>
                            @if ( $user->isAdmin )
                                <td>Admin</td>
                            @else
                                <td>Membre</td>
                            @endif
                            <td><a href="/admin/post/{{ $user->id }}">Modifier</a></td>
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
                <h2 class="section-heading">Membres :</h2>
                <hr>                                          
                <div class="post-preview">
                    <p>
                        Il n'y a pas de membre pour le moment !
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection