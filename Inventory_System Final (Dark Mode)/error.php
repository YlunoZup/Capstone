<?php if (isset($error)) : ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if (isset($success)) : ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>