import os
from PIL import Image, ImageDraw, ImageFont

W, H = 1080, 1920

# Colors
BG_TOP = (7, 24, 18)
BG_BOTTOM = (13, 44, 34)
CARD_BG = (16, 42, 33, 230)
CARD_BORDER = (45, 95, 75)
GOLD = (230, 184, 0)
EMERALD = (46, 204, 113)
WHITE = (255, 255, 255)
LIGHT_GRAY = (200, 215, 210)
SUB_TEXT = (140, 175, 160)

FONT_DIR = "zabi/assets/font"
BOLD_FONT_PATH = os.path.join(FONT_DIR, "Roboto-Bold.ttf")
MED_FONT_PATH = os.path.join(FONT_DIR, "Roboto-Medium.ttf")
REG_FONT_PATH = os.path.join(FONT_DIR, "Roboto-Regular.ttf")
ARABIC_FONT_PATH = os.path.join(FONT_DIR, "Amiri-Regular.ttf")

font_tag = ImageFont.truetype(BOLD_FONT_PATH, 24)
font_title = ImageFont.truetype(BOLD_FONT_PATH, 58)
font_sub = ImageFont.truetype(REG_FONT_PATH, 28)
font_card_title = ImageFont.truetype(BOLD_FONT_PATH, 34)
font_card_sub = ImageFont.truetype(REG_FONT_PATH, 22)
font_arabic = ImageFont.truetype(ARABIC_FONT_PATH, 36)
font_big_num = ImageFont.truetype(BOLD_FONT_PATH, 72)

def create_base_canvas(tag_text, title_line1, title_line2, subtitle):
    # Create canvas with vertical gradient
    base = Image.new("RGBA", (W, H))
    draw = ImageDraw.Draw(base)
    
    # Gradient
    for y in range(H):
        ratio = y / H
        r = int(BG_TOP[0] + (BG_BOTTOM[0] - BG_TOP[0]) * ratio)
        g = int(BG_TOP[1] + (BG_BOTTOM[1] - BG_TOP[1]) * ratio)
        b = int(BG_TOP[2] + (BG_BOTTOM[2] - BG_TOP[2]) * ratio)
        draw.line([(0, y), (W, y)], fill=(r, g, b, 255))
        
    # Top Tag
    tag_w = draw.textlength(tag_text, font=font_tag) + 40
    tag_x, tag_y = 80, 80
    draw.rounded_rectangle([tag_x, tag_y, tag_x + tag_w, tag_y + 46], radius=23, fill=(230, 184, 0, 40), outline=GOLD, width=1)
    draw.text((tag_x + 20, tag_y + 8), tag_text, font=font_tag, fill=GOLD)
    
    # Title
    draw.text((80, 150), title_line1, font=font_title, fill=WHITE)
    draw.text((80, 225), title_line2, font=font_title, fill=WHITE)
    
    # Subtitle
    draw.text((80, 315), subtitle, font=font_sub, fill=SUB_TEXT)
    
    # Phone Container Mockup
    phone_x0, phone_y0 = 80, 390
    phone_x1, phone_y1 = W - 80, H - 70
    draw.rounded_rectangle([phone_x0, phone_y0, phone_x1, phone_y1], radius=48, fill=(12, 34, 26, 250), outline=CARD_BORDER, width=2)
    
    return base, draw, (phone_x0, phone_y0, phone_x1, phone_y1)


# =========================================================================
# 1. PRAYER TIMES SCREENSHOT
# =========================================================================
img1, draw1, (px0, py0, px1, py1) = create_base_canvas(
    "PRAYER & AZAN",
    "Accurate Prayer Times",
    "& Azan Reminders",
    "Real-time countdowns & notifications for your location"
)

# App Logo inside Phone
logo_path = "zabi/assets/icon/deenio_logo_hr.png"
if os.path.exists(logo_path):
    logo = Image.open(logo_path).convert("RGBA")
    logo.thumbnail((260, 90), Image.Resampling.LANCZOS)
    img1.paste(logo, (px0 + 50, py0 + 40), logo)

# Upcoming Prayer Banner inside phone
up_y0 = py0 + 150
up_y1 = up_y0 + 240
draw1.rounded_rectangle([px0 + 40, up_y0, px1 - 40, up_y1], radius=28, fill=(22, 78, 58, 255), outline=GOLD, width=2)
draw1.text((px0 + 80, up_y0 + 30), "UPCOMING PRAYER", font=font_card_sub, fill=GOLD)
draw1.text((px0 + 80, up_y0 + 75), "Asr • 03:08 PM", font=ImageFont.truetype(BOLD_FONT_PATH, 48), fill=WHITE)
draw1.text((px0 + 80, up_y0 + 160), "Time remaining: 01 hr 14 min", font=font_sub, fill=LIGHT_GRAY)

