<?php
/**

Author : Nick Jenkins, http://nickj.org/
Date   : 18 May 2006.
License: GPL v 2.

Desc:
  Performs fuzz-style testing of MediaWiki's parser.
  The script feeds the parser some randomized malformed wiki-text, and stores
  the HTML output.

  Checks the HTML output for:
    - unclosed tags
    - errors in Tidy
  both can indicate potential security issues.

    Can optionally W3C validate of the HTML output (indicates malformed HTML
  output).

Background:
    Contains a PHP port, of a "shameless" Python PORT, OF LCAMTUF'S MANGELME
          http://www.securiteam.com/tools/6Z00N1PBFK.html

Requirements:
    You need PHP4 or PHP5, with PHP-curl enabled, and Tidy installed.

Usage:
    Update the "Configuration" section, especially the "WIKI_URL" to point
  to a local wiki you can test stuff on. You can optionally set
  "VALIDATE_ON_WEB" to true, although at the moment very few generated pages
  will validate. Then run "php wiki-mangleme.php".

    This will print a list of HTML output that had unclosed tags, and/or that
  caused tidy errors. It will keep running until you press Ctrl-C. All output
  files are stored in the "mangleme" subdirectory.
*/

# This is a command line script, load mediawiki env:
include('commandLine.inc');

// Configuration:

# The directory name where we store the output
# for windows: "c:\\temp\\mangleme"
define("DIRECTORY",      "/tmp/mangleme");

# URL to some wiki on which we can run our tests:
define("WIKI_URL", $wgServer . $wgScriptPath . '/index.php?title=WIKIMANGLE' );

# Should our test output include binary strings?
define("INCLUDE_BINARY",  false);

# Whether we want to send output on the web for validation:
define("VALIDATE_ON_WEB", false);
# URL to use to validate our output:
define("VALIDATOR_URL",  "http://validator.w3.org/check");


// If it goes wrong, we want to know about it.
error_reporting(E_ALL);

/////////////////////  DEFINE THE DATA THAT WILL BE USED //////////////////////
/* Note: Only some HTML tags are understood by MediaWiki, the rest is ignored. 
         The tags that are ignored have been commented out below. */ 

