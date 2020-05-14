<?php

class View {

    // Nom du fichier associé à la vue
    private $file;
    
    // Titre de la vue (défini dans le fichier view)
    private $title;
    private $header;
    private $subheader;
    private $button;

    public function __construct($p)
    {
        // Détermination du nom du fichier vue à partir de l'action
        $this->file = "view/view" . $p . ".php";
    }

    // Génère et affiche la vue
    public function generate()
    {
        require ('view/layout.php');
    }
}
