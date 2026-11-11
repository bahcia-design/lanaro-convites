# Convites digitais

Cada convite é uma pasta própria e independente dentro de `site/convites/`. Na Hostinger, `site/` é o conteúdo do `public_html`, então cada pasta fica no ar em `https://<domínio>/convites/<pasta>/`, sem build.

## Nome da pasta (o que evita conflito)

```
AAAA-MM-DD-nome-da-pessoa[-sufixo]
```

- **Data do evento** primeiro, depois o **nome em minúsculas, sem acento, com hífen**. Exemplo: `2027-02-06-kimberly-sophia`.
- Duas pessoas com o mesmo nome no mesmo dia (raro) → acrescente um sufixo: `2027-02-06-kimberly-sophia-2`.
- **Nunca renomeie nem reaproveite** a pasta de um convite que já foi enviado: o link já está no WhatsApp dos convidados. Para corrigir algo, edite os arquivos dentro dela.

## O que vai dentro de cada pasta

```
index.html      página (título e descrição do convite)
config.js       link de confirmação (ou WhatsApp), link do Maps, nome, música de fundo (opcional)
assets/
  convite.mp4   vídeo
  abertura.jpg  tela de abertura
  convite.jpg   tela final com os botões
  presentes.jpg sugestão de presentes
  poster.jpg    prévia do link
  primeiro-quadro.jpg  primeiro quadro do vídeo (evita piscar ao abrir)
```

Os arquivos têm sempre os mesmos nomes; o que muda é a pasta. Assim um convite nunca enxerga os arquivos de outro.

## Criar um convite novo

```bash
node scripts/novo-convite.mjs --nome "Maria Eduarda" --data 2027-05-20 --modelo 2027-02-06-kimberly-sophia
```

O script copia o modelo, coloca o nome no `config.js`, limpa o WhatsApp e **se recusa a sobrescrever** uma pasta que já existe. Depois é só trocar as artes em `assets/` e preencher o `config.js`.

## Subir para a Hostinger

Envie a pasta do convite para `public_html/convites/` (Gerenciador de Arquivos ou FTP), mantendo o nome da pasta. Só ela muda; nada do resto do site é tocado.

## Git

- Um branch por convite: `convites/<pasta>` (ex.: `convites/2027-02-06-kimberly-sophia`). Como cada convite só mexe na própria pasta, os branches não conflitam entre si.
- Comprima o vídeo antes de subir (alvo: até ~10 MB):
  `ffmpeg -i entrada.mp4 -c:v libx264 -crf 27 -maxrate 2M -bufsize 4M -movflags +faststart -c:a aac -b:a 128k assets/convite.mp4`

## Registro

| Pasta | Aniversariante | Evento | Status |
|---|---|---|---|
| `2027-02-06-kimberly-sophia` | Kimberly Sophia — 15 anos (Baile de Inverno) | 06/02/2027 20h, Le Partie Eventos | pronto (RSVP pela Assessoria VIP) |
