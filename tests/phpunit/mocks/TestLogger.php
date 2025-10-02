<?php
/**
 * Copyright (C) 2015 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * A logger that may be configured to either buffer logs or to print them to
 * the output where PHPUnit will complain about them.
 *
 * @since 1.27
 */
class TestLogger extends \Psr\Log\AbstractLogger {
	/** @var bool */
	private $collect;
	/** @var bool */
	private $collectContext;
	/** @var array */
	private $buffer = [];
	/** @var callable|null */
	private $filter;

	/**
	 * @param bool $collect Whether to collect logs, also see {@link setCollect}
	 * @param callable|null $filter Filter logs before collecting/printing. Signature is
	 *  string|null function ( string $message, string $level, array $context );
	 * @param bool $collectContext Whether to keep the context passed to log
	 *  (since 1.29), also see {@link setCollectContext}
	 */
	public function __construct( $collect = false, $filter = null, $collectContext = false ) {
		$this->collect = $collect;
		$this->collectContext = $collectContext;
		$this->filter = $filter;
	}

	/**
	 * Set the "collect" flag
	 * @param bool $collect
	 * @return TestLogger $this
	 */
	public function setCollect( $collect ) {
		$this->collect = $collect;
		return $this;
	}

	/**
	 * Set the collectContext flag
	 *
	 * @param bool $collectContext
	 * @since 1.29
	 * @return TestLogger $this
	 */
	public function setCollectContext( $collectContext ) {
		$this->collectContext = $collectContext;
		return $this;
	}

	/**
	 * Return the collected logs
	 * @return array Array of [ string $level, string $message ], or
	 *   [ string $level, string $message, array $context ] if $collectContext was true.
	 */
	public function getBuffer() {
		return $this->buffer;
	}

	/**
	 * Clear the collected log buffer
	 */
	public function clearBuffer() {
		$this->buffer = [];
	}

	/** @inheritDoc */
	public function log( $level, $message, array $context = [] ): void {
		$message = trim( $message );

		if ( $this->filter ) {
			$message = ( $this->filter )( $message, $level, $context );
			if ( $message === null ) {
				return;
			}
		}

		if ( $this->collect ) {
			if ( $this->collectContext ) {
				$this->buffer[] = [ $level, $message, $context ];
			} else {
				$this->buffer[] = [ $level, $message ];
			}
		} else {
			echo "LOG[$level]: $message\n";
		}
	}
}
