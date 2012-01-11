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
 * @author Mahitgar
 * @author Naveen Sankar
 * @author Omnipaedista
 * @author Shijualex
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
	NS_SPECIAL          => 'विशेष',
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
'unprotect'         => 'असुरक्षितीकरोतु',
'unprotectthispage' => 'एतत्पृष्ठं असुरक्षितीकरोतु',
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
'view-pool-error'   => 'क्षम्यताम्, परिवेषणयन्त्राणि अतिभारितानि अस्मिन् समये।
बहवः प्रयोक्तारः एतत् पृष्ठं द्रष्टुं प्रयतमानाः सन्ति।
कृपया किंचित्कालं प्रतीक्षताम् भवान्, तदा क्रियताम् प्रयासः।
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
'pagetitle'               => '',
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
'dberrortext'          => 'समंकाधार पृच्छायां वाक्यरचना त्रुटिरेका अभवत्।
अनेन अस्माकं तन्त्रांशे त्रुटिरपि निर्दिष्टा स्यात्।
अन्तिमा चेष्टिता समंकाधार-पृच्छा आसीत्:
<blockquote><tt>$1</tt></blockquote>
 "<tt>$2</tt>" इत्यस्मात् फलनात्।
समंकाधारे त्रुटिरासीत्:  "<tt>$3: $4</tt>" इति।',
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
'missing-article'      => 'दत्ताधारेण(डाटाबेस् इत्यनेन) "$1" $2 इतिनामकं प्राप्तव्यं यत् पृष्ठं तत् नैव प्राप्तम्।

प्रायः कालातीतस्य अथवा अपाकृतस्य इतिहाससम्पर्कतन्तोः कारणेन एवं भवति।

