<?php
/**
 * Content object for wiki text pages.
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
 * @since 1.21
 *
 * @file
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;

/**
 * Content object for wiki text pages.
 *
 * @newable
 * @ingroup Content
 */
class WikitextContent extends TextContent {
	private $redirectTargetAndText = null;

	/**
	 * @var bool Tracks if the parser set the user-signature flag when creating this content, which
	 *   would make it expire faster in ApiStashEdit.
	 */
	private $hadSignature = false;

	/**
	 * @var string|null Stack trace of the previous parse
	 */
	private $previousParseStackTrace = null;

	/**
	 * @stable to call
	 *
	 * @param string $text
	 */
	public function __construct( $text ) {
		parent::__construct( $text, CONTENT_MODEL_WIKITEXT );
	}

	/**
	 * @param string|int $sectionId
	 *
	 * @return Content|bool|null
	 *
	 * @see Content::getSection()
	 */
	public function getSection( $sectionId ) {
		$text = $this->getText();
		$sect = MediaWikiServices::getInstance()->getParser()
			->getSection( $text, $sectionId, false );

		if ( $sect === false ) {
			return false;
		} else {
			return new static( $sect );
		}
	}

	/**
	 * @param string|int|null|bool $sectionId
	 * @param Content $with
	 * @param string $sectionTitle
	 *
	 * @throws MWException
	 * @return Content
	 *
	 * @see Content::replaceSection()
	 */
	public function replaceSection( $sectionId, Content $with, $sectionTitle = '' ) {
		$myModelId = $this->getModel();
		$sectionModelId = $with->getModel();

		if ( $sectionModelId != $myModelId ) {
			throw new MWException( "Incompatible content model for section: " .
				"document uses $myModelId but " .
				"section uses $sectionModelId." );
		}
		/** @var self $with $oldtext */
		'@phan-var self $with';

		$oldtext = $this->getText();
		$text = $with->getText();

		if ( strval( $sectionId ) === '' ) {
			return $with; # XXX: copy first?
		}

		if ( $sectionId === 'new' ) {
			# Inserting a new section
			$subject = $sectionTitle ? wfMessage( 'newsectionheaderdefaultlevel' )
					->plaintextParams( $sectionTitle )->inContentLanguage()->text() . "\n\n" : '';
			if ( Hooks::runner()->onPlaceNewSection( $this, $oldtext, $subject, $text ) ) {
				$text = strlen( trim( $oldtext ) ) > 0
					? "{$oldtext}\n\n{$subject}{$text}"
					: "{$subject}{$text}";
			}
		} else {
			# Replacing an existing section; roll out the big guns
			$text = MediaWikiServices::getInstance()->getParser()
				->replaceSection( $oldtext, $sectionId, $text );
		}

		$newContent = new static( $text );

		return $newContent;
	}

	/**
	 * Returns a new WikitextContent object with the given section heading
	 * prepended.
	 *
	 * @param string $header
	 *
	 * @return Content
	 */
	public function addSectionHeader( $header ) {
		$text = wfMessage( 'newsectionheaderdefaultlevel' )
			->rawParams( $header )->inContentLanguage()->text();
		$text .= "\n\n";
		$text .= $this->getText();

		return new static( $text );
	}

	/**
	 * Returns a Content object with pre-save transformations applied using
	 * Parser::preSaveTransform().
	 *
	 * @param Title $title
	 * @param User $user
	 * @param ParserOptions $popts
	 *
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		$text = $this->getText();

		$parser = MediaWikiServices::getInstance()->getParser();
		$pst = $parser->preSaveTransform( $text, $title, $user, $popts );

		if ( $text === $pst ) {
			return $this;
		}

		$ret = new static( $pst );

		if ( $parser->getOutput()->getFlag( 'user-signature' ) ) {
			$ret->hadSignature = true;
		}

		return $ret;
	}

	/**
	 * Returns a Content object with preload transformations applied (or this
	 * object if no transformations apply).
	 *
	 * @param Title $title
	 * @param ParserOptions $popts
	 * @param array $params
	 *
	 * @return Content
	 */
	public function preloadTransform( Title $title, ParserOptions $popts, $params = [] ) {
		$text = $this->getText();
		$plt = MediaWikiServices::getInstance()->getParser()
			->getPreloadText( $text, $title, $popts, $params );

		return new static( $plt );
	}

