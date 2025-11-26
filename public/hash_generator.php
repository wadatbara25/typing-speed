<?php
/*
|--------------------------------------------------------------------------
| 🔐 Footer Hash Generator
| هذا الملف لتوليد توقيع (Hash) جديد للفوتر بعد أي تعديل
| فقط افتحه في المتصفح واكتب النص الذي تريد حساب بصمته.
|--------------------------------------------------------------------------
*/
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مولد توقيع الفوتر — معهد الخوارزمي للتدريب</title>
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background: #f4f6f8;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        textarea {
            width: 90%;
            max-width: 600px;
            height: 150px;
            padding: 10px;
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid #bbb;
            resize: none;
        }
        button {
            margin-top: 15px;
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }
        button:hover {
            background: #1d4ed8;
        }
        .result {
            margin-top: 20px;
            font-size: 16px;
            background: #e0f2fe;
            padding: 10px 20px;
            border-radius: 8px;
            color: #0f172a;
        }
    </style>
</head>
<body>

<h2>🔑 توليد توقيع (Hash) جديد للفوتر</h2>

<form method="post">
    <textarea name="footer_text" placeholder="ألصق هنا النص الكامل للفوتر بدون أكواد HTML..." required><?php
        if (!empty($_POST['footer_text'])) echo htmlspecialchars($_POST['footer_text']);
    ?></textarea>
    <br>
    <button type="submit">توليد التوقيع</button>
</form>

<?php
if (!empty($_POST['footer_text'])) {
    $text = trim($_POST['footer_text']);
    $hash = substr(hash('sha256', $text), 0, 32);
    echo "<div class='result'>🔒 التوقيع الناتج:<br><strong>{$hash}</strong></div>";
}
?>

</body>
</html>
