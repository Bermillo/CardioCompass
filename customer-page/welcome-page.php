<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <link rel="stylesheet" href="/CardioCompass/Styles/welcome.css">
    <link rel="icon" type="image/png" href="/CardioCompass/media/cardio-compass-bg-logo.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <title>Welcome to CardioCompass</title>
</head>

<body>
    <section>
        <div class="navigation-bar">
            <div class="logo-text">
                <img class="website-logo" src="/CardioCompass/media/cardio-compass-bg-logo.png" alt="Logo">
                <a href="/CardioCompass/customer-page/welcome-page.php">CardioCompass</a>
            </div>
            <div class="user-side">
                <button class="dropbtn solo" onclick="redirectToHome()"> Home</button>
                <div class="dropdown">
                    <button class="dropbtn">Clinics <span
                            class="material-symbols-outlined">arrow_drop_down</span></button>
                    <div class="dropdown-content">
                        <a href="#locations">Branches</a>
                        <a href="#footer">Contacts</a>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="dropbtn">Doctors <span
                            class="material-symbols-outlined">arrow_drop_down</span></button>
                    <div class="dropdown-content">
                        <a href="available-doctors.php">View Doctors</a>
                    </div>
                </div>
                <button class="dropbtn solo" onclick="redirectToDonate()">Donate</button>
                <div class="dropdown">
                    <button class="profile-btn">
                        <span class="material-symbols-outlined">account_circle</span>
                    </button>
                    <div class="dropdown-content">
                        <a href="/CardioCompass/account-page.php">Account</a>
                        <a href="/CardioCompass/logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="hero-section" id="hero-section">
        <div class="video-section">
            <div class="vid-with-gradient">
                <video autoplay loop muted plays-inline class="bg-video">
                    <source src="/CardioCompass/media/bg-video-loop.mp4" type="video/mp4">
                </video>
            </div>
            <div class="gradient"></div>
        </div>
        <div class="header">
            <h2 class="hd">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <div class="inner-section">
                <p>Find Your Way to Optimal Heart Health</p>
                <button onclick="scrollFunctionInfo()">Learn how</button>
            </div>
        </div>
    </section>

    <section class="info-section" id="info">
        <div class="info">
            <img class="img1" src="/CardioCompass/media/doctor-with-patient.jpg" alt="Doctor with patient">
            <div class="info-wrapper">
                <h1>Start your heart health journey now.</h1>
                <p>At CardioCompass, we prioritize your heart health through innovative technology and personalized service.
                    Our dedicated team of healthcare professionals is committed to empowering individuals to understand their
                    cardiovascular risk and make informed decisions for a healthier future.
                    From assessments to tailored lifestyle recommendations, we are here to support you at every stage of your
                    heart health journey.
                    Start your path to a better cardiovascular wellness today and experience the healthcare benefits of CardioCompass.</p> <!--- EDIT -->
            </div>
            <div class="info-wrapper" data-aos="fade-up" data-aos-duration="800">
                <h1>Your Global Companion for Heart Disease Prevention</h1>
                <p>We make it easy for patients around the world to access heart health care from CardioCompass</p>
                <button onclick="redirectToDoctors()">Request Appointment</button>
            </div>
            <img class="img2" data-aos="fade-up" data-aos-duration="800" src="/CardioCompass/media/world-class-care.avif"
                alt="World class care">
            <img class="img3" data-aos="fade-up" data-aos-duration="800" src="/CardioCompass/media/donate-tab.jfif"
                alt="Doctor with patient">
            <div class="info-wrapper" data-aos="fade-up" data-aos-duration="800">
                <h1>Your Support Can Save Lives: Donate Now!</h1>
                <p>Your donations can help us provide critical cardiovascular assessments, educational materials,
                    and personalized support to individuals in need. With your support, we can empower people to
                    take charge of their heart health, prevent diseases, and improve their quality of life.
                    Each contribution helps us reach those who may not have the means to prioritize their cardiovascular
                    well-being. Join us in our mission to transform lives through heart health and make a lasting impact,
                    one heartbeat at a time,
                </p>
                <button onclick="redirectToDonate()">Donate Now</button>

            </div>
        </div>
    </section>

    <div id="locations" class="location-header" data-aos="fade-up" data-aos-duration="800">
        <hr>
        <h1>Branches</h1>
        <div class="wrapper" data-aos="fade-up" data-aos-duration="600">
            <div class="box" data-aos="fade-up" data-aos-duration="600">
                <p>De La Salle University Medical Center</p>
                <img class="img1" data-aos="fade-up" data-aos-duration="600"
                    src="/CardioCompass/media/DLSUMC.jpg" alt="De La Salle University Medical Center">
                <a
                    href="https://www.google.com/maps/place/De+La+Salle+University+Medical+Center/@14.3270591,120.9434311,17z/data=!4m6!3m5!1s0x3397d45537645401:0xe4b3b238d5bdbfd9!8m2!3d14.3271648!4d120.9434273!16s%2Fg%2F11b61qtwbf?entry=ttu&g_ep=EgoyMDI0MTExOS4yIKXMDSoASAFQAw%3D%3D">
                    Check Directions ></a>
            </div>
            <div class="box" data-aos="fade-up" data-aos-duration="600">
                <p>St. Paul Hospital Cavite</p>
                <img class="img2" data-aos="fade-up" data-aos-duration="600" src="/CardioCompass/media/ST.P.jpeg"
                    alt="St. Paul Hospital Cavite">
                <a
                    href="https://www.google.com/maps/place/St.+Paul+Hospital+Cavite/@14.3233209,120.9603737,17z/data=!3m1!4b1!4m6!3m5!1s0x3397d5b5373472ff:0xa2eac1776142141a!8m2!3d14.3233157!4d120.9629486!16s%2Fg%2F1v_vqf_l?entry=ttu&g_ep=EgoyMDI0MTExOS4yIKXMDSoASAFQAw%3D%3D">
                    Check Directions ></a>
            </div>
            <div class="box" data-aos="fade-up" data-aos-duration="600">
                <p>Dasmariñas City Medical Center</p>
                <img class="img1" data-aos="fade-up" data-aos-duration="600" src="/CardioCompass/media/DCMC.jpeg"
                    alt="Dasmariñas City Medical Center">
                <a
                    href="https://www.google.com/maps/place/Dasmari%C3%B1as+City+Medical+Center/@14.3530591,120.9814308,17z/data=!4m6!3m5!1s0x3397d404a10368bd:0xf5bb2e5d77ea867!8m2!3d14.3530591!4d120.9814308!16s%2Fg%2F1ptzpmlpk?entry=ttu&g_ep=EgoyMDI0MTExOS4yIKXMDSoASAFQAw%3D%3D">
                    Check Directions ></a>
            </div>
        </div>
    </div>
    <footer class="footer" id="footer">
        <div class="footer-top">
            <div class="footer-column">
                <a href="#" class="link ">About Us</a>
                <a href="tel:+63 912 345 6789" class="link">Contact Us: +63 912 345 6789</a>
            </div>
            <div class="footer-column">
                <a href="\CardioCompass\terms&conditions.php" class="link">Terms & Conditions</a>
                <a href="\CardioCompass\privacy-policy.php" class="link">Privacy Policy</a>
            </div>
            <div class="footer-column">
                <a href="\CardioCompass\privacy-policy.php" class="link">FAQs</a>
                <a href="\CardioCompass\privacy-policy.php" class="link">Support: <a
                        href="mailto:cardiocompass@support.com">cardiocompass@support.com</a></a>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="divider social-media">
                <a href="https://www.facebook.com/DLSU.Dasmarinas" class="fa fa-facebook link"></a>
                <a href="https://x.com/DLSBrothersDSM" class="fa fa-twitter link"></a>
                <a href="https://www.instagram.com/dlsud_official?igsh=MTkwNDV6MjV0dzdsaw==" class="fa fa-instagram link"></a>
                <a href="https://www.linkedin.com/school/delasalleuniversitydasmarinas" class="fa fa-linkedin link"></a>
            </div>
            <hr>
            <div class="copy-right">
                <p>&copy; 2024 CardioCompass. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <button onclick="topFunction()" class="pageReset" id="backToTopBtn" title="Go to top">
        <span class="material-symbols-outlined">stat_1</span>
    </button>

    <script>
        let backToTopBtn = document.getElementById("backToTopBtn");
        window.onscroll = function () {
            scrollFunction();
        };

        function scrollFunction() {
            if (document.body.scrollTop > 600 || document.documentElement.scrollTop > 600) {
                backToTopBtn.style.display = "block";
            } else {
                backToTopBtn.style.display = "none";
            }
        }

        function topFunction() {
            document.documentElement.scrollTop = 0;
        }

        function scrollFunctionInfo() {
            const element = document.getElementById("info");
            element.scrollIntoView();
        }
        function redirectToDonate() {
            window.location.href = "/CardioCompass/customer-page/donation-page.php";
        }

        function redirectToDoctors() {
            window.location.href = "/CardioCompass/customer-page/available-doctors.php"
        }

        function redirectToHome() {
            const element = document.getElementById("hero-section");
            element.scrollIntoView();
        }

        function redirectToDonate() {
            window.location.href = "/CardioCompass/customer-page/donation-page.php"
        }

    </script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>