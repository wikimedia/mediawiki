<?php
/** Marathi (मराठी)
 *
 * @addtogroup Language
 *
 * @author Angela
 * @author Hemanshu
 * @author Harshalhayat
 * @author कोलࣿहापࣿरी
 * @author Sankalpdravid
 * @author अभय नातू
 * @author शࣿरीहरि
 * @author Kaustubh
 * @author SPQRobin
 */

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'विशेष',
	NS_MAIN           => '',
	NS_TALK           => 'चर्चा',
	NS_USER           => 'सदस्य',
	NS_USER_TALK      => 'सदस्य_चर्चा',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_चर्चा',
	NS_IMAGE          => 'चित्र',
	NS_IMAGE_TALK     => 'चित्र_चर्चा',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_talk',
	NS_TEMPLATE       => 'साचा',
	NS_TEMPLATE_TALK  => 'साचा_चर्चा',
	NS_CATEGORY       => 'वर्ग',
	NS_CATEGORY_TALK  => 'वर्ग_चर्चा',
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

$messages = array(
# User preference toggles
'tog-underline'               => 'दुव्यांना अधोरेखित करा:',
'tog-highlightbroken'         => 'चुकीचे दुवे <a href="" class="new">असे दाखवा</a> (किंवा: असे दाखवा<a href="" class="internal">?</a>).',
'tog-hideminor'               => 'छोटे बदल लपवा',
'tog-extendwatchlist'         => 'पहार्‍याच्या सूचीत सर्व बदल दाखवा',
'tog-usenewrc'                => 'वाढीव अलीकडील बदल (जावास्क्रीप्ट)',
'tog-numberheadings'          => 'शीर्षके स्वयंक्रमांकित करा',
'tog-showtoolbar'             => 'संपादन चिन्हे दाखवा (जावास्क्रीप्ट)',
'tog-editondblclick'          => 'दोनवेळा क्लीक करुन पान संपादित करा (जावास्क्रीप्ट)',
'tog-editsection'             => '[संपादन] दुव्याने संपादन करणे शक्य करा',
'tog-editsectiononrightclick' => 'विभाग शीर्षकावर उजव्या क्लीकने संपादन करा(जावास्क्रीप्ट)',
'tog-showtoc'                 => '३ पेक्षा जास्त शीर्षके असताना अनुक्रमाणिका दाखवा',
'tog-rememberpassword'        => 'माझा प्रवेश या संगणकावर लक्षात ठेवा',
'tog-editwidth'               => 'संपादन खिडकी पूर्ण रुंदीची दाखवा.',
'tog-watchcreations'          => 'मी तयार केलेली पाने माझ्या पहार्‍याच्या सूचीत टाका',
'tog-watchdefault'            => 'मी संपादित केलेली पाने माझ्या पहार्‍याच्या सूचीत टाका',
'tog-watchmoves'              => 'मी स्थानांतरीत केलेली पाने माझ्या पहार्‍याच्या सूचीत टाका',
'tog-watchdeletion'           => 'मी वगळलेली पाने माझ्या पहार्‍याच्या सूचीत टाका',
'tog-minordefault'            => 'सर्व संपादने ’छोटी’ म्हणून आपोआप जतन करा',
'tog-previewontop'            => 'झलक संपादन खिडकीच्या आधी दाखवा',
'tog-previewonfirst'          => 'पहिल्या संपादनानंतर झलक दाखवा',
'tog-nocache'                 => 'पाने सयी मध्ये ठेवू नका',
'tog-enotifwatchlistpages'    => 'माझ्या पहार्‍याच्या सूचीतील पान बदलल्यास मला विरोप पाठवा',
'tog-enotifusertalkpages'     => 'माझ्या चर्चा पानावर बदल झाल्यास मला विरोप पाठवा',
'tog-enotifminoredits'        => 'मला छोट्या बदलांकरीता सुद्धा विरोप पाठवा',
'tog-enotifrevealaddr'        => 'सूचना विरोपात माझा विरोपाचा पत्ता दाखवा',
'tog-shownumberswatching'     => 'पहारा दिलेले सदस्य दाखवा',
'tog-fancysig'                => 'साधी सही (कुठल्याही दुव्याशिवाय)',
'tog-externaleditor'          => 'कायम बाह्य संपादक वापरा',
'tog-forceeditsummary'        => 'जर ’बदलांचा आढावा’ दिला नसेल तर मला सूचित करा',
'tog-watchlisthideown'        => 'पहार्‍याच्या सूचीतून माझे बदल लपवा',
'tog-watchlisthidebots'       => 'पहार्‍याच्या सूचीतून सांगकामे बदल लपवा',
'tog-watchlisthideminor'      => 'माझ्या पहार्‍याच्या सूचीतून छोटे बदल लपवा',
'tog-ccmeonemails'            => 'मी इतर सदस्यांना पाठविलेल्या इमेल च्या प्रती मलाही पाठवा',

'underline-always' => 'नेहमी',
'underline-never'  => 'कधीच नाही',

'skinpreview' => '(झलक)',

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
'sat'           => 'शनि.',
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

# Bits of text used by many pages
'categories'            => 'वर्ग',
'pagecategories'        => '{{PLURAL:$1|वर्ग|वर्ग}}',
'category_header'       => '"$1" वर्गातील लेख',
'subcategories'         => 'उपवर्ग',
'category-media-header' => '"$1" वर्गातील माध्यमे',
'category-empty'        => "''या वर्गात अद्याप एकही लेख नाही.''",

'about'          => 'च्या विषयी',
'article'        => 'लेख',
'newwindow'      => '(नवीन खिडकीत उघडते.)',
'cancel'         => 'रद्द करा',
'qbfind'         => 'शोध',
'qbbrowse'       => 'विचरण',
'qbedit'         => 'संपादन',
'qbpageoptions'  => 'पृष्ठ विकल्प',
'qbpageinfo'     => 'पृष्ठ जानकारी',
'qbmyoptions'    => 'माझे विकल्प',
'qbspecialpages' => 'विशेष पृष्ठे',
'moredotdotdot'  => 'अजून...',
'mypage'         => 'माझे पृष्ठ',
'mytalk'         => 'माझ्या चर्चा',
'anontalk'       => 'या अंकपत्त्याचे चर्चा पान उघडा',
'navigation'     => 'सुचालन',

'errorpagetitle'    => 'चुक',
'returnto'          => '$1 कडे परत चला.',
'tagline'           => '{{SITENAME}} कडून',
'help'              => 'साहाय्य',
'search'            => 'शोधा',
'searchbutton'      => 'शोधा',
'go'                => 'चला',
'searcharticle'     => 'लेख',
'history'           => 'जुन्या आवृत्ती',
'history_short'     => 'इतिहास',
'updatedmarker'     => 'शेवटच्या भेटीनंतर बदलले',
'info_short'        => 'माहिती',
'printableversion'  => 'छापन्यायोग्य आवर्तन',
'permalink'         => 'शाश्वत दुवा',
'print'             => 'छापा',
'edit'              => 'संपादन',
'editthispage'      => 'हे पृष्ठ संपादित करा',
'delete'            => 'वगळा',
'deletethispage'    => 'हे पृष्ठ काढून टाका',
'protect'           => 'सुरक्षित करा',
'protect_change'    => 'सुरक्षेचे नियम बदला',
'protectthispage'   => 'हे पृष्ठ सुरक्षित करा',
'unprotect'         => 'असुरक्षित करा',
'unprotectthispage' => 'हे पृष्ठ असुरक्षित करा',
'newpage'           => 'नवीन पृष्ठ',
'talkpage'          => 'चर्चा पृष्ठ',
'talkpagelinktext'  => 'चर्चा',
'specialpage'       => 'विशेष पृष्ठ',
'personaltools'     => 'वैयक्‍तिक साधने',
'postcomment'       => 'मत नोंदवा',
'articlepage'       => 'लेख पृष्ठ',
'talk'              => 'चर्चा',
'toolbox'           => 'साधनपेटी',
'userpage'          => 'सदस्य पृष्ठ',
'projectpage'       => 'प्रकल्प पान पाहा',
'imagepage'         => 'चित्र पृष्ठ',
'mediawikipage'     => 'संदेश पान पाहा',
'templatepage'      => 'साचा पृष्ठ पाहा.',
'viewhelppage'      => 'साहाय्य पान पाहा',
'categorypage'      => 'वर्ग पान पाहा',
'viewtalkpage'      => 'चर्चा पृष्ठ पहा',
'otherlanguages'    => 'इतर भाषा',
'redirectedfrom'    => '($1 पासून पुनर्निर्देशित)',
'redirectpagesub'   => 'पुनर्निदेशनाचे पान',
'lastmodifiedat'    => 'या पानातील शेवटचा बदल $1 रोजी $2 वाजता केला गेला.', # $1 date, $2 time
'protectedpage'     => 'सुरक्षित पृष्ठ',
'jumpto'            => 'येथे जा:',
'jumptonavigation'  => 'सुचालन',
'jumptosearch'      => 'शोधयंत्र',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} बद्दल',
'aboutpage'         => 'Project:माहितीपृष्ठ',
'bugreports'        => 'दोष अहवाल',
'bugreportspage'    => 'Project:दोष अहवाल',
'copyright'         => 'येथील मजकूर $1च्या अंतर्गत उपलब्ध आहे.',
'copyrightpagename' => '{{SITENAME}} प्रताधिकार',
'copyrightpage'     => '{{ns:project}}:प्रताधिकार',
'currentevents'     => 'सद्य घटना',
'currentevents-url' => 'प्रकल्प:सद्य घटना',
'edithelp'          => 'संपादन साहाय्य',
'edithelppage'      => 'Help:साहाय्य',
'faq'               => 'नेहमीची प्रश्नावली',
'faqpage'           => 'Project:प्रश्नावली',
'helppage'          => 'Help:साहाय्य पृष्ठ',
'mainpage'          => 'मुखपृष्ठ',
'portal'            => 'समाज मुखपृष्ठ',
'privacy'           => 'गुप्तता नीती',
'sitesupport'       => 'दान',

