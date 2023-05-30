<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('getUserLevel')) {

    function getUserLevel($Designation) {
        if ($Designation == 'RSM') {
            $level = "Level3";
        } else if ($Designation == 'MSO') {
            $level = "Level1";
        } else if ($Designation == 'FM') {
            $level = "Level2";
        } else if ($Designation == 'SM') {
            $level = 'Level4';
        } else {
            $level = "Level3";
        }
        return $level;
    }

}

function mssql_escape($str) {
    if (get_magic_quotes_gpc()) {
        $str = stripslashes(nl2br($str));
    }
    return str_replace("'", "''", $str);
}

if (!function_exists('select_option_Plant')) {

    function select_option_Plant($table, $field_id, $field_name, $userid) {
        $ci = get_instance();

        $ci->load->model('product_m');
        $option = $ci->product_m->option_select_Plant($table, $field_id, $userid);

        foreach ($option as $opt) {
            $opt_id = $opt[$field_id];
            $opt_name = $opt[$field_name];
            //echo "<option value=\"$opt_id\">$opt_name</option>";
            echo "<option value=" . "'" . $opt_id . "'" . ">$opt_name</option>";
        }
    }

}

if (!function_exists('select_option_Business')) {

    function select_option_Business($table, $field_id, $field_name, $userid) {
        $ci = get_instance();

        $ci->load->model('product_m');
        $option = $ci->product_m->option_select_Business($table, $field_id, $userid);

        foreach ($option as $opt) {
            $opt_id = $opt[$field_id];
            $opt_name = $opt[$field_name];
            //echo "<option value=\"$opt_id\">$opt_name</option>";
            echo "<option value=" . "'" . $opt_id . "'" . ">$opt_name</option>";
        }
    }
}

function doPrntSelectOption($array, $valuename, $showname, $selectedvalue = '', $selectedcolumn = ''){
    $string = '';
    if(!empty($array)){
        foreach($array AS $row){
            if(!empty($selectedcolumn)){
                if($row[$selectedcolumn] == 1){
                     $selected = ' selected="selected" ';
                }else{
                    $selected = '';
                }
            }else{
                 if($selectedvalue == $row[$valuename]){
                    $selected = ' selected="selected" ';
                 }else{
                    $selected = '';
                 }
            }
            
            $string .= "<option ".$selected." value='".$row[$valuename]."'>".$row[$showname]."</option>";
        }
    }
    return $string;
}

function passEncode($password){
    $ENCPassword = "";
    For ($i = strLen($password) - 1; $i >= 0; $i--) {
        $ENCPassword .= Chr(ord(substr($password, $i, 1)) + 104);
    }
    return $ENCPassword;
}