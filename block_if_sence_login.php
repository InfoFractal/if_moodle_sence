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

require_once(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/model/courses_sence_list.php');
require_once(dirname(__FILE__).'/model/session_store_helpers.php');
defined('MOODLE_INTERNAL')||die();
class block_if_sence_login extends block_base {
    
    public function init() {
        $this->title = get_string('pluginname', 'block_if_sence_login');
    }
    function get_required_javascript() {
        parent::get_required_javascript();
 
        $this->page->requires->jquery();
        $this->page->requires->jquery_plugin('ui');
        $this->page->requires->jquery_plugin('ui-css');
    }
    function has_config() {return true;}
    
    function is_coursetopicpage(){
        global $PAGE;
        if ($PAGE->bodyid == 'page-site-index') {
            return true;
        }else{
            return false;
        }
    }
    
    
    function set_formcontent(){
        global $USER;
        $runotec = get_config('block_if_sence_login','runotec');
        $tokenotec = get_config('block_if_sence_login','tokenotec');
        
        //Desarrollo
        //$DB->set_debug(true);
        
        if(!empty($runotec) ){
            if(!empty($tokenotec)){
                return $this->get_body_plugin();
            }else{
                return $text = $this->get_missing_data();
            }
        }else{
            return $text = $this->get_missing_data();
        }
    }
    public function get_body_plugin(){
        global $USER;
        $runalumno = $USER->profile['runalumno'];
        $userid = $USER->id;
        
        $runotec = get_config('block_if_sence_login','runotec');
        $tokenotec = get_config('block_if_sence_login','tokenotec');
        $idsesionalumno = sesskey();
        $courses_sence = get_courses_sence_list();
        $courses_sence_2 = get_courses_sence_list();
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $new_link = str_replace("?redirect=0", "",$actual_link);
        $text  = '<h5>Selecciona el curso que deseas</h5>';
        $text .= '<form method="post" id="form-sence">';
        $text .=    '<select id="course-selector">';
        $text .=    '<option value="-1">Seleccione...</option>';
        foreach ($courses_sence as $course){
            $text .='<option value="'.$course->id.'">'.$course->fullname."</option>";
        }
        $text .=    '</select>';
        $text .=    '<input type="hidden" value="'.$runotec.'" name="RutOtec">';
        $text .=    '<input type="hidden" value="'.$tokenotec.'" name="Token">';
        $text .=    '<input type="hidden" value="" name="CodSence" id="cod-sence">';
        $text .=    '<input type="hidden" value="" name="CodigoCurso" id="cod-curso">';
        $text .=    '<input type="hidden" value="" name="LineaCapacitacion" id="linea-cap">';
        $text .=    '<input type="hidden" value="'.$new_link.'blocks/if_sence_login/pages/exito.php?type=login" name="UrlRetoma">';
        $text .=    '<input type="hidden" value="'.$new_link.'blocks/if_sence_login/pages/error.php" name="UrlError">';
        $text .=    '<input type="hidden" value="'.$runalumno.'" name="RunAlumno">';
        $text .=    '<input type="hidden" value="'.$idsesionalumno.'" name="IdSesionAlumno"><br>';
        $text .=    '<br><input type="submit" value="Iniciar" disabled="disabled" id="submit-button">';
        $text .='</form>';
        $text .='<script>';
            $text .=    'var firstFolder = window.location.pathname.split("/")[1];';
            $text .=    '$(document).ready(function(){';
            $text .=    'var prot = document.location.protocol;';
            $text .=    'var host = document.location.hostname;';
        foreach ($courses_sence_2 as $course){
            $data_course = get_data_course_by_courseid($course->id);
            $text .=        '$("a[href*=\'/course/view.php?id='.$course->id.'\']:contains(\''.$course->fullname.'\')").remove(".if_block_sence_'.$course->id.'");';
            $text .=        '$("a[href*=\'/course/view.php?id='.$course->id.'\']:contains(\''.$course->fullname.'\')").append("<div style=\'margin-top:10px;font-size:12px;\' class=\'if_block_sence_'.$course->id.'\'>';
            if(is_only_student_in_course($userid,$course->id)){
                //Posee solo el rol de estudiante y hace la verificación de la sesi+on
                if(sence_validate_session($runalumno,$course->codsence)){
                    //Continua su sesión iniciada sin logearse nuevamente en sence
                    $text .=        '<form action=\""+prot+"//"+host+"/"+firstFolder+"/course/view.php?id='.$course->id.'\" method=\"post\">';
                    $text .=                '<input type=\"submit\" value=\"Seguir con el curso\">';
                }else{
                    //la sesión no es valida o se encuentra caducada por lo que se envía a sence para login
                    $text .=        '<form action=\"https://sistemas.sence.cl/rce/Registro/IniciarSesion\" method=\"post\">';
                    $text .=                '<input type=\"hidden\" value=\"'.$runotec.'\" name=\"RutOtec\">';
                    $text .=                '<input type=\"hidden\" value=\"'.$tokenotec.'\" name=\"Token\">';
                    $text .=                '<input type=\"hidden\" value=\"'.$course->codsence.'\" name=\"CodSence\">';
                    $text .=                '<input type=\"hidden\" value=\"'.$data_course['codcurso'].'\" name=\"CodigoCurso\">';
                    $text .=                '<input type=\"hidden\" value=\"'.$data_course['lineacap'].'\" name=\"LineaCapacitacion\">';
                    $text .=                '<input type=\"hidden\" value=\""+prot+"//"+host+"/"+firstFolder+"/blocks/if_sence_login/pages/exito.php?type=login\" name=\"UrlRetoma\">';
                    $text .=                '<input type=\"hidden\" value=\""+prot+"//"+host+"/"+firstFolder+"/blocks/if_sence_login/pages/error.php\" name=\"UrlError\">';
                    $text .=                '<input type=\"hidden\" value=\"'.$runalumno.'\" name=\"RunAlumno\">';
                    $text .=                '<input type=\"hidden\" value=\"'.$idsesionalumno.'\" name=\"IdSesionAlumno\">';
                    if(is_registered_in_course($userid,$course->id)){
                        //El estudiante se encuentra matriculado
                        $text .=            '<input type=\"submit\" value=\"Iniciar con SENCE\">';
                    }else{
                        //El estudiante no se encuentra matriculado
                        $text .=            '<input type=\"submit\" value=\"Iniciar con SENCE\" disabled=\"disabled\" title=\"No estas matriculado en este curso\">';
                    }
                }
            }else{
                //el usuario no es estudiante o posee mas de un perfil en el curso
                $text .=        '<form action=\""+prot+"//"+host+"/"+firstFolder+"/course/view.php?id='.$course->id.'\" method=\"post\">';
                $text .=            '<input type=\"submit\" value=\"Ingresar al curso\">';
            }
            
            $text .=        '");';
            $text .=        '$("a[href*=\'/course/view.php?id='.$course->id.'\']").removeAttr("href");';            
        }
            $text .=    '});';
            $text .=    'var url = "blocks/if_sence_login/model/sence_data_filter.php";';
        $text .=    '$("#course-selector").change(function(){';
        $text .=        'if($("#course-selector").val()!=="-1"){';
        $text .=            'var id = $("#course-selector").val();';
        $text .=            '$.ajax({';
        $text .=                'url: url,';
        $text .=                'type: "POST",';
        $text .=                'data: {id:id,runalumno:"'.$runalumno.'"}';
        $text .=            '}).done(function(js) {';
        $text .=                'eval(js);';
        $text .=                '$("#submit-button").attr("disabled", false);';
        $text .=            '});';
        $text .=        '}else{';
        $text .=            '$("#submit-button").attr("disabled", true);';
        $text .=        '}';
        $text .='   });';
        $text .='</script>';
        $text .= '<hr>';
        $courses_user = get_courses_data_from_alumnoid($userid);
        $text  .= '<h5>Sesiones iniciadas</h5>';
        $count = 1;
        foreach($courses_user as $c_u){
            $text  .= '<form name="course_list_form_'.$count.'" action="https://sistemas.sence.cl/rce/Registro/CerrarSesion" method="post">';
            $text .=    '<p>'.$c_u->nombrecurso.'  <input type="submit" value="Cerrar"/></p>';
            $text .=    '<input type="hidden" value="'.$runotec.'" name="RutOtec">';
            $text .=    '<input type="hidden" value="'.$tokenotec.'" name="Token">';
            $text .=    '<input type="hidden" value="'.$c_u->codsence.'" name="CodSence">';
            $text .=    '<input type="hidden" value="'.$c_u->codcurso.'" name="CodigoCurso">';
            $text .=    '<input type="hidden" value="'.$c_u->lineacap.'" name="LineaCapacitacion">';
            $text .=    '<input type="hidden" value="'.$new_link.'blocks/if_sence_login/pages/exito.php?type=logout" name="UrlRetoma">';
            $text .=    '<input type="hidden" value="'.$new_link.'blocks/if_sence_login/pages/error.php" name="UrlError">';
            $text .=    '<input type="hidden" value="'.$runalumno.'" name="RunAlumno">';
            $text .=    '<input type="hidden" value="'.$idsesionalumno.'" name="IdSesionAlumno">';
            $text .=    '<input type="hidden" value="'.$c_u->idsesionsence.'" name="IdSesionSence"><br>';
            $text .= '</form>';
            $count++;
        } 
        return $text;
    }
    
    public function get_missing_data(){
        $text  = '<h5>Hola. Falta algo!</h5>';
        $text  = '<p>Por favor, echa un vistazo al formulario de Informaci&oacute;n OTEC.</p>';
        
        return $text;
    }
    public function get_content() {
        
        if ($this->content !== null) {
          return $this->content;
        }
        if(isloggedin()){
            $this->content         =  new stdClass;
            $this->content->text   = $this->set_formcontent();
        }
        return $this->content;
    }
}
?>
