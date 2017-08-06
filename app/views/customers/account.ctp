<?php
/*	$initTab = isset($init_tab) && !empty($init_tab) ? $init_tab : 'tb_profile';
	$contactList = "";
	$defBillingId = $defShippingId = '';
	foreach($user['Contact'] as $cont) {
		$defaultType = "&nbsp;";
		if ($cont['is_billing'] == 1) {
			$defBillingId = $cont['id'];
			$defaultType .= "<span class='default_billing'>Billing</span>";
		}
		if ($cont['is_shipping'] == 1) {
			$defShippingId = $cont['id'];
			if ($defaultType != "&nbsp;") {
				$defaultType .= "<span class='separator'><br>" .
								"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&amp;<br></span>";
			} 
			$defaultType .= "<span class='default_shipping'>Shipping</span>";
		}
		
		$contactList .= "<tr class='row' id='address_row_" . $cont['id'] . "'>" .
						"<td valign='top'>" . $cont['alias'] . "</td>" . 
						"<td valign='top'>" . $cont['firstname'] . "&nbsp;" . $cont['lastname'] .
						"<br>" . $cont['address1'] . "&nbsp;" . $cont['address2'] . "<br>" . 
						$cont['suburb'] . "&nbsp;" .$cont['state'] . "&nbsp;" . 
						$cont['postcode'] . "<br>" . $cont['country'] . 
						(!empty($cont['phone']) ? "<br>Phone: " . $cont['phone'] : "") .
						(!empty($cont['mobile']) ? "<br>Mobile: " . $cont['mobile'] : "") . 
						"</td>" . 
						"<td valign='top' class='default_type'>" . $defaultType . "</td>" .
						"<td valign='top'><input type='radio' name='radSelect' class='radSelect' /></td>" . 
						"</tr>";
	}
	
	$orders_tracking = $orders_to_complete = $orders_history = "";
	foreach($user['Order'] as $order) {
		$tmp_common = "";
		$tmp_order = $tmp_order1 = "<tr class='row' id='order_row_" . $order['id'] . "'>";
		$tmp_common .= "<td valign='top'>$" . $order['subtotal'] . "</td>" .
								 "<td valign='top'>$" . $order['freight'] . "</td>" .
								 "<td valign='top'>" . date('d/m/Y', strtotime($order['created'])) . "</td>" .
								 "<td valign='top'>" . $order['Shipping']['firstname'] . "&nbsp;" . 
								 $order['Shipping']['lastname'] . "<br>" . $order['Shipping']['address1'] . 
								 "&nbsp;" . $order['Shipping']['address2'] . "<br>" . 
								 $order['Shipping']['suburb'] . "&nbsp;" .$order['Shipping']['state'] . 
								 "&nbsp;" . $order['Shipping']['postcode'] . "<br>" . 
								 $order['Shipping']['country'] . 
								 (!empty($order['Shipping']['phone']) ? "<br>Phone: " . 
								 $order['Shipping']['phone'] : "") .
								 (!empty($order['Shipping']['mobile']) ? "<br>Mobile: " . 
								 $order['Shipping']['mobile'] : "") . 
								 "</td>" .
								 "<td valign='top'>" . $order['Status']['name'] . "</td>" .
								 "<td valign='top'><input type='radio' name='radOrder' class='radSelect' /></td>" .
								 "</tr>";
		$tmp_order .= "<td valign='top'>" . $order['order_no'] . "</td>" . $tmp_common;
		$tmp_order1 .= $tmp_common;
		if ($order['status_id'] == TYPE_ORDER_PAY_REVIEW || 
				$order['status_id'] == TYPE_ORDER_PAID ||
				$order['status_id'] == TYPE_ORDER_PENDING ||
				$order['status_id'] == TYPE_ORDER_DELIVERED) {
			$orders_tracking .= $tmp_order;
		} else if ($order['is_paid'] == 1 && $order['status_id'] == TYPE_ORDER_COMPLETED) {
			$orders_history .= $tmp_order;
		} else if (empty($order['is_paid']) && $order['status_id'] == TYPE_ORDER_NOT_PAID) {
			$orders_to_complete .= $tmp_order1;
		}
	}*/
?>

