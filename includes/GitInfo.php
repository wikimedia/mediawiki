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

use MediaWiki\Shell\Shell;

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
	 * Location of the repository
	 */
	protected $repoDir;

	/**
	 * Path to JSON cache file for pre-computed git information.
	 */
	protected $cacheFile;

	/**
	 * Cached git information.
	 */
	protected $cache = [];

	/**
	 * @var array|false Map of repo URLs to viewer URLs. Access via static method getViewers().
	 */
	private static $viewers = false;

	/**
	 * @param string $repoDir The root directory of the repo where .git can be found
	 * @param bool $usePrecomputed Use precomputed information if available
	 * @see precomputeValues
	 */
	public function __construct( $repoDir, $usePrecomputed = true ) {
		$this->repoDir = $repoDir;
		$this->cacheFile = self::getCacheFilePath( $repoDir );
		wfDebugLog( 'gitinfo',
			"Computed cacheFile={$this->cacheFile} for {$repoDir}"
		);
		if ( $usePrecomputed &&
			$this->cacheFile !== null &&
			is_readable( $this->cacheFile )
		) {
			$this->cache = FormatJson::decode(
				file_get_contents( $this->cacheFile ),
				true
			);
			wfDebugLog( 'gitinfo', "Loaded git data from cache for {$repoDir}" );
		}

		if ( !$this->cacheIsComplete() ) {
			wfDebugLog( 'gitinfo', "Cache incomplete for {$repoDir}" );
			$this->basedir = $repoDir . DIRECTORY_SEPARATOR . '.git';
			if ( is_readable( $this->basedir ) && !is_dir( $this->basedir ) ) {
				$GITfile = file_get_contents( $this->basedir );
				if ( strlen( $GITfile ) > 8 &&
					substr( $GITfile, 0, 8 ) === 'gitdir: '
				) {
					$path = rtrim( substr( $GITfile, 8 ), "\r\n" );
					if ( $path[0] === '/' || substr( $path, 1, 1 ) === ':' ) {
						// Path from GITfile is absolute
						$this->basedir = $path;
					} else {
						$this->basedir = $repoDir . DIRECTORY_SEPARATOR . $path;
					}
				}
			}
		}
	}

	/**
	 * Compute the path to the cache file for a given directory.
	 *
	 * @param string $repoDir The root directory of the repo where .git can be found
	 * @return string Path to GitInfo cache file in $wgGitInfoCacheDirectory or
	 * fallback in the extension directory itself
	 * @since 1.24
	 */
	protected static function getCacheFilePath( $repoDir ) {
		global $IP, $wgGitInfoCacheDirectory;

		if ( $wgGitInfoCacheDirectory ) {
			// Convert both $IP and $repoDir to canonical paths to protect against
			// $IP having changed between the settings files and runtime.
			$realIP = realpath( $IP );
			$repoName = realpath( $repoDir );
			if ( $repoName === false ) {
				// Unit tests use fake path names
				$repoName = $repoDir;
			}
			if ( strpos( $repoName, $realIP ) === 0 ) {
				// Strip $IP from path
				$repoName = substr( $repoName, strlen( $realIP ) );
			}
			// Transform path to git repo to something we can safely embed in
			// a filename
			$repoName = strtr( $repoName, DIRECTORY_SEPARATOR, '-' );
			$fileName = 'info' . $repoName . '.json';
			$cachePath = "{$wgGitInfoCacheDirectory}/{$fileName}";
			if ( is_readable( $cachePath ) ) {
				return $cachePath;
			}
		}

		return "$repoDir/gitinfo.json";
	}

	/**
	 * Get the singleton for the repo at $IP
	 *
	 * @return GitInfo
	 */
	public static function repo() {
		if ( is_null( self::$repo ) ) {
			global $IP;
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
		return (bool)preg_match( '/^[0-9A-F]{40}$/i', $str );
	}

	/**
	 * Get the HEAD of the repo (without any opening "ref: ")
	 *
	 * @return string|bool The HEAD (git reference or SHA1) or false
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
	 * Get the SHA1 for the current HEAD of the repo
	 *
	 * @return string|bool A SHA1 or false
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
				$packedRefs = "{$this->basedir}/packed-refs";
				$headRegex = preg_quote( $head, '/' );
				if ( is_readable( $refFile ) ) {
					$sha1 = rtrim( file_get_contents( $refFile ) );
				} elseif ( is_readable( $packedRefs ) &&
					preg_match( "/^([0-9A-Fa-f]{40}) $headRegex$/m", file_get_contents( $packedRefs ), $matches )
				) {
					$sha1 = $matches[1];
				}
			}
			$this->cache['headSHA1'] = $sha1;
		}
		return $this->cache['headSHA1'];
	}

	/**
	 * Get the commit date of HEAD entry of the git code repository
	 *
	 * @since 1.22
	 * @return int|bool Commit date (UNIX timestamp) or false
	 */
	public function getHeadCommitDate() {
		global $wgGitBin;

		if ( !isset( $this->cache['headCommitDate'] ) ) {
			$date = false;
			if ( is_file( $wgGitBin ) &&
				is_executable( $wgGitBin ) &&
				!Shell::isDisabled() &&
				$this->getHead() !== false
			) {
				$cmd = [
					$wgGitBin,
					'show',
					'-s',
					'--format=format:%ct',
					'HEAD',
				];
				$gitDir = realpath( $this->basedir );
				$result = Shell::command( $cmd )
					->environment( [ 'GIT_DIR' => $gitDir ] )
					->restrict( Shell::RESTRICT_DEFAULT | Shell::NO_NETWORK )
					->whitelistPaths( [ $gitDir, $this->repoDir ] )
					->execute();

				if ( $result->getExitCode() === 0 ) {
					$date = (int)$result->getStdout();
				}
			}
			$this->cache['headCommitDate'] = $date;
		}
		return $this->cache['headCommitDate'];
	}

	/**
	 * Get the name of the current branch, or HEAD if not found
	 *
	 * @return string|bool The branch name, HEAD, or false
	 */
	public function getCurrentBranch() {
		if ( !isset( $this->cache['branch'] ) ) {
			$branch = $this->getHead();
			if ( $branch &&
				preg_match( "#^refs/heads/(.*)$#", $branch, $m )
			) {
				$branch = $m[1];
			}
			$this->cache['branch'] = $branch;
		}
		return $this->cache['branch'];
	}

	/**
	 * Get an URL to a web viewer link to the HEAD revision.
	 *
	 * @return string|bool String if a URL is available or false otherwise
	 */
	public function getHeadViewUrl() {
		$url = $this->getRemoteUrl();
		if ( $url === false ) {
			return false;
		}
		foreach ( self::getViewers() as $repo => $viewer ) {
			$pattern = '#^' . $repo . '$#';
			if ( preg_match( $pattern, $url, $matches ) ) {
				$viewerUrl = preg_replace( $pattern, $viewer, $url );
				$headSHA1 = $this->getHeadSHA1();
				$replacements = [
					'%h' => substr( $headSHA1, 0, 7 ),
					'%H' => $headSHA1,
					'%r' => urlencode( $matches[1] ),
					'%R' => $matches[1],
				];
				return strtr( $viewerUrl, $replacements );
			}
		}
		return false;
	}

	/**
	 * Get the URL of the remote origin.
	 * @return string|bool String if a URL is available or false otherwise.
	 */
	protected function getRemoteUrl() {
		if ( !isset( $this->cache['remoteURL'] ) ) {
			$config = "{$this->basedir}/config";
			$url = false;
			if ( is_readable( $config ) ) {
				Wikimedia\suppressWarnings();
				$configArray = parse_ini_file( $config, true );
				Wikimedia\restoreWarnings();
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
	 * Precompute and cache git information.
	 *
	 * Creates a JSON file in the cache directory associated with this
	 * GitInfo instance. This cache file will be used by subsequent GitInfo objects referencing
	 * the same directory to avoid needing to examine the .git directory again.
	 *
	 * @since 1.24
	 */
	public function precomputeValues() {
		if ( $this->cacheFile !== null ) {
			// Try to completely populate the cache
			$this->getHead();
			$this->getHeadSHA1();
			$this->getHeadCommitDate();
			$this->getCurrentBranch();
			$this->getRemoteUrl();

			if ( !$this->cacheIsComplete() ) {
				wfDebugLog( 'gitinfo',
					"Failed to compute GitInfo for \"{$this->basedir}\""
				);
				return;
			}

			$cacheDir = dirname( $this->cacheFile );
			if ( !file_exists( $cacheDir ) &&
				!wfMkdirParents( $cacheDir, null, __METHOD__ )
			) {
				throw new MWException( "Unable to create GitInfo cache \"{$cacheDir}\"" );
			}

			file_put_contents( $this->cacheFile, FormatJson::encode( $this->cache ) );
		}
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
			Hooks::run( 'GitViewers', [ &self::$viewers ] );
		}

		return self::$viewers;
	}
}
