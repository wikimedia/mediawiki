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
'broken-file-category'           => 'भग्नेभ्यः संबन्धनेभ्यः युक्तानि पृष्ठाणि',

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
'printableversion'  => 'मुद्रणीय पाठान्तरम्',
'permalink'         => 'स्थिरबन्धनम्',
'print'             => 'मुद्रयतु',
'view'              => 'दर्शाव',
'edit'              => 'सम्पादयतु',
'create'            => 'सृजतु',
'editthispage'      => 'इदं पृष्ठं सम्पादयतु',
'create-this-page'  => 'इदं पृष्ठं सृज',
'delete'            => 'विनाशयतु',
'deletethispage'    => 'एतत् पृष्ठं अपाकरोतु',
'undelete_short'    => '{{PLURAL:$1|एकं सम्पादनं|$1 सम्पादनानि}} अनपाकरोतु',
'viewdeleted_short' => 'दृश्यतु {{PLURAL:$1|एको विलोपित सम्पादनम्|$1 विलोपित-सम्पादनानि}}',
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
'collapsible-collapse'    => 'संकोचयतु',
'collapsible-expand'      => 'विस्तारयतु',
'thisisdeleted'           => '$1 दर्शयेत् वा प्रत्यानयेत् वा?',
'viewdeleted'             => '$1 पश्यतु?',
'restorelink'             => '{{PLURAL:$1|एकम् अपाकृतं संपादनम्  |$1 अपाकृतानि संपादनानि}}',
'feedlinks'               => 'अनुबन्ध:',
'feed-invalid'            => 'अमान्यं सेवाग्रहण-पूरण (सब्स्क्रिप्शन-फीड् इति) प्रकारः।',
'site-rss-feed'           => '$1 आरएसएस पूरणम्',
'site-atom-feed'          => '$1 ऍटम पूरणम्',
'page-rss-feed'           => '"$1" आरएसएस-पूरणम्',
'page-atom-feed'          => '"$1" ऍटम अनुबन्ध',
'red-link-title'          => '$1 (पृष्ठं इदानीं यावत् न रचितम्)',
'sort-descending'         => 'अवरोहिक्रमेण सज्जयतु',
'sort-ascending'          => 'आरोहिक्रमेण सज्जयतु',

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
'nosuchactiontext'  => 'अनेन समरूप-संसाधन-अवस्थापकेन (URL इति) वर्णिता क्रिया अमान्याऽस्ति।
भवता समरूप-संसाधन-अवस्थापकं अपटंकितं स्यात्, अथवा असुष्ठु संबंधनम् अनुसृतम् स्यात्।
इदम् {{SITENAME}} इत्यनेन प्रयुक्ते क्रमादेशे त्रुटिर्वा स्यात्।',
'nosuchspecialpage' => 'एतादृश विशेष पृष्टम् नास्ति',
'nospecialpagetext' => '<strong>भवता एकम् अमान्यं विशिष्टपृष्ठं याचितम्। </strong>
मान्यानां विशिष्टपृष्ठाणां सूचिका [[Special:SpecialPages|{{int:specialpages}}]] इत्यत्र प्राप्तुं शक्यते।',

# General errors
'error'                => 'विभ्रम',
'databaseerror'        => 'दत्ताधार-विभ्रमः',
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
'readonly'             => 'डाटाबेस बन्धितमस्ति',
'enterlockreason'      => 'तन्त्रितीकरणस्य कारणं ददातु, अपि च आकलितं ददातु यत् तन्त्रणं कदा उद्घाट्यिष्यते।',
'readonlytext'         => 'समंकाधारं वर्तमानकाले तन्त्रितमस्ति नूतनान् प्रविष्टीन् विरुध्य तथा च अन्यानि परिवर्तनानि विरुध्य। इदं नियमिततया समंकाधार परिचर्याऽर्थं तथा स्यात्। तत्पश्चादिदं सामान्यतां संप्राप्स्यति।
तन्त्रितीकारकेन प्रबन्धकेन इदं कारणं प्रदत्तम्: $1',
'missing-article'      => 'दत्ताधारेण(डाटाबेस् इत्यनेन) "$1" $2 इतिनामकं पृष्ठं नैव प्राप्तम्, यत्तु प्राप्तीभवितव्यमासीत्।

कदाचित् एवं तु अवसिते सति अंतरे अथवा अपाकृतस्य पृष्ठस्य इतिहास-संबंधनात् भवति।


