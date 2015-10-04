postlinks = [];
webcount = 0;
domains = [];
domains3 = [];
influence2 = [];

mytwitter = "caleuanhopkins,dan_jenkins,etiene_d,hughrawlinson,jna_sh,jr0cket,JFKingsley,man0jn,thebeebs,martinkearn,mseckington,picsoung,leggetter,dn0t,rbin,rumyra,samphippen,simon_tabor,SeraAndroid,pimterry,andypiper,hazanjon,amansoni,pseudoh,jna_sh,J0shSimpson,mariaVrb01,cbetta,sawiczpawel,houmanB,JamesLMilner,RobertHDavies,andy_c_jones,siddvee,lyndsaywalsh,NRavenhill,gkudelis,jesslynnrose,skinofstars,SteveJPitchford";
handleArray = mytwitter.split(',');
handleArray.sort();
console.log(handleArray);
leaders = [];

//base = {'twitter': 'amansoni',
base = {
  'domain' : 'name',
  'ip' : '127.0..0.1',
  'rank' : '5',
  children: []
};

function get1stDegree(){
  //console.log('1st degree');
  groupurl = $('#groupurl');
  //console.log(groupurl.val());
  $('#twitterhandles').val('');
  console.log(handleArray);
  $.each( handleArray, function( key, value ) {
    $('#twitterhandles').val($('#twitterhandles').val()+ value + '\n');
    //console.log(value.href );
    postlinks.push(key = 'https://twitter.com/' + value);
  });
  console.log(postlinks);
  /*
  $.get( groupurl.val(), function( data ) {
    links = $(data).find("a[href*='twitter.com']");
    $.each( links, function( key, value ) {
      $('#twitterhandles').val($('#twitterhandles').val()+ value + '\n');
      //console.log(value.href );
      postlinks.push(key = 'https://twitter.com/' + value.href);
    });
  });*/
  countTwitterLines();
}

function get2ndDegree(){
  console.log('2nd degree');

  $.each(postlinks, function(key, valueo) {
    var majestic = 'http://178.62.11.44/hackference2015/process.php?url=' + valueo;
    //console.log(majestic );
    $.get( majestic, function( data ) {
      score = 0;
      //links = $(data.DataTables.Results.Data).find("a[href*='twitter.com']");
      childrent = [];
      $.each( data.DataTables.Results.Data, function( key, value ) {
        console.log((value.RefDomains  / value.AlexaRank));
        tscore = Math.abs(value.RefDomains  / value.AlexaRank);
        score += tscore;
        childrent.push(addToList(value.Domain, value.IP, value.AlexaRank));
      });
      var children = {'children' : childrent};
      //console.log(children);
      //$.extend( value0, children );
      //console.log(valueo);
      key1 = valueo.replace('https://twitter.com/', '');
      //console.log(key1);
      //console.log(handleArray.indexOf(key1));
      leaders.push({'score' : score , 'value':valueo});
    });
  });
  leaders = leaders.sort(function(a, b){
    if (a.score <= b.score){
      return -1;
    } else {
      return 1;
    }
  });
  console.log(leaders);
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
        childrent.push(addToList2(value.Domain, value.IP, value.AlexaRank));
      });
      var children = {'children' : childrent};
      //console.log(children);
      $.extend( value, children );
    });
    //leaders.push();
  });
}

function addToList2(domain, ip, rank){
  //console.log('addToList ' + domain +' '+ip+' '+rank);
  i = $.inArray(domain, domains3);
  if (i == -1){
    $('#weblinks').val($('#weblinks').val() + domain + '\n');
    domains3.push(domain);
    i = $.inArray(domain, domains3);
    influence2.push({'domain' : i, 'IP' : ip, 'rank' : rank});
    //influence2.push()
  }
  return i;
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

function countTwitterLines(){
  var text = $("#twitterhandles").val();
  var lines = text.split(/\n/);
  $("#countTwitter").html(lines.length);
  text = $("#weblinks").val();
  lines = text.split(/\n/);
  $("#countSites").html(lines.length);
}

function loadHandles(){
  $('#leader').val('');
  $.each(leaders, function(key, value) {
    $('#leader').val($('#leader').val()+ 'Position: ' + value.score + ' ' + value.value + '\n');
  });
}
