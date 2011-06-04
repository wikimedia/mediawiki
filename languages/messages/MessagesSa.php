<?php
/** Sanskrit (संस्कृत)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bharata
 * @author Bhawani Gautam
 * @author Hemant wikikosh1
 * @author Hrishikesh.kb
 * @author Htt
 * @author Kaustubh
 * @author Mahitgar
 * @author Naveen Sankar
 * @author Omnipaedista
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
	'DoubleRedirects'           => array( 'पुनर्निर्देशनद्वंद्व' ),
	'BrokenRedirects'           => array( 'खण्डीतपुनर्निर्देशन' ),
	'Disambiguations'           => array( 'नि:संदिग्धीकरण' ),
	'Userlogin'                 => array( 'सदस्यप्रवेशन' ),
	'Userlogout'                => array( 'सदस्यबहिर्गमन' ),
	'CreateAccount'             => array( 'सृज्उपयोजकसंज्ञा' ),
	'Preferences'               => array( 'इष्टतमानि' ),
	'Watchlist'                 => array( 'निरीक्षा_सूची' ),
	'Recentchanges'             => array( 'नवीनतम_परिवर्तन' ),
	'Upload'                    => array( 'भारंन्यस्यति' ),
	'Listfiles'                 => array( 'चित्रसूची', 'संचिकासूचि' ),
	'Newimages'                 => array( 'नूतनसंचिका', 'नूतनचित्रानि' ),
	'Listusers'                 => array( 'सदस्यासूची' ),
	'Listgrouprights'           => array( 'गटअधिकारसूची' ),
	'Statistics'                => array( 'सांख्यिकी' ),
	'Randompage'                => array( 'अविशीष्टपृष्ठम्' ),
	'Lonelypages'               => array( 'अकलपृष्टानि' ),
	'Uncategorizedpages'        => array( 'अवर्गीकृतपृष्टानि' ),
	'Uncategorizedcategories'   => array( 'अवर्गीकृतवर्ग' ),
	'Uncategorizedimages'       => array( 'अवर्गीकृतसंचिका', 'अवर्गीकृतचित्रानि' ),
	'Uncategorizedtemplates'    => array( 'अवर्गीकृतफलकानि' ),
	'Unusedcategories'          => array( 'अप्रयूक्तवर्ग' ),
	'Unusedimages'              => array( 'अप्रयूक्तसंचिका' ),
	'Wantedpages'               => array( 'प्रार्थितपृष्टानि' ),
	'Wantedcategories'          => array( 'प्रार्थितवर्ग' ),
	'Wantedfiles'               => array( 'प्रार्थितसंचिका' ),
	'Wantedtemplates'           => array( 'प्रार्थितफलकानि' ),
	'Mostlinked'                => array( 'अधिकतमसम्भन्दिन्_पृष्टानि', 'अधिकतमसम्भन्दिन्' ),
	'Mostlinkedcategories'      => array( 'अधिकतमसम्भन्दिन्_वर्ग' ),
	'Mostlinkedtemplates'       => array( 'अधिकतमसम्भन्दिन्_फलकानि' ),
	'Mostimages'                => array( 'अधिकतमसम्भन्दिन्_संचिका' ),
	'Mostcategories'            => array( 'अधिकतमवर्ग' ),
	'Mostrevisions'             => array( 'अधिकतमपरिवर्तन' ),
	'Fewestrevisions'           => array( 'स्वल्पपरिवर्तन' ),
	'Shortpages'                => array( 'लघुपृष्टानि' ),
	'Longpages'                 => array( 'दीर्घपृष्टानि' ),
	'Newpages'                  => array( 'नूतनपृष्टानि' ),
	'Ancientpages'              => array( 'पूर्वतनपृष्टानि' ),
	'Deadendpages'              => array( 'निराग्रपृष्टानि' ),
	'Protectedpages'            => array( 'सुरक्षितपृष्टानि' ),
	'Protectedtitles'           => array( 'सुरक्षितशिर्षकम्' ),
	'Allpages'                  => array( 'सर्वपृष्टानि' ),
	'Prefixindex'               => array( 'उपसर्गअनुक्रमणी' ),
	'Ipblocklist'               => array( 'प्रतिबन्धसूची' ),
	'Specialpages'              => array( 'विशेषपृष्टानि' ),
	'Contributions'             => array( 'योगदानम्' ),
	'Emailuser'                 => array( 'विपत्रयोजक' ),
	'Confirmemail'              => array( 'विपत्रपुष्टिकृते' ),
	'Whatlinkshere'             => array( 'किमपृष्ठ_सम्बद्धंकरोति' ),
	'Recentchangeslinked'       => array( 'नवीनतमसम्भन्दिन_परिवर्त' ),
	'Movepage'                  => array( 'पृष्ठस्थानान्तर' ),
	'Blockme'                   => array( 'मदर्थेप्रतिबन्ध' ),
	'Booksources'               => array( 'पुस्तकस्रोत' ),
	'Categories'                => array( 'वर्गः' ),
	'Export'                    => array( 'निर्यात' ),
	'Version'                   => array( 'आवृत्ती' ),
	'Allmessages'               => array( 'सर्वप्रणाली-संदेश' ),
	'Log'                       => array( 'अङ्कन' ),
	'Blockip'                   => array( 'सदस्यप्रतिबन्ध' ),
	'Undelete'                  => array( 'प्रत्यादिश्_परित्याग' ),
	'Import'                    => array( 'आयात' ),
	'Lockdb'                    => array( 'विदाद्वारंबन्ध्' ),
	'Unlockdb'                  => array( 'विवृतविदाद्वारंतालक' ),
	'Userrights'                => array( 'योजकआधिकार' ),
	'MIMEsearch'                => array( 'विविधामाप_(माईम)_शोधसि' ),
	'FileDuplicateSearch'       => array( 'अनुकृतसंचिकाशोध' ),
	'Unwatchedpages'            => array( 'अनिरिक्षीतपृष्ठ' ),
	'Listredirects'             => array( 'विचालन्‌सूची' ),
	'Revisiondelete'            => array( 'आवृत्तीपरित्याग' ),
	'Unusedtemplates'           => array( 'अप्रयूक्तबिंबधर' ),
	'Randomredirect'            => array( 'अविशीष्टविचालन्‌' ),
	'Mypage'                    => array( 'मम_पृष्टम्' ),
	'Mytalk'                    => array( 'मदीयसंवादम्' ),
	'Mycontributions'           => array( 'मदीययोगदानम्' ),
	'Listadmins'                => array( 'प्रचालकसूची' ),
	'Listbots'                  => array( 'स्वयंअनुकृसूची' ),
	'Popularpages'              => array( 'लोकप्रियपृष्टानि' ),
	'Search'                    => array( 'शोध' ),
	'Resetpass'                 => array( 'सङ्केतशब्दपुन:प्रयुक्ता' ),
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
# User preference toggles
'tog-underline'               => 'संबंधनानि अधोरेखितानि करोतु',
'tog-highlightbroken'         => 'भग्नानि संबंधनानि <a href="" class="new"> एवं दर्शयतु </a> (अथवा : एवं दर्शयतु <a href="" class="internal">?</a>)।',
'tog-justify'                 => 'परिच्छेदान् समानान् करोतु।',
'tog-hideminor'               => 'सद्यःभूतेभ्यः परिवर्तनेभ्यः लघूनि संपादनानि मा दर्शयतु।',
'tog-hidepatrolled'           => 'सद्यःभूतेभ्यः परिवर्तनेभ्यः दृष्टपूर्वाणि संपादनानि मा दर्शयतु।',
'tog-newpageshidepatrolled'   => 'नूतन-पृष्ठाणां सूचिकातः दृष्टपूर्वाणि पृष्ठाणि मा दर्शयतु।',
'tog-extendwatchlist'         => 'निरीक्षासूचिकां विस्तारयित्वा सर्वाणि परिवर्तनानि दर्शयतु, न तु केवलानि सद्यःभूतानि।',
'tog-usenewrc'                => 'संवृद्धानि (एन्-हेन्स्ड् इति) सद्यःभूतानि परिवर्तनानि प्रयोजयतु (जावास्क्रिप्टम् आवश्यकम्)।',
'tog-numberheadings'          => 'शीर्षकान् स्वयमेव सक्रमांकीकरोतु।',
'tog-showtoolbar'             => 'संपादन-उपकरण-पट्टिकां दर्शयतु (जावास्क्रिप्टम् आवश्यकम्)।',
'tog-editondblclick'          => 'द्विक्लिक्कारेण पृष्ठाणि संपादयतु (जावास्क्रिप्टम् आवश्यकम्)',
'tog-editsection'             => '[संपादयतु़] इति संबंधनद्वारा विभाग-संपादनं समर्थयतु।',
'tog-editsectiononrightclick' => 'विभाग-शीर्षकाणामुपरि दक्षिणक्लिक्कारेण विभागसंपादनं समर्थयतु (जावास्क्रिप्टम् आवश्यकम्)।',
'tog-showtoc'                 => 'अनुक्रमणिकां दर्शयतु (त्र्यधिकशीर्षकयुतेषु पृष्ठेषु)।',
'tog-rememberpassword'        => 'अस्मिन् संगणके मम प्रवेशसंबंधि-सूचनाः स्मरतु (अधिकतम $1 {{PLURAL:$1|दिनम्|दिनानि}} पर्यन्तम्)',
'tog-watchcreations'          => 'मया रचितानि पृष्ठाणि मम निरीक्षासूचिकायां योजयतु।',
'tog-watchdefault'            => 'मया संपादितानि पृष्ठाणि मम निरीक्षासूचिकायां योजयतु।',
'tog-watchmoves'              => 'मया चालितानि पृष्ठाणि मम निरीक्षासूचिकायां योजयतु।',
'tog-watchdeletion'           => 'मया अपाकृतानि पृष्ठाणि मम निरीक्षासूचिकायां योजयतु।',
'tog-minordefault'            => 'मम सर्वाणि संपादनानि लघुत्वेन वर्तन्ते।',
'tog-previewontop'            => 'सम्पादन-पिटकस्योपरि प्राग्दृश्यं दर्शयतु।',
'tog-previewonfirst'          => 'प्रथम-संपादन-पश्चात् प्राग्दृश्यं दर्शयतु।',
'tog-nocache'                 => 'पृष्ठ धारक-ब्राउजरं निस्क्रियतु ।',
'tog-enotifwatchlistpages'    => 'मम निरीक्षासूचिकायां सतां पृष्ठाणां परिवर्तनसमये मां विद्युत्पत्रेण (ईमेल् इति) ज्ञापयतु।',
'tog-enotifusertalkpages'     => 'मम योजकसंभाषणपृष्ठे परिवर्तिते सति मां विद्युत्पत्रेण (ईमेल् इति) ज्ञापयतु।',
'tog-enotifminoredits'        => 'लघुपरिवर्तनेषु सत्सु अपि मां विद्युत्पत्रेण (ईमेल् इति) ज्ञापयतु।',
'tog-enotifrevealaddr'        => 'अधिसूचना-विद्युत्पत्रेषु मम विद्युत्पत्रसंकेतं दर्शयतु।',
'tog-shownumberswatching'     => 'निरीक्षमतां प्रयोक्तृणां संख्यां दर्शयतु।',
'tog-oldsig'                  => 'अधुनातनानां हस्ताक्षराणां प्रारूपम्।',
'tog-fancysig'                => 'हस्ताक्षराणि विकिपाठवत् सन्तु (स्वचालित-संबंधनेभ्यः रहितानि)।',
'tog-externaleditor'          => 'Use external editor by default (for experts only, needs special settings on your computer. [http://www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-externaldiff'            => 'अकथिते (बाइ डिफाल्ट् इति), बाह्य अंतरक्रमादेशं प्रयोजयतु (केवलेभ्यः निपुणेभ्यः, भवतः संगणके विशेषाः न्यासाः आवश्यकाः)।',
'tog-showjumplinks'           => '"इत्येतत् प्रति कूर्दयतु" इति संबंधनानि समर्थयतु।',
'tog-uselivepreview'          => 'संपादनेन सहैव प्राग्दृश्यं दर्शयतु (जावास्क्रिप्टम् आवश्यकम्) (प्रयोगात्मकम्)।',
'tog-forceeditsummary'        => 'संपादन-सारांशः चेत् न ददामि तदा मां ज्ञापयतु।',
'tog-watchlisthideown'        => 'मम संपादनानि निरीक्षासूचिकातः लोपयतु।',
'tog-watchlisthidebots'       => 'बोट्कृतानि संपादनानि निरीक्षासूचिकातः लोपयतु।',
'tog-watchlisthideminor'      => 'मम निरीक्षासूचिकातः लघूनि संपादनानि लोपयतु।',
'tog-watchlisthideliu'        => 'प्रवेशितेभ्यः प्रयोक्तृभ्यः कृतानि संपादनानि मम निरीक्षासूचिकातः लोपयतु।',
'tog-watchlisthideanons'      => 'अनामकेभ्यः प्रयोक्तृभ्यः कृतानि संपादनानि मम निरीक्षासूचिकातः लोपयतु।',
'tog-watchlisthidepatrolled'  => 'मम निरीक्षासूचिकातः दृष्टपूर्वाणि संपादनानि लोपयतु।',
'tog-ccmeonemails'            => 'मया अन्यान् प्रति प्रेषितानां विद्युत्पत्राणां प्रतिलिप्यः मां प्रेषयतु।',
'tog-diffonly'                => 'आवृत्तिसु अंतरं दर्शयन् पुरातनाः आवृत्तयः मा दर्शयतु।',
'tog-showhiddencats'          => 'लोपिताः श्रेण्यः दर्शयतु।',
'tog-norollbackdiff'          => 'पूर्णप्रतिगमने कृते मा दर्शयतु तद् अंतरम्।',

'underline-always'  => 'सदा',
'underline-never'   => 'नैव',
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
'tuesday'       => 'मङ्गळवासरः',
'wednesday'     => 'बुधवासरः',
'thursday'      => 'गुरुवासरः',
'friday'        => 'शुक्रवासरः',
'saturday'      => 'शनिवासरः',
'sun'           => 'रविः',
'mon'           => 'सोमः',
'tue'           => 'मङ्गळः',
'wed'           => 'बुधः',
'thu'           => 'गुरुः',
'fri'           => 'शुक्रः',
'sat'           => 'शनिः',
'january'       => 'जनुवरि',
'february'      => 'फेब्रुवरि',
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
'july-gen'      => 'जूलय्',
'august-gen'    => 'ओगस्ट्',
'september-gen' => 'सप्तम्बर्',
'october-gen'   => 'अष्टोबर्',
'november-gen'  => 'नवम्बर्',
'december-gen'  => 'दशम्बर्',
'jan'           => 'जनु॰',
'feb'           => 'फेब्रु॰',
'mar'           => 'मार्च्',
'apr'           => 'एप्रि॰',
'may'           => 'मेय्',
'jun'           => 'जून्',
'jul'           => 'जूल॰',
'aug'           => 'ओग॰',
'sep'           => 'सप्तं॰',
'oct'           => 'अष्टो॰',
'nov'           => 'नवं॰',
'dec'           => 'दशं॰',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|श्रेणी|श्रेण्यः }}',
'category_header'                => '"$1" इति श्रेण्यां पृष्ठाणि',
'subcategories'                  => 'उपश्रेण्यः',
'category-media-header'          => '"$1" इति श्रेण्यां माध्यमानि (मीडिया इति)।',
'category-empty'                 => "''अस्मिन् श्रेण्यां संप्रति न कोऽपि पृष्ठं, माध्यमं वा विद्यते।''",
'hidden-categories'              => '{{PLURAL:$1|लोपिता श्रेणी|लोपिताः श्रेण्यः}}',
'hidden-category-category'       => 'लोपिताः श्रेण्यः',
'category-subcat-count'          => '{{PLURAL:$2|अस्मन् श्रेण्यां केवला अधोलिखिता उपश्रेणी विद्यते|अस्मिन् श्रेण्यां {{PLURAL:$1|अधोलिखिता उपश्रेणी अस्ति|अधोलिखिताः $1 उपश्रेण्यः सन्ति}}, सकलाः उपश्रेण्यः $2 ।}}',
'category-subcat-count-limited'  => 'अस्मिन् श्रेण्यां {{PLURAL:$1|अधोलिखिता $1 उपश्रेणी अस्ति|अधोलिखितानि $1 उपश्रेण्यः सन्ति}}।',
'category-article-count'         => '{{PLURAL:$2|अस्मिन् श्रेण्यां केवलं इदं पृष्ठं विद्यते ।|अस्मिन् श्रेण्यां  {{PLURAL:$1|अधोलिखितं पृष्ठमस्ति|$1 अधोलिखितानि पृष्ठाणि सन्ति}}, सकलानि पृष्ठाणि $2 ।}}',
'category-article-count-limited' => 'अधोलिखितं {{PLURAL:$1|पृष्ठम् अस्मिन् श्रेण्याम् अस्ति|$1 पृष्ठाणि अस्मिन् श्रेण्यां सन्ति}}।',
'category-file-count'            => '{{PLURAL:$2|अस्मिन् श्रेण्यां केवला अधोलिखिता संचिका वर्तते।|अस्मिन् श्रेण्यां  {{PLURAL:$1|अधोलिखिता संचिका|अधोलिखिताः $1 संचिकाः}} वर्तन्ते, सकलाः संचिकाः - $2 ।}}',
'category-file-count-limited'    => 'एतस्यां श्रेण्यां {{PLURAL:$1|संचिका|$1 संचिकाः}} अधस्तात् सूचिता{{PLURAL:$1||ः}} -
The following {{PLURAL:$1|file is|$1 files are}} in the current category.',
'listingcontinuesabbrev'         => 'आगामि.',
'index-category'                 => 'सूचकांकितानि पृष्ठाणि',
'noindex-category'               => 'असूचकांकितानि पृष्ठाणि',

'mainpagetext' => 'मीडियाविकि तु सफलतया अन्तःस्थापितमस्ति',

'about'         => 'विषयः:',
'article'       => 'लेखनम्',
'newwindow'     => '(नवे गवाक्षे उद्घाट्यते)',
'cancel'        => 'निरसनम्',
'moredotdotdot' => 'अपि च...',
'mypage'        => 'मम पृष्ठम्',
'mytalk'        => 'मम सम्भाषणम्',
'anontalk'      => 'अस्य आइ.पी. संकेतस्य कृते संभाषणम्',
'navigation'    => 'सुचलनम्',
'and'           => '&#32;तथा च',

# Cologne Blue skin
'qbfind'         => 'अन्वेषयतु',
'qbbrowse'       => 'ब्राउस् इत्येतत् करोतु।',
'qbedit'         => 'सम्पादयतु',
'qbpageoptions'  => 'इदं पृष्ठम्',
'qbpageinfo'     => 'प्रसंगः',
'qbmyoptions'    => 'मम पृष्ठाणि',
'qbspecialpages' => 'विशिष्टपृष्ठाणि',
'faq'            => 'बहुधा पृष्टव्याः प्रश्नाः',
'faqpage'        => 'Project:बहुधा पृष्टव्याः प्रश्नाः',

# Vector skin
'vector-action-addsection'       => 'विषयं योजयतु',
'vector-action-delete'           => 'अपाकरोतु',
'vector-action-move'             => 'चालयतु',
'vector-action-protect'          => 'सुरक्षितं करोतु',
'vector-action-undelete'         => 'अपाकरणस्य निरसनम्',
'vector-action-unprotect'        => 'सुरक्षितीकरणस्य निरसनम्',
'vector-simplesearch-preference' => 'संवर्धिताः अन्वेषणोपक्षेपाः समर्थीकरोतु। (केवलं वैक्टर-स्किन् इत्यस्यार्थे)',
'vector-view-create'             => 'सृजतु',
'vector-view-edit'               => 'सम्पादयतु',
'vector-view-history'            => 'इतिहासं दर्शयतु',
'vector-view-view'               => 'पठतु',
'vector-view-viewsource'         => 'स्रोतसं दर्शयतु',
'actions'                        => 'क्रियाः',
'namespaces'                     => 'नामाकाशानि',
'variants'                       => 'प्रकीर्णत्वेन',

'errorpagetitle'    => 'विभ्रमः',
'returnto'          => '$1 इत्येतद् प्रति निवर्तताम्।',
'tagline'           => '{{SITENAME}} इत्यस्मात्',
'help'              => 'सहायम्',
'search'            => 'अन्वेषणम्',
'searchbutton'      => 'अन्विष्यतु',
'go'                => 'गच्छतु',
'searcharticle'     => 'गच्छतु',
'history'           => 'पृष्ठस्य इतिहासः',
'history_short'     => 'इतिहासः',
'updatedmarker'     => 'मम पौर्विक-आगमन-पश्चात् परिवर्तितानि',
'info_short'        => 'समाचारः',
'printableversion'  => 'मुद्रणीय पाठान्तरम्',
'permalink'         => 'स्थिरबन्धनम्',
'print'             => 'मुद्रयतु',
'edit'              => 'सम्पादयतु',
'create'            => 'सृजतु',
'editthispage'      => 'इदं पृष्ठं सम्पादयतु',
'create-this-page'  => 'इदं पृष्ठं सृज',
'delete'            => 'विनाशयतु',
'deletethispage'    => 'एतत् पृष्ठं अपाकरोतु',
'undelete_short'    => '{{PLURAL:$1|एकं सम्पादनं|$1 सम्पादनानि}} अनपाकरोतु',
'protect'           => 'सुरक्षित करोसि',
'protect_change'    => 'परिवर्तयतु',
'protectthispage'   => 'एतत्पृष्ठं सुरक्षितीकरोतु।',
'unprotect'         => 'असुरक्षितीकरोतु',
'unprotectthispage' => 'एतत्पृष्ठं असुरक्षितीकरोतु',
'newpage'           => 'नवीनपृष्ठम्',
'talkpage'          => 'अस्य पृष्ठस्य विषये चर्चां करोतु',
'talkpagelinktext'  => 'सम्भाषणम्',
'specialpage'       => 'विशेषपृष्ठम्',
'personaltools'     => 'वैयक्तिक उपकरणानि',
'postcomment'       => 'नवीन विभागः',
'articlepage'       => 'लेखनं पश्यतु',
'talk'              => 'चर्चा',
'views'             => 'दृश्यरूपाणि',
'toolbox'           => 'उपकरणपेटिका',
'userpage'          => 'प्रयोक्तृ-पृष्ठं पश्यतु',
'projectpage'       => 'प्रकल्प-पृष्ठं पश्यतु',
'imagepage'         => 'सञ्चिका-पृष्ठं पश्यतु',
'mediawikipage'     => 'सन्देश-पृष्ठं पश्यतु।',
'templatepage'      => 'संफलकपृष्ठं पश्यतु',
'viewhelppage'      => 'सहायपृष्ठं पश्यतु',
'categorypage'      => 'श्रेणी-पृष्ठं पश्यतु',
'viewtalkpage'      => 'चर्चां पश्यतु',
'otherlanguages'    => 'अन्यासु भाषासु',
'redirectedfrom'    => '($1 इत्यस्मात् अनुप्रेषितम्)',
'redirectpagesub'   => 'अनुप्रेषण-पृष्ठम्',
'lastmodifiedat'    => 'एतत् पृष्ठस्य अन्तिमपरिवर्तनं $1 दिवसे $2 वादने कृतम्',
'viewcount'         => 'एतत्पृष्ठं {{PLURAL:$1|एक वारं|$1 वारं}} दृष्टम् अस्ति',
'protectedpage'     => 'संरक्षितपृष्ठम्',
'jumpto'            => 'कूर्दयतु अत्र :',
'jumptonavigation'  => 'सुचलनम्',
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
'aboutpage'            => 'Project:विवरणम्',
'copyright'            => 'अस्य घटकानि $1 इत्यस्यान्तर्गतानि उपलब्धानि।',
'copyrightpage'        => '{{ns:project}}:प्रतिलिप्यधिकाराणि',
'currentevents'        => 'सद्य घटना',
'currentevents-url'    => 'Project:सद्यस्काः घटनाः',
'disclaimers'          => 'प्रत्याख्यानम्',
'disclaimerpage'       => 'Project:सामान्यं प्रत्याख्यानम्',
'edithelp'             => 'सम्पादनार्थं सहायः',
'edithelppage'         => 'Help:सम्पादनम्',
'helppage'             => 'Help:घटकानि',
'mainpage'             => 'मुख्यपृष्ठम्',
'mainpage-description' => 'मुख्यपृष्ठम्',
'policy-url'           => 'Project:नीतिः',
'portal'               => 'समुदाय द्वारकम्',
'portal-url'           => 'Project:समुदाय द्वारकम्',
'privacy'              => 'नैजता-नीतिः',
'privacypage'          => 'Project:नैजता-नीतिः',

'badaccess'        => 'अनुज्ञा-विभ्रमः',
'badaccess-group0' => 'भवदर्थम्, अत्र प्रार्थिता क्रिया प्रवर्तितुं न अनुमतम्।',
'badaccess-groups' => 'भवता प्रार्थिता क्रिया केवले {{PLURAL:$2|अस्मिन् समूहे|एतेषु समूहेषु}} अनुमता अस्ति: $1।',

'versionrequired'     => 'मीडीयाविके: $1 संस्करण आवश्यकः ।',
'versionrequiredtext' => 'एतत्पृष्ठं प्रयोक्तुं मीडियाविकि इत्येतस्य $1तमा आवृत्तिः आवश्यकी। पश्यतु [[Special:Version|आवृत्ति-सूचिका]]',

'ok'                      => 'आम्',
'pagetitle'               => '',
'retrievedfrom'           => '"$1" इत्यस्मात् गृहीतम्',
'youhavenewmessages'      => 'भवदर्थम् $1 सन्ति। ($2).',
'newmessageslink'         => 'नूतनाः संदेशाः',
'newmessagesdifflink'     => 'अन्तिमपरिवर्तनम्',
'youhavenewmessagesmulti' => 'भवतः कृते $1 मध्ये नूतन सन्देशम् अस्ति',
'editsection'             => 'सम्पादयतु',
'editold'                 => 'सम्पादनम्',
'viewsourceold'           => 'स्रोतः पश्यतु',
'editlink'                => 'सम्पादयतु',
'viewsourcelink'          => 'स्रोतसम् दर्शयतु',
'editsectionhint'         => 'विभागं सम्पादयतु: $1',
'toc'                     => 'अन्तर्विषयाः',
'showtoc'                 => 'दर्शयतु',
'hidetoc'                 => 'गोपयतु',
'thisisdeleted'           => '$1 दर्शयेत् वा प्रत्यानयेत् वा?',
'viewdeleted'             => '$1 पश्यतु?',
'feedlinks'               => 'अनुबन्ध:',
'site-rss-feed'           => '$1 आरएसएस पूरणम्',
'site-atom-feed'          => '$1 ऍटम पूरणम्',
'page-rss-feed'           => '"$1" आरएसएस-पूरणम्',
'page-atom-feed'          => '"$1" ऍटम अनुबन्ध',
'red-link-title'          => '$1 (पृष्ठं इदानीं यावत् न रचितम्)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'पृष्ठम्',
'nstab-user'      => 'प्रयोक्तृपृष्ठम्',
'nstab-media'     => 'माध्यमपृष्ठम्',
'nstab-special'   => 'विशिष्टपृष्ठम्',
'nstab-project'   => 'प्रकल्पपृष्ठम्',
'nstab-image'     => 'सञ्चिका',
'nstab-mediawiki' => 'सन्देशः',
'nstab-template'  => 'संफलकम्',
'nstab-help'      => 'सहायपृष्ठम्',
'nstab-category'  => 'श्रेणी',

# Main script and global functions
'nosuchaction'      => 'तथाविध न कर्म',
'nosuchspecialpage' => 'एतादृश विशेष पृष्टम् नास्ति',

# General errors
'error'                => 'विभ्रम',
'databaseerror'        => 'दत्ताधार-विभ्रमः',
'readonly'             => 'डाटाबेस बन्धितमस्ति',
'missing-article'      => 'दत्ताधारेण(डाटाबेस् इत्यनेन) "$1" $2 इतिनामकं पृष्ठं नैव प्राप्तम्, यत्तु प्राप्तीभवितव्यमासीत्।

कदाचित् एवं तु अवसिते सति अंतरे अथवा अपाकृतस्य पृष्ठस्य इतिहास-संबंधनात् भवति।


यदि न एवं विद्यते, तदा भवता क्रमादेश-कीटकं अन्विष्टम्।
कृपया केनचित् [[Special:ListUsers/sysop|प्रचालकेन]] सह अस्य पृष्ठस्य URL इत्येतद्- ज्ञापनपूर्वकं संभाषताम्।',
'missingarticle-rev'   => '(आवृत्तिः# :$1)',
'missingarticle-diff'  => '(व्यतिरेक: $1, $2)',
'internalerror'        => 'आन्तरिका त्रुटिः',
'internalerror_info'   => 'आन्तरिका त्रुटिः: $1',
'directorycreateerror' => '$1 इति निर्देशिकां स्रष्टुं न अपारयत्',
'unexpected'           => 'अप्रतीक्षितमूल्यम् : "$1"="$2"।',
'formerror'            => 'त्रुटिः : प्रारूपं समर्पयितुं न अपारयत्',
'badarticleerror'      => 'अस्मिन् पृष्ठे एषा क्रिया कर्तुं न शक्या।',
'badtitletext'         => 'प्रार्थितं पृष्ठ-शीर्षकं अमान्यं वा, रिक्तं वा, अथवा अशुद्धतया संबंद्धं आंतर्भाषिकं, आंतर्विकीयं वा शीर्षकमस्ति। अस्मिन् एकं एकाधिकानि वा एतादृशाणि अक्षराणि विद्यन्ते येषां प्रयोगं शीर्षकेषु अशक्यम्।',
'viewsource'           => 'स्रोतः दर्शयतु',
'viewsourcefor'        => '$1 कृते',

# Virus scanner
'virus-unknownscanner' => 'अज्ञातं विषाणुप्रतिकारकम्:',

# Login and logout pages
'welcomecreation'         => '==स्वागतम्‌, $1!==
भवतः/भवत्याः लेखा सृष्टितमस्ति।
भवतः/भवत्याः [[Special:Preferences|{{SITENAME}} इष्टतमानि]]  स्वच्छानुसारं कर्तुं स्मरणा करोतु।',
'yourname'                => 'प्रयोक्तृ-नाम :',
'yourpassword'            => 'रहस्यवाक् :',
'yourpasswordagain'       => 'रहस्यवाक् पुनः लिखतु।',
'remembermypassword'      => 'अस्मिन् संगणके मम प्रवेशसंबंधि-सूचनाः स्मरतु (अधिकतम् $1 {{PLURAL:$1|दिन्|दिन्}})',
'login'                   => 'प्रविशतु',
'nav-login-createaccount' => 'प्रविशतु / लेखां सृजतु',
'userlogin'               => 'प्रविशतु / लेखां सृजतु',
'userloginnocreate'       => 'प्रविशतु',
'logout'                  => 'बहिर्गच्छतु',
'userlogout'              => 'बहिर्गच्छति',
'notloggedin'             => 'भवान् प्रविशितवान् नास्ति',
'nologinlink'             => 'लेखां रचयतु',
'createaccount'           => 'लेखां रचयतु',
'gotaccountlink'          => 'प्रविशतु',
'createaccountreason'     => 'कारणम्',
'createaccounterror'      => '$1 इति लेखां स्रष्टुं न अपारयत्',
'loginsuccesstitle'       => 'सुस्वागतम्‌। प्रवेशः सिद्धः।',
'mailmypassword'          => 'नूतनं रहस्यवाक् विद्युत्पत्रेण प्रेषयतु',
'accountcreated'          => 'खाता निर्मित',
'loginlanguagelabel'      => 'भाषा : $1',

# Password reset dialog
'resetpass_text'            => '<!-- पाठं अत्र लिखतु -->',
'oldpassword'               => 'पुरातन रहस्यवाक् :',
'newpassword'               => 'नूतन रहस्यवाक् :',
'retypenew'                 => 'नूतन रहस्यवाक् पुनर्लिखतु :',
'resetpass-submit-loggedin' => 'रहस्यवाक् परिवर्तयतु',
'resetpass-submit-cancel'   => 'निरसनम् करोतु',
'resetpass-temp-password'   => 'अस्थिर रहस्यवाक् :',

# Edit page toolbar
'bold_sample'     => 'स्थूलाक्षरम्',
'bold_tip'        => 'स्थूलाक्षरम्',
'italic_sample'   => 'तिर्यक् अक्षरम्',
'italic_tip'      => 'तिर्यक् अक्षरम्',
'link_sample'     => 'संबंधनस्य शीर्षकम्',
'link_tip'        => 'अन्तर्गतं संबंधनम्',
'extlink_sample'  => 'http://www.example.com संबंधनस्य शीर्षकम्',
'extlink_tip'     => 'बाह्य-संबंधनम् (अवश्यमेव  http:// इति पूर्वलग्नं योक्तव्यम्)',
'headline_sample' => 'शीर्षकम्',
'headline_tip'    => 'द्वितीय-स्तरीयं शीर्षकम्',
'math_sample'     => 'सूत्रवाक्यं अत्र निवेशयतु',
'math_tip'        => 'गणितीयसूत्रम् (LaTeX)',
'nowiki_sample'   => 'अप्रारूपीकृतं पाठं अत्र निवेशयतु',
'nowiki_tip'      => 'विकिप्रारूपणं अवगणना कुरु',
'image_sample'    => 'उदाहरणम्.jpg',
'image_tip'       => 'अन्तर्गता संचिका',
'media_sample'    => 'उदाहरणम्.ogg',
'media_tip'       => 'संचिका-संबंधनम्',
'sig_tip'         => 'भवतः हस्ताक्षराणि समयेन सह',
'hr_tip'          => 'क्षैतिज-रेखा (न्यूनतया प्रयोक्तव्या)',

# Edit pages
'summary'                          => 'संग्रहः :',
'subject'                          => 'विषयः/शीर्षकम् :',
'minoredit'                        => 'इदं लघु परिवर्तनम्',
'watchthis'                        => 'एतत् पृष्ठं निरीक्षताम्',
'savearticle'                      => 'पृष्ठं रक्षतु',
'preview'                          => 'प्राग्दृश्यम्',
'showpreview'                      => 'प्राग्दृश्यं दर्शयतु',
'showdiff'                         => 'परिवर्तनानि दर्शयतु',
'anoneditwarning'                  => "'''सावधानो भवतु:''' भवता प्रवेशं न कृतम्।
अस्य पृष्ठस्य इतिहासे भवतः आइ-पी-संकेतः अंकितः भविष्यति।",
'summary-preview'                  => 'सारांशस्य प्राग्दृश्यम् :',
'blockednoreason'                  => 'न किमपि कारणम् दत्तम्।',
'loginreqtitle'                    => 'प्रवेशनम् आविश्यकम्',
'loginreqlink'                     => 'प्रविशतु',
'accmailtitle'                     => 'पास्वेड् पप्रेषितम्',
'newarticle'                       => '(नवीनम्)',
'newarticletext'                   => "भवता एतादृशमेकं पृष्टं प्रति संबंधनम् अनुसृतम्, यत्पृष्ठं न इदानींयावत् विद्यते।

पृष्ठं स्रष्टुम् अधःप्रदत्तायां पेटिकायां टंकणं करोतु (सहाय्यार्थं [[{{MediaWiki:Helppage}}|अत्र]] क्लिक्करोतु।

चेद्भवान् अत्र भ्रान्तिना आनीतोऽस्ति तदा स्वकीये ब्राउसर् इत्यस्मिन् '''बैक्''' इत्यस्मिन् क्लिक्करोतु।)",
'noarticletext'                    => 'अस्मिन् पृष्ठे संप्रति न कोऽपि पाठः विद्यते। भवान् विकिपीडियावर्तिषु अन्येषु पृष्ठेषु इदं [[Special:Search/{{PAGENAME}}|शीर्षकम् अन्विष्टुं शक्नोति]] अथवा इदं पृष्ठं या फिर यह लेख 

<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}  related logs अन्विष्टुं शक्नोति],
अथवा [{{fullurl:{{FULLPAGENAME}}|action=edit}} इदं पृष्ठं स्रष्टुं शक्नोति]</span>.',
'previewnote'                      => "'''स्मरणीयं यदेतत् केवलं प्राग्दृश्यमस्ति।'''
भवता कृतानि परिवर्तनानि इदानींयावत् न रक्षितानि सन्ति!",
'editing'                          => '$1 सम्पाद्यते',
'editingsection'                   => '$1 संपादनम् (विभागः)',
'yourdiff'                         => 'अन्तरानि',
'copyrightwarning'                 => "कृपया संस्मर्तव्यं यत् {{SITENAME}} इत्येतद् प्रति कृतानि सर्वाणि योगदानानि $2 इत्यस्य प्रतिबंधांतर्गतानि सन्ति (अधिकाय ज्ञानाय $1 इत्येतद् पश्यतु)।

यदि भवान् स्वकीयानि लिखितानि परिवर्तमन्तश्च, पुनः वितर्यमन्तश्च न द्रष्टुमिच्छति तदा मा कृपया माऽत्र योगदानं करोतु। <br />

भवान् एतदपि प्रमाणीकरोति यत् एतद् भवता स्वतः लिखितमस्ति अथवा कस्माच्चत् जनार्पितात् वा मुक्तात् वा स्रोतसः प्रतिलिपीकृतमस्ति।

'''प्रतिलिप्यधिकारयुतान् लेखान्, अनुज्ञां विना, माऽत्र प्रददातु!'''",
'templatesused'                    => 'अस्मिन् पृष्ठे प्रयुक्ताः {{PLURAL:$1|बिंबधराः|बिंबधराः}}:',
'templatesusedpreview'             => 'अस्मिन् प्राग्दृश्ये प्रयुक्ताः {{PLURAL:$1|बिंबधराः |बिंबधराः}}:',
'template-protected'               => '(संरक्षितम्)',
'template-semiprotected'           => '(अर्धसंरक्षितम्)',
'hiddencategories'                 => 'इदं पृष्ठं {{PLURAL:$1|1 निगूढस्य श्रेण्याः |$1 निगूढानां श्रेणीनां}} सदस्यत्वेन विद्यते :',
'permissionserrorstext-withaction' => 'भवान् $2 इत्येतदर्थम् अनुज्ञां न धारयति, अस्य {{PLURAL:$1|कारणम्|कारणानि}}:',
'log-fulllog'                      => 'पूर्ण प्रवर्तनरेख पश्यतु',

# History pages
'viewpagelogs'           => 'अस्य पृष्ठस्य लॉंग् इत्येतद् दर्शयतु',
'currentrev-asof'        => 'वर्तमती आवृत्तिः $1 इति समये',
'revisionasof'           => '$1 इत्यस्य आवृत्तिः',
'previousrevision'       => '← पुरातनाः आवृत्तयः',
'nextrevision'           => 'नूतनतरा आवृत्तिः →',
'currentrevisionlink'    => 'सद्यःकालीना आवृत्तिः',
'cur'                    => 'नवतरम्',
'last'                   => 'पूर्वतनम्',
'page_first'             => 'प्रथमम्',
'page_last'              => 'अन्तिमम्',
'history-fieldset-title' => 'सुगमनस्य(ब्राउस् इत्यस्य) इतिहासः',
'histfirst'              => 'पुरातनतमम्',
'histlast'               => 'नूतनतमम्',

# Revision feed
'history-feed-item-nocomment' => '$2 मद्ये $1',

# Revision deletion
'rev-delundel'               => 'दर्शयतु/गोपयतु',
'rev-showdeleted'            => 'दर्शयतु',
'revdelete-show-file-submit' => 'हाँ',
'revdelete-radio-set'        => 'हाँ',
'revdelete-log'              => 'कारणम् :',
'revdel-restore'             => 'दृश्यतां परिवर्तयतु',
'revdel-restore-deleted'     => 'विलोपितानि संशोधनानि',
'revdel-restore-visible'     => 'दृष्टिगोचर संशोधनानि',
'pagehist'                   => 'पुटस्य चरित्रम्',
'revdelete-uname'            => 'उपयोक्तृ-नाम',
'revdelete-otherreason'      => 'अन्य/अधिक कारणम् :',
'revdelete-reasonotherlist'  => 'अन्य कारणानि',

# History merging
'mergehistory-reason' => 'कारणम् :',

# Merge log
'revertmerge' => 'पृथक् करोतु',

# Diffs
'history-title'           => '"$1" इत्येतस्य आवर्तनेतिहासः :',
'difference'              => '(आवृत्तीनां मध्ये अन्तरम्)',
'lineno'                  => 'पंक्तिः $1:',
'compareselectedversions' => 'चितानां आवृत्तीनां परस्परं तुलनां करोतु',
'editundo'                => 'अकरोतु',

# Search results
'searchresults'                    => 'अन्वेषण फलानि',
'searchresults-title'              => '"$1" इत्यस्य कृते अन्वेषण-फलानि',
'searchresulttext'                 => '{{SITENAME}} इत्यस्मिन् अन्वेषणे सहाय्यार्थम् [[{{MediaWiki:Helppage}}|{{int:help}}]] इत्येतत् पश्यतु ।',
'searchsubtitle'                   => 'भवान् \'\'\'[[:$1]]\'\'\'([[Special:Prefixindex/$1|सर्वाणि "$1" इत्यस्माद् आरभमन्तः पृष्ठाणि]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|सर्वाणि "$1" इत्येतत्प्रति संबद्धानि पृष्ठाणि]]) इत्यस्य कृते अन्विष्टवान्।',
'searchsubtitleinvalid'            => "भवता '''$1''' इत्यस्य कृते अन्वेषणं कृतम्",
'notitlematches'                   => 'न कस्यापि पृष्ठस्य शीर्षकम् अस्य समम्।',
'notextmatches'                    => 'न कस्यापि पृष्ठस्य पाठः अस्य सममस्ति',
'prevn'                            => 'पूर्वतनानि {{PLURAL:$1|$1}}',
'nextn'                            => 'आगामि{{PLURAL:$1|$1}}',
'prevn-title'                      => 'विगत {{PLURAL:$1|परिणाम| परिणामानि}}',
'nextn-title'                      => 'अवर {{PLURAL:$1|  १ परिणाम|  $1 परिणामानि}}',
'shown-title'                      => 'प्रत्येक पृष्ठे $1 {{PLURAL:$1|परिणामं|परिणामान्}}प्रदर्शयतु',
'viewprevnext'                     => 'दर्शयतु ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new'                   => "'''अश्मिन् विकिते \"[[:\$1]]\" पृष्ठं सृजतु!'''",
'searchprofile-articles'           => 'सामग्री पृष्ठानि',
'searchprofile-project'            => 'सहायता तथा परियोजनासहित  पृष्ठानि',
'searchprofile-images'             => 'बहुमाध्यमः',
'searchprofile-everything'         => 'सर्वम्',
'searchprofile-advanced'           => 'उन्नतमः',
'searchprofile-articles-tooltip'   => '$1 स्थले अन्वेशयतु',
'searchprofile-project-tooltip'    => '$1 स्थले अन्वेशयतु',
'searchprofile-images-tooltip'     => 'सञ्चिकान् अन्वेशयतु',
'searchprofile-everything-tooltip' => '(चर्चा पृष्ठसहित) सम्पूर्ण पठनीय सामग्रीं अन्वेषयतु',
'searchprofile-advanced-tooltip'   => 'विशेष नामस्थाने अन्वेषयतु',
'search-result-size'               => '$1 ({{PLURAL:$2|1 शब्दम्|$2 शब्दे}})',
'search-redirect'                  => '($1 इतीदं अनुप्रेषितम्)',
'search-section'                   => '(विभागः $1)',
'search-suggest'                   => 'किं भवतः आशयः एवमस्ति : $1',
'search-interwiki-caption'         => 'बन्धु-प्रकल्पाः',
'search-interwiki-default'         => '$1 परिणामाः :',
'search-interwiki-more'            => '(अधिकानि)',
'search-mwsuggest-enabled'         => 'उपक्षेपेभ्यः सह',
'search-mwsuggest-disabled'        => 'नात्र उपक्षेपाः',
'searchall'                        => 'सर्वाणि',
'showingresultsheader'             => "'''$4''' निमित्तये {{PLURAL:$5|'''$3'''स्य '''$1'''  परिणाम|'''$3'''स्य '''$1 - $2'''  परिणामानि}}",
'nonefound'                        => "'''सूचना''': स्वतः अत्र केषुचिदेव नामाकाशेषु अन्वेषणं क्रियते।

सकले घटके अन्वेषणं कर्तुं स्व अन्वेषणपदेभ्यः पूर्वं ''all:'' इति योजयतु, अथवा इष्टं नामाकाशं पूर्वलग्नरूपेण योजयतु।",
'powersearch'                      => 'प्रगतम् अन्वेषणम्',
'powersearch-legend'               => 'प्रगतम् अन्वेषणम्',
'powersearch-ns'                   => 'नामाकाशेषु अन्विष्यतु :',
'powersearch-redir'                => 'अनुप्रेषणानां सूचिकां दर्शयतु।',
'powersearch-field'                => 'इत्यस्मै अन्विष्यतु',
'powersearch-toggleall'            => 'सर्वम्',
'powersearch-togglenone'           => 'नास्ति',
'search-external'                  => 'वाह्य अन्वेषणम्',
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
'mypreferences'             => 'मम वरीयांसि',
'prefs-edits'               => 'सम्पादनानां सख्याः',
'prefsnologin'              => 'नैव प्रविष्ट',
'changepassword'            => 'प्रवेश शव्दं परिवर्तयतु',
'prefs-skin'                => 'त्वचा',
'skin-preview'              => 'प्राग्दृश्यम्',
'datedefault'               => 'वरीयांसि नास्ति',
'prefs-datetime'            => 'दिनांक तथा समय',
'prefs-personal'            => 'योजकः व्यक्तिरेखा',
'prefs-rc'                  => 'नवतमानि परिवर्तनानि',
'prefs-watchlist'           => 'दृष्टि सूची',
'prefs-watchlist-days'      => 'दृष्टि सूची दर्शनार्थे  दिवसानि',
'prefs-watchlist-days-max'  => 'अधिकतम ७ दिवसानि',
'prefs-watchlist-edits-max' => 'अधिकतम संख्या: १०००',
'prefs-misc'                => 'विविधः',
'prefs-resetpass'           => 'प्रवेश शव्दं परिवर्तयतु',
'prefs-email'               => 'इमेल वैकल्पिकाः',
'prefs-rendering'           => 'स्वरुपः',
'saveprefs'                 => 'संरक्षतु',
'resetprefs'                => 'असंरक्षित परिवर्तनानि विलोपयतु',
'restoreprefs'              => 'समग्राः व्यवस्थादय व्यवस्थानुसारे पुनः संरक्षतु',
'prefs-editing'             => 'सम्पादनशील:',
'rows'                      => 'पंक्ति',
'columns'                   => 'अध: पंक्त्याः',
'searchresultshead'         => 'अन्वेषणम्',
'resultsperpage'            => 'प्रति पृष्ट हिट्स:',
'stub-threshold-disabled'   => 'निष्क्रियः',
'recentchangesdays'         => 'दिवसानि पर्यन्तो सद्यावधि-परिवर्तनानि दृश्यतु:',
'recentchangesdays-max'     => 'अधिकतम $1 {{PLURAL:$1|दिवसः|दिवसानि}}',
'recentchangescount'        => 'सम्पादन संख्यकानि व्यवस्थानुसारेण दृश्यतु:',
'yourlanguage'              => 'भाषा:',
'email'                     => 'विद्युत्पत्रव्यवस्था',

# Groups
'group-sysop' => 'प्रबंधकाः',

'grouppage-sysop' => '{{ns:project}}:प्रचालकाः',

# User rights log
'rightslog' => 'प्रयोक्तृ-अधिकार-सूचिका',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'इदं पृष्ठं संपादयतु',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|परिवर्तनम्|परिवर्तनानि}}',
'recentchanges'                  => 'नवतमानि परिवर्तनानि',
'recentchanges-legend'           => 'नवतमानां परिवर्तनानां विकल्पाः',
'recentchanges-feed-description' => 'अस्मिन् पूरणे विकि इत्यस्मिन् भूतानि नवतमानि परिवर्तनानि पश्यतु।',
'rcnote'                         => "अधस्तात् {{PLURAL:$1|'''1''' परिवर्तनमस्ति|अंतिमानि '''$1''' परिवर्तनानि सन्ति}},{{PLURAL:$2|गते दिवसे|'''$2''' गतेषु दिवसेषु}}, $5, $4 इति समये।",
'rclistfrom'                     => '$1 इत्यस्मात् आरभमन्तः नूतनानि परिवर्तनानि दर्शयतु',
'rcshowhideminor'                => '$1 लघूनि संपादनानि',
'rcshowhidebots'                 => '$1 बोट् इत्येतानि',
'rcshowhideliu'                  => '$1 प्रवेशिताः प्रयोक्तारः',
'rcshowhideanons'                => 'अनामकाः योजकाः $1',
'rcshowhidemine'                 => '$1 मम सम्पादनानि',
'rclinks'                        => 'अंतिमानि $1 परिवर्तनानि अंतिमेषु $2 दिनेषु, दर्शयतु<br />$3',
'diff'                           => 'अन्तरम्',
'hist'                           => 'इतिहासः',
'hide'                           => 'गोपयतु',
'show'                           => 'दर्शयतु',
'minoreditletter'                => 'लघु',
'newpageletter'                  => 'नवी॰',
'boteditletter'                  => 'यन्त्रं',
'newsectionsummary'              => '/* $1 */ नवीन विभागः',
'rc-enhanced-expand'             => 'विवरणानि दर्शयतु (जावालिपि आवश्यकम्)',
'rc-enhanced-hide'               => 'विवरणानि विलोपयतु',

