# Fitur Voucher Concertix

## Pengenalan
Fitur Voucher memungkinkan Event Organizer (EO) untuk membuat dan mengelola kode diskon yang dapat digunakan oleh user saat membeli tiket. Setiap voucher hanya berlaku untuk acara/organizer yang membuat voucher tersebut.

## Fitur Utama

### 1. Untuk EO (Event Organizer)

#### Buat Voucher Baru
- Navigate ke `eo/vouchers` (Navbar EO > Voucher)
- Click "Buat Voucher Baru"
- Isi form:
  - **Kode Voucher**: Kode unik (contoh: `SAVE50`)
  - **Deskripsi**: Deskripsi opsional voucher
  - **Jenis Diskon**: Persentase (%) atau Jumlah Tetap (Rp)
  - **Nilai Diskon**: Besaran diskon
  - **Batas Total Penggunaan**: Berapa kali voucher boleh digunakan (opsional, kosongkan untuk unlimited)
  - **Max Penggunaan per User**: Maksimal kali user bisa pakai voucher ini
  - **Berlaku Dari**: Tanggal mulai voucher (opsional)
  - **Berlaku Hingga**: Tanggal akhir voucher (opsional)
  - **Status Aktif**: Checkbox untuk mengaktifkan voucher

#### Kelola Voucher
- List semua voucher di `/eo/vouchers`
- Lihat statistik untuk setiap voucher
- Edit voucher (kode tidak bisa diubah, hanya detail lainnya)
- Hapus voucher
- Lihat statistik penggunaan per user

#### Validasi Voucher
- Voucher hanya berlaku jika:
  - Status aktif = true
  - Dalam periode valid (jika ada)
  - Belum melebihi usage limit total
  - User belum melebihi max_per_user

---

### 2. Untuk User

#### Apply Voucher Saat Checkout
User bisa apply kode voucher saat process pembelian tiket (di halaman detail/payment):
```
POST /purchase/{order}/apply-voucher
Body: { "code": "SAVE50" }
```

**Response sukses:**
```json
{
  "success": true,
  "message": "Voucher berhasil diterapkan",
  "discount": "Rp 50.000",
  "new_total": "Rp 200.000"
}
```

**Response error:**
```json
{
  "error": "Kode voucher tidak valid atau tidak berlaku untuk acara ini"
}
```

#### Remove Voucher
User bisa remove voucher sebelum complete order:
```
POST /purchase/{order}/remove-voucher
```

---

## Database Schema

### Tabel `vouchers`
```
id                  (PK)
organizer_id        (FK) - Organizer yang membuat voucher
code                (UNIQUE) - Kode voucher (e.g., "SAVE50")
description         - Deskripsi opsional
discount_type       (ENUM: 'percentage', 'fixed') - Jenis diskon
discount_value      (INT) - Nilai diskon
usage_limit         (INT, nullable) - Max total penggunaan
usage_count         (INT, default: 0) - Jumlah sudah dipakai
max_per_user        (INT, default: 1) - Max per user
valid_from          (TIMESTAMP, nullable)
valid_until         (TIMESTAMP, nullable)
is_active           (BOOLEAN, default: true)
created_at, updated_at
```

### Tabel `voucher_usages`
```
id                  (PK)
voucher_id          (FK)
user_id             (FK)
order_id            (FK, nullable)
used_at             (TIMESTAMP, nullable)
created_at, updated_at
```

### Modifikasi Tabel `orders`
```
voucher_id          (FK, nullable)
discount_amount     (INT, default: 0)
```

---

## Models

### Model Voucher
```php
// Relasi
$voucher->organizer()       // Organizer pemilik voucher
$voucher->usages()          // VoucherUsage records

// Methods
$voucher->isValid()         // Check if voucher valid (date, limit, active)
$voucher->userCanUse($userId)  // Check if user bisa pakai voucher ini
$voucher->calculateDiscount($amount)  // Hitung discount amount
```

### Model VoucherUsage
```php
$usage->voucher()
$usage->user()
$usage->order()
```

---

## Routes (EO)

| Method | Route | Action |
|--------|-------|--------|
| GET | `/eo/vouchers` | List semua voucher |
| GET | `/eo/vouchers/create` | Form create voucher |
| POST | `/eo/vouchers` | Store voucher baru |
| GET | `/eo/vouchers/{id}/edit` | Form edit voucher |
| PUT | `/eo/vouchers/{id}` | Update voucher |
| DELETE | `/eo/vouchers/{id}` | Delete voucher |
| GET | `/eo/vouchers/{id}/stats` | Lihat statistik penggunaan |

## Routes (User)

| Method | Route | Action |
|--------|-------|--------|
| POST | `/purchase/{order}/apply-voucher` | Apply voucher code |
| POST | `/purchase/{order}/remove-voucher` | Remove voucher |

---

## Authorization

- EO hanya bisa manage voucher milik organizer mereka sendiri (divalidasi di Controller)
- User hanya bisa apply/remove voucher di order milik mereka sendiri
- Voucher hanya bisa diapply ke order yang belum dibayar (status: pending/processing)

---

## Contoh Usage

### EO Create Voucher
```
POST /eo/vouchers
{
  "code": "DISCOUNT20",
  "description": "Diskon 20% untuk pembeli awal",
  "discount_type": "percentage",
  "discount_value": 20,
  "max_per_user": 2,
  "valid_from": "2025-12-01",
  "valid_until": "2025-12-31",
  "is_active": true
}
```

### User Apply Voucher
```
POST /purchase/5/apply-voucher
{
  "code": "DISCOUNT20"
}

Response:
{
  "success": true,
  "message": "Voucher berhasil diterapkan",
  "discount": "Rp 200.000",
  "new_total": "Rp 800.000"
}
```

---

## Validasi & Error Handling

1. **Voucher tidak ditemukan**
   - "Kode voucher tidak valid atau tidak berlaku untuk acara ini"

2. **Voucher tidak berlaku**
   - "Voucher tidak berlaku atau sudah kadaluarsa"

3. **User sudah melebihi limit**
   - "Anda sudah mencapai batas penggunaan voucher ini"

4. **Order tidak valid**
   - "Cannot apply voucher to this order"

---

## Fitur Tambahan yang Bisa Dikembangkan

- [ ] Voucher category/tags
- [ ] Bulk voucher generation (CSV upload)
- [ ] Email notification saat voucher apply
- [ ] Analytics dashboard (top vouchers, revenue impact)
- [ ] QR code for voucher sharing
- [ ] Referral/affiliate voucher system
