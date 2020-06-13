<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/model/courses_sence_list.php');
defined('MOODLE_INTERNAL')||die();
class block_if_sence_login extends block_base {
    
    public function init() {
        $this->title = get_string('pluginname', 'block_if_sence_login');
        if(isset($_POST['CodSence'])){
            echo '<script>console.log("'.$_POST['CodSence'].'");\n</script>';
        }
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
        $username = $USER->username;
        
        $runotec = get_config('block_if_sence_login','runotec');
        $tokenotec = get_config('block_if_sence_login','tokenotec');
        $urlerror = get_config('block_if_sence_login','urlerror');
        $courses = get_courses();
        $courses_sence = get_courses_sence_list();
        $courses_sence_2 = get_courses_sence_list();
        $text  = '<h5>Selecciona el curso que deseas</h5>';
        $text .= '<form action="https://sistemas.sence.cl/rcetest/Registro/IniciarSesion" method="post" id="form-sence">';
        $text .=    '<select name="CodigoCurso" id="course-selector">';
        $text .=    '<option value="-1">Seleccione...</option>';
        foreach ($courses_sence as $course){
            $text .='<option value="'.$course->id.'">'.$course->fullname."</option>";
        }
        $text .=    '</select>';
        $text .=    '<input type="hidden" value="'.$runotec.'" name="RutOtec"/>';
        $text .=    '<input type="hidden" value="'.$tokenotec.'" name="Token"/>';
        $text .=    '<input type="hidden" value="" name="CodSence" id="cod-sence"/>';
        $text .=    '<input type="hidden" value="" name="CodigoCurso" id="cod-curso"/>';
        $text .=    '<input type="hidden" value="" name="LineaCapacitacion" id="linea-cap"/>';
        $text .=    '<input type="hidden" value="" name="UrlRetoma" id="url-retoma"/>';
        $text .=    '<input type="hidden" value="'.$urlerror.'" name="UrlError"/>';
        $text .=    '<input type="hidden" value="'.$runalumno.'" name="RunAlumno"/>';
        $text .=    '<input type="hidden" value="'.$username.'" name="IdSesionAlumno"/><br>';
        $text .=    '<br><input type="submit" value="Iniciar" disabled="disabled" id="submit-button"/>';
        $text .='</form>';
        $text .='<script>';
        foreach ($courses_sence_2 as $course){
            $text .=    '$(document).ready(function(){$("[data-courseid='.$course->id.'] a").removeAttr("href");});';
        }
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
        $this->content         =  new stdClass;
        $this->content->text   = $this->set_formcontent();
        return $this->content;
    }
}
?>