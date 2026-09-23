const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const base = 'http://127.0.0.1:8000';
const outDir = path.join(__dirname, 'progress-shots');
fs.mkdirSync(outDir, { recursive: true });

async function shot(page, name) {
  const file = path.join(outDir, `${name}.png`);
  await page.screenshot({ path: file, fullPage: true });
  console.log('saved', file);
}

(async () => {
  const browser = await chromium.launch();
  const page = await browser.newPage({ viewport: { width: 1280, height: 800 } });

  await page.goto(base + '/');
  await shot(page, '01-home');

  await page.goto(base + '/about');
  await shot(page, '02-about');

  await page.goto(base + '/services');
  await shot(page, '03-services');

  await page.goto(base + '/bouquets');
  await shot(page, '04-bouquets-index');

  // Blade component + $slot + named badge slot visible on cards
  await shot(page, '04b-blade-component-slot');

  await page.goto(base + '/bouquets/1');
  await shot(page, '05-bouquet-param');

  await page.goto(base + '/contact');
  await shot(page, '06-contact-csrf-form');

  await page.goto(base + '/login');
  await shot(page, '07-login');

  await page.fill('input[name="email"]', 'test@test.com');
  await page.fill('input[name="password"]', '123');
  await page.click('button[type="submit"]');
  await page.waitForURL('**/dashboard');
  await shot(page, '08-dashboard');

  // themed Breeze member nav (layout customization)
  await shot(page, '08b-breeze-layout-nav');

  await page.goto(base + '/orders');
  await shot(page, '09-orders');

  await page.goto(base + '/favorites');
  await shot(page, '10-favorites');

  await page.goto(base + '/account');
  await shot(page, '11-account');

  const guest = await browser.newPage({ viewport: { width: 1280, height: 800 } });
  await guest.goto(base + '/orders');
  await guest.waitForURL('**/login');
  await shot(guest, '12-guest-redirect-login');

  await guest.goto(base + '/register');
  await shot(guest, '13-register-breeze');

  // final happy path: logged-in dashboard again as "testing / final output"
  await page.goto(base + '/dashboard');
  await shot(page, '15-final-member-dashboard');

  await browser.close();
  console.log('done');
})().catch((err) => {
  console.error(err);
  process.exit(1);
});
