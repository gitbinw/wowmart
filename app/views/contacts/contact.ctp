<form id='form_address' name='form_address' method='post'>
<div id="form_contact" class="form_main">
 	<input type="hidden" name="data[Contact][id]" value='<?=@$thisItem['Contact']['id'];?>' />
 	<input type="hidden" name="data[Contact][is_billing]" value='<?=@$thisItem['Contact']['is_billing'];?>' />
 	<input type="hidden" name="data[Contact][is_shipping]" value='<?=@$thisItem['Contact']['is_shipping'];?>' />
 	<ul class="form_errors" id="form_errors"></ul>
	<ul class="form_fields">
		<li>
			<div class="label">Alias:&nbsp;*&nbsp;</div>
			<div><input type="text" name="data[Contact][alias]" value='<?=@$thisItem['Contact']['alias'];?>' /></div>
		</li>
   		<li>
			<div class="label">Company:</div>
			<div><input type="text" name="data[Contact][company]" value='<?=@$thisItem['Contact']['company'];?>' /></div>
		</li>
		<li>
			<div class="label">First Name:&nbsp;*&nbsp;</div>
			<div><input type="text" name="data[Contact][firstname]" value='<?=@$thisItem['Contact']['firstname'];?>' /></div>
		</li>
		<li>
			<div class="label">Last Name:&nbsp;*&nbsp;</div>
			<div><input type='text' name='data[Contact][lastname]' value='<?=@$thisItem['Contact']['lastname'];?>'></div>
		</li>
		<li>
			<div class="label">Address 1:&nbsp;*&nbsp;</div>
			<div><input type='text' name='data[Contact][address1]' value='<?=@$thisItem['Contact']['address1'];?>' class="long"></div>
		</li>
		<li>
			<div class="label">Address 2:</div>
			<div><input type='text' name='data[Contact][address2]' value='<?=@$thisItem['Contact']['address2'];?>' class="long"></div>
		</li>
		<li>
			<div class="label">Suburb:&nbsp;*&nbsp;</div>
			<div><input type='text' name='data[Contact][suburb]' value='<?=@$thisItem['Contact']['suburb'];?>'></div>
		</li>
		<li>
			<div class="label">State:&nbsp;*&nbsp;</div>
			<div><?=$this->element('states', array(
							'stateName'  => 'data[Contact][state]',
							'stateId'    => 'state', 
							'stateValue' => @$thisItem['Contact']['state'])
					);?></div>
		</li>
		<li>
			<div class="label">Post Code:&nbsp;*&nbsp;</div>
			<div><input type='text' name='data[Contact][postcode]' value='<?=@$thisItem['Contact']['postcode'];?>'></div>
		</li>
		<li>
			<div class="label">Country:</div>
			<div><input type='text' name='data[Contact][country]' value='Australia' readonly></div>
		</li>
		<li>
			<div class="label">Phone:</div>
			<div><input type='text' name='data[Contact][phone]' value='<?=@$thisItem['Contact']['phone'];?>'></div>
		</li>
		<li>
			<div class="label">Mobile:</div>
			<div><input type='text' name='data[Contact][mobile]' value='<?=@$thisItem['Contact']['mobile'];?>'></div>
		</li>
		<li>
			<div class="label">&nbsp;</div>
			<div class='button' id='btn_update'>
				<?=isset($thisItem['Contact']['id']) ? 'Update' : 'Submit';?>
			</div>
			<div class='button' id='btn_cancel'>Cancel</div>
		</li>
	</ul>
</div>
</form> 
   