	/**
	 * Extract the redirect target and the remaining text on the page.
	 *
	 * @note migrated here from Title::newFromRedirectInternal()
	 *
	 * @since 1.23
	 *
	 * @return array List of two elements: Title|null and string.
	 */
	protected function getRedirectTargetAndText() {
		global $wgMaxRedirects;

		if ( $this->redirectTargetAndText !== null ) {
			return $this->redirectTargetAndText;
		}

		if ( $wgMaxRedirects < 1 ) {
			// redirects are disabled, so quit early
			$this->redirectTargetAndText = [ null, $this->getText() ];
			return $this->redirectTargetAndText;
		}

		$redir = MediaWikiServices::getInstance()->getMagicWordFactory()->get( 'redirect' );
		$text = ltrim( $this->getText() );
		if ( $redir->matchStartAndRemove( $text ) ) {
			// Extract the first link and see if it's usable
			// Ensure that it really does come directly after #REDIRECT
			// Some older redirects included a colon, so don't freak about that!
			$m = [];
			if ( preg_match( '!^\s*:?\s*\[{2}(.*?)(?:\|.*?)?\]{2}\s*!', $text, $m ) ) {
				// Strip preceding colon used to "escape" categories, etc.
				// and URL-decode links
				if ( strpos( $m[1], '%' ) !== false ) {
					// Match behavior of inline link parsing here;
					$m[1] = rawurldecode( ltrim( $m[1], ':' ) );
				}
				$title = Title::newFromText( $m[1] );
				// If the title is a redirect to bad special pages or is invalid, return null
				if ( !$title instanceof Title || !$title->isValidRedirectTarget() ) {
					$this->redirectTargetAndText = [ null, $this->getText() ];
					return $this->redirectTargetAndText;
				}

				$this->redirectTargetAndText = [ $title, substr( $text, strlen( $m[0] ) ) ];
				return $this->redirectTargetAndText;
			}
		}

		$this->redirectTargetAndText = [ null, $this->getText() ];
		return $this->redirectTargetAndText;
	}

	/**
	 * Implement redirect extraction for wikitext.
	 *
	 * @return Title|null
	 *
	 * @see Content::getRedirectTarget
	 */
	public function getRedirectTarget() {
		list( $title, ) = $this->getRedirectTargetAndText();

		return $title;
	}

	/**
	 * This implementation replaces the first link on the page with the given new target
	 * if this Content object is a redirect. Otherwise, this method returns $this.
	 *
	 * @since 1.21
	 *
	 * @param Title $target
	 *
	 * @return Content
	 *
	 * @see Content::updateRedirect()
	 */
	public function updateRedirect( Title $target ) {
		if ( !$this->isRedirect() ) {
			return $this;
		}

		# Fix the text
		# Remember that redirect pages can have categories, templates, etc.,
		# so the regex has to be fairly general
		$newText = preg_replace( '/ \[ \[  [^\]]*  \] \] /x',
			'[[' . $target->getFullText() . ']]',
			$this->getText(), 1 );

		return new static( $newText );
	}

