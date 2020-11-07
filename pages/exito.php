<?php

require_once(__DIR__ . '/../../../config.php');
require_once(__DIR__ . '/../model/session_store_helpers.php');

require_login();
$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("Login SENCE exitoso");
$PAGE->set_heading("Pronto seras redireccionado");
$PAGE->set_url($CFG->wwwroot.'/blocks/if_sence_login/pages/exito.php');
$isLogoutURL = false;
$type = $_GET['type'];
if($type == "logout"){
    $isLogoutURL = true;
}
$CodSence =          $_POST['CodSence'];
$CodigoCurso =       $_POST['CodigoCurso'];
$IdSesionAlumno =    $_POST['IdSesionAlumno'];

$RunAlumno =         $_POST['RunAlumno'];
$FechaHora =         $_POST['FechaHora'];
$ZonaHoraria =       $_POST['ZonaHoraria'];
$LineaCapacitacion = $_POST['LineaCapacitacion'];

#Revisar si el idSesionAlumno que llego es el mismo que fue enviado
if (strval(sesskey()) == strval($IdSesionAlumno)){
  #Obtener categoria de los campos SENCE
  $catId = $DB->get_record_sql("SELECT id FROM {customfield_category} WHERE name = 'SENCE'")->id;
  #Obtener ids de los campos SENCE
  $fieldIds = $DB->get_records_sql("SELECT id,shortname FROM {customfield_field} WHERE categoryid = ".$catId);
  $customFields = array();
  foreach ($fieldIds as $v){
      $customFields[$v->shortname] = $v->id; 
  }
  
  #Obtener ids de los cursos que tienen el mismo CodigoCurso entregado por el POST
  $query = "SELECT instanceid,charvalue FROM {customfield_data} WHERE fieldid = '".$customFields["codcurso"]."' AND charvalue = '".$CodigoCurso."'";
  $courseIds = $DB->get_records_sql($query);
  $text = '';
  #Genera url de redireccion
  if($isLogoutURL == true){
      #curso es valido, agregando datos de sesion a la db 
      sence_invalidate_session($RunAlumno,$CodSence);
      $idcurso = array_values($courseIds)[0]->instanceid;
      $url = $CFG->wwwroot;
      $text .= "<script>";
      $text .= "  setTimeout(function(){";
      $text .= "  window.location.href = '".$url."'";
      $text .= "  },5000);";
      $text .= "</script>";
      $text .= "<p> La sesion ha sido cerrada. Seras redireccionado despues de 5 segundos.<p>";
  }else if (count($courseIds) == 1 && $isLogoutURL == false){ #un curso por codigo sence
      #curso es valido, agregando datos de sesion a la db 
      $IdSesionSence =     $_POST['IdSesionSence'];
      sence_write_session($RunAlumno,$IdSesionAlumno,$IdSesionSence,$CodSence,$FechaHora,$ZonaHoraria);
      $idcurso = array_values($courseIds)[0]->instanceid;
      $url = $CFG->wwwroot."/course/view.php?id=".$idcurso;
      $text .= "<script>";
      $text .= "  setTimeout(function(){";
      $text .= "  window.location.href = '".$url."'";
      $text .= "  },5000);";
      $text .= "</script>";
      $text .= "<p> Seras redireccionado despues de 5 segundos.<p>";
  } 
  else if(count($courseIds) > 1){ #mas de un curso con los mismos codigos
    
  }
  else { #curso no encontrado
    
  }

  echo $OUTPUT->header();
  echo $text;
  echo $OUTPUT->footer();
  die();
}
else{
  echo "id sesion invalido";    
}
