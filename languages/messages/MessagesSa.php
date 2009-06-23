<?php
/** Sanskrit (संस्कृत)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Hemant wikikosh1
 * @author Kaustubh
 * @author Mahitgar
 * @author Omnipaedista
 */

$fallback = 'hi';

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

$linkPrefixExtension = false;

$namespaceNames = array(
	NS_MEDIA            => 'माध्यम',
	NS_SPECIAL          => 'विशेष',
	NS_TALK             => 'संभाषणं',
	NS_USER             => 'योजकः',
	NS_USER_TALK        => 'योजकसंभाषणं',
	NS_PROJECT_TALK     => '$1संभाषणं',
	NS_FILE             => 'चित्रं',
	NS_FILE_TALK        => 'चित्रसंभाषणं',
	NS_MEDIAWIKI        => 'मिडीयाविकी',
	NS_MEDIAWIKI_TALK   => 'मिडियाविकीसंभाषणं',
	NS_TEMPLATE         => 'बिंबधर',
	NS_TEMPLATE_TALK    => 'बिंबधर संभाषणं',
	NS_HELP             => 'सहाय्य',
	NS_HELP_TALK        => 'सहाय्यसंभाषणं',
	NS_CATEGORY         => 'वर्गः',
	NS_CATEGORY_TALK    => 'वर्गसंभाषणं',
);

