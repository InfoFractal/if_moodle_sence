<?php
defined('MOODLE_INTERNAL') || die();
if($hassiteconfig){
    $settings = new admin_settingpage('block_if_sence_login', 
            get_string('adminpageheading','block_if_sence_login'));
    
    $settings->add(new admin_setting_heading('block_if_sence_login/adminsettingheading', 
            get_string('adminsettingheading','block_if_sence_login'), ''));
    $settings->add(new admin_setting_configtext('block_if_sence_login/runotec', 
            get_string('runotec','block_if_sence_login'), '', ''));
    $settings->add(new admin_setting_configtext('block_if_sence_login/tokenotec', 
            get_string('tokenotec','block_if_sence_login'), '', ''));
    $settings->add(new admin_setting_configtext('block_if_sence_login/urlerror', 
            get_string('urlerror','block_if_sence_login'), '', ''));
    //$ADMIN->add('messaging',$settings);
}
