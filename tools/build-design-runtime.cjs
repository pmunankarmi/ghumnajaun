const fs=require('fs');fs.copyFileSync('design/styles.css','wordpress/ghumnajaun/assets/styles.css');
fs.mkdirSync('wordpress/ghumnajaun/views',{recursive:true});
const homeSource=fs.readFileSync('design/index.html','utf8');fs.writeFileSync('wordpress/ghumnajaun/views/social-icons.html',homeSource.match(/<ul class="social-icons"[\s\S]*?<\/ul>/)[0]);
for(const name of ['index','destination','explore','blog','post','about','contact','privacy-policy','terms-of-use','disclaimer','photo-credits']){const html=fs.readFileSync('design/'+name+'.html','utf8');fs.writeFileSync('wordpress/ghumnajaun/views/'+name+'.html',html.slice(html.indexOf('<main>')+6,html.indexOf('</main>')));}
const explore=fs.readFileSync('design/explore.html','utf8');fs.writeFileSync('wordpress/ghumnajaun/views/explore-dialogs.html',explore.match(/<dialog class="search-dialog start-dialog"[\s\S]*?<\/dialog>/)[0]);
const destination=fs.readFileSync('design/destination.html','utf8');fs.writeFileSync('wordpress/ghumnajaun/views/dialogs.html',destination.slice(destination.indexOf('<dialog class="search-dialog stop-photo-dialog"'),destination.indexOf('<main>')));
const contact=fs.readFileSync('design/contact.html','utf8');fs.writeFileSync('wordpress/ghumnajaun/views/contact-form.html',contact.match(/<form class="contact-form"[\s\S]*?<\/form>/)[0]);
let s=fs.readFileSync('design/prototype.js','utf8');
const maps=[['photos','const picnicCategory'],['places','const dayPlans'],['dayPlans','const origins'],['placePoints','function distanceFrom'],['citySightseeing','const cityPlaces'],['destinationGuides','const find ='],['destinationGalleries','const photoFor'],['tripDetails','function standardTripDetails'],['blogPosts','function renderBlogCards']];
for(const[name,end]of maps){let a=s.indexOf('const '+name+' ='),b=s.indexOf(end,a);s=s.slice(0,a)+`const ${name} = window.GJ.${name};\n`+s.slice(b);}
s=s.replace("const query = new URLSearchParams(window.location.search);", "const query = new URLSearchParams(window.location.search); if(window.GJ.currentPlace)query.set('place',window.GJ.currentPlace);if(window.GJ.currentPost)query.set('slug',window.GJ.currentPost);if(window.GJ.category)query.set('category',window.GJ.category);if(window.GJ.city)query.set('city',window.GJ.city);");
s=s.replace(/function pageUrl\(path,params = \{\}\)\{[\s\S]*?\n\}/,`function pageUrl(path,params = {}){
 let target=window.GJ.pages[path] || path;
 if(path==='explore.html'){
  const kind=params.city?'city':'category', key=params[kind];
  if(key && window.GJ.collectionUrls?.[kind]?.[key]){target=window.GJ.collectionUrls[kind][key];params={...params};delete params[kind];}
 }
 if(path==='destination.html' && window.GJ.urls[params.place]){target=window.GJ.urls[params.place];params={...params};delete params.place;}
 if(path==='post.html' && window.GJ.postUrls[params.slug]){target=window.GJ.postUrls[params.slug];params={...params};delete params.slug;}
 const search=new URLSearchParams();if(from()!=='Kathmandu Valley')search.set('from',from());Object.entries(params).forEach(([key,value])=>{if(value)search.set(key,value)});return target+(search.size?'?'+search:'');
}`);
s=s.replace("const photoFor = (name,dining) => dining ? photos.Dining : photos[destinationGalleries[name]?.[0] || name];","const photoFor = (name,dining) => dining ? photos.Dining : photos[name];");
s=s.replace(/const thumbnail = \(photo,width = 960\) => \{[\s\S]*?\n\};/, 'const thumbnail = (photo,width = 960) => photo?.src || "";');
s=s.replace("const isGarden = name === 'Godawari Botanical Garden';",'const isGarden = false;');
s=s.replace(/   details\.facts\.forEach\([\s\S]*?\n   \}\);/,`   slot('facts').innerHTML=details.facts.map(([label,value,note])=>'<div><dt>'+label+'</dt><dd>'+value+'</dd><dd>'+note+'</dd></div>').join('')+'<div><dt data-trip-origin></dt><dd data-trip-distance></dd><dd>Straight-line distance</dd></div>';`);
s=s.replace("const featured = ['Nagarkot','Panauti','Chitlang','Chandragiri Hills','Godawari Botanical Garden','Bhaktapur Durbar Square'];",'const featured = window.GJ.featured;');
s=s.replace('featured.map(name => places.find(entry => entry[0] === name))','featured.map(name => places.find(entry => entry[0] === name)).filter(Boolean)');
s=s.replace('familyTrips.map(name => places.find(entry => entry[0] === name))','familyTrips.map(name => places.find(entry => entry[0] === name)).filter(Boolean)');
s=s.replace("const bannerPlaces = ['Nagarkot','Chandragiri Hills','Panauti','Kulekhani','Bheda Farm'];",'const bannerPlaces = window.GJ.bannerPlaces;');
let a=s.indexOf('const bannerPhotos = {'),b=s.indexOf("if (view === 'home') find('banner-slides')",a);s=s.slice(0,a)+'const bannerPhotos = window.GJ.bannerPhotos || {};\n'+s.slice(b);
s=s.replace("const result = await response.json();","const wpResult = await response.json(); const result = {ok:wpResult.success===true,message:wpResult.data?.message||'Unable to send. Please try again.'};");
s=s.replace("if (view === 'home') {\n renderBlogCards", "if (view === 'home') {\n renderBlogCards");
s=s.replace(" find('post-body').innerHTML = post.sections.map", " find('post-body').innerHTML = post.html || post.sections.map");
s=s.replaceAll('href="privacy-policy.html"','href="${window.GJ.pages[\'privacy-policy.html\']}"');
s=s.replace("   trip.querySelector('#gj-vlog-title').remove();","   const vlog=trip.querySelector('#gj-vlog-title');if(details.videoUrl){vlog.querySelector('iframe').dataset.consentSrc=details.videoUrl;vlog.querySelector('iframe').title=name+' vlog on YouTube';vlog.setAttribute('aria-label',name+' vlog');}else vlog.remove();");
s=s.replace("   slot('visit').innerHTML = details.visit.map","   slot('visit').innerHTML = details.visitHtml || details.visit.map");
s=s.replace("   slot('meal-note').remove();","   if(details.mealNote)slot('meal-note').textContent=details.mealNote;else slot('meal-note').remove();");
s=s.replace("   if (details.photos[1]) {\n     feature.querySelector('img').dataset.tripPhoto = details.photos[1];\n     feature.querySelector('figcaption').textContent = photos[details.photos[1]].alt;","   const featureKey=details.featurePhoto || details.photos[1];if (featureKey) {\n     feature.querySelector('img').dataset.tripPhoto = featureKey;\n     feature.querySelector('figcaption').textContent = details.featureCaption || photos[featureKey].alt;");
let mealsStart=s.indexOf("   slot('meals').innerHTML ="),mealsEnd=s.indexOf("   if (!details.meals)",mealsStart);
s=s.slice(0,mealsStart)+`   slot('meals').innerHTML = (details.meals || []).map(([stage,title,note,url,label],index)=>'<article class="trip-card"><p class="eyebrow">'+stage.toUpperCase()+'</p><h3>'+title+'</h3><p>'+note+'</p>'+((details.mealLinks?.[index] || (url?[[label,url]]:[])).length?'<div class="trip-text-links">'+(details.mealLinks?.[index] || [[label,url]]).map(([text,href])=>externalLink(href,text)).join('')+'</div>':'')+'</article>').join('');\n`+s.slice(mealsEnd);
s=s.replace("details.transport.map(([title,note]) =>","details.transport.map(([title,note],index) =>");
s=s.replace("<div><h3>${title}</h3><p>${note}</p></div>","<div><h3>${title}</h3>${details.transportHtml?.[index] || `<p>${note}</p>`}${(details.transportLinks?.[index] || []).map(([label,url])=>externalLink(url,label,'')).join('')}</div>");
s=s.replace("details.stays.map(([label,location,url]) =>","details.stays.map(([label,location,url,linkLabel,plain]) =>");
s=s.replace("externalLink(url,'Hotel website')","externalLink(url,linkLabel||'Hotel website',plain?'':'page-link')");
s=s.replace("details ? 'Find nearby restaurants' : 'More restaurants near the garden'","details.originalGardenLayout?'More restaurants near the garden':'Find nearby restaurants'");
s=s.replace("details ? 'Find nearby stays' : 'More stays near the garden'","details.originalGardenLayout?'More stays near the garden':'Find nearby stays'");
s=s.replace(" const walker = document.createTreeWalker(container,NodeFilter.SHOW_TEXT);"," container.querySelectorAll('.link-arrow:empty').forEach(arrow=>arrow.innerHTML=icon);\n const walker = document.createTreeWalker(container,NodeFilter.SHOW_TEXT);");
s=s.replace("const entries = (city ? cityPlaces(city) : places).filter","const archiveEntries=window.GJ.archivePlaces?places.filter(entry=>window.GJ.archivePlaces.includes(entry[0])):places;\n const entries = (city ? cityPlaces(city) : archiveEntries).filter");
s=s.replace(" find('results-title').textContent = title;"," if(window.GJ.archiveTitle)title=window.GJ.archiveTitle;\n find('results-title').textContent = title;");
s=s.replace("let title = 'Explore nearby';","let title = window.GJ.resultsTitle || 'Explore nearby';");
// Keep SEO titles controlled by WordPress rather than overwriting them after load.
s=s.replace(/ document\.title = [^;]+;/g,'');
s=s.replace('[...Object.values(photos),...Object.values(bannerPhotos)].map(photo => {','[...new Map([...Object.values(photos),...Object.values(bannerPhotos)].filter(photo=>photo.author && (photo.commons||photo.source)).map(photo=>[photo.src,photo])).values()].map(photo => {');
// Search both original routing names and translated labels on Nepali pages.
s=s.replace('function setPhoto(image,photo,width){','function setPhoto(image,photo,width){\n if(!image || !photo)return;');
s=s.replace("[name,type,tags,...cities].join(' ')","[name,type,tags,...cities,...(window.GJ.searchAliases?.[name]||[]),...[name,type,...tags.split('|'),...cities].map(label=>window.GJ_NE?.[label]||label)].join(' ')");
fs.copyFileSync('tools/data/ne-translations.json','wordpress/ghumnajaun/data/ne-translations.json');
fs.copyFileSync('tools/data/ne-editorial-paragraphs.json','wordpress/ghumnajaun/data/ne-editorial-paragraphs.json');
// Responsive image markup is used by cards and any later filter/search redraw.
s=s.replace('const cards = (entries,distances)',`const responsiveAttrs = (photo,sizes='100vw') => {
 if(!photo)return '';
 const escape=value=>String(value).replaceAll('&','&amp;').replaceAll('"','&quot;');
 return (photo.width ? ' width="'+photo.width+'" height="'+photo.height+'"' : '')+(photo.srcset ? ' srcset="'+escape(photo.srcset)+'" sizes="'+escape(sizes)+'"' : '');
};
const cards = (entries,distances)`);
s=s.replaceAll('data-original="${photo.src}" alt=', 'data-original="${photo.src}"${responsiveAttrs(photo,\'(max-width:575px) calc(100vw - 32px), (max-width:991px) calc(50vw - 32px), 360px\')} alt=');
s=s.replace(' image.alt = photo.alt;',` image.alt = photo.alt;
 if(photo.width && photo.height){image.width=photo.width;image.height=photo.height;}
 if(photo.srcset){image.srcset=photo.srcset;image.sizes=width ? width+'px' : '100vw';}`);
