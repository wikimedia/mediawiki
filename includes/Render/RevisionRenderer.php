<?php
/**
 * Service for looking up page revisions.
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
 * Attribution notice: when this file was created, much of its content was taken
 * from the Revision.php file as present in release 1.30. Refer to the history
 * of that file for original authorship.
 *
 * @file
 */

namespace MediaWiki\Render;

use ActorMigration;
use CommentStore;
use CommentStoreComment;
use Content;
use ContentHandler;
use DBAccessObjectUtils;
use Hooks;
use IDBAccessObject;
use InvalidArgumentException;
use IP;
use LogicException;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Render\SlotOutputProvider;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\Storage\SlotRecord;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use Message;
use MWException;
use MWUnknownContentModelException;
use ParserOptions;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RecentChange;
use Revision;
use stdClass;
use Title;
use User;
use WANObjectCache;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\DBConnRef;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Single-slot revision renderer.
 * FIXME document
 * FIXME: extract interface; implementation should be called SingleSlotRevisionRenderer, etc
 *
 * @since 1.31
 */
class RevisionRenderer {

	private $slot;

	/**
	 * RevisionRenderer constructor.
	 *
	 * @param string $slot
	 */
	public function __construct( $slot ) {
		Assert::parameterType( 'string', $slot, '$slot' );
		$this->slot = $slot;
	}

	/**
	 * @return ParserOptions
	 */
	public function getCanonicalParserOptions( LinkTarget $title, RevisionSlots $slots ) {
		// FIXME
	}

	/**
	 * @return ParserOptions
	 */
	public function getUserParserOptions( LinkTarget $title, RevisionSlots $slots, User $user ) {
		// FIXME
	}

	/**
	 * FIXME document
	 * XXX: do we need this?! Or should the caller just construct a fake revision?
	 *
	 * @param LinkTarget $title
	 * @param RevisionSlots $slots
	 * @param User $user
	 * @param ParserOptions|null $options
	 * @param SlotOutputProvider|null $slotOutput
	 * @param int|null $revId
	 *
	 * @return Rendering
	 */
	public function renderPreviewForUser(
		LinkTarget $title,
		RevisionSlots $slots,
		User $user,
		ParserOptions $options = null,
		SlotOutputProvider $slotOutput = null,
		$revId = null
	) {
		$options = $options ?: $this->getUserParserOptions( $title, $slots, $user );

		return $this->composeRendering( $title, $slots, $options, $slotOutput, $revId, true );
	}

	/**
	 * FIXME document
	 * XXX: do we need a version without HTML? Do we need a flag?
	 *
	 * @param RevisionRecord $revision
	 * @param User $user
	 * @param ParserOptions|null $options
	 * @param SlotOutputProvider|null $slotOutput
	 *
	 * @return Rendering
	 */
	public function renderRevisionForUser(
		RevisionRecord $revision,
		User $user,
		ParserOptions $options = null,
		SlotOutputProvider $slotOutput = null
	) {
		// FIXME: audience check!

		$revId = $revision->getId();
		$slots = $revision->getSlots();
		$title = $revision->getPageAsLinkTarget();
		$options = $options ?: $this->getUserParserOptions( $title, $slots, $user );

		return $this->composeRendering( $title, $slots, $options, $slotOutput, $revId, true );
	}

	/**
	 * FIXME document
	 * XXX: do we need a version without HTML? Do we need a flag?
	 *
	 * @param RevisionRecord $revision
	 * @param ParserOptions|null $options
	 * @param SlotOutputProvider|null $slotOutput
	 *
	 * @return Rendering
	 */
	public function renderRevisionForPublic(
		RevisionRecord $revision,
		ParserOptions $options = null,
		SlotOutputProvider $slotOutput = null
	) {
		// FIXME: audience check!

		$revId = $revision->getId();
		$slots = $revision->getSlots();
		$title = $revision->getPageAsLinkTarget();
		$options = $options ?: $this->getCanonicalParserOptions( $title, $slots );

		return $this->composeRendering( $title, $slots, $options, $slotOutput, $revId, true );
	}

	/**
	 * FIXME document
	 * XXX: do we need a version without HTML? Do we need a flag?
	 *
	 * @param RevisionRecord $revision
	 * @param ParserOptions|null $options
	 * @param SlotOutputProvider|null $slotOutput
	 *
	 * @return Rendering "blind" rendering with no HTML
	 */
	public function processRevisionForUpdate(
		RevisionRecord $revision,
		ParserOptions $options = null,
		SlotOutputProvider $slotOutput = null
	) {
		// FIXME: audience check!

		$revId = $revision->getId();
		$slots = $revision->getSlots();
		$title = $revision->getPageAsLinkTarget();
		$options = $options ?: $this->getCanonicalParserOptions( $title, $slots );

		return $this->composeRendering( $title, $slots, $options, $slotOutput, $revId, false );
	}

	/**
	 * @param LinkTarget $title
	 * @param RevisionSlots $slots
	 * @param ParserOptions|null $options
	 * @param SlotOutputProvider|null $slotOutput
	 * @param int|null $revisionId
	 * @param bool $generateHtml
	 *
	 * @return Rendering
	 */
	private function composeRendering(
		LinkTarget $title,
		RevisionSlots $slots,
		ParserOptions $options,
		SlotOutputProvider $slotOutput,
		$revisionId,
		$generateHtml
	) {
		$title = Title::newFromLinkTarget( $title );

		// TODO: MCR: logic for combining the output of multiple slot goes here!
		// Either a separate implementation for multiple slots, or some kind of
		// hook system.
		$mainSlot = $slots->getSlot( $this->slot );

		return $this->getSlotParserOutput(
			$title,
			$mainSlot,
			$options,
			$revisionId,
			$generateHtml
		);
	}

	/**
	 * @param Title $title
	 * @param SlotRecord $slot
	 * @param ParserOptions $options
	 * @param int|null $revisionId
	 * @param bool $generateHtml
	 *
	 * @return Rendering
	 */
	private function getSlotParserOutput(
		Title $title,
		SlotRecord $slot,
		ParserOptions $options,
		$revisionId,
		$generateHtml
	) {
		$content = $slot->getContent();
		$output = $content->getParserOutput(
			$title,
			$revisionId,
			$options,
			$generateHtml
		);

		return $output;
	}

}