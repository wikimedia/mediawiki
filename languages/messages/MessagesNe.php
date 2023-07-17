<?php
/** Nepali (नेपाली)
 *
 * @file
 * @ingroup Languages
 *
 * @author Bhawani Gautam
 * @author Bhawani Gautam Rhk
 * @author Ganesh
 * @author Ganesh Paudel
 * @author Indiver
 * @author Kaganer
 * @author Krish Dulal
 * @author Lkhatiwada
 * @author Nirmal Dulal
 * @author RajeshPandey
 * @author Reedy
 * @author ne.wikipedia.org sysops
 * @author सरोज कुमार ढकाल
 * @author Biplab Anand
 */

$namespaceNames = [
	NS_MEDIA            => 'मीडिया',
	NS_SPECIAL          => 'विशेष',
	NS_TALK             => 'वार्तालाप',
	NS_USER             => 'प्रयोगकर्ता',
	NS_USER_TALK        => 'प्रयोगकर्ता_वार्ता',
	NS_PROJECT_TALK     => '$1_वार्ता',
	NS_FILE             => 'चित्र',
	NS_FILE_TALK        => 'चित्र_वार्ता',
	NS_MEDIAWIKI        => 'मीडियाविकि',
	NS_MEDIAWIKI_TALK   => 'मीडियाविकि_वार्ता',
	NS_TEMPLATE         => 'ढाँचा',
	NS_TEMPLATE_TALK    => 'ढाँचा_वार्ता',
	NS_HELP             => 'मद्दत',
	NS_HELP_TALK        => 'मद्दत_वार्ता',
	NS_CATEGORY         => 'श्रेणी',
	NS_CATEGORY_TALK    => 'श्रेणी_वार्ता',
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'सक्रिय_प्रयोगकर्ताहरू', 'सक्रिय_प्रयोगकर्ताहरु' ],
	'Listgrouprights'           => [ 'प्रयोगकर्ता_समूह_अधिकार' ],
];

$digitTransformTable = [
	'0' => '०', # U+0966
	'1' => '१', # U+0967
	'2' => '२', # U+0968
	'3' => '३', # U+0969
	'4' => '४', # U+096A
	'5' => '५', # U+096B
	'6' => '६', # U+096C
	'7' => '७', # U+096D
	'8' => '८', # U+096E
	'9' => '९', # U+096F
];

