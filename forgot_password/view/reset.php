<div class="container mt-5">
<form action="../process/reset_password.php" method="post" class="card p-4">
    <input type="hidden" name="email" value="<?= $_GET['email'] ?>">
    <input type="password" name="password" class="form-control mb-3" placeholder="Password Baru" required>
    <button class="btn btn-warning">Reset</button>
</form>
</div>