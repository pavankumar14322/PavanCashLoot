<?php

session_start();

$is_logged_in = isset($_SESSION['user']);
$userData = $is_logged_in ? $_SESSION['user'] : [];

function esc($v){ return htmlspecialchars($v ?? '', ENT_QUOTES); }
?>
<!doctype html>
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
  <style>
    :root{
      --accent1: #7c3aed;
      --accent2: #06b6d4;
      --accent3: #f97316;
    }
    html,body{font-family:'Poppins',system-ui,-apple-system,'Segoe UI',Roboto, sans-serif}
    /* small custom animations */
    @keyframes floaty { 0%{ transform: translateY(0) } 50%{ transform: translateY(-10px) } 100%{ transform: translateY(0) } }
    @keyframes slideIn { from{ opacity:0; transform: translateY(14px) } to{ opacity:1; transform: translateY(0) } }
    .floaty { animation: floaty 6s ease-in-out infinite; }
    .slideIn { animation: slideIn 0.6s ease both; }
    /* subtle glass */
    .glass { background: rgba(255,255,255,0.65); backdrop-filter: blur(6px); }
    /* carousel dots */
    .dot { width:10px; height:10px; border-radius:9999px; display:inline-block; }
    /* small accessible focus */
    :focus{ outline: 3px solid rgba(124,58,237,0.18); outline-offset: 2px; }
  </style>
  </head>

