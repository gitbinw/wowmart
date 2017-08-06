<div class="checkout_head">
	<div class="checkout_cart" style="margin-left: 400px;"></div>
	<ul>
		<li class="step current">1 <div>Login</div> <div class="delimiter"></div></li>
		<li class="step current"><a href="/checkout/billing">2 <div>Billing</div></a> <div class="delimiter"></div></li>
		<li class="step current">3 <div>Delivery</div> <div class="delimiter"></div></li>
		<li class="step">4 <div>Confirm & Payment</div></li>
	</ul>
</div>

<div class="main_title">
	<i>Step 3 - Delivery</i><br>
</div>
<div class="checkout_form">
	<span class="checkout_info">
		You have to enter an address for delivery.<br><br>
	</span>
	
	<div>
		<input id="use_billing" type="checkbox" value="1" /><b>Use the billing address for delivery.</b>
	</div>
	<div id="form_delivery" class="form_main">
   	 <?php echo $form->create(null, array('url'=>'/checkout/delivery', 'id'=>'Form_Delivery'));?>
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
					<div><input type="text" id="company" name="data[Shipping][company]" value='<?=@$thisItem['Shipping']['company'];?>' /></div>
				</li>
				<li>
					<div class="label">First Name:&nbsp;*&nbsp;</div>
					<div><input type="text" id="firstname" name="data[Shipping][firstname]" value='<?=@$thisItem['Shipping']['firstname'];?>' /></div>
				</li>
				<li>
					<div class="label">Last Name:&nbsp;*&nbsp;</div>
					<div><input type='text' id="lastname" name='data[Shipping][lastname]' value='<?=@$thisItem['Shipping']['lastname'];?>'></div>
				</li>
				<li>
					<div class="label">Address 1:&nbsp;*&nbsp;</div>
					<div><input type='text' id="address1" name='data[Shipping][address1]' value='<?=@$thisItem['Shipping']['address1'];?>' size="40"></div>
				</li>
				<li>
					<div class="label">Address 2:</div>
					<div><input type='text' id="address2" name='data[Shipping][address2]' value='<?=@$thisItem['Shipping']['address2'];?>' size="40"></div>
				</li>
				<li>
					<div class="label">Suburb:&nbsp;*&nbsp;</div>
					<div><input type='text' id="suburb" name='data[Shipping][suburb]' value='<?=@$thisItem['Shipping']['suburb'];?>'></div>
				</li>
				<li>
					<div class="label">State:&nbsp;*&nbsp;</div>
					<div><?=$this->element('states', array(
									'stateName'  => 'data[Shipping][state]',
									'stateId'    => 'state', 
									'stateValue' => @$thisItem['Shipping']['state'])
							);?></div>
				</li>
				<li>
					<div class="label">Post Code:&nbsp;*&nbsp;</div>
					<div><input type='text' id="postcode" name='data[Shipping][postcode]' value='<?=@$thisItem['Shipping']['postcode'];?>'></div>
				</li>
				<li>
					<div class="label">Country:</div>
					<div><input type='text' id="country" name='data[Shipping][country]' value='Australia' readonly></div>
				</li>
				<li>
					<div class="label">Phone:</div>
					<div><input type='text' id="phone" name='data[Shipping][phone]' value='<?=@$thisItem['Shipping']['phone'];?>'></div>
				</li>
				<li>
					<div class="label">Mobile:</div>
					<div><input type='text' id="mobile" name='data[Shipping][mobile]' value='<?=@$thisItem['Shipping']['mobile'];?>'></div>
				</li>
				<li>
					<div class="label">&nbsp;</div>
					<div>
						<a href="/checkout/billing"><div class='button btn_link'><< Go Back</div></a>
						&nbsp;&nbsp;<button type="submit" class='button'>Continue >></button>
					</div>
				</li>
			</ul>
   	<?php echo $form->end(); ?> 
   </div>
   <div class="clear_line_30"></div>
   <script language="javascript" type="text/javascript">
   	 var jsonData = $.parseJSON('<?=@$jsonData;?>');
   	 $('#use_billing').click( function(e){
   	 	if(this.checked) { 
   	 		$("#form_delivery input[type|=text], #form_delivery select").each(function(i, val) {
   				this.value = jsonData[this.id];
   	 		});
   	 	}
   	 });
   </script>
</div>