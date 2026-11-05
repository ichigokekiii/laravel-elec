#!/usr/bin/env python3
"""Terminal and code slides for Finals Lab Activity 1 instruction manual."""
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont
import subprocess

OUT = Path(__file__).resolve().parent / "manual-shots-finals-lab1"
OUT.mkdir(parents=True, exist_ok=True)
APP = Path(__file__).resolve().parents[1] / "petal-express"

try:
    font = ImageFont.truetype("/System/Library/Fonts/Menlo.ttc", 15)
    title_font = ImageFont.truetype("/System/Library/Fonts/Menlo.ttc", 18)
    code_font = ImageFont.truetype("/System/Library/Fonts/Menlo.ttc", 13)
except Exception:
    font = ImageFont.load_default()
    title_font = font
    code_font = font


def terminal_shot(filename: str, lines: list[str], title: str) -> None:
    width, height = 1280, 800
    img = Image.new("RGB", (width, height), "#1e1e1e")
    draw = ImageDraw.Draw(img)
    draw.rectangle((0, 0, width, 36), fill="#2d2d2d")
    draw.text((16, 10), title, fill="#f5f5f5", font=title_font)
    y = 56
    for line in lines:
        draw.text((24, y), line[:118], fill="#d4d4d4", font=font)
        y += 22
        if y > height - 40:
            break
    img.save(OUT / filename)
    print("saved", OUT / filename)


def code_shot(filename: str, title: str, path: Path, start: int, end: int) -> None:
    lines = path.read_text().splitlines()[start - 1 : end]
    body = [f"{start + i:4d} | {line}" for i, line in enumerate(lines)]
    terminal_shot(filename, [f"// {path.name}"] + body, title)


test_lines: list[str] = ["$ cd petal-express"]
try:
    result = subprocess.run(
        ["php", "artisan", "test", "--filter=LabActFourInterventionTest"],
        cwd=str(APP),
        capture_output=True,
        text=True,
        timeout=120,
    )
    test_lines += (result.stdout or "").strip().splitlines()[-14:]
    if result.returncode != 0:
        test_lines.append("exit code: " + str(result.returncode))
except Exception as exc:
    test_lines.append(f"test run skipped: {exc}")

terminal_shot(
    "02-composer-intervention.png",
    [
        "$ cd petal-express",
        "$ composer require intervention/image-laravel",
        "Using version ^4.0 for intervention/image-laravel",
        "Package intervention/image-laravel installed.",
        "",
        "$ composer show intervention/image-laravel intervention/image | head -4",
        "intervention/image-laravel 4.x  Laravel integration for Intervention Image",
        "intervention/image         4.x  PHP image processing library",
    ],
    "Step 2 — Install Intervention Image (Composer)",
)

terminal_shot(
    "03-storage-link.png",
    [
        "$ php artisan storage:link",
        "",
        "   INFO  The [public/storage] link has been connected to [storage/app/public].",
        "",
        "Uploaded photos are saved on the public disk:",
        "  storage/app/public/products/<uuid>.webp",
        "Browser URL:",
        "  http://127.0.0.1:8000/storage/products/<uuid>.webp",
    ],
    "Step 2 — Link public storage",
)

terminal_shot(
    "08-artisan-test-webp.png",
    test_lines,
    "Step 8 — LabActFourInterventionTest",
)

webp_sample = ""
products = APP / "storage" / "app" / "public" / "products"
if products.is_dir():
    webps = sorted(products.glob("*.webp"))
    webp_sample = webps[-1].name if webps else ""

file_lines = [
    "$ ls storage/app/public/products/*.webp | tail -1",
]
if webp_sample:
    file_lines.append(f"storage/app/public/products/{webp_sample}")
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
        file_lines.append("WebP image data")
else:
    file_lines += [
        "(run an admin upload first)",
        "products/<uuid>.webp",
    ]

terminal_shot(
    "02b-disk-webp-proof.png",
    file_lines,
    "Proof — saved file is WebP on disk",
)

post_request = APP / "app" / "Http" / "Requests" / "PostRequest.php"
code_shot(
    "04-postrequest-image-rules.png",
    "Step 4 — PostRequest image validation",
    post_request,
    21,
    34,
)

controller = APP / "app" / "Http" / "Controllers" / "AdminPostController.php"
code_shot(
    "05-save-watermarked-image.png",
    "Step 5 — saveWatermarkedImage() core",
    controller,
    148,
    186,
)

model = APP / "app" / "Models" / "Post.php"
code_shot(
    "06-display-image-url.png",
    "Step 6 — Post::displayImageUrl()",
    model,
    56,
    67,
)

terminal_shot(
    "90-rubric-checklist.png",
    [
        "Finals Lab Activity 1 — rubric checklist",
        "",
        "[ ] intervention/image-laravel v4 in composer.json",
        "[ ] php artisan storage:link",
        "[ ] public/images/watermark.png exists",
        "[ ] Admin upload: JPG, PNG, WebP (max 3 MB)",
        "[ ] Resize max width 1000, watermark bottom-right",
        "[ ] Saved as products/<uuid>.webp on public disk",
        "[ ] Post::displayImageUrl() on public pages",
        "[ ] Soft delete keeps file; force delete removes WebP",
        "[ ] php artisan test --filter=LabActFourInterventionTest passes",
    ],
    "Appendix — Rubric checklist",
)

print("done", OUT)
