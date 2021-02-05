<?php

require_once(__DIR__ . '../../../../config.php');

$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("Error en login SENCE");
$PAGE->set_heading("Hemos detectado un error en el login SENCE");
$PAGE->set_url($CFG->wwwroot.'/blocks/if_sence_login/pages/error.php');

$cod_error = intval($_POST['GlosaError']);
$error_msg = "";
switch ($cod_error){
    case(100):
        $error_msg = "Contraseña incorrecta, intente otra vez.";
        break;
    case(200):
        $error_msg = "POST incorrecto, tiene uno o más parámetros mandatorios sin información o mal escritos.";
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
        $error_msg = "Código SENCE inválido, debe tener al menos 10 caracteres.";
        break;
    case(205):
        $error_msg = "Código curso inválido, debe tener al menos 7 caracteres.";
        break;
    case(206):
        $error_msg = "Linea capacitación incorrecta.";
        break;
    case(207):
        $error_msg = "RUN Alumno con formato incorrecto, debe ser 12345678-9.";
        break;
    case(208):
        $error_msg = "RUN Alumno no está autorizado para realizar el curso.";
        break;
    case(209):
        $error_msg = "RUN OTEC con formato incorrecto, debe ser 12345678-9.";
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
        $error_msg = "Error interno no clasificado, se debe reportar al SENCE con la mayor cantidad de antecedentes disponibles.";
        break;
    case(301):
        $error_msg = "No se pudo registrar el ingreso o cierre de sesión. Esto ocurre cuando la Línea de Capacitación es incorrecta, o el Código de Curso es incorrecto";
        break;
    case(302):
        $error_msg = "No se pudo validar la información del Organismo, se debe reportar al SENCE con la mayor cantidad de antecedentes disponibles.";
        break;
    case(303):
        $error_msg = "Token OTEC no existe o su formato es incorrecto.";
        break;
    case(304):
        $error_msg = "No se pudo verificar los datos de ingreso, se debe reportar al SENCE con la mayor cantidad de antecedentes disponibles.";
        break;
    case(305):
        $error_msg = "No se pudo registrar el ingreso, se debe reportar al SENCE con la mayor cantidad de antecedentes disponibles. ";
        break;
    case(306):
        $error_msg = "El código curso no corresponde al código SENCE.";
        break;
    case(307):
        $error_msg = "El curso no tiene modalidad E-learning.";
        break;    
    case(308):
        $error_msg = "El Código curso no corresponde al RUT OTEC.";
        break;
    case(309):
        $error_msg = "Las fechas de ejecución comunicadas para el Código Curso no corresponden a la fecha actual";
        break;    
    case(310):
        $error_msg = "El Código Curso está en Estado terminado o anulado.";
        break;        
    default:
        $error_msg = 'Error ' . $cod_error . ' desconocido, contactar soporte';
}
echo $OUTPUT->header();
echo $error_msg;
echo $OUTPUT->footer();
die();
