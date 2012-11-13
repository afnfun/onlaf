<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/favicon.ico" />
<?php 
//if(isset($_POST['seid'])) {session_id($_POST['seid']);}
include ("../includes/config.php");
include ("../includes/def.php");
if($user->signed) header("Location: ../pages/main_user.php");
if (!$_POST || ($_POST && $_POST['request']!='change_language')) // if page is loaded initially or page refreshed without any language change request
    {   if (isset($_COOKIE['olaffile'])) // if there is a cookie, read default language from it
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
include ('../'.$_SESSION['lang']['file']); // next command is to set right page css, validation css and validation java file
echo '   <link href="../'.$_SESSION['lang']['css_direction'].'" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" href="../jquery_validation/css/validationEngine.jquery.'.$_SESSION['lang']['css_direction'].'" type="text/css"/>
        <script language="javascript" type="text/javascript" src="../jquery_validation/js/languages/jquery.validationEngine-'.$_SESSION['lang']['name_e'].'.js" charset="'.$_SESSION['lang']['charset'].'"></script>
      ';
?>
<script language="javascript" type="text/javascript" src="../functions.js"></script>
<script language="javascript" type="text/javascript" src="../jquery_validation/js/jquery.validationEngine.js" charset="<?php echo $_SESSION['lang']['charset']; ?>"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function()
    {
        jQuery("#change_password").validationEngine();
    });
$(function() 
    {
        $( ".change button:first" ).button({icons: {secondary: "ui-icon-key"}}).click(function() {$('form#change_password').submit();});
        $( ".back button:first" ).button({icons: {secondary: "ui-icon-close"}});
        $( ".home button:first" ).button({icons: {secondary: "ui-icon-close"}}).click(function() {window.location = "../index.php";});  
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
		</div>
	</div>
        <br/>
	<div id="page">
		<div id="page-bgtop">
			<div id="page-bgbtm">
				<div id="content">
					<?php
                                                        //Proccess Password change
                                                        if (!$_POST){  
                                                       echo '<h6>'.$change['change_message'].'</h6><br/><br/>
                                                       <form name="change_password" id="change_password" method="post" action="">
                                                           <table width="250">
                                                            <tr>
                                                                <td>
                                                                    <label>'.$label["write_new_password"].'</label><span class="required">*</span>
                                                                    <input name="password" type="password" class="validate[required,minSize[6],maxSize[16]] text-input" >
                                                                </td>
                                                                </tr>      
                                                            <tr>
                                                                <td>
                                                                    <label>'.$label["re_write_new_password"].'</label><span class="required">*</span>
                                                                    <input name="password2" type="password" class="validate[required,minSize[6],maxSize[16]] text-input" >
                                                                </td>
                                                            </tr>        
                                                            </table>
                                                            </form>
                                                       <div class="change">    
                                                                <button>'.$button["change"].'</button>
                                                       </div>';
                                                       }if(count($_POST))
                                                            {
                                                           if ($_POST['password']==$_POST['password2'])
                                                               {
                                                                if(!$user->signed and isset($_GET['c']))
                                                                    {
                                                                        //Change password with confirmation hash
                                                                        $user->new_pass($_GET['c'],$_POST);	
                                                                    }
                                                                else
                                                                    {
                                                                        //Change the password of signed in user without a confirmation hash 
                                                                        $user->update($_POST);			
                                                                    }
                                                                echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                                                                            <div id="effect3" class="ui-widget-content ui-corner-all">
                                                                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['change_password'].'</h5>
                                                                                <div id="toggler_contents">';
                                                                                 if($user->has_error()) 
                                                                                 {
                                                                                     foreach($user->error() as $i=>$x)
                                                                                        {
                                                                                            echo $uFlex[$x];
                                                                                        }
                                                                                 }
                                                                                 else echo $uFlex['Password_Changed'];
                                                                echo '          </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="home"> 
                                                                            <button >'.$button['home'].'</button>
                                                                        </div>';
                                                                }
                                                            else
                                                            {
                                                               echo ' <div class="toggler3" style=" padding-bottom: 15px;">
                                                                            <div id="effect3" class="ui-widget-content ui-corner-all">
                                                                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['change_password'].'</h5>
                                                                                <div id="toggler_contents"> 
                                                                             '.$edit['unsimilar_pass'].'    
                                                                          </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="back" > 
                                                                            <button onclick="window.location.reload( true );" >'.$button['back'].'</button>
                                                                        </div>';}
                                                                    
                                                            }
                                                        else if(!$user->signed and !isset($_GET['c']))
                                                            {
                                                                //Refirect
                                                                header("Location: index.php");
                                                            }
                                                       ?>
                                                    </div>
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
	<p><?php echo $general["copyright"] ?></p>
</div>
<!-- end #footer -->
</body>
</html>