<div class="my-account">
	<div class="dashboard">
    	<div class="page-title">
        	<h1>My Dashboard</h1>
    	</div>
        <div class="welcome-msg">
    		<p class="hello"><strong>Hello, <?=$profile['firstname'] . ' ' . $profile['lastname'];?></strong></p>
    		<p>From your My Account Dashboard you have the ability to view a 
				snapshot of your recent account activity and update your account 
				information. Select a link below to view or edit information.
           	</p>
		</div>
    	<div class="box-account box-recent">
    		<div class="box-head">
        		<h2>Recent Orders</h2>
        		<a href="http://127.0.0.1/magento/index.php/sales/order/history/">View All</a>    
           	</div>
    		<table class="data-table" id="my-orders-table">
                <colgroup>
                    <col width="1">
                    <col width="1">
                    <col>
                    <col width="1">
                    <col width="1">
                    <col width="1">
        		</colgroup>
                <thead>
                    <tr class="first last">
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Ship To</th>
                        <th><span class="nobr">Order Total</span></th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
        		<tbody>
                    <tr class="first last odd">
                        <td class="order-id">100000001</td>
                        <td class="order-date"><span class="nobr">9/10/2015</span></td>
                        <td class="order-ship">shuyi wu</td>
                        <td class="order-total"><span class="price">$1,105.00</span></td>
                        <td class="order-status"><em>Pending</em></td>
                        <td class="last">
                            <span class="nobr">
                            	<a href="http://127.0.0.1/magento/index.php/sales/order/view/order_id/1/">View Order</a>
                                <span class="separator">|</span> <a href="http://127.0.0.1/magento/index.php/sales/order/reorder/order_id/1/" class="link-reorder">Reorder</a>
                            </span>
                        </td>
                    </tr>
            	</tbody>
    		</table>
    		<script type="text/javascript">decorateTable('my-orders-table')</script>
		</div>
    	<div class="box-account box-info">
        	<div class="box-head">
            	<h2>Account Information</h2>
        	</div>
            <div class="col2-set">
    			<!--<div class="col-1">-->
        			<div class="box">
            			<div class="box-title">
                			<h3>Contact Information</h3>
                			<a href="/account/edit/">Edit</a>
            			</div>
            			<div class="box-content">
                            <p>
                                <?=$profile['firstname'] . ' ' . $profile['lastname'];?><br>
                                <?=$Auth['User']['email'];?><br>
                                <a href="/account/changepassword">Change Password</a>
                            </p>
            			</div>
        			</div>
    			<!--</div>-->
        		<!--<div class="col-2">
        			<div class="box">
            			<div class="box-title">
                			<h3>Newsletters</h3>
                			<a href="/account/newsletter">Edit</a>
            			</div>
            			<div class="box-content">
                			<p>
                            	<?php if (isset($profile['subscribed'])) { ?>
                                You are currently not subscribed to any newsletter.
                                <?php } ?>                                    
                          	</p>
            			</div>
        			</div>
             	</div>-->
    		</div>
        	<div class="col2-set">
    			<div class="box">
        			<div class="box-title">
            			<h3>Address Book</h3>
            			<a href="http://127.0.0.1/magento/index.php/customer/address/">Manage Addresses</a>
        			</div>
        			<div class="box-content">
            			<div class="col-1">
                			<h4>Default Billing Address</h4>
                			<address>
                    			shuyi wu<br>
                                179 bettington rd<br>
                                carlingford,  Alabama, 2118<br>
                                United States<br>
                                T: 0466362562
                                <br>
                    			<a href="http://127.0.0.1/magento/index.php/customer/address/edit/id/1/">Edit Address</a>
                			</address>
            			</div>
                        <div class="col-2">
                            <h4>Default Shipping Address</h4>
                            <address>
                                shuyi wu<br>
                                179 bettington rd<br>
                                carlingford,  Alabama, 2118<br>
                                United States<br>
                                T: 0466362562
                                <br>
                                <a href="http://127.0.0.1/magento/index.php/customer/address/edit/id/1/">Edit Address</a>
                            </address>
                        </div>
                	</div>
        		</div>
    		</div>
		</div>
    </div>
