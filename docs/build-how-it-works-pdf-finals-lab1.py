#!/usr/bin/env python3
"""PDF: how one bouquet upload becomes a watermarked WebP.

Same letter layout as the other lab PDFs. A caption sits above each figure.
"""
from pathlib import Path
import os
from PIL import Image, ImageDraw, ImageFont
from reportlab.lib.pagesizes import letter
from reportlab.lib.units import inch
from reportlab.pdfgen import canvas
from reportlab.lib.utils import ImageReader

ROOT = Path(__file__).resolve().parent
SHOTS = ROOT / "explain-shots-finals-lab1"
APP = ROOT.parent / "petal-express"
OUT = ROOT / "Acuesta_John_Constantine_Finals_LabActivity1_HOW_IT_WORKS.pdf"
TMP = ROOT / "_build_how_it_works_finals_lab1.tmp.pdf"
MANUAL = ROOT / "manual-shots-finals-lab1"
PROGRESS = ROOT / "progress-shots-finals-lab1"

EXAMPLE = "products/8bfef734-0640-407f-af5d-a6f47d24a581.webp"


def font_or_default(size: int):
    try:
        return ImageFont.truetype("/System/Library/Fonts/Menlo.ttc", size)
    except Exception:
        return ImageFont.load_default()


def terminal_shot(filename: str, lines: list[str], title: str) -> None:
    SHOTS.mkdir(parents=True, exist_ok=True)
    width, height = 1280, 800
    img = Image.new("RGB", (width, height), "#1e1e1e")
    draw = ImageDraw.Draw(img)
    title_font = font_or_default(18)
    body = font_or_default(16)
    draw.rectangle((0, 0, width, 36), fill="#2d2d2d")
    draw.text((16, 10), title, fill="#f5f5f5", font=title_font)
    y = 56
    for line in lines:
        draw.text((24, y), line[:110], fill="#d4d4d4", font=body)
        y += 22
        if y > height - 40:
            break
    img.save(SHOTS / filename)


def code_shot(filename: str, rel: str, start: int, end: int) -> None:
    """Editor-style figure. The title bar is the file path. Each row keeps its line number."""
    SHOTS.mkdir(parents=True, exist_ok=True)
    source = (APP / rel).read_text().splitlines()
    if start < 1 or end > len(source):
        raise SystemExit(f"{rel} has {len(source)} lines, asked {start}-{end}")

    body = font_or_default(22)
    header = font_or_default(20)
    wrapped = []
    for number in range(start, end + 1):
        text = source[number - 1].expandtabs(4)
        if text == "":
            wrapped.append((str(number), ""))
            continue
        first = True
        limit = 86
        while text:
            if len(text) <= limit:
                piece, text = text, ""
            else:
                cut = text.rfind(" ", 0, limit)
                if cut < 16:
                    cut = limit
                piece, text = text[:cut].rstrip(), text[cut:].lstrip()
            wrapped.append((str(number) if first else "", piece))
            first = False

    line_h = 30
    width = 1280
    height = 56 + line_h * len(wrapped) + 20
    img = Image.new("RGB", (width, height), "#ffffff")
    draw = ImageDraw.Draw(img)
    draw.rectangle((0, 0, width, 44), fill="#243044")
    draw.text((16, 12), "petal-express/" + rel, fill="#ffffff", font=header)
    y = 58
    for num, piece in wrapped:
        draw.text((12, y), num.rjust(4), fill="#6b7280", font=body)
        draw.text((96, y), piece, fill="#111827", font=body)
        y += line_h
    img.save(SHOTS / filename)


def ratio_diagram() -> None:
    width, height = 1280, 760
    img = Image.new("RGB", (width, height), "#f7f4ef")
    draw = ImageDraw.Draw(img)
    title = font_or_default(26)
    label = font_or_default(20)
    draw.text((40, 24), "Aspect ratio means width compared with height", fill="#111827", font=title)

    draw.rectangle((70, 110, 70 + 300, 110 + 431), outline="#4f6fa5", width=4)
    draw.text((70, 560), "Saved WebP", fill="#111827", font=label)
    draw.text((70, 592), "800 wide, 1150 tall", fill="#111827", font=label)
    draw.text((70, 624), "about 16 to 23", fill="#374151", font=label)
    draw.text((70, 656), "shape kept by scaleDown", fill="#374151", font=label)

    draw.rectangle((520, 220, 520 + 220, 220 + 220), outline="#111827", width=4)
    draw.text((520, 470), "Admin list box", fill="#111827", font=label)
    draw.text((520, 502), "48 by 48, from h-12 w-12", fill="#111827", font=label)
    draw.text((520, 534), "object-cover cuts the extra", fill="#374151", font=label)
    draw.text((520, 566), "height, so the mark can hide", fill="#374151", font=label)

    draw.rectangle((860, 120, 860 + 340, 120 + 420), outline="#9ca3af", width=3)
    inner_w = int(390 * 800 / 1150)
    x0 = 860 + (340 - inner_w) // 2
    draw.rectangle((x0, 135, x0 + inner_w, 135 + 390), fill="#f3c1b8", outline="#4f6fa5", width=4)
    draw.text((860, 560), "Preview box", fill="#111827", font=label)
    draw.text((860, 592), "object-contain", fill="#111827", font=label)
    draw.text((860, 624), "whole photo stays visible", fill="#374151", font=label)
    draw.text((860, 656), "empty space on the sides", fill="#374151", font=label)
    img.save(SHOTS / "06-aspect-ratio.png")


