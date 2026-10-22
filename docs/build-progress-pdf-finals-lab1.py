#!/usr/bin/env python3
"""Build submit PDF: Acuesta_John_Constantine_Finals_LabActivity1.pdf"""
from pathlib import Path
import os
from reportlab.lib.pagesizes import letter
from reportlab.lib.units import inch
from reportlab.pdfgen import canvas
from reportlab.lib.utils import ImageReader

ROOT = Path(__file__).resolve().parent
SHOTS = ROOT / "progress-shots-finals-lab1"
OUT = ROOT / "Acuesta_John_Constantine_Finals_LabActivity1.pdf"
TMP = ROOT / "_build_finals_lab_activity1.tmp.pdf"

PAGES = [
    ("00-intervention-composer.png", "Lab setup: intervention/image-laravel, storage:link, and watermark.png in public/images."),
    ("00b-artisan-test-webp.png", "Feature test proves admin upload stores products/*.webp on the public disk."),
    ("00c-stored-webp-on-disk.png", "On disk every saved bouquet photo is WebP even when the upload was JPG or PNG."),
    ("20-admin-create-upload-hint.png", "Add bouquet form accepts JPG, PNG, or WebP. PostRequest validates image mime types."),
    ("21-admin-save-jpg-to-webp-toast.png", "Uploaded a JPG sample. Success flash confirms save. Controller encodes WebP with watermark."),
    ("22-admin-save-png-to-webp-toast.png", "Uploaded a PNG sample. Same Intervention pipeline. Only the stored file format changes to WebP."),
    ("23-admin-edit-current-webp-thumb.png", "Edit screen shows the processed thumbnail from storage. Click opens watermark preview."),
    ("24-watermark-preview-modal.png", "Watermark preview modal. petal express mark sits bottom-right on the resized photo."),
    ("25-public-showcase-webp-rows.png", "Public showcase reads Post::displayImageUrl() so visitors see the watermarked WebP."),
    ("26-public-bouquet-detail-photo.png", "Bouquet detail page loads the same /storage/products/....webp asset."),
    ("90-final-checklist-finals-lab1.png", "Final checklist for Finals Lab Activity 1 rubric items in this build."),
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
    c.drawString(margin, y, "Finals Lab Activity 1 Final Submission")
    y -= 28

    c.setFont("Helvetica", 12)
    lines = [
        "Student file: Acuesta_John_Constantine_Finals_LabActivity1.pdf",
        "Theme: Petal Express (Laravel 11 + Blade + Breeze, SQLite).",
        "",
        "Focus: Intervention Image upload pipeline.",
        "Any upload format (JPG, PNG, WebP) is decoded, resized, watermarked,",
        "and saved as WebP under storage/app/public/products.",
        "",
        "Screenshots include composer setup, automated test output, disk proof,",
        "admin upload from JPG and PNG, watermark preview, and public showcase.",
        "",
        "Demo login used in screenshots:",
        "admin@test.com / 123",
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

    os.replace(TMP, OUT)
    print("wrote", OUT)


if __name__ == "__main__":
    build()
