/*
@Author		:	Bijomon Varghese
@Created On	:	11/Nov/2013
*/

(function($){
	$.fn.slider = function( options ){
		//Establish our default settings
		var settings = $.extend({
			width		: null,
			spacing		: null,
			slideList	: null,
			complete	: null
		}, options);
		
		return this.each(function(){
			if (settings.width){
				$('li', this).css('width', settings.width);
			}
			
			if (settings.spacing){
				$('li', this).css({
					'padding-right'	:	settings.spacing,
					'padding-left'	:	settings.spacing
				});
			}
			if ($.isFunction(settings.complete)){
				settings.complete.call( this );
			}
			var lstWdth 	=	$('li', this).outerWidth(),
				lstHght 	=	$('li', this).outerHeight(),
				lstNo 		=	$('li', this).length,
				ulWdth		=	lstWdth*lstNo,
				animLeft	=	-lstWdth * settings.slideList,
				minLst		=	settings.slideList*3;
				curntlst	=	0,
				slidingNo	=	settings.slideList*lstWdth,
				arrowbtn	=	0, //if arrow btn is clicked stop the slider from auto scroll
				area		=	$(this).parent('div').width() - 50;

			if(lstNo <= settings.slideList){
			}
			else if(lstNo < minLst){
				var pendingList	=	minLst - lstNo;
				
				for (var i=0; i<pendingList; i++){
					 $(this).children('li').eq(i).clone().appendTo(this);
				}
				
			}
			lstNo	=	$('li', this).length;
			
			$(this).parent('div').css('position', 'relative');
			
                        if(lstNo <= settings.slideList){
                            $(this).css({
				'width'		:	lstWdth * lstNo,
				'float'		:	'left',
				'position'	:	'relative'
				});
			}
			else{
                            $(this).css({
				'width'		:	lstWdth * lstNo,
				'float'		:	'left',
				'position'	:	'relative',
				'left'		:	-lstWdth * settings.slideList
				});
			}
			
			
			$('li', this).css({
				'position'	:	'relative',
				'float'		:	'left'
				});
			
			$(this).wrap('<div class="sliderDiv"></div>');
			if(lstNo <= settings.slideList){
                        
			}
			else{
                        $(this).parent('.sliderDiv').after('<a href="javascript:void(0)" class="common-sprite sl-prev">prev</a><a href="javascript:void(0)" class="common-sprite sl-next">next</a><div class="clear"></div>');
			}
                        
			
			if(lstNo == settings.slideList){
				$(this).parent().siblings('a').hide();
				$(this).css('left', '0');
			}
			else{
				$('li', this).slice(-settings.slideList).prependTo(this);
			}
			
			
			$(this).parent('.sliderDiv').css({
				width		:	area,
				//height		:	lstHght,
				position	:	'relative',
				margin		:	'auto',
				overflow	:	'hidden'
				});
			/*if(!$('li', this).children('a').children('img').attr('data-src-onclick') == ''){alert(1);
				$('li', this).append('<span><img src="http://static.jabong.com/images/jlite/loading.gif"></span>')
			}*/
			
			if (settings.slideList){
				$(this).parent().siblings('.sl-prev').click(function(){
					arrowbtn = 1;
					$(this).siblings('.sliderDiv').children('ul').animate({left: 0}, {
						duration: settings.slidingSpeed,
						complete: function() {
							$('li', this).slice(-settings.slideList).prependTo(this);
							$(this).css('left', -lstWdth * settings.slideList);
							
							for (var i=settings.slideList; i<settings.slideList*2; i++){
								if($('li', this).eq(i).children('a').children('img').attr('data-src-onclick') != undefined){
									var path = $('li', this).eq(i).children('a').children('img').attr('data-src-onclick');
									$('li', this).eq(i).children('a').children('img').attr('src',path);						
									$('li', this).eq(i).children('a').children('img').removeAttr('data-src-onclick');	
								}
							}
							
						}
					});
				});
				$(this).parent().siblings('.sl-next').click(function(){
					arrowbtn = 1;
					$(this).siblings('.sliderDiv').children('ul').animate({left: animLeft - lstWdth * settings.slideList}, {
						duration: settings.slidingSpeed,
						complete: function() {
							$('li', this).slice(0,settings.slideList).appendTo(this);
							$(this).css('left', -lstWdth * settings.slideList);
							
							for (var i=settings.slideList; i<settings.slideList*2; i++){
								if($('li', this).eq(i).children('a').children('img').attr('data-src-onclick') != undefined){
									var path = $('li', this).eq(i).children('a').children('img').attr('data-src-onclick');
									$('li', this).eq(i).children('a').children('img').attr('src',path);						
									$('li', this).eq(i).children('a').children('img').removeAttr('data-src-onclick');	
								}
							}
							
						}
					});
				});
			}
			
			if (settings.autoSlide){
				if(settings.autoSlide == 'true'){
					var curntSlider = $(this);
					
					setInterval(function() {
						if(arrowbtn == 0){ //if arrow btn is clicked the value will change to 1 and the auto slider will stop working
							$(curntSlider).animate({left: animLeft - lstWdth * settings.slideList}, {
								duration: settings.slidingSpeed,
								complete: function() {
									$('li', curntSlider).slice(0,settings.slideList).appendTo(curntSlider);
									$(curntSlider).css('left', -lstWdth * settings.slideList)
								}
							});
						}
					}, settings.autoSlideInterval);
					
				}
			}
			
			
			
		});
	}
}(jQuery));