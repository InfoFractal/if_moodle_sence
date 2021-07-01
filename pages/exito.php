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
    $PAGE->set_title("Login SENCE exitoso");
}
$CodSence =          $_POST['CodSence'];
$CodigoCurso =       $_POST['CodigoCurso'];
$IdSesionAlumno =    $_POST['IdSesionAlumno'];
$IdSesionSence =     $_POST['IdSesionSence'];
$RunAlumno =         $_POST['RunAlumno'];
$FechaHora =         $_POST['FechaHora'];
$ZonaHoraria =       $_POST['ZonaHoraria'];
$LineaCapacitacion = $_POST['LineaCapacitacion'];

#Revisar si el idSesionAlumno que llego es el mismo que fue enviado
if (strval(sesskey()) == strval($IdSesionAlumno)){
  #Obtener id de usuario, necesario en caso de codigo curso por grupo
  $uid = $USER->id;
  #Obtener categoria de los campos SENCE
  $catId = $DB->get_record_sql("SELECT id FROM {customfield_category} WHERE name = 'SENCE'")->id;
  #Obtener ids de los campos SENCE
  $fieldIds = $DB->get_records_sql("SELECT id,shortname FROM {customfield_field} WHERE categoryid = ".$catId);
  $customFields = array();
  foreach ($fieldIds as $v){
      $customFields[$v->shortname] = $v->id; 
  }
  #mensaje inicial
  $text = '<p>Contacte soporte si ve este mensaje.</p>';
  $idcurso = false;
  #Obtener ids de los cursos que tienen el mismo CodigoCurso entregado por el POST
  $query = "SELECT instanceid,charvalue FROM {customfield_data} WHERE fieldid = '".$customFields["codcurso"]."' AND charvalue = '".$CodigoCurso."'";
  $courseIds = $DB->get_records_sql($query);
  #Buscaremos si el usuario esta registrado en algun grupo interno de alguno de los cursos
  $Gquery = "SELECT courseid FROM {groups_members} INNER JOIN {groups} WHERE {groups_members}.groupid = {groups}.id AND userid = ".$uid." AND name = '".$CodigoCurso."'"; 
  $GcourseIds = $DB->get_records_sql($Gquery);
  $cIdsCount = count($courseIds);
  $GcIdsCount = count($GcourseIds);

  if($cIdsCount == 1){
      $idcurso = array_values($courseIds)[0]->instanceid;
  }
  else{
      $text .= "<p>Se encontraron ".$cIdsCount." cursos con el id".$CodigoCurso." en los cursos estandar</p>";
      $text .= "<p>".$query."</p>";
  }
  if($GcIdsCount == 1){
      $idcurso = array_values($GcourseIds)[0]->courseid;
  }
  else{
      $text .= "<p>Se encontraron ".$GcIdsCount." cursos con el id".$CodigoCurso." en los grupos.</p>";
      $text .= "<p>".$Gquery."</p>";
  }
  #Genera url de redireccion
  
  if($idcurso){
      if($isLogoutURL){ #logout
          #curso es valido, invalidando sesion 
          sence_invalidate_session($RunAlumno,$CodSence);
          $url = $CFG->wwwroot;
          $msg = "<p>La sesion ha sido cerrada.</p><p>Seras redireccionado despues de 5 segundos.</p>";
          $text = gen_redir($url,$msg);
      }
      else{ #login 
          #curso es valido, agregando datos de sesion a la db 
          sence_write_session($RunAlumno,$IdSesionAlumno,$IdSesionSence,$CodSence,$FechaHora,$ZonaHoraria);
          $url = $CFG->wwwroot."/course/view.php?id=".$idcurso;
          $msg = "<p>La sesion ha sido abierta.</p><p>Seras redireccionado despues de 5 segundos.</p>";
          $text = gen_redir($url,$msg);
      }
  }  

  echo $OUTPUT->header();
  echo $text;
  echo $OUTPUT->footer();
  die();
}
else{
  echo $OUTPUT->header();
  echo "<p>ID sesion invalido.</p>";
  echo $OUTPUT->footer();
  die();
}
