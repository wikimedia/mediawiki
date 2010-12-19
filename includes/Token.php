<?php
/**
 * Copyright © 2010
 * http://www.mediawiki.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * CSRF attacks (where a malicious website uses frames, <img> tags, or
 * similar, to prompt a wiki user to open a wiki page or submit a form,
 * without being aware of doing so) are most easily countered by using
 * tokens.  For normal browsing, loading the form for a protected action 
 * sets two copies of a random string: one in the $_SESSION, and one as 
 * a hidden field in the form.  When the form is submitted, it checks 
 * that a) the set of cookies submitted with the form *has* a copy of 
 * the session cookie, and b) that it matches.  Since malicious websites
 * don't have control over the session cookies, they can't craft a form
 * that can be instantly submitted which will have the appropriate tokens. 
 * 
 * Note that these tokens are distinct from those in User::setToken(), which
 * are used for persistent session authentication and are retained for as 
 * long as the user is logged in to the wiki.  These tokens are to protect
 * one individual action, and should ideally be cleared once the action is over.
 */
class Token {
	
	/*
	 * Some punctuation to prevent editing from broken 
	 *  text-mangling proxies.
	 */
	const TOKEN_SUFFIX = '+\\';
	
	/**
	 * Different tokens for different types of action.
	 * 
	 * We don't store tokens for some actions for anons
	 * so they can still do things when they have cookies disabled.
	 * So either use this for actions which anons can't access, or 
	 * where you don't mind an attacker being able to trigger the action
	 * anonymously from the user's IP.  However, the token is still 
	 * useful because it fails with some broken proxies.
	 */
	const ANONYMOUS = 'Edit';
	
	/**
	 * For actions requiring a medium level of protection, or where the 
	 * user will be making repeated actions: this token should not be
	 * cleared once the action is completed.  For instance, a user might
	 * revert mass vandalism from a user by loading their contribs and 
	 * ctrl+clicking each rollback link.  If we cleared the Token from 
	 * session after each rollback, they'd have to reload the contribs
	 * page each time, which would be annoying.
	 */
	const PERSISTENT = 'Action';
	
	/**
	 * For actions requiring a high level of protection, and where the user
	 * will not be performing multiple sequential actions without reloading 
	 * the form or link.  Eg login, block/protect/delete, userrights, etc.
	 * Callers should clear these tokens upon completion of the action, and 
	 * other callers should expect that they will be cleared.
	 */
	const UNIQUE = 'Unique';
	
	/**
	 * String the action which is being protected by the token 
	 * ('edit', 'login', 'rollback', etc)
	 */
	protected $type = self::ANONYMOUS;
	
	/**
	 * An instance-specific salt. So if you want to generate a hundred rollback 
	 * tokens for the watchlist, pass a $salt which is unique 
	 * to each revision. Only one token is stored in the session, but it is munged 
	 * with a different salt for each revision, so the required value in the HTML 
	 * is different for each case.
	 */
	protected $salt = '';
	
	protected $request;
	
	/**
	 * Constructor
	 * @param $salt String an instance-specific salt.  @see Token::$salt
	 * @param $type Token class constant identifier
	 * @param $request WebRequest most of the time you'll want to get/store
	 *     the tokens in $wgRequest, which is the default.
	 */
	public function __construct( $salt, $type = self::PERSISTENT, WebRequest $request = null ){
		global $wgRequest;
		$this->type = $type;
	
		if( is_array( $this->salt ) ) {
			$this->salt = implode( '|', $this->salt );
		} else {
			$this->salt = strval( $salt );
		}
		
		$this->request = $request instanceof WebRequest
			? $request
			: $wgRequest;
	}
	
	/**
	 * Ensure that a token is set in cookies, by setting a new one 
	 * if necessary.
	 * @param $purge Bool whether to overwrite an existing token in 
	 *     session if there is one.  This is more secure, but will
	 *     only allow one Token of a particular $action to be used on
	 *     the page (which may itself be a good thing).
	 * @return String The version of the token which should be included
	 *     in the HTML form/link.
	 */
	public function set( $purge = false ) {
		global $wgUser;
		if ( $this->type == self::ANONYMOUS && $wgUser->isAnon() ) {
			return self::TOKEN_SUFFIX;
		} 
		
		if( $purge || $this->get() === null ){
			$token = self::generate();
			if( session_id() == '' ) {
				wfSetupSession();
			}
			$this->store( $token );
		} else {
			$token = $this->get();
		}
		
		return md5( $token . $this->salt ) . self::TOKEN_SUFFIX;
	}
	
	/**
	 * Check whether the copy of the token submitted with a form
	 * matches the version stored in session
	 * @param $val String version submitted with the form.
	 * @return Mixed null if no session token was set, Bool false if there
	 *     was a token but it didn't match, Bool true if it matched correctly
	 */
	public function match( $val ){
		global $wgUser;
		if( $this->type == self::ANONYMOUS && $wgUser->isAnon() ){
			return $val === self::TOKEN_SUFFIX;
		}
		
		if( $this->get() === null ){
			return null;
		}

		return md5( $this->get() . $this->salt ) . self::TOKEN_SUFFIX === $val;
	}
	
	/**
	 * Delete the token after use, so it can't be used again.  This will 
	 * invalidate all tokens for this Token's action type.
	 */
	public function clear(){
		$this->store( null );
	}
	
	/**
	 * Prepare a new Token for a given action, set it in session, and
	 * return the value we need to pass in the HTML
	 * @param $salt String
	 * @param $type Token class constant identifier
	 * @return String token string to store in HTML
	 */
	public static function prepare( $salt, $type = self::PERSISTENT ){
		$t = new Token( $salt, $type );
		return $t->set( false );
	}

	/**
	 * Generate a random token
	 * @param $salt String Optional salt value
	 * @return String 32-char random token
	 */
	protected static function generate( $salt = '' ) {
		$rand = dechex( mt_rand() ) . dechex( mt_rand() );
		return md5( $rand . $salt );
	}
	
	/**
	 * Set the given token for the given action in the session
	 * @param $token String
	 * @param $action String
	 */
	protected function store( $token ){
		$this->request->setSessionData( "ws{$this->type}Token", $token );
	}
	
	/**
	 * Get the token set for a given action
	 * @return String or null if no token was stored in the session
	 */
	protected function get(){
		return $this->request->getSessionData( "ws{$this->type}Token" );
	}
}
