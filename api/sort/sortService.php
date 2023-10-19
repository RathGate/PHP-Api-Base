<?php

require_once __DIR__ . "/../../lib/sortLib.php";
require_once __DIR__."/../../lib/apiLib.php";
require_once __DIR__."/../service.php";
class SortService extends Service {

    // Propriétés propres à l'endpoint service (type de tri et tableau à trier)
    // Todo: Pourquoi ça plante quand j'essaie d'attribuer un type à ces attributs ?
    private $sortFunc;
    private $arrQuery;

    // Surcharge Service.__construct() pour ajouter le traitement spécifique de la requête.
    public function __construct()
    {
        // Attribue la valeur des attributs
        // Todo: Peut-être qu'il faudrait vérifier ici s'ils sont invalides
        $this->sortFunc = $_GET["f"] ?? SortLib::$defaultFunc;
        $this->arrQuery = json_decode($_GET["q"] ?? false);

        parent::__construct();
    }

    function Trig(): void
    {
        // Vérification de base des attributs et du verbe de la requête
        if (!$this->sortFunc || $_SERVER["REQUEST_METHOD"] !== "GET" || !$this->arrQuery) {
            header("HTTP/1.0 400 Bad Request");
            return;
        }

        // Vérification de l'existence de la fonction dans la librairie.
        if (!method_exists(SortLib::class, $this->sortFunc)) {
            $response = ApiLib::errorResponse(400, "La fonction de tri spécifiée est invalide.");
            echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            header("HTTP/1.0 400 Bad Request");
            return;
        }

        $sortedArr = SortLib::{$this->sortFunc}($this->arrQuery);
        $response = ApiLib::successResponse(array("sort_function"=>$this->sortFunc, "sorted_arr"=>$sortedArr));
        echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        header('Content-Type: application/json');
    }
}