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
	/*
	 * Hooks that are ignored
	 */
	protected static $ignore = array( 'testRunLegacyHooks' );

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

		$documentedHooks = $this->getHooksFromDoc( $IP . '/docs/hooks.txt' );
		$potentialHooks = array();
		$bad = array();

		// TODO: Don't hardcode the list of directories
		$pathinc = array(
			$IP . '/',
			$IP . '/includes/',
			$IP . '/includes/actions/',
			$IP . '/includes/api/',
			$IP . '/includes/cache/',
			$IP . '/includes/changes/',
			$IP . '/includes/changetags/',
			$IP . '/includes/clientpool/',
			$IP . '/includes/content/',
			$IP . '/includes/context/',
			$IP . '/includes/dao/',
			$IP . '/includes/db/',
			$IP . '/includes/debug/',
			$IP . '/includes/deferred/',
			$IP . '/includes/diff/',
			$IP . '/includes/exception/',
			$IP . '/includes/externalstore/',
			$IP . '/includes/filebackend/',
			$IP . '/includes/filerepo/',
			$IP . '/includes/filerepo/file/',
			$IP . '/includes/gallery/',
			$IP . '/includes/htmlform/',
			$IP . '/includes/installer/',
			$IP . '/includes/interwiki/',
			$IP . '/includes/jobqueue/',
			$IP . '/includes/json/',
			$IP . '/includes/logging/',
			$IP . '/includes/mail/',
			$IP . '/includes/media/',
			$IP . '/includes/page/',
			$IP . '/includes/parser/',
			$IP . '/includes/password/',
			$IP . '/includes/rcfeed/',
			$IP . '/includes/resourceloader/',
			$IP . '/includes/revisiondelete/',
			$IP . '/includes/search/',
			$IP . '/includes/site/',
			$IP . '/includes/skins/',
			$IP . '/includes/specialpage/',
			$IP . '/includes/specials/',
			$IP . '/includes/upload/',
			$IP . '/includes/utils/',
			$IP . '/languages/',
			$IP . '/maintenance/',
			$IP . '/maintenance/language/',
			$IP . '/tests/',
			$IP . '/tests/parser/',
			$IP . '/tests/phpunit/suites/',
		);

		foreach ( $pathinc as $dir ) {
			$potentialHooks = array_merge( $potentialHooks, $this->getHooksFromPath( $dir ) );
			$bad = array_merge( $bad, $this->getBadHooksFromPath( $dir ) );
		}

		$documented = array_keys( $documentedHooks );
		$potential = array_keys( $potentialHooks );
		$potential = array_unique( $potential );
		$bad = array_diff( array_unique( $bad ), self::$ignore );
		$todo = array_diff( $potential, $documented, self::$ignore );
		$deprecated = array_diff( $documented, $potential, self::$ignore );

		// Check parameter count and references
		$badParameterCount = $badParameterReference = array();
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

		// let's show the results:
		$this->printArray( 'Undocumented', $todo );
		$this->printArray( 'Documented and not found', $deprecated );
		$this->printArray( 'Unclear hook calls', $bad );
		$this->printArray( 'Different parameter count', $badParameterCount );
		$this->printArray( 'Different parameter reference', $badParameterReference );

		if ( count( $todo ) == 0 && count( $deprecated ) == 0 && count( $bad ) == 0
			&& count( $badParameterCount ) == 0 && count( $badParameterReference ) == 0
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
		$m = array();
		$content = file_get_contents( $doc );
		preg_match_all(
			"/\n'(.*?)':.*((?:\n.+)*)/",
			$content,
			$m,
			PREG_SET_ORDER
		);

		// Extract the documented parameter
		$hooks = array();
		foreach ( $m as $match ) {
			$args = array();
			if ( isset( $match[2] ) ) {
				$n = array();
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
		$params = array(
			'action' => 'query',
			'list' => 'categorymembers',
			'cmtitle' => "Category:$title",
			'cmlimit' => 500,
			'format' => 'json',
			'continue' => '',
		);

		$retval = array();
		while ( true ) {
			$json = Http::get(
				wfAppendQuery( 'http://www.mediawiki.org/w/api.php', $params ),
				array(),
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
	 * @param string $file Full filename to the PHP file.
	 * @return array Array: key => hook name; value => array of arguments or string 'unknown'
	 */
	private function getHooksFromFile( $file ) {
		$content = file_get_contents( $file );
		$m = array();
		preg_match_all(
			// All functions which runs hooks
			'/(?:wfRunHooks|Hooks\:\:run|ContentHandler\:\:runLegacyHooks)\s*\(\s*' .
				// First argument is the hook name as string
				'([\'"])(.*?)\1' .
				// Comma for second argument
				'(?:\s*(,))?' .
				// Second argument must start with array to be processed
				'(?:\s*array\s*\(' .
				// Matching inside array - allows one deep of brackets
				'((?:[^\(\)]|\([^\(\)]*\))*)' .
				// End
				'\))?/',
			$content,
			$m,
			PREG_SET_ORDER
		);

		// Extract parameter
		$hooks = array();
		foreach ( $m as $match ) {
			$args = array();
			if ( isset( $match[4] ) ) {
				$n = array();
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
	 * Get hooks from the source code.
	 * @param string $path Directory where the include files can be found
	 * @return array Array: key => hook name; value => array of arguments or string 'unknown'
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
	 * @param string $file Full filename to the PHP file.
	 * @return array Array of bad wfRunHooks() lines
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
	 * @param string $path Directory where the include files can be found
	 * @return array Array of bad wfRunHooks() lines
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
	 * @param string $msg A message to show before the value
	 * @param array $arr
	 * @param bool $sort Whether to sort the array (Default: true)
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
require_once RUN_MAINTENANCE_IF_MAIN;