# Recent changes linked
'recentchangeslinked'         => 'पृष्ठ-सम्बन्धि-परिवर्तनानि',
'recentchangeslinked-feed'    => 'पृष्ठ-सम्बन्धितानि परिवर्तनानि',
'recentchangeslinked-toolbox' => 'पृष्ठ-सम्बन्धितानि परिवर्तनानि',
'recentchangeslinked-title'   => '"$1" इत्यस्मिन् भूतानि परिवर्तनानि',
'recentchangeslinked-summary' => "इदं पृष्ठं दर्शयति पृष्ठविशेषेण सह संबद्धीकृतेषु पृष्ठेषु परिवर्तनानि (अथवा श्रेणीविशेषे अन्तर्भूतेषु पृष्ठेषु परिवर्तनानि)।

[[Special:Watchlist|भवतः निरीक्षासूचिकायां]] धारितानि पृष्ठाणि '''स्थूलाक्षरैः''' दर्शितानि।",
'recentchangeslinked-page'    => 'पृष्ठ-नाम :',
'recentchangeslinked-to'      => 'अस्य स्थाने इदं पृष्ठं प्रति संबद्धानां पृष्ठाणां परिवर्तनानि दर्शयतु',

# Upload
'upload'        => 'संचिकाम् उद्भारयतु',
'uploadbtn'     => 'संचिकाम् उद्भारयतु',
'uploadlogpage' => 'उद्भारण-सूचिका',
'filedesc'      => 'सांक्षेपिक',
'uploadedimage' => '"[[$1]]" इत्येतद् उद्भारितमस्ति',

