<?php

require_once( "LanguageUtf8.php" );

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesHi = array(
	-2	=> "Media",
	-1	=> "विशेष",
	0	=> "",
	1	=> "वार्ता",
	2	=> "सदस्य",
	3	=> "सदस्य_वार्ता",
	4	=> "विकिपीडिया",
	5	=> "विकिपीडिआ_वार्ता",
	6	=> "चित्र",
	7	=> "चित्र_वार्ता",
	8	=> "MediaWiki",
	9	=> "MediaWiki_talk",
	10  => "Template",
	11  => "Template_talk"

) + $wgNamespaceNamesEn;

/* private */ $wgWeekdayNamesHi = array(
	"रविवार", "सोमवार", "मंगलवार", "बुधवार", "गुरुवार",
	"शुक्रवार", "शनिवार"
);

/* private */ $wgMonthNamesHi = array(
	"जनवरी", "फरवरी", "मार्च", "अप्रैल", "मई", "जून",
	"जुलाई", "अगस्त", "सितम्बर", "अक्टूबर", "नवम्बर",
	"दिसम्बर"
);

/* private */ $wgAllMessagesHi = array(

# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "मुख्य पृष्ठ",
"about"			=> "अबाउट",
"aboutwikipedia"        => "विकिपीडिया के बारे में",
"aboutpage"		=> "विकिपीडिया:अबाउट",
"help"			=> "सहायता",
"helppage"		=> "विकिपीडिया:सहायता",
"wikititlesuffix"       => "विकिपीडिया",
"bugreports"	        => "Bug reports",
"bugreportspage"        => "विकिपीडिया:Bug_reports",
"faq"			=> "FAQ",
"faqpage"		=> "विकिपीडिया:FAQ",
"edithelp"		=> "Editing help",
"edithelppage"	        => "विकिपीडिया:How_does_one_edit_a_page",
"cancel"		=> "Cancel",
"qbfind"		=> "Find",
"qbbrowse"		=> "Browse",
"qbedit"		=> "Edit",
"qbpageoptions"         => "Page options",
"qbpageinfo"	        => "Page info",
"qbmyoptions"	        => "My options",
"mypage"		=> "मेरा पृष्ठ",
"mytalk"		=> "मेरी बातें",
"currentevents"         => "Current events",
"errorpagetitle"        => "Error",
"returnto"		=> "लौटें $1.",
"fromwikipedia"	        => "From Wikipedia, the free encyclopedia.",
"whatlinkshere"	        => "Pages that link here",
"help"			=> "सहायता ",
"search"		=> "खोज ",
"go"		        => "जायें",
"history"		=> "Older versions",
"printableversion"      => "Printable version",
"editthispage"	        => "इस पृष्ठ को बदलें",
"deletethispage"        => "इस पृष्ठ को हटायें",
"protectthispage"       => "इस पृष्ठ को सुरक्षित करें",
"unprotectthispage"     => "इस पृष्ठ को असुरक्षित करें",
"newpage"               => "नया पृष्ठ ",
"talkpage"		=> "इस पृष्ठ के बारे में बात करें",
"articlepage"	        => "लेख देखें",
"subjectpage"	        => "विषय देखें", # For compatibility
"userpage"              => "सदस्य पृष्ठ देखें",
"wikipediapage"         => "मेटा पृष्ठ देखें",
"imagepage"             => "चित्र पृष्ठ देखें",
"viewtalkpage"          => "चर्चा देखें",
"otherlanguages"        => "अन्य भाषायें",
"redirectedfrom"        => "($1 से भेजा गया)",
"lastmodified"	        => "अन्तिम परिवर्तन $1.",
"viewcount"		=> "यह पृष्ठ $1 बार देखा गया है",
"gnunote" => "All text is available under the terms of the <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle"         => "(From http://www.wikipedia.org)",
"protectedpage"         => "सुरक्षित पृष्ठ",
"administrators"        => "विकिपीडिया:प्रबन्धक",
"sysoptitle"	        => "sysop आवश्यक है",
"sysoptext"		=> "आप जो करना चाहते हैं‌ उसे केवल \"sysop\" स्तर के सदस्य कर सकते हैं. $1 देखें.",
"developertitle"        => "Developer आवश्यक है",
"developertext"	=> "आप जो करना चाहते हैं‌ उसे केवल \"developer\" स्तर के सदस्य कर सकते हैं. $1 देखें.",
"nbytes"		=> "$1 bytes",
"go"			=> "Go",
"ok"			=> "OK",
"sitetitle"		=> "विकिपीडिया ",
"sitesubtitle"	        => "निःशुल्क ज्ञान संग्रह ",
"retrievedfrom"         => "\"$1\" से लिया गया",
"newmessages"           => "आपके लिये $1 हैं.",
"newmessageslink"       => "नये सन्देश",

# Main script and global functions
#
"nosuchaction"	=> "ऐसा कोई कार्य नहीं है",
"nosuchactiontext" => "विकिपीडिया सौफ़्टवेयर में इस URL द्वारा निर्धारित कोई क्रिया नही है",
"nosuchspecialpage" => "ऐसा कोई विशेष पृष्ठ नहीं है",
"nospecialpagetext" => "आपने ऐसा विशेष पृष्ठ मांगा है जो विकिपीडिया सौफ़्टवेयर में नहीं है.",

# General errors
# ........

"welcomecreation" => "<h2>स्वागतम्‌, $1!</h2><p>आपका अकाउन्ट बना दिया गया है.
Don't forget to personalize your wikipedia preferences.",

"loginpagetitle" => "User login",
"yourname"		=> "आपका नाम",
"yourpassword"	=> "आपका पासवर्ड ",
"yourpasswordagain" => "पासवर्ड दुबारा लिखें",

## ....... more messages .....
);

class LanguageHi extends LanguageUtf8 {
	var $digitTransTable = array(
		"0" => "०",
		"1" => "१",
		"2" => "२",
		"3" => "३",
		"4" => "४",
		"5" => "५",
		"6" => "६",
		"7" => "७",
		"8" => "८",
		"9" => "९"
	);

	function getNamespaces() {
		global $wgNamespaceNamesHi;
		return $wgNamespaceNamesHi;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesHi;
		return $wgNamespaceNamesHi[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesHi, $wgNamespaceNamesEn;

		foreach ( $wgNamespaceNamesHi as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		# fallback
		foreach ( $wgNamespaceNamesEn as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesHi;
		return $wgMonthNamesHi[$key-1];
	}
	
	function getMessage( $key )
	{
		global $wgAllMessagesHi;
		if(array_key_exists($key, $wgAllMessagesHi))
			return $wgAllMessagesHi[$key];
		else
			return Language::getMessage($key);
	}

	function formatNum( $number ) {
		global $wgTranslateNumerals;
		if( $wgTranslateNumerals ) {
			return strtr( $number, $this->digitTransTable );
		} else {
			return $number;
		}
	}
}

?>
