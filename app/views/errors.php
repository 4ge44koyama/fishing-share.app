<div class="alert alert-danger" role="alert">
    <ul class="error_list">
        <?php foreach ($errors as $error): ?>
        <li><?php echo $this->escape($error); ?></li>
        <?php endforeach; ?>
    </ul>
</div>