# Special:ListFiles
'imgfile' => 'संचिका',

# File description page
'file-anchor-link'          => 'संचिका',
'filehist'                  => 'संचिकायाः इतिहासः',
'filehist-help'             => 'संचिका तत्समये कीदृशी आसीदिति द्रष्टुं दिनांकः/समयः इत्यस्मिन् नोदयतु।',
'filehist-deleteone'        => 'विलोप',
'filehist-current'          => 'नवीनतमम्',
'filehist-datetime'         => 'दिनाङ्कः/समयः',
'filehist-thumb'            => 'अंगुष्ठनखाकारम्',
'filehist-thumbtext'        => '$1 समये विद्यमत्याः आवृत्तेः अंगुष्ठनखाकारम्',
'filehist-user'             => 'प्रयोक्ता',
'filehist-dimensions'       => 'आयामाः',
'filehist-comment'          => 'टिप्पणी',
'imagelinks'                => 'संचिका-संबंधनानि',
'linkstoimage'              => '{{PLURAL:$1|अधोलिखितं पृष्ठं| अधोलिखितानि $1 पृष्ठाणि}} इदं संचिकां प्रति संबंधनं {{PLURAL:$1|करोति| कुर्वन्ति}}।',
'sharedupload'              => 'इयं संचिका $1 इत्यस्मादस्ति, एषा खलु अन्येष्वपि प्रकल्पेषु प्रयोक्तुं शक्यते।',
'uploadnewversion-linktext' => 'अस्य पृष्ठस्य नूतनाम् आवृत्तिं उद्भारयतु',

