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
'pagecategories'                 => '{{PLURAL:$1|श्रेणी|श्रेणीहरु}}',
'category_header'                => '"$1" श्रेणीमा भएका लेखहरू',
'subcategories'                  => 'उपश्रेणीहरु',
'category-media-header'          => '"$1" श्रेणीमा रहेका मिडियाहरू',
'category-empty'                 => "''यो श्रेणीमा हाल कुनै पृष्ठ या मिडियाहरु रहेका छैनन् ।''",
'hidden-categories'              => '{{PLURAL:$1|लुकाइएको श्रेणी|लुकाइएका श्रेणीहरु}}',
'hidden-category-category'       => 'लुकाइएका श्रेणीहरु',
'category-subcat-count'          => '{{PLURAL:$2|यो श्रेणीमा निम्न उपश्रेणीहरु मात्र छन्।|यो श्रेणीको निम्न {{PLURAL:$1|उपश्रेणी|$1 उपश्रेणीहरु}},  $2 कुल मध्ये श्रेणीहरु छन् ।}}',
'category-subcat-count-limited'  => 'यो श्रेणीको निम्न {{PLURAL:$1|उपश्रेणी|$1 उपश्रेणीहरु}} छ।',
'category-article-count'         => '{{PLURAL:$2|यो श्रेणीमा एक मात्र पृष्ठरहेको छ।|यो श्रेणीमा  {{PLURAL:$1|पृष्ठ|$1 पृष्ठहरु}} , कुल $2 मध्ये रहेका छन् । }}',
'category-article-count-limited' => 'निम्न {{PLURAL:$1|पृष्ठ|$1 पृष्ठहरु}} यस श्रेणीमा रहेको ।',
'category-file-count'            => '{{PLURAL:$2|यो श्रेणीमा निम्न फाइल मात्र छ ।|निम्न श्रेणीमा {{PLURAL:$1|फाइल|$1 फाइलहरु}} , कुल  $2 मध्ये रहेको ।}}',
'category-file-count-limited'    => 'निम्न  {{PLURAL:$1|फाइल|$1 फाइलहरु}} यस श्रेणीमा रहेको ।',
'listingcontinuesabbrev'         => 'निरन्तरता...',

'mainpagetext'      => "<big>'''MediaWiki सफलतापूर्वक स्थापना भयो ।'''</big>",
'mainpagedocfooter' => " विकी अनुप्रयोग कसरी प्रयोग गर्ने भन्ने जानकारीको लागि  [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] हेर्नुहोस् 

== सुरू गर्नको लागि  ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

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
'qbpageinfo'     => 'सन्दर्भ',
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
'nosuchactiontext'  => 'URL ले खुलाएको कार्य मान्य छैन । 
तपाईले URL गलत टाइपगर्नु भएको , वा गलत लिंक पछ्याउनु भएको हुनसक्छ । 
यस{{SITENAME}}ले सफ्टवेयरमा भएको गल्ति देखाएको पनि हुनसक्छ ।',
'nosuchspecialpage' => 'त्यस्तो विषेश पृष्ठ छैन',
'nospecialpagetext' => "<big>'''तपाईँले अनुरोध गर्नुभएको विशेष पृष्ठ अमान्य छ ।'''</big>

मान्य पृष्ठहरुको सूची यहाँ [[Special:SpecialPages|{{int:specialpages}}]] उपलब्ध छ ।",

# General errors
'error'                => 'त्रुटी',
'databaseerror'        => 'डेटावेस त्रुटी',
'dberrortext'          => '',
'laggedslavemode'      => "'''चेतावनी:''' पृष्ठमा हालैको अध्यावधिहरु नरहेका पनि हुनसक्छन ।",
'readonly'             => 'डेटाबेस ताल्चामारिएको छ',
'missing-article'      => 'डेटाबेसले पृष्ठको पाठ भेटाएन जुन भेटिनु पर्ने थियो , नाम "$1" $2.

यस्तो प्राय: मिति नाघिसकेको diff वा इतिहास वा कुनै मेटिसकेको पानाको लिंक पछ्याउनाले हुन्छ ।

यदी यस्तो भएको होइन भने सफ्टवेयरको त्रुटी पनि हुनस्छ ।
कृपया यसको url खुलाइ यहाँ उजुरी गर्नुहोस्  [[Special:ListUsers/sysop|administrator]].',
'missingarticle-rev'   => '(पुनरावलोकन #: $1)',
'missingarticle-diff'  => '(डिफ diff: $1, $2)',
'internalerror'        => 'आन्तरिक त्रुटि',
'internalerror_info'   => 'आन्तरिक त्रुटि: $1',
'fileappenderror'      => ' "$2".लाई"$1" मा जोडन सकिएन ।',
'filecopyerror'        => 'फाइल  "$1" लाई "$2" मा प्रतिलिपी गर्न सकिएन ।',
'filerenameerror'      => 'फाइल "$1" को नाम "$2" मा परिवर्तन गर्न सकिएन ।',
'filedeleteerror'      => 'फाइल "$1"  मेट्न सकिएन ।',
'directorycreateerror' => 'डाइरेक्टरी "$1" निर्माणगर्न सकिएन ।',
'filenotfound'         => '"$1" फाइल भेटिएन ।',
'fileexistserror'      => 'फाइल  "$1 लेख्न सकिएन : फाइल पहिले देखि रहेको छ',
'unexpected'           => 'अप्रत्यासित मान :"$1"="$2" ।',
'formerror'            => 'त्रुटी : फर्म बुझाउन सकिएन',
'badarticleerror'      => 'यो कार्य यस पृष्ठमा गर्न मिल्दैन।',
'cannotdelete'         => 'खुलाइएको फाइल वा पृष्ठ मेट्न सकिएन ।
यो पहिले नै अरु कसै द्वारा मेटाइएको हुन सक्छ ।',
'badtitle'             => 'गलत शिर्षक',
'badtitletext'         => 'अनुरोध गरेको पृष्ठ शिर्षक अमान्य, खाली वा गलत रुपमा अन्तर भाषा वा अन्तर विकी सम्बन्ध गरिएको थियो।  यसमा शिर्षकमा प्रयोग गर्न नमिल्ने एक वा बढी अक्षरहरू रहेका हुनसक्छन ।',
'perfcached'           => 'तलको डाटाहरु क्याचमा रहेका कुराहरु हुन्। अपटुडेट नहुनपनि सक्छन्।',
'perfcachedts'         => 'तलको डाटाहरु क्याचमा रहेका कुराहरु हुन् र यो पछिल्लो पल्ट $1 मा अपडेट गरीएको थियो ।',
'querypage-no-updates' => 'यो पृष्ठको अध्यावधी कार्य निस्क्रिय गरिएको छ।
यहाँको डेटा तत्कालै ताजा पारिने  छैन।',
'wrong_wfQuery_params' => 'गलत प्यारामेटर  wfQuery()को लागि <br />
फङ्सन: $1<br />
क्वेरी: $2',
'viewsource'           => 'स्रोत हेर्नुहोस',
'viewsourcefor'        => '$1 को लागि',
'actionthrottled'      => 'कार्य रोकियो',
'actionthrottledtext'  => 'स्पामबाट बच्ने तरिकाको रुपमा , तपाईँलाई यो कार्य थोरै समयमा धेरै पटक गर्नबाट सिमित गरिएको छ, र तपाईले आफ्नो सिमा पार गरिसक्नु भयो । 
कृपया केही मिनेटहरु पछि पुन: प्रयास गर्नुहोस्  ।',
'protectedpagetext'    => 'यो पृष्ठ सम्पादन हुनबाट बचाउन सम्पादनमा रोक  लगाइएको छ।',
'viewsourcetext'       => 'तपाईँले यस पृष्ठको स्रोत हेर्न र प्रतिलिपी गर्न सक्नुहुन्छ ।',
'protectedinterface'   => 'यो पृष्ठले सफ्टवेयरको लागि अन्तरमोहडा पाठ प्रदान गर्दछ , र यसलाई दुरुपयोग हुनबाट बचाउन ताल्चा मारिएको छ।',
'sqlhidden'            => '(SQL क्वेरी लुकाएको)',
'ns-specialprotected'  => 'विशेष पृष्ठ सम्पादन गर्न सकिदैन ।',

# Virus scanner
'virus-scanfailed'     => 'पढाइ असफल(कोड $1)',
'virus-unknownscanner' => 'नखुलेको एन्टीभाइरस:',

# Login and logout pages
'welcomecreation'            => '== स्वागतम् , $1! ==
तपाँईको खाता खोलिएको छ। [[Special:Preferences|{{SITENAME}} preferences]]मा आफ्ना अभिरुचिहरू परिवर्तन गर्न नबिर्सिनुहोला।',
'yourname'                   => 'प्रयोगकर्ता-नाम',
'yourpassword'               => 'पासवर्ड',
'yourpasswordagain'          => 'पासवर्ड फेरी टाईप गर्नुहोस्',
'remembermypassword'         => 'यो कम्प्युटरमा मेरो प्रवेश याद गर्ने ।',
'yourdomainname'             => 'तपाइको कार्यक्षेत्र(डोमेन)',
'login'                      => 'प्रवेश (लग ईन)',
'nav-login-createaccount'    => 'प्रवेश गर्नुहोस्  / नयँ खाता खोल्नुहोस्',
'loginprompt'                => 'तपाईले  {{SITENAME}}मा प्रवेशगर्न कुकीहरू सक्रिय बनाउनुपर्छ ।',
'userlogin'                  => 'प्रवेश / खाता खोल्नुहोस्',
'logout'                     => 'निर्गमन (लग आउउ)',
'userlogout'                 => 'निर्गमन (लग आउउ)',
'notloggedin'                => 'प्रवेश (लग ईन) नगरिएको',
'nologin'                    => 'के तपाईसँग खाता छैन ?$1 ।',
'nologinlink'                => 'नयाँ खाता खोल्नुहोस्',
'createaccount'              => 'खाता खोल्नुहोस्',
'gotaccount'                 => 'के तपाईँसँग पहिले देखि नै खाता छ ? $1 ।',
'gotaccountlink'             => 'लग इन',
'createaccountmail'          => 'इ-मेलबाट',
'badretype'                  => 'तपाईंले दिनुभएको पासवर्ड मिल्दैन।',
'userexists'                 => 'तपाईले प्रविष्ट गर्नुभएको प्रयोगकर्ताको नाम पहिले देखिनै प्रयोगमा छ ।
कृपया फरक नाम छान्नुहोला ।',
'loginerror'                 => 'प्रवेश त्रुटी',
'nocookiesnew'               => 'तपाईँको खाता बनाइयो, तर तपाईँ प्रवेश गर्नुभएको छैन । 
{{SITENAME}} ले प्रयोगकर्ता प्रवेश गराउन कुकीहरू प्रयोग गर्छ ।
तपाईँको कुकीहरू निस्क्रिय गरिएको छ।
कृपया सक्रिय बनाइ , नाम र प्रवेशशव्द राखी प्रवेश गर्नुहोला ।',
'nocookieslogin'             => '{{SITENAME}} ले प्रयोगकर्ता प्रवेश गराउन कुकीहरू प्रयोग गर्छ । तपाईँको कुकीहरू निस्क्रिय गरिएको छ। कृपया सक्रिय बनाइ , नाम र प्रवेशशव्द राखी प्रवेश गर्नुहोला ।',
'noname'                     => 'तपाईले सहि युजर नेम उल्लेख गर्नु भएन.',
'loginsuccesstitle'          => 'प्रवेश सफल',
'loginsuccess'               => "'''तपाईँ अहिले {{SITENAME}} मा \"\$1\"को रूपमा प्रवेशगर्नु भएकोछ ।'''",
'nosuchuser'                 => '"$1" को नामबाट कुनै पनि प्रयोगकर्ता भेटिएनन् ।
प्रयोगकर्ता नाम वर्णसंवेदनशील हुन्छन् ।
हिज्जे जाँच्नुहोस् , या [[Special:UserLogin/signup|नयाँ खाता बनाउनुहोस्]].',
'nosuchusershort'            => ' "<nowiki>$1</nowiki>"नामबाट कुनैपनि प्रयोगकर्ता भेटिएनन् ।
 तपाईँको हिज्जे जाँच्नुहोस् ।',
