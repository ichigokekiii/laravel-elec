#!/usr/bin/env python3
"""Build instruction PDF: Acuesta_John_Constantine_Finals_LabActivity1_INSTRUCTION.pdf"""
from pathlib import Path
import os
from reportlab.lib.pagesizes import letter
from reportlab.lib.units import inch
from reportlab.lib import colors
from reportlab.pdfgen import canvas
from reportlab.lib.utils import ImageReader

ROOT = Path(__file__).resolve().parent
MANUAL = ROOT / "manual-shots-finals-lab1"
SUBMIT = ROOT / "progress-shots-finals-lab1"
OUT = ROOT / "Acuesta_John_Constantine_Finals_LabActivity1_INSTRUCTION.pdf"
TMP = ROOT / "_build_finals_lab1_instruction.tmp.pdf"

STEPS = [
    {
        "title": "Before you start",
        "why": (
            "Finals Lab Activity 1 adds an image upload pipeline on top of Lab 2 and Lab 3. "
            "Your app should already have admin CRUD, role middleware, categories, soft delete, "
            "and a Form Request for bouquet fields. Petal Express uses Post on table showcase_flowers."
        ),
        "do": [
            "Check out branch finallabact1 on GitHub or match the same behaviors in your project.",
            "Confirm you can log in as admin and open the admin bouquet list.",
            "Keep SQLite or your existing database. Run migrate and db:seed if the app is fresh.",
        ],
        "commands": [
            "cd petal-express",
            "php artisan migrate",
            "php artisan db:seed",
            "php artisan serve",
        ],
        "see": [
            "Home page loads at http://127.0.0.1:8000",
            "Admin login admin@test.com password 123 reaches /admin/posts (Petal Express demo)",
        ],
        "files": [],
    },
    {
        "title": "Install Intervention Image and storage link",
        "why": (
            "Intervention Image decodes uploads, resizes, overlays the watermark, and encodes WebP. "
            "Laravel stores files on the public disk. storage:link exposes them under /storage/..."
        ),
        "do": [
            "Require intervention/image-laravel v4 with Composer.",
            "Run php artisan storage:link once per machine.",
            "Copy watermark.png into public/images/ (Petal Express ships this file).",
        ],
        "commands": [
            "cd petal-express",
            "composer require intervention/image-laravel",
            "php artisan storage:link",
        ],
        "see": [
            "composer.json lists intervention/image-laravel",
            "public/storage symlink points to storage/app/public",
            "public/images/watermark.png exists",
        ],
        "files": ["petal-express/composer.json", "petal-express/public/images/watermark.png"],
    },
    {
        "title": "Database column for stored path",
        "why": (
            "The image column stores the relative path on the public disk, for example products/uuid.webp. "
            "It is nullable so old rows can still use gallery fallbacks."
        ),
        "do": [
            "If your products table lacks an image column, add nullable string image in a migration.",
            "Run php artisan migrate.",
            "Add image to the model $fillable array.",
        ],
        "commands": [
            "php artisan migrate",
        ],
        "see": [
            "showcase_flowers.image (or your products table) accepts a string path",
        ],
        "files": [
            "petal-express/database/migrations/2026_09_23_234450_create_showcase_flowers_table.php",
            "petal-express/app/Models/Post.php",
        ],
    },
    {
        "title": "Validate uploads in PostRequest",
        "why": (
            "Validation runs before the controller touches the file. Create requires a photo. "
            "Update allows an optional new photo. Mimes and max size match the lab rubric."
        ),
        "do": [
            "Open PostRequest rules().",
            "Set image required on POST and nullable on PUT/PATCH.",
            "Allow jpg, jpeg, png, webp and max 3072 kilobytes.",
        ],
        "commands": [],
        "see": [
            "Invalid mime or oversize file returns validation errors on the form",
        ],
        "files": ["petal-express/app/Http/Requests/PostRequest.php"],
    },
    {
        "title": "Process upload in AdminPostController",
        "why": (
            "store() and update() call saveWatermarkedImage() when a file is present. "
            "The private method decodes, scaleDown width 1000, inserts watermark bottom-right at 0.55 opacity, "
            "then encodes WEBP quality 80 to products/uuid.webp."
        ),
        "do": [
            "Import Intervention Image facade and Format enum.",
            "In store(), pass saveWatermarkedImage() result into Post::create image field.",
            "In update(), delete the old WebP from storage when a new file replaces it.",
            "Use try/catch to delete a new file if the database update fails.",
        ],
        "commands": [],
        "see": [
            "After save, storage/app/public/products contains a new .webp file",
            "Success flash mentions watermark on create (Petal Express)",
        ],
        "files": ["petal-express/app/Http/Controllers/AdminPostController.php"],
    },
    {
        "title": "Show photos with displayImageUrl()",
        "why": (
            "Views should not build storage paths by hand. displayImageUrl() returns /storage/products/....webp "
            "when the file exists, otherwise a packed gallery image for demo rows."
        ),
        "do": [
            "Add displayImageUrl() on the Post model.",
            "Replace hard-coded gallery URLs in admin list, trash, showcase, and detail Blade files.",
        ],
        "commands": [],
        "see": [
            "Public bouquet detail shows a large photo with petal express text bottom-right",
            "List thumbnails are small; open detail or admin preview to verify the watermark",
        ],
        "files": [
            "petal-express/app/Models/Post.php",
            "petal-express/resources/views/pages/bouquets-index.blade.php",
            "petal-express/resources/views/pages/bouquets-show.blade.php",
        ],
    },
    {
        "title": "Admin forms and watermark preview",
        "why": (
            "Admin create and edit forms need enctype multipart/form-data and a file input. "
            "Optional UX: thumbnail click opens Alpine modal to inspect watermark (admin-photo-preview-shell)."
        ),
        "do": [
            "Set enctype on admin store and update forms.",
            "Add accept image/jpeg,image/png,image/webp on the file input.",
            "Wrap admin index/trash in x-admin-photo-preview-shell if you use the modal.",
        ],
        "commands": [],
        "see": [
            "Create form shows JPG, PNG, or WebP hint and 3 MB limit",
            "Edit form shows current thumbnail from storage",
        ],
        "files": [
            "petal-express/resources/views/admin/posts/create.blade.php",
            "petal-express/resources/views/admin/posts/edit.blade.php",
            "petal-express/resources/views/components/admin-photo-preview-shell.blade.php",
        ],
    },
    {
        "title": "Soft delete vs force delete and files",
        "why": (
            "Soft delete sets deleted_at and keeps the WebP on disk so restore still works. "
            "forceDelete() removes the database row and deletes the file from the public disk."
        ),
        "do": [
            "In destroy(), call $post->delete() only.",
            "In forceDelete(), read image path, forceDelete(), then Storage::disk('public')->delete($path).",
            "Guard force delete so only trashed rows can be permanently removed.",
        ],
        "commands": [],
        "see": [
            "Trash list still shows thumbnail from storage",
            "After delete forever, the WebP file is gone from products/",
        ],
        "files": ["petal-express/app/Http/Controllers/AdminPostController.php"],
    },
    {
        "title": "Automated tests",
        "why": (
            "LabActFourInterventionTest proves WebP storage, soft delete keeps files, and force delete removes them. "
            "Run it after every change to the upload pipeline."
        ),
        "do": [
            "Create tests/Feature/LabActFourInterventionTest.php (copy from finallabact1).",
            "Use Storage::fake('public') and UploadedFile::fake()->image().",
        ],
        "commands": [
            "cd petal-express",
            "php artisan test --filter=LabActFourInterventionTest",
        ],
        "see": [
            "Three tests pass (upload WebP, soft delete keeps file, force delete removes file)",
        ],
        "files": ["petal-express/tests/Feature/LabActFourInterventionTest.php"],
    },
    {
        "title": "Browser demo walkthrough",
        "why": (
            "Manual checks match the instructor CRUD + Intervention PDF: create with JPG and PNG, read on showcase, "
            "edit, soft delete, trash, restore optional, force delete optional."
        ),
        "do": [
            "Log in as admin. Create bouquet with JPG. Create another with PNG.",
            "Open public showcase and detail pages.",
            "Soft delete one bouquet and open Trash.",
            "See docs/LAB-ACT-4-DEMO.md for the full spoken demo order.",
        ],
        "commands": [
            "php artisan serve",
        ],
        "see": [
            "Toast after save on admin index",
            "Watermark visible bottom-right on preview modal and public photos",
            "file command reports WebP for products/*.webp (see 02b-disk-webp-proof.png)",
        ],
        "files": ["docs/LAB-ACT-4-DEMO.md"],
    },
]

