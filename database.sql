CREATE DATABASE IF NOT EXISTS clinic_db;
USE clinic_db;

-- 1. جدول المستخدمين لإدارة الموقع
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. جدول الحجوزات
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    service_name VARCHAR(100) NOT NULL,
    booking_time VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. جدول العيادات / الخدمات
CREATE TABLE IF NOT EXISTS clinics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    badge VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- إدخال الخدمات الافتراضية الثمانية للحفاظ على التصميم والمحتوى الأصلي
INSERT INTO clinics (name, description, price, image, badge) VALUES
('تجميل الأسنان', 'ابتسامة هوليوود، فينير، وتبييض احترافي باستخدام الليزر.', 15000.00, './دكتورة اسنان _ إتقان ورعاية_files/photo-1606811841689-23dfddce3e95', 'الأكثر طلباً'),
('زراعة الأسنان', 'تعويض الأسنان المفقودة بأحدث التقنيات الألمانية وبدون ألم.', 35000.00, './دكتورة اسنان _ إتقان ورعاية_files/photo-1598256989800-fe5f95da9787', NULL),
('تقويم الأسنان', 'تقويم معدني وشفاف لتعديل اصطفاف الأسنان للصغار والكبار.', 50000.00, './دكتورة اسنان _ إتقان ورعاية_files/photo-1588776814546-1ffcf47267a5', NULL),
('جراحة الفم', 'خلع ضرس العقل المطمور وعلاج أمراض اللثة المعقدة.', 60000.00, './دكتورة اسنان _ إتقان ورعاية_files/photo-1460672985063-6764ac8b9c74', NULL),
('علاج العصب', 'تنظيف قنوات الجذور وحشو العصب باستخدام أحدث أجهزة الروتاري.', 60000.00, './دكتورة اسنان _ إتقان ورعاية_files/photo-1629909613654-28e377c37b09', NULL),
('تركيبات الزيركون', 'تيجان وجسور ثابتة من مادة الزيركون والبورسلين عالية الجودة.', 50000.00, './دكتورة اسنان _ إتقان ورعاية_files/photo-1516062423079-7ca13cdc7f5a', NULL),
('طب أسنان الأطفال', 'رعاية خاصة للأطفال تشمل الوقاية، الفلورايد، وحشوات الأطفال.', 45000.00, './دكتورة اسنان _ إتقان ورعاية_files/photo-1551076805-e1869033e561', NULL),
('تنظيف وتلميع', 'إزالة الجير (Scaling) وتلميع الأسنان لحماية اللثة من الالتهابات.', 33000.00, './دكتورة اسنان _ إتقان ورعاية_files/photo-1579445710183-f9a816f5da05', NULL)
ON DUPLICATE KEY UPDATE id=id;
