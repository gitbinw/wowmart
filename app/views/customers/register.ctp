<div class="account-login">
	<div class="page-title">
		<h1>Login or Create an Account</h1>
	</div>
	<form action="/login" method="post" id="login-form">
		<div class="col2-set">
			<div class="wrapper">
				<div class="registered-users-wrapper">
					<div class="col-2 registered-users">
						<div class="content">
							<h2>Registered Customers</h2>
							<p>If you have an account with us, please log in.</p>
							<ul class="form-list">
								<li>
									<label for="email" class="required"><em>*</em>Email Address</label>
									<div class="input-box">
										<input type="email" name="data[User][email]" 
											value="<?=isset($thisItem['User']['email']) ? $thisItem['User']['email'] : '';?>" 
											id="email" class="input-text required-entry validate-email form-control" 
											title="Email Address" />
									</div>
								</li>
								<li>
									<label for="pass" class="required"><em>*</em>Password</label>
									<div class="input-box">
										<input type="password" name="data[User][password]" id="pass" 
											class="input-text required-entry validate-password form-control" 
											title="Password" />
									</div>
								</li>
							</ul>
							
							<p class="required">* Required Fields</p>
							<div class="buttons-set">
								<a href="/forgot" class="f-left">Forgot Your Password?</a>
								<button type="submit" class="button" title="Login" name="send" id="send2">
									<span><span>Login</span></span>
								</button>
							</div>
						</div>
					</div>
				</div>
				
				<div class="new-users-wrapper">
					<div class="col-1 new-users">
						<div class="content">
							<h2>New Customers</h2>
							<p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, 
								view and track your orders in your account and more.
							</p>
							<div class="buttons-set">
								<button type="button" title="Create an Account" class="button" onclick="return false;">
									<span><span>Create an Account</span></span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>