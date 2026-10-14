<?php
// Variabel buat inputan nantinya dari form HTML
$nama_barang = '';
$jumlah_beli = '';
$price = '';
$member = false;

// Variabel ada yang kosong atau nol karena nantinya ketika halaman pertama kali dibuka, sebelum pengguna mengirim form.
$total = 0;
$diskon = 0;
$potongan = 0;
$pay = 0;

$submitted = false;
$error = '';

// Function menghitung total setelah diskon, Mengalikan harga, menentukan diskon, lalu mengurangkan diskon
function hitung_total($price, $jumlah_beli, $member) {
    // Menghitung Subtotal
    $total = $price * $jumlah_beli;

    // Menentukan diskon berdasarkan status member
    if ($member) {
        $diskon = 0.10;
    } else {
        $diskon = 0.02;
    }

    // Mengembalikan hasil dengan return
    return $total - ($total * $diskon);
}

// Function untuk ke format rupiah nantinya
function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Proses from POST Menghubungkan HTML dengan PHP

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['hitung'])) {

    // Mengambil data dari form POST
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $jumlah_input = $_POST['jumlah_beli'] ?? '';
    $price_input = $_POST['price'] ?? '';
    $member = isset($_POST['member']);

     // Validasi input untuk memastikan data yang dimasukkan valid 
    $jumlah_beli = filter_var(
        $jumlah_input,
        FILTER_VALIDATE_INT
    );

    if (
        $nama_barang === ''
        || $jumlah_beli === false
        || $jumlah_beli < 1
        || !is_numeric($price_input)
        || !is_finite((float) $price_input)
        || (float) $price_input < 0
    ) {
        $error = 'Data tidak valid. Periksa kembali input kamu.';
    } else {
        // Menghitung transaksi setelah validasi berhasil
        $price = (float) $price_input;

        $total = $price * $jumlah_beli;
        $diskon = $member ? 0.10 : 0.02; // <- Operator ternary: kondisi ? nilai_jika_benar : nilai_jika_salah;
        $potongan = $total * $diskon;

        $pay = hitung_total(
            $price,
            $jumlah_beli,
            $member
        );

        $submitted = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>KasirKu | Kalkulator Diskon</title>

    <style>
        /* =========================
           1. RESET DAN WARNA
        ========================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --dark: #10251c;
            --green: #16a36a;
            --green-dark: #087c4d;
            --light-green: #eaf8ef;
            --bg: #f3f7f4;
            --white: #ffffff;
            --text: #1b2b22;
            --muted: #7a8980;
            --border: #e4ebe6;
            --red: #bd3434;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background:
                radial-gradient(
                    circle at top left,
                    #dff6e8,
                    transparent 35%
                ),
                var(--bg);

            color: var(--text);
            min-height: 100vh;
            padding: 35px 20px;
        }

        input,
        button {
            font: inherit;
        }

        /* =========================
           2. LAYOUT UTAMA
        ========================= */

        .app {
            max-width: 1050px;
            margin: auto;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 23px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-icon {
            display: grid;
            place-items: center;
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: var(--green);
            color: white;
            font-size: 24px;
        }

        .brand span:last-child {
            color: var(--green);
        }

        .status {
            background: #e1f7e9;
            color: var(--green-dark);
            border: 1px solid #c7ecd5;
            padding: 10px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
        }

        /* =========================
           3. HERO SECTION
        ========================= */

        .hero {
            background: var(--dark);
            color: white;
            padding: 35px;
            border-radius: 24px;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 230px;
            height: 230px;
            border-radius: 50%;
            background: var(--green);
            opacity: 0.17;
            right: -65px;
            top: -110px;
        }

        .hero-label {
            color: #86e5b5;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .hero h1 {
            font-size: clamp(28px, 4vw, 40px);
            line-height: 1.2;
            letter-spacing: -1.3px;
            margin: 15px 0 12px;
        }

        .hero p {
            color: #c4d6cb;
            line-height: 1.8;
            font-size: 14px;
            max-width: 520px;
        }

        /* =========================
           4. GRID DAN CARD
        ========================= */

        .dashboard {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
        }

        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 8px 30px
                rgba(16, 37, 28, 0.05);

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease;
        }

        .card:hover {
            box-shadow: 0 14px 35px
                rgba(16, 37, 28, 0.08);
        }

        .card-heading {
            margin-bottom: 25px;
        }

        .card-heading h2 {
            font-size: 19px;
            margin-bottom: 8px;
            letter-spacing: -0.4px;
        }

        .card-heading p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        /* =========================
           5. FORM INPUT
        ========================= */

        .form-group {
            margin-bottom: 21px;
        }

        .form-group > label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 9px;
        }

        .input-box {
            position: relative;
        }

        .input-box input {
            width: 100%;
            min-width: 0;
            padding: 14px;
            border: 1px solid #dfe7e1;
            border-radius: 11px;
            outline: none;
            background: #fbfdfb;
            color: var(--text);

            transition:
                border-color 0.2s,
                box-shadow 0.2s;
        }

        .input-box input:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 4px
                rgba(22, 163, 106, 0.10);
        }

        .input-box.has-prefix input {
            padding-left: 45px;
        }

        .prefix {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 13px;
            font-weight: 700;
        }

        /* =========================
           6. MEMBER CHECKBOX
        ========================= */

        .member-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 13px;
            cursor: pointer;

            transition:
                background 0.2s,
                border-color 0.2s;
        }

        .member-option:has(input:checked) {
            background: var(--light-green);
            border-color: #83d9a9;
        }

        .member-option:hover {
            border-color: var(--green);
        }

        .member-info strong {
            display: block;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .member-info small {
            display: block;
            color: var(--muted);
            font-size: 12px;
        }

        .member-option input {
            width: 19px;
            height: 19px;
            accent-color: var(--green);
            cursor: pointer;
            flex-shrink: 0;
        }

        /* =========================
           7. BUTTON
        ========================= */

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            background: var(--green);
            color: white;
            font-weight: 800;
            cursor: pointer;

            transition:
                background 0.2s,
                transform 0.2s,
                box-shadow 0.2s;
        }

        .btn:hover {
            background: var(--green-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px
                rgba(22, 163, 106, 0.22);
        }

        .btn:active {
            transform: scale(0.98);
        }

        /* =========================
           8. TOTAL PEMBAYARAN
        ========================= */

        .total-box {
            padding: 23px;
            border-radius: 16px;
            background: linear-gradient(
                135deg,
                #eaf9ef,
                #f4fcf6
            );
            border: 1px solid #d7f0e0;
            margin-bottom: 25px;
        }

        .total-label {
            color: #597566;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.3px;
        }

        .total-price {
            color: var(--green-dark);
            font-size: clamp(25px, 3vw, 34px);
            font-weight: 850;
            letter-spacing: -1px;
            margin: 13px 0;
            overflow-wrap: anywhere;
        }

        .badge {
            display: inline-block;
            background: #d7f4e3;
            color: #087c4d;
            padding: 7px 10px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 800;
        }

        /* =========================
           9. RINGKASAN
        ========================= */

        .summary-heading {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin: 17px 0;
            font-size: 13px;
        }

        .summary-row span {
            color: var(--muted);
        }

        .summary-row strong {
            text-align: right;
            overflow-wrap: anywhere;
        }

        .saving {
            color: var(--green-dark);
        }

        .separator {
            border: none;
            border-top: 1px dashed #dbe5de;
            margin: 22px 0;
        }

        /* =========================
           10. HASIL DAN SIMULASI
        ========================= */

        .success {
            padding: 13px;
            margin-bottom: 22px;
            border-radius: 10px;
            background: #eaf9ef;
            color: #087c4d;
            font-size: 13px;
            line-height: 1.6;
        }

        .result-heading {
            font-size: 15px;
            margin-bottom: 15px;
        }

        .simulation-list {
            list-style: none;
        }

        .simulation-list li {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 0;
            border-bottom: 1px solid #edf1ee;
            font-size: 12px;
        }

        .simulation-list li span {
            color: var(--muted);
        }

        .simulation-list li strong {
            text-align: right;
        }

        .note {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
            margin-top: 14px;
        }

        .empty-state {
            margin-top: 25px;
            padding: 24px 15px;
            text-align: center;
            background: #f8faf8;
            border: 1px dashed #dce6df;
            border-radius: 13px;
        }

        .empty-icon {
            font-size: 35px;
            margin-bottom: 12px;
        }

        .empty-state strong {
            font-size: 13px;
        }

        .empty-state p {
            margin-top: 8px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .error {
            margin-top: 17px;
            padding: 13px;
            border-radius: 10px;
            background: #fff0f0;
            border: 1px solid #ffd8d8;
            color: var(--red);
            font-size: 13px;
            line-height: 1.6;
        }

        /* =========================
           11. FOOTER
        ========================= */

        footer {
            text-align: center;
            color: #89958d;
            font-size: 12px;
            margin-top: 28px;
            line-height: 1.8;
        }

        /* =========================
           12. RESPONSIVE
        ========================= */

        @media (max-width: 750px) {
            body {
                padding: 20px 13px;
            }

            .dashboard {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 26px;
            }

            .card {
                padding: 22px;
            }

            .brand {
                font-size: 20px;
            }
        }

        @media (max-width: 400px) {
            .status {
                padding: 8px 10px;
                font-size: 10px;
            }

            .brand {
                font-size: 17px;
                gap: 8px;
            }

            .brand-icon {
                width: 39px;
                height: 39px;
            }
        }
    </style>
</head>

<body>
<div class="app">

    <!-- HEADER -->
    <header class="navbar">
        <div class="brand">
            <div class="brand-icon">▦</div>
            MyCashier<span>.</span>
        </div>

        <div class="status">
            ● System Actived
        </div>
    </header>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-label">
            Smart Cashier System
        </div>

        <h1>
            Belanja lebih mudah,<br>
            hitung lebih cepat.
        </h1>

        <p>
            Kalkulator transaksi sederhana untuk
            menghitung harga, diskon, dan total pembayaran
            dengan mudah.
        </p>
    </section>

    <!-- DASHBOARD -->
    <main class="dashboard">

        <!-- FORM TRANSAKSI -->
        <section class="card">

            <div class="card-heading">
                <h2>🛒 Input Transaksi</h2>

                <p>
                    Masukkan informasi barang
                    yang ingin dibeli.
                </p>
            </div>

            <form action="" method="POST">

                <div class="form-group">
                    <label for="nama_barang">
                        Nama Barang / Pelanggan
                    </label>

                    <div class="input-box">
                        <input
                            type="text"
                            name="nama_barang"
                            id="nama_barang"
                            placeholder="Contoh: Keyboard Mechanical"
                            value="<?= htmlspecialchars(
                                $nama_barang,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="jumlah_beli">
                        Jumlah Pembelian
                    </label>

                    <div class="input-box">
                        <input
                            type="number"
                            name="jumlah_beli"
                            id="jumlah_beli"
                            placeholder="Contoh: 3"
                            min="1"
                            step="1"
                            value="<?= htmlspecialchars(
                                (string) $jumlah_beli,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="price">
                        Harga Satuan
                    </label>

                    <div class="input-box has-prefix">
                        <span class="prefix">Rp</span>

                        <input
                            type="number"
                            name="price"
                            id="price"
                            placeholder="150000"
                            min="0"
                            step="1"
                            value="<?= htmlspecialchars(
                                (string) $price,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label>Keanggotaan</label>

                    <label
                        class="member-option"
                        for="member"
                    >
                        <div class="member-info">
                            <strong>
                                💳 Saya punya kartu member
                            </strong>
                        </div>

                        <input
                            type="checkbox"
                            name="member"
                            id="member"
                            value="1"
                            <?= $member ? 'checked' : '' ?>
                        >
                    </label>
                </div>

                <button
                    class="btn"
                    type="submit"
                    name="hitung"
                >
                    Hitung Total &rarr;
                </button>

                <?php if ($error !== ''): ?>
                    <div class="error">
                        <?= htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>
                <?php endif; ?>

            </form>
        </section>

        <!-- RINGKASAN TRANSAKSI -->
        <section class="card">

            <div class="card-heading">
                <h2>📊 Ringkasan Transaksi</h2>

                <p>
                    Hasil perhitungan dan detail
                    pembayaran kamu.
                </p>
            </div>

            <?php if ($submitted): ?>

                <div class="total-box">
                    <div class="total-label">
                        TOTAL PEMBAYARAN
                    </div>

                    <div class="total-price">
                        <?= rupiah($pay) ?>
                    </div>

                    <span class="badge">
                        Diskon <?= $diskon * 100 ?>%
                    </span>
                </div>

                <h3 class="summary-heading">
                    Detail Pembayaran
                </h3>

                <div class="summary-row">
                    <span>Nama barang</span>

                    <strong>
                        <?= htmlspecialchars(
                            $nama_barang,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Jumlah pembelian</span>

                    <strong>
                        <?= $jumlah_beli ?> pcs
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Harga satuan</span>

                    <strong>
                        <?= rupiah($price) ?>
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Subtotal</span>

                    <strong>
                        <?= rupiah($total) ?>
                    </strong>
                </div>

                <div class="summary-row saving">
                    <span>Potongan diskon</span>

                    <strong>
                        − <?= rupiah($potongan) ?>
                    </strong>
                </div>

                <hr class="separator">

                <div class="summary-row">
                    <span>Status member</span>

                    <strong>
                        <?= $member
                            ? 'Member'
                            : 'Nonmember' ?>
                    </strong>
                </div>

                <div class="success">
                    ✓ Perhitungan transaksi berhasil!
                </div>

                // loops kelipatan harga untuk 1 sampai 5
                <h3 class="result-heading">
                    Simulasi Harga 1–5 pcs
                </h3>

                <ul class="simulation-list">

                    <?php for (
                        $i = 1;
                        $i <= 5;
                        $i++
                    ): ?>

                        <li>
                            <span>
                                Beli <?= $i ?> pcs
                            </span>

                            <strong>
                                <?= rupiah($price * $i) ?>
                            </strong>
                        </li>

                    <?php endfor; ?>

                </ul>

                <p class="note">
                    * Simulasi menggunakan harga sebelum
                    diskon. Total pembayaran menggunakan
                    diskon sesuai status member.
                </p>

            <?php else: ?>

                <div class="total-box">
                    <div class="total-label">
                        TOTAL PEMBAYARAN
                    </div>

                    <div class="total-price">
                        Rp 0
                    </div>

                    <span class="badge">
                        Diskon member 10% / nonmember 2%
                    </span>
                </div>

                <h3 class="summary-heading">
                    Detail Pembayaran
                </h3>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>Rp 0</strong>
                </div>

                <div class="summary-row">
                    <span>Potongan diskon</span>
                    <strong>Rp 0</strong>
                </div>

                <hr class="separator">

                <div class="empty-state">
                    <div class="empty-icon">🧾</div>

                    <strong>Siap menghitung transaksi?</strong>

                    <p>
                        Isi form di sebelah kiri,
                        kemudian tekan tombol
                        Hitung Total untuk melihat
                        hasil transaksi.
                    </p>
                </div>

            <?php endif; ?>

        </section>
    </main>

    <footer>
        MyCashier © 2026<br>
    </footer>

</div>
</body>
</html>
