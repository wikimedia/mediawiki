<?php
/**
 * Dump $wg* variables.
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
 * @ingroup Maintenance
 * @author Antoine Musso <hashar@free.fr>
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class MWdumpWg extends Maintenance {
	/** Array of globals starting with 'wg' prefix */
	protected $wgList;

	protected $regex;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Dumps \$wg* variables";
		$this->addOption( 'regex', 'regex to filter variables with, will be prefixed with /^wm?g/ and will be case insensitive', false, true );
	}

	/** main entry point */
	public function execute() {
		$this->regex = sprintf( '/^wm?g.*(?i:%s).*/',
			$this->getOption( 'regex', '')
		);
		$this->initWgList();
		$this->dump();
	}

	/**
	 * Grab globals prefixed with 'wg'
	 */
	protected function initWgList() {
		$wg = array();
		foreach( $GLOBALS as $name => $value ) {
			if( preg_match( $this->regex , $name ) ) {
				$wg[$name] = $value;
			}
		}
		ksort( $wg );
		$this->wgList = $wg;
	}

	protected function dump() {
		foreach( $this->wgList as $key => $value ) {
			$isEmptyArray = is_array( $value ) && empty($array);
			print "\${$key} = ";
			ob_start();
			var_dump( $value );
			$dump = trim( ob_get_clean() );
			# Align value:
			$dump = preg_replace( "/=>\n\s*/", ' => ', $dump );
			# Insert an obvious comment about array being empty
			$dump = preg_replace( "/array\(0\) {\n\s*}/", 'array(0) { /** empty **/ }', $dump );
			#	print "array(0) { /** empty **/ }";
			print "$dump;\n";
		}
	}
}


$maintClass = 'MWdumpWg';
require_once( RUN_MAINTENANCE_IF_MAIN );
