<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI 色彩妝容與臉型分析</title>
    <link rel="stylesheet" href="style.css">

    <style>
        /* ===== 首頁右上角導覽列 ===== */
        .top-nav {
            text-align: right;
            margin-bottom: 15px;
            position: relative;
            z-index: 1000;
        }

        .top-nav a,
        .profile-btn {
            display: inline-block;
            margin-left: 10px;
            padding: 8px 16px;
            background: #fff0f6;
            color: #c94f7c;
            border-radius: 999px;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            border: 1px solid #f4c6d8;
            transition: 0.2s;
            cursor: pointer;
        }

        .top-nav a:hover,
        .profile-btn:hover {
            background: #ffe4ef;
            transform: translateY(-2px);
        }

        .profile-wrapper {
            display: inline-block;
            position: relative;
            margin-left: 10px;
            vertical-align: middle;
        }

        .profile-btn {
            width: 42px !important;
            height: 42px !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            border-radius: 50% !important;
            background: #ffffff !important;
            color: #333 !important;
            border: 1px solid #ddd !important;
            font-size: 20px !important;
            line-height: 42px !important;
            text-align: center !important;
            box-shadow: none !important;
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 55px;
            width: 270px;
            background: #ffffff;
            border-radius: 24px;
            padding: 22px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.14);
            border: 1px solid #f0d8e3;
            z-index: 9999;
            text-align: left;
        }

        .profile-dropdown.show {
            display: block;
        }

        .profile-name {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 6px;
        }

        .profile-account {
            font-size: 14px;
            color: #888;
            margin-bottom: 18px;
            padding-bottom: 18px;
            border-bottom: 1px solid #f3d6e2;
        }

        .profile-menu a {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 0;
            padding: 13px 8px;
            background: transparent;
            border: none;
            border-radius: 12px;
            color: #555;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.2s;
        }

        .profile-menu a:hover {
            background: #fff0f6;
            color: #c94f7c;
            transform: none;
        }

        .profile-icon {
            width: 26px;
            text-align: center;
            font-size: 20px;
        }

        .logout-link {
            color: #d93025 !important;
        }

        .logout-link:hover {
            background: #ffe8e8 !important;
            color: #d93025 !important;
        }

        @media(max-width:768px) {
            .top-nav {
                text-align: center;
            }

            .top-nav a {
                margin: 5px;
                font-size: 13px;
                padding: 8px 14px;
            }

            .profile-wrapper {
                margin-left: 5px;
            }

            .profile-dropdown {
                right: -25px;
                width: 250px;
            }
        }
    </style>
</head>
<body>

<div id="loading-screen">

    <div class="loading-box">

        <div class="loader"></div>

        <h2>AI 正在分析中...</h2>

        <p id="loading-text">
            正在分析你的臉型與五官比例
        </p>

        <div class="loading-bar">
            <div class="loading-progress"></div>
        </div>

    </div>

</div>

