<?

function wfSpecialImport( $page = "" ) {
	global $wgOut, $wgLang;
	
	if( $_REQUEST['action'] == 'submit') {
		$importer = WikiImporter::fromUpload();
		if( empty($importer) ) {
			$wgOut->addHTML( wfMsg( "importnofile" ) );
		} else {
			if( $importer->doImport() ) {
				# Success!
				$wgOut->addHTML( "<p>" . wfMsg( "importsuccess" ) . "</p>" );
			} else {
				$wgOut->addHTML( "<p>" . wfMsg( "importfailed",
					htmlspecialchars( $importer->getError() ) ) . "</p>" );
			}
		}
	}
	
	$wgOut->addWikiText( "<p>" . wfMsg( "importtext" ) . "</p>" );
	$action = wfLocalUrlE( $wgLang->SpecialPage( "Import" ) );
	$wgOut->addHTML( "
<form enctype='multipart/form-data'  method='post' action=\"$action\">
<input type='hidden' name='action' value='submit' />
<input type='file' name='xmlimport' value='' size='40' /><br />
<input type='submit' value='" . wfMsg( "uploadbtn" ) . "'/>
</form>
" );
}

function wfISO86012Timestamp( $ts ) {
	#2003-08-05T18:30:02Z
	# Warning: overly simplistic!
	return preg_replace( '/^(....)-(..)-(..)T(..):(..):(..)$/', '$1$2$3$4$5$6', $ts );
}

class WikiImporter {
	var $mSource, $mError;
	
	function WikiImporter() {
		$this->mSource = NULL;
		$this->mError = XML_ERROR_NONE;
	}
	
	/* static */ function fromUpload() {
		if( !empty( $_FILES['xmlimport'] ) ) {
			$fname = $_FILES['xmlimport']['tmp_name'];
			if( is_uploaded_file($fname ) ) {
				$im = new WikiImporter;
				$im->mSource = file_get_contents( $fname );
				return $im;
			}
		}
		return NULL;
	}
	
	function doImport() {
		if( !$this->mSource ) return false;
		
		/*FIXME*/
		global $wgOut;
		$wgOut->addHTML( "<pre>" . htmlspecialchars($this->mSource) . "</pre>" );
		
		$parser = xml_parser_create( "UTF-8" );
		
		/* case folding violates XML standard, turn it off */
		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, false );
		
		xml_set_object( $parser, &$this );
		xml_set_element_handler( $parser, "in_start", "" );
		
		if( !xml_parse( $parser, $this->mSource, true ) ) {
			# return error message
			$this->mError = xml_get_error_code( $parser );
			xml_parser_free( $parser );
			return false;
		}
		xml_parser_free( $parser );
		return true;
	}
	
	function getError() {
		return xml_error_string( $this->mError );
	}
	
	function throwXmlError( $err ) {
		#echo htmlspecialchars("\n** $err **\n");
		#die();
		$this->debug( "FAILURE: $err" );
	}
	
	function debug( $data ) {
		global $wgOut;
		if( true )
			$wgOut->addHTML( htmlspecialchars( $data ) . "<br>\n" );
	}
	
	/* xml parser callbacks */
	function donothing( $parser, $x, $y="" ) {
		#$this->debug( "donothing" );
	}
	
	function in_start( $parser, $name, $attribs ) {
		$this->debug( "in_start $name" );
		if( $name != "mediawiki" ) {
			return $this->throwXMLerror( "Expected <mediawiki>, got <$name>" );
		}
		xml_set_element_handler( $parser, "in_mediawiki", "out_mediawiki" );
	}
	
	function in_mediawiki( $parser, $name, $attribs ) {
		$this->debug( "in_mediawiki $name" );
		if( $name != "page" ) {
			return $this->throwXMLerror( "Expected <page>, got <$name>" );
		}
		xml_set_element_handler( $parser, "in_page", "out_page" );
	}
	function out_mediawiki( $parser, $name ) {
		$this->debug( "out_mediawiki $name" );
		if( $name != "mediawiki" ) {
			return $this->throwXMLerror( "Expected </mediawiki>, got </$name>" );
		}
		xml_set_element_handler( $parser, "donothing", "donothing" );
	}

	function in_page( $parser, $name, $attribs ) {
		$this->debug( "in_page $name" );
		switch( $name ) {
		case "id":
		case "title":
		case "restrictions":
			$this->appendfield = $name;
			$this->parenttag = "page";
			xml_set_element_handler( $parser, "in_nothing", "out_append" );
			xml_set_character_data_handler( $parser, "char_append" );
			break;
		case "revision":
			xml_set_element_handler( $parser, "in_revision", "out_revision" );
			break;
		default:
			return $this->throwXMLerror( "Element <$name> not allowed in a <page>." );
		}
	}
	function out_page( $parser, $name ) {
		$this->debug( "out_page $name" );
		if( $name != "page" ) {
			return $this->throwXMLerror( "Expected </page>, got </$name>" );
		}
		xml_set_element_handler( $parser, "in_mediawiki", "out_mediawiki" );
	}
	
	
	function in_nothing( $parser, $name, $attribs ) {
		$this->debug( "in_nothing $name" );
		return $this->throwXMLerror( "No child elements allowed here; got <$name>" );
	}
	function char_append( $parser, $data ) {
		$this->debug( "char_append '$data'" );
		$x = $this->appendfield;
		$this->pagedata->$x .= $data;
	}
	function out_append( $parser, $name ) {
		$this->debug( "out_append $name" );
		if( $name != $this->appendfield ) {
			return $this->throwXMLerror( "Expected </{$this->appendfield}>, got </$name>" );
		}
		xml_set_element_handler( $parser, "in_$this->parenttag", "out_$this->parenttag" );
		xml_set_character_data_handler( $parser, "donothing" );
	}
	
	function in_revision( $parser, $name, $attribs ) {
		$this->debug( "in_revision $name" );
		switch( $name ) {
		case "id":
		case "timestamp":
		case "comment":
		case "text":
			$this->parenttag = "revision";
			$this->appendfield = $name;
			xml_set_element_handler( $parser, "in_nothing", "out_append" );
			xml_set_character_data_handler( $parser, "char_append" );
			break;
		case "contributor":
			xml_set_element_handler( $parser, "in_contributor", "out_contributor" );
			break;
		default:
			return $this->throwXMLerror( "Element <$name> not allowed in a <revision>." );
		}
	}
	function out_revision( $parser, $name ) {
		$this->debug( "out_revision $name" );
		if( $name != "revision" ) {
			return $this->throwXMLerror( "Expected </revision>, got </$name>" );
		}
		xml_set_element_handler( $parser, "in_page", "out_page" );
	}
	
	function in_contributor( $parser, $name, $attribs ) {
		$this->debug( "in_contributor $name" );
		switch( $name ) {
		case "username":
		case "ip":
			
			break;
		default:
			$this->throwXMLerror( "Invalid tag <$name> in <contributor>" );
		}
	}
	function out_contributor( $parser, $name, $attribs ) {
		$this->debug( "out_contributor $name" );
		if( $name != "contributor" ) {
			return $this->throwXMLerror( "Expected </contributor>, got </$name>" );
		}
		xml_set_element_handler( $parser, "in_revision", "out_revision" );
	}
}


?>