# One full page each. Submit-pack shots first so the watermark matches the PDF already passed.
PROOFS = [
    (SUBMIT / "00-intervention-composer.png", "Composer setup, storage:link, and watermark.png.", "Composer and storage link"),
    (SUBMIT / "00b-artisan-test-webp.png", "Feature test proves admin upload stores products/*.webp.", "Automated test output"),
    (SUBMIT / "00c-stored-webp-on-disk.png", "On disk the saved file is WebP even when the upload was JPG or PNG.", "Disk proof: WebP"),
    (SUBMIT / "20-admin-create-upload-hint.png", "Add bouquet accepts JPG, PNG, or WebP. PostRequest checks the mime type.", "Admin create form"),
    (SUBMIT / "21-admin-save-jpg-to-webp-toast.png", "JPG upload. Toast says the watermark was applied. Stored file is WebP.", "JPG upload toast"),
    (SUBMIT / "22-admin-save-png-to-webp-toast.png", "PNG upload. Same pipeline. Stored file is still WebP.", "PNG upload toast"),
    (SUBMIT / "23-admin-edit-current-webp-thumb.png", "Edit screen shows the processed thumbnail from storage.", "Edit thumbnail"),
    (SUBMIT / "24-watermark-preview-modal.png", "Watermark preview. Look at the bottom-right of the flower photo for petal express.", "Watermark preview modal"),
    (MANUAL / "14b-watermark-closeup.png", "Close-up of the saved photo. petal express sits in the bottom-right corner.", "Watermark close-up"),
    (SUBMIT / "25-public-showcase-webp-rows.png", "Public showcase uses Post::displayImageUrl(). Thumbs are small.", "Public showcase"),
    (SUBMIT / "26-public-bouquet-detail-photo.png", "Bouquet detail. The large photo shows the petal express watermark bottom-right.", "Public detail watermark"),
    (MANUAL / "18-admin-trash-list.png", "Soft delete keeps the row in Trash and keeps the WebP file on disk.", "Trash after soft delete"),
    (SUBMIT / "90-final-checklist-finals-lab1.png", "Rubric checklist for Finals Lab Activity 1.", "Rubric checklist"),
]

