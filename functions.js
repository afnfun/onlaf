src="captcha/gen_validatorv31.js"
function refreshCaptcha()
{
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
// related to this for main page without user logged in
function hide_prompts()
    { // hide any validation prompts
        $(".formError").remove();
    }
function main()
    { // shows main contents
        $.post('pages/post.php', { request: "main" },function(data){hide_prompts();$("#content").html(data);jQuery("#track_code_main").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});
        $.post('pages/post.php', { request: null, sidebar:"login" },function(data){$("#sidebar_dynamic").html(data);jQuery("#login_form").validationEngine('attach');$(':button').button();});
    }
function show_cities(){
        jQuery("select[name='country']").change(function(){
                             // path of ajax-loader gif image
                            var ajaxLoader = "<img src='../ajax-loader.gif' alt='loading...' />";
                             // get the selected option value of country
                            var optionValue = jQuery("select[name='country']").val();      
                             jQuery("#cityAjax")
                                .load("pages/post.php", "country="+optionValue+"&status=1"+"&request=show_city", function(response){
                                //$.post('pages/post.php', { request: "show_cities", country: optionValue, status:1 },function(response){
                                    if(response) {
                                        jQuery("#cityAjax").css("display", "");
                                    } else {
                                        jQuery("#cityAjax").css("display", "none");
                                    }
                            });
                        });                                      
}
function show_other()
    {
        $('#city2').change(function() 
            {
                var selected = $(this).val();
                if(selected == 'Other')
                {
                    $('#others').show();
                }
                else
                {
                    $('#others').hide();
                }
            });
        $('#city').change(function() 
            {
                var selected = $(this).val();
                if(selected == 'Other')
                {
                    $('#others').show();
                }
                else
                {
                    $('#others').hide();
                }
            });
    }
 function checkpass() 
    {
        if (document.register_form.password.value==document.register_form.password2.value) return true;
        alert("You have entered unsimilar passwords");
        return false;
    }
///// end of this for main page without user logged in
// main user page
function main_user()
    { // shows main contents
        $.post('post_user.php', { request: "main_user" },function(data){hide_prompts();$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});
    }
function clear_sidebar()
    {
        $.post('post_user.php', { request: "null",sidebar_request:"clear" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});
    }
function privacy_checkbox_change(){
    $('#1').change(function() {
         if ($('#1').is(':checked')) {$('#1').load('set_pr.php?st=1ok');}
         if (!$('#1').is(':checked')) {$('#1').load('set_pr.php?st=1notok');}  
        });
    $('#2').change(function() {
         if ($('#2').is(':checked')) {$('#2').load('set_pr.php?st=2ok');}
         if (!$('#2').is(':checked')) {$('#2').load('set_pr.php?st=2notok');}  
        });
    $('#3').change(function() {
         if ($('#3').is(':checked')) {$('#3').load('set_pr.php?st=3ok');}
         if (!$('#3').is(':checked')) {$('#3').load('set_pr.php?st=3notok');}  
        });
    $('#4').change(function() {
         if ($('#4').is(':checked')) {$('#4').load('set_pr.php?st=4ok');}
         if (!$('#4').is(':checked')) {$('#4').load('set_pr.php?st=4notok');}  
        });
}
// Events Function
function viewPlayer(obj, gridObj) {
  document.getElementById("confirm").value = '0';
  document.getElementById("item_code").value = obj.item_code;
        document.getElementById("select_item").submit();
}
function dblClickOnData(obj, gridObj){
         document.getElementById("confirm").value = '0';
         document.getElementById("item_code").value = obj.item_code;
         document.getElementById("select_item").submit();
        }
/**
 * THE GRID OBJECT
 */
function grid() {
    var grid = new DG.Grid({
    title : 'My Items',    // Title bar
    height : 500,           // Height
    els : {
        parent : 'gridContainer'    // Where to render the grid
    },
    behaviour : {
        resizable : true, // Grid is resizable
        closable : false // Grid is not closable
    },
    listeners : {
        'view' : viewPlayer,
        'load' : function(obj, gridObj){
            gridObj.setStatusText('Number of Items ' + gridObj.getCountRows());
        },
        'add' : function(obj, gridObj){
            gridObj.setStatusText('Number of players ' + gridObj.getCountRows());
        },
        'dblclick' : dblClickOnData
    },
    stretch : true, // If total width of columns is less than width of view, last column will use all remaining space
    defaultSort : { /* Initial sorting */
        key : 'score',
        direction : 'asc'
    },
    statusBar : {
        visible : true
    },
    /* Column configuration */
    columnConfig : [
        {
            resizable : false,
            width : 40,
            sortable : false,
            txt : 'View',
            event : 'view',
            movable : false
        },
         {
            width : 240,
            key : 'item_name',
            heading : 'Item Name',
            sortable : true,
            sortWith : 'item_name'
        },
        {
            width : 130,
            key : 'item_code',
            sortable : true,
            heading : 'Item Code',
            sortWith : 'item_code'
        },
        {
            width : 60,
            key : 'code_status',
            sortable : true,
            removable : true,
            heading : 'Status'
        },
        {
            width : 40,
            key : 'empty',
            heading : '',
            sortable : true,
            sortWith : 'item_name'
        }       
    ],
    remote : {
        url : '../dhtmlgoodies-grid/datasource/grid-data.php'
    }
});
/** END GRID CONFIG */
}
function del(){
    if ($('#delete').is(':checked')) {update_item.file.disabled=true}
    if (!$('#delete').is(':checked')) {update_item.file.disabled=false} 
}
// to track a code submitted by QR request
function track_code($code) 
    {
        $.post('pages/post.php', { request: "track", item_code: $code }, function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});
    }
