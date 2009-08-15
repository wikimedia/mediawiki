<?php
/** Nepali (नेपाली)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author ne.wikipedia.org sysops
 * @author सरोज कुमार ढकाल
 */

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
# User preference toggles
'tog-underline'               => 'सम्बन्ध निम्न रेखाङ्ककन:',
'tog-highlightbroken'         => 'ढाँचा टुटेको लिङ्क <a href="" class="new">यस्तो </a> (alternative: यस्तै<a href="" class="internal">?</a>)',
'tog-justify'                 => 'अनुच्छेद जस्टिफाइ(justify) गर्ने',
'tog-hideminor'               => 'तत्कालका सामान्य सम्पादनहरुलाई लुकाउनुहोस',
'tog-hidepatrolled'           => 'गस्ती(patrolled)सम्पादनहरु हालको परिवर्तनहरुबाट लुकाउने',
'tog-newpageshidepatrolled'   => 'गस्ती(patrolled) पृष्ठहरु नयाँ पृष्ठ सूचीबाट लुकाउने',
'tog-extendwatchlist'         => 'निगरानी सूचीलाई सबै परिवर्ताहरू देखाउने गरी बढाउने , हालैको  बाहेक',
'tog-usenewrc'                => 'विकसित हालको परिवर्तन प्रयोग गर्ने ( जाभास्क्रिप्ट चाहिन्छ)',
'tog-numberheadings'          => 'शिर्षकहरुलाई स्वत:अङ्कित गर्नुहोस्',
'tog-showtoolbar'             => 'सम्पादन औजारबट्टा देखाउने( जाभा स्क्रिप्ट चाहिन्छ)',
'tog-editondblclick'          => 'दोहोरो क्लिकमा पृष्ठ सम्पादन गर्ने (जाभा स्क्रिप्ट चाहिन्छ)',
'tog-editsection'             => '[परिवर्तन्]सम्बन्ध मार्फत हुने खण्ड सम्पादनलाई सक्षमपार्ने',
'tog-editsectiononrightclick' => 'शीर्षकमा दाहिने क्लिकद्वारा खण्ड सम्पादन सक्षम पार्ने ( जाभा स्क्रिप्ट चाहिने )',
'tog-showtoc'                 => 'विषय सुची देखाउने (तीन भन्दा बढी शीर्षक भएमा)',
'tog-rememberpassword'        => 'मेरो यस कम्प्युटर प्रवेशलाई सम्झनुहोस्',
'tog-editwidth'               => 'सम्पादन सन्दुकलाई पूरै पर्दा ढाक्नेगरी बढाउने',
'tog-watchcreations'          => 'मेरो निगरानीसूचिमा मैले शृजना गरेको पृष्ठ थप्नुहोस्',
'tog-watchdefault'            => 'मैले सम्पादन गरेको पृष्ठ निगरानीसूचीमा थप्ने',
'tog-watchmoves'              => 'मैले सारेको पृष्ठहरुलाई निगरानीसूचीमा थप्ने',
'tog-watchdeletion'           => 'मैले हटाएको पृष्ठहरुलाई निगरानी सुचीमा थप्ने',
'tog-minordefault'            => 'सबै सम्पादनहरुलाई पूर्वनिर्धारितरुपमा सामान्य चिनो लगाउने',
'tog-previewontop'            => 'सम्पादन सन्दुक अगि पूर्वरुप देखाउने',
'tog-previewonfirst'          => 'पहिलो सम्पादनमा पूर्वरुप देखाउने',
'tog-nocache'                 => 'पृष्ठ क्यासिङ्ग निस्क्रिय पार्ने',
'tog-enotifwatchlistpages'    => 'मेरो निगरानी सूचीमा रहेको पृष्ठ परिवर्तन गरिए मलाई ई-मेल गर्ने',
'tog-enotifusertalkpages'     => 'मेरो प्रयोगकर्ता वार्ता पृष्ठ परिवर्रतन गरिए मलाई ई-मेल गर्ने',
'tog-enotifminoredits'        => 'पृष्ठहरुको सामान्य सम्पादनको लागि पनि मलाई ई-मेल गर्ने',
'tog-enotifrevealaddr'        => 'जानकारी इ-मेलहरुमा मेरो इ-मेल खुलाउने',
'tog-shownumberswatching'     => 'निगरानी गरिरहेका प्रयोगकर्ताहरुको संख्या देखाउने',
'tog-oldsig'                  => 'हालको दस्तखतको पूर्वारूप :',
'tog-fancysig'                => 'मेरो दस्तखतलाई विकि पाठको रुपमा लिने(स्वत सम्वन्ध बिना)',
'tog-externaleditor'          => 'पूर्वनिर्धारित रुपमा बाह्य सम्पादक प्रयोग गर्नुहोस्(विज्ञहरुको लागि मात्र,तपाईको कम्प्युटरमा विशेष अनुकुलता आवश्यक हुन्छ )',
'tog-externaldiff'            => 'पूर्वनिर्धारित रुपमा बाह्य diff प्रयोग गर्नुहोस (विज्ञ प्रयोगकर्ताहरुको लागि मात्र, तपाईंको कम्प्युटरमा विशेष अनुकुलता आवश्यक हुन्छ)',
'tog-showjumplinks'           => '"जानुहोस" पहुँच सम्वन्ध सक्रिय पर्नुहोस्',
'tog-uselivepreview'          => 'प्रत्यक्ष पूर्वरुप प्रयोग गर्नुहोस् ( जाभा स्क्रिप्ट आवश्यक) (प्रयोगात्मक)',
'tog-forceeditsummary'        => 'खाली सम्पादन सार प्रविष्ठी गरेमा मलाई सोध्ने',
'tog-watchlisthideown'        => 'मेरा सम्पादनहरू निगनारी सूचीवबाट लुकाउने',
'tog-watchlisthidebots'       => 'बोट सम्पादनहरू निगरानी सूचीबाट लुकाउने',
'tog-watchlisthideminor'      => 'सामान्य सम्पादनहरू निगरानी सूचीबाट लुकाउने',
'tog-watchlisthideliu'        => 'प्रवेश गरेका प्रयोगकर्ताहरुको सम्पादन निगरानी सूचीबाट लुकाउने',
'tog-watchlisthideanons'      => 'अज्ञात प्रयोगकर्ताहरुबाट गरिएको सम्पादन निगरानी सूचीबाट लुकाउने',
'tog-watchlisthidepatrolled'  => 'गस्ती(पट्रोल)सम्पादनहरु मेरो निगरानी सूचीबाट लुकाउने',
'tog-nolangconversion'        => 'बहुरुप रुपान्तरण निस्क्रिय पार्नुहोस',
'tog-ccmeonemails'            => 'मैले अरु प्रयोगकर्ताहरुलाई पठाउने इ-मेल को प्रतिलिपी मलाई पठाउने',
'tog-diffonly'                => 'तलका पृष्टहरुको diffहरु सामग्री नदेखाउने',
'tog-showhiddencats'          => 'लुकाइएको प्रकारहरु देखाउने',
'tog-noconvertlink'           => 'सम्बन्ध शीर्षक रुपान्तरण निस्क्रिय पार्ने',
'tog-norollbackdiff'          => 'पूर्वस्थितीमा फर्काएपछि  diff हटाउने',