APPENDIX_MAPPING = [
    ("Instructor Product model", "Post model (showcase_flowers table)"),
    ("image_path column", "image nullable string"),
    ("ProductController upload", "AdminPostController + saveWatermarkedImage()"),
    ("Admin /products routes", "/admin/posts with role:admin middleware"),
    ("Public catalog", "/bouquets using displayImageUrl()"),
]

APPENDIX_FILES = [
    "petal-express/app/Http/Controllers/AdminPostController.php",
    "petal-express/app/Http/Requests/PostRequest.php",
    "petal-express/app/Models/Post.php",
    "petal-express/composer.json",
    "petal-express/public/images/watermark.png",
    "petal-express/tests/Feature/LabActFourInterventionTest.php",
    "petal-express/resources/views/admin/posts/create.blade.php",
    "petal-express/resources/views/admin/posts/edit.blade.php",
    "petal-express/resources/views/components/admin-photo-preview-shell.blade.php",
    "petal-express/resources/views/components/admin-photo-thumb.blade.php",
    "petal-express/resources/views/pages/bouquets-index.blade.php",
    "petal-express/resources/views/pages/bouquets-show.blade.php",
]

ORAL_LINES = [
    "Intervention Image decodes JPG, PNG, or WebP before we resize and watermark.",
    "We always store WebP on the public disk under products/ with a UUID filename.",
    "storage:link lets the browser load /storage/products/file.webp without copying files into public/.",
    "PostRequest blocks bad mimes and files over 3 MB before the controller runs.",
    "Soft delete keeps the image file so restore still shows the same photo.",
    "Force delete removes the row and Storage::delete removes the WebP from disk.",
    "displayImageUrl() centralizes how Blade gets the photo URL.",
]


def wrap(c, text, x, y, max_width, font="Helvetica", size=11, leading=15):
    words = text.split()
    line = ""
    for word in words:
        trial = (line + " " + word).strip()
        if c.stringWidth(trial, font, size) <= max_width:
            line = trial
        else:
            c.drawString(x, y, line)
            y -= leading
            line = word
    if line:
        c.drawString(x, y, line)
        y -= leading
    return y


def draw_footer(c, width, margin, page_num):
    c.setFont("Helvetica", 9)
    c.setFillColor(colors.grey)
    c.drawString(margin, 0.45 * inch, f"Finals Lab Activity 1 Instruction — page {page_num}")
    c.drawRightString(width - margin, 0.45 * inch, "Petal Express · finallabact1")
    c.setFillColor(colors.black)


