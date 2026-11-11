# Ferramentas de vídeo dos convites

Scripts (Python + OpenCV + ffmpeg) que montam o vídeo de um convite a partir de um **vídeo base** (modelo comprado)
e das **artes do cliente**. Foram usados no convite `site/convites/2027-02-06-kimberly-sophia/` e reproduzem o mesmo vídeo.

> O repositório é público: vídeos base, músicas e arquivos intermediários **não vão para o git**
> (`modelos/**/*.mp4|mp3|wav`, `build/`, `clientes/` estão no `.gitignore`). Guarde-os em `tools/convites/modelos/<modelo>/` só na sua máquina.

## Instalar

```bash
pip install -r tools/convites/requirements.txt   # + ffmpeg/ffprobe no sistema
```

## Fluxo de um convite novo (modelo baile-de-inverno)

1. **Pasta do site:** `node scripts/novo-convite.mjs --nome "Nome" --data AAAA-MM-DD --modelo 2027-02-06-kimberly-sophia`
   e coloque as artes em `assets/` (`abertura.jpg`, `convite.jpg`, `presentes.jpg`).
2. **Config:** copie `exemplos/kimberly-sophia.json` e ajuste caminhos, tempos, nome e música.
3. **(Opcional) Cor do cabelo:**
   ```bash
   python tools/convites/recolorir.py modelos/.../base.mp4 tools/convites/build/base-cabelo.mp4 \
     --inicio 20.9 --fim 30.133 --tom 70,105,150 --saida-clarao 30.40
   ```
   e aponte `"video_fonte"` para o arquivo gerado (sem isso, use o próprio `video_base`).
4. **Montar:** `python tools/convites/montar.py caminho/do/config.json`
   → gera `convite.mp4` (web, com a música) e `primeiro-quadro.jpg` em `saida_dir` e imprime o `telaFinalEm` para o `config.js`.
5. **Botões da arte final:** `python tools/convites/botoes.py site/convites/<pasta>/assets/convite.jpg`
   → posições em % para os `<a class="hot">` do `index.html` (só muda se a arte tiver os botões em outro lugar).
6. Conferir quadros-chave, testar no PC e no celular, abrir PR (ver skill `convite-digital-lanaro`).

## Arquivos

| Arquivo | O que faz |
|---|---|
| `montar.py` | Monta o vídeo inteiro a partir do JSON (troca do nome no envelope, clarões, capa animada, dissolve, tela final, música) |
| `recolorir.py` | Recolore uma região escura (ex.: cabelo) num trecho, incluindo a saída em clarão |
| `nome_envelope.py` | Recorta o nome da arte do cliente e troca o nome "gravado" numa cena com zoom |
| `botoes.py` | Acha os botões redondos na arte final |
| `fx.py` | Efeitos: zoom, neve, brilhos, reflexo no prateado, bokeh, clarão, estimativa de zoom da câmera |
| `exemplos/*.json` | Configs de convites já feitos |

## Config (campos principais)

| Campo | Exemplo | Para quê |
|---|---|---|
| `tempos.capa_inicio` | `10.07` | Pico do clarão em que a capa entra (s do vídeo base) |
| `tempos.capa_dur` | `3.3` | Quanto tempo a capa fica (o reflexo passa 1 vez) |
| `tempos.castelo_src` | `20.9` | 1º quadro limpo da cena seguinte no vídeo base |
| `tempos.salao_fim` | `39.38` | Último quadro antes do clarão da tela final |
| `nome_envelope.caixa_na_arte` | `[y0,y1,x0,x1]` | Onde está o nome na arte de capa (pixels da arte) |
| `nome_envelope.regiao` | `[y0,y1,x0,x1]` | Onde está o nome antigo no quadro 720×1280 |
| `nome_envelope.linhas` | `[[cx,topo,altura],…]` | 1 linha ou 2 (nome dividido) |
| `musica` | `{arquivo, inicio, fim}` | Corta o silêncio; a tela final fica parada até a música acabar |

Para outro **vídeo base** (outro modelo), os tempos mudam: gere uma folha de quadros
(`ffmpeg -i base.mp4 -vf "fps=1,scale=180:-1,tile=8x6" folha.jpg`) e localize as cenas.
