<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
require 'db.php';

$type = $_GET['type'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($type == 'user') {
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        $conn->query($sql);
    } elseif ($type == 'booking') {
        $patient_name = $conn->real_escape_string($_POST['patient_name']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $service_name = $conn->real_escape_string($_POST['service_name']);
        $booking_time = $conn->real_escape_string($_POST['booking_time']);
        $price = floatval($_POST['price']);
        
        $sql = "INSERT INTO bookings (patient_name, phone, service_name, booking_time, price) VALUES ('$patient_name', '$phone', '$service_name', '$booking_time', '$price')";
        $conn->query($sql);
    } elseif ($type == 'clinic') {
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        $badge = $conn->real_escape_string($_POST['badge']);
        
        // معالجة رفع الصورة من الجهاز
        $image_path = './دكتورة اسنان _ إتقان ورعاية_files/photo-1606811841689-23dfddce3e95'; // الصورة الافتراضية
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../uploads/";
            // إنشاء المجلد إذا لم يكن موجوداً
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_name = time() . '_' . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $file_name;
            
            // نقل الصورة المرفوعة
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = 'uploads/' . $file_name;
            }
        }
        
        $sql = "INSERT INTO clinics (name, description, price, image, badge) VALUES ('$name', '$description', '$price', '$image_path', '$badge')";
        $conn->query($sql);
    }
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة جديد</title>
    <style>
        body { font-family: 'Tajawal', sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 450px; }
        .form-container h2 { text-align: center; color: #0984e3; margin-top: 0; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .input-group input, .input-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-family: inherit; }
        .btn { width: 100%; padding: 10px; background: #2ecc71; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #27ae60; }
        .btn-cancel { background: #e74c3c; margin-top: 10px; text-align: center; display: block; text-decoration: none; padding: 10px; color: white; border-radius: 5px; font-size: 16px; font-weight: bold; }
        .btn-cancel:hover { background: #c0392b; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>إضافة <?php 
            if ($type == 'user') echo 'مستخدم جديد';
            elseif ($type == 'booking') echo 'حجز جديد';
            elseif ($type == 'clinic') echo 'عيادة / خدمة جديدة';
        ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <?php if ($type == 'user') { ?>
                <div class="input-group"><label>اسم المستخدم</label><input type="text" name="username" required></div>
                <div class="input-group"><label>البريد الإلكتروني</label><input type="email" name="email" required></div>
                <div class="input-group"><label>الرقم السري</label><input type="password" name="password" required></div>
            <?php } elseif ($type == 'booking') { ?>
                <div class="input-group"><label>اسم المريض</label><input type="text" name="patient_name" required></div>
                <div class="input-group"><label>الهاتف</label><input type="text" name="phone" required></div>
                <div class="input-group"><label>الخدمة</label><input type="text" name="service_name" required></div>
                <div class="input-group"><label>الوقت</label><input type="text" name="booking_time" required></div>
                <div class="input-group"><label>السعر (ج.س)</label><input type="number" step="0.01" name="price" required></div>
            <?php } elseif ($type == 'clinic') { ?>
                <div class="input-group"><label>اسم العيادة / الخدمة</label><input type="text" name="name" required></div>
                <div class="input-group"><label>الوصف</label><textarea name="description" rows="3" required></textarea></div>
                <div class="input-group"><label>السعر (ج.س)</label><input type="number" step="0.01" name="price" required></div>
                <div class="input-group"><label>اختر صورة العيادة من جهازك</label><input type="file" name="image" accept="image/*" required></div>
                <div class="input-group"><label>الشارة المميزة (مثال: الأكثر طلباً - اتركه فارغاً لعدم التفعيل)</label><input type="text" name="badge"></div>
            <?php } ?>
            <button type="submit" class="btn">حفظ</button>
            <a href="dashboard.php" class="btn-cancel">إلغاء</a>
        </form>
    </div>
</body>
</html>
