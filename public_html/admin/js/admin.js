/* Black Apple Inc.
 * http://black-apple.biz/
 * Dixie CMS
 * Created by Luthfie
 * luthfie@y7mail.com
 */

$(document).ready(function(){
  $('.aside-each').hover(function(){
      $(this).animate({"padding":"10px 5px 10px 40px"},100,function(){});
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

var aside_button = document.getElementById('aside_button');
aside_button.onclick = aside_menu;

function aside_menu(){
  var aside = document.getElementById('aside');
  var display = aside.style.display;
  if(display==''||display=='none'){
    aside.style.display="block";
  }else{
    aside.style.display="none";
  }
}

window.onresize = function(){
  var ww = window.innerWidth;
  var aside = document.getElementById('aside');
  if(ww>790){
    aside.style.display="block";
  }else{
    aside.style.display="none";
  }
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