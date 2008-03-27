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
'tog-underline'               => 'कड़ियाँ अधोरेखित किजीयें:',
'tog-highlightbroken'         => 'टूटी हुई कड़ियाँ <a href="" class="new">इस प्रकार दर्शायें</a> (या फिर: इस प्रकार दर्शायें<a href="" class="internal">?</a>).',
'tog-justify'                 => 'परिच्छेद समान करें',
'tog-hideminor'               => 'हाल में हुएं बदलावोंसे छोटे बदलाव छुपायें',
'tog-extendwatchlist'         => 'ध्यानसूचीमें सभी बदलाव दर्शायें',
'tog-usenewrc'                => 'एनहान्सड हाल में हुए बदलाव (जावास्क्रीप्ट)',
'tog-numberheadings'          => 'शीर्षक स्वयं-क्रमांकित करें',
'tog-showtoolbar'             => 'एडिट टूलबार दर्शायें (जावास्क्रीप्ट)',
'tog-editondblclick'          => 'दुगुनी क्लीक करके पन्ना संपादित करें (जावास्क्रीप्ट)',
'tog-editsection'             => '[संपादित करें] कड़ियों द्वारा विभाग संपादन करने की अनुमती दें',
'tog-editsectiononrightclick' => 'विभाग शीर्षकपर दायाँ क्लीक करके संपादन करने की अनुमती दें (जावास्क्रीप्ट)',
'tog-showtoc'                 => 'अनुक्रम दर्शायें (जिन पन्नोंपर तीन से ज्यादा विभाग हो)',
'tog-rememberpassword'        => 'इस संगणक पर मेरा कूटशब्द याद रखें',
'tog-editwidth'               => 'एडिट बॉक्स पूरी चौड़ाई के साथ दर्शायें',

# Dates
'sunday'        => 'रविवार',
'monday'        => 'सोमवार',
'tuesday'       => 'मंगलवार',
'wednesday'     => 'बुधवार',
'thursday'      => 'गुरुवार',
'friday'        => 'शुक्रवार',
'saturday'      => 'शनिवार',
'sun'           => 'रवि',
'mon'           => 'सोम',
'tue'           => 'मंगल',
'wed'           => 'बुध',
'thu'           => 'गुरू',
'fri'           => 'शुक्र',
'sat'           => 'शनि',
'january'       => 'जनवरी',
'february'      => 'फरवरी',
'march'         => 'मार्च',
'april'         => 'अप्रैल',
'may_long'      => 'मई',
'june'          => 'जून',
'july'          => 'जुलाई',
'august'        => 'अगस्त',
'september'     => 'सितम्बर',
'october'       => 'अक्टूबर',
'november'      => 'नवम्बर',
'december'      => 'दिसम्बर',
'january-gen'   => 'जनवरी',
'february-gen'  => 'फरवरी',
'march-gen'     => 'मार्च',
'april-gen'     => 'अप्रैल',
'may-gen'       => 'मई',
'june-gen'      => 'जून',
'july-gen'      => 'जुलाई',
'august-gen'    => 'अगस्त',
'september-gen' => 'सितंबर',
'october-gen'   => 'अक्टूबर',
'november-gen'  => 'नव्हंबर',
'december-gen'  => 'दिसंबर',
'jan'           => 'जनवरी',
'feb'           => 'फरवरी',
'mar'           => 'मार्च',
'apr'           => 'अप्रैल',
'may'           => 'मई',
'jun'           => 'जून',
'jul'           => 'जुलाई',
'aug'           => 'अगस्त',
'sep'           => 'सितम्बर',
'oct'           => 'अक्टूबर',
'nov'           => 'नवम्बर',
'dec'           => 'दिसम्बर',

# Categories related messages
'categories'             => '{{PLURAL:$1|श्रेणि|श्रेणियाँ}}',
'categoriespagetext'     => 'निम्न श्रेणियाँ विकि मे अस्तित्वमान हैं।',
'category_header'        => '"$1" श्रेणी में लेख',
'subcategories'          => 'उपविभाग',
'category-media-header'  => '"$1" श्रेणी में होनेवाली मीडिया',
'category-empty'         => "''यह श्रेणी वर्तमान में कोई लेख या मीडिया(संचार-माध्यम) नही रखती''",
'listingcontinuesabbrev' => 'आगे.',

'about'      => 'अबाउट',
'article'    => 'लेख',
'newwindow'  => '(नया विंडो में खुलता है)',
'cancel'     => 'रद्द करें',
'qbfind'     => 'खोज',
'qbedit'     => 'बदलें',
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
'views'             => 'दर्शाव',
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
'jumpto'            => 'यहां जाईयें:',
'jumptonavigation'  => 'नेविगेशन',
'jumptosearch'      => 'ख़ोज',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} के बारे में',
'aboutpage'         => 'Project:अबाउट',
'bugreports'        => 'बग रिपोर्ट',
'bugreportspage'    => 'Project:बग रिपोर्ट',
'copyrightpage'     => '{{ns:project}}:कोपिराइट',
'currentevents'     => 'हाल की घटनाएँ',
'currentevents-url' => 'Project:हाल की घटनाएँ',
'disclaimers'       => 'अस्वीकरण',
'disclaimerpage'    => 'Project:साधारण अस्वीकरण',
'edithelp'          => 'बदलाव सहायता',
'edithelppage'      => 'Help:संपादन',
'faq'               => 'अक्सर पूछे जाने वाले सवाल',
'faqpage'           => 'Project:अक्सर पूछे जाने वाले सवाल',
'helppage'          => 'Help:सहायता',
'mainpage'          => 'मुख्य पृष्ठ',
'portal'            => 'समाज मुखपृष्ठ',
'portal-url'        => 'Project:समाज मुखपृष्ठ',
'privacy'           => 'गोपनीयता नीति',
'privacypage'       => 'Project:गोपनीयता नीति',
'sitesupport'       => 'दान',
'sitesupport-url'   => 'Project:साईट सहाय्य',

'badaccess'        => 'अनुमति त्रुटि',
'badaccess-group0' => 'जिस क्रिया का अनुरोध आपने किया है उसे संचालित करने की अनुमति आपको नही है।',

