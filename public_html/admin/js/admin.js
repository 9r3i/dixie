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