'nouserspecified'            => 'तपाँईले प्रयोगकर्तानाम (युजरनेम) जनाउनुपर्छ।',
'wrongpassword'              => 'पासवर्ड गलत हालियो । कृपया फेरी प्रयास गर्नुहोला ।',
'wrongpasswordempty'         => 'तपाइले हालेको पासवर्ड खालि थियो । कृपया फेरी प्रयास गर्नुहोला ।',
'passwordtooshort'           => 'तपाईको प्रवेशशव्द धेरै छोटो थियो ।
यो कम्तिमा{{PLURAL:$1|१ अक्षर |$1 अक्षरहरु }}को हुनुपर्छ ।',
'password-name-match'        => 'तपाईँको प्रवेशशव्द प्रयोगकर्ता नाम भन्दा फरक हुनुपर्छ ।',
'mailmypassword'             => 'नयाँ प्रवेशशव्द इमेल गर्ने',
'passwordremindertitle'      => 'नयाँ अस्थाइ प्रवेशशव्द {{SITENAME}} को लागि ।',
'passwordremindertext'       => 'कसैले (सायद तपाईँ, IP ठेगाना $1 बाट), {{SITENAME}}($4) को लागि नयाँ प्रवेशशव्द अनुरोध गर्नुभएको छ । प्रयोगकर्ता "$2" को लागि नयाँ अस्थाई प्रवेशशव्द "$3"तयार पारिएको छ। यदि यो तपाईको इच्छामा भएको भए अहिले तपाईले तपाईँले प्रवेशगरी नयाँ प्रवेशशव्द छान्नु पर्ने हुन्छ।
तपाईको अस्थाई प्रवेशशव्द  {{PLURAL:$5|एक दिन|$5 दिनहरू पछि}} अमान्य हुनेछ ।

यदि कोही अरुले नै अनुरोध गरेको हो भने , या तपाईले आफ्नो प्रवेशशव्द सम्झिनु भयो भने, अथवा
त्यसलाई परिवर्तान गर्न चाहनुहुन्न भने, तपाईँले यो सन्देशको वेवास्ता गर्नसक्नुहुन्छ र पुरानै प्रवेशशव्द प्रयोग गरिरहन सक्नुहुन्छ ।',
'noemail'                    => 'प्रयोगकर्ता  "$1" को लागि कुनै पनि इ-मेल दर्ता गरिएको छैन ।',
'passwordsent'               => '"$1" को लागि दर्ता गरिएको इमेल ठेगानामा एक प्रवेशशव्द पठाइएको छ।
कृपया त्यसलाई प्राप्त गरेपछि प्रवेश गर्नुहोला ।',
'blocked-mailpassword'       => 'तपाईको IP ठेगानालाई सम्पादनगर्नबाट रोक लगाइएको छ, र त्यसैले दुरुपयोग रोक्नको लागि प्रवेशशव्द पुनर्लाभ प्रक्रिया प्रयोग गर्न अनुमति छैन ।',
'eauthentsent'               => 'दिइएको इमेलठेगनामा  किटानी इमेल ठाइएको छ ।
तपाईको खातामा अरु इमेल पठउनु अघि , इमेलमा लेखिएको मार्गदर्शन अनुसार , त्यो खाता तपाईँकै हो भनेर निश्चित गराउनु पर्नेछ ।',
'mailerror'                  => ' चिठी :$1 पठाउँदा त्रुटी भयो',
'acct_creation_throttle_hit' => 'माफ गर्नुहोला तपाइले पहिलेनै $1 वटा खाताहरु खोलिसक्नुभएको छ। तपाइले अब अरु बनाउन सक्नुहुन्न।',
'emailconfirmlink'           => 'तपाईंको ई-मेल ठेगाना कन्फर्म गर्नुहोस्',
'accountcreated'             => 'खाता खोलियो',
'accountcreatedtext'         => '$1 को लागी प्रयोगकर्ता खाता खोलियो ।',
'createaccount-title'        => ' {{SITENAME}}को लागि खाता खोल्ने काम',
'loginlanguagelabel'         => 'भाषा: $1',

# Password reset dialog
'resetpass'                 => 'प्रवेशशव्द परिवर्तन गर्नुहोस्',
'resetpass_header'          => 'खाताको प्रवेशशव्द परिवर्तन गर्ने',
'oldpassword'               => 'पुरानो पासवर्ड:',
'newpassword'               => 'नयाँ पासवर्ड:',
'retypenew'                 => 'प्रवेशशव्द पुन: प्रविष्ट गर्नुहोस् :',
'resetpass_submit'          => 'प्रवेशशव्द मिउने र प्रवेशगर्ने',
'resetpass_forbidden'       => 'प्रवेशशव्द परिवर्तन गर्न मिल्दैन',
'resetpass-no-info'         => 'यो पृष्ठ सिधै हेर्नको लागि तपाईँले प्रवेश गर्नुपर्छ ।',
'resetpass-submit-loggedin' => 'प्रवेशशव्द परिवर्तन गर्ने',
'resetpass-temp-password'   => 'अस्थाइ प्रवेशशव्द',

# Edit page toolbar
'bold_sample'     => 'गाढा अक्षर',
'bold_tip'        => 'गाढा अक्षर',
'italic_sample'   => 'इटालिक पाठ',
'italic_tip'      => 'इटालिक पाठ',
'link_sample'     => 'शिर्षक लिंङ्क',
'link_tip'        => 'आन्तरिक लिङ्क',
'extlink_sample'  => 'http://www.example.com लिङ्क शिर्षक',
'extlink_tip'     => 'बाह्य लिङ्क (सम्झनुहोस् http:// prefix)',
'headline_sample' => 'शिर्षक अक्षर',
'headline_tip'    => 'दोस्रो स्तर शिर्षपंक्ति',
'math_sample'     => 'सूत्र यहाँ थप्नुहोस्',
'math_tip'        => 'गणितीय सूत्र (LaTeX)',
'nowiki_sample'   => 'यहाँ नन फर्म्याटेड ट्क्स्ट घुसाउनुहोस/लेख्नुहोस् ।',
'nowiki_tip'      => 'विकि फरम्याटिङ्लाइ वास्ता नगर्ने (इग्नोर गर्ने)',
'image_sample'    => 'उदाहरण.jpg',
'image_tip'       => 'इम्बेडेड(जडान गरिएको) फाइल',
'media_sample'    => 'उदाहरण.ogg',
'media_tip'       => 'फाइल लिङ्क',
'sig_tip'         => 'तपाईँको समयछाप सहितको दस्तखत',
'hr_tip'          => 'क्षितिजिय रेखा (कम प्रयोग गर्नुहोस्)',

# Edit pages
'summary'                          => 'सारांश:',
'subject'                          => 'विषय/शिर्षक:',
'minoredit'                        => 'यो सानो सम्पादन हो',
'watchthis'                        => 'यो पृष्ठ अवलोकन गर्नुहोस्',
'savearticle'                      => 'संग्रह गर्नुहोस्',
'preview'                          => 'पूर्वालोकन',
'showpreview'                      => 'पूर्वालोकन देखाउनुहोस्',
'showlivepreview'                  => 'प्रत्यक्ष पूर्वालोकन',
'showdiff'                         => 'परिवर्तन देखाउनुहोस्',
'anoneditwarning'                  => "'''चेतावनी:''' तपाईँले प्रवेश गर्नु भएको छैन। 
तपाईँको IP ठेगाना पृष्ठसम्पादन इतिहासमा दर्तागरिने छ ।",
'missingsummary'                   => "'''यादगर्नुहोस् :''' तपाईले सम्पादन सारांश दिनुभएको छैन ।
यदि तपाईले संग्रहगर्नुहोस्  थिच्नुभयो भने , सारांश बिना नै संग्रहित गरिने छ ।",
'missingcommenttext'               => 'कृपया टिप्पणी प्रविष्ठ गर्नुहोस् ।',
'missingcommentheader'             => "'''याद गर्नुहोस् :''' तपाईँले टिप्पणीमा विषय /शिर्ष पंक्ति  दिनुभएको छैन ।
तपाईँले फेरि संग्रह गर्नुहोस्  थिच्नु भएमा , तपाईको सम्पादन यसै रुपमा संग्रहित हुनेछ ।",
'summary-preview'                  => 'सारांश पूर्वालोकन:',
'subject-preview'                  => 'विषय/शिर्षपंंक्ति पूर्वरुप:',
'blockedtitle'                     => 'प्रयोककर्तालाई रोक लगाइएको छ',
'blockedtext'                      => "<big>'''तपाईँको प्रयोगकर्ता नाम या IP ठेगानालाई रोक लगाइएको छ ।'''</big>

रोक लगाउने  $1.
रोक लगाउनाको कारण ''$2''.

* रोक सुरूहुने : $8
* रोक सकिने: $6
* रोकबाट लक्षित: $7

तपाईले  $1 वा अरु कुनै  [[{{MediaWiki:Grouppage-sysop}}|administrator]] सँग रोकको बारेमा छलफल गर्न सम्पर्क गर्न सक्नुहुन्छ ।
तपाईँले  'प्रयोगकर्तालाई इ-मेल गर्ने ' सुविधा मान्य इमेल ठेगाना [[Special:Preferences|account preferences]] मा नखुलाए सम्म प्रयोगगर्न पाउनुहुने छैन र यसको प्रयोग गर्नबाट रोक लगाइएको छैन ।
तपाईको IP ठेगाना $3 को, र रोक्का संख्या #$5.
कृपया तपाईँको प्रश्नमा सबै जानकारी खुलाउनुहोला ।",
'blockednoreason'                  => 'कारण दिइएको छैन',
'blockedoriginalsource'            => "'''$1''' को स्रोत तल देखाइएको छ:",
'whitelistedittitle'               => 'सम्पादन गर्नको लागि प्रवेश (लग इन) आवश्यक छ',
'whitelistedittext'                => 'पाना सम्पादन गर्न तपाँईले $1 गर्नु पर्दछ।',
'nosuchsectiontitle'               => 'त्यस्तो खण्ड छैन',
'loginreqtitle'                    => 'प्रवेशगर्नु जरुरी छ।',
'loginreqlink'                     => 'प्रवेश (लग ईन)',
'loginreqpagetext'                 => 'अरु पृष्ठहेर्न तपाईले $1 गर्नुपर्छ ।',
'accmailtitle'                     => 'पासवर्ड पठाइयो',
'accmailtext'                      => "जथाभावीरुपमा शृजना गरिएको प्रवेशशब्द प्रयोगकर्ता [[User talk:$1|$1]] को  $2 मा पठाइएको छ। 