// Keep the server-rendered LCP image in place while enhancing the rest of a guide.
s=s.replace("   slot('slides').innerHTML = details.photos.map", "   if(root.querySelector('[data-screen=\"detail\"] .trip-hero'))slot('slides').replaceChildren();\n   else slot('slides').innerHTML = details.photos.map");
s=s.replace(' screen.replaceChildren(trip);',` const savedHero=screen.querySelector('.trip-hero');
 const newHero=trip.querySelector('.trip-hero');
 if(savedHero && newHero){
  for(const selector of ['[data-trip-slider]','.trip-photo-dots']){
   const original=savedHero.querySelector(selector),enhanced=newHero.querySelector(selector);
   if(original && enhanced)original.setAttribute('aria-label',enhanced.getAttribute('aria-label'));
  }
  newHero.remove();
  for(const child of [...screen.childNodes])if(child!==savedHero)child.remove();
  screen.append(trip);
 }else screen.replaceChildren(trip);`);
// Homepage heroes also keep their original responsive image instead of replacing it.
s=s.replace("if (view === 'home') find('banner-slides').innerHTML =", "if (view === 'home' && !find('banner-slides').querySelector('img')) find('banner-slides').innerHTML =");
s=s.replace("const motion = window.matchMedia('(prefers-reduced-motion: reduce)');", `function loadBannerPhoto(index){
 const image=find('banner-slides')?.children[index]?.querySelector('img');
 if(image && !image.getAttribute('src'))setPhoto(image,bannerPhotos[bannerPlaces[index]] || photos[bannerPlaces[index]]);
}
const motion = window.matchMedia('(prefers-reduced-motion: reduce)');`);
s=s.replace('   slideChange(slider) {', '   slideChange(slider) {\n     loadBannerPhoto(slider.realIndex);\n     setTimeout(()=>loadBannerPhoto((slider.realIndex+1)%bannerPlaces.length),1000);');
s=s.replace('if (banner && !motion.matches) bannerTimer.hidden = false;', "if (banner && !motion.matches) bannerTimer.hidden = false;\nif(banner)setTimeout(()=>loadBannerPhoto(1),3000);");