यदि न एवं विद्यते, तदा भवता क्रमादेश-कीटकं अन्विष्टम्।
कृपया केनचित् [[Special:ListUsers/sysop|प्रचालकेन]] सह अस्य पृष्ठस्य URL इत्येतद्- ज्ञापनपूर्वकं संभाषताम्।',
'missingarticle-rev'   => '(आवृत्तिः# :$1)',
'missingarticle-diff'  => '(व्यतिरेक: $1, $2)',
'readonly_lag'         => 'मुख्य-समंकाधार-परिवेशकं उपमुख्य-समंकाधार-परिवेशकस्य संप्रापणात् पूर्वे एव स्वतः तन्त्रितम् अस्ति।',
'internalerror'        => 'आन्तरिका त्रुटिः',
'internalerror_info'   => 'आन्तरिका त्रुटिः: $1',
'fileappenderrorread'  => 'संलग्नीकरणकाले $1 इति न पठितुं शक्तम्।',
'fileappenderror'      => '$1 इत्यस्य पश्चात् $2 इति योजयितुं नाशक्नोत्।',
'filecopyerror'        => '$1 इत्येतस्याः संचिकायाः  $2 इति प्रतिलिपिं कर्तुं नाशक्नोत्।',
'filerenameerror'      => '$1 इति संचिकायाः $2 इति पुनर्नामकरणं कर्तुं नाशक्नोत्।',
'filedeleteerror'      => '$1 इति सञ्चिकाम् अपाकर्तुं नाशक्नोत्।',
'directorycreateerror' => '$1 इति निर्देशिकां स्रष्टुं न अपारयत्',
'filenotfound'         => '"$1" इति संचिका न लब्धा।',
'fileexistserror'      => '$1 इति संचिकायां लिखितुम् अशक्तोऽस्ति। संचिका वर्तते  एव।',
'unexpected'           => 'अप्रतीक्षितमूल्यम् : "$1"="$2"।',
'formerror'            => 'त्रुटिः : प्रारूपं समर्पयितुं न अपारयत्',
'badarticleerror'      => 'अस्मिन् पृष्ठे एषा क्रिया कर्तुं न शक्या।',
'cannotdelete'         => '$1 इति पृष्ठं संचिका वा अपाकर्तुं नाशक्नोत्।
इदं खलु केनचिदन्येन पूर्वे एव अपाकृतं स्यात्।',
'badtitle'             => 'दुरितशीर्षक',
'badtitletext'         => 'प्रार्थितं पृष्ठ-शीर्षकं अमान्यं वा, रिक्तं वा, अथवा अशुद्धतया संबंद्धं आंतर्भाषिकं, आंतर्विकीयं वा शीर्षकमस्ति। अस्मिन् एकं एकाधिकानि वा एतादृशाणि अक्षराणि विद्यन्ते येषां प्रयोगं शीर्षकेषु अशक्यम्।',
'perfcached'           => 'अनुपदोक्तं समंकं कैश् इत्येतस्माद् अस्ति, अतः अद्यतनं न स्यात्।',
'perfcachedts'         => 'अनुपदोक्तं समंकं कैश् इत्येतस्मिन् विद्यते, तथा च $1 इत्येतत्समये अन्तिमं वारं परिवर्तितम्।',
'viewsource'           => 'स्रोतः दर्शयतु',
'viewsourcefor'        => '$1 कृते',
'viewsourcetext'       => 'भवान् एतस्य पृष्ठस्य स्रोतसं द्रष्टुं शक्नोति तस्य च प्रतिलिपिं कर्तुं शक्नोति।',
'protectedinterface'   => 'इदं पृष्ठं तंत्रांशाय अन्तराफलकं ददाति, तथा च दुरुपयोगात् वारयितुं सुरक्षितीकृतम्।',
'sqlhidden'            => '(निगूढा एसक्यूएल्- पृच्छा)',
'cascadeprotected'     => 'इदं पृष्ठं संपादनात् सुरक्षितमस्ति, यतः इदं अधोलिखितानां {{PLURAL:$1| पृष्ठस्य|पृष्ठाणां}} सुरक्षा-सोपाने समाहितं वर्तते।
$2',
'namespaceprotected'   => 'भवान् "$1" इति नामाकाशे विद्यमानान् पृष्ठान् परिवर्तितुं अनुमतिं न धारयति।',
'ns-specialprotected'  => 'विशिष्टानि पृष्ठाणि परिवर्तितुं न शक्यन्ते।',
'titleprotected'       => 'सदस्य [[User:$1|$1]] इत्यनेन एतत्-शीर्षकीयं पृष्ठं सृजनात् वारितमस्ति।
एतदर्थं प्रदत्तं कारणम् "$2"।',

# Virus scanner
'virus-badscanner'     => "असुष्ठु अभिविन्यासः : अज्ञातं विषाणु-निरीक्षित्रम्: ''$1''",
'virus-scanfailed'     => 'परीक्षणं विफलीभूतम् (कूटम् $1)',
'virus-unknownscanner' => 'अज्ञातं विषाणुप्रतिकारकम्:',

# Login and logout pages
'logouttext'                 => '"भवान् अधुना सत्राद् बहिः तिष्ठति।"

