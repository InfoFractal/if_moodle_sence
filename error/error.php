<?php

require_once(__DIR__ . '../../../../config.php');

$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("Error en login SENCE");
$PAGE->set_heading("Hemos detectado un error en el login SENCE");
$PAGE->set_url($CFG->wwwroot.'/error.php');

$cod_error = intval($_POST['GlosaError']);
$error_msg = "";
switch ($cod_error){
    case(100):
        $error_msg = "Contraseña incorrecta, intente otra vez.";
        break;
    case(200):
        $error_msg = "POST incorrecto, contactar soporte.";
        break;
    case(201):
        $error_msg = "URL retorno/error vacia, contactar soporte.";
        break;
    case(202):
        $error_msg = "URL de Retoma tiene formato incorrecto, contactar soporte.";
        break;
    case(203):
        $error_msg = "URL de Error tiene formato incorrecto, contactar soporte.";
        break;
    case(204):
        $error_msg = "Código SENCE inválido, contactar soporte.";
        break;
    case(205):
        $error_msg = "Código curso inválido, contactar soporte.";
        break;
    case(206):
        $error_msg = "Linea capacitación incorrecta, contactar soporte.";
        break;
    case(207):
        $error_msg = "RUN Alumno con formato incorrecto, debe ser 12345678-9.";
        break;
    case(208):
        $error_msg = "RUN Alumno no está autorizado.";
        break;
    case(209):
        $error_msg = "RUN OTEC con formato incorrecto, debe ser 12345678-9, contactar soporte.";
        break;
    case(210):
        $error_msg = "Login expirado, el tiempo es de 180 segundos. Intente otra vez.";
        break;
    case(211):
        $error_msg = "Token OTEC incorrecto, contactar soporte.";
        break;
    case(212):
        $error_msg = "Token OTEC expirado, contactar soporte.";
        break;
    case(300):
        $error_msg = "Error interno, contactar soporte.";
        break;
    case(301):
        $error_msg = "No se pudo registrar sesión, contactar soporte.";
        break;
    case(302):
        $error_msg = "No se logró validar institución, contactar soporte.";
        break;
    case(303):
        $error_msg = "Token OTEC no existe, contactar soporte.";
        break;
    case(304):
        $error_msg = "No se pudo verificar el ingreso, contactar soporte.";
        break;
    case(305):
        $error_msg = "No se pudo registrar el ingreso, contactar soporte.";
        break;
    case(306):
        $error_msg = "Código curso no corresponde al código SENCE, contactar soporte.";
        break;
    default:
        $error_msg = 'Error ' . $cod_error . ' desconocido, contactar soporte';
}
echo $OUTPUT->header();
echo $error_msg;
echo $OUTPUT->footer();
die();
