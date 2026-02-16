<h1>Create Account</h1>
<form method="post" action="/index.php?r=register-post">
  <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
  <input type="text" name="name" placeholder="Full Name" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password (min 8 chars)" required>
  <button>Register</button>
</form>
