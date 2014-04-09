/*!
 * Starticle v0.0.1
 * starticle.js
 * Created by Florian Rotagnon
 * Licensed under MIT
 */
 
$( document ).ready(function() {
	var nbArticle = 1;
	var cur = 0;
	
	$('body').append('<div id="starticles-infos">ahahaha</div>');
	
	var infos = $("#starticles-infos").css("content");
	infos = infos.replace(/'/g, '');
	
	var last = infos.split(" ")[0];
	var url = infos.split(" ")[1];
	
	$('#starticles-infos').remove();
		
	function update(){	
		if(cur%nbArticle == 0)
		{
			var file = url+'articles'+(cur/nbArticle)+'.css';
			if (!$("link[href='"+file+"']").length)
				$('head').append('<link rel="stylesheet" href="'+file+'" type="text/css" />');
		}
	}
	
	$('#starticle-next').click(function (e) {
		var p = "article-"+cur;
		var pNew = "article-"+(cur+1);
		$('.'+p).removeClass(p).addClass(pNew);
		cur = cur + 1;
		update();
	});
	
	$('#starticle-prev').click(function (e) {
		var p = "article-"+cur;
		var pNew = "article-"+(cur-1);
		$('.'+p).removeClass(p).addClass(pNew);
		cur = cur - 1;
		update();
	});

	$('#starticle-first').click(function (e) {
		var p = "article-"+cur;
		var pNew = "article-0";
		$('.'+p).removeClass(p).addClass(pNew);
		cur = 0;
		update();
	});
	
	$('#starticle-last').click(function (e) {
		var p = "article-"+cur;
		var pNew = "article-"+last;
		$('.'+p).removeClass(p).addClass(pNew);
		cur = last;
		update();
	});
});