<?php

namespace MediaWiki\Search\SearchWidgets;

use File;
use HtmlArmor;
use MediaTransformOutput;
use MediaWiki\Category\Category;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Search\Entity\SearchResultThumbnail;
use MediaWiki\Search\SearchResultThumbnailProvider;
use MediaWiki\Specials\SpecialSearch;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsManager;
use RepoGroup;
use SearchResult;
use ThumbnailImage;

/**
 * Renders a 'full' multi-line search result with metadata.
 *
 *  The Title
 *  some *highlighted* *text* about the search result
 *  5 KiB (651 words) - 12:40, 6 Aug 2016
 */
class FullSearchResultWidget implements SearchResultWidget {
	/** @var int */
	public const THUMBNAIL_SIZE = 90;
	/** @var SpecialSearch */
	protected $specialPage;
	/** @var LinkRenderer */
	protected $linkRenderer;
	/** @var HookRunner */
	private $hookRunner;
	/** @var RepoGroup */
	private $repoGroup;
	/** @var SearchResultThumbnailProvider */
	private $thumbnailProvider;
	/** @var string */
	private $thumbnailPlaceholderHtml;
	/** @var UserOptionsManager */
	private $userOptionsManager;

	public function __construct(
		SpecialSearch $specialPage,
		LinkRenderer $linkRenderer,
		HookContainer $hookContainer,
		RepoGroup $repoGroup,
		SearchResultThumbnailProvider $thumbnailProvider,
		UserOptionsManager $userOptionsManager
	) {
		$this->specialPage = $specialPage;
		$this->linkRenderer = $linkRenderer;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->repoGroup = $repoGroup;
		$this->thumbnailProvider = $thumbnailProvider;
		$this->userOptionsManager = $userOptionsManager;
	}

	/**
	 * @param SearchResult $result The result to render
	 * @param int $position The result position, including offset
	 * @return string HTML
	 */
	public function render( SearchResult $result, $position ) {
		// If the page doesn't *exist*... our search index is out of date.
		// The least confusing at this point is to drop the result.
		// You may get less results, but... on well. :P
		if ( $result->isBrokenTitle() || $result->isMissingRevision() ) {
			return '';
		}

		$link = $this->generateMainLinkHtml( $result, $position );
		// If page content is not readable, just return ths title.
		// This is not quite safe, but better than showing excerpts from
		// non-readable pages. Note that hiding the entry entirely would
		// screw up paging (really?).
		if ( !$this->specialPage->getAuthority()->definitelyCan( 'read', $result->getTitle() ) ) {
			return Html::rawElement( 'li', [], $link );
		}

		$redirect = $this->generateRedirectHtml( $result );
		$section = $this->generateSectionHtml( $result );
		$category = $this->generateCategoryHtml( $result );
		$date = htmlspecialchars(
			$this->specialPage->getLanguage()->userTimeAndDate(
				$result->getTimestamp(),
				$this->specialPage->getUser()
			)
		);
		[ $file, $desc, $thumb ] = $this->generateFileHtml( $result );
		$snippet = $result->getTextSnippet();
		if ( $snippet ) {
			$snippetWithEllipsis = $snippet . $this->specialPage->msg( 'ellipsis' );
			$extract = Html::rawElement( 'div', [ 'class' => 'searchresult' ], $snippetWithEllipsis );
		} else {
			$extract = '';
		}

		if ( $result->getTitle() && $result->getTitle()->getNamespace() !== NS_FILE ) {
			// If no file, then the description is about size
			$desc = $this->generateSizeHtml( $result );

			// Let hooks do their own final construction if desired.
			// FIXME: Not sure why this is only for results without thumbnails,
			// but keeping it as-is for now to prevent breaking hook consumers.
			$html = null;
			$score = '';
			$related = '';
			// TODO: remove this instanceof and always pass [], let implementors do the cast if
			// they want to be SearchDatabase specific
			$terms = $result instanceof \SqlSearchResult ? $result->getTermMatches() : [];
			if ( !$this->hookRunner->onShowSearchHit( $this->specialPage, $result,
				$terms, $link, $redirect, $section, $extract, $score,
				// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
				$desc, $date, $related, $html )
			) {
				return $html;
			}
		}

		// All the pieces have been collected. Now generate the final HTML
		$joined = "{$link} {$redirect} {$category} {$section} {$file}";
		$meta = $this->buildMeta( $desc, $date );

		// Text portion of the search result
		$html = Html::rawElement(
			'div',
			[ 'class' => 'mw-search-result-heading' ],
			$joined
		);
		$html .= $extract . ' ' . $meta;

		// If the result has a thumbnail, wrap a table around it and the text
		if ( $thumb ) {
			$tableCells = Html::rawElement(
				'td',
				[ 'class' => 'searchResultImage-thumbnail' ],
				$thumb
			) . Html::rawElement(
				'td',
				[ 'class' => 'searchResultImage-text' ],
				$html
			);
			$html = Html::rawElement(
				'table',
				[ 'class' => 'searchResultImage' ],
				Html::rawElement(
					'tr',
					[],
					$tableCells
				)
			);
		}

		return Html::rawElement(
			'li',
			[ 'class' => [ 'mw-search-result', 'mw-search-result-ns-' . $result->getTitle()->getNamespace() ] ],
			$html
		);
	}

