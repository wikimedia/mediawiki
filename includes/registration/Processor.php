<?php

/**
 * Processors read associated arrays and register
 * whatever is required
 *
 * @since 1.25
 */
interface Processor {

	/**
	 * Main entry point, processes the information
	 * provided.
	 * Callers should call "callback" after calling
	 * this function.
	 *
	 * @param string $path Absolute path of JSON file
	 * @param array $info
	 * @param int $version manifest_version for info
	 * @return array "credits" information to store
	 */
	public function extractInfo( $path, array $info, $version );

	/**
	 * @return array With following keys:
	 *     'globals' - variables to be set to $GLOBALS
	 *     'defines' - constants to define
	 *     'config' - configuration information
	 *     'callbacks' - functions to be executed by the registry
	 *     'credits' - metadata to be stored by registry
	 *     'attributes' - registration info which isn't a global variable
	 */
	public function getExtractedInfo();

	/**
	 * Get the requirements for the provided info
	 *
	 * @since 1.26
	 * @param array $info
	 * @return array Where keys are the name to have a constraint on,
	 * 		like 'MediaWiki'. Values are a constraint string like "1.26.1".
	 */
	public function getRequirements( array $info );

	/**
	 * Get the path for additional autoloaders, e.g. the one of Composer.
	 *
	 * @param string $dir
	 * @param array $info
	 * @return array Containing the paths for autoloader file(s).
	 * @since 1.27
	 */
	public function getExtraAutoloaderPaths( $dir, array $info );
}
