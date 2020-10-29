<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This is a one-line short description of the file
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    block_if_sence_login
 * @copyright  authors: Daniel Torres and J AND J SPA
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__).'/../../../config.php');
require_once(__DIR__ . '/../model/session_store_helpers.php');
global $DB;

$courseid = $_POST['id'];
$runalumno = $_POST['runalumno'];

//Obtenemos id del campo personalizado 'CodSence'
$codsenceid = $DB->get_record('customfield_field', ['shortname' => 'codsence'],'id');
//Obtenemos el valor del CodSence
$codsence = $DB->get_record('customfield_data', 
        ['fieldid' => $codsenceid->id,'instanceid' => intval($courseid)],
        'value');

//Obtenemos id del campo personalizado 'LineaCapacitaci贸n'
$lineacapid = $DB->get_record('customfield_field', ['shortname' => 'lineacap'],'id');
//Obtenemos el valor del LineaCapacitaci贸n
$lineacap = $DB->get_record('customfield_data', 
        ['fieldid' => $lineacapid->id,'instanceid' => intval($courseid)],
        'value');



//Obtenemos id del campo personalizado 'codigo curso'
$codcursoid = $DB->get_record('customfield_field', ['shortname' => 'codcurso'],'id');
//Obtenemos el valor del 'codigo curso'
$codcurso = $DB->get_record('customfield_data', 
        ['fieldid' => $codcursoid->id,'instanceid' => intval($courseid)],
        'value');

echo '$("#cod-sence").val("'.$codsence->value.'");';
echo '$("#linea-cap").val("'.$lineacap->value.'");';
echo '$("#cod-curso").val("'.$codcurso->value.'");';
if(sence_validate_session($runalumno,$codsence->value)){
    echo '$("#form-sence").attr("action","'.$CFG->wwwroot.'/course/view.php?id='.$courseid.'");';
}else{
    echo '$("#form-sence").attr("action","https://sistemas.sence.cl/rce/Registro/IniciarSesion");';
}
