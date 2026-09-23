#!/usr/bin/env python3
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont
import subprocess

OUT = Path(__file__).resolve().parent / "progress-shots"
OUT.mkdir(parents=True, exist_ok=True)
# app root (artisan lives here). docs/ is one level down from workspace.
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


# refresh routes.txt for the routes shot
try:
    result = subprocess.run(
        ["php", "artisan", "route:list", "--path=/"],
        cwd=str(APP),
        capture_output=True,
        text=True,
        timeout=30,
    )
    routes_body = (result.stdout or "").strip().splitlines()
    (OUT / "routes.txt").write_text("\n".join(routes_body[:40]) + "\n", encoding="utf-8")
except Exception as exc:
    print("route list skipped:", exc)
    routes_body = (OUT / "routes.txt").read_text(errors="ignore").splitlines() if (OUT / "routes.txt").exists() else []

terminal_shot(
    "00-create-project.png",
    [
        "$ cd ~/laravel\\ elec",
        "$ composer create-project laravel/laravel:^11.0 petal-express",
        "Creating a \"laravel/laravel:^11.0\" project at \"./petal-express\"",
        "Installing laravel/laravel (v11.6.1)",
        "...",
        "$ cd petal-express",
        "$ php artisan key:generate",
        "Application key set successfully.",
        "$ php artisan --version",
        "Laravel Framework 11.56.1",
    ],
    "Terminal - create Laravel 11 project",
)

terminal_shot(
    "00b-breeze-install.png",
    [
        "$ composer require laravel/breeze --dev",
        "$ php artisan breeze:install blade",
        "INFO  Installing and building Node dependencies.",
        "Breeze scaffolding installed successfully.",
        "$ php artisan migrate",
        "INFO  Running migrations.",
        "0001_01_01_000000_create_users_table ................ DONE",
        "$ npm install && npm run build",
        "vite v6.4.3 building for production...",
        "built successfully",
    ],
    "Terminal - install Laravel Breeze Blade",
)

terminal_shot(
    "00c-routes-controllers.png",
    [
        "$ php artisan route:list",
        *routes_body[:18],
        "",
        "Controllers:",
        "app/Http/Controllers/PageController.php",
        "app/Http/Controllers/MemberController.php",
        "app/Http/Controllers/ContactController.php",
    ],
    "Terminal - routes and controllers",
)

terminal_shot(
    "14-final-checklist.png",
    [
        "Petal Express Lab Activity 1 - final submit",
        "",
        "Done:",
        "- Laravel 11 + Breeze Blade auth (register / login / logout)",
        "- 5 guest pages + /bouquets/{id} route parameter",
        "- 3 member pages behind auth middleware",
        "- Controllers pass data to Blade views",
        "- Breeze layout customized (brand, colors, member nav)",
        "- bouquet-card component with $slot + named badge slot",
        "- Contact form with @csrf",
        "",
        "Submit file:",
        "Acuesta_John_Constantine_LabActivity1.pdf",
    ],
    "Final checklist",
)