'badaccess'        => 'परवानगी नाकारण्यात आली आहे',
'badaccess-group0' => 'तुम्ही करत असलेल्या क्रियेचे तुम्हाला अधिकार नाहीत.',
'badaccess-group1' => 'फक्त $1 प्रकारचे सदस्य हे काम करू शकतात.',

'ok'                      => 'ठीक',
'retrievedfrom'           => '"$1" पासून मिळविले',
'youhavenewmessages'      => 'तुमच्यासाठी $1 ($2).',
'newmessageslink'         => 'नवीन संदेश',
'newmessagesdifflink'     => 'ताजा बदल',
'youhavenewmessagesmulti' => '$1 वर तुमच्यासाठी नवीन संदेश आहेत.',
'editsection'             => 'संपादन',
'editold'                 => 'संपादन',
'editsectionhint'         => 'विभाग: $1 संपादा',
'toc'                     => 'अनुक्रमणिका',
'showtoc'                 => 'दाखवा',
'hidetoc'                 => 'लपवा',
'feed-atom'               => 'ऍटम',
'feed-rss'                => 'आर.एस.ए‍स.',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'लेख',
'nstab-user'      => 'सदस्य पान',
'nstab-media'     => 'माध्यम पान',
'nstab-special'   => 'विशेष',
'nstab-project'   => 'प्रकल्प पान',
'nstab-image'     => 'संचिका',
'nstab-mediawiki' => 'संदेश',
'nstab-template'  => 'साचा',
'nstab-help'      => 'साहाय्य पान',
'nstab-category'  => 'वर्ग',

# Main script and global functions
'nosuchaction'      => 'अशी कृती अस्तित्वात नाही',
'nosuchspecialpage' => 'असे कोणतेही विशेष पृष्ठ अस्तित्वात नाही',

# General errors
'error'             => 'त्रुटी',
'databaseerror'     => 'माहितीसंग्रहातील त्रुटी',
'dberrortextcl'     => 'चुकीच्या प्रश्नलेखनामुळे माहितीसंग्रह त्रुटी.
शेवटची माहितीसंग्रहाला पाठविलेला प्रश्न होता:
"$1"
"$2" या कार्यकृतीमधून .
MySQL returned error "$3: $4".',
'laggedslavemode'   => 'सुचना: पानावर नवीन बदल नसतील.',
'filecopyerror'     => '"$1" संचिकेची "$2" ही प्रत करता आली नाही.',
'filerenameerror'   => '"$1" संचिकेचे "$2" असे नामांतर करता आले नाही.',
'filedeleteerror'   => '"$1" संचिका वगळता आली नाही.',
'filenotfound'      => '"$1" ही संचिका सापडत नाही.',
'badarticleerror'   => 'या पानावर ही कृती करता येत नाही.',
'badtitle'          => 'चुकीचे शीर्षक',
'perfcached'        => 'खालील माहिती सयीमध्ये(कॅशे) ठेवली आहे त्यामुळे ती नवीनतम नसावी.',
'perfcachedts'      => 'खालील माहिती सयीमध्ये(कॅशे) ठेवली आहे आणि शेवटी $1 ला बदलली होती.',
'viewsource'        => 'स्रोत पाहा',
'viewsourcefor'     => '$1 चा',
'protectedpagetext' => 'हे पान बदल होऊ नयेत म्हणुन सुरक्षित केले आहे.',
'viewsourcetext'    => 'तुम्ही या पानाचा स्रोत पाहू शकता व प्रत करू शकता:',

