"""Efeitos reutilizáveis para os vídeos de convite (OpenCV + NumPy).

Tudo trabalha em BGR float32, quadro 720x1280. Artes entram em 2x (1440x2560) para
permitir zoom sem perder nitidez.
"""
import cv2
import numpy as np

W, H = 720, 1280


# ---------- curvas
def ss(x):
    """smoothstep 0..1"""
    c = np.clip(x, 0, 1)
    return c * c * (3 - 2 * c)


def bell(t, centro, largura):
    return float(np.exp(-((t - centro) / largura) ** 2))


def blur(img, sigma):
    return img if sigma < 0.3 else cv2.GaussianBlur(img, (0, 0), sigma)


# ---------- artes
def carregar_arte_2x(caminho):
    """Lê a arte, ajusta para 1440 de largura e recorta o centro em 9:16 (2x)."""
    im = cv2.imread(str(caminho))
    if im is None:
        raise FileNotFoundError(caminho)
    s = (2 * W) / im.shape[1]
    im = cv2.resize(im, (2 * W, round(im.shape[0] * s)), interpolation=cv2.INTER_AREA)
    o = max(0, (im.shape[0] - 2 * H) // 2)
    return im[o:o + 2 * H].astype(np.float32)


def zoom_sobre(img2x, z, cx, cy):
    """Zoom z (>=1) com ponto fixo (cx, cy) em coordenadas do quadro final."""
    s = 0.5 * z
    M = np.float32([[s, 0, cx * (1 - z)], [0, s, cy * (1 - z)]])
    return cv2.warpAffine(img2x, M, (W, H), flags=cv2.INTER_LINEAR, borderMode=cv2.BORDER_REFLECT)


def arte_parada(img2x):
    return cv2.resize(img2x, (W, H), interpolation=cv2.INTER_AREA)


# ---------- brilhos
def pontos_prateados(img, n, rng, excluir_faixas=()):
    """Pontos claros e pouco saturados (prata/branco) para colocar brilhos."""
    hsv = cv2.cvtColor(np.clip(img, 0, 255).astype(np.uint8), cv2.COLOR_BGR2HSV)
    m = (hsv[..., 2] > 235) & (hsv[..., 1] < 40)
    h = m.shape[0]
    for y0, y1 in excluir_faixas:
        m[int(y0 * h):int(y1 * h)] = False
    cand = np.argwhere(m)
    if len(cand) == 0:
        return np.zeros((0, 2), int)
    return cand[rng.choice(len(cand), min(n, len(cand)), replace=False)]


def estrela(camada, y, x, r, a, esp=2):
    if a <= 0.01:
        return
    cv2.line(camada, (int(x - r), int(y)), (int(x + r), int(y)), a, esp, cv2.LINE_AA)
    cv2.line(camada, (int(x), int(y - r * 1.3)), (int(x), int(y + r * 1.3)), a, esp, cv2.LINE_AA)
    cv2.circle(camada, (int(x), int(y)), max(2, int(r / 5)), a, -1, cv2.LINE_AA)


class Brilhos:
    """Estrelinhas piscando em pontos fixos de uma imagem."""

    def __init__(self, img, n, rng, excluir_faixas=()):
        self.p = pontos_prateados(img, n, rng, excluir_faixas)
        self.fase = rng.uniform(0, 6.28, len(self.p))
        self.freq = rng.uniform(0.6, 1.4, len(self.p))

    def aplicar(self, img, t, forca=1.0):
        cam = np.zeros(img.shape[:2], np.float32)
        for (y, x), f, q in zip(self.p, self.fase, self.freq):
            a = max(0, np.sin(t * 2.2 * q + f)) ** 6 * forca
            estrela(cam, y, x, 10 + 16 * a, a)
        cam = cv2.GaussianBlur(cam, (0, 0), 1.6)[..., None]
        return img + cam * 255 * 0.9


def mascara_prata(img2x):
    hsv = cv2.cvtColor(np.clip(img2x, 0, 255).astype(np.uint8), cv2.COLOR_BGR2HSV)
    m = ((hsv[..., 1] < 45) & (hsv[..., 2] > 120)).astype(np.float32)
    return cv2.GaussianBlur(m, (0, 0), 1.2)


class Reflexo:
    """Faixa de luz diagonal passando pelas partes prateadas (nome)."""

    def __init__(self, img2x):
        self.m = mascara_prata(img2x)
        h, w = img2x.shape[:2]
        yy, xx = np.mgrid[0:h, 0:w].astype(np.float32)
        self.d = (xx + yy * 0.55) / (w + h * 0.55)

    def aplicar(self, img2x, fase):
        if not 0 <= fase <= 1:
            return img2x
        faixa = np.exp(-((self.d - (-0.15 + 1.3 * fase)) / 0.05) ** 2)
        return img2x + (self.m * faixa)[..., None] * 110


class Neve:
    def __init__(self, rng, n=80):
        self.x = rng.uniform(0, W, n); self.y = rng.uniform(0, H, n)
        self.v = rng.uniform(25, 75, n); self.r = rng.uniform(1.0, 3.0, n); self.f = rng.uniform(0, 6.28, n)

    def aplicar(self, img, t, qtd=0.8):
        cam = np.zeros((H, W), np.float32)
        for k in range(len(self.x)):
            y = (self.y[k] + self.v[k] * t) % (H + 40) - 20
            x = (self.x[k] + 14 * np.sin(t * 0.7 + self.f[k])) % W
            cv2.circle(cam, (int(x), int(y)), int(self.r[k] + 0.5), 1.0, -1, cv2.LINE_AA)
        cam = cv2.GaussianBlur(cam, (0, 0), 0.9)[..., None] * qtd
        return img * (1 - cam) + 255 * cam


class Bokeh:
    """Bolhas de luz iridescentes para as transições em clarão."""

    def __init__(self, rng, n=22):
        self.x = rng.uniform(0, W, n); self.y = rng.uniform(0, H, n); self.r = rng.uniform(35, 120, n)
        self.vx = rng.uniform(-40, 40, n); self.vy = rng.uniform(-60, -10, n)
        cores = np.array([[255, 220, 235], [255, 245, 215], [235, 225, 255], [215, 255, 250], [255, 255, 255]], np.float32)
        self.c = cores[rng.integers(0, len(cores), n)]

    def aplicar(self, img, t, a):
        if a < 0.01:
            return img
        cam = np.zeros((H, W, 3), np.float32); al = np.zeros((H, W), np.float32)
        for k in range(len(self.x)):
            x = int(self.x[k] + self.vx[k] * t); y = int(self.y[k] + self.vy[k] * t)
            cv2.circle(cam, (x, y), int(self.r[k]), tuple(float(c) for c in self.c[k]), -1, cv2.LINE_AA)
            cv2.circle(al, (x, y), int(self.r[k]), 0.32, -1, cv2.LINE_AA)
        al = cv2.GaussianBlur(al, (0, 0), 12)[..., None] * a
        cam = cv2.GaussianBlur(cam, (0, 0), 12)
        return img * (1 - al) + cam * al


def clarao(img, b, tom=(1, 1, 1)):
    """Bloom suave (b 0..~0.8). Nunca deixa a tela branca chapada por muito tempo."""
    if b < 0.01:
        return img
    return img * (1 - 0.35 * b) + b * (blur(img, 22) * 0.55 + np.array(tom, np.float32) * 95)


# ---------- movimento de câmera
def estimar_zoom(quadro_a, quadro_b, mascara=None):
    """Transformação de similaridade a->b (ORB). Retorna (escala, ponto_fixo, matriz)."""
    g1, g2 = [cv2.cvtColor(np.clip(q, 0, 255).astype(np.uint8), cv2.COLOR_BGR2GRAY) for q in (quadro_a, quadro_b)]
    orb = cv2.ORB_create(4000)
    k1, d1 = orb.detectAndCompute(g1, mascara)
    k2, d2 = orb.detectAndCompute(g2, mascara)
    mt = cv2.BFMatcher(cv2.NORM_HAMMING, crossCheck=True).match(d1, d2)
    M, _ = cv2.estimateAffinePartial2D(np.float32([k1[m.queryIdx].pt for m in mt]),
                                       np.float32([k2[m.trainIdx].pt for m in mt]), ransacReprojThreshold=2)
    s = float(np.hypot(M[0, 0], M[0, 1]))
    c = M[:, 2] / (1 - s) if abs(1 - s) > 1e-6 else np.array([W / 2, H / 2])
    return s, c, M


# ---------- leitura de vídeo
class LeitorSequencial:
    """Lê quadros em ordem crescente (pula os que não forem pedidos)."""

    def __init__(self, caminho):
        self.cap = cv2.VideoCapture(str(caminho))
        self.fps = self.cap.get(cv2.CAP_PROP_FPS)
        self.n = int(self.cap.get(cv2.CAP_PROP_FRAME_COUNT))
        self.i = -1; self.atual = None

    def quadro(self, k):
        k = min(k, self.n - 1)
        while self.i < k:
            ok, f = self.cap.read(); self.i += 1
            if ok:
                self.atual = f
        return self.atual.astype(np.float32)


def ler_intervalo(caminho, t0, t1):
    cap = cv2.VideoCapture(str(caminho)); fps = cap.get(cv2.CAP_PROP_FPS)
    cap.set(cv2.CAP_PROP_POS_FRAMES, round(t0 * fps)); d = {}
    for i in range(round(t0 * fps), round(t1 * fps) + 1):
        ok, f = cap.read()
        if not ok:
            break
        d[i] = f.astype(np.float32)
    return d, fps
