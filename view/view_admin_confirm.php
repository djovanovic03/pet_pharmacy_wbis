<?php if (($_SESSION['login_status'] ?? 0) && isset($_SESSION['user']) && (int)($_SESSION['user']['is_admin'] ?? 0) == 1): ?>
<form method="POST" class="add-edit-form">
        <p>Da li ste sigurni da želite da obrišete ovaj proizvod?</p>
        <button name="yes">Da</button>
        <button name="no">Ne</button>
    </form>
<?php else: ?>
    <?=$_error[] = "Nemate pristup."?>
<?php endif; ?>
