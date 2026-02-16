<h1>All Shoes</h1>
<form method="get" class="filter-bar">
  <input type="hidden" name="r" value="products">
  <select name="category">
    <option value="">All Categories</option>
    <?php foreach ($categories as $cat): ?>
      <option value="<?php echo $cat['slug']; ?>" <?php echo ($_GET['category'] ?? '') === $cat['slug'] ? 'selected' : ''; ?>><?php echo $cat['name']; ?></option>
    <?php endforeach; ?>
  </select>
  <input type="number" name="min" placeholder="Min" value="<?php echo $_GET['min'] ?? ''; ?>">
  <input type="number" name="max" placeholder="Max" value="<?php echo $_GET['max'] ?? ''; ?>">
  <select name="sort">
    <option value="asc">Price Low to High</option>
    <option value="desc" <?php echo ($_GET['sort'] ?? '') === 'desc' ? 'selected' : ''; ?>>Price High to Low</option>
  </select>
  <button>Apply</button>
</form>
<div class="grid">
  <?php foreach ($products as $item): ?>
    <article class="card">
      <img src="/assets/uploads/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
      <h3><?php echo $item['name']; ?></h3>
      <p><?php echo $item['category_name']; ?> · $<?php echo number_format((float)$item['price'], 2); ?></p>
      <a href="/index.php?r=product&id=<?php echo $item['id']; ?>">View details</a>
    </article>
  <?php endforeach; ?>
</div>
<div class="pagination">
  <?php for ($i = 1; $i <= max(1, $pages); $i++): ?>
    <a href="/index.php?r=products&page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
  <?php endfor; ?>
</div>
