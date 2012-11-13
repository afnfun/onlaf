<?php
//if(isset($_POST['seid'])) {session_id($_POST['seid']);}
include ("../includes/config.php");
if(!$user->signed) header("Location: ../index.php");
$st = $_GET['st'];
//echo $st;echo 
//$user->data['user_id'];
if ($st=='1ok') $reg->update_privacy_flag(1,$user->data['user_id']);
if ($st=='1notok') $reg->update_privacy_flag(1,$user->data['user_id']);

if ($st=='2ok') $reg->update_privacy_flag(2,$user->data['user_id']);
if ($st=='2notok') $reg->update_privacy_flag(2,$user->data['user_id']);

if ($st=='3ok') $reg->update_privacy_flag(4,$user->data['user_id']);
if ($st=='3notok') $reg->update_privacy_flag(4,$user->data['user_id']);

if ($st=='4ok') $reg->update_privacy_flag(8,$user->data['user_id']);
if ($st=='4notok') $reg->update_privacy_flag(8,$user->data['user_id']);

?>