$data = array();
// $data["A"] = array("NAME", "HREF", "REF", "REV", "TITLE", "TARGET", "SHAPE", "onLoad", "STYLE");
// $data["APPLET"] = array("CODEBASE", "CODE", "NAME", "ALIGN", "ALT", "HEIGHT", "WIDTH", "HSPACE", "VSPACE", "DOWNLOAD", "HEIGHT", "NAME", "TITLE", "onLoad", "STYLE");
// $data["AREA"] = array("SHAPE", "ALT", "CO-ORDS", "HREF", "onLoad", "STYLE");
$data["B"] = array("onLoad", "STYLE");
// $data["BANNER"] = array("onLoad", "STYLE");
// $data["BASE"] = array("HREF", "TARGET", "onLoad", "STYLE");
// $data["BASEFONT"] = array("SIZE", "onLoad", "STYLE");
// $data["BGSOUND"] = array("SRC", "LOOP", "onLoad", "STYLE");
// $data["BQ"] = array("CLEAR", "NOWRAP", "onLoad", "STYLE");
// $data["BODY"] = array("BACKGROUND", "BGCOLOR", "TEXT", "LINK", "ALINK", "VLINK", "LEFTMARGIN", "TOPMARGIN", "BGPROPERTIES", "onLoad", "STYLE");
$data["CAPTION"] = array("ALIGN", "VALIGN", "onLoad", "STYLE");
$data["CENTER"] = array("onLoad", "STYLE");
// $data["COL"] = array("ALIGN", "SPAN", "onLoad", "STYLE");
// $data["COLGROUP"] = array("ALIGN", "VALIGN", "HALIGN", "WIDTH", "SPAN", "onLoad", "STYLE");
$data["DIV"] = array("ALIGN", "CLASS", "LANG", "onLoad", "STYLE");
// $data["EMBED"] = array("SRC", "HEIGHT", "WIDTH", "UNITS", "NAME", "PALETTE", "onLoad", "STYLE");
// $data["FIG"] = array("SRC", "ALIGN", "HEIGHT", "WIDTH", "UNITS", "IMAGEMAP", "onLoad", "STYLE");
// $data["FN"] = array("ID", "onLoad", "STYLE");
$data["FONT"] = array("SIZE", "COLOR", "FACE", "onLoad", "STYLE");
// $data["FORM"] = array("ACTION", "METHOD", "ENCTYPE", "TARGET", "SCRIPT", "onLoad", "STYLE");
// $data["FRAME"] = array("SRC", "NAME", "MARGINWIDTH", "MARGINHEIGHT", "SCROLLING", "FRAMESPACING", "onLoad", "STYLE");
// $data["FRAMESET"] = array("ROWS", "COLS", "onLoad", "STYLE");
$data["H1"] = array("SRC", "DINGBAT", "onLoad", "STYLE");
// $data["HEAD"] = array("onLoad", "STYLE");
$data["HR"] = array("SRC", "SIZE", "WIDTH", "ALIGN", "COLOR", "onLoad", "STYLE");
// $data["HTML"] = array("onLoad", "STYLE");
// $data["IFRAME"] = array("ALIGN", "FRAMEBORDER", "HEIGHT", "MARGINHEIGHT", "MARGINWIDTH", "NAME", "SCROLLING", "SRC", "ADDRESS", "WIDTH", "onLoad", "STYLE");
// $data["IMG"] = array("ALIGN", "ALT", "SRC", "BORDER", "DYNSRC", "HEIGHT", "HSPACE", "ISMAP", "LOOP", "LOWSRC", "START", "UNITS", "USEMAP", "WIDTH", "VSPACE", "onLoad", "STYLE");
// $data["INPUT"] = array("TYPE", "NAME", "VALUE", "onLoad", "STYLE");
// $data["ISINDEX"] = array("HREF", "PROMPT", "onLoad", "STYLE");
$data["LI"] = array("SRC", "DINGBAT", "SKIP", "TYPE", "VALUE", "onLoad", "STYLE");
// $data["LINK"] = array("REL", "REV", "HREF", "TITLE", "onLoad", "STYLE");
// $data["MAP"] = array("NAME", "onLoad", "STYLE");
// $data["MARQUEE"] = array("ALIGN", "BEHAVIOR", "BGCOLOR", "DIRECTION", "HEIGHT", "HSPACE", "LOOP", "SCROLLAMOUNT", "SCROLLDELAY", "WIDTH", "VSPACE", "onLoad", "STYLE");
// $data["MENU"] = array("onLoad", "STYLE");
// $data["META"] = array("HTTP-EQUIV", "CONTENT", "NAME", "onLoad", "STYLE");
// $data["MULTICOL"] = array("COLS", "GUTTER", "WIDTH", "onLoad", "STYLE");
// $data["NOFRAMES"] = array("onLoad", "STYLE");
// $data["NOTE"] = array("CLASS", "SRC", "onLoad", "STYLE");
// $data["OVERLAY"] = array("SRC", "X", "Y", "HEIGHT", "WIDTH", "UNITS", "IMAGEMAP", "onLoad", "STYLE");
// $data["PARAM"] = array("NAME", "VALUE", "onLoad", "STYLE");
// $data["RANGE"] = array("FROM", "UNTIL", "onLoad", "STYLE");
// $data["SCRIPT"] = array("LANGUAGE", "onLoad", "STYLE");
// $data["SELECT"] = array("NAME", "SIZE", "MULTIPLE", "WIDTH", "HEIGHT", "UNITS", "onLoad", "STYLE");
// $data["OPTION"] = array("VALUE", "SHAPE", "onLoad", "STYLE");
// $data["SPACER"] = array("TYPE", "SIZE", "WIDTH", "HEIGHT", "ALIGN", "onLoad", "STYLE");
// $data["SPOT"] = array("ID", "onLoad", "STYLE");
// $data["TAB"] = array("INDENT", "TO", "ALIGN", "DP", "onLoad", "STYLE");
$data["TABLE"] = array("ALIGN", "WIDTH", "BORDER", "CELLPADDING", "CELLSPACING", "BGCOLOR", "VALIGN", "COLSPEC", "UNITS", "DP", "onLoad", "STYLE");
// $data["TBODY"] = array("CLASS", "ID", "onLoad", "STYLE");
$data["TD"] = array("COLSPAN", "ROWSPAN", "ALIGN", "VALIGN", "BGCOLOR", "onLoad", "STYLE");
// $data["TEXTAREA"] = array("NAME", "COLS", "ROWS", "onLoad", "STYLE");
// $data["TEXTFLOW"] = array("CLASS", "ID", "onLoad", "STYLE");
// $data["TFOOT"] = array("COLSPAN", "ROWSPAN", "ALIGN", "VALIGN", "BGCOLOR", "onLoad", "STYLE");
$data["TH"] = array("ALIGN", "CLASS", "ID", "onLoad", "STYLE");
// $data["TITLE"] = array("onLoad", "STYLE");
$data["TR"] = array("ALIGN", "VALIGN", "BGCOLOR", "CLASS", "onLoad", "STYLE");
$data["UL"] = array("SRC", "DINGBAT", "SKIP", "TYPE", "VALUE", "onLoad", "STYLE");