भवान् {{SITENAME}} इत्येतत् अनामतया प्रयोक्तुं शक्नोति, अथवा भवान् तेनैव प्रयोक्तृनाम्ना, भिन्नप्रयोक्तृनाम्ना वा  [[Special:UserLogin|पुनः प्रविष्टुं शक्नोति]]।
मनसि धारयतु यत् कानिचित् पृष्ठाणि अधुनाऽपि प्रविष्टरूपेणैव द्रष्टुं शक्नुवन्ति, यावद् भवान् स्वकीयस्य विचरकस्य कैश् इति स्मृतिसंचयं न अपाकरोति।',
'welcomecreation'            => '==स्वागतम्‌, $1!==
भवतः/भवत्याः लेखा सृष्टितमस्ति।
भवतः/भवत्याः [[Special:Preferences|{{SITENAME}} इष्टतमानि]]  स्वच्छानुसारं कर्तुं स्मरणा करोतु।',
'yourname'                   => 'प्रयोक्तृ-नाम :',
'yourpassword'               => 'रहस्यवाक् :',
'yourpasswordagain'          => 'रहस्यवाक् पुनः लिखतु।',
'remembermypassword'         => 'अस्मिन् संगणके मम प्रवेशसंबंधि-सूचनाः स्मरतु (अधिकतम् $1 {{PLURAL:$1|दिन्|दिन्}})',
'securelogin-stick-https'    => 'प्रवेशोपरान्तं एचटीटीपीएस(HTTPS) इत्यनेन सह संबद्धः तिष्ठतु।',
'yourdomainname'             => 'भवतः प्रक्षेत्रम्:',
'externaldberror'            => 'तत्र प्रमाणीकरण समंकाधारे त्रुटिर्जाता, अथवा भवान् स्वकीयां बाह्य-लेखां अद्यतनीकर्तुं अनुमतिं न धारयति।',
'login'                      => 'प्रविशतु',
'nav-login-createaccount'    => 'प्रविशतु / लेखां सृजतु',
'loginprompt'                => '{{SITENAME}} इत्यत्र प्रविष्टुं कुकी इत्येते (cookies)  समर्थीकरणीयाः।',
'userlogin'                  => 'प्रविशतु / लेखां सृजतु',
'userloginnocreate'          => 'प्रविशतु',
'logout'                     => 'बहिर्गच्छतु',
'userlogout'                 => 'बहिर्गच्छति',
'notloggedin'                => 'भवान् प्रविशितवान् नास्ति',
'nologin'                    => 'उपयोजकसंज्ञा न अस्ति? $1।',
'nologinlink'                => 'लेखां रचयतु',
'createaccount'              => 'लेखां रचयतु',
'gotaccount'                 => 'उपयोजकसंज्ञा तावत् अस्ति? $1।',
'gotaccountlink'             => 'प्रविशतु',
'userlogin-resetlink'        => 'तव नामाभिलेखननिर्देश अन्तर्गतगामिन्?',
'createaccountmail'          => 'विद्युत्पत्रेण',
'createaccountreason'        => 'कारणम्',
'badretype'                  => 'भवता प्रदत्ते कूटशब्दे न खलु समाने स्तः। कृपया पुनः लिखतु।',
'userexists'                 => 'भवतः प्रदत्तः प्रयोक्तृनाम पूर्वे एव प्रयुज्यमानम् अस्ति। कृपया अन्यदेकं प्रयोक्तृनाम चिनोतु।',
'loginerror'                 => 'प्रवेशसंबंधि-त्रुटिः',
'createaccounterror'         => '$1 इति लेखां स्रष्टुं न अपारयत्',
'nocookiesnew'               => 'भवतः लेखा सृष्टाऽस्ति, परन्तु भवान् प्रविष्टो नासि।
{{SITENAME}} इत्यनेन प्रवेशं कर्तुं कुकि इत्येते प्रयुज्यन्ते।
भवतः पक्षे कुकि इत्येते असमर्थीकृतानि सन्ति।
कृपया तानि समर्थानि करोतु, तथा स्वकीयेन सदस्यनाम्ना कूटशब्देन च प्रविशतु।',
'nocookieslogin'             => '{{SITENAME}} इत्यत्र प्रयोक्तृणां प्रवेशार्थं कुकि इत्येतेषां प्रयोगः क्रियते।
भवता कुकि इत्येते असमर्थीकृतानि सन्ति।
कृपया तानि समर्थीकरोतु पुनश्च प्रयततु।',
'noname'                     => 'भवता एकं मान्यं प्रयोक्तृ-नाम न प्रदत्तम्।',
'loginsuccesstitle'          => 'सुस्वागतम्‌। प्रवेशः सिद्धः।',
'loginsuccess'               => 'भवान् अधुना {{SITENAME}} इत्यत्र "$1" रूपेण प्रविष्टोऽस्ति।',
'nosuchuser'                 => 'तत्र $1 इति नाम्ना न कोऽपि प्रयोक्ता विद्यते।
प्रयोक्तृनाम्नि आंग्ललिपेः लघुभिः दीर्घैश्च अक्षरैः भिन्नता गण्यते।
स्वकीयां वर्तनीं पुनरीक्षतां, अथवा [[Special:UserLogin/signup|नूतनां लेखां सृजतु]]।',
'nosuchusershort'            => '"$1" इति नाम्ना न कोऽपि प्रयोक्ता विद्यते।
स्वकीयां वर्तनीं पुनरीक्षताम्।',
'nouserspecified'            => 'भवता एकं प्रयोक्तृनाम अवश्यमेव दातव्यम्।',
'login-userblocked'          => 'एषः प्रयोक्ता प्रतिबन्धितः अस्ति। सत्रारम्भाय अनुमतिः नास्ति।',
'wrongpassword'              => 'भवता प्रदत्तं कूटशब्दं त्रुटिपूर्णम् अस्ति। 
कृपया पुनः चेष्टताम्।',
'wrongpasswordempty'         => 'प्रविष्टं कूटशब्दं रिक्तं विद्यते।
कृपया पुनः चेष्टताम्।',
'passwordtooshort'           => 'कूटशब्दं न्यूनान्नयूनं {{PLURAL: $1| 1 अक्षरात्मकम्|$1 अक्षरात्मकम्}} अवश्यमेव भवितव्यम्।',
'password-name-match'        => 'भवतः कूटशब्दं अवश्यमेव भवतः प्रयोक्तृनामतः भिन्नं  भवितव्यम्।',
'password-login-forbidden'   => 'अस्य प्रयोक्तृनाम्नः कूटशब्दस्य च प्रयोगः वर्जितोऽस्ति।',
'mailmypassword'             => 'नूतनं रहस्यवाक् विद्युत्पत्रेण प्रेषयतु',
'passwordremindertitle'      => '{{SITENAME}} इत्येतदर्थे नूतन् अस्थायि कूटशब्दम्।',
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
'accountcreated'             => 'खाता निर्मित',
'accountcreatedtext'         => '$1 इत्येतस्य कृते लेखा निर्मिताऽस्ति।',
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

# E-mail sending
'php-mail-error-unknown' => 'पीएच्पी इत्येतस्य mail() फलने अज्ञाता काऽपि त्रुटिर्जाता।',

