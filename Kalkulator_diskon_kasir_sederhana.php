
<?php
// Tampilkan error saat proses debugging.
// Setelah selesai, sebaiknya nonaktifkan di website produksi.
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Nilai awal agar tidak terjadi Undefined variable
$nama_barang = '';
$jumlah_beli = 0;
$price = 0;
$member = false;
$pay = 0;
$diskon = 0;
$submitted = false;

// Function menghitung total setelah diskon
function hitung_total($price, $jumlah_beli, $member) {
    $total = $price * $jumlah_beli;

    if ($member) {
        $diskon = 0.10;
    } else {
        $diskon = 0.02;
    }

    return $total - ($total * $diskon);
}

// Proses data ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['hitung'])) {

    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $jumlah_beli = (int) ($_POST['jumlah_beli'] ?? 0);
    $price = (float) ($_POST['price'] ?? 0);
    $member = isset($_POST['member']);

    // Validasi sederhana
    if ($nama_barang !== ''
        && $jumlah_beli >= 1
        && $price >= 0) {

        $pay = hitung_total(
            $price,
            $jumlah_beli,
            $member
        );

        $diskon = $member ? 0.10 : 0.02;
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

    <title>Kalkulator Diskon</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            padding: 30px;
            display: flex;
            justify-content: center;
        }

        .container {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 400px;
            max-width: 100%;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .checkbox-group label {
            margin-bottom: 0;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .result {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow-wrap: anywhere;
        }

        .error {
            color: #b00020;
            margin-top: 15px;
        }

        .simulation {
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>

<body>
<div class="container">
    <h2>Kalkulator Diskon</h2>

    <form action="" method="POST">
        <div class="form-group">
            <label for="nama_barang">
                Nama Barang / Pelanggan:
            </label>

            <input
                type="text"
                name="nama_barang"
                id="nama_barang"
                value="<?= htmlspecialchars($nama_barang, ENT_QUOTES, 'UTF-8') ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="jumlah_beli">Jumlah Beli:</label>

            <input
                type="number"
                name="jumlah_beli"
                id="jumlah_beli"
                min="1"
                value="<?= $jumlah_beli > 0 ? $jumlah_beli : '' ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="price">Harga Satuan (Rp):</label>

            <input
                type="number"
                name="price"
                id="price"
                min="0"
                step="500"
                value="<?= $price ?>"
                required
            >
        </div>

        <div class="form-group checkbox-group">
            <input
                type="checkbox"
                name="member"
                id="member"
                value="1"
                <?= $member ? 'checked' : '' ?>
            >

            <label for="member">Punya Kartu Member?</label>
        </div>

        <button type="submit" name="hitung">
            Hitung Total
        </button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$submitted): ?>
        <p class="error">
            Data tidak valid. Periksa nama, jumlah beli,
            dan harga yang kamu masukkan.
        </p>
    <?php endif; ?>

    <?php if ($submitted): ?>
        <div class="result">
            <h3>Ringkasan Transaksi</h3>

            <p>
                <strong>Nama/Barang:</strong>
                <?= htmlspecialchars($nama_barang, ENT_QUOTES, 'UTF-8') ?>
            </p>

            <p>
                <strong>Jumlah Beli:</strong>
                <?= $jumlah_beli ?> pcs
            </p>

            <p>
                <strong>Harga Satuan:</strong>
                Rp <?= number_format($price, 0, ',', '.') ?>
            </p>

            <p>
                <strong>Status Member:</strong>
                <?= $member ? 'Ya (Diskon 10%)' : 'Tidak (Diskon 2%)' ?>
            </p>

            <p>
                <strong>Total Sebelum Diskon:</strong>
                Rp <?= number_format($price * $jumlah_beli, 0, ',', '.') ?>
            </p>

            <p>
                <strong>Potongan Diskon:</strong>
                Rp <?= number_format(($price * $jumlah_beli) * $diskon, 0, ',', '.') ?>
            </p>

            <p>
                <strong>Total Bayar:</strong>
                Rp <?= number_format($pay, 0, ',', '.') ?>
            </p>

            <div class="simulation">
                <h4>Simulasi Harga Kelipatan 1–5</h4>

                <ul> // loops kelipatan harga untuk 1 sampai 5
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <li>
                            Beli <?= $i ?> pcs:
                            Rp <?= number_format($price * $i, 0, ',', '.') ?>
                        </li>
                    <?php endfor; ?>
                </ul>

                <small>
                    Simulasi menggunakan harga sebelum diskon.
                </small>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
