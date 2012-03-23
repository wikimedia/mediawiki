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
		return !!preg_match( '/^[0-9A-Z]{40}$/i', $str );
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