'underline-always'  => 'सधैँ',
'underline-never'   => 'कहिल्यै',
'underline-default' => 'ब्राउजर पूर्वस्थिती',

# Font style option in Special:Preferences
'editfont-style'     => 'फन्ट प्रकार क्षेत्र सम्पादन गर्नुहोस् :',
'editfont-default'   => 'ब्राउजर पूर्वस्थिती',
'editfont-monospace' => 'मोनोस्पेस्ड फन्ट',
'editfont-sansserif' => 'सान्स-सेरिफ फन्ट',
'editfont-serif'     => 'सेरिफ फन्ट',

# Dates
'sunday'        => 'आईतबार',
'monday'        => 'सोमबार',
'tuesday'       => 'मंगलबार',
'wednesday'     => 'बुधबार',
'thursday'      => 'बिहीबार',
'friday'        => 'शुक्रबार',
'saturday'      => 'शनिबार',
'sun'           => 'आईत',
'mon'           => 'सोम',
'tue'           => 'मंगल',
'wed'           => 'वुध',
'thu'           => 'बिही',
'fri'           => 'शुक्र',
'sat'           => 'शनि',
'january'       => 'जेनवरी',
'february'      => 'फेब्रुअरी',
'march'         => 'मार्च',
'april'         => 'एप्रील',
'may_long'      => 'मे',
'june'          => 'जुन',
'july'          => 'जुलाई',
'august'        => 'अगस्त',
'september'     => 'सेप्टेम्बर',
'october'       => 'अक्टोबर',
'november'      => 'नोभेम्बर',
'december'      => 'डिसेम्बर',
'january-gen'   => 'जेनवरी',
'february-gen'  => 'फेब्रुअरी',
'march-gen'     => 'मार्च',
'april-gen'     => 'एप्रील',
'may-gen'       => 'मे',
'june-gen'      => 'जुन',
'july-gen'      => 'जुलाई',
'august-gen'    => 'अगस्त',
'september-gen' => 'सेप्टेम्बर',
'october-gen'   => 'अक्टोबर',
'november-gen'  => 'नोभेम्बर',
'december-gen'  => 'डिसेम्बर',
'jan'           => 'जनवरी',
'feb'           => 'फेब्रुअरी',
'mar'           => 'मार्च',
'apr'           => 'एप्रील',
'may'           => 'मे',
'jun'           => 'जुन',
'jul'           => 'जुलाई',
'aug'           => 'अगस्ट',
'sep'           => 'सेप्टेम्बर',
'oct'           => 'अक्टुबर',
'nov'           => 'नोभेम्बर',
'dec'           => 'डिसेम्बर',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|श्रेणी|श्रेणीहरु}}',
'category_header'          => '"$1" श्रेणीमा भएका लेखहरू',
'subcategories'            => 'उपश्रेणीहरु',
'category-media-header'    => '"$1" श्रेणीमा रहेका मिडियाहरू',
'category-empty'           => "''यो श्रेणीमा हाल कुनै पृष्ठ या मिडियाहरु रहेका छैनन् ।''",
'hidden-categories'        => '{{PLURAL:$1|लुकाइएको श्रेणी|लुकाइएका श्रेणीहरु}}',
'hidden-category-category' => 'लुकाइएका श्रेणीहरु',

'about'         => 'बारेमा',
'article'       => 'मुख्य-लेख पृष्ठ',
'newwindow'     => '(नयाँ विन्डोमा खुल्छ)',
'cancel'        => 'रद्द',
'moredotdotdot' => 'थप...',
'mypage'        => 'मेरो पृष्ठ',
'mytalk'        => 'मेरो कुरा',
'anontalk'      => 'यस् IP को वारेमा वार्तालाप गर्नुहोस्',
'navigation'    => 'अन्वेषण',
'and'           => '&#32;र',

# Cologne Blue skin
'qbfind'         => 'पत्ता लगाउनु',
'qbbrowse'       => 'खोज',
'qbedit'         => 'परिवर्तन्',
'qbpageoptions'  => 'यो पेज',
'qbmyoptions'    => 'मेरो पेज',
'qbspecialpages' => 'विशेष पृष्ठहरु',
'faq'            => 'धैरै सोधिएका प्रश्नहरु',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'   => 'पाठ थप्नुहोस',
'vector-action-delete'       => 'हटाउने',
'vector-action-move'         => 'सार्ने',
'vector-action-protect'      => 'सुरक्षितगर्ने',
'vector-action-undelete'     => 'हटाएको रद्द गर्ने',
'vector-action-unprotect'    => 'सुरक्षित गरिएको रद्द गर्ने',
'vector-namespace-category'  => 'प्रकार',
'vector-namespace-help'      => 'सहायता पृष्ठ',
'vector-namespace-image'     => 'फाइल',
'vector-namespace-main'      => 'पृष्ठ',
'vector-namespace-media'     => 'मिडिया पृष्ठ',
'vector-namespace-mediawiki' => 'सन्देश',
'vector-namespace-project'   => 'प्रोजेक्ट पृष्ठ',
'vector-namespace-special'   => 'विशेष पृष्ठ',
'vector-namespace-talk'      => 'छलफल',
'vector-namespace-template'  => 'टेम्पलेट',
'vector-namespace-user'      => 'प्रयोगकर्ता पृष्ठ',
'vector-view-create'         => 'शृजना गर्ने',
'vector-view-edit'           => 'सम्पादन',
'vector-view-history'        => 'इतिहास हेर्ने',
'vector-view-view'           => 'पढ्ने',
'vector-view-viewsource'     => 'स्रोत हेर्ने',
'actions'                    => 'कृयाकलापहरु',
'namespaces'                 => 'नेमस्पेस',
'variants'                   => 'बहुरुपहरु',

# Metadata in edit box
'metadata_help' => 'मेटाडेटा:',

