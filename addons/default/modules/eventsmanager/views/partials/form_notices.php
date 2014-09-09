<?php if(validation_errors()): ?>
<br>
<div class="alert alert-danger">
  <?php echo validation_errors(); ?>
</div>
<?php endif ?>
