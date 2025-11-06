<?php

namespace Wikimedia\Rdbms;

/**
 * Determine whether a site is statically configured as read-only.
 *
 * Unlike ReadOnlyMode, this only checks site configuration.
 * It does not confirm whether the primary database host actively accepts writes.
 *
 * @since 1.29
 */
class ConfiguredReadOnlyMode {
	/** @var string|false|null */
	private $reason;

	/** @var string|null */
	private $reasonFile;

	/**
	 * @param string|false|null $reason Current reason for read-only mode, if known. null means look
	 *   in $reasonFile instead.
	 * @param string|null $reasonFile A file to look in for a reason, if $reason is null. If it
	 *   exists and is non-empty, its contents are treated as the reason for read-only mode.
	 *   Otherwise, the site is not read-only.
	 */
	public function __construct( $reason, ?string $reasonFile = null ) {
		$this->reason = $reason;
		$this->reasonFile = $reasonFile;
	}

	/**
	 * Check whether the site is in read-only mode.
	 */
	public function isReadOnly(): bool {
		return $this->getReason() !== false;
	}

	/**
	 * @return string|false String when in read-only mode; false otherwise
	 */
	public function getReason() {
		if ( $this->reason !== null ) {
			return $this->reason;
		}
		if ( $this->reasonFile === null ) {
			return false;
		}
		// Try the reason file
		if ( is_file( $this->reasonFile ) && filesize( $this->reasonFile ) > 0 ) {
			$this->reason = file_get_contents( $this->reasonFile );
		}
		// No need to try the reason file again
		$this->reasonFile = null;
		return $this->reason ?? false;
	}

	/**
	 * Set the read-only mode, which will apply for the remainder of the
	 * request or until a service reset.
	 *
	 * @param string|false|null $msg
	 */
	public function setReason( $msg ): void {
		$this->reason = $msg;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( ConfiguredReadOnlyMode::class, 'ConfiguredReadOnlyMode' );
