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

function get_courses_sence_list(){
    global $DB;
    $query = "select c.id,c.fullname from mdl_course c,mdl_customfield_data cfd,"
            . "mdl_customfield_field cff where cff.shortname=? and cff.id = cfd.fieldid "
            . "and cfd.instanceid = c.id and cfd.value<>''";
    $result = $DB->get_recordset_sql($query, ['codsence']);
    return $result;
}