'errorpagetitle'    => 'त्रुटि',
'returnto'          => '$1 मा फर्कनुहोस् ।',
'tagline'           => '{{SITENAME}}बाट',
'help'              => 'सहयोग',
'search'            => 'खोज',
'searchbutton'      => 'खोज',
'go'                => 'जाउ',
'searcharticle'     => 'जाउ',
'history'           => 'पृष्ठको इतिहास',
'history_short'     => 'पृष्ठको इतिहास',
'updatedmarker'     => 'मेरो अन्तिम भ्रमण पछि अध्यावधिक गरिएको',
'info_short'        => 'जानकारी',
'printableversion'  => 'छाप्नमिल्ने संस्करण',
'permalink'         => 'स्थायी लिंक',
'print'             => 'प्रिन्ट (छाप्नुस)',
'edit'              => 'परिवर्तन',
'create'            => 'शृजना गर्ने',
'editthispage'      => 'यो पृष्ठ सम्पादन गर्नुहोस्',
'create-this-page'  => 'यो पृष्ठ शृजना गर्नुहोस्',
'delete'            => 'मेट्नुहोस्',
'deletethispage'    => 'यो पृष्ठ हटाउनुहोस्',
'undelete_short'    => 'नमेट्ने {{PLURAL:$1|one सम्पादन|$1 सम्पादनहरु}}',
'protect'           => 'सुरक्षित राख्नुहोस्',
'protect_change'    => 'परिवर्तन',
'protectthispage'   => 'यो पृष्ठ सुरक्षित गर्नुहोस्',
'unprotect'         => 'सुरक्षण खारेज गर्ने',
'unprotectthispage' => 'यो पृष्ठको सुरक्षण खारेज गर्ने',
'newpage'           => 'नयाँ पृष्ठ',
'talkpage'          => 'यो पृष्ठको बारेमा छलफल गर्नुहोस्',
'talkpagelinktext'  => 'वार्तालाप',
'specialpage'       => 'विशेष पृष्ठ',
'personaltools'     => 'व्यक्तिगत औजारहरू',
'postcomment'       => 'नयाँ खण्ड',
'articlepage'       => 'कन्टेन्ट पृष्ठ हेर्नुहोस्',
'talk'              => 'वार्तालाप',
'views'             => 'अवलोकनहरू',
'toolbox'           => 'औजारबट्टा',
'userpage'          => 'प्रयोगकर्ता पृष्ठ हेर्ने',
'projectpage'       => 'प्रोजेक्ट पृष्ठ हेर्ने',
'imagepage'         => 'फाइल पृष्ठ हेर्नुहोस्',
'mediawikipage'     => 'सन्देश पृष्ठ हेर्ने',
'templatepage'      => 'टेम्प्लेट पृष्ठहेर्नुहोस',
'viewhelppage'      => 'सहायता पृष्ठ हेर्ने',
'categorypage'      => 'श्रेणी पृष्ठ हेर्नुहोस्',
'viewtalkpage'      => 'छलफल हेर्नुहोस्',
'otherlanguages'    => 'अरु भाषामा',
'redirectedfrom'    => '($1 बाट पठाईएको)',
'redirectpagesub'   => 'रिडाइरेक्ट पृष्ठ',
'lastmodifiedat'    => 'यो पृष्ठलाई अन्तिमपटक $2, $1 मा परिवर्तन गरिएको थियो।',
'viewcount'         => 'यो पृष्ठ हेरिएको थियो {{PLURAL:$1|एकपटक|$1 पटक}}',
'protectedpage'     => 'सुरक्षित गरिएका पृष्ठहरू',
'jumpto'            => 'यसमा जानुहोस्:',
'jumptonavigation'  => 'अन्वेषण',
'jumptosearch'      => 'खोज',
'view-pool-error'   => 'माफ गर्नुहोस् , यस समयमा सर्भरहरुमा कार्यभार उच्च रहेको छ।
अति धेरै प्रयोगकर्ताहरु यो पृष्ट हेर्ने प्रयास गरी रहनु भएको छ।
कृपया यो पृष्ठ पुन: हेर्नु अगाडि केही समय पर्खिदिनुहोस् ।

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} बारेमा',
'aboutpage'            => 'Project:बारेमा',
'copyright'            => 'लेखका सामाग्री $1 अनुसार उपलब्ध छ।',
'copyrightpagename'    => 'प्रतिलिपी अधिकार {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:प्रतिलिपी अधिकारहरु',
'currentevents'        => 'हालैका घटनाहरु',
'currentevents-url'    => 'Project:हालैका घटनाहरु',
'disclaimers'          => 'अस्विकारोक्तिहरु',
'disclaimerpage'       => 'Project:साधारण अस्विकारोक्ति',
'edithelp'             => 'सम्पादन सहायता',
'edithelppage'         => 'Help:सम्पादन',
'helppage'             => 'Help:विषयवस्तुहरू',
'mainpage'             => 'मुख्य पृष्ठ',
'mainpage-description' => 'मुख्य पृष्ठ',
'policy-url'           => 'Project:निति',
'portal'               => 'सामाजिक पोर्टल',
'portal-url'           => 'Project:सामाजिक पोर्टल',
'privacy'              => 'गोपनियता नीति',
'privacypage'          => 'Project: गोपनितता नीति',

'badaccess'        => 'आज्ञा त्रुटी',
'badaccess-group0' => 'तपाईले अनुरोध गर्नुभएको कार्य गर्न तपाईलाई अनुमति दिइएको छैन।',
'badaccess-groups' => 'तपाईले अनुरोध गर्नुभएको कार्य  {{PLURAL:$2|समूह |कुनै एक समूह}}: $1 मा रहेका प्रयोगकर्ताहरुले मात्र गर्नसक्छन ।',

'versionrequired'     => 'MediaWiki संस्करण $1 चाहिने',
'versionrequiredtext' => 'यो पृष्ठ प्रयोग गर्नको लागि MediaWiki $1 संस्करण चाहिन्छ ।
हेर्नुहोस्  [[Special:Version|version page]]',

'ok'                      => 'हुन्छ',
'pagetitle'               => '$1 - {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => ' "$1" बाट निकालिएको',
'youhavenewmessages'      => 'तपाइको लागि ($2) मा  $1 छ ।',
'newmessageslink'         => 'नयाँ सन्देशहरू',
'newmessagesdifflink'     => 'आखिरी परिवर्तन',
'youhavenewmessagesmulti' => 'तपाईंको लागि $1 मा  नयाँ सन्देशहरू छन्',
'editsection'             => 'परिवर्तन्',
'editsection-brackets'    => '[$1]',
'editold'                 => 'परिवर्तन्',
'viewsourceold'           => 'स्रोत हेर्नुहोस्',
'editlink'                => 'सम्पादन',
'viewsourcelink'          => 'स्रोत हेर्नुहोस्',
'editsectionhint'         => 'खण्ड: $1 सम्पादन गर्नुहोस्',
'toc'                     => 'विषयसूची',
'showtoc'                 => 'देखाउनु',
'hidetoc'                 => 'लुकाउनुहोस्',
'thisisdeleted'           => '$1 हेर्ने या पूर्वरुपमा फर्काउने हो ?',
'viewdeleted'             => '$1 हेर्ने ?',
'restorelink'             => '{{PLURAL:$1|एक मेटिएको सम्पादन |$1 मेटिएका सम्पादनहरू}}',
'feedlinks'               => 'फिड :',
'feed-invalid'            => 'अमान्य फिड प्रकार ग्राह्याता ।',
'feed-unavailable'        => 'सिन्डीकेसन फिडहरु उपलब्ध छैनन्',
'site-rss-feed'           => '$1 RSS फिड',
'site-atom-feed'          => '$1 एटम फिड',
'page-rss-feed'           => '"$1" RSS फिड',
'page-atom-feed'          => '"$1" एटम फिड',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (पृष्ठ उपलब्ध छैन)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'लेख',
'nstab-user'      => 'प्रयोगकर्ता पृष्ठ',
'nstab-media'     => 'माध्यम पृष्ठ',
'nstab-special'   => 'Special page',
'nstab-project'   => 'आयोजना पृष्ठ',
'nstab-image'     => 'फाईल',
'nstab-mediawiki' => 'सन्देश',
'nstab-template'  => 'ढाँचा (टेम्प्लेट)',
'nstab-help'      => 'सहायता पृष्ठ',
'nstab-category'  => 'श्रेणी',

