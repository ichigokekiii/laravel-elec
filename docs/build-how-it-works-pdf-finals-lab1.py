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

EXAMPLE = "products/0f95b48d-2d8d-4a6e-8406-0aa6522e34ec.webp"


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
            "  file(1) says: WebP image, 800 x 800",
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
            "0f95b48d-2d8d-4a6e-8406-0aa6522e34ec",
            "              Str::uuid()  (8-4-4-4-12 hex)",
            ".webp         Format::WEBP, quality 80",
            "",
            "Not used: the visitor filename (rose.jpg)",
            "Not used: the database id",
            "Why: two uploads must not overwrite each other,",
            "and the file is written before the new id exists.",
            "",
            "Browser URL:",
            "  /storage/products/0f95b48d-2d8d-4a6e-8406-0aa6522e34ec.webp",
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
            "        WHERE id = 107;",
            "107 | Manual PDF PNG Upload",
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
            "Admin list box:  about 80px, same URL",
            "Edit thumb:      same URL",
            "Preview modal:   same URL, drawn larger",
            "Public detail:   same URL, min-height 420px",
            "",
            "Method they all call:",
            "  Post::displayImageUrl()",
            "  return '/storage/' + image column",
        ],
        "The thumbnail is the same WebP drawn small",
    )


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
        "This PDF walks one photo from the Add bouquet button to the folder.",
        "It names the watermark file, the saved WebP, and the database column.",
        "It also says why each name is shaped that way.",
        "",
        "Intervention does not write a separate thumbnail.",
        "The small admin photo is the same WebP, drawn small in the browser.",
        "",
        "Longer notes: docs/FINALS-LAB-ACT-1-CODE.md",
        "Demo login: admin@test.com / 123",
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
        "There are two picture files. public/images/watermark.png is the shared stamp. Its name stays watermark.png because AdminPostController always opens that path. storage/app/public/products/<uuid>.webp is the bouquet photo Intervention just encoded. A new UUID is used so a second upload cannot overwrite the first. The original JPG or PNG name is thrown away.",
    ),
    (
        APP / "public/images/watermark.png",
        "This is the actual stamp file, public/images/watermark.png. It is a PNG with a transparent background, 560 by 160 pixels on this machine. It is part of the project, not an upload. saveWatermarkedImage() scales it to at most 38 percent of the photo width, and never wider than 320 pixels, then paints it 12 pixels in from the bottom-right at 55 percent opacity.",
    ),
    (
        MANUAL / "10-admin-create-form.png",
        "The Add bouquet form posts to admin.posts.store. The Blade file is resources/views/admin/posts/create.blade.php. enctype=\"multipart/form-data\" is required or PHP never receives the file. The input name is image, which is the key PostRequest validates. @csrf is Laravel's check that the form came from our own site.",
    ),
    (
        MANUAL / "04-postrequest-image-rules.png",
        "PostRequest runs before the controller saves anything. On create, image is required. Allowed types are JPG, PNG, and WebP. max:3072 means 3 MB. On edit the rule is nullable, so a blank file input keeps the old photo. A failed rule redirects back to the form. saveWatermarkedImage() does not run, so no WebP is written.",
    ),
    (
        MANUAL / "05-save-watermarked-image.png",
        "saveWatermarkedImage() is private on AdminPostController. Image::decode() reads the upload so JPG and PNG become the same in-memory picture. scaleDown(width: 1000) shrinks only photos wider than 1000 pixels. insert() stamps the watermark. encodeUsingFormat(Format::WEBP, quality: 80) makes the bytes. Storage::disk('public')->put() writes them. The method returns the relative path. store() saves that string in showcase_flowers.image.",
    ),
    (
        SHOTS / "02-filename-parts.png",
        "The column does not store pixels. It stores products/ plus a UUID plus .webp. products/ keeps bouquet photos in one folder on the public disk. Str::uuid() runs before Post::create(), so the name cannot be the new row id yet. .webp matches Format::WEBP. The visitor filename is not used, because rose.jpg can be uploaded twice and can contain spaces.",
    ),
    (
        SHOTS / "03-symlink-and-row.png",
        "The public disk root is storage/app/public, from config/filesystems.php. Browsers cannot read that folder until php artisan storage:link creates public/storage as a shortcut. The URL is /storage/ plus the column value. Row 107 stores products/0f95b48d-2d8d-4a6e-8406-0aa6522e34ec.webp for Manual PDF PNG Upload. displayImageUrl() returns /storage/products/0f95b48d-2d8d-4a6e-8406-0aa6522e34ec.webp.",
    ),
    (
        APP / "storage/app/public/products/0f95b48d-2d8d-4a6e-8406-0aa6522e34ec.webp",
        "This figure is that saved file itself, not a screenshot of the page. The file command reports WebP, 800 by 800. Width stayed 800 because scaleDown only shrinks photos wider than 1000. The mark is in the bottom-right. This is the only image file Intervention wrote for that upload.",
    ),
    (
        SHOTS / "04-no-thumbnail-file.png",
        "There is no thumbnail file. The admin list, the edit page, the preview modal, and the public detail page all use Post::displayImageUrl(). CSS makes the admin box small. The modal and the detail page show the same URL larger, which is why you can see the watermark there. One file means force delete has one path to remove.",
    ),
    (
        MANUAL / "13-admin-edit-thumb.png",
        "The edit screen looks like a thumbnail. The img src is still /storage/products/<uuid>.webp. admin-photo-thumb only changes the box size. Click it and Alpine opens the preview with that same src. Seeded bouquets with an empty image column do not use this file. They fall back to public/images/bouquets/01.jpg through 06.jpg, which are not watermarked.",
    ),
    (
        MANUAL / "14-watermark-preview-modal.png",
        "The preview modal loads the same WebP. It does not ask Intervention to render again. Intervention already burned the mark into the file at save time. The helper text tells you to look at the bottom-right corner.",
    ),
    (
        MANUAL / "14b-watermark-closeup.png",
        "Close-up of the saved WebP. The mark is the contents of watermark.png after scaleDown and insert(). Offset 12, 12 is the gap from the bottom-right corner. 0.55 keeps 55 percent of the mark's opacity. The GD driver in Intervention treats 1 as full strength.",
    ),
    (
        MANUAL / "16-public-detail-photo.png",
        "The public detail page calls displayImageUrl() and puts it in an img tag. The page does not decode, resize, or watermark. If the photo looks unmarked, you opened a seeded row whose image column is empty. Open a bouquet you uploaded, such as Manual PDF PNG Upload.",
    ),
    (
        MANUAL / "11-admin-save-jpg-toast.png",
        "After a successful create, store() redirects to the admin list. The flash text is Bouquet added. Watermark applied. That text is the signal that saveWatermarkedImage() returned a path and Post::create() wrote the row. The original JPG is not in storage/app/public/products. Only the WebP is.",
    ),
    (
        MANUAL / "18-admin-trash-list.png",
        "Delete on the list calls destroy(), which sets deleted_at. The WebP stays in storage/app/public/products, so Trash can still show it. Delete forever calls forceDelete(), which removes the row and then Storage::disk('public')->delete() on that products/<uuid>.webp path. Restore does not move a file, because the file never left.",
    ),
]


def build():
    make_shots()
    if TMP.exists():
        TMP.unlink()
    c = canvas.Canvas(str(TMP), pagesize=letter)
    width, height = letter
    margin = 0.7 * inch
    cover_page(c, width, height, margin)
    for path, caption in PAGES:
        shot_page(c, width, height, margin, path, caption)
    c.save()
    os.replace(TMP, OUT)
    print("wrote", OUT)


if __name__ == "__main__":
    build()
