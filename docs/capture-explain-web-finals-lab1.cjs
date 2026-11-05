const path = require('path');
const { chromium } = require(path.join(__dirname, '..', 'petal-express', 'node_modules', 'playwright'));

const base = 'http://127.0.0.1:8000';
const outDir = path.join(__dirname, 'explain-shots-finals-lab1');

async function shot(page, name) {
    const file = path.join(outDir, name);
    await page.screenshot({ path: file });
    console.log('saved', file);
}

async function box(page, selector) {
    return page.evaluate((sel) => {
        const img = document.querySelector(sel);
        if (!img) {
            return null;
        }
        const r = img.getBoundingClientRect();
        return {
            selector: sel,
            boxW: Math.round(r.width),
            boxH: Math.round(r.height),
            naturalW: img.naturalWidth,
            naturalH: img.naturalHeight,
            cls: img.className,
        };
    }, selector);
}

(async () => {
    const browser = await chromium.launch();
    const page = await browser.newPage({ viewport: { width: 1280, height: 900 } });
    await page.goto(base + '/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', '123');
    await page.locator('form[action*="/login"] button[type=submit]').click();
    await page.waitForURL(/\/(dashboard|orders|admin\/posts)(\?|$)/);

    await page.goto(base + '/admin/posts');
    await page.waitForSelector('text=Progress Report Bouquet');
    await shot(page, 'web-admin-list.png');
    console.log('thumb', await box(page, 'main img'));

    await page.getByRole('button', { name: 'Preview photo of Progress Report Bouquet' }).click();
    await page.waitForSelector('text=Watermark preview');
    await page.waitForTimeout(300);
    await shot(page, 'web-preview.png');
    console.log('preview', await box(page, '[role=dialog] img'));

    await page.goto(base + '/admin/posts/create');
    await page.waitForSelector('text=Photo');
    await shot(page, 'web-create.png');

    await page.goto(base + '/admin/posts/101/edit');
    await page.waitForSelector('text=Click the photo');
    await shot(page, 'web-edit.png');

    await page.goto(base + '/bouquets/101');
    await page.waitForSelector('text=Progress Report Bouquet');
    await shot(page, 'web-detail.png');
    console.log('detail', await box(page, 'main img'));

    await page.goto(base + '/bouquets');
    await page.waitForSelector('text=Progress Report Bouquet');
    await shot(page, 'web-public-list.png');

    await browser.close();
})().catch((error) => {
    console.error(error);
    process.exit(1);
});
