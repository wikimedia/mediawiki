<?php
/**
 * A class to help return information about a git repo MediaWiki may be inside
 * This is used by Special:Version and is also useful for the LocalSettings.php
 * of anyone working on large branches in git to setup config that show up only
 * when specific branches are currently checked out.
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

class GitInfo {

	/**
	 * Singleton for the repo at $IP
	 */
	protected static $repo = null;

	/**
	 * Location of the .git directory
	 */
	protected $basedir;

	/**
	 * Map of repo URLs to viewer URLs. Access via static method getViewers().
	 */
	private static $viewers = false;

	/**
	 * @param string $dir The root directory of the repo where the .git dir can be found
	 */
	public function __construct( $dir ) {
		$this->basedir = $dir . DIRECTORY_SEPARATOR . '.git';
		if ( is_readable( $this->basedir ) && !is_dir( $this->basedir ) ) {
			$GITfile = file_get_contents( $this->basedir );
			if ( strlen( $GITfile ) > 8 && substr( $GITfile, 0, 8 ) === 'gitdir: ' ) {
				$path = rtrim( substr( $GITfile, 8 ), "\r\n" );
				$isAbsolute = $path[0] === '/' || substr( $path, 1, 1 ) === ':';
				$this->basedir = $isAbsolute ? $path : $dir . DIRECTORY_SEPARATOR . $path;
			}
		}
	}

	/**
	 * Return a singleton for the repo at $IP
	 * @return GitInfo
	 */
	public static function repo() {
		global $IP;
		if ( is_null( self::$repo ) ) {
			self::$repo = new self( $IP );
		}
		return self::$repo;
	}

	/**
	 * Check if a string looks like a hex encoded SHA1 hash
	 *
	 * @param string $str The string to check
	 * @return bool Whether or not the string looks like a SHA1
	 */
	public static function isSHA1( $str ) {
		return !!preg_match( '/^[0-9A-F]{40}$/i', $str );
	}

	/**
	 * Return the HEAD of the repo (without any opening "ref: ")
	 * @return string The HEAD
	 */
	public function getHead() {
		$headFile = "{$this->basedir}/HEAD";

		if ( !is_readable( $headFile ) ) {
			return false;
		}

		$head = file_get_contents( $headFile );

		if ( preg_match( "/ref: (.*)/", $head, $m ) ) {
			return rtrim( $m[1] );
		} else {
			return rtrim( $head );
		}
	}

	/**
	 * Return the SHA1 for the current HEAD of the repo
	 * @return string A SHA1 or false
	 */
	public function getHeadSHA1() {
		$head = $this->getHead();

		// If detached HEAD may be a SHA1
		if ( self::isSHA1( $head ) ) {
			return $head;
		}

		// If not a SHA1 it may be a ref:
		$refFile = "{$this->basedir}/{$head}";
		if ( !is_readable( $refFile ) ) {
			return false;
		}

		$sha1 = rtrim( file_get_contents( $refFile ) );

		return $sha1;
	}

	/**
	 * Return the commit date of HEAD entry of the git code repository
	 *
	 * @since 1.22
	 * @return int|bool Commit date (UNIX timestamp) or false
	 */
	public function getHeadCommitDate() {
		global $wgGitBin;

		if ( !is_file( $wgGitBin ) || !is_executable( $wgGitBin ) ) {
			return false;
		}

		$environment = array( "GIT_DIR" => $this->basedir );
		$cmd = wfEscapeShellArg( $wgGitBin ) . " show -s --format=format:%ct HEAD";
		$retc = false;
		$commitDate = wfShellExec( $cmd, $retc, $environment );

		if ( $retc !== 0 ) {
			return false;
		} else {
			return (int)$commitDate;
		}
	}

	/**
	 * Return the name of the current branch, or HEAD if not found
	 * @return string The branch name, HEAD, or false
	 */
	public function getCurrentBranch() {
		$head = $this->getHead();
		if ( $head && preg_match( "#^refs/heads/(.*)$#", $head, $m ) ) {
			return $m[1];
		} else {
			return $head;
		}
	}

	/**
	 * Get an URL to a web viewer link to the HEAD revision.
	 *
	 * @return string|bool string if a URL is available or false otherwise.
	 */
	public function getHeadViewUrl() {
		$config = "{$this->basedir}/config";
		if ( !is_readable( $config ) ) {
			return false;
		}

		wfSuppressWarnings();
		$configArray = parse_ini_file( $config, true );
		wfRestoreWarnings();
		$remote = false;

		// Use the "origin" remote repo if available or any other repo if not.
		if ( isset( $configArray['remote origin'] ) ) {
			$remote = $configArray['remote origin'];
		} elseif ( is_array( $configArray ) ) {
			foreach ( $configArray as $sectionName => $sectionConf ) {
				if ( substr( $sectionName, 0, 6 ) == 'remote' ) {
					$remote = $sectionConf;
				}
			}
		}

		if ( $remote === false || !isset( $remote['url'] ) ) {
			return false;
		}

		$url = $remote['url'];
		if ( substr( $url, -4 ) !== '.git' ) {
			$url .= '.git';
		}
		foreach ( self::getViewers() as $repo => $viewer ) {
			$pattern = '#^' . $repo . '$#';
			if ( preg_match( $pattern, $url, $matches ) ) {
				$viewerUrl = preg_replace( $pattern, $viewer, $url );
				$headSHA1 = $this->getHeadSHA1();
				$replacements = array(
					'%h' => substr( $headSHA1, 0, 7 ),
					'%H' => $headSHA1,
					'%r' => urlencode( $matches[1] ),
				);
				return strtr( $viewerUrl, $replacements );
			}
		}
		return false;
	}

	/**
	 * @see self::getHeadSHA1
	 * @return string
	 */
	public static function headSHA1() {
		return self::repo()->getHeadSHA1();
	}

	/**
	 * @see self::getCurrentBranch
	 * @return string
	 */
	public static function currentBranch() {
		return self::repo()->getCurrentBranch();
	}

	/**
	 * @see self::getHeadViewUrl()
	 * @return bool|string
	 */
	public static function headViewUrl() {
		return self::repo()->getHeadViewUrl();
	}

	/**
	 * Gets the list of repository viewers
	 * @return array
	 */
	protected static function getViewers() {
		global $wgGitRepositoryViewers;

		if ( self::$viewers === false ) {
			self::$viewers = $wgGitRepositoryViewers;
			wfRunHooks( 'GitViewers', array( &self::$viewers ) );
		}

		return self::$viewers;
	}
}