// Now add in a few that were not in the original, but which MediaWiki understands, even with
// extraneous attributes:
$data["gallery"] = array("CLASS", "ID", "onLoad", "STYLE");
$data["pre"]     = array("CLASS", "ID", "onLoad", "STYLE");
$data["nowiki"]  = array("CLASS", "ID", "onLoad", "STYLE");
$data["blockquote"] = array("CLASS", "ID", "onLoad", "STYLE");
$data["span"]    = array("CLASS", "ID", "onLoad", "STYLE");
$data["code"]    = array("CLASS", "ID", "onLoad", "STYLE");
$data["tt"]      = array("CLASS", "ID", "onLoad", "STYLE");
$data["small"]   = array("CLASS", "ID", "onLoad", "STYLE");
$data["big"]     = array("CLASS", "ID", "onLoad", "STYLE");
$data["s"]       = array("CLASS", "ID", "onLoad", "STYLE");
$data["u"]       = array("CLASS", "ID", "onLoad", "STYLE");
$data["del"]     = array("CLASS", "ID", "onLoad", "STYLE");
$data["ins"]     = array("CLASS", "ID", "onLoad", "STYLE");
$data["sub"]     = array("CLASS", "ID", "onLoad", "STYLE");
$data["ol"]      = array("CLASS", "ID", "onLoad", "STYLE");


// The types of the HTML that we will be testing were defined above
$types = array_keys($data);

// Some attribute values.
$other = array("&","=",":","?","\"","\n","%n%n%n%n%n%n%n%n%n%n%n%n","\\");
$ints = array("0","-1","127","7897","89000","808080","90928345","74326794236234","0xfffffff","ffff");

///////////////////////////////// WIKI-SYNTAX ///////////////////////////
/* Note: Defines various wiki-related bits of syntax, that can potentially cause 
         MediaWiki to do something other than just print that literal text */
