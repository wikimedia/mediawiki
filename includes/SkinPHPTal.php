<?php
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
 * Generic PHPTal (http://phptal.sourceforge.net/) skin
 * Based on Brion's smarty skin
 * Copyright (C) Gabriel Wicke -- http://www.aulinx.de/
 *
 * Todo: Needs some serious refactoring into functions that correspond
 * to the computations individual esi snippets need. Most importantly no body
 * parsing for most of those of course.
 * 
 * Set this in LocalSettings to enable phptal:
 * set_include_path(get_include_path() . ":" . $IP.'/PHPTAL-NP-0.7.0/libs');
 * $wgUsePHPTal = true;
 *
 * @package MediaWiki
 * @subpackage Skins
 */

/**
 * This is not a valid entry point, perform no further processing unless
 * MEDIAWIKI is defined
 */
if( defined( 'MEDIAWIKI' ) ) {

require_once 'GlobalFunctions.php';
require_once 'SkinTemplate.php';

if( version_compare( phpversion(), "5.0", "lt" ) ) {
	define( 'OLD_PHPTAL', true );
	global $IP;
	require_once $IP.'/PHPTAL-NP-0.7.0/libs/PHPTAL.php';
} else {
	define( 'NEW_PHPTAL', true );
	# For now, PHPTAL 1.0.x must be installed via PEAR in system dir.
	require_once 'PEAR.php';
	require_once 'PHPTAL.php';
}

/**
 *
 * @package MediaWiki
 */
class SkinPHPTal extends SkinTemplate {
	/** */
	function initPage( &$out ) {
		parent::initPage( $out );
		$this->skinname  = 'monobook';
		$this->stylename = 'monobook';
		$this->template  = 'MonoBook';
	}

	/**
	 * If using PHPTAL 0.7 on PHP 4.x, return a PHPTAL template object.
	 * If using PHPTAL 1.0 on PHP 5.x, return a bridge object.
	 * @return object
	 * @access private
	 */
	function &setupTemplate( $file, $repository=false, $cache_dir=false ) {
		if( defined( 'NEW_PHPTAL' ) ) {
			return new PHPTAL_version_bridge( $file . '.pt', $repository, $cache_dir );
		} else {
			return new PHPTAL( $file . '.pt', $repository, $cache_dir );
		}
	}
	
	/**
	 * Output the string, or print error message if it's
	 * an error object of the appropriate type.
	 *
	 * @param mixed $str
	 * @access private
	 */
	function printOrError( &$str ) {
		if( PEAR::isError( $str ) ) {
			echo $str->toString(), "\n";
		} else {
			echo $str;
		}
	}
}

class PHPTAL_version_bridge {
	function PHPTAL_version_bridge( $file, $repository=false, $cache_dir=false ) {
		$this->tpl =& new PHPTAL( $file );
		if( $repository ) {
			$this->tpl->setTemplateRepository( $repository );
		}
	}
	
	function set( $name, $value ) {
		$this->tpl->$name = $value;
	}
	
	function setRef($name, &$value) {
		$this->set( $name, $value );
	}
	
	function setTranslator( &$t ) {
		$this->tpl->setTranslator( $t );
	}
	
	function execute() {
		/*
		try {
		*/
			return $this->tpl->execute();
		/*
		}
		catch (Exception $e) {
			echo "<div class='error' style='background: white; white-space: pre; position: absolute; z-index: 9999; border: solid 2px black; padding: 4px;'>We caught an exception...\n ";
			echo $e;
			echo "</div>";
		}
		*/
	}
}

} // end of if( defined( 'MEDIAWIKI' ) ) 
?>
