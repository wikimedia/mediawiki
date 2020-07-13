<?php
/**
 * Copyright © 2014 Wikimedia Foundation and contributors
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

	protected $paramValue;
	protected $deprecated;
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
	 * @throws InvalidArgumentException
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
	 * Fetch the message.
	 * @return string
	 */
	public function fetchMessage() {
		if ( $this->message === null ) {
			$prefix = '';
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
			$this->message = ";<span dir=\"ltr\" lang=\"en\">{$this->paramValue}</span>:"
				. $prefix . parent::fetchMessage();
		}
		return $this->message;
	}

	private function subMessage( $key ) {
		$msg = new Message( $key );
		$msg->interface = $this->interface;
		$msg->language = $this->language;
		$msg->useDatabase = $this->useDatabase;
		$msg->title = $this->title;
		return $msg->fetchMessage();
	}

}
