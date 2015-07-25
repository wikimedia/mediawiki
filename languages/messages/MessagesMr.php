<?php
/** Marathi (मराठी)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Aan
 * @author Angela
 * @author Ankitgadgil
 * @author Ashupawar
 * @author Balaji
 * @author Chandu
 * @author Dnyanesh325
 * @author Harshalhayat
 * @author Hemanshu
 * @author Hemant wikikosh1
 * @author Htt
 * @author Kaajawa
 * @author Kaganer
 * @author Kaustubh
 * @author Mahitgar
 * @author Marathipremi101
 * @author Mohanpurkar
 * @author Mvkulkarni23
 * @author Prabodh1987
 * @author Pranav jagtap
 * @author Rahuldeshmukh101
 * @author Rdeshmuk
 * @author Sankalpdravid
 * @author Sau6402
 * @author Shantanoo
 * @author Shreewiki
 * @author Shreyas19
 * @author Sudhanwa
 * @author Tusharpawar1982
 * @author V.narsikar
 * @author Vibhavari
 * @author Vpnagarkar
 * @author Ydyashad
 * @author Ynwala
 * @author अभय नातू
 * @author कोलࣿहापࣿरी
 * @author कोल्हापुरी
 * @author प्रणव कुलकर्णी
 * @author प्रतिमा
 * @author शࣿरीहरि
 * @author संतोष दहिवळ
 */

