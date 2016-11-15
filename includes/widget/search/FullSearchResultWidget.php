<?php

namespace MediaWiki\Widget\Search;

use Hooks;
use Linker;
use SearchResult;
use SpecialSearch;
use Title;

/**
 * Renders a 'full' multi-line search result with metadata.
 *
 *  The Title
 *  some *highlighted* *text* about the search result
 *  5KB (651 words) - 12:40, 6 Aug 2016
 */
class FullSearchResultWidget implements SearchResultWidget {
	/** @var SpecialSearch */
	protected $specialPage;

	public function __construct( SpecialSearch $specialPage ) {
		$this->specialPage = $specialPage;
	}

	/**
	 * @param SearchResult $result The result to render
	 * @param string $terms Terms to be highlighted (@see SearchResult::getTextSnippet)
	 * @param int $position The result position, including offset
	 * @return string HTML
	 */
	public function render( SearchResult $result, $terms, $position ) {
		// If the page doesn't *exist*... our search index is out of date.
		// The least confusing at this point is to drop the result.
		// You may get less results, but... on well. :P
		if ( $result->isBrokenTitle() || $result->isMissingRevision() ) {
			return '';
		}

		$link = $this->generateMainLinkHtml( $result );
		// If page content is not readable, just return ths title.
		// This is not quite safe, but better than showing excerpts from
		// non-readable pages. Note that hiding the entry entirely would
		// screw up paging (really?).
		if ( !$result->getTitle()->userCan( 'read', $this->specialPage->getUser() ) ) {
			return "<li>{$link}</li>";
		}

		$redirect = $this->generateRedirectHtml( $result );
		$section = $this->generateSectionHtml( $result );
		$category = $this->generateCategoryHtml( $result );
		$date = $this->specialPage->getLanguage()->userTimeAndDate(
			$result->getTimestamp(),
			$this->specialPage->getUser()
		);
		list( $file, $desc, $thumb ) = $this->generateFileHtml( $result );
		$snippet = $result->getTextSnippet( $terms );
		if ( $snippet ) {
			$extract = "<div class='searchresult'>$snippet</div>";
		} else {
			$extract = '';
		}

		if ( $thumb === null ) {
			// If no thumb, then the description is about size
			$desc = $this->generateSizeHtml( $result );

			// Let hooks do their own final construction if desired. Not sure
			// why this is only for results without thumbnails, but keeping it
			// as-is for now to prevent breaking hook consumers.
			$html = null;
			$score = '';
			$related = '';
			if ( !Hooks::run( 'ShowSearchHit', [
				$this->specialPage, $result, $terms,
				&$link, &$redirect, &$section, &$extract,
				&$score, &$size, &$date, &$related, &$html
			] ) ) {
				return $html;
			}
		}

		// All the pieces have been collected. Now generate the final HTML
		$joined = "{$link} {$redirect} {$category} {$section} {$file}";
		$meta = $this->buildMeta( $desc, $date );

		if ( $thumb === null ) {
			$html =
				"<div class='mw-search-result-heading'>{$joined}</div>" .
				"{$extract} {$meta}";
		} else {
			$html =
				"<table class='searchResultImage'>" .
					"<tr>" .
						"<td style='width: 120px; text-align: center; vertical-align: top'>" .
							$thumb .
						"</td>" .
						"<td style='vertical-align: top'>" .
							"{$joined} {$extract} {$meta}" .
						"</td>" .
					"</tr>" .
				"</table>";
		}

		return "<li>{$html}</li>";
	}

	protected function generateMainLinkHtml( SearchResult $result ) {
		$snippet = $result->getTitleSnippet();
		if ( $snippet === '' ) {
			$snippet = null;
		}

		// clone to prevent hook from changing the title stored inside $result
		$title = clone $result->getTitle();
		$queryString = [];

		Hooks::run( 'ShowSearchHitTitle',
			[ $title, &$snippet, $result, $terms, $this->specialPage, $queryString ] );

		$link = Linker::linkKnown(
			$title,
			$snippet,
			[ 'data-serp-pos' => $position ],
			$queryString
		);

		return $link;
	}

	/**
	 * @param string $msgKey
	 * @param Title|null $title
	 * @param string $text
	 * @return string HTML
	 */
	protected function generateAltTitleHtml( $msgKey, Title $title = null, $text ) {
		$inner = $title === null
			? $text
			: Linker::linkKnown( $title, $text ?: null );

		return "<span class='searchalttitle'>" .
				$this->specialPage->msg( $msgKey )->rawParams( $inner )->text()
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
				->numParams( $cat->getPageCount(), $cat->getSubcatCount(), $cat->getFileCount() )
				->escaped();
		// TODO: This is a bit odd...but requires changing the i18n message to fix
		} elseif ( $result->getByteSize() !== null || $result->getWordCount() > 0 ) {
			$lang = $this->specialPage->getLanguage();
			$bytes = $lang->formatSize( $result->getByteSize() );
			$words = $result->getWordCount();

			return $this->specialPage->msg( 'search-result-size', $bytes )
				->numParams( $words )
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
		if ( $title->getNamespace() !== NS_FILE ) {
			return [ '', null, null ];
		}

		if ( $result->isFileMatch() ) {
			$html = "<span class='searchalttitle'>" .
					$this->specialPage->msg( 'search-file-match' )->escaped() .
				"</span>";
		}

		$descHtml = null;
		$thumbHtml = null;

		$img = $result->getFile() ?: wfFindFile( $title );
		if ( $img ) {
			$thumb = $img->transform( [ 'width' => 120, 'height' => 120 ] );
			if ( $thumb ) {
				$descHtml = $this->specialPage->msg( 'parentheses' )
					->rawParams( $img->getShortDesc() )
					->escaped();
				$thumbHtml = $thumb->toHtml( [ 'desc-link' => true ] );
			}
		}

		return [ $html, $descHtml, $thumbHtml ];
	}

	/**
	 * @param string $desc HTML description of result, ex: size in bytes
	 * @param string $date HTML representation of last edit date
	 * @return string HTML
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