यो नयाँ खाताको प्रवेशशब्द  ''[[Special:ChangePassword|change password]]'' मा प्रवेश गरेर परिवर्तन गर्न सकिन्छ ।",
'newarticle'                       => '(नयाँ)',
'newarticletext'                   => "तपाईँले अहिले सम्म नभएको पृष्ठको लिंङ्क पहिल्याउनु भएको छ।
यो पृष्ठ निर्माण गर्न तलको बाक्सामा टाइप गर्नुहोस्  ।(थप जानकारीको लागि [[{{MediaWiki:Helppage}}|help page]] हेर्नुहोस् )।
यहाँ त्यत्तिकै आइपुग्नु भएको हो भने , ब्राउजरको  '''back''' बटन थिच्नुहोस ।",
'updated'                          => '(अध्यावधिक)',
'note'                             => "'''सुझाव:'''",
'previewnote'                      => "'''यो केवल पूर्वालोकन मात्र हो; परिवर्तनहरू संग्रह गरिसकिएको छैन!'''",
'editing'                          => '$1 सम्पादन गरिँदै',
'editingsection'                   => '$1 (खण्ड) सम्पादन गरिँदै',
'editingcomment'                   => '$1 सम्पादन गर्दै(नयाँ खण्ड)',
'editconflict'                     => 'सम्पादन बाँझियो: $1',
'yourtext'                         => 'तपाइका शव्दहरु',
'storedversion'                    => 'संग्रहित पुनरावलोकन',
'yourdiff'                         => 'भिन्नताहरु',
'templatesused'                    => 'यस पृष्ठमा प्रयोग भएको टेम्प्लेट(नमूना):',
'templatesusedpreview'             => 'यो पूर्वावलोकनमा प्रयोग भएका टेम्प्लेट(नमूना) हरु:',
'templatesusedsection'             => 'यो खण्डमा प्रयोग गरिएको टेम्प्लेटहरु :',
'template-protected'               => '(सुरक्षित)',
'template-semiprotected'           => '(अर्ध-सुरक्षित)',
'hiddencategories'                 => 'यो पृष्ठ निम्न {{PLURAL:$1|1 लुकाइएको श्रेणी|$1 लुकाइएका श्रेणीहरु}}को सदस्य हो :',
'nocreatetitle'                    => 'पृष्ठ शृजना सिमित गरिएको',
'nocreate-loggedin'                => 'नयाँ पृष्ठ शृजनागर्नको लागि तपाईँसँग अनुमति छैन ।',
'permissionserrors'                => 'अनुमति त्रुटीहरु',
'permissionserrorstext-withaction' => '$2 को लागि तपाईँलाई अनुमति छैन , निम्न {{PLURAL:$1|कारण|कारणहरुले}} गर्दा :',
'edit-conflict'                    => 'द्वन्द समपादन गर्ने ।',

# Parser/template warnings
'parser-template-loop-warning' => 'टम्प्लेट(नमूना) चक्र भेटियो : [[$1]]',

# Account creation failure
'cantcreateaccounttitle' => 'खाता बनाउन सकिएन',

# History pages
'viewpagelogs'           => 'यस पृष्ठका लगहरू हेर्नुहोस्',
'nohistory'              => 'यस पृष्ठको लागी कुनै सम्पादन इतिहास छैन।',
'currentrev'             => 'हालको संस्करण',
'currentrev-asof'        => '$1को रुपमा हालको पुनरावलोकनहरु',
'revisionasof'           => '$1 जस्तै गरी पुनरावलोकन',
'revision-info'          => ' $2द्वारा $1को रुपमा पुनरावलोकन गर्ने',
'previousrevision'       => '← पुरानो संशोधन',
'nextrevision'           => 'नयाँ संशोधन →',
'currentrevisionlink'    => 'हालको पुनरावलोकन',
'cur'                    => 'cur पृष्ठको लिंक इतिहास',
'next'                   => 'अर्को',
'last'                   => 'पूर्वरुप',
'page_first'             => 'पहिलो',
'page_last'              => 'अन्तिम',
'history-fieldset-title' => 'ब्राउज इतिहास',
'histfirst'              => 'पहिलो',
'histlast'               => 'अन्तिम',
'historysize'            => '({{PLURAL:$1|१ बाइट |$1 बाइटहरु}})',
'historyempty'           => '(खाली)',

# Revision feed
'history-feed-title'          => 'पुनरावलोकन इतिहास',
'history-feed-description'    => 'विकीमा यो पृष्ठको पुनरावलोकन इतिहास',
'history-feed-item-nocomment' => '$1  $2मा',

# Revision deletion
'rev-deleted-comment'        => '(टिप्पणी हटाइयो)',
'rev-deleted-user'           => '(प्रयोगकर्ता नाम हटाइयो)',
'rev-deleted-event'          => '(लग कार्य हटाइयो)',
'rev-delundel'               => 'देखाउने/छुपाउने',
'revisiondelete'             => 'मेटाउने/मेटाएको रद्दगर्ने  पुनरावलोकनहरु',
'revdelete-nooldid-title'    => 'अमान्य पुनरावलोकन लक्ष',
'revdelete-nologtype-title'  => 'कुनै पनि लग प्रकार खुलाइएन',
'revdelete-nologid-title'    => 'अमान्य लग प्रविष्टि',
'revdelete-no-file'          => 'खुलाइएको पृष्ठ अस्तित्वमा छैन',
'revdelete-show-file-submit' => 'हो',
'revdelete-selected'         => "'''[[:$1]]को {{PLURAL:$2|छानिएको संस्करण|छानिएका संस्करणहरु}}  :'''",
'logdelete-selected'         => "'''{{PLURAL:$1|छानिएको लग घटना|छानिएका लग घटनाहरु}}:'''",
'revdelete-legend'           => 'दृष्टि बन्देज मिलाउने',
'revdelete-hide-text'        => 'पुनरावलोकन पाठ लुकाउने',
'revdelete-hide-name'        => 'कार्य र गन्तब्य लुकाउने',
'revdelete-hide-comment'     => 'सम्पादन टिप्पणी लुकाउने',
'revdelete-hide-user'        => 'सम्पादकको प्रयोगकर्ता नाम/IP लुकाउने',
'revdelete-hide-restricted'  => 'प्रवन्धक वा अरुबाट डेटा कम लिने',
'revdelete-suppress'         => 'प्रवन्धक वा अरुबाट डेटा कम लिने',
'revdelete-hide-image'       => 'फाइल कमेन्ट लुकाउने',
'revdelete-unsuppress'       => 'पुनर्सथापित पुनरावलोकनबाट बन्देज हटाउने',
'revdelete-log'              => 'लग टिप्पणी :',
'revdelete-submit'           => 'छानिएको पुनरावलोकन प्रयोग गर्ने',
'revdelete-logentry'         => '[[$1]] को पुनरावलोकन दृष्टि परिवर्तन गरियो',
'logdelete-logentry'         => '[[$1]] को पुनरावलोकन घटना दृष्टि परिवर्तन गरियो',
'revdelete-success'          => "'''पुनरावलोकन दृष्टि सफलतापूर्वक मिलाइयो ।'''",
'revdelete-failure'          => "'''पुनरावलोक दृष्टि मिलाउन सकिएन:'''
$1",
'logdelete-success'          => "'''लग दृष्टि सफलतापूर्वक मिलाइयो ।'''",
'logdelete-failure'          => "'''लग दृष्टि मिलाउन सकिएन :'''
$1",
'revdel-restore'             => 'दृष्टि परिवर्तन गर्ने',
'pagehist'                   => 'पृष्ठको इतिहास',
'deletedhist'                => 'मेटाएका इतिहास',
'revdelete-content'          => 'सामग्री',
'revdelete-summary'          => 'सम्पादन सारांश',
'revdelete-uname'            => 'प्रयोगकर्ताको नाम',
'revdelete-hid'              => ' $1 लुकाउने',
'revdelete-unhid'            => ' $1 नलुकाउने',
'revdelete-log-message'      => '$1  $2 को लागि{{PLURAL:$2|पुनरावलोकन|पुनरावलोकनहरु}}',
'logdelete-log-message'      => '$1 $2को लागि {{PLURAL:$2|घटना|घटनाहरु}}',

# Suppression log
'suppressionlog' => 'कमगरेको लग',

# History merging
'mergehistory'                     => 'पृष्ठ इतिहासहरु जोड्नुहोस्',
'mergehistory-box'                 => 'दुई पृष्ठहरुको पुनरावलोकन जोड्नुहोस् :',
'mergehistory-from'                => 'स्रोत पृष्ठ:',
'mergehistory-into'                => 'गन्तब्य पृष्ठ :',
'mergehistory-list'                => 'जोड्न मिल्ने इतिहास सम्पादन',
'mergehistory-go'                  => 'जोड्न मिल्ने सम्पादनहरु',
'mergehistory-submit'              => 'पुनरावलोकहरु जोड्नुहोस्',
'mergehistory-empty'               => 'कुनै पनि पुनरावलोकनहरु जोड्न मिल्दैन ।',
'mergehistory-no-source'           => 'स्रोत पृष्ठ $1 अस्तित्वमा छैन ।',
'mergehistory-no-destination'      => 'गन्तव्य पृष्ठ $1 अस्तित्वमा छैन ।',
'mergehistory-invalid-source'      => 'स्रोत पृष्ठ मान्य पृष्ठ शिर्षक हुनुपर्ने छ ।',
'mergehistory-invalid-destination' => 'गन्तव्य पृष्ठ मान्य पृष्ठ शिर्षक हुनुपर्ने छ।',
'mergehistory-autocomment'         => ' [[:$1]] लाई [[:$2]] मा जोडियो',
'mergehistory-comment'             => ' [[:$1]] लाई[[:$2]] मा जोडियो : $3',
'mergehistory-same-destination'    => 'स्रोत र गन्तव्य पृष्ठ एउटै हुनसक्दैनन्',
'mergehistory-reason'              => 'कारण :',

# Merge log
'mergelog'           => 'जोडेको लग',
'pagemerge-logentry' => '[[$1]] लाई [[$2]] मा जोडियो  ( $3 सम्म पुनरावलोकन)',
'revertmerge'        => 'नजोड्ने',

