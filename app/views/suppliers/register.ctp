<form id='frmSupplier' name='frmSupplier' action='/suppliers/register' method='post'>
<div class="form_fields">
<div class="form_title">
	<b>Freshla Producer Application</b>
</div>
<div class="form_info">
	Awesome, so you've scoped out the guidelines and your products seem like the perfect 
	fit for Freshla. We love discovering new producers so we're excited to hear from you! 
	Please fill out this quick form and you'll hear from Marco within 2 business days.
</div>
<div id="form_supplier" class="form_main">
   	<ul class="form_errors">
   	<?php
   		if (isset($errors) && is_array($errors)) {
   			echo '<li class="title">Errors:</li>';
   			foreach($errors as $key=>$err) {
   					if (is_array($err)) {
   						foreach ($err as $errVal) {
   							echo '<li>' . $errVal . '</li>';
   							break;
   						}
   					} else {
   						echo '<li>' . $err . '</li>';
   					}
   					break;
   			}
   		}
   	?>
   		</ul>
   		<ul class="form_part">
   			<li>
					<label>Business Name:&nbsp;*&nbsp;</label>
					<div><input type="text" name="data[Supplier][biz_name]" 
											value="<?=@$thisItem['Supplier']['biz_name'];?>" 
											class="form_field" /></div>
				</li>
				<li>
					<label>Phone Number:&nbsp;*&nbsp;</label>
					<div><input type="text" name="data[Supplier][phone]" 
											value="<?=@$thisItem['Supplier']['phone'];?>"  
											class="form_field" /></div>
				</li>
				<li>
					<label>Email Address:&nbsp;*&nbsp;</label>
					<div><input type="text" name="data[User][email]"  
											value="<?=@$thisItem['User']['email'];?>" 
											class="form_field" /></div>
				</li>
				<li>
					<label>Password:&nbsp;*&nbsp;</label>
					<div><input type="password" name="data[User][password]" class="form_field" /></div>
				</li>
				<li>
					<label>Confirm Password:&nbsp;*&nbsp;</label>
					<div><input type="password" name="data[User][confirm_password]" class="form_field" /></div>
				</li>
				<li>
					<label>Preferred Subdomain Name:&nbsp;*&nbsp;</label>
					<div><input type="text" name="data[Supplier][subdomain]"  
											value="<?=@$thisItem['Supplier']['subdomain'];?>" 
											class="form_field" /><b>.freshla.com.au</b></div>
				</li>
				<li>
					<label>Your Website:</label>
					<div><input type="text" name="data[Supplier][website]"  
											value="<?=@$thisItem['Supplier']['website'];?>" 
											class="form_field" /></div>
				</li>
				<li>
					<label>Tell us a bit about your passion for your products and yourself!&nbsp;*&nbsp;</label>
					<div>
					<textarea name="data[Supplier][aboutus]"><?=@$thisItem['Supplier']['aboutus'];?></textarea>
					</div>
				</li>
			</ul>
			
			<ul class="form_part">
				<li>
					<label>Contact Name:&nbsp;*&nbsp;</label>
					<div><input type="text" name="data[Supplier][contact_name]"  
											value="<?=@$thisItem['Supplier']['contact_name'];?>" 
											class="form_field" /></div>
				</li>
				<li class="address">
					<label>Shipping Return Address (No PO Boxes):&nbsp;*&nbsp;</label>
					<div>
						<input type="text" name="data[Supplier][return_address1]"  
											value="<?=@$thisItem['Supplier']['return_address1'];?>" 
											class="form_field" />
					</div>
					<div>
						<input type="text" name="data[Supplier][return_address2]"  
											value="<?=@$thisItem['Supplier']['return_address2'];?>" 
											class="form_field" />
					</div>
				</li>
				<li>
					<label>Suburb:&nbsp;*&nbsp;</label>
					<div>
						<input type="text" name="data[Supplier][return_suburb]"  
											value="<?=@$thisItem['Supplier']['return_suburb'];?>" 
											class="form_field" />
					</div>
				</li>
				<li>
					<label>Postcode:&nbsp;*&nbsp;</label>
					<div>
						<input type="text" name="data[Supplier][return_postcode]"  
											value="<?=@$thisItem['Supplier']['return_postcode'];?>" 
											class="form_field" />
					</div>
				</li>
				<li>
					<label>State:&nbsp;*&nbsp;</label>
					<div>
						<?=$this->element('states', array(
							'stateName'  => 'data[Supplier][return_state]',
							'stateId'    => 'state', 
							'stateValue' => @$thisItem['Supplier']['return_state'])
						);?>
					</div>
				</li>
				<li>
					<br>
					* Required Fields
				</li>
		</ul>
	</div>
</div>

<div class="form_buttons">
	<div class="left_button">
		<a href="/login"><div class="return_link">Sign in</div></a>
	</div>
	<div class="right_button">
		<button type="submit" class='btn_normal'>Register</button>
	</div>
</div>
</form>