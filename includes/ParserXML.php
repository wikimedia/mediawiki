<?php
require_once ( "Parser.php" ) ;

/**
 * This should one day become the XML->(X)HTML parser
 * Based on work by Jan Hidders and Magnus Manske
 * To use, set
 *    $wgUseXMLparser = true ;
 *    $wgEnableParserCache = false ;
 *    $wgWiki2xml to the path and executable of the command line version (cli)
 * in LocalSettings.php
 * @package MediaWiki
 * @subpackage Experimental
 */

/**
 * the base class for an element
 */
class element {
    var $name = '';
    var $attrs = array();
    var $children = array();

    /**
    * This finds the ATTRS element and returns the ATTR sub-children as a single string
    */
    function getSourceAttrs ()
        {
	$ret = "" ;
	foreach ($this->children as $child)
	    {
            if ( !is_string($child) AND $child->name == "ATTRS" )
	        {
                $ret = $child->makeXHTML ( $parser );
		}
	    }
	return $ret ;
	}

    /**
    * This collects the ATTR thingies for getSourceAttrs()
    */
    function getTheseAttrs ()
        {
	$ret = array() ;
	foreach ($this->children as $child)
	    {
            if ( !is_string($child) AND $child->name == "ATTR" )
	        {
		$ret[] = $child->attrs["NAME"] . "='" . $child->children[0] . "'" ;
		}
	    }
	return implode ( " " , $ret ) ;
	}

    /**
    * This function generates the XHTML for the entire subtree
    */
    function sub_makeXHTML ( &$parser , $tag = "" , $attr = "" )
    	{
    	$ret = "" ;

	$attr2 = $this->getSourceAttrs () ;
	if ( $attr != "" AND $attr2 != "" ) $attr .= " " ;
	$attr .= $attr2 ;

    	if ( $tag != "" )
    		{
    		$ret .= "<" . $tag ;
    		if ( $attr != "" ) $ret .= " " . $attr ;
    		$ret .= ">" ;
    		}

	foreach ($this->children as $child) {
            if ( is_string($child) ) {
                $ret .= $child ;
            } else if ( $child->name != "ATTRS" ) {
                $ret .= $child->makeXHTML ( $parser );
            }
           }
    	if ( $tag != "" )
    		$ret .= "</" . $tag . ">\n" ;
	return $ret ;
    	}
    	
    /**
    * Link functions
    */
    function createInternalLink ( &$parser , $target , $display_title , $options )
    	{
	global $wgUser ;
	$skin = $wgUser->getSkin() ;
    	$tp = explode ( ":" , $target ) ; # tp = target parts
    	$title = "" ;     # The plain title
    	$language = "" ;  # The language/meta/etc. part
    	$namespace = "" ; # The namespace, if any
    	$subtarget = "" ; # The '#' thingy


	$nt = Title::newFromText ( $target ) ;
	$fl = strtoupper ( $this->attrs["FORCEDLINK"] ) == "YES" ;

    	if ( $fl || count ( $tp ) == 1 ) $title = $target ; # Plain and simple case
    	else # There's stuff missing here...
    		{
		if ( $nt->getNamespace() == NS_IMAGE )
		   {
		   $options[] = $display_title ;
		   return $skin->makeImageLinkObj ( $nt , implode ( "|" , $options ) ) ;
		   }	  
		else $title = $target ; # Default
    		}
    	
    	if ( $language != "" ) # External link within the WikiMedia project
    		{
    		return "{language link}" ;
    		}
    	else if ( $namespace != "" ) # Link to another namespace, check for image/media stuff
    		{
    		return "{namespace link}" ;
    		}
    	else
    		{
		return $skin->makeLink ( $target , $display_title ) ;
    		}
    	}
    
