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

use ContentHandler;
use Language;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\Storage\SlotRecord;
use ParserOptions;
use Title;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\ScopedCallback;

/**
 * Single-slot revision renderer.
 * FIXME document
 * FIXME: extract interface; implementation should be called SingleSlotRevisionRenderer, etc
 *
 * @since 1.31
 */
class RevisionRenderer {

	/**
	 * @var string
	 */
	private $slot;

	/**
	 * @var Language
	 */
	private $contentLanguage;

	/**
	 * RevisionRenderer constructor.
	 *
	 * @param string $slot
	 * @param Language $contentLanguage
	 */
	public function __construct( $slot, Language $contentLanguage ) {
		Assert::parameterType( 'string', $slot, '$slot' );

		$this->slot = $slot;
		$this->contentLanguage = $contentLanguage;
	}

	/**
	 * @param LinkTarget $title
	 * @param RevisionSlots|null $slots
	 * @param string $context
	 *
	 * @return ParserOptions
	 */
	public function makeParserOptions(
		LinkTarget $title,
		RevisionSlots $slots = null,
		$context = 'canonical'
	) {
		$title = Title::newFromLinkTarget( $title );

		// TODO: Kill ContentHandler::makeParserOptions.
		// But we need to keep a way to force the parser cache to be split on whatever
		// factor the rendering depends on.
		// XXX: we may want to passing User and Language explicitly
		// XXX: The idea of a "canonical" ParserOutput is broken, as per the above.

		$options = $this->getContentHandler( $title, $slots )->makeParserOptions( $context );

		if ( $title->isConversionTable() ) {
			// @todo ConversionTable should become a separate content model, so
			// we don't need special cases like this one.
			$options->disableContentConversion();
		}

		return $options;
	}

	/**
	 * @param LinkTarget $title
	 * @param RevisionSlots|null $slots
	 * @param User $user
	 *
	 * @param Language $lang
	 *
	 * @return ParserOptions
	 */
	public function makeUserParserOptions(
		LinkTarget $title,
		RevisionSlots $slots = null,
		User $user,
		Language $lang
	) {
		$lang = $lang ?: $this->contentLanguage;

		// XXX: We need to keep a way to force the parser cache to be split on whatever
		// factor the rendering depends on, per content model.
		$options = ParserOptions::newFromUserAndLang( $user, $lang );

		return $options;
	}

	/**
	 * @param LinkTarget $title
	 * @param RevisionSlots|null $slots
	 * @param User $user
	 * @param string|null $section
	 *
	 * @return ParserOptions
	 */
	public function makePreviewParserOptions(
		LinkTarget $title,
		RevisionSlots $slots = null,
		User $user,
		$section = null
	) {
		$options = $this->makeUserParserOptions( $title, $slots, $user );
		$options->setIsPreview( true );
		$options->setIsSectionPreview( !is_null( $section ) && $section !== '' );
		$options->enableLimitReport();

		return $options;
	}


	/**
	 * @param RevisionSlots|null $slots
	 *
	 * @return ContentHandler
	 */
	private function getContentHandler( LinkTarget $title, RevisionSlots $slots = null ) {
		// FIXME: if $slots is null, use per-slot default model in this namespace!

		$model = $slots->getSlot( $this->slot )->getModel();
		return ContentHandler::getForModelID( $model );
	}

