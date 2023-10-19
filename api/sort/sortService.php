<?php

require_once __DIR__ . "/../../libs/sortLib.php";
require_once __DIR__ . "/../../libs/apiLib.php";
require_once __DIR__."/../service.php";

class SortService extends Service {

    // Surcharge Service.__construct() pour ajouter le traitement spécifique de la requête.
    public function __construct()
    {
        $this->allowedVerbs = ["GET"];
        $this->requiredParams = ["arr"];
        parent::__construct();;
    }

    function Trig(): void
    {
        // Trie l'array avec la fonction associée à l'endpoint
        $sortedArr = SortLib::{$this->params->sortFunc}($this->params->arr);

        // Ecrit le json de la réponse et l'envoie
        $response = ApiLib::successResponse(array("sort_function"=>$this->params->sortFunc, "sorted_arr"=>$sortedArr));
        header('Content-Type: application/json');

        echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    // Surcharge Service::SetParameters() pour y ajouter le nom de la fonction de tri à utiliser,
    // récupérée depuis l'URI de la requête.
    public function SetParameters(): void
    {
        parent::SetParameters();

        // Récupère le nom de l'endpoint (.../{endpoint}/index.php)
        preg_match("/^.*\/(?P<folder_name>.+)\/.+\.php$/", $_SERVER["PHP_SELF"], $matches);
        // Vérifie si une fonction du nom récupéré {endpoint} existe dans SortLib, sinon attribue 'false'
        $this->params->sortFunc = $matches["folder_name"] ?? false;
    }

    // Renvoie l'erreur en réponse et termine le script si un paramètre est invalide.
    public function CheckParameters()
    {
        // La méthode associée à l'endpoint n'existe pas
        if (!method_exists(SortLib::class, $this->params->sortFunc)) {
            $response = ApiLib::errorResponse(500, "Aucune fonction de tri associée à `".$this->params->sortFunc."`.");
            header("HTTP/1.0 500 Internal Server Error");
            echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            exit;
        }
        // Le type du paramètre est invalide
        if (!is_array($this->params->arr)) {
            $response = ApiLib::errorResponse(400, "Le paramètre `arr` doit être de type array.");
            header("HTTP/1.0 400 Bad Request");
            echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            exit;
        }
    }
}