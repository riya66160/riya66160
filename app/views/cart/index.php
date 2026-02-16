<h1>Your Cart</h1>
<form method="post" action="/index.php?r=cart-update">
  <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
  <table>
    <tr><th>Product</th><th>Size</th><th>Qty</th><th>Line</th><th></th></tr>
    <?php foreach ($items as $item): ?>
      <tr>
        <td><?php echo $item['product']['name']; ?></td>
        <td><?php echo $item['size']; ?></td>
        <td><input type="number" min="1" name="qty[<?php echo $item['product']['id']; ?>]" value="<?php echo $item['qty']; ?>"></td>
        <td>$<?php echo number_format((float)$item['line'], 2); ?></td>
        <td>
          <form method="post" action="/index.php?r=cart-remove">
            <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
            <input type="hidden" name="product_id" value="<?php echo $item['product']['id']; ?>">
            <button>Remove</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
  <button>Update Cart</button>
</form>
<div class="summary">
  <p>Subtotal: $<?php echo number_format((float)$subtotal, 2); ?></p>
  <p>Tax (10%): $<?php echo number_format((float)$tax, 2); ?></p>
  <p>Shipping: $<?php echo number_format((float)$shipping, 2); ?></p>
  <p><strong>Total: $<?php echo number_format((float)$total, 2); ?></strong></p>
  <a href="/index.php?r=checkout" class="btn">Proceed to Checkout</a>
</div>
