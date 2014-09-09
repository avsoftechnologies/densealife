function RecommendationView(data, displaySlideQty)
{
    
    if(data == "\n<!-- \n-->")
        return;
    
    this.data = $.trim(data);
    this.recommendations;
    this.displaySlideQty = displaySlideQty;
	
    this.addDataToView = function()
    {
        var self = this;
        /*inject data*/
        jQuery('#' + self.containerId).html(self.recommendations);
        /*check if another recomm container exists*/
        if (jQuery('#' + self.containerId + ' ' + this.altHtmlElement).length &&
            !jQuery('#' + self.containerId + ' ' + this.altHtmlElement).is(':visible')) {
            /*injection hint existing?*/
            if (jQuery('#' + this.altContainerId).length) {
                jQuery('#' + this.altContainerId).html(jQuery('#' + self.containerId + ' ' + this.altHtmlElement).html());
                jQuery('#' + self.containerId + ' ' + this.altHtmlElement).remove();
            }
        }

        if ($('html').hasClass('ie7')) {
            jQuery('#' + self.containerId).hide();
        } else {
            jQuery('#' + self.containerId).show();
        }
        if(jQuery('#recommengine_topseller_container').length) {
            jQuery('#recommengine_topseller_container').bxSlider({
                displaySlideQty: this.displaySlideQty,
                moveSlideQty: this.displaySlideQty,
                auto: false,
                speed: 450
            });
        }
             
        if(jQuery('#recommSlider').length) {
            jQuery('#recommSlider').bxSlider({
                mode: 'vertical',
                displaySlideQty: 3,
                moveSlideQty: 1,
                auto: false,
                speed: 250
            });
        }

        /* $('#flex-slider3 .slides').bxSlider({
            slideWidth: 192,
            minSlides: 4,
            maxSlides: 4,
            pager: false
        });*/
        
        //JS For Like Slider
        //        $('.like-sec ul.product-list').bxSlider({
        //          slideWidth: 135,
        //         /*added by aditya narayan on 11-oct-2013
        //          aditya.narayan@jabong.com
        //          changed sliding
        //         */
        //         slideMargin: 42,
        //          minSlides: 2,
        //            maxSlides: 2
        //            /* */
        //        });


        $(document).ready(function(e) {
            $('#recomm-slider .slides').slider({
                width					:	'135px',  //item width
                spacing					:	'27px',  //item padding on left and right side, it should be a single value
                slideList				:	'5',  //no of items to slide at a time
                autoSlide				:	'false', //auto slide true/false
                autoSlideInterval		:	5000, //if auto slide true define auto interval in millisec
                slidingSpeed			:	500	 //sliding in interval in millisec
            //complete  : function(){alert('Done!')}
            });
            $('#mostPopular .slides').slider({
                width					:	'135px',  //item width
                spacing					:	'24px',  //item padding on left and right side, it should be a single value
                slideList				:	'4',  //no of items to slide at a time
                autoSlide				:	'false', //auto slide true/false
                autoSlideInterval		:	5000, //if auto slide true define auto interval in millisec
                slidingSpeed			:	500	 //sliding in interval in millisec
            //complete  : function(){alert('Done!')}
            });
            $('#crtYouLike .slides').slider({
                width					:	'135px',  //item width
                spacing					:	'17px',  //item padding on left and right side, it should be a single value
                slideList				:	'4',  //no of items to slide at a time
                autoSlide				:	'false', //auto slide true/false
                autoSlideInterval		:	5000, //if auto slide true define auto interval in millisec
                slidingSpeed			:	500	 //sliding in interval in millisec
            //complete  : function(){alert('Done!')}
            });
            
              $('#crtSaveForLater .slides').slider({
                width					:	'135px',  //item width
                spacing					:	'17px',  //item padding on left and right side, it should be a single value
                slideList				:	'4',  //no of items to slide at a time
                autoSlide				:	'false', //auto slide true/false
                autoSlideInterval		:	5000, //if auto slide true define auto interval in millisec
                slidingSpeed			:	500	 //sliding in interval in millisec
            //complete  : function(){alert('Done!')}
            });
        });
        // DO NOT REMOVE
        /*$('#flex-slider2 .slides').bxSlider({
        slideWidth: 135,
            maxSlides: 5,
            moveSlides: 5,
            pager: false,
			slideMargin: 60
    });*/
        
        /* slider = $('#flex-slider1').bxSlider({
        mode: 'fade'
        });
        if($('#flex-slider1').length){
            slider.startAuto();
        }*/
		
		
        /*you may also like starts*/
		
        var lastslide = $('.verti-slider li:last').index(), //check the last li index no.
        totalnum = lastslide + 1,  //check the total no. of li
        slideno = 4, //no. of li's in one slide
        paginglist = totalnum / slideno,
        pagingnum = 0,
        noofuls = 0,
        liWidth	= $('.verti-slider li').outerWidth(true),
        uls	= $('.verti-slider li');
			
        $('.verti-slider ul').children().unwrap(); //remove ul on load
	
        for(var i = 0, l = uls.length; i < l; i+=slideno) { //add ul on every 4 li's
            uls.slice(i, i+slideno).wrapAll('<ul class="product-list"></ul>');
        }
        $('.verti-slider ul').css({
            'width'		:	(liWidth*2),
            'display'	:	('none')
        });
	
	
        $('.verti-slider li').append('<span class="svloader"><img src="http://static.jabong.com/images/jlite/loading.gif" alt="loading" title="loading" width="32" height="32"></span>');

        $('.verti-slider').before('<div class="verti-slider-paging-wrapper"><div class="verti-slider-paging-left-arrow product-sprite cursor"><span></span></div><div class="verti-slider-paging-right-arrow product-sprite cursor"><span></span></div></div>');
        $('.verti-slider-paging-left-arrow').after('<ul class="verti-slider-paging-list pos-abs mt5"></ul>'); //adding paging list
        for (i = 0; i < paginglist; i++ ) {
            $('.verti-slider-paging-list').append('<li><span></span></li>');
        }
        $('.verti-slider-paging-wrapper li:first').addClass('active');
        $('.verti-slider ul:first').css('display', 'block');
        loaderHide();
	
	
	
        $('.verti-slider-paging-wrapper li span').click(function(){ //click
            if(!$(this).parent('li').hasClass('active')){
                $('.verti-slider li .svloader').show();
                pagingnum = $(this).parent('li').index();
                $('.verti-slider ul').fadeOut();
                $('.verti-slider ul').eq(pagingnum).fadeIn();
                $('.verti-slider-paging-wrapper li').removeClass('active');
                $('.verti-slider-paging-wrapper li').eq(pagingnum).addClass('active');
                datasrc();
            }
        });
        $('.verti-slider-paging-left-arrow span').click(function(){ //click
            $('.verti-slider li .svloader').show();
            if(pagingnum > 0 ){
                pagingnum -= 1;
                fadeUl()
                datasrc();
            }
            else{
                pagingnum = 2;
                fadeUl()
                datasrc();
            }
        });
        $('.verti-slider-paging-right-arrow span').click(function(){ //click
            $('.verti-slider li .svloader').show();
            if(pagingnum < 2 ){
                pagingnum += 1;
                fadeUl()
                datasrc();
            }
            else{
                pagingnum = 0;
                fadeUl()
                datasrc();
            }
        });
	
        function datasrc(){
            $('.verti-slider ul').eq(pagingnum).children('li').each(function(i) {
                if($('a img', this).attr('data-src-onclick')){
                    var path = $('a img', this).attr('data-src-onclick');
                    if(path !== ''){
                        $('a img', this).attr('src',path);
                        $('a img', this).removeAttr('data-src-onclick');
                    }
                    else{}
                }
            });
            loaderHide();
            waitClick();

        }
	
        function fadeUl(){
            $('.verti-slider ul').hide();
            $('.verti-slider ul').eq(pagingnum).show();
            $('.verti-slider-paging-wrapper li').removeClass('active');
            $('.verti-slider-paging-wrapper li').eq(pagingnum).addClass('active');
        }
	
        function loaderHide(){
            //$('ul.recom-widget-slider').eq(pagingnum).waitForImages(function() {
            $('.verti-slider ul').eq(pagingnum).children('li').each(function(i) { //hiding loader one after other
                $('.svloader', this).delay(i*200).fadeOut('slow');
            });
        //});
        }
	
        function waitClick(){
            $('.verti-slider-paging-wrapper span').hide();
            $('.verti-slider-paging-wrapper span').fadeIn(1000);
        }
		
		
        /*you may also like ends*/
		
		
		
		
		
		

        if ($('#recommengine_recommendations').length) {
            $('#recommengine_recommendations .bx-prev,#recommengine_recommendations .bx-pager-link, #recommengine_recommendations .bx-next,#recommengine_recommendations .recom-left-arrow, #recommengine_recommendations .recom-right-arrow').live('click',function() {
                loadImages('recommengine_recommendations', 'id', 'data-src-onclick');
            });
            if((typeof(window.loaded) != 'undefined' && window.loaded == true)) {
                loadImages('recommengine_recommendations', 'id', 'data-src-onload');
            //loadImages('recommengine_recommendations', 'id', 'data-src-onclick');
            }
        }
    }
    
    this.setOptions = function()
    {
        var data = this.data;        
        if (data) {
            if (undefined != data) {    
                $('#recommengine_recommendations').removeClass('d-none');
                this.recommendations = data;
                this.containerId     = 'recommengine_recommendations'; 
                this.altContainerId  = 'recommengine_lastproductsviewed'; 
                this.altHtmlElement  = '.lastproductsviewed';
                this.addDataToView();
            }
        }
    }    
    this.setOptions();    
}

