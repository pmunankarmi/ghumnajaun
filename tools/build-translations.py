"""Build WordPress-native gettext catalogs from the reviewed translation sources."""
from pathlib import Path
import json
import struct

root = Path(__file__).resolve().parents[1]
labels = json.loads((root / 'tools/data/ne-translations.json').read_text())
paragraphs = json.loads((root / 'tools/data/ne-editorial-paragraphs.json').read_text())
messages = {'': 'Project-Id-Version: Ghumna Jaun\nLanguage: ne_NP\nContent-Type: text/plain; charset=UTF-8\nPlural-Forms: nplurals=2; plural=(n != 1);\n'}
messages.update(labels)
messages.update({'editorial-paragraph\x04' + key: value for key, value in paragraphs.items()})
keys = sorted(messages)
originals = [key.encode('utf8') for key in keys]
translations = [messages[key].encode('utf8') for key in keys]
count = len(keys)
strings_offset = 28 + count * 16
original_blob = b'\0'.join(originals) + b'\0'
translated_blob = b'\0'.join(translations) + b'\0'
original_table = []
translation_table = []
offset = strings_offset
for value in originals:
    original_table.append((len(value), offset))
    offset += len(value) + 1
offset = strings_offset + len(original_blob)
for value in translations:
    translation_table.append((len(value), offset))
    offset += len(value) + 1
header = struct.pack('<7I', 0x950412de, 0, count, 28, 28 + count * 8, 0, strings_offset)
tables = b''.join(struct.pack('<2I', *entry) for entry in original_table + translation_table)
languages = root / 'wordpress/ghumnajaun/languages'
languages.mkdir(exist_ok=True)
(languages / 'ne_NP.mo').write_bytes(header + tables + original_blob + translated_blob)
quote = lambda value: json.dumps(value, ensure_ascii=False)
po = ['# Reviewed Nepali translations for Ghumna Jaun.\n']
for key in keys:
    if '\x04' in key:
        context, key_id = key.split('\x04', 1)
        po.append('msgctxt ' + quote(context))
    else:
        key_id = key
    po.extend(['msgid ' + quote(key_id), 'msgstr ' + quote(messages[key]), ''])
(languages / 'ne_NP.po').write_text('\n'.join(po))
# The browser uses the same reviewed labels for interactive controls.
(root / 'wordpress/ghumnajaun/assets/ne-dictionary.js').write_text('window.GJ_NE=' + json.dumps(labels, ensure_ascii=False, separators=(',', ':')) + ';\n')
print(f'Built Nepali catalog: {len(labels)} labels and {len(paragraphs)} linked paragraphs')
