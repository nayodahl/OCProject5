@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">                
                <article class="post-preview">
                    <header>
                        <h2 class="post-title">
                            {{ $post->title }}
                        </h2>
                    </header>
                    <section>
                        <h4 class="post-subtitle">
                            {{ $post->chapo }}
                        </h4>                   
                        <p>
                            {{ $post->content }}
                        </p>
                    </section>
                    <p class="post-meta">Publié par {{ $post->user->name }} le {{ $post->updated_at->format('j/m/Y, à H:i') }}</p>
                </article>
                
                <!-- Pager for Posts
                <div class="clearfix">
                    {% include 'FrontOffice/PaginationSinglePost.twig' with {
                        prevId: prevId,
                        nextId: nextId,
                    } only %}
                    
                </div>
                -->
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    
    @isset($comments)
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto"  id="commentspart">                
                <h2 class="section-heading" id="commentsection">Commentaires</h2>
                <br>
                @foreach ($comments as $comment)
                <div class="post-preview">
                    <p class="post-meta">{{ $comment->user->name }}, le {{ $comment->updated_at->format('j/m/Y, à H:i') }} :</p>
                    <p>
                        {{ $comment->content }}
                    </p>
                </div>
                @endforeach
                <!-- Pager for Comments
                <div class="clearfix">
                    {% include 'FrontOffice/PaginationComment.twig' with {
                        currentPage: currentPage,
                        lastPage: totalPages,
                        postId: postId
                    } only %}
                </div>
                -->
            </div>
        </div>
    </div>
    <hr>
    @endisset

    <!-- Comment Form -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <h2 class="section-heading" id="comments">Ajouter un commentaire</h2>
                @isset($session->success)                
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session.success }}
                    </div>    
                @endisset
                @isset($session->error)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session.error }}
                    </div>    
                @endisset 
                <form method="post" action="#" name="sentMessage" id="contactForm" class="needs-validation" novalidate>
                    <div class="control-group">
                        <div class="form-group floating-label-form-group controls">
                        <label for="comment">Tapez votre message</label>
                        <textarea rows="5" name="comment" id="comment" class="form-control" placeholder="Votre message (vous devez être connecté pour ajouter un commentaire, et il sera soumis à validation"  required data-validation-required-message="Entre votre message ici."></textarea>
                        <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <br>
                    <div id="success"></div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="sendMessageButton">Envoyer</button>
                    </div>
                </form>                
                
                <script>
                    // starter JavaScript for disabling form submissions if there are invalid fields, from bootstrap4 doc
                    (function() {
                    'use strict';
                    window.addEventListener('load', function() {
                        // Fetch all the forms we want to apply custom Bootstrap validation styles to
                        var forms = document.getElementsByClassName('needs-validation');
                        // Loop over them and prevent submission
                        var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                        });
                    }, false);
                    })();
                </script>
            </div>
        </div>
    </div>
@endsection