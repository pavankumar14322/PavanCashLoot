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


?>

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$number = $_SESSION['user']['number'];
$lifafaDir = "lifafa";
$myLifafas = [];

$userFile = "users/$number/$number.json";
$chat_id = '';
if (file_exists($userFile)) {
    $userData = json_decode(file_get_contents($userFile), true);
    $chat_id = $userData['chat_id'] ?? '';
}

// Fetch user's own lifafas
if (is_dir($lifafaDir)) {
    foreach (scandir($lifafaDir) as $folder) {
        if ($folder === '.' || $folder === '..') continue;

        $file = "$lifafaDir/$folder/$folder.json";
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            if (isset($data[$folder]) && $data[$folder]['created_by'] === $number) {
                $myLifafas[] = $data[$folder];
            }
        }
    }
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_lifafa'])) {
    $deleteId = $_POST['delete_lifafa'];
    $path = "$lifafaDir/$deleteId";
    if (is_dir($path)) {
        array_map('unlink', glob("$path/*.*"));
        rmdir($path);

        // Telegram Alert
        if (!empty($chat_id)) {
            $msg = urlencode("❌ Your Lifafa (ID: $deleteId) was deleted.");
            $botToken = json_decode(file_get_contents("adminrole/Admin/Admin.json"), true)['bot_token'] ?? '';
            if ($botToken) {
                file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chat_id&text=$msg");
            }
        }

        echo "<script>localStorage.setItem('lifafa_deleted', 'yes'); location.href='my-lifafa.php';</script>";
        exit;
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

<!--div class="card"-->
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$number = $_SESSION['user']['number'];
$lifafaDir = "lifafa";
$myLifafas = [];

$userFile = "users/$number/$number.json";
$chat_id = '';
if (file_exists($userFile)) {
    $userData = json_decode(file_get_contents($userFile), true);
    $chat_id = $userData['chat_id'] ?? '';
}

// Fetch user's own lifafas
if (is_dir($lifafaDir)) {
    foreach (scandir($lifafaDir) as $folder) {
        if ($folder === '.' || $folder === '..') continue;

        $file = "$lifafaDir/$folder/$folder.json";
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            if (isset($data[$folder]) && $data[$folder]['created_by'] === $number) {
                $myLifafas[] = $data[$folder];
            }
        }
    }
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_lifafa'])) {
    $deleteId = $_POST['delete_lifafa'];
    $path = "$lifafaDir/$deleteId";
    if (is_dir($path)) {
        array_map('unlink', glob("$path/*.*"));
        rmdir($path);

        // Telegram Alert
        if (!empty($chat_id)) {
            $msg = urlencode("❌ Your Lifafa (ID: $deleteId) was deleted.");
            $botToken = json_decode(file_get_contents("adminrole/Admin/Admin.json"), true)['bot_token'] ?? '';
            if ($botToken) {
                file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chat_id&text=$msg");
            }
        }

        echo "<script>localStorage.setItem('lifafa_deleted', 'yes'); location.href='my-lifafa.php';</script>";
        exit;
    }
}
?>


    <link rel="stylesheet" href="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" />
    <style>
        .card { font-family: 'Poppins', sans-serif; }
        .profile-info .name {
    font-size: 24px;
    font-weight: bold;
    color: #444;
}

.profile-info .username {
    font-size: 18px;
    font-weight: bold;
    color: #888;
    margin-bottom: 8px;
    text-align: center;
}

.profile-info .contact {
    font-size: 16px;
    color: #444;
}

.profile-info .email {
    color: #6a11cb;
    font-weight: bold;
}

.profile-info .number {
    color: #2575fc;
    font-weight: bold;
}

