 <?php
    // Cek apakah form sudah disubmit oleh pengguna
    if (isset($_POST['hitung'])) {
        
        // 1. Variabel (4 tipe data) dari Form Input
        $nama_barang  = (string) $_POST['nama_barang'];
        $jumlah_beli  = (int) $_POST['jumlah_beli'];
        $price        = (float) $_POST['price'];
        $member       = isset($_POST['member']) ? true : false; // Boolean

        // 2. Function buatan sendiri
        function hitung_total($price, $jumlah_beli, $status_member) {
            $total = $price * $jumlah_beli;
            $diskon = 0;

            // 3. Percabangan
            if ($status_member == true) {
                $diskon = 0.10; // Diskon 10%
            } else {
                $diskon = 0.02; // Diskon 2%
            }

            return $total - ($total * $diskon);
        }

        // Eksekusi fungsi
        $pay = hitung_total($price, $jumlah_beli, $member);
        ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Diskon & Kasir Sederhana</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            padding: 30px;
            display: flex;
            justify-content: center;
        }
        .container {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 400px;
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
        input[type="text"], input[type="number"] {
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
    
    <!-- Form Input HTML -->
    <form action="" method="POST">
        <div class="form-group">
            <label>Nama Barang / Pelanggan:</label>
            <input type="text" name="nama_barang" required>
        </div>

        <div class="form-group">
            <label>Jumlah Beli:</label>
            <input type="number" name="jumlah_beli" min="1" required>
        </div>

        <div class="form-group">
            <label>Harga Satuan (Rp):</label>
            <input type="number" name="price" step="500" required>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" name="member" id="member" value="1">
            <label for="member">Punya Kartu Member?</label>
        </div>

        <button type="submit" name="hitung">Hitung Total</button>
    </form>

        <!-- Menampilkan Hasil -->
        <div class="result">
            <h3>Ringkasan Transaksi</h3>
            <p><strong>Nama/Barang:</strong> <?php echo htmlspecialchars($nama_barang); ?></p>
            <p><strong>Status Member:</strong> <?php echo $member ? "Ya (Diskon 10%)" : "Tidak (Diskon 2%)"; ?></p>
            <p><strong>Total Bayar:</strong> Rp <?php echo number_format($pay, 0, ',', '.'); ?></p>

            <div class="simulation">
                <h4>Simulasi Harga Kelipatan 1 - 5:</h4>
                <ul>
                    <?php
                    // 4. Perulangan (Loop)
                    for ($i = 1; $i <= 5; $i++) {
                        $simtotal = $price * $i;
                        echo "<li>Beli " . $i . " pcs: Rp " . number_format($simtotal, 0, ',', '.') . "</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

    <?php } ?>
</div>

</body>
</html>