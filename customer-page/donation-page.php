<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate</title>
    <link rel="stylesheet" href="/CardioCompass/Styles/donation-page-style.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap">
    <link rel="icon" type="png" href="/CardioCompass/media/cardio-compass-bg-logo.png">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>

    <div class="return">
        <a href="/CardioCompass/customer-page/welcome-page.php"> <span class="material-symbols-outlined">
                arrow_back_ios
            </span>Go back to home</a>
    </div>
    <div class="container">
        <div class="left-rectangle">
            <div class="card">
                <img src="/CardioCompass/media/donation-image.webp" alt="Image">
                <div class="card-content">
                    <div class="logo-with-header">
                        <h2>You Can Make a Difference Right Now</h2>
                    </div>
                    <p>Your contribution has the power to change lives. By donating today, you're not just giving
                        money—you're giving hope, support, and opportunities to those in need. Your generosity fuels
                        our
                        mission and makes a tangible impact where it matters most. Join us in making a difference.
                        Together, we can create a brighter future for all. Donate now and be the catalyst for
                        positive
                        change.</p>
                </div>
            </div>
        </div>
        <div class="right-rectangle">
            <form class="donation-form" id="account-form" method="post">
                <div class="logo-header">
                    <img src="/CardioCompass/media/cardio-compass-bg-logo.png" type="png" class="logo">
                    <p class="logo-header-p">CardioCompass</p>
                </div>
                <div class="main-content">
                    <h2>Donate</h2>

                    <div class="form-group">
                        <input type="text" id="name" name="name" class="input-field" placeholder="Enter Name" required>
                        <label for="input-field" class="input-label"></label>
                        <span class="input-highlight"></span>
                    </div>
                    <div class="form-group">
                        <input type="text" id="email" name="email" class="input-field" placeholder="Enter Email"
                            required><label for="input-field" class="input-label"></label>

                        <span class="input-highlight"></span>
                    </div>
                    <div class="form-group">
                        <input type="number" id="amount" name="amount" class="input-field" placeholder="Enter Amount"
                            required>
                        <label for="input-field" class="input-label"></label>

                        <span class="input-highlight"></span>
                    </div>
                    <button type="submit" class="submit-btn" id="submit-btn">Donate</button>
            </form>
        </div>
    </div>
    </div>
    <!-- modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" id="modal-close">&times;</span>
            <p>Confirm submission?</p>
            <button id="confirm-btn" class="submit-btn">Confirm</button>
            <button id="cancel-btn" class="submit-btn">Cancel</button>
        </div>
    </div>

    <script>
        var btn = document.getElementById('submit-btn');
        var modal = document.getElementById('myModal');
        var span = document.getElementsByClassName('close')[0];
        var confirmBtn = document.getElementById('confirm-btn');
        var cancelBtn = document.getElementById('cancel-btn');
        var nameField = document.getElementById('name');
        var emailField = document.getElementById('email');
        var amountField = document.getElementById('amount');

        function validateForm() {
            if (nameField.value.trim() !== "" && emailField.value.trim() !== "" && amountField.value.trim() !== "") {
                return true;
            } else {
                alert("Please fill in all required fields.");
            }
        }

        span.onclick = function () {
            modal.style.display = 'none';
        }

        confirmBtn.onclick = function () {
            modal.style.display = 'none';
            document.getElementById('account-form').submit();
            alert("Thank you for your donation!");
        }

        cancelBtn.onclick = function () {
            modal.style.display = 'none';
        }
        btn.onclick = function (event) {
            event.preventDefault();
            if (validateForm()) {
                modal.style.display = "block";
            }
        }

        function redirectToHome() {
            windows.location.href = "/CardioCompass/customer-page/welcome-page.php";
        }

    </script>

</body>

</html>