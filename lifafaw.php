<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
/*if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}*/

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
/*if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}*/

$number = $_SESSION['user']['number'];
$userFile = "users/$number/$number.json";
$walletFile = "users/$number/{$number}.txt";
$lifafaId = $_GET['id'] ?? null;

if (!$lifafaId) {
    echo "❌ Invalid Lifafa ID."; exit;
}

$lifafaOwner = null;
$lifafaData = null;

foreach (glob("lifafa/*/*.json") as $file) {
    $data = json_decode(file_get_contents($file), true);
    if (isset($data[$lifafaId])) {
        $lifafaData = $data[$lifafaId];
        $lifafaOwner = basename($file, ".json");
        break;
    }
}

if (!$lifafaData) {
    echo "❌ Lifafa not found."; exit;
}

$user = json_decode(file_get_contents($userFile), true);
$chat_id = $user['chat_id'] ?? null;
$channelsStatus = [];
$allJoined = true;

if ($lifafaData['require_channel'] && $chat_id) {
    $channels = $lifafaData['channels'] ?? [];
    $botData = json_decode(file_get_contents("adminrole/Admin/Admin.json"), true);
    $botToken = $botData['bot_token'];
    $botus = $botData['bot_username'];

    foreach ($channels as $channel) {
        $check = file_get_contents("https://api.telegram.org/bot$botToken/getChatMember?chat_id=@$channel&user_id=$chat_id");
        $checkData = json_decode($check, true);
        $status = $checkData['result']['status'] ?? 'left';
        $joined = ($status !== 'left' && $status !== 'kicked');
        $channelsStatus[$channel] = $joined;
        if (!$joined) $allJoined = false;
    }
}

$success = "";
$showAnimation = false;
$redirectUrl = $_SERVER['REQUEST_URI'];

$canAutoClaim = true;

if (in_array($number, $lifafaData['claimed_users'] ?? [])) {
    $error = "⚠️ You already claimed this Lifafa!";
    $canAutoClaim = false;
} elseif ($lifafaData['claimed'] >= $lifafaData['total_users']) {
    $error = "⛔ Lifafa limit reached!";
    $canAutoClaim = false;
} elseif ($lifafaData['require_channel']) {
    if (!$chat_id) {
        $error = "🔗 Please link your Telegram using /start bot.
        
Bot Link :- https://t.me/AlertPCLBot";
        $canAutoClaim = false;
    } elseif (!$allJoined) {
        $error = "🚫 You must join all required channels before claiming.";
        $canAutoClaim = false;
    }
}

