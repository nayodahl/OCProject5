@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <h2 class="section-heading">Les derniers articles</h2>
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

                <!-- Pager -->
                <div class="clearfix">
                    <a class="btn btn-primary float-right" href="/posts/?page=1">Anciens Articles &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <!-- Contact Form -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <h2 class="section-heading" id="contact">Contactez-moi</h2>

                <p>Envoyez-moi un message et je vous répondrai dans les plus brefs délais !</p>

                <form method="post" action="/#contact" name="sentMessage" id="contactForm" class="needs-validation" novalidate>
                    <div class="control-group">
                        <div class="form-row">
                            <div class="form-group col-lg-6 floating-label-form-group controls">
                                <label for="lastname">Nom</label>
                                <input name="lastname" type="text" class="form-control" placeholder="Nom" id="lastname" required data-validation-required-message="Entrez votre nom.">
                                <div class="invalid-feedback">Entrez un nom valide.</div>
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group col-lg-6 floating-label-form-group controls">
                                <label for="firstname">Prénom</label>
                                <input name="firstname" type="text" class="form-control" placeholder="Prénom" id="firstname" required data-validation-required-message="Entrez votre prénom.">
                                <div class="invalid-feedback">Entrez un prénom valide.</div>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group floating-label-form-group controls">
                            <label for="email">Email</label>
                            <input name="email" type="email" class="form-control" placeholder="Email" id="email" required data-validation-required-message="Entrez votre adresse email.">
                            <div class="invalid-feedback">Entrez un email valide.</div>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group floating-label-form-group controls">
                            <label for="message">Message</label>
                            <textarea name="message" rows="5" class="form-control" placeholder="Message" id="message" maxlength="500" required data-validation-required-message="Entrez votre message ici."></textarea>
                            <div class="invalid-feedback">Entrez un message valide.</div>
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