यदि नैवं तर्हि भवता तन्त्रांशकीटकं प्राप्तं स्यात्।
कृपया कोऽपि [[Special:ListUsers//sysop|administrator]]अस्य पृष्ठस्य सङ्केतज्ञापनपूर्वकं सूच्यताम्।',
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
'badtitle'             => 'दुष्टं शिरोनाम',
'badtitletext'         => 'प्रार्थितं पृष्ठशीर्षकम् अमान्यं रिक्तम् अशुद्धतया सम्बद्धम् आन्तर्भाषिकम्, आन्तर्विकीयं वा शीर्षकमस्ति । अस्मिन् एकं एकाधिकानि वा एतादृशानि अक्षराणि विद्यन्ते येषां प्रयोगं शीर्षकेषु कर्तुम् अशक्यम्।',
'perfcached'           => 'अनुपदोक्तं समंकं कैश् इत्येतस्माद् अस्ति, अतः अद्यतनं न स्यात्।',
'perfcachedts'         => 'अनुपदोक्तं समंकं कैश् इत्येतस्मिन् विद्यते, तथा च $1 इत्येतत्समये अन्तिमं वारं परिवर्तितम्।',
'querypage-no-updates' => 'अस्य पृष्ठस्य परिशोधनं विफलीकृतमस्ति । 
सद्यः अत्रत्यः विषयः न नवीक्रियते ।',
'wrong_wfQuery_params' => 'wfQuery() इत्येतस्य अशुद्धः मानदण्डः दत्तः अस्ति<br />
कार्यम्: $1<br />
पृच्छा: $2',
'viewsource'           => 'स्रोतः दृश्यताम्',
'viewsourcefor'        => '$1 कृते',
'actionthrottled'      => 'कार्यम् अवरुद्धम् अस्ति',
'actionthrottledtext'  => "'स्प्याम्'इत्येतस्य अवरोधाय अल्पे काले अत्यधिकवारम् अस्य कार्यकरणम् अवरुद्धम् अस्ति । 
कृपया किञ्चित्कालानन्तरं पुनः प्रयत्नः क्रियताम् ।",
'protectedpagetext'    => 'सम्पादनस्य अवरोधाय इदं पृष्ठं सुरक्षितमस्ति ।',
'viewsourcetext'       => 'भवान् एतस्य पृष्ठस्य स्रोतः द्रष्टुं तस्य प्रतिलिपिं कर्तुम् अर्हति।',
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
'passwordreset'              => 'कूटशब्द पुनःस्थापनम्',
'passwordreset-text'         => 'भवतः सदस्यताविवरणानि ईपत्रद्वारा प्राप्तुम् इदं प्रपत्रं पूर्यताम् ।',
'passwordreset-legend'       => 'कूटशब्द पुनःस्थापनम्',
'passwordreset-disabled'     => 'अस्मिन् विक्यां कूटशब्द पुनःस्थापनं असमर्थीकृतमस्ति।',
'passwordreset-pretext'      => '{{PLURAL:$1| |समंकेषु एकम् अधस्यात् प्रविष्टीकरोतु।}}',
'passwordreset-username'     => 'योजकनामन्:',
'passwordreset-domain'       => 'क्षेत्रम्:',
'passwordreset-email'        => 'परमाणुपत्रसङ्गेत:',
'passwordreset-emailtitle'   => '{{SITENAME}} इत्यत्र लेखा-विवरणम्',
'passwordreset-emailtext-ip' => 'कश्चित् (भवान् अपि स्यात्, $1 इति ऐ. पि. सङ्केतात्) {{SITENAME}} ($4) इत्यस्य प्रवेशसम्बद्धं विवरणं प्रार्थितवान् । अधः सूचितस्य उपयोक्तुः {{PLURAL:$3 | प्रवेशविवरणं | प्रवेशविवरणानि}} 
$2
इत्यनेन ईपत्रसङ्केतेन सम्बद्धम् अस्ति / सम्बद्धानि सन्ति ।
{{PLURAL:$3|अयं तात्कालिकः कूटशब्दः | इमे तात्कालिकाः कूटशब्दाः}}  {{PLURAL:$5| एकं दिनं | $5 दिनानि}} यावत् सक्रियः भवति / सक्रियाः भवन्ति ।',
'passwordreset-emailelement' => 'प्रयोक्तृनाम: $1
अल्पकालिकः कूटशब्दः : $2',
'passwordreset-emailsent'    => 'एकः स्मारकः विद्युत्सन्देशः प्रेषितोऽस्ति।',

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
'blockedoriginalsource'            => "'''$1''' इत्येतस्य स्रोतः अधस्तात् प्रदर्शितम्:",
'blockededitsource'                => " '''$1''' इत्यत्र '''भवतः सम्पादनानां''' पाठः अधस्तात् प्रदर्शितम्:",
'whitelistedittitle'               => 'सम्पादनार्थं सत्रारम्भः (प्रवेशः) आवश्यकः',
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
भवता कृतानि परिवर्तनानि इदानीं यावत् न रक्षितानि !",
'previewconflict'                  => 'अस्मिन् प्राग्दृश्ये दर्शितमस्ति यत् उपरिवर्ति पाठ क्षेत्रस्य पाठः रक्षणपश्चात् कीदृशः दृष्टिगोचरः भविष्यति।',
'session_fail_preview'             => "'''क्षम्यताम्! अस्माभिः भवतः सम्पादनस्य संसाधनं न कर्तुं शक्तम् यस्माद्धि सत्रस्य सूचनाः लुप्ताः।'''
कृपया पुनः चेष्टताम्।
चेदेतत् अधुनाऽपि न कार्यशीलं स्यात्, [[Special:UserLogout|सत्राद्बहिः गत्वा]] पुनः प्रवेशं करोतु।",
'editing'                          => '$1 सम्पाद्यते',
'editingsection'                   => '$1 सम्पादनम् (विभागः)',
'editingcomment'                   => '$1 संपादनम् (विभागः)',
'editconflict'                     => 'सम्पादनयोः/सम्पादनानाम् अन्तर्विरोधः : $1',
'yourtext'                         => 'भवतः पाठः',
'storedversion'                    => 'रक्षिता आवृत्तिः',
'nonunicodebrowser'                => "'''पूर्वसूचना: भवतः विचरकं यूनीकोड्-अनुकूलम् नास्ति।'''
भवान् सुरक्षिततया सम्पादनं करोतु इत्येतदर्थं एका युक्तिः कृताऽस्ति: आस्की-इतराणि अक्षराणि सम्पादनपिटके षौडशिक(hexadecimal) कूटेषु द्रक्ष्यन्ते।",
'yourdiff'                         => 'भेदाः',
'copyrightwarning'                 => "कृपया संस्मर्तव्यं यत् {{SITENAME}} इत्येतद् प्रति कृतानि सर्वाणि योगदानानि $2 इत्यस्य प्रतिबंधांतर्गतानि सन्ति (अधिकाय ज्ञानाय $1 इत्येतद् पश्यतु)।

यदि भवान् स्वकीयानि लिखितानि परिवर्तमन्तश्च, पुनः वितर्यमन्तश्च न द्रष्टुमिच्छति तदा मा कृपया माऽत्र योगदानं करोतु। <br />

भवान् एतदपि प्रमाणीकरोति यत् एतद् भवता स्वतः लिखितमस्ति अथवा कस्माच्चत् जनार्पितात् वा मुक्तात् वा स्रोतसः प्रतिलिपीकृतमस्ति।

'''प्रतिलिप्यधिकारयुतान् लेखान्, अनुज्ञां विना, माऽत्र प्रददातु!'''",
'longpageerror'                    => "'''त्रुटिः: भवता प्रदत्तः पाठः $1 किलोबैटमितः दीर्घः, अतः एषः अधिकतमानुज्ञातात् $2 मितात् दीर्घतरः अस्ति। एषः रक्षितुं न शक्यते।'''",
'templatesused'                    => 'अस्मिन् पृष्ठे प्रयुक्तानि {{PLURAL:$1|फलकम्|फलकानि}}:',
'templatesusedpreview'             => 'अस्मिन् प्राग्दृश्ये प्रयुक्ताः {{PLURAL:$1|बिंबधराः |बिंबधराः}}:',
'template-protected'               => '(संरक्षितम्)',
'template-semiprotected'           => '(अर्धसंरक्षितम्)',
'hiddencategories'                 => 'इदं पृष्ठं {{PLURAL:$1|1 निगूढे वर्गे |$1 निगूढेषु वर्गेषु}} अन्यतमं विद्यते :',
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
'currentrev-asof'        => 'वर्तमती आवृत्तिः $1 इति समये',
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
'rev-delundel'                => 'दर्श्यन्ताम्/गोप्यन्ताम्',
'rev-showdeleted'             => 'दर्श्यताम्',
'revdelete-nooldid-title'     => 'लक्ष्यरूपा आवृत्तिः अमान्याऽस्ति।',
'revdelete-nologtype-title'   => 'अभिलेखस्य प्रकारः न प्रदत्तः',
'revdelete-nologtype-text'    => 'अस्यै क्रियायै भवता न कोऽपि अभिलेखप्रकारः निर्दिष्टः।',
'revdelete-nologid-title'     => 'अमान्या अभिलेख-प्रविष्टिः',
'revdelete-no-file'           => 'निर्दिष्टा सञ्चिका न विद्यते ।',
'revdelete-show-file-confirm' => '$2 तः $3 मध्ये "<nowiki>$1</nowiki>" इति सञ्चिकायाः निरस्तं परिष्करणं भवान् नूनं द्रष्टुम् इच्छति ?',
'revdelete-show-file-submit'  => 'आम्',
'revdelete-hide-user'         => 'सम्पादकस्य योजकनाम/आइपिसंकेतः गोप्यताम्।',
'revdelete-hide-restricted'   => 'प्रबन्धकेभ्यः अन्येभ्यश्च समंकं गोपयतु।',
'revdelete-radio-same'        => 'मा परिवर्तयतु।',
'revdelete-radio-set'         => 'आम्',
'revdelete-radio-unset'       => 'न हि',
'revdelete-suppress'          => 'प्रबन्धकेभ्यः अन्येभ्यश्च समंकं गोपयतु।',
'revdelete-unsuppress'        => 'प्रत्यानीताऽऽवृत्तिभ्यः  वर्जनाः अपाकरोतु।',
'revdelete-log'               => 'कारणम् :',
'revdelete-submit'            => '{{PLURAL:$1|चितायां आवृत्त्यां|चितासु आवृत्तिषु}} अनुप्रयोजयतु।',
'revdelete-logentry'          => '"[[$1]]" इत्यस्य आवृत्ति-दृश्यता परिवर्तिताऽस्ति।',
'revdel-restore'              => 'दृष्टिविषयः परिवर्त्यताम्',
'revdel-restore-deleted'      => 'अपास्तानि संस्करणानि',
'revdel-restore-visible'      => 'दृष्टिगोचराणि संस्करणानि',
'pagehist'                    => 'पृष्ठस्य इतिहासः',
'revdelete-content'           => 'विषयः',
'revdelete-summary'           => 'सम्पादनसांक्षेपिक',
'revdelete-uname'             => 'उपयोक्तृ-नाम',
'revdelete-hid'               => 'आवृत $1',
'revdelete-reason-dropdown'   => '*परित्यागाय समानकारणाः
** प्रतिलिपिअधिकारअतिक्रम
** अयोग्यवैयक्तिकविज्ञप्ति',
'revdelete-otherreason'       => 'अन्यत्/सङ्कलितं कारणम् :',
'revdelete-reasonotherlist'   => 'अन्यानि कारणानि',
'revdelete-edit-reasonlist'   => 'सम्पादनस्य अपाकरणाय कारणानि',

# History merging
'mergehistory-reason' => 'कारणम् :',

# Merge log
'revertmerge' => 'पृथक्क्रियताम्',

# Diffs
'history-title'           => '"$1" इत्येतस्य आवर्तनेतिहासः :',
'difference'              => '(संस्करणानां भेदाः)',
'lineno'                  => 'पंक्तिः $1:',
'compareselectedversions' => 'चितानाम् आवृत्तीनां तोलनं क्रियताम्',
'editundo'                => 'निष्क्रियताम्',

# Search results
'searchresults'                    => 'अन्वेषणस्य फलितानि',
'searchresults-title'              => '"$1" इत्यस्य कृते अन्वेषणफलानि',
'searchresulttext'                 => '{{SITENAME}} इत्यस्मिन् अन्वेषणे सहाय्यार्थम् [[{{MediaWiki:Helppage}}|{{int:help}}]] इत्येतत् पश्यतु ।',
'searchsubtitle'                   => 'भवान् \'\'\'[[:$1]]\'\'\'([[Special:Prefixindex/$1|सर्वाणि "$1" इत्यस्माद् आरभमन्तः पृष्ठाणि]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|सर्वाणि "$1" इत्येतत्प्रति संबद्धानि पृष्ठाणि]]) इत्यस्य कृते अन्विष्टवान्।',
'searchsubtitleinvalid'            => "भवता '''$1''' इत्यस्य कृते अन्वेषणं कृतम्",
'notitlematches'                   => 'न कस्यापि पृष्ठस्य शीर्षकम् अस्य समम्।',
'notextmatches'                    => 'न कस्यापि पृष्ठस्य पाठः अस्य सममस्ति',
'prevn'                            => 'प्राक्तनानि {{PLURAL:$1|$1}}',
'nextn'                            => 'अग्रिमाणि{{PLURAL:$1|$1}}',
'prevn-title'                      => 'प्राक्तन-{{PLURAL:$1|फलितम्| फलितानि}}',
'nextn-title'                      => 'प्राक्तन-{{PLURAL:$1|फलितम्| फलितानि}}',
'shown-title'                      => 'प्रत्येकस्मिन् पृष्ठे $1 {{PLURAL:$1|फलितम्|फलितानि}} दर्श्यताम्',
'viewprevnext'                     => 'दर्श्यताम् ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists'                => 'अस्मिन् विकिमध्ये "[[:$1]]"नामकं पृष्ठं विद्यते।',
'searchmenu-new'                   => "'''अस्यां विक्यां \"[[:\$1]]\" इति पृष्ठं सृज्यताम्!'''",
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
'search-redirect'                  => '($1 इत्यत्र अनुप्रेषितम्)',
'search-section'                   => '(विभागः $1)',
'search-suggest'                   => 'किं भवतः आशयः एवमस्ति : $1',
'search-interwiki-caption'         => 'बन्धु-प्रकल्पाः',
'search-interwiki-default'         => '$1 परिणामाः :',
'search-interwiki-more'            => '(अधिकानि)',
'search-mwsuggest-enabled'         => 'उपक्षेपेभ्यः सह',
'search-mwsuggest-disabled'        => 'नात्र उपक्षेपाः',
'searchrelated'                    => 'सम्बद्धानि',
'searchall'                        => 'सर्वाणि',
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
'qbsettings'            => 'शीघ्रपट',
'qbsettings-none'       => 'नास्ति',
'qbsettings-fixedleft'  => 'बामे स्थापितः',
'qbsettings-fixedright' => 'दक्षिणे स्थापितः',

# Preferences page
'preferences'               => 'इष्टतमानि',
'mypreferences'             => 'मम इष्टतमानि',
'prefs-edits'               => 'सम्पादनानां सख्याः',
'prefsnologin'              => 'नैव प्रविष्ट',
'changepassword'            => 'कूटशब्दः परिवर्त्यताम्',
'prefs-skin'                => 'त्वक्',
'skin-preview'              => 'प्राग्दृश्यम्',
'datedefault'               => 'वरीयांसि नास्ति',
'prefs-datetime'            => 'दिनांक तथा समय',
'prefs-personal'            => 'योजकः व्यक्तिरेखा',
'prefs-rc'                  => 'सद्योजातानि परिवर्तनानि',
'prefs-watchlist'           => 'दृष्टि सूची',
'prefs-watchlist-days'      => 'दृष्टि सूची दर्शनार्थे  दिवसानि',
'prefs-watchlist-days-max'  => 'अधिकतम ७ दिवसानि',
'prefs-watchlist-edits-max' => 'अधिकतम संख्या: १०००',
'prefs-misc'                => 'विविधः',
'prefs-resetpass'           => 'कूटशब्दः परिवर्त्यताम्',
'prefs-email'               => 'इमेल वैकल्पिकाः',
'prefs-rendering'           => 'स्वरुपः',
'saveprefs'                 => 'संरक्ष्यताम्',
'resetprefs'                => 'असंरक्षितानि परिवर्तनानि विलुप्यन्ताम्',
'restoreprefs'              => 'समग्राः व्यवस्थादय व्यवस्थानुसारं पुनः संरक्ष्यताम्',
'prefs-editing'             => 'सम्पादनम्',
'rows'                      => 'पंक्ति',
'columns'                   => 'अध: पंक्त्याः',
'searchresultshead'         => 'अन्वेषणम्',
'resultsperpage'            => 'प्रति पृष्ट हिट्स:',
'stub-threshold-disabled'   => 'निष्क्रियः',
'recentchangesdays'         => 'दिवसानि पर्यन्तो सद्यावधि-परिवर्तनानि दृश्यतु:',
'recentchangesdays-max'     => 'अधिकतम $1 {{PLURAL:$1|दिवसः|दिवसानि}}',
'recentchangescount'        => 'सम्पादन संख्यकानि व्यवस्थानुसारेण दृश्यतु:',
'timezonelegend'            => 'समय मण्डल:',
'localtime'                 => 'स्थानीय समय:',
'timezoneregion-africa'     => 'कालद्वीप',
'timezoneregion-america'    => 'अमेरिका',
'timezoneregion-antarctica' => 'अंटार्कटिका',
'timezoneregion-arctic'     => 'आर्कटिक',
'timezoneregion-asia'       => 'जम्बुमहाद्वीप',
'timezoneregion-atlantic'   => 'एटलांटिक महासागर',
'timezoneregion-australia'  => 'ऑस्ट्रेलिया',
'timezoneregion-europe'     => 'यूरोप',
'timezoneregion-indian'     => 'हिंद महासागर',
'timezoneregion-pacific'    => 'प्रशांत महासागर',
'default'                   => 'यदभावे',
'prefs-files'               => 'सञ्चिका',
'youremail'                 => 'ईपत्रसङ्केतः',
'username'                  => 'योजकनामन्:',
'uid'                       => 'प्रयोक्तृ-क्रमांकः :',
'prefs-memberingroups'      => '{{PLURAL:$1|समूहस्य|समूहानां}}  सदस्यः:',
'prefs-registration'        => 'पंजीकरण कालः:',
'yourrealname'              => 'वास्तविकं नाम:',
'yourlanguage'              => 'भाषा:',
'yournick'                  => ' नूतनाः हस्ताक्षराः:',
'prefs-help-signature'      => 'संभाषणपृष्ठगताः संवादाः "<nowiki>~~~~</nowiki>" इति लिखित्वा हस्ताक्षरोपेताः कर्त्तव्याः। एतानि चिह्नानि पृष्ठरक्षणपश्चात् भवतः हस्ताक्षरान् समयमुद्रां च प्रदर्शयिष्यन्ति।',
'badsig'                    => 'अमान्याः (त्रुटिपूर्णाः) हि एते अपक्वाः हस्ताक्षराः।
एचटीएमएल्-टैग इत्येतानि पुनरीक्षितव्यानि भवता।',
'badsiglength'              => 'भवतः हस्ताक्षराः तु अतीव दीर्घाः।
एते $1 {{PLURAL:$1|अक्षरात्|अक्षरेभ्यः}} दीर्घाः न भवितव्याः।',
'yourgender'                => 'लिंगम् (Gender):',
'gender-unknown'            => 'अनिर्दिष्टम्',
'gender-male'               => 'पुरुष',
'gender-female'             => 'स्त्री',
'email'                     => 'विद्युत्पत्रव्यवस्था',
'prefs-help-email'          => 'ईपत्रसङ्केतः अनिवार्यः नास्ति । किन्तु कूटशब्दः विस्मर्यते चेत् तस्य परिवर्तनाय अवश्यकः भवति ।',
'prefs-help-email-others'   => 'अन्ये योजकाः ईपत्रमाध्यमेन भवतः सम्पर्कं यथा कुर्युः तथा भवदीये योजकपृष्ठे सम्भाषणपृष्ठे वा सम्पर्कतन्तुः योजयितुं शक्यः ।
भवतः सम्पर्कं कृतवद्भिः योजकैः भवदीयः ईपत्रसङ्केतः अभिज्ञातः न भवति ।',
'prefs-signature'           => 'हस्ताक्षर',

# User rights
'userrights-reason' => 'कारणम् :',

# Groups
'group-user'  => 'योजकः',
'group-sysop' => 'प्रबंधकाः',
'group-all'   => '(सर्वे)',

'group-user-member' => '{{GENDER:$1|योजक}}',

'grouppage-user'  => '{{ns:project}}:योजक',
'grouppage-sysop' => '{{ns:project}}:प्रचालकाः',

# User rights log
'rightslog'  => 'प्रयोक्तृ-अधिकार-सूचिका',
'rightsnone' => '(कतम)',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'इदं पृष्ठं सम्पाद्यताम्',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|परिवर्तनम्|परिवर्तनानि}}',
'recentchanges'                   => 'सद्योजातानि परिवर्तनानि',
'recentchanges-legend'            => 'सद्योजातानां परिवर्तनानां विकल्पाः',
'recentchangestext'               => 'अस्मिन् विकियोजनायां सद्योजातानि परिवर्तनानि दर्श्यन्ताम्',
'recentchanges-feed-description'  => 'अस्मिन् विकियोजनायां सद्योजातानि परिवर्तनानि दर्श्यन्ताम्',
'recentchanges-label-newpage'     => 'एतस्मात् सम्पादनात् नूतनं पृष्ठं सृष्टमस्ति',
'recentchanges-label-minor'       => 'इदं लघु परिवर्तनम्',
'recentchanges-label-bot'         => 'एतद् यन्त्रेण कृतं सम्पादनम् आसीत्',
'recentchanges-label-unpatrolled' => 'एतद् सम्पादनम् एतावता परिशीलितं नास्ति ।',
'rcnote'                          => "अधस्तात् {{PLURAL:$1|'''1''' परिवर्तनमस्ति|अन्तिमानि '''$1''' परिवर्तनानि सन्ति}},{{PLURAL:$2|गते दिवसे|'''$2''' गतेषु दिवसेषु}}, $5, $4 इति समये।",
'rcnotefrom'                      => "अधः '''$2''' तः  ('''$1''' पर्यन्तं) परिवर्तनानि दर्शितानि सन्ति ।",
'rclistfrom'                      => '$1 तः जातानि नूतनानि परिवर्तनानि दर्श्यताम्',
'rcshowhideminor'                 => '$1 लघूनि सम्पादनानि',
'rcshowhidebots'                  => '$1 बोट् इत्येतानि',
'rcshowhideliu'                   => '$1 प्रविष्टाः योजकाः',
'rcshowhideanons'                 => 'अनामकाः योजकाः $1',
'rcshowhidepatr'                  => '$1 ईक्षितसम्पादनानि',
'rcshowhidemine'                  => '$1 मम सम्पादनानि',
'rclinks'                         => 'अन्तिमानि $1 परिवर्तनानि अन्तिमेषु $2 दिनेषु, दृश्यताम्<br />$3',
'diff'                            => 'भेदः',
'hist'                            => 'इतिहासः',
'hide'                            => 'गोप्यताम्',
'show'                            => 'दर्श्यताम्',
'minoreditletter'                 => '(लघु)',
'newpageletter'                   => '(नवीनम्)',
'boteditletter'                   => '(बोट्)',
'rc_categories_any'               => 'कश्चित्',
'newsectionsummary'               => '/* $1 */ नवीन विभागः',
'rc-enhanced-expand'              => 'विवरणानि दर्श्यन्ताम् (जावालिपिः अपेक्ष्यते)',
'rc-enhanced-hide'                => 'विवरणानि गोप्यन्ताम्',

