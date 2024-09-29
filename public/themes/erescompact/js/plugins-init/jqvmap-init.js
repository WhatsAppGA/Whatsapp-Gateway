(function($) {
    /* "use strict" */


 var dzMap = function(){


	var handleWorldMap = function(){
		$('#world-map').vectorMap({ 
			map: 'world_en',
			backgroundColor: 'transparent',
			borderColor: 'rgb(239, 242, 244)',
			borderOpacity: 0.25,
			borderWidth: 1,
			color: 'rgb(239, 242, 244)',
			enableZoom: true,
			hoverColor: 'var(--primary)',
			hoverOpacity: null,
			normalizeFunction: 'linear',
			scaleColors: ['#b6d6ff', '#005ace'],
			selectedColor: 'var(--primary)',
			selectedRegions: null,
			showTooltip: true,
			onRegionClick: function(element, code, region)
			{
				var message = 'You clicked "'
					+ region
					+ '" which has the code: '
					+ code.toUpperCase();
		 
				alert(message);
			}
		});
	}
	
	var handleUsaMap = function(){

		$('#usa').vectorMap({ 
			map: 'usa_en',
			backgroundColor: 'transparent',
			borderColor: 'rgb(239, 242, 244)',
			borderOpacity: 0.25,
			borderWidth: 1,
			color: 'rgb(239, 242, 244)',
			enableZoom: true,
			hoverColor: 'var(--primary)',
			hoverOpacity: null,
			normalizeFunction: 'linear',
			scaleColors: ['#b6d6ff', '#005ace'],
			selectedColor: 'var(--primary)',
			selectedRegions: null,
			showTooltip: true,
			onRegionClick: function(element, code, region)
			{
				var message = 'You clicked "'
					+ region
					+ '" which has the code: '
					+ code.toUpperCase();
		 
				alert(message);
			}
		});
	}
	
	
	/* Function ============ */
		return {
			init:function(){
				//handleWorldMap();
				//handleUsaMap();
			},
			
			
			load:function(){
				handleWorldMap();
				handleUsaMap();
			},
			
			resize:function(){
				
			}
		}
	
	}();
   
   /* Document.ready Start */	
	jQuery(document).ready(function() {
		dzMap.init();
	});
	/* Document.ready END */
	
	/* Window Load START */
	jQuery(window).on('load',function () {
		dzMap.load();
	});
	/*  Window Load END */

})(jQuery);