'pagetitle'               => '$1 - विकिपीडिया',
'retrievedfrom'           => '"$1" से लिया गया',
'youhavenewmessages'      => 'आपके लिए $1 है। ($2)',
'newmessageslink'         => 'नया संदेश',
'newmessagesdifflink'     => 'पिछला बदलाव',
'youhavenewmessagesmulti' => '$1 पर आपके लिए नया संदेश है',
'editsection'             => 'संपादित करें',
'editold'                 => 'संपादन',
'editsectionhint'         => 'विभाग संपादन: $1',
'toc'                     => 'अनुक्रम',
'showtoc'                 => 'दिखा',
'hidetoc'                 => 'छुपा',
'thisisdeleted'           => '$1 देखें या बदलें?',
'restorelink'             => '{{PLURAL:$1|एक हटाया हुआ|$1 हटाये हुए}} बदलाव',
'feedlinks'               => 'फ़ीड :',
'site-rss-feed'           => '$1 आरएसएस फीड',
'site-atom-feed'          => '$1 ऍटम फीड',
'page-rss-feed'           => '"$1" आरएसएस फ़ीड',
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
'badtitletext'     => 'आपके द्वारा पूछा गया लेख का शीर्षक अयोग्य, ख़ाली या गलतीसे जुडा हुवा आंतर-भाषिय या आंतर-विकि शीर्षक हैं । इसमें एक या एकसे ज्यादा ऐसे कॅरेक्टर है जो शीर्षकमें इस्तेमाल नहीं किये जा सकते है ।',
'viewsource'       => 'सोर्स देखें .',
'viewsourcefor'    => '$1 के लिये',
'viewsourcetext'   => 'आप इस पन्ने का स्रोत देख सकते हैं और उसकी नकल उतार सकतें हैं:',
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
'gotaccount'                 => 'पहलेसे आपका खाता हैं? $1.',
'gotaccountlink'             => 'लॉग इन',
'createaccountmail'          => 'ई-मेल द्वारा',
'badretype'                  => 'आपने जो पासवर्ड दिये हैं वे एक दूसरे से नहीं मिलते। फिर से लिखें।',
'youremail'                  => 'आपका ई-मेल पता*',
'yourrealname'               => 'आपका असली नाम*',
'yourlanguage'               => 'भाषा:',
'yournick'                   => 'आपका उपनाम (दस्तखत/सही के लिये)',
'badsiglength'               => 'अत्याधिक बड़ा उपनाम; $1 अक्षरों से कम होना चाहिये',
'prefs-help-realname'        => 'आपका असली नाम देना जरूरी नहीं है पर अगर दिया तो आपके योगदानको आपसे आरोपण करने के लिये इस्तेमाल किया जायेगा ।',
'loginerror'                 => 'लॉग इन ग़लती',
'loginsuccesstitle'          => 'लॉग इन हो गया है',
'loginsuccess'               => 'आप विकिपीडिया में "$1" सदस्य नाम  से लॉग इन हो चुके हैं ।',
'nosuchuser'                 => '"$1" नाम से कोई सदस्य नहीं है।
अपनी वर्तनी जाचेँ, या निम्न प्रपत्र का प्रयोग कर के नया उपयोगकर्ता खाता बनायें।',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" इस नाम का कोई सदस्य नहीं है ।
कृपया आपने दिया हुवा नाम जाँचियें ।',
'nouserspecified'            => 'आपको सदस्यनाम देना जरूरी है ।',
'wrongpassword'              => 'आपने जो कूटशब्द लिखा है वह गलत है। कृपया पुनः प्रयास करें।',
'wrongpasswordempty'         => 'कूटशब्द खाली है; फिरसे यत्न किजीये ।',
'passwordtooshort'           => 'आपका कूटशब्द गलत या फिर ज्यादा छोटा है ।
उसमें कम से कम $1 अक्षरे होने चाहिये और वह आपके सदस्यनामसे अलग होना चाहिये ।',
'mailmypassword'             => 'ई-मेल द्वारा नया पासवर्ड भेजें',
'passwordremindertitle'      => '{{SITENAME}} के लिया नया अस्थायी कूटशब्द',
'passwordremindertext'       => 'किसीने (शायद आपने, $1 आयपी एड्रेस से)
{{SITENAME}} पर इस्तेमाल के लिये ’नया कूटशब्द’ मंगाया है ($4) ।
"$2" सदस्यके लिये  कूटशब्द अब "$3" है ।
अभी आप लॉग इन करें और अपना कूटशब्द बदलें ।

अगर यह गुजारिश आपके अलावा और किसीने की है या फिर आपको अपना पुराना कूटशब्द याद आया हो और आप इसे बदलना नहीं चाहते हो तो, इस संदेश को नजर-अंदाज करके पुराना कूटशब्द इस्तेमाल कर सकते है ।',
'noemail'                    => '"$1" सदस्य के लिये कोई भी इ-मेल पता दर्ज नहीं किया गया हैं ।',
'passwordsent'               => '"$1" का ई-मेल पता पर एक ई-मेल भेजा गया। ई-मेल पाने बाद में कृपया दुबारा लॉग इन करें।',
'eauthentsent'               => 'दर्ज किये हुए इ-मेल पते पर एक सत्यापन इ-मेल भेजा गया है ।
कोई भी दूसरा इ-मेल भेजने से पहले, आपके सदस्यत्व का सत्यापन करने के लिये, आपको उस इ-मेल में दिये हुए सूचनाओंके अनुसार क्रियाएं करना आवश्यक है ।',
'acct_creation_throttle_hit' => 'माफ करें, आप ने पहले ही $1 खाते बना रखे हैं। आप और अधिक नही बना सकते।',
'accountcreated'             => 'खाता निर्मित',
'accountcreatedtext'         => '$1 के किये खाता निर्मित कर दिया गया है।',

# Edit page toolbar
'bold_sample'     => 'मोटा पाठ',
'bold_tip'        => 'बोल्ड पाठ्य',
'italic_sample'   => 'झूकी मूल',
'italic_tip'      => 'तिरछा पाठ्य',
'link_sample'     => 'कड़ी शीर्षक',
'link_tip'        => 'आंतर्गत कड़ि',
'extlink_sample'  => 'http://www.example.com कड़ी शीर्षक',
'extlink_tip'     => 'बाहरी कड़ी (उपसर्ग http:// अवश्य लगाएँ)',
'headline_sample' => 'शीर्षक',
'headline_tip'    => 'द्वितीय-स्तर शीर्षक',
'math_sample'     => 'गणितीय फ़ॉर्म्युला यहाँ निवेश करें',
'math_tip'        => 'मॅथेमॅटिकल फ़ार्म्यूला (LaTeX)',
'nowiki_sample'   => 'असंरूपित पाठ यहाँ निवेश करें',
'nowiki_tip'      => 'विकिभाषाके अनुसार बदलाव न करें',
'image_sample'    => 'उदाहरण.jpg',
'image_tip'       => 'एम्बडेड फ़ाईल',
'media_sample'    => 'उदाहरण.ogg',
'media_tip'       => 'फ़ाईल कड़ि',
'sig_tip'         => 'आपकी सिग्नेचर समय के साथ',
'hr_tip'          => 'हॉरिझॉंटल लाईन (कम इस्तेमाल करें)',

