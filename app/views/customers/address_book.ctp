<?php 
	function renderAddressRow($model, $address) {
		$contactName = $address[$model]['firstname'] . ' ' . $address[$model]['lastname'];
		$default = $address[$model]['is_default'] == 1 ? 'Default' : '';
		
		$htmlRow = '<tr id="address_' . $address[$model]['id'] . '">' . 
				  '		<td class="order-contact">' . $contactName . '</td>' . 
				  '		<td class="order-address">' . $address[$model]['address1'] . '</td>' .
				  '		<td class="order-suburb">' . $address[$model]['suburb'] . '</td>' . 
				  '		<td class="order-state">' . $address[$model]['state'] . '</td>' . 
				  '		<td class="order-postcode">' . $address[$model]['postcode'] . '</td>' . 
				  '		<td class="order-default default">' . $default . '</td>' . 
				  '		<td class="">' . 
				  '			<span class="nobr">' . 
				  '				<a class="btn-edit-address">View/Edit</a>' . 
				  '			</span>' . 
				  '		</td>' . 
				  '		<td class="last">' . 
				  '			<span class="nobr">' . 
				  '				<a class="btn-delete-address">Delete</a>' . 
				  '			</span>' . 
				  '		</td>' . 
				  '</tr>';
		
		return $htmlRow;
	}
	
	$htmlBillings = $htmlShippings = '';
	if (isset($billings) && count($billings) > 0) {
		foreach($billings as $address) {
			$htmlBillings .= renderAddressRow('Contact', $address);
		}
	}
	if (isset($shippings) && count($shippings) > 0) {
		foreach($shippings as $address) {
			$htmlShippings .= renderAddressRow('Contact', $address);
		}
	}
?> 
<div class="my-account address-book" id="my-address-book">
    <div class="page-title">
        <h1>Address Book</h1>
  	</div>
    <div class="box-account box">
        <div class="box-title">
            <h3 class="legend">Billing Addresses</h3>
            <a id="create-new-billing" class="btn-create-address">Create</a>
        </div>
        <table class="data-table" id="address-table-billing">
            <colgroup>
                <col width="1">
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr class="first last">
                    <th>Contact Name</th>
                    <th>Address</th>
                    <th>Suburb</th>
                    <th>State</th>
                    <th>Postcode</th>
                    <th>Default</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?=$htmlBillings;?>
            </tbody>
        </table>
        <script type="text/javascript">
        	//decorateTable('billing-address-table')
        </script>
    </div>
    
    <div class="box-account box">
        <div class="box-title">
            <h3 class="legend">Shipping Addresses</h3>
            <a id="create-new-shipping" class="btn-create-address">Create</a>
        </div>
        <table class="data-table" id="address-table-shipping">
            <colgroup>
                <col width="1">
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
            </colgroup>
            <thead>
                <tr class="first last">
                    <th>Contact Name</th>
                    <th>Address</th>
                    <th>Suburb</th>
                    <th>State</th>
                    <th>Postcode</th>
                    <th>Default</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?=$htmlShippings;?>
            </tbody>
        </table>
        <script type="text/javascript">
        	//decorateTable('billing-address-table')
        </script>
    </div>

</div>