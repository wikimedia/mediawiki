<?php
/**
 * Copyright © 2005 Ævar Arnfjörð Bjarmason
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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\WantedQueryPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of the most wanted categories
 *
 * @ingroup SpecialPage
 */
class SpecialWantedCategories extends WantedQueryPage {
	/** @var int[] */
	private $currentCategoryCounts;

	private ILanguageConverter $languageConverter;
	private LinksMigration $linksMigration;

	public function __construct(
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory,
		LinksMigration $linksMigration
	) {
		parent::__construct( 'Wantedcategories' );
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->languageConverter = $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() );
		$this->linksMigration = $linksMigration;
	}

	public function getQueryInfo() {
		$queryInfo = $this->linksMigration->getQueryInfo( 'categorylinks' );
		$titleField = $this->linksMigration->getTitleFields( 'categorylinks' )[1];

		return [
			'tables' => array_merge( $queryInfo['tables'], [ 'page' ] ),
			'fields' => [
				'namespace' => NS_CATEGORY,
				'title' => $titleField,
				'value' => 'COUNT(*)'
			],
			'conds' => [ 'page_title' => null ],
			'options' => [ 'GROUP BY' => $titleField ],
			'join_conds' => array_merge( $queryInfo['joins'],
				[ 'page' => [ 'LEFT JOIN',
					[ 'page_title = ' . $titleField,
						'page_namespace' => NS_CATEGORY ] ] ] )
		];
	}

	public function preprocessResults( $db, $res ) {
		parent::preprocessResults( $db, $res );

		$this->currentCategoryCounts = [];

		if ( !$res->numRows() || !$this->isCached() ) {
			return;
		}

		// Fetch (hopefully) up-to-date numbers of pages in each category.
		// This should be fast enough as we limit the list to a reasonable length.

		$allCategories = [];
		foreach ( $res as $row ) {
			$allCategories[] = $row->title;
		}

		$categoryRes = $db->newSelectQueryBuilder()
			->select( [ 'cat_title', 'cat_pages' ] )
			->from( 'category' )
			->where( [ 'cat_title' => $allCategories ] )
			->caller( __METHOD__ )->fetchResultSet();
		foreach ( $categoryRes as $row ) {
			$this->currentCategoryCounts[$row->cat_title] = intval( $row->cat_pages );
		}

		// Back to start for display
		$res->seek( 0 );
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$nt = Title::makeTitle( $result->namespace, $result->title );

		$text = new HtmlArmor( $this->languageConverter->convertHtml( $nt->getText() ) );

		if ( !$this->isCached() ) {
			// We can assume the freshest data
			$plink = $this->getLinkRenderer()->makeBrokenLink(
				$nt,
				$text
			);
			$nlinks = $this->msg( 'nmembers' )->numParams( $result->value )->escaped();
		} else {
			$plink = $this->getLinkRenderer()->makeLink( $nt, $text );

			$currentValue = $this->currentCategoryCounts[$result->title] ?? 0;
			$cachedValue = intval( $result->value ); // T76910

			// If the category has been created or emptied since the list was refreshed, strike it
			if ( $nt->isKnown() || $currentValue === 0 ) {
				$plink = "<del>$plink</del>";
			}

			// Show the current number of category entries if it changed
			if ( $currentValue !== $cachedValue ) {
				$nlinks = $this->msg( 'nmemberschanged' )
					->numParams( $cachedValue, $currentValue )->escaped();
			} else {
				$nlinks = $this->msg( 'nmembers' )->numParams( $cachedValue )->escaped();
			}
		}

		return $this->getLanguage()->specialList( $plink, $nlinks );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialWantedCategories::class, 'SpecialWantedCategories' );
