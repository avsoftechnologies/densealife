var lastTheme = 'simplex';

loadCSS = function(href) {

	var cssLink = $("<link>");
	$("head").append(cssLink); //IE hack: append before setting href

	cssLink.attr({
		    rel:  "stylesheet",
		    type: "text/css",
		    href: href
	});

};


function loadThemeCSS(theme)
{
	var styleSheets = document.styleSheets;
	var href = [
		'/addons/demo_bootswatch/themes/bootswatch/css/bootstrap-responsive.min.css',
		'/addons/demo_bootswatch/themes/bootswatch/css/bootstrap.min.css',
		'/addons/demo_bootswatch/themes/bootswatch/css/'+lastTheme+'/bootstrap-responsive.min.css',
		'/addons/demo_bootswatch/themes/bootswatch/css/'+lastTheme+'/bootstrap.min.css'
	];

	for (var i = 0; i < styleSheets.length; i++)
	{
	    if (styleSheets[i].href == href)
	    {
	        styleSheets[i].disabled = true;
	        break;
	    }
	}

	loadCSS("/addons/demo_bootswatch/themes/bootswatch/css/"+theme+"/bootstrap-responsive.min.css");
	loadCSS("/addons/demo_bootswatch/themes/bootswatch/css/"+theme+"/bootstrap.min.css");

	lastTheme = theme;
}