# Diffs
'history-title'            => '"$1" को पुनरावलोकन इतिहास',
'difference'               => '(पुनरावलोकनहरुको बीचमा भिन्नता)',
'lineno'                   => 'पंक्ति $1:',
'compareselectedversions'  => 'छानिएका संस्करणहरू दाँज्नुहोस्',
'showhideselectedversions' => 'छानिएका पुनरावलोकनहरु देखाउने/लुकाउने',
'visualcomparison'         => 'दृश्य तुलना',
'wikicodecomparison'       => 'विकीपाठ तुलना',
'editundo'                 => 'रद्द गर्ने',
'diff-multi'               => '({{PLURAL:$1|एक मध्य पुनरावलोकन|$1 मध्य पुनरावलोकनहरू}} नदेखाइएको)',
'diff-movedto'             => '$1 मा सारिएको',
'diff-styleadded'          => '$1 ढाँचा थपिएको',
'diff-added'               => '$1 थपिएको',
'diff-changedto'           => '$1 मा परिवर्तन गरिएको',
'diff-movedoutof'          => '$1 बाट बाहिर सारिएको',
'diff-styleremoved'        => '$1 ढाँचा हटाइएको',
'diff-removed'             => '$1 हटाइएको',
'diff-changedfrom'         => '$1 बाट परिवर्तन गरिएको',
'diff-src'                 => 'स्रोत',
'diff-withdestination'     => 'गन्तब्य $1 मा',
'diff-with'                => '&#32;साथमा $1 $2',
'diff-with-final'          => '&#32;र $1 $2',
'diff-width'               => 'चौडाई',
'diff-height'              => 'उचाई',
'diff-p'                   => "एक '''अनुच्छेद'''",
'diff-blockquote'          => "एक '''कथन'''",
'diff-h1'                  => "एक '''शिर्ष पंक्ति स्तर १'''",
'diff-h2'                  => "एक '''शिर्ष पंक्ति स्तर २'''",
'diff-h3'                  => "एक '''शिर्ष पंक्ति स्तर ३'''",
'diff-h4'                  => "एक ''' शिर्ष पंक्ति स्तर ४'''",
'diff-h5'                  => "एक '''शिर्ष पंक्ति स्तर ५'''",

# Search results
'searchresults'                    => 'खोज नतिजाहरू',
'searchresults-title'              => ' "$1"को लागि खोज नतिजाहरु',
'searchresulttext'                 => ' {{SITENAME}}मा खोज्ने बारे थप जानकारीको लागि,[[{{MediaWiki:Helppage}}|{{int:help}}]] मा हेर्नुहोस् ।',
'searchsubtitle'                   => 'तपाईँले \'\'\'[[:$1]]\'\'\' खोज्नु भएको थियो ([[Special:Prefixindex/$1| "$1"बाट सुरु हुने पृष्ठ ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" मा जोडिने पृष्ठ]])',
'searchsubtitleinvalid'            => "तपाईँले '''$1''' खोज्नुभएको थियो",
'noexactmatch'                     => "'''\"\$1\" शिर्षक भएको पृष्ठ छैन ।'''
तपाई [[:\$1|यस पृष्ठ निर्माण गर्न सक्नुहुन्छ ]]।",
'noexactmatch-nocreate'            => "''' \"\$1\" शिर्षक भएको पृष्ठ छैन।'''",
'notitlematches'                   => 'कुनैपनि पृष्ठको शिर्षक संग मिल्दैन',
'notextmatches'                    => 'अक्षरस् पेज भेटिएन',
'prevn'                            => 'पहिलेको {{PLURAL:$1|$1}}',
'nextn'                            => 'अर्को {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'हेर्नुहोस् ($1) ($2) ($3)',
'searchmenu-legend'                => 'खोज विकल्प',
'searchprofile-images'             => 'मल्टिमिडिया(श्रव्य दृश्य)',
'searchprofile-everything'         => 'सब थोक',
'searchprofile-advanced'           => 'उन्नत',
'searchprofile-articles-tooltip'   => '$1 मा खोज्ने',
'searchprofile-project-tooltip'    => '$1 मा खोज्ने',
'searchprofile-images-tooltip'     => 'फाइलहरु खोज्ने',
'searchprofile-everything-tooltip' => 'सबै सामग्री खोज्ने(वार्तालाप समेत )',
'searchprofile-advanced-tooltip'   => 'अनुकुल नेमस्पेसमा खोज्ने',
'search-result-size'               => '$1 ({{PLURAL:$2|1 शव्द|$2 शव्दहरु}})',
'search-result-score'              => 'मिल्ने :$1%',
'search-redirect'                  => '(जाने $1)',
'search-section'                   => '(खण्ड $1)',
'search-suggest'                   => 'के तपाईको मतलब : $1',
'search-interwiki-caption'         => 'भगिनी आयोजना',
'search-interwiki-default'         => '$1 नतिजाहरु:',
'search-interwiki-more'            => '(धेरै)',
'search-mwsuggest-enabled'         => 'सुझाव सहितको',
'search-mwsuggest-disabled'        => 'सुझाव बाहेकको',
'search-relatedarticle'            => 'सम्बन्धित',
'mwsuggest-disable'                => 'AJAX सुझाव निस्क्रिय पार्नुहोस्',
'searcheverything-enable'          => 'सबै नेमस्पेसेजहरुमा खोज्नुहोस्',
'searchrelated'                    => 'सम्बन्धित',
'searchall'                        => 'सबै',
'showingresults'                   => "देखाउदै  {{PLURAL:$1|'''१''' नतिजा|'''$1''' नतिजाहरु }} , #'''$2''' बाट सुरुहुने ।",
'showingresultsnum'                => "तल देखाउदै  {{PLURAL:$3|'''१''' नतिजा|'''$3''' नतिजाहरु }}, #'''$2''' बाट सुरुहुने ।",
'showingresultstotal'              => "तल देखाउदै {{PLURAL:$4|नतिजा '''$1''' को '''$3'''|नतिजाहरू '''$1 - $2''' को '''$3'''}}",
'showingresultsheader'             => "{{PLURAL:$5|नतिजा '''$1''' को '''$3'''|नतिजाहरु '''$1 - $2''' को'''$3'''}}  ,'''$4''' को लागि",
'nonefound'                        => "'''द्रष्टव्य''': पूर्वनिर्धारित रुपमा केहीमात्र नेमस्पेसेजहरू खोजिन्छ ।त
तपाईँको क्वेरीलाई  ''all:'' राखी सवै(वार्रतालाप , टेम्लेट सहित, इत्यादी)सामग्री खोज्ने गरी मिलाउनुहोस् , ‍नत्र चाहेको नेमस्पेसलाई अगाडि जोड्नुहोस् ।",
'search-nonefound'                 => 'तपाईँको क्वेरीसँग मेल खाने नतिजाहरू भेटिएनन्',
'powersearch'                      => 'उन्नत खोज',
'powersearch-legend'               => 'उन्नत खोज',
'powersearch-ns'                   => 'नेमस्पेसेजहरुमा खोज्ने :',
'powersearch-redir'                => 'रिडाइरेक्टहरू सूचीकृत गर्ने',
'powersearch-field'                => 'को लागि खोज्ने',
'powersearch-togglelabel'          => 'जाँच्ने :',
'powersearch-toggleall'            => 'सबै',
'powersearch-togglenone'           => 'कुनैपनि होइन',
'search-external'                  => 'बाह्य खोज',
'searchdisabled'                   => '{{SITENAME}} खोज निस्कृय पारिएको छ ।
यस समयमा तपाईले Google द्वारा खोज्न सक्नुहुन्छ ।
याद गर्नुहोस् उनीहरुको {{SITENAME}}को सूची सामग्री पुरानो पनि हुनसक्छ ।',

# Quickbar
'qbsettings'               => 'क्विकबार',
'qbsettings-none'          => 'कुनैपनि होइन',
'qbsettings-fixedleft'     => 'देब्रे निश्चित गरिएको',
'qbsettings-fixedright'    => 'दाहिने निश्चित गरिएको',
'qbsettings-floatingleft'  => 'देब्रे तैरने',
'qbsettings-floatingright' => 'दाहिने तैरने',

# Preferences page
'preferences'                   => 'रोजाईहरू',
'mypreferences'                 => 'मेरा अभिरुचिहरू',
'prefs-edits'                   => 'सम्पादन संख्या:',
'prefsnologin'                  => 'प्रवेश (लग ईन) नगरिएको',
'changepassword'                => 'पासवर्ड परिवर्तन गर्नुहोस्',
'prefs-skin'                    => 'काँचुली',
'skin-preview'                  => 'पूर्वालोकन',
'prefs-math'                    => 'गणित',
'datedefault'                   => 'कुनै अभिरुचि छैन',
'prefs-datetime'                => 'मिति र समय',
'prefs-personal'                => 'प्रयोगकर्ताको विवरण',
'prefs-rc'                      => 'नयाँ परिवर्तनहरु',
'prefs-watchlist'               => 'अवलोकन पृष्ठ',
'prefs-watchlist-days'          => 'निगरानी सूचीमा देखाउन दिनहरु:',
'prefs-watchlist-days-max'      => 'धेरैमा ७ दिनहरु',
'prefs-watchlist-edits'         => 'उच्चतम परिवर्तन संख्या बढाइएको निगरानी सूचीमा  देखाउनको लागि :',
'prefs-watchlist-edits-max'     => 'उच्चतम संख्या : १०००',
'prefs-watchlist-token'         => 'निगरानी सूची टोकन',
'prefs-misc'                    => 'साधारण',
'prefs-resetpass'               => 'प्रवेशशव्द परिवर्रतन',
'prefs-email'                   => 'इमेल  विकल्पहरु',
'prefs-rendering'               => 'स्वरुप',
'saveprefs'                     => 'संग्रह',
'resetprefs'                    => 'संग्रह नगरिएका परिवर्तनहरु सफागर्ने',
'restoreprefs'                  => 'सबै पूर्वनिर्धारित स्थिती कायम गर्ने',
'prefs-editing'                 => 'सम्पादन',
'prefs-edit-boxsize'            => 'सम्पादन झ्यालको आकार',
'columns'                       => 'स्तम्भहरु :',
'searchresultshead'             => 'खोज',
'resultsperpage'                => 'प्रति पृष्ठ खोज मेल(hits):',
'contextlines'                  => 'प्रति पंक्ति  मेल :',
'contextchars'                  => 'प्रति पंक्ति सन्दर्भ :',
'recentchangesdays'             => 'हालको परिवर्तनमा देखाउने दिनहरु:',
'recentchangesdays-max'         => 'उच्चतम $1 {{PLURAL:$1|दिन|दिनहरु}}',
'recentchangescount'            => 'पूर्व निर्धारितरुपमा देखाउनुपर्ने सम्पादनहरू :',
'prefs-help-recentchangescount' => 'यसमा हालका परिवर्रनहरु , पृष्ठ इतिहासहरु , र सुचीहरू समाविष्ठ छन् ।',
'prefs-help-watchlist-token'    => 'यो विधामा गोप्य संकेत राख्नाले तपाईँको निगरानीसूचीको RSS फिड शृजन हुने छ ।
संकेत थाहपाउने जो कसैले तपाईको निगरानी सूची पढ्न सक्ने भएकोले , सुरक्षित मान छान्नुहोला ।
यहाँ जथाभावीरुपमा-शृजना गरिएको तपाईले प्रयोग गर्ने सक्नुहुने मान छ : $1',
'savedprefs'                    => 'तपाँईका अभिरुचिहरू सङ्ग्रहित भयो।',
'timezonelegend'                => 'समय क्षेत्र :',
'localtime'                     => 'स्थानिय समय:',
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
'userrights'                  => 'प्रयोगकर्ता अधिकार व्यवस्थापन',
'userrights-lookup-user'      => 'प्रयोगकर्ता समूह व्यवस्थापन गर्नुहोस',
'userrights-user-editname'    => 'प्रयोगरकर्ता नाम प्रविष्ठ गर्नुहोस :',
'editusergroup'               => 'प्रयोगकर्ता समूह सम्पादन गर्नुहोस्',
'userrights-editusergroup'    => 'प्रयोगकर्रा समूह सम्पादन गर्नुहोस्',
'saveusergroups'              => 'प्रयोगकर्ता समूहरू संग्रह गर्नुहोस्',
'userrights-groupsmember'     => 'को सदस्य:',
'userrights-reason'           => 'परिवर्तनका कारणहरू :',
'userrights-changeable-col'   => 'परिवर्तन गर्न सकिने समूहहरु',
'userrights-unchangeable-col' => 'तपाईले परिवर्तन गर्न सक्नुनहुने समूहहरु',

