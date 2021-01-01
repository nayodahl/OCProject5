<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Anthony Fachaux">
    <title>Message envoyé par le formulaire de contact du Dev Blog d'Anthony Fachaux</title>
    
  </head>
  <body>     
      <div class="container">
          <div class="row">
              <div class="col-lg-8 col-md-10 mx-auto">                
                  <div class="post-preview">
                      <h3>Message envoyé par {{ $contact->firstname }} {{ $contact->lastname }} :</h3>
                      <p>
                        {{ $contact->message }}
                      </p>
                      <p>
                        Répondre à l'adresse : <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                      </p>
                  </div>                
              </div>
          </div>
      </div>        
    <hr>
  </body>
</html>