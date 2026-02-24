<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
$number = $user['number'];
$username = $user['username'];
$fullname = $user['name'];
$email = $user['email'];

$pathnames = "Loots";

// Load balance from .txt
$balanceFile = "users/{$number}/{$number}.txt";
$balance = file_exists($balanceFile) ? trim(file_get_contents($balanceFile)) : "0";

// Load last 5 transactions
$transactions = [];
$txnFile = "users/{$number}/transactions/{$number}.json";
if (file_exists($txnFile)) {
    $data = json_decode(file_get_contents($txnFile), true);
    $transactions = array_reverse(array_slice($data, -5)); // Last 5
}

$filePath = 'Admin/data.json';

if (!file_exists($filePath)) {

    die("❌ Admin/data.json not found.");
}

$datav = json_decode(file_get_contents($filePath), true);


$userPath = "users/$number/$number.json";
$chatIdMissing = false;

if (file_exists($userPath)) {
    $userData = json_decode(file_get_contents($userPath), true);
    if (empty($userData['chat_id'])) {
        $chatIdMissing = true;
    }
}

$userFilee = "users/$number/$number.json";
$adminnData = "adminrole/Admin/Admin.json";

if (file_exists($userFilee)) {
    $dataa = json_decode(file_get_contents($userFilee), true);
} else {
    $dataa = [];
}

if (file_exists($adminnData)) {
    $adminn = json_decode(file_get_contents($adminnData), true);
} else {
    $adminn = [];
}

if (!empty($dataa['banned'])) {
    echo '
    <style>
    body {
        overflow: hidden !important;
    }
    .alertOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255, 0, 0, 0.1);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .alertMessage {
        background: #fee2e2;
        color: #991b1b;
        padding: 30px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        text-align: center;
    }
  @import url("https://fonts.googleapis.com/icon?family=Material+Icons") screen and (max-width: 599px);

    .cssbuttons-io-button {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 12px;
      color: white;
      text-decoration: none;
      cursor: pointer;
      transition: transform 0.3s ease, box-shadow 0.3s ease, filter 0.3s ease;
    }

    .cssbuttons-io-button:hover {
      transform: scale(1.05);
      filter: brightness(1.1);
    }

    .cssbuttons-io-button.blue {
      background: linear-gradient(0deg, #1e88e5 0%, #42a5f5 100%);
      box-shadow: 0 0.7em 1.5em -0.5em #1e88e598;
    }

    /*.cssbuttons-io-button.blue:hover {
      box-shadow: 0 0 18px #1e88e5, 0 0 24px #42a5f5;
    }*/

    .material-icons {
      font-size: 20px;
      vertical-align: middle;
    }


    </style>
    <div class="alertOverlay">
        <div class="alertMessage">
        
    <span class="material-icons">block</span>
    <span>You are currently banned.</span>
    <br><br>
    <span>Please contact support.</span>
    
    <br><br><br>
    
    <!-- Telegram Contact Button with href -->
  <a onclick="support()" class="cssbuttons-io-button blue">
    <span class="material-icons">sms</span>
    <span>Contact via Telegram</span>
  </a>

</div>
</div>';
}

if (!empty($adminn['maintenance'])) {
    echo '
    <style>
    body {
        overflow: hidden !important;
    }
    .alertOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(255, 255, 0, 0.1);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .alertMessage {
        background: #fef9c3;
        color: #92400e;
        padding: 30px;
        font-size: 18px;
        font-weight: bold;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        text-align: center;
    }
        .material-icons {
      font-size: 20px;
      vertical-align: middle;
    }
    </style>
    <div class="alertOverlay">
        <div class="alertMessage">
        
    <span class="material-icons">warning</span>
    <span>Site is under maintenance.</span>
    <br><br>
    <span class="material-icons">arrow_back</span>
    <span>Please check back later.</span>
    
        </div>
    </div>';
}

$txFile1 = "users/$number/TXN/add/$number.json";
$txFile2 = "users/$number/TXN/cashout/$number.json";

$transactions1 = file_exists($txFile1) ? json_decode(file_get_contents($txFile1), true) : [];
$transactions2 = file_exists($txFile2) ? json_decode(file_get_contents($txFile2), true) : [];

$transactions = array_merge(
    array_map(fn($t) => $t + ['source' => 'add'], $transactions1),
    array_map(fn($t) => $t + ['source' => 'withdraw'], $transactions2)
);

$transactions = array_reverse($transactions);
?>

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// User session
$user = $_SESSION['user'];
$number = $user['number'];
$username = $user['username'];
$fullname = $user['name'];
$email = $user['email'];

// File paths
$userFile = "users/$number/$number.txt";
$userJsonFile = "users/$number/$number.json";
$filename = basename($_GET['product']);
$jsonPath = "Store/$filename/$filename.json";

