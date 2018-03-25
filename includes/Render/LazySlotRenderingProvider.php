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
	 * @var object[] anonymous objects with two fields, using slot roles as keys:
	 *  - hasHtml: whether the output contains HTML
	 *  - ParserOutput: the slot's parser output
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
	public function getSlotRendering( $role, $generateHtml = true ) {
		if ( isset( $this->slotsOutput[$role] ) ) {
			$entry = $this->slotsOutput[$role];

			if ( $entry->hasHtml || !$generateHtml ) {
				return $entry->output;
			}
		}

		$content = $this->slots->getContent( $role );

		$output = $content->getParserOutput(
			$this->title,
			$this->revisionId,
			$this->options,
			$generateHtml
		);

		// FIXME: put hasHtml() into Rendering, so we don't have to track hasHtml here!
		$this->slotsOutput[$role] = (object)[
			'output' => $output,
			'hasHtml' => $generateHtml,
		];

		$output->setCacheTime( wfTimestampNow() );

		return $output;
	}
}