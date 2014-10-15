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
	 * @return array With 'globals', 'defines', 'callbacks', 'credits' keys.
	 */
	public function getExtractedInfo();
}
