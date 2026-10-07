<?php
    // Menyiapkan variabel
    $nim = "";
    $nama = "";
    $fakultas = "";
    $prodi = "";
    $asal_sekolah = "";
    $semester = "";
    $hobi = "";

// Mengecek apakah form sudah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nim = $_POST["nim"];
    $nama = $_POST["nama"];
    $fakultas = $_POST["fakultas"];
    $prodi = $_POST["prodi"];
    $asal_sekolah = $_POST["asal_sekolah"];
    $semester = $_POST["semester"];
    $hobi = $_POST["hobi"];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .container {
            width: 600px;
            margin: auto;
            background-color: white;
            padding: 25px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #234f7d;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        input,
        select {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background-color: #234f7d;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #163652;
        }

        .hasil {
            margin-top: 30px;
            padding: 20px;
            background-color: #eef5fa;
            border-radius: 8px;
        }
    </style>

</head>

<body>

<div class="container">

    <h1>Input Data Mahasiswa</h1>

    <form method="POST">

        <label>NIM</label>
        <input type="text" name="nim" required>

        <label>Nama Mahasiswa</label>
        <input type="text" name="nama" required>

        <label>Fakultas</label>
        <input type="text" name="fakultas" required>

        <label>Program Studi</label>
        <input type="text" name="prodi" required>

        <label>Asal Sekolah</label>
        <input type="text" name="asal_sekolah" required>

        <label>Semester</label>
        <select name="semester" required>
            <option value="">-- Pilih Semester --</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
            <option value="3">Semester 3</option>
            <option value="4">Semester 4</option>
            <option value="5">Semester 5</option>
            <option value="6">Semester 6</option>
            <option value="7">Semester 7</option>
            <option value="8">Semester 8</option>
        </select>

        <label>Hobi</label>
        <input type="text" name="hobi">

        <button type="submit">Simpan Data</button>

    </form>


    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>

        <div class="hasil">

            <h2>Data Mahasiswa</h2>

            <p><strong>NIM:</strong>
                <?= htmlspecialchars($nim) ?>
            </p>

            <p><strong>Nama:</strong>
                <?= htmlspecialchars($nama) ?>
            </p>

            <p><strong>Fakultas:</strong>
                <?= htmlspecialchars($fakultas) ?>
            </p>

            <p><strong>Program Studi:</strong>
                <?= htmlspecialchars($prodi) ?>
            </p>

            <p><strong>Asal Sekolah:</strong>
                <?= htmlspecialchars($asal_sekolah) ?>
            </p>

            <p><strong>Semester:</strong>
                <?= htmlspecialchars($semester) ?>
            </p>

            <p><strong>Hobi:</strong>
                <?= htmlspecialchars($hobi) ?>
            </p>

        </div>

    <?php } ?>

</div>

</body>
</html>