<body class="bg-gradient-to-br from-slate-50 to-sky-50 min-h-screen text-slate-800">

  <!-- Top floating decorations -->
  <div class="pointer-events-none fixed inset-0 -z-10">
    <svg class="absolute left-0 top-0 w-[34rem] opacity-10" viewBox="0 0 600 600"><defs><linearGradient id="g1" x1="0" x2="1"><stop offset="0" stop-color="#7c3aed"/><stop offset="1" stop-color="#06b6d4"/></linearGradient></defs><circle cx="200" cy="100" r="180" fill="url(#g1)"/></svg>
  </div>

  <!-- Header -->
  <header class="w-full bg-transparent py-5">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <img src="data/images/pcl.jpg" alt="PCL" class="w-14 h-14 rounded-lg shadow"/>
        <div>
          <div class="text-lg font-extrabold text-slate-900">SMART X LIFAFA</div>
          <div class="text-xs text-slate-500 font-bold">Lifafa · Dice · Scratch · Refer</div>
        </div>
      </div>

      <nav class="flex items-center gap-3">
        <!--a href="#features" class="text-sm text-slate-700 hover:text-slate-900 transition">Features</a>
        <a href="#games" class="text-sm text-slate-700 hover:text-slate-900 transition">Games</a>
        <a href="#faq" class="text-sm text-slate-700 hover:text-slate-900 transition">FAQ</a-->

        <?php if (!$is_logged_in): ?>
          <a href="login.php" class="ml-4 inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-500 text-white shadow-lg hover:scale-[1.02] transition font-extrabold">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
            Login
          </a>
          <!--a href="login.php" class="ml-2 px-4 py-2 rounded-lg border text-sm text-slate-700">Login</a-->
        <?php else: ?>
          <div class="ml-4 flex items-center gap-3">
            <img src="<?= esc($userData['photo'] ?? "data/images/default.png") ?>" alt="avatar" class="w-10 h-10 rounded-full border-2 border-white shadow-sm" />
            <div class="text-sm font-bold text-slate-700">
              <div class="font-medium"><?= esc($userData['name'] ?? 'User') ?></div>
              <a href="dashboard.php" class="text-xs text-slate-500 hover:underline font-semibold">Go to Dashboard</a>
            </div>
          </div>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <!-- Main Hero -->
  <main class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <section class="lg:col-span-2 space-y-6">
      <div class="rounded-2xl glass p-6 shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row items-center gap-6">
          <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 slideIn">Play • Bet • Win — Earn Real Cash</h1>
            <p class="mt-3 text-slate-600">Join lifafas, spin dice, play scratch cards and withdraw instantly via UPI. Fair gameplay & quick payouts.</p>

            <div class="mt-5 flex flex-wrap gap-3">
              <?php if (!$is_logged_in): ?>
                <a href="login.php" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-violet-500 text-white font-semibold shadow hover:scale-[1.02] transition">Create Account</a>
                <a href="login.php" class="px-4 py-3 rounded-lg border text-slate-700">Login</a>
              <?php else: ?>
                <a href="dashboard.php" class="px-5 py-3 rounded-lg bg-gradient-to-r from-green-500 to-emerald-400 text-white font-semibold shadow">Open Dashboard</a>
                <a href="#create-lifafa" class="px-4 py-3 rounded-lg border text-slate-700">Create Giveaway</a>
              <?php endif; ?>

              <button onclick="openQuickPlay()" class="px-4 py-3 rounded-lg border text-slate-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 2a2 2 0 00-2 2v12l4-2 4 2 4-2 4 2V4a2 2 0 00-2-2H4z"></path></svg>
                Quick Play
              </button>
            </div>

            <!-- metrics -->
            <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
              <div class="p-3 rounded-lg bg-white shadow text-center">
                <div class="text-xs text-slate-400">Active Users</div>
                <div class="font-bold text-lg">12.4k</div>
              </div>
              <div class="p-3 rounded-lg bg-white shadow text-center">
                <div class="text-xs text-slate-400">Total Payouts</div>
                <div class="font-bold text-lg">₹2.1M</div>
              </div>
              <div class="p-3 rounded-lg bg-white shadow text-center">
                <div class="text-xs text-slate-400">Games</div>
                <div class="font-bold text-lg">34+</div>
              </div>
              <div class="p-3 rounded-lg bg-white shadow text-center">
                <div class="text-xs text-slate-400">Support</div>
                <div class="font-bold text-lg">24/7</div>
              </div>
            </div>

          </div>

          <div class="w-full md:w-72">
            <!--div class="rounded-xl overflow-hidden shadow-lg transform hover:scale-[1.03] transition floaty">
              <img src="<?= esc("$pathnames/images/b.png") ?>" alt="hero" class="w-full h-56 object-cover">
            </div-->
            <!-- small CTA card -->
            <div class="mt-4 p-4 bg-white rounded-xl shadow flex items-center gap-3">
              <div class="flex-1">
                <div class="text-xs text-slate-400">New User Bonus</div>
                <div class="font-semibold">Get ₹20 on signup</div>
              </div>
              <a href="login.php" class="px-3 py-2 rounded-md bg-indigo-600 text-white text-sm">Claim</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Feature cards -->
      <div id="features" class="grid sm:grid-cols-2 gap-6">
        <div class="p-6 rounded-xl bg-white shadow hover:shadow-2xl transition transform hover:-translate-y-2 slideIn">
          <div class="flex items-start gap-4">
            <div class="p-3 rounded-lg bg-indigo-50">
              <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 .895-4 2v2h8v-2c0-1.105-1.79-2-4-2z"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Secure Wallet</h3>
              <p class="text-sm text-slate-500">Store and withdraw with instant UPI & bank integration with verification.</p>
            </div>
          </div>
        </div>

        <div class="p-6 rounded-xl bg-white shadow hover:shadow-2xl transition transform hover:-translate-y-2 slideIn">
          <div class="flex items-start gap-4">
            <div class="p-3 rounded-lg bg-emerald-50">
              <svg class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2-2 4 4"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Fair Gameplay</h3>
              <p class="text-sm text-slate-500">Transparent, provably-fair results & dispute logs to verify outcomes.</p>
            </div>
          </div>
        </div>

        <div class="p-6 rounded-xl bg-white shadow hover:shadow-2xl transition transform hover:-translate-y-2 slideIn">
          <div class="flex items-start gap-4">
            <div class="p-3 rounded-lg bg-pink-50">
              <svg class="w-6 h-6 text-pink-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Refer & Earn</h3>
              <p class="text-sm text-slate-500">Invite friends, get commissions & extra bonuses on referrals.</p>
            </div>
          </div>
        </div>

        <div class="p-6 rounded-xl bg-white shadow hover:shadow-2xl transition transform hover:-translate-y-2 slideIn">
          <div class="flex items-start gap-4">
            <div class="p-3 rounded-lg bg-yellow-50">
              <svg class="w-6 h-6 text-yellow-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3 8 4-16 3 8h4"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Promotions</h3>
              <p class="text-sm text-slate-500">Daily bonuses, streak rewards & seasonal events with leaderboards.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Popular games carousel -->
      <!--section id="games" class="mt-6">
        <h3 class="text-xl font-bold mb-3">Popular Games</h3>

        <div class="relative">
          <div id="carousel" class="overflow-hidden rounded-xl">
            <div class="flex transition-transform duration-500" style="transform: translateX(0%)">
              <div class="w-full flex-shrink-0">
                <div class="bg-white rounded-xl shadow overflow-hidden">
                  <img src="<?= esc("$pathnames/images/wallet.png") ?>" alt="Dice" class="w-full h-44 object-cover">
                  <div class="p-4">
                    <div class="font-semibold">Dice Toss</div>
                    <div class="text-sm text-slate-500 mt-1">Fast-paced dice challenges with instant results.</div>
                  </div>
                </div>
              </div>

              <div class="w-full flex-shrink-0 px-4">
                <div class="bg-white rounded-xl shadow overflow-hidden">
                  <img src="<?= esc("$pathnames/images/withdraw.png") ?>" alt="Scratch" class="w-full h-44 object-cover">
                  <div class="p-4">
                    <div class="font-semibold">Scratch Cards</div>
                    <div class="text-sm text-slate-500 mt-1">Scratch & reveal rewards instantly.</div>
                  </div>
                </div>
              </div>

              <div class="w-full flex-shrink-0">
                <div class="bg-white rounded-xl shadow overflow-hidden">
                  <img src="<?= esc("$pathnames/images/refer.png") ?>" alt="Lifafa" class="w-full h-44 object-cover">
                  <div class="p-4">
                    <div class="font-semibold">Lifafa Giveaways</div>
                    <div class="text-sm text-slate-500 mt-1">Create giveaways and let users claim with access codes.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-3 flex items-center justify-center gap-2" id="carousel-dots" aria-hidden="true">
            <span class="dot bg-slate-300"></span>
            <span class="dot bg-slate-300/60"></span>
            <span class="dot bg-slate-300/60"></span>
          </div>

        </div>
      </section-->

      <!-- FAQ -->
      <section id="faq" class="mt-6">
        <h3 class="text-xl font-bold mb-3">Frequently Asked Questions</h3>
        <div class="space-y-3">
          <details class="bg-white p-4 rounded-xl shadow" open>
            <summary class="font-semibold cursor-pointer">How fast are withdrawals?</summary>
            <div class="mt-2 text-sm text-slate-600">Most withdrawals via UPI are instant; bank transfers can vary (minutes to a few hours).</div>
          </details>

          <details class="bg-white p-4 rounded-xl shadow">
            <summary class="font-semibold cursor-pointer">Is gameplay fair?</summary>
            <div class="mt-2 text-sm text-slate-600">Yes — we log outcomes and use deterministic/randomized techniques that can be audited.</div>
          </details>

          <details class="bg-white p-4 rounded-xl shadow">
            <summary class="font-semibold cursor-pointer">How do referrals work?</summary>
            <div class="mt-2 text-sm text-slate-600">Share your referral link. When friends sign up and play, you earn a commission based on play.</div>
          </details>
        </div>
      </section>

    </section>

    <!-- Right column -->
    <aside class="space-y-6">
      <!--div id="wallet" class="p-5 rounded-2xl glass shadow-lg">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs text-slate-500">Wallet Balance</div>
            <div class="text-2xl font-extrabold">₹ <span id="wallet-amount">0.00</span></div>
          </div>
          <div class="text-sm text-slate-500">User: <?= esc($userData['number'] ?? 'Guest') ?></div>
        </div>

        <div class="mt-4 flex gap-3">
          <button class="flex-1 px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-violet-500 text-white" onclick="openAddFunds()">Add Funds</button>
          <button class="flex-1 px-4 py-2 rounded-lg border" onclick="openWithdraw()">Withdraw</button>
        </div>

        <div class="mt-3 text-xs text-slate-500">Recent: <span id="recent-activity">No activity</span></div>

        <div class="mt-4 grid gap-2">
          <button class="px-3 py-2 rounded-md bg-yellow-400 hover:brightness-95" onclick="claimDaily()">Claim Daily Bonus</button>
          <button class="px-3 py-2 rounded-md bg-indigo-100 text-indigo-700" onclick="openRefer()">Refer & Earn</button>
        </div>
      </div-->

      <div class="p-5 rounded-2xl bg-white shadow">
        <h4 class="font-bold mb-2">Quick Actions</h4>
        <div class="grid gap-3">
          <a href="#create-lifafa" class="p-3 rounded-lg border hover:bg-slate-50">Create Lifafa</a>
          <a href="#games" class="p-3 rounded-lg border hover:bg-slate-50">Browse Games</a>
          <a href="transactions.php" class="p-3 rounded-lg border hover:bg-slate-50">Transactions</a>
          <a href="https://t.me/smartxlifafa" class="p-3 rounded-lg border hover:bg-slate-50">Contact Support</a>
        </div>
      </div>

      <!--div class="p-5 rounded-2xl bg-white shadow">
        <h4 class="font-bold mb-2">Promotions</h4>
        <div class="text-sm text-slate-600">Daily Bonus: <strong>Claim now</strong></div>
        <div class="mt-3">
          <button class="w-full px-4 py-2 rounded-lg bg-gradient-to-r from-pink-500 to-rose-500 text-white" onclick="openPromo()">View Offers</button>
        </div-->
      </div>

    </aside>
  </main>

  <!-- Footer (single-file include) -->
  <footer class="border-t bg-white mt-10">
    <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col md:flex-row items-center justify-between gap-3 text-sm text-slate-600">
      <div>© <?= date('Y') ?> SMART X LIFAFA — Built with ❤️</div>
      <div class="flex items-center gap-3">
        <a href="terms.php" class="hover:underline">Terms</a>
        <a href="privacy.php" class="hover:underline">Privacy</a>
        <a href="contact.php" class="hover:underline">Contact</a>
      </div>
    </div>
  </footer>

  <!-- Toast container -->
  <div id="toast" class="fixed right-4 bottom-6 space-y-2 z-50"></div>

  <!-- Modals (lightweight) -->
  <div id="modal-root"></div>

