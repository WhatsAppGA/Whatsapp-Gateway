(function($) {
  "use strict" 

 var dzChartJs = function(){
	//let draw = Chart.controllers.line.__super__.draw; //draw shadow
	
	var screenWidth = $(window).width();
	
	var barChart1 = function () {
		if (jQuery('#barChart_1').length > 0) {
			const barChart_1 = document.getElementById("barChart_1").getContext('2d');

			barChart_1.height = 100;

			// Get the reference to the existing chart with ID 'barChart_1'
			var existingChart = Chart.getChart(barChart_1.canvas.id);

			// Check if the chart exists
			if (existingChart) {
				// Destroy the existing chart
				existingChart.destroy();
			}

			var ctx = document.getElementById("barChart_1").getContext("2d");
			
			var newChart = new Chart(ctx, {
				type: 'bar',
				data: {
					defaultFontFamily: 'Poppins',
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
					datasets: [
						{
							label: "My First dataset",
							data: [65, 59, 80, 81, 56, 55, 40],
							borderColor: 'rgba(54, 201, 95, 1)',
							borderWidth: "0",
							backgroundColor: 'rgba(54, 201, 95, 1)',
							barPercentage: 0.5
						}
					]
				},
				options: {
					plugins: {
						legend: false,
					},
					scales: {
						y: {
							ticks: {
								beginAtZero: true
							}
						},
						x: {
							barPercentage: 0.5
						}
					}
				}
			});
		}
	}

	var barChart2 = function(){
		if(jQuery('#barChart_2').length > 0 ){

			//gradient bar chart
			const barChart_2 = document.getElementById("barChart_2").getContext('2d');
			
			//generate gradient
			const barChart_2gradientStroke = barChart_2.createLinearGradient(0, 0, 0, 250);
			barChart_2gradientStroke.addColorStop(0, "rgba(54, 201, 95, 1)");
			barChart_2gradientStroke.addColorStop(1, "rgba(54, 201, 95, 0.5)");

			barChart_2.height = 100;
			
			
			// Get the reference to the existing chart with ID 'barChart_2'
			var existingChart = Chart.getChart(barChart_2.canvas.id);

			// Check if the chart exists
			if (existingChart) {
				// Destroy the existing chart
				existingChart.destroy();
			}

			new Chart(barChart_2, {
				type: 'bar',
				data: {
					defaultFontFamily: 'Poppins',
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
					datasets: [
						{
							label: "My First dataset",
							data: [65, 59, 80, 81, 56, 55, 40],
							borderColor: barChart_2gradientStroke,
							borderWidth: "0",
							backgroundColor: barChart_2gradientStroke, 
							hoverBackgroundColor: barChart_2gradientStroke,
							barPercentage: 0.5
							
						}
					]
				},
				options: {
					plugins:{
						legend:false,
						
					},
					scales: {
						y:{
							ticks: {
								beginAtZero: true
							}
						},
						x:{
							// Change here
							barPercentage: 0.5
						}
					}
				}
			});
		}
	}

	var barChart3 = function(){
		//stalked bar chart
		if(jQuery('#barChart_3').length > 0 ){
			
			const barChart_3 = document.getElementById("barChart_3").getContext('2d');
			
			//generate gradient
			const barChart_3gradientStroke = barChart_3.createLinearGradient(50, 100, 50, 50);
			barChart_3gradientStroke.addColorStop(0, "rgba(54, 201, 95, 1)");
			barChart_3gradientStroke.addColorStop(1, "rgba(54, 201, 95, 0.5)");

			const barChart_3gradientStroke2 = barChart_3.createLinearGradient(50, 100, 50, 50);
			barChart_3gradientStroke2.addColorStop(0, "rgba(244, 107, 104, 1)");
			barChart_3gradientStroke2.addColorStop(1, "rgba(244, 107, 104, 1)");

			const barChart_3gradientStroke3 = barChart_3.createLinearGradient(50, 100, 50, 50);
			barChart_3gradientStroke3.addColorStop(0, "rgba(163, 54, 201, 1)");
			barChart_3gradientStroke3.addColorStop(1, "rgba(163, 54, 201, 1)");

			// Get the reference to the existing chart with ID 'barChart_3'
			var existingChart = Chart.getChart(barChart_3.canvas.id);

			// Check if the chart exists
			if (existingChart) {
				// Destroy the existing chart
				existingChart.destroy();
			}
			
			barChart_3.height = 100;

			let barChartData = {
				defaultFontFamily: 'Poppins',
				labels: ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun'],
				datasets: [{
					label: 'Red',
					backgroundColor: barChart_3gradientStroke,
					hoverBackgroundColor: barChart_3gradientStroke, 
					barPercentage: 0.5,
					data: [
						'12',
						'12',
						'12',
						'12',
						'12',
						'12',
						'12'
					]
				},
				{
					label: 'Green',
					backgroundColor: barChart_3gradientStroke2,
					hoverBackgroundColor: barChart_3gradientStroke2, 
					barPercentage: 0.5,
					data: [
						'12',
						'12',
						'12',
						'12',
						'12',
						'12',
						'12'
					]
				},
				{
					label: 'Blue',
					backgroundColor: barChart_3gradientStroke3,
					hoverBackgroundColor: barChart_3gradientStroke3, 
					barPercentage: 0.5,
					data: [
						'12',
						'12',
						'12',
						'12',
						'12',
						'12',
						'12'
					]
				}]
			};

			new Chart(barChart_3, {
				type: 'bar',
				data: barChartData,
				options: {
					plugins:{
						legend:false,
						tooltip: {
							mode: 'index',
							intersect: false
						},
						
					},
					title: {
						display: false
					},
					responsive: true,
					scales: {
						x:{
							stacked: true,
						},
						y:{
							stacked: true
						}
					}
				}
			});
		}
	}
	
	var lineChart1 = function(){
		if(jQuery('#lineChart_1').length > 0 ){


		//basic line chart
			const lineChart_1 = document.getElementById("lineChart_1").getContext('2d');

			class Custom extends Chart.LineController {
				draw() {
					// Call bubble controller method to draw all the points
					super.draw(arguments);	
					const ctx = this.chart.ctx;
					let _stroke = ctx.stroke;
					//ctx.strokeStyle = 'red';
					//ctx.lineWidth = 1;
					ctx.stroke = function(){
						ctx.save();
						ctx.shadowColor = 'rgba(54, 201, 95, 0.1)';
						ctx.shadowBlur = 10;
						ctx.shadowOffsetX = 0;
						ctx.shadowOffsetY = 4;
						_stroke.apply(this, arguments);
						ctx.restore();
						
					}
				}
			};
			Custom.id = 'shadowLine';
			Custom.defaults = Chart.LineController.defaults;

			// Stores the controller so that the chart initialization routine can look it up
			Chart.register(Custom);
			// Get the reference to the existing chart with ID 'lineChart_1'
			var existingChart = Chart.getChart(lineChart_1.canvas.id);

			// Check if the chart exists
			if (existingChart) {
				// Destroy the existing chart
				existingChart.destroy();
			}
			
			lineChart_1.height = 100;

			new Chart(lineChart_1, {
				type: 'shadowLine',
				data: {
					defaultFontFamily: 'Poppins',
					labels: ["Jan", "Febr", "Mar", "Apr", "May", "Jun", "Jul"],
					datasets: [
						{
							label: "My First dataset",
							data: [25, 20, 60, 41, 66, 45, 80],
							borderColor: 'rgba(100, 24, 195, 1)',
							borderWidth: "2",
							backgroundColor: 'transparent',  
							pointBackgroundColor: 'rgba(100, 24, 195, 1)',
							tension:0.5,
						}
					]
				},
				options: {
					plugins:{
							legend:false,
						
					},
					scales: {
						y:{
							max: 100, 
							min: 0, 
							ticks: {
								beginAtZero: true, 
								stepSize: 20, 
								padding: 10
							}
						},
						x:{
							ticks: {
								padding: 5
							}
						}
					}
				}
			});
		}
	}
	
	var lineChart2 = function(){
		//gradient line chart
		if(jQuery('#lineChart_2').length > 0 ){
			
			const lineChart_2 = document.getElementById("lineChart_2").getContext('2d');
			//generate gradient
			const lineChart_2gradientStroke = lineChart_2.createLinearGradient(500, 0, 100, 0);
			lineChart_2gradientStroke.addColorStop(0, "rgba(54, 201, 95, 1)");
			lineChart_2gradientStroke.addColorStop(1, "rgba(54, 201, 95, 0.5)");

			class Custom extends Chart.LineController {
				draw() {
					// Call bubble controller method to draw all the points
					super.draw(arguments);	
					const ctx = this.chart.ctx;
					let _stroke = ctx.stroke;
					//ctx.strokeStyle = 'red';
					//ctx.lineWidth = 1;
					ctx.stroke = function(){
						ctx.save();
						ctx.shadowColor = 'rgba(54, 201, 95, 0.5)';
						ctx.shadowBlur = 10;
						ctx.shadowOffsetX = 0;
						ctx.shadowOffsetY = 4;
						_stroke.apply(this, arguments);
						ctx.restore();
						
					}
				}
			};
			Custom.id = 'shadowLine';
			Custom.defaults = Chart.LineController.defaults;

			// Stores the controller so that the chart initialization routine can look it up
			Chart.register(Custom);

			// Get the reference to the existing chart with ID 'lineChart_2'
			var existingChart = Chart.getChart(lineChart_2.canvas.id);

			// Check if the chart exists
			if (existingChart) {
			  // Destroy the existing chart
			  existingChart.destroy();
			}
				
			lineChart_2.height = 100;

			new Chart(lineChart_2, {
				type: 'line',
				data: {
					defaultFontFamily: 'Poppins',
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
					datasets: [
						{
							label: "My First dataset",
							data: [25, 20, 60, 41, 66, 45, 80],
							borderColor: lineChart_2gradientStroke,
							borderWidth: "2",
							backgroundColor: 'transparent', 
							tension:0.5,
							pointBackgroundColor: 'rgba(54, 201, 95, 0.5)'
						}
					]
				},
				options: {
					plugins:{
						legend:false,
						
					},
					scales: {
						y:{
							max: 100, 
							min: 0, 
							ticks: {
								beginAtZero: true, 
								stepSize: 20, 
								padding: 10
							}
						},
						x:{ 
							ticks: {
								padding: 5
							}
						}
					}
				}
			});
		}
	}
	
	var lineChart3 = function(){
		//dual line chart
		if(jQuery('#lineChart_3').length > 0 ){
			const lineChart_3 = document.getElementById("lineChart_3").getContext('2d');
			//generate gradient
			const lineChart_3gradientStroke1 = lineChart_3.createLinearGradient(500, 0, 100, 0);
			lineChart_3gradientStroke1.addColorStop(0, "rgba(54, 201, 95, 1)");
			lineChart_3gradientStroke1.addColorStop(1, "rgba(54, 201, 95, 0.5)");

			const lineChart_3gradientStroke2 = lineChart_3.createLinearGradient(500, 0, 100, 0);
			lineChart_3gradientStroke2.addColorStop(0, "rgba(54, 157, 201, 1)");
			lineChart_3gradientStroke2.addColorStop(1, "rgba(54, 157, 201, 1)");

			class Custom extends Chart.LineController {
				draw() {
					// Call bubble controller method to draw all the points
					super.draw(arguments);	
					const ctx = this.chart.ctx;
					let _stroke = ctx.stroke;
					//ctx.strokeStyle = 'red';
					//ctx.lineWidth = 1;
					ctx.stroke = function(){
						ctx.save();
						ctx.shadowColor = 'rgba(19, 98, 252, 0.5)';
						ctx.shadowBlur = 10;
						ctx.shadowOffsetX = 0;
						ctx.shadowOffsetY = 4;
						_stroke.apply(this, arguments);
						ctx.restore();
						
					}
				}
			};
			Custom.id = 'shadowLine';
			Custom.defaults = Chart.LineController.defaults;

			// Stores the controller so that the chart initialization routine can look it up
			Chart.register(Custom);

			// Get the reference to the existing chart with ID 'lineChart_3'
			var existingChart = Chart.getChart(lineChart_3.canvas.id);

			// Check if the chart exists
			if (existingChart) {
			  // Destroy the existing chart
			  existingChart.destroy();
			}
				
			lineChart_3.height = 100;

			new Chart(lineChart_3, {
				type: 'shadowLine',
				data: {
					defaultFontFamily: 'Poppins',
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
					datasets: [
						{
							label: "My First dataset",
							data: [25, 20, 60, 41, 66, 45, 80],
							borderColor: lineChart_3gradientStroke1,
							borderWidth: "2",
							backgroundColor: 'transparent', 
							pointBackgroundColor: 'rgba(54, 201, 95, 0.5)',
							tension:0.5,
						}, {
							label: "My First dataset",
							data: [5, 20, 15, 41, 35, 65, 80],
							borderColor: lineChart_3gradientStroke2,
							borderWidth: "2",
							backgroundColor: 'transparent', 
							tension:0.5,
							pointBackgroundColor: 'rgba(254, 176, 25, 1)'
						}
					]
				},
				options: {
					plugins:{
						legend:false,
						
					},
					scales: {
						y:{
							max: 100, 
							min: 0, 
							ticks: {
								beginAtZero: true, 
								stepSize: 20, 
								padding: 10
							}
						},
						x:{ 
							ticks: {
								padding: 5
							}
						}
					}
				}
			});
		}
	}
	
	var areaChart1 = function(){	
		//basic area chart
		if(jQuery('#areaChart_1').length > 0 ){
			const areaChart_1 = document.getElementById("areaChart_1").getContext('2d');
			// Get the reference to the existing chart with ID 'areaChart_1'
			var existingChart = Chart.getChart(areaChart_1.canvas.id);

			// Check if the chart exists
			if (existingChart) {
			  // Destroy the existing chart
			  existingChart.destroy();
			}
    
			areaChart_1.height = 100;

			new Chart(areaChart_1, {
				type: 'line',
				data: {
					defaultFontFamily: 'Poppins',
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
					datasets: [
						{
							label: "My First dataset",
							data: [25, 20, 60, 41, 66, 45, 80],
							borderColor: 'rgba(0, 0, 1128, .3)',
							borderWidth: "1",
							backgroundColor: 'rgba(54, 201, 95, .5)', 
							tension:0.5,
							fill:true,
							pointBackgroundColor: 'rgba(0, 0, 1128, .3)'
						}
					]
				},
				options: {
					plugins:{
						legend:false,
						
					},
					scales: {
						y:{
							max: 100, 
							min: 0, 
							ticks: {
								beginAtZero: true, 
								stepSize: 20, 
								padding: 10
							}
						},
						x:{ 
							ticks: {
								padding: 5
							}
						}
					}
				}
			});
		}
	}
	
	var areaChart2 = function(){	
		//gradient area chart
		if(jQuery('#areaChart_2').length > 0 ){
			const areaChart_2 = document.getElementById("areaChart_2").getContext('2d');
			//generate gradient
			const areaChart_2gradientStroke = areaChart_2.createLinearGradient(0, 1, 0, 500);
			areaChart_2gradientStroke.addColorStop(0, "rgba(163, 54, 201, 0.2)");
			areaChart_2gradientStroke.addColorStop(1, "rgba(163, 54, 201, 0)");

			// Get the reference to the existing chart with ID 'areaChart_2'
			var existingChart = Chart.getChart(areaChart_2.canvas.id);

			// Check if the chart exists
			if (existingChart) {
			  // Destroy the existing chart
			  existingChart.destroy();
			}
			
			areaChart_2.height = 100;

			new Chart(areaChart_2, {
				type: 'line',
				data: {
					defaultFontFamily: 'Poppins',
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
					datasets: [
						{
							label: "My First dataset",
							data: [25, 20, 60, 41, 66, 45, 80],
							borderColor: "#ffb800",
							borderWidth: "4",
							tension:0.5,
							fill:true,
							backgroundColor: areaChart_2gradientStroke
						}
					]
				},
				options: {
					plugins:{
						legend:false,
					},
					scales: {
						y:{
							max: 100, 
							min: 0, 
							ticks: {
								beginAtZero: true, 
								stepSize: 20, 
								padding: 5
							}
						},
						x:{ 
							ticks: {
								padding: 5
							}
						}
					}
				}
			});
		}    
	}    

	var areaChart3 = function(){	
		//gradient area chart
		if(jQuery('#areaChart_3').length > 0 ){
			const areaChart_3 = document.getElementById("areaChart_3").getContext('2d');
			// Get the reference to the existing chart with ID 'areaChart_3'
			var existingChart = Chart.getChart(areaChart_3.canvas.id);

			// Check if the chart exists
			if (existingChart) {
			  // Destroy the existing chart
			  existingChart.destroy();
			}
    
			areaChart_3.height = 100;

			new Chart(areaChart_3, {
				type: 'line',
				data: {
					defaultFontFamily: 'Poppins',
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
					datasets: [
						{
							label: "My First dataset",
							data: [25, 20, 60, 41, 66, 45, 80],
							borderColor: 'rgb(54, 201, 95)',
							borderWidth: "1",
							fill:true,
							backgroundColor: 'rgba(54, 201, 95, .5)',
							tension:0.5,
						}, 
						{
							label: "My First dataset",
							data: [5, 25, 20, 41, 36, 75, 70],
							borderColor: 'rgb(54, 157, 201)',
							borderWidth: "1",
							tension:0.5,
							fill:true,
							backgroundColor: 'rgba(54, 157, 201, .5)'
						}
					]
				},
				options: {
					plugins:{
						legend:false,
						
					},
					scales: {
						y:{
							max: 100, 
							min: 0, 
							 
							ticks: {
								beginAtZero: true, 
								stepSize: 20, 
								padding: 10
							}
						},
						x:{
							 
							ticks: {
								padding: 5
							}
						}
					}
				}
			});
		}
	}

	var radarChart = function(){	
		if(jQuery('#radar_chart').length > 0 ){
			//radar chart
			const radar_chart = document.getElementById("radar_chart").getContext('2d');

			const radar_chartgradientStroke1 = radar_chart.createLinearGradient(500, 0, 100, 0);
			radar_chartgradientStroke1.addColorStop(0, "rgba(54, 185, 216, .5)");
			radar_chartgradientStroke1.addColorStop(1, "rgba(75, 255, 162, .5)");

			const radar_chartgradientStroke2 = radar_chart.createLinearGradient(500, 0, 100, 0);
			radar_chartgradientStroke2.addColorStop(0, "rgba(68, 0, 235, .5");
			radar_chartgradientStroke2.addColorStop(1, "rgba(68, 236, 245, .5");
				
			// Get the reference to the existing chart with ID 'radar_chart'
			var existingChart = Chart.getChart(radar_chart.canvas.id);

			// Check if the chart exists
			if (existingChart) {
			  // Destroy the existing chart
			  existingChart.destroy();
			}

			// radar_chart.height = 100;
			new Chart(radar_chart, {
				type: 'radar',
				data: {
					defaultFontFamily: 'Poppins',
					labels: [["Eating", "Dinner"], ["Drinking", "Water"], "Sleeping", ["Designing", "Graphics"], "Coding", "Cycling", "Running"],
					datasets: [
						{
							label: "My First dataset",
							data: [65, 59, 66, 45, 56, 55, 40],
							borderColor: '#f21780',
							borderWidth: "1",
							backgroundColor: radar_chartgradientStroke2
						},
						{
							label: "My Second dataset",
							data: [28, 12, 40, 19, 63, 27, 87],
							borderColor: '#f21780',
							borderWidth: "1",
							backgroundColor: radar_chartgradientStroke1
						}
					]
				},
				options: {
					plugins:{
						legend:false,
						
					},
					maintainAspectRatio: false, 
					scale: {
						ticks: {
							beginAtZero: true
						}
					}
				}
			});
		}
	}
	
	var pieChart = function(){
		//pie chart
		if(jQuery('#pie_chart').length > 0 ){
			//pie chart
			const pie_chart = document.getElementById("pie_chart").getContext('2d');
			// Get the reference to the existing chart with ID 'pie_chart'
			var existingChart = Chart.getChart(pie_chart.canvas.id);

			// Check if the chart exists
			if (existingChart) {
			  // Destroy the existing chart
			  existingChart.destroy();
			}
			// pie_chart.height = 100;
			new Chart(pie_chart, {
				type: 'pie',
				data: {
					defaultFontFamily: 'Poppins',
					datasets: [{
						data: [45, 25, 20, 10],
						borderWidth: 0, 
						backgroundColor: [
							"rgba(54, 201, 95, .9)",
							"rgba(54, 201, 95, .7)",
							"rgba(54, 201, 95, .5)",
							"rgba(0,0,0,0.07)"
						],
						hoverBackgroundColor: [
							"rgba(54, 201, 95, .9)",
							"rgba(54, 201, 95, .7)",
							"rgba(54, 201, 95, .5)",
							"rgba(0,0,0,0.07)"
						]
					}],
					labels: [
						"one",
						"two",
						"three", 
						"four"
					]
				},
				options: {
					responsive: true, 
					
					plugins:{
						legend:false,
					},
					aspectRatio:5,
					maintainAspectRatio: false
				}
			});
		}
	}
	
    var doughnutChart = function(){
		if(jQuery('#doughnut_chart').length > 0 ){
			//doughut chart
			const doughnut_chart = document.getElementById("doughnut_chart").getContext('2d');

			// Get the reference to the existing chart with ID 'doughnut_chart'
			var existingChart = Chart.getChart(doughnut_chart.canvas.id);

			// Check if the chart exists
			if (existingChart) {
			  // Destroy the existing chart
			  existingChart.destroy();
			}
			// doughnut_chart.height = 100;
			new Chart(doughnut_chart, {
				type: 'doughnut',
				data: {
					weight: 5,	
					defaultFontFamily: 'Poppins',
					datasets: [{
						data: [45, 25, 20],
						borderWidth: 3, 
						borderColor: "rgba(255,255,255,1)",
						backgroundColor: [
							"rgba(54, 201, 95, 1)",
							"rgba(244, 107, 104, 1)",
							"rgba(163, 54, 201, 1)"
						],
						hoverBackgroundColor: [
							"rgba(54, 201, 95, 0.9)",
							"rgba(244, 107, 104, .9)",
							"rgba(163, 54, 201, .9)"
						]
					}],
					// labels: [
					//     "green",
					//     "green",
					//     "green",
					//     "green"
					// ]
				},
				options: {
					weight: 1,	
					 cutout: 30,
					responsive: true,
					maintainAspectRatio: false
				}
			});
		}
	}
	
	var polarChart = function(){
		if(jQuery('#polar_chart').length > 0 ){
			//polar chart
			const polar_chart = document.getElementById("polar_chart").getContext('2d');

			// Get the reference to the existing chart with ID 'polar_chart'
			var existingChart = Chart.getChart(polar_chart.canvas.id);

			// Check if the chart exists
			if (existingChart) {
			  // Destroy the existing chart
			  existingChart.destroy();
			}
			// polar_chart.height = 100;
			new Chart(polar_chart, {
				type: 'polarArea',
				data: {
					defaultFontFamily: 'Poppins',
					datasets: [{
						data: [15, 18, 9, 6, 19],
						borderWidth: 0, 
						backgroundColor: [
							"rgba(54, 201, 95, 1)",
							"rgba(244, 107, 104, 1)",
							"rgba(163, 54, 201, 1)",
							"rgba(255, 184, 0, 1)",
							"rgba(54, 157, 201, 1)"
						]
					}]
				},
				options: {
					responsive: true, 
					aspectRatio:2,
					maintainAspectRatio: false
				}
			});

		}
	}

	/* Function ============ */
	return {
		init:function(){
			
		},
		
		load:function(){
			barChart1();	
			barChart2();
			barChart3();	
			lineChart1();	
			lineChart2();		
			lineChart3();
			//lineChart03();
			areaChart1();
			areaChart2();
			areaChart3();
			radarChart();
			pieChart();
			doughnutChart(); 
			polarChart(); 
		},
		
		resize:function(){
			barChart1();	
			barChart2();
			barChart3();	
			lineChart1();	
			lineChart2();		
			lineChart3();
			//lineChart03();
			areaChart1();
			areaChart2();
			areaChart3();
			radarChart();
			pieChart();
			doughnutChart(); 
			polarChart(); 		
		}
	}
}();

jQuery(document).ready(function(){
});
	
jQuery(window).on('load',function(){
	dzChartJs.load();
});

jQuery(window).on('resize',function(){
	//dzChartJs.resize();
	setTimeout(function(){ dzChartJs.resize(); }, 1000);
});
	
})(jQuery);