	/**
	 * Generates HTML for the primary call to action. It is
	 * typically the article title, but the search engine can
	 * return an exact snippet to use (typically the article
	 * title with highlighted words).
	 *
	 * @param SearchResult $result
	 * @param int $position
	 * @return string HTML
	 */
	protected function generateMainLinkHtml( SearchResult $result, $position ) {
		$snippet = $result->getTitleSnippet();
		if ( $snippet === '' ) {
			$snippet = null;
		} else {
			$snippet = new HtmlArmor( $snippet );
		}

		// clone to prevent hook from changing the title stored inside $result
		$title = clone $result->getTitle();
		$query = [];

		$attributes = [ 'data-serp-pos' => $position ];
		$this->hookRunner->onShowSearchHitTitle( $title, $snippet, $result,
			$result instanceof \SqlSearchResult ? $result->getTermMatches() : [],
			// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
			$this->specialPage, $query, $attributes );

		$link = $this->linkRenderer->makeLink(
			$title,
			$snippet,
			$attributes,
			$query
		);

		return $link;
	}

	/**
	 * Generates an alternate title link, such as (redirect from <a>Foo</a>).
	 *
	 * @param string $msgKey i18n message  used to wrap title
	 * @param Title|null $title The title to link to, or null to generate
	 *  the message without a link. In that case $text must be non-null.
	 * @param string|null $text The text snippet to display, or null
	 *  to use the title
	 * @return string HTML
	 */
	protected function generateAltTitleHtml( $msgKey, ?Title $title, $text ) {
		$inner = $title === null
			? $text
			: $this->linkRenderer->makeLink( $title, $text ? new HtmlArmor( $text ) : null );

		return "<span class='searchalttitle'>" .
				$this->specialPage->msg( $msgKey )->rawParams( $inner )->parse()
			. "</span>";
	}

	/**
	 * @param SearchResult $result
	 * @return string HTML
	 */
	protected function generateRedirectHtml( SearchResult $result ) {
		$title = $result->getRedirectTitle();
		return $title === null
			? ''
			: $this->generateAltTitleHtml( 'search-redirect', $title, $result->getRedirectSnippet() );
	}

	/**
	 * @param SearchResult $result
	 * @return string HTML
	 */
	protected function generateSectionHtml( SearchResult $result ) {
		$title = $result->getSectionTitle();
		return $title === null
			? ''
			: $this->generateAltTitleHtml( 'search-section', $title, $result->getSectionSnippet() );
	}

	/**
	 * @param SearchResult $result
	 * @return string HTML
	 */
	protected function generateCategoryHtml( SearchResult $result ) {
		$snippet = $result->getCategorySnippet();
		return $snippet
			? $this->generateAltTitleHtml( 'search-category', null, $snippet )
			: '';
	}

	/**
	 * @param SearchResult $result
	 * @return string HTML
	 */
	protected function generateSizeHtml( SearchResult $result ) {
		$title = $result->getTitle();
		if ( $title->getNamespace() === NS_CATEGORY ) {
			$cat = Category::newFromTitle( $title );
			return $this->specialPage->msg( 'search-result-category-size' )
				->numParams( $cat->getMemberCount(), $cat->getSubcatCount(), $cat->getFileCount() )
				->escaped();
		// TODO: This is a bit odd...but requires changing the i18n message to fix
		} elseif ( $result->getByteSize() !== null || $result->getWordCount() > 0 ) {
			return $this->specialPage->msg( 'search-result-size' )
				->sizeParams( $result->getByteSize() )
				->numParams( $result->getWordCount() )
				->escaped();
		}

		return '';
	}

