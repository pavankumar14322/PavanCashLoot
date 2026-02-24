<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

/*/ If already logged in, redirect to dashboard
if (isset($_SESSION['user']) && !empty($_SESSION['user']['number'])) {
    header('Location: dashboard.php');
    exit;
}*/

$pathnames = "PCL-STORE";

// Load data from admin/data.json

$filePath = 'Admin/data.json';
if (!file_exists($filePath)) {
    die("❌ Admin/data.json not found.");
}

$data = json_decode(file_get_contents($filePath), true);
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
  <script>
   const switchMode = document.getElementById('switch-mode');

   // ✅ On page load, check saved mode
   if (localStorage.getItem("theme") === "dark") {
       document.body.classList.add("dark");
       switchMode.checked = true; // Keep toggle ON
   } else {
       document.body.classList.remove("dark");
       switchMode.checked = false; // Keep toggle OFF
   }

   // ✅ Toggle & Save
   switchMode.addEventListener("change", function () {
       if (this.checked) {
           document.body.classList.add("dark");
           localStorage.setItem("theme", "dark");
       } else {
           document.body.classList.remove("dark");
           localStorage.setItem("theme", "light");
       }
   });
</script>
<style>div.clickEffect{position:fixed;box-sizing:border-box;border-style:solid;border-color: green blue red;border-radius:100%;animation:clickEffect .4s ease-out;a-index 99999}@keyframes clickEffect{0%{opacity:1;width:.1em;height:.1em;margin:-.25em;border-width:.5rem}100%{opacity:.2;width:15em;height:15em;margin:-7.5em;border-width:.03rem}}</style><script>function clickEffect(e){var d=document.createElement("div");d.className="clickEffect";d.style.top=e.clientY+"px";d.style.left=e.clientX+"px";document.body.appendChild(d);d.addEventListener('animationend',function(){d.parentElement.removeChild(d)}.bind(this))}document.addEventListener('click',clickEffect);</script>
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




       input:focus {
  border: solid green 2px;
  border-radius:20px;
  width:100%;
   box-shadow: 1px 1px 8px grey ;
  transition: width 200ms ease-in, box-shadow 200ms ease-in, border 200ms ease-in, border-radius 2000ms ease-in ;
 
}
    input:invalid {
  border: solid red 2px;
 /* background-color: #FFCCCB;*/
}


    .submit11{
        height: 40px;
        width: 50%;
        border: 0;
        border-radius: 4px;
        margin: 0 auto;
        padding: 0 25px 0 25px;
        background: black;
        font-family: 'Montserrat';
        font-size: 14px;
        font-weight: bold;
        text-transform: capitalize;
        letter-spacing: 0;
        color: #FFFFFF;
        cursor: pointer;
        outline: none;
        box-shadow: 0 2px 5px 0 rgba(0, 0, 100,.2);
    }
    .submit111{
        height: 40px;
        width: 50%;
        border: 0;
        border-radius: 4px;
        margin: 0 auto;
        padding: 0 25px 0 25px;
        background: brown;
        font-family: 'Montserrat';
        font-size: 14px;
        font-weight: bold;
        text-transform: capitalize;
        letter-spacing: 0;
        color: #FFFFFF;
        cursor: pointer;
        outline: none;
        box-shadow: 0 2px 5px 0 rgba(0, 0, 100,.2);
    }
    	    .alert {
  padding: 20px;
  background-color: red;
  color: white;
  opacity: 1;
  transition: opacity 0.6s;
  margin-bottom: 15px;
}