if (!file_exists($jsonPath) || !file_exists($userFile)) {
    die("❌ Product or user not found.");
}

$data1 = json_decode(file_get_contents($jsonPath), true);
$folder = "Store/$filename";
$price = floatval($data1['price']);
$productName = $data1['title'] ?? $filename;
$productImg = $data1['image'];
// Get current balance
$currentBalance = floatval(file_get_contents($userFile));

if ($currentBalance < $price) {
    die("<p style='color:red;'>❌ Insufficient Balance. Your balance is ₹" . number_format($currentBalance, 2) . "</p>");
}

// Deduct balance
$newBalance = $currentBalance - $price;
file_put_contents($userFile, number_format($newBalance, 2, '.', ''));

// Log plain transaction
$log = "[" . date("l / F / Y • h:i:s A") . "] $number bought '$filename' for ₹$price. Balance left: ₹$newBalance\n";
file_put_contents("users/$number/transactions.txt", $log, FILE_APPEND);

// Log JSON transaction
$txnDir = "users/$number/TXN/store";
$txnFile = "$txnDir/$number.json";
if (!is_dir($txnDir)) mkdir($txnDir, 0777, true);

$txn_id = strtoupper("TXN" . substr(md5(uniqid()), 0, 10));
$txnData = [
    'txn_id' => $txn_id,
    'product' => $productName,
    'amount' => $price,
    'type' => 'debit',
    "status" => "Success",
    "image" => "Store/$filename/$productImg",
    'time' => date("l / F / Y • h:i:s A"),
    'balance_after' => number_format($newBalance, 2, '.', '')
];
$txnList = file_exists($txnFile) ? json_decode(file_get_contents($txnFile), true) : [];
$txnList[] = $txnData;
file_put_contents($txnFile, json_encode($txnList, JSON_PRETTY_PRINT));

