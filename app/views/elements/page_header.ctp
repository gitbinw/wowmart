<div class="swipe">
    <div class="swipe-menu">
        <a class="home-link" title="Home" href="http://livedemo00.template-help.com/magento_53792/">Home</a>
        <ul class="links">
            <li class="first">
            	<a class="my-account-link" title="My Account" href="<?=SITE_URL;?>/account">My Account</a>
            </li>
            <li>
            	<a class="wishlist-link" title="My Wishlist" href="<?=SITE_URL;?>/wishlist">My Wishlist</a>
            </li>
            <li class="top-car">
            	<a class="top-link-cart" title="My Cart" href="<?=SITE_URL;?>/checkout/cart">My Cart</a>
            </li>
            <li>
            	<a class="top-link-checkout" title="Checkout" href="<?=SITE_URL;?>/checkout">Checkout</a>
            </li>
            <li class="last">
            	<?php if (isset($Auth) && !empty($Auth)) { ?>
				<a class="log-in-link" title="Log Out" href="<?=SITE_URL;?>/logout">Log Out</a>
				<?php } else { ?>
                <a class="log-in-link" title="Log In" href="<?=SITE_URL;?>/login" onclick="return false;">Log In</a>
            	<?php } ?>
            </li>
        </ul>
        <div class="currency-switch switch-show">
        	<div class="currency-title">
        		<span class="label">Currency:</span><strong class="current">AUD</strong>
        	</div>
        	<ul class="currency-dropdown" style="">
        		<li>
        			<a class="selected" href=""><span>Australia Dollar -</span> AUD</a>
        		</li>
        	</ul>
        </div>
         
        <div class="language-list switch-show">
       		<div class="language-title"><span class="label">Your Language:</span> <strong>en</strong></div>
        </div>
        <div class="footer-links-menu">
        	<ul>
   	    	    <li><a href="http://livedemo00.template-help.com/magento_53792/about-magento-demo-store">About Us</a></li>
    	        <li><a href="http://livedemo00.template-help.com/magento_53792/customer-service">Customer Service</a></li>
            	<li><a href="http://livedemo00.template-help.com/magento_53792/template-settings">Template Settings</a></li>
            	<li class="last privacy"><a href="http://livedemo00.template-help.com/magento_53792/privacy-policy-cookie-restriction-mode">Privacy Policy</a></li>
            </ul>
        	<ul class="links-2">
        		<li class="first"><a href="http://livedemo00.template-help.com/magento_53792/catalog/seo_sitemap/product/">Product Sitemap</a></li>
        		<li><a href="http://livedemo00.template-help.com/magento_53792/catalog/seo_sitemap/category/">Category Sitemap</a></li>
        		<li><a href="http://livedemo00.template-help.com/magento_53792/catalogsearch/advanced/">Advanced Search</a></li>
        		<li><a href="http://livedemo00.template-help.com/magento_53792/sales/guest/form/">Orders and Returns</a></li>
        	</ul> 
    	</div>
    </div>
</div>

<div class="top-icon-menu">
    <div class="swipe-control"><i class="fa fa-align-justify"></i></div>
    <div class="top-search"><i class="fa fa-search"></i></div>
    <span class="clear"></span>
</div>
    
