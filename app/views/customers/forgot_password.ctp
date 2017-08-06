<div class="page-title">
	<h1>Forgot Your Password?</h1>
</div>
<form action="<?=SITE_URL;?>/forgot" method="post" id="form-validate">
	<div class="fieldset">
		<h2 class="legend">Retrieve your password here</h2>
		<p>Please enter your email address below. You will receive a link to reset your password.</p>
		<ul class="form-list">
			<li>
				<label for="email_address" class="required"><em>*</em>Email Address</label>
				<div class="input-box">
					<input type="text" name="data[User][email]" alt="email" id="email_address" class="input-text required-entry validate-email form-control" value="">
				</div>
			</li>
		</ul>
	</div>
	<div class="buttons-set">
		<p class="required">* Required Fields</p>
		<p class="back-link">
			<a href="<?=SITE_URL;?>/login"><small>« </small>Back to Login</a>
		</p>
		<button type="submit" title="Submit" class="button">
			<span><span>Submit</span></span>
		</button>
	</div>
</form>
