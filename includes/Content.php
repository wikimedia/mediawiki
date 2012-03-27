<?php

/**
 * A content object represents page content, e.g. the text to show on a page.
 * Content objects have no knowledge about how they relate to Wiki pages.
 * Content objects are imutable.
 *
 */
abstract class Content {
    
    public function __construct( $modelName = null ) {
        $this->mModelName = $modelName;
    }

    public function getModelName() {
        return $this->mModelName;
    }

    protected function checkModelName( $modelName ) {
        if ( $modelName !== $this->mModelName ) {
            throw new MWException( "Bad content model: expected " . $this->mModelName . " but got found " . $modelName );
        }
    }

    public function getContentHandler() {
        return ContentHandler::getForContent( $this );
    }

    public function getDefaultFormat() {
        return $this->getContentHandler()->getDefaultFormat();
    }

    public function getSupportedFormats() {
        return $this->getContentHandler()->getSupportedFormats();
    }

    public function isSupportedFormat( $format ) {
        if ( !$format ) return true; # this means "use the default"

        return $this->getContentHandler()->isSupportedFormat( $format );
    }

    protected function checkFormat( $format ) {
        if ( !$this->isSupportedFormat( $format ) ) {
            throw new MWException( "Format $format is not supported for content model " . $this->getModelName() );
        }
    }

    public function serialize( $format = null ) {
        return $this->getContentHandler()->serialize( $this, $format );
    }

    /**
     * @return String a string representing the content in a way useful for building a full text search index.
     *         If no useful representation exists, this method returns an empty string.
     */
    public abstract function getTextForSearchIndex( );

    /**
     * @return String the wikitext to include when another page includes this  content, or false if the content is not
     *         includable in a wikitext page.
     */
    #TODO: allow native handling, bypassing wikitext representation, like for includable special pages.
    public abstract function getWikitextForTransclusion( ); #FIXME: use in parser, etc!

    /**
     * Returns a textual representation of the content suitable for use in edit summaries and log messages.
     *
     * @param int $maxlength maximum length of the summary text
     * @return String the summary text
     */
    public abstract function getTextForSummary( $maxlength = 250 );

    /**
     * Returns native represenation of the data. Interpretation depends on the data model used,
     * as given by getDataModel().
     *
     * @return mixed the native representation of the content. Could be a string, a nested array
     *         structure, an object, a binary blob... anything, really.
     */
    public abstract function getNativeData( ); #FIXME: review all calls carefully, caller must be aware of content model!

    /**
     * returns the content's nominal size in bogo-bytes.
     *
     * @return int
     */
    public abstract function getSize( );

    public function isEmpty() {
        return $this->getSize() == 0;
    }

    public function equals( Content $that ) {
        if ( empty( $that ) ) return false;
        if ( $that === $this ) return true;
        if ( $that->getModelName() !== $this->getModelName() ) return false;

        return $this->getNativeData() == $that->getNativeData();
    }

    /**
     * Returns true if this content is countable as a "real" wiki page, provided
     * that it's also in a countable location (e.g. a current revision in the main namespace).
     *
     * @param $hasLinks Bool: if it is known whether this content contains links, provide this information here,
     *                        to avoid redundant parsing to find out.
     */
    public abstract function isCountable( $hasLinks = null ) ;

    /**
     * @param null|Title $title
     * @param null $revId
     * @param null|ParserOptions $options
     * @return ParserOutput
     */
    public abstract function getParserOutput( Title $title = null, $revId = null, ParserOptions $options = NULL );

    /**
     * Construct the redirect destination from this content and return an
     * array of Titles, or null if this content doesn't represent a redirect.
     * The last element in the array is the final destination after all redirects
     * have been resolved (up to $wgMaxRedirects times).
     *
     * @return Array of Titles, with the destination last
     */
    public function getRedirectChain() {
        return null;
    }

    /**
     * Construct the redirect destination from this content and return an
     * array of Titles, or null if this content doesn't represent a redirect.
     * This will only return the immediate redirect target, useful for
     * the redirect table and other checks that don't need full recursion.
     *
     * @return Title: The corresponding Title
     */
    public function getRedirectTarget() {
        return null;
    }