$ext = array(
"[[", "]]", "\n{|", "|}", "{{", "}}", "|", "[[image:", "[", "]", 
"=", "==", "===", "====", "=====", "======", "\n*", "*", "\n:", ":", 
"{{{", "}}}", 
"\n", "\n#", "#", "\n;", ";", "\n ", 
"----", "\n----", 
"|]]", "~~~", "#REDIRECT [[", "'''", "''", 
"ISBN 2", "\n|-", "| ", "\n| ",
"<!--", "-->", 
"\"", "'",
">",
"http://","https://","url://","ftp://","file://","irc://","javascript:",
"!",
"\n! ",
"!!",
"||",
".gif",
".png",
".jpg",
".jpeg",
"<!--()()",
'%08X',
'/',
":x{|",
"\n|-",
"\n|+",
"<noinclude>",
"</noinclude>",
"\n-----",
"UNIQ25f46b0524f13e67NOPARSE",
" \302\273",
" :",
" !",
" ;",
"\302\253",
"RFC 000",
"PMID 000",
"?=",
"(",
")".
"]]]",
"../",
"{{{{",
"}}}}",
"{{subst:",
'__NOTOC__',
'__FORCETOC__',
'__NOEDITSECTION__',
'__START__',
'{{PAGENAME}}',
'{{PAGENAMEE}}',
'{{NAMESPACE}}',
'{{MSG:',
'{{MSGNW:',
'__END__',
'{{INT:',        
'{{SITENAME}}',        
'{{NS:',        
'{{LOCALURL:',        
'{{LOCALURLE:',        
'{{SCRIPTPATH}}',        
'{{GRAMMAR:',        
'__NOTITLECONVERT__',        
'__NOCONTENTCONVERT__',    
"<!--MWTEMPLATESECTION=",
"<!--LINK 987-->",
"<!--IWLINK 987-->",
"Image:",
"[[category:",
"{{REVISIONID}}",
"{{SUBPAGENAME}}",
"{{SUBPAGENAMEE}}",
"{{ns:0}}",
"[[:Image",
"[[Special:",
"{{fullurl:}}",
'__TOC__',
"<includeonly>",
"</includeonly>",
"<math>",
"</math>"
);


/////////////////////  A CLASS THAT GENERATES RANDOM STRINGS OF DATA //////////////////////

class htmler {
	var $maxparams = 4;
	var $maxtypes = 40;

	function randnum($finish,$start=0) {
		return mt_rand($start,$finish);
	}

	function randstring() {
		global $ext;
		$thestring = "";
		
		for ($i=0; $i<40; $i++) {
			$what = $this->randnum(1);
			
			if ($what == 0) { // include some random wiki syntax
				$which = $this->randnum(count($ext) - 1);
				$thestring .= $ext[$which];
			}
			else { // include some random text
				$char = chr(INCLUDE_BINARY ? $this->randnum(255) : $this->randnum(126,32));
				if ($char == "<") $char = ""; // we don't want the '<' character, it stuffs us up.
				$length = $this->randnum(8);
				$thestring .= str_repeat ($char, $length);
			}
		}
		return $thestring;
	}

	function makestring() {
		global $ints, $other;
		$what = $this->randnum(2);
		if ($what == 0) {
			return $this->randstring();
		}
		elseif ($what == 1) {
			return $ints[$this->randnum(count($ints) - 1)];
		}
		else {
			return $other[$this->randnum(count($other) - 1)];
		}
	}

    function loop() {
		global $types, $data;
		$string = "";
		$i = $this->randnum(count($types) - 1);
		$t = $types[$i];
		$arr = $data[$t];
		$string .= "<" . $types[$i] . " ";
		for ($z=0; $z<$this->maxparams; $z++) {
			$badparam = $arr[$this->randnum(count($arr) - 1)];
			$badstring = $this->makestring();
			$string .= $badparam . "=" . $badstring . " ";
		}
		$string .= ">\n";
		return $string;
		}

    function main() {
		$page = "";
		for ($k=0; $k<$this->maxtypes; $k++) {
			$page .= $this->loop();
		}
		return $page;
		}
}


////////////////////  SAVING OUTPUT  /////////////////////////


