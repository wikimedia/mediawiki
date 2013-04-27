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

/**
 * Content object for wiki text pages.
 *
 * @ingroup Content
 */
class WikitextContent extends TextContent {

	public function __construct( $text ) {
		parent::__construct( $text, CONTENT_MODEL_WIKITEXT );
	}

	/**
	 * @see Content::getSection()
	 */
	public function getSection( $section ) {
		global $wgParser;

		$text = $this->getNativeData();
		$sect = $wgParser->getSection( $text, $section, false );

		if ( $sect === false ) {
			return false;
		} else {
			return new WikitextContent( $sect );
		}
	}

	/**
	 * @see Content::replaceSection()
	 */
	public function replaceSection( $section, Content $with, $sectionTitle = '' ) {
		wfProfileIn( __METHOD__ );

		$myModelId = $this->getModel();
		$sectionModelId = $with->getModel();

		if ( $sectionModelId != $myModelId ) {
			wfProfileOut( __METHOD__ );
			throw new MWException( "Incompatible content model for section: " .
				"document uses $myModelId but " .
				"section uses $sectionModelId." );
		}

		$oldtext = $this->getNativeData();
		$text = $with->getNativeData();

		if ( $section === '' ) {
			wfProfileOut( __METHOD__ );
			return $with; # XXX: copy first?
		} if ( $section == 'new' ) {
			# Inserting a new section
			$subject = $sectionTitle ? wfMessage( 'newsectionheaderdefaultlevel' )
				->rawParams( $sectionTitle )->inContentLanguage()->text() . "\n\n" : '';
			if ( wfRunHooks( 'PlaceNewSection', array( $this, $oldtext, $subject, &$text ) ) ) {
				$text = strlen( trim( $oldtext ) ) > 0
					? "{$oldtext}\n\n{$subject}{$text}"
					: "{$subject}{$text}";
			}
		} else {
			# Replacing an existing section; roll out the big guns
			global $wgParser;

			$text = $wgParser->replaceSection( $oldtext, $section, $text );
		}

		$newContent = new WikitextContent( $text );

		wfProfileOut( __METHOD__ );
		return $newContent;
	}

	/**
	 * Returns a new WikitextContent object with the given section heading
	 * prepended.
	 *
	 * @param $header string
	 * @return Content
	 */
	public function addSectionHeader( $header ) {
		$text = wfMessage( 'newsectionheaderdefaultlevel' )
			->rawParams( $header )->inContentLanguage()->text();
		$text .= "\n\n";
		$text .= $this->getNativeData();

		return new WikitextContent( $text );
	}

	/**
	 * Returns a Content object with pre-save transformations applied using
	 * Parser::preSaveTransform().
	 *
	 * @param $title Title
	 * @param $user User
	 * @param $popts ParserOptions
	 * @return Content
	 */
	public function preSaveTransform( Title $title, User $user, ParserOptions $popts ) {
		global $wgParser;

		$text = $this->getNativeData();
		$pst = $wgParser->preSaveTransform( $text, $title, $user, $popts );
		rtrim( $pst );

		return ( $text === $pst ) ? $this : new WikitextContent( $pst );
	}

	/**
	 * Returns a Content object with preload transformations applied (or this
	 * object if no transformations apply).
	 *
	 * @param $title Title
	 * @param $popts ParserOptions
	 * @return Content
	 */
	public function preloadTransform( Title $title, ParserOptions $popts ) {
		global $wgParser;

		$text = $this->getNativeData();
		$plt = $wgParser->getPreloadText( $text, $title, $popts );

		return new WikitextContent( $plt );
	}

