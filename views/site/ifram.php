<script src="/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$("#alert").click(function(e){
		var html = alertW();
		$("#c").append(html);
	});
	$("#add").click(function(e){
		var addHtml = '<div style="width:100%;height:20px;">123456</div>';
		$("#c").append(addHtml);
	});
});
function alertW(){
	var html = '';
	html += '<div style="width:100px;height:100px;border:1px solid #f00;position:fixed;z-index:198;left:296px;top:368px;margin:0;padding:0;">';
	 

	html += '</div>'
	return html;
}
</script>
<button id="alert">alert</button>
<button id="add">+++</button>
<div id="c"></div>