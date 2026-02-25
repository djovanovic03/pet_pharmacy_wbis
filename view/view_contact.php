<?php global $contact_msg; ?>
<page_contact>
    <?php if ($contact_msg) : ?>
        Vratite se na <a href="../index.php">početnu stranu.</a>
    <?php else: ?>
    <form method="post">
        <label>Ime</label>
        <input type="username" name="ime">
        <label>Email</label>
        <input type="email" name="email">
        <label>Poruka</label>
        <textarea name="poruka"></textarea>
        <button>Submit</button>
    </form>
    <?php endif; ?>
</page_contact>