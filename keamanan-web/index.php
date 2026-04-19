    <?php
    session_start();
    require 'db.php';

    // Cek jika user sudah login
    if (isset($_SESSION['user_id'])) {
        header("Location: notes.php");
        exit();
    }

    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

   
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            // Simpan sesi jika berhasil login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            header("Location: notes.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    }
    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Login - Mini Note-Taking (Vulnerable)</title>
        <style>body { font-family: sans-serif; margin: 40px; } .login-box { max-width: 300px; margin: auto; padding: 20px; border: 1px solid #ccc; }</style>
    </head>
    <body>

    <div class="login-box">
        <h2>Login Aplikasi Note</h2>
        
        <?php if ($error != ''): ?>
            <p style="color:red;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <p>
                <label>Username (Hint SQLi: <code>' OR 1=1 -- -</code> )</label><br>
                <input type="text" name="username" required>
            </p>
            <p>
                <label>Password</label><br>
                <input type="password" name="password" required>
            </p>
            <button type="submit">Login</button>
            <p style="margin-top: 15px; font-size: 14px;">Belum punya akun? <a href="signup.php">Daftar di sini</a></p>
        </form>
    </div>

    </body>
    </html>