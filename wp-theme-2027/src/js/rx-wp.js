function rxHydrate(root){
  if(!root) return;
  root.querySelectorAll('template[data-rx]').forEach(t=>{ const f=RXH[t.dataset.rx]; if(!f) return; try{ t.insertAdjacentHTML('beforebegin', f(t)); }catch(e){ console.warn('Rolmax', t.dataset.rx, e); } t.remove(); });
  root.querySelectorAll('[data-rxv="total"]').forEach(e=>e.textContent=total);
  root.querySelectorAll('[data-rxv="news"]').forEach(e=>e.textContent=NEWS_ITEMS().length);
  root.querySelectorAll('[data-count="total"]').forEach(e=>e.dataset.count=total);
}
function rxRestoreHome(app){
  window.__rxRestoring=1;
  app.innerHTML=window.RX_HOME||'';
  rxHydrate(app);
  if(window.elementorFrontend && elementorFrontend.elementsHandler && window.jQuery){
    jQuery(app).find('.elementor-element').each((i,el)=>{ try{ elementorFrontend.elementsHandler.runReadyTrigger(jQuery(el)); }catch(e){} });
  }
  window.__rxRestoring=0;
}
function rxBoot(){
  const app=$('#app'); window.RX_HOME=app.innerHTML; rxHydrate(app);
  const h=location.hash.slice(1);
  if(RXWP.front){ if(!h||h==='home'||document.getElementById(h)){ app.dataset.view='home'; mountHome(); animate(); } }
  else { app.dataset.view='wp'; mountHome(); animate(); }
}
/* Poza stroną główną linki z # (podstrony projektu) prowadzą na stronę główną z tą kotwicą. */
if(!RXWP.front) document.addEventListener('click',e=>{
  const a=e.target.closest('a[href^="#"]'); if(!a) return;
  const h=a.getAttribute('href');
  if(h==='#'||h==='#top'||a.matches('.hdr-drive,[data-drive],[data-sell]')||a.closest('.dr-modal,.lang-box,#navPanel .np-x')) return;
  if(document.getElementById(h.slice(1))&&h!=='#home') return;
  e.preventDefault(); e.stopImmediatePropagation(); location.href=RXWP.home+(h==='#home'?'':h);
},true);
/* Edytor Elementora: po każdej zmianie widżetu Rolmax odśwież kafelki i efekty. */
if(window.jQuery) jQuery(window).on('elementor/frontend/init',()=>{
  if(!window.elementorFrontend||!elementorFrontend.isEditMode||!elementorFrontend.isEditMode()) return;
  elementorFrontend.hooks.addAction('frontend/element_ready/global',$s=>{
    if(window.__rxRestoring) return; const el=$s&&$s[0]; if(!el||!/(^| )elementor-widget-rx_/.test(el.className)) return;
    rxHydrate(el); clearTimeout(window.__rxm); window.__rxm=setTimeout(()=>{ try{ mountHome(); animate(); }catch(e){ console.warn(e); } },150);
  });
});