    function makeInternalLink ( &$parser )
    	{
    	$target = "" ;
    	$option = array () ;
      foreach ($this->children as $child) {
            if ( is_string($child) ) {
            	# This shouldn't be the case!
            } else {
                if ( $child->name == "LINKTARGET" )
                	$target = trim ( $child->makeXHTML ( $parser ) ) ;
		    else
		    	$option[] = trim ( $child->makeXHTML ( $parser ) ) ;
            }
           }
           
      if ( count ( $option ) == 0 ) $option[] = $target ; # Create dummy display title
      $display_title = array_pop ( $option ) ;
      return $this->createInternalLink ( $parser , $target , $display_title , $option ) ;
    	}

    /**
    * This function actually converts wikiXML into XHTML tags
    */    	
    function makeXHTML ( &$parser )
    	{
    	$ret = "" ;
    	$n = $this->name ; # Shortcut

	if ( $n == "EXTENSION" ) # Fix allowed HTML
	   {
	   $old_n = $n ;
	   $ext = strtoupper ( $this->attrs["NAME"] ) ;
	   if ( $ext == "B" || $ext == "STRONG" ) $n = "BOLD" ;
	   else if ( $ext == "I" || $ext == "EM" ) $n = "ITALICS" ;
	   else if ( $ext == "U" ) $n = "UNDERLINED" ; # Hey, virtual wiki tag! ;-)
	   else if ( $ext == "S" ) $n = "STRIKE" ;
	   else if ( $ext == "P" ) $n = "PARAGRAPH" ;
	   else if ( $ext == "TABLE" ) $n = "TABLE" ;
	   else if ( $ext == "TR" ) $n = "TABLEROW" ;
	   else if ( $ext == "TD" ) $n = "TABLECELL" ;
	   else if ( $ext == "TH" ) $n = "TABLEHEAD" ;
	   else if ( $ext == "CAPTION" ) $n = "CAPTION" ;
	   else if ( $ext == "NOWIKI" ) $n = "NOWIKI" ;
	   if ( $n != $old_n ) unset ( $this->attrs["NAME"] ) ; # Cleanup
	   else if ( $parser->nowiki > 0 ) $n = "" ; # No "real" wiki tags allowed
	   }

    	if ( $n == "ARTICLE" )
    		$ret .= $this->sub_makeXHTML ( $parser ) ;
    	else if ( $n == "HEADING" )
    		$ret .= $this->sub_makeXHTML ( $parser , "h" . $this->attrs["LEVEL"] ) ;
    	else if ( $n == "PARAGRAPH" )
    		$ret .= $this->sub_makeXHTML ( $parser , "p" ) ;
    	else if ( $n == "BOLD" )
    		$ret .= $this->sub_makeXHTML ( $parser , "strong" ) ;
    	else if ( $n == "ITALICS" )
    		$ret .= $this->sub_makeXHTML ( $parser , "em" ) ;

	# These don't exist as wiki markup
    	else if ( $n == "UNDERLINED" )
    		$ret .= $this->sub_makeXHTML ( $parser , "u" ) ;
    	else if ( $n == "STRIKE" )
    		$ret .= $this->sub_makeXHTML ( $parser , "strike" ) ;

	# Links
    	else if ( $n == "LINK" )
    		$ret .= $this->makeInternalLink ( $parser ) ;
    	else if ( $n == "LINKTARGET" )
    		$ret .= $this->sub_makeXHTML ( $parser ) ;
    	else if ( $n == "LINKOPTION" )
    		$ret .= $this->sub_makeXHTML ( $parser ) ;

    	else if ( $n == "NOWIKI" )
		{
		$parser->nowiki++ ;
    		$ret .= $this->sub_makeXHTML ( $parser , "" ) ;
		$parser->nowiki-- ;
		}
    		
	# Unknown HTML extension
    	else if ( $n == "EXTENSION" ) # This is currently a dummy!!!
    		{
    		$ext = $this->attrs["NAME"] ;
    		
    		$ret .= "&lt;" . $ext . "&gt;" ;
    		$ret .= $this->sub_makeXHTML ( $parser ) ;
    		$ret .= "&lt;/" . $ext . "&gt; " ;
    		}

	# Table stuff
    	else if ( $n == "TABLE" )
    		{
    		$ret .= $this->sub_makeXHTML ( $parser , "table" ) ;
    		}
    	else if ( $n == "TABLEROW" )
    		{
    		$ret .= $this->sub_makeXHTML ( $parser , "tr" ) ;
    		}
    	else if ( $n == "TABLECELL" )
    		{
    		$ret .= $this->sub_makeXHTML ( $parser , "td" ) ;
    		}
    	else if ( $n == "TABLEHEAD" )
    		{
    		$ret .= $this->sub_makeXHTML ( $parser , "th" ) ;
    		}
    	else if ( $n == "CAPTION" )
    		{
    		$ret .= $this->sub_makeXHTML ( $parser , "caption" ) ;
    		}

    	else if ( $n == "ATTRS" ) # SPECIAL CASE : returning attributes
    		{
		return $this->getTheseAttrs () ;
    		}

	# Lists
    	else if ( $n == "LISTITEM" )
    		$ret .= $this->sub_makeXHTML ( $parser , "li" ) ;
    	else if ( $n == "LIST" )
    		{
    		$type = "ol" ; # Default
    		if ( $this->attrs["TYPE"] == "bullet" ) $type = "ul" ;
    		$ret .= $this->sub_makeXHTML ( $parser , $type ) ;
    		}

	# Something else entirely    		
    	else
    		{
    		$ret .= "&lt;" . $n . "&gt;" ;
    		$ret .= $this->sub_makeXHTML ( $parser ) ;
    		$ret .= "&lt;/" . $n . "&gt; " ;
    		}

    	$ret = "\n{$ret}\n" ;
    	$ret = str_replace ( "\n\n" , "\n" , $ret ) ;
    	return $ret ;
    	}

