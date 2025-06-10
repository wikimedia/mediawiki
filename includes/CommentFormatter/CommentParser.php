<?php

namespace MediaWiki\CommentFormatter;

use MediaWiki\Cache\LinkBatch;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Cache\LinkCache;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Parser\Parser;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleParser;
use MediaWiki\Title\TitleValue;
use MediaWiki\WikiMap\WikiMap;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\StringUtils\StringUtils;

/**
 * The text processing backend for CommentFormatter.
 *
 * CommentParser objects should be discarded after the comment batch is
 * complete, in order to reduce memory usage.
 *
 * @internal
 */
class CommentParser {
	/** @var LinkRenderer */
	private $linkRenderer;
	/** @var LinkBatchFactory */
	private $linkBatchFactory;
	/** @var RepoGroup */
	private $repoGroup;
	/** @var Language */
	private $userLang;
	/** @var Language */
	private $contLang;
	/** @var TitleParser */
	private $titleParser;
	/** @var NamespaceInfo */
	private $namespaceInfo;
	/** @var HookRunner */
	private $hookRunner;
	/** @var LinkCache */
	private $linkCache;

	/** @var callable[] */
	private $links = [];
	/** @var LinkBatch|null */
	private $linkBatch;

	/** @var array Input to RepoGroup::findFiles() */
	private $fileBatch;
	/** @var File[] Resolved File objects indexed by DB key */
	private $files = [];

	/** @var int The maximum number of digits in a marker ID */
	private const MAX_ID_SIZE = 7;
	/** @var string Prefix for marker. ' and " included to break attributes (T355538) */
	private const MARKER_PREFIX = "\x1B\"'";

	/**
	 * @param LinkRenderer $linkRenderer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param LinkCache $linkCache
	 * @param RepoGroup $repoGroup
	 * @param Language $userLang
	 * @param Language $contLang
	 * @param TitleParser $titleParser
	 * @param NamespaceInfo $namespaceInfo
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		LinkRenderer $linkRenderer,
		LinkBatchFactory $linkBatchFactory,
		LinkCache $linkCache,
		RepoGroup $repoGroup,
		Language $userLang,
		Language $contLang,
		TitleParser $titleParser,
		NamespaceInfo $namespaceInfo,
		HookContainer $hookContainer
	) {
		$this->linkRenderer = $linkRenderer;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->linkCache = $linkCache;
		$this->repoGroup = $repoGroup;
		$this->userLang = $userLang;
		$this->contLang = $contLang;
		$this->titleParser = $titleParser;
		$this->namespaceInfo = $namespaceInfo;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Convert a comment to HTML, but replace links with markers which are
	 * resolved later.
	 *
	 * @param string $comment
	 * @param LinkTarget|null $selfLinkTarget
	 * @param bool $samePage
	 * @param string|false|null $wikiId
	 * @param bool $enableSectionLinks
	 * @return string
	 */
	public function preprocess( string $comment, ?LinkTarget $selfLinkTarget = null,
		$samePage = false, $wikiId = false, $enableSectionLinks = true
	) {
		return $this->preprocessInternal( $comment, false, $selfLinkTarget,
			$samePage, $wikiId, $enableSectionLinks );
	}

	/**
	 * Convert a comment in pseudo-HTML format to HTML, replacing links with markers.
	 *
	 * @param string $comment
	 * @param LinkTarget|null $selfLinkTarget
	 * @param bool $samePage
	 * @param string|false|null $wikiId
	 * @param bool $enableSectionLinks
	 * @return string
	 */
	public function preprocessUnsafe( $comment, ?LinkTarget $selfLinkTarget = null,
		$samePage = false, $wikiId = false, $enableSectionLinks = true
	) {
		return $this->preprocessInternal( $comment, true, $selfLinkTarget,
			$samePage, $wikiId, $enableSectionLinks );
	}

	/**
	 * Execute pending batch queries and replace markers in the specified
	 * string(s) with actual links.
	 *
	 * @param string|string[] $comments
	 * @return string|string[]
	 */
	public function finalize( $comments ) {
		$this->flushLinkBatches();
		return preg_replace_callback(
			'/' . self::MARKER_PREFIX . '([0-9]{' . self::MAX_ID_SIZE . '})/',
			function ( $m ) {
				$callback = $this->links[(int)$m[1]] ?? null;
				if ( $callback ) {
					return $callback();
				} else {
					return '<!-- MISSING -->';
				}
			},
			$comments
		);
	}