# Main script and global functions
'nosuchaction'      => 'यस्तो कार्य हैन',
'nosuchspecialpage' => 'त्यस्तो विषेश पृष्ठ छैन',

# General errors
'badarticleerror' => 'यो कार्य यस पृष्ठमा गर्न मिल्दैन।',
'badtitle'        => 'गलत शिर्षक',
'perfcached'      => 'तलको डाटाहरु क्याचमा रहेका कुराहरु हुन्। अपटुडेट नहुनपनि सक्छन्।',
'perfcachedts'    => 'तलको डाटाहरु क्याचमा रहेका कुराहरु हुन् र यो पछिल्लो पल्ट $1 मा अपडेट गरीएको थियो ।',

# Login and logout pages
'welcomecreation'            => '== स्वागतम् , $1! ==
तपाँईको खाता खोलिएको छ। [[Special:Preferences|{{SITENAME}} preferences]]मा आफ्ना अभिरुचिहरू परिवर्तन गर्न नबिर्सिनुहोला।',
'yourname'                   => 'प्रयोगकर्ता-नाम',
'yourpassword'               => 'पासवर्ड',
'yourpasswordagain'          => 'पासवर्ड फेरी टाईप गर्नुहोस्',
'yourdomainname'             => 'तपाइको कार्यक्षेत्र(डोमेन)',
'login'                      => 'प्रवेश (लग ईन)',
'userlogin'                  => 'प्रवेश / खाता खोल्नुहोस्',
'logout'                     => 'निर्गमन (लग आउउ)',
'userlogout'                 => 'निर्गमन (लग आउउ)',
'notloggedin'                => 'प्रवेश (लग ईन) नगरिएको',
'nologinlink'                => 'नयाँ खाता खोल्नुहोस्',
'createaccount'              => 'खाता खोल्नुहोस्',
'gotaccountlink'             => 'लग इन',
'badretype'                  => 'तपाईंले दिनुभएको पासवर्ड मिल्दैन।',
'noname'                     => 'तपाईले सहि युजर नेम उल्लेख गर्नु भएन.',
'nouserspecified'            => 'तपाँईले प्रयोगकर्तानाम (युजरनेम) जनाउनुपर्छ।',
'wrongpassword'              => 'पासवर्ड गलत हालियो । कृपया फेरी प्रयास गर्नुहोला ।',
'wrongpasswordempty'         => 'तपाइले हालेको पासवर्ड खालि थियो । कृपया फेरी प्रयास गर्नुहोला ।',
'mailmypassword'             => '',
'passwordremindertitle'      => 'नयाँ अस्थाइ प्रवेशशव्द {{SITENAME}} को लागि ।',
'passwordremindertext'       => 'कसैले (सायद तपाईँ, IP ठेगाना $1 बाट), {{SITENAME}}($4) को लागि नयाँ प्रवेशशव्द अनुरोध गर्नुभएको छ । प्रयोगकर्ता "$2" को लागि नयाँ अस्थाई प्रवेशशव्द "$3"तयार पारिएको छ। यदि यो तपाईको इच्छामा भएको भए अहिले तपाईले तपाईँले प्रवेशगरी नयाँ प्रवेशशव्द छान्नु पर्ने हुन्छ।
तपाईको अस्थाई प्रवेशशव्द  {{PLURAL:$5|एक दिन|$5 दिनहरू पछि}} अमान्य हुनेछ ।

यदि कोही अरुले नै अनुरोध गरेको हो भने , या तपाईले आफ्नो प्रवेशशव्द सम्झिनु भयो भने, अथवा
त्यसलाई परिवर्तान गर्न चाहनुहुन्न भने, तपाईँले यो सन्देशको वेवास्ता गर्नसक्नुहुन्छ र पुरानै प्रवेशशव्द प्रयोग गरिरहन सक्नुहुन्छ ।',
'noemail'                    => 'प्रयोगकर्ता  "$1" को लागि कुनै पनि इ-मेल दर्ता गरिएको छैन ।',
'passwordsent'               => '"$1" को लागि दर्ता गरिएको इमेल ठेगानामा एक प्रवेशशव्द पठाइएको छ।
कृपया त्यसलाई प्राप्त गरेपछि प्रवेश गर्नुहोला ।',
'blocked-mailpassword'       => 'तपाईको IP ठेगानालाई सम्पादनगर्नबाट रोक लगाइएको छ, र त्यसैले दुरुपयोग रोक्नको लागि प्रवेशशव्द पुनर्लाभ प्रक्रिया प्रयोग गर्न अनुमति छैन ।',
'acct_creation_throttle_hit' => 'माफ गर्नुहोला तपाइले पहिलेनै $1 वटा खाताहरु खोलिसक्नुभएको छ। तपाइले अब अरु बनाउन सक्नुहुन्न।',
'emailconfirmlink'           => 'तपाईंको ई-मेल ठेगाना कन्फर्म गर्नुहोस्',
'accountcreated'             => 'खाता खोलियो',
'accountcreatedtext'         => '$1 को लागी प्रयोगकर्ता खाता खोलियो ।',
'loginlanguagelabel'         => 'भाषा: $1',

# Password reset dialog
'oldpassword' => 'पुरानो पासवर्ड:',
'newpassword' => 'नयाँ पासवर्ड:',

# Edit page toolbar
'bold_sample'     => 'गाढा अक्षर',
'bold_tip'        => 'गाढा अक्षर',
'headline_sample' => 'शिर्षक अक्षर',
'math_sample'     => 'सूत्र यहाँ थप्नुहोस्',
'math_tip'        => 'गणितीय सूत्र (LaTeX)',
'nowiki_sample'   => 'यहाँ नन फर्म्याटेड ट्क्स्ट घुसाउनुहोस/लेख्नुहोस् ।',
'nowiki_tip'      => 'विकि फरम्याटिङ्लाइ वास्ता नगर्ने (इग्नोर गर्ने)',
'image_sample'    => 'उदाहरण.jpg',
'media_sample'    => 'उदाहरण.ogg',