<div class="page">
    <div class="container">

        <div class="top-nav">

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>

                <a href="admin_dashboard.php">
                    🛠 管理員後台
                </a>

                <a href="logout.php">
                    🚪 登出
                </a>

            <?php elseif (isset($_SESSION['username'])): ?>

                <a href="shop.php">
                    🛍 商品頁
                </a>

                <a href="celebrities.php">
                    ⭐ 明星案例庫
                </a>

                <div class="profile-wrapper">

                    <button type="button" class="profile-btn" onclick="toggleProfileMenu()">
                        👤
                    </button>

                    <div id="profileDropdown" class="profile-dropdown">

                        <div class="profile-name">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </div>

                        <div class="profile-account">
                            會員帳號
                        </div>

                        <div class="profile-menu">

                            <a href="history.php">
                                <span class="profile-icon">✨</span>
                                <span>我的分析紀錄</span>
                            </a>

                            <a href="cart.php">
                                <span class="profile-icon">🛒</span>
                                <span>購物車</span>
                            </a>

                            <a href="my_orders.php">
                                <span class="profile-icon">🧾</span>
                                <span>我的訂單</span>
                            </a>

                            <a href="logout.php" class="logout-link">
                                <span class="profile-icon">↪</span>
                                <span>登出</span>
                            </a>

                        </div>

                    </div>

                </div>

            <?php else: ?>

                <a href="login.php">
                    🔑 登入
                </a>

                <a href="shop.php">
                    🛍 商品頁
                </a>

                <a href="celebrities.php">
                    ⭐ 明星案例庫
                </a>

            <?php endif; ?>

        </div>

        <div class="badge">
            AI Beauty Analysis
        </div>

        <h1>AI 色彩妝容與臉型分析</h1>

        <p class="subtitle">
            最多上傳 5 張照片，建議上傳 2～3 張。
            AI 會綜合分析你的臉型、五官特徵、膚色冷暖調，
            並提供妝容、色彩、髮色與穿搭建議。
        </p>

        <div class="feature-grid">

            <div class="feature-card">
                <span>😊</span>
                <h3>臉型分析</h3>
                <p>分析臉部比例、下顎線條與修容方向。</p>
            </div>

            <div class="feature-card">
                <span>🎨</span>
                <h3>色彩分析</h3>
                <p>分析膚色冷暖調、明暗度與適合色系。</p>
            </div>

            <div class="feature-card">
                <span>💄</span>
                <h3>妝容建議</h3>
                <p>推薦底妝、眼影、腮紅、唇彩與妝容風格。</p>
            </div>

        </div>

        <form id="analyze-form" action="analyze.php" method="POST" enctype="multipart/form-data">

            <div class="upload-box">

                <label for="photo">
                    上傳照片（最多 5 張，建議 2～3 張）
                </label>

                <p class="upload-desc">
                    建議包含正面照、45 度側臉、自然光照片，
                    讓 AI 更準確判斷臉型、五官與膚色。
                </p>

                <div class="tips-box">
                    <div class="tip-item">📸 正面照</div>
                    <div class="tip-item">🙂 45° 側臉</div>
                    <div class="tip-item">☀️ 自然光照片</div>
                </div>

                <input
                    type="file"
                    id="photo"
                    name="photos[]"
                    accept="image/jpeg,image/png,image/webp"
                    multiple
                    required
                >

                <div id="preview-container"></div>

                <button type="submit">
                    開始 AI 分析
                </button>

            </div>

        </form>

        <p class="notice">
            小提醒：照片越清楚、光線越自然，AI 分析結果通常會更穩定。
            避免使用濾鏡、美顏過重或臉部被遮擋的照片。
        </p>

    </div>
</div>

<script>

function toggleProfileMenu() {
    const menu = document.getElementById("profileDropdown");

    if (menu) {
        menu.classList.toggle("show");
    }
}

document.addEventListener("click", function(event) {
    const wrapper = document.querySelector(".profile-wrapper");

    if (wrapper && !wrapper.contains(event.target)) {
        const menu = document.getElementById("profileDropdown");

        if (menu) {
            menu.classList.remove("show");
        }
    }
});

const input = document.getElementById("photo");
const preview = document.getElementById("preview-container");

input.addEventListener("change", function () {

    preview.innerHTML = "";

    if (this.files.length > 5) {
        alert("最多只能上傳 5 張照片");
        this.value = "";
        return;
    }

    if (this.files.length === 1) {
        alert("可以只上傳 1 張，但建議上傳 2～3 張，AI 分析會更準確。");
    }

    let totalSize = 0;

    for (let i = 0; i < this.files.length; i++) {
        totalSize += this.files[i].size;
    }

    const maxSizeInBytes = 8 * 1024 * 1024;

    if (totalSize > maxSizeInBytes) {
        alert("照片總大小不能超過 8MB，請先壓縮照片或減少張數喔！");
        this.value = "";
        return;
    }

    Array.from(this.files).forEach(file => {

        const reader = new FileReader();

        reader.onload = function(e) {

            const img = document.createElement("img");

            img.src = e.target.result;

            img.classList.add("preview-image");

            preview.appendChild(img);
        };

        reader.readAsDataURL(file);
    });
});

const form = document.getElementById("analyze-form");

const loadingScreen = document.getElementById("loading-screen");

const loadingText = document.getElementById("loading-text");

const loadingMessages = [
    "正在分析你的臉型與五官比例",
    "正在判斷膚色冷暖調",
    "正在分析適合的妝容色彩",
    "正在推薦適合的髮色與穿搭",
    "AI 正在生成專屬分析報告"
];

let loadingIndex = 0;

setInterval(() => {

    loadingIndex++;

    if (loadingIndex >= loadingMessages.length) {
        loadingIndex = 0;
    }

    loadingText.innerText = loadingMessages[loadingIndex];

}, 2500);

form.addEventListener("submit", function(){

    loadingScreen.style.display = "flex";

});

</script>

</body>
</html>