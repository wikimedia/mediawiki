<?php
/** Pali (पालि)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Eukesh (on pi.wikipedia.org)
 * @author Hemant wikikosh1
 * @author Vibhijain
 */

$namespaceNames = array(
	NS_MEDIA            => 'मीडिया',
	NS_SPECIAL          => 'विसेस',
	NS_TALK             => 'सम्भासित',
	NS_USER             => 'अवयव',
	NS_USER_TALK        => 'अवयव_सम्भासित',
	NS_PROJECT_TALK     => '$1_सम्भासित',
	NS_FILE             => 'पटिमा',
	NS_FILE_TALK        => 'पटिमा_सम्भासित',
	NS_MEDIAWIKI        => 'मीडियाविकि',
	NS_MEDIAWIKI_TALK   => 'मीडियाविकि_सम्भासित',
	NS_TEMPLATE         => 'पटिरूप',
	NS_TEMPLATE_TALK    => 'पटिरूप_सम्भासित',
	NS_HELP             => 'अवस्सय',
	NS_HELP_TALK        => 'अवस्सय_सम्भासित',
	NS_CATEGORY         => 'विभाग',
	NS_CATEGORY_TALK    => 'विभाग_सम्भासित',
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

$messages = array(
# Dates
'january' => 'ज्यानुवरी',
'february' => 'फ़रवरी',
'march' => 'मार्च',
'april' => 'अप्रैल',
'may_long' => 'मई',
'june' => 'जून',
'july' => 'जुलाई',
'august' => 'अगस्त',
'september' => 'सेप्टेम्बर',
'october' => 'ओक्टोबर',
'november' => 'नवम्बर',
'december' => 'दिसम्बर',

'article' => 'लेख पत्त',

# Vector skin
'vector-view-create' => 'रचेतु',

'help' => 'सहायता',
'search' => 'अन्वेसना',
'searchbutton' => 'खोज',
'searcharticle' => 'गच्छामि',
'create' => 'रचेतु',
'talkpagelinktext' => 'सम्भासनं',
'talk' => 'सम्भासनं',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{SITENAME}} इच्चस्स विसये',
'disclaimers' => 'पच्चाक्खानं',
'mainpage' => 'पमुख पत्त',
'mainpage-description' => 'पमुख पत्त',
'portal' => 'समुदायद्वारं',
'portal-url' => 'Project:समुदायद्वारं',
'privacy' => 'गोपनीयता-नीति',

'editsection' => 'सम्पादेतु',
'editsectionhint' => 'एतं विभागं सम्पादेतु',
'red-link-title' => '$1 (पिट्ठं न वत्तति)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'पिट्ठं',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''पबोधो:''' फलकानं योजनस्स आकारो अतिविसालो वत्तति।
कानिचन फलकानि योजेतुं न सक्कन्ति ।",

# History pages
'currentrev-asof' => 'वत्तमाना आवुत्ति  $1 इति समये',
'previousrevision' => '↓← पुरातनं अवतरणं',

# Search results
'searchmenu-new' => 'अस्मिं विकियं "[[:$1]]" इति पिट्ठं रचेतु।',
'search-result-size' => '$1 ({{PLURAL:$2|1 सद्दो|$2 सद्दा}})',

# Recent changes
'recentchanges' => 'सज्जोजातानि परिवत्तनानि',
'hide' => 'गोपेतु',
'show' => 'दस्सेतु',

# Random page
'randompage' => 'यदिच्छकपिट्ठं',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|बाइटं|बाइटानि}}',

# Book sources
'booksources-go' => 'गच्छामि',

# Special:AllPages
'allarticles' => 'सब्ब लेखा',
'allpagessubmit' => 'गच्छामि',

# Block/unblock
'contribslink' => 'योगदानानि',

# Export
'export' => 'पिट्ठानं निय्यातं करोतु',

# Namespace 8 related
'allmessagesname' => 'नाम',

# Tooltip help for the actions
'tooltip-ca-talk' => 'पिट्ठन्तग्गतविसये सम्भासनं',
'tooltip-ca-move' => 'इदं पिट्ठं चालेतु',
'tooltip-ca-watch' => 'इदं पिट्ठं भवतो अवेक्खणसूचियं योजेतु',
'tooltip-search' => '{{SITENAME}} इच्चेत्थ अन्विस्सतु',
'tooltip-search-fulltext' => 'एतं वचनं पिट्ठेसु अन्विस्सतु',
'tooltip-p-logo' => 'मुखपिट्ठं गच्छतु',
'tooltip-n-recentchanges' => 'सज्जोजातानं परिवत्तनानं सूची',
'tooltip-n-randompage' => 'यदिच्छकं पिट्ठं गच्छतु',
'tooltip-n-help' => 'अन्वेसनठानं',
'tooltip-t-specialpages' => 'सब्बेसं पमुखानं पिट्ठानं सूची',
'tooltip-summary' => 'संखित्तं सारंसं योजेतु',

# EXIF tags
'exif-gpslatitude' => 'अक्षांश',
'exif-gpslongitude' => 'देशान्तर',

'exif-sensingmethod-1' => 'अपरिभाषित',

);