# Change password dialog
'resetpass'                 => 'कूटशब्दं परिवर्तयतु',
'resetpass_text'            => '<!-- पाठं अत्र लिखतु -->',
'resetpass_header'          => 'लेखायाः कूटशब्दं परिवर्तताम्।',
'oldpassword'               => 'पुरातन रहस्यवाक् :',
'newpassword'               => 'नूतन रहस्यवाक् :',
'retypenew'                 => 'नूतन रहस्यवाक् पुनर्लिखतु :',
'resetpass_submit'          => 'कूटशब्दं निर्धारयतु तथा च प्रविशतु',
'resetpass_success'         => 'भवतः कूटशब्दः सफलतया परिवर्तितम्!
अधुना भवन्तं प्रवेशयामः...',
'resetpass_forbidden'       => 'कूटशब्दाः परिवर्तितुं न शक्यन्ते',
'resetpass-no-info'         => 'भवता एतत्पृष्ठं प्रत्यक्षतया सम्प्राप्तुं प्रवेशः अवश्यमेव कर्त्तव्यः।',
'resetpass-submit-loggedin' => 'रहस्यवाक् परिवर्तयतु',
'resetpass-submit-cancel'   => 'निरसनम् करोतु',
'resetpass-wrong-oldpass'   => 'अल्पकालीनः अथवा सद्यःकालीनः कूटशब्दः अमान्यः अस्ति।
भवता पूर्वे एव सफलतया स्वकीयः कूटशब्दः परिवर्तितः स्यात्, अथवा एकः नूतनः अल्पकालीनः कूटशब्दः प्रार्थितः स्यात्।',
'resetpass-temp-password'   => 'अस्थिर रहस्यवाक् :',

