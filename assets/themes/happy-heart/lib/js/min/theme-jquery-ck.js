function strripos(r,s,t){r=(r+"").toLowerCase(),s=(s+"").toLowerCase();var a=-1;return t?(a=(r+"").slice(t).lastIndexOf(s),-1!==a&&(a+=t)):a=(r+"").lastIndexOf(s),a>=0?a:!1}jQuery(document).ready(function(r){r("*:first-child").addClass("first-child"),r("*:last-child").addClass("last-child"),r("*:nth-child(even)").addClass("even"),r("*:nth-child(odd)").addClass("odd");var s=r("#footer-widgets div.widget").length;r("#footer-widgets").addClass("cols-"+s),r(".ftr-menu ul.menu>li").after(function(){return!r(this).hasClass("last-child")&&r(this).hasClass("menu-item")&&"none"!==r(this).css("display")?'<li class="separator">|</li>':void 0});var t=new Array("aubrey.adv","aubreyrose.msdlab2.com","aubreyrose.org","www.aubreyrose.org");r("a").attr("target",function(){var s=r(this).attr("href"),a=r(this).attr("target");if("#"===s||strripos(s,"http",0)===!1)return"_self";for(var e=0;t[e];){if(strripos(s,t[e],0))return a;e++}return"_blank"})});