</div>
        
        
<div id="user_account">
	<div class="tab_bar">
		<div id="tb_profile" class="tab_element <?=$initTab=='tb_profile'?'tab_current':'';?>">
			<div class="tab_left"></div>
			<div class="tab_name">My Details</div>
			<div class="tab_right"></div>
		</div>

		<div id="tb_book" class="tab_element <?=$initTab=='tb_book'?'tab_current':'';?>">
			<div class="tab_left"></div>
			<div class="tab_name">Address Book</div>
			<div class="tab_right"></div>
		</div>
	
		<div id="tb_order" class="tab_element <?=$initTab=='tb_order'?'tab_current':'';?>">
			<div class="tab_left"></div>
			<div class="tab_name">My Orders</div>
			<div class="tab_right"></div>
		</div>
	</div>
	
	<div class="tab_content">
		<div id="tb_profile_cnt" class="tab_cnt">
			<ul>
				<li>
					<div class="label">Email Address: 
						<a href="#" id="email" class="btn_edit" name="Email">Edit</a>
					</div>
					<div>
						<span id="email_text"><?=$user['User']['email'];?></span>
					</div>
				</li>
				<li>
					<div class="label">Password:
						<a href="#" id="password" class="btn_edit" name="Password">Edit</a>
					</div>
					<div>
						<span>(hidden)</span>
					</div>
				</li>
				<li>
					<div class="label">First Name:
						<a href="#" id="firstname" class="btn_edit" name="First Name">Edit</a>
					</div>
					<div>
						<span id="firstname_text"><?=$user['UserProfile']['firstname'];?></span>
					</div>
				</li>
				<li>
					<div class="label">Last Name:
						<a href="#" id="lastname" class="btn_edit" name="Last Name">Edit</a>
					</div>
					<div>
						<span id="lastname_text"><?=$user['UserProfile']['lastname'];?></span>
					</div>
				</li>
			</ul>
		</div>
		<div id="tb_book_cnt" class="tab_cnt">
			<div class="address_book">
				<div class="address_head">
					<div class="title">Address Book</div>
					<div id="icon_process" class="icon_process">Processing ...</div>
				</div>
				<ul>
					<li id="address_add" class="btn_address">Create</li>
					<li id="address_edit" class="btn_address">Edit</li>
					<li id="address_billing" class="btn_address">Set as Default Billing</li>
					<li id="address_shipping" class="btn_address">Set as Default Shipping</li>
					<li id="address_del" class="btn_address">Remove</li>
				</ul>
				<table cellspacing="0" cellpadding="0" width="100%" class="tb" id="tb_address_book">
					<tr>
						<th width="200">Name</th>
						<th width="250">Address & Contact</th>
						<th width="100">&nbsp;Default</th>
						<th>&nbsp;</th>
					</tr>
					<?=!empty($contactList) ? $contactList : 
					   '<tr id="noaddress"><td colspan="4" class="noitem">No Address So Far !</td></tr>';?>
				</table>
			</div>
		</div>
		<div id="tb_order_cnt" class="tab_cnt">
			<div class="address_book">
				<div class="address_head">
					<div class="title">Orders Tracking</div>
				</div>
				<div>
					<ul>
						<li id="order_view_track" class="btn_address">View Details</li>
					</ul>
					<table cellspacing="0" cellpadding="0" width="100%" class="tb">
						<tr>
							<th width="115">Order No.</th>
							<th width="70">Amount</th>
							<th width="70">Freight</th>
							<th width="60">Date</th>
							<th>Delivery To</th>
							<th width="60">Status</th>
							<th width="20">&nbsp;</th>
						</tr>
						<?=$orders_tracking;?>
					</table>
				</div>
				
				<div class="clear_line_30"></div>
				
				<div class="address_head">
					<div class="title">Order to be Completed</div>
					<div id="icon_order_del" class="icon_process">Processing ...</div>
				</div>
				<div>
					<ul>
						<li id="order_continue" class="btn_address">Continue to Pay</li>
						<li id="order_del" class="btn_address">Remove</li>
					</ul>
					<table cellspacing="0" cellpadding="0" width="100%" class="tb">
						<tr>
							<th width="80">Amount</th>
							<th width="80">Freight</th>
							<th width="90">Date</th>
							<th>Delivery To</th>
							<th width="60">Status</th>
							<th width="20">&nbsp;</th>
						</tr>
						<?=$orders_to_complete;?>
					</table>		
				</div>
				
				<div class="clear_line_30"></div>
				
				<div class="address_head">
					<div class="title">Order History</div>
				</div>
				<div>
					<ul>
						<li id="order_view_history" class="btn_address">View Details</li>
					</ul>
					<table cellspacing="0" cellpadding="0" width="100%" class="tb">
						<tr>
							<th width="115">Order No.</th>
							<th width="70">Amount</th>
							<th width="70">Freight</th>
							<th width="60">Date</th>
							<th>Delivery To</th>
							<th width="60">Status</th>
							<th width="20">&nbsp;</th>
						</tr>
						<?=$orders_history;?>
					</table>
				</div>
			</div>
		</div>
			
		<div class="board_header_rgt"></div>
	</div>
		
	<div class="board_footer">
		<div class="corner_btm_lft"></div>
		<div class="btm_middle"></div>
		<div class="corner_btm_rgt"></div>
	</div>
	
	<script language='javascript' type='text/javascript'>
	//	loadTabs('user_account');
	//	loadUserEditor('tb_profile_cnt');
	//	setupAddressBook();
	//	setupOrderList();
	</script>
</div>