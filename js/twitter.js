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
  console.log('2nd degree');
  console.log('postlinks for majestic');
  console.log(postlinks);

  $.each( postlinks, function( key, value ) {
    var majestic = 'http://178.62.11.44/hackference2015/process.php?url=' + value;
    console.log(majestic );
    $.get( majestic, function( data ) {
      console.log(data);
      links = $(data.Domain).find("a[href*='twitter.com']");
      console.log(links);

    });
  });

}
