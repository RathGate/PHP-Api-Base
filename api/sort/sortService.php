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
        // Récupère le nom de l'endpoint (.../{endpoint}/index.php)
        preg_match("/^.*\/(?P<folder_name>.+)\/.+\.php$/", $_SERVER["PHP_SELF"], $matches);

        // Attribue la valeur des attributs
        // Todo: Peut-être qu'il faudrait vérifier ici s'ils sont invalides
        $this->sortFunc = $matches["folder_name"] ?? false;
        $this->arrQuery = json_decode($_GET["q"] ?? false);

        parent::__construct();
    }

    function Trig(): void
    {
        // Vérification de base des attributs et du verbe de la requête
        if (!$this->sortFunc || $_SERVER["REQUEST_METHOD"] !== "GET" || !$this->arrQuery) {
            echo $this->arrQuery;
            header("HTTP/1.0 400 Bad Request");
            return;
        }

        // Protection supplémentaire dans le cas où la fonction associée à l'endpoint
        // n'existe pas dans sortLib et lancerait une erreur.
        // Todo: Préciser l'exception au lieu de faire un catch généraliste ? Ou pas ?
        try {
            $sortedArr = SortLib::{$this->sortFunc}($this->arrQuery);
            $response = ApiLib::successResponse(array("sort_function"=>$this->sortFunc, "sorted_arr"=>$sortedArr));
            header('Content-Type: application/json');
        } catch (Exception $e) {
            $response = ApiLib::errorResponse(400, $e);
            header("HTTP/1.0 400 Bad Request");
        }

        // Renvoie la réponse, succès ou échec
        echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}