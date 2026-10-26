import { cp, mkdir, readdir, rm } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const repositoryRoot = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const sourceRoot = path.join(repositoryRoot, 'site');
const outputRoot = path.join(repositoryRoot, 'dist');
const staticDirectories = ['assets', 'css', 'fonts', 'img', 'js'];
const staticExtensions = new Set([
  '.avif', '.css', '.eot', '.gif', '.ico', '.jpeg', '.jpg', '.js', '.json',
  '.mjs', '.otf', '.png', '.svg', '.ttf', '.webp', '.woff', '.woff2',
]);

async function copyStaticTree(source, destination) {
  await mkdir(destination, { recursive: true });
  const entries = await readdir(source, { withFileTypes: true });

  for (const entry of entries) {
    const sourcePath = path.join(source, entry.name);
    const destinationPath = path.join(destination, entry.name);

    if (entry.isDirectory()) {
      await copyStaticTree(sourcePath, destinationPath);
    } else if (entry.isFile() && staticExtensions.has(path.extname(entry.name).toLowerCase())) {
      await cp(sourcePath, destinationPath);
    }
  }
}

await rm(outputRoot, { recursive: true, force: true });
await mkdir(outputRoot, { recursive: true });

for (const entry of await readdir(sourceRoot, { withFileTypes: true })) {
  if (entry.isFile() && path.extname(entry.name).toLowerCase() === '.html') {
    await cp(path.join(sourceRoot, entry.name), path.join(outputRoot, entry.name));
  }
}

for (const directory of staticDirectories) {
  const source = path.join(sourceRoot, directory);
  try {
    await copyStaticTree(source, path.join(outputRoot, directory));
  } catch (error) {
    if (error.code !== 'ENOENT') throw error;
  }
}

const outputEntries = await readdir(outputRoot);
if (!outputEntries.includes('index.html')) {
  throw new Error('Static preview was not built: site/index.html is missing.');
}

console.log(`Built static preview in ${path.relative(repositoryRoot, outputRoot)}.`);
