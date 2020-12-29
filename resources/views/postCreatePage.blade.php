@extends('layouts.back')

@section('content')
    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <h2 class="section-heading" id="add">Nouvel article :</h2>
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session.error }}
                    </div>    
                @endif 
                <hr>                               
                <form method="post" action="/admin/create" name="sentMessage" id="PostForm" class="needs-validation" novalidate>
                    <div class="control-group">
                        <div class="form-group controls">
                            <label>Titre :</label>
                            <input name="title" type="text" class="form-control" id="title" required data-validation-required-message="Entrez le titre.">
                            <div class="invalid-feedback">Entrez un titre valide.</div>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group controls">
                            <label>Chapô :</label>
                            <input name="chapo" type="text" class="form-control"  id="chapo" required data-validation-required-message="Ecrivez le chapo.">
                            <div class="invalid-feedback">Entrez un chapô valide.</div>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group controls">
                            <label for="author">Auteur :</label>
                            <select id="author" name="author" class="form-control" form="PostForm" required data-validation-required-message="Sélectionnez un auteur...">
                                <option value="" disabled selected hidden>Sélectionnez un auteur...</option>
                                @foreach ($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Sélectionnez un auteur.</div>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group  controls">
                            <label>Contenu : </label>
                            <textarea name="content" rows="20" class="form-control" id="content" maxlength="4000" required data-validation-required-message="Tapez le contenu de l'article ici."></textarea>
                            <div class="invalid-feedback">Tapez un contenu d'article valide.</div>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <br>
                    <div id="success"></div>
                    <div class="row justify-content-center">
                        <div col-md-2>
                            <a class="btn btn-primary" href="/admin/posts/1">Annuler</a></a>
                        </div>
                        <div col-md-2>
                            <button type="submit" class="btn btn-primary" id="sendMessageButton">Publier</button>
                        </div>                        
                    </div>
                    @csrf
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