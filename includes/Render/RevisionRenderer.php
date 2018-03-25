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

use Language;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Storage\MutableRevisionSlots;
use MediaWiki\Storage\RevisionAccessException;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionSlots;
use ParserOptions;
use ParserOutput;
use Title;
use User;
use Wikimedia\ScopedCallback;

/**
 * Single-slot revision renderer.
 * FIXME document
 *
 * XXX: we'll probably need single-slot RevisionRenderers too. RevisionRenderer would become
 * an interface or a base class, then. We may also want personal vs public RevisionRenderers.
 *
 * @since 1.31
 */
class RevisionRenderer {

	/**
	 * @var Language
	 */
	private $contentLanguage;

	/**
	 * RevisionRenderer constructor.
	 *
	 * @param Language $contentLanguage
	 */
	public function __construct( Language $contentLanguage ) {
		$this->contentLanguage = $contentLanguage;
	}

	/**
	 * @param LinkTarget $title
	 *
	 * @return ParserOptions
	 */
	public function makeCanonicalParserOptions( LinkTarget $title ) {
		// FIXME: this breaks per-content-model ParserOptions, currently used
		// by a few extensions like Wikibase! See T190712.

		$user = new User();
		$options = ParserOptions::newCanonical( $user, $this->contentLanguage );

		$title = Title::newFromLinkTarget( $title );
		if ( $title->isConversionTable() ) {
			// TODO: ConversionTable should become a separate content model,
			// so this would not have to be controlled via ParserOptions.
			$options->disableContentConversion();
		}

		return $options;
	}

	/**
	 * @param LinkTarget $title
	 * @param User $user
	 *
	 * @param Language $lang
	 *
	 * @return ParserOptions
	 */
	public function makeUserParserOptions(
		LinkTarget $title,
		User $user,
		Language $lang = null
	) {
		$lang = $lang ?: $this->contentLanguage;

		// XXX: We need to keep a way to force the parser cache to be split on whatever
		// factor the rendering depends on, per content model.
		$options = ParserOptions::newFromUserAndLang( $user, $lang );

		$title = Title::newFromLinkTarget( $title );
		if ( $title->isConversionTable() ) {
			// TODO: ConversionTable should become a separate content model,
			// so this would not have to be controlled via ParserOptions.
			$options->disableContentConversion();
		}

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
		$options = $this->makeUserParserOptions( $title, $user );
		$options->setIsPreview( true );
		$options->setIsSectionPreview( !is_null( $section ) && $section !== '' );
		$options->enableLimitReport();

		return $options;
	}

	/**
	 * FIXME document (+PST!)
	 * XXX: do we need this?! Or should the caller just construct a fake revision?
	 *
	 * @param LinkTarget $title
	 * @param RevisionSlots $slots
	 * @param User $user
	 * @param string|null $section
	 * @param ParserOptions|null $options
	 * @param SlotRenderingProvider|null $slotRenderingProvider
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
		SlotRenderingProvider $slotRenderingProvider = null
	) {
		$options = $options ?: $this->makePreviewParserOptions( $title, $slots, $user, $section );

		$title = Title::newFromLinkTarget( $title );

		try {
			// XXX: this duplicates some logic from PageMetaDataUpdater!
			$pstSlots = new MutableRevisionSlots();
			foreach ( $slots->getSlotRoles() as $role ) {
				$content = $slots->getContent( $role );
				$pstContent = $content->preSaveTransform( $title, $user, $options );
				$pstSlots->setContent( $role, $pstContent );
			}

			$scopedCallback = $options->setupFakeRevision( $title, $pstContent, $user );
			$parserOutput = $this->renderRevisionSlots(
				$title,
				$pstSlots,
				$options,
				$slotRenderingProvider,
				null,
				true
			);
			ScopedCallback::consume( $scopedCallback );
		} catch ( RevisionAccessException $ex ) {
			$parserOutput = $this->handleRevisionAccessEAxception( $ex );
		}

		return $parserOutput;
	}

	/**
	 * FIXME document
	 * XXX: do we need a version without HTML? Do we need a flag?
	 *
	 * @param RevisionRecord $revision
	 * @param User $user
	 * @param ParserOptions|null $options
	 * @param SlotRenderingProvider|null $slotRenderingProvider
	 *
	 * @return Rendering
	 */
	public function renderRevisionForUser(
		RevisionRecord $revision,
		User $user,
		ParserOptions $options = null,
		SlotRenderingProvider $slotRenderingProvider = null
	) {
		$revId = $revision->getId();
		$slots = $revision->getSlots();
		$title = $revision->getPageAsLinkTarget();
		$options = $options ?: $this->makeUserParserOptions( $title, $user );

		return $this->renderRevisionSlots(
			$title,
			$slots,
			$options,
			$slotRenderingProvider,
			$revId,
			true
		);
	}