# Edit pages
'summary'                => 'सारांश',
'subject'                => 'विषय/शीर्षक',
'minoredit'              => 'यह एक छोटा बदलाव है',
'watchthis'              => 'इस पृष्ठ को ध्यानसूची में डालें',
'savearticle'            => 'बदलाव संजोयें',
'preview'                => 'झलक',
'showpreview'            => 'झलक दिखायें',
'showdiff'               => 'बदलाव दिखाएं',
'anoneditwarning'        => "'''सावधान:''' आपने लॉग इन नहीं किया। इस पृष्ठ के इतिहास में आपका आइपी पता अंकित किया जाएगा।",
'summary-preview'        => 'सारांशकी झलक',
'blockedtitle'           => 'सदस्य अवरुद्ध है',
'blockedtext'            => "<big>'''आपका सदस्यनाम अथवा IP एड्रेस ब्लॉक कर दिया गया हैं ।'''</big>

यह ब्लॉक $1 ने दिया है । इसके लिये ''$2'' यह कारन दिया हुआ हैं ।

* ब्लॉकका आरंभ: $8
* ब्लॉककी समाप्ती: $6
* किसे ब्लॉक करना है: $7

आप इस ब्लॉकके बारेमें वार्तालाप के लिये $1 या [[{{MediaWiki:Grouppage-sysop}}|प्रबन्धकोंसे]] संपर्क कर सकते हैं ।
आप जबतक वैध इमेल पता अपने [[Special:Preferences|सदस्य पसंद]] पन्नेपर देते नहीं तबतक आप ’सदस्यको इमेल भेजें’ यह कड़ि इस्तेमाल नहीं कर सकतें । आपको ऐसा करनेसे नहीं रोका गया है । आपका अभीका IP एड्रेस $3 यह है, और आपका ब्लॉक क्रमांक #$5 हैं । कृपया इस विषयपर होनेवाले वार्तालापमें इनमेंसे किसीकाभी प्रयोग किजीयें ।",
'blockedoriginalsource'  => "'''$1''' का स्रोत इसके नीचे दिया गया है:",
'accmailtitle'           => 'पासवर्ड भेज दिया गया है।',
'accmailtext'            => "'$1' का पासवर्ड $2 को भेज दिया गया है।",
'newarticle'             => '(नया)',
'newarticletext'         => 'आप जो लेख चाहते हैं वह अभीतक लिखा नहीं गया हैं । यह लेख लिखनेके लिये नीचे पाठ्य लिखियें । सहाय्यताके लिये [[{{MediaWiki:Helppage}}|यहां]] क्लीक किजीयें ।

अगर आप यहांपर गलतीसे आये हैं तो अपने ब्राउज़रके बॅक (back) पर क्लीक किजीयें ।',
'anontalkpagetext'       => "---- ''यह वार्ता पन्ना उस अज्ञात सदस्य के लिये है जिसने या तो अकाउन्ट नहीं बनाया है या वह उसे प्रयोग नहीं कर रहा है । इसलिये उसकी पहचान के लिये हम उसका आ ई पी ऐड्रस प्रयोग कर रहे हैं । ऐसे आ ई पी ऐड्रस कई लोगों के बीच शेयर किये जा सके हैं । अगर आप एक अज्ञात सदस्य हैं और आपको लगता है कि आपके बारे में अप्रासंगिक टीका टिप्पणी की गयी है तो कृपया  [[Special:Userlogin|अकाउन्ट बनायें या लॉग इन करें]] जिससे भविष्य में अन्य अज्ञात सदस्यों के साथ  कोई गलतफहमी न हों .''",
'noarticletext'          => 'इस लेखमें अभी कुछभी पाठ्य नहीं हैं । आप विकिपीडियापर होनेवाले दुसरे लेखोंमें इस [[Special:Search/{{PAGENAME}}|शीर्षक को खोज सकते हैं]] या फिर यह लेख [{{fullurl:{{FULLPAGENAME}}|action=edit}} तैयार कर सकते हैं] ।',
'previewnote'            => 'याद रखें, यह केवल एक झलक है और अभी तक सुरक्षित  नहीं किया गया है!',
'editing'                => '$1 सम्पादन',
'editingsection'         => '$1 सम्पादन (अनुभाग)',
'editingcomment'         => '$1 (टिप्पणी) सम्पादन',
'editconflict'           => 'संपादन अंतर्विरोध: $1',
'yourtext'               => 'आपका पाठ',
'yourdiff'               => 'अंतर',
'copyrightwarning'       => 'कृपया ध्यान रहे कि {{SITENAME}} को किये गये सभी योगदान $2
की शर्तों के तहत् उपलब्ध किये हुए माने जायेंगे (अधिक जानकारी के लिये $1 देखें) । अगर आप अपनी लिखाई को बदलते और पुनः वितरित होते नहीं देखना चाहते हैं तो यहां योगदान नहीं करें । <br />
आप यह भी प्रमाणित कर रहे हैं कि यह आपने स्वयं लिखा है अथवा जनार्पीत या किसी अन्य मुक्त स्रोत से कॉपी किया है । <strong>कॉपीराइट वाले लेखों को, बिना अनुमति के, यहाँ नहीं डालिये !</strong>',
'longpagewarning'        => '<strong>सूचना: यह पन्ना $1 किलोबाईट्सका है; कुछ ब्राउज़र्स 32kb से ज्यादा बडे पन्नोंको ठीक से नहीं दिखा सकते या संपादित करने में असुविधा हो सकती है ।
कृपया इस पन्नेके उससे कम आकारके विभाग बनाईये ।</strong>',
'templatesused'          => 'इस पृष्ठ पर प्रयुक्त साँचे:',
'templatesusedpreview'   => 'इस झलकमें इस्तेमाल किये हुए टेम्प्लेट्स:',
'template-protected'     => '(सुरक्षित)',
'template-semiprotected' => '(अर्ध-सुरक्षीत)',
'nocreatetext'           => '{{SITENAME}} पर नये लेख लिखनेके लिये मनाई की गई हैं ।
आप पीछे जाकर अस्तित्वमें होनेवाले लेखोंको संपादित कर सकते हैं, अथवा [[Special:Userlogin|नया ख़ाता खोलें / प्रवेश करें]] ।',
'recreate-deleted-warn'  => "'''चेतावनी: आप एक पहले से मिटाये गये पृष्ठ को पुनर्निर्मित कर रहे हैं।'''

आप को विचार करना चाहिये क्या इस पृष्ठ का संपादन चालू रखना उचित होगा।
इस पृष्ट को मिटाने का अभिलेख/प्रचालेख सुविधा के लिये उपलब्ध कराया गया है:",