# Login and logout pages
'logouttitle'                => 'बाहेर पडा',
'loginpagetitle'             => 'सदस्य नोंदणी',
'yourname'                   => 'तुमचे नाव',
'yourpassword'               => 'तुमचा परवलीचा शब्द',
'yourpasswordagain'          => 'तुमचा परवलीचा शब्द पुन्हा लिहा',
'remembermypassword'         => 'माझा परवलीचा पुढच्या खेपेसाठी शब्द लक्षात ठेवा.',
'loginproblem'               => '<b>तुमच्या प्रवेशप्रक्रियेमध्ये चुक झाली आहे.</b><br />कृपया पुन्हा प्रयत्न करा!',
'login'                      => 'प्रवेश करा',
'userlogin'                  => 'सदस्य प्रवेश',
'logout'                     => 'बाहेर पडा',
'userlogout'                 => 'बाहेर पडा',
'notloggedin'                => 'प्रवेशाची नोंदणी झालेली नाही!',
'createaccount'              => 'नवीन खात्याची नोंदणी करा',
'gotaccount'                 => 'जुने खाते आहे? $1.',
'gotaccountlink'             => 'प्रवेश करा',
'createaccountmail'          => 'इमेल द्वारे',
'badretype'                  => 'आपला परवलीचा शब्द चुकीचा आहे.',
'userexists'                 => 'या नावाने सदस्याची नोंदणी झालेली आहे, कृपया दुसरे सदस्य नाव निवडा.',
'youremail'                  => 'आपला इमेल:',
'username'                   => 'सदस्यनाम:',
'yourrealname'               => 'तुमचे खरे नाव:',
'yourlanguage'               => 'भाषा:',
'yournick'                   => 'आपले उपनाव (सहीसाठी)',
'prefs-help-realname'        => 'तुमचे खरे नाव (वैकल्पिक): हे नाव दिल्यास आपले योगदान या नावाखाली नोंदले व दाखवले जाईल.',
'loginerror'                 => 'आपल्या प्रवेश नोंदणीमध्ये चुक झाली आहे',
'prefs-help-email'           => 'विरोप(ईमेल)(वैकल्पिक):इतरांना सदस्य किंवा सदस्य_चर्चा पानातून, तुमची ओळख देण्याची आवश्यकता न ठेवता , तुमच्याशी संपर्क सुविधा पुरवते.',
'noname'                     => 'आपण नोंदणीसाठी सदस्याचे योग्य नाव लिहिले नाही.',
'loginsuccesstitle'          => 'आपल्या प्रवेशाची नोंदणी यशस्वीरित्या पूर्ण झाली',
'loginsuccess'               => "'''तुम्ही {{SITENAME}} वर \"\$1\" नावाने प्रवेश केला आहे.'''",
'nosuchusershort'            => '"$1" या नावाचा सदस्य नाही. लिहीताना आपली चूक तर नाही ना झाली?',
'wrongpassword'              => 'आपला परवलीचा शब्द चुकीचा आहे, पुन्हा एकदा प्रयत्न करा.',
'wrongpasswordempty'         => 'परवलीचा शब्द रिकामा आहे; परत प्रयत्न करा.',
'passwordtooshort'           => 'तुमचा परवलीचा शब्द जरूरीपेक्षा लहान आहे. यात कमीत कमी $1 अक्षरे पाहिजेत.',
'mailmypassword'             => 'कृपया परवलीचा नवीन शब्द माझ्या इमेल पत्त्यावर पाठविणे.',
'noemail'                    => '"$1" सदस्यासाठी कोणताही इमेल पत्ता दिलेला नाही.',
'passwordsent'               => '"$1" सदस्याच्या इमेल पत्त्यावर परवलीचा नवीन शब्द पाठविण्यात आलेला आहे.
तो शब्द वापरुन पुन्हा प्रवेश करा.',
'eauthentsent'               => 'नामांकित ई-मेल पत्त्यावर एक निश्चितता स्वीकारक ई-मेल पाठविला गेला आहे.
खात्यावर कोणताही इतर ई-मेल पाठविण्यापूर्वी - तो ई-मेल पत्ता तुमचाच आहे, हे सूनिश्चित करण्यासाठी - तुम्हाला त्या ई-मेल मधील सूचनांचे पालन करावे लागेल.',
'acct_creation_throttle_hit' => 'माफ करा, तुम्ही आत्तापर्यंत $1 खाती उघडली आहेत. तुम्हाला आणखी खाती उघडता येणार नाहीत.',
'emailauthenticated'         => 'तुमचा इ-मेल $1 ला तपासलेला आहे.',
'noemailprefs'               => 'खालील सुविधा कार्यान्वित करण्यासाठी इ-मेल पत्ता पुरवा.',
'accountcreated'             => 'खाते उघडले.',
'accountcreatedtext'         => '$1 चे सदस्यखाते उघडले.',
'loginlanguagelabel'         => 'भाषा: $1',

# Edit page toolbar
'bold_sample'   => 'ठळक मजकूर',
'bold_tip'      => 'ठळक',
'italic_sample' => 'तिरकी अक्षरे',
'italic_tip'    => 'तिरकी अक्षरे',
'link_sample'   => 'दुव्याचे शीर्षक',
'link_tip'      => 'अंतर्गत दुवा',
'sig_tip'       => 'वेळेबरोबर तुमची सही',
'hr_tip'        => 'आडवी रेषा (कमी वापरा)',

# Edit pages
'summary'                  => 'सारांश',
'subject'                  => 'विषय',
'minoredit'                => 'हा एक छोटा बदल आहे',
'watchthis'                => 'या लेखावर लक्ष ठेवा',
'savearticle'              => 'हा लेख साठवून ठेवा',
'preview'                  => 'झलक',
'showpreview'              => 'झलक दाखवा',
'showlivepreview'          => 'थेट झलक',
'showdiff'                 => 'बदल दाखवा',
'anoneditwarning'          => "'''सावधानः''' तुम्ही विकिपीडियाचे सदस्य म्हणून प्रवेश (लॉग-इन) केलेला नाही. या पानाच्या संपादन इतिहासात तुमचा आय.पी. ऍड्रेस नोंदला जाईल.",
'summary-preview'          => 'आढाव्याची झलक',
'subject-preview'          => 'विषय/मथळा झलक',
'blockedtitle'             => 'या सदस्यासाठी प्रवेश नाकारण्यात आलेला आहे.',
'blockednoreason'          => 'कारण दिलेले नाही',
'blockedoriginalsource'    => "'''$1''' चा स्रोत खाली दिल्याप्रमाणे:",
'whitelistedittitle'       => 'संपादनासाठी सदस्य म्हणून प्रवेश आवश्यक आहे.',
'whitelistedittext'        => 'लेखांचे संपादन करण्यासाठी आधी $1 करा.',
'whitelistreadtitle'       => 'हा लेख वाचण्यासाठी [[Special:Userlogin|सदस्य म्हणून प्रवेश करावा लागेल]].',
'whitelistreadtext'        => 'हा लेख वाचण्यासाठी [[Special:Userlogin|सदस्य म्हणून प्रवेश करावा लागेल]].',
'whitelistacctitle'        => 'आपणास नवीन खात्याची नोंदणी करण्यास मनाई आहे.',
'whitelistacctext'         => 'आपणास नवीन खात्याची नोंदणी करण्यास मनाई आहे, कृपया व्यवस्थापक सूचीमधील कोणात्याही व्यवस्थापकाशी संपर्क करावा',
'nosuchsectiontitle'       => 'असा विभाग नाही.',
'loginreqtitle'            => 'प्रवेश गरजेचा आहे',
'accmailtitle'             => 'परवलीचा शब्द पाठविण्यात आलेला आहे.',
'accmailtext'              => "'$1' चा परवलीचा शब्द $2 पाठविण्यात आलेला आहे.",
'newarticle'               => '(नवीन लेख)',
'newarticletext'           => 'तुम्हाला अपेक्षित असलेला लेख अजून लिहिला गेलेला नाही. हा लेख लिहिण्यासाठी खालील पेटीत मजकूर लिहा. मदतीसाठी [[{{MediaWiki:Helppage}}|येथे]] टिचकी द्या.

जर येथे चुकून आला असाल तर ब्राउझरच्या बॅक (back) कळीवर टिचकी द्या.',
'anontalkpagetext'         => "---- ''हे बोलपान  अशा अज्ञात सदस्यासाठी आहे ज्यांनी खाते तयार केले नाही आहे
 किंवा त्याचा वापर करत नाही आहे. त्याच्या ओळखीसाठी आम्ही आंतरजाल अंकपत्ता वापरतो आहे. असा अंकपत्ता 
बऱ्याच लोकांच्यात एकच असू शकतो जर आपण अज्ञात सदस्य असाल आणि आपल्याला काही अप्रासंगिक  संदेश
 मिळाला असेल तर  कृपया [[Special:Userlogin| खाते तयार करा किंवा प्रवेश करा]] ज्यामुळे पुढे असा गैरसमज होणार नाही.''",
