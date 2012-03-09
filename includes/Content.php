<?php

/**
 * A content object represents page content, e.g. the text to show on a page.
 *
 */
abstract class Content {
    
    public function __construct( Title $title = null, $revId = null, $modelName = null ) { #FIXME: really need revId? annoying! #FIXME: really $title? or just when parsing, every time?
        $this->mModelName = $modelName;
        $this->mTitle = $title;
        $this->mRevId = $revId;
    }

    public function getModelName() {
        return $this->mModelName;
    }

    public function getTitle() {
        return $this->mTitle;
    }

    public function getRevId() {
        return $this->mRevId;
    }

    public abstract function getSearchText( $obj );

    public abstract function getWikitextForTransclusion( $obj );

    public abstract function getParserOutput( ParserOptions $options = NULL );

    public abstract function getRawData( );

    public function getHtml( ParserOptions $options ) {
        $po = $this->getParserOutput( $options );
        return $po->getText();
    }

    public function getIndexUpdateJobs( ParserOptions $options , $recursive = true ) {
        $po = $this->getParserOutput( $options );
        $update = new LinksUpdate( $this->mTitle, $po, $recursive );
        return $update;
    }

    public function getRedirectChain() {
        return null;
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
     * Replaces the section with the given id.
     *
     * The default implementation returns $this.
     *
     * @param String $sectionId the section's id
     * @param Content $with the section's new content
     * @return Content a new content object with the section replaced, or this content object if the section couldn't be replaced.
     */
    public function replaceSection( $sectionId ) {
    }

    #XXX: is the native model for wikitext a string or the parser output? parse early or parse late?


    # TODO: EditPage::mergeChanges( Content $a, Content $b )
    # TODO: Wikipage::isCountable(Content $a)
    # TODO: Title::newFromRedirectRecurse( $this->getRawText() );

    # TODO: isCacheable( )
    # TODO: getSize( )

    # TODO: WikiPage::getUndoText( Revision $undo, Revision $undoafter = null )
    # TODO: WikiPage::replaceSection( $section, $text, $sectionTitle = '', $edittime = null )
    # TODO: WikiPage::getAutosummary( $oldtext, $text, $flags )

    # TODO: EditPage::getPreloadedText( $preload ) // $wgParser->getPreloadText


    # TODO: tie into API to provide contentModel for Revisions
    # TODO: tie into API to provide serialized version and contentFormat for Revisions
    # TODO: tie into API edit interface

}

class TextContent extends Content {
    public function __construct( $text, Title $title = null, $revId = null, $modelName = null ) {
        parent::__construct($title, $revId, $modelName);

        $this->mText = $text;
    }

    public function getSearchText( $obj ) {
        return $this->getRawData();
    }

    public function getWikitextForTransclusion( $obj ) {
        return $this->getRawData();
    }


    public function getParserOutput( ParserOptions $options = null ) {
        # generic implementation, relying on $this->getHtml()

        $html = $this->getHtml( $options );
        $po = new ParserOutput( $html );

        if ( $this->mTitle ) $po->setTitleText( $this->mTitle->getText() );

        #TODO: cache settings, etc?

        return $po;
    }

    public function getHtml( ParserOptions $options ) {
        $html = "";
        $html .= "<pre class=\"mw-code\" dir=\"ltr\">\n";
        $html .= htmlspecialchars( $this->getRawData() );
        $html .= "\n</pre>\n";

        return $html;
    }


    public function getRawData( ) {
        $text = $this->mText;
        return $text;
    }

    public function getRedirectChain() {
        #XXX: really do this for all text, or just in WikitextContent?
        $text = $this->getRawData();
        return Title::newFromRedirectArray( $text );
    }
}

class WikitextContent extends TextContent {
    public function __construct( $text, Title $title, $revId = null) {
        parent::__construct($text, $title, $revId, CONTENT_MODEL_WIKITEXT);

        $this->mDefaultParserOptions = null;
    }

    public function getDefaultParserOptions() {
        global $wgUser, $wgContLang;

        if ( !$this->mDefaultParserOptions ) {
            #TODO: use static member?!
            $this->mDefaultParserOptions = ParserOptions::newFromUserAndLang( $wgUser, $wgContLang );
        }

        return $this->mDefaultParserOptions;
    }

