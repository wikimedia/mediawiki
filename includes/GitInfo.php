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
	 * Path to JSON cache file for pre-computed git information.
	 */
	protected $precomputedFile;

	/**
	 * Cached git information.
	 */
	protected $cache = array();

	/**
	 * Map of repo URLs to viewer URLs. Access via static method getViewers().
	 */
	private static $viewers = false;

	/**
	 * @param string $dir The root directory of the repo where the .git dir can be found
	 * @param bool $usePrecomputed Use precomputed information if available
	 * @see precomputeValues
	 */
	public function __construct( $dir, $usePrecomputed=true ) {
		$this->precomputedFile = $dir . DIRECTORY_SEPARATOR . '.mw-git-info.json';
		if ( $usePrecomputed && is_readable( $this->precomputedFile ) ) {
			$this->cache =
				FormatJson::decode( file_get_contents( $this->precomputedFile ), true );
		}

		if ( !$this->cacheIsComplete() ) {
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
	}

	/**
	 * Check to see if the current cache is fully populated.
	 *
	 * Note: This method is public only to make unit testing easier. There's
	 * really no strong reason that anything other than a test should want to
	 * call this method.
	 *
	 * @return bool True if all expected cache keys exist, false otherwise
	 */
	public function cacheIsComplete() {
		return isset( $this->cache['head'] ) &&
			isset( $this->cache['headSHA1'] ) &&
			isset( $this->cache['headCommitDate'] ) &&
			isset( $this->cache['branch'] ) &&
			isset( $this->cache['remoteURL'] );
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
		if ( !isset( $this->cache['head'] ) ) {
			$headFile = "{$this->basedir}/HEAD";
			$head = false;

			if ( is_readable( $headFile ) ) {
				$head = file_get_contents( $headFile );

				if ( preg_match( "/ref: (.*)/", $head, $m ) ) {
					$head = rtrim( $m[1] );
				} else {
					$head = rtrim( $head );
				}
			}
			$this->cache['head'] = $head;
		}
		return $this->cache['head'];
	}

	/**
	 * Return the SHA1 for the current HEAD of the repo
	 * @return string A SHA1 or false
	 */
	public function getHeadSHA1() {
		if ( !isset( $this->cache['headSHA1'] ) ) {
			$head = $this->getHead();
			$sha1 = false;

			// If detached HEAD may be a SHA1
			if ( self::isSHA1( $head ) ) {
				$sha1 = $head;
			} else {
				// If not a SHA1 it may be a ref:
				$refFile = "{$this->basedir}/{$head}";
				if ( is_readable( $refFile ) ) {
					$sha1 = rtrim( file_get_contents( $refFile ) );
				}
			}
			$this->cache['headSHA1'] = $sha1;
		}
		return $this->cache['headSHA1'];
	}

	/**
	 * Return the commit date of HEAD entry of the git code repository
	 *
	 * @since 1.22
	 * @return int|bool Commit date (UNIX timestamp) or false
	 */
	public function getHeadCommitDate() {
		global $wgGitBin;

		if ( !isset( $this->cache['headCommitDate'] ) ) {
			$date = false;
			if ( is_file( $wgGitBin ) && is_executable( $wgGitBin ) ) {
				$environment = array( "GIT_DIR" => $this->basedir );
				$cmd = wfEscapeShellArg( $wgGitBin ) .
					" show -s --format=format:%ct HEAD";
				$retc = false;
				$commitDate = wfShellExec( $cmd, $retc, $environment );
				if ( $retc === 0 ) {
					$date = (int)$commitDate;
				}
			}
			$this->cache['headCommitDate'] = $date;
		}
		return $this->cache['headCommitDate'];
	}

	/**
	 * Return the name of the current branch, or HEAD if not found
	 * @return string The branch name, HEAD, or false
	 */
	public function getCurrentBranch() {
		if ( !isset( $this->cache['branch'] ) ) {
			$branch = $this->getHead();
			if ( $branch && preg_match( "#^refs/heads/(.*)$#", $branch, $m ) ) {
				$branch = $m[1];
			}
			$this->cache['branch'] = $branch;
		}
		return $this->cache['branch'];
	}

	/**
	 * Get an URL to a web viewer link to the HEAD revision.
	 *
	 * @return string|bool string if a URL is available or false otherwise.
	 */
	public function getHeadViewUrl() {
		$url = $this->getRemoteUrl();
		if ( $url === false ) {
			return false;
		}
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
	 * Get the URL of the remote origin.
	 * @return string|bool string if a URL is available or false otherwise.
	 */
	protected function getRemoteUrl() {
		if ( !isset( $this->cache['remoteURL'] ) ) {
			$config = "{$this->basedir}/config";
			$url = false;
			if ( is_readable( $config ) ) {
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

				if ( $remote !== false && isset( $remote['url'] ) ) {
					$url = $remote['url'];
				}
			}
			$this->cache['remoteURL'] = $url;
		}
		return $this->cache['remoteURL'];
	}

	/**
	 * Precompute and cache git information.
	 *
	 * Creates a ".git-info.json" file in the directory associated with this
	 * GitInfo instance that contains the output of getHead(), getHeadSHA1(),
	 * getHeadCommitDate(), getCurrentBranch() and getRemoteUrl(). This cache
	 * file will be used by subsequent GitInfo objects referencing the same
	 * directory to avoid the need to examine the .git directory.
	 *
	 * @since 1.24
	 */
	public function precomputeValues() {
		$cache = array(
			'head' => $this->getHead(),
			'headSHA1' => $this->getHeadSHA1(),
			'headCommitDate' => $this->getHeadCommitDate(),
			'branch' => $this->getCurrentBranch(),
			'remoteURL' => $this->getRemoteUrl(),
		);
		file_put_contents( $this->precomputedFile, FormatJson::encode( $cache ) );
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