# Random page
'randompage' => 'अविशिष्ट पृष्ठ',

# Statistics
'statistics'              => 'स्थितिगणितम्',
'statistics-users-active' => 'सक्रिय सदस्य',

'doubleredirects' => 'दुगुनी-अनुप्रेषिते',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|बैटम्|बैटानि}}',
'nmembers'      => '$1 {{PLURAL:$1|सदस्यः|सदस्याः}}',
'prefixindex'   => 'पूर्वलग्नेन सहितानि सर्वाणि पृष्ठाणि',
'longpages'     => 'दीर्घाणि पृष्ठाणि',
'newpages'      => 'नूतनानि पृष्ठाणि',
'ancientpages'  => 'प्राचीनतम् पृष्ठा',
'move'          => 'नाम परिवर्तयतु',
'movethispage'  => 'इदं पृष्ठं चालयतु',
'pager-newer-n' => '{{PLURAL:$1|नूतनतरम् 1|नूतनतराणि $1}}',
'pager-older-n' => '{{PLURAL:$1|पुरातनतरम् 1|पुरातनतराणि $1}}',

# Book sources
'booksources'               => 'पुस्तक-स्रोतांसि',
'booksources-search-legend' => 'पुस्तक-स्रोतांसि अन्विष्यतु',
'booksources-go'            => 'गच्छतु',