// Admin config
$adminPath = "adminrole/Admin/Admin.json";
if (file_exists($adminPath)) {
    $adminData = json_decode(file_get_contents($adminPath), true);

    // PHPMailer - User Email Receipt
    if (!empty($adminData['smtp_host'])) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $adminData['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $adminData['smtp_user'];
            $mail->Password   = $adminData['smtp_pass'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $adminData['smtp_port'] ?? 587;
            


            $mail->setFrom($adminData['smtp_user'], 'PCL ~ Store Receipt');
            $mail->addAddress($email, $fullname);
            $mail->addAddress($adminData['admin_email']); // Admin email alert too

            $mail->isHTML(true);
            $mail->Subject = "Purchase Receipt - $productName";

            $bodyHtml = '
            <div style="font-family:sans-serif; padding:20px; background:#f4f4f4;">
              <div style="max-width:600px; background:#fff; padding:20px; border-radius:8px; margin:auto; box-shadow:0 0 10px rgba(0,0,0,0.1);">
                <h2 style="color:green;">✅ Payment Successful</h2>
                <p><strong>Name:</strong> ' . $fullname . '</p>
                <p><strong>Username:</strong> ' . $username . '</p>
                <p><strong>Mobile:</strong> ' . $number . '</p>
                <p><strong>Product:</strong> ' . htmlspecialchars($productName) . '</p>
                <p><strong>Amount:</strong> ₹' . number_format($price, 2) . '</p>
                <p><strong>Txn ID:</strong> ' . $txn_id . '</p>
                <p><strong>Remaining Balance:</strong> ₹' . number_format($newBalance, 2) . '</p>
                <p style="margin-top:20px;">🗓 ' . date("l / F / Y • h:i:s A") . '</p>
                <hr>
                <p style="font-size:13px; color:#555;">Thank you for shopping with us!<br> - Pavan Cash Loot</p>
              </div>
            </div>';

            $mail->Body = $bodyHtml;
            $mail->send();
        } catch (Exception $e) {
            // Optionally log error
        }
    }
    
    $timee = date("l / F / Y • h:i:s A"); // Monday / July / 2025 • 07:00:46 PM

    // Telegram Alert - Admin & User
    $message = "🧾 *New Purchase \n\n👤 $fullname ($number)\n🛍️ Product: $productName\n💸 Amount: ₹$price\n⏳ Time: $timee\n🆔 Txn: `$txn_id`\n💰 Balance: ₹*" . number_format($newBalance, 2);
    $tg_token = $adminData['bot_token'];
    $admin_chat = $adminData['admin_chat_id'];

    if (!empty($tg_token) && !empty($admin_chat)) {
        $urlAdmin = "https://api.telegram.org/bot$tg_token/sendMessage?chat_id=$admin_chat&text=" . urlencode($message) . "&parse_mode=Markdown";
        file_get_contents($urlAdmin);
    }

    if (file_exists($userJsonFile)) {
        $userData = json_decode(file_get_contents($userJsonFile), true);
        if (!empty($userData['chat_id'])) {
            $userChatId = $userData['chat_id'];
            $urlUser = "https://api.telegram.org/bot$tg_token/sendMessage?chat_id=$userChatId&text=" . urlencode($message) . "&parse_mode=Markdown";
            file_get_contents($urlUser);
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">	
	<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  
  <!-- Title & SEO -->
  
  <title>Pavan Cash Loot App</title>
  <meta name="description" content="Pavan Cash Loot (PCL) is India's #1 Lifafa and Cash Earning Platform. Play, Bet & Earn Real Money with Dice, Scratch, Refer & Earn." />
  
  <!-- Canonical URL -->
  <link rel="canonical" href="https://pavancashloot.xyz/Loots/" />

  <!-- Robots Control -->
  <meta name="robots" content="index, follow" />
  <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />

  <!-- Keywords -->
  <meta name="keywords" content="Pavan Cash Loot, Lifafa, Scratch Cards, Dice Games, Real Cash Games, Earn Money Online India" />

  <!-- Author -->
  <meta name="author" content="Pavan Cash Loot Team" />
  <!-- Mobile Web App Capable -->
  <meta name="mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="default" />
  <meta name="apple-mobile-web-app-title" content="Pavan Cash Loot" />
  <!-- Prevent Clickjacking -->
  <meta http-equiv="X-Frame-Options" content="DENY" />

   <!-- Content Security Policy (basic) -->
   <meta http-equiv="Content-Security-Policy" content="default-src 'self' https:; img-src 'self' data: https:; script-src 'self' https: 'unsafe-inline'; style-src 'self' https: 'unsafe-inline';" />
 
   <!-- Referrer Policy -->
   <meta name="referrer" content="no-referrer-when-downgrade" />

   <!-- HSTS Preload (optional if server supports HTTPS) -->
   <meta http-equiv="Strict-Transport-Security" content="max-age=31536000; includeSubDomains; preload" />
   <!-- LinkedIn -->
   <meta property="og:title" content="Pavan Cash Loot – India's Best Cash Earning Platform" />
   <meta property="og:description" content="Play Lifafa, Dice, Scratch & Earn Real Money. Trusted by thousands." />
   <meta property="og:image:alt" content="PCL Logo" />

   <!-- Pinterest -->
   <meta name="pinterest-rich-pin" content="true" />

    <!-- Reddit -->
    <meta name="reddit:title" content="Pavan Cash Loot – Real Cash Games" />
   <!-- App Icons -->
   <link rel="apple-touch-icon" sizes="180x180" href="https://pavancashloot.xyz/data/images/pcl.jpg">
    <link rel="icon" type="image/png" sizes="32x32" href="https://pavancashloot.xyz/data/images/pcl.jpg">
    <link rel="icon" type="image/png" sizes="16x16" href="https://pavancashloot.xyz/data/images/pcl.jpg">

    <!-- Splash Screens for iOS -->
   <link rel="apple-touch-startup-image" href="https://pavancashloot.xyz/data/images/pcl.jpg">
   <!-- Preload Important Assets -->
   <link rel="preload" href="data/css/dashboard.css" as="style" />
   <link rel="preload" href="data/css/body.css" as="style" />
   <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
   <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin />
  <meta name="theme-color" content="#3498db" />
  <link rel="manifest" href="/store/site.webmanifest" />
  <!-- Open Graph & Twitter -->
  <meta property="og:title" content="Pavan Cash Loot – Lifafa & Real Cash Games" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://pavancashloot.xyz/" />
  <meta property="og:image" content="https://pavancashloot.xyz/data/images/pcl.jpg" />
  <meta property="og:description" content="India's #1 Cash Earning Platform: Lifafa, Scratch Cards, Dice Games & more. Start earning now!" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Pavan Cash Loot – Bet & Earn Money in India" />
  <meta name="twitter:description" content="PCL offers real cash games like Lifafa, Dice & Scratch. Register now & start earning." />
  <meta name="twitter:image" content="https://pavancashloot.xyz/data/images/pcl.jpg" />
  
  <!-- Open Graph (Facebook, WhatsApp, Telegram, Instagram, ShareChat) -->
  <meta property="og:title" content="Pavan Cash Loot – Lifafa & Real Cash Games" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://pavancashloot.xyz/" />
  <meta property="og:image" content="https://pavancashloot.xyz/data/images/pcl.jpg" />
  <meta property="og:description" content="India's #1 Cash Earning Platform: Lifafa, Scratch Cards, Dice Games & more. Start earning now!" />
  <meta property="og:site_name" content="Pavan Cash Loot" />
  <meta property="og:locale" content="en_IN" />

  <!-- WhatsApp-specific (uses OG but good to add preview color/theme) -->
  <meta property="al:android:app_name" content="WhatsApp" />
  <meta property="al:ios:app_name" content="WhatsApp" />

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Pavan Cash Loot – Bet & Earn Money in India" />
  <meta name="twitter:description" content="PCL offers real cash games like Lifafa, Dice & Scratch. Register now & start earning." />
  <meta name="twitter:image" content="https://pavancashloot.xyz/data/images/pcl.jpg" />
  <meta name="twitter:site" content="@PavanCashLoot" />
  <meta name="twitter:creator" content="@PavanCashLoot" />

  <!-- Telegram -->
  <meta name="telegram:title" content="Pavan Cash Loot – Lifafa & Real Cash Games" />
  <meta name="telegram:description" content="Play Lifafa, Scratch & Dice. Earn real money instantly on India's #1 cash earning platform." />
  <meta name="telegram:image" content="https://pavancashloot.xyz/data/images/pcl.jpg" />

  <!-- Instagram / ShareChat (uses OG) -->
  <meta name="instagram:title" content="Pavan Cash Loot – Lifafa & Real Cash Games" />
  <meta name="sharechat:title" content="Pavan Cash Loot – Lifafa & Real Cash Games" />

  <!-- YouTube (still uses OG, extra not needed, but adding app links helps) -->
  <meta property="al:android:package" content="com.google.android.youtube" />
  <meta property="al:android:url" content="https://pavancashloot.xyz/" />
  <meta property="al:android:app_name" content="YouTube" />

  <!-- Favicon & PWA -->
  <link rel="icon" href="https://pavancashloot.xyz/data/images/pcl.jpg" type="image/png" />
  <link rel="apple-touch-icon" href="https://pavancashloot.xyz/data/images/pcl.jpg" />
  <link rel="manifest" href="/site.webmanifest" />

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="data/css/dashboard.css" />
  <!--link rel="stylesheet" href="data/css/body.css" /-->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  	<!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <!-- Google Tag Manager -->
  <script>
    (function(w,d,s,l,i){w[l]=w[l]||[];
      w[l].push({'gtm.start': new Date().getTime(), event:'gtm.js'});
      var f=d.getElementsByTagName(s)[0],
          j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
      j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
      f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5NXKK4SP');
  </script>

  <!-- GA4 -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-85SWKJJDYW"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){ dataLayer.push(arguments); }
    gtag('js', new Date());
    gtag('config', 'G-85SWKJJDYW');
  </script>

  <!-- JSON-LD Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "Organization",
        "name": "Pavan Cash Loot",
        "url": "https://pavancashloot.xyz/Loots/",
        "logo": "https://pavancashloot.xyz/data/images/pcl.jpg",
        "sameAs": [
          "https://t.me/pavancashloot", 
          "https://twitter.com/pavancashloot", 
          "https://www.facebook.com/pavancashloot"
        ]
      },
      {
        "@type": "WebSite",
        "url": "https://pavancashloot.xyz/Loots/",
        "name": "Pavan Cash Loot",
        "publisher": {
          "@type": "Organization",
          "name": "Pavan Cash Loot",
          "logo": {
            "@type": "ImageObject",
            "url": "https://pavancashloot.xyz/data/images/pcl.jpg"
          }
        },
        "potentialAction": {
          "@type": "SearchAction",
          "target": "https://pavancashloot.xyz/Loots/search?q={search_term_string}",
          "query-input": "required name=search_term_string"
        }
      }
    ]
  }
  </script>
  <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "MobileApplication",
  "name": "Pavan Cash Loot",
  "operatingSystem": "Android, iOS, Web",
  "applicationCategory": "GameApplication",
  "url": "https://pavancashloot.xyz/Loots/",
  "logo": "https://pavancashloot.xyz/data/images/pcl.jpg",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "INR"
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.8",
    "ratingCount": "1200"
  }
}
</script>
<!-- JSON-LD Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      "name": "Pavan Cash Loot",
      "url": "https://pavancashloot.xyz/Loots/",
      "logo": "https://pavancashloot.xyz/data/images/pcl.jpg",
      "sameAs": [
        "https://t.me/pavancashloot",
        "https://twitter.com/pavancashloot",
        "https://www.facebook.com/pavancashloot"
      ]
    },
    {
      "@type": "WebSite",
      "url": "https://pavancashloot.xyz/Loots/",
      "name": "Pavan Cash Loot",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://pavancashloot.xyz/Loots/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    },
    {
      "@type": "MobileApplication",
      "name": "Pavan Cash Loot",
      "operatingSystem": "Android, iOS, Web",
      "applicationCategory": "GameApplication",
      "url": "https://pavancashloot.xyz/Loots/",
      "logo": "https://pavancashloot.xyz/data/images/pcl.jpg",
      "offers": { 
        "@type": "Offer", 
        "price": "0", 
        "priceCurrency": "INR" 
      },
      "aggregateRating": { 
        "@type": "AggregateRating", 
        "ratingValue": "4.8", 
        "ratingCount": "1200" 
      },
      "featureList": [
        "Login",
        "Register",
        "Wallet",
        "Refer & Earn",
        "Play Dice",
        "Scratch & Win"
      ]
    },
    {
      "@type": "WebApplication",
      "name": "Pavan Cash Loot",
      "url": "https://pavancashloot.xyz/Loots/",
      "applicationCategory": "GameApplication",
      "browserRequirements": "Requires JavaScript. Requires HTML5.",
      "operatingSystem": "All",
      "logo": "https://pavancashloot.xyz/data/images/pcl.jpg",
      "offers": { 
        "@type": "Offer", 
        "price": "0", 
        "priceCurrency": "INR" 
      },
      "featureList": [
        "Instant Play in Browser",
        "Wallet Access",
        "Refer & Earn",
        "Lifafa",
        "Dice Game",
        "Scratch & Win"
      ]
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://pavancashloot.xyz/Loots/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Wallet",
          "item": "https://pavancashloot.xyz/Loots/"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "Play Dice",
          "item": "https://pavancashloot.xyz/Loots/"
        }
      ]
    },
    {
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "How can I add money to my Pavan Cash Loot wallet?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "You can easily add funds using UPI, Wallet Transfer, or special offers available on the platform."
          }
        },
        {
          "@type": "Question",
          "name": "What is Lifafa in Pavan Cash Loot?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Lifafa is a digital gift packet that you can send or receive. It can contain bonus coins, wallet balance, or game credits."
          }
        },
        {
          "@type": "Question",
          "name": "How do I withdraw my earnings?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "You can request a withdrawal through UPI. Withdrawals are processed within 5 hours, subject to admin approval and minimum/maximum limits."
          }
        },
        {
          "@type": "Question",
          "name": "Is my account and wallet secure?",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, your account is protected with OTP verification and secure wallet transactions. Suspicious activities trigger instant Telegram alerts."
          }
        }
      ]
    },
    {
      "@type": "HowTo",
      "name": "How to Register & Start Using Pavan Cash Loot",
      "description": "Follow these simple steps to register, verify, and start earning with Pavan Cash Loot.",
      "step": [
        {
          "@type": "HowToStep",
          "name": "Open the Website",
          "text": "Go to https://pavancashloot.xyz/Loots/ in your browser."
        },
        {
          "@type": "HowToStep",
          "name": "Click on Register",
          "text": "Tap the Register button and enter your mobile number."
        },
        {
          "@type": "HowToStep",
          "name": "Verify with OTP",
          "text": "Enter the OTP sent to your mobile to complete verification."
        },
        {
          "@type": "HowToStep",
          "name": "Set up your Profile",
          "text": "Create your username, upload a profile picture, and set wallet PIN for security."
        },
        {
          "@type": "HowToStep",
          "name": "Start Playing & Earning",
          "text": "Access Wallet, Lifafa, Dice, and Scratch games to begin earning rewards instantly."
        }
      ]
    },
    {
      "@type": "Review",
      "itemReviewed": {
        "@type": "MobileApplication",
        "name": "Pavan Cash Loot",
        "operatingSystem": "Android, iOS, Web"
      },
      "author": {
        "@type": "Person",
        "name": "Rahul Kumar"
      },
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "5",
        "bestRating": "5"
      },
      "datePublished": "2025-08-15",
      "reviewBody": "Amazing app! The wallet system is super fast, and I love the Lifafa feature to send money to friends."
    },
    {
      "@type": "Review",
      "itemReviewed": {
        "@type": "WebApplication",
        "name": "Pavan Cash Loot"
      },
      "author": {
        "@type": "Person",
        "name": "Sneha Sharma"
      },
      "reviewRating": {
        "@type": "Rating",
        "ratingValue": "4.7",
        "bestRating": "5"
      },
      "datePublished": "2025-08-20",
      "reviewBody": "Easy to play in browser and withdrawal worked smoothly within hours."
    },
    {
      "@type": "Product",
      "name": "Pavan Cash Loot",
      "image": "https://pavancashloot.xyz/data/images/pcl.jpg",
      "description": "India's #1 Lifafa & Cash Earning Platform. Play, Bet & Earn Real Money with Dice, Scratch & Wallet features.",
      "sku": "PCL-APP-2025",
      "brand": {
        "@type": "Organization",
        "name": "Pavan Cash Loot"
      },
      "offers": {
        "@type": "Offer",
        "url": "https://pavancashloot.xyz/Loots/",
        "priceCurrency": "INR",
        "price": "0",
        "availability": "https://schema.org/InStock"
      },
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "1200"
      },
      "review": [
        {
          "@type": "Review",
          "author": { "@type": "Person", "name": "Rahul Kumar" },
          "reviewRating": { "@type": "Rating", "ratingValue": "5", "bestRating": "5" },
          "reviewBody": "Amazing app! The wallet system is super fast, and I love the Lifafa feature."
        },
        {
          "@type": "Review",
          "author": { "@type": "Person", "name": "Sneha Sharma" },
          "reviewRating": { "@type": "Rating", "ratingValue": "4.7", "bestRating": "5" },
          "reviewBody": "Easy to play in browser and withdrawal worked smoothly within hours."
        }
      ]
    }
  ]
}
</script>
  
  

  <!-- PWA Service Worker -->
  <script>
    if ("serviceWorker" in navigator) {
      window.addEventListener("load", () => {
        navigator.serviceWorker.register("sw.js")
        .then(reg => console.log("✅ Service Worker registered:", reg.scope))
        .catch(err => console.error("❌ Service Worker failed:", err));
      });
    }
  </script>