	/**
	 * @param SearchResult $result
	 * @return array Three element array containing the main file html,
	 *  a text description of the file, and finally the thumbnail html.
	 *  If no thumbnail is available the second and third will be null.
	 */
	protected function generateFileHtml( SearchResult $result ) {
		$title = $result->getTitle();
		// don't assume that result is a valid title; e.g. could be an interwiki link target
		if ( $title === null || !$title->canExist() ) {
			return [ '', null, null ];
		}

		$html = '';
		if ( $result->isFileMatch() ) {
			$html = Html::rawElement(
				'span',
				[ 'class' => 'searchalttitle' ],
				$this->specialPage->msg( 'search-file-match' )->escaped()
			);
		}

		$allowExtraThumbsFromRequest = $this->specialPage->getRequest()->getVal( 'search-thumbnail-extra-namespaces' );
		$allowExtraThumbsFromPreference = $this->userOptionsManager->getOption(
			$this->specialPage->getUser(),
			'search-thumbnail-extra-namespaces'
		);
		$allowExtraThumbs = (bool)( $allowExtraThumbsFromRequest ?? $allowExtraThumbsFromPreference );
		if ( !$allowExtraThumbs && $title->getNamespace() !== NS_FILE ) {
			return [ $html, null, null ];
		}

		$thumbnail = $this->getThumbnail( $result, self::THUMBNAIL_SIZE );
		$thumbnailName = $thumbnail ? $thumbnail->getName() : null;
		if ( !$thumbnailName ) {
			return [ $html, null, $this->generateThumbnailHtml( $result ) ];
		}

		$img = $this->repoGroup->findFile( $thumbnailName );
		if ( !$img ) {
			return [ $html, null, $this->generateThumbnailHtml( $result ) ];
		}

		// File::getShortDesc() is documented to return HTML, but many handlers used to incorrectly
		// return plain text (T395834), so sanitize it in case the same bug is present in extensions.
		$unsafeShortDesc = $img->getShortDesc();
		$shortDesc = Sanitizer::removeSomeTags( $unsafeShortDesc );

		return [
			$html,
			$this->specialPage->msg( 'parentheses' )->rawParams( $shortDesc )->escaped(),
			$this->generateThumbnailHtml( $result, $thumbnail )
		];
	}

	/**
	 * @param SearchResult $result
	 * @param int $size
	 * @return SearchResultThumbnail|null
	 */
	private function getThumbnail( SearchResult $result, int $size ): ?SearchResultThumbnail {
		$title = $result->getTitle();
		// don't assume that result is a valid title; e.g. could be an interwiki link target
		if ( $title === null || !$title->canExist() ) {
			return null;
		}

		$thumbnails = $this->thumbnailProvider->getThumbnails( [ $title->getArticleID() => $title ], $size );

		return $thumbnails[ $title->getArticleID() ] ?? null;
	}

	/**
	 * @param SearchResult $result
	 * @param SearchResultThumbnail|null $thumbnail
	 * @return string|null
	 */
	private function generateThumbnailHtml( SearchResult $result, SearchResultThumbnail $thumbnail = null ): ?string {
		$title = $result->getTitle();
		// don't assume that result is a valid title; e.g. could be an interwiki link target
		if ( $title === null || !$title->canExist() ) {
			return null;
		}

		$namespacesWithThumbnails = $this->specialPage->getConfig()->get( 'ThumbnailNamespaces' );
		$showThumbnail = in_array( $title->getNamespace(), $namespacesWithThumbnails );
		if ( !$showThumbnail ) {
			return null;
		}

		$thumbnailName = $thumbnail ? $thumbnail->getName() : null;
		if ( !$thumbnail || !$thumbnailName ) {
			return $this->generateThumbnailPlaceholderHtml();
		}

		$img = $this->repoGroup->findFile( $thumbnailName );
		if ( !$img ) {
			return $this->generateThumbnailPlaceholderHtml();
		}

		$thumb = $this->transformThumbnail( $img, $thumbnail );
		if ( $thumb ) {
			if ( $title->getNamespace() === NS_FILE ) {
				// don't use a custom link, just use traditional thumbnail HTML
				return $thumb->toHtml( [
					'desc-link' => true,
					'loading' => 'lazy',
					'alt' => $this->specialPage->msg( 'search-thumbnail-alt' )->params( $title ),
				] );
			}

			// thumbnails for non-file results should link to the relevant title
			return $thumb->toHtml( [
				'desc-link' => true,
				'custom-title-link' => $title,
				'loading' => 'lazy',
				'alt' => $this->specialPage->msg( 'search-thumbnail-alt' )->params( $title ),
			] );
		}

		return $this->generateThumbnailPlaceholderHtml();
	}

