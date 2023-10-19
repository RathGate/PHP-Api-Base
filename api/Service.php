<?php

namespace api;
require_once __DIR__."/../autoload.php";
use libs\ApiLib;

abstract class Service {
    protected $allowedVerbs = [];
    protected $requiredParams = [];
    protected $params;

    public function __construct()
    {
        // Vérifie le verbe de la requête
        if (!self::IsValidMethod()) {
            ApiLib::WriteErrorResponse(405, "Méthode ".$_SERVER["REQUEST_METHOD"]." non autorisée.");
        }

        // Récupère, traite et vérifie les paramètres
        $this::SetParameters();
        // Todo: séparé de SetParameters mais peut certainement être factorisé en une fonction.
        $this->CheckParameters();
        // Si aucune erreur n'a été détectée, lance l'exécution du service en lui-même.
        $this->Trig();
    }

    abstract function Trig();

    public function IsValidMethod(): bool
    {
        return in_array($_SERVER["REQUEST_METHOD"], $this->allowedVerbs);
    }

    // Enregistre les paramètres dans l'object $this->params.
    public function SetParameters(): void {
        $this->params = new \stdClass();
        foreach ($this->requiredParams as $param) {
            if (!isset($_GET[$param])) {
                ApiLib::WriteErrorResponse(400, "Paramètre obligatoire `".$param."` manquant.");
            }
            $this->params->$param = json_decode($_GET[$param]);
        }
    }
    public abstract function CheckParameters();

}