	/**
	 * Implement redirect extraction for wikitext.
	 *
	 * @return null|Title
	 *
	 * @note: migrated here from Title::newFromRedirectInternal()
	 *
	 * @see Content::getRedirectTarget
	 * @see AbstractContent::getRedirectTarget
	 */
	public function getRedirectTarget() {
		global $wgMaxRedirects;
		if ( $wgMaxRedirects < 1 ) {
			// redirects are disabled, so quit early
			return null;
		}
		$redir = MagicWord::get( 'redirect' );
		$text = trim( $this->getNativeData() );
		if ( $redir->matchStartAndRemove( $text ) ) {
			// Extract the first link and see if it's usable
			// Ensure that it really does come directly after #REDIRECT
			// Some older redirects included a colon, so don't freak about that!
			$m = array();
			if ( preg_match( '!^\s*:?\s*\[{2}(.*?)(?:\|.*?)?\]{2}!', $text, $m ) ) {
				// Strip preceding colon used to "escape" categories, etc.
				// and URL-decode links
				if ( strpos( $m[1], '%' ) !== false ) {
					// Match behavior of inline link parsing here;
					$m[1] = rawurldecode( ltrim( $m[1], ':' ) );
				}
				$title = Title::newFromText( $m[1] );
				// If the title is a redirect to bad special pages or is invalid, return null
				if ( !$title instanceof Title || !$title->isValidRedirectTarget() ) {
					return null;
				}
				return $title;
			}
		}
		return null;
	}

	/**
	 * @see   Content::updateRedirect()
	 *
	 * This implementation replaces the first link on the page with the given new target
	 * if this Content object is a redirect. Otherwise, this method returns $this.
	 *
	 * @since 1.21
	 *
	 * @param Title $target
	 *
	 * @return Content a new Content object with the updated redirect (or $this if this Content object isn't a redirect)
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
			$this->getNativeData(), 1 );

		return new WikitextContent( $newText );
	}

	/**
	 * Returns true if this content is not a redirect, and this content's text
	 * is countable according to the criteria defined by $wgArticleCountMethod.
	 *
	 * @param bool $hasLinks  if it is known whether this content contains
	 *    links, provide this information here, to avoid redundant parsing to
	 *    find out (default: null).
	 * @param $title Title: (default: null)
	 *
	 * @internal param \IContextSource $context context for parsing if necessary
	 *
	 * @return bool True if the content is countable
	 */
	public function isCountable( $hasLinks = null, Title $title = null ) {
		global $wgArticleCountMethod;

		if ( $this->isRedirect() ) {
			return false;
		}

		$text = $this->getNativeData();

		switch ( $wgArticleCountMethod ) {
			case 'any':
				return true;
			case 'comma':
				return strpos( $text, ',' ) !== false;
			case 'link':
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

		return false;
	}

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
	 * using $wgParser.
	 *
	 * @since    1.21
	 *
	 * @param $title Title
	 * @param int $revId Revision to pass to the parser (default: null)
	 * @param $options ParserOptions (default: null)
	 * @param bool $generateHtml (default: false)
	 *
	 * @internal param \IContextSource|null $context
	 * @return ParserOutput representing the HTML form of the text
	 */
	public function getParserOutput( Title $title,
		$revId = null,
		ParserOptions $options = null, $generateHtml = true
	) {
		global $wgParser;

		if ( !$options ) {
			//NOTE: use canonical options per default to produce cacheable output
			$options = $this->getContentHandler()->makeParserOptions( 'canonical' );
		}

		$po = $wgParser->parse( $this->getNativeData(), $title, $options, true, true, $revId );
		return $po;
	}

	protected function getHtml() {
		throw new MWException(
			"getHtml() not implemented for wikitext. "
				. "Use getParserOutput()->getText()."
		);
	}

	/**
	 * @see  Content::matchMagicWord()
	 *
	 * This implementation calls $word->match() on the this TextContent object's text.
	 *
	 * @param MagicWord $word
	 *
	 * @return bool whether this Content object matches the given magic word.
	 */
	public function matchMagicWord( MagicWord $word ) {
		return $word->match( $this->getNativeData() );
	}
}