# History pages
'viewpagelogs'        => 'इस पन्नेका लॉग देखियें',
'nohistory'           => 'इस पन्ने का कोई इतिहास नहीं',
'currentrev'          => 'सद्य अवतरण',
'revisionasof'        => '$1 का आवर्तन',
'revision-info'       => '$2ने किया हुवा $1का अवतरण',
'previousrevision'    => '← पुराना संशोधन',
'nextrevision'        => 'नया संशोधन →',
'currentrevisionlink' => 'वर्तमान संशोधन',
'cur'                 => 'चालू',
'next'                => 'अगले',
'last'                => 'पिछला',
'page_first'          => 'पहला',
'page_last'           => 'आखिरी',
'histlegend'          => 'फर्क चयन: फर्क देखनेके लिये पुराने अवतरणोंके आगे दिये गये रेडियो बॉक्सपर क्लीक करें तथा एन्टर करें अथवा नीचे दिये हुए बटनपर क्लीक करें<br />
लिजेंड: (सद्य) = सद्य अवतरणके बीचमें फर्क,
(आखिरी) = पिछले अवतरणके बीचमें फर्क, छो = छोटा बदलाव ।',
'histfirst'           => 'सबसे पुराना',
'histlast'            => 'नवीनतम',

# Revision feed
'history-feed-item-nocomment' => '$1 यहांके $2', # user at time

# Diffs
'history-title'           => '"$1" का अवतरण इतिहास',
'difference'              => '(संसकरणों के बीच अंतर)',
'lineno'                  => 'लाईन $1:',
'compareselectedversions' => 'च़यन किये हुए अवतरणोंमें फर्क देखियें',
'editundo'                => 'पूर्ववत करें',
'diff-multi'              => '({{PLURAL:$1|बीच वाला एक अवतरण|बीचवाले $1 अवतरण}} दर्शाये नहीं हैं ।)',

# Search results
'searchresulttext'      => '{{SITENAME}} में खोज में सहायता के लिए [[{{MediaWiki:Helppage}}|{{int:help}}]] देखें ।',
'searchsubtitleinvalid' => "आपकी खोज '''$1''' के परिणाम",
'noexactmatch'          => "'''\"\$1\" नामसे कोई भी लेख नहीं हैं।''' आप यह लेख [[:\$1|तैयार कर सकते हैं]]।",
'prevn'                 => 'पिछले $1',
'nextn'                 => 'अगले $1',
'viewprevnext'          => 'देख़ें ($1) ($2) ($3)',
'powersearch'           => 'खोज',

# Preferences page
'preferences'    => 'मेरी पसंद',
'mypreferences'  => 'मेरी वरीयताएँ',
'changepassword' => 'कूटशब्द बदलें',
'oldpassword'    => 'पुराना पासवर्ड',
'newpassword'    => 'नया कूटशब्द',
'retypenew'      => 'नया कूटशब्द पुन: लिखें',
'allowemail'     => 'अन्य उपयोगकर्ताओं से ई-मेल समर्थ करें',

'grouppage-sysop' => '{{ns:project}}:प्रबंधक',

# User rights log
'rightslog' => 'सदस्य अधिकार सूची',

# Recent changes
'nchanges'                       => '$1 बदलाव',
'recentchanges'                  => 'हाल में हुए बदलाव',
'recentchanges-feed-description' => 'इस फ़ीडमें होनेवाले विकिपर हाल में हुए बदलाव देखियें ।',
'rcnote'                         => "नीचे $3 तक पिछले {{PLURAL:$2|'''१''' दिनमें हुआ|'''$2''' दिनोंमें हुए}} आखिरी $1 बदलाव {{PLURAL:$1|दिया है|दिये हैं}}।",
'rcnotefrom'                     => 'नीचे <b>$2</b> से हुए (<b>$1</b> या कम) बदलाव दर्शाये गये है ।',
'rclistfrom'                     => '$1 से नये बदलाव दिखाएँ',
'rcshowhideminor'                => 'छोटे बदलाव $1',
'rcshowhidebots'                 => 'बोटों $1',
'rcshowhideliu'                  => 'लॉग्ड इन सदस्यों के बदलाव $1',
'rcshowhideanons'                => 'अनामक सदस्यों के बदलाव $1',
'rcshowhidepatr'                 => '$1 पहारा दी हुई एडिट्स',
'rcshowhidemine'                 => 'मेरे बदलाव $1',
'rclinks'                        => 'पिछले $2 दिनोंमें हुए $1 बदलाव देखियें.<br />$3',
'diff'                           => 'फर्क',
'hist'                           => 'इतिहास',
'hide'                           => 'छुपायें',
'show'                           => 'दिखायें',
'minoreditletter'                => 'छो',
'newpageletter'                  => 'न',
'boteditletter'                  => 'बो',

# Recent changes linked
'recentchangeslinked'          => 'पन्ने से जुडे बदलाव',
'recentchangeslinked-title'    => '$1 में हुए बदलाव',
'recentchangeslinked-noresult' => 'जुडे हुए पन्नोंमें दिये हुए अवसरमें कोई भी बदलाव नहीं हुए हैं ।',
'recentchangeslinked-summary'  => "यह विशेष पृष्ठ जुड़े हुए पन्नोंके बदलाव दर्शाता हैं। आपकी ध्यानसूची में रखे पन्ने '''बोल्ड''' दिखेंगे।",

# Upload
'upload'        => 'फ़ाइल अपलोड करें',
'uploadbtn'     => 'फ़ाइल अपलोड करें',
'uploadnologin' => 'आप लॉगड इन नहीं हैं ।',
'uploadlogpage' => 'अपलोड सूची',
'badfilename'   => 'फाइल का नाम "$1" कर दिया गया है।',
'uploadedimage' => '"[[$1]]" को चढाया गया हैं',

# Special:Imagelist
'imagelist' => 'चित्र सूची',

# Image description page
'filehist'                  => 'फ़ाईलका इतिहास',
'filehist-help'             => 'फ़ाईलका पुराना अवतरण देखनेके लिये दिनांक/समय पर क्लीक करें।',
'filehist-current'          => 'सद्य',
'filehist-datetime'         => 'दिनांक/समय',
'filehist-user'             => 'सदस्य',
'filehist-dimensions'       => 'डायमेन्शन्स',
'filehist-filesize'         => 'फ़ाईलका आकार (बाईट्स)',
'filehist-comment'          => 'प्रतिक्रीया',
'imagelinks'                => 'कड़ियाँ',
'linkstoimage'              => 'निम्नलिखित पन्ने इस चित्र से जुडते हैं :',
'nolinkstoimage'            => 'इस चित्र से कोई पन्ने नहीं जुडते',
'sharedupload'              => 'यह फ़ाईल दुसरेभी प्रोजेक्ट्समें इस्तेमाल की हुई होनेकी आशंका है ।',
'noimage'                   => 'इस नामसे कोई भी फ़ाईल नहीं है, आप $1 कर सकते हैं ।',
'noimage-linktext'          => 'चढाईयें',
'uploadnewversion-linktext' => 'इस फ़ाईलका नया अवतरण अपलोड करें',