def watermark_closeup() -> None:
    src = Image.open(APP / "storage/app/public" / EXAMPLE).convert("RGB")
    width, height = src.size
    crop = src.crop((max(0, width - 460), max(0, height - 300), width, height))
    crop.save(SHOTS / "07-watermark-closeup.png")


def make_shots() -> None:
    terminal_shot(
        "01-two-files.png",
        [
            "Stamp (one shared file, committed with the app)",
            "  petal-express/public/images/watermark.png",
            "  PNG, 560 x 160, transparent background",
            "",
            "Saved bouquet photo (new file on every upload)",
            "  petal-express/storage/app/public/products/<uuid>.webp",
            f"  example: {EXAMPLE}",
            "  Pillow says: WebP, 800 x 1150",
            "",
            "These are not the same file.",
            "The stamp is not copied into products/.",
            "The upload's original name is not kept.",
        ],
        "Two picture files",
    )
    terminal_shot(
        "02-filename-parts.png",
        [
            "Stored in showcase_flowers.image:",
            f"  {EXAMPLE}",
            "",
            "products/     folder on the public disk",
            "8bfef734-0640-407f-af5d-a6f47d24a581",
            "              Str::uuid()  (8-4-4-4-12 hex)",
            ".webp         Format::WEBP, quality 80",
            "",
            "Not used: the visitor filename (rose.jpg)",
            "Not used: the database id",
            "Why: two uploads must not overwrite each other,",
            "and the file is written before the new id exists.",
            "",
            "Browser URL:",
            "  /storage/products/8bfef734-0640-407f-af5d-a6f47d24a581.webp",
        ],
        "Why the saved name looks like that",
    )
    terminal_shot(
        "03-symlink-and-row.png",
        [
            "$ ls -ld public/storage",
            "public/storage -> storage/app/public",
            "",
            "Disk root:  storage/app/public",
            "Web prefix: /storage",
            "Shortcut:   public/storage   (php artisan storage:link)",
            "",
            "sqlite> SELECT id, title, image FROM showcase_flowers",
            "        WHERE id = 101;",
            "101 | Progress Report Bouquet",
            f"    | {EXAMPLE}",
            "",
            "The column stores the path. It does not store pixels.",
        ],
        "Symlink and the database path",
    )
    terminal_shot(
        "04-no-thumbnail-file.png",
        [
            "Intervention writes ONE file:",
            f"  storage/app/public/{EXAMPLE}",
            "",
            "There is no products/<uuid>-thumb.webp.",
            "",
            "Admin list box:  h-12 w-12, 48 by 48, object-cover",
            "Preview modal:   same URL, object-contain",
            "Public detail:   same URL, object-cover, min-h 420px",
            "",
            "Method they all call:",
            "  Post::displayImageUrl()",
            "  return '/storage/' + image column",
        ],
        "The thumbnail is the same WebP drawn small",
    )
    terminal_shot(
        "05-display-lines.png",
        [
            "Post.php line 62, the URL for an upload:",
            "  return '/storage/'.ltrim($this->image, '/');",
            "",
            "Public detail, bouquets-show.blade.php line 5:",
            "  <img src=\"{{ $bouquet->displayImageUrl() }}\" ...>",
            "",
            "Public list, bouquets-index.blade.php line 47:",
            "  :image=\"$bouquet->displayImageUrl()\"",
            "Then showcase-list-item.blade.php line 14:",
            "  <img src=\"{{ $image }}\" class=\"h-20 w-20 ...\">",
            "",
            "Admin list, admin/posts/index.blade.php line 27:",
            "  <x-admin-photo-thumb :src=\"$post->displayImageUrl()\" />",
            "Then admin-photo-thumb.blade.php line 17:",
            "  <img src=\"{{ $src }}\" class=\"h-12 w-12 ... object-cover\">",
            "",
            "Do not demo /posts/{id}.",
            "posts/show.blade.php line 9 uses galleryImageUrl().",
        ],
        "Lines that put the WebP on the page",
    )
    ratio_diagram()
    watermark_closeup()
    code_ranges = [
        ("code-form-enctype.png", "resources/views/admin/posts/create.blade.php", 10, 11),
        ("code-form-input.png", "resources/views/admin/posts/create.blade.php", 46, 49),
        ("code-rules.png", "app/Http/Requests/PostRequest.php", 21, 33),
        ("code-messages.png", "app/Http/Requests/PostRequest.php", 68, 79),
        ("code-edit-image.png", "resources/views/admin/posts/edit.blade.php", 48, 57),
        ("code-middleware.png", "routes/web.php", 42, 46),
        ("code-store.png", "app/Http/Controllers/AdminPostController.php", 35, 50),
        ("code-decode.png", "app/Http/Controllers/AdminPostController.php", 148, 165),
        ("code-encode.png", "app/Http/Controllers/AdminPostController.php", 167, 185),
        ("code-disk.png", "config/filesystems.php", 41, 48),
        ("code-column.png", "database/migrations/2026_09_23_234450_create_showcase_flowers_table.php", 14, 24),
        ("code-display.png", "app/Models/Post.php", 56, 78),
        ("code-detail-img.png", "resources/views/pages/bouquets-show.blade.php", 4, 6),
        ("code-index-pass.png", "resources/views/pages/bouquets-index.blade.php", 44, 47),
        ("code-list-img.png", "resources/views/components/showcase-list-item.blade.php", 13, 16),
        ("code-admin-pass.png", "resources/views/admin/posts/index.blade.php", 24, 28),
        ("code-thumb.png", "resources/views/components/admin-photo-thumb.blade.php", 7, 17),
        ("code-preview.png", "resources/views/components/admin-photo-preview-shell.blade.php", 34, 40),
        ("code-member-show.png", "resources/views/posts/show.blade.php", 8, 9),
        ("code-destroy.png", "app/Http/Controllers/AdminPostController.php", 97, 104),
        ("code-force.png", "app/Http/Controllers/AdminPostController.php", 127, 141),
    ]
    for name, rel, start, end in code_ranges:
        code_shot(name, rel, start, end)


