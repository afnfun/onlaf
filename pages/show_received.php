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
?><script language="javascript" type="text/javascript" src="../flexigrid-1.1/js/flexigrid.js"></script>
<script language="javascript" type="text/javascript" src="../functions.js"></script>
<script language="javascript" type="text/javascript" src="../jquery_validation/js/jquery.validationEngine.js" charset="<?php echo $_SESSION['lang']['charset']; ?>"></script>
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
            $.post('post_user.php', { request: "null",sidebar_request:"show_received_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});
        });  
$(function() 
    {
        $(".flexme3").flexigrid({
                    url: 'post_receivedcodes.php',
                    dataType: 'xml',
                    colModel : [
                            {display: '<?php echo $grid['name'];?>', name : 'item_name', width : 140, sortable : false, align: '<?php echo $dir['dir'];?>'},
                            {display: '<?php echo $grid['code'];?>', name : 'code', width : 120, sortable : true, align: 'center'},
                            {display: '<?php echo $grid['sender'];?>', name : 'sender', width : 160, sortable : true, align: '<?php echo $dir['dir'];?>'},
                            {display: '<?php echo htmlspecialchars($grid['send_date'],ENT_QUOTES); ?>', name : 'code_transfer_time', width : 110, sortable : true, align: 'center'}
                            ],
                    buttons : [
                            {name: '<?php echo $button["more_details"];?>', bclass: 'more', onpress : test},
                            {name: '<?php echo $button["accept"];?>', bclass: 'accept_s', onpress : test},
                            {name: '<?php echo $button["reject"];?>', bclass: 'reject_s', onpress : test},
                            {name: '<?php echo $button["accept_all"];?>', bclass: 'accept_a', onpress : test},
                            {name: '<?php echo $button["reject_all"];?>', bclass: 'reject_a', onpress : test},
                            {separator: true}
                            ],
                    searchitems : [
                            {display: '<?php echo $grid['name'];?>', name : 'item_name', isdefault: true},
                            {display: '<?php echo $grid['code'];?>', name : 'code'},
                            {display: '<?php echo $grid['sender'];?>', name : 'sender'},
                            {display: '<?php echo htmlspecialchars($grid['send_date'],ENT_QUOTES); ?>', name : 'code_transfer_time'}
                            ],
                    sortname: "sender",
                    sortorder: "desc",
                    usepager: true,
                    title: '<?php echo $grid_r['title'];?>',
                    useRp: true,
                    rp: 15,
                    showTableToggleBtn: false,
                    width: 610,
                    height: 400,
                    singleSelect: true
            });   
        function test(com, grid) 
            {
                        if (com == '<?php echo $button["more_details"];?>') {
                                $('.trSelected', grid).each(function() {
                                var id = $(this).attr('id');
                                id = id.substring(id.lastIndexOf('row')+3);
                                $.post('post_user.php', { request: "show_single_received_item_user",received_code: id },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});
                                });
                        } 
                        if (com == '<?php echo $button["accept"];?>') {
                                $('.trSelected', grid).each(function() {
                                var id = $(this).attr('id');
                                id = id.substring(id.lastIndexOf('row')+3);
                                $.post('post_user.php', { request: "accept_single_received_code_user",received_code: id },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});
                                });
                        }
                        if (com == '<?php echo $button["reject"];?>') {
                                 $('.trSelected', grid).each(function() {
                                 var id = $(this).attr('id');
                                 id = id.substring(id.lastIndexOf('row')+3);
                                 $.post('post_user.php', { request: "reject_single_received_code_user",received_code: id },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});
                                 });
                        }
                        if (com == '<?php echo $button["accept_all"];?>') {
                                $.post('post_user.php', { request: "accept_all_received_codes_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});
                                } 
                        if (com == '<?php echo $button["reject_all"];?>') {
                                $.post('post_user.php', { request: "reject_all_received_codes_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});
                                }
            } 
        $('.flexme3').on('dblclick', 'tr[id*="row"]', function()
            {
                var id=  $(this).attr('id').substr(3);
                $.post('post_user.php', { request: "show_single_received_item_user",received_code: id },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});
            });   
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
                                        $reg->welcome_user();
                                        /// show for new user registration /////
                                            if($reg->check_status($user->data['user_id'])=='2') 
                                                    {
                                                        echo "<script language='javascript' type='text/javascript' >main_user();</script>";
                                                    }
                                            if (!$_POST && $reg->check_status($user->data['user_id'])!='2')
                                                {
                                                    if ($reg->get_number_of_received_codes($user->data['user_id']))
                                                        {    
                                                            echo '<table class="flexme3" style="display: none"></table>';
                                                        } 
                                                        else 
                                                        echo '<div class="toggler3" style=" padding-bottom: 15px;">
                                                                <div id="effect3" class="ui-widget-content ui-corner-all">
                                                                    <h5 class="ui-widget-header ui-corner-all" style="padding-right: 5px;padding-left: 5px;">'.$label['received_code'].'</h5>
                                                                    <div id="toggler_contents">
                                                                        <p>'.$show_r['no_items'].'</p>
                                                                    </div>
                                                                </div>
                                                            </div>';
                                                             
                                                    echo '  <br/>
                                                            <button id="main_user_button">'.$button["home"].'</button>
                                                            <script language="javascript" type="text/javascript"> $("html, body").animate({ scrollTop: $("#content").offset().top }, "slow"); </script>';
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