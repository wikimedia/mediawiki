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
 * The guts of this have moved to SkinTemplate.php
 *
 * Make sure the appropriate version of PHPTAL is installed (0.7.0 for PHP4,
 * or 1.0.0 for PHP5) and available in the include_path. The system PEAR
 * directory is good.
 *
 * If PEAR or PHPTAL can't be loaded, it will try to gracefully fall back.
 * Turn on MediaWiki's debug log to see it complain.
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
	# PEAR and PHPTAL 0.7.x must be installed and in include_path
} else {
	define( 'NEW_PHPTAL', true );
	# PEAR and PHPTAL 1.0.x must be installed and in include_path
}

@include_once 'PEAR.php';
if( !class_exists( 'PEAR' ) ) {
	wfDebug( 'PHPTAL-based skin couldn\'t include PEAR.php' );
} else {

// PHPTAL may be in the libs dir direct, or under HTML/Template.
// Try them both to be safe.
@include_once 'HTML/Template/PHPTAL.php';
if( !class_exists( 'PHPTAL' ) ) {
	@include_once 'PHPTAL.php';
}

if( !class_exists( 'PHPTAL' ) ) {
	wfDebug( 'PHPTAL-based skin couldn\'t include PHPTAL.php' );
} else {

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

} // end of if( class_exists( 'PHPTAL' ) )
} // end of if( class_exists( 'PEAR' ) )
} // end of if( defined( 'MEDIAWIKI' ) ) 
?>
