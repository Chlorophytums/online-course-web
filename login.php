<?php
require("koneksi.php");
include("divider/session.php");

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (login($conn, $email, $password)) {
        // Ambil user_id dari database setelah login berhasil
        $query = "SELECT id FROM pengguna WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();

        // Simpan user_id ke dalam sesi
        $_SESSION['user_id'] = $user_id;

        if ($_SESSION['usertype'] == 'admin') {
            header("Location: Admin/adminIndex.php");
            exit();
        } else {
            header("Location: student/homepage.php");
            exit();
        }
    } else {
        $loginError = "Email atau password salah.";
    }
}

if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (createAccount($conn, $name, $email, $password)) {
        $signupSuccess = "Akun berhasil dibuat. Silakan login.";
    } else {
        $signupError = "Gagal membuat akun.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
</head>
<body>

    <div class="container" id="container">
        <!-- Login -->
        <div class="form-container sign-in-container">
                <form action="#" method="POST">
                    <h1>Sign in</h1>
                    <?php if(isset($loginError)) { ?>
                        <div class="error"><?php echo $loginError; ?></div>
                    <?php } ?>
                    <div class="infield">
                        <input type="email" placeholder="Email" name="email" required/>
                        <label></label>
                    </div>
                    <div class="infield">
                        <input type="password" placeholder="Password" name="password" required />
                        <label></label>
                    </div>
                    <button type="submit" name="login">Sign In</button>
                </form>
            </div>

        <!-- Register -->
        <div class="form-container sign-up-container">
            <form action="#" method="POST">
                <h1>Create Account</h1>
             
                <?php if(isset($signupSuccess)) { ?>
                    <div class="success"><?php echo $signupSuccess; ?></div>
                <?php } ?>
                <?php if(isset($signupError)) { ?>
                    <div class="error"><?php echo $signupError; ?></div>
                <?php } ?>
               
                <div class="infield">
                    <input type="text" placeholder="Name" name="name" required/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="email" placeholder="Email" name="email" required/>
                    <label></label>
                </div>
                <div class="infield">
                    <input type="password" placeholder="Password" name="password" required/>
                    <label></label>
                </div>
                <button type="submit" name="signup">Sign Up</button>
            </form>
        </div>
        <!-- End Sign Up -->

        <!-- Sign in -->
        

        <div class="overlay-container" id="overlayCon">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button>Sign In</button>
                </div>
                
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button>Sign Up</button>
                </div>
            </div>
            <button id="overlayBtn"></button>
        </div>
    </div>

    <script src="fontawesome/js/all.min.js"></script>

    <!-- js code -->
    <script>
        const container = document.getElementById('container');
        const overlayCon = document.getElementById('overlayCon');
        const overlayBtn = document.getElementById('overlayBtn');

        overlayBtn.addEventListener('click', ()=> {
            container.classList.toggle('right-panel-active');    

            overlayBtn.classList.remove('btnScaled');
            window.requestAnimationFrame( ()=> {
                overlayBtn.classList.add('btnScaled');
            })
        });

    </script>

</body>
</html>