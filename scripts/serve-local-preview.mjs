import { createReadStream } from 'node:fs';
import { stat } from 'node:fs/promises';
import http from 'node:http';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const repositoryRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const publicRoot = path.join(repositoryRoot, 'dist');
const port = Number(process.env.LANARO_PREVIEW_PORT || 4173);
const host = '127.0.0.1';
const mimeTypes = {
  '.avif': 'image/avif',
  '.css': 'text/css; charset=utf-8',
  '.eot': 'application/vnd.ms-fontobject',
  '.gif': 'image/gif',
  '.html': 'text/html; charset=utf-8',
  '.ico': 'image/x-icon',
  '.jpeg': 'image/jpeg',
  '.jpg': 'image/jpeg',
  '.js': 'text/javascript; charset=utf-8',
  '.json': 'application/json; charset=utf-8',
  '.mjs': 'text/javascript; charset=utf-8',
  '.otf': 'font/otf',
  '.png': 'image/png',
  '.svg': 'image/svg+xml',
  '.ttf': 'font/ttf',
  '.webp': 'image/webp',
  '.woff': 'font/woff',
  '.woff2': 'font/woff2',
};

http.createServer(async (request, response) => {
  if (request.method !== 'GET' && request.method !== 'HEAD') {
    response.writeHead(405, { Allow: 'GET, HEAD' }).end();
    return;
  }

  let pathname;
  try {
    pathname = decodeURIComponent(new URL(request.url, `http://${host}`).pathname);
  } catch {
    response.writeHead(400).end();
    return;
  }

  const relativePath = pathname === '/' ? 'index.html' : pathname.replace(/^\/+/, '');
  const filePath = path.resolve(publicRoot, relativePath);
  if (filePath !== publicRoot && !filePath.startsWith(`${publicRoot}${path.sep}`)) {
    response.writeHead(403).end();
    return;
  }

  try {
    const file = await stat(filePath);
    if (!file.isFile()) {
      response.writeHead(404).end();
      return;
    }

    response.writeHead(200, {
      'Content-Type': mimeTypes[path.extname(filePath).toLowerCase()] || 'application/octet-stream',
      'Content-Length': file.size,
      'Cache-Control': 'no-store',
    });
    if (request.method === 'HEAD') response.end();
    else createReadStream(filePath).pipe(response);
  } catch {
    response.writeHead(404).end();
  }
}).listen(port, host, () => {
  console.log(`Local preview ready at http://${host}:${port}/`);
});