def cover_page(c, width, height, margin):
    y = height - margin - 10
    c.setFont("Helvetica-Bold", 20)
    c.drawString(margin, y, "Finals Lab Activity 1")
    y -= 26
    c.setFont("Helvetica-Bold", 16)
    c.drawString(margin, y, "Hand coding instruction booklet")
    y -= 32
    c.setFont("Helvetica", 12)
    for line in [
        "Rebuild Intervention Image upload without AI.",
        "Source branch: finallabact1 on github.com/ichigokekiii/laravel-elec",
        "",
        "Assumes Lab 2 and Lab 3 are done (admin CRUD, roles, categories, soft delete).",
        "",
        "Demo login (Petal Express): admin@test.com / 123",
        "",
        "How to use this PDF:",
        "1. Follow steps in order.",
        "2. Run each command block in your terminal.",
        "3. Type or paste code into the listed files.",
        "4. Then match the full-page proof screenshots that follow the steps.",
        "",
        "Watermark proof pages are in the table of contents: preview modal, close-up, and public detail.",
        "Showcase list thumbs are too small to show the watermark clearly.",
    ]:
        if line == "":
            y -= 10
            continue
        y = wrap(c, line, margin, y, width - 2 * margin, size=12, leading=16)
        y -= 2
    c.showPage()


def toc_page(c, width, height, margin, page_map):
    c.setFont("Helvetica-Bold", 16)
    c.drawString(margin, height - margin, "Table of contents")
    y = height - margin - 36
    c.setFont("Helvetica", 11)
    c.drawString(margin, y, "Steps")
    y -= 22
    for i, step in enumerate(STEPS, start=1):
        pg = page_map.get(f"step_{i}", "?")
        line = f"  Step {i}. {step['title']}"
        dots = "." * max(2, int((width - 2 * margin - c.stringWidth(line, "Helvetica", 11) - 30) / 4))
        c.drawString(margin, y, f"{line} {dots} {pg}")
        y -= 18
    y -= 8
    c.setFont("Helvetica-Bold", 11)
    c.drawString(margin, y, "Proof pages")
    y -= 18
    c.setFont("Helvetica", 10)
    for i, (_path, _caption, label) in enumerate(PROOFS, start=1):
        pg = page_map.get(f"proof_{i}", "?")
        c.drawString(margin, y, f"  {label} .............. {pg}")
        y -= 14
    y -= 8
    c.setFont("Helvetica-Bold", 11)
    c.drawString(margin, y, "Appendices")
    y -= 20
    c.setFont("Helvetica", 11)
    for label, key in [
        ("A. Instructor name mapping", "app_a"),
        ("B. Finals file index", "app_b"),
        ("C. Oral defense one-liners", "app_c"),
        ("D. Rubric checklist", "app_d"),
        ("E. Rebuild this PDF", "app_e"),
    ]:
        pg = page_map.get(key, "?")
        c.drawString(margin, y, f"  {label} .............. {pg}")
        y -= 18
    c.showPage()


def proof_page(c, width, height, margin, path, caption, page_num):
    content_width = width - (2 * margin)
    c.setFont("Helvetica", 11)
    caption_bottom = wrap(c, caption, margin, height - margin, content_width, size=11, leading=14)

    if path.exists():
        img = ImageReader(str(path))
        iw, ih = img.getSize()
        gap = 0.15 * inch
        top = caption_bottom - gap
        max_h = max(top - (margin + 0.15 * inch), 1)
        scale = min(content_width / iw, max_h / ih)
        draw_w = iw * scale
        draw_h = ih * scale
        y = top - draw_h
        c.drawImage(img, margin, y, width=draw_w, height=draw_h, preserveAspectRatio=True, mask="auto")
    else:
        c.drawString(margin, caption_bottom - 20, f"Missing image: {path.name}")

    draw_footer(c, width, margin, page_num)
    c.showPage()


def step_page(c, width, height, margin, num, step, page_num):
    content_width = width - 2 * margin
    y = height - margin

    c.setFont("Helvetica-Bold", 15)
    c.drawString(margin, y, f"Step {num} — {step['title']}")
    y -= 22

    c.setFont("Helvetica-Bold", 11)
    c.drawString(margin, y, "Why")
    y -= 14
    c.setFont("Helvetica", 10)
    y = wrap(c, step["why"], margin, y, content_width, size=10, leading=13)
    y -= 6

    c.setFont("Helvetica-Bold", 11)
    c.drawString(margin, y, "Do this")
    y -= 14
    c.setFont("Helvetica", 10)
    for i, item in enumerate(step["do"], start=1):
        y = wrap(c, f"{i}. {item}", margin + 8, y, content_width - 8, size=10, leading=13)
        y -= 2
    y -= 4

    if step.get("commands"):
        c.setFont("Helvetica-Bold", 11)
        c.drawString(margin, y, "Commands")
        y -= 12
        c.setFont("Courier", 9)
        cmd_lines = step["commands"]
        box_h = 13 * len(cmd_lines) + 10
        c.setFillColor(colors.HexColor("#f0f0f0"))
        c.rect(margin, y - box_h + 8, content_width, box_h, fill=1, stroke=0)
        c.setFillColor(colors.black)
        y -= 2
        for cmd in cmd_lines:
            c.drawString(margin + 8, y, cmd[:95])
            y -= 13
        y -= 6

    c.setFont("Helvetica-Bold", 11)
    c.drawString(margin, y, "You should see")
    y -= 14
    c.setFont("Helvetica", 10)
    for item in step["see"]:
        y = wrap(c, f"• {item}", margin + 4, y, content_width - 4, size=10, leading=13)
        y -= 1

    if step.get("files"):
        y -= 4
        c.setFont("Helvetica-Bold", 11)
        c.drawString(margin, y, "Files")
        y -= 12
        c.setFont("Courier", 8)
        for f in step["files"]:
            c.drawString(margin + 4, y, f[:100])
            y -= 11

    draw_footer(c, width, margin, page_num)
    c.showPage()


