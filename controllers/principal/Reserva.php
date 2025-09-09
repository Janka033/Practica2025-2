<?php
require_once 'vendor/autoload.php';
// SDK de Mercado Pago

class Reserva extends Controller
{
    public function __construct()
    {
        parent::__construct();
        session_start();
    }

    // ======================================================
    // --- NUEVO: Utilidad para sanitizar y validar fechas ---
    // ======================================================
    /**
     * Valida formato y reglas de negocio de las fechas.
     * Reglas:
     *  - Formato Y-m-d
     *  - Llegada >= hoy
     *  - Salida > llegada
     *
     * @return array [
     *   'ok' => bool,
     *   'llegada' => DateTime|null,
     *   'salida'  => DateTime|null,
     *   'errores' => string[]
     * ]
     */
    private function validarRangoFechas(string $f_llegada_raw, string $f_salida_raw): array
    {
        $errores = [];
        $tz = new DateTimeZone('America/Bogota'); // Ajusta si necesitas otra zona
        $hoy = new DateTime('today', $tz);

        // Limpieza básica (asumiendo que tienes una función global strClean, si no, usa trim)
        $f_llegada_str = strClean($f_llegada_raw);
        $f_salida_str  = strClean($f_salida_raw);

        // Formato
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $f_llegada_str)) {
            $errores[] = 'Formato de fecha de llegada inválido';
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $f_salida_str)) {
            $errores[] = 'Formato de fecha de salida inválido';
        }

        $llegada = $salida = null;

        if (empty($errores)) {
            $llegada = DateTime::createFromFormat('Y-m-d', $f_llegada_str, $tz);
            $salida  = DateTime::createFromFormat('Y-m-d', $f_salida_str, $tz);

            if (!$llegada) {
                $errores[] = 'Fecha de llegada inválida';
            }
            if (!$salida) {
                $errores[] = 'Fecha de salida inválida';
            }
        }

        if (empty($errores) && $llegada && $salida) {
            if ($llegada < $hoy) {
                $errores[] = 'La fecha de llegada no puede ser pasada';
            }
            if ($salida <= $llegada) {
                $errores[] = 'La fecha de salida debe ser posterior a la llegada';
            }
        }

        return [
            'ok' => empty($errores),
            'llegada' => $llegada,
            'salida' => $salida,
            'errores' => $errores
        ];
    }

    //LISTAR RESERVAS
    public function listarReservas() {
        $data = $this->model->listarReservas($_SESSION['id_usuario']);
        $item = 1;
        for ($i=0; $i < count($data); $i++) { 
            $data[$i]['item'] = $item;
            $item++;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function verify()
    {
        if (isset($_GET['f_llegada']) && isset($_GET['f_salida']) && isset($_GET['habitacion'])) {
            $f_llegada_raw = $_GET['f_llegada'];
            $f_salida_raw  = $_GET['f_salida'];
            $habitacion    = strClean($_GET['habitacion']);

            // --- CAMBIO: Validación temprana de campos vacíos
            if (empty($f_llegada_raw) || empty($f_salida_raw) || empty($habitacion)) {
                header('Location: ' . RUTA_PRINCIPAL . '?respuesta=warning');
                exit;
            }

            // --- NUEVO: Validar fechas en servidor
            $val = $this->validarRangoFechas($f_llegada_raw, $f_salida_raw);
            if (!$val['ok']) {
                // Puedes definir un mecanismo para mostrar errores; de momento redirigimos con bandera
                // Opcional: guardar errores en sesión para mostrarlos en la vista
                $_SESSION['errores_reserva'] = $val['errores'];
                header('Location: ' . RUTA_PRINCIPAL . '?respuesta=warning');
                exit;
            }

            $f_llegada = $val['llegada']->format('Y-m-d');
            $f_salida  = $val['salida']->format('Y-m-d');

            // Consulta de disponibilidad
            $reserva = $this->model->getDisponible($f_llegada, $f_salida, $habitacion);
            $data['title'] = 'Reservas';
            $data['subtitle'] = 'Verificar Disponiblilidad';
            $data['disponible'] = [
                'f_llegada' => $f_llegada,
                'f_salida' => $f_salida,
                'habitacion' => $habitacion
            ];
            if (empty($reserva)) {
                //CREAR SESION DE LA HABITACIÓN
                $_SESSION['reserva'] = $data['disponible'];
                $data['mensaje'] = 'DISPONIBLE';
                $data['tipo'] = 'success';
            } else {
                $data['mensaje'] = 'NO DISPONIBLE';
                $data['tipo'] = 'danger';
                unset($_SESSION['reserva']);
            }
            $data['empresa'] = $this->model->getEmpresa();
            $data['habitaciones'] = $this->model->getHabitaciones();
            $data['habitacion'] = $this->model->getHabitacion($habitacion);
            $this->views->getView('principal/reservas', $data);
        }
    }

    public function listar($parametros)
    {
        $array = explode(',', $parametros);
        $f_llegada = (!empty($array[0])) ? $array[0] : null;
        $f_salida = (!empty($array[1])) ? $array[1] : null;
        $habitacion = (!empty($array[2])) ? $array[2] : null;
        $results = [];
        if ($f_llegada != null && $f_salida != null && $habitacion != null) {
            $reservas = $this->model->getReservasHabitacion($habitacion);
            for ($i = 0; $i < count($reservas); $i++) {
                $datos['id'] = $reservas[$i]['id'];
                $datos['title'] = 'OCUPADO';
                $datos['start'] = $reservas[$i]['fecha_ingreso'];
                $datos['end'] = $reservas[$i]['fecha_salida'];
                $datos['color'] = '#dc3545';
                array_push($results, $datos);
            }
            $data['id'] = $habitacion;
            $data['title'] = 'COMPROBANDO';
            $data['start'] = $f_llegada;
            $data['end'] = date("Y-m-d", strtotime($f_salida . " +1 day"));;
            $data['color'] = '#ffc107';
            array_push($results, $data);
            echo json_encode($results, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function pendiente()
    {
        if (empty($_SESSION['reserva'])) {
            redirect(RUTA_PRINCIPAL);
        }

        $data['title'] = 'Reserva pendiente';
        $data['habitacion'] = [];
        if (!empty($_SESSION['reserva'])) {
            $data['habitacion'] = $this->model->getHabitacion($_SESSION['reserva']['habitacion']);
        }

        // Construir galería
        $galeria = [];
        if (!empty($data['habitacion'])) {
            if (!empty($data['habitacion']['foto'])) {
                $galeria[] = [
                    'path' => 'assets/img/habitaciones/' . $data['habitacion']['foto'],
                    'principal' => true
                ];
            }
            $folder = 'assets/img/habitaciones/' . $data['habitacion']['id'] . '/';
            if (is_dir($folder)) {
                $files = scandir($folder);
                if ($files !== false) {
                    foreach ($files as $f) {
                        if ($f === '.' || $f === '..') { continue; }
                        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            $galeria[] = [
                                'path' => $folder . $f,
                                'principal' => false
                            ];
                        }
                    }
                }
            }
        }
        $data['galeria'] = $galeria;

        // Cálculo total (NOTA: Estás cobrando (días + 1). Revisa si es tu regla real de negocio)
        $fecha1 = new DateTime($_SESSION['reserva']['f_llegada']);
        $fecha2 = new DateTime($_SESSION['reserva']['f_salida']);
        $cantidad = $fecha2->diff($fecha1);
        $precio = floatval($data['habitacion']['precio']);
        $data['total'] = ($cantidad->d + 1) * $precio;

        $this->views->getView('principal/clientes/reservas/pendiente', $data);
    }

    public function agregarNotas()
    {
        $datos = file_get_contents('php://input');
        $array = json_decode($datos, true);
        $_SESSION['notas'] = $array['descripcion'];
        echo 'ok';
    }

    public function success()
    {
        if (empty($_SESSION['reserva'])) {
            echo 'NO SE PUEDO RECUPERAR LOS DATOS DE LA RESERVA';
            exit;
        }
        // --- NUEVO: asegurar fechas válidas también aquí
        $val = $this->validarRangoFechas($_SESSION['reserva']['f_llegada'], $_SESSION['reserva']['f_salida']);
        if (!$val['ok']) {
            echo 'Fechas inválidas';
            exit;
        }

        $fecha1 = $val['llegada'];
        $fecha2 = $val['salida'];
        $cantidad = $fecha2->diff($fecha1);
        $habitacion = $this->model->getHabitacion($_SESSION['reserva']['habitacion']);
        $descripcion = $_SESSION['notas'] ?? '';
        $reserva = [
            'monto' => $habitacion['precio'] * ($cantidad->d + 1),
            'fecha_ingreso' => $fecha1->format('Y-m-d'),
            'fecha_salida' => $fecha2->format('Y-m-d'),
            'descripcion' => $descripcion,
            'id_habitacion' => $_SESSION['reserva']['habitacion'],
            'id_usuario' => $_SESSION['id_usuario']
        ];
        $data = $this->agregarReserva($reserva);
        if ($data > 0) {
            redirect(RUTA_PRINCIPAL . 'reserva/completado');
        }
    }

    public function failure()
    {
        echo 'failure';
    }

    public function pending()
    {
        echo 'pending';
    }

    public function registrarReserva()
    {
        $datos = file_get_contents('php://input');
        $array = json_decode($datos, true);

        if (empty($_SESSION['reserva'])) {
            echo json_encode(['tipo'=>'error','msg'=>'No hay reserva en sesión']);
            die();
        }

        // --- NUEVO: validar
        $val = $this->validarRangoFechas($_SESSION['reserva']['f_llegada'], $_SESSION['reserva']['f_salida']);
        if (!$val['ok']) {
            echo json_encode(['tipo'=>'error','msg'=>'Fechas inválidas']);
            die();
        }

        $descripcion = $_SESSION['notas'] ?? '';
        $reserva = [
            'monto' => $array['reserva']['purchase_units'][0]['amount']['value'],
            'fecha_ingreso' => $val['llegada']->format('Y-m-d'),
            'fecha_salida' => $val['salida']->format('Y-m-d'),
            'descripcion' => $descripcion,
            'id_habitacion' => $_SESSION['reserva']['habitacion'],
            'id_usuario' => $_SESSION['id_usuario']
        ];

        $data = $this->agregarReserva($reserva);
        if ($data > 0) {
            $res = ['tipo' => 'success', 'msg' => 'RESERVA REGISTRADO'];
        } else {
            $res = ['tipo' => 'error', 'msg' => 'ERROR AL REGISTRAR RESERVA'];
        }
        echo json_encode($res);
        die();
    }

    private function agregarReserva($reserva)
    {
        return $this->model->agregarReserva(
            $reserva['monto'],
            $reserva['fecha_ingreso'],
            $reserva['fecha_salida'],
            $reserva['descripcion'],
            $reserva['id_habitacion'],
            $reserva['id_usuario']
        );
    }

    public function completado()
    {
        unset($_SESSION['notas']);
        unset($_SESSION['reserva']);
        if (empty($_SESSION['reserva'])) {
            header('Location: ' . RUTA_PRINCIPAL . 'dashboard');
            exit;
        }
    }

    // NUEVO: confirmar la reserva
    public function confirmar()
    {
        header('Content-Type: application/json');
        if (empty($_SESSION['id_usuario'])) {
            echo json_encode(['type' => 'error', 'msg' => 'Sesión expirada']);
            die();
        }
        if (empty($_SESSION['reserva'])) {
            echo json_encode(['type' => 'error', 'msg' => 'No hay reserva pendiente']);
            die();
        }

        // --- NUEVO: validar fechas
        $val = $this->validarRangoFechas($_SESSION['reserva']['f_llegada'], $_SESSION['reserva']['f_salida']);
        if (!$val['ok']) {
            echo json_encode(['type'=>'error','msg'=>'Fechas inválidas']);
            die();
        }

        $f_llegada     = $val['llegada']->format('Y-m-d');
        $f_salida      = $val['salida']->format('Y-m-d');
        $id_habitacion = (int) $_SESSION['reserva']['habitacion'];
        $id_usuario    = (int) $_SESSION['id_usuario'];

        $hab = $this->model->getHabitacion($id_habitacion);
        if (empty($hab)) {
            echo json_encode(['type' => 'error', 'msg' => 'Habitación no encontrada']);
            die();
        }

        $dias = (int) $val['llegada']->diff($val['salida'])->days;
        if ($dias < 1) { $dias = 1; }
        $precio = (float) $hab['precio'];
        $monto  = $dias * $precio;
        $desc   = 'Reserva del ' . $f_llegada . ' al ' . $f_salida;

        $data = $this->agregarReserva([
            'monto'         => $monto,
            'fecha_ingreso' => $f_llegada,
            'fecha_salida'  => $f_salida,
            'descripcion'   => $desc,
            'id_habitacion' => $id_habitacion,
            'id_usuario'    => $id_usuario
        ]);

        if ($data > 0) {
            unset($_SESSION['reserva']);
            echo json_encode([
                'type' => 'success',
                'msg'  => 'Reserva confirmada',
                'id'   => $data
            ]);
        } else {
            echo json_encode([
                'type' => 'error',
                'msg'  => 'No se pudo confirmar la reserva'
            ]);
        }
        die();
    }
}