<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it AND/or modify
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
 * if you like, AND it can span multiple lines.
 *
 * @package    block_if_sence_login
 * @copyright  authors: Daniel Torres AND J AND J SPA
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__).'/../../../config.php');
function is_registered_in_course($uid,$cid){
    global $DB;
    $query = "SELECT c.fullname FROM {course} c,{user} u,{enrol} e,{user_enrolments} ue WHERE e.courseid = c.id AND e.id = ue.enrolid AND ue.userid = u.id AND u.id = ? AND c.id = ?";
    $result = $DB->get_recordset_sql($query, [$uid,$cid]); 
    foreach($result as $r){
        return true;
    }
    
    return false;
}
function is_only_student_in_course($uid,$cid){
    global $DB;
    //$query = "SELECT c.fullname,r.shortname,u.firstname FROM {course} c, {user} u, {role} r, {role_assignments} ra WHERE ra.roleid = r.id AND ra.userid = u.id AND u.id = ? AND c.id = ?";
    $query = "SELECT c.id, c.fullname,r.shortname,u.firstname FROM {course} c, {user} u, {role} r, {role_assignments} ra,{enrol} e, {user_enrolments} ue WHERE ra.roleid = r.id AND ra.userid = u.id AND ue.userid = u.id AND ue.enrolid = e.id AND e.courseid = c.id AND u.id = ? AND c.id = ?";
    $result = $DB->get_recordset_sql($query, [$uid,$cid]); 
    $roles_arr = [];
    $id ="";$shnm = "";
    foreach($result as $r){
        if($id == $r->id){
            //echo "id igual\n";
            if($shnm !=$r->shortname){
                //echo "id igual y rol distinto\n";
                array_push($roles_arr,$r->shortname);
            }
        }else{
            //echo "id distinto\n";
            array_push($roles_arr,$r->shortname);
        }
        $id = $r->id;
        $shnm = $r->shortname;
    }
    //var_dump($roles_arr);die;
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
        return true;
    }
    return false;
}
function get_courses_data_FROM_alumnoid($id){
    global $DB;
    $data_array = [];
    $query = "SELECT u.username,u.id as 'userid',c.id as 'courseid',c.fullname, cfd.value as 'codsence',bifl.runalumno,bifl.idsesionsence FROM {course} c,{customfield_data} cfd, {customfield_field} cff,{user} u,{enrol} e,{user_enrolments} ue,{block_if_sence_login} bifl,{user_info_field} uif,{user_info_data} uidata WHERE cff.shortname=? AND uif.shortname=? AND cff.id = cfd.fieldid AND cfd.instanceid = c.id AND cfd.value<>'' AND e.courseid = c.id AND e.id = ue.enrolid AND ue.userid = u.id AND u.id = uidata.userid AND uif.id = uidata.fieldid AND uidata.data = bifl.runalumno AND bifl.codsence = cfd.value AND bifl.idsesionalumno <>'' AND u.id = ?";
    $result = $DB->get_recordset_sql($query, ['codsence','runalumno',$id]); 
    foreach($result as $r){
        $data = new stdClass();
        $data_ = get_data_course_by_courseid($r->courseid,$id);
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
    $query = "SELECT c.id,c.fullname, cfd.value as 'codsence' FROM {course} c,{customfield_data} cfd, {customfield_field} cff WHERE cff.shortname=? AND cff.id = cfd.fieldid AND cfd.instanceid = c.id AND cfd.value<>''";
    $result = $DB->get_recordset_sql($query, ['codsence']);
    return $result;
}
function get_data_course_by_courseid($id,$uid=-1){
    global $DB;
    $result = [];
    $query1 = "SELECT cfd.value as 'lineacap' FROM {course} c,{customfield_data} cfd, {customfield_field} cff WHERE cff.shortname=? AND cff.id = cfd.fieldid AND cfd.instanceid = c.id AND cfd.value<>'' AND cfd.instanceid = ?";
    $result1 = $DB->get_recordset_sql($query1, ['lineacap',$id]);
    //var_dump($result1);
    foreach($result1 as $lc){
        $result['lineacap'] = $lc->lineacap;
    }
    $query2 = "SELECT cfd.value as 'codcurso' FROM {course} c,{customfield_data} cfd, {customfield_field} cff WHERE cff.shortname=? AND cff.id = cfd.fieldid AND cfd.instanceid = c.id AND cfd.value<>'' AND cfd.instanceid = ?";
    $result2 = $DB->get_recordset_sql($query2, ['codcurso',$id]);
    foreach($result2 as $lc2){
        $result['codcurso'] = $lc2->codcurso;
    }
    //busca codcurso en grupos
    if($uid != -1){
        $query2a = "SELECT name FROM {groups_members} INNER JOIN {groups} WHERE {groups_members}.groupid = {groups}.id AND userid = ".$uid." AND courseid = '".$id."'"; 
        $courseIds = $DB->get_records_sql($query2a);
        if(count($courseIds) == 1){
            $tmp = array_values($courseIds)[0]->name;
            if(is_numeric($tmp)){
                 $result['codcurso'] = $tmp;
            }
        }
    }

    $query3 = "SELECT cfd.value as 'successurl' FROM {course} c,{customfield_data} cfd,{customfield_field} cff WHERE cff.shortname=? AND cff.id = cfd.fieldid AND cfd.instanceid = c.id AND cfd.value<>'' AND cfd.instanceid = ?";
    $result3 = $DB->get_recordset_sql($query3, ['successurl',$id]);
    foreach($result3 as $lc2){
        $result['successurl'] = $lc2->successurl;
    }
    return $result;
}

