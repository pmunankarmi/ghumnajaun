(() => {
 const html=document.documentElement, toggle=document.querySelector('#gj-theme-toggle');
 try {const saved=localStorage.getItem('ghumna-jaun-theme');if(['dark','light'].includes(saved))html.dataset.theme=saved;}catch{}
 if(toggle){toggle.hidden=false;toggle.addEventListener('click',()=>{const current=html.dataset.theme||(matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');html.dataset.theme=current==='dark'?'light':'dark';try{localStorage.setItem('ghumna-jaun-theme',html.dataset.theme)}catch{} toggle.setAttribute('aria-label','Switch to '+(html.dataset.theme==='dark'?'light':'dark')+' mode');});}
 const dialog=document.querySelector('#gj-search-dialog'),open=document.querySelector('#gj-search-toggle'),close=document.querySelector('#gj-search-close');
 if(dialog&&open){open.hidden=false;open.addEventListener('click',()=>dialog.showModal());close?.addEventListener('click',()=>dialog.close());}
 const form=document.querySelector('#gj-contact-form');
 if(form)form.addEventListener('submit',async e=>{e.preventDefault();const status=document.querySelector('#gj-contact-status');const button=form.querySelector('[type=submit]');button.disabled=true;try{const r=await fetch(form.action,{method:'POST',body:new FormData(form)});const data=await r.json();status.textContent=data.data?.message||'Please try again.';if(r.ok&&data.success)form.reset();}catch{status.textContent='Unable to send. Your message is still here; please try again.';}finally{button.disabled=false;}});
})();
