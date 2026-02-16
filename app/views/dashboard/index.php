<h1>My Dashboard</h1>
<form method="post" action="/index.php?r=profile-update">
  <input type="hidden" name="csrf" value="<?php echo Security::csrfToken(); ?>">
  <input type="text" name="name" value="<?php echo $_SESSION['user']['name']; ?>">
  <button>Update Profile</button>
</form>
<h2>Order History</h2>
<table>
  <tr><th>#</th><th>Status</th><th>Total</th><th>Date</th></tr>
  <?php foreach ($orders as $order): ?>
    <tr>
      <td><?php echo $order['id']; ?></td>
      <td><?php echo $order['status']; ?></td>
      <td>$<?php echo number_format((float)$order['total'], 2); ?></td>
      <td><?php echo $order['created_at']; ?></td>
    </tr>
  <?php endforeach; ?>
</table>
