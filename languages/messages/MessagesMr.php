<?php
/** Marathi (मराठी)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aan
 * @author Angela
 * @author Ankitgadgil
 * @author Balaji
 * @author Chandu
 * @author Dnyanesh325
 * @author Harshalhayat
 * @author Hemanshu
 * @author Hemant wikikosh1
 * @author Htt
 * @author Kaajawa
 * @author Kaustubh
 * @author Mahitgar
 * @author Marathipremi101
 * @author Mohanpurkar
 * @author Mvkulkarni23
 * @author Prabodh1987
 * @author Rahuldeshmukh101
 * @author Rdeshmuk
 * @author Sankalpdravid
 * @author Shreewiki
 * @author Shreyas19
 * @author Sudhanwa
 * @author Tusharpawar1982
 * @author V.narsikar
 * @author Vpnagarkar
 * @author Ynwala
 * @author अभय नातू
 * @author कोलࣿहापࣿरी
 * @author कोल्हापुरी
 * @author प्रणव कुलकर्णी
 * @author शࣿरीहरि
 */

$namespaceNames = array(
	NS_MEDIA            => 'मिडिया',
	NS_SPECIAL          => 'विशेष',
	NS_TALK             => 'चर्चा',
	NS_USER             => 'सदस्य',
	NS_USER_TALK        => 'सदस्य चर्चा',
	NS_PROJECT_TALK     => '$1_चर्चा',
	NS_FILE             => 'चित्र',
	NS_FILE_TALK        => 'चित्र_चर्चा',
	NS_MEDIAWIKI        => 'मिडियाविकी',
	NS_MEDIAWIKI_TALK   => 'मिडियाविकी_चर्चा',
	NS_TEMPLATE         => 'साचा',
	NS_TEMPLATE_TALK    => 'साचा_चर्चा',
	NS_HELP             => 'सहाय्य',
	NS_HELP_TALK        => 'सहाय्य_चर्चा',
	NS_CATEGORY         => 'वर्ग',
	NS_CATEGORY_TALK    => 'वर्ग_चर्चा',
);

$namespaceAliases = array(
	'साहाय्य' => NS_HELP,
	'साहाय्य_चर्चा' => NS_HELP_TALK,
);

# !!# sqlविचारा is not in normalised form, which is Sqlविचारा or Sqlविचारा
$specialPageAliases = array(
	'Activeusers'               => array( 'कार्यरतसदस्य' ),
	'Allmessages'               => array( 'सर्व_निरोप' ),
	'Allpages'                  => array( 'सर्व_पाने' ),
	'Ancientpages'              => array( 'जुनी_पाने' ),
	'Blankpage'                 => array( 'कोरेपान' ),
	'Block'                     => array( 'प्रतिबंध', 'अंकपत्ताप्रतिबंध', 'सदस्यप्रतिबंध' ),
	'Blockme'                   => array( 'मलाप्रतिबंधकरा' ),
	'Booksources'               => array( 'पुस्तकस्रोत' ),
	'BrokenRedirects'           => array( 'चुकीची_पुनर्निर्देशने' ),
	'Categories'                => array( 'वर्ग' ),
	'ChangePassword'            => array( 'परवलीचाशब्दबदला' ),
	'Confirmemail'              => array( 'विपत्रनक्कीकरा' ),
	'Contributions'             => array( 'योगदान' ),
	'CreateAccount'             => array( 'सदस्यनोंद' ),
	'Deadendpages'              => array( 'टोकाची_पाने' ),
	'DeletedContributions'      => array( 'वगळलेलीयोगदाने' ),
	'Disambiguations'           => array( 'नि:संदिग्धीकरण' ),
	'DoubleRedirects'           => array( 'दुहेरी_पुनर्निर्देशने' ),
	'Emailuser'                 => array( 'विपत्रवापरकर्ता' ),
	'Export'                    => array( 'निर्यात' ),
	'Fewestrevisions'           => array( 'कमीतकमीआवर्तने' ),
	'FileDuplicateSearch'       => array( 'दुहेरीसंचिकाशोध' ),
	'Filepath'                  => array( 'संचिकेचा_पत्ता_(पाथ)' ),
	'Import'                    => array( 'आयात' ),
	'Invalidateemail'           => array( 'चूकदिनांकविपत्र' ),
	'BlockList'                 => array( 'प्रतिबंधनसुची' ),
	'LinkSearch'                => array( 'दुवाशोध' ),
	'Listadmins'                => array( 'प्रबंधकांची_यादी' ),
	'Listbots'                  => array( 'सांगकाम्यांची_यादी' ),
	'Listfiles'                 => array( 'चित्रयादी' ),
	'Listgrouprights'           => array( 'गट_अधिकार_यादी' ),
	'Listredirects'             => array( 'पुर्ननिर्देशनसुची' ),
	'Listusers'                 => array( 'सदस्यांची_यादी' ),
	'Lockdb'                    => array( 'कुलुपबंद_करा_(डेटाबेस)' ),
	'Log'                       => array( 'नोंद', 'नोंदी' ),
	'Lonelypages'               => array( 'एकलपाने' ),
	'Longpages'                 => array( 'मोठी_पाने' ),
	'MergeHistory'              => array( 'इतिहास_एकत्र_करा' ),
	'MIMEsearch'                => array( 'माईमशोध' ),
	'Mostcategories'            => array( 'सर्वात_जास्त_वर्ग' ),
	'Mostimages'                => array( 'सर्वाधिकसांधलेलीसंचिका' ),
	'Mostlinked'                => array( 'सर्वात_जास्त_जोडलेली' ),
	'Mostlinkedcategories'      => array( 'सर्वात_जास्त_जोडलेले_वर्ग', 'सर्वात_जास्त_वापरलेले_वर्ग' ),
	'Mostlinkedtemplates'       => array( 'सर्वात_जास्त_जोडलेले_साचे', 'सर्वात_जास्त_वापरलेले_साचे' ),
	'Mostrevisions'             => array( 'सर्वाधिकआवर्तने' ),
	'Movepage'                  => array( 'पान_हलवा' ),
	'Mycontributions'           => array( 'माझे_योगदान' ),
	'Mypage'                    => array( 'माझे_पान' ),
	'Mytalk'                    => array( 'माझ्या_चर्चा' ),
	'Newimages'                 => array( 'नवीन_चित्रे' ),
	'Newpages'                  => array( 'नवीन_पाने' ),
	'Popularpages'              => array( 'प्रसिद्ध_पाने' ),
	'Preferences'               => array( 'पसंती' ),
	'Prefixindex'               => array( 'उपसर्गसुची' ),
	'Protectedpages'            => array( 'सुरक्षित_पाने' ),
	'Protectedtitles'           => array( 'सुरक्षित_शीर्षके' ),
	'Randompage'                => array( 'अविशिष्ट', 'अविशिष्ट_पृष्ठ' ),
	'Randomredirect'            => array( 'अविशिष्ट्पुर्ननिर्देशन' ),
	'Recentchanges'             => array( 'अलीकडील_बदल' ),
	'Recentchangeslinked'       => array( 'सांधलेलेअलिकडीलबदल' ),
	'Revisiondelete'            => array( 'आवर्तनवगळा' ),
	'Search'                    => array( 'शोधा' ),
	'Shortpages'                => array( 'छोटी_पाने' ),
	'Specialpages'              => array( 'विशेष_पाने' ),
	'Statistics'                => array( 'सांख्यिकी' ),
	'Tags'                      => array( 'खूणा' ),
	'Uncategorizedcategories'   => array( 'अवर्गीकृत_वर्ग' ),
	'Uncategorizedimages'       => array( 'अवर्गीकृत_चित्रे' ),
	'Uncategorizedpages'        => array( 'अवर्गीकृत_पाने' ),
	'Uncategorizedtemplates'    => array( 'अवर्गीकृत_साचे' ),
	'Undelete'                  => array( 'काढणे_रद्द_करा' ),
	'Unlockdb'                  => array( 'विदागारताळेउघडा' ),
	'Unusedcategories'          => array( 'न_वापरलेले_वर्ग' ),
	'Unusedimages'              => array( 'न_वापरलेली_चित्रे' ),
	'Unusedtemplates'           => array( 'नउपयोगातआणलेलासाचा' ),
	'Unwatchedpages'            => array( 'अप्रेक्षीतपाने' ),
	'Upload'                    => array( 'चढवा' ),
	'Userlogin'                 => array( 'सदस्यप्रवेश' ),
	'Userlogout'                => array( 'सदस्यबहिर्गमन' ),
	'Userrights'                => array( 'खातेदाराचे_अधिकार' ),
	'Version'                   => array( 'आवृत्ती' ),
	'Wantedcategories'          => array( 'हवे_असलेले_वर्ग' ),
	'Wantedfiles'               => array( 'संचिकाहवी' ),
	'Wantedpages'               => array( 'हवे_असलेले_लेख' ),
	'Wantedtemplates'           => array( 'साचाहवा' ),
	'Watchlist'                 => array( 'पहार्‍याची_सूची' ),
	'Whatlinkshere'             => array( 'येथे_काय_जोडले_आहे' ),
	'Withoutinterwiki'          => array( 'विनाआंतरविकि' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#पुनर्निर्देशन', '#पुर्ननिर्देशन', '#REDIRECT' ),
	'notoc'                 => array( '0', '__अनुक्रमणिकानको__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__प्रदर्शननको__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__अनुक्रमणिकाहवीच__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__अनुक्रमणिका__', '__TOC__' ),
	'noeditsection'         => array( '0', '__असंपादनक्षम__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__शीर्षकनाही__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'सद्यमहिना', 'सद्यमहिना२', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'सद्यमहिना१', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'सद्यमहिनानाव', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'सद्यमहिनासाधारण', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'सद्यमहिनासंक्षीप्त', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'सद्यदिवस', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'सद्यदिवस२', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'सद्यदिवसनाव', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'सद्यवर्ष', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'सद्यवेळ', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'सद्यतास', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'स्थानिकमहिना', 'स्थानिकमहिना२', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'स्थानिकमहिना१', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'स्थानिकमहिनानाव', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'स्थानिकमहिनासाधारण', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'स्थानिकमहिनासंक्षीप्त', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'स्थानिकदिवस', 'LOCALDAY' ),
	'localday2'             => array( '1', 'स्थानिकदिवस२', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'स्थानिकदिवसनाव', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'स्थानिकवर्ष', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'स्थानिकवेळ', 'LOCALTIME' ),
	'localhour'             => array( '1', 'स्थानिकतास', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'पानसंख्या', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'लेखसंख्या', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'संचिकासंख्या', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'सदस्यसंख्या', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'सक्रीयसदस्यसंख्या', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'संपादनसंख्या', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'धडकसंख्या', 'प्रेक्षासंख्या', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'लेखनाव', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'लेखानावव', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'नामविश्व', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'नामविश्वा', 'नामविश्वाचे', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'चर्चाविश्व', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'चर्चाविश्वा', 'चर्चाविश्वाचे', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'विषयविश्व', 'लेखविश्व', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'विषयविश्वा', 'लेखविश्वा', 'विषयविश्वाचे', 'लेखविश्वाचे', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'पूर्णलेखनाव', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'पूर्णलेखनावे', 'अंशदुवा', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'उपपाननाव', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'उपपाननावे', 'उपपाननावाचे', 'उपौंशदुवा', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'मूळपाननाव', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'मूळपाननावे', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'चर्चापाननाव', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'चर्चापाननावे', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'विषयपाननाव', 'लेखपाननाव', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'विषयपाननावे', 'लेखपाननावे', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                   => array( '0', 'संदेश:', 'निरोप:', 'MSG:' ),
	'subst'                 => array( '0', 'पर्याय:', 'समाविष्टी:', 'अबाह्य:', 'निरकंसबिंब:', 'कंसत्याग:', 'साचाहिन:', 'साचान्तर:', 'साचापरिस्फोट:', 'साचोद्घाटन:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'संदेशनवा:', 'निरोपनवा:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'इवलेसे', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'इवलेसे=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'उजवे', 'right' ),
	'img_left'              => array( '1', 'डावे', 'left' ),
	'img_none'              => array( '1', 'कोणतेचनाही', 'नन्ना', 'none' ),
	'img_width'             => array( '1', '$1अंश', '$1कणी', '$1पक्ष', '$1px' ),
	'img_center'            => array( '1', 'मध्यवर्ती', 'center', 'centre' ),
	'img_framed'            => array( '1', 'चौकट', 'फ़्रेम', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'विनाचौकट', 'विनाफ़्रेम', 'frameless' ),
	'img_page'              => array( '1', 'पान=$1', 'पान $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'उभा', 'उभा=$1', 'उभा $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'सीमा', 'border' ),
	'img_baseline'          => array( '1', 'तळरेषा', 'आधाररेषा', 'baseline' ),
	'img_sub'               => array( '1', 'अधो', 'sub' ),
	'img_super'             => array( '1', 'उर्ध्व', 'super', 'sup' ),
	'img_top'               => array( '1', 'अत्यूच्च', 'top' ),
	'img_text_top'          => array( '1', 'मजकूर-शीर्ष', 'शीर्ष-मजकूर', 'text-top' ),
	'img_middle'            => array( '1', 'मध्य', 'middle' ),
	'img_bottom'            => array( '1', 'तळ', 'बूड', 'bottom' ),
	'img_text_bottom'       => array( '1', 'मजकुरतळ', 'text-bottom' ),
	'img_link'              => array( '1', 'दुवा=$1', 'link=$1' ),
	'img_alt'               => array( '1', 'अल्ट=$1', 'alt=$1' ),
	'int'                   => array( '0', 'इन्ट:', 'INT:' ),
	'sitename'              => array( '1', 'संकेतस्थळनाव', 'SITENAME' ),
	'ns'                    => array( '0', 'नावि:', 'NS:' ),
	'nse'                   => array( '0', 'नाविअरिक्त:', 'नाव्यारिक्त:', 'नाव्याख:', 'NSE:' ),
	'localurl'              => array( '0', 'स्थानिकस्थळ:', 'स्थानिकसंकेतस्थळ:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'स्थानिकस्थली:', 'LOCALURLE:' ),
	'server'                => array( '0', 'विदादाता', 'SERVER' ),
	'servername'            => array( '0', 'विदादातानाव', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'संहीतामार्ग', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'व्याकरण:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'लिंग:', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__विनाशीर्षकबदल__', '__विनाशीब__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__विनामजकुरबदल__', '__विनामब__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'सद्यआठवडा', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'सद्यउतरण', 'सद्यउतार', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'स्थानिकआठवडा', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'स्थानिकउतरण', 'स्थानिकउतार', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'आवृत्तीक्र्मांक', 'REVISIONID' ),
	'revisionday'           => array( '1', 'आवृत्तीदिन', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'आवृत्तीदिन२', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'आवृत्तीमास', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'आवृत्तीवर्ष', 'REVISIONYEAR' ),
	'revisiontimestamp'     => array( '1', 'आवृत्तीमुद्रा', 'आवृत्तीठसा', 'REVISIONTIMESTAMP' ),
	'revisionuser'          => array( '1', 'आवृत्तीसदस्य', 'REVISIONUSER' ),
	'plural'                => array( '0', 'बहुवचन:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'संपूर्णसंस्थळ', 'FULLURL:' ),
	'fullurle'              => array( '0', 'संपूर्णसंस्थली:', 'संपूर्णसंस्थळी:', 'FULLURLE:' ),
	'raw'                   => array( '0', 'कच्चे:', 'RAW:' ),
	'displaytitle'          => array( '1', 'शीर्षकदाखवा', 'DISPLAYTITLE' ),
	'rawsuffix'             => array( '1', 'ॠ', 'R' ),
	'newsectionlink'        => array( '1', '__नवविभागदुवा__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__विनानवविभागदुवा__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'सद्यआवृत्ती', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'संकेतस्थलीआंग्ल्संकेत:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'नांगरआंग्लसंकेत', 'ANCHORENCODE' ),
	'currenttimestamp'      => array( '1', 'सद्यकालमुद्रा', 'CURRENTTIMESTAMP' ),
	'localtimestamp'        => array( '1', 'स्थानिककालमुद्रा', 'LOCALTIMESTAMP' ),
	'directionmark'         => array( '1', 'दिशाचिन्ह', 'दिशादर्शक', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'              => array( '0', '#भाषा:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'मसुदाभाषा', 'मजकुरभाषा', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'नामविश्वातीलपाने:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'प्रचालकसंख्या', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'क्रमपद्धती', 'FORMATNUM' ),
	'padleft'               => array( '0', 'डावाभरीव', 'भरीवडावा', 'PADLEFT' ),
	'padright'              => array( '0', 'उजवाभरीव', 'भरीवउजवा', 'PADRIGHT' ),
	'special'               => array( '0', 'विशेष', 'special' ),
	'defaultsort'           => array( '1', 'अविचलवर्ग:', 'अविचलवर्गकळ:', 'अविचलवर्गवर्गीकरण:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'संचिकामार्ग:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'खूण', 'खूणगाठ', 'tag' ),
	'hiddencat'             => array( '1', '__वर्गलपवा__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'वर्गातीलपाने', 'वर्गीतपाने', 'श्रेणीतपाने', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'पानक्षमता', 'PAGESIZE' ),
	'index'                 => array( '1', '__क्रमीत__', '__अनुक्रमीत__', '__INDEX__' ),
	'noindex'               => array( '1', '__विनाक्रमीत__', '__विनाअनुक्रमीत__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'गटक्रमांक', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__अविचलपुर्ननिर्देश__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'सुरक्षास्तर', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'दिनांकनपद्धती', 'formatdate', 'dateformat' ),
);

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

$linkTrail = "/^([\xE0\xA4\x80-\xE0\xA5\xA3\xE0\xA5\xB1-\xE0\xA5\xBF\xEF\xBB\xBF\xE2\x80\x8D]+)(.*)$/sDu";

$digitGroupingPattern = "##,##,###";

$messages = array(
# User preference toggles
'tog-underline'               => 'दुव्यांना अधोरेखित करा:',
'tog-highlightbroken'         => 'चुकीचे दुवे <a href="" class="new">असे दाखवा</a> (किंवा: असे दाखवा<a href="" class="internal">?</a>).',
'tog-justify'                 => 'परिच्छेद समान करा',
'tog-hideminor'               => 'छोटे बदल लपवा',
'tog-hidepatrolled'           => 'पहारा दिलेली संपादने अलीकडील बदलांमधून लपवा',
'tog-newpageshidepatrolled'   => 'नवीन पृष्ठ यादीतून पहारा दिलेली पाने लपवा',
'tog-extendwatchlist'         => 'पहार्‍याच्या सूचीत सर्व बदल दाखवा, फक्त अलीकडील बदल नकोत',
'tog-usenewrc'                => 'वाढीव अलीकडील बदल वापरा (जावास्क्रीप्टच्या उपलब्धतेची गरज)',
'tog-numberheadings'          => 'शीर्षके स्वयंक्रमांकित करा',
'tog-showtoolbar'             => 'संपादन चिन्हे दाखवा (जावास्क्रीप्ट)',
'tog-editondblclick'          => 'दोनवेळा क्लीक करुन पान संपादित करा (जावास्क्रीप्ट)',
'tog-editsection'             => '[संपादन] दुव्याने संपादन करणे शक्य करा',
'tog-editsectiononrightclick' => 'विभाग शीर्षकावर उजव्या क्लीकने संपादन करा(जावास्क्रीप्ट)',
'tog-showtoc'                 => '३ पेक्षा जास्त शीर्षके असताना अनुक्रमणिका दाखवा',
'tog-rememberpassword'        => 'माझा प्रवेश या संगणकावर लक्षात ठेवा (जास्तीत जास्त $1 {{PLURAL:$1|दिवसांकरिता}})',
'tog-watchcreations'          => 'मी तयार केलेली पाने माझ्या पहार्‍याच्या सूचीत टाका',
'tog-watchdefault'            => 'मी संपादित केलेली पाने माझ्या पहार्‍याच्या सूचीत टाका',
'tog-watchmoves'              => 'मी स्थानांतरीत केलेली पाने माझ्या पहार्‍याच्या सूचीत टाका',
'tog-watchdeletion'           => 'मी वगळलेली पाने माझ्या पहार्‍याच्या सूचीत टाका',
'tog-minordefault'            => "सर्व संपादने 'छोटा बदल' म्हणून आपोआप जतन करा.",
'tog-previewontop'            => 'झलक संपादन खिडकीच्या आधी दाखवा',
'tog-previewonfirst'          => 'पहिल्या संपादनानंतर झलक दाखवा',
'tog-nocache'                 => 'न्याहाळकाची पान सय अक्षम करा',
'tog-enotifwatchlistpages'    => 'माझ्या पहार्‍याच्या सूचीतील पान बदलल्यास मला विरोप (e-mail) पाठवा',
'tog-enotifusertalkpages'     => 'माझ्या चर्चा पानावर बदल झाल्यास मला विरोप पाठवा',
'tog-enotifminoredits'        => 'मला छोट्या बदलांकरीता सुद्धा विरोप पाठवा',
'tog-enotifrevealaddr'        => 'सूचना विरोपात माझा विरोपाचा पत्ता दाखवा',
'tog-shownumberswatching'     => 'पहारा दिलेले सदस्य दाखवा',
'tog-oldsig'                  => 'सध्याची सही:',
'tog-fancysig'                => 'सही विकिसंज्ञा म्हणून वापरा (आपोआप दुव्याशिवाय)',
'tog-externaleditor'          => 'कायम बाह्य संपादक वापरा (फक्त प्रशिक्षित सदस्यांसाठीच, संगणकावर विशेष प्रणाली लागते) ([//www.mediawiki.org/wiki/Manual:External_editors अधिक माहिती])',
'tog-externaldiff'            => 'इतिहास पानावर निवडलेल्या आवृत्त्यांमधील बदल दाखविण्यासाठी बाह्य प्रणाली वापरा (फक्त प्रशिक्षित सदस्यांसाठीच, संगणकावर विशेष प्रणाली लागते) ([//www.mediawiki.org/wiki/Manual:External_editors अधिक माहिती])',
'tog-showjumplinks'           => '"कडे जा" सुगम दुवे, उपलब्ध करा.',
'tog-uselivepreview'          => 'संपादन करता करताच झलक दाखवा (जावास्क्रीप्ट)(प्रयोगक्षम)',
'tog-forceeditsummary'        => 'जर ’बदलांचा आढावा’ दिला नसेल तर मला सूचित करा',
'tog-watchlisthideown'        => 'पहार्‍याच्या सूचीतून माझे बदल लपवा',
'tog-watchlisthidebots'       => 'पहार्‍याच्या सूचीतून सांगकामे बदल लपवा',
'tog-watchlisthideminor'      => 'माझ्या पहार्‍याच्या सूचीतून छोटे बदल लपवा',
'tog-watchlisthideliu'        => 'पहार्‍याच्या सूचीतून प्रवेश केलेल्या सदस्यांची संपादने लपवा',
'tog-watchlisthideanons'      => 'पहा‍र्‍याच्या सूचीतून अनामिक सदस्यांची संपादने लपवा',
'tog-watchlisthidepatrolled'  => 'पहार्‍याच्या सूचीतून तपासलेली संपादने लपवा',
'tog-ccmeonemails'            => 'मी इतर सदस्यांना पाठविलेल्या इमेल च्या प्रती मलाही माझ्या इमेल पत्त्यावर पाठवा',
'tog-diffonly'                => 'निवडलेल्या आवृत्त्यांमधील बदल दाखवताना जुनी आवृत्ती दाखवू नका.',
'tog-showhiddencats'          => 'लपविलेले वर्ग दाखवा',
'tog-norollbackdiff'          => 'द्रुतमाघार घेतल्यास बदल वगळा',

'underline-always'  => 'नेहेमी',
'underline-never'   => 'कधीच नाही',
'underline-default' => 'न्याहाळक अविचल (browser default)',

# Font style option in Special:Preferences
'editfont-style'     => 'विभागाची टंकशैली संपादित करा:',
'editfont-default'   => 'न्याहाळक अविचल',
'editfont-monospace' => 'एकलअंतर असलेला टंक',
'editfont-sansserif' => 'सॅन्स-सेरिफ टंक',
'editfont-serif'     => 'सेरिफ टंक',

# Dates
'sunday'        => 'रविवार',
'monday'        => 'सोमवार',
'tuesday'       => 'मंगळवार',
'wednesday'     => 'बुधवार',
'thursday'      => 'गुरूवार',
'friday'        => 'शुक्रवार',
'saturday'      => 'शनिवार',
'sun'           => 'रवि',
'mon'           => 'सोम',
'tue'           => 'मंगळ',
'wed'           => 'बुध',
'thu'           => 'गुरू',
'fri'           => 'शुक्र',
'sat'           => 'शनि',
'january'       => 'जानेवारी',
'february'      => 'फेब्रुवारी',
'march'         => 'मार्च',
'april'         => 'एप्रिल',
'may_long'      => 'मे',
'june'          => 'जून',
'july'          => 'जुलै',
'august'        => 'ऑगस्ट',
'september'     => 'सप्टेंबर',
'october'       => 'ऑक्टोबर',
'november'      => 'नोव्हेंबर',
'december'      => 'डिसेंबर',
'january-gen'   => 'जानेवारी',
'february-gen'  => 'फेब्रुवारी',
'march-gen'     => 'मार्च',
'april-gen'     => 'एप्रिल',
'may-gen'       => 'मे',
'june-gen'      => 'जून',
'july-gen'      => 'जुलै',
'august-gen'    => 'ऑगस्ट',
'september-gen' => 'सप्टेंबर',
'october-gen'   => 'ऑक्टोबर',
'november-gen'  => 'नोव्हेंबर',
'december-gen'  => 'डिसेंबर',
'jan'           => 'जाने.',
'feb'           => 'फेब्रु.',
'mar'           => 'मार्च',
'apr'           => 'एप्रि.',
'may'           => 'मे',
'jun'           => 'जून',
'jul'           => 'जुलै',
'aug'           => 'ऑग.',
'sep'           => 'सप्टें.',
'oct'           => 'ऑक्टो.',
'nov'           => 'नोव्हें.',
'dec'           => 'डिसें.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|वर्ग|वर्ग}}',
'category_header'                => '"$1" वर्गातील लेख',
'subcategories'                  => 'उपवर्ग',
'category-media-header'          => '"$1" वर्गातील माध्यमे',
'category-empty'                 => "''या वर्गात अद्याप एकही लेख नाही.''",
'hidden-categories'              => '{{PLURAL:$1|लपविलेला वर्ग|लपविलेले वर्ग}}',
'hidden-category-category'       => 'लपविलेले वर्ग',
'category-subcat-count'          => '{{PLURAL:$2|या वर्गात फक्त खालील उपवर्ग आहे.|एकूण $2 उपवर्गांपैकी या वर्गात खालील {{PLURAL:$1|उपवर्ग आहे.|$1 उपवर्ग आहेत.}}}}',
'category-subcat-count-limited'  => 'या वर्गात खालील $1 उपवर्ग {{PLURAL:$1|आहे|आहेत}}.',
'category-article-count'         => '{{PLURAL:$2|या वर्गात फक्त खालील लेख आहे.|एकूण $2 पैकी खालील {{PLURAL:$1|पान|$1 पाने}} या वर्गात {{PLURAL:$1|आहे|आहेत}}.}}',
'category-article-count-limited' => 'खालील {{PLURAL:$1|पान|$1 पाने}} या वर्गात {{PLURAL:$1|आहे|आहेत}}.',
'category-file-count'            => '{{PLURAL:$2|या वर्गात फक्त खालील संचिका आहे.|एकूण $2 पैकी खालील {{PLURAL:$1|संचिका|$1 संचिका}} या वर्गात {{PLURAL:$1|आहे|आहेत}}.}}',
'category-file-count-limited'    => 'खालील {{PLURAL:$1|संचिका|$1 संचिका}} या वर्गात आहेत.',
'listingcontinuesabbrev'         => 'पुढे.',
'index-category'                 => 'अनुक्रमित पाने',
'noindex-category'               => 'अनुक्रम नसलेली पाने',
'broken-file-category'           => 'तुटलेल्या संचिका दुव्यांसह असलेली पाने',

'about'         => 'च्या विषयी',
'article'       => 'लेख',
'newwindow'     => '(नवीन खिडकीत उघडते.)',
'cancel'        => 'रद्द करा',
'moredotdotdot' => 'अजून...',
'mypage'        => 'माझे पृष्ठ',
'mytalk'        => 'माझ्या चर्चा',
'anontalk'      => 'या अंकपत्त्याचे चर्चा पान उघडा',
'navigation'    => 'सुचालन',
'and'           => '&#32;आणि',

# Cologne Blue skin
'qbfind'         => 'शोध',
'qbbrowse'       => 'न्याहाळा',
'qbedit'         => 'संपादन',
'qbpageoptions'  => 'हे पान',
'qbpageinfo'     => 'सामग्री',
'qbmyoptions'    => 'माझी पाने',
'qbspecialpages' => 'विशेष पृष्ठे',
'faq'            => 'नेहमीची प्रश्नावली',
'faqpage'        => 'Project:प्रश्नावली',

# Vector skin
'vector-action-addsection'       => 'विषय जोडा',
'vector-action-delete'           => 'वगळा',
'vector-action-move'             => 'स्थानांतरण',
'vector-action-protect'          => 'सुरक्षित करा',
'vector-action-undelete'         => 'पुनर्स्थापित करा',
'vector-action-unprotect'        => 'असुरक्षित करा',
'vector-simplesearch-preference' => 'प्रगत शोधविकल्प सक्रिय करा (फक्त व्हेक्टर त्वचेसाठी)',
'vector-view-create'             => 'तयार करा',
'vector-view-edit'               => 'संपादन',
'vector-view-history'            => 'इतिहास पहा',
'vector-view-view'               => 'वाचा',
'vector-view-viewsource'         => 'स्रोत पहा',
'actions'                        => 'क्रिया',
'namespaces'                     => 'नामविश्वे',
'variants'                       => 'अस्थिर',

'errorpagetitle'    => 'चूक',
'returnto'          => '$1 कडे परत चला.',
'tagline'           => '{{SITENAME}} कडून',
'help'              => 'सहाय्य',
'search'            => 'शोधा',
'searchbutton'      => 'शोधा',
'go'                => 'चला',
'searcharticle'     => 'लेख',
'history'           => 'जुन्या आवृत्ती',
'history_short'     => 'इतिहास',
'updatedmarker'     => 'शेवटच्या भेटीनंतर बदलले',
'printableversion'  => 'छापण्यायोग्य आवृत्ती',
'permalink'         => 'शाश्वत दुवा',
'print'             => 'छापा',
'view'              => 'दाखवा',
'edit'              => 'संपादन',
'create'            => 'तयार करा',
'editthispage'      => 'हे पृष्ठ संपादित करा',
'create-this-page'  => 'हे पान तयार करा',
'delete'            => 'वगळा',
'deletethispage'    => 'हे पृष्ठ काढून टाका',
'undelete_short'    => 'पुनर्स्थापन {{PLURAL:$1|एक संपादन|$1 संपादने}}',
'viewdeleted_short' => '{{PLURAL:$1|एक वगळलेले संपादन|$1 वगळलेली संपादने}} पहा.',
'protect'           => 'सुरक्षित करा',
'protect_change'    => 'बदला',
'protectthispage'   => 'हे पृष्ठ सुरक्षित करा',
'unprotect'         => 'असुरक्षित करा',
'unprotectthispage' => 'हे पृष्ठ असुरक्षित करा',
'newpage'           => 'नवीन पृष्ठ',
'talkpage'          => 'चर्चा पृष्ठ',
'talkpagelinktext'  => 'चर्चा',
'specialpage'       => 'विशेष पृष्ठ',
'personaltools'     => 'वैयक्‍तिक साधने',
'postcomment'       => 'नवीन चर्चा',
'articlepage'       => 'लेख पृष्ठ',
'talk'              => 'चर्चा',
'views'             => 'दृष्ये',
'toolbox'           => 'साधनपेटी',
'userpage'          => 'सदस्य पृष्ठ',
'projectpage'       => 'प्रकल्प पान पहा',
'imagepage'         => 'संचिका पृष्ठ पहा',
'mediawikipage'     => 'संदेश पान पहा',
'templatepage'      => 'साचा पृष्ठ पहा.',
'viewhelppage'      => 'साहाय्य पान पहा',
'categorypage'      => 'वर्ग पान पहा',
'viewtalkpage'      => 'चर्चा पृष्ठ पहा',
'otherlanguages'    => 'इतर भाषा',
'redirectedfrom'    => '($1 पासून पुनर्निर्देशित)',
'redirectpagesub'   => 'पुनर्निर्देशनाचे पान',
'lastmodifiedat'    => 'या पानातील शेवटचा बदल $1 रोजी $2 वाजता केला गेला.',
'viewcount'         => 'हे पान {{PLURAL:$1|एकदा|$1 वेळा}} बघितले गेलेले आहे.',
'protectedpage'     => 'सुरक्षित पृष्ठ',
'jumpto'            => 'येथे जा:',
'jumptonavigation'  => 'सुचालन',
'jumptosearch'      => 'शोध',
'view-pool-error'   => 'माफ करा. यावेळेस सर्व्हरवर ताण आहे. अनेक सदस्य हे पान बघण्याचा प्रयत्न करीत आहेत. पुन्हा या पानावर पोचण्यासाठी थोडा वेळ थांबून परत प्रयत्‍न करा.
$1',
'pool-timeout'      => 'ताळ्यासाठी वाट पाहताना वेळ संपली',
'pool-queuefull'    => 'सर्व्हरवर ताण आहे.',
'pool-errorunknown' => 'अपरिचित त्रूटी',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} बद्दल',
'aboutpage'            => 'Project:माहितीपृष्ठ',
'copyright'            => 'येथील मजकूर $1च्या अंतर्गत उपलब्ध आहे.',
'copyrightpage'        => '{{ns:project}}:प्रताधिकार',
'currentevents'        => 'सद्य घटना',
'currentevents-url'    => 'Project:सद्य घटना',
'disclaimers'          => 'उत्तरदायकत्वास नकार',
'disclaimerpage'       => 'Project: सर्वसाधारण उत्तरदायकत्वास नकार',
'edithelp'             => 'संपादन साहाय्य',
'edithelppage'         => 'Help:संपादन',
'helppage'             => 'Help:साहाय्य पृष्ठ',
'mainpage'             => 'मुखपृष्ठ',
'mainpage-description' => 'मुखपृष्ठ',
'policy-url'           => 'Project:नीती',
'portal'               => 'समाज मुखपृष्ठ',
'portal-url'           => 'Project:समाज मुखपृष्ठ',
'privacy'              => 'गुप्तता नीती',
'privacypage'          => 'Project:गुप्तता नीती',

'badaccess'        => 'परवानगी नाकारण्यात आली आहे',
'badaccess-group0' => 'तुम्ही करत असलेल्या क्रियेचे तुम्हाला अधिकार नाहीत.',
'badaccess-groups' => 'आपण विनीत केलेली कृती खालील {{PLURAL:$2|समूहासाठी|पैकी एका समूहासाठी}} मर्यादीत आहे: $1.',

'versionrequired'     => 'मीडियाविकीच्या $1 आवृत्तीची गरज आहे.',
'versionrequiredtext' => 'हे पान वापरण्यासाठी मीडियाविकीच्या $1 आवृत्तीची गरज आहे. पहा [[Special:Version|आवृत्ती यादी]].',

'ok'                      => 'ठीक',
'retrievedfrom'           => '"$1" पासून मिळविले',
'youhavenewmessages'      => 'तुमच्यासाठी $1 ($2).',
'newmessageslink'         => 'नवीन संदेश',
'newmessagesdifflink'     => 'ताजा बदल',
'youhavenewmessagesmulti' => '$1 वर तुमच्यासाठी नवीन संदेश आहेत.',
'editsection'             => 'संपादन',
'editold'                 => 'संपादन',
'viewsourceold'           => 'स्रोत पहा',
'editlink'                => 'संपादन',
'viewsourcelink'          => 'स्रोत पहा',
'editsectionhint'         => 'विभाग: $1 संपादित करा',
'toc'                     => 'अनुक्रमणिका',
'showtoc'                 => 'दाखवा',
'hidetoc'                 => 'लपवा',
'collapsible-collapse'    => 'लपवा',
'collapsible-expand'      => 'विस्तार',
'thisisdeleted'           => 'आवलोकन किंवा पूनर्स्थापन $1?',
'viewdeleted'             => 'आवलोकन $1?',
'restorelink'             => '{{PLURAL:$1|एक वगळलेले संपादन|$1 वगळलेली संपादने}}',
'feedlinks'               => 'रसद (Feed):',
'feed-invalid'            => 'अयोग्य रसद नोंदणी (Invalid subscription feed type).',
'feed-unavailable'        => 'सिंडीकेशन फीड उपलब्ध नाहीत',
'site-rss-feed'           => '$1 आरएसएस फीड',
'site-atom-feed'          => '$1 ऍटम रसद (Atom Feed)',
'page-rss-feed'           => '"$1" आर.एस.एस.रसद (RSS Feed)',
'page-atom-feed'          => '"$1" ऍटम रसद (Atom Feed)',
'feed-atom'               => 'ऍटम',
'feed-rss'                => 'आर.एस.ए‍स.',
'red-link-title'          => '$1 (पान अस्तित्त्वात नाही)',
'sort-descending'         => 'उतरत्या क्रमाने लावा',
'sort-ascending'          => 'चढत्या क्रमाने लावा',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'लेख',
'nstab-user'      => 'सदस्य पान',
'nstab-media'     => 'माध्यम पान',
'nstab-special'   => 'विशेष पृष्ठ',
'nstab-project'   => 'प्रकल्प पान',
'nstab-image'     => 'संचिका',
'nstab-mediawiki' => 'संदेश',
'nstab-template'  => 'साचा',
'nstab-help'      => 'साहाय्य पान',
'nstab-category'  => 'वर्ग',

# Main script and global functions
'nosuchaction'      => 'अशी कृती अस्तित्वात नाही',
'nosuchactiontext'  => 'URL ने सांगितलेली कृती चुकीची आहे.
तुम्ही कदाचित URL चुकीची दिली असेल, किंवा चुकीच्या दुव्यावर टिचकी दिली असेल.
कदाचित ही कृती {{SITENAME}} मधील त्रुटी सुद्धा दर्शवित असेल.',
'nosuchspecialpage' => 'असे कोणतेही विशेष पृष्ठ अस्तित्वात नाही',
'nospecialpagetext' => '<strong>आपण केलेली विनंती अयोग्य विशेषपानासंबंधी आहे.</strong>

योग्य विशेषपानांची यादी  [[Special:SpecialPages|{{int:specialpages}}]] येथे उपलब्ध होऊ शकते.',

# General errors
'error'                => 'त्रुटी',
'databaseerror'        => 'माहितीसंग्रहातील त्रुटी',
'dberrortext'          => 'एक विदा पृच्छारचना त्रूटी घडली आहे.
ही बाब संचेतनात (सॉफ्टवेअरमध्ये) क्षितिजन्तु असण्याची शक्यता निर्देशीत करते.
"<tt>$2</tt>" या कार्यातून निघालेली शेवटची विदापृच्छा पुढील प्रमाणे होती:
<blockquote><tt>$1</tt></blockquote>
मायएसक्युएलने "<tt>$3: $4</tt>" ही त्रूटी दिलेली आहे.',
'dberrortextcl'        => 'चुकीच्या प्रश्नलेखनामुळे माहितीसंग्रह त्रुटी.
शेवटची माहितीसंग्रहाला पाठविलेला प्रश्न होता:
"$1"
"$2" या कार्यकृतीमधून .
MySQL returned error "$3: $4".',
'laggedslavemode'      => 'सुचना: पानावर नवीन बदल नसतील.',
'readonly'             => 'विदागारास (database) ताळे आहे.',
'enterlockreason'      => 'विदागारास ताळे ठोकण्याचे कारण, ताळे उघडले जाण्याच्या अदमासे कालावधीसहीत द्या.',
'readonlytext'         => 'बहुधा विदागार मेंटेनन्सकरिता नवीन भर घालण्यापासून आणि इतर बदल करण्यापासून बंद ठेवण्यात आला आहे, मेंटेनन्सनंतर तो नियमीत होईल.

ताळे ठोकणार्‍या प्रबंधकांनी खालील कारण नमुद केले आहे: $1',
'missing-article'      => 'डाटाबेसला "$1" $2 नावाचे पान मिळालेले नाही, जे मिळायला हवे होते.

असे बहुदा संपुष्टात आलेल्या फरकामुळे किंवा वगळलेल्या पानाच्या इतिहास दुव्यामुळे घडते.

जर असे घडलेले नसेल, तर तुम्हाला प्रणाली मधील त्रुटी आढळलेली असू शकते.
कृपया याबद्दल एखाद्या [[Special:ListUsers/sysop|प्रचालकाशी]] चर्चा करा व या URLची नोंद करा.',
'missingarticle-rev'   => '(आवृत्ती#: $1)',
'missingarticle-diff'  => '(फरक: $1, $2)',
'readonly_lag'         => 'मुख्य विदागार दात्याच्या (master database server) बरोबरीने पोहचण्यास पराधीन-विदागारदात्यास (slave server) वेळ लागल्यामुळे, विदागार आपोआप बंद झाला आहे.',
'internalerror'        => 'अंतर्गत त्रूटी',
'internalerror_info'   => 'अंतर्गत त्रूटी: $1',
'fileappenderrorread'  => 'जोडणीच्या दरम्यान "$1" वाचता आले नाही.',
'fileappenderror'      => '"$1" ते "$2" जोडता आले नाही.',
'filecopyerror'        => '"$1" संचिकेची "$2" ही प्रत करता आली नाही.',
'filerenameerror'      => '"$1" संचिकेचे "$2" असे नामांतर करता आले नाही.',
'filedeleteerror'      => '"$1" संचिका वगळता आली नाही.',
'directorycreateerror' => '"$1" कार्यधारीका (directory) तयार केली जाऊ शकली नाही.',
'filenotfound'         => '"$1" ही संचिका सापडत नाही.',
'fileexistserror'      => 'संचिका "$1" वर लिहीता आले नाही: संचिका अस्तित्वात आहे.',
'unexpected'           => 'अनपेक्षित मूल्य: "$1"="$2"',
'formerror'            => 'त्रूटी: फॉर्म सबमीट करता आलेला नाही',
'badarticleerror'      => 'या पानावर ही कृती करता येत नाही.',
'cannotdelete'         => '$1 हे पान किंवा संचिका वगळता आलेली नाही. (आधीच इतर कुणी वगळले असण्याची शक्यता आहे.)',
'cannotdelete-title'   => '$1 ला वगळू शकत नाहि',
'badtitle'             => 'चुकीचे शीर्षक',
'badtitletext'         => 'आपण मागितलेले शीर्षक पान अयोग्य, रिकामे अथवा चूकीने जोडलेले आंतर-भाषिय किंवा आंतर-विकि शीर्षक आहे. त्यात एक किंवा अधिक शीर्षकअयोग्य चिन्हे आहेत.',
'perfcached'           => 'खालील माहिती सयीमध्ये(कॅशे) ठेवली आहे त्यामुळे ती नवीनतम नसावी.',
'perfcachedts'         => 'खालील माहिती सयीमध्ये(कॅशे) ठेवली आहे आणि शेवटी $1 ला बदलली होती.',
'querypage-no-updates' => 'सध्या या पाना करिता नवीसंस्करणे अनुपलब्ध केली आहेत.आत्ताच येथील विदा ताजा होणार नाही.',
'wrong_wfQuery_params' => 'wfQuery()साठी चुकीचे पॅरेमीटर्स दिलेले आहेत<br />
कार्य (function): $1<br />
पृच्छा (Query): $2',
'viewsource'           => 'स्रोत पहा',
'viewsource-title'     => '$1 चा उगम बघा',
'actionthrottled'      => 'कृती अवरूद्ध (throttle) केली',
'actionthrottledtext'  => 'आंतरजाल-चिखलणी विरोधी उपायाच्या दृष्टीने(anti-spam measure), ही कृती थोड्या कालावधीत असंख्यवेळा करण्यापासून तुम्हाला प्रतिबंधीत करण्यात आले आहे, आणि आपण या मर्यादेचे उल्लंघन केले आहे. कृपया थोड्या वेळाने पुन्हा प्रयत्न करा.',
'protectedpagetext'    => 'हे पान बदल होऊ नयेत म्हणुन सुरक्षित केले आहे.',
'viewsourcetext'       => 'तुम्ही या पानाचा स्रोत पाहू शकता व प्रत करू शकता:',
'viewyourtext'         => 'तुम्ही या पानाचे स्त्रोत पाहू शकता व प्रत करू शकता',
'protectedinterface'   => 'हे पान सॉफ्टवेअरला इंटरफेस लेखन पुरवते, म्हणून दुरूपयोग टाळण्यासाठी संरक्षित केलेले आहे.',
'editinginterface'     => "'''सावधान:''' तुम्ही संचेतनाचे(Software) संपर्कमाध्यम मजकुर असलेले पान संपादीत करित  आहात.या पानावरील बदल इतर उपयोगकर्त्यांच्या  उपयोगकर्ता-संपर्कमाध्यमाचे स्वरूप पालटवू शकते.भाषांतरणांकरिता कृपया मिडीयाविकि स्थानिकीकरण प्रकल्पाच्या [//translatewiki.net/wiki/Main_Page?setlang=mr बीटाविकि] सुविधेचा उपयोग करण्याबद्दल विचार करा.",
'sqlhidden'            => 'छूपी एस्क्यूएल पृच्छा (SQL query hidden)',
'cascadeprotected'     => 'हे पान संपादनांपासून सुरक्षित केले गेलेले आहे, कारण ते खालील {{PLURAL:$1|पानात|पानांमध्ये}} अंतर्भूत केलेले आहे, की जे पान/जी पाने शिडी पर्यायाने सुरक्षित आहेत:
$2',
'namespaceprotected'   => "'''$1''' नामविश्वातील पाने बदलण्याची आपणांस परवानगी नाही.",
'customcssprotected'   => 'या पानावर इतर सदस्याच्या व्यक्तिगत पसंती असल्यामुळे, तुम्हाला हे सीएसएस पान संपादीत करण्याची परवानगी नाही.',
'customjsprotected'    => 'या पानावर इतर सदस्याच्या व्यक्तिगत पसंती असल्यामुळे, तुम्हाला हे JavaScript पान संपादीत करण्याची परवानगी नाही.',
'ns-specialprotected'  => 'विशेष पाने संपादीत करता येत नाहीत.',
'titleprotected'       => "या शीर्षकाचे पान सदस्य [[User:$1|$1]]ने निर्मीत करण्यापासून सुरक्षित केलेले आहे.
''$2'' हे कारण नमूद केलेले आहे.",

# Virus scanner
'virus-badscanner'     => "चुकीचे कॉन्फिगरेशन: व्हायरस स्कॅनर अनोळखी: ''$1''",
'virus-scanfailed'     => 'स्कॅन पूर्ण झाले नाही (कोड $1)',
'virus-unknownscanner' => 'अनोळखी ऍन्टीव्हायरस:',

# Login and logout pages
'logouttext'                 => "'''तुम्ही आता अदाखल झाला(logout)आहात.'''

तुम्ही अनामिकपणे {{SITENAME}}चा उपयोग करत राहू शकता, किंवा त्याच अथवा वेगळ्या सदस्य नावाने [[Special:UserLogin| पुन्हा दाखल होऊ शकता]].
आपण स्वत:च्या न्याहाळकाची सय (cache) रिकामी करत नाही तो पर्यंत काही पाने आपण अजून दाखल आहात, असे नुसतेच दाखवत राहू शकतील.",
'welcomecreation'            => '== सुस्वागतम, $1! ==

तुमचे खाते उघडण्यात आले आहे.
आपल्या [[Special:Preferences|{{SITENAME}} पसंती]] बदलण्यास विसरू नका.',
'yourname'                   => 'तुमचे नाव',
'yourpassword'               => 'तुमचा परवलीचा शब्द',
'yourpasswordagain'          => 'तुमचा परवलीचा शब्द पुन्हा लिहा',
'remembermypassword'         => 'माझा परवलीचा शब्द पुढील खेपेसाठी लक्षात ठेवा (जास्तीतजास्त $1 {{PLURAL:$1|दिवसासाठी|दिवसांसाठी}})',
'securelogin-stick-https'    => 'प्रवेशानंतर एचटीटीपीएसच्या संपर्कात रहा',
'yourdomainname'             => 'तुमचे क्षेत्र (डॉमेन) :',
'externaldberror'            => 'विदागार ’खातरजमा’ (प्रमाणितीकरण) त्रूटी होती अथवा तुम्हाला तुमचे बाह्य खाते अद्यावत  करण्याची परवानगी नाही.',
'login'                      => 'प्रवेश करा',
'nav-login-createaccount'    => 'सदस्य प्रवेश',
'loginprompt'                => '{{SITENAME}}मध्ये दाखल होण्याकरिता  स्मृतिशेष ऊपलब्ध (Cookie enable)असणे आवश्यक आहे.',
'userlogin'                  => 'दाखल व्हा /सदस्य खाते उघडा',
'userloginnocreate'          => 'प्रवेश करा',
'logout'                     => 'बाहेर पडा',
'userlogout'                 => 'बाहेर पडा',
'notloggedin'                => 'प्रवेशाची नोंदणी झालेली नाही!',
'nologin'                    => "आपण सदस्यत्व घेतलेले नाही का? '''$1'''.",
'nologinlink'                => 'सदस्य खाते तयार करा',
'createaccount'              => 'नवीन खात्याची नोंदणी करा',
'gotaccount'                 => "जुने खाते आहे? '''$1'''.",
'gotaccountlink'             => 'प्रवेश करा',
'userlogin-resetlink'        => 'प्रवेश तपशील विसरला आसाल तर येथे टिचकी मारा.',
'createaccountmail'          => 'इमेल द्वारे',
'createaccountreason'        => 'कारण:',
'badretype'                  => 'आपला परवलीचा शब्द चुकीचा आहे.',
'userexists'                 => 'या नावाने सदस्याची नोंदणी झालेली आहे.
कृपया दुसरे सदस्य नाव निवडा.',
'loginerror'                 => 'आपल्या प्रवेश नोंदणीमध्ये चूक झाली आहे',
'createaccounterror'         => 'हे खाते तयार करता येउ शकले नाही:$1',
'nocookiesnew'               => 'सदस्य खाते उघडले ,पण तुम्ही खाते वापरून दाखल झालेले नाही आहात.{{SITENAME}} सदस्यांना दाखल करून घेताना त्यांच्या स्मृतीशेष (cookies) वापरते.तुम्ही स्मृतीशेष सुविधा अनुपलब्ध टेवली आहे.ती कृपया उपलब्ध करा,आणि नंतर तुमच्या नवीन सदस्य नावाने आणि परवलीने दाखल व्हा.',
'nocookieslogin'             => '{{SITENAME}} सदस्यांना दाखल करून घेताना त्यांच्या स्मृतीशेष (cookies) वापरते.तुम्ही स्मृतीशेष सुविधा अनुपलब्ध टेवली आहे.स्मृतीशेष सुविधा कृपया उपलब्ध करा,आणि दाखल होण्यासाठी पुन्हा प्रयत्न करा.',
'nocookiesfornew'            => 'हे सदस्य खाते अस्तित्वात नाही, त्यामुळे आम्ही त्याच्या स्रोताची खात्री करू शकलो नाही.
तुमचे स्मृतिशेष उपलब्ध असण्याची खात्री करा, किंवा थोड्या वेळाने हे पान पुन्हा पहा.',
'noname'                     => 'आपण नोंदणीसाठी सदस्याचे योग्य नाव लिहिले नाही.',
'loginsuccesstitle'          => 'आपल्या प्रवेशाची नोंदणी यशस्वीरित्या पूर्ण झाली',
'loginsuccess'               => "'''तुम्ही {{SITENAME}} वर \"\$1\" नावाने प्रवेश केला आहे.'''",
'nosuchuser'                 => '"$1" या नावाचा कोणताही सदस्य नाही.तुमचे शुद्धलेखन तपासा, किंवा [[Special:UserLogin/signup|नवीन खाते]] तयार करा.',
'nosuchusershort'            => '"$1" या नावाचा सदस्य नाही. लिहीताना आपली चूक तर नाही ना झाली?',
'nouserspecified'            => 'तुम्हाला सदस्यनाव नमुद करावे लागेल.',
'login-userblocked'          => 'या सदस्याचे खाते ’प्रतिबंधित’ आहे. त्यास प्रवेश करु देणे शक्य नाही.',
'wrongpassword'              => 'आपला परवलीचा शब्द चुकीचा आहे, पुन्हा एकदा प्रयत्न करा.',
'wrongpasswordempty'         => 'परवलीचा शब्द रिकामा आहे; परत प्रयत्न करा.',
'passwordtooshort'           => 'तुमचा परवलीचा शब्द जरूरीपेक्षा लहान आहे. यात कमीत कमी {{PLURAL:$1|१ अक्षर |$1 अक्षरे}} पाहिजेत.',
'password-name-match'        => 'आपला परवलीचा शब्द हा आपल्या सदस्यनावापेक्षा वेगळा हवा.',
'password-login-forbidden'   => 'या सदस्यनामाचा व परवलीच्या शब्दाचा वापर निषिद्ध आहे.',
'mailmypassword'             => 'परवलीचा नवीन शब्द इमेल पत्त्यावर पाठवा',
'passwordremindertitle'      => '{{SITENAME}}करिता नवा तात्पुरता परवलीचा शब्दांक.',
'passwordremindertext'       => 'कुणीतरी (कदाचित तुम्ही, अंकपत्ता $1 कडून) {{SITENAME}} करिता ’नवा परवलीचा शब्दांक पाठवावा’ अशी विनंती केली आहे ($4).
"$2" सदस्याकरिता परवलीचा शब्दांक "$3" झाला आहे.
तुम्ही आता प्रवेश करा व तुमचा परवलीचा शब्दांक बदला. तुमचा अस्थायी शब्दांक {{PLURAL:$5|एका दिवसात|$5 दिवसांत}} संपेल.

जर ही विनंती इतर कुणी केली असेल किंवा तुम्हाला तुमचा परवलीचा शब्दांक आठवला असेल आणि तुम्ही तो आता बदलू इच्छित नसाल तर, तुम्ही हा संदेश दुर्लक्षित करून जुना परवलीचा शब्दांक वापरत राहू शकता.',
'noemail'                    => '"$1" सदस्यासाठी कोणताही इमेल पत्ता दिलेला नाही.',
'noemailcreate'              => 'आपण वैध विरोप-पत्ता (ई-मेल ऍड्रेस) देणे आवश्यक आहे.',
'passwordsent'               => '"$1" सदस्याच्या इमेल पत्त्यावर परवलीचा नवीन शब्द पाठविण्यात आलेला आहे.
तो शब्द वापरुन पुन्हा प्रवेश करा.',
'blocked-mailpassword'       => 'संपादनापासून तुमच्या अंकपत्त्यास आडविण्यात आले आहे,आणि म्हणून दुरूपयोग टाळ्ण्याच्या दृष्टीने परवलीचाशब्द परत मिळवण्यास सुद्धा मान्यता उपलब्ध नाही.',
'eauthentsent'               => 'नामांकित ई-मेल पत्त्यावर एक निश्चितता स्वीकारक ई-मेल पाठविला गेला आहे.
खात्यावर कोणताही इतर ई-मेल पाठविण्यापूर्वी - तो ई-मेल पत्ता तुमचाच आहे, हे सूनिश्चित करण्यासाठी - तुम्हाला त्या ई-मेल मधील सूचनांचे पालन करावे लागेल.',
'throttled-mailpassword'     => 'मागील {{PLURAL:$1|एका तासामध्ये|$1 तासांमध्ये}} परवलीचा शब्द बदलण्यासाठीची सूचना पाठविलेली आहे. दुरुपयोग टाळण्यासाठी {{PLURAL:$1|एका तासामध्ये|$1 तासांमध्ये}} फक्त एकदाच सूचना दिली जाईल.',
'mailerror'                  => 'विपत्र पाठवण्यात त्रूटी: $1',
'acct_creation_throttle_hit' => 'माफ करा, तुम्ही आत्तापर्यंत {{PLURAL:$1|१ खाते उघडले आहे|$1 खाती उघडली आहेत}}. तुम्हाला आणखी खाती उघडता येणार नाहीत.',
'emailauthenticated'         => 'तुमचा विपत्रपत्ता $3 येथे $2 यावेळी तपासण्यात आला आहे.',
'emailnotauthenticated'      => 'तुमचा इमेल पत्ता तपासलेला नाही. खालील कार्यांकरिता इमेल पाठविला जाणार नाही.',
'noemailprefs'               => 'खालील सुविधा कार्यान्वित करण्यासाठी इ-मेल पत्ता पुरवा.',
'emailconfirmlink'           => 'आपला इमेल पत्ता तपासून पहा.',
'invalidemailaddress'        => 'तुम्ही दिलेला इमेल पत्ता चुकीचा आहे, कारण तो योग्यप्रकारे लिहिलेला नाही. कृपया योग्यप्रकारे इमेल पत्ता लिहा अथवा ती जागा मोकळी सोडा.',
'cannotchangeemail'          => 'या विकीवर खात्याचा ईमेल बदलता येत नाही',
'accountcreated'             => 'खाते उघडले.',
'accountcreatedtext'         => '$1 चे सदस्यखाते उघडले.',
'createaccount-title'        => '{{SITENAME}} साठीची सदस्य नोंदणी',
'createaccount-text'         => 'तुमच्या विपत्र पत्त्याकरिता {{SITENAME}} ($4)वर "$2" नावाच्या कुणी "$3" परवलीने खाते उघडले आहे. कृपया आपण सदस्य प्रवेश करून आपला परवलीचा शब्द बदलावा.

जर ही नोंदणी चुकीने झाली असेल तर तुम्ही या संदेशाकडे दुर्लक्ष करू शकता.',
'usernamehasherror'          => 'सदस्यनामात "हॅश" वर्ण असू शकत नाहीत.',
'login-throttled'            => 'तुम्ही प्रवेश करण्यासाठी खूप प्रयत्‍न केले आहेत.
कृपया पुन्हा प्रयत्‍न करण्याआधी थांबा',
'login-abort-generic'        => 'तुमचा प्रवेश अयशस्वी होऊन रद्द झाला.',
'loginlanguagelabel'         => 'भाषा: $1',
'suspicious-userlogout'      => 'तुमचे अदाखल होणे प्रतिबंधित झाले कारण असे दिसते की ते तुटलेल्या न्याहाळकाद्वारे पाठवले गेले.',

# E-mail sending
'php-mail-error-unknown' => 'पीएचपीच्या विपत्र() पर्यायात अज्ञात चूक',
'user-mail-no-addy'      => 'ईमेल पत्त्या विना ईमेल पाठवण्यचा प्रयत्न केला',

# Change password dialog
'resetpass'                 => 'परवलीचा शब्द बदला',
'resetpass_announce'        => 'तुम्ही इमेलमधून दिलेल्या तात्पुरत्या शब्दांकाने प्रवेश केलेला आहे. आपली सदस्य नोंदणी पूर्ण करण्यासाठी कृपया इथे नवीन परवलीचा शब्द द्या:',
'resetpass_text'            => '<!-- मजकूर इथे लिहा -->',
'resetpass_header'          => 'खात्याचा परवलीचा शब्द बदला',
'oldpassword'               => 'जुना परवलीचा शब्दः',
'newpassword'               => 'नवीन परवलीचा शब्द:',
'retypenew'                 => 'पुन्हा एकदा परवलीचा शब्द',
'resetpass_submit'          => 'परवलीचा शब्द टाका आणि प्रवेश करा',
'resetpass_success'         => 'तुमचा परवलीचा शब्द बदललेला आहे! आता तुमचा प्रवेश करीत आहोत...',
'resetpass_forbidden'       => 'परवलीचा शब्द बदलता येत नाही.',
'resetpass-no-info'         => 'या पानामध्ये थेट जाण्यासाठी तुम्हाला प्रवेश घ्यावा लागेल.',
'resetpass-submit-loggedin' => 'परवलीचा शब्द बदला',
'resetpass-submit-cancel'   => 'रद्द करा',
'resetpass-wrong-oldpass'   => 'अवैध किंवा अस्थायी परवलीचा शब्द.
कदाचित तुम्ही आधीच तो यशस्वीरीत्या बदलला असेल किंवा नवीन तात्पुरता परवलीचा शब्द मागवला असेल.',
'resetpass-temp-password'   => 'तात्पुरता परवलीचा शब्द',

# Special:PasswordReset
'passwordreset'                    => 'परवलीचा शब्द पूर्ववत करा',
'passwordreset-text'               => 'तुमच्या खात्याच्या माहितीसंदर्भात विपत्राद्वारे अनुस्मारक येण्यासाठी हा अर्ज पूर्ण भरा.',
'passwordreset-legend'             => 'परवलीचा शब्द पूर्ववत करा',
'passwordreset-disabled'           => 'या विकीवर परवलीचा शब्द पुनर्स्थापित करता येत नाही.',
'passwordreset-pretext'            => '{{PLURAL:$1||खालील माहितीच्या भागांपैकी एक भाग लिहा}}',
'passwordreset-username'           => 'सदस्यनाव:',
'passwordreset-domain'             => 'डोमेन',
'passwordreset-capture'            => 'ईमेल कशी असेल ते बघायचेय ?',
'passwordreset-capture-help'       => 'या चौकटित खूण केली तर, ईमेल (तात्पुर्त्या परवली शब्दा सोबत) दखवण्यत व प्रयोगकर्त्त्यस पाठवण्यत येइल',
'passwordreset-email'              => 'विपत्र पत्ता',
'passwordreset-emailtitle'         => '{{SITENAME}} वर खात्याची माहिती',
'passwordreset-emailtext-ip'       => 'कुणीतरी (कदाचित तुम्ही, अंकपत्ता $1 कडून) {{SITENAME}} करिता ’नवा परवलीचा शब्दांक पाठवावा’ अशी विनंती केली आहे ($4).
"$2" सदस्याकरिता परवलीचा शब्दांक "$3" झाला आहे.
तुम्ही आता प्रवेश करा व तुमचा परवलीचा शब्दांक बदला. तुमचा अस्थायी शब्दांक {{PLURAL:$5|एका दिवसात|$5 दिवसांत}} संपेल.

जर ही विनंती इतर कुणी केली असेल किंवा तुम्हाला तुमचा परवलीचा शब्दांक आठवला असेल आणि तुम्ही तो आता बदलू इच्छित नसाल तर, तुम्ही हा संदेश दुर्लक्षित करून जुना परवलीचा शब्दांक वापरत राहू शकता.',
'passwordreset-emailtext-user'     => 'कुणीतरी (कदाचित तुम्ही, सदस्य $1 कडून) {{SITENAME}} करिता ’नवा परवलीचा शब्दांक पाठवावा’ अशी विनंती केली आहे ($4).
"$2" सदस्याकरिता परवलीचा शब्दांक "$3" झाला आहे.
तुम्ही आता प्रवेश करा व तुमचा परवलीचा शब्दांक बदला. तुमचा अस्थायी शब्दांक {{PLURAL:$5|एका दिवसात|$5 दिवसांत}} संपेल.

जर ही विनंती इतर कुणी केली असेल किंवा तुम्हाला तुमचा परवलीचा शब्दांक आठवला असेल आणि तुम्ही तो आता बदलू इच्छित नसाल तर, तुम्ही हा संदेश दुर्लक्षित करून जुना परवलीचा शब्दांक वापरत राहू शकता.',
'passwordreset-emailelement'       => 'सदस्यनाव: $1
अस्थायी परवलीचा शब्द: $2',
'passwordreset-emailsent'          => 'आठवणीसाठी एक विपत्र पाठवण्यात आले आहे.',
'passwordreset-emailsent-capture'  => 'खाली दाखवल्यानुसार आठवणीकरता ईमेल पाठवला आहे',
'passwordreset-emailerror-capture' => 'आठवणीकरता खाली दाखवल्यानुसार ईमेल तयार केला होता, पण प्रयोगकरता $1 ला पाठवता आला नाही',

# Special:ChangeEmail
'changeemail'          => 'इमेल पत्ता बदला',
'changeemail-header'   => 'अपल्या खात्याचा ईमेल पत्ता बदला.',
'changeemail-text'     => 'आपला ई-मेल पत्त बदलण्यासाठी हा फोर्म भरा. या बदलाची पुष्टी करण्यासाठी तुम्हाला तुमचा परवलीचा शब्द द्यावा लगेल.',
'changeemail-no-info'  => 'हे पान थेट बघण्यासठी तुम्हाला प्रवेश करावा लगेल.',
'changeemail-oldemail' => 'सध्याचा ईमेल पत्ता :',
'changeemail-newemail' => 'नवा ईमेल पत्ता:',
'changeemail-none'     => '(दिलेला नाही)',
'changeemail-submit'   => 'ईमेल बदला',
'changeemail-cancel'   => 'रद्द करा',

# Edit page toolbar
'bold_sample'     => 'ठळक मजकूर',
'bold_tip'        => 'ठळक',
'italic_sample'   => 'तिरकी अक्षरे',
'italic_tip'      => 'तिरकी अक्षरे',
'link_sample'     => 'दुव्याचे शीर्षक',
'link_tip'        => 'अंतर्गत दुवा',
'extlink_sample'  => 'http://www.example.com दुव्याचे शीर्षक',
'extlink_tip'     => 'बाह्य दुव्यात (http:// हा उपसर्ग विसरू नका)',
'headline_sample' => 'अग्रशीर्ष मजकुर',
'headline_tip'    => 'द्वितीय-स्तर अग्रशीर्ष',
'nowiki_sample'   => 'मजकूर इथे लिहा',
'nowiki_tip'      => 'विकिभाषेप्रमाणे बदल करू नका',
'image_tip'       => 'संलग्न संचिका',
'media_tip'       => 'संचिकेचा दुवा',
'sig_tip'         => 'वेळेबरोबर तुमची सही',
'hr_tip'          => 'आडवी रेषा (कमी वापरा)',

# Edit pages
'summary'                          => 'बदलांचा आढावा :',
'subject'                          => 'विषय:',
'minoredit'                        => 'हा एक छोटा बदल आहे',
'watchthis'                        => 'या लेखावर लक्ष ठेवा',
'savearticle'                      => 'हा लेख साठवून ठेवा',
'preview'                          => 'झलक',
'showpreview'                      => 'झलक पहा',
'showlivepreview'                  => 'थेट झलक',
'showdiff'                         => 'बदल दाखवा',
'anoneditwarning'                  => "'''सावधानः''' तुम्ही विकिपीडियाचे सदस्य म्हणून प्रवेश (लॉग-इन) केलेला नाही. या पानाच्या संपादन इतिहासात तुमचा आय.पी. ऍड्रेस नोंदला जाईल.",
'anonpreviewwarning'               => "\"'''सावधान:''' तुम्ही विकिपीडियाचे सदस्य म्हणून प्रवेश (लॉग-इन) केलेला नाही. या पानाच्या संपादन इतिहासात तुमचा आय.पी. अंकपत्ता (अ‍ॅड्रेस) नोंदला जाईल.\"",
'missingsummary'                   => "'''आठवण:''' तूम्ही संपादन सारांश पुरवलेला नाही.आपण जतन करा वर पुन्हा टीचकी मारली तर तेत्या शिवाय जतन होईल.",
'missingcommenttext'               => 'कृपया खाली प्रतिक्रीया भरा.',
'missingcommentheader'             => "'''आठवण:''' आपण या लेखनाकरिता विषय किंवा अधोरेषा दिलेली नाही. आपण पुन्हा जतन करा अशी सूचना केली तर, तुमचे संपादन त्याशिवायच जतन होईल.",
'summary-preview'                  => 'आढाव्याची झलक:',
'subject-preview'                  => 'विषय/मथळा झलक:',
'blockedtitle'                     => 'या सदस्यासाठी प्रवेश नाकारण्यात आलेला आहे.',
'blockedtext'                      => "'''तुमचे सदस्यनाव अथवा IP पत्ता ब्लॉक केलेला आहे.'''

हा ब्लॉक $1 यांनी केलेला आहे.
यासाठी ''$2'' हे कारण दिलेले आहे.

* ब्लॉकची सुरूवात: $8
* ब्लॉकचा शेवट: $6
* कुणाला ब्लॉक करायचे आहे: $7

तुम्ही ह्या ब्लॉक संदर्भातील चर्चेसाठी $1 अथवा [[{{MediaWiki:Grouppage-sysop}}|प्रबंधकांशी]] संपर्क करू शकता.
तुम्ही जोवर वैध इमेल पत्ता आपल्या [[Special:Preferences|माझ्या पसंती]] पानावर देत नाही तोवर तुम्ही ’सदस्याला इमेल पाठवा’ हा दुवा वापरू शकत नाही. तसेच असे करण्यापासून आपल्याला ब्लॉक केलेले नाही.
तुमचा सध्याचा IP पत्ता $3 हा आहे, व तुमचा ब्लॉक क्रमांक #$5 हा आहे.
कृपया या संदर्भातील चर्चेमध्ये यापैकी काहीही उद्घृत करा.",
'autoblockedtext'                  => 'तुमचा आंतरजालीय अंकपत्ता आपोआप स्थगित केला आहे कारण तो इतर अशा सदस्याने वापरलाकी, ज्याला $1ने प्रतिबंधित केले.
आणि दिलेले कारण खालील प्रमाणे आहे
:\'\'$2\'\'

* स्थगन तारीख: $8
* स्थगिती संपते: $6

तुम्ही $1शी संपर्क करू शकता किंवा इतर [[{{MediaWiki:Grouppage-sysop}}|प्रबंधकां पैकी]] एकाशी स्थगनाबद्दल चर्चा करू शकता.

[[Special:Preferences|सदस्य पसंतीत]]त शाबीत विपत्र पत्ता नमुद असल्या शिवाय आणि तुम्हाला  तो वापरण्या पासून प्रतिबंधीत केले असल्यास तुम्ही  "या सदस्यास विपत्र पाठवा" सुविधा  वापरू शकणार नाही.

तुमचा स्थगन क्र $5 आहे. कृपया तूमच्या कोणत्याही शंकासमाधाना साठी हा क्रंमांक नमुद करा.',
'blockednoreason'                  => 'कारण दिलेले नाही',
'whitelistedittext'                => 'लेखांचे संपादन करण्यासाठी आधी $1 करा.',
'confirmedittext'                  => 'तुम्ही संपादने करण्यापुर्वी तुमचा विपत्र पत्ता प्रमाणित करणे आवश्यक आहे.Please set and validate तुमचा विपत्र पत्ता तुमच्या[[Special:Preferences|सदस्य पसंती]]तून लिहा व सिद्ध करा.',
'nosuchsectiontitle'               => 'असा विभाग नाही.',
'nosuchsectiontext'                => 'तुम्ही अस्तिवात नसलेला विभाग संपादन करण्याचा प्रयत्न केला आहे.',
'loginreqtitle'                    => 'प्रवेश गरजेचा आहे',
'loginreqlink'                     => 'प्रवेश करा',
'loginreqpagetext'                 => 'तुम्ही इतर पाने पहाण्याकरिता $1 केलेच पाहिजे.',
'accmailtitle'                     => 'परवलीचा शब्द पाठविण्यात आलेला आहे.',
'accmailtext'                      => '[[User talk:$1|$1]] यांसाठी अविशिष्टपनॆ निर्मित केलेला परवलीचा शब्द $2 यांना पाठवण्यात आला आहे.

या नवीन खात्यासाठीचा परवलीचा शब्द प्रवेश घेतल्यावर [[Special:ChangePassword|येथे]] बदलता येईल.',
'newarticle'                       => '(नवीन लेख)',
'newarticletext'                   => 'तुम्हाला अपेक्षित असलेला लेख अजून लिहिला गेलेला नाही. हा लेख लिहिण्यासाठी खालील पेटीत मजकूर लिहा. मदतीसाठी [[{{MediaWiki:Helppage}}|येथे]] टिचकी द्या.

जर येथे चुकून आला असाल तर ब्राउझरच्या बॅक (back) कळीवर टिचकी द्या.',
'anontalkpagetext'                 => "---- ''हे बोलपान अशा अज्ञात सदस्यासाठी आहे ज्यांनी खाते तयार केलेले नाही किंवा त्याचा वापर करत नाहीत. त्यांच्या ओळखीसाठी आम्ही आंतरजाल अंकपत्ता वापरतो आहोत. असा अंकपत्ता बर्‍याच लोकांचा एकच असू शकतो जर आपण अज्ञात सदस्य असाल आणि आपल्याला काही अप्रासंगिक संदेश मिळाला असेल तर कृपया [[Special:UserLogin| खाते तयार करा]] किंवा [[Special:UserLogin/signup|प्रवेश करा]] ज्यामुळे पुढे असे गैरसमज होणार नाहीत.''",
'noarticletext'                    => 'या लेखात सध्या काहीही मजकूर नाही.
तुम्ही विकिपिडीयावरील इतर लेखांमध्ये या [[Special:Search/{{PAGENAME}}|मथळ्याच्या शोध घेऊ शकता]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} इतर याद्या शोधा],
किंवा हा लेख [{{fullurl:{{FULLPAGENAME}}|action=edit}} लिहू शकता]</span>.',
'noarticletext-nopermission'       => 'या लेखात सध्या काहीही मजकूर नाही.
तुम्ही विकिपिडीयावरील इतर लेखांमध्ये या [[Special:Search/{{PAGENAME}}|मथळ्याच्या शोध घेऊ शकता]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} इतर याद्या शोधा],
किंवा हा लेख [{{fullurl:{{FULLPAGENAME}}|action=edit}} लिहू शकता]</span>.',
'userpage-userdoesnotexist'        => '"<nowiki>$1</nowiki>" सदस्य खाते नोंदीकॄत नाही.कृपया हे पान तुम्ही संपादीत किंवा नव्याने तयार करू इच्छिता का या बद्दल विचार करा.',
'userpage-userdoesnotexist-view'   => 'सदस्यखाते "$1"  हे नोंदलेले नाही.',
'blocked-notice-logextract'        => 'हा सदस्य सध्या प्रतिबंधित आहे.
सर्वांत नवीन प्रतिबंधन यादी खाली संदर्भासाठी दिली आहे:',
'clearyourcache'                   => "'''सूचना:''' जतन केल्यानंतर, बदल पहाण्याकरिता तुम्हाला तुमच्या विचरकाची सय टाळायला लागू शकते. '''मोझील्ला/फायरफॉक्स /सफारी:''' ''Reload''करताना ''Shift''दाबून ठेवा किंवा ''Ctrl-Shift-R'' दाबा

(ऍपल मॅक वर ''Cmd-shift-R'');'''IE:''' ''Refresh'' टिचकताना ''Ctrl'' दाबा,किंवा ''Ctrl-F5'' दाबा ; '''Konqueror:''': केवळ '''Reload''' टिचकवा,किवा ''F5'' दाबा; '''Opera'''उपयोगकर्त्यांना  ''Tools→Preferences'' मधील सय पूर्ण रिकामी करायला लागेल.",
'usercssyoucanpreview'             => "'''टीप:'''तुमचे नवे सीएसएस जतन करण्यापूर्वी 'झलक पहा' कळ वापरा.",
'userjsyoucanpreview'              => "'''टीप:''' तुमचा नवा जावास्क्रिप्ट जतन करण्यापूर्वी 'झलक पहा' कळ वापरा.",
'usercsspreview'                   => "'''तुम्ही तुमच्या सी.एस.एस.ची केवळ झलक पहात आहात, ती अजून जतन केलेली नाही हे लक्षात घ्या.'''",
'userjspreview'                    => "'''तुम्ही तुमची सदस्य जावास्क्रिप्ट तपासत आहात किंवा झलक पहात आहात ,ती अजून जतन केलेली नाही हे लक्षात घ्या!'''",
'sitecsspreview'                   => "'''तुम्ही तुमच्या सी.एस.एस.ची केवळ झलक पहात आहात, ती अजून जतन केलेली नाही हे लक्षात घ्या.'''",
'sitejspreview'                    => "'''तुम्ही तुमच्या जावास्क्रिप्टची केवळ झलक पहात आहात, ती अजून जतन केलेली नाही हे लक्षात घ्या.'''",
'userinvalidcssjstitle'            => "'''सावधान:''' \"\$1\" अशी त्वचा नाही.custom .css आणि .js पाने lowercase title वापरतात हे लक्षात घ्या, उदा. {{ns:user}}:Foo/vector.css या विरूद्ध {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(बदल झाला आहे.)',
'note'                             => "'''सूचना:'''",
'previewnote'                      => "'''लक्षात ठेवा की ही फक्त झलक आहे, बदल अजून सुरक्षित केलेले नाहीत.'''",
'previewconflict'                  => 'वरील संपादन क्षेत्रातील मजकूर जतन केल्यावर या झलकेप्रमाणे दिसेल.',
'session_fail_preview'             => "'''क्षमस्व! सत्र विदेच्या क्षयामुळे आम्ही तुमची संपादन प्रक्रीया पार पाडू शकलो नाही.कृपया पुन्हा प्रयत्न करा.जर एवढ्याने काम झाले नाही तर सदस्य खात्यातून बाहेर पडून पुन्हा प्रवेश करून पहा.'''",
'session_fail_preview_html'        => "'''क्षमस्व! सत्र विदेच्या क्षयामुळे आम्ही तुमची संपादन प्रक्रीया पार पाडू शकलो नाही.'''

''कारण {{SITENAME}}चे कच्चे HTML चालू ठेवले आहे, जावास्क्रिप्ट हल्ल्यांपासून बचाव व्हावा म्हणून झलक लपवली आहे.''

'''जर संपादनाचा हा सुयोग्य प्रयत्न असेल तर ,कॄपया पुन्हा प्रयत्न करा. जर एवढ्याने काम झाले नाही तर सदस्य खात्यातून बाहेर पडून पुन्हा प्रवेश करून पहा.'''",
'token_suffix_mismatch'            => "'''तुमचे संपादन रद्द करण्यात आलेले आहे कारण तुमच्या क्लायंटनी तुमच्या संपादनातील उद्गारवाचक चिन्हांमध्ये (punctuation) बदल केलेले आहेत.
पानातील मजकूर खराब होऊ नये यासाठी संपादन रद्द करण्यात आलेले आहे.
असे कदाचित तुम्ही अनामिक proxy वापरत असल्याने होऊ शकते.'''",
'edit_form_incomplete'             => '”’तुमच्या संपादनाचा काही भाग सर्व्हरपर्यंत पोचला नाही; तुमचे संपादन पूर्ण आहे का याची पुन्हा खात्री करा.',
'editing'                          => '$1 चे संपादन होत आहे.',
'editingsection'                   => '$1 (विभाग) संपादन',
'editingcomment'                   => '$1 चे संपादन (प्रतिक्रिया)',
'editconflict'                     => 'वादग्रस्त संपादन: $1',
'explainconflict'                  => "तुम्ही संपादनाला सुरूवात केल्यानंतर इतर कोणीतरी बदल केला आहे.
वरील पाठ्यभागामध्ये सध्या अस्तिवात असलेल्या पृष्ठातील पाठ्य आहे, तर तुमचे बदल खालील पाठ्यभागात दर्शविलेले आहेत.
तुम्हाला हे बदल सध्या अस्तिवात असणाऱ्या पाठ्यासोबत एकत्रित करावे लागतील.
'''केवळ''' वरील पाठ्यभागामध्ये असलेले पाठ्य साठविण्यात येईल जर तुम्ही \"{{int:savearticle}}\" ही कळ दाबली.",
'yourtext'                         => 'तुमचे पाठ्य',
'storedversion'                    => 'साठविलेली आवृत्ती',
'nonunicodebrowser'                => "'''सावधान: तुमचा विचरक यूनिकोड आधारीत नाही. ASCII नसलेली  अक्षरचिन्हे संपादन खिडकीत सोळाअंकी कूटसंकेत (हेक्झाडेसीमल कोड) स्वरूपात दिसण्याची, सुरक्षीतपणे संपादन करू देणारी,  पळवाट उपलब्ध आहे.'''",
'editingold'                       => "'''इशारा: तुम्ही मूळ पृष्ठाची एक कालबाह्य आवृत्ती संपादित करीत आहात.
जर आपण बदल साठवून ठेवण्यात आले तर या नंतरच्या सर्व आवृत्त्यांमधील साठविण्यात आलेले बदल नष्ठ होतील.'''",
'yourdiff'                         => 'फरक',
'copyrightwarning'                 => "{{SITENAME}} येथे केलेले कोणतेही लेखन $2 (अधिक माहितीसाठी $1 पहा) अंतर्गत मुक्त उद्घोषित केले आहे असे गृहित धरले जाईल याची कृपया नोंद घ्यावी. आपणास आपल्या लेखनाचे मुक्त संपादन आणि मुक्त वितरण होणे पसंत नसेल तर येथे संपादन करू नये.<br />
तुम्ही येथे लेखन करताना हे सुद्धा गृहित धरलेले असते की येथे केलेले लेखन तुमचे स्वतःचे आणि केवळ स्वतःच्या प्रताधिकार (कॉपीराईट) मालकीचे आहे किंवा प्रताधिकाराने गठीत न होणार्‍या सार्वजनिक ज्ञानक्षेत्रातून घेतले आहे किंवा तत्सम मुक्त स्रोतातून घेतले आहे. तुम्ही संपादन करताना तसे वचन देत आहात. '''प्रताधिकारयुक्त लेखन सुयोग्य परवानगीशिवाय मुळीच चढवू/भरू नये!'''",
'copyrightwarning2'                => "{{SITENAME}} येथे केलेले कोणतेही लेखन हे इतर संपादकांकरवी बदलले अथवा काढले जाऊ शकते. जर आपणास आपल्या लेखनाचे मुक्त संपादन होणे पसंत नसेल तर येथे संपादन करू नये.<br />
तुम्ही येथे लेखन करताना हे सुद्धा गृहित धरलेले असते की येथे केलेले लेखन तुमचे स्वतःचे आणि केवळ स्वतःच्या प्रताधिकार (कॉपीराईट) मालकीचे आहे किंवा प्रताधिकाराने गठीत न होणार्‍या सार्वजनिक ज्ञानक्षेत्रातून घेतले आहे किंवा तत्सम मुक्त स्रोतातून घेतले आहे. तुम्ही संपादन करताना तसे वचन देत आहात (अधिक माहितीसाठी $1 पहा). '''प्रताधिकारयुक्त लेखन सुयोग्य परवानगीशिवाय मुळीच चढवू/भरू नये!'''",
'longpageerror'                    => "'''त्रूटी:आपण दिलेला मजकुर जास्तीत जास्त शक्य $2  किलोबाईट पेक्षा अधिक लांबीचा $1 किलोबाईट आहे.तो जतन केला जाऊ शकत नाही.'''",
'readonlywarning'                  => "सावधान:विदागारास भरण-पोषणाकरिता ताळे ठोकले आहे,त्यामुळे सध्या तुम्ही तुमचे संपादन जतन करू शकत नाही.जर तुम्हाला हवे असेल तर नंतर उपयोग करण्याच्या दृष्टीने, तुम्ही मजकुर ’मजकुर संचिकेत’(टेक्स्ट फाईल मध्ये) कापून-चिटकवू शकता.'''
विदागारास ताळे ठोकलेल्या प्रचालकांनी $1 असे स्पष्टीकरणे दीले आहे",
'protectedpagewarning'             => "'''सूचना: हे सुरक्षित पान आहे. फक्त प्रचालक याच्यात बदल करु शकतात.'''",
'semiprotectedpagewarning'         => "'''सूचना:''' हे पान सुरक्षित आहे. फक्त नोंदणीकृत सदस्य याच्यात बदल करू शकतात.",
'cascadeprotectedwarning'          => "'''सावधान:''' हे पान निम्न-लिखीत शिडी-प्रतिबंधीत {{PLURAL:$1|पानात|पानात}} आंतरभूत असल्यामुळे,केवळ प्रचालक सुविधाप्राप्त सदस्यांनाच संपादन करता यावे असे ताळे त्यास ठोकलेले आहे :",
'titleprotectedwarning'            => "”’सावधान: फक्त काही सदस्यानांच [[Special:ListGroupRights|विशेष आधिकार]] तयार करता यावे म्हणून ह्या पानास ताळे आहे.'''",
'templatesused'                    => 'या झलकेमध्ये {{PLURAL:$1|वापरलेला साचा|वापरलेले साचे}}:',
'templatesusedpreview'             => 'या झलकेमध्ये {{PLURAL:$1|वापरलेला साचा|वापरलेले साचे}}:',
'templatesusedsection'             => 'या विभागामध्ये {{PLURAL:$1|वापरलेला साचा|वापरलेले साचे}}:',
'template-protected'               => '(सुरक्षित)',
'template-semiprotected'           => '(अर्ध-सुरक्षीत)',
'hiddencategories'                 => 'हे पान खालील {{PLURAL:$1|एका लपविलेल्या वर्गामध्ये|$1 लपविलेल्या वर्गांमध्ये}} आहे:',
'nocreatetitle'                    => 'पान निर्मीतीस मर्यादा',
'nocreatetext'                     => '{{SITENAME}}वर नवीन लेख लिहिण्यास मज्जाव करण्यात आलेला आहे. आपण परत जाऊन अस्तित्वात असलेल्या लेखांचे संपादन करू शकता अथवा [[Special:UserLogin|नवीन सदस्यत्व घ्या/ प्रवेश करा]].',
'nocreate-loggedin'                => 'येथे तुम्हाला नवीन पाने बनवण्याची परवानगी नाही.',
'sectioneditnotsupported-title'    => 'विभाग संपादन समर्थित नाही.',
'sectioneditnotsupported-text'     => 'या लेखामध्ये विभाग संपादन समर्थित नाही.',
'permissionserrors'                => 'परवानगीतील त्रूटी',
'permissionserrorstext'            => 'खालील{{PLURAL:$1|कारणामुळे|कारणांमुळे}} तुम्हाला तसे करण्याची परवानगी नाही:',
'permissionserrorstext-withaction' => 'तुम्हाला $2 ची परवानगी नाही, खालील {{PLURAL:$1|कारणासाठी|कारणांसाठी}}:',
'recreate-moveddeleted-warn'       => "'''सूचना: पूर्वी वगळलेला लेख तुम्ही पुन्हा संपादित आहात.'''

कृपया तुम्ही करत असलेले संपादन योग्य असल्याची खात्री करा.
या लेखाची वगळल्याची नोंद तुमच्या संदर्भाकरीता पुढीलप्रमाणे:",
'moveddeleted-notice'              => 'हे पान वगळण्यात आलेले आहे.
खाली संदर्भासाठी वगळण्याची सूची दिलेली आहे.',
'log-fulllog'                      => 'पूर्ण यादी पहा.',
'edit-hook-aborted'                => 'हूकद्वारे संपादन रद्द.
कारण नाही.',
'edit-gone-missing'                => 'नविन पृष्ठ तयार करता आले नाही. पूर्वीपासून अस्तित्वात आहे.',
'edit-conflict'                    => 'वादग्रस्त संपादन',
'edit-no-change'                   => 'तुमचे संपादन दुर्लक्षित करण्यात आले आहे, कारण माहितीमध्ये काहीही बदल झालेला नाही.',
'edit-already-exists'              => 'नवीन पान तयार करता येऊ शकले नाही.
या नावाचे पान आधीच अस्तित्वात आहे.',

# Parser/template warnings
'expensive-parserfunction-warning'        => '”’इशारा:”’ या पानावर खूप सारे खर्चिक पृथक्करण क्रिया कॉल्स आहेत.

ते $2{{PLURAL:$2|कॉल|कॉल्स}} पेक्षा कमी असायला हवेत, सध्या $1{{PLURAL:$1| $1 कॉल| $1 कॉल्स}} एवढे आहेत.',
'expensive-parserfunction-category'       => 'खूप सारे खर्चिक पार्सर क्रिया कॉल्स असणारी पाने',
'post-expand-template-inclusion-warning'  => 'सूचना: साचे वाढविण्याची मर्यादा संपलेली आहे.
काही साचे वगळले जातील.',
'post-expand-template-inclusion-category' => 'अशी पाने ज्यांच्यावर साचे चढविण्याची मर्यादा संपलेली आहे',
'post-expand-template-argument-warning'   => 'सूचना: या पानावर असा एकतरी साचा आहे जो वाढविल्यास खूप मोठा होईल.
असे साचे वगळण्यात आलेले आहेत.',
'post-expand-template-argument-category'  => 'अशी पाने ज्यांच्यामध्ये साचे वगळलेले आहेत',
'parser-template-loop-warning'            => 'साचा चक्र मिळाले: [[$1]]',
'parser-template-recursion-depth-warning' => 'साचा पुनरावर्तन खोली मर्यादा ओलांडली ($1)',
'language-converter-depth-warning'        => 'भाषा रुपांतरण खोली मर्यादा ओलांडली ($1)',

# "Undo" feature
'undo-success' => 'संपादन परतवले जाऊ शकते.कृपया, आपण नेमके हेच करू इच्छीता हे खाली दिलेली तुलना पाहू निश्चीत करा,आणि नंतर संपादन परतवण्याचे काम पूर्ण करण्याकरिता इच्छीत बद्ल जतन करा.',
'undo-failure' => 'दरम्यान परस्पर विरोधी संपादने झाल्यामुळे आपण हे संपादन परतवू शकत नाही.',
'undo-norev'   => 'हे संपादन परतविता आलेले नाही कारण ते अगोदरच उलटविलेले किंवा वगळलेले आहे.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|चर्चा]])यांची आवृत्ती $1 परतवली.',

# Account creation failure
'cantcreateaccounttitle' => 'खाते उघडू शकत नाही',
'cantcreateaccount-text' => "('''$1''')या आंतरजाल अंकपत्त्याकडूनच्या खाते निर्मीतीस [[User:$3|$3]]ने अटकाव केला आहे.

$3ने ''$2'' कारण दिले आहे.",

# History pages
'viewpagelogs'           => 'या पानाच्या नोंदी पहा',
'nohistory'              => 'या पृष्ठासाठी आवृत्ती इतिहास अस्तित्वात नाही.',
'currentrev'             => 'चालू आवृत्ती',
'currentrev-asof'        => '$1 ची सध्याची आवृत्ती',
'revisionasof'           => '$1 नुसारची आवृत्ती',
'revision-info'          => '$2ने $1चे आवर्तन',
'previousrevision'       => '←मागील आवृत्ती',
'nextrevision'           => 'पुढील आवृत्ती→',
'currentrevisionlink'    => 'आताची आवृत्ती',
'cur'                    => 'चालू',
'next'                   => 'पुढील',
'last'                   => 'मागील',
'page_first'             => 'प्रथम',
'page_last'              => 'अंतिम',
'histlegend'             => 'बदल निवडणे: जुन्या आवृत्तींमधील फरक पाहण्यासाठी रेडियो बॉक्स निवडा व एन्टर कळ दाबा अथवा खाली दिलेल्या कळीवर टिचकी द्या.<br />
लिजेंड: (चालू) = चालू आवृत्तीशी फरक,
(मागील) = पूर्वीच्या आवृत्तीशी फरक, छो = छोटा बदल',
'history-fieldset-title' => 'इतिहास विंचरण करा',
'history-show-deleted'   => 'फक्त काढून टाकलेले',
'histfirst'              => 'सर्वात जुने',
'histlast'               => 'सर्वात नवीन',
'historysize'            => '({{PLURAL:$1|1 बाइट|$1 बाइट}})',
'historyempty'           => '(रिकामे)',

# Revision feed
'history-feed-title'          => 'आवृत्ती इतिहास',
'history-feed-description'    => 'विकिवरील या पानाच्या आवृत्त्यांचा इतिहास',
'history-feed-item-nocomment' => '$2 इथले $1',
'history-feed-empty'          => 'विनंती केलेले पान अस्तित्वात नाही.

ते विकिवरून वगळले किंवा नाव बदललेले असण्याची शक्यता आहे.

संबधीत नव्या पानांकरिता [[Special:Search|विकिवर शोध घेण्याचा ]] प्रयत्न करा.',

# Revision deletion
'rev-deleted-comment'         => '(प्रतिक्रिया वगळली)',
'rev-deleted-user'            => '(सदस्य नाव वगळले)',
'rev-deleted-event'           => '(कार्य नोंद वगळली)',
'rev-deleted-user-contribs'   => '[सदस्यनाव / अंकपत्ता वगळला]',
'rev-deleted-text-permission' => "या पानाची आवृत्ती सार्वजनिक विदागारातून '''वगळण्यात आली आहे'''.

[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} वगळल्याच्या नोंदीत]निर्देश असण्याची शक्यता आहे",
'rev-deleted-text-unhide'     => "या पेज चे रिविषन '''काधुन ताकले आहे'''.
याचि महिति मीलेल [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} वगळलेल्या नों].
तुम्हि तरि पन [$1 पाहु शकता] जर तुम्ही इंक्चुक असाल तर चालु थेउ शकता.",
'rev-suppressed-text-unhide'  => "या पानाचि आवृत्ती '''वगळण्यात आली आहे'''.
हे तुम्हि बघु शकता; महिति हि तुम्हाला इथे सपदेल्[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} वगळलेल्या नों].
तुम्ही तरी सुद्धा [$1 view this revision] जर तुम्म्ही ईच्चुक असाल तर .",
'rev-deleted-text-view'       => "या पानाचि आवृत्ती '''वगळण्यात आली आहे'''.
हे तुम्हि बघु शकता; महिति हि तुम्हाला इथे सपदेल् [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} कधुन तकले आहे].",
'rev-suppressed-text-view'    => "या पानाचि आवृत्ती '''वगळण्यात आली आहे'''.
हे तुम्हि बघु शकता; महिति हि तुम्हाला इथे सपदेल [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} वगळलेल्या नोंदनित].",
'rev-deleted-no-diff'         => "या पानाची आवृत्ती '''वगळण्यात आली आहे'''.

[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} वगळल्याच्या नोंदीत]निर्देश असण्याची शक्यता आहे",
'rev-suppressed-no-diff'      => 'तुम्ही हा फरक पाहू शकत नाही कारण या आवृत्त्यांमधील एक आवृती ”’वगळण्यात आली आहे.”’',
'rev-deleted-unhide-diff'     => "या पेज चे रिविषन  '''रीक्त करन्यात आले आहे'''.
महिती एथे सुद्धा मीलु शकेल [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} रीक्क्त कर्न्यात आले आहे].
तुम्म्हि आत्ता सुद्धा [$1 फरक बघा] जर तुम्हि चलु थेउ ईच्चुक असाल तर.",
'rev-suppressed-unhide-diff'  => "या पेज चे रिविषन  '''रीक्त करन्यात आले आहे'''.
महिती एथे सुद्धा मीलु शकेल  [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} रीक्क्त कर्न्यात आले आहे].
तुम्म्हि तरी सुद्धा [$1 हा फरक ओलखा] जर तुम्हि चलु थेउ ईच्चुक असाल तर.",
'rev-deleted-diff-view'       => "या पेज चे रिविषन  '''रीक्त करन्यात आले आहे'''.
तुम्म्ही हा फरक बघु शकता ; माहिती यात मीलु शकेल [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} रिक्क्त केलेली महिती].",
'rev-suppressed-diff-view'    => "या पेज चे रिविषन  '''रीक्त करन्यात आले आहे'''.
तुम्म्ही हा फरक बघु शकता ; माहिती यात मीलु शकेल [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}}  जर तुम्हि चलु थेउ ईच्चुक असाल तर].",
'rev-delundel'                => 'दाखवा/लपवा',
'rev-showdeleted'             => 'दाखवा',
'revisiondelete'              => 'आवर्तने वगळा/पुनर्स्थापित करा',
'revdelete-nooldid-title'     => 'अपेक्षीत आवृत्ती दिलेली नाही',
'revdelete-nooldid-text'      => '!!आपण ही कृती करावयाची आवर्तने सूचीत केलेली नाहीत, दिलेले आवर्तन अस्तित्वात नाही, किंवा तुम्ही सध्याचे आवर्तन लपविण्याचा प्रयत्न करीत आहात.',
'revdelete-nologtype-title'   => 'कोणताही यादीप्रकार दिलेला नाही',
'revdelete-nologtype-text'    => 'ही क्रिया करण्यासाठी तुम्ही यादीप्रकार निवडला नाही.',
'revdelete-nologid-title'     => 'अवैध यादी प्रविष्टी',
'revdelete-nologid-text'      => 'तुम्ही हे कार्य होण्यासाठी निश्चित यादी प्रसंग निवडला नाही किंवा दिलेली प्रविष्टी अस्तित्वात नाही.',
'revdelete-no-file'           => 'दर्शिवलेली संचिका अस्तित्वात नाही.',
'revdelete-show-file-confirm' => 'तुम्ही "<nowiki>$1</nowiki>" या संचिकेचे $2 येथून $3 वेळी असलेले आवर्तन नक्की पहाणार आहात?',
'revdelete-show-file-submit'  => 'होय',
'revdelete-selected'          => "'''[[:$1]] {{PLURAL:$2|चे निवडलेले आवर्तन|ची निवडलेली आवर्तने}}:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|निवडलेली नोंदीकृत घटना|निवडलेल्या नोंदीकृत घटना}}:'''",
'revdelete-text'              => "'''वगळलेल्या नोंदी आणि घटना अजूनही पानाच्या इतिहासात आणि नोंदीत आढळेल,परंतु मजकुराचा भाग सार्वजनिक स्वरूपात उपलब्ध राहणार नाही.'''

अजून इतर  प्रतिबंध घातल्या शिवाय {{SITENAME}}चे इतर प्रबंधक झाकलेला मजकुर याच दुव्याने परतवू शकतील.",
'revdelete-confirm'           => 'कृपया याची खात्री करा की तुम्ही हे करत आहात, त्याचे परिणाम जाणत आहात, आणि ते [[{{MediaWiki:Policy-url}}|मीडियाविकीच्या नीती]]नुसार आहे का?',
'revdelete-suppress-text'     => "लपवण्याचा वापर '''फक्त''' पुढील गोष्टी असल्यासाठी होतो:
* अनुपयोगी माहिती
* अयोग्य व्यक्तिगत माहिती
*: ''गृहपत्ते, दूरध्वनी क्रमांक व सामाजिक सुरक्षा क्रमांक''",
'revdelete-legend'            => 'दृश्य बंधने निश्चित करा',
'revdelete-hide-text'         => 'आवर्तीत मजकुर लपवा',
'revdelete-hide-image'        => 'संचिका मजकुर लपवा',
'revdelete-hide-name'         => 'कृती आणि ध्येय लपवा',
'revdelete-hide-comment'      => 'संपादन प्रतिक्रीया लपवा',
'revdelete-hide-user'         => 'संपादकाचे सदस्यनाव/आंतरजाल अंकपत्ता लपवा',
'revdelete-hide-restricted'   => 'ही बंधने प्रबंधक तसेच इतरांनाही लागू करा तसेच व्यक्तिरेखेला ताळा ठोका.',
'revdelete-radio-same'        => '(कृपया बदलू नये)',
'revdelete-radio-set'         => 'होय',
'revdelete-radio-unset'       => 'नाही',
'revdelete-suppress'          => 'प्रबंधक तसेच इतरांपासून विदा लपवा',
'revdelete-unsuppress'        => 'पुर्नस्थापीत आवृत्तीवरील बंधने ऊठवा',
'revdelete-log'               => 'कारण:',
'revdelete-submit'            => 'निवडलेल्या {{PLURAL:$1|आवृत्तीला|आवृत्त्यांना}} लागू करा',
'revdelete-success'           => "'''आवर्तनांची दृश्यता यशस्वी पणे लाविली.'''",
'revdelete-failure'           => "'''आवर्तन दृश्यता अद्ययावत करता येत नाही:'''
$1",
'logdelete-success'           => "'''घटनांची दृश्यता यशस्वी पणे लाविली.'''",
'logdelete-failure'           => "'''यादी दृश्यता ठरवता आली नाही:'''
$1",
'revdel-restore'              => 'दृश्यता बदला',
'revdel-restore-deleted'      => 'वगळलेल्या आवृत्त्या',
'revdel-restore-visible'      => 'दृष्य आवर्तने',
'pagehist'                    => 'पानाचा इतिहास',
'deletedhist'                 => 'वगळलेला इतिहास',
'revdelete-hide-current'      => '$1 मधील $2 या वेळचे आवर्तन लपवता येत नाही, : ते सद्य पुनरावर्तन आहे.
ते लपवता येत नाही.',
'revdelete-show-no-access'    => '$2, $1 ची वस्तू दाखवताना अडचण: ती "प्रतिबंधित" खूण असलेली आहे.
तुम्ही तिच्यापर्यंत पोचू शकत नाही.',
'revdelete-modify-no-access'  => '$2, $1 ची वस्तू संपादताना अडचण: ती "प्रतिबंधित" खूण असलेली आहे.
तुम्ही तिच्यापर्यंत पोचू शकत नाही.',
'revdelete-modify-missing'    => 'वस्तू क्र. $1 ला संपादताना त्रुटी: ती माहितीकोषात नाही!',
'revdelete-no-change'         => "'''सूचना:''' $2, $1 च्या वस्तूने अगोदरच दृश्यता रुपरेषा मागितल्या आहेत.",
'revdelete-concurrent-change' => '$2, $1 ची वस्तू संपादताना चूक: तुम्ही तिला संपादताना दुसर्‍या व्यक्तिने वस्तूस संपादले असावे.
कृपया याद्या तपासा.',
'revdelete-only-restricted'   => '$2, $1 ची वस्तू लपवताना चूक: तुम्ही इतर दृश्यता पर्यायांना निवडल्याशिवाय प्रचालकांपासून वस्तू लपवू शकत नाही.',
'revdelete-reason-dropdown'   => '*सामान्य वगळण्याची कारणे
** प्रताधिकार उल्लंघन
** अयोग्य व्यक्तिगत माहिती
** अनुपयोगी माहिती',
'revdelete-otherreason'       => 'इतर / आणखी कारण:',
'revdelete-reasonotherlist'   => 'इतर कारणे',
'revdelete-edit-reasonlist'   => 'वगळण्याची कारणे संपादीत करा',
'revdelete-offender'          => 'आवर्तन निर्माता:',

# Suppression log
'suppressionlog'     => 'सप्रेशन नोंद',
'suppressionlogtext' => 'खालील यादी ही रिक्क्त आनी ब्लोक त्याचे प्रकार हे आड्मिनिस्ट्रेटर्स पासून चुपे असतात.
हे बघा [[Special:BlockList|IP block list]] सद्ध्या चालु असलेले  ओपरेश्नल बन्स आणी ब्लोच्क्स.',

# History merging
'mergehistory'                     => 'पान ईतिहासांचे एकत्रिकरण करा',
'mergehistory-header'              => 'हे पान एका स्रोत पानाचा इतिहास एखाद्या नविन पानात समाविष्ट करू देते.
हा बदल पानाचे ऐतिहासिक सातत्य राखेल याची दक्षता घ्या.',
'mergehistory-box'                 => 'दोन पानांची आवर्तने संमिलीत करा:',
'mergehistory-from'                => 'स्रोत पान:',
'mergehistory-into'                => 'लक्ष्य पान:',
'mergehistory-list'                => 'गोळाबेरीज करण्याजोगा संपादन इतिहास',
'mergehistory-merge'               => '[[:$1]]ची पूढील आवर्तने [[:$2]]मध्ये एकत्रित करता येतील.ठराविक वेळी अथवा तत्पूर्वी झालेल्या आवर्तनांचे एकत्रिकरण करण्याकरिता रेडीओ कळ स्तंभ वापरा.हा स्तंभ सुचालन दुवे वापरल्यास पूर्वपदावर येईल हे लक्षात घ्या.',
'mergehistory-go'                  => 'गोळाबेरीज करण्याजोगी संपादने दाखवा',
'mergehistory-submit'              => 'आवर्तने एकत्रित करा.',
'mergehistory-empty'               => 'कोणतेही आवर्तन एकत्रित करता येत नाही.',
'mergehistory-success'             => '[[:$1]] {{PLURAL:$3|चे|ची}} $3 {{PLURAL:$3|आवर्तन|आवर्तने}} [[:$2]] मध्ये यशस्वीरित्या एकत्रित केली.',
'mergehistory-fail'                => 'इतिहासाचे एकत्रिकरण कार्य करू शकत नाही आहे, कृपया पान आणि वेळ नियमावलीची पुर्नतपासणी करा.',
'mergehistory-no-source'           => 'स्रोत पान $1 अस्तित्वात नाही.',
'mergehistory-no-destination'      => 'लक्ष्य पान $1  अस्तित्वात नाही.',
'mergehistory-invalid-source'      => 'स्रोत पानाचे शीर्षक योग्य असणे आवश्यक आहे.',
'mergehistory-invalid-destination' => 'लक्ष्य पानाचे शीर्षक योग्य असणे आवश्यक आहे.',
'mergehistory-autocomment'         => '[[:$2]] मध्ये [[:$1]] एकत्रित केले',
'mergehistory-comment'             => '[[:$2]] मध्ये [[:$1]] एकत्रित केले: $3',
'mergehistory-same-destination'    => 'स्रोत व लक्ष्यपाने सारखीच असू शकत नाहीत',
'mergehistory-reason'              => 'कारण:',

# Merge log
'mergelog'           => 'नोंदी एकत्र करा',
'pagemerge-logentry' => '[[$2]]मध्ये[[$1]] समाविष्ट केले ($3पर्यंतची आवर्तने)',
'revertmerge'        => 'वेगवेगळे करा',
'mergelogpagetext'   => 'एकापानाचा इतिहास इतर पानात टाकून अगदी अलिकडे एकत्रित केलेली एकत्रिकरणे निम्न्दर्शीत सूचीत आहेत.',

# Diffs
'history-title'            => '"$1" चा संपादन इतिहास',
'difference'               => '(आवर्तनांमधील फरक)',
'difference-multipage'     => '(पानांमधील फरक)',
'lineno'                   => 'ओळ $1:',
'compareselectedversions'  => 'निवडलेल्या आवृत्त्यांमधील बदल पहा',
'showhideselectedversions' => 'निवडलेल्या आवृत्त्या दाखवा / लपवा',
'editundo'                 => 'उलटवा',
'diff-multi'               => '{{PLURAL:$2|सदस्याची|$2 सदस्यांच्या}} ({{PLURAL:$1|आवृत्ती|$1 आवृत्त्या}} दाखवल्या नाहीत)',
'diff-multi-manyusers'     => '{{PLURAL:$2|सदस्याची|$2 सदस्यांच्या}} ({{PLURAL:$1|आवृत्ती|$1 आवृत्त्या}} दाखवल्या नाहीत)',

# Search results
'searchresults'                    => 'शोध निकाल',
'searchresults-title'              => '"$1" साठीचे निकाल शोधा',
'searchresulttext'                 => '{{SITENAME}} वरील माहिती कशी शोधावी, याच्या माहिती करता पहा - [[{{MediaWiki:Helppage}}|{{SITENAME}} वर शोध कसा घ्यावा]].',
'searchsubtitle'                   => 'तुम्ही \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|"$1" ने सुरू होणारी सर्व पाने]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" ला जोडणारी सर्व पाने]]) याचा शोध घेत आहात.',
'searchsubtitleinvalid'            => "तुम्ही '''$1''' या शब्दाचा शोध घेत आहात.",
'toomanymatches'                   => 'खूप एकसारखी उत्तरे मिळाली, कृपया पृच्छा वेगळ्या तर्‍हेने करून पहा',
'titlematches'                     => 'पानाचे शीर्षक जुळते',
'notitlematches'                   => 'कोणत्याही पानाचे शीर्षक जुळत नाही',
'textmatches'                      => 'पानातील मजकुर जुळतो',
'notextmatches'                    => 'पानातील मजकुराशी जुळत नाही',
'prevn'                            => 'मागील {{PLURAL:$1|$1}}',
'nextn'                            => 'पुढील {{PLURAL:$1|$1}}',
'prevn-title'                      => 'मागील $1 {{PLURAL:$1|निकाल|निकाल}}',
'nextn-title'                      => 'पुढील $1 {{PLURAL:$1|निकाल|निकाल}}',
'shown-title'                      => '$1 {{PLURAL:$1|निकाल|निकाल}} निकाल प्रतिपान पहा',
'viewprevnext'                     => 'पहा ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'विकल्प शोधा',
'searchmenu-exists'                => "'''या विकीवर \"[[:\$1]]\" या नावाचे पान आहे.'''",
'searchmenu-new'                   => "'''या विकीवर \"[[:\$1]]\" हे पान तयार करा!'''",
'searchhelp-url'                   => 'Help:साहाय्य पृष्ठ',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|या उपसर्गानिशी असलेली पाने न्याहाळा]]',
'searchprofile-articles'           => 'संबंधित पाने',
'searchprofile-project'            => 'सहाय्य व प्रकल्प पाने',
'searchprofile-images'             => 'मल्टिमीडिया',
'searchprofile-everything'         => 'सगळे',
'searchprofile-advanced'           => 'प्रगत',
'searchprofile-articles-tooltip'   => '$1मध्ये शोधा',
'searchprofile-project-tooltip'    => '$1मध्ये शोधा',
'searchprofile-images-tooltip'     => 'संचिकांसाठी शोधा',
'searchprofile-everything-tooltip' => 'सर्व पाने शोधा (चर्चापानांसहित)',
'searchprofile-advanced-tooltip'   => 'निवडलेल्या नामविश्वांमध्ये शोधा:',
'search-result-size'               => '$1 ({{PLURAL:$2|१ शब्द|$2 शब्द}})',
'search-result-category-size'      => '{{PLURAL:$1|१ सदस्य|$1 सदस्य}} ({{PLURAL:$2|१ उपवर्ग|$2 उपउपवर्ग}}, {{PLURAL:$3|1 संचिका|$3 संचिका}})',
'search-result-score'              => 'जुळणी: $1%',
'search-redirect'                  => '(पुनर्निर्देशन $1)',
'search-section'                   => '(विभाग $1)',
'search-suggest'                   => 'तुम्हाला हेच म्हणायचे का: $1',
'search-interwiki-caption'         => 'इतर प्रकल्प',
'search-interwiki-default'         => '$1चे निकाल:',
'search-interwiki-more'            => '(आणखी)',
'search-mwsuggest-enabled'         => 'सजेशन्स सहित',
'search-mwsuggest-disabled'        => 'सजेशन्स नाहीत',
'search-relatedarticle'            => 'जवळील',
'mwsuggest-disable'                => 'AJAX सजेशन्स रद्द करा',
'searcheverything-enable'          => 'सर्वनामविश्वांमध्ये शोधा:',
'searchrelated'                    => 'जवळील',
'searchall'                        => 'सर्व',
'showingresults'                   => "#'''$2'''पासून {{PLURAL:$1|'''1'''पर्यंतचा निकाल|'''$1'''पर्यंतचे निकाल}} खाली दाखवले आहे.",
'showingresultsnum'                => "खाली दिलेले #'''$2'''पासून सुरू होणारे  {{PLURAL:$3|'''1''' निकाल|'''$3''' निकाल}}.",
'showingresultsheader'             => "'''$4''' साठी {{PLURAL:$5|'''$3'''पैकी '''$1''' निकाल|'''$3''' पैकी '''$1 - $2''' निकाल}}",
'nonefound'                        => "'''सूचना''':काही नामविश्वेच नेहमी शोधली जातात. सर्व नामविश्वे शोधण्याकरीता (चर्चा पाने, साचे, इ. सकट) कॄपया शोधशब्दांच्या आधी ''all:'' लावून पहा किंवा पाहिजे असलेले नामविश्व लिहा.",
'search-nonefound'                 => 'दिलेल्या वर्णनाशी जुळणारे निकाल नाहीत.',
'powersearch'                      => 'वाढीव शोध',
'powersearch-legend'               => 'वाढीव शोध',
'powersearch-ns'                   => 'नामविश्वांमध्ये शोधा:',
'powersearch-redir'                => 'पुनर्निर्देशने दाखवा',
'powersearch-field'                => 'साठी शोधा',
'powersearch-togglelabel'          => 'तपासा:',
'powersearch-toggleall'            => 'सर्व',
'powersearch-togglenone'           => 'काहीही नाही',
'search-external'                  => 'बाह्य शोध',
'searchdisabled'                   => '{{SITENAME}} शोध अनुपलब्ध केला आहे.तो पर्यंत गूगलवरून शोध घ्या.{{SITENAME}}च्या मजकुराची त्यांची सूचिबद्धता शिळी असण्याची शक्यता असु शकते हे लक्षात घ्या.',

# Quickbar
'qbsettings'                => 'शीघ्रपट',
'qbsettings-none'           => 'नाही',
'qbsettings-fixedleft'      => 'स्थिर डावे',
'qbsettings-fixedright'     => 'स्थिर ऊजवे',
'qbsettings-floatingleft'   => 'तरंगते डावे',
'qbsettings-floatingright'  => 'तरंगते ऊजवे',
'qbsettings-directionality' => 'तुमच्या भाशा ची पद्धत दिशात्मक असली पाहिजे.',

# Preferences page
'preferences'                   => 'माझ्या पसंती',
'mypreferences'                 => 'माझ्या पसंती',
'prefs-edits'                   => 'संपादनांची संख्या:',
'prefsnologin'                  => 'प्रवेश केलेला नाही',
'prefsnologintext'              => 'तुम्हाला सदस्य पसंती बदलण्यासाठी <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} प्रवेश]</span> करावा लागेल.',
'changepassword'                => 'परवलीचा शब्द बदला',
'prefs-skin'                    => 'त्वचा',
'skin-preview'                  => 'झलक',
'datedefault'                   => 'प्राथमिकता नाही',
'prefs-beta'                    => 'बीटा चेहेरेपट्टी',
'prefs-datetime'                => 'दिनांक आणि वेळ',
'prefs-labs'                    => 'प्रायोगिक वैशिष्ट्ये',
'prefs-personal'                => 'सदस्य व्यक्तिरेखा',
'prefs-rc'                      => 'अलीकडील बदल',
'prefs-watchlist'               => 'पहार्‍याची सूची',
'prefs-watchlist-days'          => 'पहार्‍याच्या सूचीत दिसणार्‍या दिवसांची संख्या:',
'prefs-watchlist-days-max'      => 'जास्तीत जास्त ७ दिवस.',
'prefs-watchlist-edits'         => 'वाढीव पहार्‍याच्या सूचीत दिसणार्‍या संपादनांची संख्या:',
'prefs-watchlist-edits-max'     => 'अधिकतम अंक:  १०००.',
'prefs-watchlist-token'         => 'पहार्‍याच्या सूचीचा बिल्ला:',
'prefs-misc'                    => 'इतर',
'prefs-resetpass'               => 'परवलीचा शब्द बदला.',
'prefs-changeemail'             => 'विपत्रपत्ता बदला',
'prefs-setemail'                => 'तुमचा इमेल पत्ता लिहा.',
'prefs-email'                   => 'विपत्र पर्याय',
'prefs-rendering'               => 'देखावा',
'saveprefs'                     => 'जतन करा',
'resetprefs'                    => 'न जतन केलेले बदल रद्द करा',
'restoreprefs'                  => 'सर्व डिफॉल्ट मांडणी पूर्ववत करा.',
'prefs-editing'                 => 'संपादन',
'prefs-edit-boxsize'            => 'संपादन खिडकीचा आकार',
'rows'                          => 'ओळी:',
'columns'                       => 'स्तंभ:',
'searchresultshead'             => 'शोध',
'resultsperpage'                => 'प्रति पान धडका:',
'stub-threshold'                => '<a href="#" class="stub">अंकुरीत दुव्यांच्या</a> रचनेची नांदी (बाईट्स):',
'stub-threshold-disabled'       => 'अक्षम केले',
'recentchangesdays'             => 'अलिकडील बदल मधील दाखवावयाचे दिवस:',
'recentchangesdays-max'         => 'जास्तीतजास्त $1 {{PLURAL:$1|दिवस|दिवस}}',
'recentchangescount'            => 'अलिकडील बदल, इतिहास व नोंद पानांमध्ये दाखवायाच्या संपादनांची संख्या:',
'prefs-help-recentchangescount' => 'यात नुकतेच झालेले बदल, पानांचे इतिहास व याद्या या गोष्टी असतात.',
'prefs-help-watchlist-token'    => 'या क्षेत्रत गुपित किल्लि प्रदान केल्यस तुमच्या निरीक्षणयादीसाठी एक आरएसएस फीड उत्पन्न होईल.
कोणीही ज्याला या क्षेत्रातिल किल्लि माहित असेल तुमची निरीक्षणयादी वाचू शकतो, त्यमुळे कोणतीही सुरक्षित किल्लि निवडा.
येथे एक यंत्रजनित किल्लि दिलेली आहे गरज असल्यस तुम्ही ती वपरु शकता: $1',
'savedprefs'                    => 'तुमच्या पसंती जतन केल्या आहेत.',
'timezonelegend'                => 'वेळक्षेत्र',
'localtime'                     => 'स्थानिक वेळ:',
'timezoneuseserverdefault'      => 'सर्व्हर मूलस्थिती वापरा ($1)',
'timezoneuseoffset'             => 'इतर (वेळेतील अंतर लिहा)',
'timezoneoffset'                => 'समासफरक¹:',
'servertime'                    => 'विदागारदात्याची वेळ',
'guesstimezone'                 => 'विचरकातून भरा',
'timezoneregion-africa'         => 'आफ्रिका',
'timezoneregion-america'        => 'अमेरिका',
'timezoneregion-antarctica'     => 'अँटार्क्टिका',
'timezoneregion-arctic'         => 'आर्क्टिक',
'timezoneregion-asia'           => 'आशिया',
'timezoneregion-atlantic'       => 'अटलांटिक महासागर',
'timezoneregion-australia'      => 'ऑस्ट्रेलिया',
'timezoneregion-europe'         => 'युरोप',
'timezoneregion-indian'         => 'हिंदी महासागर',
'timezoneregion-pacific'        => 'प्रशांत महासागर',
'allowemail'                    => 'इतर सदस्यांकडून माझ्या इमेल पत्त्यावर इमेल येण्यास मुभा द्या',
'prefs-searchoptions'           => 'शोध विकल्प',
'prefs-namespaces'              => 'नामविश्वे',
'defaultns'                     => 'या नामविश्वातील अविचल शोध :',
'default'                       => 'अविचल',
'prefs-files'                   => 'संचिका',
'prefs-custom-css'              => 'सीएसएस पद्धत बदला',
'prefs-custom-js'               => 'जावास्क्रिप्ट पद्धत बदला',
'prefs-common-css-js'           => 'मिळून वापरलेले सर्व त्वचांसाठींचे सीएसएस / जावास्क्रिप्ट:',
'prefs-reset-intro'             => 'आपन द्दीलेले सर्व प्रीफ्र्न्सेस् वपर्न्यासथि तुम्ही हे पेज् वापरू शकता.',
'prefs-emailconfirm-label'      => 'विपत्र निश्चितीकरण:',
'prefs-textboxsize'             => 'संपादन खिडकीचा आकार',
'youremail'                     => 'विपत्र:',
'username'                      => 'सदस्यनाम:',
'uid'                           => 'सदस्य खाते:',
'prefs-memberingroups'          => 'खालील {{PLURAL:$1|गटाचा|गटांचा}} सदस्य:',
'prefs-registration'            => 'नोंदणीची वेळ:',
'yourrealname'                  => 'तुमचे खरे नाव:',
'yourlanguage'                  => 'भाषा:',
'yourvariant'                   => 'भाषा वेगळे आशय:',
'prefs-help-variant'            => 'या विकीची पाने दाखवण्यासाठी तुमच्या पसंतीचे शुद्धलेखन',
'yournick'                      => 'आपले उपनाव (सहीसाठी)',
'prefs-help-signature'          => 'चर्चा पानावरील टिपणाखाली "<nowiki>~~~~</nowiki>" लिहावे म्हणजे त्याचे रूपांतर आपली सही व सही करण्याची वेळ यात होईल.',
'badsig'                        => 'अयोग्य कच्ची सही;HTML खूणा तपासा.',
'badsiglength'                  => 'तुमची स्वाक्षरी खूप लांब आहे.
टोपणनाव $1 {{PLURAL:$1|अक्षरापेक्षा|अक्षरांपेक्षा}} कमी लांबीचे हवे.',
'yourgender'                    => 'लिंग',
'gender-unknown'                => 'अज्ञात',
'gender-male'                   => 'पुरुष',
'gender-female'                 => 'स्त्री',
'prefs-help-gender'             => 'ऐच्छिक: याचा उपयोग लिंगानुसार संबोधन करण्यास होतो. ही माहिती सार्वजनिक असेल.',
'email'                         => 'विपत्र',
'prefs-help-realname'           => 'तुमचे खरे नाव (वैकल्पिक): हे नाव दिल्यास आपले योगदान या नावाखाली नोंदले व दाखवले जाईल.',
'prefs-help-email'              => 'विपत्र (वैकल्पिक) :इतरांना सदस्य किंवा सदस्यचर्चा पानातून, तुमची ओळख देण्याची आवश्यकता न ठेवता, तुमच्याशी संपर्क सुविधा पुरवते.',
'prefs-help-email-others'       => 'इतरांना सदस्य किंवा सदस्य चर्चा पानातून, तुमची ओळख (इ मेल) देण्याची आवश्यकता न ठेवता, तुमच्याशी संपर्क साधता येऊ शकतो. तुमचा इ मेल गुप्त राहतो.',
'prefs-help-email-required'     => 'विपत्र(ईमेल)पत्ता  लागेल.',
'prefs-info'                    => 'मूलभूत माहिती',
'prefs-i18n'                    => 'आंतरराष्ट्रीयीकरण',
'prefs-signature'               => 'स्वाक्षरी',
'prefs-dateformat'              => 'तारीख रचना',
'prefs-timeoffset'              => 'वेळ बरोबरी',
'prefs-advancedediting'         => 'प्रगत पर्याय',
'prefs-advancedrc'              => 'प्रगत पर्याय',
'prefs-advancedrendering'       => 'प्रगत पर्याय',
'prefs-advancedsearchoptions'   => 'प्रगत पर्याय',
'prefs-advancedwatchlist'       => 'प्रगत पर्याय',
'prefs-displayrc'               => 'दर्शन पर्याय',
'prefs-displaysearchoptions'    => 'दर्शन पर्याय',
'prefs-displaywatchlist'        => 'दर्शन पर्याय',
'prefs-diffs'                   => 'फरक',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'विपत्रपत्ता वैध आहे',
'email-address-validity-invalid' => 'वैध विपत्रपत्ता लिहा',

# User rights
'userrights'                   => 'सदस्य अधिकार व्यवस्थापन',
'userrights-lookup-user'       => 'सदस्य गटांचे(ग्रूप्स) व्यवस्थापन करा.',
'userrights-user-editname'     => 'सदस्य नाव टाका:',
'editusergroup'                => 'सदस्य गट (ग्रूप्स) संपादीत करा',
'editinguser'                  => "सदस्य '''[[User:$1|$1]]''' $2 चे सदस्य अधिकारात बदल केला जात आहे.",
'userrights-editusergroup'     => 'सदस्य मंडळे संपादीत करा',
'saveusergroups'               => 'सदस्य गट जतन करा',
'userrights-groupsmember'      => '(चा) सभासद:',
'userrights-groupsmember-auto' => 'चा निर्विवाद सदस्य:',
'userrights-groups-help'       => 'तुम्ही एखाद्या सदस्याचे गट सदस्यत्व बदलू शकता:
* निवडलेला चौकोन म्हणजे सदस्य त्या गटात आहे.
* न निवडलेला चौकोन म्हणजे सदस्य त्या गटात नाही.
* एक * चा अर्थ तुम्ही एकदा समावेश केल्यानंतर तो गट बदलू शकत नाही, किंवा काढल्यानंतर समावेश करू शकत नाही.',
'userrights-reason'            => 'कारण:',
'userrights-no-interwiki'      => 'इतर विकींवरचे सदस्य अधिकार बदलण्याची परवानगी तुम्हाला नाही.',
'userrights-nodatabase'        => 'विदा $1 अस्तीत्वात नाही अथवा स्थानिक नाही.',
'userrights-nologin'           => 'सदस्य अधिकार देण्यासाठी तुम्ही प्रबंधक म्हणून [[Special:UserLogin|प्रवेश केलेला]] असणे आवश्यक आहे.',
'userrights-notallowed'        => 'तुमच्या सदस्य खात्यास सदस्य अधिकारांची निश्चिती करण्याची परवानगी नाही.',
'userrights-changeable-col'    => 'गट जे तुम्ही बदलू शकता',
'userrights-unchangeable-col'  => 'गट जे तुम्ही बदलू शकत नाही',

# Groups
'group'               => 'गट:',
'group-user'          => 'सदस्य',
'group-autoconfirmed' => 'नोंदणीकृत सदस्य',
'group-bot'           => 'सांगकामे',
'group-sysop'         => 'प्रचालक',
'group-bureaucrat'    => 'स्विकृती अधिकारी',
'group-suppress'      => 'झापडबंद',
'group-all'           => '(सर्व)',

'group-user-member'          => '{{लिंग:$1|सदस्य}}',
'group-autoconfirmed-member' => 'स्वयंशाबीत सदस्य',
'group-bot-member'           => '{{GENDER:$1|सांगकाम्या}}',
'group-sysop-member'         => '{{GENDER:$1|प्रचालक}}',
'group-bureaucrat-member'    => '{{GENDER:$1|स्विकृती अधिकारी}}',
'group-suppress-member'      => '{{GENDER:$1|झापडबंद}}',

'grouppage-user'          => '{{ns:project}}:सदस्य',
'grouppage-autoconfirmed' => '{{ns:project}}:नोंदणीकृत सदस्य',
'grouppage-bot'           => '{{ns:project}}:सांगकाम्या',
'grouppage-sysop'         => '{{ns:project}}:प्रचालक',
'grouppage-bureaucrat'    => '{{ns:project}}:स्विकृती अधिकारी',
'grouppage-suppress'      => '{{ns:project}}:झापडबंद',

# Rights
'right-read'                  => 'पृष्ठे वाचा',
'right-edit'                  => 'पाने संपादा',
'right-createpage'            => 'पृष्ठे तयार करा',
'right-createtalk'            => 'चर्चा पृष्ठे तयार करा',
'right-createaccount'         => 'नवीन सदस्य खाती तयार करा',
'right-minoredit'             => 'बदल छोटे म्हणून जतन करा',
'right-move'                  => 'पानांचे स्थानांतरण करा',
'right-move-subpages'         => 'पाने उपपानांसकट हलवा',
'right-move-rootuserpages'    => 'मूळ सदस्यपाने हलवा',
'right-movefile'              => 'संचिका हलवा',
'right-suppressredirect'      => 'एखाद्या पानाचे नवीन नावावर स्थानांतरण करत असताना पुनर्निर्देशन वगळा',
'right-upload'                => 'संचिका चढवा',
'right-reupload'              => 'अस्तित्वात असलेल्या संचिकेवर पुनर्लेखन करा',
'right-reupload-own'          => 'त्याच सदस्याने चढविलेल्या संचिकेवर पुनर्लेखन करा',
'right-reupload-shared'       => 'स्थानिक पातळीवरून शेअर्ड चित्र धारिकेतील संचिकांवर पुनर्लेखन करा',
'right-upload_by_url'         => 'एखादी संचिका URL सहित चढवा',
'right-purge'                 => 'एखाद्या पानाची सय रिकामी करा',
'right-autoconfirmed'         => 'नोंदणीकृत सदस्याप्रमाणे वागणूक मिळवा',
'right-bot'                   => 'स्वयंचलित कार्याप्रमाणे वागणूक मिळवा',
'right-nominornewtalk'        => 'चर्चा पृष्ठावर छोटी संपादने जी नवीन चर्चा दर्शवितात ती नकोत',
'right-apihighlimits'         => 'API पृच्छांमध्ये वरची मर्यादा वापरा',
'right-writeapi'              => 'लेखन एपीआय चा उपयोग',
'right-delete'                => 'पृष्ठे वगळा',
'right-bigdelete'             => 'जास्त इतिहास असणारी पाने वगळा',
'right-deleterevision'        => 'एखाद्या पानाच्या विशिष्ट आवृत्त्या लपवा',
'right-deletedhistory'        => 'वगळलेल्या इतिहास नोंदी, त्यांच्या संलग्न मजकूराशिवाय पहा',
'right-deletedtext'           => 'वगळलेला मजकूर व वगळलेल्या आवर्तनांमधील बदल पहा',
'right-browsearchive'         => 'वगळलेली पाने पहा',
'right-undelete'              => 'एखादे पान पुनर्स्थापित करा',
'right-suppressrevision'      => 'लपविलेल्या आवृत्त्या पहा व पुनर्स्थापित करा',
'right-suppressionlog'        => 'खासगी नोंदी पहा',
'right-block'                 => 'इतर सदस्यांना संपादन करण्यापासून प्रतिबंधित करा',
'right-blockemail'            => 'एखाद्या सदस्याला इ-मेल पाठविण्यापासून थांबवा',
'right-hideuser'              => 'एखादे सदस्य नाव इतरांपासून लपवा',
'right-ipblock-exempt'        => 'आइपी ब्लॉक्स कडे दुर्लक्ष करा',
'right-proxyunbannable'       => 'प्रॉक्सी असताना ब्लॉक्स कडे दुर्लक्ष करा',
'right-unblockself'           => 'अप्रतिबंधित करा',
'right-protect'               => 'सुरक्षितता पातळी बदला',
'right-editprotected'         => 'सुरक्षित पाने संपादा',
'right-editinterface'         => 'सदस्य पसंती बदला',
'right-editusercssjs'         => 'इतर सदस्यांच्या CSS व JS संचिका संपादित करा',
'right-editusercss'           => 'इतर सदस्यांच्या CSS संचिका संपादित करा',
'right-edituserjs'            => 'इतर सदस्यांच्या JS संचिका संपादित करा',
'right-rollback'              => 'एखादे विशिष्ट पान ज्याने संपादन केले त्याला लवकर पूर्वपदास न्या',
'right-markbotedits'          => 'निवडलेली संपादने सांगकाम्यांची म्हणून जतन करा',
'right-noratelimit'           => 'रेट लिमिट्स चा परिणाम होत नाही.',
'right-import'                => 'इतर विकिंमधून पाने आयात करा',
'right-importupload'          => 'चढविलेल्या संचिकेतून पाने आयात करा',
'right-patrol'                => 'इतरांची संपादने तपासलेली म्हणून जतन करा',
'right-autopatrol'            => 'संपादने आपोआप तपासलेली (patrolled) म्हणून जतन करा',
'right-patrolmarks'           => 'अलीकडील बदलांमधील तपासल्याच्या खूणा पहा',
'right-unwatchedpages'        => 'न पाहिलेल्या पानांची यादी पहा',
'right-mergehistory'          => 'पानांचा इतिहास एकत्रित करा',
'right-userrights'            => 'सर्व सदस्यांचे अधिकार संपादा',
'right-userrights-interwiki'  => 'इतर विकिंवर सदस्य अधिकार बदला',
'right-siteadmin'             => 'माहितीसाठ्याला कुलुप लावा अथवा काढा',
'right-override-export-depth' => 'पाने निर्यात करा (आंतरिक जेडलेली पाने पाचव्या पतळी पर्यंत समाविष्ट करुन).',
'right-sendemail'             => 'इतर सदस्यांना विपत्रे पाठवा',
'right-passwordreset'         => 'परवलीचा शब्द (पासवर्ड) पुन:स्थापित केल्याची इ मेल पहा.',

# User rights log
'rightslog'                  => 'सदस्य आधिकार नोंद',
'rightslogtext'              => 'ही सदस्य अधिकारांमध्ये झालेल्या बदलांची यादी आहे.',
'rightslogentry'             => '$1 चे ग्रुप सदस्यत्व $2 पासून $3 ला बदलण्यात आलेले आहे',
'rightslogentry-autopromote' => '$2 ते $3 आपोआप नियुक्ती झाली.',
'rightsnone'                 => '(काहीही नाही)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'हे पान वाचा',
'action-edit'                 => 'हे पान संपादित करा',
'action-createpage'           => 'लेख बनवा',
'action-createtalk'           => 'चर्चा पृष्ठे तयार करा',
'action-createaccount'        => 'हे सदस्यखाते तयार करा',
'action-minoredit'            => 'हे संपादन छोटे ठरवा',
'action-move'                 => 'हे पान स्थानांतरित करा',
'action-move-subpages'        => 'हे पान व त्याची उपपाने हलवा',
'action-move-rootuserpages'   => 'मूळ सदस्यपाने हलवा',
'action-movefile'             => 'ही संचिका हलवा',
'action-upload'               => 'ही संचिका चढवा',
'action-reupload'             => 'अस्तित्वात असलेल्या संचिकेवर पुनर्लेखन करा',
'action-reupload-shared'      => 'हि संचिका सामाईक (shared) संग्रहस्थानावर (repository) पुन्हा लिहा.',
'action-upload_by_url'        => 'आंतरजालपत्त्यापासून संचिका चढवा',
'action-writeapi'             => 'लेखन एपीआय वापरा',
'action-delete'               => 'हे पान वगळा',
'action-deleterevision'       => 'हे आवर्तन वगळा',
'action-deletedhistory'       => 'या पानाचा वगळलेला इतिहास पहा',
'action-browsearchive'        => 'वगळलेली पाने शोधा',
'action-undelete'             => 'वगळ्लेले पृष्ठ पुन्हा आणा',
'action-suppressrevision'     => 'लपलेले पुनरावर्तन पहा व सद्यस्थितीत आणा',
'action-suppressionlog'       => 'ही खासगी यादी पहा',
'action-block'                => 'या सदस्यास संपादन करण्यापासून प्रतिबंधित करा',
'action-protect'              => 'या पानाशाठी सुरक्षापातळी बदला',
'action-rollback'             => 'या आधीच्या सदस्याचे नुकतेच संपादन केलेले एखादे विशिष्ट पानाचे बदल लवकर आधीच्य स्थितीत न्या',
'action-import'               => 'दुसर्‍या विकीवरुन हे पान आयात करा',
'action-importupload'         => 'चढविलेल्या संचिकेतून पान आयात करा',
'action-patrol'               => 'इतरांची संपादने तपासलेली म्हणून जतन करा',
'action-autopatrol'           => 'आपल्या बदलास देखरेखी खाली असल्याचे सुचवा',
'action-unwatchedpages'       => 'न पाहिलेल्या पानांची यादी पहा',
'action-mergehistory'         => 'पानाचा इतिहास विलीन करा',
'action-userrights'           => 'सर्व सदस्यांचे अधिकार संपादित करा',
'action-userrights-interwiki' => 'इतर विकिंवरच्या सदस्यांचे अधिकार संपादित करा',
'action-siteadmin'            => 'माहितीसाठ्याला कुलुप लावा अथवा काढा',
'action-sendemail'            => 'विपत्रे (ई-मेल्स) पाठवा.',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|बदल|बदल}}',
'recentchanges'                     => 'अलीकडील बदल',
'recentchanges-legend'              => 'अलीकडील बदल पर्याय',
'recentchangestext'                 => 'विकितील अलीकडील बदल या पानावर दिसतात.',
'recentchanges-feed-description'    => 'या रसदीमधील विकीवर झालेले सर्वात अलीकडील बदल पहा.',
'recentchanges-label-newpage'       => 'या संपादनाने नवीन पान तयार झाले',
'recentchanges-label-minor'         => 'हा एक छोटा बदल आहे',
'recentchanges-label-bot'           => 'हे संपादन एका सांगकाम्याकडून केले गेले आहे',
'recentchanges-label-unpatrolled'   => 'हे संपादन अजून तपासले गेले नाही',
'rcnote'                            => "खाली $4, $5 पर्यंतचे गेल्या {{PLURAL:$2|'''१''' दिवसातील|'''$2''' दिवसांतील}} {{PLURAL:$1|शेवटचा '''1''' बदल|शेवटचे '''$1''' बदल}} दिलेले आहेत.",
'rcnotefrom'                        => 'खाली <b>$2</b> पासूनचे (<b>$1</b> किंवा कमी) बदल दाखवले आहेत.',
'rclistfrom'                        => '$1 नंतर केले गेलेले बदल दाखवा.',
'rcshowhideminor'                   => 'छोटे बदल $1',
'rcshowhidebots'                    => 'सांगकामे(बॉट्स) $1',
'rcshowhideliu'                     => 'प्रवेश केलेले सदस्य $1',
'rcshowhideanons'                   => 'अनामिक सदस्य $1',
'rcshowhidepatr'                    => '$1 पहारा असलेली संपादने',
'rcshowhidemine'                    => 'माझे बदल $1',
'rclinks'                           => 'मागील $2 दिवसांतील $1 बदल पहा.<br />$3',
'diff'                              => 'फरक',
'hist'                              => 'इति',
'hide'                              => 'लपवा',
'show'                              => 'पहा',
'minoreditletter'                   => 'छो',
'newpageletter'                     => 'न',
'boteditletter'                     => 'सां',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|सदस्याने|सदस्यांनी}} पहारा दिलेला आहे]',
'rc_categories'                     => 'वर्गांपपुरते मर्यादीत ठेवा ("|"ने वेगळे करा)',
'rc_categories_any'                 => 'कोणतेही',
'newsectionsummary'                 => '/* $1 */ नवीन विभाग',
'rc-enhanced-expand'                => 'अधिक माहिती दाखवा (जावास्क्रीप्टची गरज)',
'rc-enhanced-hide'                  => 'अधिक माहिती लपवा',

# Recent changes linked
'recentchangeslinked'          => 'या पृष्ठासंबंधीचे बदल',
'recentchangeslinked-feed'     => 'या पृष्ठासंबंधीचे बदल',
'recentchangeslinked-toolbox'  => 'या पृष्ठासंबंधीचे बदल',
'recentchangeslinked-title'    => '"$1" च्या संदर्भातील बदल',
'recentchangeslinked-noresult' => 'जोडलेल्या पानांमध्ये दिलेल्या कालावधीत काहीही बदल झालेले नाहीत.',
'recentchangeslinked-summary'  => "हे पृष्ठ एखाद्या विशिष्ट पानाशी (किंवा एखाद्या विशिष्ट वर्गात असणार्‍या पानांशी) जोडलेल्या पानांवरील बदल दर्शवते.
तुमच्या पहार्‍याच्या सूचीतील पाने '''ठळक''' दिसतील.",
'recentchangeslinked-page'     => 'पृष्ठ नाव:',
'recentchangeslinked-to'       => 'याऐवजी दिलेल्या पानाला जोडलेल्या पानांवरील बदल दाखवा',

# Upload
'upload'                      => 'संचिका चढवा',
'uploadbtn'                   => 'संचिका चढवा',
'reuploaddesc'                => 'चढवायच्या पानाकडे परता',
'upload-tryagain'             => 'बदललेले संचिका वर्णन पाठवा',
'uploadnologin'               => 'प्रवेश केलेला नाही',
'uploadnologintext'           => 'संचिका चढविण्यासाठी तुम्हाला [[Special:UserLogin|प्रवेश]] करावा लागेल.',
'upload_directory_missing'    => 'अपलोड डिरेक्टरी ($1) सापडली नाही तसेच वेबसर्व्हर ती तयार करू शकलेला नाही.',
'upload_directory_read_only'  => '$1 या डिरेक्टरी मध्ये सर्व्हर लिहू शकत नाही.',
'uploaderror'                 => 'चढवण्यात चूक',
'upload-recreate-warning'     => "'''सावधान: या नावाची संचीका वगळली अथवा स्थलांतरीत करण्यात आली आहे.'''
या पानाची वगळल्याची व स्थलांतरणाची नोंद तुमच्या सोयीसाठी येथे पुरवली आहे.:",
'uploadtext'                  => "खालील अर्ज नवीन संचिका चढविण्यासाठी वापरा.
पूर्वी चढविलेल्या संचिका पाहण्यासाठी अथवा शोधण्यासाठी [[Special:FileList|चढविलेल्या संचिकांची यादी]] पहा. चढविलेल्या तसेच वगळलेल्या संचिकांची यादी पहाण्यासाठी [[Special:Log/upload|चढवलेल्या संचिकांची सूची]] व [[Special:Log/delete|वगळलेल्या संचिकांची सूची]] पहा.

एखाद्या लेखात ही संचिका वापरण्यासाठी खालीलप्रमाणे दुवा द्या
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|alt text]]</nowiki>''' किंवा
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' संचिकेला थेट दुवा देण्यासाठी वापरा.",
'upload-permitted'            => 'अनुमतीत संचिका वर्ग: $1.',
'upload-preferred'            => 'श्रेयस्कर संचिका प्रकार:$1.',
'upload-prohibited'           => 'प्रतिबंधीत संचिका प्रकार: $1.',
'uploadlog'                   => 'चढवल्याची नोंद',
'uploadlogpage'               => 'चढवल्याची नोंद',
'uploadlogpagetext'           => 'नवीनतम चढवलेल्या संचिकांची यादी.',
'filename'                    => 'संचिकेचे नाव',
'filedesc'                    => 'वर्णन',
'fileuploadsummary'           => 'आढावा:',
'filereuploadsummary'         => 'संचिका बदल:',
'filestatus'                  => 'प्रताधिकार स्थिती:',
'filesource'                  => 'स्रोत:',
'uploadedfiles'               => 'चढवलेल्या संचिका',
'ignorewarning'               => 'सुचनेकडे दुर्लक्ष करा आणि संचिका जतन करा.',
'ignorewarnings'              => 'सर्व सुचनांकडे दुर्लक्ष करा',
'minlength1'                  => 'संचिकानाम किमान एक अक्षराचे हवे.',
'illegalfilename'             => '"$1" या संचिकानामात शीर्षकात चालू न शकणारी अक्षरे आहेत. कृपया संचिकानाम बदलून पुन्हा चढवण्याचा प्रयत्न करा.',
'filename-toolong'            => '२४० बाईटपेक्षा फाईलचे नांव स्वीकारले जाणार नाही.',
'badfilename'                 => 'संचिकेचे नाव बदलून "$1" असे केले आहे.',
'filetype-mime-mismatch'      => 'संचिका विस्तारक ".$1" ठरवलेल्या एमआयएमई संचिकाप्रकारांशी जुळत नाही ($2).',
'filetype-badmime'            => 'विविधामाप(माईम) "$1" प्रकारच्या संचिका चढवण्यास परवानगी नाही.',
'filetype-bad-ie-mime'        => 'ही संचिका चढवता येत नाही कारण इंटरनेट एक्स्प्लोरर तिला "$1" म्हणून ओळखेल. हा संचिकाप्रकार प्रतिबंधित व संभाव्य धोकादायक संचिकाप्रकार आहे.',
'filetype-unwanted-type'      => "'''\".\$1\"''' हा अवांछित संचिका प्रकार आहे. प्राधान्याने \$2 {{PLURAL:\$3|या प्रकारच्या |या प्रकारांच्या}} संचिका हव्या आहेत.",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|ही परवानगी नसलेल्या प्रकारची संचिका आहे.|ह्या परवानगी नसलेल्या प्रकारच्या संचिका आहेत.}} $2 {{PLURAL:$3|ही परवानगी असलेल्या प्रकारची संचिका आहे|ह्या परवानगी असलेल्या प्रकारच्या संचिका आहेत}}.',
'filetype-missing'            => 'या संचिकेला एक्सटेंशन दिलेले नाही (उदा. ".jpg").',
'empty-file'                  => 'तुम्ही प्रस्तुत केलेली संचिका रिकामी होती.',
'file-too-large'              => 'तुम्ही प्रस्तुत केलेली संचिका आकाराने खूप जास्त होती.',
'filename-tooshort'           => 'तुम्ही प्रस्तुत केलेली संचिका आकाराने खूप कमी होती.',
'filetype-banned'             => 'याप्रकारची संचिका प्रतिबंधित आहे.',
'verification-error'          => 'संचिका पडताळणीत ही संचिका अनुत्तीर्ण झाली.',
'hookaborted'                 => 'तुम्ही करू इच्छीणारे संपादन बाह्य हुक द्वारे थंबवण्यात आले.',
'illegal-filename'            => 'हे संचिकानाम प्रतिबंधित आहे.',
'overwrite'                   => 'अस्तित्वात असलेल्या संचिकेवर पुनर्लेखन प्रतिबंधित आहे.',
'unknown-error'               => 'एक अज्ञात चूक उद्भवली.',
'tmp-create-error'            => 'तात्पुरती संचिका बनवता आली नाही.',
'tmp-write-error'             => 'तात्पुरती संचिका लिहताना अडचण',
'large-file'                  => 'संचिका $1 पेक्षा कमी आकाराची असण्याची अपेक्षा आहे, ही संचिका $2 एवढी आहे.',
'largefileserver'             => 'सेवा संगणकावर (सर्वर) निर्धारित केलेल्या आकारापेक्षा या संचिकेचा आकार मोठा आहे.',
'emptyfile'                   => 'चढवलेली संचिका रिकामी आहे. हे संचिकानाम चुकीचे लिहिल्याने असू शकते. कृपया तुम्हाला हीच संचिका चढवायची आहे का ते तपासा.',
'windows-nonascii-filename'   => 'या विकीवर विशेष चिन्हातील फाईलनांवाचा आधार घेता येणार नाही.',
'fileexists'                  => "या नावाची संचिका आधीच अस्तित्वात आहे, कृपया ही संचिका बदलण्याबद्दल तुम्ही साशंक असाल तर '''<tt>[[:$1]]</tt>''' तपासा.
[[$1|thumb]]",
'filepageexists'              => "या नावाचे एक माहितीपृष्ठ (संचिका नव्हे) अगोदरच अस्तित्त्वात आहे. कृपया जर आपणांस त्यात बदल करायचा नसेल तर '''<tt>[[:$1]]</tt>''' तपासा.
[[$1|thumb]]",
'fileexists-extension'        => "या नावाची संचिका अस्तित्वात आहे: [[$2|thumb]]
* चढवित असलेल्या संचिकेचे नाव: '''<tt>[[:$1]]</tt>'''
* अस्तित्वात असलेल्या संचिकेचे नाव: '''<tt>[[:$2]]</tt>'''
कृपया दुसरे नाव निवडा.",
'fileexists-thumbnail-yes'    => "आपण चढवित असलेली संचिका ही मोठ्या चित्राची इवलीशी प्रतिकृती ''(thumbnail)'' असण्याची शक्यता आहे. [[$1|इवलेसे]]
कृपया '''<tt>[[:$1]]</tt>''' ही संचिका तपासा.
जर तपासलेली संचिका ही याच आकाराची असेल तर नवीन प्रतिकृती चढविण्याची गरज नाही.",
'file-thumbnail-no'           => "या संचिकेचे नाव '''<tt>$1</tt>''' पासून सुरू होत आहे. ही कदाचित झलक असू शकते.
जर तुमच्या कडे पूर्ण रिझोल्यूशनची संचिका असेल तर चढवा अथवा संचिकेचे नाव बदला.",
'fileexists-forbidden'        => 'या नावाची संचिका अगोदरच अस्तित्त्वात आहे; कृपया पुन्हा मागे जाऊन ही संचिका नवीन नावाने चढवा.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'हे नाव असलेली एक संचिका शेअर्ड संचिका कोशात आधी पासून आहे; कृपया परत फिरा आणि नविन(वेगळ्या) नावाने ही संचिका पुन्हा चढवा.[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'ही संचिका खालील {{PLURAL:$1|संचिकेची|संचिकांची}} प्रत आहे:',
'file-deleted-duplicate'      => 'या संचिकेसारखीच् संचिका ([[:$1]]) या आधी वगळण्यात आली आहे.
हि संचिका पुनः चढवण्यापूर्वी आपण त्या संचिकेची वगळण्याची नोंद तपासावी.',
'uploadwarning'               => 'चढवताना सूचना',
'uploadwarning-text'          => 'कृपया खालील संचिका वर्णन संपादित करून पुनर्प्रयत्न करा.',
'savefile'                    => 'संचिका जतन करा',
'uploadedimage'               => '"[[$1]]" ही संचिका चढवली',
'overwroteimage'              => '"[[$1]]" या संचिकेची नवीन आवृत्ती चढविली.',
'uploaddisabled'              => 'संचिका चढविण्यास बंदी घालण्यात आलेली आहे.',
'copyuploaddisabled'          => 'आंतरजालपत्त्याद्वारे चढवणे प्रतिबंधित आहे.',
'uploadfromurl-queued'        => 'तुमचे चढवणे नोंदवण्यात आले आहे',
'uploaddisabledtext'          => '{{SITENAME}} वर संचिका चढविण्यास बंदी घालण्यात आलेली आहे.',
'php-uploaddisabledtext'      => 'PHP मध्ये संचिका चढवणे प्रतिबंधीत केले आहे.
कृपया file_uploads मांडणी (setting) तपासावी.',
'uploadscripted'              => 'या संचिकेत HTML किंवा स्क्रिप्ट कोडचा आंतर्भाव आहे, त्याचा एखाद्या विचरकाकडून विचीत्र अर्थ लावला जाऊ शकतो.',
'uploadvirus'                 => 'ह्या संचिकेत व्हायरस आहे. अधिक माहिती: $1',
'uploadjava'                  => 'ही फाईल झीप् ह्या प्रकारातिल आहे ज्यामधे जाव्हा .क्लास फाईल. आहे,
 जाव्हा फाईल  ह्यात वापर्ता  येनार नाहित ,कारन ईथे सुरक्षेचे कारने येतात्',
'upload-source'               => 'स्रोत संचिका',
'sourcefilename'              => 'स्रोत-संचिकानाम:',
'sourceurl'                   => 'स्रोत युआरएल',
'destfilename'                => 'नवे संचिकानाम:',
'upload-maxfilesize'          => 'जास्तीतजास्त संचिका आकार: $1',
'upload-description'          => 'संचिका वर्णन',
'upload-options'              => 'चढवण्यासाठी पर्याय',
'watchthisupload'             => 'या पानावर बदलांसाठी लक्ष ठेवा.',
'filewasdeleted'              => 'या नावाची संचिका या पूर्वी एकदा चढवून नंतर वगळली होती.तुम्ही ती पुन्हा चढवण्या अगोदर $1 तपासा.',
'filename-bad-prefix'         => "तुम्ही चढवत असलेल्या संचिकेचे नाव '''\"\$1\"''' पासून सुरू होते, जे की अंकीय छाउ (कॅमेरा) ने दिलेले अवर्णनात्मक नाव आहे.कृपया तुमच्या संचिकेकरिता अधिक वर्णनात्मक नाव निवडा.",
'upload-success-subj'         => 'यशस्वीरीत्या चढवले',
'upload-success-msg'          => 'तुमचे [$2] येथून्ब चढवणे यशस्वी ठरले. ते येथे उपलब्ध आहे: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'चढवण्यातील त्रूटि:',
'upload-failure-msg'          => '[$2] येथून तुमच्या चढवण्यात चूक झाली:

$1',
'upload-warning-subj'         => 'चढवताना सूचना',
'upload-warning-msg'          => 'तुमच्या चढवण्यात [$2] येथून चूक झाली. तुम्ही [[Special:Upload/stash/$1|चढवण्याचा अर्ज]] पुन्हा भरुन ही चूक दूर करू शकता.',

'upload-proto-error'        => 'चूकीचा संकेत',
'upload-proto-error-text'   => 'दूरस्थ चढवण्याच्या क्रियेत <code>http://</code>पासून किंवा <code>ftp://</code>पासून सूरू होणारी URL लागतात.',
'upload-file-error'         => 'अंतर्गत त्रूटी',
'upload-file-error-text'    => 'विदादात्यावर तात्पुरती संचिका तयार करण्याच्या प्रयत्न करत असताना अंतर्गत तांत्रिक अडचण आली.कृपया [[Special:ListUsers/sysop|प्रचालकांशी]] संपर्क करा.',
'upload-misc-error'         => 'संचिका चढविताना माहित नसलेली त्रूटी आलेली आहे.',
'upload-misc-error-text'    => 'चढवताना अज्ञात तांत्रिक अडचण आली.कृपया आंतरजालपत्ता सुयोग्य आणि उपलब्ध आहे का ते तपासा आणि पुन्हा प्रयत्न करा. अधिक अडचणी आल्यास तर [[Special:ListUsers/sysop|प्रचालकांशी]] संपर्क करा.',
'upload-too-many-redirects' => 'या आंतरजालपत्त्यात खूप पुनर्निर्देशने आहेत',
'upload-unknown-size'       => 'अज्ञात आकारमान',
'upload-http-error'         => 'एक एचटीटीपी चूक उद्भवली: $1',

# ZipDirectoryReader
'zip-file-open-error' => 'संचीका ZIP तपासणीसाठी उघडताना त्रुटी आली.',
'zip-wrong-format'    => 'ही संचिका "झिप" प्रकारची नाही.',
'zip-bad'             => 'ही संचिका दूषित किंवा न वाचता येणारी झिप संचिका आहे.
ती सुरक्षिततेसाठी नीट तपासता आली नाही.',
'zip-unsupported'     => 'हि संचिका एक ZIP संचिका आहे जी मिडीयाविकी द्वरे  (support) न केलेले ZIP वैशिष्ट्ये (features) वापरते.
या संचिकेची सुरक्षा पडताळणीसाठी व्यवस्थितरीत्या होऊ शकत नाही.',

# Special:UploadStash
'uploadstash'          => 'चढवणे लपवा',
'uploadstash-summary'  => 'या पानावर अश्या संचिका पहावयास् मिळतात ज्या चढवल्या आहेत (अथवा चढवल्या जात आहेत) परंतु अजुन विकी वर प्रकाशित केल्या नाहित. या संचिका फक्त त्या सदस्यास् दिसतील ज्याने त्या चढवल्या आहेत, इतर सदस्यांस् त्या दिसणार नाहीत.',
'uploadstash-clear'    => 'लपवलेल्या संचिका काढा',
'uploadstash-nofiles'  => 'तुमच्याकडे लपवलेल्या संचिका नाहीत.',
'uploadstash-badtoken' => 'हि कृती अयशस्वी होती. कदाचित आपल्या संपादन अधिकारपत्राची (editing credentials) मुदत संपली.',
'uploadstash-errclear' => 'संचिका स्वच्छ करणे अयशस्वी.',
'uploadstash-refresh'  => 'संचिकांची यादी ताजीतवानी करा',
'invalid-chunk-offset' => 'अग्राह्य चंक ऑफसेट',

# img_auth script messages
'img-auth-accessdenied'     => 'परवानगी नाही',
'img-auth-nopathinfo'       => 'PATH_INFO आढळले नाही.
आपला सर्व्हर ही माहिती पोचवू शकत नाही.
तो सीजीआय-आधारित व img_auth ला समर्थन न देऊ शकणारा असू शकतो.
[//www.mediawiki.org/wiki/Manual:Image_Authorization कृपया हे पहा.]',
'img-auth-notindir'         => 'मागितलेला मार्ग अपलोड निर्देशिकेकरीता जोडलेला नाही.',
'img-auth-badtitle'         => '"$1" पासून वैध शीर्षक बनवण्यात अयशस्वी.',
'img-auth-nologinnWL'       => 'तुम्ही प्रवेश घेतलेला नाही व "$1" श्वेतयादीत नाही.',
'img-auth-nofile'           => '"$1" ही संचिका अस्तित्वात नाही.',
'img-auth-isdir'            => 'तुम्ही $1 निदेशक वापरण्याचा प्रयत्न करत आहात.
फक्त संचिका वापरण्याची परवानगी आहे.',
'img-auth-streaming'        => 'स्ट्रीमिंग "$1".',
'img-auth-public'           => 'img_auth.php हे  वैयक्तिक विकीमधून  माहिती प्रदान करण्याचे कार्य करते.
हा विकि सार्वजनिक विकि म्हणून सब्चित करण्यात आला आ.े.
उचित सुरक्षा के लिए img_auth.php को निष्कृय किया हुआ है।',
'img-auth-noread'           => 'तुम्हाला "$1" वाचण्याची परवानगी नाही',
'img-auth-bad-query-string' => 'या दुव्यामध्ये (URL) अवैध query string आहे.',

# HTTP errors
'http-invalid-url'      => 'अवैध आंतरजालपत्ता: $1',
'http-invalid-scheme'   => 'URL सोबत "$1"पध्दत चालत नाही',
'http-request-error'    => 'एचटीटीपी मागणी अज्ञात कारणामुळे अयशस्वी.',
'http-read-error'       => 'एचटीटीपी वाचन त्रुटी.',
'http-timed-out'        => 'विनंती वेळ सपला आहे',
'http-curl-error'       => 'आंतरजालपत्ता पकडताना चूक: $1',
'http-host-unreachable' => 'आंतरजाल पत्त्यापाशी पोहोचले नाही',
'http-bad-status'       => 'एचटीटीपी मागणीदरम्यान एक चूक उद्भवली: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'आंतरजाल पत्त्यापाशी पोहोचले नाही',
'upload-curl-error6-text'  => 'दिलेल्या URL ला पोहचू शकलो नाही.कृपया URL बरोबर असून संकेतस्थळ चालू असल्याची पुनश्च खात्री करा.',
'upload-curl-error28'      => 'चढवण्यात वेळगेली',
'upload-curl-error28-text' => 'संकेतस्थळाने साद देण्यात खूप जास्त वेळ घेतला आहे,कृपया थोडा वेळ थांबा आणि पुन्हा प्रयत्न करा.कदाचित तुम्ही कमी गर्दीच्या वेळात प्रयत्न करू इच्छीताल.',

'license'            => 'परवाना:',
'license-header'     => 'परवाना:',
'nolicense'          => 'काही निवडलेले नाही',
'license-nopreview'  => '(झलक उपलब्ध नाही)',
'upload_source_url'  => '(एक सुयोग्य,सार्वजनिकरित्या उपलब्ध URL)',
'upload_source_file' => '(तुमच्या संगणकावरील एक संचिका)',

# Special:ListFiles
'listfiles-summary'     => 'हे विशेष पान सर्व चढविलेल्या संचिका दर्शिविते.
सर्वसाधारणपणे सगळ्यात शेवटी बदल झालेल्या संचिका सर्वात वर दिसतात.
रकान्याच्या नावापुढे टिचकी देऊन संचिकांचा अनुक्रम बदलता येतो.',
'listfiles_search_for'  => 'चित्र नावाने शोध:',
'imgfile'               => 'संचिका',
'listfiles'             => 'चित्र यादी',
'listfiles_thumb'       => 'प्रारुप',
'listfiles_date'        => 'दिनांक',
'listfiles_name'        => 'नाव',
'listfiles_user'        => 'सदस्य',
'listfiles_size'        => 'आकार (बाईट्स)',
'listfiles_description' => 'वर्णन',
'listfiles_count'       => 'आवृत्त्या',

# File description page
'file-anchor-link'          => 'संचिका',
'filehist'                  => 'संचिकेचा इतिहास',
'filehist-help'             => 'संचिकेची पूर्वीची आवृत्ती बघण्यासाठी दिनांक/वेळ वर टिचकी द्या.',
'filehist-deleteall'        => 'सर्व वगळा',
'filehist-deleteone'        => 'वगळा',
'filehist-revert'           => 'उलटवा',
'filehist-current'          => 'सद्य',
'filehist-datetime'         => 'दिनांक/वेळ',
'filehist-thumb'            => 'प्रारुप',
'filehist-thumbtext'        => '$1 च्या आवृत्तीचे छोटे प्रारुप',
'filehist-nothumb'          => 'प्रारुप नाही',
'filehist-user'             => 'सदस्य',
'filehist-dimensions'       => 'आकार',
'filehist-filesize'         => 'संचिकेचा आकार (बाईट्स)',
'filehist-comment'          => 'प्रतिक्रीया',
'filehist-missing'          => 'संचिका सापडत नाही',
'imagelinks'                => 'संचिका दुवे',
'linkstoimage'              => 'खालील {{PLURAL:$1|पान चित्राशी जोडले आहे|$1 पाने चित्राशी जोडली आहेत}}:',
'linkstoimage-more'         => 'या संचिके ला $1 {{PLURAL:$1|पान जोडले|पाने जोडली}} आहेत.
या संचिके ला जोडलेल्या {{PLURAL:$1|पहिल्या पानचा दुवा खाली दिला आहे|पहिल्या $1 पानांचे दुवे खाली दिले आहेत}}.
[[Special:WhatLinksHere/$2|संपुर्ण यादी]] उपलब्ध आहे.',
'nolinkstoimage'            => 'या चित्राशी जोडलेली पृष्ठे नाही आहेत.',
'morelinkstoimage'          => 'या संचिकेचे [[Special:WhatLinksHere/$1|अधिक दुवे]] पहा.',
'linkstoimage-redirect'     => '$1 (संचिका पुनर्निर्देशन) $2',
'duplicatesoffile'          => 'खालील संचिका या दिलेल्या {{PLURAL:$1|संचिकेची प्रत आहे|$1 संचिकांच्या प्रती आहेत}}. [[Special:FileDuplicateSearch/$2|अधिक माहिती]]',
'sharedupload'              => 'ही संचिका $1 मधील आहे व ती इतर प्रकल्पांमध्ये वापरली गेल्याची शक्यता आहे.',
'sharedupload-desc-there'   => 'ही संचिका $1 मधील आहे व ती इतर प्रकल्पांमध्ये वापरली जाऊ शकते.
अधिक माहिती साठी कृपया [$2 संचिका वर्णन पान] पहावे.',
'sharedupload-desc-here'    => 'ही संचिका $1 येथील असून ती इतर प्रकल्पात वापरलेली असू शकते.
तिचे तेथील [$2 संचिका वर्णन पान] खाली दाखवले आहे.',
'filepage-nofile'           => 'या नावाची संचिका अस्तित्वात नाही.',
'filepage-nofile-link'      => 'या नावाची संचिका अस्तित्य्वात नाही, पण तुम्ही ती [$1 चढवू शकता].',
'uploadnewversion-linktext' => 'या संचिकेची नवीन आवृत्ती चढवा',
'shared-repo-from'          => '$1 पासून',
'shared-repo'               => 'एक मुक्त कोश',

# File reversion
'filerevert'                => '$1 पूर्वपद',
'filerevert-legend'         => 'संचिका पूर्वपदास',
'filerevert-intro'          => 'तुम्ही [$3, $2 प्रमाणे आवर्तन$4 कडे] [[Media:$1|$1]]  उलटवत आहात.',
'filerevert-comment'        => 'कारण:',
'filerevert-defaultcomment' => '$2, $1 च्या आवृत्तीत पूर्वपदास',
'filerevert-submit'         => 'पूर्वपद',
'filerevert-success'        => "[$3, $2 प्रमाणे आवर्तन $4]कडे '''[[Media:$1|$1]]''' उलटवण्यात आली.",
'filerevert-badversion'     => 'दिलेलेल्या वेळ मापनानुसार,या संचिकेकरिता कोणतीही पूर्वीची स्थानिक आवृत्ती नाही.',

# File deletion
'filedelete'                   => '$1 वगळा',
'filedelete-legend'            => 'संचिका वगळा',
'filedelete-intro'             => "तुम्ही '''[[Media:$1|$1]]''' वगळत आहात.",
'filedelete-intro-old'         => "[$4 $3, $2]च्या वेळेचे '''[[Media:$1|$1]]'''चे आवर्तन तुम्ही वगळत आहात.",
'filedelete-comment'           => 'कारण:',
'filedelete-submit'            => 'वगळा',
'filedelete-success'           => "'''$1'''वगळण्यात आले.",
'filedelete-success-old'       => '<span class="plainlinks">$3, $2 वेळी \'\'\'[[Media:$1|$1]]\'\'\' चे आवर्तन वगळण्यात आले आहे .</span>',
'filedelete-nofile'            => "'''$1''' अस्तित्वात नाही.",
'filedelete-nofile-old'        => "सांगितलेल्या गुणधर्मानुसार  '''$1'''चे कोणतेही विदा आवर्तन संचित नाही.",
'filedelete-otherreason'       => 'इतर/शिवाय अधिक कारण:',
'filedelete-reason-otherlist'  => 'इतर कारण',
'filedelete-reason-dropdown'   => '*वगळण्याची सामान्य कारणे
** प्रताधिकार उल्लंघन
** जुळी संचिका',
'filedelete-edit-reasonlist'   => 'वगळण्याची कारणे संपादीत करा',
'filedelete-maintenance'       => 'फाईल वगळने आणि पुन्:स्थापित करण्',
'filedelete-maintenance-title' => 'संचिका (फाईल) वगळू शकत नाही.',

# MIME search
'mimesearch'         => 'विविधामाप (माईम) शोधा',
'mimesearch-summary' => 'हे पान विविधामाप (माईम)-प्रकारांकरिता संचिकांची चाळणी करण्याची सुविधा पुरवते:
Input:contenttype/subtype, e.g. <tt>image/jpeg</tt>.',
'mimetype'           => 'विविधामाप (माईम) प्रकार:',
'download'           => 'उतरवा',

# Unwatched pages
'unwatchedpages' => 'लक्ष नसलेली पाने',

# List redirects
'listredirects' => 'पुनर्निर्देशने दाखवा',

# Unused templates
'unusedtemplates'     => 'न वापरलेले साचे',
'unusedtemplatestext' => 'या पानावर साचा नामविश्वातील अशी सर्व पाने आहेत जी कुठल्याही पानात वापरलेली नाहीत. वगळण्यापूर्वी साच्यांना जोडणारे इतर दुवे पाहण्यास विसरू नका.',
'unusedtemplateswlh'  => 'इतर दुवे',

# Random page
'randompage'         => 'अविशिष्ट लेख',
'randompage-nopages' => 'पुढील {{PLURAL:$2|नामविश्वात|नामविश्वांत}} कोणतीही पाने नाहीत: $1.',

# Random redirect
'randomredirect'         => 'अविशिष्ट पुनर्निर्देशन',
'randomredirect-nopages' => '$1 या नामविश्वात कोणतीही पुर्ननिर्देशने नाहीत.',

# Statistics
'statistics'                   => 'सांख्यिकी',
'statistics-header-pages'      => 'पृष्ठ सांख्यिकी',
'statistics-header-edits'      => 'संपादन सांख्यिकी',
'statistics-header-views'      => 'सांख्यिकी पहा',
'statistics-header-users'      => 'सदस्य सांख्यिकी',
'statistics-header-hooks'      => 'इतर सांख्यिकी',
'statistics-articles'          => 'संबंधित पाने',
'statistics-pages'             => 'पाने',
'statistics-pages-desc'        => 'विकीमधील सर्व पाने, पुनर्निर्देशने, चर्चापानांसहित.',
'statistics-files'             => 'चढवलेल्या संचिका',
'statistics-edits'             => '{{SITENAME}} च्या सुरुवातीपासूनची पानांची संपादने',
'statistics-edits-average'     => 'प्रतिपान सरासरी संपादने',
'statistics-views-total'       => 'सर्व दाखवते',
'statistics-views-total-desc'  => 'जे पाने यामध्दे नाहीत ते पाहा आनि खास पाने सामिला करु नका.',
'statistics-views-peredit'     => 'प्रति संपादनामागे पाहणे',
'statistics-users'             => 'नोंदणीकृत [[Special:ListUsers|सदस्य]]',
'statistics-users-active'      => 'कार्यरत सदस्य',
'statistics-users-active-desc' => '{{PLURAL:$1|शेवटच्या दिवसात|शेवटच्या $1 दिवसांत}} एकतरी संपादन केलेले सदस्य',
'statistics-mostpopular'       => 'सर्वाधिक बघितली जाणारी पाने',

'disambiguations'      => 'नि:संदिग्धकरण पृष्ठे',
'disambiguationspage'  => 'Template:नि:संदिग्धीकरण',
'disambiguations-text' => "निम्नलिखीत पाने एका '''नि:संदिग्धकरण पृष्ठास'''जोडली जातात. त्याऐवजी ती सुयोग्य विषयाशी जोडली जावयास हवीत.<br /> जर जर एखादे पान [[MediaWiki:Disambiguationspage]]पासून जोडलेला साचा वापरत असेल तर ते पान '''नि:संदिग्धकरण पृष्ठ''' गृहीत धरले जाते",

'doubleredirects'                   => 'दुहेरी-पुनर्निर्देशने',
'doubleredirectstext'               => 'हे पान अशा पानांची सूची पुरवते की जी पुर्ननिर्देशीत पाने दुसर्‍या पुर्ननिर्देशीत पानाकडे निर्देशीत झाली आहेत.प्रत्येक ओळीत पहिल्या आणि दुसर्‍या पुर्ननिर्देशनास दुवा दीला आहे सोबतच दुसरे पुर्ननिर्देशन ज्या पानाकडे पोहचते ते पण दिले आहे, जे की बरोबर असण्याची शक्यता आहे ,ते वस्तुत: पहिल्या पानापासूनचेही पुर्ननिर्देशन असावयास हवे.',
'double-redirect-fixed-move'        => '[[$1]] हलवले गेले आहे.
ते [[$2]] येथे निर्देशित होते.',
'double-redirect-fixed-maintenance' => '[[$1]] ते [[$2]] हे चुकीचे पुनर्निर्देशन नीट केले.',
'double-redirect-fixer'             => 'पुनर्निर्देशन नीट करणारा',

'brokenredirects'        => 'मोडके पुनर्निर्देशन',
'brokenredirectstext'    => 'खालील पुनर्निर्देशने अस्तित्वात नसलेली पाने जोडतात:',
'brokenredirects-edit'   => 'संपादन',
'brokenredirects-delete' => 'वगळा',

'withoutinterwiki'         => 'आंतरविकि दुवे नसलेली पाने',
'withoutinterwiki-summary' => 'खालील लेखात इतर भाषांमधील आवृत्तीला दुवे नाहीत:',
'withoutinterwiki-legend'  => 'उपपद',
'withoutinterwiki-submit'  => 'दाखवा',

'fewestrevisions' => 'सगळ्यात कमी बदल असलेले लेख',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|बाइट|बाइट}}',
'ncategories'             => '$1 {{PLURAL:$1|वर्ग|वर्ग}}',
'nlinks'                  => '$1 {{PLURAL:$1|दुवा|दुवे}}',
'nmembers'                => '$1 {{PLURAL:$1|सदस्य|सदस्य}}',
'nrevisions'              => '$1 {{PLURAL:$1|आवर्तन|आवर्तने}}',
'nviews'                  => '$1 {{PLURAL:$1|दृषीपथ|दृषीपथ}}',
'nimagelinks'             => '$1{{PLURAL:$1|पानावर|पानांवर}}',
'ntransclusions'          => '$1{{PLURAL:$1|पानावर|पानांवर}} वापर',
'specialpage-empty'       => 'या अहवालाकरिता(रिपोर्ट)कोणताही निकाल नाही.',
'lonelypages'             => 'पोरकी पाने',
'lonelypagestext'         => 'खालील पानांना {{SITENAME}}च्या इतर पानांकडून दूवा जोड झालेली नाही.',
'uncategorizedpages'      => 'अवर्गीकृत पाने',
'uncategorizedcategories' => 'अवर्गीकृत वर्ग',
'uncategorizedimages'     => 'अवर्गीकृत चित्रे',
'uncategorizedtemplates'  => 'अवर्गीकृत साचे',
'unusedcategories'        => 'न वापरलेले वर्ग',
'unusedimages'            => 'न वापरलेल्या संचिका',
'popularpages'            => 'प्रसिद्ध पाने',
'wantedcategories'        => 'पाहिजे असलेले वर्ग',
'wantedpages'             => 'पाहिजे असलेले लेख',
'wantedpages-badtitle'    => 'परिणामाच्या यादीत अवैध शीर्षक: $1',
'wantedfiles'             => 'पाहिजे असलेल्या संचिका',
'wantedtemplates'         => 'पाहिजे असलेले साचे',
'mostlinked'              => 'सर्वाधिक जोडलेली पाने',
'mostlinkedcategories'    => 'सर्वाधिक जोडलेले वर्ग',
'mostlinkedtemplates'     => 'सर्वाधिक जोडलेले साचे',
'mostcategories'          => 'सर्वाधिक वर्गीकृत पाने',
'mostimages'              => 'सर्वाधिक जोडलेली चित्रे',
'mostrevisions'           => 'सर्वाधिक बदललेले लेख',
'prefixindex'             => 'उपसर्ग असणार्‍या लेखांची यादी',
'shortpages'              => 'छोटी पाने',
'longpages'               => 'मोठी पाने',
'deadendpages'            => 'टोकाची पाने',
'deadendpagestext'        => 'या पानांवर या विकिवरील इतर कुठल्याही पानाला जोडणारा दुवा नाही.',
'protectedpages'          => 'सुरक्षित पाने',
'protectedpages-indef'    => 'फक्त अनंत काळासाठी सुरक्षित केलेले',
'protectedpages-cascade'  => 'केवळ एकामेकांवर अवलंबून कास्केडींग सुरक्षा (सुरक्षा शिडी)',
'protectedpagestext'      => 'खालील पाने स्थानांतरण किंवा संपादन यांपासुन सुरक्षित आहेत',
'protectedpagesempty'     => 'सध्या या नियमावलीने कोणतीही पाने सुरक्षीत केलेली नाहीत.',
'protectedtitles'         => 'सुरक्षीत शीर्षके',
'protectedtitlestext'     => 'पुढील शिर्षके बदल घडवण्यापासून सुरक्षीत आहेत.',
'protectedtitlesempty'    => 'या नियमावलीने सध्या कोणतीही शीर्षके सुरक्षीत केलेली नाहीत.',
'listusers'               => 'सदस्यांची यादी',
'listusers-editsonly'     => 'फक्त संपादनांसहित सदस्य दाखवा',
'listusers-creationsort'  => 'निर्मितीच्या तारखेप्रमाणे लावा',
'usereditcount'           => '$1 {{PLURAL:$1|संपादन|संपादने}}',
'usercreated'             => '{{GENDER:$3|बनावला}} या $1 अत $2',
'newpages'                => 'नवीन पाने',
'newpages-username'       => 'सदस्य नाव:',
'ancientpages'            => 'जुनी पाने',
'move'                    => 'स्थानांतरण',
'movethispage'            => 'हे पान स्थानांतरित करा',
'unusedimagestext'        => 'कृपया लक्षात घ्या की इतर संकेतस्थळे संचिकेशी थेट दुव्याने जोडल्या असू शकतात, त्यामुळे सक्रिय उपयोगात असून सुद्धा यादीत असू शकतात.',
'unusedcategoriestext'    => 'खालील वर्ग पाने अस्तित्वात आहेत पण कोणतेही लेख किंवा वर्ग त्यांचा वापर करत नाहीत.',
'notargettitle'           => 'कर्म(target) नाही',
'notargettext'            => 'ही क्रिया करण्यासाठी तुम्ही सदस्य किंवा पृष्ठ लिहिले नाही.',
'nopagetitle'             => 'असे लक्ष्य पान नाही',
'nopagetext'              => 'तुम्ही दिलेले लक्ष्य पान अस्तित्वात नाही.',
'pager-newer-n'           => '{{PLURAL:$1|नवे 1|नवे $1}}',
'pager-older-n'           => '{{PLURAL:$1|जुने 1|जुने $1}}',
'suppress'                => 'झापडबंद',
'querypage-disabled'      => 'हे विषेश पान कार्यमापन (performance) करणांमुळे प्रतिबंधित करण्यात आले आहे.',

# Book sources
'booksources'               => 'पुस्तक स्रोत',
'booksources-search-legend' => 'पुस्तक स्रोत शोधा',
'booksources-go'            => 'चला',
'booksources-text'          => 'खालील यादीत नवी आणिजुनी पुस्तके विकणार्‍या संकेतस्थळाचे दुवे आहेत,आणि त्यात कदाचित आपण शोधू पहात असलेल्या पुस्तकाची अधिक माहिती असेल:',
'booksources-invalid-isbn'  => 'दिलेला आयएसबीएन वैध नाही; मूळ स्रोतातून उतरवताना झालेल्या चुकांचे निरसन करा.',

# Special:Log
'specialloguserlabel'  => 'कार्यकर्ता:',
'speciallogtitlelabel' => 'उद्दिष्ट (लक्ष):',
'log'                  => 'नोंदी',
'all-logs-page'        => 'सर्व नोंदी',
'alllogstext'          => '{{SITENAME}}च्या सर्व नोंदीचे एकत्र दर्शन.नोंद प्रकार, सदस्यनाव किंवा बाधीत पान निवडून तुम्ही तुमचे दृश्यपान मर्यादीत करू शकता.',
'logempty'             => 'नोंदीत अशी बाब नाही.',
'log-title-wildcard'   => 'या मजकुरापासून सुरू होणारी शिर्षके शोधा.',

# Special:AllPages
'allpages'          => 'सर्व पृष्ठे',
'alphaindexline'    => '$1 पासून $2 पर्यंत',
'nextpage'          => 'पुढील पान ($1)',
'prevpage'          => 'मागील पान ($1)',
'allpagesfrom'      => 'पुढील शब्दाने सुरू होणारे लेख दाखवा:',
'allpagesto'        => 'इथे संपणारी पाने दाखवा:',
'allarticles'       => 'सगळे लेख',
'allinnamespace'    => 'सर्व पाने ($1 नामविश्व)',
'allnotinnamespace' => 'सर्व पाने ($1 नामविश्वात नसलेली)',
'allpagesprev'      => 'मागील',
'allpagesnext'      => 'पुढील',
'allpagessubmit'    => 'चला',
'allpagesprefix'    => 'पुढील शब्दाने सुरू होणारी पाने दाखवा:',
'allpagesbadtitle'  => 'दिलेले शीर्षक चुकीचे किंवा आंतरभाषीय किंवा आंतरविकि शब्दाने सुरू होणारे होते. त्यात एक किंवा अधिक शीर्षकात न वापरता येणारी अक्षरे असावीत.',
'allpages-bad-ns'   => '{{SITENAME}}मध्ये "$1" हे नामविश्व नाही.',

# Special:Categories
'categories'                    => 'वर्ग',
'categoriespagetext'            => 'विकिवर खालील वर्ग {{PLURAL:$1|आहे|आहेत}}.
[[Special:UnusedCategories|न वापरलेले वर्ग]] येथे दाखवलेले नाहीत.
हेही पहा: [[Special:WantedCategories|पाहिजे असलेले वर्ग]].',
'categoriesfrom'                => 'या शब्दापासून सुरू होणारे वर्ग दाखवा:',
'special-categories-sort-count' => 'क्रमानुसार लावा',
'special-categories-sort-abc'   => 'अक्षरांप्रमाणे लावा',

# Special:DeletedContributions
'deletedcontributions'             => 'वगळलेली सदस्य संपादने',
'deletedcontributions-title'       => 'वगळलेली सदस्य संपादने',
'sp-deletedcontributions-contribs' => 'संपादने',

# Special:LinkSearch
'linksearch'       => 'बाह्य दुवे शोध',
'linksearch-pat'   => 'शोधण्याचा मजकूर:',
'linksearch-ns'    => 'नामविश्व:',
'linksearch-ok'    => 'शोध',
'linksearch-text'  => '"*.wikipedia.org" सारखी वाईल्डकार्ड्स वापरायला परवानगी आहे.
किमान एक उच्च-स्तरिय डोमेन (top-level domain) गरजेचे आहे.<br />
पुढील प्रोटोकॉल्समध्ये चालेल: <tt>$1</tt> (तुमच्या शोधामध्ये या पैकी कुठलेही टाकू नयेत).',
'linksearch-line'  => '$2 मधून $1 जोडलेले आहे',
'linksearch-error' => 'वाईल्डकार्ड्स होस्ट नावाच्या फक्त सुरवातीलाच येऊ शकतात.',

# Special:ListUsers
'listusersfrom'      => 'पुढील शब्दापासुन सुरू होणारे सदस्य दाखवा:',
'listusers-submit'   => 'दाखवा',
'listusers-noresult' => 'एकही सदस्य सापडला नाही.',
'listusers-blocked'  => '(खंडित)',

# Special:ActiveUsers
'activeusers'            => 'कार्यरत सदस्यांची यादी',
'activeusers-intro'      => '$1 {{PLURAL:$1|day|days}} मधे शेवटी काम केलेल्या सदस्यांची यादी येथे मिळेल',
'activeusers-count'      => 'शेवटच्या {{PLURAL:$3|दिवसात|$3 दिवसांत}} $1 {{PLURAL:$1|संपादन|संपादने}}',
'activeusers-from'       => 'पुढील शब्दापासुन सुरू होणारे सदस्य दाखवा:',
'activeusers-hidebots'   => 'सांगकामे लपवा',
'activeusers-hidesysops' => 'प्रचालक लपवा',
'activeusers-noresult'   => 'एकही सदस्य सापडला नाही.',

# Special:Log/newusers
'newuserlogpage'     => 'नवीन सदस्यांची नोंद',
'newuserlogpagetext' => 'ही नवीन सदस्यांची नोंद यादी आहे.',

# Special:ListGroupRights
'listgrouprights'                      => 'सदस्य गट अधिकार',
'listgrouprights-summary'              => 'खाली या विकिवर दिलेली सदस्य गटांची यादी त्यांच्या अधिकारांसकट दर्शविलेली आहे. प्रत्येकाच्या अधिकारांची अधिक माहिती [[{{MediaWiki:Listgrouprights-helppage}}|इथे]] दिलेली आहे.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">दिलेले अधिकार</span>
* <span class="listgrouprights-revoked">रद्द अधिकार</span>',
'listgrouprights-group'                => 'गट',
'listgrouprights-rights'               => 'अधिकार',
'listgrouprights-helppage'             => 'Help:गट अधिकार',
'listgrouprights-members'              => '(सदस्यांची यादी)',
'listgrouprights-addgroup'             => '{{PLURAL:$2|गट|गट}} वाढवा: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2|गट|गट}} वगळा: $1',
'listgrouprights-addgroup-all'         => 'सर्व गट वाढवा',
'listgrouprights-removegroup-all'      => 'सर्व समूह काढून टाका',
'listgrouprights-addgroup-self'        => 'स्वतःच्या खात्यात हे {{PLURAL:$2|गट|गट}} मिळवा: $1',
'listgrouprights-removegroup-self'     => 'स्वतःच्या खात्यातून हे {{PLURAL:$2|गट|गट}} वगळा: $1',
'listgrouprights-addgroup-self-all'    => 'सर्व समूह स्वतःच्या खात्यात मिळवा',
'listgrouprights-removegroup-self-all' => 'सर्व समूह स्वतःच्या खात्यातून काढून टाका',

# E-mail user
'mailnologin'          => 'पाठविण्याचा पत्ता नाही',
'mailnologintext'      => 'इतर सदस्यांना विपत्र(ईमेल) पाठवण्याकरिता तुम्ही [[Special:UserLogin|प्रवेश केलेला]] असणे आणि  प्रमाणित (इमेल) पत्ता तुमच्या [[Special:Preferences|पसंतीत]] नमुद असणे आवश्यक आहे.',
'emailuser'            => 'या सदस्याला इमेल पाठवा',
'emailpage'            => 'विपत्र (ईमेल) उपयोगकर्ता',
'emailpagetext'        => 'जर या सदस्याने प्रमाणित विपत्र (ईमेल)पत्ता तीच्या अथवा त्याच्या सदस्य पसंतीत नमुद केला असेल,तर खालील सारणी तुम्हाला एक(च) संदेश पाठवेल.तुम्ही तुमच्या [[Special:Preferences|सदस्य पसंतीत]] नमुद केलेला विपत्र पत्ता "कडून" पत्त्यात येईल म्हणजे  प्राप्तकरता आपल्याला उत्तर देऊ शकेल.',
'usermailererror'      => 'पत्र बाब त्रूटी वापस पाठवली:',
'defemailsubject'      => '{{SITENAME}} "$1" सदस्याकडून विपत्र',
'usermaildisabled'     => 'सदस्य विपत्र निष्क्रीय आहे',
'usermaildisabledtext' => 'या विकीवर तुम्हाला इतर सदस्यांना विपत्रे पाठवता येत नाहीत',
'noemailtitle'         => 'विपत्र पत्ता नाही',
'noemailtext'          => 'या सदस्याने वैध विपत्र पत्ता नमूद केलेला नाही.',
'nowikiemailtitle'     => 'विपत्र प्रतिबंधित',
'nowikiemailtext'      => 'हा प्रयोक्ता अन्य प्रयोक्ता कडुन  ई-मेल घेऊ ईच्छित नाही.',
'emailnotarget'        => 'प्राप्तकर्ता करीता अस्तित्वात नसलेले  किंवा अवैध सदस्य',
'emailtarget'          => 'प्राप्तकर्ता प्रयोक्ताचे नांव टाका.',
'emailusername'        => 'सदस्यनाम:',
'emailusernamesubmit'  => 'पाठवा',
'email-legend'         => 'ईमेल अन्य सदस्याला पाठवा',
'emailfrom'            => 'प्रेषक',
'emailto'              => 'प्रति:',
'emailsubject'         => 'विषय:',
'emailmessage'         => 'संदेश:',
'emailsend'            => 'पाठवा',
'emailccme'            => 'माझ्या संदेशाची मला विपत्र प्रत पाठवा.',
'emailccsubject'       => 'तुमच्या विपत्राची प्रत कडे $1: $2',
'emailsent'            => 'विपत्र पाठवले',
'emailsenttext'        => 'तुमचा विपत्र संदेश पाठवण्यात आला आहे.',
'emailuserfooter'      => 'हे विपत्र $1 ने $2 ला {{SITENAME}} वरील "सदस्यास विपत्र पाठवा" वापरुन पाठवले आहे.',

# User Messenger
'usermessage-summary' => 'प्रणाली संदेश देत आहे.',
'usermessage-editor'  => 'प्रणाली संदेशवाहक',

# Watchlist
'watchlist'            => 'माझी पहार्‍याची सूची',
'mywatchlist'          => 'माझी पहार्‍याची सूची',
'watchlistfor2'        => '$1 $2 साठी',
'nowatchlist'          => 'तुमची पहार्‍याची सूची रिकामी आहे.',
'watchlistanontext'    => 'तुमच्या पहार्‍याच्या सूचीतील बाबी पाहण्याकरता किंवा संपादित करण्याकरता, कृपया $1.',
'watchnologin'         => 'प्रवेश केलेला नाही',
'watchnologintext'     => 'तुमची पहार्‍याची सूची बदलावयाची असेल तर तुम्ही [[Special:UserLogin|प्रवेश केलेला]] असलाच पाहीजे.',
'addwatch'             => 'पहार्‍याच्या सूचीत टाका',
'addedwatchtext'       => '"[[:$1]]"  हे पान तुमच्या  [[Special:Watchlist|पहार्‍याच्या सूचीत]] टाकले आहे. या पानावरील तसेच त्याच्या चर्चा पानावरील पुढील बदल येथे दाखवले जातील, आणि   [[Special:RecentChanges|अलीकडील बदलांमध्ये]] पान ठळक दिसेल.

पहार्‍याच्या सूचीतून पान काढायचे असेल तर "पहारा काढा" वर टिचकी द्या.',
'removewatch'          => 'पहार्‍याच्या सूचीतून वगळा',
'removedwatchtext'     => '"[[:$1]]" पान तुमच्या [[Special:Watchlist|पहार्‍याच्या सूची]]तून वगळण्यात आले आहे.',
'watch'                => 'पहारा',
'watchthispage'        => 'या पानावर बदलांसाठी लक्ष ठेवा.',
'unwatch'              => 'पहारा काढा',
'unwatchthispage'      => 'पहारा काढून टाका',
'notanarticle'         => 'मजकुर विरहीत पान',
'notvisiblerev'        => 'आवृत्ती वगळण्यात आलेली आहे',
'watchnochange'        => 'प्रदर्शित कालावाधीत, तुम्ही पहारा ठेवलेली कोणतीही बाब संपादीत झाली नाही.',
'watchlist-details'    => '{{PLURAL:$1|$1 पान|$1 पाने}} पहार्‍याच्या सूचीत,चर्चा पाने मोजलेली नाहीत.',
'wlheader-enotif'      => '* विपत्र सूचना सुविधा ऊपलब्ध केली.',
'wlheader-showupdated' => "* तुम्ही पानांस दिलेल्या शेवटच्या भेटी पासून बदललेली पाने '''ठळक''' दाखवली आहेत.",
'watchmethod-recent'   => 'पहार्‍यातील पानांकरिता अलिकडील बदलांचा तपास',
'watchmethod-list'     => 'अलिकडील बदलांकरिता पहार्‍यातील पानांचा तपास',
'watchlistcontains'    => 'तुमचा $1 {{PLURAL:$1|पानावर|पानांवर}} पहारा आहे.',
'iteminvalidname'      => "'$1'बाबीस समस्या, अमान्य नाव...",
'wlnote'               => "खाली $3, $4 पर्यंतचे गेल्या {{PLURAL:$2| '''१''' तासातील|'''$2''' तासातील}} {{PLURAL:$1|शेवटचा बदल दिला आहे|शेवटाचे '''$1'''बदल दिले आहेत}}.",
'wlshowlast'           => 'मागील $1 तास $2 दिवस $3 पहा',
'watchlist-options'    => 'पहार्‍याची सूची पर्याय',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'पाहताहे...',
'unwatching'     => 'पहारा काढत आहे...',
'watcherrortext' => '$1 साठीच्या तुमच्या पहाऱ्याच्या सूचीतील मांडणीत (watchlist settings) बदल करताना त्रुटी आली.',

'enotif_mailer'                => '{{SITENAME}} सूचना विपत्र',
'enotif_reset'                 => 'सर्व पानास भेट दिल्याचे नमुद करा',
'enotif_newpagetext'           => 'हे नवीन पान आहे.',
'enotif_impersonal_salutation' => '{{SITENAME}} सदस्य',
'changed'                      => 'बदलले',
'created'                      => 'तयार केले',
'enotif_subject'               => '{{SITENAME}} पान $PAGETITLE $PAGEEDITOR ने $CHANGEDORCREATED आहे',
'enotif_lastvisited'           => 'तुमच्या शेवटच्या भेटीनंतरचे बदल बघणयासाठी पहा - $1.',
'enotif_lastdiff'              => 'हा बदल पहाण्याकरिता $1 पहा.',
'enotif_anon_editor'           => 'अनामिक उपयोगकर्ता $1',
'enotif_body'                  => 'प्रिय $WATCHINGUSERNAME,

The {{SITENAME}}चे  $PAGETITLE पान $PAGEEDITORने $PAGEEDITDATE तारखेस $CHANGEDORCREATED आहे ,सध्याची आवृत्ती पाहण्यासाठी [$PAGETITLE_URL येथे टिचकी मारा].

$NEWPAGE

संपादकाचा आढावा : $PAGESUMMARY $PAGEMINOREDIT

संपादकास संपर्क करा :
विपत्र: $PAGEEDITOR_EMAIL
विकि: $PAGEEDITOR_WIKI

तुम्ही पानास भेट देत नाही तोपर्यंत पुढे होणार्‍या बदलांची इतर कोणतीही वेगळी सूचना नसेल. तुम्ही पहाऱ्याची सूचीतील पहारा ठेवलेल्या पानांकरिताच्या सूचना पताकांचे पुर्नयोजन करु शकता.

तुमची मैत्रीपूर्ण {{SITENAME}} सुचना प्रणाली

--

तुमचे पहार्‍यातील पानांची मांडणावळ (कोंदण) बदलू शकता,{{canonicalurl:{{#special:EditWatchlist}}}}ला भेट द्या

पुढील सहाय्य आणि प्रतिक्रीया:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'पान वगळा',
'confirm'                => 'निश्चीत',
'excontent'              => "मजकूर होता: '$1'",
'excontentauthor'        => "मजकूर होता: '$1' (आणि फक्त '[[Special:Contributions/$2|$2]]' यांचे योगदान होते.)",
'exbeforeblank'          => "वगळण्यापूर्वीचा मजकूर पुढीलप्रमाणे: '$1'",
'exblank'                => 'पान रिकामे होते',
'delete-confirm'         => '"$1" वगळा',
'delete-legend'          => 'वगळा',
'historywarning'         => 'सूचना: तुम्ही वगळत असलेल्या पानाला $1 {{PLURAL:$1|आवर्तनाचा|आवर्तनांचा}} इतिहास आहे:',
'confirmdeletetext'      => 'तुम्ही एक लेख त्याच्या सर्व इतिहासासोबत वगळण्याच्या तयारीत आहात.
कृपया तुम्ही करत असलेली कृती ही मीडियाविकीच्या [[{{MediaWiki:Policy-url}}|नीतीनुसार]] आहे ह्याची खात्री करा. तसेच तुम्ही करीत असलेल्या कृतीचे परीणाम कृती करण्यापूर्वी जाणून घ्या.',
'actioncomplete'         => 'काम पूर्ण',
'actionfailed'           => 'कृती अयशस्वी झाली',
'deletedtext'            => '"$1" हा लेख वगळला. अलीकडे वगळलेले लेख पाहण्यासाठी $2 पहा.',
'dellogpage'             => 'वगळल्याची नोंद',
'dellogpagetext'         => 'नुकत्याच वगळलेल्या पानांची यादी खाली आहे.',
'deletionlog'            => 'वगळल्याची नोंद',
'reverted'               => 'जुन्या आवृत्तीकडे पूर्वपदास नेले',
'deletecomment'          => 'कारण:',
'deleteotherreason'      => 'दुसरे/अतिरिक्त कारण:',
'deletereasonotherlist'  => 'दुसरे कारण',
'deletereason-dropdown'  => '* वगळण्याची सामान्य कारणे
** लेखकाची(लेखिकेची) विनंती
** प्रताधिकार उल्लंघन
** उत्पात',
'delete-edit-reasonlist' => 'वगळण्याची कारणे संपादित करा',
'delete-toobig'          => 'या पानाला खूप मोठी इतिहास यादी आहे, तसेच हे पान $1 {{PLURAL:$1|पेक्षा|पेक्षा}}पेक्षा जास्त वेळा बदलण्यात आलेले आहे. अशी पाने वगळणे हे {{SITENAME}} ला धोकादायक ठरू नये म्हणून शक्य केलेले नाही.',
'delete-warning-toobig'  => 'या पानाला खूप मोठी इतिहास यादी आहे, तसेच हे पान $1 {{PLURAL:$1|पेक्षा|पेक्षा}} पेक्षा जास्त वेळा बदलण्यात आलेले आहे.
अशी पाने वगळणे हे {{SITENAME}} ला धोकादायक ठरू शकते;
कृपया काळजीपूर्वक हे पान वगळा.',

# Rollback
'rollback'          => 'बदल वेगात माघारी न्या',
'rollback_short'    => 'द्रुतमाघार',
'rollbacklink'      => 'द्रुतमाघार',
'rollbackfailed'    => 'द्रूतमाघार फसली',
'cantrollback'      => 'जुन्या आवृत्तीकडे परतवता येत नाही; शेवटचा संपादक या पानाचा एकमात्र लेखक आहे.',
'alreadyrolled'     => '[[User:$2|$2]] ([[User talk:$2|Talk]] [[Special:Contributions/$2|{{int:contribslink}}]])चे शेवटाचे [[:$1]]वे संपादन माघारी परतवता येत नाही; पान आधीच कुणी माघारी परतवले आहे किंवा संपादीत केले आहे.

शेवटचे संपादन [[User:$3|$3]] ([[User talk:$3|Talk]] [[Special:Contributions/$3|{{int:contribslink}}]])-चे होते.',
'editcomment'       => "संपादन सारांश \"''\$1''\" होता.",
'revertpage'        => '[[Special:Contributions/$2|$2]] ([[User talk:$2|चर्चा]]) यांनी केलेले बदल [[User:$1|$1]] यांच्या आवृत्तीकडे पूर्वपदास नेले.',
'revertpage-nouser' => '(सदस्यनाम काढून टाकले) यांचे बदल उलटवुन [[User:$1|$1]] यांच्या मागील आवृत्तीस न्या.',
'rollback-success'  => '$1 ने उलटवलेली संपादने;$2 च्या आवृत्तीस परत नेली.',

# Edit tokens
'sessionfailure-title' => 'सत्र त्रुटी',
'sessionfailure'       => 'तुमच्या दाखल सत्रात काही समस्या दिसते;सत्र अपहारणा पासून काळजी घेण्याच्या दृष्टीने ही कृती रद्द केली गेली आहे.कपया आपल्या विचरकाच्या "back" कळीवर टिचकी मारा आणि तुम्ही ज्या पानावरून आला ते पुन्हा चढवा,आणि प्रत प्रयत्न करा.',

# Protect
'protectlogpage'              => 'सुरक्षा नोंदी',
'protectlogtext'              => 'पानांना लावलेल्या ताळ्यांची आणि ताळे उघडण्याबद्दलच्या पानाची खाली सूची दिली आहे.सध्याच्या सुरक्षीत पानांबद्दलच्या माहितीकरिता [[Special:ProtectedPages|सुरक्षीत पानांची सूची]] पहा.',
'protectedarticle'            => '"[[$1]]" सुरक्षित केला',
'modifiedarticleprotection'   => '"[[$1]]"करिता सुरक्षापातळी बदलली',
'unprotectedarticle'          => '"[[$1]]" असुरक्षित केला.',
'movedarticleprotection'      => 'सुरक्षापातळी "[[$2]]" येथून "[[$1]]" येथे हलवली.',
'protect-title'               => '"$1" सुरक्षित करत आहे',
'protect-title-notallowed'    => '"$1" ची सुरक्षा पातळी पहा',
'prot_1movedto2'              => '"[[$1]]" हे पान "[[$2]]" मथळ्याखाली स्थानांतरित केले.',
'protect-badnamespace-title'  => 'असुरक्षणीय नामविश्व',
'protect-badnamespace-text'   => 'या नामविश्वातील पाने सुरक्षीत करता येत नाहीत',
'protect-legend'              => 'सुरक्षापातळीतील बदल निर्धारित करा',
'protectcomment'              => 'कारण:',
'protectexpiry'               => 'संपण्याचा कालावधी:',
'protect_expiry_invalid'      => 'संपण्याचा कालावधी चुकीचा आहे.',
'protect_expiry_old'          => 'संपण्याचा कालावधी उलटून गेलेला आहे.',
'protect-unchain-permissions' => 'पुढील संरक्षित विकल्प उघडा.',
'protect-text'                => "'''$1''' या पानाची सुरक्षापातळी तुम्ही इथे पाहू शकता अथवा बदलू शकता.",
'protect-locked-blocked'      => "तुम्ही प्रतिबंधीत असताना सुरक्षा पातळी बदलू शकत नाही.येथे '''$1''' पानाकरिता सध्याची मांडणावळ आहे:",
'protect-locked-dblock'       => "विदागारास ताळे लागलेले असताना सुरक्षा पातळी बदलता येत नाही.येथे '''$1''' पानाकरिता सध्याची मांडणावळ आहे:",
'protect-locked-access'       => "तुम्हाला या पानाची सुरक्षा पातळी बदलण्याचे अधिकार नाहीत.
'''$1''' या पानाची सुरक्षा पातळी पुढीलप्रमाणे:",
'protect-cascadeon'           => 'हे पान सध्या सुरक्षित आहे कारण ते {{PLURAL:$1|या पानाच्या|या पानांच्या}} सुरक्षा शिडीवर आहे. तुम्ही या पानाची सुरक्षा पातळी बदलू शकता, पण त्याने सुरक्षाशिडी मध्ये बदल होणार नाहीत.',
'protect-default'             => 'सर्व सदस्यांना परवानगी द्या',
'protect-fallback'            => '"$1" परवानगीची गरज',
'protect-level-autoconfirmed' => 'नवीन व अनामिक सदस्यांना ब्लॉक करा',
'protect-level-sysop'         => 'केवळ प्रचालकांसाठी',
'protect-summary-cascade'     => 'शिडी',
'protect-expiring'            => '$1 (UTC) ला संपेल',
'protect-expiring-local'      => '$1 ला सम्पते',
'protect-expiry-indefinite'   => 'अनंत',
'protect-cascade'             => 'या पानात असलेली पाने सुरक्षित करा (सुरक्षा शिडी)',
'protect-cantedit'            => 'तुम्ही या पानाची सुरक्षा पातळी बदलू शकत नाही कारण तुम्हाला तसे करण्याची परवानगी नाही.',
'protect-othertime'           => 'इतर वेळ:',
'protect-othertime-op'        => 'इतर वेळ',
'protect-existing-expiry'     => 'शेवट दिनांक: $3, $2',
'protect-otherreason'         => 'इतर / अतिरिक्त कारण:',
'protect-otherreason-op'      => 'दुसरे कारण',
'protect-dropdown'            => '* सुरक्षीत करण्याची सामान्य कारणे
** अती उपद्रव
** अती उत्पात
** अनुत्पादक संपादन युद्ध
** अत्यधिक वाचकभेटींचे पान',
'protect-edit-reasonlist'     => 'सुरक्षेची कारणे संपादित करा',
'protect-expiry-options'      => '१ तास:1 hour,१ दिवस:1 day,१ आठवडा:1 week,२ आठवडे:2 weeks,१ महिना:1 month,३ महिने:3 months,६ महिने:6 months,१ वर्ष:1 year,अनंत:infinite',
'restriction-type'            => 'परवानगी:',
'restriction-level'           => 'सुरक्षापातळी:',
'minimum-size'                => 'किमान आकार',
'maximum-size'                => 'महत्तम आकार:',
'pagesize'                    => '(बाइट)',

# Restrictions (nouns)
'restriction-edit'   => 'संपादन',
'restriction-move'   => 'स्थानांतरण',
'restriction-create' => 'निर्मित करा',
'restriction-upload' => 'चढवा',

# Restriction levels
'restriction-level-sysop'         => 'पूर्ण सूरक्षीत',
'restriction-level-autoconfirmed' => 'अर्ध सुरक्षीत',
'restriction-level-all'           => 'कोणतीही पातळी',

# Undelete
'undelete'                     => 'वगळलेली पाने पहा',
'undeletepage'                 => 'वगळलेली पाने पहा आणि पुनर्स्थापित करा',
'undeletepagetitle'            => "'''खाली [[:$1]] च्या वगळलेल्या आवृत्त्या समाविष्ट केलेल्या आहेत'''.",
'viewdeletedpage'              => 'काढून टाकलेले लेख पहा',
'undeletepagetext'             => 'खालील {{PLURAL:$1|पान वगळले आहे तरीसुद्धा विदागारात जतन आहे आणि पुर्न्स्थापित करणे शक्य आहे|$1 पाने वगळली आहेत तरी सुद्धा विदागारात जतन आहेत आणि पुर्न्स्थापित करणे शक्य आहेत}}. विदागारातील साठवण ठराविक कालावधीने स्वच्छ करता येते.',
'undelete-fieldset-title'      => 'आवर्तने पुनर्स्थापित करा',
'undeleteextrahelp'            => "संपूर्ण पान पुनर्स्थापित करण्याकरिता,सारे रकाने रिकामे ठेवा आणि '''''पुनर्स्थापन'''''वर टिचकी मारा. निवडक पुनर्स्थापन करण्याकरिता, ज्या आवर्तनांचे पुनर्स्थापन करावयाचे त्यांचे रकाने निवडा , आणि '''''पुनर्स्थापन'''''वर टिचकी मारा. '''''पुनर्योजन ''''' वर टिचकी मारल्यास सारे रकाने आणि प्रतिक्रीया खिडकी रिकामी होईल.",
'undeleterevisions'            => '$1 {{PLURAL:$1|आवर्तन|आवर्तने}}विदागारात संचीत',
'undeletehistory'              => 'जर तुम्ही पान पुनर्स्थापित केले तर ,सारी आवर्तने इतिहासात पुनर्स्थापित होतील.
वगळल्या पासून त्याच नावाचे नवे पान तयार केले गेले असेले तर, पुनर्स्थापित आवर्तने पाठीमागील इतिहासात दिसतील. पुनर्स्थापना नंतर संचिकांच्या आवर्तनांवरील बंधने गळून पडतील याची नोंद घ्या.',
'undeleterevdel'               => 'पृष्ठ पानाचे आवर्तन अर्धवट वगळले जाणार असेल तर पुनर्स्थापनाची कृती केली जाणार नाही.
अशा प्रसंगी, तुम्ही अगदी अलिकडील वगळलेली आवर्तने अनचेक किंवा अनहाईड केलीच पाहिजे.',
'undeletehistorynoadmin'       => 'हे पान वगळले गेले आहे.वगळण्याचे कारण खालील आढाव्यात,वगळण्यापूर्वी संपादीत करणार्‍या संपादकांच्या माहिती सोबत,दाखवले आहे. वगळलेल्या आवर्त्नांचा नेमका मजकुर केवळ प्रचालकांना उपलब्ध असेल.',
'undelete-revision'            => '$1चे($4चे, $5 येथील) आवर्तन $3 ने वगळले:',
'undeleterevision-missing'     => 'अयोग्य अथवा नसापडणारे आवर्तन. तुमचा दुवा कदाचित चूकीचा असेल, किंवा आवर्तन पुनर्स्थापित केले गेले असेल किंवा विदागारातून वगळले असेल.',
'undelete-nodiff'              => 'पूर्वीचे कोणतेही आवर्तन आढळले नाही.',
'undeletebtn'                  => 'वगळण्याची क्रिया रद्द करा',
'undeletelink'                 => 'पहा/पुनर्स्थापित करा',
'undeleteviewlink'             => 'पहा',
'undeletereset'                => 'पूर्ववत',
'undeleteinvert'               => 'निवड उलट करा',
'undeletecomment'              => 'प्रतिक्रिया:',
'undeletedrevisions'           => '{{PLURAL:$1|1 आवर्तन|$1 आवर्तने}} पुनर्स्थापित',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 आवर्तन|$1 आवर्तने}}आणि {{PLURAL:$2|1 संचिका|$2 संचिका}} पुनर्स्थापित',
'undeletedfiles'               => '{{PLURAL:$1|1 संचिका|$1 संचिका}} पुनर्स्थापित',
'cannotundelete'               => 'वगळणे उलटवणे फसले; इतर कुणी तुमच्या आधी वगळणे उलटवले असु शकते.',
'undeletedpage'                => "'''$1ला पुनर्स्थापित केले'''

अलिकडिल वगळलेल्या आणि पुनर्स्थापितांच्या नोंदीकरिता [[Special:Log/delete|वगळल्याच्या नोंदी]] पहा .",
'undelete-header'              => 'अलिकडील वगळलेल्या पानांकरिता [[Special:Log/delete|वगळलेल्या नोंदी]] पहा.',
'undelete-search-title'        => 'वगळलेली पाने शोधा',
'undelete-search-box'          => 'वगळलेली पाने शोधा',
'undelete-search-prefix'       => 'पासून सूरू होणारी पाने दाखवा:',
'undelete-search-submit'       => 'शोध',
'undelete-no-results'          => 'वगळलेल्यांच्या विदांमध्ये जुळणारी पाने सापडली नाहीत.',
'undelete-filename-mismatch'   => '$1 वेळेचे, वगळलेल्या संचिकेचे आवर्तन उलटवता येत नाही: नजुळणारे संचिकानाव',
'undelete-bad-store-key'       => '$1 वेळ दिलेली संचिका आवर्तन पुनर्स्थापित करता येत नाही:संचिका वगळण्यापूर्वी पासून मिळाली नव्हती.',
'undelete-cleanup-error'       => 'न वापरलेली विदा संचिका "$1" वगळताना त्रूटी दाखवते.',
'undelete-missing-filearchive' => 'संचिका विदास्मृती ID $1 पुनर्स्थापित करू शकत नाही कारण ती विदागारात उपलब्ध नाही. ती आधीच पुनर्स्थापित केली असण्याची शक्यता सुद्धा असू शकते.',
'undelete-error'               => 'जर पाना काढुन नाही टाकले तर पान शीर्षक',
'undelete-error-short'         => 'संचिकेचे वगळणे उलटवताना त्रूटी: $1',
'undelete-error-long'          => 'संचिकेचे वगळणे उलटवताना त्रूटींचा अडथळा आला:

$1',
'undelete-show-file-confirm'   => 'तुम्ही "<nowiki>$1</nowiki>" या संचिकेचे $2 येथून $3 वेळी असलेले आवर्तन नक्की पहाणार आहात?',
'undelete-show-file-submit'    => 'होय',

# Namespace form on various pages
'namespace'                     => 'नामविश्व:',
'invert'                        => 'निवडीचा क्रम उलटा करा',
'tooltip-invert'                => 'निवडलेल्या नामविश्वातील (आणि तसे निवडल्यास संबंधीत नामविश्वातील)  पानांचे बदल  अदृष्य करण्या साटी टिचकी मारा',
'namespace_association'         => 'सहभागी नामविश्वे',
'tooltip-namespace_association' => 'निवडलेल्या नामविश्वासंबधीत विषय अथवा चर्चा नामविश्वसुद्धा आंतर्भूत करण्याकरिता हा बॉक्स टिचकवून चिह्नित करा',
'blanknamespace'                => '(मुख्य)',

# Contributions
'contributions'       => 'सदस्याचे योगदान',
'contributions-title' => '$1 साठी सदस्याचे योगदान',
'mycontris'           => 'माझे योगदान',
'contribsub2'         => '$1 ($2) साठी',
'nocontribs'          => 'या मानदंडाशी जुळणारे बदल सापडले नाहीत.',
'uctop'               => ' (वर)',
'month'               => 'या महिन्यापासून (आणि पूर्वीचे):',
'year'                => 'या वर्षापासून (आणि पूर्वीचे):',

'sp-contributions-newbies'             => 'केवळ नवीन सदस्य खात्यांचे योगदान दाखवा',
'sp-contributions-newbies-sub'         => 'नवशिक्यांसाठी',
'sp-contributions-newbies-title'       => 'नवीन खात्यांसाठी सदस्य योगदान',
'sp-contributions-blocklog'            => 'ब्लॉक यादी',
'sp-contributions-deleted'             => 'वगळलेली सदस्य संपादने',
'sp-contributions-uploads'             => 'चढवलेल्या संचिका',
'sp-contributions-logs'                => 'नोंदी',
'sp-contributions-talk'                => 'चर्चा',
'sp-contributions-userrights'          => 'सदस्य अधिकार व्यवस्थापन',
'sp-contributions-blocked-notice'      => 'हा सदस्य सध्या प्रतिबंधित आहे.
सर्वांत नवीन प्रतिबंधन यादी खाली संदर्भासाठी दिली आहे:',
'sp-contributions-blocked-notice-anon' => 'हा अंकपत्ता सध्या प्रतिबंधित आहे.
सर्वांत नवीन प्रतिबंधन यादी खाली संदर्भासाठी दिली आहे:',
'sp-contributions-search'              => 'योगदान शोधयंत्र',
'sp-contributions-username'            => 'आंतरजाल अंकपत्ता किंवा सदस्यनाम:',
'sp-contributions-toponly'             => 'नवीन आवर्तने असलेली संपादने दाखवा',
'sp-contributions-submit'              => 'शोध',

# What links here
'whatlinkshere'            => 'येथे काय जोडले आहे',
'whatlinkshere-title'      => '"$1" ला जोडलेली पाने',
'whatlinkshere-page'       => 'पान:',
'linkshere'                => "खालील लेख '''[[:$1]]''' या निर्देशित पानाशी जोडले आहेत:",
'nolinkshere'              => "'''[[:$1]]''' इथे काहीही जोडलेले नाही.",
'nolinkshere-ns'           => "निवडलेल्या नामविश्वातील कोणतीही पाने '''[[:$1]]'''ला दुवा देत नाहीत .",
'isredirect'               => 'पुनर्निर्देशित पान',
'istemplate'               => 'मिळवा',
'isimage'                  => 'चित्र दुवा',
'whatlinkshere-prev'       => '{{PLURAL:$1|पूर्वीचा|पूर्वीचे $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|पुढील|पुढील $1}}',
'whatlinkshere-links'      => '← दुवे',
'whatlinkshere-hideredirs' => '$1 पुनर्निर्देशने',
'whatlinkshere-hidetrans'  => '$1 ट्रान्स्क्ल्युजन्स',
'whatlinkshere-hidelinks'  => '$1 दुवे',
'whatlinkshere-hideimages' => '$1 चित्र दुवे',
'whatlinkshere-filters'    => 'फिल्टर्स',

# Block/unblock
'autoblockid'                     => '#$1ला स्वयंचलितपणे प्रतिबंधित करा',
'block'                           => 'सदस्यास प्रतिबंध करा',
'unblock'                         => 'सदस्य सोडवा',
'blockip'                         => 'हा अंकपत्ता अडवा',
'blockip-title'                   => 'सदस्यास प्रतिबंध करा',
'blockip-legend'                  => 'सदस्यास प्रतिबंध करा',
'blockiptext'                     => 'एखाद्या विशिष्ट अंकपत्त्याची किंवा सदस्याची लिहिण्याची क्षमता प्रतिबंधीत  करण्याकरिता खालील सारणी वापरा.
हे केवळ उच्छेद टाळण्याच्याच दृष्टीने आणि [[{{MediaWiki:Policy-url}}|निती]]स अनुसरून केले पाहिजे.
खाली विशिष्ट कारण भरा(उदाहरणार्थ,ज्या पानांवर उच्छेद माजवला गेला त्यांची उद्धरणे देऊन).',
'ipadressorusername'              => 'अंकपत्ता किंवा सदस्यनाम:',
'ipbexpiry'                       => 'समाप्ति:',
'ipbreason'                       => 'कारण:',
'ipbreasonotherlist'              => 'इतर कारण',
'ipbreason-dropdown'              => '*प्रतिबंधनाची सामान्य कारणे
** चुकीची माहिती भरणे
** पानांवरील मजकूर काढणे
** बाह्यसंकेतस्थळाचे चिखलणी(स्पॅमींग) दुवे देणे
** पानात अटरफटर/वेडगळ भरणे
** धमकावणारे/उपद्रवी वर्तन
** असंख्य खात्यांचा गैरवापर
** अस्वीकार्य सदस्यनाम',
'ipb-hardblock'                   => 'या अंक पत्यावरुन (IP address) प्रवेश केलेल्या सदस्यांना बदल करण्यापासून प्रतिबंध करा.',
'ipbcreateaccount'                => 'खात्याची निर्मिती प्रतिबंधीत करा',
'ipbemailban'                     => 'सदस्यांना विपत्र पाठवण्यापासून प्रतिबंधीत करा',
'ipbenableautoblock'              => 'या सदस्याने वापरलेला शेवटचा अंकपत्ता आणि जेथून या पुढे तो संपादनाचा प्रयत्न करेल ते सर्व अंकपत्ते आपोआप प्रतिबंधीत करा.',
'ipbsubmit'                       => 'हा पत्ता अडवा',
'ipbother'                        => 'इतर वेळ:',
'ipboptions'                      => '२ तास:2 hours,१ दिवस:1 day,३ दिवस:3 days,१ आठवडा:1 week,२ आठवडे:2 weeks,१ महिना:1 month,३ महिने:3 months,६ महिने:6 months,१ वर्ष:1 year,अनंत:infinite',
'ipbotheroption'                  => 'इतर',
'ipbotherreason'                  => 'इतर/अजून कारण:',
'ipbhidename'                     => 'सदस्य नाम प्रतिबंधन नोंदी, प्रतिबंधनाची चालू यादी आणि सदस्य यादी इत्यादीतून लपवा',
'ipbwatchuser'                    => 'या सदस्याच्या सदस्य तसेच चर्चा पानावर पहारा ठेवा',
'ipb-disableusertalk'             => 'सदस्यास स्वत:चे चर्चापान संपादण्यापासून प्रतिबंधीत करा',
'ipb-change-block'                => 'युपयोगकर्ताला पुन्हा ब्लाक करा सोबत स्थानिक सेथिँग.',
'ipb-confirm'                     => 'अडथाळा सुनिश्चित करा.',
'badipaddress'                    => 'अंकपत्ता बरोबर नाही.',
'blockipsuccesssub'               => 'अडवणूक यशस्वी झाली',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]]ला प्रतिबंधीत केले.<br />
प्रतिबंधनांचा आढावा घेण्याकरिता [[Special:IPBlockList|अंकपत्ता प्रतिबंधन सूची]] पहा.',
'ipb-blockingself'                => 'तुम्ही स्वतःलाच प्रतिबंधित करत आहात! तुम्ही ते नक्की करणार आहात का?',
'ipb-confirmhideuser'             => 'तुमच्याकडून सदस्य प्रतिबंधनासोबतच "सदस्य लपवला" जातो आहे.या कृउतीने सर्व याद्या आणि नोंदीतून सदस्य नाव लपविले जाते.असे करावयाचे आहे या बद्दल आपली खात्री आहे काय ?',
'ipb-edit-dropdown'               => 'प्रतिबंधाची कारणे संपादा',
'ipb-unblock-addr'                => '$1चा प्रतिबंध उठवा',
'ipb-unblock'                     => 'सदस्यनाव आणि अंकपत्त्यावरचे प्रतिबंधन उठवा',
'ipb-blocklist'                   => 'सध्याचे प्रतिबंध पहा',
'ipb-blocklist-contribs'          => '$1 साठी सदस्याचे योगदान',
'unblockip'                       => 'अंकपत्ता सोडवा',
'unblockiptext'                   => 'खाली दिलेला फॉर्म वापरून पूर्वी अडवलेल्या अंकपत्त्याला लेखनासाठी आधिकार द्या.',
'ipusubmit'                       => 'हा पत्ता सोडवा',
'unblocked'                       => '[[User:$1|$1]] वरचे प्रतिबंध उठवले आहेत',
'unblocked-range'                 => '$1 याच्यावरील प्रतिबंधन काढले आहे',
'unblocked-id'                    => 'प्रतिबंध $1 काढले',
'blocklist'                       => 'प्रतिबंधित केलेले सदस्य',
'ipblocklist'                     => 'अडविलेले अंकपत्ते व सदस्य नावे',
'ipblocklist-legend'              => 'प्रतिबंधीत सदस्य शोधा',
'blocklist-userblocks'            => 'खाते प्रतिबंधन लपवा',
'blocklist-tempblocks'            => 'तात्पुरती प्रतिबंधने लपवा',
'blocklist-addressblocks'         => 'एकल अंकपत्ता प्रतिबंधने दाखवू नका',
'blocklist-rangeblocks'           => 'अभिसीमा गट लपवा',
'blocklist-timestamp'             => 'वेळशिक्का',
'blocklist-target'                => 'लक्ष्य',
'blocklist-expiry'                => 'संपण्याचा कालावधी',
'blocklist-by'                    => 'प्रबंधकास प्रतिबंधन',
'blocklist-params'                => 'प्रतिबंध मापदंड',
'blocklist-reason'                => 'कारण',
'ipblocklist-submit'              => 'शोध',
'ipblocklist-localblock'          => 'स्थानिक प्रतिबंधन',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|दुसरे प्रतिबंधन|इतर प्रतिबंधने}}',
'infiniteblock'                   => 'अनंत',
'expiringblock'                   => 'समाप्ति $1 $2',
'anononlyblock'                   => 'केवळ अनामिक',
'noautoblockblock'                => 'स्व्यंचलितप्रतिबंधन स्थगित केले',
'createaccountblock'              => 'खात्याची निर्मिती प्रतिबंधीत केली',
'emailblock'                      => 'विपत्र प्रतिबंधीत',
'blocklist-nousertalk'            => 'ला स्वतःचे चर्चापान संपादता येत नाही',
'ipblocklist-empty'               => 'प्रतिबंधन यादी रिकामी आहे.',
'ipblocklist-no-results'          => 'विनंती केलेला अंकपत्ता अथवा सदस्यनाव प्रतिबंधीत केलेले नाही.',
'blocklink'                       => 'अडवा',
'unblocklink'                     => 'सोडवा',
'change-blocklink'                => 'ब्लॉक बदला',
'contribslink'                    => 'योगदान',
'emaillink'                       => 'ई-मेल पाठवा.',
'autoblocker'                     => 'स्वयंचलितप्रतिबंधन केले गेले कारण तुमचा अंकपत्ता अलीकडे "[[User:$1|$1]]"ने वापरला होता. $1 च्या प्रतिबंधनाकरिता दिलेले कारण: "$2" आहे.',
'blocklogpage'                    => 'ब्लॉक यादी',
'blocklog-showlog'                => 'या सदस्यावर आधी बन्दी घालन्यात आली आहे. बन्दी सन्दर्भातील अधिक नोन्दी येथे आहेत',
'blocklog-showsuppresslog'        => 'हा सदस्य पुर्वी प्रतिबंधीत अथवा लपविला गेला होता.
लपविलेल्या नोंदी खाली संदर्भाकरिता उपलब्ध आहेत.',
'blocklogentry'                   => '[[$1]] ला $2 $3 पर्यंत ब्लॉक केलेले आहे',
'reblock-logentry'                => ' $2 $3 हि अंतीम वेळ देऊन   [[$1]] चे प्रतिबंधन बदलले',
'blocklogtext'                    => 'ही सदस्यांच्या प्रतिबंधनाची आणि प्रतिबंधने उठवल्याची नोंद आहे.
आपोआप प्रतिबंधीत केलेले अंकपत्ते नमूद केलेले नाहीत.
सध्या लागू असलेली बंदी व प्रतिबंधनांच्या यादीकरिता [[Special:BlockList|अंकपत्ता प्रतिबंधन सूची]] पहा.',
'unblocklogentry'                 => 'प्रतिबंधन $1 हटवले',
'block-log-flags-anononly'        => 'केवळ अनामिक सदस्य',
'block-log-flags-nocreate'        => 'खाते तयारकरणे अवरूद्ध केले',
'block-log-flags-noautoblock'     => 'स्वयंचलित प्रतिबंधन अवरूद्ध केले',
'block-log-flags-noemail'         => 'विपत्र अवरूद्ध केले',
'block-log-flags-nousertalk'      => 'ला स्वतःचे चर्चापान संपादता येत नाही',
'block-log-flags-angry-autoblock' => 'अद्ययावत स्वयमेवप्रतिबंधन सक्षमीत',
'block-log-flags-hiddenname'      => 'सदस्यनाम लपवलेले आहे',
'range_block_disabled'            => 'प्रचालकांची पल्ला बंधने घालण्याची क्षमता अनुपलब्ध केली आहे.',
'ipb_expiry_invalid'              => 'अयोग्य समाप्ती काळ.',
'ipb_expiry_temp'                 => 'लपविलेले सदस्यनाम प्रतिबंधन कायमस्वरुपी असले पाहिजे.',
'ipb_hide_invalid'                => 'हे खात दाबन्यासाथि असमर्थ: ते सुध्दा बदल करन्याचि सकतात.',
'ipb_already_blocked'             => '"$1" आधीच अवरूद्ध केलेले आहे.',
'ipb-needreblock'                 => '$1 आधीच प्रतिबंधीत आहे . तुम्हाला त्याचि सेटींग्स बदलण्याची इच्छा आहे का ?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|दुसरे प्रतिबंधन|इतर प्रतिबंधने}}',
'unblock-hideuser'                => 'सदस्याचे नाव हे गोपनीय असल्यामुळे हे सदस्य खाते आपण गोठवू शकत नाही',
'ipb_cant_unblock'                => 'त्रूटी: प्रतिबंधन क्र.$1 मिळाला नाही. त्यावरील प्रतिबंधन कदाचित आधीच उठवले असेल.',
'ipb_blocked_as_range'            => 'त्रूटी:अंकपत्ता IP $1 हा प्रत्यक्षपणे प्रतिबंधीत केलेला नाही आणि अप्रतिबंधीत करता येत नाही.तो,अर्थात,$2पल्ल्याचा भाग म्हाणून तो प्रतिबंधीत केलेला आहे,जो की अप्रतिबंधीत करता येत नाही.',
'ip_range_invalid'                => 'अंकपत्ता अयोग्य टप्प्यात.',
'ip_range_toolarge'               => '/$1 पेक्षा मोठ्या Range प्रतिबंधनाची परवानगी नाह् are not allowed.',
'blockme'                         => 'मला प्रतिबंधीत करा',
'proxyblocker'                    => 'प्रातिनिधी(प्रॉक्झी)प्रतिबंधक',
'proxyblocker-disabled'           => 'हे कार्य अवरूद्ध केले आहे.',
'proxyblockreason'                => 'तुमचा अंकपत्ता प्रतिबंधीत केला आहे कारण तो उघड-उघड प्रतिनिधी आहे.कृपया तुमच्या आंतरजाल सेवा दात्यास किंवा तंत्रज्ञास पाचारण संपर्क करा आणि त्यांचे या गंभीर सुरक्षाप्रश्ना कडे लक्ष वेधा.',
'proxyblocksuccess'               => 'झाले.',
'sorbsreason'                     => '{{SITENAME}}ने वापरलेल्या DNSBL मध्ये तुमच्या अंकपत्त्याची नोंद उघड-उघड प्रतिनिधी म्हणून सूचित केली आहे.',
'sorbs_create_account_reason'     => '{{SITENAME}}च्या DNSBLने तुमचा अंकपत्ता उघड-उघड प्रतिनिधी म्हणून सूचित केला आहे.तुम्ही खाते उघडू शकत नाही',
'cant-block-while-blocked'        => 'तुम्ही स्वतः प्रतिबंधित असताना इतरांना प्रतिबंधित करू शकत नाही.',
'cant-see-hidden-user'            => 'तुम्ही प्रतिब्ंधकरण्याचा प्रयत्न करत असलेले सदस्य खाते आधीपासूनच प्रतिबंधीत आणि लपविले गेले आहे.
तुमच्याकडे सदस्य लपविण्याचे अधिकार नसल्यामुळे , तुम्ही सदस्य प्रतिबंधन  पाहू अथवा संपादीत करू शकत नाही',
'ipbblocked'                      => 'तुमचे स्वत:चेच खाते प्रतिबंधीत असल्यामुळे तुम्ही इतर सदस्यांना प्रतिबंधीत किंवा अप्रतिबंधीत करू शकत नाही',
'ipbnounblockself'                => 'तुम्ही स्वतः अप्रतिबंधित करू शकत नाही',

# Developer tools
'lockdb'              => 'विदागारास ताळे ठोका',
'unlockdb'            => 'विदागाराचे ताळे उघडा',
'lockdbtext'          => 'विदागारास ताळे ठोकल्याने सर्व सदस्यांची संपादन क्षमता, त्यांच्या सदस्य पसंती बदलणे,त्यांच्या पहार्‍याच्या सूची संपादीत करणे,आणि विदेत बदल घडवणार्‍या इतर गोष्टी संस्थगीत होतील.
कृपया तुम्हाला हेच करावयाचे आहे आणि भरण-पोषणा नंतर विदागाराचे ताळे उघडावयाचे आहे हे निश्चित करा.',
'unlockdbtext'        => 'विदागाराचे ताळे उघडल्याने सर्व सदस्यांची संपादन क्षमता, त्यांच्या सदस्य पसंती बदलणे,त्यांच्या पहार्‍याच्या सूची संपादीत करणे,आणि विदेत बदल घडवणार्‍या इतर गोष्टीची क्षमता पुन्हा उपलब्ध होईल.
कृपया तुम्हाला हेच करावयाचे आहे हे निश्चित करा.',
'lockconfirm'         => 'होय,मला खरेच विदागारास ताळे ठोकायच आहे.',
'unlockconfirm'       => 'होय,मला खरेच विदागाराचे ताळे उघडवयाचे आहे.',
'lockbtn'             => 'विदागारास ताळे ठोका',
'unlockbtn'           => 'विदागारचे ताळे काढा',
'locknoconfirm'       => 'आपण होकार पेटीत होकार भरला नाही.',
'lockdbsuccesssub'    => 'विदागरास ताळे यशस्वी',
'unlockdbsuccesssub'  => 'विदागाराचे ताळे काढले',
'lockdbsuccesstext'   => 'विदागारास ताळे ठोकण्यात आले आहे.<br />
तुमच्याकडून भरण-पोषण पूर्ण झाल्या नंतर [[Special:UnlockDB|ताळे उघडण्याचे]] लक्षात ठेवा.',
'unlockdbsuccesstext' => 'विदागाराचे ताळे उघडण्यात आले आहे.',
'lockfilenotwritable' => 'विदा ताळे संचिका लेखनीय नाही.विदेस ताळे लावण्याकरिता किंवा उघडण्याकरिता, ती आंतरजाल विदादात्याकडून लेखनीय असावयास हवी.',
'databasenotlocked'   => 'विदागारास ताळे नही',
'lockedbyandtime'     => '({{GENDER:$1|$1}} द्वारे $2 ला $3 वाजता)',

# Move page
'move-page'                    => '$1 हलवा',
'move-page-legend'             => 'पृष्ठ स्थानांतरण',
'movepagetext'                 => "खालील अर्ज हा एखाद्या लेखाचे शीर्षक बदलण्यासाठी वापरता येईल. खालील अर्ज भरल्यानंतर लेखाचे शीर्षक बदलले जाईल तसेच त्या लेखाचा सर्व इतिहास हा नवीन लेखामध्ये स्थानांतरित केला जाईल.
जुने शीर्षक नवीन शीर्षकाला पुनर्निर्देशित करेल.
जुन्या शीर्षकाला असलेले दुवे बदलले जाणार नाहीत, तरी तुम्हाला विनंती आहे की स्थानांतरण केल्यानंतर
[[Special:DoubleRedirects|दुहेरी]] अथवा [[Special:BrokenRedirects|मोडकी]] पुनर्निर्देशने तपासावीत.
चुकीचे दुवे टाळण्याची जबाबदारी सर्वस्वी तुमच्यावर राहील.

जर नवीन शीर्षकाचा लेख अस्तित्वात असेल तर स्थानांतरण होणार '''नाही'''.
पण जर नवीन शीर्षकाचा लेख हा रिकामा असेल अथवा पुनर्निर्देशन असेल (म्हणजेच त्या लेखाला जर संपादन इतिहास नसेल) तर स्थानांतरण होईल. याचा अर्थ असा की जर काही चूक झाली तर तुम्ही पुन्हा जुन्या शीर्षकाकडे स्थानांतरण करू शकता.

'''सूचना!'''
स्थानांतरण केल्याने एखाद्या महत्वाच्या लेखामध्ये अनपेक्षित बदल होऊ शकतात. तुम्हाला विनंती आहे की तुम्ही पूर्ण काळजी घ्या व होणारे परिणाम समजावून घ्या.
जर तुम्हाला शंका असेल तर प्रबंधकांशी संपर्क करा.",
'movepagetext-noredirectfixer' => "खालील अर्ज हा एखाद्या लेखाचे शीर्षक बदलण्यासाठी वापरता येईल. खालील अर्ज भरल्यानंतर लेखाचे शीर्षक बदलले जाईल तसेच त्या लेखाचा सर्व इतिहास हा नवीन लेखामध्ये स्थानांतरित केला जाईल.

जुने शीर्षक नवीन शीर्षकाकडे पुनर्निर्देशित करेल.

[[Special:DoubleRedirects|दुहेरी]] अथवा [[Special:BrokenRedirects|मोडकी]] पुनर्निर्देशनांकरीता तपासण्याची काळजी घ्या.
उपलब्ध दुवे  जीथे उघडणे अभिप्रेत होते तसेच उघडतील याची तुम्ही जबाबदारी घेत आहात

जर नवीन शीर्षकाचा लेख अस्तित्वात असेल तर स्थानांतरण होणार '''नाही'''.
पण जर नवीन शीर्षकाचा लेख हा रिकामा असेल अथवा पुनर्निर्देशन असेल (म्हणजेच त्या लेखाला जर संपादन इतिहास नसेल) तर स्थानांतरण होईल. याचा अर्थ असा की जर काही चूक झाली तर तुम्ही पुन्हा जुन्या शीर्षकाकडे स्थानांतरण करू शकता.
'''सूचना!'''
असे केल्याने एखाद्या महत्वाच्या/लोकप्रीय लेखामध्ये अनपेक्षित आणि महत्वाचे बदल होऊ शकतात. तुम्हाला विनंती आहे की तुम्ही पूर्ण काळजी घ्या व होणारे परिणाम समजावून घ्या.
जर तुम्हाला शंका असेल तर प्रचालक/प्रबंधकांशी संपर्क करा.",
'movepagetalktext'             => "संबंधित चर्चा पृष्ठ याबरोबर स्थानांतरीत होणार नाही '''जर:'''
* तुम्ही पृष्ठ दुसर्‍या नामविश्वात स्थानांतरीत करत असाल
* या नावाचे चर्चा पान अगोदरच अस्तित्वात असेल तर, किंवा
* खालील चेकबॉक्स तुम्ही काढून टाकला तर.

या बाबतीत तुम्हाला स्वतःला ही पाने एकत्र करावी लागतील.",
'movearticle'                  => 'पृष्ठाचे स्थानांतरण',
'moveuserpage-warning'         => "'''सावधान:''' आपण एक सदस्य पान स्थलांतरीत करत आहात. कृपया लक्षात घ्या की, फक्त हे पान स्थलांतरीत होइल, सदस्य नाम बदलले जणार नाही.",
'movenologin'                  => 'प्रवेश केलेला नाही',
'movenologintext'              => 'पान स्थानांतरित करण्यासाठी तुम्हाला [[Special:UserLogin|प्रवेश]] करावा लागेल.',
'movenotallowed'               => '{{SITENAME}}वरील पाने स्थानांतरीत करण्याची आपल्यापाशी परवानगी नाही.',
'movenotallowedfile'           => 'तुम्हाला दस्तावैज स्थानांतरीत करण्याची परवानगी नाही.',
'cant-move-user-page'          => 'तुम्हाला सदस्याचे दस्तावैज स्थानांतरीत करण्याची परवानगी नाही.',
'cant-move-to-user-page'       => 'तुम्हाला एखाद्या पानास सदस्य पानांवर (सदस्य उप-पाने सोडून) घेऊन जाण्यास परवानगी नाही.',
'newtitle'                     => 'नवीन शीर्षकाकडे:',
'move-watch'                   => 'या पानावर लक्ष ठेवा',
'movepagebtn'                  => 'स्थानांतरण करा',
'pagemovedsub'                 => 'स्थानांतरण यशस्वी',
'movepage-moved'               => '\'\'\'"$1" ला "$2" मथळ्याखाली स्थानांतरीत केले\'\'\'',
'movepage-moved-redirect'      => 'एक पुनर्निर्देशन तयार केले आहे.',
'movepage-moved-noredirect'    => 'पुनःनिर्देशीत पान तयार केलेले नाही',
'articleexists'                => 'त्या नावाचे पृष्ठ अगोदरच अस्तित्वात आहे, किंवा तुम्ही निवडलेले
नाव योग्य नाही आहे.
कृपया दुसरे नाव शोधा.',
'cantmove-titleprotected'      => 'नवे शीर्षक निर्मीत करण्या पासून सुरक्षीत केलेले असल्यामुळे,तुम्ही या जागी एखादे पान स्थानांतरीत करू शकत नाही.',
'talkexists'                   => 'पृष्ठ यशस्वीरीत्या स्थानांतरीत झाले पण चर्चा पृष्ठ स्थानांतरीत होवू
शकले नाही कारण त्या नावाचे पृष्ठ आधीच अस्तित्वात होते. कृपया तुम्ही स्वतः ती पृष्ठे एकत्र करा.',
'movedto'                      => 'कडे स्थानांतरण केले',
'movetalk'                     => 'शक्य असल्यास "चर्चा पृष्ठ" स्थानांतरीत करा',
'move-subpages'                => 'उपपाने स्थानांतरीत करा (जास्तीतजास्त $1)',
'move-talk-subpages'           => 'चर्चा पानाची सर्व उपपाने स्थानांतरीत करा (जास्तीतजास्त $1)',
'movepage-page-exists'         => '$1 पान अगोदरच अस्तित्त्वात आहे व त्याच्यावर आपोआप पुनर्लेखन करता येणार नाही.',
'movepage-page-moved'          => '$1 हे पान $2 या मथळ्याखाली स्थानांतरीत केले.',
'movepage-page-unmoved'        => '$1 हे पान $2 या मथळ्याखाली स्थानांतरीत करता आलेले नाही.',
'movepage-max-pages'           => 'जास्तीत जास्त $1 {{PLURAL:$1|पान|पाने}} स्थानांतरीत करण्यात {{PLURAL:$1|आलेले आहे|आलेली आहेत}} व आता आणखी पाने आपोआप स्थानांतरीत होणार नाहीत.',
'movelogpage'                  => 'स्थांनांतराची नोंद',
'movelogpagetext'              => 'स्थानांतरित केलेल्या पानांची यादी.',
'movesubpage'                  => '{{PLURAL:$1|उपपान|उपपाने}}',
'movesubpagetext'              => 'या पानास $1 {{PLURAL:$1|उपपान|उपपाने}} असून ती पुढे दर्शवली आहेत:',
'movenosubpage'                => 'या पानात उपपाने नाहीत.',
'movereason'                   => 'कारण:',
'revertmove'                   => 'पूर्वपदास न्या',
'delete_and_move'              => 'वगळा आणि स्थानांतरित करा',
'delete_and_move_text'         => '==वगळण्याची आवशकता==

लक्ष्यपान  "[[:$1]]" आधीच अस्तीत्वात आहे.स्थानांतराचा मार्ग मोकळाकरण्या करिता तुम्हाला ते वगळावयाचे आहे काय?',
'delete_and_move_confirm'      => 'होय, पान वगळा',
'delete_and_move_reason'       => '"[[$1]]" पासून वगळून स्थानांतर केले.',
'selfmove'                     => 'स्रोत आणि लक्ष्य पाने समान आहेत; एखादे पान स्वत:च्याच जागी स्थानांतरीत करता येत नाही.',
'immobile-source-namespace'    => 'नामविश्व "$1" मधील पाने हलवता आली नाहीत.',
'immobile-target-namespace'    => 'नामविश्व "$1" मध्ये पाने हलवता आली नाहीत.',
'immobile-target-namespace-iw' => 'पुढे चाल करण्यासाठी हा विकिअंतर्गत दुवा योग्य लक्ष नाही',
'immobile-source-page'         => 'हे पान हलवता येत नाही',
'immobile-target-page'         => 'लक्ष्य मथळा हलवता येत नाही.',
'imagenocrossnamespace'        => 'ज्या नामविश्वात संचिका साठविता येत नाहीत, त्या नामविश्वात संचिकांचे स्थानांतरण करता येत नाही',
'nonfile-cannot-move-to-file'  => 'संचिका स्वरुपाची नसलेली माहिती आपणास संचिका नामविश्वात वळती करता येणार नाही',
'imagetypemismatch'            => 'दिलेले संचिकेचे एक्सटेंशन त्या संचिकेच्या प्रकाराशी जुळत नाही',
'imageinvalidfilename'         => 'लक्ष्यसंचिका अवैध आहे',
'fix-double-redirects'         => 'मुळ शिर्षक दर्शविणारे फेरे अद्ययावत करा',
'move-leave-redirect'          => 'मागे एक पुनर्निर्देशन ठेवा',
'protectedpagemovewarning'     => "'''सूचना:''' हे पान सुरक्षित आहे. फक्त प्रशासकीय अधिकार असलेले सदस्य याच्यात बदल करू शकतात.",
'semiprotectedpagemovewarning' => "'''सूचना:''' हे पान सुरक्षित आहे. फक्त नोंदणीकृत सदस्य याच्यात बदल करू शकतात.
सर्वांत ताजी यादी खाली संदर्भासाठी दिली आहे:",
'move-over-sharedrepo'         => '== संचिका अस्तित्वात आहे ==
सामायिक भांडारात [[:$1]] नाव आधी पासून अस्तित्वात आहे. संचिका या नावावर स्थानांतरीत केल्यास सामायिक संचिकेवर चढेल.',
'file-exists-sharedrepo'       => 'धीरिकेसाठी तुम्ही निवडलेले नाव हे सामुहीक संग्राहलयात आधीपासून वापरात असल्याने कृपया दुसरे नाव निवडा.',

# Export
'export'            => 'पाने निर्यात करा',
'exporttext'        => 'तुम्ही एखाद्या विशिष्ट पानाचा मजकुर आणि संपादन इतिहास किंवा  पानांचा संच एखाद्या XML वेष्ठणात ठेवून निर्यात करू शकता.हे तुम्हाला [[Special:Import|पान आयात करा]]वापरून मिडीयाविकि वापरणार्‍या इतर विकित आयात करता येईल.

पाने निर्यात करण्या करिता,एका ओळीत एक मथळा असे, खालील मजकुर रकान्यात मथळे भरा आणि तुम्हाला ’सध्याची आवृत्ती तसेच सर्व जुन्या आवृत्ती ,पानाच्या इतिहास ओळी सोबत’, किंवा ’केवळ सध्याची आवृत्ती शेवटच्या संपादनाच्या माहिती सोबत’ हवी आहे का ते निवडा.

तुम्ही नंतरच्या बाबतीत एखादा दुवा सुद्धा वापरू शकता, उदाहरणार्थ "[[{{MediaWiki:Mainpage}}]]" पाना करिता [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] .',
'exportcuronly'     => 'संपूर्ण इतिहास नको,केवळ आताचे आवर्तन आंर्तभूत करा',
'exportnohistory'   => "----
'''सूचना:''' या फॉर्मचा वापर करून पानाचा पूर्ण इतिहास निर्यात करण्याची सुविधा कार्यकुशलतेच्या कारणंनी अनुपल्ब्ढ ठेवली आहे.",
'exportlistauthors' => 'प्रत्येक पानासाठी योगदात्यांच्या  पूर्ण सूचीचा(यादीचा) समावेश करावा',
'export-submit'     => 'निर्यात करा',
'export-addcattext' => 'वर्गीकरणातून पाने भरा:',
'export-addcat'     => 'भर',
'export-addnstext'  => 'नामविश्वातून पाने वाढवा:',
'export-addns'      => 'वाढवा',
'export-download'   => 'संचिका म्हणून जतन करा',
'export-templates'  => 'साचे आंतरभूत करा',
'export-pagelinks'  => 'पुढे उल्लेखीत पातळी पर्यंत दुवे दिलेल्या पानांचा आंतर्भाव करा :',

# Namespace 8 related
'allmessages'                   => 'सर्व प्रणाली-संदेश',
'allmessagesname'               => 'नाव',
'allmessagesdefault'            => 'सुरुवातीचा मजकूर',
'allmessagescurrent'            => 'सध्याचा मजकूर',
'allmessagestext'               => 'मीडियाविकी नामविश्वातील सर्व प्रणाली संदेशांची यादी',
'allmessagesnotsupportedDB'     => "हे पान संपादित करता येत नाही कारण'''\$wgUseDatabaseMessages''' मालवला आहे.",
'allmessages-filter-legend'     => 'गाळक',
'allmessages-filter'            => 'कस्टमायझेशन स्टेटनुसार गाळणी लावा :',
'allmessages-filter-unmodified' => 'असंपादित',
'allmessages-filter-all'        => 'सर्व',
'allmessages-filter-modified'   => 'संपादित',
'allmessages-prefix'            => 'उपसर्गाने गाळा:',
'allmessages-language'          => 'भाषा:',
'allmessages-filter-submit'     => 'चला',

# Thumbnails
'thumbnail-more'           => 'मोठे करा',
'filemissing'              => 'संचिका अस्तित्वात नाही',
'thumbnail_error'          => 'इवलेसे चित्र बनविण्यात अडथळा आलेला आहे: $1',
'djvu_page_error'          => 'टप्प्याच्या बाहेरचे DjVu पान',
'djvu_no_xml'              => 'DjVu संचिकेकरिता XML ओढण्यात असमर्थ',
'thumbnail_invalid_params' => 'इवल्याशाचित्राचा अयोग्य परिचय',
'thumbnail_dest_directory' => 'लक्ष्य धारिकेच्या निर्मितीस असमर्थ',
'thumbnail_image-type'     => 'चित्रप्रकार समर्थित नाही',
'thumbnail_gd-library'     => '$1 जी.डी. ग्रंथालयाची बांधणी अपूर्ण आहे.',
'thumbnail_image-missing'  => 'संचिका सापडत नाही: $1',

# Special:Import
'import'                     => 'पाने आयात करा',
'importinterwiki'            => 'आंतरविकि आयात',
'import-interwiki-text'      => 'आयात करण्याकरिता एक विकि आणि पानाचा मथळा निवडा.
आवर्तनांच्या तारखा आणि संपादकांची नावे जतन केली जातील.
सर्व आंतरविकि आयात क्रिया [[Special:Log/import|आयात नोंदीत]] दाखल केल्या आहेत.',
'import-interwiki-source'    => 'स्रोत विकी / पान:',
'import-interwiki-history'   => 'या पानाकरिताची सार्‍या इतिहास आवर्तनांची नक्कल करा',
'import-interwiki-templates' => 'साचे आंतरभूत करा',
'import-interwiki-submit'    => 'आयात',
'import-interwiki-namespace' => 'पाने नामविश्वात स्थानांतरीत करा:',
'import-upload-filename'     => 'संचिकानाव:',
'import-comment'             => 'प्रतिक्रीया:',
'importtext'                 => 'कृपया [[Special:Export|निर्यात सुविधा]] वापरून स्रोत विकिकडून संचिका निर्यात करा,ती तुमच्या तबकडीवर जतन करा आणि येथे चढवा.',
'importstart'                => 'पाने आयात करत आहे...',
'import-revision-count'      => '$1 {{PLURAL:$1|आवर्तन|आवर्तने}}',
'importnopages'              => 'आयातीकरिता पाने नाहीत.',
'imported-log-entries'       => '{{PLURAL:$1|आयात केलेली|आयात केलेल्या}} $1 {{PLURAL:$1|यादी प्रविष्टी|यादी प्रविष्ट्या}}.',
'importfailed'               => 'अयशस्वी आयात: $1',
'importunknownsource'        => 'आयात स्रोत प्रकार अज्ञात',
'importcantopen'             => 'आयातीत संचिका उघडणे जमले नाही',
'importbadinterwiki'         => 'अयोग्य आंतरविकि दुवा',
'importnotext'               => 'रिकामे अथवा मजकुर नाही',
'importsuccess'              => 'आयात पूर्ण झाली!',
'importhistoryconflict'      => 'उपलब्ध इतिहास आवर्तने परस्पर विरोधी आहेत(हे पान पूर्वी आयात केले असण्याची शक्यता आहे)',
'importnosources'            => 'कोणतेही आंतरविकि आयात स्रोत व्यक्त केलेले नाहीत आणि प्रत्यक्ष इतिहास चढवा अनुपलब्ध केले आहे.',
'importnofile'               => 'कोणतीही आयातीत संचिका चढवलेली नाही.',
'importuploaderrorsize'      => 'आयात संचिकेचे चढवणे फसले.संचिका चढवण्याच्या मान्यताप्राप्त आकारा पेक्षा मोठी आहे.',
'importuploaderrorpartial'   => 'आयात संचिकेचे चढवणे फसले.संचिका केवळ अर्धवटच चढू शकली.',
'importuploaderrortemp'      => 'आयात संचिकेचे चढवणे फसले.एक तात्पुरती धारिका मिळत नाही.',
'import-parse-failure'       => 'XML आयात पृथक्करण अयशस्वी',
'import-noarticle'           => 'आयात करण्याकरिता पान नाही!',
'import-nonewrevisions'      => 'सारी आवर्तने पूर्वी आयात केली होती.',
'xml-error-string'           => '$1 ओळ $2मध्ये , स्तंभ $3 (बाईट $4): $5',
'import-upload'              => 'XML डाटा चढवा',
'import-token-mismatch'      => 'अधिवेशन माहितीची हानी.
कृपया पुन्हा प्रयत्न करा.',
'import-invalid-interwiki'   => 'नमूद केलेल्या विकिमधून आयात करू शकत नाही.',
'import-error-edit'          => 'तुम्हाला संपादनाची परवानगी नसल्याने $1 पान आयात केले गेले नाही.',
'import-error-create'        => 'तुम्हाला $1 तयार करण्याची परवानगी नसल्याने ते आयात केले गेले नाही.',

# Import log
'importlogpage'                    => 'ईम्पोर्ट सूची',
'importlogpagetext'                => 'इतर विकिक्डून पानांची, संपादकीय इतिहासासहीत, प्रबंधकीय आयात.',
'import-logentry-upload'           => 'संचिका चढवल्याने [[$1]] आयात',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|आवर्तन|आवर्तने}}',
'import-logentry-interwiki'        => 'आंतरविकिकरण $1',
'import-logentry-interwiki-detail' => '$2 पासून $1 {{PLURAL:$1|आवर्तन|आवर्तने}}',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'तुमचे सदस्य पान',
'tooltip-pt-anonuserpage'         => 'तुम्ही ज्या अंकपत्त्यान्वये संपादीत करत आहात त्याकरिता हे सदस्य पान',
'tooltip-pt-mytalk'               => 'तुमचे चर्चा पान',
'tooltip-pt-anontalk'             => 'या अंकपत्त्यापासून झालेल्या संपादनांबद्दल चर्चा',
'tooltip-pt-preferences'          => 'माझ्या पसंती',
'tooltip-pt-watchlist'            => 'तुम्ही पहारा दिलेल्या पानांची यादी',
'tooltip-pt-mycontris'            => 'तुमच्या योगदानांची यादी',
'tooltip-pt-login'                => 'आपणांस सदस्यत्व घेण्याची विनंती करण्यात येत आहे. सदस्यत्व घेणे अनिवार्य नाही.',
'tooltip-pt-anonlogin'            => 'आपण खात्यात दाखल व्हावे या करिता प्रोत्साहन देतो, अर्थात ते अत्यावश्यक नाही.',
'tooltip-pt-logout'               => 'बाहेर पडा',
'tooltip-ca-talk'                 => 'कंटेंट पानाबद्दलच्या चर्चा',
'tooltip-ca-edit'                 => 'तुम्ही हे पान बद्लू शकता. कृपया जतन करण्यापुर्वी झलक कळ वापरून पहा.',
'tooltip-ca-addsection'           => 'नवीन चर्चा सुरू करा',
'tooltip-ca-viewsource'           => 'हे पान सुरक्षित आहे. तुम्ही याचा स्रोत पाहू शकता.',
'tooltip-ca-history'              => 'या पानाच्या जुन्या आवृत्या.',
'tooltip-ca-protect'              => 'हे पान सुरक्षित करा',
'tooltip-ca-unprotect'            => 'पृष्ठ असुरक्षित करा',
'tooltip-ca-delete'               => 'हे पान वगळा',
'tooltip-ca-undelete'             => 'या पानाची वगळण्यापूर्वी केलेली संपादने पुनर्स्थापित करा',
'tooltip-ca-move'                 => 'हे पान स्थानांतरित करा.',
'tooltip-ca-watch'                => 'हे पान तुमच्या पहार्‍याची सूचीत टाका',
'tooltip-ca-unwatch'              => 'हे पान पहार्‍याच्या सूचीतून काढा.',
'tooltip-search'                  => '{{SITENAME}} शोधा',
'tooltip-search-go'               => 'या नेमक्या नावाच्या पानाकडे,अस्तित्वात असल्यास, चला',
'tooltip-search-fulltext'         => 'या मजकुराकरिता पान शोधा',
'tooltip-p-logo'                  => 'मुखपृष्ठ',
'tooltip-n-mainpage'              => 'मुखपृष्ठाला भेट द्या',
'tooltip-n-mainpage-description'  => 'मुखपृष्ठाला भेट द्या',
'tooltip-n-portal'                => 'प्रकल्पाबद्दल, तुम्ही काय करू शकता, कुठे काय सापडेल',
'tooltip-n-currentevents'         => 'सद्य घटनांबद्दलची माहिती',
'tooltip-n-recentchanges'         => 'विकिवरील अलीकडील बदलांची यादी',
'tooltip-n-randompage'            => 'कोणतेही अविशिष्ट पान पहा',
'tooltip-n-help'                  => 'मदत मिळवण्याचे ठिकाण',
'tooltip-t-whatlinkshere'         => 'येथे जोडलेल्या सर्व विकिपानांची यादी',
'tooltip-t-recentchangeslinked'   => 'येथुन जोडलेल्या सर्व पानांवरील अलीकडील बदल',
'tooltip-feed-rss'                => 'या पानाकरिता आर.एस.एस. रसद',
'tooltip-feed-atom'               => 'या पानाकरिता ऍटम रसद',
'tooltip-t-contributions'         => 'या सदस्याच्या योगदानांची यादी पहा',
'tooltip-t-emailuser'             => 'या सदस्याला इमेल पाठवा',
'tooltip-t-upload'                => 'चित्रे किंवा माध्यम संचिका चढवा',
'tooltip-t-specialpages'          => 'सर्व विशेष पृष्ठांची यादी',
'tooltip-t-print'                 => 'या पानाची छापण्यायोग्य आवृत्ती',
'tooltip-t-permalink'             => 'पानाच्या या आवर्तनाचा शाश्वत दुवा',
'tooltip-ca-nstab-main'           => 'मजकुराचे पान पहा',
'tooltip-ca-nstab-user'           => 'सदस्य पान पहा',
'tooltip-ca-nstab-media'          => 'माध्यम पान पहा',
'tooltip-ca-nstab-special'        => 'हे विशेष पान आहे; तुम्ही ते बदलू शकत नाही.',
'tooltip-ca-nstab-project'        => 'प्रकल्प पान पहा',
'tooltip-ca-nstab-image'          => 'चित्र पान पहा',
'tooltip-ca-nstab-mediawiki'      => 'सिस्टीम संदेश पहा',
'tooltip-ca-nstab-template'       => 'साचा पहा',
'tooltip-ca-nstab-help'           => 'साहाय्य पान पहा',
'tooltip-ca-nstab-category'       => 'वर्ग पान पहा',
'tooltip-minoredit'               => 'बदल छोटा असल्याची नोंद करा',
'tooltip-save'                    => 'तुम्ही केलेले बदल जतन करा',
'tooltip-preview'                 => 'तुम्ही केलेल्या बदलांची झलक पहा, जतन करण्यापूर्वी कृपया हे वापरा!',
'tooltip-diff'                    => 'या पाठ्यातील तुम्ही केलेले बदल दाखवा.',
'tooltip-compareselectedversions' => 'निवडलेल्या आवृत्त्यांमधील बदल दाखवा.',
'tooltip-watch'                   => 'हे पान तुमच्या पहार्‍याच्या सूचीत टाका.',
'tooltip-recreate'                => 'हे पान मागे वगळले असले तरी नवनिर्मीत करा',
'tooltip-upload'                  => 'चढवणे सुरूकरा',
'tooltip-rollback'                => '"द्रुतमाघार". याद्वारे शेवटच्या सदस्याने या पानात केलेली संपादने एका झटक्यात उलटवली जातात.',
'tooltip-undo'                    => '"रद्द करा" हे संपादन उलटविते व संपादन खिडकी उघडते.
त्यामुळे तुम्ही बदलांचा आढावा देऊ शकता.',
'tooltip-preferences-save'        => 'माझ्या पसंती जतन करा',
'tooltip-summary'                 => 'लहान सारांश लिहा',

# Metadata
'notacceptable' => 'विकि विदादाता तुमचा घेता वाचू शकेल अशा स्वरूपात(संरचनेत) विदा पुरवू शकत नाही.',

# Attribution
'anonymous'        => '{{SITENAME}} वरील अनामिक {{PLURAL:$1|सदस्य|सदस्य}}',
'siteuser'         => '<!--{{SITENAME}}-->मराठी विकिपीडियाचा सदस्य $1',
'anonuser'         => '{{SITENAME}} वरील अनामी सदस्य $1',
'lastmodifiedatby' => 'या पानातील शेवटचा बदल $3ने $2, $1 यावेळी केला.',
'othercontribs'    => '$1 ने केलेल्या कामानुसार.',
'others'           => 'इतर',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|सदस्य|सदस्य}} $1',
'anonusers'        => '{{SITENAME}} वरील अनामी {{PLURAL:$2|सदस्य|सदस्य}} $1',
'creditspage'      => 'पान श्रेय नामावली',
'nocredits'        => 'या पानाकरिता श्रेय नामावलीची माहिती नाही.',

# Spam protection
'spamprotectiontitle' => 'केर(स्पॅम) सुरक्षा चाचणी',
'spamprotectiontext'  => 'तुम्ही जतन करू इच्छित असलेले पान केर-उत्पात रोधक चाळणीने प्रतिबंधीत केले आहे.

असे बाहेरच्या संकेतस्थळाचा दुवा देण्याची शक्यता असल्यामुळे घडू शकते.',
'spamprotectionmatch' => 'खालील मजकुरामुळे आमची चिखलणी रोधक चाळणी सुरू झाली: $1',
'spambot_username'    => 'मिडियाविकि स्पॅम स्वछता',
'spam_reverting'      => '$1शी दुवे नसलेल्या गेल्या आवर्तनाकडे परत उलटवत आहे',
'spam_blanking'       => '$1शी दुवे असलेली सर्व आवर्तने,रिक्त केली जात आहेत',

# Info page
'pageinfo-title'            => '"$1" च्याबद्दल माहिती',
'pageinfo-header-edits'     => 'संपादने',
'pageinfo-header-watchlist' => 'पहार्‍याची सूची',
'pageinfo-header-views'     => 'दृष्टीपथात',
'pageinfo-subjectpage'      => 'पान',
'pageinfo-talkpage'         => 'चर्चा पान',
'pageinfo-watchers'         => 'पाहणाऱ्यांची संख्या',
'pageinfo-edits'            => 'संपादनांची संख्या',
'pageinfo-authors'          => 'वेगळ्या लेखकांची संख्या',
'pageinfo-views'            => 'अभिप्रायांची संख्या',
'pageinfo-viewsperedit'     => 'प्रति संपादन अभिप्राय',

# Skin names
'skinname-standard'    => 'अभिजात',
'skinname-nostalgia'   => 'रम्य',
'skinname-cologneblue' => 'सुरेखनीळी',
'skinname-monobook'    => 'मोनोबुक',
'skinname-myskin'      => 'माझीकांती',
'skinname-chick'       => 'मस्त',
'skinname-simple'      => 'साधी',
'skinname-modern'      => 'आधुनिक',

# Patrolling
'markaspatrolleddiff'                 => 'टेहळणी केल्याची खूण करा',
'markaspatrolledtext'                 => 'या पानावर गस्त झाल्याची खूण करा',
'markedaspatrolled'                   => 'गस्त केल्याची खूण केली',
'markedaspatrolledtext'               => 'निवडलेल्या [[:$1]]च्या आवर्तनास गस्त घातल्याची खूण केली.',
'rcpatroldisabled'                    => 'अलिकडील बदलची गस्ती अनुपलब्ध',
'rcpatroldisabledtext'                => 'सध्या ’अलिकडील बदल’ ची गस्त सुविधा अनुपलब्ध केली आहे.',
'markedaspatrollederror'              => 'गस्तीची खूण करता येत नाही',
'markedaspatrollederrortext'          => 'गस्त घातल्याची खूण करण्याकरिता तुम्हाला एक आवर्तन नमुद करावे लागेल.',
'markedaspatrollederror-noautopatrol' => 'तुम्हाला स्वत:च्याच बदलांवर गस्त घातल्याची खूण करण्याची परवानगी नाही.',

# Patrol log
'patrol-log-page'      => 'टेहळणीतील नोंदी',
'patrol-log-header'    => 'ही पाहणीनंतरच्या निरिक्षणाची नोंद आहे.',
'log-show-hide-patrol' => '$1 गस्तीची नोंद',

# Image deletion
'deletedrevision'                 => 'जुनी आवृत्ती ($1) वगळली.',
'filedeleteerror-short'           => 'संचिका वगळताना त्रूटी: $1',
'filedeleteerror-long'            => 'संचिका वगळताना त्रूटी आढळल्या:

$1',
'filedelete-missing'              => 'संचिका "$1" वगळता येत नाही, कारण ती अस्तित्वात नाही.',
'filedelete-old-unregistered'     => 'निर्देशीत संचिका आवर्तन "$1" विदागारात नाही.',
'filedelete-current-unregistered' => 'नमुद संचिका "$1" विदागारात नाही.',
'filedelete-archive-read-only'    => 'विदागार धारीका "$1" आंतरजाल विदादात्याकडून लेखनीय नाही.',

# Browsing diffs
'previousdiff' => '← मागील संपादन',
'nextdiff'     => 'पुढील संपादन →',

# Media information
'mediawarning'           => "'''सावधान''': या संचिकेत डंखी संकेत असू शकतो, जो वापरल्याने तुमच्या संचालन प्रणालीस नाजूक परिस्थितीस सामोरे जावे लागू शकते.",
'imagemaxsize'           => 'संचिका वर्णन पानांवरील चित्रांना मर्यादा घाला:',
'thumbsize'              => 'इवलासा आकार:',
'widthheightpage'        => '$1×$2, $3 {{PLURAL:$3|पान|पाने}}',
'file-info'              => 'संचिकेचा आकार:$1,विविधामापमाईमप्रकार: $2',
'file-info-size'         => '$1 × $2 pixel, संचिकेचा आकार: $3, MIME प्रकार: $4',
'file-info-size-pages'   => '$1 × $2 पिक्सेल, संचिका आकारमान: $3, एमआयएमई प्रकार: $4, $5 {{PLURAL:$5|पान|पाने}}',
'file-nohires'           => 'यापेक्षा मोठे चित्र उपलब्ध नाही.',
'svg-long-desc'          => 'SVG संचिका, साधारणपणे $1 × $2 pixels, संचिकेचा आकार: $3',
'show-big-image'         => 'संपूर्ण रिजोल्यूशन',
'show-big-image-preview' => 'या झलकेचा आकार: $1. पिक्सेल',
'show-big-image-other'   => 'इतर {{PLURAL:$2|resolution|resolutions}}: $1.',
'show-big-image-size'    => '$1 × $2 पिक्सेल',
'file-info-gif-looped'   => 'विळख्यात सापडलेले',
'file-info-gif-frames'   => '$1 {{PLURAL:$1|चौकट|चौकटी}}',
'file-info-png-looped'   => 'विळख्यात सापडलेले',
'file-info-png-repeat'   => '$1 {{PLURAL:$1|वेळा दाखवले|वेळा दाखवले}}',
'file-info-png-frames'   => '$1 {{PLURAL:$1|चौकट|चौकटी}}',

# Special:NewFiles
'newimages'             => 'नवीन संचिकांची यादी',
'imagelisttext'         => "खाली '''$1''' संचिका {{PLURAL:$1|दिली आहे.|$2 क्रमाने दिल्या आहेत.}}",
'newimages-summary'     => 'हे विशेष पान शेवटी चढविलेल्या संचिका दर्शविते.',
'newimages-legend'      => 'गाळक',
'newimages-label'       => 'संचिकानाम (किंवा त्याचा भाग):',
'showhidebots'          => '(सांगकामे $1)',
'noimages'              => 'बघण्यासारखे येथे काही नाही.',
'ilsubmit'              => 'शोधा',
'bydate'                => 'तारखेनुसार',
'sp-newimages-showfrom' => '$2, $1 पासूनच्या नवीन संचिका दाखवा',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 सेकंद|$1 सेकंद}}',
'minutes' => '{{PLURAL:$1|$1 मिनिट|$1 मिनिट}}',
'hours'   => '{{PLURAL:$1|$1 तास|$1 तास}}',
'days'    => '{{PLURAL:$1|$1 दिवस|$1 दिवस}}',
'ago'     => '$1 पूर्वी',

# Bad image list
'bad_image_list' => 'रूपरेषा खालील प्रमाणे आहे:

फक्त यादीमधील संचिका (ज्यांच्यापुढे * हे चिन्ह आहे अशा ओळी) लक्षात घेतलेल्या आहेत. ओळीवरील पहिला दुवा हा चुकीच्या संचिकेचा असल्याची खात्री करा.
त्यापुढील दुवे हे अपवाद आहेत, म्हणजेच असे लेख जिथे ही संचिका मिळू शकते.',

# Metadata
'metadata'          => 'मेटाडाटा',
'metadata-help'     => 'या संचिकेत जास्तीची माहिती आहे. बहुधा ही संचिका बनवताना वापरलेल्या कॅमेरा किंवा स्कॅनर कडून ही माहिती जमा झाली आहे. जर या संचिकेत निर्मितीपश्चात बदल करण्यात आले असतील, तर कदाचित काही माहिती नवीन संचिकेशी पूर्णपणे जुळणार नाही.',
'metadata-expand'   => 'जास्तीची माहिती दाखवा',
'metadata-collapse' => 'जास्तीची माहिती लपवा',
'metadata-fields'   => 'या यादीतील जी माहिती दिलेली असेल ती माहिती संचिकेच्या खाली मेटाडाटा माहितीत दिसेल. बाकीची माहिती झाकलेली राहील.
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
'exif-imagewidth'                  => 'रूंदी',
'exif-imagelength'                 => 'उंची',
'exif-bitspersample'               => 'प्रती घटक बीट्स',
'exif-compression'                 => 'आकुंचन योजना',
'exif-photometricinterpretation'   => 'चित्रांश विन्यास (पिक्सेल कॉम्पोझीशन)',
'exif-orientation'                 => 'वळण',
'exif-samplesperpixel'             => 'घटकांची संख्या',
'exif-planarconfiguration'         => 'विदा रचना',
'exif-ycbcrsubsampling'            => 'Y चे C शी  उपनमुनातपासणी (सबसॅम्पलींग) गुणोत्तर',
'exif-ycbcrpositioning'            => 'Y आणि C प्रतिस्थापना (पोझीशनींग)',
'exif-xresolution'                 => 'समांतर रिझोल्यूशन',
'exif-yresolution'                 => 'उभे रिझोल्यूशन',
'exif-stripoffsets'                => 'चित्रविदा स्थान',
'exif-rowsperstrip'                => 'प्रत्येक पट्टीतील ओळींची संख्या',
'exif-stripbytecounts'             => 'प्रत्येक आकुंचीत पट्टीतील बाईट्सची संख्या',
'exif-jpeginterchangeformat'       => 'JPEG SOI करिता ऑफसेट',
'exif-jpeginterchangeformatlength' => 'JPEG विदे च्या बाईट्स',
'exif-whitepoint'                  => 'धवल बिंदू क्रोमॅटिसिटी',
'exif-primarychromaticities'       => 'क्रोमॅटिसिटीज ऑफ प्राईमारिटीज',
'exif-ycbcrcoefficients'           => 'कलर स्पेस ट्रान्स्फॉर्मेशन मॅट्रीक्स कोएफिशीयंट्स',
'exif-referenceblackwhite'         => 'काळ्या आणि पांढर्‍या संदर्भ मुल्यांची जोडी',
'exif-datetime'                    => 'संचिका बदल तारीख आणि वेळ',
'exif-imagedescription'            => 'चित्र शीर्षक',
'exif-make'                        => 'हुबहू छाउ (कॅमेरा) उत्पादक',
'exif-model'                       => 'हुबहू छाउ (कॅमेरा) उपकरण',
'exif-software'                    => 'वापरलेली संगणन अज्ञावली',
'exif-artist'                      => 'लेखक',
'exif-copyright'                   => 'प्रताधिकार धारक',
'exif-exifversion'                 => 'Exif आवृत्ती',
'exif-flashpixversion'             => 'पाठींबा असलेली फ्लॅशपीक्स मानक आवृत्ती',
'exif-colorspace'                  => 'रंगांकन (कलर स्पेस)',
'exif-componentsconfiguration'     => 'प्रत्येक घटकाचा अर्थ',
'exif-compressedbitsperpixel'      => 'चित्र आकुंचन स्थिती',
'exif-pixelydimension'             => 'आकृतीची सुयोग्य रूंदी',
'exif-pixelxdimension'             => 'आकृतीची सुयोग्य उंची',
'exif-usercomment'                 => 'सदस्य प्रतिक्रीया',
'exif-relatedsoundfile'            => 'संबधीत ध्वनी संचिका',
'exif-datetimeoriginal'            => 'विदा निर्मितीची तारीख आणि वेळ',
'exif-datetimedigitized'           => 'अंकनीकरणाची तारीख आणि वेळ',
'exif-subsectime'                  => 'तारीख वेळ उपसेकंद',
'exif-subsectimeoriginal'          => 'तारीखवेळमुळ उपसेकंद',
'exif-subsectimedigitized'         => 'तारीखवेळ अंकनीकृत उपसेकंद',
'exif-exposuretime'                => 'छायांकन कालावधी',
'exif-exposuretime-format'         => '$1 सेक ($2)',
'exif-fnumber'                     => 'F क्रमांक',
'exif-exposureprogram'             => "'''प्रभाव'''न कार्य (एक्स्पोजर प्रोग्राम)",
'exif-spectralsensitivity'         => 'झोत संवेदनशीलता (स्पेक्ट्रल सेन्सिटीव्हिटी)',
'exif-isospeedratings'             => 'आंतरराष्ट्रीय मानक संस्थेचे वेग मुल्यमापन',
'exif-shutterspeedvalue'           => 'शटर वेग',
'exif-aperturevalue'               => 'रन्ध्र',
'exif-brightnessvalue'             => 'झळाळी',
'exif-exposurebiasvalue'           => 'प्रभावन अभिनत (एक्सपोजर बायस)',
'exif-maxaperturevalue'            => 'महत्तम जमिनी रन्ध्र(लॅंड ऍपर्चर)',
'exif-subjectdistance'             => 'गोष्टीपासूनचे अंतर',
'exif-meteringmode'                => 'मीटरींग मोड',
'exif-lightsource'                 => 'प्रकाश स्रोत',
'exif-flash'                       => "लख'''लखाट''' (फ्लॅश)",
'exif-focallength'                 => 'भींगाची मध्यवर्ती लांबी (फोकल लांबी)',
'exif-subjectarea'                 => 'विषय विभाग',
'exif-flashenergy'                 => 'लखाट उर्जा (फ्लॅश एनर्जी)',
'exif-focalplanexresolution'       => 'फोकल प्लेन x रिझोल्यूशन',
'exif-focalplaneyresolution'       => 'फोकल प्लेन Y रिझोल्यूशन',
'exif-focalplaneresolutionunit'    => 'फोकल प्लेन  रिझोल्यूशन माप',
'exif-subjectlocation'             => 'लक्ष्य स्थळ',
'exif-exposureindex'               => 'प्रभावन सूची',
'exif-sensingmethod'               => 'सेन्सींग पद्धती',
'exif-filesource'                  => 'संचिका स्रोत',
'exif-scenetype'                   => 'दृष्य प्रकार',
'exif-customrendered'              => 'कस्टम इमेज प्रोसेसिंग',
'exif-exposuremode'                => "'''प्रभाव'''न मोड",
'exif-whitebalance'                => 'व्हाईट बॅलन्स',
'exif-digitalzoomratio'            => 'अंकीय झूम गुणोत्तर',
'exif-focallengthin35mmfilm'       => 'भींगाची मध्यवर्ती लांबी (फोकल लांबी) ३५ मी.मी. फील्ममध्ये',
'exif-scenecapturetype'            => 'दृश्य टिपण्याचा प्रकार',
'exif-gaincontrol'                 => 'दृश्य नियंत्रण',
'exif-contrast'                    => 'विभेद (कॉन्ट्रास्ट)',
'exif-saturation'                  => 'सॅचूरेशन',
'exif-sharpness'                   => 'प्रखरता(शार्पनेस)',
'exif-devicesettingdescription'    => 'उपकरण रचना वर्णन',
'exif-subjectdistancerange'        => 'गोष्टीपासूनचे पल्ला अंतर',
'exif-imageuniqueid'               => 'विशिष्ट चित्र क्रमांक',
'exif-gpsversionid'                => 'GPS खूण आवृत्ती',
'exif-gpslatituderef'              => 'उत्तर किंवा दक्षीण अक्षांश',
'exif-gpslatitude'                 => 'अक्षांश',
'exif-gpslongituderef'             => 'पूर्व किंवा पश्चिम रेखांश',
'exif-gpslongitude'                => 'रेखांश',
'exif-gpsaltituderef'              => 'उन्नतांश संदर्भ',
'exif-gpsaltitude'                 => 'उन्नतांश (अल्टीट्यूड)',
'exif-gpstimestamp'                => 'GPS वेळ(ऍटॉमिक घड्याळ)',
'exif-gpssatellites'               => 'मापनाकरिता वापरलेला उपग्रह',
'exif-gpsstatus'                   => 'प्राप्तकर्त्याची स्थिती',
'exif-gpsmeasuremode'              => 'मापन स्थिती',
'exif-gpsdop'                      => 'मापन अचूकता',
'exif-gpsspeedref'                 => 'वेग एकक',
'exif-gpsspeed'                    => 'GPS प्राप्तकर्त्याचा वेग',
'exif-gpstrackref'                 => 'हालचालीच्या दिशेकरिता संदर्भ',
'exif-gpstrack'                    => 'हालचालीची दिशा',
'exif-gpsimgdirectionref'          => 'चित्राच्या दिशेकरिता संदर्भ',
'exif-gpsimgdirection'             => 'चित्राची दिशा',
'exif-gpsmapdatum'                 => 'Geodetic पाहणी विदा वापरली',
'exif-gpsdestlatituderef'          => 'लक्ष्याचे अक्षांशाकरिता संदर्भ',
'exif-gpsdestlatitude'             => 'अक्षांश लक्ष्य',
'exif-gpsdestlongituderef'         => 'लक्ष्याचे रेखांशकरिता संदर्भ',
'exif-gpsdestlongitude'            => 'रेखांशाचे लक्ष्य',
'exif-gpsdestbearingref'           => 'बियरींग डेस्टीनेशनकरिता संदर्भ',
'exif-gpsdestbearing'              => 'बीअरींग ऑफ डेस्टीनेशन',
'exif-gpsdestdistanceref'          => 'लक्ष्यस्थळापर्यंतच्या अंतराकरिता संदर्भ',
'exif-gpsdestdistance'             => 'लक्ष्यस्थळापर्यंतचे अंतर',
'exif-gpsprocessingmethod'         => 'GPS प्रक्रीया पद्धतीचे नाव',
'exif-gpsareainformation'          => 'GPS विभागाचे नाव',
'exif-gpsdatestamp'                => 'GPSतारीख',
'exif-gpsdifferential'             => 'GPS डिफरेंशीअल सुधारणा',
'exif-jpegfilecomment'             => 'जेपीईजी संचिका टिप्पणी',
'exif-keywords'                    => 'लघुशब्द',
'exif-worldregioncreated'          => 'छायाचित्र काढले तो देश',
'exif-countrycreated'              => 'देश ज्याच्यात चित्र घेतले',
'exif-countrycodecreated'          => 'ज्या देशात छायाचित्र घेतले त्या देशाचे कोड',
'exif-provinceorstatecreated'      => 'जिथे छायाचित्र काढले तो प्रांत वा देश',
'exif-citycreated'                 => 'छायाचित्र घेतले ‍‍‍‍(काढले) ते शहर',
'exif-sublocationcreated'          => 'शहराज्या ज्या परिसरात छायाचित्र काढले तो परिसर',
'exif-worldregiondest'             => 'जगाचा दर्शित केलेला भूभाग(प्रदेश)',
'exif-countrydest'                 => 'दर्शविलेला देश',
'exif-countrycodedest'             => 'दर्शविलेल्या देशाचे चिन्ह',
'exif-provinceorstatedest'         => 'दर्शविलेला प्रांत वा देश',
'exif-citydest'                    => 'दर्शविलेले शहर',
'exif-sublocationdest'             => 'दर्शविलेले शहरातील नगर',
'exif-objectname'                  => 'लघुशीर्षक',
'exif-specialinstructions'         => 'विशेष सूचना',
'exif-headline'                    => 'मथळा',
'exif-credit'                      => 'जमा/पुरवठादार',
'exif-source'                      => 'स्रोत',
'exif-editstatus'                  => 'प्रतिमेच्या संपादनाची स्थिती',
'exif-urgency'                     => 'तात्कालिकता',
'exif-fixtureidentifier'           => 'संपादयकीय जोडणीदाराचे नाव',
'exif-locationdest'                => 'स्थान दर्शविले आहे',
'exif-locationdestcode'            => 'स्थानाच्या कूटाक्षराचा(कोड)  निर्देश केला आहे',
'exif-objectcycle'                 => 'मिडिया दिवसाच्या ज्या वेळेकरिता अभिप्रेत आहे.',
'exif-contact'                     => 'संपर्क माहिती',
'exif-writer'                      => 'लेखक',
'exif-languagecode'                => 'भाषा',
'exif-iimversion'                  => 'आय् आय् एम्  संस्करण',
'exif-iimcategory'                 => 'वर्ग',
'exif-iimsupplementalcategory'     => 'पुरवणी श्रेणी',
'exif-datetimeexpires'             => 'या तारखेपश्चात वापरू नका',
'exif-datetimereleased'            => 'या वेळी/दिवशी प्रसृत (प्रसारण )केले/मुक्त केले / सूरू केले',
'exif-originaltransmissionref'     => 'Original transmission location code: मूळ प्रसारण केले त्या स्थानाचे कूटाक्षर(कोड)',
'exif-identifier'                  => 'ओळख दुवा',
'exif-lens'                        => 'वापरलेले भिंग',
'exif-serialnumber'                => 'छायाचित्रकाचा सामयिक क्रमांक',
'exif-cameraownername'             => 'छायाचित्रकाचा मालक',
'exif-label'                       => 'लेबल',
'exif-datetimemetadata'            => 'मेटाडाटाच्या शेवटच्या बदलाची तारीख',
'exif-nickname'                    => 'चित्राचे / फोटोचे सामान्य नाव',
'exif-rating'                      => 'गुण (५ पैकी)',
'exif-rightscertificate'           => 'अधिकार व्यवस्थापन प्रमाणपत्र',
'exif-copyrighted'                 => 'प्रताधिकार स्थिती',
'exif-copyrightowner'              => 'प्रताधिकार धारक',
'exif-usageterms'                  => 'वापरण्यासाठी शर्थी',
'exif-webstatement'                => 'ऑनलाईन प्रताधिकार(कॉपीराईट)  उद्घोषणा',
'exif-originaldocumentid'          => 'मुळ दस्तएवजाचा  यूनिक आयडी (Unique ID)',
'exif-licenseurl'                  => 'प्रताधिकार परवान्याचा  (कॉपीराईट लायसन्सचा)  URL',
'exif-morepermissionsurl'          => 'पर्यायी परवाना माहिती',
'exif-attributionurl'              => 'ह्या कामाचा पुर्न-उपयोग करताना , कृपया पुढीलास दुवा द्या',
'exif-preferredattributionname'    => 'ह्या कामाचा पुर्न-उपयोग करताना, कृपया श्रेय दुवा द्या',
'exif-pngfilecomment'              => 'पीएनजी संचिका टिप्पणी',
'exif-disclaimer'                  => 'परवाना',
'exif-contentwarning'              => 'आशय विषयी सूचना',
'exif-giffilecomment'              => 'जीआयएफ संचिका टिप्पणी',
'exif-intellectualgenre'           => 'विशिष्ठ वस्तुचा प्रकार',
'exif-subjectnewscode'             => 'विषयाचे संकेतचिन्ह',
'exif-scenecode'                   => 'IPTC दृश्य संकेत',
'exif-event'                       => 'सादर केलेला उपक्रम',
'exif-organisationinimage'         => 'सादरकर्ती संस्था',
'exif-personinimage'               => 'सादरकर्ती व्यक्ती',
'exif-originalimageheight'         => 'चित्राचा आकार बदलण्यापुर्वीची उंची',
'exif-originalimagewidth'          => 'छाचाचित्राचा आकार बदलण्यापुर्वीची रूंदी',

# EXIF attributes
'exif-compression-1' => 'अनाकुंचीत',
'exif-compression-2' => 'CCITT गट३ १-Dimensional Modified Huffman run length encoding',
'exif-compression-3' => 'CCITT Group 3 फॅक्स संकेतन',
'exif-compression-4' => 'CCITT Group 4  फॅक्स संकेतन',

'exif-copyrighted-true'  => 'प्रताधिकारीत',
'exif-copyrighted-false' => 'सार्वजनिक ज्ञानक्षेत्र',

'exif-unknowndate' => 'अज्ञात तारीख',

'exif-orientation-1' => 'सामान्य',
'exif-orientation-2' => 'समांतर पालटले',
'exif-orientation-3' => '180° फिरवले',
'exif-orientation-4' => 'उभ्या बाजूने पालटले',
'exif-orientation-5' => '९०° CCW अंशात वळवले आणि उभे पालटले',
'exif-orientation-6' => '90° घडाळ्याच्या काट्याच्या दिशेने फिरवले',
'exif-orientation-7' => '90° CW वळवले आणि उभे पलटवले',
'exif-orientation-8' => '90° घडाळ्याच्या काट्याच्या दिशेने फिरवले',

'exif-planarconfiguration-1' => 'चंकी संरचना (रूपरेषा)',
'exif-planarconfiguration-2' => 'प्लानर संरचना(रूपरेषा)',

'exif-colorspace-65535' => 'रंगमात्रांश न दिलेले (अनकॅलिब्रेटेड)',

'exif-componentsconfiguration-0' => 'अस्तित्वात नाही',

'exif-exposureprogram-0' => 'अव्यक्त',
'exif-exposureprogram-1' => 'हातकाम',
'exif-exposureprogram-2' => 'सामान्य प्रोग्रॅम',
'exif-exposureprogram-3' => 'रन्ध्र (ऍपर्चर) प्राथमिकता',
'exif-exposureprogram-4' => 'झडप (शटर प्राथमिकता)',
'exif-exposureprogram-5' => 'क्रियेटीव्ह कार्यक्रम(विषयाच्या खोलीस बायस्ड)',
'exif-exposureprogram-6' => 'कृती कार्यक्रम(द्रूत आवर्तद्वार(शटर) वेग कडे बायस्ड)',
'exif-exposureprogram-7' => 'व्यक्तिचित्र स्थिती(क्लोजप छायाचित्रांकरिता आऊट ऑफ फोकस बॅकग्राऊंड सहीत)',
'exif-exposureprogram-8' => 'लॅंडस्केप स्थिती (लॅंडस्केप छायाचित्रांकरिता बॅकग्राऊंड इन फोकस सहीत)',

'exif-subjectdistance-value' => '$1 मीटर',

'exif-meteringmode-0'   => 'अज्ञात',
'exif-meteringmode-1'   => 'सरासरी',
'exif-meteringmode-2'   => 'सेंटरवेटेड सरासरी',
'exif-meteringmode-3'   => 'स्पॉट',
'exif-meteringmode-4'   => 'मल्टीस्पॉट',
'exif-meteringmode-5'   => 'पद्धत(पॅटर्न)',
'exif-meteringmode-6'   => 'अर्धवट',
'exif-meteringmode-255' => 'इतर',

'exif-lightsource-0'   => 'अज्ञात',
'exif-lightsource-1'   => 'सूर्यप्रकाश',
'exif-lightsource-2'   => 'फ्लूरोसेंट',
'exif-lightsource-3'   => 'टंगस्ट्न (इनकॅन्‍डेसेंट प्रकाश)',
'exif-lightsource-4'   => "लख'''लखाट''' (फ्लॅश)",
'exif-lightsource-9'   => 'चांगले हवामान',
'exif-lightsource-10'  => 'ढगाळ हवामान',
'exif-lightsource-11'  => 'छटा',
'exif-lightsource-12'  => 'दिवसप्रकाशी फ्लूरोशेंट (D 5700 – 7100K)',
'exif-lightsource-13'  => 'दिवस प्रकाशी फ्लूरोसेंट (N ४६०० – ५४०० K)',
'exif-lightsource-14'  => 'शीतल पांढरा फ्लूरोशेंट (W 3900 – 4500K)',
'exif-lightsource-15'  => 'व्हाईट फ्लूरोसेंट(WW ३२०० – ३७००K)',
'exif-lightsource-17'  => 'प्रकाश दर्जा A',
'exif-lightsource-18'  => 'प्रकाश दर्जा B',
'exif-lightsource-19'  => 'प्रमाण प्रकाश C',
'exif-lightsource-24'  => 'ISO स्टूडीयो टंगस्टन',
'exif-lightsource-255' => 'इतर प्रकाश स्रोत',

# Flash modes
'exif-flash-fired-0'    => 'Flash did not fire
फ्लॅशदिवा प्रज्ज्वलित झाला नाही',
'exif-flash-fired-1'    => 'क्षणदीप(फ्लेशदिवा)प्रज्ज्वलित झाला',
'exif-flash-return-0'   => 'लखलखाट (फ्लॅश) - प्रकाश परावर्तन नोंदणीची सुविधा अनुपलब्ध',
'exif-flash-return-2'   => 'लखलखाटाच्या (फ्लॅश)   परावर्तन प्रकाशाची नोंद झाली नाही',
'exif-flash-return-3'   => 'लखलखाटाचे (फ्लॅश) - प्रकाश परावर्तन होत असल्याचे टिपले',
'exif-flash-mode-1'     => 'अनिवार्य लखलखाट प्रदीपन (फ्लॅश फायरींग )',
'exif-flash-mode-2'     => 'अनिवार्य विना-लखलखाट  (फ्लॅश सप्रेशन)',
'exif-flash-mode-3'     => 'स्वयंचलित स्थिती',
'exif-flash-function-1' => 'लखलखाट  (फ्लॅश) सुविधा अनुपलब्ध',
'exif-flash-redeye-1'   => 'बुबुळ-लाली कमीकरा सक्षमता (रेड-आय रिडक्शन मोड)',

'exif-focalplaneresolutionunit-2' => 'इंच',

'exif-sensingmethod-1' => 'अव्यक्त',
'exif-sensingmethod-2' => 'वन चीप कलर एरीया सेन्‍सर',
'exif-sensingmethod-3' => 'टू चीप कलर एरीया सेन्‍सर',
'exif-sensingmethod-4' => 'थ्री चीप कलर एरीया सेन्‍सर',
'exif-sensingmethod-5' => 'कलर सिक्वेण्शीयल एरीया सेंसॉर',
'exif-sensingmethod-7' => 'ट्राय्‍एलिनीयर सेंसर',
'exif-sensingmethod-8' => 'कलर सिक्वेंशीयल लिनीयर सेन्‍सर',

'exif-filesource-3' => 'स्थिरचित्र  अंकीय छाउ (डिजीटल स्टील कॅमेरा)',

'exif-scenetype-1' => 'डायरेक्टली छायाचित्रीत चित्र',

'exif-customrendered-0' => 'नियमीत प्रक्रीया',
'exif-customrendered-1' => 'आवडीनुसार प्रक्रीया',

'exif-exposuremode-0' => 'स्वयंचलित छायांकन',
'exif-exposuremode-1' => 'अस्वयंचलित छायांकन',
'exif-exposuremode-2' => 'स्वयंसिद्ध कंस',

'exif-whitebalance-0' => 'ऍटो व्हाईट बॅलेन्स',
'exif-whitebalance-1' => 'मॅन्यूअल व्हाईट बॅलेन्स',

'exif-scenecapturetype-0' => 'दर्जा',
'exif-scenecapturetype-1' => 'आडवे',
'exif-scenecapturetype-2' => 'उभे',
'exif-scenecapturetype-3' => 'रात्रीचे दृश्य',

'exif-gaincontrol-0' => 'नाही',
'exif-gaincontrol-1' => 'लघु वृद्धी वर',
'exif-gaincontrol-2' => 'बृहत्‌ वृद्धी वर',
'exif-gaincontrol-3' => 'लघु वृद्धी खाली',
'exif-gaincontrol-4' => 'बृहत्‌ वृद्धी खाली',

'exif-contrast-0' => 'सामान्य',
'exif-contrast-1' => 'नरम',
'exif-contrast-2' => 'कठीण',

'exif-saturation-0' => 'सर्व साधारण',
'exif-saturation-1' => 'कमी सॅचूरेशन',
'exif-saturation-2' => 'जास्त सॅचूरेशन',

'exif-sharpness-0' => 'सर्वसाधारण',
'exif-sharpness-1' => 'मृदू',
'exif-sharpness-2' => 'कठीण',

'exif-subjectdistancerange-0' => 'अज्ञात',
'exif-subjectdistancerange-1' => 'मॅक्रो',
'exif-subjectdistancerange-2' => 'जवळचे दृश्य',
'exif-subjectdistancerange-3' => 'दूरचे दृश्य',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'उत्तर अक्षांश',
'exif-gpslatitude-s' => 'दक्षीण अक्षांश',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'पूर्व रेखांश',
'exif-gpslongitude-w' => 'पश्चिम रेखांश',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => 'समुद्रपातळीच्यावर $1 {{PLURAL:$1|मीटर|मीटर}}',
'exif-gpsaltitude-below-sealevel' => 'समुद्रपातळीच्याखाली $1 {{PLURAL:$1|मीटर|मीटर}}',

'exif-gpsstatus-a' => 'मोजणी काम चालू आहे',
'exif-gpsstatus-v' => 'आंतरोपयोगीक्षमतेचे मोजमाप',

'exif-gpsmeasuremode-2' => 'द्वि-दिश मापन',
'exif-gpsmeasuremode-3' => 'त्रि-दिश मोजमाप',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'प्रतिताशी किलोमीटर',
'exif-gpsspeed-m' => 'प्रतिताशी मैल',
'exif-gpsspeed-n' => 'गाठी',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'किमी',
'exif-gpsdestdistance-m' => 'मैल',
'exif-gpsdestdistance-n' => 'सागरी मैल',

'exif-gpsdop-excellent' => 'उत्कृष्ट ($1)',
'exif-gpsdop-good'      => 'चांगले ($1)',
'exif-gpsdop-moderate'  => 'मध्यम ($1)',
'exif-gpsdop-fair'      => 'निष्पक्ष ($1)',
'exif-gpsdop-poor'      => 'वाईट ($1)',

'exif-objectcycle-a' => 'फक्त सकाळी',
'exif-objectcycle-p' => 'फक्त संध्याकाळी',
'exif-objectcycle-b' => 'सकाळ-संध्याकाळ दोन्ही सक्षमता',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'बरोबर दिशा',
'exif-gpsdirection-m' => 'चुंबकीय दिशा',

'exif-ycbcrpositioning-1' => 'मध्यकेंद्रीत (सेंटर्ड)',
'exif-ycbcrpositioning-2' => 'आरोहीत (को-सिटेड )',

'exif-dc-contributor' => 'योगदानकर्ते',
'exif-dc-coverage'    => 'माध्यमाचा स्पॅतीयल किंवा टेंपोरल आवाका',
'exif-dc-date'        => 'दिनांक',
'exif-dc-publisher'   => 'प्रकाशक',
'exif-dc-relation'    => 'संबंधित मीडिया',
'exif-dc-rights'      => 'अधिकार',
'exif-dc-source'      => 'स्रोत मीडिया',
'exif-dc-type'        => 'मीडिया प्रकार',

'exif-rating-rejected' => 'अमान्य केले/झाले',

'exif-isospeedratings-overflow' => '६५,५३६ हून मोठे',

'exif-iimcategory-ace' => 'कला, संस्कृती व मनोरंजन',
'exif-iimcategory-clj' => 'कायदे व गुन्हे',
'exif-iimcategory-dis' => 'अपघात आणि अनर्थ',
'exif-iimcategory-fin' => 'व्यापार व अर्थशास्त्र',
'exif-iimcategory-edu' => 'शिक्षण',
'exif-iimcategory-evn' => 'पर्यावरण',
'exif-iimcategory-hth' => 'तब्येत',
'exif-iimcategory-hum' => 'मानवी अभिरुचि',
'exif-iimcategory-lab' => 'परिश्रम',
'exif-iimcategory-lif' => 'आराम आणि जिवन पद्धती',
'exif-iimcategory-pol' => 'राजनीती',
'exif-iimcategory-rel' => 'धर्म व श्रद्धा',
'exif-iimcategory-sci' => 'विज्ञान व तंत्रज्ञान',
'exif-iimcategory-soi' => 'सामाजिक प्रश्न',
'exif-iimcategory-spo' => 'क्रीडा',
'exif-iimcategory-war' => 'युद्ध, संघर्ष आणि अशांती',
'exif-iimcategory-wea' => 'हवामान',

'exif-urgency-normal' => 'सामान्य ($1)',
'exif-urgency-low'    => 'नीचतम ($1)',
'exif-urgency-high'   => 'उच्चतम ($1)',
'exif-urgency-other'  => '($1) उपयोगकर्ता-निश्चित  प्राधान्य',

# External editor support
'edit-externally'      => 'बाहेरील संगणक प्रणाली वापरून ही संचिका संपादित करा.',
'edit-externally-help' => 'अधिक माहितीसाठी स्थापन करण्याच्या सूचना [//www.mediawiki.org/wiki/Manual:External_editors] येथे पहा.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'सर्व',
'namespacesall' => 'सर्व',
'monthsall'     => 'सर्व',
'limitall'      => 'सर्व',

# E-mail address confirmation
'confirmemail'              => 'इमेल पत्ता पडताळून पहा',
'confirmemail_noemail'      => '[[Special:Preferences|सदस्य पसंतीत]] तुम्ही प्रमाणित विपत्र (इमेल) पत्ता दिलेला नाही.',
'confirmemail_text'         => 'विपत्र सुविधा वापरण्या पूर्वी {{SITENAME}}वर तुमचा विपत्र (इमेल) पत्ता प्रमाणित करणे गरजेचे आहे. तुमच्या पत्त्यावर निश्चितीकरण विपत्र (इमेल) पाठवण्याकरिता खालील बटण सुरू करा.विपत्रात कुटसंकेतच्(पासवर्ड) असलेला दुवा असेल;तुमचा विपत्र (इमेल) पत्ता प्रमाणित करण्या करिता तुमच्या विचरकात हा दिलेला दुवा चढवा.',
'confirmemail_pending'      => 'एक निश्चितीकरण कुटसंकेत आधीच तुम्हाला विपत्र केला आहे; जर तुम्ही खाते अशातच उघडले असेल तर,एक नवा कुट संकेत मागण्यापूर्वी,पाठवलेला मिळण्याकरिता थोडी मिनिटे वाट पहाणे तुम्हाला आवडू शकेल.',
'confirmemail_send'         => 'विपत्र निश्चितीकरण नियमावली',
'confirmemail_sent'         => 'शाबितीकरण विपत्र पाठवले.',
'confirmemail_oncreate'     => 'तुमच्या विपत्र पत्त्यावर निश्चितीकरण कुटसंकेत पाठवला होता .
हा कुटसंकेत तुम्हाला खात्यात दाखल होण्याकरिता लागणार नाही,पण तुम्हाला तो कोणतीही विपत्रावर अवलंबून कोणतीही सुविधा उपलब्ध करून घेण्यापूर्वी द्यावा लागेल.',
'confirmemail_sendfailed'   => 'पोच-विपत्र पाठवू शकलो नाही. अयोग्य चिन्हांकरिता पत्ता तपासा.

मेलर परत आले: $1',
'confirmemail_invalid'      => 'अयोग्य निश्चितीकरण नियमावली.नियमावली काल समाप्त झाला असु शकेल.',
'confirmemail_needlogin'    => 'तुमचा विपत्रपत्ता प्रमाणित करण्यासाठी तुम्ही $1 करावयास हवे.',
'confirmemail_success'      => 'तुमचा विपत्र (इमेल) पत्ता प्रमाणित झाला आहे.तुम्ही आता दाखल होऊ शकता आणि विकिचा आनंद घेऊ शकता.',
'confirmemail_loggedin'     => 'तुमचा विपत्र (इमेल) पत्ता आता प्रमाणित झाला आहे.',
'confirmemail_error'        => 'तुमची निश्चिती जतन करताना काही तरी चूकले',
'confirmemail_subject'      => '{{SITENAME}} विपत्र (इमेल) पत्ता प्रमाणित',
'confirmemail_body'         => 'कुणीतरी, बहुतेक तुम्ही, $1 या पत्त्यावारून, "$2" खाते हा ईमेल पत्ता वापरून {{SITENAME}} या संकेतस्थळावर उघडले आहे.

हे खाते खरोखर तुमचे आहे याची खात्री करण्यासाठी आणि {{SITENAME}} वर ईमेल पर्याय उत्तेजित (उपलब्ध) करण्यासाठी, हा दुवा तुमच्या ब्राउजर मधे उघडा:

$3

जर तुम्ही हे खाते उघडले *नसेल* तर ही मागणी रद्द करण्यासाठी खालील दुवा उघडा:

$5

हा हमी कलम $4 ला नष्ट होईल.',
'confirmemail_body_changed' => '

{{SITENAME}} या संकेतस्थळावर कुणीतरी, बहुतेक तुम्ही, $1 या अंकपत्त्यावारून, "$2" खात्याकरिताचा  ईमेल   आपल्या या इमेल पत्त्यावर बदलला आहे.

हे खाते खरोखर तुमचे आहे याची खात्री करण्यासाठी आणि {{SITENAME}} वर ईमेल पर्याय उत्तेजित (उपलब्ध) करण्यासाठी, हा दुवा तुमच्या ब्राउजर मधे उघडा:

$3

जर तुम्ही हे खाते तुमचे *नसेल* तर ही इमेलपत्त्याच्या बदलाची मागणी रद्द करण्यासाठी खालील दुवा उघडा:

$5

हा  निश्चितीकरण संदेश  $4 ला नष्ट होईल.',
'confirmemail_body_set'     => '{{SITENAME}} या संकेतस्थळावर कुणीतरी, बहुतेक तुम्ही, $1 या अंकपत्त्यावारून, "$2" खात्याकरिताचा  ईमेल  आपल्या या इमेल पत्त्यानुसार दिला आहे.

हे खाते खरोखर तुमचे आहे याची खात्री करण्यासाठी आणि {{SITENAME}} वर ईमेल पर्याय उत्तेजित (उपलब्ध) करण्यासाठी, हा दुवा तुमच्या ब्राउजर मधे उघडा:

$3

जर तुम्ही हे खाते तुमचे *नसेल* तर ही इमेलपत्त्याच्या बदलाची मागणी रद्द करण्यासाठी खालील दुवा उघडा:

$5

हा खात्रीकरण संदेश  $4 ला नष्ट होईल.',
'confirmemail_invalidated'  => 'इ-मेल पत्ता तपासणी रद्द करण्यात आलेली आहे',
'invalidateemail'           => 'इ-मेल तपासणी रद्द करा',

# Scary transclusion
'scarytranscludedisabled' => '[आंतरविकि आंतरन्यास अनुपलब्ध केले आहे]',
'scarytranscludefailed'   => '[क्षमस्व;$1करिताची साचा ओढी फसली]',
'scarytranscludetoolong'  => '[आंतरजालपत्ता खूप लांब आहे]',

# Delete conflict
'deletedwhileediting'      => '”’सूचना:”’ तुम्ही संपादन सुरू केल्यानंतर हे पान वगळले गेले आहे.',
'confirmrecreate'          => "तुम्ही संपादन सुरू केल्यानंतर सदस्य [[User:$1|$1]] ([[User talk:$1|चर्चा]])ने हे पान पुढील कारणाने वगळले:
: ''$2''
कृपया हे पान खरेच पुन्हा निर्मीत करून हवे आहे का हे निश्चित करा.",
'confirmrecreate-noreason' => 'तुम्ही संपादन सुरू केल्यानंतर सदस्य [[User:$1|$1]] ([[User talk:$1|चर्चा]])ने हे पान  वगळले. तुम्हाला हे पान खरेच पुन्हा निर्मित करून हवे आहे का हे निश्चित करा.',
'recreate'                 => 'पुनर्निर्माण',

# action=purge
'confirm_purge_button' => 'ठीक',
'confirm-purge-top'    => 'यापानाची सय रिकामी करावयाची आहे?',
'confirm-purge-bottom' => 'पानाची अती अलिकडील आवृत्ती सादर करण्यासाठी त्या पानाचे क्षालन,  पानाची सय ( पानाचे पर्जींग पानाची cache )  रिकामी करते .',

# action=watch/unwatch
'confirm-watch-button'   => 'ठीक आहे',
'confirm-watch-top'      => 'हे पान तुमच्या पहारा सूचीत टाकायचे?',
'confirm-unwatch-button' => 'ठीक',
'confirm-unwatch-top'    => 'हे पान पहार्‍याच्या सूचीतून काढायचे?',

# Multipage image navigation
'imgmultipageprev' => '← मागील पान',
'imgmultipagenext' => 'पुढील पान →',
'imgmultigo'       => 'चला!',
'imgmultigoto'     => '$1 पानावर जा',

# Table pager
'ascending_abbrev'         => 'चढ',
'descending_abbrev'        => 'उतर',
'table_pager_next'         => 'पुढील पान',
'table_pager_prev'         => 'मागील पान',
'table_pager_first'        => 'पहिले पान',
'table_pager_last'         => 'शेवटचे पान',
'table_pager_limit'        => 'एका पानावर $1 नग दाखवा',
'table_pager_limit_label'  => 'निकाल प्रतिपान:',
'table_pager_limit_submit' => 'चला',
'table_pager_empty'        => 'निकाल नाहीत',

# Auto-summaries
'autosumm-blank'   => 'या पानावरील सगळा मजकूर काढला',
'autosumm-replace' => "पान '$1' वापरून बदलले.",
'autoredircomment' => '[[$1]] कडे पुनर्निर्देशित',
'autosumm-new'     => 'नवीन पान: $1',

# Live preview
'livepreview-loading' => 'चढवत आहे…',
'livepreview-ready'   => 'चढवत आहे… तयार!',
'livepreview-failed'  => 'प्रत्यक्ष ताजी झलक अयश्स्वी! नेहमीची झलक पहा.',
'livepreview-error'   => 'संपर्कात अयशस्वी: $1 "$2".नेहमीची झलक पहा.',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 {{PLURAL:$1|सेकंदाच्या|सेकंदांच्या}} आतले बदल या यादी नसण्याची शक्यता आहे.',
'lag-warn-high'   => 'विदा विदादात्यास लागणार्‍या अत्युच्च कालावधी मुळे, $1 {{PLURAL:$1|सेकंदापेक्षा|सेकंदांपेक्षा}} नवे बदल या सूचीत न दाखवले जाण्याची शक्यता आहे.',

# Watchlist editor
'watchlistedit-numitems'       => 'चर्चा पाने सोडून, {{PLURAL:$1|1 शीर्षक पान|$1 शीर्षक पाने}} तुमच्या पहार्‍याच्या सूचीत आहेत.',
'watchlistedit-noitems'        => 'पहार्‍याच्या सूचीत कोणतेही शीर्षक पान नोंदलेले नाही.',
'watchlistedit-normal-title'   => 'पहार्‍याची सूची संपादीत करा',
'watchlistedit-normal-legend'  => 'शीर्षकपाने पहार्‍याच्या सूचीतून वगळा',
'watchlistedit-normal-explain' => 'तुमच्या पहार्‍याच्या सूचीतील अंतर्भूत नामावळी खाली निर्देशीत केली आहे. शीर्षक वगळण्याकरिता, त्या पुढील खिडकी निवडा, आणि शीर्षक वगळावर टिचकी मारा. तुम्ही [[Special:EditWatchlist/raw|कच्ची यादी सुद्धा संपादित]] करू शकता.',
'watchlistedit-normal-submit'  => 'शिर्षक वगळा',
'watchlistedit-normal-done'    => 'तुमच्या पहार्‍याच्या सूचीतून वगळलेली {{PLURAL:$1|1 शिर्षक होते |$1 शिर्षके होती }}:',
'watchlistedit-raw-title'      => 'कच्ची पहार्‍याची सूची संपादीत करा.',
'watchlistedit-raw-legend'     => 'कच्ची पहार्‍याची सूची संपादीत करा.',
'watchlistedit-raw-explain'    => 'तुमच्या पहार्‍याच्या सूचीतील अंतर्भूत नामावळी खाली निर्देशीत केली आहे, एका ओळीत एक नाव या पद्धतीने; ह्या यादीतील नावे वगळून किंवा भर घालून संपादित करून नामावळी अद्यावत(परिष्कृत) करता येते.
पहार्‍याची सूची अद्यावत करा येथे टिचकी मारा.
तुम्ही [[Special:EditWatchlist|प्रस्थापित संपादकाचा उपयोग]] सुद्धा करू शकता.',
'watchlistedit-raw-titles'     => 'शिर्षके:',
'watchlistedit-raw-submit'     => 'पहार्‍याची सूची अद्यावत करा.',
'watchlistedit-raw-done'       => 'तुमची पहार्‍याची सूची परिष्कृत करण्यात आली आहे.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 शिर्षक होते |$1 शिर्षक होती }} भर घातली:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 शिर्षक होते |$1 शिर्षक होती }} वगळले:',

# Watchlist editing tools
'watchlisttools-view' => 'सुयोग्य बदल पहा',
'watchlisttools-edit' => 'पहार्‍याची सूची पहा आणि संपादीत करा',
'watchlisttools-raw'  => 'कच्ची पहार्‍याची सूची संपादीत करा',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|चर्चा]])',

# Core parser functions
'unknown_extension_tag' => 'अज्ञात विस्तार खूण "$1"',
'duplicate-defaultsort' => '\'\'\'वॉर्निंग:\'\'\' डिफॉल्ट सॉर्ट की "$2"ओवर्राइड्स अर्लीयर डिफॉल्ट सॉर्ट की "$1".',

# Special:Version
'version'                       => 'आवृत्ती',
'version-extensions'            => 'स्थापित विस्तार',
'version-specialpages'          => 'विशेष पाने',
'version-parserhooks'           => 'पृथकक अंकुश',
'version-variables'             => 'चल',
'version-antispam'              => 'उत्पात प्रतिबंधन',
'version-skins'                 => 'त्वचा',
'version-other'                 => 'इतर',
'version-mediahandlers'         => 'मिडिया हॅंडलर',
'version-hooks'                 => 'अंकुश',
'version-extension-functions'   => 'अतिविस्तार(एक्स्टेंशन) कार्ये',
'version-parser-extensiontags'  => 'पृथकक विस्तारीत खूणा',
'version-parser-function-hooks' => 'पृथकक कार्य अंकुश',
'version-hook-name'             => 'अंकुश नाव',
'version-hook-subscribedby'     => 'वर्गणीदार',
'version-version'               => '(आवृत्ती $1)',
'version-license'               => 'परवाना',
'version-poweredby-credits'     => "हा विकी '''[//www.mediawiki.org/ मीडियाविकी]'''द्वारे संचालित आहे, प्रताधिकारित © २००१-$1 $2.",
'version-poweredby-others'      => 'इतर',
'version-license-info'          => 'मिडियाविकि हे  मुक्त संगणक प्रणाली विकि पॅकेज आहे.Free Software Foundation प्रकाशित  GNU General Public परवान्याच्या अटीस अनुसरून तुम्ही त्यात बदल आणि/अथवा त्याचे  पुर्नवितरण  करू शकता.

मिडियाविकि  संगणक प्रणाली उपयूक्त ठरेल या आशेने वितरीत केली जात असली तरी;कोणत्याही वितरणास अथवा विशीष्ट उद्देशाकरिता योग्यतेची अगदी कोणतीही अप्रत्यक्ष अथवा उपलक्षित   अथवा  निहित अशा अथवा कोणत्याही प्रकारच्या केवळ  कोणत्याही प्राश्वासनाशिवायच (WITHOUT ANY WARRANTY) उपलब्ध आहे.अधिक माहिती करिता   GNU General Public License पहावे.

तुम्हाला या प्रणाली सोबत [{{SERVER}}{{SCRIPTPATH}}/COPYING  GNU General Public License परवान्याची प्रत] मिळालेली असावयास हवी, तसे नसेल तर,[//www.gnu.org/licenses/old-licenses/gpl-2.0.html  येथे ऑनलाईन वाचा] किंवा the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA ला लिहा.',
'version-software'              => 'स्थापित संगणक प्रणाली (Installed software)',
'version-software-product'      => 'उत्पादन',
'version-software-version'      => 'आवृत्ती',

# Special:FilePath
'filepath'         => 'संचिका मार्ग',
'filepath-page'    => 'संचिका:',
'filepath-submit'  => 'चला',
'filepath-summary' => 'हे विशेष पान संचिकेचा संपूर्ण मार्ग कळवते.
चित्रे संपूर्ण रिझोल्यूशन मध्ये दाखवली आहेत,इतर संचिका प्रकार त्यांच्या संबधीत प्रोग्रामने प्रत्यक्ष सुरू होतात.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'जुळ्या संचिका शोधा',
'fileduplicatesearch-summary'   => 'हॅश किंमतीप्रमाणे जुळ्या संचिका शोधा.',
'fileduplicatesearch-legend'    => 'जुळी संचिका शोधा',
'fileduplicatesearch-filename'  => 'संचिकानाव:',
'fileduplicatesearch-submit'    => 'शोधा',
'fileduplicatesearch-info'      => '$1 × $2 पीक्सेल<br />संचिकेचा आकार: $3<br />MIME प्रकार: $4',
'fileduplicatesearch-result-1'  => '"$1" या संचिकेशी जुळणारी जुळी संचिका सापडली नाही.',
'fileduplicatesearch-result-n'  => '"$1" ला {{PLURAL:$2|१ जुळी संचिका आहे|$2 जुळ्या संचिका आहेत}}.',
'fileduplicatesearch-noresults' => '"$1" या नावाची संचिका सापडली नाही.',

# Special:SpecialPages
'specialpages'                   => 'विशेष पृष्ठे',
'specialpages-note'              => '* सर्वसाधारण विशेष पृष्ठे.
* <strong class="mw-specialpagerestricted">प्रतिबंधित विशेष पृष्ठे.</strong>',
'specialpages-group-maintenance' => 'व्यवस्थापन अहवाल',
'specialpages-group-other'       => 'इतर विशेष पृष्ठे',
'specialpages-group-login'       => 'प्रवेश / नवीन सदस्य नोंदणी',
'specialpages-group-changes'     => 'अलीकडील बदल व सूची',
'specialpages-group-media'       => 'मीडिया अहवाल व चढविलेल्या संचिका',
'specialpages-group-users'       => 'सदस्य व अधिकार',
'specialpages-group-highuse'     => 'सर्वात जास्त वापरली जाणारी पृष्ठे',
'specialpages-group-pages'       => 'पृष्ठ याद्या',
'specialpages-group-pagetools'   => 'पृष्ठ उपकरणे',
'specialpages-group-wiki'        => 'विकि डाटा व उपकरणे',
'specialpages-group-redirects'   => 'पुनर्निर्देशन करणारी विशेष पृष्ठे',
'specialpages-group-spam'        => 'उत्पात साधने',

# Special:BlankPage
'blankpage'              => 'रिकामे पान',
'intentionallyblankpage' => 'हे पान मुद्दाम कोरे सोडण्यात आले आहे.',

# External image whitelist
'external_image_whitelist' => '#हे ओल बरोबर जशि आहे तशि घेने.
#नेहमि वपरले जानारे सर्व चीह्न्न् वपरने.
#बाहेरिल सर्व चित्राना ह्यासोबत जोद्दले जाइल.
#ह्या मधिल जुललेले सर्व चित्र म्हनुन दखवले जतिल,अथवा चित्राचि फक़्त् लिन्क दखवलि जाइल.
## ह्या चिह्ना पासुन सुरु झलेल्या सर्व ओलि कमेन्त म्हनुन वपरर्ल्या जातिल.
#हे केस सेन्सेतिव आहे.',

# Special:Tags
'tags'                    => 'मान्य बदल खुणा',
'tag-filter'              => '[[Special:Tags|खूण]] गाळक:',
'tag-filter-submit'       => 'गाळक',
'tags-title'              => 'खुणा',
'tags-intro'              => 'प्रणालीतून विशिष्ट संपादनांच्या अर्थासहीत  खूणांची  यादी नमुद करणारे पान',
'tags-tag'                => 'खूण नाव',
'tags-display-header'     => 'बदल सुचीवर कसे दिसेल',
'tags-description-header' => 'अर्थाची पूर्ण माहिती',
'tags-hitcount-header'    => 'खुणा केलेले बदल',
'tags-edit'               => 'संपादन करा',
'tags-hitcount'           => '$1 {{PLURAL:$1|बदल|बदल}}',

# Special:ComparePages
'comparepages'                => 'पानांची तुलना करा',
'compare-selector'            => 'पानांच्या आवर्तनांची तुलना करा',
'compare-page1'               => 'पान १',
'compare-page2'               => 'पान २',
'compare-rev1'                => 'आवर्तन १',
'compare-rev2'                => 'आवर्तन २',
'compare-submit'              => 'तुलना करा',
'compare-invalid-title'       => 'तुम्ही नमुद केलेले शीर्षक अग्राह्य आहे.',
'compare-title-not-exists'    => 'या नावाने काहीही अस्तित्वात नाही.',
'compare-revision-not-exists' => 'आपण नमुद करत असलेली आवृत्ती अस्तित्वात नाही.',

# Database error messages
'dberr-header'      => 'या विकीत एक चूक आहे',
'dberr-problems'    => 'माफ करा, हे संकेतस्थळ सध्या तांत्रिक अडचणींना सामोरे जात आहे.',
'dberr-again'       => 'थोडा वेळ थांबून पुन्हा पहा.',
'dberr-info'        => '( विदादाताशी संपर्क साधण्यात  असमर्थ : $1)',
'dberr-usegoogle'   => 'तोपर्यंत गूगलवर शोधून पहा',
'dberr-outofdate'   => 'लक्षात घ्या, आमच्या मजकुराबाबत त्यांची सुची कालबाह्य असु शकते',
'dberr-cachederror' => 'ही मागवलेल्या पानाची सयीतील प्रत आहे, ती अद्ययावत नसण्याची शक्यता आहे.',

# HTML forms
'htmlform-invalid-input'       => 'तुम्ही दिलेल्या माहितीत काहीतरी गडबड आहे',
'htmlform-select-badoption'    => 'आपण नमुद करत असलेली व्हॅल्यू ग्राह्य पर्याय ठरत नाही',
'htmlform-int-invalid'         => 'आपण नमुद केलेली व्हॅल्यू पूर्णांक (इंटीजर) नाही.',
'htmlform-float-invalid'       => 'तुम्ही दिलेली किंमत आकडा नाही.',
'htmlform-int-toolow'          => '$1 किंवा मोठा आकडा द्या.',
'htmlform-int-toohigh'         => '$1 किंवा त्याहून छोटा आकडा द्या.',
'htmlform-required'            => 'ही किंमत आवश्यक आहे',
'htmlform-submit'              => 'पाठवा',
'htmlform-reset'               => 'बदल उलटवा',
'htmlform-selectorother-other' => 'इतर',

# SQLite database support
'sqlite-has-fts' => 'पूर्ण-मजकूर शोध समर्थनासहित $1',
'sqlite-no-fts'  => 'पूर्ण-मजकूर शोध समर्थनाविरहित $1',

# New logging system
'logentry-delete-delete'              => '$1 {{GENDER:$2|वगळले}} पान $3',
'logentry-delete-restore'             => '$1 {{GENDER:$2|restored}} पृष्ठ  $3',
'logentry-delete-event'               => ' $3: $4 वरील  {{PLURAL:$5|एका नोंद घटने |$5 lनोंद घटनां}} ची दृष्यता $1 {{GENDER:$2|बदलली}}',
'logentry-delete-revision'            => '$3: $4 पानावरील  {{PLURAL:$5|एका आवृत्ती |$5 lआवृत्यां}} ची दृष्यता $1 {{GENDER:$2|बदलली}}',
'logentry-delete-event-legacy'        => '$3 वरील नोंदींची दृष्यता $1 {{GENDER:$2|बदलली}}',
'logentry-delete-revision-legacy'     => '$3 वरील आवृत्त्यांची दृष्यता $1 {{GENDER:$2|बदलली}}',
'logentry-suppress-delete'            => '$1 {{GENDER:$2|लपवले }} पान $3',
'logentry-suppress-event'             => ' $3: $4 वरील  {{PLURAL:$5|एका नोंद घटने |$5 lनोंद घटनां}} ची दृष्यता $1 ने गुप्ततेने  {{GENDER:$2|बदलली}}',
'logentry-suppress-revision'          => '$3: $4 वरील  {{PLURAL:$5|आवृत्ती|$5 lआवृत्यां}} ची दृष्यता $1 ने गुप्ततेने  {{GENDER:$2|बदलली}}',
'logentry-suppress-event-legacy'      => '$3 वरील नोंदींची दृष्यता $1 ने गोपनियतेने  {{GENDER:$2|बदलली}}',
'logentry-suppress-revision-legacy'   => '$3 वरील आवृत्त्यांची दृष्यता $1 ने गोपनियतेने {{GENDER:$2|बदलली}}',
'revdelete-content-hid'               => 'माहिती लपवली आहे',
'revdelete-summary-hid'               => 'बदलांचा आढावा लुप्त',
'revdelete-uname-hid'                 => 'सदस्यनाम लपवलेले आहे',
'revdelete-content-unhid'             => 'माहिती लपवलीनाही',
'revdelete-summary-unhid'             => 'बदलांचा आढावा दृष्य',
'revdelete-uname-unhid'               => 'सदस्यनाम लपवलेले नाही',
'revdelete-restricted'                => 'प्रबंधकांना बंधने दिली',
'revdelete-unrestricted'              => 'प्रबंधकांची बंधने काढली',
'logentry-move-move'                  => '  $3पान    $4 कडे  $1 {{GENDER:$2|स्थानांतरीत}}',
'logentry-move-move-noredirect'       => '$1 ने $3 हे पान पुनर्निर्देशीत न करता $4 येथे {{GENDER:$2|स्थानांतरीत केले}}',
'logentry-move-move_redir'            => '$1 {{GENDER:$2|यांनी}} $3 हे पान पुनर्निर्देशन लावुन $4 येथे हलवले',
'logentry-move-move_redir-noredirect' => '$1 ने $3 हे पान पुनर्निर्देशीत न करता $4 येथे पुर्ननिर्देशनावर  {{GENDER:$2|स्थानांतरीत केले}}',
'logentry-patrol-patrol'              => '  $3  पानाच्या  $1 {{GENDER:$2|सुचवलेल्या}}  $4 आवृत्तीस गस्त घातली',
'logentry-patrol-patrol-auto'         => '  $3  पानाच्या  $1 {{GENDER:$2|सुचवलेल्या}}  $4 आवृत्तीस स्वयंचलित गस्त घातली',
'logentry-newusers-newusers'          => 'एक सदस्यखाते $1 {{GENDER:$2|तयार केले}}',
'logentry-newusers-create'            => 'एक सदस्यखाते $1 {{GENDER:$2|तयार केले}}',
'logentry-newusers-create2'           => '$1  ने  {{GENDER:$4|सदस्य खाते}} $3  {{GENDER:$2|निर्मित}} केले  आहे.',
'logentry-newusers-autocreate'        => '$1  खाते स्वयमेव {{GENDER:$2|निर्मित}} झाले आहे.',
'newuserlog-byemail'                  => 'परवलीचा शब्द इमेलमार्फत पाठविलेला आहे',

# Feedback
'feedback-subject' => 'विषय:',
'feedback-message' => 'संदेश:',
'feedback-cancel'  => 'रद्द करा',
'feedback-submit'  => 'प्रतिक्रिया द्या',
'feedback-error2'  => 'त्रुटी: संपादन रद्द',

);
