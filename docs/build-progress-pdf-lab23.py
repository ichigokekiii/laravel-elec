#!/usr/bin/env python3
"""Build submit PDF: Acuesta_John_Constantine_LabActivity2_and_3.pdf"""
from pathlib import Path
import os
from reportlab.lib.pagesizes import letter
from reportlab.lib.units import inch
from reportlab.pdfgen import canvas
from reportlab.lib.utils import ImageReader

ROOT = Path(__file__).resolve().parent
SHOTS = ROOT / "progress-shots-lab23"
OUT = ROOT / "Acuesta_John_Constantine_LabActivity2_and_3.pdf"
TMP = ROOT / "_build_lab_activity23.tmp.pdf"

PAGES = [
    ("00-lab2-migrations.png", "Lab Act 2 starts in SQLite. I added role on users and the showcase_flowers table with user_id and softDeletes()."),
    ("00b-lab2-role-routes.png", "Admin and member post routes sit behind auth and the role middleware. Wrong role stops at 403 before the controller runs."),
    ("20-user-403-admin.png", "Logged in as test@test.com (role user). /admin/posts is blocked with 403."),
    ("21-admin-403-user-submit.png", "Logged in as admin@test.com. The member custom-order page is blocked the same way."),
    ("22-admin-posts-list.png", "Admin showcase list. Eloquent reads showcase_flowers through the Post model."),
    ("23-admin-create-form.png", "Add bouquet form. POST uses @csrf; user_id will come from auth()->id() on save."),
    ("24-admin-save-success-toast.png", "Valid save redirects back with a success flash. The toast reads session data once."),
    ("25-user-post-show.png", "Member bouquet page. Route model binding turns {post} into a Post row."),
    ("26-user-submit-static-form.png", "Custom order validates and flashes thank-you. It does not call Post::create."),
    ("00c-lab3-categories-seed.png", "Lab Act 3 adds categories and reseeds to 100 bouquets so pagination is easy to show."),
    ("30-bouquets-categories.png", "Public showcase category chips use withCount and ?category= in the URL."),
    ("30b-bouquets-category-filter.png", "Filtered showcase keeps the chip active and still paginates with withQueryString()."),
    ("31-bouquets-pagination.png", "Showcase uses paginate(9). Page links only load nine cards at a time."),
    ("32-home-latest-six.png", "Home shows latest()->take(6) while the headline count uses Post::count()."),
    ("33-admin-pagination.png", "Admin list uses paginate(10) with the same Tailwind pagination view."),
    ("34-validation-errors-toast.png", "Empty submit hits PostRequest rules. Errors show under fields and once in the toast."),
    ("36-admin-trash.png", "Trash lists onlyTrashed() rows. Restore and delete forever use withTrashed() routes."),
    ("37-final-checklist-lab23.png", "Final checklist of Lab Act 2 and Lab Act 3 rubric pieces in this build."),
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


def cover_page(c, width, height, margin):
    y = height - margin - 10
    c.setFont("Helvetica-Bold", 18)
    c.drawString(margin, y, "Lab Activity 2 and 3 Final Submission")
    y -= 28

    c.setFont("Helvetica", 12)
    lines = [
        "Student file: Acuesta_John_Constantine_LabActivity2_and_3.pdf",
        "Theme: Petal Express (Laravel 11 + Blade + Breeze, SQLite).",
        "",
        "Part A (Lab Act 2): roles, showcase_flowers, admin save, member read/static form.",
        "Part B (Lab Act 3): PostRequest, flash toast, categories, pagination, trash.",
        "",
        "Screenshots include middleware 403 checks, admin CRUD, validation,",
        "public showcase filters and pages, and soft-delete trash.",
        "",
        "Demo logins used in screenshots:",
        "admin@test.com / 123  ·  test@test.com / 123",
    ]
    for line in lines:
        if line == "":
            y -= 12
            continue
        y = wrap(c, line, margin, y, width - 2 * margin, size=12, leading=16)
        y -= 4
    c.showPage()


def shot_page(c, width, height, margin, filename, caption):
    content_width = width - (2 * margin)
    path = SHOTS / filename

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
        c.drawString(margin, caption_bottom - 20, f"Missing image: {filename}")

    c.showPage()


def build():
    if TMP.exists():
        TMP.unlink()

    c = canvas.Canvas(str(TMP), pagesize=letter)
    width, height = letter
    margin = 0.7 * inch

    cover_page(c, width, height, margin)
    for filename, caption in PAGES:
        shot_page(c, width, height, margin, filename, caption)

    c.save()

    if OUT.exists():
        OUT.unlink()
    os.replace(TMP, OUT)
    print("wrote", OUT)


if __name__ == "__main__":
    build()
