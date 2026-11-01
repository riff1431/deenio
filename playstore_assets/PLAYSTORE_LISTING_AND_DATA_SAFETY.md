# 📱 Deenio - Google Play Store Listing & Data Safety Guide

This document contains everything you need to copy and paste into the **Google Play Console** for publishing **Deenio**.

---

## 1. App Details (Main Store Listing)

### **App Name (Title)** *(Max 30 characters)*
```text
Deenio: Quran, Prayer, Qibla
```
*(Character count: 28 / 30)*

---

### **Short Description** *(Max 80 characters)*
```text
Al-Quran, accurate prayer times, live Adhan, Qibla compass & Islamic assistant.
```
*(Character count: 79 / 80)*

---

### **Full Description** *(Max 4000 characters)*
```text
Deenio is your comprehensive, beautifully designed all-in-one Islamic daily lifestyle companion. Built with modern technology and authentic Islamic resources, Deenio empowers millions of Muslims worldwide to stay connected with their faith seamlessly every day.

✨ KEY FEATURES:

📖 AL-QURAN AL-KAREEM (ONLINE & OFFLINE)
• Read the Holy Quran with crystal-clear Uthmani / Indo-Pak Arabic scripts.
• High-quality audio recitation by world-renowned Qaris and Reciters.
• Multiple language translations (English, Bangla, Spanish, Arabic, Urdu, and more).
• Ayah-by-Ayah translation, audio playback, tafseer, and quick bookmarking.
• Complete Offline Quran mode — read anytime, anywhere without an active internet connection.

🕌 ACCURATE PRAYER TIMES & LIVE ADHAN ALERTS
• GPS-based precise prayer calculation (Fajr, Dhuhr, Asr, Maghrib, Isha, Sunrise).
• Multiple calculation methods (MWL, ISNA, Umm al-Qura, Karachi, Egyptian, etc.).
• Beautiful Adhan sound notifications with customizable audio reminders for each Waqt.
• Manual prayer time adjustments according to your local mosque or regional timetable.

🧭 360° PRECISION QIBLA COMPASS
• Real-time electronic compass sensor ensuring exact Kaaba direction from anywhere in the world.
• Intuitive visual alignment feedback with distance calculation to Makkah.

📍 NEARBY MOSQUES FINDER
• Instant discovery of nearby Masjids and prayer spaces around your current GPS location.
• Real-time distance indicators and one-tap navigation via Google Maps.

🤖 ISLAMIC AI ASSISTANT & BABY NAME GENERATOR
• Ask Islamic lifestyle and educational questions powered by respectful AI assistance.
• Meaningful Islamic baby name generator with authentic Arabic origins and definitions.

🤲 DUA, DHIKR & TASBIH COUNTER
• Authentic collection of daily Masnoon Duas and Azkar categorized by situations (Morning/Evening, Travel, Protection, etc.).
• Digital Tasbih / Dhikr counter with haptic feedback and target milestone tracking.

🌟 99 NAMES OF ALLAH (ASMAUL HUSNA)
• Discover all 99 names of Allah with rich Arabic pronunciation, meanings, and spiritual benefits.

⚖️ ZAKAT CALCULATOR & HARAM FOOD CODE SCANNER
• Easy-to-use Zakat calculation tool for gold, silver, savings, and assets.
• Instant E-code and food ingredient status checker for Halal & Haram food verification.

🎨 ISLAMIC WALLPAPERS & CUSTOMIZATION
• Curated collection of high-definition Islamic wallpapers with one-tap device wallpaper setup.
• Elegant light and dark themes tailored to protect your eyes during nighttime recitation.

🔒 PRIVACY & AD-FREE INTEGRITY
Deenio respects your spiritual journey and privacy. Your location data is used solely on-device to calculate exact prayer times and Qibla direction, without selling your private data to third parties.

Download Deenio today and enrich your daily Islamic routine!
```

---

## 2. Store Assets Summary

