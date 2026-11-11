#!/usr/bin/env python3
"""Monta o vídeo do convite a partir de um vídeo base + artes do cliente + config JSON.

Uso:
  python tools/convites/montar.py tools/convites/exemplos/kimberly-sophia.json

Modelo "baile-de-inverno" (o do vídeo base comprado):
  [0 .. env_fim]        envelope com o nome  -> troca do nome (opcional, bloco "nome_envelope")
  [.. capa_inicio]      envelope abrindo, "Convite Especial", clarão
  capa                  arte "capa" com push-in, neve, brilhos e 1 reflexo no nome (capa_dur s)
  dissolve -> castelo   cena do castelo do vídeo base a partir de castelo_src
  salão -> convite      clarão azulado; arte "convite" assenta com zoom e fica parada
  música                (opcional) entra no lugar do áudio; a tela final segue parada até a música acabar

Saídas em "saida_dir": convite.mp4 (web), primeiro-quadro.jpg e o tempo para "telaFinalEm".
Todos os tempos do config são em segundos do VÍDEO BASE (exceto capa_dur/dissolve, que são durações).
"""
import json, subprocess, sys
from pathlib import Path
import cv2
import numpy as np

sys.path.insert(0, str(Path(__file__).parent))
from fx import (W, H, ss, bell, blur, carregar_arte_2x, zoom_sobre, arte_parada, Brilhos, Reflexo, Neve,
                Bokeh, clarao, estimar_zoom, LeitorSequencial, ler_intervalo)
from nome_envelope import extrair_letreiro, TrocaNome

RAIZ = Path(__file__).resolve().parents[2]


def p(x):
    x = Path(x)
    return x if x.is_absolute() else RAIZ / x