.alert.success {background-color: #04AA6D;}
.alert.info {background-color: #2196F3;}
.alert.warning {background-color: #ff9800;}

.closebtn {
  margin-left: 15px;
  color: white;
  font-weight: bold;
  float: right;
  font-size: 22px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: black;
}

.switches {
  margin: 20px;
}

.switches h1 {
  font-size: 1.5em;
  margin-bottom: 20px;
}

/* ----- end demo code ----- */

.switch {
  display: inline-block;
  height: 34px;
  min-width: 60px;
  position: relative;
  vertical-align: middle;
}

.switch.disabled {
  cursor: default;
  opacity: 0.5;
}

.switch .slider {
  background-color: red;
  border: 1px solid #047aed;
  bottom: 0;
  color: white;
  cursor: pointer;
  display: block;
  height: 34px;
  left: 0;
  padding: 0 20px 5px 40px;
  position: relative;
  right: 0;
  top: 0;
  transition: 0.4s;
}

.switch .slider .on,
.switch .slider .off {
  line-height: 34px;
}

.switch .slider .off {
  display: block;
}

.switch .slider .on {
  display: none;
}

.switch .slider:before {
  background-color: yellow;
  bottom: 4px;
  content: " ";
  height: 26px;
  left: 4px;
  position: absolute;
  transition: all 0.4s;
  width: 26px;
}

.switch .slider.round {
  border-radius: 34px;
}

.switch .slider.round:before {
  border-radius: 50%;
}

.switch input {
  display: none;
}

.switch input:focus + .slider {
  box-shadow: 0 0 1px green;
}

.switch input:checked + .slider {
  background-color: green;
  padding: 0 40px 0 20px;
}

.switch input:checked + .slider:before {
  left: auto;
  right: 4px;
  transition: all 0.4s;
}

.switch input:checked + .slider .on {
  display: block;
}

.switch input:checked + .slider .off {
  display: none;
}

@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap');
/*
* {
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}

a {
	text-decoration: none;
}

li {
	list-style: none;
}

:root {
	--poppins: 'Poppins', sans-serif;
	--lato: 'Lato', sans-serif;

	--light: #F9F9F9;
	--blue: #3C91E6;
	--light-blue: #CFE8FF;
	--grey: #eee;
	--dark-grey: #AAAAAA;
	--dark: #342E37;
	--red: #DB504A;
	--yellow: #FFCE26;
	--light-yellow: #FFF2C6;
	--orange: #FD7238;
	--light-orange: #FFE0D3;
}


/* ===========================
   VARIABLES (Design System)
=========================== */
:root {
  --color-primary: #2196f3;
  --color-primary-light: #00bfff;
  --color-text: #333;
  --color-muted: #666;
  --color-bg: #f5f5f5;
  --color-white: #fff;
  --color-gray: #D3D3D3;
  --font-family: "Arial", sans-serif;

  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 25px;

  --shadow-sm: 0 2px 5px rgba(0,0,0,0.08);
  --shadow-md: 0 2px 8px rgba(0,0,0,0.12);

  --spacing-xs: 6px;
  --spacing-sm: 10px;
  --spacing-md: 12px;
  --spacing-lg: 20px;

  --transition: 0.3s ease;
}

/* ===========================
   GLOBAL STYLES
=========================== */


/* ===========================
   HEADER
=========================== */
.header {
  background: linear-gradient(to right, var(--color-primary), var(--color-primary-light));
  padding: var(--spacing-md) var(--spacing-lg);
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: var(--color-white);
}
.header h2 {
  font-size: 18px;
}
/* ===========================
   TOP CATEGORIES
=========================== */
.top-categories {
  display: flex;
  gap: var(--spacing-sm);
  padding: var(--spacing-sm);
  background: var(--color-white);
  overflow-x: auto;
}
.top-categories div {
  flex: 0 0 auto;
  background: /*#f9f9f9*/none;
  padding: var(--spacing-sm) var(--spacing-lg);
  border-radius: var(--radius-md);
  font-size: 14px;
  font-weight: bold;
  color: var(--color-text);
  box-shadow: var(--shadow-md);
  cursor: pointer;
  transition: var(--transition);
}
.top-categories div:hover {
  background: #e6f3ff;
  color: var(--color-primary);
}

/* ===========================
   SEARCH BAR
=========================== */
.search-bar {
  padding: var(--spacing-sm);
  /*background: var(--color-white);*/
}
.search-bar input {
  width: 100%;
  padding: var(--spacing-md) var(--spacing-lg);
  border-radius: var(--radius-lg);
  border: 1px solid #ddd;
  font-size: 14px;
  outline: none;
}

/* ===========================
   TABS
=========================== */
.tabs {
  display: flex;
  overflow-x: auto;
 /* background: var(--color-white);*/
  border-bottom: 1px solid #ddd;
}
.tabs div {
  padding: var(--spacing-md) var(--spacing-lg);
  font-size: 14px;
  color: var(--color-muted);
  cursor: pointer;
  flex: 0 0 auto;
  transition: var(--transition);
}
.tabs div.active {
  color: var(--color-primary);
  font-weight: bold;
  border-bottom: 2px solid var(--color-primary);
}

/* ===========================
   PRODUCTS GRID
=========================== */
.products {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--spacing-md);
  padding: var(--spacing-md);
}
.product {
  background: var(--color-gray);
  border-radius: var(--radius-sm);
  padding: var(--spacing-sm);
  text-align: center;
  box-shadow: var(--shadow-sm);
  transition: var(--transition);
}
.product:hover {
  transform: translateY(-3px);
}
.product img {
  width: 100%;
  height: 130px;
  object-fit: cover;
  border-radius: var(--radius-sm);
}
.product h4 {
  margin: var(--spacing-sm) 0 4px;
  font-size: 14px;
  font-weight: normal;
  font-weight: bold;
}
.product p {
  margin: 0;
  font-size: 15px;
  color: var(--color-primary);
  font-weight: bold;
}

/* ===========================
   BOTTOM NAVIGATION
=========================== */
.bottom-nav {
  position: fixed;
  bottom: 0;
  width: 100%;
  display: flex;
  justify-content: space-around;
  background: var(--color-white);
  border-top: 1px solid #ddd;
  padding: var(--spacing-sm) 0;
}
.bottom-nav div {
  text-align: center;
  font-size: 13px;
  color: var(--color-muted);
  cursor: pointer;
}
.bottom-nav div.active {
  color: var(--color-primary);
  font-weight: bold;
}

/* Top categories */
.top-categories {
  display: flex;
  gap: 15px;
  margin: 15px 0;
  font-weight: bold;
}
.top-categories div {
  cursor: pointer;
  padding: 8px 12px;
  border-radius: 6px;
  background: #f5f5f5;
  transition: 0.3s;
}
.top-categories div:hover {
  background: #ddd;
}

/* Search bar */
.search-bar {
  margin: 10px 0 20px;
}
.search-bar input {
  width: 100%;
  padding: 10px 15px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 15px;
}

/* Main tabs */
.tabs {
  display: flex;
  gap: 10px;
  border-bottom: 2px solid #ddd;
  margin-bottom: 15px;
}
.tabs .tab {
  padding: 10px 15px;
  cursor: pointer;
  color: #555;
  font-weight: bold;
}
.tabs .tab.active {
  border-bottom: 3px solid #2196f3;
  color: #2196f3;
}

/* Mini tabs (subcategories) */
.mini-tabs {
  display: flex;
  gap: 10px;
  margin: 10px 0;
  flex-wrap: wrap;
}
.mini-tab {
  padding: 6px 12px;
  cursor: pointer;
  border-radius: 20px;
  border: 1px solid #ccc;
  font-size: 14px;
  background: #f9f9f9;
}
.mini-tab.active {
  background: #2196f3;
  color: #fff;
  border-color: #2196f3;
}

/* Tab content */
.tab-content { display: none; }
.tab-content.active { display: block; }

/* Products grid 
.products {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 15px;
}
.product {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  padding: 10px;
  text-align: center;
  transition: transform 0.2s;
}
.product:hover { transform: translateY(-4px); }
.product img {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 8px;
}*/
.product h4 { margin: 8px 0 5px; font-size: 16px; }
.product p { color: #2196f3; font-weight: bold; }
.product small { color: #666; }
	</style>
	
</head>


<body>
    

	<!--header>
      <a onclick="toggleSidebar()">
        <i data-lucide="menu" class="w-6 h-6 text-white-900 text-4xl"></i>
      </a>
		<h1>PCL - STORE </h1>
		<a style="display:flex; align-items: center;
  justify-content: space-between;">
	</header-->
	
	
  
<!-- Loader HTML -->
<div id="loaders-container-03">
  <div class="loader-inner">
    <span class="dot-loader-3"></span>
  </div>
</div>
  
<div class="auth-container">
    <div class="auth-tabs">
        <div id="tab-login" class="active" onclick="switchTab('login')">Login</div>
        <div id="tab-register" onclick="switchTab('register')">Register</div>
        <div id="tab-forget" onclick="switchTab('forget')">Forget</div>
    </div>
    <div class="auth-forms">
       <div>
         <img src="data/images/pcl.jpg" alt="Pavan Cash Loot" class="center-img">
        </div>
        
        <br>
    	<div id="alertBox" style="display: none;"></div>
        <div id="form-login" class="auth-form active">
            <div class="input-box otp-row">
                <i class="fas fa-user left-icon"></i>
                <input type="text" id="login_user" placeholder=" " pattern="[6-9][0-9]{9}" 

       required title="Enter 10-digit Indian mobile number starting with 6-9">
                <label>Email or Mobile Number</label>
            </div>
            <div class="input-box otp-row">
                <i class="fas fa-lock left-icon"></i>
                <input type="password" id="login_pass" placeholder=" " required>
                <label>Password</label>
                <i class="fas fa-eye toggle-password" onclick="togglePassword(this, 'login_pass')"></i>
            </div>
            <!--button class="button btn-submit" onclick="login()">Login</button-->
<button id="loginBtn" class="submit11 btn-submit" onclick="login()" style="display: flex; align-items: center; justify-content: center; gap: 8px;">

<i class="fas fa-sign-in-alt left-icon"></i>
  <span class="btn-text">Login</span>
    <span class="spinner" style="display: none;"></span>
</button>
           
            <!-- OR Divider -->
<div style="display: flex; align-items: center; text-align: center; margin: 20px 0;">
    <hr style="flex: 1; border: none; border-top: 1px solid #ccc;">
    <span style="padding: 0 10px; color: #555; font-weight: bold;">OR</span>
    <hr style="flex: 1; border: none; border-top: 1px solid #ccc;">
</div>

<!-- Login Prompt -->
<div style="text-align: center; margin-top: 10px;">
    <p style="display: inline; margin-right: 5px;">Don't have an account ?</p>
    <p style="display: inline; color: #3498db; cursor: pointer;" id="tab-register" class="active" onclick="switchTab('register')">Register</p>
</div>
<!-- Login Prompt -->
<div style="text-align: center; margin-top: 10px;">
    <p style="display: inline; margin-right: 5px;">Forget Password?</p>
    <p style="display: inline; color: #3498db; cursor: pointer;" id="tab-register" class="active" onclick="switchTab('forget')">Reset Password</p>
</div>
            
        </div>

        <div id="form-register" class="auth-form">
            <div class="input-box otp-row">
                <i class="fas fa-user left-icon"></i>
                <input type="text" id="reg_username" placeholder=" " required oninput="this.value = this.value.replace(/[^a-z]/g, '')" 
         maxlength="20">
                <label>Username</label>
    <div class="otp-btn">
    <span class="btn-text">New</span>
    <span class="spinner" style="display:none;"></span>
  </div>
            </div>
            <div class="input-box otp-row">
                <i class="fas fa-id-card left-icon"></i>
                <input type="text" id="reg_name" placeholder=" " required>
                <label>Full Name</label>
            </div>
            
            <div class="input-box otp-row">

                <i class="fas fa-phone left-icon"></i>

                <input type="number" id="reg_number" placeholder=" " pattern="[6-9][0-9]{9}" 
      maxlength="10" required title="Enter 10-digit Indian mobile number starting with 6-9">
                <label>Mobile Number</label>
            </div>
            
            <!--div class="input-box">
                <i class="fas fa-envelope left-icon"></i>
                <input type="email" id="reg_email" placeholder=" " required>
                <label>Email ID</label>
            </div-->
            <!--div class="input-box">
    <i class="fas fa-envelope left-icon"></i>
    <input type="email" id="reg_email" name="email" placeholder=" " required>
    <label>Email ID</label>
</div-->

<!-- OTP Input >
<div class="input-box">
    <i class="fas fa-key left-icon"></i>
    <input type="text" id="reg_email_otp" name="email_otp" placeholder=" " maxlength="6" required>
    <label>Email OTP</label>
</div-->

<!-- Button to send OTP 
<button type="button" onclick="sendEmailOTP()">Send OTP</button-->

<!-- Input + Button Combo >
<div style=" display: flex; gap: 12px; align-items: center; font-family: 'Poppins', sans-serif; margin: 20px 0;">
  <div style="position: relative; flex: 1;">
    <span class="material-icons" style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); color: #888;">
      key
    </span>
    <input 
      type="text" 
      id="reg_email_otp"
      name="email_otp" 
      placeholder="Enter Email OTP" 
      maxlength="6"
      required
      style="font-weight: bold; width: 100%; padding: 13px 13px 13px 50px; border: 1.5px solid #d0d0d0; border-radius: 10px; font-size: 15px; transition: 0.3s; outline: none;"
      onfocus="this.style.borderColor='#007bff';"
      onblur="this.style.borderColor='#d0d0d0';"
    >
  </div>

  <button 
    type="button"
    onclick="sendEmailOTP()"
    style="background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; padding: 12px 18px; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: 0.2s; font-weight: bold;"
    onmouseover="this.style.background='linear-gradient(135deg, #0072ff, #0051d4)';"
    onmouseout="this.style.background='linear-gradient(135deg, #00c6ff, #0072ff)';"
  >
    <span class="material-icons">add_circle</span> OTP 
  </button>
</div-->

<style>
    .otp-box {
  display: flex;
  gap: 12px;
  align-items: center;
  margin: 20px 0;
  font-family: 'Poppins', sans-serif;
}

.otp-field {
  position: relative;
  flex: 1;
}

.otp-field input {
  font-weight: bold;
  width: 100%;
  padding: 13px 13px 13px 50px;
  border: 1.5px solid #d0d0d0;
  border-radius: 10px;
  font-size: 15px;
  outline: none;
  transition: 0.3s;
}

.otp-field input:focus {
  border-color: #007bff;
}

.otp-icon {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
  color: #888;
}

.otp-box button {
  background: linear-gradient(135deg, #00c6ff, #0072ff);
  color: white;
  padding: 12px 18px;
  border: none;
  border-radius: 10px;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transition: 0.2s;
}

.otp-box button:hover {
  background: linear-gradient(135deg, #0072ff, #0051d4);
}
</style>

<!-- Email Input 
<div class="input-box">
  <i class="fas fa-envelope left-icon"></i>
  <input type="email" id="reg_email" placeholder=" " required>
  <label>Email ID</label>
</div>

<!-- OTP Field + Button
<div class="otp-box">
  <!-- OTP Field (hidden initially)
  <div class="otp-field" id="otpFieldBox" style="display: none;">
    <span class="material-icons otp-icon">key</span>
    <input 
      type="text" 
      id="reg_email_otp"
      placeholder="Enter Email OTP" 
      maxlength="6"
    >
  </div>

  <!-- Send OTP Button 
  <button type="button" id="sendEmailOtpBtn" onclick="sendEmailOTP()">
    <span class="material-icons btn-icon">send</span> 
    <span class="btn-text">OTP</span>
    <span class="spinner" style="display:none;"></span>
  </button>

  <!-- Resend OTP Button (hidden initially)
  <button type="button" id="resendEmailOtpBtn" onclick="resendEmailOTP()" style="display:none;">
    <span class="material-icons btn-icon">refresh</span> 
    <span class="btn-text">Resend</span>
    <span class="spinner" style="display:none;"></span>
  </button>
</div> -->
<!-- Email field >
<div class="input-box">
  <i class="fas fa-envelope left-icon"></i>
  <input type="email" id="reg_email" name="email" placeholder=" " required>
  <label>Email ID</label>
</div>

<!-- OTP Input >
<div id="otpFieldBox" style="display:none;">
  <div class="input-box">
    <i class="fas fa-key left-icon"></i>
    <input type="text" id="reg_email_otp" name="email_otp" placeholder=" " maxlength="6">
    <label>Email OTP</label>
  </div>
  <button type="button" onclick="verifyEmailOTP()" class="verify-btn">Verify OTP</button>
  <i id="verifiedIcon" class="fas fa-check-circle" style="display:none; color:#2ecc71;"></i>
</div>

<!-- Send OTP Button 
<button type="button" id="sendEmailOtpBtn" onclick="sendEmailOTP()">
  <span class="btn-icon">mail</span>
  <span class="btn-text">Send OTP</span>
  <span class="spinner" style="display:none;">⏳</span>
</button-->

<!-- Email + Send OTP -->
<div class="input-box otp-row">
  <i class="fas fa-envelope left-icon"></i>
  <input type="email" id="reg_email" name="email" placeholder=" " required>
  <label>Email ID</label>
  <button type="button" id="sendEmailOtpBtn" onclick="sendEmailOTP()" class="otp-btn">
    <span class="btn-text">Send OTP</span>
    <span class="spinner" style="display:none;"></span>
  </button>
</div>

<!-- OTP + Verify -->
<div id="otpFieldBox" class="input-box otp-row" style="display:none;">
  <i class="fas fa-key left-icon"></i>
  <input type="text" id="reg_email_otp" name="email_otp" placeholder=" " maxlength="6">
  <label>Email OTP</label>
  <!--button type="button" onclick="verifyEmailOTP()" class="otp-btn">Verify</button>
  <i id="verifiedIcon" class="fas fa-check-circle verified-icon"></i-->
</div>

<style>
/* Input + Button combo style */
.otp-row {
  position: relative;
  display: flex;
  align-items: center;
  font-weight: bold;
  gap: 8px;
}

.otp-row input {
  flex: 1;
  padding-right: 90px; /* space for button */
  font-weight: bold;
}

.otp-btn {
  position: absolute;
  right: 8px;
  padding: 6px 12px;
  font-size: 13px;
  border: none;
  border-radius: 6px;
  background: #3498db;
  color: #fff;
  cursor: pointer;
  transition: 0.3s;
  font-weight: bold;
}

.otp-btn:hover {
  background: #2980b9;
  font-weight: bold;
}

.verified-icon {
  position: absolute;
  right: 70px;
  color: #2ecc71;
  display: none;
  font-weight: bold;
}
</style>

<!-- Email Input >
<div class="input-box">
  <i class="fas fa-envelope left-icon"></i>
  <input type="email" id="reg_email" placeholder=" " required>
  <label>Email ID</label>
</div-->

<!-- OTP Field + Button 
<div style="display: flex; gap: 12px; align-items: center; font-family: 'Poppins', sans-serif; margin: 20px 0;">
  <div style="position: relative; flex: 1; display: none;" id="otpFieldBox">
    <span class="material-icons" style="position: absolute; top: 50%; left: 12px; transform: translateY(-50%); color: #888;">
      key
    </span>
    <input 
      type="text" 
      id="reg_email_otp"
      placeholder="Enter Email OTP" 
      maxlength="6"
      style="font-weight: bold; width: 100%; padding: 13px 13px 13px 50px; border: 1.5px solid #d0d0d0; border-radius: 10px; font-size: 15px; transition: 0.3s; outline: none;"
      onfocus="this.style.borderColor='#007bff';"
      onblur="this.style.borderColor='#d0d0d0';"
    >
  </div>

  <button 
    type="button"
    id="sendEmailOtpBtn"
    onclick="sendEmailOTP()"
    style="background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; padding: 12px 18px; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: 0.2s; font-weight: bold;"
    onmouseover="this.style.background='linear-gradient(135deg, #0072ff, #0051d4)';"
    onmouseout="this.style.background='linear-gradient(135deg, #00c6ff, #0072ff)';"
  >
    <span class="material-icons btn-icon">add_circle</span> 
    <span class="btn-text">Send OTP</span>
    <span class="spinner" style="display:none; margin-left:6px;">⏳</span>
  </button>
</div>-->
            <div class="input-box otp-row">
                <i class="fas fa-lock left-icon"></i>
                <input type="password" id="reg_password" placeholder=" " required>
                <label>Password</label>
                <i class="fas fa-eye toggle-password" onclick="togglePassword(this, 'reg_password')"></i>
            </div>
            
            <!--button class="button btn-submit" onclick="register()">Register</button-->
<button id="registerBtn" class="submit11 btn-submit" onclick="register()" style="display: flex; align-items: center; justify-content: center; gap: 8px;">

<i class="fas fa-user-plus left-icon"></i>
  <span class="btn-text">Register</span>
    <span class="spinner" style="display: none;"></span>
</button>

            
<!-- OR Divider -->
<div style="display: flex; align-items: center; text-align: center; margin: 20px 0;">
    <hr style="flex: 1; border: none; border-top: 1px solid #ccc;">
    <span style="padding: 0 10px; color: #555; font-weight: bold;">OR</span>
    <hr style="flex: 1; border: none; border-top: 1px solid #ccc;">
</div>

<!-- Login Prompt -->
<div style="text-align: center; margin-top: 10px;">
    <p style="display: inline; margin-right: 5px;">Already have an account?</p>
    <p style="display: inline; color: #3498db; cursor: pointer;" id="tab-login" class="active" onclick="switchTab('login')">Login</p>
</div>
            
        </div>
<div id="form-forget" class="auth-form">

    <!-- Mobile Number Input -->
    <div class="input-box otp-row">
        <i class="fas fa-phone left-icon"></i>
        <input type="text" id="use_number" placeholder=" " pattern="[6-9][0-9]{9}" 

      maxlength="10" required title="Enter 10-digit Indian mobile number starting with 6-9">
        <label>Mobile Number</label>
    </div>

    <!-- New Password Input -->
    <div class="input-box otp-row">
        <i class="fas fa-lock left-icon"></i>
        <input type="password" id="reset" placeholder=" " required>
        <label>New Password</label>
    </div>

    <!-- Submit Button -->
    <!--button class="button btn-submit" onclick="forget()">
        <span>
            <i class="ri-lock-password-line"></i>
            Reset Password
        </span>
    </button-->
<button id="resetBtn" class="submit11 btn-submit" onclick="forget()" style="display: flex; align-items: center; justify-content: center; gap: 8px;">

<i class="fas fa-unlock-alt left-icon"></i>
  <span class="btn-text">Reset</span>
    <span class="spinner" style="display: none;"></span>
</button>
<!-- OR Divider -->
<div style="display: flex; align-items: center; text-align: center; margin: 20px 0;">
    <hr style="flex: 1; border: none; border-top: 1px solid #ccc;">
    <span style="padding: 0 10px; color: #555; font-weight: bold;">OR</span>
    <hr style="flex: 1; border: none; border-top: 1px solid #ccc;">
</div>

<!-- Login Prompt -->
<div style="text-align: center; margin-top: 10px;">
    <p style="display: inline; margin-right: 5px;">Already have an account?</p>
    <p style="display: inline; color: #3498db; cursor: pointer;" id="tab-login" class="active" onclick="switchTab('login')">Login</p>
</div>

</div>
     </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="data/js/script.js"></script>

<script>
      function switchTab(tab) {



    $('.auth-form').removeClass('active');

    $('.auth-tabs div').removeClass('active');
    $('#form-' + tab).addClass('auth-form active');
    $('#tab-' + tab).addClass('active');
}

function login() {
    const btn = $('#loginBtn');
    const btnText = btn.find('.btn-text');
    const spinner = btn.find('.spinner');

    // Show spinner and disable button
    btn.prop('disabled', true);
    btnText.text('Logging in');
    spinner.show();

    $.post('alldata.php', {
        work: 'login',
        id: $('#login_user').val(),
        pass: $('#login_pass').val()
    }, function(res) {
        showAlert(res.message, res.status === 'success' ? '#2ecc71' : '#e74c3c');

        if (res.status === 'success') {
            setTimeout(() => {
                window.location.href = 'dashboard.php';
            }, 2000);
        } else {
            // Revert spinner if failed
            btn.prop('disabled', false);
            btnText.text('Login');
            spinner.hide();
        }
    }, 'json').fail(function() {
        showAlert('Something went wrong!', '#e74c3c');
        btn.prop('disabled', false);
        btnText.text('Login');
        spinner.hide();
    });
}

/*function register() {
    const btn = $('#registerBtn');
    const btnText = btn.find('.btn-text');
    const spinner = btn.find('.spinner');

    // Show spinner and disable button
    btn.prop('disabled', true);
    btnText.text('Registering...');
    spinner.show();

    $.post('alldata.php', {
        work: 'register',
        username: $('#reg_username').val(),
        name: $('#reg_name').val(),
        number: $('#reg_number').val(),
        email: $('#reg_email').val(),
        password: $('#reg_password').val(),
        logo: $('#reg_img').val(),
        email_otp: $('#reg_email_otp').val()
    }, function(res) {
        showAlert(res.message, res.status === 'success' ? '#2ecc71' : '#e74c3c');

        if (res.status === 'success') {
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            // Reset button if failed
            btn.prop('disabled', false);
            btnText.text('Register');
            spinner.hide();
        }
    }, 'json').fail(function() {
        showAlert('Something went wrong!', '#e74c3c');
        btn.prop('disabled', false);
        btnText.text('Register');
        spinner.hide();
    });
}*/
function register() {
    const btn = $('#registerBtn');
    const btnText = btn.find('.btn-text');
    const spinner = btn.find('.spinner');

    // Show spinner and disable button
    btn.prop('disabled', true);
    btnText.text('Registering...');
    spinner.show();

    const otp = $('#reg_email_otp').val();

    // Check OTP entered
    if (!otp) {
        showAlert('Please verify your Email OTP first', '#e74c3c');
        btn.prop('disabled', false);
        btnText.text('Register');
        spinner.hide();
        return;
    }

    // AJAX request for OTP verification first
    $.post('alldata.php', {
        work: 'verify_email_otp',
        email: $('#reg_email').val(),
        otp: otp
    }, function(verifyRes) {
        if (verifyRes.status !== 'success') {
            showAlert('Invalid or unverified OTP', '#e74c3c');
            btn.prop('disabled', false);
            btnText.text('Register');
            spinner.hide();
            return;
        }

        // ✅ OTP verified → now proceed with registration
        $.post('alldata.php', {
            work: 'register',
            username: $('#reg_username').val(),
            name: $('#reg_name').val(),
            number: $('#reg_number').val(),
            email: $('#reg_email').val(),
            password: $('#reg_password').val(),
            logo: $('#reg_img').val(),
            email_otp: otp
        }, function(res) {
            showAlert(res.message, res.status === 'success' ? '#2ecc71' : '#e74c3c');

            if (res.status === 'success') {
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                // Reset button if failed
                btn.prop('disabled', false);
                btnText.text('Register');
                spinner.hide();
            }
        }, 'json').fail(function() {
            showAlert('Something went wrong!', '#e74c3c');
            btn.prop('disabled', false);
            btnText.text('Register');
            spinner.hide();
        });

    }, 'json').fail(function() {
        showAlert('OTP verification failed!', '#e74c3c');
        btn.prop('disabled', false);
        btnText.text('Register');
        spinner.hide();
    });
}

function togglePassword(el, inputId) {
  const input = document.getElementById(inputId);
  if (input.type === "password") {
    input.type = "text";
    el.classList.remove("fa-eye");
    el.classList.add("fa-eye-slash");
  } else {
    input.type = "password";
    el.classList.remove("fa-eye-slash");
    el.classList.add("fa-eye");
  }
}

function showAlert(message, color = '#2ecc71') {
    const alertBox = document.getElementById('alertBox');
    alertBox.style.backgroundColor = color;
    alertBox.innerText = message;
    alertBox.style.display = 'block';

    setTimeout(() => {
        alertBox.style.display = 'none';
    }, 3000);
}
function forget() {
    const btn = $('#resetBtn');
    const btnText = btn.find('.btn-text');
    const spinner = btn.find('.spinner');

    // Show loading
    btn.prop('disabled', true);
    btnText.text('Resetting...');
    spinner.show();

    $.post('alldata.php', {
        work: 'reset',
        number: $('#use_number').val(),
        password: $('#reset').val()
    }, function(res) {
        showAlert(res.message, res.status === 'success' ? '#2ecc71' : '#e74c3c');

        if (res.status === 'success') {
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            btn.prop('disabled', false);
            btnText.text('Reset Password');
            spinner.hide();
        }
    }, 'json').fail(function() {
        showAlert('Something went wrong!', '#e74c3c');
        btn.prop('disabled', false);
        btnText.text('Reset Password');
        spinner.hide();
    });
}

/*function sendEmailOTP() {
    const btn = $('#sendEmailOtpBtn');   // your button id
    const btnText = btn.find('.btn-text');
    const spinner = btn.find('.spinner');
    const email = $('#reg_email').val();

    if (!email) {
        showAlert('Enter your email first', '#e74c3c');
        return;
    }

    // Show loading
    btn.prop('disabled', true);
    btnText.text('Sending...');
    spinner.show();

    $.post('alldata.php', {
        work: 'send_email_otp',
        email: email
    }, function(res) {
        showAlert(res.message, res.status === 'success' ? '#2ecc71' : '#e74c3c');

        if (res.status === 'success') {
            // maybe auto-focus OTP input
            $('#reg_email_otp').focus();
        }

        btn.prop('disabled', false);
        btnText.text('Send OTP');
        spinner.hide();
    }, 'json').fail(function() {
        showAlert('Something went wrong!', '#e74c3c');
        btn.prop('disabled', false);
        btnText.text('Send OTP');
        spinner.hide();
    });
}*/

/*function sendEmailOTP() {
    const btn = $('#sendEmailOtpBtn');
    const btnText = btn.find('.btn-text');
    const spinner = btn.find('.spinner');
    const email = $('#reg_email').val();

    if (!email) {
        showAlert('Enter your email first', '#e74c3c');
        return;
    }

    // Show loading
    btn.prop('disabled', true);
    btnText.text('Sending...');
    spinner.show();

    $.post('alldata.php', {
        work: 'send_email_otp',
        email: email
    }, function(res) {
        showAlert(res.message, res.status === 'success' ? '#2ecc71' : '#e74c3c');

        if (res.status === 'success') {
            // Show OTP field
            $('#otpFieldBox').show();

            // Change button text → Resend OTP
            btn.find('.btn-icon').text('refresh'); // change icon
            btnText.text('Resend OTP');
        }

        btn.prop('disabled', false);
        spinner.hide();
    }, 'json').fail(function() {
        showAlert('Something went wrong!', '#e74c3c');
        btn.prop('disabled', false);
        btnText.text('Send OTP');
        spinner.hide();
    });
}*/
// === Send Email OTP (your existing function) ===
function sendEmailOTP() {
    const btn = $('#sendEmailOtpBtn');
    const btnText = btn.find('.btn-text');
    const spinner = btn.find('.spinner');
    const email = $('#reg_email').val();

    if (!email) {
        showAlert('Enter your email first', '#e74c3c');
        return;
    }

    // Show loading
    btn.prop('disabled', true);
    btnText.text('Sending...');
    spinner.show();

    $.post('alldata.php', {
        work: 'send_email_otp',
        email: email
    }, function(res) {
        showAlert(res.message, res.status === 'success' ? '#2ecc71' : '#e74c3c');

        if (res.status === 'success') {
            // Show OTP field
            $('#otpFieldBox').show();

            // Change button text → Resend OTP
            btn.find('.btn-icon').text('refresh');
            btnText.text('Resend OTP');

            // Start 60s timer
            startEmailOtpTimer();
        }

        btn.prop('disabled', false);
        spinner.hide();
    }, 'json').fail(function() {
        showAlert('Something went wrong!', '#e74c3c');
        btn.prop('disabled', false);
        btnText.text('Send OTP');
        spinner.hide();
    });
}


// === Verify Email OTP ===
function verifyEmailOTP() {
    const email = $('#reg_email').val();
    const otp = $('#reg_email_otp').val();

    if (!otp) {
        showAlert('Enter OTP first', '#e74c3c');
        return;
    }

    $.post('alldata.php', {
        work: 'verify_email_otp',
        email: email,
        otp: otp
    }, function(res) {
        showAlert(res.message, res.status === 'success' ? '#2ecc71' : '#e74c3c');

        if (res.status === 'success') {
            $('#reg_email_otp').prop('disabled', true);
            $('#sendEmailOtpBtn').prop('disabled', true);
            $('#verifiedIcon').show(); // optional tick mark
        }
    }, 'json').fail(function() {
        showAlert('OTP verification failed!', '#e74c3c');
    });
}


// === OTP Timer (60s cooldown) ===
let emailOtpTimer;
function startEmailOtpTimer() {
    let seconds = 60;
    const btn = $('#sendEmailOtpBtn');
    const btnText = btn.find('.btn-text');

    clearInterval(emailOtpTimer); // reset if running

    emailOtpTimer = setInterval(() => {
        if (seconds > 0) {
            btn.prop('disabled', true);
            btnText.text(`Resend in ${seconds}s`);
            seconds--;
        } else {
            clearInterval(emailOtpTimer);
            btn.prop('disabled', false);
            btnText.text('Resend OTP');
        }
    }, 1000);
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

<!--script>
function sendEmailOTP() {
    let email = document.getElementById("reg_email").value;
    if (!email) {
        alert("Enter your email first");
        return;
    }

    fetch("alldata.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "work=send_email_otp&email=" + encodeURIComponent(email)
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
    });
}
</script--> 
</body>
</html>