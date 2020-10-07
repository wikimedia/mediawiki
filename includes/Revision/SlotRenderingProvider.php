<?php

namespace MediaWiki\Revision;

use ParserOutput;

/**
 * A lazy provider of ParserOutput objects for a revision's individual slots.
 *
 * @since 1.32
 */
interface SlotRenderingProvider {

	/**
	 * @param string $role
	 * @param array $hints Hints given as an associative array. Known keys:
	 *      - 'generate-html' => bool: Whether the caller is interested in output HTML (as opposed
	 *        to just meta-data). Default is to generate HTML.
	 *
	 * @throws SuppressedDataException if the content is not accessible for the audience
	 *         specified in the constructor.
	 * @return ParserOutput
	 */
	public function getSlotParserOutput( $role, array $hints = [] );

}