'noarticletext'            => 'या लेखात सध्या काहीही मजकूर नाही. तुम्ही विकिपिडीयावरील इतर लेखांमध्ये या [[Special:Search/{{PAGENAME}}|मथळ्याच्या शोध घेऊ शकता]] किंवा हा लेख [{{fullurl:{{FULLPAGENAME}}|action=edit}} लिहू शकता].',
'updated'                  => '(बदल झाला आहे.)',
'note'                     => '<strong>सूचना:</strong>',
'previewnote'              => 'लक्षात ठेवा की ही फक्त झलक आहे, बदल अजून सुरक्षित केले नाहीत.',
'previewconflict'          => 'वरील संपादन क्षेत्रातील मजकूर जतन केल्यावर या झलकेप्रमाणे दिसेल.',
'editing'                  => '$1 चे संपादन होत आहे.',
'editingsection'           => '$1 (विभाग) संपादन',
'editconflict'             => 'वादग्रस्त संपादन: $1',
'explainconflict'          => 'तुम्ही संपादनाला सुरूवात केल्यानंतर इतर कोणीतरी बदल केला आहे.
वरील पाठ्यभागामध्ये सध्या अस्तिवात असलेल्या पृष्ठातील पाठ्य आहे, तर तुमचे बदल खालील 
पाठ्यभागात दर्शविलेले आहेत. तुम्हाला हे बदल सध्या अस्तिवात असणाऱ्या पाठ्यासोबत एकत्रित करावे 
लागतील.
<b>केवळ</b> वरील पाठ्यभागामध्ये असलेले पाठ्य साठविण्यात येईल जर तुम्ही "साठवून ठेवा" ही
कळ दाबली.<br />',
'yourtext'                 => 'तुमचे पाठ्य',
'storedversion'            => 'साठविलेली आवृत्ती',
'editingold'               => '<strong>इशारा: तुम्ही मूळ पृष्ठाची एक कालबाह्य आवृत्ती संपादित करीत आहात.
जर आपण बदल साठवून ठेवण्यात आले तर या नंतरच्या सर्व आवृत्त्यांमधील साठविण्यात आलेले बदल नष्ठ होतील.</strong>',
'yourdiff'                 => 'फरक',
'copyrightwarning'         => '{{SITENAME}} येथे केलेले कोणतेही लेखन $2 (अधिक माहितीसाठी $1 पाहा) अंतर्गत मुक्त उद्घोषित केले आहे असे गृहित धरले जाईल याची कृपया नोंद घ्यावी. आपणास आपल्या लेखनाचे मुक्त संपादन आणि मुक्त वितरण होणे पसंत नसेल तर येथे संपादन करू नये.<br/>
तुम्ही येथे लेखन करताना हे सुद्धा गृहित धरलेले असते की येथे केलेले लेखन तुमचे स्वतःचे आणि केवळ स्वतःच्या प्रताधिकार (कॉपीराईट) मालकीचे आहे किंवा प्रताधिकाराने गठीत न होणार्‍या सार्वजनिक ज्ञानक्षेत्रातून घेतले आहे किंवा तत्सम मुक्त स्रोतातून घेतले आहे. तुम्ही संपादन करताना तसे वचन देत आहात. <strong>प्रताधिकारयुक्त लेखन सुयोग्य परवानगीशिवाय मुळीच चढवू/भरू नये!</strong>',
'longpagewarning'          => 'इशारा: या पृष्ठ $1 kilobytes लांबीचे आहे; काही विचरकांना
सुमारे ३२ किलोबाईट्स् आणि त्यापेक्षा जास्त लांबीच्या पृष्ठांना संपादित करण्यास अडचण येऊ शकते.
कृपया या पृष्ठाचे त्याहून छोट्या भागात रुपांतर करावे',
'protectedpagewarning'     => '<strong>सूचना:  हे सुरक्षीत पान आहे. फक्त प्रबंधक याच्यात बदल करु शकतात.</strong>',
'semiprotectedpagewarning' => "'''सूचना:''' हे पान सुरक्षीत आहे. फक्त सदस्य याच्यात बदल करू शकतात.",
'templatesused'            => 'या पानावर खालील साचे वापरण्यात आलेले आहेत:',
'templatesusedpreview'     => 'या झलकेमध्ये वापरलेले साचे:',
'templatesusedsection'     => 'या विभागात वापरलेले साचे:',
'template-protected'       => '(सुरक्षित)',
'template-semiprotected'   => '(अर्ध-सुरक्षीत)',

# Account creation failure
'cantcreateaccounttitle' => 'खाते उघडू शकत नाही',

# History pages
'viewpagelogs'        => 'या पानाच्या नोंदी पाहा',
'nohistory'           => 'या पृष्ठासाठी आवृत्ती इतिहास अस्तित्वात नाही.',
'revnotfound'         => 'आवृत्ती सापडली नाही',
'revnotfoundtext'     => 'या पृष्ठाची तुम्ही मागविलेली जुनी आवृत्ती सापडली नाही.
कृपया URL तपासून पहा.',
'loadhist'            => 'पृष्ठाचा इतिहास दाखवित आहोत',
'currentrev'          => 'चालू आवृत्ती',
'revisionasof'        => '$1 नुसारची आवृत्ती',
'previousrevision'    => '←मागील आवृत्ती',
'nextrevision'        => 'पुढील आवृत्ती→',
'currentrevisionlink' => 'आताची आवृत्ती',
'cur'                 => 'चालू',
'next'                => 'पुढील',
'last'                => 'मागील',
'orig'                => 'मूळ',
'page_first'          => 'प्रथम',
'page_last'           => 'अंतिम',
'histlegend'          => 'Legend: (चालू) = चालू आवृत्तीशी फरक,
(मागील) = पूर्वीच्या आवृत्तीशी फरक, M = छोटा बदल',
'deletedrev'          => '[वगळले]',
'histfirst'           => 'सर्वात जुने',
'histlast'            => 'सर्वात नवीन',
'historysize'         => '({{PLURAL:$1|1 बाइट|$1 बाइट}})',

# Revision feed
'history-feed-title'       => 'आवृत्ती इतिहास',
'history-feed-description' => 'विकिवरील या पानाच्या आवृत्त्यांचा इतिहास',

# Revision deletion
'revdelete-submit' => 'निवडलेल्या आवृत्त्यांना लागू करा',

# Diffs
'difference'              => '(आवर्तनांमधील फरक)',
'lineno'                  => 'ओळ $1:',
'compareselectedversions' => 'निवडलेल्या आवृत्त्यांमधील बदल पाहा',
'diff-multi'              => '({{PLURAL:$1|मधील एक आवृत्ती|मधल्या $1 आवृत्त्या}} दाखवलेल्या नाहीत.)',

# Search results
'searchresults'         => 'शोध निकाल',
'searchresulttext'      => '{{SITENAME}} वरील माहिती कशी शोधावी, याच्या माहिती करता पाहा - [[{{MediaWiki:Helppage}}|{{SITENAME}} वर शोध कसा घ्यावा]].',
'searchsubtitle'        => "तुम्ही '''[[:$1]]''' या शब्दाचा शोध घेत आहात.",
'searchsubtitleinvalid' => "तुम्ही '''$1''' या शब्दाचा शोध घेत आहात.",
'noexactmatch'          => "'''\"\$1\" या मथळ्याचा लेख अस्तित्त्वात नाही.''' तुम्ही हा लेख [[:\$1|लिहु शकता]].",
'prevn'                 => 'मागील $1',
'nextn'                 => 'पुढील $1',
'viewprevnext'          => 'पाहा ($1) ($2) ($3).',

# Preferences page
'preferences'           => 'माझ्या पसंती',
'mypreferences'         => 'माझ्या पसंती',
'prefs-edits'           => 'संपादनांची संख्या:',
'prefsnologin'          => 'प्रवेश केलेला नाही',
'prefsnologintext'      => 'सदस्य पसंती बदलण्यासाठी [[Special:Userlogin|प्रवेश]] करावा लागेल.',
'prefsreset'            => 'पसंती पूर्ववत करण्यात आल्या आहेत.',
'changepassword'        => 'परवलीचा शब्द बदला',
'skin'                  => 'त्वचा',
'math'                  => 'गणित',
'datetime'              => 'दिनांक आणि वेळ',
'prefs-personal'        => 'सदस्य व्यक्तिरेखा',
'prefs-rc'              => 'अलीकडील बदल',
'prefs-watchlist'       => 'पहार्‍याची सूची',
'prefs-watchlist-days'  => 'पहार्‍याच्या सूचीत दिसणार्‍या दिवसांची संख्या:',
'prefs-watchlist-edits' => 'वाढीव पहार्‍याच्या सूचीत दिसणार्‍या संपादनांची संख्या:',
'prefs-misc'            => 'इतर',
'saveprefs'             => 'जतन करा',
'oldpassword'           => 'जुना परवलीचा शब्दः',
'newpassword'           => 'नवीन परवलीचा शब्द:',
'retypenew'             => 'पुन्हा एकदा परवलीचा शब्द',
'textboxsize'           => 'संपादन',
'rows'                  => 'ओळी:',
'columns'               => 'स्तंभ:',
'searchresultshead'     => 'शोध',
'savedprefs'            => 'तुमच्या पसंती जतन केल्या आहेत.',
'localtime'             => 'स्थानिक वेळ',
'allowemail'            => 'इतर सदस्यांकडून इ-मेल येण्यास मुभा द्या',
'files'                 => 'संचिका',

