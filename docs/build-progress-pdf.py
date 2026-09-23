#!/usr/bin/env python3
"""Build final submit PDF: Acuesta_John_Constantine_LabActivity1.pdf"""
from pathlib import Path
import os
from reportlab.lib.pagesizes import letter
from reportlab.lib.units import inch
from reportlab.pdfgen import canvas
from reportlab.lib.utils import ImageReader

ROOT = Path(__file__).resolve().parent
SHOTS = ROOT / "progress-shots"
OUT = ROOT / "Acuesta_John_Constantine_LabActivity1.pdf"
TMP = ROOT / "_build_lab_activity1.tmp.pdf"
LEGACY = ROOT / "Lastname_Firstname_LabActivity1.pdf"

PAGES = [
    ("00-create-project.png", "Created the Laravel project with Composer so I had a clean Petal Express folder to start with."),
    ("00b-breeze-install.png", "Installed Laravel Breeze with Blade for register, login, logout, and the starter layouts."),
    ("00c-routes-controllers.png", "Set up the routes and controllers. Each page has a named route and a controller method."),
    ("01-home.png", "Home page. Guest layout, featured bouquets from PageController, and the site theme."),
    ("02-about.png", "About page. Uses the same guest layout as the other public pages."),
    ("03-services.png", "Services page. Another guest page with its own named route."),
    ("04-bouquets-index.png", "Bouquets list at /bouquets. Cards come from the controller array."),
    ("04b-blade-component-slot.png", "Blade component bouquet-card. Default $slot is the description. Named slot badge shows ID or Collection."),
    ("05-bouquet-param.png", "Opened /bouquets/1. The id in the URL loads Sunrise Dozen from the controller."),
    ("06-contact-csrf-form.png", "Contact form with @csrf so form posts are protected."),
    ("07-login.png", "Breeze login page with Petal Express branding."),
    ("08-dashboard.png", "Dashboard after logging in with the demo member account."),
    ("08b-breeze-layout-nav.png", "Breeze layout customization. Member nav has Shop, Dashboard, My Orders, Favorites, and Account."),
    ("09-orders.png", "My Orders page. Only works when you are logged in."),
    ("10-favorites.png", "Favorites page while logged in."),
    ("11-account.png", "Account page for the signed in user."),
    ("12-guest-redirect-login.png", "Tried to open Orders without logging in. Auth middleware sent me to the login page."),
    ("13-register-breeze.png", "Breeze register page for new accounts."),
    ("15-final-member-dashboard.png", "Final testing output. Demo member can open protected pages after login."),
    ("14-final-checklist.png", "Final checklist of rubric pieces covered in this Petal Express build."),
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
    c.drawString(margin, y, "Lab Activity 1 Final Submission")
    y -= 28

    c.setFont("Helvetica", 12)
    lines = [
        "Student file: Acuesta_John_Constantine_LabActivity1.pdf",
        "Theme: Petal Express (Laravel + Blade + Breeze, no React).",
        "",
        "This PDF shows the project from setup to final output.",
        "Screenshots include project creation, Breeze install, routes and",
        "controllers, guest pages, route parameter, Blade component and",
        "slot, contact form with CSRF, Breeze layout customization,",
        "authenticated pages, middleware redirect, and final testing.",
        "",
        "Demo login used in screenshots:",
        "test@test.com / 123",
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

    if LEGACY.exists():
        LEGACY.unlink()
        print("removed", LEGACY.name)

    old = ROOT / "ProgressCheck_LabActivity1.pdf"
    if old.exists():
        old.unlink()

    print("wrote", OUT)


if __name__ == "__main__":
    build()
