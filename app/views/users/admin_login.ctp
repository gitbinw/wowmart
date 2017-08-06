<div class="general_form" id="main_login">
	<div class="login_form">
		<div id="login_error"><?=isset($errorMessage) ? $errorMessage : '';?></div>
 		<?php echo $form->create(null, array('url'=>'#', 'id'=>'Form_UserLogin'));?>
        <?php echo $form->input('email', array('id'=>'email', 'class'=>'validation'));?>
        <?php echo $form->input('password', array('id'=>'password', 'class'=>'validation'));?>
				<div class="clearline_10px"></div>
        <?php echo $form->button('Login', array('id'=>'Login', 'type'=>'button'));?>
    <?php echo $form->end(); ?> 
  </div>
</div>