	/**
	 * @param File $img
	 * @param SearchResultThumbnail $thumbnail
	 * @return ThumbnailImage|MediaTransformOutput|bool False on failure
	 */
	private function transformThumbnail( File $img, SearchResultThumbnail $thumbnail ) {
		$optimalThumbnailWidth = $thumbnail->getWidth();

		// $thumb will have rescaled to fit within a <$size>x<$size> bounding
		// box, but we want it to cover a full square (at the cost of losing
		// some of the edges)
		// instead of the largest side matching up with $size, we want the
		// smallest size to match (or exceed) $size
		$thumbnailMaxDimension = max( $thumbnail->getWidth(), $thumbnail->getHeight() );
		$thumbnailMinDimension = min( $thumbnail->getWidth(), $thumbnail->getHeight() );
		$rescaleCoefficient = $thumbnailMinDimension
			? $thumbnailMaxDimension / $thumbnailMinDimension : 1;

		// we'll only deal with width from now on since conventions for
		// standard sizes have formed around width; height will simply
		// follow according to aspect ratio
		$rescaledWidth = (int)round( $rescaleCoefficient * $thumbnail->getWidth() );

		// we'll also be looking at $wgThumbLimits to ensure that we pick
		// from within the predefined list of sizes
		// NOTE: only do this when there is a difference in the rescaled
		// size vs the original thumbnail size - some media types are
		// different and thumb limits don't matter (e.g. for audio, the
		// player must remain at the size we want, regardless of whether or
		// not it fits the thumb limits, which in this case are irrelevant)
		if ( $rescaledWidth !== $thumbnail->getWidth() ) {
			$thumbLimits = $this->specialPage->getConfig()->get( 'ThumbLimits' );
			$largerThumbLimits = array_filter(
				$thumbLimits,
				static function ( $limit ) use ( $rescaledWidth ) {
					return $limit >= $rescaledWidth;
				}
			);
			$optimalThumbnailWidth = $largerThumbLimits ? min( $largerThumbLimits ) : max( $thumbLimits );
		}

		return $img->transform( [ 'width' => $optimalThumbnailWidth ] );
	}

	/**
	 * @return string
	 */
	private function generateThumbnailPlaceholderHtml(): string {
		if ( $this->thumbnailPlaceholderHtml ) {
			return $this->thumbnailPlaceholderHtml;
		}

		$path = MW_INSTALL_PATH . '/resources/lib/ooui/themes/wikimediaui/images/icons/imageLayoutFrameless.svg';
		$this->thumbnailPlaceholderHtml = Html::rawElement(
			'div',
			[
				'class' => 'searchResultImage-thumbnail-placeholder',
				'aria-hidden' => 'true',
			],
			file_get_contents( $path )
		);
		return $this->thumbnailPlaceholderHtml;
	}

	/**
	 * @param string $desc HTML description of result, ex: size in bytes, or empty string
	 * @param string $date HTML representation of last edit date, or empty string
	 * @return string HTML A div combining $desc and $date with a separator in a <div>.
	 *  If either is missing only one will be represented. If both are missing an empty
	 *  string will be returned.
	 */
	protected function buildMeta( $desc, $date ) {
		if ( $desc && $date ) {
			$meta = "{$desc} - {$date}";
		} elseif ( $desc ) {
			$meta = $desc;
		} elseif ( $date ) {
			$meta = $date;
		} else {
			return '';
		}

		return "<div class='mw-search-result-data'>{$meta}</div>";
	}
}