/// enter key press processing on input    
function checkForEnter(event,id) 
    {
        if (event.keyCode == 13) 
            {
                if (id=='track_code_main') {event.preventDefault();$("#track_code_button").click();}
                if (id=='captcha_form_more_details') {event.preventDefault();$("#more_details").click();}
                if (id=='login_form') $("#login_button").click();
                if (id=='captcha_form_contact_us' || id=='contact_us_form') {event.preventDefault();$("#contact_us_captcha").click();}
                if (id=='postmail') {event.preventDefault(); $("#send_owner_message").click();}
                if (id=='send_change_password_email') {event.preventDefault(); $("#send_password_verification").click();}
                if (id=='edit_data_user_form') {event.preventDefault(); $("#process_edit_data_user_button").click();}
                if (id=='change_password_user_form') {event.preventDefault(); $("#process_password_change_user_button").click();}
                if (id=='account_activation_user_form') {event.preventDefault(); $("#confirm_account_activation_user_button").click();}
                if (id=='add_item') {event.preventDefault(); $("#process_add_item_user").click();}
                if (id=='charge_credit') {event.preventDefault(); $("#process_charge_your_credit").click();}
                if (id=='credit_ask_credit') {event.preventDefault(); $("#process_ask_for_credit_user").click();}
                if (id=='subject_contact_us_user_form') {event.preventDefault(); $("#process_contact_us_user_button").click();}
                if (id=='home_user') {event.preventDefault(); $("#home_user").click();}
                if (id=='register_form') {event.preventDefault(); $("#register_button").click();}
                
            }//contact_us_user_form 
    }