| Asset | Specifications | Status | Location |
| :--- | :--- | :---: | :--- |
| **App Icon** | 512 × 512 px, 32-bit PNG, < 1 MB | ✅ Ready | `playstore_assets/icon_512x512.png` |
| **Feature Graphic** | 1024 × 500 px, JPG/PNG, < 15 MB | ✅ Ready | `playstore_assets/feature_graphic_1024x500.png` |
| **Phone Screenshots** | Min 2, Max 8 (16:9 or 9:16) | ⏳ Capture from app | See Section 6 below |
| **Privacy Policy URL** | HTTPS public web page | ✅ Live | `https://deenio.huipper.com/privacy-policy` |

---

## 3. Data Safety Form Answers (Google Play Console)

When Google Play Console asks you the **Data safety questionnaire**, follow these exact answers:

### **Overview Questions:**
1. **Does your app collect or share any user data?**
   * Select: **Yes** (Location data is collected for Prayer Times / Qibla).
2. **Is all of the user data collected by your app encrypted in transit?**
   * Select: **Yes** (All API communication uses HTTPS / TLS encryption).
3. **Do you provide a way for users to request that their data be deleted?**
   * Select: **No** (The app does not require account creation and does not store personal account records).

### **Data Types Declared:**

#### A. Location (Approximate Location & Precise Location):
* **Collected?** Yes
* **Shared?** No (Not shared with 3rd parties).
* **Is this data processed ephemerally?** Yes (Calculated in real-time or sent to backend solely for prayer time lookup).
* **Is this data required or optional?** Required for location-based prayer calculation & Qibla finder.
* **Why is this data collected?** 
  * Check: **App functionality** (Calculating accurate prayer times, Qibla compass bearing, and nearby mosques).

#### B. App Info and Performance (Crash logs / Diagnostics):
* If Firebase Analytics / Crashlytics is enabled: Check **App performance** and **Analytics**.

---

## 4. App Access & Permissions Declaration

### A. App Access:
* **Does your app have restricted access (login)?**
  * Select: **All functionality is available without restrictions** (Since Deenio has no mandatory login or paywall to access core Islamic tools).

### B. Ads Declaration:
* **Does your app contain ads?**
  * Select: **No, my app does not contain ads** (or Yes if you enabled AdMob).

### C. Target Audience and Content:
* **Target age:** 13 and older (or 18+ depending on preference).
* **Appeal to children:** No.

### D. Exact Alarm Permission (`USE_EXACT_ALARM` / `SCHEDULE_EXACT_ALARM`):
Google Play strictly requires justification for scheduling exact alarms.
* **Category:** **Alarm / Reminder / Clock**
* **Justification text to paste:**
```text
Deenio is an Islamic utility application that schedules exact prayer time (Salah / Adhan) audio notifications throughout the day. Because Muslim daily prayers are strictly time-bound to the minute based on the sun's position, delayed or batched notifications will cause users to miss their religious prayer obligations. Therefore, the USE_EXACT_ALARM permission is critical for scheduling exact Waqt Adhan reminders reliably.
```

### E. Foreground Service (`FOREGROUND_SERVICE_MEDIA_PLAYBACK`):
* **Justification:** Continuous playback of Quranic audio surahs and recitations while the user's screen is locked or while using other applications.

---

## 5. Keystore & Release Build Guide

### Step 1: Generate your release keystore
Run the following PowerShell command in the workspace:
```powershell
& "C:\Program Files\Android\Android Studio\jbr\bin\keytool.exe" -genkey -v -keystore zabi/android/upload-keystore.jks -alias deenio_upload -keyalg RSA -keysize 2048 -validity 10000
```
*(Enter a strong password when prompted, and keep the password safe!)*

### Step 2: Create `zabi/android/key.properties`
Create `zabi/android/key.properties` with the following content:
```properties
storePassword=YOUR_CHOSEN_PASSWORD
keyPassword=YOUR_CHOSEN_PASSWORD
keyAlias=deenio_upload
storeFile=upload-keystore.jks
```

### Step 3: Build the production App Bundle (.aab)
```powershell
cd zabi
flutter build appbundle --release
```
The output `.aab` file will be created at:
`zabi/build/app/outputs/bundle/release/app-release.aab`
This is the file you upload directly to the Google Play Console!
