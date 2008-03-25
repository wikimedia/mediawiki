<?php
/** Hindi (हिन्दी)
 *
 * @addtogroup Language
 *
 * @author לערי ריינהארט
 * @author Kaustubh
 * @author Sunil Mohan
 * @author Nike
 * @author Aksi great
 * @author SPQRobin
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
# User preference toggles
'tog-underline'        => 'कड़ियाँ अधोरेखित किजीयें:',
'tog-rememberpassword' => 'इस संगणक पर मेरा कूटशब्द याद रखें',

# Dates
'sunday'    => 'रविवार',
'monday'    => 'सोमवार',
'tuesday'   => 'मंगलवार',
'wednesday' => 'बुधवार',
'thursday'  => 'गुरुवार',
'friday'    => 'शुक्रवार',
'saturday'  => 'शनिवार',
'sun'       => 'रवि',
'mon'       => 'सोम',
'tue'       => 'मंगल',
'wed'       => 'बुध',
'thu'       => 'गुरू',
'fri'       => 'शुक्र',
'sat'       => 'शनि',
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

# Categories related messages
'categories'         => '{{PLURAL:$1|श्रेणि|श्रेणियाँ}}',
'categoriespagetext' => 'निम्न श्रेणियाँ विकि मे अस्तित्वमान हैं।',
'category_header'    => '"$1" श्रेणी में लेख',
'subcategories'      => 'उपविभाग',
'category-empty'     => "''यह श्रेणी वर्तमान में कोई लेख या मीडिया(संचार-माध्यम) नही रखती''",

'about'      => 'अबाउट',
'article'    => 'लेख',
'newwindow'  => '(नया विंडो में खुलता है)',
'cancel'     => 'रद्द करें',
'mypage'     => 'मेरा पृष्ठ',
'mytalk'     => 'मेरी सदस्य वार्ता',
'anontalk'   => 'इस आई पी के लिये वार्ता',
'navigation' => 'नैविगेशन',
'and'        => 'और',

'errorpagetitle'    => 'त्रुटि',
'returnto'          => 'लौटें $1.',
'tagline'           => 'विकिपीडिया, एक मुक्त ज्ञानकोष से',
'help'              => 'सहायता',
'search'            => 'खोजें',
'searchbutton'      => 'खोज',
'go'                => 'जा',
'searcharticle'     => 'जायें',
'history'           => 'पन्ने का इतिहास',
'history_short'     => 'पुराने अवतरण',
'printableversion'  => 'प्रिन्ट करने लायक',
'permalink'         => 'स्थायी कड़ी',
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
'talkpagelinktext'  => 'वार्ता',
'specialpage'       => 'विशेष पन्ना',
'personaltools'     => 'वैयक्तिक औज़ार',
'postcomment'       => 'अपनी राय दें',
'articlepage'       => 'लेख देखें',
'talk'              => 'संवाद',
'toolbox'           => 'औज़ार-सन्दूक',
'userpage'          => 'सदस्य पृष्ठ देखें',
'projectpage'       => 'मेटा पृष्ठ देखें',
'imagepage'         => 'चित्र पृष्ठ देखें',
'viewtalkpage'      => 'चर्चा देखें',
'otherlanguages'    => 'अन्य भाषायें',
'redirectedfrom'    => '($1 से भेजा गया)',
'redirectpagesub'   => 'पुनर्निर्देश पृष्ठ',
'lastmodifiedat'    => 'अन्तिम परिवर्तन $2, $1.', # $1 date, $2 time
'viewcount'         => 'यह पृष्ठ {{PLURAL:$1|एक|$1}} बार देखा गया है',
'protectedpage'     => 'सुरक्षित पृष्ठ',
'jumptonavigation'  => 'नेविगेशन',
'jumptosearch'      => 'ख़ोज',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'      => '{{SITENAME}} के बारे में',
'aboutpage'      => 'Project:अबाउट',
'copyrightpage'  => 'विकिपीडिया:कोपिराइट',
'currentevents'  => 'हाल की घटनाएँ',
'disclaimers'    => 'अस्वीकरण',
'disclaimerpage' => 'Project:साधारण अस्वीकरण',
'edithelp'       => 'बदलाव सहायता',
'faq'            => 'अक्सर पूछे जाने वाले सवाल',
'faqpage'        => 'विकिपीडिया:अक्सर पूछे जाने वाले सवाल',
'helppage'       => 'Help:सहायता',
'mainpage'       => 'मुख्य पृष्ठ',
'portal'         => 'समाज मुखपृष्ठ',
'portal-url'     => 'विकिपीडिया:समाज मुखपृष्ठ',
'privacy'        => 'गोपनीयता नीति',
'privacypage'    => 'Project:गोपनीयता नीति',
'sitesupport'    => 'दान',

'badaccess'        => 'अनुमति त्रुटि',
'badaccess-group0' => 'जिस क्रिया का अनुरोध आपने किया है उसे संचालित करने की अनुमति आपको नही है।',

'pagetitle'               => '$1 - विकिपीडिया',
'retrievedfrom'           => '"$1" से लिया गया',
'youhavenewmessages'      => 'आपके लिए $1 है। ($2)',
'newmessageslink'         => 'नया संदेश',
'newmessagesdifflink'     => 'पिछला बदलाव',
'youhavenewmessagesmulti' => '$1 पर आपके लिए नया संदेश है',
'editsection'             => 'संपादित करें',
'editsectionhint'         => 'विभाग संपादन: $1',
'toc'                     => 'अनुक्रम',
'showtoc'                 => 'दिखा',
'hidetoc'                 => 'छुपा',
'thisisdeleted'           => '$1 देखें या बदलें?',
'restorelink'             => '{{PLURAL:$1|एक हटाया हुआ|$1 हटाये हुए}} बदलाव',
'feedlinks'               => 'फ़ीड :',
'site-rss-feed'           => '$1 आरएसएस फीड',
'site-atom-feed'          => '$1 ऍटम फीड',
'feed-atom'               => 'ऐटम',
'feed-rss'                => 'आरएसएस',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'लेख',
'nstab-user'      => 'सदस्य',
'nstab-media'     => 'मीडिया',
'nstab-special'   => 'विशेष',
'nstab-project'   => 'परियोजना पृष्ठ',
'nstab-image'     => 'चित्र',
'nstab-mediawiki' => 'संदेश',
'nstab-template'  => 'टेम्प्लेट',
'nstab-help'      => 'सहायता पृष्ठ',
'nstab-category'  => 'विभाग',

# Main script and global functions
'nosuchaction'      => 'ऐसा कोई कार्य नहीं है',
'nosuchactiontext'  => '{{SITENAME}} सौफ़्टवेयर में इस URL द्वारा निर्धारित कोई क्रिया नही है',
'nosuchspecialpage' => 'ऐसा कोई विशेष पृष्ठ नहीं है',
'nospecialpagetext' => 'आपने ऐसा विशेष पृष्ठ मांगा है जो {{SITENAME}} सौफ़्टवेयर में नहीं है.',

# General errors
'cannotdelete'     => 'इस पन्ने या चित्र को हटाया नहीं जा सका । (शायद इसे किसी और ने पहले ही हटा दिया हो )',
'badtitle'         => 'खराब शीर्षक',
'viewsource'       => 'सोर्स देखें .',
'editinginterface' => "'''सावधान:''' आप ऐसे पृष्ठ का संपादन कर रहें हैं जो सॉफ़्टवेयर के अंतरफलक का पाठ उपलब्ध कराता हैं। इस पृष्ठ के परिवर्तन अन्य उपयोगकर्ताओं के प्रयोग में आने वाले अंतरफलकों के स्वरूप को प्रभावित करेंगे।",

# Login and logout pages
'welcomecreation'            => "<h2>स्वागतम्‌, $1!</h2><p>आपका अकाउन्ट बना दिया गया है.
Don't forget to personalize your {{SITENAME}} preferences.",
'loginpagetitle'             => 'सदस्य लॉग इन',
'yourname'                   => 'आपका नाम',
'yourpassword'               => 'आपका पासवर्ड',
'yourpasswordagain'          => 'पासवर्ड दुबारा लिखें',
'remembermypassword'         => 'इस कंप्यूटर पर मेरी लॉग-इन सूचना याद रखें।',
'loginproblem'               => '<b>आपके लोगिन में समस्या हुई है ।</b><br />दुबारा प्रयत्न करें!',
'login'                      => 'लॉग इन',
'loginprompt'                => 'विकिपीडिया पर लॉग इन करने लिए आपने ब्राउज़र पर कुकी (cookies) को समर्थ करें।',
'userlogin'                  => 'सदस्य लॉग इन',
'logout'                     => 'लॉग आउट',
'userlogout'                 => 'लॉग आउट',
'nologin'                    => 'क्या आपका खाता नहीं है? $1।',
'nologinlink'                => 'नया खाता बनाएँ',
'createaccount'              => 'खाता बनाएँ',
'createaccountmail'          => 'ई-मेल द्वारा',
'badretype'                  => 'आपने जो पासवर्ड दिये हैं वे एक दूसरे से नहीं मिलते। फिर से लिखें।',
'youremail'                  => 'आपका ई-मेल पता*',
'yourrealname'               => 'आपका असली नाम*',
'yourlanguage'               => 'भाषा:',
'yournick'                   => 'आपका उपनाम (दस्तखत/सही के लिये)',
'badsiglength'               => 'अत्याधिक बड़ा उपनाम; $1 अक्षरों से कम होना चाहिये',
'loginerror'                 => 'लॉग इन ग़लती',
'loginsuccess'               => 'आप विकिपीडिया में "$1" सदस्य नाम  से लॉग इन हो चुके हैं ।',
'nosuchuser'                 => '"$1" नाम से कोई सदस्य नहीं है।
अपनी वर्तनी जाचेँ, या निम्न प्रपत्र का प्रयोग कर के नया उपयोगकर्ता खाता बनायें।',
'wrongpassword'              => 'आपने जो कूटशब्द लिखा है वह गलत है। कृपया पुनः प्रयास करें।',
'mailmypassword'             => 'ई-मेल द्वारा नया पासवर्ड भेजें',
'passwordsent'               => '"$1" का ई-मेल पता पर एक ई-मेल भेजा गया। ई-मेल पाने बाद में कृपया दुबारा लॉग इन करें।',
'acct_creation_throttle_hit' => 'माफ करें, आप ने पहले ही $1 खाते बना रखे हैं। आप और अधिक नही बना सकते।',
'accountcreated'             => 'खाता निर्मित',
'accountcreatedtext'         => '$1 के किये खाता निर्मित कर दिया गया है।',

# Edit page toolbar
'bold_sample'     => 'मोटा पाठ',
'italic_sample'   => 'झूकी मूल',
'link_sample'     => 'कड़ी शीर्षक',
'extlink_sample'  => 'http://www.example.com कड़ी शीर्षक',
'extlink_tip'     => 'बाहरी कड़ी (उपसर्ग http:// अवश्य लगाएँ)',
'headline_sample' => 'शीर्षक',
'math_sample'     => 'गणितीय फ़ॉर्म्युला यहाँ निवेश करें',
'nowiki_sample'   => 'असंरूपित पाठ यहाँ निवेश करें',
'image_sample'    => 'उदाहरण.jpg',
'media_sample'    => 'उदाहरण.ogg',

# Edit pages
'summary'               => 'सारांश',
'subject'               => 'विषय/शीर्षक',
'minoredit'             => 'यह एक छोटा बदलाव है',
'watchthis'             => 'इस पृष्ठ को ध्यानसूची में डालें',
'savearticle'           => 'बदलाव संजोयें',
'preview'               => 'झलक',
'showpreview'           => 'झलक दिखायें',
'showdiff'              => 'बदलाव दिखाएं',
'anoneditwarning'       => "'''सावधान:''' आपने लॉग इन नहीं किया। इस पृष्ठ के इतिहास में आपका आइपी पता अंकित किया जाएगा।",
'blockedtitle'          => 'सदस्य अवरुद्ध है',
'blockedoriginalsource' => "'''$1''' का स्रोत इसके नीचे दिया गया है:",
'accmailtitle'          => 'पासवर्ड भेज दिया गया है।',
'accmailtext'           => "'$1' का पासवर्ड $2 को भेज दिया गया है।",
'anontalkpagetext'      => "---- ''यह वार्ता पन्ना उस अज्ञात सदस्य के लिये है जिसने या तो अकाउन्ट नहीं बनाया है या वह उसे प्रयोग नहीं कर रहा है । इसलिये उसकी पहचान के लिये हम उसका आ ई पी ऐड्रस प्रयोग कर रहे हैं । ऐसे आ ई पी ऐड्रस कई लोगों के बीच शेयर किये जा सके हैं । अगर आप एक अज्ञात सदस्य हैं और आपको लगता है कि आपके बारे में अप्रासंगिक टीका टिप्पणी की गयी है तो कृपया  [[Special:Userlogin|अकाउन्ट बनायें या लॉग इन करें]] जिससे भविष्य में अन्य अज्ञात सदस्यों के साथ  कोई गलतफहमी न हों .''",
'previewnote'           => 'याद रखें, यह केवल एक झलक है और अभी तक सुरक्षित  नहीं किया गया है!',
'editing'               => '$1 सम्पादन',
'editingsection'        => '$1 सम्पादन (अनुभाग)',
'editingcomment'        => '$1 (टिप्पणी) सम्पादन',
'editconflict'          => 'संपादन अंतर्विरोध: $1',
'yourtext'              => 'आपका पाठ',
'yourdiff'              => 'अंतर',
'copyrightwarning'      => 'कृपया ध्यान रहे कि {{SITENAME}} को किये गये सभी योगदान $2
की शर्तों के तहत् उपलब्ध किये हुए माने जायेंगे (अधिक जानकारी के लिये $1 देखें) । अगर आप अपनी लिखाई को बदलते और पुनः वितरित होते नहीं देखना चाहते हैं तो यहां योगदान नहीं करें । <br />
आप यह भी प्रमाणित कर रहे हैं कि यह आपने स्वयं लिखा है अथवा जनार्पीत या किसी अन्य मुक्त स्रोत से कॉपी किया है । <strong>कॉपीराइट वाले लेखों को, बिना अनुमति के, यहाँ नहीं डालिये !</strong>',
'templatesused'         => 'इस पृष्ठ पर प्रयुक्त साँचे:',
'recreate-deleted-warn' => "'''चेतावनी: आप एक पहले से मिटाये गये पृष्ठ को पुनर्निर्मित कर रहे हैं।'''

आप को विचार करना चाहिये क्या इस पृष्ठ का संपादन चालू रखना उचित होगा।
इस पृष्ट को मिटाने का अभिलेख/प्रचालेख सुविधा के लिये उपलब्ध कराया गया है:",

# History pages
'nohistory'           => 'इस पन्ने का कोई इतिहास नहीं',
'revisionasof'        => '$1 का आवर्तन',
'previousrevision'    => '← पुराना संशोधन',
'nextrevision'        => 'नया संशोधन →',
'currentrevisionlink' => 'वर्तमान संशोधन',
'cur'                 => 'चालू',
'next'                => 'अगले',
'last'                => 'पिछला',

# Diffs
'difference' => '(संसकरणों के बीच अंतर)',
'lineno'     => 'लाईन $1:',
'editundo'   => 'पूर्ववत करें',

# Search results
'searchresulttext'      => 'विकिपीडिया में खोज में सहायता के लिए [[Help:सहायता|सहायता]] देखें।',
'searchsubtitleinvalid' => "आपकी खोज '''$1''' के परिणाम",
'prevn'                 => 'पिछले $1',
'nextn'                 => 'अगले $1',
'powersearch'           => 'खोज',

# Preferences page
'preferences'    => 'मेरी पसंद',
'mypreferences'  => 'मेरी वरीयताएँ',
'changepassword' => 'कूटशब्द बदलें',
'oldpassword'    => 'पुराना पासवर्ड',
'newpassword'    => 'नया कूटशब्द',
'retypenew'      => 'नया कूटशब्द पुन: लिखें',
'allowemail'     => 'अन्य उपयोगकर्ताओं से ई-मेल समर्थ करें',

# Recent changes
'recentchanges'   => 'हाल में हुए बदलाव',
'rclistfrom'      => '$1 से नये बदलाव दिखाएँ</div></div><br />',
'rcshowhideminor' => 'छोटे बदलाव $1',
'rcshowhidebots'  => 'बोटों $1',
'rcshowhideliu'   => 'लॉग्ड इन सदस्यों के बदलाव $1',
'rcshowhideanons' => 'अनामक सदस्यों के बदलाव $1',
'rcshowhidemine'  => 'मेरे बदलाव $1',
'diff'            => 'फर्क',
'hist'            => 'इतिहास',
'hide'            => 'छुपायें',
'show'            => 'दिखायें',
'minoreditletter' => 'छो',
'newpageletter'   => 'न',
'boteditletter'   => 'बो',

# Recent changes linked
'recentchangeslinked' => 'पन्ने से जुडे बदलाव',

# Upload
'upload'        => 'फ़ाइल अपलोड करें',
'uploadbtn'     => 'फ़ाइल अपलोड करें',
'uploadnologin' => 'आप लॉगड इन नहीं हैं ।',
'badfilename'   => 'फाइल का नाम "$1" कर दिया गया है।',

# Special:Imagelist
'imagelist' => 'चित्र सूची',

# Image description page
'filehist'       => 'फ़ाईलका इतिहास',
'imagelinks'     => 'कड़ियाँ',
'linkstoimage'   => 'निम्नलिखित पन्ने इस चित्र से जुडते हैं :',
'nolinkstoimage' => 'इस चित्र से कोई पन्ने नहीं जुडते',

# Random page
'randompage' => 'किसी एक लेख पर जाएं',

# Statistics
'statistics'    => 'आंकड़े',
'sitestats'     => 'विकिपीडिया आंकड़े',
'userstats'     => 'सदस्य आंकड़े',
'userstatstext' => "इस विकिपीडिया में  '''$1''' रजिस्टर्ड सदस्य हैं । 
इसमें से  '''$2''' (या '''$4%''') प्रबन्धक हैं । (देखिये  $3).",

# Miscellaneous special pages
'nbytes'       => '{{PLURAL:$1|एक बैट|$1 बैट}}',
'ncategories'  => '{{PLURAL:$1|एक श्रेणि|$1 श्रेणियाँ}}',
'nlinks'       => '$1 {{PLURAL:$1|कडि|कडियाँ}}',
'nmembers'     => '{{PLURAL:$1|एक सदस्य|$1 सदस्य}}',
'nrevisions'   => '$1 {{PLURAL:$1|रूपान्तर|रूपान्तरें}}',
'nviews'       => '{{PLURAL:$1|एक|$1}} बार देखा गया है',
'lonelypages'  => 'अकेले पन्ने',
'unusedimages' => 'अप्रयुक्त चित्र',
'wantedpages'  => 'जो पन्ने चाहिये',
'shortpages'   => 'छोटे पन्ने',
'longpages'    => 'लम्बे पन्ने',
'deadendpages' => 'डेड-एंड पन्ने',
'listusers'    => 'सदस्यसूची',
'specialpages' => 'खास पन्नें',
'spheading'    => 'सभी सदस्यों के लिये खास पन्ने',
'newpages'     => 'नये पन्ने',
'ancientpages' => 'सबसे पुराने लेख',
'move'         => 'नाम बदलें',
'movethispage' => 'पन्ने का नाम बदलें',

# Book sources
'booksources'      => 'पुस्तक के स्रोत',
'booksources-isbn' => 'आइएसबीएन:',

# Special:Log
'log'         => 'प्रचालेख सूची',
'alllogstext' => 'विकिपीडिया के सभी प्राप्य सत्रों का संयुक्त प्रदर्शन।
आप सत्र के प्रकार,उपयोगकर्ता नाम,या प्रभावित पृष्ठ का चयन कर के दृश्य को संकीर्णित कर सकते हैं।',

# Special:Allpages
'allpages'         => 'सभी पन्ने',
'alphaindexline'   => '$1 से $2',
'allarticles'      => 'सभी लेख',
'allinnamespace'   => 'सभी पन्ने ($1 नामस्थान)',
'allpagesprev'     => 'पिछला',
'allpagesnext'     => 'अगला',
'allpagessubmit'   => 'जाएँ',
'allpagesbadtitle' => 'दिया गया शीर्षक अमान्य था या उसमें अंतर-भाषित अथवा अंतर-विकी उपसर्ग था। इसमें संभवतः एक या एक से अधिक शीर्षक में प्रयोग न होने वाले अक्षर हैं।',
'allpages-bad-ns'  => 'विकिपीडिया does not have namespace "$1".',

# E-mail user
'emailuser'       => 'इस सदस्य को ई-मेल भेजें',
'defemailsubject' => 'विकिपीडिया ई-मेल',
'emailsubject'    => 'विषय',
'emailsend'       => 'भेजें',
'emailsent'       => 'ई-मेल भेज दिया गया है।',
'emailsenttext'   => 'आपका ई-मेल संदेश भेज दिया गया है ।',

# Watchlist
'watchlist'       => 'मेरी ध्यानसूची',
'mywatchlist'     => 'मेरी ध्यानसूची',
'addedwatch'      => 'ध्यानसूची में जोड़ दिया गया',
'addedwatchtext'  => 'आपकी [[Special:Watchlist|ध्यानसूची]] में "<nowiki>$1</nowiki>" का समावेश कर दिया गया है । 
भविष्य में इस पन्ने तथा इस पन्ने की वार्ता में होने  वाले बदलाव आपकी ध्यानसूची में दिखेंगे तथा [[Special:Recentchanges|हाल में हुए बदलावों की सूची]] में यह पन्ना बोल्ड दिखेगा ताकि  आप आसानी से इसका ध्यान रख सके । 

<p>अगर आपको इस पन्ने को अपनी ध्यानसूची से निकालना हो तो "ध्यान हटायें" पर क्लिक करें ।',
'watch'           => 'ध्यान रखें',
'watchthispage'   => 'इस पन्ने का ध्यान रखें',
'unwatch'         => 'ध्यान हटायें',
'unwatchthispage' => 'ध्यानसूची से हटायें',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'ध्यान दे रहे हैं...',
'unwatching' => 'ध्यान हटा रहे हैं...',

'changed' => 'परिवर्तित',

# Delete/protect/revert
'deletepage'     => 'पन्ना हटायें',
'confirm'        => 'सुनिश्चित करें',
'historywarning' => 'चेतावनी: आप जिस पन्ने को हटाने जा रहे हैं उसका इतिहास खाली नहीं है ।',
'actioncomplete' => 'कार्य पूर्ण',
'deletedarticle' => '"$1" को हटाया गया है।',
'deletecomment'  => 'हटाने का कारण',
'alreadyrolled'  => '[[$1]] का [[User:$2|$2]] ([[User talk:$2|वार्ता]]) द्वारा किया गया पिछला बदलाव रोलबेक नहीं किया जा सकता; किसी और ने पहले ही इसे रोलबेक किया अथवा बदल दिया है । पिछला बदलाव [[User:$3|$3]] ([[User talk:$3|वार्ता]]) द्वारा किया गया है ।',

# Undelete
'undelete'           => 'हटाया पन्ना वापस लायें',
'undeletedrevisions' => '{{PLURAL:$1|एक रूपान्तर वापस लाया गया|$1 रूपान्तर वापस लाये गये}} है',

# Namespace form on various pages
'namespace'      => 'नामस्थान:',
'invert'         => 'विपरीत प्रवरण',
'blanknamespace' => '(मुख्य)',

# Contributions
'contributions' => 'सदस्य योगदान',
'mycontris'     => 'मेरा योगदान',

# What links here
'whatlinkshere'       => 'यहाँ क्या जुड़ता है',
'linklistsub'         => '(कडियों की सूची)',
'whatlinkshere-links' => '← कड़ियाँ',

# Block/unblock
'blockip'           => 'अवरोधित करें',
'ipbreason'         => 'कारण',
'ipbsubmit'         => 'इस सदस्य को और बदलाव करने से रोकें',
'badipaddress'      => 'अमान्य आईपी पता।',
'blockipsuccesssub' => 'अवरोधन सफल ।(संपादन करने से रोक दिया गया है)',
'blocklistline'     => '$1, $2 ने $3 को बदलाव करने से रोक दिया है (यह रोक $4 तक मान्य है)',
'anononlyblock'     => 'केवल अनाम सदस्य',
'blocklink'         => 'अवरोधित करें',
'contribslink'      => 'योगदान',
'blocklogentry'     => '"[[$1]]" को $2 $3 तक बदलाव करने से रोक दिया गया है।',

# Move page
'movearticle'    => 'पृष्ठ का नाम बदलें',
'move-watch'     => 'ध्यान रखें',
'movepage-moved' => "<big>'''“$1” का नाम बदलकर “$2” कर दिया गया है'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'  => 'इस नाम का एक पृष्ठ पहले से ही उपस्थित है, अथवा आप ने अमान्य नाम चुना है। कृपया दूसरा नाम चुनें।',
'1movedto2'      => '$1 का नाम बदलकर $2 कर दिया गया है',

# Namespace 8 related
'allmessages'         => 'व्यवस्था संदेश',
'allmessagesname'     => 'नाम',
'allmessagescurrent'  => 'वर्तमान पाठ',
'allmessagestext'     => 'यह मीडियाविकि नेमस्पेस में उपलब्ध वयवस्था संदेशों की सूची है।',
'allmessagesmodified' => 'केवल परिवर्तित दिखायें',

# Thumbnails
'thumbnail-more' => 'बड़ा किजीये',

# Special:Import
'import'        => 'पन्ने इम्पोर्ट करें',
'importfailed'  => 'इम्पोर्ट असफल रहा  $1',
'importsuccess' => 'इम्पोर्ट सफल हुआ !',

# Tooltip help for the actions
'tooltip-pt-mytalk'       => 'मेरी सदस्य वार्ता',
'tooltip-pt-preferences'  => 'मेरी पसंद',
'tooltip-pt-login'        => 'आप लॉग इन करें, बल्कि यह अत्यावश्यक नहीं हैं.',
'tooltip-pt-logout'       => 'लॉग आउट',
'tooltip-ca-talk'         => 'कंटेंट पन्नेके बारेमें वार्तालाप',
'tooltip-ca-edit'         => 'आप यह पन्ना बदल सकते हैं. कृपया बदलाव संजोनेसे पहले झलक देखें.',
'tooltip-search'          => '{{SITENAME}} में खोजियें',
'tooltip-n-mainpage'      => 'मुखपृष्ठ पर जाए',
'tooltip-n-portal'        => 'प्रोजेक्ट के बारे में, आप क्या कर सकतें हैं, ख़ोजने की जगह',
'tooltip-n-currentevents' => 'हालकी घटनाओंके पीछेकी जानकारी',
'tooltip-n-recentchanges' => 'विकिमें हाल में हुए बदलावोंकी सूची.',
'tooltip-n-randompage'    => 'किसी एक लेख पर जाएँ',
'tooltip-n-help'          => 'ख़ोजने की जगह.',
'tooltip-n-sitesupport'   => 'हमें सहायता दें',
'tooltip-t-whatlinkshere' => 'यहां जुडे सभी विकिपन्नोंकी सूची',
'tooltip-t-upload'        => 'फ़ाइल अपलोड करें',
'tooltip-t-specialpages'  => 'सभी खास पन्नोंकी सूची',

# Attribution
'anonymous' => 'विकिपीडिया के अनाम सदस्य',
'siteuser'  => 'विकिपीडिया सदस्य  $1',
'siteusers' => 'विकिपीडिया सदस्य  $1',

# Browsing diffs
'previousdiff' => '← पिछला अंतर',
'nextdiff'     => 'अगला अंतर →',

# Special:Newimages
'ilsubmit' => 'खोज',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'समस्त',

# E-mail address confirmation
'confirmemail' => 'ई-मेल प्रमाणित करे',

# action=purge
'confirm_purge' => 'क्या आप यह पृष्ठ का कैश ख़ाली करने चाहिए?

$1',

# AJAX search
'searchcontaining' => 'उन लेखों को खोजे जिनमे $1 हो।',
'articletitles'    => "लेख जो ''$1'' से शुरु होते हैं",

# Auto-summaries
'autosumm-blank'   => 'पृष्ठ से सम्पूर्ण विषयवस्तु हटा रहा है',
'autosumm-replace' => "पृष्ठ को '$1' से बदल रहा है।",
'autosumm-new'     => 'नया पृष्ठ: $1',

# Special:Version
'version' => 'रूपान्तर', # Not used as normal message but as header for the special page itself

);
