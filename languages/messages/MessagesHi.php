<?php
/** Hindi (हिन्दी)
 *
 * @addtogroup Language
 *
 * @author לערי ריינהארט
 * @author Sunil Mohan
 * @author Nike
 * @author Aksi great
 */

$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'विशेष',
	NS_MAIN           => '',
	NS_TALK           => 'वार्ता',
	NS_USER           => 'सदस्य',
	NS_USER_TALK      => 'सदस्य_वार्ता',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_वार्ता',
	NS_IMAGE          => 'चित्र',
	NS_IMAGE_TALK     => 'चित्र_वार्ता',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_talk',
	NS_TEMPLATE       => 'साँचा',
	NS_TEMPLATE_TALK  => 'साँचा_वार्ता',
	NS_CATEGORY       => 'श्रेणी',
	NS_CATEGORY_TALK  => 'श्रेणी_वार्ता',
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
$linkTrail = "/^([a-z]+)(.*)\$/sD";

$messages = array(
# Dates
'sunday'    => 'रविवार',
'monday'    => 'सोमवार',
'tuesday'   => 'मंगलवार',
'wednesday' => 'बुधवार',
'thursday'  => 'गुरुवार',
'friday'    => 'शुक्रवार',
'saturday'  => 'शनिवार',
'january'   => 'जनवरी',
'february'  => 'फरवरी',
'march'     => 'मार्च',
'april'     => 'अप्रैल',
'may_long'  => 'मई',
'june'      => 'जून',
'july'      => 'जुलाई',
'august'    => 'अगस्त',
'september' => 'सितम्बर',
'october'   => 'अक्टूबर',
'november'  => 'नवम्बर',
'december'  => 'दिसम्बर',
'jan'       => 'जनवरी',
'feb'       => 'फरवरी',
'mar'       => 'मार्च',
'apr'       => 'अप्रैल',
'may'       => 'मई',
'jun'       => 'जून',
'jul'       => 'जुलाई',
'aug'       => 'अगस्त',
'sep'       => 'सितम्बर',
'oct'       => 'अक्टूबर',
'nov'       => 'नवम्बर',
'dec'       => 'दिसम्बर',

# Bits of text used by many pages
'categories' => '{{PLURAL:$1|श्रेणि|श्रेणियाँ}}',

'about'      => 'अबाउट',
'article'    => 'लेख',
'mypage'     => 'मेरा पृष्ठ',
'mytalk'     => 'मेरी सदस्य वार्ता',
'navigation' => 'नैविगेशन',

'returnto'          => 'लौटें $1.',
'help'              => 'सहायता',
'search'            => 'खोजें',
'searchbutton'      => 'खोज',
'go'                => 'जा',
'searcharticle'     => 'जायें',
'history'           => 'पन्ने का इतिहास',
'history_short'     => 'पुराने अवतरण',
'printableversion'  => 'प्रिन्ट करने लायक',
'edit'              => 'बदलें',
'editthispage'      => 'इस पृष्ठ को बदलें',
'delete'            => 'हटायें',
'deletethispage'    => 'इस पृष्ठ को हटायें',
'undelete_short'    => '{{PLURAL:$1|एक हटायागया|$1 हटायागये}} बदलाव वापस लायें',
'protect'           => 'सुरक्षित करें',
'protectthispage'   => 'इस पृष्ठ को सुरक्षित करें',
'unprotect'         => 'असुरक्षित करें',
'unprotectthispage' => 'इस पृष्ठ को असुरक्षित करें',
'newpage'           => 'नया पृष्ठ',
'talkpage'          => 'इस पृष्ठ के बारे में बात करें',
'personaltools'     => 'પોતાનાં ઓજાર',
'articlepage'       => 'लेख देखें',
'talk'              => 'संवाद',
'toolbox'           => 'औज़ार-सन्दूक',
'userpage'          => 'सदस्य पृष्ठ देखें',
'projectpage'       => 'मेटा पृष्ठ देखें',
'imagepage'         => 'चित्र पृष्ठ देखें',
'viewtalkpage'      => 'चर्चा देखें',
'otherlanguages'    => 'अन्य भाषायें',
'redirectedfrom'    => '($1 से भेजा गया)',
'lastmodifiedat'    => 'अन्तिम परिवर्तन $2, $1.', # $1 date, $2 time
'viewcount'         => 'यह पृष्ठ {{PLURAL:$1|एक|$1}} बार देखा गया है',
'protectedpage'     => 'सुरक्षित पृष्ठ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'     => '{{SITENAME}} के बारे में',
'aboutpage'     => 'Project:अबाउट',
'currentevents' => 'हाल की घटनाएँ',
'helppage'      => 'Help:सहायता',
'mainpage'      => 'मुख्य पृष्ठ',
'portal'        => 'समाज मुखपृष्ठ',
'sitesupport'   => 'दान',

'retrievedfrom' => '"$1" से लिया गया',
'showtoc'       => 'दिखा',
'hidetoc'       => 'छुपा',
'restorelink'   => '{{PLURAL:$1|एक हटाया हुआ|$1 हटाये हुए}} बदलाव',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'लेख',
'nstab-template' => 'टेम्प्लेट',
'nstab-category' => 'विभाग',

# Main script and global functions
'nosuchaction'      => 'ऐसा कोई कार्य नहीं है',
'nosuchactiontext'  => '{{SITENAME}} सौफ़्टवेयर में इस URL द्वारा निर्धारित कोई क्रिया नही है',
'nosuchspecialpage' => 'ऐसा कोई विशेष पृष्ठ नहीं है',
'nospecialpagetext' => 'आपने ऐसा विशेष पृष्ठ मांगा है जो {{SITENAME}} सौफ़्टवेयर में नहीं है.',

# General errors
'viewsource' => 'सोर्स देखें .',

# Login and logout pages
'welcomecreation'   => "<h2>स्वागतम्‌, $1!</h2><p>आपका अकाउन्ट बना दिया गया है.
Don't forget to personalize your {{SITENAME}} preferences.",
'yourname'          => 'आपका नाम',
'yourpassword'      => 'आपका पासवर्ड',
'yourpasswordagain' => 'पासवर्ड दुबारा लिखें',
'loginproblem'      => '<b>आपके लोगिन में समस्या हुई है ।</b><br />दुबारा प्रयत्न करें!',
'userlogin'         => 'सदस्य लॉग इन',
'userlogout'        => 'लॉग आउट',

# Edit pages
'savearticle'      => 'बदलाव संजोयें',
'showpreview'      => 'झलक दिखायें',
'anontalkpagetext' => "---- ''यह वार्ता पन्ना उस अज्ञात सदस्य के लिये है जिसने या तो अकाउन्ट नहीं बनाया है या वह उसे प्रयोग नहीं कर रहा है । इसलिये उसकी पहचान के लिये हम उसका आ ई पी ऐड्रस प्रयोग कर रहे हैं । ऐसे आ ई पी ऐड्रस कई लोगों के बीच शेयर किये जा सके हैं । अगर आप एक अज्ञात सदस्य हैं और आपको लगता है कि आपके बारे में अप्रासंगिक टीका टिप्पणी की गयी है तो कृपया  [[Special:Userlogin|अकाउन्ट बनायें या लॉग इन करें]] जिससे भविष्य में अन्य अज्ञात सदस्यों के साथ  कोई गलतफहमी न हों .''",
'copyrightwarning' => 'कृपया ध्यान रहे कि {{SITENAME}} को किये गये सभी योगदान $2
की शर्तों के तहत् उपलब्ध किये हुए माने जायेंगे (अधिक जानकारी के लिये $1 देखें) । अगर आप अपनी लिखाई को बदलते और पुनः वितरित होते नहीं देखना चाहते हैं तो यहां योगदान नहीं करें । <br />
आप यह भी प्रमाणित कर रहे हैं कि यह आपने स्वयं लिखा है अथवा जनार्पीत या किसी अन्य मुक्त स्रोत से कॉपी किया है । <strong>कॉपीराइट वाले लेखों को, बिना अनुमति के, यहाँ नहीं डालिये !</strong>',

# Preferences page
'preferences' => 'मेरी पसंद',

# Recent changes
'recentchanges' => 'हाल में हुए बदलाव',
'diff'          => 'फर्क',
'hist'          => 'इतिहास',
'hide'          => 'छुपायें',
'show'          => 'दिखायें',

# Recent changes linked
'recentchangeslinked' => 'पन्ने से जुडे बदलाव',

# Upload
'upload' => 'फ़ाइल अपलोड करें',

# Random page
'randompage' => 'किसी एक लेख पर जाएं',

# Statistics
'userstatstext' => "इस विकिपीडिया में  '''$1''' रजिस्टर्ड सदस्य हैं । 
इसमें से  '''$2''' (या '''$4%''') प्रबन्धक हैं । (देखिये  $3).",

# Miscellaneous special pages
'nbytes'       => '{{PLURAL:$1|एक बैट|$1 बैट}}',
'ncategories'  => '{{PLURAL:$1|एक श्रेणि|$1 श्रेणियाँ}}',
'nlinks'       => '$1 {{PLURAL:$1|कडि|कडियाँ}}',
'nmembers'     => '{{PLURAL:$1|एक सदस्य|$1 सदस्य}}',
'nrevisions'   => '$1 {{PLURAL:$1|रूपान्तर|रूपान्तरें}}',
'nviews'       => '{{PLURAL:$1|एक|$1}} बार देखा गया है',
'allpages'     => 'सभी पन्ने',
'specialpages' => 'खास पन्नें',
'move'         => 'नाम बदलें',

'version' => 'रूपान्तर',

# E-mail user
'emailuser' => 'इस सदस्य को ई-मेल भेजें',

# Watchlist
'watchlist'      => 'मेरी ध्यानसूची',
'addedwatchtext' => 'आपकी [[Special:Watchlist|ध्यानसूची]] में "$1" का समावेश कर दिया गया है । 
भविष्य में इस पन्ने तथा इस पन्ने की वार्ता में होने  वाले बदलाव आपकी ध्यानसूची में दिखेंगे तथा [[Special:Recentchanges|हाल में हुए बदलावों की सूची]] में यह पन्ना बोल्ड दिखेगा ताकि  आप आसानी से इसका ध्यान रख सके । 

<p>अगर आपको इस पन्ने को अपनी ध्यानसूची से निकालना हो तो "ध्यान हटायें" पर क्लिक करें ।',
'watch'          => 'ध्यान रखें',
'unwatch'        => 'ध्यान हटायें',

# Delete/protect/revert
'alreadyrolled' => '[[$1]] का [[User:$2|$2]] ([[User talk:$2|वार्ता]]) द्वारा किया गया पिछला बदलाव रोलबेक नहीं किया जा सकता; किसी और ने पहले ही इसे रोलबेक किया अथवा बदल दिया है । पिछला बदलाव [[User:$3|$3]] ([[User talk:$3|वार्ता]]) द्वारा किया गया है ।',

# Undelete
'undeletedrevisions' => '{{PLURAL:$1|एक रूपान्तर वापस लाया गया|$1 रूपान्तर वापस लाये गये}} है',

# Contributions
'contributions' => 'सदस्य योगदान',
'mycontris'     => 'मेरा योगदान',

# What links here
'whatlinkshere' => 'यहाँ क्या जुड़ता है',

# Spam protection
'subcategorycount'     => 'इस श्रेणीमे {{PLURAL:$1|एक|$1}} उपविभाग है ।',
'categoryarticlecount' => 'इस श्रेणी में {{PLURAL:$1|एक|$1}} लेख है ।',

);
