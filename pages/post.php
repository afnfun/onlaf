<?php
//if(isset($_POST['seid'])) {session_id($_POST['seid']);}
include ("../includes/config.php");
include ("../".$_SESSION['lang']['file']);
//$errors = '';
header('Content-Type: text/html; charset='.$_SESSION['lang']['charset']);
// show tracking input @ main page
if ($_POST && $_POST['request']=="main"){
    echo '
        <div class="toggler" align="center">
            <div id="effect" class="ui-widget-content ui-corner-all">
                <h3 class="ui-widget-header ui-corner-all">'.$label['track_code'].'</h3>
                <div id="toggler_contents">
                    <form id="track_code_main" name="track_code_main"  onkeypress="checkForEnter(event,this.id)">
                        <input name="request" type="hidden"  value="track"/>   
                        <label for="item_code" style="text-align:center;" >'.$label['enter_track_code'].'</label>
                        <input  name="item_code" class="validate[required] text ui-widget-content ui-corner-all" type="text"   size="24" style="border-width: 5px; font-size: 16px;" />
                    </form>
                   <button id="track_code_button" align="center">'.$button['enter_tracking_code'].'</button>
                </div>
            </div>
        </div>
     ';
}
// show tracking result @ main page
if ($_POST && $_POST['request']=='track'){
    $data=$reg->track_code($_POST['item_code']);
    if(!$data) {
            echo '
                <div class="toggler3">
                    <div id="effect3" class="ui-widget-content ui-corner-all">
                        <h4 class="ui-widget-header ui-corner-all">'.$label['not_found'].'</h3>
                        <div id="toggler_contents">'.$reports[$reg->report].'</div>
                    </div>
                </div>
                <br/><br/>
                <button id="home">'.$button["home"].'</button>
            ';
            }
    else{
        $_SESSION['data']=$data;
        $data['item_image']=substr($data['item_image'],3);
        echo '
            <div class="toggler2" >
                <div id="effect2" class="ui-widget-content ui-corner-all">
                    <div class="toggler3" >
                        <div id="effect3" class="ui-widget-content ui-corner-all">
                            <h4 class="ui-widget-header ui-corner-all">'.$show['item_details'].'</h4>
                            <div style="text-align: center; "><img style="padding-top: 10px; padding-bottom: 10px; border-radius:20px;-moz-border-radius:20px; width:300px; height:auto;" align="center" src='.$data['item_image'].'>
                            </div>
                            <dl>
                                    <dt><h3>'.$label['item_name'].'</h3></dt>
                                    <dd><p>'.$data['item_name'].'</p></dd><br/>
                            </dl>
                        </div>
                        <div id="more_details_div">
                            <div class="toggler3">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <div id="captcha" class="captcha" >
                                            <p><img style="border-radius:10px;-moz-border-radius:10px;" src="captcha/captcha_code_file.php?rand='.rand().'" id="captchaimg" ></p>
                                            <p><label for="message">'.$label['enter_captcha_code'].'</label></p>
                                            <form id="captcha_form_more_details" onkeypress="checkForEnter(event,this.id)" >
                                                <p><input id="6_letters_code" name="6_letters_code" type="text"></p>
                                            </form>
                                            <p><small>'.$captcha['refresh1'].' <a href="javascript: refreshCaptcha();"> '.$captcha['refresh2'].'</a> '.$captcha['refresh3'].'</small></p>
                                    </div> 
                                    <button id="more_details">'.$button["show_details"].'</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
            ';    
//                    
        echo '
        <br/>
        <button id="home">'.$button["home"].'</button>
        ';                                                                  
        }
}
// more data
if ($_POST && $_POST['request']=="more_details")
{
    if(empty($_SESSION['6_letters_code'] ) || strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
	{
            echo '  <script language="javascript" type="text/javascript" > refreshCaptcha(); </script>
                    <div class="toggler3">
                                    <div id="effect3" class="ui-widget-content ui-corner-all">
                                        <div>'.$captcha['no_match'].' </div>
                                        <div id="captcha" class="captcha" >
                                            <p><img style="border-radius:10px;-moz-border-radius:10px;" src="captcha/captcha_code_file.php?rand='.rand().'" id="captchaimg" ></p>
                                            <p><label for="message">'.$label['enter_captcha_code'].'</label></p>
                                            <form id="captcha_form_more_details" onkeypress="checkForEnter(event,this.id)" >
                                                 <p><input id="6_letters_code" name="6_letters_code" type="text"></p>
                                            </form>
                                            <p><small>'.$captcha['refresh1'].' <a href="javascript: refreshCaptcha();"> '.$captcha['refresh2'].'</a> '.$captcha['refresh3'].'</small></p>
                                        </div> 
                                        <button id="more_details">'.$button["show_details"].'</button>
                                    </div>
                                </div>
                            </div>
            ';
	}
    else 
	{
            echo'   <div class="toggler3" >
                        <div id="effect3" class="ui-widget-content ui-corner-all">
                        <h4 class="ui-widget-header ui-corner-all">'.$label['more_details'].'</h4>
                            <dl>';
                            //
                            echo'<dt><h3>'.$label['item_description'].'</h3></dt>';
                            echo'<dd><p>'.$_SESSION['data']['item_description'].'</p></dd><br/>';
                            //
                            echo'<dt><h3>'.$label['owner_name'].'</h3></dt>';
                            if($_SESSION['data']['pr_name']==1) echo'<dd><p>'.$_SESSION['data']['name'].'&nbsp;'.$_SESSION['data']['surename'].'</p></dd><br/>';
                            else echo'<dd><p>'.$message['not_available'].'</p></dd><br/>';
                            //
                            echo ' <dt><h3>'.$label["owner_phone"].'</h3></dt>';
                            if($_SESSION['data']['pr_phone']==8 && $_SESSION['data']['phone']) echo'<dd><p>'.$_SESSION['data']['phone'].'</p></dd><br/>';
                            else echo'<dd><p>'.$message['not_available'].'</p></dd><br/>';
                            //
                            echo '<dt><h3>'.$label["owner_email"].'</h3></dt>';
                            if($_SESSION['data']['pr_email']==2) echo '<dd><p>'.$_SESSION['data']['email'].'</p></dd><br/>';
                            else echo'<dd><p>'.$message['not_available'].'</p></dd><br/>';
                            //
                            echo '<dt><h3>'.$label["owner_address"].'</h3></dt>';
                            if($_SESSION['data']['pr_address']==4 && ( $_SESSION['data']['address'] || $_SESSION['data']['country'] || $_SESSION['data']['city'])) echo'<dd><address>'.$_SESSION['data']['address'].'<br/>'.$_SESSION['data']['city'].'<br/>'.$_SESSION['data']['country'].'<br/></address></dd><br/>'; 
                            else echo'<dd><p>'.$message['not_available'].'</p></dd><br/>';
                            //
 echo'                  </div>
                    <br/>
                    <button id="contact_owner">'.$button["contact_owner"].'</button>
                    </div> 
                    
                    ';
        }
}
// show contact owner message form @ main page
if ($_POST && $_POST['request']=='contact_owner'){
     echo '
        <div class="toggler3" style=" padding-bottom: 15px;">
            <div id="effect3" class="ui-widget-content ui-corner-all">
                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$index["send_owner_message"].'</h5>
                <div id="toggler_contents">
                    <form id="postmail"  name="postmail" method="post" enctype="multipart/form-data" onkeypress="checkForEnter(event,this.id)" >
                        <table  width="450">
                            <tr>
                                <td>
                                    '.$mail_m['subject'].$mail_m['contact_subject_founder'].'
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>'.$grid['name'].'</label>
                                    <input name="name" type="text" class="validate[required,minSize[2],maxSize[35]] text-input" style="width:60%;"/>
                                </td>
                            </tr>
                           <tr>
                                <td>
                                    <label>'.$label["email"].'&nbsp;'.$mail_m['optional'].'</label> 
                                    <input name="email" class="validate[custom[email]] text-input" type="text" style="width:60%;"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>'.$label['phone'].'&nbsp;'.$mail_m['optional'].'</label> 
                                    <input name="phone" class="validate[custom[phone]] text-input" type="text" style="width:60%;"/>
                                </td>  
                            </tr>
                            <tr>
                                <td>
                                    <label>'.$label["message"].' </label>
                                    <textarea name="body" type="text" class="validate[required,minSize[8],maxSize=[1024]] text-input" style="width:70%;" rows="6" ></textarea>
                                </td>
                           </tr>
                           <tr>
                                <td>
                                
                                </td>
                            </tr>
                       </table>
                       <input name="request" type="hidden"  value="send_owner_message"   /> 
                   </form>
                </div>
            </div>
       </div>
       <button id="send_owner_message">'.$button["send"].'</button>
       <button id="home">'.$button["cancel"].'</button>
       '; 
}
// execute send owner message @ main page
if ($_POST && $_POST['request']=='send_owner_message'){
if (!isset($_POST['email'])) $_POST['email']="-";
if (!isset($_POST['phone'])) $_POST['phone']="-";
$body=$mail_m['contact_subject_founder']."\n\n".$mail_m['sender'].$_POST['name']."\n\n".$label['phone'].': '.$_POST['phone']."\n\n".$mail_m['email'].$_POST['email']."\n\n".$mail_m['subject'].$mail_m['contact_subject_founder']."\n\n".$mail_m['message']."\n\n".$_POST['body'];
$headers = 'From: Online Lost and Found <info@onlaf.org>';
                echo '
                    <div class="toggler3" style=" padding-bottom: 15px;">
                        <div id="effect3" class="ui-widget-content ui-corner-all">
                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$button["contact_owner"].'</h5>
                                <div id="toggler_contents">';
                if (mail($_SESSION['data']['email'], $mail_m['contact_subject_founder'] , $body,$headers)) 
                        echo $index["message_send_message"];
                else 
                        echo $index["message_fail_message"].'
                            </div>
                        </div>
                   </div>
                   <button id="home">'.$button["home"].'</button>
                       ';    
}
// change password @ main page      
if ($_POST && $_POST['request']=='change_password'){
    echo ' 
        <div class="toggler3" style=" padding-bottom: 15px;">
            <div id="effect3" class="ui-widget-content ui-corner-all">
                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['change_password'].'</h5>
                <form name="change_password" class="cmxform" id="change_password" method="post" action="">
                    <div id="toggler_contents">
                        '.$index['enter_email_message'].'            
                        <input name="email" id="send_change_password_email" class="validate[required,custom[email]] text-input" type="text" value="" onkeypress="checkForEnter(event,this.id)">
                        <input name="request" type="hidden"  value="send_password_verification"/>
                    </div>
                </form>   
            </div>
        </div>
        <button id="send_password_verification">'.$index["send_password"].'</button>
        ';
}
// proceed change password by sending verification code
if ($_POST && $_POST['request']=='send_password_verification')
    { 
    $mail=$user->check_field("email",$_POST['email']);
    echo '
        <div class="toggler3" style=" padding-bottom: 15px;">
            <div id="effect3" class="ui-widget-content ui-corner-all">
                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['change_password'].'</h5>
                <div id="toggler_contents">';
    if ($mail)
        {
        $res = $user->pass_reset($_POST['email']);
        if($res)
            {
            //echo $_SERVER["SERVER_NAME"].dirname($_SERVER['PHP_SELF']).'/change_password.php?c='.$res['hash'];
            //$body=$mail_m['password_retrieve_message']."\n".$_SERVER["SERVER_NAME"].dirname($_SERVER['PHP_SELF']).'/change_password.php?c='.$res['hash'];
            $body=$mail_m['password_retrieve_message'].'\n www.onlaf.org/pages/change_password.php?c='.$res['hash'];
            $headers = 'From: Online Lost and Found <info@onlaf.org>';
            //echo $_POST['email'].' : '.$mail_m['password_retrieve'].' : '.$body;
            if (mail($_POST['email'],$mail_m['password_retrieve'],$body,$headers) ) {
                echo '<p>'.$index['message_send_message'].'</p>';
                } 
            else 
                {
                echo '<p>'.$index['message_fail_message'].'</p>';
                }
            }
        }
    else 
        {
        echo '<p>'.$index['invalid_email'].'</p>';
        }
    echo '
                </div>
            </div>
        </div>
        <button id="home">'.$button["home"].'</button>
        ';
    } 
// login @ main page
if ($_POST && isset($_POST['sidebar']) && $_POST['sidebar']=="login")
{
    echo '  <form name="login_form" id="login_form" method="post" action="" onkeypress="checkForEnter(event,this.id)">
                
                            <label>'.$label['username'].'</label>
                            <input name="username" id="username_login" type="text" class="validate[required,minSize[6],maxSize[16]] text ui-widget-content ui-corner-all" style="width:70%;"/>
                        
                            <label>'.$label['password'].'</label>
                            <input name="password" id="password_login" type="password" class="validate[required,minSize[6]] text ui-widget-content ui-corner-all" style="width:70%;" />
                        
                <input name="request" type="hidden"  value="login"   />
             </form>
            <button id="login_button" >'.$button["log_in"].'</button> 
            <button id="forgot_password">'.$index["forgot_password"].'</button>';
}
if ($_POST && $_POST['request']=='login')
    {
    //Proccess Login
    @$username = $_POST['username'];
    @$password = $_POST['password'];
    @$auto = $_POST['auto'];
    @$user = new uFlex($username,$password,$auto);
    if(!$user->signed) echo'
        <div class="toggler3" style=" padding-bottom: 15px;">
            <div id="effect3" class="ui-widget-content ui-corner-all">
                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['login'].'</h5>
                    <div id="toggler_contents">'.$reports[$user->report].'</div>
              </div>
        </div>
        <button id="home">'.$button["home"].'</button>
        ';
    if($user->signed) echo '<script> location.replace("pages/main_user.php"); </script>';
    }
// contact us @ main
if ($_POST && $_POST['request']=='contact_us')
    {
    echo '  
        <div class="toggler3" style=" padding-bottom: 15px;">
            <div id="effect3" class="ui-widget-content ui-corner-all">
                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['contact_us'].'</h5>
                <div id="toggler_contents">
                    <form id="contact_us_form"  name="contact_us_form" method="post" enctype="multipart/form-data" onkeypress="checkForEnter(event,this.id)">
                        <table  width="450">
                            <tr>
                                <td>
                                    <p>'.$index['contact_paragraph'].'</p>
                                </td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>
                                    <label>'.$grid['name'].'</label>
                                    <input name="name" type="text" class="validate[required,minSize[2],maxSize[35]] text-input" style="width:60%;" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>'.$label['email'].'</label>
                                    <input name="email" type="text" class="validate[required,custom[email]] text-input" style="width:60%;" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>'.$label['subject'].'</label>
                                    <input name="subject" type="text" class="validate[required,minSize[4],maxSize[64]] text-input" style="width:60%;" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>'.$label['message'].'</label>
                                    <textarea name="body" type="text" class="validate[required,minSize[8],maxSize[1024]] text-input" style="width:80%;" rows="6" ></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input name="request" type="hidden"  value="send_contact_us"   /></label>
                                </td>   
                            </tr>
                        </table>
                    </form>
               <div id="captcha_contact">
                                   <div id="captcha" class="captcha" >
                                            <p><img style="border-radius:10px;-moz-border-radius:10px;" src="captcha/captcha_code_file.php?rand='.rand().'" id="captchaimg" ></p>
                                            <p><label for="message">'.$label['enter_captcha_code'].'</label></p>
                                            <form id="captcha_form_contact_us" onkeypress="checkForEnter(event,this.id)" >
                                                <p><input id="6_letters_code" name="6_letters_code" type="text" /></p>
                                            </form>
                                            <p><small>'.$captcha['refresh1'].' <a href="javascript: refreshCaptcha();"> '.$captcha['refresh2'].'</a> '.$captcha['refresh3'].'</small></p>
                            </div> </div>
                        
            </div>
        </div>
        <br/>
        <button id="contact_us_captcha">'.$button['send'].'</button>
        <button id="home">'.$button['cancel'].'</button>
        <button id="contact_us" style="visibility:hidden;"></button>
        ';                   
    }
if ($_POST && $_POST['request']=='check_captcha'){
       if(empty($_SESSION['6_letters_code'] ) || strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0)
	{
            echo '  <script language="javascript" type="text/javascript" > refreshCaptcha(); </script>
                    <div>'.$captcha['no_match'].' </div>
                                         <div id="captcha" class="captcha" >
                                            <p><img style="border-radius:10px;-moz-border-radius:10px;" src="captcha/captcha_code_file.php?rand='.rand().'" id="captchaimg" ></p>
                                            <p><label for="message">'.$label['enter_captcha_code'].'</label></p>
                                            <form id="captcha_form_contact_us" onkeypress="checkForEnter(event,this.id)" >
                                                <p><input id="6_letters_code" name="6_letters_code" type="text" /></p>
                                            </form>
                                            <p><small>'.$captcha['refresh1'].' <a href="javascript: refreshCaptcha();"> '.$captcha['refresh2'].'</a> '.$captcha['refresh3'].'</small></p>
                            </div> </div>
            ';
        }
    else 
	{
        // return nothing
        }
}
if ($_POST && $_POST['request']=='send_contact_us')
    {
            $body=$mail_m['contact_subject']."\n\n".$mail_m['sender'].$_POST['name']."\n\n".$mail_m['email'].$_POST['email']."\n\n".$mail_m['subject'].$_POST['subject']."\n\n".$mail_m['message']."\n\n".$_POST['body'];
            $headers = 'From: Online Lost and Found <info@onlaf.org>';
            echo '
                <div class="toggler3" style=" padding-bottom: 15px;">
                    <div id="effect3" class="ui-widget-content ui-corner-all">
                        <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['contact_us'].'</h5>
                            <div id="toggler_contents">';
            if (mail("afnfun@yahoo.com",$mail_m['contact_subject'], $body, $headers))
                {
                            echo $add['successful_sending_message'];
                } 
            else 
                {
                            echo $add['error_sending_message'];
                }
            echo '
                        </div>
                    </div>
                </div>
                <button id="home">'.$button["home"].'</button>
                ';
     }
 // services information @ main page 
 if($_POST && $_POST['request']=='services'){
     echo'
         <div id="tabs">
           <!-- <h3>'.$service['title1'].'</h3>
            <h4>'.$service['title2'].'</h4> --!>
            <hr>
            <ul>
                <li><a href="#tabs-1">'.$service['our_service'].'</a></li>
                <li><a href="#tabs-2">'.$service['labels'].'</a></li>
                <li><a href="#tabs-3">'.$service['acknowledgment'].'</a></li>
            </ul>
            <div id="tabs-1" >
                <p>&nbsp;</p>
                <p>'.$service['contents1'].'<a  href="" onclick= "go()">'.$service['register'].'</a></p>                                        </p>
                <p><img src="images/cycle.png" width="500" height="295" aligh="center"></p>
            </div>
            <div id="tabs-2">
                <p>'.$service['contents2'].'</p><br/>
                <table width="520" align="center" >
                    <tr>
                        <td width="181"><img src="images/loggage.png" width="165" height="135"></td>
                        <td width="180"><img src="images/camera.png" width="165" height="135"></td>
                        <td width="176"><img src="images/dog.png" width="165" height="135"></td>
                    </tr>
                    <tr>
                        <td><img src="images/book.png" width="165" height="135"></td>
                        <td><img src="images/phone.png" width="165" height="135"></td>
                        <td><img src="images/keychain.png" width="165" height="135"></td>
                    </tr>
                    <tr>
                        <td><img src="images/usb.png" width="165" height="135"></td>
                        <td><img src="images/wallet.png" width="165" height="135"></td>
                        <td><img src="images/hangbag.png" width="165" height="135"></td>
                    </tr>
                </table>
            </div>
            <div id="tabs-3">
            <img src="images/people/team.png" style="align:right;" /><br/><br/>
            <!--<table width="520" align="center" >
                    <tr>
                        <td><img src="images/vergne.png" width="200" height="auto"></td>
                        <td><img src="images/ali.png" width="200" height="auto"></td>
                        <td width="176"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>-->
            </div>
        </div> 
        ';
    }
// register new user @ main
if($_POST && $_POST['request']=='register')
    {
    echo '
        <div class="toggler3" style=" padding-bottom: 15px;">
            <div id="effect3" class="ui-widget-content ui-corner-all">
                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$register['registration_data'].'</h5>
                    <div id="toggler_contents">
                         <h3>'.$register['registration_data'].'</h3>
                         <form id="register_form" class="cmxform" name="register_form" method="post" enctype="multipart/form-data"  onkeypress="checkForEnter(event,this.id)">
                            <input name="request" type="hidden"  value="process_regisration"/>
                            <table  width="450">
                                <tr>
                                    <td>
                                        <label>'.$label['name'].'</label> 
                                        <input name="name" type="text" class="validate[required,minSize[2],maxSize[35]] text-input" style="width:100%;"/>
                                  </td>
                                  <td>
                                      <label>'.$label['surename'].'</label>        
                                      <input name="surename" type="text"   class="validate[required,minSize[2],maxSize[20]] text-input"  style="width:80%;"/>
                                   </td>  
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td>
                                        <label>'.$label['male'].'</label>
                                        <input name="gender" type="radio" class="validate[required] radio" type="radio" value="male" checked="checked"/>
                                    </td>   
                                    <td>    
                                        <label>'.$label['female'].'</label>
                                        <input type="radio" name="gender"  class="validate[required] radio"  value="female" />
                                    </td>
                                </tr>
                        </table>
                        <br/>
                        <h3>'.$register['contact_information'].'</h3>  
                        <table width="450">
                        <tr> 
                                <td>
                                    <label>'.$label['email'].'</label>
                                    <input name="email" type="text" class="validate[required,custom[email]] text-input" style="width:100%;" />
                                </td>
                                <td>
                                    <label>'.$label['phone'].'</label> 
                                    <input name="phone" type="text" class="validate[custom[phone]] size="20" text-input" style="width:80%;"/>
                                </td>  
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td colspan="2">
                                    <label>'.$label['address'].'</label>
                                    <input name="address" type="text" class="validate[minSize[2],maxSize[64]] text-input" style="width:90%;"/>
                                </td>  
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                        </table>
                        <table width="300"> 
                            <tr>
                                <td>
                                    <label>'.$label['country'].'</label> 
                                    <select name="country" class=" text-input" id="country" type="text" style="width:80%;" >
                                        <option  value="">'.$register['select_country'].'</option>';
                                        $reg->show_country_list();
    echo '                          </select>
                                </td>  
                            </tr>
                            <tr>
                                <td>
                                    <div id="cityAjax" style="display:none">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div id="others" style="display: none" >
                                        <label>'.$label['other_city'].'</label>
                                        <input type="text" id="other"  name="other" class="validate[minSize[2],maxSize[20]] text-input" style="width:80%;"/>
                                    </div>
                                </td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>
                                    <label>'.$label['language'].'</label> 
                                    <select name="language" class="validate[required] text-input" id="language" type="text"   >
                                    <option  value="">'.$register['select_language'].'</option>';
                                         foreach ($reg->language as $i => $value) 
                                                    {
                                                    echo '<option value="'.$reg->language[$i][1].'">'.$reg->language[$i][3].'</option>';  
                                                    }
        echo '                      </select>
                                </td> 
                            </tr>
                        </table>
                        <br/>
                        <h3>'.$register['login_information'].'</h3>  
                        <table width="300">
                            <tr> 
                                <td>
                                    <label>'.$label['reg_username'].'</label>
                                    <input name="username"  class="validate[required,minSize[6],maxSize[16]] text-input" style="width:100%;"/>
                                </td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr>
                                <td>
                                    <label>'.$label['password'].'</label>
                                    <input name="password" type="password" class="validate[required,minSize[6],maxSize[16]] text-input" style="width:100%;"/>
                                </td>
                                <td>
                                     <label>'.$label['re_password'].'</label>
                                    <input name="password2" type="password" class="validate[required,minSize[6],maxSize[16]] text-input" style="width:100%;"/>
                                </td>
                            </tr>
                            <tr><td>&nbsp;</td></tr>
                        </table>
                    </form>
                </div>
                <button id="register_button" >'.$button['register'].'</button>
                <button  id="home">'.$button['cancel'].'</button>
            </div>
        </div>
        ';
}
/// generate list of cities based on country selection @ regiter @ main
if($_GET && $_GET['request']=='show_city')
    {
    $country = $_GET['country'];
    echo "<script>show_other();</script>";
    //echo $country;
    $query = "SELECT * FROM country where printable_name='".$country."'";  
    $result = mysql_query($query) ;
    $row=mysql_fetch_array($result);
    //echo $row['Code'];
    if($row){
        $query = "SELECT Name FROM city where CountryCode='".$row['Code']."' ORDER BY Name";  
        //echo $query;
        $result = mysql_query($query) ;
        echo '<label>'.$label['city'].'</label>';
        echo '<select name="city2" id="city2" class=" text-input" style="width:80%;"> 
                <option value="">Please Select</option>';
        $i=0; 
        while($row2 = mysql_fetch_array($result))
            {
                $i++;
                if ($row2['Name']!='Other') echo '  <option value="'.utf8_encode($row2['Name']).'">'.utf8_encode($row2['Name']).'</option>';
            } 
        if($i==1) echo '<option value="'.$country.'">'.$country.'</option>';
        echo '  <option value="Other">'.$label['other_city'].'</option>';
        echo '</select>';
        }
}
// confirm data before register @main
if ($_POST && $_POST['request']=='process_regisration' ){
if (!($reg->check_field('users','email',$_POST['email']))&& !($reg->check_field('users','username',$_POST['username']))){
    $_POST['city']=$_POST['city2']; unset($_POST['city2']);
    if ($_POST['city']=='Other') {$_POST['city']=$_POST['other'];}
    unset($_POST['other']);
            $_SESSION['registation']=$_POST;
            echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                        <div id="effect3" class="ui-widget-content ui-corner-all">
                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$register['confirm_registration_message'].'</h5>
                            <div id="toggler_contents">
                  ';
                              echo $label['name'].' : <font color="#FF7200">'.$_SESSION['registation']['name'].'</font><br/>';
                              echo $label['surename'].' : <font color="#FF7200">'.$_SESSION['registation']['surename'].'</font><br/>';
                              echo $label['gender'].' : <font color="#FF7200">';
                                    if ($_SESSION['registation']['gender']=="male") echo $label['male']; 
                                    if ($_SESSION['registation']['gender']=="female") echo $label['female']; 
                                    echo '</font><br/>';
                              echo $label['email'].' : <font color="#FF7200">'.$_SESSION['registation']['email'].'</font><br/>';
                              echo $label['phone'].' : <font color="#FF7200">'.$_SESSION['registation']['phone'].'</font><br/>';
                              echo $label['address'].' : <font color="#FF7200">'.$_SESSION['registation']['address'].'</font><br/>';
                              echo $label['city'].' : <font color="#FF7200">'.$_SESSION['registation']['city'].'</font><br/>';
                              echo $label['country'].' : <font color="#FF7200">'.$_SESSION['registation']['country'].'</font><br/>';
                              echo $label['language'].' : <font color="#FF7200">'.$_SESSION['registation']['language'].'</font><br/>';
                              echo $label['reg_username'].' : <font color="#FF7200">'.$_SESSION['registation']['username'].'</font>';
                              echo'</p>
                            </div>
                        </div>
                    </div>
                    <button id="confirm_register_button" >'.$button['register'].'</button>
                    <button  id="home">'.$button['cancel'].'</button>';   
               ;
        }
        else {
            echo '
                    <div class="toggler3" style=" padding-bottom: 15px;">
                        <div id="effect3" class="ui-widget-content ui-corner-all">
                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$register['confirm_registration_message'].'</h5>
                                <div id="toggler_contents">
                 ';
                                if ($reg->check_field('users','email',$_POST['email']))echo $register['new_email_exist'].'<br/>';
                                if ($reg->check_field('users','username',$_POST['username'])) echo $register['new_username_exist'];
            echo'        `       </div>
                        </div>
                    </div>
                    <button  id="register_user">'.$button['back'].'</button>
                    <button  id="home">'.$button['cancel'].'</button>
                        ';   
               
        }
               
}
// complete registration process @ main
if ($_POST && $_POST['request']=='register_user' )
    {
       unset($_SESSION['registation']['request']);
       ////Register User
	$confirm=$user->register($_SESSION['registation'], true );
		//If there is not error
		
			//A workaround to display a confirmation message in this specific  Example
			 echo ' <div class="toggler3" style=" padding-bottom: 15px;">
                                    <div id="effect3" class="ui-widget-content ui-corner-all">
                                        <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$register['confirm_registration_message'].'</h5>
                                            <div id="toggler_contents">';
                                               if(!$user->has_error()) 
                                                       {
                                                            $activation_code=$activation['activation_mail_message']."\n".$_SERVER["SERVER_NAME"].dirname($_SERVER['PHP_SELF']).'/activation.php?c='.$confirm.'&d='.$_SESSION['registation']['username'];
                                                            $headers = 'From: Online Lost and Found <info@onlaf.org>';
                                                            //echo 'mail('.$_SESSION['registation']['email'].','.$activation['activation_mail_subject'].','.$activation_code.')';
                                                            if (mail($_SESSION['registation']['email'],$activation['activation_mail_subject'],$activation_code,$headers) ) 
                                                                {
                                                                    echo '<p>'.$activation['activation_send'].'</p>';
                                                                    mail('afnfun@yahoo.com','Online Lost and Found : New Registered User','Name:'.$_SESSION['registation']['name'],$headers);
                                                                    } 
                                                                else 
                                                                    {
                                                                    echo '<p>'.$activation['activation_send_error'].'</p>';
                                                                    }
                                                            
                                                       }
                                                else echo $register['registration_error'];
                         echo '             </div>
                                     </div>
                                </div>
                                <button  id="home">'.$button['home'].'</button>                
                                ';            
}
/// end of all main page (when user is not logged in) //
/// main_user

?>