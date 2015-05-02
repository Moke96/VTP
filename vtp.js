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

$( document ).on( "pageinit", "#pageone", function() {
    $( document ).on( "swipeleft swiperight", "#pageone", function( e ) {
        // We check if there is no open panel on the page because otherwise
        // a swipe to close the left panel would also open the right panel (and v.v.).
        // We do this by checking the data that the framework stores on the page element (panel: open).
        if ( $.mobile.activePage.jqmData( "panel" ) !== "open" ) {
           if ( e.type === "swiperight" ) {
                $( "#search" ).panel( "open" );
            }
        }
    });
});

$.getJSON('http://anyorigin.com/dev/get?url=http%3A//intern.willms-gymnasium.de/vtp/json.php&callback=?', function(data){
    var table_obj = $('table');
    $.each(data, function(index, item){
         var table_row = $('<tr>', {id: item.id});
         var table_cell = $('<td>', {html: item.data});
         table_row.append(table_cell);
         table_obj.append(table_row);
    });
});
