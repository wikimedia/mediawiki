<?php

/**
 * Subclass with context specific LESS variables
 */
class ResourceLoaderLessVarFileModule extends ResourceLoaderFileModule {
	/**
	 * @param \ResourceLoaderContext $context
	 * @return array LESS variables
	 */
	protected function getLessVars( \ResourceLoaderContext $context ) {
		return [
			'msg-collapsible-collapse' => $context->msg( 'collapsible-collapse' ),
			'msg-collapsible-expand' => $context->msg( 'collapsible-expand' ),
		];
	}
}
