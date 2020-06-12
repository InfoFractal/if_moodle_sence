<?php
require_once(dirname(__FILE__).'/../../../config.php');
global $DB;
$courseid = $_POST['id'];
//obtenemos id del campo personalizado 'CodSence'
$codsenceid = $DB->get_record('customfield_field', ['shortname' => 'codsence'],'id');
//Obtenemos el valor del CodSence
$codsence = $DB->get_record('customfield_data', 
        ['fieldid' => $codsenceid->id,'instanceid' => intval($courseid)],
        'value');

//obtenemos id del campo personalizado 'LineaCapacitación'
$lineacapid = $DB->get_record('customfield_field', ['shortname' => 'lineacap'],'id');
//Obtenemos el valor del LineaCapacitación
$lineacap = $DB->get_record('customfield_data', 
        ['fieldid' => $lineacapid->id,'instanceid' => intval($courseid)],
        'value');

//obtenemos id del campo personalizado 'Url Retorno'
$successurlid = $DB->get_record('customfield_field', ['shortname' => 'successurl'],'id');
//Obtenemos el valor del 'Url Retorno'
$successurl = $DB->get_record('customfield_data', 
        ['fieldid' => $successurlid->id,'instanceid' => intval($courseid)],
        'value');

//obtenemos id del campo personalizado 'Url Retorno'
$codcursoid = $DB->get_record('customfield_field', ['shortname' => 'codcurso'],'id');
//Obtenemos el valor del 'Url Retorno'
$codcurso = $DB->get_record('customfield_data', 
        ['fieldid' => $successurlid->id,'instanceid' => intval($courseid)],
        'value');

//Obtenemos el Shortname del curso y lo usamos como CodigoCurso
//$codcurso = $DB->get_record('course', ['id' => $courseid],'shortname');


echo '$("#cod-sence").val("'.$codsence->value.'");';
echo '$("#linea-cap").val("'.$lineacap->value.'");';
echo '$("#url-retoma").val("'.$successurl->value.'");';
echo '$("#course-shortname").val("'.$codcurso->value.'");';
