<?php
session_start();
require 'db_connect.php';

$login_error = ""; // ใช้เก็บข้อความแจ้งเตือน

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            header("Location: upload.php");
            exit();
        } else {
            $login_error = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $login_error = "ไม่พบชื่อผู้ใช้นี้";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color:rgb(56, 81, 242);
        }
        .btn-custom {
            background-color:rgb(7, 225, 249);
            border: none;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background-color:rgb(19, 231, 246);
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>เข้าสู่ระบบ</h2>

    <?php if ($login_error): ?>
        <div class="alert alert-danger"><?php echo $login_error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="ชื่อผู้ใช้" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
        </div>
        <button type="submit" class="btn btn-custom w-100">เข้าสู่ระบบ</button>
    </form>

    <!-- ปุ่มสมัครสมาชิก -->
    <div class="mt-2">
        <a href="register.php" class="btn btn-outline-primary w-100">สมัครสมาชิก</a>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
