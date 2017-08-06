<?php 
	$msgClass = 'error';
	if (isset($response)) {
		if ($response['status'] == 1) {
			$msgClass = 'success';
			$msg = '';
		} else if (!empty($response['errorMsg'])) {
			if (is_array($response['errorMsg'])) {
				foreach($response['errorMsg'] as $err) {
					$msg = $err;
					break;
				}
			} else {
				$msg = $response['errorMsg'];
			}
		} 
	}
?> 
<div class="my-account">
    <div class="page-title">
        <h1>Edit Account Information</h1>
  	</div>
    <?php if (!empty($msg)) { ?> 
    <h2 class="<?=$msgClass;?>"><?=$msg;?></h2>
    <?php } ?>
    <form action="/account/update" method="post" id="form-validate" autocomplete="off">
        <div class="fieldset">
            <h2 class="legend">Account Information</h2>
            <ul class="form-list">
                <li class="fields">
                    <div class="customer-name-middlename">
                        <div class="field name-firstname">
                            <label for="firstname" class="required"><em>*</em>First Name</label>
                            <div class="input-box">
                                <input id="firstname" name="firstname" value="<?=$profile['firstname'];?>" title="First Name" maxlength="255" class="input-text required-entry form-control" type="text">
                            </div>
                        </div>
                        <div class="field name-lastname">
                            <label for="lastname" class="required"><em>*</em>Last Name</label>
                            <div class="input-box">
                                <input id="lastname" name="lastname" value="<?=$profile['lastname']?>" title="Last Name" maxlength="255" class="input-text required-entry form-control" type="text">
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <label for="email" class="required"><em>*</em>Email Address</label>
                    <div class="input-box">
                        <input name="email" id="email" value="<?=$Auth['User']['email']?>" title="Email Address" class="input-text required-entry validate-email form-control" type="text">
                    </div>
                </li>
                <li class="control">
                    <input name="change_password" id="change_password" value="1" onclick="setPasswordForm(this.checked)" title="Change Password" class="checkbox" type="checkbox"><label for="change_password">Change Password</label>
                </li>
            </ul>
        </div>
        <div class="fieldset" style="display:none;">
            <h2 class="legend">Change Password</h2>
            <ul class="form-list">
                <li>
                    <label for="current_password" class="required"><em>*</em>Current Password</label>
                    <div class="input-box">
                        <!-- This is a dummy hidden field to trick firefox from auto filling the password -->
                        <input class="input-text no-display form-control" name="dummy" id="dummy" type="text">
                        <input title="Current Password" class="input-text form-control" name="current_password" id="current_password" type="password">
                    </div>
                </li>
                <li class="fields">
                    <div class="field">
                        <label for="password" class="required"><em>*</em>New Password</label>
                        <div class="input-box">
                            <input title="New Password" class="input-text validate-password form-control" name="password" id="password" type="password">
                        </div>
                    </div>
                    <div class="field">
                        <label for="confirmation" class="required"><em>*</em>Confirm New Password</label>
                        <div class="input-box">
                            <input title="Confirm New Password" class="input-text validate-cpassword form-control" name="confirm_password" id="confirmation" type="password">
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="buttons-set">
            <p class="required">* Required Fields</p>
            <button type="submit" title="Save" class="button"><span><span>Save</span></span></button>
        </div>
    </form>
    <script type="text/javascript">
    //<![CDATA[
        var dataForm = new VarienForm('form-validate', true);
        function setPasswordForm(arg){
            if(arg){
                $('current_password').up(3).show();
                $('current_password').addClassName('required-entry');
                $('password').addClassName('required-entry');
                $('confirmation').addClassName('required-entry');
    
            }else{
                $('current_password').up(3).hide();
                $('current_password').removeClassName('required-entry');
                $('password').removeClassName('required-entry');
                $('confirmation').removeClassName('required-entry');
            }
        }
    
        //]]>
    </script>

</div>