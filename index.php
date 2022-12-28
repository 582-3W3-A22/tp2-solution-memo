<?php
    // Connexion à la BD.
    $connexion = mysqli_connect('localhost', 'root', '', 'memo');
    // Configuration de l'encodage du code de caractères.
    mysqli_set_charset($connexion, 'UTF8');
 
    /**************************************************************************/
    /* 
        TP #2 - Point 3
        Ajout d'une nouvelle tâche.

        Remarquez que le code de ce point doit être interprété avant le code du
        point 2 dans lequel on obtient les tâches à afficher !!! C'est logique : 
        on ajoute la nouvelle tâche avant de consulter les tâches sinon notre
        consultation ne contient pas la tâche nouvelle ;-)
    */
    // On commence par tester si l'utilisateur a soumit le formulaire par POST
    // et dans ce cas si le champ nommé 'texteTache' contient au moins deux
    // caractères non-vide (optionnel dans l'évaluation)
    if(isset($_POST['texteTache']) && strlen(trim($_POST['texteTache'])) > 1) {
        // TRÈS IMPORTANT : assainir la valeur reçue de l'utilisateur avant
        // de la concaténer dans une requête SQL pour éviter les attaques par
        // injection SQL
        $texteTache = mysqli_real_escape_string($connexion, $_POST['texteTache']);
        // Soumettre la requête d'ajout de données (INSERT)
        // Remarquez que la seule colonne requise dans notre table 'tache' de la
        // BD est la colonne texte : la colonne 'id' est auto_increment, la 
        // et les autres colonnes (date_ajout, accomplie, utilisateur_id) ont 
        // toutes des valeurs par défaut définies (date courante, 0, et NULL 
        // respectivement)
        mysqli_query($connexion, "INSERT INTO tache (texte) VALUES ('$texteTache')");
    }

    /**************************************************************************/
    /* 
        TP #2 - Point 5
        Basculer l'état d'une tâche (entre les valeurs 0 et 1).

        Remarquez que le code de ce point doit être interprété avant le code du
        point 2 dans lequel on obtient les tâches à afficher !!! C'est logique : 
        on modifie l'état d'une tâche avant de consulter les tâches sinon notre
        consultation ne contient pas le dernier état des tâches.
    */
    // On commence par tester si l'utilisateur a cliqué sur l'icône de 'coche'
    // associé à une tâche (auque cas le paramètre 'basculer' est dans l'URL).
    if(isset($_GET['basculer'])) {
        // La valeur du paramètre 'basculer' sera l'identifiant de la tâche qu'on
        // veut faire basculer entre 0 et 1.
        $idTache = $_GET['basculer'];
        // Remarquer que pour MySQL les valeurs 0 et 1 sont comme les booléennes
        // false et true (autrement dit NOT 0 donne 1 et NOT 1 donne 0).
        mysqli_query($connexion, "UPDATE tache SET accomplie = NOT accomplie WHERE id=$idTache");
        /* 
            Remarque additionnelle :
            Vous auriez pu trouver cette astuce par une simple recherche Google.
            Par exemple, les 4 premières réponses à la question StackOverflow 
            suivante, vous montrent 4 façons de faire basculer une valeur 0 ou 1: 
            https://stackoverflow.com/questions/603835/mysql-simple-way-to-toggle-a-value-of-an-int-field
        */
    }

    /**************************************************************************/
    /* 
        TP #2 - Point 2 (préparation)
        Obtenir la liste de toutes les tâches.
    */
    // On commence par écrire la requête SQL adéquate.
    // Remarquez comment on obtient une nouvelle colonne dans le jeu 
    // d'enregistrements en appliquant une fonction à une colonne existente et 
    // lui attribuant un alias (AS) car sinon la colonne sera difficile à référer.
    // Remarquez aussi le tri (ORDER BY)
    // Remarquez finalement que nous n'exéutons pas cette requête SQL 
    // immédiatement, mais plus loin dans le code...
    $requeteTaches = "SELECT 
                            id, 
                            texte, 
                            accomplie, 
                            DATE_FORMAT(date_ajout, '%d/%m/%Y à %k:%i:%s') AS date_ajout_f 
                        FROM tache 
                        ORDER BY date_ajout DESC";
    
    /**************************************************************************/
    /* 
        TP #2 - Point 4 (préparation)
        Filtrer les tâches (complétées/non-complétées)
    */
    // On commence par tester si l'utilisateur a cliqué sur un des liens de 
    // filtre des tâches (auque cas le paramètre 'filtrer' est dans l'URL)
    if(isset($_GET['filtrer'])) {
        // La valeur du paramètre 'filtrer' devrait être 0 (pour afficher les 
        // tâches non-complétées) ou 1 (pour afficher les tâches complétées).
        $filtre = intval($_GET['filtrer']);
        // Remarquez ici que j'ai aussi utilisé la fonction intval() de PHP pour
        // m'assurer que la valeur de la variable $filtre soit un nombre entier
        // et rien d'autre que l'utilisateur essaierai d'injecter dans mon code
        // en changer le paramètre d'URL 'filtrer'
        
        // On utilise la même requête que pour afficher TOUTES les tâches mais
        // on y ajoute la clause WHERE pour filtrer selon la colonne 'accomplie'
        // Remaquez que la variable $filtre obtenue à la ligne précédente 
        // devrait contenir tout simplement la valeur 0 ou 1.
        $requeteTaches = "SELECT 
                                id, 
                                texte, 
                                accomplie, 
                                DATE_FORMAT(date_ajout, '%d/%m/%Y à %h:%i:%s') AS date_ajout_f 
                            FROM tache 
                            WHERE accomplie = $filtre 
                            ORDER BY date_ajout DESC";
        /* 
            Remarque additionnelle : 
                Il y aurait du travail additionnel à faire pour éviter les 
                erreurs si l'utilisateur joue avec les valeurs des paramètres en 
                URL comme nous avions vu en début de session avec la valeur du 
                paramètre de choix de langue du site du restaurant Leila ; mais 
                ce n'est pas demandé dans ce TP ;-)
        */
    }

    /**************************************************************************/
    /* 
        TP #2 - Points 2 et 4 (finalisation)
        Obtenir les tâches (toutes ou filtrées seulement)
    */
    // La variable suivante contiendra le jeu d'enregistrements désiré et sera
    // utilisée dans l'affichage plus bas dans la page (gabarit de code HTML).
    $resultatTaches = mysqli_query($connexion, $requeteTaches);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>MEMO | Liste de tâches</title>
    <meta name="description" content="Application Web de gestion de tâches - à produire dans le cadre du TP du cours.">
    <link rel="stylesheet" href="ressources/css/styles.css">
    <link rel="icon" type="image/x-icon" href="ressources/images/favicon.ico">
