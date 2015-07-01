/// <reference path="../typings/jquery/jquery.d.ts"/>
$(document).ready(function() {
  if (navigator.userAgent.match(/Android/i)) {
    window.scrollTo(0,0); // reset in case prev not scrolled  
    var nPageH = $(document).height();
    var nViewH = window.outerHeight;
    if (nViewH > nPageH) {
      nViewH -= 250;
      $('BODY').css('height',nViewH + 'px');
    }
    window.scrollTo(0,1);
  }

});

if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 $( document ).on( "pageinit", "#pageone", function() {
    $( document ).on( "swipeleft swiperight", "#pageone", function( e ) {
        // Hier überprüfen wir ob ein bespielsweise linkes Panel bereits offen ist, ansonsten würde
	    // sich das rechte Panel beim Schließen des linken Panels durch ein Swipe mit öffnen.
        // Dabei verwenden wir die Daten aus dem framework und überprüfen durch (panel: open).
        if ( $.mobile.activePage.jqmData( "panel" ) !== "open" ) {
           if ( e.type === "swiperight" ) {
                $( "#search" ).panel( "open" );
            }
        }
    });
});
}

function updateVtp() {
	$.getJSON('json.php', showVtpData);
};

//call updateVtp automatically
document.onLoad = function() {
	updateVtp();
}

function showVtpData(d) {
	for (var day = 1; day <=3; day++) {
		var cday = getDay(day);
		cday.children()[0].innerHTML = d.vertretungen['tag'+day].datum;
		data = d.vertretungen['tag'+day].daten;
		$('#day' + day + " ul li").remove();
		$('#day' + day + " ul").append("<li class=\"ui-li-static ui-body-inherit ui-first-child\">" + data[0] + "</li>");
		for (var i = 1; i < data.length; i++) {
			$('#day' + day + " ul").append("<li class=\"ui-li-static ui-body-inherit\">" + data[i] + "</li>");
		}
		$('#day' + day + " ul").append("<li class=\"ui-li-static ui-body-inherit\">Fehlende Lehrer:</li>");
		$('#day' + day + " ul").append("<li class=\"ui-li-static ui-body-inherit\">" + d.vertretungen['tag'+day].fehlende_lehrer + "</li>");
		$('#day' + day + " ul").append("<li class=\"ui-li-static ui-body-inherit\">Fehlende Klassen:</li>");
		$('#day' + day + " ul").append("<li class=\"ui-li-static ui-body-inherit ui-last-child\">" + d.vertretungen['tag'+day].fehlende_klassen + "</li>");
	}
	
    getNotice().children()[1].innerHTML = d.info;
}

function getDay(n) {
	return $('#day' + n);
}

function getNotice() {
  	return $('#hinweis');
}