# Edit pages
'summary'                => 'सारांश:',
'subject'                => 'विषय/शिर्षक:',
'minoredit'              => 'यो सानो सम्पादन हो',
'watchthis'              => 'यो पृष्ठ अवलोकन गर्नुहोस्',
'savearticle'            => 'संग्रह गर्नुहोस्',
'preview'                => 'पूर्वालोकन',
'showpreview'            => 'पूर्वालोकन देखाउनुहोस्',
'showlivepreview'        => 'प्रत्यक्ष पूर्वालोकन',
'showdiff'               => 'परिवर्तन देखाउनुहोस्',
'summary-preview'        => 'सारांश पूर्वालोकन:',
'blockedoriginalsource'  => "'''$1''' को स्रोत तल देखाइएको छ:",
'whitelistedittitle'     => 'सम्पादन गर्नको लागि प्रवेश (लग इन) आवश्यक छ',
'whitelistedittext'      => 'पाना सम्पादन गर्न तपाँईले $1 गर्नु पर्दछ।',
'loginreqlink'           => 'प्रवेश (लग ईन)',
'accmailtitle'           => 'पासवर्ड पठाइयो',
'accmailtext'            => '"$1" को पासवर्ड $2मा पठाइएको छ ।',
'newarticle'             => '(नयाँ)',
'note'                   => "'''सुझाव:'''",
'previewnote'            => "'''यो केवल पूर्वालोकन मात्र हो; परिवर्तनहरू संग्रह गरिसकिएको छैन!'''",
'editing'                => '$1 सम्पादन गरिँदै',
'editingsection'         => '$1 (खण्ड) सम्पादन गरिँदै',
'editconflict'           => 'सम्पादन बाँझियो: $1',
'yourtext'               => 'तपाइका शव्दहरु',
'yourdiff'               => 'भिन्नताहरु',
'template-protected'     => '(सुरक्षित)',
'template-semiprotected' => '(अर्ध-सुरक्षित)',

# Account creation failure
'cantcreateaccounttitle' => 'खाता बनाउन सकिएन',

# History pages
'viewpagelogs'     => 'यस पृष्ठका लगहरू हेर्नुहोस्',
'nohistory'        => 'यस पृष्ठको लागी कुनै सम्पादन इतिहास छैन।',
'previousrevision' => '← पुरानो संशोधन',
'nextrevision'     => 'नयाँ संशोधन →',
'next'             => 'अर्को',
'histfirst'        => 'पहिलो',
'histlast'         => 'अन्तिम',

# Diffs
'compareselectedversions' => 'छानिएका संस्करणहरू दाँज्नुहोस्',

# Search results
'notitlematches' => 'कुनैपनि पृष्ठको शिर्षक संग मिल्दैन',
'notextmatches'  => 'अक्षरस् पेज भेटिएन',
'nextn'          => 'अर्को {{PLURAL:$1|$1}}',

# Preferences page
'preferences'                   => 'रोजाईहरू',
'mypreferences'                 => 'मेरा अभिरुचिहरू',
'prefsnologin'                  => 'प्रवेश (लग ईन) नगरिएको',
'changepassword'                => 'पासवर्ड परिवर्तन गर्नुहोस्',
'skin-preview'                  => 'पूर्वालोकन',
'prefs-math'                    => 'गणित',
'prefs-datetime'                => 'मिति र समय',
'prefs-personal'                => 'प्रयोगकर्ताको विवरण',
'prefs-rc'                      => 'नयाँ परिवर्तनहरु',
'prefs-watchlist'               => 'अवलोकन पृष्ठ',
'recentchangescount'            => 'पूर्व निर्धारितरुपमा देखाउनुपर्ने सम्पादनहरू :',
'prefs-help-recentchangescount' => 'यसमा हालका परिवर्रनहरु , पृष्ठ इतिहासहरु , र सुचीहरू समाविष्ठ छन् ।',
'prefs-help-watchlist-token'    => 'यो विधामा गोप्य संकेत राख्नाले तपाईँको निगरानीसूचीको RSS फिड शृजन हुने छ ।
संकेत थाहपाउने जो कसैले तपाईको निगरानी सूची पढ्न सक्ने भएकोले , सुरक्षित मान छान्नुहोला ।
यहाँ जथाभावीरुपमा-शृजना गरिएको तपाईले प्रयोग गर्ने सक्नुहुने मान छ : $1',
'savedprefs'                    => 'तपाँईका अभिरुचिहरू सङ्ग्रहित भयो।',
'timezonelegend'                => 'समय क्षेत्र :',
'localtime'                     => 'स्थानीय समय',
'timezoneuseserverdefault'      => 'सर्भर पूर्वनिर्धारित प्रयोग गर्नुहोस',
'timezoneuseoffset'             => 'अरु नै(अफसेट खुलाउनुहोस्)',
'timezoneoffset'                => 'अफसेट¹:',
'servertime'                    => 'सर्भर समय:',
'guesstimezone'                 => 'ब्राउजरबाट भराउनुहोस्',
'timezoneregion-africa'         => 'अफ्रिका',
'timezoneregion-america'        => 'अमेरिका',
'timezoneregion-antarctica'     => 'एन्टार्टिका',
'timezoneregion-arctic'         => 'आर्टिक',
'timezoneregion-asia'           => 'एसिया',
'timezoneregion-atlantic'       => 'एट्लान्टिक महासागर',
'timezoneregion-australia'      => 'अष्ट्रेलिया',
'timezoneregion-europe'         => 'युरोप',
'timezoneregion-indian'         => 'हिन्द महासागर',
'timezoneregion-pacific'        => 'प्राशान्त महासागर',
'allowemail'                    => 'अरु प्रयोगकर्ताहरुबाट प्राप्त हुने ईमेल enable गर्नुहोस् ।',
'prefs-searchoptions'           => 'खोज विकल्पहरु',
'prefs-namespaces'              => 'नेमस्पेसेज',
'defaultns'                     => 'अन्यथा यी नेमस्पेसेजमा खोज्ने :',
'default'                       => 'पूर्वनिर्धारित',
'prefs-files'                   => 'फाइलहरु',
'prefs-custom-css'              => 'अनुकुलित CSS',
'prefs-custom-js'               => 'अनुकुलित JS',
'prefs-reset-intro'             => 'तपाईले यो पृष्ठ आफ्नो अभिरुचीहरू साइट पूर्वावस्थामा फर्काउन प्रयोग गर्न सक्नुहुन्छ ।
यो रद्द गर्न सक्नुहुन्छ ।',
'prefs-emailconfirm-label'      => 'इ-मेल एकिन प्रक्रिया :',
'prefs-textboxsize'             => 'सम्पादन विन्डोको आकार',
'youremail'                     => 'ईमेल',
'username'                      => 'प्रयोगकर्ता :',
'uid'                           => 'प्रोगकर्ता आइडी:',
'prefs-memberingroups'          => 'निम्न {{PLURAL:$1|समूह | समूहहरू}}को सदस्य :',
'prefs-memberingroups-type'     => '$1',
'prefs-registration'            => 'दर्ता समय:',
'prefs-registration-date-time'  => '$1',
'yourrealname'                  => 'वास्तविक नाम:',
'yourlanguage'                  => 'भाषा:',
'yourvariant'                   => 'परिवर्तित प्रकार',
'yournick'                      => 'उपनाम (निकनेम):',
'prefs-help-signature'          => 'वार्तालाप पृष्ठका टिप्पणीहरु "<nowiki>~~~~</nowiki>" द्वारा दस्तखत गरिनुपर्छ ,जुन पछि तपाईँको दस्तखत र समयरेखामा रुपान्तरित हुनेछ ।',
'badsig'                        => 'अमान्य कच्चा दस्तखत।
HTML ट्यागहरु जाँच्नुहोस् ।',
'badsiglength'                  => 'तपाईको दस्तखत धेरै लामो छ।
यो $1 {{PLURAL:$1|अक्षर|अक्षरहरू}} भन्दा लामो हुनु हुदैन ।',
'yourgender'                    => 'लिङ्ग:',
'gender-unknown'                => 'नखुलेको',
'gender-male'                   => 'पुरूष',
'gender-female'                 => 'महिला',
'prefs-help-gender'             => 'ऐच्छिक: सफ्टवेयरले लिङगानुसार सम्बोधन गर्नको लागि प्रयोग गरिन्छ ।
यो जानकारी सार्वजनिक हुनेछ ।',
'email'                         => 'ईमेल',
'prefs-help-realname'           => 'वास्तविक नाम ऐच्छिक हो ।
तपाईले यो खुलाउनु भएको खण्डमा तपाईँको कामको श्रेय दिनको लागि प्रयोग गरिने छ।',
'prefs-help-email'              => 'इमेल ठेगाना ऐच्छिक हो , तर तपाईँले आफ्नो प्रवेशशव्द भुल्नु भएमा तपाईँ नयाँ प्रवेशशव्द इमेल गराइ पाउन सक्नुहुन्छ।
तपाईले आफ्नो वास्ताविक परिचय नखुलाइकन  वार्तालाप पृष्ठ प्रयोग गरेर अरुले तपाईँसँग सम्पर्क गर्न पाउने गर्न सक्नुहुन्छ ।',
'prefs-help-email-required'     => 'इमेल ठेगामा चाहिन्छ ।',
'prefs-info'                    => 'साधारण जानकारी',
'prefs-i18n'                    => 'अन्तर्राष्ट्रियकरण',
'prefs-signature'               => 'दस्तखत',
'prefs-dateformat'              => 'मिति ढाँचा',
'prefs-timeoffset'              => 'समय अफसेट',
'prefs-advancedediting'         => 'विशिष्ट विकल्प',
'prefs-advancedrc'              => 'उन्नत विकल्पहरू',
'prefs-advancedrendering'       => 'उन्नत विकल्पहरु',
'prefs-advancedsearchoptions'   => 'उन्नत विकल्पहरू',
'prefs-advancedwatchlist'       => 'उन्नत विकल्पहरू',
'prefs-display'                 => 'प्रदर्शन विकल्पहरू',
'prefs-diffs'                   => 'diffs(भिन्नता)',