<style>div.clickEffect{position:fixed;box-sizing:border-box;border-style:solid;border-color: green blue red;border-radius:100%;animation:clickEffect .4s ease-out;a-index 99999}@keyframes clickEffect{0%{opacity:1;width:.1em;height:.1em;margin:-.25em;border-width:.5rem}100%{opacity:.2;width:15em;height:15em;margin:-7.5em;border-width:.03rem}}</style><script>function clickEffect(e){var d=document.createElement("div");d.className="clickEffect";d.style.top=e.clientY+"px";d.style.left=e.clientX+"px";document.body.appendChild(d);d.addEventListener('animationend',function(){d.parentElement.removeChild(d)}.bind(this))}document.addEventListener('click',clickEffect);</script>

    
    <!-- styles css -->
    <link rel="stylesheet" media="all" href="data/css/styles.css" />
    <link rel="stylesheet" media="all" href="data/css/body.css" />
    
    <link rel="stylesheet" media="all" href="data/css/dashboard.css" />
    </head>
<body>
    <!-- Google Tag Manager (noscript) -->
<noscript>
<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5NXKK4SP"
height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
    <!-- Header Code -->

<?php include 'include/header.php'; ?>

    <!-- Sidebar Code -->
<?php include 'include/sidebar.php'; ?>

