#!/usr/bin/env python3
"""Acha os botões redondos desenhados na arte final e imprime as posições em % para o index.html.

Uso:  python tools/convites/botoes.py convite.png [--faixa 0.68 0.86]
Saída: left/top e diâmetro (em % da largura) de cada botão, da esquerda para a direita,
e um preview com os círculos marcados (botoes_preview.jpg).
"""
import argparse
import cv2
import numpy as np

ap = argparse.ArgumentParser()
ap.add_argument('arte'); ap.add_argument('--faixa', nargs=2, type=float, default=[0.68, 0.86],
                                          help='faixa vertical (fração da altura) onde ficam os botões')
ap.add_argument('--raio', nargs=2, type=float, default=[0.085, 0.13], help='raio mín/máx em fração da largura')
a = ap.parse_args()
im = cv2.imread(a.arte); H, W = im.shape[:2]
g = cv2.medianBlur(cv2.cvtColor(im, cv2.COLOR_BGR2GRAY), 5)
y0, y1 = int(H * a.faixa[0]), int(H * a.faixa[1])
c = cv2.HoughCircles(g[y0:y1], cv2.HOUGH_GRADIENT, dp=1.2, minDist=int(W * 0.18), param1=120, param2=60,
                     minRadius=int(W * a.raio[0]), maxRadius=int(W * a.raio[1]))
if c is None:
    raise SystemExit('Nenhum botão encontrado: ajuste --faixa/--raio')
for x, y, r in sorted(c[0], key=lambda v: v[0]):
    y += y0
    print(f'left:{x / W * 100:.1f}%  top:{y / H * 100:.1f}%  diâmetro:{2 * r / W * 100:.1f}%')
    cv2.circle(im, (int(x), int(y)), int(r), (0, 0, 255), 6)
cv2.imwrite('botoes_preview.jpg', cv2.resize(im, None, fx=0.4, fy=0.4))
