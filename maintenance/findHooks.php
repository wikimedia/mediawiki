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
 * with the ones at https://www.mediawiki.org/wiki/Manual:Hooks
 *
 * Any instance of wfRunHooks that doesn't meet these parameters will be noted.
 *
 * Copyright © Antoine Musso
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
 * @author Antoine Musso <hashar at free dot fr>
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that compares documented and actually present mismatches.
 *
 * @ingroup Maintenance
 */
class FindHooks extends Maintenance {
	const FIND_NON_RECURSIVE = 0;
	const FIND_RECURSIVE = 1;

	/*
	 * Hooks that are ignored
	 */
	protected static $ignore = [ 'Test' ];

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Find hooks that are undocumented, missing, or just plain wrong' );
		$this->addOption( 'online', 'Check against MediaWiki.org hook documentation' );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		global $IP;

		$documentedHooks = $this->getHooksFromDoc( $IP . '/docs/hooks.txt' );
		$potentialHooks = [];
		$badHooks = [];

		$recurseDirs = [
			"$IP/includes/",
			"$IP/mw-config/",
			"$IP/languages/",
			"$IP/maintenance/",
			// Omit $IP/tests/phpunit as it contains hook tests that shouldn't be documented
			"$IP/tests/parser",
			"$IP/tests/phpunit/suites",
		];
		$nonRecurseDirs = [
			"$IP/",
		];

		foreach ( $recurseDirs as $dir ) {
			$ret = $this->getHooksFromDir( $dir, self::FIND_RECURSIVE );
			$potentialHooks = array_merge( $potentialHooks, $ret['good'] );
			$badHooks = array_merge( $badHooks, $ret['bad'] );
		}
		foreach ( $nonRecurseDirs as $dir ) {
			$ret = $this->getHooksFromDir( $dir );
			$potentialHooks = array_merge( $potentialHooks, $ret['good'] );
			$badHooks = array_merge( $badHooks, $ret['bad'] );
		}

		$documented = array_keys( $documentedHooks );
		$potential = array_keys( $potentialHooks );
		$potential = array_unique( $potential );
		$badHooks = array_diff( array_unique( $badHooks ), self::$ignore );
		$todo = array_diff( $potential, $documented, self::$ignore );
		$deprecated = array_diff( $documented, $potential, self::$ignore );

		// Check parameter count and references
		$badParameterCount = $badParameterReference = [];
		foreach ( $potentialHooks as $hook => $args ) {
			if ( !isset( $documentedHooks[$hook] ) ) {
				// Not documented, but that will also be in $todo
				continue;
			}
			$argsDoc = $documentedHooks[$hook];
			if ( $args === 'unknown' || $argsDoc === 'unknown' ) {
				// Could not get parameter information
				continue;
			}
			if ( count( $argsDoc ) !== count( $args ) ) {
				$badParameterCount[] = $hook . ': Doc: ' . count( $argsDoc ) . ' vs. Code: ' . count( $args );
			} else {
				// Check if & is equal
				foreach ( $argsDoc as $index => $argDoc ) {
					$arg = $args[$index];
					if ( ( $arg[0] === '&' ) !== ( $argDoc[0] === '&' ) ) {
						$badParameterReference[] = $hook . ': References different: Doc: ' . $argDoc .
							' vs. Code: ' . $arg;
					}
				}
			}
		}

		// Print the results
		$this->printArray( 'Undocumented', $todo );
		$this->printArray( 'Documented and not found', $deprecated );
		$this->printArray( 'Unclear hook calls', $badHooks );
		$this->printArray( 'Different parameter count', $badParameterCount );
		$this->printArray( 'Different parameter reference', $badParameterReference );

