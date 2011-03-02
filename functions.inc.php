<?php
  /*
    functions library
  
    function breaks
    function dberror
    function links
    function cleanname
    function findbase
    function findext
    function sanitize
    function h
    function form_row_class
    function error_for
    function selected
    function checked
  */

  // grabs the first paragraph
  function breaks($content) {
    $text = explode('</p>', $content);

    return $text[0];
  }
  
  // check if there was a mysql query error
  function dberror($db_call) {
    if(!$db_call) { die("<p>There was an error retrieving information from the database. Error: ". mysql_error() ."</p>\n"); }
  }

  // format links as active
  function links($whatever, $title) {
    if($title == "$whatever"){ echo " id=\"current\""; }
  }

  // cleans input
  function cleanname($input) {
    $input = stripslashes($input);
    $input = str_replace("'", "", $input);
    $input = str_replace(' ', '-', $input);
    $input = str_replace('--', '-', $input);
    $input = str_replace(array('!', '\\', '*', '?', '(', ')'), '', $input);
    $input = preg_replace('/[^A-Za-z0-9_.-]/', '', $input);

    return strtolower($input);
  }


  // find the basename of a file
  function findbase($filename) {
    $base = explode(".", $filename);
    $count = (count($base)-1);
    $i = 0;
    while($i < $count) {
        $returnbase=$returnbase . $base[$i] . ".";
        $i++;
    }
    $returnbase = rtrim($returnbase, '.');

    return cleanname($returnbase); // cleans the file basename
  }

  // finds the extension of a file
  function findext($filename) {
    $base = explode(".", $filename);
    $count = count($base)-1;
    $ext = $count;

    return ".".$base[$ext];
  }

  // sanitize the user input
  function clean($string) {
    return mysql_real_escape_string($string);
  }
  
  // escape out any special html characters
  function h($string) {
    return htmlspecialchars($string);
  }
  
  function form_row_class($name) {
    global $errors;
    return $errors[$name] ? "form_error_row" : "";
  }
  
  // returns error text for forms
  function error_for($name) {
    global $errors;
    return "<div class=\"error\">". $errors[$name] ."</div>";
  }
  
  // selected from drop down
  function selected($field, $answer) {
    if(h($field) == $answer) {
      return " selected=\"selected\"";
    }
  }
  
  // checklist item is checked
  function checked($field) {
    if(h($field) == "checked") {
      return " checked=\"yes\"";
    }
  }
?>