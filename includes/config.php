<?php
$hostname="localhost";
$username="root";
$password="root";
$db="info2";
//
$connect =mysql_connect($hostname,$username,$password);
mysql_select_db($db,$connect);
//
include('class.php');
include('class.uFlex.php');
include('Simple_Image.php');
include('defines.php');
//	
$user = new uFlex();	       
$reg= new info_registration;	
?>