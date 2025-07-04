<?php

namespace MediaWiki\Logging;

use MediaWiki\Message\Message;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleParser;

/**
 * LogFormatter for renameuser/renameuser logs
 */
class RenameuserLogFormatter extends LogFormatter {
	private TitleParser $titleParser;

	public function __construct(
		LogEntry $entry,
		TitleParser $titleParser
	) {
		parent::__construct( $entry );
		$this->titleParser = $titleParser;
	}

	/**
	 * @inheritDoc
	 */
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		/* Current format:
		 * 1,2,3: normal logformatter params
		 * 4: old username (linked)
		 *    (legaciest doesn't have this at all, all in comment)
		 *    (legacier uses this as new name and stores old name in target)
		 * 5: new username (linked)
		 * 6: number of edits the user had at the time
		 *    (not available except in newest log entries)
		 * 7: new username (raw format for GENDER)
		 * Note that the arrays are zero-indexed, while message parameters
		 * start from 1, so substract one to get array entries below.
		 */

		if ( !isset( $params[3] ) ) {
			// The oldest format
			return $params;
		} elseif ( !isset( $params[4] ) ) {
			// See comments above
			$params[4] = $params[3];
			$params[3] = $this->entry->getTarget()->getText();
		}

		if ( isset( $params[5] ) ) {
			// Make sure number of edits is formatted
			$params[5] = Message::numParam( $params[5] );
		}

		// Nice link to old user page
		$title = Title::makeTitleSafe( NS_USER, $params[3] );
		// @phan-suppress-next-line SecurityCheck-DoubleEscaped
		$link = $this->myPageLink( $title, $params[3],
			[ 'redirect' => 'no' ] );
		// @phan-suppress-next-line SecurityCheck-XSS
		$params[3] = Message::rawParam( $link );

		// Nice link to new user page
		$title = Title::makeTitleSafe( NS_USER, $params[4] );
		// @phan-suppress-next-line SecurityCheck-DoubleEscaped
		$link = $this->myPageLink( $title, $params[4] );
		// @phan-suppress-next-line SecurityCheck-XSS
		$params[4] = Message::rawParam( $link );
		// GENDER support (using new user page)
		$params[6] = $title->getText();

		return $params;
	}

	/**
	 * @param Title|null $title
	 * @param string $text
	 * @param array $query
	 * @return string wikitext or html
	 * @return-taint onlysafefor_html
	 */
	protected function myPageLink( ?Title $title, $text, $query = [] ) {
		if ( !$this->plaintext ) {
			if ( !$title instanceof Title ) {
				$link = htmlspecialchars( $text );
			} else {
				$link = $this->getLinkRenderer()->makeLink( $title, $text, [], $query );
			}
		} else {
			if ( !$title instanceof Title ) {
				$link = "[[User:$text]]";
			} else {
				$link = '[[' . $title->getPrefixedText() . ']]';
			}
		}

		return $link;
	}

	/** @inheritDoc */
	public function getMessageKey() {
		$key = parent::getMessageKey();
		$params = $this->extractParameters();

		// Very old log format, everything in comment
		if ( !isset( $params[3] ) ) {
			// Message: logentry-renameuser-renameuser-legaciest
			return "$key-legaciest";
		} elseif ( !isset( $params[5] ) ) {
			// Message: logentry-renameuser-renameuser-legacier
			return "$key-legacier";
		}

		// Message: logentry-renameuser-renameuser
		return $key;
	}

	/** @inheritDoc */
	public function getPreloadTitles() {
		$params = $this->extractParameters();
		if ( !isset( $params[3] ) ) {
			// Very old log format, everything in comment - legaciest
			return [];
		}
		if ( !isset( $params[4] ) ) {
			// Old log format - legacier
			$newUserName = $params[3];
		} else {
			$newUserName = $params[4];
		}

		$title = $this->titleParser->makeTitleValueSafe( NS_USER, $newUserName );
		if ( $title ) {
			return [ $title ];
		}

		return [];
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RenameuserLogFormatter::class, 'RenameuserLogFormatter' );
