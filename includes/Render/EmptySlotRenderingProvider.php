<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 27.03.18
 * Time: 18:58
 */

namespace MediaWiki\Render;

use ParserOutput;

/**
 * Dummy implementation of SlotRenderingProvider that returns empty
 * ParserOutput objects.
 *
 * This can be used as a stand-in in situations where a SlotRenderingProvider is needed,
 * but access to the revision content has been suppressed for the audience in question.
 *
 * @package MediaWiki\Render
 */
class EmptySlotRenderingProvider implements SlotRenderingProvider {

	/**
	 * @return Rendering
	 */
	public function getRendering( $role, $generateHtml = true ) {
		return new ParserOutput();
	}
}