	/**
	 * @param string $comment
	 * @param bool $unsafe
	 * @param LinkTarget|null $selfLinkTarget
	 * @param bool $samePage
	 * @param string|false|null $wikiId
	 * @param bool $enableSectionLinks
	 * @return string
	 */
	private function preprocessInternal( $comment, $unsafe, $selfLinkTarget, $samePage, $wikiId,
		$enableSectionLinks
	) {
		// Sanitize text a bit
		// \x1b needs to be stripped because it is used for link markers
		$comment = strtr( $comment, "\n\x1b", "  " );
		// Allow HTML entities (for T15815)
		if ( !$unsafe ) {
			$comment = Sanitizer::escapeHtmlAllowEntities( $comment );
		}
		if ( $enableSectionLinks ) {
			$comment = $this->doSectionLinks( $comment, $selfLinkTarget, $samePage, $wikiId );
		}
		return $this->doWikiLinks( $comment, $selfLinkTarget, $samePage, $wikiId );
	}

	/**
	 * Converts C-style comments in edit summaries into section links.
	 *
	 * Too many things are called "comments", so these are mostly now called
	 * section links rather than autocomments.
	 *
	 * We look for all comments, match any text before and after the comment,
	 * add a separator where needed and format the comment itself with CSS.
	 *
	 * @param string $comment Comment text
	 * @param LinkTarget|null $selfLinkTarget An optional LinkTarget object used to links to sections
	 * @param bool $samePage Whether section links should refer to local page
	 * @param string|false|null $wikiId Id of the wiki to link to (if not the local wiki),
	 *  as used by WikiMap.
	 * @return string Preprocessed comment
	 */
	private function doSectionLinks(
		$comment,
		$selfLinkTarget = null,
		$samePage = false,
		$wikiId = false
	) {
		$comment = preg_replace_callback(
		// To detect the presence of content before or after the
		// auto-comment, we use capturing groups inside optional zero-width
		// assertions. But older versions of PCRE can't directly make
		// zero-width assertions optional, so wrap them in a non-capturing
		// group.
			'!(?:(?<=(.)))?/\*\s*(.*?)\s*\*/(?:(?=(.)))?!',
			function ( $match ) use ( $selfLinkTarget, $samePage, $wikiId ) {
				// Ensure all match positions are defined
				$match += [ '', '', '', '' ];

				$pre = $match[1] !== '';
				$auto = $match[2];
				$post = $match[3] !== '';
				$comment = null;

				$this->hookRunner->onFormatAutocomments(
					$comment, $pre, $auto, $post,
					Title::castFromLinkTarget( $selfLinkTarget ),
					$samePage,
					$wikiId );
				if ( $comment !== null ) {
					return $comment;
				}

				if ( $selfLinkTarget ) {
					$section = $auto;
					# Remove links that a user may have manually put in the autosummary
					# This could be improved by copying as much of Parser::stripSectionName as desired.
					$section = str_replace( [
						'[[:',
						'[[',
						']]'
					], '', $section );

					// We don't want any links in the auto text to be linked, but we still
					// want to show any [[ ]]
					$sectionText = str_replace( '[[', '&#91;[', $auto );

					$section = substr( Parser::guessSectionNameFromStrippedText( $section ), 1 );
					if ( $section !== '' ) {
						if ( $samePage ) {
							$sectionTitle = new TitleValue( NS_MAIN, '', $section );
						} else {
							$sectionTitle = $selfLinkTarget->createFragmentTarget( $section );
						}
						$auto = $this->makeSectionLink(
							$sectionTitle,
							$this->userLang->getArrow() .
								Html::rawElement( 'bdi', [ 'dir' => $this->userLang->getDir() ], $sectionText ),
							$wikiId,
							$selfLinkTarget
						);
					}
				}
				if ( $pre ) {
					# written summary $presep autocomment (summary /* section */)
					$pre = wfMessage( 'autocomment-prefix' )->inContentLanguage()->escaped();
				}
				if ( $post ) {
					# autocomment $postsep written summary (/* section */ summary)
					$auto .= wfMessage( 'colon-separator' )->inContentLanguage()->escaped();
				}
				if ( $auto ) {
					$auto = Html::rawElement( 'span', [ 'class' => 'autocomment' ], $auto );
				}
				return $pre . $auto;
			},
			$comment
		);
		return $comment;
	}

