<!DOCTYPE HTML>
<html>
    <head>
        <title>Login - ElegantWare</title>
    </head>
    <body>
        <header>
            <nav class="navbar container">

            </nav>
        </header>
        <main>
            <div class="auth-container">
                <div class ="auth-box">
                    <h1 class="auth-title">Login</h1>
                    <form id="loginForm" method="post" action="">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">                        
                            <label for="password">Password</label>                       
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        <div class="auth-links">
                            <p>Don't have an account? <a href="register.php">Register here</a></p>
                        </div>

                    </form>
                </div>
            </div>
        </main>
    </body>