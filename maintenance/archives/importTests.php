<?php
/**
 * @deprecated
 * @package MediaWiki
 * @subpackage MaintenanceArchive
 */

/** */
print "This script is obsolete!";
print "It is retained in the source here in case some of its
code might be useful for ad-hoc conversion tasks, but it is
not maintained and probably won't even work as is.";
exit();

?><html>
<head>
<title>Unit tests for UseMod-to-PediaWiki import script</title>
<meta http-equiv="Refresh" content="10;URL=importTests.php">
<style>
.pass { color: green }
.fail { color: red }
</style>
</head>
<body>

<?php

# Unit tests for importUseModWiki
# Well, more or less ;)

$testingonly = true;

setlocale( LC_ALL, "C" );

include_once( "importUseModWiki.php" );

$wgRootDirectory = "./testconvert";
runTests();

function runTests() {
	$success =
		testTimestamp()
		&& testRecode()
		&& testFreeToNormal()
		&& testTransformTitle()
		&& testMediaLinks()
		&& testRemoveTalkLink()
		&& testSubPages()
		;
	if( $success ) {
		echo "\n<h1 class='pass'>** Passed all tests! **</h1>\n";
	} else {
		echo "\n<h1 class='fail'>-- FAILED ONE OR MORE TESTS --</h1>\n";
	}
	return $success;
}

function passTest( $testname, $note = "" ) {
	if( $notes != "" ) $notes = " -- $notes";
	echo "<span class='pass'>.. passed test $testname $notes</span><br />\n";
	return true;
}

function failTest( $testname, $notes = "" ) {
	if ( $notes != "" ) $notes = " -- $notes";
	echo "<span class='fail'>** FAILED TEST $testname **$notes</span><br />\n";
	return false;
}

function testTimestamp() {
	$tn = "Timestamp";
	$x = wfUnix2Timestamp( 0 );
	if( $x != "19700101000000" ) {
		return failTest( $tn, "wfUnix2Timestamp for epoch returned unexpected $x" );
	}
	
	$x = wfTimestamp2Unix( "19700101000000" );
	if( $x != 0 ) {
		return failTest( $tn, "wfTimestamp2Unix for epoch returned unexpected $x" );
	}
	
	return passTest( $tn );
}

function testRecode() {
	$tn = "Recode";
	
	# These functions are dummies for now
	$a = "abcd";
	$x = recodeInput( $a );
	if( $a != $x ) return failTest( $tn, "eo test returned different value" );

	$a = "ĉeĥa ŝaŭmmanĝaĵo";
	$x = recodeInput( $a );
	if( $a != $x ) return failTest( $tn, "eo test returned different value" );
	
	return passTest( $tn );
}

function testFreeToNormal() {
	$tn = "FreeToNormal";
	$a = "WikiName"; $x = FreeToNormal( $a );
	if( $a != $x ) return failTest( $tn, "$a -> $a != $x" );
	
	$a = "With_Underscore"; $x = FreeToNormal( $a );
	if( $a != $x ) return failTest( $tn, "$a -> $a != $x" );
	
	$a = "With Space"; $x = FreeToNormal( $a );
	if( "With_Space" != $x ) return failTest( $tn, "$a -> With_Space != $x" );
	
	$a = "Mixed case"; $x = FreeToNormal( $a );
	if( "Mixed_Case" != $x ) return failTest( $tn, "$a -> Mixed_Case != $x" );
	
	$a = "\xe9cole"; $x = FreeToNormal( $a );
	if( $a != $x ) return failTest( $tn, "$a -> $a != $x (must replicate high caps bug)" );
	
	return passTest( $tn );
}

function testTransformTitle() {
	global $talkending;
	$oldtalkending = $talkending;
	$tn = "TransformTitle";
	
	$a = "WikiName"; $x = transformTitle( $a );
	if( $x->namespace != 0 or $x->title != "WikiName" ) return failTest( $tn, "$a -> 0, WikiName instead -> $x->namespace , $x->title" );
	
	$talkending = "Talk";
	$a = "WikiName/Talk"; $x = transformTitle( $a );
	if( $x->namespace != 1 or $x->title != "WikiName" ) return failTest( $tn, "$a -> 1, WikiName instead -> $x->namespace , $x->title" );

	$a = "WikiName/talk"; $x = transformTitle( $a );
	if( $x->namespace != 1 or $x->title != "WikiName" ) return failTest( $tn, "$a -> 1, WikiName instead -> $x->namespace , $x->title" );
	
	$talkending = "Diskuto";
	$a = "WikiName/Diskuto"; $x = transformTitle( $a );
	if( $x->namespace != 1 or $x->title != "WikiName" ) return failTest( $tn, "$a -> 1, WikiName instead -> $x->namespace , $x->title" );

	$talkending = $oldtalkending;
	return passTest( $tn );
}

