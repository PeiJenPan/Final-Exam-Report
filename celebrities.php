<?php
$conn = new mysqli("localhost", "root", "1133377", "color");
$celebrities = $conn->query("SELECT * FROM celebrity_analysis ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>明星色彩分析參考</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .gallery-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05); transition: 0.3s; cursor: pointer; border: 1px solid #f3c5d8; }
        .gallery-card:hover { transform: translateY(-5px); }
        .gallery-card img { width: 100%; height: 250px; object-fit: cover; }
        .gallery-card h3 { padding: 15px; margin: 0; color: #b93f72; text-align: center; }
    </style>
</head>
<body>
<div class="page">
    <div class="container">
        <div class="badge">Celebrity Cases</div>
        <h1>明星分析參考庫</h1>
        <p style="text-align:center; color:#666; margin-bottom:40px;">查看不同明星的色彩與臉型分析，尋找你的風格靈感。</p>

        <div class="gallery-grid">
            <?php while($row = $celebrities->fetch_assoc()): ?>
            <?php
                // 將資料庫裡的 JSON 字串解碼成陣列
                $images = json_decode($row['image_path'], true);
                // 判斷是否為陣列，如果是就抓第一張圖當封面，否則就直接用原本的路徑
                $cover = is_array($images) ? $images[0] : $row['image_path'];
            ?>
            <div class="gallery-card" onclick="location.href='view_celebrity.php?id=<?php echo $row['id']; ?>'">
                <img src="<?php echo htmlspecialchars($cover); ?>">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            </div>
            <?php endwhile; ?>
        </div>
        
        <div style="text-align:center;"><a href="index.php" class="back">返回首頁</a></div>
    </div>
</div>
</body>
</html>