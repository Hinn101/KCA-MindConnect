function openSidebar(){ document.getElementById('sidebar').style.width='280px'; document.getElementById('backdrop').style.display='block'; }
function closeSidebar(){ document.getElementById('sidebar').style.width='0'; document.getElementById('backdrop').style.display='none'; }
document.addEventListener('click', function(e){ if(e.target.tagName==='BUTTON'){ let btn=e.target; let r=document.createElement('span'); r.className='ripple'; r.style.left=(e.clientX-btn.getBoundingClientRect().left)+'px'; r.style.top=(e.clientY-btn.getBoundingClientRect().top)+'px'; btn.appendChild(r); setTimeout(()=>r.remove(),600); }});
function animateAvatar(){ let a=document.querySelector('.avatar'); if(!a) return; a.classList.add('bounce'); setTimeout(()=>{ a.classList.remove('bounce'); a.classList.add('wave'); setTimeout(()=>a.classList.remove('wave'),2000); },2000); }
setInterval(animateAvatar,8000);
function toggleDarkMode(){ document.body.classList.toggle('dark'); if(document.body.classList.contains('dark')) localStorage.setItem('darkMode','enabled'); else localStorage.setItem('darkMode','disabled'); }
window.addEventListener('load', ()=>{ if(localStorage.getItem('darkMode')==='enabled') document.body.classList.add('dark'); });
function fadeInMessages(){ document.querySelectorAll('.chatbox p').forEach((p,i)=>{ p.style.opacity=0; setTimeout(()=>{ p.style.transition='opacity 0.6s'; p.style.opacity=1; }, i*80); }); }
window.addEventListener('load', fadeInMessages);