# Recent changes linked
'recentchangeslinked'         => 'पृष्ठसम्बद्धानि परिवर्तनानि',
'recentchangeslinked-feed'    => 'पृष्ठ-सम्बन्धितानि परिवर्तनानि',
'recentchangeslinked-toolbox' => 'पृष्ठसम्बद्धानि परिवर्तनानि',
'recentchangeslinked-title'   => '"$1" इत्यस्मिन् जातानि परिवर्तनानि',
'recentchangeslinked-summary' => "एषा विशेषपृष्ठसम्बद्धेषु पॄष्ठेषु अथवा वर्गविशेषे अन्तर्भूतेषु पृष्ठेषु सद्योजातानां परिवर्तनानाम् आवलिः।

[[Special:Watchlist|भवतः अवेक्षणसूच्यां]] विद्यमानानि पृष्ठानि '''स्थूलाक्षरैः''' दर्शितानि।",
'recentchangeslinked-page'    => 'पृष्ठ-नाम :',
'recentchangeslinked-to'      => 'अस्मिन् स्थाने अस्य पृष्ठस्य संबद्धानां पृष्ठानां परिवर्तनानि दर्श्यन्ताम्',

# Upload
'upload'            => 'सञ्चिका आरोप्यताम्',
'uploadbtn'         => 'सञ्चिका आरोप्यताम्',
'uploadlogpage'     => 'आरोपितानां सूची',
'filename'          => 'सञ्चिकानाम',
'filedesc'          => 'सारांशः :',
'fileuploadsummary' => 'संग्रहः :',
'filesource'        => 'मूल:',
'uploadedfiles'     => 'आरोपिताः सञ्चिकाः',
'uploadedimage'     => '"[[$1]]" इत्येतद् आरोपितमस्ति',

