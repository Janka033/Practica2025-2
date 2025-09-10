<?php
class ReservaModel extends Query{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getEmpresa() {
        return $this->select("SELECT * FROM empresa");
    }

    // Verifica solapamiento estricto: (ingreso existente < nueva_salida) AND (salida existente > nueva_llegada)
    public function getDisponible($f_llegada, $f_salida, $habitacion) {
        $sql = "SELECT * FROM reservas
                WHERE fecha_ingreso < ?
                  AND fecha_salida > ?
                  AND id_habitacion = ?";
        return $this->selectAll($sql, [$f_salida, $f_llegada, (int)$habitacion]);
    }

    public function getReservasHabitacion($habitacion) {
        return $this->selectAll("SELECT * FROM reservas WHERE id_habitacion = ?", [(int)$habitacion]);
    }

    public function getHabitaciones() {
        return $this->selectAll("SELECT * FROM habitaciones WHERE estado = 1");
    }

    public function getHabitacion($id_habitacion) {
        return $this->select("SELECT * FROM habitaciones WHERE id = ?", [(int)$id_habitacion]);
    }

    public function agregarReserva($monto, $fecha_ingreso, $fecha_salida, $descripcion, $id_habitacion, $id_usuario) {
        $sql = "INSERT INTO reservas (monto, fecha_ingreso, fecha_salida, descripcion, id_habitacion, id_usuario)
                VALUES (?,?,?,?,?,?)";
        return $this->insert($sql, [$monto, $fecha_ingreso, $fecha_salida, $descripcion, (int)$id_habitacion, (int)$id_usuario]);
    }

    public function listarReservas($id_usuario) {
        return $this->selectAll(
            "SELECT r.*, h.estilo
             FROM reservas r
             INNER JOIN habitaciones h ON r.id_habitacion = h.id
             WHERE r.id_usuario = ?", [(int)$id_usuario]
        );
    }
    
    public function actualizarEstadoReserva($estado, $id_reserva, $id_usuario) {
        $sql = "UPDATE reservas SET estado = ? WHERE id = ? AND id_usuario = ?";
        return $this->save($sql, [$estado, (int)$id_reserva, (int)$id_usuario]);
    }
}
?>