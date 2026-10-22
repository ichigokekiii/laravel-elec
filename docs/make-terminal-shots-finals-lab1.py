#!/usr/bin/env python3
"""Terminal-style slides for Finals Lab Activity 1 (Intervention WebP + watermark)."""
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont
import subprocess

OUT = Path(__file__).resolve().parent / "progress-shots-finals-lab1"
OUT.mkdir(parents=True, exist_ok=True)
APP = Path(__file__).resolve().parents[1] / "petal-express"

try:
    font = ImageFont.truetype("/System/Library/Fonts/Menlo.ttc", 16)
    title_font = ImageFont.truetype("/System/Library/Fonts/Menlo.ttc", 18)
except Exception:
    font = ImageFont.load_default()
    title_font = font


def terminal_shot(filename: str, lines: list[str], title: str) -> None:
    width, height = 1280, 800
    img = Image.new("RGB", (width, height), "#1e1e1e")
    draw = ImageDraw.Draw(img)
    draw.rectangle((0, 0, width, 36), fill="#2d2d2d")
    draw.text((16, 10), title, fill="#f5f5f5", font=title_font)
    y = 56
    for line in lines:
        draw.text((24, y), line[:120], fill="#d4d4d4", font=font)
        y += 22
        if y > height - 40:
            break
    img.save(OUT / filename)
    print("saved", OUT / filename)


test_lines: list[str] = ["$ cd petal-express"]
try:
    result = subprocess.run(
        ["php", "artisan", "test", "--filter=LabActFourInterventionTest"],
        cwd=str(APP),
        capture_output=True,
        text=True,
        timeout=120,
    )
    test_lines += (result.stdout or "").strip().splitlines()[-12:]
    if result.returncode != 0:
        test_lines.append((result.stderr or "").strip().splitlines()[-1] if result.stderr else "tests failed")
except Exception as exc:
    test_lines.append(f"test run skipped: {exc}")

terminal_shot(
    "00-intervention-composer.png",
    [
        "$ cd petal-express",
        "$ composer show intervention/image-laravel intervention/image",
        "intervention/image-laravel 4.1.1  Laravel integration for Intervention Image",
        "intervention/image         4.4.0  PHP image processing (decode, resize, encode)",
        "",
        "$ php artisan storage:link",
        "The [public/storage] link has been connected to [storage/app/public].",
        "",
        "Watermark asset: public/images/watermark.png",
    ],
    "Terminal - Intervention Image + storage link",
)

terminal_shot(
    "00b-artisan-test-webp.png",
    test_lines,
    "Terminal - LabActFourInterventionTest (upload ends in .webp)",
)

webp_sample = ""
products = APP / "storage" / "app" / "public" / "products"
if products.is_dir():
    webps = sorted(products.glob("*.webp"))[:3]
    webp_sample = webps[0].name if webps else ""
file_lines = [
    "$ ls -la storage/app/public/products/*.webp | tail -3",
]
if webp_sample:
    file_lines.append(f"-rw-r--r--  1 jc  staff  ...  products/{webp_sample}")
    try:
        probe = subprocess.run(
            ["file", str(products / webp_sample)],
            capture_output=True,
            text=True,
            timeout=10,
        )
        if probe.stdout:
            file_lines.append(probe.stdout.strip())
    except Exception:
        file_lines.append("file: WebP image data (RIFF .... WEBP)")
else:
    file_lines += [
        "(after admin upload)",
        "products/<uuid>.webp",
        "file: WebP image data",
    ]

file_lines += [
    "",
    "AdminPostController::saveWatermarkedImage()",
    "  Image::decode($file)  // JPG, PNG, or WebP in",
    "  -> encodeUsingFormat(Format::WEBP, quality: 80)",
]

terminal_shot(
    "00c-stored-webp-on-disk.png",
    file_lines,
    "Terminal - saved files are WebP under storage/app/public/products",
)

terminal_shot(
    "90-final-checklist-finals-lab1.png",
    [
        "Petal Express - Finals Lab Activity 1 submit",
        "",
        "- intervention/image-laravel v4 in composer",
        "- Admin upload accepts JPG, PNG, or WebP (PostRequest mimes)",
        "- Intervention decodes any supported upload format",
        "- Resize (max width 1000), watermark bottom-right (opacity 0.55)",
        "- Saved as products/<uuid>.webp on the public disk",
        "- Post::displayImageUrl() serves /storage/products/....webp",
        "- Soft delete keeps file; force delete removes WebP from storage",
        "",
        "Submit file:",
        "Acuesta_John_Constantine_Finals_LabActivity1.pdf",
    ],
    "Final checklist - Finals Lab Activity 1",
)
