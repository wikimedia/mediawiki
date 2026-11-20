<?php
/**
 * Copyright © 2014 Wikimedia Foundation and contributors
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Message\Message;
use Wikimedia\Message\MessageSpecifier;

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

	/**
	 * @see Message::__construct
	 * @stable to call
	 *
	 * @param string $paramValue Parameter value being documented, as wikitext
	 * @param string $text Message to use.
	 * @param array $params Parameters for the message.
	 * @param bool|MessageSpecifier $deprecated Whether the value is deprecated,
	 *  and an optional message describing the deprecation or replacement.
	 * @param bool $internal Whether the value is internal
	 * @since 1.30 Added the `$deprecated` parameter
	 * @since 1.35 Added the `$internal` parameter
	 */
	public function __construct(
		protected readonly string $paramValue,
		$text,
		$params = [],
		protected readonly bool|MessageSpecifier $deprecated = false,
		protected readonly bool $internal = false,
	) {
		parent::__construct( $text, $params );
	}

	/**
	 * Fetch the parameter value
	 * @return string Wikitext
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
		return $this->deprecated !== false;
	}

	/**
	 * Fetch the deprecation message.
	 * @since 1.46
	 */
	public function deprecationMsg(): ?MessageSpecifier {
		return $this->deprecated instanceof MessageSpecifier ?
			$this->deprecated : null;
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
				$deprecationMsg = $this->deprecationMsg();
				$divspan = $deprecationMsg === null ? 'span' : 'div';
				$prefix .= "<$divspan class='apihelp-deprecated'>" .
					$this->subMessage( 'api-help-param-deprecated' );
				if ( $deprecationMsg !== null ) {
					$prefix .= $this->subMessage( 'word-separator' ) .
						$this->subMessage(
							Message::newFromSpecifier( $deprecationMsg )
						);
				}
				$prefix .= "</$divspan>" .
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

	private function subMessage( string|Message $key ): string {
		$msg = $key instanceof Message ? $key : new Message( $key );
		$msg->isInterface = $this->isInterface;
		$msg->language = $this->language;
		$msg->useDatabase = $this->useDatabase;
		$msg->contextPage = $this->contextPage;
		return $msg->plain();
	}

}

/** @deprecated class alias since 1.43 */
class_alias( ApiHelpParamValueMessage::class, 'ApiHelpParamValueMessage' );
