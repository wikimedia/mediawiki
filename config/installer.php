<?php

# MediaWiki web-based config/installation
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html


/**
 * This is to be the fancy new installer package for MediaWiki,
 * which will work with table prefixes, both MySQL and PostgreSQL, and
 * ideally such wacky things as text-based install as well as web.
 *
 * It's not done yet.
 *
 * @todo finish...
 * @package MediaWiki
 * @subpackage Installer
 */

$IP = ".."; # Just to suppress notices, not for anything useful
define( "MEDIAWIKI", true );
define( "MEDIAWIKI_INSTALL", true );
require_once( "../includes/Defines.php" );
require_once( "../includes/DefaultSettings.php" );
require_once( "../includes/MagicWord.php" );
require_once( "../includes/Namespace.php" );
require_once( "../install-utils.inc" );
require_once( "../maintenance/updaters.inc" );
require_once( "../maintenance/convertLinks.inc" );
require_once( "../maintenance/archives/moveCustomMessages.inc" );

class InstallInterface {
	function message( $text ) {
		echo $text . "\n";
	}
	
	function warning( $text ) {
		echo "** $text **\n";
	}
	
	function formatLink( $url, $text = '' ) {
		if( $text ) {
			return "$text ($url)";
		} else {
			return $url;
		}
	}
	
	function showHeader() {
		global $wgVersion;
		echo "MediaWiki $wgVersion installation\n\n";
	}
}

class Installer {
	var $settings = array();
	
	function Installer( &$interface ) {
		$this->ui =& $interface;
	}
	
	function runInstall() {
		$this->ui->showHeader();
		$this->preInstallChecks();
	}
	
	function override( $var, $value ) {
		$this->settings[$var] = $value;
	}
	
	function preInstallChecks() {
		$checks = array(
			'checkConfigured',
			'checkInstalled',
			'checkWritable',
			'checkPHP',
			'checkGlobals',
			'checkSafeMode',
			'checkSAPI',
			'checkMemory',
			'checkZlib',
			'checkImageMagick',
			'checkGD'
		);
		foreach( $checks as $check ) {
			if( !$this->$check() ) {
				$this->ui->warning( "Installation aborted." );
				return false;
			}
		}
	}
	
	function checkConfigured() {
		if( file_exists( "../LocalSettings.php" ) || file_exists( "../AdminSettings.php" ) ) {
			$this->ui->warning( "Already configured." );
			return false;
		}
		return true;
	}
	
	function checkInstalled() {
		if( file_exists( "./LocalSettings.php" ) || file_exists( "./AdminSettings.php" ) ) {
			$this->ui->warning( "Already configured; move LocalSettings.php from this directory to the parent dir and take the wiki for a spin." );
			return false;
		}
		return true;
	}
	
