<?php 
//if(isset($_POST['seid'])) {session_id($_POST['seid']);}
include ("../includes/config.php");
	$user->logout();
	header("Location: ../index.php");
        echo '<script>window.location = "../index.php";</script>';
?>