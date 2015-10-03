postlinks = [];

function get1stDegree(){
  console.log('1st degree');
  groupurl = $('#groupurl');
  console.log(groupurl.val());
  $.get( groupurl.val(), function( data ) {
    links = $(data).find("a[href*='twitter.com']");
    $.each( links, function( key, value ) {
      $('#twitterhandles').val($('#twitterhandles').val()+ value + '\n');
      console.log(value.href );
      postlinks.push(value.href);
    });
  });
}

function get2ndDegree(){
  console.log('postlinks' + postlinks);
  console.log('2nd degree');
  url2 = 'process.php';
  // Send the data using post
  var posting = $.post( url2, { s: postlinks } );

  // Put the results in a div
  posting.done(function( data ) {
    console.log(data);
    var content = $( data ).find( "#content" );
    $( "#result" ).empty().append( content );
  });

}