# User rights
'userrights' => 'प्रयोगकर्ता अधिकार व्यवस्थापन',

# Groups
'group-bot' => 'बोटहरु',

'group-bot-member'   => 'बोट',
'group-sysop-member' => 'सिसप',

# Recent changes
'recentchanges'     => 'नयाँ परिवर्तनहरु',
'rclistfrom'        => '$1 देखिका नयाँ परिवर्तनहरू देखाउनु',
'diff'              => 'भिन्न',
'hist'              => 'इतिहास',
'hide'              => 'लुकाउनुहोस्',
'show'              => 'देखाउनुहोस्',
'rc_categories_any' => 'कुनै',

# Recent changes linked
'recentchangeslinked'         => 'संबन्धित परिवर्तनहरु',
'recentchangeslinked-feed'    => 'संबन्धित परिवर्तनहरु',
'recentchangeslinked-toolbox' => 'संबन्धित परिवर्तनहरु',

# Upload
'upload'            => 'फाइल अपलोड',
'uploadbtn'         => 'फाइल अपलोड गर्नु',
'uploadnologin'     => 'प्रवेश (लग ईन) नगरिएको',
'filename'          => 'फाइलनाम',
'filedesc'          => 'सारांश',
'fileuploadsummary' => 'सारांश:',
'filestatus'        => 'लेखाधिकार स्थिति:',
'filesource'        => 'स्रोत:',
'watchthisupload'   => 'यो पृष्ठ अवलोकन गर्नुहोस्',

'nolicense' => 'केहिपनि छानिएन',

# Special:ListFiles
'listfiles_date'        => 'मिति',
'listfiles_name'        => 'नाम',
'listfiles_user'        => 'प्रयोगकर्ता',
'listfiles_description' => 'वर्णन',

# File description page
'file-anchor-link' => 'फाईल',
'nolinkstoimage'   => 'यो फाईलसंग लिंकभएको कुनै पृष्ठ छैन.',

# MIME search
'download' => 'डाउनलोड',

# Random page
'randompage' => 'कुनै एक लेख',

# Statistics
'statistics' => 'तथ्यांक',

'brokenredirects'     => 'टुटेका रिडाइरेक्टहरू',
'brokenredirectstext' => 'तलका लिङ्कहरु ले हुदै नभएका पृष्ठहरुलाइ जोड्न खोज्छन्:',

'withoutinterwiki-summary' => 'यी पानाहरूले अन्य भाषाका संस्करणहरूमा संबन्ध राखेका छैनन्:',

# Miscellaneous special pages
'specialpage-empty' => 'यो पृष्ठ खाली छ।',
'lonelypages'       => 'अनाथ पृष्ठहरु',
'popularpages'      => 'धेरै रूचाईएका पृष्ठहरू',
'wantedpages'       => 'खोजिएका पृष्ठहरु',
'mostlinked'        => 'सबैभन्दा बढि लिंक भएको पृष्ठ',
'mostcategories'    => 'सबैभन्दा धेरै श्रेणीहरू भएका लेखहरू',
'mostimages'        => 'सबैभन्दा बढि लिंक भएको चित्र',
'mostrevisions'     => 'सबैभन्दा बढी संशोधित लेखहरू',
'shortpages'        => 'छोटा पृष्ठहरु',
'protectedpages'    => 'संरक्षित पृष्ठहरु',
'listusers'         => 'प्रयोगकर्ता सूची',
'newpages'          => 'नयाँ पृष्ठहरू',
'newpages-username' => 'युजरनेम:',
'ancientpages'      => 'सबैभन्दा पुराना पृष्ठहरु',
'move'              => 'सार्ने',
'movethispage'      => 'यो पृष्ठ सार्नुहोस्',
'notargettitle'     => 'कुनैपनि निसाना(टारगेट) छैन',

# Book sources
'booksources'               => 'किताबका श्रोतहरु',
'booksources-search-legend' => 'किताबका श्रोतहरु खोज्ने',
'booksources-go'            => 'जाउ',

# Special:Log
'specialloguserlabel'  => 'प्रयोगकर्ता:',
'speciallogtitlelabel' => 'शिर्षक:',

# Special:AllPages
'allpages'       => 'सबै पृष्ठहरु',
'alphaindexline' => '$1 लाई $2 मा',
'nextpage'       => 'अर्को पृष्ठ ($1)',
'allpagesfrom'   => 'यहाँदेखि शुरु हुने पृष्ठहरु देखाउनुहोस्:',
'allarticles'    => 'सबै लेखहरु',
'allpagesprev'   => 'अघिल्लो',
'allpagesnext'   => 'अर्को',
'allpagessubmit' => 'जानुहोस्',

