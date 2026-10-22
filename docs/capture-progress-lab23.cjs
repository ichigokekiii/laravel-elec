const path = require('path');
const fs = require('fs');
const { chromium } = require(path.join(__dirname, '..', 'petal-express', 'node_modules', 'playwright'));

const base = 'http://127.0.0.1:8000';
const outDir = path.join(__dirname, 'progress-shots-lab23');
const sampleImage = path.join(__dirname, '..', 'petal-express', 'public', 'images', 'bouquets', '01.jpg');
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
  await page.locator('form[action*="/login"] button[type="submit"]').click();
  await page.waitForURL(/\/(dashboard|orders|admin\/posts)(\?|$)/);
}

async function clearSession(context) {
  await context.clearCookies();
}

(async () => {
  const browser = await chromium.launch();
  const context = await browser.newContext({ viewport: { width: 1280, height: 800 } });
  const page = await context.newPage();

  await login(page, 'test@test.com', '123');
  await page.goto(base + '/admin/posts');
  await page.waitForTimeout(400);
  await shot(page, '20-user-403-admin');

  await clearSession(context);
  const adminPage = await context.newPage();
  await login(adminPage, 'admin@test.com', '123');
  await adminPage.goto(base + '/posts/submit');
  await adminPage.waitForTimeout(400);
  await shot(adminPage, '21-admin-403-user-submit');

  await adminPage.goto(base + '/admin/posts');
  await shot(adminPage, '22-admin-posts-list');

  await adminPage.goto(base + '/admin/posts/create');
  await shot(adminPage, '23-admin-create-form');

  const saveBouquet = () => adminPage.locator('form[action*="/admin/posts"] button:has-text("Save bouquet")');

  await adminPage.goto(base + '/admin/posts/create');
  await saveBouquet().click();
  await adminPage.waitForTimeout(500);
  await shot(adminPage, '34-validation-errors-toast');

  await adminPage.selectOption('#category_id', { index: 1 });
  await adminPage.fill('#title', 'Progress Report Bouquet');
  await adminPage.fill('#price', '1499');
  await adminPage.fill('#body', 'Added for the Lab Act 2 and 3 progress PDF screenshot.');
  await adminPage.setInputFiles('#image', sampleImage);
  await saveBouquet().click();
  await adminPage.waitForTimeout(800);
  await shot(adminPage, '24-admin-save-success-toast');

  await clearSession(context);
  const userPage = await context.newPage();
  await login(userPage, 'test@test.com', '123');
  await userPage.goto(base + '/posts/1');
  await shot(userPage, '25-user-post-show');

  await userPage.goto(base + '/posts/submit');
  await shot(userPage, '26-user-submit-static-form');

  const guest = await browser.newPage({ viewport: { width: 1280, height: 800 } });
  await guest.goto(base + '/bouquets');
  await shot(guest, '30-bouquets-categories');

  await guest.goto(base + '/bouquets?page=2');
  await shot(guest, '31-bouquets-pagination');

  await guest.goto(base + '/bouquets?category=1');
  await shot(guest, '30b-bouquets-category-filter');

  await guest.goto(base + '/');
  await shot(guest, '32-home-latest-six');

  await guest.close();

  await adminPage.goto(base + '/admin/posts');
  await shot(adminPage, '33-admin-pagination');

  const deleteBtn = adminPage.locator('form[action*="/admin/posts/"] button:has-text("Delete")').first();
  if (await deleteBtn.count()) {
    await deleteBtn.click();
    await adminPage.waitForURL('**/admin/posts**');
    await adminPage.waitForTimeout(400);
  }
  await adminPage.goto(base + '/admin/posts/trash');
  await shot(adminPage, '36-admin-trash');

  await browser.close();
  console.log('done');
})().catch((err) => {
  console.error(err);
  process.exit(1);
});