// Preserve translated homepage category markup and avoid fetching inactive guide photos.
s=s.replace(" find('home-chips').innerHTML = chips(categories);", " if(!find('home-chips').children.length)find('home-chips').innerHTML = chips(categories);");
s=s.replace("   const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;", `   function loadTripPhoto(index){
     const image=slider.querySelector('.swiper-wrapper')?.children[index]?.querySelector('img');
     if(image && !image.getAttribute('src'))setPhoto(image,photos[image.dataset.tripPhoto]);
   }
   setTimeout(()=>loadTripPhoto(1),3000);
   const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;`);
s=s.replace('       slideChange() {', '       slideChange() {\n         loadTripPhoto(this.realIndex);\n         setTimeout(()=>loadTripPhoto((this.realIndex+1)%slider.querySelectorAll(\'.swiper-slide\').length),1000);');
s=s.replace('const thumbnail = (photo,width = 960) => photo?.src || "";', 'const thumbnail = (photo,width = 960) => (width<=400 ? photo?.thumbnail : photo?.src) || photo?.src || "";');

s=s.replace("find('post-category').textContent = post.category;", "find('post-category').innerHTML = post.categoryHtml || post.category;");
fs.writeFileSync('wordpress/ghumnajaun/assets/design.js',s);

// Cache the Nepali UI dictionary as a versioned asset instead of repeating it in HTML.
fs.writeFileSync('wordpress/ghumnajaun/assets/ne-dictionary.js','window.GJ_NE='+JSON.stringify(JSON.parse(fs.readFileSync('tools/data/ne-translations.json','utf8')))+';\n');

// One local stylesheet removes chained CDN and Google Fonts render blockers.
const cssFiles=['fonts.css','vendor/bootstrap-grid.min.css','vendor/swiper-bundle.min.css','vendor/aos.css','styles.css','wordpress.css','nepali.css'];
fs.writeFileSync('wordpress/ghumnajaun/assets/site.css',cssFiles.map(file=>fs.readFileSync('wordpress/ghumnajaun/assets/'+file,'utf8').replace(/@import\s+url\([^)]*\)\s*;/g,'')).join('\n'));
