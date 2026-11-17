(function(){
  var form=document.querySelector('form.enquiry');
  var modal=document.getElementById('fw-sent');
  if(!form||!modal) return;
  var err=form.querySelector('.form-error');
  var btn=form.querySelector('button[type=submit]');
  var last=null;

  function openModal(){
    last=document.activeElement;
    modal.hidden=false;
    requestAnimationFrame(function(){modal.classList.add('show');});
    var c=modal.querySelector('[data-close]'); if(c) c.focus();
    document.addEventListener('keydown',esc);
  }
  function closeModal(){
    modal.classList.remove('show');
    setTimeout(function(){modal.hidden=true;},250);
    document.removeEventListener('keydown',esc);
    if(last&&last.focus) last.focus();
  }
  function esc(e){ if(e.key==='Escape') closeModal(); }
  modal.addEventListener('click',function(e){ if(e.target===modal||e.target.hasAttribute('data-close')) closeModal(); });

  function showError(msg){ err.textContent=msg; err.hidden=false; }

  form.addEventListener('submit',function(e){
    e.preventDefault();
    err.hidden=true;
    form.querySelectorAll('.invalid').forEach(function(el){el.classList.remove('invalid');});
    if(!form.checkValidity()){
      var bad=form.querySelectorAll(':invalid');
      bad.forEach(function(el){el.classList.add('invalid');});
      if(bad[0]) bad[0].focus();
      showError('Please fill in the highlighted fields.');
      return;
    }
    btn.disabled=true; var label=btn.textContent; btn.textContent='Sending…';
    fetch(form.dataset.ajax,{method:'POST',body:new FormData(form),credentials:'same-origin'})
      .then(function(r){return r.json().catch(function(){return {success:false};});})
      .then(function(res){
        if(res&&res.success){ form.reset(); openModal(); }
        else showError((res&&res.data&&res.data.message)||'Sorry, something went wrong. Please try again.');
      })
      .catch(function(){ showError('Sorry, we couldn’t send that. Please check your connection and try again.'); })
      .then(function(){ btn.disabled=false; btn.textContent=label; });
  });

  var q=new URLSearchParams(location.search).get('enquiry');
  if(q==='sent') openModal();
  else if(q==='error') showError('Sorry, something went wrong. Please check your details and try again.');
})();
