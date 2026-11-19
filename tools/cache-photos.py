import json,urllib.request,pathlib,hashlib,concurrent.futures,time
D=json.load(open('wordpress/ghumnajaun/data/content.json'))
CACHE=pathlib.Path('/tmp/ghumnajaun-media');CACHE.mkdir(exist_ok=True)
def download(item):
 name,p=item;url=p['src']
 if not url.startswith('https://'):return
 target=CACHE/(hashlib.sha256(url.encode()).hexdigest()+'.'+url.split('?')[0].rsplit('.',1)[-1])
 if target.exists():return
 for attempt in range(2):
  try:
   request=urllib.request.Request(url,headers={'User-Agent':'GhumnaJaun/1.0 (WordPress migration; https://ghumnajaun.com)'})
   with urllib.request.urlopen(request,timeout=35)as r:
    data=r.read(20000001)
   if len(data)>20000000:raise ValueError('Image exceeds 20 MB')
   target.write_bytes(data);return
  except Exception as e:
   if attempt:print(name+': '+str(e),flush=True)
   else:time.sleep(2)
with concurrent.futures.ThreadPoolExecutor(max_workers=3)as pool:list(pool.map(download,D['photos'].items()))
print('Cached',len(list(CACHE.iterdir())),'photos')
