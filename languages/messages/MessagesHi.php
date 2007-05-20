<?php

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'विशेष',
	NS_MAIN           => '',
	NS_TALK           => 'वार्ता',
	NS_USER           => 'सदस्य',
	NS_USER_TALK      => 'सदस्य_वार्ता',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_वार्ता',
	NS_IMAGE          => 'चित्र',
	NS_IMAGE_TALK     => 'चित्र_वार्ता',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_talk',
	NS_TEMPLATE       => 'साँचा',
	NS_TEMPLATE_TALK  => 'साँचा_वार्ता',
	NS_CATEGORY       => 'श्रेणी',
	NS_CATEGORY_TALK  => 'श्रेणी_वार्ता',
);

$digitTransformTable = array(
	'0' => '०', # &#x0966;
	'1' => '१', # &#x0967;
	'2' => '२', # &#x0968;
	'3' => '३', # &#x0969;
	'4' => '४', # &#x096a;
	'5' => '५', # &#x096b;
	'6' => '६', # &#x096c;
	'7' => '७', # &#x096d;
	'8' => '८', # &#x096e;
	'9' => '९', # &#x096f;
);
$linkTrail = "/^([a-z]+)(.*)\$/sD";


$messages = array(
# Dates
'sunday'    => 'रविवार',
'monday'    => 'सोमवार',
'tuesday'   => 'मंगलवार',
'wednesday' => 'बुधवार',
'thursday'  => 'गुरुवार',
'friday'    => 'शुक्रवार',
'saturday'  => 'शनिवार',
'january'   => 'जनवरी',
'february'  => 'फरवरी',
'march'     => 'मार्च',
'april'     => 'अप्रैल',
'may_long'  => 'मई',
'june'      => 'जून',
'july'      => 'जुलाई',
'august'    => 'अगस्त',
'september' => 'सितम्बर',
'october'   => 'अक्टूबर',
'november'  => 'नवम्बर',
'december'  => 'दिसम्बर',
'jan'       => 'जनवरी',
'feb'       => 'फरवरी',
'mar'       => 'मार्च',
'apr'       => 'अप्रैल',
'may'       => 'मई',
'jun'       => 'जून',
'jul'       => 'जुलाई',
'aug'       => 'अगस्त',
'sep'       => 'सितम्बर',
'oct'       => 'अक्टूबर',
'nov'       => 'नवम्बर',
'dec'       => 'दिसम्बर',

'about'  => 'अबाउट',
'mypage' => 'मेरा पृष्ठ',
'mytalk' => 'मेरी बातें',

'returnto'          => 'लौटें $1.',
'help'              => 'सहायता',
'search'            => 'खोज',
'searchbutton'      => 'खोज',
'go'                => 'जायें',
'searcharticle'     => 'जायें',
'editthispage'      => 'इस पृष्ठ को बदलें',
'deletethispage'    => 'इस पृष्ठ को हटायें',
'protectthispage'   => 'इस पृष्ठ को सुरक्षित करें',
'unprotectthispage' => 'इस पृष्ठ को असुरक्षित करें',
'newpage'           => 'नया पृष्ठ',
'talkpage'          => 'इस पृष्ठ के बारे में बात करें',
'articlepage'       => 'लेख देखें',
'userpage'          => 'सदस्य पृष्ठ देखें',
'projectpage'       => 'मेटा पृष्ठ देखें',
'imagepage'         => 'चित्र पृष्ठ देखें',
'viewtalkpage'      => 'चर्चा देखें',
'otherlanguages'    => 'अन्य भाषायें',
'redirectedfrom'    => '($1 से भेजा गया)',
'lastmodifiedat'    => 'अन्तिम परिवर्तन $2, $1.', # $1 date, $2 time
'viewcount'         => 'यह पृष्ठ $1 बार देखा गया है',
'protectedpage'     => 'सुरक्षित पृष्ठ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{SITENAME}} के बारे में',
'aboutpage' => '{{ns:project}}:अबाउट',
'helppage'  => '{{ns:project}}:सहायता',
'mainpage'  => 'मुख्य पृष्ठ',

'retrievedfrom' => '"$1" से लिया गया',

# Main script and global functions
'nosuchaction'      => 'ऐसा कोई कार्य नहीं है',
'nosuchactiontext'  => '{{SITENAME}} सौफ़्टवेयर में इस URL द्वारा निर्धारित कोई क्रिया नही है',
'nosuchspecialpage' => 'ऐसा कोई विशेष पृष्ठ नहीं है',
'nospecialpagetext' => 'आपने ऐसा विशेष पृष्ठ मांगा है जो {{SITENAME}} सौफ़्टवेयर में नहीं है.',

# Login and logout pages
'welcomecreation'   => "<h2>स्वागतम्‌, $1!</h2><p>आपका अकाउन्ट बना दिया गया है.
Don't forget to personalize your {{SITENAME}} preferences.",
'yourname'          => 'आपका नाम',
'yourpassword'      => 'आपका पासवर्ड',
'yourpasswordagain' => 'पासवर्ड दुबारा लिखें',

);

?>