def wrap(c, text, x, y, max_width, font="Helvetica", size=11, leading=15):
    words = text.split()
    line = ""
    for word in words:
        trial = (line + " " + word).strip()
        if c.stringWidth(trial, font, size) <= max_width:
            line = trial
        else:
            if line:
                c.drawString(x, y, line)
                y -= leading
            line = word
    if line:
        c.drawString(x, y, line)
        y -= leading
    return y


def cover_page(c, width, height, margin):
    y = height - margin - 10
    c.setFont("Helvetica-Bold", 18)
    c.drawString(margin, y, "How a bouquet upload is saved")
    y -= 28
    c.setFont("Helvetica", 12)
    lines = [
        "Student file: Acuesta_John_Constantine_Finals_LabActivity1_HOW_IT_WORKS.pdf",
        "Theme: Petal Express (Laravel 11 + Blade + Breeze, SQLite).",
        "",
        "Part 1 is the reading, in plain words, including aspect ratio.",
        "Part 2 is one figure per page. A code figure shows the file",
        "name in the title bar and the real line numbers beside it.",
        "A page figure is the website at 1280 by 900.",
        "",
        "You upload a JPG, a PNG, or a WebP. Intervention writes one",
        "watermarked WebP. Blade prints that URL in an img tag.",
        "There is no separate thumbnail file.",
        "",
        "Notes: docs/FINALS-LAB-ACT-1-CODE.md",
        "Demo login: admin@test.com / 123",
        "Show the watermark on /bouquets/{id}, not on /posts/{id}.",
    ]
    for line in lines:
        if line == "":
            y -= 12
            continue
        y = wrap(c, line, margin, y, width - 2 * margin, size=12, leading=16)
        y -= 4
    c.showPage()


def shot_page(c, width, height, margin, path: Path, caption: str):
    content_width = width - (2 * margin)
    c.setFont("Helvetica", 11)
    caption_bottom = wrap(c, caption, margin, height - margin, content_width)
    if path.exists():
        img = ImageReader(str(path))
        iw, ih = img.getSize()
        gap = 0.2 * inch
        top = caption_bottom - gap
        max_h = max(top - margin, 1)
        scale = min(content_width / iw, max_h / ih)
        draw_w = iw * scale
        draw_h = ih * scale
        y = top - draw_h
        c.drawImage(img, margin, y, width=draw_w, height=draw_h, preserveAspectRatio=True, mask="auto")
    else:
        c.drawString(margin, caption_bottom - 20, f"Missing image: {path}")
    c.showPage()