$namespaceAliases = array(
	'उपकारः' => NS_HELP,
	'उपकारसंभाषणं' => NS_HELP_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'पुनर्निर्देशनद्वंद्व' ),
	'BrokenRedirects'           => array( 'खण्डीतपुनर्निर्देशन' ),
	'Disambiguations'           => array( 'नि:संदिग्धीकरण' ),
	'Userlogin'                 => array( 'सदस्यप्रवेश' ),
	'Userlogout'                => array( 'सदस्यबहिर्गमन' ),
	'CreateAccount'             => array( 'सृज्उपयोजकसंज्ञा' ),
	'Preferences'               => array( 'प्रियविकल्प' ),
	'Watchlist'                 => array( 'निरीक्षा सूची' ),
	'Recentchanges'             => array( 'नवीनतम परिवर्तन' ),
	'Upload'                    => array( 'भारंन्यस्यति' ),
	'Listfiles'                 => array( 'चित्रसूची' ),
	'Newimages'                 => array( 'नूतनसंचिका' ),
	'Listusers'                 => array( 'सदस्यासूची' ),
	'Listgrouprights'           => array( 'गटअधिकारसूची' ),
	'Statistics'                => array( 'सांख्यिकी' ),
	'Randompage'                => array( 'अविशीष्टपृष्ठ' ),
	'Lonelypages'               => array( 'अकलपृष्ठ' ),
	'Uncategorizedpages'        => array( 'अवर्गीकृतपृष्ठ' ),
	'Uncategorizedcategories'   => array( 'अवर्गीकृतवर्ग' ),
	'Uncategorizedimages'       => array( 'अवर्गीकृतसंचिका' ),
	'Uncategorizedtemplates'    => array( 'अवर्गीकृतबिंबधर' ),
	'Unusedcategories'          => array( 'अप्रयूक्तवर्ग' ),
	'Unusedimages'              => array( 'अप्रयूक्तसंचिका' ),
	'Wantedpages'               => array( 'प्रार्थितलेख' ),
	'Wantedcategories'          => array( 'प्रार्थितवर्ग' ),
	'Wantedfiles'               => array( 'प्रार्थितसंचिका' ),
	'Wantedtemplates'           => array( 'प्रार्थितबिंबधर' ),
	'Mostlinked'                => array( 'अधिकतमसम्भन्दिन् पृष्ठ' ),
	'Mostlinkedcategories'      => array( 'अधिकतमसम्भन्दिन् वर्ग' ),
	'Mostlinkedtemplates'       => array( 'अधिकतमसम्भन्दिन् बिंबधर' ),
	'Mostimages'                => array( 'अधिकतमसम्भन्दिन् संचिका' ),
	'Mostcategories'            => array( 'अधिकतमवर्ग' ),
	'Mostrevisions'             => array( 'अधिकतमपरिवर्त' ),
	'Fewestrevisions'           => array( 'स्वल्पपरिवर्तन' ),
	'Shortpages'                => array( 'लघुपृष्ठ' ),
	'Longpages'                 => array( 'दीर्घपृष्ठ' ),
	'Newpages'                  => array( 'नूतनपृष्ठ' ),
	'Ancientpages'              => array( 'पूर्वतनपृष्ठ' ),
	'Deadendpages'              => array( 'निराग्रपृष्ठ' ),
	'Protectedpages'            => array( 'सुरक्षितपृष्ठ' ),
	'Protectedtitles'           => array( 'सुरक्षितशिर्षक' ),
	'Allpages'                  => array( 'सर्वपृष्ठ' ),
	'Prefixindex'               => array( 'उपसर्गअनुक्रमणी' ),
	'Ipblocklist'               => array( 'प्रतिबन्धसूची' ),
	'Specialpages'              => array( 'विशेषपृष्ठ' ),
	'Contributions'             => array( 'योगदान' ),
	'Emailuser'                 => array( 'विपत्रयोजक' ),
	'Confirmemail'              => array( 'विपत्रपुष्टिकृते' ),
	'Whatlinkshere'             => array( 'किमपृष्ठ सम्बद्धंकरोति' ),
	'Recentchangeslinked'       => array( 'नवीनतमसम्भन्दिन परिवर्त' ),
	'Movepage'                  => array( 'पृष्ठस्थानान्तर' ),
	'Blockme'                   => array( 'मदर्थेप्रतिबन्ध' ),
	'Booksources'               => array( 'पुस्तकस्रोत' ),
	'Categories'                => array( 'वर्ग' ),
	'Export'                    => array( 'निर्यात' ),
	'Version'                   => array( 'आवृत्ती' ),
	'Allmessages'               => array( 'सर्वप्रणाली-संदेश' ),
	'Log'                       => array( 'अङ्कन' ),
	'Blockip'                   => array( 'सदस्यप्रतिबन्ध' ),
	'Undelete'                  => array( 'प्रत्यादिश् परित्याग' ),
	'Import'                    => array( 'आयात' ),
	'Lockdb'                    => array( 'विदाद्वारंबन्ध्' ),
	'Unlockdb'                  => array( 'विवृतविदाद्वारंतालक' ),
	'Userrights'                => array( 'योजकआधिकार' ),
	'MIMEsearch'                => array( 'विविधामाप (माईम) शोधसि' ),
	'FileDuplicateSearch'       => array( 'अनुकृतसंचिकाशोध' ),
	'Unwatchedpages'            => array( 'अनिरिक्षीतपृष्ठ' ),
	'Listredirects'             => array( 'विचालन्‌सूची' ),
	'Revisiondelete'            => array( 'आवृत्तीपरित्याग' ),
	'Unusedtemplates'           => array( 'अप्रयूक्तबिंबधर' ),
	'Randomredirect'            => array( 'अविशीष्टविचालन्‌' ),
	'Mypage'                    => array( 'मदीयपृष्ठ' ),
	'Mytalk'                    => array( 'मदीयसंवाद' ),
	'Mycontributions'           => array( 'मदीययोगदान' ),
	'Listadmins'                => array( 'प्रचालकसूची' ),
	'Listbots'                  => array( 'स्वयंअनुकृसूची' ),
	'Popularpages'              => array( 'लोकप्रियपृष्ठ' ),
	'Search'                    => array( 'शोध' ),
	'Resetpass'                 => array( 'सङ्केतशब्दपुन:प्रयु्क्ता' ),
	'Withoutinterwiki'          => array( 'आन्तरविकिहीन' ),
	'MergeHistory'              => array( 'इतिहाससंयोग' ),
	'Filepath'                  => array( 'संचिकापथ' ),
	'Invalidateemail'           => array( 'अमान्यविपत्र' ),
	'Blankpage'                 => array( 'रिक्तपृष्ठ' ),
	'LinkSearch'                => array( 'सम्बन्धन्‌शोध' ),
	'DeletedContributions'      => array( 'परित्यागितयोगदान' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#पुनर्निदेशन', '#REDIRECT' ),
	'notoc'                 => array( '0', '__नैवअनुक्रमणी__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__नैवसंक्रमणका__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__अनुक्रमणीसचते__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__अनुक्रमणी__', '__TOC__' ),
	'noeditsection'         => array( '0', '__नैवसम्पादनविभाग__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__नैवमुख्यशिर्षक__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'अद्यमासे', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'अद्यमासेनाम', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'अद्यमासेनामसाधारण', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'अद्यमासेसंक्षीप्त', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'अद्यदिवसे', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'अद्यदिवसे२', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'अद्यदिवसेनाम', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'अद्यवर्ष', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'सद्यसमय', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'सद्यघण्टा', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'स्थानिकमासे', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'        => array( '1', 'स्थानिकमासेनाम', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'स्थानिकमासेनामसाधारण', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'स्थानिकमासेसंक्षीप्त', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'स्थानिकदिवसे', 'LOCALDAY' ),
	'localday2'             => array( '1', 'स्थानिकदिवसे२', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'स्थानिकदिवसेनाम', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'स्थानिकवर्षे', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'स्थानिकसमये', 'LOCALTIME' ),
	'localhour'             => array( '1', 'स्थानिकघण्टा', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'पृष्ठानाम्‌सङ्ख्या', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'लेखस्य‌सङ्ख्या', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'संचिकानाम्‌‌सङ्ख्या', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'योजकस्यसङ्ख्या', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'सम्पादनसङ्ख्या', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'दृष्टिसङ्ख्या', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'पृष्ठनाम', 'PAGENAME' ),
	'namespace'             => array( '1', 'नामविश्व', 'NAMESPACE' ),
	'talkspace'             => array( '1', 'व्यासपिठ', 'TALKSPACE' ),
	'subjectspace'          => array( '1', 'विषयविश्व', 'लेखविश्व', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'fullpagename'          => array( '1', 'पूर्णपृष्ठनाम', 'FULLPAGENAME' ),
	'subpagename'           => array( '1', 'उपपृष्ठनाम', 'SUBPAGENAME' ),
	'basepagename'          => array( '1', 'आधारपृष्ठनाम', 'BASEPAGENAME' ),
	'talkpagename'          => array( '1', 'संवादपृष्ठनाम', 'TALKPAGENAME' ),
	'subjectpagename'       => array( '1', 'विषयपृष्ठनाम', 'लेखपृष्ठनाम', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'msg'                   => array( '0', 'सन्देश:', 'MSG:' ),
	'msgnw'                 => array( '0', 'नूतनसन्देश:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'लघुत्तम', 'सङ्कुचितचित्र', 'अङ्गुष्ठ', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'सङ्कुचितचित्र=$1', 'अङ्गुष्ठ=$1', 'लघुत्तमचित्र=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'दक्षिणत', 'right' ),
	'img_left'              => array( '1', 'वामतः', 'left' ),
	'img_none'              => array( '1', 'नैव', 'none' ),
	'img_width'             => array( '1', '$1पिट', '$1px' ),
	'img_center'            => array( '1', 'मध्य', 'center', 'centre' ),
	'img_framed'            => array( '1', 'आबन्ध', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'निराबन्ध', 'frameless' ),
	'img_page'              => array( '1', 'पृष्ठ=$1', 'पृष्ठ $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'उन्नत', 'उन्नत=$1', 'उन्नत $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'सीमा', 'border' ),
	'img_baseline'          => array( '1', 'आधाररेखा', 'baseline' ),
	'img_sub'               => array( '1', 'विषये', 'sub' ),
	'img_super'             => array( '1', 'अति', 'तीव्र', 'super', 'sup' ),
	'img_top'               => array( '1', 'अग्र', 'top' ),
	'img_text_top'          => array( '1', 'पाठ्य-अग्र', 'text-top' ),
	'img_middle'            => array( '1', 'मध्ये', 'middle' ),
	'img_bottom'            => array( '1', 'अधस', 'bottom' ),
	'img_text_bottom'       => array( '1', 'पाठ्य-अधस', 'text-bottom' ),
	'img_link'              => array( '1', 'सम्बद्धं=$1', 'link=$1' ),
	'img_alt'               => array( '1', 'विकल्प=$1', 'alt=$1' ),
	'sitename'              => array( '1', 'स्थलनाम', 'SITENAME' ),
	'grammar'               => array( '0', 'व्याकरण:', 'GRAMMAR:' ),
	'notitleconvert'        => array( '0', '__नैवशिर्षकपरिवर्त__', '__नैशिप__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__नैवलेखपरिवर्त__', '__नैलेप__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'अद्यसप्ताह', 'CURRENTWEEK' ),
	'localweek'             => array( '1', 'स्थानिकसप्ताह', 'LOCALWEEK' ),
	'revisionid'            => array( '1', 'आवृत्तीक्रमांक', 'REVISIONID' ),
	'revisionday'           => array( '1', 'आवृत्तीदिवसे', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'आवृत्तीदिवसे२', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'आवृत्तीमासे', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'आवृत्तीवर्षे', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'आवृत्तीसमयमुद्रा', 'REVISIONTIMESTAMP' ),
	'plural'                => array( '0', 'अनेकवचन:', 'PLURAL:' ),
	'displaytitle'          => array( '1', 'प्रदर्शनशीर्षक', 'उपाधिदर्शन', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__नूतनविभागसम्बद्धं__', '__NEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'अद्यआवृत्ती', 'CURRENTVERSION' ),
	'currenttimestamp'      => array( '1', 'सद्यसमयमुद्रा', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'स्थानिकसमयमुद्रा', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'दिशाचिह्न', 'दिशे', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#भाषा:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'विषयभाषा', 'आधेयभाषा', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'नामविश्वातपृष्ठ', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'प्रचालकसंख्या', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'रचनासंख्या', 'FORMATNUM' ),
	'special'               => array( '0', 'विशेष', 'special' ),
	'filepath'              => array( '0', 'संचिकापथ', 'FILEPATH:' ),
	'tag'                   => array( '0', 'वीजक', 'tag' ),
	'hiddencat'             => array( '1', '__लुप्तवर्ग__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'वर्गेपृष्ठ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'पृष्ठाकार', 'PAGESIZE' ),
	'index'                 => array( '1', '__अनुक्रमणिका__', '__INDEX__' ),
	'noindex'               => array( '1', '__नैवअनुक्रमणिका__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'गणानामसंख्या', 'गणसंख्या', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__अनित्यपुनर्निदेशन__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'रक्षास्तर', 'PROTECTIONLEVEL' ),
);

$messages = array(
'underline-always' => 'सदा',
'underline-never'  => 'न जातु',

# Dates
'sunday'    => 'विश्रामवासरे',
'monday'    => 'सोमवासरे',
'tuesday'   => 'मंगलवासरे',
'wednesday' => 'बुधवासरे',
'thursday'  => 'गुरुवासरे',
'friday'    => 'शुक्रवासरे',
'saturday'  => 'शनिवासरे',
'sun'       => 'विश्राम',
'mon'       => 'सोम',
'tue'       => 'मंगल',
'wed'       => 'बुध',
'thu'       => 'गुरु',
'fri'       => 'शुक्र',
'sat'       => 'शनि',
'january'   => 'जनवरीमासः',
'february'  => 'फेब्रुवरीमासः',
'march'     => 'मार्चमासः',
'april'     => 'एप्रिलमासः',
'may_long'  => 'मेमासः',
'june'      => 'जूनमासः',
'july'      => 'जुलाइमासः',
'august'    => 'आगस्टमासः',
'september' => 'सेप्टेम्बरमासः',
'october'   => 'अक्टूबरमासः',
'november'  => 'नवम्बरमासः',
'december'  => 'डेसेम्बरमासः',
'jan'       => 'जन.',
'feb'       => 'फेब्रु.',
'mar'       => 'मार्च.',
'apr'       => 'एप्रि.',
'may'       => 'मे.',
'jun'       => 'जून.',
'jul'       => 'जुला.',
'aug'       => 'अग.',
'sep'       => 'सित.',
'oct'       => 'अक्टू.',
'nov'       => 'नव.',
'dec'       => 'दिस.',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|श्रेणी|श्रेण्यः }}',
'hidden-categories'      => '{{PLURAL:$1|लोपिता श्रेणी|लोपिताः श्रेण्यः}}',
'listingcontinuesabbrev' => 'आगामि.',

'about'      => 'विषये',
'newwindow'  => '(नवे गवाक्षे उद्घाट्यते)',
'cancel'     => 'निरसनम्',
'mypage'     => 'मम पृष्ठम्',
'mytalk'     => 'मम लोकप्रवादः',
'navigation' => 'सुचलनम्',
'and'        => '&#32;एवम्',

# Cologne Blue skin
'qbfind'        => 'शोध',
'qbedit'        => 'संपादयति',
'qbpageoptions' => 'इदम्‌ पृष्ठ',
'qbmyoptions'   => 'पृष्ठाणि मया लिखितानि',
'faq'           => 'अतिप्रश्नपृष्ट',

'tagline'          => '{{SITENAME}} इत्यस्मात्',
'help'             => 'सहायता',
'search'           => 'अन्विष्यतु',
'searchbutton'     => 'अन्विष्यतु',
'go'               => 'गच्छति',
'searcharticle'    => 'गच्छतु',
'history'          => 'पृष्ठस्य इतिहासः',
'history_short'    => 'इतिहासः',
'printableversion' => 'मुद्रणीया आवृत्तिः',
'permalink'        => 'स्थायि-सम्बन्धनम्',
'print'            => 'मुद्रयतु',
'edit'             => 'सम्पादयतु',
'create'           => 'रचयतु',
'editthispage'     => 'इदं पृष्ठं सम्पादयतु',
'create-this-page' => 'इदं पृष्ठ सृजामि',
'delete'           => 'विलोप',
'protect'          => 'सुरक्षित करोसि',
'protect_change'   => 'सुरक्षा-नियमान् परिवर्तयतु',
'newpage'          => 'नूतनं पृष्ठम्',
'talkpagelinktext' => 'संवादः',
'specialpage'      => 'विशेष पृष्ठ',
'personaltools'    => 'वैयक्तिक-साधनानि',
'talk'             => 'चर्चा',
'views'            => 'दृश्यरूपाणि',
'toolbox'          => 'साधन-पिटकम्',
'otherlanguages'   => 'अन्यासु भाषासु',
'redirectedfrom'   => '($1 इत्यस्मात् अनुप्रेषितम्)',
'lastmodifiedat'   => 'इदं पृष्ठं अन्तिमं वारं परिवर्तितम् : दिनांके $1, $2 वादने।',
'jumpto'           => 'कूर्दयतु अत्र :',
'jumptonavigation' => 'सुचलनम्',
'jumptosearch'     => 'अन्वेषणम्',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} इत्यस्य विषये',
'aboutpage'            => 'Project:एतद्विषये',
'copyright'            => 'अस्य घटकानि $1 इत्यस्यान्तर्गतानि उपलब्धानि।',
'copyrightpage'        => '{{ns:project}}:प्रतिलिप्यधिकाराणि',
'currentevents'        => 'सद्य घटना',
'disclaimers'          => 'प्रत्याख्यानम्',
'disclaimerpage'       => 'Project:सामान्यं प्रत्याख्यानम्',
'edithelp'             => 'संपादनार्थं सहाय्यम्',
'edithelppage'         => 'Help:संपादनम्',
'helppage'             => 'Help:सहाय्य',
'mainpage'             => 'मुखपृष्ठम्',
'mainpage-description' => 'मुख्यपृष्ठम्',
'privacy'              => 'नैजता-नीतिः',
'privacypage'          => 'Project:नैजता-नीतिः',

'retrievedfrom'   => '"$1" इत्यस्मात् गृहीतम्',
'newmessageslink' => 'नूतनाः संदेशाः',
'editsection'     => 'सम्पादयतु',
'editold'         => 'संपादनम्',
'viewsourcelink'  => 'स्रोतसम् दर्शयतु',
'editsectionhint' => 'विभागं संपादयतु: $1',
'toc'             => 'अनुक्रमणिका',
'showtoc'         => 'दर्शयतु',
'hidetoc'         => 'लोपयतु',
'feedlinks'       => 'अनुबन्ध:',
'site-rss-feed'   => '$1 आरएसएस पूरणम्',
'site-atom-feed'  => '$1 ऍटम पूरणम्',
'page-rss-feed'   => '"$1" आरएसएस-पूरणम्',
'page-atom-feed'  => '"$1" ऍटम अनुबन्ध',
'red-link-title'  => '$1 (इदानीं यावत् न रचितम्)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'पृष्ठम्',
'nstab-user'     => 'प्रयोक्तृ-पृष्ठम्',
'nstab-special'  => 'विशिष्टं पृष्ठम्',
'nstab-image'    => 'संचिका',
'nstab-template' => 'बिंबधर',
'nstab-category' => 'श्रेणी',

# General errors
'error'         => 'विभ्रम',
'viewsource'    => 'स्रोतः दर्शयतु',
'viewsourcefor' => '$1 कृते',

# Login and logout pages
'yourpassword'            => 'कूटशब्दः:',
'login'                   => 'प्रवेश करोसि',
'nav-login-createaccount' => 'प्रविशतु / लेखां सृजतु',
'userlogin'               => 'प्रवेश करोसि/ सृज् उपयोजकसंज्ञा',
'logout'                  => 'बहिर्गच्छतु',
'userlogout'              => 'बहिर्गच्छति',
'createaccount'           => 'लेखां रचयतु',
'gotaccountlink'          => 'प्रवेश करोसि',
'loginsuccesstitle'       => 'सुस्वागतम्‌। प्रवेशः सिद्धः।',

# Edit page toolbar
'bold_sample'     => 'स्थूलाक्षरित-पाठः',
'italic_sample'   => 'इटालिक-पाठः',
'italic_tip'      => 'इटालिकः पाठः',
'link_sample'     => 'संबंधनस्य शीर्षकम्',
'link_tip'        => 'अन्तर्गतं संबंधनम्',
'extlink_sample'  => 'http://www.example.com संबंधनस्य शीर्षकम्',
'extlink_tip'     => 'बाह्य-संबंधनम् (अवश्यमेव  http:// इति पूर्वलग्नं योक्तव्यम्)',
'headline_sample' => 'शीर्षकम्',
'headline_tip'    => 'द्वितीय-स्तरीयं शीर्षकम्',
'math_sample'     => 'गणितीयं सूत्रम् अत्र निवेशयतु',
'math_tip'        => 'गणितीयम् सूत्रम् (LaTeX)',
'nowiki_sample'   => 'अप्रारूपीकृतं पाठम् अत्र निवेशयतु',
'nowiki_tip'      => 'विकिभाषानुसारेण मा परिवर्तयतु',
'image_tip'       => 'अन्तर्गता संचिका',
'media_tip'       => 'संचिका-संबंधनम्',
'sig_tip'         => 'भवतः हस्ताक्षराणि समयेन सह',
'hr_tip'          => 'क्षैतिज-रेखा (न्यूनतया प्रयोक्तव्या)',

# Edit pages
'summary'            => 'सारांश:',
'subject'            => 'विषयः/शीर्षकम् :',
'minoredit'          => 'इदं लघु परिवर्तनम्',
'watchthis'          => 'इदं पृष्ठं निरीक्षताम्',
'savearticle'        => 'पृष्ठं रक्षतु',
'preview'            => 'प्राग्दृश्यम्',
'showpreview'        => 'प्राग्दृश्यं दर्शयतु',
'showdiff'           => 'परिवर्तनानि दर्शयतु',
'anoneditwarning'    => "'''सावधानो भवतु:''' भवता प्रवेशं न कृतम्। अस्य पृष्ठस्य इतिहासे भवतः आइ-पी-संकेतः अंकितः भविष्यति।",
'newarticle'         => '(नविन)',
'editing'            => '$1 संपाद्यते',
'template-protected' => '(सुरक्षितम्)',

# History pages
'currentrev-asof'     => 'वर्तमती आवृत्तिः $1 इति समये',
'revisionasof'        => '$1 इत्यस्य आवृत्तिः',
'previousrevision'    => '← पुरातनाः आवृत्तयः',
'currentrevisionlink' => 'सद्यःकालीना आवृत्तिः',
'cur'                 => 'नवतरम्',
'last'                => 'पूर्वतनम्',
'page_first'          => 'प्रथम्‌',
'page_last'           => 'अन्तिमम्',
'histfirst'           => 'पुरातनतमम्',
'histlast'            => 'नूतनतमम्',

# Revision feed
'history-feed-item-nocomment' => '$1 उप $2',

# Revision deletion
'rev-delundel'   => 'दर्शयतु/लोपयतु',
'revdel-restore' => 'दृश्यतां परिवर्तयतु',

# Merge log
'revertmerge' => 'पृथक् करोतु',

# Diffs
'difference' => '(आवृत्तीनां मध्ये अन्तरम्)',
'lineno'     => 'पंक्तिः $1:',
'editundo'   => 'अकरोतु',

# Search results
'searchresults'             => 'अन्वेषण-फलानि',
'searchresults-title'       => '"$1" इत्यस्य कृते अन्वेषण-फलानि',
'notitlematches'            => 'न कस्यापि पृष्ठस्य शीर्षकम् अस्य समम्।',
'notextmatches'             => 'न कस्यापि पृष्ठस्य पाठः अस्य सममस्ति',
'prevn'                     => 'पूर्वतनानि {{PLURAL:$1|$1}}',
'nextn'                     => 'आगामि{{PLURAL:$1|$1}}',
'viewprevnext'              => 'दर्शयतु ($1) ($2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|1 शब्दम्|$2 शब्दे}})',
'search-redirect'           => '($1 इतीदं अनुप्रेषितम्)',
'search-section'            => '(विभागः $1)',
'search-suggest'            => 'किं भवतः आशयः एवमस्ति : $1',
'search-interwiki-default'  => '$1 परिणामाः :',
'search-interwiki-more'     => '(अधिकानि)',
'search-mwsuggest-enabled'  => 'उपक्षेपेभ्यः सह',
'search-mwsuggest-disabled' => 'नात्र उपक्षेपाः',
'showingresultstotal'       => "अधस्तात् {{PLURAL:$4| '''$1''' परिणामः '''$3''' इत्येभ्यः प्रदर्शितः|'''$1 - $2''' परिणामाः '''$3''' इत्येभ्यः प्रदर्शिताः}}",
'nonefound'                 => "'''सूचना''': स्वतः अत्र केषुचिदेव नामाकाशेषु अन्वेषणं क्रियते।

सकले घटके अन्वेषणं कर्तुं स्व अन्वेषणपदेभ्यः पूर्वं ''all:'' इति योजयतु, अथवा इष्टं नामाकाशं पूर्वलग्नरूपेण योजयतु।",
'powersearch'               => 'प्रगतम् अन्वेषणम्',
'powersearch-legend'        => 'प्रगतम् अन्वेषणम्',
'powersearch-ns'            => 'नामाकाशेषु अन्विष्यतु :',
'powersearch-redir'         => 'अनुप्रेषणानां सूचिकां दर्शयतु।',
'powersearch-field'         => 'इत्यस्मै अन्विष्यतु',

# Preferences page
'mypreferences' => 'मम वरीयांसि',
'yourlanguage'  => 'भाषा:',
'email'         => 'विद्युत्पत्रव्यवस्था',

# Groups
'group-sysop' => 'प्रबंधकाः',

'grouppage-sysop' => '{{ns:project}}:प्रचालकाः',

# Recent changes
'recentchanges'      => 'नवतमानि परिवर्तनानि',
'rcshowhideminor'    => '$1 लघूनि संपादनानि',
'rcshowhidebots'     => '$1 बोट् इत्येतानि',
'rcshowhideliu'      => '$1 प्रवेशिताः प्रयोक्तारः',
'rcshowhideanons'    => 'अनामकाः योजकाः $1',
'rcshowhidemine'     => '$1 मम संपादनानि',
'diff'               => 'अन्तरम्',
'hist'               => 'इति.',
'hide'               => 'लोपयतु',
'show'               => 'दर्शयतु',
'minoreditletter'    => 'लघु',
'newpageletter'      => 'न',
'boteditletter'      => 'बो',
'rc-enhanced-expand' => 'विवरणानि दर्शयतु (जावास्क्रिप्टम् आवश्यकम्)',
'rc-enhanced-hide'   => 'विवरणानि विलोपयतु',

# Recent changes linked
'recentchangeslinked'         => 'पृष्ठ-सम्बन्धि-परिवर्तनानि',
'recentchangeslinked-feed'    => 'सम्भन्दिन् परिवर्त',
'recentchangeslinked-toolbox' => 'सम्भन्दिन् परिवर्त',
'recentchangeslinked-summary' => "इदं पृष्ठं दर्शयति पृष्ठविशेषेण सह संबद्धीकृतेषु पृष्ठेषु परिवर्तनानि (अथवा श्रेणीविशेषे अन्तर्भूतेषु पृष्ठेषु परिवर्तनानि)।

भवतः निरीक्षासूचिकायां धारितानि पृष्ठाणि '''स्थूलाक्षरैः''' दर्शितानि।",

# Upload
'upload' => 'संचिकाम् उद्भारयतु',

# Special:ListFiles
'imgfile' => 'संचिका',

# File description page
'file-anchor-link'    => 'संचिका',
'filehist'            => 'संचिकायाः इतिहासः',
'filehist-help'       => 'संचिका तत्समये कीदृशी आसीदिति द्रष्टुं दिनांकः/समयः इत्यस्मिन् नोदयतु।',
'filehist-deleteone'  => 'विलोप',
'filehist-current'    => 'नवीनतमम्',
'filehist-datetime'   => 'दिनांकः/समयः',
'filehist-thumb'      => 'अंगुष्ठनखाकारम्',
'filehist-thumbtext'  => '$1 समये विद्यमत्याः आवृत्तेः अंगुष्ठनखाकारम्',
'filehist-user'       => 'प्रयोक्ता',
'filehist-dimensions' => 'आयामाः',
'filehist-comment'    => 'टिप्पणी',
'imagelinks'          => 'संचिका-संबंधनानि',
'linkstoimage'        => '{{PLURAL:$1|अधोलिखितं पृष्ठं| अधोलिखितानि $1 पृष्ठाणि}} इदं संचिकां प्रति संबंधनं {{PLURAL:$1|करोति| कुर्वन्ति}}।',
'sharedupload'        => 'इयं संचिका $1 इत्यस्मादस्ति, एषा खलु अन्येष्वपि प्रकल्पेषु प्रयोक्तुं शक्यते।',

# Random page
'randompage' => 'अविशिष्ट पृष्ठ',

# Statistics
'statistics' => 'सांख्यिकी',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|बैटम्|बैटानि}}',
'nmembers'      => '$1 {{PLURAL:$1|सदस्यः|सदस्याः}}',
'longpages'     => 'दीर्घाणि पृष्ठाणि',
'newpages'      => 'नूतनानि पृष्ठाणि',
'ancientpages'  => 'प्राचीनतम् पृष्ठा',
'move'          => 'नाम परिवर्तयतु',
'movethispage'  => 'इदं पृष्ठं चालयतु',
'pager-newer-n' => '{{PLURAL:$1|नूतनतरम् 1|नूतनतराणि $1}}',
'pager-older-n' => '{{PLURAL:$1|पुरातनतरम् 1|पुरातनतराणि $1}}',

# Book sources
'booksources-go' => 'प्रस्थानम्',

# Special:Log
'log' => 'लॉग् इत्येतानि',

# Special:AllPages
'allpages'       => 'सकलानि पृष्ठाणि',
'alphaindexline' => '$1 इत्यस्मात् $2 इतीदं यावत्',
'allarticles'    => 'सर्व लेखा',
'allpagessubmit' => 'गच्छतु',

# Special:Categories
'categories' => 'वर्ग',

# E-mail user
'emailsubject' => 'विषयः',
'emailmessage' => 'सन्देशः',

# Watchlist
'mywatchlist'      => 'मम निरीक्षासूचिका',
'addedwatch'       => 'निरीक्षासूचिकायां योजितम्',
'addedwatchtext'   => 'भवतः [[Special:Watchlist|ध्यानसूचिकायां]] "[[:$1]]" इत्येतत् योजितमस्ति।
इदानींप्रभृति अस्मिन् पृष्ठे तथा अस्य चर्चापृष्ठे सन्तः परिवर्तनानि भवतः निरीक्षासूचिकायां द्रक्ष्यन्ते तथा च [[Special:RecentChanges|सद्यःपरिवर्तितानां सूचिकायां]] इदं पृष्ठं स्थूलाक्षरैः द्रक्ष्यते, यस्मात् भवान् सरलतया इदं पश्यतु <p>निरीक्षासूचिकातः निराकर्तुमिच्छति चेत्, "मा निरीक्षताम्" इत्यसमिन् नोदयतु।',
'removedwatch'     => 'निरीक्षासूचिकातः निराकृतम्।',
'removedwatchtext' => '"[[:$1]]" इति पृष्ठं भवतः निरीक्षासूचिकातः निराकृतमस्ति।',
'watch'            => 'निरीक्षताम्',
'watchthispage'    => 'इदं पृष्ठं निरीक्षताम्',
'unwatch'          => 'मा निरीक्षताम्',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'निरीक्षते...',
'unwatching' => 'निरीक्षाम् अपाकरोति...',

# Delete
'deletepage'            => 'पृष्ठं निराकरोतु।',
'actioncomplete'        => 'कार्य समापनम्',
'deletedtext'           => '"<nowiki>$1</nowiki>" इत्येतद् अपाकृतमस्ति।
सद्यःकृतानां अपाकरणानाम् अभिलेखः $2 इत्यस्मिन् पश्यतु।',
'deletedarticle'        => '"[[$1]]" अपाकृतमस्ति।',
'dellogpage'            => 'अपाकरणानां सूचिका',
'deletecomment'         => 'निराकरणस्य कारणम् :',
'deleteotherreason'     => 'अपरं/अतिरिक्तं कारणम् :',
'deletereasonotherlist' => 'अपरं कारणम्',

# Rollback
'rollbacklink' => 'पूर्ण-प्रतिगमनम्',

# Protect
'protectcomment'              => 'टिप्पणी:',
'protect-cascadeon'           => 'इदं पृष्ठं वर्तमत्काले सुरक्षितमस्ति, यत इदं {{PLURAL:$1|निम्नलिखिते पृष्ठे |निम्नलिखितेषु पृष्ठेषु}} समाहितमस्ति {{PLURAL:$1|यस्मिन्|येषु}} सोपानात्मिका सुरक्षा प्रभाविनी अस्ति। भवान् अस्य पृष्ठस्य सुरक्षा-स्तरं परिवर्तयितुं शक्नोति, परं तेन सोपानात्मिका-सुरक्षा न परिवर्तयिष्यति।',
'protect-default'             => 'सर्वान् प्रयोक्तॄन् अनुज्ञापयतु।',
'protect-fallback'            => '"$1" अनुज्ञा आवश्यकी।',
'protect-level-autoconfirmed' => 'नूतनान् तथा अपंजीकृतान् प्रयोक्तॄन् निरुध्नातु।',
'protect-level-sysop'         => 'प्रबंधकाः केवलाः',
'protect-summary-cascade'     => 'सोपानात्मकम्',
'protect-expiring'            => 'अवसानम् $1 (UTC)',
'protect-cascade'             => 'अस्मिन् पृष्ठे समाहितानि पृष्ठाणि सुरक्षितानि करोतु (सोपानात्मिका सुरक्षा)।',
'protect-cantedit'            => 'भवान् अस्य पृष्ठस्य सुरक्षा-स्तरं परिवर्तयितुं न शक्नोति, यतो भवान् इदं पृष्ठं संपादयितुं अनुज्ञां न धारयति।',
'restriction-type'            => 'अनुमतिः:',
'restriction-level'           => 'सुरक्षा-स्तरः :',

# Undelete
'undeletelink' => 'दर्शयतु/पुनःस्थापयतु',

# Namespace form on various pages
'namespace'      => 'नामाकाशः',
'invert'         => 'चयनं विपरीतीकरोतु',
'blanknamespace' => '(मुख्यः)',

# Contributions
'contributions' => 'प्रयोक्तॄणां योगदानानि',
'mycontris'     => 'मम योगदानानि',

'sp-contributions-talk' => 'संवाद',

# What links here
'whatlinkshere'       => 'केभ्यः पृष्ठेभ्यः सम्बद्धम्',
'whatlinkshere-links' => '← निबन्धन',

# Block/unblock
'blocklink'    => 'अवरुध्नातु',
'unblocklink'  => 'अनिरुध्नातु',
'contribslink' => 'योगदानम्',

# Move page
'movearticle'    => 'पृष्ठं चालयतु :',
'newtitle'       => 'नूतनं शीर्षकं प्रति :',
'move-watch'     => 'इदं पृष्ठं निरीक्षताम्।',
'movepagebtn'    => 'पृष्ठं चालयतु।',
'pagemovedsub'   => 'चालनं सिद्धम्।',
'movepage-moved' => '<big>\'\'\'"$1" इत्येतद् "$2" इत्येतद् प्रति चालितमस्ति \'\'\'</big>',
'articleexists'  => 'अनेन नाम्ना पृष्ठमेकं पूर्वेऽपि विद्यते, अथवा भवता चितं नाम तु अमान्यमस्ति। कृपया इतरं किमपि नाम चिनोतु।',
'talkexists'     => "'''पृष्ठं साफल्येन चालितमस्ति, परं चर्चापृष्ठं चालयितुं न शक्यम्, यतो नवेऽपि पृष्ठे चर्चापृष्ठं विद्यते। कृपया तं स्वयमेव चालयतु।'''",
'movedto'        => 'इदं प्रति चालितम्।',
'movetalk'       => 'सहगामिनं चर्चापृष्ठं चालयतु।',
'movereason'     => 'कारणम् :',
'revertmove'     => 'पुरातनीं आवृत्तिं प्रति गमयतु',

# Export
'export' => 'पृष्ठाणां निर्यातं करोतु',

# Namespace 8 related
'allmessages'     => 'व्यवस्था सन्देशानि',
'allmessagesname' => 'नाम',

# Thumbnails
'thumbnail-more' => 'विस्तारयतु',

# Special:Import
'import-comment' => 'व्याखान:',

# Tooltip help for the actions
'tooltip-pt-userpage'           => 'भवतः प्रयोक्तृ-पृष्ठम्',
'tooltip-pt-mytalk'             => 'भवतः संभाषण-पृष्ठम्',
'tooltip-pt-preferences'        => 'भवतः वरीयांसि',
'tooltip-pt-watchlist'          => 'भवद्भिः निरीक्ष्यमतां पृष्ठाणां सूचिका',
'tooltip-pt-mycontris'          => 'भवतः योगदानानां सूचिका',
'tooltip-pt-login'              => 'भवान् लेखायां प्रविशतु इति श्रेयः परन्तु नावश्यकम्',
'tooltip-pt-logout'             => 'बहिर्गच्छतु',
'tooltip-ca-talk'               => 'घटक-पृष्ठ-विषये चर्चा',
'tooltip-ca-edit'               => 'भवान् इदं पृष्ठं संपादयितुं शक्नोति। कृपया रक्षणात्पूर्वं प्राग्दृश्यं पश्यतु।',
'tooltip-ca-addsection'         => 'नूतनं विभागम् आरभतु',
'tooltip-ca-viewsource'         => 'इदं पृष्ठं सुरक्षितं विद्यते। भवान् अस्य स्रोतसम् दृष्टुं शक्नोति।',
'tooltip-ca-history'            => 'अस्य पृष्ठस्य पुरातनाः आवृत्तयः',
'tooltip-ca-move'               => 'इदं पृष्ठं चालयतु',
'tooltip-ca-watch'              => 'इदं पृष्ठं स्व-निरीक्षासूचिकायां योजयतु।',
'tooltip-ca-unwatch'            => 'इदं पृष्ठं स्व-निरीक्षासूचिकातः अपाकरोतु',
'tooltip-search'                => '{{SITENAME}} अन्वेषणं करोति',
'tooltip-search-go'             => 'एतत्-शीर्षकीयम् पृष्ठं गच्छतु चेत् तद्विद्यते',
'tooltip-search-fulltext'       => 'एतत्पाठार्थम् पृष्ठेषु अन्विष्यतु',
'tooltip-p-logo'                => 'मुख्यपृष्ठम्  अभ्यागम्',
'tooltip-n-mainpage'            => 'मुखपृष्ठं प्रति गच्छतु',
'tooltip-n-portal'              => 'प्रकल्पविषये, भवता किं कर्तुं शक्यम्, कुत्र अन्वेषणं शक्यम्',
'tooltip-n-currentevents'       => 'सद्यःघटितानां घटनानां विषये पृष्ठभूमिक-सूचना',
'tooltip-n-recentchanges'       => 'सद्यःपरिवर्तितानां सूचिका',
'tooltip-n-randompage'          => 'यादृच्छिकमेकं पृष्ठं गच्छतु',
'tooltip-n-help'                => 'निराकरण-स्थानम्',
'tooltip-t-whatlinkshere'       => 'सर्वेषामपि एतत्संबद्धानां पृष्ठानां सूची',
'tooltip-t-recentchangeslinked' => 'अस्मात् पृष्ठात् संबंद्धीकृतेषु पृष्ठेषु नवीनतमानि परिवर्तनानि',
'tooltip-feed-rss'              => 'अस्मै पृष्ठाय आर-एस-एस-पूरणम्',
'tooltip-feed-atom'             => 'अस्मै पृष्ठाय ऍटम-पूरणम्',
'tooltip-t-upload'              => 'संचिकाः उद्भारयतु',
'tooltip-t-specialpages'        => 'सर्वेषां विशिष्ट-पृष्ठानां सूचिका',
'tooltip-t-print'               => 'अस्य पृष्ठस्य मुद्रणीया आवृत्तिः',
'tooltip-t-permalink'           => 'पृष्ठस्य इमाम् आवृत्तिं प्रति स्थायि संबंधनम्',
'tooltip-ca-nstab-main'         => 'घटक-पृष्ठं पश्यतु',
'tooltip-ca-nstab-user'         => 'प्रयोक्तॄ-पृष्ठं दर्शयतु',
'tooltip-ca-nstab-special'      => 'इदमेकं विशिष्टं पृष्ठम्, भवान् इदं पृष्ठं संपादयितुं न शक्नोति।',
'tooltip-ca-nstab-image'        => 'संचिकायाः पृष्ठं पश्यतु',
'tooltip-minoredit'             => 'इदं परिवर्तनं लघु-परिवर्तन-रूपेण दर्शयतु',
'tooltip-save'                  => 'परिवर्तनानि रक्षतु',
'tooltip-preview'               => 'भवता कृतानि परिवर्तनानि प्राग्दृश्यरूपेण पश्यतु, कृपया रक्षणात्पूर्वं इदं प्रयोजयतु।',
'tooltip-diff'                  => 'पाठे भवता कृतानि परिवर्तनानि पश्यतु।',
'tooltip-watch'                 => 'इदं पृष्ठं स्व-निरीक्षासूचिकायां योजयतु',

# Skin names
'skinname-standard'    => 'पूर्व',
'skinname-nostalgia'   => 'पुराण',
'skinname-cologneblue' => 'नील',
'skinname-monobook'    => 'पुस्तक',
'skinname-myskin'      => 'मे चर्मन्',
'skinname-chick'       => 'Chick',

# Browsing diffs
'previousdiff' => '← पुरातनतरं संपादनम्',

# Media information
'file-info-size' => '($1 × $2 पिक्सेलानि, संचिकायाः आकारः: $3, MIME-प्रकारः: $4)',
'show-big-image' => 'पूर्णं विभेदनम्',

# Special:NewFiles
'newimages' => 'नूतन-संचिकानां वीथिका',

# Bad image list
'bad_image_list' => 'प्रारूपम् एवम् अस्ति :

केवलानि सूचिकान्तर्गतानि वस्तूनि विचारितानि सन्ति (* इत्यस्मात् आरभमत्यः पंक्तयः)। पंक्त्यां प्रथमं संबंधनं त्रुटिपूर्णां संचिकां प्रति भवतु।

पंक्तौ परवर्तिनः संबंधनानि अपवादान् इव विचार्यन्ते, अर्थात् तादृशानि पृष्ठाणि यत्र संचिकैषा भवितुं शक्नोति।',

# Metadata
'metadata' => 'अधिसमंकम्',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'सर्वाणि',
'namespacesall' => 'सर्वाणि',
'monthsall'     => 'सर्वाः',

# Auto-summaries
'autosumm-new' => '$1 नवीन पृष्ठं निर्मीत अस्ती',

# Special:Version
'version' => 'आवृत्ति',

# Special:SpecialPages
'specialpages' => 'विशिष्ट-पृष्ठाणि',

);
