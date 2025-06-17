<?php
/**
 * Copyright © 2010 Derk-Jan Hartman <hartman@videolan.org>
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
 */

namespace MediaWiki\Specials;

use DifferenceEngine;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;

/**
 * Implements Special:ComparePages
 *
 * @ingroup SpecialPage
 */
class SpecialComparePages extends SpecialPage {

	private RevisionLookup $revisionLookup;
	private IContentHandlerFactory $contentHandlerFactory;

	/** @var DifferenceEngine */
	private $differenceEngine;

	public function __construct(
		RevisionLookup $revisionLookup,
		IContentHandlerFactory $contentHandlerFactory
	) {
		parent::__construct( 'ComparePages' );
		$this->revisionLookup = $revisionLookup;
		$this->contentHandlerFactory = $contentHandlerFactory;
	}

	/**
	 * Show a form for filtering namespace and username
	 *
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->addModuleStyles( 'mediawiki.special' );
		$this->addHelpLink( 'Help:Diff' );

		$form = HTMLForm::factory( 'ooui', [
			'Page1' => [
				'type' => 'title',
				'exists' => true,
				'name' => 'page1',
				'label-message' => 'compare-page1',
				'size' => '40',
				'section' => 'page1',
				'required' => false,
			],
			'Revision1' => [
				'type' => 'int',
				'name' => 'rev1',
				'label-message' => 'compare-rev1',
				'size' => '8',
				'section' => 'page1',
				'validation-callback' => $this->checkExistingRevision( ... ),
			],
			'Page2' => [
				'type' => 'title',
				'name' => 'page2',
				'exists' => true,
				'label-message' => 'compare-page2',
				'size' => '40',
				'section' => 'page2',
				'required' => false,
			],
			'Revision2' => [
				'type' => 'int',
				'name' => 'rev2',
				'label-message' => 'compare-rev2',
				'size' => '8',
				'section' => 'page2',
				'validation-callback' => $this->checkExistingRevision( ... ),
			],
			'Action' => [
				'type' => 'hidden',
				'name' => 'action',
			],
			'Unhide' => [
				'type' => 'hidden',
				'name' => 'unhide',
			],
		], $this->getContext(), 'compare' );

		$form->setMethod( 'get' )
			->setSubmitTextMsg( 'compare-submit' )
			->setSubmitCallback( $this->showDiff( ... ) )
			->show();

		if ( $this->differenceEngine ) {
			$this->differenceEngine->showDiffPage( true );
		}
	}

	/**
	 * @param array $data
	 * @param HTMLForm $form
	 */
	private function showDiff( $data, HTMLForm $form ) {
		$rev1 = $this->revOrTitle( $data['Revision1'], $data['Page1'] );
		$rev2 = $this->revOrTitle( $data['Revision2'], $data['Page2'] );

		if ( $rev1 && $rev2 ) {
			// Revision IDs either passed the existence check or were fetched from existing titles.
			$revisionRecord = $this->revisionLookup->getRevisionById( $rev1 );
			$contentModel = $revisionRecord->getSlot(
				SlotRecord::MAIN,
				RevisionRecord::RAW
			)->getModel();
			$contentHandler = $this->contentHandlerFactory->getContentHandler( $contentModel );
			$this->differenceEngine = $contentHandler->createDifferenceEngine( $form->getContext(),
				$rev1,
				$rev2,
				0, // rcid
				( $data['Action'] == 'purge' ),
				( $data['Unhide'] == '1' )
			);
		}
	}

	private function revOrTitle( ?string $revision, ?string $title ): ?int {
		if ( $revision ) {
			return (int)$revision;
		} elseif ( $title ) {
			return Title::newFromText( $title )->getLatestRevID();
		}

		return null;
	}

	/**
	 * @param string|null $value
	 * @param array $alldata
	 * @return string|bool
	 */
	private function checkExistingRevision( $value, $alldata ) {
		if ( $value === '' || $value === null ) {
			return true;
		}
		$revisionRecord = $this->revisionLookup->getRevisionById( (int)$value );
		if ( $revisionRecord === null ) {
			return $this->msg( 'compare-revision-not-exists' )->parseAsBlock();
		}

		return true;
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialComparePages::class, 'SpecialComparePages' );
