//XSP Interpreter for client-side code.
//Allows XSP to be written inline on
//the page, using script type="xsp".

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