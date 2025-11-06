<?php
/**
 * Formatter for new user log entries.
 *
 * @license GPL-2.0-or-later
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 * @since 1.22
 */

namespace MediaWiki\Logging;

use MediaWiki\Message\Message;

/**
 * This class formats patrol log entries.
 *
 * @since 1.19
 */
class PatrolLogFormatter extends LogFormatter {
	/** @inheritDoc */
	protected function getMessageKey() {
		$params = $this->getMessageParameters();
		if ( isset( $params[5] ) && $params[5] ) {
			$key = 'logentry-patrol-patrol-auto';
		} else {
			$key = 'logentry-patrol-patrol';
		}

		return $key;
	}

	/** @inheritDoc */
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();

		$target = $this->entry->getTarget();
		$oldid = $params[3];
		$revision = $this->context->getLanguage()->formatNumNoSeparators( $oldid );

		if ( $this->plaintext ) {
			$revlink = $revision;
		} elseif ( $target->exists() ) {
			$query = [
				'oldid' => $oldid,
				'diff' => 'prev'
			];
			$revlink = $this->getLinkRenderer()->makeLink( $target, $revision, [], $query );
		} else {
			$revlink = htmlspecialchars( $revision );
		}

		// @phan-suppress-next-line SecurityCheck-XSS Unlikely positive, only if language format is bad
		$params[3] = Message::rawParam( $revlink );

		return $params;
	}

	/** @inheritDoc */
	protected function getParametersForApi() {
		$entry = $this->entry;
		$params = $entry->getParameters();

		static $map = [
			'4:number:curid',
			'5:number:previd',
			'6:bool:auto',
			'4::curid' => '4:number:curid',
			'5::previd' => '5:number:previd',
			'6::auto' => '6:bool:auto',
		];
		foreach ( $map as $index => $key ) {
			if ( isset( $params[$index] ) ) {
				$params[$key] = $params[$index];
				unset( $params[$index] );
			}
		}

		return $params;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( PatrolLogFormatter::class, 'PatrolLogFormatter' );
