<?php
include('class.php');
include('class.uFlex.php');
include('Simple_Image.php');
include('defines.php');
include('databaseConfig.php');
$connect =mysql_connect($hostname,$username,$password);
mysql_select_db($db,$connect);
//	
$user = new uFlex();	       
$reg= new info_registration;	
?>