.profile-info .divider {
    margin: 16px auto 8px;
    border: 0;
    height: 2px;
    width: 80%;
    background: linear-gradient(to right, #6a11cb, #2575fc);
    border-radius: 4px;
}

.profile-info {
  margin-top: 10px;
}

.profile-info h3,
.profile-info p {
  display: flex;
  align-items: center;
  gap: 6px;
  margin: 10px 0 6px;
  font-size: 14px;
}

.profile-info input {
  width: 100%;
  border: none;
  background: #f9f9f9;
  border-radius: 6px;
  padding: 6px 10px;
  font-size: 14px;
  color: #333;
  margin-left: 6px;
}

.profile-info input:read-only {
  background: #f1f1f1;
  cursor: default;
}

.divider {
  margin-top: 16px;
  border: none;
  border-top: 1px dashed #ccc;
}
    h3 {
        z-index: 2;
        position: relative;
        font-size: 22px;
        margin-bottom: 4px;
        color: #333;
    }

    .title0 {
        z-index: 2;
        position: relative;
        color: #666;
        font-size: 16px;
    }

    p {
        z-index: 2;
        position: relative;
        color: #888;
        font-size: 14px;
        margin: 10px 0 15px;
    }

    .icons0 {
        z-index: 2;
        position: relative;
        margin-bottom: 15px;
    }

    .icons0 i {
        color: #555;
        margin: 0 8px;
        font-size: 18px;
        transition: 0.3s;
        cursor: pointer;
    }

    .icons0 i:hover {
        color: #00c6ff;
        transform: scale(1.2);
    }

    .upload-form0 {
        z-index: 2;
        position: relative;
    }

    input[type="file"] {
        margin: 10px auto;
        display: block;
        padding: 6px;
        font-size: 14px;
    }

    .btn {
        background: linear-gradient(45deg, #4facfe, #00f2fe);
        color: white;
        border: none;
        padding: 8px 18px;
        border-radius: 30px;
        cursor: pointer;
        margin-top: 10px;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .alert {
        z-index: 2;
        position: relative;
        font-size: 14px;
        color: green;
        margin-top: 10px;
    }
    .profile-info {
    text-align: center;
    margin-top: 10px;
    padding: 10px;
    font-family: 'Poppins', sans-serif;
}
    </style>
    <script>
        function showToast(msg) {
            alert(msg); // Replace with custom toast if needed
        }
    </script>
<div class="flex justify-center items-center w-full gap-3 mb-6">
  <span class="material-icons text-3xl text-indigo-600 animate-bounce">card_giftcard</span>
  <h2 class="text-2xl font-extrabold bg-gradient-to-r from-purple-600 via-pink-500 to-orange-400 bg-clip-text text-transparent font-poppins tracking-wide">
    My Lifafa's
  </h2>
</div>


<?php if (empty($myLifafas)): ?>
    <div style="text-align:center; font-size:16px; color:#777;">You haven’t created any Lifafa yet.</div>
<?php else: ?>
    <?php foreach ($myLifafas as $lifafa): 
        $type = ucfirst($lifafa['type'] ?? 'Normal');
    ?>
        <div class="card profile-info" style="background: #fff; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">

            <!--div style="font-weight: bold; font-size: 18px; margin-bottom: 6px;">
                <?= htmlspecialchars($lifafa['title']) ?>
                <span style="background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-left: 10px;">
                    <i data-lucide="gamepad-2" style="width: 14px; height: 14px; vertical-align: middle; margin-right: 3px;"></i><?= $type ?>
                </span>
            </div-->
    <h3 class="contact">
      <i data-lucide="gift"></i>Title
      <input type="text" readonly value="<?= htmlspecialchars($lifafa['title']) ?>">
    </h3>
    <h3 class="contact">
      <i data-lucide="gamepad-2"></i>Type 
      <input type="text" readonly value="<?= $type ?>">
    </h3>
    <h3 class="contact">
      <i data-lucide="users"></i>Users
      <input type="text" readonly value="<?= $lifafa['claimed'] ?>/<?= $lifafa['total_users'] ?>">
    </h3>
    
    <h3 class="contact">
      <i data-lucide="indian-rupee"></i>Per 
      <input type="text" readonly value="₹<?= $lifafa['per_user'] ?>">
    </h3>

    <h3 class="contact">
      <i data-lucide="link"></i>Redirect
      <input type="text" readonly value="<?= $lifafa['redirect'] ?>">
    </h3>
    
    <h3 class="contact">
      <i data-lucide="lock"></i>Code 
      <input type="text" readonly value="<?= $lifafa['access_code'] ?>">
    </h3>
    
    <h3 class="contact">
      <i data-lucide="calendar-days"></i>Time
      <input type="text" readonly value=" <?= $lifafa['created_at'] ?>">
    </h3>

            <!--div style="font-size: 14px; margin-top: 4px;">
                <i data-lucide="users" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px;"></i>
                Claimed: <?= $lifafa['claimed'] ?>/<?= $lifafa['total_users'] ?>
            </div>

            <div style="font-size: 14px; margin-top: 2px;">
                <i data-lucide="indian-rupee" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px;"></i>
                ₹<?= $lifafa['per_user'] ?> per user
            </div>

            <div style="font-size: 13px; margin-top: 2px;">
                <i data-lucide="lock" style="width: 14px; height: 14px; vertical-align: middle; margin-right: 6px;"></i>
                Code: <?= $lifafa['access_code'] ?>
            </div>

            <div style="font-size: 13px; margin-top: 2px;">
                <i data-lucide="calendar-days" style="width: 14px; height: 14px; vertical-align: middle; margin-right: 6px;"></i>
                <?= $lifafa['created_at'] ?>
            </div-->
    <h3 class="contact">
      <i data-lucide="link"></i>Link
            <input 
                type="text" 
                value="https://pavancashloot.xyz/Loots/lifafaw.php?id=<?= $lifafa['id'] ?>" 
                readonly 
                onclick="this.select(); document.execCommand('copy'); showToast('🔗 Link copied!')" 
                style="margin-top: 12px; width: 100%; padding: 10px; font-size: 13px; border: none; border-radius: 8px; box-shadow: inset 0 0 5px rgba(0,0,0,0.2); color: #333;"
            >
            </h3>

            <!--div style="margin-top: 12px; display: flex; gap: 12px;">
                <a 
                    href="edit_lifafa.php?id=<?= $lifafa['id'] ?>" 
                    
                    style="background: #ff9800; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: bold; text-decoration: none; box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: 0.2s;"
                    
                    onmouseover="this.style.background='#fb8c00'" 
                    onmouseout="this.style.background='#ff9800'"
                >
                    <i data-lucide="edit" style="width: 50px; height: 16px; vertical-align: middle; margin-right: 6px;"></i> Edit
                </a-->
<div style="margin-top: 12px; display: flex; gap: 12px;">
    <a 
        href="edit_lifafa.php?id=<?= $lifafa['id'] ?>" 
        style="
            display: flex;
            align-items: center;
            gap: 6px;
            background: #ff9800;
            color: white;
            padding: 1px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: 0.2s;
        "
        onmouseover="this.style.background='#fb8c00'" 
        onmouseout="this.style.background='#ff9800'"
    >
        <i data-lucide="edit" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px;"></i> Edit
    </a>

<!-- Lucide Icons loader (add once in your page footer or header) -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
                <form method="POST" class="delete-lifafa-form" data-title="<?= htmlspecialchars($lifafa['title']) ?>">
                    <input type="hidden" name="delete_lifafa" value="<?= $lifafa['id'] ?>">
                    <button 
                        type="submit" 
                        style="background: #f44336; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: 0.2s;"
                        onmouseover="this.style.background='#d32f2f'" 
                        onmouseout="this.style.background='#f44336'"
                    >
                        <i data-lucide="trash-2" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px;"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    lucide.createIcons();
</script>

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