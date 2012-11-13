<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/favicon.ico" />
<?php 
//if(isset($_POST['seid'])) {session_id($_POST['seid']);}
include ("../includes/config.php");
include ("../includes/def.php");
if(!$user->signed) header("Location: ../index.php");
foreach ($reg->language as $i => $value) // read language array 
    {
        if ($user->data['language']==$reg->language[$i][1]) // set session language to user predefined language
                {
                    $_SESSION['lang']['file']='languages/'.$reg->language[$i][1].'.lng';
                    $_SESSION['lang']['css_direction']=$reg->language[$i][2];
                    $_SESSION['lang']['name_e']=$reg->language[$i][1];
                    $_SESSION['lang']['name_native']=$reg->language[$i][3];
                    $_SESSION['lang']['charset']=$reg->language[$i][4];
                }
    }
$_SESSION['user']=$user->data['user_id'];
include ("../".$_SESSION['lang']['file']);// next command is to set right page css, validation css and validation java file
echo '<link href="../'.$_SESSION['lang']['css_direction'].'" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" href="../jquery_validation/css/validationEngine.jquery.'.$_SESSION['lang']['css_direction'].'" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="../flexigrid-1.1/css/flexigrid.'.$_SESSION['lang']['css_direction'].'" />
    <script language="javascript" type="text/javascript" src="../jquery_validation/js/languages/jquery.validationEngine-'.$_SESSION['lang']['name_e'].'.js" charset="'.$_SESSION['lang']['charset'].'"></script>
      ';
?><script language="javascript" type="text/javascript" src="../jquery_validation/js/jquery.validationEngine.js" charset="<?php echo $_SESSION['lang']['charset']; ?>"></script>
<script language="javascript" type="text/javascript" src="../functions.js"></script>
<script language="javascript" type="text/javascript">
   //// when country list changes
function show_cities_user()
    {
        jQuery("select[name='country']").change(function()
            {
                // path of ajax-loader gif image
                var ajaxLoader = "<img src='../images/ajax-loader.gif' alt='loading...' />";
                // get the selected option value of country
                var optionValue = jQuery("select[name='country']").val(); 
                if(optionValue == '<?php echo $user->data['country']; ?>') 
                    {
                        $('#current_city').css('display', '');
                        $('#cityAjax').css('display', 'none');
                        <?php // shows "other" field beside city in case if user city is not in the list of cities
                        if (!$reg->check_city_in_country($user->data['country'],$user->data['city'])) echo "$('#others').css('display', '');";
                        else echo "$('#others').css('display', 'none');";
                        ?>
                    }
                else
                    {
                        $('#current_city').css('display', 'none');
                        $('#others').css('display', 'none');
                        /**
                        * pass country value through GET method as query string
                        * the 'status' parameter is only a dummy parameter (just to show how multiple parameters can be passed)
                        * if we get response from data.php, then only the cityAjax div is displayed
                        * otherwise the cityAjax div remains hidden
                        */
                        jQuery("#cityAjax")
                        .html(ajaxLoader)
                        .load("post_user.php", "country="+optionValue+"&status=1"+"&request=show_city", function(response)
                            {
                                if(response) 
                                    {
                                        jQuery("#cityAjax").css('display', 'block');
                                    } 
                                else 
                                    {
                                        jQuery("#cityAjax").css('display', 'none');
                                    }
                            }); 
                    }
            }); 
    }
$(document).ready(function()
    {
        main_user();
    });
</script>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $_SESSION['lang']['charset']; ?>"> 
<meta name="keywords" content="<?php echo $general['meta_keywords']; ?>" />
<meta name="description" content="<?php echo $general['meta_description']; ?>" />
<meta http-equiv="content-type" content="text/html; charset=<?php echo $_SESSION['lang']['charset']; ?>" />
<title><?php echo $general['page_title']; ?></title>
</head>
<body>
<div id="wrapper">
	<div id="header-wrapper">
		<div id="header">
			<div id="logo">
				<h1><?php echo $general['logo_title']; ?></h1>
			</div>
			<div id="menu">
				<ul>
                                    <li><a href="main_user.php"><?php echo $login_general['main']; ?></a></li>
                                        <li><a href="#" id="add_item_user_link"><?php echo $show['add_code']; ?></a></li>
					<li><a href="show_items.php" ><?php echo $login_general['codes']; ?></a></li>
                                        <li><a href="#" id="edit_data_user_link"><?php echo $login_general['profile']; ?></a></li>
                                        <li><a href="#" id="contact_us_user_link"><?php echo $login_general['contact']; ?></a></li>
					<li><a href="logout.php"><?php echo $login_general['logout']; ?></a>
				</ul>
			</div>
		</div>
	</div>
	<!-- end #header -->
	<br/>
        <div id="page">
		<div id="page-bgtop">
			<div id="page-bgbtm">
                            	<table class="flexme3" style="display: none"></table>
                                <!-- #Contents -->
                                <div id="content"></div>
				<!-- end #content -->
				<div id="sidebar">
					<ul>
						<li>
                                                    <br/><br/>
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
	<p><?php echo $general['copyright']; ?>.</p>
</div>
<!-- end #footer -->
</body>
</html>