# Special:PasswordReset
'passwordreset'              => 'कूटशब्द पुनःस्थापनम्',
'passwordreset-disabled'     => 'अस्मिन् विक्यां कूटशब्द पुनःस्थापनं असमर्थीकृतमस्ति।',
'passwordreset-pretext'      => '{{PLURAL:$1| |समंकेषु एकम् अधस्यात् प्रविष्टीकरोतु।}}',
'passwordreset-username'     => 'योजकनामन्:',
'passwordreset-email'        => 'परमाणुपत्रसङ्गेत:',
'passwordreset-emailtitle'   => '{{SITENAME}} इत्यत्र लेखा-विवरणम्',
'passwordreset-emailelement' => 'प्रयोक्तृनाम: $1
अल्पकालिकः कूटशब्दः : $2',
'passwordreset-emailsent'    => 'एकः स्मारकः विद्युत्सन्देशः प्रेषितोऽस्ति।',

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
'showlivepreview'                  => 'प्रत्यक्षं प्राग्दृश्यम्',
'showdiff'                         => 'परिवर्तनानि दर्शयतु',
'anoneditwarning'                  => "'''सावधानो भवतु:''' भवता प्रवेशं न कृतम्।
अस्य पृष्ठस्य इतिहासे भवतः आइ-पी-संकेतः अंकितः भविष्यति।",
'anonpreviewwarning'               => "''भवान् प्रवेशितः न अस्ति। रक्षणेन पृष्ठस्य सम्पादनेतिहासे भवतः आइपीसंकेतः अंकितः भविष्यति।''",
'missingsummary'                   => "'''अनुस्मारकम्:''' भवता सम्पादनस्य सारः न प्रदत्तः।
चेद्भवान् \"{{int:savearticle}}\" इत्येतद् पुनः क्लिक्करोति, भवतः सम्पादनानि साराद् ऋते रक्षितीभविष्यन्ति।",
'missingcommenttext'               => 'कृपया अधस्तात् एका टिप्पणी दातव्या।',
'missingcommentheader'             => "'''अनुस्मारकम्:''' भवता अस्याः टिप्पण्याः विषयः शीर्षकं वा न प्रदत्तः।
चेद्भवान् \"{{int:savearticle}}\" इत्येतद् पुनः क्लिक्करोति, भवतः सम्पादनानि विषयात् शीर्षकाद् वा ऋते रक्षितीभविष्यन्ति।",
'summary-preview'                  => 'सारांशस्य प्राग्दृश्यम् :',
'subject-preview'                  => 'विषयस्य/शीर्षकस्य प्राग्दृश्यम्:',
'blockedtitle'                     => 'प्रयोक्ता अवरुद्धः वर्तते',
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
'loginreqtitle'                    => 'प्रवेशनम् आविश्यकम्',
'loginreqlink'                     => 'प्रविशतु',
'loginreqpagetext'                 => 'अन्यानि पृष्ठाणि द्रष्टुं भवता $1 इति अवश्यमेव कर्त्तव्यम्।',
'accmailtitle'                     => 'पास्वेड् पप्रेषितम्',
'accmailtext'                      => "[[User talk:$1|$1]] इत्येतदर्थं एकः यादृच्छिकतया उत्पादितः कूटशब्दः $2 इत्येतत् प्रति प्रेषितोऽस्ति।
सत्रारम्भपश्चात् नूतनायाः अस्याः लेखायाः कूटशब्दः  '''[[Special:ChangePassword|कूटशब्दं परिवर्तताम्]]'' इति पृष्ठे परिवर्तितुं शक्यते।",
'newarticle'                       => '(नवीनम्)',
'newarticletext'                   => "भवता एतादृशमेकं पृष्टं प्रति संबंधनम् अनुसृतम्, यत्पृष्ठं न इदानींयावत् विद्यते।

पृष्ठं स्रष्टुम् अधःप्रदत्तायां पेटिकायां टंकणं करोतु (सहाय्यार्थं [[{{MediaWiki:Helppage}}|अत्र]] क्लिक्करोतु।

चेद्भवान् अत्र भ्रान्तिना आनीतोऽस्ति तदा स्वकीये ब्राउसर् इत्यस्मिन् '''बैक्''' इत्यस्मिन् क्लिक्करोतु।)",
'noarticletext'                    => 'अस्मिन् पृष्ठे संप्रति न कोऽपि पाठः विद्यते। भवान् विकिपीडियावर्तिषु अन्येषु पृष्ठेषु इदं [[Special:Search/{{PAGENAME}}|शीर्षकम् अन्विष्टुं शक्नोति]] अथवा इदं पृष्ठं या फिर यह लेख 

<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}  related logs अन्विष्टुं शक्नोति],
अथवा [{{fullurl:{{FULLPAGENAME}}|action=edit}} इदं पृष्ठं स्रष्टुं शक्नोति]</span>.',
'noarticletext-nopermission'       => 'सम्प्रति अस्मिन् पृष्ठे न कोऽपि पाठः विद्यते।
भवान् अस्य पृष्ठस्य शीर्षकं अन्येषु पृष्ठेषु [[Special:Search/{{PAGENAME}}|अन्विष्टुं शक्नोति]],
अथवा <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} सम्बन्धितान् अभिलेखान् अन्विष्टुं शक्नोति]</span>',
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
भवता कृतानि परिवर्तनानि इदानींयावत् न रक्षितानि सन्ति!",
'previewconflict'                  => 'अस्मिन् प्राग्दृश्ये दर्शितमस्ति यत् उपरिवर्ति पाठ क्षेत्रस्य पाठः रक्षणपश्चात् कीदृशः दृष्टिगोचरः भविष्यति।',
'session_fail_preview'             => "'''क्षम्यताम्! अस्माभिः भवतः सम्पादनस्य संसाधनं न कर्तुं शक्तम् यस्माद्धि सत्रस्य सूचनाः लुप्ताः।'''
कृपया पुनः चेष्टताम्।
चेदेतत् अधुनाऽपि न कार्यशीलं स्यात्, [[Special:UserLogout|सत्राद्बहिः गत्वा]] पुनः प्रवेशं करोतु।",
'editing'                          => '$1 सम्पाद्यते',
'editingsection'                   => '$1 संपादनम् (विभागः)',
'editconflict'                     => 'सम्पादनयोः/सम्पादनानाम् अन्तर्विरोधः : $1',
'yourtext'                         => 'भवतः पाठः',
'storedversion'                    => 'रक्षिता आवृत्तिः',
'nonunicodebrowser'                => "'''पूर्वसूचना: भवतः विचरकं यूनीकोड्-अनुकूलम् नास्ति।'''
भवान् सुरक्षिततया सम्पादनं करोतु इत्येतदर्थं एका युक्तिः कृताऽस्ति: आस्की-इतराणि अक्षराणि सम्पादनपिटके षौडशिक(hexadecimal) कूटेषु द्रक्ष्यन्ते।",
'yourdiff'                         => 'अन्तरानि',
'copyrightwarning'                 => "कृपया संस्मर्तव्यं यत् {{SITENAME}} इत्येतद् प्रति कृतानि सर्वाणि योगदानानि $2 इत्यस्य प्रतिबंधांतर्गतानि सन्ति (अधिकाय ज्ञानाय $1 इत्येतद् पश्यतु)।

यदि भवान् स्वकीयानि लिखितानि परिवर्तमन्तश्च, पुनः वितर्यमन्तश्च न द्रष्टुमिच्छति तदा मा कृपया माऽत्र योगदानं करोतु। <br />

भवान् एतदपि प्रमाणीकरोति यत् एतद् भवता स्वतः लिखितमस्ति अथवा कस्माच्चत् जनार्पितात् वा मुक्तात् वा स्रोतसः प्रतिलिपीकृतमस्ति।

'''प्रतिलिप्यधिकारयुतान् लेखान्, अनुज्ञां विना, माऽत्र प्रददातु!'''",
'longpageerror'                    => "'''त्रुटिः: भवता प्रदत्तः पाठः $1 किलोबैटमितः दीर्घः, अतः एषः अधिकतमानुज्ञातात् $2 मितात् दीर्घतरः अस्ति। एषः रक्षितुं न शक्यते।'''",
'templatesused'                    => 'अस्मिन् पृष्ठे प्रयुक्ताः {{PLURAL:$1|बिंबधराः|बिंबधराः}}:',
'templatesusedpreview'             => 'अस्मिन् प्राग्दृश्ये प्रयुक्ताः {{PLURAL:$1|बिंबधराः |बिंबधराः}}:',
'template-protected'               => '(संरक्षितम्)',
'template-semiprotected'           => '(अर्धसंरक्षितम्)',
'hiddencategories'                 => 'इदं पृष्ठं {{PLURAL:$1|1 निगूढस्य श्रेण्याः |$1 निगूढानां श्रेणीनां}} सदस्यत्वेन विद्यते :',
'permissionserrors'                => 'अनुज्ञा-विभ्रमाः',
'permissionserrorstext'            => 'भवान् तत् कर्तुं अनुज्ञां न धारयति {{PLURAL:$1|अधोऽङ्कितात् कारणात् |अधोऽङ्कितेभ्यः कारणेभ्यः:}}',
'permissionserrorstext-withaction' => 'भवान् $2 इत्येतदर्थम् अनुज्ञां न धारयति, अस्य {{PLURAL:$1|कारणम्|कारणानि}}:',
'recreate-moveddeleted-warn'       => "'''पूर्वसूचना: भवान् एकं एतादृशं पृष्ठं रचयति यत्तु पूर्वे अपाकृतमासीत्।'''

भवान् पुनः विचारयतु यदेतस्य पृष्ठस्य सम्पादनं युक्तं न वा।
एतस्य पृष्ठस्य अपाकरणस्य तथा च चालनस्य अभिलेखाः भवतः सुविधार्थम् अत्र दीयन्ते:",
'moveddeleted-notice'              => 'इदं पृष्ठम् अपाकृतम् अस्ति।
एतत्पृष्ठार्थं अपाकरणस्य तथा च स्थानान्तरणस्य अभिलेखाः सन्दर्भार्थं अधस्तात् प्रदत्तानि।',
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
'expensive-parserfunction-warning'        => "'''पूर्वसूचना :''' अस्मिन् पृष्ठे प्रभूतानि जटिलानि पार्सर्-फ़ंक्शन्-आह्वानानि सन्ति।
अत्र $2 संख्यातः  {{PLURAL:$2|न्यूनं आह्वानं|न्यूनानि आह्वानानि}} भवितव्यानि, सद्यः तत्र {{PLURAL:$1 $1 आह्वानं विद्यते|$1 आह्वानानि विद्यन्ते}}।",
'expensive-parserfunction-category'       => 'प्रभूतेभ्यः जटिलेभ्यः पार्सर्-फंक्शन्-आह्वानेभ्यः युक्तानि पृष्ठाणि।',
'post-expand-template-inclusion-warning'  => "'''पूर्वसूचना:''' बिम्बधराणां इन्क्लूड् इत्येतस्य आकारः अतिविशालः वर्तते।
केचित् बिम्बधराः न समाहितीभविष्यन्ति।",
'post-expand-template-inclusion-category' => 'पृष्ठाणि यत्र अतोऽधिकाः बिम्बधराः समाहितीकर्तुं न शक्यन्ते।',
'post-expand-template-argument-warning'   => "'''पूर्वसूचना:''' अस्मिन् पृष्ठे न्यूनान्नयूनं एकः एतादृशः बिम्बधरः अस्ति यस्तु संवर्धने बृहदाकारः भवति।
एतादृशः बिम्बधरः लुप्तीकृतः अस्ति।",
'post-expand-template-argument-category'  => 'एतादृशानि पृष्ठाणि यत्र तु बिम्बधराः लुप्तीकृताः सन्ति।',
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
'previousrevision'       => '← पुरातनाः आवृत्तयः',
'nextrevision'           => 'नूतनतरा आवृत्तिः →',
'currentrevisionlink'    => 'सद्यःकालीना आवृत्तिः',
'cur'                    => 'नवतरम्',
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
'history-feed-item-nocomment' => '$2 मद्ये $1',
'history-feed-empty'          => 'याचितं पृष्ठं न विद्यते।
इदं विकितः अपाकृतम् अथवा पुनर्नामितं स्यात्।
सम्बन्धितानि नूतनानि पृष्ठाणि सम्प्राप्तुं [[Special:Search|विक्याम् अन्वेषणं]] करोतु।',

# Revision deletion
'rev-deleted-comment'        => '(सम्पादनस्य सारः अपाकृतमस्ति)',
'rev-deleted-user'           => '(प्रयोक्तृनाम अपाकृतमस्ति)',
'rev-deleted-event'          => '(अभिलेखन-क्रिया अपाकृताऽस्ति)',
'rev-deleted-user-contribs'  => '[प्रयोक्तृनाम अथवा आइपीसंकेतः अपाकृतः - सम्पादनं योगदानेभ्यः निगूढमस्ति]',
'rev-delundel'               => 'दर्शयतु/गोपयतु',
'rev-showdeleted'            => 'दर्शयतु',
'revdelete-nooldid-title'    => 'लक्ष्यरूपा आवृत्तिः अमान्याऽस्ति।',
'revdelete-nologtype-title'  => 'अभिलेखस्य प्रकारः न प्रदत्तः',
'revdelete-nologtype-text'   => 'अस्यै क्रियायै भवता न कोऽपि अभिलेखप्रकारः निर्दिष्टः।',
'revdelete-nologid-title'    => 'अमान्या अभिलेख-प्रविष्टिः',
'revdelete-show-file-submit' => 'हाँ',
'revdelete-hide-user'        => 'सम्पादकस्य प्रयोकतृनाम/आइपिसंकेतं गोपयतु।',
'revdelete-hide-restricted'  => 'प्रबन्धकेभ्यः अन्येभ्यश्च समंकं गोपयतु।',
'revdelete-radio-same'       => 'मा परिवर्तयतु।',
'revdelete-radio-set'        => 'हाँ',
'revdelete-radio-unset'      => 'न हि',
'revdelete-suppress'         => 'प्रबन्धकेभ्यः अन्येभ्यश्च समंकं गोपयतु।',
'revdelete-unsuppress'       => 'प्रत्यानीताऽऽवृत्तिभ्यः  वर्जनाः अपाकरोतु।',
'revdelete-log'              => 'कारणम् :',
'revdelete-submit'           => '{{PLURAL:$1|चितायां आवृत्त्यां|चितासु आवृत्तिषु}} अनुप्रयोजयतु।',
'revdelete-logentry'         => '"[[$1]]" इत्यस्य आवृत्ति-दृश्यता परिवर्तिताऽस्ति।',
'revdel-restore'             => 'दृश्यतां परिवर्तयतु',
'revdel-restore-deleted'     => 'विलोपितानि संशोधनानि',
'revdel-restore-visible'     => 'दृष्टिगोचर संशोधनानि',
'pagehist'                   => 'पुटस्य चरित्रम्',
'revdelete-content'          => 'विषय',
'revdelete-uname'            => 'उपयोक्तृ-नाम',
'revdelete-hid'              => 'आवृत $1',
'revdelete-otherreason'      => 'अन्य/अधिक कारणम् :',
'revdelete-reasonotherlist'  => 'अन्य कारणानि',
'revdelete-edit-reasonlist'  => 'सम्पादनस्य अपाकरणाय कारणानि',

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
'searchmenu-exists'                => 'एतद विकि अधि "[[:$1]]" 	संस्मृत पृष्ठ अस्ति।',
'searchmenu-new'                   => "'''अस्मिन् विक्यां \"[[:\$1]]\" इति पृष्ठं सृजतु!'''",
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
'searchrelated'                    => 'रिलेटेड',
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
'youremail'                 => 'विद्युत्पत्र',
'uid'                       => 'प्रयोक्तृ-क्रमांकः :',
'prefs-memberingroups'      => '{{PLURAL:$1|समूहस्य|समूहानां}}  सदस्यः:',
'prefs-registration'        => 'पंजीकरण कालः:',
'yourrealname'              => 'यथार्थनामन्:',
'yourlanguage'              => 'भाषा:',
'yournick'                  => ' नूतनाः हस्ताक्षराः:',
'prefs-help-signature'      => 'संभाषणपृष्ठगताः संवादाः "<nowiki>~~~~</nowiki>" इति लिखित्वा हस्ताक्षरोपेताः कर्त्तव्याः। एतानि चिह्नानि पृष्ठरक्षणपश्चात् भवतः हस्ताक्षरान् समयमुद्रां च प्रदर्शयिष्यन्ति।',
'badsig'                    => 'अमान्याः (त्रुटिपूर्णाः) हि एते अपक्वाः हस्ताक्षराः।
एचटीएमएल्-टैग इत्येतानि पुनरीक्षितव्यानि भवता।',
'badsiglength'              => 'भवतः हस्ताक्षराः तु अतीव दीर्घाः।
एते $1 {{PLURAL:$1|अक्षरात्|अक्षरेभ्यः}} दीर्घाः न भवितव्याः।',
'yourgender'                => 'लिंगम् (Gender):',
'gender-unknown'            => 'अनिर्दिष्टम्',
'email'                     => 'विद्युत्पत्रव्यवस्था',

