<?php
//définition des constantes d'erreurs
const ERROR_REQUIRED = 'Veuillez renseigner une todo';
const ERROR_TOO_SHORT = 'Veuillez entrer au moins 5 caractères';
const ERROR_TOO_LONG = 'Veuillez entrer maximum 200 caractères';
$filename = __DIR__ . "todos.json";

$error = '';
$todo = '';
$todos = [];
//si le fichier todo.js existe :
// On lit le contenu du fichier 
//et on le décode en tant que tableau de tâches
if (file_exists($filename)) {
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];
}
//Si la requête est de type POST (envoi de formulaire) :
//On filtre et nettoie la valeur "todo" reçue en utilisant 
//certaines règles de sécurité.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = filter_input_array(INPUT_POST, [
        "todo" => [
            "filter" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            "flags" => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_BACKTICK
        ]
    ]);
    $todo = $_POST['todo'] ?? '';
    //On vérifie si la "todo" est vide, 
    //si oui, on assigne le message d'erreur correspondant.
    if (!$todo) {
        $error = ERROR_REQUIRED;
        ///Sinon, on vérifie si la "todo" est trop courte (moins de 5 caractères), 
        //si oui, on assigne le message d'erreur correspondant
    } else if (mb_strlen($todo) < 5) {
        $error = ERROR_TOO_SHORT;
    } else if (mb_strlen($todo) > 200) {
        $error = ERROR_TOO_LONG;
    }
    //Si aucune erreur n'est présente :
    //On ajoute la nouvelle tâche au tableau des tâches à faire.
    if (!$error) {
        array_push($todos, [
            'name' => $todo,
            'done' => false,
            'id' => time()
        ]);
        
        //On enregistre le tableau mis à jour dans le fichier "todos.json", en le convertissant en format JSON et en p
        //réservant les caractères Unicode et en le rendant plus lisible.
        //d'autres filtres sont ensuite ajouter ( à revoir dans le détail)
        file_put_contents($filename, json_encode($todos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $todo = '';
        header('Location: http://54.37.67.40');
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="style.css">
<head>
    <?php require_once 'head.php'; ?>

    <title>Todo</title>
</head>

<body>
    <div class="container">

        <?php require_once 'header.php'; ?>
        <div class="content">
            <div class="todo-container">


                <h1>Ma ToDo</h1>
                <form action="/" method="POST" class="todo-form">
                    <input value="<?= $todo ?>" name="todo" type="text">
                    <button class="btn btn-primary">Ajouter </button>
                </form>
              

                <!--Pour afficher les erreurs -->
                <?php if ($error) : ?>
                    <p class="text-danger"><?= $error ?></p>
                <?php endif; ?>
                <ul class="todo-list">
                    <?php foreach ($todos as $t) : ?>
                        <li class="todo-item<?= $t['done'] ? 'low-opacity' : '' ?>">
                            <span class="todo-name"><?= $t['name'] ?></span>
                            <a href="/edit-todo.php?id=<?= $t['id'] ?>">
                                <button class="btn btn-primary btn-small"><?= $t['done'] ? 'Annuler' : 'Valider' ?></button>
                            </a>
                            <a href="/remove-todo.php?id=<?= $t['id'] ?>">
                                <button class="btn btn-danger btn-small">Supprimer</button>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>

        </div>

             
        <?php require_once 'footer.php'; ?>
         
             
    </div>
    

    <script>
    var dateInput = document.getElementById('dateInput');

    dateInput.addEventListener('change', function() {
      var selectedDate = dateInput.value;
      console.log('Date sélectionnée :', selectedDate);
    });
  </script>
</body>

</html>