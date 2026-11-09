import json, re, os, sys, subprocess
SRC='/home/claude/rolmax/projekt/nh/specs/nh_specs.json'
SITE=sys.argv[1]
d=json.load(open(SRC))
items=json.loads(subprocess.check_output(['node','-e',"global.window={};require('%s/data.js');console.log(JSON.stringify(window.RX))"%SITE]))
out=os.path.join(SITE,'assets','specs'); os.makedirs(out,exist_ok=True)
def clean(h):
    h=re.sub(r'<(p|li|ul|ol)>\s*</\1>','',h or '').strip()
    return h
def txt(h): return re.sub(r'\s+',' ',re.sub(r'<[^>]+>',' ',h or '')).strip()
made={}
for c in items:
  for it in c['items']:
    m=it.get('man') or ''
    if '/produkty/' not in m: continue
    k=m.split('/produkty/')[1]; v=d.get(k)
    if not v: continue
    o={'src':m}
    if v.get('hero'): o['hero']={'d':clean(v['hero']['d']),'img':v['hero']['img']}
    o['ov']=[{'t':(x.get('t') or '').strip(),'d':clean(x['d']),'img':x['img']} for x in v.get('ov') or [] if txt(x['d']) or x['img']]
    o['feat']=[{'t':(f.get('t') or '').strip(),'e':f['e'],'d':clean(f['d']),'img':f['img'],'sub':[{'t':(s.get('t') or '').strip(),'d':clean(s['d']),'img':s['img']} for s in f['sub'] if txt(s['d']) or s['t']]} for f in v.get('feat') or [] if f['t'] or txt(f['d'])]
    o['models']=[{'t':x['t'],'img':x['img'],'ss':[[(a or '').strip(),b] for a,b in x['ss']]} for x in v.get('models') or []]
    if v.get('spec'):
        s=v['spec']; o['spec']={'cols':s['cols'],'notes':clean(s['notes']),'g':[[(n or '').strip(),rows] for n,rows in s['g'] if rows]}
    o['br']=[b for b in v.get('br') or []]
    if not (o['models'] or o.get('spec') or o['feat'] or o['ov']): continue
    json.dump(o,open(os.path.join(out,it['s']+'.json'),'w'),ensure_ascii=False,separators=(',',':'))
    made[it['s']]=[len(o['models']),len(o['spec']['cols']) if o.get('spec') else 0]
json.dump(made,open(os.path.join(out,'index.json'),'w'),separators=(',',':'))
print(len(made),'files'); print(made)
