<?php

/**
 * Subclass with context specific LESS variables
 */
class ResourceLoaderLessVarFileModule extends ResourceLoaderFileModule {
	protected $messages = [
		'collapsible-collapse',
		'collapsible-expand',
	];

	/**
	 * Takes a message and wraps it in quotes for compatibility with LESS parser
	 * (ModifyVars) method so that the variable can be loaded and made available to stylesheets.
	 * Note this does not take care of CSS escaping. That will be taken care of as part
	 * of CSS Janus.
	 * @param string $msg
	 * @return string wrapped LESS variable definition
	 */
	private static function wrapAndEscapeMessage( $msg ) {
		return str_replace( "'", "\'", CSSMin::serializeStringValue( $msg ) );
	}

	/**
	 * @param \ResourceLoaderContext $context
	 * @return array LESS variables
	 */
	protected function getLessVars( \ResourceLoaderContext $context ) {
		$lang = $context->getLanguage();
		$store = $context->getResourceLoader()->getMessageBlobStore();
		$blob = $store->getBlob( $this, $lang );
		$data = json_decode( $blob, true );

		return [
			'msg-collapsible-collapse' => self::wrapAndEscapeMessage(
				$data['collapsible-collapse']
			),
			'msg-collapsible-expand' => self::wrapAndEscapeMessage(
				$data['collapsible-expand']
			),
		];
	}
}