def appendix_page(c, width, height, margin, title, lines, page_num, image=None):
    y = height - margin
    c.setFont("Helvetica-Bold", 14)
    c.drawString(margin, y, title)
    y -= 28
    c.setFont("Helvetica", 10)
    for line in lines:
        if line == "":
            y -= 8
            continue
        y = wrap(c, line, margin, y, width - 2 * margin, size=10, leading=13)
        y -= 2
        if y < margin + 80:
            break
    if image and Path(image).exists():
        path = Path(image)
        img = ImageReader(str(path))
        iw, ih = img.getSize()
        max_h = 3 * inch
        scale = min((width - 2 * margin) / iw, max_h / ih)
        draw_h = ih * scale
        c.drawImage(img, margin, margin + 40, width=iw * scale, height=draw_h, preserveAspectRatio=True, mask="auto")
    draw_footer(c, width, margin, page_num)
    c.showPage()


def build():
    if TMP.exists():
        TMP.unlink()

    page_num = 1
    c = canvas.Canvas(str(TMP), pagesize=letter)
    width, height = letter
    margin = 0.65 * inch

    # One page each: cover, toc, each step, each proof, five appendices.
    page_map = {}
    step_start = 3
    for i in range(len(STEPS)):
        page_map[f"step_{i + 1}"] = step_start + i
    proof_start = step_start + len(STEPS)
    for i in range(len(PROOFS)):
        page_map[f"proof_{i + 1}"] = proof_start + i
    app_start = proof_start + len(PROOFS)
    page_map["app_a"] = app_start
    page_map["app_b"] = app_start + 1
    page_map["app_c"] = app_start + 2
    page_map["app_d"] = app_start + 3
    page_map["app_e"] = app_start + 4

    cover_page(c, width, height, margin)
    page_num = 2
    toc_page(c, width, height, margin, page_map)
    page_num = 3

    for i, step in enumerate(STEPS, start=1):
        step_page(c, width, height, margin, i, step, page_num)
        page_num += 1

    for path, caption, _label in PROOFS:
        proof_page(c, width, height, margin, path, caption, page_num)
        page_num += 1

    map_lines = [f"{a}  ->  {b}" for a, b in APPENDIX_MAPPING]
    appendix_page(c, width, height, margin, "Appendix A — Instructor name mapping", map_lines, page_num)
    page_num += 1

    appendix_page(c, width, height, margin, "Appendix B — Finals file index (finallabact1)", APPENDIX_FILES, page_num)
    page_num += 1

    appendix_page(c, width, height, margin, "Appendix C — Oral defense one-liners", ORAL_LINES, page_num)
    page_num += 1

    rubric_page = page_map["proof_13"]
    appendix_page(
        c,
        width,
        height,
        margin,
        "Appendix D — Rubric checklist",
        [f"The full checklist figure is on page {rubric_page}."],
        page_num,
    )
    page_num += 1

    rebuild = [
        "cd petal-express && php artisan migrate && php artisan db:seed && php artisan serve",
        "cd docs && python3 make-terminal-shots-manual-finals-lab1.py",
        "cd docs && node capture-manual-finals-lab1.cjs",
        "cd docs && python3 build-instruction-pdf-finals-lab1.py",
        "",
        "Proof pages reuse docs/progress-shots-finals-lab1 from the submitted PDF.",
        "The close-up and trash shots come from docs/manual-shots-finals-lab1.",
    ]
    appendix_page(c, width, height, margin, "Appendix E — Rebuild this instruction PDF", rebuild, page_num)

    c.save()
    os.replace(TMP, OUT)
    print("wrote", OUT, "pages", page_num)


if __name__ == "__main__":
    build()
