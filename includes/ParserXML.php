<?
require_once ( "Parser.php" ) ;

/**
 * This should one day become the XML->(X)HTML parser
 * Based on work by Jan Hidders and Magnus Manske
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

    function sub_makeXHTML ( &$parser , $tag = "" , $attr = "" )
    	{
    	$ret = "" ;
    	if ( $tag != "" )
    		{
    		$ret .= "<" . $tag ;
    		if ( $attr != "" ) $ret .= " " . $attr ;
    		$ret .= ">" ;
    		}
      foreach ($this->children as $child) {
            if ( is_string($child) ) {
                $ret .= $child ;
            } else {
                $ret .= $child->makeXHTML ( $parser );
            }
           }
    	if ( $tag != "" )
    		$ret .= "</" . $tag . ">\n" ;
      return $ret ;
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
           
      $ret = "" ;
      $ret .= "\n[[" . $target . "|" . implode ( "|" , $option ) . "]]\n" ;
      return $ret ;
    	}
    	
    function makeXHTML ( &$parser )
    	{
    	$ret = "" ;
    	$n = $this->name ; # Shortcut
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

    	else if ( $n == "LINK" )
    		$ret .= $this->makeInternalLink ( $parser ) ;
    	else if ( $n == "LINKTARGET" )
    		$ret .= $this->sub_makeXHTML ( $parser ) ;
    	else if ( $n == "LINKOPTION" )
    		$ret .= $this->sub_makeXHTML ( $parser ) ;
    		
    	else if ( $n == "EXTENSION" ) # This is currently a dummy!!!
    		{
    		$ext = $this->attrs["NAME"] ;
    		
    		$ret .= "&lt;" . $ext . "&gt;" ;
    		$ret .= $this->sub_makeXHTML ( $parser ) ;
    		$ret .= "&lt;/" . $ext . "&gt; " ;
    		}

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


    	else if ( $n == "LISTITEM" )
    		$ret .= $this->sub_makeXHTML ( $parser , "li" ) ;
    	else if ( $n == "LIST" )
    		{
    		$type = "ol" ; # Default
    		if ( $this->attrs["TYPE"] == "bullet" ) $type = "ul" ;
    		$ret .= $this->sub_makeXHTML ( $parser , $type ) ;
    		}
    		
    	else
    		{
    		$ret .= "&lt;" . $n . "&gt;" ;
    		$ret .= $this->sub_makeXHTML ( $parser ) ;
    		$ret .= "&lt;/" . $n . "&gt; " ;
    		}
    	return $ret ;
    	}

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
		global $dummytext ;
		$text = $dummytext ;
		
		$w = new xml2php;
		$result = $w->scanString( $text );
		$text .= "<hr>" . $result->makeXHTML ( $this );
		$text .= "<hr>" . $result->myPrint();
		
		$this->mOutput->setText ( $text ) ;
		return $this->mOutput;
	}	
	
	}

?>