'upload-unknown-size' => 'अज्ञात आकार',

'license'        => 'अनुमतिदानम्',
'license-header' => 'अनुमतिदानम्',

# Special:ListFiles
'imgfile'               => 'संचिका',
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
'filehist-deleteone'        => 'विलोप',
'filehist-revert'           => 'प्रतिनिवर्त्यताम्',
'filehist-current'          => 'सद्योजातम्',
'filehist-datetime'         => 'दिनाङ्कः/समयः',
'filehist-thumb'            => 'अंगुष्ठनखाकारम्',
'filehist-thumbtext'        => '$1 समये विद्यमत्याः आवृत्तेः अंगुष्ठनखाकारम्',
'filehist-user'             => 'योजकः',
'filehist-dimensions'       => 'आयामाः',
'filehist-comment'          => 'टिप्पणी',
'imagelinks'                => 'संचिका यत्र उपयुक्ता',
'linkstoimage'              => '{{PLURAL:$1|अधोलिखितं पृष्ठं| अधोलिखितानि $1 पृष्ठाणि}} इदं संचिकां प्रति संबंधनं {{PLURAL:$1|करोति| कुर्वन्ति}}।',
'nolinkstoimage'            => 'एतद चित्रात् न पृष्ठा सम्बद्धं करोन्ति।',
'sharedupload'              => 'इयं संचिका $1 इत्यस्मादस्ति, एषा खलु अन्येष्वपि प्रकल्पेषु प्रयोक्तुं शक्यते।',
'sharedupload-desc-here'    => 'एषा सञ्चिका $1 इत्यतः उद्धृता अन्यासु योजनासु उपयोगार्हा ।
अस्याः सञ्चिकायाः  [$2 सञ्चिकाविवरणपृष्ठम्]] इत्यत्र उपलभ्यमानं विवरणम् अधोलिखितं यथा ।',
'uploadnewversion-linktext' => 'अस्य पृष्ठस्य नूतनाम् आवृत्तिं उद्भारयतु',

# File reversion
'filerevert-comment' => 'कारणम् :',

# File deletion
'filedelete-comment'          => 'कारणम् :',
'filedelete-submit'           => 'विलुप्यताम्',
'filedelete-reason-otherlist' => 'अन्य कारणम्',

# MIME search
'download' => 'डाउनलोड',

# Random page
'randompage' => 'यादृच्छिकपृष्ठम्',

# Statistics
'statistics'              => 'स्थितिगणितम्',
'statistics-pages'        => 'पृष्ठानि',
'statistics-files'        => 'उद्भारितसञ्चिकाः',
'statistics-users-active' => 'सक्रियाः सदस्याः',

'disambiguationspage' => 'Template:असन्दिग्धम्',

'doubleredirects' => 'दुगुनी-अनुप्रेषिते',

'brokenredirects-edit'   => 'सम्पाद्यताम्',
'brokenredirects-delete' => 'विलुप्यताम्',

'withoutinterwiki-legend' => 'पूर्वोऽपपदम्',
'withoutinterwiki-submit' => 'दर्श्यताम्',

