/* Dixie - Free and Simple CMS
 * Created by Luthfie a.k.a. 9r3i
 * luthfie@y7mail.com
 */

var langs,www;

function change_locale(el,langs,www){
  var r = el.getAttribute('count');
  if(r<=0){
    el.innerHTML = '';
    var div = document.createElement('div');
    div.setAttribute('id','locale_list');
    var ul = document.createElement('ul');
    for(key in langs){
      var li = document.createElement('li');
      var string = langs[key];
      var letter = string.charAt(0).toUpperCase()+string.slice(1);
      li.innerHTML = '<a href="'+www+'admin/a?data=change-locale&locale='+string+'" title="'+letter+'"><div>'+letter+'</div></a>';
      ul.appendChild(li);
    }
    div.appendChild(ul);
    el.setAttribute('count','1');
    el.appendChild(div);
  }
}