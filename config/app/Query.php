<?php
class Query extends Conexion
{
    private $con;
    private $pdo;

    public function __construct()
    {
        $this->con = new Conexion();
        $this->conectarPDO();
    }

    private function conectarPDO(): void
    {
        $this->pdo = $this->con->conectar(); // Asumo que retorna un PDO
        // Refuerzos (idempotentes)
        try {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (Exception $e) {
            // Silencioso; si algo falla igual PDO seguirá funcionando básico
        }
        // Zona horaria y charset (si ya lo hace Conexion no pasa nada por repetir)
        try {
            $this->pdo->exec("SET time_zone='-05:00'");
            $this->pdo->exec("SET NAMES utf8mb4");
        } catch (PDOException $e) {
            // No romper por esto
        }
    }

    private function esErrorConexion(PDOException $e): bool
    {
        // Códigos típicos de conexión perdida
        $code = (string)$e->getCode();
        return in_array($code, ['2006', '2013'], true);
    }

    private function reintentar(callable $fn)
    {
        try {
            return $fn();
        } catch (PDOException $e) {
            if ($this->esErrorConexion($e)) {
                // Reconectar y reintentar UNA vez
                $this->conectarPDO();
                return $fn();
            }
            throw $e; // Propaga otros errores (SQL mal formado, constraints, etc.)
        }
    }

    // RECUPERAR UN SOLO REGISTRO
    public function select($sql)
    {
        return $this->reintentar(function() use ($sql) {
            $result = $this->pdo->prepare($sql);
            $result->execute();
            return $result->fetch(PDO::FETCH_ASSOC);
        });
    }

    public function update($sql)
    {
        return $this->reintentar(function() use ($sql) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute();
        });
    }

    // RECUPERAR TODOS LOS REGISTROS
    public function selectAll($sql)
    {
        return $this->reintentar(function() use ($sql) {
            $result = $this->pdo->prepare($sql);
            $result->execute();
            return $result->fetchAll(PDO::FETCH_ASSOC);
        });
    }

    // REGISTRAR
    public function insert($sql, $array)
    {
        return $this->reintentar(function() use ($sql, $array) {
            $result = $this->pdo->prepare($sql);
            $ok = $result->execute($array);
            if ($ok) {
                return (int)$this->pdo->lastInsertId();
            }
            return 0;
        });
    }

    // MODIFICAR / ELIMINAR
    public function save($sql, $array)
    {
        return $this->reintentar(function() use ($sql, $array) {
            $result = $this->pdo->prepare($sql);
            $ok = $result->execute($array);
            return $ok ? 1 : 0;
        });
    }

    // (Opcional) método para ejecutar cualquier SQL con parámetros y devolver filas afectadas
    public function execParams(string $sql, array $params = []): int
    {
        return $this->reintentar(function() use ($sql, $params) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        });
    }

    // (Opcional) PING para saber si sigue viva la conexión
    public function ping(): bool
    {
        try {
            $this->pdo->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>