# MIME search
'mimesearch' => 'MIME खोज',

# List redirects
'listredirects' => 'अनुप्रेषित सूची',

# Unused templates
'unusedtemplates' => 'इस्तेमाल न हुए टेम्प्लेट्स',

# Random page
'randompage' => 'किसी एक लेख पर जाएं',

# Random redirect
'randomredirect' => 'किसी एक पुनर्निर्देशन पर जायें',

# Statistics
'statistics'    => 'आंकड़े',
'sitestats'     => 'विकिपीडिया आंकड़े',
'userstats'     => 'सदस्य आंकड़े',
'userstatstext' => "इस विकिपीडिया में  {{PLURAL:$1|'''1''' रजिस्टर्ड [[Special:Listusers|सदस्य]]|'''$1''' रजिस्टर्ड  [[Special:Listusers|सदस्य]]}} हैं ।
इसमें से  '''$2''' (या '''$4%''') {{PLURAL:$2|सदस्यको|सदस्योंको}} $5 अधिकार हैं ।",

'disambiguations' => 'डिसऍम्बिग्वीशन पन्ने',

'doubleredirects' => 'दुगुनी-अनुप्रेषिते',

'brokenredirects' => 'टूटे हुए अनुप्रेष',

'withoutinterwiki' => 'आंतरविकि कड़ियाँ न होनेवाले लेख',

'fewestrevisions' => 'सबसे कम अवतरण होने वाले लेख',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|एक बैट|$1 बैट}}',
'ncategories'             => '{{PLURAL:$1|एक श्रेणि|$1 श्रेणियाँ}}',
'nlinks'                  => '$1 {{PLURAL:$1|कडि|कडियाँ}}',
'nmembers'                => '{{PLURAL:$1|एक सदस्य|$1 सदस्य}}',
'nrevisions'              => '$1 {{PLURAL:$1|रूपान्तर|रूपान्तरें}}',
'nviews'                  => '{{PLURAL:$1|एक|$1}} बार देखा गया है',
'lonelypages'             => 'अकेले पन्ने',
'uncategorizedpages'      => 'अश्रेणीकृत पन्ने',
'uncategorizedcategories' => 'अश्रेणीकृत श्रेणियाँ',
'uncategorizedimages'     => 'अश्रेणीकृत फ़ाईल्स',
'uncategorizedtemplates'  => 'अश्रेणीकृत टेम्प्लेट्स',
'unusedcategories'        => 'इस्तेमाल न हुई श्रेणियाँ',
'unusedimages'            => 'अप्रयुक्त चित्र',
'wantedcategories'        => 'श्रेणियाँ जो चाहिये',
'wantedpages'             => 'जो पन्ने चाहिये',
'mostlinked'              => 'सबसे ज्यादा जुडे हुए पन्ने',
'mostlinkedcategories'    => 'सबसे ज्यादा जुडी हुई श्रेणियाँ',
'mostlinkedtemplates'     => 'सबसे ज्यादा जुडी हुई टेम्प्लेट्स',
'mostcategories'          => 'सबसे ज्यादा श्रेणियाँ होनेवाले पन्ने',
'mostimages'              => 'सबसे ज्यादा जुडी हुई फ़ाईल्स',
'mostrevisions'           => 'सबसे ज्यादा अवतरण हुए लेख',
'prefixindex'             => 'उपपद सूची',
'shortpages'              => 'छोटे पन्ने',
'longpages'               => 'लम्बे पन्ने',
'deadendpages'            => 'डेड-एंड पन्ने',
'protectedpages'          => 'सुरक्षित पन्ने',
'listusers'               => 'सदस्यसूची',
'specialpages'            => 'खास पन्नें',
'spheading'               => 'सभी सदस्यों के लिये खास पन्ने',
'newpages'                => 'नये पन्ने',
'ancientpages'            => 'सबसे पुराने लेख',
'move'                    => 'नाम बदलें',
'movethispage'            => 'पन्ने का नाम बदलें',

# Book sources
'booksources'      => 'पुस्तक के स्रोत',
'booksources-isbn' => 'आइएसबीएन:',

# Special:Log
'specialloguserlabel'  => 'सदस्य:',
'speciallogtitlelabel' => 'शीर्षक:',
'log'                  => 'प्रचालेख सूची',
'all-logs-page'        => 'सभी सूचियाँ',
'alllogstext'          => 'विकिपीडिया के सभी प्राप्य सत्रों का संयुक्त प्रदर्शन।
आप सत्र के प्रकार,उपयोगकर्ता नाम,या प्रभावित पृष्ठ का चयन कर के दृश्य को संकीर्णित कर सकते हैं।',

# Special:Allpages
'allpages'         => 'सभी पन्ने',
'alphaindexline'   => '$1 से $2',
'nextpage'         => 'अगला पन्ना ($1)',
'prevpage'         => 'पिछला पन्ना ($1)',
'allpagesfrom'     => 'दिये हुए अक्षर से आरंभ होनेवाले लेख दर्शायें:',
'allarticles'      => 'सभी लेख',
'allinnamespace'   => 'सभी पन्ने ($1 नामस्थान)',
'allpagesprev'     => 'पिछला',
'allpagesnext'     => 'अगला',
'allpagessubmit'   => 'जाएँ',
'allpagesprefix'   => 'इस उपपदसे शुरू होनेवाले लेख दर्शायें:',
'allpagesbadtitle' => 'दिया गया शीर्षक अमान्य था या उसमें अंतर-भाषित अथवा अंतर-विकी उपसर्ग था। इसमें संभवतः एक या एक से अधिक शीर्षक में प्रयोग न होने वाले अक्षर हैं।',
'allpages-bad-ns'  => '{{SITENAME}} में "$1" यह नामस्थान नहीं हैं ।',

# E-mail user
'emailuser'       => 'इस सदस्य को ई-मेल भेजें',
'defemailsubject' => 'विकिपीडिया ई-मेल',
'emailsubject'    => 'विषय',
'emailsend'       => 'भेजें',
'emailsent'       => 'ई-मेल भेज दिया गया है।',
'emailsenttext'   => 'आपका ई-मेल संदेश भेज दिया गया है ।',

# Watchlist
'watchlist'            => 'मेरी ध्यानसूची',
'mywatchlist'          => 'मेरी ध्यानसूची',
'watchlistfor'         => "('''$1''' के लिये)",
'addedwatch'           => 'ध्यानसूची में जोड़ दिया गया',
'addedwatchtext'       => 'आपकी [[Special:Watchlist|ध्यानसूची]] में "<nowiki>$1</nowiki>" का समावेश कर दिया गया है ।
भविष्य में इस पन्ने तथा इस पन्ने की वार्ता में होने  वाले बदलाव आपकी ध्यानसूची में दिखेंगे तथा [[Special:Recentchanges|हाल में हुए बदलावों की सूची]] में यह पन्ना बोल्ड दिखेगा ताकि  आप आसानी से इसका ध्यान रख सके ।

