<?
/**
 * This should one day become the XML->(X)HTML parser
 * Based on work by Jan Hidders and Magnus Manske
 */

/**
 * the base class for an element
 */
class element {
  var $name = '';
  var $attrs = array();
  var $children = array();
  
  function myPrint() {
    echo '<UL>';
    echo "<LI> <B> Name: </B> $this->name";
    // print attributes
    echo '<LI> <B> Attributes: </B>';
    foreach ($this->attrs as $name => $value) {
      echo "$name => $value; " ;
    }
    // print children
    foreach ($this->children as $child) {
       if ( is_string($child) ) {
         echo '<LI> '.$child;
       } else {
         $child->myPrint();
       }
    }
    echo '</UL>';
  }

}

$ancStack = array();    // the stack with ancestral elements

// Three global functions needed for parsing, sorry guys
function wgXMLstartElement($parser, $name, $attrs) {
   global $ancStack, $rootElem;

   $newElem = new element;
   $newElem->name = $name;
   $newElem->attrs = $attrs;
   array_push($ancStack, $newElem);
   // add to parent if parent exists
   $nrAncs = count($ancStack)-1;
   if ( $nrAncs > 0 ) {
     array_push($ancStack[$nrAncs-1]->children, &$ancStack[$nrAncs]);
   } else {
     // make extra copy of root element and alias it with the original
     array_push($ancStack, &$ancStack[0]);
   }
}

function wgXMLendElement($parser, $name) {
  global $ancStack, $rootElem;
  
  // pop element of stack
  array_pop($ancStack);
}

function wgXMLcharacterData($parser, $data) {
  global $ancStack, $rootElem;
  
  $data = trim ( $data ) ; // Don't add blank lines, they're no use...
  
  // add to parent if parent exists
  if ( $ancStack && $data != "" ) {
    array_push($ancStack[count($ancStack)-1]->children, $data);   
  }
}


/**
 * Here's the class that generates a nice tree
 * package parserxml
 */
class xml2php {

   function &scanFile( $filename ) {
       global $ancStack;
       $ancStack = array();
   
       $xml_parser = xml_parser_create();
       xml_set_element_handler($xml_parser, 'wgXMLstartElement', 'wgXMLendElement');
       xml_set_character_data_handler($xml_parser, 'wgXMLcharacterData');
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
       return $ancStack[0];
   }
   
}

$w = new xml2php;
$filename = 'sample.xml';
$result = $w->scanFile( $filename );
$result->myPrint();

return 0;

?>
