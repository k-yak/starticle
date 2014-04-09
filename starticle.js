/*!
 * Starticle v0.0.1
 * starticle.js
 * Created by Florian Rotagnon
 * Licensed under MIT
 */
 
$( document ).ready(function() {
	var cur = 0;
	
	$('body').append('<div id="starticles-infos">ahahaha</div>');
	
	var infos = $("#starticles-infos").css("content");
	infos = infos.replace(/'/g, '');
	
	var last = Math.round(infos.split(" ")[0]);
	var nbArticle = Math.round(infos.split(" ")[1]);
	var url = infos.split(" ")[2];
	
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
		for(var i = 0 ; i < nbArticle ; ++i)
		{
			var p = "article-"+(cur+i);
			var pNew = "article-"+(cur+i+nbArticle);
			$('.'+p).removeClass(p).addClass(pNew);
		}
		cur = cur + nbArticle;
		update();
	});
	
	$('#starticle-prev').click(function (e) {
		for(var i = 0 ; i < nbArticle ; ++i)
		{
			var p = "article-"+(cur+i);
			var pNew = "article-"+(cur+i-nbArticle);
			$('.'+p).removeClass(p).addClass(pNew);
		}
		cur = cur - nbArticle;
		update();
	});

	$('#starticle-first').click(function (e) {
		for(var i = 0 ; i < nbArticle ; ++i)
		{
			var p = "article-"+(cur+i);
			var pNew = "article-"+i;
			$('.'+p).removeClass(p).addClass(pNew);
		}
		cur = 0;
		update();
	});
	
	$('#starticle-last').click(function (e) {
		for(var i = 0 ; i < nbArticle ; ++i)
		{	
			var nbArtlastPage = (last+1)%nbArticle; 
			if(nbArtlastPage == 0)
				nbArtlastPage = nbArticle;

			var p = "article-"+(cur+i);
			var pNew = "article-"+(last+1-nbArtlastPage+i);
			$('.'+p).removeClass(p).addClass(pNew);
		}
		cur = last;
		update();
	});
});