<script>
  // --------- Demo data & small utilities ----------
  const loggedIn = <?= $is_logged_in ? 'true' : 'false' ?>;
  // placeholder: replace with real API calls
  async function fetchWallet() {
    // Example fetch to your API:
    // return fetch('/api/wallet.php').then(r => r.json());
    // Demo: simulate
    return new Promise(resolve => setTimeout(()=> resolve({success:true,balance:249.50,recent:'Added ₹50 today'}),500));
  }

  function toast(message, type='info'){
    const id = Date.now();
    const el = document.createElement('div');
    el.className = 'p-3 rounded-lg shadow-lg text-sm bg-white flex items-center gap-3';
    el.innerHTML = '<div class="w-2.5 h-2.5 rounded-full ' + (type==='success' ? 'bg-green-400' : type==='error' ? 'bg-red-400' : 'bg-indigo-400') + '"></div><div>'+message+'</div>';
    el.style.opacity = '0'; el.style.transform='translateY(8px)';
    document.getElementById('toast').appendChild(el);
    setTimeout(()=>{ el.style.transition='all .28s'; el.style.opacity='1'; el.style.transform='translateY(0)'; },10);
    setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(8px)'; setTimeout(()=>el.remove(),300); }, 4000);
  }

  // --------- Initialize wallet demo ----------
  (async function init(){
    const res = await fetchWallet();
    if(res && res.success){
      document.getElementById('wallet-amount').innerText = parseFloat(res.balance).toFixed(2);
      document.getElementById('recent-activity').innerText = res.recent;
    }
  })();

  // --------- Quick actions ----------
  function openAddFunds(){
    if(!loggedIn){ location.href='login.php'; return; }
    // example modal
    openModal('Add Funds', `<p class="text-sm text-slate-600">Add funds via UPI / Gateway.</p>
      <div class="mt-4 flex gap-2">
        <button class="px-4 py-2 rounded bg-indigo-600 text-white" onclick="fakeAddFunds()">Add ₹50</button>
        <button class="px-4 py-2 rounded border" onclick="closeModal()">Cancel</button>
      </div>`);
  }
  function openWithdraw(){ if(!loggedIn){ location.href='login.php'; return; } location.href='cashout.php'; }
  function openQuickPlay(){ openModal('Quick Play', '<p class="text-sm text-slate-600">Choose a quick challenge or lifafa to join — coming soon.</p>'); }
  function openRefer(){ if(!loggedIn){ location.href='login.php'; return; } openModal('Refer & Earn', '<p class="text-sm text-slate-600">Share your referral link with friends to earn rewards.</p>'); }
  function openPromo(){ openModal('Promotions', '<p class="text-sm text-slate-600">Seasonal offers and bonus codes appear here.</p>'); }

  // --------- Fake add funds to demo ----------
