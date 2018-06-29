<?php
/** Sanskrit (संस्कृतम्)
 *
 * To improve a translation please visit https://translatewiki.net
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

$digitTransformTable = [
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
];

$linkPrefixExtension = false;

$namespaceNames = [
	NS_MEDIA            => 'माध्यमम्',
	NS_SPECIAL          => 'विशेषः',
	NS_TALK             => 'सम्भाषणम्',
	NS_USER             => 'सदस्यः',
	NS_USER_TALK        => 'सदस्यसम्भाषणम्',
	NS_PROJECT_TALK     => '$1सम्भाषणम्',
	NS_FILE             => 'सञ्चिका',
	NS_FILE_TALK        => 'सञ्चिकासम्भाषणम्',
	NS_MEDIAWIKI        => 'मीडियाविकि',
	NS_MEDIAWIKI_TALK   => 'मीडियाविकिसम्भाषणम्',
	NS_TEMPLATE         => 'फलकम्',
	NS_TEMPLATE_TALK    => 'फलकसम्भाषणम्',
	NS_HELP             => 'साहाय्यम्',
	NS_HELP_TALK        => 'साहाय्यसम्भाषणम्',
	NS_CATEGORY         => 'वर्गः',
	NS_CATEGORY_TALK    => 'वर्गसम्भाषणम्',
];

$namespaceAliases = [
	'माध्यम'             => NS_MEDIA,
	'विशेष'              => NS_SPECIAL,
	'विशेषम्'            => NS_SPECIAL,
	'संभाषणं'            => NS_TALK,
	'योजकः'              => NS_USER,
	'योजकसंभाषणं'        => NS_USER_TALK,
	'योजकसम्भाषणम्'      => NS_USER_TALK,
	'$1संभाषणं'          => NS_PROJECT_TALK,
	'चित्रं'             => NS_FILE,
	'चित्रम्'           => NS_FILE,
	'चित्रसंभाषणं'       => NS_FILE_TALK,
	'चित्रसम्भाषणम्'     => NS_FILE_TALK,
	'मिडीयाविकी'         => NS_MEDIAWIKI,
	'मिडियाविकीसंभाषणं'  => NS_MEDIAWIKI_TALK,
	'मिडियाविकीसम्भाषणम्' => NS_MEDIAWIKI_TALK,
	'बिंबधर'             => NS_TEMPLATE,
	'बिंबधर_संभाषणं'     => NS_TEMPLATE_TALK,
	'फलकस्य_सम्भाषणम्'   => NS_TEMPLATE_TALK,
	'सहाय्य'             => NS_HELP,
	'सहाय्यम्'           => NS_HELP,
	'सहाय्यसंभाषणं'      => NS_HELP_TALK,
	'सहाय्यस्य_सम्भाषणम्' => NS_HELP_TALK,
	'उपकारः'             => NS_HELP,
	'उपकारसंभाषणं'       => NS_HELP_TALK,
	'वर्गसंभाषणं'        => NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Allmessages'               => [ 'सर्वसन्देशाः', 'सर्वप्रणाली-संदेश' ],
	'Allpages'                  => [ 'सर्वपृष्ठानि', 'सर्वपृष्टानि' ],
	'Ancientpages'              => [ 'पुरातनपृष्ठानि', 'पूर्वतनपृष्टानि' ],
	'Blankpage'                 => [ 'रिक्तपृष्ठानि', 'रिक्तपृष्ठ' ],
	'Block'                     => [ 'प्रतिबन्धः', 'सदस्यप्रतिबन्ध' ],
	'Booksources'               => [ 'पुस्तकस्रोतांसि', 'पुस्तकस्रोत' ],
	'BrokenRedirects'           => [ 'भग्नानि_अनुप्रेषणानि', 'खण्डीतपुनर्निर्देशन' ],
	'Categories'                => [ 'वर्गाः', 'वर्गः' ],
	'ChangePassword'            => [ 'कूटशब्दः_परिवर्त्यताम्', 'सङ्केतशब्दपुन:प्रयुक्ता' ],
	'Confirmemail'              => [ 'विपत्रं_पुष्ट्यताम्', 'विपत्रपुष्टिकृते' ],
	'Contributions'             => [ 'योगदानानि', 'योगदानम्' ],
	'CreateAccount'             => [ 'लेखा_सृज्यताम्', 'सृज्उपयोजकसंज्ञा' ],
	'Deadendpages'              => [ 'मृतानि_पृष्ठानि', 'निराग्रपृष्टानि' ],
	'DeletedContributions'      => [ 'अपाकृतानि_योगदानानि', 'परित्यागितयोगदान' ],
	'DoubleRedirects'           => [ 'द्वैधपुनर्निर्देशनम्', 'पुनर्निर्देशनद्वंद्व' ],
	'Emailuser'                 => [ 'विपत्रप्रयोक्ता', 'विपत्रयोजक' ],
	'ExpandTemplates'           => [ 'फलकानि_विस्तीर्यन्ताम्', 'बिंबधरविस्तारकरोसि' ],
	'Export'                    => [ 'निर्यापयतु', 'निर्यात' ],
	'Fewestrevisions'           => [ 'स्वल्पतमपरिवर्तानानि', 'स्वल्पपरिवर्तन' ],
	'FileDuplicateSearch'       => [ 'समानसञ्चिकान्वेषणम्', 'अनुकृतसंचिकाशोध' ],
	'Filepath'                  => [ 'सञ्चिकापथः', 'संचिकापथ' ],
	'Import'                    => [ 'आयापयतु', 'आयात' ],
	'Invalidateemail'           => [ 'विपत्रेऽमान्यम्', 'अमान्यविपत्र' ],
	'BlockList'                 => [ 'प्रतिबन्धावलिः', 'प्रतिबन्धसूची' ],
	'LinkSearch'                => [ 'परिसन्धेः_अन्वेषणम्', 'सम्बन्धन्‌शोध' ],
	'Listadmins'                => [ 'प्रबन्धकावलिः', 'प्रचालकसूची' ],
	'Listbots'                  => [ 'बॉटसूची', 'स्वयंअनुकृसूची' ],
	'Listfiles'                 => [ 'सञ्चिकावलिः', 'चित्रसूची', 'संचिकासूचि' ],
	'Listgrouprights'           => [ 'समूहाधिकारावलिः', 'गटअधिकारसूची' ],
	'Listredirects'             => [ 'अनुप्रेषितावलिः', 'विचालन्‌सूची' ],
	'Listusers'                 => [ 'सदस्यावलिः', 'सदस्यासूची' ],
	'Lockdb'                    => [ 'दत्तांशकीलनम्', 'विदाद्वारंबन्ध्' ],
	'Log'                       => [ 'संरक्षितावलिः', 'अङ्कन' ],
	'Lonelypages'               => [ 'एकाकिपृष्ठानि', 'अकलपृष्टानि' ],
	'Longpages'                 => [ 'दीर्घपृष्ठानि', 'दीर्घपृष्टानि' ],
	'MergeHistory'              => [ 'इतिहासविलयः', 'इतिहाससंयोग' ],
	'MIMEsearch'                => [ 'MIME_अन्वेषणम्', 'विविधामाप_(माईम)_शोधसि' ],
	'Mostcategories'            => [ 'अधिकतमवर्गाः', 'अधिकतमवर्ग' ],
	'Mostimages'                => [ 'अधिकतमसञ्चिकाः', 'अधिकतमसम्भन्दिन्_संचिका' ],
	'Mostlinked'                => [ 'अधिकतमपरिसन्धितम्', 'अधिकतमसम्भन्दिन्_पृष्टानि', 'अधिकतमसम्भन्दिन्' ],
	'Mostlinkedcategories'      => [ 'अधिकतमपरिसन्धितवर्गाः', 'अधिकतमसम्भन्दिन्_वर्ग' ],
	'Mostlinkedtemplates'       => [ 'अधिकतमपरिसन्धितफलकानि', 'अधिकतमसम्भन्दिन्_फलकानि' ],
	'Mostrevisions'             => [ 'अधिकतमसंस्करणानि', 'अधिकतमपरिवर्तन' ],
	'Movepage'                  => [ 'पृष्ठस्थानान्तरणम्', 'पृष्ठस्थानान्तर' ],
	'Mycontributions'           => [ 'मम_योगदानानि', 'मदीययोगदानम्' ],
	'Mypage'                    => [ 'मम_पृष्ठम्', 'मम_पृष्टम्' ],
	'Mytalk'                    => [ 'मम_सम्भाषणम्', 'मदीयसंवादम्' ],
	'Newimages'                 => [ 'नवीनचित्राणि', 'नूतनसंचिका', 'नूतनचित्रानि' ],
	'Newpages'                  => [ 'नवीनपृष्ठानि', 'नूतनपृष्टानि' ],
	'PasswordReset'             => [ 'कूटशब्दस्य_पुनस्स्थापनम्', 'सङ्केतशब्दपुन:प्रयु्क्ता' ],
	'Preferences'               => [ 'इष्टतमानि' ],
	'Prefixindex'               => [ 'उपसर्गानुक्रमणी', 'उपसर्गअनुक्रमणी' ],
	'Protectedpages'            => [ 'सुरक्षितपृष्ठानि', 'सुरक्षितपृष्टानि' ],
	'Protectedtitles'           => [ 'सुरक्षितशीर्षकाणि', 'सुरक्षितशिर्षकम्' ],
	'Randompage'                => [ 'यादृच्छिकपृष्ठम्', 'अविशीष्टपृष्ठम्' ],
	'RandomInCategory'          => [ 'वर्गे_यादृच्छिकम्', 'अविशिष्टवर्ग' ],
	'Randomredirect'            => [ 'यादृच्छिकानुप्रेषितम्', 'अविशीष्टविचालन्‌' ],
	'Recentchanges'             => [ 'नूतनपरिवर्तनानि', 'नवीनतम_परिवर्तन' ],
	'Recentchangeslinked'       => [ 'नूतनपरिवर्तनानां_परिसन्धयः', 'नवीनतमसम्भन्दिन_परिवर्त' ],
	'Revisiondelete'            => [ 'संस्करणापाकरणम्', 'आवृत्तीपरित्याग' ],
	'Search'                    => [ 'अन्वेषणम्', 'शोध' ],
	'Shortpages'                => [ 'लघुपृष्ठानि', 'लघुपृष्टानि' ],
	'Specialpages'              => [ 'विशेषपृष्ठानि', 'विशेषपृष्टानि' ],
	'Statistics'                => [ 'साङ्ख्यिकी', 'सांख्यिकी' ],
	'Uncategorizedcategories'   => [ 'अवर्गीकृतवर्गाः', 'अवर्गीकृतवर्ग' ],
	'Uncategorizedimages'       => [ 'अवर्गीकृतचित्राणि', 'अवर्गीकृतसंचिका', 'अवर्गीकृतचित्रानि' ],
	'Uncategorizedpages'        => [ 'अवर्गीकृतपृष्ठानि', 'अवर्गीकृतपृष्टानि' ],
	'Uncategorizedtemplates'    => [ 'अवर्गीकृतफलकानि' ],
	'Undelete'                  => [ 'पुनस्स्थापनम्', 'प्रत्यादिश्_परित्याग' ],
	'Unlockdb'                  => [ 'दत्तांशोद्घाटनम्', 'विवृतविदाद्वारंतालक' ],
	'Unusedcategories'          => [ 'अप्रयुक्तवर्गाः', 'अप्रयूक्तवर्ग' ],
	'Unusedimages'              => [ 'अप्रयुक्तचित्राणि', 'अप्रयूक्तसंचिका' ],
	'Unusedtemplates'           => [ 'अप्रयुक्तफलकानि', 'अप्रयूक्तबिंबधर' ],
	'Unwatchedpages'            => [ 'अनिरीक्षितपृष्ठानि', 'अनिरिक्षीतपृष्ठ' ],
	'Upload'                    => [ 'उपारोपणम्', 'भारंन्यस्यति' ],
	'Userlogin'                 => [ 'सदस्यप्रवेशः', 'सदस्यप्रवेशन' ],
	'Userlogout'                => [ 'सदस्यनिर्गमनम्', 'सदस्यबहिर्गमन' ],
	'Userrights'                => [ 'सदस्याधिकाराः', 'योजकआधिकार' ],
	'Version'                   => [ 'संस्करणम्', 'आवृत्ती' ],
	'Wantedcategories'          => [ 'वाञ्छितवर्गाः', 'प्रार्थितवर्ग' ],
	'Wantedfiles'               => [ 'वाञ्छितसञ्चिकाः', 'प्रार्थितसंचिका' ],
	'Wantedpages'               => [ 'वाञ्छितपृष्ठानि', 'प्रार्थितपृष्टानि' ],
	'Wantedtemplates'           => [ 'वाञ्छितफलकानि', 'प्रार्थितफलकानि' ],
	'Watchlist'                 => [ 'निरीक्षा_सूची', 'निरीक्षासूचिः' ],
	'Whatlinkshere'             => [ 'किमत्र_सँल्लग्नम्', 'किमपृष्ठ_सम्बद्धंकरोति' ],
	'Withoutinterwiki'          => [ 'अन्तर्विकिपरिसन्धिहीनम्', 'आन्तरविकिहीन' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#पुनर्निदेशन', '#अनुप्रेषित', '#REDIRECT' ],
	'notoc'                     => [ '0', '__नैवअनुक्रमणी__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__नैवसंक्रमणका__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__अनुक्रमणीसचते__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__अनुक्रमणी__', '__TOC__' ],
	'noeditsection'             => [ '0', '__नैवसम्पादनविभाग__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'अद्यमासे', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'अद्यमासेनाम', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'अद्यमासेनामसाधारण', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'अद्यमासेसंक्षीप्त', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'अद्यदिवसे', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'अद्यदिवसे२', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'अद्यदिवसेनाम', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'अद्यवर्ष', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'सद्यसमय', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'सद्यघण्टा', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'स्थानिकमासे', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'स्थानिकमासेनाम', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'स्थानिकमासेनामसाधारण', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'स्थानिकमासेसंक्षीप्त', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'स्थानिकदिवसे', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'स्थानिकदिवसे२', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'स्थानिकदिवसेनाम', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'स्थानिकवर्षे', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'स्थानिकसमये', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'स्थानिकघण्टा', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'पृष्ठानाम्‌सङ्ख्या', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'लेखस्य‌सङ्ख्या', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'संचिकानाम्‌‌सङ्ख्या', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'योजकस्यसङ्ख्या', 'NUMBEROFUSERS' ],
	'numberofedits'             => [ '1', 'सम्पादनसङ्ख्या', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'पृष्ठनाम', 'PAGENAME' ],
	'namespace'                 => [ '1', 'नामविश्व', 'NAMESPACE' ],
	'talkspace'                 => [ '1', 'व्यासपिठ', 'TALKSPACE' ],
	'subjectspace'              => [ '1', 'विषयविश्व', 'लेखविश्व', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'fullpagename'              => [ '1', 'पूर्णपृष्ठनाम', 'FULLPAGENAME' ],
	'subpagename'               => [ '1', 'उपपृष्ठनाम', 'SUBPAGENAME' ],
	'basepagename'              => [ '1', 'आधारपृष्ठनाम', 'BASEPAGENAME' ],
	'talkpagename'              => [ '1', 'संवादपृष्ठनाम', 'TALKPAGENAME' ],
	'subjectpagename'           => [ '1', 'विषयपृष्ठनाम', 'लेखपृष्ठनाम', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'msg'                       => [ '0', 'सन्देश:', 'MSG:' ],
	'msgnw'                     => [ '0', 'नूतनसन्देश:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'लघुत्तम', 'अङ्गुष्ठ', 'सङ्कुचितचित्र', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'सङ्कुचितचित्र=$1', 'अङ्गुष्ठ=$1', 'लघुत्तमचित्र=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'दक्षिणत', 'right' ],
	'img_left'                  => [ '1', 'वामतः', 'left' ],
	'img_none'                  => [ '1', 'नैव', 'none' ],
	'img_width'                 => [ '1', '$1पिट', '$1px' ],
	'img_center'                => [ '1', 'मध्य', 'center', 'centre' ],
	'img_framed'                => [ '1', 'आबन्ध', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'निराबन्ध', 'frameless' ],
	'img_page'                  => [ '1', 'पृष्ठ=$1', 'पृष्ठ $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'उन्नत', 'उन्नत=$1', 'उन्नत $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'सीमा', 'border' ],
	'img_baseline'              => [ '1', 'आधाररेखा', 'baseline' ],
	'img_sub'                   => [ '1', 'विषये', 'sub' ],
	'img_super'                 => [ '1', 'अति', 'तीव्र', 'super', 'sup' ],
	'img_top'                   => [ '1', 'अग्र', 'top' ],
	'img_text_top'              => [ '1', 'पाठ्य-अग्र', 'text-top' ],
	'img_middle'                => [ '1', 'मध्ये', 'middle' ],
	'img_bottom'                => [ '1', 'अधस', 'bottom' ],
	'img_text_bottom'           => [ '1', 'पाठ्य-अधस', 'text-bottom' ],
	'img_link'                  => [ '1', 'सम्बद्धं=$1', 'link=$1' ],
	'img_alt'                   => [ '1', 'विकल्प=$1', 'alt=$1' ],
	'sitename'                  => [ '1', 'स्थलनाम', 'SITENAME' ],
	'grammar'                   => [ '0', 'व्याकरण:', 'GRAMMAR:' ],
	'notitleconvert'            => [ '0', '__नैवशिर्षकपरिवर्त__', '__नैशिप__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__नैवलेखपरिवर्त__', '__नैलेप__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'अद्यसप्ताह', 'CURRENTWEEK' ],
	'localweek'                 => [ '1', 'स्थानिकसप्ताह', 'LOCALWEEK' ],
	'revisionid'                => [ '1', 'आवृत्तीक्रमांक', 'REVISIONID' ],
	'revisionday'               => [ '1', 'आवृत्तीदिवसे', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'आवृत्तीदिवसे२', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'आवृत्तीमासे', 'REVISIONMONTH' ],
	'revisionyear'              => [ '1', 'आवृत्तीवर्षे', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'आवृत्तीसमयमुद्रा', 'REVISIONTIMESTAMP' ],
	'plural'                    => [ '0', 'अनेकवचन:', 'PLURAL:' ],
	'displaytitle'              => [ '1', 'प्रदर्शनशीर्षक', 'उपाधिदर्शन', 'DISPLAYTITLE' ],
	'newsectionlink'            => [ '1', '__नूतनविभागसम्बद्धं__', '__NEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'अद्यआवृत्ती', 'CURRENTVERSION' ],
	'currenttimestamp'          => [ '1', 'सद्यसमयमुद्रा', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'स्थानिकसमयमुद्रा', 'LOCALTIMESTAMP' ],
	'directionmark'             => [ '1', 'दिशाचिह्न', 'दिशे', 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                  => [ '0', '#भाषा:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'विषयभाषा', 'आधेयभाषा', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'नामविश्वातपृष्ठ', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'प्रचालकसंख्या', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'रचनासंख्या', 'FORMATNUM' ],
	'special'                   => [ '0', 'विशेष', 'special' ],
	'filepath'                  => [ '0', 'संचिकापथ', 'FILEPATH:' ],
	'tag'                       => [ '0', 'वीजक', 'tag' ],
	'hiddencat'                 => [ '1', '__लुप्तवर्ग__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'वर्गेपृष्ठ', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'पृष्ठाकार', 'PAGESIZE' ],
	'index'                     => [ '1', '__अनुक्रमणिका__', '__INDEX__' ],
	'noindex'                   => [ '1', '__नैवअनुक्रमणिका__', '__NOINDEX__' ],
	'numberingroup'             => [ '1', 'गणानामसंख्या', 'गणसंख्या', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'staticredirect'            => [ '1', '__अनित्यपुनर्निदेशन__', '__STATICREDIRECT__' ],
	'protectionlevel'           => [ '1', 'रक्षास्तर', 'PROTECTIONLEVEL' ],
];

$digitGroupingPattern = "##,##,###";