<!-- css / js code -->
<?php include '/data/js/scripts.php'; ?>
<?php include '/data/css/styles.php'; ?>
<?php include '/data/js/dashboard.php'; ?>
<?php include '/data/js/body.php'; ?>
<!-- wrap code css -->
<div id="background-wrap">
    <div class="bubble x1"></div>
    <div class="bubble x2"></div>
    <div class="bubble x3"></div>
    <div class="bubble x4"></div>
    <div class="bubble x5"></div>
    <div class="bubble x6"></div>
    <div class="bubble x7"></div>
    <div class="bubble x8"></div>
    <div class="bubble x9"></div>
    <div class="bubble x10"></div>
</div>

	<!-- Main -->
<main>
    
<?php if ($chatIdMissing): ?>
  <div id="alertOverlay" class="alert-overlay">
    <div class="alert-box">
      <h2>Telegram ID Missing</h2>
      <p>Please set your Telegram Chat ID to receive updates and alerts.</p>
      <!-- Telegram Set Button -->
<div class="telegram-card">
<button onclick="window.location.href='https://t.me/AlertPCLBot?start=<?= $user['number'] ?>'">
    <span class="icon">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240">
        <path fill="#fff" d="M120 0C53.73 0 0 53.73 0 120s53.73 120 120 120 120-53.73 120-120S186.27 0 120 0zm52.14 82.45l-19.7 92.98c-1.48 6.67-5.41 8.33-10.95 5.19l-30.26-22.3-14.6 14.05c-1.61 1.61-2.96 2.96-6.04 2.96l2.16-30.55 55.62-50.16c2.42-2.15-.52-3.35-3.74-1.2l-68.7 43.19-29.61-9.26c-6.44-2.01-6.56-6.44 1.35-9.52l115.86-44.67c5.36-1.96 10.04 1.2 8.34 9.25z"/>
      </svg>
    </span>
    <span class="text">Set Telegram ID</span>
  </button>