function fakeAddFunds(){
    // simulate adding funds
    const cur = parseFloat(document.getElementById('wallet-amount').innerText || 0);
    const added = 50;
    document.getElementById('wallet-amount').innerText = (cur + added).toFixed(2);
    document.getElementById('recent-activity').innerText = `Added ₹${added} on ${new Date().toLocaleString()}`;
    toast('₹'+added+' added to wallet', 'success');
    closeModal();
  }

  // --------- Claim daily (demo) ----------
  function claimDaily(){
    if(!loggedIn){ location.href='login.php'; return; }
    // Replace this with POST to api/claim_daily.php
    toast('Claiming daily bonus...');
    setTimeout(()=> {
      const amount = 5; // demo
      const cur = parseFloat(document.getElementById('wallet-amount').innerText || 0);
      document.getElementById('wallet-amount').innerText = (cur + amount).toFixed(2);
      document.getElementById('recent-activity').innerText = `Daily bonus ₹${amount} on ${new Date().toLocaleString()}`;
      toast('Daily bonus claimed: ₹' + amount, 'success');
    }, 1000);
  }

  // --------- Modal helper ----------
  function openModal(title, html){
    const root = document.getElementById('modal-root');
    root.innerHTML = `
      <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal()"></div>
        <div class="relative w-full max-w-md p-6 bg-white rounded-xl shadow-lg">
          <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">${title}</h3>
            <button class="text-slate-500" onclick="closeModal()">✕</button>
          </div>
          <div class="mt-4">${html}</div>
        </div>
      </div>
    `;
  }
  function closeModal(){ document.getElementById('modal-root').innerHTML = ''; }

  // --------- Simple carousel (auto-rotate) ----------
  (function carousel(){
    const container = document.querySelector('#carousel > div');
    const dots = document.querySelectorAll('#carousel-dots .dot');
    if(!container) return;
    let idx = 0, total = container.children.length;
    function show(i){
      container.style.transform = `translateX(-${i * 100}%)`;
      dots.forEach((d,di)=> d.classList.toggle('bg-slate-300/60', di!==i) );
      dots[i].classList.remove('bg-slate-300/60'); dots[i].classList.add('bg-slate-400');
    }
    show(0);
    setInterval(()=>{ idx = (idx+1) % total; show(idx); }, 3500);
  })();
</script>
</body>
</html>
