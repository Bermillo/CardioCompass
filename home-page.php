<?php
session_start();
if (isset($_SESSION['username'])) {  // if already logged in
    header("Location: customer-page/welcome-page.php");
    exit();

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap');
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel=stylesheet href="/CardioCompass/Styles/home.css">
    <link rel="icon" type="png" href="media/cardio-compass-bg-logo.png">
    <title>Home</title>
</head>

<body>
    <div class="navigation-bar">
        <div class="logo-text">
            <img class="website-logo" src="/CardioCompass/media/cardio-compass-bg-logo.png" alt="">
            <a href=home-page.php>CardioCompass</a> 
        </div>

        <div class="user-section">
            <a href="registration-page.php">Sign-up</a>|
            <a href="login-page.php">Sign-in</a>
        </div>
    </div>

    <section class="hero-section">
        <div class="video-section">
            <div class="vid-with-gradient">
                <video autoplay loop muted plays-inline class="bg-video">
                    <source src="/CardioCompass/media/bg-video-loop.mp4">
                </video>
            </div>
            <div class="gradient"></div>
        </div>
        <div class="header">
            <h2 class="hd">Your path to a healthier heart starts here!</h2>
            <button onclick="redirect()">Register Now</button>

        </div>
    </section>
    <section class="about-the-project">
        <div class="container">
            <div class="left-col">
                <h1>About the Project</h1>
                <p><strong class="emphasized-text">CardioCompass</strong> is a web-based application, heart disease prediction platform
                    designed to help individuals to take control of the cardiovascular health.
                    <p><strong class = "emphasized-text">For Patients:</strong> It serves as a tool to help patients make informed decisions
                    about their heart health, offering recommendations to prevent disease progression and manage existing diseases.
                    <p><strong class = "emphasized-text">For Healthcare Providers:</strong> Cardiocompass provides Healthcare
                    professionals with an integrated platform to support patient care. They can access an administrative portal
                    to monitor patient data, progress, and support preventive care efforts. 
                    <p><strong class="emphasized-text">Developed by De La Salle University - Dasmariñas Students:</strong> What makes CardioCompass even more special is that it’s the result of the hard work and dedication of undergraduate students from De La Salle University - Dasmariñas. These students have worked tirelessly to develop a tool that combines the latest advancements in healthcare technology with a deep understanding of the challenges patients and providers face when it comes to managing heart health. This platform is a testament to their commitment to making a positive impact on healthcare, and it reflects their passion for creating solutions that truly help people.
                </p>
            </div>
    </section>
    <section class="info-section">
        <hr>
        <div class="info-header">
            <h1>Programmers</h1>
        </div>
        <div class="wrapper">
            <div class="card">
                <div class="card-img">
                    <img class = "image1" src = "/CardioCompass/media/Adlei.jpg" alt = "Adlei">
                </div>
                <ul class="social-media">
                    <a href="https://twitter.com/haimadoods">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
                                <path d="M 11 4 C 7.134 4 4 7.134 4 11 L 4 39 C 4 42.866 7.134 46 11 46 L 39 46 C 42.866 46 46 42.866 46 39 L 46 11 C 46 7.134 42.866 4 39 4 L 11 4 z M 13.085938 13 L 21.023438 13 L 26.660156 21.009766 L 33.5 13 L 36 13 L 27.789062 22.613281 L 37.914062 37 L 29.978516 37 L 23.4375 27.707031 L 15.5 37 L 13 37 L 22.308594 26.103516 L 13.085938 13 z M 16.914062 15 L 31.021484 35 L 34.085938 35 L 19.978516 15 L 16.914062 15 z">
                                </path>
                            </svg>
                        </li>
                    </a>
                    <a href="https://www.facebook.com/adleijed.tan">
                        <li>
                            <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M12 2.03998C6.5 2.03998 2 6.52998 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.84998C10.44 7.33998 11.93 5.95998 14.22 5.95998C15.31 5.95998 16.45 6.14998 16.45 6.14998V8.61998H15.19C13.95 8.61998 13.56 9.38998 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96C15.9164 21.5878 18.0622 20.3855 19.6099 18.57C21.1576 16.7546 22.0054 14.4456 22 12.06C22 6.52998 17.5 2.03998 12 2.03998Z">
                                    </path>
                                </g>
                            </svg>
                        </li>
                    </a>
                    <a href="https://github.com/TambaySaKanto">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024" class="icon">
                                <path
                                    d="M950.930286 512q0 143.433143-83.748571 257.974857t-216.283429 158.573714q-15.433143 2.852571-22.601143-4.022857t-7.168-17.115429l0-120.539429q0-55.442286-29.696-81.115429 32.548571-3.437714 58.587429-10.313143t53.686857-22.308571 46.299429-38.034286 30.281143-59.977143 11.702857-86.016q0-69.12-45.129143-117.686857 21.138286-52.004571-4.534857-116.589714-16.018286-5.12-46.299429 6.290286t-52.589714 25.161143l-21.723429 13.677714q-53.174857-14.848-109.714286-14.848t-109.714286 14.848q-9.142857-6.290286-24.283429-15.433143t-47.689143-22.016-49.152-7.68q-25.161143 64.585143-4.022857 116.589714-45.129143 48.566857-45.129143 117.686857 0 48.566857 11.702857 85.723429t29.988571 59.977143 46.006857 38.253714 53.686857 22.308571 58.587429 10.313143q-22.820571 20.553143-28.013714 58.88-11.995429 5.705143-25.746286 8.557714t-32.548571 2.852571-37.449143-12.288-31.744-35.693714q-10.825143-18.285714-27.721143-29.696t-28.306286-13.677714l-11.410286-1.682286q-11.995429 0-16.603429 2.56t-2.852571 6.582857 5.12 7.972571 7.460571 6.875429l4.022857 2.852571q12.580571 5.705143 24.868571 21.723429t17.993143 29.110857l5.705143 13.165714q7.460571 21.723429 25.161143 35.108571t38.253714 17.115429 39.716571 4.022857 31.744-1.974857l13.165714-2.267429q0 21.723429 0.292571 50.834286t0.292571 30.866286q0 10.313143-7.460571 17.115429t-22.820571 4.022857q-132.534857-44.032-216.283429-158.573714t-83.748571-257.974857q0-119.442286 58.88-220.306286t159.744-159.744 220.306286-58.88 220.306286 58.88 159.744 159.744 58.88 220.306286z">
                                </path>
                            </svg>
                        </li>
                    </a>
                </ul>
                <div class="card-info">
                    <p class="title">Adlei</p>
                    <p class="subtitle"> Programmer</p> 
                </div>
            </div>
            <div class="card">
                <div class="card-img">
                    <img class = "image2" src = "/CardioCompass/media/Amiel.jpg" alt = "Amiel">
                </div>
                <ul class="social-media">
                    <a href="https://x.com/AJBermillo">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
                                <path d="M 11 4 C 7.134 4 4 7.134 4 11 L 4 39 C 4 42.866 7.134 46 11 46 L 39 46 C 42.866 46 46 42.866 46 39 L 46 11 C 46 7.134 42.866 4 39 4 L 11 4 z M 13.085938 13 L 21.023438 13 L 26.660156 21.009766 L 33.5 13 L 36 13 L 27.789062 22.613281 L 37.914062 37 L 29.978516 37 L 23.4375 27.707031 L 15.5 37 L 13 37 L 22.308594 26.103516 L 13.085938 13 z M 16.914062 15 L 31.021484 35 L 34.085938 35 L 19.978516 15 L 16.914062 15 z">
                                </path>
                            </svg>
                        </li>
                    </a>
                    <a href="https://www.facebook.com/amiel.bermillo/">
                        <li>
                            <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M12 2.03998C6.5 2.03998 2 6.52998 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.84998C10.44 7.33998 11.93 5.95998 14.22 5.95998C15.31 5.95998 16.45 6.14998 16.45 6.14998V8.61998H15.19C13.95 8.61998 13.56 9.38998 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96C15.9164 21.5878 18.0622 20.3855 19.6099 18.57C21.1576 16.7546 22.0054 14.4456 22 12.06C22 6.52998 17.5 2.03998 12 2.03998Z">
                                    </path>
                                </g>
                            </svg>
                        </li>
                    </a>
                    <a href="https://github.com/Bermillo">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024" class="icon">
                                <path
                                    d="M950.930286 512q0 143.433143-83.748571 257.974857t-216.283429 158.573714q-15.433143 2.852571-22.601143-4.022857t-7.168-17.115429l0-120.539429q0-55.442286-29.696-81.115429 32.548571-3.437714 58.587429-10.313143t53.686857-22.308571 46.299429-38.034286 30.281143-59.977143 11.702857-86.016q0-69.12-45.129143-117.686857 21.138286-52.004571-4.534857-116.589714-16.018286-5.12-46.299429 6.290286t-52.589714 25.161143l-21.723429 13.677714q-53.174857-14.848-109.714286-14.848t-109.714286 14.848q-9.142857-6.290286-24.283429-15.433143t-47.689143-22.016-49.152-7.68q-25.161143 64.585143-4.022857 116.589714-45.129143 48.566857-45.129143 117.686857 0 48.566857 11.702857 85.723429t29.988571 59.977143 46.006857 38.253714 53.686857 22.308571 58.587429 10.313143q-22.820571 20.553143-28.013714 58.88-11.995429 5.705143-25.746286 8.557714t-32.548571 2.852571-37.449143-12.288-31.744-35.693714q-10.825143-18.285714-27.721143-29.696t-28.306286-13.677714l-11.410286-1.682286q-11.995429 0-16.603429 2.56t-2.852571 6.582857 5.12 7.972571 7.460571 6.875429l4.022857 2.852571q12.580571 5.705143 24.868571 21.723429t17.993143 29.110857l5.705143 13.165714q7.460571 21.723429 25.161143 35.108571t38.253714 17.115429 39.716571 4.022857 31.744-1.974857l13.165714-2.267429q0 21.723429 0.292571 50.834286t0.292571 30.866286q0 10.313143-7.460571 17.115429t-22.820571 4.022857q-132.534857-44.032-216.283429-158.573714t-83.748571-257.974857q0-119.442286 58.88-220.306286t159.744-159.744 220.306286-58.88 220.306286 58.88 159.744 159.744 58.88 220.306286z">
                                </path>
                            </svg>
                        </li>
                    </a>
                </ul>
                <div class="card-info">
                    <p class="title">Amiel</p>
                    <p class="subtitle"> Programmer</p>
                </div>
            </div>
            <div class="card">
                <div class="card-img">
                    <div>
                         <img class = "image3" src = "/CardioCompass/media/Nikki.jpg" alt = "Nikki">
                    </div>
                </div>
                <ul class="social-media">
                    <a href="https://x.com/neon_ballss">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
                                <path d="M 11 4 C 7.134 4 4 7.134 4 11 L 4 39 C 4 42.866 7.134 46 11 46 L 39 46 C 42.866 46 46 42.866 46 39 L 46 11 C 46 7.134 42.866 4 39 4 L 11 4 z M 13.085938 13 L 21.023438 13 L 26.660156 21.009766 L 33.5 13 L 36 13 L 27.789062 22.613281 L 37.914062 37 L 29.978516 37 L 23.4375 27.707031 L 15.5 37 L 13 37 L 22.308594 26.103516 L 13.085938 13 z M 16.914062 15 L 31.021484 35 L 34.085938 35 L 19.978516 15 L 16.914062 15 z">
                                </path>
                            </svg>
                        </li>
                    </a>
                    <a href="https://www.facebook.com/nicoleifaith.abot">
                        <li>
                            <svg fill="#000000" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path d="M12 2.03998C6.5 2.03998 2 6.52998 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.84998C10.44 7.33998 11.93 5.95998 14.22 5.95998C15.31 5.95998 16.45 6.14998 16.45 6.14998V8.61998H15.19C13.95 8.61998 13.56 9.38998 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96C15.9164 21.5878 18.0622 20.3855 19.6099 18.57C21.1576 16.7546 22.0054 14.4456 22 12.06C22 6.52998 17.5 2.03998 12 2.03998Z">
                                    </path>
                                </g>
                            </svg>
                        </li>
                    </a>
                    <a href="https://github.com/NikkiAbot">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024" class="icon">
                                <path
                                    d="M950.930286 512q0 143.433143-83.748571 257.974857t-216.283429 158.573714q-15.433143 2.852571-22.601143-4.022857t-7.168-17.115429l0-120.539429q0-55.442286-29.696-81.115429 32.548571-3.437714 58.587429-10.313143t53.686857-22.308571 46.299429-38.034286 30.281143-59.977143 11.702857-86.016q0-69.12-45.129143-117.686857 21.138286-52.004571-4.534857-116.589714-16.018286-5.12-46.299429 6.290286t-52.589714 25.161143l-21.723429 13.677714q-53.174857-14.848-109.714286-14.848t-109.714286 14.848q-9.142857-6.290286-24.283429-15.433143t-47.689143-22.016-49.152-7.68q-25.161143 64.585143-4.022857 116.589714-45.129143 48.566857-45.129143 117.686857 0 48.566857 11.702857 85.723429t29.988571 59.977143 46.006857 38.253714 53.686857 22.308571 58.587429 10.313143q-22.820571 20.553143-28.013714 58.88-11.995429 5.705143-25.746286 8.557714t-32.548571 2.852571-37.449143-12.288-31.744-35.693714q-10.825143-18.285714-27.721143-29.696t-28.306286-13.677714l-11.410286-1.682286q-11.995429 0-16.603429 2.56t-2.852571 6.582857 5.12 7.972571 7.460571 6.875429l4.022857 2.852571q12.580571 5.705143 24.868571 21.723429t17.993143 29.110857l5.705143 13.165714q7.460571 21.723429 25.161143 35.108571t38.253714 17.115429 39.716571 4.022857 31.744-1.974857l13.165714-2.267429q0 21.723429 0.292571 50.834286t0.292571 30.866286q0 10.313143-7.460571 17.115429t-22.820571 4.022857q-132.534857-44.032-216.283429-158.573714t-83.748571-257.974857q0-119.442286 58.88-220.306286t159.744-159.744 220.306286-58.88 220.306286 58.88 159.744 159.744 58.88 220.306286z">
                                </path>
                            </svg>
                        </li>
                    </a>
                </ul>
                <div class="card-info">
                    <p class="title">Nikki</p>
                    <p class="subtitle"> Programmer</p>
                </div>
            </div>
        </div>    
    </section>
    <footer class="footer">
        <div class="footer-top">
            <div class="footer-column">
                <a href="#" class="link">About Us</a>
                <a href="tel:+63 912 345 6789" class="link">Contact Us: +63 912 345 6789</a>
            </div>
            <div class="footer-column">
                <a href="\CardioCompass\terms&conditions.php" class="link">Terms & Conditions</a>
                <a href="\CardioCompass\privacy-policy.php" class="link">Privacy Policy</a>
            </div>
            <div class="footer-column">
                <a href="">FAQs</a>
                <a href="\CardioCompass\privacy-policy.php" class="link">Support: <a
                        href="mailto:anchormed@support.com">cardiocompass@support.com</a></a>
            </div>

        </div>
        </div>
        <div class="footer-bottom">
            <div class="social-icon">
                <a href="https://www.facebook.com/zorah.19" class="fa fa-facebook facebook link"></a>
                <a href="https://x.com/_IUofficial" class="fa fa-twitter twitter link"></a>
                <a href="https://www.instagram.com/pookie_bear_fanpage_/" class="fa fa-instagram instagram link"></a>
                <a href="https://www.linkedin.com" class="fa fa-linkedin linkedin link"></a>
            </div>
            <hr>
            <div class="copy-right">
                <p>&copy; 2024 CardioCompass. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        function redirect() {
            window.location.href = 'registration-page.php';
        }
    </script>
</body>

</html>