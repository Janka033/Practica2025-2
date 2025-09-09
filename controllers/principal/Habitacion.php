<?php
require_once 'vendor/autoload.php';
use Carbon\Carbon;

class Habitacion extends Controller
{
    public function __construct()
    {
        parent::__construct();
        // Establecer locale español para diffForHumans
        Carbon::setLocale('es');
        // (Opcional) establecer la zona horaria por defecto de Carbon
        date_default_timezone_set('America/Bogota');
    }

    public function index()
    {
        $data['title'] = 'Hospedajes';
        // Sliders
        $data['sliders'] = $this->model->getSliders();
        $data['empresa'] = $this->model->getEmpresa();

        // Habitaciones con cálculo de calificación
        $data['habitaciones'] = $this->model->getHabitaciones();
        for ($i = 0; $i < count($data['habitaciones']); $i++) {
            $calificacion = $this->model->getCalificaciones($data['habitaciones'][$i]['id']);
            $totalCalificacion = $this->model->getTotalCalificaciones($data['habitaciones'][$i]['id']);
            $total = ($totalCalificacion['total'] != null) ? $totalCalificacion['total'] : 5;
            $existe = (count($calificacion) > 0) ? count($calificacion) : 1;
            $data['habitaciones'][$i]['calificacion'] = $total / $existe;
        }

        // Calificaciones (testimonios) generales
        $data['testimonial'] = $this->model->getCalificacionesGeneral();

        // NO sobrescribimos 'fecha'. Creamos: fecha_iso (ya convertida) y fecha_rel (humana)
        for ($i = 0; $i < count($data['testimonial']); $i++) {
            $fechaCruda = $data['testimonial'][$i]['fecha']; // valor original de la BD

            // Asumimos que la BD está en UTC. Si ya está en hora local, cambia 'UTC' por 'America/Bogota'
            $carbon = Carbon::parse($fechaCruda, 'UTC')->setTimezone('America/Bogota');

            // Fecha exacta local formateada (puedes cambiar el formato)
            $data['testimonial'][$i]['fecha_iso'] = $carbon->format('Y-m-d H:i:s');

            // Relativa en español (ej: "hace 2 horas")
            $data['testimonial'][$i]['fecha_rel'] = $carbon->diffForHumans();
        }

        $this->views->getView('principal/habitacion/index', $data);
    }
}