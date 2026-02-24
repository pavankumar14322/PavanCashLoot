<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

/*/ If already logged in, redirect to dashboard
if (isset($_SESSION['user']) && !empty($_SESSION['user']['number'])) {
    header('Location: dashboard.php');
    exit;
}*/

$pathnames = "Loots";

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
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	
  <title>Pavan Cash Loot ~ Login / Register</title>
    <meta name="description" content="Pavan Cash Loot , Advanced Lifafa Giveaway maker site, Payment Gateway, Play Games to earn Money, Paytm Dice Toss Form Giveaway, Lifafa with Refer & Earn, Free Cash Loot Website, Refer Earn Bots With Upi Cash Instant. Create Lifafa, Generate Claim Codes, Transfer Wallet to Wallet, Connect Telegram Bot API, Indian Trusted Earning Web | Pavan Cash Loot" />
   
   
    <meta property="og:title" content="Pavan Cash Loot ~ Login / Register"/>
    <meta property="og:description" content="Pavan Cash Loot , Advanced Lifafa Giveaway maker site, Payment Gateway, Play Games to earn Money, Paytm Dice Toss Form Giveaway, Lifafa with Refer & Earn, Free Cash Loot Website, Refer Earn Bots With Upi Cash Instant. Create Lifafa, Wallet Transfers, Claim Codes, and More | Pavan Cash Loot" />
    <meta property="og:image" content="https://PavanCashLoot.xyz/Loots/data/images/favicon.png" />
    <meta property="og:url" content="https://PavanCashLoot.xyz/Loots/" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="keywords" content="meta keywords, html tags, meta tags, Google ranking factors , Free Cash, Upi Withdrawal,refer earn system,free cadh Earn.">
	<meta name="author" content="Pavan Cash Loot">
	<meta name="msapplication-TileImage" content="https://www.PavanCashLoot.xyz/Loots/data/images/favicon.png">
    <meta property="og:image" itemprop="image" content="https://www.PavanCashLoot.xyz/Loots/data/images/favicon.png">
    <meta property="og:type" content="website/App">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="300">
    <meta property="og:image:height" content="300">

    <!-- Favicon and Manifest -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#3498db"> <!-- #0f172a -->

	<link rel="canonical" href="https://www.PavanCashLoot.xyz/Loots/" />
	
	
	<link rel="icon" type="image/png" href="https://www.PavanCashLoot.xyz/Loots/data/images/favicon.png">
	<!-- Font Awesome 6.5.0 -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <!-- Google Font - Poppins >

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"-->
    
	<meta name="description" content="Pavan Cash Loot (PCL) is India's #1 Lifafa and Cash Earning Platform. Play, Bet & Earn Real Money with Dice, Scratch, Refer & Earn." />
    <meta name="keywords" content="Pavan Cash Loot, PCL, Pavan Kumar, Login, Register, Lifafa Website, Lifafa, Games, Betting Games, Bet And Game, Cash Loot, Earning Apps, Earning Websites, Free Cash Websites, India Earning, Dice Game, Ludo Win, Refer & Earn, Scratch Card" />
    <meta name="author" content="Pavan Cash Loot" />
    <meta name="theme-color" content="#3498db">

    <!-- Open Graph -->
    <meta property="og:title" content="Pavan Cash Loot – Lifafa & Real Cash Games" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://pavancashloot.xyz/Loots/" />
    <meta property="og:image" content="https://pavancashloot.xyz/Loots/data/images/logo.png" />
    <meta property="og:description" content="India's #1 Cash Earning Platform: Lifafa, Scratch Cards, Dice Games & more. Start earning now!" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Pavan Cash Loot – Bet & Earn Money in India" />
    <meta name="twitter:description" content="PCL offers real cash games like Lifafa, Dice & Scratch. Register now & start earning." />
    <meta name="twitter:image" content="https://pavancashloot.xyz/Loots/data/images/logo.png" />
    <meta name="google-site-verification" content="hDOTdeaWNNhxhpX40Zah7QWvKV9PNaJT5ZuZ92t6Qmw" />

    <!-- Favicon -->
    <link rel="icon" href="https://pavancashloot.xyz/Loots/data/images/favicon.png" type="image/png" />

    <link rel="apple-touch-icon" href="https://pavancashloot.xyz/Loots/data/images/logo.png" />
  
	<meta name="author" content="Pavan Cash Loot">
	
	<!-- Favicon and Manifest -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="manifest" href="/site.webmanifest">
	<link rel="icon" type="image/png/jpg/x-icon" href="https://pavancashloot.xyz/Loots/data/images/logo.png">

<!-- Add GTM preview activity -->
<activity
  android:name="com.google.android.gms.tagmanager.TagManagerPreviewActivity"
  android:noHistory="true">
  <intent-filter>
    <data android:scheme="tagmanager.c.com.pavancashloot.app" />
    <action android:name="android.intent.action.VIEW" />
    <category android:name="android.intent.category.DEFAULT" />
    <category android:name="android.intent.category.BROWSABLE"/>
  </intent-filter>