</div>

<style>


  </style>
    </div>
  </div>

  <script>
    document.getElementById("alertOverlay").style.display = "flex";
  </script>
<?php endif; ?>
<style>
    .image {
        z-index: 2;
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background-image: url('<?= 
            isset($userData["photo"]) && file_exists($userData["photo"]) 
            ? $userData["photo"] 
            : "data/images/default.png" 
        ?>');
        background-size: cover;
        background-position: center;
        border: 2px solid #007bff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }


</style>
<style>
.custom-alert {
  padding: 12px 20px;
  margin: 15px auto;
  width: fit-content;
  border-radius: 8px;
  font-weight: bold;
  font-size: 15px;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
  display: inline-block;
  animation: fadeIn 0.4s ease-in-out;
}

.custom-alert.error {
  background-color: #ffe5e5;
  color: #b10000;
  border: 1px solid #ff7b7b;
}

.custom-alert.warning {
  background-color: #fff8e1;
  color: #856404;
  border: 1px solid #ffe58f;
}

@keyframes fadeIn {
  from {opacity: 0; transform: translateY(-10px);}
  to {opacity: 1; transform: translateY(0);}
}
</style>
<!-- Include this in your <head> -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid #fff;
  border-top: 2px solid #3498db;
  border-radius: 50%;
  display: inline-block;
  animation: spin 0.8s linear infinite;
  vertical-align: middle;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}


  .product {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.2s ease, box-shadow 0.3s ease;
  }

  .product:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  }

  .product img {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #e0e0e0;
  }

  .product-details {
    flex: 1;
  }

  .title2 {
    font-size: 20px;
    font-weight: bold;
    color: #1E90FF;
  }

  .desc {
    margin: 8px 0;
    font-size: 14px;
    color: #008080;
    font-weight: bold;
    
  }

  .price2 {
    color: #0f9d58;
    font-weight: 600;
    font-size: 16px;
    font-weight: bold;
    gap: 8px;
  }

  .btn {
    padding: 10px 18px;
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    transition: background 0.3s ease;
    display: inline-block;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
  }

  .btn:hover {
    background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
  }
  
