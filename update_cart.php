<?php
session_start();
require_once "shop_db.php";
requireLoginForShop();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['remove'])) {
    $removeId = intval($_POST['remove']);
    unset($_SESSION['cart'][$removeId]);
    header("Location: cart.php");
    exit;
}

if (isset($_POST['qty']) && is_array($_POST['qty'])) {
    foreach ($_POST['qty'] as $pid => $qty) {
        $pid = intval($pid);
        $qty = intval($qty);
        if ($qty <= 0) {
            unset($_SESSION['cart'][$pid]);
        } else {
            $_SESSION['cart'][$pid] = $qty;
        }
    }
}

header("Location: cart.php");
exit;
?>
