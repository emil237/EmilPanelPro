<?php
// check_mac.php - ملف التحقق من MAC address من جهة الخادم
$allowed_macs_file = 'mac.txt';
$device_mac = isset($_GET['mac']) ? strtoupper(trim($_GET['mac'])) : '';

if (empty($device_mac)) {
    echo "0|NO_MAC";
    exit;
}

// التحقق من صحة تنسيق MAC address
if (!preg_match('/^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$/i', $device_mac)) {
    echo "0|INVALID_MAC";
    exit;
}

// قراءة قائمة الـ MACs المسموحة
if (!file_exists($allowed_macs_file)) {
    echo "0|NO_CONFIG";
    exit;
}

$macs = file($allowed_macs_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$authorized = false;

foreach ($macs as $allowed_mac) {
    $allowed_mac = strtoupper(trim($allowed_mac));
    // تجاهل التعليقات
    if (strpos($allowed_mac, '#') === 0) continue;
    if ($allowed_mac === $device_mac) {
        $authorized = true;
        break;
    }
}

if ($authorized) {
    echo "1|AUTHORIZED";
} else {
    echo "0|UNAUTHORIZED";
}
?>
