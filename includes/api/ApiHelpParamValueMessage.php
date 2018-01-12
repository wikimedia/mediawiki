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
 * @since 1.25
 */
class ApiHelpParamValueMessage extends Message {

	protected $paramValue;
	protected $deprecated;

	/**
	 * @see Message::__construct
	 *
	 * @param string $paramValue Parameter value being documented
	 * @param string $text Message to use.
	 * @param array $params Parameters for the message.
	 * @param bool $deprecated Whether the value is deprecated
	 * @throws InvalidArgumentException
	 * @since 1.30 Added the `$deprecated` parameter
	 */
	public function __construct( $paramValue, $text, $params = [], $deprecated = false ) {
		parent::__construct( $text, $params );
		$this->paramValue = $paramValue;
		$this->deprecated = (bool)$deprecated;
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
	 * Fetch the message.
	 * @return string
	 */
	public function fetchMessage() {
		if ( $this->message === null ) {
			$dep = '';
			if ( $this->isDeprecated() ) {
				$msg = new Message( 'api-help-param-deprecated' );
				$msg->interface = $this->interface;
				$msg->language = $this->language;
				$msg->useDatabase = $this->useDatabase;
				$msg->title = $this->title;
				$dep = '<span class="apihelp-deprecated">' . $msg->fetchMessage() . '</span> ';
			}
			$this->message = ";<span dir=\"ltr\" lang=\"en\">{$this->paramValue}</span>:"
				. $dep . parent::fetchMessage();
		}
		return $this->message;
	}

}