# Groups
'group-sysop' => 'प्रबंधकाः',

'grouppage-sysop' => '{{ns:project}}:प्रचालकाः',

# User rights log
'rightslog' => 'प्रयोक्तृ-अधिकार-सूचिका',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'इदं पृष्ठं संपादयतु',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|परिवर्तनम्|परिवर्तनानि}}',
'recentchanges'                   => 'नवतमानि परिवर्तनानि',
'recentchanges-legend'            => 'नवतमानां परिवर्तनानां विकल्पाः',
'recentchanges-feed-description'  => 'अस्मिन् पूरणे विकि इत्यस्मिन् भूतानि नवतमानि परिवर्तनानि पश्यतु।',
'recentchanges-label-newpage'     => 'एतद सम्पादन नवपृष्ठ अरचतः',
'recentchanges-label-minor'       => 'इदं लघु परिवर्तनम्',
'recentchanges-label-bot'         => 'एतद एकः स्वचालितसम्पादन आसीत',
'recentchanges-label-unpatrolled' => 'एतद सम्पादन तत्थापि आरक्षक न अस्ति।',
'rcnote'                          => "अधस्तात् {{PLURAL:$1|'''1''' परिवर्तनमस्ति|अंतिमानि '''$1''' परिवर्तनानि सन्ति}},{{PLURAL:$2|गते दिवसे|'''$2''' गतेषु दिवसेषु}}, $5, $4 इति समये।",
'rclistfrom'                      => '$1 इत्यस्मात् आरभमन्तः नूतनानि परिवर्तनानि दर्शयतु',
'rcshowhideminor'                 => '$1 लघूनि संपादनानि',
'rcshowhidebots'                  => '$1 बोट् इत्येतानि',
'rcshowhideliu'                   => '$1 प्रवेशिताः प्रयोक्तारः',
'rcshowhideanons'                 => 'अनामकाः योजकाः $1',
'rcshowhidepatr'                  => '$1 ईक्षितसम्पादन',
'rcshowhidemine'                  => '$1 मम सम्पादनानि',
'rclinks'                         => 'अंतिमानि $1 परिवर्तनानि अंतिमेषु $2 दिनेषु, दर्शयतु<br />$3',
'diff'                            => 'अन्तरम्',
'hist'                            => 'इतिहासः',
'hide'                            => 'गोपयतु',
'show'                            => 'दर्शयतु',
'minoreditletter'                 => 'लघु',
'newpageletter'                   => 'नवी॰',
'boteditletter'                   => 'यन्त्रं',
'newsectionsummary'               => '/* $1 */ नवीन विभागः',
'rc-enhanced-expand'              => 'विवरणानि दर्शयतु (जावालिपि आवश्यकम्)',
'rc-enhanced-hide'                => 'विवरणानि विलोपयतु',

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

