<?php

namespace database;

use security\Credentials;

class Connection
{
    public $dbname;
    public $dbh;

    function __construct(Credentials $credentials=NULL) {
        if (!isset($credentials)) {
            $credentials = new Credentials();
        }

        $this->dbname = isset($credentials->dbname) ?? "";
        $this->dbh = Connection::PDO($credentials, $this->dbname);
        $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    static function PDO(Credentials $credentials, ?string $dbname=NULL): \PDO
    {
        if (!isset($dbname) || $dbname == "") {
            $dsn = "mysql:".
                "host=".$credentials->host.";".
                "port=".$credentials->port.";";
        } else {
            $dsn = "mysql:".
                "host=".$credentials->host.";".
                "dbname=".$credentials->dbname.";".
                "port=".$credentials->port.";";
            print $dsn;
        }
        return new \PDO($dsn, $credentials->username, $credentials->password);
    }

}