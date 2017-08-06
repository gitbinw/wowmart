
	<?php
		if (isset($success) && $success === true) {
	?>
			<div class="info_success">
				Thank you. <br>Your email: '<?=$thisItem['Subscription']['email'];?>'
				 has been successfully subscribed.
			</div>
	<?php
		} else {
	?>
			<div class="item_not_found">
				<?=isset($errors['email']) ? 
						str_replace('%%email%%', 
												$thisItem['Subscription']['email'], 
												$errors['email']) : 'Please enter your email to subscribe.';
				?>
			</div>
	<?php
		}
	?>