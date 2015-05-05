/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

var www,pubdir;

$(document).ready(function(){
  $('.aside-each').hover(function(){
      $(this).animate({"padding":"8px 5px 8px 15px"},100,function(){});
    },function(){
      $(this).animate({"padding":"7px 5px 7px 10px"},50,function(){});
  });
});

function check_all(cn){
  var checkbox = document.getElementsByClassName(cn);
  var i = checkbox.length;
  while(i--){
    checkbox[i].checked=true;
  }
}

var aside_button = document.getElementById('new_aside_button');
aside_button.onclick = aside_menu;

function aside_menu(){
  var aside = document.getElementById('aside');
  var tbside = document.getElementById('tbside');
  var display = aside.style.display;
  if(display==''||display=='none'){
    aside.style.display="block";
    tbside.style.display="block";
  }else{
    aside.style.display="none";
    tbside.style.display="none";
  }
}

function hide_aside(){
  var pc = document.getElementById('page_content');
  var ww = window.innerWidth;
  var aside = document.getElementById('aside');
  var tbside = document.getElementById('tbside');
  var display = aside.style.display;
  if(ww<=790){
    aside.style.display="none";
    tbside.style.display="none";
  }
};


window.onresize = function(){
  var ww = window.innerWidth;
  var aside = document.getElementById('aside');
  var tbside = document.getElementById('tbside');
  if(ww>790){
    aside.style.display="block";
    tbside.style.display="block";
  }else{
    aside.style.display="none";
    tbside.style.display="none";
  }
}


function open_theme_file(name,file){
  window.location.assign(www+'admin/edit-theme?name='+name+'&file='+file);
}

function open_plugin_file(name,file){
  window.location.assign(www+'admin/edit-plugin?name='+name+'&file='+file);
}


/* email validated */
function is_valid_email(email){
  var regExp = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/;
  if(email.match(regExp)){
    return true;
  }else{
    return false;
  }
}

function setCookie(cname,cvalue,exdays){
  var d = new Date();
  d.setTime(d.getTime()+(exdays*24*60*60*1000));
  var expires = "expires="+d.toGMTString();
  /* BlackBerry browser cannot suport document.cookie */
  document.cookie = cname+"="+cvalue+"; "+expires;
}

function getCookie(cname){
  var name = cname+"=";
  var ca = document.cookie.split(';');
  for(var i=0; i<ca.length; i++)  {
    var c = ca[i].trim();
    if (c.indexOf(name)==0) return c.substring(name.length,c.length);
  }
  return "";
}

function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}