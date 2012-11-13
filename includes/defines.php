<?php 
function check_lang() { 
//make sure that we have a language selected by using session .. 
  if (!isset($_SESSION['lang'])) { 
  /* you can either show error message and terminate script 
    die('No language was selected! please go back and choose a langauge!');*/ 
  //or set a default language 
    $lang = 'english'; 
  } else { 
    $lang = $_SESSION['lang']; 
  } 
//directory name 
  $dir = 'languages'; 
//no we return the langauge wanted ! 
//Returned String Format: dirname/filename.ext 
  return "$dir/$lang.lng"; 
} 
?>