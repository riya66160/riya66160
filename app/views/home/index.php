<section class="hero">
  <h1>Step Into Urban Performance</h1>
  <p>Street-ready comfort engineered for every stride.</p>
  <a class="btn" href="/index.php?r=products">Shop Now</a>
</section>

<section>
  <h2>Featured Products</h2>
  <div class="grid">
    <?php foreach ($featured as $item): ?>
      <article class="card">
        <img src="/assets/uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
        <h3><?php echo $item['name']; ?></h3>
        <p>$<?php echo number_format((float)$item['price'], 2); ?></p>
        <a href="/index.php?r=product&id=<?php echo $item['id']; ?>">View</a>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section>
  <h2>Categories</h2>
  <div class="chips">
    <?php foreach ($categories as $cat): ?>
      <a class="chip" href="/index.php?r=products&category=<?php echo $cat['slug']; ?>"><?php echo $cat['name']; ?></a>
    <?php endforeach; ?>
  </div>
</section>

<section>
  <h2>Testimonials</h2>
  <blockquote>"UrbanStep sneakers changed my city runs." — Alex</blockquote>
  <blockquote>"Durable, stylish, and premium feel." — Priya</blockquote>
</section>

<section>
  <h2>Newsletter</h2>
  <form>
    <input type="email" placeholder="Enter your email" required>
    <button type="submit">Subscribe</button>
  </form>
</section>
