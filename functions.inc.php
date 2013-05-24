<?php
  /*
    functions library
    
    function show_errors
    function breaks
    function dberror
    function canonical
    function links
    function cleanname
    function findbase
    function findext
    function clean
    function strip
    function h
    function text
    function headline
    function form_row_class
    function error_for
    function selected
    function checked
    function blank_error
    function blank_email
    function blank_select
    function error_email
    function dater
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
  
  // display a canonical url on our pages
  function canonical($url) {
    $canon = "<link rel=\"canonical\" href=\"". $url . $_SERVER['REQUEST_URI'] ."\" />\n";
    
    return $canon;
  }

  // format links as active
  function links($whatever, $title) {
    if(stristr($whatever, $title)){ echo " class=\"active\""; }
  }

  // cleans input
  function cleanname($input) {
    // $input = stripslashes($input);
    //     $input = str_replace("'", "", $input);
    //     $input = str_replace(' ', '-', $input);
    //     $input = str_replace('--', '-', $input);
    //     $input = str_replace(array('!', '\\', '*', '?', '(', ')'), '', $input);
    //     $input = preg_replace('/[^A-Za-z0-9_.-]/', '', $input);

    return strtolower(headline($input));
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
  
  // sanitize the user input (strip tags)
  function strip($string) {
    return mysql_real_escape_string(strip_tags($string));
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
  
  // format a headline for url
  function headline($headline) {
    // strip out bad characters
    $headline = str_replace("'s", "s", $headline);
    $headline = str_replace("'d", "d", $headline);
    $headline = str_replace("'t", "t", $headline);
    $headline = str_replace("'ll", "ll", $headline);
    $headline = str_replace("'", "", $headline);
    $headline = str_replace(" ", "-", $headline);
    $headline = str_replace("&amp;", "-", $headline);
    $headline = str_replace("&ndash;", "-", $headline);
    $headline = str_replace("&rsquo;", "-", $headline);
    $headline = str_replace("&rdquo;", "-", $headline);
    $headline = str_replace("&lsquo;", "-", $headline);
    $headline = str_replace("&ldquo;", "-", $headline);
    $headline = str_replace(".", "", $headline);
    $headline = str_replace(",", "", $headline);
    $headline = str_replace(";", "", $headline);
    $headline = str_replace(":", "", $headline);
    $headline = str_replace("!", "", $headline);
    $headline = str_replace(".", "", $headline);
    $headline = str_replace("?", "-", $headline);
    $headline = str_replace("&", "-", $headline);
    $headline = str_replace("---", "-", $headline);
    $headline = str_replace("--", "-", $headline);
    $headline = str_replace(array('!', '\\', '*', '?', '(', ')'), '', $headline);
    $headline = preg_replace('/[^A-Za-z0-9_.-]/', '', $headline);
    $headline = stripslashes($headline);
    $headline = strtolower($headline);
    
    return $headline;
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
    if(h($field) === $answer) {
      return " selected=\"selected\"";
    }
  }
  
  // checklist item is checked
  function checked($field) {
    if(h($field) == "checked") {
      return " checked=\"yes\"";
    }
  }
  
  // error handling for blank fields
  function blank_error($field, $message) {
    global $errors;
    
    if(0 === preg_match("/\S+/", $_POST[$field])) {
      return $errors[$field] = "$message";
    }
  }
  
  // error handling - validate email somewhat
  function blank_email($field) {
    global $errors;
    
    if(0 === preg_match("/.+@.+\..+/", $_POST[$field])) {
      return $errors[$field] = "Please enter a valid email address";
    }
  }
  
  // error handling - select/option fields
  function blank_select($field, $message) {
    global $errors;
    
    if ($_POST[$field] == "Please Select") {
      return $errors[$field] = "$message";
    }
  }
  
  // send an error email
  function error_email($message) {
    mail('to email', 'subject', $message, 'From: reply email', '-f reply email');
  }
  
  // format the date for mysql
  function dater($date) {
    $pm_pos     = strpos($date, "pm");
    $date_str   = substr($date, 0, -2);
    $date_exp   = explode(":", $date_str);
    $final_time = substr($date_exp[0], -2);

    if ($pm_pos != False && $final_time != "12") {
      $final_time = $final_time + "12";
    }

    $final_date = substr($date_exp[0], 0, -2)." ". $final_time .":". rtrim($date_exp[1]) . ":00";

    return $final_date;
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