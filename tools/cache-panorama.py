import json,urllib.request,pathlib,hashlib
x=json.load(open('wordpress/ghumnajaun/data/content.json'))['photos']['Bhaktapur Durbar Square Panorama'];url=x['src'];thumb=url.replace('/commons/','/commons/thumb/')+'/1280px-'+url.rsplit('/',1)[1]
req=urllib.request.Request(thumb,headers={'User-Agent':'GhumnaJaun/1.0 (https://ghumnajaun.com)'})
with urllib.request.urlopen(req,timeout=35)as r:data=r.read()
pathlib.Path('/tmp/ghumnajaun-media',hashlib.sha256(url.encode()).hexdigest()+'.'+url.rsplit('.',1)[1]).write_bytes(data)
print('Cached panorama thumbnail')
