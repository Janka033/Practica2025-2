<?php
class Query extends Conexion{
    private $con, $pdo;

    public function __construct() {
        $this->con = new Conexion();
        $this->pdo = $this->con->conectar();
    }

    //RECUPERAR UN SOLO REGISTRO (ahora con parámetros opcionales)
    public function select($sql, $params = []){
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    //RECUPERAR TODOS LOS REGISTROS (ahora con parámetros opcionales)
    public function selectAll($sql, $params = []){
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //REGISTRAR
    public function insert($sql, $array){
        $stmt = $this->pdo->prepare($sql);
        $data = $stmt->execute($array);
        if ($data) {
            $res = $this->pdo->lastInsertId();
        } else {
            $res = 0;
        }
        return $res;
    }

    //MODIFICAR, ELIMINAR
    public function save($sql, $array){
        $stmt = $this->pdo->prepare($sql);
        $data = $stmt->execute($array);
        if ($data) {
            $res = 1;
        } else {
            $res = 0;
        }
        return $res;
    }
}
?>