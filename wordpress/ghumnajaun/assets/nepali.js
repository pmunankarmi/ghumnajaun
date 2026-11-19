/* Localize visible text only. Keep data attributes, form values and routing keys stable. */
(() => {
 'use strict';
 const dictionary = window.GJ_NE;
 if (!dictionary) return;
 const translate = input => {
   const key = input.trim();
   let value = dictionary[key];
   if (!value && key.includes(' · ')) value = key.split(' · ').map(part => dictionary[part] || part).join(' · ');
   if (!value && / ↗$/.test(key)) value = translate(key.slice(0,-2)) + ' ↗';
   if (!value) {
     let match;
     if ((match = key.match(/^(\d+) (places to see|place to see|places|place)$/))) value = match[2].includes('to see') ? 'हेर्न मिल्ने ' + match[1] + ' ठाउँ' : match[1] + ' ठाउँ';
     else if ((match = key.match(/^(\d+) matching destinations?$/))) value = match[1] + (Number(match[1]) === 1 ? ' मिल्दो गन्तव्य' : ' मिल्दा गन्तव्य');
     else if ((match = key.match(/^Sightseeing in (.+)$/))) value = translate(match[1]) + 'मा घुमघाम';
     else if ((match = key.match(/^From (.+)$/))) value = translate(match[1]) + 'बाट';
     else if ((match = key.match(/^Search results for “(.+)”$/))) value = '“' + match[1] + '” को खोज नतिजा';
     else if ((match = key.match(/^Under (\d+) km away$/))) value = match[1] + ' किमिभन्दा कम दूरीमा';
     else if ((match = key.match(/^(.+) km away$/))) value = match[1] + ' किमि टाढा';
     else if ((match = key.match(/^(.+) loads after you accept optional embedded content\.$/))) value = translate(match[1]) + ' वैकल्पिक बाह्य सामग्री स्वीकार गरेपछि लोड हुन्छ।';
     else if ((match = key.match(/^Get directions from (.+) in Google Maps$/))) value = 'Google Maps मा ' + translate(match[1]) + 'बाट जाने बाटो हेर्नुहोस्';
     else if ((match = key.match(/^About (\d+) km$/))) value = 'करिब ' + match[1] + ' किमि';
     else if ((match = key.match(/^Show featured destination (\d+)$/))) value = 'विशेष गन्तव्य ' + match[1] + ' देखाउनुहोस्';
     else if ((match = key.match(/^Show destination photo (\d+)$/))) value = 'गन्तव्यको तस्बिर ' + match[1] + ' देखाउनुहोस्';
     else if ((match = key.match(/^View larger photo of (.+)$/))) value = translate(match[1]) + 'को ठूलो तस्बिर हेर्नुहोस्';
     else if ((match = key.match(/^Show photo of (.+)$/))) value = translate(match[1]) + 'को तस्बिर देखाउनुहोस्';
     else if ((match = key.match(/^View (.+) on map\s*$/))) value = translate(match[1]) + ' नक्सामा हेर्नुहोस्';
     else if ((match = key.match(/^(.+) photos$/))) value = translate(match[1]) + 'का तस्बिरहरू';
   }
   return value ? input.match(/^\s*/u)[0] + value + input.match(/\s*$/u)[0] : input;
 };
 const ignored = node => node.parentElement?.closest('script,style,textarea,[contenteditable],#wpadminbar');
 function localize(node) {
   if (node.nodeType === Node.TEXT_NODE) {
     if (!ignored(node)) {
       const value = translate(node.nodeValue);
       if (value !== node.nodeValue) node.nodeValue = value;
     }
     return;
   }
   if (node.nodeType !== Node.ELEMENT_NODE && node.nodeType !== Node.DOCUMENT_FRAGMENT_NODE) return;
   if (node.matches?.('script,style,textarea,[contenteditable],#wpadminbar')) return;
   if (node.tagName === 'OPTION' && !node.hasAttribute('value')) node.setAttribute('value',node.textContent);
   for (const attribute of ['alt','title','placeholder','aria-label']) {
     if (node.hasAttribute?.(attribute)) {
       const original = node.getAttribute(attribute), value = translate(original);
       if (value !== original) node.setAttribute(attribute,value);
     }
   }
   if (node.tagName === 'TEMPLATE') localize(node.content);
   for (const child of [...node.childNodes]) localize(child);
 }
 localize(document.body);
 const observer = new MutationObserver(records => {
   for (const record of records) {
     if (record.type === 'characterData') localize(record.target);
     else if (record.type === 'attributes') localize(record.target);
     else for (const node of record.addedNodes) localize(node);
   }
 });
 observer.observe(document.body,{subtree:true,childList:true,characterData:true,attributes:true,attributeFilter:['alt','title','placeholder','aria-label']});
})();
