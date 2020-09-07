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
require_login();
global $DB;


function sence_get_session_info($runalumno,$codsence){
  $sesionsence = $DB->get_record('block_if_sence_login',['runalumno' => $runalumno, 'codsence' => $codsence]);
  return $sesionsence;
}

function sence_write_session($runalumno,$idsesionalumno,$idsesionsence,$codsence,$fechahora){
  $sesionsence = sence_get_session_info($runalumno,$codsence);
  if($sesionsence){
    $sesionsence->idsesionalumno = $idsesionalumno;
    $sesionsence->idsesionsence = $idsesionsence;
    $sesionsence->fechahora = $fechahora;
    $DB->update_record('block_if_sence_login',$sesionsence);
  }else{
    $nsesionsence = new stdClass();
    $nsesionsence->runalumno = $runalumno;
    $nsesionsence->idsesionalumno = $idsesionalumno;
    $nsesionsence->idsesionsence = $idsesionsence;
    $nsesionsence->codigocurso = $codsence;
    $nsesionsence->fechahora = $fechahora;
    $DB->insert_record('block_if_sence_login',$nsesionsence);
  }
}

function sence_invalidate_session($runalumno,$codsence){
  sence_write_session_info($runalumno,'','',$codsence,'');
}

function sence_validate_session($runalumno,$codsence){
  #esta funcion revisa si el ultimo login en sence es aún válido. por el momento los logins expiran despues de 6 horas
  #o si el usuario hizo logout de moodle.
  $sesionsence = sence_get_session_info($runalumno,$codsence);
  if($sesionsence){
    if($sesionsence->idsesionsence !== ''){
      $sesionmdl = strval(sesskey());
      if($sesionmdl == strval($sesionsence->idsesionalumno)){
        $now = date("yy-m-d h:m:s");
        $horalogin = date(strtotime($sesionsence->fechahora),strtotime("+6 hours"));
        if($horalogin > $now){
          return True;
        }
      }
    }
  }
  return False;
}