# Miscellaneous special pages
'nbytes'            => '$1 {{PLURAL:$1|बैट्|बैट्स्}}',
'nmembers'          => '$1 {{PLURAL:$1|सदस्यः|सदस्याः}}',
'prefixindex'       => 'उपसर्गयुक्तानि सर्वाणि पृष्ठानि',
'longpages'         => 'दीर्घाणि पृष्ठानि',
'listusers'         => 'योजक सूचि',
'usercreated'       => '$1 दिने $2 समये रचितम्',
'newpages'          => 'नवीनपृष्ठम्',
'newpages-username' => 'योजकनामन्:',
'ancientpages'      => 'प्राचीनतमानि पृष्ठानि',
'move'              => 'चाल्यताम्',
'movethispage'      => 'इदं पृष्ठं चाल्यताम्',
'pager-newer-n'     => '{{PLURAL:$1|नूतनतरम् 1|नूतनतराणि $1}}',
'pager-older-n'     => '{{PLURAL:$1|पुरातनतरम् 1|पुरातनतराणि $1}}',

# Book sources
'booksources'               => 'ग्रन्थानां स्रोतः',
'booksources-search-legend' => 'ग्रन्थस्रोतः अन्विष्यताम्',
'booksources-go'            => 'गम्यताम्',

# Special:Log
'log' => 'लॉग् इत्येतानि',

# Special:AllPages
'allpages'       => 'सर्वाणि पृष्ठानि',
'alphaindexline' => '$1 तः $2 पर्यन्तम्',
'prevpage'       => 'पूर्वपृष्ठम् ($1)',
'allpagesfrom'   => 'इत्यस्मात् आरभ्यमाणानि पृष्ठानि दर्श्यन्ताम्:',
'allpagesto'     => 'तानि पृष्ठानि दर्श्यन्तां येषाम् अन्त्यम् एवम् :',
'allarticles'    => 'सर्वाणि पृष्ठानि',
'allpagesprev'   => 'पूर्वतन',
'allpagesnext'   => 'अग्रिम',
'allpagessubmit' => 'गम्यताम्',

# Special:Categories
'categories' => 'वर्गाः',

# Special:LinkSearch
'linksearch'      => 'बाह्यसम्पर्कतन्तूनाम् अन्वेषणम्',
'linksearch-ok'   => 'अन्वेषणम्',
'linksearch-line' => '$2 पृष्ठं $1 तः सम्पृक्तम् अस्ति।',

# Special:ListUsers
'listusers-submit' => 'दर्श्यताम्',

# Special:Log/newusers
'newuserlogpage'          => 'प्रयोक्तृ-सृजन-सूचिका',
'newuserlog-create-entry' => 'नूतन-प्रयोक्तृ-लेखा',

# Special:ListGroupRights
'listgrouprights-group'   => 'वर्ग',
'listgrouprights-members' => '(सदस्यानां सूची)',

# E-mail user
'emailuser'     => 'एतस्मै योजकाय ईपत्रं प्रेष्यताम्',
'emailpage'     => 'ई-मेल योजक',
'emailusername' => 'योजकनामन्:',
'emailfrom'     => 'सकाशात्',
'emailto'       => 'सविधे:',
'emailsubject'  => 'विषयः',
'emailmessage'  => 'सन्देशः :',
'emailsend'     => 'प्रेषति',

# Watchlist
'watchlist'         => 'मम अवेक्षणसूची',
'mywatchlist'       => 'मम अवेक्षणसूची',
'watchlistfor2'     => 'हि $1 $2',
'addedwatchtext'    => 'भवतः [[Special:Watchlist|ध्यानसूचिकायां]] "[[:$1]]" इत्येतत् योजितमस्ति।
इदानींप्रभृति अस्मिन् पृष्ठे तथा अस्य चर्चापृष्ठे सन्तः परिवर्तनानि भवतः निरीक्षासूचिकायां द्रक्ष्यन्ते तथा च [[Special:RecentChanges|सद्यःपरिवर्तितानां सूचिकायां]] इदं पृष्ठं स्थूलाक्षरैः द्रक्ष्यते, यस्मात् भवान् सरलतया इदं पश्यतु <p>निरीक्षासूचिकातः निराकर्तुमिच्छति चेत्, "मा निरीक्षताम्" इत्यसमिन् नोदयतु।',
'removedwatchtext'  => '"[[:$1]]" इति पृष्ठं [[Special:Watchlist|भवतः निरीक्षासूचिकातः]] निराकृतमस्ति।',
'watch'             => 'निरीक्षताम्',
'watchthispage'     => 'इदं पृष्ठं निरीक्षताम्',
'unwatch'           => 'मा निरीक्षताम्',
'watchlist-details' => '{{PLURAL:$1|$1 पृष्ठं|$1 पृष्ठानि}} भवतः अवेक्षणसूच्यां सन्ति, सम्भाषणपृष्ठानि नात्र गणितानि।',
'wlshowlast'        => 'अन्तिमानि ($1 होराः $2 दिनानि) $3 इति दर्श्यन्ताम्',
'watchlist-options' => 'अवेक्षणसूच्याः विकल्पाः',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'निरीक्षते...',
'unwatching' => 'निरीक्षाम् अपाकरोति...',

'enotif_newpagetext'           => 'इदम् एकं नवीनपृष्ठम्',
'enotif_impersonal_salutation' => '{{SITENAME}} योजक',

# Delete
'deletepage'            => 'पृष्ठं निराकरोतु।',
'confirm'               => 'स्थिरीकरोतु',
'delete-confirm'        => 'विलुप्यताम् "$1"',
'delete-legend'         => 'विलुप्यताम्',
'confirmdeletetext'     => 'भवान् एकं पृष्ठं तस्य अखिलेन इतिहासेन सहितं अपाकर्तुं प्रवृत्तोऽस्ति। कृपया सुपुष्टीकरोतु यत् भवतः एतदेव आशयः, यद् भवता अस्य परिणामाः सुविज्ञाताः सन्ति तथा च भवता क्रियैषा [[{{MediaWiki:Policy-url}}| यथानीति]] सम्पाद्यते।',
'actioncomplete'        => 'कार्यं सम्पन्नम्',
'actionfailed'          => 'कर्मन् रिष्ट',
'deletedtext'           => '"$1" इत्येतद् अपाकृतमस्ति।
सद्यःकृतानां अपाकरणानाम् अभिलेखः $2 इत्यस्मिन् पश्यतु।',
'deletedarticle'        => '"[[$1]]" अपाकृतमस्ति।',
'dellogpage'            => 'अपाकरणानां सूचिका',
'deletecomment'         => 'कारणम् :',
'deleteotherreason'     => 'अपरं/अतिरिक्तं कारणम् :',
'deletereasonotherlist' => 'इतर कारणम्',

# Rollback
'rollbacklink' => 'प्रतिनिवर्त्यताम्',

