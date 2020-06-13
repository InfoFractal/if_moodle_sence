<?php
require_once(dirname(__FILE__).'/../../../config.php');

function get_courses_sence_list(){
    global $DB;
    $query = "select c.id,c.fullname from mdl_course c,mdl_customfield_data cfd,"
            . "mdl_customfield_field cff where cff.shortname=? and cff.id = cfd.fieldid "
            . "and cfd.instanceid = c.id and cfd.value<>''";
    $result = $DB->get_recordset_sql($query, ['codsence']);
    return $result;
}