	/**
	 * Make a section link. These don't need to go into the LinkBatch, since
	 * the link class does not depend on whether the link is known.
	 *
	 * @param LinkTarget $target
	 * @param string $text
	 * @param string|false|null $wikiId Id of the wiki to link to (if not the local wiki),
	 *  as used by WikiMap.
	 * @param LinkTarget $contextTitle
	 *
	 * @return string HTML link
	 */
	private function makeSectionLink(
		LinkTarget $target, $text, $wikiId, LinkTarget $contextTitle
	) {
		if ( $wikiId !== null && $wikiId !== false && !$target->isExternal() ) {
			return $this->linkRenderer->makeExternalLink(
				WikiMap::getForeignURL(
					$wikiId,
					$target->getNamespace() === 0
						? $target->getDBkey()
						: $this->namespaceInfo->getCanonicalName( $target->getNamespace() ) .
						':' . $target->getDBkey(),
					$target->getFragment()
				),
				new HtmlArmor( $text ), // Already escaped
				$contextTitle
			);
		}
		return $this->linkRenderer->makePreloadedLink( $target, new HtmlArmor( $text ), '' );
	}

	/**
	 * Formats wiki links and media links in text; all other wiki formatting
	 * is ignored
	 *
	 * @todo FIXME: Doesn't handle sub-links as in image thumb texts like the main parser
	 *
	 * @param string $comment Text to format links in. WARNING! Since the output of this
	 *   function is html, $comment must be sanitized for use as html. You probably want
	 *   to pass $comment through Sanitizer::escapeHtmlAllowEntities() before calling
	 *   this function.
	 *   as used by WikiMap.
	 * @param LinkTarget|null $selfLinkTarget An optional LinkTarget object used to links to sections
	 * @param bool $samePage Whether section links should refer to local page
	 * @param string|false|null $wikiId Id of the wiki to link to (if not the local wiki),
	 *   as used by WikiMap.
	 *
	 * @return string HTML
	 */
	private function doWikiLinks( $comment, $selfLinkTarget = null, $samePage = false, $wikiId = false ) {
		return preg_replace_callback(
			'/
				\[\[
				\s*+ # ignore leading whitespace, the *+ quantifier disallows backtracking
				:? # ignore optional leading colon
				([^[\]|]+) # 1. link target; page names cannot include [, ] or |
				(?:\|
					# 2. link text
					# Stop matching at ]] without relying on backtracking.
					((?:]?[^\]])*+)
				)?
				\]\]
				([^[]*) # 3. link trail (the text up until the next link)
			/x',
			function ( $match ) use ( $selfLinkTarget, $samePage, $wikiId ) {
				$medians = '(?:';
				$medians .= preg_quote(
					$this->namespaceInfo->getCanonicalName( NS_MEDIA ), '/' );
				$medians .= '|';
				$medians .= preg_quote(
						$this->contLang->getNsText( NS_MEDIA ),
						'/'
					) . '):';

				$comment = $match[0];

				// Fix up urlencoded title texts (copied from Parser::replaceInternalLinks)
				if ( strpos( $match[1], '%' ) !== false ) {
					$match[1] = strtr(
						rawurldecode( $match[1] ),
						[ '<' => '&lt;', '>' => '&gt;' ]
					);
				}

				// Handle link renaming [[foo|text]] will show link as "text"
				if ( $match[2] != "" ) {
					$text = $match[2];
				} else {
					$text = $match[1];
				}
				$submatch = [];
				$linkMarker = null;
				if ( preg_match( '/^' . $medians . '(.*)$/i', $match[1], $submatch ) ) {
					// Media link; trail not supported.
					$linkRegexp = '/\[\[(.*?)\]\]/';
					$linkTarget = $this->titleParser->makeTitleValueSafe( NS_FILE, $submatch[1] );
					if ( $linkTarget ) {
						$linkMarker = $this->addFileLink( $linkTarget, $text );
					}
				} else {
					// Other kind of link
					// Make sure its target is non-empty
					if ( isset( $match[1][0] ) && $match[1][0] == ':' ) {
						$match[1] = substr( $match[1], 1 );
					}
					// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
					if ( $match[1] !== false && $match[1] !== null && $match[1] !== '' ) {
						if ( preg_match(
							$this->contLang->linkTrail(),
							$match[3],
							$submatch
						) ) {
							$trail = $submatch[1];
						} else {
							$trail = "";
						}
						$linkRegexp = '/\[\[(.*?)\]\]' . preg_quote( $trail, '/' ) . '/';
						[ $inside, $trail ] = Linker::splitTrail( $trail );

						$linkText = $text;
						$linkTarget = Linker::normalizeSubpageLink( $selfLinkTarget, $match[1], $linkText );

						try {
							$target = $this->titleParser->parseTitle( $linkTarget );

							if ( $target->getText() == '' && !$target->isExternal()
								&& !$samePage && $selfLinkTarget
							) {
								$target = $selfLinkTarget->createFragmentTarget( $target->getFragment() );
							}

							// We should deprecate `null` as a valid value for
							// $selfLinkTarget to ensure that we can use it as
							// the title context for the external link.
							// phpcs:ignore MediaWiki.Usage.DeprecatedGlobalVariables.Deprecated$wgTitle
							global $wgTitle;
							$linkMarker = $this->addPageLink(
								$target,
								$linkText . $inside,
								$wikiId,
								$selfLinkTarget ?? $wgTitle ?? SpecialPage::getTitleFor( 'Badtitle' )
							);
							$linkMarker .= $trail;
						} catch ( MalformedTitleException ) {
							// Fall through
						}
					}
				}
				if ( $linkMarker ) {
					// If the link is still valid, go ahead and replace it in!
					$comment = preg_replace(
						// @phan-suppress-next-next-line PhanPossiblyUndeclaredVariable linkRegexp set when used
						// @phan-suppress-next-line PhanTypeMismatchArgumentNullableInternal linkRegexp set when used
						$linkRegexp,
						StringUtils::escapeRegexReplacement( $linkMarker ),
						$comment,
						1
					);
				}

				return $comment;
			},
			$comment
		);
	}

	/**
	 * Add a deferred link to the list and return its marker.
	 *
	 * @param callable $callback
	 * @return string
	 */
	private function addLinkMarker( $callback ) {
		$nextId = count( $this->links );
		if ( strlen( (string)$nextId ) > self::MAX_ID_SIZE ) {
			throw new \RuntimeException( 'Too many links in comment batch' );
		}
		$this->links[] = $callback;
		return sprintf( self::MARKER_PREFIX . "%0" . self::MAX_ID_SIZE . 'd', $nextId );
	}

	/**
	 * Link to a LinkTarget. Return either HTML or a marker depending on whether
	 * existence checks are deferred.
	 *
	 * @param LinkTarget $target
	 * @param string $text
	 * @param string|false|null $wikiId
	 * @param LinkTarget $contextTitle
	 * @return string
	 */
	private function addPageLink( LinkTarget $target, $text, $wikiId, LinkTarget $contextTitle ) {
		if ( $wikiId !== null && $wikiId !== false && !$target->isExternal() ) {
			// Handle links from a foreign wiki ID
			return $this->linkRenderer->makeExternalLink(
				WikiMap::getForeignURL(
					$wikiId,
					$target->getNamespace() === 0
						? $target->getDBkey()
						: $this->namespaceInfo->getCanonicalName( $target->getNamespace() ) .
						':' . $target->getDBkey(),
					$target->getFragment()
				),
				new HtmlArmor( $text ), // Already escaped
				$contextTitle
			);
		} elseif ( $this->linkCache->getGoodLinkID( $target ) ||
			Title::newFromLinkTarget( $target )->isAlwaysKnown()
		) {
			// Already known
			return $this->linkRenderer->makeKnownLink( $target, new HtmlArmor( $text ) );
		} elseif ( $this->linkCache->isBadLink( $target ) ) {
			// Already cached as unknown
			return $this->linkRenderer->makeBrokenLink( $target, new HtmlArmor( $text ) );
		}

		// Defer page link
		if ( !$this->linkBatch ) {
			$this->linkBatch = $this->linkBatchFactory->newLinkBatch();
			$this->linkBatch->setCaller( __METHOD__ );
		}
		$this->linkBatch->addObj( $target );
		return $this->addLinkMarker( function () use ( $target, $text ) {
			return $this->linkRenderer->makeLink( $target, new HtmlArmor( $text ) );
		} );
	}

	/**
	 * Link to a file, returning a marker.
	 *
	 * @param LinkTarget $target The name of the file.
	 * @param string $html The inner HTML of the link
	 * @return string
	 */
	private function addFileLink( LinkTarget $target, $html ) {
		$this->fileBatch[] = [
			'title' => $target
		];
		return $this->addLinkMarker( function () use ( $target, $html ) {
			return Linker::makeMediaLinkFile(
				$target,
				$this->files[$target->getDBkey()] ?? false,
				$html
			);
		} );
	}

	/**
	 * Execute any pending link batch or file batch
	 */
	private function flushLinkBatches() {
		if ( $this->linkBatch ) {
			$this->linkBatch->execute();
			$this->linkBatch = null;
		}
		if ( $this->fileBatch ) {
			$this->files += $this->repoGroup->findFiles( $this->fileBatch );
			$this->fileBatch = [];
		}
	}

}