		if ( !$todo && !$deprecated && !$badHooks
			&& !$badParameterCount && !$badParameterReference
		) {
			$this->output( "Looks good!\n" );
		} else {
			$this->error( 'The script finished with errors.', 1 );
		}
	}

	/**
	 * Get the hook documentation, either locally or from MediaWiki.org
	 * @param string $doc
	 * @return array Array: key => hook name; value => array of arguments or string 'unknown'
	 */
	private function getHooksFromDoc( $doc ) {
		if ( $this->hasOption( 'online' ) ) {
			return $this->getHooksFromOnlineDoc();
		} else {
			return $this->getHooksFromLocalDoc( $doc );
		}
	}

	/**
	 * Get hooks from a local file (for example docs/hooks.txt)
	 * @param string $doc Filename to look in
	 * @return array Array: key => hook name; value => array of arguments or string 'unknown'
	 */
	private function getHooksFromLocalDoc( $doc ) {
		$m = [];
		$content = file_get_contents( $doc );
		preg_match_all(
			"/\n'(.*?)':.*((?:\n.+)*)/",
			$content,
			$m,
			PREG_SET_ORDER
		);

		// Extract the documented parameter
		$hooks = [];
		foreach ( $m as $match ) {
			$args = [];
			if ( isset( $match[2] ) ) {
				$n = [];
				if ( preg_match_all( "/\n(&?\\$\w+):.+/", $match[2], $n ) ) {
					$args = $n[1];
				}
			}
			$hooks[$match[1]] = $args;
		}
		return $hooks;
	}

	/**
	 * Get hooks from www.mediawiki.org using the API
	 * @return array Array: key => hook name; value => string 'unknown'
	 */
	private function getHooksFromOnlineDoc() {
		$allhooks = $this->getHooksFromOnlineDocCategory( 'MediaWiki_hooks' );
		$removed = $this->getHooksFromOnlineDocCategory( 'Removed_hooks' );
		return array_diff_key( $allhooks, $removed );
	}

	/**
	 * @param string $title
	 * @return array
	 */
	private function getHooksFromOnlineDocCategory( $title ) {
		$params = [
			'action' => 'query',
			'list' => 'categorymembers',
			'cmtitle' => "Category:$title",
			'cmlimit' => 500,
			'format' => 'json',
			'continue' => '',
		];

		$retval = [];
		while ( true ) {
			$json = Http::get(
				wfAppendQuery( 'http://www.mediawiki.org/w/api.php', $params ),
				[],
				__METHOD__
			);
			$data = FormatJson::decode( $json, true );
			foreach ( $data['query']['categorymembers'] as $page ) {
				if ( preg_match( '/Manual\:Hooks\/([a-zA-Z0-9- :]+)/', $page['title'], $m ) ) {
					// parameters are unknown, because that needs parsing of wikitext
					$retval[str_replace( ' ', '_', $m[1] )] = 'unknown';
				}
			}
			if ( !isset( $data['continue'] ) ) {
				return $retval;
			}
			$params = array_replace( $params, $data['continue'] );
		}
	}

	/**
	 * Get hooks from a PHP file
	 * @param string $filePath Full file path to the PHP file.
	 * @return array Array: key => hook name; value => array of arguments or string 'unknown'
	 */
	private function getHooksFromFile( $filePath ) {
		$content = file_get_contents( $filePath );
		$m = [];
		preg_match_all(
			// All functions which runs hooks
			'/(?:wfRunHooks|Hooks\:\:run)\s*\(\s*' .
				// First argument is the hook name as string
				'([\'"])(.*?)\1' .
				// Comma for second argument
				'(?:\s*(,))?' .
				// Second argument must start with array to be processed
				'(?:\s*(?:array\s*\(|\[)' .
				// Matching inside array - allows one deep of brackets
				'((?:[^\(\)\[\]]|\((?-1)\)|\[(?-1)\])*)' .
				// End
				'[\)\]])?/',
			$content,
			$m,
			PREG_SET_ORDER
		);

		// Extract parameter
		$hooks = [];
		foreach ( $m as $match ) {
			$args = [];
			if ( isset( $match[4] ) ) {
				$n = [];
				if ( preg_match_all( '/((?:[^,\(\)]|\([^\(\)]*\))+)/', $match[4], $n ) ) {
					$args = array_map( 'trim', $n[1] );
				}
			} elseif ( isset( $match[3] ) ) {
				// Found a parameter for Hooks::run,
				// but could not extract the hooks argument,
				// because there are given by a variable
				$args = 'unknown';
			}
			$hooks[$match[2]] = $args;
		}

		return $hooks;
	}

	/**
	 * Get bad hooks (where the hook name could not be determined) from a PHP file
	 * @param string $filePath Full filename to the PHP file.
	 * @return array Array of bad wfRunHooks() lines
	 */
	private function getBadHooksFromFile( $filePath ) {
		$content = file_get_contents( $filePath );
		$m = [];
		// We want to skip the "function wfRunHooks()" one.  :)
		preg_match_all( '/(?<!function )wfRunHooks\(\s*[^\s\'"].*/', $content, $m );
		$list = [];
		foreach ( $m[0] as $match ) {
			$list[] = $match . "(" . $filePath . ")";
		}

		return $list;
	}

	/**
	 * Get hooks from a directory of PHP files.
	 * @param string $dir Directory path to start at
	 * @param int $recursive Pass self::FIND_RECURSIVE
	 * @return array Array: key => hook name; value => array of arguments or string 'unknown'
	 */
	private function getHooksFromDir( $dir, $recurse = 0 ) {
		$good = [];
		$bad = [];

		if ( $recurse === self::FIND_RECURSIVE ) {
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $dir, RecursiveDirectoryIterator::SKIP_DOTS ),
				RecursiveIteratorIterator::SELF_FIRST
			);
		} else {
			$iterator = new DirectoryIterator( $dir );
		}

		foreach ( $iterator as $info ) {
			// Ignore directories, work only on php files,
			if ( $info->isFile() && in_array( $info->getExtension(), [ 'php', 'inc' ] )
				// Skip this file as it contains text that looks like a bad wfRunHooks() call
				&& $info->getRealPath() !== __FILE__
			) {
				$good = array_merge( $good, $this->getHooksFromFile( $info->getRealPath() ) );
				$bad = array_merge( $bad, $this->getBadHooksFromFile( $info->getRealPath() ) );
			}
		}

		return [ 'good' => $good, 'bad' => $bad ];
	}

	/**
	 * Nicely sort an print an array
	 * @param string $msg A message to show before the value
	 * @param array $arr
	 */
	private function printArray( $msg, $arr ) {
		asort( $arr );

		foreach ( $arr as $v ) {
			$this->output( "$msg: $v\n" );
		}
	}
}

$maintClass = 'FindHooks';
require_once RUN_MAINTENANCE_IF_MAIN;
