<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
        echo $this->Form->input('role_id');
		echo $this->Form->input('is_active');
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('username');
		echo $this->Form->input('gender',array('options'=>array('Male'=>'Male','Female'=>'Female')));
		echo $this->Form->input('password');
		echo $this->Form->input('email_address');
		echo $this->Form->input('date_of_birth');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Roles'), array('controller' => 'roles', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Role'), array('controller' => 'roles', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Profile Pictures'), array('controller' => 'profile_pictures', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Profile Picture'), array('controller' => 'profile_pictures', 'action' => 'add')); ?> </li>
	</ul>
</div>