PAGES = [
    (
        SHOTS / "01-two-files.png",
        "There are two picture files. public/images/watermark.png is the shared stamp. The controller always opens that exact name. storage/app/public/products/<uuid>.webp is the bouquet photo. A new UUID keeps a second upload from replacing the first. The visitor's original filename is discarded.",
    ),
    (
        APP / "public/images/watermark.png",
        "This picture is the stamp file itself, public/images/watermark.png. The pixels are the words petal express. The file is a PNG, 560 by 160, with a transparent background. saveWatermarkedImage() does not draw those letters. It inserts this PNG. A transparent word mark stays readable on flowers. A second floral picture would cover the bouquet. The font and the color already live in the file, so the upload does not depend on a font installed on the laptop.",
    ),
    (
        SHOTS / "web-create.png",
        "This is the Add bouquet page in the browser, /admin/posts/create, at 1280 by 900. The Photo field is the new part. JPG, PNG, or WebP, max 3 MB. The rest of the form is the same bouquet card from Labs 2 and 3.",
    ),
    (
        SHOTS / "code-form-enctype.png",
        "The dark bar is the file name, petal-express/resources/views/admin/posts/create.blade.php. Lines 10 and 11 are the whole form tag. enctype=\"multipart/form-data\" is what puts the photo bytes in the request. A normal form sends text only. @csrf is Laravel's check that the form came from this site.",
    ),
    (
        SHOTS / "code-form-input.png",
        "Same file, lines 46 to 49. The input name is image. PostRequest looks for that exact name. accept is only a hint to the file picker. The real check is the PHP rules on the next figure.",
    ),
    (
        SHOTS / "code-rules.png",
        "File petal-express/app/Http/Requests/PostRequest.php, lines 21 to 33. On create, image is required. On edit it is nullable, so a blank file input keeps the old photo. image means PHP recognizes a picture. mimes allows jpg, jpeg, png, and webp. max:3072 is kilobytes, so the cap is 3 MB. A failed rule sends the admin back to the form. The save method never starts.",
    ),
    (
        SHOTS / "code-middleware.png",
        "File petal-express/routes/web.php, lines 42 to 46. auth and role:admin run before the controller. A guest goes to login. A member gets 403. Intervention does not run for them. POST /admin/posts is the URL the form uses.",
    ),
    (
        SHOTS / "code-store.png",
        "File petal-express/app/Http/Controllers/AdminPostController.php, lines 35 to 50. store() does not resize the photo. Line 44 calls saveWatermarkedImage and stores the string it returns in the image column. The flash text is Bouquet added. Watermark applied.",
    ),
    (
        SHOTS / "code-decode.png",
        "Same controller, lines 148 to 165. Image::decode reads the upload, so a JPG and a PNG become the same in-memory picture. scaleDown(width: 1000) shrinks only a photo wider than 1000. Height changes by the same factor, so the aspect ratio stays. The stamp path is public/images/watermark.png. For an 800-pixel-wide photo, 38 percent is 304, which is under the 320 cap, so the mark is scaled to 304 wide.",
    ),
    (
        SHOTS / "code-encode.png",
        "Same file, lines 167 to 185. insert paints the mark 12 pixels in from the bottom-right at 0.55 opacity. The GD driver treats 1 as full strength. The saved name is products/ plus Str::uuid() plus .webp. encodeUsingFormat writes WebP at quality 80. Storage::disk('public')->put writes the bytes. The method returns that path, and that path is all the database stores.",
    ),
    (
        SHOTS / "02-filename-parts.png",
        "Read the stored string in three pieces. products/ is the folder. The middle is the UUID from Str::uuid(), shaped 8-4-4-4-12. .webp matches Format::WEBP. The file is written before the new row id exists, so the name is not the id. The browser URL adds /storage/ in front.",
    ),
    (
        SHOTS / "code-disk.png",
        "File petal-express/config/filesystems.php, lines 41 to 48. The public disk root is storage_path('app/public'), which is petal-express/storage/app/public. The url line would prefix APP_URL. displayImageUrl() skips that helper and returns a path that starts with /storage/.",
    ),
    (
        SHOTS / "code-column.png",
        "File petal-express/database/migrations/2026_09_23_234450_create_showcase_flowers_table.php, lines 14 to 24. The table is showcase_flowers. Line 20 makes image a nullable string. The column holds the path. It does not hold the pixels.",
    ),
    (
        SHOTS / "03-symlink-and-row.png",
        "public/storage is a shortcut to storage/app/public, from php artisan storage:link. Row 101, Progress Report Bouquet, stores products/8bfef734-0640-407f-af5d-a6f47d24a581.webp. The browser asks for /storage/ plus that string.",
    ),
    (
        APP / "storage/app/public" / EXAMPLE,
        "This figure is the saved file itself, storage/app/public/products/8bfef734-0640-407f-af5d-a6f47d24a581.webp. Pillow measures it at 800 by 1150. That ratio is about 16 to 23. Width stayed 800 because it was already under 1000, and height stayed in proportion. The mark is in the bottom-right. This is the only image file Intervention wrote for that upload.",
    ),
    (
        SHOTS / "07-watermark-closeup.png",
        "Close-up of the bottom-right of that same WebP. The words are the stamp after scaleDown and insert. The gap from the corner is 12 pixels. 0.55 keeps 55 percent of the mark's strength.",
    ),
    (
        SHOTS / "06-aspect-ratio.png",
        "A diagram of the same photo in three boxes. The file stays 800 by 1150. The admin list forces a 48 by 48 square and object-cover cuts the extra height, which can hide the mark. The preview uses object-contain, so the whole tall photo stays visible and empty space appears on the sides.",
    ),
    (
        SHOTS / "web-admin-list.png",
        "The admin Showcase page at 1280 by 900. Each row is the Lab 2 and 3 layout, with the watermarked photo on the left and View, Edit, and Delete on the same line. Progress Report Bouquet's small photo is the 800 by 1150 file drawn in a 48 by 48 square. The square shows the middle of the flowers. The mark is in the part object-cover cuts off.",
    ),
    (
        SHOTS / "code-admin-pass.png",
        "File petal-express/resources/views/admin/posts/index.blade.php, lines 24 to 28. Line 27 is the list photo. :src=\"$post->displayImageUrl()\" is the full WebP URL. The component only decides how big the box is.",
    ),
    (
        SHOTS / "code-thumb.png",
        "File petal-express/resources/views/components/admin-photo-thumb.blade.php, lines 7 to 17. Line 8 sets the box to h-12 w-12, 48 by 48, unless size is lg. Line 15 sends the same src to the preview. Line 17 is the img tag. object-cover fills the square and crops. There is still one file.",
    ),
    (
        SHOTS / "web-preview.png",
        "Clicking the Progress Report Bouquet photo opens this preview. Playwright measured the img box at 846 by 675 on a 1280 by 900 window. The file inside is still 800 by 1150. object-contain fits the whole shape, so the petal express mark is visible at the bottom-right. Intervention does not run again for this click.",
    ),
    (
        SHOTS / "code-preview.png",
        "File petal-express/resources/views/components/admin-photo-preview-shell.blade.php, lines 34 to 40. :src=\"previewSrc\" is the URL the thumb already had. object-contain keeps the aspect ratio and letterboxes. max-h-[75vh] stops the photo from growing past 75 percent of the window height.",
    ),
    (
        SHOTS / "web-detail.png",
        "The public page /bouquets/101. This is the page to demo. Playwright measured the photo box at 414 by 595. 414 to 595 matches 800 to 1150, so on this window object-cover shows the whole file, mark included. The page only prints an img tag.",
    ),
    (
        SHOTS / "code-detail-img.png",
        "File petal-express/resources/views/pages/bouquets-show.blade.php, lines 4 to 6. Line 5 is the public img tag. displayImageUrl() runs here, when Blade prints the src. object-cover and min-h-[420px] are the box. They do not change the file.",
    ),
    (
        SHOTS / "web-public-list.png",
        "The public showcase list. The small photo on each row is the same WebP URL in a larger CSS box, h-20 w-20, which is 80 by 80, and 96 by 96 from the sm: breakpoint. object-cover is on that box too.",
    ),
    (
        SHOTS / "code-index-pass.png",
        "File petal-express/resources/views/pages/bouquets-index.blade.php, lines 44 to 47. Line 47 passes displayImageUrl() into the list component. home.blade.php line 55 and member/favorites.blade.php line 19 do the same call.",
    ),
    (
        SHOTS / "code-list-img.png",
        "File petal-express/resources/views/components/showcase-list-item.blade.php, lines 13 to 16. Line 14 is the img src. The classes h-20 w-20 and object-cover are why the list looks like a thumbnail. The src is still the full WebP.",
    ),
    (
        SHOTS / "code-display.png",
        "File petal-express/app/Models/Post.php, lines 56 to 78. Line 62 is the line that displays an upload. It returns /storage/ plus the column. galleryImageUrl() on line 70 runs only when the column is empty or the file is missing. It picks public/images/bouquets/01.jpg through 06.jpg. Those JPGs have no watermark.",
    ),
    (
        SHOTS / "code-member-show.png",
        "File petal-express/resources/views/posts/show.blade.php, lines 8 and 9. Line 9 calls galleryImageUrl(), so /posts/{id} shows a packed JPG even when the row has a WebP. Demo the watermark on /bouquets/{id}.",
    ),
    (
        SHOTS / "web-edit.png",
        "The edit page for bouquet 101. The photo is the same WebP at size lg, 96 by 96. Leave the file input empty and the old path stays. Choose a new file and the controller writes the new WebP before it deletes the old one.",
    ),
    (
        SHOTS / "04-no-thumbnail-file.png",
        "Intervention writes one file. There is no products/<uuid>-thumb.webp. The list, the edit page, the preview, and /bouquets/101 all request that one URL. CSS decides the box. Delete forever removes that one path.",
    ),
    (
        SHOTS / "code-destroy.png",
        "File petal-express/app/Http/Controllers/AdminPostController.php, lines 97 to 104. destroy() calls delete(), which only sets deleted_at. The WebP stays in storage/app/public/products, so Trash can still show it.",
    ),
    (
        SHOTS / "code-force.png",
        "Same file, lines 127 to 141. forceDelete copies the path, removes the row, then Storage::disk('public')->delete on products/<uuid>.webp. The route uses withTrashed() so Laravel can still load a row that is already in Trash. Restore does not move a file, because the file never left.",
    ),
    (
        SHOTS / "code-decode.png",
        "File petal-express/app/Http/Controllers/AdminPostController.php, lines 148 to 165. Intervention is used for more than the stamp. Image::decode reads a JPG, a PNG, or a WebP into one picture. scaleDown shrinks the photo when it is wider than 1000 pixels and keeps the aspect ratio. The same scaleDown then sizes watermark.png. There is no text() call and no blur, crop, or flip.",
    ),
    (
        SHOTS / "code-rules.png",
        "File petal-express/app/Http/Requests/PostRequest.php, lines 21 to 33. On Add bouquet, title, body, price, category, and image are already required. On Edit, line 24 says nullable for the photo, so an empty file input is allowed. If sir says make the edit photo required, change nullable to required on that line. The input name in Blade must stay image, because that is the key in this array.",
    ),
    (
        SHOTS / "code-edit-image.png",
        "File petal-express/resources/views/admin/posts/edit.blade.php, lines 48 to 57. Line 56 is the file input. It has no required attribute. create.blade.php line 48 already has required. For the live change, add required to this edit input as well. The browser then blocks an empty photo. PostRequest is still the check that matters if the browser check is skipped.",
    ),
    (
        SHOTS / "code-messages.png",
        "File petal-express/app/Http/Requests/PostRequest.php, lines 68 to 79. image.required already says Please upload a photo for this bouquet. After you change nullable to required, submit Edit with no new file. That sentence appears under Photo, and saveWatermarkedImage() does not run. You do not need a migration. required here is a form rule, not a new column.",
    ),
]


