<?php
//if(isset($_POST['seid'])) {session_id($_POST['seid']);}
include ("../includes/config.php");
include_once("../".$_SESSION['lang']['file']);
header('Content-Type: text/html; charset='.$_SESSION['lang']['charset']);
if(!$user->signed) header("Location: ../index.php");
//
if($reg->check_status($user->data['user_id'])!='2') 
        {
         if (isset($_POST['request']) && !isset($_POST['sidebar_request'])) $reg->welcome_user();
        /// main_user
        if ($_POST && ($_POST && $_POST['request']=="main_user")){
                                                   echo ' <br/><br/><br/>
                                                       <table align="center">
                                                        <tr>
                                                            <td><a href="show_items.php" > <img src="../images/manage.png"></a></td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                            <td><a href="#" id="edit_data_user_link"><img src="../images/profile.png"></a></td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                            <td><a href="#" id="add_item_user_link"><img src="../images/add_item.png"></a></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center"><a href="show_items.php" >'.$login_general['codes'].'</a></td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                            <td align="center"><a href="#" id="edit_data_user_link">'.$login_general['profile'].'</a></td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                            <td align="center"><a href="#" id="add_item_user_link">'.$show['add_code'].'</a></td>
                                                        </tr>
                                                    </table>';
                                                   }
        // contact us @ user
        if ($_POST && ($_POST && $_POST['request']=="contact_us_user"))
            {
            echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                        <div id="effect3" class="ui-widget-content ui-corner-all">
                        <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['contact_us'].'</h5>
                            <div id="toggler_contents">
                                <form id="contact_us_user_form"  name="contact_us_user_form" method="post" enctype="multipart/form-data">
                                    <table  width="100">
                                        <tr>
                                            <td>
                                                '.$index['contact_paragraph'].'
                                            </td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                            <td>
                                                <label>'.$grid["name"].'</label>
                                                <input name="name" type="text" text-input" size="35" value="'.$user->data['name']."&nbsp;".$user->data['surename'].'" disabled/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>'.$label["email"].'</label>
                                                <input name="email" type="text" size="35" value="'.$user->data['email'].'" disabled/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>'.$label["subject"].'</label>
                                                <input name="subject" id="subject_contact_us_user_form" type="text" class="validate[required,minSize[4],maxSize[64]] text-input" size="35" onkeypress="checkForEnter(event,this.id)" />
                                            </td>
                                         </tr>
                                         <tr>
                                            <td>
                                                <label>'.$label["message"].'</label>
                                                <textarea name="body" type="text" class="validate[required,minSize[8],maxSize[1024]] text-input" cols="50" rows="6" ></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                                <input name="request" type="hidden"  value="process_contact_us_user"/>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <button id="process_contact_us_user_button">'.$button['send'].'</button>
                    <button id="main_user_button">'.$button['cancel'].'</button>
                 ';
                 }
        // process contact us @ user                                                         //
        if ($_POST && ($_POST && $_POST['request']=="process_contact_us_user"))
            {
            $body=$mail_m['contact_subject']."\n\n".$mail_m['sender'].$user->data['name']."\n\n".$mail_m['email'].$user->data['email']."\n\n".$mail_m['subject'].$_POST['subject']."\n\n".$mail_m['message']."\n\n".$_POST['body'];
            $headers = 'From:'.$user->data['name'].'<'.$user->data['email'].'>';
            echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                        <div id="effect3" class="ui-widget-content ui-corner-all">
                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['contact_us'].'</h5>
                                <div id="toggler_contents">';
                                    if (mail("afnfun@yahoo.com",$mail_m['contact_subject'], $body, $headers)) echo $main['successful_contact_message'];
                                    else echo $main['unsuccessful_contact_message'];
            echo '              </div>
                        </div>
                    </div>
                    <button id="main_user_button">'.$button['home'].'</button>
                 ';                                                       
            }
        // sidebar editdata @ user
        if (isset($_POST['sidebar_request']) && $_POST['sidebar_request']=="edit_data_sidebar_user")
            {
        echo '
                <button  id="edit_data_user_button">'.$edit['profile_management'].'</button>
                <button    id="privacy_management_user_button">'.$edit['privacy_setting'].'</button>
                <button    id="password_change_user_button">'.$edit['change_password'].'</button>
                <button    id="account_activation_user_button">'.$edit['block_account'].'</button>
            ';
            }
        //if there is no sidebar contents
        if (isset($_POST['sidebar_request']) && $_POST['sidebar_request']=="clear") 
            {
                echo "";
            }
        // editdata @ user
        if ($_POST && ($_POST && $_POST['request']=="edit_data_user"))
            {
            // select gender radio button 
            if( $user->data['gender']=='male' )  {$Male='checked';$Fem='unchecked';}  
            else  {$Fem='checked';$Male='unchecked';}
            // check if current city is from the list or 'other' city, this is only for first load of setting page
            if (!$reg->check_city_in_country($user->data['country'],$user->data['city']))
                echo "<script> $('#others').css('display', '');</script>"; // display 'other' input 
           else echo "<script>$('#others').css('display', 'none');</script>"; // remove 'other' input
           //
            echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                        <div id="effect3" class="ui-widget-content ui-corner-all">
                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['edit_profile'].'</h5>
                                <div id="toggler_contents">
                                    <form id="edit_data_user_form" class="cmxform" name="edit_data_user_form" method="post" enctype="multipart/form-data"  onkeypress="checkForEnter(event,this.id)" >
                                        <h3>'.$edit['label1'].'</h3>
                                        <hr>
                                        <table width="450">
                                            <tr>
                                                <td>
                                                    <label>'.$label['name'].'</label>
                                                    <input name="name" type="text" class="validate[required,minSize[2],maxSize[35]] text-input" style="width:100%;" value="'.$user->data['name'].'"/>
                                                </td>
                                                <td>
                                                    <label>'.$label['surename'].'</label>
                                                    <input name="surename" type="text" class="validate[required,minSize[2],maxSize[20]] text-input" style="width:80%;" value="'.$user->data['surename'].'"  />
                                                </td>
                                            </tr>
                                            <tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <td>
                                                    <label float:left >'.$label['male'].'
                                                    <input name="gender" type="radio"  value="male"  '.$Male.' /></label>
                                                </td>
                                                <td>
                                                    <label float:left >'.$label['female'].'
                                                    <input type="radio" name="gender"  value="female" '.$Fem.'/></label>
                                                </td>
                                            </tr>
                                        </table>
                                        <br/>
                                        <h3>'.$edit['label2'].'</h3>
                                        <hr>
                                        <table width="450">
                                            <tr> 
                                                <td>
                                                    <label>'.$label['email'].'</label>
                                                    <input name="email" type="text" class="validate[required,custom[email]]" style="width:100%;" value="'.$user->data['email'].'"  />                            
                                                </td>
                                                <td>
                                                    <label>'.$label['phone'].'</label>
                                                    <input name="phone" type="text" class="validate[custom[phone]]" style="width:80%;" value="'.$user->data['phone'].'" />
                                                </td>
                                                      </tr>
                                                <tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <td>
                                                    <label style="display: block;">'.$label['address'].'</label>
                                                    <input name="address" type="text" class="validate[minSize[2],maxSize[64]] text-input" value="'.$user->data['address'].'" style="width:180%;"/>
                                                </td>
                                            </tr> 
                                            <tr><td>&nbsp;</td></tr>
                                        </table>
                                        <table width="300">
                                            <tr>
                                                <td>
                                                    <label>'.$label['country'].'</label>
                                                    <select name="country" class=" text-input" id="country" type="text" style="width:80%;" >
                                                        <option  value="">Select country ....</option>';
                                                        $reg->select_country_list($user->data['country']);
                                 echo '             </select>
                                                </td>  
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div id="cityAjax" style="display: none"></div>';
                                 if ($user->data['country']){
                                    echo'               <div id="current_city" > 
                                                        <label>'.$label['city'].'</label>
                                                        <select name="city" id="city" class=" text-input" type="text"  style="width:80%;">
                                                            <option  value="">Select city ....</option>';
                                                            $_SESSION['other_city']=$label['other_city'];$reg->select_current_city_list($user->data['country'],$user->data['city']);
                                echo '                  </select>
                                                    </div>';
                                 }
                                 echo'          </td>
                                            </tr>
                                            <tr>
                                                <td>';
                                 if ($user->data['country']){
                                 echo'                   <div id="others" style="display: none" >
                                                        <label>'.$label['other_city'].'</label>
                                                        <input type="text" id="other" name="other" class=" text-input" minlength="2" maxlength="32" style="width:80%;" value="'.$user->data['city'].'"/>
                                                    </div>';
                                 }
                                 echo'               </td>
                                            </tr>
                                        </table>
                                        <table width="150">
                                            <tr>
                                                <td>
                                                    <label>'.$label["language"].'</label>
                                                        <select name="language" class="validate[required] text-input" id="language" type="text"  style="width:100%;" >
                                                            <option  value="">'.$register["select_language"].'</option>';
                                                            $reg->select_language_list($user->data['language']);
                                echo '              </select>
                                                </td> 
                                            </tr>
                                            <tr><td>&nbsp;</td></tr>
                                        </table>
                                        <hr>
                                        <table width="150">
                                            <tr><td>&nbsp;</td></tr>
                                            <tr>
                                                <td>
                                                    <label>'.$label['password'].'</label>
                                                    <input name="password" type="password" class="validate[required,minSize[6],maxSize[16]] text-input" style="width:100%;"/>
                                                </td>
                                            </tr>
                                            <tr><td>&nbsp;</td></tr>
                                        </table>
                                        <input name="request" type="hidden"  value="process_edit_data_user"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <button id="process_edit_data_user_button" >'.$button['update'].'</button>
                        <button id="main_user_button">'.$button['cancel'].'</button>
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

        //Proccess Update
        if ($_POST && ($_POST && $_POST['request']=="process_edit_data_user"))
            {
            unset($_POST['request']);
            if ($_POST['language']!=$user->data['language']) $lang=true; else $lang=false; 
            if (isset ($_POST['city2'])) {$_POST['city']=$_POST['city2'];unset($_POST['city2']);} // if city selection comes from external data.php list
            if ($_POST['city']=='Other') {$_POST['city']=$_POST['other'];} // if city selection comes from 'other' input
            if (isset ($_POST['other'])) unset($_POST['other']); 
            $_POST['password']=$user->hash_pass($_POST['password']);
            $check_email=$user->check_field('email',$_POST['email']);
            echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                        <div id="effect3" class="ui-widget-content ui-corner-all">
                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['update_profile'].'</h5>
                                <div id="toggler_contents">';
           if (!$check_email || ( $check_email && $user->data['email']==$_POST['email']))
                {
                        if ($_POST['password']==$user->data['password'])
                             {
                                foreach($_POST as $name=>$val)
                                    {
                                        if($user->data[$name] == $val){unset($_POST[$name]);}		
                                    }
                                if(count($_POST))
                                    {
                                        //Update info
                                        $user->update($_POST);			
                                        //If there is not error
                                        if(!$user->has_error())
                                            {
                                                //A workaround to display a confirmation message in this specific  Example
                                                 echo $uFlex["information_updated"];
                                            }
                                        else 
                                            {
                                                echo $uFlex["information_not_updated"];
                                            }
                                    }
                                else
                                    {
                                    echo $uFlex["No_need_update"];
                                    }
                                
                            }
                        else
                            {
                                echo $edit['wrong_pass'];
                            }
                }
            else
                {
                    echo $edit['exist_email'];
                }
            echo '              </div>
                            </div>
                        </div>
                        <button id="home_user">'.$button['home'].'</button>
                        ';
            $_POST['request']="";
          }         
        // privacy management
        if ($_POST && ($_POST && $_POST['request']=="privacy_management_user"))
            {
                $prflag=$reg->get_privacy_flag($user->data['user_id']);
                echo '   <div class="toggler3" style=" padding-bottom: 15px;">
                            <div id="effect3" class="ui-widget-content ui-corner-all">
                                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['privacy_setting'].'</h5>
                                    <div id="toggler_contents"><p>';
                echo                   $privacy['privacy_paragraph'].'</p><br/><br/><hr>
                                        <div class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;"><table align="center">
                                            <br/>
                                            <table style="text-align: center;">
                                                <tr>
                                                    <td align="center"><img style="border-radius:15px;-moz-border-radius:15px;" src="../images/pr_name_small.png"</td><td>&nbsp;</td>
                                                    <td align="center"><img style="border-radius:15px;-moz-border-radius:15px;" src="../images/pr_email_small.png"></td><td>&nbsp;</td>
                                                    <td align="center"><img style="border-radius:15px;-moz-border-radius:15px;" src="../images/pr_post_small.png"></td><td>&nbsp;</td>
                                                    <td align="center"><img style="border-radius:15px;-moz-border-radius:15px;" src="../images/pr_phone_small.png"></td><td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td><label>'.$grid['name'].'</label><input id="1" type="checkbox"';  if ($prflag['pr_name']==1) echo 'checked'; echo '></td><td>&nbsp;</td>
                                                    <td><label>'.$label['email'].'</label><input id="2" type="checkbox"'; if ($prflag['pr_email']==2) echo 'checked'; echo '></td><td>&nbsp;</td>    
                                                    <td><label>'. $label['address'].'</label><input id="3" type="checkbox"'; if ($prflag['pr_address']==4) echo 'checked'; echo '></td><td>&nbsp;</td>
                                                    <td><label>'. $label['phone'].'</label><input id="4" type="checkbox"'; if ($prflag['pr_phone']==8) echo 'checked'; echo '></td><td>&nbsp;</td>
                                                </tr>
                                            </table>
                                            <br/>
                                        </div>
                                        <hr>
                                    </div>
                                </div>
                            </div>
                            <button id="main_user_button">'.$button['home'].'</button>
                    ';
            }
        // password change 
        if ($_POST && ($_POST && $_POST['request']=="password_change_user"))
            {
                 echo'  <div class="toggler3" style=" padding-bottom: 15px;">
                            <div id="effect3" class="ui-widget-content ui-corner-all">
                                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['change_password'].'</h5>
                                <div id="toggler_contents">
                                    <form id="change_password_user_form" name="change_password_user_form" method="post"  onkeypress="checkForEnter(event,this.id)">
                                        <table width="200">
                                            <tr>
                                                <td>
                                                    <label>'.$label['current_password'].'</label><span class="required">*</span>
                                                    <input name="c_password" type="password" class="validate[required,minSize[6],maxSize[16]] text-input" />
                                                </td>
                                            </tr>
                                            <tr>
                                                 <td>
                                                    <label>'.$label['write_new_password'].'</label><span class="required">*</span>
                                                    <input name="password" type="password" class="validate[required,minSize[6],maxSize[16]] text-input" >
                                                </td>
                                            </tr>      
                                            <tr>
                                                <td>
                                                    <label>'.$label['re_write_new_password'].'</label><span class="required">*</span>
                                                    <input name="password2" type="password" class="validate[required,minSize[6],maxSize[16]] text-input" >
                                                </td>
                                            </tr>        
                                        </table>
                                        <input name="request" type="hidden"  value="process_password_change_user"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <button id="process_password_change_user_button">'.$edit['change_password'].'</button>
                        <button id="main_user_button">'.$button['home'].'</button>
                    ';
            }
        // process password change 
        if ($_POST && $_POST['request']=="process_password_change_user")
            {
                unset($_POST['request']);
                echo'  <div class="toggler3" style=" padding-bottom: 15px;">
                            <div id="effect3" class="ui-widget-content ui-corner-all">
                                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['change_password'].'</h5>
                                <div id="toggler_contents">';
                 if ($_POST['password']==$_POST['password2'])
                    {
                        $_POST['c_password']=$user->hash_pass($_POST['c_password']);
                        //
                            if ($_POST['c_password']==$user->data['password'])
                                    {                    
                                        unset($_POST['c_password']);
                                        $user->update($_POST);			
                                        //If there is not error
                                        if(!$user->has_error())
                                                {
                                                    //A workaround to display a confirmation message in this specific  Example
                                                    echo $edit['password_change_message'];
                                                    echo '</div></div></div>';
                                                }
                                        if( $user->has_error())
                                                {
                                                    foreach($user->error() as $i=>$x)
                                                            {
                                                                echo $uFlex[$x];
                                                            }
                                                }
                                     }
                            else
                                     {
                                        echo $edit['wrong_pass'];
                                        echo '</div></div></div><button id="password_change_user_button">'.$button['back'].'</button>';
                                     } 
                        }
                    else
                        {
                        echo $edit['unsimilar_pass'];
                        echo '</div></div></div><button id="password_change_user_button">'.$button['back'].'</button>';
                        }
                 echo '       
                        <button id="main_user_button">'.$button['home'].'</button>
                      ';
                 $_POST['request']=null;
        }
        /// show item page
        // sidebar editdata @ show item page
        if (isset($_POST['sidebar_request']) && $_POST['sidebar_request']=="show_items_sidebar_user")
            {
            echo '
                    <button style={width:200px;} id="add_item_user">'.$show['add_code'].'</button>
                 ';
            }
        if ($_POST && $_POST['request']=="show_single_item")
            {
                if (isset($_POST['item_code'])) $item=$reg->get_item($_POST['item_code']);
                else $item=$reg->get_item($_SESSION['item_info']['item_code']);
                $_SESSION['item_info']=$item;
                echo'   
                    <div class="toggler3" style=" padding-bottom: 15px;">
                        <div id="effect3" class="ui-widget-content ui-corner-all">
                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$show['item_details'].'</h5>
                            <div id="toggler_contents">
                                <table width="450" >
                                    <colgroup>
                                        <col width="5%"><col width="5%">
                                        <col width="5%"><col width="5%">
                                        <col width="5%"><col width="5%">
                                        <col width="5%"><col width="5%">
                                        <col width="5%"><col width="5%">
                                        <col width="5%"><col width="5%">
                                        <col width="5%"><col width="5%">
                                        <col width="5%"><col width="5%">
                                        <col width="5%"><col width="5%">
                                        <col width="5%"><col width="5%">
                                    </colgroup>
                                    <tr>
                                        <td style="text-align: center;" colspan="20" width=90%>
                                             <div id="gallery">
                                                <a href="'.$item['item_image'].'?'.rand().'">
                                                <img style="border-radius:25px;-moz-border-radius:25px; width:300px; height:auto;  " src='.$item['item_image'].'?'.rand().'>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center;" colspan="20" width=90%">
                                            <div id="gallery">
                                                <a href="'.$reg->get_image_path($item['item_code']).'QR'.$item['item_code'].'.png">
                                                <img width="72" height="72" alt="" src='.$reg->get_image_path($item['item_code']).'QR'.$item['item_code'].'.png /></a>
                                            </div> 
                                            <b><code>'.$label['track_code'].':</code></b>
                                            <b><code>'.$item['item_code'].'<code></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="12">
                                            <h4>'.$grid['name'].'</h4>
                                            <p>'.$item['item_name'].'</p>
                                        <td>
                                    </tr>
                                    <tr>
                                        <td colspan="12">
                                            <h4>'.$label['description'].'</h4>
                                            <p>'.$item['item_description'].'</p><br/>
                                        <td>
                                    </tr>
                                </table>
                                <br/>';
                if ($item['code_status']=='active')
                echo '      </div>
                        </div>
                    </div>
                    <button id="show_items_user" >'.$button['back'].'</button>
                    <button id="move_to_other_user" >'.$button['move_to_other'].'</button>
                    <button id="edit_item_user" >'.$button['edit'].'</button>
                    <button id="get_sticker_user" >'.$grid['get_label'].'</button>
                     ';
                //
                if ($item['code_status']=='pending'){
                    $_SESSION['transfer']=$reg->get_transfer_record($item['item_code']);
                    echo '
                                <form id="cancel_code_move" name="cancel_code_move"  method="post" onsubmit="">
                                    <input name="confirm" type="hidden"  value="22"   />
                                </form>
                                <p> '.$show['pending_message1'].$_SESSION['transfer']['destination_email'].$show['pending_message2'].'<br/><hr><br/>
                            </div>
                        </div>
                    </div>
                    <button id="show_items_user" >'.$button['back'].'</button>
                    <button id="cancel_move_user" >'.$button['cancel_move'].'</button>
                        ';
                    }
            }
// move to other user        
        if ($_POST && $_POST['request']=='move_to_other_user')
            {
                if (isset($_POST['item_code'])) $item=$reg->get_item($_POST['item_code']);
                else $item=$reg->get_item($_SESSION['item_info']['item_code']);
                $_SESSION['item_info']=$item;
                echo '
                        <div class="toggler3" style=" padding-bottom: 15px;">
                            <div id="effect3" class="ui-widget-content ui-corner-all">
                                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$grid['move'].'</h5>
                                <div id="toggler_contents"> 
                                '.$show['send_item_message'].'
                                    <br/><br/>
                                    <table width="450">
                                        <tr>
                                            <h4>'.$grid['name'].'</h4>
                                            <p>'.$_SESSION['item_info']['item_name'].'</p>
                                        </tr>
                                        <tr>
                                            <h4>'.$label['track_code'].'</h4>
                                            <p>'.$_SESSION['item_info']['item_code'].'</p>
                                        </tr>
                                        <tr>
                                            <h4>'.$label['description'].'</h4>
                                            <p>'.$_SESSION['item_info']['item_description'].'</p>
                                        </tr>
                                    </table>
                                    <br/>
                                    <form id="move_item" name="move_item"  method="post" enctype="multipart/form-data" action="">
                                        <label><span>'.$label["email"].'</span></label>
                                        <input name="email" class="validate[required,custom[email]] text-input" type="text" size="35" />
                                        <input name="request" type="hidden"  value="proceed_move_to_other_user"/>
                                   </form>
                                </div>
                            </div>
                        </div>
                        <button id="proceed_move_to_other_user">'.$button['proceed'].'</button>
                        <button  id="show_single_item_user" >'.$button['cancel']. '</button>
                     ';
            }
            if ($_POST && $_POST['request']=='proceed_move_to_other_user')
                {
                    $email=$user->check_field('email',$_POST['email']);
                            if (!$email)
                                {
                                 echo '<div class="toggler3" style=" padding-bottom: 15px;">
                                        <div id="effect3" class="ui-widget-content ui-corner-all">
                                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['error'].'</h5>
                                            <div id="toggler_contents">
                                                '.$reports['invalid_user'].'
                                            </div>
                                        </div>
                                    </div>
                                    ';            
                                 }
                            else 
                                {
                                $move=$reg->move_code_ownership($user->data['user_id'],$_POST['email'],$_SESSION['item_info']['item_code']);  
                                echo '<div class="toggler3" style=" padding-bottom: 15px;">
                                        <div id="effect3" class="ui-widget-content ui-corner-all">
                                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['move_code'].'</h5>
                                            <div id="toggler_contents">
                                                '.$reports[$reg->report].'
                                            </div>
                                        </div>
                                    </div>
                                    ';            
                                 }
                            echo ' <button  id="show_single_item_user" >'.$button['back']. '</button>';
                }
            /// cancel move code to other user    
            if ($_POST && $_POST['request']=='cancel_move_to_other_user')
                {
                     echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$button['confirm'].'</h5>
                                    <div id="toggler_contents">
                                        '.$show['cancel_transfer_message'].$_SESSION['transfer']['destination_email'].$special['question_mark'].'
                                    </div>
                                </div>
                            </div>
                            <button id="proceed_cancel_move_to_other_user">'.$button['proceed'].'</button>
                            <button  id="show_single_item_user" >'.$button['cancel']. '</button>
                          ';
                                                        }
            if ($_POST && $_POST['request']=='proceed_cancel_move_to_other_user')
                {
                    $cancel=$reg->cancel_move_code_ownership($_SESSION['item_info']['item_code']);                                                       
                    echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['move_code'].'</h5>
                                    <div id="toggler_contents">
                                                '.$reports[$reg->report].'
                                    </div>
                                </div>
                            </div>
                            <button  id="show_single_item_user" >'.$button['back']. '</button>
                            ';                                     
                }
            /// edit item
            if ($_POST && $_POST['request']=='edit_item_user')
                {   
                /// script that enables or disables image input based of image remove check box status 
                echo '  <script>
                                $(document).ready(function()
                                    {
                                        $("#delete").change(function() 
                                            {
                                             if ($("#delete").is(":checked")) {update_item.file.disabled=true}
                                             if (!$("#delete").is(":checked")) {update_item.file.disabled=false}  
                                            });
                                    });
                            </script>';
                if (isset($_POST['item_code'])) $item=$reg->get_item($_POST['item_code']);
                else $item=$reg->get_item($_SESSION['item_info']['item_code']);
                $_SESSION['item_info']=$item;
                 echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['edit_item'].'</h5>
                                    <div id="toggler_contents">
                                        <form id="update_item"  name="update_item" method="post" enctype="multipart/form-data"  action="show_items.php">
                                            <table  width="450">
                                                <colgroup>
                                                    <col width="5%"><col width="5%">
                                                    <col width="5%"><col width="5%">
                                                    <col width="5%"><col width="5%">
                                                    <col width="5%"><col width="5%">
                                                    <col width="5%"><col width="5%">
                                                    <col width="5%"><col width="5%">
                                                    <col width="5%"><col width="5%">
                                                    <col width="5%"><col width="5%">
                                                    <col width="5%"><col width="5%">
                                                    <col width="5%"><col width="5%">
                                                </colgroup>
                                                <tr>
                                                    <td style="text-align: center;" colspan="20" >
                                                        <label style="font-size: 14px;">'.$show['modify_item_message'].' : '.$item['item_code'].'</label>
                                                    </td>
                                                <tr>
                                                <tr>
                                                    <td colspan="20">
                                                        <label>'.$grid['name'].'</label> 
                                                        <input name="item_name" type="text" class="validate[required,minSize[2],maxSize[32]] text-input" cols="26"  style="width:60%;" value="'.$item['item_name'].'"/>
                                                    </td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                    <td colspan="20">
                                                        <label>'.$label['description'].'</label> 
                                                        <textarea name="item_description"  class="validate[required,minSize[8],maxSize[512]] text-input" style="width:60%;" rows="6" maxlength="1024">'.$item['item_description'].' </textarea>
                                                    </td>
                                                </tr>  
                                                <tr><td>&nbsp;</td></tr>
                                                <tr>
                                                    <td style="text-align: center;" colspan="20" >
                                                        <img style="border-radius:25px;-moz-border-radius:25px; width:300px; height:auto; " src='.$item['item_image'].'?'.rand().'>
                                                    </td>
                                                </tr>
                                                <tr><td>&nbsp;</td></tr>                  
                                                <tr>
                                                    <td colspan="11">
                                                        <label>'.$label['select_another_image'].'</label>
                                                        <input type="file" name="file" id="file" value=""/>
                                                    </td>
                                                    <td colspan="9">
                                                        <label >'.$label['remove_image'].'</label>
                                                        <input  type="checkbox" id="delete" name="del" onclick="del()" value="1">
                                                    </td>
                                                    </tr>
                                                    <tr><td></td></tr>                  
                                            </table>
                                            <input name="request" type="hidden"  value="process_edit_item_user"   />
                                        </form>    
                                    </div>
                                </div>
                            </div>
                            <button  id="process_edit_item_user">'.$button['save'].'</button>
                            <button  id="show_single_item_user" >'.$button['cancel']. '</button>
                         ';
                }
            /// get sticker
            if ($_POST && $_POST['request']=='get_sticker_user')
                {
                    if (isset($_POST['item_code'])) $item=$reg->get_item($_POST['item_code']);
                    else $item=$reg->get_item($_SESSION['item_info']['item_code']);
                    $_SESSION['item_info']=$item;
                    echo '
                            <div id="language" align="center" style="text-align: center;">
                                <div class="get_sticker">
                                    <form id="get_sticker" name="get_sticker" action="show_items.php"  method="post">
                                        <input name="request" type="hidden"  value="show_pdf_user"   />
                                        <label><h6>'.$label["language"].'&nbsp;'.'</h6></label>
                                        <select name="language" class="required" id="language" type="text"   >
                        ';
                    foreach ($reg->language as $i => $value) 
                            {
                                echo '      <option value="'.$reg->language[$i][1].'"'; if ($user->data['language']==$reg->language[$i][1]) echo 'selected="selected"'; echo '>'.$reg->language[$i][3].'</option>';
                            }
                    echo    '
                                        </select>
                                    </form>
                                    <button id="process_get_sticker_user">'.$grid['get_label'].'</button>        
                                </div>  
                            </div>
                            <button  id="show_single_item_user" >'.$button['cancel']. '</button>
                            ';
                }
            if ($_POST && $_POST['request']=='show_pdf_user')
                {
                    $reg->generate_pdf($_SESSION['item_info']['item_code'],$_POST['language']);
                    echo '  <script> window.open("pdf/'.$_SESSION['item_info']['item_code'].'.pdf");</script>
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$grid['get_label'].'</h5>
                                    <div id="toggler_contents">
                                                '.$get_sticker['show_label'].'
                                    </div>
                                </div>
                            </div>
                            <button id="get_sticker_user" >'.$button['back'].'</button>
                            ';
                } 
           /// add item 
           // side bar of add item     
            if (isset($_POST['sidebar_request']) && $_POST['sidebar_request']=="add_item_sidebar_user")
                {
                    $credit=$reg->gettokens($user->data['user_id']);
                    echo '
                            <p class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px; padding-top:5px; vertical-align:middle;">'.$login_general['your_credit_is'].$credit.'</p>
                            <br/>
                            <button style={width:200px;} id="register_your_own_code_user" >'.$add['register_your_own_code'].'</button>
                            <button style={width:200px;} id="charge_your_credit_user">'.$add['charge_your_credit'].'</button>
                            <button style={width:200px;} id="ask_for_credit_user">'.$add['ask_for_credit'].'</button> 
                            <br/>
                           ';
                }
            // default of add item      
            if ($_POST && $_POST['request']=="add_item_user") // default add item
                {  
                     $_SESSION['page']='add_item';
                     $credit=$reg->gettokens($user->data['user_id']);
                     if ($credit>0) // if you have code credits
                         {
                            if (isset($_POST['sub_request']) && $_POST['sub_request']=="cancel_add_item_user" && $_SESSION['FILES']!=0)  // delete uploaded image file in case of cancelling add item process
                                {                               
                                    $temp_file="../images/".$reg->item_table."/tmp/".$_SESSION['FILES']["file"]["name"];
                                    if (file_exists($temp_file))unlink($temp_file);
                                }
                             echo ' <div class="toggler3" style=" padding-bottom: 15px;">
                                        <div id="effect3" class="ui-widget-content ui-corner-all">
                                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$add['add_code_paragraph'].'</h5>
                                            <div id="toggler_contents"> 
                                                <form id="add_item"  name="add_item" method="post" enctype="multipart/form-data"  action="show_items.php">
                                                    <table  width="450">
                                                        <tr>
                                                            <td>
                                                                <label>'.$grid['name'].'</label>
                                                                <input name="item_name" class="validate[required,minSize[2],maxSize[32]] text-input" type="text" size="35" onkeypress="checkForEnter(event,this.id)" style="width:65%;"/>
                                                            </td>
                                                        </tr>
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td>
                                                                <label>'.$label['description'].'</label>
                                                                <textarea name="item_description" type="text" class="validate[required,minSize[8],maxSize[512]] text-input" style="width:65%;" rows="6" ></textarea>
                                                            </td>
                                                        </tr>
                                                        <tr><td>&nbsp;</td></tr>
                                                        <tr>
                                                            <td>
                                                                <label>'.$label['image'].'</label>
                                                                <input type="file" name="file" id="file" />
                                                            </td>
                                                        </tr>
                                                        <tr><td>&nbsp;</td></tr>
                                                    </table>
                                                    <input name="request" type="hidden"  value="process_add_item_user"   /></label> 
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <button id="process_add_item_user">'.$button['get_code'].'</button>
                                    <button id="show_items_user" >'.$button['back'].'</button>
                                ';
                        }  
                    if ($credit==0) //if you don't have code credits
                        {
                            echo '<div class="toggler3" style=" padding-bottom: 15px;">
                                        <div id="effect3" class="ui-widget-content ui-corner-all">
                                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$add['no_credit'].'</h5>
                                            <div id="toggler_contents">
                                                        '.$add['no_credit_message'].'
                                            </div>
                                        </div>
                                    </div>
                                    <button id="charge_your_credit_user">'.$add['charge_your_credit'].'</button>
                                    <button id="ask_for_credit_user">'.$add['ask_for_credit'].'</button>
                                    <button id="home_user" >'.$button['home'].'</button>
                                ';
                        }
                }   
            // process data insertion //////
            if ($_POST && $_POST['request']=='confirm_add_item_user')
                {
                    $code= $reg->insert_item_data($_SESSION['item_info'],$_SESSION['FILES'],$user->data['user_id'] );
                    $_POST['request']=='0';
                        if ($code) // successfull add of item
                            {
                                $_SESSION['item_info']['item_code']=$code;
                                 echo ' <div class="toggler3" style=" padding-bottom: 15px;">
                                            <div id="effect3" class="ui-widget-content ui-corner-all">
                                                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['add_item'].'</h5>
                                                <div id="toggler_contents">
                                                        '.  $reports[$reg->report].'<br/>
                                                            <br/><p2 float:left>'.$add['your_code'].'&nbsp;'.$_SESSION['item_info']['item_name'].'&nbsp;'.$add['is'].'&nbsp;'.$code.'
                                                </div>
                                            </div>
                                        </div>
                                        <button id="get_sticker_user" >'.$grid['get_label'].'</button>                                      
                                        <button  id="show_single_item_user" >'.$button['show_item']. '</button>
                                        <button id="show_items_user" >'.$button['items_table'].'</button>
                                       ';
                            }
                        else 
                            {
                                 echo ' <div class="toggler3" style=" padding-bottom: 15px;">
                                            <div id="effect3" class="ui-widget-content ui-corner-all">
                                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['add_item'].'</h5>
                                                <div id="toggler_contents">
                                                    '.$reports[$reg->report].'
                                                </div>
                                            </div>
                                        </div>
                                        <button id="show_items_user" >'.$button['items_table'].'</button>
                                       ';           
                             }
                }
             /// register code
             if ($_POST && $_POST['request']=='register_your_own_code_user')
                {
                    echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['register_code'].'</h5>
                                    <div id="toggler_contents">
                                                    Comming Soon
                                    </div>
                                </div>
                            </div>
                            <button id="add_item_user" >'.$button['back'].'</button>
                                       ';  
                }
            ////////// add credit //////////////
            if ($_POST && $_POST['request']=='charge_your_credit_user')
                {
                     if (!isset($_SESSION['block_add_credit'])) $_SESSION['block_add_credit']=0;
                     if ($_SESSION['block_add_credit']>=5)
                        {
                        $block_add_credit=$reg->block_add_credit($user->data['user_id']);
                        if ($block_add_credit==1) $_SESSION['block_add_credit']=0;
                        }
                     $block=$reg->check_block_add_credit($user->data['user_id']);
                     if ($block['Block_Add_Credit']=='1'){
                         echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label["charge_credit"].'</h5>
                                    <div id="toggler_contents">
                                                  <p>  '.$reports['user_blocked_add_credit1'].(0-$block['hour']).':'.(29-$block['minute']).$reports['user_blocked_add_credit2'].'</p>
                                    </div>
                                </div>
                            </div>
                            <button id="add_item_user" >'.$button['back'].'</button>
                        ';     
                    }
                    if ($_SESSION['block_add_credit']<5 && $block['Block_Add_Credit']==0)
                        {
                            $_SESSION['page']='process_charge_your_credit';
                            echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                                        <div id="effect3" class="ui-widget-content ui-corner-all">
                                        <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label["charge_credit"].'</h5>
                                            <div id="toggler_contents">
                                                <form id="charge_credit"  name="charge_credit" method="post" enctype="multipart/form-data"  onkeypress="checkForEnter(event,this.id)">
                                                    <table  width="150">
                                                        <tr>
                                                            <td>
                                                                <label> '.$label['add_credit_code'].'</label>
                                                                <input name="token_code" type="text" class="validate[required,minSize[12],maxSize[12]] text-input" size="17"/>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <input name="request" type="hidden"  value="process_charge_your_credit"   /></label></td>   
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <button id="process_charge_your_credit">'.$button['add_credit'].'</button>
                                    <button id="add_item_user">'.$button['back'].'</button>
                                  ';
                        }
                    
                    
                        
                    
                 }
            if ($_POST && $_POST['request']=='process_charge_your_credit' && $_SESSION['page']=='process_charge_your_credit')
                {
                    $code= $reg->insert_token_code($_POST['token_code'],$user->data['user_id'] );
                    if ($code==2) $_SESSION['block_add_credit']++;
                    echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label["charge_credit"].'</h5>
                                    <div id="toggler_contents">
                                                    <p>'.$reports[$reg->report].'</p>
                                    </div>
                                </div>
                            </div>
                            <button id="charge_your_credit_user" >'.$button['back'].'</button>
                        ';  
                }
            //////////////////////// Ask for Credit
            if ($_POST && $_POST['request']=="ask_for_credit_user")
                {
                    $_SESSION['page']="ask_for_credit_user";
                    echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                    <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['ask_credit'].'</h5>
                                        <div id="toggler_contents">
                                            <h6>'.$add['ask_for_credit_message'].'</h6>
                                            <form id="ask_credit"  name="ask_credit" method="post" enctype="multipart/form-data"  action="">
                                                <br/>
                                                <table  width="100">
                                                    <tr>
                                                        <td>
                                                            <label>'.$grid['name'].'</label>
                                                            <input name="name" type="text" size="35" value="'.$user->data['name']."&nbsp;".$user->data['surename'].'" disabled/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <label>'.$label['subject'].'</label>
                                                            <input name="subject" type="text" size="35" value="'.$add['credit_request'].'" disabled/>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table width="150">
                                                    <tr>
                                                        <td>
                                                            <label>'.$label['num_codes'].'</label> 
                                                            <input name="credit" id="credit_ask_credit" type="integer" class="validate[required,custom[integer],min[1],max[999]] text-input" size="5" onkeypress="checkForEnter(event,this.id)"/>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <table width="100">
                                                    <tr>
                                                        <td>
                                                            <label>'.$label['comments'].'</label> 
                                                            <textarea name="body" type="text" class="validate[required,minSize[8],maxSize[1024]] cols="26" rows="6" ></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input name="request" type="hidden"  value="process_ask_for_credit_user"   /></label>
                                                        </td>   
                                                    </tr>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <button id="process_ask_for_credit_user">'.$button["send_request"].'</button>
                                <button id="add_item_user">'.$button['back'].'</button>
                           ';
                }
                //
            if ($_POST && $_POST['request']=='process_ask_for_credit_user' &&  $_SESSION['page']=='ask_for_credit_user')
                {
                    $_SESSION['page']=0;
                    $body= $add['credit_request']."\n\n ".$mail_m['sender'].$user->data['name']."\n\n ".$mail_m['email'].$user->data['email']."\n\n ".$add['credit_message_body4'].$_POST['credit']."\n\n ".$add['credit_message_body5'].$_POST['body']."\n\n Online Lost and Found System";
                    $headers = 'From:'.$user->data['name'].'<'.$user->data['email'].'>';
                    echo '      <div class="toggler3" style=" padding-bottom: 15px;">
                                    <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['ask_credit'].'</h5>
                                        <div id="toggler_contents">';
                    if (mail("afnfun@yahoo.com",$add['credit_request'], $body,$headers)) 
                        {
                            echo $add['successful_sending_message'];
                        } 
                    else 
                        {
                            echo $add['error_sending_message'];
                        }
                    echo '              </div>
                                    </div>
                                </div>
                                <button id="add_item_user">'.$button['back'].'</button>
                          ';
                        }    
            // received items page
            // show received side bar
            if (isset($_POST['sidebar_request']) && $_POST['sidebar_request']=="show_received_sidebar_user")
                {
                    $credit=$reg->gettokens($user->data['user_id']);
                    echo '
                            <p class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px; padding-top:5px; vertical-align:middle;">'.$login_general['your_credit_is'].$credit.'</p>
                            <br/>
                           ';
                }
            //      show item information  /////////////////////
            if ($_POST && $_POST['request']=='show_single_received_item_user')
                {
                    if (isset($_POST['received_code'])) $item=$reg->get_item($_POST['received_code']);
                    else $item=$reg->get_item($_SESSION['item_info']['item_code']);
                    $_SESSION['item_info']=$item; //$_SESSION['item_info']['item_code']
                    $received_code=$reg->get_transfer_record($_SESSION['item_info']['item_code']);
                    $sender=$reg->get_user_by_id($received_code['source_user_id']);
                    echo '      <div class="toggler3" style=" padding-bottom: 15px;">
                                    <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['received_code'].'</h5>
                                        <div id="toggler_contents">
                                            <table width="450">
                                                <tr>
                                                    <h4>'.$grid['sender'].'</h4>
                                                    <h5>'.$sender['name'].'&nbsp;'.$sender['surename'].'</h5><br/>
                                                </tr>
                                                <tr>
                                                    <h4>'.$grid['send_date'].'</h4>
                                                    <h5>'.$received_code["code_transfer_time"].'</h5><br/>
                                                </tr>
                                                <tr>
                                                    <h4>'.$grid["name"].'</h4>
                                                    <h5>'.$item['item_name'].'</h5><br/>
                                                </tr>
                                                <tr>
                                                    <h4>'.$label["track_code"].'</h4>
                                                    <h5>'.$item["item_code"].'</h5><br/>
                                                </tr>
                                                <tr>
                                                    <h4>'.$label["description"].'</h4>
                                                    <h5>'.$item["item_description"].'</h5><br/>
                                                </tr>
                                                <tr>
                                                    <h4>'.$label["status"].'</h4>
                                                    <h5>'.$item["code_status"].'</h5><br/>
                                                </tr>
                                            </table>
                                            <br/><br/><hr><br/><br/>
                                        </div>
                                    </div>
                                </div>
                                <button id="accept_single_received_code_user">'.$button["accept"].'</button>
                                <button id="reject_single_received_code_user">'.$button["reject"].'</button>
                                <button id="show_received_codes_user">'.$button['back'].'</button>
                          ';
                }
            // Accept single received code    
            if ($_POST && $_POST['request']=='accept_single_received_code_user')
                {
                    if (isset($_POST['received_code'])) $item=$reg->get_item($_POST['received_code']);
                    else $item=$reg->get_item($_SESSION['item_info']['item_code']);
                    $_SESSION['item_info']=$item;
                    $credit=$reg->gettokens($user->data['user_id']);
                    echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$button['confirm'].'</h5>
                                    <div id="toggler_contents">';
                    if ($credit > 0)
                        echo 
                                        $show_r['confirm_acceptance'].'<br/>'. $_SESSION['item_info']['item_code'].' '.$special['question_mark'].'
                                    </div>
                                </div>
                            </div>
                            <button id="proceed_accept_single_received_code_user">'.$button['proceed'].'</button>
                            <button id="show_single_received_item_user">'.$button['back'].'</button>
                          ';
                    else 
                        echo 
                                      $add['no_credit_message'].'
                                    </div>
                                </div>
                            </div>
                            <button id="charge_your_credit_user">'.$button["add_credit"].'</button>
                            <button id="show_single_received_item_user">'.$button['back'].'</button>
                          ';
                }
            if ($_POST && $_POST['request']=='proceed_accept_single_received_code_user')
                {
                    $move=$reg->confirm_code_receive($_SESSION['item_info']['item_code'],$user->data['user_id']);
                    echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$button['confirm'].'</h5>
                                    <div id="toggler_contents">
                                        '.$reports[$reg->report].'
                                    </div>
                                </div>
                            </div>
                            <button id="show_received_codes_user">'.$button['back'].'</button>
                          ';  
                }
            // Reject single received code    
            if ($_POST && $_POST['request']=='reject_single_received_code_user')
                {
                    if (isset($_POST['received_code'])) $item=$reg->get_item($_POST['received_code']);
                    else $item=$reg->get_item($_SESSION['item_info']['item_code']);
                    $_SESSION['item_info']=$item;
                    echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$button['confirm'].'</h5>
                                    <div id="toggler_contents">
                                        '.$show_r['reject_item'].'<br/>'. $_SESSION['item_info']['item_code'].' '.$special['question_mark'].'
                                    </div>
                                </div>
                            </div>
                            <button id="proceed_reject_single_received_code_user">'.$button['proceed'].'</button>
                            <button id="show_single_received_item_user">'.$button['back'].'</button>
                          ';
                }
            if ($_POST && $_POST['request']=='proceed_reject_single_received_code_user')
                {
                $move=$reg->cancel_move_code_ownership($_SESSION['item_info']['item_code']);
                echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$button['confirm'].'</h5>
                                    <div id="toggler_contents">
                                        '.$reports[$reg->report].'
                                    </div>
                                </div>
                            </div>
                            <button id="show_received_codes_user">'.$button['back'].'</button>
                          '; 
                }
            // Accept all received codes
            if ($_POST && $_POST['request']=='accept_all_received_codes_user')
                {
                    $check_credit=$reg->check_received_codes_vs_tokens($user->data['user_id']);
                    echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$button['confirm'].'</h5>
                                    <div id="toggler_contents">';
                    // if there is enough credit
                    if ($check_credit!=2) echo '
                                        '.$show_r['accept_all'].' '.$special['question_mark'].'
                                    </div>
                                </div>
                            </div>
                            <button id="proceed_accept_all_received_codes_user">'.$button['proceed'].'</button>
                            <button id="show_received_codes_user">'.$button['back'].'</button>
                          ';
                    // if there is no enough credit
                    if ($check_credit==2) echo '
                                        '.$reports[$reg->report].'
                                    </div>
                                </div>
                            </div>
                            <button id="charge_your_credit_user">'.$button["add_credit"].'</button>
                            <button id="show_received_codes_user">'.$button['back'].'</button>
                          '; 
                 }
            if ($_POST && $_POST['request']=='proceed_accept_all_received_codes_user')
                {
                $move=$reg->confirm_code_receive_all($user->data['user_id']);
                echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['received_code'].'</h5>
                                    <div id="toggler_contents">
                                        '.$reports[$reg->report].'
                                    </div>
                                </div>
                            </div>
                            <button id="main_user_button">'.$button['home'].'</button>
                            ';   
                 }
            // Reject all received codes
            if ($_POST && $_POST['request']=='reject_all_received_codes_user')
                {
                    echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$button['confirm'].'</h5>
                                    <div id="toggler_contents">
                                        '.$show_r['reject_all'].' '.$special['question_mark'].'
                                    </div>
                                </div>
                            </div>
                            <button id="proceed_reject_all_received_codes_user">'.$button['proceed'].'</button>
                            <button id="show_received_codes_user">'.$button['back'].'</button>
                          ';
                }
            if ($_POST && $_POST['request']=='proceed_reject_all_received_codes_user')
                {
                    $move=$reg->cancel_move_code_ownership_all($user->data['user_id']);
                    echo '
                            <div class="toggler3" style=" padding-bottom: 15px;">
                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['received_code'].'</h5>
                                    <div id="toggler_contents">
                                        '.$reports[$reg->report].'
                                    </div>
                                </div>
                            </div>
                            <button id="main_user_button">'.$button['home'].'</button>
                          ';                                    
                  }
} /// end of active user








/// if user is blocked or there is an account block request
/// if account is blocked or there is request to change account status
if(($reg->check_status($user->data['user_id'])=='2' || (isset($_POST['request']) && $_POST['request']=="account_activation_user" )) && !isset($_POST['sidebar_request']) && $_POST['request']!="confirm_account_activation_user" && $_POST['request']!='process_activation_user') //default if user is blocked ) 
    {
    $status=$reg->check_status($user->data['user_id']);
    if ($status=='2') echo '<script>jQuery("#account_activation_user_form").validationEngine("attach");</script>';
    echo'              <div class="toggler3" style=" padding-bottom: 15px;">
                            <div id="effect3" class="ui-widget-content ui-corner-all">
         ';
    if ($status==1)
        {
        echo '              <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$activation['activated_message'].'</h5>
             ';
        }
    if ($status==2)
        {
        echo '              <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$activation['blocked_message'].'</h5>
             ';
        }
    echo '                      <div id="toggler_contents">
                                    <form id="account_activation_user_form" name="account_activation_user_form" class="cmxform" method="post" enctype="multipart/form-data" onkeypress="checkForEnter(event,this.id)">
                                        <input name="request" type="hidden"  value="confirm_account_activation_user"   /></label> 
                                        <table width="60">  
                                            <tr>
                                                <td>
                                                    <label>'.$label['password'].'</label>
                                                    <input name="password" type="password" class="validate[required,minSize[6],maxSize[16]] text-input" />
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                ';
    if($status==1)
        {
        echo'           <button id="confirm_account_activation_user_button">'.$button['block'].'</button>
                        <button id="main_user_button" >'.$button['cancel'].'</button>
            ';
        }
    if($status==2)
        {
        echo'           <button id="confirm_account_activation_user_button">'.$button['activate'].'</button>
            ';
        }
   }    
  //confirm activation or blocking
if ($_POST && $_POST['request']=="confirm_account_activation_user")
    {
    $_POST['password']=$user->hash_pass($_POST['password']);
    echo'               <div class="toggler3" style=" padding-bottom: 15px;">
                            <div id="effect3" class="ui-widget-content ui-corner-all">
        ';
    if ($_POST['password']==$user->data['password'])
            {
            $status=$reg->check_status($user->data['user_id']);
            if ($status==1)
                {
                echo '      <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['block_account'].'</h5>
                                <div id="toggler_contents">
                                    <p>'.$activation['block_question'].'</p>
                                </div> 
                     ';
                }
            if ($status==2)
                {
                echo '      <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['block_account'].'</h5>
                                <div id="toggler_contents">
                                        <p>'.$activation['activate_question'].'</p>
                                </div>    
                     ';                                                    
                 }
             echo '         </div>
                        </div>
                        <button id="process_account_activation_user_button">'.$button['continue'].'</button>
                        <button id="main_user_button" >'.$button['cancel'].'</button>    
                  ';
            }
        else
            {
             echo '      <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['block_account'].'</h5>
                                <div id="toggler_contents">
                                        <p>'.$edit['wrong_pass'].'</p>
                                </div>    
                            </div>
                        </div>
                        <button id="account_activation_user_button">'.$button['back'].'</button>
            ';
            }
    }
if ($_POST && $_POST['request']=='process_activation_user')
    {
    unset ($_POST['request']);
    $x=$reg->change_status($user->data['user_id']);
    echo'               <div class="toggler3" style=" padding-bottom: 15px;">
                            <div id="effect3" class="ui-widget-content ui-corner-all">      <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$edit['block_account'].'</h5>
                                <div id="toggler_contents">
                     ';
    if ($x==0) echo $reports[$reg->report];
    else 
        {
        $status=$reg->check_status($user->data['user_id']);
        if ($status==1)
            {
            echo '
                                    <p>'.$activation['successful_activation'].'</p>
                 ';                
            }
   if ($status==2)
       {
        echo '
                                    <p>'.$activation['successful_block'].'</p>
             ';
        }
   echo '                       </div>
                            </div>
                        </div>
                        <button id="main_user_button">'.$button['main'].'</button>
                  ';             
        }                                                                
    }

?>