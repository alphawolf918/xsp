$(document).ready(function(){
	var elem = $('xsp');
	elem.each(function(){
		var xspElem = $(this);
		var xspCode = xspElem.html();
		xspCode = xspCode.replace(/\n|\t/g,"");
		var attrID = xspElem.attr('forid');
		if(!xspElem.attr('forid')){
			clog("error","ID and FORID attributes cannot be 0 or empty for XSP elements.");
			return false;
		}
		var resElem = $('xresult[id='+attrID+']');
		$.ajax({
			type: "POST", url: "runXSP.php", data: "q="+xspCode+"&n=1",
			complete: function(data){
				resElem.html(data.responseText);
			}
		});
	});
	var xip = $('xip');
	xip.each(function(){
		$.getJSON("getip.php", function(data){
			xip.html(data.ip);
		});
	});
	var pb = $('pb');
	pb.each(function(){
		$(this).css({
			"padding" : "4px"
		});
	});
});

function clog(strType,strMessage){
	var str = "["+strType.toUpperCase()+"] "+strMessage;
	switch(strType){
		default:
			console.log(str);
		break;
		case 'error':
			console.error(str);
		break;
		case 'info':
			console.info(str);
		break;
		case 'warn':
			console.warn(str);
		break;
		case 'debug':
			console.debug(str);
		break;
	}
}

var selector='#xsp';
var res=$(selector);

$(document).ready(function(){
	$('script, xscript').each(function(){
		var script=$(this);
		$(this).css("display","none");
		if(script.attr("type")=="xsp"){
			console.log("XSP Located");
			var scriptHTML=script.html();
			scriptHTML=scriptHTML.replace("<!--","");
			scriptHTML=scriptHTML.replace("// -->","");
			scriptHTML=scriptHTML.replace(/\n|\t/g,"");
			runXSP(scriptHTML,$(this));
		}});
	});
	
function runXSP(str){
	$.ajax({
		type: "POST", url: "runXSP.php", data:"q="+str+"&n=1",
		complete: function(data){
			res.html(data.responseText);
		}});
}