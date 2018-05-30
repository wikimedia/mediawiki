<?php

/**
 * Subclass with context specific LESS variables
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
	 * @return array $blob
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