function RecommendationAjaxCart(data, displaySlideQty)
{
    this.data = data;
    this.recommendations;
    this.displaySlideQty = displaySlideQty;

    this.addDataToView = function()
    {
        var self = this;
        /*inject data*/
        jQuery('#' + self.containerId).html(self.recommendations);
        /*check if another recomm container exists*/
        if (jQuery('#' + self.containerId + ' ' + this.altHtmlElement).length &&
            !jQuery('#' + self.containerId + ' ' + this.altHtmlElement).is(':visible')) {
            /*injection hint existing?*/
            if (jQuery('#' + this.altContainerId).length) {
                jQuery('#' + this.altContainerId).html(jQuery('#' + self.containerId + ' ' + this.altHtmlElement).html());
                jQuery('#' + self.containerId + ' ' + this.altHtmlElement).remove();
            }
        }

        if ($('html').hasClass('ie7')) {
            jQuery('#' + self.containerId).hide();
        } else {
            jQuery('#' + self.containerId).show();
        }        
    }

    this.setOptions = function()
    {
        var data = this.data;
        if (data) {
            if (data.indexOf("fail") == -1) {
               
                $(".ui-dialogCartview .pcart-overlay").css("min-height","430px");
                $('#floatingBottomLine').addClass('pcart-overlay-top-bg');               
                this.recommendations = data;
                this.containerId     = 'recommengine_recommendations_cart';
                this.altContainerId  = 'recommengine_lastproductsviewed';
                this.altHtmlElement  = '.lastproductsviewed';
                this.addDataToView();
            }
            else {                
                _gaq.push(['_trackEvent','Pre-cart','Reco','0']);               
                ("#recommengine_recommendations_cart").hide();
            }
        }
    }
    this.setOptions();
}