# Protect
'protectlogpage'              => 'सुरक्षासूची',
'protectedarticle'            => '"[[$1]]" इत्येतद् संरक्षितमस्ति',
'modifiedarticleprotection'   => '"[[$1]]" इत्येतदर्थं सुरक्षा-स्तरः परिवर्तित: :',
'protectcomment'              => 'कारणम् :',
'protectexpiry'               => 'अवसानम् :',
'protect_expiry_invalid'      => 'अवसान-समयः अमान्योऽस्ति।',
'protect_expiry_old'          => 'अवसान-समयः अतीतोऽस्ति।',
'protect-text'                => "'''$1''' इति पृष्ठस्य कृते सुरक्षा-स्तरं भवान् अत्र दृष्टुं शक्नोति, तथा च तं परिवर्तयितुं शक्नोति।",
'protect-locked-access'       => "भवान् अस्य पृष्ठस्य सुरक्षा-स्तरं परिवर्तयितुम् अनुज्ञां न धारयति। '''$1''' इति पृष्ठस्य अधुनातनः सुरक्षा-स्तरः :",
'protect-cascadeon'           => 'इदं पृष्ठं वर्तमत्काले सुरक्षितमस्ति, यत इदं {{PLURAL:$1|निम्नलिखिते पृष्ठे |निम्नलिखितेषु पृष्ठेषु}} समाहितमस्ति {{PLURAL:$1|यस्मिन्|येषु}} सोपानात्मिका सुरक्षा प्रभाविनी अस्ति। भवान् अस्य पृष्ठस्य सुरक्षा-स्तरं परिवर्तयितुं शक्नोति, परं तेन सोपानात्मिका-सुरक्षा न परिवर्तयिष्यति।',
'protect-default'             => 'सर्वान् प्रयोक्तॄन् अनुज्ञापयतु।',
'protect-fallback'            => '"$1" अनुज्ञा आवश्यकी।',
'protect-level-autoconfirmed' => 'नूतनान् तथा अपंजीकृतान् प्रयोक्तॄन् निरुध्नातु।',
'protect-level-sysop'         => 'प्रबंधकाः केवलाः',
'protect-summary-cascade'     => 'सोपानात्मकम्',
'protect-expiring'            => 'अवसानम् $1 (UTC)',
'protect-expiry-indefinite'   => 'अनिश्चितकालः',
'protect-cascade'             => 'अस्मिन् पृष्ठे समाहितानि पृष्ठाणि सुरक्षितानि करोतु (सोपानात्मिका सुरक्षा)।',
'protect-cantedit'            => 'भवान् अस्य पृष्ठस्य सुरक्षा-स्तरं परिवर्तयितुं न शक्नोति, यतो भवान् इदं पृष्ठं संपादयितुं अनुज्ञां न धारयति।',
'protect-otherreason-op'      => 'इतर कारणम्',
'restriction-type'            => 'अनुमतिः:',
'restriction-level'           => 'सुरक्षा-स्तरः :',

# Restrictions (nouns)
'restriction-edit'   => 'सम्पाद्यताम्',
'restriction-create' => 'सृज्यताम्',
'restriction-upload' => 'आरोप्यताम्',

# Restriction levels
'restriction-level-sysop'         => 'पूर्णतया संरक्षितम्',
'restriction-level-autoconfirmed' => 'अर्धसंरक्षितम्',

# Undelete
'undeletelink'              => 'दृश्यताम्/प्रत्यानीयताम्',
'undeleteviewlink'          => 'दृश्यताम्',
'undeletecomment'           => 'कारणम् :',
'undeletedarticle'          => '"[[$1]]" इत्येतद् पुनःस्थापितमस्ति।',
'undelete-search-submit'    => 'अन्वेषणम्',
'undelete-show-file-submit' => 'आम्',

# Namespace form on various pages
'namespace'      => 'नामाकाशः :',
'invert'         => 'चयनं विपरीतीकरोतु',
'blanknamespace' => '(मुख्यः)',

# Contributions
'contributions'       => 'प्रयोक्तॄणां योगदानानि',
'contributions-title' => '$1 इत्येतस्य कृते योजकानां योगदानानि',
'mycontris'           => 'मम योगदानानि',
'contribsub2'         => '$1 इत्येतदर्थम् ($2)',
'uctop'               => '(शीर्षम्)',
'month'               => 'अस्मात् मासात् (प्राक्तनानि च):',
'year'                => 'अस्मात् वर्षात् (प्राक्तनानि च):',

'sp-contributions-newbies'  => 'नूतनयोजकानां केवलं योगदानानि दर्श्यन्ताम्',
'sp-contributions-blocklog' => 'अवरुद्धा सूची',
'sp-contributions-uploads'  => 'आरोप्यताम्',
'sp-contributions-logs'     => 'लोग्स',
'sp-contributions-talk'     => 'सम्भाषणम्',
'sp-contributions-search'   => 'योगदानानि अन्विष्यन्ताम्',
'sp-contributions-username' => 'आइ.पी.सङ्केतः अथवा योजकनाम :',
'sp-contributions-toponly'  => 'सम्पादनानां नूतनावृत्तिमात्रं दर्श्यताम्',
'sp-contributions-submit'   => 'अन्विष्यताम्',

# What links here
'whatlinkshere'            => 'केभ्यः पृष्ठेभ्यः सम्बद्धम्',
'whatlinkshere-title'      => '"$1" इत्येतेन सम्बद्धानि पृष्ठानि',
'whatlinkshere-page'       => 'पृष्ठम् :',
'linkshere'                => "अधोलिखितानि पृष्ठाणि '''[[:$1]]''' इत्येतद् प्रति संबंधनं कुर्वन्ति :",
'nolinkshere'              => "'''[[:$1]]'''इत्येतेन न किञ्चित् पृष्ठं संयुक्तम्",
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
'blockip'                  => 'प्रयोक्तारं निरुध्नातु',
'ipbreason'                => 'कारणम् :',
'ipboptions'               => '२ होराः:2 hours,१ दिनम्:1 day,३ दिनानि:3 days,१ सप्ताहः:1 week,२ सप्ताहौ:2 weeks,१ मासः:1 month,३ मासाः:3 months,६ मासाः:6 months,१ वर्षः:1 year,अनन्तम्:infinite',
'ipbotheroption'           => 'अन्य',
'ipblocklist'              => 'अवरुद्धाः योजकाः',
'blocklist-reason'         => 'कारणम्',
'ipblocklist-submit'       => 'अन्वेषणम्',
'blocklink'                => 'अवरोधः क्रियताम्',
'unblocklink'              => 'निरोधः अपास्यताम्',
'change-blocklink'         => 'विभागः परिवर्त्यताम्',
'contribslink'             => 'योगदानम्',
'blocklogpage'             => 'अवरोधानां सूची',
'blocklogentry'            => '[[$1]] इत्येतद् अवरुद्धम्, $2 $3 इति अवसान-समयेन सह',
'unblocklogentry'          => 'अनिरुद्धम् $1',
'block-log-flags-nocreate' => 'सदस्यता प्राप्तिः अवरुद्धा अस्ति',
'proxyblocksuccess'        => 'समापित ।',

# Move page
'move-page-legend' => 'पृष्ठं रक्ष्यताम्',
'movearticle'      => 'पृष्ठं चाल्यताम्',
'newtitle'         => 'नूतनं शीर्षकं प्रति :',
'move-watch'       => 'इदं पृष्ठं निरीक्षताम्।',
'movepagebtn'      => 'पृष्ठं चालयतु।',
'pagemovedsub'     => 'चालनं सिद्धम्।',
'movepage-moved'   => '\'\'\'"$1" इत्येतद् "$2" इत्येतद् प्रति चालितमस्ति \'\'\'',
'articleexists'    => 'अनेन नाम्ना पृष्ठमेकं पूर्वेऽपि विद्यते, अथवा भवता चितं नाम तु अमान्यमस्ति। कृपया इतरं किमपि नाम चिनोतु।',
'talkexists'       => "'''पृष्ठं साफल्येन चालितमस्ति, परं चर्चापृष्ठं चालयितुं न शक्यम्, यतो नवेऽपि पृष्ठे चर्चापृष्ठं विद्यते। कृपया तं स्वयमेव चालयतु।'''",
'movedto'          => 'इदं प्रति चालितम्।',
'movetalk'         => 'सहगामिनं चर्चापृष्ठं चालयतु।',
'1movedto2'        => '[[$1]] इत्येतद् [[$2]] इत्येतद् प्रति चालितम्',
'1movedto2_redir'  => '[[$1]] इति लेखस्य नाम परिवर्तितं कृत्वा [[$2]] इति कृतम् (अनुप्रेषितम्)',
'movelogpage'      => 'लॉग् इत्येतद् चाल्यताम्',
'movereason'       => 'कारणम् :',
'revertmove'       => 'प्रतिनिवर्त्यताम्',

