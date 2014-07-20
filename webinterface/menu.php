<?php
    function affiche_menu()
    {
        // tableaux contenant les liens d'accèet le texte àfficher
        $tab_menu_lien = array( "index.php", "modedisabled.php", "modeforced.php", "modenormal.php", "modeeco.php" );
        $tab_menu_texte = array( "Configuration", "Mode Disabled", "Mode Forced", "Mode Normal", "Mode ECO" );
        
        // informations sur la page
        $info = pathinfo($_SERVER['PHP_SELF']);

        $menu = "\n<div id=\"menu\">\n    <ul id=\"onglets\">\n";

        

        // boucle qui parcours les deux tableaux
        foreach($tab_menu_lien as $cle=>$lien)
        {
            $menu .= "    <li";
                
            // si le nom du fichier correspond àelui pointéar l'indice, alors on l'active
            if( $info['basename'] == $lien )
                $menu .= " class=\"active\"";
                
            $menu .= "><a href=\"" . $lien . "\">" . $tab_menu_texte[$cle] . "</a></li>\n";
        }
        
        $menu .= "</ul>\n</div>";
        
        // on renvoie le code xHTML
        return $menu;        
    }
?>

