<h1>Admin Panel</h1>
<div class="grid stats">
  <div class="card">Products: <?php echo $stats['products']; ?></div>
  <div class="card">Orders: <?php echo $stats['orders']; ?></div>
  <div class="card">Users: <?php echo $stats['users']; ?></div>
  <div class="card">Revenue: $<?php echo number_format((float)$stats['revenue'], 2); ?></div>
</div>

<section>
  <h2>Add Category</h2>
  <form method="post" action="/index.php?r=admin-category-save">
    <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
    <input type="text" name="name" placeholder="Category Name" required>
    <button>Save Category</button>
  </form>
</section>

<section>
  <h2>Add / Edit Product</h2>
  <form method="post" action="/index.php?r=admin-product-save" enctype="multipart/form-data">
    <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
    <input type="hidden" name="id" value="">
    <input type="text" name="name" placeholder="Name" required>
    <textarea name="description" placeholder="Description"></textarea>
    <select name="category_id" required>
      <?php foreach ($categories as $cat): ?><option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option><?php endforeach; ?>
    </select>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="number" name="stock" placeholder="Stock" required>
    <label><input type="checkbox" name="is_featured"> Featured</label>
    <input type="file" name="image" accept="image/*">
    <input type="hidden" name="existing_image" value="default-shoe.jpg">
    <button>Save Product</button>
  </form>
</section>

<section>
  <h2>Products</h2>
  <table>
    <tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Action</th></tr>
    <?php foreach ($products as $p): ?>
      <tr>
        <td><?php echo $p['id']; ?></td>
        <td><?php echo $p['name']; ?></td>
        <td><?php echo $p['category_name']; ?></td>
        <td>$<?php echo number_format((float)$p['price'], 2); ?></td>
        <td><?php echo $p['stock']; ?></td>
        <td>
          <form method="post" action="/index.php?r=admin-product-delete" onsubmit="return confirm('Delete product?');">
            <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
            <button>Delete</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>

<section>
  <h2>Orders</h2>
  <table>
    <tr><th>ID</th><th>User</th><th>Total</th><th>Status</th><th>Update</th></tr>
    <?php foreach ($orders as $o): ?>
      <tr>
        <td><?php echo $o['id']; ?></td>
        <td><?php echo $o['name']; ?></td>
        <td>$<?php echo number_format((float)$o['total'], 2); ?></td>
        <td><?php echo $o['status']; ?></td>
        <td>
          <form method="post" action="/index.php?r=admin-order-status">
            <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
            <input type="hidden" name="id" value="<?php echo $o['id']; ?>">
            <select name="status"><option>pending</option><option>paid</option><option>shipped</option><option>delivered</option></select>
            <button>Update</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</section>
