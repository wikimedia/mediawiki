<?php
/** Sanskrit (संस्कृत)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
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
	'currentmonth'          => array( '1', 'अद्यमासे', 'CURRENTMONTH' ),
	'currentmonthname'      => array( '1', 'अद्यमासेनाम', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'अद्यमासेनामसाधारण', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'अद्यमासेसंक्षीप्त', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'अद्यदिवसे', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'अद्यदिवसे२', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'अद्यदिवसेनाम', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'अद्यवर्ष', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'सद्यसमय', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'सद्यघण्टा', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'स्थानिकमासे', 'LOCALMONTH' ),
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
'january'   => 'पौषमाघे',
'february'  => 'फाल्गुने',
'march'     => 'फाल्गुनचैत्रे',
'april'     => 'मधुमासे',
'may_long'  => 'वैशाखज्येष्ठे',
'june'      => 'ज्येष्ठाषाढके',
'july'      => 'आषाढश्रावणे',
'august'    => 'नभस्ये',
'september' => 'भाद्रपदाश्विने',
'october'   => 'अश्विनकार्तिके',
'november'  => 'कार्तिकमार्गशीर्षे',
'december'  => 'मार्गशीर्षपौषे',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|वर्ग|वर्गा}}',
'listingcontinuesabbrev' => 'आगामि.',

'about'         => 'विषये',
'newwindow'     => '(उद्घट् नविन पृष्ठ)',
'cancel'        => 'अपकर्ष',
'qbfind'        => 'शोध',
'qbedit'        => 'संपादयति',
'qbpageoptions' => 'इदम्‌ पृष्ठ',
'qbmyoptions'   => 'मदीय लिखितपृष्ठ',
'mypage'        => 'मम पृष्ठ',
'mytalk'        => 'मम लोकप्रवाद',
'navigation'    => 'सुचालन',
'and'           => '&#32;एवम्',

'tagline'          => '{{SITENAME}}त्',
'help'             => 'सहायता',
'search'           => 'शोध',
'searchbutton'     => 'शोध',
'go'               => 'गच्छति',
'searcharticle'    => 'गच्छति',
'history'          => 'पृष्ठस्य इतिहास',
'history_short'    => 'इतिहास',
'printableversion' => 'मुद्रणस्य पाठ',
'permalink'        => 'स्थायी निबन्धन',
'print'            => 'मुद्रण',
'edit'             => 'सम्पादन',
'create'           => 'सृजति',
'editthispage'     => 'इदं पृष्ठस्य सम्पादनार्थ',
'create-this-page' => 'इदं पृष्ठ सृजामि',
'delete'           => 'विलोप',
'protect'          => 'सुरक्षित करोसि',
'protect_change'   => 'सुरक्षा नियम परिवर्त',
'newpage'          => 'नविन पृष्ठ',
'talkpagelinktext' => 'संवाद',
'specialpage'      => 'विशेष पृष्ठ',
'personaltools'    => 'वैयक्तिक साधन',
'talk'             => 'संवाद',
'views'            => 'दृश्य',
'toolbox'          => 'साधनपेटी',
'jumpto'           => 'कूर्दनं करोति :',
'jumptonavigation' => 'सुचालन',
'jumptosearch'     => 'शोध',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}विषये',
'aboutpage'            => 'Project:विषये',
'copyrightpage'        => '{{ns:project}}:प्रताधिकार',
'currentevents'        => 'सद्य घटना',
'disclaimers'          => 'स्वाम्यत्यागं',
'disclaimerpage'       => 'Project:स्वाम्यत्यागं',
'edithelp'             => 'संपादनार्थं सहायता',
'faq'                  => 'अतिप्रश्नपृष्ट',
'helppage'             => 'Help:सहाय्य',
'mainpage'             => 'मुख्यपृष्ठम्',
'mainpage-description' => 'मुख्यपृष्ठम्',
'privacy'              => 'गोपनीयविषये नीति',
'privacypage'          => 'Project:गोपनीयता नीति',

'retrievedfrom'   => 'इतः "$1" निसह्वे',
'newmessageslink' => 'नूतन संदेश',
'editsection'     => 'संपादयति',
'editsectionhint' => 'विभाग संपादन: $1',
'hidetoc'         => 'लुप्य',
'feedlinks'       => 'अनुबन्ध:',
'site-rss-feed'   => '$1 आरएसएस पूरयति',
'site-atom-feed'  => '$1 ऍटम पूरयति',
'page-rss-feed'   => '"$1" आरएसएस अनुबन्ध',
'page-atom-feed'  => '"$1" ऍटम अनुबन्ध',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'पृष्ठ',
'nstab-image'    => 'संचिका',
'nstab-template' => 'बिंबधर',
'nstab-category' => 'वर्ग',

# General errors
'error'         => 'विभ्रम',
'viewsource'    => 'स्रोत पश्यति',
'viewsourcefor' => '$1 कृते',

# Login and logout pages
'yourpassword'            => 'सङ्केतशब्द:',
'login'                   => 'प्रवेश करोसि',
'nav-login-createaccount' => 'प्रवेश करोसि/ सृज् उपयोजकसंज्ञा',
'userlogin'               => 'प्रवेश करोसि/ सृज् उपयोजकसंज्ञा',
'logout'                  => 'बहिर्गच्छति',
'userlogout'              => 'बहिर्गच्छति',
'createaccount'           => 'सृज उपयोजकसंज्ञा',
'gotaccountlink'          => 'प्रवेश करोसि',
'yourlanguage'            => 'भाषा:',
'email'                   => 'विद्युत्पत्रव्यवस्था',
'loginsuccesstitle'       => 'सुस्वागतम्‌ प्रवेश यशस्वी अस्ति',

# Edit page toolbar
'italic_sample' => 'इटालिकाक्षर्‌',

# Edit pages
'summary'     => 'सारांश:',
'watchthis'   => 'इदं पृष्ठ निरीक्षा',
'savearticle' => 'पृष्ठ त्रायते',
'preview'     => 'प्रारूप प्रेक्षा',
'showpreview' => 'प्रारूप प्रेक्षा',
'newarticle'  => '(नविन)',

# History pages
'currentrevisionlink' => 'सद्य आवृत्ती',
'cur'                 => 'अद्य',
'last'                => 'पूर्वतन',
'page_first'          => 'प्रथम्‌',
'page_last'           => 'अन्तिम',

# Revision feed
'history-feed-item-nocomment' => '$1 उप $2', # user at time

# Diffs
'lineno'   => 'रेखा $1:',
'editundo' => 'पूर्ववत करोसि',

# Search results
'nextn'       => 'आगामि$1',
'powersearch' => 'परिणत शोध',

# Recent changes
'recentchanges'   => 'नवीनतम परिवर्तन',
'rcshowhideanons' => 'अनामिक योजकस्य परिवर्त $1',
'hist'            => 'इति.',
'hide'            => 'प्रछद्',
'show'            => 'दर्शयति',
'minoreditletter' => 'ल्घु',
'newpageletter'   => 'न',
'boteditletter'   => 'य',

# Recent changes linked
'recentchangeslinked' => 'सम्भन्दिन् परिवर्त',

# Upload
'upload' => 'भारं न्यस्यति सञ्चिका',

# Special:ListFiles
'imgfile' => 'संचिका',

# File description page
'filehist-deleteone' => 'विलोप',

# Random page
'randompage' => 'अविशिष्ट पृष्ठ',

# Statistics
'statistics' => 'सांख्यिकी',

# Miscellaneous special pages
'longpages'    => 'दीर्घ पृष्ठ',
'newpages'     => 'नूतन पृष्ठ',
'ancientpages' => 'प्राचीनतम् पृष्ठा',
'move'         => 'नामभेद',
'movethispage' => 'इदं पृष्ठस्य स्थानांतर',

# Book sources
'booksources-go' => 'प्रस्थानम्',

# Special:AllPages
'allpages'       => 'सर्व पृष्ठ',
'alphaindexline' => 'इतः $1 यावत् $2',
'allarticles'    => 'सर्व लेखा',
'allpagessubmit' => 'गच्छति',

# Special:Categories
'categories' => 'वर्ग',

# E-mail user
'emailsubject' => 'विषयः',
'emailmessage' => 'सन्देशः',

# Watchlist
'watch'         => 'निरीक्षति',
'watchthispage' => 'प्रतिरक्षति इदं पृष्ठ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'निरिक्षा',

# Delete
'actioncomplete' => 'कार्य समापनम्',

# Protect
'protectcomment'          => 'प्रतिक्रीया:',
'protect-level-sysop'     => 'केवल प्रबंधक',
'protect-summary-cascade' => 'निःश्रेणि',
'restriction-type'        => 'अनुमति:',

# Namespace form on various pages
'namespace'      => 'नामविश्व:',
'blanknamespace' => '(मुख्य)',

# What links here
'whatlinkshere'       => 'किम्‌ पृष्ठ सम्बद्धं करोति',
'whatlinkshere-links' => '← निबन्धन',

# Block/unblock
'blocklink'    => 'निषेध',
'contribslink' => 'योगदान',

# Namespace 8 related
'allmessages'     => 'व्यवस्था सन्देशानि',
'allmessagesname' => 'नाम',

# Thumbnails
'thumbnail-more' => 'विस्तार',

# Special:Import
'import-comment' => 'व्याखान:',

# Tooltip help for the actions
'tooltip-pt-logout'       => 'बहिर्गच्छति',
'tooltip-search'          => '{{SITENAME}} अन्वेषणं करोति',
'tooltip-p-logo'          => 'मुख्यपृष्ठम्  अभ्यागम्',
'tooltip-n-mainpage'      => 'मुख्यपृष्ठम्  अभ्यागम्',
'tooltip-n-portal'        => 'प्रकल्प विषये,भवदिय त्वां किम्‌ करोति, शोधिका',
'tooltip-n-recentchanges' => 'नविनतम परिवर्तन सूची',
'tooltip-n-randompage'    => 'अविशीष्ट लेख',
'tooltip-n-help'          => 'शोधन्‌ स्थानम्‌।',
'tooltip-t-upload'        => 'भारं न्यस्यति संचिका',
'tooltip-t-specialpages'  => 'सर्वानि विशेष पृष्ठस्य सूची',
'tooltip-save'            => 'त्रायते',

# Skin names
'skinname-standard'    => 'पूर्व',
'skinname-nostalgia'   => 'पुराण',
'skinname-cologneblue' => 'नील',
'skinname-monobook'    => 'पुस्तक',
'skinname-myskin'      => 'मे चर्मन्',
'skinname-chick'       => 'Chick',

# Special:NewFiles
'newimages' => 'नूतन संचिका दालन',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'सर्व',
'namespacesall' => 'सर्व',
'monthsall'     => 'सर्व',

# Auto-summaries
'autosumm-new' => 'नवीन पृष्ठं: $1',

# Special:Version
'version' => 'आवृत्ति', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages' => 'विशेष पृष्ठ',

);