# Groups
'group'     => 'गट:',
'group-bot' => 'सांगकामे',
'group-all' => '(सर्व)',

'group-bot-member' => 'सांगकाम्या',

# User rights log
'rightslog' => 'सदस्य आधिकार नोंद',

# Recent changes
'nchanges'          => '$1 {{PLURAL:$1|बदल|बदल}}',
'recentchanges'     => 'अलीकडील बदल',
'rcnotefrom'        => 'खाली <b>$2</b> पासूनचे (<b>$1</b> किंवा कमी) बदल दाखवले आहेत.',
'rclistfrom'        => '$1 नंतर केले गेलेले बदल दाखवा.',
'rcshowhideminor'   => 'छोटे बदल $1',
'rcshowhidebots'    => 'सांगकामे(बॉट्स) $1',
'rcshowhideliu'     => 'प्रवेश केलेले सदस्य $1',
'rcshowhideanons'   => 'अनामिक सदस्य $1',
'rcshowhidemine'    => 'माझे बदल $1',
'rclinks'           => 'मागील $2 दिवसांतील $1 बदल पाहा.<br />$3',
'diff'              => 'फरक',
'hist'              => 'इति',
'hide'              => 'लपवा',
'show'              => 'पाहा',
'minoreditletter'   => 'छो',
'newpageletter'     => 'न',
'boteditletter'     => 'सां',
'newsectionsummary' => '/* $1 */ नवीन विभाग',

# Recent changes linked
'recentchangeslinked' => 'या पृष्ठासंबंधीचे बदल',

# Upload
'upload'            => 'संचिका चढवा',
'uploadbtn'         => 'संचिका चढवा',
'reupload'          => 'पुन्हा चढवा',
'reuploaddesc'      => 'चढवायच्या पानाकडे परता',
'uploadnologintext' => 'संचिका चढविण्यासाठी तुम्हाला [[Special:Userlogin|प्रवेश]] करावा लागेल.',
'uploaderror'       => 'चढवण्यात चुक',
'uploadlog'         => 'चढवल्याची नोंद',
'uploadlogpage'     => 'चढवल्याची नोंद',
'uploadlogpagetext' => 'नवीनतम चढवलेल्या संचिकांची यादी.',
'filename'          => 'संचिकेचे नाव',
'filedesc'          => 'वर्णन',
'fileuploadsummary' => 'आढावा:',
'filesource'        => 'स्रोत',
'uploadedfiles'     => 'चढवलेल्या संचिका',
'ignorewarning'     => 'सुचनेकडे दुर्लक्ष करा आणि संचिका जतन करा.',
'ignorewarnings'    => 'सर्व सुचनांकडे दुर्लक्ष करा',
'illegalfilename'   => '"$1" या संचिकानामात शीर्षकात चालू न शकणारी अक्षरे आहेत. कृपया संचिकानाम बदलून पुन्हा चढवण्याचा प्रयत्न करा.',
'badfilename'       => 'संचिकेचे नाव बदलून "$1" असे केले आहे.',
'large-file'        => 'संचिका $1 पेक्षा कमी आकाराची असण्याची अपेक्षा आहे, ही संचिका $2 एवढी आहे.',
'largefileserver'   => 'सेवा संगणकावर (सर्वर) निर्धारित केलेल्या आकारापेक्षा या संचिकेचा आकार मोठा आहे.',
'emptyfile'         => 'चढवलेली संचिका रिकामी आहे. हे संचिकानाम चुकीचे लिहिल्याने असू शकते. कृपया तुम्हाला हीच संचिका चढवायची आहे का ते तपासा.',
'fileexists'        => 'या नावाची संचिका आधीच अस्तित्वात आहे, कृपया ही संचिका बदलण्याबद्दल तुम्ही साशंक असाल तर <strong><tt>$1</tt></strong> तपासा.',
'successfulupload'  => 'यशस्वीरीत्या चढवले',
'savefile'          => 'संचिका जतन करा',
'uploadedimage'     => '"[[$1]]" ही संचिका चढवली.',
'uploadcorrupt'     => 'ही संचिका भ्रष्ट आहे किंवा तिचे नाव व्यवस्थित नाही. कृपया संचिका तपासा आणि पुन्हा चढवा.',
'sourcefilename'    => 'स्रोत-संचिकानाम',
'destfilename'      => 'नवे संचिकानाम',
'watchthisupload'   => 'या पानावर बदलांसाठी लक्ष ठेवा.',

'license' => 'परवाना',

# Image list
'imagelist'                 => 'चित्र यादी',
'imagelisttext'             => "खाली '''$1''' संचिका {{PLURAL:$1|दिली आहे.|$2 क्रमाने दिल्या आहेत.}}",
'getimagelist'              => 'चित्र यादी खेचत आहे',
'ilsubmit'                  => 'शोधा',
'showlast'                  => '$2 क्रमबद्ध शेवटची $1 चित्रे पहा.',
'byname'                    => 'नावानुसार',
'bydate'                    => 'तारखेनुसार',
'bysize'                    => 'आकारानुसार',
'imgdelete'                 => 'पुसा',
'imgdesc'                   => 'वर्णन',
'imgfile'                   => 'संचिका',
'imagelinks'                => 'चित्र दुवे',
'linkstoimage'              => 'खालील पाने या चित्राशी जोडली आहेत:',
'nolinkstoimage'            => 'या चित्राशी जोडलेली पृष्ठे नाही आहेत.',
'shareduploadwiki'          => 'अधिक माहितीसाठी $1 पहा.',
'shareduploadwiki-linktext' => 'संचिका वर्णन पान',
'noimage'                   => 'या नावाचे चित्र अस्तित्त्वात नाही. $1 करून पहा.',
'imagelist_date'            => 'दिनांक',
'imagelist_name'            => 'नाव',
'imagelist_user'            => 'सदस्य',
'imagelist_size'            => 'आकार (बाईट्स)',
'imagelist_description'     => 'वर्णन',
'imagelist_search_for'      => 'चित्र नावाने शोध:',

# MIME search
'download' => 'उतरवा',

# Unwatched pages
'unwatchedpages' => 'लक्ष नसलेली पाने',

# List redirects
'listredirects' => 'पुनर्निर्देशने दाखवा',

# Unused templates
'unusedtemplates'     => 'न वापरलेले साचे',
'unusedtemplatestext' => 'या पानावर साचा नामविश्वातील अशी सर्व पाने आहेत जी कुठल्याही पानात वापरलेली नाहीत. वगळण्यापुर्वी साच्यांना जोडणारे इतर दुवे पाहण्यास विसरू नका.',
'unusedtemplateswlh'  => 'इतर दुवे',

# Random page
'randompage' => 'अविशिष्ट लेख',

# Random redirect
'randomredirect' => 'अविशिष्ट पुनर्निर्देशन',

# Statistics
'statistics' => 'सांख्यिकी',
'sitestats'  => 'स्थळ सांख्यिकी',
'userstats'  => 'सदस्य सांख्यिकी',

'disambiguations' => 'नि:संदिग्धकरण पृष्ठे',