    public function getParserOutput( ParserOptions $options = null ) {
        global $wgParser;

        #TODO: quick local cache: if $options is NULL, use ->mParserOutput!
        #FIXME: need setParserOutput, so we can use stuff from the parser cache??
        #FIXME: ...or we somehow need to know the parser cache key??

        if ( !$options ) {
            $options = $this->getDefaultParserOptions();
        }

        $po = $wgParser->parse( $this->mText, $this->getTitle(), $options, true, true, $this->mRevId );

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

        $text = $this->getRawData();
        $sect = $wgParser->getSection( $text, $section, false );
        $title = Title::newFromDBkey( $this->mTitle->getText() . '#' . $section, $this->mTitle->getNamespace() ); #FIXME: get rid of titles here

        return  new WikitextContent( $sect, $title );
    }

    /**
     * Replaces the section with the given id.
     *
     * @param String $sectionId the section's id
     * @param Content $with the section's new content
     * @return Boolean true if te section was replaced sucessfully, false otherwise
     */
    #FIXME: implement replaceSection(), use in WikiPage

    /**
     * @param $section empty/null/false or a section number (0, 1, 2, T1, T2...)
     * @param $text String: new text of the section
     * @param $sectionTitle String: new section's subject, only if $section is 'new'
     * @param $edittime String: revision timestamp or null to use the current revision
     * @return string Complete article text, or null if error
     */
    /*public function replaceSection( $section, $text, $sectionTitle = '', $edittime = null ) { #FIXME: adopt this!
        wfProfileIn( __METHOD__ );

        if ( strval( $section ) == '' ) {
            // Whole-page edit; let the whole text through
        } else {
            // Bug 30711: always use current version when adding a new section
            if ( is_null( $edittime ) || $section == 'new' ) {
                $oldtext = $this->getRawText();
                if ( $oldtext === false ) {
                    wfDebug( __METHOD__ . ": no page text\n" );
                    wfProfileOut( __METHOD__ );
                    return null;
                }
            } else {
                $dbw = wfGetDB( DB_MASTER );
                $rev = Revision::loadFromTimestamp( $dbw, $this->mTitle, $edittime );

                if ( !$rev ) {
                    wfDebug( "WikiPage::replaceSection asked for bogus section (page: " .
                        $this->getId() . "; section: $section; edittime: $edittime)\n" );
                    wfProfileOut( __METHOD__ );
                    return null;
                }

                $oldtext = $rev->getText();
            }

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
        }

        wfProfileOut( __METHOD__ );
        return $text;
    } */

}

class MessageContent extends TextContent {
    public function __construct( $msg_key, $params = null, $options = null ) {
        parent::__construct(null, null, null, CONTENT_MODEL_WIKITEXT);

        $this->mMessageKey = $msg_key;

        $this->mParameters = $params;

        if ( !$options ) $options = array();
        $this->mOptions = $options;

        $this->mHtmlOptions = null;
    }


    public function getHtml( ParserOptions $options ) {
        $opt = array_merge( $this->mOptions, array('parse') );
        return wfMsgExt( $this->mMessageKey, $this->mParameters, $opt );
    }


    public function getRawData( ) {
        $opt = array_diff( $this->mOptions, array('parse', 'parseinline') );

        return wfMsgExt( $this->mMessageKey, $this->mParameters, $opt );
    }

}


class JavaScriptContent extends TextContent {
    public function __construct( $text, Title $title, $revId = null ) {
        parent::__construct($text, $title, $revId, CONTENT_MODEL_JAVASCRIPT);
    }

    public function getHtml( ParserOptions $options ) {
        $html = "";
        $html .= "<pre class=\"mw-code mw-js\" dir=\"ltr\">\n";
        $html .= htmlspecialchars( $this->getRawData() );
        $html .= "\n</pre>\n";

        return $html;
    }

}

class CssContent extends TextContent {
    public function __construct( $text, Title $title, $revId = null ) {
        parent::__construct($text, $title, $revId, CONTENT_MODEL_CSS);
    }

    public function getHtml( ParserOptions $options ) {
        $html = "";
        $html .= "<pre class=\"mw-code mw-css\" dir=\"ltr\">\n";
        $html .= htmlspecialchars( $this->getRawData() );
        $html .= "\n</pre>\n";

        return $html;
    }
}

#FIXME: special type for redirects?!
#FIXME: special type for message-based pseudo-content? with raw html?

#TODO: MultipartMultipart < WikipageContent (Main + Links + X)
#TODO: LinksContent < LanguageLinksContent, CategoriesContent
#EXAMPLE: CoordinatesContent
#EXAMPLE: WikidataContent
