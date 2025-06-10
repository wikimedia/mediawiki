<?php
/**
 * List and paging of category members.
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

namespace MediaWiki\Category;

use Collation;
use ImageGalleryBase;
use ImageGalleryClassNotFoundException;
use InvalidArgumentException;
use MediaWiki\Cache\LinkCache;
use MediaWiki\Context\ContextSource;
use MediaWiki\Context\IContextSource;
use MediaWiki\Debug\DeprecationHelper;
use MediaWiki\HookContainer\ProtectedHookAccessorTrait;
use MediaWiki\Html\Html;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Rdbms\SelectQueryBuilder;

class CategoryViewer extends ContextSource {
	use ProtectedHookAccessorTrait;
	use DeprecationHelper;

	/** @var int */
	public $limit;

	/** @var array */
	public $from;

	/** @var array */
	public $until;

	/** @var string[] */
	public $articles;

	/** @var array */
	public $articles_start_char;

	/** @var array */
	public $children;

	/** @var array */
	public $children_start_char;

	/** @var bool */
	public $showGallery;

	/** @var array */
	public $imgsNoGallery_start_char;

	/** @var array */
	public $imgsNoGallery;

	/** @var array */
	public $nextPage;

	/** @var array */
	protected $prevPage;

	/** @var array */
	public $flip;

	/** @var PageIdentity */
	protected $page;

	/** @var Collation */
	public $collation;

	/** @var ImageGalleryBase */
	public $gallery;

	/** @var Category Category object for this page. */
	private $cat;

	/** @var array The original query array, to be used in generating paging links. */
	private $query;

	/** @var ILanguageConverter */
	private $languageConverter;

	private int $migrationStage;

	/**
	 * @since 1.19 $context is a second, required parameter
	 * @param PageIdentity $page
	 * @param IContextSource $context
	 * @param array $from An array with keys page, subcat,
	 *        and file for offset of results of each section (since 1.17)
	 * @param array $until An array with 3 keys for until of each section (since 1.17)
	 * @param array $query
	 */
	public function __construct( PageIdentity $page, IContextSource $context, array $from = [],
		array $until = [], array $query = []
	) {
		$this->page = $page;

		$this->deprecatePublicPropertyFallback(
			'title',
			'1.37',
			function (): Title {
				return Title::newFromPageIdentity( $this->page );
			},
			function ( PageIdentity $page ) {
				$this->page = $page;
			}
		);

		$this->setContext( $context );
		$this->getOutput()->addModuleStyles( [
			'mediawiki.action.styles',
		] );
		$this->from = $from;
		$this->until = $until;
		$this->limit = $context->getConfig()->get( MainConfigNames::CategoryPagingLimit );
		$this->migrationStage = $context->getConfig()->get( MainConfigNames::CategoryLinksSchemaMigrationStage );
		$this->cat = Category::newFromTitle( $page );
		$this->query = $query;
		$this->collation = MediaWikiServices::getInstance()->getCollationFactory()->getCategoryCollation();
		$this->languageConverter = MediaWikiServices::getInstance()
			->getLanguageConverterFactory()->getLanguageConverter();
		unset( $this->query['title'] );
	}

	/**
	 * Format the category data list.
	 *
	 * @return string HTML output
	 */
	public function getHTML() {
		$this->showGallery = $this->getConfig()->get( MainConfigNames::CategoryMagicGallery )
			&& !$this->getOutput()->getOutputFlag( ParserOutputFlags::NO_GALLERY );

		$this->clearCategoryState();
		$this->doCategoryQuery();
		$this->finaliseCategoryState();

		$html = $this->getSubcategorySection() .
			$this->getPagesSection() .
			$this->getImageSection();

		if ( $html == '' ) {
			// If there is no category content to display, only
			// show the top part of the navigation links.
			// @todo FIXME: Cannot be completely suppressed because it
			//        is unknown if 'until' or 'from' makes this
			//        give 0 results.
			$html = $this->getCategoryTop();
		} else {
			$html = $this->getCategoryTop() .
				$html .
				$this->getCategoryBottom();
		}

		// Give a proper message if category is empty
		if ( $html == '' ) {
			$html = $this->msg( 'category-empty' )->parseAsBlock();
		}

		$lang = $this->getLanguage();
		$attribs = [
			'class' => 'mw-category-generated',
			'lang' => $lang->getHtmlCode(),
			'dir' => $lang->getDir()
		];
		# put a div around the headings which are in the user language
		$html = Html::rawElement( 'div', $attribs, $html );

		return $html;
	}

	protected function clearCategoryState() {
		$this->articles = [];
		$this->articles_start_char = [];
		$this->children = [];
		$this->children_start_char = [];
		if ( $this->showGallery ) {
			// Note that null for mode is taken to mean use default.
			$mode = $this->getRequest()->getVal( 'gallerymode', null );
			try {
				$this->gallery = ImageGalleryBase::factory( $mode, $this->getContext() );
			} catch ( ImageGalleryClassNotFoundException ) {
				// User specified something invalid, fallback to default.
				$this->gallery = ImageGalleryBase::factory( false, $this->getContext() );
			}

			$this->gallery->setHideBadImages();
		} else {
			$this->imgsNoGallery = [];
			$this->imgsNoGallery_start_char = [];
		}
	}

	/**
	 * Add a subcategory to the internal lists, using a Category object
	 * @param Category $cat
	 * @param string $sortkey
	 * @param int $pageLength
	 */
	public function addSubcategoryObject( Category $cat, $sortkey, $pageLength ) {
		$page = $cat->getPage();
		if ( !$page ) {
			return;
		}

		// Subcategory; strip the 'Category' namespace from the link text.
		$pageRecord = MediaWikiServices::getInstance()->getPageStore()
			->getPageByReference( $page );
		if ( !$pageRecord ) {
			return;
		}

		$this->children[] = $this->generateLink(
			'subcat',
			$pageRecord,
			$pageRecord->isRedirect(),
			htmlspecialchars( str_replace( '_', ' ', $pageRecord->getDBkey() ) )
		);

		$this->children_start_char[] =
			$this->getSubcategorySortChar( $page, $sortkey );
	}

	/**
	 * @param string $type
	 * @param PageReference $page
	 * @param bool $isRedirect
	 * @param string|null $html
	 * @return string
	 * Annotations needed to tell taint about HtmlArmor,
	 * due to the use of the hook it is not possible to avoid raw html handling here
	 * @param-taint $html tainted
	 * @return-taint escaped
	 */
	private function generateLink(
		string $type, PageReference $page, bool $isRedirect, ?string $html = null
	): string {
		$link = null;
		$legacyTitle = MediaWikiServices::getInstance()->getTitleFactory()
			->newFromPageReference( $page );
		// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
		$this->getHookRunner()->onCategoryViewer__generateLink( $type, $legacyTitle, $html, $link );
		if ( $link === null ) {
			$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
			if ( $html !== null ) {
				$html = new HtmlArmor( $html );
			}
			$link = $linkRenderer->makeLink( $page, $html );
		}
		if ( $isRedirect ) {
			$link = Html::rawElement(
				'span',
				[ 'class' => 'redirect-in-category' ],
				$link
			);
		}

		return $link;
	}

	/**
	 * Get the character to be used for sorting subcategories.
	 * If there's a link from Category:A to Category:B, the sortkey of the resulting
	 * entry in the categorylinks table is Category:A, not A, which it SHOULD be.
	 * Workaround: If sortkey == "Category:".$title, than use $title for sorting,
	 * else use sortkey...
	 *
	 * @param PageIdentity $page
	 * @param string $sortkey The human-readable sortkey (before transforming to icu or whatever).
	 * @return string
	 */
	public function getSubcategorySortChar( PageIdentity $page, string $sortkey ): string {
		$titleText = MediaWikiServices::getInstance()->getTitleFormatter()
			->getPrefixedText( $page );
		if ( $titleText === $sortkey ) {
			$word = $page->getDBkey();
		} else {
			$word = $sortkey;
		}

		$firstChar = $this->collation->getFirstLetter( $word );

		return $this->languageConverter->convert( $firstChar );
	}

	/**
	 * Add a page in the image namespace
	 * @param PageReference $page
	 * @param string $sortkey
	 * @param int $pageLength
	 * @param bool $isRedirect
	 */
	public function addImage(
		PageReference $page, string $sortkey, int $pageLength, bool $isRedirect = false
	): void {
		$title = MediaWikiServices::getInstance()->getTitleFactory()
			->newFromPageReference( $page );
		if ( $this->showGallery ) {
			$flip = $this->flip['file'];
			if ( $flip ) {
				$this->gallery->insert( $title, '', '', '', [], ImageGalleryBase::LOADING_LAZY );
			} else {
				$this->gallery->add( $title, '', '', '', [], ImageGalleryBase::LOADING_LAZY );
			}
		} else {
			$this->imgsNoGallery[] = $this->generateLink( 'image', $page, $isRedirect );

			$this->imgsNoGallery_start_char[] =
				$this->languageConverter->convert( $this->collation->getFirstLetter( $sortkey ) );
		}
	}

	/**
	 * Add a miscellaneous page
	 * @param PageReference $page
	 * @param string $sortkey
	 * @param int $pageLength
	 * @param bool $isRedirect
	 */
	public function addPage(
		PageReference $page,
		string $sortkey,
		int $pageLength,
		bool $isRedirect = false
	): void {
		$this->articles[] = $this->generateLink( 'page', $page, $isRedirect );

		$this->articles_start_char[] =
			$this->languageConverter->convert( $this->collation->getFirstLetter( $sortkey ) );
	}

	protected function finaliseCategoryState() {
		if ( $this->flip['subcat'] ) {
			$this->children = array_reverse( $this->children );
			$this->children_start_char = array_reverse( $this->children_start_char );
		}
		if ( $this->flip['page'] ) {
			$this->articles = array_reverse( $this->articles );
			$this->articles_start_char = array_reverse( $this->articles_start_char );
		}
		if ( !$this->showGallery && $this->flip['file'] ) {
			$this->imgsNoGallery = array_reverse( $this->imgsNoGallery );
			$this->imgsNoGallery_start_char = array_reverse( $this->imgsNoGallery_start_char );
		}
	}

	protected function doCategoryQuery() {
		$dbr = MediaWikiServices::getInstance()->getConnectionProvider()->getReplicaDatabase();

		$this->nextPage = [
			'page' => null,
			'subcat' => null,
			'file' => null,
		];
		$this->prevPage = [
			'page' => null,
			'subcat' => null,
			'file' => null,
		];

		$this->flip = [ 'page' => false, 'subcat' => false, 'file' => false ];

		foreach ( [ 'page', 'subcat', 'file' ] as $type ) {
			# Get the sortkeys for start/end, if applicable.  Note that if
			# the collation in the database differs from the one
			# set in $wgCategoryCollation, pagination might go totally haywire.
			$extraConds = [ 'cl_type' => $type ];
			if ( isset( $this->from[$type] ) ) {
				$extraConds[] = $dbr->expr(
					'cl_sortkey',
					'>=',
					$this->collation->getSortKey( $this->from[$type] )
				);
			} elseif ( isset( $this->until[$type] ) ) {
				$extraConds[] = $dbr->expr(
					'cl_sortkey',
					'<',
					$this->collation->getSortKey( $this->until[$type] )
				);
				$this->flip[$type] = true;
			}

			$queryBuilder = $dbr->newSelectQueryBuilder();
			$queryBuilder->select( array_merge(
					LinkCache::getSelectFields(),
					[
						'cl_sortkey',
						'cat_id',
						'cat_title',
						'cat_subcats',
						'cat_pages',
						'cat_files',
						'cl_sortkey_prefix',
					]
				) )
				->from( 'page' )
				->andWhere( $extraConds );

			if ( $this->flip[$type] ) {
				$queryBuilder->orderBy( 'cl_sortkey', SelectQueryBuilder::SORT_DESC );
			} else {
				$queryBuilder->orderBy( 'cl_sortkey' );
			}

			$queryBuilder
				->join( 'categorylinks', null, [ 'cl_from = page_id' ] )
				->leftJoin( 'category', null, [
					'cat_title = page_title',
					'page_namespace' => NS_CATEGORY
				] )
				->limit( $this->limit + 1 );
			if ( $this->migrationStage & SCHEMA_COMPAT_READ_OLD ) {
				$queryBuilder->where( [ 'cl_to' => $this->page->getDBkey() ] )
					->field( 'cl_collation' )
					->useIndex( [ 'categorylinks' => 'cl_sortkey' ] );
			} else {
				$queryBuilder->join( 'linktarget', null, 'cl_target_id = lt_id' )
					->where( [ 'lt_title' => $this->page->getDBkey(), 'lt_namespace' => NS_CATEGORY ] );

				$queryBuilder->join( 'collation', null, 'cl_collation_id = collation_id' )
					->field( 'collation_name', 'cl_collation' );

				$queryBuilder->useIndex( [ 'categorylinks' => 'cl_sortkey_id' ] );
			}

			$res = $queryBuilder->caller( __METHOD__ )->fetchResultSet();

			$this->getHookRunner()->onCategoryViewer__doCategoryQuery( $type, $res );
			$linkCache = MediaWikiServices::getInstance()->getLinkCache();

			$count = 0;
			foreach ( $res as $row ) {
				$title = Title::newFromRow( $row );
				$linkCache->addGoodLinkObjFromRow( $title, $row );

				if ( $row->cl_collation === '' ) {
					// Hack to make sure that while updating from 1.16 schema
					// and db is inconsistent, that the sky doesn't fall.
					// See r83544. Could perhaps be removed in a couple decades...
					$humanSortkey = $row->cl_sortkey;
				} else {
					$humanSortkey = $title->getCategorySortkey( $row->cl_sortkey_prefix );
				}

				if ( ++$count > $this->limit ) {
					# We've reached the one extra which shows that there
					# are additional pages to be had. Stop here...
					$this->nextPage[$type] = $humanSortkey;
					break;
				}
				if ( $count == $this->limit ) {
					$this->prevPage[$type] = $humanSortkey;
				}

				if ( $title->getNamespace() === NS_CATEGORY ) {
					$cat = Category::newFromRow( $row, $title );
					$this->addSubcategoryObject( $cat, $humanSortkey, $row->page_len );
				} elseif ( $title->getNamespace() === NS_FILE ) {
					$this->addImage( $title, $humanSortkey, $row->page_len, $row->page_is_redirect );
				} else {
					$this->addPage( $title, $humanSortkey, $row->page_len, $row->page_is_redirect );
				}
			}
		}
	}

	/**
	 * @return string HTML
	 */
	protected function getCategoryTop() {
		$html = $this->getCategoryBottom();
		return $html === ''
			? $html
			: "<br style=\"clear:both;\"/>\n" . $html;
	}

	/**
	 * @return string HTML
	 */
	protected function getSubcategorySection() {
		# Don't show subcategories section if there are none.
		$html = '';
		$localCount = count( $this->children );
		$databaseCount = $this->cat->getSubcatCount();
		// This function should be called even if the result isn't used, it has side-effects
		$countMessage = $this->getCountMessage( $localCount, $databaseCount, 'subcat' );

		if ( $localCount > 0 ) {
			$html .= Html::openElement( 'div', [ 'id' => 'mw-subcategories' ] ) . "\n";
			$html .= Html::rawElement( 'h2', [], $this->msg( 'subcategories' )->parse() ) . "\n";
			$html .= $countMessage;
			$html .= $this->getSectionPagingLinks( 'subcat' );
			$html .= $this->formatList( $this->children, $this->children_start_char );
			$html .= $this->getSectionPagingLinks( 'subcat' );
			$html .= "\n" . Html::closeElement( 'div' );
		}
		return $html;
	}

	/**
	 * @return string HTML
	 */
	protected function getPagesSection() {
		$name = $this->getOutput()->getUnprefixedDisplayTitle();
		# Don't show articles section if there are none.
		$html = '';

		# @todo FIXME: Here and in the other two sections: we don't need to bother
		# with this rigmarole if the entire category contents fit on one page
		# and have already been retrieved.  We can just use $rescnt in that
		# case and save a query and some logic.
		$databaseCount = $this->cat->getPageCount( Category::COUNT_CONTENT_PAGES );
		$localCount = count( $this->articles );
		// This function should be called even if the result isn't used, it has side-effects
		$countMessage = $this->getCountMessage( $localCount, $databaseCount, 'article' );

		if ( $localCount > 0 ) {
			$html .= Html::openElement( 'div', [ 'id' => 'mw-pages' ] ) . "\n";
			$html .= Html::rawElement(
				'h2',
				[],
				$this->msg( 'category_header' )->rawParams( $name )->parse()
			) . "\n";
			$html .= $countMessage;
			$html .= $this->getSectionPagingLinks( 'page' );
			$html .= $this->formatList( $this->articles, $this->articles_start_char );
			$html .= $this->getSectionPagingLinks( 'page' );
			$html .= "\n" . Html::closeElement( 'div' );
		}
		return $html;
	}

	/**
	 * @return string HTML
	 */
	protected function getImageSection() {
		$name = $this->getOutput()->getUnprefixedDisplayTitle();
		$html = '';
		$localCount = $this->showGallery ?
			$this->gallery->count() :
			count( $this->imgsNoGallery ?? [] );
		$databaseCount = $this->cat->getFileCount();
		// This function should be called even if the result isn't used, it has side-effects
		$countMessage = $this->getCountMessage( $localCount, $databaseCount, 'file' );

		if ( $localCount > 0 ) {
			$html .= Html::openElement( 'div', [ 'id' => 'mw-category-media' ] ) . "\n";
			$html .= Html::rawElement(
				'h2',
				[],
				$this->msg( 'category-media-header' )->rawParams( $name )->parse()
			) . "\n";
			$html .= $countMessage;
			$html .= $this->getSectionPagingLinks( 'file' );
			if ( $this->showGallery ) {
				$html .= $this->gallery->toHTML();
			} else {
				$html .= $this->formatList( $this->imgsNoGallery, $this->imgsNoGallery_start_char );
			}
			$html .= $this->getSectionPagingLinks( 'file' );
			$html .= "\n" . Html::closeElement( 'div' );
		}
		return $html;
	}

	/**
	 * Get the paging links for a section (subcats/pages/files), to go at the top and bottom
	 * of the output.
	 *
	 * @param string $type 'page', 'subcat', or 'file'
	 * @return string HTML output, possibly empty if there are no other pages
	 */
	private function getSectionPagingLinks( $type ) {
		if ( isset( $this->until[$type] ) ) {
			// The new value for the until parameter should be pointing to the first
			// result displayed on the page which is the second last result retrieved
			// from the database.The next link should have a from parameter pointing
			// to the until parameter of the current page.
			if ( $this->nextPage[$type] !== null ) {
				return $this->pagingLinks(
					$this->prevPage[$type] ?? '',
					$this->until[$type],
					$type
				);
			}

			// If the nextPage variable is null, it means that we have reached the first page
			// and therefore the previous link should be disabled.
			return $this->pagingLinks(
				'',
				$this->until[$type],
				$type
			);
		} elseif ( $this->nextPage[$type] !== null || isset( $this->from[$type] ) ) {
			return $this->pagingLinks(
				$this->from[$type] ?? '',
				$this->nextPage[$type],
				$type
			);
		}

		return '';
	}

	/**
	 * @return string
	 */
	protected function getCategoryBottom() {
		return '';
	}

	/**
	 * Format a list of articles chunked by letter, either as a
	 * bullet list or a columnar format, depending on the length.
	 *
	 * @param array $articles
	 * @param array $articles_start_char
	 * @param int $cutoff
	 * @return string
	 * @internal
	 */
	private function formatList( $articles, $articles_start_char, $cutoff = 6 ) {
		$list = '';
		if ( count( $articles ) > $cutoff ) {
			$list = self::columnList( $articles, $articles_start_char );
		} elseif ( count( $articles ) > 0 ) {
			// for short lists of articles in categories.
			$list = self::shortList( $articles, $articles_start_char );
		}

		$pageLang = MediaWikiServices::getInstance()->getTitleFactory()
			->newFromPageIdentity( $this->page )
			->getPageLanguage();
		$attribs = [ 'lang' => $pageLang->getHtmlCode(), 'dir' => $pageLang->getDir(),
			'class' => 'mw-content-' . $pageLang->getDir() ];
		$list = Html::rawElement( 'div', $attribs, $list );

		return $list;
	}

	/**
	 * Format a list of articles chunked by letter in a three-column list, ordered
	 * vertically. This is used for categories with a significant number of pages.
	 *
	 * @param string[] $articles HTML links to each article
	 * @param string[] $articles_start_char The header characters for each article
	 * @param string $cssClasses CSS classes for the wrapper element
	 * @return string HTML to output
	 * @internal
	 */
	public static function columnList(
		$articles,
		$articles_start_char,
		$cssClasses = 'mw-category mw-category-columns'
	) {
		$columns = array_combine( $articles, $articles_start_char );

		$ret = Html::openElement( 'div', [ 'class' => $cssClasses ] );

		$colContents = [];

		# Kind of like array_flip() here, but we keep duplicates in an
		# array instead of dropping them.
		foreach ( $columns as $article => $char ) {
			$colContents[$char][] = $article;
		}

		foreach ( $colContents as $char => $articles ) {
			# Change space to non-breaking space to keep headers aligned
			$h3char = $char === ' ' ? "\u{00A0}" : htmlspecialchars( $char );

			$ret .= Html::openElement( 'div', [ 'class' => 'mw-category-group' ] );
			$ret .= Html::rawElement( 'h3', [], $h3char ) . "\n";
			$ret .= Html::openElement( 'ul' );
			$ret .= implode(
				"\n",
				array_map(
					static function ( $article ) {
						return Html::rawElement( 'li', [], $article );
					},
					$articles
				)
			);
			$ret .= Html::closeElement( 'ul' ) . Html::closeElement( 'div' );

		}

		$ret .= Html::closeElement( 'div' );
		return $ret;
	}

	/**
	 * Format a list of articles chunked by letter in a bullet list. This is used
	 * for categories with a small number of pages (when columns aren't needed).
	 * @param string[] $articles HTML links to each article
	 * @param string[] $articles_start_char The header characters for each article
	 * @return string HTML to output
	 * @internal
	 */
	public static function shortList( $articles, $articles_start_char ) {
		return self::columnList( $articles, $articles_start_char, 'mw-category' );
	}

	/**
	 * Create paging links, as a helper method to getSectionPagingLinks().
	 *
	 * @param string $first The 'until' parameter for the generated URL
	 * @param string $last The 'from' parameter for the generated URL
	 * @param string $type A prefix for parameters, 'page' or 'subcat' or
	 *     'file'
	 * @return string HTML
	 */
	private function pagingLinks( $first, $last, $type = '' ) {
		$prevLink = $this->msg( 'prev-page' )->escaped();

		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		if ( $first != '' ) {
			$prevQuery = $this->query;
			$prevQuery["{$type}until"] = $first;
			unset( $prevQuery["{$type}from"] );
			$prevLink = $linkRenderer->makeKnownLink(
				$this->addFragmentToTitle( $this->page, $type ),
				new HtmlArmor( $prevLink ),
				[],
				$prevQuery
			);
		}

		$nextLink = $this->msg( 'next-page' )->escaped();

		if ( $last != '' ) {
			$lastQuery = $this->query;
			$lastQuery["{$type}from"] = $last;
			unset( $lastQuery["{$type}until"] );
			$nextLink = $linkRenderer->makeKnownLink(
				$this->addFragmentToTitle( $this->page, $type ),
				new HtmlArmor( $nextLink ),
				[],
				$lastQuery
			);
		}

		return $this->msg( 'categoryviewer-pagedlinks' )->rawParams( $prevLink, $nextLink )->escaped();
	}

	/**
	 * Takes a title, and adds the fragment identifier that
	 * corresponds to the correct segment of the category.
	 *
	 * @param PageReference $page The title (usually $this->title)
	 * @param string $section Which section
	 * @return LinkTarget
	 */
	private function addFragmentToTitle( PageReference $page, string $section ): LinkTarget {
		switch ( $section ) {
			case 'page':
				$fragment = 'mw-pages';
				break;
			case 'subcat':
				$fragment = 'mw-subcategories';
				break;
			case 'file':
				$fragment = 'mw-category-media';
				break;
			default:
				throw new InvalidArgumentException( __METHOD__ .
					" Invalid section $section." );
		}

		return new TitleValue( $page->getNamespace(),
			$page->getDBkey(), $fragment );
	}

	/**
	 * What to do if the category table conflicts with the number of results
	 * returned?  This function says what. Each type is considered independently
	 * of the other types.
	 *
	 * @param int $localCount The number of items returned by our database query.
	 * @param int $databaseCount The number of items according to the category table.
	 * @param string $type 'subcat', 'article', or 'file'
	 * @return string A message giving the number of items, to output to HTML.
	 */
	private function getCountMessage( $localCount, $databaseCount, $type ) {
		// There are three cases:
		//   1) The category table figure seems good.  It might be wrong, but
		//      we can't do anything about it if we don't recalculate it on ev-
		//      ery category view.
		//   2) The category table figure isn't good, like it's smaller than the
		//      number of actual results, *but* the number of results is less
		//      than $this->limit and there's no offset.  In this case we still
		//      know the right figure.
		//   3) We have no idea.

		// Check if there's a "from" or "until" for anything

		// This is a little ugly, but we seem to use different names
		// for the paging types then for the messages.
		if ( $type === 'article' ) {
			$pagingType = 'page';
		} else {
			$pagingType = $type;
		}

		$fromOrUntil = false;
		if ( isset( $this->from[$pagingType] ) || isset( $this->until[$pagingType] ) ) {
			$fromOrUntil = true;
		}

		if ( $databaseCount == $localCount ||
			( ( $localCount == $this->limit || $fromOrUntil ) && $databaseCount > $localCount )
		) {
			// Case 1: seems good.
			$totalCount = $databaseCount;
		} elseif ( $localCount < $this->limit && !$fromOrUntil ) {
			// Case 2: not good, but salvageable.  Use the number of results.
			$totalCount = $localCount;
		} else {
			// Case 3: hopeless.  Don't give a total count at all.
			// Messages: category-subcat-count-limited, category-article-count-limited,
			// category-file-count-limited
			return $this->msg( "category-$type-count-limited" )->numParams( $localCount )->parseAsBlock();
		}
		// Messages: category-subcat-count, category-article-count, category-file-count
		return $this->msg( "category-$type-count" )->numParams( $localCount, $totalCount )->parseAsBlock();
	}
}