	/**
	 * FIXME document
	 * XXX: do we need this?! Or should the caller just construct a fake revision?
	 *
	 * @param LinkTarget $title
	 * @param RevisionSlots $slots
	 * @param User $user
	 * @param string|null $section
	 * @param ParserOptions|null $options
	 * @param SlotOutputProvider|null $slotOutputProvider
	 * @param int|null $revId
	 *
	 * @return Rendering
	 */
	public function renderPreviewForUser(
		LinkTarget $title,
		RevisionSlots $slots,
		User $user,
		$section = null,
		ParserOptions $options = null,
		SlotOutputProvider $slotOutputProvider = null
	) {
		$options = $options ?: $this->makePreviewParserOptions( $title, $slots, $user, $section );

		$title = Title::newFromLinkTarget( $title );

		// TODO: MCR: all slots!
		// FIXME: audience check!
		// XXX: this duplicates some logic from PageMetaDataUpdater! Move here??
		$content = $slots->getContent( $this->slot );
		$pstContent = $content->preSaveTransform( $title, $user, $options );

		$pstSlots = new RevisionSlots(
			[ $this->slot => SlotRecord::newUnsaved( $this->slot, $pstContent ) ]
		);

		$scopedCallback = $options->setupFakeRevision( $title, $pstContent, $user );
		$parserOutput = $this->renderRevisionSlots(
			$title,
			$pstSlots,
			$options,
			$slotOutput,
			null,
			true
		);
		ScopedCallback::consume( $scopedCallback );

		return $parserOutput;
	}

	/**
	 * FIXME document
	 * XXX: do we need a version without HTML? Do we need a flag?
	 *
	 * @param RevisionRecord $revision
	 * @param User $user
	 * @param ParserOptions|null $options
	 * @param SlotOutputProvider|null $slotOutputProvider
	 *
	 * @return Rendering
	 */
	public function renderRevisionForUser(
		RevisionRecord $revision,
		User $user,
		ParserOptions $options = null,
		SlotOutputProvider $slotOutputProvider = null
	) {
		// FIXME: audience check!

		$revId = $revision->getId();
		$slots = $revision->getSlots();
		$title = $revision->getPageAsLinkTarget();
		$options = $options ?: $this->makeUserParserOptions( $title, $slots, $user );

		return $this->renderRevisionSlots( $title, $slots, $options, $slotOutput, $revId, true );
	}

	/**
	 * FIXME document
	 * XXX: do we need a version without HTML? Do we need a flag?
	 *
	 * @param RevisionRecord $revision
	 * @param ParserOptions|null $options
	 * @param SlotOutputProvider|null $slotOutputProvider
	 *
	 * @return Rendering
	 */
	public function renderRevisionForPublic(
		RevisionRecord $revision,
		ParserOptions $options = null,
		SlotOutputProvider $slotOutputProvider = null
	) {
		// FIXME: audience check!

		$revId = $revision->getId();
		$slots = $revision->getSlots();
		$title = $revision->getPageAsLinkTarget();
		$options = $options ?: $this->makeParserOptions( $title, $slots );

		return $this->renderRevisionSlots( $title, $slots, $options, $slotOutput, $revId, true );
	}

	/**
	 * FIXME document
	 * XXX: do we need a version without HTML? Do we need a flag?
	 *
	 * @param RevisionRecord $revision
	 * @param ParserOptions|null $options
	 * @param SlotOutputProvider|null $slotOutputProvider
	 *
	 * @return Rendering "blind" rendering with no HTML
	 */
	public function processRevisionForUpdate(
		RevisionRecord $revision,
		ParserOptions $options = null,
		SlotOutputProvider $slotOutputProvider = null
	) {
		// FIXME: audience check!

		$revId = $revision->getId();
		$slots = $revision->getSlots();
		$title = $revision->getPageAsLinkTarget();
		$options = $options ?: $this->makeParserOptions( $title, $slots );

		return $this->renderRevisionSlots( $title, $slots, $options, $slotOutput, $revId, false );
	}

	/**
	 * @param LinkTarget $title
	 * @param RevisionSlots $slots
	 * @param ParserOptions|null $options
	 * @param SlotOutputProvider|null $slotOutputProvider
	 * @param int|null $revisionId
	 * @param bool $generateHtml
	 *
	 * @return Rendering
	 */
	public function renderRevisionSlots(
		LinkTarget $title,
		RevisionSlots $slots,
		ParserOptions $options,
		SlotOutputProvider $slotOutputProvider = null,
		$revisionId = null,
		$generateHtml = true
	) {
		if ( $slotOutputProvider ) {
			return $slotOutputProvider->getSlotParserOutput( $this->slot, $generateHtml );
		}

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