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
 * Dummy implementation of SlotRenderingProvider that returns dummy
 * ParserOutput objects.
 *
 * This can be used as a stand-in in situations where a SlotRenderingProvider is needed,
 * but access to the revision content has been suppressed for the audience in question.
 *
 * @package MediaWiki\Render
 */
class DummySlotRenderingProvider implements SlotRenderingProvider {

	/**
	 * @var Rendering
	 */
	private $dummy;

	/**
	 * DummySlotRenderingProvider constructor.
	 *
	 * @param Rendering|null $dummy
	 */
	public function __construct( Rendering $dummy = null ) {
		if ( !$dummy ) {
			$dummy = new ParserOutput();
		}

		$this->dummy = $dummy;
	}

	/**
	 * @return Rendering
	 */
	public function getRendering( $role, $generateHtml = true ) {
		return $this->dummy;
	}
}