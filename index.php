<?php
include "koneksimysql.php";

// Tambah data
if (isset($_POST['btnAdd'])) {
    $fotoName = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $fotoName = uniqid() . '_' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], 'img/' . $fotoName);
    } else {
        $fotoName = $_POST['foto'] ?? '';
    }
    $stmt = $conn->prepare("INSERT INTO tbl_product (kode, merk, kategori, satuan, hargabeli, diskonbeli, hargapokok, hargajual, diskonjual, stok, foto, deskripsi, view_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssddddddissi",
        $_POST['kode'],
        $_POST['merk'],
        $_POST['kategori'],
        $_POST['satuan'],
        $_POST['hargabeli'],
        $_POST['diskonbeli'],
        $_POST['hargapokok'],
        $_POST['hargajual'],
        $_POST['diskonjual'],
        $_POST['stok'],
        $fotoName,
        $_POST['deskripsi'],
        $_POST['view_count']
    );
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
}

// Update data
if (isset($_POST['btnUpdate'])) {
    $fotoName = $_POST['foto_lama'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $fotoName = uniqid() . '_' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], 'img/' . $fotoName);
    }
    $stmt = $conn->prepare("UPDATE tbl_product SET merk=?, kategori=?, satuan=?, hargabeli=?, diskonbeli=?, hargapokok=?, hargajual=?, diskonjual=?, stok=?, foto=?, deskripsi=?, view_count=? WHERE kode=?");
    $stmt->bind_param(
        "sssddddddssis",
        $_POST['merk'],
        $_POST['kategori'],
        $_POST['satuan'],
        $_POST['hargabeli'],
        $_POST['diskonbeli'],
        $_POST['hargapokok'],
        $_POST['hargajual'],
        $_POST['diskonjual'],
        $_POST['stok'],
        $fotoName,
        $_POST['deskripsi'],
        $_POST['view_count'],
        $_POST['kode']
    );
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
}

// Hapus data
if (isset($_GET['delete'])) {
    $kode = $_GET['delete'];
    $conn->query("DELETE FROM tbl_product WHERE kode='$kode'");
    header("Location: index.php");
}

// Ambil data produk
$result = $conn->query("SELECT * FROM tbl_product");
?>

<!DOCTYPE html>
<html>

<head>
    <title>CRUD Produk</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h2>Data Produk</h2>
        <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalAdd">Tambah Produk</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Merk</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Harga Beli</th>
                    <th>Diskon Beli</th>
                    <th>Harga Pokok</th>
                    <th>Harga Jual</th>
                    <th>Diskon Jual</th>
                    <th>Stok</th>
                    <th>Foto</th>
                    <th>Deskripsi</th>
                    <th>View Count</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['kode'] ?></td>
                        <td><?= $row['merk'] ?></td>
                        <td><?= $row['kategori'] ?></td>
                        <td><?= $row['satuan'] ?></td>
                        <td><?= $row['hargabeli'] ?></td>
                        <td><?= $row['diskonbeli'] ?></td>
                        <td><?= $row['hargapokok'] ?></td>
                        <td><?= $row['hargajual'] ?></td>
                        <td><?= $row['diskonjual'] ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td>
                            <?php if ($row['foto']): ?>
                                <img src="img/<?= $row['foto'] ?>" width="60" alt="foto">
                            <?php endif; ?>
                            <div><?= $row['foto'] ?></div>
                        </td>
                        <td><?= $row['deskripsi'] ?></td>
                        <td><?= $row['view_count'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#modalEdit<?= $row['kode'] ?>">Edit</button>
                            <a href="?delete=<?= $row['kode'] ?>" class="btn btn-danger btn-sm"
                                onclick="return confirm('Hapus data?')">Hapus</a>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit<?= $row['kode'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="post" enctype="multipart/form-data">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Edit Produk</h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="kode" value="<?= $row['kode'] ?>">
                                        <input type="hidden" name="foto_lama" value="<?= $row['foto'] ?>">
                                        <div class="form-group"><label>Merk</label><input type="text" name="merk"
                                                class="form-control" value="<?= $row['merk'] ?>"></div>
                                        <div class="form-group"><label>Kategori</label><input type="text" name="kategori"
                                                class="form-control" value="<?= $row['kategori'] ?>"></div>
                                        <div class="form-group"><label>Satuan</label><input type="text" name="satuan"
                                                class="form-control" value="<?= $row['satuan'] ?>"></div>
                                        <div class="form-group"><label>Harga Beli</label><input type="number" step="any"
                                                name="hargabeli" class="form-control" value="<?= $row['hargabeli'] ?>">
                                        </div>
                                        <div class="form-group"><label>Diskon Beli</label><input type="number" step="any"
                                                name="diskonbeli" class="form-control" value="<?= $row['diskonbeli'] ?>">
                                        </div>
                                        <div class="form-group"><label>Harga Pokok</label><input type="number" step="any"
                                                name="hargapokok" class="form-control" value="<?= $row['hargapokok'] ?>">
                                        </div>
                                        <div class="form-group"><label>Harga Jual</label><input type="number" step="any"
                                                name="hargajual" class="form-control" value="<?= $row['hargajual'] ?>">
                                        </div>
                                        <div class="form-group"><label>Diskon Jual</label><input type="number" step="any"
                                                name="diskonjual" class="form-control" value="<?= $row['diskonjual'] ?>">
                                        </div>
                                        <div class="form-group"><label>Stok</label><input type="number" name="stok"
                                                class="form-control" value="<?= $row['stok'] ?>"></div>
                                        <div class="form-group">
                                            <label>Foto</label>
                                            <input type="file" name="foto" class="form-control-file">
                                            <?php if ($row['foto']): ?>
                                                <div class="mt-2"><img src="img/<?= $row['foto'] ?>" width="80"></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi"
                                                class="form-control"><?= $row['deskripsi'] ?></textarea></div>
                                        <div class="form-group"><label>View Count</label><input type="number"
                                                name="view_count" class="form-control" value="<?= $row['view_count'] ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="btnUpdate" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Add -->
    <div class="modal fade" id="modalAdd" tabindex="-1">
        <div class="modal-dialog">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Tambah Produk</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label>Kode</label><input type="text" name="kode" class="form-control"
                                required></div>
                        <div class="form-group"><label>Merk</label><input type="text" name="merk" class="form-control">
                        </div>
                        <div class="form-group"><label>Kategori</label><input type="text" name="kategori"
                                class="form-control"></div>
                        <div class="form-group"><label>Satuan</label><input type="text" name="satuan"
                                class="form-control"></div>
                        <div class="form-group"><label>Harga Beli</label><input type="number" step="any"
                                name="hargabeli" class="form-control"></div>
                        <div class="form-group"><label>Diskon Beli</label><input type="number" step="any"
                                name="diskonbeli" class="form-control"></div>
                        <div class="form-group"><label>Harga Pokok</label><input type="number" step="any"
                                name="hargapokok" class="form-control"></div>
                        <div class="form-group"><label>Harga Jual</label><input type="number" step="any"
                                name="hargajual" class="form-control"></div>
                        <div class="form-group"><label>Diskon Jual</label><input type="number" step="any"
                                name="diskonjual" class="form-control"></div>
                        <div class="form-group"><label>Stok</label><input type="number" name="stok"
                                class="form-control"></div>
                        <div class="form-group">
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control-file">
                        </div>
                        <div class="form-group"><label>Deskripsi</label><textarea name="deskripsi"
                                class="form-control"></textarea></div>
                        <div class="form-group"><label>View Count</label><input type="number" name="view_count"
                                class="form-control"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="btnAdd" class="btn btn-primary">Tambah</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>