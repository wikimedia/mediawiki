<?
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
	
	# Setup -- called once environment is configured
	
	function setupSyslog() {
		
		global $wgSyslogIdentity, $wgSyslogFacility, $_syslogId;
		global $wgHooks;
		
		openlog($wgSyslogIdentity, LOG_ODELAY | LOG_PID, $wgSyslogFacility);
		
		$wgHooks['UserLoginComplete'][] = syslogUserLogin;
		$wgHooks['UserLogout'][] = syslogUserLogout;
		
		return true;
	}

	# Add to global list of extensions
	
	$wgExtensionFunctions[] = setupSyslog;
}

?>