<p>अगर आपको इस पन्ने को अपनी ध्यानसूची से निकालना हो तो "ध्यान हटायें" पर क्लिक करें ।',
'removedwatch'         => 'ध्यानसूची से हटाया गया है',
'removedwatchtext'     => '"[[:$1]]" लेख आपके ध्यानसूचीसे हटाया गया है ।',
'watch'                => 'ध्यान रखें',
'watchthispage'        => 'इस पन्ने का ध्यान रखें',
'unwatch'              => 'ध्यान हटायें',
'unwatchthispage'      => 'ध्यानसूची से हटायें',
'watchlist-details'    => '{{PLURAL:$1|$1 पन्ना|$1 पन्ने}} ध्यानसूचीमें हैं, इसमे वार्ता पृष्ठ शामिल नहीं हैं ।',
'wlshowlast'           => 'पिछले $1 घंटे $2 दिन $3 देखें',
'watchlist-hide-bots'  => 'बोट एडिट छुपायें',
'watchlist-hide-own'   => 'मेरे बदलाव छुपायें',
'watchlist-hide-minor' => 'छोटे बदलाव छुपायें',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'ध्यान दे रहे हैं...',
'unwatching' => 'ध्यान हटा रहे हैं...',

'changed' => 'परिवर्तित',

# Delete/protect/revert
'deletepage'                  => 'पन्ना हटायें',
'confirm'                     => 'सुनिश्चित करें',
'historywarning'              => 'चेतावनी: आप जिस पन्ने को हटाने जा रहे हैं उसका इतिहास खाली नहीं है ।',
'confirmdeletetext'           => 'आप एक लेख उसके सभी अवतरणोंके साथ हटाना चाहते हैं ।
आपसे अनुरोध है कि आप जो कर रहे है वह मीडियाविकिके [[{{MediaWiki:Policy-url}}|नीतिनुसार]] है इस बात की पुष्टि किजीये । तथा क्रिया करने से पहले आपकी क्रिया के परिणाम जान लें ।',
'actioncomplete'              => 'कार्य पूर्ण',
'deletedtext'                 => '"<nowiki>$1</nowiki>" को हटाया गया है ।
हाल में हटाये गये लेखोंकी सूची के लिये $2 देखें ।',
'deletedarticle'              => '"$1" को हटाया गया है।',
'dellogpage'                  => 'हटाने की सूची',
'deletecomment'               => 'हटाने का कारण',
'deleteotherreason'           => 'दुसरा/अतिरिक्त कारण:',
'deletereasonotherlist'       => 'दुसरा कारण',
'rollbacklink'                => 'रोलबॅक',
'alreadyrolled'               => '[[$1]] का [[User:$2|$2]] ([[User talk:$2|वार्ता]]) द्वारा किया गया पिछला बदलाव रोलबेक नहीं किया जा सकता; किसी और ने पहले ही इसे रोलबेक किया अथवा बदल दिया है । पिछला बदलाव [[User:$3|$3]] ([[User talk:$3|वार्ता]]) द्वारा किया गया है ।',
'protectlogpage'              => 'सुरक्षा सूची',
'protectcomment'              => 'टिप्पणी:',
'protectexpiry'               => 'समाप्ती:',
'protect_expiry_invalid'      => 'समाप्ती समय गलत है ।',
'protect_expiry_old'          => 'समाप्ती समय बीत चुका है ।',
'protect-unchain'             => 'नाम बदलने की अनुमति दिजीये',
'protect-text'                => '<strong><nowiki>$1</nowiki></strong> पन्ने का सुरक्षा-स्तर आप यहां देख सकते है और उसे बदल भी सकते है ।',
'protect-locked-access'       => 'आपको इस पन्ने का सुरक्षा-स्तर बदलने कि अनुमति नहीं है ।
<strong>$1</strong> का अभीका सुरक्षा-स्तर:',
'protect-cascadeon'           => 'यह पन्ना अभी सुरक्षित है क्योंकि वह {{PLURAL:$1|इस पन्नेकी|इन पन्नोंकी}} सुरक्षा-सीढीपर है । आप इस पन्ने का सुरक्षा-स्तर बदल सकते हैं, पर उससे सुरक्षा-सीढीमें बदलाव नहीं होंगे ।',
'protect-default'             => '(ड़िफॉल्ट)',
'protect-fallback'            => '"$1" इजाज़त जरूरी है',
'protect-level-autoconfirmed' => 'अनामक सदस्योंको ब्लॉक करें',
'protect-level-sysop'         => 'सिर्फ प्रबंधक',
'protect-summary-cascade'     => 'सीढी',
'protect-expiring'            => 'समाप्ती $1 (UTC)',
'protect-cascade'             => 'इस पन्ने से जुडे हुए पन्ने सुरक्षित करें (सुरक्षा-सीढी)',
'protect-cantedit'            => 'आप इस पन्नेका सुरक्षा-स्तर बदल नहीं सकते क्योंकी आपको ऐसा करने की अनुमति नहीं है ।',
'restriction-type'            => 'इजाज़त:',
'restriction-level'           => 'सुरक्षा-स्तर:',

# Undelete
'undelete'           => 'हटाया पन्ना वापस लायें',
'undeletebtn'        => 'वापस ले आयें',
'undeletedrevisions' => '{{PLURAL:$1|एक रूपान्तर वापस लाया गया|$1 रूपान्तर वापस लाये गये}} है',

# Namespace form on various pages
'namespace'      => 'नामस्थान:',
'invert'         => 'विपरीत प्रवरण',
'blanknamespace' => '(मुख्य)',

# Contributions
'contributions' => 'सदस्य योगदान',
'mycontris'     => 'मेरा योगदान',
'contribsub2'   => '$1 के लिये ($2)',
'uctop'         => '(उपर)',
'month'         => 'इस महिनेसे (और पुरानें):',
'year'          => 'इस सालसे (और पुराने):',

'sp-contributions-newbies-sub' => 'नये सदस्योंके लिये',
'sp-contributions-blocklog'    => 'ब्लॉक सूची',