/**
** @desc: Utility function for saving a file. Currently has no error checking.
*/
function saveFile($string, $name) {
	$fp = fopen ( DIRECTORY . "/" . $name, "w");
	fwrite($fp, $string);
	fclose ($fp);
}


//////////////////// MEDIAWIKI PREVIEW /////////////////////////

/*
** @desc: Asks MediaWiki for a preview of a string. Returns the HTML.
*/
function wikiPreview($text) {

	$params = array (
		"action"      => "submit",
		"wpMinoredit" => "1",
		"wpPreview"   => "Show preview",
		"wpSection"   => "new",
		"wpEdittime"  => "",
		"wpSummary"   => "This is a test",
		"wpTextbox1"  => $text
	);

	if( function_exists('curl_init') ) {
		$ch = curl_init();
	} else {
		die("Could not found 'curl_init' function. Is curl extension enabled ?\n");
	}

	curl_setopt($ch, CURLOPT_POST, 1);                    // save form using a POST
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);        // load the POST variables
	curl_setopt($ch, CURLOPT_URL, WIKI_URL);              // set url to post to
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);           // return into a variable

	$result=curl_exec ($ch);

	// if we encountered an error, then log it, and exit.
	if (curl_error($ch)) {
		trigger_error("Curl error #: " . curl_errno($ch) . " - " . curl_error ($ch) );
		print "Curl error #: " . curl_errno($ch) . " - " . curl_error ($ch) . " - exiting.\n";
		exit();
	}

	curl_close ($ch);

	return $result;
}


//////////////////// HTML VALIDATION /////////////////////////

/*
** @desc: Asks the validator whether this is valid HTML, or not.
*/
function validateHTML($text) {

	$params = array ("fragment"   => $text);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_POST, 1);                    // save form using a POST
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);        // load the POST variables
	curl_setopt($ch, CURLOPT_URL, VALIDATOR_URL);         // set url to post to
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);           // return into a variable

	$result=curl_exec ($ch);

	// if we encountered an error, then log it, and exit.
	if (curl_error($ch)) {
		trigger_error("Curl error #: " . curl_errno($ch) . " - " . curl_error ($ch) );
		print "Curl error #: " . curl_errno($ch) . " - " . curl_error ($ch) . " - exiting.\n";
		exit();
	}

	curl_close ($ch);

	$valid = (strpos($result, "Failed validation") === false ? true : false);

	return array($valid, $result);
}



/**
** @desc: checks the string to see if tags are balanced.
*/
function checkOpenCloseTags($string, $filename) {
	$valid = true;

	$lines = explode("\n", $string);

	$num_lines = count($lines);
	// print "Num lines: " . $num_lines . "\n";

	foreach ($lines as $line_num => $line) {

		// skip mediawiki's own unbalanced lines.
		if ($line_num == 15) continue;
		if ($line == "\t\t<style type=\"text/css\">/*<![CDATA[*/") continue;
		if ($line == "<textarea tabindex='1' accesskey=\",\" name=\"wpTextbox1\" id=\"wpTextbox1\" rows='25'") continue;

		if ($line == "/*<![CDATA[*/") continue;
		if ($line == "/*]]>*/") continue;
		if (ereg("^<form id=\"editform\" name=\"editform\" method=\"post\" action=\"", $line)) continue;
		if (ereg("^enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"wikidb_session\" value=\"", $line)) continue; // line num and content changes.
		if ($line == "<textarea tabindex='1' accesskey=\",\" name=\"wpTextbox1\" rows='25'") continue;
		if (ereg("^cols='80'>", $line)) continue; //  line num and content changes.

		if ($num_lines - $line_num == 246) continue;
		if ($num_lines - $line_num == 65) continue;
		if ($num_lines - $line_num == 62) continue;
		if ($num_lines - $line_num == 52) continue;
		if ($num_lines - $line_num == 50) continue;
		if ($num_lines - $line_num == 29) continue;
		if ($num_lines - $line_num == 28) continue;
		if ($num_lines - $line_num == 27) continue;
		if ($num_lines - $line_num == 23) continue;

		if (substr_count($line, "<") > substr_count($line, ">")) {
			print "\nUnclosed tag in " . DIRECTORY . "/" . $filename . " on line: " . ($line_num + 1) . " \n$line\n";
			$valid = false;
		}
	}
	return $valid;
}


