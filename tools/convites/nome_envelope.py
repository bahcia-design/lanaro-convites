"""Troca um nome "gravado" numa cena com zoom (ex.: nome sobre o envelope no início do vídeo).

Passos (usados pelo montar.py):
1. extrair_letreiro(): recorta o nome prateado da arte do cliente (fundo azul -> transparente).
2. TrocaNome(...): limpa a área do nome antigo no 1º quadro (preenchimento suave + brilhos),
   monta o nome novo em 1 ou 2 linhas, rastreia o zoom da câmera e o fade do nome antigo,
   e aplica quadro a quadro (respeitando a aba do envelope que sobe por cima).
"""
import cv2
import numpy as np
from fx import W, H, ss, estrela


def extrair_letreiro(arte_path, caixa, sat_max=48):
    """caixa = (y0, y1, x0, x1) em pixels da arte. Retorna RGBA recortado (BGRA uint8)."""
    a = cv2.imread(str(arte_path)); y0, y1, x0, x1 = caixa
    c = a[y0:y1, x0:x1]
    S = cv2.cvtColor(c, cv2.COLOR_BGR2HSV)[..., 1].astype(np.float32)
    al = np.clip((sat_max - S) / 28, 0, 1)
    b = (al > 0.5).astype(np.uint8)
    n, lab, st, _ = cv2.connectedComponentsWithStats(b)
    keep = np.zeros_like(b)
    for k in range(1, n):
        if st[k, 4] > 400:
            keep[lab == k] = 1
    keep = cv2.dilate(keep, np.ones((5, 5), np.uint8))
    al = al * cv2.GaussianBlur(keep.astype(np.float32), (0, 0), 1.2)
    ys, xs = np.where(al > 0.05)
    return np.dstack([c, (al * 255).astype(np.uint8)])[ys.min():ys.max() + 1, xs.min():xs.max() + 1]


def _apara(x):
    ys, xs = np.where(x[..., 3] > 8)
    return x[ys.min():ys.max() + 1, xs.min():xs.max() + 1]


def dividir_palavras(rgba):
    """Divide 'Nome Sobrenome' no maior vão vertical perto do meio."""
    col = rgba[..., 3].sum(0); mid = rgba.shape[1] // 2
    gap = int(np.argmin(col[mid - 150:mid + 100]) + mid - 150)
    return _apara(rgba[:, :gap]), _apara(rgba[:, gap:])