/* Main card wrapper centered on screen */
.success-card {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 30px;
  background: linear-gradient(135deg, #f0f8ff, #e1f5fe);
  min-height: 100vh;
}

/* Glassmorphic box */
.success-box {
  background: rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(14px);
  border-radius: 20px;
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
  padding: 35px 40px;
  max-width: 450px;
  width: 100%;
  text-align: center;
  border: 1px solid rgba(255, 255, 255, 0.4);
  animation: fadeIn 0.8s ease;
}

/* Header */
.success-title {
  font-size: 30px;
  font-weight: bold;
  color: #2e7d32;
  margin-bottom: 25px;
}

/* Shared text style */
.success-text {
  font-size: 18px;
  margin: 10px 0;
  font-weight: 500;
}

/* Individual line coloring */
.success-text.paid {
  color: #00897b; /* teal */
}
.success-text.balance {
  color: #ef6c00; /* orange */
}
.success-text.item {
  color: #6a1b9a; /* purple */
}

/* Download button */
.success-btn {
  display: inline-block;
  margin-top: 25px;
  padding: 12px 28px;
  font-size: 16px;
  background: linear-gradient(to right, #43cea2, #185a9d);
  color: #fff;
  border-radius: 50px;
  text-decoration: none;
  font-weight: bold;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}

.success-btn:hover {
  transform: scale(1.05);
  background: linear-gradient(to right, #11998e, #0575e6);
}

/* Fade in animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

<!-- Lucide script -->
<script src="https://unpkg.com/lucide@latest"></script>

<div class="card">
  <h5 class="success-title">
    <i data-lucide="check-circle" class="icon"></i>
    <span>Payment Successful</span>
  </h5>

  <div class="line">
    <i data-lucide="wallet" class="icon"></i>
    <span>You paid: ₹<?= number_format($price, 2) ?></span>
  </div>

  <div class="line">
    <i data-lucide="banknote" class="icon"></i>
    <span>New Balance: ₹<?= number_format($newBalance, 2) ?></span>
  </div>

  <div class="line">
    <i data-lucide="shopping-bag" class="icon"></i>
    <span>You're buying: <?= htmlspecialchars($data1['title']) ?></span>
  </div>

  <!--a href="<?= "$folder/" . $data['zip'] ?>" class="success-btn" download>
    <i data-lucide="download"></i>
    <span>Download ZIP</span>
    <span class="spinner" style="display: none; margin-left: 5px;">
          </span>
          
  </a>
  
<a href="<?= "$folder/" . $data['zip'] ?>" 
   class="btn-submit downloadBtn" 
   data-href="<?= "$folder/" . $data['zip'] ?>">
  <i data-lucide="download" class="btn-icon" style="margin-right: 6px;"></i>
  <span class="btn-text">Download ZIP</span>
  <i data-lucide="loader" class="spinner" style="display: none; margin-left: 8px;"></i>
</a--><br><br>
  <a class="btn-submit downloadBtn" 
      data-href="https://PavanCashLoot.xyz/Loots/<?= htmlspecialchars($folder . '/' . $data1['zip']) ?>">
            <i class="fa-solid fa-download"></i>
    <span class="btn-text"> Download </span>
    <span class="spinner" style="display: none; margin-left: 8px;">

    </span>
  </a>
<!--a class="btn-submit downloadBtn" 
   href="download.php?product=<?= urlencode($filename) ?>" 
   style="text-decoration:none;">
    <i class="fa-solid fa-download"></i>
    <span class="btn-text">Download</span>
        <span class="spinner" style="display: none; margin-left: 8px;"></span>


</a-->
<br>
</div>

<script>
  lucide.createIcons();
</script>

<style>
.success-card {
  background: #fff;
  padding: 30px;
  border-radius: 16px;
  max-width: 480px;
  margin: 60px auto;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  font-family: 'Poppins', sans-serif;
}

.success-title {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  color: #27ae60;
  margin-bottom: 20px;
  gap: 10px;
}

.line {
  display: flex;
  align-items: center;
  background: #f5f6fa;
  padding: 12px 15px;
  border-radius: 12px;
  margin: 8px 0;
  gap: 10px;
  font-size: 15px;
  color: #2f3542;
  border: 1px solid #e0e0e0;
}

.success-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-top: 25px;
  background: #007bff;
  color: #fff;
  padding: 12px 25px;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 500;
  font-size: 15px;
  gap: 10px;
  transition: background 0.3s ease;
}

.success-btn:hover {
  background: #0056b3;
}

.icon {
  width: 20px;
  height: 20px;
  stroke-width: 1.8;
  color: #4b4b4b;
}
/*.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid #fff;
  border-top: 2px solid #3498db;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
  margin-left: 8px;
  vertical-align: middle;
  display: none;
}
@keyframes spin {
  to { transform: rotate(360deg); }
}*/
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  $('.downloadBtn').on('click', function(e) {
      e.preventDefault();

      const btn = $(this);
      const btnText = btn.find('.btn-text');
      const spinner = btn.find('.spinner');
      const targetURL = btn.data('href');

      // Disable the button
      btn.css('pointer-events', 'none').css('opacity', '0.6');
      btnText.text('Preparing...');
      spinner.show();

      // Redirect after 5 seconds
      setTimeout(function() {
          window.location.href = targetURL;
      }, 5000);
  });
});
</script>

</div>


</main>

<!-- footer code -->
<?php include 'include/footer.php'; ?>
	
	<!-- Scripts -->
<script src="data/js/scripts.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>lucide.createIcons();</script>

<script>
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("open");
}
</script>

