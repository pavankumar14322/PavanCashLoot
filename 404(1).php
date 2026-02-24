<?php
http_response_code(404); // Set HTTP status code
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 Not Found</title>
<style>
/* Reset and body */
* {margin:0; padding:0; box-sizing:border-box;}
body {
    font-family: 'Poppins', sans-serif;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    color:#fff;
    overflow:hidden;
}

/* Container */
.container {
    text-align:center;
    position:relative;
}

/* Animated 404 */
h1 {
    font-size:12rem;
    font-weight: 900;
    background: linear-gradient(90deg, #ff4c4c, #ffc600);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: float 2s ease-in-out infinite;
    position: relative;
}

/* Floating animation */
@keyframes float {
    0%,100% {transform: translateY(0);}
    50% {transform: translateY(-25px);}
}

/* Glow effect */
h1::after {
    content: "404";
    position: absolute;
    top:0; left:0;
    right:0; bottom:0;
    color: #ff4c4c;
    text-shadow: 0 0 20px #ff4c4c, 0 0 40px #ffc600;
    opacity:0.3;
    z-index:-1;
}

/* Subtext */
p {
    font-size:1.8rem;
    margin:20px 0 40px 0;
    animation: fadeIn 2s ease forwards;
    opacity:0;
}

/* Fade in animation */
@keyframes fadeIn {
    to {opacity:1;}
}

/* Button */
a {
    text-decoration:none;
    color:white;
    background:#ff4c4c;
    padding:14px 40px;
    border-radius:50px;
    font-weight:bold;
    font-size:1.1rem;
    display:inline-block;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(255,76,76,0.4);
}
a:hover {
    background:#ff0000;
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(255,76,76,0.5);
}

/* Floating particles */
.particle {
    position:absolute;
    background: rgba(255,255,255,0.1);
    border-radius:50%;
    pointer-events:none;
    animation: drift 10s linear infinite;
}

@keyframes drift {
    0% {transform: translateY(0) rotate(0deg);}
    100% {transform: translateY(-120vh) rotate(360deg);}
}
</style>
</head>
<body>
<div class="container">
    <h1>404</h1>
    <p>Oops! The page you are looking for doesn't exist.</p>
    <a href="../index.php">Go Back Home</a>
</div>

<script>
// Generate floating particles
for(let i=0;i<30;i++){
    let particle = document.createElement('div');
    particle.classList.add('particle');
    let size = Math.random()*10+5;
    particle.style.width = size + 'px';
    particle.style.height = size + 'px';
    particle.style.left = Math.random()*100 + 'vw';
    particle.style.animationDuration = (Math.random()*5 + 5) + 's';
    particle.style.opacity = Math.random()*0.5 + 0.2;
    document.body.appendChild(particle);
}
</script>
</body>
</html>