# Special:Log
'log' => 'लॉग् इत्येतानि',

# Special:AllPages
'allpages'       => 'सकलानि पृष्ठाणि',
'alphaindexline' => '$1 इत्यस्मात् $2 इतीदं यावत्',
'prevpage'       => 'पूर्वपृष्ठम् ($1)',
'allpagesfrom'   => 'इत्यस्मात् आरभमन्तः पृष्ठाणि दर्शयतु :',
'allpagesto'     => 'दर्शयतु पृष्ठाणि येषाम् अंतम् एवम् :',
'allarticles'    => 'सर्वाणि पृष्ठाणि',
'allpagessubmit' => 'गच्छतु',

# Special:Categories
'categories' => 'वर्ग',

# Special:LinkSearch
'linksearch' => 'बाह्य-संबंधनानि',

# Special:Log/newusers
'newuserlogpage'          => 'प्रयोक्तृ-सृजन-सूचिका',
'newuserlog-create-entry' => 'नूतन-प्रयोक्तृ-लेखा',

# Special:ListGroupRights
'listgrouprights-members' => '(सदस्यानां सूचिका)',

# E-mail user
'emailuser'    => 'एनं प्रयोक्तारं विद्युत्पत्रं (ई-मेल् इत्येतद्) प्रेषयतु',
'emailsubject' => 'विषयः',
'emailmessage' => 'सन्देशः :',

