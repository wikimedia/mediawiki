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

// Per https://phabricator.wikimedia.org/T241091
// phpcs:disable MediaWiki.Commenting.FunctionAnnotations.UnrecognizedAnnotation

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
		array $options = [],
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
	 * Return a subset of messages from a JSON string representation.
	 *
	 * @param string $blob JSON
	 * @param string[] $whitelist
	 * @return array
	 */
	private function pluckFromMessageBlob( $blob, array $whitelist ) : array {
		$data = json_decode( $blob, true );
		// Keep only the messages intended for LESS export
		// (opposite of getMesssages essentially).
		return array_intersect_key( $data, array_flip( $whitelist ) );
	}

	/**
	 * @inheritDoc
	 */
	protected function getMessageBlob( ResourceLoaderContext $context ) {
		$blob = parent::getMessageBlob( $context );
		return json_encode( (object)$this->pluckFromMessageBlob( $blob, $this->messages ) );
	}

	// phpcs:disable MediaWiki.Commenting.DocComment.SpacingDocTag, Squiz.WhiteSpace.FunctionSpacing.Before
	/**
	 * Escape and wrap a message value as literal string for LESS.
	 *
	 * This mostly lets CSSMin escape it and wrap it, but also escape single quotes
	 * for compatibility with LESS's feature of variable interpolation into other strings.
	 * This is relatively rare for most use of LESS, but for messages it is quite common.
	 *
	 * Example:
	 *
	 * @code
	 *     @x: "foo's";
	 *     .eg { content: 'Value is @{x}'; }
	 * @endcode
	 *
	 * Produces output: `.eg { content: 'Value is foo's'; }`.
	 * (Tested in less.php 1.8.1, and Less.js 2.7)
	 *
	 * @param string $msg
	 * @return string wrapped LESS variable value
	 */
	private static function wrapAndEscapeMessage( $msg ) {
		return str_replace( "'", "\'", CSSMin::serializeStringValue( $msg ) );
	}

	// phpcs:enable MediaWiki.Commenting.DocComment.SpacingDocTag, Squiz.WhiteSpace.FunctionSpacing.Before

	/**
	 * Get language-specific LESS variables for this module.
	 *
	 * @param ResourceLoaderContext $context
	 * @return array LESS variables
	 */
	protected function getLessVars( ResourceLoaderContext $context ) {
		$blob = parent::getMessageBlob( $context );
		$messages = $this->pluckFromMessageBlob( $blob, $this->lessVariables );

		$vars = parent::getLessVars( $context );
		foreach ( $messages as $msgKey => $value ) {
			$vars['msg-' . $msgKey] = self::wrapAndEscapeMessage( $value );
		}
		return $vars;
	}
}
