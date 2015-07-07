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

