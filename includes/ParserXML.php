<?
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

?>
