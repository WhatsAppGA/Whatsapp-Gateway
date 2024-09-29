$(function() {
    "use strict";
	
	
	// chart 1
	
	var options1 = {
      chart: {
        type: 'area',
        height: 90,
        sparkline: {
          enabled: true
        }
      },
      dataLabels: {
          enabled: false
      },
      fill: {
        type: 'gradient',
          gradient: {
              shade: 'light',
              //gradientToColors: [ '#8f50ff'],
              shadeIntensity: 1,
              type: 'vertical',
              opacityFrom: 0.8,
              opacityTo: 0.4,
              stops: [0, 100, 100, 100]
          },
      },
      colors: ["#3b5998"],
      series: [{
        name: 'Facebook Followers',
        data: [25, 66, 41, 59, 25, 44, 12, 36, 9, 21]
      }],
      stroke: {
              width: 2.5, 
              curve: 'smooth',
              dashArray: [0]
         },
      tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },

            }
    }

    new ApexCharts(document.querySelector("#chart1"), options1).render();
	
	
	
	// chart 2
	
	var options1 = {
      chart: {
        type: 'bar',
        height: 90,
        sparkline: {
          enabled: true
        }
      },
      dataLabels: {
          enabled: false
      },
	  plotOptions: {
			bar: {
		columnWidth: '50%',
		  endingShape: 'rounded',
				dataLabels: {
					position: 'top', // top, center, bottom
				},
			}
		},
      fill: {
        type: 'gradient',
          gradient: {
              shade: 'light',
              //gradientToColors: [ '#8f50ff'],
              shadeIntensity: 1,
              type: 'vertical',
              opacityFrom: 0.8,
              opacityTo: 0.4,
              stops: [0, 100, 100, 100]
          }
      },
      colors: ["#55acee"],
      series: [{
        name: 'Twitter Followers',
        data: [25, 66, 41, 59, 25, 44, 12, 36, 9, 21]
      }],
      stroke: {
              width: 2.5, 
              curve: 'smooth',
              dashArray: [0]
         },
      tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },

            }
    }

    new ApexCharts(document.querySelector("#chart2"), options1).render();
	
	
	
	
	// chart 3
	
	var options1 = {
      chart: {
        type: 'line',
        height: 90,
        sparkline: {
          enabled: true
        }
      },
      dataLabels: {
          enabled: false
      },
      colors: ["#e52d27"],
      series: [{
        name: 'Youtube Subscribers',
        data: [12, 14, 2, 47, 32, 44, 14, 55, 41, 69]
      }],
      stroke: {
              width: 2.5, 
              curve: 'smooth',
              dashArray: [0]
         },
      tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },

            }
    }

    new ApexCharts(document.querySelector("#chart3"), options1).render();
	
	
	
	
	
	// chart 4
	 
  var options = {
    chart: {
      height: 220,
      type: 'radialBar',
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      radialBar: {
        //startAngle: -240,
        //endAngle: 120,
         hollow: {
          margin: 0,
          size: '80%',
    //background: '#000',
          image: 'assets/images/icons/shopping-cart.svg',
      imageWidth: 34,
      imageHeight: 34,
    imageClipped: false
        },
        track: {
          background: '#a9ffb2',
          strokeWidth: '100%',
          margin: 0, // margin is in pixels
          dropShadow: {
            enabled: false,
            top: -3,
            left: 0,
            blur: 4,
            opacity: 0.35
          }
        },

        dataLabels: {
          showOn: 'always',
          name: {
            offsetY: -10,
            show: false,
            color: '#fff',
            fontSize: '17px'
          },
          value: {
            formatter: function(val) {
              return parseInt(val);
            },
            color: '#fff',
            fontSize: '36px',
            show: false,
          }
        }
      }
    },
    colors: ["#12bf24"],
    series: [75],
    stroke: {
      lineCap: 'butt'
    },
    labels: ['Percent'],

  }

  var chart = new ApexCharts(
    document.querySelector("#chart4"),
    options
  );

  chart.render(); 




