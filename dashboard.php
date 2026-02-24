<?php
/*session_start();
date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}*/


session_start();
date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
/*/ Start secure session
session_start();
date_default_timezone_set('Asia/Kolkata');

// Check if user is logged in
if (!isset($_SESSION['user']) || empty($_SESSION['user']['number'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Optional: prevent session hijacking by locking to IP
if (!isset($_SESSION['ip'])) {
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
}
if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

// Store login time if not already stored
if (!isset($_SESSION['login_time'])) {
    $_SESSION['login_time'] = date("l, d M Y • h:i:s A");
}

// Store last activity time for auto-logout feature
$_SESSION['last_activity'] = time();

$user = $_SESSION['user']; // user data from session
$loginTime = $_SESSION['login_time'];


//If not logged in, redirect to login
if (!isset($_SESSION['user']) || empty($_SESSION['user']['number'])) {
    header('Location: login.php');
    exit;
}

// Optional: extra security check
if (isset($_SESSION['ip']) && $_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
} */



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
$storeDir = 'adminrole/Store/';
$products = [];

$entries = scandir($storeDir);
foreach ($entries as $entry) {
    if ($entry === '.' || $entry === '..') continue;

    $folderPath = $storeDir . $entry;
    $jsonFile = "$folderPath/$entry.json";

    if (is_dir($folderPath) && file_exists($jsonFile)) {
        $product = json_decode(file_get_contents($jsonFile), true);
        if ($product) {
            $product['ext'] = file_exists("$folderPath/$entry.ext") ? "$entry.ext" : '';
            $products[] = $product;
        }
    }
}
usort($products, fn($a, $b) => $b['id'] <=> $a['id']);
?>
	
<!DOCTYPE html>
<html lang="en">	
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  
  <!-- Title & SEO -->
  <title>Pavan Cash Loot App </title>
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
<button onclick="window.location.href='https://t.me/SmartxlifafaVerifier_bot?start=<?= $user['number'] ?>'">
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
/*body {
	background: #00b4ff;
	color: #333;
	font: 100% Lato, Arial, Sans Serif;
	height: 100vh;
	margin: 0;
	padding: 0;
	overflow-x: hidden;
	font-weight: bold;
}*/

#background-wrap {
    bottom: 0;
	left: 0;
	position: fixed;
	right: 0;
	top: 0;
	z-index: -1;
}

/* KEYFRAMES */

@-webkit-keyframes animateBubble {
    0% {
        margin-top: 1000px;
    }
    100% {
        margin-top: -100%;
    }
}

@-moz-keyframes animateBubble {
    0% {
        margin-top: 1000px;
    }
    100% {
        margin-top: -100%;
    }
}

@keyframes animateBubble {
    0% {
        margin-top: 1000px;
    }
    100% {
        margin-top: -100%;
    }
}

@-webkit-keyframes sideWays { 
    0% { 
        margin-left:0px;
    }
    100% { 
        margin-left:50px;
    }
}

@-moz-keyframes sideWays { 
    0% { 
        margin-left:0px;
    }
    100% { 
        margin-left:50px;
    }
}

@keyframes sideWays { 
    0% { 
        margin-left:0px;
    }
    100% { 
        margin-left:50px;
    }
}

/* ANIMATIONS */

.x1 {
    -webkit-animation: animateBubble 25s linear infinite, sideWays 2s ease-in-out infinite alternate;
	-moz-animation: animateBubble 25s linear infinite, sideWays 2s ease-in-out infinite alternate;
	animation: animateBubble 25s linear infinite, sideWays 2s ease-in-out infinite alternate;
	
	left: -5%;
	top: 5%;
	
	-webkit-transform: scale(0.6);
	-moz-transform: scale(0.6);
	transform: scale(0.6);
}

.x2 {
    -webkit-animation: animateBubble 20s linear infinite, sideWays 4s ease-in-out infinite alternate;
	-moz-animation: animateBubble 20s linear infinite, sideWays 4s ease-in-out infinite alternate;
	animation: animateBubble 20s linear infinite, sideWays 4s ease-in-out infinite alternate;
	
	left: 5%;
	top: 80%;
	
	-webkit-transform: scale(0.4);
	-moz-transform: scale(0.4);
	transform: scale(0.4);
}

.x3 {
    -webkit-animation: animateBubble 28s linear infinite, sideWays 2s ease-in-out infinite alternate;
	-moz-animation: animateBubble 28s linear infinite, sideWays 2s ease-in-out infinite alternate;
	animation: animateBubble 28s linear infinite, sideWays 2s ease-in-out infinite alternate;
	
	left: 10%;
	top: 40%;
	
	-webkit-transform: scale(0.7);
	-moz-transform: scale(0.7);
	transform: scale(0.7);
}

.x4 {
    -webkit-animation: animateBubble 22s linear infinite, sideWays 3s ease-in-out infinite alternate;
	-moz-animation: animateBubble 22s linear infinite, sideWays 3s ease-in-out infinite alternate;
	animation: animateBubble 22s linear infinite, sideWays 3s ease-in-out infinite alternate;
	
	left: 20%;
	top: 0;
	
	-webkit-transform: scale(0.3);
	-moz-transform: scale(0.3);
	transform: scale(0.3);
}

.x5 {
    -webkit-animation: animateBubble 29s linear infinite, sideWays 4s ease-in-out infinite alternate;
	-moz-animation: animateBubble 29s linear infinite, sideWays 4s ease-in-out infinite alternate;
	animation: animateBubble 29s linear infinite, sideWays 4s ease-in-out infinite alternate;
	
	left: 30%;
	top: 50%;
	
	-webkit-transform: scale(0.5);
	-moz-transform: scale(0.5);
	transform: scale(0.5);
}

.x6 {
    -webkit-animation: animateBubble 21s linear infinite, sideWays 2s ease-in-out infinite alternate;
	-moz-animation: animateBubble 21s linear infinite, sideWays 2s ease-in-out infinite alternate;
	animation: animateBubble 21s linear infinite, sideWays 2s ease-in-out infinite alternate;
	
	left: 50%;
	top: 0;
	
	-webkit-transform: scale(0.8);
	-moz-transform: scale(0.8);
	transform: scale(0.8);
}

.x7 {
    -webkit-animation: animateBubble 20s linear infinite, sideWays 2s ease-in-out infinite alternate;
	-moz-animation: animateBubble 20s linear infinite, sideWays 2s ease-in-out infinite alternate;
	animation: animateBubble 20s linear infinite, sideWays 2s ease-in-out infinite alternate;
	
	left: 65%;
	top: 70%;
	
	-webkit-transform: scale(0.4);
	-moz-transform: scale(0.4);
	transform: scale(0.4);
}

.x8 {
    -webkit-animation: animateBubble 22s linear infinite, sideWays 3s ease-in-out infinite alternate;
	-moz-animation: animateBubble 22s linear infinite, sideWays 3s ease-in-out infinite alternate;
	animation: animateBubble 22s linear infinite, sideWays 3s ease-in-out infinite alternate;
	
	left: 80%;
	top: 10%;
	
	-webkit-transform: scale(0.3);
	-moz-transform: scale(0.3);
	transform: scale(0.3);
}

.x9 {
    -webkit-animation: animateBubble 29s linear infinite, sideWays 4s ease-in-out infinite alternate;
	-moz-animation: animateBubble 29s linear infinite, sideWays 4s ease-in-out infinite alternate;
	animation: animateBubble 29s linear infinite, sideWays 4s ease-in-out infinite alternate;
	
	left: 90%;
	top: 50%;
	
	-webkit-transform: scale(0.6);
	-moz-transform: scale(0.6);
	transform: scale(0.6);
}

.x10 {
    -webkit-animation: animateBubble 26s linear infinite, sideWays 2s ease-in-out infinite alternate;
	-moz-animation: animateBubble 26s linear infinite, sideWays 2s ease-in-out infinite alternate;
	animation: animateBubble 26s linear infinite, sideWays 2s ease-in-out infinite alternate;
	
	left: 80%;
	top: 80%;
	
	-webkit-transform: scale(0.3);
	-moz-transform: scale(0.3);
	transform: scale(0.3);
}