# What links here
'whatlinkshere'       => 'यहाँ क्या जुड़ता है',
'whatlinkshere-title' => '$1को जुडे हुए पन्ने',
'linklistsub'         => '(कडियों की सूची)',
'linkshere'           => "नीचे दिये हुए पन्ने '''[[:$1]]''' से जुडते हैं:",
'nolinkshere'         => "'''[[:$1]]''' को कुछभी जुडता नहीं हैं ।",
'isredirect'          => 'पुनर्निर्देशन पन्ना',
'istemplate'          => 'मिलाईयें',
'whatlinkshere-prev'  => '{{PLURAL:$1|पिछला|पिछले $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|अगला|अगले $1}}',
'whatlinkshere-links' => '← कड़ियाँ',

# Block/unblock
'blockip'           => 'अवरोधित करें',
'ipbreason'         => 'कारण',
'ipbsubmit'         => 'इस सदस्य को और बदलाव करने से रोकें',
'ipboptions'        => '२ घंटे:2 hours,१ दिन:1 day,३ दिन:3 days,१ हफ्ता:1 week,२ हफ्ते:2 weeks,१ महिना:1 month,३ महिने:3 months,६ महिने:6 months,१ साल:1 year,हमेशा के लिये:infinite', # display1:time1,display2:time2,...
'badipaddress'      => 'अमान्य आईपी पता।',
'blockipsuccesssub' => 'अवरोधन सफल ।(संपादन करने से रोक दिया गया है)',
'ipblocklist'       => 'ब्लॉक किये हुए सदस्यनाम और IP एड्रेसकी सूची',
'blocklistline'     => '$1, $2 ने $3 को बदलाव करने से रोक दिया है (यह रोक $4 तक मान्य है)',
'anononlyblock'     => 'केवल अनाम सदस्य',
'blocklink'         => 'अवरोधित करें',
'unblocklink'       => 'अनब्लॉक',
'contribslink'      => 'योगदान',
'blocklogpage'      => 'ब्लॉक सूची',
'blocklogentry'     => '"[[$1]]" को $2 $3 तक बदलाव करने से रोक दिया गया है।',

# Move page
'movepagetext'     => "नीचे दिया हुआ ढाँचा किसी लेख का शीर्षक बदलने में सहयोगी है । इसे सबमीट करने के बाद किसी लेख का नाम बदला जायेगा तथा उसके सभी अवतरण नये नामसे जोडे जायेंगे ।
पुराना शीर्षक नये नामको अनुप्रेषित करेगा ।
पुराने नामसे दी हुई कड़ियाँ नहीं बदली जायेगी, इसलिये आपसे अनुरोध है कि आप गलत एवम्‌ दुगुनी-अनुप्रेषिते देखें ।
गलत कड़ियाँ ना हो इसलिये सर्वस्वी आप जिम्मेदार रहेंगे ।

अगर नये शीर्षकका लेख पहले से है तो स्थानांतरण '''नहीं''' होगा. पर अगर नये शीर्षकका लेख खाली है अथवा अनुप्रेषित करता है (मतलब उसके अवतरन नहीं है) तो स्थानांतरण हो जायेगा । इसका मतलब अगर कुछ गलती हुई तो आप फिरसे पुराने नाम पर स्थानांतरण कर पायेंगे ।

<b>सूचना!</b>
स्थानांतरण करनेसे कोई भी महत्वपूर्ण लेख में अनपेक्षित बदलाव हो सकते है । आपसे अनुरोध है कि आप इसके परिणाम जान लें ।",
'movepagetalktext' => "संबंधित वार्ता पृष्ठ इसके साथ स्थानांतरीत नहीं होगा '''अगर:'''
* आप पृष्ठ दुसरे नामस्थान में स्थानांतरीत कर रहें है
* इस नाम का वार्ता पृष्ठ पहलेसे बना हुवा है, या
* नीचे दिया हुआ चेक बॉक्स आपने निकाल दिया है ।

