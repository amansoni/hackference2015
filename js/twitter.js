postlinks = [];
influence2 = [];

//base = {'twitter': 'amansoni',
base = {
    'domain' : 'name',
    'ip' : '127.0..0.1',
    'rank' : '5',
    children: []
};

function get1stDegree(){
  console.log('1st degree');
  groupurl = $('#groupurl');
  console.log(groupurl.val());
  $.get( groupurl.val(), function( data ) {
    links = $(data).find("a[href*='twitter.com']");
    $.each( links, function( key, value ) {
      $('#twitterhandles').val($('#twitterhandles').val()+ value + '\n');
      console.log(value.href );
      postlinks.push(key = value.href);
    });
  });
}

function get2ndDegree(){
  console.log('2nd degree');
  console.log('postlinks for majestic');
  console.log(postlinks);

  $.each(postlinks, function(key, value) {
    var majestic = 'http://178.62.11.44/hackference2015/process.php?url=' + value;
    console.log(majestic );
    count = 0;
    childrent = [];
    $.get( majestic, function( data ) {
      console.log(data);
      //links = $(data.DataTables.Results.Data).find("a[href*='twitter.com']");
      $.each( data.DataTables.Results.Data, function( key, value ) {
        childrent.push({'domain' :value.Domain, 'IP' : value.IP, 'rank' : value.AlexaRank});
        $('#weblinks').val($('#weblinks').val()+ value.Domain + '\n');
        //influence2.push(key + )
        count++;
      });
      var children = {'children' : childrent};
      $.extend( value, children );
//      console.log(childrent);
  //    console.log(value.children);
  //    console.log(count);

    });
  });

  function addToList(url, source){
      i = $.(influence2).find(url);
      console.console.log(i);
  }

}
