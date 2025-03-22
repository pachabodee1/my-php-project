<?php
session_start();

$host = "localhost";
$user = "root";
$password = "12345678";
$dbname = "file_uploads";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];

// อัปโหลดไฟล์
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $filename = time() . '_' . basename($file['name']);
    $upload_dir = "uploads/";
    $target_file = $upload_dir . $filename;

    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO files (filename, filepath, description) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $filename, $target_file, $_POST['description']);
            $stmt->execute();
            header("Location: upload.php");
        }
    }
}

// ลบไฟล์
if (isset($_GET['delete'])) {
    $file_id = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT filepath FROM files WHERE id = ?");
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $stmt->bind_result($filepath);
    $stmt->fetch();
    $stmt->close();

    if ($filepath && file_exists($filepath)) {
        unlink($filepath);
    }

    $stmt = $conn->prepare("DELETE FROM files WHERE id = ?");
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    header("Location: upload.php");
}

// อัปเดตคำอธิบาย (API)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_description'])) {
    $file_id = intval($_POST['file_id']);
    $new_description = htmlspecialchars($_POST['new_description'], ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("UPDATE files SET description = ? WHERE id = ?");
    $stmt->bind_param("si", $new_description, $file_id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload รูปภาพ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, rgb(244, 141, 223), rgb(255, 187, 248));
            color: white;
        }
        .container-fluid {
            max-width: 1200px;
        }
        .preview-img {
            max-width: 100%;
            height: auto;
            display: none;
            margin-top: 10px;
            border-radius: 10px;
        }
        .card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .card img:hover {
            transform: scale(1.05);
        }
        .edit-box {
            display: none;
        }
    </style>
</head>
<body class="container-fluid mt-5">

<!-- Modal แสดงรูปภาพ -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalImage" src="" class="img-fluid rounded shadow-lg">
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".preview-image").forEach(img => {
        img.addEventListener("click", function () {
            document.getElementById("modalImage").src = this.getAttribute("data-img");
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



<h2 class="text-center">📸 อัปโหลดรูปภาพ</h2>
<p class="text-center" style="font-size: 32px;">ยินดีต้อนรับคุณ <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
<div class="text-end">
    <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
</div>

<form method="POST" enctype="multipart/form-data" class="mt-4">
    <div class="mb-3">
        <label class="form-label">เลือกไฟล์รูปภาพ</label>
        <input type="file" name="image" accept="image/*" required class="form-control">
        <img id="preview" class="preview-img">
    </div>
    <div class="mb-3">
        <input type="text" name="description" placeholder="คำอธิบาย (Optional)" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary w-100">📤 อัปโหลด</button>
</form>

<hr>

<h3 class="text-center mt-4">🖼️ รูปภาพทั้งหมด</h3>
<div class="row">
<?php
$result = $conn->query("SELECT * FROM files ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    echo "<div class='col-md-4 mb-4'>";
    echo "<div class='card'>";
    echo "<img src='" . htmlspecialchars($row['filepath']) . "' class='card-img-top preview-image' alt='File Image' data-bs-toggle='modal' data-bs-target='#imageModal' data-img='" . htmlspecialchars($row['filepath']) . "'>";
    echo "<div class='card-body text-dark'>";
    echo "<h5 class='card-title'>" . htmlspecialchars($row['filename']) . "</h5>";

    // แสดงคำอธิบายและช่องแก้ไข
    echo "<p id='desc-" . $row['id'] . "'>" . htmlspecialchars($row['description']) . "</p>";
    echo "<div class='edit-box' id='edit-" . $row['id'] . "'>";
    echo "<input type='text' class='form-control new-description' data-id='" . $row['id'] . "' value='" . htmlspecialchars($row['description']) . "'>";
    echo "<button class='btn btn-success btn-sm save-btn' data-id='" . $row['id'] . "'>💾 บันทึก</button>";
    echo "</div>";

    echo "<button class='btn btn-primary btn-sm edit-btn' data-id='" . $row['id'] . "'>✏️ แก้ไข</button> ";
    echo "<a href='?delete=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"คุณต้องการลบไฟล์นี้ใช่หรือไม่?\")'>🗑️ ลบ</a>";

    echo "</div>";
    echo "</div>";
    echo "</div>";
}
?>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            let id = this.getAttribute("data-id");
            document.getElementById("desc-" + id).style.display = "none";
            document.getElementById("edit-" + id).style.display = "block";
        });
    });

    document.querySelectorAll(".save-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            let id = this.getAttribute("data-id");
            let newDesc = document.querySelector(".new-description[data-id='" + id + "']").value;

            fetch("upload.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "update_description=1&file_id=" + id + "&new_description=" + encodeURIComponent(newDesc)
            })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    document.getElementById("desc-" + id).innerText = newDesc;
                    document.getElementById("desc-" + id).style.display = "block";
                    document.getElementById("edit-" + id).style.display = "none";
                }
            });
        });
    });
});
</script>

</body>
<?php
date_default_timezone_set("Asia/Bangkok"); // ตั้งค่าให้เป็นเวลาไทย
?>
<footer class="text-center mt-4">
    <p>© <?php echo date("Y"); ?> by [Pachabodee]</p>
</footer>
</html>