    /**
     * Construct the redirect destination from this content and return the
     * Title, or null if this content doesn't represent a redirect.
     * This will recurse down $wgMaxRedirects times or until a non-redirect target is hit
     * in order to provide (hopefully) the Title of the final destination instead of another redirect.
     *
     * @return Title
     */
    public function getUltimateRedirectTarget() {
        return null;
    }

    public function isRedirect() {
        return $this->getRedirectTarget() != null;
    }

    /**
     * Returns the section with the given id.
     *
     * The default implementation returns null.
     *
     * @param String $sectionId the section's id
     * @return Content|Boolean|null the section, or false if no such section exist, or null if sections are not supported
     */
    public function getSection( $sectionId ) {
        return null;
    }

    /**
     * Replaces a section of the content and returns a Content object with the section replaced.
     *
     * @param $section empty/null/false or a section number (0, 1, 2, T1, T2...), or "new"
     * @param $with Content: new content of the section
     * @param $sectionTitle String: new section's subject, only if $section is 'new'
     * @return string Complete article text, or null if error
     */
    public function replaceSection( $section, Content $with, $sectionTitle = ''  ) {
        return $this;
    }

    /**
     * Returns a Content object with pre-save transformations applied (or this object if no transformations apply).
     *
     * @param Title $title
     * @param User $user
     * @param null|ParserOptions $popts
     * @return Content
     */
    public function preSaveTransform( Title $title, User $user, ParserOptions $popts = null ) {
        return $this;
    }

    /**
     * Returns a new WikitextContent object with the given section heading prepended, if supported.
     * The default implementation just returns this Content object unmodified, ignoring the section header.
     *
     * @param $header String
     * @return Content
     */
    public function addSectionHeader( $header ) {
        return $this;
    }

    /**
     * Returns a Content object with preload transformations applied (or this object if no transformations apply).
     *
     * @param Title $title
     * @param null|ParserOptions $popts
     * @return Content
     */
    public function preloadTransform( Title $title, ParserOptions $popts = null ) {
        return $this;
    }

    #TODO: implement specialized ParserOutput for Wikidata model
    #TODO: provide "combined" ParserOutput for Multipart... somehow.

    # XXX: isCacheable( ) # can/should we do this here?

    # TODO: EditPage::getPreloadedText( $preload ) // $wgParser->getPreloadText
    # TODO: tie into EditPage, make it use Content-objects throughout, make edit form aware of content model and format
    # TODO: make model-aware diff view!
    # TODO: handle ImagePage and CategoryPage

    # TODO: Title::newFromRedirectRecurse( $this->getRawText() );

    # TODO: tie into API to provide contentModel for Revisions
    # TODO: tie into API to provide serialized version and contentFormat for Revisions
    # TODO: tie into API edit interface

}

/**
 * Content object implementation for representing flat text. The
 */
abstract class TextContent extends Content {
    public function __construct( $text, $modelName = null ) {
        parent::__construct($modelName);

        $this->mText = $text;
    }

    public function getTextForSummary( $maxlength = 250 ) {
        global $wgContLang;

        $text = $this->getNativeData();

        $truncatedtext = $wgContLang->truncate(
            preg_replace( "/[\n\r]/", ' ', $text ),
            max( 0, $maxlength ) );

        return $truncatedtext;
    }

    /**
     * returns the content's nominal size in bogo-bytes.
     */
    public function getSize( ) { #FIXME: use! replace strlen in WikiPage.
        $text = $this->getNativeData( );
        return strlen( $text );
    }

    /**
     * Returns true if this content is not a redirect, and $wgArticleCountMethod is "any".
     *
     * @param $hasLinks Bool: if it is known whether this content contains links, provide this information here,
     *                        to avoid redundant parsing to find out.
     */
    public function isCountable( $hasLinks = null ) {
        global $wgArticleCountMethod;

        if ( $this->isRedirect( ) ) {
            return false;
        }

        if (  $wgArticleCountMethod === 'any' ) {
            return true;
        }

        return false;
    }

    /**
     * Returns the text represented by this Content object, as a string.
     *
     * @return String the raw text
     */
    public function getNativeData( ) {
        $text = $this->mText;
        return $text;
    }

    /**
     * Returns the text represented by this Content object, as a string.
     *
     * @return String the raw text
     */
    public function getTextForSearchIndex( ) { #FIXME: use!
        return $this->getNativeData();
    }