# Watchlist
'watchlist'         => 'मम निरीक्षासूचिका',
'mywatchlist'       => 'मम निरीक्षासूचिका',
'addedwatch'        => 'निरीक्षासूचिकायां योजितम्',
'addedwatchtext'    => 'भवतः [[Special:Watchlist|ध्यानसूचिकायां]] "[[:$1]]" इत्येतत् योजितमस्ति।
इदानींप्रभृति अस्मिन् पृष्ठे तथा अस्य चर्चापृष्ठे सन्तः परिवर्तनानि भवतः निरीक्षासूचिकायां द्रक्ष्यन्ते तथा च [[Special:RecentChanges|सद्यःपरिवर्तितानां सूचिकायां]] इदं पृष्ठं स्थूलाक्षरैः द्रक्ष्यते, यस्मात् भवान् सरलतया इदं पश्यतु <p>निरीक्षासूचिकातः निराकर्तुमिच्छति चेत्, "मा निरीक्षताम्" इत्यसमिन् नोदयतु।',
'removedwatch'      => 'निरीक्षासूचिकातः निराकृतम्।',
'removedwatchtext'  => '"[[:$1]]" इति पृष्ठं [[Special:Watchlist|भवतः निरीक्षासूचिकातः]] निराकृतमस्ति।',
'watch'             => 'निरीक्षताम्',
'watchthispage'     => 'इदं पृष्ठं निरीक्षताम्',
'unwatch'           => 'मा निरीक्षताम्',
'watchlist-details' => '{{PLURAL:$1|$1 पृष्ठं|$1 पृष्ठाणि}} भवतः निरीक्षासूचिकायां सन्ति, संभाषणपृष्ठाणि नात्र गणितानि।',
'wlshowlast'        => 'अंतिमानि ($1 होराः $2 वासराः) $3 इति दर्शयतु',
'watchlist-options' => 'निरीक्षा-सूचिका विकल्पाः',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'निरीक्षते...',
'unwatching' => 'निरीक्षाम् अपाकरोति...',