'doubleredirects' => 'दुहेरी-पुनर्निर्देशने',

'brokenredirects'        => 'मोडके पुनर्निर्देशन',
'brokenredirectstext'    => 'खालील पुनर्निर्देशने अस्तित्वात नसलेली पाने जोडतात:',
'brokenredirects-edit'   => '(संपादा)',
'brokenredirects-delete' => '(वगळा)',

'withoutinterwiki'        => 'आंतरविकि दुवे नसलेली पाने',
'withoutinterwiki-header' => 'खालील लेखात इतर भाषांमधील आवृत्तीला दुवे नाहीत:',

'fewestrevisions' => 'सगळ्यात कमी बदल असलेले लेख',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|बाइट|बाइट}}',
'ncategories'             => '$1 {{PLURAL:$1|वर्ग|वर्ग}}',
'nlinks'                  => '$1 {{PLURAL:$1|दुवा|दुवे}}',
'nmembers'                => '$1 {{PLURAL:$1|सदस्य|सदस्य}}',
'lonelypages'             => 'पोरकी पाने',
'uncategorizedpages'      => 'अवर्गीकृत पाने',
'uncategorizedcategories' => 'अवर्गीकृत वर्ग',
'uncategorizedimages'     => 'अवर्गीकृत चित्रे',
'unusedcategories'        => 'न वापरलेले वर्ग',
'unusedimages'            => 'न वापरलेल्या संचिका',
'popularpages'            => 'प्रसिद्ध पाने',
'wantedcategories'        => 'पाहिजे असलेले वर्ग',
'wantedpages'             => 'पाहिजे असलेले लेख',
'mostlinked'              => 'सर्वाधिक जोडलेली पाने',
'mostlinkedcategories'    => 'सर्वाधिक जोडलेले वर्ग',
'mostcategories'          => 'सर्वाधिक वर्गीकृत पाने',
'mostimages'              => 'सर्वाधिक जोडलेली चित्रे',
'mostrevisions'           => 'सर्वाधिक बदललेले लेख',
'allpages'                => 'सर्व पृष्ठे',
'shortpages'              => 'छोटी पाने',
'longpages'               => 'मोठी पाने',
'deadendpages'            => 'टोकाची पाने',
'deadendpagestext'        => 'या पानांवर या विकिवरील इतर कुठल्याही पानाला जोडणारा दुवा नाही.',
'protectedpages'          => 'सुरक्षित पाने',
'protectedpagestext'      => 'खालील पाने स्थानांतरण किंवा संपादन यांपासुन सुरक्षित आहेत',
'listusers'               => 'सदस्यांची यादी',
'specialpages'            => 'विशेष पृष्ठे',
'spheading'               => 'सर्व सदस्यांसाठी विशेष पृष्ठे',
'newpages'                => 'नवीन पाने',
'newpages-username'       => 'सदस्य नाव:',
'ancientpages'            => 'जुने लेख',
'intl'                    => 'आंतर्भाषीय दुवे',
'move'                    => 'स्थानांतरण',
'movethispage'            => 'हे पान स्थानांतरित करा',
'unusedcategoriestext'    => 'खालील वर्ग पाने अस्तित्वात आहेत पण कोणतेही लेख किंवा वर्ग त्यांचा वापर करत नाहीत.',
'notargettitle'           => 'कर्म(target) नाही',
'notargettext'            => 'ही क्रिया करण्यासाठी तुम्ही सदस्य किंवा पृष्ठ लिहिले नाही.',

# Book sources
'booksources'               => 'पुस्तक स्रोत',
'booksources-search-legend' => 'पुस्तक स्रोत शोधा',
'booksources-go'            => 'चला',

'categoriespagetext' => 'विकिवर खालील वर्ग आहेत.',
'alphaindexline'     => '$1 पासून $2 पर्यंत',

# Special:Log
'specialloguserlabel'  => 'सदस्य:',
'speciallogtitlelabel' => 'शीर्षक:',
'log'                  => 'नोंदी',

# Special:Allpages
'nextpage'          => 'पुढील पान ($1)',
'prevpage'          => 'मागील पान ($1)',
'allpagesfrom'      => 'पुढील शब्दाने सुरू होणारे लेख दाखवा:',
'allarticles'       => 'सगळे लेख',
'allinnamespace'    => 'सर्व पाने ($1 नामविश्व)',
'allnotinnamespace' => 'सर्व पाने ($1 नामविश्वात नसलेली)',
'allpagesprev'      => 'मागील',
'allpagesnext'      => 'पुढील',
'allpagessubmit'    => 'चला',
'allpagesprefix'    => 'पुढील शब्दाने सुरू होणारी पाने दाखवा:',
'allpagesbadtitle'  => 'दिलेले शीर्षक चुकीचे किंवा आंतरभाषीय किंवा आंतरविकि शब्दाने सुरू होणारे होते. त्यात एक किंवा अधिक शीर्षकात न वापरता येणारी अक्षरे असावीत.',
'allpages-bad-ns'   => '{{SITENAME}}मध्ये "$1" हे नामविश्व नाही.',

# Special:Listusers
'listusersfrom'      => 'पुढील शब्दापासुन सुरू होणारे सदस्य दाखवा:',
'listusers-noresult' => 'एकही सदस्य सापडला नाही.',

# E-mail user
'emailuser'    => 'या सदस्याला इमेल पाठवा',
'emailfrom'    => 'कडून',
'emailto'      => 'प्रति',
'emailsubject' => 'विषय',
'emailmessage' => 'संदेश',

# Watchlist
'watchlist'            => 'माझी पहार्‍याची सूची',
'mywatchlist'          => 'माझी पहार्‍याची सूची',
'nowatchlist'          => 'तुमची पहार्‍याची सूची रिकामी आहे.',
'addedwatch'           => 'हे पान पहार्‍याच्या सूचीत घातले.',
'addedwatchtext'       => '"[[:$1]]"  हे पान तुमच्या  [[Special:Watchlist|पहार्‍याच्या सूचीत]] टाकले आहे. या पानावरील तसेच त्याच्या चर्चा पानावरील पुढील बदल येथे दाखवले जातील, आणि   [[Special:Recentchanges|अलीकडील बदलांमध्ये]] पान ठळक दिसेल.

पहार्‍याच्या सूचीतून पान काढायचे असेल तर "पहारा काढा" वर टिचकी द्या.',
'removedwatch'         => 'पहार्‍याच्या सूचीतून वगळले',
'watch'                => 'पहारा',
'watchthispage'        => 'या पानावर बदलांसाठी लक्ष ठेवा.',
'unwatch'              => 'पहारा काढा',
'unwatchthispage'      => 'पहारा काढून टाका',
'watchlistcontains'    => 'तुमचा $1 {{PLURAL:$1|पानावर|पानांवर}} पहारा आहे.',
'wlshowlast'           => 'मागील $1 तास $2 दिवस $3 पाहा',
'watchlist-show-bots'  => 'सांगकाम्यांची संपादने पहा',
'watchlist-hide-bots'  => 'सांगकाम्यांची संपादने लपवा',
'watchlist-show-own'   => 'माझी संपादने पहा',
'watchlist-hide-own'   => 'माझी संपादने लपवा',
'watchlist-show-minor' => 'छोटी संपादने पहा',
'watchlist-hide-minor' => 'छोटी संपादने लपवा',

# Displayed when you click the "watch" button and it's in the process of watching
'unwatching' => 'पहारा काढत आहे...',

'enotif_newpagetext' => 'हे नवीन पान आहे.',
'changed'            => 'बदलला',
'enotif_lastvisited' => 'तुमच्या शेवटच्या भेटीनंतरचे बदल बघणयासाठी पहा - $1.',

