postlinks = [];
webcount = 0;
domains = [];
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
      //console.log(value.href );
      postlinks.push(key = value.href);
    });
  });
}

function get2ndDegree(){
  console.log('2nd degree');

  $.each(postlinks, function(key, value) {
    var majestic = 'http://178.62.11.44/hackference2015/process.php?url=' + value;
    //console.log(majestic );
    $.get( majestic, function( data ) {
      //console.log(data);
      //links = $(data.DataTables.Results.Data).find("a[href*='twitter.com']");
      childrent = [];
      $.each( data.DataTables.Results.Data, function( key, value ) {
        childrent.push(addToList(value.Domain, value.IP, value.AlexaRank));
      });
      var children = {'children' : childrent};
      //console.log(children);
      $.extend( value, children );
    });
  });
}

function get3rdDegree(){
  console.log('3rd degree');
  
  $.each(domains, function(key, value) {
    var majestic = 'http://178.62.11.44/hackference2015/process.php?url=' + value;
    //console.log(majestic );
    $.get( majestic, function( data ) {
      //console.log(data);
      //links = $(data.DataTables.Results.Data).find("a[href*='twitter.com']");
      childrent = [];
      $.each( data.DataTables.Results.Data, function( key, value ) {
        childrent.push(addToList(value.Domain, value.IP, value.AlexaRank));
      });
      var children = {'children' : childrent};
      //console.log(children);
      $.extend( value, children );
    });
  });
}

function addToList(domain, ip, rank){
  //console.log('addToList ' + domain +' '+ip+' '+rank);
  i = $.inArray(domain, domains);
  if (i == -1){
    $('#weblinks').val($('#weblinks').val()+ domain + '\n');
    domains.push(domain);
    i = $.inArray(domain, domains);
    influence2.push({'domain' : i, 'IP' : ip, 'rank' : rank});
    //influence2.push()
  }
  return i;
}

function sortDomains(){
  tdomains = $.merge([],domains);
  tdomains.sort();
  $('#weblinks').val('');
  $.each(tdomains, function(key, value) {
    $('#weblinks').val($('#weblinks').val()+ value + '\n');
  });
}
