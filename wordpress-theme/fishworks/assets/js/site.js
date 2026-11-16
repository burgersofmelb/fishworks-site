(function(){
  document.querySelectorAll('.yr').forEach(function(e){e.textContent=new Date().getFullYear();});
  var h=document.querySelector('header.site-header');
  if(h){
    var home=document.body.classList.contains('home');
    var onScroll=function(){h.classList.toggle('scrolled',scrollY>(home?innerHeight*.55:10));};
    addEventListener('scroll',onScroll,{passive:true});onScroll();
    var t=h.querySelector('.menu-toggle');
    if(t){t.addEventListener('click',function(){var o=h.classList.toggle('open');t.setAttribute('aria-expanded',o);});
      h.querySelectorAll('.nav-list a').forEach(function(a){a.addEventListener('click',function(){h.classList.remove('open');});});}
  }
  if('IntersectionObserver' in window){
    var io=new IntersectionObserver(function(es){es.forEach(function(e){if(e.isIntersecting){e.target.classList.add('in');io.unobserve(e.target);}});},{rootMargin:'0px 0px -8% 0px'});
    document.querySelectorAll('.reveal').forEach(function(el){io.observe(el);});
  } else document.querySelectorAll('.reveal').forEach(function(el){el.classList.add('in');});
})();
