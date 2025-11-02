<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use Wikimedia\AtEase\AtEase;

/**
 * Depend on a file.
 *
 * @newable
 * @ingroup Language
 */
class FileDependency extends CacheDependency {
	/** @var string */
	private $filename;
	/** @var null|false|int */
	private $timestamp;

	/**
	 * Create a file dependency
	 *
	 * @stable to call
	 *
	 * @param string $filename The name of the file, preferably fully qualified
	 * @param null|false|int $timestamp The unix last modified timestamp, or false if the
	 *        file does not exist. If omitted, the timestamp will be loaded from
	 *        the file.
	 *
	 * A dependency on a nonexistent file will be triggered when the file is
	 * created. A dependency on an existing file will be triggered when the
	 * file is changed.
	 */
	public function __construct( $filename, $timestamp = null ) {
		$this->filename = $filename;
		$this->timestamp = $timestamp;
	}

	/**
	 * @return array
	 */
	public function __sleep() {
		$this->loadDependencyValues();

		return [ 'filename', 'timestamp' ];
	}

	public function loadDependencyValues() {
		if ( $this->timestamp === null ) {
			AtEase::suppressWarnings();
			# Dependency on a non-existent file stores "false"
			# This is a valid concept!
			$this->timestamp = filemtime( $this->filename );
			AtEase::restoreWarnings();
		}
	}

	/** @inheritDoc */
	public function isExpired() {
		AtEase::suppressWarnings();
		$lastmod = filemtime( $this->filename );
		AtEase::restoreWarnings();
		if ( $lastmod === false ) {
			if ( $this->timestamp === false ) {
				# Still nonexistent
				return false;
			}

			# Deleted
			wfDebug( "Dependency triggered: {$this->filename} deleted." );

			return true;
		}

		if ( $lastmod > $this->timestamp ) {
			# Modified or created
			wfDebug( "Dependency triggered: {$this->filename} changed." );

			return true;
		}

		# Not modified
		return false;
	}
}
