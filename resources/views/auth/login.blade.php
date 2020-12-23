@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <h2 class="section-heading" id="logintitle">Connexion</h2>
        </div>
        <div class="col-lg-6 col-md-4 mx-auto">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6 form-group floating-label-form-group controls">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Mot de passe') }}</label>

                    <div class="col-md-6 form-group floating-label-form-group controls">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-8 offset-md-2 form-group form-inline justify-content-center">
                        <a href="/" class="btn btn-primary">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Se connecter') }}
                        </button>


                    </div>
                </div>
            </form>
            <div class="col-md-6 mx-auto">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié ?') }}
                    </a>
                @endif
                <a href="{{ route('register') }}">
                        {{ __('Pas encore de compte ?') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
