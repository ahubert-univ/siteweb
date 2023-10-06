<?php

readonly class Storage
{

    public function __construct(private string $host, private string $db, private string $login ='root', private string $pass=''){
    }

    public function connexion():\PDO
    {
        try
        {
            $bdd = new \PDO(
                'mysql:host='.$this->host.';dbname='.$this->db.';charset=utf8mb4',
                $this->login,
                $this->pass
            );
            $bdd->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);


            return $bdd;

        }
        catch (\PDOException $e)
        {
            $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            die($msg);
        }
    }

}