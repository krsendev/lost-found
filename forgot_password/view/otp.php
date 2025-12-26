<div class="container mt-5">
<form action="../process/verify_otp.php" method="post" class="card p-4">
    <input type="hidden" name="email" value="<?= $_GET['email'] ?>">
    <input type="text" name="otp" class="form-control mb-3" placeholder="Kode OTP" required>
    <button class="btn btn-success">Verifikasi</button>
</form>
</div>