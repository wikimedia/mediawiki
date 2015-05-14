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
	 * @return array "credits" information to store
	 */
	public function extractInfo( $path, array $info );

	/**
	 * @return array With following keys:
	 * 		'globals' - variables to be set to $GLOBALS
	 * 		'defines' - constants to define
	 * 		'callbacks' - functions to be executed by the registry
	 * 		'credits' - metadata to be stored by registry
	 * 		'attributes' - registration info which isn't a global variable
	 */
	public function getExtractedInfo();

	/**
	 * Get the constraints for the provided info
	 *
	 * @since 1.26
	 * @param array $info
	 * @return array
	 */
	public function getConstraints( array $info );
}