# Delete/protect/revert
'deletepage'         => 'पान वगळा',
'excontent'          => "मजकूर होता: '$1'",
'excontentauthor'    => "मजकूर होता: '$1' (आणि फक्त '[[Special:Contributions/$2|$2]]' यांचे योगदान होते.)",
'exblank'            => 'पान रिकामे होते',
'confirmdelete'      => 'नक्की वगळायचे',
'deletesub'          => '( "$1" वगळत आहे)',
'historywarning'     => 'सुचना: तुम्ही वगळत असलेल्या पानाला इतिहास आहे:',
'actioncomplete'     => 'काम पूर्ण',
'deletedtext'        => '"$1" हा लेख वगळला. अलीकडे वगळलेले लेख पाहण्यासाठी $2 पाहा.',
'deletedarticle'     => '"[[$1]]" लेख वगळला.',
'dellogpage'         => 'वगळल्याची नोंद',
'dellogpagetext'     => 'नुकत्याच वगळलेल्या पानांची यादी खाली आहे.',
'deletionlog'        => 'वगळल्याची नोंद',
'reverted'           => 'जुन्या आवृत्तीकडे पूर्वपदास नेले',
'deletecomment'      => 'वगळण्याचे कारण',
'editcomment'        => 'बदलासोबतची नोंद होती : "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'         => '[[Special:Contributions/$2|$2]] ([[User talk:$2|चर्चा]]) यांनी केलेले बदल [[User:$1|$1]] यांच्या आवृत्तीकडे पूर्वपदास नेले.',
'protectlogpage'     => 'सुरक्षा नोंदी',
'protectedarticle'   => '"[[$1]]" सुरक्षित केला',
'unprotectedarticle' => '"[[$1]]" असुरक्षित केला.',
'protectsub'         => '("$1" सुरक्षित करत आहे)',
'protectcomment'     => 'सुरक्षित करण्यामागचे कारण',
'unprotectsub'       => '("$1" असुरक्षित करत आहे)',
'pagesize'           => '(बाइट)',

# Undelete
'undelete'        => 'वगळलेली पाने पाहा',
'viewdeletedpage' => 'काढून टाकलेले लेख पाहा',

# Namespace form on various pages
'namespace'      => 'नामविश्व:',
'blanknamespace' => '(मुख्य)',

# Contributions
'contributions' => 'सदस्याचे योगदान',
'mycontris'     => 'माझे योगदान',
'contribsub2'   => '$1 ($2) साठी',
'nocontribs'    => 'या मानदंडाशी जुळणारे बदल सापडले नाहीत.',
'ucnote'        => 'या सदस्याचे गेल्या <b>$2</b> दिवसातील शेवटचे <b>$1</b> बदल दिले आहेत.',
'uclinks'       => 'शेवटचे $1 बदल पहा;शेवटचे $2 दिवस पहा.',
'uctop'         => ' (वर)',

'sp-contributions-newbies-sub' => 'नवशिक्यांसाठी',

'sp-newimages-showfrom' => '$1 पासुनची नवीन चित्रे दाखवा',

# What links here
'whatlinkshere'       => 'येथे काय जोडले आहे',
'whatlinkshere-title' => '$1ला जोडलेली पाने',
'linklistsub'         => '(दुव्यांची यादी)',
'linkshere'           => "खालील लेख '''[[:$1]]''' या निर्देशित पानाशी जोडले आहेत.",
'isredirect'          => 'पुनर्निर्देशित पान',

# Block/unblock
'blockip'           => 'हा अंकपत्ता आडवा',
'ipaddress'         => 'अंकपत्ता',
'ipbreason'         => 'कारण',
'ipbsubmit'         => 'हा पत्ता आडवा',
'badipaddress'      => 'अंकपत्ता बरोबर नाही.',
'blockipsuccesssub' => 'आडवणूक यशस्वी झाली',
'unblockip'         => 'अंकपत्ता सोडवा',
'unblockiptext'     => 'खाली दिलेला फॉर्म वापरून पुर्वी आडवलेल्या अंकपत्त्याला लेखनासाठी आधिकार द्या.',
'ipusubmit'         => 'हा पत्ता सोडवा',
'ipblocklist'       => 'आडवलेल्या अंकपत्त्यांची यादी',
'infiniteblock'     => 'अनंत',
'blocklink'         => 'आडवा',
'unblocklink'       => 'सोडवा',
'contribslink'      => 'योगदान',

# Move page
'movepage'                => 'पृष्ठ स्थानांतरण',
'movepagetalktext'        => "संबंधित चर्चा पृष्ठ याबरोबर स्थानांतरीत होणार नाही '''जर:'''
* तुम्ही पृष्ठ दुसऱ्या नामावकाशात  स्थानांतरीत करत असाल
* या नावाचे चर्चा अगोदरच अस्तित्वात असेल तर, किंवा 
* खालील चेकबॉक्स तुम्ही काढुन टाकला तर.

या बाबतीत तुम्हाला स्वतःला ही पाने एकत्र करावी लागतील.",
'movearticle'             => 'पृष्ठाचे स्थानांतरण',
'movenologin'             => 'प्रवेश केलेला नाही',
'movenologintext'         => 'पान स्थानांतरित करण्यासाठी तुम्हाला [[Special:Userlogin|प्रवेश]] करावा लागेल.',
'newtitle'                => 'नवीन शिर्षकाकडे',
'move-watch'              => 'या पानावर लक्ष ठेवा',
'movepagebtn'             => 'स्थानांतरण करा',
'pagemovedsub'            => 'स्थानांतरण यशस्वी',
'movepage-moved'          => '<big>\'\'\'"$1" हे पान "$2" या मथळ्याखाली स्थानांतरित केले आहे.\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'त्या नावाचे पृष्ठ अगोदरच अस्तित्वात आहे, किंवा तुम्ही निवडलेले
नाव योग्य नाही आहे.
कृपया दुसरे नाव शोधा.',
'talkexists'              => 'पृष्ठ यशस्वीरीत्या स्थानांतरीत झाले पण चर्चा पृष्ठ स्थानांतरीत होवू
शकले नाही कारण त्या नावाचे पृष्ठ आधीच अस्तित्वात होते. कृपया तुम्ही स्वतः ती पृष्ठे एकत्र करा.',
'movedto'                 => 'कडे स्थानांतरण केले',
'movetalk'                => 'शक्य असल्यास "चर्चा पृष्ठ" स्थानांतरीत करा',
'talkpagemoved'           => 'संबंधित चर्चा पृष्ठही स्थानांतरीत केले.',
'talkpagenotmoved'        => 'संबंधित चर्चा पृष्ठ स्थानांतरीत केले <strong>नाही</strong>',
'1movedto2'               => '"[[$1]]" हे पान "[[$2]]" मथळ्याखाली स्थानांतरित केले.',
'1movedto2_redir'         => '[[$1]] हे पान [[$2]] मथळ्याखाली स्थानांतरित केले (पुनर्निर्देशन).',
'movelogpage'             => 'स्थांनांतराची नोंद',
'movelogpagetext'         => 'स्थानांतरित केलेल्या पानांची यादी.',
'movereason'              => 'कारण',
'revertmove'              => 'पूर्वपदास न्या',
'delete_and_move_confirm' => 'होय, पान वगळा',
'delete_and_move_reason'  => 'आधीचे पान वगळून स्थानांतर केले',

# Export
'export'        => 'पाने निर्यात करा',
'export-submit' => 'निर्यात करा',

# Namespace 8 related
'allmessages'         => 'सर्व प्रणाली-संदेश',
'allmessagesname'     => 'नाव',
'allmessagesdefault'  => 'सुरुवातीचा मजकूर',
'allmessagescurrent'  => 'सध्याचा मजकूर',
'allmessagestext'     => 'MediaWiki नामविश्वातील सर्व प्रणाली संदेशांची यादी',
'allmessagesfilter'   => 'संदेशनावांची चाळणी:',
'allmessagesmodified' => 'फक्त बदललेले दाखवा',

