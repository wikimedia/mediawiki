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
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\User;

/**
 * This class formats new user log entries.
 *
 * @since 1.19
 */
class NewUsersLogFormatter extends LogFormatter {
	private NamespaceInfo $namespaceInfo;

	public function __construct(
		LogEntry $entry,
		NamespaceInfo $namespaceInfo
	) {
		parent::__construct( $entry );
		$this->namespaceInfo = $namespaceInfo;
	}

	/** @inheritDoc */
	protected function getMessageParameters() {
		$params = parent::getMessageParameters();
		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'create2' || $subtype === 'byemail' ) {
			if ( isset( $params[3] ) ) {
				$target = User::newFromId( $params[3] );
			} else {
				$target = User::newFromName( $this->entry->getTarget()->getText(), false );
			}
			$params[2] = Message::rawParam( $this->makeUserLink( $target ) );
			$params[3] = $target->getName();
		}

		return $params;
	}

	/** @inheritDoc */
	public function getComment() {
		$timestamp = wfTimestamp( TS_MW, $this->entry->getTimestamp() );
		if ( $timestamp < '20080129000000' ) {
			# Suppress $comment from old entries (before 2008-01-29),
			# not needed and can contain incorrect links
			return '';
		}

		return parent::getComment();
	}

	/** @inheritDoc */
	public function getPreloadTitles() {
		$subtype = $this->entry->getSubtype();
		if ( $subtype === 'create2' || $subtype === 'byemail' ) {
			// add the user talk to LinkBatch for the userLink
			return [ $this->namespaceInfo->getTalkPage( $this->entry->getTarget() ) ];
		}

		return [];
	}
}

/** @deprecated class alias since 1.44 */
class_alias( NewUsersLogFormatter::class, 'NewUsersLogFormatter' );