	/**
	 * Returns true if this content is not a redirect, and this content's text
	 * is countable according to the criteria defined by $wgArticleCountMethod.
	 *
	 * @param bool|null $hasLinks If it is known whether this content contains
	 *    links, provide this information here, to avoid redundant parsing to
	 *    find out (default: null).
	 * @param Title|null $title Optional title, defaults to the title from the current main request.
	 *
	 * @return bool
	 */
	public function isCountable( $hasLinks = null, Title $title = null ) {
		global $wgArticleCountMethod;

		if ( $this->isRedirect() ) {
			return false;
		}

		if ( $wgArticleCountMethod === 'link' ) {
			if ( $hasLinks === null ) { # not known, find out
				if ( !$title ) {
					$context = RequestContext::getMain();
					$title = $context->getTitle();
				}

				$po = $this->getParserOutput( $title, null, null, false );
				$links = $po->getLinks();
				$hasLinks = !empty( $links );
			}

			return $hasLinks;
		}

		return true;
	}

	/**
	 * @param int $maxlength
	 * @return string
	 */
	public function getTextForSummary( $maxlength = 250 ) {
		$truncatedtext = parent::getTextForSummary( $maxlength );

		# clean up unfinished links
		# XXX: make this optional? wasn't there in autosummary, but required for
		# deletion summary.
		$truncatedtext = preg_replace( '/\[\[([^\]]*)\]?$/', '$1', $truncatedtext );

		return $truncatedtext;
	}

	/**
	 * Returns a ParserOutput object resulting from parsing the content's text
	 * using the global Parser service.
	 *
	 * @param Title $title
	 * @param int|null $revId ID of the revision being rendered.
	 *  See Parser::parse() for the ramifications. (default: null)
	 * @param ParserOptions $options (default: null)
	 * @param bool $generateHtml (default: true)
	 * @param ParserOutput &$output ParserOutput representing the HTML form of the text,
	 *           may be manipulated or replaced.
	 */
	protected function fillParserOutput( Title $title, $revId,
			ParserOptions $options, $generateHtml, ParserOutput &$output
	) {
		$stackTrace = ( new RuntimeException() )->getTraceAsString();
		if ( $this->previousParseStackTrace ) {
			// NOTE: there may be legitimate changes to re-parse the same WikiText content,
			// e.g. if predicted revision ID for the REVISIONID magic word mismatched.
			// But that should be rare.
			$logger = LoggerFactory::getInstance( 'DuplicateParse' );
			$logger->debug(
				__METHOD__ . ': Possibly redundant parse!',
				[
					'title' => $title->getPrefixedDBkey(),
					'rev' => $revId,
					'options-hash' => $options->optionsHash(
						ParserOptions::allCacheVaryingOptions(),
						$title
					),
					'trace' => $stackTrace,
					'previous-trace' => $this->previousParseStackTrace,
				]
			);
		}
		$this->previousParseStackTrace = $stackTrace;

		list( $redir, $text ) = $this->getRedirectTargetAndText();
		$output = MediaWikiServices::getInstance()->getParser()
			->parse( $text, $title, $options, true, true, $revId );

		// Add redirect indicator at the top
		if ( $redir ) {
			// Make sure to include the redirect link in pagelinks
			$output->addLink( $redir );
			if ( $generateHtml ) {
				$chain = $this->getRedirectChain();
				$output->setText(
					Article::getRedirectHeaderHtml( $title->getPageLanguage(), $chain, false ) .
					$output->getRawText()
				);
				$output->addModuleStyles( 'mediawiki.action.view.redirectPage' );
			}
		}

		// Pass along user-signature flag
		if ( $this->hadSignature ) {
			$output->setFlag( 'user-signature' );
		}
	}

	/**
	 * @throws MWException
	 */
	protected function getHtml() {
		throw new MWException(
			"getHtml() not implemented for wikitext. "
				. "Use getParserOutput()->getText()."
		);
	}

	/**
	 * This implementation calls $word->match() on the this TextContent object's text.
	 *
	 * @param MagicWord $word
	 *
	 * @return bool
	 *
	 * @see Content::matchMagicWord()
	 */
	public function matchMagicWord( MagicWord $word ) {
		return $word->match( $this->getText() );
	}

}
