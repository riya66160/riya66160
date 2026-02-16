<h1>Checkout</h1>
<form method="post" action="/index.php?r=checkout-post">
  <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
  <label>Address <input type="text" name="address" required></label>
  <label>City <input type="text" name="city" required></label>
  <label>ZIP <input type="text" name="zip" required></label>
  <label>Payment Method
    <select name="payment_method">
      <option value="stripe_simulated">Stripe (Simulated)</option>
      <option value="cod">Cash on Delivery</option>
    </select>
  </label>
  <button>Place Order</button>
</form>
<p>Stripe Public Key Loaded: <?php echo !empty($stripePublicKey) ? 'Yes' : 'No'; ?></p>
