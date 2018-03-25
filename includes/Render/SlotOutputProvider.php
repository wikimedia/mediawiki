<?php

namespace MediaWiki\Render;

use ParserOutput;

/**
 * FIXME Document
 *
 * @since 1.31
 * @ingroup Page
 */
interface SlotOutputProvider {

	/**
	 * @return ParserOutput
	 */
	public function getSlotParserOutput( $role, $generateHtml = true );
}