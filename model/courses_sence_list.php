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
function is_registered_in_course($uid,$cid){
    global $DB;
    $query = "select c.fullname from {course} c,{user} u,{enrol} e,{user_enrolments} ue where e.courseid = c.id and e.id = ue.enrolid and ue.userid = u.id and u.id = ? and c.id = ?";
    $result = $DB->get_recordset_sql($query, [$uid,$cid]); 
    foreach($result as $r){
        return true;
    }
    return false;
}
function is_only_student_in_course($uid,$cid){
    global $DB;
    $query = "select c.fullname,r.shortname,u.firstname from {course} c, {user} u, {role} r, {role_assignments} ra where ra.roleid = r.id and ra.userid = u.id and u.id = ? and c.id = ?";
    $result = $DB->get_recordset_sql($query, [$uid,$cid]); 
    $roles_arr = [];
    foreach($result as $r){
        array_push($roles_arr,$r->shortname);
    }
    
    if(count($roles_arr)>1){
        //
    }else if(count($roles_arr)==1){
       // 
        if($roles_arr[0]=="student"){
            //
            return true;
        }else{
            //
            return false;
        }
    }else{
        return false;
    }
    return false;
}
function get_courses_data_from_alumnoid($id){
    global $DB;
    $data_array = [];
    $query = "select u.username,u.id as 'userid',c.id as 'courseid',c.fullname, cfd.value as 'codsence',bifl.runalumno,bifl.idsesionsence from {course} c,{customfield_data} cfd, {customfield_field} cff,{user} u,{enrol} e,{user_enrolments} ue,{block_if_sence_login} bifl where cff.shortname=? and cff.id = cfd.fieldid and cfd.instanceid = c.id and cfd.value<>'' and e.courseid = c.id and e.id = ue.enrolid and ue.userid = u.id and u.id = ? and bifl.codsence = cfd.value and bifl.idsesionalumno <>''";
    $result = $DB->get_recordset_sql($query, ['codsence',$id]); 
    foreach($result as $r){
        $data = new stdClass();
        $data_ = get_data_course_by_courseid($r->courseid);
        $data->nombrecurso = $r->fullname;
        $data->codsence = $r->codsence;
        $data->idsesionsence = $r->idsesionsence;
        $data->lineacap = $data_['lineacap'];
        $data->codcurso = $data_['codcurso'];
        array_push($data_array,$data);
    }
    return $data_array;
}

function get_courses_sence_list(){
    global $DB;
    $query = "select c.id,c.fullname, cfd.value as 'codsence' from {course} c,{customfield_data} cfd, {customfield_field} cff where cff.shortname=? and cff.id = cfd.fieldid and cfd.instanceid = c.id and cfd.value<>''";
    $result = $DB->get_recordset_sql($query, ['codsence']);
    return $result;
}
function get_data_course_by_courseid($id){
    global $DB;
    $result = [];
    $query1 = "select cfd.value as 'lineacap' from {course} c,{customfield_data} cfd, {customfield_field} cff where cff.shortname=? and cff.id = cfd.fieldid and cfd.instanceid = c.id and cfd.value<>'' and cfd.instanceid = ?";
    $result1 = $DB->get_recordset_sql($query1, ['lineacap',$id]);
    //var_dump($result1);
    foreach($result1 as $lc){
        $result['lineacap'] = $lc->lineacap;
    }
    $query2 = "select cfd.value as 'codcurso' from {course} c,{customfield_data} cfd, {customfield_field} cff where cff.shortname=? and cff.id = cfd.fieldid and cfd.instanceid = c.id and cfd.value<>'' and cfd.instanceid = ?";
    $result2 = $DB->get_recordset_sql($query2, ['codcurso',$id]);
    foreach($result2 as $lc2){
        $result['codcurso'] = $lc2->codcurso;
    }
    $query3 = "select cfd.value as 'successurl' from {course} c,{customfield_data} cfd,{customfield_field} cff where cff.shortname=? and cff.id = cfd.fieldid and cfd.instanceid = c.id and cfd.value<>'' and cfd.instanceid = ?";
    $result3 = $DB->get_recordset_sql($query3, ['successurl',$id]);
    foreach($result3 as $lc2){
        $result['successurl'] = $lc2->successurl;
    }
    return $result;
}

