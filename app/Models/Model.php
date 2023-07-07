<?php

namespace app\Models;

use mysqli;

class Model {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $name = DB_NAME;

    protected $connection;
    protected $query;

    protected $table;
    protected $primaryKey;
    protected $softDelete;

    function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->name);

        if($this->connection->connect_error) {
            return 'An error has ocurred trying to connect to de database server: '.
                    $this->connection->connect_error;
        }
    }

    public function query($sql) {
        $this->query = $this->connection->query($sql);
        return $this;
    }

    public function first() {
      return $this->query->fetch_assoc();
    }

    public function all() {
        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    public function getAll() {
        return $this->query("SELECT * FROM {$this->table}")->all();
    }

    public function getBy($data = [], $single = false) {

        $whereSentece = $this->setWhereSentence($data);
        $sql = "SELECT * FROM {$this->table} WHERE {$whereSentece}";

        return $single ?$this->query($sql)->first() : $this->query($sql)->all();
    }

    public function saveData($data = []) {

        $sql = '';

        if(isset($data['id']) || isset($data['where'])) { // if it is an update
            $sql .= "UPDATE {$this->table} SET ";

            foreach ($data['data'] as $column => $value) {
                $sql .= "{$column} = '{$value}', ";
            }

            $sql = trim($sql, ', ');

            if(isset($data['id'])) {
                $sql .= " WHERE {$this->primaryKey} = {$data['id']}";
            } else {
                $sql .= ' WHERE ' . $this->setWhereSentence($data['where']);
            }

            return $this->query($sql);

        } else { //if it is an insert

            $columns = '('. trim(implode(', ', array_keys($data['data'])), ', ').')';
            $values = "('". implode( "', '", array_values($data['data'])). "')";

            $sql = "INSERT INTO {$this->table} {$columns} VALUES {$values}";

            $this->query($sql);

            echo $sql;
           return $this->query("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = {$this->connection->insert_id}")
                       ->first();
        }

    }

    public function delete($data = []) {
        
        if(is_array($data)) {
            $where = $this->setWhereSentence($data);
        } else {
            $where = "{$this->primaryKey} = '{$data}'";
        }

        $sql = $this->softDelete ? "UPDATE {$this->table} SET hidden = 1 WHERE {$where}" :
                                   "DELETE FROM {$this->table} WHERE {$where}";

        return $this->query($sql);
    }


    //Private

    private function setWhereSentence($data = []) {
        $whereSentece = '';

        foreach ($data as $column => $value) { 
            $whereSentece .= "{$column} = '{$value}' AND ";
        }

       return trim($whereSentece, 'AND ');
    }
}