// chart 5
 
 var options = {
    chart: {
      height: 220,
      type: 'radialBar',
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      radialBar: {
        //startAngle: -240,
        //endAngle: 120,
         hollow: {
          margin: 0,
          size: '80%',
    //background: '#000',
          image: 'assets/images/icons/man-user.svg',
      imageWidth: 34,
      imageHeight: 34,
    imageClipped: false
        },
        track: {
          background: '#e7d9ff',
          strokeWidth: '100%',
          margin: 0, // margin is in pixels
          dropShadow: {
            enabled: false,
            top: -3,
            left: 0,
            blur: 4,
            opacity: 0.35
          }
        },

        dataLabels: {
          showOn: 'always',
          name: {
            offsetY: -10,
            show: false,
            color: '#fff',
            fontSize: '17px'
          },
          value: {
            formatter: function(val) {
              return parseInt(val);
            },
            color: '#fff',
            fontSize: '36px',
            show: false,
          }
        }
      }
    },
    colors: ["#673ab7"],
    series: [55],
    stroke: {
      lineCap: 'butt'
    },
    labels: ['Percent'],

  }

  var chart = new ApexCharts(
    document.querySelector("#chart5"),
    options
  );

  chart.render(); 



// chart 6
 
 var options = {
    chart: {
      height: 220,
      type: 'radialBar',
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      radialBar: {
        //startAngle: -240,
        //endAngle: 120,
         hollow: {
          margin: 0,
          size: '80%',
    //background: '#000',
          image: 'assets/images/icons/eye.svg',
      imageWidth: 34,
      imageHeight: 34,
    imageClipped: false
        },
        track: {
          background: '#ffd2e2',
          strokeWidth: '100%',
          margin: 0, // margin is in pixels
          dropShadow: {
            enabled: false,
            top: -3,
            left: 0,
            blur: 4,
            opacity: 0.35
          }
        },

        dataLabels: {
          showOn: 'always',
          name: {
            offsetY: -10,
            show: false,
            color: '#fff',
            fontSize: '17px'
          },
          value: {
            formatter: function(val) {
              return parseInt(val);
            },
            color: '#fff',
            fontSize: '36px',
            show: false,
          }
        }
      }
    },
    colors: ["#e91e63"],
    series: [68],
    stroke: {
      lineCap: 'butt'
    },
    labels: ['Percent'],

  }

  var chart = new ApexCharts(
    document.querySelector("#chart6"),
    options
  );

  chart.render(); 



