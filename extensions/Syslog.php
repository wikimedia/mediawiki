<?php
/* Syslog.php -- an extension to log events to the system logger
 * Copyright 2004 Evan Prodromou <evan@wikitravel.org>
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @author Evan Prodromou <evan@wikitravel.org>
 * @package MediaWiki
 * @subpackage Extensions
 */

if (defined('MEDIAWIKI')) {

	# Setup globals
	
	if (!isset($wgSyslogIdentity)) {
		$wgSyslogIdentity = $wgDBname;
	}
	if (!isset($wgSyslogFacility)) {
		$wgSyslogFacility = LOG_USER;
	}

	# Hook for login
	
	function syslogUserLogin(&$user) {
		syslog(LOG_INFO, "User '" . $user->getName() . "' logged in.");
		return true;
	}

	# Hook for logout
	
	function syslogUserLogout(&$user) {
		syslog(LOG_INFO, "User '" . $user->getName() . "' logged out.");
		return true;
	}

	# Hook for IP & user blocks
	
	function syslogBlockIp(&$block, &$user) {
		syslog(LOG_NOTICE, "User '" . $user->getName() . 
			   "' blocked '" . (($block->mUser) ? $block->mUser : $block->mAddress) .
			   "' for '" . $block->mReason . "' until '" . $block->mExpiry . "'");
		return true;
	}

	# Hook for article protection
	
	function syslogArticleProtect(&$article, &$user, $protect, &$reason, &$moveonly) {
		$title = $article->mTitle;
		syslog(LOG_NOTICE, "User '" . $user->getName() . "' " .
			   (($protect) ? "protected" : "unprotected") . " article '" .
			   $title->getPrefixedText() .
			   "' for '" . $reason . "' " . (($moveonly) ? "(moves only)" : "") );
		return true;
	}

	# Hook for article deletion
	
	function syslogArticleDelete(&$article, &$user, &$reason) {
		$title = $article->mTitle;
		syslog(LOG_NOTICE, "User '" . $user->getName() . "' deleted '" .
			   $title->getPrefixedText() .
			   "' for '" . $reason . "' ");
		return true;
	}

	# Hook for article save
	
	function syslogArticleSave(&$article, &$user, &$text, $summary, $isminor, $iswatch, $section) {
		$title = $article->mTitle;
		syslog(LOG_NOTICE, "User '" . $user->getName() . "' saved '" .
			   $title->getPrefixedText() .
			   "' with comment '" . $summary . "' ");
		return true;
	}

	# Setup -- called once environment is configured
	
	function setupSyslog() {
		
		global $wgSyslogIdentity, $wgSyslogFacility, $_syslogId;
		global $wgHooks;
		
		openlog($wgSyslogIdentity, LOG_ODELAY | LOG_PID, $wgSyslogFacility);
		
		$wgHooks['UserLoginComplete'][] = 'syslogUserLogin';
		$wgHooks['UserLogout'][] = 'syslogUserLogout';
		$wgHooks['BlockIpComplete'][] = 'syslogBlockIp';
		$wgHooks['ArticleProtectComplete'][] = 'syslogArticleProtect';
		$wgHooks['ArticleDeleteComplete'][] = 'syslogArticleDelete';
		$wgHooks['ArticleSaveComplete'][] = 'syslogArticleSave';
		
		return true;
	}

	# Add to global list of extensions
	
	$wgExtensionFunctions[] = setupSyslog;
}

?>