# Special:Categories
'categories' => 'श्रेणीहरू',

# Special:LinkSearch
'linksearch-ns' => 'नेमस्पेस:',
'linksearch-ok' => 'खोज्नुहोस्',

# Special:ListUsers
'listusers-submit' => 'देखाउनुहोस्',

# Special:Log/newusers
'newuserlog-create-entry'  => 'नयाँ प्रयोगकर्ता',
'newuserlog-create2-entry' => '$1 का लागी खाता खोलियो',

# E-mail user
'mailnologin'     => 'ईमेल पठाउने ठेगाना नै भएन ।',
'mailnologintext' => 'तपाईले अरु प्रयोगकर्ताहरुलाई ईमेल पठाउनको लागी आफु पहिले [[Special:UserLogin|प्रवेश(लगइन)गरेको]] हुनुपर्छ र [[Special:Preferences|आफ्नो रोजाइहरुमा]] यौटा वैध ईमेल ठेगाना भएको हुनुपर्छ।',
'emailuser'       => 'यो प्रयोगकर्तालाई ई-मेल पठाउनुहोस्',
'emailpage'       => 'प्रयोगकर्तालाई इमेल गर्नुहोस्',
'noemailtitle'    => 'ईमेल ठेगाना नभएको',
'emailsubject'    => 'विषय',
'emailmessage'    => 'सन्देश',
'emailsend'       => 'पठाउनुहोस्',
'emailsent'       => 'इमेल पठाईयो',

# Watchlist
'watchlist'            => 'मेरो अवलोकन',
'mywatchlist'          => 'मेरो अवलोकनसूची',
'nowatchlist'          => 'तपाईको अवलोकन(वाचलिस्ट)मा कुनैपनि चिज छैन।',
'watchnologin'         => 'प्रवेश (लग ईन) नगरिएको',
'watchnologintext'     => 'आफ्नो अवलोकनलाइ परिवर्तन गर्नको लागि त तपाइ यसमा [[Special:UserLogin|प्रवेश(लगइन)]] गर्नुपर्छ।',
'addedwatch'           => 'अवलोकनसूची मा थपियो',
'watch'                => 'अवलोकन',
'watchthispage'        => 'यो पृष्ठ अवलोकन गर्नुहोस्',
'notanarticle'         => 'सामाग्री सहितको पेज हैन',
'wlheader-enotif'      => '* ईमेलद्वारा जानकारी गराउने तरिका enable गरियो ।',
'wlheader-showupdated' => "* तपाइले पछिल्लो पल्ट भ्रमण गरेपछि परिवर्तन भएका पृष्ठहरूलाई '''गाढा''' गरेर देखाइएको छ ।",
'wlshowlast'           => 'पछिल्ला $2 दिनहरूका $3 $1 घण्टाहरूका देखाउनुहोस्',

'enotif_newpagetext' => 'यो नयाँ पृष्ठ हो।',
'changed'            => 'परिवर्तन भइसकेको',

# Delete
'excontent'      => "लेख थियो: '$1'",
'historywarning' => 'खबरदारी: तपाईंले मेटाउन लाग्नुभएको पृष्ठको इतिहास छ:',
'actioncomplete' => 'काम सकियो',
'reverted'       => 'अघिल्लो संशोधनको स्थितिमा फर्काइयो',

# Protect
'protectlogpage'              => 'सुरक्षण लग',
'protectedarticle'            => '"[[$1]]" लाई सुरक्षित गरियो',
'prot_1movedto2'              => '[[$1]] लाई [[$2]]मा सारियो',
'protectcomment'              => 'बचाउको कारण',
'protect-default'             => '(स्वतह)',
'protect-level-autoconfirmed' => 'दर्ता नभएका प्रयोगकर्ताहरूलाई रोक',
'protect-level-sysop'         => 'सिस्अपहरू मात्र',

# Restrictions (nouns)
'restriction-edit' => 'परिवर्तन्',
'restriction-move' => 'सार्ने',

# Undelete
'viewdeletedpage' => 'मेटिएका पृष्ठहरू हेर्नुहोस्',

# Namespace form on various pages
'namespace' => 'नेमस्पेस:',

# Contributions
'contributions' => 'प्रयोगकर्ताका योगदानहरू',
'mycontris'     => 'मेरो योगदान',

# What links here
'whatlinkshere'       => 'यहाँ के जोडिन्छ',
'whatlinkshere-title' => '$1 सँग संबन्ध राख्ने पानाहरू',

# Block/unblock
'blockip'            => 'प्रयोगकर्तालाइ निषेध गर्नुहोस',
'ipaddress'          => 'आई पी ठेगाना',
'ipbotheroption'     => 'अन्य',
'ipblocklist-submit' => 'खोज्नुहोस्',
'blocklistline'      => '$1, $2 द्वारा रोकियो $3 ($4)',
'anononlyblock'      => 'anon. हरु मात्र',
'blocklink'          => 'रोक्नुहोस्',
'contribslink'       => 'योगदान',
'blocklogpage'       => 'निषेध सूची',
'proxyblocksuccess'  => 'सकियो.',

# Developer tools
'lockconfirm' => 'हो, म साँच्चिकै डेटाबेस थुन्न चाहन्छु।',
'lockbtn'     => 'डेटाबेस थुनिदिनुस्',
'unlockbtn'   => 'डाटाबेस अनलक गर्नुहोस्',

# Move page
'move-page-legend'        => 'पृष्ठ सार्नुहोस्',
'movearticle'             => 'पृष्ठ सार्नुहोस्',
'movenologin'             => 'प्रवेश (लग ईन) नगरिएको',
'movepagebtn'             => 'पृष्ठ सार्नुहोस्',
'pagemovedsub'            => 'सार्ने काम सफल भयो',
'movedto'                 => 'मा सारियो',
'1movedto2'               => '[[$1]] लाई [[$2]]मा सारियो',
'movereason'              => 'कारण',
'delete_and_move_confirm' => 'हो, पृष्ठ मेट्नुहोस्',

# Namespace 8 related
'allmessages'     => 'सिस्टम सन्देशहरू',
'allmessagesname' => 'नाम',
'allmessagestext' => 'यो मिडियाविकि नेमस्पेसमा पाइने सिस्टम सन्देशहरूको सूची हो।',

# Special:Import
'import'                  => 'पृष्ठहरु आयात गर्नुहोस्',
'import-interwiki-submit' => 'आयात',
'importstart'             => 'पृष्ठ आयात गरिँदै...',
'importsuccess'           => 'आयात सम्पन्न भयो!',

