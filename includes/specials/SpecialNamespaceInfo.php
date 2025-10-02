<?php

/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\NamespaceInfo;

/**
 * Show information about the different namespaces
 *
 * @since 1.43
 * @author DannyS712
 * @ingroup SpecialPage
 */
class SpecialNamespaceInfo extends SpecialPage {

	private NamespaceInfo $namespaceInfo;

	public function __construct( NamespaceInfo $namespaceInfo ) {
		parent::__construct( 'NamespaceInfo' );

		$this->namespaceInfo = $namespaceInfo;
	}

	/**
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();

		$idHeading = Html::element(
			'th',
			[],
			$this->msg( 'namespaceinfo-heading-id' )->text()
		);
		$canonicalHeading = Html::element(
			'th',
			[],
			$this->msg( 'namespaceinfo-heading-canonical' )->text()
		);
		$localHeading = Html::element(
			'th',
			[],
			$this->msg( 'namespaceinfo-heading-local' )->text()
		);
		$infoHeading = Html::element(
			'th',
			[],
			$this->msg( 'namespaceinfo-heading-info' )->text()
		);
		$tableHeadingRow = Html::rawElement(
			'tr',
			[],
			$idHeading . $canonicalHeading . $localHeading . $infoHeading
		);

		$tableBodyRows = '';
		foreach ( $this->getContentLanguage()->getFormattedNamespaces() as $ns => $localName ) {
			$tableBodyRows .= $this->makeNamespaceRow( $ns, $localName );
		}

		$table = Html::rawElement(
			'table',
			[ 'class' => 'wikitable' ],
			$tableHeadingRow . $tableBodyRows
		);

		$out->addHtml( $table );
	}

	/**
	 * Get the HTML for a row for a specific namespace
	 *
	 * @param int $ns
	 * @param string $localName
	 * @return string
	 */
	private function makeNamespaceRow( int $ns, string $localName ): string {
		$canonicalName = $this->namespaceInfo->getCanonicalName( $ns );
		if ( $canonicalName ) {
			$canonicalName = strtr( $canonicalName, '_', ' ' );
		} else {
			$canonicalName = '';
		}

		// Special handling for main namespace
		if ( $ns === NS_MAIN ) {
			$localName = $this->msg( 'blanknamespace' )->escaped();
			$canonicalName = $this->msg( 'blanknamespace' )->inLanguage( 'en' )->escaped();
		}

		$description = $this->msg( 'namespaceinfo-description-ns' . $ns );
		if ( $description->isDisabled() ) {
			// Custom namespace with no message

			if ( $this->namespaceInfo->isTalk( $ns ) ) {
				$subjectNs = $this->namespaceInfo->getSubject( $ns );
				$subjectName = strtr(
					$this->namespaceInfo->getCanonicalName( $subjectNs ),
					'_',
					' '
				);
				$description = $this->msg( 'namespaceinfo-description-custom-talk' )
					->params(
						$subjectName,
						$subjectNs
					);
			} else {
				$description = $this->msg( 'namespaceinfo-description-custom' )
					->params( $localName );
			}
		}
		$descriptionText = $description->parse();

		$properties = [];
		if ( $ns >= NS_MAIN ) {
			// Don't talk about immovable namespaces for virtual NS_SPECIAL or NS_MEDIA
			$namespaceProtection = $this->getConfig()->get( 'NamespaceProtection' );
			if ( isset( $namespaceProtection[$ns] ) ) {
				$rightsNeeded = $namespaceProtection[$ns];
				if ( !is_array( $rightsNeeded ) ) {
					$rightsNeeded = [ $rightsNeeded ];
				}
				foreach ( $rightsNeeded as $right ) {
					$properties[] = $this->msg( 'namespaceinfo-namespace-protection-right' )
						->params( $right )
						->parse();
				}
			}

			if ( !$this->namespaceInfo->isMovable( $ns ) ) {
				$properties[] = $this->msg( 'namespaceinfo-namespace-immovable' )->parse();
			}
			if ( $this->namespaceInfo->isContent( $ns ) ) {
				$properties[] = $this->msg( 'namespaceinfo-namespace-iscontent' )->parse();
			}
			if ( $this->namespaceInfo->hasSubpages( $ns ) ) {
				$properties[] = $this->msg( 'namespaceinfo-namespace-subpages' )->parse();
			}
			if ( $this->namespaceInfo->isNonincludable( $ns ) ) {
				$properties[] = $this->msg( 'namespaceinfo-namespace-nonincludable' )->parse();
			}
			if ( $this->namespaceInfo->getNamespaceContentModel( $ns ) !== null ) {
				$properties[] = $this->msg( 'namespaceinfo-namespace-default-contentmodel' )
					->params( $this->namespaceInfo->getNamespaceContentModel( $ns ) )
					->parse();
			}
		}

		// Convert to a string
		$namespaceProperties = '';
		if ( $properties !== [] ) {
			$namespaceProperties = Html::openElement( 'ul' );
			foreach ( $properties as $propertyText ) {
				$namespaceProperties .= Html::rawElement(
					'li',
					[],
					$propertyText
				);
			}
			$namespaceProperties .= Html::closeElement( 'ul' );
		}

		$idField = Html::rawElement( 'td', [], (string)$ns );
		$canonicalField = Html::rawElement( 'td', [], $canonicalName );
		$localField = Html::rawElement( 'td', [], $localName );
		$infoField = Html::rawElement( 'td', [], $descriptionText . $namespaceProperties );

		return Html::rawElement(
			'tr',
			[],
			$idField . $canonicalField . $localField . $infoField
		);
	}

	/**
	 * @return string
	 */
	protected function getGroupName() {
		return 'wiki';
	}

}
