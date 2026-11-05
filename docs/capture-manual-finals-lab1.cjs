const path = require('path');
const fs = require('fs');
const { chromium } = require(path.join(__dirname, '..', 'petal-express', 'node_modules', 'playwright'));

const base = 'http://127.0.0.1:8000';
const outDir = path.join(__dirname, 'manual-shots-finals-lab1');
const sampleJpg = path.join(__dirname, '..', 'petal-express', 'public', 'images', 'bouquets', '02.jpg');
const samplePng = path.join(outDir, '_sample-upload.png');

fs.mkdirSync(outDir, { recursive: true });

async function shot(page, name) {
  const file = path.join(outDir, `${name}.png`);
  await page.screenshot({ path: file, fullPage: true });
  console.log('saved', file);
}

async function login(page, email, password) {
  await page.goto(base + '/login');
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await page.locator('form[action*="/login"] button[type=submit]').click();
  await page.waitForURL(/\/(dashboard|orders|admin\/posts)(\?|$)/);
}

(async () => {
  if (!fs.existsSync(samplePng)) {
    const { execSync } = require('child_process');
    try {
      execSync(`sips -s format png "${sampleJpg}" --out "${samplePng}"`, { stdio: 'ignore' });
    } catch {
      fs.copyFileSync(sampleJpg, samplePng);
    }
  }

  const browser = await chromium.launch();
  const context = await browser.newContext({ viewport: { width: 1280, height: 800 } });
  const adminPage = await context.newPage();
  await login(adminPage, 'admin@test.com', '123');

  await adminPage.goto(base + '/admin/posts/create');
  await shot(adminPage, '10-admin-create-form');

  const saveBouquet = () => adminPage.locator('form[action*="/admin/posts"] button:has-text("Save bouquet")');

  await adminPage.selectOption('#category_id', { index: 1 });
  await adminPage.fill('#title', 'Manual PDF JPG Upload');
  await adminPage.fill('#price', '1599');
  await adminPage.fill('#body', 'Instruction manual screenshot: JPG in, WebP with watermark out.');
  await adminPage.setInputFiles('#image', sampleJpg);
  await saveBouquet().click();
  await adminPage.waitForURL('**/admin/posts**');
  await adminPage.waitForTimeout(600);
  await shot(adminPage, '11-admin-save-jpg-toast');

  await adminPage.goto(base + '/admin/posts/create');
  await adminPage.selectOption('#category_id', { index: 1 });
  await adminPage.fill('#title', 'Manual PDF PNG Upload');
  await adminPage.fill('#price', '1699');
  await adminPage.fill('#body', 'Instruction manual screenshot: PNG in, same WebP pipeline.');
  await adminPage.setInputFiles('#image', samplePng);
  await saveBouquet().click();
  await adminPage.waitForURL('**/admin/posts**');
  await adminPage.waitForTimeout(600);
  await shot(adminPage, '12-admin-save-png-toast');

  const editLink = adminPage.locator('a:has-text("Edit")').first();
  await editLink.click();
  await adminPage.waitForURL('**/admin/posts/*/edit');
  await shot(adminPage, '13-admin-edit-thumb');

  const thumb = adminPage.locator('button img').first();
  if (await thumb.count()) {
    await thumb.click();
    await adminPage.waitForTimeout(400);
    await shot(adminPage, '14-watermark-preview-modal');
    const modalImg = adminPage.locator('[role="dialog"] img').last();
    if (await modalImg.count()) {
      await modalImg.screenshot({
        path: path.join(outDir, '14b-watermark-closeup.png'),
      });
      console.log('saved', path.join(outDir, '14b-watermark-closeup.png'));
    }
    await adminPage.keyboard.press('Escape');
  }

  await adminPage.goto(base + '/bouquets');
  await adminPage.waitForTimeout(400);
  await shot(adminPage, '15-public-showcase');

  const bouquetRow = adminPage.locator('a.rounded-2xl.border-gray-200').first();
  if (await bouquetRow.count()) {
    await bouquetRow.click();
    await adminPage.waitForTimeout(500);
    await shot(adminPage, '16-public-detail-photo');
  }

  const deleteBtn = adminPage.locator('form[action*="/admin/posts/"] button:has-text("Delete")').first();
  if (await deleteBtn.count()) {
    await adminPage.goto(base + '/admin/posts');
    await adminPage.locator('form[action*="/admin/posts/"] button:has-text("Delete")').first().click();
    await adminPage.waitForTimeout(500);
    await shot(adminPage, '17-admin-soft-delete-toast');
  }

  await adminPage.goto(base + '/admin/posts/trash');
  await adminPage.waitForTimeout(400);
  await shot(adminPage, '18-admin-trash-list');

  await browser.close();
  console.log('done');
})().catch((err) => {
  console.error(err);
  process.exit(1);
});
