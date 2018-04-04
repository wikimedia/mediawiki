<?php

/**
 * Subclass with context specific LESS variables
 */
class ResourceLoaderLessVarFileModule extends ResourceLoaderFileModule {
	/**
	 * Takes a message and wraps it in quotes for compatibility with LESS parser
	 * @param string $msg
	 * @return string wrapped LESS variable definition
	 */
	public static function escapeMessage( $msg ) {
		return "'" . str_replace( "'", "\'", $msg ) . "'";
	}

	/**
	 * @param \ResourceLoaderContext $context
	 * @return array LESS variables
	 */
	protected function getLessVars( \ResourceLoaderContext $context ) {
		return [
			'msg-collapsible-collapse' => self::escapeMessage( $context->msg( 'collapsible-expand' ) ),
			'msg-collapsible-expand' => self::escapeMessage( $context->msg( 'collapsible-expand' ) ),
		];
	}
}
