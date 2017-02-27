$("#bigger").click(function () {
	$('.content').css('font-size', '+=2');
  });

$("#smaller").click(function () {
	$('.content').css('font-size', '-=2');
  });

$("#green").click(function(){
	var bgcolor = $(".body").css('background-color');
	if (bgcolor == 'rgb(255, 255, 255)') {
		$(".body").css('background-color','rgb(233, 250, 255)');
		return;
	}
	$(".body").css('background-color','#fff');
});

$("#lamp").click(function(){
	var islamp = $(".body").css('background-color');
	if (islamp == 'rgb(255, 255, 255)') {
		$(".body").css('background-color','#303030');
		$(".body a").css('color','#b0b7ac');
		$(".body").css('color','#b0b7ac');
		$(".btn").css('color','#b0b7ac');
		$(".btn").css('background-color','#303030');
		return;
	}
	$(".body").css('background-color','#fff');
	$(".body a").css('color','#000');
	$(".body").css('color','#000');
	$(".btn").css('color','#000');
	$(".btn").css('background-color','#fff');
});