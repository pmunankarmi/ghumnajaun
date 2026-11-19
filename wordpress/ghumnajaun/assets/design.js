(() => {
const root = document.getElementById('gj');
const themeRoot = document.documentElement;
const themeToggle = document.getElementById('gj-theme-toggle');
const systemTheme = window.matchMedia('(prefers-color-scheme: dark)');
function activeTheme(){
 return themeRoot.dataset.theme || (systemTheme.matches ? 'dark' : 'light');
}
function updateThemeToggle(){
 const nextTheme = activeTheme() === 'dark' ? 'light' : 'dark';
 themeToggle.dataset.nextTheme = nextTheme;
 themeToggle.setAttribute('aria-label','Switch to ' + nextTheme + ' mode');
 themeToggle.title = 'Switch to ' + nextTheme + ' mode';
}
themeToggle.addEventListener('click', () => {
 const theme = activeTheme() === 'dark' ? 'light' : 'dark';
 themeRoot.dataset.theme = theme;
 try { window.localStorage.setItem('ghumna-jaun-theme',theme); } catch {}
 updateThemeToggle();
});
systemTheme.addEventListener('change',updateThemeToggle);
updateThemeToggle();
themeToggle.hidden = false;

const photos = window.GJ.photos;
const picnicCategory = 'Picnic Spot';
const categories = ['Family','Couple','Friends','Group','Honeysun','Honeymoon','Dharmik',picnicCategory,'Kids'];
const places = window.GJ.places;
const dayPlans = window.GJ.dayPlans;
const origins = {
 'Kathmandu Valley': [27.717, 85.324],
 Kathmandu: [27.717, 85.324],
 Lalitpur: [27.673, 85.325],
 Bhaktapur: [27.672, 85.429]
};
const placePoints = window.GJ.placePoints;
function distanceFrom(origin,point){
 if (!point) return Infinity;
 const radians = angle => angle * Math.PI / 180;
 const lat = radians(point[0] - origin[0]);
 const lon = radians(point[1] - origin[1]);
 const chord = Math.sin(lat / 2) ** 2 + Math.cos(radians(origin[0])) * Math.cos(radians(point[0])) * Math.sin(lon / 2) ** 2;
 return 6371 * 2 * Math.asin(Math.sqrt(Math.min(1,chord)));
}
const citySightseeing = window.GJ.citySightseeing;
const cityPlaces = name => places.filter(([placeName]) => citySightseeing[name].includes(placeName));
const familyTrips = ['Kathmandu Fun Park','Central Zoo','Natural History Museum'];
const entryFeeSource = 'https://ntb.gov.np/en/plan-your-trip/before-you-come/heritage-site-entry-fees';
const entryFees = {
 'Kathmandu Durbar Square': [500,1000],
 'Patan Durbar Square': [500,1000],
 'Bhaktapur Durbar Square': [500,1800],
 Swayambhunath: [50,200],
 'Changu Narayan Temple': [100,300]
};
const destinationGuides = window.GJ.destinationGuides;
const find = id => document.getElementById('gj-' + id);
const view = root.dataset.view;
const query = new URLSearchParams(window.location.search); if(window.GJ.currentPlace)query.set('place',window.GJ.currentPlace);if(window.GJ.currentPost)query.set('slug',window.GJ.currentPost);if(window.GJ.category)query.set('category',window.GJ.category);if(window.GJ.city)query.set('city',window.GJ.city);
const availableFilters = ['All',...categories,'Scenic dining'];
const startSelect = find('start');
const requestedOrigin = query.get('from');
const startingFrom = Object.hasOwn(origins,requestedOrigin) ? requestedOrigin : 'Kathmandu Valley';
const from = () => startSelect?.value || startingFrom;
if (startSelect) startSelect.value = startingFrom;
const requestedCity = query.get('city');
const city = Object.hasOwn(citySightseeing,requestedCity) ? requestedCity : null;
const requestedFilter = query.get('category');
const filter = availableFilters.includes(requestedFilter) ? requestedFilter : 'All';
const place = places.find(([name]) => name === query.get('place'));
const searchQuery = (query.get('q') || '').trim().slice(0,100);
const normalizeSearch = value => value.normalize('NFKC').toLowerCase().replace(/[^\p{L}\p{M}\p{N}]+/gu,' ').trim();
const searchIndex = new Map(places.map(([name,type,tags]) => {
 const cities = Object.keys(citySightseeing).filter(city => citySightseeing[city].includes(name));
 return [name,normalizeSearch([name,type,tags,...cities,...[name,type,...tags.split('|'),...cities].map(label=>window.GJ_NE?.[label]||label)].join(' '))];
}));
function matchingPlaces(entries,value){
 const terms = normalizeSearch(value).split(' ').filter(Boolean);
 return entries.filter(([name]) => terms.length && terms.every(term => searchIndex.get(name).includes(term)));
}

const searchToggle = find('search-toggle');
const searchDialog = find('search-dialog');
const headerSearch = find('header-search');
const searchInput = find('search-input');
const suggestions = find('search-suggestions');
const searchMatches = find('search-matches');
const searchStatus = find('search-status');
const searchAll = find('search-all');

function showSuggestions(){
 const q = searchInput.value.trim();
 suggestions.hidden = !q;
 if (!q) return;
 const matches = matchingPlaces(places,q);
 const phrase = normalizeSearch(q);
 const rank = name => {
   const label = normalizeSearch(name);
   if (label === phrase) return 0;
   if (label.startsWith(phrase)) return 1;
   return label.includes(phrase) ? 2 : 3;
 };
 matches.sort(([a],[b]) => rank(a) - rank(b) || a.localeCompare(b));
 const items = matches.slice(0,6).map(([name,type,,,dining]) => {
   const item = document.createElement('li');
   const link = document.createElement('a');
   link.className = 'page-link search-match';
   link.href = pageUrl('destination.html',{place:name,q});
   const preview = document.createElement('span');
   preview.className = 'search-match-photo';
   const photo = photoFor(name,dining);
   if (photo) {
     const image = document.createElement('img');
     image.loading = 'lazy';
     image.decoding = 'async';
     setPhoto(image,photo,120);
     image.alt = '';
     preview.append(image);
   }
   const text = document.createElement('span');
   text.className = 'search-match-copy';
   const title = document.createElement('strong');
   title.textContent = name;
   const description = document.createElement('small');
   description.textContent = type;
   text.append(title,description);
   link.append(preview,text);
   item.append(link);
   return item;
 });
 searchMatches.replaceChildren(...items);
 searchStatus.textContent = matches.length === 1 ? '1 matching destination' :
   matches.length ? matches.length + ' matching destinations' : 'No matching destinations';
 searchAll.hidden = !matches.length;
 searchAll.href = pageUrl('explore.html',{q});
}
searchToggle.hidden = false;
searchInput.value = searchQuery;
searchToggle.addEventListener('click', () => {
 searchDialog.showModal();
 showSuggestions();
});
find('search-close').addEventListener('click',() => searchDialog.close());
searchDialog.addEventListener('click',event => {
 if (event.target !== searchDialog) return;
 const {left,right,top,bottom} = searchDialog.getBoundingClientRect();
 if (event.clientX < left || event.clientX > right || event.clientY < top || event.clientY > bottom) searchDialog.close();
});
searchInput.addEventListener('input',showSuggestions);
headerSearch.addEventListener('keydown',event => {
 if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;
 if (event.target === searchInput && suggestions.hidden) showSuggestions();
 if (suggestions.hidden) return;
 const links = [...suggestions.querySelectorAll('a:not([hidden])')];
 if (!links.length) return;
 const current = links.indexOf(event.target);
 if (current === -1 && event.target !== searchInput) return;
 event.preventDefault();
 const direction = event.key === 'ArrowDown' ? 1 : -1;
 const next = event.target === searchInput && direction === -1 ? links.length - 1 : current + direction;
 (links[next] || searchInput).focus();
});
headerSearch.addEventListener('submit',event => {
 searchInput.value = searchInput.value.trim();
 if (!searchInput.value) {
   event.preventDefault();
   searchInput.reportValidity();
   return;
 }
 const originInput = headerSearch.elements.namedItem('from');
 originInput.value = from();
 originInput.disabled = originInput.value === 'Kathmandu Valley';
});

function pageUrl(path,params = {}){
 let target=window.GJ.pages[path] || path;
 if(path==='explore.html'){
  const kind=params.city?'city':'category', key=params[kind];
  if(key && window.GJ.collectionUrls?.[kind]?.[key]){target=window.GJ.collectionUrls[kind][key];params={...params};delete params[kind];}
 }
 if(path==='destination.html' && window.GJ.urls[params.place]){target=window.GJ.urls[params.place];params={...params};delete params.place;}
 if(path==='post.html' && window.GJ.postUrls[params.slug]){target=window.GJ.postUrls[params.slug];params={...params};delete params.slug;}
 const search=new URLSearchParams();if(from()!=='Kathmandu Valley')search.set('from',from());Object.entries(params).forEach(([key,value])=>{if(value)search.set(key,value)});return target+(search.size?'?'+search:'');
}
function updateLinks(){
 root.querySelectorAll('a[data-filter],a[data-city],a[data-place],a[data-page],a[data-post]').forEach(link => {
   const data = link.dataset;
   if (data.post) {
     link.href = pageUrl('post.html',{slug:data.post});
   } else if (data.page === 'blog') {
     link.href = pageUrl('blog.html');
   } else if (data.place) {
     link.href = pageUrl('destination.html',{place:data.place,city,category:filter === 'All' ? null : filter,q:searchQuery});
   } else if (data.city) {
     link.href = pageUrl('explore.html',{city:data.city});
   } else if (data.filter) {
     link.href = pageUrl('explore.html',{
       city: link.closest('#gj-filters') ? city : null,
       category: data.filter === 'All' ? null : data.filter,
       q: link.closest('#gj-filters') ? searchQuery : null
     });
   } else if (data.page === 'home') {
     link.href = pageUrl('index.html');
   } else {
     link.href = pageUrl('explore.html',{city,category:filter === 'All' ? null : filter,q:searchQuery});
   }
 });
}
const chips = (items,active) => items.map(label => {
 const extra = label === 'Honeysun' ? ' aria-label="Honeysun, one-day trip for newlyweds"' : '';
 const current = active === label ? ' aria-current="true"' : '';
 return `<a class="page-link" href="${pageUrl('explore.html',{city,category:label === 'All' ? null : label})}" data-filter="${label}" data-reveal="fade-up"${current}${extra}>${label}</a>`;
}).join('');
const destinationGalleries = window.GJ.destinationGalleries;
const photoFor = (name,dining) => dining ? photos.Dining : photos[name];
const thumbnail = (photo,width = 960) => photo?.src || "";
const responsiveAttrs = (photo,sizes='100vw') => {
 if(!photo)return '';
 const escape=value=>String(value).replaceAll('&','&amp;').replaceAll('"','&quot;');
 return (photo.width ? ' width="'+photo.width+'" height="'+photo.height+'"' : '')+(photo.srcset ? ' srcset="'+escape(photo.srcset)+'" sizes="'+escape(sizes)+'"' : '');
};
const cards = (entries,distances) => entries.map(([name,type,tags,,dining]) => {
 const photo = photoFor(name,dining);
 return `<div class="col" data-reveal="fade-up"><a class="place page-link" href="${pageUrl('destination.html',{place:name})}" data-place="${name}">
   <span class="art">${photo ? `<img src="${thumbnail(photo)}" data-original="${photo.src}"${responsiveAttrs(photo,'(max-width:575px) calc(100vw - 32px), (max-width:991px) calc(50vw - 32px), 360px')} alt="${photo.alt}" loading="lazy">` : '<span class="photo-pending">Photo pending</span>'}</span>
   <span class="info"><span class="meta">${type}${dining ? ' · Sample listing' : ''}</span><strong>${name}</strong>${distances ? `<span class="place-distance">${Number.isFinite(distances[name]) ? (distances[name] < 1 ? 'Under 1' : '≈ ' + Math.round(distances[name])) + ' km away' : 'Distance unavailable'}</span>` : ''}<span class="card-foot">${dining ? dining.view + ' · About 1 hr (sample)' : tags.split('|').slice(0,2).join(' · ')} <span aria-hidden="true">↗</span></span></span>
 </a></div>`;
}).join('');
function renderCities(){
 find('cities').innerHTML = Object.entries(citySightseeing).map(([name,sights]) => {
   const photo = photos[sights[0]];
   const count = cityPlaces(name).length;
   const countLabel = count + (count === 1 ? ' place to see' : ' places to see');
   return `<div class="col" data-reveal="fade-up"><a class="place city-card page-link" href="${pageUrl('explore.html',{city:name})}" data-city="${name}">
     <span class="art"><img src="${thumbnail(photo)}" data-original="${photo.src}"${responsiveAttrs(photo,'(max-width:575px) calc(100vw - 32px), (max-width:991px) calc(50vw - 32px), 360px')} alt="" loading="lazy"></span>
     <span class="info"><strong>${name}</strong><span class="card-foot"><span>${countLabel}</span><span aria-hidden="true">↗</span></span></span>
   </a></div>`;
 }).join('');
}
function results(){
 const origin = origins[startingFrom];
 const distances = Object.fromEntries(places.map(([name]) => {
   const picnicPoint = filter === picnicCategory ? destinationGuides[name]?.picnicPoint : null;
   return [name,distanceFrom(origin,picnicPoint || placePoints[name])];
 }));
 const archiveEntries=window.GJ.archivePlaces?places.filter(entry=>window.GJ.archivePlaces.includes(entry[0])):places;
 const entries = (city ? cityPlaces(city) : archiveEntries).filter(([,type,tags]) =>
   filter === 'All' || filter === type || tags.split('|').includes(filter));
 const matches = searchQuery ? matchingPlaces(entries,searchQuery) : entries;
 if (!city) matches.sort((a,b) => distances[a[0]] - distances[b[0]]);
 let title = window.GJ.resultsTitle || 'Explore nearby';
 if (searchQuery) title = 'Search results for “' + searchQuery + '”';
 else if (city) title = 'Sightseeing in ' + city;
 else if (filter === 'Scenic dining') title = filter;
 else if (filter === picnicCategory) title = 'Picnic spots';
 else if (filter === 'Kids') title = 'Places to go with kids';
 if(window.GJ.archiveTitle)title=window.GJ.archiveTitle;
 find('results-title').textContent = title;

 find('origin').textContent = city ? 'Browse by city' : 'From ' + startingFrom;
 find('distance-note').hidden = Boolean(city);
 const startDialog = find('start-dialog');
 const originSelect = find('start-origin');
 find('change-start').addEventListener('click',() => {
   originSelect.value = startingFrom;
   startDialog.showModal();
 });
 find('start-close').addEventListener('click',() => startDialog.close());
 startDialog.addEventListener('click',event => {
   if (event.target !== startDialog) return;
   const bounds = startDialog.getBoundingClientRect();
   if (event.clientX < bounds.left || event.clientX > bounds.right ||
       event.clientY < bounds.top || event.clientY > bounds.bottom) startDialog.close();
 });
 find('start-form').addEventListener('submit',event => {
   event.preventDefault();
   const url = new URL(window.location.href);
   if (originSelect.value === 'Kathmandu Valley') url.searchParams.delete('from');
   else url.searchParams.set('from',originSelect.value);
   url.hash = '';
   window.location.assign(url.href);
 });
 find('count').textContent = matches.length + (matches.length === 1 ? ' place' : ' places');
 const filters = ['All',...categories,'Scenic dining'].filter(label =>
   !city || label === 'All' || cityPlaces(city).some(([,type,tags]) => label === type || tags.split('|').includes(label))
 );
 find('filters').innerHTML = chips(filters,filter);
 const emptyMessage = searchQuery ? 'No places match your search. Try a different name, area or activity.' : 'No places match this filter.';
 find('list').innerHTML = matches.length ? cards(matches,city ? null : distances) : '<p>' + emptyMessage + '</p>';
}
function mapSearch(query){
 return 'https://www.google.com/maps/search/?' + new URLSearchParams({api:'1',query});
}
function externalLink(url,label,className = 'page-link'){
 return `<a class="${className}" href="${url}" target="_blank" rel="noopener">${label} <span class="link-arrow" aria-hidden="true">↗</span></a>`;
}
function embedPlaceholder(label){
 return `<div class="consent-placeholder"><p>${label} loads after you accept optional embedded content.</p><button type="button" data-cookie-preferences>Cookie preferences</button></div>`;
}
function destinationMap(name,point){
 const [lat,lon] = point;
 const params = new URLSearchParams({
   bbox: [lon - 0.025,lat - 0.015,lon + 0.025,lat + 0.015].join(','),
   layer: 'mapnik',
   marker: point.join(',')
 });
 return `<div class="consent-embed consent-map">${embedPlaceholder('This map')}<iframe class="guide-map" title="Map of ${name}" loading="lazy" data-consent-src="https://www.openstreetmap.org/export/embed.html?${params}" hidden></iframe></div>`;
}
const tripDetails = window.GJ.tripDetails;
function standardTripDetails(name,type){
 const guide = destinationGuides[name];
 const fee = entryFees[name];
 const stops = guide.stops.map(stop => Array.isArray(stop) ? stop : [stop]);
 const picnic = guide.picnic;
 const visit = [[picnic ? 'Picnic planning' : 'Before you go',[
   picnic || guide.visitNote || 'Check opening hours and access before leaving, especially if visiting on a holiday.'
 ]]];
 if (fee) {
   visit.unshift(['Published entry fees',[
     'SAARC visitors · NPR ' + fee[0].toLocaleString('en'),
     'Other international visitors · NPR ' + fee[1].toLocaleString('en')
   ],['Nepal Tourism Board fee table',entryFeeSource]]);
 }
 const sources = guide.sources || (guide.source ? [['Nepal Tourism Board visitor guide',guide.source]] : []);
 const nearby = stops.slice(1).map(([label]) => [label + ', Nepal',label]);
 if (name === 'Shikali Temple') {
   for (const temple of ['Rudrayani Temple','Karya Binayak Temple','Rato Machhindranath Temple','Jal Binayak Temple']) {
     nearby.push([null,temple,pageUrl('destination.html',{place:temple,category:'Dharmik'})]);
   }
 }
 if (guide.picnicPoint) nearby.push([guide.picnicPoint.join(','),'Picnic ground pin']);
 return {
   location: type,
   photos: destinationGalleries[name] || (photos[name] ? [name] : []),
   facts: [
     ['Outing',type.split(' · ')[0],name === 'Four Binayak Ganesh Temples' ? 'Four stops across the valley' : ''],
     [fee ? 'Entry fees' : 'Visiting',fee ? 'See ticket details' : 'Check local access',''],
     [picnic ? 'Picnics' : 'Visit plan',picnic ? 'See picnic guidance' : 'See places to visit','']
   ],
   activities: stops.map(([label,note,photo]) => [label,note,mapSearch(label + ', Nepal'),photo]),
   sources,
   visit,
   transport: [
     ['By bus',guide.bus || 'Confirm the boarding point, nearest drop-off and last return with a local operator. Use the map to plan the walk from the stop.'],
     ['By car or taxi',guide.transport || 'Use the destination pin for directions. Check where vehicles can drop passengers off and whether there is parking nearby.']
   ],
   plan: dayPlans[name]?.map(stop => stop.split(' · ')) || stops.map(([label],index) => [
     index === 0 ? 'Start here' : 'If you have time',label
   ]),
   nearby,
   nearbyNote: nearby.length ? 'Open each location separately to plan the order of your stops.' : ''
 };
}
function renderTrip(name,destination,directionsUrl,type,dining){
 const trip = find('trip-template').content.cloneNode(true);
 const slot = key => trip.querySelector('[data-trip-' + key + ']');
 const isGarden = false;
 const details = isGarden ? null : tripDetails[name] || (dining ? {
   location: 'Scenic dining · Sample listing',
   intro: 'A sample scenic dining stop with ' + dining.view.toLowerCase() + ' and ' + dining.seating.toLowerCase() + '. The sample menu and budget illustrate what a confirmed venue listing could include.',
   photos: ['Dining'],
   facts: [['View',dining.view,''],['Seating',dining.seating,''],['Sample budget',dining.budget,'']],
   activities: dining.menu.map(item => [item]),
   visit: [['View and seating',[dining.view,dining.seating]]],
   transport: [
     ['By bus','The venue address is pending. Check the nearest stop once a restaurant is confirmed.'],
     ['By car or taxi','Confirm the restaurant address, drive time, and parking before travelling.']
   ],
   nearby: []
 } : standardTripDetails(name,type));
 const point = name === 'Four Binayak Ganesh Temples' ? placePoints['Kathmandu Durbar Square'] : placePoints[name];
 const originLabel = startingFrom === 'Kathmandu Valley' ? 'central Kathmandu' : startingFrom;
 const distance = distanceFrom(origins[startingFrom],point);
 if (details) {
   slot('title').textContent = name;
   slot('location').textContent = details.location.toUpperCase();
   slot('slider').setAttribute('aria-label',name + ' photos');
   slot('slider').querySelector('.trip-photo-dots').setAttribute('aria-label','Choose a destination photo');
   if(root.querySelector('[data-screen="detail"] .trip-hero'))slot('slides').replaceChildren();
   else slot('slides').innerHTML = details.photos.map((key,index) =>
     `<div class="swiper-slide"><img data-trip-photo="${key.replaceAll('&','&amp;')}" alt="" ${index ? 'loading="lazy"' : 'fetchpriority="high"'}></div>`
   ).join('');
   slot('facts').innerHTML=details.facts.map(([label,value,note])=>'<div><dt>'+label+'</dt><dd>'+value+'</dd><dd>'+note+'</dd></div>').join('')+'<div><dt data-trip-origin></dt><dd data-trip-distance></dd><dd>Straight-line distance</dd></div>';
   slot('activities').innerHTML = details.activities.map(([title,description,url,photo]) => {
     const image = photos[photo] ? `<img class="trip-stop-photo" data-trip-photo="${photo.replaceAll('&','&amp;')}" alt="" loading="lazy" decoding="async">` : '';
     return `<li>${image}<div><h3>${title}</h3>${description ? '<p>' + description + '</p>' : ''}${url ? externalLink(url,'View ' + title + ' on map','trip-inline-link') : ''}</div></li>`;
   }).join('');
   const sources = details.sources || (details.source ? [details.source] : []);
   slot('feature-link').innerHTML = sources.map(([label,url]) => externalLink(url,label,'trip-inline-link')).join('');
   const feature = slot('feature');
   const featureKey=details.featurePhoto || details.photos[1];if (featureKey) {
     feature.querySelector('img').dataset.tripPhoto = featureKey;
     feature.querySelector('figcaption').textContent = details.featureCaption || photos[featureKey].alt;
   } else {
     feature.remove();
   }
   const vlog=trip.querySelector('#gj-vlog-title');if(details.videoUrl){vlog.querySelector('iframe').dataset.consentSrc=details.videoUrl;vlog.querySelector('iframe').title=name+' vlog on YouTube';vlog.setAttribute('aria-label',name+' vlog');}else vlog.remove();
   slot('visit').innerHTML = details.visitHtml || details.visit.map(([title,notes,source]) =>
     `<div class="trip-card"><h3>${title}</h3><ul class="trip-visit-list">${notes.map(note => '<li>' + note + '</li>').join('')}</ul>${source ? externalLink(source[1],source[0]) : ''}</div>`
   ).join('');
   slot('transport').innerHTML = details.transport.map(([title,note],index) =>
     `<div><h3>${title}</h3><p>${note}</p>${(details.transportLinks?.[index] || []).map(([label,url])=>externalLink(url,label,'')).join('')}</div>`
   ).join('') + '<div class="trip-text-links" data-trip-travel-links></div>';
   if(details.mealNote)slot('meal-note').textContent=details.mealNote;else slot('meal-note').remove();
   slot('meals').innerHTML = (details.meals || []).map(([stage,title,note,url,label],index)=>'<article class="trip-card"><p class="eyebrow">'+stage.toUpperCase()+'</p><h3>'+title+'</h3><p>'+note+'</p>'+((details.mealLinks?.[index] || (url?[[label,url]]:[])).length?'<div class="trip-text-links">'+(details.mealLinks?.[index] || [[label,url]]).map(([text,href])=>externalLink(href,text)).join('')+'</div>':'')+'</article>').join('');
   if (!details.meals) slot('meals').remove();
   const stays = slot('stays');
   if (details.stays) {
     stays.innerHTML = details.stays.map(([label,location,url,linkLabel,plain]) =>
       `<li><strong>${label}</strong> <span>${location}</span> ${externalLink(url,linkLabel||'Hotel website',plain?'':'page-link')}</li>`
     ).join('');
   } else {
     stays.remove();
   }
   slot('plan').innerHTML = (details.plan || []).map(([stage,note]) =>
     `<li><strong>${stage}</strong><span>${note}</span></li>`
   ).join('');
   slot('nearby-note').textContent = details.nearbyNote || '';
   if (!details.nearby.length) slot('nearby-note').closest('.trip-card').hidden = true;
   if (!details.photos.length) slot('slider').remove();
   if (dining) {
     for (const [id,label] of [['gj-things','Menu & prices'],['gj-visit','View & seating']]) {
       trip.querySelector('#' + id + '-title').textContent = label;
       trip.querySelector('a[href="#' + id + '"]').textContent = label;
     }
     for (const id of ['gj-eat','gj-day']) {
       trip.querySelector('#' + id).hidden = true;
       trip.querySelector('a[href="#' + id + '"]').remove();
     }
     trip.querySelector('.trip-mobile-bar').hidden = true;
   }
 }
 slot('intro').textContent = destinationGuides[name]?.summary || details?.intro || '';
 const history = destinationGuides[name]?.history;
 if (history) {
   const [copy,label,url] = history;
   slot('history').textContent = copy;
   slot('history-source').innerHTML = externalLink(url,label);
   slot('history').closest('section').hidden = false;
   slot('history-jump').hidden = false;
 }
 slot('origin').textContent = 'From ' + originLabel;
 slot('distance').textContent = distance < 1 ? 'Under 1 km' : 'About ' + Math.round(distance) + ' km';
 if (!point) slot('origin').parentElement.remove();
 trip.querySelectorAll('[data-trip-photo]').forEach(image => {
   setPhoto(image,photos[image.dataset.tripPhoto],image.classList.contains('trip-stop-photo') ? 250 : undefined);
 });
 const photoDialog = find('stop-photo-dialog');
 const largePhoto = find('stop-photo-large');
 const photoStatus = find('stop-photo-status');
 const stopImages = [...trip.querySelectorAll('.trip-stop-photo')];
 const photoPosition = find('stop-photo-position');
 const photoThumbnails = find('stop-photo-thumbnails');
 const thumbnailButtons = stopImages.map((image,index) => {
   const button = document.createElement('button');
   button.type = 'button';
   button.setAttribute('aria-label','Show photo of ' + image.closest('li').querySelector('h3').textContent);
   button.setAttribute('aria-pressed','false');
   const preview = document.createElement('img');
   preview.width = 60;
   preview.height = 44;
   preview.loading = 'lazy';
   preview.decoding = 'async';
   setPhoto(preview,photos[image.dataset.tripPhoto],120);
   preview.alt = '';
   button.append(preview);
   button.addEventListener('click',() => {
     if (index !== activePhoto) showStopPhoto(index);
   });
   return button;
 });
 photoThumbnails.replaceChildren(...thumbnailButtons);
 photoThumbnails.hidden = stopImages.length < 2;
 const photoMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
 let activePhoto = 0;
 let photoChange = 0;
 let photoFade = null;
 function revealActiveThumbnail(){
   if (!photoDialog.open || photoThumbnails.hidden) return;
   const strip = photoThumbnails.getBoundingClientRect();
   const selected = thumbnailButtons[activePhoto].getBoundingClientRect();
   const centered = photoThumbnails.scrollLeft + selected.left - strip.left -
     (photoThumbnails.clientWidth - selected.width) / 2;
   const maximum = photoThumbnails.scrollWidth - photoThumbnails.clientWidth;
   photoThumbnails.scrollTo({
     left:Math.max(0,Math.min(maximum,centered)),
     behavior:photoMotion.matches ? 'instant' : 'smooth'
   });
 }
 new ResizeObserver(revealActiveThumbnail).observe(photoThumbnails);
 async function showStopPhoto(index){
   const change = ++photoChange;
   activePhoto = (index + stopImages.length) % stopImages.length;
   const image = stopImages[activePhoto];
   const photo = photos[image.dataset.tripPhoto];
   const opacity = getComputedStyle(largePhoto).opacity;
   photoFade?.cancel();
   if (photoDialog.open && !largePhoto.hidden && !photoMotion.matches) {
     photoFade = largePhoto.animate([{opacity},{opacity:0}],{duration:120,easing:'ease-out',fill:'forwards'});
     await photoFade.finished.catch(() => {});
     if (change !== photoChange) return;
   }
   largePhoto.style.opacity = '0';
   photoFade?.cancel();
   find('stop-photo-title').textContent = image.closest('li').querySelector('h3').textContent;
   find('stop-photo-caption').textContent = photo.alt;
   photoPosition.textContent = (activePhoto + 1) + ' of ' + stopImages.length;
   thumbnailButtons.forEach((button,index) => button.setAttribute('aria-pressed',String(index === activePhoto)));
   revealActiveThumbnail();
   photoStatus.textContent = 'Loading photo…';
   largePhoto.hidden = false;
   setPhoto(largePhoto,photo,1280);
 }
 find('stop-photo-controls').hidden = stopImages.length < 2;
 find('stop-photo-previous').addEventListener('click',() => showStopPhoto(activePhoto - 1));
 find('stop-photo-next').addEventListener('click',() => showStopPhoto(activePhoto + 1));
 let photoSwipe = null;
 largePhoto.addEventListener('pointerdown',event => {
   if (!event.isPrimary) {
     photoSwipe = null;
     return;
   }
   if (event.pointerType === 'mouse' || stopImages.length < 2) return;
   photoSwipe = {id:event.pointerId,x:event.clientX,y:event.clientY};
   largePhoto.setPointerCapture(event.pointerId);
 });
 largePhoto.addEventListener('pointerup',event => {
   const start = photoSwipe;
   photoSwipe = null;
   if (!start || start.id !== event.pointerId) return;
   const horizontal = event.clientX - start.x;
   const vertical = event.clientY - start.y;
   if (Math.abs(horizontal) < 48 || Math.abs(horizontal) < Math.abs(vertical) * 1.3) return;
   showStopPhoto(activePhoto + (horizontal < 0 ? 1 : -1));
 });
 const cancelPhotoSwipe = () => { photoSwipe = null; };
 largePhoto.addEventListener('pointercancel',cancelPhotoSwipe);
 largePhoto.addEventListener('lostpointercapture',cancelPhotoSwipe);
 photoDialog.addEventListener('close',cancelPhotoSwipe);
 photoDialog.addEventListener('keydown',event => {
   if (!['ArrowLeft','ArrowRight'].includes(event.key) || stopImages.length < 2) return;
   event.preventDefault();
   showStopPhoto(activePhoto + (event.key === 'ArrowRight' ? 1 : -1));
 });
 stopImages.forEach((image,index) => {
   const button = document.createElement('button');
   button.type = 'button';
   button.className = 'trip-stop-photo-button';
   const title = image.closest('li').querySelector('h3').textContent;
   button.setAttribute('aria-label','View larger photo of ' + title);
   button.setAttribute('aria-haspopup','dialog');
   button.setAttribute('aria-controls',photoDialog.id);
   image.before(button);
   button.append(image);
   button.addEventListener('click',() => {
     showStopPhoto(index);
     photoDialog.showModal();
     revealActiveThumbnail();
   });
 });
 largePhoto.addEventListener('load',() => {
   photoStatus.textContent = '';
   photoFade?.cancel();
   largePhoto.style.opacity = '1';
   if (photoDialog.open && !photoMotion.matches) {
     photoFade = largePhoto.animate([{opacity:0},{opacity:1}],{duration:220,easing:'ease-out'});
   }
 });
 largePhoto.addEventListener('error',() => {
   if (!largePhoto.dataset.original) photoStatus.textContent = 'This photo could not load. Try another photo or close the popup and try again.';
 });
 find('stop-photo-close').addEventListener('click',() => photoDialog.close());
 photoDialog.addEventListener('close',() => {
   photoChange++;
   photoFade?.cancel();
   largePhoto.style.opacity = '1';
 });
 photoDialog.addEventListener('click',event => {
   if (event.target !== photoDialog) return;
   const bounds = photoDialog.getBoundingClientRect();
   if (event.clientX < bounds.left || event.clientX > bounds.right ||
       event.clientY < bounds.top || event.clientY > bounds.bottom) photoDialog.close();
 });
 trip.querySelectorAll('[data-trip-directions]').forEach(link => {
   if (dining) {
     link.hidden = true;
     return;
   }
   link.href = directionsUrl;
   if (name === 'Four Binayak Ganesh Temples') link.textContent = 'Open temple route ↗';
   link.setAttribute('aria-label','Get directions from ' + originLabel + ' in Google Maps');
 });
 trip.querySelectorAll('[data-trip-venue]').forEach(link => {
   link.href = mapSearch(link.dataset.tripVenue);
 });
 trip.querySelectorAll('[data-trip-stop]').forEach(link => {
   const route = new URL(directionsUrl);
   const params = route.searchParams;
   if (link.hasAttribute('data-trip-return')) {
     const origin = params.get('origin');
     params.set('origin',params.get('destination'));
     params.set('destination',origin);
   }
   params.set('waypoints',link.dataset.tripStop);
   link.href = route.href;
 });
 if (point) {
   slot('map').insertAdjacentHTML('afterbegin',destinationMap(name,point));
   const mapPin = name === 'Four Binayak Ganesh Temples' ? 'Ashok Binayak Temple, Maru' : destination;
   slot('map-link').href = destinationGuides[name]?.mapUrl || mapSearch(mapPin + ', Nepal');
 } else {
   slot('map').remove();
 }
 const transportPin = isGarden ? ['Godawari Bus Park, Lalitpur, Nepal','Godawari Bus Park'] : details.transportPin;
 slot('travel-links').innerHTML = [
   ...(transportPin ? [transportPin] : []),
   ['Petrol pumps near ' + destination,'Nearby petrol pumps'],
   ['EV charging near ' + destination,'Nearby EV charging']
 ].map(([query,label]) => externalLink(mapSearch(query),label)).join('');
 if (dining) slot('travel-links').remove();
 slot('nearby-links').innerHTML = (details?.nearby || [
   ['Godawari Kunda, Lalitpur, Nepal','Godawari Kunda'],
   ['Naudhara, Godawari, Lalitpur, Nepal','Naudhara']
 ]).map(([query,label,url]) => url ? `<a class="page-link" href="${url}">${label} ↗</a>` : externalLink(mapSearch(query),label)).join('');
 const foodArea = details?.foodArea || destination;
 slot('food-links').innerHTML =
   externalLink(mapSearch('Restaurants near ' + foodArea),details.originalGardenLayout?'More restaurants near the garden':'Find nearby restaurants') +
   externalLink(mapSearch('Hotels near ' + foodArea),details.originalGardenLayout?'More stays near the garden':'Find nearby stays');
 const currentTags = place[2].split('|');
 const related = places.filter(entry => entry[0] !== name && !entry[4]).map(entry => {
   const sharedTags = entry[2].split('|').filter(tag => currentTags.includes(tag)).length;
   const distance = point ? distanceFrom(point,placePoints[entry[0]]) : Infinity;
   return {entry,score:(Number.isFinite(distance) ? distance : 50) - sharedTags * 2};
 }).sort((a,b) => a.score - b.score).slice(0,3).map(item => item.entry);
 slot('related').innerHTML = cards(related);
 const screen = root.querySelector('[data-screen="detail"]');
 screen.classList.add('destination-trip');
 const savedHero=screen.querySelector('.trip-hero');
 const newHero=trip.querySelector('.trip-hero');
 if(savedHero && newHero){
  newHero.remove();
  for(const child of [...screen.childNodes])if(child!==savedHero)child.remove();
  screen.append(trip);
 }else screen.replaceChildren(trip);
 const header = root.querySelector('header');
 const jumpMenu = screen.querySelector('.trip-jumps');
 const jumpLinks = [...jumpMenu.querySelectorAll('a:not([hidden])')];
 const jumpSections = jumpLinks.map(link => document.getElementById(link.hash.slice(1)));
 let activeLink = null;
 let scrollFrame = 0;
 function updateActiveSection(){
   scrollFrame = 0;
   const menuBounds = jumpMenu.getBoundingClientRect();
   const atPageEnd = window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 2;
   const activeIndex = atPageEnd ? jumpLinks.length - 1 : jumpSections.findLastIndex(
     section => section.getBoundingClientRect().top <= menuBounds.bottom + 24
   );
   const nextLink = jumpLinks[activeIndex] || null;
   if (nextLink === activeLink) return;
   activeLink?.removeAttribute('aria-current');
   activeLink = nextLink;
   if (!activeLink) return;
   activeLink.setAttribute('aria-current','location');
   const linkBounds = activeLink.getBoundingClientRect();
   const leftEdge = menuBounds.left + 5;
   const rightEdge = menuBounds.right - 5;
   if (linkBounds.left < leftEdge) jumpMenu.scrollLeft += linkBounds.left - leftEdge;
   if (linkBounds.right > rightEdge) jumpMenu.scrollLeft += linkBounds.right - rightEdge;
 }
 function scheduleActiveSection(){
   if (!scrollFrame) scrollFrame = requestAnimationFrame(updateActiveSection);
 }
 const updateHeaderHeight = () => {
   screen.style.setProperty('--trip-header-height',header.offsetHeight + 'px');
   scheduleActiveSection();
 };
 updateHeaderHeight();
 new ResizeObserver(updateHeaderHeight).observe(header);
 window.addEventListener('scroll',scheduleActiveSection,{passive:true});
 window.addEventListener('resize',scheduleActiveSection);
 const slider = screen.querySelector('[data-trip-slider]');
 if (slider && window.Swiper && slider.querySelectorAll('.swiper-slide').length > 1) {
   const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
   const timer = slider.querySelector('[data-trip-timer]');
   new window.Swiper(slider,{
     speed: reduceMotion ? 0 : 550,
     effect: 'fade',
     fadeEffect: {crossFade: true},
     autoplay: reduceMotion ? false : {delay: 6000,disableOnInteraction: true,pauseOnMouseEnter: true},
     pagination: {
       el: slider.querySelector('.trip-photo-dots'),
       clickable: true,
       bulletElement: 'button'
     },
     a11y: {paginationBulletMessage: details ? 'Show destination photo {{index}}' : 'Show garden photo {{index}}'},
     on: {
       autoplayStart() {
         timer.hidden = false;
       },
       autoplayStop() {
         timer.hidden = true;
       },
       slideChange() {
         timer.style.setProperty('--elapsed',0);
       },
       autoplayTimeLeft(_slider,_timeLeft,remaining) {
         timer.style.setProperty('--elapsed',Math.max(0,Math.min(1,1 - remaining)));
       }
     }
   });
 }

}
function detail(){
 const [name,type,,,dining] = place;
 const destination = {
   "Kathmandu Fun Park": "Kathmandu Fun Park, Bhrikutimandap",
   "Natural History Museum": "Natural History Museum, Swayambhu, Kathmandu",
   "Jump KTM, Jhamsikhel": "Jump Jhamsikhel Indoor Trampoline Park, Lalitpur",
   "Whoopee Land": "Whoopee Land Amusement and Water Park, Chobhar",
   "Kathmandu Fun Valley": "Kathmandu Fun Valley, Palanse, Bhaktapur",
   "Bagh Bhairab Temple": "Bagh Bhairab Temple, Kirtipur",
   "Indrayani Temple (Luti Ajima)": "Lhuti Ajima, Kathmandu",
   "Mhepi Ajima Temple": "Mhepi Ajima Temple, Kathmandu",
   "Bijeshwari Temple": "Bijeshwari Temple, Kathmandu",
   "Santaneshwor Mahadev Temple": "Santaneshwor Mahadev Temple, Jharuwarasi",
   "Chonga Ganesh Temple": "Chonga Ganesh Temple, Sallaghari, Bhaktapur",
   "Surya Binayak Temple": "Surya Binayak Temple, Bhaktapur",
   "Tribhuvan Park": "Tribhuwan Park, Thankot",
   "Balaju Baisdhara Park": "Balaju Baisdhara Park, Kathmandu",
   "Manjushree Park": "Manjushree Park, Chobhar, Kirtipur",
   "Sangdaha Picnic Spot": "Sangdaha Picnic Spot, Changunarayan",
   'Changu Narayan Temple': 'Changu Narayan Temple, Bhaktapur',
   'Godawari Botanical Garden': 'National Botanical Garden, Godawari, Lalitpur',
   'Mahalaxmi Temple, Telkot': 'Shree Pancha Mahalaxmi Temple, Telkot, Bhaktapur',
   'White Gumba': 'Druk Amitabha Mountain Monastery, Nagarjun',
   'Chandragiri Hills': 'Chandragiri Hills Cable Car, Thankot',
   'Bheda Farm': 'Bheda Farm, Chitlang',
   '1,000 Steps, Dhulikhel': 'Dhulikhel 1000 Steps',
   'Central Zoo': 'Central Zoo, Jawalakhel',
   'Simulation Science Center': 'Simulation Science Center, Chobhar, Kirtipur',
   'National Museum of Nepal': 'National Museum of Nepal, Chhauni',
   'Adinath Lokeshwar Temple': 'Adinath Lokeshwar Temple, Chobhar',
   'Bajra Barahi Temple': 'Bajra Barahi Temple, Chapagaon',
   'Nil Barahi Temple': 'Nil Barahi Temple, Bode, Madhyapur Thimi',
   'Chandeshwori Temple': 'Chandeshwori Temple, Banepa',
   'Matsya Narayan Temple': 'Matsya Narayan Temple, Machhegaun',
   'Doleshwor Mahadev Temple': 'Doleshwor Mahadev Temple, Sipadol, Bhaktapur',
   'Ashapuri Mahadev Temple': 'Ashapuri Mahadev Temple, Suryabinayak, Bhaktapur',
   'Ichangu Narayan Temple': 'Ichangu Narayan Temple, Ichangu, Nagarjun',
   'Shikali Temple': 'Shikali Temple, Khokana',
   'Rudrayani Temple': 'Rudrayani Temple, Khokana',
   'Karya Binayak Temple': 'Karya Binayak Temple, Bungamati',
   'Rato Machhindranath Temple': 'Rato Machhindranath Temple, Bungamati',
   'Jal Binayak Temple': 'Jal Binayak Temple, Chobhar',
   'Sankhu & Bajrayogini': 'Bajrayogini Temple, Sankhu',
   'Four Binayak Ganesh Temples': 'Jal Binayak Temple, Chobhar'
 }[name] || name;
 const route = new URLSearchParams({
   api: '1',
   origin: (startingFrom === 'Kathmandu Valley' ? 'Kathmandu' : startingFrom) + ', Nepal',
   destination: destination + ', Nepal'
 });
 if (name === 'Sankhu & Bajrayogini') route.set('waypoints','Sankhu, Nepal');
 if (name === 'Four Binayak Ganesh Temples') {
   const temples = destinationGuides[name].stops;
   route.set('waypoints',temples.slice(0,-1).map(([temple]) => temple + ', Nepal').join('|'));
 }
 const directionsUrl = 'https://www.google.com/maps/dir/?' + route;
 renderTrip(name,destination,directionsUrl,type,dining);
}
const blogPosts = window.GJ.blogPosts;
function renderBlogCards(posts){
 find('blog-posts').innerHTML = posts.map(post => {
   const photo = photos[post.photo];
   return `<div class="col" data-reveal="fade-up"><a class="place page-link blog-card" href="${pageUrl('post.html',{slug:post.slug})}" data-post="${post.slug}">
     <span class="art"><img src="${thumbnail(photo)}" data-original="${photo.src}"${responsiveAttrs(photo,'(max-width:575px) calc(100vw - 32px), (max-width:991px) calc(50vw - 32px), 360px')} alt="" loading="lazy"></span>
     <span class="info"><span class="meta">${post.category}</span><strong>${post.title}</strong><span>${post.intro}</span><span class="meta">Read guide ↗</span></span>
   </a></div>`;
 }).join('');
}

function renderBlogPost(){
 const slug = query.get('slug');
 if (slug === 'four-binayak-ganesh-temples') {
   window.location.replace(pageUrl('destination.html',{place:'Four Binayak Ganesh Temples',category:'Dharmik'}));
   return;
 }
 const post = blogPosts.find(entry => entry.slug === slug);
 if (!post) {
   const title = query.has('slug') ? 'Guide not found' : 'Choose a travel guide';
   find('post-title').textContent = title;
  
   return;
 }

 find('post-title').textContent = post.title;
 find('post-category').textContent = post.category;
 find('post-intro').textContent = post.intro;
 const image = find('post-image');
 setPhoto(image,photos[post.photo]);
 image.hidden = false;
 find('post-body').innerHTML = post.html || post.sections.map(([heading,copy]) =>
   `<section data-reveal="fade-up"><h2>${heading}</h2><p>${copy}</p></section>`
 ).join('') + `<section data-reveal="fade-up"><h2>Destination guides</h2><div class="guide-links">${post.places.map(name =>
   `<a class="page-link" data-place="${name}" href="${pageUrl('destination.html',{place:name})}">${name} ↗</a>`
 ).join('')}</div></section><section class="blog-sources" data-reveal="fade-up"><h2>Sources</h2><ul>${post.sources.map(([label,url]) =>
   `<li><a href="${url}" target="_blank" rel="noopener">${label}</a></li>`
 ).join('')}</ul></section>`;
}

if (view === 'home') {
 renderBlogCards(blogPosts.slice(0,3));
 find('home-chips').innerHTML = chips(categories);
 const featured = window.GJ.featured;
 find('featured').innerHTML = cards(featured.map(name => places.find(entry => entry[0] === name)).filter(Boolean));
 find('family-trips').innerHTML = cards(familyTrips.map(name => places.find(entry => entry[0] === name)).filter(Boolean));
 renderCities();
 setPhoto(find('dining-image'),photos.Dining);
 startSelect.addEventListener('change',updateLinks);
} else if (view === 'blog') {
 renderBlogCards(blogPosts);
} else if (view === 'post') {
 renderBlogPost();
} else if (view === 'results') {
 results();
} else if (view === 'about') {
 setPhoto(find('about-image'),photos.Kulekhani);
} else if (view === 'contact') {
 const contactForm = find('contact-form');
 let sending = false;
 const sendContactMessage = async () => {
   if (sending) return;
   sending = true;
   const button = contactForm.querySelector('button[type="submit"]');
   const status = find('contact-status');
   const controller = new AbortController();
   const timeout = setTimeout(() => controller.abort(),20000);
   button.disabled = true;
   button.textContent = 'Sending…';
   contactForm.setAttribute('aria-busy','true');
   status.hidden = false;
   status.textContent = 'Sending your message…';
   delete status.dataset.state;
   try {
     const response = await fetch(contactForm.action,{
       method:'POST',body:new FormData(contactForm),
       headers:{'Accept':'application/json','X-GhumnaJaun-Contact':'1'},
       signal:controller.signal
     });
     const wpResult = await response.json(); const result = {ok:wpResult.success===true,message:wpResult.data?.message||'Unable to send. Please try again.'};
     if (!response.ok || result.ok !== true) {
       if (result.errors && window.jQuery?.fn.validate) {
         window.jQuery(contactForm).validate().showErrors(result.errors);
       }
       throw new Error(result.message || 'Your message could not be sent. Please try again later.');
     }
     contactForm.reset();
     if (window.jQuery?.fn.validate) window.jQuery(contactForm).validate().resetForm();
     find('contact-errors').hidden = true;
     status.dataset.state = 'success';
     status.textContent = result.message;
   } catch (error) {
     status.dataset.state = 'error';
     status.textContent = error.name === 'AbortError' ?
       'We could not confirm whether your message was sent. Please wait before trying again, or email hello@ghumnajaun.com.' :
       error instanceof SyntaxError || error instanceof TypeError ?
       'Your message could not be sent. Please try again later or email hello@ghumnajaun.com.' : error.message;
   } finally {
     clearTimeout(timeout);
     sending = false;
     button.disabled = false;
     button.textContent = 'Send message';
     contactForm.removeAttribute('aria-busy');
   }
 };
 if (window.jQuery?.fn.validate) {
   const summary = find('contact-errors');
   const trim = value => value.trim();
   window.jQuery(contactForm).validate({
     errorElement:'span',
     errorClass:'contact-field-error',
     rules:{
       name:{required:true,maxlength:100,normalizer:trim},
       email:{required:true,email:true,maxlength:254,normalizer:trim},
       subject:{required:true},
       body:{required:true,maxlength:3000,normalizer:trim}
     },
     messages:{
       name:{required:'Please enter your name.',maxlength:'Use 100 characters or fewer.'},
       email:{required:'Please enter your email address.',email:'Enter a valid email address.',maxlength:'Use 254 characters or fewer.'},
       subject:{required:'Please choose a topic.'},
       body:{required:'Please enter your message.',maxlength:'Use 3,000 characters or fewer.'}
     },
     highlight(element){element.setAttribute('aria-invalid','true');},
     unhighlight(element){element.removeAttribute('aria-invalid');},
     invalidHandler(){summary.hidden=false;},
     showErrors(){
       this.defaultShowErrors();
       const count = this.numberOfInvalids();
       summary.textContent = count ? `Please check ${count === 1 ? 'the highlighted field' : 'the ' + count + ' highlighted fields'}.` : '';
       if (!count) summary.hidden=true;
     },
     submitHandler(){
       summary.hidden=true;
       sendContactMessage();
       return false;
     }
   });
 } else {
   contactForm.addEventListener('submit',event => {
     event.preventDefault();
     if (contactForm.reportValidity()) sendContactMessage();
   });
 }
} else if (place) {
 detail();
} else if (view === 'detail') {
 root.querySelector('[data-screen="detail"]').hidden = true;
 find('destination-missing').hidden = false;

}
updateLinks();
function setPhoto(image,photo,width){
 if(!image || !photo)return;
 image.alt = photo.alt;
 if(photo.width && photo.height){image.width=photo.width;image.height=photo.height;}
 if(photo.srcset){image.srcset=photo.srcset;image.sizes=width ? width+'px' : '100vw';}
 image.dataset.original = photo.src;
 image.src = thumbnail(photo,width);
}
const bannerPlaces = window.GJ.bannerPlaces;
const bannerPhotos = window.GJ.bannerPhotos || {};
if (view === 'home' && !find('banner-slides').querySelector('img')) find('banner-slides').innerHTML = bannerPlaces.map((name,index) => {
 const photo = bannerPhotos[name] || photos[name];
 return `<div class="swiper-slide"><img src="${thumbnail(photo)}" data-original="${photo.src}"${bannerPhotos[name] ? ` data-fallback="${photos[name].src}"` : ''} alt="" loading="${index === 0 ? 'eager' : 'lazy'}"></div>`;
}).join('');
function loadBannerPhoto(index){
 const image=find('banner-slides')?.children[index]?.querySelector('img');
 if(image && !image.getAttribute('src'))setPhoto(image,bannerPhotos[bannerPlaces[index]] || photos[bannerPlaces[index]]);
}
const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
const bannerElement = find('banner');
const bannerTimer = find('banner-timer');
const banner = bannerElement && window.Swiper ? new window.Swiper(bannerElement, {
 rewind: true,
 effect: 'fade',
 fadeEffect: {crossFade: true},
 speed: motion.matches ? 0 : 550,
 autoplay: motion.matches ? false : {delay: 6000, disableOnInteraction: false},
 pagination: {el: bannerElement.querySelector('.banner-pagination'), clickable: true},
 a11y: {enabled: true, paginationBulletMessage: 'Show featured destination {{index}}'},
 on: {
   slideChange(slider) {
     loadBannerPhoto(slider.realIndex);
     setTimeout(()=>loadBannerPhoto((slider.realIndex+1)%bannerPlaces.length),1000);
     const name = bannerPlaces[slider.realIndex];
     const label = find('banner-place');
     label.dataset.place = name;
     label.textContent = name + ' ↗';
     decorateArrows(label);
     label.href = pageUrl('destination.html',{place:name});
     bannerTimer.style.setProperty('--elapsed',0);
   },
   autoplayTimeLeft(_slider,_timeLeft,remaining) {
     bannerTimer.style.setProperty('--elapsed',Math.max(0,Math.min(1,1 - remaining)));
   }
 }
}) : null;
if (banner && !motion.matches) bannerTimer.hidden = false;
if(banner)setTimeout(()=>loadBannerPhoto(1),3000);
const aos = motion.matches ? null : window.AOS;
function refreshReveals(){
 if (!aos) return;
 root.querySelectorAll('[data-reveal]:not([data-aos])').forEach(element => {
   element.dataset.aos = element.dataset.reveal;
 });
 requestAnimationFrame(() => aos.refreshHard());
}
if (aos) {
 root.classList.add('aos-enabled');
 refreshReveals();
 aos.init({once: true, duration: 480, offset: 35, easing: 'ease-out-cubic', disableMutationObserver: true});
}

const creditList = find('credits');
if (creditList) creditList.innerHTML = [...new Map([...Object.values(photos),...Object.values(bannerPhotos)].filter(photo=>photo.author && (photo.commons||photo.source)).map(photo=>[photo.src,photo])).values()].map(photo => {
 const license = photo.license === 'PD' ? 'Public domain' : photo.license === 'BY'
  ? '<a href="https://creativecommons.org/licenses/by/4.0/">CC BY 4.0</a>'
  : '<a href="https://creativecommons.org/licenses/by-sa/' + photo.license + '/">CC BY-SA ' + photo.license + '</a>';
 const attribution = photo.license ? `by ${photo.author} · ${license}` : `· Photo source: ${photo.author}`;
 return `<li>
   <span class="credit-thumbnail"><img src="${thumbnail(photo,120)}" data-original="${photo.src}" width="64" height="48" alt="" loading="lazy" decoding="async"></span>
   <div><a href="${photo.commons || photo.source}">${photo.alt}</a> ${attribution}</div>
 </li>`;
}).join('');
root.addEventListener('error', event => {
 const image = event.target;
 if (image.tagName !== 'IMG') return;
 const original = image.dataset.original;
 if (original && image.src !== original) {
   delete image.dataset.original;
   image.src = original;
 } else if (image.dataset.fallback && image.src !== image.dataset.fallback) {
   delete image.dataset.original;
   image.src = image.dataset.fallback;
   delete image.dataset.fallback;
 } else {
   image.hidden = true;
 }
},true);
decorateArrows(root);
initializeCookieConsent();
function decorateArrows(container){
 const icon = '<svg viewBox="0 0 24 24" focusable="false"><path d="M4 2h18v18h-4V8.8L4.8 22 2 19.2 15.2 6H4z"/></svg>';
 container.querySelectorAll('.link-arrow:empty').forEach(arrow=>arrow.innerHTML=icon);
 const walker = document.createTreeWalker(container,NodeFilter.SHOW_TEXT);
 const nodes = [];
 while (walker.nextNode()) {
   if (walker.currentNode.textContent.includes('↗')) nodes.push(walker.currentNode);
 }
 nodes.forEach(node => {
   if (node.textContent === '↗' && node.parentElement.classList.contains('link-arrow')) {
     node.parentElement.innerHTML = icon;
     return;
   }
   const fragment = document.createDocumentFragment();
   node.textContent.split('↗').forEach((part,index) => {
     if (index) {
       const arrow = document.createElement('span');
       arrow.className = 'link-arrow';
       arrow.setAttribute('aria-hidden','true');
       arrow.innerHTML = icon;
       fragment.append(arrow);
     }
     fragment.append(document.createTextNode(part));
   });
   node.replaceWith(fragment);
 });
}
function initializeCookieConsent(){
 const storageKey = 'ghumna-jaun-consent';
 const lifetime = 180 * 24 * 60 * 60 * 1000;
 function readChoice(){
   try {
     const saved = JSON.parse(localStorage.getItem(storageKey));
     if (saved?.version === 1 && typeof saved.embeds === 'boolean' &&
         Number.isFinite(saved.savedAt) && saved.savedAt <= Date.now() && Date.now() - saved.savedAt < lifetime) return saved;
   } catch {}
   return null;
 }
 let choice = readChoice();
 root.insertAdjacentHTML('beforeend',`
   <section class="cookie-banner" id="gj-cookie-banner" aria-labelledby="gj-cookie-title" hidden>
     <div class="cookie-banner-copy"><h2 id="gj-cookie-title">Your privacy choices</h2>
       <p>Optional maps and videos connect to OpenStreetMap and YouTube. Choose whether to load them. <a href="${window.GJ.pages['privacy-policy.html']}">Privacy Policy</a></p>
     </div>
     <div class="cookie-actions"><button type="button" data-embed-consent="reject">Reject</button><button type="button" data-embed-consent="accept">Accept</button></div>
   </section>
   <dialog class="search-dialog cookie-dialog" id="gj-cookie-dialog" aria-labelledby="gj-cookie-dialog-title">
     <div class="search-dialog-heading"><h2 id="gj-cookie-dialog-title">Cookie preferences</h2>
       <button class="search-close" type="button" id="gj-cookie-close" aria-label="Close cookie preferences"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
     </div>
     <p>Choose whether embedded OpenStreetMap maps and YouTube videos can load. These providers receive connection information and may use their own cookies or storage.</p>
     <p>Your theme preference is stored separately. Photos, fonts and site files also load from their providers. <a href="${window.GJ.pages['privacy-policy.html']}">Read our Privacy Policy</a>.</p>
     <p id="gj-cookie-current" role="status"></p>
     <div class="cookie-actions"><button type="button" data-embed-consent="reject" autofocus>Reject</button><button type="button" data-embed-consent="accept">Accept</button></div>
   </dialog>`);
 const banner = find('cookie-banner');
 const dialog = find('cookie-dialog');
 const bannerMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
 let preferencesAnimation = null;
 let preferencesClosing = false;
 function openPreferences(){
   if (dialog.open) return;
   dialog.showModal();
   if (!bannerMotion.matches) {
     preferencesAnimation = dialog.animate([{opacity:0},{opacity:1}],{duration:220,easing:'ease-out'});
   }
 }
 async function closePreferences(){
   if (!dialog.open || preferencesClosing) return;
   preferencesClosing = true;
   const opacity = getComputedStyle(dialog).opacity;
   preferencesAnimation?.cancel();
   if (!bannerMotion.matches) {
     dialog.classList.add('is-closing');
     preferencesAnimation = dialog.animate([{opacity},{opacity:0}],{duration:220,easing:'ease-out',fill:'forwards'});
     await preferencesAnimation.finished.catch(() => {});
   }
   dialog.close();
   dialog.classList.remove('is-closing');
   preferencesAnimation?.cancel();
   preferencesClosing = false;
 }
 let bannerTarget = false;
 let bannerTransition = 0;
 let bannerAnimation = null;
 const updateBannerSpace = () => root.style.setProperty('--cookie-banner-height',banner.hidden ? '0px' : banner.offsetHeight + 'px');
 async function updateBannerVisibility(){
   const show = choice === null;
   if (show === bannerTarget) return;
   bannerTarget = show;
   const transition = ++bannerTransition;
   const wasHidden = banner.hidden;
   const style = getComputedStyle(banner);
   const opacity = style.opacity;
   const transform = style.transform;
   bannerAnimation?.cancel();
   banner.hidden = false;
   banner.inert = !show;
   updateBannerSpace();
   if (!bannerMotion.matches) {
     const frames = show ? [
       {opacity:wasHidden ? 0 : opacity,transform:wasHidden ? 'translateY(100%)' : transform},
       {opacity:1,transform:'translateY(0)'}
     ] : [{opacity,transform},{opacity:0,transform}];
     bannerAnimation = banner.animate(frames,{
       duration:show ? 280 : 180,
       easing:'ease-out',
       fill:'forwards'
     });
     await bannerAnimation.finished.catch(() => {});
     if (transition !== bannerTransition) return;
   }
   banner.hidden = !show;
   banner.inert = false;
   bannerAnimation?.cancel();
   updateBannerSpace();
 }
 function applyChoice(){
   updateBannerVisibility();
   find('cookie-current').textContent = choice === null ? 'No choice saved yet.' :
     choice.embeds ? 'Current choice · Optional maps and videos accepted.' : 'Current choice · Optional maps and videos rejected.';
   root.querySelectorAll('.consent-embed').forEach(container => {
     const frame = container.querySelector('iframe[data-consent-src]');
     const allowed = choice?.embeds === true;
     container.querySelector('.consent-placeholder').hidden = allowed;
     frame.hidden = !allowed;
     if (allowed && !frame.hasAttribute('src')) frame.src = frame.dataset.consentSrc;
     if (!allowed) frame.removeAttribute('src');
   });
   updateBannerSpace();
 }
 root.querySelectorAll('[data-cookie-preferences]').forEach(button => {
   button.hidden = false;
   button.addEventListener('click',openPreferences);
 });
 root.querySelectorAll('[data-embed-consent]').forEach(button => button.addEventListener('click',() => {
   choice = {version:1,embeds:button.dataset.embedConsent === 'accept',savedAt:Date.now()};
   try { localStorage.setItem(storageKey,JSON.stringify(choice)); } catch {}
   applyChoice();
   closePreferences();
 }));
 find('cookie-close').addEventListener('click',closePreferences);
 dialog.addEventListener('cancel',event => {
   event.preventDefault();
   closePreferences();
 });
 dialog.addEventListener('click',event => {
   if (event.target !== dialog) return;
   const bounds = dialog.getBoundingClientRect();
   if (event.clientX < bounds.left || event.clientX > bounds.right ||
       event.clientY < bounds.top || event.clientY > bounds.bottom) closePreferences();
 });
 window.addEventListener('storage',event => {
   if (event.key === storageKey || event.key === null) {
     choice = readChoice();
     applyChoice();
   }
 });
 new ResizeObserver(updateBannerSpace).observe(banner);
 applyChoice();
}
})();