if (isset($_POST['claim'], $_POST['access_code'], $_POST['number_input']) && $canAutoClaim) {
    $inputNumber = trim($_POST['number_input']);
    $inputCode = trim($_POST['access_code']);

    if ($inputNumber !== $number) {
        $error = "❌ Entered number doesn't match your session!";
    } elseif ($inputCode !== $lifafaData['access_code']) {
        $error = "🔐 Invalid access code!";
    } else {
        $amount = $lifafaData['per_user'];
        $balance = file_exists($walletFile) ? floatval(file_get_contents($walletFile)) : 0;
        $balance += $amount;
        file_put_contents($walletFile, $balance);

        $txnLogDir = "users/$number/TXN/lifafa";
        if (!file_exists($txnLogDir)) mkdir($txnLogDir, 0777, true);

        $txnFile = "$txnLogDir/$lifafaId.json";
        $txnData = [
            "type" => "Lifafa Claim",
            "lifafa_id" => $lifafaId,
            "amount" => $amount,
            "status" => "Success",
            "datetime" => date("l / F / Y • h:i:s A"),
            "closing_balance" => $balance,
        ];
        file_put_contents($txnFile, json_encode($txnData, JSON_PRETTY_PRINT));

        $lifafaData['claimed'] += 1;
        $lifafaData['claimed_users'][] = $number;

        $lifafaFile = "lifafa/$lifafaOwner/$lifafaOwner.json";
        $allLifafas = json_decode(file_get_contents($lifafaFile), true);
        $allLifafas[$lifafaId] = $lifafaData;
        file_put_contents($lifafaFile, json_encode($allLifafas, JSON_PRETTY_PRINT));
$datee = date("l / F / Y • h:i:s A"); 
        if ($chat_id) {
            $msg = urlencode("
<b>🧧 Lifafa Claimed By User $number

🌊 Giveaway Title : {$lifafaData['title']}

💰 Giveaway Amount : $amount

🆔 Lifafa Id : <code>https://pavancashloot.xyz/Loots/lifafaw.php?id=$lifafaId</code>

💫 Date : $datee </b>");

// Inline button JSON
$button = [
    'inline_keyboard' => [
        [
            ['text' => '🎁 View Lifafa', 'url' => "https://pavancashloot.xyz/Loots/lifafaw.php?id=$lifafaId"]
        ],
        [
            ['text' => '💖 JOIN', 'url' => "https://t.me/PCLWebsite"]
        ]
    ]
];
$replyMarkup = urlencode(json_encode($button));

            file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chat_id&text=$msg&parse_mode=HTML&reply_markup=$replyMarkup");
        }
        
        $adminData = json_decode(file_get_contents("adminrole/Admin/Admin.json"), true);
        $admin_chat_id = $adminData['admin_chat_id'] ?? null;

        if ($admin_chat_id) {
            $adminMsg = urlencode("<b>📥 Lifafa Claimed\n👤 User: $number\n🎁 Title: {$lifafaData['title']}\n💸 Amount: ₹$amount\n🆔 ID: $lifafaId </b>");
            file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?chat_id=$admin_chat_id&text=$adminMsg&parse_mode=HTML");
        }

        $success = "₹$amount";
        $showAnimation = true;
        $redirectUrl = !empty($lifafaData['redirect']) ? $lifafaData['redirect'] : $_SERVER['REQUEST_URI'];
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
      <link rel="stylesheet" media="all" href="data/css/styles.css" />
    <link rel="stylesheet" media="all" href="data/css/body.css" />
    
    <link rel="stylesheet" media="all" href="data/css/dashboard.css" />
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
<!-- css / js code -->
<?php include '/data/js/scripts.php'; ?>


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
<div id="fireworks-wrap">
  <div class="firework x1"></div>
  <div class="firework x2"></div>
  <div class="firework x3"></div>
  <div class="firework x4"></div>
  <div class="firework x5"></div>
  <div class="firework x6"></div>
  <div class="firework x7"></div>
  <div class="firework x8"></div>
  <div class="firework x9"></div>
  <div class="firework x10"></div>
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
    .price-tag {
      position: absolute;
      top: 0;
      right: 0;
      background: linear-gradient(135deg, #b06ab3, #4568dc);
      color: white;
      font-size: 14px;
      font-weight: bold;
      padding: 8px 15px;
      border-bottom-left-radius: 12px;
      border-top-right-radius: 8px;
    }

.animated-title {
  text-align: center;
  display: inline-block;
  margin: 20px auto;
  padding: 10px 24px;
  font-size: 22px;
  font-weight: 700;
  font-family: 'Poppins', sans-serif;
  color: #4A148C;
  background: #fff;
  border: 4px solid;
  border-image: linear-gradient(90deg, #8e2de2, #4a00e0, #8e2de2) 1;
  border-radius: 12px;
  animation: borderAnim 5s infinite linear;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
}

@keyframes borderAnim {
  0% {
    border-image-source: linear-gradient(90deg, #8e2de2, #4a00e0, #8e2de2);
  }
  50% {
    border-image-source: linear-gradient(90deg, #4a00e0, #8e2de2, #4a00e0);
  }
  100% {
    border-image-source: linear-gradient(90deg, #8e2de2, #4a00e0, #8e2de2);
  }
}
@import url('https://fonts.googleapis.com/css2?family=Urbanist:wght@700&display=swap');

.gradient-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 20px;
}

.animated-gradient-text {
  font-weight: 700;
  font-size: 26px;
  font-family: 'Urbanist', sans-serif;
  letter-spacing: 1.2px;
  background: linear-gradient(270deg, #ff6ec4, #ffb86c, #00f2fe, #4facfe, #7873f5);
  background-size: 1000% 1000%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: gradientSlide 8s ease-in-out infinite;
  display: inline-block;
  padding: 10px 20px;
  text-align: center;
}
@keyframes gradientSlide {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}
/* From Uiverse.io by LightAndy1  
.group11 {
  display: flex;
  line-height: 28px;
  align-items: center;
  position: relative;
  max-width: 190px;
}

.input11 {
  width: 800px;
  height: 60px;
  line-height: 28px;
  padding: 0 2.5rem;
  padding-left: 2.6rem;
  padding-right: 0.6rem;
  border: 2px solid transparent;
  border-radius: 8px;
  outline: none;
  background-color: #f3f3f4;
  color: #0d0c22;
  transition: 0.3s ease;
  border: 2px solid #1E90FF;
}

.input11::placeholder {
  color: #9e9ea7;
}

.input11:focus,
input:hover {
  outline: none;
  border-color: rgba(30, 144, 255, 0.4);
  background-color: #fff;
  box-shadow: 0 0 0 4px rgb(30 144 255 / 10%);
}

.icon11 {
  position: absolute;
  left: 1rem;
  fill: #9e9ea7;
  width: 1rem;
  height: 1rem;
} */

/* From Uiverse.io by LightAndy1 */ 
.group11 {
  display: flex;
  line-height: 28px;
  align-items: center;
  position: relative;
  /* removed conflicting max-width */
  width: 100%; 
  max-width: 800px; /* keep input aligned */
}

/*.input11 {
  width: 100%;
  height: 60px;
  line-height: 28px;
  padding: 0 2.5rem;
  padding-left: 2.6rem;
  padding-right: 0.6rem;
  border: 2px solid #1E90FF;
  
  border-radius: 8px;
  outline: none;
  background-color: #f3f3f4;
  color: #0d0c22;
  transition: 0.3s ease;
}*/
.input11 {
  width: 100%;
  height: 50px;
  line-height: 28px;
  padding: 0 2.5rem;
  padding-left: 2.6rem;
  padding-right: 0.6rem;
  
  border: 2px solid transparent; /* important */
  border-radius: 8px;
  outline: none;
  color: #0d0c22;
  transition: 0.3s ease;

  /* Gradient border with solid inner background */
  background: 
    linear-gradient(#f3f3f4, #f3f3f4) padding-box, 
    conic-gradient(
      from 0deg,
      #1E90FF 0deg,   /* top - blue */
      #32CD32 90deg,  /* right - green */
      #FF4500 180deg, /* bottom - orange/red */
      #8A2BE2 270deg, /* left - violet */
      #1E90FF 360deg  /* back to top */
    ) border-box;
}

.input11::placeholder {
  color: #9e9ea7;
}

.input11:focus,
.input11:hover {
  outline: none;
  border-color: rgba(30, 144, 255, 0.6);
  background-color: #fff;
  box-shadow: 0 0 0 4px rgb(30 144 255 / 15%);
}

.icon11 {
  position: absolute;
  left: 1rem;
  fill: #9e9ea7;
  width: 1rem;
  height: 1rem;
}
.custom-alert {
  position: fixed;
  top: 20px;
  right: 20px;
  background: #4caf50;
  color: #fff;
  padding: 15px 20px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  z-index: 9999;
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: bold;
  animation: fadeInSlide 0.5s ease;
}
.custom-alert button {
  background: transparent;
  border: none;
  color: #fff;
  font-size: 18px;
  cursor: pointer;
}
.hidden {
  display: none;
}
@keyframes fadeInSlide {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
#fireworks-wrap {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  overflow: hidden;
  z-index: 9999;
}

.firework {
  position: absolute;
  bottom: 0;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  animation: explodeUp 1.8s ease-out infinite;
  opacity: 0.8;
  filter: drop-shadow(0 0 6px currentColor);
}

/* No white - only vibrant colors */
.firework.x1 { left: 10%; background: #ff4081; color: #ff4081; animation-delay: 0s; }
.firework.x2 { left: 22%; background: #ff5722; color: #ff5722; animation-delay: 0.3s; }
.firework.x3 { left: 33%; background: #ffc107; color: #ffc107; animation-delay: 0.6s; }
.firework.x4 { left: 44%; background: #4caf50; color: #4caf50; animation-delay: 0.1s; }
.firework.x5 { left: 55%; background: #00bcd4; color: #00bcd4; animation-delay: 0.5s; }
.firework.x6 { left: 66%; background: #3f51b5; color: #3f51b5; animation-delay: 0.2s; }
.firework.x7 { left: 77%; background: #9c27b0; color: #9c27b0; animation-delay: 0.7s; }
.firework.x8 { left: 88%; background: #e91e63; color: #e91e63; animation-delay: 0.4s; }
.firework.x9 { left: 15%; background: #ff9800; color: #ff9800; animation-delay: 0.8s; }
.firework.x10 { left: 75%; background: #8bc34a; color: #8bc34a; animation-delay: 0.9s; }

@keyframes explodeUp {
  0% {
    transform: translateY(0) scale(1);
    opacity: 1;
  }
  50% {
    transform: translateY(-50vh) scale(1.2);
    opacity: 1;
  }
  100% {
    transform: translateY(-100vh) scale(0.3);
    opacity: 0;
  }
}





</style> 
<!--div class="card" style="border-left: 2px solid #ff6b6b;
         border-right: 2px solid #00BFFF;
         border-bottom: 2px solid #8A2BE2;
         border-top: 2px solid #008080;
         border-radius: 10px; 
         
         overflow: hidden; max-width: 600px;">
      <div style="background: #4a90e2; color: white; padding: 14px 30px; font-family: 'Poppins', sans-serif; font-weight: 600;">🔐 Secure Login</div-->
<div class="card" style="
  border-left: 2px solid #ff6b6b;
  border-right: 2px solid #00BFFF;
  border-bottom: 2px solid #8A2BE2;
  border-top: 2px solid #008080;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
">


    <div class="price-tag">₹<?= $lifafaData['per_user'] ?></div>
<!--h2 class="title">🎁 <?= htmlspecialchars($lifafaData['title']) ?></h2-->



<!--div class="gradient-wrapper">
  <h2 class="animated-gradient-text">
    <span class="material-icons" style="vertical-align: middle; margin-right: 10px; font-size: 28px;">card_giftcard</span>
    <?= htmlspecialchars($lifafaData['title']) ?>
  </h2>
</div-->
<br>
<div class="flex justify-center items-center w-full gap-3 mb-6">
  <span class="material-icons text-3xl text-indigo-600 animate-bounce">card_giftcard</span>
  <h2 class="text-2xl font-extrabold bg-gradient-to-r from-purple-600 via-pink-500 to-orange-400 bg-clip-text text-transparent font-poppins tracking-wide">
        <?= htmlspecialchars($lifafaData['title']) ?>
  </h2>
</div>

<div id="fireworks-wrap">
  <div class="firework x1"></div>
  <div class="firework x2"></div>
  <div class="firework x3"></div>
  <div class="firework x4"></div>
  <div class="firework x5"></div>
  <div class="firework x6"></div>
  <div class="firework x7"></div>
  <div class="firework x8"></div>
  <div class="firework x9"></div>
  <div class="firework x10"></div>
</div>

  <!--p class="subtext"><?= htmlspecialchars($lifafaData['comment']) ?></p>
  <p class="claim-info">💰 Per User: ₹<?= $lifafaData['per_user'] ?> | 👥 Claimed: <?= $lifafaData['claimed'] ?>/<?= $lifafaData['total_users'] ?></p-->

  <?php
    $notJoinedChannels = array_filter($channelsStatus, fn($joined) => !$joined);
  ?>

<!-- All channels with status -->

<div class="mb-4" style="gap: 9px;">

  <?php if (in_array(false, $channelsStatus)): ?>
    <p style="display: flex; align-items: center; justify-content: center; gap: 6px; color: #555; font-size: 14px; text-align: center;">
      <span class="material-icons" style="font-size: 18px;">link</span>
      Channel Join Status
      <span class="material-icons" style="font-size: 18px;">arrow_downward</span>
    </p>
  <?php endif; ?>

  <?php foreach ($channels as $channel): ?>
    <?php if (!$channelsStatus[$channel]): ?>
      <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; background: #f0f4f9; padding: 12px 16px; border-radius: 12px; font-family: 'Poppins', sans-serif; box-shadow: 0 2px 6px rgba(0,0,0,0.04); margin-bottom: 10px;">
        
        <div style="display: flex; align-items: center; gap: 10px; font-size: 15px; font-weight: 500; color: #333;">
          <span class="material-icons" style="font-size: 18px; color: #555;">link</span>
          <a href="https://t.me/<?= $channel ?>" target="_blank" style="color: #007bff; text-decoration: none; font-weight: bold;">@<?= $channel ?></a>
          <span style="padding: 6px 14px; background: linear-gradient(135deg, #e53935, #b71c1c); color: white; font-weight: 600; font-size: 9px; border-radius: 8px; text-decoration: none;">Not Joined</span>

          <?php if (!empty($adminInChannels[$channel])): ?>
            <span style="color: #ff9800;">👑 Admin</span>
          <?php endif; ?>
        </div>

        <a href="https://t.me/<?= $channel ?>" target="_blank" style="padding: 6px 14px; background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; font-weight: 600; font-size: 13px; border-radius: 8px; text-decoration: none;">
          Join
        </a>
      </div>
    <?php endif; ?>
  <?php endforeach; ?>

  <?php if (in_array(false, $channelsStatus)): ?>
    <div style="color: #856404; padding: 12px; border-radius: 8px; margin-top: 16px; font-size: 14px; border: 1px solid #ffeeba; font-family: 'Poppins', sans-serif;">
      After joining,
      <a href="https://pavancashloot.xyz/Loots/lifafaw.php?id=<?= htmlspecialchars($lifafaData['id']) ?>" style="color: #0000CD; font-weight: bold;">
        CLICK HERE
      </a>
    </div>
  <?php endif; ?>


<!--
<?php if (empty($notJoinedChannels)): ?>
  <div style=" color: #856404; padding: 12px; border-radius: 8px; margin-top: 16px; font-size: 14px; border: 1px solid #ffeeba; font-family: 'Poppins', sans-serif; ">
    ✅ You’ve joined all channels.
    <a href="https://pavancashloot.xyz/Loots/lifafaw.php?id=<?= htmlspecialchars($lifafaData['id']) ?>" style="color: #856404; font-weight: bold; text-decoration: underline;">
      CLICK HERE
    </a>
  </div>
<?php endif; ?>
-->
  <!-- Not joined warning -->
  <?php if (!empty($notJoinedChannels)): ?>
    <!--div class="mb-4">
      <p class="text-bold text-red-700 mb-2">🚫 You must join all required channels before claiming:</p>
    </div-->
    
  <?php endif; ?>
</div>

  <!-- Claim form -->
  <!--div class="mb-4"
  style="gap: 9px;">
  <?php if ($canAutoClaim): ?>
    <form method="post" class="mt-4" style="display: flex; flex-direction: column; gap: 12px;">
<?php if (!empty($success) && $showAnimation): ?>
  <div style="text-align: center; margin-top: 60px;">
    <p style="font-family: 'Poppins', sans-serif; font-size: 16px; color: #2e7d32; font-weight: 600;">
      <?= $success ?><br>Redirecting in 5 seconds...
    </p>
    <div class="letter-image">
      <div class="animated-mail">
        <div class="back-fold"></div>
        <div class="letter">
          <div class="letter-border"></div>
          <div class="letter-title"></div>
          <div class="letter-context"></div>
          <div class="letter-stamp">
            <div class="letter-stamp-inner"></div>
          </div>
        </div>
        <div class="top-fold"></div>
        <div class="body"></div>
        <div class="left-fold"></div>
      </div>
      <div class="shadow"></div>
    </div>
  </div>

  <script>
    setTimeout(() => {
      window.location.href = "<?= $redirectUrl ?>";
    }, 5000);
  </script>
<?php endif; ?>
        -->


<style>

.letter-image.hovered .animated-mail {
  transform: translateY(50px);
}
.letter-image.hovered .top-fold {
  transform: rotateX(180deg);
  z-index: 0;
}
.letter-image.hovered .letter {
  height: 180px;
}
.letter-image.hovered .shadow {
  width: 250px;
}
    .letter-image {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 200px;
  height: 200px;
  transform: translate(-50%, -50%);
  cursor: pointer;
}
.animated-mail {
  position: absolute;
  height: 150px;
  width: 200px;
  transition: 0.4s;
}
.animated-mail .body {
  position: absolute;
  bottom: 0;
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 0 0 100px 200px;
  border-color: transparent transparent #e95f55 transparent;
  z-index: 2;
}
.animated-mail .top-fold {
  position: absolute;
  top: 50px;
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 50px 100px 0 100px;
  transform-origin: 50% 0%;
  transition: transform 0.4s 0.4s, z-index 0.2s 0.4s;
  border-color: #cf4a43 transparent transparent transparent;
  z-index: 2;
}
.animated-mail .back-fold {
  position: absolute;
  bottom: 0;
  width: 200px;
  height: 100px;
  background: #cf4a43;
  z-index: 0;
}
.animated-mail .left-fold {
  position: absolute;
  bottom: 0;
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 50px 0 50px 100px;
  border-color: transparent transparent transparent #e15349;
  z-index: 2;
}
.animated-mail .letter {
  left: 20px;
  bottom: 0px;
  position: absolute;
  width: 160px;
  height: 60px;
  background: white;
  z-index: 1;
  overflow: hidden;
  transition: 0.4s 0.2s;
}
.animated-mail .letter-border {
  height: 10px;
  width: 100%;
  background: repeating-linear-gradient(-45deg, #cb5a5e, #cb5a5e 8px, transparent 8px, transparent 18px);
}
.animated-mail .letter-title {
  margin-top: 10px;
  margin-left: 5px;
  height: 10px;
  width: 40%;
  background: #cb5a5e;
}
.animated-mail .letter-context {
  margin-top: 10px;
  margin-left: 5px;
  height: 10px;
  width: 20%;
  background: #cb5a5e;
}
.animated-mail .letter-stamp {
  margin-top: 30px;
  margin-left: 120px;
  border-radius: 100%;
  height: 30px;
  width: 30px;
  background: #cb5a5e;
  opacity: 0.3;
}
.shadow {
  position: absolute;
  top: 200px;
  left: 50%;
  width: 400px;
  height: 30px;
  transform: translateX(-50%);
  border-radius: 100%;
  background: radial-gradient(rgba(0, 0, 0, 0.5), transparent, transparent);
}

</style>
        

<!--div class="group11">

  <svg viewBox="0 0 24 24" aria-hidden="true" class="icon11">
  <g>
    <path d="M6.62 10.79a15.53 15.53 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20a1 1 0 0 1-1 1c-9.39 0-17-7.61-17-17a1 1 0 0 1 1-1h3.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57a1 1 0 0 1-.25 1.02l-2.2 2.2z"/>
  </g>
</svg>
  <input class="input11" type="text" name="number_input"  value="<?= htmlspecialchars($number) ?>" placeholder="Enter Your Number" />
</div>

<div class="group11">

  <svg viewBox="0 0 24 24" aria-hidden="true" class="icon11">
  <g>
    <path d="M12 17a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-7h-1V7a5 5 0 0 0-10 0v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2zm-8-3a4 4 0 1 1 8 0v3H10V7zm8 13H6v-8h12v8z"/>
  </g>
</svg>
  <input class="input11" type="text" name="access_code" placeholder="Enter Access Code" />
</div>
<button 
  type="submit"
  name="claim" 
  style="background: linear-gradient(135deg, #00c6ff, #0072ff); 
         color: white; 
         padding: 12px 18px; 
         border: none; 
         border-radius: 10px; 
         font-size: 14px; 
         font-weight: 600; 
         cursor: pointer; 
         display: flex; 
         align-items: center; 
         justify-content: center; 
         gap: 6px; 
         box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
         transition: 0.2s; 
         font-weight: bold;"
  onmouseover="this.style.background='linear-gradient(135deg, #0072ff, #0051d4)';"
  onmouseout="this.style.background='linear-gradient(135deg, #00c6ff, #0072ff)';"
>
  <span class="material-icons">check_circle</span> Claim Lifafa
</button>


    </form>
  <?php else: ?>
    <div style="background: #fff3cd; color: #856404; padding: 12px; border-radius: 8px; margin-top: 16px; font-size:11px;"><?= $error ?></div>
  <?php endif; ?>
    </div>
    <br><br>

<div style="
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  flex-wrap: wrap;
  color: #333;
  background: #fff3cd;
  padding: 14px 20px;
  border-left: 2px solid #ff6b6b;
  border-right: 2px solid #00BFFF;
  border-bottom: 2px solid #8A2BE2;
  border-top: 2px solid #008080;
  border-radius: 10px;
  font-family: 'Poppins', sans-serif;
  font-size: 18px;
  font-weight: 500;
  box-shadow: 0 4px 8px rgba(0,0,0,0.05);
  margin-top: 20px;
">
  <span style="color: #e53935; font-size: 22px; font-weight: bold;"><?= $lifafaData['claimed'] ?></span>
  <span style="font-size: 22px; color: #D2691E; font-weight: bold;">/<?= $lifafaData['total_users'] ?></span>
  <span style="color: #666; font-size: 16px; margin-left: 6px; font-weight: bold;">claimed</span>
</div>

<div style="text-align: center; margin-top: 20px;">
  <p style="font-weight: bold; font-family: 'Poppins', sans-serif; font-size: 13px;">
    Verified as –
    <span style="
      display: inline-flex;
      align-items: center;
      gap: 6px;
    ">
      <span style="
        background: linear-gradient(135deg, #00c853, #43a047);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
        font-size: 18px;
      ">
        <?= htmlspecialchars($number) ?>
      </span>
      <span class="material-icons" style="
        font-size: 20px;
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      ">
        check_circle
      </span>
    </span>
  </p>
</div-->


<!-- Claim form -->
<div class="mb-4" style="gap: 9px;">
<?php if (!empty($success) && $showAnimation): ?>
  <div style="text-align: center; margin-top: 60px;">
    <!--p style="font-family: 'Poppins', sans-serif; font-size: 16px; color: #2e7d32; font-weight: 600;">
      <?= $success ?><br>Redirecting in 5 seconds...
    </p-->
<!-- 🎉 Success Animation -->
<div class="letter-image hovered" style="margin-top: 20px;">
  <div class="animated-mail">
    <div class="back-fold"></div>
    <div class="letter">
      <div class="letter-border"></div>
      <div class="letter-title"></div>
      <div class="letter-context" style="
        font-size: 20px;
        font-weight: bold;
        color: #20B2AA;
        padding: 10px 12px;
        text-align: center;
        line-height: 2.5;
      ">
        <?= $success ?>
      </div>
      <div class="letter-stamp">
        <div class="letter-stamp-inner"></div>
      </div>
    </div>
    <div class="top-fold"></div>
    <div class="body"></div>
    <div class="left-fold"></div>
  </div>
  <div class="shadow"></div>
</div>
    <!-- 🎉 Success Animation >
    <div class="letter-image hovered">
      <div class="animated-mail">
        <div class="back-fold"></div>
        <div class="letter">
          <div class="letter-border"></div>
          <div class="letter-title"></div>
          <div class="letter-context" style="font-size: 13px; font-weight: bold; color: #cb5a5e; padding-left: 5px; padding-top: 6px;">
            <?= $success ?>
          </div>
          <div class="letter-stamp">
            <div class="letter-stamp-inner"></div>
          </div>
        </div>
        <div class="top-fold"></div>
        <div class="body"></div>
        <div class="left-fold"></div>
      </div>
      <div class="shadow"></div>
    </div>
  </div-->

  <!-- 🎵 Success Sound -->
  <audio id="claimSuccessSound" autoplay>
    <source src="data/sounds/win.mp3" type="audio/mpeg">
    Your browser does not support the audio tag.
  </audio>

  <!-- 🎯 JS: Auto Redirect and Play Sound -->
  <script>
    setTimeout(() => {
      window.location.href = "<?= $redirectUrl ?>";
    }, 5000);

    window.addEventListener('DOMContentLoaded', () => {
      document.querySelector('.letter-image')?.classList.add('hovered');

      const audio = document.getElementById('claimSuccessSound');
      if (audio) {
        audio.volume = 1.0;
        audio.play().catch(err => {
          // Some browsers need user interaction first
          console.warn("Autoplay blocked:", err);
        });
      }
    });
  </script>

<?php elseif ($canAutoClaim): ?>
  <!-- ✅ Show Claim Form Only if Allowed -->
  <form method="post" class="mt-4" style="display: flex; flex-direction: column; gap: 12px;">

    <!-- 🔢 Number Field -->
    <div class="group11">
      <svg viewBox="0 0 24 24" aria-hidden="true" class="icon11">
        <g><path d="M6.62 10.79a15.53 15.53 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20a1 1 0 0 1-1 1c-9.39 0-17-7.61-17-17a1 1 0 0 1 1-1h3.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57a1 1 0 0 1-.25 1.02l-2.2 2.2z"/></g>
      </svg>
      <input class="input11" type="text" name="number_input" value="<?= htmlspecialchars($number) ?>" placeholder="Enter Your Number" required />
    </div>

    <!-- 🔐 Access Code Field -->
    <div class="group11">
      <svg viewBox="0 0 24 24" aria-hidden="true" class="icon11">
        <g><path d="M12 17a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm6-7h-1V7a5 5 0 0 0-10 0v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2zm-8-3a4 4 0 1 1 8 0v3H10V7zm8 13H6v-8h12v8z"/></g>
      </svg>
      <input class="input11" type="text" name="access_code" placeholder="Enter Access Code" required />
    </div>

    <!-- 🚀 Claim Button -->
    <button 
      type="submit"
      name="claim" 
      style="background: linear-gradient(135deg, #00c6ff, #0072ff); 
            color: white; 
            padding: 12px 18px; 
            border: none; 
            border-radius: 10px; 
            font-size: 14px; 
            font-weight: bold; 
            cursor: pointer; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 6px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
            transition: 0.2s;"
      onmouseover="this.style.background='linear-gradient(135deg, #0072ff, #0051d4)';"
      onmouseout="this.style.background='linear-gradient(135deg, #00c6ff, #0072ff)';"
    >
      <span class="material-icons">check_circle</span> Claim Lifafa
    </button>
  </form>

<?php else: ?>
  <!-- ❌ Claim Error -->
  <div style="background: #fff3cd; color: #856404; padding: 12px; border-radius: 8px; margin-top: 16px; font-size: 12px;">
    <?= $error ?>
  </div>
<?php endif; ?>
</div>

<!-- 👥 Claimed Count -->
<div style="display: flex; justify-content: center; align-items: center; text-align: center; flex-wrap: wrap; color: #333; background: #fff3cd; padding: 14px 20px; border-left: 2px solid #ff6b6b; border-right: 2px solid #00BFFF; border-bottom: 2px solid #8A2BE2; border-top: 2px solid #008080; border-radius: 10px; font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 500; box-shadow: 0 4px 8px rgba(0,0,0,0.05); margin-top: 20px;">
  <span style="color: #e53935; font-size: 22px; font-weight: bold;"><?= $lifafaData['claimed'] ?></span>
  <span style="font-size: 22px; color: #D2691E; font-weight: bold;">/<?= $lifafaData['total_users'] ?></span>
  <span style="color: #666; font-size: 16px; margin-left: 6px; font-weight: bold;">claimed</span>
</div>

<!-- 🪪 User Verified Badge -->
<div style="text-align: center; margin-top: 20px;">
  <p style="font-weight: bold; font-family: 'Poppins', sans-serif; font-size: 13px;">
    Verified as –
    <span style="display: inline-flex; align-items: center; gap: 6px;">
      <span style="background: linear-gradient(135deg, #00c853, #43a047); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; font-size: 18px;">
        <?= htmlspecialchars($number) ?>
      </span>
      <span class="material-icons" style="font-size: 20px; background: linear-gradient(135deg, #00c6ff, #0072ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        check_circle
      </span>
    </span>
  </p>
</div>
</div>



  
  <!--script>
  window.addEventListener("DOMContentLoaded", function () {
    const msg = localStorage.getItem('lifafa_success');
    if (msg) {
      document.getElementById('lifafa-alert-text').textContent = msg;
      document.getElementById('lifafa-alert').classList.remove('hidden');
      localStorage.removeItem('lifafa_success');

      // Auto-close after 4 seconds (optional)
      setTimeout(() => {
        document.getElementById('lifafa-alert').classList.add('hidden');
      }, 4000);
    }
  });
</script>
  <script>
    const successMsg = localStorage.getItem('lifafa_success');
    if (successMsg) {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: successMsg,
        confirmButtonColor: '#10B981'
      });
      localStorage.removeItem('lifafa_success');
    }
  </script-->

</main>

	
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