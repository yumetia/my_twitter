<?php

$auth = parse_ini_file("Config/config.ini");

class GenericModel extends Database{
    protected $database;

    public function __construct(){
        $this->database = (new Database())->connect();
    }

    public function count($table,$queryExtras="")
    {
        $request = "SELECT count(*)
        FROM $table
        $queryExtras
        ";
        $stmt = $this->database->prepare($request);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_NUM)[0];
    }

    // when I dont want to get tired :)
    public function query($query){
        $request = $query;
        $stmt = $this->database->prepare($request);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // _____


    public function getRow($table, $column,$value,$all="*")
    {
        $request = "SELECT $all FROM $table WHERE $column= :value";
        $stmt = $this->database->prepare($request);
        $stmt->bindParam(":value",$value);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function innerJoin($table,$table2,$key,$all="*",$where="")
    {
        $sql = "SELECT $all FROM $table
        JOIN $table2
        ON $table.$key=$table2.$key
        $where";

        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addRow($data)
    {
        if (!isset($data["table"]) || empty($data)) {
            return false;
        }

        $table = $data["table"];
        unset($data["table"]);

        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        try {
            $stmt = $this->database->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