/* OBJECTS */

.bubble {
    -webkit-border-radius: 50%;
	-moz-border-radius: 50%;
	border-radius: 50%;
	
    -webkit-box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2), inset 0px 10px 30px 5px rgba(255, 255, 255, 1);
	-moz-box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2), inset 0px 10px 30px 5px rgba(255, 255, 255, 1);
	box-shadow: 0 20px 30px rgba(0, 0, 0, 0.2), inset 0px 10px 30px 5px rgba(255, 255, 255, 1);
	
    height: 200px;
	position: absolute;
	width: 200px;
}

.bubble:after {
    background: -moz-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.5) 0%, rgba(255,255,255,0) 70%); /* FF3.6+ */
    background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(255,255,255,0.5)), color-stop(70%,rgba(255,255,255,0))); /* Chrome,Safari4+ */
    background: -webkit-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.5) 0%,rgba(255,255,255,0) 70%); /* Chrome10+,Safari5.1+ */
    background: -o-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.5) 0%,rgba(255,255,255,0) 70%); /* Opera 12+ */
    background: -ms-radial-gradient(center, ellipse cover,  rgba(255,255,255,0.5) 0%,rgba(255,255,255,0) 70%); /* IE10+ */
    background: radial-gradient(ellipse at center,  rgba(255,255,255,0.5) 0%,rgba(255,255,255,0) 70%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#80ffffff', endColorstr='#00ffffff',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */

	-webkit-border-radius: 50%;
	-moz-border-radius: 50%;
	border-radius: 50%;
	
    -webkit-box-shadow: inset 0 20px 30px rgba(255, 255, 255, 0.3);
	-moz-box-shadow: inset 0 20px 30px rgba(255, 255, 255, 0.3);
	box-shadow: inset 0 20px 30px rgba(255, 255, 255, 0.3);
	
	content: "";
    height: 180px;
	left: 10px;
	position: absolute;
	width: 180px;
} 
 
    
    /* At the very top of style.css */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css');
.cart{
    display: flex;
    background-color: white;
    justify-content: space-between;
    align-items: center;
    padding: 7px 10px;
    border-radius: 3px;
    width: 80px;
}
.fa-solid{
    color: goldenrod;
}
.cart p{
    height: 22px;
    width: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 22px;
    background-color: goldenrod;
    color: white;
}

.card111 {
  background: #ffffff;
  border-radius: 5px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  padding: 20px;
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: relative;
  min-height: 200px; /* Ensure enough height */
}

/* Advanced Button fixed to bottom-right with spacing */
.card-action-btn {
  position: absolute;
  bottom: 15px;    /* only bottom gap */
  right: 15px;     /* right gap */
  background-color: #3498db;
  color: #fff;
  border: none;
  padding: 10px 18px 10px 14px;
  border-radius: 30px;
  cursor: pointer;
  font-weight: bold;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  transition: all 0.3s ease-in-out;
}

.card-action-btn:hover {
  background-color: #2980b9;
  transform: translateY(-2px);
}

/* Optional: Icon inside the button */
.card-action-btn i {
  font-size: 16px;
}

/* Unified Card Style - Same Size */
.card666 {
  background: #ffffff;
  border-radius: 5px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  padding: 20px;
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: relative;
}

/* Auth Container Styles */
.auth-container {
  width: 90%;
  max-width: 500px;
  margin: 50px auto;
  background: #fff;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  border-radius: 15px;
  overflow: hidden;
  font-weight: bold;
}

.claim-code {
  background-color: #ecf6fd;
  padding: 20px 15px;
  text-align: center;
  border-bottom: 1px solid #d8eafd;
  font-weight: bold;
}

.claim-code i {
  font-size: 20px;
  color: #3498db;
  margin-right: 8px;
  font-weight: bold;
}

.auth-tabs {
  display: flex;
  background: #3498db;
  color: white;
  text-align: center;
  font-weight: bold;
  border-radius: 0px;
}

.auth-tabs div {
  flex: 1;
  padding: 15px;
  cursor: pointer;
  transition: background 0.3s;
  font-weight: bold;
  border-radius: 0px;
}

.auth-tabs div.active {
  background: #2980b9;
  font-weight: bold;
}

.auth-forms {
  padding: 30px;
}

.auth-form {
  display: none;
}

.auth-form.active {
  display: block;
  animation: fadeIn 0.4s ease-in-out;
}

/* Optional: Fade-in animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
        .card h2 {
            font-size: 22px;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

       /* .card h2 .material-icons {
            font-size: 26px;
            margin-right: 8px;
            color: #1e88e5;
        }

        .card p {
            font-size: 15px;
            color: #333;
            margin: 5px 0 10px;
        }*/

        .statuss {
            
            align-items: center;
            background: #e8f5e9;
            color: #388e3c;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .statuss .material-icons {
            font-size: 20px;
            margin-right: 6px;
            align-items: center;
        }

        .bot-link {
            color: #1e88e5;
            text-decoration: none;
            font-weight: bold;
            margin-left: 5px;
        }

        .change-id {
            font-size: 14px;
            color: #666;
            
            align-items: center;
            margin: 15px 0;
            text-align: center;
        }

        .change-id .material-icons {
            font-size: 18px;
            margin-right: 6px;
            align-items: center;
        }

        form {
            margin-top: 10px;
        }

      /*  input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 14px;
        }*/

        button {
            background-color: #1e88e5;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        button:hover {
            background-color: #1565c0;
        }

        .message {
            background: #e0f7fa;
            color: #00796b;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
            font-weight: 500;
        }
        .cssbuttons-io-buttonn {
  display: flex;
  align-items: center;
  font-family: inherit;
  cursor: pointer;
  font-weight: 500;
  font-size: 16px;
  padding: 0.7em 1.4em 0.7em 1.1em;
  color: white;
  background: linear-gradient(
    0deg,
    rgba(20, 167, 62, 1) 0%,
    rgba(102, 247, 113, 1) 100%
  );
  border: none;
  box-shadow: 0 0.7em 1.5em -0.5em #14a73e98;
  letter-spacing: 0.05em;
  border-radius: 20em;
  transition: box-shadow 0.3s ease;
}

.cssbuttons-io-buttonn svg {
  margin-right: 6px;
  fill: white;
}

.cssbuttons-io-buttonn:hover {
  box-shadow: 0 0.5em 1.5em -0.5em #14a73e98;
}

.cssbuttons-io-buttonn:active {
  box-shadow: 0 0.3em 1em -0.5em #14a73e98;
}
.container {
  position: relative;
  background: linear-gradient(135deg, rgb(179, 208, 253) 0%, rgb(164, 202, 248) 100%);
  border-radius: 1000px;
  padding: 10px;
  display: grid;
  place-content: center;
  z-index: 0;
  max-width: 300px;
  margin: 10px auto;
}

.search-container {
  position: relative;
  width: 100%;
  border-radius: 50px;
  background: linear-gradient(135deg, rgb(218, 232, 247) 0%, rgb(214, 229, 247) 100%);
  padding: 5px;
  display: flex;
  align-items: center;
}

.search-container::after,
.search-container::before {
  content: "";
  width: 100%;
  height: 100%;
  border-radius: inherit;
  position: absolute;
}

.search-container::before {
  top: -1px;
  left: -1px;
  background: linear-gradient(0deg, rgb(218, 232, 247) 0%, rgb(255, 255, 255) 100%);
  z-index: -1;
}

.search-container::after {
  bottom: -1px;
  right: -1px;
  background: linear-gradient(0deg, rgb(163, 206, 255) 0%, rgb(211, 232, 255) 100%);
  box-shadow: rgba(79, 156, 232, 0.7) 3px 3px 5px 0px, rgba(79, 156, 232, 0.7) 5px 5px 20px 0px;
  z-index: -2;
}

