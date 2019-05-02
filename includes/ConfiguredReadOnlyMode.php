<?php

/**
 * A read-only mode service which does not depend on LoadBalancer.
 * To obtain an instance, use MediaWikiServices::getInstance()->getConfiguredReadOnlyMode().
 *
 * @since 1.29
 */
class ConfiguredReadOnlyMode {
	/** @var string|boolean|null */
	private $reason;

	/** @var string|null */
	private $reasonFile;

	/**
	 * @param string|bool|null $reason Current reason for read-only mode, if known. null means look
	 *   in $reasonFile instead.
	 * @param string|null $reasonFile A file to look in for a reason, if $reason is null. If it
	 *   exists and is non-empty, its contents are treated as the reason for read-only mode.
	 *   Otherwise, the wiki is not read-only.
	 */
	public function __construct( $reason, $reasonFile = null ) {
		if ( $reason instanceof Config ) {
			// Before 1.34 we passed a whole Config object, which was overkill
			wfDeprecated( __METHOD__ . ' with Config passed to constructor', '1.34' );
			$reason = $reason->get( 'ReadOnly' );
			$reasonFile = $reason->get( 'ReadOnlyFile' );
		}
		$this->reason = $reason;
		$this->reasonFile = $reasonFile;
	}

	/**
	 * Check whether the wiki is in read-only mode.
	 *
	 * @return bool
	 */
	public function isReadOnly() {
		return $this->getReason() !== false;
	}

	/**
	 * Get the value of $wgReadOnly or the contents of $wgReadOnlyFile.
	 *
	 * @return string|bool String when in read-only mode; false otherwise
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
	 * @param string|null $msg
	 */
	public function setReason( $msg ) {
		$this->reason = $msg;
	}
}
