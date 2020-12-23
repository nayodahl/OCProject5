<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Anthony Fachaux, Mon Blog de Dev en PHP Vanilla pour OpenClassRooms">
  <meta name="author" content="Anthony Fachaux">
  
  <title>Anthony Fachaux, Mon Blog de Dev PHP </title>

  <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">

  <!-- Bootstrap core CSS -->
  <link href="/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="/css/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

  <!-- Custom styles for this template -->
  <link href="/css/clean-blog.min.css" rel="stylesheet">
</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container">
      <a class="navbar-brand" href="index.php"><img src="/img/logo-white.png" alt="logo du dev blog"></a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu
        <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="/">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('app_posts_show') }}">Articles</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/#contact">Contact</a>
          </li>
            @auth
            <li class="nav-item">
              <a class="nav-link disabled">
                <i class="fa fa-user fa-fw"></i> {{ Auth::user()->name }}
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ route('logout') }}">
                <i class="fa fa-sign-out-alt"></i>
              </a>
            </li>
            @endauth
            @guest
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">
                <i class="fa fa-user fa-fw"></i> Se Connecter
              </a>
            </li>  
            @endguest
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Header -->
  <header class="masthead" style="background-image: url('/img/keyboard.jpg')">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
          <div class="site-heading">
            <h1>Dev Blog</h1>
            <span class="subheading">Je m'appelle Anthony Fachaux</span>
            <span class="subheading">et je suis &lt; votre futur ? &gt; Développeur d'Application</span>
          </div>
        </div>
      </div>
    </div>
  </header>

    
    @section('content')
    @show
        
  <hr>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
          <ul class="list-inline text-center">
            <li class="list-inline-item">
              <a target="_blank" rel="noopener noreferrer" href="https://twitter.com/anthonyfachaux">
                <span class="fa-stack fa-lg">
                  <i class="fas fa-circle fa-stack-2x"></i>
                  <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                </span>
              </a>
            </li>
            <li class="list-inline-item">
              <a target="_blank" rel="noopener noreferrer" href="https://www.linkedin.com/in/anthony-fachaux-61838616b/">
                <span class="fa-stack fa-lg">
                  <i class="fas fa-circle fa-stack-2x"></i>
                  <i class="fab fa-linkedin fa-stack-1x fa-inverse"></i>
                </span>
              </a>
            </li>
            <li class="list-inline-item">
              <a target="_blank" rel="noopener noreferrer" href="https://github.com/nayodahl/OCProject5">
                <span class="fa-stack fa-lg">
                  <i class="fas fa-circle fa-stack-2x"></i>
                  <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                </span>
              </a>
            </li>
            <li class="list-inline-item">
              <a target="_blank" rel="noopener noreferrer" href="/pdf/cv.pdf">CV</a>
            </li>
            <li class="list-inline-item">
              <a href="/admin/posts/1">ADMIN</a>
            </li>
          </ul>
          <p class="copyright text-muted">Copyright &copy; Anthony Fachaux 2020 - site réalisé pour <a href="https://openclassrooms.com/">OpenClassrooms</a> - thème <a href="https://startbootstrap.com/previews/clean-blog/">boostrap</a></p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="/js/jquery.min.js"></script>
  <script src="/js/bootstrap.bundle.min.js"></script>

  <!-- Custom scripts for this template -->
  <script src="/js/clean-blog.min.js"></script>
</body>
</html>