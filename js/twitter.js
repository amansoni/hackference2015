function get1stDegree(){
  console.log('get1stDegree');
  groupurl = $('#groupurl');
  console.log(groupurl.val());
  $.get( groupurl.val(), function( data ) {
    links = $(data).find("a[href*='twitter.com']");
    $.each( links, function( key, value ) {
      $('#twitterhandles').val($('#twitterhandles').val()+ value + '\n');
      //console.log( key + ": " + value );
    });
  });
}
