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
 * @author NehalDaveND
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
	'Booksources'               => array( 'पुस्तकस्रोत' ),
	'BrokenRedirects'           => array( 'खण्डीतपुनर्निर्देशन' ),
	'Categories'                => array( 'वर्गः' ),
	'ChangePassword'            => array( 'सङ्केतशब्दपुन:प्रयुक्ता' ),
	'Confirmemail'              => array( 'विपत्रपुष्टिकृते' ),
	'Contributions'             => array( 'योगदानम्' ),
	'CreateAccount'             => array( 'सृज्उपयोजकसंज्ञा' ),
	'Deadendpages'              => array( 'निराग्रपृष्टानि' ),
	'DeletedContributions'      => array( 'परित्यागितयोगदान' ),
	'DoubleRedirects'           => array( 'पुनर्निर्देशनद्वंद्व' ),
	'Emailuser'                 => array( 'विपत्रयोजक' ),
	'ExpandTemplates'           => array( 'बिंबधरविस्तारकरोसि' ),
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
	'RandomInCategory'          => array( 'अविशिष्टवर्ग' ),
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
	'redirect'                  => array( '0', '#पुनर्निदेशन', '#अनुप्रेषित', '#REDIRECT' ),
	'notoc'                     => array( '0', '__नैवअनुक्रमणी__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__नैवसंक्रमणका__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__अनुक्रमणीसचते__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__अनुक्रमणी__', '__TOC__' ),
	'noeditsection'             => array( '0', '__नैवसम्पादनविभाग__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'अद्यमासे', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'अद्यमासेनाम', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'अद्यमासेनामसाधारण', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'अद्यमासेसंक्षीप्त', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'अद्यदिवसे', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'अद्यदिवसे२', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'अद्यदिवसेनाम', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'अद्यवर्ष', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'सद्यसमय', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'सद्यघण्टा', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'स्थानिकमासे', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'स्थानिकमासेनाम', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'स्थानिकमासेनामसाधारण', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'स्थानिकमासेसंक्षीप्त', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'स्थानिकदिवसे', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'स्थानिकदिवसे२', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'स्थानिकदिवसेनाम', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'स्थानिकवर्षे', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'स्थानिकसमये', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'स्थानिकघण्टा', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'पृष्ठानाम्‌सङ्ख्या', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'लेखस्य‌सङ्ख्या', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'संचिकानाम्‌‌सङ्ख्या', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'योजकस्यसङ्ख्या', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'सम्पादनसङ्ख्या', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'दृष्टिसङ्ख्या', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'पृष्ठनाम', 'PAGENAME' ),
	'namespace'                 => array( '1', 'नामविश्व', 'NAMESPACE' ),
	'talkspace'                 => array( '1', 'व्यासपिठ', 'TALKSPACE' ),
	'subjectspace'              => array( '1', 'विषयविश्व', 'लेखविश्व', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'fullpagename'              => array( '1', 'पूर्णपृष्ठनाम', 'FULLPAGENAME' ),
	'subpagename'               => array( '1', 'उपपृष्ठनाम', 'SUBPAGENAME' ),
	'basepagename'              => array( '1', 'आधारपृष्ठनाम', 'BASEPAGENAME' ),
	'talkpagename'              => array( '1', 'संवादपृष्ठनाम', 'TALKPAGENAME' ),
	'subjectpagename'           => array( '1', 'विषयपृष्ठनाम', 'लेखपृष्ठनाम', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'msg'                       => array( '0', 'सन्देश:', 'MSG:' ),
	'msgnw'                     => array( '0', 'नूतनसन्देश:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'लघुत्तम', 'सङ्कुचितचित्र', 'अङ्गुष्ठ', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'सङ्कुचितचित्र=$1', 'अङ्गुष्ठ=$1', 'लघुत्तमचित्र=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'दक्षिणत', 'right' ),
	'img_left'                  => array( '1', 'वामतः', 'left' ),
	'img_none'                  => array( '1', 'नैव', 'none' ),
	'img_width'                 => array( '1', '$1पिट', '$1px' ),
	'img_center'                => array( '1', 'मध्य', 'center', 'centre' ),
	'img_framed'                => array( '1', 'आबन्ध', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'निराबन्ध', 'frameless' ),
	'img_page'                  => array( '1', 'पृष्ठ=$1', 'पृष्ठ $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'उन्नत', 'उन्नत=$1', 'उन्नत $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'सीमा', 'border' ),
	'img_baseline'              => array( '1', 'आधाररेखा', 'baseline' ),
	'img_sub'                   => array( '1', 'विषये', 'sub' ),
	'img_super'                 => array( '1', 'अति', 'तीव्र', 'super', 'sup' ),
	'img_top'                   => array( '1', 'अग्र', 'top' ),
	'img_text_top'              => array( '1', 'पाठ्य-अग्र', 'text-top' ),
	'img_middle'                => array( '1', 'मध्ये', 'middle' ),
	'img_bottom'                => array( '1', 'अधस', 'bottom' ),
	'img_text_bottom'           => array( '1', 'पाठ्य-अधस', 'text-bottom' ),
	'img_link'                  => array( '1', 'सम्बद्धं=$1', 'link=$1' ),
	'img_alt'                   => array( '1', 'विकल्प=$1', 'alt=$1' ),
	'sitename'                  => array( '1', 'स्थलनाम', 'SITENAME' ),
	'grammar'                   => array( '0', 'व्याकरण:', 'GRAMMAR:' ),
	'notitleconvert'            => array( '0', '__नैवशिर्षकपरिवर्त__', '__नैशिप__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__नैवलेखपरिवर्त__', '__नैलेप__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'अद्यसप्ताह', 'CURRENTWEEK' ),
	'localweek'                 => array( '1', 'स्थानिकसप्ताह', 'LOCALWEEK' ),
	'revisionid'                => array( '1', 'आवृत्तीक्रमांक', 'REVISIONID' ),
	'revisionday'               => array( '1', 'आवृत्तीदिवसे', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'आवृत्तीदिवसे२', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'आवृत्तीमासे', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'आवृत्तीवर्षे', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'आवृत्तीसमयमुद्रा', 'REVISIONTIMESTAMP' ),
	'plural'                    => array( '0', 'अनेकवचन:', 'PLURAL:' ),
	'displaytitle'              => array( '1', 'प्रदर्शनशीर्षक', 'उपाधिदर्शन', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__नूतनविभागसम्बद्धं__', '__NEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'अद्यआवृत्ती', 'CURRENTVERSION' ),
	'currenttimestamp'          => array( '1', 'सद्यसमयमुद्रा', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'स्थानिकसमयमुद्रा', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'दिशाचिह्न', 'दिशे', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#भाषा:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'विषयभाषा', 'आधेयभाषा', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'नामविश्वातपृष्ठ', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'प्रचालकसंख्या', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'रचनासंख्या', 'FORMATNUM' ),
	'special'                   => array( '0', 'विशेष', 'special' ),
	'filepath'                  => array( '0', 'संचिकापथ', 'FILEPATH:' ),
	'tag'                       => array( '0', 'वीजक', 'tag' ),
	'hiddencat'                 => array( '1', '__लुप्तवर्ग__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'वर्गेपृष्ठ', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'पृष्ठाकार', 'PAGESIZE' ),
	'index'                     => array( '1', '__अनुक्रमणिका__', '__INDEX__' ),
	'noindex'                   => array( '1', '__नैवअनुक्रमणिका__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'गणानामसंख्या', 'गणसंख्या', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__अनित्यपुनर्निदेशन__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'रक्षास्तर', 'PROTECTIONLEVEL' ),
);

$digitGroupingPattern = "##,##,###";

