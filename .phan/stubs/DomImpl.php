<?php
# For the purpose of phan, we're always core's DOM implementation.

class_alias( "DOMAttr", "Wikimedia\\Parsoid\\DOM\\Attr" );
class_alias( "DOMCharacterData", "Wikimedia\\Parsoid\\DOM\\CharacterData" );
class_alias( "DOMComment", "Wikimedia\\Parsoid\\DOM\\Comment" );
class_alias( "DOMDocument", "Wikimedia\\Parsoid\\DOM\\Document" );
class_alias( "DOMDocumentFragment", "Wikimedia\\Parsoid\\DOM\\DocumentFragment" );
class_alias( "DOMDocumentType", "Wikimedia\\Parsoid\\DOM\\DocumentType" );
class_alias( "DOMElement", "Wikimedia\\Parsoid\\DOM\\Element" );
class_alias( "DOMNode", "Wikimedia\\Parsoid\\DOM\\Node" );
class_alias( "DOMProcessingInstruction", "Wikimedia\\Parsoid\\DOM\\ProcessingInstruction" );
class_alias( "DOMText", "Wikimedia\\Parsoid\\DOM\\Text" );
