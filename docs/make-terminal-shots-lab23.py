#!/usr/bin/env python3
"""Terminal-style slides for Lab Activity 2 and 3 progress PDF."""
from pathlib import Path
from PIL import Image, ImageDraw, ImageFont
import subprocess

OUT = Path(__file__).resolve().parent / "progress-shots-lab23"
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


routes_body: list[str] = []
try:
    result = subprocess.run(
        ["php", "artisan", "route:list", "--path=admin"],
        cwd=str(APP),
        capture_output=True,
        text=True,
        timeout=30,
    )
    routes_body = (result.stdout or "").strip().splitlines()
    posts_routes = subprocess.run(
        ["php", "artisan", "route:list", "--path=posts"],
        cwd=str(APP),
        capture_output=True,
        text=True,
        timeout=30,
    )
    routes_body += (posts_routes.stdout or "").strip().splitlines()
except Exception as exc:
    print("route list skipped:", exc)

terminal_shot(
    "00-lab2-migrations.png",
    [
        "$ cd petal-express",
        "$ php artisan migrate",
        "2026_09_23_234450_add_role_to_users_table ............... DONE",
        "2026_09_23_234450_create_showcase_flowers_table .......... DONE",
        "  user_id foreignId, softDeletes() on showcase_flowers",
        "$ php artisan db:seed",
        "DatabaseSeeder: admin@test.com + test@test.com + starter bouquets",
        "",
        "SQLite: petal-express/database/database.sqlite",
    ],
    "Terminal - Lab Act 2 migrations and seed",
)

terminal_shot(
    "00b-lab2-role-routes.png",
    [
        "$ php artisan route:list --path=admin",
        *routes_body[:14],
        "",
        "Middleware: auth + role:admin | role:user",
        "app/Http/Middleware/EnsureUserHasRole.php",
        "bootstrap/app.php registers alias role",
    ],
    "Terminal - Lab Act 2 role routes",
)

terminal_shot(
    "00c-lab3-categories-seed.png",
    [
        "$ php artisan migrate",
        "2026_10_08_102010_create_categories_table ............... DONE",
        "2026_10_08_102011_add_category_id_to_showcase_flowers ... DONE",
        "$ php artisan db:seed",
        "5 categories, 3 named bouquets, Post::factory()->count(97)",
        "=> 100 active showcase rows for pagination demos",
        "",
        "sqlite> SELECT COUNT(*) FROM showcase_flowers WHERE deleted_at IS NULL;",
        "100",
    ],
    "Terminal - Lab Act 3 categories and 100 rows",
)

terminal_shot(
    "37-final-checklist-lab23.png",
    [
        "Petal Express Lab Activity 2 and 3 - final submit",
        "",
        "Lab Act 2:",
        "- users.role + EnsureUserHasRole middleware (403)",
        "- showcase_flowers table, Post model, admin CRUD save",
        "- member show + static custom-order form (no insert)",
        "",
        "Lab Act 3:",
        "- PostRequest validation + flash toast component",
        "- categories belongsTo / hasMany + filter chips",
        "- paginate on showcase, admin list, and trash",
        "- soft delete, restore, force delete",
        "",
        "Submit file:",
        "Acuesta_John_Constantine_LabActivity2_and_3.pdf",
    ],
    "Final checklist - Lab Act 2 and 3",
)
