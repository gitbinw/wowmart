<?php
	$checkLogin = "checked";
	$checkNew   = "";
	$showLogin  = "display:block;";
	$showNew    = "display:none;";
	if (isset($errorCode) && $errorCode == 'register_failed') {
		$checkLogin = "";
		$checkNew   = "checked";
		$showLogin  = "display:none;";
		$showNew    = "display:block;";
	}
?>
<div class="checkout_head">
	<div class="checkout_cart" style="margin-left: 60px;"></div>
	<ul>
		<li class="step current">1 <div>Login</div> <div class="delimiter"></div></li>
		<li class="step">2 <div>Billing</div> <div class="delimiter"></div></li>
		<li class="step">3 <div>Delivery</div> <div class="delimiter"></div></li>
		<li class="step">4 <div>Confirm & Payment</div></li>
	</ul>
</div>

<div class="main_title">
	<i>Step 1 - Login</i><br>
</div>
<div class="checkout_form">
	<span class="checkout_info">
		You have to login before purchasing an order.<br><br>
	</span>
	<div id="form_type_login" class="form_type" onclick="switchForm(this);">
		<input type="radio" name="login_type" value="existing" <?=$checkLogin;?> />
		&nbsp<b>I'm a returning customer</b>
	</div>
	<div id="form_login" class="form_main" style="<?=$showLogin;?>">
		<?php echo $form->create(null, array('url'=>'/customers/checkout_login', 'id'=>'Form_UserLogin'));?>
		<?php
   		if (isset($login_errors) && is_array($login_errors)) {
   			echo '<ul class="form_errors">' .
   				   '	<li class="title">Errors:</li>';
   			foreach($login_errors as $key=>$err) {
   					echo '<li>' . $err . '</li>';
   			}
   			echo '</ul>';
   		}
   	?>
			<ul>
				<li>
					<div class="label">Your Email Address:&nbsp;*&nbsp;</div>
					<div><input type="text" name="data[User][email]" 
								value='<?=@$thisItem['User']['email'];?>' 
								style="width: 250px;" /></div>
				</li>
				<li>
					<div class="label">Password:&nbsp;*&nbsp;</div>
					<div><input type="password" name="data[User][password]" /></div>
				</li>
				<li>
					<div class="label">&nbsp;</div>
					<div class="forgot_pass">Forgot password?&nbsp;&nbsp;
						<a href="#" onclick="return forgot();">Click here</a></div>
				</li>
				<li>
					<div class="label">&nbsp;</div>
    			<div><button type="submit" class='button'>Continue >></button></div>
    		</li>
    	</ul>
    <?php echo $form->end(); ?> 
   </div>
   
   <div id="form_type_register" class="form_type" onclick="switchForm(this);">
   		<input type="radio" name="login_type" value="new" <?=$checkNew;?> />
   		&nbsp;<b>I'm a new customer</b>
   </div>
   <div id="form_register" class="form_main" style="<?=$showNew;?>">
   	<?php echo $form->create(null, array('url'=>'/register/customer', 'id'=>'Form_Register'));?>
   	<?php
   		if (isset($errors) && is_array($errors)) {
   			echo '<ul class="form_errors">' .
   				   '	<li class="title">Errors:</li>';
   			foreach($errors as $key=>$err) {
   					echo '<li>' . $err . '</li>';
   			}
   			echo '</ul>';
   		}
   	?>
   		<ul>
				<li>
					<div class="label">Your Email Address:&nbsp;*&nbsp;</div>
					<div><input type="text" name="data[User][email]"  
											value='<?=@$thisItem['User']['email'];?>' 
											style="width: 250px;" /></div>
				</li>
				<li>
					<div class="label">Type a Password:&nbsp;*&nbsp;</div>
					<div><input type="password" name="data[User][password]" /></div>
				</li>
				<li>
					<div class="label">Re-Type Password:&nbsp;*&nbsp;</div>
					<div><input type="password" name="data[User][confirm_password]" /></div>
				</li>
				<li>
					<div class="label">&nbsp;</div>
					<div><button type="submit" class='button'>Continue >></button></div>
				</li>
			</ul>
			<input type='hidden' name='data[User][active]' value=1 />
   	<?php echo $form->end(); ?> 
   </div>
</div>