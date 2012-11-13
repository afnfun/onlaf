<?php
//if(isset($_POST['seid'])) {session_id($_POST['seid']);}
include ("../includes/config.php");
if(!$user->signed) header("Location: ../index.php");
//
// request: return the status of given code of given user 
if($_GET['request']=='check_code_ststus'){
$x=$reg->get_item_of_user($_GET['user_id'],$_GET['code']);
echo json_encode(array("status" => $x['code_status']));
}
// request: accept code 
if($_GET['request']=='accept_code'){
$move=$reg->confirm_code_recieve($_SESSION['item_code']);
$_SESSION['report']= $reg->report;
}
?>