# Groups
'group'               => 'समूह :',
'group-user'          => 'प्रयोगकर्ताहरु',
'group-autoconfirmed' => 'स्वत निश्चित गरिएका प्रयोगकर्ताहरु',
'group-bot'           => 'बोटहरु',
'group-sysop'         => 'प्रवन्धकहरू',
'group-bureaucrat'    => 'कुटनीतिज्ञहरु',
'group-all'           => '(सबै)',

'group-user-member'          => 'प्रयोगकर्ता',
'group-autoconfirmed-member' => 'स्वत: निर्धारित प्रयोगकर्ता',
'group-bot-member'           => 'बोट',
'group-sysop-member'         => 'सिसप',
'group-bureaucrat-member'    => 'कुटनीतिज्ञ',

'grouppage-sysop' => '{{ns:project}}: प्रशासकहरु',

# Rights
'right-read'               => 'पृष्ठहरू पढ्नुहोस्',
'right-edit'               => 'पृष्ठहरु सम्पादन गर्नुहोस्',
'right-createtalk'         => 'छलफल पृष्ठ शृजना गर्नुहोस्',
'right-createaccount'      => 'नयाँ खाताहरु शृजना गर्नुहोस ।',
'right-minoredit'          => 'सम्पादनलाई सामान्य चिनो लगाउने',
'right-move'               => 'पृष्ठहरु सार्ने',
'right-move-subpages'      => 'तिनीहरुको सह-पृष्ठसहित पृष्ठहरु सार्ने',
'right-move-rootuserpages' => 'मूल(root) प्रयोगकर्ताको पृष्ठहरु सार्ने',
'right-movefile'           => 'फाइलहरु सार्ने',
'right-upload'             => 'फाइलहरु उर्ध्वभरण गर्ने',
'right-reupload'           => 'रहेका फाइललाई अधिलेखन गर्ने',
'right-upload_by_url'      => 'URL बाट फाइल उर्ध्वभरण गर्ने',
'right-autoconfirmed'      => 'अर्ध-सुरक्षित पृष्ठहरु सम्पादन गर्ने',
'right-writeapi'           => 'लेखन API प्रयोग गर्ने',
'right-delete'             => 'पृष्ठहरु मेट्ने',
'right-bigdelete'          => 'लामो इतिहासहरु भएको पृष्ठहरु मेट्ने',
'right-browsearchive'      => 'मेटिएको पानाहरु खोज्ने',
'right-undelete'           => 'मेटेको पृष्ठ फिर्तागर्ने',

# User rights log
'rightslog' => 'प्रयोगकर्ता अधिकार लग',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'यो पृष्ट सम्पादन गर्ने',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|परिवर्तन|परिवर्तनहरु}}',
'recentchanges'                  => 'नयाँ परिवर्तनहरु',
'recentchanges-legend'           => 'हालैको परिवर्रन विकल्पहरु',
'recentchanges-feed-description' => 'यो फिडमा रहेको विकीको सवैभन्दा अन्तिम परिवर्तनहरुको जानकारी राख्नुहोस्',
'rclistfrom'                     => '$1 देखिका नयाँ परिवर्तनहरू देखाउनु',
'rcshowhideminor'                => '$1 सामान्य सम्पादन',
'rcshowhidebots'                 => '$1 बोटहरू',
'rcshowhideliu'                  => '$1 प्रवेश गरेका प्रयोगकर्ताहरु',
'rcshowhideanons'                => '$1 अज्ञात प्रयोगकर्ता',
'rcshowhidemine'                 => '$1 मेरो सम्पादनहरु',
'rclinks'                        => 'पछिल्ला $1 परिवर्तनहरु पछिल्ला $2 दिनहरुमा<br />$3',
'diff'                           => 'भिन्न',
'hist'                           => 'इतिहास',
'hide'                           => 'लुकाउनुहोस्',
'show'                           => 'देखाउनुहोस्',
'minoreditletter'                => 'सा',
'newpageletter'                  => 'न',
'boteditletter'                  => 'बो',
'rc_categories_any'              => 'कुनै',
'rc-enhanced-expand'             => 'जानकारी देखाउने( जाभा स्क्रिप्ट चाहिने)',
'rc-enhanced-hide'               => 'जानकारी लुकाउने',

# Recent changes linked
'recentchangeslinked'         => 'संबन्धित परिवर्तनहरु',
'recentchangeslinked-feed'    => 'संबन्धित परिवर्तनहरु',
'recentchangeslinked-toolbox' => 'संबन्धित परिवर्तनहरु',
'recentchangeslinked-title'   => '"$1"सम्वन्धित परिवर्तनसँग',
'recentchangeslinked-summary' => "यो खुलाईएको पृष्ठसँग जोडिएका पृष्ठहरुमा गरिएको परिवर्तनहरुको सुची हो(या खुलाइएको श्रेणी )[[Special:Watchlist|तपाईँको निगरानी सूची]] का पृष्ठहरु '''बोल्ड'''.",
'recentchangeslinked-page'    => 'पृष्ठ नाम:',
'recentchangeslinked-to'      => 'यसको सट्ता यो पृष्ठसँग जोडिएका पृष्ठहरुको परिवर्तन देखाउने',

# Upload
'upload'            => 'फाइल अपलोड',
'uploadbtn'         => 'फाइल अपलोड गर्नु',
'uploadnologin'     => 'प्रवेश (लग ईन) नगरिएको',
'uploadlogpage'     => 'उर्ध्वभरण(upload) लग',
'filename'          => 'फाइलनाम',
'filedesc'          => 'सारांश',
'fileuploadsummary' => 'सारांश:',
'filestatus'        => 'लेखाधिकार स्थिति:',
'filesource'        => 'स्रोत:',
'uploadedimage'     => 'उर्ध्वभरण(upload) गरियो  "[[$1]]"',
'watchthisupload'   => 'यो पृष्ठ निगरानी गर्नुहोस्',

'nolicense' => 'केहिपनि छानिएन',

# Special:ListFiles
'listfiles_date'        => 'मिति',
'listfiles_name'        => 'नाम',
'listfiles_user'        => 'प्रयोगकर्ता',
'listfiles_description' => 'वर्णन',

# File description page
'file-anchor-link'          => 'फाईल',
'filehist'                  => 'फाइल इतिहास',
'filehist-help'             => 'मिति/समय मा क्लिक गरेर त्यससमयमा यो फाइल कस्तो थियो भनेर हेर्न सकिन्छ ।',
'filehist-current'          => 'हालको',
'filehist-datetime'         => 'मिति/समय',
'filehist-thumb'            => 'थम्बनेल',
'filehist-thumbtext'        => 'थम्बनेल $1 सस्करणको रुपमा',
'filehist-user'             => 'प्रयोगकर्ता',
'filehist-dimensions'       => 'आकारहरू',
'filehist-comment'          => 'टिप्पणी',
'imagelinks'                => 'फाइल लिंकहरू',
'linkstoimage'              => 'यस फाइलमा निम्न{{PLURAL:$1|पृष्ठ जोडिन्छ|$1 पृष्ठहरु जोडिन्छन}}:',
'nolinkstoimage'            => 'यो फाईलसंग लिंकभएको कुनै पृष्ठ छैन.',
'uploadnewversion-linktext' => 'यो फाइलको नयाँ संस्करण उर्ध्वभरण गर्नुहोस् ।',

# MIME search
'download' => 'डाउनलोड',

# Random page
'randompage' => 'कुनै एक लेख',

# Statistics
'statistics' => 'तथ्यांक',

'brokenredirects'     => 'टुटेका रिडाइरेक्टहरू',
'brokenredirectstext' => 'तलका लिङ्कहरु ले हुदै नभएका पृष्ठहररसँग जोडिन्छन्:',

'withoutinterwiki-summary' => 'यी पानाहरूले अन्य भाषाका संस्करणहरूमा संबन्ध राखेका छैनन्:',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|बाइट|बाइटहरू}}',
'ncategories'       => '$1 {{PLURAL:$1|श्रेणी|श्रेणीहरू}}',
'nlinks'            => '$1 {{PLURAL:$1|लिंक|लिंकहरु}}',
'nmembers'          => '$1 {{PLURAL:$1|सदस्य|सदस्यहरू}}',
'nrevisions'        => '$1 {{PLURAL:$1|पुनरावलोकन|पुनरावलोकनहरु}}',
'nviews'            => '$1 {{PLURAL:$1|अवलोकन|अवलोकनहरु}}',
'specialpage-empty' => 'यो पृष्ठ खाली छ।',
'lonelypages'       => 'अनाथ पृष्ठहरु',
'popularpages'      => 'धेरै रूचाईएका पृष्ठहरू',
'wantedpages'       => 'खोजिएका पृष्ठहरु',
'mostlinked'        => 'सबैभन्दा बढि लिंक भएको पृष्ठ',
'mostcategories'    => 'सबैभन्दा धेरै श्रेणीहरू भएका लेखहरू',
'mostimages'        => 'सबैभन्दा बढि लिंक भएको चित्र',
'mostrevisions'     => 'सबैभन्दा बढी संशोधित लेखहरू',
'prefixindex'       => 'प्रिफिक्स सहितका पृष्ठहरु',
'shortpages'        => 'छोटा पृष्ठहरु',
'protectedpages'    => 'संरक्षित पृष्ठहरु',
'listusers'         => 'प्रयोगकर्ता सूची',
'newpages'          => 'नयाँ पृष्ठहरू',
'newpages-username' => 'युजरनेम:',
'ancientpages'      => 'सबैभन्दा पुराना पृष्ठहरु',
'move'              => 'सार्ने',
'movethispage'      => 'यो पृष्ठ सार्नुहोस्',
'notargettitle'     => 'कुनैपनि निसाना(टारगेट) छैन',
'pager-newer-n'     => '{{PLURAL:$1|नयाँ १|नयाँ $1}}',
'pager-older-n'     => '{{PLURAL:$1|पुरानो १|पुरानो $1}}',