    /**
    * A function for additional debugging output
    */
    function myPrint() {
        $ret = "<ul>\n";
        $ret .= "<li> <b> Name: </b> $this->name </li>\n";
        // print attributes
        $ret .= '<li> <b> Attributes: </b>';
        foreach ($this->attrs as $name => $value) {
            $ret .= "$name => $value; " ;
        }
        $ret .= " </li>\n";
        // print children
        foreach ($this->children as $child) {
            if ( is_string($child) ) {
                $ret .= "<li> $child </li>\n";
            } else {
                $ret .= $child->myPrint();
            }
        }
        $ret .= "</ul>\n";
        return $ret;
    }
}

$ancStack = array();    // the stack with ancestral elements

// Three global functions needed for parsing, sorry guys
function wgXMLstartElement($parser, $name, $attrs) {
    global $ancStack;

    $newElem = new element;
    $newElem->name = $name;
    $newElem->attrs = $attrs;

    array_push($ancStack, $newElem);
}

function wgXMLendElement($parser, $name) {
    global $ancStack, $rootElem;
    // pop element off stack
    $elem = array_pop ($ancStack);
    if (count ($ancStack) == 0)
        $rootElem = $elem;
    else
        // add it to its parent
        array_push ($ancStack[count($ancStack)-1]->children, $elem);
}

function wgXMLcharacterData($parser, $data) {
    global $ancStack;
    $data = trim ($data); // Don't add blank lines, they're no use...
    // add to parent if parent exists
    if ( $ancStack && $data != "" ) {
        array_push ($ancStack[count($ancStack)-1]->children, $data);
    }
}


/**
 * Here's the class that generates a nice tree
 */
class xml2php {

    function &scanFile( $filename ) {
        global $ancStack, $rootElem;
        $ancStack = array();

        $xml_parser = xml_parser_create();
        xml_set_element_handler ($xml_parser, 'wgXMLstartElement', 'wgXMLendElement');
        xml_set_character_data_handler ($xml_parser, 'wgXMLcharacterData');
        if (!($fp = fopen($filename, 'r'))) {
            die('could not open XML input');
        }
        while ($data = fread($fp, 4096)) {
            if (!xml_parse($xml_parser, $data, feof($fp))) {
                die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
            }
        }
        xml_parser_free($xml_parser);

        // return the remaining root element we copied in the beginning
        return $rootElem;
    }

