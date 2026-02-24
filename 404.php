<!-- -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 - Page Not Found</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: #f6f7fb;
      color: #333;
      text-align: center;
    }

    .container {
      max-width: 500px;
      padding: 20px;
    }

    .illustration {
      position: relative;
      margin-bottom: 20px;
    }

    .illustration img {
      max-width: 220px;
    }

    h1 {
      font-size: 2rem;
      margin-bottom: 10px;
      color: #222;
    }

    p {
      color: #666;
      font-size: 1rem;
      margin-bottom: 20px;
    }

    .btn {
      display: inline-block;
      padding: 12px 24px;
      background: #6c63ff;
      color: #fff;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
    }

    .btn:hover {
      background: #5146d9;
    }

    .floating {
      animation: float 2s ease-in-out infinite;
    }

    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-12px); }
      100% { transform: translateY(0px); }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="illustration floating">
      <!-- Replace this with your own image -->
      <img src="https://cdn-icons-png.flaticon.com/512/2748/2748558.png" alt="404">
    </div>
    <h1>404 - Page Not Found</h1>
    <p>Oops! The page you are looking for doesn’t exist or has been moved.</p>
    <a href="/" class="btn">Go Home</a>
  </div>

  <script>
    // Optional: Redirect back home after 10 seconds
    setTimeout(() => {
      window.location.href = "/";
    }, 10000);
  </script>
</body>
</html>