#!/usr/bin/env node
// generate-icons.js
// Usage: node scripts/generate-icons.js
// Requires sharp: npm install --save-dev sharp

import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import sharp from 'sharp';

// Compute project root in a Windows-safe way
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
// project root is the parent directory of scripts
const root = path.resolve(__dirname, '..');
const publicDir = path.join(root, 'public');
const src = path.join(publicDir, 'eni.png');
const outDir = path.join(publicDir, 'icons');

if (!fs.existsSync(src)) {
  console.error('Source image not found at', src);
  process.exit(1);
}

if (!fs.existsSync(outDir)) fs.mkdirSync(outDir, { recursive: true });

async function generate() {
  try {
    // 192x192
    await sharp(src).resize(192, 192, { fit: 'cover' }).toFile(path.join(outDir, 'icon-192x192.png'));
    // 512x512
    await sharp(src).resize(512, 512, { fit: 'cover' }).toFile(path.join(outDir, 'icon-512x512.png'));
    // 512x512 maskable (same image but keep alpha if present)
    await sharp(src).resize(512, 512, { fit: 'cover' }).toFile(path.join(outDir, 'icon-512x512-maskable.png'));

    console.log('Icons generated in', outDir);
  } catch (err) {
    console.error('Error generating icons:', err);
    process.exit(1);
  }
}

generate();