'license'        => 'लाइसेन्सिंग:',
'license-header' => 'लाइसेन्सिंग',

# Special:ListFiles
'imgfile' => 'संचिका',

# File description page
'file-anchor-link'          => 'संचिका',
'filehist'                  => 'संचिकायाः इतिहासः',
'filehist-help'             => 'संचिका तत्समये कीदृशी आसीदिति द्रष्टुं दिनांकः/समयः इत्यस्मिन् नोदयतु।',
'filehist-deleteone'        => 'विलोप',
'filehist-revert'           => 'पुरातनीं आवृत्तिं प्रति गमयतु',
'filehist-current'          => 'नवीनतमम्',
'filehist-datetime'         => 'दिनाङ्कः/समयः',
'filehist-thumb'            => 'अंगुष्ठनखाकारम्',
'filehist-thumbtext'        => '$1 समये विद्यमत्याः आवृत्तेः अंगुष्ठनखाकारम्',
'filehist-user'             => 'प्रयोक्ता',
'filehist-dimensions'       => 'आयामाः',
'filehist-comment'          => 'टिप्पणी',
'imagelinks'                => 'संचिका-संबंधनानि',
'linkstoimage'              => '{{PLURAL:$1|अधोलिखितं पृष्ठं| अधोलिखितानि $1 पृष्ठाणि}} इदं संचिकां प्रति संबंधनं {{PLURAL:$1|करोति| कुर्वन्ति}}।',
'nolinkstoimage'            => 'एतद चित्रात् न पृष्ठा सम्बद्धं करोन्ति।',
'sharedupload'              => 'इयं संचिका $1 इत्यस्मादस्ति, एषा खलु अन्येष्वपि प्रकल्पेषु प्रयोक्तुं शक्यते।',
'uploadnewversion-linktext' => 'अस्य पृष्ठस्य नूतनाम् आवृत्तिं उद्भारयतु',

