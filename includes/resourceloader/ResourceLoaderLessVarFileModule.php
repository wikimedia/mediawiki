<?php
/**
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
 * Module augmented with context-specific LESS variables.
 *
 * @ingroup ResourceLoader
 * @since 1.32
 */
class ResourceLoaderLessVarFileModule extends ResourceLoaderFileModule {
	protected $lessVariables = [];

	/**
	 * @inheritDoc
	 */
	public function __construct(
		$options = [],
		$localBasePath = null,
		$remoteBasePath = null
	) {
		if ( isset( $options['lessMessages'] ) ) {
			$this->lessVariables = $options['lessMessages'];
		}
		parent::__construct( $options, $localBasePath, $remoteBasePath );
	}

	/**
	 * @inheritDoc
	 */
	public function getMessages() {
		// Overload so MessageBlobStore can detect updates to messages and purge as needed.
		return array_merge( $this->messages, $this->lessVariables );
	}

	/**
	 * Exclude a set of messages from a JSON string representation
	 *
	 * @param string $blob
	 * @param array $exclusions
	 * @return object $blob
	 */
	protected function excludeMessagesFromBlob( $blob, $exclusions ) {
		$data = json_decode( $blob, true );
		// unset the LESS variables so that they are not forwarded to JavaScript
		foreach ( $exclusions as $key ) {
			unset( $data[$key] );
		}
		return (object)$data;
	}

	/**
	 * @inheritDoc
	 */
	protected function getMessageBlob( ResourceLoaderContext $context ) {
		$blob = parent::getMessageBlob( $context );
		return json_encode( $this->excludeMessagesFromBlob( $blob, $this->lessVariables ) );
	}

	/**
	 * Takes a message and wraps it in quotes for compatibility with LESS parser
	 * (ModifyVars) method so that the variable can be loaded and made available to stylesheets.
	 * Note this does not take care of CSS escaping. That will be taken care of as part
	 * of CSS Janus.
	 *
	 * @param string $msg
	 * @return string wrapped LESS variable definition
	 */
	private static function wrapAndEscapeMessage( $msg ) {
		return str_replace( "'", "\'", CSSMin::serializeStringValue( $msg ) );
	}

	/**
	 * Get language-specific LESS variables for this module.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array LESS variables
	 */
	protected function getLessVars( ResourceLoaderContext $context ) {
		$blob = parent::getMessageBlob( $context );
		$lessMessages = $this->excludeMessagesFromBlob( $blob, $this->messages );

		$vars = parent::getLessVars( $context );
		foreach ( $lessMessages as $msgKey => $value ) {
			$vars['msg-' . $msgKey] = self::wrapAndEscapeMessage( $value );
		}
		return $vars;
	}
}
