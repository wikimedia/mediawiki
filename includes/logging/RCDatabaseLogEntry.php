<?php
/**
 * Contains a class for dealing with recent changes database log entries
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Niklas Laxström
 * @license GPL-2.0-or-later
 * @since 1.19
 */

namespace MediaWiki\Logging;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * A subclass of DatabaseLogEntry for objects constructed from entries in the
 * recentchanges table (rather than the logging table).
 *
 * This class should only be used in context of the LogFormatter class.
 */
class RCDatabaseLogEntry extends DatabaseLogEntry {

	/** @inheritDoc */
	public static function newFromId( $id, IReadableDatabase $db ) {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		// Make the LSP violation explicit to prevent sneaky failures
		throw new LogicException( 'Not implemented!' );
	}

	/** @inheritDoc */
	public static function getSelectQueryData() {
		// @phan-suppress-previous-line PhanPluginNeverReturnMethod
		// Make the LSP violation explicit to prevent sneaky failures
		throw new LogicException( 'Not implemented!' );
	}

	/** @inheritDoc */
	public function getId() {
		return $this->row->rc_logid;
	}

	/** @inheritDoc */
	protected function getRawParameters() {
		return $this->row->rc_params;
	}

	/** @inheritDoc */
	public function getAssociatedRevId() {
		return $this->row->rc_this_oldid;
	}

	/** @inheritDoc */
	public function getType() {
		return $this->row->rc_log_type;
	}

	/** @inheritDoc */
	public function getSubtype() {
		return $this->row->rc_log_action;
	}

	/** @inheritDoc */
	public function getPerformerIdentity(): UserIdentity {
		if ( !$this->performer ) {
			$actorStore = MediaWikiServices::getInstance()->getActorStore();
			$userFactory = MediaWikiServices::getInstance()->getUserFactory();
			if ( isset( $this->row->rc_actor ) ) {
				try {
					$this->performer = $actorStore->newActorFromRowFields(
						$this->row->rc_user ?? 0,
						$this->row->rc_user_text,
						$this->row->rc_actor
					);
				} catch ( InvalidArgumentException $e ) {
					$this->performer = $actorStore->getUnknownActor();
					LoggerFactory::getInstance( 'logentry' )->warning(
						'Failed to instantiate RC log entry performer', [
							'exception' => $e,
							'log_id' => $this->getId()
						]
					);
				}
			} elseif ( isset( $this->row->rc_user ) ) {
				$this->performer = $userFactory->newFromId( $this->row->rc_user )->getUser();
			} elseif ( isset( $this->row->rc_user_text ) ) {
				$user = $userFactory->newFromName( $this->row->rc_user_text );
				if ( $user ) {
					$this->performer = $user->getUser();
				} else {
					$this->performer = $actorStore->getUnknownActor();
					LoggerFactory::getInstance( 'logentry' )->warning(
						'Failed to instantiate RC log entry performer', [
							'rc_user_text' => $this->row->rc_user_text,
							'log_id' => $this->getId()
						]
					);
				}
			}
		}
		return $this->performer;
	}

	/** @inheritDoc */
	public function getTarget() {
		$namespace = $this->row->rc_namespace;
		$page = $this->row->rc_title;
		return Title::makeTitle( $namespace, $page );
	}

	/** @inheritDoc */
	public function getTimestamp() {
		return wfTimestamp( TS_MW, $this->row->rc_timestamp );
	}

	/** @inheritDoc */
	public function getComment() {
		$services = MediaWikiServices::getInstance();

		return $services->getCommentStore()
			// Legacy because the row may have used RecentChange::selectFields()
			->getCommentLegacy(
				$services->getConnectionProvider()->getReplicaDatabase(),
				'rc_comment',
				$this->row
			)->text;
	}

	/** @inheritDoc */
	public function getDeleted() {
		return $this->row->rc_deleted;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( RCDatabaseLogEntry::class, 'RCDatabaseLogEntry' );