</head>
<body>
    <div class="conteneur">
        <a href="index.php"><h1>MEMO</h1></a>
        <form method="post" autocomplete="off" action="index.php">
            <input autofocus class="quoi-faire" type="text" name="texteTache" placeholder="Tâche à accomplir ...">
        </form>
        <div class="filtres">
            <!-- Les liens suivants permettent de filtrer les tâches -->
            <a href="index.php?filtrer=1" title="Afficher les tâches complétées uniquement.">Complétées</a>
            <a href="index.php?filtrer=0" title="Afficher les tâches non-complétées uniquement.">Non-complétées</a>
            <!-- Le lien suivant revient à l'affichage par défaut : toutes les tâches -->
            <a href="index.php" title="Afficher toutes les tâches.">Toutes</a>
        </div>
        <ul class="liste-taches">
            <!-- 
                TP #2 - Points 2 et 4 (affichage)
                Affichage des tâches.
            -->
            <?php while($tache = mysqli_fetch_assoc($resultatTaches)) { ?>
            <li class="<?= ($tache['accomplie']) ? 'accomplie' : ''; ?>">
                <span class="coche"><a href="index.php?basculer=<?= $tache['id']; ?>" title="Cliquez pour faire basculer l'état de cette tâche."><img src="ressources/images/coche.svg" alt=""></a></span>
                <span class="texte"><?= $tache['texte']; ?></span>
                <span class="ajout"><?= $tache['date_ajout_f']; ?></span>
            </li>
            <?php } ?>
        </ul>
    </div>
</body>
</html>