# Prayer Times rows
prayers = [
    ("Fajr", "04:52 AM", "Passed", False),
    ("Dhuhr", "11:48 AM", "Passed", False),
    ("Asr", "03:08 PM", "Next Prayer", True),
    ("Maghrib", "05:22 PM", "Upcoming", False),
    ("Isha", "06:40 PM", "Upcoming", False),
]

row_start_y = up_y1 + 40
row_h = 135
for i, (name, time_str, status, is_active) in enumerate(prayers):
    ry0 = row_start_y + i * (row_h + 20)
    ry1 = ry0 + row_h
    fill_color = (30, 95, 72, 255) if is_active else (18, 48, 38, 240)
    border_color = GOLD if is_active else (40, 90, 70)
    border_w = 2 if is_active else 1
    
    draw1.rounded_rectangle([px0 + 40, ry0, px1 - 40, ry1], radius=22, fill=fill_color, outline=border_color, width=border_w)
    
    name_color = GOLD if is_active else WHITE
    draw1.text((px0 + 80, ry0 + 28), name, font=font_card_title, fill=name_color)
    draw1.text((px0 + 80, ry0 + 78), status, font=font_card_sub, fill=SUB_TEXT if not is_active else LIGHT_GRAY)
    
    time_w = draw1.textlength(time_str, font=font_card_title)
    draw1.text((px1 - 80 - time_w, ry0 + 42), time_str, font=font_card_title, fill=WHITE)

img1.save("playstore_assets/screenshots/screenshot_1_prayer_times.png", "PNG")
print("Saved screenshot 1")


# =========================================================================
# 2. HOLY QURAN SCREENSHOT
# =========================================================================
img2, draw2, (px0, py0, px1, py1) = create_base_canvas(
    "HOLY QURAN",
    "The Holy Quran Majeed",
    "with Audio Recitation",
    "Read 114 Surahs with translations, tafsir and reciters"
)

# Surah Header Banner inside phone
q_y0 = py0 + 50
q_y1 = q_y0 + 220
draw2.rounded_rectangle([px0 + 40, q_y0, px1 - 40, q_y1], radius=28, fill=(24, 82, 62, 255), outline=GOLD, width=2)
draw2.text((px0 + 80, q_y0 + 35), "Surah Al-Mulk (الملك)", font=ImageFont.truetype(BOLD_FONT_PATH, 42), fill=GOLD)
draw2.text((px0 + 80, q_y0 + 95), "The Sovereignty • 30 Verses • Makki", font=font_sub, fill=WHITE)

# Audio Bar
draw2.rounded_rectangle([px0 + 80, q_y0 + 145, px1 - 80, q_y0 + 195], radius=12, fill=(0, 0, 0, 100))
draw2.text((px0 + 110, q_y0 + 155), "▶  Reciter: Mishary Rashid Alafasy", font=ImageFont.truetype(BOLD_FONT_PATH, 20), fill=WHITE)

verses = [
    ("1", "تَبَارَكَ الَّذِي بِيَدِهِ الْمُلْكُ وَهُوَ عَلَىٰ كُلِّ شَيْءٍ قَدِيرٌ", "Blessed is He in whose hand is dominion, and He is over all things competent."),
    ("2", "الَّذِي خَلَقَ الْمَوْتَ وَالْحَيَاةَ لِيَبْلُوَكُمْ أَيُّكُمْ أَحْسَنُ عَمَلًا", "He who created death and life to test you as to which of you is best in deed."),
    ("3", "الَّذِي خَلَقَ سَبْعَ سَمَاوَاتٍ طِبَاقًا", "Who created seven heavens in layers without any flaw.")
]

v_start_y = q_y1 + 40
v_h = 290
for i, (v_num, v_ar, v_en) in enumerate(verses):
    vy0 = v_start_y + i * (v_h + 25)
    vy1 = vy0 + v_h
    draw2.rounded_rectangle([px0 + 40, vy0, px1 - 40, vy1], radius=22, fill=(18, 48, 38, 240), outline=(40, 90, 70), width=1)
    
    # Badge
    draw2.rounded_rectangle([px0 + 70, vy0 + 30, px0 + 130, vy0 + 90], radius=16, fill=(30, 95, 72), outline=GOLD, width=1)
    bw = draw2.textlength(v_num, font=font_card_title)
    draw2.text((px0 + 100 - bw/2, vy0 + 40), v_num, font=font_card_title, fill=GOLD)
    
    # Arabic
    ar_w = draw2.textlength(v_ar, font=font_arabic)
    draw2.text((px1 - 80 - ar_w, vy0 + 35), v_ar, font=font_arabic, fill=WHITE)
    
    # English translation
    draw2.text((px0 + 70, vy0 + 140), v_en, font=font_sub, fill=LIGHT_GRAY)
    
    # Action buttons
    draw2.text((px0 + 70, vy0 + 225), "🔊 Play Audio   •   📖 Tafsir   •   🔖 Bookmark", font=ImageFont.truetype(REG_FONT_PATH, 20), fill=SUB_TEXT)

