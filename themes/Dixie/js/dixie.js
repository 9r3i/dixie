/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */


$('#menu_button').click(function(){
  var data = $('#nav').attr('data');
  var height = $('#nav').height();
  if(data=='visible'){
    $('#nav').hide(300);
    $('#nav').attr({"data":"hidden"});
  }else{
    $('#nav').show(300);
    $('#nav').attr({"data":"visible"});
  }
});
$('a').click(function(key){
  key.preventDefault();
  var ww = $(window).width();
  var gw = (ww/2)-46;
  var wh = $(window).height();
  var gh = (wh/2)-46;
  $('#loading').attr({"style":"left:"+gw+"px;top:"+gh+"px"});
  $('#loading').show();
  var href = $(this).attr('href');
  $('header').animate({"opacity":"0"},700,function(){});
  $('footer').animate({"opacity":"0"},700,function(){});
  $('aside').animate({"opacity":"0"},700,function(){});
  $('nav').animate({"opacity":"0"},500,function(){});
  $('.tubuh').animate(
    {"opacity":"0"},500,function(){
      location.assign(href);
  });
});
$('#sidebar_button').click(function(){
  var data = $('#sidebar').attr('data');
  if(data=='hidden'){
    $(this).animate({"left":"250px"},600,function(){});
    $('#sidebar').animate({"left":"0px"},500,function(){});
    $('#sidebar').attr({"data":"visible"});
  }else{
    $(this).animate({"left":"0px"},400,function(){});
    $('#sidebar').animate({"left":"-270px"},500,function(){});
    $('#sidebar').attr({"data":"hidden"});
  }
});
$(document).ready(function(){
  var height = $('#nav').height();
  $('#sidebar').attr({"data":"hidden"});
});
$(document).ready(function(){
  $('.menu-list-sidebar').hover(function(){
      $(this).animate({"width":"240px","padding":"11px 11px 11px 20px"},200,function(){});
    },function(){
      $(this).animate({"width":"230px","padding":"10px"},200,function(){});
  });
});
