const fs=require('fs');fs.copyFileSync('design/styles.css','wordpress/ghumnajaun/assets/styles.css');
fs.mkdirSync('wordpress/ghumnajaun/views',{recursive:true});
for(const name of ['index','destination','explore','blog','post']){const html=fs.readFileSync('design/'+name+'.html','utf8');fs.writeFileSync('wordpress/ghumnajaun/views/'+name+'.html',html.slice(html.indexOf('<main>')+6,html.indexOf('</main>')));}
const explore=fs.readFileSync('design/explore.html','utf8');fs.writeFileSync('wordpress/ghumnajaun/views/explore-dialogs.html',explore.match(/<dialog class="search-dialog start-dialog"[\s\S]*?<\/dialog>/)[0]);
const destination=fs.readFileSync('design/destination.html','utf8');fs.writeFileSync('wordpress/ghumnajaun/views/dialogs.html',destination.slice(destination.indexOf('<dialog class="search-dialog stop-photo-dialog"'),destination.indexOf('<main>')));
const contact=fs.readFileSync('design/contact.html','utf8');fs.writeFileSync('wordpress/ghumnajaun/views/contact-form.html',contact.match(/<form class="contact-form"[\s\S]*?<\/form>/)[0]);
let s=fs.readFileSync('design/prototype.js','utf8');
const maps=[['photos','const picnicCategory'],['places','const dayPlans'],['dayPlans','const origins'],['placePoints','function distanceFrom'],['citySightseeing','const cityPlaces'],['destinationGuides','const find ='],['destinationGalleries','const photoFor'],['tripDetails','function standardTripDetails'],['blogPosts','function renderBlogCards']];
for(const[name,end]of maps){let a=s.indexOf('const '+name+' ='),b=s.indexOf(end,a);s=s.slice(0,a)+`const ${name} = window.GJ.${name};\n`+s.slice(b);}
s=s.replace("const query = new URLSearchParams(window.location.search);", "const query = new URLSearchParams(window.location.search); if(window.GJ.currentPlace)query.set('place',window.GJ.currentPlace);if(window.GJ.currentPost)query.set('slug',window.GJ.currentPost);if(window.GJ.category)query.set('category',window.GJ.category);if(window.GJ.city)query.set('city',window.GJ.city);");
s=s.replace(/function pageUrl\(path,params = \{\}\)\{[\s\S]*?\n\}/,`function pageUrl(path,params = {}){
 let target=window.GJ.pages[path] || path;
 if(path==='destination.html' && window.GJ.urls[params.place]){target=window.GJ.urls[params.place];params={...params};delete params.place;}
 if(path==='post.html' && window.GJ.postUrls[params.slug]){target=window.GJ.postUrls[params.slug];params={...params};delete params.slug;}
 const search=new URLSearchParams();if(from()!=='Kathmandu Valley')search.set('from',from());Object.entries(params).forEach(([key,value])=>{if(value)search.set(key,value)});return target+(search.size?'?'+search:'');
}`);
s=s.replace(/const thumbnail = \(photo,width = 960\) => \{[\s\S]*?\n\};/, 'const thumbnail = (photo,width = 960) => photo?.src || "";');
s=s.replace("const isGarden = name === 'Godawari Botanical Garden';",'const isGarden = false;');
s=s.replace(/   details\.facts\.forEach\([\s\S]*?\n   \}\);/,`   slot('facts').innerHTML=details.facts.map(([label,value,note])=>'<div><dt>'+label+'</dt><dd>'+value+'</dd><dd>'+note+'</dd></div>').join('')+'<div><dt data-trip-origin></dt><dd data-trip-distance></dd><dd>Straight-line distance</dd></div>';`);
s=s.replace("const featured = ['Nagarkot','Panauti','Chitlang','Chandragiri Hills','Godawari Botanical Garden','Bhaktapur Durbar Square'];",'const featured = window.GJ.featured;');
s=s.replace('featured.map(name => places.find(entry => entry[0] === name))','featured.map(name => places.find(entry => entry[0] === name)).filter(Boolean)');
s=s.replace('familyTrips.map(name => places.find(entry => entry[0] === name))','familyTrips.map(name => places.find(entry => entry[0] === name)).filter(Boolean)');
s=s.replace("const bannerPlaces = ['Nagarkot','Chandragiri Hills','Panauti','Kulekhani','Bheda Farm'];",'const bannerPlaces = window.GJ.bannerPlaces;');
let a=s.indexOf('const bannerPhotos = {'),b=s.indexOf("if (view === 'home') find('banner-slides')",a);s=s.slice(0,a)+'const bannerPhotos = {};\n'+s.slice(b);
s=s.replace("const result = await response.json();","const wpResult = await response.json(); const result = {ok:wpResult.success===true,message:wpResult.data?.message||'Unable to send. Please try again.'};");
s=s.replace("if (view === 'home') {\n renderBlogCards", "if (view === 'home') {\n renderBlogCards");
s=s.replace(" find('post-body').innerHTML = post.sections.map", " find('post-body').innerHTML = post.html || post.sections.map");
s=s.replaceAll('href="privacy-policy.html"','href="${window.GJ.pages[\'privacy-policy.html\']}"');
// Keep SEO titles controlled by WordPress rather than overwriting them after load.
s=s.replace(/ document\.title = [^;]+;/g,'');
s=s.replace('[...Object.values(photos),...Object.values(bannerPhotos)].map(photo => {','[...new Map([...Object.values(photos),...Object.values(bannerPhotos)].map(photo=>[photo.src,photo])).values()].map(photo => {');
fs.writeFileSync('wordpress/ghumnajaun/assets/design.js',s);
