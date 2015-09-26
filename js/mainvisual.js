 $(document).ready( function(){	
		var buttons = { previous:$('#jslidernews1 .button-previous') ,
						next:$('#jslidernews1 .button-next') };
		$('#jslidernews1').lofJSidernews( { interval:4000,
											 	easing:'easeInOutQuad',
												duration:1000,
												auto:true,
												mainWidth:800,
												mainHeight:300,
												navigatorHeight		: 100,
												navigatorWidth		: 224,
												maxItemDisplay:3,
												buttons:buttons} );						
	});