<div class="header-container">
	<div class="container">
    	<div class="row">
        	<div class="col-xs-12">
                                
				<div class="header">
                    <div class="header-buttons">
                        <div class="header-button lang-list">
							<span class="select-label-name">Your Language:</span>
							<a title="Language" href="#">English</a>
						</div>

						<div class="header-button currency-list">
							<span class="select-label-name">Currency:</span>
							<a title="Currency" href="#">AUD</a>
                       	</div>
                    </div>
                         
					<div class="block-cart-header">
    					<div id="ajaxscicon">
        					<div class="block-cart-icon">
                				<img src="/magento/media/cmsmart/ajaxcart/block-cart-icon.png">
    						</div>
    						<!-- <h3></h3> -->
    						<span class="summary-top">0</span>
                		</div>

        				<!--  <h3>:</h3> -->
    					<div class="block-content"></div>
					</div>
                   
                	<div class="head-icon-menu">
                    	<div class="icon-click"><i></i></div>
                    	<div class="icon-block">
                       		<div class="quick-access">
                            	<ul class="links">
                                	<li class="first">
                                		<a href="<?=SITE_URL;?>/account" title="My Account" class="my-account-link">My Account</a>
                                	</li>
                                	<li>
                                		<a href="<?=SITE_URL;?>/wishlist" title="My Wishlist" class="wishlist-link">My Wishlist</a>
                                	</li>
                                	<li class="top-car">
                                		<a href="<?=SITE_URL;?>/checkout/cart" title="My Cart" class="top-link-cart">My Cart</a>
                                	</li>
                                	<li>
                                		<a href="<?=SITE_URL;?>/checkout" title="Checkout" class="top-link-checkout">Checkout</a>
                                	</li>
                                	<li class="last">
                                    	<?php if (isset($Auth) && !empty($Auth)) { ?>
                                		<a href="<?=SITE_URL;?>/logout" title="Log Out" class="log-in-link">Log Out</a>
                                		<?php } else { ?>
                                        <a class="log-in-link" title="Log In" href="<?=SITE_URL;?>/login" onclick="return false;">Log In</a>
            							<?php } ?>
                                    </li>
                            	</ul>
                        	</div> 
                    	</div>
              		</div>
                	<p class="welcome-msg">Welcome to our online store! </p>
                
                    <div class="clear"></div>
                    <div class="header-bg">
                        <h1 class="logo">
                            <strong><?=STORE_NAME;?></strong>
                            <a href="/" title="<?=STORE_NAME;?>" class="logo">
                                <img src="/magento/skin/frontend/default/theme713/images/logo.gif" alt="<?=STORE_NAME;?>">
                            </a>
                        </h1>
                        <form id="search_mini_form" action="index.php/catalogsearch/result/" method="get">
                            <div class="form-search">
                                <label for="search">Search:</label>
                                <input type="text" autocomplete="off" id="search" name="q" value="" class="input-text" maxlength="128">
                                <button type="submit" title="Search" class="button"><span><span>Search</span></span></button>
                               <div style="display: none;" id="search_autocomplete" class="search-autocomplete"></div>
                            </div>
                        </form>
                    </div>
                
                    <div class="skip-links"> 
                        <span href="#header-account" class="skip-link skip-account"></span>
                    </div>
                    
                    <div id="header-account" class="skip-content">
                        <div style="display: none;" class="youama-login-window">
                            <div class="youama-window-outside">
                                <span class="close">×</span>
    
                                <div class="youama-window-inside">
                                    <div class="youama-window-title">
                                        <h3>Login Form</h3>
                                    </div>
                                    <div class="account-login"></div>
                                    <div class="youama-window-box first">
                                        <div class="youama-window-content">
                                            <div class="input-fly youama-showhideme">
                                                <label for="youama-email">E-mail address <span>*</span></label>
                                                <input type="text" placeholder="E-mail address" id="youama-email" name="youama-email" value="">
                                                <div style="display: none;" class="youama-ajaxlogin-error err-email err-noemail err-wrongemail err-wronglogin"></div>
                                            </div>
                                            <div class="input-fly youama-showhideme">
                                                <label for="youama-password">Password <span>*</span></label>
                                                <input type="password" placeholder="Password" id="youama-password" name="youama-password" value="">
                                                <div style="display: none;" class="youama-ajaxlogin-error err-password err-dirtypassword err-nopassword err-longpassword"></div>
                                            </div>
                                        </div>
                                    </div>
                    
                                    <div class="youama-window-box last">
                                        <div class="youama-window-content box-contents box-contents-button youama-showhideme">
                                            <span class="youama-forgot-password">
                                                <a href="index.php/customer/account/forgotpassword/">Forgot Password ?</a>
                                            </span>
                                            <button type="button" class="button btn-proceed-checkout btn-checkout youama-ajaxlogin-button">
                                                <span>
                                                    <span>Login</span>
                                                </span>
                                            </button>
                                            <p id="y-to-register" class="yoauam-switch-window">or registration</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div style="display: none;" class="youama-register-window">
                        	<div class="youama-window-outside">
                            	<span class="close">×</span>
    
                               	<div class="youama-window-inside">
                                	<div class="youama-window-title">
                                    	<h3>Registration</h3>
                                   	</div>
    
                                   	<div class="youama-window-box first">
                                    	<div class="youama-window-subtitle youama-showhideme">
                                        	<p>Profile Informations</p>
                                       	</div>
                                       	<div class="youama-window-content">
                                        	<div class="input-fly youama-showhideme">
                                            	<label for="youama-firstname">First Name <span>*</span></label>
                                               	<input type="text" placeholder="First Name" id="youama-firstname" name="youama-firstname" value="">
                                               	<div style="display: none;" class="youama-ajaxlogin-error err-firstname err-nofirstname err-dirtyfirstname"></div>
                                           	</div>
                                           	<div class="input-fly youama-showhideme">
                                               	<label for="youama-lastname">Last Name <span>*</span></label>
                                               	<input type="text" placeholder="Last Name" id="youama-lastname" name="youama-lastname" value="">
                                               	<div style="display: none;" class="youama-ajaxlogin-error err-lastname err-nolastname err-dirtylastname"></div>
                                           	</div>
                                           	<div class="input-fly input-fly-checkbox youama-showhideme">
                                               	<input type="checkbox" id="youama-newsletter" name="youama-newsletter" value="ok">
                                               	<label for="youama-newsletter">Subscribe to Newsletter</label>
                                           	</div>
                                       	</div>
                                  	</div>
    
                                  	<div class="youama-window-box second">
                                    	<div class="youama-window-subtitle youama-showhideme">
                                            <p>Login Datas</p>
                                        </div>
                                        <div class="youama-window-content">
                                        	<div class="input-fly youama-showhideme">
                                                <label for="youama-email">E-mail address <span>*</span></label>
                                                <input type="text" placeholder="E-mail address" id="youama-email" name="youama-email" value="">
                                                <div style="display: none;" class="youama-ajaxlogin-error err-email err-noemail err-wrongemail err-emailisexist"></div>
                                            </div>
                                            <div class="input-fly youama-showhideme">
                                                <label for="youama-password">Password <span>*</span></label>
                                                <input type="password" placeholder="Password" id="youama-password" name="youama-password" value="">
                                                <div style="display: none;" class="youama-ajaxlogin-error err-password err-dirtypassword err-nopassword err-shortpassword err-longpassword"></div>
                                            </div>
                                            <div class="input-fly youama-showhideme">
                                                <label for="youama-passwordsecond">Password confirmation <span>*</span></label>
                                                <input type="password" placeholder="Password confirmation" id="youama-passwordsecond" name="youama-passwordsecond" value="">
                                                <div style="display: none;" class="youama-ajaxlogin-error err-passwordsecond err-nopasswordsecond err-notsamepasswords"></div>
                                            </div>
                                            <div class="input-fly input-fly-checkbox youama-showhideme">
                                                <input type="checkbox" id="youama-licence" name="youama-licence" value="ok">
                                                <label for="youama-licence">I accept the <a href="index.php/privacy-policy-cookie-restriction-mode/" target="_blank">Terms and Coditions</a></label>
                                                <div style="display: none;" class="youama-ajaxlogin-error err-nolicence"></div>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="youama-window-box last">
                                        <div class="youama-window-content box-contents youama-showhideme">
                                            <button type="button" class="button btn-proceed-checkout btn-checkout youama-ajaxlogin-button">
                                                <span>
                                                    <span>Register</span>
                                                </span>
                                            </button>
                                            <p id="y-to-login" class="yoauam-switch-window">or login</p>
                                        </div>
                                    </div>
    
                                </div><!-- end of youama-window-inside --> 
                                
                            </div><!-- end of youama-window-outside -->
                            
                        </div><!-- enf of youama-register-window -->
                    
                    	<div class="youama-ajaxlogin-loader"></div>
    
    				</div><!-- header-account -->
                                    
         		</div><!-- end of header -->
                
     		</div><!-- end of col-xs-12 -->
  		
        </div><!-- end of row -->
        
	</div><!-- end of container -->
    
</div><!-- end of header-container -->

<div class="youama_ajaxlogin-temp-error" style="display:none !important;">
    <div class="ytmpa-nofirstname">First name is required!</div>
    <div class="ytmpa-nolastname">Last name is required!</div>
    <div class="ytmpa-dirtyfirstname">First name is not valid!</div>
    <div class="ytmpa-dirtylastname">Last name is not valid!</div>

    <div class="ytmpa-wrongemail">This is not an email address!</div>
    <div class="ytmpa-noemail">Email address is required!</div>
    <div class="ytmpa-emailisexist">This email is already registered!</div>

    <div class="ytmpa-nopassword">Password is required!</div>
    <div class="ytmpa-dirtypassword">Enter a valid password!</div>
    <div class="ytmpa-shortpassword">Please enter 6 or more characters!</div>
    <div class="ytmpa-longpassword">Please enter 16 or less characters!</div>
    <div class="ytmpa-notsamepasswords">Passwords are not same!</div>
    <div class="ytmpa-nolicence">Terms and Conditions are required!</div>
    
    <div class="ytmpa-wronglogin">Email or Password is wrong!</div>
</div>