# Delete
'deletepage'            => 'पृष्ठं निराकरोतु।',
'confirmdeletetext'     => 'भवान् एकं पृष्ठं तस्य अखिलेन इतिहासेन सहितं अपाकर्तुं प्रवृत्तोऽस्ति। कृपया सुपुष्टीकरोतु यत् भवतः एतदेव आशयः, यद् भवता अस्य परिणामाः सुविज्ञाताः सन्ति तथा च भवता क्रियैषा [[{{MediaWiki:Policy-url}}| यथानीति]] सम्पाद्यते।',
'actioncomplete'        => 'कार्य समापनम्',
'deletedtext'           => '"<nowiki>$1</nowiki>" इत्येतद् अपाकृतमस्ति।
सद्यःकृतानां अपाकरणानाम् अभिलेखः $2 इत्यस्मिन् पश्यतु।',
'deletedarticle'        => '"[[$1]]" अपाकृतमस्ति।',
'dellogpage'            => 'अपाकरणानां सूचिका',
'deletecomment'         => 'कारणम् :',
'deleteotherreason'     => 'अपरं/अतिरिक्तं कारणम् :',
'deletereasonotherlist' => 'इतर कारणम्',

# Rollback
'rollbacklink' => 'पूर्ण-प्रतिगमनम्',

# Protect
'protectlogpage'              => 'सुरक्षा-सूचिका',
'protectedarticle'            => '"[[$1]]" इत्येतद् सुरक्षितीकृतमस्ति',
'modifiedarticleprotection'   => '"[[$1]]" इत्येतदर्थं सुरक्षा-स्तरः परिवर्तित: :',
'protectcomment'              => 'कारणम् :',
'protectexpiry'               => 'अवसानम् :',
'protect_expiry_invalid'      => 'अवसान-समयः अमान्योऽस्ति।',
'protect_expiry_old'          => 'अवसान-समयः अतीतोऽस्ति।',
'protect-text'                => "'''<nowiki>$1</nowiki>''' इति पृष्ठस्य कृते सुरक्षा-स्तरं भवान् अत्र दृष्टुं शक्नोति, तथा च तं परिवर्तयितुं शक्नोति।",
'protect-locked-access'       => "भवान् अस्य पृष्ठस्य सुरक्षा-स्तरं परिवर्तयितुम् अनुज्ञां न धारयति। '''$1''' इति पृष्ठस्य अधुनातनः सुरक्षा-स्तरः :",
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

# Restriction levels
'restriction-level-sysop'         => 'पूर्ण सुरक्षित',
'restriction-level-autoconfirmed' => 'अर्ध सुरक्षित',

# Undelete
'undeletelink'     => 'दर्शयतु/पुनःस्थापयतु',
'undeleteviewlink' => 'द्र्ष्टुमिच्छामि',
'undeletedarticle' => '"[[$1]]" इत्येतद् पुनःस्थापितमस्ति।',

# Namespace form on various pages
'namespace'      => 'नामाकाशः :',
'invert'         => 'चयनं विपरीतीकरोतु',
'blanknamespace' => '(मुख्यः)',

# Contributions
'contributions'       => 'प्रयोक्तॄणां योगदानानि',
'contributions-title' => 'प्रयोक्तॄणां योगदानानि $1',
'mycontris'           => 'मम योगदानानि',
'contribsub2'         => '$1 इत्येतदर्थम् ($2)',
'uctop'               => '(शीर्षम्)',
'month'               => 'अस्मात् मासात् (पुरातनतराणि च):',
'year'                => 'अस्मात् वर्षात् (पूर्वतराणि च):',

'sp-contributions-newbies'  => 'केवलानां नूतन-लेखानां योगदानानि दर्शयतु',
'sp-contributions-blocklog' => 'निरोधानां सूचिका',
'sp-contributions-talk'     => 'संभाषणम्',
'sp-contributions-search'   => 'योगदानानां कृते अन्विष्यतु',
'sp-contributions-username' => 'आइ.पी.संकेतः अथवा प्रयोक्तृ-नाम :',
'sp-contributions-submit'   => 'अन्वेषणम्',

# What links here
'whatlinkshere'            => 'केभ्यः पृष्ठेभ्यः सम्बद्धम्',
'whatlinkshere-title'      => '"$1" इत्यस्मात् संबंधितानि पृष्ठाणि',
'whatlinkshere-page'       => 'पृष्ठम् :',
'linkshere'                => "अधोलिखितानि पृष्ठाणि '''[[:$1]]''' इत्येतद् प्रति संबंधनं कुर्वन्ति :",
'isredirect'               => 'अनुप्रेषण-पृष्ठम्',
'istemplate'               => 'मिलापयतु',
'isimage'                  => 'चित्र-संबंधनम्',
'whatlinkshere-prev'       => '{{PLURAL:$1|पूर्वतनम्|पूर्वतनानि $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|आगामि|आगामिनि $1}}',
'whatlinkshere-links'      => '← संबंधनानि',
'whatlinkshere-hideredirs' => '$1 अनुप्रेषणानि',
'whatlinkshere-hidetrans'  => '$1 मिलापनानि (ट्रांस्क्लुसन् इत्येतानि)',
'whatlinkshere-hidelinks'  => '$1 संबंधनानि',
'whatlinkshere-filters'    => 'निस्यन्दनानि',

# Block/unblock
'blockip'                  => 'प्रयोक्तारं निरुध्नातु',
'ipboptions'               => '२ होराः:2 hours,१ वासरः:1 day,३ वासराः:3 days,१ सप्ताहः:1 week,२ सप्ताहौ:2 weeks,१ मासः:1 month,३ मासाः:3 months,६ मासाः:6 months,१ वर्षः:1 year,अनंतम्:infinite',
'ipblocklist'              => 'निरोधिताः आइ.पी. संकेताः, प्रयोक्तृ-नामानि च',
'blocklink'                => 'अवरुध्नातु',
'unblocklink'              => 'अनिरुध्नातु',
'change-blocklink'         => 'विरोध दशा परिवर्तयतु',
'contribslink'             => 'योगदानम्',
'blocklogpage'             => 'निरोधानां सूचिका',
'blocklogentry'            => '[[$1]] इत्येतद् निरोधितं, $2 $3 इति अवसान-समयेन सह',
'unblocklogentry'          => 'अनिरुद्धम् $1',
'block-log-flags-nocreate' => 'लेखा-सृजनम् अक्षमीकृतम्',

# Move page
'movearticle'     => 'पृष्ठं चालयतु :',
'newtitle'        => 'नूतनं शीर्षकं प्रति :',
'move-watch'      => 'इदं पृष्ठं निरीक्षताम्।',
'movepagebtn'     => 'पृष्ठं चालयतु।',
'pagemovedsub'    => 'चालनं सिद्धम्।',
'movepage-moved'  => '\'\'\'"$1" इत्येतद् "$2" इत्येतद् प्रति चालितमस्ति \'\'\'',
'articleexists'   => 'अनेन नाम्ना पृष्ठमेकं पूर्वेऽपि विद्यते, अथवा भवता चितं नाम तु अमान्यमस्ति। कृपया इतरं किमपि नाम चिनोतु।',
'talkexists'      => "'''पृष्ठं साफल्येन चालितमस्ति, परं चर्चापृष्ठं चालयितुं न शक्यम्, यतो नवेऽपि पृष्ठे चर्चापृष्ठं विद्यते। कृपया तं स्वयमेव चालयतु।'''",
'movedto'         => 'इदं प्रति चालितम्।',
'movetalk'        => 'सहगामिनं चर्चापृष्ठं चालयतु।',
'1movedto2'       => '[[$1]] इत्येतद् [[$2]] इत्येतद् प्रति चालितम्',
'1movedto2_redir' => '[[$1]] इति लेखस्य नाम परिवर्तितं कृत्वा [[$2]] इति कृतम् (अनुप्रेषितम्)',
'movelogpage'     => 'लॉग् इत्येतद् चालयतु',
'movereason'      => 'कारणम् :',
'revertmove'      => 'पुरातनीं आवृत्तिं प्रति गमयतु',

# Export
'export' => 'पृष्ठाणां निर्यातं करोतु',

# Namespace 8 related
'allmessages'               => 'व्यवस्था सन्देशाः',
'allmessagesname'           => 'नाम',
'allmessages-filter-all'    => 'अखिलम्',
'allmessages-language'      => 'भाषा:',
'allmessages-filter-submit' => 'गच्छतु',

# Thumbnails
'thumbnail-more' => 'विस्तारयतु',

