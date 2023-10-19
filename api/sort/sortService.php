<?php

require_once __DIR__ . "/../../libs/sortLib.php";
require_once __DIR__ . "/../../libs/apiLib.php";
require_once __DIR__."/../service.php";
class SortService extends Service {

    // Propriétés propres à l'endpoint service (type de tri et tableau à trier)
    private $sortFunc;
    private $queryArr;

    // Surcharge Service.__construct() pour ajouter le traitement spécifique de la requête.
    public function __construct()
    {
        // Récupère le nom de l'endpoint (.../{endpoint}/index.php)
        preg_match("/^.*\/(?P<folder_name>.+)\/.+\.php$/", $_SERVER["PHP_SELF"], $matches);

        // Vérifie si une fonction du nom récupéré {endpoint} existe dans SortLib, sinon attribue 'false'
        // Todo: possible de retourner une erreur dès le constructeur ?
        $this->sortFunc = method_exists(SortLib::class, $matches["folder_name"] ?? false) ?
            $matches["folder_name"] : false;
        $this->queryArr = json_decode($_GET["arr"] ?? false);

        // Appelle le constructeur du parent
        parent::__construct();
    }

    function Trig(): void
    {
        // Vérification de base des attributs et du verbe de la requête
        if ($_SERVER["REQUEST_METHOD"] !== "GET" || !$this->queryArr || !is_array($this->queryArr)) {
            header("HTTP/1.0 400 Bad Request");
            return;
        }
        // Aucune fonction associée à la route
        if (!$this->sortFunc) {
            header("HTTP/1.0 500 Internal Server Error");
            return;
        }

        // Trie l'array et le renvoie
        $sortedArr = SortLib::{$this->sortFunc}($this->queryArr);
        $response = ApiLib::successResponse(array("sort_function"=>$this->sortFunc, "sorted_arr"=>$sortedArr));
        header('Content-Type: application/json');

        // Renvoie la réponse, succès ou échec
        echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}