# Tooltip help for the actions
'tooltip-pt-userpage'     => 'मेरो प्रयोगकर्ता पृष्ठ',
'tooltip-pt-mytalk'       => 'मेरो वार्तालाप पृष्ठ',
'tooltip-pt-preferences'  => 'मेरा अभिरुचिहरू (प्रेफरेन्सेस्‌हरू)',
'tooltip-pt-watchlist'    => 'पृष्ठहरूको सूची जसका परिवर्तनहरूलाई तपाईँले निगरानी गरिरहनु भएको छ',
'tooltip-pt-mycontris'    => 'मेरा योगदानहरूको सूची',
'tooltip-pt-anonlogin'    => 'तपाईँलाई लग-इन गर्न प्रोत्साहन गरिन्छ, तर यो अनिवार्य चाँही होइन।',
'tooltip-pt-logout'       => 'निर्गमन (लग आउट) गर्नुहोस्',
'tooltip-ca-viewsource'   => 'यो पृष्ठ सुरक्षित गरिएको छ। यसको श्रोत हेर्न सक्नुहुन्छ।',
'tooltip-ca-protect'      => 'यो पृष्ठलाई संरक्षित गर्नुहोस्',
'tooltip-ca-delete'       => 'यो पृष्ठ मेटाउनुहोस्',
'tooltip-ca-move'         => 'यो पृष्ठलाई सार्नुहोस्',
'tooltip-ca-watch'        => 'यो पृष्ठलाई तपाईँको अवलोकनसूचीमा थप्नुहोस्',
'tooltip-ca-unwatch'      => 'यो पृष्ठलाई तपाईँको अवलोकनसूचीबाट हटाउनुहोस्',
'tooltip-search'          => '{{SITENAME}} मा खोज्नुहोस्',
'tooltip-p-logo'          => 'मुख्य पृष्ठ',
'tooltip-t-contributions' => 'यस प्रयोगकर्ताका योगदानहरूको सूची हेर्नुहोस्',
'tooltip-t-specialpages'  => 'सबै विशेष पृष्ठहरूको सूची',
'tooltip-save'            => 'तपाईँले गरेका परिवर्तनहरू संग्रह (सेभ) गर्नुहोस्',
'tooltip-watch'           => 'यो पृष्ठलाई तपाईँको अवलोकनसूचीमा थप्नुहोस्',

# Attribution
'lastmodifiedatby' => 'यो पृष्ठ अन्तिमपटक $3द्वारा $2, $1 मा परिवर्तन गरिएको थियो।',
'othercontribs'    => '$1 को कामको आधारमा',
'others'           => 'अन्य',

# Info page
'infosubtitle'   => 'पृष्ठको लागि जानकारी',
'numedits'       => 'सम्पादन संख्या (लेख): $1',
'numtalkedits'   => 'सम्पादन संख्या (छलफल पृष्ठ): $1',
'numwatchers'    => 'अवलोकन संख्या: $1',
'numauthors'     => 'प्रष्ट लेखकहरुको संख्या (लेख): $1',
'numtalkauthors' => 'लेखकहरूको संख्या (छलफल पृष्ठ): $1',

# Math errors
'math_unknown_error'    => 'अज्ञात समस्या',
'math_unknown_function' => 'अज्ञात निर्देशन',

# Patrol log
'patrol-log-auto' => '(स्वचालित)',

# Browsing diffs
'previousdiff' => '← अधिल्लो भिन्नता',
'nextdiff'     => 'पछिल्लो भिन्नता →',

# Special:NewFiles
'noimages' => 'हेर्नको लागि केही छैन.',
'ilsubmit' => 'खोज्नुहोस्',
'bydate'   => 'मिति अनुसार',

# Metadata
'metadata'          => 'मेटाडेटा',
'metadata-expand'   => 'लामो विबरण देखाउनुहोस्',
'metadata-collapse' => 'लामो विबरण लुकाउनुहोस्',

# EXIF tags
'exif-imagewidth'                => 'चौडाइ',
'exif-photometricinterpretation' => 'पिक्सेल कम्पोजिसन',
'exif-imagedescription'          => 'चित्र नाम',
'exif-make'                      => 'क्यामेरा निर्माता',
'exif-artist'                    => 'लेखक',
'exif-lightsource'               => 'प्रकाश स्रोत',
'exif-subjectarea'               => 'विषय क्षेत्र',
'exif-filesource'                => 'फाइल स्रोत',
'exif-gpslatituderef'            => 'उत्तर वा दक्षिण अक्षांश',
'exif-gpslatitude'               => 'अक्षांश',
'exif-gpslongituderef'           => 'पूर्व वा पश्चिम देशान्तर',
'exif-gpslongitude'              => 'देशान्तर',
'exif-gpsaltitude'               => 'उँचाई',
'exif-gpsstatus'                 => 'रिसिभर अवस्था',
'exif-gpstrack'                  => 'चलेको दिशा',

'exif-orientation-1' => 'सामान्य',
'exif-orientation-6' => '90° CW घुमाइएको',

'exif-subjectdistance-value' => '$1 मिटर',

'exif-meteringmode-0'   => 'अज्ञात',
'exif-meteringmode-1'   => 'सामान्य',
'exif-meteringmode-255' => 'अन्य',

'exif-lightsource-0' => 'अज्ञात',

'exif-focalplaneresolutionunit-2' => 'इञ्च',

'exif-customrendered-0' => 'सामान्य प्रक्रिया',

'exif-contrast-0' => 'सामान्य',
'exif-contrast-1' => 'हल्का',
'exif-contrast-2' => 'गाढा',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'उत्तर अक्षांश',
'exif-gpslatitude-s' => 'दक्षिण अक्षांश',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'पूर्व देशान्तर',
'exif-gpslongitude-w' => 'पश्चिम देशान्तर',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'किलोमिटर प्रति घण्टा',
'exif-gpsspeed-m' => 'माइल प्रति घण्टा',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'सबै',
'imagelistall'     => 'सबै',
'watchlistall2'    => 'सबै',
'namespacesall'    => 'सबै',

# E-mail address confirmation
'confirmemail' => 'इमेल ठेगाना पक्का गर्नुहोस्',

# Delete conflict
'recreate' => 'पुनर्निर्माण',

# action=purge
'confirm_purge_button' => 'हुन्छ',

# Multipage image navigation
'imgmultipageprev' => '← अघिल्लो पृष्ठ',
'imgmultipagenext' => 'पछिल्लो पृष्ठ →',
'imgmultigo'       => 'जानुहोस्!',

# Table pager
'ascending_abbrev'         => 'वर्णानुक्रम',
'table_pager_next'         => 'पछिको पृष्ठ',
'table_pager_prev'         => 'अगाडिको पृष्ठ',
'table_pager_first'        => 'प्रथम पृष्ठ',
'table_pager_last'         => 'अन्तिम पृष्ठ',
'table_pager_limit_submit' => 'जाउ',

# Auto-summaries
'autosumm-blank'   => 'पृष्ठको सबै सामाग्रीहरू हटाइ पृष्ठ खाली गरीँदै',
'autosumm-replace' => "पृष्ठलाई '$1' संग हटाइदै",
'autosumm-new'     => 'नयाँ पृष्ठ: $1',

# Live preview
'livepreview-loading' => 'लोड गरिंदै छ…',

# Special:FilePath
'filepath-page' => 'फाइल',

# Special:SpecialPages
'specialpages' => 'विशेष पृष्ठ',

);