    /**
     * Returns the text represented by this Content object, as a string.
     *
     * @return String the raw text
     */
    public function getWikitextForTransclusion( ) { #FIXME: use!
        return $this->getNativeData();
    }

    /**
     * Returns a generic ParserOutput object, wrapping the HTML returned by getHtml().
     *
     * @return ParserOutput representing the HTML form of the text
     */
    public function getParserOutput( Title $title = null, $revId = null, ParserOptions $options = null ) {
        # generic implementation, relying on $this->getHtml()

        $html = $this->getHtml( $options );
        $po = new ParserOutput( $html );

        return $po;
    }

    protected abstract function getHtml( );

}

class WikitextContent extends TextContent {
    public function __construct( $text ) {
        parent::__construct($text, CONTENT_MODEL_WIKITEXT);

        $this->mDefaultParserOptions = null; #TODO: use per-class static member?!
    }

    protected function getHtml( ) {
        throw new MWException( "getHtml() not implemented for wikitext. Use getParserOutput()->getText()." );
    }

    public function getDefaultParserOptions() {
        global $wgUser, $wgContLang;

        if ( !$this->mDefaultParserOptions ) { #TODO: use per-class static member?!
            $this->mDefaultParserOptions = ParserOptions::newFromUserAndLang( $wgUser, $wgContLang );
        }

        return $this->mDefaultParserOptions;
    }

    /**
     * Returns a ParserOutput object reesulting from parsing the content's text using $wgParser
     *
     * @return ParserOutput representing the HTML form of the text
     */
    public function getParserOutput( Title $title = null, $revId = null, ParserOptions $options = null ) {
        global $wgParser;

        if ( !$options ) {
            $options = $this->getDefaultParserOptions();
        }

        $po = $wgParser->parse( $this->mText, $title, $options, true, true, $revId );

        return $po;
    }

    /**
     * Returns the section with the given id.
     *
     * @param String $sectionId the section's id
     * @return Content|false|null the section, or false if no such section exist, or null if sections are not supported
     */
    public function getSection( $section ) {
        global $wgParser;

        $text = $this->getNativeData();
        $sect = $wgParser->getSection( $text, $section, false );

        return  new WikitextContent( $sect );
    }