# Book sources
'booksources'               => 'किताबका श्रोतहरु',
'booksources-search-legend' => 'किताबका श्रोतहरु खोज्ने',
'booksources-go'            => 'जाउ',

# Special:Log
'specialloguserlabel'  => 'प्रयोगकर्ता:',
'speciallogtitlelabel' => 'शिर्षक:',
'log'                  => 'लगहरु',

# Special:AllPages
'allpages'       => 'सबै पृष्ठहरु',
'alphaindexline' => '$1 लाई $2 मा',
'nextpage'       => 'अर्को पृष्ठ ($1)',
'prevpage'       => 'पहिलो पृष्ठ ($1)',
'allpagesfrom'   => 'यहाँदेखि शुरु हुने पृष्ठहरु देखाउनुहोस्:',
'allpagesto'     => 'निम्नमा अन्तहुने पृष्ठहरु देखाउने:',
'allarticles'    => 'सबै लेखहरु',
'allpagesprev'   => 'अघिल्लो',
'allpagesnext'   => 'अर्को',
'allpagessubmit' => 'जानुहोस्',

# Special:Categories
'categories' => 'श्रेणीहरू',

# Special:LinkSearch
'linksearch'    => 'बाह्य लिंक',
'linksearch-ns' => 'नेमस्पेस:',
'linksearch-ok' => 'खोज्नुहोस्',

# Special:ListUsers
'listusers-submit' => 'देखाउनुहोस्',

# Special:Log/newusers
'newuserlogpage'           => 'प्रयोगकर्ता श्रृजना लग',
'newuserlog-create-entry'  => 'नयाँ प्रयोगकर्ता',
'newuserlog-create2-entry' => 'नयाँ खाता $1 खोलियो',

# Special:ListGroupRights
'listgrouprights-members' => '(सदस्यहरुको सूची)',

# E-mail user
'mailnologin'     => 'ईमेल पठाउने ठेगाना नै भएन ।',
'mailnologintext' => 'तपाईले अरु प्रयोगकर्ताहरुलाई ईमेल पठाउनको लागी आफु पहिले [[Special:UserLogin|प्रवेश(लगइन)गरेको]] हुनुपर्छ र [[Special:Preferences|आफ्नो रोजाइहरुमा]] यौटा वैध ईमेल ठेगाना भएको हुनुपर्छ।',
'emailuser'       => 'यो प्रयोगकर्तालाई ई-मेल पठाउनुहोस्',
'emailpage'       => 'प्रयोगकर्तालाई इमेल गर्नुहोस्',
'noemailtitle'    => 'ईमेल ठेगाना नभएको',
'emailsubject'    => 'विषय:',
'emailmessage'    => 'सन्देश:',
'emailsend'       => 'पठाउनुहोस्',
'emailsent'       => 'इमेल पठाईयो',

# Watchlist
'watchlist'            => 'मेरो अवलोकन',
'mywatchlist'          => 'मेरो अवलोकनसूची',
'watchlistfor'         => "('''$1''' को लागि)",
'nowatchlist'          => 'तपाईको अवलोकन(वाचलिस्ट)मा कुनैपनि चिज छैन।',
'watchnologin'         => 'प्रवेश (लग ईन) नगरिएको',
'watchnologintext'     => 'आफ्नो अवलोकनलाइ परिवर्तन गर्नको लागि त तपाइ यसमा [[Special:UserLogin|प्रवेश(लगइन)]] गर्नुपर्छ।',
'addedwatch'           => 'अवलोकनसूची मा थपियो',
'removedwatch'         => 'निगरानी सूचीबाट हटाइयो',
'removedwatchtext'     => 'पृष्ठ "[[:$1]]" [[Special:Watchlist|तपाईको निगरानी सूची]]बाट हटाइएको छ।',
'watch'                => 'अवलोकन',
'watchthispage'        => 'यो पृष्ठ अवलोकन गर्नुहोस्',
'unwatch'              => 'निगरानीबाट हटाउने',
'notanarticle'         => 'सामाग्री सहितको पेज हैन',
'watchlist-details'    => 'तपाईको निगरानी सूचीमा रहेका{{PLURAL:$1|$1 पृष्ठ|$1 पृष्ठहरु}}वार्तालापमा पृष्ठमा गनिएका छैनन् ।',
'wlheader-enotif'      => '* ईमेलद्वारा जानकारी गराउने तरिका enable गरियो ।',
'wlheader-showupdated' => "* तपाइले पछिल्लो पल्ट भ्रमण गरेपछि परिवर्तन भएका पृष्ठहरूलाई '''गाढा''' गरेर देखाइएको छ ।",
'wlshowlast'           => 'पछिल्ला $2 दिनहरूका $3 $1 घण्टाहरूका देखाउनुहोस्',
'watchlist-options'    => 'निगरानि सूची विकल्प',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'निगरानी गर्दै...',
'unwatching' => 'निगरानीबाट हटाउँदै...',

'enotif_newpagetext' => 'यो नयाँ पृष्ठ हो।',
'changed'            => 'परिवर्तन भइसकेको',

# Delete
'deletepage'            => 'पृष्ठ मेट्नुहोस्',
'excontent'             => "लेख थियो: '$1'",
'historywarning'        => 'खबरदारी: तपाईंले मेटाउन लाग्नुभएको पृष्ठको इतिहास छ:',
'actioncomplete'        => 'काम सकियो',
'deletedtext'           => '"<nowiki>$1</nowiki>" मेटिएको छ। 
हालै हटाइएको सूची $2 मा हेर्नुहोस् ।',
'deletedarticle'        => '"[[$1]]" मेटियो',
'dellogpage'            => 'मेटाएको लग',
'reverted'              => 'अघिल्लो संशोधनको स्थितिमा फर्काइयो',
'deletecomment'         => 'मेट्नुको कारण:',
'deleteotherreason'     => 'अरू/थप कारणहरू :',
'deletereasonotherlist' => 'अरु कारण',

# Rollback
'rollbacklink' => 'पहिलेको रुपमा फर्काउने',

