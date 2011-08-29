<?php
  /*
    functions library
    
    function show_errors
    function breaks
    function dberror
    function links
    function cleanname
    function findbase
    function findext
    function sanitize
    function h
    function text
    function form_row_class
    function error_for
    function selected
    function checked
    function mkthumb
  */
  
  // show php errors
  function show_errors() {
    ini_set("display_errors", "on");
  }

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
    if(stristr($whatever, $title)){ echo " class=\"active\""; }
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
  
  // format our text
  function text($text) {
    // paragraphs and line breaks
    $text = str_replace('\r\n', '\n', $text);
    $text = str_replace('\r', '\n', $text);
    $text = str_replace('\n\n', '</p><p>', $text);
    $text = str_replace('\n', '<br />', $text);
    
    return $text;
  }
  
  function form_row_class($name) {
    global $errors;
    return $errors[$name] ? "form_error_row" : "";
  }
  
  // returns error text for forms
  function error_for($name) {
    global $errors;
    
    if(!empty($errors[$name])) {
      return "<br /> <div class=\"error\">". $errors[$name] ."</div>";
    }
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
  
  // create a thumbnail
  function mkthumb($filename, $max) {
    $thumb_width = $max;
    $thumb_height = $thumb_width;

    // check extension
    if(preg_match('/\.gif$/i', $filename)) {
      $srcimage = imagecreatefromgif($filename);
    } elseif (preg_match('/\.png$/i', $filename)) {
      $srcimage = imagecreatefrompng($filename);
    } else {
    // assume jpg by default
      $srcimage = imagecreatefromjpeg($filename);
    }

    // determine file dimensions
    $width = imagesx($srcimage);
    $height = imagesy($srcimage);

    // check file dimensions
    if(($height > $thumb_height) || ($width > $thumb_width)) {
      // determine ratio for thumb dimensions
      if($width > $height) {
        $ratio = $thumb_width / $width;
      } else {
        $ratio = $thumb_height / $height;
      }

      // set thumb dimensions
      $new_width = round($width * $ratio);
      $new_height = round($height * $ratio);
      $dest_image = ImageCreateTrueColor($new_width, $new_height);

      imagecopyresampled($dest_image, $srcimage, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

      imagedestroy($srcimage);

    } else {
      // image is already the correct size
      $dest_image = $srcimage;
    }

    imagejpeg($dest_image, $filename);
    imagedestroy($dest_image);
  }
?>