<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
require 'db.php';

$type = $_GET['type'] ?? '';
$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($type == 'user') {
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id=$id";
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username='$username', email='$email', password='$password' WHERE id=$id";
        }
        $conn->query($sql);
    } elseif ($type == 'booking') {
        $patient_name = $conn->real_escape_string($_POST['patient_name']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $service_name = $conn->real_escape_string($_POST['service_name']);
        $booking_time = $conn->real_escape_string($_POST['booking_time']);
        $price = floatval($_POST['price']);
        
        $sql = "UPDATE bookings SET patient_name='$patient_name', phone='$phone', service_name='$service_name', booking_time='$booking_time', price='$price' WHERE id=$id";
        $conn->query($sql);
    } elseif ($type == 'clinic') {
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        $price = floatval($_POST['price']);
        $badge = $conn->real_escape_string($_POST['badge']);
        
        // جلب الصورة القديمة كافتراضية
        $image_path = $_POST['existing_image'];
        
        // معالجة رفع الصورة الجديدة إذا اختارها المستخدم
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "uploads/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_name = time() . '_' . basename($_FILES["image"]["name"]);
            $target_file = $target_dir . $file_name;
            
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = 'uploads/' . $file_name;
            }
        }
        
        $sql = "UPDATE clinics SET name='$name', description='$description', price='$price', image='$image_path', badge='$badge' WHERE id=$id";
        $conn->query($sql);
    }
    header("Location: dashboard.php");
    exit();
}

$data = [];
if ($type == 'user') {
    $res = $conn->query("SELECT * FROM users WHERE id=$id");
    $data = $res->fetch_assoc();
} elseif ($type == 'booking') {
    $res = $conn->query("SELECT * FROM bookings WHERE id=$id");
    $data = $res->fetch_assoc();
} elseif ($type == 'clinic') {
    $res = $conn->query("SELECT * FROM clinics WHERE id=$id");
    $data = $res->fetch_assoc();
}

if (!$data) {
    die("Data not found");
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل البيانات</title>
    <style>
        body { font-family: 'Tajawal', sans-serif; background: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 450px; }
        .form-container h2 { text-align: center; color: #0984e3; margin-top: 0; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .input-group input, .input-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-family: inherit; }
        .btn { width: 100%; padding: 10px; background: #f39c12; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #d35400; }
        .btn-cancel { background: #e74c3c; margin-top: 10px; text-align: center; display: block; text-decoration: none; padding: 10px; color: white; border-radius: 5px; font-size: 16px; font-weight: bold; }
        .btn-cancel:hover { background: #c0392b; }
        .current-img { display: block; max-width: 100px; margin-top: 5px; border-radius: 5px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>تعديل <?php 
            if ($type == 'user') echo 'مستخدم';
            elseif ($type == 'booking') echo 'حجز';
            elseif ($type == 'clinic') echo 'عيادة / خدمة';
        ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <?php if ($type == 'user') { ?>
                <div class="input-group"><label>الاسم</label><input type="text" name="username" value="<?php echo htmlspecialchars($data['username']); ?>" required></div>
                <div class="input-group"><label>البريد</label><input type="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>" required></div>
                <div class="input-group"><label>الرقم السري (اتركه فارغاً لعدم التغيير)</label><input type="password" name="password"></div>
            <?php } elseif ($type == 'booking') { ?>
                <div class="input-group"><label>اسم المريض</label><input type="text" name="patient_name" value="<?php echo htmlspecialchars($data['patient_name']); ?>" required></div>
                <div class="input-group"><label>الهاتف</label><input type="text" name="phone" value="<?php echo htmlspecialchars($data['phone']); ?>" required></div>
                <div class="input-group"><label>الخدمة</label><input type="text" name="service_name" value="<?php echo htmlspecialchars($data['service_name']); ?>" required></div>
                <div class="input-group"><label>الوقت</label><input type="text" name="booking_time" value="<?php echo htmlspecialchars($data['booking_time']); ?>" required></div>
                <div class="input-group"><label>السعر (ج.س)</label><input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($data['price']); ?>" required></div>
            <?php } elseif ($type == 'clinic') { ?>
                <div class="input-group"><label>اسم العيادة / الخدمة</label><input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" required></div>
                <div class="input-group"><label>الوصف</label><textarea name="description" rows="3" required><?php echo htmlspecialchars($data['description']); ?></textarea></div>
                <div class="input-group"><label>السعر (ج.س)</label><input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($data['price']); ?>" required></div>
                
                <!-- إرسال مسار الصورة الحالية لكي لا تُفقد إن لم يقم برفع صورة جديدة -->
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($data['image']); ?>">
                
                <div class="input-group">
                    <label>صورة العيادة الحالية</label>
                    <img class="current-img" src="<?php echo htmlspecialchars($data['image']); ?>" alt="img">
                </div>
                <div class="input-group">
                    <label>تغيير الصورة من الجهاز (اختياري)</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <div class="input-group"><label>الشارة المميزة</label><input type="text" name="badge" value="<?php echo htmlspecialchars($data['badge']); ?>"></div>
            <?php } ?>
            <button type="submit" class="btn">حفظ التعديلات</button>
            <a href="dashboard.php" class="btn-cancel">إلغاء</a>
        </form>
    </div>
</body>
</html>