// chart 7
 
 var options = {
    chart: {
      height: 220,
      type: 'radialBar',
      toolbar: {
        show: false
      }
    },
    plotOptions: {
      radialBar: {
        //startAngle: -240,
        //endAngle: 120,
         hollow: {
          margin: 0,
          size: '80%',
    //background: '#000',
          image: 'assets/images/icons/influencer.svg',
      imageWidth: 34,
      imageHeight: 34,
    imageClipped: false
        },
        track: {
          background: '#fbe7ad',
          strokeWidth: '100%',
          margin: 0, // margin is in pixels
          dropShadow: {
            enabled: false,
            top: -3,
            left: 0,
            blur: 4,
            opacity: 0.35
          }
        },

        dataLabels: {
          showOn: 'always',
          name: {
            offsetY: -10,
            show: false,
            color: '#fff',
            fontSize: '17px'
          },
          value: {
            formatter: function(val) {
              return parseInt(val);
            },
            color: '#fff',
            fontSize: '36px',
            show: false,
          }
        }
      }
    },
    colors: ["#ffc107"],
    series: [85],
    stroke: {
      lineCap: 'butt'
    },
    labels: ['Percent'],

  }

  var chart = new ApexCharts(
    document.querySelector("#chart7"),
    options
  );

  chart.render(); 
	
	
	
	
	// chart 8
	
	var options1 = {
      chart: {
        type: 'area',
        height: 100,
        sparkline: {
          enabled: true
        }
      },
      dataLabels: {
          enabled: false
      },
      fill: {
        type: 'gradient',
          gradient: {
              shade: 'light',
              //gradientToColors: [ '#8f50ff'],
              shadeIntensity: 1,
              type: 'vertical',
              opacityFrom: 0.8,
              opacityTo: 0.4,
              stops: [0, 100, 100, 100]
          },
      },
      colors: ["#d80898"],
      series: [{
        name: 'Orders',
        data: [25, 66, 41, 59, 25, 44, 12, 36, 9, 21]
      }],
      stroke: {
              width: 2.5, 
              curve: 'smooth',
              dashArray: [0]
         },
      tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },

            }
    }

    new ApexCharts(document.querySelector("#chart8"), options1).render();
	
	
	
	
	// chart 9
	
	var options1 = {
      chart: {
        type: 'area',
        height: 100,
        sparkline: {
          enabled: true
        }
      },
      dataLabels: {
          enabled: false
      },
      fill: {
        type: 'gradient',
          gradient: {
              shade: 'light',
              //gradientToColors: [ '#8f50ff'],
              shadeIntensity: 1,
              type: 'vertical',
              opacityFrom: 0.8,
              opacityTo: 0.4,
              stops: [0, 100, 100, 100]
          },
      },
      colors: ["#02cc89"],
      series: [{
        name: 'Visits',
        data: [12, 14, 2, 47, 32, 44, 14, 55, 41, 69]
      }],
      stroke: {
              width: 2.5, 
              curve: 'smooth',
              dashArray: [0]
         },
      tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },

            }
    }

    new ApexCharts(document.querySelector("#chart9"), options1).render();
	
	
	
	
	// chart 10
	
	var options1 = {
      chart: {
        type: 'bar',
        height: 100,
        sparkline: {
          enabled: true
        }
      },
	  plotOptions: {
			bar: {
		columnWidth: '50%',
		  endingShape: 'rounded',
				dataLabels: {
					position: 'top', // top, center, bottom
				},
			}
		},
      dataLabels: {
          enabled: false
      },
      fill: {
        type: 'gradient',
          gradient: {
              shade: 'light',
              //gradientToColors: [ '#8f50ff'],
              shadeIntensity: 1,
              type: 'vertical',
              opacityFrom: 0.8,
              opacityTo: 0.4,
              stops: [0, 100, 100, 100]
          },
      },
      colors: ["#1b56d6"],
      series: [{
        name: 'Sales',
        data: [47, 45, 74, 32, 56, 31, 44, 33, 45, 19]
      }],
      stroke: {
              width: 2.5, 
              curve: 'straight',
              dashArray: [0]
         },
      tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },

            }
    }

    new ApexCharts(document.querySelector("#chart10"), options1).render();
	
	
	
	// chart 11
	
	var options1 = {
      chart: {
        type: 'bar',
        height: 100,
        sparkline: {
          enabled: true
        }
      },
	  plotOptions: {
			bar: {
		columnWidth: '50%',
		  endingShape: 'rounded',
				dataLabels: {
					position: 'top', // top, center, bottom
				},
			}
		},
      dataLabels: {
          enabled: false
      },
      fill: {
        type: 'gradient',
          gradient: {
              shade: 'light',
              //gradientToColors: [ '#8f50ff'],
              shadeIntensity: 1,
              type: 'vertical',
              opacityFrom: 0.8,
              opacityTo: 0.4,
              stops: [0, 100, 100, 100]
          },
      },
      colors: ["#f1b307"],
      series: [{
        name: 'New Users',
        data: [15, 75, 47, 65, 14, 32, 19, 54, 44, 61]
      }],
      stroke: {
              width: 2.5, 
              curve: 'straight',
              dashArray: [0]
         },
      tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },

            }
    }

    new ApexCharts(document.querySelector("#chart11"), options1).render();
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	});