# Export
'export'        => 'पृष्ठानां निर्यातं करोतु',
'export-submit' => 'निर्हरति',
'export-addcat' => 'संयोजयति',
'export-addns'  => 'संयोजयति',

# Namespace 8 related
'allmessages'                 => 'व्यवस्था सन्देशाः',
'allmessagesname'             => 'नाम',
'allmessagesdefault'          => 'डिफॉल्टसन्देशपाठ',
'allmessages-filter-all'      => 'अखिलम्',
'allmessages-filter-modified' => 'परिवर्तितम्',
'allmessages-language'        => 'भाषा:',
'allmessages-filter-submit'   => 'गम्यताम्',

# Thumbnails
'thumbnail-more'  => 'विस्तीर्यताम्',
'thumbnail_error' => 'सङ्कुचितचित्रनिर्माणे दोषः: $1',

# Special:Import
'import-comment' => 'टिप्पणी:',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'भवतः योजकपृष्ठम्',
'tooltip-pt-mytalk'               => 'भवतः सम्भाषणपृष्ठम्',
'tooltip-pt-preferences'          => 'भवतः इष्टतमानि',
'tooltip-pt-watchlist'            => 'भवद्भिः परिवर्तनानि निरीक्ष्यमाणानां पृष्ठानां सूची',
'tooltip-pt-mycontris'            => 'भवतः योगदानानाम् आवली',
'tooltip-pt-login'                => 'भवान् न प्रविष्टः। प्रवेशः अनिवार्यः न।',
'tooltip-pt-logout'               => 'निर्गमनम्',
'tooltip-ca-talk'                 => 'पृष्ठान्तर्गतविषये चर्चा',
'tooltip-ca-edit'                 => 'भवान् इदं पृष्ठं सम्पादयितुम् अर्हति। रक्षणात्पूर्वं कृपया प्राग्दृश्यं पश्यतु।',
'tooltip-ca-addsection'           => 'नूतनः विभागः आरभ्यताम्',
'tooltip-ca-viewsource'           => 'इदं पृष्ठं संरक्षितं विद्यते। भवान् अस्य स्रोतः द्रष्टुम् अर्हति।',
'tooltip-ca-history'              => 'अस्य पृष्ठस्य पुरातन्यः आवृत्तयः',
'tooltip-ca-protect'              => 'इदं पृष्ठं संरक्ष्यताम्',
'tooltip-ca-delete'               => 'इदं पृष्ठम् अपाक्रियताम्',
'tooltip-ca-move'                 => 'इदं पृष्ठं चाल्यताम्',
'tooltip-ca-watch'                => 'इदं पृष्ठं भवतः अवेक्षणसूच्यां योज्यताम्',
'tooltip-ca-unwatch'              => 'इदं पृष्ठं भवतः अवेक्षणसूच्याः निष्कास्यताम्',
'tooltip-search'                  => '{{SITENAME}} अत्र अन्विष्यताम्',
'tooltip-search-go'               => 'समानशिरोनामयुक्तं पृष्ठं विद्यते चेत् तत्र गम्यताम्',
'tooltip-search-fulltext'         => 'इदं वचनं पृष्ठेषु अन्विष्यताम्',
'tooltip-p-logo'                  => 'मुख्यपृष्ठं गम्यताम्',
'tooltip-n-mainpage'              => 'मुख्यपृष्ठं गम्यताम्',
'tooltip-n-mainpage-description'  => 'मुख्यपृष्ठं गम्यताम्',
'tooltip-n-portal'                => 'प्रकल्पविषये भवता किं कर्तुं शक्यं, कुत्र अन्वेषणं शक्यम्',
'tooltip-n-currentevents'         => 'सद्यःकालीनघटनानां पृष्ठभूमिका प्राप्यताम्',
'tooltip-n-recentchanges'         => 'सद्योजातानां परिवर्तनानां सूची',
'tooltip-n-randompage'            => 'किमप्येकं पृष्ठं गम्यताम्',
'tooltip-n-help'                  => 'अन्वेषणस्थानम्',
'tooltip-t-whatlinkshere'         => 'एतत्सम्बद्धानां सर्वेषां विकि-पृष्ठानां सूची',
'tooltip-t-recentchangeslinked'   => 'एतत्सम्बद्धेषु पृष्ठेषु जातानि सद्यःकालीनानि परिवर्तनानि',
'tooltip-feed-rss'                => 'अस्मै पृष्ठाय आर-एस-एस-पूरणम्',
'tooltip-feed-atom'               => 'अस्मै पृष्ठाय अणुपूरणम्',
'tooltip-t-contributions'         => 'अस्य योजकस्य योगदानानाम् आवलिः',
'tooltip-t-emailuser'             => 'एतस्मै योजकाय ईपत्रं प्रेष्यताम्',
'tooltip-t-upload'                => 'संचिकाः आरोप्यन्ताम्',
'tooltip-t-specialpages'          => 'सर्वेषां विशिष्टपृष्ठानां सूची',
'tooltip-t-print'                 => 'अस्य पृष्ठस्य मुद्रणयोग्या आवृत्तिः',
'tooltip-t-permalink'             => 'पृष्ठस्य अस्याः आवृत्तेः स्थिरसम्पर्कतन्तुः',
'tooltip-ca-nstab-main'           => 'आन्तर्यं दृश्यताम्',
'tooltip-ca-nstab-user'           => 'योजकपृष्ठं दृश्यताम्',
'tooltip-ca-nstab-special'        => 'इदमेकं विशिष्टं पृष्ठम्, भवान् इदं पृष्ठं सम्पादयितुं न अर्हति।',
'tooltip-ca-nstab-project'        => 'प्रकल्पपृष्ठं दृश्यताम्',
'tooltip-ca-nstab-image'          => 'सञ्चिकापृष्ठं दृश्यताम्',
'tooltip-ca-nstab-template'       => 'फलकं दृश्यताम्',
'tooltip-ca-nstab-category'       => 'वर्गाणां पृष्ठं दृश्यताम्',
'tooltip-minoredit'               => 'इदं परिवर्तनं लघुपरिवर्तनरूपेण अङ्क्यताम्',
'tooltip-save'                    => 'परिवर्तनानि रक्ष्यन्ताम्',
'tooltip-preview'                 => 'भवता कृतानां परिवर्तनानां प्राग्दृश्यं दृश्यताम्, रक्षणात्पूर्वं कृपया इदम् उपयुज्यताम्।',
'tooltip-diff'                    => 'पाठे भवता कृतानि परिवर्तनानि दृश्यन्ताम्।',
'tooltip-compareselectedversions' => 'पृष्ठस्य द्वयोः चितयोः आवृत्त्योः भेदः दृश्यताम्',
'tooltip-watch'                   => 'इदं पृष्ठं भवतः अवेक्षणसूच्यां योज्यताम्',
'tooltip-rollback'                => '"पूर्ण-प्रतिगमनं(रोलबैक् इत्येतद्)" अस्य पृष्ठस्य संपादनानि अंतिम-योगदातृकृतानि विपरीतीकरोति एकेन क्लिक्कारेण',
'tooltip-undo'                    => '"निष्क्रियताम्" इत्येतद् इदं सम्पादनं विपरीतीकरोति, तथा च सम्पादनप्रारूपं प्राग्दृश्यरूपेण उद्घाटयति।

अस्य सारांशे कारणमपि लेखितुं शक्यते।',
'tooltip-summary'                 => 'संक्षिप्तः सारांशः योज्यताम्',

