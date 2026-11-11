#!/usr/bin/env python3
"""Recolore uma região escura (ex.: cabelo preto -> castanho) num trecho do vídeo.

Uso:
  python tools/convites/recolorir.py base.mp4 saida.mp4 --inicio 20.9 --fim 30.13 \
      --tom 70,105,150 --saida-clarao 30.40

- Máscara: pixels escuros dentro da faixa central, mantendo só o maior componente (o cabelo).
- --tom é BGR do novo cabelo. Valores do convite Kimberly: castanho médio 70,105,150.
- --saida-clarao: se a cena termina num clarão, continua aplicando a correção (proporcional ao
  quanto o cabelo ainda aparece) até esse segundo.
O áudio é copiado do original.
"""
import argparse, subprocess, sys
from pathlib import Path
import cv2
import numpy as np


def recolor(img, tom, lift=1.7, base=0.05, ganho=1.25, brilho=0.3, regiao=(0.25, 0.75, 0.25, 0.75)):
    hsv = cv2.cvtColor(img, cv2.COLOR_BGR2HSV)
    v = hsv[..., 2].astype(np.float32); h, w = v.shape
    m = np.clip((95 - v) / 40.0, 0, 1)
    y0, y1, x0, x1 = regiao
    reg = np.zeros_like(m); reg[int(h * y0):int(h * y1), int(w * x0):int(w * x1)] = 1
    m = m * cv2.GaussianBlur(reg, (0, 0), 25)
    m = cv2.GaussianBlur(cv2.dilate(m, np.ones((3, 3), np.uint8)), (0, 0), 1.5)
    L = v / 255.0
    Ln = np.clip(L * lift + base, 0, 1)
    novo = Ln[..., None] * (np.array(tom, np.float32) / 255.0)[None, None] * ganho
    novo = np.clip(novo + np.clip(L - 0.25, 0, 1)[..., None] * brilho, 0, 1) * 255
    return novo, m


def delta(f, tom, **kw):
    o, m = recolor(f, tom, **kw)
    b = (m > 0.15).astype(np.uint8)
    n, lab, st, _ = cv2.connectedComponentsWithStats(b)
    if n <= 1:
        return np.zeros(f.shape, np.float32)
    k = 1 + np.argmax(st[1:, 4])
    keep = cv2.dilate((lab == k).astype(np.uint8), np.ones((9, 9), np.uint8)).astype(np.float32)
    keep = cv2.GaussianBlur(keep, (0, 0), 3)[..., None]
    return keep * (o - f.astype(np.float32))


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('entrada'); ap.add_argument('saida')
    ap.add_argument('--inicio', type=float, required=True, help='1º segundo com a cena limpa')
    ap.add_argument('--fim', type=float, required=True, help='último segundo com a cena limpa')
    ap.add_argument('--tom', default='70,105,150')
    ap.add_argument('--saida-clarao', type=float, default=None)
    a = ap.parse_args()
    tom = tuple(int(x) for x in a.tom.split(','))
    cap = cv2.VideoCapture(a.entrada); fps = cap.get(5)
    W, H = int(cap.get(3)), int(cap.get(4))
    # referência para o clarão de saída
    ref_cap = cv2.VideoCapture(a.entrada); ref_cap.set(cv2.CAP_PROP_POS_FRAMES, round(a.fim * fps))
    ref = ref_cap.read()[1]; D_out = delta(ref, tom)
    HM = np.abs(D_out).sum(2) > 30
    refv = cv2.cvtColor(ref, cv2.COLOR_BGR2HSV)[..., 2][HM].astype(np.float32).mean() if HM.any() else 0
    Path(a.saida).parent.mkdir(parents=True, exist_ok=True)
    tmp = Path(a.saida).with_suffix('.noaudio.mp4')
    out = cv2.VideoWriter(str(tmp), cv2.VideoWriter_fourcc(*'mp4v'), fps, (W, H))
    i = 0
    while True:
        ok, f = cap.read()
        if not ok:
            break
        t = i / fps; i += 1
        if a.inicio <= t <= a.fim:
            f = np.clip(f + delta(f, tom), 0, 255).astype(np.uint8)
        elif a.saida_clarao and a.fim < t < a.saida_clarao and HM.any():
            fv = cv2.cvtColor(f, cv2.COLOR_BGR2HSV)[..., 2][HM].astype(np.float32).mean()
            al = float(np.clip((255 - fv) / max(1, 255 - refv), 0, 1))
            f = np.clip(f + al * D_out, 0, 255).astype(np.uint8)
        out.write(f)
    out.release()
    subprocess.run(['ffmpeg', '-v', 'error', '-y', '-i', str(tmp), '-i', a.entrada, '-map', '0:v', '-map', '1:a?',
                    '-c:v', 'libx264', '-preset', 'veryfast', '-crf', '18', '-pix_fmt', 'yuv420p', '-c:a', 'copy', a.saida], check=True)
    tmp.unlink()
    print('ok', a.saida)


if __name__ == '__main__':
    sys.exit(main())
