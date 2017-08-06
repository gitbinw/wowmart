<div class="checkout_head">
	<div class="checkout_cart" style="margin-left: 230px;"></div>
	<ul>
		<li class="step current">1 <div>Login</div> <div class="delimiter"></div></li>
		<li class="step current">2 <div>Billing</div> <div class="delimiter"></div></li>
		<li class="step">3 <div>Delivery</div> <div class="delimiter"></div></li>
		<li class="step">4 <div>Confirm & Payment</div></li>
	</ul>
</div>

<div class="main_title">
	<i>Step 2 - Billing</i><br>
</div>
<div class="checkout_form">
	<span class="checkout_info">
		You have to enter an address for billing.<br><br>
	</span>
	
	<div id="form_billing" class="form_main">
   	 <?php echo $form->create(null, array('url'=>'/checkout/billing', 'id'=>'Form_Billing'));?>
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
					<div class="label">Address Book:</div>
					<div><?=$this->element('contacts', array(
									'userId'	   => $Auth['User']['id'],
									'contactName'  => 'data[Contact][id]',
									'contactId'    => 'contact_id', 
									'contactValue' => '')
							);?> <a href="/account/tb_book">Create a new address</a></div>
				</li>
   				<li>
					<div class="label">Company:</div>
					<div><input type="text" id="company" name="data[Billing][company]" value='<?=@$thisItem['Billing']['company'];?>' /></div>
				</li>
				<li>
					<div class="label">First Name:&nbsp;*&nbsp;</div>
					<div><input type="text" id="firstname" name="data[Billing][firstname]" value='<?=@$thisItem['Billing']['firstname'];?>' /></div>
				</li>
				<li>
					<div class="label">Last Name:&nbsp;*&nbsp;</div>
					<div><input type='text' id="lastname" name='data[Billing][lastname]' value='<?=@$thisItem['Billing']['lastname'];?>'></div>
				</li>
				<li>
					<div class="label">Address 1:&nbsp;*&nbsp;</div>
					<div><input type='text' id="address1" name='data[Billing][address1]' value='<?=@$thisItem['Billing']['address1'];?>' size="40"></div>
				</li>
				<li>
					<div class="label">Address 2:</div>
					<div><input type='text' id="address2" name='data[Billing][address2]' value='<?=@$thisItem['Billing']['address2'];?>' size="40"></div>
				</li>
				<li>
					<div class="label">Suburb:&nbsp;*&nbsp;</div>
					<div><input type='text' id="suburb" name='data[Billing][suburb]' value='<?=@$thisItem['Billing']['suburb'];?>'></div>
				</li>
				<li>
					<div class="label">State:&nbsp;*&nbsp;</div>
					<div><?=$this->element('states', array(
									'stateName'  => 'data[Billing][state]',
									'stateId'    => 'state', 
									'stateValue' => @$thisItem['Billing']['state'])
							);?></div>
				</li>
				<li>
					<div class="label">Post Code:&nbsp;*&nbsp;</div>
					<div><input type='text' id="postcode" name='data[Billing][postcode]' value='<?=@$thisItem['Billing']['postcode'];?>'></div>
				</li>
				<li>
					<div class="label">Country:</div>
					<div><input type='text' id="country" name='data[Billing][country]' value='Australia' readonly></div>
				</li>
				<li>
					<div class="label">Phone:</div>
					<div><input type='text' id="phone" name='data[Billing][phone]' value='<?=@$thisItem['Billing']['phone'];?>'></div>
				</li>
				<li>
					<div class="label">Mobile:</div>
					<div><input type='text' id="mobile" name='data[Billing][mobile]' value='<?=@$thisItem['Billing']['mobile'];?>'></div>
				</li>
				<li>
					<input type="checkbox" value="1" name="data[Billing][is_delivery]" /><b>Use this address as delivery address.</b>
				</li>
				<li>
					<div class="label">&nbsp;</div>
					<div><button type="submit" class='button'>Continue >></button></div>
				</li>
			</ul>
   	<?php echo $form->end(); ?> 
   </div>
   <div class="clear_line_30"></div>
</div>