# Protect
'protectlogpage'              => 'सुरक्षण लग',
'protectedarticle'            => '"[[$1]]" लाई सुरक्षित गरियो',
'modifiedarticleprotection'   => ' "[[$1]]"को सुरक्षा स्तर परिवर्तन गरियो',
'prot_1movedto2'              => '[[$1]] लाई [[$2]]मा सारियो',
'protectcomment'              => 'कारण:',
'protectexpiry'               => 'सकिने:',
'protect_expiry_invalid'      => 'सकिने समयावधि अमान्य ।',
'protect_expiry_old'          => 'सकिने समय बिगतमा छ ।',
'protect-unchain'             => 'सार्ने अनुमतिलाई खोल्ने',
'protect-text'                => "तपाईँ यो पृष्ठको यहाँ'''<nowiki>$1</nowiki>''' सुरक्षा स्तर परिवर्तन गर्न र हेर्न सक्नुहुन्छ ।",
'protect-locked-access'       => "तपाईँको खातालाई पृष्ठको सुरक्ष स्तरहरू परिवर्तन गर्ने अनुमति छैन ।
'''$1''पृष्ठको हालको स्थिती  निम्न छ :",
'protect-default'             => 'सबै प्रयोगकर्ताहरुलाई अनुमति दिने',
'protect-fallback'            => '"$1" अनुमति चाहिन्छ',
'protect-level-autoconfirmed' => 'नयाँ तथा दर्ता नभएका प्रयोगकर्ताहरुलाई निषेध गर्ने',
'protect-level-sysop'         => 'सिस्अपहरू मात्र',
'protect-summary-cascade'     => 'लाममा राख्ने',
'protect-expiring'            => '$1 (UTC) मा सकिने छ ।',
'protect-cascade'             => 'यो पृष्ठमा संलग्न सुरक्षित पृष्ठहरु(लामबद्द सुरक्षा)',
'protect-cantedit'            => 'तपाईँ यस पृष्ठको सुरक्षा स्तर परिवर्तन गर्न सक्नुहुन्न , किनकी तपाईँलाई यसको सम्पादनको अनुमति छैन ।',
'restriction-type'            => 'अनुमति:',
'restriction-level'           => 'सिमितता स्तर:',

# Restrictions (nouns)
'restriction-edit' => 'परिवर्तन्',
'restriction-move' => 'सार्ने',

# Undelete
'viewdeletedpage'  => 'मेटिएका पृष्ठहरू हेर्नुहोस्',
'undeletelink'     => 'हेर्ने/पूर्वरुपमा फर्काउने',
'undeletedarticle' => '"[[$1]]" मा फर्काइयो',

# Namespace form on various pages
'namespace'      => 'नेमस्पेस:',
'invert'         => 'रोजाइ उल्टाउने',
'blanknamespace' => '(मुख्य)',

# Contributions
'contributions'       => 'प्रयोगकर्ताका योगदानहरू',
'contributions-title' => '$1को प्रयोगकर्ता योगदानहरु',
'mycontris'           => 'मेरो योगदान',
'contribsub2'         => ' $1 ($2)को लागि',
'uctop'               => '(माथि)',
'month'               => 'महिना देखि (र पहिले):',
'year'                => 'वर्ष देखि( र पहिले):',

'sp-contributions-newbies'  => 'नयाँ खाताको लागि मात्र योगदानहरु देखाउने',
'sp-contributions-blocklog' => 'रोकावट लग',
'sp-contributions-search'   => 'योगदानहरु खोज्नुहोस्',
'sp-contributions-username' => 'IP ठेगाना वा प्रयोगकर्ता नाम :',
'sp-contributions-submit'   => 'खोज',

# What links here
'whatlinkshere'            => 'यहाँ के जोडिन्छ',
'whatlinkshere-title'      => '$1 सँग जोडिएका पानाहरू',
'whatlinkshere-page'       => 'पृष्ठ:',
'linkshere'                => "निम्न पृष्ठहरु '''[[:$1]]''' मा जोडिन्छ :",
'isredirect'               => 'रिडाइरेक्ट पृष्ठ',
'istemplate'               => 'पारदर्शिता',
'isimage'                  => 'तस्विर लिंक',
'whatlinkshere-prev'       => '{{PLURAL:$1|पहिलो|पहिलो $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|अर्को|अर्को $1}}',
'whatlinkshere-links'      => '← लिंकहरु',
'whatlinkshere-hideredirs' => '$1 रिडाइरेक्टहुन्छ',
'whatlinkshere-hidetrans'  => '$1 पारदर्शन',
'whatlinkshere-hidelinks'  => '$1 लिंकहरु',
'whatlinkshere-filters'    => 'फिल्टरहरू',

# Block/unblock
'blockip'                      => 'प्रयोगकर्तालाइ निषेध गर्नुहोस',
'ipaddress'                    => 'आई पी ठेगाना',
'ipboptions'                   => '२ घण्टाहरु:2 hours,१ दिन :1 day,३ दिनहरु:3 days,१ हप्ता:1 week,२ हप्ताहरु:2 weeks,१ महिना:1 month,३ महिनाहरु:3 months,६ महिनाहरु:6 months,१ वर्ष:1 year,अनगिन्ती:infinite',
'ipbotheroption'               => 'अन्य',
'ipbotherreason'               => 'अन्य / थप कारणहरु:',
'ipbhidename'                  => 'प्रयोगकर्ताको नाम सम्पादनबाट र सूचीबाट हटाउने',
'ipbwatchuser'                 => 'यो प्रयोगकर्ताको  प्रयपोगकर्ता र वार्तलाप पृष्ठ हेर्नुहोस्',
'ipballowusertalk'             => 'निषेधित हुँदा पनि यो प्रयोगकर्ताको आफ्नो वार्तालाप पृष्ठ सम्पादन गर्न दिने',
'ipb-change-block'             => 'निम्म स्थितीमा प्रयोगकर्तालाई पुन: निषेध गर्ने',
'badipaddress'                 => 'अमान्य IP ठेगाना',
'blockipsuccesssub'            => 'निषेधकार्य सफल भयो',
'blockipsuccesstext'           => '[[Special:Contributions/$1|$1]] निषेधगरिएको छ।<br />
पुनरावलकोनको लागि [[Special:IPBlockList|IP निषेध सूची]] हेर्नहोस् ।',
'ipb-edit-dropdown'            => 'निषेध कारण सम्पादन गर्नुहोस्',
'ipb-unblock-addr'             => '$1 निषेध खारेज गर्ने',
'ipb-unblock'                  => 'प्रयोगकर्ता वा IP माथिको निषेध खारेज गर्ने',
'ipb-blocklist-addr'           => '$1माथि रहेका निषेधहरु',
'ipb-blocklist'                => 'हाल रहेका निषेधहरु हेर्नुहोस्',
'ipb-blocklist-contribs'       => '$1 को लागि योगदान',
'unblockip'                    => 'प्रयोगकर्ताको निषेध खारेज गर्नुहोस्',
'ipblocklist'                  => 'निषेधित IP ठेगानाहरु र प्रयोगकर्ता नामहरु',
'ipblocklist-sh-addressblocks' => 'एक $1  IP निषेध',
'ipblocklist-submit'           => 'खोज्नुहोस्',
'blocklistline'                => '$1, $2 द्वारा रोकियो $3 ($4)',
'infiniteblock'                => 'अनगिन्ती',
'expiringblock'                => ' सकिने $1 ,$2 मा',
'anononlyblock'                => 'anon. हरु मात्र',
'noautoblockblock'             => 'स्वत: निषेध निस्क्रिय गरिएको',
'createaccountblock'           => 'खाता खोल्न बन्देज गरिएको',
'emailblock'                   => 'इमेल बन्देज गरिएको',
'blocklist-nousertalk'         => 'वार्तालाप पृष्ठ सम्पादन गर्न सकिएन',
'ipblocklist-empty'            => 'निषेध सूची खाली छ ।',
'ipblocklist-no-results'       => 'अनुरोध गरिएको प्रयोगकर्तानाम  वा IP बन्देज गरिएको छैन ।',
'blocklink'                    => 'रोक्नुहोस्',
'unblocklink'                  => 'रोक फुकुवा गर्ने',
'change-blocklink'             => 'ढिका परिवर्तन गर्नुहोस्',
'contribslink'                 => 'योगदानहरु',
'autoblocker'                  => 'स्वत: बन्देज गरिएको किनकी IP ठेगाना  "[[User:$1|$1]]" द्वारा प्रयोग गरिएकोले .
$1को बन्देजको कारण : "$2" हो',
'blocklogpage'                 => 'निषेध सूची',
'blocklog-fulllog'             => 'पूर्ण बन्देज सूची',
'blocklogentry'                => ' [[$1]]लाई $2 $3 समयसम्म को लागि निषेध गरिएको छ',
'reblock-logentry'             => '$2 $3 मा सकिने गरि  [[$1]] को निषेध स्थिती परिवर्तन गरिएको छ ।',
'unblocklogentry'              => 'अनिषेधित गरियो $1',
'block-log-flags-anononly'     => 'अज्ञात प्रयोगकर्तामात्र',
'block-log-flags-nocreate'     => 'खाता खोल्न निस्क्रिय पारिएको',
'proxyblocksuccess'            => 'सकियो.',

# Developer tools
'lockconfirm'         => 'हो, म साँच्चिकै डेटाबेस थुन्न चाहन्छु।',
'unlockconfirm'       => 'हो , म साँच्चै  डेटाबेसको ताल्चा खोल्न चाहन्छु ।',
'lockbtn'             => 'डेटाबेस थुनिदिनुस्',
'unlockbtn'           => 'डाटाबेस अनलक गर्नुहोस्',
'locknoconfirm'       => 'मैले ठोकुवा गर्ने सन्दुकमा चिनो लगाएको छैन ।',
'lockdbsuccesssub'    => 'डेटाबेस ताल्चा मार्नेकाम सफल भयो',
'unlockdbsuccesssub'  => 'डेटाबेस ताल्चा हटाइयो',
'unlockdbsuccesstext' => 'डेटाबेसको ताल्चा खोलियो ।',
'databasenotlocked'   => 'डेटाबेस ताल्चा मारिइएको छैन',

# Move page
'move-page'                 => ' $1 लाई सार्ने',
'move-page-legend'          => 'पृष्ठ सार्नुहोस्',
'movearticle'               => 'पृष्ठ सार्नुहोस्',
'movenologin'               => 'प्रवेश (लग ईन) नगरिएको',
'newtitle'                  => 'नयाँ शिर्षकमा :',
'move-watch'                => 'यो पृष्ठ निगरानीमा राख्नुहोस्',
'movepagebtn'               => 'पृष्ठ सार्नुहोस्',
'pagemovedsub'              => 'सार्ने काम सफल भयो',
'movepage-moved'            => '<big>\'\'\'"$1" लाई "$2"मा सारिएको छ\'\'\'</big>',
'movepage-moved-redirect'   => 'रिडाइरेक्ट पृष्ठ शृजना गरियो',
'movepage-moved-noredirect' => 'रिडाइरेक्ट पृष',
'articleexists'             => 'यस नामको पृष्ठ पहिले देखि नै रहेको ,या तपाईँले छान्नु भएको नाम अमान्य छ।
कृपया अर्कै नाम छान्नुहोस् ।',
'movedto'                   => 'मा सारियो',
'movetalk'                  => 'सम्बन्धित वार्ता पृष्ठ',
'move-subpages'             => 'सहायक पृष्ठहरु सार्ने($1 सम्मको)',
'move-talk-subpages'        => 'वार्तालाप पृष्ठको सह-पृष्ठहरु सार्ने($1 सम्मको )',
'movepage-page-exists'      => '$1 पृष्ठ पहिले देखि नै रहेको छ र स्वत: अधिलेखन गर्न सकिएन ।',
'movepage-page-moved'       => ' $1 पृष्ठलाई $2 मा सारियो ।',
'movepage-page-unmoved'     => ' $1 पृष्ठलाई $2 मा सार्न सकिएन ।',
'1movedto2'                 => '[[$1]] लाई [[$2]]मा सारियो',
'1movedto2_redir'           => 'रिडाइरेक्टमा [[$1]]लाई [[$2]]मा सारियो',
'movelogpage'               => 'लग सार्ने',
'movereason'                => 'कारण',
'revertmove'                => 'पहिलेको रुपमा फर्काउने',
'delete_and_move_confirm'   => 'हो, पृष्ठ मेट्नुहोस्',

# Export
'export'            => 'पृष्ठ निर्यात गर्ने',
'exportcuronly'     => 'हालको संस्करण मात्र थप्ने ,पूरा इतिहास हैन',
'export-submit'     => 'निर्यात',
'export-addcattext' => 'श्रेणीबाट पृष्ठ थप्ने :',
'export-addcat'     => 'थप्ने',
'export-addnstext'  => 'नेमस्पेसबाट पृष्ठ थप्ने :',
'export-addns'      => 'थप्ने',
'export-download'   => 'संग्रह गर्ने',

# Namespace 8 related
'allmessages'     => 'सिस्टम सन्देशहरू',
'allmessagesname' => 'नाम',
'allmessagestext' => 'यो मिडियाविकि नेमस्पेसमा पाइने सिस्टम सन्देशहरूको सूची हो।',

# Thumbnails
'thumbnail-more' => 'ठूलो बनाउने',

# Special:Import
'import'                  => 'पृष्ठहरु आयात गर्नुहोस्',
'import-interwiki-submit' => 'आयात',
'import-comment'          => 'टिप्पणी :',
'importstart'             => 'पृष्ठ आयात गरिँदै...',
'importnotext'            => 'पाठ नभएको या  खाली',
'importsuccess'           => 'आयात सम्पन्न भयो!',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'तपाईको प्रयोगकर्ता पृष्ठ',
'tooltip-pt-mytalk'               => 'तपाईको वार्ता पृष्ठ',
'tooltip-pt-preferences'          => 'मेरा अभिरुचिहरू (प्रेफरेन्सेस्‌हरू)',
'tooltip-pt-watchlist'            => 'पृष्ठहरूको सूची जसका परिवर्तनहरूलाई तपाईँले निगरानी गरिरहनु भएको छ',
'tooltip-pt-mycontris'            => 'तपाईको योगदानको सूची',
'tooltip-pt-login'                => 'तपाईँलाई प्रवेशगर्न सुझाव दिइन्छ ; तर यो जरुरी भने छैन',
'tooltip-pt-anonlogin'            => 'तपाईँलाई लग-इन गर्न प्रोत्साहन गरिन्छ, तर यो अनिवार्य चाँही होइन।',
'tooltip-pt-logout'               => 'निर्गमन (लग आउट) गर्नुहोस्',
'tooltip-ca-talk'                 => 'सामग्री पृष्ठकोबारेमा छलफल',
'tooltip-ca-edit'                 => 'तपाईँले यो पृष्ठ सम्पादन गर्न सक्नुहुन्छ ।
कृपया संग्रह ‍पहिले पूर्वावलोकन बटन प्रयोग गर्नुहोला ।',
'tooltip-ca-addsection'           => 'नयाँ खण्ड सुरूगर्नुहोस्',
'tooltip-ca-viewsource'           => 'यो पृष्ठ सुरक्षित गरिएको छ। यसको श्रोत हेर्न सक्नुहुन्छ।',
'tooltip-ca-history'              => 'यस पृष्ठको पहिलेका पुनरावलोकनहरु',
'tooltip-ca-protect'              => 'यो पृष्ठलाई संरक्षित गर्नुहोस्',
'tooltip-ca-delete'               => 'यो पृष्ठ मेटाउनुहोस्',
'tooltip-ca-undelete'             => 'मेटिपको भए पनि यो पृष्ठको सम्पादनहरु पुन:प्राप्त गर्नुहोस्',
'tooltip-ca-move'                 => 'यो पृष्ठलाई सार्नुहोस्',
'tooltip-ca-watch'                => 'यो पृष्ठलाई तपाईँको अवलोकनसूचीमा थप्नुहोस्',
'tooltip-ca-unwatch'              => 'यो पृष्ठलाई तपाईँको अवलोकनसूचीबाट हटाउनुहोस्',
'tooltip-search'                  => '{{SITENAME}} मा खोज्नुहोस्',
'tooltip-search-go'               => 'यदि यो नामको पृष्ठ रहेको छ भने त्यसमा जाने',
'tooltip-search-fulltext'         => 'यो पाठको लागि पृष्ठहरु खोज्नुहोस्',
'tooltip-p-logo'                  => 'मुख्य पृष्ठ',
'tooltip-n-mainpage'              => 'मुख्य पृष्ठमा जानुहोस्',
'tooltip-n-portal'                => 'आयोजनाका बारेमा , तपाईँ के गर्न सक्नुहुन्छ , सामग्री कहाँ भेट्टाउने',
'tooltip-n-currentevents'         => 'हालैको घटनाको बारेमा पृष्ठभूमि जानकारी पत्तालगाउनुहोस्',
'tooltip-n-recentchanges'         => 'विकीमा गरिएका हालैका परिवर्तनहरुको सूची',
'tooltip-n-randompage'            => 'जुन कुनै पृष्ठ खोल्ने',
'tooltip-n-help'                  => 'पत्तालगाउनु पर्ने स्थान',
'tooltip-t-whatlinkshere'         => 'यहाँ लिङ्क गरिएका विकी पृष्ठहरुको सूची',
'tooltip-t-recentchangeslinked'   => 'यस पृष्ठमा जोडिएका पृष्ठहरुमा हालैको परिवर्तन',
'tooltip-feed-rss'                => 'यो पृष्ठको लागि RSS फिड',
'tooltip-feed-atom'               => 'यो पृष्ठको लागि Atom फिड',
'tooltip-t-contributions'         => 'यस प्रयोगकर्ताका योगदानहरूको सूची हेर्नुहोस्',
'tooltip-t-emailuser'             => 'यो प्रयोगकर्तालाई इमेल पठाउनुहोस्',
'tooltip-t-upload'                => 'फाइल उर्ध्वभरण(upload) गर्ने',
'tooltip-t-specialpages'          => 'सबै विशेष पृष्ठहरूको सूची',
'tooltip-t-print'                 => 'यो पृष्ठको मुद्रण योग्य संस्करण',
'tooltip-t-permalink'             => 'पृष्ठको यो पुनरावलोकनको लागि स्थाई लिङ्क',
'tooltip-ca-nstab-main'           => 'सामग्री पृष्ठ हेर्नुहोस',
'tooltip-ca-nstab-user'           => 'प्रयोगकर्ता पृष्ठ हेर्नुहोस्',
'tooltip-ca-nstab-media'          => 'मिडिया पृष्ठ हेर्नुहोस्',
'tooltip-ca-nstab-special'        => 'यो विशेष पृष्ठ हो , तपाईँले आफै सम्पादन गर्न सक्नुहुन्न',
'tooltip-ca-nstab-project'        => 'आयोजान पृष्ठ हेर्नुहोस्',
'tooltip-ca-nstab-image'          => 'फाइल पृष्ठ हेर्नुहोस्',
'tooltip-ca-nstab-mediawiki'      => 'प्रणाली सन्देश हेर्नुहोस्',
'tooltip-ca-nstab-template'       => 'टेम्प्लेट(नमूना) हेर्नुहोस्',
'tooltip-ca-nstab-help'           => 'सहायता पृष्ठ हेर्नुहोस्',
'tooltip-ca-nstab-category'       => 'श्रेणी पृष्ठ हेर्नुहोस्',
'tooltip-minoredit'               => 'यसलाई सामान्य सम्पादनको रुपमा चिनो लगाउने',
'tooltip-save'                    => 'तपाईँले गरेका परिवर्तनहरू संग्रह (सेभ) गर्नुहोस्',
'tooltip-preview'                 => 'तपाईँको परिवर्तनको पूर्वरूप , कृपया संग्रह गर्नु अघि यो प्रयोग गर्नुहोला !',
'tooltip-diff'                    => 'तपाईँले पाठमा के के परिवर्तन गर्नुभयो भनेर देखाउने',
'tooltip-compareselectedversions' => 'यस पृष्ठको छानिएका दुई पुनरावलोकन बीच फरक हेर्नुहोस्',
'tooltip-watch'                   => 'यो पृष्ठलाई तपाईँको अवलोकनसूचीमा थप्नुहोस्',
'tooltip-recreate'                => 'मेटिएको भए ता पनि यो पृष्ट पुन:निर्माण गर्नुहोस् ।',
'tooltip-upload'                  => 'उर्ध्वभरण(upload) सुरुगर्ने',
'tooltip-rollback'                => '"पूर्वरुप" ले यो पृष्ठको सम्पादन(हरु) खारेज गरी पृष्ठलाई पछिल्लो सम्पादनमा एक क्लिकमा पुर्याइ दिन्छ',
'tooltip-undo'                    => '"रद्द"ले पछिल्लो सम्पादन खारेज गरी पूर्वावलोकन मा देखाउछ ।
यसले सारांशमा कारण राख्न दिन्छ ।',

# Attribution
'lastmodifiedatby' => 'यो पृष्ठ अन्तिमपटक $3द्वारा $2, $1 मा परिवर्तन गरिएको थियो।',
'othercontribs'    => '$1 को कामको आधारमा',
'others'           => 'अन्य',
'creditspage'      => 'पृष्ठ श्रेयहरु',
'nocredits'        => 'यो पृष्ठको लागि कुनै श्रेय उपलब्ध छैन ।',

# Spam protection
'spamprotectiontitle' => 'स्प्याम सुरक्षा फिल्टर',
'spamprotectionmatch' => 'निम्न पाठले हाम्रो स्प्प्याम फिल्टर : $1 घच्घच्यायो',
'spambot_username'    => 'MediaWiki स्पाम सर-सफाइ',

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
'previousdiff' => '← पहिलेको सम्पादन',
'nextdiff'     => 'नयाँ सम्पादन →',

# Media information
'file-info-size'       => '($1 × $2 पिक्सेलहरु, फाइल आकार: $3, MIME प्रकार: $4)',
'file-nohires'         => '<small>उच्च रिजोल्युशन अनुपलब्ध</small>',
'svg-long-desc'        => '(SVG फाइल,साधारण $1 × $2 पिक्सेलहरु, फाइल आकार: $3)',
'show-big-image'       => 'पूरा रिजोल्युशन',
'show-big-image-thumb' => '<small>यस पूर्वावलोकनको आकार : $1 × $2 pixels</small>',

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
'exif-meteringmode-3'   => 'स्थान',
'exif-meteringmode-4'   => 'बहुस्थान',
'exif-meteringmode-5'   => 'ढाँचा',
'exif-meteringmode-6'   => 'आंशिक',
'exif-meteringmode-255' => 'अन्य',

'exif-lightsource-0'  => 'अज्ञात',
'exif-lightsource-1'  => 'दिनको उज्यालो',
'exif-lightsource-2'  => 'फ्लोरोसेन्ट',
'exif-lightsource-3'  => 'टङ्स्टेन(अश्वेत प्रकाश)',
'exif-lightsource-4'  => 'झिल्को(फ्लास)',
'exif-lightsource-9'  => 'सफा मौसम',
'exif-lightsource-10' => 'बादली मैसम',
'exif-lightsource-11' => 'छाँया',
'exif-lightsource-12' => 'दिवा फ्लोरोसेन्ट  (D 5700 – 7100K)',
'exif-lightsource-13' => 'दिवा फ्लोरोसेन्ट (N 4600 – 5400K)',
'exif-lightsource-14' => 'शितल सेतो फ्लोरेसेन्ट (W 3900 – 4500K)',

'exif-focalplaneresolutionunit-2' => 'इञ्च',

'exif-customrendered-0' => 'सामान्य प्रक्रिया',

'exif-scenecapturetype-0' => 'स्तरीय',
'exif-scenecapturetype-3' => 'रात्री दृश्य',

'exif-gaincontrol-0' => 'कुनै पनि होइन',

'exif-contrast-0' => 'सामान्य',
'exif-contrast-1' => 'हल्का',
'exif-contrast-2' => 'गाढा',

'exif-saturation-0' => 'साधारण',
'exif-saturation-1' => 'न्युन संतृप्तता',
'exif-saturation-2' => 'उच्च संतृप्ता',

'exif-sharpness-0' => 'साधारण',
'exif-sharpness-1' => 'नरम',
'exif-sharpness-2' => 'कडा',

'exif-subjectdistancerange-0' => 'थाह नभएको',
'exif-subjectdistancerange-1' => 'म्याक्रो',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'उत्तर अक्षांश',
'exif-gpslatitude-s' => 'दक्षिण अक्षांश',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'पूर्व देशान्तर',
'exif-gpslongitude-w' => 'पश्चिम देशान्तर',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'किलोमिटर प्रति घण्टा',
'exif-gpsspeed-m' => 'माइल प्रति घण्टा',

# External editor support
'edit-externally'      => 'यो फाइललाई बाह्य अनुप्रयोग प्रयोग गरेर सम्पादन गर्ने',
'edit-externally-help' => '(थप जानकारीको लागि [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] मा हेर्नुहोस् )',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'सबै',
'imagelistall'     => 'सबै',
'watchlistall2'    => 'सबै',
'namespacesall'    => 'सबै',
'monthsall'        => 'सबै',

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
'autosumm-blank'   => 'पृष्ठ खाली गरीयो',
'autosumm-replace' => "पृष्ठलाई '$1' संग हटाइदै",
'autosumm-new'     => ' $1 को साथमा पृष्ठ शृजना भयो',

# Live preview
'livepreview-loading' => 'लोड गरिंदै छ…',

# Watchlist editing tools
'watchlisttools-view' => 'मिल्दो परिवर्तनहरु हेर्ने',
'watchlisttools-edit' => 'निगरानी सूची हेर्नुहोस् र सम्पादन गर्नुहोस्',
'watchlisttools-raw'  => 'कच्चा निगरानी सूची सम्पादन गर्नुहोस् ।',

# Special:FilePath
'filepath-page' => 'फाइल',

# Special:SpecialPages
'specialpages' => 'विशेष पृष्ठ',

);