$namespaceNames = array(
	NS_MEDIA            => 'मिडिया',
	NS_SPECIAL          => 'विशेष',
	NS_TALK             => 'चर्चा',
	NS_USER             => 'सदस्य',
	NS_USER_TALK        => 'सदस्य_चर्चा',
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
	'Activeusers'               => array( 'सक्रिय_सदस्य' ),
	'Allmessages'               => array( 'सर्व_निरोप' ),
	'Allpages'                  => array( 'सर्व_पाने' ),
	'Ancientpages'              => array( 'जुनी_पाने' ),
	'Blankpage'                 => array( 'कोरे_पान' ),
	'Block'                     => array( 'प्रतिबंध', 'अंकपत्ता_प्रतिबंध', 'सदस्य_प्रतिबंध' ),
	'Booksources'               => array( 'पुस्तक_स्रोत' ),
	'BrokenRedirects'           => array( 'चुकीची_पुनर्निर्देशने' ),
	'Categories'                => array( 'वर्ग' ),
	'ChangeEmail'               => array( 'विपत्र_बदला' ),
	'ChangePassword'            => array( 'परवलीचा_शब्द_बदला' ),
	'ComparePages'              => array( 'पानांची_तुलना' ),
	'Confirmemail'              => array( 'विपत्र_नक्की_करा' ),
	'Contributions'             => array( 'योगदान' ),
	'CreateAccount'             => array( 'सदस्य_नोंद' ),
	'Deadendpages'              => array( 'टोकाची_पाने' ),
	'DeletedContributions'      => array( 'वगळलेली_योगदाने' ),
	'DoubleRedirects'           => array( 'दुहेरी_पुनर्निर्देशने' ),
	'Emailuser'                 => array( 'विपत्र_वापरकर्ता' ),
	'ExpandTemplates'           => array( 'साचेविस्तारकरा' ),
	'Export'                    => array( 'निर्यात' ),
	'Fewestrevisions'           => array( 'कमीत_कमी_आवर्तने' ),
	'FileDuplicateSearch'       => array( 'दुहेरी_संचिका_शोध' ),
	'Filepath'                  => array( 'संचिकेचा_पत्ता_(पाथ)' ),
	'Import'                    => array( 'आयात' ),
	'Invalidateemail'           => array( 'अग्राह्य_विपत्र' ),
	'BlockList'                 => array( 'प्रतिबंधन_सुची' ),
	'LinkSearch'                => array( 'दुवाशोध' ),
	'Listadmins'                => array( 'प्रबंधकांची_यादी' ),
	'Listbots'                  => array( 'सांगकाम्यांची_यादी' ),
	'Listfiles'                 => array( 'चित्रयादी' ),
	'Listgrouprights'           => array( 'गट_अधिकार_यादी' ),
	'Listredirects'             => array( 'पुर्ननिर्देशन_सुची' ),
	'Listusers'                 => array( 'सदस्यांची_यादी' ),
	'Lockdb'                    => array( 'डेटाबेस_कुलुपबंद_करा' ),
	'Log'                       => array( 'नोंद', 'नोंदी' ),
	'Lonelypages'               => array( 'एकाकी_पाने' ),
	'Longpages'                 => array( 'मोठी_पाने' ),
	'MergeHistory'              => array( 'इतिहास_एकत्र_करा' ),
	'MIMEsearch'                => array( 'माईम‌_शोध' ),
	'Mostcategories'            => array( 'सर्वात_जास्त_वर्ग' ),
	'Mostimages'                => array( 'सर्वाधिक_सांधलेली_संचिका' ),
	'Mostlinked'                => array( 'सर्वात_जास्त_जोडलेली' ),
	'Mostlinkedcategories'      => array( 'सर्वात_जास्त_जोडलेले_वर्ग', 'सर्वात_जास्त_वापरलेले_वर्ग' ),
	'Mostlinkedtemplates'       => array( 'सर्वात_जास्त_जोडलेले_साचे', 'सर्वात_जास्त_वापरलेले_साचे' ),
	'Mostrevisions'             => array( 'सर्वाधिकआवर्तने' ),
	'Movepage'                  => array( 'पान_हलवा' ),
	'Mycontributions'           => array( 'माझे_योगदान' ),
	'MyLanguage'                => array( 'माझीभाषा' ),
	'Mypage'                    => array( 'माझे_पान' ),
	'Mytalk'                    => array( 'माझ्या_चर्चा' ),
	'Newimages'                 => array( 'नवीन_संचिका', 'नवीन_चित्रे' ),
	'Newpages'                  => array( 'नवीन_पाने' ),
	'Preferences'               => array( 'पसंती' ),
	'Prefixindex'               => array( 'उपसर्गसुची' ),
	'Protectedpages'            => array( 'सुरक्षित_पाने' ),
	'Protectedtitles'           => array( 'सुरक्षित_शीर्षके' ),
	'Randompage'                => array( 'कोणतेही', 'कोणतेही_पृष्ठ' ),
	'Randomredirect'            => array( 'अविशिष्ट_पुर्ननिर्देशन' ),
	'Recentchanges'             => array( 'अलीकडील_बदल' ),
	'Recentchangeslinked'       => array( 'सांधलेलेअलिकडीलबदल' ),
	'Revisiondelete'            => array( 'आवर्तनवगळा' ),
	'Search'                    => array( 'शोधा' ),
	'Shortpages'                => array( 'छोटी_पाने' ),
	'Specialpages'              => array( 'विशेष_पाने' ),
	'Statistics'                => array( 'सांख्यिकी' ),
	'Tags'                      => array( 'खूणा' ),
	'Uncategorizedcategories'   => array( 'अवर्गीकृत_वर्ग' ),
	'Uncategorizedimages'       => array( 'अवर्गीकृत_संचिका', 'अवर्गीकृत_चित्रे' ),
	'Uncategorizedpages'        => array( 'अवर्गीकृत_पाने' ),
	'Uncategorizedtemplates'    => array( 'अवर्गीकृत_साचे' ),
	'Undelete'                  => array( 'काढणे_रद्द_करा' ),
	'Unlockdb'                  => array( 'विदागारताळेउघडा' ),
	'Unusedcategories'          => array( 'न_वापरलेले_वर्ग' ),
	'Unusedimages'              => array( 'न_वापरलेली_चित्रे' ),
	'Unusedtemplates'           => array( 'उपयोगात_नसलेले_साचे' ),
	'Unwatchedpages'            => array( 'अप्रेक्षीत_पाने' ),
	'Upload'                    => array( 'चढवा' ),
	'Userlogin'                 => array( 'सदस्य_प्रवेश' ),
	'Userlogout'                => array( 'सदस्य‌_बहिर्गमन' ),
	'Userrights'                => array( 'खातेदाराचे_अधिकार' ),
	'Version'                   => array( 'आवृत्ती' ),
	'Wantedcategories'          => array( 'हवे_असलेले_वर्ग' ),
	'Wantedfiles'               => array( 'संचिका_हवी' ),
	'Wantedpages'               => array( 'हवे_असलेले_लेख' ),
	'Wantedtemplates'           => array( 'साचा_हवा' ),
	'Watchlist'                 => array( 'नित्य‌_पहाण्याची_सूची' ),
	'Whatlinkshere'             => array( 'येथे_काय_जोडले_आहे' ),
	'Withoutinterwiki'          => array( 'आंतरविकि_शिवाय' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#पुनर्निर्देशन', '#पुर्ननिर्देशन', '#REDIRECT' ),
	'notoc'                     => array( '0', '__अनुक्रमणिकानको__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__प्रदर्शननको__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__अनुक्रमणिकाहवीच__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__अनुक्रमणिका__', '__TOC__' ),
	'noeditsection'             => array( '0', '__विभागअसंपादनक्षम__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'सद्यमहिना', 'सद्यमहिना२', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'सद्यमहिना१', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'सद्यमहिनानाव', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'सद्यमहिनासाधारण', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'सद्यमहिनासंक्षीप्त', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'सद्यदिवस', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'सद्यदिवस२', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'सद्यदिवसनाव', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'सद्यवर्ष', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'सद्यवेळ', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'सद्यतास', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'स्थानिकमहिना', 'स्थानिकमहिना२', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'स्थानिकमहिना१', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'स्थानिकमहिनानाव', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'स्थानिकमहिनासाधारण', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'स्थानिकमहिनासंक्षीप्त', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'स्थानिकदिवस', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'स्थानिकदिवस२', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'स्थानिकदिवसनाव', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'स्थानिकवर्ष', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'स्थानिकवेळ', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'स्थानिकतास', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'पानसंख्या', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'लेखसंख्या', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'संचिकासंख्या', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'सदस्यसंख्या', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'सक्रीयसदस्यसंख्या', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'संपादनसंख्या', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'लेखनाव', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'लेखानावव', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'नामविश्व', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'नामविश्वा', 'नामविश्वाचे', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', 'नामविश्वक्रमांक', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', 'चर्चाविश्व', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'चर्चाविश्वा', 'चर्चाविश्वाचे', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'विषयविश्व', 'लेखविश्व', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'विषयविश्वा', 'लेखविश्वा', 'विषयविश्वाचे', 'लेखविश्वाचे', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'पूर्णलेखनाव', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'पूर्णलेखनावे', 'अंशदुवा', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'उपपाननाव', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'उपपाननावे', 'उपपाननावाचे', 'उपौंशदुवा', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'मूळपाननाव', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'मूळपाननावे', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'चर्चापाननाव', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'चर्चापाननावे', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'विषयपाननाव', 'लेखपाननाव', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'विषयपाननावे', 'लेखपाननावे', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'संदेश:', 'निरोप:', 'MSG:' ),
	'subst'                     => array( '0', 'पर्याय:', 'समाविष्टी:', 'अबाह्य:', 'निरकंसबिंब:', 'कंसत्याग:', 'साचाहिन:', 'साचान्तर:', 'साचापरिस्फोट:', 'साचोद्घाटन:', 'SUBST:' ),
	'msgnw'                     => array( '0', 'संदेशनवा:', 'निरोपनवा:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'इवलेसे', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'इवलेसे=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'उजवे', 'right' ),
	'img_left'                  => array( '1', 'डावे', 'left' ),
	'img_none'                  => array( '1', 'कोणतेचनाही', 'नन्ना', 'none' ),
	'img_width'                 => array( '1', '$1अंश', '$1कणी', '$1पक्ष', '$1px' ),
	'img_center'                => array( '1', 'मध्यवर्ती', 'center', 'centre' ),
	'img_framed'                => array( '1', 'चौकट', 'फ़्रेम', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'विनाचौकट', 'विनाफ़्रेम', 'frameless' ),
	'img_page'                  => array( '1', 'पान=$1', 'पान_$1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'उभा', 'उभा=$1', 'उभा_$1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'सीमा', 'border' ),
	'img_baseline'              => array( '1', 'तळरेषा', 'आधाररेषा', 'baseline' ),
	'img_sub'                   => array( '1', 'अधो', 'sub' ),
	'img_super'                 => array( '1', 'उर्ध्व', 'super', 'sup' ),
	'img_top'                   => array( '1', 'अत्यूच्च', 'top' ),
	'img_text_top'              => array( '1', 'मजकूर-शीर्ष', 'शीर्ष-मजकूर', 'text-top' ),
	'img_middle'                => array( '1', 'मध्य', 'middle' ),
	'img_bottom'                => array( '1', 'तळ', 'बूड', 'bottom' ),
	'img_text_bottom'           => array( '1', 'मजकुरतळ', 'text-bottom' ),
	'img_link'                  => array( '1', 'दुवा=$1', 'link=$1' ),
	'img_alt'                   => array( '1', 'अल्ट=$1', 'alt=$1' ),
	'int'                       => array( '0', 'इन्ट:', 'INT:' ),
	'sitename'                  => array( '1', 'संकेतस्थळनाव', 'SITENAME' ),
	'ns'                        => array( '0', 'नावि:', 'NS:' ),
	'nse'                       => array( '0', 'नाविअरिक्त:', 'नाव्यारिक्त:', 'नाव्याख:', 'NSE:' ),
	'localurl'                  => array( '0', 'स्थानिकस्थळ:', 'स्थानिकसंकेतस्थळ:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'स्थानिकस्थली:', 'LOCALURLE:' ),
	'server'                    => array( '0', 'विदादाता', 'SERVER' ),
	'servername'                => array( '0', 'विदादातानाव', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'संहीतामार्ग', 'SCRIPTPATH' ),
	'grammar'                   => array( '0', 'व्याकरण:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'लिंग:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__विनाशीर्षकबदल__', '__विनाशीब__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__विनामजकुरबदल__', '__विनामब__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'सद्यआठवडा', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'सद्यउतरण', 'सद्यउतार', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'स्थानिकआठवडा', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'स्थानिकउतरण', 'स्थानिकउतार', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'आवृत्तीक्र्मांक', 'REVISIONID' ),
	'revisionday'               => array( '1', 'आवृत्तीदिन', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'आवृत्तीदिन२', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'आवृत्तीमास', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'आवृत्तीवर्ष', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'आवृत्तीमुद्रा', 'आवृत्तीठसा', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'आवृत्तीसदस्य', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'बहुवचन:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'संपूर्णसंस्थळ', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'संपूर्णसंस्थली:', 'संपूर्णसंस्थळी:', 'FULLURLE:' ),
	'raw'                       => array( '0', 'कच्चे:', 'RAW:' ),
	'displaytitle'              => array( '1', 'शीर्षकदाखवा', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', 'ॠ', 'R' ),
	'newsectionlink'            => array( '1', '__नवविभागदुवा__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__विनानवविभागदुवा__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'सद्यआवृत्ती', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'संकेतस्थलीआंग्ल्संकेत:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'नांगरआंग्लसंकेत', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'सद्यकालमुद्रा', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'स्थानिककालमुद्रा', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'दिशाचिन्ह', 'दिशादर्शक', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#भाषा:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'मसुदाभाषा', 'मजकुरभाषा', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'नामविश्वातीलपाने:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'प्रचालकसंख्या', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'क्रमपद्धती', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'डावाभरीव', 'भरीवडावा', 'PADLEFT' ),
	'padright'                  => array( '0', 'उजवाभरीव', 'भरीवउजवा', 'PADRIGHT' ),
	'special'                   => array( '0', 'विशेष', 'special' ),
	'defaultsort'               => array( '1', 'अविचलवर्ग:', 'अविचलवर्गकळ:', 'अविचलवर्गवर्गीकरण:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'संचिकामार्ग:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'खूण', 'खूणगाठ', 'tag' ),
	'hiddencat'                 => array( '1', '__वर्गलपवा__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'वर्गातीलपाने', 'वर्गीतपाने', 'श्रेणीतपाने', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'पानक्षमता', 'PAGESIZE' ),
	'index'                     => array( '1', '__क्रमीत__', '__अनुक्रमीत__', '__INDEX__' ),
	'noindex'                   => array( '1', '__विनाक्रमीत__', '__विनाअनुक्रमीत__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'गटक्रमांक', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__अविचलपुर्ननिर्देश__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'सुरक्षास्तर', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'दिनांकनपद्धती', 'formatdate', 'dateformat' ),
	'url_wiki'                  => array( '0', 'विकि', 'WIKI' ),
	'pagesincategory_all'       => array( '0', 'सर्व', 'all' ),
	'pagesincategory_pages'     => array( '0', 'पाने', 'pages' ),
	'pagesincategory_subcats'   => array( '0', 'उपवर्ग', 'subcats' ),
	'pagesincategory_files'     => array( '0', 'संचिका', 'files' ),
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

