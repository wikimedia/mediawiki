<?php
/**
 * Copyright © 2014 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Message\Message;

/**
 * Message subclass that prepends wikitext for API help.
 *
 * This exists so the apihelp-*-paramvalue-*-* messages don't all have to
 * include markup wikitext while still keeping the
 * 'APIGetParamDescriptionMessages' hook simple.
 *
 * @newable
 * @since 1.25
 * @ingroup API
 */
class ApiHelpParamValueMessage extends Message {

	/** @var string */
	protected $paramValue;
	/** @var bool */
	protected $deprecated;
	/** @var bool */
	protected $internal;

	/**
	 * @see Message::__construct
	 * @stable to call
	 *
	 * @param string $paramValue Parameter value being documented
	 * @param string $text Message to use.
	 * @param array $params Parameters for the message.
	 * @param bool $deprecated Whether the value is deprecated
	 * @param bool $internal Whether the value is internal
	 * @since 1.30 Added the `$deprecated` parameter
	 * @since 1.35 Added the `$internal` parameter
	 */
	public function __construct(
		$paramValue,
		$text,
		$params = [],
		$deprecated = false,
		$internal = false
	) {
		parent::__construct( $text, $params );
		$this->paramValue = $paramValue;
		$this->deprecated = (bool)$deprecated;
		$this->internal = (bool)$internal;
	}

	/**
	 * Fetch the parameter value
	 * @return string
	 */
	public function getParamValue() {
		return $this->paramValue;
	}

	/**
	 * Fetch the 'deprecated' flag
	 * @since 1.30
	 * @return bool
	 */
	public function isDeprecated() {
		return $this->deprecated;
	}

	/**
	 * Fetch the 'internal' flag
	 * @since 1.35
	 * @return bool
	 */
	public function isInternal() {
		return $this->internal;
	}

	/**
	 * @return string
	 */
	public function fetchMessage() {
		if ( $this->message === null ) {
			$prefix = ";<span dir=\"ltr\" lang=\"en\">{$this->paramValue}</span>:";
			if ( $this->isDeprecated() ) {
				$prefix .= '<span class="apihelp-deprecated">' .
					$this->subMessage( 'api-help-param-deprecated' ) .
					'</span>' .
					$this->subMessage( 'word-separator' );
			}
			if ( $this->isInternal() ) {
				$prefix .= '<span class="apihelp-internal">' .
					$this->subMessage( 'api-help-param-internal' ) .
					'</span>' .
					$this->subMessage( 'word-separator' );
			}

			if ( $this->getLanguage()->getCode() === 'qqx' ) {
				# Insert a list of alternative message keys for &uselang=qqx.
				$keylist = implode( ' / ', $this->keysToTry );
				if ( $this->overriddenKey !== null ) {
					$keylist .= ' = ' . $this->overriddenKey;
				}
				$this->message = $prefix . "($keylist$*)";
			} else {
				$this->message = $prefix . parent::fetchMessage();
			}
		}
		return $this->message;
	}

	private function subMessage( string $key ): string {
		$msg = new Message( $key );
		$msg->isInterface = $this->isInterface;
		$msg->language = $this->language;
		$msg->useDatabase = $this->useDatabase;
		$msg->contextPage = $this->contextPage;
		return $msg->plain();
	}

}

/** @deprecated class alias since 1.43 */
class_alias( ApiHelpParamValueMessage::class, 'ApiHelpParamValueMessage' );
