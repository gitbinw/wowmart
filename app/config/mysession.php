<?php
	// app/config/my_session.php
	//
	// Revert value and get rid of the referrer check even when,
	// Security.level is medium
	/*ini_restore('session.referer_check');
	ini_set('session.use_trans_sid', 0);
	ini_set('session.name', Configure::read('Session.cookie'));
	// Cookie is now destroyed when browser is closed, doesn't
	// persist for days as it does by default for security
	// low and medium
	ini_set('session.cookie_lifetime', 0);
	ini_set('session.save_path', TMP . 'sessions');
	// Cookie path is now '/' even if you app is within a sub
	// directory on the domain
	$this->path = '/';
	ini_set('session.cookie_path', $this->path);
	// Session cookie now persists across all subdomains
	ini_set('session.cookie_domain', env('HTTP_BASE'));*/
	
	$this->path = '/';
	ini_set('session.use_trans_sid', 0);
	ini_set('url_rewriter.tags', '');
	ini_set('session.serialize_handler', 'php');
	ini_set('session.use_cookies', 1);
	ini_set('session.name', Configure::read('Session.cookie'));
	ini_set('session.cookie_lifetime', 0);
	ini_set('session.cookie_path', $this->path);
	ini_set('session.auto_start', 0);
	ini_set('session.save_path', TMP . 'sessions');
	ini_set('session.cookie_domain', env('HTTP_BASE'));
?>