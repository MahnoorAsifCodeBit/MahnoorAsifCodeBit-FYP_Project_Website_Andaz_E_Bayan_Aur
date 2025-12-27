<?php
session_start();
$order_id = $_GET['id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Success | Andaz-e-Bayan Aur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: linear-gradient(135deg, #6c0d1e, #b23a48, #e86d82);
      font-family: 'Poppins', sans-serif;
      overflow: hidden;
    }

    /* Confetti Animation */
    .confetti {
      position: absolute;
      width: 10px;
      height: 10px;
      background-color: #fff;
      opacity: 0.8;
      animation: fall 5s infinite linear;
    }
    @keyframes fall {
      0% { transform: translateY(-10px) rotate(0deg); }
      100% { transform: translateY(110vh) rotate(360deg); }
    }

    .success-card {
      position: relative;
      z-index: 2;
      background: rgba(255,255,255,0.15);
      backdrop-filter: blur(20px);
      border-radius: 22px;
      padding: 50px;
      text-align: center;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 15px 40px rgba(0,0,0,0.25);
      animation: fadeInUp 1s ease;
      color: #fff;
    }

    .success-icon {
      width: 100px;
      height: 100px;
      margin: 0 auto 25px;
      border-radius: 50%;
      background: #28a745;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 50px;
      color: #fff;
      box-shadow: 0 0 20px rgba(40,167,69,0.6);
      animation: pop 0.6s ease forwards;
    }

    .success-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.2rem;
      margin-bottom: 15px;
    }

    .success-msg {
      font-size: 1.1rem;
      margin-bottom: 30px;
      color: #f8f8f8;
    }

    .btn-maroon {
      background-color: #fff;
      color: #6c0d1e;
      border-radius: 50px;
      padding: 12px 30px;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .btn-maroon:hover {
      background-color: #f1f1f1;
      color: #500a15;
      transform: translateY(-3px) scale(1.05);
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pop {
      from { transform: scale(0.6); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
  </style>
</head>
<body>
  <!-- Confetti elements -->
  <?php for($i=0; $i<20; $i++): ?>
    <div class="confetti" style="left:<?= rand(0,100); ?>%; animation-duration:<?= rand(3,6); ?>s; background-color: hsl(<?= rand(0,360); ?>,70%,60%);"></div>
  <?php endfor; ?>

  <div class="success-card">
    <div class="success-icon">✔</div>
    <h2 class="success-title">Order Placed Successfully!</h2>
    <p class="success-msg">
      Thank you for shopping with <strong>Andaz-e-Bayan Aur</strong>.<br>
      Your Order ID is <strong>#<?= $order_id; ?></strong>.
    </p>
    <a href="e-commerce.php" class="btn btn-maroon">Continue Shopping</a>
  </div>
</body>
</html>
