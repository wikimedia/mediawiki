<?php
/**
 * @package MediaWiki
 * @subpackage Maintenance
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Ashar Voultoiz <thoane@altern.org>
 * @bug 2499
 *
 * Output is posted from time to time on:
 * http://meta.wikimedia.org/wiki/Localization_statistics
 */

/** */
require_once('commandLine.inc');
require_once('languages.inc');

if( isset($options['help']) ) { usage(); wfDie(); }
// default output is WikiText
if( !isset($options['output']) ) { $options['output']='wiki'; }


/** Print a usage message*/
function usage() {
print <<<END
Usage: php transstat.php [--help] [--output:csv|text|wiki] [--showdupes]
         --help : this helpful message
      --showold : show old messages that are not in Messages.php
       --output : select an output engine one of:
                    * 'csv'  : Comma Separated Values.
                    * 'none' : Nothing, usefull with --showdupes
                    * 'wiki' : MediaWiki syntax (default).
                    * 'text' : Text with tabs.
Example: php transstat.php --showdupes --output=none


END;
}


/** A general output object. Need to be overriden */
class statsOutput {
	var $output; // buffer that contain the text
	function statsOutput() { $this->output='';}
	function getContent() { return $this->output;}

	function formatPercent($subset, $total, $revert=false, $accuracy=2) {
		return @sprintf( '%.' . $accuracy . 'f%%', 100 * $subset / $total );
	}

	// Override the next methods
	function heading() {}
	function footer() {}
	function blockstart() {}
	function blockend() {}
	function element($in, $heading=false) {}
}

/** Outputs nothing ! */
class noneStatsOutput extends statsOutput {
	function getContent() { return NULL;}
}

/** Outputs WikiText */
class wikiStatsOutput extends statsOutput {
	function heading() {
		$this->output .= "{| border=2 cellpadding=4 cellspacing=0 style=\"background: #f9f9f9; border: 1px #aaa solid; border-collapse: collapse;\" width=100%\n";
	}
	function footer()     { $this->output .= "|}\n"; }
	function blockstart() { $this->output .= "|-\n"; }
	function blockend()   { $this->output .= ''; }
	function element($in, $heading = false) {
		$this->output .= ($heading ? '!' : '|') . " $in\n";
	}
	function formatPercent($subset, $total, $revert=false, $accuracy=2) {
		$v = @round(255 * $subset / $total);
		if($revert) $v = 255 - $v;
		if($v < 128) {
			// red to yellow
			$red = 'FF';
			$green = sprintf('%02X', 2*$v);
		} else {
			// yellow to green
			$red   = sprintf('%02X', 2*(255 -$v) );
			$green = 'FF';
		}
		$blue  = '00';
		$color = $red.$green.$blue;

		$percent = statsOutput::formatPercent($subset, $total, $revert, $accuracy);
		return 'bgcolor="#'.$color.'" | '.$percent;
	}
}

/** Output text. To be used on a terminal for example. */
class textStatsOutput extends statsOutput {
	function element($in, $heading = false) {
		$this->output .= $in."\t";
	}
	function blockend(){ $this->output .="\n";}
}

/** csv output. Some people love excel */
class csvStatsOutput extends statsOutput {
	function element($in, $heading = false) {
		$this->output .= $in.";";
	}
	function blockend(){ $this->output .="\n";}
}


function redundant(&$arr, $langcode) {
	global $wgAllMessagesEn;

	$redundant = 0;
	foreach(array_keys($arr) as $key) {
		if ( @$wgAllMessagesEn[$key] === null ) {
			global $options;
			if( isset($options['showold']) ) {
				print "Deprecated [$langcode]: $key\n";
			}
			++$redundant;
		}
	}
	return $redundant;
}

// Select an output engine
switch ($options['output']) {
	case 'csv':
		$out = new csvStatsOutput(); break;
	case 'none':
		$out = new noneStatsOutput(); break;
	case 'text':
		$out = new textStatsOutput(); break;
	case 'wiki':
		$out = new wikiStatsOutput(); break;
	default:
		usage(); wfDie();
	break;
}

$langTool = new languages();

//  Load message and compute stuff
$msgs = array();
foreach($langTool->getList() as $langcode) {
	// Since they aren't loaded by default..
	require_once( 'languages/Language' . $langcode . '.php' );
	$arr = 'wgAllMessages'.$langcode;
	if(@is_array($$arr)) {
		$msgs[$wgContLang->lcfirst($langcode)] = array(
			'total' => count($$arr),
			'redundant' => redundant($$arr, $langcode),
		);
	} else {
		$msgs[$wgContLang->lcfirst($langcode)] = array(
			'total' => 0,
			'redundant' => 0,
		);
	}
}

// Top entry
$out->heading();
$out->blockstart();
$out->element('Language', true);
$out->element('Translated', true);
$out->element('%', true);
$out->element('Untranslated', true);
$out->element('%', true);
$out->element('Redundant', true);
$out->element('%', true);
$out->blockend();

// Generate rows
foreach($msgs as $lang => $stats) {
	$out->blockstart();
	// Language
	$out->element($wgContLang->getLanguageName(strtr($lang, '_', '-')) . " ($lang)");
	// Translated
	$out->element($stats['total'] . '/' . $msgs['en']['total']);
	// % Translated
	$out->element($out->formatPercent($stats['total'], $msgs['en']['total']));
	// Untranslated
	$out->element($msgs['en']['total'] - $stats['total']);
	// % Untranslated
	$out->element($out->formatPercent($msgs['en']['total'] - $stats['total'], $msgs['en']['total'], true));
	// Redundant & % Redundant
	if($stats['redundant'] =='NC') {
		$out->element('NC');
		$out->element('NC');
	} else {
		$out->element($stats['redundant'] . '/' . $stats['total']);
		$out->element($out->formatPercent($stats['redundant'],  $stats['total'],true));
	}
	$out->blockend();
}
$out->footer();

// Final output
echo $out->getContent();
?>
