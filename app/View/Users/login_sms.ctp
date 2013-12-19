<div class="users form">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->create('User'); ?>
<fieldset>
<legend>
<?php echo __('Please enter SMS token'); ?>
</legend>
SMS token : <?php echo $this->Form->input('sms_token'); ?>
<?php 
echo $this->Form->input('username', ['type' => 'hidden']);
echo $this->Form->input('password', ['type' => 'hidden']);
?>
</fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>