/**
** @desc: Get tidy to check for no HTML errors in the output file (e.g. unescaped strings).
*/
function tidyCheckFile($name) {
	$file = DIRECTORY . "/" . $name;
	$x = `tidy -errors -quiet --show-warnings false $file 2>&1`;
	if (trim($x) != "") {
		print "Tidy errors found in $file:\n$x";
		return false;
	} else {
		return true;
	}
}


////////////////////// TESTING FUNCTION ////////////////////////
/**
** @desc: takes a wiki markup string, and tests it for security or validation problems.
*/
function testWikiMarkup($raw_markup, $testname) {

	   // don't overwrite a previous test of the same name.
	   while (file_exists(DIRECTORY . "/" . $testname . ".raw_markup.txt")) {
			   $testname .= "-" . mt_rand(0,9);
	   }

		// upload to MediaWiki install.
		$wiki_preview = wikiPreview($raw_markup);

		// save output files
		saveFile($raw_markup,  $testname . ".raw_markup.txt");
		saveFile($wiki_preview,  $testname . ".wiki_preview.html");

		// validate result
		$valid = true;
		if (VALIDATE_ON_WEB) list ($valid, $validator_output) = validateHTML($wiki_preview);
		$valid = $valid && checkOpenCloseTags ($wiki_preview, $testname . ".wiki_preview.html");
		$valid = $valid && tidyCheckFile( $testname . ".wiki_preview.html" );


		if( $valid ) {
				// Remove valid tests:
				unlink( DIRECTORY . "/" . $testname . ".raw_markup.txt" );
				unlink( DIRECTORY . "/" . $testname . ".wiki_preview.html");
		} elseif( VALIDATE_ON_WEB ) {
				saveFile($validator_output,  $testname . ".validator_output.html");
		}
}


//////////////////////  MAIN LOOP  ////////////////////////

// Make directory if doesn't exist
if (!is_dir(DIRECTORY)) {
	mkdir (DIRECTORY, 0700 );
}
// otherwise, retest the things that we have found in previous runs
else {
	   print "Retesting previously found problems.\n";

	   // create a handler for the directory
	   $handler = opendir(DIRECTORY);

	   // keep going until all files in directory have been read
	   while ($file = readdir($handler)) {

			   // if file is not raw markup, or is a retest, then skip it.
			   if (!ereg("\.raw_markup.txt$", $file)) continue;
				if ( ereg("^retest-", $file)) continue;

				print "Retesting " . DIRECTORY . "/" . $file . "\n";

			   // get file contents
			   $markup = file_get_contents(DIRECTORY . "/" . $file);

			   // run retest
			   testWikiMarkup($markup, "retest-" . $file);
	   }

	   // tidy up: close the handler
	   closedir($handler);

	   print "Done retesting.\n";
}

// seed the random number generator
mt_srand(crc32(microtime()));

// main loop.
$h = new htmler();

print "Beginning main loop. Results are stored in the ".DIRECTORY." directory.\n";
print "Press CTRL+C to stop testing.\n";
for ($count=0; true /*$count<10000 */ ; $count++) { // while (true)
	switch( $count % 4 ) {
		case '0': print "\r/"; break;
		case '1': print "\r-"; break;
		case '2': print "\r\\"; break;
		case '3': print "\r|"; break;
	}
	print " $count";

	// generate and save text to test.
	$raw_markup = $h->main();

	// test this wiki markup
	testWikiMarkup($raw_markup, $count);
}
?>