<script>

  window.addEventListener("load", () => {

    const loader = document.getElementById("loaders-container-03");
    if (loader) {
      // Keep loader visible for 60 seconds
      setTimeout(() => {
        loader.style.opacity = "0";
        setTimeout(() => {
          loader.style.display = "none";
        }, 400); // Matches fade-out transition
      }, 4000); // 60,000ms = 1 minute
    }
  });
</script>

<!-- Lucide & SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include SweetAlert2 and Lucide -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/lucide@latest"></script>

 <script>
    function confirmLogout() {
      Swal.fire({
        title: '<b>Are you sure?</b>',
       // text: "<b>You will be logged out of your account.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<b>Logout</b>',
        cancelButtonText: '<b>Cancel</b>'
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect to logout page
          window.location.href = "logout.php";
        }
      });
    }
  </script>
  <script>
document.querySelectorAll('.magic-card').forEach(card => {
  card.addEventListener('mousemove', (e) => {
    const { width, height, left, top } = card.getBoundingClientRect();
    const x = e.clientX - left;
    const y = e.clientY - top;
    card.style.setProperty('--x', `${x}px`);
    card.style.setProperty('--y', `${y}px`);
  });
});
  </script>


<script>
function carddoocard() {
  const card = document.getElementById("doocard");
  const arrow = document.getElementById("arrowIcon");

  if (card.classList.contains("open")) {
    card.classList.remove("open");
    arrow.textContent = "keyboard_double_arrow_up"; // Arrow down
  } else {
    card.classList.add("open");
    arrow.textContent = "keyboard_double_arrow_down"; // Arrow up
  }
}
</script>

<!--script>
  setInterval(function () {
    const adContainer = document.querySelector(".adsbygoogle");
    if (adContainer) {
      adContainer.innerHTML = "";
      (adsbygoogle = window.adsbygoogle || []).push({});
    }
  }, 60000); // 60 seconds
</script>
 Google AdSense 
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"
     crossorigin="anonymous"></script>
<ins class="adsbygoogle"
     style="display:block; text-align:center;"
     data-ad-client="ca-pub-7181413470434375"
     data-ad-slot="1234567890"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script> -->
</body>
</html>