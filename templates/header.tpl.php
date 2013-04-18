<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>Scraped items</title>
<script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript">
(function($){
	$.fn.extend({
		center: function () {
			return this.each(function() {
				var top = ($(window).height() - $(this).outerHeight()) / 2;
				var left = ($(window).width() - $(this).outerWidth()) / 2;
				$(this).css({position:'fixed', margin:0, top: (top > 0 ? top : 0)+'px', left: (left > 0 ? left : 0)+'px'});
			});
		}
	}); 
})(jQuery);

jQuery.fn.selectText = function(){
	var doc = document;
	var element = this[0];
	var range, selection;

	if (window.getSelection) {
		selection = window.getSelection();
		range = document.createRange();
		range.selectNodeContents(element);
		selection.removeAllRanges();
		selection.addRange(range);
	}
};

$(document).ready(function(){
	$('.name').click(function(){
		$(this).siblings('.hidden').toggle();
	});

	function copy(text) {
		$('#overlay').fadeIn(250, function(){
			$('#board').find('textarea').empty().append(text);
			$('#board').center().show();
			$('#board').find('textarea').selectText();
		});
	}

	function copy2 (text) {
		window.prompt ("Copy to clipboard: Ctrl+C", text);
	}

	$('.btn').click(function(){
		copy2($(this).attr('title'));
	});

	$('#board .close').click(function(){
		$('#board').hide();
		$('#overlay').hide();
	});
	$('#overlay').click(function(){
		$('#board').hide();
		$('#overlay').hide();
	});
	$('.state').change(function(){
		jQuery.post('ajax/changestate.php', {id: $(this).attr('title'), state: $(this).is(':checked')}, function(data){
			console.log(data);
		});
	});
});
</script>

</head>

<body>
<div class="line"></div>
<div id="nav">
	<ul>
		<li><a href="index.php">Scrapyd</a></li>
		<li><a href="data_json.php">Test JSON</a></li>
		<li><a href="data_xml.php">XML</a></li>
		<li><a href="spiders.php">Spiders</a></li>
		<li><a href="scrapydstatus.php">Status</a></li>
	</ul>
</div>