</activity>

<data android:scheme="tagmanager.c.com.pavancashloot.app" />
<!-- Google tag (gtag.js) - Global site tag for GA4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-85SWKJJDYW"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){ dataLayer.push(arguments); }
  
  // Initializes tracking with the current timestamp
  gtag('js', new Date());

  // Replace 'G-85SWKJJDYW' with your GA4 Measurement ID if needed
  gtag('config', 'G-85SWKJJDYW');
</script>
    
    <!-- Lucide Icons -->
     <script src="https://unpkg.com/lucide@latest"></script>
     <script>
        lucide.createIcons();
     </script>

     <link rel="stylesheet" href="https://www.PavanCashLoot.xyz/Loots/data/css/styles.css" />
          <!--link rel="stylesheet" href="https://www.PavanCashLoot.xyz/Loots/data/css/dashboard.css" />
         <link rel="stylesheet" href="https://www.PavanCashLoot.xyz/Loots/data/css/body.css" /-->
    <style>

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



    </style>
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

/*.button.btn-submit:disabled {
  background-color: #a5d6a7;
  cursor: not-allowed;
  opacity: 0.7;
}*/
</style>

<style>
    

</style>

</head>
<body>
    

	<header>
      <a onclick="toggleSidebar()">
        <i data-lucide="menu" class="w-6 h-6 text-white-900 text-4xl"></i>
      </a>
		<h1>Login & Register ~ Pavan Cash Loot </h1>
		<a style="display:flex; align-items: center;
  justify-content: space-between;">
	</header>
	
	
  
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
         <img src="https://PavanCashLoot.xyz/Loots/data/images/logo.png" alt="Pavan Cash Loot" class="center-img">
        </div>
        
        <br>
    	<div id="alertBox" style="display: none;"></div>
        <div id="form-login" class="auth-form active">
            <div class="input-box">
                <i class="fas fa-user left-icon"></i>
                <input type="text" id="login_user" placeholder=" " pattern="[6-9][0-9]{9}" 

       required title="Enter 10-digit Indian mobile number starting with 6-9">
                <label>Email or Mobile Number</label>
            </div>
            <div class="input-box">
                <i class="fas fa-lock left-icon"></i>
                <input type="password" id="login_pass" placeholder=" " required>
                <label>Password</label>
                <i class="fas fa-eye toggle-password" onclick="togglePassword(this, 'login_pass')"></i>
            </div>
            <!--button class="button btn-submit" onclick="login()">Login</button-->
<button id="loginBtn" class="button btn-submit" onclick="login()" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
  <span class="spinner" style="display: none;"></span>
<i class="fas fa-sign-in-alt left-icon"></i>
  <span class="btn-text">Login</span>
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
            
        </div>

        <div id="form-register" class="auth-form">
            <div class="input-box">
                <i class="fas fa-user left-icon"></i>
                <input type="text" id="reg_username" placeholder=" " required oninput="this.value = this.value.replace(/[^a-z]/g, '')" 
         maxlength="20">
                <label>Username</label>
            </div>
            <div class="input-box">
                <i class="fas fa-id-card left-icon"></i>
                <input type="text" id="reg_name" placeholder=" " required>
                <label>Full Name</label>
            </div>
            
            <div class="input-box">

                <i class="fas fa-phone left-icon"></i>

                <input type="number" id="reg_number" placeholder=" " pattern="[6-9][0-9]{9}" 
      maxlength="10" required title="Enter 10-digit Indian mobile number starting with 6-9">
                <label>Mobile Number</label>
            </div>
            
            <div class="input-box">
                <i class="fas fa-envelope left-icon"></i>
                <input type="email" id="reg_email" placeholder=" " required>
                <label>Email ID</label>
            </div>
            <div class="input-box">
                <i class="fas fa-lock left-icon"></i>
                <input type="password" id="reg_password" placeholder=" " required>
                <label>Password</label>
                <i class="fas fa-eye toggle-password" onclick="togglePassword(this, 'reg_password')"></i>
            </div>
            
            <!--button class="button btn-submit" onclick="register()">Register</button-->
<button id="registerBtn" class="button btn-submit" onclick="register()" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
  <span class="spinner" style="display: none;"></span>
<i class="fas fa-user-plus left-icon"></i>
  <span class="btn-text">Register</span>
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
    <div class="input-box">
        <i class="fas fa-phone left-icon"></i>
        <input type="text" id="use_number" placeholder=" " pattern="[6-9][0-9]{9}" 

      maxlength="10" required title="Enter 10-digit Indian mobile number starting with 6-9">
        <label>Mobile Number</label>
    </div>

    <!-- New Password Input -->
    <div class="input-box">
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
<button id="resetBtn" class="button btn-submit" onclick="forget()" style="display: flex; align-items: center; justify-content: center; gap: 8px;">
  <span class="spinner" style="display: none;"></span>
<i class="fas fa-unlock-alt left-icon"></i>
  <span class="btn-text">Reset Password</span>
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

function register() {
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
        logo: $('#reg_img').val()
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
</body>
</html>