    function scanString ( $input ) {
        global $ancStack, $rootElem;
        $ancStack = array();

        $xml_parser = xml_parser_create();
        xml_set_element_handler ($xml_parser, 'wgXMLstartElement', 'wgXMLendElement');
        xml_set_character_data_handler ($xml_parser, 'wgXMLcharacterData');

        if (!xml_parse ($xml_parser, $input, true)) {
            die (sprintf ("XML error: %s at line %d",
                xml_error_string(xml_get_error_code($xml_parser)),
                xml_get_current_line_number($xml_parser)));
        }
        xml_parser_free ($xml_parser);

        // return the remaining root element we copied in the beginning
        return $rootElem;
    }

}

/* Example code:

    $w = new xml2php;
    $filename = 'sample.xml';
    $result = $w->scanFile( $filename );
    print $result->myPrint();
*/

$dummytext = "<article><heading level='2'> R-type </heading><paragraph><link><linktarget>image:a.jpg</linktarget><linkoption>1</linkoption><linkoption>2</linkoption><linkoption>3</linkoption><linkoption>text</linkoption></link></paragraph><paragraph>The <link><linktarget>video game</linktarget><linkoption>computer game</linkoption></link> <bold>R-type</bold> is <extension name='nowiki'>cool &amp; stuff</extension> because:</paragraph><list type='bullet'><listitem>it's nice</listitem><listitem>it's fast</listitem><listitem>it has:<list type='bullet'><listitem>graphics</listitem><listitem>sound</listitem></list></listitem></list><table><tablerow><tablecell>Version 1     </tablecell><tablecell>not bad</tablecell></tablerow><tablerow><tablecell>Version 2     </tablecell><tablecell>much better </tablecell></tablerow></table><paragraph>This is a || token in the middle of text.</paragraph></article>" ;

class ParserXML EXTENDS Parser
	{
	/**#@+
	 * @access private
	 */
	# Persistent:
	var $mTagHooks;

	# Cleared with clearState():
	var $mOutput, $mAutonumber, $mDTopen, $mStripState = array();
	var $mVariables, $mIncludeCount, $mArgStack, $mLastSection, $mInPre;

	# Temporary:
	var $mOptions, $mTitle, $mOutputType,
	    $mTemplates,	// cache of already loaded templates, avoids
		                // multiple SQL queries for the same string
	    $mTemplatePath;	// stores an unsorted hash of all the templates already loaded
		                // in this path. Used for loop detection.

	var $nowikicount ;

	/**#@-*/

	/**
	 * Constructor
	 * 
	 * @access public
	 */
	function ParserXML() {
 		$this->mTemplates = array();
 		$this->mTemplatePath = array();
		$this->mTagHooks = array();
		$this->clearState();
	}

	/**
	 * Clear Parser state
	 *
	 * @access private
	 */
	function clearState() {
		$this->mOutput = new ParserOutput;
		$this->mAutonumber = 0;
		$this->mLastSection = "";
		$this->mDTopen = false;
		$this->mVariables = false;
		$this->mIncludeCount = array();
		$this->mStripState = array();
		$this->mArgStack = array();
		$this->mInPre = false;
	}
	
	function parse( $text, &$title, $options, $linestart = true, $clearState = true ) {
		 global $wgWiki2xml ;
		$tmpfname = tempnam("/tmp", "FOO");

		$handle = fopen($tmpfname, "w");
		fwrite($handle, $text);
		fclose($handle);

		 exec ( $wgWiki2xml . " < " . $tmpfname , $a ) ;
		 $text = implode ( "\n" , $a ) ;

		unlink($tmpfname);

		$nowikicount = 0 ;
		$w = new xml2php;
		$result = $w->scanString( $text );
		$text = $result->makeXHTML ( $this ) . "<hr>" . $text ;
		$text .= "<hr>" . $result->myPrint();
		
		$this->mOutput->setText ( $text ) ;
		return $this->mOutput;
	}	
	
	}

?>