var serialized;
$(function() { 
        $('#gallery a').lightBox();
        $( "#main_index" ).button()                                 .live("click",function() {window.location = "../index.php";});
        $(":button") .live("click",function() {hide_prompts();});
	// this for main page without user logged in
        $( "#home" ).button()                                       .live("click",function() {main();});
        $( "#home_user" ).button()                                       .live("click",function() {main_user();});
        $( "#track_code_button").button()                           .live("click",function() { if(jQuery("#track_code_main").validationEngine('validate')){ $.post('pages/post.php', $("#track_code_main").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top  }, "slow");});}});
        $( "#contact_owner" ).button()                              .live("click",function() {$.post('pages/post.php', { request: "contact_owner" }, function(data){$("#content").html(data);jQuery("#postmail").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top  }, "slow");});});
        $( "#more_details" ).button()                               .live("click",function() {serialized = $('#captcha_form_more_details').serialize()+"&request="+"more_details"; $.post('pages/post.php', serialized , function(data){$("#more_details_div").html(data);$("html, body").animate({ scrollTop: $("#more_details_div").offset().top  }, "slow");$(':button').button();});});
        $( "#send_owner_message" ).button()                         .live("click",function() {if(jQuery("#postmail").validationEngine('validate')) $.post('pages/post.php', $("#postmail").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#forgot_password").button({})                           .live("click",function() {$.post('pages/post.php', { request: "change_password" },function(data){$("#content").html(data);jQuery("#change_password").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});});
        $( "#send_password_verification").button()                  .live("click",function() {if(jQuery("#change_password").validationEngine('validate')) $.post('pages/post.php', $("#change_password").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});});
        $( "#login_button" ).button({})                             .live("click",function() {if(jQuery("#login_form").validationEngine('validate')) $.post('pages/post.php', $("#login_form").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});});
        $( "#contact_us_link")                                      .live("click",function() {hide_prompts();$.post('pages/post.php', { request: "contact_us" },function(data){$("#content").html(data);jQuery("#contact_us_form").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#contact_us_captcha" ).button()                         .live("click",function() {serialized = $('#captcha_form_contact_us').serialize()+"&request="+"check_captcha"; if(jQuery("#contact_us_form").validationEngine('validate')) $.post('pages/post.php', serialized , function(data){ if (data!="") {$("#captcha_contact").html(data);$("html, body").animate({ scrollTop: $("#captcha_contact").offset().top }, "slow");} else $("#contact_us").click();});});
        $( "#contact_us" ).button()                                 .live("click",function() {$.post('pages/post.php', $("#contact_us_form").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        //$( "#contact_us" ).button()                                 .live("click",function() {serialized = $('#captcha_form').serialize()+$("#contact_us_form").serialize(); $.post('pages/post.php', serialized , function(data){$("#captcha_contact").html(data);$(':button').button();});})
        $( "#services_link")                                        .live("click",function() {hide_prompts();$.post('pages/post.php', { request: "services" },function(data){$("#content").html(data);$(':button').button();$("#tabs" ).tabs();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#register_link")                                        .live("click",function() {hide_prompts();$.post('pages/post.php', { request: "register" },function(data){$("#content").html(data);show_cities();jQuery("#register_form").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#register_user" ).button()                              .live("click",function() {hide_prompts();$.post('pages/post.php', { request: "register" },function(data){$("#content").html(data);show_cities();jQuery("#register_form").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#register_button" ).button()                            .live("click",function() {if(jQuery("#register_form").validationEngine('validate') && checkpass()) $.post('pages/post.php', $("#register_form").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#confirm_register_button" ).button()                    .live("click",function() {$.post('pages/post.php', { request: "register_user" }, function(data){$("#content").html(data);$(':button').button();});});
        // end of this for main page without user logged in
        // user logged in
        $( "#main_user_button" ).button()                           .live("click",function() {main_user();clear_sidebar();});
        $( "#contact_us_user_link")                                 .live("click",function() {hide_prompts();$.post('post_user.php', { request: "contact_us_user" },function(data){$("#content").html(data);jQuery("#contact_us_user_form").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#process_contact_us_user_button" ).button()             .live("click",function() {if(jQuery("#contact_us_user_form").validationEngine('validate')) $.post('post_user.php', $("#contact_us_user_form").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#edit_data_user_link")                                  .live("click",function() {hide_prompts();$.post('post_user.php', { request: "edit_data_user" },function(data){$("#content").html(data);show_cities_user();show_other();jQuery("#edit_data_user_form").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"edit_data_sidebar_user"},function(data){$("#sidebar_dynamic").html(data);jQuery("#edit_data_user_form").validationEngine('attach');$(':button').button();});});
        $( "#process_edit_data_user_button" ).button()              .live("click",function() {if(jQuery("#edit_data_user_form").validationEngine('validate')) $.post('post_user.php', $("#edit_data_user_form").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});});
        $( "#edit_data_user_button")                                .live("click",function() {hide_prompts();$.post('post_user.php', { request: "edit_data_user" },function(data){$("#content").html(data);show_cities_user();show_other();jQuery("#edit_data_user_form").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#privacy_management_user_button")                       .live("click",function() {hide_prompts();$.post('post_user.php', { request: "privacy_management_user" },function(data){$("#content").html(data);privacy_checkbox_change();$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#password_change_user_button")                          .live("click",function() {hide_prompts();$.post('post_user.php', { request: "password_change_user" },function(data){$("#content").html(data);jQuery("#change_password_user_form").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});});
        $( "#process_password_change_user_button" ).button()        .live("click",function() {if(jQuery("#change_password_user_form").validationEngine('validate')) $.post('post_user.php', $("#change_password_user_form").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});});
        $( "#account_activation_user_button" ).button()             .live("click",function() {hide_prompts();$.post('post_user.php', { request: "account_activation_user" },function(data){$("#content").html(data);jQuery("#account_activation_user_form").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});clear_sidebar();});
        $( "#confirm_account_activation_user_button" ).button()     .live("click",function() {if(jQuery("#account_activation_user_form").validationEngine('validate')) $.post('post_user.php', $("#account_activation_user_form").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: 0 }, "slow");});});
        $( "#process_account_activation_user_button" ).button()     .live("click",function() {$.post('post_user.php', { request: "process_activation_user" },function(data){$("#content").html(data);$(':button').button();});});
        // show items
        $( "#show_items_user" ).button()                            .live("click",function() {window.location = "show_items.php";});
        $( "#show_single_item_user" ).button()                      .live("click",function() {$.post('post_user.php', { request: "show_single_item",item_code: id },function(data){$("#content").html(data);$(':button').button();$('#gallery a').lightBox();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#move_to_other_user" ).button()                         .live("click",function() {$.post('post_user.php', { request: "move_to_other_user" },function(data){$("#content").html(data);jQuery("#move_item").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#proceed_move_to_other_user" ).button()                 .live("click",function() {if(jQuery("#move_item").validationEngine('validate')) $.post('post_user.php', $("#move_item").serialize(),function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#cancel_move_user" ).button()                           .live("click",function() {$.post('post_user.php', { request: "cancel_move_to_other_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#proceed_cancel_move_to_other_user" ).button()          .live("click",function() {$.post('post_user.php', { request: "proceed_cancel_move_to_other_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#edit_item_user" ).button()                             .live("click",function() {$.post('post_user.php', { request: "edit_item_user" },function(data){$("#content").html(data);jQuery("#update_item").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#process_edit_item_user" ).button()                     .live("click",function() {if(jQuery("#update_item").validationEngine('validate')) jQuery("#update_item").submit(); $.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#get_sticker_user" ).button()                           .live("click",function() {$.post('post_user.php', { request: "get_sticker_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#process_get_sticker_user" ).button()                   .live("click",function() { $.post('post_user.php', $("#get_sticker").serialize(),function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"show_items_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        // add item 
        $( "#add_item_user" ).button()                              .live("click",function() {hide_prompts();$.post('post_user.php', { request: "add_item_user" },function(data){$("#content").html(data);jQuery("#add_item").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"add_item_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#add_item_user_link" )                                  .live("click",function() {hide_prompts();$.post('post_user.php', { request: "add_item_user" },function(data){$("#content").html(data);jQuery("#add_item").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"add_item_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#process_add_item_user" ).button()                      .live("click",function() {if(jQuery("#add_item").validationEngine('validate')) jQuery("#add_item").submit(); $.post('post_user.php', { request: "null",sidebar_request:"add_item_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#confirm_add_item_user" ).button()                      .live("click",function() { $.post('post_user.php', {request:"confirm_add_item_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"add_item_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#cancel_add_item_user" ).button()                       .live("click",function() {$.post('post_user.php', { request: "add_item_user", sub_request:"cancel_add_item_user" },function(data){$("#content").html(data);jQuery("#add_item").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"add_item_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#register_your_own_code_user" ).button()                .live("click",function() {$.post('post_user.php', { request: "register_your_own_code_user" },function(data){$("#content").html(data);jQuery("#register_code").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"add_item_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#charge_your_credit_user" ).button()                    .live("click",function() {$.post('post_user.php', { request: "charge_your_credit_user" },function(data){$("#content").html(data);jQuery("#charge_credit").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"add_item_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#process_charge_your_credit" ).button()                 .live("click",function() {if(jQuery("#charge_credit").validationEngine('validate')) $.post('post_user.php', $("#charge_credit").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"add_item_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();})});
        $( "#ask_for_credit_user" ).button()                        .live("click",function() {$.post('post_user.php', { request: "ask_for_credit_user" },function(data){$("#content").html(data);jQuery("#ask_credit").validationEngine('attach');$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"add_item_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();});});
        $( "#process_ask_for_credit_user" ).button()                .live("click",function() {if(jQuery("#ask_credit").validationEngine('validate')) $.post('post_user.php', $("#ask_credit").serialize(), function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});$.post('post_user.php', { request: "null",sidebar_request:"add_item_sidebar_user" },function(data){$("#sidebar_dynamic").html(data);$(':button').button();})});
        // show received items
        $( "#show_received_codes_user" ).button()                   .live("click",function() {window.location = "show_received.php";});
        $( "#show_single_received_item_user" ).button()             .live("click",function() {$.post('post_user.php', { request: "show_single_received_item_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#accept_single_received_code_user" ).button()           .live("click",function() {$.post('post_user.php', { request: "accept_single_received_code_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#proceed_accept_single_received_code_user" ).button()   .live("click",function() {$.post('post_user.php', { request: "proceed_accept_single_received_code_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        //
        $( "#reject_single_received_code_user" ).button()           .live("click",function() {$.post('post_user.php', { request: "reject_single_received_code_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#proceed_reject_single_received_code_user" ).button()   .live("click",function() {$.post('post_user.php', { request: "proceed_reject_single_received_code_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#proceed_accept_all_received_codes_user" ).button()     .live("click",function() {$.post('post_user.php', { request: "proceed_accept_all_received_codes_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        $( "#proceed_reject_all_received_codes_user" ).button()     .live("click",function() {$.post('post_user.php', { request: "proceed_reject_all_received_codes_user" },function(data){$("#content").html(data);$(':button').button();$("html, body").animate({ scrollTop: $("#content").offset().top }, "slow");});});
        //        
    });
    