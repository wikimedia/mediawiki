<?php
/**
 * A class to help return information about a git repo MediaWiki may be inside
 * This is used by Special:Version and is also useful for the LocalSettings.php
 * of anyone working on large branches in git to setup config that show up only
 * when specific branches are currently checked out.
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
	 * Map of repo URLs to viewer URLs.
	 * Key is a pattern passed to preg_match() and preg_replace(),
	 * without the delimiters (which are #) and must match the whole URL.
	 * The value is the replacement for the key (it can contain $1, etc.)
	 * %h will be replaced by the short SHA-1 (7 first chars) and %H by the
	 * full SHA-1 of the HEAD revision.
	 */
	protected $viewers = array(
		'https://gerrit.wikimedia.org/r/p/(.*)' => 'https://gerrit.wikimedia.org/r/gitweb?p=$1;h=%H',
		'ssh://(?:[a-z0-9_]+@)?gerrit.wikimedia.org:29418/(.*)' => 'https://gerrit.wikimedia.org/r/gitweb?p=$1;h=%H',
	);

	/**
	 * @param $dir The root directory of the repo where the .git dir can be found
	 */
	public function __construct( $dir ) {
		$this->basedir = "{$dir}/.git/";
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
	 * @param $str The string to check
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
		$HEADfile = "{$this->basedir}/HEAD";

		if ( !is_readable( $HEADfile ) ) {
			return false;
		}

		$HEAD = file_get_contents( $HEADfile );

		if ( preg_match( "/ref: (.*)/", $HEAD, $m ) ) {
			return rtrim( $m[1] );
		} else {
			return $HEAD;
		}
	}

	/**
	 * Return the SHA1 for the current HEAD of the repo
	 * @return string A SHA1 or false
	 */
	public function getHeadSHA1() {
		$HEAD = $this->getHead();

		// If detached HEAD may be a SHA1
		if ( self::isSHA1( $HEAD ) ) {
			return $HEAD;
		}

		// If not a SHA1 it may be a ref:
		$REFfile = "{$this->basedir}{$HEAD}";
		if ( !is_readable( $REFfile ) ) {
			return false;
		}

		$sha1 = rtrim( file_get_contents( $REFfile ) );

		return $sha1;
	}

	/**
	 * Return the name of the current branch, or HEAD if not found
	 * @return string The branch name, HEAD, or false
	 */
	public function getCurrentBranch() {
		$HEAD = $this->getHead();
		if ( $HEAD && preg_match( "#^refs/heads/(.*)$#", $HEAD, $m ) ) {
			return $m[1];
		} else {
			return $HEAD;
		}
	}

	/**
	 * Get an URL to a web viewer link to the HEAD revision.
	 *
	 * @return string|false string if an URL is available or false otherwise.
	 */
	public function getHeadViewUrl() {
		$config = "{$this->basedir}/config";
		if ( !is_readable( $config ) ) {
			return false;
		}

		$configArray = parse_ini_file( $config, true );
		$remote = false;

		// Use the "origin" remote repo if available or any other repo if not.
		if ( isset( $configArray['remote origin'] ) ) {
			$remote = $configArray['remote origin'];
		} else {
			foreach( $configArray as $sectionName => $sectionConf ) {
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
		foreach( $this->viewers as $repo => $viewer ) {
			$m = array();
			$pattern = '#^' . $repo . '$#';
			if ( preg_match( $pattern, $url ) ) {
				$viewerUrl = preg_replace( $pattern, $viewer, $url );
				$headSHA1 = $this->getHeadSHA1();
				$replacements = array(
					'%h' => substr( $headSHA1, 0, 7 ),
					'%H' => $headSHA1
				);
				return strtr( $viewerUrl, $replacements );
			}
		}
		return false;
	}

	/**
	 * @see self::getHeadSHA1
	 */
	public static function headSHA1() {
		return self::repo()->getHeadSHA1();
	}

	/**
	 * @see self::getCurrentBranch
	 */
	public static function currentBranch() {
		return self::repo()->getCurrentBranch();
	}

}
