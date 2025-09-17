<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Login</h2>
        <p>Please enter your credentials to log in.</p>

        <form action="/login" method="POST">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <div class="auth-footer">
            <a href="/register">Don't have an account? Register</a>
        </div>
    </div>
</div>
