<?php foreach ($msg_arr as $key => $val): ?>
<div class="alert alert-<?php echo $this->escape($key); ?>" role="alert">
    <?php echo $this->escape($val); ?>
</div>
<?php endforeach; ?>