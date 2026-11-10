#!/usr/bin/env node
// Cria a pasta de um convite novo a partir de um convite existente, sem nunca sobrescrever nada.
//
// Uso:
//   node scripts/novo-convite.mjs --nome "Maria Eduarda" --data 2027-05-20 --modelo 2027-02-06-kimberly-sophia
//
// Resultado: site/convites/2027-05-20-maria-eduarda/  ->  https://<dominio>/convites/2027-05-20-maria-eduarda/
// Se já existir um convite com o mesmo nome e data, o script para com erro; use --sufixo para diferenciar
// (ex.: --sufixo 2  ->  2027-05-20-maria-eduarda-2).

import { cp, readFile, writeFile, access } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const invitesDir = path.join(root, 'site', 'convites');

const args = Object.fromEntries(
  process.argv.slice(2).reduce((acc, cur, i, arr) => {
    if (cur.startsWith('--')) acc.push([cur.slice(2), arr[i + 1]]);
    return acc;
  }, []),
);

const fail = (msg) => { console.error(`✖ ${msg}`); process.exit(1); };
if (!args.nome) fail('Informe --nome "Nome da aniversariante"');
if (!/^\d{4}-\d{2}-\d{2}$/.test(args.data || '')) fail('Informe --data no formato AAAA-MM-DD (data do evento)');
if (!args.modelo) fail('Informe --modelo <pasta de um convite existente em site/convites>');

const slug = args.nome
  .normalize('NFD').replace(/[̀-ͯ]/g, '')
  .toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
const folder = [args.data, slug, args.sufixo].filter(Boolean).join('-');
const target = path.join(invitesDir, folder);
const model = path.join(invitesDir, args.modelo);

const exists = (p) => access(p).then(() => true, () => false);
if (!(await exists(model))) fail(`Modelo não encontrado: site/convites/${args.modelo}`);
if (await exists(target)) fail(`Já existe site/convites/${folder}. Use --sufixo 2 (ou outro) para criar um diferente.`);

await cp(model, target, { recursive: true, errorOnExist: true, force: false });

const cfgPath = path.join(target, 'config.js');
let cfg = await readFile(cfgPath, 'utf8');
cfg = cfg
  .replace(/aniversariante:\s*".*?"/, `aniversariante: ${JSON.stringify(args.nome)}`)
  .replace(/whatsapp:\s*".*?"/, 'whatsapp: ""');
await writeFile(cfgPath, cfg);

console.log(`✔ Criado site/convites/${folder}/`);
console.log('  Agora: troque as imagens/vídeo em assets/, ajuste config.js (WhatsApp, endereço)');
console.log('  e o <title>/descrição no index.html. Depois registre o convite em site/convites/README.md');
console.log('  e envie a pasta para public_html/convites/ na Hostinger.');
console.log(`  Endereço: /convites/${folder}/`);
