<?php
abstract class Service {
    protected $allowedVerbs = [];
    protected $requiredParams = [];
    protected $params;

    public function __construct()
    {
        // Vérifie le verbe de la requête
        if (!self::IsValidMethod()) {
            $response = ApiLib::errorResponse(405, "Méthode ".$_SERVER["REQUEST_METHOD"]." non autorisée.");
            header("HTTP/1.0 405 Method Not Allowed");
            echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            exit;
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
        $this->params = new stdClass();
        foreach ($this->requiredParams as $param) {
            if (!isset($_GET[$param])) {
                $response = ApiLib::errorResponse(405, "Paramètre obligatoire `".$param."` manquant.");
                header("HTTP/1.0 400 Bad Request");
                echo stripslashes(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                exit;
            }
            $this->params->$param = json_decode($_GET[$param]);
        }
    }
    public abstract function CheckParameters();

}

