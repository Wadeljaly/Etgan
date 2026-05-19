<?php
require 'db.php';
// جلب جميع العيادات / الخدمات من قاعدة البيانات
$clinics_result = $conn->query("SELECT * FROM clinics ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دكتورة اسنان | إتقان ورعاية</title>
    <link rel="stylesheet" href="./دكتورة اسنان _ إتقان ورعاية_files/style.css">
    <link href="./دكتورة اسنان _ إتقان ورعاية_files/css2" rel="stylesheet">
    <style>
        /* Top Navigation Header for Login / Dashboard link */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .logo {
            font-size: 1.3rem;
            font-weight: bold;
            color: #0984e3;
            text-decoration: none;
        }
        .nav-links a {
            color: #2d3436;
            text-decoration: none;
            margin-right: 15px;
            font-weight: bold;
            transition: 0.3s;
        }
        .nav-links a:hover {
            color: #0984e3;
        }
        .nav-links .btn-login {
            background-color: #0984e3;
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
        }
        .nav-links .btn-login:hover {
            background-color: #076ab5;
            color: white;
        }
        /* Custom adjustment to hero height to account for sticky bar */
        .hero {
            height: calc(100vh - 65px);
        }
    </style>
</head>
<body>

    <header class="top-bar">
        <a href="#" class="logo">إتقان ورعاية</a>
        <nav class="nav-links">
            <a href="#services-target">الخدمات</a>
            <a href="#booking">تواصل معنا</a>
            <a href="login.html" class="btn-login">لوحة التحكم / دخول</a>
        </nav>
    </header>

    <section class="hero">
        <h1 class="main-word show" id="word">إتقان</h1>
        <p class="sub-text reveal" id="subText">
            في عيادة الدكتورة، لا نصنع مجرد ابتسامة، بل نصيغ تفاصيل الجمال بكل <b>دقة</b>.
        </p>
        <a href="#services-target" class="cta-btn reveal" id="btn">استعرض الخدمات</a>
    </section>

    <section class="services" id="services-target">
        <h2 class="section-title">خدماتنا التخصصية</h2>
        <div class="services-grid">
            <?php 
            if ($clinics_result && $clinics_result->num_rows > 0) {
                while ($service = $clinics_result->fetch_assoc()) { 
            ?>
                <div class="service-card" onclick="selectService('<?php echo htmlspecialchars($service['name']); ?>', <?php echo $service['price']; ?>)">
                    <?php if (!empty($service['badge'])) { ?>
                        <div class="card-badge"><?php echo htmlspecialchars($service['badge']); ?></div>
                    <?php } ?>
                    <img src="<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['name']); ?>">
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($service['name']); ?></h3>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                        <span class="price-tag"><?php echo number_format($service['price']); ?> ج.س</span>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<p style='grid-column: 1/-1; text-align: center; color: #777;'>لا توجد خدمات متاحة حالياً.</p>";
            }
            ?>
        </div>
    </section>

    <section id="payment-section" class="payment-card" style="display: none; max-width: 600px; margin: 40px auto; padding: 20px;">
        <div class="payment-box">
            <h2 style="color: #0984e3; text-align: center;">تأكيد موعد العيادة</h2>
            <p id="selected-service-name" style="margin-bottom: 20px; font-weight: bold; text-align: center; font-size: 1.2rem;"></p>
            
            <div class="patient-form">
                <div class="input-group">
                    <label>الاسم الكامل للمريض</label>
                    <input type="text" id="patient-name" placeholder="أدخل اسمك لغرض الحجز">
                </div>
                <div class="input-group">
                    <label>رقم الهاتف</label>
                    <input type="tel" id="patient-phone" inputmode="numeric" placeholder="09xxxxxxx">
                </div>

                <div class="input-group">
                    <label>اختر الساعة المتاحة (مواعيد اليوم)</label>
                    <div class="time-slots" id="time-slots-container">
                        <div class="slot" onclick="selectTime(this, '04:00 م')">04:00 م</div>
                        <div class="slot" onclick="selectTime(this, '04:45 م')">04:45 م</div>
                        <div class="slot" onclick="selectTime(this, '05:30 م')">05:30 م</div>
                        <div class="slot" onclick="selectTime(this, '06:15 م')">06:15 م</div>
                        <div class="slot" onclick="selectTime(this, '07:00 م')">07:00 م</div>
                        <div class="slot" onclick="selectTime(this, '07:45 م')">07:45 م</div>
                    </div>
                    <input type="hidden" id="selected-time-value">
                </div>
            </div>

            <div class="total-price" style="font-size: 1.4rem;">المبلغ: <span id="service-price">0</span> ج.س</div>
            <button class="pay-btn visa" onclick="processPayment()">تأكيد الحجز النهائي</button>
        </div>
    </section>

    <section id="booking" class="contact-section" style="padding: 40px 20px; text-align: center; background-color: #fafafa;">
        <div class="contact-container" style="max-width: 600px; margin: 0 auto;">
            <h2>فرع الخرطوم - حي المطار</h2>
            <p style="font-size: 1.2rem; margin: 15px 0;">للتواصل المباشر: <a href="tel:0992734018" style="color:#0984e3; text-decoration:none; font-weight: bold;">0992734018</a></p>
            <div class="social-links" style="margin-top: 20px;">
                <a href="https://wa.me/249992734018" target="_blank" class="cta-btn" style="background-color: #25D366; text-decoration: none; padding: 10px 25px;">واتساب</a>
                <a href="https://instagram.com" target="_blank" class="cta-btn" style="background-color: #E1306C; text-decoration: none; padding: 10px 25px; margin-right: 10px;">انستقرام</a>
            </div>
        </div>
    </section>

    <script src="script.js"></script>

</body>
</html>
<?php $conn->close(); ?>
