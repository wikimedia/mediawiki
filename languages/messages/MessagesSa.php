<?php
/** Sanskrit (संस्कृतम्)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Abhirama
 * @author Ansumang
 * @author Bharata
 * @author Bhawani Gautam
 * @author Hemant wikikosh1
 * @author Hrishikesh.kb
 * @author Htt
 * @author Kaustubh
 * @author Krinkle
 * @author Mahitgar
 * @author Naveen Sankar
 * @author Omnipaedista
 * @author Shantanoo
 * @author Shijualex
 * @author Shreekant Hegde
 * @author Shubha
 * @author Vibhijain
 * @author రాకేశ్వర
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
	NS_MEDIA            => 'माध्यमम्',
	NS_SPECIAL          => 'विशेषम्',
	NS_TALK             => 'सम्भाषणम्',
	NS_USER             => 'योजकः',
	NS_USER_TALK        => 'योजकसम्भाषणम्',
	NS_PROJECT_TALK     => '$1सम्भाषणम्',
	NS_FILE             => 'चित्रम्',
	NS_FILE_TALK        => 'चित्रसम्भाषणम्',
	NS_MEDIAWIKI        => 'मिडीयाविकी',
	NS_MEDIAWIKI_TALK   => 'मिडियाविकीसम्भाषणम्',
	NS_TEMPLATE         => 'फलकम्',
	NS_TEMPLATE_TALK    => 'फलकस्य_सम्भाषणम्',
	NS_HELP             => 'सहाय्यम्',
	NS_HELP_TALK        => 'सहाय्यस्य_सम्भाषणम्',
	NS_CATEGORY         => 'वर्गः',
	NS_CATEGORY_TALK    => 'वर्गसम्भाषणम्',
);

$namespaceAliases = array(
	'माध्यम'             => NS_MEDIA,
	'विशेष'              => NS_SPECIAL,
	'संभाषणं'            => NS_TALK,
	'योजकसंभाषणं'        => NS_USER_TALK,
	'$1संभाषणं'         => NS_PROJECT_TALK,
	'चित्रं'             => NS_FILE,
	'चित्रसंभाषणं'       => NS_FILE_TALK,
	'मिडियाविकीसंभाषणं' => NS_MEDIAWIKI_TALK,
	'बिंबधर'             => NS_TEMPLATE,
	'बिंबधर संभाषणं'      => NS_TEMPLATE_TALK,
	'सहाय्य'             => NS_HELP,
	'सहाय्यसंभाषणं'      => NS_HELP_TALK,
	'उपकारः'             => NS_HELP,
	'उपकारसंभाषणं'        => NS_HELP_TALK,
	'वर्गसंभाषणं'         => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'सर्वप्रणाली-संदेश' ),
	'Allpages'                  => array( 'सर्वपृष्टानि' ),
	'Ancientpages'              => array( 'पूर्वतनपृष्टानि' ),
	'Blankpage'                 => array( 'रिक्तपृष्ठ' ),
	'Block'                     => array( 'सदस्यप्रतिबन्ध' ),
	'Blockme'                   => array( 'मदर्थेप्रतिबन्ध' ),
	'Booksources'               => array( 'पुस्तकस्रोत' ),
	'BrokenRedirects'           => array( 'खण्डीतपुनर्निर्देशन' ),
	'Categories'                => array( 'वर्गः' ),
	'ChangePassword'            => array( 'सङ्केतशब्दपुन:प्रयुक्ता' ),
	'Confirmemail'              => array( 'विपत्रपुष्टिकृते' ),
	'Contributions'             => array( 'योगदानम्' ),
	'CreateAccount'             => array( 'सृज्उपयोजकसंज्ञा' ),
	'Deadendpages'              => array( 'निराग्रपृष्टानि' ),
	'DeletedContributions'      => array( 'परित्यागितयोगदान' ),
	'Disambiguations'           => array( 'नि:संदिग्धीकरण' ),
	'DoubleRedirects'           => array( 'पुनर्निर्देशनद्वंद्व' ),
	'Emailuser'                 => array( 'विपत्रयोजक' ),
	'Export'                    => array( 'निर्यात' ),
	'Fewestrevisions'           => array( 'स्वल्पपरिवर्तन' ),
	'FileDuplicateSearch'       => array( 'अनुकृतसंचिकाशोध' ),
	'Filepath'                  => array( 'संचिकापथ' ),
	'Import'                    => array( 'आयात' ),
	'Invalidateemail'           => array( 'अमान्यविपत्र' ),
	'BlockList'                 => array( 'प्रतिबन्धसूची' ),
	'LinkSearch'                => array( 'सम्बन्धन्‌शोध' ),
	'Listadmins'                => array( 'प्रचालकसूची' ),
	'Listbots'                  => array( 'स्वयंअनुकृसूची' ),
	'Listfiles'                 => array( 'चित्रसूची', 'संचिकासूचि' ),
	'Listgrouprights'           => array( 'गटअधिकारसूची' ),
	'Listredirects'             => array( 'विचालन्‌सूची' ),
	'Listusers'                 => array( 'सदस्यासूची' ),
	'Lockdb'                    => array( 'विदाद्वारंबन्ध्' ),
	'Log'                       => array( 'अङ्कन' ),
	'Lonelypages'               => array( 'अकलपृष्टानि' ),
	'Longpages'                 => array( 'दीर्घपृष्टानि' ),
	'MergeHistory'              => array( 'इतिहाससंयोग' ),
	'MIMEsearch'                => array( 'विविधामाप_(माईम)_शोधसि' ),
	'Mostcategories'            => array( 'अधिकतमवर्ग' ),
	'Mostimages'                => array( 'अधिकतमसम्भन्दिन्_संचिका' ),
	'Mostlinked'                => array( 'अधिकतमसम्भन्दिन्_पृष्टानि', 'अधिकतमसम्भन्दिन्' ),
	'Mostlinkedcategories'      => array( 'अधिकतमसम्भन्दिन्_वर्ग' ),
	'Mostlinkedtemplates'       => array( 'अधिकतमसम्भन्दिन्_फलकानि' ),
	'Mostrevisions'             => array( 'अधिकतमपरिवर्तन' ),
	'Movepage'                  => array( 'पृष्ठस्थानान्तर' ),
	'Mycontributions'           => array( 'मदीययोगदानम्' ),
	'Mypage'                    => array( 'मम_पृष्टम्' ),
	'Mytalk'                    => array( 'मदीयसंवादम्' ),
	'Newimages'                 => array( 'नूतनसंचिका', 'नूतनचित्रानि' ),
	'Newpages'                  => array( 'नूतनपृष्टानि' ),
	'PasswordReset'             => array( 'सङ्केतशब्दपुन:प्रयु्क्ता' ),
	'Popularpages'              => array( 'लोकप्रियपृष्टानि' ),
	'Preferences'               => array( 'इष्टतमानि' ),
	'Prefixindex'               => array( 'उपसर्गअनुक्रमणी' ),
	'Protectedpages'            => array( 'सुरक्षितपृष्टानि' ),
	'Protectedtitles'           => array( 'सुरक्षितशिर्षकम्' ),
	'Randompage'                => array( 'अविशीष्टपृष्ठम्' ),
	'Randomredirect'            => array( 'अविशीष्टविचालन्‌' ),
	'Recentchanges'             => array( 'नवीनतम_परिवर्तन' ),
	'Recentchangeslinked'       => array( 'नवीनतमसम्भन्दिन_परिवर्त' ),
	'Revisiondelete'            => array( 'आवृत्तीपरित्याग' ),
	'Search'                    => array( 'शोध' ),
	'Shortpages'                => array( 'लघुपृष्टानि' ),
	'Specialpages'              => array( 'विशेषपृष्टानि' ),
	'Statistics'                => array( 'सांख्यिकी' ),
	'Uncategorizedcategories'   => array( 'अवर्गीकृतवर्ग' ),
	'Uncategorizedimages'       => array( 'अवर्गीकृतसंचिका', 'अवर्गीकृतचित्रानि' ),
	'Uncategorizedpages'        => array( 'अवर्गीकृतपृष्टानि' ),
	'Uncategorizedtemplates'    => array( 'अवर्गीकृतफलकानि' ),
	'Undelete'                  => array( 'प्रत्यादिश्_परित्याग' ),
	'Unlockdb'                  => array( 'विवृतविदाद्वारंतालक' ),
	'Unusedcategories'          => array( 'अप्रयूक्तवर्ग' ),
	'Unusedimages'              => array( 'अप्रयूक्तसंचिका' ),
	'Unusedtemplates'           => array( 'अप्रयूक्तबिंबधर' ),
	'Unwatchedpages'            => array( 'अनिरिक्षीतपृष्ठ' ),
	'Upload'                    => array( 'भारंन्यस्यति' ),
	'Userlogin'                 => array( 'सदस्यप्रवेशन' ),
	'Userlogout'                => array( 'सदस्यबहिर्गमन' ),
	'Userrights'                => array( 'योजकआधिकार' ),
	'Version'                   => array( 'आवृत्ती' ),
	'Wantedcategories'          => array( 'प्रार्थितवर्ग' ),
	'Wantedfiles'               => array( 'प्रार्थितसंचिका' ),
	'Wantedpages'               => array( 'प्रार्थितपृष्टानि' ),
	'Wantedtemplates'           => array( 'प्रार्थितफलकानि' ),
	'Watchlist'                 => array( 'निरीक्षा_सूची' ),
	'Whatlinkshere'             => array( 'किमपृष्ठ_सम्बद्धंकरोति' ),
	'Withoutinterwiki'          => array( 'आन्तरविकिहीन' ),
);

$magicWords = array(
	'redirect'                => array( '0', '#पुनर्निदेशन', '#REDIRECT' ),
	'notoc'                   => array( '0', '__नैवअनुक्रमणी__', '__NOTOC__' ),
	'nogallery'               => array( '0', '__नैवसंक्रमणका__', '__NOGALLERY__' ),
	'forcetoc'                => array( '0', '__अनुक्रमणीसचते__', '__FORCETOC__' ),
	'toc'                     => array( '0', '__अनुक्रमणी__', '__TOC__' ),
	'noeditsection'           => array( '0', '__नैवसम्पादनविभाग__', '__NOEDITSECTION__' ),
	'noheader'                => array( '0', '__नैवमुख्यशिर्षक__', '__NOHEADER__' ),
	'currentmonth'            => array( '1', 'अद्यमासे', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'        => array( '1', 'अद्यमासेनाम', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'     => array( '1', 'अद्यमासेनामसाधारण', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'      => array( '1', 'अद्यमासेसंक्षीप्त', 'CURRENTMONTHABBREV' ),
	'currentday'              => array( '1', 'अद्यदिवसे', 'CURRENTDAY' ),
	'currentday2'             => array( '1', 'अद्यदिवसे२', 'CURRENTDAY2' ),
	'currentdayname'          => array( '1', 'अद्यदिवसेनाम', 'CURRENTDAYNAME' ),
	'currentyear'             => array( '1', 'अद्यवर्ष', 'CURRENTYEAR' ),
	'currenttime'             => array( '1', 'सद्यसमय', 'CURRENTTIME' ),
	'currenthour'             => array( '1', 'सद्यघण्टा', 'CURRENTHOUR' ),
	'localmonth'              => array( '1', 'स्थानिकमासे', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'          => array( '1', 'स्थानिकमासेनाम', 'LOCALMONTHNAME' ),
	'localmonthnamegen'       => array( '1', 'स्थानिकमासेनामसाधारण', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'        => array( '1', 'स्थानिकमासेसंक्षीप्त', 'LOCALMONTHABBREV' ),
	'localday'                => array( '1', 'स्थानिकदिवसे', 'LOCALDAY' ),
	'localday2'               => array( '1', 'स्थानिकदिवसे२', 'LOCALDAY2' ),
	'localdayname'            => array( '1', 'स्थानिकदिवसेनाम', 'LOCALDAYNAME' ),
	'localyear'               => array( '1', 'स्थानिकवर्षे', 'LOCALYEAR' ),
	'localtime'               => array( '1', 'स्थानिकसमये', 'LOCALTIME' ),
	'localhour'               => array( '1', 'स्थानिकघण्टा', 'LOCALHOUR' ),
	'numberofpages'           => array( '1', 'पृष्ठानाम्‌सङ्ख्या', 'NUMBEROFPAGES' ),
	'numberofarticles'        => array( '1', 'लेखस्य‌सङ्ख्या', 'NUMBEROFARTICLES' ),
	'numberoffiles'           => array( '1', 'संचिकानाम्‌‌सङ्ख्या', 'NUMBEROFFILES' ),
	'numberofusers'           => array( '1', 'योजकस्यसङ्ख्या', 'NUMBEROFUSERS' ),
	'numberofedits'           => array( '1', 'सम्पादनसङ्ख्या', 'NUMBEROFEDITS' ),
	'numberofviews'           => array( '1', 'दृष्टिसङ्ख्या', 'NUMBEROFVIEWS' ),
	'pagename'                => array( '1', 'पृष्ठनाम', 'PAGENAME' ),
	'namespace'               => array( '1', 'नामविश्व', 'NAMESPACE' ),
	'talkspace'               => array( '1', 'व्यासपिठ', 'TALKSPACE' ),
	'subjectspace'            => array( '1', 'विषयविश्व', 'लेखविश्व', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'fullpagename'            => array( '1', 'पूर्णपृष्ठनाम', 'FULLPAGENAME' ),
	'subpagename'             => array( '1', 'उपपृष्ठनाम', 'SUBPAGENAME' ),
	'basepagename'            => array( '1', 'आधारपृष्ठनाम', 'BASEPAGENAME' ),
	'talkpagename'            => array( '1', 'संवादपृष्ठनाम', 'TALKPAGENAME' ),
	'subjectpagename'         => array( '1', 'विषयपृष्ठनाम', 'लेखपृष्ठनाम', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'msg'                     => array( '0', 'सन्देश:', 'MSG:' ),
	'msgnw'                   => array( '0', 'नूतनसन्देश:', 'MSGNW:' ),
	'img_thumbnail'           => array( '1', 'लघुत्तम', 'सङ्कुचितचित्र', 'अङ्गुष्ठ', 'thumbnail', 'thumb' ),
	'img_manualthumb'         => array( '1', 'सङ्कुचितचित्र=$1', 'अङ्गुष्ठ=$1', 'लघुत्तमचित्र=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'               => array( '1', 'दक्षिणत', 'right' ),
	'img_left'                => array( '1', 'वामतः', 'left' ),
	'img_none'                => array( '1', 'नैव', 'none' ),
	'img_width'               => array( '1', '$1पिट', '$1px' ),
	'img_center'              => array( '1', 'मध्य', 'center', 'centre' ),
	'img_framed'              => array( '1', 'आबन्ध', 'framed', 'enframed', 'frame' ),
	'img_frameless'           => array( '1', 'निराबन्ध', 'frameless' ),
	'img_page'                => array( '1', 'पृष्ठ=$1', 'पृष्ठ $1', 'page=$1', 'page $1' ),
	'img_upright'             => array( '1', 'उन्नत', 'उन्नत=$1', 'उन्नत $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'              => array( '1', 'सीमा', 'border' ),
	'img_baseline'            => array( '1', 'आधाररेखा', 'baseline' ),
	'img_sub'                 => array( '1', 'विषये', 'sub' ),
	'img_super'               => array( '1', 'अति', 'तीव्र', 'super', 'sup' ),
	'img_top'                 => array( '1', 'अग्र', 'top' ),
	'img_text_top'            => array( '1', 'पाठ्य-अग्र', 'text-top' ),
	'img_middle'              => array( '1', 'मध्ये', 'middle' ),
	'img_bottom'              => array( '1', 'अधस', 'bottom' ),
	'img_text_bottom'         => array( '1', 'पाठ्य-अधस', 'text-bottom' ),
	'img_link'                => array( '1', 'सम्बद्धं=$1', 'link=$1' ),
	'img_alt'                 => array( '1', 'विकल्प=$1', 'alt=$1' ),
	'sitename'                => array( '1', 'स्थलनाम', 'SITENAME' ),
	'grammar'                 => array( '0', 'व्याकरण:', 'GRAMMAR:' ),
	'notitleconvert'          => array( '0', '__नैवशिर्षकपरिवर्त__', '__नैशिप__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'        => array( '0', '__नैवलेखपरिवर्त__', '__नैलेप__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'             => array( '1', 'अद्यसप्ताह', 'CURRENTWEEK' ),
	'localweek'               => array( '1', 'स्थानिकसप्ताह', 'LOCALWEEK' ),
	'revisionid'              => array( '1', 'आवृत्तीक्रमांक', 'REVISIONID' ),
	'revisionday'             => array( '1', 'आवृत्तीदिवसे', 'REVISIONDAY' ),
	'revisionday2'            => array( '1', 'आवृत्तीदिवसे२', 'REVISIONDAY2' ),
	'revisionmonth'           => array( '1', 'आवृत्तीमासे', 'REVISIONMONTH' ),
	'revisionyear'            => array( '1', 'आवृत्तीवर्षे', 'REVISIONYEAR' ),
	'revisiontimestamp'       => array( '1', 'आवृत्तीसमयमुद्रा', 'REVISIONTIMESTAMP' ),
	'plural'                  => array( '0', 'अनेकवचन:', 'PLURAL:' ),
	'displaytitle'            => array( '1', 'प्रदर्शनशीर्षक', 'उपाधिदर्शन', 'DISPLAYTITLE' ),
	'newsectionlink'          => array( '1', '__नूतनविभागसम्बद्धं__', '__NEWSECTIONLINK__' ),
	'currentversion'          => array( '1', 'अद्यआवृत्ती', 'CURRENTVERSION' ),
	'currenttimestamp'        => array( '1', 'सद्यसमयमुद्रा', 'CURRENTTIMESTAMP' ),
	'localtimestamp'          => array( '1', 'स्थानिकसमयमुद्रा', 'LOCALTIMESTAMP' ),
	'directionmark'           => array( '1', 'दिशाचिह्न', 'दिशे', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                => array( '0', '#भाषा:', '#LANGUAGE:' ),
	'contentlanguage'         => array( '1', 'विषयभाषा', 'आधेयभाषा', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'        => array( '1', 'नामविश्वातपृष्ठ', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'          => array( '1', 'प्रचालकसंख्या', 'NUMBEROFADMINS' ),
	'formatnum'               => array( '0', 'रचनासंख्या', 'FORMATNUM' ),
	'special'                 => array( '0', 'विशेष', 'special' ),
	'filepath'                => array( '0', 'संचिकापथ', 'FILEPATH:' ),
	'tag'                     => array( '0', 'वीजक', 'tag' ),
	'hiddencat'               => array( '1', '__लुप्तवर्ग__', '__HIDDENCAT__' ),
	'pagesincategory'         => array( '1', 'वर्गेपृष्ठ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                => array( '1', 'पृष्ठाकार', 'PAGESIZE' ),
	'index'                   => array( '1', '__अनुक्रमणिका__', '__INDEX__' ),
	'noindex'                 => array( '1', '__नैवअनुक्रमणिका__', '__NOINDEX__' ),
	'numberingroup'           => array( '1', 'गणानामसंख्या', 'गणसंख्या', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'          => array( '1', '__अनित्यपुनर्निदेशन__', '__STATICREDIRECT__' ),
	'protectionlevel'         => array( '1', 'रक्षास्तर', 'PROTECTIONLEVEL' ),
);

$digitGroupingPattern = "##,##,###";

$messages = array(
# User preference toggles
'tog-underline'               => 'सम्पर्कतन्तोः अधोरेखाङ्कनम्:',
'tog-highlightbroken'         => 'विच्छिन्नाः सम्पर्कतन्तवः <a href="" class="new"> एवं दर्श्यन्ताम् </a> (अथवा : एवं दर्श्यन्ताम् <a href="" class="internal">?</a>)।',
'tog-justify'                 => 'परिच्छेदाः समानाः क्रियन्ताम्',
'tog-hideminor'               => 'सद्योजातानां परिवर्तनानां लघूनि सम्पादनानि गोप्यन्ताम्',
'tog-hidepatrolled'           => 'सद्योजातानां परिवर्तनानां परिशीलितानि सम्पादनानि गोप्यन्ताम्',
'tog-newpageshidepatrolled'   => 'नूतनपृष्ठानाम् आवलीतः परिशीलितानि पृष्ठानि गोप्यन्त्ताम्',
'tog-extendwatchlist'         => 'अवेक्षणसूच्यां सर्वाणि परिवर्तनानि दर्श्यन्ताम्, न केवलं सद्योजातानि',
'tog-usenewrc'                => 'विस्तृतानि सद्योजातानि परिवर्तनानि उपयुज्यन्ताम् (जावालिपिः अपेक्ष्यते)',
'tog-numberheadings'          => 'शीर्षकान् स्वयमेव सक्रमांकीकरोतु।',
'tog-showtoolbar'             => 'सम्पादन-उपकरण-पट्टिका दर्श्यताम् (जावालिपिः अपेक्ष्यते)',
'tog-editondblclick'          => 'द्विक्लिक्कारेण पृष्ठानि सम्पाद्यन्ताम् (जावालिपिः अपेक्ष्यते)',
'tog-editsection'             => '[संपादयतु़] इति संबंधनद्वारा विभाग-संपादनं समर्थयतु।',
'tog-editsectiononrightclick' => 'विभागशीर्षकाणामुपरि दक्षिणक्लिक्करणेन विभागसम्पादनं समर्थ्यताम् (जावालिपिः अपेक्ष्यते)।',
'tog-showtoc'                 => 'विषयानुक्रमणिका दर्श्यताम् (त्र्यधिकशीर्षकयुतेषु पृष्ठेषु)।',
'tog-rememberpassword'        => 'अस्मिन् सङ्गणके मम प्रवेशः स्मर्यताम् (अधिकतमम् $1 {{PLURAL:$1|दिनम्|दिनानि}})',
'tog-watchcreations'          => 'मया रचितानि पृष्ठानि मम अवेक्षणसूच्यां योज्यन्ताम्।',
'tog-watchdefault'            => 'मया सम्पादितानि पृष्ठानि मम अवेक्षणसूच्यां योज्यन्ताम्।',
'tog-watchmoves'              => 'मया चालितानि पृष्ठानि मम अवेक्षणसूच्यां योज्यन्ताम्।',
'tog-watchdeletion'           => 'मया अपाकृतानि पृष्ठानि मम अवेक्षणसूच्यां योज्यन्ताम्।',
'tog-minordefault'            => '
मम सर्वाणि सम्पादनानि लघुत्वेन वर्त्यन्ताम्।',
'tog-previewontop'            => 'सम्पादनात् पूर्वं प्राग्दृश्यं दर्श्यताम्।',
'tog-previewonfirst'          => 'प्रथमसम्पादनस्य प्राग्दृश्यं दर्श्यताम्।',
'tog-nocache'                 => 'पृष्ठ धारक-ब्राउजरं निस्क्रियतु ।',
'tog-enotifwatchlistpages'    => 'मम अवेक्षणसूच्यां विद्यमाने पृष्ठे परिवर्तिते सति ईपत्रद्वारा ज्ञाप्यताम्।',
'tog-enotifusertalkpages'     => 'मम योजकसंभाषणपृष्ठे परिवर्तिते सति ईपत्रद्वारा ज्ञाप्यताम्',
'tog-enotifminoredits'        => 'लघुपरिवर्तनेषु सत्सु अपि ईपत्रद्वारा ज्ञाप्यताम्',
'tog-enotifrevealaddr'        => 'अधिसूचना-ईपत्रेषु मम ईपत्रसङ्केतः प्रदर्श्यताम्',
'tog-shownumberswatching'     => 'निरीक्षमाणानां योजकानां संख्या दर्श्यताम्',
'tog-oldsig'                  => 'विद्यमानं हस्ताङ्कनम्:',
'tog-fancysig'                => 'हस्ताक्षराणि विकिपाठवत् सन्तु (स्वचालित-संबंधनेभ्यः रहितानि)।',
'tog-externaleditor'          => 'Use external editor by default (for experts only, needs special settings on your computer. [//www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-externaldiff'            => 'अकथिते (बाइ डिफाल्ट् इति), बाह्य अंतरक्रमादेशं प्रयोजयतु (केवलेभ्यः निपुणेभ्यः, भवतः संगणके विशेषाः न्यासाः आवश्यकाः)।',
'tog-showjumplinks'           => '"इत्येतत् प्रति कूर्दयतु" इति संबंधनानि समर्थयतु।',
'tog-uselivepreview'          => 'संपादनेन सहैव प्राग्दृश्यं दर्शयतु (जावालिपिः अपेक्ष्यते) (प्रयोगात्मकम्)।',
'tog-forceeditsummary'        => 'सम्पादनसारांशः न ददामि चेत् तदा मां ज्ञापयतु।',
'tog-watchlisthideown'        => 'मम सम्पादनानि अवेक्षणसूच्याः गोप्यन्ताम्।',
'tog-watchlisthidebots'       => 'बोट्कृतानि सम्पादनानि अवेक्षणसूच्याः गोप्यन्ताम्।',
'tog-watchlisthideminor'      => 'मम अवेक्षणसूच्याः लघूनि सम्पादनानि गोप्यन्ताम्।',
'tog-watchlisthideliu'        => 'प्रविष्टैः योजकैः कृतानि सम्पादनानि अवेक्षणसूच्याः गोप्यन्ताम्।',
'tog-watchlisthideanons'      => 'अनामकैः योजकैः कृतानि सम्पादनानि अवेक्षणसूच्याः गोप्यन्ताम्।',
'tog-watchlisthidepatrolled'  => 'मम अवेक्षणसूच्याः दृष्टपूर्वाणि सम्पादनानि गोप्यन्ताम्।',
'tog-ccmeonemails'            => 'अन्येभ्यः प्रेषितानाम् ईपत्राणां प्रतिकृतिः मत्कृते प्रेष्यताम्',
'tog-diffonly'                => 'आवृत्तिसु अंतरं दर्शयन् पुरातनाः आवृत्तयः मा दर्शयतु।',
'tog-showhiddencats'          => 'निगूढाः वर्गाः दर्श्यन्ताम्',
'tog-norollbackdiff'          => 'पूर्णप्रतिगमने कृते मा दर्शयतु तद् अंतरम्।',

'underline-always'  => 'सर्वदा',
'underline-never'   => 'कदापि न',
'underline-default' => 'ब्राउसर अकथितप्रकरणम्।',

# Font style option in Special:Preferences
'editfont-style'     => 'सम्पादन-क्षेत्रस्य मुद्राक्षराणां शैली:',
'editfont-default'   => 'विचरकस्य अकथित-प्रकरणानुसारम् (ब्राउसर् डिफ़ॉल्ट्)',
'editfont-monospace' => 'एकलान्तरितानि मुद्राक्षराणि',
'editfont-sansserif' => 'कोणविहीनानि मुद्राक्षराणि',
'editfont-serif'     => 'सकोणानि मुद्राक्षराणि',

# Dates
'sunday'        => 'रविवासरः',
'monday'        => 'सोमवासरः',
'tuesday'       => 'मङ्गलवासरः',
'wednesday'     => 'बुधवासरः',
'thursday'      => 'गुरुवासरः',
'friday'        => 'शुक्रवासरः',
'saturday'      => 'शनिवासरः',
'sun'           => 'रविः',
'mon'           => 'सोमः',
'tue'           => 'मङ्गलः',
'wed'           => 'बुधः',
'thu'           => 'गुरुः',
'fri'           => 'शुक्रः',
'sat'           => 'शनिः',
'january'       => 'जनुवरि',
'february'      => 'फ़ेब्रुवरि',
'march'         => 'मार्च्',
'april'         => 'एप्रिल्',
'may_long'      => 'मेय्',
'june'          => 'जून्',
'july'          => 'जूलय्',
'august'        => 'ओगस्ट्',
'september'     => 'सप्तम्बर्',
'october'       => 'अष्टोबर्',
'november'      => 'नवम्बर्',
'december'      => 'दशम्बर्',
'january-gen'   => 'जनुवरि',
'february-gen'  => 'फे़ब्रुवरि',
'march-gen'     => 'मार्च्',
'april-gen'     => 'एप्रिल्',
'may-gen'       => 'मेय्',
'june-gen'      => 'जून्',
'july-gen'      => 'जुलै',
'august-gen'    => 'ओगस्ट्',
'september-gen' => 'सप्तम्बर्',
'october-gen'   => 'अष्टोबर्',
'november-gen'  => 'नवम्बर्',
'december-gen'  => 'दशम्बर्',
'jan'           => 'जनु॰',
'feb'           => 'फ़ेब्रु॰',
'mar'           => 'मार्च्',
'apr'           => 'एप्रि॰',
'may'           => 'मेय्',
'jun'           => 'जून्',
'jul'           => 'जूलय्',
'aug'           => 'ओग॰',
'sep'           => 'सप्तं॰',
'oct'           => 'अष्टो॰',
'nov'           => 'नवं॰',
'dec'           => 'दशं॰',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|वर्गः|वर्गाः }}',
'category_header'                => '"$1" इत्येतस्मिन् वर्गे विद्यमानानि पृष्ठानि',
'subcategories'                  => 'उपवर्गाः',
'category-media-header'          => '"$1" इत्येतस्मिन् वर्गे माध्यमाम्',
'category-empty'                 => "''अस्मिन् वर्गे अधुना न कोऽपि पृष्ठं, माध्यमं वा विद्यते।''",
'hidden-categories'              => '{{PLURAL:$1|निगूढः वर्गः|निगूढाः वर्गाः}}',
'hidden-category-category'       => 'निगूढाः वर्गाः',
'category-subcat-count'          => '{{PLURAL:$2|अस्मिन् वर्गे अधोलिखिताः उपवर्गाः विद्यन्ते|अस्मिन् वर्गे {{PLURAL:$1|अधोलिखितः उपवर्गः अस्ति|अधोलिखिताः $1 उपवर्गाः सन्ति}}, सर्वे उपवर्गाः $2 ।}}',
'category-subcat-count-limited'  => 'अस्मिन् वर्गे {{PLURAL:$1|अधोलिखितह् $1 वर्गः अस्ति|अधोलिखिताः $1 वर्गाः सन्ति}}।',
'category-article-count'         => '{{PLURAL:$2|अस्मिन् वर्गे केवलम् इदं पृष्ठं विद्यते ।|अस्मिन् वर्गे  {{PLURAL:$1|अधोलिखितं पृष्ठमस्ति|$1 अधोलिखितानि पृष्ठानि सन्ति}}, सर्वाणि पृष्ठानि $2 ।}}',
'category-article-count-limited' => 'अधोलिखितं {{PLURAL:$1|पृष्ठम् अस्मिन् श्रेण्याम् अस्ति|$1 पृष्ठाणि अस्मिन् श्रेण्यां सन्ति}}।',
'category-file-count'            => '{{PLURAL:$2|अस्मिन् वर्गे अधोलिखिता सञ्चिकामात्रं वर्तते।|अस्मिन् वर्गे {{PLURAL:$1|अधोलिखिताः सञ्चिका|अधोलिखिताः $1 सञ्चिकाः}} वर्तन्ते, सर्वाः सञ्चिकाः - $2 ।}}',
'category-file-count-limited'    => 'एतस्यां श्रेण्यां {{PLURAL:$1|संचिका|$1 संचिकाः}} अधस्तात् सूचिता{{PLURAL:$1||ः}} -
The following {{PLURAL:$1|file is|$1 files are}} in the current category.',
'listingcontinuesabbrev'         => 'आगामि.',
'index-category'                 => 'सूचकांकितानि पृष्ठानि',
'noindex-category'               => 'असूचकांकितानि पृष्ठानि',
'broken-file-category'           => 'भग्नसम्बन्धैः युक्तानि पृष्ठाणि',

'about'         => 'इत्यस्मिन् विषये:',
'article'       => 'लेखः',
'newwindow'     => '(नवे गवाक्षे इदम् उद्घाट्यते)',
'cancel'        => 'निरस्यताम्',
'moredotdotdot' => 'अपि च...',
'mypage'        => 'मम पृष्ठम्',
'mytalk'        => 'मम सम्भाषणम्',
'anontalk'      => 'अस्य आइ.पी. संकेतस्य कृते सम्भाषणम्',
'navigation'    => 'पर्यटनम्',
'and'           => '&#32;तथा च',

# Cologne Blue skin
'qbfind'         => 'अन्विष्यताम्',
'qbbrowse'       => 'ब्राउस् इत्येतत् करोतु।',
'qbedit'         => 'सम्पाद्यताम्',
'qbpageoptions'  => 'इदं पृष्ठम्',
'qbpageinfo'     => 'प्रसंगः',
'qbmyoptions'    => 'मम पृष्ठानि',
'qbspecialpages' => 'विशेषपृष्ठानि',
'faq'            => 'बहुधा पृच्छ्यमानाः प्रश्नाः',
'faqpage'        => 'Project:बहुधा पृछ्यमानाः प्रश्नाः',

# Vector skin
'vector-action-addsection'       => 'विषयः योज्यताम्',
'vector-action-delete'           => 'विलुप्यताम्',
'vector-action-move'             => 'चाल्यताम्',
'vector-action-protect'          => 'संरक्ष्यताम्',
'vector-action-undelete'         => 'अपाकरणस्य निरसनम्',
'vector-action-unprotect'        => 'सुरक्षितीकरणस्य निरसनम्',
'vector-simplesearch-preference' => 'संवर्धिताः अन्वेषणोपक्षेपाः समर्थीकरोतु। (केवलं वैक्टर-स्किन् इत्यस्यार्थे)',
'vector-view-create'             => 'सृज्यताम्',
'vector-view-edit'               => 'सम्पाद्यताम्',
'vector-view-history'            => 'इतिहासः दृश्यताम्',
'vector-view-view'               => 'पठ्यताम्',
'vector-view-viewsource'         => 'स्रोतः दृश्यताम्',
'actions'                        => 'क्रियाः',
'namespaces'                     => 'नामाकाशानि',
'variants'                       => 'भिन्नरूपाणि',

'errorpagetitle'    => 'दोषः',
'returnto'          => '$1 इत्येतद् प्रति निवर्तताम्।',
'tagline'           => '{{SITENAME}} इत्यस्मात्',
'help'              => 'साहाय्यम्',
'search'            => 'अन्विष्यताम्',
'searchbutton'      => 'अन्विष्यताम्',
'go'                => 'गम्यताम्',
'searcharticle'     => 'गम्यताम्',
'history'           => 'पृष्ठस्य इतिहासः',
'history_short'     => 'इतिहासः',
'updatedmarker'     => 'मम पौर्विक-आगमन-पश्चात् परिवर्तितानि',
'printableversion'  => 'मुद्रणयोग्या आवृत्तिः',
'permalink'         => 'स्थिरसम्पर्कतन्तुः',
'print'             => 'मुद्र्यताम्',
'view'              => 'दृश्यताम्',
'edit'              => 'सम्पाद्यताम्',
'create'            => 'सृज्यताम्',
'editthispage'      => 'इदं पृष्ठं सम्पाद्यताम्',
'create-this-page'  => 'इदं पृष्ठं सृज्यताम्',
'delete'            => 'विलुप्यताम्',
'deletethispage'    => 'इदं पृष्ठम् अपाक्रियताम्',
'undelete_short'    => '{{PLURAL:$1|एकं सम्पादनं|$1 सम्पादनानि}} अनपाकरोतु',
'viewdeleted_short' => 'दर्श्यताम् {{PLURAL:$1|एको विलुप्तं सम्पादनम्|$1 विलुप्तानि सम्पादनानि}}',
'protect'           => 'संरक्ष्यताम्',
'protect_change'    => 'परिवर्त्यताम्',
'protectthispage'   => 'इदं पृष्ठं संरक्ष्यताम्',
'unprotect'         => 'संरक्षणं परिवर्तयतु',
'unprotectthispage' => 'अस्य पुटस्य सुरक्षां परिवर्तयतु ।',
'newpage'           => 'नवीनपृष्ठम्',
'talkpage'          => 'अस्य पृष्ठस्य विषये चर्चा क्रियताम्',
'talkpagelinktext'  => 'सम्भाषणम्',
'specialpage'       => 'विशेषपृष्ठम्',
'personaltools'     => 'वैयक्तिकोपकरणानि',
'postcomment'       => 'नवीनः विभागः',
'articlepage'       => 'लेखः दृश्यताम्',
'talk'              => 'सम्भाषणम्',
'views'             => 'दृश्यानि',
'toolbox'           => 'उपकरणपेटिका',
'userpage'          => 'योजकपृष्ठं दृश्यताम्',
'projectpage'       => 'प्रकल्पपृष्ठं दृश्यताम्',
'imagepage'         => 'सञ्चिकापृष्ठं दृश्यताम्',
'mediawikipage'     => 'सन्देशपृष्ठं दृश्यताम्।',
'templatepage'      => 'फलकपृष्ठं दृश्यताम्',
'viewhelppage'      => 'सहायपृष्ठं दृश्यताम्',
'categorypage'      => 'वर्गाणां पृष्ठं दृश्यताम्',
'viewtalkpage'      => 'चर्चा दृश्यताम्',
'otherlanguages'    => 'अन्यासु भाषासु',
'redirectedfrom'    => '($1 इत्यस्मात् पुनर्निर्दिष्टम्)',
'redirectpagesub'   => 'अनुप्रेषण-पृष्ठम्',
'lastmodifiedat'    => 'एतस्य पृष्ठस्य अन्तिमपरिवर्तनं $1 दिनाङ्के $2 समये कृतम्',
'viewcount'         => 'एतत्पृष्ठं {{PLURAL:$1|एक वारं|$1 वारं}} दृष्टम् अस्ति',
'protectedpage'     => 'संरक्षितपृष्ठम्',
'jumpto'            => 'गम्यताम् अत्र :',
'jumptonavigation'  => 'पर्यटनम्',
'jumptosearch'      => 'अन्वेषणम्',
'view-pool-error'   => 'भोः, अधुना वितारकः अतिभाराक्रान्तः ।
बहवः योजकाः एतत् पृष्ठं द्रष्टुं प्रयतमानाः सन्ति ।
कृपया, कञ्चित्कालं प्रतीक्षतां करोतु । 
$1',
'pool-timeout'      => 'कालावधिः समाप्ता, यन्त्रणस्यार्थे प्रतीक्षते',
'pool-queuefull'    => 'कुण्डपंक्तिः (पूल् क्यू इत्येषा) पूर्णा अस्ति।',
'pool-errorunknown' => 'अज्ञाता त्रुटिः',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} इत्यस्य विषये',
'aboutpage'            => 'Project:एतद्विषयकम्',
'copyright'            => 'अस्य घटकानि $1 इत्यस्यान्तर्गतानि उपलब्धानि।',
'copyrightpage'        => '{{ns:project}}:प्रतिलिप्यधिकाराः',
'currentevents'        => 'सद्यःकालीनवार्ताः',
'currentevents-url'    => 'Project:सद्यःकालीनवार्ताः',
'disclaimers'          => 'प्रत्याख्यानम्',
'disclaimerpage'       => 'Project:साधारणं प्रत्याख्यानम्',
'edithelp'             => 'सम्पादनार्थं सहाय्यम्',
'edithelppage'         => 'Help:सम्पादनम्',
'helppage'             => 'Help:आन्तर्यम्',
'mainpage'             => 'मुख्यपृष्ठम्',
'mainpage-description' => 'मुख्यपृष्ठम्',
'policy-url'           => 'Project:नीतिः',
'portal'               => 'समुदायद्वारम्',
'portal-url'           => 'Project:समुदायद्वारम्',
'privacy'              => 'निभृततानीतिः',
'privacypage'          => 'Project:निभृततानीतिः',

'badaccess'        => 'अनुज्ञा-प्रमादः',
'badaccess-group0' => 'भवदर्थम्, अत्र प्रार्थितक्रियायाः प्रवर्तनं न अनुमतम्।',
'badaccess-groups' => 'भवता प्रार्थिता क्रिया केवले {{PLURAL:$2|अस्मिन् समूहे|एतेषु समूहेषु}} अनुमता अस्ति: $1।',

'versionrequired'     => 'मीडीयाविके: $1 संस्करणम् आवश्यकम् ।',
'versionrequiredtext' => 'एतत्पृष्ठं प्रयोक्तुं मीडियाविकि इत्येतस्य $1तमा आवृत्तिः आवश्यकी। पश्यतु [[Special:Version|आवृत्ति-सूचिका]]',

'ok'                      => 'अस्तु',
'pagetitle'               => '$1 - {{SITENAME}}',
'retrievedfrom'           => '"$1" इत्यस्माद् उद्धृतम्',
'youhavenewmessages'      => 'भवदर्थम् $1 सन्ति। ($2).',
'newmessageslink'         => 'नूतनाः सन्देशाः',
'newmessagesdifflink'     => 'अन्तिमं परिवर्तनम्',
'youhavenewmessagesmulti' => 'भवतः कृते $1 मध्ये नूतनः सन्देशः विद्यते',
'editsection'             => 'सम्पाद्यताम्',
'editold'                 => 'सम्पाद्यताम्',
'viewsourceold'           => 'स्रोतः दृश्यताम्',
'editlink'                => 'सम्पाद्यताम्',
'viewsourcelink'          => 'स्रोतः दृश्यताम्',
'editsectionhint'         => 'अयं विभागः सम्पाद्यताम्: $1',
'toc'                     => 'अन्तर्विषयाः',
'showtoc'                 => 'दर्श्यताम्',
'hidetoc'                 => 'गोप्यताम्',
'collapsible-collapse'    => 'संकोच्यताम्',
'collapsible-expand'      => 'विस्तीर्यताम्',
'thisisdeleted'           => '$1 दर्शयेत् वा प्रत्यानयेत् वा?',
'viewdeleted'             => '$1 दृश्यताम् ?',
'restorelink'             => '{{PLURAL:$1|एकम् अपाकृतं संपादनम्  |$1 अपाकृतानि संपादनानि}}',
'feedlinks'               => 'अनुबन्ध:',
'feed-invalid'            => 'अमान्यं सेवाग्रहण-पूरण (सब्स्क्रिप्शन-फीड् इति) प्रकारः।',
'feed-unavailable'        => 'समवायसम्पूरणं नोपलभते ।',
'site-rss-feed'           => '$1 आरएसएस पूरणम्',
'site-atom-feed'          => '$1 अणुपूरणम्',
'page-rss-feed'           => '"$1" आरएसएस-पूरणम्',
'page-atom-feed'          => '"$1" अणुपूरणम्',
'red-link-title'          => '$1 (पृष्ठम् इदानीं यावत् न रचितम्)',
'sort-descending'         => 'अवरोहिक्रमेण सज्जयतु',
'sort-ascending'          => 'आरोहिक्रमेण सज्जयतु',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'पृष्ठम्',
'nstab-user'      => 'योजकस्य पृष्ठम्',
'nstab-media'     => 'माध्यमपृष्ठम्',
'nstab-special'   => 'विशेषपृष्ठम्',
'nstab-project'   => 'प्रकल्पपृष्ठम्',
'nstab-image'     => 'सञ्चिका',
'nstab-mediawiki' => 'सन्देशः',
'nstab-template'  => 'फलकम्',
'nstab-help'      => 'सहायपृष्ठम्',
'nstab-category'  => 'वर्गः',

# Main script and global functions
'nosuchaction'      => 'तादृशं कार्यं न विद्यते',
'nosuchactiontext'  => 'अनेन समरूप-संसाधन-अवस्थापकेन (URL इति) वर्णिता क्रिया अमान्याऽस्ति।
भवता समरूप-संसाधन-अवस्थापकं अपटंकितं स्यात्, अथवा असुष्ठु संबंधनम् अनुसृतम् स्यात्।
इदम् {{SITENAME}} इत्यनेन प्रयुक्ते क्रमादेशे त्रुटिर्वा स्यात्।',
'nosuchspecialpage' => 'तादृशं विशेषपृष्टं न विद्यते',
'nospecialpagetext' => '<strong>भवता एकम् अमान्यं विशिष्टपृष्ठं याचितम्। </strong>
मान्यानां विशिष्टपृष्ठाणां सूचिका [[Special:SpecialPages|{{int:specialpages}}]] इत्यत्र प्राप्तुं शक्यते।',

# General errors
'error'                => 'दोषः',
'databaseerror'        => 'दत्ताधारे दोषः',
'dberrortext'          => 'समंकाधार पृच्छायां वाक्यरचनात्रुटिरेका अभवत्।
अनेन अस्माकं तन्त्रांशे त्रुटिरपि निर्दिष्टा स्यात्।
अन्तिमा चेष्टिता समंकाधार-पृच्छा आसीत्:
<blockquote><code>$1</code></blockquote>
 "<code>$2</code>" इत्यस्मात् फलनात्।
समंकाधारे त्रुटिरासीत्:  "<samp>$3: $4</samp>" इति।',
'dberrortextcl'        => 'समंकाधार पृच्छायां वाक्यरचना त्रुटिरेका अभवत्।
अन्तिमा चेष्टिता समंकाधार पृच्छा आसीत् : 
"$1"
"$2" इति फलनात्।
समंकाधारे "$3:$4" इति त्रुटिर्जाता।',
'laggedslavemode'      => 'प्राक्प्रबोधनम्:अस्मिन् पृष्ठे सद्योजातानि परिशोधनानि न स्युः ।',
'readonly'             => 'दत्तधारः कीलितः',
'enterlockreason'      => 'तन्त्रितीकरणस्य कारणं ददातु, अपि च आकलितं ददातु यत् तन्त्रणं कदा उद्घाट्यिष्यते।',
'readonlytext'         => 'समंकाधारं वर्तमानकाले तन्त्रितमस्ति नूतनान् प्रविष्टीन् विरुध्य तथा च अन्यानि परिवर्तनानि विरुध्य। इदं नियमिततया समंकाधार परिचर्याऽर्थं तथा स्यात्। तत्पश्चादिदं सामान्यतां संप्राप्स्यति।
तन्त्रितीकारकेन प्रबन्धकेन इदं कारणं प्रदत्तम्: $1',
'missing-article'      => 'त्ताधारेण(डाटाबेस् इत्यनेन) "$1" $2 इतिनामकं प्राप्तव्यं यत् पृष्ठं तत् नैव प्राप्तम्।
प्रायः कालातीतस्य अथवा अपाकृतस्य इतिहाससम्पर्कतन्तोः कारणेन एवं भवति।
यदि नैवं तर्हि भवता तन्त्रांशकीटकं प्राप्तं स्यात्।
कृपया कोऽपि [[Special:ListUsers/sysop|administrator]]अस्य पृष्ठस्य सङ्केतज्ञापनपूर्वकं सूच्यताम्।',
'missingarticle-rev'   => '(आवृत्तिः# :$1)',
'missingarticle-diff'  => '(व्यतिरेक: $1, $2)',
'readonly_lag'         => 'मुख्य-समंकाधार-परिवेशकं उपमुख्य-समंकाधार-परिवेशकस्य संप्रापणात् पूर्वे एव स्वतः तन्त्रितम् अस्ति।',
'internalerror'        => 'आन्तरिकः दोषः',
'internalerror_info'   => 'आन्तरिकः दोषः: $1',
'fileappenderrorread'  => 'संलग्नीकरणकाले $1 पठितुम् अशक्यम्।',
'fileappenderror'      => '$1 इत्यस्य पश्चात् $2 इति योजयितुं नाशक्नोत्।',
'filecopyerror'        => '$1 इत्येतस्याः संचिकायाः  $2 इति प्रतिलिपिं कर्तुं नाशक्नोत्।',
'filerenameerror'      => '$1 इति संचिकायाः $2 इति पुनर्नामकरणं कर्तुं नाशक्नोत्।',
'filedeleteerror'      => '$1 इति सञ्चिकाम् अपाकर्तुं नाशक्नोत्।',
'directorycreateerror' => '$1 इति निर्देशिकां स्रष्टुं न अपारयत्',
'filenotfound'         => '"$1" इति सञ्चिका न लब्धा।',
'fileexistserror'      => '$1 इति संचिकायां लेखनम् अशक्यम् । सञ्चिका वर्तते  एव।',
'unexpected'           => 'अप्रतीक्षितमूल्यम् : "$1"="$2"।',
'formerror'            => 'त्रुटिः : प्रारूपं समर्पयितुं न अपारयत्',
'badarticleerror'      => 'अस्मिन् पृष्ठे एषा क्रिया कर्तुं न शक्या।',
'cannotdelete'         => '$1 इति पृष्ठं संचिका वा अपाकर्तुं नाशक्नोत्।
इदं खलु केनचिदन्येन पूर्वे एव अपाकृतं स्यात्।',
'cannotdelete-title'   => ' "$1" पृष्ठं निर्मार्जयितुम् अशक्यम्',
'badtitle'             => 'दुष्टं शिरोनाम',
'badtitletext'         => 'प्रार्थितं पृष्ठशीर्षकम् अमान्यं रिक्तम् अशुद्धतया सम्बद्धम् आन्तर्भाषिकम्, आन्तर्विकीयं वा शीर्षकमस्ति । अस्मिन् एकं एकाधिकानि वा एतादृशानि अक्षराणि विद्यन्ते येषां प्रयोगं शीर्षकेषु कर्तुम् अशक्यम्।',
'perfcached'           => 'अनुपदोक्तं लेखः कैश् इत्येतस्माद् अस्ति, अतः अद्यतनं न स्यात्।  {{PLURAL:$1|one result is|$1 results are}}',
'perfcachedts'         => 'अधोनिदेशितलेखः सञ्चितः । पूर्वपदोन्नतिः $1 । $4 {{PLURAL:}} अधिकाधिकपरिणामः सञ्चये उपलब्धः ।',
'querypage-no-updates' => 'अस्य पृष्ठस्य परिशोधनं विफलीकृतमस्ति । 
सद्यः अत्रत्यः विषयः न नवीक्रियते ।',
'wrong_wfQuery_params' => 'wfQuery() इत्येतस्य अशुद्धः मानदण्डः दत्तः अस्ति<br />
कार्यम्: $1<br />
पृच्छा: $2',
'viewsource'           => 'स्रोतः दृश्यताम्',
'viewsource-title'     => '$1 इत्येतस्य स्रोतः दृश्यताम् ।',
'actionthrottled'      => 'कार्यम् अवरुद्धम् अस्ति',
'actionthrottledtext'  => "'स्प्याम्'इत्येतस्य अवरोधाय अल्पे काले अत्यधिकवारम् अस्य कार्यकरणम् अवरुद्धम् अस्ति । 
कृपया किञ्चित्कालानन्तरं पुनः प्रयत्नः क्रियताम् ।",
'protectedpagetext'    => 'सम्पादनस्य अवरोधाय इदं पृष्ठं सुरक्षितमस्ति ।',
'viewsourcetext'       => 'भवान् एतस्य पृष्ठस्य स्रोतः द्रष्टुं तस्य प्रतिलिपिं कर्तुम् अर्हति।',
'viewyourtext'         => "भवान् अस्य पृष्ठस्य स्रोतसि '''भवतः सम्पादनानि''' द्रष्टुं प्रतिलिपिं कर्तुं च अर्हति ।",
'protectedinterface'   => 'इदं पृष्ठं तंत्रांशाय अन्तराफलकं ददाति, तथा च दुरुपयोगात् वारणाय सुरक्षितमस्ति ।',
'editinginterface'     => "'''अवधीयताम्'''  तन्त्रांशस्य विन्यासं यत् पृष्ठं रचयति तद् भवान् अधुना सम्पादयति । अत्र कृतानि परिवर्तनानि अन्येषाम् उपयोक्तॄणां पृष्ठविन्यासमपि परिवर्तयन्ति ।  अनुवादार्थम्   [//translatewiki.net/wiki/Main_Page?setlang=sa translatewiki.net] स्थानीयतानयनपरियोजनायाः उपयोगः क्रियताम्  ।",
'sqlhidden'            => '(निगूढा एसक्यूएल्- पृच्छा)',
'cascadeprotected'     => 'इदं पृष्ठं संपादनात् सुरक्षितमस्ति, यतः इदं अधोलिखितानां {{PLURAL:$1| पृष्ठस्य|पृष्ठाणां}} सुरक्षा-सोपाने समाहितं वर्तते।
$2',
'namespaceprotected'   => 'भवान् "$1" इति नामाकाशे विद्यमानान् पृष्ठान् परिवर्तितुं अनुमतिं न धारयति।',
'customcssprotected'   => 'भवान् इदं पृष्ठं सम्पादयितुम् अनुमतः नास्ति, यतो हि अस्मिन् अन्यस्य प्रयोक्तुः वैयक्तिकचयनानि सन्ति।',
'customjsprotected'    => 'भवान् इदं जावालिपियुक्तं पृष्ठं सम्पादयितुम् अनुमतः नास्ति, यतो हि अस्मिन् अन्यस्य प्रयोक्तुः वैयक्तिकचयनानि सन्ति।',
'ns-specialprotected'  => 'विशिष्टानि पृष्ठानि परिवर्तयितुं न शक्यन्ते।',
'titleprotected'       => 'सदस्य [[User:$1|$1]] इत्यनेन एतत्-शीर्षकीयं पृष्ठं सृजनात् वारितमस्ति।
एतदर्थं प्रदत्तं कारणम् "$2"।',

# Virus scanner
'virus-badscanner'     => "असुष्ठु अभिविन्यासः : अज्ञातं विषाणु-निरीक्षित्रम्: ''$1''",
'virus-scanfailed'     => 'परीक्षणं विफलीभूतम् (कूटम् $1)',
'virus-unknownscanner' => 'अज्ञातं विषाणुप्रतिकारकम्:',

# Login and logout pages
'logouttext'                 => "'''भवान् अधुना बहिरागतः ।'''

भवान् {{SITENAME}} इत्येतत् अनामतया प्रयोक्तुं शक्नोति, अथवा भवान् तेनैव प्रयोक्तृनाम्ना, भिन्नप्रयोक्तृनाम्ना वा  [[Special:UserLogin|पुनः प्रवेष्टुं शक्नोति]]।
इदानीमपि कानिचन पृष्ठानि पूर्ववदेव दृश्येरन् । अस्य वारणाय विचरकस्य स्मृतिसञ्चयः रिक्तीक्रियताम् ।",
'welcomecreation'            => '==स्वागतम्‌, $1!==
भवता सदस्यता प्राप्ता अस्ति।
भवतः [[Special:Preferences|{{SITENAME}} इष्टतमानि]]  इत्यस्य परिवर्तनं न विस्मर्यताम्।',
'yourname'                   => 'योजकनामन्:',
'yourpassword'               => 'कूटशब्दः',
'yourpasswordagain'          => 'कूटशब्दः पुनः लिख्यताम् ।',
'remembermypassword'         => 'अस्मिन् सङ्गणके मम प्रवेशः स्मर्यताम् (अधिकतमम् $1 {{PLURAL:$1|दिनम्|दिनानि}})',
'securelogin-stick-https'    => 'प्रवेशोपरान्तं एचटीटीपीएस(HTTPS) इत्यनेन सह संबद्धः तिष्ठतु।',
'yourdomainname'             => 'भवतः प्रक्षेत्रम्:',
'externaldberror'            => 'तत्र प्रमाणीकरण समंकाधारे त्रुटिर्जाता, अथवा भवान् स्वकीयां बाह्य-लेखां अद्यतनीकर्तुं अनुमतिं न धारयति।',
'login'                      => 'प्रविश्यताम्',
'nav-login-createaccount'    => 'प्रविश्यताम्/ सदस्यता प्राप्यताम्',
'loginprompt'                => '{{SITENAME}} इत्यत्र प्रवेष्टुं कुकी इत्येते (cookies)  समर्थीकरणीयानि।',
'userlogin'                  => 'प्रविश्यताम्/ सदस्यता प्राप्यताम्',
'userloginnocreate'          => 'प्रविश्यताम्',
'logout'                     => 'निर्गमनम्',
'userlogout'                 => 'निर्गमनम्',
'notloggedin'                => 'नैव प्रविष्टः',
'nologin'                    => 'पूर्वमेव योजकः नास्ति किम् ? $1।',
'nologinlink'                => 'सदस्यता प्राप्यताम्',
'createaccount'              => 'सदस्यता प्राप्यताम्',
'gotaccount'                 => 'पूर्वमेव योजकः अस्ति किम् ? $1।',
'gotaccountlink'             => 'प्रविश्यताम्',
'userlogin-resetlink'        => 'प्रवेशविवरणानि विस्मृतानि किम् ?',
'createaccountmail'          => 'ईपत्रद्वारा',
'createaccountreason'        => 'कारणम्',
'badretype'                  => 'भवता प्रदत्ते कूटशब्दे न खलु समाने स्तः। कृपया पुनः लिखतु।',
'userexists'                 => 'भवतः प्रदत्तः प्रयोक्तृनाम अन्येन प्रयुज्यमानम् अस्ति। कृपया अन्यदेकं प्रयोक्तृनाम चिनोतु।',
'loginerror'                 => 'प्रवेशने प्रमादः',
'createaccounterror'         => '$1 इति लेखां स्रष्टुं न अपारयत्',
'nocookiesnew'               => 'भवतः लेखा सृष्टाऽस्ति, परन्तु भवान् प्रविष्टो नासि।
{{SITENAME}} इत्यनेन प्रवेशं कर्तुं कुकि इत्येते प्रयुज्यन्ते।
भवतः पक्षे कुकि इत्येते असमर्थीकृतानि सन्ति।
कृपया तानि समर्थानि करोतु, तथा स्वकीयेन सदस्यनाम्ना कूटशब्देन च प्रविशतु।',
'nocookieslogin'             => '{{SITENAME}} इत्यत्र प्रयोक्तृणां प्रवेशार्थं कुकि इत्येतेषां प्रयोगः क्रियते।
भवता कुकि इत्येते असमर्थीकृतानि सन्ति।
कृपया तानि समर्थीकरोतु पुनश्च प्रयततु।',
'nocookiesfornew'            => 'योजकसदस्यता न सिद्धा यतः स्रोतः प्रमाणीकृतं न जातम् । 
भवता कुक्कीस् इत्येतत् समर्थीकृतानि किम् इति परिशील्य इदं पृष्ठं पुनरारोप्य प्रयतताम् ।',
'noname'                     => 'भवता एकं मान्यं प्रयोक्तृ-नाम न प्रदत्तम्।',
'loginsuccesstitle'          => 'स्वागतम्‌। प्रवेशः सिद्धः।',
'loginsuccess'               => 'भवान् अधुना {{SITENAME}} इत्यत्र "$1" रूपेण प्रविष्टोऽस्ति।',
'nosuchuser'                 => 'तत्र $1 इति नाम्ना न कोऽपि प्रयोक्ता विद्यते।
प्रयोक्तृनाम्नि आंग्ललिपेः लघुभिः दीर्घैश्च अक्षरैः भिन्नता गण्यते।
स्वकीयां वर्तनीं पुनरीक्षतां, अथवा [[Special:UserLogin/signup|नूतनसदस्यता प्राप्यताम्]]।',
'nosuchusershort'            => '"$1" इति नाम्ना न कोऽपि प्रयोक्ता विद्यते।
स्वकीयां वर्तनीं पुनरीक्षताम्।',
'nouserspecified'            => 'भवता एकं प्रयोक्तृनाम अवश्यमेव दातव्यम्।',
'login-userblocked'          => 'एषः प्रयोक्ता प्रतिबन्धितः अस्ति। सत्रारम्भाय अनुमतिः नास्ति।',
'wrongpassword'              => 'भवता प्रदत्तः कूटशब्दः त्रुटिपूर्णः अस्ति। 
कृपया पुनः लिख्यताम्।',
'wrongpasswordempty'         => 'लिखितः कूटशब्दः रिक्तः विद्यते।
कृपया पुनः लिख्यताम्।',
'passwordtooshort'           => 'कूटशब्दः न्यूनातिन्यूनं {{PLURAL: $1| 1 अक्षरात्मकः|$1 अक्षरात्मकमः}} अवश्यमेव भवेत्।',
'password-name-match'        => 'भवतः कूटशब्दः भवतः प्रयोक्तृनामतः अवश्यम् भिन्नं  भवेत् ।',
'password-login-forbidden'   => 'अस्य प्रयोक्तृनाम्नः कूटशब्दस्य च प्रयोगः वर्जितोऽस्ति।',
'mailmypassword'             => 'नूतनः कूटशब्दः ईपत्रद्वारा प्रेष्यताम्',
'passwordremindertitle'      => '{{SITENAME}} इत्येतदर्थे नूतन् अस्थायि कूटशब्दम्।',
'passwordremindertext'       => 'कश्चित्  (भवान् अपि स्यात्,  $1 ऐ. पि. सङ्केतात् ) {{SITENAME}} ($4) इत्यस्य कृते नूतनं कूटशब्दं प्रार्थितवान् । तात्कालिकः कूटशब्दः "$2" इति उपयोक्तुः कृते निर्मितः "$3" कृते प्रेषितश्च । यदि अयं भवतः  आशयः, भवान् प्रविश्य नूतनं कूटशब्दम् इदानीं चिनोतु । भवतः तात्कालिकः कूटशब्दः  {{PLURAL:$5|दिनम्|$5 दिनानि}} यावत् सक्रियः भवति । 

अन्यः कश्चित् एतां प्रार्थनां कृतवान्, अथवा भवानेव पूर्वतनं कृटशब्दं स्मृतवान्, इदानीं तस्य परिवर्तनं न् इच्छति चेत् एतां सूचनाम् अनङ्गीकृत्य पूर्वतनस्य कूटशब्दस्य एव उपयोगं करोतु ।',
'noemail'                    => '"$1" इति प्रयोक्तुः कृते न कोऽपि विद्युत्सन्देशसंकेतः पञ्जीकृतोऽस्ति।',
'noemailcreate'              => 'भवता एकः मान्यः विद्युत्सन्देशसंकेतः दातव्यः।',
'passwordsent'               => '"$1" इत्येतस्य कृते पञ्जीकृते विद्युत्सन्देशसंकेते एकः नूतनः कूटशब्दः प्रेषितोऽस्ति।
कृपया तस्य सम्प्राप्तिपश्चात् पुनः प्रविशतु।',
'blocked-mailpassword'       => 'भवतः आइपी-संकेतः सम्पादनात् प्रतिबन्धितः अस्ति, अतश्च कूटशब्द-पुनरवाप्ति-सुविधायाः प्रयोगादपि वर्जितः, येन हि दुष्प्रयोगः न स्यात्।',
'eauthentsent'               => 'भवता प्रदत्ते विद्युत्पत्र-संकेते एकः परिपुष्टिरूपः विद्युत्सन्देशः प्रेषितोऽस्ति।
अन्यः कश्चिद् विद्युत्सन्देशः प्रेषितो भवेत् इत्येतदर्थं भवता तत्सन्देशे प्रदत्ताः निर्देशाः पालितव्याः, येन हि सा विद्युत्सन्देशलेखा भवदीया एव इति सत्यापितो भवेत्।',
'throttled-mailpassword'     => 'पूर्वतनायां {{PLURAL:$1|होरस्य|$1 होराणां}} अवधौ भवदर्थं एकः कूटशब्द-पुनःस्मारकम् प्रेषितोऽस्ति।
दुष्प्रयोगाद् वारयितुं प्रति {{PLURAL:$1|होरे|$1 होरेषु}} केवलम् एकं कूटशब्द-पुनःस्मारकं प्रेषयितुं शक्यते।',
'mailerror'                  => 'विद्युत्सन्देशप्रेषणे त्रुटिः: $1',
'acct_creation_throttle_hit' => 'गते दिवसे अस्यां विक्यां भवतः आइपीसंकेतम् आधृत्य आगन्तुकाः {{PLURAL:$1|1 लेखां|$1 लेखाः}} सृजितवन्तः सन्ति। तदेव एतत्समयावधौ अधिकतमम् अनुमतम्।
तस्मात्, एतद् आइपीसंकेते आगन्तुकाः एतत्समये नाधिकं लेखां स्रष्टुं शक्नुवन्ति।',
'emailauthenticated'         => 'भवतः विद्युत्सन्देशसंकेतः $2 दिनांके $3 वादने परिपुष्टीकृतः आसीत्।',
'emailnotauthenticated'      => 'भवतः विद्युत्संदेश-संकेतः अधुनायावत् परिपुष्टीकृतः नास्ति।
अधस्तात् उल्लिखितेषु कस्मिंश्चिदपि विषये भवान् विद्युत्सन्देशं प्रषयितुं न शक्नोसि।',
'noemailprefs'               => 'एताः सुविधाः कार्यशीलाः भवेयुः इत्येतदर्थं स्वकीये वरीयांसि इति प्रभागे विद्युत्सन्देशसंकेत एकः दातव्यः।',
'emailconfirmlink'           => 'स्वकीयं विद्युत्सन्देशसंकेतं प्रमाणीकरोतु।',
'invalidemailaddress'        => 'प्रतीयते यद् विद्युत्सन्देशसंकेतः अमान्ये प्रारूपे विद्यते। अतएव एतत् स्वीकरोतुं न शक्यते।
कृपया एकं प्रारूपसम्मतं संकेतं ददातु, अथवा तत् क्षेत्रं रिक्तमेव करोतु।',
'cannotchangeemail'          => 'अस्मिन् विकिमध्ये सदस्य-ईपत्र-सङ्केतः परिवर्तयितुं न शक्यते ।',
'accountcreated'             => 'सदस्यता प्राप्ता',
'accountcreatedtext'         => '$1 इत्येतस्य सदस्यता प्राप्ता अस्ति।',
'createaccount-title'        => '{{SITENAME}} इत्येतदर्थं लेखासृजनम्',
'createaccount-text'         => 'भवतः विद्युत्संदेशसंकेतार्थं केनचित् $2 इति जनेन {{SITENAME}} ($4) इत्यत्र  $3 इति कूटशब्दं दत्वा लेखा सृष्टाऽस्ति।
भवता अधुना प्रवेशं कृत्वा कूटशब्दः परिवर्तितव्यः।

चेत् सा लेखा त्रुटिवशात् सृष्टा, तर्हि भवान् एतत्सन्देशम् उपेक्षितुं शक्नोति।

Translation in English: 
Someone created an account for your e-mail address on {{SITENAME}} ($4) named "$2", with password "$3".
You should log in and change your password now.

You may ignore this message, if this account was created in error.',
'usernamehasherror'          => 'प्रयोक्तृनाम्नि हेश् इत्यक्षरं (#) न अन्तर्भवितुं शक्नोति।',
'login-throttled'            => 'भवता सद्य एव प्रभूततया प्रवेशप्रयासाः कृताः।
कृपया पुनः प्रयासार्थं किंचित् प्रतीक्षताम्।',
'login-abort-generic'        => 'भवतः प्रवेशप्रयासः विफलीभूतः - परित्यक्तः',
'loginlanguagelabel'         => 'भाषा : $1',
'suspicious-userlogout'      => 'भवतः सत्राद् बहिर्गमनस्य अनुरोधः अस्वीकृतोऽस्ति, यस्मादेतत् भग्नादेकस्मात् ब्राउज़र्तः अथवा स्वल्पसञ्चयि-प्रॉक्सितः प्रेषित आसीत्।',

# E-mail sending
'php-mail-error-unknown' => 'पीएच्पी इत्येतस्य mail() फलने अज्ञाता काऽपि त्रुटिर्जाता।',
'user-mail-no-addy'      => 'ईपत्रसङ्केतं विना ईपत्रप्रेषणस्य प्रयासः कृतः ।',

# Change password dialog
'resetpass'                 => 'कूटशब्दः परिवर्त्यताम्',
'resetpass_announce'        => 'भवान् तात्कालिक-ईपत्रद्वारा अत्र प्रविष्टः अस्ति ।
प्रवेशनस्य समापनाय भवता अत्र नूतनः कूटशब्दः दातव्यः:',
'resetpass_text'            => '<!-- पाठं अत्र लिखतु -->',
'resetpass_header'          => 'सदस्यतायाः कूटशब्दः परिवर्त्यताम्।',
'oldpassword'               => 'पुरातनः कूटशब्दः',
'newpassword'               => 'नूतनः कूटशब्दः',
'retypenew'                 => 'नूतनः कूटशब्दः पुनः लिख्यताम्:',
'resetpass_submit'          => 'कूटशब्दः योज्यतां प्रविश्यतां च',
'resetpass_success'         => 'भवतः कूटशब्दः सफलतया परिवर्तितः!
अधुना भवान् प्रवेश्यते ...',
'resetpass_forbidden'       => 'कूटशब्दाः परिवर्तयितुं न शक्यन्ते',
'resetpass-no-info'         => 'भवता एतत्पृष्ठं प्रत्यक्षतया सम्प्राप्तुं प्रवेशः अवश्यमेव कर्त्तव्यः।',
'resetpass-submit-loggedin' => 'कूटशब्दः परिवर्त्यताम्',
'resetpass-submit-cancel'   => 'निरस्यताम्',
'resetpass-wrong-oldpass'   => 'अल्पकालीनः अथवा सद्यःकालीनः कूटशब्दः अमान्यः अस्ति।
भवता पूर्वे एव सफलतया स्वकीयः कूटशब्दः परिवर्तितः स्यात्, अथवा एकः नूतनः अल्पकालीनः कूटशब्दः प्रार्थितः स्यात्।',
'resetpass-temp-password'   => 'अस्थिर रहस्यवाक् :',

# Special:PasswordReset
'passwordreset'                    => 'कूटशब्द पुनःस्थापनम्',
'passwordreset-text'               => 'भवतः सदस्यताविवरणानि ईपत्रद्वारा प्राप्तुम् इदं प्रपत्रं पूर्यताम् ।',
'passwordreset-legend'             => 'कूटशब्द पुनःस्थापनम्',
'passwordreset-disabled'           => 'अस्मिन् विक्यां कूटशब्द पुनःस्थापनं असमर्थीकृतमस्ति।',
'passwordreset-pretext'            => '{{PLURAL:$1| |समंकेषु एकम् अधस्यात् प्रविष्टीकरोतु।}}',
'passwordreset-username'           => 'योजकनामन्:',
'passwordreset-domain'             => 'क्षेत्रम्:',
'passwordreset-capture'            => 'फलितरूपम् ईपत्रं किं दृश्यते ?',
'passwordreset-capture-help'       => 'अस्यां मञ्जूषायां यदि भवता अङ्क्यते तर्हि ईपत्रम् (अस्थायिकूटशब्देन सह) दर्श्यते प्रेष्यते च ।',
'passwordreset-email'              => 'परमाणुपत्रसङ्गेत:',
'passwordreset-emailtitle'         => '{{SITENAME}} इत्यत्र लेखा-विवरणम्',
'passwordreset-emailtext-ip'       => 'कश्चित् (भवान् अपि स्यात्, $1 इति ऐ. पि. सङ्केतात्) {{SITENAME}} ($4) इत्यस्य प्रवेशसम्बद्धं विवरणं प्रार्थितवान् । अधः सूचितस्य उपयोक्तुः {{PLURAL:$3 | प्रवेशविवरणं | प्रवेशविवरणानि}} 
$2
इत्यनेन ईपत्रसङ्केतेन सम्बद्धम् अस्ति / सम्बद्धानि सन्ति ।
{{PLURAL:$3|अयं तात्कालिकः कूटशब्दः | इमे तात्कालिकाः कूटशब्दाः}}  {{PLURAL:$5| एकं दिनं | $5 दिनानि}} यावत् सक्रियः भवति / सक्रियाः भवन्ति ।',
'passwordreset-emailtext-user'     => 'कश्चित् (भवान् अपि स्यात्, $1 इति ऐ. पि. सङ्केतात्) {{SITENAME}} ($4) इत्यस्य प्रवेशसम्बद्धं विवरणं प्रार्थितवान् । अधः सूचितस्य उपयोक्तुः {{PLURAL:$3 | प्रवेशविवरणं | प्रवेशविवरणानि}} 
$2
इत्यनेन ईपत्रसङ्केतेन सम्बद्धम् अस्ति / सम्बद्धानि सन्ति ।
{{PLURAL:$3|अयं तात्कालिकः कूटशब्दः | इमे तात्कालिकाः कूटशब्दाः}}  {{PLURAL:$5| एकं दिनं | $5 दिनानि}} यावत् सक्रियः भवति / सक्रियाः भवन्ति ।',
'passwordreset-emailelement'       => 'प्रयोक्तृनाम: $1
अल्पकालिकः कूटशब्दः : $2',
'passwordreset-emailsent'          => 'एकः स्मारकः विद्युत्सन्देशः प्रेषितोऽस्ति।',
'passwordreset-emailsent-capture'  => 'अधो दर्शितस्य विद्युन्मानसङ्केतस्य अनुस्मारकं प्रेषितम् ।',
'passwordreset-emailerror-capture' => 'अधो निर्दिष्टानुस्मारकः विद्युन्मानसन्देशः रचितः । किन्तुः योजकसम्प्रेषणं विपन्नम् ।$1',

# Special:ChangeEmail
'changeemail'          => 'विद्युन्मानपत्रादेशं परिवर्तयतु',
'changeemail-header'   => 'उपयोजकसंज्ञायाः विद्युन्मानपत्रसङ्केतं परिवर्तयतु ।',
'changeemail-text'     => 'स्वस्य विद्युन्मानपत्रसङ्केतं परिवर्तयितुम् एतत् प्रपत्रं पूरयतु । दृढीकरणार्थं निकुञ्चः निवेशनीयः ।',
'changeemail-no-info'  => 'अस्य पुटस्य उपसञ्चारार्थं नामाभिलेखनम् अनिवार्यम् ।',
'changeemail-oldemail' => 'प्रचलितः विद्युन्मानपत्रसङ्केतः ।',
'changeemail-newemail' => 'नूतनः विद्युन्मानसङ्केतः ।',
'changeemail-none'     => 'असत्',
'changeemail-submit'   => 'विद्युन्मानपत्रसङ्केतं परिवर्तयतु ।',
'changeemail-cancel'   => 'निवर्तयते',

# Edit page toolbar
'bold_sample'     => 'स्थूलाक्षरैः युक्तः भागः',
'bold_tip'        => 'स्थूलाक्षरैः युक्तः भागः',
'italic_sample'   => 'तिर्यक् अक्षरम्',
'italic_tip'      => 'तिर्यक् अक्षरम्',
'link_sample'     => 'संबंधनस्य शीर्षकम्',
'link_tip'        => 'आन्तरिकसम्पर्कतन्तुः',
'extlink_sample'  => 'http://www.example.com संबंधनस्य शीर्षकम्',
'extlink_tip'     => 'बाह्य-संबंधनम् (अवश्यमेव  http:// इति पूर्वलग्नं योक्तव्यम्)',
'headline_sample' => 'शीर्षकम्',
'headline_tip'    => 'द्वितीयस्तरीयं शीर्षकम्',
'nowiki_sample'   => 'अप्रारूपीकृतं पाठं अत्र निवेशयतु',
'nowiki_tip'      => 'विकिप्रारूपणं अवगणना कुरु',
'image_sample'    => 'उदाहरणम्.jpg',
'image_tip'       => 'अन्तर्गता सञ्चिका',
'media_sample'    => 'उदाहरणम्.ogg',
'media_tip'       => 'संचिका-सम्पर्कतन्तुः',
'sig_tip'         => 'भवतः हस्ताङ्कनं समयोल्लेखश्च',
'hr_tip'          => 'क्षैतिज-रेखा (न्यूनतया प्रयोक्तव्या)',

# Edit pages
'summary'                          => 'सारांशः :',
'subject'                          => 'विषयः/शीर्षकम् :',
'minoredit'                        => 'इदं लघु परिवर्तनम्',
'watchthis'                        => 'इदं पृष्ठं निरीक्षताम्',
'savearticle'                      => 'पृष्ठं रक्ष्यताम्',
'preview'                          => 'प्राग्दृश्यम्',
'showpreview'                      => 'प्राग्दृश्यं दर्श्यताम्',
'showlivepreview'                  => 'प्रत्यक्षं प्राग्दृश्यम्',
'showdiff'                         => 'परिवर्तनानि दर्श्यन्ताम्',
'anoneditwarning'                  => "'''प्रबोधः'''भवान् न प्रविष्टोऽस्ति !
सम्पादनं कर्तुम् अत्र प्रवेशः आवश्यकः । अन्यथा अस्य पृष्ठस्य इतिहासे भवदीया IPसंख्या अङ्किता भवति ।",
'anonpreviewwarning'               => "''भवान् प्रवेशितः न अस्ति। रक्षणेन पृष्ठस्य सम्पादनेतिहासे भवतः आइपीसंकेतः अंकितः भविष्यति।''",
'missingsummary'                   => "'''अनुस्मारकम्:''' भवता सम्पादनस्य सारः न प्रदत्तः।
चेद्भवान् \"{{int:savearticle}}\" इत्येतद् पुनः क्लिक्करोति, भवतः सम्पादनानि साराद् ऋते रक्षितीभविष्यन्ति।",
'missingcommenttext'               => 'कृपया अधस्तात् एका टिप्पणी दातव्या।',
'missingcommentheader'             => "'''अनुस्मारकम्:''' भवता अस्याः टिप्पण्याः विषयः शीर्षकं वा न प्रदत्तः।
चेद्भवान् \"{{int:savearticle}}\" इत्येतद् पुनः क्लिक्करोति, भवतः सम्पादनानि विषयात् शीर्षकाद् वा ऋते रक्षितीभविष्यन्ति।",
'summary-preview'                  => 'सारांशस्य प्राग्दृश्यम् :',
'subject-preview'                  => 'विषयस्य/शीर्षकस्य प्राग्दृश्यम्:',
'blockedtitle'                     => 'प्रयोक्ता अवरुद्धः वर्तते',
'blockedtext'                      => 'भवतः आइपिसङ्केतः स्वचालितविधिना अवरुद्धोऽस्ति, यस्मादयं भिन्नेनैकेन सदस्येन प्रयुक्त आसीत्, यो हि $1 इत्यनेन अवरुद्धः आसीत्।
प्रदत्तं कारणमेतदस्ति:
:\'\'$2\'\'
* अवरोधनस्यारम्भः: $8
* अवरोधनस्य समाप्तिः: $6
* अभिप्रेतः अवरोध्यः: $7

भवान् अवरोधार्थं सम्भाषणं कर्तुं  $1 इत्येतं अथवा अन्यान् [[{{MediaWiki:Grouppage-sysop}}|प्रबन्धकान्]] सम्पर्कं कर्त्तुं शक्नोति।
मनसि धारयतु यद् भवान् "e-mail this user"(विद्युत्सन्देशः)  इति सुविधायाः प्रयोगः तावत् कर्त्तुं न शक्नोति यावत् भवानेकं  विधिमान्यं विद्युत्सन्देश-सङ्केतं [[Special:Preferences|user preferences]] इत्यत्र न पञ्जीकृतवानस्ति अपि च भवान् तस्य प्रयोगात् न निवारितोऽस्ति।

भवतः वर्तमानः आइपीसङ्केतः $3 इति अस्ति। अपि च अवरोधनस्य परिचयचिह्नम्  (आइडी) #$5 इत्यस्ति।
कृपया भवान् स्वकीयेषु सर्वेष्वपि प्रश्नेषु सर्वमेतत् वर्णनं ददातु।',
'autoblockedtext'                  => 'भवतः आइपिसङ्केतः स्वचालितविधिना अवरुद्धोऽस्ति, यस्मादयं भिन्नेनैकेन सदस्येन प्रयुक्त आसीत्, यो हि $1 इत्यनेन अवरुद्धः आसीत्।
प्रदत्तं कारणमेतदस्ति:
:\'\'$2\'\'
* अवरोधनस्यारम्भः: $8
* अवरोधनस्य समाप्तिः: $6
* अभिप्रेतः अवरोध्यः: $7

भवान् अवरोधार्थं सम्भाषणं कर्तुं  $1 इत्येतं अथवा अन्यान् [[{{MediaWiki:Grouppage-sysop}}|प्रबन्धकान्]] सम्पर्कं कर्त्तुं शक्नोति।
मनसि धारयतु यद् भवान् "e-mail this user"(विद्युत्सन्देशः)  इति सुविधायाः प्रयोगः तावत् कर्त्तुं न शक्नोति यावत् भवानेकं  विधिमान्यं विद्युत्सन्देश-सङ्केतं [[Special:Preferences|user preferences]] इत्यत्र न पञ्जीकृतवानस्ति अपि च भवान् तस्य प्रयोगात् न निवारितोऽस्ति।

भवतः वर्तमानः आइपीसङ्केतः $3 इति अस्ति। अपि च अवरोधनस्य परिचयचिह्नम्  (आइडी) #$5 इत्यस्ति।
कृपया भवान् स्वकीयेषु सर्वेष्वपि प्रश्नेषु सर्वमेतत् वर्णनं ददातु।',
'blockednoreason'                  => 'न किमपि कारणम् दत्तम्।',
'whitelistedittext'                => 'पृष्ठाणां सम्पादनार्थं $1 इति कार्यम् आवश्यकम्।',
'confirmedittext'                  => 'सम्पादनात् पूर्वं भवता स्वकीयं विद्युत्सन्देशसंकेतः परिपुष्टीकरणीयः।
कृपया स्वकीयः विद्युत्सन्देशसंकेतः [[Special:Preferences|प्रयोक्तृ-वरीयांसि]] इत्येतद्द्वारा प्रददातु तथा च प्रमाणीकरोतु।',
'nosuchsectiontitle'               => 'एतादृशः कोप्यनुभागः न लब्धः',
'nosuchsectiontext'                => 'भवता एतादृश एकोऽनुभागः सम्पादितुं चेष्टितं, यन्न हि विद्यते।
तत्तु पश्यति भवति एव प्रचालितम् अथवा अपाकृतं स्यात्।',
'loginreqtitle'                    => 'प्रवेशः अपेक्षितः',
'loginreqlink'                     => 'प्रविश्यताम्',
'loginreqpagetext'                 => 'अन्यानि पृष्ठानि द्रष्टुं भवता $1 इत्येतत् अवश्यमेव कर्त्तव्यम्।',
'accmailtitle'                     => 'कूटसङ्केतः प्रेषितः',
'accmailtext'                      => "[[User talk:$1|$1]] इत्येतदर्थं एकः यादृच्छिकतया उत्पादितः कूटशब्दः $2 इत्येतत् प्रति प्रेषितोऽस्ति।
सत्रारम्भपश्चात् नूतनायाः अस्याः लेखायाः कूटशब्दः  '''[[Special:ChangePassword|कूटशब्दं परिवर्तताम्]]'' इति पृष्ठे परिवर्तितुं शक्यते।",
'newarticle'                       => '(नूतनम्)',
'newarticletext'                   => "भवता एतादृशमेकं पृष्टं प्रति संबंधनम् अनुसृतम्, यत्पृष्ठं न इदानींयावत् विद्यते।

पृष्ठं स्रष्टुम् अधःप्रदत्तायां पेटिकायां टंकणं करोतु (सहाय्यार्थं [[{{MediaWiki:Helppage}}|अत्र]] क्लिक्करोतु।

चेद्भवान् अत्र भ्रान्तिना आनीतोऽस्ति तदा स्वकीये ब्राउसर् इत्यस्मिन् '''बैक्''' इत्यस्मिन् क्लिक्करोतु।)",
'anontalkpagetext'                 => 'तस्य अनामकयोजकस्य, अथवा अनुपयोजकस्य च परिचर्चापुटम् येन एतावति काले स्वस्थनं  न निर्मितम् । 
अतः तस्य अभिज्ञानार्थं ऐ.पि.सङ्गेतसङ्ख्या प्रयोजनीया । 
सा समाना सङ्ख्याः अन्ययोजकैः अपि विभक्ता । यदि भवान् अनामकयोजकः, भवता असम्बद्धटीकाः श्रुताः, कृपया स्वस्थनं निर्मीय नामाभिलेखं करोतु ।  [[Special:UserLogin/signup|create an account]], [[Special:UserLogin|log in]] अन्यानामकयोजकैः सह सम्भूयमनभ्रमैः विमुक्तः भवतु ।',
'noarticletext'                    => 'अस्मिन् पृष्ठे अधुना किमपि न विद्यते। भवान् विकिपीडियावर्तिषु अन्येषु पृष्ठेषु इदं [[Special:Search/{{PAGENAME}}|शीर्षकम् अन्वेष्टुम्]]अर्हति अथवा इदं पृष्ठं 
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}  सम्बद्धेषु पृष्ठेषु अन्वेष्टुम् अर्हति],
अथवा [{{fullurl:{{FULLPAGENAME}}|action=edit}} इदं पृष्ठं सम्पादयितुम् अर्हति]</span>.',
'noarticletext-nopermission'       => 'अस्मिन् पृष्ठे अधुना किमपि न विद्यते। भवान् विकिपीडियावर्तिषु अन्येषु पृष्ठेषु इदं [[Special:Search/{{PAGENAME}}|शीर्षकम् अन्वेष्टुम् अर्हति]] 
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}  related logs अन्वेष्टुम् अर्हति],
अथवा [{{fullurl:{{FULLPAGENAME}}|action=edit}} इदं पृष्ठं स्रष्टुम् अर्हति]</span>.',
'userpage-userdoesnotexist'        => '"$1" इति प्रयोक्तृलेखा पञ्जीकृता नास्ति।
चेद्भवान् एतत्पृष्ठं स्रष्टुमिच्छति सम्पादयितुमिच्छति वा तदा कृपया पुनरीक्षताम्।',
'userpage-userdoesnotexist-view'   => '"$1" इति प्रयोक्तृलेखा पञ्जीकृता नास्ति।',
'blocked-notice-logextract'        => 'अयं प्रयोक्ता सम्प्रति अवरुद्धः वर्तते।
नूतनतमा अवरोधाभिलेख-प्रविष्टिः सन्दर्भार्थम् अधस्तात् प्रदत्ताऽस्ति:',
'clearyourcache'                   => "'''सूचनाः:''' संरक्षणानन्तरं परिवर्तनानां दर्शनाय जालगवेशकस्य पुनर्चालनम् अवश्यं भवेत् ।
* '''Firefox / Safari:''' गृह्यताम् ''Shift'' नोदनावसरे ''Reload'', अथवा एतयोः अन्यतरं नुद्यताम् ''Ctrl-F5'' अथवा ''Ctrl-R'' (''⌘-R'' on a Mac)
* '''Google Chrome:''' नुद्यताम् ''Ctrl-Shift-R'' (''⌘-Shift-R'' on a Mac)
* '''Internet Explorer:''' गृह्यताम् ''Ctrl'' नोदनावसरे ''Refresh'', अथवा नुद्यताम् ''Ctrl-F5''
* '''Opera:''' पुनर्चाल्यताम् ''Tools → Preferences''",
'usercssyoucanpreview'             => "'''सूचना :''' रक्षणात्पूर्वं स्वकीयं जावास्क्रिप्ट् इति लिपिं परीक्षितुं \"{{int:showpreview}}\" इति गण्डं प्रयोजयतु।",
'userjsyoucanpreview'              => "'''सूचना :''' रक्षणात्पूर्वं स्वकीयं जावास्क्रिप्ट् इति लिपिं परीक्षितुं \"{{int:showpreview}}\" इति गण्डं प्रयोजयतु।",
'usercsspreview'                   => "'''मनसि धारयतु यद्भवान् केवलं प्राग्दृश्यं पश्यति स्वकीयस्य प्रयोक्तृ-सीएसएस् इत्येतस्य'''
'''इदं अधुनावधि यावत् रक्षितं नास्ति!'''",
'userjspreview'                    => "'''मनसि धारयतु यद्भवान् केवलं स्वकीयस्य जावास्क्रिप्ट्लिपेः परीक्षणं प्राग्दर्शनं वा करोति।'''
'''इदं अधुनावधि यावत् रक्षितं नास्ति!'''",
'sitecsspreview'                   => "'''मनसि धारयतु यद्भवान् स्वकीयस्य सीएस्एस्-इत्येतस्य केवलं प्राग्दृश्यं पश्यति।'''
'''इदं अधुनावधि यावत् रक्षितं नास्ति!'''",
'sitejspreview'                    => "'''मनसि धारयतु यद्भवान् स्वकीयस्य जावास्क्रिप्ट्कूटस्य केवलं प्राग्दृश्यं पश्यति।'''
'''इदं अधुनावधि यावत् रक्षितं नास्ति!'''",
'userinvalidcssjstitle'            => "'''पूर्वसूचना:'''  \"\$1\" इति त्वक् न विद्यते।
मनसि धारयतु यत् स्वेच्छया परिवर्तिताः .css, .js चेति पृष्ठाः लघूनक्षरान् प्रयोजयन्ति, यथा  {{ns:user}}:Foo/Vector.css इत्येतस्य स्थाने  {{ns:user}}:Foo/vector.css इत्येतत्।",
'updated'                          => '(अद्यतनीकृतः)',
'note'                             => "'''सूचना:'''",
'previewnote'                      => "'''स्मरणीयं यदेतत् केवलं प्राग्दृश्यमस्ति।'''
 ते परिवर्तनानि इदानीं यावत् न रक्षितानि ।",
'previewconflict'                  => 'अस्मिन् प्राग्दृश्ये दर्शितमस्ति यत् उपरिवर्ति पाठ क्षेत्रस्य पाठः रक्षणपश्चात् कीदृशः दृष्टिगोचरः भविष्यति।',
'session_fail_preview'             => "'''क्षम्यताम्! अस्माभिः भवतः सम्पादनस्य संसाधनं न कर्तुं शक्तम् यस्माद्धि सत्रस्य सूचनाः लुप्ताः।'''
कृपया पुनः चेष्टताम्।
चेदेतत् अधुनाऽपि न कार्यशीलं स्यात्, [[Special:UserLogout|सत्राद्बहिः गत्वा]] पुनः प्रवेशं करोतु।",
'session_fail_preview_html'        => 'लेखभागाभावात् ते परिचर्यां समापयितुं न शक्यते ।[[Special:UserLogout|logging out]]',
'token_suffix_mismatch'            => "'''ते सम्पादनं तिर्स्कृतम् । यतः ते ग्राहकः सम्पादनप्रतीके लेखानचिह्नानि क्षतविक्षतानि अकरोत्। '''
पाठ्यपुटस्य संरक्षणार्थं सम्पादनावकाशः पिहितः । अनामिकानाम् उपयोगकाले कदाचित् एवं सम्भवति ।",
'edit_form_incomplete'             => "'''सम्पादनस्य कतिचनांशाः वितारकं न प्राप्ताः ; सम्पादनं  द्विवरं परिशीलयतु । ते सम्पादनानि अनाहतानि, पुनः यतताम्  '''",
'editing'                          => '$1 सम्पाद्यते',
'editingsection'                   => '$1 सम्पादनम् (विभागः)',
'editingcomment'                   => '$1 संपादनम् (विभागः)',
'editconflict'                     => 'सम्पादनयोः/सम्पादनानाम् अन्तर्विरोधः : $1',
'explainconflict'                  => 'ते सम्पादनावसरे कोपि अन्यः परिवर्तितवान् । उपरितनलेखस्य क्षेत्रं सद्यः विद्यमानपुटयुक्तमस्ति । ते परिवर्तनम् अधः लेखक्षेत्रे दृश्यते । विद्यमानलेखैः सह ते परिवर्ताननि विलीनयतु । यदा संरक्षणप्रयत्नः क्रियते तदा केवलम् उपरिपठ्यभागः एव सुरक्षितं भवति ।',
'yourtext'                         => 'भवतः पाठः',
'storedversion'                    => 'रक्षिता आवृत्तिः',
'nonunicodebrowser'                => "'''पूर्वसूचना: भवतः विचरकं यूनीकोड्-अनुकूलम् नास्ति।'''
भवान् सुरक्षिततया सम्पादनं करोतु इत्येतदर्थं एका युक्तिः कृताऽस्ति: आस्की-इतराणि अक्षराणि सम्पादनपिटके षौडशिक(hexadecimal) कूटेषु द्रक्ष्यन्ते।",
'editingold'                       => "''' पूर्वसूचना : कालातीतपुटस्य सम्पादनं करोति  ''' यदि एतत् रक्षितुं यतते परिवर्तनं नैव रक्ष्यते ।",
'yourdiff'                         => 'भेदाः',
'copyrightwarning'                 => "कृपया संस्मर्तव्यं यत् {{SITENAME}} इत्येतद् प्रति कृतानि सर्वाणि योगदानानि $2 इत्यस्य प्रतिबंधांतर्गतानि सन्ति (अधिकाय ज्ञानाय $1 इत्येतद् पश्यतु)।

यदि भवान् स्वकीयानि लिखितानि परिवर्तमन्तश्च, पुनः वितर्यमन्तश्च न द्रष्टुमिच्छति तदा मा कृपया माऽत्र योगदानं करोतु। <br />

भवान् एतदपि प्रमाणीकरोति यत् एतद् भवता स्वतः लिखितमस्ति अथवा कस्माच्चत् जनार्पितात् वा मुक्तात् वा स्रोतसः प्रतिलिपीकृतमस्ति।

'''प्रतिलिप्यधिकारयुतान् लेखान्, अनुज्ञां विना, माऽत्र प्रददातु!'''",
'copyrightwarning2'                => "कृपया संस्मर्तव्यं यत् {{SITENAME}} इत्येतद् प्रति कृतानि सर्वाणि योगदानानि  इत्यस्य प्रतिबंधांतर्गतानि सन्ति (अधिकाय ज्ञानाय $1 इत्येतद् पश्यतु)।

यदि भवान् स्वकीयानि लिखितानि परिवर्तमन्तश्च, पुनः वितर्यमन्तश्च न द्रष्टुमिच्छति तदा मा कृपया माऽत्र योगदानं करोतु। <br />

भवान् एतदपि प्रमाणीकरोति यत् एतद् भवता स्वतः लिखितमस्ति अथवा कस्माच्चत् जनार्पितात् वा मुक्तात् वा स्रोतसः प्रतिलिपीकृतमस्ति।

'''प्रतिलिप्यधिकारयुतान् लेखान्, अनुज्ञां विना, माऽत्र प्रददातु!'''",
'longpageerror'                    => "रुटिः: भवता प्रदत्तः पाठः {{PLURAL:}} $1 किलोबैटमितः दीर्घः, अतः एषः अधिकतमानुज्ञातात् $2 मितात् दीर्घतरः अस्ति। एषः रक्षितुं न शक्यते।'''",
'readonlywarning'                  => "पूर्वसूचना ''' निर्वहणार्थं पाठः पिहितः । अधुना भवान् सम्पादनं रक्षितुं नैव शक्नोति । पाठसञ्चिकायां संश्लेष्य कार्यफलं रक्षतु । एतद्विवरणं प्रतिबन्धकः प्रशासकः विरतरि ।$1",
'protectedpagewarning'             => "'''पूर्वसूचना ''' प्रशासकपदयुक्ताः योजकाः एव सम्पादनं कर्तुमर्हन्ति । अतः एतत्पुटं सुरक्षितम् । निदेशार्थम् अधः जघन्यप्रवेशः सूचितः ।",
'semiprotectedpagewarning'         => "'''सूचना ''' पञ्जीकृतयोजकानां  उपयोगार्थ केवलम् एतत्पुटम् अभिरक्षितम् । जघन्यप्रवेशस्य सूचना आनुकूल्यार्थम् अधोनिदेशिता ।",
'cascadeprotectedwarning'          => "'''पूर्वसूचना ''' प्रशासकसौकर्ययुक्तानां योजकानाम् सम्पादनार्थम् एतत् पुटम् अभिरक्षितमस्ति । यतः अधोनिदेशितनिर्झरे एतदन्तर्गतम् । {{PLURAL:$1|page|pages}}:",
'titleprotectedwarning'            => "'''पूर्वसूचना  [[Special:ListGroupRights|specific rights]] जनानां सर्जनार्थम् एतत्पुटम् अभिरक्षितम् । '''",
'templatesused'                    => 'अस्मिन् पृष्ठे प्रयुक्तानि {{PLURAL:$1|फलकम्|फलकानि}}:',
'templatesusedpreview'             => 'अस्मिन् प्राग्दृश्ये प्रयुक्ताः {{PLURAL:$1|बिंबधराः |बिंबधराः}}:',
'templatesusedsection'             => '{{PLURAL:$1|Template|Templates}} अस्मिन् विभागे उपयुक्तम् ।',
'template-protected'               => '(संरक्षितम्)',
'template-semiprotected'           => '(अर्धसंरक्षितम्)',
'hiddencategories'                 => 'इदं पृष्ठं {{PLURAL:$1|1 निगूढे वर्गे |$1 निगूढेषु वर्गेषु}} अन्यतमं विद्यते :',
'nocreatetitle'                    => 'पुटनिर्माणं नियतम् ।',
'nocreatetext'                     => '{{SITENAME}} नूतनपुटनिर्माणस्य क्षमता नियता । वर्तमानापुटानां सम्पादनार्थं निर्गच्छतु । अथवा [[Special:UserLogin|log in or create an account]].',
'nocreate-loggedin'                => 'नूतनपुटनिर्मार्थम् अनुमतिः नास्ति ।',
'sectioneditnotsupported-title'    => 'विभागसम्पादनं न पोषितम् ।',
'sectioneditnotsupported-text'     => 'अस्मिन् पुटे विभागसम्पादनण् न पोषितम् ।',
'permissionserrors'                => 'अनुज्ञा-विभ्रमाः',
'permissionserrorstext'            => 'भवान् तत् कर्तुं अनुज्ञां न धारयति {{PLURAL:$1|अधोऽङ्कितात् कारणात् |अधोऽङ्कितेभ्यः कारणेभ्यः:}}',
'permissionserrorstext-withaction' => 'भवान् $2 इत्येतदर्थम् अनुमतः नास्ति, यतः {{PLURAL:$1|कारणम्|कारणानि}}:',
'recreate-moveddeleted-warn'       => "'''प्रबोधः: पूर्वम् अपाकृतं पृष्टं भवता रच्यमानम् अस्ति ।'''
अस्य पृष्ठस्य सम्पादनं किं युक्तम् इति पुनः विचार्यताम् ।
एतस्य पृष्ठस्य अपाकरणस्य चालनस्य च विवरणं भवतः उपयोगाय अत्र दीयन्ते :",
'moveddeleted-notice'              => 'इदं पृष्ठम् अपाकृतम् अस्ति।
अस्य अपाकरणस्य स्थानान्तरणस्य च विवरणम् अधः प्रदत्तम् अस्ति।',
'log-fulllog'                      => 'पूर्ण प्रवर्तनरेख पश्यतु',
'edit-hook-aborted'                => 'पाशेन (हुक् इत्यनेन) सम्पादनं परित्यक्तम्।
अनेन न किमपि कारणं प्रदत्तम्।',
'edit-gone-missing'                => 'पृष्ठं परिवर्तितुं नापारयत्।
प्रतीयते यदिदं अपाकृतमस्ति।',
'edit-conflict'                    => 'सम्पादनयोः/सम्पादनानां अन्तर्विरोधः।',
'edit-no-change'                   => 'भवतः सम्पादनम् उपेक्षितम्, यतो हि भवता पाठे न किमपि परिवर्तनं कृतम्।',
'edit-already-exists'              => 'नूतनं पृष्ठं स्रष्टुं नापारयत्।
इदं पूर्वे एव विद्यते।',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''प्रबोधः :''' अस्मिन् पृष्ठे प्रभूतानि जटिलानि पार्सर्-फ़ंक्शन्-आह्वानानि सन्ति।
अत्र $2 संख्यातः  {{PLURAL:$2|न्यूनं आह्वानं|न्यूनानि आह्वानानि}} भवितव्यानि, सद्यः तत्र {{PLURAL:$1 $1 आह्वानं विद्यते|$1 आह्वानानि विद्यन्ते}}।",
'expensive-parserfunction-category'       => 'प्रभूतेभ्यः जटिलेभ्यः पार्सर्-फंक्शन्-आह्वानेभ्यः युक्तानि पृष्ठाणि।',
'post-expand-template-inclusion-warning'  => "'''प्रबोधः:''' फलकानां योजनस्य आकारः अतिविशालः वर्तते ।
कानिचन फलकानि न योजयिष्यते ।",
'post-expand-template-inclusion-category' => 'पृष्ठाणि यत्र अतोऽधिकाः बिम्बधराः समाहितीकर्तुं न शक्यन्ते।',
'post-expand-template-argument-warning'   => "'''जागरणम्''' अस्मिन् पृष्ठे कश्चन एतादृशं फलकं विद्यते यच्च संवर्धनेन बृहदाकारतां प्राप्नोति ।
एतादृशानि फलकानि परित्यक्तानि सन्ति ।",
'post-expand-template-argument-category'  => 'परित्यक्तैः फलकैः युक्तानि पृष्ठानि एतानि',
'parser-template-loop-warning'            => 'बिम्बधर-पाशः प्राप्तः: [[$1]]',
'parser-template-recursion-depth-warning' => 'बिम्बधर-पुनरावर्तनार्थं गहनतायाः सीमा अतिक्रान्ताऽस्ति ($1)',
'language-converter-depth-warning'        => 'भाषा-परिवर्तकस्य गहनतायाः सीमा अतिक्रान्ताऽस्ति (*$1)',

# "Undo" feature
'undo-success' => 'सम्पादनमिदं विपरीतीकर्तुं शक्यते।
कृपया अधस्तात् तुलनां दृष्ट्वा निश्चितीकरोतु यत् भवान् एवमेव कर्तुमिच्छति। तदा भवान् विपरीतीकर्तुं निम्नांकितानि परिवर्तनानि रक्षतु।',
'undo-failure' => 'सम्पादनम् अकर्तुं न पारयत् यस्मात् मध्ये परस्परविरोधीनि सम्पादनानि अभवन्।',
'undo-norev'   => 'इदं सम्पादनं अकर्तुं न पारयत् यस्मात् एतत् न विद्यते अथवा अपाकृतस्ति।',
'undo-summary' => ' [[Special:Contributions/$2|$2]] ([[User talk:$2|talk]]) इत्यनेन कृताम् $1 इति आवृत्तिम् अकरोतु',

# Account creation failure
'cantcreateaccounttitle' => 'लेखा स्रष्टुं न शक्यते',
'cantcreateaccount-text' => "('''$1''') इति आइपिसंकेतात् लेखासृजनम् [[User:$3|$3]] इत्यनेन अवरुद्धीकृतः अस्ति।
एतदर्थं $3 इत्यनेन प्रदत्तं कारणम्''$2'' इत्यस्ति।",

# History pages
'viewpagelogs'           => 'अस्य पृष्ठस्य लॉंग् इत्येतद् दर्शयतु',
'nohistory'              => 'अस्य पृष्ठस्य कृते पृष्ठेतिहासः न वर्तते।',
'currentrev'             => 'सद्यःकालीना आवृत्तिः',
'currentrev-asof'        => 'वर्तमाना आवृत्तिः $1 इति समये',
'revisionasof'           => '$1 इत्यस्य आवृत्तिः',
'revision-info'          => '$1इति समयस्य आवृत्तिः $2 इत्यनेन',
'previousrevision'       => '← पुरातनानि संस्करणानि',
'nextrevision'           => 'नूतनतरा आवृत्तिः →',
'currentrevisionlink'    => 'सद्यःकालीना आवृत्तिः',
'cur'                    => 'सद्योजातम्',
'next'                   => 'आगामि',
'last'                   => 'पूर्वतनम्',
'page_first'             => 'प्रथमम्',
'page_last'              => 'अन्तिमम्',
'histlegend'             => 'भेदस्य चयनम्: आवृत्तिभेदस्य दर्शनाय अग्रे प्रदत्ता रेडियोमञ्जूषा नुद्यताम्, एण्टर्-कुड्मलं नुद्यताम्, अधः दत्तं कुड्मलं वा नुद्यताम् । <br />
इतिहासः: (सद्योजातम्) = नूतनासु आवृत्तिषु भेदः, 
(पूर्वतनम्) = पूर्वतनासु आवृत्तिषु भेदः, (लघु) = लघु परिवर्तनम्',
'history-fieldset-title' => 'सुगमनस्य(ब्राउस् इत्यस्य) इतिहासः',
'history-show-deleted'   => 'केवलम् विलोपित',
'histfirst'              => 'पुरातनतमम्',
'histlast'               => 'नूतनतमम्',
'historysize'            => '({{PLURAL:$1|1 बैटम्|$1 बैटानि}})',
'historyempty'           => '(रिक्तम्)',

# Revision feed
'history-feed-title'          => 'आवर्तनेतिहासः',
'history-feed-description'    => 'विक्याम् अस्य पृष्ठस्य आवर्तनेतिहासः',
'history-feed-item-nocomment' => '$2 मध्ये $1',
'history-feed-empty'          => 'याचितं पृष्ठं न विद्यते।
इदं विकितः अपाकृतम् अथवा पुनर्नामितं स्यात्।
सम्बन्धितानि नूतनानि पृष्ठाणि सम्प्राप्तुं [[Special:Search|विक्याम् अन्वेषणं]] करोतु।',

# Revision deletion
'rev-deleted-comment'         => '(सम्पादनस्य सारः अपाकृतमस्ति)',
'rev-deleted-user'            => '(प्रयोक्तृनाम अपाकृतमस्ति)',
'rev-deleted-event'           => '(अभिलेखन-क्रिया अपाकृताऽस्ति)',
'rev-deleted-user-contribs'   => '[प्रयोक्तृनाम अथवा आइपीसंकेतः अपाकृतः - सम्पादनं योगदानेभ्यः निगूढमस्ति]',
'rev-deleted-text-permission' => 'अस्य पुटस्य पुनरवतरणम् अपमार्जितम् । विवरणम् अत्र प्राप्यते  । [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log].',
'rev-deleted-text-unhide'     => 'अस्य पुटस्य पुनरवतरणम् अपमार्जितम् । विवरणम् अत्र प्राप्यते । [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log].
You can still [$1 view this revision]',
'rev-suppressed-text-unhide'  => "अस्य पुटस्य पुनरवतरणं ''' निषिद्धम् ''' यदि अनुवर्तनमिच्छति तर्हि विवरणम् अत्र प्राप्यते । [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} suppression log].
You can still [$1 view this revision]",
'rev-deleted-text-view'       => 'एतस्मात् अन्तरतः किञ्चिदवतरणं परिमार्जितम् । एतदन्तरं दृष्टुं शक्नुवन्ति । विवरणम् [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} हटाने की लॉग]',
'rev-suppressed-text-view'    => 'अस्मिन्नन्तरे किञ्चिदवतरणं सङ्गुपतम् । तदन्तरम् अत्र दृष्टुं शक्नुवन्ति । [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} suppression log].',
'rev-deleted-no-diff'         => 'एतत् दृष्टुं नैव शक्यते यतः पुनरावर्तनं परिमार्जितम् । विवरणम् अत्र प्राप्यते । [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log].',
'rev-suppressed-no-diff'      => 'एतदन्तरं दृष्टुं नैव शक्यते यतः अत्र किञ्चिदवतरणं परिमार्जितम् ।',
'rev-deleted-unhide-diff'     => 'अस्य पुटस्य पुनरवतरणम् अपमार्जितम् । विवरणम् अत्र प्राप्यते । [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} deletion log].
You can still [$1 view this revision]',
'rev-suppressed-unhide-diff'  => "अस्य पुटस्य पुनरवतरणं ''' निषिद्धम् ''' यदि अनुवर्तनमिच्छति तर्हि विवरणम् अत्र प्राप्यते । [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} suppression log].
You can still [$1 view this revision]",
'rev-deleted-diff-view'       => 'एतस्मात् अन्तरतः किञ्चिदवतरणं परिमार्जितम् । एतदन्तरं दृष्टुं शक्नुवन्ति । विवरणम् [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} हटाने की लॉग]',
'rev-suppressed-diff-view'    => 'अस्मिन्नन्तरे किञ्चिदवतरणं सङ्गुपतम् । तदन्तरम् अत्र दृष्टुं शक्नुवन्ति । [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} suppression log].',
'rev-delundel'                => 'दर्श्यन्ताम्/गोप्यन्ताम्',
'rev-showdeleted'             => 'दर्श्यताम्',
'revisiondelete'              => 'अवतरणं परिमार्जयतु/पुनस्थापयतु',
'revdelete-nooldid-title'     => 'लक्ष्यरूपा आवृत्तिः अमान्याऽस्ति।',
'revdelete-nooldid-text'      => 'एतत्कार्यं कर्तुं भवतः अवतरणं न दत्तम् । अथवा भवता दत्तावतरणस्य अस्तित्वं नास्ति । अथवा सद्यः अवतरणस्य सङ्गोपनं कुर्वन् अस्ति ।',
'revdelete-nologtype-title'   => 'अभिलेखस्य प्रकारः न प्रदत्तः',
'revdelete-nologtype-text'    => 'अस्यै क्रियायै भवता न कोऽपि अभिलेखप्रकारः निर्दिष्टः।',
'revdelete-nologid-title'     => 'अमान्या अभिलेख-प्रविष्टिः',
'revdelete-nologid-text'      => 'एतत् कार्यं साधयितुं भवान् प्रवेशलक्ष्यं न स्पष्टीकृतवान् अथवा प्रवेशः अस्तित्वे नास्ति ।',
'revdelete-no-file'           => 'निर्दिष्टा सञ्चिका न विद्यते ।',
'revdelete-show-file-confirm' => '$2 तः $3 मध्ये "<nowiki>$1</nowiki>" इति सञ्चिकायाः निरस्तं परिष्करणं भवान् नूनं द्रष्टुम् इच्छति ?',
'revdelete-show-file-submit'  => 'आम्',
'revdelete-selected'          => "'''{{PLURAL:$2|Selected revision|Selected revisions}} of [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Selected log event|Selected log events}}:'''",
'revdelete-text'              => "'''परिमार्जितानि अवतरणानि पुटेतिहासे अद्यापि दृश्यन्ते । तस्य कश्चन भागः सार्वजनिकः न भवति । '''
{{SITENAME}} इत्यस्य अन्यप्रशसासकः गुप्तसामग्रीः प्राप्नुवन्ति । अपि च अन्तरापुटेन अस्य अपरिमार्जनं कर्तुं शक्नुवन्ति । यावत् अतिरिक्तप्रतिबन्धकाः न स्थापिताः ।",
'revdelete-confirm'           => 'भवान् एतत् कार्यं करोति इति दढयतु । भवान् अस्य परिणामं जानाति । [[{{MediaWiki:Policy-url}}|the policy]] भवान् एतदनुसारं करोति ।',
'revdelete-suppress-text'     => 'अधोनिदेशितपरिस्थितिषु केवलं निग्रहः कार्यः । 
* अवमाननीयाः विषयाः ।
* अप्रधानाः वैयक्तिकविषयाः
* गृहसङ्केतः, दूरवाणीसङ्ख्या, सामाजिकसुरक्षासङ्ख्या, इत्यादयः ।',
'revdelete-legend'            => 'दृश्यप्रतिबन्धं निश्चिनोतु ।',
'revdelete-hide-text'         => 'अवतरणस्य पाठं गोपयतु ।',
'revdelete-hide-image'        => 'सञ्चिकाधेयं गोपयतु ।',
'revdelete-hide-name'         => 'प्रक्रियां लक्ष्यं च गोपयतु ।',
'revdelete-hide-comment'      => 'सम्पादनसारं गोपयतु ।',
'revdelete-hide-user'         => 'सम्पादकस्य योजकनाम/आइपिसंकेतः गोप्यताम्।',
'revdelete-hide-restricted'   => 'प्रबन्धकेभ्यः अन्येभ्यश्च समंकं गोपयतु।',
'revdelete-radio-same'        => 'मा परिवर्तयतु।',
'revdelete-radio-set'         => 'आम्',
'revdelete-radio-unset'       => 'न हि',
'revdelete-suppress'          => 'प्रबन्धकेभ्यः अन्येभ्यश्च समंकं गोपयतु।',
'revdelete-unsuppress'        => 'प्रत्यानीताऽऽवृत्तिभ्यः  वर्जनाः अपाकरोतु।',
'revdelete-log'               => 'कारणम् :',
'revdelete-submit'            => '{{PLURAL:$1|चितायां आवृत्त्यां|चितासु आवृत्तिषु}} अनुप्रयोजयतु।',
'revdelete-success'           => 'अवतरणदृश्यता साफल्येन उन्नतीकृता ।',
'revdelete-failure'           => 'अवतरणदृश्यता उन्नतीकरणं न शक्यते ।$1',
'logdelete-success'           => 'नामाङ्कनदृश्यता साफल्येन योजिता ।',
'logdelete-failure'           => 'नामाभिलेखदृश्यता सपला नाभवत् । $1',
'revdel-restore'              => 'दृष्टिविषयः परिवर्त्यताम्',
'revdel-restore-deleted'      => 'अपास्तानि संस्करणानि',
'revdel-restore-visible'      => 'दृष्टिगोचराणि संस्करणानि',
'pagehist'                    => 'पृष्ठस्य इतिहासः',
'deletedhist'                 => 'परिमार्जितेतिहासः ।',
'revdelete-hide-current'      => '$2 $1 दिनाङ्कितस्य गोपने दोषः । एतत् प्रकृतावतरणम्, एतत् न गोपनीयम् ।',
'revdelete-show-no-access'    => '$2, $1: दिनाङ्कितस्य दर्शने दोषः । एतत् पञ्जीकृतमिति अङ्कितम् । एतत् प्राप्तुं नैव शक्नोति ।',
'revdelete-modify-no-access'  => ' $2, $1 दिनाङ्कितं परिवर्तने दोषः । एतत् निर्बन्धितमिति अङ्कितम् । एतत् प्राप्तुं नैव शक्नोति ।',
'revdelete-modify-missing'    => '$1 दिनाङ्कितं परिवर्तने दोषः । मूलपाठात् विहीनम् एतत् ।',
'revdelete-no-change'         => "'''पूर्वसूचना ''' $2, $1 दिनाङ्किताः पूर्वमेव दृश्यतासंयोजनम् आभ्यर्थिताः ।",
'revdelete-concurrent-change' => '$2, $1: दिनाङ्कितदेषपरिमार्जनानि । अस्य स्तरः केनचित् परिवर्तितं यत् भवता परिवर्तितुं प्रयत्नः कृतः । प्रवेशं परिशीलयतु ।',
'revdelete-only-restricted'   => '$2, $1: दिनाङ्कितस्य गोपने दोषाः। अन्यदृश्यविकल्पानां चयनेन विना एतत् निग्रहितुं नैव शक्नोति ।',
'revdelete-reason-dropdown'   => '*परित्यागाय समानकारणाः
** प्रतिलिपिअधिकारअतिक्रम
** अयोग्यवैयक्तिकविज्ञप्ति',
'revdelete-otherreason'       => 'अन्यत्/सङ्कलितं कारणम् :',
'revdelete-reasonotherlist'   => 'अन्यानि कारणानि',
'revdelete-edit-reasonlist'   => 'सम्पादनस्य अपाकरणाय कारणानि',
'revdelete-offender'          => 'अवतरणकर्ता ।',

# Suppression log
'suppressionlog'     => 'निग्रहनामाभिलेखः ।',
'suppressionlogtext' => 'अधोनिदेशितप्रशासकैः सङ्गुप्तस्य विभागस्य निष्कासितपुटानां सूची ।
निषिद्धपिहितपुटानि  [[Special:BlockList|block list]] पश्यतु ।',

# History merging
'mergehistory'                     => 'संलीनपुटेतिहासाः ।',
'mergehistory-header'              => 'एतत्पुटं कस्यचित् स्रोतपुटस्य इतिहासस्य संयोजनार्थमस्ति ।
एतत्परिवर्तनं पूर्वतनपुटैः नैरन्तर्यं रक्षति इति दृढयतु ।',
'mergehistory-box'                 => 'पुटद्वयस्य अवतरणं व्यलीयताम् ।',
'mergehistory-from'                => 'मूलपुटम् ।',
'mergehistory-into'                => 'लक्षितपुटम् ।',
'mergehistory-list'                => 'विलीनयोग्यसम्पादनस्य इतिहासः ।',
'mergehistory-merge'               => '[[:$1]] इत्यस्य निम्नावतरणं । [[:$2]] इत्यनेन संयोजयितुं शक्यते । निर्दिष्टकाले सर्जितानि संयोजयितुं रेडियोपिञ्जस्तम्भम् उपयोजयतु । 
सञ्चलनस्य अनुबन्धाः स्तम्भमेतं पुनःस्थापयति ।',
'mergehistory-go'                  => 'विलीनयोग्यसम्पादनानि दर्शयतु ।',
'mergehistory-submit'              => 'अवतरणं योजयतु ।',
'mergehistory-empty'               => 'अवतरणानि संयोजयितुं न शक्यते ।',
'mergehistory-success'             => '$3 {{PLURAL:$3|revision|revisions}} of [[:$1]] successfully merged into [[:$2]].',
'mergehistory-fail'                => 'इतिहासविलीनता नैव शक्यते । पुटं कालं व्याप्तिं च परिशीलयतु ।',
'mergehistory-no-source'           => 'पूलपुटं $1 अस्तित्वं नास्ति ।',
'mergehistory-no-destination'      => 'लक्षितपुटं $1 अस्तित्वे नास्ति ।',
'mergehistory-invalid-source'      => 'मूलपुटस्य मान्यशीर्षिका स्यात् ।',
'mergehistory-invalid-destination' => 'लक्षितपुटं मानितशीर्षिकायुतं भवेत् ।',
'mergehistory-autocomment'         => 'लीनं [[:$1]] into [[:$2]]',
'mergehistory-comment'             => 'लीनं [[:$1]]    [[:$2]] : $3',
'mergehistory-same-destination'    => 'मूलपुटं लक्षितपुटं च समानं न भवेत् ।',
'mergehistory-reason'              => 'कारणम् :',

# Merge log
'mergelog'           => 'नामाभिलेखं योजयतु ।',
'pagemerge-logentry' => '[[$1]]  तु [[$2]] मध्ये विलीनम् (अवतरणं $3 पर्यन्तम् ) ।',
'revertmerge'        => 'पृथक्क्रियताम्',
'mergelogpagetext'   => 'अतिनूतनविलीनस्य आवली अधो दत्ता यस्य इतिहासः अन्यस्मिन् अस्ति ।',

# Diffs
'history-title'            => '"$1" इत्येतस्य आवर्तनेतिहासः :',
'difference'               => '(संस्करणानां भेदाः)',
'difference-multipage'     => 'पुटेषु व्यत्यासः ।',
'lineno'                   => 'पंक्तिः $1:',
'compareselectedversions'  => 'चितानाम् आवृत्तीनां तोलनं क्रियताम्',
'showhideselectedversions' => 'चितावतरणानि दर्शयतु/गोपयतु ।',
'editundo'                 => 'निष्क्रियताम्',
'diff-multi'               => '({{PLURAL:$2|योजकेन|$2 योजकैः}} कृता {{PLURAL:$1|मध्यमा आवृत्तिः|$1 मध्यमा आवृत्तयः}} न दर्शिताः ।)',
'diff-multi-manyusers'     => '({{PLURAL:$2|योजकेन|$2 योजकैः}} कृता {{PLURAL:$1|मध्यमा आवृत्तिः|$1 मध्यमा आवृत्तयः}} न दर्शिताः ।)',

# Search results
'searchresults'                    => 'अन्वेषणस्य फलितानि',
'searchresults-title'              => '"$1" इत्यस्य कृते अन्वेषणफलानि',
'searchresulttext'                 => '{{SITENAME}} इत्यस्मिन् अन्वेषणे सहाय्यार्थम् [[{{MediaWiki:Helppage}}|{{int:help}}]] इत्येतत् पश्यतु ।',
'searchsubtitle'                   => 'भवान् \'\'\'[[:$1]]\'\'\'([[Special:Prefixindex/$1|सर्वाणि "$1" इत्यस्माद् आरभमन्तः पृष्ठाणि]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|सर्वाणि "$1" इत्येतत्प्रति संबद्धानि पृष्ठाणि]]) इत्यस्य कृते अन्विष्टवान्।',
'searchsubtitleinvalid'            => "भवता '''$1''' इत्यस्य कृते अन्वेषणं कृतम्",
'toomanymatches'                   => 'अत्यधिकाः मेलाः प्रत्यागताः । अन्यप्रश्नेन यतताम् ।',
'titlematches'                     => 'पुटशीर्षिकामेलाः ।',
'notitlematches'                   => 'न कस्यापि पृष्ठस्य शीर्षकम् अस्य समम्।',
'textmatches'                      => 'पुटपाठस्य मेलाः',
'notextmatches'                    => 'न कस्यापि पृष्ठस्य पाठः अस्य सममस्ति',
'prevn'                            => 'प्राक्तनानि {{PLURAL:$1|$1}}',
'nextn'                            => 'अग्रिमाणि {{PLURAL:$1|$1}}',
'prevn-title'                      => 'प्राक्तन-{{PLURAL:$1|फलितम्| फलितानि}}',
'nextn-title'                      => 'प्राक्तन-{{PLURAL:$1|फलितम्| फलितानि}}',
'shown-title'                      => 'प्रत्येकस्मिन् पृष्ठे $1 {{PLURAL:$1|फलितम्|फलितानि}} दर्श्यताम्',
'viewprevnext'                     => 'दर्श्यताम् ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'अन्वेषणस्य विकल्पाः ।',
'searchmenu-exists'                => 'अस्मिन् विकिमध्ये "[[:$1]]"नामकं पृष्ठं विद्यते।',
'searchmenu-new'                   => "'''अस्यां विक्यां \"[[:\$1]]\" इति पृष्ठं सृज्यताम्!'''",
'searchhelp-url'                   => 'Help: साहाय्यम् : आधेयाः ।',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|एतदुपसर्गयुक्तपुटं पश्यतु ]]',
'searchprofile-articles'           => 'आन्तर्यम्',
'searchprofile-project'            => 'सहायता प्रकल्पपृष्ठानि च',
'searchprofile-images'             => 'बहुमाध्यमः',
'searchprofile-everything'         => 'सर्वम्',
'searchprofile-advanced'           => 'प्रगतम्',
'searchprofile-articles-tooltip'   => '$1 स्थले अन्विष्यताम्',
'searchprofile-project-tooltip'    => '$1 स्थले अन्विष्यताम्',
'searchprofile-images-tooltip'     => 'सञ्चिका अन्विष्यताम्',
'searchprofile-everything-tooltip' => '(चर्चापृष्ठानि अविहाय) सर्वत्र अन्विष्यताम्',
'searchprofile-advanced-tooltip'   => 'विशेषनामस्थानेषु अन्विष्यताम्',
'search-result-size'               => '$1 ({{PLURAL:$2|1 शब्दः|$2 शब्दाः}})',
'search-result-category-size'      => '{{PLURAL:$1|1 सदस्यः|$1 सदस्याः}} ({{PLURAL:$2|1 उपवर्गः|$2 उपर्गाः}}, {{PLURAL:$3|1 सञ्चिका|$3 सञ्चिकाः}})',
'search-result-score'              => 'सम्बन्धः $1% ।',
'search-redirect'                  => '($1 इत्यत्र अनुप्रेषितम्)',
'search-section'                   => '(विभागः $1)',
'search-suggest'                   => 'किं भवतः आशयः एवमस्ति : $1',
'search-interwiki-caption'         => 'बन्धु-प्रकल्पाः',
'search-interwiki-default'         => '$1 परिणामाः :',
'search-interwiki-more'            => '(अधिकानि)',
'search-mwsuggest-enabled'         => 'उपक्षेपेभ्यः सह',
'search-mwsuggest-disabled'        => 'नात्र उपक्षेपाः',
'search-relatedarticle'            => 'सम्बद्धानि ।',
'mwsuggest-disable'                => 'निष्क्रियाः AJAX सूचनाः ।',
'searcheverything-enable'          => 'सर्वनामावकाशे अन्विषतु ।',
'searchrelated'                    => 'सम्बद्धानि',
'searchall'                        => 'सर्वाणि',
'showingresults'                   => "निम्नगतक्रमाङ्कस्य '''$2''' तः आरभ्य अधिकतमं परिणामः'''$1''' {{PLURAL:$1| दर्शितः}}।",
'showingresultsnum'                => "निम्नगतक्रमाङ्क'''$2'''तः आरभ्य अधिकतमः '''$3''' परिणामः {{PLURAL:$3|दर्शितः}}।",
'showingresultsheader'             => "'''$4''' इत्येतस्य {{PLURAL:$5|'''$3'''स्य '''$1'''  फलितम्|'''$3'''स्य '''$1 - $2'''  फलितानि}}",
'nonefound'                        => "'''सूचना''': स्वतः अत्र केषुचिदेव नामाकाशेषु अन्वेषणं क्रियते।

सकले घटके अन्वेषणं कर्तुं स्व अन्वेषणपदेभ्यः पूर्वं ''all:'' इति योजयतु, अथवा इष्टं नामाकाशं पूर्वलग्नरूपेण योजयतु।",
'search-nonefound'                 => 'भवतः अपेक्षानुगुणं फलितं न किमपि विद्यते ।',
'powersearch'                      => 'प्रगतम् अन्वेषणम्',
'powersearch-legend'               => 'प्रगतम् अन्वेषणम्',
'powersearch-ns'                   => 'नामाकाशेषु अन्विष्यताम्:',
'powersearch-redir'                => 'अनुप्रेषणानां सूचिका दर्श्यताम्',
'powersearch-field'                => 'इत्यस्मै अन्विष्यताम्',
'powersearch-togglelabel'          => 'आयीका:',
'powersearch-toggleall'            => 'सर्वम्',
'powersearch-togglenone'           => 'नास्ति',
'search-external'                  => 'बाह्यान्वेषणम्',
'searchdisabled'                   => '{{SITENAME}} अन्वेषणं निष्क्रियम्
अश्मिन् समये भवान् गूगल माध्यमेन अन्वेषणं कर्तुं शक्नोति
स्मरयतु यत् {{SITENAME}} इति स्थलस्य क्रमाङ्का नैव अद्यातना  इति सोच्यते।',

# Quickbar
'qbsettings'                => 'शीघ्रपट',
'qbsettings-none'           => 'नास्ति',
'qbsettings-fixedleft'      => 'बामे स्थापितः',
'qbsettings-fixedright'     => 'दक्षिणे स्थापितः',
'qbsettings-floatingleft'   => 'वामप्लवनम् ।',
'qbsettings-floatingright'  => 'दक्षिणे प्लवनम् ।',
'qbsettings-directionality' => 'निश्चितम् । ते भाषालिप्याः दिशात्मकतानुसारं भवति ।',

# Preferences page
'preferences'                   => 'इष्टतमानि',
'mypreferences'                 => 'मम इष्टतमानि',
'prefs-edits'                   => 'सम्पादनानां सख्याः',
'prefsnologin'                  => 'नैव प्रविष्ट',
'prefsnologintext'              => 'वरीयतां परिवर्तयितुं भवता <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}}नामाभिलेखः]</span> करणियः।',
'changepassword'                => 'कूटशब्दः परिवर्त्यताम्',
'prefs-skin'                    => 'त्वक्',
'skin-preview'                  => 'प्राग्दृश्यम्',
'datedefault'                   => 'वरीयांसि नास्ति',
'prefs-beta'                    => 'आवर्णलक्षणानि ।',
'prefs-datetime'                => 'दिनांक तथा समय',
'prefs-labs'                    => 'प्रयोगशालालक्षणानि ।',
'prefs-personal'                => 'योजकः व्यक्तिरेखा',
'prefs-rc'                      => 'सद्योजातानि परिवर्तनानि',
'prefs-watchlist'               => 'दृष्टि सूची',
'prefs-watchlist-days'          => 'दृष्टि सूची दर्शनार्थे  दिवसानि',
'prefs-watchlist-days-max'      => 'अधिकतमानि $1 {{PLURAL:$1|दिनानि}}',
'prefs-watchlist-edits'         => 'विस्तृतावलोकनावल्यां प्रदर्शयितुम् अत्यधिकपरिवर्तनानि ।',
'prefs-watchlist-edits-max'     => 'अधिकतम संख्या: १०००',
'prefs-watchlist-token'         => 'अवलोकनावल्याः प्रतीकः ।',
'prefs-misc'                    => 'विविधः',
'prefs-resetpass'               => 'कूटशब्दः परिवर्त्यताम्',
'prefs-changeemail'             => 'विद्युन्मानपत्रसङ्केतं परिवर्तयतु ।',
'prefs-setemail'                => 'विद्युन्मानपत्रसङ्केतं योजयतु ।',
'prefs-email'                   => 'इमेल वैकल्पिकाः',
'prefs-rendering'               => 'स्वरुपः',
'saveprefs'                     => 'संरक्ष्यताम्',
'resetprefs'                    => 'असंरक्षितानि परिवर्तनानि विलुप्यन्ताम्',
'restoreprefs'                  => 'समग्राः व्यवस्थादय व्यवस्थानुसारं पुनः संरक्ष्यताम्',
'prefs-editing'                 => 'सम्पादनम्',
'prefs-edit-boxsize'            => 'सम्पादनकोष्ठस्य आकारः ।',
'rows'                          => 'पंक्ति',
'columns'                       => 'अध: पंक्त्याः',
'searchresultshead'             => 'अन्वेषणम्',
'resultsperpage'                => 'प्रति पृष्ट हिट्स:',
'stub-threshold'                => '<a href="#" class="stub">आधारानुबन्धानां </a>अधिकतमाकारः ।',
'stub-threshold-disabled'       => 'निष्क्रियः',
'recentchangesdays'             => 'दिवसानि पर्यन्तो सद्यावधि-परिवर्तनानि दृश्यतु:',
'recentchangesdays-max'         => 'अधिकतम $1 {{PLURAL:$1|दिवसः|दिवसानि}}',
'recentchangescount'            => 'सम्पादन संख्यकानि व्यवस्थानुसारेण दृश्यतु:',
'prefs-help-recentchangescount' => 'अत्र सद्यः परिवर्तनानि, पुटेतिहासाः, प्रवेशाः च अन्तर्गताः ।',
'prefs-help-watchlist-token'    => 'अत्र रहस्यकुञ्चिकया पूरणेन भवतः नीरीक्षावल्यां RSS पूरितं भवति । रहस्यकुञ्चिकां यः जानाति तेन भवतः निरीक्षावली दृष्टुं शक्यते । अतः कृपया सुरक्षमौल्यं चिनोतु । अत्र यादृच्छया निर्मितं मौल्यं भवान्  $1 द्वारा पश्यति ।',
'savedprefs'                    => 'आद्यताः संरक्षिताः ।',
'timezonelegend'                => 'समय मण्डल:',
'localtime'                     => 'स्थानीय समय:',
'timezoneuseserverdefault'      => 'विकिनिश्चितं ($1) उपयुज्यताम् ।',
'timezoneuseoffset'             => 'अन्ये (समयान्तरं निर्दिशतु )',
'timezoneoffset'                => 'समयान्तरम् ¹',
'servertime'                    => 'वितारकसमयः ।',
'guesstimezone'                 => 'जालदर्शिकातः पूरयतु ।',
'timezoneregion-africa'         => 'कालद्वीप',
'timezoneregion-america'        => 'अमेरिका',
'timezoneregion-antarctica'     => 'अंटार्कटिका',
'timezoneregion-arctic'         => 'आर्कटिक',
'timezoneregion-asia'           => 'जम्बुमहाद्वीप',
'timezoneregion-atlantic'       => 'एटलांटिक महासागर',
'timezoneregion-australia'      => 'ऑस्ट्रेलिया',
'timezoneregion-europe'         => 'यूरोप',
'timezoneregion-indian'         => 'हिंद महासागर',
'timezoneregion-pacific'        => 'प्रशांत महासागर',
'allowemail'                    => 'अन्योपयोजकानां विद्युन्मानसङ्केतं निष्कियं करोतु ।',
'prefs-searchoptions'           => 'अन्वेषणविकल्पाः ।',
'prefs-namespaces'              => 'नामाकाशः :',
'defaultns'                     => 'अन्यथा एतेषु नामाकाशेषु अन्विषतु ।',
'default'                       => 'यदभावे',
'prefs-files'                   => 'सञ्चिका',
'prefs-custom-css'              => 'सि.एस्.एस्.रचयतु ।',
'prefs-custom-js'               => 'जावालिपिं रचयतु ।',
'prefs-common-css-js'           => 'सर्वावरणानां कृते विभक्त सि.एस्.एस्./ जावालिपिः ।',
'prefs-reset-intro'             => 'आद्यतानां पुनर्निदेशार्थम् एतत्पुटम् उपयोक्तुं शकोति । एतत् अकृतं न भवति ।',
'prefs-emailconfirm-label'      => 'विद्युन्मानसङ्केतस्य दृढीकरणम् ।',
'prefs-textboxsize'             => 'सम्पादनकोष्ठस्य आकारः ।',
'youremail'                     => 'ईपत्रसङ्केतः',
'username'                      => 'योजकनामन्:',
'uid'                           => 'प्रयोक्तृ-क्रमांकः :',
'prefs-memberingroups'          => '{{PLURAL:$1|समूहस्य|समूहानां}}  सदस्यः:',
'prefs-registration'            => 'पंजीकरण कालः:',
'yourrealname'                  => 'वास्तविकं नाम:',
'yourlanguage'                  => 'भाषा:',
'yourvariant'                   => 'भाषासामग्रीणां संस्करणम् ।',
'prefs-help-variant'            => ' विक्यां प्रदर्शितुं भवति ।',
'yournick'                      => 'नूतनाः हस्ताक्षराः:',
'prefs-help-signature'          => 'संभाषणपृष्ठगताः संवादाः "<nowiki>~~~~</nowiki>" इति लिखित्वा हस्ताक्षरोपेताः कर्त्तव्याः। एतानि चिह्नानि पृष्ठरक्षणपश्चात् भवतः हस्ताक्षरान् समयमुद्रां च प्रदर्शयिष्यन्ति।',
'badsig'                        => 'अमान्याः (त्रुटिपूर्णाः) हि एते अपक्वाः हस्ताक्षराः।
एचटीएमएल्-टैग इत्येतानि पुनरीक्षितव्यानि भवता।',
'badsiglength'                  => 'भवतः हस्ताक्षराः तु अतीव दीर्घाः।
एते $1 {{PLURAL:$1|अक्षरात्|अक्षरेभ्यः}} दीर्घाः न भवितव्याः।',
'yourgender'                    => 'लिंगम् (Gender):',
'gender-unknown'                => 'अनिर्दिष्टम्',
'gender-male'                   => 'पुरुष',
'gender-female'                 => 'स्त्री',
'prefs-help-gender'             => 'वैकल्पिकः : अयं तन्त्रांशः लिङ्गानुसारसम्बोधनस्य उपयोजकः ।',
'email'                         => 'विद्युत्पत्रव्यवस्था',
'prefs-help-realname'           => 'निजनामधेयस्य उल्लेखः आवश्यकः नास्ति । 
यदि ददाति तर्हि अस्य प्रयोगः भवतः योगदानार्थं भवते श्रेयं दातुम् उपयुक्तः भवति ।',
'prefs-help-email'              => 'ईपत्रसङ्केतः अनिवार्यः नास्ति । किन्तु कूटशब्दः विस्मर्यते चेत् तस्य परिवर्तनाय अवश्यकः भवति ।',
'prefs-help-email-others'       => 'अन्ये योजकाः ईपत्रमाध्यमेन भवतः सम्पर्कं यथा कुर्युः तथा भवदीये योजकपृष्ठे सम्भाषणपृष्ठे वा सम्पर्कतन्तुः योजयितुं शक्यः ।
भवतः सम्पर्कं कृतवद्भिः योजकैः भवदीयः ईपत्रसङ्केतः अभिज्ञातः न भवति ।',
'prefs-help-email-required'     => 'विद्युन्मानपत्रसङ्केतः आवश्यकः ।',
'prefs-info'                    => 'मूलसूचनाः ।',
'prefs-i18n'                    => 'अन्ताराष्ट्रिकरणम् ।',
'prefs-signature'               => 'हस्ताक्षर',
'prefs-dateformat'              => 'दिनाङ्कस्य प्रारूपः',
'prefs-timeoffset'              => 'समयान्तरम् ।',
'prefs-advancedediting'         => 'उन्नतविकल्पाः',
'prefs-advancedrc'              => 'उन्नतविकल्पाः',
'prefs-advancedrendering'       => 'उन्नतविकल्पाः',
'prefs-advancedsearchoptions'   => 'उन्नतविकल्पाः',
'prefs-advancedwatchlist'       => 'उन्नतविकल्पाः',
'prefs-displayrc'               => 'प्रदर्शनविकल्पाः',
'prefs-displaysearchoptions'    => 'प्रदर्शनविकल्पाः',
'prefs-displaywatchlist'        => 'प्रदर्शनविकल्पाः',
'prefs-diffs'                   => 'अन्तरम्',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'प्रयुक्तः विद्युन्मानपत्रसङ्केतः मानितः ।',
'email-address-validity-invalid' => 'मान्यः विद्युन्मानपत्रसङ्केतः योजनीयः ।',

# User rights
'userrights'                   => 'योजकाधिकारस्य प्रबन्धनम् ।',
'userrights-lookup-user'       => 'योजकसमूहं प्रबन्धयतु ।',
'userrights-user-editname'     => 'योजकनाम योजयतु ।',
'editusergroup'                => 'योजकसमूहं सम्पादयतु ।',
'editinguser'                  => "'''[[User:$1|$1]]''' $2 इति योजकस्य योजकाधिकारः परिवर्त्यते ।",
'userrights-editusergroup'     => 'योजकसमूहं सम्पादयतु ।',
'saveusergroups'               => 'योजकसमूहं संरक्षतु ।',
'userrights-groupsmember'      => 'अस्य सदस्यः  ।',
'userrights-groupsmember-auto' => 'अस्य निश्चितसदस्यः ।',
'userrights-groups-help'       => 'अस्य सदस्यस्य समूहसदस्यत्वं परिवर्तयितुं शक्यते । 
* मञ्जूषा अङ्किता चेत् योजकः अस्य समूहस्य सदस्यः अस्ति ।
* मञ्जूषा अनङ्किता चेत् योजकः अस्य समूहस्य सदस्यः न 
* कदाचित् भवता समूहः योजितः चेत् अपनेतुं नैव शक्नोति इति * चिह्नं सूचयति ।',
'userrights-reason'            => 'कारणम् :',
'userrights-no-interwiki'      => 'अन्यविकिषु योजकाधिकारं सम्पादयितुं ते अनुमतिः नास्ति ।',
'userrights-nodatabase'        => '$1 मूलपाठाः न सन्ति अथवा स्थानीयाः ।',
'userrights-nologin'           => '[[Special:UserLogin|log in]] प्रशासकस्थानेन प्रविश्य योजकाधिकारान् निर्देष्टुं शक्नोति ।',
'userrights-notallowed'        => 'योजकाधिकारान् अपनेतुं ते स्थानस्य अनुमतिः नास्ति ।',
'userrights-changeable-col'    => 'परिवर्तनार्हाः समूहाः ।',
'userrights-unchangeable-col'  => 'परिवर्तनार्हाः समूहाः ।',

# Groups
'group'               => 'समूहः :',
'group-user'          => 'योजकः',
'group-autoconfirmed' => 'स्वदृढितयोजकाः ।',
'group-bot'           => 'स्वयं सक्रियाः ।',
'group-sysop'         => 'प्रबंधकाः',
'group-bureaucrat'    => 'स्वयम् अधिकारिणः ।',
'group-suppress'      => 'अलक्ष्यम् ।',
'group-all'           => '(सर्वे)',

'group-user-member'          => '{{GENDER:$1|योजक}}',
'group-autoconfirmed-member' => '{{GENDER:$1|स्वस्थानदृढितः योजकः}}',
'group-bot-member'           => '{{GENDER:$1|स्वयं सक्रियः}}',
'group-sysop-member'         => '{{GENDER:$1|प्रशासकः}}',
'group-bureaucrat-member'    => '{{GENDER:$1|स्वयम् अधिकारी}}',
'group-suppress-member'      => '{{GENDER:$1|अलक्ष्यम्}}',

'grouppage-user'          => '{{ns:project}}:योजक',
'grouppage-autoconfirmed' => '{{ns:project}}: स्वयंदृढितयोजकाः ।',
'grouppage-bot'           => '{{ns:project}}: स्वयंसक्रियाः।',
'grouppage-sysop'         => '{{ns:project}}:प्रचालकाः',
'grouppage-bureaucrat'    => '{{ns:project}}: स्वयम् अधिकारिणः ।',
'grouppage-suppress'      => '{{ns:project}}: अक्ष्यम् ।',

# Rights
'right-read'                  => 'पुटानि पठतु ।',
'right-edit'                  => 'पुटसम्पादनं करोतु ।',
'right-createpage'            => 'पुटनिर्माणं करोतु ।(यानि चर्च्यानि न सन्ति)',
'right-createtalk'            => 'चर्च्यपुटानां निर्माणं करोतु ।',
'right-createaccount'         => 'नूतनयोजकस्थानं निर्मातु ।',
'right-minoredit'             => 'सम्पादनं लघुचिह्नया निर्दिशतु ।',
'right-move'                  => 'पुटं चालयतु ।',
'right-move-subpages'         => 'उपपुटैः सह पुटं चालयतु ।',
'right-move-rootuserpages'    => 'मूलयोजकपुटानि चालयतु ।',
'right-movefile'              => 'सञ्चिकाः चालयतु ।',
'right-suppressredirect'      => 'पुटचालनावसरे मूलपुटेभ्यः पुनर्निदेशं न सृजतु ।',
'right-upload'                => 'सञ्चिकाः उत्तारयतु ।',
'right-reupload'              => 'स्थितसञ्चिकाः पुनर्लिखतु ।',
'right-reupload-own'          => 'एकेन उत्तारितसञ्चिकाः पुनर्लिखतु ।',
'right-reupload-shared'       => 'विभक्तमाध्यमकोशगतसञ्चिकाः अतिसञ्चरतु ।',
'right-upload_by_url'         => 'अन्तर्जालस्थानात् सञ्चिकाः उत्तारयतु ।',
'right-purge'                 => 'दृढतारहितपुटस्य क्षेत्राधारं पुनातु ।',
'right-autoconfirmed'         => 'अल्परक्षितपुटनि सम्पादयतु ।',
'right-bot'                   => 'स्वचालितप्रक्रियाः इव उपचारितः भवतु ।',
'right-nominornewtalk'        => 'चर्चापुटानां लघुसम्पादनं न भवतु । नूतनसन्देशान् चोदयतु ।',
'right-apihighlimits'         => 'API प्रश्नेषु उन्नतसीमम् उपयोजयतु ।',
'right-writeapi'              => 'श्वेतं API उपयोगः ।',
'right-delete'                => 'पुटानि परिमार्जयतु ।',
'right-bigdelete'             => 'दीर्घेतिहासयुक्तपुटानि परिमार्जयतु ।',
'right-deleterevision'        => 'निर्दिष्टावरतरणस्य पुटानि अपमर्जतु, अनपमर्जतु ।',
'right-deletedhistory'        => ' तत्सम्बद्धपाठैः विनाअपमर्जितेतिहासप्रवेशस्य दर्शनम् ।',
'right-deletedtext'           => 'अपमर्जितावतरणेषु परिवर्तनं, अपमर्जितपाठान् च अवलोकयतु ।',
'right-browsearchive'         => 'अपमर्जितपुटानि अन्विषतु ।',
'right-undelete'              => 'पुटम् अनपमर्जतु ।',
'right-suppressrevision'      => 'प्रशासकेभ्यः सङ्गुप्तावतरणानि पुनरालोक्य पुनरानयतु ।',
'right-suppressionlog'        => 'स्वायत्तनामाबिलेखं पश्यतु ।',
'right-block'                 => 'अन्ययोजकान् सम्पादनेन अवरोधतु ।',
'right-blockemail'            => 'योजकस्य विद्युन्मानसन्देशप्रेषणम् अवरोधतु ।',
'right-hideuser'              => 'योजकनाम अवरोधतु । तेन सर्वजनोपयोगात् गोपयतु ।',
'right-ipblock-exempt'        => 'IP अवरोधं मार्गयतु, स्वयम् अवरोधः, निर्दिष्टावरोधः ।',
'right-proxyunbannable'       => 'अन्येषां स्वयंचालितावरोधं परिहरतु ।',
'right-unblockself'           => 'स्वयम् अनवरोधं करोतु ।',
'right-protect'               => 'सुरक्षास्तरान् परिवर्तयतु । सुरक्षितपुटानि सम्पादयतु ।',
'right-editprotected'         => 'सुरक्षितपुटानि सम्पादयतु ।',
'right-editinterface'         => 'योजकमाध्यमं सम्पादयतु ।',
'right-editusercssjs'         => 'अन्ययोजकान् सम्पादयतु । सि.एस्.एस्. जावलालिपिसञ्चिकाः च ।',
'right-editusercss'           => 'अन्ययोजकान् सम्पादयतु सि.एस्.एस्. सञ्चिकाः ।',
'right-edituserjs'            => 'अन्ययोजकान सम्पादयतु जावालिपिसञ्चिकाः ।',
'right-rollback'              => 'अन्तिमयोजकस्य सम्पादनं शीघ्रं प्रचालयतु यः निर्दिष्टपुटं सम्पादितवान् ।',
'right-markbotedits'          => 'प्रतिचालितसम्पादनानि स्वचालितसम्पदनं इव  अङ्कितानिकरोतु ।',
'right-noratelimit'           => 'मूल्यनियत्या प्रभावितं नस्यात् ।',
'right-import'                => 'अन्यविकितः पुटानाम् आयातं करोतु ।',
'right-importupload'          => 'उत्तारितसञ्चिकातः पुटानि आयातानि करोतु ।',
'right-patrol'                => 'अन्येषां सम्पादनम् आरक्षितमिव अङ्कयतु ।',
'right-autopatrol'            => 'कस्यचित् स्वस्य सम्पादनानि आरक्षितमिव स्वयम् अङ्कयतु ।',
'right-patrolmarks'           => 'आरक्षणाङ्कितानां सद्यः परिवर्तनानि अवलोकयतु ।',
'right-unwatchedpages'        => 'अपरीक्षितपुटानाम् आवलीम् अवलोकयतु ।',
'right-mergehistory'          => 'पुटेतिहासं विलीनं करोतु ।',
'right-userrights'            => 'सर्वयोजकाधिकारं सम्पादयतु ।',
'right-userrights-interwiki'  => 'योजकाधिकारान् अन्यविकिषु सम्पादयतु ।',
'right-siteadmin'             => 'पाठमूलस्य निशेधनम् अनिशेधनं च ।',
'right-override-export-depth' => 'पञ्चस्तरपर्यन्तं संलग्नपुटानि निर्यातानि करोतु ।',
'right-sendemail'             => 'अन्ययोजकेभ्यः विद्युन्मानपत्राणि प्रेषयतु ।',
'right-passwordreset'         => 'निकुञ्चपुनारचितानां विद्युन्मानपत्राणाम् अवलोकनम् ।',

# User rights log
'rightslog'                  => 'प्रयोक्तृ-अधिकार-सूचिका',
'rightslogtext'              => 'अयं योजकाधिकारस्य परिवर्तनकुञ्चः ।',
'rightslogentry'             => '$2 - $3 तः $1 सामूहिकसदस्यत्वं परिवर्तितम् ।',
'rightslogentry-autopromote' => '$2 तः $3 स्वयम् उन्नतीकृतम् ।',
'rightsnone'                 => '(कतम)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'एतत्पुटं पठतु ।',
'action-edit'                 => 'इदं पृष्ठं सम्पाद्यताम्',
'action-createpage'           => 'पुटानि सृजतु ।',
'action-createtalk'           => 'चर्चापुटानि सृजतु ।',
'action-createaccount'        => 'नूतनयोजकस्थानं निर्मातु ।',
'action-minoredit'            => 'एतत्सम्पादनं लघु इति अङ्कयतु ।',
'action-move'                 => 'एतत्पुटं चालयतु ।',
'action-move-subpages'        => 'एतत्पुटम् अस्य उपपुटानि च चालयतु ।',
'action-move-rootuserpages'   => 'मूलयोजकपुटानि चालयतु ।',
'action-movefile'             => 'एतां सञ्चिकां चालयतु ।',
'action-upload'               => 'एतां सञ्चिकाम् उत्तारयतु ।',
'action-reupload'             => 'स्थितसञ्चिकां पुनर्लिखतु ।',
'action-reupload-shared'      => 'विभक्तकोशे एतां सञ्चिकां पुनर्लिखतु ।',
'action-upload_by_url'        => 'अन्तर्जालस्थानतः एतां सञ्चिकाम् उत्तारयतु ।',
'action-writeapi'             => 'श्वेतं API उपयोगः ।',
'action-delete'               => 'एतत्पुटं अपमर्जयतु ।',
'action-deleterevision'       => 'एतदवतरणम् अपमर्जतु ।',
'action-deletedhistory'       => 'अस्य पुटस्य अपमर्जितेतिहासम् अवलोकयतु ।',
'action-browsearchive'        => 'अपमर्जितपुटानि अन्विषतु ।',
'action-undelete'             => 'एतत्पुटम् अनपमर्जयतु ।',
'action-suppressrevision'     => 'सङ्गुप्तावतरणं पुनःपश्यतु पुनर्नयतु च ।',
'action-suppressionlog'       => 'एतत् स्वायत्तपुटम् अवलोकयतु ।',
'action-block'                => 'अन्ययोजकान् सम्पादनेन अवरोधतु ।',
'action-protect'              => 'अस्य पुटस्य सुरक्षास्तरं परिवर्तयतु ।',
'action-rollback'             => 'अन्तिमयोजकस्य सम्पादनं शीघ्रं प्रचालयतु यः निर्दिष्टपुटं सम्पादितवान् ।',
'action-import'               => 'अन्यविकितः एतत्पुटम् आयातयतु ।',
'action-importupload'         => 'उत्तारितसञ्चिकातः पुटानि आयातानि करोतु ।',
'action-patrol'               => 'अन्येषां सम्पादनम् आरक्षितमिव अङ्कयतु ।',
'action-autopatrol'           => 'भवतः सम्पादनम् आरक्षितम् इति अङ्कयतु ।',
'action-unwatchedpages'       => 'अपरीक्षितपुटानाम् आवलीम् अवलोकयतु ।',
'action-mergehistory'         => 'पुटेतिहासं विलीनं करोतु ।',
'action-userrights'           => 'सर्वयोजकाधिकारं सम्पादयतु ।',
'action-userrights-interwiki' => 'योजकाधिकारान् अन्यविकिषु सम्पादयतु ।',
'action-siteadmin'            => 'पाठमूलस्य निशेधनम् अनिशेधनं च ।',
'action-sendemail'            => 'विद्युन्मानपत्राणि प्रेषयतु ।',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|परिवर्तनम्|परिवर्तनानि}}',
'recentchanges'                     => 'सद्योजातानि परिवर्तनानि',
'recentchanges-legend'              => 'सद्योजातानां परिवर्तनानां विकल्पाः',
'recentchangestext'                 => 'अस्मिन् विकियोजनायां सद्योजातानि परिवर्तनानि दर्श्यन्ताम्',
'recentchanges-feed-description'    => 'अस्मिन् विकियोजनायां सद्योजातानि परिवर्तनानि दर्श्यन्ताम्',
'recentchanges-label-newpage'       => 'एतस्मात् सम्पादनात् नूतनं पृष्ठं सृष्टमस्ति',
'recentchanges-label-minor'         => 'इदं लघु परिवर्तनम्',
'recentchanges-label-bot'           => 'एतद् यन्त्रेण कृतं सम्पादनम् आसीत्',
'recentchanges-label-unpatrolled'   => 'एतद् सम्पादनम् एतावता परिशीलितं नास्ति ।',
'rcnote'                            => "अधस्तात् {{PLURAL:$1|'''1''' परिवर्तनमस्ति|अन्तिमानि '''$1''' परिवर्तनानि सन्ति}},{{PLURAL:$2|गते दिवसे|'''$2''' गतेषु दिवसेषु}}, $5, $4 इति समये।",
'rcnotefrom'                        => "अधः '''$2''' तः  ('''$1''' पर्यन्तं) परिवर्तनानि दर्शितानि सन्ति ।",
'rclistfrom'                        => '$1 तः जातानि नूतनानि परिवर्तनानि दर्श्यताम्',
'rcshowhideminor'                   => '$1 लघूनि सम्पादनानि',
'rcshowhidebots'                    => '$1 बोट् इत्येतानि',
'rcshowhideliu'                     => '$1 प्रविष्टाः योजकाः',
'rcshowhideanons'                   => 'अनामकाः योजकाः $1',
'rcshowhidepatr'                    => '$1 ईक्षितसम्पादनानि',
'rcshowhidemine'                    => '$1 मम सम्पादनानि',
'rclinks'                           => 'अन्तिमानि $1 परिवर्तनानि अन्तिमेषु $2 दिनेषु, दृश्यताम्<br />$3',
'diff'                              => 'भेदः',
'hist'                              => 'इतिहासः',
'hide'                              => 'गोप्यताम्',
'show'                              => 'दर्श्यताम्',
'minoreditletter'                   => '(लघु)',
'newpageletter'                     => '(नवीनम्)',
'boteditletter'                     => '(बोट्)',
'number_of_watching_users_pageview' => '[$1 अवलोकयति {{PLURAL:$1|योजकः|योजकाः}}]',
'rc_categories'                     => 'वर्गान् नियतीकरोतु ।',
'rc_categories_any'                 => 'कश्चित्',
'rc-change-size-new'                => '$1 {{PLURAL:$1|byte|bytes}} परिवर्तनपश्चात् ।',
'newsectionsummary'                 => '/* $1 */ नवीन विभागः',
'rc-enhanced-expand'                => 'विवरणानि दर्श्यन्ताम् (जावालिपिः अपेक्ष्यते)',
'rc-enhanced-hide'                  => 'विवरणानि गोप्यन्ताम्',
'rc-old-title'                      => 'मूलरूपेण $1 इति रचितम् ।',

# Recent changes linked
'recentchangeslinked'          => 'पृष्ठसम्बद्धानि परिवर्तनानि',
'recentchangeslinked-feed'     => 'पृष्ठ-सम्बन्धितानि परिवर्तनानि',
'recentchangeslinked-toolbox'  => 'पृष्ठसम्बद्धानि परिवर्तनानि',
'recentchangeslinked-title'    => '"$1" इत्यस्मिन् जातानि परिवर्तनानि',
'recentchangeslinked-noresult' => 'निर्दिष्टे अवधौ सम्बद्धे पृष्ठे कोपि परिवर्तनं न जातम् ।',
'recentchangeslinked-summary'  => "एषा विशेषपृष्ठसम्बद्धेषु पॄष्ठेषु अथवा वर्गविशेषे अन्तर्भूतेषु पृष्ठेषु सद्योजातानां परिवर्तनानाम् आवलिः।

[[Special:Watchlist|भवतः अवेक्षणसूच्यां]] विद्यमानानि पृष्ठानि '''स्थूलाक्षरैः''' दर्शितानि।",
'recentchangeslinked-page'     => 'पृष्ठ-नाम :',
'recentchangeslinked-to'       => 'अस्मिन् स्थाने अस्य पृष्ठस्य संबद्धानां पृष्ठानां परिवर्तनानि दर्श्यन्ताम्',

# Upload
'upload'                      => 'सञ्चिका आरोप्यताम्',
'uploadbtn'                   => 'सञ्चिका आरोप्यताम्',
'reuploaddesc'                => 'उत्तारणम् अपकर्षतु उत्तरणप्रपत्रम् आगच्छतु च ।',
'upload-tryagain'             => 'उन्नतीकृतं सञ्चिकाविवरणं समर्पयतु ।',
'uploadnologin'               => 'न प्रविष्टम्',
'uploadnologintext'           => 'सञ्चिकारोपणाय [[Special:UserLogin|अन्तःप्रवेशः]] अपेक्षितः ।',
'upload_directory_missing'    => 'उत्तारणनिदेशनं ($1) नष्टम्, जालवितारकेन सर्जितुं न शक्यते ।',
'upload_directory_read_only'  => 'उत्तारणनिदेशनं ($1) तु जालवितारकेन लेखनयोग्यं नास्ति ।',
'uploaderror'                 => 'उत्तरणदोषः ।',
'upload-recreate-warning'     => "''' पूर्वसूचना ''' तन्नामयुक्ता सञ्चिका अपमर्जिता अथवा चालिता ।",
'uploadtext'                  => "सञ्चिकाः उत्तर्तुम् अधः सूचितरूपणि उपयोजयतु ।
To view or search previously uploaded files go to the [[Special:FileList|list of uploaded files]], (re)uploads are also logged in the [[Special:Log/upload|upload log]], deletions in the [[Special:Log/delete|deletion log]].

To include a file in a page, use a link in one of the following forms:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' to use the full version of the file
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></code>''' to use a 200 pixel wide rendition in a box in the left margin with 'alt text' as description
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' for directly linking to the file without displaying the file",
'upload-permitted'            => 'अनुमतसञ्चिकाभेदाः $1.',
'upload-preferred'            => 'अनुमतसञ्चिकाभेदाः $1.',
'upload-prohibited'           => 'अनुमतसञ्चिकाभेदाः $1.',
'uploadlog'                   => 'उत्तरणस्य सूची ।',
'uploadlogpage'               => 'आरोपितानां सूची',
'uploadlogpagetext'           => 'अधः सद्यः काले उत्तारितसञ्चिकानाम् आवली अस्ति ।
अधिकदृश्यविवरणार्थम् एतत् पश्यतु [[Special:NewFiles|gallery of new files]]',
'filename'                    => 'सञ्चिकानाम',
'filedesc'                    => 'सारांशः :',
'fileuploadsummary'           => 'संग्रहः :',
'filereuploadsummary'         => 'सञ्चिकापरिवर्तनानि ।',
'filestatus'                  => 'प्रतिकृत्यधिकारस्य स्थितिः ।',
'filesource'                  => 'मूल:',
'uploadedfiles'               => 'आरोपिताः सञ्चिकाः',
'ignorewarning'               => 'पूर्वसूचनां निर्लक्ष्य सञ्चिकाः कथञ्चित् संरक्षतु ।',
'ignorewarnings'              => 'पूर्वसूचनाः निर्लक्षतु ।',
'minlength1'                  => 'सञ्चिकानाम न्यूनतिन्यूनम् एकाक्षरं भवेत् ।',
'illegalfilename'             => 'अस्यां "$1" सञ्चिकानाम्नि सङ्ख्या अस्ति । अत्र सा निषिद्धा । सञ्चिकां पुनः नामाङ्कयतु ।',
'filename-toolong'            => 'सञ्चिकानाम २४०बैट्स्तः अधिकदीर्घं न भवेत् ।',
'badfilename'                 => '"$1" इति सञ्चिकानाम परिवर्तितम् ।',
'filetype-mime-mismatch'      => '".$1" इति सञ्चिकाविस्तारः अपमर्जितया MIME ($2) प्रकारस्य सञ्चिका मेलं न करोति ।',
'filetype-badmime'            => 'MIME प्रकारस्य "$1" सञ्चिकाः उत्तारयितुं नार्हन्ति ।',
'filetype-bad-ie-mime'        => 'जालदर्शिकया सूचितं यत् "$1" सञ्चिका अपायकरिणीसञ्चिका इति । अतः एताम् उत्तारयितुं नैव शक्यते ।',
'filetype-unwanted-type'      => "'''\".\$1\"''' काचित् अनपक्षिता सञ्चिका अस्ति । 
अपेक्षिता सञ्चिका एषा {{PLURAL:\$3|अस्ति}} \$2।",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\'सञ्चिका {{PLURAL:$4|प्रकारस्य }} अनुमतिः नास्ति ।
प्रकारसञ्चिकायाः{{PLURAL:$3|}} अनुमतिरस्ति  $2।',
'filetype-missing'            => 'अस्याः सञ्चिकायाः विस्तारः नास्ति । (उदाहरणम् ".jpg")।',
'empty-file'                  => 'समर्पिता सञ्चिका रिक्ता अस्ति ।',
'file-too-large'              => 'संयोजिता शीर्षिका सुदीर्घा अस्ति ।',
'filename-tooshort'           => 'सञ्चिकानाम अतीव ह्रस्वम् अस्ति ।',
'filetype-banned'             => 'ईदृशी सञ्चिका प्रतिबन्धिता ।',
'verification-error'          => 'सञ्चिकापरीक्षायाम् इयं सञ्चिका अनुत्तीर्णा ।',
'hookaborted'                 => 'भवतः संस्करणप्रयत्नः विस्तारेण अपसारितः ।',
'illegal-filename'            => 'सञ्चिकानामलेखनं नानुमतः ।',
'overwrite'                   => 'वर्तमानसञ्चिकायाः पुनर्लेखनं नानुमतम् ।',
'unknown-error'               => 'अज्ञातदोषः उपगतः ।',
'tmp-create-error'            => 'तत्कालिकसञ्चिकां सृष्टुं नैव शक्यते ।',
'tmp-write-error'             => 'तात्कालिकसञ्चिकायाः दोषसम्पादनम् ।',
'large-file'                  => '$1; इयं सञ्चिका $2. तः अधिका दीर्घा न स्यात् इति सूचितम् ।',
'largefileserver'             => 'इयं सञ्चिका वितारकस्य निदेशनात् अधिका दीर्घा अस्ति ।',
'emptyfile'                   => 'उत्तारितसञ्चिका रिक्ता इति भाति । 
सञिकानामाङ्कनकारणं स्यात् ।
एतां सञ्चिकाम् उत्तारयितुमिच्छति वा इति परिशीलयतु ।',
'windows-nonascii-filename'   => 'एषा विकि विशेषाक्षरयुक्तं सञ्चिकानाम न अनुमन्यते ।',
'fileexists'                  => 'अनेन सञ्चिकानाम्ना काचित् सञ्चिकास्ति । यदि निश्चयेन न जानाति परिवर्तयितुम् इच्छति तर्हि  <strong>[[:$1]]</strong> एतत् परिशीलयतु । : [[$1|thumb]]',
'filepageexists'              => 'अस्याः सञ्चिकायाः विवरणपुटम् तावत् निर्मितम् एव । <strong>[[:$1]]</strong>, अनेन नाम्ना सद्यः कापि सञ्चिका वर्तते  । 
लिखितसारांशः विवरणपुटे न आगमिष्यति । 
ते सारांशः तत्रागन्तुं स्वयं सम्पादयतु । [[$1|thumb]]',
'fileexists-extension'        => 'अनेन नाम्ना सदृनामाङ्किता सञ्चिका पूर्वमेव अस्ति । [[$2|thumb]]
* उत्तर्यमानसञ्चिकायाः नाम  <strong>[[:$1]]</strong>
* वर्तमानसञिकायाः नाम <strong>[[:$2]]</strong>
* अन्यनाम चिनोतु ।',
'fileexists-thumbnail-yes'    => "एषा सञ्चिका बृहच्चित्रस्य क्षीणाकारा इति भाति । ''(उङ्गुष्टाकारः)''  [[$1|thumb]]
<strong>[[:$1]]</strong> सञ्चिकां पश्यतु । 
यदि परिक्षिता सञ्चिका एतादृशाकरस्य भवति तर्हि उत्तारणस्य आवश्यकता नास्ति ।",
'file-thumbnail-no'           => 'सञ्चिकानाम आरभते <strong>$1</strong>एतस्मात् ।
न्यूनीकृताकारस्य चित्रम् इति भाति  
यदि एतच्चित्रं मूलाकारेण अस्ति तर्हि उत्तारयतु अन्यथा न ।',
'fileexists-forbidden'        => 'एदादृशनाम्नः सञ्चिका तावत् पूर्वमेवोपस्थिता । अस्य स्थाने अन्यां नोत्तारयितुं शक्यते । 
तथापि यदि एतां सञ्चिकाम् उत्तारयितुम् इच्छति तर्हि सञ्चिकायाः नाम परिवर्तयतु ।
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'एतस्य नाम्नः सञ्चिका विभक्तभाण्डारे तावत् अस्ति एव । 
तथापि यदि एतां सञ्चिकाम् उत्तारयितुम् इच्छति तर्हि अस्याः नामपरिवर्तनं करोतु ।
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'एषा सञ्चिका तु {{PLURAL:$1|file|files}}: इत्यस्य प्रतिकृतिः ।',
'file-deleted-duplicate'      => 'अस्याः सञ्चिकायाः ([[:$1]]) सादृश्ययुक्ता सञ्चिकातु अपमर्जिता ।
एतस्याः उत्तारणात् पूर्वं प्राचीनसञ्चिकायाः इतिहासः अवलोकनीयः ।',
'uploadwarning'               => 'उत्तारणस्य पूर्वसूचना ।',
'uploadwarning-text'          => 'अधो दत्तं सञ्चिकाविवरणं संस्कृत्य पुनः यतताम् ।',
'savefile'                    => 'सञ्चिकां संरक्षतु ।',
'uploadedimage'               => '"[[$1]]" इत्येतद् आरोपितमस्ति',
'overwroteimage'              => '"[[$1]]" इत्यस्य नूतनावतरणम् उत्तारयतु ।',
'uploaddisabled'              => 'सक्रियम् उत्तारयतु ।',
'copyuploaddisabled'          => 'निष्क्रियतः यु.आर्.एल् तः उत्तारयतु  ।',
'uploadfromurl-queued'        => 'ते उत्तारणम् अनुपङ्कौ अस्ति ।',
'uploaddisabledtext'          => 'उत्तारितसञ्चिकाः निष्क्रियाः ।',
'php-uploaddisabledtext'      => 'PHP मध्ये उत्तारितसञ्चिकाः निष्क्रियाः ।',
'uploadscripted'              => 'HTMLयुक्ताः अथवा लिपिसङ्केतयुक्ताः सञ्चिकाः जालदर्शिकया बाधिताः ।',
'uploadvirus'                 => 'अस्यां सञ्चिकायां वैराणुः अस्ति । विवरणम् $1',
'uploadjava'                  => 'इयं ZIP सञ्चिका अस्यां जावावर्गस्य सञ्चिकाः सन्ति । 
जावासञ्चिकाः उत्तरणं निषिद्धम् । यतः अनेन सुरक्षाबन्धाः शिथिलाः भवन्ति ।',
'upload-source'               => 'मूलसञ्चिका ।',
'sourcefilename'              => 'मूलसञ्चिकायाः नाम ।',
'sourceurl'                   => 'मूलं URL:',
'destfilename'                => 'लक्षितसञ्चिकायाः नाम ।',
'upload-maxfilesize'          => 'सञ्चिकायाः गरिष्ठाकारः ।$1',
'upload-description'          => 'सञ्चिकाविवरणम् ।',
'upload-options'              => 'उत्तारणविकल्पाः ।',
'watchthisupload'             => 'इमां सञ्चिकाम् अवलोकयतु ।',
'filewasdeleted'              => 'अनेन नाम्ना उत्तारिता काचित् सञ्चिका पूर्वमेव अपमर्जिता ।
 $1 परिशील्य उत्तरणं पुनः उत्तारयतु ।',
'filename-bad-prefix'         => "यस्याः सञ्चिकायाः उत्तारणं कुर्वाणः अस्ति तस्य नाम '''\"\$1\"''' तः आरभते ।  यत् डिज़िटल् क्यामरा द्वारा दत्तम् अस्ति । 
अस्याः अधिकज्ञानप्रपकं किमपि अन्यत् नाम योजयतु ।",
'upload-success-subj'         => 'सफलम् उत्तारणम् ।',
'upload-success-msg'          => '[$2] तः उत्तारणं सफलम् । तदत्र अस्ति । [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'उत्तारणसमस्या ।',
'upload-failure-msg'          => '[$2]तः उत्तारणे कापिसमस्या आसीत् । 
$1',
'upload-warning-subj'         => 'उत्तारणस्य पूर्वसूचना ।',
'upload-warning-msg'          => ' [$2] तः उत्तारणे समस्या आसीत् । अस्याः समस्यायाः परिहारार्थम् अत्र गच्छतु  [[Special:Upload/stash/$1|उत्तारणप्रपत्रम्]]',

'upload-proto-error'        => 'सदोषः क्रमः ।',
'upload-proto-error-text'   => 'स्वयम् उत्तरणं <code>http://</code> or <code>ftp://</code>. इत्यनेन सह आरब्धः भवति ।',
'upload-file-error'         => 'आन्तरिकः दोषः',
'upload-file-error-text'    => 'वितारके तात्कालिकसञ्चिकानिर्माणावसरे उपगतः आन्तरिकदोषः । 
सम्पर्कयतु एतम् [[Special:ListUsers/sysop|administrator]]',
'upload-misc-error'         => 'अज्ञातः उत्तारणदोषः ।',
'upload-misc-error-text'    => 'उत्तारणावसरे कश्चन अज्ञातदोषः उपगतः । 
URL मान्यम् अभिगम्यं वेति परिशील्य पुनः यतताम् ।[[Special:ListUsers/sysop|administrator]]',
'upload-too-many-redirects' => 'URL अधिकपुनर्निदेशान् अन्तर्गतम् ।',
'upload-unknown-size'       => 'अज्ञात आकार',
'upload-http-error'         => 'कश्चन HTTP दोषः उपगतः $1',

# File backend
'backend-fail-stream'        => 'सञ्चिका क्रमगता न $1.',
'backend-fail-backup'        => 'सञ्चिकां प्रतिचयनम् अशक्तम् $1.',
'backend-fail-notexists'     => '$1 सञ्चिका न वर्तते ।',
'backend-fail-hashes'        => 'सञ्चिकादोषः तोलनार्थं न मिलति ।',
'backend-fail-notsame'       => '$1 मध्ये काचित् अज्ञातसञ्चिका पूर्वमेवास्ति ।',
'backend-fail-invalidpath'   => '$1 मान्यः सङ्ग्रहपथः न ।',
'backend-fail-delete'        => '$1 सञ्चिकां परिमर्जितुं नैव शक्यते ।',
'backend-fail-alreadyexists' => '$1 इति सञ्चिक पूर्वमेव वर्तते ।',
'backend-fail-store'         => '$1 सञ्चिकां $2 मध्ये सङ्ग्रहितुं नैव शक्यते ।',
'backend-fail-copy'          => '$1 सञ्चिकां $2 मध्ये प्रतिकृतिः कर्तुं नैव शक्यते ।',
'backend-fail-move'          => '$1 सञ्चिकां $2 प्रति चालयितुं न शक्यते ।',
'backend-fail-opentemp'      => 'तात्कालिकसञ्चिकाः उद्घाटयितुं नैव शक्यते ।',
'backend-fail-writetemp'     => 'तात्कालिकसञ्चिकायां लेखितुं न शक्यते ।',
'backend-fail-closetemp'     => 'तात्कालिकसञ्चिकां पिधातुं नैव शक्यते ।',
'backend-fail-read'          => '$1 इति सञ्चिकां पठितुं नैव शक्यते ।',
'backend-fail-create'        => '$1 इति सञ्चिकां लेखितुं नैव शक्यते ।',
'backend-fail-readonly'      => '"$1" सङ्ग्रहागारान्तः तु सद्यः केवलं पठनार्हः  कारणं दत्तं तु  "\'\'$2\'\'" ।',
'backend-fail-synced'        => '"$1" सञ्चिका आन्तरिकसङ्ग्रहागारन्ते  उपयोगायोग्यस्थितौ न अस्ति ।',
'backend-fail-connect'       => '"$1" सङ्ग्राहागारन्ते सम्पर्कयितुं नैव शक्यते ।',
'backend-fail-internal'      => '"$1"सङ्ग्रहागारन्ते अज्ञातदोषः उपगतः ।',
'backend-fail-contenttype'   => '"$1"मध्ये सङ्ग्रहितुं सञ्चिकायाः प्रकारं निश्चिनोतुं नैव शक्यते ।',
'backend-fail-batchsize'     => '$1 संचिकायाः गणस्य निक्षेपावकाशः प्रदत्तः । {{PLURAL:$1|operation|operations}}; समयनिर्बन्धः $2 {{PLURAL:$2|operation|operations}}.',

# Lock manager
'lockmanager-notlocked'        => '"$1" इत्येतत् उद्घाटयितुं न शक्यते यतः एतत् कीलितं न ।',
'lockmanager-fail-closelock'   => '"$1" निमित्तं सञ्चिकाम् उद्घाटयितुं न शक्यते ।',
'lockmanager-fail-deletelock'  => '"$1"कृते कपाटितसञ्चिकाम् अपमर्जितुं न शक्यते ।',
'lockmanager-fail-acquirelock' => '"$1"कपाटितुं न शक्यते ।',
'lockmanager-fail-openlock'    => '"$1" निमित्तं सञ्चिकाम् उद्घाटयितुं न शक्यते ।',
'lockmanager-fail-releaselock' => '"$1"कपाटितुं न शक्यते ।',
'lockmanager-fail-db-bucket'   => ' $1 द्रेणपात्रे कपाटनीयमूलपाठाः अपर्याप्ताः सन्ति ।',
'lockmanager-fail-db-release'  => '$1 मूलपाठेषु कपाटिकाविमोचनं नैव शक्यते ।',
'lockmanager-fail-svr-release' => '$1 मूलपाठेषु कपाटिकाविमोचनं नैव शक्यते ।',

# ZipDirectoryReader
'zip-file-open-error' => 'ZIP परिशीलनार्थम् उद्घाटनावसरे कश्चन दोषः सङ्गतः ।',
'zip-wrong-format'    => 'निश्चितसञ्चिका तु सञ्चिका ZIP नैव ।',
'zip-bad'             => 'ZIP सञ्चिका तु दूषिता अथवा अपठनीया अस्ति । 
सुरक्षार्थं परिशीलयितुं  न शक्यते ।',
'zip-unsupported'     => 'एषा सञिका तु मीडियाविकिना अननुमोदिता ZIP सञ्चिका अस्ति ।
सुरक्षर्थं सम्यक् परिशील्या न भवति ।',

# Special:UploadStash
'uploadstash'          => 'राशीः उत्तारयतु ।',
'uploadstash-summary'  => 'एतत्पुटम् उत्तारितसञ्चिकानां सम्पर्कं साधयति । विक्याम् एतानि प्रकाशितानि न । योजकः उत्तारितवानपि एताः सञ्चिकाः अदृश्याः सन्ति ।',
'uploadstash-clear'    => 'राशीकृतसञ्चिकाः विशदयतु ।',
'uploadstash-nofiles'  => 'भवान् सञ्चिकाः न राशीकृतवान् ।',
'uploadstash-badtoken' => 'प्रक्रियाचरणं सफलम् । किन्तु प्रायः ते सम्पादनाधिकारः विनष्टः । पुनः यतताम् ।',
'uploadstash-errclear' => 'सञ्चिकविशदनं सफलम् ।',
'uploadstash-refresh'  => 'सञ्चिकावलीं संस्करोतु ।',
'invalid-chunk-offset' => 'अमान्यं चङ्क् आफ्सेट्',

# img_auth script messages
'img-auth-accessdenied'     => 'अभिगमनम् अपलपितम् ।',
'img-auth-nopathinfo'       => 'पथसूची विनष्टा ।
ते वितारकः सूचनाः प्रेषयितुं संसिद्धः न ।
एतत् CGI अवलम्बितं स्यात् अपि च img_auth अनुमोदनं न करोति ।
See https://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'         => 'सुदृढितायाम् उत्तारणनिदेशिकायाम् अभ्यर्थितपथः नास्ति ।',
'img-auth-badtitle'         => '"$1"तः मान्यशीर्षिकां निर्मातुं न शक्यते ।',
'img-auth-nologinnWL'       => 'नामाभिलेखेन न प्रविष्टः अपिच $1 तु श्वेतावली न ।',
'img-auth-nofile'           => '"$1" इति सञ्चिका न वर्तते ।',
'img-auth-isdir'            => 'भवान् "$1"निदेशिकाम् अभिगन्तुं यतते ।
सञ्चिकाभिगमनम् एव अनुमतम् ।',
'img-auth-streaming'        => '"$1"इत्यस्य प्रवाहिनी ।',
'img-auth-public'           => 'स्वायत्तविकितः सञ्चिकाः नेतुम् अयं कार्यक्रमः img_auth.php उपयुज्यते ।
एषा विकिः सार्वजनिकविकिः इति  दृढिता । 
वैकल्पिकसुरक्षार्थं img_auth.php अपलपितः । ।',
'img-auth-noread'           => '"$1"पठने योजकस्य अभिगमनं नास्ति ।',
'img-auth-bad-query-string' => ' URL मध्ये अमान्यं प्रश्नतन्तुः अस्ति ।',

# HTTP errors
'http-invalid-url'      => ' $1 इति अमान्यम् URL ।',
'http-invalid-scheme'   => '"$1"योजनायुक्तं URLs नानुमोदितानि ।',
'http-request-error'    => ' अज्ञातदोषात् HTTP अभ्यर्थनं निष्पलम् ।',
'http-read-error'       => 'HTTP पठनदोषः।',
'http-timed-out'        => 'HTTP अभ्यर्थनं कालातीतम् ।',
'http-curl-error'       => 'दोषाहरणस्य URL: $1',
'http-host-unreachable' => 'URL प्राप्तुं न शक्यते ।',
'http-bad-status'       => 'HTTP : $1 $2अभ्यर्थने समस्या आसीत् ।',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL प्राप्तुं न शक्यते ।',
'upload-curl-error6-text'  => 'उपपन्नं URL न प्राप्नोति ।
द्विटङ्कनेन URLअदोषत्वं क्षेत्रं च परिशीलयतु ।',
'upload-curl-error28'      => 'उत्तारणस्य समयातीतः ।',
'upload-curl-error28-text' => 'जालक्षेत्रेण प्रतिस्पन्दितुं दीर्घकालः आश्रितः । 
जालक्षेत्रस्य जीवितं परिशीलयतु । अथवा कञ्चित्कालान्तरेण प्रयतताम् । 
भवान् न्यूनकार्यव्यस्तकाले प्रयत्नं करोतु ।',

'license'            => 'अनुमतिदानम्',
'license-header'     => 'अनुमतिदानम्',
'nolicense'          => 'चियनं नास्ति ।',
'license-nopreview'  => 'पूर्वावलोकनं न मिलति ।',
'upload_source_url'  => '(मान्यं, प्रचारात्मकाभिगमनयुतं URL)',
'upload_source_file' => ' (ते सङ्गणकस्य सञ्चिका)',

# Special:ListFiles
'listfiles-summary'     => 'एतद्विशेषपुटम् उत्तारितसञ्चिकाः प्रदर्शयति । 
योजकेन शुद्धाः अतिनूतनं सञ्चिकाः केवलम् अत्र प्रदर्शयति ।',
'listfiles_search_for'  => 'माध्यमनामधेयार्थम् अन्विषतु ।',
'imgfile'               => 'संचिका',
'listfiles'             => 'सञ्चिकावली ।',
'listfiles_thumb'       => 'अंगुष्ठनखाकारम् ।',
'listfiles_date'        => 'दिनाङ्क',
'listfiles_name'        => 'नामन्',
'listfiles_user'        => 'योजक',
'listfiles_size'        => 'आकार',
'listfiles_description' => 'वर्णन',
'listfiles_count'       => 'आवृत्ति',

# File description page
'file-anchor-link'          => 'सञ्चिका',
'filehist'                  => 'सञ्चिकायाः इतिहासः',
'filehist-help'             => 'सञ्चिका तत्समये कीदृशी आसीदिति द्रष्टुं दिनांकः/समयः नुद्यताम् ।',
'filehist-deleteall'        => 'सर्वान् परिमर्जतु ।',
'filehist-deleteone'        => 'विलोप',
'filehist-revert'           => 'प्रतिनिवर्त्यताम्',
'filehist-current'          => 'सद्योजातम्',
'filehist-datetime'         => 'दिनाङ्कः/समयः',
'filehist-thumb'            => 'अंगुष्ठनखाकारम्',
'filehist-thumbtext'        => '$1 समये विद्यमानायाः आवृत्तेः अंगुष्ठनखाकारम्',
'filehist-nothumb'          => 'अङ्गुष्टनखाकारकं नाश्ति ।',
'filehist-user'             => 'योजकः',
'filehist-dimensions'       => 'आयामाः',
'filehist-filesize'         => 'सञ्चिकाकारः ।',
'filehist-comment'          => 'टिप्पणी',
'filehist-missing'          => 'सञ्चिका विनष्टा ।',
'imagelinks'                => 'संचिका यत्र उपयुक्ता',
'linkstoimage'              => '{{PLURAL:$1|अधोलिखितं पृष्ठं| अधोलिखितानि $1 पृष्ठाणि}} इदं संचिकां प्रति संबंधनं {{PLURAL:$1|करोति| कुर्वन्ति}}।',
'linkstoimage-more'         => '{{PLURAL:$1|$1}} तः अधिकपुटानि अस्यां सञ्चिकायां योज्यन्ते । 
अधोनिदेशितसूची सञ्चिकाभिः योजनीयपुटानि पश्यति ।{{PLURAL:$1|$1 पृष्ठ|$1 पृष्ठ}} 
[[Special:WhatLinksHere/$2|पूर्णसूची]] अपि लभ्यते ।',
'nolinkstoimage'            => 'एतद चित्रात् न पृष्ठा सम्बद्धं करोन्ति।',
'morelinkstoimage'          => ' [[Special:WhatLinksHere/$1|more links]] मध्ये सञ्चिकामवलोकयतु ।',
'linkstoimage-redirect'     => '$1 (सञ्चिका पुनर्निदेशिता) $2',
'duplicatesoffile'          => 'अधो निदेशितसञ्चिका द्विप्रतिः । {{PLURAL:$1|}} विशेषविवरणार्थम् अत्र प्रविशतु । [[Special:FileDuplicateSearch/$2|more details]]',
'sharedupload'              => 'इयं संचिका $1 इत्यस्मादस्ति, एषा खलु अन्येष्वपि प्रकल्पेषु प्रयोक्तुं शक्यते।',
'sharedupload-desc-there'   => 'एषा सञ्चिका $1 तथा अन्यप्रकल्पेन च उपयुक्ता ।
इत्योप्यतिशयसूचनार्थं $2 सञ्चिकाविवरणपुटं पश्यतु ।',
'sharedupload-desc-here'    => 'एषा सञ्चिका $1 इत्यतः उद्धृता अन्यासु योजनासु उपयोगार्हा ।
अस्याः सञ्चिकायाः  [$2 सञ्चिकाविवरणपृष्ठम्] इत्यत्र उपलभ्यमानं विवरणम् अधोलिखितं यथा ।',
'filepage-nofile'           => 'अनेन नाम्ना कापि सञ्चिका न वर्तते ।',
'filepage-nofile-link'      => 'अनेन नाम्ना कापि सञ्चिका न वर्तते । $1 इत्येतत् उत्तारयितुं शक्नोति ।',
'uploadnewversion-linktext' => 'अस्य पृष्ठस्य नूतनाम् आवृत्तिं उद्भारयतु',
'shared-repo-from'          => '$1 इत्यस्मात् ।',
'shared-repo'               => 'विभक्तः कोशः ।',

# File reversion
'filerevert'                => '$1 अनुवर्तताम् ।',
'filerevert-legend'         => 'सञ्चिकाम् अनुवर्तताम् ।',
'filerevert-intro'          => "भवान् '''[[Media:$1|$1]]''' इति सञ्चिकायाः  $4 इत्यवतरणं $3, $2 इति अनुवर्तमानः अस्ति ।",
'filerevert-comment'        => 'कारणम् :',
'filerevert-defaultcomment' => '$2 इत्येनं $1 समयस्य अवतरणम् अनुवृत्तम् ।',
'filerevert-submit'         => 'अनुवर्तताम् ।',
'filerevert-success'        => "'''[[Media:$1|$1]]''' इत्येनं $4 $2 को $3 समयावतरणम् अनुवृत्तम् ।",
'filerevert-badversion'     => 'दत्तसमये सन्देशदायिका सञ्चिका प्राचीनावतरणं नास्ति ।',

# File deletion
'filedelete'                   => '$1 इत्येतत् अपमर्जतु ।',
'filedelete-legend'            => 'सञ्चिकाम् अपमर्जतु ।',
'filedelete-intro'             => "'''[[Media:$1|$1]]''' इति सञ्चिकायाः इतिहाससहितम् अपमर्जयन् अस्ति ।",
'filedelete-intro-old'         => "भवान्'''[[Media:$1|$1]]''' इत्यस्य [$4 $2 इत्येतयोः $3 कालस्य अवतरणम्] अपमार्जयन् अस्ति ।",
'filedelete-comment'           => 'कारणम् :',
'filedelete-submit'            => 'विलुप्यताम्',
'filedelete-success'           => "'''$1''' अपमर्जितम् ।",
'filedelete-success-old'       => "'''[[Media:$1|$1]]''' इत्यस्य $2 इत्येतत् $3 समयस्यावतरणम् अपमर्जितम् ।",
'filedelete-nofile'            => "'''$1''' न वर्तते ।",
'filedelete-nofile-old'        => "'''$1''' इत्यस्य भवता वर्णितविशेषतायुतम् अवतरणम् अत्र न वर्तते ।",
'filedelete-otherreason'       => 'अपरम्/अतिरिक्तं कारणम् :',
'filedelete-reason-otherlist'  => 'अन्य कारणम्',
'filedelete-reason-dropdown'   => '* अपमर्जनस्य सामान्यं कारणम् । 
** कृतिस्वाम्यस्य उल्लङ्घनम् । 
** प्रतिकृता सञ्चिका ।',
'filedelete-edit-reasonlist'   => 'अपमार्जनकारणानि सम्पादयतु ।',
'filedelete-maintenance'       => 'सञ्चिकानाम् अपमर्जनम् अनमपमर्जनं च निर्वहणकाले तात्कालिकतया निष्क्रियौ ।',
'filedelete-maintenance-title' => 'सञ्चिकाम् अपमर्जितुं न शक्यते ।',

# MIME search
'mimesearch'         => 'MIME अन्वेषणम् ।',
'mimesearch-summary' => 'MIME-प्रकारानुसारं सञ्चिकान्वेषणार्थम् एतत्पुटम् उपयोक्तुं शाक्नोति । 
इनपुट: सञ्चिकायाः प्रकारः/उपप्रकारः, उदाहरणम्. <code>image/jpeg</code>.',
'mimetype'           => 'MIME प्रकारः :',
'download'           => 'डाउनलोड',

# Unwatched pages
'unwatchedpages' => 'अनवलोकितपुटानि ।',

# List redirects
'listredirects' => 'चालितानाम् अवली ।',

# Unused templates
'unusedtemplates'     => 'अनुपयुक्ताः प्राकृतयः ।',
'unusedtemplatestext' => 'अस्मिन् पुटे {{ns:template}} नामस्थानयुतानि सर्वपुटानि अन्तर्गतानि । यानि अन्यपुटेषु न सन्ति । 
अस्य अपमर्जनात् पूर्वं सञ्चिकायाः अन्यानुबन्धान् परिशीलयतु ।',
'unusedtemplateswlh'  => 'अन्यानुबन्धाः ।',

# Random page
'randompage'         => 'यादृच्छिकपृष्ठम्',
'randompage-nopages' => 'अधोनिदेशितनामस्थाने पुटानि न सन्ति । {{PLURAL:$2| एतन्नमस्थाने}} नास्ति : $1।',

# Random redirect
'randomredirect'         => 'यादृच्छिकचालनम् ।',
'randomredirect-nopages' => '$1नामस्थाने चालनानि न सन्ति ।',

# Statistics
'statistics'                   => 'स्थितिगणितम्',
'statistics-header-pages'      => 'पुटसाङ्ख्यिकाः ।',
'statistics-header-edits'      => 'सङ्ख्यिकाः सम्पादयतु ।',
'statistics-header-views'      => 'साङ्ख्यिकाः अवलोकयतु ।',
'statistics-header-users'      => 'योजकसाङ्ख्यिकाः ।',
'statistics-header-hooks'      => 'अन्यसाङ्ख्यिकाः ।',
'statistics-articles'          => 'आधेयपुटानि ।',
'statistics-pages'             => 'पृष्ठानि',
'statistics-pages-desc'        => 'अस्यां विक्यां तु सम्भाषाणपुटसहितानि अन्यसर्वपुटानि चालितानि ।',
'statistics-files'             => 'उद्भारितसञ्चिकाः',
'statistics-edits'             => '{{SITENAME}} व्यवस्थापनपर्यन्तं पुटसम्पादनानि ।',
'statistics-edits-average'     => 'प्रतिपुटं माध्यसम्पादनानि ।',
'statistics-views-total'       => 'अवलोकनयोगः ।',
'statistics-views-total-desc'  => 'असंवृत्तपुटानाम् अवलोकनानि । अपि च विशेषपुटानि नान्तर्गतानि ।',
'statistics-views-peredit'     => 'प्रतिसम्पादनम् अवलोकनम् ।',
'statistics-users'             => 'पञ्जीकृतः [[Special:ListUsers|योजकः]]',
'statistics-users-active'      => 'सक्रियाः सदस्याः',
'statistics-users-active-desc' => 'गतेषु {{PLURAL:$1|day|$1 दिनेषु}} सक्रियाः योजकाः  ।',
'statistics-mostpopular'       => 'अत्यवलोकितपुटानि ।',

'disambiguations'      => 'द्वैधीभावरहितपुटानाम् अनुबन्धितपुटानि ।',
'disambiguationspage'  => 'Template:असन्दिग्धम्',
'disambiguations-text' => 'अधो निदेशितपुटानि असन्धिग्धपुटेन अनुबन्धितानि । 
एतानि यथार्थविषैः योजनीयानि । <br />
यदि कोऽपि पुटेन प्रकृतिं प्रयोजयति यः  [[MediaWiki:Disambiguationspage]] इत्यनेन अनुबद्धः  ससन्दिग्धपुटम् इति उच्यते ।',

'doubleredirects'                   => 'दुगुनी-अनुप्रेषिते',
'doubleredirectstext'               => 'एतत्पुटं तेषां पुटानां सूची अस्ति यानि अन्यपुनर्निदेशितपुटानि प्रति पुनरिदेशितानि सन्ति । 
प्रत्येकं पङ्क्तिः प्रथमद्वितीयपुनर्निदेशम् अन्तर्गता । द्वितीयपुनर्निदेशः लक्ष्यं यत् वास्तवं लक्ष्यपुटं प्रथमं प्रदर्शितम् । 
अपि च प्रथमपुनर्निदेशः वास्तवेन एतदेवलक्षितं स्यात् । <del>काटी गई</del> प्रविष्टयः परिहृताः ।',
'double-redirect-fixed-move'        => '[[$1]] इत्यस्य स्थानं परिवर्तितम् । 
इदानीम् [[$2]] इत्यस्य दिशि पुनर्निदिष्टम् अस्ति ।',
'double-redirect-fixed-maintenance' => '[[$1]] तः [[$2]] पुनर्निदेशद्वयं निश्चिनोति ।',
'double-redirect-fixer'             => 'पुनर्निदेशस्य बन्धकः ।',

'brokenredirects'        => 'भग्नपुनर्निदेशाः ।',
'brokenredirectstext'    => 'अधो दत्तपुनर्निदेशाः अवृत्तपुटैः सह अनुबन्दं रक्षन्ति ।',
'brokenredirects-edit'   => 'सम्पाद्यताम्',
'brokenredirects-delete' => 'विलुप्यताम्',

'withoutinterwiki'         => 'भाषानुबन्धरिहातानि पुटानि ।',
'withoutinterwiki-summary' => 'अधस्थपुटानि अन्यभाषावतरणैः अनुबन्धं न कुर्वन्ति ।',
'withoutinterwiki-legend'  => 'पूर्वोऽपपदम्',
'withoutinterwiki-submit'  => 'दर्श्यताम्',

'fewestrevisions' => 'न्यूनतमालोकनयुक्तपुटानि ।',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|बैट्|बैट्स्}}',
'ncategories'             => '{{PLURAL:$1|वर्गः|वर्गाः }}',
'nlinks'                  => '$1 {{PLURAL:$1|अनुबन्धः|अनुबन्धाः}}',
'nmembers'                => '$1 {{PLURAL:$1|सदस्यः|सदस्याः}}',
'nrevisions'              => '$1 {{PLURAL:$1|पुनरावृत्तिः}}',
'nviews'                  => '$1 {{PLURAL:$1|अनुबन्धः|अनुबन्धाः}}',
'nimagelinks'             => '$1 {{PLURAL:$1|पुटम्|पुटानि}} प्रयुक्तानि ।',
'ntransclusions'          => '$1 {{PLURAL:$1|पुटम्|पुटानि}} प्रयुक्तानि ।',
'specialpage-empty'       => 'अस्य वृत्तस्य परिणामः नास्ति ।',
'lonelypages'             => 'अनाथपुटानि ।',
'lonelypagestext'         => '{{SITENAME}} इत्यस्मिन् अधो निदेशितपुटानि नानुबद्धानि अथवा अन्तर्गतानि अन्यपुटेषु ।',
'uncategorizedpages'      => 'अवर्गीकृतपुटानि ।',
'uncategorizedcategories' => 'अवर्गीकृताः वर्गाः ।',
'uncategorizedimages'     => 'अवर्गीकृताः सञ्चिकाः ।',
'uncategorizedtemplates'  => 'अवर्गीकृताः प्रकृतयः ।',
'unusedcategories'        => 'अनुपयुक्ताः वर्गाः ।',
'unusedimages'            => 'अनुपयुक्तानि पुटाणी ।',
'popularpages'            => 'प्रसिद्धानि पुटानि ।',
'wantedcategories'        => 'आवश्यकाः वर्गाः ।',
'wantedpages'             => 'आवश्यकपुटानि ।',
'wantedpages-badtitle'    => '$1 परिणामनिरूपणे अमान्यशीर्षकम् ।',
'wantedfiles'             => 'आवश्यकाः सञ्चिकाः ।',
'wantedfiletext-cat'      => 'अधो दत्तसञ्चिकाः उपयुक्ताः किन्तु न वर्तन्ते । बाह्यकोशानां सञ्चिकाः उपस्थिताः इति एताः सूच्यां स्युः । एतादृशः कोपि सदोषप्रवेशः<del> अवरुद्धः</del> भवति । अपि च यत्पुटं तादृश्याः अनुपस्थितसञ्चिकायाः प्रयोगं कुर्वन्ति तासं सूची  [[:$1]] मध्ये अस्ति ।',
'wantedfiletext-nocat'    => 'अधो दत्ताः सञ्चिकाः उपयुक्ताः किन्तु न वर्तन्ते । बाह्यकोशस्य सञ्चिकाः उपस्थिताः इति एताः सूच्यां स्युः । तदृशः कोऽपि सदोषप्रवेशः<del>struck out</del>. अत्र स्यात् ।',
'wantedtemplates'         => 'आवश्यकाः प्राकृतयः ।',
'mostlinked'              => 'अत्यनुबद्धानि पुटानि ।',
'mostlinkedcategories'    => 'वर्गैः सह अत्यनुबद्धाः ।',
'mostlinkedtemplates'     => 'प्राकृतिभिः अत्यनुबद्धाः ।',
'mostcategories'          => 'बहुवर्गयुक्तपुटानि ।',
'mostimages'              => 'अत्यनुबद्धानि पुटानि ।',
'mostrevisions'           => 'सर्वाधिकपुनरावृत्तियुक्तानि पुटानि ।',
'prefixindex'             => 'उपसर्गयुक्तानि सर्वाणि पृष्ठानि',
'prefixindex-namespace'   => 'उपसर्गैः युक्तानि सर्वपुटानि । ($1 नामस्थानम्)',
'shortpages'              => 'ह्रस्वपुटानि',
'longpages'               => 'दीर्घाणि पृष्ठानि',
'deadendpages'            => 'अन्तिमपुटानि ।',
'deadendpagestext'        => 'अधो निदेशितपुटानि {{SITENAME}} इत्यस्मिन्  अन्यपुटैः अनुबद्धानि न ।',
'protectedpages'          => 'सुरक्षितानि पुतानि ।',
'protectedpages-indef'    => 'अनिर्दिष्टसुरक्षा केवलम् ।',
'protectedpages-cascade'  => 'प्रपातसंरक्षणं केवलम् ।',
'protectedpagestext'      => 'अधोसूचितपुटानि चालनात् सम्पादनात् वा सुरक्षितानि ।',
'protectedpagesempty'     => 'अनेन विस्तारेण न किमपि पुटं सद्यः न सुरक्षितम् ।',
'protectedtitles'         => 'सुरक्षितानि शीर्षकानि ।',
'protectedtitlestext'     => 'अधो दत्तशीर्षकाणि सर्जनात् रक्षितानि ।',
'protectedtitlesempty'    => 'एतैः विस्तारैः न किमपि शीर्षकं सद्यः परिरक्षितानि ।',
'listusers'               => 'योजक सूचि',
'listusers-editsonly'     => 'केवलं सम्पादनसहितयोजकान् दर्शयतु ।',
'listusers-creationsort'  => 'सर्जनदिनाङ्कैः वर्गीकरोतु ।',
'usereditcount'           => '$1 {{PLURAL:$1|दिनम्|दिनानि}}',
'usercreated'             => '$1 दिने $2 समये रचितम् योजकनाम $3',
'newpages'                => 'नवीनपृष्ठम्',
'newpages-username'       => 'योजकनामन्:',
'ancientpages'            => 'प्राचीनतमानि पृष्ठानि',
'move'                    => 'चाल्यताम्',
'movethispage'            => 'इदं पृष्ठं चाल्यताम्',
'unusedimagestext'        => 'अधो दत्तसञ्चिकाः सन्ति किन्तु कस्मिंश्चिदपि पुटे न न्यस्ताः ।',
'unusedcategoriestext'    => 'निम्नलिखितवर्गाः सन्ति तथापि अन्यपुटं वर्गः वा न उपयुङ्क्ते ।',
'notargettitle'           => 'लक्ष्यं नास्ति ।',
'notargettext'            => 'एतत्कार्यं समाचरितुं भवान् लक्षितपुटं योजकं वा न निर्दिष्टवान् ।',
'nopagetitle'             => 'तादृशलक्षितपुटं नास्ति ।',
'nopagetext'              => 'भवता निर्दिष्टं लक्षितपुटं नास्ति ।',
'pager-newer-n'           => '{{PLURAL:$1|नूतनतरम् 1|नूतनतराणि $1}}',
'pager-older-n'           => '{{PLURAL:$1|पुरातनतरम् 1|पुरातनतराणि $1}}',
'suppress'                => 'अलक्ष्यम् ।',
'querypage-disabled'      => 'समाचरणकारणेन एतद्विशेषपुटं निष्क्रियम् ।',

# Book sources
'booksources'               => 'ग्रन्थानां स्रोतः',
'booksources-search-legend' => 'ग्रन्थस्रोतः अन्विष्यताम्',
'booksources-go'            => 'गम्यताम्',
'booksources-text'          => 'अधस्था आवली नूतनप्राचीनपुस्तकानां विक्रयकेन्द्रस्य अनुबन्धान् सूचयति । यत्र ते  आवश्यकाः अन्यविषयाः अपि उपलभ्याः ।',
'booksources-invalid-isbn'  => 'दत्तं ISBN मान्यम् इति  न भाति । मूलस्रोततः प्रतिकृतीः कर्तुं परिशीलयतु ।',

# Special:Log
'specialloguserlabel'  => 'आचारी :',
'speciallogtitlelabel' => 'लक्ष्यम् (शीर्षकम् / योजकः)',
'log'                  => 'लॉग् इत्येतानि',
'all-logs-page'        => 'सर्वसार्वजनिकप्रवेशः ।',
'alllogstext'          => '{{SITENAME}}इत्यस्य उबलब्धप्रवेशानां  संयुक्तप्रदर्शनम् ।
प्रवेशप्रकारं चित्वा भवान् दृश्यं क्षाययितुं शक्नोति । योजकनाम,  सदस्य नाम (ह्रस्वदीर्घाक्षरसंवादी) प्रभावितपुटम् ।',
'logempty'             => 'प्रवेशे मेलयुक्तपुटं नास्ति ।',
'log-title-wildcard'   => 'अनेन पाठेन आरब्धानि शीर्षकानि अन्विषतु ।',

# Special:AllPages
'allpages'          => 'सर्वाणि पृष्ठानि',
'alphaindexline'    => '$1 तः $2 पर्यन्तम्',
'nextpage'          => '($1)अग्रिमपुटम् ।',
'prevpage'          => 'पूर्वपृष्ठम् ($1)',
'allpagesfrom'      => 'इत्यस्मात् आरभ्यमाणानि पृष्ठानि दर्श्यन्ताम्:',
'allpagesto'        => 'तानि पृष्ठानि दर्श्यन्तां येषाम् अन्त्यम् एवम् :',
'allarticles'       => 'सर्वाणि पृष्ठानि',
'allinnamespace'    => 'सर्वपुटानि ($1 नामस्थानम्)',
'allnotinnamespace' => 'सर्वपुटानि ($1 नामस्थानं विना)',
'allpagesprev'      => 'पूर्वतन',
'allpagesnext'      => 'अग्रिम',
'allpagessubmit'    => 'गम्यताम्',
'allpagesprefix'    => 'उपसर्गयुक्तपुटानि दर्शयतु ।',
'allpagesbadtitle'  => 'दत्तपुटशीर्षकम् अमान्यम् अथवा आन्तर्भाषिकम्, आन्तर्विकीयं वा अस्ति । 
अस्मिन् एकं नैकं वा अक्षराणि सन्ति येषां प्रयोगं शीर्षकेषु कर्तुम् अशक्यम् ।',
'allpages-bad-ns'   => '{{SITENAME}} इत्यस्मिन् "$1" नामस्थानं नास्ति ।',

# Special:Categories
'categories'                    => 'वर्गाः',
'categoriespagetext'            => 'निम्नोक्ताः {{PLURAL:$1|श्रेणी|श्रेणयः}} पुटानि माध्यमान् वा युक्ताः ।
यस्याः श्रेण्याः [[Special:UnusedCategories|अप्रयुक्तश्रेण्यः]] अत्र न सन्ति ।
[[Special:WantedCategories|अपेक्षितश्रेण्यः]] अपि पश्यतु ।',
'categoriesfrom'                => 'इत्यस्मात् आरभ्यमाणानि पृष्ठानि दर्श्यन्ताम्:',
'special-categories-sort-count' => 'गणनानुगुणं वर्गीकरोतु ।',
'special-categories-sort-abc'   => 'अकारदिक्रमेण वर्गीकरोतु ।',

# Special:DeletedContributions
'deletedcontributions'             => 'अपमर्जितानि योजकयोगदानानि ।',
'deletedcontributions-title'       => 'अपमर्जितानि योजकयोगदानानि ।',
'sp-deletedcontributions-contribs' => 'योगदानानि ।',

# Special:LinkSearch
'linksearch'       => 'बाह्यसम्पर्कतन्तूनाम् अन्वेषणम्',
'linksearch-pat'   => 'अन्वेषणस्य क्रमः ।',
'linksearch-ns'    => 'नामस्थानम् :',
'linksearch-ok'    => 'अन्वेषणम्',
'linksearch-text'  => '"*.wikipedia.org" सदृशानि वन्यपत्राणि योजयितुं शक्यते । 
न्यूनातिन्यूनं ".org" सदृशः अत्युन्नतस्तरस्य डोमेन आवश्यकम् अस्ति <br />
अनुमोदितक्रमागतिः  <code>$1</code> (एतेषु कतममपि अन्वेषणे न योजयतु )',
'linksearch-line'  => '$2 पृष्ठं $1 तः सम्पृक्तम् अस्ति।',
'linksearch-error' => 'वन्यपत्राणि आतिथेयस्य नाम्ना समं केवलं प्रभान्ति ।',

# Special:ListUsers
'listusersfrom'      => 'एतस्मात् आरभमाणान् योजकान् दर्शयतु ।',
'listusers-submit'   => 'दर्श्यताम्',
'listusers-noresult' => 'योजकः न प्राप्तः ।',
'listusers-blocked'  => 'अवरुद्धम् ।',

# Special:ActiveUsers
'activeusers'            => 'सक्रिययोजकानाम् आवली ।',
'activeusers-intro'      => 'एषा तु गतेषु $1 {{PLURAL:$1|दिनेषु}} कृतकार्याणां योजकाना आवली ।',
'activeusers-count'      => '$1 {{PLURAL:$1|सम्पादनानि}} गतेषु $3 {{PLURAL:$3|दिनेषु}} कृतानि  ।',
'activeusers-from'       => 'एतस्मात् आरभमाणान् योजकान् दर्शयतु ।',
'activeusers-hidebots'   => 'स्वयं चालकान् गोपयतु ।',
'activeusers-hidesysops' => 'प्रशासकान् गोपयतु ।',
'activeusers-noresult'   => 'योजकः न प्राप्तः ।',

# Special:Log/newusers
'newuserlogpage'     => 'प्रयोक्तृ-सृजन-सूचिका',
'newuserlogpagetext' => 'अयं योजकनिर्माणास्य प्रवेशः ।',

# Special:ListGroupRights
'listgrouprights'                      => 'योजकसमूहाधिकाराः ।',
'listgrouprights-summary'              => 'अधोदत्ता विकिपरिभाषितस्य सङ्गताभिगम्यताधिकारैः सहिता योजकसमूहस्य आवली । [[{{MediaWiki:Listgrouprights-helppage}}|additional information]]',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">दत्ताधिकाराः</span>
* <span class="listgrouprights-revoked">हृताधिकाराः</span>',
'listgrouprights-group'                => 'वर्ग',
'listgrouprights-rights'               => 'अधिकाराः ।',
'listgrouprights-helppage'             => 'Help: समूहाधिकाराः ।',
'listgrouprights-members'              => '(सदस्यानां सूची)',
'listgrouprights-addgroup'             => '{{PLURAL:$2|समूहः}} योज्यताम् $1',
'listgrouprights-removegroup'          => 'समूहः{{PLURAL:$2|विलोपयतु}}: $1',
'listgrouprights-addgroup-all'         => 'सर्वसमूहान् योजयतु ।',
'listgrouprights-removegroup-all'      => 'सर्वसमूहान् अपनयतु ।',
'listgrouprights-addgroup-self'        => 'स्वस्थाने {{PLURAL:$2|समूहम्}} योजयतु $1',
'listgrouprights-removegroup-self'     => 'स्वस्थाने {{PLURAL:$2|समूहम्}} अपनयतु  $1',
'listgrouprights-addgroup-self-all'    => 'स्वस्थाने सर्वसमूहान योजयतु ।',
'listgrouprights-removegroup-self-all' => 'स्वस्थानात् सर्वसमूहान् अपनयतु ।',

# E-mail user
'mailnologin'          => 'सम्प्रेषणस्य सङ्केतः नास्ति ।',
'mailnologintext'      => 'अस्य योजकेभ्यः विद्युन्मानपत्रप्रेषणार्थम् [[Special:UserLogin|नामाभिलेखनम्]] आवश्यकम् [[Special:Preferences|आद्यता]]यां प्रेषयितुं विद्युन्मानपत्रसङ्केतः आवश्यकः ।',
'emailuser'            => 'एतस्मै योजकाय ईपत्रं प्रेष्यताम्',
'emailpage'            => 'ई-मेल योजक',
'emailpagetext'        => 'अस्मै योजकाय विद्युन्मानपत्रं प्रेषयितुम् अधो दत्तप्रपत्रम् उपयोक्तुं शक्नोति । 
[[Special:Preferences|your user preferences]] अत्र भवता विनिवेशितः वि-पत्रसङ्केतः सकाशात् इति स्थाने प्रतिभाति । अनेन स्वीकर्ता साक्षात् प्रत्युत्तरं दातुं प्रभविष्यति ।',
'usermailererror'      => 'पत्राचारपदार्थस्य प्रत्यागतदोषः ।',
'defemailsubject'      => '{{SITENAME}}"$1" इति योजकात् विद्युन्मानपत्रम् ।',
'usermaildisabled'     => 'योजकस्य विद्युन्मानपत्रं निष्क्रियम् ।',
'usermaildisabledtext' => 'अस्यां विक्याम् अन्ययोजकेभ्यः विद्युन्मानपत्रं प्रेषयितुं नै शक्नोति ।',
'noemailtitle'         => 'विद्युन्मानपत्रसङ्केतः नास्ति ।',
'noemailtext'          => 'अस्य योजकस्य निरिदिष्टः विद्युन्मानपत्रसङ्केतः नास्ति ।',
'nowikiemailtitle'     => 'विद्युन्मानपत्रम् अननुमतम् ।',
'nowikiemailtext'      => 'अयं योजकः अन्ययोजकेभ्यः विद्युन्मानपत्राणि स्वीकार्तुं नेच्छति ।',
'emailnotarget'        => 'स्वीकर्तुः अस्तित्वविहीनम् अथवा अमान्यं योजकनाम  ।',
'emailtarget'          => 'स्वीकर्तुः योजकनाम लिखतु ।',
'emailusername'        => 'योजकनामन्:',
'emailusernamesubmit'  => 'उपस्थाप्यताम्',
'email-legend'         => '{{SITENAME}}  इति अन्ययोजकाय विद्युन्मानपत्रं प्रेषयतु ।',
'emailfrom'            => 'सकाशात्',
'emailto'              => 'सविधे:',
'emailsubject'         => 'विषयः',
'emailmessage'         => 'सन्देशः :',
'emailsend'            => 'प्रेषति',
'emailccme'            => 'सन्देशस्य प्रतिकृतिः मे विद्युन्मानपत्रसङ्केताय अपि प्रेषयतु ।',
'emailccsubject'       => '$1: $2 कृते अपि भवतः सन्देशस्य प्रकृतीः ।',
'emailsent'            => 'विद्युन्मानपत्रं प्रेषितम् ।',
'emailsenttext'        => 'भवतः विद्युन्मानपत्रसन्देशः प्रेषिताः ।',
'emailuserfooter'      => 'एतद्विद्युन्मानपत्रं {{SITENAME}} इत्यस्य योजपत्राचरव्यवस्थाद्वारा  $1 इत्यनेन $2 कृते प्रेषितम् ।',

# User Messenger
'usermessage-summary' => 'तान्त्रिकसन्देशानां त्यागः ।',
'usermessage-editor'  => 'तान्त्रिकसन्देशवाहकः ।',

# Watchlist
'watchlist'            => 'मम अवेक्षणसूची',
'mywatchlist'          => 'मम अवेक्षणसूची',
'watchlistfor2'        => 'हि $1 $2',
'nowatchlist'          => 'अवलोकनावल्यां पदार्थः नास्ति ।',
'watchlistanontext'    => 'अवलोकनपट्टिकायां पुटं दृष्टुं सम्पादयितुं वा  $1  करोतु ।',
'watchnologin'         => 'न नामाभिलितम्',
'watchnologintext'     => 'अवलोकनावलीं परिवर्तयितुं भवता नामाभिलेखनं करणीयम् ।[[Special:UserLogin|logged in]]',
'addwatch'             => 'अवलोकनावलीं योजयतु ।',
'addedwatchtext'       => 'भवतः [[Special:Watchlist|ध्यानसूचिकायां]] "[[:$1]]" इत्येतत् योजितमस्ति।
इदानींप्रभृति अस्मिन् पृष्ठे तथा अस्य चर्चापृष्ठे सन्तः परिवर्तनानि भवतः निरीक्षासूचिकायां द्रक्ष्यन्ते तथा च [[Special:RecentChanges|सद्यःपरिवर्तितानां सूचिकायां]] इदं पृष्ठं स्थूलाक्षरैः द्रक्ष्यते, यस्मात् भवान् सरलतया इदं पश्यतु <p>निरीक्षासूचिकातः निराकर्तुमिच्छति चेत्, "मा निरीक्षताम्" इत्यसमिन् नोदयतु।',
'removewatch'          => 'अवलोकनावलीतः अपनयतु ।',
'removedwatchtext'     => '"[[:$1]]" इति पृष्ठं [[Special:Watchlist|भवतः निरीक्षासूचिकातः]] निराकृतमस्ति।',
'watch'                => 'निरीक्षताम्',
'watchthispage'        => 'इदं पृष्ठं निरीक्षताम्',
'unwatch'              => 'मा निरीक्षताम्',
'unwatchthispage'      => 'अवलोकनेन अलम् ।',
'notanarticle'         => 'न आधेयं पुटम् ।',
'notvisiblerev'        => 'अन्ययोजकेन कृतम् अवतरणम् अपमर्जितम् ।',
'watchnochange'        => 'दर्शितावधौ अवलोकितपदार्थाः न सम्पादिताः ।',
'watchlist-details'    => '{{PLURAL:$1|$1 पृष्ठं|$1 पृष्ठानि}} भवतः अवेक्षणसूच्यां सन्ति, सम्भाषणपृष्ठानि नात्र गणितानि।',
'wlheader-enotif'      => '* विद्युन्मानपत्रस्य सूचनाः सक्रियाः ।',
'wlheader-showupdated' => '* भवतः सन्दर्शनस्य पश्चात् परिवर्तितानि पुटानि स्थूलाक्षरैः निर्दिष्टानि ।',
'watchmethod-recent'   => 'अवलोकितपुटानां सद्यः सम्पादनस्य परीक्षणम् ।',
'watchmethod-list'     => 'सद्यः सम्पादनार्थम् अवलोकितपुटानां परीक्षणम् ।',
'watchlistcontains'    => 'भवतः अवलोकनावली $1 युक्तास्ति ।{{PLURAL:$1|page|pages}}.',
'iteminvalidname'      => "समस्या  '$1' इत्यनेन अस्ति । अमान्यं नाम ।",
'wlnote'               => "अधस्तात् {{PLURAL:$1|'''1''' परिवर्तनमस्ति|अन्तिमानि '''$1''' परिवर्तनानि सन्ति}},{{PLURAL:$2|गते दिवसे|'''$2''' गतेषु दिवसेषु}}, , $3, $4. इति",
'wlshowlast'           => 'अन्तिमानि ($1 होराः $2 दिनानि) $3 इति दर्श्यन्ताम्',
'watchlist-options'    => 'अवेक्षणसूच्याः विकल्पाः',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'निरीक्षते...',
'unwatching'     => 'निरीक्षाम् अपाकरोति...',
'watcherrortext' => ' "$1" कृते अवलोकनावल्याः व्यवस्थापरिवर्तनावसरे दोषः संविधितः ।',

'enotif_mailer'                => '{{SITENAME}} सूचितः विद्युन्मानपत्रप्रेषकः ।',
'enotif_reset'                 => 'सन्दर्शितानि इति सर्वपुटानि अङ्कयतु ।',
'enotif_newpagetext'           => 'इदम् एकं नवीनपृष्ठम्',
'enotif_impersonal_salutation' => '{{SITENAME}} योजक',
'changed'                      => 'परिवर्तितम् ।',
'created'                      => 'सृष्टम् ।',
'enotif_subject'               => '{{SITENAME}}  $ पुटशीर्षकं $ परिवर्तितम्$ इत्यनेन ।',
'enotif_lastvisited'           => 'भवतः पूवसन्दर्शनस्य पश्चात् सवृत्तपरिवर्तनार्थं $1 पश्यतु ।',
'enotif_lastdiff'              => 'एतत्परिवर्तनं दृष्टुं $1 पश्यतु ।',
'enotif_anon_editor'           => 'अनामकः योजकः $1',
'enotif_body'                  => 'आत्मीय $ अवलोकनबन्धो',

# Delete
'deletepage'             => 'पृष्ठं निराकरोतु।',
'confirm'                => 'स्थिरीकरोतु',
'excontent'              => '"$1" आधेयः आसीत् ।',
'excontentauthor'        => 'आधेयः $1आसीत् । अपि च योगदाता तु "[[Special:Contributions/$2|$2]]" आसीत् ।',
'exbeforeblank'          => 'रिक्तीकरणात् पूर्वम् आधेयः "$1" आसीत् ।',
'exblank'                => 'पुटं रिक्तमासीत् ।',
'delete-confirm'         => 'विलुप्यताम् "$1"',
'delete-legend'          => 'विलुप्यताम्',
'historywarning'         => "' पूर्वसूचना ''' भवता अपमर्जनसिद्धपुटे बहुशः  $1 इतिहासयुक्तः अस्ति ।{{PLURAL:$1|revision|revisions}}:",
'confirmdeletetext'      => 'भवान् एकं पृष्ठं तस्य अखिलेन इतिहासेन सहितं अपाकर्तुं प्रवृत्तोऽस्ति। कृपया सुपुष्टीकरोतु यत् भवतः एतदेव आशयः, यद् भवता अस्य परिणामाः सुविज्ञाताः सन्ति तथा च भवता क्रियैषा [[{{MediaWiki:Policy-url}}| यथानीति]] सम्पाद्यते।',
'actioncomplete'         => 'कार्यं सम्पन्नम्',
'actionfailed'           => 'कर्मन् रिष्ट',
'deletedtext'            => '"$1" इत्येतद् अपाकृतमस्ति।
सद्यःकृतानां अपाकरणानाम् अभिलेखः $2 इत्यस्मिन् पश्यतु।',
'dellogpage'             => 'अपाकरणानां सूचिका',
'dellogpagetext'         => 'सद्यः कालीनापमर्जितपुटानाम् आवली अधः अस्ति ।',
'deletionlog'            => 'अपमर्जनसूचिका ।',
'reverted'               => 'प्राचीनपुनरावृत्तिः पूर्ववत् कृता ।',
'deletecomment'          => 'कारणम् :',
'deleteotherreason'      => 'अपरं/अतिरिक्तं कारणम् :',
'deletereasonotherlist'  => 'इतर कारणम्',
'deletereason-dropdown'  => '*अपमर्जनस्य सामान्यकारणानि । 
** लेखकस्य निवेदनम् । 
** कृतिस्वाम्यस्य उल्लङ्घनम् । 
** नाशकत्वम् ।',
'delete-edit-reasonlist' => 'अपमार्जनकारणानि सम्पादयतु ।',
'delete-toobig'          => 'अस्य पुटास्य सम्पादनेतिहासः$1तः अधिकः {{PLURAL:$1|पुनरावृत्तिः}} इति कारणेन बृहत् अस्ति । 
{{SITENAME}} इत्यस्य अकस्मात् प्रविदारणम् अवरोद्धुं तादृशपुटस्य अपमर्जनं निषिद्धम्  ।',
'delete-warning-toobig'  => ' $1 {{PLURAL:$1|पुनरावृत्तिः|पुनरावृत्तयः}} अस्मिन् पुटे विसृतः सम्पादनेतिहासः ।',

# Rollback
'rollback'          => 'सम्पादनं निर्वर्तयतु ।',
'rollback_short'    => 'प्रत्याहरणम् ।',
'rollbacklink'      => 'प्रतिनिवर्त्यताम्',
'rollbackfailed'    => 'प्रत्यहरणम् असफलम् ।',
'cantrollback'      => 'सम्पादनं  पूर्ववत् प्रत्यानेतुं न शक्यते ।
गतयोजकः केवलम् अस्यपुटस्य कर्ता ।',
'alreadyrolled'     => '[[User:$2|$2]] ([[User talk:$2|वार्ता]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) द्वारा कृतम्  [[:$1]] इत्यस्य गतसम्पादनं पूर्वतनस्थितौ प्रत्याहरणं न शक्यते । अत्रान्तरे कोऽप्यन्यः एतत्पुटं पुनस्सम्पादितवान् अथवा पूर्वमेव प्राचीनस्थितौ आनीतम् अस्ति ।
अस्य पुटास्य अन्तिमसम्पादनं [[User:$3|$3]] ([[User talk:$3|वार्ता]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) इत्यनेन कृतम् ।',
'editcomment'       => "\"''\$1''\" इति सम्पादनसारः आसीत् ।",
'revertpage'        => '[[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]])इत्यस्य सम्पादनम् अपमर्ज्य  [[User:$1|$1]] इति अन्तिमपुनरावृत्तिः ।',
'revertpage-nouser' => '(योजकस्य नाम अपनीतम्) द्वारा कृतसम्पादनं पूर्वस्थितौ प्रत्याहृत्य तत्पूर्वतनस्य [[User:$1|$1]] द्वारा कृतपुनरावृत्तेः नूतनावृत्तिः कृता ।',
'rollback-success'  => '$1 इत्यस्य सम्पादनम् अपनयतु । 
$2 द्वारा सम्पादितां अन्तिमावृत्तिं पुनस्थापयतु ।',

# Edit tokens
'sessionfailure-title' => 'सत्रस्य वैफल्यम् ।',
'sessionfailure'       => 'भवतः प्रवेशत्रेण सह कापि समस्या अस्ति इति भाति ।
सत्रापहरणात् रक्षणस्य सावधानार्थं भवतः क्रियाकलापः अपनीतः ।
निर्गत्य पूर्वपुटं गत्वा पुनः गत्वा प्रयत्नं करोतु ।',

# Protect
'protectlogpage'              => 'सुरक्षासूची',
'protectlogtext'              => 'अधो दत्ता सुरक्षार्थं कृतपरिवर्ननानां सूचिका अस्ति । 
वरतमानस्य सुरक्षितपुटानां सूचिकार्थम् अत्र [[Special:ProtectedPages|सुरक्षितपुटानां सूचिका]] पश्यतु ।',
'protectedarticle'            => '"[[$1]]" इत्येतद् संरक्षितमस्ति',
'modifiedarticleprotection'   => '"[[$1]]" इत्येतदर्थं सुरक्षा-स्तरः परिवर्तित: :',
'unprotectedarticle'          => '"[[$1]]"तः सुरक्षा अपमर्जिता ।',
'movedarticleprotection'      => 'सुरक्षणस्य स्तरः  "[[$2]]" तः परिवर्त्य   "[[$1]]" कृतः अस्ति ।',
'protect-title'               => '"$1" इत्यस्य सुरक्षास्तरं पश्यतु ।',
'protect-title-notallowed'    => '"$1" इत्यस्य सुरक्षास्तरं पश्यतु ।',
'prot_1movedto2'              => '[[$1]] इत्यस्य नामपरिवर्तनं कृत्वा [[$2]] इति कृतम् ।',
'protect-badnamespace-title'  => 'असुरक्षितं नामस्थानम् ।',
'protect-badnamespace-text'   => 'अस्मिन् नामस्थाने पुटानि सुरक्षितानि न भवन्ति ।',
'protect-legend'              => 'सुरक्षां दृढयतु ।',
'protectcomment'              => 'कारणम् :',
'protectexpiry'               => 'अवसानम् :',
'protect_expiry_invalid'      => 'अवसान-समयः अमान्योऽस्ति।',
'protect_expiry_old'          => 'अवसान-समयः अतीतोऽस्ति।',
'protect-unchain-permissions' => 'अग्रिमान् सुरक्षाविकल्पान् निर्तालयतु ।',
'protect-text'                => "'''$1''' इति पृष्ठस्य कृते सुरक्षा-स्तरं भवान् अत्र दृष्टुं शक्नोति, तथा च तं परिवर्तयितुं शक्नोति।",
'protect-locked-blocked'      => "भवान् सुरक्शणस्य स्तरं परिवर्तयितुं नैव शक्नोति ।
'''$1'' इति पुटस्य वर्तमाना स्थितिः एषा अस्ति ।",
'protect-locked-dblock'       => "सक्रियेन दत्तपाठतालनेन सुरक्षापत्राणि परिवर्तयितुं न शक्यते ।
'''$1''' इत्यस्य वर्तमाना स्थितिः एषा अस्ति ।",
'protect-locked-access'       => "भवान् अस्य पृष्ठस्य सुरक्षा-स्तरं परिवर्तयितुम् अनुज्ञां न धारयति। '''$1''' इति पृष्ठस्य अधुनातनः सुरक्षा-स्तरः :",
'protect-cascadeon'           => 'इदं पृष्ठं वर्तमत्काले सुरक्षितमस्ति, यत इदं {{PLURAL:$1|निम्नलिखिते पृष्ठे |निम्नलिखितेषु पृष्ठेषु}} समाहितमस्ति {{PLURAL:$1|यस्मिन्|येषु}} सोपानात्मिका सुरक्षा प्रभाविनी अस्ति। भवान् अस्य पृष्ठस्य सुरक्षा-स्तरं परिवर्तयितुं शक्नोति, परं तेन सोपानात्मिका-सुरक्षा न परिवर्तयिष्यति।',
'protect-default'             => 'सर्वान् प्रयोक्तॄन् अनुज्ञापयतु।',
'protect-fallback'            => '"$1" अनुज्ञा आवश्यकी।',
'protect-level-autoconfirmed' => 'नूतनान् तथा अपंजीकृतान् प्रयोक्तॄन् निरुध्नातु।',
'protect-level-sysop'         => 'प्रबंधकाः केवलाः',
'protect-summary-cascade'     => 'सोपानात्मकम्',
'protect-expiring'            => 'अवसानम् $1 (UTC)',
'protect-expiring-local'      => '$1 अपनीतम् ।',
'protect-expiry-indefinite'   => 'अनिश्चितकालः',
'protect-cascade'             => 'अस्मिन् पृष्ठे समाहितानि पृष्ठाणि सुरक्षितानि करोतु (सोपानात्मिका सुरक्षा)।',
'protect-cantedit'            => 'भवान् अस्य पृष्ठस्य सुरक्षा-स्तरं परिवर्तयितुं न शक्नोति, यतो भवान् इदं पृष्ठं संपादयितुं अनुज्ञां न धारयति।',
'protect-othertime'           => 'अन्यः समयः ।',
'protect-othertime-op'        => 'अन्यः समयः :',
'protect-existing-expiry'     => 'विद्यमानः समाप्तिसमयः  $3, $2',
'protect-otherreason'         => 'अपरं/अतिरिक्तं कारणम् :',
'protect-otherreason-op'      => 'इतर कारणम्',
'protect-dropdown'            => '*सुरक्षायाः सामान्यकारणानि । 
** अत्यधिकं नाशकत्वम् ।
** अत्यधिकं शुष्कसन्देशाः ।
** अफलदायि सम्पादनयुद्धम्
** अधिकसञ्चारयुक्तपुटानि ।',
'protect-edit-reasonlist'     => 'सुरक्षाकारणानि सम्पादयतु ।',
'protect-expiry-options'      => '१ होरा :1 hour,१दिनम्:1 day,१ सप्ताहः:1 week,सप्ताहद्वयम्:2 weeks,१मासः:1 month,३मासाः:3 months,६मासाः:6 months,१ वर्षम् :1 year, अनन्तम् :infinite',
'restriction-type'            => 'अनुमतिः:',
'restriction-level'           => 'सुरक्षा-स्तरः :',
'minimum-size'                => 'कनिष्टाकारः ।',
'maximum-size'                => 'गरिष्टाकारः ।',
'pagesize'                    => 'बैट्स् ।',

# Restrictions (nouns)
'restriction-edit'   => 'सम्पाद्यताम्',
'restriction-move'   => 'चलनम् ।',
'restriction-create' => 'सृज्यताम्',
'restriction-upload' => 'आरोप्यताम्',

# Restriction levels
'restriction-level-sysop'         => 'पूर्णतया संरक्षितम्',
'restriction-level-autoconfirmed' => 'अर्धसंरक्षितम्',
'restriction-level-all'           => 'कोऽपि स्तरः ।',

# Undelete
'undelete'                     => 'अपमर्जितपुटानि अवलोकयतु ।',
'undeletepage'                 => 'अपमर्जितपुटानि दृष्ट्वा पुनस्थापयतु ।',
'undeletepagetitle'            => "'''अधः [[:$1|$1]] इत्येतेषाम् अपनीतावृत्तीनां दर्शनं भवति ।",
'viewdeletedpage'              => 'अपमर्जितपुटानि अवलोकयतु ।',
'undeletepagetext'             => '{{PLURAL:$1|$1पुटं|$1 पुटानि}} इत्येतानि अपनीतानि किन्तु  एतानि लेखागारे सन्ति अपि च पुनस्थापितानि कर्तुं शक्यते ।',
'undelete-fieldset-title'      => 'पुनरावृत्तीः पुनस्थापयतु ।',
'undeleteextrahelp'            => "पुटानाम् इतिहासं प्रत्याहर्तुं चिह्नितमञ्जूषाः अवचिताः कृत्वा '''''{{int:undeletebtn}}''''' इत्येतत् तुदतु ।  
विचितेतिहासं प्रत्याहर्तुं तद्वृत्तीनां पार्श्वगतचिह्नमञ्जूषासु चयनचिह्नानि विनिवेशयतु । पश्चात्'''''{{int:undeletebtn}}''''' एतत् तुदतु  ।",
'undeleterevisions'            => '$1 {{PLURAL:$1|पुनरावृत्तिः}}',
'undeletehistory'              => 'यदि भवान् पुटानि पुनस्थापयितुमिच्छति तर्हि पुनरवृत्तीनां सर्वेतिहासाः पुनस्थापितानि भवन्ति । 
अपनयनात् परं यदि तस्मिन् एव नाम्नि नूतनपुटनिर्माणं करोति चेत् तस्य पुनस्थापितावृत्तिः पूर्वेतिहासे एव दृश्यते ।',
'undeleterevdel'               => 'यदि पुनस्थापनस्य फलस्वरूपशीर्षकपुटं, सञ्चिकां, पुनरावृत्तिं वा आंशिकरूपेण नाशयति चेत् एतत् न क्रियते ।
एतादस्थितौ नूतनापनीताः पुनरावृत्तीनाम् अपचयनं असङ्गोपनं वा कुर्याट् ।',
'undeletehistorynoadmin'       => 'एतत्पुटम् अपमर्जितम् ।
अधः अपमर्जनस्य कारणं दर्शितम् । अपमर्जनात् पूर्वं ये योजकाः सम्पादनं कृतवन्तः तेषां विषयः अपि दर्शिताः । 
अपमर्जितपुनरावृत्तीनां वास्तवपाठः केवलं प्रशासकै दृष्टुं शक्यते ।',
'undelete-revision'            => '$1 ($4 इत्येतं $5 समये $3 द्वारा निर्मितम्) इत्येतेषाम् अपमर्जितपुनरावृत्तयः ।',
'undeleterevision-missing'     => 'अमान्या अथवा विलुप्ता पुनरावृत्तिः । भवान् प्रदुष्टानुबन्धयुक्तः अथवा पुनरावृत्तिः पुनस्थापिता अथवा लेखागारात् अपनीता ।',
'undelete-nodiff'              => 'पूर्वतनपुनरावृत्तिः न दृष्टा ।',
'undeletebtn'                  => 'पुन्थापयतु ।',
'undeletelink'                 => 'दृश्यताम्/प्रत्यानीयताम्',
'undeleteviewlink'             => 'दृश्यताम्',
'undeletereset'                => 'पुनर्योजयतु ।',
'undeleteinvert'               => 'चयनं परिवर्तयतु ।',
'undeletecomment'              => 'कारणम् :',
'undeletedrevisions'           => '{{PLURAL:$1| पुनरावृत्तिः पुनस्थापिता|$1 पुनरावृत्तयः पुनस्थापिताः}} अस्ति|सन्ति ।',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 पुनरावृत्तिः|$1 पुनरावृत्तयः}} अपि च {{PLURAL:$2|१सञ्चिका|$2 सञ्चिकाः}} पुनस्थापिताः सन्ति ।',
'undeletedfiles'               => '{{PLURAL:$1|१सञ्चिका|$1 सञ्चिकाः}} पुनस्थापिताः ।',
'cannotundelete'               => 'अनपमर्जनम् असफलम् । 
प्रथमं कोऽप्यन्यः पुटम् अपमर्जितवान् स्यात् ।',
'undeletedpage'                => "'''$1 इत्येतत् पुनस्थापितम् अस्ति । 
सद्यः अपनीतानि पुनस्थापितानि च पुटाणि ज्ञातुम् अत्र पश्यतु । [[Special:Log/delete|अपनयनप्रवेशः]] ।",
'undelete-header'              => 'सद्यः एव अपनीतानां पुटानां दर्शनार्थं अत्र प्रविशतु । [[Special:Log/delete|अपनीतावली]] ।',
'undelete-search-title'        => 'अपमर्जितपुटानि अन्विषतु ।',
'undelete-search-box'          => 'अपमर्जितपुटानि अन्विषतु ।',
'undelete-search-prefix'       => 'इत्यनेन आरब्धपुटानि दर्शयतु ।',
'undelete-search-submit'       => 'अन्वेषणम्',
'undelete-no-results'          => 'अपमर्जनलेखागारे अमेलपुटानि लब्धानि ।',
'undelete-filename-mismatch'   => 'समयमुद्रया सञ्चिकापुनरावृत्तिः अनपमर्जितुं शक्यते । यतः $1 इति सञिकानाम अननुरूपम् ।',
'undelete-bad-store-key'       => 'समयमुद्रया सञ्चिकापुनरावृत्तिः अनपमर्जनं  नैव शक्नोति । ।$1: यतः अपमर्जनात् पूर्वमेव विलुप्तम् ।',
'undelete-cleanup-error'       => 'दोषापमारजनस्य अनुपयुक्ता लेखागारसञ्चिका "$1" ।',
'undelete-missing-filearchive' => '$1 इति सञ्चिकालेखागारस्य अभिज्ञापकं पुनस्थापितुं नैव शक्यते । यतः एतत् दत्तपाठे नास्ति ।
एतत् पूर्वमेव अनपमर्जितं स्यात् ।',
'undelete-error'               => 'पुटापमर्जने दोषः ।',
'undelete-error-short'         => 'सञ्चिकानपमर्जने दोषः : $1',
'undelete-error-long'          => '!!सञ्चिकानपमर्जने आगता समस्या ।$1',
'undelete-show-file-confirm'   => '$2 तः $3 मध्ये "<nowiki>$1</nowiki>" इति सञ्चिकायाः निरस्तं परिष्करणं भवान् नूनं द्रष्टुम् इच्छति ?',
'undelete-show-file-submit'    => 'आम्',

# Namespace form on various pages
'namespace'                     => 'नामाकाशः :',
'invert'                        => 'चयनं विपरीतीकरोतु',
'tooltip-invert'                => 'चितनामस्थाने परिवर्तनं गोपयितुं मञ्जूषाम् अर्गलयतु ।',
'namespace_association'         => 'सम्बद्धं नामस्थानम् ।',
'tooltip-namespace_association' => 'चितनामस्थानेन सह सम्बद्धं विषयनामस्थानम् अथवा सम्भाषणम् अपि उपादातुम् इमां मञ्जूषाम् अर्गलयतु ।',
'blanknamespace'                => '(मुख्यः)',

# Contributions
'contributions'       => 'प्रयोक्तॄणां योगदानानि',
'contributions-title' => '$1 इत्येतस्य कृते योजकानां योगदानानि',
'mycontris'           => 'मम योगदानानि',
'contribsub2'         => '$1 इत्येतदर्थम् ($2)',
'nocontribs'          => 'एतादृशयोग्यताभिः समं परिवर्तनानि न दृष्टानि ।',
'uctop'               => '(शीर्षम्)',
'month'               => 'अस्मात् मासात् (प्राक्तनानि च):',
'year'                => 'अस्मात् वर्षात् (प्राक्तनानि च):',

'sp-contributions-newbies'             => 'नूतनयोजकानां केवलं योगदानानि दर्श्यन्ताम्',
'sp-contributions-newbies-sub'         => 'नूतनलेखार्थम् ।',
'sp-contributions-newbies-title'       => 'नूतनलेखार्थं योजकयोगदानम् ।',
'sp-contributions-blocklog'            => 'अवरुद्धा सूची',
'sp-contributions-deleted'             => 'योजकयोगदानम् अपमर्जतु ।',
'sp-contributions-uploads'             => 'आरोप्यताम्',
'sp-contributions-logs'                => 'लोग्स',
'sp-contributions-talk'                => 'सम्भाषणम्',
'sp-contributions-userrights'          => 'योजकाधिकारस्य व्यवस्थापनम् ।',
'sp-contributions-blocked-notice'      => 'अयं प्रयोक्ता सम्प्रति अवरुद्धः वर्तते।
नूतनतमा अवरोधाभिलेख-प्रविष्टिः सन्दर्भार्थम् अधस्तात् प्रदत्ताऽस्ति:',
'sp-contributions-blocked-notice-anon' => 'अयं प्रयोक्ता सम्प्रति अवरुद्धः वर्तते।
नूतनतमा अवरोधाभिलेख-प्रविष्टिः सन्दर्भार्थम् अधस्तात् प्रदत्ताऽस्ति:',
'sp-contributions-search'              => 'योगदानानि अन्विष्यन्ताम्',
'sp-contributions-username'            => 'आइ.पी.सङ्केतः अथवा योजकनाम :',
'sp-contributions-toponly'             => 'सम्पादनानां नूतनावृत्तिमात्रं दर्श्यताम्',
'sp-contributions-submit'              => 'अन्विष्यताम्',

# What links here
'whatlinkshere'            => 'केभ्यः पृष्ठेभ्यः सम्बद्धम्',
'whatlinkshere-title'      => '"$1" इत्येतेन सम्बद्धानि पृष्ठानि',
'whatlinkshere-page'       => 'पृष्ठम् :',
'linkshere'                => "अधोलिखितानि पृष्ठाणि '''[[:$1]]''' इत्येतद् प्रति संबंधनं कुर्वन्ति :",
'nolinkshere'              => "'''[[:$1]]'''इत्येतेन न किञ्चित् पृष्ठं संयुक्तम्",
'nolinkshere-ns'           => "चितनामस्थानात्  '''[[:$1]]''' इत्येनं योजनयोग्यं पुटं नास्ति  ।",
'isredirect'               => 'अनुप्रेषण-पृष्ठम्',
'istemplate'               => 'मिलापयतु',
'isimage'                  => 'सञ्चिकासंबन्ध',
'whatlinkshere-prev'       => '{{PLURAL:$1|पूर्वतनम्|पूर्वतनानि $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|आगामि|आगामिनि $1}}',
'whatlinkshere-links'      => '← संबंधनानि',
'whatlinkshere-hideredirs' => '$1 पुनर्निर्दिष्टानि',
'whatlinkshere-hidetrans'  => '$1 मिलापनानि (ट्रांस्क्लुसन् इत्येतानि)',
'whatlinkshere-hidelinks'  => '$1 सम्पर्कतन्तवः',
'whatlinkshere-hideimages' => '$1 चित्रसम्पर्कतन्तुः',
'whatlinkshere-filters'    => 'निस्यन्दनानि',

# Block/unblock
'autoblockid'                     => 'स्वयं पिहितम् । $1',
'block'                           => 'योजकम् अवरुणद्धु ।',
'unblock'                         => 'योजकम् अनवरुणद्धु ।',
'blockip'                         => 'प्रयोक्तारं निरुध्नातु',
'blockip-title'                   => 'योजकम् अवरुणद्धु ।',
'blockip-legend'                  => 'योजकम् अवरुणद्धु ।',
'blockiptext'                     => 'विशिष्टं  IP सङ्केतम् अथवा योजकनाम लेखानाधिकारस्य प्राप्तये निम्नदत्तपत्रस्य उपयोगं करोतु ।
केवलं नाशकत्वम् अवरोद्धुं एतस्य उपयोगं करोतु । [[{{MediaWiki:Policy-url}}|नीतिः]] इत्यानुसारं करणीयम् ।
अधः विशिष्टं कारणमपि लिखतु ।',
'ipadressorusername'              => 'आइ.पी.सङ्केतः अथवा योजकनाम :',
'ipbexpiry'                       => 'समाप्तिः :',
'ipbreason'                       => 'कारणम् :',
'ipbreasonotherlist'              => 'अन्यत् कारणम्',
'ipbreason-dropdown'              => '* अवरोधस्य सामान्यानि कारणानि ।  
** मिथ्या योजकनाम । 
** एकाधिकयोजकस्थानं निर्मीय तेषां दुरुपयोगः । 
** असत्यविषयानाम् उत्तारणम् । 
** पुटेषु अवकरपूरणम् । 
** पुटेभ्यः पदार्थान् अपनयनम् । 
** बाह्यजालस्थानाम् असम्बद्धानुबन्धानाम् संयोजनम् । 
** योजकानां पीडनम् ।',
'ipb-hardblock'                   => 'नामाभिलेखितयोजकान् अनेन ऐपि सङ्केतेन सम्पादनं निवारयतु ।',
'ipbcreateaccount'                => 'योजकस्थानस्य निर्माणं निवारयतु ।',
'ipbemailban'                     => 'योजकस्य विद्युन्मानसन्देशप्रेषणम् अवरुणद्धु ।',
'ipbenableautoblock'              => 'अनेन योजकेन उपयुक्तम् ऐपिसङ्केतम्, अग्रे अनेन योजकेन सम्पादयितुं प्रयतमानम् ऐपिसङ्केतं च स्वयम् अवरुद्धं करोतु ।',
'ipbsubmit'                       => 'एतं योजकम् अवरुणद्धु ।',
'ipbother'                        => 'अन्यः समयः ।',
'ipboptions'                      => '२ होराः:2 hours,१ दिनम्:1 day,३ दिनानि:3 days,१ सप्ताहः:1 week,२ सप्ताहौ:2 weeks,१ मासः:1 month,३ मासाः:3 months,६ मासाः:6 months,१ वर्षः:1 year,अनन्तम्:infinite',
'ipbotheroption'                  => 'अन्य',
'ipbotherreason'                  => 'अपरं/अतिरिक्तं कारणम् :',
'ipbhidename'                     => 'सम्पादनेभ्यः आवलीभ्यः च योजकनाम सङ्गोपयतु ।',
'ipbwatchuser'                    => 'अस्य योजकस्य योजकपुटानि सम्भाषणपुटानि च अवलोकयतु ।',
'ipb-disableusertalk'             => 'एतं योजकम् अवरोधकाले स्वस्य सम्भाषणपुटस्य सम्पानात् निवारयतु ।',
'ipb-change-block'                => 'एतैः विन्यासैः योजकं पुनः अवरुणद्धु ।',
'ipb-confirm'                     => 'अवरोधं दृढयतु ।',
'badipaddress'                    => 'अमान्यः ऐपिसङ्केतः ।',
'blockipsuccesssub'               => 'अवरोधः सफलः ।',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]]इत्येतत् अवरुद्धम् । <br />
अवरोधानां समीक्षां करोतु । [[Special:BlockList|IP अवरोधसूचिका]]',
'ipb-blockingself'                => 'भवान् स्वयम् अवरोधने निरतः । निश्चयेन स्वावरोधनम् इच्छति वा ?',
'ipb-confirmhideuser'             => 'योजकगोपनस्य पिञ्जं निपीडयन् भवान् योजकावरुद्धिं यतते । एतत् सर्वावलीषु सर्वप्रवेशसूचिकासु च योजकनाम निग्रहति । भवान् निश्चयेन एतत् कर्तुमिच्छति वा ?',
'ipb-edit-dropdown'               => 'अवरोधकारणानि सम्पादयतु ।',
'ipb-unblock-addr'                => '$1 अनवरोधनं करोतु ।',
'ipb-unblock'                     => 'योजकनाम अथवा ऐपिसङ्केतम् अवरुणद्धु ।',
'ipb-blocklist'                   => 'वर्तमानावरोधान् अवलोकयतु ।',
'ipb-blocklist-contribs'          => '$1कृते योगदानम् ।',
'unblockip'                       => 'योजकसु अवरोधं परिहरतु ।',
'unblockiptext'                   => 'सद्यः अवरुद्धान् ऐपिसङ्केतान् अथवा अवरुद्धानि योजकनामानि पुनस्संस्थाप्य लिखनावकाशं प्राप्तुम् अधो दत्तप्रपत्रस्य उपयोगं करोतु ।',
'ipusubmit'                       => 'अवरोधम् अपनयतु ।',
'unblocked'                       => '[[User:$1|$1]] इति योजकस्य अवरोधम् अपनयतु ।',
'unblocked-range'                 => '$1 इत्येतस्य अवरोधः कृतः ।',
'unblocked-id'                    => '$1 इत्यस्य अवरोधः अपनीतः ।',
'blocklist'                       => 'अवरुद्धाः योजकाः ।',
'ipblocklist'                     => 'अवरुद्धाः योजकाः',
'ipblocklist-legend'              => 'अवरुद्धयोजकं पश्यतु ।',
'blocklist-userblocks'            => 'योजकस्थानावरुद्धिं गोपयतु ।',
'blocklist-tempblocks'            => 'तात्कालिकावरुद्धिं गोपयतु ।',
'blocklist-addressblocks'         => 'एकाकिनम् ऐपि अवरोधं गोपयतु ।',
'blocklist-rangeblocks'           => 'प्रान्तीयावरोधान् गोपयतु ।',
'blocklist-timestamp'             => 'समयमुद्रा',
'blocklist-target'                => 'लक्ष्यम्',
'blocklist-expiry'                => 'नश्यति',
'blocklist-by'                    => 'वरोधनस्य प्रशसनम् ।',
'blocklist-params'                => 'विस्तारान् अवरुणद्धु ।',
'blocklist-reason'                => 'कारणम्',
'ipblocklist-submit'              => 'अन्वेषणम्',
'ipblocklist-localblock'          => 'स्थानीयावरोधः ।',
'ipblocklist-otherblocks'         => 'अन्याः{{PLURAL:$1|अवरोधाः}}',
'infiniteblock'                   => 'अनन्तम् ।',
'expiringblock'                   => '$1 इत्यस्य $2समये समाप्तिः भवति ।',
'anononlyblock'                   => 'अनामकः केवलम् ।',
'noautoblockblock'                => 'स्वयमवरोधः निष्क्रियः ।',
'createaccountblock'              => 'योजकस्थाननिर्माणं निष्क्रियम् ।',
'emailblock'                      => 'विद्युन्मानपत्रं निष्क्रियम् ।',
'blocklist-nousertalk'            => 'स्वस्य सम्भाषणपुटं सम्पादयितुं न शक्यते ।',
'ipblocklist-empty'               => 'अवरोधावली रिक्ता अस्ति ।',
'ipblocklist-no-results'          => 'अभ्यर्थितः ऐपिसङ्केतः अथवा अभ्यर्थितः योजकनाम अवरुद्धं न ।',
'blocklink'                       => 'अवरोधः क्रियताम्',
'unblocklink'                     => 'निरोधः अपास्यताम्',
'change-blocklink'                => 'विभागः परिवर्त्यताम्',
'contribslink'                    => 'योगदानम्',
'emaillink'                       => 'विद्युन्मानपत्रं प्रेषयतु ।',
'autoblocker'                     => 'भवतः ऐपि सङ्केतः स्वयम् अवरुद्धः यः सद्यः काले एव [[User:$1|$1]]" इत्यनेन उपयुक्तः । 
$1 इत्यस्य अवरोधस्य कारणं तु "$2" अस्ति ।',
'blocklogpage'                    => 'अवरोधानां सूची',
'blocklog-showlog'                => 'अयम् एपि सङ्केतः पूर्वमेव अवरुद्धः । 
अवरोधसूची आधाराय अधः दत्तः अस्ति :',
'blocklog-showsuppresslog'        => 'अयं योजकः पूर्वमेव अवरुद्धः सङ्गुप्तः च  ।
निग्रहकरणं तु अधः उल्लिखितम् ।',
'blocklogentry'                   => '[[$1]] इत्येतद् अवरुद्धम्, $2 $3 इति अवसान-समयेन सह',
'reblock-logentry'                => '[[$1]] इत्यस्य अवरोधस्य विन्यासः परिवर्तितः अयं $2 $3 समये विनश्येत् ।',
'blocklogtext'                    => 'इयम् अवरुद्धानवरुद्धप्रक्रियायाः अवलोकनस्य सूचिका । 
स्वयम् अवरुद्धानाम् ऐपिसङ्केतानाम् आवली न कृता ।
सद्यः उपयोगनिषेधस्य अवरोधानाम् आवलीप्राप्तये [[Special:BlockList|block list]] अवलोकयतु ।',
'unblocklogentry'                 => 'अनिरुद्धम् $1',
'block-log-flags-anononly'        => 'अनामकाः योजकाः केवलम् ।',
'block-log-flags-nocreate'        => 'सदस्यता प्राप्तिः अवरुद्धा अस्ति',
'block-log-flags-noautoblock'     => 'स्वयमवरोधः निष्क्रियः ।',
'block-log-flags-noemail'         => 'विद्युन्मानपत्रं निष्क्रियम् ।',
'block-log-flags-nousertalk'      => 'स्वस्य सम्भाषणपुटं सम्पादयितुं न शक्यते ।',
'block-log-flags-angry-autoblock' => ' उन्नतीकृतः स्वयमवरोधः सक्रियः ।',
'block-log-flags-hiddenname'      => 'योजकस्य नाम सङ्गुप्तम् ।',
'range_block_disabled'            => ' प्रादेशिकावरोधस्य प्रशासकस्य सामर्थ्यं निष्क्रियम् ।',
'ipb_expiry_invalid'              => 'अवसानसमयः अमान्योऽस्ति।',
'ipb_expiry_temp'                 => 'सङ्गुप्तयोजकनामावरोधः शश्वतः भवेत् ।',
'ipb_hide_invalid'                => 'एतस्य योजकस्थानस्य निग्रहः असाध्यः । अस्मिन् अनेकानि सम्पादनानि स्युः ।',
'ipb_already_blocked'             => '"$1" इत्येषः पूर्वमेव अवरुद्धः ।',
'ipb-needreblock'                 => '$1 इत्येषः पूर्वमेव अवरुद्धः विन्यासं परिवर्तयितुमिच्छति वा ?',
'ipb-otherblocks-header'          => 'अन्याः {{PLURAL:$1|अवरोधः |अवरोधाः}}',
'unblock-hideuser'                => 'एतं योजकम् अवरोधात् विमोचयितुं न शक्यते । यतः अस्य योजकनाम सङ्गुप्तम् ।',
'ipb_cant_unblock'                => ' दोषः : $1 इति अवरुद्धः पत्रसङ्केतः न दृष्टः । प्रायः तावत् पूर्वमेव उत्तारितम् ।',
'ipb_blocked_as_range'            => 'दोषः : $1 इति ऐपिसङ्केतः साक्षात् अवरुद्धः न अपि च विमोचनं न शक्यते ।
$2 इति प्रकारस्य अवरोधं कर्तुं शक्यते यत् अनवरोधमिच्छति ।',
'ip_range_invalid'                => 'अमान्यः ऐपिप्रकारः',
'ip_range_toolarge'               => '/$1 तः अधिकं वृहत्प्रकारकः अवरोधः नानुमतः ।',
'blockme'                         => 'माम् अवरुणद्धु ।',
'proxyblocker'                    => 'प्रतिहस्तकः अवरोधकः ।',
'proxyblocker-disabled'           => 'अयं कार्यकलापः निष्क्रियः ।',
'proxyblockreason'                => 'भवतः ऐपि सङ्केतः अवरुद्धः  यतः अयं कश्चन मुक्तप्रतिहस्तकः । 
अन्तर्जालसेवादायकं सम्पर्कयतु गभीरायाः सुरक्षासमस्यायाः विषये सूचयतु च',
'proxyblocksuccess'               => 'समापित ।',
'sorbsreason'                     => 'DNSBL उपयोगः {{SITENAME}} कृतस्य भवतः ऐपिसङ्केतः मुक्तप्रतिहस्तकः इति आवलीगतः',
'sorbs_create_account_reason'     => 'DNSBL उपयुक्तः {{SITENAME}} अतः भवतः ऐपिसङ्केतः अवरुद्धः यतः अयं मुक्तप्रतिहस्तकः इति आवलीगतः । अतः भवान् योजकस्थानं निर्मातुं न शक्नोति ।',
'cant-block-while-blocked'        => 'अन्ययोजकान् अवरोद्धुं भवान् नैव शक्नोति यतः भवान् अवरुद्धः ।',
'cant-see-hidden-user'            => 'यं योजकः अवरोद्धं भवान् प्रयतमानः सः पूर्वमेव अवरुद्धः सङ्गुप्तः च ।
भवान् तु योजकसङ्गोपनाधिकारयुक्तः न । अतः भवान् योजकावरोधं दृष्टुं सम्पादयितुं वा न शक्नोति ।',
'ipbblocked'                      => 'भवान् अन्ययोजकान् अवरोद्धुम् विमोचयितुं वा न शक्नोति । यतः भवान् तु अवरुद्धः अस्ति ।',
'ipbnounblockself'                => 'भवान् भवन्तं मोचयितुं नैव शक्नोति ।',

# Developer tools
'lockdb'              => 'दत्तपाठान् अवरुणद्धु ।',
'unlockdb'            => 'दत्तपाठान् अनवरुणद्धु ।',
'lockdbtext'          => 'दत्तपाठानाम् अवरोधः सर्वयोजकानां सम्पादनसामर्थ्यं लुम्पति । तेषाम् आद्यतां परिवर्तयतु । तेषाम् अवलोकनावलीं सम्पादयतु । परिवर्तनावश्यकदतापाठान् अपि परिवर्तयतु । भवान् एतदेव कर्तुकामः इति दृढयतु । यदा भवतः निर्वहणं भविष्यति तदा दत्तपाठाः अनवरुद्धाः भविष्यन्ति ।',
'unlockdbtext'        => 'दत्तपाठानाम् अवरोधः सर्वयोजकानां सम्पादनसामर्थ्यं लुम्पति । तेषाम् आद्यतां परिवर्तयतु । तेषाम् अवलोकनावलीं सम्पादयतु । परिवर्तनावश्यकदतापाठान् अपि परिवर्तयतु । भवान् एतदेव कर्तुकामः इति दृढयतु ।',
'lockconfirm'         => 'आम्, अहं निश्चयेन दत्तपाठान् अवरोद्धुम् इच्छामि ।',
'unlockconfirm'       => 'आम्, अहं निश्चयेन दत्तपाठान् अनवरोद्धुम् इच्छामि ।',
'lockbtn'             => 'दत्तपाठान् अवरुणद्धु ।',
'unlockbtn'           => 'दत्तपाठान् विमोचयतु ।',
'locknoconfirm'       => 'दृढीकरणमञ्जूषां भवान् न अर्गलितवान् ।',
'lockdbsuccesssub'    => 'दत्तपाठावरोधः सफलः ।',
'unlockdbsuccesssub'  => 'दत्तपाठावरोधः विमुक्तः ।',
'lockdbsuccesstext'   => 'दत्तपाठाः तालिताः । ।<br />
भवतः निर्वहणस्य पश्चात् वितालनं स्मरतु [[Special:UnlockDB|वितालनम्]] ।',
'unlockdbsuccesstext' => 'दत्तपाठाः वितालिताः ।',
'lockfilenotwritable' => 'दत्तपाठः कपाटनस्य सञ्चिका लेखनार्हा न ।
दत्तपाठान् कपाटयितुम् अकापाटयितुं वा जालवितारकेन लेखनार्हः आवश्यक्तः ।',
'databasenotlocked'   => 'दत्तपाठाः कपाटिताः न ।',
'lockedbyandtime'     => '(द्वारा {{GENDER:$1|$1}} इत्यस्मिन् $2 अत्र $3)',

# Move page
'move-page'                    => ' $1 चालयतु ।',
'move-page-legend'             => 'पृष्ठं रक्ष्यताम्',
'movepagetext'                 => "अधोतत्तं प्रपत्रमुपयुज्य  पुटस्य पुनर्नामकरणं करिष्यति । अस्य पूर्णेतिहासः नूतनेन नाम्ना सह गच्छति । 
नूतनशीर्षकस्य प्राचीनशीर्षकं पुनर्निदिष्टं  भवति । 
भवान् पुनर्निदेशान् उन्नतीकरोतु यत् स्वयं मूलशीर्षकं निदेशति । 
यदि भवान् एवं कर्तुं नैव शक्नोति तर्हि  [[Special:DoubleRedirects|द्विगुणम्]]पुनर्दिदेशाः[[Special:BrokenRedirects|भग्नपुनर्निदेशाः]] एतदर्थम् अवश्यं परिशीलयतु । 
एतत् भवतः दायित्वं यत् अनुबन्धाः सुनिश्चितं स्थानं नयेयुः ।
यदि नूतनशीर्षकस्य लेखः पूर्वमेवास्ति तर्हि स्थानान्तरणं न भविष्यति । नूतनशीर्षकयुक्तलेखः रिक्तमस्ति अथवा कुत्रचित् अनुप्रेषणं करोति अपि च अनेन सह प्राचीनेतिहासः नास्ति चेत् स्थानान्तरणं न सम्भविष्यति ।
अर्थात् यदि भवता दोषः संवृत्तः चेत् भवान् पुनः प्राचीननाम्ना एतत्पुटं स्थानान्तरणं कर्तुं शक्नोति । अपि च किञ्चिदपि वर्तमानपुटस्य स्थाने एतत् स्थानान्तरणं कर्तुं नैव शक्नोति ।
पूर्वसूचना : यदि पुटं प्रसिद्धं तर्हि तस्य एतत् बृहत् अथवा अकस्मात् परिवर्तनं भवितुमर्हति ।
अनुवर्तनात् पूर्वम् अस्य परिणामं सम्यक् चिन्तयतु ।

'''सूचना'''
स्थानान्तरकरणेन कस्मिंश्चित् महालेखे अनपेक्षितं परिवर्तनं सम्भवेत् ।
अतः भवति निवेदनम् अस्ति यत् भवान् पूवमेव परिणामं चित्नयतु ।",
'movepagetext-noredirectfixer' => "अधोतत्तं प्रपत्रमुपयुज्य  पुटस्य पुनर्नामकरणं करिष्यति । अस्य पूर्णेतिहासः नूतनेन नाम्ना सह गच्छति । 
नूतनशीर्षकस्य प्राचीनशीर्षकं पुनर्निदिष्टं  भवति । 
भवान् पुनर्निदेशान् उन्नतीकरोतु यत् स्वयं मूलशीर्षकं निदेशति । 
यदि भवान् एवं कर्तुं नैव शक्नोति तर्हि  [[Special:DoubleRedirects|द्विगुणम्]]पुनर्दिदेशाः[[Special:BrokenRedirects|भग्नपुनर्निदेशाः]] एतदर्थम् अवश्यं परिशीलयतु । 
एतत् भवतः दायित्वं यत् अनुबन्धाः सुनिश्चितं स्थानं नयेयुः ।
यदि नूतनशीर्षकस्य लेखः पूर्वमेवास्ति तर्हि स्थानान्तरणं न भविष्यति । नूतनशीर्षकयुक्तलेखः रिक्तमस्ति अथवा कुत्रचित् अनुप्रेषणं करोति अपि च अनेन सह प्राचीनेतिहासः नास्ति चेत् स्थानान्तरणं न सम्भविष्यति ।
अर्थात् यदि भवता दोषः संवृत्तः चेत् भवान् पुनः प्राचीननाम्ना एतत्पुटं स्थानान्तरणं कर्तुं शक्नोति । अपि च किञ्चिदपि वर्तमानपुटस्य स्थाने एतत् स्थानान्तरणं कर्तुं नैव शक्नोति ।
पूर्वसूचना : यदि पुटं प्रसिद्धं तर्हि तस्य एतत् बृहत् अथवा अकस्मात् परिवर्तनं भवितुमर्हति ।
अनुवर्तनात् पूर्वम् अस्य परिणामं सम्यक् चिन्तयतु ।

'''सूचना'''
स्थानान्तरकरणेन कस्मिंश्चित् महालेखे अनपेक्षितं परिवर्तनं सम्भवेत् ।
अतः भवति निवेदनम् अस्ति यत् भवान् पूवमेव परिणामं चित्नयतु ।",
'movepagetalktext'             => 'सम्बद्धसम्भाषणपुटानि अनेन सह स्थानान्तरितानि भवन्ति अन्यथा  
* भवान् पुटं अन्यस्थानान्तरं कुर्वन् अस्ति । 
* अस्मिन् नाम्नि सम्भाषणपुटं पूर्वनिर्मितमस्ति अस्थवा  
* अधोदत्ताम् अर्गलनमञ्चूषाम् उत्पाटितवान् । 
अस्मिन् विषये यदि इच्छति तर्हि भवता पुटानि चालनीयानि अथवा संयोजनीयानि ।',
'movearticle'                  => 'पृष्ठं चाल्यताम्',
'moveuserpage-warning'         => 'पूर्वसूचा : योजकपुटं चालयितुम् उद्युक्तः । स्मरतु केवलं पुटं स्थानान्तरितं भवति न तु योजकनाम परिवर्तनं न भविष्यति ।',
'movenologin'                  => 'न नामाभिलितम्',
'movenologintext'              => ' [[Special:UserLogin|logged in]] पञ्जीकृतयोजकः भवता नामाभिलेखनं करणीयं भवति ।',
'movenotallowed'               => 'पुटानि स्थानान्तरियितुम् अनुमतिः नाश्ति ।',
'movenotallowedfile'           => 'सञ्चिकाः स्थानान्तरयितुम् अनुमतिः नास्ति ।',
'cant-move-user-page'          => 'योजकपुटानि स्थानन्तरितुम् अनुमतिः ते नास्ति । (उपपुटानि विना)',
'cant-move-to-user-page'       => 'किञ्चिनपुटं योजकपुटं स्थानान्तरितुं ते अनुमतिः नास्ति । (योजकपुटं विना)',
'newtitle'                     => 'नूतनं शीर्षकं प्रति :',
'move-watch'                   => 'इदं पृष्ठं निरीक्षताम्।',
'movepagebtn'                  => 'पृष्ठं चालयतु।',
'pagemovedsub'                 => 'चालनं सिद्धम्।',
'movepage-moved'               => '\'\'\'"$1" इत्येतद् "$2" इत्येतद् प्रति चालितमस्ति \'\'\'',
'movepage-moved-redirect'      => 'पुनर्निदेशः सृष्टः ।',
'movepage-moved-noredirect'    => 'पुनर्निदेशनसृष्टिः निग्रहितः ।',
'articleexists'                => 'अनेन नाम्ना पृष्ठमेकं पूर्वेऽपि विद्यते, अथवा भवता चितं नाम तु अमान्यमस्ति। कृपया इतरं किमपि नाम चिनोतु।',
'cantmove-titleprotected'      => 'अस्मिन् स्थाने पुटस्थानान्तरणं न भवति । यतः नूतनशीर्षकं सर्जनात् सुरक्षितम् ।',
'talkexists'                   => "'''पृष्ठं साफल्येन चालितमस्ति, परं चर्चापृष्ठं चालयितुं न शक्यम्, यतो नवेऽपि पृष्ठे चर्चापृष्ठं विद्यते। कृपया तं स्वयमेव चालयतु।'''",
'movedto'                      => 'इदं प्रति चालितम्।',
'movetalk'                     => 'सहगामिनं चर्चापृष्ठं चालयतु।',
'move-subpages'                => 'उपपुटनि चालयतु । ($1 पर्यन्तम्)',
'move-talk-subpages'           => 'सम्भाषणपुटानाम् उपपुटानि चालयतु ।($1 पर्यन्तम्)',
'movepage-page-exists'         => '$1 इत्येतत् पुटं पूर्वमेव विद्यते । तदुपरि लेखनम् अशक्यम् ।',
'movepage-page-moved'          => '$1 पुटं $2 प्रति चालितम् अस्ति ।',
'movepage-page-unmoved'        => '$1 पुटं $2 प्रति चालनम् अशक्यम् ।',
'movepage-max-pages'           => '$1  इत्यस्य {{PLURAL:$1|page|pages}} गरष्टपुटानि चालितानि अतः इतोप्यधिकपुटानि स्वयं चालितानि न भवन्ति ।',
'movelogpage'                  => 'लॉग् इत्येतद् चाल्यताम्',
'movelogpagetext'              => 'पुटचालनस्य आवली अधः अस्ति ।',
'movesubpage'                  => '{{PLURAL:$1|उपपुटः|उपपुटानि}}',
'movesubpagetext'              => '$1 {{PLURAL:$1|उपपुटम्|उपपुटानि }}अस्य पुटस्य उपपुटानि अधः दर्शितानि ।',
'movenosubpage'                => 'अस्य पुटस्य उपपुटानि न सन्ति ।',
'movereason'                   => 'कारणम् :',
'revertmove'                   => 'प्रतिनिवर्त्यताम्',
'delete_and_move'              => 'अपमर्जनं चालनं च ।',
'delete_and_move_text'         => '==अपमर्जनम् आवश्यकम्==
लक्षितपुटं "[[:$1]]" पूर्वमेव अस्ति ।
चालनपथं सृष्टुम् एतत् अपमर्जितुम् इच्छति वा ?',
'delete_and_move_confirm'      => 'आम्, पुटम् अपमर्जतु ।',
'delete_and_move_reason'       => '"[[$1]]" तः स्थानान्तरणं कर्तुं पथनिर्माणार्थम् अपमर्जितम् ।',
'selfmove'                     => 'स्रोतः लक्ष्यशीर्षकं च समाने ।
पुटं स्वस्थानान् स्थानान्तरं न शक्यते ।',
'immobile-source-namespace'    => '$1 इति नामस्थाने पुटस्थानान्तरं न शक्यते ।',
'immobile-target-namespace'    => '"$1" इति नामस्थाने पुटानां स्थानान्तरं न शक्यते ।',
'immobile-target-namespace-iw' => 'पुटचालनार्थम् अन्तर्विक्यानुबन्धः मान्यं लक्ष्यं न ।',
'immobile-source-page'         => 'एतत्पुटं चालनयोग्यं न ।',
'immobile-target-page'         => 'तत् लक्षितशीर्षकं प्रति चालयितुं न शक्यते ।',
'imagenocrossnamespace'        => 'सञ्चिकां  अनामस्थाने स्थानान्तरितं कर्तुं नैव शक्यते ।',
'nonfile-cannot-move-to-file'  => 'असञ्चिकायाः सञ्चिकानामस्थाने स्थानान्तरं न शक्यते ।',
'imagetypemismatch'            => 'नूतपुटविस्तारः तस्य प्रकाण सह मेलं न  प्राप्नोति ।',
'imageinvalidfilename'         => 'लक्षितसञ्चिकानाम अमान्यम् ।',
'fix-double-redirects'         => 'यङ्कमपि पुनर्निदेशं उन्नतीकरोतु यः मूलशीर्षकं निदेशति ।',
'move-leave-redirect'          => 'कञ्चित् पुनर्निदेशं पूर्वमेव त्यजतु ।',
'protectedpagemovewarning'     => "'''पूर्वसूचना ''' प्रशासकपदयुक्ताः योजकाः एव सम्पादनं कर्तुमर्हन्ति । अतः एतत्पुटं सुरक्षितम् । निदेशार्थम् अधः जघन्यप्रवेशः सूचितः ।",
'semiprotectedpagemovewarning' => "'''सूचना ''' पञ्जीकृतयोजकानां  उपयोगार्थ केवलम् एतत्पुटम् अभिरक्षितम् । जघन्यप्रवेशस्य सूचना आनुकूल्यार्थम् अधोनिदेशिता ।",
'move-over-sharedrepo'         => '==वर्तमानसञ्चिकाः==
 [[:$1]] विभक्तकोशे सञ्चिकास्ति । अस्यां शीर्षकं स्थानान्तरणेन विभक्तसञ्चिका विकृता भवति ।',
'file-exists-sharedrepo'       => 'विभक्तकोशे चितसञ्चिकानाम प्रथममेव उपयोगे अस्ति  । अन्यं नाम चिनोतु ।',

# Export
'export'            => 'पृष्ठानां निर्यातं करोतु',
'exporttext'        => 'विशेष पुटस्य पाठम् अथवा सम्पादनेतिहासं निर्हर्तुं शक्नोति । अथवा पुटसमूहम् उपोतं कर्तुमपि शक्नोति ।
एतत् [[Special:Import|आयातपुटं]] अस्य साहाय्येन मीडियाविक्याः प्रयोगं कृत्वा अन्यविकीतः आयातं कर्तुं शक्नोति ।
पुटानि नर्हर्तुम् अधो दत्तपाठमञ्जूषायां शीर्शकं लिखतु । एकस्य शीर्षकस्य एका पङ्क्तिः । अपि च वर्तमानावृत्त्या सह प्राचीनावृत्तिमपि इच्छति वा नेति अथवा गतसम्पादनस्य विषयज्ञानेन सह केवलं वर्नमानावृत्तिम् इच्छाति । 
पश्चात् स्थित्यर्थे भवान् कञ्चित् अनुबन्धं प्रयोक्तुमर्हति । यथा"[[{{MediaWiki:Mainpage}}]]"पुटार्थम् [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]।',
'exportall'         => 'सर्वपुटानि निर्यातानि करोतु ।',
'exportcuronly'     => 'सद्यः पुनरावृत्तिं केवलं सङ्गृह्णातु  समूर्णम् इतिहासं न ।',
'exportnohistory'   => 'सुचना : अनुष्टानस्य कारणेन पुटनिर्यातस्य सम्पूर्णेतिहासः एतत्पुटाद्वारा निष्क्रियाः  ।',
'exportlistauthors' => 'प्रत्येकं पुटाय योगदातॄणां पूर्णावलीम् अन्तर्भावयतु ।',
'export-submit'     => 'निर्हरति',
'export-addcattext' => 'वर्गतः पटानि योजयतु ।',
'export-addcat'     => 'संयोजयति',
'export-addnstext'  => 'नामस्थानात् पुटानि योजयतु ।',
'export-addns'      => 'संयोजयति',
'export-download'   => 'सञ्चिका इव रक्षतु ।',
'export-templates'  => 'प्राकृतीः अनर्भावयतु ।',
'export-pagelinks'  => '...इत्यस्य गाहाय अनुबद्धपुटानि अन्तरभावयतु ।',

# Namespace 8 related
'allmessages'                   => 'व्यवस्था सन्देशाः',
'allmessagesname'               => 'नाम',
'allmessagesdefault'            => 'डिफॉल्टसन्देशपाठ',
'allmessagescurrent'            => 'सद्यः सन्देशपाठः ।',
'allmessagestext'               => 'एषा मीडियाविकिनामस्थाने उपलब्धा काचित् तन्त्रसन्देशस्य सूचिका अस्ति ।  यदि भवान् सामान्यमीडियाविकि क्षेत्रीयकरणे योगदानं कर्तुमिच्छति तर्हि[//www.mediawiki.org/wiki/Localisation मीडियाविकि क्षेत्रीयकरणम्] अथवा [//translatewiki.net translatewiki.net] इत्यत्र गच्छतु ।',
'allmessagesnotsupportedDB'     => "अस्य पुटस्य उपयोगः नैव शक्यते यतः '''\$wgUseDatabaseMessages''' तटास्थम् अस्ति ।",
'allmessages-filter-legend'     => 'शोधनी ।',
'allmessages-filter'            => 'ग्राहकीकरणस्य स्थितौ शोधनी ।',
'allmessages-filter-unmodified' => 'अपरिष्कृतम् ।',
'allmessages-filter-all'        => 'अखिलम्',
'allmessages-filter-modified'   => 'परिवर्तितम्',
'allmessages-prefix'            => 'उपसर्गानुगुणं शोधनी ।',
'allmessages-language'          => 'भाषा:',
'allmessages-filter-submit'     => 'गम्यताम्',

# Thumbnails
'thumbnail-more'           => 'विस्तीर्यताम्',
'filemissing'              => 'सञ्चिका विनष्टा ।',
'thumbnail_error'          => 'सङ्कुचितचित्रनिर्माणे दोषः: $1',
'djvu_page_error'          => 'DjVu पुटं  पृष्ठ परिधेः बहिः ।',
'djvu_no_xml'              => 'DjVu पुटार्थं XMLप्राप्तुं न शक्तम् ।',
'thumbnail-temp-create'    => 'अनित्यां सङ्कुचितसञ्चिकां निर्मातुं न शक्यते ।',
'thumbnail-dest-create'    => 'लक्ष्ये सङ्कुचितं रक्षितुं न शक्यते ।',
'thumbnail_invalid_params' => 'सङ्कुचितस्य विस्तारः अमान्यः ।',
'thumbnail_dest_directory' => 'लक्षस्य निदेशिकां सृष्टुं नैव शक्यते ।',
'thumbnail_image-type'     => 'चित्रस्य प्रकारः नानुमोदितः ।',
'thumbnail_gd-library'     => 'अपूर्णं जि.जि.ग्रन्थालयानुन्यासः : विनष्टः कार्यकलापः $1',
'thumbnail_image-missing'  => 'सञ्चिका विनष्टा इति भाति : $1',

# Special:Import
'import'                     => 'पृष्ठानां निर्यातं करोतु',
'importinterwiki'            => 'ट्रान्स् विकि आयातकाः',
'import-interwiki-text'      => 'आयातं कर्तुं एकां विकिं एकं पुटं चिनोतु ।  
पुनरावृत्तीनां दिनाङ्कानि, सम्पादनानि च सुरक्षितानि भविष्यन्ति। 
सर्वाः ट्रान्सविक्यायातक्रियाः नामाभिलेखिताः [[Special:Log/import|आयातसूचिकासु]] स्थापिताः ।',
'import-interwiki-source'    => 'स्रोतविकि/पुटम्',
'import-interwiki-history'   => 'एतत्पुटार्थं सर्वेतिहासान् पुनरावृत्तीः च प्रकृतीः करोतु ।',
'import-interwiki-templates' => 'प्राकृतीः अनर्भावयतु ।',
'import-interwiki-submit'    => 'आयातं करोतु ।',
'import-interwiki-namespace' => 'लक्षितनामस्थानानि ।',
'import-upload-filename'     => 'सञ्चिकानाम',
'import-comment'             => 'टिप्पणी:',
'importtext'                 => '[[Special:Export|export utility]] एतेनानुबन्धेन स्रोतविकितः सञ्चिकानां निर्यातं करोतु । भवदीयसङ्गणके सुरक्ष्य अत्र उत्तारयतु ।',
'importstart'                => 'पुटानाम् आयातः....',
'import-revision-count'      => '$1 {{PLURAL:$1|पुनरावृत्तिः}}',
'importnopages'              => 'आयातं कर्तुं पुटानि न सन्ति ।',
'imported-log-entries'       => 'आयातकृतम्$1{{PLURAL:$1|log entry|प्रवेशसूचिकाः}}.',
'importfailed'               => 'असफलायाताः : $1',
'importunknownsource'        => 'अज्ञातायातस्रोतप्रकारः ।',
'importcantopen'             => 'आयातसञ्चिकाः उद्घाटयितुं न शक्यते ।',
'importbadinterwiki'         => 'प्रदुष्टः अन्तर्विक्यनुबन्धः ।',
'importnotext'               => 'रिक्तम् अथवा पाठः नास्ति ।',
'importsuccess'              => 'आयातः समाप्तः ।',
'importhistoryconflict'      => 'उपलब्धाः इतिहासपुनरावृत्तयः परस्परं विपरीताः। (एतत्पुटं पूर्वमेव आयातम् इति भाति ।)',
'importnosources'            => 'कोऽपि ट्रान्स्विकि आयातः नोपलब्धः अपि च प्रत्यक्षेतिहासस्य उत्तारणं निष्कियम् ।',
'importnofile'               => 'कापि आयातसञ्चिका उत्तारिता ।',
'importuploaderrorsize'      => 'आयातसञ्चिकाः अनुत्तारिताः। अस्याः आकारः अधिकतरः अस्ति ।',
'importuploaderrorpartial'   => 'आयातसञ्चिकाः अनुत्तारिताः । सञ्चिकाः अपूर्णोत्तारिताः ।',
'importuploaderrortemp'      => 'अयातसञ्चिकानाम् उत्तारणम् असफलम् ।
अनित्यः सम्पुटः विनष्टः ।',
'import-parse-failure'       => 'XML आयातस्य व्यवस्थायाः वैफल्यम् ।',
'import-noarticle'           => 'आयातं कर्तुं पुटानि न सन्ति ।',
'import-nonewrevisions'      => 'सर्वाः पुनरावृत्तयः पूर्वमेव आयाताः ।',
'xml-error-string'           => '$1 पङ्किः $2 इत्यस्मिन् , स्तम्भः $3 (बैट्स् $4): $5',
'import-upload'              => 'XML पाठान् उत्तारयतु ।',
'import-token-mismatch'      => 'सत्रस्य पाठानां नाशः ।
पुनः प्रयतताम् ।',
'import-invalid-interwiki'   => 'निर्दिष्टविकितः आयातः न सम्भवति ।',
'import-error-edit'          => '" $1 "पुटस्य आयातः न शक्यते यतः तस्य सम्पादनुमति ते नास्ति ।',
'import-error-create'        => '" $1 "पुटस्य आयातः न शक्यते यतः ते सम्पादनस्य अनुमतिः नास्ति ।',
'import-error-interwiki'     => '"$1" पुटम् आयातं न यतः अस्य नाम बाह्यानुबन्धार्थं सुरक्षितम् । (अन्तर्विकि)',
'import-error-special'       => '"$1" पुटम् आयातं नैव यतः एतत् विशेषनामस्थानेन सम्बद्धं यत् अन्यपुटानि नानुमन्यते ।',
'import-error-invalid'       => '"$1" पुटं न आयातं यतः अस्य नाम अमान्यम् ।',

# Import log
'importlogpage'                    => 'आयातसूचिका ।',
'importlogpagetext'                => 'अन्यविकितः सम्पादितेतिहाससहितानि प्रशासकानाम् आयातपुटानि ।',
'import-logentry-upload'           => 'सञ्चिकाम् उत्तारयित्वा [[$1]] इत्यस्य आयातः कृतः ।',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|पुनरावृत्तिः}}',
'import-logentry-interwiki'        => 'ट्रान्स्विकिकृतम् ।$1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|पुनरावृत्तिः}} $2 इत्येतस्मात् ।',

# JavaScriptTest
'javascripttest'                           => 'जावालिपिपरीक्षणम् ।',
'javascripttest-disabled'                  => 'विक्याम् अयं क्रियाकलापः निष्क्रियः ।',
'javascripttest-title'                     => '$1 परीक्षाप्रचलति ।',
'javascripttest-pagetext-noframework'      => 'जावलिपिचालनपरीक्षार्थम् एतत्पुटम् आरक्षितम् ।',
'javascripttest-pagetext-unknownframework' => 'अज्ञातपरीक्षाप्रक्रिया  $1',
'javascripttest-pagetext-frameworks'       => 'अधो दत्तेषु कञ्चिदेकां परीक्षाप्रक्रियां चिनोतु : $1',
'javascripttest-pagetext-skins'            => 'अनेन सह परीक्षां सञ्चालयितुं  काचित् त्वक् चिनोतु ।',
'javascripttest-qunit-intro'               => 'mediawiki.org. [$1 अभिलेखपरीक्षा] इत्यत्र पश्यतु ।',
'javascripttest-qunit-heading'             => 'मिडियाविक्याः जवालिपेः Qघटकस्य परीक्षाप्रणाली ।',

# Tooltip help for the actions
'tooltip-pt-userpage'                 => 'भवतः योजकपृष्ठम्',
'tooltip-pt-anonuserpage'             => 'ऐपिसङ्केतार्थं योजकपुटं भवान् सम्पादयति एवम्..',
'tooltip-pt-mytalk'                   => 'भवतः सम्भाषणपृष्ठम्',
'tooltip-pt-anontalk'                 => 'एतस्मात् ऐपिसङ्केतात् सम्पादनस्य परिचर्चा ।',
'tooltip-pt-preferences'              => 'भवतः इष्टतमानि',
'tooltip-pt-watchlist'                => 'भवद्भिः परिवर्तनानि निरीक्ष्यमाणानां पृष्ठानां सूची',
'tooltip-pt-mycontris'                => 'भवतः योगदानानाम् आवली',
'tooltip-pt-login'                    => 'भवान् न प्रविष्टः। प्रवेशः अनिवार्यः न।',
'tooltip-pt-anonlogin'                => 'भवतः नामाभिलेखः उत्साहयते । किन्तु नामाभिलेखः ऐच्छिकः ।',
'tooltip-pt-logout'                   => 'निर्गमनम्',
'tooltip-ca-talk'                     => 'पृष्ठान्तर्गतविषये चर्चा',
'tooltip-ca-edit'                     => 'भवान् इदं पृष्ठं सम्पादयितुम् अर्हति। रक्षणात्पूर्वं कृपया प्राग्दृश्यं पश्यतु।',
'tooltip-ca-addsection'               => 'नूतनः विभागः आरभ्यताम्',
'tooltip-ca-viewsource'               => 'इदं पृष्ठं संरक्षितं विद्यते। भवान् अस्य स्रोतः द्रष्टुम् अर्हति।',
'tooltip-ca-history'                  => 'अस्य पृष्ठस्य पुरातन्यः आवृत्तयः',
'tooltip-ca-protect'                  => 'इदं पृष्ठं संरक्ष्यताम्',
'tooltip-ca-unprotect'                => 'अस्य पुटास्य सुरक्षां परिवर्तयतु ।',
'tooltip-ca-delete'                   => 'इदं पृष्ठम् अपाक्रियताम्',
'tooltip-ca-undelete'                 => 'अस्य पुटस्य अपमर्जनात् पूर्वम् अस्य सम्पादनानि पुनस्थापयतु ।',
'tooltip-ca-move'                     => 'इदं पृष्ठं चाल्यताम्',
'tooltip-ca-watch'                    => 'इदं पृष्ठं भवतः अवेक्षणसूच्यां योज्यताम्',
'tooltip-ca-unwatch'                  => 'इदं पृष्ठं भवतः अवेक्षणसूच्याः निष्कास्यताम्',
'tooltip-search'                      => '{{SITENAME}} अत्र अन्विष्यताम्',
'tooltip-search-go'                   => 'समानशिरोनामयुक्तं पृष्ठं विद्यते चेत् तत्र गम्यताम्',
'tooltip-search-fulltext'             => 'इदं वचनं पृष्ठेषु अन्विष्यताम्',
'tooltip-p-logo'                      => 'मुख्यपृष्ठं गम्यताम्',
'tooltip-n-mainpage'                  => 'मुख्यपृष्ठं गम्यताम्',
'tooltip-n-mainpage-description'      => 'मुख्यपृष्ठं गम्यताम्',
'tooltip-n-portal'                    => 'प्रकल्पविषये भवता किं कर्तुं शक्यं, कुत्र अन्वेषणं शक्यम्',
'tooltip-n-currentevents'             => 'सद्यःकालीनघटनानां पृष्ठभूमिका प्राप्यताम्',
'tooltip-n-recentchanges'             => 'सद्योजातानां परिवर्तनानां सूची',
'tooltip-n-randompage'                => 'किमप्येकं पृष्ठं गम्यताम्',
'tooltip-n-help'                      => 'अन्वेषणस्थानम्',
'tooltip-t-whatlinkshere'             => 'एतत्सम्बद्धानां सर्वेषां विकि-पृष्ठानां सूची',
'tooltip-t-recentchangeslinked'       => 'एतत्सम्बद्धेषु पृष्ठेषु जातानि सद्यःकालीनानि परिवर्तनानि',
'tooltip-feed-rss'                    => 'अस्मै पृष्ठाय आर-एस-एस-पूरणम्',
'tooltip-feed-atom'                   => 'अस्मै पृष्ठाय अणुपूरणम्',
'tooltip-t-contributions'             => 'अस्य योजकस्य योगदानानाम् आवलिः',
'tooltip-t-emailuser'                 => 'एतस्मै योजकाय ईपत्रं प्रेष्यताम्',
'tooltip-t-upload'                    => 'संचिकाः आरोप्यन्ताम्',
'tooltip-t-specialpages'              => 'सर्वेषां विशिष्टपृष्ठानां सूची',
'tooltip-t-print'                     => 'अस्य पृष्ठस्य मुद्रणयोग्या आवृत्तिः',
'tooltip-t-permalink'                 => 'पृष्ठस्य अस्याः आवृत्तेः स्थिरसम्पर्कतन्तुः',
'tooltip-ca-nstab-main'               => 'आन्तर्यं दृश्यताम्',
'tooltip-ca-nstab-user'               => 'योजकपृष्ठं दृश्यताम्',
'tooltip-ca-nstab-media'              => 'माध्यमपुटम् अवलोकयतु ।',
'tooltip-ca-nstab-special'            => 'इदमेकं विशिष्टं पृष्ठम्, भवान् इदं पृष्ठं सम्पादयितुं न अर्हति।',
'tooltip-ca-nstab-project'            => 'प्रकल्पपृष्ठं दृश्यताम्',
'tooltip-ca-nstab-image'              => 'सञ्चिकापृष्ठं दृश्यताम्',
'tooltip-ca-nstab-mediawiki'          => 'तन्त्रसन्देशान् अवलोकयतु ।',
'tooltip-ca-nstab-template'           => 'फलकं दृश्यताम्',
'tooltip-ca-nstab-help'               => 'साहाय्यपुटम् अवलोकयतु ।',
'tooltip-ca-nstab-category'           => 'वर्गाणां पृष्ठं दृश्यताम्',
'tooltip-minoredit'                   => 'इदं परिवर्तनं लघुपरिवर्तनरूपेण अङ्क्यताम्',
'tooltip-save'                        => 'परिवर्तनानि रक्ष्यन्ताम्',
'tooltip-preview'                     => 'भवता कृतानां परिवर्तनानां प्राग्दृश्यं दृश्यताम्, रक्षणात्पूर्वं कृपया इदम् उपयुज्यताम्।',
'tooltip-diff'                        => 'पाठे भवता कृतानि परिवर्तनानि दृश्यन्ताम्।',
'tooltip-compareselectedversions'     => 'पृष्ठस्य द्वयोः चितयोः आवृत्त्योः भेदः दृश्यताम्',
'tooltip-watch'                       => 'इदं पृष्ठं भवतः अवेक्षणसूच्यां योज्यताम्',
'tooltip-watchlistedit-normal-submit' => 'शीर्षकानि अपनयतु ।',
'tooltip-watchlistedit-raw-submit'    => 'अवलोकनावलीं समुद्धरतु ।',
'tooltip-recreate'                    => 'एतत्पुटं पूर्वमेव अपमर्जितः अतः पुन सृजतु ।',
'tooltip-upload'                      => 'उत्तारणम् आरभताम्',
'tooltip-rollback'                    => '"पूर्ण-प्रतिगमनं(रोलबैक् इत्येतद्)" अस्य पृष्ठस्य संपादनानि अंतिम-योगदातृकृतानि विपरीतीकरोति एकेन क्लिक्कारेण',
'tooltip-undo'                        => '"निष्क्रियताम्" इत्येतद् इदं सम्पादनं विपरीतीकरोति, तथा च सम्पादनप्रारूपं प्राग्दृश्यरूपेण उद्घाटयति।

अस्य सारांशे कारणमपि लेखितुं शक्यते।',
'tooltip-preferences-save'            => 'आद्यताः रक्षतु ।',
'tooltip-summary'                     => 'संक्षिप्तः सारांशः योज्यताम्',

# Metadata
'notacceptable' => 'भवतः ग्रहकस्य पठनेच्छारूपेण विकिवितारकः दत्तपाठं प्रकल्पितुं नैव शक्नोति ।',

# Attribution
'anonymous'        => '{{SITENAME}} इत्यस्य {{PLURAL:$1||}} अनामकयोजकः ।',
'siteuser'         => '{{SITENAME}} योजक $1',
'anonuser'         => '{{SITENAME}} अज्ञात योजक $1',
'lastmodifiedatby' => 'एतस्य पुटस्य अन्तिमपरिवर्तनं $1 दिनाङ्के $2 समये कृतम् ।',
'othercontribs'    => '$1 इत्यस्य कार्यस्य अनुसारम् ।',
'others'           => 'अन्य',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|योजक|योजक}} $1',
'anonusers'        => '{{SITENAME}} अज्ञात {{PLURAL:$2|योजक|योजक}} $1',
'creditspage'      => 'पुटनां समाकलनानि ।',
'nocredits'        => 'अस्य पुटस्य समाकलनसूचना नोपलब्धा ।',

# Spam protection
'spamprotectiontitle' => 'स्पाम् सुरक्षाशोधनी ।',
'spamprotectiontext'  => 'भवान् यं पाठं रक्षितुमिच्छति सः स्पाम् शोधन्या अवरुद्धः । 
प्रायः एतत् निषिद्धबाह्यस्थानस्य अनुबन्धेन सम्भूतम् ।',
'spamprotectionmatch' => 'अधस्थपाठेन अस्माकं स्फांशोधनी लक्षिता : $1',
'spambot_username'    => 'मिडियाविकिअवकरशुद्धीकरणम् ।',
'spam_reverting'      => '$1 इत्यनेन नानुबद्धनां प्राचीनपुनरावृत्तीनां पुनस्थापनं कुर्वन्ति ।',
'spam_blanking'       => 'सर्वाः पुनरावृत्तयः $1 इत्यस्य अनुबन्धाः पूर्णपाठाः अपनीयन्ते ।',

# Info page
'pageinfo-title'            => '"$1" कृते सूचनाः ।',
'pageinfo-header-edits'     => 'इतिहासः सम्पाद्यताम्',
'pageinfo-header-watchlist' => 'अवलोकनावली ।',
'pageinfo-header-views'     => 'अवलोकनानि ।',
'pageinfo-subjectpage'      => 'पृष्ठम्',
'pageinfo-talkpage'         => 'कथा पृष्ठम्',
'pageinfo-watchers'         => 'पृष्ठावलोककानां सङ्ख्या ।',
'pageinfo-edits'            => 'सम्पादननां सङ्ख्या ।',
'pageinfo-authors'          => 'प्रत्येककर्तॄणां समग्रा सङ्ख्या ।',
'pageinfo-views'            => 'अवलोकनानां सङ्ख्या ।',
'pageinfo-viewsperedit'     => 'प्रत्येकं सम्पादनस्य अवलोकनानि ।',

# Skin names
'skinname-standard'    => 'पूर्व',
'skinname-nostalgia'   => 'पुराण',
'skinname-cologneblue' => 'नील',
'skinname-monobook'    => 'पुस्तक',
'skinname-myskin'      => 'मे चर्मन्',
'skinname-chick'       => 'Chick',

# Patrolling
'markaspatrolleddiff'                 => 'आरक्षितमिति अङ्कयतु ।',
'markaspatrolledtext'                 => 'एतपुटम् आरक्षितमिति अङ्कयतु ।',
'markedaspatrolled'                   => 'आरक्षितमिति अङ्कयतु ।',
'markedaspatrolledtext'               => '[[:$1]] इत्यस्य चितपुनरावृत्तिः आरक्षणचिह्निता अस्ति ।',
'rcpatroldisabled'                    => 'सद्यः परिवर्तननानि आरक्षणं निष्क्रियम् ।',
'rcpatroldisabledtext'                => 'नूतनपरिवर्ननानाम् आरक्षणव्यवस्था सद्यः निष्क्रिया ।',
'markedaspatrollederror'              => 'आरक्षितमिति अङ्कितं न भवति ।',
'markedaspatrollederrortext'          => 'आरक्षितमिति सूचयितुं पुनरावृत्तिं विशेषयतु ।',
'markedaspatrollederror-noautopatrol' => 'स्वस्य परिवर्तनानि आरक्षितं कर्तुं भवान् नानुमतः ।',

# Patrol log
'patrol-log-page'      => 'आरक्षणसूचिका ।',
'patrol-log-header'    => 'इयम् आरक्षितपुनरावृत्तीनां सूचिका अस्ति ।',
'log-show-hide-patrol' => '$1 इत्यस्य आरक्षणसूचिका ।',

# Image deletion
'deletedrevision'                 => 'अपमर्जितप्राचीनपुनरावृत्तिः $1',
'filedeleteerror-short'           => 'सञ्चिकानपमर्जने दोषः : $1',
'filedeleteerror-long'            => ' सञ्चिकानामपमर्जने आगता समस्या  $1',
'filedelete-missing'              => '"$1" सञ्चिका अनपमर्जनीया यतः एषा न वर्तते एव ।',
'filedelete-old-unregistered'     => 'दत्तपाठे $1 इति विशिष्टा पुनरावृत्तिः नास्ति ।',
'filedelete-current-unregistered' => '"$1" विशिष्टा सञ्चिका दत्तपाठे नास्ति ।',
'filedelete-archive-read-only'    => '"$1" लेखागारास्य निदेशिका जालवितारकेन अलेख्या अस्ति ।',

# Browsing diffs
'previousdiff' => '← पुरातनतरं सम्पादनम्',
'nextdiff'     => 'नवतरं सम्पादनम् →',

# Media information
'mediawarning'           => 'पूर्वसूचना : प्रायः एतादृशपुटे अनर्थसङ्केतः भवति ।
एतदनुष्टाने अनीय भवतः तन्त्रव्यवस्था सदोषा स्यात् ।',
'imagemaxsize'           => "चित्राकरस् परिमितिः :<br />''(सञ्चिकाविविरणपुटार्थम्)''",
'thumbsize'              => 'सङ्कुचितास्य आकारः ।',
'widthheightpage'        => '$1 × $2, $3 {{PLURAL:$1|पुटम्|पुटानि}} प्रयुक्तानि ।',
'file-info'              => 'सञ्चिकाकारः : $1, MIME प्रकारः $2',
'file-info-size'         => '$1 × $2 पिक्सेलानि, संचिकायाः आकारः: $3, MIME-प्रकारः: $4',
'file-info-size-pages'   => '$1 × $2  पिक्सेल्, सञ्चिकायाः आकारः :  $3 , MIME प्रकारः :  $4 ,  $5   {{PLURAL:$5|पुटम्|पुटानि}}',
'file-nohires'           => 'उच्चतरं विभेदनं नोपलब्धम्',
'svg-long-desc'          => 'SVG संचिका, साधारणतया $1 × $2 पिक्सेलानि, संचिकायाः आकारः : $3',
'show-big-image'         => 'पूर्णं विभेदनम्',
'show-big-image-preview' => 'अस्य पूर्वावलोकनस्य आकारः : $1',
'show-big-image-other'   => 'अन्याः {{PLURAL:$2| प्रस्तवः|प्रस्तावाः}}:  $1 ।',
'show-big-image-size'    => '$1 × $2  पिक्सेल्',
'file-info-gif-looped'   => 'चक्रितम्',
'file-info-gif-frames'   => '$1 {{PLURAL:$1|पृष्ठम्|पृष्ठानि}}',
'file-info-png-looped'   => 'चक्रितम्',
'file-info-png-repeat'   => 'विलसितम् $1   {{PLURAL:$1|समयः|समयाः}}',
'file-info-png-frames'   => '$1 {{PLURAL:$1|पृष्ठम्|पृष्ठानि}}',

# Special:NewFiles
'newimages'             => 'नूतन-संचिकानां वीथिका',
'imagelisttext'         => "अधः $2 इत्यनुसारं '''$1''' {{PLURAL:$1|सञ्चिका दत्ता|सञ्चिकाः प्रदत्ता।}}",
'newimages-summary'     => 'एतत् विशेषपुटम् सद्यः उत्तारितसञ्चिकाः दर्शयति ।',
'newimages-legend'      => 'शोधनी ।',
'newimages-label'       => 'सञ्चिकानाम (अथवा अस्य भागः)',
'showhidebots'          => '(स्वयं चालकः $1)',
'noimages'              => 'शून्यदर्शनम् ।',
'ilsubmit'              => 'अन्वेषणम्',
'bydate'                => 'दिनाङ्कानुगुणम्',
'sp-newimages-showfrom' => ' $2 $1 तः आरब्धाः सञ्चिकाः दर्शयतु ।',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 second|$1 seconds}}',
'minutes' => '{{PLURAL:$1|$1 निमेषः|$1 निमेषाः}}',
'hours'   => '{{PLURAL:$1|$1होरा|$1 होराः}}',
'days'    => '{{PLURAL:$1|$1 दिनम्|$1 दिनानि}}',
'ago'     => '$1 पूर्वम्',

# Bad image list
'bad_image_list' => 'रूपम् एवम् अस्ति -

केवलं सूच्यन्तर्गताः विषयाः (* इति चिन्हात् आरभमाणाः पंक्तयः)परामृष्टाः।

प्रथमः सम्पर्कतन्तुः दोषपूर्णां सञ्चिकां प्रत्येव गच्छेत्।
तस्याम् एव पङ्क्तौ उत्तरोत्तरसम्पर्कतन्तवः अपवादाः ज्ञेयाः। अर्थात् येषु पृष्ठेषु एषा सञ्चिका योजिता स्यात्।',

# Metadata
'metadata'          => 'अधिदत्तानि',
'metadata-help'     => 'अस्यां सञ्चिकायाम् अतिरिक्ताः विषयाः सन्ति, कदाचित् आंकिक-छायाचित्रग्राहकेन स्क्यानर् इत्यनेन वा स्रष्टाः वा आंकिकीकृताः वा स्युः ।

यदि एषा सञ्चिका मूलावस्थातः परिवर्तिता अस्ति, तर्हि अत्र कानिचिद् विवरणानि परिवर्तितां संचिकां पूर्णतया न प्रदर्शयेयुः ।',
'metadata-expand'   => 'विस्तारितानि विवरणानि दर्शयतु',
'metadata-collapse' => 'विस्तारितानि विवरणानि लोपयतु',
'metadata-fields'   => 'अस्मिन् तालिकायां दर्शिता सूचना संचिकायाः अधस्तात् मेटाडाटा इत्यस्मिन् सदा दर्शिता भविष्यति।
अवशिष्टा सूचना सदा निगूढा भविष्यति।
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'                  => 'विस्तारः',
'exif-imagelength'                 => 'औन्नत्यम्',
'exif-bitspersample'               => 'प्रत्येकं भागस्य अंशः ।',
'exif-compression'                 => 'तुलनायाः योजना ।',
'exif-photometricinterpretation'   => 'पिक्सेल् रचनम् ।',
'exif-orientation'                 => 'अभिस्थापनम्',
'exif-samplesperpixel'             => 'भागानां सङ्ख्या ।',
'exif-planarconfiguration'         => 'दत्तांशस्य व्यवस्था ।',
'exif-ycbcrsubsampling'            => 'Y इत्यस्य C इत्यनेन सह सबसॅम्पलींग प्रमाणम् ।',
'exif-ycbcrpositioning'            => 'Y तथा C  पोज़िशनिंग',
'exif-xresolution'                 => 'तिर्यक् प्रस्तावः ।',
'exif-yresolution'                 => 'लम्बप्रस्तावः ।',
'exif-stripoffsets'                => 'चित्रदत्तांशस्य स्थानम् ।',
'exif-rowsperstrip'                => 'प्रतिपट्टं स्तम्भानां सङ्ख्या ।',
'exif-stripbytecounts'             => 'सङ्कोचितप्रपट्टं बैट्स् ।',
'exif-jpeginterchangeformat'       => 'Offset to JPEG SOI',
'exif-jpeginterchangeformatlength' => 'जेपिइजि दत्तांशस्य बैट्स् ।',
'exif-whitepoint'                  => 'श्वेतबिन्दुवर्णगुणः ।',
'exif-primarychromaticities'       => 'प्राथमिकस्य वर्णगुणः ।',
'exif-ycbcrcoefficients'           => 'वर्णाकाशस्य वर्गान्तरम् मॅट्रीक्स कोएफिशीयंट्स्',
'exif-referenceblackwhite'         => 'उल्लेखमौल्यस्य श्वेतकृष्णयुगम् ।',
'exif-datetime'                    => 'सञ्चिकापरिवर्तनस्य दिनाङ्कः समयः च ।',
'exif-imagedescription'            => 'चित्रशीर्षकम् ।',
'exif-make'                        => 'चित्रग्राहिण्याः उत्पादकः ।',
'exif-model'                       => 'चित्रग्राहिण्याः स्वरूपम् ।',
'exif-software'                    => 'तन्त्रांशः उपयुक्तः ।',
'exif-artist'                      => 'लेखक',
'exif-copyright'                   => 'स्वामित्वस्य धारकः ।',
'exif-exifversion'                 => 'Exif आवृत्तिः ।',
'exif-flashpixversion'             => 'अनुमोदिता फ्लाश्पिक्स् आवृत्तिः ।',
'exif-colorspace'                  => 'वर्णावकाशः ।',
'exif-componentsconfiguration'     => 'प्रत्येकं भागस्य अर्थः ।',
'exif-compressedbitsperpixel'      => 'चित्रसङ्कोचविधानम् ।',
'exif-pixelydimension'             => 'चित्रविस्तारः ।',
'exif-pixelxdimension'             => 'चित्रैन्नत्यम् ।',
'exif-usercomment'                 => 'योजकाभिप्रायः ।',
'exif-relatedsoundfile'            => 'सम्बद्धश्रवणसञ्चिका ।',
'exif-datetimeoriginal'            => 'दत्तांशसर्जनस्य दिनाङ्कः समयः च ।',
'exif-datetimedigitized'           => 'अङ्कीकरणस्य दिनाङ्कः समयः च ।',
'exif-subsectime'                  => 'उपक्षणानां दिनाङ्कः समयः च ।',
'exif-subsectimeoriginal'          => 'मूलोपक्षणानां दिनाङ्कः समयः च ।',
'exif-subsectimedigitized'         => 'अङ्कीकृतोपक्षणानां दिनाङ्कः समयः च ।',
'exif-exposuretime'                => 'विगोपनसमयः ।',
'exif-exposuretime-format'         => '$1 क्षणः ($2)',
'exif-fnumber'                     => 'F सङ्ख्या',
'exif-exposureprogram'             => 'विगोपनकार्यक्रमः',
'exif-spectralsensitivity'         => 'सङ्घट्टनसंवेदनशीलता ।',
'exif-isospeedratings'             => 'ISO वेगतुलना ।',
'exif-shutterspeedvalue'           => 'APEX पिधानस्य वेगः ।',
'exif-aperturevalue'               => 'APEX रन्ध्रः ।',
'exif-brightnessvalue'             => 'APEX कान्तिः ।',
'exif-exposurebiasvalue'           => 'APEX विगोपनाधारः ।',
'exif-maxaperturevalue'            => 'गरिष्टभूरन्ध्रः ।',
'exif-subjectdistance'             => 'विषयान्तरम् ।',
'exif-meteringmode'                => 'मापनस्य विधानम् ।',
'exif-lightsource'                 => 'प्रकाशमूलम् ।',
'exif-flash'                       => 'स्फुरणम्',
'exif-focallength'                 => 'काचपटलस्य दैर्घ्यम् ।',
'exif-subjectarea'                 => 'विषयक्षेत्रम् ।',
'exif-flashenergy'                 => 'स्फुरणशक्तिः ।',
'exif-focalplanexresolution'       => 'मध्यकेन्द्रविमान X प्रस्तावः ।',
'exif-focalplaneyresolution'       => 'मध्यकेन्द्रविमानस्य Y प्रस्तावः ।',
'exif-focalplaneresolutionunit'    => 'मध्यकेन्द्रस्य विमानप्रस्तावस्य घटकः ।',
'exif-subjectlocation'             => 'विष्यस्थानम् ।',
'exif-exposureindex'               => 'विगोपनस्य अनुक्रमणी',
'exif-sensingmethod'               => 'संवेदशीलक्रमः ।',
'exif-filesource'                  => 'सञ्चिकास्रोतः ।',
'exif-scenetype'                   => 'संवेदनप्रकारः ।',
'exif-customrendered'              => 'चित्रविन्यासं परिवर्तयतु ।',
'exif-exposuremode'                => 'विगोपनस्य विधानम् ।',
'exif-whitebalance'                => 'श्वेतावकाशः ।',
'exif-digitalzoomratio'            => 'आङ्किकविस्तारकप्रमाणः ।',
'exif-focallengthin35mmfilm'       => 'मध्यकेन्द्रस्य दैर्घ्यं ३५मि.मी. पटले भवति ।',
'exif-scenecapturetype'            => 'दृश्यग्रहणविधानम् ।',
'exif-gaincontrol'                 => 'दृश्यनियन्त्रणम् ।',
'exif-contrast'                    => 'सङ्कोचनम् ।',
'exif-saturation'                  => 'तर्पणम् ।',
'exif-sharpness'                   => 'नैशित्यम् ।',
'exif-devicesettingdescription'    => 'उपकरणव्यवस्थापनस्य विवरणम् ।',
'exif-subjectdistancerange'        => 'विषयन्तरवलयः ।',
'exif-imageuniqueid'               => 'विशिष्टं चित्रचिह्नम् ।',
'exif-gpsversionid'                => 'GPS लग्नावृत्तिः ।',
'exif-gpslatituderef'              => 'उत्तरस्य अथवा दक्षिणस्य अक्षांशः ।',
'exif-gpslatitude'                 => 'अक्षांशः ।',
'exif-gpslongituderef'             => 'पूर्वस्य अथवा पश्चिमस्य अक्षांशः ।',
'exif-gpslongitude'                => 'रेखांशः',
'exif-gpsaltituderef'              => 'आरोहस्य उल्लेखः ।',
'exif-gpsaltitude'                 => 'उन्मितिः',
'exif-gpstimestamp'                => 'GPS समयः (एटोमिक क्लॉक)',
'exif-gpssatellites'               => 'मापनार्थम् उपयुक्ताः उपग्रहाः ।',
'exif-gpsstatus'                   => 'स्वीकर्तुः स्थितिः ।',
'exif-gpsmeasuremode'              => 'मापनस्य विधानम् ।',
'exif-gpsdop'                      => 'मापनस्य यथार्थता ।',
'exif-gpsspeedref'                 => 'गती एकक',
'exif-gpsspeed'                    => 'जिपिएस् ग्राहकस्य वेगः ।',
'exif-gpstrackref'                 => 'सञ्चालनस्य निदेशार्थम् उल्लेखः ।',
'exif-gpstrack'                    => 'सञ्चालनस्य निदेशः ।',
'exif-gpsimgdirectionref'          => 'सञ्चालनस्य निदेशार्थम् उल्लेखः ।',
'exif-gpsimgdirection'             => 'चित्रस्य निदेशः ।',
'exif-gpsmapdatum'                 => 'जियोडेटिक्  सर्वेक्षणोपयुक्तः दत्तांशः ।',
'exif-gpsdestlatituderef'          => 'लक्ष्याक्षांशस्य उल्लेखः ।',
'exif-gpsdestlatitude'             => 'अक्षांशस्य लक्ष्यम् ।',
'exif-gpsdestlongituderef'         => 'लक्ष्यस्य रेखांशस्य उल्लेखः ।',
'exif-gpsdestlongitude'            => 'लक्ष्यस्य रेखांशः ।',
'exif-gpsdestbearingref'           => 'लक्ष्यस्य स्वभावार्थमुल्लेखः ।',
'exif-gpsdestbearing'              => 'लक्ष्यस्य स्वभावः ।',
'exif-gpsdestdistanceref'          => 'लक्षान्तरस्य कृते उल्लेखः ।',
'exif-gpsdestdistance'             => 'लक्ष्यान्तरम् ।',
'exif-gpsprocessingmethod'         => 'जिपिएस् प्रक्रियायाः क्रमस्य नाम ।',
'exif-gpsareainformation'          => 'जिपिएस् क्षेत्रस्य नाम ।',
'exif-gpsdatestamp'                => 'जिपिएस् महाद्वारम् ।',
'exif-gpsdifferential'             => 'जिपिएस् व्यत्यासस्य परिष्कारः ।',
'exif-jpegfilecomment'             => 'जिपिइजि सञ्चिकाटीका ।',
'exif-keywords'                    => 'कुञ्चपदानि ।',
'exif-worldregioncreated'          => 'चित्रग्राहस्य वैश्विकप्रदेशः ।',
'exif-countrycreated'              => 'चित्रग्राहस्य देशीयप्रदेशः ।',
'exif-countrycodecreated'          => 'चित्रग्राहस्य देशस्य सङ्केतसङ्ख्या ।',
'exif-provinceorstatecreated'      => 'चित्रग्राहस्य राज्यस्य प्रदेशः ।',
'exif-citycreated'                 => 'चित्रग्राहस्य नगरप्रदेशः ।',
'exif-sublocationcreated'          => 'चित्रग्रहस्य उपनगरप्रदेशः ।',
'exif-worldregiondest'             => 'वैश्विकप्रदेशः दर्शितः ।',
'exif-countrydest'                 => 'नगरं दर्शितम् ।',
'exif-countrycodedest'             => 'दर्शितनगरस्य सङ्केतसङ्ख्या ।',
'exif-provinceorstatedest'         => 'दर्शितं राज्यम् ।',
'exif-citydest'                    => 'दर्शितं नगरम् ।',
'exif-sublocationdest'             => 'दर्शितः नगरस्य उपभागः ।',
'exif-objectname'                  => 'ह्रस्वशीर्षकम् ।',
'exif-specialinstructions'         => 'विशेषसूचनाः ।',
'exif-headline'                    => 'शीर्षकम् ।',
'exif-credit'                      => 'श्रेयः/ प्रदाता',
'exif-source'                      => 'मूल',
'exif-editstatus'                  => 'चित्रस्य सम्पादस्थितिः ।',
'exif-urgency'                     => 'त्वरा',
'exif-fixtureidentifier'           => 'सङ्गमनाम ।',
'exif-locationdest'                => 'स्थानं चित्रितम् ।',
'exif-locationdestcode'            => 'चित्रितस्थानस्य सङ्केतसङ्ख्या ।',
'exif-objectcycle'                 => 'माध्यमगम्यः दिनस्य समयः ।',
'exif-contact'                     => 'सम्पर्कस्य सूचनाः ।',
'exif-writer'                      => 'लेखकः ।',
'exif-languagecode'                => 'भाषा ।',
'exif-iimversion'                  => 'IIM आवृत्तिः ।',
'exif-iimcategory'                 => 'वर्ग',
'exif-iimsupplementalcategory'     => 'संयोज्यवर्गः ।',
'exif-datetimeexpires'             => 'पश्चात् न उपयोजयतु ।',
'exif-datetimereleased'            => 'अस्मिन् दिने लोकार्पितम् ।',
'exif-originaltransmissionref'     => 'मूलप्रसरणस्य स्थानसङ्केतसङ्ख्या ।',
'exif-identifier'                  => 'अभिज्ञापकः ।',
'exif-lens'                        => 'उपयुक्तः काछपटलः ।',
'exif-serialnumber'                => 'चित्रग्राह्याः क्रमसङ्ख्या ।',
'exif-cameraownername'             => 'चित्रग्राह्याः स्वामी ।',
'exif-label'                       => 'लक्षः',
'exif-datetimemetadata'            => 'मेटा दत्तांशस्य परिवर्तनस्य दिनाङ्कः ।',
'exif-nickname'                    => 'चित्रस्य साधारणं नाम ।',
'exif-rating'                      => 'तुलना ।(पञ्चसु)',
'exif-rightscertificate'           => 'अधिकरनिर्वहणस्य प्रमाणपत्रम् ।',
'exif-copyrighted'                 => 'प्रतिकृत्यधिकारस्य स्थितिः ।',
'exif-copyrightowner'              => 'स्वामित्वस्य धारकः ।',
'exif-usageterms'                  => 'उपयोगस्य नियमाः ।',
'exif-webstatement'                => 'सद्यस्कस्य स्वामित्वस्य वृत्तम् ।',
'exif-originaldocumentid'          => 'मूलप्रलेखस्य विशिष्टाभिज्ञानसङ्केतः ।',
'exif-licenseurl'                  => 'अनुज्ञापत्रस्य स्वामित्वस्य कृते URL ।',
'exif-morepermissionsurl'          => 'पर्यायानज्ञापत्रीकरणस्य सूचनाः ।',
'exif-attributionurl'              => 'यदा एतस्य पु्नरुपयोगं करोति तदा अनेन अनुबद्नातु ।',
'exif-preferredattributionname'    => 'यदा एतत्कार्यं पुनरुपयोजति तदा समाकलयतु ।',
'exif-pngfilecomment'              => 'जिपिइजि सञ्चिकाटीका ।',
'exif-disclaimer'                  => 'प्रत्याख्यानम्',
'exif-contentwarning'              => 'पूर्वसूचना विषयः ।',
'exif-giffilecomment'              => 'GIF सञ्चिकायाः टीका ।',
'exif-intellectualgenre'           => 'वस्तुनः प्रकारः ।',
'exif-subjectnewscode'             => 'विषयसङ्केतसङ्ख्या ।',
'exif-scenecode'                   => 'IPTC योजनासङ्केतसङ्ख्या ।',
'exif-event'                       => 'चित्रितघटना ।',
'exif-organisationinimage'         => 'चित्रितसङ्घटनम् ।',
'exif-personinimage'               => 'चित्रितजनः ।',
'exif-originalimageheight'         => 'कर्तनात्पूरव चित्रस्य औन्नत्यम् ।',
'exif-originalimagewidth'          => 'कर्तनात् पूर्वं चित्रस्य व्यासः ।',

# EXIF attributes
'exif-compression-1' => 'असङ्कोचितम् ।',
'exif-compression-2' => 'CCITT समूहः 3 1- Dimensional Modified Huffman run length encoding',
'exif-compression-3' => 'CCITT समूह ३ फेक्स  सङ्केतीकरणम् ।',
'exif-compression-4' => 'CCITT समूहः३ फेक्स् सङ्केतीरणम् ।',

'exif-copyrighted-true'  => 'स्वामत्वरक्षितम् ।',
'exif-copyrighted-false' => 'सार्जनिकस्थानम् ।',

'exif-unknowndate' => 'अज्ञातदिनाङ्कः ।',

'exif-orientation-1' => 'सामान्य',
'exif-orientation-2' => 'तिर्यक् परिवर्तितम् ।',
'exif-orientation-3' => '१८०° प्ररिभ्रमितम् ।',
'exif-orientation-4' => 'लम्भतया परिवर्तितम् ।',
'exif-orientation-5' => 'CCW ९०° परिभ्रमितम् । अपि च लम्बतया परिवर्तितम् ।',
'exif-orientation-6' => 'CCW ९०° परिभ्रमितम् ।',
'exif-orientation-7' => 'CCW ९०° परिभ्रमितम् । अपि च लम्बतया परिवर्तितम् ।',
'exif-orientation-8' => 'CCW ९०° परिभ्रमितम् ।',

'exif-planarconfiguration-1' => 'विशालं प्रारूपम् ।',
'exif-planarconfiguration-2' => 'पर्यालोचकस्य प्रारूपम् ।',

'exif-colorspace-65535' => 'अक्रमाङ्कितम् ।',

'exif-componentsconfiguration-0' => 'न वर्तते ।',

'exif-exposureprogram-0' => 'न व्याख्यातम् ।',
'exif-exposureprogram-1' => 'मानवीयः ।',
'exif-exposureprogram-2' => 'साधारणकार्यक्रमः ।',
'exif-exposureprogram-3' => 'अवकाशस्य आद्यता ।',
'exif-exposureprogram-4' => 'पिधानस्य आद्यता ।',
'exif-exposureprogram-5' => 'सर्जनात्मकः कार्यक्रमः ।',
'exif-exposureprogram-6' => 'प्रक्रियाकार्यक्रमः ।',
'exif-exposureprogram-7' => 'आलेख्य प्रकारः ।',
'exif-exposureprogram-8' => 'आयतप्रकारः । (आयतचित्राणि पृष्ठभूमिकेन्द्रीकृतनि )',

'exif-subjectdistance-value' => '$1 मीटर्स् ।',

'exif-meteringmode-0'   => 'अज्ञात',
'exif-meteringmode-1'   => 'माध्य',
'exif-meteringmode-2'   => 'केन्द्रभारयुतं सर्वसामान्यम् ।',
'exif-meteringmode-3'   => 'प्रदेशः ।',
'exif-meteringmode-4'   => 'विविधप्रदेशाः ।',
'exif-meteringmode-5'   => 'प्रकारः ।',
'exif-meteringmode-6'   => 'भागशः ।',
'exif-meteringmode-255' => 'अन्यत्',

'exif-lightsource-0'   => 'अज्ञात',
'exif-lightsource-1'   => 'दिवाप्रकाशः ।',
'exif-lightsource-2'   => 'प्रभासमानम् ।',
'exif-lightsource-3'   => 'उज्वलप्रकाशस्य तन्त्रीविशेषः',
'exif-lightsource-4'   => 'स्फुरणम् ।',
'exif-lightsource-9'   => 'सुवायुमण्डलम् ।',
'exif-lightsource-10'  => 'मेघाच्छन्नवायुमण्डलम् ।',
'exif-lightsource-11'  => 'छाया ।',
'exif-lightsource-12'  => 'दिवाप्रकाशः उज्वलकान्तिः ।(D 5700 – 7100K)',
'exif-lightsource-13'  => 'दिनस्य श्वेतोज्वलप्रकाशः (N 4600 – 5400K)',
'exif-lightsource-14'  => 'शान्तशुभ्रः उज्ज्वलप्रकाशः (W 3900 – 4500K)',
'exif-lightsource-15'  => 'श्वेतदीप्तप्रकाशः ।(WW 3200 – 3700K)',
'exif-lightsource-17'  => 'सुयोगः प्रकाशः A',
'exif-lightsource-18'  => 'योग्यप्रकाशः B',
'exif-lightsource-19'  => 'सुयोग्यप्रकाशः C',
'exif-lightsource-24'  => 'ISO स्टूडीयो टङ्गस्टन् ।',
'exif-lightsource-255' => 'अन्यप्रकाशस्रोतः ।',

# Flash modes
'exif-flash-fired-0'    => 'स्फुरणं न सम्भूतम् ।',
'exif-flash-fired-1'    => 'स्फुरणमभवत् ।',
'exif-flash-return-0'   => 'न कोऽपि स्फुरणप्रयागमनस्य शोधकार्यकलापः अस्ति ।',
'exif-flash-return-2'   => 'स्फुरणप्रत्यागमनस्य प्रकाशः न शोधितः ।',
'exif-flash-return-3'   => 'स्फुरणप्रत्यागमनस्य प्रकाशः अभिज्ञातम् ।',
'exif-flash-mode-1'     => 'अनिवार्यं स्फुरणचालनम् ।',
'exif-flash-mode-2'     => 'अनिवर्यः स्फुरणनिग्रहः ।',
'exif-flash-mode-3'     => 'स्वयं चालनप्रकारः ।',
'exif-flash-function-1' => 'स्फुरणकार्यकलापः नास्ति ।',
'exif-flash-redeye-1'   => 'लोहितनेत्र न्यूनीकरणस्य प्रकारः ।',

'exif-focalplaneresolutionunit-2' => 'इञ्चस्',

'exif-sensingmethod-1' => 'अनिरूपितम् ।',
'exif-sensingmethod-2' => 'एकशलाकावर्णस्थानस्य संवेदकः ।',
'exif-sensingmethod-3' => 'शलाकद्वसस्य वर्णस्थानस्य संवेदकः ।',
'exif-sensingmethod-4' => 'शलाकत्रयस्य वर्णस्थानस्य संवेदकः ।',
'exif-sensingmethod-5' => 'वर्णसान्दर्भिकस्थानस्य संवेदकः ।',
'exif-sensingmethod-7' => 'ट्रिलियनियर्  संवेदकः ।',
'exif-sensingmethod-8' => 'वर्णसान्दर्भिकस्थानस्य संवेदकः ।',

'exif-filesource-3' => 'आङ्किकस्थिरचित्रग्राही ।',

'exif-scenetype-1' => 'सक्षात् ग्रहीतचित्रम् ।',

'exif-customrendered-0' => 'साधारणप्रक्रिया ।',
'exif-customrendered-1' => 'प्रक्रियां परिवर्तयतु ।',

'exif-exposuremode-0' => 'स्वयं प्रदर्शनम् ।',
'exif-exposuremode-1' => 'मानवीयं प्रदर्शनम् ।',
'exif-exposuremode-2' => 'स्वयम् आवरणम्',

'exif-whitebalance-0' => 'स्वयं श्वेतावरणम् ।',
'exif-whitebalance-1' => 'मनवकरणस्य श्वेतावरणम् ।',

'exif-scenecapturetype-0' => 'सुयोग्यम् ।',
'exif-scenecapturetype-1' => 'आयतम् ।',
'exif-scenecapturetype-2' => 'लम्बचित्राकृतिः ।',
'exif-scenecapturetype-3' => 'रात्रिदृश्यम् ।',

'exif-gaincontrol-0' => 'नास्ति',
'exif-gaincontrol-1' => 'मन्दार्जनम् ।',
'exif-gaincontrol-2' => 'तीव्रार्जनम् ।',
'exif-gaincontrol-3' => 'मन्दार्जनावन्तिः ।',
'exif-gaincontrol-4' => 'तीव्रार्जनावनतिः ।',

'exif-contrast-0' => 'सामान्य',
'exif-contrast-1' => 'कोमलम् ।',
'exif-contrast-2' => 'कठिणम् ।',

'exif-saturation-0' => 'सामान्यम्',
'exif-saturation-1' => 'मन्दतर्पणम् ।',
'exif-saturation-2' => 'तीव्रतर्पणम् ।',

'exif-sharpness-0' => 'सामान्य',
'exif-sharpness-1' => 'कोमलम् ।',
'exif-sharpness-2' => 'कठिणम् ।',

'exif-subjectdistancerange-0' => 'अज्ञात',
'exif-subjectdistancerange-1' => 'बृहत्',
'exif-subjectdistancerange-2' => 'अवलोकनं पिदधातु ।',
'exif-subjectdistancerange-3' => 'दूरदृश्यम् ।',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'उत्तराक्षांशः ।',
'exif-gpslatitude-s' => 'दक्षिणाक्षांशः ।',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'पश्चिमरेखांशः ।',
'exif-gpslongitude-w' => 'पश्चिमरेखांशः ।',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|meter|meters}} समुद्रस्तरादौन्नत्यम् ।',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|meter|meters}} समुद्रस्तरादवनतिः ।',

'exif-gpsstatus-a' => 'मापनस्य प्रगतिः ।',
'exif-gpsstatus-v' => 'अन्तर्निर्वहणस्य मापनम् ।',

'exif-gpsmeasuremode-2' => 'द्विमुखमापनम् ।',
'exif-gpsmeasuremode-3' => 'त्रिमुखमापनम् ।',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'प्रतिहोरा कि.मी ।',
'exif-gpsspeed-m' => 'मैल् प्रतिहोरा ।',
'exif-gpsspeed-n' => 'ग्रन्थयः ।',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'कि.मी.',
'exif-gpsdestdistance-m' => 'मैल्स् ।',
'exif-gpsdestdistance-n' => 'समुद्रीयः क्रोशः ।',

'exif-gpsdop-excellent' => 'उत्कृष्टम् ($1)',
'exif-gpsdop-good'      => 'साधु ($1)',
'exif-gpsdop-moderate'  => 'मध्यमः $1',
'exif-gpsdop-fair'      => ' युक्तम् ($1)',
'exif-gpsdop-poor'      => 'दीनम् ($1)',

'exif-objectcycle-a' => 'प्रतः केवलम् ।',
'exif-objectcycle-p' => 'सायं केवलम् ।',
'exif-objectcycle-b' => 'सायं प्रातः च ।',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'यथार्था दिशा ।',
'exif-gpsdirection-m' => 'कान्तीयदिशा ।',

'exif-ycbcrpositioning-1' => 'केन्द्रितम् ।',
'exif-ycbcrpositioning-2' => 'सहक्षेत्रम् ।',

'exif-dc-contributor' => 'योगदानिनः ।',
'exif-dc-coverage'    => 'माध्यमस्य स्थानिकः अथवा लैकिकः अवकाशः ।',
'exif-dc-date'        => 'दिनाङ्काः ।',
'exif-dc-publisher'   => 'प्रकाशकः ।',
'exif-dc-relation'    => 'सम्बद्धमाध्यमः ।',
'exif-dc-rights'      => 'अधिकाराः ।',
'exif-dc-source'      => 'स्रोतसः माध्यमः ।',
'exif-dc-type'        => 'माध्यमप्रकारः ।',

'exif-rating-rejected' => 'तिरस्कृतम् ।',

'exif-isospeedratings-overflow' => '६५५३५ तः महत्तरः ।',

'exif-iimcategory-ace' => 'कलाः, संस्कृतिः, मनोरञ्जनम् ।',
'exif-iimcategory-clj' => 'पराधः न्यायनियमाः ।',
'exif-iimcategory-dis' => 'विपदः व्यापदः च ।',
'exif-iimcategory-fin' => 'अर्थव्यवस्था वाणिज्यं च ।',
'exif-iimcategory-edu' => 'विद्याभ्यासः',
'exif-iimcategory-evn' => 'परिसरः',
'exif-iimcategory-hth' => 'स्वास्थ्यम्',
'exif-iimcategory-hum' => 'मानवीयासक्तिः ।',
'exif-iimcategory-lab' => 'परिश्रमः',
'exif-iimcategory-lif' => 'जीवनविधानं विश्रामः च ।',
'exif-iimcategory-pol' => 'राजनीतिः ।',
'exif-iimcategory-rel' => 'मतं विश्वासः च ।',
'exif-iimcategory-sci' => 'विज्ञानं तन्त्रज्ञानं च ।',
'exif-iimcategory-soi' => 'समाजिकाः विवादाः ।',
'exif-iimcategory-spo' => 'क्रीडाः',
'exif-iimcategory-war' => 'युद्धम्, सङ्घर्षः, अशान्तिः ।',
'exif-iimcategory-wea' => 'वातावरण',

'exif-urgency-normal' => 'सामान्यम् ($1)',
'exif-urgency-low'    => 'मन्दम् ।$1',
'exif-urgency-high'   => 'उन्नतम् ($1)',
'exif-urgency-other'  => 'योजकनिरूपिता आद्यता : $1',

# External editor support
'edit-externally'      => 'बाह्यां प्रणालीम् उपयुज्य इयं सञ्चिका सम्पाद्यताम् ।',
'edit-externally-help' => '(अधिकासूचनार्थं [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] इत्येतत् दृश्यताम्)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'सर्वाणि',
'namespacesall' => 'सर्वाणि',
'monthsall'     => 'सर्वाणि',
'limitall'      => 'सर्वाणि',

# E-mail address confirmation
'confirmemail'              => 'ईपत्रसङ्केतः प्रमाणीक्रियताम्',
'confirmemail_noemail'      => 'भवतः योजकाद्यतायां व्यवस्थापितः विद्युन्मानपत्रसङ्केतः मान्यं नाश्ति ।  [[Special:Preferences|user preferences]]',
'confirmemail_text'         => '{{SITENAME}} इत्यत्र विद्युन्मानसुविधोपयोगात् पूर्वं भवतः विद्युन्मानपत्रसङ्केतं मान्यं करोतु । 
भवतः सङ्केतं दृढीकरणसङ्केतं प्राप्तुं अधः दत्तं कड्मलं तुदतु ।
तत्र पत्रं ससङ्केतम् अनुबन्धयुक्तं भवति । 
भवतः विद्युन्मानपत्रसङ्केतं प्रमाणीकर्तुम् अनेन अनुबन्धेन जालगवाक्षेन पूरयतु ।',
'confirmemail_pending'      => 'कश्चित् दृढीकरणस्य सङ्केतसङ्ख्या तावत् विद्युन्मानपत्रद्वारा ते प्रेषितम् । 
भवान् सद्यः योजकस्थानं निर्मितवान् । नूतनसङ्केतसङ्ख्या कतिपयनिमेषापर्यन्तं निरीक्षताम्,',
'confirmemail_send'         => 'दृढीकरणसङ्केतसङ्ख्यां प्रेषयतु ।',
'confirmemail_sent'         => 'दृढीकरणस्य विद्युन्मानपत्रं प्रेषितम् ।',
'confirmemail_oncreate'     => 'दृढीकरणस्य सङ्केतसङ्ख्या विद्युन्मानपत्रद्वारा सम्प्रेषितम् । 
नामाभिलेखार्थम् एषा सङ्केतसङ्ख्या नावश्यकी । किन्तु  वीक्यां विद्युन्मानपत्राधारितलक्षणानि सक्रिययितुं सङ्केतसङ्ख्या प्रकप्पनीया ।',
'confirmemail_sendfailed'   => '{{SITENAME}}भवतः दृढीकरणपत्रप्रेषणं विफलम् । 
अमान्याक्षरशोधाय भवतः विद्युन्मानपत्रसङ्केतं परिशीलयतु ।   
पत्रवाहकः प्रत्यर्पितवान् : $1',
'confirmemail_invalid'      => 'अमान्या दृढीकरणसङ्केतसङ्ख्या ।
प्रायः सङ्केतसङ्ख्या विनष्टा ।',
'confirmemail_needlogin'    => 'भवतः विद्युन्मनपत्रसङ्केतं दृढयितुं भवान् $1 करोतु ।',
'confirmemail_success'      => 'भवतः विद्युन्मानपत्रसङ्केतः इदानीं दृढीकृतः अस्ति । [[Special:UserLogin|log in]]
अधुना भवान् नामाभिलेखेन विकिविहरस्य आनन्दम् अनुभवितुं शक्नोति ।',
'confirmemail_loggedin'     => 'भवतः विद्युन्मानपत्रसङ्केतः दृढीकृतः ।',
'confirmemail_error'        => 'भवतः दृढीकरणावसरे काचित् समस्या उत्पन्ना ।',
'confirmemail_subject'      => '{{SITENAME}}विद्युन्मानपत्रसङ्केतस्य दृढीकरणम् ।',
'confirmemail_body'         => 'कोऽपि अथवा भवान् $1 इति ऐपिसङ्केतद्वारा {{SITENAME}}इत्यस्मिन् "$2" इति नाम्ना योजकस्थाननिर्माणार्थम् अभ्यर्थनं दत्तवान् ।
एतत् योजकस्थानं भवतः एव अपि च {{SITENAME}} इत्यस्मिन् उलब्धे विद्युन्मानपत्रसङ्केतः ।
सुविधारम्भं कर्तुम् अधोदत्तानुबन्ध स्वस्य जालगवाक्षे उद्घाटयतु ।

$3

यदि एतदभ्यर्थनं भवान् न कृतवान् तर्हि एतत् अपकर्षितुम् अधो दतानुबन्धम् उद्घाटयतु ।

$5

इयं दृढीकरणसङ्केतसङ्ख्या $4 इत्यस्मिन् समाप्ता भवति ।',
'confirmemail_body_changed' => 'कोऽपि अथवा भवान् $1 इति ऐपिसङ्केतद्वारा {{SITENAME}}इत्यस्मिन् "$2" इति योजस्थानस्य विद्युन्मानपत्रसङ्केतं परिवर्त्य दत्तवान्  ।
अस्य विषयस्य दृढीकरणार्थम् एतत् योजकस्थानं भवतः एव अस्ति अपि च  {{SITENAME}}इत्यस्मिन् विद्युन्मानसौकर्यं पुनरारब्धुम् अधो दत्तानुबन्धं जालगवाक्षे उद्घाटयतु ।

$3

यदि एतत् योजकस्थानं भवतः नाश्ति तर्हि भवतः विद्युन्मानपत्रसङ्केतम् अपाकृष्टुं अधो दत्तानुबन्धं जालगवाक्षे उद्घाटयतु ।


$5
एतत् दृढीकर्तुं $6 इत्येतत् $7 इति होरायाः पश्चात् कार्यं न करोति ।',
'confirmemail_body_set'     => 'कोऽपि अथवा भवान् $1 इति ऐपिसङ्केतद्वारा {{SITENAME}}इत्यस्मिन् "$2" इति योजस्थानस्य विद्युन्मानपत्रसङ्केतं परिवर्त्य दत्तवान्  ।
अस्य विषयस्य दृढीकरणार्थम् एतत् योजकस्थानं भवतः एव अस्ति अपि च  {{SITENAME}}इत्यस्मिन् विद्युन्मानसौकर्यं पुनरारब्धुम् अधो दत्तानुबन्धं जालगवाक्षे उद्घाटयतु ।

$3

यदि एतत् योजकस्थानं भवतः नाश्ति तर्हि भवतः विद्युन्मानपत्रसङ्केतम् अपाकृष्टुं अधो दत्तानुबन्धं जालगवाक्षे उद्घाटयतु ।


$5
एतत् दृढीकर्तुं $6 इत्येतत् $7 इति होरायाः पश्चात् कार्यं न करोति ।',
'confirmemail_invalidated'  => 'विद्युन्मानपत्रसङ्केतस्य दृढीकरणम् अपकर्षितम् ।',
'invalidateemail'           => 'विद्युन्मानपत्रस्य दृढीकरणम् अपकर्षतु ।',

# Scary transclusion
'scarytranscludedisabled' => 'अन्तर्विकीयः अन्तर्भवनं निष्क्रियम् ।',
'scarytranscludefailed'   => '$1 कृते प्राकृतिः प्रार्थना न प्राप्ता ।',
'scarytranscludetoolong'  => '[URLअतिदीर्घा अस्ति ]',

# Delete conflict
'deletedwhileediting'      => 'पूर्वसूचना : भवतः सम्पादनारम्भात् पश्चात् एतत् पुटम् अपमर्जितम् ।',
'confirmrecreate'          => "योजकः [[User:$1|$1]] ([[User talk:$1|सम्भाषणम्]]) सकारणं भवतः सम्पादनात् परं पुटमेतत् अपमर्जितम् । 
: ''$2''
एतत्पुटं पुनर्निमातुम् इच्छति वेति दृढयतु ।",
'confirmrecreate-noreason' => 'यदा भावान् अस्य पुटास्य सम्पादनम् आरब्धवान् तत्पश्चात् अन्यः योजकः [[User:$1|$1]] ([[User talk:$1|talk]]) एतत् अपनीतवान् । अतः एतत्पुटं पुनर्निमातुम् इच्छति वेति दृढयतु ।',
'recreate'                 => 'पुनर्निर्मीयताम्',

# action=purge
'confirm_purge_button' => 'अस्तु',
'confirm-purge-top'    => 'अस्य पुटस्य इतिहाससङ्ग्रहं निर्मलं करोति वा ?',
'confirm-purge-bottom' => 'कस्यचिदपि पुटस्य अपमर्जनेन सञ्चिका निर्मला भवति  अपि च नूतनतमा आवृत्तिः प्रकटिता भवति ।',

# action=watch/unwatch
'confirm-watch-button'   => 'अस्तु',
'confirm-watch-top'      => 'इदं पृष्ठं भवतः अवेक्षणसूच्यां योजयाम ?',
'confirm-unwatch-button' => 'अस्तु',
'confirm-unwatch-top'    => 'इदं पृष्ठं भवतः अवेक्षणसूच्याः निष्कास्यताम्',

# Multipage image navigation
'imgmultipageprev' => 'पूर्वतनं पृष्ठम्',
'imgmultipagenext' => 'अग्रिमं पृष्ठम्',
'imgmultigo'       => 'गम्यताम् !',
'imgmultigoto'     => '$1 पृष्ठं गम्यताम्',

# Table pager
'ascending_abbrev'         => 'आरुह्',
'descending_abbrev'        => 'अवरुह्',
'table_pager_next'         => 'अग्रिमं पृष्ठम्',
'table_pager_prev'         => 'पूर्वतनं पृष्ठम्',
'table_pager_first'        => 'प्रथमं पृष्ठम्',
'table_pager_last'         => 'अन्तिमं पृष्ठम्',
'table_pager_limit'        => 'प्रतिपृष्ठं $1 वस्तु दर्श्यताम्',
'table_pager_limit_label'  => 'प्रतिपुटं पदार्थाः ।',
'table_pager_limit_submit' => 'गम्यताम्',
'table_pager_empty'        => 'फलितानि न सन्ति',

# Auto-summaries
'autosumm-blank'   => 'पृष्ठं रिक्तीकृतम्',
'autosumm-replace' => '"$1" इत्यनेन सह आधेस्य विनिमयः कृतः ।',
'autoredircomment' => '[[$1]] प्रति पुटं पुनर्निदिष्टम् ।',
'autosumm-new'     => '$1 नवीन पृष्ठं निर्मीत अस्ती',

# Live preview
'livepreview-loading' => 'सम्पूर्यमाणः.....',
'livepreview-ready'   => 'सम्पूरणं सज्जम् ।',
'livepreview-failed'  => 'साक्षात् पूर्वावलोकनं निष्पलम् ।
सामान्यपूर्वावलोकनं यतताम् ।',
'livepreview-error'   => '$1 "$2" तः सम्पर्कः न सिद्धः ।
सामान्यपूर्वावलोकनं यतताम् ।',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 {{PLURAL:$1|क्षणम्|क्षणानि}} इति काले सम्भूतपरिवर्तन प्रायः अस्यां सूचिकायां न दर्शितम् ।',
'lag-warn-high'   => 'अत्युन्नतदत्तांशवितारकस्य विलम्बत्वेन {{PLURAL:$1|क्षणम्|क्षणानि}} तः पूर्वं सम्भूतपरिवर्तनं सूचिकायां न दृश्यते ।',

# Watchlist editor
'watchlistedit-numitems'       => 'भवतः अवलोकनावली {{PLURAL:$1|1 शीर्षकम्|$1 शीर्षकानि}}, अन्तर्भूता, सम्भाषणपुटरहिता ।',
'watchlistedit-noitems'        => 'भवतः अवलोकनावली शीर्षकैः रहिता ।',
'watchlistedit-normal-title'   => 'अवलोकनावलीं सम्पादयतु ।',
'watchlistedit-normal-legend'  => 'अवलोकनावलीतः शीर्षकानि अपनयतु ।',
'watchlistedit-normal-explain' => 'भवतः अवलोकनावल्याः शीर्षकानि अधः दर्शितानि । 
शीर्षकम् अपनेतुम् अस्य पार्श्वे विद्यमानमञ्जूषाम् अर्गलयतु । पश्चात् {{int:Watchlistedit-normal-submit}}" एतत् तुदतु ।
भवान् [[Special:EditWatchlist/raw|अपक्वावलोकनावलीं सम्पादयतु ]] कर्तुं शक्नोति ।',
'watchlistedit-normal-submit'  => 'शीर्षकानि अपनयतु ।',
'watchlistedit-normal-done'    => 'भवतः अवलोकनावली तः{{PLURAL:$1|1शीर्षकम्|$1 शीर्षकानि}} अपनीतानि ।',
'watchlistedit-raw-title'      => 'अपक्वाम् अवलोकनावलीं सम्पादयतु ।',
'watchlistedit-raw-legend'     => 'अपक्वाम् अवलोकनावलीं सम्पादयतु ।',
'watchlistedit-raw-explain'    => 'भवतः अवलोकनावल्याः शीर्षकानि अधः दर्शितानि । अपि च भवान् आवलीतः अपनीय संयोज्य वा परिवर्तयितुं शक्नोति । 
प्रत्येकं लङ्क्तौ एकं शीर्षकम् । 
समाप्तेः पश्चात् "{{int:Watchlistedit-raw-submit}}" एतत् तुदतु ।
भवान् [[Special:EditWatchlist|सूक्तसम्पादकस्य]] अपि उपयोजयितुं शक्नोति ।',
'watchlistedit-raw-titles'     => 'शीर्षकाणि :',
'watchlistedit-raw-submit'     => 'अवलोकनावली उपारोप्यताम्',
'watchlistedit-raw-done'       => 'भवतः अवलोकनावली उन्नतीकारोतु ।',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1शीर्षकम्|$1 शीर्षकानि}} संवृद्धानि ।',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1शीर्षकम्|$1 शीर्षकानि}} अपनीतानि ।',

# Watchlist editing tools
'watchlisttools-view' => 'उचितानि परिवर्तनानि दृश्यन्ताम्',
'watchlisttools-edit' => 'अवेक्षणसूची दृश्यतां सम्पाद्यतां च',
'watchlisttools-raw'  => 'अपरिष्कृता अवेक्षणसूची सम्पाद्यताम्',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|अम्भाषणम्]])',

# Core parser functions
'unknown_extension_tag' => 'अज्ञातं वर्तमानसूत्रम् $1',
'duplicate-defaultsort' => '\'\'\'प्रबोधः\'\'\' पुरानी मूल क्रमांकन कुंजी "$1" के बजाय अब मूल क्रमांकन कुंजी "$2" होगी।',

# Special:Version
'version'                       => 'आवृत्तिः',
'version-extensions'            => 'अनुस्थापितानि विस्तरणानि ।',
'version-specialpages'          => 'विशेषपृष्ठानि',
'version-parserhooks'           => 'विन्यासस्य आलम्बः ।',
'version-variables'             => 'भङ्गुरम्',
'version-antispam'              => 'अनिष्टस्य अवरोधः ।',
'version-skins'                 => 'छादन',
'version-other'                 => 'अन्यत्',
'version-mediahandlers'         => 'माध्यनिर्वाहकाः ।',
'version-hooks'                 => 'आलम्बाः ।',
'version-extension-functions'   => 'विस्तरणस्य कार्यकलापाः ।',
'version-parser-extensiontags'  => 'विन्यासविस्तारणस्य सूत्रम् ।',
'version-parser-function-hooks' => 'विन्यासकलापस्य आलम्बाः ।',
'version-hook-name'             => 'आलम्बस्य नाम ।',
'version-hook-subscribedby'     => 'सदस्यत्वम् अनेन प्राप्तम् ।',
'version-version'               => '(आवृत्तिः$1)',
'version-license'               => 'अनुज्ञापत्रम्',
'version-poweredby-credits'     => "इयं विकिः अनेन सञ्चालिता '''[//www.mediawiki.org/ MediaWiki]''', स्वामित्वम् © 2001 - $1  $2 ।",
'version-poweredby-others'      => 'अन्य',
'version-license-info'          => 'मिडियाविकिः तु निश्शुल्कतन्त्रांशः ; भवान् पुनः वितर्तुं शक्नोति अथवा GNU सामान्यसार्वजनिकानुज्ञपत्रस्य नियमानुगुणं द्वीतीयावृत्तिम् अथवा अन्यनूतनावृतिं संस्कर्तुं शक्नोति । 

एषा बहूपयोगाय भवेत् इति धिया मिडियाविकिः वितीर्णा । किन्तु केनापि प्रमाणत्वेन विना दत्ता । अथवा निर्दिष्टोद्देशर्थे अनुकूलकरं वेति अपरिशील्य अथवा वाणिज्यस्य आनुषङ्गिकानुज्ञापत्रेण विना अपि मीडियाविकिः प्रदत्ता । विशेषविवरणप्राप्तये GNU सर्वजनसामान्यम् अनुज्ञापत्रं पश्यतु ।

[{{SERVER}}{{SCRIPTPATH}}/COPYING काचित् प्रतिः, GNU सर्वजनसामान्यम् अनुज्ञापत्रम्] इत्येतत् भवान् स्वीकृतवान् । अनेन कार्यकलापेन सह , यदि नास्ति, निश्शुल्कतन्त्रज्ञानप्रतिष्ठानं पत्रं प्रेषयतु । सङ्केतः - 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA or [//www.gnu.org/licenses/old-licenses/gpl-2.0.html सद्यसः पठितुमर्हति]',
'version-software'              => 'तन्त्रांशः अनुस्थापितः ।',
'version-software-product'      => 'उत्पाद',
'version-software-version'      => 'आवृत्ति',

# Special:FilePath
'filepath'         => 'सञ्चिकापथः ।',
'filepath-page'    => 'सञ्चिका:',
'filepath-submit'  => 'गम्यताम्',
'filepath-summary' => 'एतद्विशेषपुटं सञ्चिकायाः पूर्णपथं प्रदर्शयति । 
चित्राणि परिपूर्णसत्वयुतानि दर्शितानि । अन्यसञ्चिकाभेदाः सम्बद्धकार्यकलापैः प्रत्यक्षं आरब्धाः ।',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'प्रतिकृतिसञ्चिकार्थम् अन्विषतु ।',
'fileduplicatesearch-summary'   => 'सम्मिश्रमौल्यामूलयुतर्थं  प्रतिकृतिसञ्चिकार्थम् अन्विषतु ।',
'fileduplicatesearch-legend'    => 'प्रतिकृत्यर्थम् अन्विषतु ।',
'fileduplicatesearch-filename'  => 'सञ्चिकानाम:',
'fileduplicatesearch-submit'    => 'अन्वेषणम्',
'fileduplicatesearch-info'      => '$1 × $2 पिक्सेलानि, संचिकायाः आकारः: $3, MIME-प्रकारः: $4',
'fileduplicatesearch-result-1'  => '"$1" इत्यस्मिन् सादृश्यावृत्तिः नास्ति ।',
'fileduplicatesearch-result-n'  => 'इति सञ्चिका {{PLURAL:$1|सादृश्यप्रतिकृतिः|$2 सादृश्यप्रतिकृतयः}}',
'fileduplicatesearch-noresults' => '"$1" इति नाम्ना सञ्चिका न दृष्टा ।',

# Special:SpecialPages
'specialpages'                   => 'विशेषपृष्ठानि',
'specialpages-note'              => '----
* साधारणं विशेषपुटम् । 
* <span class="mw-specialpagerestricted">प्रतिद्धं विशेषपुटम् ।</span>',
'specialpages-group-maintenance' => 'निर्वहणवृत्तानि ।',
'specialpages-group-other'       => 'अन्यविशेषपुटानि ।',
'specialpages-group-login'       => 'प्रविश्यताम् / लेखा सृज्यताम्',
'specialpages-group-changes'     => 'सद्योजातानि परिवर्तनानि आवल्यश्च',
'specialpages-group-media'       => 'माध्यमस्य इतिवृत्तम् आरोपणानि च',
'specialpages-group-users'       => 'योजकाः अधिकाराश्च',
'specialpages-group-highuse'     => 'अधिकोपयोगीनि पृष्ठानि',
'specialpages-group-pages'       => 'पृष्ठानाम् आवली',
'specialpages-group-pagetools'   => 'पृष्ठोपकरणानि',
'specialpages-group-wiki'        => 'विकिदत्तांशः उपकरणानि च',
'specialpages-group-redirects'   => 'विशेषपृष्ठानां पुनर्निदेशनम्',
'specialpages-group-spam'        => 'अनपेक्षितसन्देशस्य उपकरणानि',

# Special:BlankPage
'blankpage'              => 'रिक्तानि पृष्ठानि',
'intentionallyblankpage' => 'इदं पृष्ठं बुद्ध्या एव रिक्तं रक्षितमस्ति ।',

# External image whitelist
'external_image_whitelist' => '  #इयं पङ्क्तिः यथावद् भवतु<pre>
#नीचे मानक अभिव्यक्ति के अंश डालें (केवल वही अंश जो // के बीच आएगा)
#इनका मेल बाहरी छवियों की कड़ियों से करने की कोशिक की जाएगी
#जिनका मेल हो जाएगा वे छवि की तरह दिखाई जाएँगे, वरना केवल उस छवि की कड़ी दिखाई जाएगी
#जो पंक्तियाँ # से शुरू होती हैं उन्हें टिप्पणी माना जाएगा
#यह लघु-दीर्घ अक्षर संवेदी नहीं है

#सभी मानक अभिव्यक्ति अंश इस पंक्ति के ऊपर डालें। इस पक्ति को जस का तस छोड़ दें</pre>',

# Special:Tags
'tags'                    => 'तर्कसिद्धानि परिवर्तनाङ्कनानि',
'tag-filter'              => '[[Special:Tags|Tag]] शोधनी:',
'tag-filter-submit'       => 'शोधनी',
'tags-title'              => 'अङ्कनानि',
'tags-intro'              => 'एतत्पुटं सार्थसूत्राणि दर्शयति यस्य कोऽपि तन्त्रांशः यत्किमपि सम्पादनम् अङ्कयितुं प्रयोजयति ।',
'tags-tag'                => 'अङ्कननाम',
'tags-display-header'     => 'परिवर्तितसूचीषु प्रदर्शनम्',
'tags-description-header' => 'अर्थस्य समग्रवर्णनम्',
'tags-hitcount-header'    => 'अङ्कितानि परिवर्तनानि',
'tags-edit'               => 'सम्पाद्यताम्',
'tags-hitcount'           => '$1 {{PLURAL:$1|परिवर्तनम्|परिवर्तनानि}}',

# Special:ComparePages
'comparepages'                => 'पृष्ठानि तोल्यन्ताम्',
'compare-selector'            => 'पृष्ठसंस्करणानि तोलयतु',
'compare-page1'               => 'पृष्ठम् १',
'compare-page2'               => 'पृष्ठम् २',
'compare-rev1'                => 'संस्करणम् 1',
'compare-rev2'                => 'संस्करणम् २',
'compare-submit'              => 'तोल्यताम्',
'compare-invalid-title'       => 'सूचिता शीर्षिका अमान्या वर्तते ।',
'compare-title-not-exists'    => 'निर्दिष्टं शीर्षकं न विद्यते ।',
'compare-revision-not-exists' => 'निर्दिष्टं संस्करनं न विद्यते ।',

# Database error messages
'dberr-header'      => 'अस्मिन् विकिमध्ये काचित् समस्या विद्यते',
'dberr-problems'    => 'क्षम्यताम् ! अस्मिन् जालपुटे तान्त्रिकसमस्याः अनुभूयमानाः सन्ति ।',
'dberr-again'       => 'किञ्चित् कालं प्रतीक्ष्य पुनः उपारोप्यताम् ।',
'dberr-info'        => '(दत्ताशं वितारकं सम्पर्कयितुं नैव शक्यते $1 )',
'dberr-usegoogle'   => 'अत्रान्तरे भवान् गूगल् इति शोधनयन्त्रे अन्वेषणं कर्तुं शक्नोति ।',
'dberr-outofdate'   => 'अस्माकम् आधेयस्य तेषाम् अनुक्रमणिका कालातिक्रान्ता इति जानातु ।',
'dberr-cachederror' => 'एषा सङ्ग्रहितप्रतिः अभ्यर्थितपुटस्य , एषा उन्नतीकृता अपि न स्यात् ।',

# HTML forms
'htmlform-invalid-input'       => 'भवता आरोपितेषु अंशेषु काचन समस्या विद्यते ।',
'htmlform-select-badoption'    => 'भवता निर्दिष्टं मौल्यं युक्तविकल्पः न ।',
'htmlform-int-invalid'         => 'भवता निर्दिष्टं मौल्यं पूर्णाङ्कः न ।',
'htmlform-float-invalid'       => 'भवता निर्दिष्टं मौल्यं संख्या न ।',
'htmlform-int-toolow'          => 'भवता निश्चितं मौल्यं $1 इत्यस्मात् न्यूनम् अस्ति ।',
'htmlform-int-toohigh'         => 'भवता निश्चितं मौल्यं  $1 तः अधिकम् अस्ति ।',
'htmlform-required'            => 'इदं मूल्यम् अपेक्षितम् ।',
'htmlform-submit'              => 'उपस्थाप्यताम्',
'htmlform-reset'               => 'परिवर्तनानि पूर्वस्थितिं प्रति आनयतु',
'htmlform-selectorother-other' => 'अन्य',

# SQLite database support
'sqlite-has-fts' => '$1 अन्वेषणसमर्थपूर्णपाठेन सह',
'sqlite-no-fts'  => '$1 अन्वेषणसमर्थपूर्णपाठेन विना',

# New logging system
'logentry-delete-delete'              => '$1 इत्यनेन $3 पुटं निष्कासितम्',
'logentry-delete-restore'             => '$1 इत्यनेन $3 पृष्ठं प्रात्यानीतम्',
'logentry-delete-event'               => '$1 परिवर्तितदृश्यस्य {{PLURAL:$5|a log event|$5 log events}} $3: $4 इत्यस्मिन् ।',
'logentry-delete-revision'            => '$1 इत्येषः $3 पुटस्य {{PLURAL:$5|एका आवृत्तिः|$५ आवृत्तयः}}इत्यस्य दृश्यता परिवर्तिता $4',
'logentry-delete-event-legacy'        => '$1 इत्येतत् $3 पुटे  प्रवेशप्रक्रियायाः दृश्यताः परिवर्तिता ।',
'logentry-delete-revision-legacy'     => '$1 इत्येतत् $3 पुटे आवृत्तीनां दृश्यता  परिवर्तिता ।',
'logentry-suppress-delete'            => '$1 निग्रहितपुटम् $3',
'logentry-suppress-event'             => '$1 परिवर्तितदृश्यस्य {{PLURAL:$5|a log event|$5 log events}} $3: $4 इत्यस्मिन् ।',
'logentry-suppress-revision'          => '$1 इत्येषः $3 पुटस्य {{PLURAL:$5|एका आवृत्तिः|$५ आवृत्तयः}}इत्यस्य दृश्यता परिवर्तिता $4',
'logentry-suppress-event-legacy'      => '$1 इत्येतत् $3 पुटे  प्रवेशप्रक्रियायाः दृश्यताः परिवर्तिता ।',
'logentry-suppress-revision-legacy'   => '$1 इत्येतत् $3 पुटे आवृत्तीनां दृश्यता  परिवर्तिता ।',
'revdelete-content-hid'               => 'आधेयं विलोपितम्',
'revdelete-summary-hid'               => 'सम्पादनसारः विलोपितः',
'revdelete-uname-hid'                 => 'योजकस्य नाम सङ्गुप्तम् ।',
'revdelete-content-unhid'             => 'आधेयं न लोपितम्',
'revdelete-summary-unhid'             => 'सम्पादनसारः न लोपितः',
'revdelete-uname-unhid'               => 'योजकस्य नाम न लोपितम्',
'revdelete-restricted'                => 'प्रबन्धकानां प्रतिबन्धनानि आरोपितानि',
'revdelete-unrestricted'              => 'प्रबन्धकानां प्रतिबन्धनानि निष्कासितानि',
'logentry-move-move'                  => '$1 इति प्रयोक्त्रा $3 इत्येतत् $4 इत्येतत् प्रति चालितम्',
'logentry-move-move-noredirect'       => '$1 इति प्रयोक्त्रा $3 इति पृष्ठम् $4 इत्येतत् प्रति चालितं, अनुप्रेषणेन विना',
'logentry-move-move_redir'            => '↓
$1 इत्यनेन $3 इति पृष्ठम् $4 इत्येतत् प्रति चालितं, अनुप्रेषणम् अतिक्रम्य',
'logentry-move-move_redir-noredirect' => '$1 इति प्रयोक्त्रा $3 इति पृष्ठं $4 इत्येतत् प्रति चालितम्, अनुप्रेषणम् अतिक्रम्य, अनुप्रेषणमोचनेन च विना।',
'logentry-patrol-patrol'              => '$1 अङ्कितावृत्तिः $4 इति पुटस्य $3 आरक्षणम् ।',
'logentry-patrol-patrol-auto'         => '$1 इत्येतत् $3 पुटस्य $4 आवृत्तिं स्वयं चालितरूपात् आरक्षितम् ।',
'logentry-newusers-newusers'          => '$1 योजकलेखाम् असृजत्',
'logentry-newusers-create'            => '$1 योजकलेखाम् असृजत्',
'logentry-newusers-create2'           => '$1,  $3 योजकलेखाम् असृजत्',
'logentry-newusers-autocreate'        => '$1 लेखा स्वयमेव सृष्टं जातम्',
'newuserlog-byemail'                  => 'कूटशब्दः ईपत्रद्वारा प्रेषितः',

# Feedback
'feedback-bugornote' => 'यदि भवान् कस्याश्चित् तान्त्रिकसमस्यायाः विषये विशदीकर्तुम् इच्छति तर्हि [$1 मत्कुणसञ्चिकां करोतु ।]
अन्यथा चेत् भवान् सरलप्रपत्रम् उपयोक्तुं शक्नोति । भवतः टीका योजकनाम्ना सह भवतः जालगवाक्षेन सह  "[$3 $2]" इत्यस्मिन् पुटे योज्यते ।',
'feedback-subject'   => 'विषय:',
'feedback-message'   => 'संदेश:',
'feedback-cancel'    => 'निवर्तयते',
'feedback-submit'    => 'प्रतिस्पन्दः प्रेष्यताम्',
'feedback-adding'    => 'पृष्ठे प्रतिस्पन्दः योजनीयः ...',
'feedback-error1'    => 'API इत्यस्मात् दोषः : अज्ञातः परिणामः ।',
'feedback-error2'    => 'दोषः : सम्पादनं निष्फलं जातम्',
'feedback-error3'    => 'दोषः : ए पि ऐ तः प्रतिस्पन्दः न प्राप्तः',
'feedback-thanks'    => 'धन्यवादः ! भवतः प्रतिस्पन्दः "[ $2  $1 ]" पृष्ठाय प्रेषितः अस्ति ।',
'feedback-close'     => 'समापित',
'feedback-bugcheck'  => 'उत्तमम् परिशीलयतु यत्  [ $1 known bugs] पूर्वमेव नासीत् इति ।',
'feedback-bugnew'    => 'अहं परीक्षितवान् ।  नूतनदोषं सूचयतु ।',

# API errors
'api-error-badaccess-groups'              => 'भवान् अस्यां वीक्यां सञ्चिकाः उत्तारयितुम् नानुमतः ।',
'api-error-badtoken'                      => 'आन्तरिकदोषः : दुष्टप्रतीकः ।',
'api-error-copyuploaddisabled'            => 'अस्मिन् वितारके युआर् एल् द्वारा उत्तारणं निष्क्रियम् ।',
'api-error-duplicate'                     => '{{PLURAL:$1| [ $2 अन्यसञ्चिकाः] | सन्ति [ $2 काश्चन अन्यसञ्चिकाः]}} एकस्मिन् एव ।',
'api-error-duplicate-archive'             => 'तत्र {{PLURAL:$1|आसीत् [$2 काश्चन अन्यसञ्चिकाः] |  [$2काचन अन्यसञ्चिकाः]}}, पूर्वमेव {{PLURAL:$1|यह was|they आसन्}} किन्तु अपनीताः ।',
'api-error-duplicate-archive-popup-title' => 'द्विप्रतिः {{PLURAL:$1| सञ्चिका |सञ्चिकाः}} पूर्वमेव अपमर्जिताः ।',
'api-error-duplicate-popup-title'         => 'द्विप्रतिः {{PLURAL:$1| सञ्चिका| सञ्चिकाः}}',
'api-error-empty-file'                    => 'समर्पिता सञ्चिका रिक्ता आसीत् ।',
'api-error-emptypage'                     => 'नूतनस्य रिक्तस्य पृष्ठस्य सर्जनं निषिद्धम् ।',
'api-error-fetchfileerror'                => 'आन्तरिकदोषः : सञ्चिकायाः प्राप्त्यवसरे कश्चन दोषः जातः ।',
'api-error-file-too-large'                => 'समर्पिता सञ्चिका सुदीर्घा अस्ति ।',
'api-error-filename-tooshort'             => 'सञ्चिकानाम अतीव ह्रस्वम् अस्ति ।',
'api-error-filetype-banned'               => 'ईदृशी सञ्चिका अनुरुद्धा ।',
'api-error-filetype-missing'              => 'अस्याः सञ्चिकायाः विस्तारः लुप्तः अस्ति ।',
'api-error-hookaborted'                   => 'भवतः संस्करणप्रयत्नः विस्तारेण अपसारितः ।',
'api-error-http'                          => 'आन्तरिकदोषः : वितारकस्य सम्पर्के असमर्थम् ।',
'api-error-illegal-filename'              => 'सञ्चिकानामलेखनं नानुमतम् ।',
'api-error-internal-error'                => 'आन्तरिकदोषः : वीक्यां भवतः उत्तारणावसरे काचनदोषः संवृत्तः ।',
'api-error-invalid-file-key'              => 'आन्तरिकदोषः : अनित्यसञ्चिकाकोशे सञ्चिका न दृष्टा ।',
'api-error-missingparam'                  => 'आन्तरिकदोषः : अभ्यर्थनानुगुणं व्याप्तिः विलुप्ता ।',
'api-error-missingresult'                 => 'आन्तरिकदोषः : प्रतिकृतिः सफला इति निश्चिता नाभवत् ।',
'api-error-mustbeloggedin'                => 'सञ्चिकायाः उपारोपणाय अन्तः प्रवेशः अनिवार्यः ।',
'api-error-mustbeposted'                  => 'आन्तरिकदोषः : HTTP प्रस्तोतुम् अभ्यर्थनम् आवश्यकम् ।',
'api-error-noimageinfo'                   => 'उत्तारणं सफलम् । किन्तु सञ्चिकाविषये वितारकः कामपि सूचनां न अयच्छतु ।',
'api-error-nomodule'                      => 'आन्तरिकदोषः : उत्तारणघटकः न व्यवस्थितः ।',
'api-error-ok-but-empty'                  => 'आन्तरिकदोषः : वितारकतः प्रतिस्पन्दः न प्राप्तः ।',
'api-error-overwrite'                     => 'वर्तमानसञ्चिकायाः पुनर्लेखनं नानुमतम् ।',
'api-error-stashfailed'                   => 'आन्तरिकदोषः : तात्कालिकसञ्चिकायाः रक्षणे वितारकः असमर्थः जातः ।',
'api-error-timeout'                       => 'अपेक्षितावधौ वितारकेण प्रतिस्पन्दः न दर्शितः ।',
'api-error-unclassified'                  => 'कश्चन अज्ञातः दोषः जातः ।',
'api-error-unknown-code'                  => 'अज्ञातः दोषः " $1 "',
'api-error-unknown-error'                 => 'आन्तरिकदोषः : सञ्चिकायाः आरोपणावसरे कश्चन दोषः जातः ।',
'api-error-unknown-warning'               => 'अज्ञातः प्रबोधः "$1"',
'api-error-unknownerror'                  => 'अज्ञातः दोषः " $1 "',
'api-error-uploaddisabled'                => 'अस्यां वीक्याम् आरोपणं निष्क्रिया कृता अस्ति ।',
'api-error-verification-error'            => 'इयं सञ्चिका सदोषा स्यात् अथवा विस्तारः दोषयुक्तः स्यात् ।',

);
