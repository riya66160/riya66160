<h1>Login</h1>
<form method="post" action="/index.php?r=login-post">
  <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button>Login</button>
</form>