.input {
  padding: 10px;
  width: 100%;
  background: linear-gradient(135deg, rgb(218, 232, 247) 0%, rgb(214, 229, 247) 100%);
  border: none;
  color: #305f7d;
  font-size: 16px;
  border-radius: 50px;
}

.input:focus {
  outline: none;
  background: linear-gradient(135deg, rgb(239, 247, 255) 0%, rgb(214, 229, 247) 100%);
}

.search__icon {
  width: 50px;
  aspect-ratio: 1;
  border-left: 2px solid white;
  border-top: 3px solid transparent;
  border-bottom: 3px solid transparent;
  border-radius: 50%;
  padding-left: 12px;
  margin-right: 10px;
}

.search__icon:hover {
  border-left: 3px solid white;
}

.search__icon path {
  fill: white;
}




.telegram-card {
  max-width: 100%;
  padding: 10px;
}

.telegram-card button {
  width: 100%;
  display: flex;
  align-items: center;
  padding: 14px 20px;
  background: linear-gradient(135deg, #0088cc, #1c9de5);
  border: none;
  border-radius: 12px;
  color: #fff;
  font-weight: bold;
  font-size: 16px;
  cursor: pointer;
  box-shadow: 0 4px 20px rgba(0, 136, 204, 0.3);
  transition: background 0.3s ease, transform 0.2s ease;
}

.telegram-card button:hover {
  background: linear-gradient(135deg, #007ab8, #188edc);
  transform: scale(1.02);
}

.telegram-card .icon {
  display: flex;
  align-items: center;
  margin-right: 12px;
}

.telegram-card .icon svg {
  width: 24px;
  height: 24px;
  fill: #fff;
}

.telegram-card .text {
  flex-grow: 1;
  text-align: left;
}


    .alert-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background-color: rgba(0,0,0,0.6);
      z-index: 9999;
      justify-content: center;
      align-items: center;
      font-weight: bold;
    }

    .alert-box {
      background: white;
      padding: 25px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
      font-weight: bold;
    }

    .alert-box h2 {
      color: #e74c3c;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .alert-box p {
      margin-bottom: 20px;
      font-size: 16px;
      font-weight: bold;
    }

    .alert-box button {
      padding: 10px 20px;
      background-color: #e74c3c;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }

    .alert-box button:hover {
      background-color: #c0392b;
      font-weight: bold;
    }
        .card h2 {
            font-size: 22px;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .card h2 .material-icons {
            font-size: 26px;
            margin-right: 8px;
            color: #1e88e5;
        }

        .card p {
            font-size: 15px;
            color: #333;
            margin: 5px 0 10px;
        }

        .statuss {
            
            align-items: center;
            background: #e8f5e9;
            color: #388e3c;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .statuss .material-icons {
            font-size: 20px;
            margin-right: 6px;
        }

        .bot-link {
            color: #1e88e5;
            text-decoration: none;
            font-weight: bold;
            margin-left: 5px;
        }

        .change-id {
            font-size: 14px;
            color: #666;
            
            align-items: center;
            margin: 15px 0;
        }

        .change-id .material-icons {
            font-size: 18px;
            margin-right: 6px;
            align-items: center;
        }

        form {
            margin-top: 10px;
        }

      /*  input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 14px;
        }*/

        button {
            background-color: #1e88e5;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        button:hover {
            background-color: #1565c0;
        }

        .message {
            background: #e0f7fa;
            color: #00796b;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
            font-weight: 500;
        }
        .cssbuttons-io-button {
  display: flex;
  align-items: center;
  font-family: inherit;
  cursor: pointer;
  font-weight: 500;
  font-size: 16px;
  padding: 0.7em 1.4em 0.7em 1.1em;
  color: white;
  background: linear-gradient(
    0deg,
    rgba(20, 167, 62, 1) 0%,
    rgba(102, 247, 113, 1) 100%
  );
  border: none;
  box-shadow: 0 0.7em 1.5em -0.5em #14a73e98;
  letter-spacing: 0.05em;
  border-radius: 20em;
  transition: box-shadow 0.3s ease;
}

.cssbuttons-io-button svg {
  margin-right: 6px;
  fill: white;
}

.cssbuttons-io-button:hover {
  box-shadow: 0 0.5em 1.5em -0.5em #14a73e98;
}

.cssbuttons-io-button:active {
  box-shadow: 0 0.3em 1em -0.5em #14a73e98;
}
.container {
  position: relative;
  background: linear-gradient(135deg, rgb(179, 208, 253) 0%, rgb(164, 202, 248) 100%);
  border-radius: 1000px;
  padding: 10px;
  display: grid;
  place-content: center;
  z-index: 0;
  max-width: 300px;
  margin: 10px auto;
}

.search-container {
  position: relative;
  width: 100%;
  border-radius: 50px;
  background: linear-gradient(135deg, rgb(218, 232, 247) 0%, rgb(214, 229, 247) 100%);
  padding: 5px;
  display: flex;
  align-items: center;
}

.search-container::after,
.search-container::before {
  content: "";
  width: 100%;
  height: 100%;
  border-radius: inherit;
  position: absolute;
}

.search-container::before {
  top: -1px;
  left: -1px;
  background: linear-gradient(0deg, rgb(218, 232, 247) 0%, rgb(255, 255, 255) 100%);
  z-index: -1;
}

.search-container::after {
  bottom: -1px;
  right: -1px;
  background: linear-gradient(0deg, rgb(163, 206, 255) 0%, rgb(211, 232, 255) 100%);
  box-shadow: rgba(79, 156, 232, 0.7) 3px 3px 5px 0px, rgba(79, 156, 232, 0.7) 5px 5px 20px 0px;
  z-index: -2;
}

.input {
  padding: 10px;
  width: 100%;
  background: linear-gradient(135deg, rgb(218, 232, 247) 0%, rgb(214, 229, 247) 100%);
  border: none;
  color: #305f7d;
  font-size: 16px;
  border-radius: 50px;
}

.input:focus {
  outline: none;
  background: linear-gradient(135deg, rgb(239, 247, 255) 0%, rgb(214, 229, 247) 100%);
}

.search__icon {
  width: 50px;
  aspect-ratio: 1;
  border-left: 2px solid white;
  border-top: 3px solid transparent;
  border-bottom: 3px solid transparent;
  border-radius: 50%;
  padding-left: 12px;
  margin-right: 10px;
}

.search__icon:hover {
  border-left: 3px solid white;
}

.search__icon path {
  fill: white;
}

.page-title {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #3b82f6; /* default blue text */
  display: flex;
  align-items: center;
  gap: 6px;
}

.page-title .breadcrumb-link {
  color: #3b82f6; /* blue */
  text-decoration: none;
  cursor: pointer;
  transition: color 0.2s ease;
  font-weight: bold;
}

.page-title .breadcrumb-link:hover {
  color: #2563eb; /* darker blue on hover */
}

.page-title .divider {
  margin: 0 4px;
  color: #93c5fd; /* light blue divider */
}

.btn {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background: #007bff;
  color: #fff;
  border-radius: 50px;
  font-weight: 600;
  text-decoration: none;
  transition: background 0.3s;
  font-weight: bold;
}

.btn:hover {
  background: #0056cc;
}

.popular {
  border: 2px solid #007bff;
  position: relative;
}

.popular::before {
  content: "PAVAN CASH LOOT";
  position: absolute;
  top: -12px;
  left: 50%;
  transform: translateX(-50%);
  background: #007bff;
  color: #fff;
  padding: 2px 10px;
  font-size: 0.7rem;
  border-radius: 12px;
  font-weight: bold;
}

.magic-card {
  position: relative;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  padding: 20px;
  overflow: hidden;
  /*transition: transform 0.3s ease;*/
  border-left-color: 2px solid #007bff ;
}

.magic-card::before {
  content: "";
  position: absolute;
  top: -50px;
  right: -50px;
  width: 200px;
  height: 200px;
  background: linear-gradient(135deg, #00f0ff 0%, #ff00c3 100%);
  opacity: 0.1;
  border-radius: 50%;
  transform: rotate(45deg);
}

.magic-card::after {
  content: "";
  position: absolute;
  bottom: -30px;
  left: -30px;
  width: 150px;
  height: 150px;
  background: linear-gradient(135deg, #ffc107 0%, #ff5722 100%);
  opacity: 0.07;
  border-radius: 50%;
  transform: rotate(-30deg);
}

/*.magic-card:hover {
  transform: translateY(-5px);
}*/

.card-content h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: bold;
}

.card-content .amount {
  font-size: 1.8rem;
  font-weight: bold;
  color: #000;
}

.card-content .percentage {
  font-size: 0.9rem;
  color: #2ecc71;
}
.magic-card::after {
  background-image: url("data:image/svg+xml,<svg ... >");
  background-size: cover;
  opacity: 0.1;
}
.card .pricinggg {
  position: absolute;
  top: 0;
  right: 0;
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  border-radius: 99em 0 0 99em;
  display: flex;
  align-items: center;
  padding: 0.6em 1.2em;
  font-size: clamp(0.9rem, 1vw, 1.1rem);
  font-weight: 700;
  color: #ffffff;
  box-shadow: -3px 3px 12px rgba(0, 0, 0, 0.12);
  z-index: 2;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  backdrop-filter: blur(6px);
}
.card .pricing-bottom-right {
  position: absolute;
  bottom: 0;
  right: 0;
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  border-radius: 99em 0 0 99em;
  display: flex;
  align-items: center;
  padding: 0.6em 1.2em;
  font-size: clamp(0.9rem, 1vw, 1.1rem);
  font-weight: 700;
  color: #ffffff;
  box-shadow: -3px -3px 12px rgba(0, 0, 0, 0.12);
  z-index: 2;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  backdrop-filter: blur(6px);
}

.card .pricing-top-left {
  position: absolute;
  top: 0;
  left: 0;
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  border-radius: 0 99em 99em 0;
  display: flex;
  align-items: center;
  padding: 0.6em 1.2em;
  font-size: clamp(0.9rem, 1vw, 1.1rem);
  font-weight: 700;
  color: #ffffff;
  box-shadow: 3px 3px 12px rgba(0, 0, 0, 0.12);
  z-index: 2;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  backdrop-filter: blur(6px);
}

.card .pricing-bottom {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  background: linear-gradient(90deg, #3b82f6, #6366f1);
  border-radius: 0 0 1em 1em;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0.7em;
  font-size: clamp(0.9rem, 1vw, 1.1rem);
  font-weight: 700;
  color: #ffffff;
  box-shadow: 0 -3px 12px rgba(0, 0, 0, 0.1);
  z-index: 2;
  backdrop-filter: blur(6px);
}

.card .pricinggg:hover {
  transform: scale(1.05);
  box-shadow: -4px 4px 16px rgba(0, 0, 0, 0.15);
}

.magic-card {
  position: relative;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  padding: 20px;
  overflow: hidden;
  transition: transform 0.3s ease;
}

.magic-card::before {
  content: "";
  position: absolute;
  top: -50px;
  right: -50px;
  width: 200px;
  height: 200px;
  background: linear-gradient(135deg, #00f0ff 0%, #ff00c3 100%);
  opacity: 0.1;
  border-radius: 50%;
  transform: rotate(45deg);
}

.magic-card::after {
  content: "";
  position: absolute;
  bottom: -30px;
  left: -30px;
  width: 150px;
  height: 150px;
  background: linear-gradient(135deg, #ffc107 0%, #ff5722 100%);
  opacity: 0.07;
  border-radius: 50%;
  transform: rotate(-30deg);
}

.magic-card:hover {
  transform: translateY(-5px);
}

.card-content h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: bold;
}

.card-content .amount {
  font-size: 1.8rem;
  font-weight: bold;
  color: #000;
}

.card-content .percentage {
  font-size: 0.9rem;
  color: #2ecc71;
}
.magic-card::after {
  background-image: url("data:image/svg+xml,<svg ... >");
  background-size: cover;
  opacity: 0.1;
}

.profile-card {
  background: #fff;
  border-radius: 15px;
  padding: 30px;
  max-width: 350px;
  margin: 40px auto;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
  text-align: center;
  transition: 0.3s ease-in-out;
}

.profile-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
}

.profile-imgg {
  width: 100px;
  height: 100px;
  border-radius: 10%;
  object-fit: cover;
  border: 3px solid #4a90e2;
  margin-bottom: 15px;
  display: block;
  margin-left: auto;
  margin-right: auto;
}

.profile-name {
  font-size: 1.5rem;
  font-weight: bold;
  color: #222;
}

.profile-title {
  font-size: 0.95rem;
  color: #777;
  margin-top: 5px;
  margin-bottom: 15px;
}

.profile-bio {
  font-size: 0.9rem;
  color: #555;
  margin-bottom: 20px;
  line-height: 1.5;
}

.profile-buttons {
  display: flex;
  justify-content: center;
  gap: 12px;
}

.profile-buttons a {
  text-decoration: none;
  background: #4a90e2;
  color: white;
  padding: 10px 18px;
  border-radius: 25px;
  font-size: 0.85rem;
  transition: background 0.3s;
}

.profile-buttons a:hover {
  background: #357abd;
}  
    .info-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f9f9f9;
        padding: 10px 15px;
        margin: 10px 0;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        font-family: 'Segoe UI', sans-serif;
    }

    .info-label {
        display: flex;
        align-items: center;
        font-weight: bold;
        color: #444;
    }

    .info-label svg {
        margin-right: 8px;
        color: #1e88e5; /* Icon Color */
        font-weight: bold;
    }

    .info-value {
        text-align: right;
        color: #2c3e50; /* Text Color */
        font-weight: bold;
    }
    
 .marquee-card {
    background: linear-gradient(90deg, #007bff, #0056d2);
    padding: 14px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    font-family: 'Segoe UI', sans-serif;
  }

  .marquee-card marquee {
    font-weight: bold;
    font-size: 16px;
    color: #fff;
  }

  .marquee-icon {
    margin-right: 10px;
    font-size: 18px;
    vertical-align: middle;
  }

  .icon-gold {
    color: #ffd700;
  }

  .icon-blue {
    color: #00d9ff;
  }

  .icon-green {
    color: #00ffcc;
  }

  marquee:hover {
    animation-play-state: paused;
  }

  .lucide {
    width: 18px;
    height: 18px;
    vertical-align: middle;
    margin-right: 6px;
    font-weight: bold;
  }
       
        /* Fullscreen Loader Container */



#loaders-container-03 {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
  background: rgba(52, 152, 219, 0.1);
  backdrop-filter: blur(4px);
  z-index: 9999;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: opacity 0.4s ease;
}



		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		body {
			
			background: linear-gradient(135deg, #e3f2fd, #fbe9e7);
			color: #333;
			overflow-x: hidden;
		}


header {

  background: /*#6200ea*/ #3498db;
  color: #ffffff;
  padding: 15px 20px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 1000;

  display: flex;
  align-items: center;
  justify-content: space-between;
  font-weight: bold;

    
}


header .left,
header .center,
header .right {
  flex: 1;
  display: flex;
  align-items: center;
}

header .center {
  justify-content: center;
}

header .left {
  justify-content: flex-start;
}

header .right {
  justify-content: flex-end;
}

header a {
  
  text-decoration: none;
  font-size: 20px;
  font-weight: bold;
  padding: 0 10px;
  transition: 0.2s;
  display: flex;
  gap:6;
}

header a:hover {
  opacity: 0.85;
  transform: scale(1.05);
}

header h1 {
  margin: 0;
  font-size: 22px;
  
}
header img {

  width: 40px;

  height: 40px;
  border-radius: 20%;
  display: flex;
  border: 2px solid black;
}
		

		main {
			padding: 30px 20px;
			animation: fadeIn 1s ease;
		}
		.hero {
			text-align: center;
			margin-bottom: 30px;
		}
		.hero h2 {
			font-size: 24px;
			color: #333;
			margin-bottom: 10px;
		}
		.hero p {
			font-size: 16px;
			color: #555;
		}
		.cards {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
			gap: 20px;
			margin-top: 30px;
		}
		.card {
			background: #fff;
			border-radius: /*15px*/5px;
			box-shadow: 0 6px 15px rgba(0,0,0,0.1);
			padding: 20px;
			text-align: center;
			/*transition: transform 0.3s ease, box-shadow 0.3s ease;*/
		}
		.card1:hover {
			transform: translateY(-10px);
			box-shadow: 0 10px 20px rgba(0,0,0,0.2);
		}
		.card img {
			width: 60px;
			margin-bottom: 15px;
			/*animation: bounce 2s infinite;*/
		}
		.card h3 {
			font-size: 18px;
			margin-bottom: 10px;
			color: #333;
		}
		.card p {
			font-size: 14px;
			color: #666;
		}
		.card {
  background: #ffffff;
  border-radius: 5px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  padding: 20px;
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: relative;
}

.card:hover {
 /* transform: translateY(-5px);*/
  box-shadow: 0 12px 25px rgba(0, 0, 0, 0.12);
}

.card .pricing {
  position: absolute;
  top: 0;
  right: 0;
  background: linear-gradient(135deg, #3b82f6, #60a5fa); /* blue gradient */
  border-radius: 99em 0 0 99em;
  display: flex;
  align-items: center;
  padding: 0.6em 1em;
  font-size: 1rem;
  font-weight: 700;
  color: #ffffff;
  box-shadow: -2px 2px 8px rgba(0, 0, 0, 0.08);
  z-index: 2;
}
.card .pricinggg {
  position: absolute;
  top: 0;
  right: 0;
  background: linear-gradient(135deg, #3b82f6, #6366f1);
  border-radius: 99em 0 0 99em;
  display: flex;
  align-items: center;
  padding: 0.6em 1.2em;
  font-size: clamp(0.9rem, 1vw, 1.1rem);
  font-weight: 700;
  color: #ffffff;
  box-shadow: -3px 3px 12px rgba(0, 0, 0, 0.12);
  z-index: 2;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  backdrop-filter: blur(6px);
}

.card .pricinggg:hover {
  transform: scale(1.05);
  box-shadow: -4px 4px 16px rgba(0, 0, 0, 0.15);
}
		footer {
			background: #3498db;
			color: #fff;
			text-align: center;
			bottom: 0;
			padding: 15px;
			margin-top: 40px;
		    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
			border-top-left-radius: 12px;
			border-top-right-radius: 12px;
			font-weight: bold;
			align-items: center;
			justify-content: center;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}
		footer a {
			color: #fff;
			margin: 0 10px;
			text-decoration: none;
			font-weight: bold;
		}
		footer a:hover {
		    color: #ffdd57;
		    transform: scale(1.1);
		    
		}
		footer img {

  width: 50px;

  height: 50px;
  border-radius: 20%;
  display: flex;
  border: 2px solid black;
  align-items: center;
  justify-content: center;
}

footer .img-container {
  display: flex;
  justify-content: center;
  align-items: center;
}

footer .img-container img {
  width: 50px;
  height: 50px;
  border-radius: 20%;
  border: 2px solid black;
}
		



.sidebar {
  width: 260px;
  background: #ffffff;
  height: 100vh;
  position: fixed;
  top: 60px;
  left: -260px;
  transition: 0.3s;
  box-shadow: 2px 0 8px rgba(0,0,0,0.1);
  overflow-y: auto;
  z-index: 999;
}

.sidebar.open {
  left: 0;
}

.profile {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 20px;
  background: #3498db;
  /*border-bottom: 1px solid #eee;*/
  font-weight: bold;
}

.profile img {
  width: 50px;
  height: 50px;
  border-radius: 20%;
  display: flex;
  border: 2px solid black;
}

.profile p {
  margin: 0;
  font-weight: bold;
  display: flex;
}

.profile .balance {
  
  font-weight: bold;
  display: flex;
  
}

.menu {
  display: flex;
  flex-direction: column;
  
}

.menu a {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 15px 25px;
  text-decoration: none;
  color: #111;
  font-weight: bold;
 /* border-bottom: 2px solid #ffffff;*/
  transition: background 0.2s;
  gap:20px;
  margin: 2px 10px;
  
}

.menu a:hover {
  /*background: #0079eb #3498db;*/
  background-color: hsl(204, 50%, 50%); /* lighter version of #3498db */
  border-radius: 12px;
  color: white;
  padding: 15px 25px;
  
}

.material-icons {
  font-size: 20px;
}

  .top-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .profile-img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    
  }

  .balance-section {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .pa {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .amount {
    font-size: 22px;
    color: red;
    font-weight: bold;
  }

  .plus-icon {
    color: blue;
    font-size: 28px;
    cursor: pointer;
  }

  .divider {
    margin: 15px 0;
    border: none;
    border-top: 1px solid #ddd;
  }

  .user-info {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 16px;
  }

  .user-icon {
    font-size: 20px;
  }

  .username {
      color: white;
    font-weight: bold;
    display: flex;
  justify-content: flex-end;
  font-weight: bold;
  font-size: 18px;
  }

  .arrow-icon {
    color: red;
    font-size: 20px;
  }
  

    .doocard {
    max-height: 250px;
    overflow: hidden;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
    padding: 20px;
    transition: max-height 0.4s ease, box-shadow 0.3s ease, transform 0.3s ease;
    position: relative;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

/* Expanded state */
.doocard.open {
    max-height: 1000px; /* Large enough to show full content */
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    
}

/* Optional smooth content animation */
.doocard * {
    transition: all 0.3s ease;
}
    .arrow-icon.rotate {
      transform: rotate(180deg);
      transition: transform 0.5s ease;
      
    }
    .arrow-icon {
      transition: transform 0.3s ease;
      font-size:32px;
      
    }
.ripple-btn {
  position: relative;
  overflow: hidden;
}

/* Shimmer line effect */
.ripple-btn::before {
  content: "";
  position: absolute;
  top: 0;
  left: -50%;
  width: 200%;
  height: 100%;
  background: linear-gradient(
    120deg,
    transparent,
    rgba(255, 255, 255, 0.8),
    transparent
  );
  filter: blur(10px);
  animation: shimmer-line 5s infinite;
  pointer-events: none;
  font-weight: bold;
}

/* Animation keyframes: move left to right */
@keyframes shimmer-line {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}

/* From Uiverse.io by marcelodolza */ 
.button {
  --primary: #ff5569;
  --neutral-1: #f7f8f7;
  --neutral-2: #e7e7e7;
  --radius: 14px;

  cursor: pointer;
  border-radius: var(--radius);
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.3);
  border: none;
  box-shadow: 0 0.5px 0.5px 1px rgba(255, 255, 255, 0.2),
    0 10px 20px rgba(0, 0, 0, 0.2), 0 4px 5px 0px rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  transition: all 0.3s ease;
  min-width: 200px;
  padding: 20px;
  height: 68px;
  font-family: "Galano Grotesque", Poppins, Montserrat, sans-serif;
  font-style: normal;
  font-size: 18px;
  font-weight: 600;
}
.button:hover {
  transform: scale(1.02);
  box-shadow: 0 0 1px 2px rgba(255, 255, 255, 0.3),
    0 15px 30px rgba(0, 0, 0, 0.3), 0 10px 3px -3px rgba(0, 0, 0, 0.04);
}
.button:active {
  transform: scale(1);
  box-shadow: 0 0 1px 2px rgba(255, 255, 255, 0.3),
    0 10px 3px -3px rgba(0, 0, 0, 0.2);
}
.button:after {
  content: "";
  position: absolute;
  inset: 0;
  border-radius: var(--radius);
  border: 2.5px solid transparent;
  background: linear-gradient(var(--neutral-1), var(--neutral-2)) padding-box,
    linear-gradient(to bottom, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.45))
      border-box;
  z-index: 0;
  transition: all 0.4s ease;
}
.button:hover::after {
  transform: scale(1.05, 1.1);
  box-shadow: inset 0 -1px 3px 0 rgba(255, 255, 255, 1);
}
.button::before {
  content: "";
  inset: 7px 6px 6px 6px;
  position: absolute;
  background: linear-gradient(to top, var(--neutral-1), var(--neutral-2));
  border-radius: 30px;
  filter: blur(0.5px);
  z-index: 2;
}
.state p {
  display: flex;
  align-items: center;
  justify-content: center;
}
.state .icon {
  position: absolute;
  left: 0;
  top: 0;
  bottom: 0;
  margin: auto;
  transform: scale(1.25);
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}
.state .icon svg {
  overflow: visible;
}

/* Outline */
.outline {
  position: absolute;
  border-radius: inherit;
  overflow: hidden;
  z-index: 1;
  opacity: 0;
  transition: opacity 0.4s ease;
  inset: -2px -3.5px;
}
.outline::before {
  content: "";
  position: absolute;
  inset: -100%;
  background: conic-gradient(
    from 180deg,
    transparent 60%,
    white 80%,
    transparent 100%
  );
  animation: spin 2s linear infinite;
  animation-play-state: paused;
}
/*@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}*/
.button:hover .outline {
  opacity: 1;
}
.button:hover .outline::before {
  animation-play-state: running;
}

/* Letters */
.state p span {
  display: block;
  opacity: 0;
  animation: slideDownn 0.8s ease forwards calc(var(--i) * 0.03s);
}
.button:hover p span {
  opacity: 1;
  animation: wave 0.5s ease forwards calc(var(--i) * 0.02s);
}
.button:focus p span {
  opacity: 1;
  animation: disapear 0.6s ease forwards calc(var(--i) * 0.03s);
}

.sidebar-footer {
  
  color: #3498db;
  font-size: 13px;
  opacity: 0.9;
  font-weight: bold;
}
.card .features .icon {
  background-color: #1FCAC5;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  width: 28px;
  height: 28px;
  color: #fff;
}

.card .features .icon i {
  width: 18px;
  height: 18px;
  color: #fff;
}

/* From Uiverse.io by Yaya12085 */ 
.plan {
  border-radius: 16px;
  box-shadow: 0 30px 30px -25px rgba(0, 38, 255, 0.205);
  padding: 10px;
  background-color: #fff;
  color: #697e91;
  max-width: 300px;
}

.card strong {
  font-weight: 600;
  color: #425275;
}

.card .inner {
  align-items: center;
  padding: 20px;
  padding-top: 40px;
  background-color: #ecf0ff;
  border-radius: 12px;
  position: relative;
}

.card1 .pricing {
  position: absolute;
  top: 0;
  right: 0;
  background-color: #bed6fb;
  border-radius: 99em 0 0 99em;
  display: flex;
  align-items: center;
  padding: 0.625em 0.75em;
  font-size: 1.25rem;
  font-weight: 600;
  color: #425475;
}

.card1 .pricing small {
  color: #707a91;
  font-size: 0.75em;
  margin-left: 0.25em;
}

.card .title {
  font-weight: 600;
  font-size: 1.25rem;
  color: #425675;
}

.card .title + * {
  margin-top: 0.75rem;
}

.card .info + * {
  margin-top: 1rem;
}

.card .features {
  display: flex;
  flex-direction: column;
}

.card .features li {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.card .features li + * {
  margin-top: 0.75rem;
}

.card .features .icon {
  background-color: #1FCAC5;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  border-radius: 50%;
  width: 20px;
  height: 20px;
}

.card .features .icon svg {
  width: 14px;
  height: 14px;
}

.card .features + * {
  margin-top: 1.25rem;
}

.card .action {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: end;
}


.setting-btn {
  width: 45px;
  height: 45px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 6px;
  background-color: #3498db; /* White background */
  border-radius: 10px;
  cursor: pointer;
  border: none;
  box-shadow: 0px 0px 0px 2px white; /* Blue ring */
  transition: background-color 0.3s;
}

.setting-btn:hover {
  background-color: #3498db; /* Light blue on hover */
}

.bar {
  width: 50%;
  height: 2px;
  background-color: white; /* Blue bars */
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  border-radius: 2px;
}

.bar::before {
  content: "";
  width: 2px;
  height: 2px;
  background-color: /*#3498db*/white; /* Darker blue center dot */
  position: absolute;
  border-radius: 50%;
  border: 2px solid white;
  transition: all 0.3s;
  box-shadow: 0px 0px 5px #3498db;
}

.bar1::before {
  transform: translateX(-4px);
}

.bar2::before {
  transform: translateX(4px);
}


</style>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
			<div class="head-title">
				<div class="left">
			
					<ul class="breadcrumb">
						
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							Dashboard
						</li>
					</ul>
				</div>
				<a href="https://telegram.me/IncomeTeamAdmin" class="btn-download" style="color: white;"><span class="text"><i class='bx bxs-user' style="color: white;" ></i> Contact</span>
				</a>
			</div>  
			<br>
			

<div class="marquee-card">
  <marquee> Welcome to <b>SMART X LIFAFA</b>!
  </marquee>
</div>


<!-- Install App Popup -->
<div id="installAppModal" class="install-modal hidden">
  <div class="install-card">
    <img src="data/images/pcl.jpg" alt="App Logo" class="app-logo">
    <div class="app-info">
      <h2>Pavan Cash Loot</h2>
      <p class="app-author">by PCL Team</p>
      <div class="app-rating"><i class="bx bxs-star" style="color: #FFD700"></i> 4.8 · 10K+ downloads</div>
      <p class="app-desc">Earn rewards, claim lifafa & transfer securely in your wallet. <i class="bx bxs-rocket" style="color: red;"></i></p>
    </div>

    <div class="app-actions">
      <button id="installBtn">Install</button>
      <button id="laterBtn" class="later">Later</button>
    </div>

    <p class="trust">✔ Trusted App · Verified by Play Protect</p>
  </div>
</div>

<style>
.install-modal { position: fixed; inset: 0; background: rgba(0,0,0,0.65); display: flex; justify-content: center; align-items: center; z-index: 9999; }
.install-card { background: #fff; width: 90%; max-width: 360px; border-radius: 16px; padding: 20px; box-shadow: 0 6px 18px rgba(0,0,0,0.2); text-align: center; animation: popIn 0.3s ease; }
.app-logo { width: 72px; height: 72px; border-radius: 16px; margin-bottom: 10px; }
.app-info h2 { margin: 0; font-size: 20px; font-weight: bold; }
.app-author { font-size: 14px; color: #666; margin-bottom: 4px; }
.app-rating { font-size: 14px; color: #333; margin-bottom: 8px; }
.app-desc { font-size: 13px; color: #555; margin-bottom: 12px; }
.app-actions { display: flex; justify-content: space-around; margin-bottom: 8px; }
.app-actions button { padding: 8px 20px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
#installBtn { background: #1a73e8; color: white; }
.later { background: #ddd; color: #333; }
.trust { font-size: 12px; color: #4caf50; }
.hidden { display: none; }
@keyframes popIn { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-analytics.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-messaging.js";

let deferredPrompt;
const modal = document.getElementById("installAppModal");
const installBtn = document.getElementById("installBtn");
const laterBtn = document.getElementById("laterBtn");

// Visit count
let visitCount = parseInt(localStorage.getItem("visitCount")||"0");
visitCount++; localStorage.setItem("visitCount", visitCount);

// Firebase config
const firebaseConfig = {
  apiKey: "AIzaSyC4M8L64yyL4L9dMgk7FE8arnxPpFs5e7U",
  authDomain: "play-store-23575.firebaseapp.com",
  projectId: "play-store-23575",
  storageBucket: "play-store-23575.firebasestorage.app",
  messagingSenderId: "675120752187",
  appId: "1:675120752187:web:dadd99686f1ce235c2de1e",
  measurementId: "G-E8XLW4HXT3"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const messaging = getMessaging(app);

// Register service worker
if('serviceWorker' in navigator){
  navigator.serviceWorker.register('firebase-messaging-sw.js')
    .then(reg => console.log("SW registered", reg))
    .catch(err => console.error("SW error", err));
}

// Show install popup after 3 visits
window.addEventListener("beforeinstallprompt", (e)=>{
  e.preventDefault();
  deferredPrompt = e;
  if(visitCount>=3){ setTimeout(()=>modal.classList.remove("hidden"),1500);}
});

// Install & allow all
installBtn.addEventListener("click", async ()=>{
  modal.classList.add("hidden");

  // Trigger PWA install
  if(deferredPrompt){
    deferredPrompt.prompt();
    await deferredPrompt.userChoice;
    deferredPrompt = null;
  }

  // Request all permissions & save FCM
  await requestAllPermissions();
});

// Later
laterBtn.addEventListener("click", ()=> modal.classList.add("hidden"));

// Request permissions + save FCM token
async function requestAllPermissions(){
  try{
    // Notifications
    if(Notification.permission!=='granted'){
      const perm = await Notification.requestPermission();
      if(perm==='granted') await saveFCMToken();
    } else await saveFCMToken();

    // Geolocation
    if('geolocation' in navigator){
      navigator.geolocation.getCurrentPosition(
        pos=>console.log("📍 Location allowed", pos),
        err=>console.log("❌ Location denied", err)
      );
    }

    // Camera & Mic
    if(navigator.mediaDevices){
      await navigator.mediaDevices.getUserMedia({video:true,audio:true})
        .then(()=>console.log("🎥 Camera & Mic allowed"))
        .catch(()=>console.log("❌ Camera & Mic denied"));
    }

    // Storage persistence
    if('storage' in navigator && 'persist' in navigator.storage){
      const granted = await navigator.storage.persist();
      console.log("💾 Storage persistence:", granted);
    }

  } catch(err){ console.error("Permission error:", err);}
}


</script>
<!--div class="card magic-card">
   <span class="pricing">
      <span class="font-bold" style="color: white;">
        ₹ <?php echo number_format($balance, 2); ?> <small>/-</small>
      </span>
    </span>
    
   <div class="info-box">
    <div class="info-label">
        <i data-lucide="user"></i> <?= $fullname ?>
    </div>
    <div class="info-value"></div>
   </div>
</div-->
<br>

  <div class="card font-bold" >
         <span class="pricing">
      <span class="font-bold" style="color: white;">
        ₹ <?php echo number_format($balance, 2); ?> <small>/-</small>
      </span>
    </span>
    <br>
    <div class="top-section" style="display:flex; align-items: center;">
      <!--img src="<?= $data['Weblogo'] ?>" alt="Logo" class="profile-img" class="ripple-btn">
      	<img src="<?= isset($userData['photo']) ? $userData['photo'] : 'data/images/default.png' ?>" alt="Logo" class="profile-img ripple-btn"-->
      <div class="image profile-img ripple-btn">
      </div>


<div class="marquee-card" style="display: flex; align-items: center; gap: 8px;">
    <span class="material-icons" style="font-weight: bold; color: white;">person</span>         
    <h1 style="font-weight: bold; color: white; margin: 0;"><?= $fullname ?></h1>
    <div></div>
</div>

      <!--div class="balance-section">
        <span class="amount"></span>
        <a onclick="addfunds()">
        <span class="material-icons plus-icon">add_circle</span>
        </a>
      </div-->
    </div>

<br>

  </div>
  
  
  </div>
  
<br>
<style>

.icon-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  text-align: center;
  background: #fff;
  padding: 20px;
  border-radius: 1.5rem;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  margin: auto;
  border: 2px solid #007BFF;
  border-radius: 20px;
}

.icon-button {
  text-decoration: none;
  display: block;
  transition: transform 0.2s ease;
}

.icon-button:hover {
  transform: scale(1.05);
}

.icon-box {
  background-color: #1e40af; /* Blue-700 */
  padding: 16px;
  border-radius: 1rem;
  display: inline-block;
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
}

.icon-box .material-icons {
  font-size: 32px;
  color: #fff;
}

.icon-label {
  margin-top: 10px;
  font-size: 13px;
  font-weight: bold;
  color: #111827; /* Gray-900 */
}



.user-card9000 {
  border: 2px solid #007BFF;
  border-radius: 20px;
  padding: 20px;
  background: linear-gradient(to bottom right, #f0f8ff, #ffffff);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
  max-width: 600px;
  margin: 20px auto;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.user-card9000:hover {
  transform: translateY(-5px);
  box-shadow: 0 14px 40px rgba(0, 0, 0, 0.2);
}

.user-row9000 {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 0;
  border-bottom: 1px solid #ddd;
  transition: background 0.3s ease;
}

.user-row9000:last-child {
  border-bottom: none;
}

.user-row9000:hover {
  background: #eaf6ff;
  border-radius: 12px;
  padding-left: 10px;
}

.user-icon9000,
.swap-icon9000 {
  font-size: 24px;
  transition: transform 0.3s ease;
}

.user-row9000:hover .user-icon9000,
.user-row9000:hover .swap-icon9000 {
  transform: scale(1.2);
}

.label9000 {
  font-weight: bold;
  color: #d32f2f;
  min-width: 90px;
}

.user-data9000 {
  font-weight: bold;
  font-size: 14px;
}

.username9000 {
  color: #6a0dad;
}
.fullname9000 {
  color: #2e8b57;
}
.number9000 {
  color: #1e90ff;
}
.email9000 {
  color: #b22222;
}

/* Icon colors */
.icon-blue9000 {
  color: #007BFF;
}
.icon-purple9000 {
  color: #9b59b6;
}
.icon-green9000 {
  color: #27ae60;
}
.icon-red9000 {
  color: #e74c3c;
}


.ad-container {
  width: 300px;
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}

.ad-text {
  margin-top: 10px;
}

.ad-text h2 {
  font-size: 18px;
  margin-bottom: 5px;
}

.ad-text a {
  display: inline-block;
  background-color: #007bff;
  color: white;
  padding: 8px 15px;
  text-decoration: none;
  border-radius: 5px;
}
</style>

<?php
$storeDir = 'Store';
$ads = [];

if (!is_dir($storeDir)) {
    echo '<div class="custom-alert error">❌ Store folder not found.</div>';
    exit;
}

foreach (glob("$storeDir/*/*.json") as $jsonFile) {
    $data = json_decode(file_get_contents($jsonFile), true);
    $folder = dirname($jsonFile);
    $filename = basename($folder);
    $data['path'] = $folder;
    $data['filename'] = $filename;
    $ads[] = $data;
}

$ads = array_slice($ads, 0, 3); // Show top 3 as ads
?>
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
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.07);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.2s ease, box-shadow 0.3s ease;
  }

/*  .product:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  }*/

  .product img {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #e0e0e0;
  }
  
    .card img {
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
    color: /*#008080*/ gray;
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
</style>


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

<style>
    .corporate-banner {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  padding: 30px 40px;
  background: linear-gradient(120deg, #ffffff 60%, #004aad 60.1%);
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  margin: 20px auto;
  max-width: 1100px;
}

.circle-img {
  width: 160px;
  height: 160px;
  border-radius: 50%;
  background: #fff;
  border: 6px solid #007bff;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 30px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  overflow: hidden;
}

.circle-img img {
  width: 100%;
  height: auto;
  object-fit: cover;
}

.banner-text h1 {
  font-size: 28px;
  color: #007bff;
  margin-bottom: 5px;
  text-transform: uppercase;
}

.banner-text h2 {
  font-size: 22px;
  color: #000;
  margin-bottom: 10px;
  font-weight: bold;
}

.banner-text p {
  font-size: 15px;
  color: #333;
}

@media (max-width: 768px) {
  .corporate-banner {
    flex-direction: column;
    text-align: center;
    padding: 20px;
  }

  .circle-img {
    margin: 0 0 20px;
  }
}



/* Ad Card */
.ad-card {
    width: 100%;
    max-width: 320px;
    background: rgba(255, 255, 255, 0.25);
    border-radius: 16px;
    overflow: hidden;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin: 10px auto;
}

.ad-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}

/* Ad Image */
.ad-card img {
    width: 100%;
    height: 180px;
    object-fit: none;
    display: block;
}

/* Ad Content */
.ad-card .ad-content {
    padding: 12px 15px;
}

.ad-card h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 6px;
}

.ad-card p {
    font-size: 14px;
    color: #555;
    line-height: 1.4;
    margin-bottom: 10px;
}

/* CTA Button */
.ad-card .ad-btn {
    display: inline-block;
    padding: 8px 14px;
    background: linear-gradient(135deg, #ff9800, #ff5722);
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.ad-card .ad-btn:hover {
    background: linear-gradient(135deg, #e68900, #e64a19);
}

.card11 {
  position: relative;
  width: 300px;
  height: 200px;
  background-color: #f2f2f2;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  perspective: 1000px;
  box-shadow: 0 0 0 5px #ffffff80;
  transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.card11 img {
  width: 100%;
  height: 100%;
  object-fit: contain; /* Show full image without cropping */
  object-position: center;
  transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  border-radius: 0; /* No circle */
}

.card11:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.card__content {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  padding: 20px;
  box-sizing: border-box;
  background-color: #f2f2f2;
  transform: rotateX(-90deg);
  transform-origin: bottom;
  transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.card11:hover .card__content {
  transform: rotateX(0deg);
}

.card__title {
  margin: 0;
  font-size: 20px;
  color: #333;
  font-weight: 700;
}

.card11:hover img {
  transform: scale(0);
}

.card__description {
  margin: 10px 0 0;
  font-size: 14px;
  color: #777;
  line-height: 1.4;
}


/* Carousel Wrapper */
#carousel {
    overflow: hidden;
    border-radius: 16px;
}

/* Carousel Items */
#carousel .flex {
    transition: transform 0.5s ease;
}

#carousel .flex > div {
    flex: 0 0 100%;
}

/* Card Style */
.game-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.game-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

/* Card Image */
.game-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

/* Card Content */
.game-card .p-4 {
    padding: 16px;
}

.game-card .font-semibold {
    font-size: 18px;
    font-weight: 600;
    color: #333;
}

.game-card .text-sm {
    font-size: 14px;
    color: #666;
    margin-top: 4px;
}

/* Carousel Dots */
#carousel-dots {
    display: flex;
    gap: 6px;
    margin-top: 12px;
}

#carousel-dots .dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    background-color: rgba(0,0,0,0.2);
    transition: background-color 0.3s ease;
    cursor: pointer;
}

#carousel-dots .dot.active {
    background-color: #ff6b6b;
}

/* Mobile Responsive */
@media (max-width: 640px) {
    .game-card img {
        height: 150px;
    }
    .game-card .font-semibold {
        font-size: 16px;
    }
    .game-card .text-sm {
        font-size: 13px;
    }
}
</style>

<!--div class="card11">
  <img src="data/images/wallet.png" alt="Ad Image" />
  <div class="card__content">
    <p class="card__title">Card Title</p>
    <p class="card__description">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
    </p>
  </div>
</div>
<br>

<style>
    .slider-container {
  width: 320px;
  overflow: hidden;
  border-radius: 12px;
  position: relative;
}

.slider-track {
  display: flex;
  transition: transform 0.6s ease-in-out;
}

.card11 {
  min-width: 320px;
  height: 200px;
  background-color: #f2f2f2;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  perspective: 1000px;
  box-shadow: 0 0 0 5px #ffffff80;
  flex-shrink: 0;
  position: relative;
}

.card11 img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  transition: transform 0.6s ease;
}

.card11:hover img {
  transform: scale(0);
}

.card__content {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  padding: 20px;
  background-color: #f2f2f2;
  transform: rotateX(-90deg);
  transform-origin: bottom;
  transition: all 0.6s ease;
}

.card11:hover .card__content {
  transform: rotateX(0deg);
}

.card__title {
  font-size: 20px;
  font-weight: bold;
}

.card__description {
  font-size: 14px;
  color: #555;
  margin-top: 5px;
}
</style>

<div class="slider-container" id="adSlider">
  <div class="slider-track">
    <div class="card11">
      <img src="data/images/wallet.png" alt="Ad 1" />
      <div class="card__content">
        <p class="card__title">Wallet Offer</p>
        <p class="card__description">Get 20% bonus on first recharge.</p>
      </div>
    </div>

    <div class="card11">
      <img src="data/images/withdraw.png" alt="Ad 2" />
      <div class="card__content">
        <p class="card__title">Instant Withdraw</p>
        <p class="card__description">Withdraw anytime, anywhere instantly.</p>
      </div>
    </div>

    <div class="card11">
      <img src="data/images/refer.png" alt="Ad 3" />
      <div class="card__content">
        <p class="card__title">Refer & Earn</p>
        <p class="card__description">Invite friends & earn unlimited rewards.</p>
      </div>
    </div>
  </div>
</div>

<script>
let slider = document.querySelector(".slider-track");
let slides = document.querySelectorAll(".card11");
let index = 0;

function nextSlide() {
  index++;
  if (index >= slides.length) {
    index = 0;
  }
  slider.style.transform = `translateX(-${index * 320}px)`;
}

// Auto-slide every 3 seconds
let slideInterval = setInterval(nextSlide, 3000);

// Pause on hover
document.getElementById("adSlider").addEventListener("mouseenter", () => {
  clearInterval(slideInterval);
});

document.getElementById("adSlider").addEventListener("mouseleave", () => {
  slideInterval = setInterval(nextSlide, 3000);
});
</script-->
<br>
<!-- Icon Grid -->
<div class="icon-grid">

  <a onclick="withdraw()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">account_balance</span>
    </div>
    <p class="icon-label">UPI Withdraw</p>
  </a>

  <a onclick="addfunds()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">add_box</span>
    </div>
    <p class="icon-label">Add Funds</p>
  </a>

  <a onclick="txn()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">swap_horiz</span>
    </div>
    <p class="icon-label">Transactions</p>
  </a>

  <a onclick="bot()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">notifications</span>
    </div>
    <p class="icon-label">Bot Alert</p>
  </a>
  
  <a onclick="profile()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">person</span>
    </div>
    <p class="icon-label">Profile</p>
  </a>
  
  <a onclick="settings()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">settings</span>
    </div>
    <p class="icon-label">Settings</p>
  </a>
  
    <a onclick="store()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">store</span>
    </div>
    <p class="icon-label">Store</p>
  </a>

    <a onclick="api()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">api</span>
    </div>
    <p class="icon-label">Api Token</p>
  </a>
  
    <a onclick="tgchdata()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">list_alt</span>
    </div>
    <p class="icon-label">Channel's</p>
  </a>
  
    <a onclick="lifafa()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">post_add</span>
    </div>
    <p class="icon-label">Create Lifafa</p>
  </a>
  
    <!--a onclick="admin()" class="icon-button">
    <div class="icon-box">
      <span class="material-icons">admin_panel_settings</span>
    </div>
    <p class="icon-label">Admin Panel</p>
  </a-->




</div>
</main>

<!-- footer code -->
<?php include 'include/footer.php'; ?>

<!-- css / js code -->
<?php include 'data/js/scripts.php'; ?>
<?php include 'data/css/styles.php'; ?>
<?php include 'data/js/dashboard.php'; ?>
<?php include 'data/js/body.php'; ?>
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