class TrocaNome:
    def __init__(self, video, quadro0, regiao, letreiro_rgba, linhas, fim_cena=4.0, seed=7):
        """
        regiao: (y0, y1, x0, x1) do nome antigo no quadro 720x1280 (sem pegar o que deve ficar, ex. "15 ANOS").
        linhas: lista de (centro_x, topo_y, altura) — 1 item = nome numa linha; 2 itens = nome dividido.
        """
        self.f0 = quadro0.astype(np.float32)
        y0, y1, x0, x1 = regiao
        self.P = self._placa(quadro0, regiao, seed)
        self.L = self._camada_nome(letreiro_rgba, linhas)
        self.A = self.L[..., 3:] / 255
        diff = np.abs(self.f0 - self.P).mean(2)
        km = ((diff > 5) | (self.A[..., 0] > 0.02)).astype(np.uint8); km[y1 + 8:] = 0
        km = cv2.GaussianBlur(cv2.dilate(km, np.ones((15, 15), np.uint8)).astype(np.float32), (0, 0), 6)
        self.km = np.clip(km * 1.4, 0, 1)
        self.tt, self.M, self.alpha = self._rastrear(video, regiao, fim_cena)
        rng = np.random.default_rng(seed)
        gp = np.argwhere(self.A[..., 0] > 0.9)
        self.gp = gp[rng.choice(len(gp), min(6, len(gp)), replace=False)] if len(gp) else gp
        self.gph = rng.uniform(0, 6.28, len(self.gp))

    # placa: fundo limpo onde estava o nome antigo
    def _placa(self, f, regiao, seed):
        y0, y1, x0, x1 = regiao
        S = cv2.cvtColor(f, cv2.COLOR_BGR2HSV)[..., 1].astype(np.float32)
        reg = np.zeros(S.shape, np.uint8); reg[y0:y1, x0:x1] = 1
        m = ((S < 70) & (reg == 1)).astype(np.uint8)
        n, lab, st, _ = cv2.connectedComponentsWithStats(m); keep = np.zeros_like(m)
        for k in range(1, n):
            if st[k, 4] > 150:
                keep[lab == k] = 1
        keep = cv2.morphologyEx(cv2.dilate(keep, np.ones((19, 19), np.uint8)), cv2.MORPH_CLOSE, np.ones((31, 31), np.uint8))
        keep[y1:] = 0
        sm = cv2.resize(f, None, fx=0.25, fy=0.25)
        ks = cv2.dilate(cv2.resize(keep, (sm.shape[1], sm.shape[0]), interpolation=cv2.INTER_NEAREST), np.ones((3, 3), np.uint8))
        fill = cv2.resize(cv2.GaussianBlur(cv2.inpaint(sm, ks, 8, cv2.INPAINT_TELEA), (0, 0), 2), (W, H),
                          interpolation=cv2.INTER_CUBIC).astype(np.float32)
        rng = np.random.default_rng(seed); tex = np.zeros((H, W), np.float32)
        n = int(H * W * 0.0022); ys = rng.integers(0, H, n); xs = rng.integers(0, W, n)
        tex[ys, xs] = rng.uniform(60, 255, n); big = rng.choice(n, n // 12, replace=False); tex[ys[big], xs[big]] = 400
        tex = np.repeat((cv2.GaussianBlur(tex, (0, 0), 0.9) * 2.2)[..., None], 3, 2) * np.array([1.0, 0.97, 0.92])
        fill = fill + tex
        km = np.clip(cv2.GaussianBlur(cv2.dilate(keep, np.ones((15, 15), np.uint8)).astype(np.float32), (0, 0), 14)[..., None] * 1.3, 0, 1)
        P = f.astype(np.float32) * (1 - km) + fill * km
        yy, xx = np.mgrid[0:H, 0:W]
        cy = (y0 + y1) / 2 + 20; cx = (x0 + x1) / 2
        glow = np.exp(-(((xx - cx) / 260) ** 2 + ((yy - cy) / 130) ** 2))[..., None]
        return np.clip(P + glow * np.array([26, 20, 14]), 0, 255)

    def _camada_nome(self, rgba, linhas):
        L = np.zeros((H, W, 4), np.float32)
        partes = [rgba] if len(linhas) == 1 else list(dividir_palavras(rgba))
        for img, (cx, topo, alt) in zip(partes, linhas):
            s = alt / img.shape[0]
            im = cv2.resize(img, None, fx=s, fy=s, interpolation=cv2.INTER_AREA).astype(np.float32)
            x = int(cx - im.shape[1] / 2); y = int(topo)
            a = im[..., 3:] / 255; r = L[y:y + im.shape[0], x:x + im.shape[1]]
            r[..., :3] = im[..., :3] * a + r[..., :3] * (1 - a); r[..., 3:] = np.maximum(r[..., 3:], a * 255)
        return L

    def _rastrear(self, video, regiao, fim):
        y0, y1, x0, x1 = regiao
        cap = cv2.VideoCapture(str(video)); fps = cap.get(5)
        g0 = cv2.cvtColor(self.f0.astype(np.uint8), cv2.COLOR_BGR2GRAY)
        S = cv2.cvtColor(self.f0.astype(np.uint8), cv2.COLOR_BGR2HSV)[..., 1]
        core = (S < 35); core[:y0] = 0; core[y1:] = 0; core[:, :x0] = 0; core[:, x1:] = 0
        d0 = (self.f0 - self.P).mean(2); core &= (np.abs(d0) > 25)
        fm = np.full_like(g0, 255); fm[y0 - 15:y1 + 8, x0 - 5:x1 + 10] = 0; fm[int(H * 0.5):] = 0
        orb = cv2.ORB_create(4000); k0, ds0 = orb.detectAndCompute(g0, fm)
        bf = cv2.BFMatcher(cv2.NORM_HAMMING, crossCheck=True)
        tt, Ms, aa = [], [], []
        for i in range(int(fim * fps)):
            ok, f = cap.read()
            if not ok:
                break
            k1, ds1 = orb.detectAndCompute(cv2.cvtColor(f, cv2.COLOR_BGR2GRAY), fm)
            mt = bf.match(ds0, ds1)
            M, _ = cv2.estimateAffinePartial2D(np.float32([k0[m.queryIdx].pt for m in mt]),
                                               np.float32([k1[m.trainIdx].pt for m in mt]), ransacReprojThreshold=2)
            gb = cv2.warpAffine(f, cv2.invertAffineTransform(M), (W, H)).astype(np.float32)
            a = float(np.median(((gb - self.P).mean(2))[core] / d0[core])) if core.any() else 0
            tt.append(i / fps); Ms.append(M.ravel()); aa.append(a)
        tt = np.array(tt); Ms = np.array(Ms); aa = np.array(aa)
        Mfit = np.stack([np.polyval(np.polyfit(tt, Ms[:, k], 2), tt) for k in range(6)], 1)
        asm = np.convolve(np.pad(aa, 7, mode='edge'), np.ones(15) / 15, 'valid')
        asm = np.minimum.accumulate(np.clip(asm, 0, 1))
        return tt, Mfit, asm

    @property
    def fim(self):
        return self.tt[-1]

    def aplicar(self, f, i, t):
        if i >= len(self.tt):
            return f
        a = float(self.alpha[i]); M = self.M[i].reshape(2, 3)
        Pw = cv2.warpAffine(self.P, M, (W, H), borderMode=cv2.BORDER_REFLECT)
        f0w = cv2.warpAffine(self.f0, M, (W, H))
        Lw = cv2.warpAffine(self.L, M, (W, H)); Aw = Lw[..., 3:] / 255 * a
        gl = np.zeros((H, W), np.float32)
        for (y, x), p in zip(self.gp, self.gph):
            g = max(0, np.sin(t * 2.5 + p)) ** 8 * a; estrela(gl, y, x, 8 + 12 * g, g)
        gl = cv2.warpAffine(cv2.GaussianBlur(gl, (0, 0), 1.3), M, (W, H))[..., None]
        regiao = Pw * (1 - Aw) + Lw[..., :3] * Aw + gl * 230
        kmw = cv2.warpAffine(self.km, M, (W, H))[..., None]
        occ = 0
        if a < 0.97:  # aba do envelope subindo por cima
            esp = a * f0w + (1 - a) * Pw
            d = cv2.morphologyEx((np.abs(f - esp).mean(2) > 45).astype(np.uint8), cv2.MORPH_OPEN, np.ones((7, 7), np.uint8))
            n_, lab, st, _ = cv2.connectedComponentsWithStats(d); o = np.zeros_like(d)
            for q in range(1, n_):
                if st[q, 4] > 3000:
                    o[lab == q] = 1
            o = cv2.morphologyEx(o, cv2.MORPH_CLOSE, np.ones((21, 21), np.uint8))
            occ = cv2.GaussianBlur(cv2.dilate(o, np.ones((9, 9), np.uint8)).astype(np.float32), (0, 0), 3)[..., None]
        w = kmw * (1 - occ) * ss(a / 0.45)
        return f * (1 - w) + regiao * w
