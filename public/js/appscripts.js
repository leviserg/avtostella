
$(document).ready(function(){
    var now = new Date();
    var timetext = now.toLocaleTimeString();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear()+"-"+(month)+"-"+(day);
    var dt = setInterval("getActUnackCount()", 60*1000);
    $('#dpicker').val(today);
    $('#tpicker').val("00:00:00");
    getCurTime();
    getActUnackCount();

	$('[data-toggle="tooltip"]').tooltip();

	$(window).scroll(function(){
		if ($(this).scrollTop() > 200) {
			$('.scrollup').fadeIn();
		}
		else{
			$('.scrollup').fadeOut();
		}
	});


	$('.scrollup').click(function(){
	$("html, body").animate({ scrollTop: 0 }, 500);
	    return false;
    });

/*
    $("#dpicker").datepicker({
		dateFormat: "yyyy-mm-dd",
		duration: "normal",
		dayNamesMin: [ "Нд", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб" ],
		monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ],
		maxLength:10
    });
*/
});



// ----------------------------------------
// ******* F U N C T I O N S *******
// ----------------------------------------
// ----------------------------------------
function ScrollToMark(linkname){
	$('html, body').animate({scrollTop:$(linkname).offset().top}, 1000);
}
// ----------------------------------------
function revMyDateFmt(val){
	var ret = "";
	var year = val.substring(6);
	var month = val.substring(3,5);
	var day = val.substring(0,2);
		ret = year+"-"+month+"-"+day;
	return ret;
}
// ----------------------------------------
function getCurTime(){
    var curdate = new Date();
    var datetext = curdate.toLocaleDateString() + " " + curdate.toLocaleTimeString();
    $('#curtime').text(datetext);
    setTimeout("getCurTime()",1000);
}
// ----------------------------------------
function getActUnackCount(){
        $.ajax({
            type:'GET',
            url: '/alarms/getactunack',
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            cache: false,
            crossDomain: true,
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function(data){
                var unack = data;
                $('#unacksum').text(unack);
            },
            error: function(err){
                console.log(err);
            }
        });
 // per minute
}
// ----------------------------------------

/*
function anim(){
	var tr = document.getElementsByClassName('t1');
	for(var id in tr){
		var elem = tr[id];
		if(elem.className === 'row chartdiv t1'){
			elem.className += ' anim';
        }
        else{
            elem.className = 'row chartdiv t1';
        }
	}
	var tl = document.getElementsByClassName('t0');
	for(var id in tl){
		var elem = tl[id];
		if(elem.className === 'row chartdiv t0'){
			elem.className += ' anim';
        }
        else{
            elem.className = 'row chartdiv t0';
        }
	}
}
*/

