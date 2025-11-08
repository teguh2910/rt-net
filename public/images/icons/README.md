# PWA Icons

## 📁 Folder Struktur

```
public/images/
├── icons/          # App icons (berbagai ukuran)
├── splash/         # iOS splash screens
└── screenshots/    # App screenshots untuk store
```

## 🎨 Cara Generate Icons

### Opsi 1: Menggunakan Icon Generator (Cepat)

1. Buka browser dan akses: `http://localhost/icon-generator.html`
2. Download semua ukuran icon yang muncul
3. Simpan semua file ke folder `public/images/icons/`

### Opsi 2: Menggunakan Online Tool (Recommended)

**PWA Builder (Recommended):**
1. Buka https://www.pwabuilder.com/imageGenerator
2. Upload logo Anda (minimal 512x512px, PNG dengan background)
3. Pilih platform: Web, Android, iOS, Windows
4. Download zip file
5. Extract dan copy file ke `public/images/icons/`

**Real Favicon Generator:**
1. Buka https://realfavicongenerator.net/
2. Upload master icon (512x512px recommended)
3. Kustomisasi untuk berbagai platform
4. Download package
5. Extract dan copy ke folder yang sesuai

### Opsi 3: Manual dengan Photoshop/Figma

1. Buat logo square (1024x1024px)
2. Export ke berbagai ukuran:
   - 72x72 px
   - 96x96 px
   - 128x128 px
   - 144x144 px
   - 152x152 px
   - 192x192 px
   - 384x384 px
   - 512x512 px
3. Simpan dengan nama: `icon-[size]x[size].png`

## 📋 Icon Requirements

### Ukuran yang Dibutuhkan:

| Size | Purpose | Priority |
|------|---------|----------|
| 72x72 | Android Chrome, MS Tile | High |
| 96x96 | Android Chrome | High |
| 128x128 | Android Chrome | Medium |
| 144x144 | MS Tile, Progressive Web App | High |
| 152x152 | iOS Safari | High |
| 192x192 | Android Chrome, PWA Standard | **REQUIRED** |
| 384x384 | Android Chrome | Medium |
| 512x512 | Splash Screen, PWA Standard | **REQUIRED** |

### Format:
- Type: PNG
- Background: Solid color (tidak transparent untuk maskable icons)
- Safe zone: 10% padding dari edge untuk maskable icons

## 🍎 iOS Splash Screens (Optional)

Untuk pengalaman iOS yang lebih baik, buat splash screens:

| Size | Device |
|------|--------|
| 640x1136 | iPhone 5/SE |
| 750x1334 | iPhone 6/7/8 |
| 1242x2208 | iPhone 6+/7+/8+ |
| 1125x2436 | iPhone X/XS/11 Pro |
| 1242x2688 | iPhone XS Max/11 Pro Max |

Simpan di `public/images/splash/` dengan nama: `splash-[width]x[height].png`

## 📸 Screenshots (Optional)

Untuk PWA listing dan better user experience:

- Minimal size: 320x320 px
- Maksimal size: 3840x3840 px
- Recommended: 540x720 px (portrait) atau 720x540 px (landscape)
- Format: PNG atau JPEG
- Simpan di `public/images/screenshots/`

## ✅ Checklist

- [ ] Icon 192x192 (REQUIRED)
- [ ] Icon 512x512 (REQUIRED)
- [ ] Icon 72x72 (for MS Tile)
- [ ] Icon 96x96
- [ ] Icon 144x144
- [ ] Icon 152x152 (for iOS)
- [ ] iOS splash screens (optional)
- [ ] App screenshots (optional)

## 🎨 Design Tips

1. **Keep it simple** - Icon harus jelas di ukuran kecil
2. **Use solid background** - Untuk maskable icons
3. **Center the logo** - Dengan safe zone 10% padding
4. **Test on real device** - Lihat bagaimana tampilannya
5. **Match brand colors** - Sesuaikan dengan tema app

## 🔧 Quick Commands

Generate placeholder icons (Windows PowerShell):
```powershell
# Buka icon generator di browser
start http://localhost/icon-generator.html
```

Verify icons exist:
```powershell
Get-ChildItem public/images/icons/
```

## 📝 Notes

- Placeholder icons sudah dibuat oleh icon-generator.html
- Ganti dengan logo real untuk production
- Test di real device untuk memastikan icons terlihat bagus
- Gunakan maskable icons untuk Android adaptive icons

## 🆘 Troubleshooting

**Icon tidak muncul:**
- Pastikan nama file exact match dengan manifest.json
- Clear browser cache
- Check file permissions
- Verify file path correct

**Icon terpotong di Android:**
- Use maskable icons dengan safe zone
- Test dengan PWA Builder's maskable icon tool

**Icon blur:**
- Pastikan menggunakan PNG, bukan JPEG
- Export dengan quality 100%
- Jangan scale up dari ukuran kecil

---

**Pro Tip:** Gunakan SVG untuk master logo, lalu export ke berbagai ukuran PNG untuk quality terbaik! 🎨
