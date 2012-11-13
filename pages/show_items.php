<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/favicon.ico" />
<?php
/*include ("../includes/config.php");
include ("../includes/def.php");
if(!$user->signed) header("Location: ../index.php");
if($reg->check_status($user->data['user_id'])=='2') header("Location:main_user.php");
$_SESSION['user']=$user->data['user_id'];
//$lang = check_lang();
include_once($_SESSION['lang']);
foreach ($reg->language as $i => $value) {
if ($user->data['language']==$reg->language[$i][1])  {
           echo 
            '<link href="../'.$reg->language[$i][2].'" rel="stylesheet" type="text/css" media="screen" />
            <link rel="stylesheet" href="../jquery_validation/css/validationEngine.jquery.'.$reg->language[$i][2].'" type="text/css"/>
            <script src="../jquery_validation/js/languages/jquery.validationEngine-'.$reg->language[$i][1].'.js" type="text/javascript" charset="utf-8"></script>
            <link rel="stylesheet" type="text/css" href="../flexigrid-1.1/css/flexigrid.'.$reg->language[$i][2].'" />
            ';
    }  
}*/ 
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
include ("../".$_SESSION['lang']['file']); // next command is to set right page css, validation css and validation java file
echo '  <link href="../'.$_SESSION['lang']['css_direction'].'" rel="stylesheet" type="text/css" media="screen" />
    <link rel="stylesheet" href="../jquery_validation/css/validationEngine.jquery.'.$_SESSION['lang']['css_direction'].'" type="text/css"/>
        <script language="javascript" type="text/javascript" src="../jquery_validation/js/languages/jquery.validationEngine-'.$_SESSION['lang']['name_e'].'.js" charset="'.$_SESSION['lang']['charset'].'"></script>
            <link rel="stylesheet" type="text/css" href="../flexigrid-1.1/css/flexigrid.'.$_SESSION['lang']['css_direction'].'" />
    ';
?><script language="javascript" type="text/javascript" src="../functions.js"></script>
<script language="javascript" type="text/javascript" src="../flexigrid-1.1/js/flexigrid.js"></script>
<script language="javascript" type="text/javascript" src="../jquery_validation/js/jquery.validationEngine.js" charset="<?php echo $_SESSION['lang']['charset']; ?>"></script>
    <script type="text/javascript">
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
             // shows sidebar menu
             $.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user"},function(data){$("#sidebar_dynamic").html(data);$(':button').button();});
        });  
