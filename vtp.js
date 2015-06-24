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
updateVtp();

function showVtpData(d) {
	for (var day = 1; day <=3; day++) {
		var cday = getDay(day);
		cday.children()[0].innerHTML = d.vertretungen['tag'+day].datum;
		data = d.vertretungen['tag'+day].daten;
		var html = "<ul data-role=\"listview\" data-filter=\"true\" data-input=\"#suche\" data-inset=\"false\" data-theme=\"a\" class=\"ui-group-theme-a ui-listview\">";
		for (var i = 0; i < data.length; i++) {
			html += "<li class=\"ui-li-static ui-body-inherit\">" + data[i] + "</li>";
		}
		html += "<li class=\"ui-li-static ui-body-inherit\">Fehlende Lehrer:</li>";
		html += "<li class=\"ui-li-static ui-body-inherit\">" + d.vertretungen['tag'+day].fehlende_lehrer + "</li>";
		html += "<li class=\"ui-li-static ui-body-inherit\">Fehlende Klassen:</li>";
		html += "<li class=\"ui-li-static ui-body-inherit\">" + d.vertretungen['tag'+day].fehlende_klassen + "</li>";
		html += "</ul>"
		cday.children()[1].innerHTML = html;
	}
	
    getNotice().children()[1].innerHTML = d.info;
}

function getDay(n) {
	return $('#day' + n);
}

function getNotice() {
  	return $('#hinweis');
}

