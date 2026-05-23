import { chromium } from '@playwright/test';

const browser = await chromium.launch({ headless: true });
const page = await browser.newPage({ viewport: { width: 1280, height: 900 } });
const issues = [];

page.on('console', (message) => {
    if (message.type() === 'error') issues.push(message.text());
});

page.on('pageerror', (error) => {
    issues.push(error.message);
});

try {
    await page.goto('http://127.0.0.1:8000', { waitUntil: 'networkidle' });
    await page.waitForTimeout(900);
    await page.screenshot({ path: 'storage/app/ui-about.png', fullPage: true });

    const canvasState = await page.evaluate(() => {
        const canvas = document.querySelector('#character-canvas');
        if (!canvas) return { exists: false };

        const sample = document.createElement('canvas');
        sample.width = canvas.width;
        sample.height = canvas.height;
        const ctx = sample.getContext('2d');
        ctx.drawImage(canvas, 0, 0);
        const pixels = ctx.getImageData(0, 0, sample.width, sample.height).data;
        let visible = 0;

        for (let index = 3; index < pixels.length; index += 16) {
            if (pixels[index] > 0) visible++;
        }

        return {
            exists: true,
            width: canvas.width,
            height: canvas.height,
            visible,
        };
    });

    if (!canvasState.exists || canvasState.width < 200 || canvasState.visible < 100) {
        throw new Error(`3D canvas not rendering: ${JSON.stringify(canvasState)} ${issues.join(' | ')}`);
    }

    await page.getByRole('button', { name: /ai assistant/i }).click();
    await page.waitForSelector('[data-ai-panel].open');
    await page.waitForSelector('text=Halo, saya bisa bantu menjelaskan portfolio');
    await page.fill('[data-ai-input]', 'Layanan apa saja yang tersedia?');
    await page.click('[data-ai-send]');
    await page.waitForSelector('text=Assistant belum terhubung penuh ke AI');

    await page.goto('http://127.0.0.1:8000/portfolio', { waitUntil: 'networkidle' });
    await page.waitForSelector('text=Capturing the');
    await page.screenshot({ path: 'storage/app/ui-portfolio.png', fullPage: true });

    await page.goto('http://127.0.0.1:8000/contact', { waitUntil: 'networkidle' });
    await page.waitForSelector("text=Let's Connect");
    await page.screenshot({ path: 'storage/app/ui-contact.png', fullPage: true });

    console.log('ui ok');
} finally {
    await browser.close();
}