# Random page
'randompage' => 'अविशिष्ट पृष्ठ',

# Statistics
'statistics'              => 'स्थितिगणितम्',
'statistics-users-active' => 'सक्रिय सदस्य',

'disambiguationspage' => 'Template:ससंशयस्यनिरास',

'doubleredirects' => 'दुगुनी-अनुप्रेषिते',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|बैटम्|बैटानि}}',
'nmembers'      => '$1 {{PLURAL:$1|सदस्यः|सदस्याः}}',
'prefixindex'   => 'पूर्वलग्नेन सहितानि सर्वाणि पृष्ठाणि',
'longpages'     => 'दीर्घाणि पृष्ठाणि',
'usercreated'   => '$1 $2वादनम् अतक्षतः',
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
'linksearch'      => 'बाह्य-संबंधनानि',
'linksearch-line' => '$2 अधि $1 अन्वित अस्ति।',

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
'watchlistfor2'     => 'हि $1 $2',
'addedwatchtext'    => 'भवतः [[Special:Watchlist|ध्यानसूचिकायां]] "[[:$1]]" इत्येतत् योजितमस्ति।
इदानींप्रभृति अस्मिन् पृष्ठे तथा अस्य चर्चापृष्ठे सन्तः परिवर्तनानि भवतः निरीक्षासूचिकायां द्रक्ष्यन्ते तथा च [[Special:RecentChanges|सद्यःपरिवर्तितानां सूचिकायां]] इदं पृष्ठं स्थूलाक्षरैः द्रक्ष्यते, यस्मात् भवान् सरलतया इदं पश्यतु <p>निरीक्षासूचिकातः निराकर्तुमिच्छति चेत्, "मा निरीक्षताम्" इत्यसमिन् नोदयतु।',
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
'actionfailed'          => 'कर्मन् रिष्ट',
'deletedtext'           => '"$1" इत्येतद् अपाकृतमस्ति।
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
'protect-text'                => "'''$1''' इति पृष्ठस्य कृते सुरक्षा-स्तरं भवान् अत्र दृष्टुं शक्नोति, तथा च तं परिवर्तयितुं शक्नोति।",
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
'sp-contributions-uploads'  => 'अपलोड',
'sp-contributions-logs'     => 'लोग्स',
'sp-contributions-talk'     => 'संभाषणम्',
'sp-contributions-search'   => 'योगदानानां कृते अन्विष्यतु',
'sp-contributions-username' => 'आइ.पी.संकेतः अथवा प्रयोक्तृ-नाम :',
'sp-contributions-submit'   => 'अन्वेषणम्',

# What links here
'whatlinkshere'            => 'केभ्यः पृष्ठेभ्यः सम्बद्धम्',
'whatlinkshere-title'      => '"$1" इत्यस्मात् संबंधितानि पृष्ठाणि',
'whatlinkshere-page'       => 'पृष्ठम् :',
'linkshere'                => "अधोलिखितानि पृष्ठाणि '''[[:$1]]''' इत्येतद् प्रति संबंधनं कुर्वन्ति :",
'nolinkshere'              => "'''[[:$1]]'''प्रति नपृष्ठा सम्बद्धं करोति",
'isredirect'               => 'अनुप्रेषण-पृष्ठम्',
'istemplate'               => 'मिलापयतु',
'isimage'                  => 'सञ्चिकासंबन्ध',
'whatlinkshere-prev'       => '{{PLURAL:$1|पूर्वतनम्|पूर्वतनानि $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|आगामि|आगामिनि $1}}',
'whatlinkshere-links'      => '← संबंधनानि',
'whatlinkshere-hideredirs' => '$1 अनुप्रेषणानि',
'whatlinkshere-hidetrans'  => '$1 मिलापनानि (ट्रांस्क्लुसन् इत्येतानि)',
'whatlinkshere-hidelinks'  => '$1 संबंधनानि',
'whatlinkshere-hideimages' => '$1 चित्र संबन्धा',
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
'move-page-legend' => 'पृष्ठं चालयतु।',
'movearticle'      => 'पृष्ठं चालयतु :',
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
'movelogpage'      => 'लॉग् इत्येतद् चालयतु',
'movereason'       => 'कारणम् :',
'revertmove'       => 'पुरातनीं आवृत्तिं प्रति गमयतु',

# Export
'export' => 'पृष्ठाणां निर्यातं करोतु',

# Namespace 8 related
'allmessages'                 => 'व्यवस्था सन्देशाः',
'allmessagesname'             => 'नाम',
'allmessagesdefault'          => 'डिफॉल्टसन्देशपाठ',
'allmessages-filter-all'      => 'अखिलम्',
'allmessages-filter-modified' => 'परिवर्तित',
'allmessages-language'        => 'भाषा:',
'allmessages-filter-submit'   => 'गच्छतु',

# Thumbnails
'thumbnail-more'  => 'विस्तारयतु',
'thumbnail_error' => 'सङ्कुचितचित्र निर्माण मिथ्यात्व: $1',

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

# Patrol log
'patrol-log-diff' => 'संस्करण $1',

# Browsing diffs
'previousdiff' => '← पुरातनतरं सम्पादनम्',
'nextdiff'     => 'नवतरं संपादनम् →',

# Media information
'file-info-size' => '$1 × $2 पिक्सेलानि, संचिकायाः आकारः: $3, MIME-प्रकारः: $4',
'file-nohires'   => '<small>उच्चतरं विभेदनं नोपलब्धम्</small>',
'svg-long-desc'  => 'SVG संचिका, साधारणतया $1 × $2 पिक्सेलानि, संचिकायाः आकारः : $3',
'show-big-image' => 'पूर्णं विभेदनम्',

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
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-gpsspeedref' => 'गती एकक',

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

# Special:Tags
'tag-filter' => '[[Special:Tags|Tag]] शोदनी:',

);