	function checkWritable() {
		if( !is_writable( "." ) ) {
			$this->ui->warning( "<h2>Can't write config file, aborting</h2>

	<p>In order to configure the wiki you have to make the <tt>config</tt> subdirectory
	writable by the web server. Once configuration is done you'll move the created
	<tt>LocalSettings.php</tt> to the parent directory, and for added safety you can
	then remove the <tt>config</tt> subdirectory entirely.</p>

	<p>To make the directory writable on a Unix/Linux system:</p>

	<pre>
	cd <i>/path/to/wiki</i>
	chmod a+w config
	</pre>" );

			return false;
		}
		return true;
	}
	
	function checkPHP() {
		$ver = phpversion();
		if( version_compare( $ver, "4.1.2", "lt" ) ) {
			$this->ui->warning( "Your version of PHP ($ver) is too old and will probably not work. We try to support 4.1.2 and above, but a current 4.3 or 5.0 release is preferred." );
			return false;
		}
		$this->ui->message( "PHP version $ver, ok" );
		if( version_compare( $ver, "gte", "5.0" ) ) {
			$this->ui->warning( "A PHP5-compatible version of the PHPTAL template system is not yet bundled. To get the regular default page layout, you will need to manually install a development snapshot. (The wiki will function normally using an older layout if you do not.)" );
		}
		return true;
	}
	
	function checkGlobals() {
		if( ini_get( 'register_globals' ) ) {
			$this->ui->warning( "PHP's " .
				$this->ui->formatLink( "http://php.net/register_globals", "register_globals" ) .
				"option is enabled." .
				"MediaWiki will work correctly, but this setting
	increases your exposure to potential security vulnerabilities in PHP-based
	software running on your server. <b>You should disable it if you are able.</b>" );
		}
		return true;
	}
	
	function checkSafeMode() {
		if( ini_get( 'safe_mode' ) ) {
			$this->ui->warning( "PHP's
	<a href='http://www.php.net/features.safe-mode'>safe mode</a> is active!</b>
	You will likely have problems caused by this. You may need to make the
	'images' subdirectory writable or specify a TMP environment variable pointing to
	a writable temporary directory owned by you, since safe mode breaks the system
	temporary directory." );
			$this->ui->message( "...due to safe mode restrictions, uploads will use flat directory mode if enabled." );
			$this->override( 'wgHashedUploadDirectory', true );
		} else {
			$this->ui->message( "PHP is not running in safe mode (this is good!)" );
			$this->ui->message( "...uploads will use hashed directory tree mode if enabled." );
		}
		return true;
	}
	
	function checkSAPI() {
		$sapi = php_sapi_name();
		$this->ui->message( "PHP server API is $sapi..." );
		switch( $sapi ) {
			case "apache":
			case "apache2handler":
				$this->ui->message( "...ok, using pretty URLs (<tt>index.php/Page_Title</tt>)" );
				break;
			case "cgi":
			case "cgi-fcgi":
			case "apache2filter":
				$this->ui->message( "using ugly URLs (<tt>index.php?title=Page_Title</tt>)" );
				$this->override( 'prettyURLs', false );
				# FIXME
				break;
			default:
				$this->ui->message( "unknown; using pretty URLs (<tt>index.php/Page_Title</tt>), if you have trouble change this in <tt>LocalSettings.php</tt>" );
		}
		return true;
	}
	
	function checkMemory() {
		$memlimit = ini_get( "memory_limit" );
		if( empty( $memlimit ) ) {
			$this->ui->message( "PHP is configured with no <tt>memory_limit</tt>." );
			return true;
		} else {
			$this->ui->message( "PHP's <tt>memory_limit</tt> is $memlimit... " );
			$n = IntVal( $memlimit );
			if( preg_match( '/^([0-9]+)[Mm]$/', trim( $memlimit ), $m ) ) {
				$n = IntVal( $m[1] * (1024*1024) );
			}
			if( $n < 20*1024*1024 ) {
				if( false === ini_set( "memory_limit", "20M" ) ) {
					$this->ui->warning( "...failed to raise the limit to 20M; you may have problems" );
				} else {
					$this->ui->message( "...raising limit to 20M" );
					$this->override( 'raiseMemory', true );
				}
			}
			return true;
		}
	}
	
	function checkZlib() {
		$zlib = function_exists( "gzencode" );
		if( $zlib ) {
			$this->ui->message( "Have zlib support; enabling output compression." );
			# This is a runtime thing, we're just letting the user know about it?
		} else {
			$this->ui->message( "No zlib support." );
		}
		return true;
	}
	
	function checkImageMagick() {
		$imcheck = array( "/usr/bin", "/usr/local/bin", "/sw/bin", "/opt/local/bin" );
		foreach( $imcheck as $dir ) {
			$im = "$dir/convert";
			if( file_exists( $im ) ) {
				$this->ui->message( "Found ImageMagick: <tt>$im</tt>; image thumbnailing will be enabled if you enable uploads." );
				$this->override( "wgImageMagick", $im );
				$this->override( "wgUseImageResize", true );
				break;
			}
		}
		return true;
	}
	
	function checkGD() {
		$gd = function_exists( "imagejpeg" );
		if( $gd ) {
			$this->ui->message( "Found GD graphics library built-in, image thumbnailing will be enabled if you enable uploads" );
			$this->override( "wgUseImageResize", true );
		} else {
			if( !isset( $this->settings['wgUseImageResize'] ) ) {
				$this->warning( "Couldn't find GD library or ImageMagick; image thumbnailing disabled." );
			}
		}
		return true;
	}
	
	
}

$ui =& new InstallInterface();
$i =& new Installer( $ui );
$i->runInstall();

?>