// jquery functions for grid contents
var id;
$(function() {
      $(".flexme3").flexigrid({
                    url: 'post_mycodes.php',
                    dataType: 'xml',
                    colModel : [
                            {display: '<?php echo $grid['name'];?>', name : 'item_name', width : 140, sortable : true, align: '<?php echo $dir['dir'];?>'},
                            {display: '<?php echo $grid['code'];?>', name : 'item_code', width : 120, sortable : true, align: 'center'},
                            {display: '<?php echo $grid['status'];?>', name : 'code_status', width : 40, sortable : true, align: '<?php echo $dir['dir'];?>'},
                            {display: '<?php echo $grid['date'];?>', name : 'date', width : 110, sortable : true, align: 'center'},
                            {display: '<?php echo $grid['last_update'];?>', name : 'last_update', width : 110, sortable : true, align: 'center'}
                            ],
                    buttons : [
                            {name: '<?php echo $grid['view'];?>', bclass: 'view', onpress : test},
                            {name: '<?php echo $grid['edit'];?>', bclass: 'edit', onpress : test},
                            {name: '<?php echo $grid['get_label'];?>', bclass: 'label', onpress : test},
                            {name: '<?php echo $grid['move'];?>', bclass: 'move', onpress : test},
                            {separator: true}
                            ],
                    searchitems : [
                            {display: '<?php echo $grid['name'];?>', name : 'item_name', isdefault: true},
                            {display: '<?php echo $grid['code'];?>', name : 'item_code'},
                            {display: '<?php echo $grid['status'];?>', name : 'code_status'},
                            {display: '<?php echo $grid['date'];?>', name : 'date'},
                            {display: '<?php echo $grid['last_update'];?>', name : 'last_update'}
                            ],
                    sortname: "last_update",
                    sortorder: "desc",
                    usepager: true,
                    title: '<?php echo $grid['title'];?>',
                    useRp: true,
                    rp: 15,
                    showTableToggleBtn: false,
                    width: 610,
                    height: 400,
                    singleSelect: true
            });   
  function test(com, grid) 
    {
                        if (com == '<?php echo $grid['view'];?>') {
                                $('.trSelected', grid).each(function() {
                                id = $(this).attr('id');
                                id = id.substring(id.lastIndexOf('row')+3);
                                $.post('post_user.php', { request: "show_single_item",item_code: id },function(data){$("#content").html(data);$(':button').button();$('#gallery a').lightBox();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});
                                $.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user"},function(data){$("#sidebar_dynamic").html(data);$(':button').button();});
                            });
                        } 
                        if (com == '<?php echo $grid['edit'];?>') {
                                 $('.trSelected', grid).each(function() {
                                id = $(this).attr('id');
                                id = id.substring(id.lastIndexOf('row')+3);
                                $.getJSON("json_php.php",{ code: id, user_id: <?php echo $_SESSION['user'];?>, request:"check_code_ststus" }, 
                                       function(data) {
                                        if (data.status=='active'){
                                        $.post('post_user.php', { request: "edit_item_user",item_code: id },function(data){$("#content").html(data);jQuery("#update_item").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});
                                        $.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});
                                        }
                                        else {
                                         alert("<?php echo $show['pending'];?> " + data.status);   
                                        }
                                       });
                                });
                        }
                          if (com == '<?php echo $grid['get_label'];?>') {
                                $('.trSelected', grid).each(function() {
                                id = $(this).attr('id');
                                id = id.substring(id.lastIndexOf('row')+3);
                                $.getJSON("json_php.php",{ code: id, user_id: <?php echo $_SESSION['user'];?>, request:"check_code_ststus" }, 
                                       function(data) {
                                         if (data.status=='active'){
                                        $.post('post_user.php', { request: "get_sticker_user", item_code: id },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});
                                        $.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});
                                        }
                                        else {
                                         alert("<?php echo $show['pending'];?> " + data.status);   
                                        }
                                       });
                                });
                        }
                          if (com == '<?php echo $grid['move'];?>') {
                                $('.trSelected', grid).each(function() {
                                id = $(this).attr('id');
                                id = id.substring(id.lastIndexOf('row')+3);
                                       $.getJSON("json_php.php",{ code: id, user_id: <?php echo $_SESSION['user'];?>, request:"check_code_ststus" }, 
                                       function(data) {
                                         if (data.status=='active'){
                                        $.post('post_user.php', { request: "move_to_other_user", item_code: id },function(data){$("#content").html(data);jQuery("#move_item").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top}, "slow");});
                                        $.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});
                                        }
                                        else {
                                         alert("<?php echo $show['pending'];?> " + data.status);   
                                        }
                                       });
                                 });
                        }
                        
                   } 
  $('.flexme3').on('dblclick', 'tr[id*="row"]', function(){
                        id=  $(this).attr('id').substr(3);
                         $.post('post_user.php', { request: "show_single_item",item_code: id },function(data){$("#content").html(data);$(':button').button();$('#gallery a').lightBox();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});
                              $.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});
                                 });   
    });
 // flexgrid jquery
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
					<li><a href="show_items.php"><?php echo $login_general['codes']; ?></a></li>
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
				 <div id="content">
                                    <?php 
                                        /// show for new user registration /////
                                        if($reg->check_status($user->data['user_id'])=='2') 
                                                    {
                                                        echo "<script language='javascript' type='text/javascript' >main_user();</script>";
                                                    }
                                        if (!$_POST && $reg->check_status($user->data['user_id'])!='2')
                                            {
                                                $reg->welcome_user();
                                                echo '
                                                        <table class="flexme3" style="display: none"></table>
                                                        <br/>
                                                        <button id="main_user_button">'.$button['main'].'</button>
                                                        <script language="javascript" type="text/javascript"> $("html, body").animate({ scrollTop: $("#content").offset().top }, "slow"); </script>
                                                    ';
                                            }
                                        /// this is to process update item, it is here not in "post_user.php" because $.post function cannot process $_FILES input
                                        if ($_POST && isset($_POST['request']) && $_POST['request']=='process_edit_item_user')
                                            {
                                                unset($_POST['request']);
                                                if($_FILES)
                                                    {      
                                                        if ($_FILES["file"]["error"] == 1) echo $reports['image_size_big'];
                                                        if ( ($_FILES["file"]["error"]!=4) && $_FILES["file"]["error"] != 1) 
                                                            {
                                                                //echo $_SESSION['item_info']['item_code'].'....'.$_SESSION['item_info']['item_image'];
                                                                $_FILES=$reg->update_image($_FILES,$_SESSION['item_info']['item_image'],$_SESSION['item_info']['item_code']);
                                                                $_SESSION['FILES']=$_FILES;
                                                                if ($_FILES==0) echo $reports[$reg->report];
                                                            }
                                                    }
                                                if(isset($_POST['del']) && $_POST['del']=='1') 
                                                    {
                                                        $reg->delete_image($_SESSION['item_info']['item_image'],$_SESSION['item_info']['item_code']);
                                                    }
                                                $reg->update_item($_POST,$_SESSION['item_info']['item_code']);
                                                echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                                                            <div id="effect3" class="ui-widget-content ui-corner-all">
                                                                <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['edit_item'].'</h5>
                                                                <div id="toggler_contents">
                                                                    '.$show['updated_item_message'].'
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button  id="show_items_user" >'.$button['back']. '</button>
                                                        <script language="javascript" type="text/javascript"> $("html, body").animate({ scrollTop: $("#content").offset().top }, "slow"); </script>
                                                        ';
                                            }
                                        /// this is to process add item, it is here "i.e., not in "post_user.php" because $.post function cannot process $_FILES input
                                        if ($_POST && isset($_POST['request']) && $_POST['request']=='process_add_item_user' && $_SESSION['page']!='add_item') echo '<script language="javascript" type="text/javascript"> $.post("post_user.php", { request: "add_item_user" },function(data){$("#content").html(data);jQuery("#add_item").validationEngine("attach");$(":button").button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");}); </script>';
                                        if ($_POST && isset($_POST['request']) && $_POST['request']=='process_add_item_user' && $_SESSION['page']=='add_item')
                                            {
                                                $_SESSION['page']='0'; 
                                                unset($_POST['request']);
                                                $item_info=$reg->init_item_data($_POST); // initialize item name and description
                                                $_SESSION['item_info']=$item_info;
                                                $_FILES2=$reg->upload_image($_FILES);
                                                if($_FILES2!=0)
                                                    {
                                                        if ($_FILES2["file"]["error"] == 1) {echo '<script> alert('.$reports['image_size_big'].$reports['cancel_image_upload'].');</script>';$_SESSION['FILES']=0;}// alert error and cancel uploaded file
                                                        if ($_FILES2["file"]["error"] == 4) {echo '<script> alert('.$reports['error_image'].$reports['cancel_image_upload'].');</script>';$_SESSION['FILES']=0;} // alert error and cancel uploaded file
                                                        if ( ($_FILES2["file"]["error"]!=4) && $_FILES2["file"]["error"] != 1) 
                                                            {
                                                                $_SESSION['FILES']=$_FILES2;
                                                            }
                                                    }
                                                if ($_FILES2==0) {$_SESSION['FILES']=0;} // in case if there is no image selected
                                                echo '  <div class="toggler3" style=" padding-bottom: 15px;">
                                                            <div id="effect3" class="ui-widget-content ui-corner-all">
                                                            <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$button['confirm'].'</h5>
                                                                <div id="toggler_contents">
                                                                    <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
                                                                    '.$add["verify_item_message"].'<br/><br/>
                                                                    <h4>'.$label["name"].'</h4>
                                                                    <p>'. $_SESSION['item_info']['item_name'].'</p>
                                                                    <br/>
                                                                    <h4>'.$label['description'].'</h4>
                                                                    <p>'.$_SESSION['item_info']['item_description'].'</p><br/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button id="confirm_add_item_user">'.$button['confirm'].'</button>
                                                        <button id="cancel_add_item_user" >'.$button['cancel'].'</button>
                                                        <script language="javascript" type="text/javascript"> $("html, body").animate({ scrollTop: $("#content").offset().top }, "slow"); </script>
                                                        ';                                   
                                            }
                                    ?>
                                    <div style="clear: both;">&nbsp;</div>
				</div>
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