BOOK = [
    ("h", "1. What this lab adds"),
    ("p", "Labs 2 and 3 already save a bouquet row, check the admin role, validate the form, and soft-delete the row. This lab adds the photo. The visitor may send a JPG, a PNG, or a WebP. The app does not keep that file."),
    ("p", "Intervention Image reads the upload, shrinks it if it is wider than 1000 pixels, stamps public/images/watermark.png on the bottom-right, and encodes a new WebP. Laravel stores the path in showcase_flowers.image. Blade later prints that path inside an img tag. The browser downloads the file. Intervention does not run again on the way out."),
    ("h", "2. Words you need"),
    ("p", "A route is the URL. POST /admin/posts is the Add bouquet form. A controller is the PHP class for that URL. Ours is AdminPostController. A form request checks the fields first. Ours is PostRequest. A model is the class for one table. Post uses the table showcase_flowers, not a table named posts."),
    ("p", "A migration created the table. The photo column is a string named image. It holds a path, not pixels. Blade is the HTML file. A disk is Laravel's name for a folder. The public disk root is storage/app/public, set in config/filesystems.php."),
    ("h", "3. The two files, and why the names differ"),
    ("p", "The stamp is petal-express/public/images/watermark.png. The name stays watermark.png because saveWatermarkedImage() always opens that exact path. It is part of the project. You do not upload it per bouquet. On this machine it is a PNG, 560 by 160, with a transparent background."),
    ("p", "The bouquet photo is storage/app/public/products/ plus a UUID plus .webp. products/ keeps uploads in one folder. Str::uuid() makes a random 8-4-4-4-12 id so two files named rose.jpg cannot overwrite each other. The file is written before Post::create() knows the new row id, so the name is not the id. .webp matches Format::WEBP. The original filename is discarded."),
    ("p", "A real row on this machine is bouquet 101, Progress Report Bouquet. The column stores products/8bfef734-0640-407f-af5d-a6f47d24a581.webp. Pillow reports WebP, 800 by 1150. Width stayed 800 because the upload was narrower than 1000. Height stayed 1150, so the shape stayed tall."),
    ("h", "4. How the browser is allowed to see storage"),
    ("p", "The web server may read the public folder. It may not read storage/app/public by itself. php artisan storage:link creates public/storage as a shortcut to storage/app/public. The browser URL is /storage/ plus the column value, with no drive letter and no storage/app prefix."),
    ("p", "For bouquet 101 the tag src is /storage/products/8bfef734-0640-407f-af5d-a6f47d24a581.webp. displayImageUrl() returns a path starting with /storage/. The browser follows public/storage to the file."),
    ("h", "5. What happens when you click Save bouquet"),
    ("p", "create.blade.php posts to the route admin.posts.store. enctype=\"multipart/form-data\" is required. Without it, PHP never receives the file. The input name is image. @csrf is Laravel's check that the form came from our site."),
    ("p", "routes/web.php runs auth and role:admin before the controller. A guest is sent to login. A member gets 403. Intervention does not run for them."),
    ("p", "PostRequest then checks image. On create the rules are required, image, mimes jpg jpeg png webp, and max 3072. max is kilobytes, so the cap is 3 MB. On edit, required becomes nullable. A blank file input keeps the old photo. If a rule fails, Laravel returns to the form and saveWatermarkedImage() never starts."),
    ("p", "store() does not resize the photo itself. It calls saveWatermarkedImage($request->file('image')) and puts the returned string in the image column, next to title, body, price, category_id, and auth()->id(). The redirect flash is Bouquet added. Watermark applied."),
    ("h", "6. What Intervention does, line by line"),
    ("p", "Image::decode($file) reads the bytes. After that line the code does not care that the original was JPG or PNG. This machine's PHP has the gd extension, so Intervention uses GD. The controller does not name the driver."),
    ("p", "scaleDown(width: 1000) shrinks the photo only when it is wider than 1000 pixels. Height changes by the same factor. A tall photo stays tall. A square stays square. favorite ceo is 1000 by 1000 because that upload was a square wider than 1000."),
    ("p", "public_path('images/watermark.png') opens the stamp. If the file is missing, the method throws Watermark not found before Post::create(), so you get no row and no WebP. The mark is scaled to 38 percent of the photo width and never wider than 320 pixels."),
    ("p", "insert($watermark, 12, 12, Alignment::BOTTOM_RIGHT, 0.55) paints it. The 12s are the gap from the corner. BOTTOM_RIGHT picks the corner. 0.55 keeps 55 percent of the mark's opacity. In the library the argument is named transparency, but the GD driver treats 1 as full strength."),
    ("p", "The path is products/ plus Str::uuid() plus .webp. encodeUsingFormat(Format::WEBP, quality: 80) makes the bytes. Storage::disk('public')->put() writes them under storage/app/public. The method returns the path. That return value is the only thing the database stores. PHP deletes the temp upload at the end of the request."),
    ("h", "6a. Why the mark is words inside a PNG"),
    ("p", "The stamp looks like the words petal express. The file is still an image, public/images/watermark.png, a PNG that is 560 by 160 with a transparent background. saveWatermarkedImage() never draws letters. It calls Image::decode on that PNG, then insert()."),
    ("p", "Intervention can draw text with a text() method. This lab does not use it. text() needs a font file on the computer that runs the upload. If that font is missing in the demo, every save fails. The PNG already has the font, the blue color, and the letter spacing, so the controller only has to place the file."),
    ("p", "A picture of flowers would cover the bouquet. Words with a transparent background stay readable in the corner and leave the flowers visible. One PNG is shared by every upload. The controller always opens the same path."),
    ("h", "6c. What else Intervention does"),
    ("p", "The watermark is one step. The same method uses Intervention for the rest of the photo. Image::decode reads the upload, so JPG, PNG, and WebP become one picture. scaleDown(width: 1000) resizes the photo and keeps the aspect ratio. scaleDown runs again on the stamp. insert() paints the stamp. encodeUsingFormat(Format::WEBP, quality: 80) makes the WebP bytes."),
    ("p", "Alignment::BOTTOM_RIGHT and Format::WEBP are settings for those calls. They are not extra effects. This lab does not blur, crop, flip, or build a second thumbnail file. CSS shrinks the one WebP on the page."),
    ("h", "6b. Aspect ratio, in plain words"),
    ("p", "Aspect ratio is how wide a picture is compared with how tall it is. A square is 1 to 1. The stamp is 560 by 160, which is 3.5 to 1. Bouquet 101 is 800 by 1150, about 16 to 23."),
    ("p", "The admin list box is h-12 and w-12. That is 48 by 48 CSS pixels, a square. The class object-cover fills the square and cuts off the extra height. For an 800 by 1150 file, the square keeps the middle and cuts about 175 pixels from the top and 175 from the bottom. The mark sits in that bottom strip, so the small list photo can hide it."),
    ("p", "The preview class is object-contain. That fits the whole photo in the box and leaves empty space on the sides. On a 1280 by 900 window the preview box measured 846 by 675, and the file inside it is still 800 by 1150. The mark shows there."),
    ("p", "The public detail class is object-cover with a minimum height of 420 pixels. On that same window the box measured 414 by 595. That matches 800 to 1150, so this screen shows the whole photo, including the mark. A different window width can cut it, because the class is still object-cover."),
    ("h", "7. The lines that display the image"),
    ("p", "Saving the file does not show it. An img tag shows it. The src must be a URL. One method builds that URL for every screen that should show the upload."),
    ("code", "Post.php lines 57-66"),
    ("code", "if ($this->image) {"),
    ("code", "    if (Storage::disk('public')->exists($this->image)) {"),
    ("code", "        return '/storage/'.ltrim($this->image, '/');"),
    ("code", "    }"),
    ("code", "}"),
    ("code", "return $this->galleryImageUrl();"),
    ("p", "Line 59 skips empty columns. Line 61 checks the file is really on the public disk. Line 62 is the display line for an upload. For bouquet 101 it returns /storage/products/8bfef734-0640-407f-af5d-a6f47d24a581.webp. Storage::url() would prefix APP_URL. A demo at 127.0.0.1:8000 would then request localhost and the image would break. The path that starts with /storage/ uses the host already in the address bar."),
    ("p", "galleryImageUrl() at line 70 runs only when there is no upload. It cycles public/images/bouquets/01.jpg through 06.jpg by row id. Those JPGs are not watermarked. Seeded rows use them."),
    ("p", "The controller only loads the row. PageController::bouquetsShow lines 57 to 61 call findOrFail($id) and return view('pages.bouquets-show'). The method displayImageUrl() runs later, inside the Blade file, when the img tag is printed."),
    ("code", "bouquets-show.blade.php line 5"),
    ("code", "<img src=\"{{ $bouquet->displayImageUrl() }}\" alt=\"{{ $bouquet->title }}\">"),
    ("p", "The double braces print the string. The browser requests that src. The shortcut public/storage serves the WebP. Intervention is not called on this request."),
    ("p", "The public list does the same call one step earlier. bouquets-index.blade.php line 47, home.blade.php line 55, and member/favorites.blade.php line 19 pass :image=\"$bouquet->displayImageUrl()\" into the showcase-list-item component. The tag is showcase-list-item.blade.php lines 13 to 16, src=\"{{ $image }}\", with classes h-20 w-20. Those classes are the whole thumbnail. They shrink the same WebP in the browser."),
    ("p", "The admin list is admin/posts/index.blade.php line 27, the component tag whose src is $post->displayImageUrl(). Trash repeats that call at trash.blade.php line 17. The img tag is admin-photo-thumb.blade.php line 17. The classes are h-12 w-12 and object-cover. Edit uses the same component at edit.blade.php line 51 with size lg, which is h-24 w-24. The button on line 15 sends that same src into the modal. The modal img is admin-photo-preview-shell.blade.php lines 34 to 37, with object-contain. Still one file."),
    ("p", "Do not demo the member page /posts/{id} for the watermark. posts/show.blade.php line 9 calls $post->galleryImageUrl(), not displayImageUrl(). That page shows a packed JPG even when the row has a WebP. The public URL /bouquets/{id} is the page that uses line 5 above."),
    ("h", "8. Replace, trash, and delete the file"),
    ("p", "Edit with no new file leaves the column alone. hasFile('image') is false, so Intervention does not run. Edit with a new file writes the new WebP first, updates the row, then deletes the old path. If the update throws, the new file is deleted and the old path stays. The row never points at a file that was already erased."),
    ("p", "Delete on the list calls destroy(), which sets deleted_at. The WebP stays, so Trash can still call displayImageUrl(). Delete in Trash copies the path, removes the row, then Storage::disk('public')->delete() on that products/<uuid>.webp path. The route uses withTrashed() so Laravel can still find a trashed row. Restore does not move a file."),
    ("h", "9. What the test proves"),
    ("p", "php artisan test --filter=LabActFourInterventionTest uses a fake public disk. It posts a JPG and asserts the stored path starts with products/ and ends with .webp. A second test soft-deletes and asserts the file remains. A third force-deletes and asserts the file is gone. The test does not look at watermark pixels. You show the mark with your eyes on /bouquets/{id} or in the admin preview."),
    ("h", "10. If sir says make a field required"),
    ("p", "On Add bouquet these are already required. Name is title. Description is body. Price is price. Category is category_id. Photo is image. The rules are PostRequest.php lines 28 to 32. The matching Blade tags in create.blade.php already have the HTML attribute required. Price also uses a regex of 1 to 16 digits, so a decimal is rejected."),
    ("p", "Edit is the field that can change in the room. PostRequest.php line 24 uses nullable for image when the method is PUT. edit.blade.php line 56 is a file input with no required attribute. An empty photo on Edit keeps the old file. If sir says make the photo required, that is the change."),
    ("p", "First, in PostRequest.php line 24, replace nullable with required. The line becomes required, image, mimes jpg jpeg png webp, and max 3072. Leave the input name as image. Second, in edit.blade.php line 56, add the attribute required on the file input, the same word create.blade.php line 48 already has. Third, do not add a migration. The image column can stay nullable in the table. The form rule is what sir is asking for."),
    ("p", "The message is already written. messages() has image.required set to Please upload a photo for this bouquet. Save both files. Open a bouquet, choose Edit, leave Photo empty, and click Save changes. Laravel returns to the form and shows that sentence under Photo. saveWatermarkedImage() does not run, so the old WebP stays."),
    ("p", "If sir points at a field that is already required, say the line and submit the form with that field empty. Do not add required a second time. If sir names a different field, use the same two places. The rule key in PostRequest must match the name attribute on the Blade tag. Put required in the rule array. Put required on the tag. Add a messages() line only when you want your own sentence. The HTML attribute stops a normal browser. PostRequest still checks the request when that attribute is skipped. Show both."),
    ("h", "11. What to say if you forget a line"),
    ("p", "The stamp is public/images/watermark.png. It is a PNG of the words petal express, not letters drawn by Intervention. The other Intervention calls are decode, scaleDown, insert, and encodeUsingFormat to WebP. The saved photo is storage/app/public/products/<uuid>.webp. The display line is Post.php line 62. The public img tag is bouquets-show.blade.php line 5. If sir says make the edit photo required, change nullable to required on PostRequest.php line 24 and add required on edit.blade.php line 56."),
]


