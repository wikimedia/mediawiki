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
	public static function wrapMessage( $msg ) {
		return "'" . str_replace( "'", "\'", $msg ) . "'";
	}

	/**
	 * @param \ResourceLoaderContext $context
	 * @return array LESS variables
	 */
	protected function getLessVars( \ResourceLoaderContext $context ) {
		return [
			'msg-collapsible-collapse' => self::wrapMessage(
				$context->msg( 'collapsible-collapse' )->escaped()
			),
			'msg-collapsible-expand' => self::wrapMessage(
				$context->msg( 'collapsible-expand' )->escaped()
			),
		];
	}
}
