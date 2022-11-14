/*
* PayPal Smart Payment Gateway
*
* @version 1.0
* @package MaaxPayPal
* @author Alex Mathias 
* @copyright (C) 2009-2022 WebsiteDons.com
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*/

var modname = "MaaxPayPal";
$(document).ready(function()
{
	$("#"+modname+"_submit").on("click",function(e) {
		e.preventDefault();
		$("#"+modname+"Form input[name=controller]").val("save_settings");
		MioAjaxElement($(this),{
			waiting_text:waiting_text,
			progress_text:progress_text,
			result:modname+"_handler",
		});
	});
	$("#"+modname+"_test").on("click",function(e){
		e.preventDefault();
		$("#"+modname+"Form input[name=controller]").val("test_connection");
		MioAjaxElement($(this),{
			waiting_text:waiting_text,
			progress_text:progress_text,
			result:modname+"_handler",
		});
	});
	
	// select2
	$(".s2-multiple").select2({
		placeholder: "Select",
		allowClear: true
	}); 
	
});

function MaaxPayPal_handler(result){
	if(result != ''){
		var solve = getJson(result);
		if(solve !== false){
			if(solve.status == "error") {
				if(solve.for != undefined && solve.for != ''){
					$("#"+modname+"Form "+solve.for).focus();
					$("#"+modname+"Form "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
					$("#"+modname+"Form "+solve.for).change(function(){
						$(this).removeAttr("style");
					});
				}
				if(solve.message != undefined && solve.message != '')
					alert_error(solve.message,{timer:3000});
			}else if(solve.status == "successful"){
				alert_success(solve.message,{timer:500});
			}
		}else{
			console.log(result);
		}
	}
}