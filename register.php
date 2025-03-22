<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #ff758c, #ff7eb3);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .register-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .register-container h2 {
            margin-bottom: 20px;
            color: #ff4d6d;
        }
        .btn-custom {
            background-color: #ff4d6d;
            border: none;
            transition: 0.3s;
            color: white;
        }
        .btn-custom:hover {
            background-color: #ff3355;
        }
        .login-link {
            display: block;
            margin-top: 15px;
            color: #ff4d6d;
            text-decoration: none;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>สมัครสมาชิก</h2>

    <form method="POST">
        <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="ชื่อผู้ใช้" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
        </div>
        <button type="submit" class="btn btn-custom w-100">สมัครสมาชิก</button>
    </form>

    <!-- ลิงก์กลับไปหน้าเข้าสู่ระบบ -->
    <a href="login.php" class="login-link">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
