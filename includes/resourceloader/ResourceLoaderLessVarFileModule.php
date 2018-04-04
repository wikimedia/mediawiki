<?php

/**
 * Subclass with context specific LESS variables
 */
class ResourceLoaderLessVarFileModule extends ResourceLoaderFileModule {
	/**
	 * Takes a message and wraps it in quotes for compatibility with LESS parser
	 * (ModifyVars) method so that the variable can be loaded and made available to stylesheets.
	 * Note this does not take care of CSS escaping. That will be taken care of as part
	 * of CSS Janus.
	 * @param string $msg
	 * @return string wrapped LESS variable definition
	 */
	public static function wrapAndEscapeMessage( $msg ) {
		return CSSMin::serializeStringValue( $msg );
	}

	/**
	 * @param \ResourceLoaderContext $context
	 * @return array LESS variables
	 */
	protected function getLessVars( \ResourceLoaderContext $context ) {
		return [
			'msg-collapsible-collapse' => self::wrapAndEscapeMessage(
				$context->msg( 'collapsible-collapse' )->text()
			),
			'msg-collapsible-expand' => self::wrapAndEscapeMessage(
				$context->msg( 'collapsible-expand' )->text()
			),
		];
	}
}
