<h1><?= $_page['title'] ?></h1>
<?php  if ($_error) : ?>
    <error>
        <ul>
            <?php foreach ($_error as $_e) : ?>
                <li><?= $_e ?></li>
            <?php endforeach; ?>
        </ul>
    </error>
<?php endif; ?>

<?php if ($_message) : ?>
    <message>
        <ul>
            <?php foreach ($_message as $_m) : ?>
                <li><?= $_m ?></li>
            <?php endforeach; ?>
        </ul>
    </message>
<?php endif; ?>