"use strict"

var dezSettingsOptions = {};

function getUrlParams() {
  const urlParams = new URLSearchParams(window.location.search);
  const dirFromUrl = urlParams.get('dir');
  if (dirFromUrl === 'ltr' || dirFromUrl === 'rtl') {
    return dirFromUrl;
  }

  const htmlElement = document.documentElement;
  return htmlElement.dir || 'ltr';
}

(function($) {
	
	"use strict"
	
	var body = $('body');
	var direction =  getUrlParams('dir');
	
	dezSettingsOptions = {
		typography: "poppins",
		version: localStorage.getItem('version') ?? "light",
		layout: "vertical",
		primary: "color_12",
		headerBg: "color_1",
		navheaderBg: "color_12",
		sidebarBg: "color_12",
		sidebarStyle: "compact",
		sidebarPosition: "fixed",
		headerPosition: "fixed",
		containerLayout: "full",
		direction: direction,
		navTextColor:'color_1',
		navigationBarImg: ''  /* image path or null*/
	};
	
	new dezSettings(dezSettingsOptions);

	jQuery(window).on('resize',function(){
        /*Check container layout on resize */
        dezSettingsOptions.containerLayout = $('#container_layout').val();
        /*Check container layout on resize END */
        
		new dezSettings(dezSettingsOptions); 
	});
	
	if(direction == 'rtl' || body.attr('direction') == 'rtl'){
        direction = 'rtl';
		jQuery('.main-css').attr('href','css/style-rtl.css');
    }else{
        direction = 'ltr';
		jQuery('.main-css').attr('href','css/style-ltr.css');
	}
	
})(jQuery);