	/**
	 * FIXME document
	 * XXX: do we need a version without HTML? Do we need a flag?
	 *
	 * @param RevisionRecord $revision
	 * @param ParserOptions|null $options
	 * @param SlotRenderingProvider|null $slotRenderingProvider
	 *
	 * @return Rendering
	 */
	public function renderRevisionForPublic(
		RevisionRecord $revision,
		ParserOptions $options = null,
		SlotRenderingProvider $slotRenderingProvider = null
	) {
		$revId = $revision->getId();
		$slots = $revision->getSlots();
		$title = $revision->getPageAsLinkTarget();
		$options = $options ?: $this->makeCanonicalParserOptions( $title );

		return $this->renderRevisionSlots(
			$title,
			$slots,
			$options,
			$slotRenderingProvider,
			$revId,
			true
		);
	}

	/**
	 * FIXME document
	 * XXX: do we need a version without HTML? Do we need a flag?
	 *
	 * @param RevisionRecord $revision
	 * @param ParserOptions|null $options
	 * @param SlotRenderingProvider|null $slotRenderingProvider
	 *
	 * @return Rendering "blind" rendering with no HTML
	 */
	public function processRevisionForUpdate(
		RevisionRecord $revision,
		ParserOptions $options = null,
		SlotRenderingProvider $slotRenderingProvider = null
	) {
		$revId = $revision->getId();
		$slots = $revision->getSlots();
		$title = $revision->getPageAsLinkTarget();
		$options = $options ?: $this->makeCanonicalParserOptions( $title );

		return $this->renderRevisionSlots(
			$title,
			$slots,
			$options,
			$slotRenderingProvider,
			$revId,
			false
		);
	}

	/**
	 * @param LinkTarget $title
	 * @param RevisionSlots $slots
	 * @param ParserOptions|null $options
	 * @param SlotRenderingProvider|null $slotRenderingProvider
	 * @param int|null $revisionId
	 * @param bool $generateHtml
	 *
	 * @return Rendering
	 */
	public function renderRevisionSlots(
		LinkTarget $title,
		RevisionSlots $slots,
		ParserOptions $options,
		SlotRenderingProvider $slotRenderingProvider = null,
		$revisionId = null,
		$generateHtml = true
	) {
		$title = Title::newFromLinkTarget( $title );
		if ( !$slotRenderingProvider ) {
			// XXX: We may want t oahve a CompositeParserOutput that is a SlotRenderingProvider!
			// This would also allow the per-slot outputs to go into the ParserCache.
			// CompositeParserOutput::getText() would compose the effective HTML on the fly,
			// based on some kind of template provided here in this method.
			// Compare comment in PageMetaDataUpdater::resetParserOptions().
			$slotRenderingProvider = new LazySlotRenderingProvider(
				$title,
				$slots,
				$options,
				$revisionId
			);

			$slotRenderingProvider->setRevisionAccessExceptionHandler(
				function( RevisionAccessException $ex ) {
					return $this->handleRevisionAccessEAxception( $ex );
				}
			);
		}

		$output = new ParserOutput();

		if ( $generateHtml ) {
			$this->composeOutput( $slots, $slotRenderingProvider, $output );
		}

		// Always merge all tracking meta-data, see T190063
		$this->mergeTrackingMetaData( $slots, $slotRenderingProvider, $output );

		return $output;
	}

	private function composeOutput(
		RevisionSlots $slots,
		SlotRenderingProvider $slotRenderingProvider,
		ParserOutput $output
	) {
		// TODO: MCR: introduce logic for combining slot HTML [SlotHandler/PageType]
		// Per-slot logic could append or prepend, perhaps with a section, or in a box.
		// Per-page-type logic would have more control over the layout, and may
		// choose to only use per-slot logic for slots it doesn't know about.

		// TODO: figure out how to merge the TOC [SlotHandler/PageType]

		$mainRendering = $slotRenderingProvider->getRendering( 'main' );
		$output->setText( $mainRendering->getRawText() );
		$output->setTOCHTML( $mainRendering->getTOCHTML() );

		$output->addResourcesFrom( $mainRendering );
		$output->mergeSkinControlFrom( $mainRendering );
		$output->mergeCachingMetaDataFrom( $mainRendering );
	}

	private function mergeTrackingMetaData(
		RevisionSlots $slots,
		SlotRenderingProvider $slotRenderingProvider,
		ParserOutput $output
	) {
		foreach ( $slots->getSlotRoles() as $role ) {
			$slotRendering = $slotRenderingProvider->getRendering( $role, false );
			$output->addTrackingMetaDataFrom( $slotRendering );
		}
	}

	/**
	 * @param RevisionAccessException $ex
	 *
	 * @return Rendering
	 */
	private function handleRevisionAccessEAxception( RevisionAccessException $ex ) {
		// TODO: pretty output for SuppressedDataException!
		// TODO: better handling of internal exceptions!
		// XXX: Throw ErrorPageError?

		return new ParserOutput( wfEscapeWikiText( $ex->getMessage() ) );
	}

}