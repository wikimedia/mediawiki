<?php
/**
 * Formatter for merge log entries.
 *
 * @license GPL-2.0-or-later
 * @file
 * @license GPL-2.0-or-later
 * @since 1.25
 */

namespace MediaWiki\Logging;

use MediaWiki\Message\Message;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleParser;

/**
 * This class formats merge log entries.
 *
 * @since 1.25
 */
class MergeLogFormatter extends LogFormatter {
	private TitleParser $titleParser;

	public function __construct(
		LogEntry $entry,
		TitleParser $titleParser
	) {
		parent::__construct( $entry );
		$this->titleParser = $titleParser;
	}

	/** @inheritDoc */
	public function getPreloadTitles() {
		$params = $this->extractParameters();

		try {
			return [ $this->titleParser->parseTitle( $params[3] ) ];
		} catch ( MalformedTitleException ) {
		}
		return [];
	}

	/** @inheritDoc */
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		$isMergeInto = $this->entry->getSubtype() === 'merge-into';

		$srcTitle = $isMergeInto
			? Title::newFromText( $params[3] )
			: $this->entry->getTarget();

		$destTitle = $isMergeInto
			? $this->entry->getTarget()
			: Title::newFromText( $params[3] );

		$srcLink = Message::rawParam(
			$this->makePageLink( $srcTitle, [ 'redirect' => 'no' ] )
		);
		$destLink = Message::rawParam( $this->makePageLink( $destTitle ) );

		if ( $isMergeInto ) {
			$params[2] = $destLink;
			$params[3] = $srcLink;
		} else {
			$params[2] = $srcLink;
			$params[3] = $destLink;
		}

		$params[4] = $this->context->getLanguage()
			->userTimeAndDate( $params[4], $this->context->getUser() );

		if ( isset( $params[6] ) ) {
			$params[6] = $this->context->getLanguage()
				->userTimeAndDate( $params[6], $this->context->getUser() );
		}
		return $params;
	}

	/** @inheritDoc */
	public function getActionLinks() {
		if ( $this->entry->isDeleted( LogPage::DELETED_ACTION ) // Action is hidden
			|| !$this->context->getAuthority()->isAllowed( 'mergehistory' )
		) {
			return '';
		}

		// Show unmerge link
		$params = $this->extractParameters();

		if ( $this->entry->getSubtype() === 'merge-into' ) {
			// merge-into entry lives at the destination page
			$target = $this->entry->getTarget()->getPrefixedDBkey(); // dest
			$dest   = $params[3];                                   // src
		} else {
			// regular merge entry lives at the source page
			$target = $params[3];                                   // dest
			$dest   = $this->entry->getTarget()->getPrefixedDBkey(); // src
		}

		if ( isset( $params[5] ) ) {
			$mergePoint = $params[4] . "|" . $params[5];
		} else {
			// This is an old log entry from before we recorded the revid separately
			$mergePoint = $params[4];
		}
		if ( isset( $params[6] ) ) {
			$start = $params[6] . "|" . $params[7];
		} else {
			$start = "";
		}
		$revert = $this->getLinkRenderer()->makeKnownLink(
			SpecialPage::getTitleFor( 'MergeHistory' ),
			$this->msg( 'revertmerge' )->text(),
			[],
			[
				'target' => $target,
				'dest' => $dest,
				'mergepoint' => $mergePoint,
				'mergepointold' => $start,
				'submitted' => 1 // show the revisions immediately
			]
		);

		return $this->msg( 'parentheses' )->rawParams( $revert )->escaped();
	}

	/** @inheritDoc */
	protected function getParametersForApi() {
		$entry = $this->entry;
		$params = $entry->getParameters();

		// Use a different label when the subtype is "merge-into"
		static $mapMerge = [
			'4:title:dest',
			'5:timestamp:mergepoint',
			'4::dest'				=> '4:title:dest',
			'5::mergepoint'  => '5:timestamp:mergepoint',
			'6::mergerevid',
			'7::mergestart'  => '7:timestamp:mergestart',
			'8::mergestartid'
		];
		static $mapMergeInto = [
			'4:title:src',
			'5:timestamp:mergepoint',
			'4::src'				 => '4:title:src',
			'5::mergepoint'  => '5:timestamp:mergepoint',
			'6::mergerevid',
			'7::mergestart'  => '7:timestamp:mergestartid',
			'8::mergestartid'
		];

		$map = $entry->getSubtype() === 'merge-into' ? $mapMergeInto : $mapMerge;

		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		return $params;
	}

	/** @inheritDoc */
	protected function getMessageKey() {
		if ( isset( $this->extractParameters()[6] ) ) {
			return parent::getMessageKey() . "-partial";
		} else {
			return parent::getMessageKey();
		}
	}
}

/** @deprecated class alias since 1.44 */
class_alias( MergeLogFormatter::class, 'MergeLogFormatter' );