def main(cfg_path):
    cfg = json.loads(Path(cfg_path).read_text())
    base = p(cfg['video_base']); fonte = p(cfg.get('video_fonte', cfg['video_base']))  # fonte = base já com cabelo recolorido, se houver
    T = cfg['tempos']; saida = p(cfg['saida_dir']); saida.mkdir(parents=True, exist_ok=True)
    tmp = RAIZ / 'tools/convites/build'; tmp.mkdir(exist_ok=True)
    rng = np.random.default_rng(11)

    CAPA = carregar_arte_2x(p(cfg['artes']['capa'])); CARD = carregar_arte_2x(p(cfg['artes']['convite']))
    CARD_PARADO = arte_parada(CARD)
    brilho_capa = Brilhos(CAPA, 45, rng); reflexo = Reflexo(CAPA); neve = Neve(rng); bokeh = Bokeh(rng)

    COV0 = T['capa_inicio']; COV_DUR = T.get('capa_dur', 3.3); XF = T.get('dissolve', 1.1)
    CAS0 = COV0 + COV_DUR; CAS1 = CAS0 + XF; SRC_CAS = T['castelo_src']; SHIFT = SRC_CAS - CAS1
    HALL_END = T['salao_fim']; CARD_IN = HALL_END + 0.04; CARD_SET = T.get('convite_assenta', HALL_END + 2.0)
    FCX, FCY = cfg.get('foco_capa', [352, 720]); ZRATE = cfg.get('zoom_capa_por_s', 0.0288)
    REFLEXO_EM = cfg.get('reflexo_em', 0.7)

    def capa(t):
        lt = t - COV0
        img = brilho_capa.aplicar(CAPA, lt)
        img = reflexo.aplicar(img, (lt - REFLEXO_EM) / 2.2)
        return neve.aplicar(zoom_sobre(img, 1.0 + ZRATE * max(0, lt), FCX, FCY), lt)

    FLASH, fps = ler_intervalo(fonte, COV0 - 0.9, COV0 + 0.1)
    HALL, _ = ler_intervalo(fonte, HALL_END - 0.8, HALL_END + 0.1)
    cas_ref = ler_intervalo(fonte, SRC_CAS, SRC_CAS)[0]; CAS_REF = list(cas_ref.values())[0]
    cas_next = list(ler_intervalo(fonte, SRC_CAS + 1, SRC_CAS + 1)[0].values())[0]
    s_c, c_c, _ = estimar_zoom(CAS_REF, cas_next)

    def rev(d, t_end, t):
        k = round((t_end - (t - t_end)) * fps); return d[max(min(d), min(max(d), k))]

    troca = None
    if cfg.get('nome_envelope'):
        ne = cfg['nome_envelope']
        q0 = cv2.VideoCapture(str(fonte)).read()[1]
        letreiro = extrair_letreiro(p(ne['arte']), ne['caixa_na_arte'])
        troca = TrocaNome(fonte, q0, ne['regiao'], letreiro, ne['linhas'], ne.get('fim_cena', 4.0))
        print('nome do envelope: rastreado até', round(troca.fim, 2), 's')

    leitor = LeitorSequencial(fonte)
    NOUT = int(round((leitor.n / fps - SHIFT) * fps))
    noaudio = tmp / 'video_sem_audio.mp4'
    out = cv2.VideoWriter(str(noaudio), cv2.VideoWriter_fourcc(*'mp4v'), fps, (W, H))
    for i in range(NOUT):
        t = i / fps
        st = t if t < CAS1 else t + SHIFT
        f = leitor.quadro(round(st * fps)) if (t < COV0 + 0.8 or t >= CAS1) else None
        if troca is not None and t <= troca.fim:
            f = troca.aplicar(f, i, t)
        elif COV0 - 0.62 <= t < COV0 + 0.78:   # envelope -> capa (clarão)
            b = 0.75 * bell(t, COV0, 0.22); m = ss((t - (COV0 - 0.1)) / 0.25)
            A = f if t <= COV0 else rev(FLASH, COV0, t)
            B = blur(capa(max(t, COV0)), 12 * b) if m > 0 else 0
            f = bokeh.aplicar(clarao((1 - m) * A + m * B, b), t - COV0 + 0.62,
                              ss((t - COV0 + 0.62) / 0.4) * (1 - ss((t - COV0 - 0.28) / 0.5)))
        elif COV0 + 0.78 <= t < CAS0:
            f = capa(t)
        elif CAS0 <= t < CAS1:                  # capa -> castelo (dissolve)
            u = ss((t - CAS0) / XF); sk = s_c ** (t - CAS1)
            M = np.float32([[sk, 0, c_c[0] * (1 - sk)], [0, sk, c_c[1] * (1 - sk)]])
            cas = cv2.warpAffine(CAS_REF, M, (W, H), borderMode=cv2.BORDER_REFLECT)
            f = clarao(capa(t) * (1 - u) + cas * u, 0.35 * bell(t, (CAS0 + CAS1) / 2, 0.3))
        elif st >= HALL_END - 0.43:             # salão -> convite (clarão azulado)
            b = 0.75 * bell(st, HALL_END + 0.17, 0.24); m = ss((st - CARD_IN) / 0.3)
            A = f if st <= HALL_END else rev(HALL, HALL_END, st)
            if st < CARD_SET:
                card = zoom_sobre(CARD, 1.08 - 0.08 * ss((st - CARD_IN) / (CARD_SET - CARD_IN)), W / 2, H / 2)
            else:
                card = CARD_PARADO
            if st < CARD_IN + 1.0:
                card = blur(card, 10 * b)
            lt = st - CARD_IN
            if 0 < lt < 2.6:
                card = neve.aplicar(card, lt, 0.6 * (1 - ss((lt - 1.6) / 1.0)))
            f = clarao((1 - m) * A + m * card if m < 1 else card, b, (1.1, 1.0, 0.85))
            if st >= CARD_SET + 0.2:
                f = CARD_PARADO
        out.write(np.clip(f, 0, 255).astype(np.uint8))
    out.release()
    dur = NOUT / fps
    tela_final = round(CARD_SET + 0.2 - SHIFT, 1)

    # áudio: música do cliente (com a tela final parada até ela acabar) ou o áudio do vídeo base
    destino = saida / 'convite.mp4'
    web = ['-c:v', 'libx264', '-preset', 'slow', '-crf', '27', '-maxrate', '2M', '-bufsize', '4M', '-g', '600',
           '-pix_fmt', 'yuv420p', '-movflags', '+faststart', '-c:a', 'aac', '-b:a', '128k']
    if cfg.get('musica'):
        mus = cfg['musica']; ini = mus.get('inicio', 0); fim = mus.get('fim')
        mtmp = tmp / 'musica.wav'
        cmd = ['ffmpeg', '-v', 'error', '-y', '-ss', str(ini)] + (['-to', str(fim)] if fim else []) + ['-i', str(p(mus['arquivo'])), mtmp.as_posix()]
        subprocess.run(cmd, check=True)
        md = float(subprocess.check_output(['ffprobe', '-v', 'error', '-show_entries', 'format=duration', '-of', 'csv=p=0', str(mtmp)]))
        hold = max(0, md - dur + 0.05)
        subprocess.run(['ffmpeg', '-v', 'error', '-y', '-i', str(noaudio), '-i', str(mtmp), '-filter_complex',
                        f'[0:v]tpad=stop_mode=clone:stop_duration={hold:.2f}[v];[1:a]afade=t=in:d=0.3,afade=t=out:st={md - 3:.2f}:d=3[a]',
                        '-map', '[v]', '-map', '[a]', '-shortest'] + web + [str(destino)], check=True)
    else:
        subprocess.run(['ffmpeg', '-v', 'error', '-y', '-i', str(noaudio), '-i', str(base), '-map', '0:v', '-map', '1:a?',
                        '-af', f'afade=t=out:st={dur - 1.8:.2f}:d=1.8', '-t', f'{dur:.3f}'] + web + [str(destino)], check=True)
    subprocess.run(['ffmpeg', '-v', 'error', '-y', '-ss', '0', '-i', str(destino), '-frames:v', '1', '-q:v', '3',
                    str(saida / 'primeiro-quadro.jpg')], check=True)
    print(f'ok: {destino}  ({dur:.1f}s de animação)')
    print(f'config.js -> telaFinalEm: {tela_final}')


if __name__ == '__main__':
    if len(sys.argv) != 2:
        raise SystemExit(__doc__)
    main(sys.argv[1])
