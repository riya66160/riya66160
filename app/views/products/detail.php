<article class="detail">
  <img src="/assets/uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
  <div>
    <h1><?php echo $product['name']; ?></h1>
    <p><?php echo $product['description']; ?></p>
    <p>$<?php echo number_format((float)$product['price'], 2); ?> · Stock: <?php echo $product['stock']; ?></p>
    <form method="post" action="/index.php?r=cart-add">
      <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
      <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
      <label>Size
        <select name="size" required>
          <option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option>
        </select>
      </label>
      <label>Qty <input type="number" name="qty" min="1" max="<?php echo $product['stock']; ?>" value="1"></label>
      <button type="submit">Add to Cart</button>
    </form>
  </div>
</article>
<section>
  <h2>Reviews</h2>
  <?php foreach ($reviews as $review): ?>
    <p><strong><?php echo $review['name']; ?></strong> (<?php echo $review['rating']; ?>/5): <?php echo $review['comment']; ?></p>
  <?php endforeach; ?>
</section>
