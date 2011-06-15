<?php
/**
 * Simple script that try to find documented hook and hooks actually
 * in the code and show what's missing.
 *
 * This script assumes that:
 * - hooks names in hooks.txt are at the beginning of a line and single quoted.
 * - hooks names in code are the first parameter of wfRunHooks.
 *
 * if --online option is passed, the script will compare the hooks in the code
 * with the ones at http://www.mediawiki.org/wiki/Manual:Hooks
 *
 * Any instance of wfRunHooks that doesn't meet these parameters will be noted.
 *
 * Copyright © Ashar Voultoiz
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
 * @author Ashar Voultoiz <hashar at free dot fr>
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class FindHooks extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Find hooks that are undocumented, missing, or just plain wrong';
		$this->addOption( 'online', 'Check against MediaWiki.org hook documentation' );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		global $IP;

		$documented = $this->getHooksFromDoc( $IP . '/docs/hooks.txt' );
		$potential = array();
		$bad = array();
		$pathinc = array(
			$IP . '/',
			$IP . '/includes/',
			$IP . '/includes/actions/',
			$IP . '/includes/api/',
			$IP . '/includes/cache/',
			$IP . '/includes/db/',
			$IP . '/includes/diff/',
			$IP . '/includes/filerepo/',
			$IP . '/includes/installer/',
			$IP . '/includes/interwiki/',
			$IP . '/includes/media/',
			$IP . '/includes/parser/',
			$IP . '/includes/resourceloader/',
			$IP . '/includes/revisiondelete/',
			$IP . '/includes/search/',
			$IP . '/includes/specials/',
			$IP . '/includes/upload/',
			$IP . '/languages/',
			$IP . '/maintenance/',
			$IP . '/tests/',
			$IP . '/tests/parser/',
			$IP . '/tests/phpunit/suites/',
			$IP . '/skins/',
		);

		foreach ( $pathinc as $dir ) {
			$potential = array_merge( $potential, $this->getHooksFromPath( $dir ) );
			$bad = array_merge( $bad, $this->getBadHooksFromPath( $dir ) );
		}

		$potential = array_unique( $potential );
		$bad = array_unique( $bad );
		$todo = array_diff( $potential, $documented );
		$deprecated = array_diff( $documented, $potential );

		// let's show the results:
		$this->printArray( 'Undocumented', $todo );
		$this->printArray( 'Documented and not found', $deprecated );
		$this->printArray( 'Unclear hook calls', $bad );

		if ( count( $todo ) == 0 && count( $deprecated ) == 0 && count( $bad ) == 0 )
		{
			$this->output( "Looks good!\n" );
		}
	}

	/**
	 * Get the hook documentation, either locally or from MediaWiki.org
	 * @return array of documented hooks
	 */
	private function getHooksFromDoc( $doc ) {
		if ( $this->hasOption( 'online' ) ) {
			// All hooks
			$allhookdata = Http::get( 'http://www.mediawiki.org/w/api.php?action=query&list=categorymembers&cmtitle=Category:MediaWiki_hooks&cmlimit=500&format=php' );
			$allhookdata = unserialize( $allhookdata );
			$allhooks = array();
			foreach ( $allhookdata['query']['categorymembers'] as $page ) {
				$found = preg_match( '/Manual\:Hooks\/([a-zA-Z0-9- :]+)/', $page['title'], $matches );
				if ( $found ) {
					$hook = str_replace( ' ', '_', $matches[1] );
					$allhooks[] = $hook;
				}
			}
			// Removed hooks
			$oldhookdata = Http::get( 'http://www.mediawiki.org/w/api.php?action=query&list=categorymembers&cmtitle=Category:Removed_hooks&cmlimit=500&format=php' );
			$oldhookdata = unserialize( $oldhookdata );
			$removed = array();
			foreach ( $oldhookdata['query']['categorymembers'] as $page ) {
				$found = preg_match( '/Manual\:Hooks\/([a-zA-Z0-9- :]+)/', $page['title'], $matches );
				if ( $found ) {
					$hook = str_replace( ' ', '_', $matches[1] );
					$removed[] = $hook;
				}
			}
			return array_diff( $allhooks, $removed );
		} else {
			$m = array();
			$content = file_get_contents( $doc );
			preg_match_all( "/\n'(.*?)'/", $content, $m );
			return array_unique( $m[1] );
		}
	}

	/**
	 * Get hooks from a PHP file
	 * @param $file Full filename to the PHP file.
	 * @return array of hooks found.
	 */
	private function getHooksFromFile( $file ) {
		$content = file_get_contents( $file );
		$m = array();
		preg_match_all( '/(?:wfRunHooks|Hooks\:\:run)\(\s*([\'"])(.*?)\1/', $content, $m );
		return $m[2];
	}

	/**
	 * Get hooks from the source code.
	 * @param $path Directory where the include files can be found
	 * @return array of hooks found.
	 */
	private function getHooksFromPath( $path ) {
		$hooks = array();
		$dh = opendir( $path );
		if ( $dh ) {
			while ( ( $file = readdir( $dh ) ) !== false ) {
				if ( filetype( $path . $file ) == 'file' ) {
					$hooks = array_merge( $hooks, $this->getHooksFromFile( $path . $file ) );
				}
			}
			closedir( $dh );
		}
		return $hooks;
	}

	/**
	 * Get bad hooks (where the hook name could not be determined) from a PHP file
	 * @param $file Full filename to the PHP file.
	 * @return array of bad wfRunHooks() lines
	 */
	private function getBadHooksFromFile( $file ) {
		$content = file_get_contents( $file );
		$m = array();
		# We want to skip the "function wfRunHooks()" one.  :)
		preg_match_all( '/(?<!function )wfRunHooks\(\s*[^\s\'"].*/', $content, $m );
		$list = array();
		foreach ( $m[0] as $match ) {
			$list[] = $match . "(" . $file . ")";
		}
		return $list;
	}

	/**
	 * Get bad hooks from the source code.
	 * @param $path Directory where the include files can be found
	 * @return array of bad wfRunHooks() lines
	 */
	private function getBadHooksFromPath( $path ) {
		$hooks = array();
		$dh = opendir( $path );
		if ( $dh ) {
			while ( ( $file = readdir( $dh ) ) !== false ) {
				# We don't want to read this file as it contains bad calls to wfRunHooks()
				if ( filetype( $path . $file ) == 'file' && !$path . $file == __FILE__ ) {
					$hooks = array_merge( $hooks, $this->getBadHooksFromFile( $path . $file ) );
				}
			}
			closedir( $dh );
		}
		return $hooks;
	}

	/**
	 * Nicely output the array
	 * @param $msg String: a message to show before the value
	 * @param $arr Array: an array
	 * @param $sort Boolean: whether to sort the array (Default: true)
	 */
	private function printArray( $msg, $arr, $sort = true ) {
		if ( $sort ) {
			asort( $arr );
		}
		foreach ( $arr as $v ) {
			$this->output( "$msg: $v\n" );
		}
	}
}

$maintClass = 'FindHooks';
require_once( RUN_MAINTENANCE_IF_MAIN );