# Attribution
'siteuser'  => '{{SITENAME}} योजक $1',
'anonuser'  => '{{SITENAME}} अज्ञात योजक $1',
'others'    => 'अन्य',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|योजक|योजक}} $1',
'anonusers' => '{{SITENAME}} अज्ञात {{PLURAL:$2|योजक|योजक}} $1',

# Info page
'pageinfo-header-edits' => 'सम्पादयति',
'pageinfo-subjectpage'  => 'पृष्ठम्',
'pageinfo-talkpage'     => 'कथा पृष्ठम्',

# Skin names
'skinname-standard'    => 'पूर्व',
'skinname-nostalgia'   => 'पुराण',
'skinname-cologneblue' => 'नील',
'skinname-monobook'    => 'पुस्तक',
'skinname-myskin'      => 'मे चर्मन्',
'skinname-chick'       => 'Chick',

# Patrol log
'patrol-log-diff' => 'संस्करण $1',

# Browsing diffs
'previousdiff' => '← पुरातनतरं सम्पादनम्',
'nextdiff'     => 'नवतरं सम्पादनम् →',

# Media information
'file-info-size' => '$1 × $2 पिक्सेलानि, संचिकायाः आकारः: $3, MIME-प्रकारः: $4',
'file-nohires'   => '<small>उच्चतरं विभेदनं नोपलब्धम्</small>',
'svg-long-desc'  => 'SVG संचिका, साधारणतया $1 × $2 पिक्सेलानि, संचिकायाः आकारः : $3',
'show-big-image' => 'पूर्णं विभेदनम्',

# Special:NewFiles
'newimages' => 'नूतन-संचिकानां वीथिका',

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
'exif-imagewidth'  => 'विस्तारः',
'exif-imagelength' => 'औन्नत्यम्',
'exif-artist'      => 'लेखक',
'exif-flash'       => 'स्फुरणम्',
'exif-gpsspeedref' => 'गती एकक',
'exif-source'      => 'मूल',
'exif-iimcategory' => 'वर्ग',

'exif-orientation-1' => 'सामान्य',

'exif-meteringmode-0' => 'अज्ञात',
'exif-meteringmode-1' => 'माध्य',

'exif-lightsource-0' => 'अज्ञात',

'exif-contrast-0' => 'सामान्य',

'exif-sharpness-0' => 'सामान्य',

'exif-subjectdistancerange-0' => 'अज्ञात',

'exif-iimcategory-edu' => 'विद्याभ्यासः',
'exif-iimcategory-evn' => 'परिसरः',
'exif-iimcategory-hth' => 'स्वास्थ्यम्',
'exif-iimcategory-spo' => 'क्रीडाः',
'exif-iimcategory-wea' => 'वातावरण',

# External editor support
'edit-externally'      => 'बाह्यां प्रणालीम् उपयुज्य इयं सञ्चिका सम्पाद्यताम् ।',
'edit-externally-help' => '(अधिकासूचनार्थं [//www.mediawiki.org/wiki/Manual:External_editors setup instructions] इत्येतत् दृश्यताम्)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'सर्वाणि',
'namespacesall' => 'सर्वाणि',
'monthsall'     => 'सर्वाणि',
'limitall'      => 'सर्वाणि',

# E-mail address confirmation
'confirmemail' => 'ईपत्रसङ्केतः प्रमाणीक्रियताम्',

# action=purge
'confirm_purge_button' => 'अस्तु',

# action=watch/unwatch
'confirm-watch-button'   => 'अस्तु',
'confirm-watch-top'      => 'इदं पृष्ठं भवतः अवेक्षणसूच्यां योजयाम ?',
'confirm-unwatch-button' => 'अस्तु',

# Multipage image navigation
'imgmultipagenext' => 'अग्रिमं पृष्ठम्',
'imgmultigo'       => 'गम्यताम् !',
'imgmultigoto'     => '$1 पृष्ठं गम्यताम्',

# Table pager
'table_pager_next'         => 'अग्रिमं पृष्ठम्',
'table_pager_prev'         => 'पूर्वतनं पृष्ठम्',
'table_pager_first'        => 'प्रथमं पृष्ठम्',
'table_pager_last'         => 'अन्तिमं पृष्ठम्',
'table_pager_limit_submit' => 'गम्यताम्',
'table_pager_empty'        => 'फलितानि न सन्ति',

# Auto-summaries
'autosumm-blank' => 'पृष्ठं रिक्तीकृतम्',
'autosumm-new'   => '$1 नवीन पृष्ठं निर्मीत अस्ती',

# Watchlist editing tools
'watchlisttools-view' => 'उचितानि परिवर्तनानि दृश्यन्ताम्',
'watchlisttools-edit' => 'अवेक्षणसूची दृश्यतां सम्पाद्यतां च',
'watchlisttools-raw'  => 'अपरिष्कृता अवेक्षणसूची सम्पाद्यताम्',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'प्रबोधः\'\'\' पुरानी मूल क्रमांकन कुंजी "$1" के बजाय अब मूल क्रमांकन कुंजी "$2" होगी।',

# Special:Version
'version'                  => 'आवृत्तिः',
'version-skins'            => 'छादन',
'version-other'            => 'अन्यत्',
'version-poweredby-others' => 'अन्य',
'version-software-product' => 'उत्पाद',
'version-software-version' => 'आवृत्ति',

# Special:FilePath
'filepath-page'   => 'सञ्चिका:',
'filepath-submit' => 'गम्यताम्',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'सञ्चिकानाम:',
'fileduplicatesearch-submit'   => 'अन्वेषणम्',

# Special:SpecialPages
'specialpages'                 => 'विशेषपृष्ठानि',
'specialpages-group-login'     => 'प्रविश्यताम् / हस्ताङ्कनं क्रियताम्',
'specialpages-group-changes'   => 'सद्योजातानि परिवर्तनानि आवल्यश्च',
'specialpages-group-media'     => 'माध्यमस्य इतिवृत्तम् आरोपणानि च',
'specialpages-group-users'     => 'योजकाः अधिकाराश्च',
'specialpages-group-highuse'   => 'अधिकोपयोगीनि पृष्ठानि',
'specialpages-group-pages'     => 'पृष्ठानाम् आवली',
'specialpages-group-pagetools' => 'पृष्ठोपकरणानि',

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
'tag-filter'    => '[[Special:Tags|Tag]] शोधनी:',
'tags-edit'     => 'सम्पाद्यताम्',
'tags-hitcount' => '$1 {{PLURAL:$1|परिवर्तनम्|परिवर्तनानि}}',

# Special:ComparePages
'comparepages'   => 'पृष्ठानि तोल्यन्ताम्',
'compare-page1'  => 'पृष्ठम् १',
'compare-page2'  => 'पृष्ठम् २',
'compare-rev1'   => 'संस्करणम् 1',
'compare-rev2'   => 'संस्करणम् २',
'compare-submit' => 'तोल्यताम्',

# Database error messages
'dberr-header'   => 'अस्मिन् विकिमध्ये काचित् समस्या विद्यते',
'dberr-problems' => 'क्षम्यताम् ! अस्मिन् जालपुटे तान्त्रिकसमस्याः अनुभूयमानाः सन्ति ।',

# HTML forms
'htmlform-invalid-input'       => 'भवता आरोपितेषु अंशेषु काचन समस्या विद्यते ।',
'htmlform-select-badoption'    => 'भवता निर्दिष्टं मौल्यं युक्तविकल्पः न ।',
'htmlform-int-invalid'         => 'भवता निर्दिष्टं मौल्यं पूर्णाङ्कः न ।',
'htmlform-float-invalid'       => 'भवता निर्दिष्टं मौल्यं संख्या न ।',
'htmlform-selectorother-other' => 'अन्य',

);
