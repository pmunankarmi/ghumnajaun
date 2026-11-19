#!/usr/bin/env python3
"""Build a resumable English→Nepali dictionary from a WordPress export.

Translates visible HTML text only; markup, URLs, IDs and units are preserved.
Run export-nepali.php through WP-CLI first. Requires network access to Google Translate.
"""
import concurrent.futures
import html
from html.parser import HTMLParser
import json
from pathlib import Path
import re
import time
import urllib.parse
import urllib.request

ROOT = Path(__file__).resolve().parents[1]
CACHE = ROOT / 'tools/data/ne-translations.json'
SOURCE = Path('/tmp/gj-ne-source.json')
SCHEMA = json.loads(Path('/tmp/gj-ne-schema.json').read_text())
source = json.loads(SOURCE.read_text())
texts = set()

def eligible(text):
    return bool(re.search('[A-Za-z]{2}', text)) and not any(x in text for x in ('${','=>','</','document.')) and not re.match(r'^(https?://|mailto:|[\w.+-]+@)', text) and not re.fullmatch(r'[\d\s.,:–—/°%+−-]+', text)

class VisibleText(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.skip = 0
    def handle_starttag(self, tag, attrs):
        if tag in ('script','style'): self.skip += 1
        if not self.skip:
            for name,value in attrs:
                if name in ('alt','title','placeholder','aria-label') and value: add(value)
    def handle_endtag(self, tag):
        if tag in ('script','style'): self.skip = max(0,self.skip-1)
    def handle_data(self, data):
        if not self.skip: add(data)

def add(text):
    text = html.unescape(text).strip()
    if eligible(text): texts.add(text)

def collect(text):
    if not isinstance(text,str) or not text: return
    if re.search(r'<[a-zA-Z][^>]*>', text): VisibleText().feed(text)
    else: add(text)

def field_info(key):
    return SCHEMA.get(key) or SCHEMA.get('field_gj_'+key)

def fields(items):
    if isinstance(items,list):
        for row in items: fields(row)
    elif isinstance(items,dict):
        for key,value in items.items():
            info = field_info(key)
            if isinstance(value,(dict,list)): fields(value)
            elif info and info['type'] in ('text','textarea','wysiwyg') and not key.endswith('_ne') and info['name'] not in ('photo_author','photo_license'):
                if info['name'] not in ('stage_link_url','stop_map','transport_pin_query'): collect(value)

for post in source['posts']:
    for key in ('title','content','excerpt'): collect(post[key])
    fields(post['fields'])
for term in source['terms']:
    collect(term['name']); collect(term['description']); fields(term['fields'])
fields(source['options'])
for media in source.get('media',[]):
    for key in ('alt','caption','description'): collect(media[key])
seed = json.loads((ROOT/'wordpress/ghumnajaun/data/content.json').read_text())
for photo in seed.get('photos',{}).values(): collect(photo.get('alt',''))
for menu in source['menus']:
    for item in menu['items'] or []: collect(item['title'])
for path in (ROOT/'wordpress/ghumnajaun/views').glob('*.html'): collect(path.read_text())
# Include literal HTML and human-readable labels from the renderer and runtime.
for path in [* (ROOT/'wordpress/ghumnajaun').glob('*.php'), ROOT/'wordpress/ghumnajaun/includes/design.php',ROOT/'wordpress/ghumnajaun/includes/contact.php',ROOT/'wordpress/ghumnajaun/assets/design.js']:
    content = path.read_text()
    for fragment in re.findall(r'`([^`]+)`',content):
        if '<' in fragment: collect(fragment)
    for quote,text in re.findall(r'''(['"])((?:\\.|(?!\1).)*?)\1''',content):
        if re.search(r'[{}=$;\\]|\b(?:function|const|document|return)\b',text): continue
        if '<' in text: collect(text)
        elif ' ' in text and re.fullmatch(r"[A-Za-z0-9 ,.!?:’'&/()↗·–—%-]+",text): collect(text)

cache = json.loads(CACHE.read_text()) if CACHE.exists() else {}
# Editorial names and frequently used interface copy.
defaults = {
 'Ghumna Jaun':'घुम्न जाऔँ','Where shall we go today?':'आज कहाँ घुम्न जाने?',
 'DAY TRIPS FROM KATHMANDU VALLEY':'काठमाडौं उपत्यकाबाट एकदिने घुमघाम',
 'Kathmandu':'काठमाडौं','Lalitpur':'ललितपुर','Bhaktapur':'भक्तपुर','Banepa':'बनेपा','Panauti':'पनौती','Dhulikhel':'धुलिखेल','Kirtipur':'कीर्तिपुर',
 'Kathmandu Fun Park':'काठमाडौं फन पार्क','Natural History Museum':'प्राकृतिक विज्ञान सङ्ग्रहालय',
 'Jump KTM, Jhamsikhel':'जम्प केटीएम, झम्सिखेल','Whoopee Land':'हुप्पी ल्यान्ड','Kathmandu Fun Valley':'काठमाडौं फन भ्याली',
 'Bagh Bhairab Temple':'बाघभैरव मन्दिर','Indrayani Temple (Luti Ajima)':'इन्द्रायणी मन्दिर (लुती अजिमा)',
 'Mhepi Ajima Temple':'म्हेपी अजिमा मन्दिर','Bijeshwari Temple':'बिजेश्वरी मन्दिर','Santaneshwor Mahadev Temple':'सन्तानेश्वर महादेव मन्दिर',
 'Chonga Ganesh Temple':'चोङ्गा गणेश मन्दिर','Surya Binayak Temple':'सूर्यविनायक मन्दिर','Tribhuvan Park':'त्रिभुवन पार्क','Balaju Baisdhara Park':'बालाजु बाइसधारा पार्क',
 'Manjushree Park':'मञ्जुश्री पार्क','Sangdaha Picnic Spot':'साँगदह पिकनिक स्थल','1,000 Steps, Dhulikhel':'हजार सिँढी, धुलिखेल',
 'Central Zoo':'सदर चिडियाखाना','Simulation Science Center':'सिमुलेसन साइन्स सेन्टर','National Museum of Nepal':'नेपाल राष्ट्रिय सङ्ग्रहालय',
 'Adinath Lokeshwar Temple':'आदिनाथ लोकेश्वर मन्दिर','Bajra Barahi Temple':'वज्रवाराही मन्दिर','Nil Barahi Temple':'नीलबाराही मन्दिर','Chandeshwori Temple':'चण्डेश्वरी मन्दिर',
 'Matsya Narayan Temple':'मत्स्यनारायण मन्दिर','Doleshwor Mahadev Temple':'डोलेश्वर महादेव मन्दिर','Ashapuri Mahadev Temple':'आशापुरी महादेव मन्दिर','Ichangu Narayan Temple':'इचङ्गुनारायण मन्दिर',
 'Shikali Temple':'सिकाली मन्दिर','Rudrayani Temple':'रुद्रायणी मन्दिर','Karya Binayak Temple':'कार्यविनायक मन्दिर','Rato Machhindranath Temple':'रातो मच्छिन्द्रनाथ मन्दिर','Jal Binayak Temple':'जलविनायक मन्दिर',
 'Nagarkot':'नगरकोट','Sankhu & Bajrayogini':'साँखु र बज्रयोगिनी','Four Binayak Ganesh Temples':'चार विनायक गणेश मन्दिर','Chitlang':'चित्लाङ','Kulekhani':'कुलेखानी','Bheda Farm':'भेडा फार्म',
 'Mahalaxmi Temple, Telkot':'महालक्ष्मी मन्दिर, तेलकोट','Kakani':'ककनी','Namo Buddha':'नमोबुद्ध','Budhanilkantha':'बूढानीलकण्ठ','White Gumba':'सेतो गुम्बा',
 'Chandragiri Hills':'चन्द्रागिरि','Godawari Botanical Garden':'गोदावरी वनस्पति उद्यान','Kathmandu Durbar Square':'काठमाडौं दरबार क्षेत्र','Patan Durbar Square':'पाटन दरबार क्षेत्र','Bhaktapur Durbar Square':'भक्तपुर दरबार क्षेत्र',
 'Pharping':'फर्पिङ','Dakshinkali Temple':'दक्षिणकाली मन्दिर','Swayambhunath':'स्वयम्भूनाथ','Changu Narayan Temple':'चाँगुनारायण मन्दिर',
 'Explore':'घुम्ने ठाउँ','Explore places':'घुम्ने ठाउँहरू','Blog':'लेखहरू','About us':'हाम्रो बारेमा','Contact us':'सम्पर्क गर्नुहोस्','Contact':'सम्पर्क',
 'Photo credits':'तस्बिरको श्रेय','Privacy Policy':'गोपनीयता नीति','Terms of Use':'प्रयोगका सर्तहरू','Disclaimer':'अस्वीकरण',
 'Travel guides':'यात्रा मार्गदर्शन','Dharmik':'धार्मिक','Family':'परिवार','Friends':'साथीहरू','Kids':'बालबालिका','Couple':'जोडी','Group':'समूह','Picnic Spot':'पिकनिक स्थल','All':'सबै',
 'Honeymoon':'मधुमास','Honeysun':'नवविवाहितको एकदिने घुमघाम','Scenic dining':'दृश्यसँगै खानपान',
 'Morning':'बिहान','Midday':'मध्याह्न','Afternoon':'दिउँसो','Evening':'साँझ','Opening hours':'खुल्ने समय','Suggested visit':'घुम्न लाग्ने अनुमानित समय',
 'Check before you go':'जानुअघि जानकारी लिनुहोस्','Before you go':'जानुअघि','By bus':'बसबाट','By car or taxi':'कार वा ट्याक्सीबाट',
 'BEFORE THE VISIT':'घुम्न जानुअघि','DURING THE VISIT':'घुम्ने क्रममा','AFTER THE VISIT':'घुमेर फर्केपछि',
 'Search':'खोज्नुहोस्','Search places':'ठाउँ खोज्नुहोस्','Close search':'खोज बन्द गर्नुहोस्','Place, area or activity':'ठाउँ, क्षेत्र वा गतिविधि',
 'View on map':'नक्सामा हेर्नुहोस्','Read guide':'मार्गदर्शन पढ्नुहोस्','Visit website':'वेबसाइट हेर्नुहोस्','Hotel website':'होटलको वेबसाइट',
}
for key,value in defaults.items(): cache.setdefault(key,value)
missing=sorted(texts-cache.keys())
print(f'{len(texts)} unique strings, {len(missing)} require translation',flush=True)

def translate(text):
    for attempt in range(5):
        try:
            url='https://translate.googleapis.com/translate_a/single?'+urllib.parse.urlencode({'client':'gtx','sl':'en','tl':'ne','dt':'t','q':text})
            request=urllib.request.Request(url,headers={'User-Agent':'Mozilla/5.0'})
            result=json.load(urllib.request.urlopen(request,timeout=40))
            value=''.join(part[0] or '' for part in result[0])
            if not value: raise ValueError('Empty response')
            return value
        except Exception:
            if attempt==4: raise
            time.sleep(2**attempt)

def batch_translate(batch):
    if len(batch)==1:return [(batch[0],translate(batch[0]))]
    translated=translate('\n'.join(batch)).split('\n')
    if len(translated)==len(batch) and all(t.strip() for t in translated):return list(zip(batch,[t.strip() for t in translated]))
    return [(text,translate(text)) for text in batch]

batches=[];batch=[];size=0
for text in missing:
    if size+len(text)>2300 or len(batch)>=15 or '\n' in text:
        if batch:batches.append(batch)
        batch=[];size=0
    if '\n' in text:batches.append([text]);continue
    batch.append(text);size+=len(text)+1
if batch:batches.append(batch)
with concurrent.futures.ThreadPoolExecutor(max_workers=4) as pool:
    futures=[pool.submit(batch_translate,batch) for batch in batches]
    for i,future in enumerate(concurrent.futures.as_completed(futures),1):
        for original,translated in future.result():cache[original]=translated
        CACHE.write_text(json.dumps(cache,ensure_ascii=False,indent=2)+'\n')
        if i%10==0 or i==len(futures):print(f'{i}/{len(futures)} batches complete; {len(cache)} translations',flush=True)
CACHE.write_text(json.dumps(cache,ensure_ascii=False,indent=2)+'\n')
print('Translation dictionary saved.',flush=True)
