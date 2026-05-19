<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
require 'db.php';

// Fetch users
$users_result = $conn->query("SELECT * FROM users ORDER BY id DESC");

// Fetch bookings
$bookings_result = $conn->query("SELECT * FROM bookings ORDER BY id DESC");

// Fetch clinics / services
$clinics_result = $conn->query("SELECT * FROM clinics ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم | العيادة</title>
    <link rel="stylesheet" href="دكتورة اسنان _ إتقان ورعاية_files/style.css">
    <style>
        body { font-family: 'Tajawal', sans-serif; background: #f4f6f9; margin: 0; padding: 20px; }
        .dashboard-container { max-width: 1100px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #0984e3; margin: 0; }
        .logout-btn { background: #d63031; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; }
        .logout-btn:hover { background: #b02a2a; }
        .section-title { color: #2d3436; margin-top: 40px; margin-bottom: 15px; border-right: 4px solid #0984e3; padding-right: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid #ddd; }
        th { background-color: #0984e3; color: white; }
        tr:hover { background-color: #f1f2f6; }
        .action-btn { padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; text-decoration: none; color: white; font-size: 14px; margin-left: 5px; }
        .edit-btn { background: #f39c12; }
        .delete-btn { background: #e74c3c; }
        .add-btn { display: inline-block; background: #2ecc71; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-bottom: 15px; }
        .thumbnail { width: 60px; height: 40px; object-fit: cover; border-radius: 4px; }
        .badge-style { display: inline-block; background: #e17055; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>أهلاً بك، <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <div>
                <a href="index.php" class="logout-btn" style="background:#0984e3; margin-left:10px;">زيارة الموقع</a>
                <a href="logout.php" class="logout-btn">تسجيل خروج</a>
            </div>
        </div>

        <!-- 1. إدارة العيادات والخدمات -->
        <h2 class="section-title">إدارة العيادات والخدمات</h2>
        <a href="insert.php?type=clinic" class="add-btn">إضافة عيادة/خدمة جديدة</a>
        <table>
            <thead>
                <tr>
                    <th>صورة</th>
                    <th>الاسم</th>
                    <th>الوصف</th>
                    <th>السعر</th>
                    <th>الشارة المميزة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $clinics_result->fetch_assoc()) { ?>
                <tr>
                    <td><img class="thumbnail" src="<?php echo htmlspecialchars($row['image']); ?>" alt="img"></td>
                    <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                    <td style="max-width: 300px;"><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo number_format($row['price']); ?> ج.س</td>
                    <td>
                        <?php if(!empty($row['badge'])) { ?>
                            <span class="badge-style"><?php echo htmlspecialchars($row['badge']); ?></span>
                        <?php } else { echo '-'; } ?>
                    </td>
                    <td>
                        <a href="edit.php?type=clinic&id=<?php echo $row['id']; ?>" class="action-btn edit-btn">تعديل</a>
                        <a href="delete.php?type=clinic&id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من حذف هذه العيادة؟');">حذف</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- 2. إدارة الحجوزات -->
        <h2 class="section-title">إدارة الحجوزات</h2>
        <a href="insert.php?type=booking" class="add-btn">إضافة حجز جديد</a>
        <table>
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>اسم المريض</th>
                    <th>الهاتف</th>
                    <th>الخدمة</th>
                    <th>الوقت</th>
                    <th>السعر</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bookings_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['booking_time']); ?></td>
                    <td><?php echo number_format($row['price']); ?> ج.س</td>
                    <td>
                        <a href="edit.php?type=booking&id=<?php echo $row['id']; ?>" class="action-btn edit-btn">تعديل</a>
                        <a href="delete.php?type=booking&id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- 3. إدارة المستخدمين -->
        <h2 class="section-title">إدارة المستخدمين</h2>
        <a href="insert.php?type=user" class="add-btn">إضافة مستخدم جديد</a>
        <table>
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <a href="edit.php?type=user&id=<?php echo $row['id']; ?>" class="action-btn edit-btn">تعديل</a>
                        <a href="delete.php?type=user&id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('هل أنت متأكد من الحذف؟');">حذف</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