$magicWords = [
	'anchorencode'              => [ '0', 'एङ्कर_कोड', 'ANCHORENCODE' ],
	'articlepath'               => [ '0', 'लेख_पथ', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', 'आधार_पृष्ठ_नाम', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'आधार_पृष्ठ_नाम_कोड', 'BASEPAGENAMEE' ],
	'canonicalurl'              => [ '0', 'मानक_यु_आर_एल:', 'CANONICALURL:' ],
	'canonicalurle'             => [ '0', 'मानक_यु_आर_एल_कोड:', 'CANONICALURLE:' ],
	'cascadingsources'          => [ '1', 'लामबद्द_सुरक्षा_स्रोत', 'CASCADINGSOURCES' ],
	'contentlanguage'           => [ '1', 'सामग्री_भाषा', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'वर्तमान_दिन', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'वर्तमान_दिन2', 'वर्तमान_दिन२', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'वर्तमान_दिन_नाम', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'वर्तमान_हप्ताको_दिन', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'वर्तमान_घण्टा', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'वर्तमान_महिना', 'वर्तमान_महिना2', 'वर्तमान_महिना२', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'वर्तमान_महिना1', 'वर्तमान_महिना१', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'वर्तमान_महिना_सङ्क्षेप', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'वर्तमान_महिना_नाम', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'वर्तमान_महिना_सम्बन्ध', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'वर्तमान_समय', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'वर्तमान_समय_मोहर', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'वर्तमान_अवतरण', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'वर्तमान_हप्ता', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'वर्तमान_वर्ष', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'क्रमबद्ध:', 'श्रेणीक्रमबद्ध:', 'मूल_सर्ट:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'defaultsort_noerror'       => [ '0', 'त्रुटि_छैन', 'noerror' ],
	'defaultsort_noreplace'     => [ '0', 'परिवर्तन_नगर्ने', 'noreplace' ],
	'directionmark'             => [ '1', 'दिशा_चिन्ह', 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'              => [ '1', 'दृश्य_शीर्षक', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'फाइल_पथ:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__अनुक्रम_देखाउने__', '__विषय_सूची_देखाउने__', '__विषय_सूची_देखाउने__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'मिति_रूप', 'formatdate', 'dateformat' ],
	'formatnum'                 => [ '0', 'सङ्ख्या_रूप', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'पूर्ण_पृष्ठ_नाम', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'पूर्ण_पृष्ठ_नाम_कोड', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'पूर्ण_यु_आर_एल:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'पूर्ण_यु_आर_एल_कोड:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'लिङ्ग:', 'GENDER:' ],
	'grammar'                   => [ '0', 'व्याकरण:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__लुकाइएको_श्रेणी__', '__HIDDENCAT__' ],
	'img_alt'                   => [ '1', 'पाठ=$1', 'alt=$1' ],
	'img_baseline'              => [ '1', 'आधार_रेखा', 'baseline' ],
	'img_border'                => [ '1', 'बोर्डर', 'किनारा', 'border' ],
	'img_bottom'                => [ '1', 'तल', 'bottom' ],
	'img_center'                => [ '1', 'केन्द्र', 'केन्द्रित', 'center', 'centre' ],
	'img_class'                 => [ '1', 'वर्ग=$1', 'class=$1' ],
	'img_framed'                => [ '1', 'फ्रेम', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'फ्रेमहीन', 'frameless' ],
	'img_lang'                  => [ '1', 'भाषा=$1', 'lang=$1' ],
	'img_left'                  => [ '1', 'बायाँ', 'देब्रे', 'left' ],
	'img_link'                  => [ '1', 'कडी=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'अङ्गुठाकार=$1', 'अङ्गुठा=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'मध्य', 'middle' ],
	'img_none'                  => [ '1', 'कुनै_पनि_होइन', 'none' ],
	'img_page'                  => [ '1', 'पृष्ठ=$1', 'पृष्ठ_$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'दायाँ', 'दाहिने', 'right' ],
	'img_sub'                   => [ '1', 'पद', 'sub' ],
	'img_super'                 => [ '1', 'मूर्ध', 'super', 'sup' ],
	'img_text_bottom'           => [ '1', 'पाठ-तल', 'text-bottom' ],
	'img_text_top'              => [ '1', 'पाठ-शीर्ष', 'text-top' ],
	'img_thumbnail'             => [ '1', 'अङ्गुठाकार', 'अङ्गुठा', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'शीर्ष', 'top' ],
	'img_upright'               => [ '1', 'ठाडो', 'ठाडो=$1', 'ठाडो_$1', 'upright', 'upright=$1', 'upright $1' ],
	'img_width'                 => [ '1', '$1पिक्सेल', '$1px' ],
	'index'                     => [ '1', '__सूचीबद्ध__', '__INDEX__' ],
	'int'                       => [ '0', 'विश्व:', 'INT:' ],
	'language'                  => [ '0', '#भाषा:', '#LANGUAGE:' ],
	'lc'                        => [ '0', 'छोटो_अक्षर:', 'LC:' ],
	'lcfirst'                   => [ '0', 'छोटो_अक्षरबाट_सुरु:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'स्थानीय_दिन', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'स्थानीय_दिन2', 'स्थानीय_दिन२', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'स्थानीय_दिन_नाम', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'स्थानीय_हप्ताको_दिन', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'स्थानीय_घण्टा', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'स्थानीय_महिना', 'स्थानीय_महिना2', 'स्थानीय_महिना२', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'स्थानीय_महिना1', 'स्थानीय_महिना१', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'स्थानीय_महिना_सङ्क्षेप', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'स्थानीय_महिना_नाम', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'स्थानीय_महिना_सम्बन्ध', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'स्थानीय_समय', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'स्थानीय_समय_मोहर', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'स्थानीय_यु_आर_एल:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'स्थानीय_यु_आर_एल_कोड:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'स्थानीय_हप्ता', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'स्थानीय_वर्ष', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'सन्देश:', 'MSG:' ],
	'msgnw'                     => [ '0', 'सन्देश_नोविकी:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'नामस्थान', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'नामस्थान_कोड', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'नामस्थान_सङ्ख्या', 'NAMESPACENUMBER' ],
	'newsectionlink'            => [ '1', '__नयाँ_खण्ड_कडी__', '__NEWSECTIONLINK__' ],
	'nocommafysuffix'           => [ '0', 'वि_छैन', 'NOSEP' ],
	'noeditsection'             => [ '0', '__अनुभाग_सम्पादन_छैन__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__चित्रदीर्घा_छैन__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__असूचीबद्ध__', '__NOINDEX__' ],
	'nonewsectionlink'          => [ '1', '__विषय_जोड्ने_कडी_रहित__', '__NONEWSECTIONLINK__' ],
	'notoc'                     => [ '0', '__बिना_अनुक्रम__', '__विषय_सूची_हीन__', '__NOTOC__' ],
	'ns'                        => [ '0', 'नामस्थान:', 'NS:' ],
	'nse'                       => [ '0', 'नामस्थान_कोड:', 'NSE:' ],
	'numberingroup'             => [ '1', 'समूह_सङ्ख्या', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', 'सक्रिय_प्रयोगकर्ता_सङ्ख्या', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'प्रबन्धक_सङ्ख्या', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'लेख_सङ्ख्या', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'सम्पादन_सङ्ख्या', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'फाइल_सङ्ख्या', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'पृष्ठ_सङ्ख्या', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'प्रयोगकर्ता_सङ्ख्या', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'बायाँ_जोड्ने', 'देब्रे_जोड्ने', 'PADLEFT' ],
	'padright'                  => [ '0', 'दायाँ_जोड्ने', 'दाहिने_जोड्ने', 'PADRIGHT' ],
	'pageid'                    => [ '0', 'पृष्ठ_आइ_डी', 'PAGEID' ],
	'pagename'                  => [ '1', 'पृष्ठ_नाम', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'पृष्ठ_नाम_कोड', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'श्रेणीमा_पृष्ठ', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_all'       => [ '0', 'सबै', 'all' ],
	'pagesincategory_files'     => [ '0', 'फाइलहरू', 'files' ],
	'pagesincategory_pages'     => [ '0', 'पृष्ठ', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'श्रेणीहरू', 'subcats' ],
	'pagesinnamespace'          => [ '1', 'नामस्थानमा_पृष्ठ:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'पृष्ठ_आकार', 'PAGESIZE' ],
	'plural'                    => [ '0', 'वचन:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'सुरक्षा_स्तर', 'PROTECTIONLEVEL' ],
	'raw'                       => [ '0', 'सादा:', 'RAW:' ],
	'rawsuffix'                 => [ '1', 'उ', 'R' ],
	'redirect'                  => [ '0', '#अनुप्रेषण', '#पुनर्प्रेषित', '#अनुप्रेषित', '#REDIRECT' ], // T317689
	'revisionday'               => [ '1', 'अवतरण_दिन', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'अवतरण_दिन2', 'अवतरण_दिन२', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'अवतरण_सङ्ख्या', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'अवतरण_महिना', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'अवतरण_महिना1', 'अवतरण_महिना१', 'REVISIONMONTH1' ],
	'revisionsize'              => [ '1', 'अवतरण_आकार', 'REVISIONSIZE' ],
	'revisiontimestamp'         => [ '1', 'अवतरण_समय', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'अवतरण_सदस्य', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'अवतरण_वर्ष', 'REVISIONYEAR' ],
	'rootpagename'              => [ '1', 'मूल_पृष्ठ_नाम', 'ROOTPAGENAME' ],
	'rootpagenamee'             => [ '1', 'मूल_पृष्ठ_नाम_कोड', 'ROOTPAGENAMEE' ],
	'safesubst'                 => [ '0', 'सुरक्षित_प्रति:', 'SAFESUBST:' ],
	'scriptpath'                => [ '0', 'स्क्रिप्ट_पथ', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'सर्भर', 'SERVER' ],
	'servername'                => [ '0', 'सर्भर_नाम', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'साइट_नाम', 'SITENAME' ],
	'special'                   => [ '0', 'विशेष', 'special' ],
	'speciale'                  => [ '0', 'विशेष_कोड', 'speciale' ],
	'staticredirect'            => [ '1', '__स्थिर_पुनर्प्रेषण__', '__स्थिर_अनुप्रेषण__', '__STATICREDIRECT__' ],
	'stylepath'                 => [ '0', 'स्टाइल_पथ', 'STYLEPATH' ],
	'subjectpagename'           => [ '1', 'सामग्री_पृष्ठ_नाम', 'लेख_पृष्ठ_नाम', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'सामग्री_पृष्ठ_नाम_कोड', 'लेख_पृष्ठ_नाम_कोड', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'सामग्री_स्थान', 'लेख_स्थान', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'सामग्री_स्थान_कोड', 'लेख_स्थान_कोड', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'उपपृष्ठ_नाम', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'उपपृष्ठ_नाम_कोड', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'प्रति:', 'SUBST:' ],
	'tag'                       => [ '0', 'चिनो', 'tag' ],
	'talkpagename'              => [ '1', 'वार्ता_पृष्ठ_नाम', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'वार्ता_पृष्ठ_नाम_कोड', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'वार्ता_स्थान', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'वार्ता_स्थान_कोड', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__अनुक्रम__', '__विषय_सूची__', '__TOC__' ],
	'uc'                        => [ '0', 'ठूलो_अक्षर:', 'UC:' ],
	'ucfirst'                   => [ '0', 'ठूलो_अक्षरबाट_सुरु:', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'यु_आर_एल_कोड:', 'URLENCODE:' ],
	'url_path'                  => [ '0', 'पथ', 'PATH' ],
	'url_query'                 => [ '0', 'पाठ', 'QUERY' ],
	'url_wiki'                  => [ '0', 'विकी', 'WIKI' ],
];