इन मामलोंमे आपको स्वयं यह पन्ने जोडने पड़ सकते है ।",
'movearticle'      => 'पृष्ठ का नाम बदलें',
'newtitle'         => 'नये शीर्षक की ओर:',
'move-watch'       => 'ध्यान रखें',
'movepagebtn'      => 'नाम बदलें',
'pagemovedsub'     => 'नाम बदल दिया गया है',
'movepage-moved'   => "<big>'''“$1” का नाम बदलकर “$2” कर दिया गया है'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'इस नाम का एक पृष्ठ पहले से ही उपस्थित है, अथवा आप ने अमान्य नाम चुना है। कृपया दूसरा नाम चुनें।',
'talkexists'       => "'''पन्ने का नाम बदल दिया गया है, पर उससे संबंधित वार्ता पृष्ठ नहीं बदला गया है क्योंकि वह पहले से बना हुवा है ।
कृपया इसे स्वयं बदल दे ।'''",
'movedto'          => 'को भेजा गया',
'movetalk'         => 'संबंधित वार्ता पृष्ठ भी बदलें',
'talkpagemoved'    => 'संबंधित वार्ता पृष्ठ भी बदल दिया गया है ।',
'talkpagenotmoved' => 'संबंधित वार्ता पृष्ठ को <strong>नहीं</strong> बदला गया है ।',
'1movedto2'        => '$1 का नाम बदलकर $2 कर दिया गया है',
'movelogpage'      => 'स्थानांतरण सूची',
'movereason'       => 'कारण:',
'revertmove'       => 'पुराने अवतरण पर ले जाएं',

# Export
'export' => 'पन्ने निर्यात किजीयें',

# Namespace 8 related
'allmessages'         => 'व्यवस्था संदेश',
'allmessagesname'     => 'नाम',
'allmessagescurrent'  => 'वर्तमान पाठ',
'allmessagestext'     => 'यह मीडियाविकि नेमस्पेस में उपलब्ध वयवस्था संदेशों की सूची है।',
'allmessagesmodified' => 'केवल परिवर्तित दिखायें',

# Thumbnails
'thumbnail-more'  => 'बड़ा किजीये',
'thumbnail_error' => 'थंबनेल बनानेमें असुविधा हुई है: $1',

# Special:Import
'import'        => 'पन्ने इम्पोर्ट करें',
'importfailed'  => 'इम्पोर्ट असफल रहा  $1',
'importsuccess' => 'इम्पोर्ट सफल हुआ !',

# Import log
'importlogpage' => 'इम्पोर्ट सूची',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'मेरा सदस्य पन्ना',
'tooltip-pt-mytalk'               => 'मेरी सदस्य वार्ता',
'tooltip-pt-preferences'          => 'मेरी पसंद',
'tooltip-pt-watchlist'            => 'आपने ध्यान दिये हुए पन्नोंकी सूची',
'tooltip-pt-mycontris'            => 'मेरे योगदानकी सूची',
'tooltip-pt-login'                => 'आप लॉग इन करें, बल्कि यह अत्यावश्यक नहीं हैं.',
'tooltip-pt-logout'               => 'लॉग आउट',
'tooltip-ca-talk'                 => 'कंटेंट पन्नेके बारेमें वार्तालाप',
'tooltip-ca-edit'                 => 'आप यह पन्ना बदल सकते हैं. कृपया बदलाव संजोनेसे पहले झलक देखें.',
'tooltip-ca-addsection'           => 'इस वार्तालापमें अपनी राय दें ।',
'tooltip-ca-viewsource'           => 'यह पन्ना सुरक्षित हैं । आप इसका स्रोत देख सकते हैं ।',
'tooltip-ca-protect'              => 'इस पन्नेको सुरक्षित किजीयें',
'tooltip-ca-delete'               => 'इस पन्ने को हटाएं',
'tooltip-ca-move'                 => 'यह पन्ना स्थानांतरित किजीयें ।',
'tooltip-ca-watch'                => 'यह पन्ना अपनी ध्यानसूचीमें डालियें',
'tooltip-ca-unwatch'              => 'यह पन्ना अपने ध्यानसूचीसे हटाएं',
'tooltip-search'                  => '{{SITENAME}} में खोजियें',
'tooltip-n-mainpage'              => 'मुखपृष्ठ पर जाए',
'tooltip-n-portal'                => 'प्रोजेक्ट के बारे में, आप क्या कर सकतें हैं, ख़ोजने की जगह',
'tooltip-n-currentevents'         => 'हालकी घटनाओंके पीछेकी जानकारी',
'tooltip-n-recentchanges'         => 'विकिमें हाल में हुए बदलावोंकी सूची.',
'tooltip-n-randompage'            => 'किसी एक लेख पर जाएँ',
'tooltip-n-help'                  => 'ख़ोजने की जगह.',
'tooltip-n-sitesupport'           => 'हमें सहायता दें',
'tooltip-t-whatlinkshere'         => 'यहां जुडे सभी विकिपन्नोंकी सूची',
'tooltip-t-contributions'         => 'इस सदस्यके योगदानकी सूची देखियें',
'tooltip-t-emailuser'             => 'इस सदस्य को इमेल भेजें',
'tooltip-t-upload'                => 'फ़ाइल अपलोड करें',
'tooltip-t-specialpages'          => 'सभी खास पन्नोंकी सूची',
'tooltip-ca-nstab-user'           => 'सदस्य पन्ना देखियें',
'tooltip-ca-nstab-project'        => 'प्रोजेक्ट पन्ना देखियें',
'tooltip-ca-nstab-image'          => 'फ़ाईल पन्ना देख़ें',
'tooltip-ca-nstab-template'       => 'टेम्प्लेट देखियें',
'tooltip-ca-nstab-help'           => 'सहायता पन्ने पर जाईयें',
'tooltip-ca-nstab-category'       => 'श्रेणियाँ पन्ना देखियें',
'tooltip-minoredit'               => 'इस बदलाव को छोटा बदलावके रूपमें दर्शायें',
'tooltip-save'                    => 'आपने किये बदलाव संजोयें',
'tooltip-preview'                 => 'आपने किये हुए बदलावोंकी झलक देखें, कृपया बदलाव संजोनेसे पहले इसका इस्तेमाल किजीयें!',
'tooltip-diff'                    => 'इस पाठ्यमें आपने किये हुए बदलाव देखें।',
'tooltip-compareselectedversions' => 'इस पन्नेके चुने हुए अवतरणोंमें फर्क दिखायें ।',
'tooltip-watch'                   => 'यह पन्ना अपनी ध्यानसूची में डालियें ।',

# Attribution
'anonymous' => 'विकिपीडिया के अनाम सदस्य',
'siteuser'  => 'विकिपीडिया सदस्य  $1',
'siteusers' => 'विकिपीडिया सदस्य  $1',

# Browsing diffs
'previousdiff' => '← पिछला अंतर',
'nextdiff'     => 'अगला अंतर →',

# Media information
'file-info-size'       => '($1 × $2 पीक्सेल, फ़ाईल का आकार: $3, MIME प्रकार: $4)',
'file-nohires'         => '<small>इससे ज्यादा रिज़ोल्यूशन उपलब्ध नहीं हैं.</small>',
'svg-long-desc'        => '(SVG फ़ाईल, साधारणत: $1 × $2 पीक्सेल्स, फ़ाईलका आकार: $3)',
'show-big-image'       => 'सम्पूर्ण रिज़ोल्यूशन',
'show-big-image-thumb' => '<small>इस झलक का आकार: $1 × $2 पीक्सेल्स</small>',

# Special:Newimages
'newimages' => 'नई फ़ाईल्सकी गैलरी',
'ilsubmit'  => 'खोज',

# Bad image list
'bad_image_list' => 'रूपरेख़ा नीचे दी गई है:

सिर्फ सूचीमें होनेवाली फ़ाईल्स (जिनके आगे * यह लिखा गया है) ध्यानमें ली गई हैं । लाईनमें पहली कड़ि गलत फ़ाईल की होनी चाहिये ।

आगे की कड़ियाँ अपवाद है, मतलब ऐसे पन्ने जहां यह फ़ाईल मिल सकती है.',

# Metadata
'metadata'          => 'मेटाडाटा',
'metadata-help'     => 'इस फ़ाईलमें बढ़ाई हुई जानकारी हैं, हो सकता है कि यह फ़ाईल बनानेमें इस्तेमाल किये गए स्कैनर अथवा कैमेरा से यह प्राप्त हुई हैं । अगर यह फ़ाईल बदलदी गई हैं तो यह जानकारी नई फ़ाईलसे मेल नहीं खाने की आशंका हैं ।',
'metadata-expand'   => 'बढ़ाई हुई जानकारी दिखायें',
'metadata-collapse' => 'बढ़ाई हुई जानकारीको छुपायें',
'metadata-fields'   => 'इस सूची में दी गई जानकारी फ़ाईलके नीचे मेटाडाटा जानकारीमें हमेशा दिखेगी ।
बची हुई जानकारी हमेशा छुपाई हुई रहेगी ।
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# External editor support
'edit-externally'      => 'बाहरी प्रणालीका इस्तेमाल करते हुए इस फ़ाईल को संपादित करें ।',
'edit-externally-help' => 'अधिक जानकारीके लिये [http://meta.wikimedia.org/wiki/Help:External_editors सेट‍अप जानकारीयाँ] देखें ।',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'सभी',
'namespacesall' => 'समस्त',
'monthsall'     => 'सभी',

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

# Watchlist editing tools
'watchlisttools-view' => 'आधारित बदलाव देखें',
'watchlisttools-edit' => 'ध्यानसूची देखें एवं संपादित करें',
'watchlisttools-raw'  => 'रॉ ध्यानसूची देखें एवम्‌ संपादित करें',

# Special:Version
'version' => 'रूपान्तर', # Not used as normal message but as header for the special page itself

);