img2.save("playstore_assets/screenshots/screenshot_2_holy_quran.png", "PNG")
print("Saved screenshot 2")


# =========================================================================
# 3. QIBLA COMPASS SCREENSHOT
# =========================================================================
img3, draw3, (px0, py0, px1, py1) = create_base_canvas(
    "QIBLA COMPASS",
    "Precise Qibla Direction",
    "Anywhere on Earth",
    "Accurate 360° sensor compass pointing directly to Kaaba"
)

# Paste Compass Frame
compass_path = "zabi/assets/icon/Compass_Frame.png"
if os.path.exists(compass_path):
    c_img = Image.open(compass_path).convert("RGBA")
    c_size = 560
    c_img = c_img.resize((c_size, c_size), Image.Resampling.LANCZOS)
    cx = px0 + (px1 - px0 - c_size) // 2
    cy = py0 + 130
    img3.paste(c_img, (cx, cy), c_img)

# Direction details card below compass
dir_y0 = cy + c_size + 60
dir_y1 = dir_y0 + 320
draw3.rounded_rectangle([px0 + 40, dir_y0, px1 - 40, dir_y1], radius=28, fill=(22, 78, 58, 255), outline=GOLD, width=2)

draw3.text((px0 + 80, dir_y0 + 40), "278° West", font=font_big_num, fill=GOLD)
draw3.text((px0 + 80, dir_y0 + 135), "Facing the Holy Kaaba, Makkah", font=font_card_title, fill=WHITE)
draw3.text((px0 + 80, dir_y0 + 195), "Distance to Makkah: 4,821 km", font=font_sub, fill=LIGHT_GRAY)
draw3.text((px0 + 80, dir_y0 + 245), "Sensor Status: Calibrated (High Accuracy)", font=ImageFont.truetype(REG_FONT_PATH, 22), fill=SUB_TEXT)

img3.save("playstore_assets/screenshots/screenshot_3_qibla_compass.png", "PNG")
print("Saved screenshot 3")


# =========================================================================
# 4. DAILY DUAS & TASBIH SCREENSHOT
# =========================================================================
img4, draw4, (px0, py0, px1, py1) = create_base_canvas(
    "DAILY DHIKR & DUAS",
    "Authentic Islamic Duas",
    "& Digital Tasbih",
    "Hisnul Muslim supplications, morning/evening azkar & counter"
)

# Tasbih Circle
tasbih_y0 = py0 + 60
tasbih_size = 420
tx = px0 + (px1 - px0 - tasbih_size) // 2
ty = tasbih_y0

# Outer ring
draw4.ellipse([tx, ty, tx + tasbih_size, ty + tasbih_size], outline=(30, 95, 72), width=18)
# Progress arc
draw4.arc([tx, ty, tx + tasbih_size, ty + tasbih_size], start=-90, end=45, fill=GOLD, width=18)

# Center Arabic text & counter
tasbih_text = "سُبْحَانَ اللَّهِ"
tw = draw4.textlength(tasbih_text, font=font_arabic)
draw4.text((px0 + (px1 - px0 - tw)//2, ty + 70), tasbih_text, font=font_arabic, fill=WHITE)

cw = draw4.textlength("33", font=ImageFont.truetype(BOLD_FONT_PATH, 96))
draw4.text((px0 + (px1 - px0 - cw)//2, ty + 150), "33", font=ImageFont.truetype(BOLD_FONT_PATH, 96), fill=GOLD)

gw = draw4.textlength("Target: 100 • SubhanAllah", font=font_card_sub)
draw4.text((px0 + (px1 - px0 - gw)//2, ty + 290), "Target: 100 • SubhanAllah", font=font_card_sub, fill=LIGHT_GRAY)

# Dua Categories list
duas = [
    ("Morning & Evening Azkar", "Sunnah supplications for morning and evening protection"),
    ("Prayers for Forgiveness (Istighfar)", "Sayyidul Istighfar and seeking Allah's mercy"),
    ("Daily Life Supplications", "Authentic duas before sleeping, eating, traveling and entering home")
]

d_start_y = ty + tasbih_size + 70
d_h = 160
for i, (title, desc) in enumerate(duas):
    dy0 = d_start_y + i * (d_h + 25)
    dy1 = dy0 + d_h
    draw4.rounded_rectangle([px0 + 40, dy0, px1 - 40, dy1], radius=22, fill=(18, 48, 38, 240), outline=(40, 90, 70), width=1)
    draw4.text((px0 + 80, dy0 + 35), title, font=font_card_title, fill=WHITE)
    draw4.text((px0 + 80, dy0 + 90), desc, font=font_card_sub, fill=LIGHT_GRAY)

img4.save("playstore_assets/screenshots/screenshot_4_duas_tasbih.png", "PNG")
print("Saved screenshot 4")

print("All 4 screenshots generated successfully!")