# Special:Import
'import-comment' => 'टिप्पणी:',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'भवतः प्रयोक्तृ-पृष्ठम्',
'tooltip-pt-mytalk'               => 'भवतः संभाषण-पृष्ठम्',
'tooltip-pt-preferences'          => 'भवतः वरीयांसि',
'tooltip-pt-watchlist'            => 'भवद्भिः निरीक्ष्यमतां पृष्ठाणां सूचिका',
'tooltip-pt-mycontris'            => 'भवतः योगदानानां सूचिका',
'tooltip-pt-login'                => 'भवान् लेखायां प्रविशतु इति श्रेयः परन्तु नावश्यकम्',
'tooltip-pt-logout'               => 'निर्गच्छतु',
'tooltip-ca-talk'                 => 'घटक-पृष्ठ-विषये चर्चा',
'tooltip-ca-edit'                 => 'भवान् इदं पृष्ठं संपादयितुं शक्नोति। कृपया रक्षणात्पूर्वं प्राग्दृश्यं पश्यतु।',
'tooltip-ca-addsection'           => 'नूतनं विभागम् आरभतु',
'tooltip-ca-viewsource'           => 'इदं पृष्ठं सुरक्षितं विद्यते। भवान् अस्य स्रोतसम् दृष्टुं शक्नोति।',
'tooltip-ca-history'              => 'अस्य पृष्ठस्य पुरातनाः आवृत्तयः',
'tooltip-ca-protect'              => 'इदं पृष्ठं सुरक्षितीकरोतु',
'tooltip-ca-delete'               => 'इदं पृष्ठं अपाकरोतु',
'tooltip-ca-move'                 => 'इदं पृष्ठं चालयतु',
'tooltip-ca-watch'                => 'इदं पृष्ठं स्व-निरीक्षासूचिकायां योजयतु।',
'tooltip-ca-unwatch'              => 'इदं पृष्ठं स्व-निरीक्षासूचिकातः अपाकरोतु',
'tooltip-search'                  => '{{SITENAME}} अन्वेषणं करोति',
'tooltip-search-go'               => 'एतत्-शीर्षकीयम् पृष्ठं गच्छतु चेत् तद्वर्तते',
'tooltip-search-fulltext'         => 'एतत् पाठं पृष्ठेषु अन्विष्यतु',
'tooltip-p-logo'                  => 'मुख्यपृष्ठम्  अभ्यागम्',
'tooltip-n-mainpage'              => 'मुख्यपृष्ठं सन्दर्शयतु',
'tooltip-n-mainpage-description'  => 'मुख्यपृष्ठं सन्दर्शयतु',
'tooltip-n-portal'                => 'प्रकल्पविषये, भवता किं कर्तुं शक्यम्, कुत्र अन्वेषणं शक्यम्',
'tooltip-n-currentevents'         => 'सद्यःघटितानां घटनानां विषये पृष्ठभूमिक-सूचना',
'tooltip-n-recentchanges'         => 'सद्यःपरिवर्तितानां सूचिका',
'tooltip-n-randompage'            => 'यादृच्छिकमेकं पृष्ठं गच्छतु',
'tooltip-n-help'                  => 'निराकरण-स्थानम्',
'tooltip-t-whatlinkshere'         => 'सर्वेषामपि एतत्संबद्धानां पृष्ठानां सूची',
'tooltip-t-recentchangeslinked'   => 'अस्मात् पृष्ठात् संबंद्धीकृतेषु पृष्ठेषु नवीनतमानि परिवर्तनानि',
'tooltip-feed-rss'                => 'अस्मै पृष्ठाय आर-एस-एस-पूरणम्',
'tooltip-feed-atom'               => 'अस्मै पृष्ठाय ऍटम-पूरणम्',
'tooltip-t-contributions'         => 'अस्य प्रयोक्तारः योगदानानां सूचिकां दर्शयतु',
'tooltip-t-emailuser'             => 'एनं प्रयोक्तारं विद्युत्पत्रं(ई-मेल इत्येतद्) प्रेषयतु',
'tooltip-t-upload'                => 'संचिकाः उद्भारयतु',
'tooltip-t-specialpages'          => 'सर्वेषां विशिष्ट-पृष्ठानां सूचिका',
'tooltip-t-print'                 => 'अस्य पृष्ठस्य मुद्रणीया आवृत्तिः',
'tooltip-t-permalink'             => 'पृष्ठस्य इमाम् आवृत्तिं प्रति स्थायि संबंधनम्',
'tooltip-ca-nstab-main'           => 'घटक-पृष्ठं पश्यतु',
'tooltip-ca-nstab-user'           => 'प्रयोक्तॄ-पृष्ठं दर्शयतु',
'tooltip-ca-nstab-special'        => 'इदमेकं विशिष्टं पृष्ठम्, भवान् इदं पृष्ठं संपादयितुं न शक्नोति।',
'tooltip-ca-nstab-project'        => 'प्रकल्प-पृष्ठं दर्शयतु',
'tooltip-ca-nstab-image'          => 'संचिकायाः पृष्ठं पश्यतु',
'tooltip-ca-nstab-template'       => 'बिंबधरं पश्यतु',
'tooltip-ca-nstab-category'       => 'श्रेणी-पृष्ठं दर्शयतु',
'tooltip-minoredit'               => 'इदं परिवर्तनं लघु-परिवर्तन-रूपेण दर्शयतु',
'tooltip-save'                    => 'परिवर्तनानि रक्षतु',
'tooltip-preview'                 => 'भवता कृतानि परिवर्तनानि प्राग्दृश्यरूपेण पश्यतु, कृपया रक्षणात्पूर्वं इदं प्रयोजयतु।',
'tooltip-diff'                    => 'पाठे भवता कृतानि परिवर्तनानि पश्यतु।',
'tooltip-compareselectedversions' => 'पृष्ठस्य द्वयोः चितयोः आवृत्त्योः मध्ये अंतरं पश्यतु',
'tooltip-watch'                   => 'इदं पृष्ठं स्व-निरीक्षासूचिकायां योजयतु',
'tooltip-rollback'                => '"पूर्ण-प्रतिगमनं(रोलबैक् इत्येतद्)" अस्य पृष्ठस्य संपादनानि अंतिम-योगदातृकृतानि विपरीतीकरोति एकेन क्लिक्कारेण',
'tooltip-undo'                    => '"अकरोतु" इत्येतद् इदं संपादनं विपरीतीकरोति, तथा च संपादन-प्रारूपं प्राग्दृश्य-रूपेण उद्घाटयति।

अस्य सारांशे कारणमपि लेखितुं शक्यते।',
'tooltip-summary'                 => 'संक्षिप्त-सारांशं प्रविष्टतु',

# Skin names
'skinname-standard'    => 'पूर्व',
'skinname-nostalgia'   => 'पुराण',
'skinname-cologneblue' => 'नील',
'skinname-monobook'    => 'पुस्तक',
'skinname-myskin'      => 'मे चर्मन्',
'skinname-chick'       => 'Chick',

# Browsing diffs
'previousdiff' => '← पुरातनतरं सम्पादनम्',
'nextdiff'     => 'नवतरं संपादनम् →',

# Media information
'file-info-size'       => '$1 × $2 पिक्सेलानि, संचिकायाः आकारः: $3, MIME-प्रकारः: $4',
'file-nohires'         => '<small>उच्चतरं विभेदनं नोपलब्धम्</small>',
'svg-long-desc'        => 'SVG संचिका, साधारणतया $1 × $2 पिक्सेलानि, संचिकायाः आकारः : $3',
'show-big-image'       => 'पूर्णं विभेदनम्',
'show-big-image-thumb' => '<small>अस्य प्राग्दृश्यस्य आकारः : $1 × $2 पिक्सेलानि</small>',

# Special:NewFiles
'newimages' => 'नूतन-संचिकानां वीथिका',

# Bad image list
'bad_image_list' => 'प्रारूपम् एवम् अस्ति :

केवलानि सूचिकान्तर्गतानि वस्तूनि विचारितानि सन्ति (* इत्यस्मात् आरभमत्यः पंक्तयः)। पंक्त्यां प्रथमं संबंधनं त्रुटिपूर्णां संचिकां प्रति भवतु।

पंक्तौ परवर्तिनः संबंधनानि अपवादान् इव विचार्यन्ते, अर्थात् तादृशानि पृष्ठाणि यत्र संचिकैषा भवितुं शक्नोति।',

# Metadata
'metadata'          => 'अधिदत्तानि',
'metadata-help'     => 'अस्यां संचिकायां अतिरिक्ता सूचना अस्ति, कदाचित् आंकिक-छायाचित्रग्राहिना अथवा स्कैनर् इत्यनेन योजिता येन एषा स्रष्टा वा आंकिकीकृता वा स्यात्।

यदि एषा संचिका मूलावस्थातः परिवर्तिता अस्ति, तदा अत्र कानिचिद् विवरणानि परिवर्तितां संचिकां न पूर्णतया प्रदर्शयन्तीति शक्यम्।',
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
* focallength',

# External editor support
'edit-externally'      => 'बाह्यां प्रणालीं उपयोज्य इमां संचिकां संपादयतु।',
'edit-externally-help' => '(अधिकायै सूचनायै [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] इत्येतत् पश्यतु)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'सर्वाणि',
'namespacesall' => 'सर्वाणि',
'monthsall'     => 'सर्वाः',

# Auto-summaries
'autosumm-new' => '$1 नवीन पृष्ठं निर्मीत अस्ती',

# Watchlist editing tools
'watchlisttools-view' => 'आधारितानि परिवर्तनानि दर्शयतु',
'watchlisttools-edit' => 'निरीक्षासूचिकां दर्शयतु, संपादयतु च',
'watchlisttools-raw'  => 'प्राकृतां निरीक्षासुचिकां संपादयतु',

# Special:Version
'version' => 'आवृत्ति',

# Special:SpecialPages
'specialpages' => 'विशिष्ट-पृष्ठाणि',

);