function testMediaLinks() {
	$tn = "MediaLinks";

	# Fetch
	$a = "magic.gif";
	$x = fetchMediaFile( "???", "magic.gif" );
	

	# Media links
	$a = "[http://www.wikipedia.org/upload/magic.gif]";
	$b = "[[Media:Magic.gif]]"; # Or should it?
	$x = fixMediaLinks( $a );
	if( $x != $b ) return failTest( $tn, "$a should be $b, is $x" );
	
	$a = "[http://www.wikipedia.org/upload/magic.gif Click image]";
	$b = "[[Media:Magic.gif|Click image]]";
	$x = fixMediaLinks( $a );
	if( $x != $b ) return failTest( $tn, "$a should be $b, is $x" );

	# Image links:
	$a = "http://www.wikipedia.org/upload/magic.gif";
	$b = "[[Image:Magic.gif]]";
	$x = fixImageLinks( $a );
	if( $x != $b ) return failTest( $tn, "$a should be $b, is $x" );

	$a = "http://www.wikipedia.org/upload/a/a4/magic.gif";
	$b = "[[Image:Magic.gif]]";
	$x = fixImageLinks( $a );
	if( $x != $b ) return failTest( $tn, "$a should be $b, is $x" );

	return passTest( $tn );
}

function testRemoveTalkLink() {
	global $talkending;
	$tn = "RemoveTalkLink";
	$oldtalkending = $talkending;
	$talkending = "Talk";
	
	$a = "Blah blah blah blah\nFoo bar baz.\n/Talk";
	$b = "Blah blah blah blah\nFoo bar baz.";
	$x = removeTalkLink( $a );
	if( $x != $b ) return failTest( $tn, "removing talk link: '$a' -> '$x', should be '$b'" );
	
	$a = "Blah blah blah blah\nFoo bar baz.\n[[/Talk]]";
	$b = "Blah blah blah blah\nFoo bar baz.";
	$x = removeTalkLink( $a );
	if( $x != $b ) return failTest( $tn, "removing talk link: '$a' -> '$x', should be '$b'" );

	$a = "Blah blah blah blah\nFoo bar baz.\n/talk"; # wait... should this not work?
	$b = "Blah blah blah blah\nFoo bar baz.";
	$x = removeTalkLink( $a );
	if( $x != $b ) return failTest( $tn, "removing talk link: '$a' -> '$x', should be '$b'" );

	$talkending = "Priparolu";
	$a = "Blah blah blah blah\nFoo bar baz.\n/Priparolu";
	$b = "Blah blah blah blah\nFoo bar baz.";
	$x = removeTalkLink( $a );
	if( $x != $b ) return failTest( $tn, "removing talk link: '$a' -> '$x', should be '$b'" );
	
	$talkending = $oldtalkending;
	return passTest( $tn );
}

function testSubPages() {
	$tn = "SubPages";
	
	$t = "TopPage";
	$a = "Blah /Subpage blah";
	$b = "Blah [[TopPage/Subpage|/Subpage]] blah";
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );
	
	$a = "Blah /subpage blah";
	$b = $a;
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );
	
	$a = "Blah [[/Subpage]] blah";
	$b = "Blah [[TopPage/Subpage|/Subpage]] blah";
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );
	
	$a = "Blah [[/subpage]] blah";
	$b = "Blah [[TopPage/Subpage|/subpage]] blah";
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );
	
	$a = "Blah [[/Subpage|Fizzle]] blah";
	$b = "Blah [[TopPage/Subpage|Fizzle]] blah";
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );

	$a = "Blah [[/subpage|Fizzle]] blah";
	$b = "Blah [[TopPage/Subpage|Fizzle]] blah";
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );
	
	$a = "Blah /\xc9cole blah";
	$b = "Blah [[TopPage/\xc9cole|/\xc9cole]] blah";
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );
	
	$a = "Blah /\xe9cole blah";
	$b = $a;
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );
	
	$a = "Blah [[/\xc9cole]] blah";
	$b = "Blah [[TopPage/\xc9cole|/\xc9cole]] blah";
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );
	
	$a = "Blah [[/\xe9cole]] blah";
	$b = "Blah [[TopPage/\xe9cole|/\xe9cole]] blah";
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );
	
	$a = "Blah [[/xe9cole|Fizzle]] blah";
	$b = "Blah [[TopPage/\xe9cole|Fizzle]] blah";
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );

	$a = "Blah [[/subpage|Fizzle]] blah";
	$b = "Blah [[TopPage/\xe9cole|Fizzle]] blah";
	$x = fixSubPages( $a, $t );
	if ( $x != $b ) return failTest( "'$a' -> '$x', should be '$b'" );
	return passTest( $tn );
}

?>
</body>
</html>
