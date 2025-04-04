<?php
$config = parse_ini_file("Config/config.ini");

class Database {
    private $pdo;

    public function connect()
    {
        // add ur informations of ur db in the config.ini file :)

        $config = parse_ini_file('Config/config.ini');

        try {
            $this->pdo = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
                $config['username'],
                $config['password']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            error_log("Error connecting to database : " . $e->getMessage());
            exit(84);
        }
    }
}
