<?php
require_once(dirname(__FILE__).'/../../config.php');
defined('MOODLE_INTERNAL')||die();
class block_if_sence_login extends block_base {
    
    public function init() {
        $this->title = get_string('pluginname', 'block_if_sence_login');
        if(isset($_POST['CodSence'])){
            echo '<script>console.log("'.$_POST['CodSence'].'");\n</script>';
        }
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
    
    function set_formcontent($courseid){
        global $DB,$USER;
        //$DB->set_debug(true);
        $runalumno = $USER->profile['runalumno'];
        $username = $USER->username;
        
        $runotec = get_config('block_if_sence_login','runotec');
        $tokenotec = get_config('block_if_sence_login','tokenotec');
        $urlerror = get_config('block_if_sence_login','urlerror');
        $courses = get_courses();
        
        $text = '';
        $text = '<form action="https://sistemas.sence.cl/rcetest/Registro/IniciarSesion" method="post" id="form-sence">';
        $text .=    '<select name="CodigoCurso" id="course-selector">';
        $text .=    '<option value="-1">Seleccione...</option>';
        foreach ($courses as $course){
            $text .='<option value="'.$course->id.'">'.$course->fullname."</option>";
        }
        $text .=    '</select>';
        $text .=    '<input type="hidden" value="'.$runotec.'" name="RutOtec"/>';
        $text .=    '<input type="hidden" value="'.$tokenotec.'" name="Token"/>';
        $text .=    '<input type="hidden" value="" name="CodSence" id="cod-sence"/>';
        $text .=    '<input type="hidden" value="" name="CodigoCurso" id="course-shortname"/>';
        $text .=    '<input type="hidden" value="" name="LineaCapacitacion" id="linea-cap"/>';
        $text .=    '<input type="hidden" value="" name="UrlRetoma" id="url-retoma"/>';
        $text .=    '<input type="hidden" value="'.$urlerror.'" name="UrlError"/>';
        $text .=    '<input type="hidden" value="'.$runalumno.'" name="RunAlumno"/>';
        $text .=    '<input type="hidden" value="'.$username.'" name="IdSesionAlumno"/><br>';
        $text .=    '<br><input type="submit" value="Ingresar" disabled="disabled" id="submit-button"/>';
        $text .='</form>';
        return $text;
        
    }
    public function get_content() {
        global $PAGE;
        $PAGE->requires->js(new moodle_url('/blocks/if_sence_login/js/scripts.js'));
        if ($this->content !== null) {
          return $this->content;
        }
        $courseid = $_GET['id'];
        $this->content         =  new stdClass;
        $this->content->text   = $this->set_formcontent($courseid);
        //$this->content->footer = 'Footer here...';
        return $this->content;
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.
}