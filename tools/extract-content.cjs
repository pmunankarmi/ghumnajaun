const fs=require('fs'),vm=require('vm');
const src=fs.readFileSync('design/prototype.js','utf8');
function block(name,next){return src.slice(src.indexOf('const '+name+' ='),src.indexOf(next,src.indexOf('const '+name+' =')));}
let code='function mapSearch(q){return "https://www.google.com/maps/search/?api=1&query="+encodeURIComponent(q)};'+block('photos','const picnicCategory')+block('places','function distanceFrom')+block('citySightseeing','const find =')+block('destinationGalleries','const photoFor')+block('tripDetails','function standardTripDetails');
let b=src.indexOf('const blogPosts ='); let end=src.indexOf('\n];',b)+3; code+=src.slice(b,end)+block('bannerPhotos',"if (view === 'home') find('banner-slides')");
code+=';JSON.stringify({photos,places,citySightseeing,dayPlans,placePoints,destinationGuides,destinationGalleries,tripDetails,blogPosts,bannerPhotos,entryFees,entryFeeSource})';
fs.writeFileSync('tools/data/legacy-theme/content.json',vm.runInNewContext(code,{}, {timeout:2000}));
