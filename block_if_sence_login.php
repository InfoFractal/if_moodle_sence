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
defined('MOODLE_INTERNAL')||die();
class block_if_sence_login extends block_base {
    
    public function init() {
        $this->title = get_string('pluginname', 'block_if_sence_login');
//        if(isset($_POST['CodSence'])){
//            echo '<script>console.log("aqui viene el post: '.$_POST['CodSence'].'");</script>';
//        }
        //require_login();
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
        //$DB->set_debug(true);
        $runalumno = $USER->profile['runalumno'];
        
        $runotec = get_config('block_if_sence_login','runotec');
        $tokenotec = get_config('block_if_sence_login','tokenotec');
        $idsesionalumno = sesskey();
        
        $courses_sence = get_courses_sence_list();
        $courses_sence_2 = get_courses_sence_list();
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $new_link = str_replace("?redirect=0", "",$actual_link);
        echo "<script>console.log('$new_link');</script>";
        $text  = '<h5>Selecciona el curso que deseas</h5>';
        $text .= '<form action="https://sistemas.sence.cl/rcetest/Registro/IniciarSesion" method="post" id="form-sence">';
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
        $text .=    '<input type="hidden" value="'.$new_link.'blocks/if_sence_login/pages/exito.php" name="UrlRetoma">';
        $text .=    '<input type="hidden" value="'.$new_link.'blocks/if_sence_login/pages/error.php" name="UrlError">';
        $text .=    '<input type="hidden" value="'.$runalumno.'" name="RunAlumno">';
        $text .=    '<input type="hidden" value="'.$idsesionalumno.'" name="IdSesionAlumno"><br>';
        $text .=    '<br><input type="submit" value="Iniciar" disabled="disabled" id="submit-button">';
        $text .='</form>';
        $text .='<script>';
            $text .=    '$(document).ready(function(){';
            $text .=    'var prot = document.location.protocol;';
            $text .=    'var host = document.location.hostname;';
        foreach ($courses_sence_2 as $course){
            $data_course = get_data_course_by_courseid($course->id);
            $text .=        '$("[data-courseid='.$course->id.'] a").removeAttr("href");';
            $text .=        '$("[data-courseid='.$course->id.'] .content").html("';
            $text .=            '<form action=\"https://sistemas.sence.cl/rcetest/Registro/IniciarSesion\" method=\"post\">';
            $text .=                '<input type=\"hidden\" value=\"'.$runotec.'\" name=\"RutOtec\">';
            $text .=                '<input type=\"hidden\" value=\"'.$tokenotec.'\" name=\"Token\">';
            $text .=                '<input type=\"hidden\" value=\"'.$course->codsence.'\" name=\"CodSence\">';
            $text .=                '<input type=\"hidden\" value=\"'.$data_course['codcurso'].'\" name=\"CodigoCurso\">';
            $text .=                '<input type=\"hidden\" value=\"'.$data_course['lineacap'].'\" name=\"LineaCapacitacion\">';
            $text .=                '<input type=\"hidden\" value=\""+prot+"//"+host+"/moodle/blocks/if_sence_login/pages/exito.php\" name=\"UrlRetoma\">';
            $text .=                '<input type=\"hidden\" value=\""+prot+"//"+host+"/moodle/blocks/if_sence_login/pages/error.php\" name=\"UrlError\">';
            $text .=                '<input type=\"hidden\" value=\"'.$runalumno.'\" name=\"RunAlumno\">';
            $text .=                '<input type=\"hidden\" value=\"'.$idsesionalumno.'\" name=\"IdSesionAlumno\">';
            $text .=                '<input type=\"submit\" value=\"Iniciar curso a través de SENCE\">';
            $text .=            '</form>';
            $text .=        '");';
            
        }
            $text .=    '});';
        $text .=    '$("#course-selector").change(function(){';
        $text .=        'if($("#course-selector").val()!=="-1"){';
        $text .=            'var id = $("#course-selector").val();';
        $text .=            '$.ajax({';
        $text .=                'url: "/moodle/blocks/if_sence_login/model/sence_data_filter.php",';
        $text .=                'type: "POST",';
        $text .=                'data: {id:id}';
        $text .=            '}).done(function(js) {';
        $text .=                'eval(js);';
        $text .=                '$("#submit-button").attr("disabled", false);';
        $text .=            '});';
        $text .=        '}else{';
        $text .=            '$("#submit-button").attr("disabled", true);';
        $text .=        '}';
        $text .='   });';
        $text .='</script>';
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
