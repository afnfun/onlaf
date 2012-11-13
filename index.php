<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="images/favicon.ico" />
<?php 
//if(isset($_POST['seid'])) {session_id($_POST['seid']);}
include ("includes/config.php");
if($user->signed) header("Location: pages/main_user.php"); // if user is signed, then go by default to main user page
//
//echo "<p style='text-align:center;'>Trial Version (Just for Testing) </p>";
//echo "<h4 style='text-align:center;'>Please, do not use or print any labels at this moment, data may be lost during testing phase. </h4>";
if (!$_POST || ($_POST && $_POST['request']!='change_language')) // if page is loaded initially or page refreshed without any language change request
    { 
        if (isset($_COOKIE['olaffile'])) // if there is a cookie, read default language from it
            {
                $_SESSION['lang']['file']=htmlspecialchars($_COOKIE["olaffile"]); 
                $_SESSION['lang']['css_direction']=htmlspecialchars($_COOKIE["olafcss"]); 
                $_SESSION['lang']['name_e']=htmlspecialchars($_COOKIE["olafname1"]); 
                $_SESSION['lang']['name_native']=htmlspecialchars($_COOKIE["olafname2"]); 
                $_SESSION['lang']['charset']=htmlspecialchars($_COOKIE["olafcharset"]); 
            }
        else // if there is no kookie, the default language is english
            {
                $_SESSION['lang']['file']='languages/english.lng'; 
                $_SESSION['lang']['css_direction']='ltr.css';
                $_SESSION['lang']['name_e']='english';
                $_SESSION['lang']['name_native']='English';
                $_SESSION['lang']['charset']='utf-8';
            }   
    }
if ($_POST && $_POST['request']=='change_language') // if there is language change request
    {
        foreach ($reg->language as $i => $value) // read language array 
                {
                    if ($_POST['language']==$reg->language[$i][1]) 
                    {
                        $_SESSION['lang']['file']='languages/'.$reg->language[$i][1].'.lng';
                        $_SESSION['lang']['css_direction']=$reg->language[$i][2];
                        $_SESSION['lang']['name_e']=$reg->language[$i][1];
                        $_SESSION['lang']['name_native']=$reg->language[$i][3];
                        $_SESSION['lang']['charset']=$reg->language[$i][4];
                    }
                }
         // set a cookie with current selected language
        setcookie("olaffile",$_SESSION['lang']['file']);
        setcookie("olafcss",$_SESSION['lang']['css_direction']);
        setcookie("olafname1",$_SESSION['lang']['name_e']);
        setcookie("olafname2",$_SESSION['lang']['name_native']);
        setcookie("olafcharset",$_SESSION['lang']['charset']);
    }
include ("includes/def_root.php");    
include ($_SESSION['lang']['file']); // next command is to set right page css, validation css and validation java file
echo '  <link href="'.$_SESSION['lang']['css_direction'].'" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" href="jquery_validation/css/validationEngine.jquery.'.$_SESSION['lang']['css_direction'].'" type="text/css"/>
        <script language="javascript" type="text/javascript" src="jquery_validation/js/languages/jquery.validationEngine-'.$_SESSION['lang']['name_e'].'.js" charset="'.$_SESSION['lang']['charset'].'"></script>
      ';
header('Content-Type: text/html; charset='.$_SESSION['lang']['charset']);
?>
<script language="javascript" type="text/javascript" src="functions.js"></script>
<script language="javascript" type="text/javascript" src="jquery_validation/js/jquery.validationEngine.js" charset="<?php echo $_SESSION['lang']['charset']; ?>"></script>
<script language="javascript" type="text/javascript">
jQuery(document).ready(function()
    {
       <?php if (!isset($_GET['r'])) echo ' main(); ' ?>
        show_cities();
    });	
</script>
<?php 
// if there is any QR request
if (isset($_GET['r']) && $_GET['r']=='1') // if there is language change request
    {
        echo '<script type="text/javascript">  track_code("'.$_GET['c'].'"); </script>';
        //$_GET['r']=0;
    }
?>
<link rel="image_src" href="images/thumb.png" />
<meta http-equiv="content-type" content="text/html; charset=<?php echo $_SESSION['lang']['charset']; ?>" /> 
<meta name="keywords" content="<?php echo $general['meta_keywords']; ?>" />
<meta name="description" content="<?php echo $general['meta_description']; ?>" />
<title><?php echo $general['page_title']; ?></title>
</head>
<body>
<div id="language_div" align="center" style="text-align: right; padding-right: 200; padding-top: 0; font-size: 12;">
    <form id="lang"  name="language" method="post" enctype="multipart/form-data">
        <input name="request" type="hidden"  value="change_language"   />
        <label style='color:#ffffff'><?php echo $label["language"]; ?></label>
        <select name="language" class="required" id="language" onchange="document.language.submit()" >
            <?php 
                foreach ($reg->language as $i => $value) 
                        {
                            echo '<option value="'.$reg->language[$i][1].'" '; if ($_SESSION['lang']['file']=='languages/'.$reg->language[$i][1].'.lng') echo 'selected="selected" '; echo 'onclick="change_language()">'.$reg->language[$i][3].'</option>';
                        }
            ?>
        </select>
    </form>
</div>
<div id="wrapper">
   <div id="header-wrapper">
		<div id="header">
			<div id="logo">
                            <h1><?php echo $general['logo_title'];?></h1><p>V 1.0</p>
			</div>
			<div id="menu">
				<ul>
					<li><a href="#"  onclick= "main()"><?php echo $general['menu_home']; ?></a></li>
					<li><a  href="#" id="register_link"><?php echo $general['menu_register']; ?></a></li>
					<li><a href="#" id="services_link"><?php echo $general['menu_service']; ?></a></li>
					<li><a href="#" id="contact_us_link"><?php echo $general['menu_contact']; ?></a></li>
				</ul>
			</div>
		</div>
	</div>
    <br/>
	<div id="page">
		<div id="page-bgtop">
			<div id="page-bgbtm">  
                                <!-- #content -->
				<div id="content"></div>
				<!-- end #content -->
				<div id="sidebar">
					<ul>
						<li>
                                                    <div id="sidebar_dynamic" class="sidebar" style="margin-bottom: 3px; ">&nbsp;</div>
                                                    <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                                                </li>
					</ul>
				</div>
				<!-- end #sidebar -->
				<div style="clear: both;">&nbsp;</div>
			</div>
		</div>
	</div>
	<!-- end #page -->
</div>
<div id="footer">
	<p><?php echo $general["copyright"] ?></p>
</div>
<!-- end #footer -->
</body>
</html>