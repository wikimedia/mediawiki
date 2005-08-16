<?php
/**
 * Convert Language files to .po files !
 *
 * Todo:
 *   - generate .po header
 *   - fix escaping of \
 */

/** This is a command line script */
require_once('commandLine.inc');
require_once('languages.inc');

define('ALL_LANGUAGES',    true);
define('XGETTEXT_BIN',     'xgettext');
define('XGETTEXT_OPTIONS', '-n --keyword=wfMsg');

define('LOCALE_OUTPUT_DIR', $IP.'/locale');


if( isset($options['help']) ) { usage(); die(); }
// default output is WikiText
if( !isset($options['lang']) ) { $options['lang'] = ALL_LANGUAGES; }

function usage() {
print <<<END
Usage: php lang2po.php [--help] [--lang=<langcode>] [--stdout]
  --help: this message.
  --lang: a lang code you want to generate a .po for (default: all languages).

END;
}


/**
 * generate and write a file in .po format.
 *
 * @param string $langcode Code of a language it will process.
 * @param array &$messages Array containing the various messages.
 * @return string Filename where stuff got saved or false.
 */
function generatePo($langcode, &$messages) {
	$data = ''; // TODO a .po header ?

	// Generate .po entries
	foreach($messages as $identifier => $content) {
		$data .= "msgid \"$identifier\"\n";

		// Double quotes escaped for $content:
		$tmp = 'msgstr "'.str_replace('"', '\"', $content)."\"";
		// New line should end with "\n not \n
		$data .= str_replace("\n", "\"\n\"", $tmp);
		$data .= "\n\n";
	}

	// Write the content to a file in locale/XX/messages.po
	$dir = LOCALE_OUTPUT_DIR.'/'.$langcode;
	if( !is_dir($dir) ) { mkdir( $dir, 0770 ); }
	$filename = $dir.'/fromlanguagefile.po';

	$file = fopen( $filename , 'wb' );
	if( fwrite( $file, $data ) ) {
		fclose( $file );
		return $filename;
	} else {
		fclose( $file );
		return false;
	}
}

$langTool = new languages();

// Do all languages
foreach ( $langTool->getList() as $langcode) {
	echo "Loading messages for $langcode:\t";
	require_once( 'languages/Language' . $langcode . '.php' );
	$arr = 'wgAllMessages'.$langcode;
	if(!@is_array($$arr)) {
		echo "NONE FOUND\n";
	} else {
		echo "ok\n";
		if( ! generatePo($langcode, $$arr) ) {
			echo "ERROR: Failed to wrote file.\n";
		}
	}
}

// Generate a default .po based source tree
echo "Getting 'gettext' default messages from sources\n";
exec( XGETTEXT_BIN
  .' '.XGETTEXT_OPTIONS
  .' -o '.$IP.'/locale/wfMsg.po'
  .' '.$IP.'/includes/*php'
  );


?>