# Thumbnails
'missingimage' => '<b>चित्र सापडत नाही</b><br /><i>$1</i>',
'filemissing'  => 'संचिका अस्तित्वात नाही',

# Tooltip help for the actions
'tooltip-pt-userpage'           => 'माझे सदस्य पान',
'tooltip-pt-mytalk'             => 'माझे चर्चा पान',
'tooltip-pt-preferences'        => 'माझ्या पसंती',
'tooltip-pt-mycontris'          => 'माझ्या योगदानांची यादी',
'tooltip-pt-logout'             => 'बाहेर पडा',
'tooltip-ca-edit'               => 'तुम्ही हे पान बद्लू शकता. कृपया जतन करण्यापुर्वी झलक कळ वापरून पाहा.',
'tooltip-ca-addsection'         => 'या चर्चेमध्ये मत नोंदवा.',
'tooltip-ca-viewsource'         => 'हे पान सुरक्षित आहे. तुम्ही याचा स्रोत पाहू शकता.',
'tooltip-ca-history'            => 'या पानाच्या जुन्या आवृत्या.',
'tooltip-ca-protect'            => 'हे पान सुरक्षित करा',
'tooltip-ca-delete'             => 'हे पान वगळा',
'tooltip-ca-move'               => 'हे पान स्थानांतरित करा.',
'tooltip-ca-watch'              => 'हे पान तुमच्या पहार्‍याची सूचीत टाका',
'tooltip-search'                => '{{SITENAME}} शोधा',
'tooltip-p-logo'                => 'मुखपृष्ठ',
'tooltip-n-mainpage'            => 'मुखपृष्ठाला भेट द्या',
'tooltip-n-portal'              => 'प्रकल्पाबद्दल, तुम्ही काय करू शकता, कुठे काय सापडेल',
'tooltip-n-currentevents'       => 'सद्य घटनांबद्दलची माहिती',
'tooltip-n-recentchanges'       => 'विकिवरील अलीकडील बदलांची यादी',
'tooltip-n-randompage'          => 'कोणतेही पान पाहा',
'tooltip-n-help'                => 'मदत मिळवण्याचे ठिकाण',
'tooltip-n-sitesupport'         => 'आम्हाला मदत करा',
'tooltip-t-whatlinkshere'       => 'येथे जोडलेल्या सर्व विकिपानांची यादी',
'tooltip-t-recentchangeslinked' => 'येथुन जोडलेल्या सर्व पानांवरील अलीकडील बदल',
'tooltip-t-contributions'       => 'या सदस्याच्या योगदानांची यादी पाहा',
'tooltip-t-upload'              => 'चित्रे किंवा माध्यम संचिका चढवा',
'tooltip-t-specialpages'        => 'सर्व विशेष पृष्ठांची यादी',
'tooltip-ca-nstab-user'         => 'सदस्य पान पाहा',
'tooltip-ca-nstab-media'        => 'माध्यम पान पाहा',
'tooltip-ca-nstab-special'      => 'हे विशेष पान आहे; तुम्ही ते बदलू शकत नाही.',
'tooltip-ca-nstab-project'      => 'प्रकल्प पान पाहा',
'tooltip-ca-nstab-image'        => 'चित्र पान पाहा',
'tooltip-ca-nstab-template'     => 'साचा पाहा',
'tooltip-ca-nstab-help'         => 'साहाय्य पान पाहा',
'tooltip-ca-nstab-category'     => 'वर्ग पान पाहा',
'tooltip-minoredit'             => 'बदल छोटा असल्याची नोंद करा',
'tooltip-save'                  => 'तुम्ही केलेले बदल जतन करा',
'tooltip-preview'               => 'तुम्ही केलेल्या बदलांची झलक पाहा, जतन करण्यापुर्वी कृपया हे वापरा!',

# Attribution
'anonymous'        => '{{SITENAME}} वरील अनामिक सदस्य',
'siteuser'         => '<!--{{SITENAME}}-->मराठी विकिपीडियाचा सदस्य $1',
'lastmodifiedatby' => 'या पानातील शेवटचा बदल $3ने $2, $1 यावेळी केला.', # $1 date, $2 time, $3 user
'and'              => 'आणि',
'othercontribs'    => '$1 ने केलेल्या कामानुसार.',
'others'           => 'इतर',
'siteusers'        => '{{SITENAME}} सदस्य $1',

# Spam protection
'subcategorycount'       => 'या वर्गात {{PLURAL:$1|एक उपवर्ग आहे.|$1 उपवर्ग आहेत.}}',
'categoryarticlecount'   => 'या वर्गात {{PLURAL:$1|एक लेख आहे.|$1 लेख आहेत.}}',
'category-media-count'   => 'या वर्गात {{PLURAL:$1|एक संचिका आहे.|$1 संचिका आहेत.}}',
'listingcontinuesabbrev' => 'पुढे.',

# Info page
'infosubtitle' => 'पानाची माहिती',

# Math options
'mw_math_png'    => 'नेहमीच पीएनजी (PNG) रेखाटा',
'mw_math_simple' => 'सुलभ असल्यास एचटीएमएल (HTML); अन्यथा पीएनजी (PNG)',
'mw_math_html'   => 'शक्य असल्यास एचटीएमएल (HTML); अन्यथा पीएनजी (PNG)',
'mw_math_mathml' => 'शक्य असल्यास मॅथ एमएल (MathML) (प्रयोगावस्था)',

# Image deletion
'deletedrevision' => 'जुनी आवृत्ती ($1) वगळली.',

# Browsing diffs
'previousdiff' => '← मागील फरक',
'nextdiff'     => 'पुढील फरक →',

# Special:Newimages
'showhidebots' => '(सांगकामे $1)',
'noimages'     => 'बघण्यासारखे येथे काही नाही.',

# External editor support
'edit-externally'      => 'बाहेरील संगणक प्रणाली वापरून ही संचिका संपादा.',
'edit-externally-help' => 'अधिक माहितीसाठी [http://meta.wikimedia.org/wiki/Help:External_editors स्थापन करण्याच्या सुचना] पाहा.',

# 'all' in various places, this might be different for inflected languages
'imagelistall'  => 'सर्व',
'watchlistall2' => 'सर्व',
'namespacesall' => 'सर्व',

# E-mail address confirmation
'confirmemail' => 'इमेल पत्ता पडताळून पहा',

# Delete conflict
'deletedwhileediting' => 'सुचना: तुम्ही संपादन सुरू केल्यानंतर हे पान वगळले गेले आहे.',

# AJAX search
'searchcontaining' => "''$1'' शब्द असलेले लेख शोधा.",
'searchnamed'      => "''$1'' या नावाचे लेख शोधा.",
'articletitles'    => "''$1'' पासून सुरू होणारे लेख",
'hideresults'      => 'निकाल लपवा',

# Multipage image navigation
'imgmultipageprev' => '← मागील पान',
'imgmultipagenext' => 'पुढील पान →',

# Table pager
'ascending_abbrev'         => 'चढ',
'descending_abbrev'        => 'उतर',
'table_pager_next'         => 'पुढील पान',
'table_pager_prev'         => 'मागील पान',
'table_pager_first'        => 'पहिले पान',
'table_pager_last'         => 'शेवटचे पान',
'table_pager_limit'        => 'एका पानावर $1 नग दाखवा',
'table_pager_limit_submit' => 'चला',
'table_pager_empty'        => 'निकाल नाहीत',

# Auto-summaries
'autosumm-blank'   => 'या पानावरील सगळा मजकूर काढला',
'autosumm-replace' => "पान '$1' वापरून बदलले.",
'autoredircomment' => '[[$1]] कडे पुनर्निर्देशित',
'autosumm-new'     => 'नवीन पान: $1',

# Friendlier slave lag warnings
'lag-warn-normal' => '$1 सेकंदाच्या आतले बदल या यादी नसण्याची शक्यता आहे.',

);