    /**
     * Replaces a section in the wikitext
     *
     * @param $section empty/null/false or a section number (0, 1, 2, T1, T2...), or "new"
     * @param $with Content: new content of the section
     * @param $sectionTitle String: new section's subject, only if $section is 'new'
     * @return string Complete article text, or null if error
     */
    public function replaceSection( $section, Content $with, $sectionTitle = '' ) {
        global $wgParser;

        wfProfileIn( __METHOD__ );

        $myModelName = $this->getModelName();
        $sectionModelName = $with->getModelName();

        if ( $sectionModelName != $myModelName  ) {
            throw new MWException( "Incompatible content model for section: document uses $myModelName, section uses $sectionModelName." );
        }

        $oldtext = $this->getNativeData();
        $text = $with->getNativeData();

        if ( $section == 'new' ) {
            # Inserting a new section
            $subject = $sectionTitle ? wfMsgForContent( 'newsectionheaderdefaultlevel', $sectionTitle ) . "\n\n" : '';
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
     * Returns a new WikitextContent object with the given section heading prepended.
     *
     * @param $header String
     * @return Content
     */
    public function addSectionHeader( $header ) {
        $text = wfMsgForContent( 'newsectionheaderdefaultlevel', $this->sectiontitle ) . "\n\n" . $this->getNativeData();

        return new WikitextContent( $text );
    }

    /**
     * Returns a Content object with pre-save transformations applied (or this object if no transformations apply).
     *
     * @param Title $title
     * @param User $user
     * @param null|ParserOptions $popts
     * @return Content
     */
    public function preSaveTransform( Title $title, User $user, ParserOptions $popts = null ) {
        global $wgParser;

        if ( $popts == null ) $popts = $this->getDefaultParserOptions();

        $text = $this->getNativeData();
        $pst = $wgParser->preSaveTransform( $text, $title, $user, $popts );

        return new WikitextContent( $pst );
    }

    /**
     * Returns a Content object with preload transformations applied (or this object if no transformations apply).
     *
     * @param Title $title
     * @param null|ParserOptions $popts
     * @return Content
     */
    public function preloadTransform( Title $title, ParserOptions $popts = null ) {
        global $wgParser;

        if ( $popts == null ) $popts = $this->getDefaultParserOptions();

        $text = $this->getNativeData();
        $plt = $wgParser->getPreloadText( $text, $title, $popts );

        return new WikitextContent( $plt );
    }

    public function getRedirectChain() {
        $text = $this->getNativeData();
        return Title::newFromRedirectArray( $text );
    }

    public function getRedirectTarget() {
        $text = $this->getNativeData();
        return Title::newFromRedirect( $text );
    }

    public function getUltimateRedirectTarget() {
        $text = $this->getNativeData();
        return Title::newFromRedirectRecurse( $text );
    }

    /**
     * Returns true if this content is not a redirect, and this content's text is countable according to
     * the criteria defiend by $wgArticleCountMethod.
     *
     * @param $hasLinks Bool: if it is known whether this content contains links, provide this information here,
     *                        to avoid redundant parsing to find out.
     */
    public function isCountable( $hasLinks = null ) {
        global $wgArticleCountMethod;

        if ( $this->isRedirect( ) ) {
            return false;
        }

        $text = $this->getNativeData();

        switch ( $wgArticleCountMethod ) {
            case 'any':
                return true;
            case 'comma':
                if ( $text === false ) {
                    $text = $this->getRawText();
                }
                return strpos( $text,  ',' ) !== false;
            case 'link':
                if ( $hasLinks === null ) { # not know, find out
                    $po = $this->getParserOutput();
                    $links = $po->getLinks();
                    $hasLinks = !empty( $links );
                }

                return $hasLinks;
        }
    }

    public function getTextForSummary( $maxlength = 250 ) {
        $truncatedtext = parent::getTextForSummary( $maxlength );

        #clean up unfinished links
        #XXX: make this optional? wasn't there in autosummary, but required for deletion summary.
        $truncatedtext = preg_replace( '/\[\[([^\]]*)\]?$/', '$1', $truncatedtext );

        return $truncatedtext;
    }

}

class MessageContent extends TextContent {
    public function __construct( $msg_key, $params = null, $options = null ) {
        parent::__construct(null, CONTENT_MODEL_WIKITEXT); #XXX: messages may be wikitext, html or plain text! and maybe even something else entirely.

        $this->mMessageKey = $msg_key;

        $this->mParameters = $params;

        if ( !$options ) $options = array();
        $this->mOptions = $options;

        $this->mHtmlOptions = null;
    }

    /**
     * Returns the message as rendered HTML, using the options supplied to the constructor plus "parse".
     */
    protected function getHtml(  ) {
        $opt = array_merge( $this->mOptions, array('parse') );

        return wfMsgExt( $this->mMessageKey, $this->mParameters, $opt );
    }


    /**
     * Returns the message as raw text, using the options supplied to the constructor minus "parse" and "parseinline".
     */
    public function getNativeData( ) {
        $opt = array_diff( $this->mOptions, array('parse', 'parseinline') );

        return wfMsgExt( $this->mMessageKey, $this->mParameters, $opt );
    }

}


class JavaScriptContent extends TextContent {
    public function __construct( $text ) {
        parent::__construct($text, CONTENT_MODEL_JAVASCRIPT);
    }

    protected function getHtml( ) {
        $html = "";
        $html .= "<pre class=\"mw-code mw-js\" dir=\"ltr\">\n";
        $html .= htmlspecialchars( $this->getNativeData() );
        $html .= "\n</pre>\n";

        return $html;
    }

}

class CssContent extends TextContent {
    public function __construct( $text ) {
        parent::__construct($text, CONTENT_MODEL_CSS);
    }

    protected function getHtml( ) {
        $html = "";
        $html .= "<pre class=\"mw-code mw-css\" dir=\"ltr\">\n";
        $html .= htmlspecialchars( $this->getNativeData() );
        $html .= "\n</pre>\n";

        return $html;
    }
}

#FUTURE: special type for redirects?!
#FUTURE: MultipartMultipart < WikipageContent (Main + Links + X)
#FUTURE: LinksContent < LanguageLinksContent, CategoriesContent
#EXAMPLE: CoordinatesContent
#EXAMPLE: WikidataContent
