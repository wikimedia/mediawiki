<?php
/**
 * Created by PhpStorm.
 * User: daki
 * Date: 27.03.18
 * Time: 18:58
 */

namespace MediaWiki\Render;

use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionSlots;
use ParserOptions;
use ParserOutput;
use Title;
use User;

class LazySlotRenderingProvider implements SlotRenderingProvider {

	/**
	 * @var Title
	 */
	private $title;

	/**
	 * @var RevisionSlots
	 */
	private $slots;

	/**
	 * @var ParserOptions
	 */
	private $options;

	/**
	 * @var int
	 */
	private $revisionId;

	/**
	 * @var Rendering[]
	 */
	private $slotsOutput = [];

	/**
	 * @param RevisionSlots $slots
	 * @param ParserOptions $options
	 * @param int $revisionId
	 * @param Title $title
	 */
	public function __construct(
		Title $title,
		RevisionSlots $slots,
		ParserOptions $options,
		$revisionId = 0
	) {
		$this->title = $title;
		$this->slots = $slots;
		$this->options = $options;
		$this->revisionId = $revisionId;
	}

	/**
	 * @return Rendering
	 */
	public function getRendering( $role, $generateHtml = true ) {
		if ( isset( $this->slotsOutput[$role] ) ) {
			$output = $this->slotsOutput[$role];

			if ( $output->hasText() || !$generateHtml ) {
				return $output;
			}
		}

		$content = $this->slots->getContent( $role );

		$output = $content->getParserOutput(
			$this->title,
			$this->revisionId,
			$this->options,
			$generateHtml
		);

		$this->slotsOutput[$role] = $output;

		$output->setCacheTime( wfTimestampNow() );

		return $output;
	}

	/**
	 * Returns the Rendering for the slot indicated by $role, or null if no such Rendering
	 * was yet created.
	 *
	 * @param string $role
	 * @return Rendering|null
	 */
	public function peekRendering( $role ) {
		if ( isset( $this->slotsOutput[$role] ) ) {
			return $this->slotsOutput[$role];
		}

		return null;
	}

	/**
	 * Sets the rendering for the slot indicated by $role.
	 * May be used to avoid re-rendering when a Rendering is already known.
	 *
	 * @param string $role
	 * @param Rendering $rendering
	 */
	public function putRendering( $role, Rendering $rendering ) {
		$this->slotsOutput[$role] = $rendering;
	}

}