def draw_book(c, width, height, margin):
    content_width = width - (2 * margin)
    page = 2
    y = height - margin

    def footer():
        c.setFont("Helvetica", 9)
        c.drawRightString(width - margin, 0.45 * inch, str(page))
        c.drawString(margin, 0.45 * inch, "Finals Lab Activity 1 guidebook")

    def new_page():
        nonlocal y, page
        footer()
        c.showPage()
        page += 1
        y = height - margin

    for kind, text in BOOK:
        need = 36 if kind == "h" else 28
        if y < margin + need:
            new_page()
        if kind == "h":
            y -= 8
            c.setFont("Helvetica-Bold", 13)
            y = wrap(c, text, margin, y, content_width, "Helvetica-Bold", 13, 16)
            y -= 6
        elif kind == "code":
            c.setFont("Courier", 9)
            c.drawString(margin + 8, y, text[:100])
            y -= 12
        else:
            c.setFont("Helvetica", 11)
            y = wrap(c, text, margin, y, content_width, "Helvetica", 11, 14)
            y -= 8
    footer()
    c.showPage()


def build():
    make_shots()
    if TMP.exists():
        TMP.unlink()
    c = canvas.Canvas(str(TMP), pagesize=letter)
    width, height = letter
    margin = 0.7 * inch
    cover_page(c, width, height, margin)
    draw_book(c, width, height, margin)
    for path, caption in PAGES:
        shot_page(c, width, height, margin, path, caption)
    c.save()
    os.replace(TMP, OUT)
    print("wrote", OUT)


if __name__ == "__main__":
    build()
