<?php
/** Hindi (हिन्दी)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Abhishrut
 * @author Akansha
 * @author Aksi great
 * @author Alolitas
 * @author Ansumang
 * @author Bhawani Gautam
 * @author Bhawani Gautam Rhk
 * @author Charu
 * @author Dineshjk
 * @author Hemant wikikosh1
 * @author Htt
 * @author Kaganer
 * @author Kamal
 * @author Kannankumar
 * @author Karunakar
 * @author Kaustubh
 * @author Kiranmayee
 * @author Krinkle
 * @author Kumar
 * @author Mayur
 * @author Odisha1
 * @author Omprakash
 * @author Pulkitsingh01
 * @author Purodha
 * @author Raj Singh
 * @author Rajesh
 * @author Rajivkurjee
 * @author Reedy
 * @author Sajeel.irkal
 * @author Sayak Sarkar
 * @author Shantanoo
 * @author Shrish
 * @author Shyam
 * @author Shyam123.ckp
 * @author Siddhartha Ghai
 * @author Subhashkataria21.90
 * @author Sunil Mohan
 * @author Taxman
 * @author Vibhijain
 * @author Wikiconference
 * @author לערי ריינהארט
 * @author आलोक
 * @author रोहित रावत
 */

$namespaceNames = array(
	NS_MEDIA            => 'मीडिया',
	NS_SPECIAL          => 'विशेष',
	NS_TALK             => 'वार्ता',
	NS_USER             => 'सदस्य',
	NS_USER_TALK        => 'सदस्य_वार्ता',
	NS_PROJECT_TALK     => '$1_वार्ता',
	NS_FILE             => 'चित्र',
	NS_FILE_TALK        => 'चित्र_वार्ता',
	NS_MEDIAWIKI        => 'मीडियाविकि',
	NS_MEDIAWIKI_TALK   => 'मीडियाविकि_वार्ता',
	NS_TEMPLATE         => 'साँचा',
	NS_TEMPLATE_TALK    => 'साँचा_वार्ता',
	NS_HELP             => 'सहायता',
	NS_HELP_TALK        => 'सहायता_वार्ता',
	NS_CATEGORY         => 'श्रेणी',
	NS_CATEGORY_TALK    => 'श्रेणी_वार्ता',
);

$specialPageAliases = array(
	'Activeusers'               => array( 'सक्रिय_सदस्य' ),
	'Allmessages'               => array( 'सभी_सन्देश', 'सभी_संदेश' ),
	'Allpages'                  => array( 'सभी_पृष्ठ', 'सभी_पन्ने' ),
	'Ancientpages'              => array( 'पुराने_पृष्ठ', 'पुराने_पन्ने' ),
	'Badtitle'                  => array( 'खराब_शीर्षक' ),
	'Blankpage'                 => array( 'रिक्त_पृष्ठ', 'खाली_पृष्ठ' ),
	'Block'                     => array( 'अवरोधन', 'आइ_पी_अवरोधन', 'सदस्य_अवरोधन' ),
	'Blockme'                   => array( 'स्वावरोधन', 'स्व_अवरोधन', 'मुझे_रोकिये' ),
	'Booksources'               => array( 'पुस्तक_स्रोत', 'किताब_स्रोत' ),
	'BrokenRedirects'           => array( 'टूटे_पुनर्निर्देश', 'टूटे_अनुप्रेष' ),
	'Categories'                => array( 'श्रेणियाँ' ),
	'ChangeEmail'               => array( 'ईमेल_बदलें' ),
	'ChangePassword'            => array( 'कूटशब्द_बदलें' ),
	'ComparePages'              => array( 'पृष्ठ_तुलना' ),
	'Confirmemail'              => array( 'ईमेल_पुष्टि', 'ईमेल_पुष्टि_करें' ),
	'Contributions'             => array( 'योगदान' ),
	'CreateAccount'             => array( 'खाता_बनाएँ', 'खाता_बनायें', 'खाता_खोलें' ),
	'Deadendpages'              => array( 'बन्द_पृष्ठ', 'बन्द_पन्ने' ),
	'DeletedContributions'      => array( 'हटाए_गए_योगदान', 'हटाये_गये_योगदान' ),
	'Disambiguations'           => array( 'बहुविकल्पी_कड़ियाँ', 'बहुविकल्पित' ),
	'DoubleRedirects'           => array( 'दुगुने_पुनर्निर्देश', 'दुगुने_अनुप्रेष' ),
	'EditWatchlist'             => array( 'ध्यानसूची_सम्पादन', 'ध्यानसूची_संपादन', 'ध्यानसूची_सम्पादन_करें' ),
	'Emailuser'                 => array( 'ईमेल_करें', 'सदस्य_को_ईमेल_करें' ),
	'Export'                    => array( 'निर्यात' ),
	'Fewestrevisions'           => array( 'न्यूनतम_अवतरण', 'कम_सम्पादित_पृष्ठ' ),
	'FileDuplicateSearch'       => array( 'फ़ाइल_प्रति_खोज', 'फाइल_प्रति_खोज', 'संचिका_प्रति_खोज' ),
	'Filepath'                  => array( 'फ़ाइल_पथ', 'फाइल_पथ', 'संचिका_पथ' ),
	'Import'                    => array( 'आयात' ),
	'Invalidateemail'           => array( 'अप्रमाणित_ईमेल', 'अमान्य_ईमेल', 'ईमेल_अमान्य_करें' ),
	'BlockList'                 => array( 'अवरोध_सूची', 'अवरोधित_सदस्य_सूची', 'अवरोधित_आइ_पी_सूची' ),
	'LinkSearch'                => array( 'बाहरी_कड़ी_खोज' ),
	'Listadmins'                => array( 'प्रबन्धक_सूची', 'प्रबंधक_सूची' ),
	'Listbots'                  => array( 'बॉट_सूची', 'बौट_सूची' ),
	'Listfiles'                 => array( 'फ़ाइल_सूची', 'फाइल_सूची' ),
	'Listgrouprights'           => array( 'सदस्य_समूह_अधिकार', 'अधिकार_सूची' ),
	'Listredirects'             => array( 'पुनर्निर्देश_सूची', 'अनुप्रेष_सूची' ),
	'Listusers'                 => array( 'सदस्य_सूची' ),
	'Lockdb'                    => array( 'डाटाबेस_पर_ताला_लगाएँ' ),
	'Log'                       => array( 'लॉग', 'लौग' ),
	'Lonelypages'               => array( 'एकाकी_पृष्ठ', 'अकेले_पृष्ठ' ),
	'Longpages'                 => array( 'लम्बे_पृष्ठ', 'लंबे_पृष्ठ' ),
	'MergeHistory'              => array( 'इतिहास_विलय' ),
	'MIMEsearch'                => array( 'माइम_खोज' ),
	'Mostcategories'            => array( 'सर्वाधिक_श्रेणीकृत', 'सर्वाधिक_श्रेणियाँ' ),
	'Mostimages'                => array( 'सर्वाधिक_प्रयुक्त_फ़ाइलें', 'सर्वाधिक_प्रयुक्त_फाइलें' ),
	'Mostlinked'                => array( 'सर्वाधिक_जुड़े_पृष्ठ' ),
	'Mostlinkedcategories'      => array( 'सर्वाधिक_प्रयुक्त_श्रेणियाँ' ),
	'Mostlinkedtemplates'       => array( 'सर्वाधिक_प्रयुक्त_साँचे' ),
	'Mostrevisions'             => array( 'सर्वाधिक_अवतरण', 'अधिकतम_सम्पादित_पृष्ठ', 'अधिकतम_संपादित_पृष्ठ' ),
	'Movepage'                  => array( 'स्थानान्तरण', 'स्थानांतरण', 'नाम_बदलें' ),
	'Mycontributions'           => array( 'मेरे_योगदान', 'मेरा_योगदान' ),
	'Mypage'                    => array( 'मेरा_पृष्ठ', 'मेरा_सदस्य_पृष्ठ' ),
	'Mytalk'                    => array( 'मेरी_वार्ता', 'मेरी_सदस्य_वार्ता' ),
	'Myuploads'                 => array( 'मेरे_अपलोड' ),
	'Newimages'                 => array( 'नई_फ़ाइलें', 'नई_फाइलें', 'नये_चित्र' ),
	'Newpages'                  => array( 'नए_पृष्ठ', 'नए_पन्ने', 'नये_पृष्ठ' ),
	'PasswordReset'             => array( 'कूटशब्द_पुनर्स्थापन' ),
	'PermanentLink'             => array( 'स्थाई_कड़ी', 'स्थायी_कड़ी' ),
	'Preferences'               => array( 'वरीयताएँ' ),
	'Prefixindex'               => array( 'उपसर्ग_अनुसार_पृष्ठ', 'उपसर्ग_खोज', 'उपसर्ग_सूचकांक' ),
	'Protectedpages'            => array( 'सुरक्षित_पृष्ठ' ),
	'Protectedtitles'           => array( 'सुरक्षित_शीर्षक' ),
	'Search'                    => array( 'खोज', 'खोजें' ),
	'Shortpages'                => array( 'छोटे_पृष्ठ', 'छोटे_पन्ने' ),
	'Specialpages'              => array( 'विशेष_पृष्ठ', 'विशेष_पन्ने' ),
	'Tags'                      => array( 'टैग', 'चिप्पियाँ' ),
	'Unblock'                   => array( 'अवरोध_हटाएँ', 'अवरोध_हटायें' ),
	'Uncategorizedcategories'   => array( 'श्रेणीहीन_श्रेणियाँ' ),
	'Uncategorizedimages'       => array( 'श्रेणीहीन_फ़ाइलें', 'श्रेणीहीन_फाइलें' ),
	'Uncategorizedpages'        => array( 'श्रेणीहीन_पृष्ठ', 'श्रेणीहीन_पन्ने' ),
	'Uncategorizedtemplates'    => array( 'श्रेणीहीन_साँचे' ),
	'Undelete'                  => array( 'पुनर्स्थापन' ),
	'Unlockdb'                  => array( 'डाटाबेस_से_ताला_हटाएँ' ),
	'Unusedcategories'          => array( 'अप्रयुक्त_श्रेणियाँ' ),
	'Unusedimages'              => array( 'अप्रयुक्त_फ़ाइलें', 'अप्रयुक्त_फाइलें' ),
	'Unusedtemplates'           => array( 'अप्रयुक्त_साँचे' ),
	'Upload'                    => array( 'अपलोड' ),
	'Userlogin'                 => array( 'लॉगिन', 'लौगिन', 'सत्रारम्भ', 'सत्रारंभ' ),
	'Userlogout'                => array( 'सत्रांत', 'लॉग_आउट', 'लौग_आउट' ),
	'Userrights'                => array( 'सदस्य_अधिकार' ),
	'Version'                   => array( 'संस्करण', 'वर्ज़न', 'वर्जन' ),
	'Wantedcategories'          => array( 'वांछित_श्रेणियाँ' ),
	'Wantedfiles'               => array( 'वांछित_फ़ाइलें', 'वांछित_फाइलें' ),
	'Wantedpages'               => array( 'वांछित_पृष्ठ', 'वांछित_पन्ने' ),
	'Wantedtemplates'           => array( 'वांछित_साँचे' ),
	'Watchlist'                 => array( 'ध्यानसूची' ),
	'Whatlinkshere'             => array( 'कड़ियाँ', 'यहाँ_की_कड़ियाँ', 'यहाँ_क्या_जुड़ता_है' ),
	'Withoutinterwiki'          => array( 'अन्तरविकि_रहित', 'अंतरविकि_रहित' ),
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
$linkTrail = "/^([a-z]+)(.*)$/sD";

$digitGroupingPattern = "##,##,###";

$messages = array(
# User preference toggles
'tog-underline' => 'कड़ियाँ अधोरेखन:',
'tog-justify' => 'परिच्छेद समान करें',
'tog-hideminor' => 'हाल में हुए बदलावों में छोटे बदलाव छिपाएँ',
'tog-hidepatrolled' => 'हाल में हुए बदलावों में जाँचे हुए बदलाव छिपाएँ',
'tog-newpageshidepatrolled' => 'नए पृष्ठों की सूची में से जाँचे हुए पृष्ठों को छिपाएँ',
'tog-extendwatchlist' => 'केवल हालिया ही नहीं, बल्कि सभी परिवर्तनों को दिखाने के लिए ध्यानसूची को विस्तारित करें',
'tog-usenewrc' => 'हाल में हुए परिवर्तनों में और ध्यानसूची में परिवर्तनों को पृष्ठ अनुसार समूहों में बाँटें (जावास्क्रिप्ट आवश्यक)',
'tog-numberheadings' => 'शीर्षक स्वयं-क्रमांकित करें',
'tog-showtoolbar' => 'सम्पादन औज़ारपट्टी दिखाएँ (जावास्क्रिप्ट की आवश्यकता है)',
'tog-editondblclick' => 'दुगुने क्लिक पर पृष्ठ संपादित करें (जावास्क्रिप्ट की आवश्यकता है)',
'tog-editsection' => '[संपादित करें] कड़ियों द्वारा अनुभाग संपादन सक्षम करें',
'tog-editsectiononrightclick' => 'अनुभाग शीर्षक पर दायाँ क्लिक करने पर अनुभाग सम्पादित करें (जावास्क्रिप्ट की आवश्यकता है)',
'tog-showtoc' => 'अनुक्रम दर्शायें (जिन पृष्ठों पर तीन से अधिक अनुभाग हों)',
'tog-rememberpassword' => 'इस ब्राउज़र पर मेरा कूटशब्द  (अधिकतम $1 {{PLURAL:$1|दिन|दिनों}} तक) याद रखें',
'tog-watchcreations' => 'मेरे द्वारा निर्मित पृष्ठों और मेरी अपलोड की फ़ाइलों को मेरी ध्यानसूची में जोड़ें',
'tog-watchdefault' => 'मेरे द्वारा सम्पादित पृष्ठों और फ़ाइलों को मेरी ध्यानसूची में जोड़ें',
'tog-watchmoves' => 'मेरे द्वारा स्थानांतरित पृष्ठों एवं फ़ाइलों को मेरी ध्यानसूची में जोड़ें',
'tog-watchdeletion' => 'मेरे द्वारा हटाए गए पृष्ठों एवं फ़ाइलों को मेरी ध्यानसूची में जोड़ें',
'tog-minordefault' => 'मेरे सभी सम्पादन छोटे बदलाव हैं',
'tog-previewontop' => 'सम्पादन बक्से के ऊपर झलक दिखाएँ',
'tog-previewonfirst' => 'प्रथम सम्पादन के बाद झलक दिखाएँ',
'tog-nocache' => 'ब्राउज़र पृष्ठ कैशिंग अक्षम करें',
'tog-enotifwatchlistpages' => 'मेरी ध्यानसूची में दर्ज किसी भी पृष्ठ अथवा फ़ाइल में परिवर्तन होने पर मुझे ई-मेल करें',
'tog-enotifusertalkpages' => 'मेरा वार्ता पृष्ठ परिवर्तित होने पर मुझे ई-मेल करें',
'tog-enotifminoredits' => 'छोटे परिवर्तनों के लिए भी मुझे ई-मेल भेजें',
'tog-enotifrevealaddr' => 'अधिसूचना ई-मेल में मेरा ई-मेल पता दर्शाएँ',
'tog-shownumberswatching' => 'ध्यान रखने वाले सदस्यों की संख्या दिखाएँ',
'tog-oldsig' => 'वर्तमान हस्ताक्षर:',
'tog-fancysig' => 'हस्ताक्षर का विकिपाठ के समान उपयोग करें (बिना स्वचालित कड़ी के)',
'tog-externaleditor' => 'डिफ़ॉल्ट रूप से बाह्य सम्पादक का उपयोग करें (केवल विशेषज्ञों के लिए, इसके लिए संगणक पर विशेष जमाव चाहिए होंगे। [//www.mediawiki.org/wiki/Manual:External_editors अधिक जानकारी।])',
'tog-externaldiff' => 'डिफ़ॉल्ट रूप से बाह्य अन्तर का उपयोग करें (केवल विशेषज्ञों के लिए, इसके लिए संगणक पर विशेष जमाव चाहिए होंगे। [//www.mediawiki.org/wiki/Manual:External_editors अधिक जानकारी।])',
'tog-showjumplinks' => '"की ओर जाएं" कड़ियाँ उपलब्ध कराएँ',
'tog-uselivepreview' => 'सजीवन झलक का उपयोग करें (जावास्क्रिप्ट चाहिए) (प्रयोगक्षम)',
'tog-forceeditsummary' => 'यदि बदलाव सारांश ना दिया गया हो तो मुझे सूचित करें',
'tog-watchlisthideown' => 'मेरी ध्यानसूची से मेरे किए परिवर्तन छिपाएँ',
'tog-watchlisthidebots' => 'मेरी ध्यानसूची से बॉटों द्वारा किए परिवर्तन छिपाएँ',
'tog-watchlisthideminor' => 'मेरी ध्यानसूची से छोटे परिवर्तन छिपाएँ',
'tog-watchlisthideliu' => 'मेरी ध्यानसूची में सत्रारम्भित सदस्यों के सम्पादन न दिखाएँ',
'tog-watchlisthideanons' => 'आइ॰पी सदस्यों द्वारा किए सम्पादनों को मेरी ध्यानसूची में न दिखाएँ',
'tog-watchlisthidepatrolled' => 'जाँचे गए सम्पादनों को मेरी ध्यानसूची में न दिखाएँ',
'tog-ccmeonemails' => 'मेरे द्वारा अन्य सदस्यों को भेजे ई-मेलों की प्रतियाँ मुझे भी भेजें',
'tog-diffonly' => 'अवतरणों में अन्तर दर्शाते समय पुराने अवतरण न दिखाएँ',
'tog-showhiddencats' => 'छिपाई हुई श्रेणियाँ दिखाएँ',
'tog-norollbackdiff' => 'सम्पादन वापस लेने के बाद अन्तर न दिखाएँ',

'underline-always' => 'सदैव',
'underline-never' => 'कभी नहीं',
'underline-default' => 'त्वचा या ब्राउज़र डिफ़ॉल्ट',

# Font style option in Special:Preferences
'editfont-style' => 'सम्पादन क्षेत्र की मुद्रलिपि शैली:',
'editfont-default' => 'ब्राउज़र डिफ़ॉल्ट',
'editfont-monospace' => 'एकल स्थल मुद्रलिपि',
'editfont-sansserif' => 'बिना नोकों वाली मुद्रलिपि',
'editfont-serif' => 'नोकों वाली मुद्रलिपि',

# Dates
'sunday' => 'रविवार',
'monday' => 'सोमवार',
'tuesday' => 'मंगलवार',
'wednesday' => 'बुधवार',
'thursday' => 'गुरुवार',
'friday' => 'शुक्रवार',
'saturday' => 'शनिवार',
'sun' => 'रवि',
'mon' => 'सोम',
'tue' => 'मंगल',
'wed' => 'बुध',
'thu' => 'गुरू',
'fri' => 'शुक्र',
'sat' => 'शनि',
'january' => 'जनवरी',
'february' => 'फ़रवरी',
'march' => 'मार्च',
'april' => 'अप्रैल',
'may_long' => 'मई',
'june' => 'जून',
'july' => 'जुलाई',
'august' => 'अगस्त',
'september' => 'सितम्बर',
'october' => 'अक्टूबर',
'november' => 'नवम्बर',
'december' => 'दिसम्बर',
'january-gen' => 'जनवरी',
'february-gen' => 'फ़रवरी',
'march-gen' => 'मार्च',
'april-gen' => 'अप्रैल',
'may-gen' => 'मई',
'june-gen' => 'जून',
'july-gen' => 'जुलाई',
'august-gen' => 'अगस्त',
'september-gen' => 'सितम्बर',
'october-gen' => 'अक्टूबर',
'november-gen' => 'नवम्बर',
'december-gen' => 'दिसम्बर',
'jan' => 'जन॰',
'feb' => 'फ़र॰',
'mar' => 'मार्च',
'apr' => 'अप्रै॰',
'may' => 'मई',
'jun' => 'जून',
'jul' => 'जुला॰',
'aug' => 'अग॰',
'sep' => 'सित॰',
'oct' => 'अक्टू॰',
'nov' => 'नव॰',
'dec' => 'दिस॰',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|श्रेणी|श्रेणियाँ}}',
'category_header' => '"$1" श्रेणी में पृष्ठ',
'subcategories' => 'उपश्रेणियाँ',
'category-media-header' => '"$1" श्रेणी में मीडिया',
'category-empty' => "''इस श्रेणी में इस समय कोई पृष्ठ या मीडिया नहीं हैं।''",
'hidden-categories' => '{{PLURAL:$1|छुपाई हुई श्रेणी|छुपाई हुई श्रेणियाँ}}',
'hidden-category-category' => 'छुपाई हुई श्रेणियाँ',
'category-subcat-count' => '{{PLURAL:$2|इस श्रेणी में केवल निम्नलिखित उपश्रेणी है|इस श्रेणी में निम्नलिखित {{PLURAL:$1|उपश्रेणी|$1 उपश्रेणियाँ}} हैं, कुल उपश्रेणियाँ $2}}',
'category-subcat-count-limited' => 'इस श्रेणी में निम्नलिखित {{PLURAL:$1|उपश्रेणी है|$1 उपश्रेणियाँ हैं}}।',
'category-article-count' => '{{PLURAL:$2|इस श्रेणी में केवल निम्नलिखित पृष्ठ है।|इस श्रेणी में निम्नलिखित {{PLURAL:$1|पृष्ठ है|$1 पृष्ठ हैं}}, कुल पृष्ठ $2}}',
'category-article-count-limited' => 'निम्नलिखित {{PLURAL:$1|पृष्ठ|$1 पृष्ठ}} इस श्रेणी में हैं।',
'category-file-count' => '{{PLURAL:$2|इस श्रेणी में केवल निम्नलिखित फ़ाइल है।|इस श्रेणी में निम्नलिखित {{PLURAL:$1|फ़ाइल|$1 फ़ाइलें}} हैं, कुल फ़ाइलें $2}}',
'category-file-count-limited' => 'इस श्रेणी में निम्नलिखित {{PLURAL:$1|फ़ाइल है।|फ़ाइलें हैं।}}',
'listingcontinuesabbrev' => 'आगे.',
'index-category' => 'सूचीबद्ध पृष्ठ',
'noindex-category' => 'असूचीबद्ध पृष्ठ',
'broken-file-category' => 'टूटी हुई सञ्चिका कड़ियों वाले पृष्ठ',

'about' => 'के बारे में',
'article' => 'लेख',
'newwindow' => '(नई विंडो में खुलता है)',
'cancel' => 'रद्द करें',
'moredotdotdot' => 'और...',
'mypage' => 'पृष्ठ',
'mytalk' => 'वार्ता',
'anontalk' => 'इस आइ॰पी के लिये वार्ता',
'navigation' => 'भ्रमण',
'and' => '&#32;और',

# Cologne Blue skin
'qbfind' => 'खोज',
'qbbrowse' => 'ब्राउज़',
'qbedit' => 'बदलें',
'qbpageoptions' => 'यह पृष्ठ',
'qbpageinfo' => 'पृष्ठ जानकारी',
'qbmyoptions' => 'मेरे पृष्ठ',
'qbspecialpages' => 'विशेष पृष्ठ',
'faq' => 'बहुधा पूछित प्रश्न',
'faqpage' => 'Project:अक्सर पूछे जाने वाले सवाल',

# Vector skin
'vector-action-addsection' => 'विषय जोड़ें',
'vector-action-delete' => 'हटाएँ',
'vector-action-move' => 'स्थानांतरण करें',
'vector-action-protect' => 'सुरक्षित करें',
'vector-action-undelete' => 'हटाना वापस लें',
'vector-action-unprotect' => 'सुरक्षा बदलें',
'vector-simplesearch-preference' => 'संवर्धित खोज सुझाव सक्षम करें। (केवल वॅक्टर स्किन हेतु)',
'vector-view-create' => 'बनाएँ',
'vector-view-edit' => 'सम्पादन',
'vector-view-history' => 'इतिहास देखें',
'vector-view-view' => 'पढ़ें',
'vector-view-viewsource' => 'स्रोत देखें',
'actions' => 'क्रियाएँ',
'namespaces' => 'नामस्थान',
'variants' => 'संस्करण',

'errorpagetitle' => 'त्रुटि',
'returnto' => '$1 को लौटें।',
'tagline' => '{{SITENAME}} से',
'help' => 'मदद',
'search' => 'खोज',
'searchbutton' => 'खोजें',
'go' => 'जाएँ',
'searcharticle' => 'जाएँ',
'history' => 'पृष्ठ इतिहास',
'history_short' => 'इतिहास',
'updatedmarker' => 'मेरे अन्तिम बार पधारने के बाद के अद्यतन',
'printableversion' => 'छापने योग्य संस्करण',
'permalink' => 'स्थायी कड़ी',
'print' => 'प्रिंट करें',
'view' => 'दर्शाव',
'edit' => 'सम्पादन',
'create' => 'बनाएँ',
'editthispage' => 'इस पृष्ठ को बदलें',
'create-this-page' => 'यह पृष्ठ बनाएँ',
'delete' => 'हटाएँ',
'deletethispage' => 'इस पृष्ठ को हटायें',
'undelete_short' => '{{PLURAL:$1|एक हटाया गया|$1 हटाए गए}} बदलाव वापस लायें',
'viewdeleted_short' => 'देखें {{PLURAL:$1|एक हटाया गया सम्पादन|$1 हटाए गए सम्पादन}}',
'protect' => 'सुरक्षित करें',
'protect_change' => 'बदलें',
'protectthispage' => 'इस पृष्ठ को सुरक्षित करें',
'unprotect' => 'असुरक्षित',
'unprotectthispage' => 'इस पृष्ठ को सुरक्षित करै',
'newpage' => 'नया पृष्ठ',
'talkpage' => 'इस पृष्ठ के बारे में चर्चा करें',
'talkpagelinktext' => 'चर्चा',
'specialpage' => 'विशेष पृष्ठ',
'personaltools' => 'वैयक्तिक औज़ार',
'postcomment' => 'नया अनुभाग',
'articlepage' => 'लेख देखें',
'talk' => 'चर्चा',
'views' => 'दर्शाव',
'toolbox' => 'साधन पेटी',
'userpage' => 'सदस्य पृष्ठ देखें',
'projectpage' => 'परियोजना पृष्ठ देखें',
'imagepage' => 'फ़ाइल पृष्ठ देखें',
'mediawikipage' => 'सन्देश पृष्ठ देखें',
'templatepage' => 'साँचा पृष्ठ देखें',
'viewhelppage' => 'सहायता पृष्ठ देखें',
'categorypage' => 'श्रेणी पृष्ठ देखें',
'viewtalkpage' => 'चर्चा देखें',
'otherlanguages' => 'अन्य भाषाएँ',
'redirectedfrom' => '($1 से पुनर्निर्देशित)',
'redirectpagesub' => 'पुनर्निर्देश पृष्ठ',
'lastmodifiedat' => 'इस पृष्ठ का पिछला बदलाव $1 को $2 बजे हुआ था।',
'viewcount' => 'यह पृष्ठ {{PLURAL:$1|एक|$1}} बार देखा गया है।',
'protectedpage' => 'सुरक्षित पृष्ठ',
'jumpto' => 'यहाँ जाएँ:',
'jumptonavigation' => 'भ्रमण',
'jumptosearch' => 'खोज',
'view-pool-error' => 'क्षमा करें, इस समय सर्वरों पर अतिभार है।
बहुत सारे प्रयोक्ता इस पृष्ठ को देखने का प्रयास कर रहे हैं।
कृपया कुछ समय प्रतीक्षा कर फिर से इस पृष्ठ तक जाने का प्रयास करें।

$1',
'pool-timeout' => 'तालाबन्दी के लिए प्रतीक्षा समय समाप्त',
'pool-queuefull' => 'पूल पंक्ति भरी हुई है',
'pool-errorunknown' => 'अज्ञात त्रुटि',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => '{{SITENAME}} के बारे में',
'aboutpage' => 'Project:परिचय',
'copyright' => 'उपलब्ध सामग्री $1 के अधीन है।',
'copyrightpage' => '{{ns:project}}:सर्वाधिकार',
'currentevents' => 'हाल की घटनाएँ',
'currentevents-url' => 'Project:हाल की घटनाएँ',
'disclaimers' => 'अस्वीकरण',
'disclaimerpage' => 'Project:साधारण अस्वीकरण',
'edithelp' => 'सम्पादन सहायता',
'edithelppage' => 'Help:संपादन',
'helppage' => 'Help:सहायता',
'mainpage' => 'मुख्य पृष्ठ',
'mainpage-description' => 'मुख्य पृष्ठ',
'policy-url' => 'Project:नीति',
'portal' => 'समाज मुखपृष्ठ',
'portal-url' => 'Project:समाज मुखपृष्ठ',
'privacy' => 'गोपनीयता नीति',
'privacypage' => 'Project:गोपनीयता नीति',

'badaccess' => 'अनुमति त्रुटि',
'badaccess-group0' => 'जिस क्रिया का अनुरोध आपने किया है उसे संचालित करने की अनुमति आपको नहीं है।',
'badaccess-groups' => 'आपने जो क्रिया आजमाई है वह केवल {{PLURAL:$2|$1 समूह|$1 समूहों}} के सदस्य ही कर सकते हैं।',

'versionrequired' => 'मीडीयाविकी का $1 अवतरण ज़रूरी है।',
'versionrequiredtext' => 'यह पृष्ठ प्रयोग करने के लिये मीडियाविकी का $1 अवतरण ज़रूरी है।
देखें [[Special:Version|अवतरण पृष्ठ]]।',

'ok' => 'ठीक है',
'retrievedfrom' => '"$1" से लिया गया',
'youhavenewmessages' => 'आपके लिए $1 है। ($2)',
'newmessageslink' => 'नए सन्देश',
'newmessagesdifflink' => 'पिछला बदलाव',
'youhavenewmessagesfromusers' => 'आपके लिये {{PLURAL:$3|एक अन्य सदस्य का सन्देश है|$3 अन्य सदस्यों के सन्देश हैं}}। ($2)',
'youhavenewmessagesmanyusers' => 'आपके लिये $1 हैं। ($2)',
'newmessageslinkplural' => '{{PLURAL:$1|एक नया सन्देश|नये सन्देश}}',
'newmessagesdifflinkplural' => 'अंतिम {{PLURAL:$1|परिवर्तन}}',
'youhavenewmessagesmulti' => '$1 पर आपके लिए नया संदेश है',
'editsection' => 'सम्पादन',
'editold' => 'सम्पादन',
'viewsourceold' => 'स्रोत देखें',
'editlink' => 'सम्पादन',
'viewsourcelink' => 'स्रोत देखें',
'editsectionhint' => 'अनुभाग सम्पादन: $1',
'toc' => 'विषय सूची',
'showtoc' => 'दिखाएँ',
'hidetoc' => 'छिपाएँ',
'collapsible-collapse' => 'छोटा करें',
'collapsible-expand' => 'विस्तार करें',
'thisisdeleted' => '$1 देखें या बदलें?',
'viewdeleted' => '$1 दिखायें?',
'restorelink' => '{{PLURAL:$1|एक हटाया हुआ|$1 हटाये हुए}} बदलाव',
'feedlinks' => 'फ़ीड:',
'feed-invalid' => 'गलत सब्स्क्रीप्शन फ़ीड प्रकार',
'feed-unavailable' => 'संघ फ़ीड उपलब्ध नहीं हैं',
'site-rss-feed' => '$1 की आर॰एस॰एस फ़ीड',
'site-atom-feed' => '$1 की ऐटम फ़ीड',
'page-rss-feed' => '"$1" आर॰एस॰एस फ़ीड',
'page-atom-feed' => '"$1" ऐटम फ़ीड',
'feed-atom' => 'ऐटम',
'feed-rss' => 'आर॰एस॰एस',
'red-link-title' => '$1 (पृष्ठ मौजूद नहीं है)',
'sort-descending' => 'उतरते क्रम में क्रमबद्ध करें',
'sort-ascending' => 'चढ़ते क्रम में क्रमबद्ध करें',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'पृष्ठ',
'nstab-user' => 'सदस्य',
'nstab-media' => 'मीडिया',
'nstab-special' => 'विशेष पृष्ठ',
'nstab-project' => 'परियोजना पृष्ठ',
'nstab-image' => 'फ़ाइल',
'nstab-mediawiki' => 'सन्देश',
'nstab-template' => 'साँचा',
'nstab-help' => 'सहायता पृष्ठ',
'nstab-category' => 'श्रेणी',

# Main script and global functions
'nosuchaction' => 'ऐसा कोई कार्य नहीं है',
'nosuchactiontext' => 'इस यू॰आर॰एल द्वारा निर्दिष्ट क्रिया अवैध है।
आपने यू॰आर॰एल गलत लिखा होगा, या किसी गलत कड़ी का प्रयोग किया होगा।
यह {{SITENAME}} के सॉफ़्टवेयर में त्रुटि भी हो सकती है।',
'nosuchspecialpage' => 'ऐसा कोई विशेष पृष्ठ नहीं है',
'nospecialpagetext' => '<strong>आपने अवैध विशेष पृष्ठ माँगा है।</strong>
वैध विशेष पृष्ठों की सूची [[Special:SpecialPages|{{int:specialpages}}]] पर देखी जा सकती है।',

# General errors
'error' => 'त्रुटि',
'databaseerror' => 'डाटाबेस त्रुटि',
'dberrortext' => 'डाटाबेस प्रश्न वाक्यरचना में त्रुटि मिली है।
संभव है कि यह सॉफ़्टवेयर में त्रुटि की वजह से हो।
पिछला डाटाबेस प्रश्न था:
<blockquote><code>$1</code></blockquote>
 "<code>$2</code>" कार्य समूह से।
डाटाबेस की त्रुटि थी "<samp>$3: $4</samp>"।',
'dberrortextcl' => 'डाटाबेस प्रश्न की वाक्यरचना में त्रुटि मिली।
डाटाबेस में पिछला प्रश्न था:
"$1"
कार्यसमूह "$2" से।
डाटाबेस से त्रुटि आई "$3: $4"',
'laggedslavemode' => "'''चेतावनी:''' यह पृष्ठ अद्यतनीत जानकारी-युक्त ना होने की आशंका है।",
'readonly' => 'डाटाबेस लॉक किया हुआ है',
'enterlockreason' => 'लॉक करने का कारण दीजिए, साथ ही लॉक खुलने के समय का लगभग आकलन दिजीये।',
'readonlytext' => 'शायद मेंटेनन्स के चलते डाटाबेस नये संपादन और अन्य बदलावों से लॉक किया गया है, जिसके बाद यह सामान्य स्थिति में आ जाना चाहिये।

जिस प्रबंधक ने यह लॉक किया था उसने यह कारण दिया है: $1',
'missing-article' => 'डाटाबेस में $2 के अंदर कहीं भी "$1" नहीं मिला।

आम तौर पर हटाए जा चुके पृष्ठ की इतिहास कड़ी का प्रयोग करने पर ऐसा होता है।

अगर ऐसा नहीं है, तो शायद आपने सॉफ़्टवेयर में त्रुटि खोज ली है।
कृपया यू॰आर॰एल पते समेत किसी [[Special:ListUsers/sysop|प्रबंधक]] को इसका ब्यौरा दें।',
'missingarticle-rev' => '(अवतरण#: $1)',
'missingarticle-diff' => '(अंतर: $1, $2)',
'readonly_lag' => 'उपमुख्य डाटाबेस सर्वर मुख्य डाटाबेस सर्वर के बराबर अद्यातानीत होने तक मुख्य डाटाबेस सर्वर लॉक हो गया है।',
'internalerror' => 'आन्तरिक त्रुटि',
'internalerror_info' => 'आन्तरिक त्रुटि: $1',
'fileappenderrorread' => 'संलग्न करने के दौरान "$1" पढ़ा नहीं जा सका।',
'fileappenderror' => '"$1" के आगे "$2" नहीं जुड़ पाया',
'filecopyerror' => '"$1" फ़ाइल की "$2" पर प्रतिलिपि नहीं बन पाई।',
'filerenameerror' => '"$1" फ़ाइल का नाम बदलकर "$2" नहीं रखा जा सका।',
'filedeleteerror' => '"$1" फ़ाइल को हटाया नहीं जा सका।',
'directorycreateerror' => '"$1" डाइरेक्टरी नहीं बनाई जा सकी।',
'filenotfound' => '"$1" फ़ाइल नहीं मिली।',
'fileexistserror' => 'फ़ाइल "$1" पर लिख नहीं सकते: फ़ाइल अस्तित्व में है',
'unexpected' => 'अनपेक्षित मूल्य: "$1"="$2".',
'formerror' => 'त्रुटि: फ़ॉर्म सबमिट नहीं किया जा सका',
'badarticleerror' => 'इस पृष्ठ पर यह कार्य नहीं किया जा सकता।',
'cannotdelete' => '"$1" पृष्ठ या फ़ाइल को हटाया नहीं जा सकता।
शायद किसी और ने इसे पहले ही हटा दिया हो।',
'cannotdelete-title' => '"$1" पृष्ठ को हटाया नहीं जा सकता',
'delete-hook-aborted' => 'हुक द्वारा हटाना बीच में ही छोड़ा गया।
इसने कोई कारण नहीं बताया।',
'badtitle' => 'खराब शीर्षक',
'badtitletext' => 'आपके द्वारा अनुरोधित शीर्षक अयोग्य, ख़ाली या गलत जुड़ा हुआ अंतर-भाषीय या अंतर-विकि शीर्षक है।
इसमें एक या एक से अधिक ऐसे कॅरेक्टर हो सकते हैं जो शीर्षक में प्रयोग नहीं किये जा सकते।',
'perfcached' => 'नीचे दिया हुआ डेटा कैशे मेमोरी से लिया हुआ है, अतः हो सकता है कि इसका पूर्ण अद्यतन न हुआ हो। कैशे मेमोरी में अधिकतम {{PLURAL:$1|एक  नतीजा|$1 नतीजे}} उपलब्ध हैं।',
'perfcachedts' => 'नीचे दिया हुआ डेटा कैशे मेमोरी से है, और इसका अंतिम अद्यतन $1 को हुआ था। कैशे मेमोरी में अधिकतम {{PLURAL:$4|एक  नतीजा|$4 नतीजे}} उपलब्ध हैं।',
'querypage-no-updates' => 'इस पृष्ठ का नवीनीकरण करना मना है। अभी यहाँ के डाटा को ताज़ा नहीं कर सकते।',
'wrong_wfQuery_params' => 'wfQuery() के लिये गलत मापदण्ड दिये हैं<br />
फ़ंक्शन: $1<br />
पृच्छा: $2',
'viewsource' => 'स्रोत देखें',
'viewsource-title' => '$1 का स्रोत देखें',
'actionthrottled' => 'कार्य समाप्त कर दिया गया है',
'actionthrottledtext' => 'स्पैम की रोकथाम के लिये, यह क्रिया इतने कम समय में एक सीमा से अधिक बार करने से मनाई है, और आप इस सीमा को पार कर चुके हैं।
कृपया कुछ समय बाद पुन: यत्न करें।',
'protectedpagetext' => 'यह पृष्ठ संपादनों एवं अन्य कार्यों से सुरक्षित किया हुआ है।',
'viewsourcetext' => 'आप इस पृष्ठ का स्रोत देख सकते हैं और उसकी नकल उतार सकते हैं:',
'viewyourtext' => "आप इस पृष्ठ में ''अपने सम्पादन'' का स्रोत देख सकते हैं और उसकी नकल उतार सकते हैं:",
'protectedinterface' => 'यह पृष्ठ इस विकी के सॉफ़्टवेयर का इंटरफ़ेस पाठ देता है, और इसे गलत प्रयोग से बचाने के लिये सुरक्षित कर दिया गया है।
सभी विकियों के लिए अनुवाद जोड़ने या बदलने के लिए कृपया मीडियाविकि के क्षेत्रीयकरण प्रकल्प [//translatewiki.net/ translatewiki.net] का प्रयोग करें।',
'editinginterface' => "'''चेतावनी:''' आप एक ऐसे पृष्ठ को बदल रहे हैं जो सॉफ़्टवेयर का इंटरफ़ेस पाठ प्रदान करता है।
इस पृष्ठ को बदलने से अन्य सदस्यों को प्रदर्शित इंटरफ़ेस की शक्लोसूरत में बदलाव आएगा। अनुवादों के लिए कृपया [//translatewiki.net/wiki/Main_Page?setlang=hi translatewiki.net] का प्रयोग करें, यह मीडियाविकि की क्षेत्रीयकरण परियोजना है।",
'sqlhidden' => '(छुपाई हुई SQL पृच्छा)',
'cascadeprotected' => 'यह पृष्ठ सुरक्षित हैं, क्योंकी यह निम्नलिखित {{PLURAL:$1|पृष्ठ|पृष्ठों}} की सुरक्षा-सीढ़ी में समाविष्ट है:
$2',
'namespaceprotected' => "आपको '''$1''' नामस्थान में समाविष्ट पृष्ठों को बदलने की अनुमति नहीं है।",
'customcssprotected' => 'आपको इस CSS पृष्ठ को संपादित करने की अनुमति नहीं है, क्योंकि इसमें अन्य सदस्य की व्यक्तिगत सेटिंग्स शामिल हैं।',
'customjsprotected' => 'आपको इस जावास्क्रिप्ट पृष्ठ को संपादित करने की अनुमति नहीं है, क्योंकि इसमें अन्य सदस्य की व्यक्तिगत सेटिंग्स शामिल हैं।',
'ns-specialprotected' => 'विशेष पृष्ठ सम्पादित नहीं किये जा सकते।',
'titleprotected' => 'सदस्य [[User:$1|$1]] ने इस शीर्षक का पृष्ठ बनाने से सुरक्षित किया हुआ है।
इसके लिये निम्न कारण दिया गया है: "\'\'$2\'\'"',
'invalidtitle-knownnamespace' => '"$2" नामस्थान और "$3" नाम वाला गलत शीर्षक',
'invalidtitle-unknownnamespace' => 'अज्ञात नामस्थान संख्या $1 और नाम "$2" वाला गलत शीर्षक',
'exception-nologin' => 'लॉग इन नहीं किया है',

# Virus scanner
'virus-badscanner' => "गलत जमाव: अज्ञात वायरस जाँचक: ''$1''",
'virus-scanfailed' => 'जाँच विफल (कूट $1)',
'virus-unknownscanner' => 'अज्ञात ऐंटीवायरस:',

# Login and logout pages
'logouttext' => "'''अब आपका सत्रांत हो चुका है।'''

आप बेनामी हो के {{SITENAME}} का प्रयोग जारी रख सकते हैं, या उसी या किसी और सदस्य के तौर पर [[Special:UserLogin|फिर से सत्रारंभ]] कर सकते हैं।
ध्यान दें कि जब तक आप अपनी ब्राउज़र कैशे खाली नहीं करते हैं, कुछ पृष्ठ अब भी ऐसे दिख सकते हैं जैसे कि आपका सत्र अभी भी चल रहा हो।",
'welcomecreation' => '== आपका स्वागत है, $1 ! ==
आपका खाता बनाया जा चुका है। अपनी [[Special:Preferences|{{SITENAME}} वरीयताएँ]] परिवर्तित करना न भूलिएगा।',
'yourname' => 'सदस्यनाम:',
'yourpassword' => 'कूटशब्द:',
'yourpasswordagain' => 'कूटशब्द दुबारा लिखें:',
'remembermypassword' => 'इस ब्राउज़र पर मेरा लॉगिन याद रखें (अधिकतम $1 {{PLURAL:$1|दिन|दिनों}} के लिए)',
'securelogin-stick-https' => 'प्रवेश के बाद HTTPS से जुड़े रहें',
'yourdomainname' => 'आपका डोमेन:',
'password-change-forbidden' => 'आप इस विकि पर कूटशब्द नहीं बदल सकते हैं।',
'externaldberror' => 'या तो प्रमाणिकरण डाटाबेस में त्रुटि हुई है या फिर आपको अपना बाह्य खाता अपडेट करने की अनुमति नहीं है।',
'login' => 'लॉग इन',
'nav-login-createaccount' => 'सत्रारंभ / खाता खोलें',
'loginprompt' => '{{SITENAME}} पर लॉग इन करने के लिए अपने ब्राउज़र पर कुकीज़ (cookies) सक्षम करें।',
'userlogin' => 'सत्रारंभ / खाता खोलें',
'userloginnocreate' => 'लॉग इन',
'logout' => 'सत्रांत',
'userlogout' => 'सत्रांत',
'notloggedin' => 'लॉग इन नहीं किया है',
'nologin' => "क्या आपने सदस्यता नहीं ली है? '''$1'''।",
'nologinlink' => 'नया खाता बनाएँ',
'createaccount' => 'खाता बनाएँ',
'gotaccount' => "पहले से आपका खाता है? '''$1''' करें।",
'gotaccountlink' => 'लॉग इन',
'userlogin-resetlink' => 'अपनी प्रवेश जानकारी भूल गए हैं?',
'createaccountmail' => 'ई-मेल द्वारा',
'createaccountreason' => 'कारण:',
'badretype' => 'आपने जो कूटशब्द दिये हैं वे एक दूसरे से नहीं मिलते। फिर से लिखें।',
'userexists' => 'आपका दिया सदस्यनाम पहले से प्रयोग में है।
कृपया कोई अन्य सदस्यनाम चुनें।',
'loginerror' => 'लॉग इन त्रुटि',
'createaccounterror' => 'खाता नहीं बन पाया: $1',
'nocookiesnew' => 'आपका खाता खोल दिया गया है, पर आप लॉग इन नहीं हुए हैं।
{{SITENAME}} पर लॉग इन करने के लिये कुकीज़ का प्रयोग होता है।
आपने कुकीज़ अक्षम कर रखी हैं।
कृपया अपने ब्राउज़र में कुकीज़ सक्षम करें, और फिर अपने सदस्यनाम एवं कूटशब्द से लॉग इन करें।',
'nocookieslogin' => '{{SITENAME}} पर लॉग इन करने के लिये कुकीज़ का प्रयोग होता है।
आपने कुकीज़ अक्षम कर रखी हैं।
कृपया अपने ब्राउज़र में कुकीज़ सक्षम करें और फिर पुनः कोशिश करें।',
'nocookiesfornew' => 'स्रोत की पुष्टि ना हो पाने के कारण यह खाता निर्मित नहीं किया गया। 
सुनिश्चित करें कि आपने कुकीज़ सक्षम की हैं, पृष्ठ को पुनः लोड करें और पुनः प्रयास करें।',
'noname' => 'आपने वैध सदस्यनाम नहीं दिया है।',
'loginsuccesstitle' => 'लॉग इन हो गया है',
'loginsuccess' => "'''आप {{SITENAME}} में \"\$1\" सदस्यनाम से लॉग इन हो {{GENDER:\$1|चुके|चुकी|चुके}} हैं।'''",
'nosuchuser' => '"$1" नाम का कोई सदस्य नहीं है।
सदस्यनाम में लघु और दीर्घ अक्षरों से फ़र्क पड़ता है।
अपनी वर्तनी जाँचें, या [[Special:UserLogin/signup|नया खाता खोलें]]।',
'nosuchusershort' => '"$1" नाम का कोई सदस्य नहीं है।
कृपया अपनी दी हुई वर्तनी जाँचें।',
'nouserspecified' => 'सदस्यनाम देना अनिवार्य है।',
'login-userblocked' => 'यह सदस्य प्रतिबन्धित है। सत्रारम्भ की अनुमति नहीं है।',
'wrongpassword' => 'आपने जो कूटशब्द लिखा है वह गलत है। कृपया पुनः प्रयास करें।',
'wrongpasswordempty' => 'कूटशब्द खाली है।
पुनः यत्न करें।',
'passwordtooshort' => 'आपका कूटशब्द कम से कम {{PLURAL:$1|1 अक्षर|$1 अक्षरों}} का होना चाहिये।',
'password-name-match' => 'आपका कूटशब्द आपके सदस्यनाम से भिन्न होना चाहिए।',
'password-login-forbidden' => 'इस सदस्यनाम और कूटशब्द का उपयोग वर्जित है।',
'mailmypassword' => 'ई-मेल द्वारा नया कूटशब्द भेजें',
'passwordremindertitle' => '{{SITENAME}} के लिये नया अस्थायी कूटशब्द',
'passwordremindertext' => 'किसी ने (शायद आपने ही, $1 आइ॰पी पते से) {{SITENAME}} ($4) पर इस्तेमाल के लिये नया कूटशब्द मँगाया है। सदस्य "$2" के लिए एक अस्थायी कूटशब्द बना दिया गया है, और यह अभी "$3" है। यदि यह आपकी ही मंशा थी, तो अब आपको सत्रारंभ करके एक नया कूटशब्द चुनना होगा।
आपके अस्थायी कूटशब्द की अवधि {{PLURAL:$5|एक दिन|$5 दिनों}} में समाप्त हो जाएगी।

यदि यह अनुरोध किसी और ने किया था, या आप अपना पुराना कूटशब्द अब नहीं बदलना चाहते क्योंकि आपको अपना पुराना कूटशब्द याद आ गया है, तो आप इस संदेश को नज़रंदाज़ कर सकते हैं, और अपने पुराने कूटशब्द का पहले की तरह इस्तेमाल करते रह सकते हैं।',
'noemail' => '"$1" सदस्य के लिये कोई भी ई-मेल पता दर्ज नहीं किया गया है।',
'noemailcreate' => 'आपको वैध ई-मेल पता देने होगा।',
'passwordsent' => '"$1" के ई-मेल पते पर एक नया कूटशब्द भेज दिया गया है।
ई-मेल पाने बाद कृपया दुबारा लॉग इन करें।',
'blocked-mailpassword' => 'आपके आइ॰पी पते को सम्पादन करने से अवरुद्ध कर दिया गया है, और गलत इस्तेमाल रोकने के लिये कूटशब्द पुनः प्राप्ति की सुविधा इस आइ॰पी पर बंद कर दी गई है।',
'eauthentsent' => 'दर्ज किये हुए ई-मेल पते पर एक सत्यापन ई-मेल भेजा गया है।
आपको उस ई-मेल में दिये हुए निर्देशों के अनुसार क्रियाएँ कर के ई-मेल पते का सत्यापन करना होगा, उसके पश्चात ही यहाँ से कोई दूसरा ई-मेल भेजा जाएगा।',
'throttled-mailpassword' => 'पिछले {{PLURAL:$1|घंटे|$1 घंटों}} के दरमियान एक कूटशब्द स्मरण-पत्र भेजा जा चुका है।
दुरुपयोग से बचाव के लिए हर {{PLURAL:$1|घंटे|$1 घंटों}} में एक कूटशब्द स्मरण-पत्र ही भेजा जाता है।',
'mailerror' => 'ई-मेल भेजने में त्रुटि: $1',
'acct_creation_throttle_hit' => 'आपके आइ॰पी पते से आए आगंतुक पिछले चौबीस घंटों में इस विकि पर {{PLURAL:$1|एक खाता|$1 खाते}} बना चुके हैं, इस समयावधि में यही अधिकतम सीमा है।
अतः इस समय इस आइ॰पी पते का प्रयोग करने वाले आगंतुक और खाते नहीं खोल सकेंगे।',
'emailauthenticated' => 'आपके ई-मेल पते की दिनांक $2 को $3 बजे पुष्टि हुई थी।',
'emailnotauthenticated' => 'आपके ई-मेल पते की पुष्टि नहीं हुई है।
नीचे दी किसी भी सुविधा के लिये आपको ई-मेल नहीं भेजा जाएगा।',
'noemailprefs' => 'इन सुविधाओं का प्रयोग करने के लिये अपनी वरीयताओं में ई-मेल पता दें।',
'emailconfirmlink' => 'अपना ई-मेल पता प्रमाणित करें',
'invalidemailaddress' => 'ई-मेल पता नहीं माना जा सकता क्योंकि ये किसी अवैध स्वरूप में है।
कृपया एक सही तरीके से स्वरूपित ई-मेल पता दें अथवा उस कोष्ठक को रिक्त ही रहने दें।',
'cannotchangeemail' => 'इस विकी पर सदस्य खाते का ई-मेल पता नहीं बदला जा सकता।',
'emaildisabled' => 'यह साइट ई-मेल नहीं भेज सकती।',
'accountcreated' => 'खाता निर्मित',
'accountcreatedtext' => '$1 के लिये खाता निर्मित कर दिया गया है।',
'createaccount-title' => '{{SITENAME}} के लिये खाता बनाएँ',
'createaccount-text' => 'आपके ई-मेल पते के लिये किसी ने {{SITENAME}} ($4) पर "$2" सदस्य नाम से "$3" कूटशब्द (पासवर्ड) सहित खाता खोला है।
आपको लॉग इन कर के अपना कूटशब्द (पासवर्ड) तुरंत बदल लेना चाहिये।

यदि यह खाता गलती से खोला गया है, तो आप इस मेसेज को नज़रंदाज़ कर सकते हैं।',
'usernamehasherror' => 'सदस्यनाम में हैश कैरैक्टर वर्जित हैं।',
'login-throttled' => 'आपने हाल ही में कई बार लॉग इन करने के प्रयास किये हैं।
पुनः प्रयास करने से पहले थोड़ी प्रतीक्षा करें।',
'login-abort-generic' => 'आपका सत्रारम्भ असफल रहा - निष्फलित',
'loginlanguagelabel' => 'भाषा: $1',
'suspicious-userlogout' => 'अपका लॉग आउट करने का अनुरोध अस्वीकृत कर दिया गया है क्योंकि ऐसा प्रतीत होता है कि यह किसी खराब ब्राउज़र या कैश करने वाली प्रॉक्सी द्वारा भेजा गया था।',

# E-mail sending
'php-mail-error-unknown' => 'PHP के mail() फ़ंक्शन में अज्ञात त्रुटि हुई।',
'user-mail-no-addy' => 'ई-मेल पते के बिना ई-मेल भेजने की कोशिश की।',

# Change password dialog
'resetpass' => 'कूटशब्द बदलें',
'resetpass_announce' => 'आप ई-मेल से प्राप्त अस्थायी कोड से लॉग इन हुए हैं।
लॉग इन को पूरा करने के लिये आपको यहाँ एक नया कूटशब्द देना होगा:',
'resetpass_text' => '<!-- पाठ यहाँ लिखें -->',
'resetpass_header' => 'खाते का कूटशब्द बदलें',
'oldpassword' => 'पुराना कूटशब्द:',
'newpassword' => 'नया कूटशब्द:',
'retypenew' => 'नया कूटशब्द पुन: लिखें:',
'resetpass_submit' => 'कूटशब्द बनाएँ और लॉग इन करें',
'resetpass_success' => 'आपका कूटशब्द बदल दिया गया है!
अब आपको लॉग इन कर रहे हैं...',
'resetpass_forbidden' => 'कूटशब्द बदले नहीं जा सकते',
'resetpass-no-info' => 'इस पृष्ठ का सीधे प्रयोग करने के लिए आपको लॉग इन करना होगा।',
'resetpass-submit-loggedin' => 'कूटशब्द बदलें',
'resetpass-submit-cancel' => 'रद्द करें',
'resetpass-wrong-oldpass' => 'अवैध अस्थायी या वर्तमान कूटशब्द।
संभव है कि या तो आपने पहले ही सफलतापूर्वक अपना कूटशब्द बदल लिया हो, या आपने एक नए अस्थायी कूटशब्द का अनुरोध किया हो।',
'resetpass-temp-password' => 'अस्थायी कूटशब्द:',

# Special:PasswordReset
'passwordreset' => 'कूटशब्द रीसेट',
'passwordreset-text' => 'अपने खाते के विवरण का एक ई-मेल अनुस्मारक प्राप्त करने के लिए इस फ़ॉर्म को पूरा करें।',
'passwordreset-legend' => 'कूटशब्द रीसेट करें',
'passwordreset-disabled' => 'कूटशब्द रीसेट करना इस विकी पर अक्षम है।',
'passwordreset-pretext' => '{{PLURAL:$1||नीचे पूछे गए डेटा में से एक लिखें}}',
'passwordreset-username' => 'सदस्यनाम:',
'passwordreset-domain' => 'डोमेन:',
'passwordreset-capture' => 'परिणामस्वरूप बना ई-मेल देखें?',
'passwordreset-capture-help' => 'अगर आप इस चेकबॉक्स को टिक करते हैं तो ई-मेल (अस्थायी कूटशब्द के साथ) आप को दिखया जाएगा और सदस्य को भेजा भी जयेगा।',
'passwordreset-email' => 'ई-मेल पता:',
'passwordreset-emailtitle' => '{{SITENAME}} पर खाते का विवरण',
'passwordreset-emailtext-ip' => 'किसी ने (शायद आपने ही, $1 आइ॰पी पते से) {{SITENAME}} ($4) पर अपने {{PLURAL:$3|खाते|खातों}} के विवरण के स्मरणपत्र का अनुरोध किया है। इस ई-मेल पते से निम्न {{PLURAL:$3|खाता जुड़ा है|खाते जुड़े हैं}}:

$2

{{PLURAL:$3|यह|ये}} अस्थायी कूटशब्द {{PLURAL:$5|एक दिन|$5 दिनों}} के बाद काम नहीं करेंगे। आपको लॉग इन करके एक नया कूटशब्द अभी चुन लेना चाहिए। यदि यह अनुरोध किसी और ने किया है, या फिर आपको अपना मूल कूटशब्द याद आ गया है, और आप {{PLURAL:$3|अपना|अपने}} कूटशब्द नहीं बदलना चाहते, आप इस संदेश को अनदेखा कर के अपने पुराने कूटशब्द का प्रयोग जारी रख सकते हैं।',
'passwordreset-emailtext-user' => '{{SITENAME}} ($4) पर सदस्य $1 ने आपके {{PLURAL:$3|खाते|खातों}} के विवरण के स्मरणपत्र का अनुरोध किया है। इस ई-मेल पते से निम्न {{PLURAL:$3|खाता जुड़ा है|खाते जुड़े हैं}}:

$2

{{PLURAL:$3|यह|ये}} अस्थायी कूटशब्द {{PLURAL:$5|एक दिन|$5 दिनों}} के बाद काम नहीं करेंगे। आपको लॉग इन करके एक नया कूटशब्द अभी चुन लेना चाहिए। यदि यह अनुरोध किसी और ने किया है, या फिर आपको अपना मूल कूटशब्द याद आ गया है, और आप {{PLURAL:$3|अपना|अपने}} कूटशब्द नहीं बदलना चाहते, आप इस संदेश को अनदेखा कर के अपने पुराने कूटशब्द का प्रयोग जारी रख सकते हैं।',
'passwordreset-emailelement' => 'सदस्यनाम: $1
अस्थायी कूटशब्द: $2',
'passwordreset-emailsent' => 'एक अनुस्मारक ई-मेल भेज दिया गया है।',
'passwordreset-emailsent-capture' => 'नीचे दिखाया गया अनुस्मारक ई-मेल भेज दिया गया है।',
'passwordreset-emailerror-capture' => 'नीचे दृष्टित अनुस्मारक ई-मेल उत्पन्न किया गया था, परंतु उसे $1 सदस्य को भेजना असफल रहा।',

# Special:ChangeEmail
'changeemail' => 'ई-मेल पता परिवर्तित करें',
'changeemail-header' => 'खाते के ई-मेल पता परिवर्तित करें',
'changeemail-text' => 'अपना ई-मेल पता परिवर्तित करने के लिए इस फ़ॉर्म को पूरा करें। इस बदलाव की पुष्टि करने के लिये आपको अपना कूटशब्द पुनः लिखना पड़ेगा।',
'changeemail-no-info' => 'इस पृष्ठ का सीधे प्रयोग करने के लिए आपको लॉग इन करना होगा।',
'changeemail-oldemail' => 'वर्तमान ई-मेल पता:',
'changeemail-newemail' => 'नया ई-मेल पता:',
'changeemail-none' => '(कोई नहीं)',
'changeemail-submit' => 'ई-मेल बदलें',
'changeemail-cancel' => 'रद्द करें',

# Edit page toolbar
'bold_sample' => 'मोटा पाठ',
'bold_tip' => 'बोल्ड पाठ',
'italic_sample' => 'तिरछा पाठ',
'italic_tip' => 'इटैलिक पाठ',
'link_sample' => 'कड़ी शीर्षक',
'link_tip' => 'आंतरिक कड़ी',
'extlink_sample' => 'http://www.example.com कड़ी शीर्षक',
'extlink_tip' => 'बाहरी कड़ी (उपसर्ग http:// अवश्य लगाएँ)',
'headline_sample' => 'शीर्षक',
'headline_tip' => 'द्वितीय-स्तर शीर्षक',
'nowiki_sample' => 'अप्रारूपित पाठ यहाँ डालें',
'nowiki_tip' => 'विकि प्रारूपण नज़रंदाज़ करें',
'image_sample' => 'उदाहरण.jpg',
'image_tip' => 'एम्बेड की हुई फ़ाइल',
'media_sample' => 'उदाहरण.ogg',
'media_tip' => 'संचिका की कड़ी',
'sig_tip' => 'आपका हस्ताक्षर व समय',
'hr_tip' => 'हॉरिज़ौंटल लाइन (कम इस्तेमाल करें)',

# Edit pages
'summary' => 'सारांश:',
'subject' => 'विषय/शीर्षक:',
'minoredit' => 'यह एक छोटा बदलाव है',
'watchthis' => 'इस पृष्ठ को ध्यानसूची में डालें',
'savearticle' => 'पृष्ठ सहेजें',
'preview' => 'झलक',
'showpreview' => 'झलक दिखाएँ',
'showlivepreview' => 'सीधी झलक',
'showdiff' => 'बदलाव दिखाएँ',
'anoneditwarning' => "'''सावधान:''' आपने सत्रारंभ नहीं किया है। इस पृष्ठ के संपादन इतिहास में आपका आइ॰पी पता अंकित किया जाएगा।",
'anonpreviewwarning' => "''आप लॉग्ड इन नहीं हैं। पृष्ठ सहेजने पर आपका आइ॰पी पता इस पृष्ठ के इतिहास में दर्ज किया जायेगा।''",
'missingsummary' => "'''अनुस्मारक:''' आपने संपादन सारांश नहीं दिया है।
अगर आप दुबारा \"{{int:savearticle}}\" पर क्लिक करते हैं तो आपका संपादन बिना सारांश के संजोया जायेगा।",
'missingcommenttext' => 'कृपया नीचे टिप्पणी दें।',
'missingcommentheader' => "'''अनुस्मारक:''' आपने इस टिप्पणी का कोई शीर्षक नहीं दिया है।
अगर आप \"{{int:savearticle}}\" पर दोबारा क्लिक करते हैं तो आपके बदलाव बिना शीर्षक के संजोये जायेंगे।",
'summary-preview' => 'सारांश की झलक:',
'subject-preview' => 'विषय/शीर्षक की झलक:',
'blockedtitle' => 'सदस्य अवरुद्ध है',
'blockedtext' => "'''आपका सदस्यनाम अथवा आइ॰पी पता अवरोधित कर दिया गया हैं ।'''

अवरोध $1 द्वारा किया गया था।
अवरोध का कारण है ''$2''

* अवरोध का आरंभ: $8
* अवरोध की समाप्ति: $6
* अवरोधित इकाई: $7

इस अवरोध के बारे में चर्चा करने के लिए आप $1 या किसी अन्य [[{{MediaWiki:Grouppage-sysop}}|प्रबन्धक]] से संपर्क कर सकते हैं।
अगर आपने [[Special:Preferences|अपनी वरीयताओं]] में वैध ई-मेल पता प्रविष्ट किया है तो ही आप 'इस प्रयोक्ता को ई-मेल भेजें' वाली सुविधा का इस्तेमाल कर सकते हैं और आपको इसका इस्तेमाल करने से नहीं रोका गया है।
आपका मौजूदा आइ॰पी पता $3 है और अवरोध क्रमांक #$5 है।
अपने किसी भी प्रश्न में कृपया यह सभी जानकारी भी शामिल करें।",
'autoblockedtext' => 'एक और सदस्य आपके ही आइ॰पी का प्रयोग कर रहे थे और उन्हें $1 द्वारा अवरोधित कर दिया गया था। फलस्वरूप आपका आइ॰पी पता भी स्वचालित रूप से अवरोधित हो गया है।
अवरोध करने का कारण है:

:\'\'$2\'\'

* अवरोध प्रारंभ: $8
* अवरोध समाप्ति: $6
* अवरोधित सदस्य: $7

अवरोध की चर्चा करने के लिए आप $1 या किसी अन्य [[{{MediaWiki:Grouppage-sysop}}|प्रबंधक]] से संपर्क कर सकते हैं।

कृपया ध्यान दें कि यदि आपक "इस सदस्य को ई-मेल भेजें" वाली सुविधा का प्रयोग करना चाहते हैं तो आपकी [[Special:Preferences|वरीयताओं]] में वैध ई-मेल पता होना चाहिए और इसका प्रयोग आपके लिए अवरोधित नहीं होना चाहिए।

आपका मौजूदा आइ॰पी पता $3 है और अवरोध क्रमांक #$5 है।
अपने किसी भी प्रश्न में कृपया यह सभी जानकारी भी शामिल करें।',
'blockednoreason' => 'कोई कारण नहीं दिया है',
'whitelistedittext' => 'पृष्ठ संपादित करने के लिये आपको $1 करना होगा।',
'confirmedittext' => 'संपादन करने से पहले अपना ई-मेल पता प्रमाणित करना आवश्यक है।
कृपया अपनी [[Special:Preferences|सदस्य वरीयताओं]] में जाकर अपना ई-मेल पता दें और उसे प्रमाणित करें।',
'nosuchsectiontitle' => 'ऐसा कोई अनुभाग शीर्षक नहीं है',
'nosuchsectiontext' => 'आप ऐसे अनुभाग का सम्पादन करने का प्रयत्न कर रहे हैं जो अस्तित्व में नहीं है।
संभव है कि जब आप पृष्ठ पढ़ रहे थे तब उसे अपनी जगह से हिलाया गया हो या हटा दिया गया हो।',
'loginreqtitle' => 'लॉग इन आवश्यक है',
'loginreqlink' => 'लॉग इन',
'loginreqpagetext' => 'अन्य पृष्ठ देखने के लिये आपको $1 करना आवश्यक है।',
'accmailtitle' => 'कूटशब्द भेज दिया गया है।',
'accmailtext' => "[[User talk:$1|$1]] के लिए एक यंत्र जनित कूटशब्द $2 को भेज दिया गया है।

सत्रारंभ करने के बाद नए खाते का कूटशब्द '''[[Special:ChangePassword|कूटशब्द बदलें]]'' वाले पृष्ठ पर बदला जा सकता है।",
'newarticle' => '(नया)',
'newarticletext' => "आप ऐसे पृष्ठ पर आए हैं जो अभी तक बनाया नहीं गया है।
पृष्ठ बनाने के लिये नीचे के बौक्स में पाठ लिखें। अधिक जानकारी के लिये [[{{MediaWiki:Helppage}}|सहायता पृष्ठ]] देखें।
अगर आप यहाँ पर गलती से आए हैं तो अपने ब्राउज़र के बैक ('''back''') बटन पर क्लिक करें।",
'anontalkpagetext' => "----''यह वार्ता पृष्ठ उन बेनामी सदस्यों के लिये है जिन्होंने या तो खाता नहीं खोला है या खाते का प्रयोग नहीं कर रहे हैं।
इसलिये उनकी पहचान के लिये हमें उनका आइ॰पी पता प्रयोग करना पड़ता है।
आइ॰पी पता कई सदस्यों के लिए साझा हो सकता है।
यदि आप एक बेनामी सदस्य हैं और आपको लगता है कि आपके बारे में अप्रासंगिक टीका टिप्पणी की गई है तो कृपया [[Special:UserLogin/signup|सदस्यता लें]] या [[Special:UserLogin|सत्रारंभ करें]] ताकि अन्य बेनामी सदस्यों में से आपको अलग से पहचाना जा सके।''",
'noarticletext' => 'फ़िलहाल इस पृष्ठ पर कोई सामग्री नहीं है।
आप अन्य पृष्ठों में [[Special:Search/{{PAGENAME}}|इस शीर्षक की खोज]] कर सकते हैं,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} सम्बन्धित लॉग खोज सकते हैं],
या इस पृष्ठ को [{{fullurl:{{FULLPAGENAME}}|action=edit}} सम्पादित] कर सकते हैं</span>।',
'noarticletext-nopermission' => 'फ़िलहाल इस पृष्ठ पर कोई सामग्री नहीं है।
आप अन्य पृष्ठों में [[Special:Search/{{PAGENAME}}|इस शीर्षक की खोज]] कर सकते हैं,
या <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} संबंधित लॉग खोज सकते हैं]</span>, परन्तु आपको यह पृष्ठ बनाने की अनुमति नहीं है।',
'userpage-userdoesnotexist' => 'सदस्य खाता "$1" पंजीकृत नहीं है।
कृपया जाँच लें कि आप यह पृष्ठ संपादित अथवा निर्मित करना चाहते हैं या नहीं।',
'userpage-userdoesnotexist-view' => 'सदस्य "$1" पंजीकृत नहीं है।',
'blocked-notice-logextract' => 'यह सदस्य फ़िलहाल अवरोधित है।
सदंर्भ के लिए ताज़ातरीन अवरोध लॉग प्रविष्टि नीचे दी है:',
'clearyourcache' => "'''ध्यान दें:'''  संजोने के बाद बदलाव देखने के लिए आपको अपने ब्राउज़र की कैश खाली करनी पड़ सकती है।
* '''फ़ायरफ़ॉक्स / सफ़ारी:''' ''Reload'' (रीलोड) दबाते समय ''Shift'' (शिफ़्ट) दबा के रखें, या फिर ''Ctrl-F5'' (कंट्रोल-F5) या ''Ctrl-R'' (कंट्रोल-R) दबाएँ (मैक पर ''⌘-R'')
* '''गूगल क्रोम:''' ''Ctrl-Shift-R'' (कंट्रोल-शिफ़्ट-R) दबाएँ (मैक पर ''⌘-Shift-R'')
* '''इन्टर्नेट एक्सप्लोरर:''' ''Ctrl'' (कंट्रोल) दबाकर ''Refresh'' (रिफ़्रेश) करें या ''Ctrl-F5'' (कंट्रोल-F5) दबाएँ
* '''ऑपेरा:''' ''Tools → Preferences'' (उपकरण → वरीयताएँ) में कैश साफ़ करें",
'usercssyoucanpreview' => "'''टिप''': संजोने से पहले अपनी नई सी॰एस॰एस को जाँचने के लिये \"{{int:showpreview}}\" बटन का प्रयोग करें।",
'userjsyoucanpreview' => "'''टिप''': संजोने से पहले अपनी नई जावास्क्रिप्ट को जाँचने के लिये \"{{int:showpreview}}\" बटन का प्रयोग करें।",
'usercsspreview' => "'''ध्यान दें कि आप अपनी सी॰एस॰एस की झलक देख रहे हैं।'''
'''यह अभी तक संजोई नहीं गई है!'''",
'userjspreview' => "'''ध्यान दें कि आप अपनी जावास्क्रिप्ट की झलक देख रहे हैं।'''
'''यह अभी तक संजोई नहीं गई है!'''",
'sitecsspreview' => "''''ध्यान दें कि आप इस सी॰एस॰एस की झलक देख रहे हैं।'''
'''यह अभी तक संजोई नहीं गई है!'''",
'sitejspreview' => "'''ध्यान दें कि आप इस जावास्क्रिप्ट कोड की झलक देख रहे हैं।'''
'''यह अभी तक संजोया नहीं गया है!'''",
'userinvalidcssjstitle' => "'''चेतावनी:''' \"\$1\" नाम की कोई त्वचा नहीं है।
बदले हुए .css और .js पृष्ठों के शीर्षक नीचे स्तर की लिपि (lowercase) का प्रयोग करते हैं। उदाहरण: {{ns:user}}:Foo/vector.css न की {{ns:user}}:Foo/Vector.css",
'updated' => '(अद्यतनीत)',
'note' => "'''सूचना:'''",
'previewnote' => "'''याद रखें, यह केवल एक झलक है।'''
आपके बदलाव अभी तक संजोये नहीं गए हैं!",
'continue-editing' => 'संपादन क्षेत्र को जाएँ',
'previewconflict' => 'यह झलक ऊपरी पाठ सम्पादन क्षेत्र में हुए बदलाव दिखाती है, और यदि आप अभी संजोते हैं तो यही पाठ संजोया जाएगा।',
'session_fail_preview' => "'''क्षमा करें! सेशन डाटा के नष्ट होने के कारण आपके बदलाव संजोये नहीं जा सके।'''
कृपया पुन: यत्न करें।
अगर इसके बाद भी ऐसा ही होता है तो कृपया [[Special:UserLogout|लॉग आउट]] कर के पुनः लॉग इन करें।",
'session_fail_preview_html' => "'''क्षमा करें! सेशन डाटा के नष्ट होने के कारण आपके बदलाव संजोये नहीं जा सके।'''

''चूँकि {{SITENAME}} पर raw HTML सक्षम है, जावास्क्रिप्ट हमलों से बचाव के लिये झलक नहीं दिखाई गई है।''

'''अगर यह आपका वैध संपादन यत्न था, तो कृपया पुनः यत्न करें।'''
अगर इसके बाद भी ऐसा ही होता है तो कृपया [[Special:UserLogout|लॉग आउट]] कर के पुनः लॉग इन करें।",
'token_suffix_mismatch' => "'''आपके द्वारा किये गये बदलाव रद्द कर दिये गये हैं क्योंकि आपके क्लायंट ने आपके संपादन टोकन में दिये हुए विरामचिन्हों में बदलाव किये हैं।'''
लेख के पाठ में खराबी ना आये इसलिये आपके बदलाव रद्द कर दिये गये हैं।
ऐसा तब भी हो सकता है यदि आप कोई खराब वेब-आधारित अनामक प्रौक्सी प्रयोग कर रहे हों।",
'edit_form_incomplete' => "'''सम्पादन फ़ॉर्म के कुछ भाग सर्वर तक नहीं पहुँच पाए; जाँच लें कि आपके द्वारा किये बदलाव अक्षुण्ण हैं, और सहेजने का पुनः यत्न करें।'''",
'editing' => '$1 सम्पादन',
'creating' => '$1 बनाएँ',
'editingsection' => '$1 सम्पादन (अनुभाग)',
'editingcomment' => '$1 सम्पादन (नया अनुभाग)',
'editconflict' => 'संपादन अंतर्विरोध: $1',
'explainconflict' => "आपके द्वारा सम्पादन शुरू करने के बाद से किसी अन्य व्यक्ति ने इस पृष्ठ में बदलाव किये हैं।
ऊपरी पाठ सम्पादन क्षेत्र में वर्तमान पाठ दर्शाया गया है।
निछले क्षेत्र में आपके बदलाव दर्शाये गये हैं।
आपको अपने बदलाव वर्तमान पाठ में स्वयं एकत्रित करने होंगे।
आपके \"{{int:savearticle}}\" पर क्लिक करने पर '''केवल''' ऊपरी क्षेत्र में दिखने वाला पाठ संजोया जायेगा।",
'yourtext' => 'आपका पाठ',
'storedversion' => 'संजोया हुआ अवतरण',
'nonunicodebrowser' => "'''सावधान: आपका ब्राउज़र युनिकोड को स्वीकार नहीं करता है।'''
आपके द्वारा सुयोग्य संपादन होने के लिये: ग़ैर-ASCII कैरैक्टर षट्‍पदी कोड (hexadecimal) में दर्शाए जायेंगे।",
'editingold' => "'''चेतावनी: आप इस पृष्ठ का कालातीत अवतरण संपादित कर रहे हैं।'''
अगर आप इसे संजोते हैं, तो इस अवतरण के बाद हुए सभी बदलाव नष्ट हो जायेंगे।",
'yourdiff' => 'अंतर',
'copyrightwarning' => "कृपया ध्यान दें कि {{SITENAME}} को किये गये सभी योगदान $2 की शर्तों के तहत होंगे (अधिक जानकारी के लिये $1 देखें)।
यदि आप अपने योगदान को लगातार बदलते और पुनः वितरित होते नहीं देख सकते हैं तो यहाँ योगदान न करें।<br />
आप यह भी प्रमाणित कर रहे हैं कि यह आपने स्वयं लिखा है अथवा सार्वजनिक क्षेत्र या किसी समान मुक्त स्रोत से प्रतिलिपित किया है।
'''कॉपीराइट सुरक्षित कार्यों को बिना अनुमति के यहाँ न डालें!'''",
'copyrightwarning2' => "{{SITENAME}} पर किया कोई भी योगदान अन्य सदस्यों द्वारा बदला जा सकता है और हटाया भी जा सकता है।
अगर आपको अपने लिखे हुए पाठ में संपादन होना नामंजूर है तो यहाँ पर न लिखें।<br />
आप हमें यह भी वचन देतें हैं कि यह आपने स्वयं लिखा है अथवा सार्वजनिक क्षेत्र या किसी समान मुक्त स्रोत से प्रतिलिपित किया है (अधिक जानकारी के लिये $1 देखें)।
'''कॉपीराइट सुरक्षित कार्यों को बिना अनुमति के यहाँ न डालें!'''",
'longpageerror' => "'''त्रुटि: आपका दिया हुआ पाठ {{PLURAL:|$1 किलोबाइट|$1 किलोबाइट}} लंबा है, जो {{PLURAL:|$2 किलोबाइट|$2 किलोबाइट}} की सीमा से बाहर है।
इसे संजोया नहीं जा सकता।'''",
'readonlywarning' => "'''सावधान: डाटाबेस को रख-रखाव के लिये बंद कर दिया गया है, इसलिये अभी आपके बदलाव संजोए नहीं जा सकते।
अगर आप चाहतें हैं तो इस सामग्री की नकलचिप्पी कर के किसी संचिका में बाद के लिए डाल के रख सकते हैं।'''

बंद करने वाले प्रबंधक ने यह बंद करने का यह कारण दिया है: $1",
'protectedpagewarning' => "'''चेतावनी: इस पृष्ठ को सुरक्षित कर दिया गया है और इसे केवल प्रबंधक ही सम्पादित कर सकते हैं।'''
नवीनतम लॉग प्रविष्टि संदर्भ के लिये नीचे दी है:",
'semiprotectedpagewarning' => "'''सूचना:''' यह पृष्ठ सुरक्षित कर दिया गया है और इसे केवल पंजीकृत सदस्य ही सम्पादित कर सकते हैं।
नवीनतम लॉग प्रविष्टि संदर्भ के लिये नीचे दी है:",
'cascadeprotectedwarning' => "'''सावधान:''' यह पृष्ठ निम्नलिखित सुरक्षा-सीढ़ी {{PLURAL:$1|पृष्ठ से|पन्नों से}} जुड़ा हुआ होने के कारण सुरक्षित है, और केवल प्रबंधक ही इसमें बदलाव कर सकते हैं:",
'titleprotectedwarning' => "'''चेतावनी: यह पृष्ठ सुरक्षित है और इसे बनाने के लिये [[Special:ListGroupRights|विशेष अधिकारों]] की आवश्यकता है।'''
नवीनतम लॉग प्रविष्टि संदर्भ के लिये नीचे दी है:",
'templatesused' => 'इस पृष्ठ पर प्रयुक्त {{PLURAL:$1|साँचा|साँचे}}:',
'templatesusedpreview' => 'इस झलक में प्रयुक्त {{PLURAL:$1|साँचा|साँचे}}:',
'templatesusedsection' => 'इस अनुभाग में प्रयुक्त {{PLURAL:$1|साँचा|साँचे}}:',
'template-protected' => '(सुरक्षित)',
'template-semiprotected' => '(अर्ध-सुरक्षित)',
'hiddencategories' => 'यह पृष्ठ निम्नलिखित $1 छुपाई हुई {{PLURAL:$1|श्रेणी|श्रेणियों}} में श्रेणीबद्ध है:',
'edittools' => '<!-- यहाँ दिया हुआ पाठ संपादन और अपलोड फ़ॉर्म के नीचे दर्शाया जायेगा। -->',
'nocreatetitle' => 'लेख निर्माण में प्रतिबंध',
'nocreatetext' => '{{SITENAME}} पर नये पृष्ठ बनाने के लिये मनाई की गई है।
आप पीछे जाकर किसी वर्तमान पृष्ठ को संपादित कर सकते हैं, अथवा [[Special:UserLogin|नया ख़ाता खोलें / प्रवेश करें]] ।',
'nocreate-loggedin' => 'नये पृष्ठ बनाने का आपको अधिकार नहीं है।',
'sectioneditnotsupported-title' => 'अनुभाग सम्पादन समर्थित नहीं है',
'sectioneditnotsupported-text' => 'इस पृष्ठ पर अनुभाग सम्पादन समर्थित नहीं है',
'permissionserrors' => 'अधिकार त्रुटि',
'permissionserrorstext' => 'निम्नलिखित {{PLURAL:$1|कारण|कारणों}} से आपको ऐसा करने की अनुमति नहीं हैं:',
'permissionserrorstext-withaction' => 'आपको $2 की अनुमति नहीं हैं, निम्नलिखित {{PLURAL:$1|कारण|कारणों}} की वजह से:',
'recreate-moveddeleted-warn' => "'''चेतावनी: आप एक पहले हटाए गए पृष्ठ को पुनर्निर्मित कर रहे हैं।'''

आप को विचार करना चाहिये कि क्या इस पृष्ठ का संपादन जारी रखना उचित होगा।
इस पृष्ट के हटाने व स्थानांतरण का लॉग सुविधा के लिये उपलब्ध है:",
'moveddeleted-notice' => 'यह पृष्ठ हटाया जा चुका है।
पृष्ठ के हटाने और स्थानांतरण का लॉग संदर्भ के लिए नीचे दिया गया है।',
'log-fulllog' => 'पूरा लॉग देखें',
'edit-hook-aborted' => 'फंदे द्वारा संपादन बीच में ही छोड़ा गया।
उसने कोई कारण नहीं बताया।',
'edit-gone-missing' => 'पृष्ठ अद्यतित न किया जा सका।
लगता है यह हटा दिया गया है।',
'edit-conflict' => 'संपादन अंतर्विरोध',
'edit-no-change' => 'आपने कोई बदलाव ही नहीं किए, अतः आपके इस संपादन को नज़रंदाज़ कर दिया गया है।',
'edit-already-exists' => 'नया पृष्ठ बनाया नहीं जा सका।
यह पहले से मौजूद है।',
'defaultmessagetext' => 'संदेश का डिफ़ॉल्ट पाठ',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''चेतावनी:''' इस पृष्ठ पर बहुत अधिक संख्या में कीमती पार्सर फ़ंक्शनों का प्रयोग किया गया है।

इनका प्रयोग $2 से कम बार होना चाहिये, इस समय प्रयोग $1 बार {{PLURAL:$1|है|हैं}}।",
'expensive-parserfunction-category' => 'कीमती पार्सर फ़ंक्शनों का अत्यधिक प्रयोग कर रहे पृष्ठ',
'post-expand-template-inclusion-warning' => "'''चेतावनी:''' साँचे जुड़ने की सीमा पार हो चुकी है।
कुछ साँचे नहीं जुड़ेंगे।",
'post-expand-template-inclusion-category' => 'ऐसे पृष्ठ जिनपर साँचे जुड़ने की सीमा पार हो गई है',
'post-expand-template-argument-warning' => "'''चेतावनी:''' इस पृष्ठ पर किसी साँचे में कम-से-कम एक ऐसा प्राचल है जो बढ़ाने पर बहुत बड़ा हो जायेगा।
ऐसे प्राचलों को छोड़ दिया गया है।",
'post-expand-template-argument-category' => 'ऐसे पृष्ठ जिनमें प्राचल छोड़े गये हैं',
'parser-template-loop-warning' => 'साँचा चक्र मिला: [[$1]]',
'parser-template-recursion-depth-warning' => 'साँचा पुनरावर्ती गहराई सीमा पार ($1)',
'language-converter-depth-warning' => 'भाषा कन्वर्टर गहराई सीमा से बाहर गया ( $1 )',

# "Undo" feature
'undo-success' => 'यह संपादन पूर्ववत किया जा सकता है।
ऐसा करने के लिये कृपया निम्नलिखित पाठ को ध्यान से देखकर बदलाव संजोयें।',
'undo-failure' => 'इस बीच अन्य बदलाव होने के कारण यह संपादन पूर्ववत करना संभव नहीं है।',
'undo-norev' => 'यह बदलाव वापिस नहीं कर पाये हैं क्योंकि या तो इसे पहले से पलटा दिया गया है या फिर पृष्ठ हटा दिया गया है।',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|वार्ता]]) द्वारा किए बदलाव $1 को पूर्ववत करें',

# Account creation failure
'cantcreateaccounttitle' => 'खाता खोल नहीं सकते',
'cantcreateaccount-text' => "इस आइ॰पी पते ('''$1''') को खाता निर्मित करने से [[User:$3|$3]] ने प्रतिबंधित किया है।

इसके लिये $3 ने ''$2'' कारण दिया है।",

# History pages
'viewpagelogs' => 'इस पृष्ठ का लॉग देखें',
'nohistory' => 'इस पृष्ठ का कोई इतिहास नहीं है।',
'currentrev' => 'सद्य अवतरण',
'currentrev-asof' => '$1 के समय का अवतरण',
'revisionasof' => '$1 का अवतरण',
'revision-info' => '$2 द्वारा परिवर्तित $1 का अवतरण',
'previousrevision' => '← पुराना अवतरण',
'nextrevision' => 'नया अवतरण →',
'currentrevisionlink' => 'वर्तमान अवतरण',
'cur' => 'चालू',
'next' => 'अगला',
'last' => 'पिछला',
'page_first' => 'पहला',
'page_last' => 'आखिरी',
'histlegend' => 'अन्तर चयन: अन्तर देखने के लिए पुराने अवतरणों के आगे दिए गए रेडियो बॉक्स पर क्लिक करें तथा एण्टर करें अथवा नीचे दिए हुए बटन पर क्लिक करें<br />
लिजण्ड: (चालू) = सद्य अवतरण के बीच में अन्तर,
(आखिरी) = पिछले अवतरण के बीच में अन्तर, छो = छोटा बदलाव।',
'history-fieldset-title' => 'इतिहास का विचरण करें',
'history-show-deleted' => 'सूची में केवल छुपाए हुए अवतरण दिखाएँ',
'histfirst' => 'सबसे पुराना',
'histlast' => 'सबसे नया',
'historysize' => '($1 {{PLURAL:$1|बाइट}})',
'historyempty' => '(खाली)',

# Revision feed
'history-feed-title' => 'अवतरण इतिहास',
'history-feed-description' => 'विकि पर उपलब्ध इस पृष्ठ का अवतरण इतिहास',
'history-feed-item-nocomment' => '$1 $3 को $4 बजे',
'history-feed-empty' => 'अनुरोधित पृष्ठ अस्तित्व में नहीं है।
यह पृष्ठ या तो हटाया गया है या फिर इसका नाम बदल दिया गया है।
[[Special:Search|विकि पर खोज]] का प्रयोग करें।',

# Revision deletion
'rev-deleted-comment' => '(टिप्पणी सारांश हटाई)',
'rev-deleted-user' => '(सदस्यनाम हटाया)',
'rev-deleted-event' => '(कार्यकी नोंद हटाई)',
'rev-deleted-user-contribs' => 'संशोधन उपयोगकर्ता योगदान नष्ट',
'rev-deleted-text-permission' => 'यह पृष्ठ अवतरण हटाया गया है।
इसकी अधिक जानकारी [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} हटाने की लॉग] में पाई जा सकती है।',
'rev-deleted-text-unhide' => 'यह पृष्ठ अवतरण हटाया गया है।
इसकी अधिक जानकारी [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} हटाने की लॉग] में पाई जा सकती है।
यदि आप चाहें तो इस अवतरण को [$1 देख सकते हैं]।',
'rev-suppressed-text-unhide' => "यह पृष्ठ अवतरण '''छिपाया गया है'''। विवरण [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} छुपाने की लॉग] पर देखा जा सकता है।
यदि आप चाहें तो इस अवतरण को [$1 देख सकते हैं]।",
'rev-deleted-text-view' => 'यह पृष्ठ अवतरण हटाया गया है।
आप इसे देख सकते हैं; विवरण [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} हटाने की लॉग] में पाया जा सकता है।',
'rev-suppressed-text-view' => "यह पृष्ठ अवतरण '''छिपाया गया है'''।
आप इसे देख सकते हैं; विवरण [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} छुपाने की लॉग] में पाया जा सकता है।",
'rev-deleted-no-diff' => "आप इस अंतर को नहीं देख सकते क्योंकि इनमें से एक अवतरण को '''हटा दिया गया है'''।
विवरण [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} हटाने की लॉग] में पाया जा सकता है।",
'rev-suppressed-no-diff' => "आप इस अंतर को नहीं देख सकते क्योंकि इनमें से एक अवतरण को '''हटा दिया गया है'''।",
'rev-deleted-unhide-diff' => "इस अंतर में से एक अवतरण को '''हटा दिया गया है'''।
विवरण [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} हटाने की लॉग] में पाया जा सकता है।
यदि आप चाहें तो इस अंतर को [$1 देख सकते हैं]।",
'rev-suppressed-unhide-diff' => "इस अंतर में से एक अवतरण को '''छुपा दिया गया है'''।
विवरण [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} छुपाने की लॉग] में पाया जा सकता है।
यदि आप चाहें तो इस अंतर को [$1 देख सकते हैं]।",
'rev-deleted-diff-view' => "इस अंतर में से एक अवतरण को '''हटा दिया गया है'''।
आप इस अंतर को देख सकते हैं; विवरण [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} हटाने की लॉग] पर पाया जा सकता है।",
'rev-suppressed-diff-view' => "इस अंतर में से एक अवतरण को '''छुपा दिया गया है'''।
आप अंतर देख सकते हैं; विवरण [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} छुपाने की लॉग] में पाया जा सकता है।",
'rev-delundel' => 'दिखाएँ/छुपाएँ',
'rev-showdeleted' => 'दिखाएँ',
'revisiondelete' => 'अवतरण हटायें/पुनर्स्थापित करें',
'revdelete-nooldid-title' => 'अमान्य लक्ष्य अवतरण',
'revdelete-nooldid-text' => 'इस क्रिया को करने के लिये आपने लक्ष्य अवतरण नहीं दिये हैं, या फिर आपने दिया हुआ अवतरण अस्तित्व में नहीं हैं या फिर आप सद्य अवतरण को छुपाने का प्रयत्न कर रहे हैं।',
'revdelete-nologtype-title' => 'लॉग प्रकार नहीं दिया गया',
'revdelete-nologtype-text' => 'इस क्रिया के लिए आपने लॉग प्रकार निर्दिष्ट नहीं किया है।',
'revdelete-nologid-title' => 'अवैध लॉग प्रविष्टि',
'revdelete-nologid-text' => 'आपने या तो इस कार्यकलाप को करने के लिए लक्ष्यित लॉग प्रसंग नहीं दिया है या यह प्रविष्टि मौजूद नहीं है।',
'revdelete-no-file' => 'निर्दिष्ट संचिका मौजूद नहीं है।',
'revdelete-show-file-confirm' => 'क्या आप वाकई फ़ाइल "<nowiki>$1</nowiki>" के $2 को $3 बजे बने, हटाए जा चुके अवतरण को देखना चाहते हैं?',
'revdelete-show-file-submit' => 'हाँ',
'revdelete-selected' => "'''[[:$1]] {{PLURAL:$2|का चुना हुआ|के चुने हुए}} अवतरण:'''",
'logdelete-selected' => "'''{{PLURAL:$1|चुना हुआ|चुने हुए}} लॉग इवेंट:'''",
'revdelete-text' => "'''हटाए गए अवतरण और इवेंट पृष्ठ इतिहास और लॉग में दिखेंगे, लेकिन उनकी कुछ सामग्री सार्वजनिक नहीं होगी।'''
{{SITENAME}} के अन्य प्रबंधक छिपी हुई सामग्री को देख पाएँगे, और इसी अंतरापृष्ठ के जरिए वे इसकी पुनर्स्थापना भी कर सकते हैं, बशर्ते कि अतिरिक्त प्रतिबंध न लगाए गए हों।",
'revdelete-confirm' => 'पुष्टि करें कि आप यह कार्य करना चाहते हैं, आप इसका परिणाम समझते हैं, और आप ये [[{{MediaWiki:Policy-url}}|नीति]] के अनुसार कर रहे हैं।',
'revdelete-suppress-text' => "छिपाने का प्रयोग '''केवल''' इन परिस्थितियों में होना चाहिए:
* बदनाम करने वाली जानकारी
* अनुपयुक्त निजी जानकारी
*: ''घर के पते व दूरभाष, सामाजिक सुरक्षा क्रमांक आदि''",
'revdelete-legend' => 'दृश्य प्रतिबंध निश्चित करें',
'revdelete-hide-text' => 'अवरतण का पाठ छुपाएँ',
'revdelete-hide-image' => 'फ़ाइल का पाठ छुपाएँ',
'revdelete-hide-name' => 'क्रिया और लक्ष्य को छुपाएँ',
'revdelete-hide-comment' => 'संपादन टिप्पणी छुपाएँ',
'revdelete-hide-user' => 'संपादक का सदस्यनाम/आइ॰पी छुपाएँ',
'revdelete-hide-restricted' => 'प्रबंधक सहित सभी सदस्यों से डाटा छुपाएँ',
'revdelete-radio-same' => '‍‌(बदलें नहीं)',
'revdelete-radio-set' => 'हाँ',
'revdelete-radio-unset' => 'नहीं',
'revdelete-suppress' => 'प्रबंधक सहित सभी सदस्यों से डाटा छुपाएँ',
'revdelete-unsuppress' => 'पुनर्स्थापित अवतरणों पर से प्रतिबन्ध हटाएँ',
'revdelete-log' => 'कारण:',
'revdelete-submit' => 'चयनित {{PLURAL:$1|अवतरण|अवतरणों}} पर लागू करें',
'revdelete-success' => "'''अवतरण दृश्यता सफलतापूर्वक अद्यातानीत की गई।'''",
'revdelete-failure' => "'''अवतरण दृश्यता अद्यातानीत नहीं की जा सकी:'''
$1",
'logdelete-success' => "'''लॉग दृष्यता बदली गई।'''",
'logdelete-failure' => "'''लॉग दृश्यता का जमाव नहीं किया जा सका:'''
$1",
'revdel-restore' => 'दृश्यता बदलें',
'revdel-restore-deleted' => 'हटाए गए अवतरण',
'revdel-restore-visible' => 'दृश्य अवतरण',
'pagehist' => 'पृष्ठ इतिहास',
'deletedhist' => 'हटाया हुआ इतिहास',
'revdelete-hide-current' => '$2 को, $1 बजे वाला मद छिपाया नहीं जा सका: यह सबसे ताज़ा अवतरण है।
यह छिपाया नहीं जा सकता है।',
'revdelete-show-no-access' => '$1, $2 वाला मद दिखाते समय त्रुटि आई: इस मद को "प्रतिबंधित" चिन्हित किया गया है।
आप इस तक नहीं पहुँच सकते हैं।',
'revdelete-modify-no-access' => '$2, $1 वाले मद को बदलते समय त्रुटि आई: इस मद को "प्रतिबंधित" अंकित किया गया है।
आप इस तक नहीं पहुँच सकते हैं।',
'revdelete-modify-missing' => 'मद क्रमांक $1 को बदलते समय त्रुटि आई: यह डाटाबेस में नहीं है!',
'revdelete-no-change' => "'''चेतावनी:''' $2, $1 वाले मद में पहले से ही यही वांछित दृश्यता जमाव था।",
'revdelete-concurrent-change' => '$2, $1 वाले मद को बदलते समय त्रुटि आई: प्रतीत होता है कि आपके द्वारा बदलने के दौरान किसी और ने इसमें बदलाव कर दिए हैं।
कृपया लॉग देख लें।',
'revdelete-only-restricted' => '$2, $1 की तिथि के आइटम को छुपाने में त्रुटि: आप अन्य दृश्यता विकल्पों को चुने बिना प्रबंधकों की दृष्टि से आइटमों को छुपा नहीं सकते।',
'revdelete-reason-dropdown' => '*हटाने के आम कारण
** सर्वाधिकार (कॉपीराइट) उल्लंघन
** अनुपयुक्त टिप्पणी या निजी जानकारी
** अनुपयुक्त सदस्यनाम
** मानहानिकारक जानकारी',
'revdelete-otherreason' => 'अन्य/अतिरिक्त कारण:',
'revdelete-reasonotherlist' => 'अन्य कारण',
'revdelete-edit-reasonlist' => 'हटाने के कारण बदलें',
'revdelete-offender' => 'अवतरण संपादक:',

# Suppression log
'suppressionlog' => 'छुपाने की लॉग',
'suppressionlogtext' => 'नीचे प्रबंधकों से छुपाये गए ब्लॉक और हटाये गये पृष्ठों की सूची है।
मौजूदा ब्लॉक एवं बैन देखने के लिये [[Special:BlockList|ब्लॉक सूची]] देखें।',

# History merging
'mergehistory' => 'पृष्ठ के इतिहास एकत्रित करें',
'mergehistory-header' => 'यह पृष्ठ एक स्रोत पृष्ठ का इतिहास किसी अन्य पृष्ठ में मिलाने के लिये है।
सुनिश्चित करें कि यह बदलाव पृष्ठ इतिहास में कन्टिन्य़ुईटी बरकरार रखे।',
'mergehistory-box' => 'दो पृष्ठों का इतिहास एकत्रित करें:',
'mergehistory-from' => 'स्रोत पृष्ठ:',
'mergehistory-into' => 'लक्ष्य पृष्ठ:',
'mergehistory-list' => 'एकत्रित करने लायक संपादन इतिहास',
'mergehistory-merge' => '[[:$1]] के निम्न अवतरण [[:$2]] में समाविष्ट किये जा सकते हैं।
किसी दिये हुए समय या उससे पहले हुए अवतरणों को एकत्रित करने के लिये रेडियो बटन का प्रयोग करें।
नैविगेशन कड़ियों के प्रयोग के बाद यह कॉलम अपनी पुरानी स्थिति पर आ जायेगा।',
'mergehistory-go' => 'एकत्रित करने लायक संपादन दिखाएँ',
'mergehistory-submit' => 'अवतरण एकत्रित करें',
'mergehistory-empty' => 'कोई भी अवतरण एकत्रित नहीं कर सकते।',
'mergehistory-success' => '[[:$1]] {{PLURAL:$3|का|के}} $3 अवतरण [[:$2]] में एकत्रित कर {{PLURAL:$3|दिया गया है|दिये गए हैं}}।',
'mergehistory-fail' => 'इतिहास एकत्रित नहीं कर सकते, कृपया पृष्ठ और समय की पुनः जाँच करें।',
'mergehistory-no-source' => 'स्रोत पृष्ठ $1 मौजूद नहीं है।',
'mergehistory-no-destination' => 'लक्ष्य पृष्ठ $1 मौजूद नहीं है।',
'mergehistory-invalid-source' => 'स्रोत पृष्ठ का शीर्षक वैध होना आवश्यक है।',
'mergehistory-invalid-destination' => 'लक्ष्य पृष्ठ का शीर्षक वैध होना आवश्यक है।',
'mergehistory-autocomment' => '[[:$2]] में [[:$1]] एकत्रित कर दिया',
'mergehistory-comment' => '[[:$2]] में [[:$1]] एकत्रित कर दिया: $3',
'mergehistory-same-destination' => 'स्रोत और लक्ष्य पृष्ठ एक ही नहीं हो सकते हैं',
'mergehistory-reason' => 'कारण:',

# Merge log
'mergelog' => 'एकत्रीकरण लॉग',
'pagemerge-logentry' => '[[$2]] में [[$1]] एकत्रित कर दिया ($3 तक के अवतरण)',
'revertmerge' => 'अलग करें',
'mergelogpagetext' => 'नीचे उन पृष्ठों की सूची है जिनका इतिहास हाल में ही दूसरे पृष्ठ में मिलाया गया था।',

# Diffs
'history-title' => '"$1" का अवतरण इतिहास',
'difference-title' => '"$1" के अवतरणों में अंतर',
'difference-multipage' => '(पृष्ठों के बीच अन्तर)',
'lineno' => 'पंक्ति $1:',
'compareselectedversions' => 'चुने हुए अवतरणों की तुलना करें',
'showhideselectedversions' => 'चयनित अवतरण दिखाएँ/छिपाएँ',
'editundo' => 'पूर्ववत करें',
'diff-multi' => '({{PLURAL:$2|एक योगदानकर्ता|$2 योगदानकर्ताओं}} द्वारा {{PLURAL:$1|किया बीच का एक|किए बीच के $1}} अवतरण दर्शाए नहीं हैं।)',
'diff-multi-manyusers' => '({{PLURAL:$2|एक योगदानकर्ता|$2 योगदानकर्ताओं}} द्वारा {{PLURAL:$1|किया बीच का एक|किए बीच के $1}} अवतरण दर्शाए नहीं हैं।)',

# Search results
'searchresults' => 'खोज परिणाम',
'searchresults-title' => '"$1" के लिए खोज परिणाम',
'searchresulttext' => '{{SITENAME}} में खोज में सहायता के लिए [[{{MediaWiki:Helppage}}|{{int:help}}]] देखें।',
'searchsubtitle' => 'आपने \'\'\'[[:$1]]\'\'\' की खोज की है। ([[Special:Prefixindex/$1|"$1" से शुरू हेने वाले सभी पृष्ठ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1| "$1" से जुड़े सभी पृष्ठ]])',
'searchsubtitleinvalid' => "आपने '''$1''' की खोज की है।",
'toomanymatches' => 'अत्यधिक जवाब मिले हैं, कृपया खोजशब्द बदलें',
'titlematches' => 'पृष्ठ शीर्षक मिलान',
'notitlematches' => 'कोई भी पृष्ठ शीर्षक मेल नहीं खाता',
'textmatches' => 'पृष्ठ पाठ मिलान',
'notextmatches' => 'किसी भी पृष्ठ में यह सामग्री नहीं मिली',
'prevn' => 'पिछले {{PLURAL:$1|$1}}',
'nextn' => 'अगले {{PLURAL:$1|$1}}',
'prevn-title' => '{{PLURAL:$1|पिछला|पिछले}} $1 परिणाम',
'nextn-title' => '{{PLURAL:$1|अगला|अगले}} $1 परिणाम',
'shown-title' => 'हर पृष्ठ पर $1 {{PLURAL:$1|परिणाम}} दिखाएँ',
'viewprevnext' => 'देखें ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => 'खोज विकल्प',
'searchmenu-exists' => "'''इस विकि पर \"[[:\$1]]\" नाम का एक पृष्ठ है'''",
'searchmenu-new' => "'''इस विकि पर \"[[:\$1]]\" नाम का पृष्ठ बनाएँ!'''",
'searchhelp-url' => 'Help:सहायता',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|इस उपसर्ग वाले पृष्ठ देखें]]',
'searchprofile-articles' => 'सामग्री पृष्ठ',
'searchprofile-project' => 'सहायता और परियोजना पृष्ठ',
'searchprofile-images' => 'मल्टीमीडिया',
'searchprofile-everything' => 'सब कुछ',
'searchprofile-advanced' => 'उन्नत',
'searchprofile-articles-tooltip' => '$1 में खोजें',
'searchprofile-project-tooltip' => '$1 में खोजें',
'searchprofile-images-tooltip' => 'फ़ाइलें खोजें',
'searchprofile-everything-tooltip' => '(वार्ता पृष्ठों सहित) सारी सामग्री में खोजें',
'searchprofile-advanced-tooltip' => 'विशेष नामस्थानों में खोजें',
'search-result-size' => '$1 ({{PLURAL:$2|$2 शब्द}})',
'search-result-category-size' => '{{PLURAL:$1|$1 सदस्य}} ({{PLURAL:$2|$2 उपश्रेणी|$2 उपश्रेणियाँ}}, {{PLURAL:$3|$3 सञ्चिका|$3 सञ्चिकाएँ}})',
'search-result-score' => 'संबद्ध: $1%',
'search-redirect' => '($1 से पुनर्निर्देशित)',
'search-section' => '(अनुभाग $1)',
'search-suggest' => 'कहीं आपका मतलब $1 तो नहीं था?',
'search-interwiki-caption' => 'अन्य प्रकल्प',
'search-interwiki-default' => '$1 के परिणाम:',
'search-interwiki-more' => '(और)',
'search-relatedarticle' => 'सम्बंधित',
'mwsuggest-disable' => 'AJAX सुझाव बंद करें',
'searcheverything-enable' => 'सभी नामस्थानों में खोजें',
'searchrelated' => 'सम्बंधित',
'searchall' => 'सभी',
'showingresults' => "नीचे क्रमांक '''$2''' से प्रारंभ कर के अधिकतम '''$1''' परिणाम {{PLURAL:$1|दिखाया गया है|दिखाए गए हैं}}।",
'showingresultsnum' => "नीचे क्रमांक '''$2''' से प्रारंभ कर के अधिकतम '''$3''' परिणाम {{PLURAL:$3|दिखाया गया है|दिखाए गए हैं}}।",
'showingresultsheader' => "'''$4''' के खोज परिणाम {{PLURAL:$5|कुल '''$3''' में से #'''$1'''|कुल '''$3''' में से क्रं. '''$1 - $2'''}}",
'nonefound' => "'''सूचना''': मूलतः कुछ ही नामस्थानों में खोजा जाता हैं। अगर आपको सभी नामस्थानों में खोजना हैं तो खोजशब्दोंके पहले ''all:'' लगाकर खोजने की कोशिश करें या फिर उपसर्ग के तौर पे किसी नामस्थान का नाम लिखें।",
'search-nonefound' => 'आपकी खोज से मेल खाते कोई परिणाम नहीं मिले।',
'powersearch' => 'उन्नत खोज करें',
'powersearch-legend' => 'उन्नत खोज',
'powersearch-ns' => 'नामस्थानों में खोजें:',
'powersearch-redir' => 'पुनार्निर्देश दर्शाएँ',
'powersearch-field' => 'के लिये खोजें',
'powersearch-togglelabel' => 'चुनें:',
'powersearch-toggleall' => 'सभी',
'powersearch-togglenone' => 'कोई भी नहीं',
'search-external' => 'बाहरी खोज',
'searchdisabled' => '{{SITENAME}} पर खोज अक्षम है।
आप गूगल से खोज कर सकते हैं।
ध्यान रखें कि उनकी {{SITENAME}} सामग्री की सूची पुरानी हो सकती है।',

# Quickbar
'qbsettings' => 'शीघ्रपट',
'qbsettings-none' => 'बिल्कुल नहीं',
'qbsettings-fixedleft' => 'स्थिर बाईं ओर',
'qbsettings-fixedright' => 'स्थिर दाहिनी ओर',
'qbsettings-floatingleft' => 'अस्थिर बाईं ओर',
'qbsettings-floatingright' => 'अस्थिर दाहिनी ओर',
'qbsettings-directionality' => 'निश्चित, आपकी भाषा की लिपि की दिशात्मकता पर निर्भर',

# Preferences page
'preferences' => 'मेरी वरीयताएँ',
'mypreferences' => 'पसंद',
'prefs-edits' => 'संपादन संख्या:',
'prefsnologin' => 'लॉग इन नहीं किया है',
'prefsnologintext' => 'वरीयताएँ बदलने के लिए आपको <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} सत्रारंभ]</span> करना होगा।',
'changepassword' => 'कूटशब्द बदलें',
'prefs-skin' => 'त्वचा',
'skin-preview' => 'झलक',
'datedefault' => 'खा़स पसंद नहीं',
'prefs-beta' => 'बीटा विशेषताएँ',
'prefs-datetime' => 'दिनांक तथा समय',
'prefs-labs' => 'लैब विशेषताएँ',
'prefs-user-pages' => 'सदस्य पृष्ठ',
'prefs-personal' => 'सदस्य व्यक्तिरेखा',
'prefs-rc' => 'हाल में हुए बदलाव',
'prefs-watchlist' => 'ध्यानसूची',
'prefs-watchlist-days' => 'ध्यानसूचीमें दिखाने के दिन:',
'prefs-watchlist-days-max' => 'अधिकतम $1 {{PLURAL:$1|दिन}}',
'prefs-watchlist-edits' => 'बढ़ाई हुई ध्यानसूची में दिखाने हेतु अधिकतम बदलाव:',
'prefs-watchlist-edits-max' => 'अधिकतम संख्या: १०००',
'prefs-watchlist-token' => 'ध्यानसूची टोकन',
'prefs-misc' => 'अन्य',
'prefs-resetpass' => 'कूटशब्द बदलें',
'prefs-changeemail' => 'ई-मेल पता बदलें',
'prefs-setemail' => 'ई-मेल पता सेट करें',
'prefs-email' => 'ई-मेल वरीयताएँ',
'prefs-rendering' => 'शक्लोसूरत',
'saveprefs' => 'संजोएँ',
'resetprefs' => 'ना संजोये गये बदलाव रद्द करें',
'restoreprefs' => 'वापस मूल जमावों पर आ जाएँ',
'prefs-editing' => 'संपादन',
'prefs-edit-boxsize' => 'संपादन झरोखे का आकार।',
'rows' => 'कतारें:',
'columns' => 'कॉलम:',
'searchresultshead' => 'खोज',
'resultsperpage' => 'प्रति पृष्ठ हिट्स:',
'stub-threshold' => '<a href="#" class="stub">आधार कड़ियों</a> का अधिकतम आकार (बाइट):',
'stub-threshold-disabled' => 'अक्षम किया गया',
'recentchangesdays' => 'हाल में हुए बदलावों में दर्शाने के दिन:',
'recentchangesdays-max' => 'अधिकतम $1 {{PLURAL:$1|दिन}}',
'recentchangescount' => 'मूल रूप से कितने संपादन दिखाएँ:',
'prefs-help-recentchangescount' => 'इसमें हाल के बदलाव, पृष्ठ इतिहास व लॉग शामिल हैं।',
'prefs-help-watchlist-token' => 'इस कोष्ठक में गुप्त कुंजी प्रदान करने से आपकी ध्यानसूची के लिए एक आर॰एस॰एस फ़ीड बन जाएगी।
जो भी इस कोष्ठक में मौजूद कुंजी को जानता है वह आपकी ध्यानसूची को पढ़ सकेगा, अतः कोई सुरक्षित कुंजी चुनें।
यह है आपके लिए एक यंत्रजनित कुंजी जिसका आप चाहें तो प्रयोग कर सकते हैं: $1',
'savedprefs' => 'आपकी वरीयताएँ संजोई गई हैं।',
'timezonelegend' => 'समयमंडल:',
'localtime' => 'स्थानीय समय:',
'timezoneuseserverdefault' => 'विकी डिफ़ॉल्ट का उपयोग करें ($1)',
'timezoneuseoffset' => 'अन्य (समयांतर निर्दिष्ट करें)',
'timezoneoffset' => 'समयांतर¹:',
'servertime' => 'सर्वर का समय:',
'guesstimezone' => 'ब्राउज़र से भरें',
'timezoneregion-africa' => 'अफ़्रीका',
'timezoneregion-america' => 'अमेरिका',
'timezoneregion-antarctica' => 'अंटार्कटिका',
'timezoneregion-arctic' => 'आर्कटिक',
'timezoneregion-asia' => 'एशिया',
'timezoneregion-atlantic' => 'एटलांटिक महासागर',
'timezoneregion-australia' => 'ऑस्ट्रेलिया',
'timezoneregion-europe' => 'यूरोप',
'timezoneregion-indian' => 'हिंद महासागर',
'timezoneregion-pacific' => 'प्रशांत महासागर',
'allowemail' => 'अन्य सदस्यों से ई-मेल सक्षम करें',
'prefs-searchoptions' => 'खोज ऑप्शन्स',
'prefs-namespaces' => 'नामस्थान',
'defaultns' => 'अन्यथा इन नामस्थानों में खोजें:',
'default' => 'डिफ़ॉल्ट',
'prefs-files' => 'फ़ाइलें',
'prefs-custom-css' => 'खासमखास सी॰एस॰एस',
'prefs-custom-js' => 'खासमखास जावास्क्रिप्ट',
'prefs-common-css-js' => 'सभी त्वचाओं के लिए साझा सी॰एस॰एस/जावास्क्रिप्ट:',
'prefs-reset-intro' => 'आप इस पृष्ठ के ज़रिए अपनी वरीयताओं को साइट की मूल वरीयताओं के समान बना सकते हैं।
इसके बाद आप वापस पुरानी स्थिति पर नहीं आ सकेंगे।',
'prefs-emailconfirm-label' => 'ई-मेल पुष्टिकरण:',
'prefs-textboxsize' => 'संपादन झरोखे का आकार:',
'youremail' => 'आपका ई-मेल पता:',
'username' => 'सदस्यनाम:',
'uid' => 'सदस्य क्रमांक:',
'prefs-memberingroups' => 'निम्नलिखित {{PLURAL:$1|समूह|समूहों}} के सदस्य:',
'prefs-registration' => 'पंजीकरण समय:',
'yourrealname' => 'वास्तविक नाम:',
'yourlanguage' => 'भाषा:',
'yourvariant' => 'सामग्री भाषा संस्करण:',
'prefs-help-variant' => 'आपका पसंदीदा प्रकार या इमला इस विकि के अंदर सामग्री पृष्ठों को प्रदर्शित करने के लिए।',
'yournick' => 'नए हस्ताक्षर:',
'prefs-help-signature' => 'वार्ता पृष्ठों पर की गई टिप्पणियों पर "<nowiki>~~~~</nowiki>" के ज़रिए हस्ताक्षर किया जाना चाहिए, यह आपके हस्ताक्षर और समय में परिवर्तित हो जाएगा।',
'badsig' => 'गलत कच्चा हस्ताक्षर।
HTML टैग की जाँच करें।',
'badsiglength' => 'यह हस्ताक्षर बहुत बड़ा है।
यह $1 {{PLURAL:$1|कैरैक्टर}} से अधिक का नहीं होना चाहिए।',
'yourgender' => 'लिंग',
'gender-unknown' => 'अनिर्दिष्ट',
'gender-male' => 'पुरुष',
'gender-female' => 'स्त्री',
'prefs-help-gender' => 'वैकल्पिक: यह सॉफ़्टवेयर में लिंग के आधार पर संबोधन के लिए प्रयुक्त होगा।
यह जानकारी सार्वजनिक होगी।',
'email' => 'ई-मेल',
'prefs-help-realname' => 'आपका असली नाम देना आवश्यक नहीं है।
यदि आप प्रदान करते हैं तो इसका प्रयोग आपके योगदानों के लिये आपको श्रेय (attribution) देने के लिये प्रयोग किया जायेगा।',
'prefs-help-email' => 'ई-मेल पता वैकल्पिक है, पर यदि आप अपना कूटशब्द भूल जाएँ तो इसके ज़रिए रीसेट किया जा सकता है।',
'prefs-help-email-others' => 'आप अपनी पहचान उजागर किए बिना अन्य सदस्यों को अपने सदस्य या वार्ता पृष्ठ के द्वारा स्वयं से सम्पर्क करने दे सकते हैं।',
'prefs-help-email-required' => 'ई-मेल पता आवश्यक है।',
'prefs-info' => 'मूलभूत जानकारी',
'prefs-i18n' => 'अंतर्राष्ट्रीयकरण',
'prefs-signature' => 'हस्ताक्षर',
'prefs-dateformat' => 'तिथि प्रारूप',
'prefs-timeoffset' => 'समयांतर',
'prefs-advancedediting' => 'उन्नत विकल्प',
'prefs-advancedrc' => 'उन्नत विकल्प',
'prefs-advancedrendering' => 'उन्नत विकल्प',
'prefs-advancedsearchoptions' => 'उन्नत विकल्प',
'prefs-advancedwatchlist' => 'उन्नत विकल्प',
'prefs-displayrc' => 'प्रदर्शन विकल्प',
'prefs-displaysearchoptions' => 'प्रदर्शन विकल्प',
'prefs-displaywatchlist' => 'प्रदर्शन विकल्प',
'prefs-diffs' => 'अंतर',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'ई-मेल पता वैध प्रतीत होता है',
'email-address-validity-invalid' => 'एक वैध ई-मेल पता प्रविष्ट करें',

# User rights
'userrights' => 'सदस्य अधिकार व्यवस्थापन',
'userrights-lookup-user' => 'सदस्य समूहों का व्यवस्थापन करें',
'userrights-user-editname' => 'सदस्यनाम दें:',
'editusergroup' => 'सदस्य समूहों का संपादन करें',
'editinguser' => "सदस्य '''[[User:$1|$1]]''' $2 के अधिकार बदलें",
'userrights-editusergroup' => 'सदस्य समूहों का संपादन करें',
'saveusergroups' => 'सदस्य समूह संजोएँ',
'userrights-groupsmember' => 'निम्न {{PLURAL:$1|समूह|समूहों}} का सदस्य:',
'userrights-groupsmember-auto' => 'निम्न {{PLURAL:$1|समूह|समूहों}} का अंतर्निहित सदस्य:',
'userrights-groups-help' => 'आप इस सदस्य की समूह-सदस्यता बदल सकते हैं:
* बक्से पर सही का निशान लगे होने का अर्थ है कि सदस्य उस समूह में है।
* बक्से पर सही का निशान न लगे होने का अर्थ है कि सदस्य उस समूह में नहीं है।
* एक * का अर्थ है कि एक बार जोड़ने के बाद वह समूह हटा नहीं सकते हैं, और हटाने के बाद जोड़ नहीं सकते हैं।',
'userrights-reason' => 'कारण:',
'userrights-no-interwiki' => 'आपको अन्य विकियों पर सदस्य अधिकार बदलने की अनुमति नहीं हैं।',
'userrights-nodatabase' => 'डाटाबेस $1 या तो मौजूद नहीं है या फिर स्थानीय नहीं है।',
'userrights-nologin' => 'सदस्य अधिकार बदलने के लिये प्रबंधक खाते से [[Special:UserLogin|लॉग इन]] किया हुआ होना आवश्यक है।',
'userrights-notallowed' => 'आपके खाते को सदस्य अधिकार जोड़ने या हटाने की अनुमति नहीं है।',
'userrights-changeable-col' => 'समूह जिन्हें आप बदल सकते हैं',
'userrights-unchangeable-col' => 'समूह जिन्हें आप नहीं बदल सकते हैं',

# Groups
'group' => 'समूह:',
'group-user' => 'सदस्य',
'group-autoconfirmed' => 'स्वतः स्थापित सदस्य',
'group-bot' => 'बॉट',
'group-sysop' => 'प्रबंधक',
'group-bureaucrat' => 'प्रशासक',
'group-suppress' => 'ओवरसाईट्स',
'group-all' => '(सभी)',

'group-user-member' => '{{GENDER:$1|सदस्य}}',
'group-autoconfirmed-member' => '{{GENDER:$1|स्वतः स्थापित सदस्य}}',
'group-bot-member' => '{{GENDER:$1|बॉट}}',
'group-sysop-member' => '{{GENDER:$1|प्रबंधक}}',
'group-bureaucrat-member' => '{{GENDER:$1|प्रशासक}}',
'group-suppress-member' => '{{GENDER:$1|ओवरसाईट}}',

'grouppage-user' => '{{ns:project}}:सदस्य',
'grouppage-autoconfirmed' => '{{ns:project}}:स्वतः स्थापित सदस्य',
'grouppage-bot' => '{{ns:project}}:बॉट',
'grouppage-sysop' => '{{ns:project}}:प्रबंधक',
'grouppage-bureaucrat' => '{{ns:project}}:प्रशासक',
'grouppage-suppress' => '{{ns:project}}:ओवरसाईट',

# Rights
'right-read' => 'पृष्ठ पढ़ें',
'right-edit' => 'पृष्ठ सम्पादित करें',
'right-createpage' => 'पृष्ठ बनाएँ (जो चर्चा पृष्ठ नहीं हैं)',
'right-createtalk' => 'वार्ता पृष्ठ बनाएँ',
'right-createaccount' => 'नये सदस्य खाते बनाएँ',
'right-minoredit' => 'अपने बदलाव छोटे चिन्हित करें',
'right-move' => 'पृष्ठ स्थानांतरित करें',
'right-move-subpages' => 'पृष्ठ उपपृष्ठों सहित स्थानांतरीत करें',
'right-move-rootuserpages' => 'मूल सदस्य पृष्ठ स्थानांतरित करें',
'right-movefile' => 'संचिकाएँ स्थानांतरित करें',
'right-suppressredirect' => 'पृष्ठ स्थानांतरित करते समय पुनर्निर्देश ना छोड़ें',
'right-upload' => 'फ़ाइल अपलोड करें',
'right-reupload' => 'मौजूदा फ़ाईलों पर पुनर्लेखन करें',
'right-reupload-own' => 'स्वयं अपलोड की हुई फ़ाइल पर पुनर्लेखन करें',
'right-reupload-shared' => 'शेअर्ड इमेज भण्डार में मौजूद फ़ाइलों पर पुनर्लेखन करें',
'right-upload_by_url' => 'यू॰आर॰एल से फ़ाइल अपलोड करें',
'right-purge' => 'पृष्ठ की कैश मेमोरी खाली करें',
'right-autoconfirmed' => 'अर्ध-सुरक्षित पृष्ठ सम्पादित करें',
'right-bot' => 'स्वचलित प्रणाली माने जाएँ',
'right-nominornewtalk' => 'वार्ता पृष्ठों पर छोटे बदलाव करने पर सदस्यों को "आपके लिये नया सन्देश है" पट्टी न दिखाएँ',
'right-apihighlimits' => 'API पृच्छाओं में ऊँची सीमाएँ प्रयोग करें',
'right-writeapi' => 'write API का प्रयोग करें',
'right-delete' => 'पृष्ठ हटाएँ',
'right-bigdelete' => 'अधिक इतिहास वाले पृष्ठ हटाएँ',
'right-deleterevision' => 'पृष्ठों के विशिष्ट अवतरण हटाएँ एवं पुनर्स्थापित करें',
'right-deletedhistory' => 'हटाई गई इतिहास सूची, उसके साथ पाये जाने वाले पाठ के बिना देखें',
'right-deletedtext' => 'हटाया गया पाठ और हटाए गए अवतरणों के बीच अंतर देखें',
'right-browsearchive' => 'हटाए गए पृष्ठ खोजें',
'right-undelete' => 'पृष्ठ पुनर्स्थापित करें',
'right-suppressrevision' => 'प्रबंधकों से छुपे हुए अवतरण देखें और पुनर्स्थापित करें',
'right-suppressionlog' => 'खासगी लॉग देखें',
'right-block' => 'अन्य सदस्यों को सम्पादन करने से ब्लॉक करें',
'right-blockemail' => 'सदस्यों को ई-मेल भेजने से ब्लॉक करें',
'right-hideuser' => 'सदस्यनाम ब्लॉक करें और उसे लोगों से छुपाएँ',
'right-ipblock-exempt' => 'आइ॰पी ब्लॉक्स, ऑटो-ब्लॉक्स और रेंज ब्लॉक्स को नज़रंदाज़ करें',
'right-proxyunbannable' => 'स्वचालित प्रौक्सी ब्लॉक्स को नज़रंदाज़ करें',
'right-unblockself' => 'स्वयं को अनब्लॉक करें',
'right-protect' => 'सुरक्षा स्तर बदलें और सुरक्षित पृष्ठ सम्पादित करें',
'right-editprotected' => 'सुरक्षित पृष्ठ सम्पादित करें (बिना सीढ़ी सुरक्षा वाले)',
'right-editinterface' => 'सॉफ़्टवेयर इंटरफ़ेस सम्पादित करें',
'right-editusercssjs' => 'अन्य सदस्यों के सी॰एस॰एस और जावास्क्रिप्ट पृष्ठ सम्पादित करें',
'right-editusercss' => 'अन्य सदस्यों के सी॰एस॰एस पृष्ठ सम्पादित करें',
'right-edituserjs' => 'अन्य सदस्यों के जावास्क्रिप्ट पृष्ठ सम्पादित करें',
'right-rollback' => 'किसी पृष्ठ का अंतिम सम्पादन करने वाले सदस्य के सम्पादन वापिस लें',
'right-markbotedits' => 'वापिस लेने में हुए संपादनों को बॉट सम्पादन चिन्हित करें',
'right-noratelimit' => 'रेट लिमिट्स से बेअसर हों',
'right-import' => 'अन्य विकियों से पृष्ठ आयात करें',
'right-importupload' => 'फ़ाइल अपलोड द्वारा पृष्ठ आयात करें',
'right-patrol' => 'अन्य सदस्यों के सम्पादन जाँचे हुए चिन्हित करें',
'right-autopatrol' => 'अपने सम्पादन स्वचालित रूप से जाँचे हुए चिन्हित करें',
'right-patrolmarks' => 'हाल में हुए बदलावों में जाँच चिन्ह देखें',
'right-unwatchedpages' => 'ऐसे पृष्ठों की सूची देखें जो किसी की ध्यानसूची में नहीं हैं',
'right-mergehistory' => 'पृष्ठ इतिहास एकत्रित करें',
'right-userrights' => 'सभी सदस्य अधिकार बदलें',
'right-userrights-interwiki' => 'अन्य विकियों पर सदस्य अधिकार बदलें',
'right-siteadmin' => 'डाटाबेस को ताला लगायें या खोलें',
'right-override-export-depth' => 'पृष्ठ निर्यात करें, पाँच स्तर की गहराई तक जुड़े हुए पृष्ठों समेत',
'right-sendemail' => 'अन्य सदस्यों को ई-मेल भेजें',
'right-passwordreset' => 'कूटशब्द रीसेट ई-मेल देखें',

# User rights log
'rightslog' => 'सदस्य अधिकार सूची',
'rightslogtext' => 'यह सदस्य अधिकारों में हुए बदलावों की सूची है।',
'rightslogentry' => '$1 की समूह सदस्यता $2 से $3 को बदली',
'rightslogentry-autopromote' => 'स्वचालित रूप से $2 से $3 को पदोन्नत हुआ था',
'rightsnone' => '(कोई नहीं)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'इस पृष्ठ को पढ़ने',
'action-edit' => 'इस पृष्ठ को सम्पादित करने',
'action-createpage' => 'पृष्ठ बनाने',
'action-createtalk' => 'वार्ता पृष्ठ बनाने',
'action-createaccount' => 'यह सदस्य खाता खोलने',
'action-minoredit' => 'इस बदलाव को छोटा बदलाव चिन्हित करने',
'action-move' => 'इस पृष्ठ को स्थानांतरित करने',
'action-move-subpages' => 'इस पृष्ठ व इसके उप-पृष्ठों को स्थानांतरित करने',
'action-move-rootuserpages' => 'मूल सदस्य पृष्ठों को स्थानांतरित करने',
'action-movefile' => 'इस फ़ाइल को स्थानांतरित करने',
'action-upload' => 'इस फ़ाइल को अपलोड करने',
'action-reupload' => 'मौजूदा फ़ाइल के स्थान पर नई सामग्री डालने',
'action-reupload-shared' => 'साझे भंडार में इस फ़ाइल के ऊपर कुछ और डालने',
'action-upload_by_url' => 'यू॰आर॰एल से इस फ़ाइल को चढ़ाने',
'action-writeapi' => 'लेखन ए॰पी॰आई का प्रयोग करने',
'action-delete' => 'इस पृष्ठ को हटाने',
'action-deleterevision' => 'इस अवतरण को हटाने',
'action-deletedhistory' => 'इस पृष्ठ के मिटे इतिहास को देखने',
'action-browsearchive' => 'हटाएँ गए पृष्ठों में खोजने',
'action-undelete' => 'इस पृष्ठ को पुनर्स्थापित करने',
'action-suppressrevision' => 'इस छिपे अवतरण को देखने और पुनर्स्थापित करने',
'action-suppressionlog' => 'इस निजी लॉग को देखने',
'action-block' => 'इस सदस्य को संपादन करने से ब्लॉक करने',
'action-protect' => 'इस पृष्ठ के सुरक्षा स्तर बदलने',
'action-rollback' => 'किसी पृष्ठ का अंतिम सम्पादन करने वाले सदस्य के सम्पादन वापिस लेने',
'action-import' => 'किसी और विकि से यह पृष्ठ आयात करने',
'action-importupload' => 'फ़ाइल अपलोड द्वारा यह पृष्ठ आयात करने',
'action-patrol' => 'अन्य सदस्यों के सम्पादन जाँचे हुए चिन्हित करने',
'action-autopatrol' => 'अपने सम्पादन स्वचालित रूप से जाँचे हुए चिन्हित करने',
'action-unwatchedpages' => 'ऐसे पृष्ठ जो किसी की ध्यानसूची में नहीं हैं की सूची देखने',
'action-mergehistory' => 'इस पृष्ठ का इतिहास एकत्रित करने',
'action-userrights' => 'सभी सदस्य अधिकार बदलने',
'action-userrights-interwiki' => 'अन्य विकियों पर सदस्य अधिकार बदलने',
'action-siteadmin' => 'डाटाबेस को ताला लगाने या खोलने',
'action-sendemail' => 'ई-मेल भेजने',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|बदलाव}}',
'recentchanges' => 'हाल में हुए बदलाव',
'recentchanges-legend' => 'हाल के परिवर्तन संबंधी विकल्प',
'recentchanges-summary' => 'इस विकिपर हाल में हुए बदलाव इस पन्ने पर देखें जा सकतें हैं।',
'recentchanges-feed-description' => 'इस विकि पर हाल में हुए बदलाव इस फ़ीड में देखे जा सकते हैं।',
'recentchanges-label-newpage' => 'इस संपादन से नया पृष्ठ बना',
'recentchanges-label-minor' => 'यह एक छोटा सम्पादन है',
'recentchanges-label-bot' => 'यह संपादन एक बॉट द्वारा किया गया था',
'recentchanges-label-unpatrolled' => 'यह संपादन अभी जाँचा नहीं गया है',
'rcnote' => "$5, $4 के पहले के '''$2''' {{PLURAL:$2|दिन|दिनों}} में  {{PLURAL:$1|हुआ '''$1''' बदलाव निम्न है| हुए '''$1''' बदलाव निम्न हैं}}।",
'rcnotefrom' => "नीचे '''$2''' के बाद से ('''$1''' तक) हुए बदलाव दर्शाए गये हैं।",
'rclistfrom' => '$1 से नये बदलाव दिखाएँ',
'rcshowhideminor' => 'छोटे बदलाव $1',
'rcshowhidebots' => 'बॉट $1',
'rcshowhideliu' => 'लॉग्ड इन सदस्यों के बदलाव $1',
'rcshowhideanons' => 'आइ॰पी सदस्यों के बदलाव $1',
'rcshowhidepatr' => 'जाँचे हुए सम्पादन $1',
'rcshowhidemine' => 'मेरे बदलाव $1',
'rclinks' => 'पिछले $2 दिनों में हुए $1 बदलाव दिखाएँ<br />$3',
'diff' => 'अंतर',
'hist' => 'इतिहास',
'hide' => 'छिपाएँ',
'show' => 'दिखाएँ',
'minoreditletter' => 'छो',
'newpageletter' => 'न',
'boteditletter' => 'बॉ',
'number_of_watching_users_pageview' => '[$1 ध्यान रखने वाले {{PLURAL:$1|सदस्य}}]',
'rc_categories' => 'श्रेणीयों तक सीमीत रखें ("|" से अलग करें)',
'rc_categories_any' => 'कोई भी',
'rc-change-size-new' => 'बदलाव के बाद $1 {{PLURAL:$1|बाइट}}',
'newsectionsummary' => '/* $1 */ नया अनुभाग',
'rc-enhanced-expand' => 'विस्तृत जानकारी दिखाएँ (इसके लिए जावास्क्रिप्ट चाहिए)',
'rc-enhanced-hide' => 'विस्तृत जानकारी छिपाएँ',
'rc-old-title' => 'मूल रूप से "$1" नाम से बनाया गया था',

# Recent changes linked
'recentchangeslinked' => 'पृष्ठ से जुड़े बदलाव',
'recentchangeslinked-feed' => 'पृष्ठ से जुड़े बदलाव',
'recentchangeslinked-toolbox' => 'पृष्ठ से जुड़े बदलाव',
'recentchangeslinked-title' => '"$1" से जुड़े बदलाव',
'recentchangeslinked-noresult' => 'जुड़े हुए पृष्ठों में दी हुई अवधि में कोई भी बदलाव नहीं हुए हैं।',
'recentchangeslinked-summary' => "यह पृष्ठ किसी विशिष्ट पृष्ठ से जुड़े पृष्ठों (या किसी श्रेणी में श्रेणीबद्ध पृष्ठों) में हाल में हुए बदलावों की सूची दर्शाता है।
[[Special:Watchlist|आपकी ध्यानसूची]] में मौजूद पृष्ठ '''मोटे''' अक्षरों में दिखेंगे।",
'recentchangeslinked-page' => 'पृष्ठ नाम:',
'recentchangeslinked-to' => 'इसके बदले में दिये हुए पृष्ठसे जुडे पन्नोंके बदलाव दर्शायें',

# Upload
'upload' => 'फ़ाइल अपलोड करें',
'uploadbtn' => 'फ़ाइल अपलोड करें',
'reuploaddesc' => 'अपलोड रद्द करें और पुनः अपलोड फ़ॉर्म पर जाएँ',
'upload-tryagain' => 'संशोधित फ़ाइल विवरण भेजें',
'uploadnologin' => 'लॉग इन नहीं किया है',
'uploadnologintext' => 'फ़ाइलें अपलोड करने के लिये [[Special:UserLogin|लॉग इन]] करना आवश्यक है।',
'upload_directory_missing' => 'अपलोड डाइरेक्टरी ($1) मौजूद नहीं है, और वेबसर्वर इसका निर्माण नहीं कर पाया।',
'upload_directory_read_only' => 'अपलोड डाइरेक्टरी ($1) में वेबसर्वर लिख नहीं पा रहा है।',
'uploaderror' => 'अपलोड त्रुटि',
'upload-recreate-warning' => "'''चेतावनी: उस नाम की फ़ाइल हटाई या स्थानान्तरित की जा चुकी है।'''

इस पृष्ठ के हटाने और स्थानान्तरण के लॉग यहाँ सन्दर्भ के लिए दिये हैं:",
'uploadtext' => "फ़ाइल अपलोड करने के लिए नीचे दिए फ़ॉर्म का प्रयोग करें।
[[Special:FileList|अपलोड की गई फ़ाइलों की सूची]] से आप पहले पहले अपलोड की गई फ़ाइलों को देख सकते हैं और उनमें खोज सकते हैं। दोबारा अपलोड की गई फ़ाइलों को [[Special:Log/upload|अपलोड सूची]] में देखें, और मिटाई फ़ाइलों के लिए [[Special:Log/delete|हटाने की सूची]] देखें।

किसी पृष्ठ में फ़ाइल का प्रयोग करने के लिए नीचे दिए गये उदाहरणों के अनुसार कड़ियाँ बनाएँ।
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' फ़ाइल का पूरा आकार प्रयोग करने के लिये
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></code>''' पृष्ठ में बाईं ओर फ़ाइल का 200 पिक्सेल चौड़ा अवतरण \"alt text\" विवरण के साथ एक बक्से में प्रयोग करने के लिये
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' बिना फ़ाइल का प्रयोग किये केवल उसकी कड़ी जोड़ने के लिये",
'upload-permitted' => 'अनुमतित फ़ाइल प्रकार: $1।',
'upload-preferred' => 'पसंदीदा फ़ाइल प्रकार: $1।',
'upload-prohibited' => 'प्रतिबंधिक फ़ाइल प्रकार: $1।',
'uploadlog' => 'अपलोड लॉग',
'uploadlogpage' => 'अपलोड लॉग',
'uploadlogpagetext' => 'नीचे हाल ही में अपलोड की गई फ़ाइलों की सूची है।
कृपया और द्रैश्यिक विवरण के लिए [[Special:NewFiles|नई फ़ाइलों की गैलरी]] देखें।',
'filename' => 'फ़ाइल का नाम',
'filedesc' => 'वर्णन',
'fileuploadsummary' => 'संक्षिप्त जानकारी:',
'filereuploadsummary' => 'फ़ाइल में बदलाव:',
'filestatus' => 'कॉपीराइट स्थिति:',
'filesource' => 'स्रोत:',
'uploadedfiles' => 'अपलोड की हुई फ़ाइलें',
'ignorewarning' => 'चेतावनियाँ नज़र‍ंदाज़ करें और फ़ाइल अपलोड करें',
'ignorewarnings' => 'सभी चेतावनियों को नज़रंदाज़ करें',
'minlength1' => 'फ़ाइल का नाम कम-से-कम एक अक्षर का होना चाहिये।',
'illegalfilename' => 'फ़ाइल के नाम "$1" में कुछ ऐसे कैरैक्टर हैं जो पृष्ठ शीर्षकों में प्रतिबंधित हैं।
कृपया फ़ाइल का नाम बदल के अपलोड करने की कोशिश करें।',
'filename-toolong' => 'फ़ाइल नाम 240 बाइट से अधिक लंबे नहीं हो सकते।',
'badfilename' => 'फ़ाइल का नाम बदल के "$1" कर दिया गया है।',
'filetype-mime-mismatch' => 'फाइल एक्सटेंशन ".$1" फ़ाइल के खोजे गए MIME प्रकार ($2) से मेल नहीं खाता।',
'filetype-badmime' => '"$1" प्रकार की फ़ाइलें अपलोड करने के लिये अनुमति नहीं है।',
'filetype-bad-ie-mime' => 'इस फ़ाइल को अपलोड नहीं किया जा सकता क्योंकि इंटर्नेट एक्स्प्लोरर इसे "$1" मानेगा जो कि प्रतिबन्धित व संभवतः खतरनाक फ़ाइल प्रकार है।',
'filetype-unwanted-type' => "'''\".\$1\"''' एक अवांछित फ़ाइल प्रकार है।
वांछित फ़ाइल प्रकार {{PLURAL:\$3|है|हैं}} \$2।",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' फ़ाइल {{PLURAL:$4|प्रकार|प्रकारों}} की अनुमति नहीं है।
फ़ाइल प्रकार {{PLURAL:$3|जिसकी|जिनकी}} अनुमति है: $2।',
'filetype-missing' => 'इस फ़ाइल को एक्स्टेंशन नहीं हैं (उदाहरण ".jpg")।',
'empty-file' => 'आपकी दी गई फ़ाइल खाली थी।',
'file-too-large' => 'आपकी दी गई फ़ाइल बहुत बड़ी थी।',
'filename-tooshort' => 'फ़ाइल का नाम बहुत छोटा है।',
'filetype-banned' => 'इस प्रकार की फ़ाइल प्रतिबन्धित है।',
'verification-error' => 'यह फ़ाइल सत्यापन में अनुत्तीर्ण रही।',
'hookaborted' => 'आपके द्वारा प्रयासरत संशोधन किसी एक्स्टेंशन द्वारा निरस्त किया गया।',
'illegal-filename' => 'इस फ़ाइल नाम की अनुमति नहीं है',
'overwrite' => ' मौजूदा फ़ाइल को अधिलेखित करने की अनुमति नहीं है',
'unknown-error' => 'अज्ञात त्रुटि उत्पन्न हुई।',
'tmp-create-error' => 'अस्थाई फ़ाइल नहीं बना सका',
'tmp-write-error' => 'अस्थायी फ़ाइल को लिखने में त्रुटि।',
'large-file' => 'फ़ाइलें $1 से कम आकार की होना आवश्यक हैं;
यह फ़ाइल $2 आकार की है।',
'largefileserver' => 'इस फ़ाइल का आकार निर्धारित आकार सीमा के पार है।',
'emptyfile' => 'आपके द्वारा अपलोड की गई फ़ाइल रिक्त है। यह फ़ाइल का नाम लिखने में गलती के चलते हो सकता है। कृपया जाँचें कि क्या आप यही फ़ाइल अपलोड करना चाहते हैं।',
'windows-nonascii-filename' => 'यह विकि विशेष कैरैक्टरों के साथ फ़ाइल के नामों को स्वीकार नहीं करता।',
'fileexists' => 'इस नाम की फ़ाइल पहले से मौजूद है, यदि यह फ़ाइल बदलने में आप साशंक हैं तो कृपया <strong>[[:$1]]</strong> देखें। [[$1|thumb]]',
'filepageexists' => 'इस फ़ाइल के लिए विवरण पृष्ठ पहले ही <strong>[[:$1]]</strong> पर बनाया जा चुका है, पर इस नाम की कोई फ़ाइल अभी उपस्थित नहीं है। 
आप जो विवरण देंगे वह विवरण पृष्ठ पर नहीं दिखेगा। 
आपको अपने विवरण को वहाँ डालने के लिए उसका हस्त्य सम्पादन करना पड़ेगा।
[[$1|thumb]]',
'fileexists-extension' => 'इस नाम से मिलते-जुलते नाम की एक फ़ाइल पहले से है: [[$2|thumb]]
* अपलोड हो रही फ़ाइल का नाम: <strong>[[:$1]]</strong>
* मौजूदा फ़ाइल का नाम: <strong>[[:$2]]</strong>
कृपया अन्य नाम चुनें।',
'fileexists-thumbnail-yes' => "यह फ़ाइल बड़े चित्र का छोटा आकार ''(अंगूठाकार)'' प्रतीत होता है। [[$1|thumb]]
<strong>[[:$1]]</strong> फ़ाइल को देखें।
अगर जाँची गई फ़ाइल इसी आकार की है तो छोटे आकार की फ़ाइल अपलोड करने की आवश्यकता नहीं है।",
'file-thumbnail-no' => "इस फ़ाइल का नाम <strong>$1</strong> से शुरू हो रहा है।
यह आकार घटाई हुई ''(अंगूठाकार)'' हो सकती है।
अगर यह चित्र अपने मूल आकार में है तो इसे अपलोड करें, नहीं तो फ़ाइल बदलें।",
'fileexists-forbidden' => 'इस नाम की फ़ाइल पहले ही मौजूद है, और इसकी जगह और नहीं अपलोड की जा सकती।
यदि आप इस फ़ाइल को फिर भी अपलोड करना चाहते हैं, तो कृपया वापस जा के इसके लिए कोई अन्य नाम चुनें।
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'इस नाम की फ़ाइल साझे फ़ाइल भंडार में पहले ही मौजूद है।
यदि आप इस फ़ाइल को फिर भी अपलोड करना चाहते हैं, तो कृपया वापस जा के इसके लिए कोई अन्य नाम चुनें।
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'यह फ़ाइल निम्नलिखित {{PLURAL:$1|फ़ाइल|फ़ाइलों}} की प्रति है:',
'file-deleted-duplicate' => 'इसी फ़ाइल ([[:$1]]) से हूबहू मेल खाती एक फ़ाइल पहले हटाई जा चुकी है।
इसे फिर से अपलोड करने से पहले आपको पुरानी फ़ाइल का हटाने के इतिहास देख लेना चाहिए।',
'uploadwarning' => 'अपलोड चेतावनी',
'uploadwarning-text' => 'फ़ाइल विवरण को संशोधित कर फिर कोशिश करें।',
'savefile' => 'फ़ाइल संजोयें',
'uploadedimage' => '"[[$1]]" अपलोड करी',
'overwroteimage' => '"[[$1]]" का नया अवतरण अपलोड किया',
'uploaddisabled' => 'अपलोड प्रतिबंधित हैं।',
'copyuploaddisabled' => 'यू॰आर॰एल द्वारा अपलोड अक्षम हैं।',
'uploadfromurl-queued' => 'आपका अपलोड पंक्तिबद्ध किया गया।',
'uploaddisabledtext' => 'फ़ाइल अपलोड अक्षम हैं।',
'php-uploaddisabledtext' => 'पी॰एच॰पी में फ़ाइल अपलोड बंद हैं।
कृपया file_uploads जमाव की जाँच करें।',
'uploadscripted' => 'इस फ़ाइल में एच॰टी॰एम॰एल या स्क्रिप्ट कोड है, जो वेब ब्राउज़र द्वारा गलत पढ़ा जा सकता है।',
'uploadvirus' => 'इस फ़ाइल में व्हाईरस हैं! अधिक जानकारी: $1',
'uploadjava' => 'यह फ़ाइल एक ज़िप फ़ाइल है जिसमें एक जावा .class फ़ाइल है।
जावा फ़ाइलों को अपलोड करना वर्जित है, क्योंकि इनके कारण सुरक्षा बाधाएँ पार की जा सकती हैं।',
'upload-source' => 'स्रोत फ़ाइल',
'sourcefilename' => 'स्रोत फ़ाइल का नाम:',
'sourceurl' => 'स्रोत यू॰आर॰एल:',
'destfilename' => 'लक्ष्य फ़ाइल नाम:',
'upload-maxfilesize' => 'अधिकतम फ़ाइल आकार: $1',
'upload-description' => 'फ़ाइल विवरण',
'upload-options' => 'अपलोड विकल्प',
'watchthisupload' => 'इस फ़ाइल पर ध्यान रखें',
'filewasdeleted' => 'इस नाम की एक फ़ाइल पहले भी अपलोड होने के बाद हटाई जा चुकी है।
फिरसे अपलोड करने से पहले आप $1 को अच्छी तरह से जाँचे।',
'filename-bad-prefix' => "आप जो फ़ाइल अपलोड कर रहे हैं उसका नाम '''\"\$1\"''' से शुरू होता है, जो डिजिटल कैमेरे द्वारा दिया गया नाम है।
कृपया इस फ़ाइल के लिये कोई दूसरा अधिक जानकारीयुक्त नाम चुनें।",
'filename-prefix-blacklist' => '#<!-- leave this line exactly as it is --> <pre>
# रूपरेखा इस प्रकार हैं:
#   *  "#" अक्षर से शुरू होने वाली लाइनें टिप्पणीयाँ हैं।
#   *हर नई लाइन कैमेरा उत्पादक द्वारा लगाये जाने वाले उपपदों की सूची है।
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # some mobil phones
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- leave this line exactly as it is -->',
'upload-success-subj' => 'अपलोड हो गई',
'upload-success-msg' => 'आपका [$2] से अपलोड असफल रहा। यह [[:{{ns:file}}:$1]] पर उपलब्ध है',
'upload-failure-subj' => 'अपलोड समस्या',
'upload-failure-msg' => '[$2] से आपके अपलोड में एक समस्या थी:

$1',
'upload-warning-subj' => 'अपलोड चेतावनी',
'upload-warning-msg' => 'आपके [$2] से अपलोड के साथ एक समस्या थी। आप इस समस्या को ठीक करने के लिए [[Special:Upload/stash/$1|अपलोड फ़ॉर्म]] पर लौट सकते हैं।',

'upload-proto-error' => 'गलत प्रोटोकॉल',
'upload-proto-error-text' => 'रिमोट अपलोड के लिये यू॰आर॰एल का नाम <code>http://</code> या <code>ftp://</code> से शुरू होना आवश्यक है।',
'upload-file-error' => 'आतंरिक त्रुटि',
'upload-file-error-text' => 'सर्वर पर अस्थायी फ़ाइल बनाते समय आंतरिक त्रुटि आई।
कृपया किसी [[Special:ListUsers/sysop|प्रबंधक]] से संपर्क करें।',
'upload-misc-error' => 'अज्ञात अपलोड त्रुटि',
'upload-misc-error-text' => 'अपलोड के दौरान कोई अज्ञात त्रुटि आई।
कृपया यह पुष्टि कर लें कि यू॰आर॰एल वैध है और उस तक पहुँचा जा सकता है, उसके बाद फिर कोशिश करें।
अगर फिर भी समस्या आती है तो किसी [[Special:ListUsers/sysop|प्रबंधक]] से संपर्क करें।',
'upload-too-many-redirects' => 'इस यू॰आर॰एल में अत्यधिक पुनर्निर्देशन हैं',
'upload-unknown-size' => 'अज्ञात आकार',
'upload-http-error' => 'एक एच॰टी॰टी॰पी त्रुटि आई: $1',
'upload-copy-upload-invalid-domain' => 'कॉपी अपलोड इस डोमेन से उपलब्ध नहीं हैं।',

# File backend
'backend-fail-stream' => 'फ़ाइल $1 स्ट्रीम नहीं हो पाई।',
'backend-fail-backup' => 'फ़ाइल $1 बैकअप नहीं हो पाई।',
'backend-fail-notexists' => 'फ़ाइल $1 मौजूद नहीं है।',
'backend-fail-hashes' => 'तुलना के लिए फ़ाइलों की हैश नहीं मिलीं।',
'backend-fail-notsame' => 'एक ग़ैर-समान फ़ाइल $1 पर पहले से मौजूद है।',
'backend-fail-invalidpath' => '$1 मान्य भंडारण पथ नहीं है।',
'backend-fail-delete' => 'फ़ाइल $1 हटाई नहीं जा सकी।',
'backend-fail-alreadyexists' => 'फ़ाइल $1 पहले से मौजूद है।',
'backend-fail-store' => 'फ़ाइल $1, $2 पर संग्रहीत नहीं हो पाई।',
'backend-fail-copy' => 'फ़ाइल $1 की $2 पर प्रतिलिपि नहीं बन पाई।',
'backend-fail-move' => 'फ़ाइल $1 से $2 पर स्थानांतरित नहीं हो पाई।',
'backend-fail-opentemp' => 'अस्थाई फ़ाइल खोल नहीं जा सकी।',
'backend-fail-writetemp' => 'अस्थायी फ़ाइल पर लिखना संभव नहीं हुआ।',
'backend-fail-closetemp' => 'अस्थाई फ़ाइल बंद नहीं हो पाई।',
'backend-fail-read' => 'फ़ाइल $1 पढ़ी नहीं जा सकी।',
'backend-fail-create' => 'फ़ाइल $1 लिखी नहीं जा सकी।',
'backend-fail-maxsize' => 'फ़ाइल $1 लिखी नहीं जा सकी क्योंकि यह {{PLURAL:$2|$2 बाईट}} से बड़ी है।',
'backend-fail-readonly' => 'भंडारण बैकेंड "$1" इस समय केवल पढ़ा जा सकता है (रीड-ओन्ली है)। दिया गया कारण था: "$2"',
'backend-fail-synced' => 'फ़ाइल "$1" आतंरिक भंडारण बैकेंड में असंगत स्थिति में है।',
'backend-fail-connect' => '"$1" भंडारण बैकेंड से सम्पर्क स्थापित नहीं किया जा सका।',
'backend-fail-internal' => 'भंडारण बैकेंड "$1" में कोई अज्ञात त्रुटि उत्पन्न हुई।',
'backend-fail-contenttype' => '"$1" पर संजोने के लिये फ़ाइल का प्रकार नहीं निश्चित किया जा सका।',
'backend-fail-batchsize' => 'भंडारण बैकेंड को $1 फ़ाइल {{PLURAL:$1|कार्य}} दिये गए थे; सीमा {{PLURAL:$2|$2 कार्य|$2 कार्यों}} की है।',

# File journal errors
'filejournal-fail-dbconnect' => 'भंडारण बैकेंड "$1" के जर्नल डाटाबेस से सम्पर्क नहीं हो पाया।',
'filejournal-fail-dbquery' => 'भंडारण बैकेंड "$1" के जर्नल डाटाबेस का अद्यतन नहीं किया जा सका।',

# Lock manager
'lockmanager-notlocked' => '"$1" अनलॉक नहीं किया जा सका; ये बंद नहीं है।',
'lockmanager-fail-closelock' => '"$1" की लॉक फ़ाइल बंद नहीं की जा सकी।',
'lockmanager-fail-deletelock' => '"$1" की लॉक फ़ाइल हटाई नहीं जा सकी।',
'lockmanager-fail-acquirelock' => '"$1" के लिए लॉक प्राप्त नहीं किया जा सका।',
'lockmanager-fail-openlock' => '"$1" के लिये लॉक फ़ाइल खोली नहीं जा सकी।',
'lockmanager-fail-releaselock' => '"$1" के लिए लॉक हटाया नहीं जा सका।',
'lockmanager-fail-db-bucket' => 'बकेट $1 में आवश्यक संख्या में लॉक डाटाबेसों से सम्पर्क नहीं हो पाया।',
'lockmanager-fail-db-release' => 'डाटाबेस $1 से ताला हटाया नहीं जा सका।',
'lockmanager-fail-svr-release' => 'सर्वर $1 से टाला हटाया नहीं जा सका।',

# ZipDirectoryReader
'zip-file-open-error' => 'ज़िप जाँच के लिए फ़ाइल खोलते समय त्रुटि आई।',
'zip-wrong-format' => 'निर्दिष्ट फ़ाइल एक ज़िप फ़ाइल नहीं थी।',
'zip-bad' => 'ज़िप फ़ाइल या तो दूषित है या किसी अन्य कारण से अपठनीय है।
इसकी ठीक से सुरक्षा जाँच नहीं की जा सकती।',
'zip-unsupported' => 'यह फ़ाइल एक ज़िप फ़ाइल है जो ऐसी ज़िप विशेषताओं का प्रयोग करती है जो मीडियाविकि द्वारा समर्थित नहीं हैं।
इसकी ठीक से सुरक्षा जाँच नहीं की जा सकती।',

# Special:UploadStash
'uploadstash' => 'स्टैश अपलोड करें',
'uploadstash-summary' => 'यह पृष्ठ उन फ़ाइलों के लिए अभिगम उपलब्ध कराता है जो अपलोड की गई हैं ‍‌‍‌(या अपलोड प्रक्रिया में हैं) लेकिन विकी पर अभी भी प्रकाशित नहीं हुई हैं। ये फ़ाइलें अपलोड करने वाले सदस्य को छोड़कर किसी के लिए भी दर्शित नहीं हैं।',
'uploadstash-clear' => 'स्टैश की गई फ़ाइलें साफ़ करें',
'uploadstash-nofiles' => 'आपके पास कोई स्टैश की हुई फ़ाइलें नहीं हैं।',
'uploadstash-badtoken' => 'वह कार्य असफल रहा, सम्भवतः आपके सम्पादन प्रमाणपत्र की अवधि समाप्त हो गई है। पुनः प्रयास करें।',
'uploadstash-errclear' => 'फ़ाइलों को साफ़ करना असफल रहा।',
'uploadstash-refresh' => 'फ़ाइलों की सूची रिफ़्रेश करें',
'invalid-chunk-offset' => 'अग्राह्य चंक ऑफ़सेट',

# img_auth script messages
'img-auth-accessdenied' => 'अनुमति नहीं है',
'img-auth-nopathinfo' => 'PATH_INFO मौजूद नहीं है।
आपके सर्वर में इस जानकारी को भेजने के लिए जमाव नहीं है।
यह सी॰जी॰आई-आधारित हो सकता है और img_auth को स्वीकार नहीं करता है।
https://www.mediawiki.org/wiki/Manual:Image_Authorization देखें।',
'img-auth-notindir' => 'अनुरोधित पथ जमाई हुई अपलोड डायरेक्टरी में नहीं है।',
'img-auth-badtitle' => '"$1" से एक वैध शीर्षक बनाने में असमर्थ।',
'img-auth-nologinnWL' => 'आपने सत्रारंभ नहीं किया हुआ है और "$1" श्वेतसूची में नहीं है।',
'img-auth-nofile' => 'फ़ाइल "$1" मौजूद नहीं है।',
'img-auth-isdir' => 'आप डायरेक्टरी "$1" खोलने की कोशिश कर रहे हैं।
केवल फ़ाइल खोली जा सकती है।',
'img-auth-streaming' => '"$1" को स्ट्रीम किया जा रहा है।',
'img-auth-public' => 'img_auth.php निजी विकि से फ़ाइलें प्रदान करने का काम करता है।
यह विकि सार्वजनिक विकि है।
उचित सुरक्षा के लिए img_auth.php को अक्षम है।',
'img-auth-noread' => 'प्रयोक्ता को "$1" पढ़ने का अधिकार नहीं है।',
'img-auth-bad-query-string' => 'यू॰आर॰एल में अवैध क्वेरी स्ट्रिंग है।',

# HTTP errors
'http-invalid-url' => 'अमान्य यू॰आर॰एल: $1',
'http-invalid-scheme' => '"$1" से शुरू होने वाले यू॰आर॰एल स्वीकार्य नहीं हैं।',
'http-request-error' => 'एच॰टी॰टी॰पी अनुरोध अज्ञात त्रुटि के कारण असफल रहा।',
'http-read-error' => 'एच॰टी॰टी॰पी पढ़ने में त्रुटि।',
'http-timed-out' => 'एच॰टी॰टी॰पी अनुरोध का समय समाप्त (टाइम आउट)',
'http-curl-error' => 'यू॰आर॰एल $1 पाने में त्रुटि',
'http-host-unreachable' => 'यू॰आर॰एल तक पहुँचा नहीं जा सका।',
'http-bad-status' => 'एच॰टी॰टी॰पी अनुरोध के दौरान समस्या थी: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'यू॰आर॰एल तक पहुँचा नहीं जा सका।',
'upload-curl-error6-text' => 'प्रदत्त यू॰आर॰एल तक पहुँचा नहीं जा सका।
कृपया एक बार फिर जाँच लें कि यू॰आर॰एल सही है और वह साइट चल रही है।',
'upload-curl-error28' => 'अपलोड टाइम‍आउट',
'upload-curl-error28-text' => 'साइट ने उत्तर देने में बहुत अधिक समय लगा दिया।
कॄपया जाँचें कि साइट चल रही है या नहीं, थोड़ी देर प्रतीक्षा करें और पुनः प्रयास करें।
आप शायद कम भीड़ वाले समय में कोशिश करना चाहेंगे।',

'license' => 'लाइसेन्सिंग:',
'license-header' => 'लाइसेन्सिंग',
'nolicense' => 'कुछ भी नहीं चुना',
'license-nopreview' => '(झलक उपलब्ध नहीं है)',
'upload_source_url' => ' (एक वैध, सभी जगहों से उपलब्ध यू॰आर॰एल)',
'upload_source_file' => ' (आपके कम्प्यूटर से फ़ाइल)',

# Special:ListFiles
'listfiles-summary' => 'इस विशेष पृष्ठ पर सभी अपलोड की गई फ़ाइलों से पता चलता है
जब सदस्य द्वारा फ़िल्टर किया जाता है, तो केवल वो फ़ाइलें दिखाई जती हैं जिनमें सदस्य ने सबसे नवीनतम संस्करण अपलोड किया हो।',
'listfiles_search_for' => 'मीडिया नाम के लिये खोजें:',
'imgfile' => 'फ़ाइल',
'listfiles' => 'फ़ाइल सूची',
'listfiles_thumb' => 'अंगूठाकार',
'listfiles_date' => 'दिनांक',
'listfiles_name' => 'नाम',
'listfiles_user' => 'सदस्य',
'listfiles_size' => 'आकार',
'listfiles_description' => 'विवरण',
'listfiles_count' => 'अवतरण',

# File description page
'file-anchor-link' => 'फ़ाइल',
'filehist' => 'फ़ाइल का इतिहास',
'filehist-help' => 'फ़ाइल पुराने समय में कैसी दिखती थी यह जानने के लिए वांछित दिनांक/समय पर क्लिक करें।',
'filehist-deleteall' => 'सभी हटाएँ',
'filehist-deleteone' => 'हटाएँ',
'filehist-revert' => 'पूर्ववत करें',
'filehist-current' => 'सद्य',
'filehist-datetime' => 'दिनांक/समय',
'filehist-thumb' => 'अंगूठाकार प्रारूप',
'filehist-thumbtext' => '$1 के संस्करण का अंगूठाकार प्रारूप।',
'filehist-nothumb' => 'कोई अंगूठाकार नहीं',
'filehist-user' => 'सदस्य',
'filehist-dimensions' => 'आकार',
'filehist-filesize' => 'फ़ाईल का आकार (बाइट)',
'filehist-comment' => 'टिप्पणी',
'filehist-missing' => 'फ़ाइल मौजूद नहीं है',
'imagelinks' => 'फ़ाइल का उपयोग',
'linkstoimage' => 'निम्नोक्त {{PLURAL:$1|पृष्ठ|$1 पन्नों}} में इस फ़ाइल की कड़ियाँ हैं:',
'linkstoimage-more' => '{{PLURAL:$1|$1}} से अधिक पृष्ठ इस फ़ाइल से जुड़ते हैं।
निम्नोक्त सूची फ़ाइल से जुड़ने वाले {{PLURAL:$1|$1 पृष्ठ|$1 पृष्ठ}} दिखाती है।
[[Special:WhatLinksHere/$2|पूरी सूची]] भी उपलब्ध है।',
'nolinkstoimage' => 'इस फ़ाइल से कोई पृष्ठ नहीं जुड़ते हैं।',
'morelinkstoimage' => 'इस फ़ाइल की [[Special:WhatLinksHere/$1|और कड़ियाँ]] देखें।',
'linkstoimage-redirect' => '$1(फ़ाइल पुनर्निर्देश) $2',
'duplicatesoffile' => 'निम्नोक्त {{PLURAL:$1|फ़ाइल इस फ़ाइल की प्रतिलिपि है|$1 फ़ाइलें इस फ़ाइल की प्रतिलिपियाँ हैं}} ([[Special:FileDuplicateSearch/$2|अधिक जानकारी]]):',
'sharedupload' => 'यह फ़ाइल $1 से है और अन्य परियोजनाओं द्वारा भी प्रयोग की जा सकती है।',
'sharedupload-desc-there' => 'यह फ़ाइल $1 से है और अन्य परियोजनाओं द्वारा भी प्रयोग की जा सकती है।
अधिक जानकारी के लिए कृपया [$2 फ़ाइल विवरण पृष्ठ] देखें।',
'sharedupload-desc-here' => 'यह फ़ाइल $1 से है और अन्य परियोजनाओं द्वारा भी प्रयोग की जा सकती है।
वहाँ पर इसके [$2 फ़ाइल विवरण पृष्ठ] में मौजूद विवरण निम्नोक्त है।',
'filepage-nofile' => 'इस नाम की कोई फ़ाइल मौजूद नहीं है।',
'filepage-nofile-link' => 'इस नाम की कोई फ़ाइल मौजूद नहीं है, पर आप उसे [$1 अपलोड कर सकते हैं]।',
'uploadnewversion-linktext' => 'इस फ़ाइल का नया अवतरण अपलोड करें',
'shared-repo-from' => '$1 से',
'shared-repo' => 'एक साझा भंडार',

# File reversion
'filerevert' => '$1 पूर्ववत करें',
'filerevert-legend' => 'फ़ाइल पूर्ववत करें',
'filerevert-intro' => "आप '''[[Media:$1|$1]]''' के [$4 $2 को $3 बजे के अवतरण] को पूर्ववत कर रहे हैं।",
'filerevert-comment' => 'कारण:',
'filerevert-defaultcomment' => '$1 को $2 बजे के अवतरण को पूर्ववत किया',
'filerevert-submit' => 'पूर्ववत करें',
'filerevert-success' => "'''[[Media:$1|$1]]''' को [$4 $2 को $3 बजे के अवतरण] को पूर्ववत कर दिया गया है।",
'filerevert-badversion' => 'दिये हुए समय से मेल खाने वाला इस फ़ाइल का पुराना अवतरण नहीं है।',

# File deletion
'filedelete' => '$1 हटाएँ',
'filedelete-legend' => 'फ़ाइल हटाएँ',
'filedelete-intro' => "आप फ़ाइल '''[[Media:$1|$1]]''' इतिहास समेत हटाने जा रहे हैं।",
'filedelete-intro-old' => "आप '''[[Media:$1|$1]]''' के [$4 $2 के $3 बजे का अवतरण] हटा रहे हैं।",
'filedelete-comment' => 'कारण:',
'filedelete-submit' => 'हटाएँ',
'filedelete-success' => "'''$1''' को हटा दिया गया है।",
'filedelete-success-old' => "'''[[Media:$1|$1]]''' का $2 को $3 बजे का अवतरण हटा दिया गया है।",
'filedelete-nofile' => "'''$1''' मौजूद नहीं है।",
'filedelete-nofile-old' => "
'''$1''' का आपकी बताई विशेषताओं वाला संग्रहित अवतरण मौजूद नहीं है।",
'filedelete-otherreason' => 'अन्य/अतिरिक्त कारण:',
'filedelete-reason-otherlist' => 'अन्य कारण',
'filedelete-reason-dropdown' => '*हटाने के साधारण कारण
** कॉपीराइट उल्लंघन
** डुप्लिकेट फ़ाइल',
'filedelete-edit-reasonlist' => 'हटाने के कारण बदलें',
'filedelete-maintenance' => 'रखरखाव चल रहा है और रखरखाव के दौरान फ़ाइलों को हटाना और पुनर्स्थापित करना अक्षम है।',
'filedelete-maintenance-title' => 'फ़ाइल हटा नहीं सकते',

# MIME search
'mimesearch' => 'MIME खोज',
'mimesearch-summary' => 'MIME-प्रकारों के अनुसार फ़ाइलें खोजने के लिये इस पृष्ठ का इस्तेमाल किया जा सकता है।
इनपुट: फ़ाइल का प्रकार/उपप्रकार, उदा. <code>image/jpeg</code>.',
'mimetype' => 'MIME प्रकार:',
'download' => 'डाउनलोड',

# Unwatched pages
'unwatchedpages' => 'ध्यान न दिये हुए पृष्ठ',

# List redirects
'listredirects' => 'पुनर्निर्देशनों की सूची',

# Unused templates
'unusedtemplates' => 'अप्रयुक्त साँचे',
'unusedtemplatestext' => 'इस पृष्ठ पर {{ns:template}} नामस्थान वाले वे सभी पृष्ठ इंगित है जो किसी अन्य पृष्ठ में शामिल नहीं हैं।
इन्हें हटाने के पहले इन साँचों की और कड़ियाँ जाँच लें।',
'unusedtemplateswlh' => 'अन्य कड़ियाँ',

# Random page
'randompage' => 'यादृच्छिक पृष्ठ',
'randompage-nopages' => 'कोई भी पृष्ठ {{PLURAL:$2|इस नामस्थान|इन नामस्थानों}} में नहीं हैं: $1।',

# Random redirect
'randomredirect' => 'किसी एक पुनर्निर्देशन पर जाएँ',
'randomredirect-nopages' => 'नामस्थान "$1" में कोई पुनर्निर्देशन नहीं हैं।',

# Statistics
'statistics' => 'आँकड़े',
'statistics-header-pages' => 'पृष्ठ के आँकड़े',
'statistics-header-edits' => 'संपादन के आँकड़े',
'statistics-header-views' => 'आँकड़े देखें',
'statistics-header-users' => 'सदस्य आँकड़े',
'statistics-header-hooks' => 'अन्य आँकड़े',
'statistics-articles' => 'सामग्री पृष्ठ',
'statistics-pages' => 'पृष्ठ',
'statistics-pages-desc' => 'वार्ता पृष्ठ, पुनर्निर्देशन आदि समेत विकि के सभी पृष्ठ।',
'statistics-files' => 'अपलोड की गई फ़ाइलें',
'statistics-edits' => '{{SITENAME}} बनने के बाद से पृष्ठ संपादन',
'statistics-edits-average' => 'प्रति पृष्ठ औसत संपादन',
'statistics-views-total' => 'कुल दृष्य',
'statistics-views-total-desc' => 'अविद्यमान पृष्ठों और विशेष पृष्ठों के लिए दृश्य सम्मिलित नहीं हैं',
'statistics-views-peredit' => 'दृष्य प्रति संपादन',
'statistics-users' => 'पंजीकृत [[Special:ListUsers|सदस्य]]',
'statistics-users-active' => 'सक्रिय सदस्य',
'statistics-users-active-desc' => 'पिछले {{PLURAL:$1|एक दिन|$1 दिनों}} में कुछ गतिविधि रखने वाले सदस्य',
'statistics-mostpopular' => 'सबसे अधिक देखे गए पृष्ठ',

'disambiguations' => 'बहुविकल्पी पृष्ठों से जुड़ते पृष्ठ',
'disambiguationspage' => 'Template:बहुविकल्पी',
'disambiguations-text' => "निम्नांकित पृष्ठ कम-से-कम एक '''बहुविकल्पी पृष्ठ''' से जुड़ते हैं।
संभवतः इन्हें उपयुक्त पृष्ठ से जुड़ा होना चाहिए।<br />
यदि कोई पृष्ठ ऐसे साँचे का प्रयोग करता है जो [[MediaWiki:Disambiguationspage]] से जुड़ा हुआ है, तो उसे बहुविकल्पी पृष्ठ माना जाता है।",

'doubleredirects' => 'दुगुने पुनर्निर्देश',
'doubleredirectstext' => 'यह पृष्ठ उन पृष्ठों की सूची देता है जो अन्य पुनर्निर्देशित पृष्ठों की ओर पुनर्निर्देशित हैं।
हर कतार में पहले और दूसरे पुनर्निर्देशन की कड़ियाँ, तथा दूसरे पुनर्निर्देशन का लक्ष्य भी है, आमतौर पर यही "वास्तविक" लक्ष्यित पृष्ठ होगा, और पहला पुनर्देशन वास्तव में इसी को लक्ष्यित होना चाहिए।
<del>काटी गई</del> प्रविष्टियाँ सुलझा दी गई हैं।',
'double-redirect-fixed-move' => '[[$1]] की जगह बदली जा चुकी है।
अब यह [[$2]] की ओर पुनर्निर्देशित होता है।',
'double-redirect-fixed-maintenance' => '[[$1]] से [[$2]] को दुगुने पुनर्निर्देश को ठीक कर रहा है।',
'double-redirect-fixer' => 'पुनर्निर्देशन मिस्त्री',

'brokenredirects' => 'टूटे हुए पुनर्निर्देशन पृष्ठ',
'brokenredirectstext' => 'निम्नोक्त पुनर्निर्देशन नामौजूद पृष्ठों की ओर ले जाते हैं:',
'brokenredirects-edit' => 'संपादित करें',
'brokenredirects-delete' => 'हटाएँ',

'withoutinterwiki' => 'बिना अंतरविकि कड़ियों वाले पृष्ठ',
'withoutinterwiki-summary' => 'निम्न पृष्ठ अन्य भाषाओं के अवतरणों से नहीं जुड़ते हैं।',
'withoutinterwiki-legend' => 'उपपद',
'withoutinterwiki-submit' => 'दिखायें',

'fewestrevisions' => 'सबसे कम अवतरणों वाले पृष्ठ',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|बाइट|बाइट}}',
'ncategories' => '{{PLURAL:$1|एक श्रेणी|$1 श्रेणियाँ}}',
'ninterwikis' => '$1 अंतरविकी {{PLURAL:$1|कड़ी|कड़ियाँ}}',
'nlinks' => '$1 {{PLURAL:$1|कड़ी|कड़ियाँ}}',
'nmembers' => '$1 {{PLURAL:$1|सदस्य}}',
'nrevisions' => '$1 {{PLURAL:$1|अवतरण}}',
'nviews' => '{{PLURAL:$1|एक|$1}} बार देखा गया है',
'nimagelinks' => '$1 {{PLURAL:$1|पृष्ठ|पृष्ठों}} पर प्रयुक्त',
'ntransclusions' => '$1 {{PLURAL:$1|पृष्ठ|पृष्ठों}} पर प्रयुक्त',
'specialpage-empty' => 'इस ब्यौरे के लिये कोई परिणाम नहीं हैं।',
'lonelypages' => 'एकाकी पृष्ठ',
'lonelypagestext' => 'निम्नोक्त पृष्ठ से न तो {{SITENAME}} के अन्य पृष्ठ जुड़ते हैं और न ही वे किसी और पृष्ठ के अंदर जड़े हुए हैं।',
'uncategorizedpages' => 'अश्रेणीकृत पृष्ठ',
'uncategorizedcategories' => 'अश्रेणीकृत श्रेणियाँ',
'uncategorizedimages' => 'अश्रेणीकृत फ़ाइलें',
'uncategorizedtemplates' => 'अश्रेणीकृत साँचे',
'unusedcategories' => 'अप्रयुक्त श्रेणियाँ',
'unusedimages' => 'अप्रयुक्त फ़ाइलें',
'popularpages' => 'लोकप्रिय पृष्ठ',
'wantedcategories' => 'वांछित श्रेणियाँ',
'wantedpages' => 'जो पृष्ठ चाहिये',
'wantedpages-badtitle' => 'परिणामों में अवैध शीर्षक: $1',
'wantedfiles' => 'वांछित फ़ाइलें',
'wantedfiletext-cat' => 'निम्न फ़ाइलें प्रयुक्त हैं पर मौजूद नहीं हैं। बाहरी भंडारों की फ़ाइलें मौजूद होने के बावजूद सूची में हो सकती हैं। ऐसी कोई भी गलत प्रविष्टियाँ <del>काटी हुई</del> होंगी। साथ ही, जो पृष्ठ ऐसी फ़ाइलों का प्रयोग करते हैं जो मौजूद नहीं हैं, उनकी सूची [[:$1]] में है।',
'wantedfiletext-nocat' => 'निम्न फ़ाइलें प्रयुक्त हैं पर मौजूद नहीं हैं। बाहरी भंडारों की फ़ाइलें मौजूद होने के बावजूद सूची में हो सकती हैं। ऐसी कोई भी गलत प्रविष्टियाँ <del>काटी हुई</del> होंगी।',
'wantedtemplates' => 'वांछित साँचे',
'mostlinked' => 'सर्वाधिक से जुड़े हुए पृष्ठ',
'mostlinkedcategories' => 'सर्वाधिक से जुड़ी हुई श्रेणियाँ',
'mostlinkedtemplates' => 'सर्वाधिक से जुड़े हुए साँचे',
'mostcategories' => 'सर्वाधिक श्रेणियों वाले पृष्ठ',
'mostimages' => 'सर्वाधिक से जुड़ी हुई फ़ाइलें',
'mostinterwikis' => 'सर्वाधिक अंतरविकी कड़ियों वाले पृष्ठ',
'mostrevisions' => 'सर्वाधिक अवतरणित पृष्ठ',
'prefixindex' => 'उपसर्ग अनुसार पृष्ठ',
'prefixindex-namespace' => 'उपसर्ग वाले सभी पृष्ठ ($1 नामस्थान)',
'shortpages' => 'छोटे पृष्ठ',
'longpages' => 'लम्बे पृष्ठ',
'deadendpages' => 'बंद सिरे पृष्ठ',
'deadendpagestext' => 'नीचे दिये पृष्ठ {{SITENAME}} के अन्य पृष्ठों से नहीं जुड़ते हैं।',
'protectedpages' => 'सुरक्षित पृष्ठ',
'protectedpages-indef' => 'केवल अनिश्चितकालीन सुरक्षाएँ',
'protectedpages-cascade' => 'केवल सोपानी सुरक्षा',
'protectedpagestext' => 'नीचे दिये हुए पृष्ठ नाम बदलने या संपादित करने से सुरक्षित हैं',
'protectedpagesempty' => 'इस समय इन नियमों द्वारा कोई पृष्ठ सुरक्षित नहीं हैं।',
'protectedtitles' => 'सुरक्षित शीर्षक',
'protectedtitlestext' => 'निम्नलिखित शीर्षकों पर पृष्ठ नहीं बनाए जा सकते।',
'protectedtitlesempty' => 'इन नियमों द्वारा कोई भी शीर्षक सुरक्षित नहीं हैं।',
'listusers' => 'सदस्यसूची',
'listusers-editsonly' => 'केवल संपादन कर चुके सदस्य दिखाएँ',
'listusers-creationsort' => 'निर्माण तिथि के आधार पर क्रमांकन करें',
'usereditcount' => '$1 {{PLURAL:$1|संपादन|संपादन}}',
'usercreated' => '$1 को $2 बजे बनाया गया, सदस्यनाम $3 है',
'newpages' => 'नए पृष्ठ',
'newpages-username' => 'सदस्यनाम:',
'ancientpages' => 'सबसे पुराने पृष्ठ',
'move' => 'स्थानान्तरण',
'movethispage' => 'पृष्ठ का नाम बदलें',
'unusedimagestext' => 'निम्न फ़ाइलें मौजूद हैं, पर किसी भी पृष्ठ में प्रयुक्त नहीं हैं।
कृपया ध्यान दें कि अन्य वेब साइट एक सीधी कड़ी से फ़ाइल से जुड़ी हो सकती हैं, और सक्रिय उपयोग में होने के बावजूद यहाँ दिखाई जा सकती है।',
'unusedcategoriestext' => 'निम्नलिखित श्रेणी पृष्ठ मौजूद हैं जबकि कोई भी पृष्ठ या अन्य श्रेणियाँ इनका प्रयोग नहीं करते हैं।',
'notargettitle' => 'लक्ष्य नहीं',
'notargettext' => 'इस क्रिया को करने के लिये आपने लक्ष्य पृष्ठ या सदस्य बताया नहीं है।',
'nopagetitle' => 'ऐसा कोई लक्ष्य पृष्ठ नहीं है',
'nopagetext' => 'आपके द्वारा लक्षित पृष्ठ मौजूद नहीं है।',
'pager-newer-n' => '{{PLURAL:$1|नया|नये}} $1',
'pager-older-n' => '{{PLURAL:$1|पुराना|पुराने}} $1',
'suppress' => 'ओवरसाइट',
'querypage-disabled' => 'प्रदर्शन कारणों से यह विशेष पृष्ठ अक्षम किया गया है।',

# Book sources
'booksources' => 'पुस्तकों के स्रोत',
'booksources-search-legend' => 'पुस्तकों के स्रोत खोजें',
'booksources-isbn' => 'आइ॰एस॰बी॰एन:',
'booksources-go' => 'जायें',
'booksources-text' => 'नीचे पुरानी और नई पुस्तकें बेचने वाली वेबसाइटों के एड्रेस हैं, जिसमें आपको आप द्वारा खोजी जाने वाली पुस्तक के बारे में अधिक जानकारी मिल सकती है:',
'booksources-invalid-isbn' => 'यह आइ॰एस॰बी॰एन सही नहीं लग रहा है; मूल स्रोत से नकल करने में हुई त्रुटि के लिए जाँचें।',

# Special:Log
'specialloguserlabel' => 'कर्ता:',
'speciallogtitlelabel' => 'प्रयोजन (शीर्षक):',
'log' => 'लॉग',
'all-logs-page' => 'सभी सार्वजनिक लॉग',
'alllogstext' => '{{SITENAME}} की सभी उपलब्ध लॉगों की प्रविष्टियों का मिला-जुला प्रदर्शन।
आप और बारीकी के लिए लॉग का प्रकार, सदस्य नाम (लघु-दीर्घ-अक्षर संवेदी), या प्रभावित पृष्ठ (लघु-दीर्घ-अक्षर संवेदी) चुन सकते हैं।',
'logempty' => 'लॉग में ऐसी प्रविष्टि नहीं है।',
'log-title-wildcard' => 'इस पाठ से शुरू होने वाले शीर्षक खोजें',
'showhideselectedlogentries' => 'चयनित लॉग प्रविष्टियाँ दिखाएँ/छुपाएँ',

# Special:AllPages
'allpages' => 'सभी पृष्ठ',
'alphaindexline' => '$1 से $2',
'nextpage' => 'अगला पृष्ठ ($1)',
'prevpage' => 'पिछला पृष्ठ ($1)',
'allpagesfrom' => 'इस अक्षर से आरंभ होने वाले पृष्ठ दर्शाएँ:',
'allpagesto' => 'इस अक्षर से समाप्त होने वाले पृष्ठ दिखाएँ:',
'allarticles' => 'सभी पृष्ठ',
'allinnamespace' => 'सभी पृष्ठ ($1 नामस्थान)',
'allnotinnamespace' => 'सभी पृष्ठ ($1 नामस्थान के अलावा)',
'allpagesprev' => 'पिछला',
'allpagesnext' => 'अगला',
'allpagessubmit' => 'जाएँ',
'allpagesprefix' => 'इस उपपद से शुरू होने वाले पृष्ठ दर्शाएँ:',
'allpagesbadtitle' => 'दिया गया शीर्षक अमान्य था या उसमें अंतरभाषीय अथवा अंतरविकी उपसर्ग था।
इसमें संभवतः एक या एक से अधिक ऐसे कैरैक्टर हैं जो शीर्षकों में प्रयुक्त नहीं हो सकते हैं।',
'allpages-bad-ns' => '{{SITENAME}} में "$1" नामस्थान नहीं है।',
'allpages-hide-redirects' => 'पुनर्निर्देश छुपाएँ',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'आप इस पृष्ठ का कैश किया हुआ अवतरण देख रहे हैं, जो $1 पुराना हो सकता है।',
'cachedspecial-viewing-cached-ts' => 'आप इस पृष्ठ का कैश किया हुआ अवतरण देख रहे हैं, जो कि संभवतः वर्तमान अवस्था से भिन्न हो।',
'cachedspecial-refresh-now' => 'नवीनतम देखें।',

# Special:Categories
'categories' => 'श्रेणियाँ',
'categoriespagetext' => 'निम्नोक्त {{PLURAL:$1|श्रेणी|श्रेणियों}} में पृष्ठ या मीडिया है।
जिन श्रेणियों का [[Special:UnusedCategories|अप्रयुक्त श्रेणियाँ]] यहाँ नहीं दिखाई गई हैं।
[[Special:WantedCategories|वांछित श्रेणियाँ]] भी देखें।',
'categoriesfrom' => 'इस अक्षर से शुरू होने वाली श्रेणीयाँ दर्शायें:',
'special-categories-sort-count' => 'संख्यानुसार शक्रमांकित करें',
'special-categories-sort-abc' => 'वर्णानुक्रम के अनुसार दर्शायें',

# Special:DeletedContributions
'deletedcontributions' => 'हटाए गए सदस्य योगदान',
'deletedcontributions-title' => 'हटाए गए सदस्य योगदान',
'sp-deletedcontributions-contribs' => 'योगदान',

# Special:LinkSearch
'linksearch' => 'बाहरी कड़ी खोजें',
'linksearch-pat' => 'खोजने के लिये पाठ:',
'linksearch-ns' => 'नामस्थान:',
'linksearch-ok' => 'खोजें',
'linksearch-text' => '"*.wikipedia.org" जैसे वाईल्ड-कार्ड्स प्रयोग किये जा सकते हैं।
कम-से-कम ".org" जैसे किसी top-level डोमेन की आवश्यकता है।<br />
स्वीकार्य प्रोटोकॉल: <code>$1</code> (इनमें से कोई भी अपनी खोज में न जोड़ें)',
'linksearch-line' => '$2 में से $1 जुडा हुआ हैं',
'linksearch-error' => 'वाईल्डकार्ड्स होस्टनाम के सिर्फ शुरू में आ सकते हैं।',

# Special:ListUsers
'listusersfrom' => 'इस अक्षर से शुरू होने वाले सदस्य दर्शाएँ:',
'listusers-submit' => 'दिखाएँ',
'listusers-noresult' => 'सदस्य नहीं मिला।',
'listusers-blocked' => '(अवरोधित)',

# Special:ActiveUsers
'activeusers' => 'सक्रिय सदस्यों की सूची',
'activeusers-intro' => 'यह सक्रिय सदस्यों की सूची है जिन्होंने पिछले $1 {{PLURAL:$1|दिन|दिनों}} में कुछ गतिविधि करी है।',
'activeusers-count' => '$1 {{PLURAL:$1|सम्पादन}} पिछले $3 {{PLURAL:$3|दिन|दिनों}} में',
'activeusers-from' => 'इस अक्षर से शुरू होने वाले सदस्य दिखाएँ:',
'activeusers-hidebots' => 'बॉट छुपाएँ',
'activeusers-hidesysops' => 'प्रबंधक छुपाएँ',
'activeusers-noresult' => 'कोई सदस्य नहीं मिले।',

# Special:Log/newusers
'newuserlogpage' => 'सदस्य खाता निर्माण लॉग',
'newuserlogpagetext' => 'यह सदस्य खातों के निर्माण का लॉग है।',

# Special:ListGroupRights
'listgrouprights' => 'सदस्य समूह अधिकार',
'listgrouprights-summary' => 'नीचे इसे विकि के लिए परिभाषित सदस्य समूहों की सूची है, साथ में हर समूह से जुड़े अधिकार भी वर्णित हैं।
हर अधिकार के बारे में [[{{MediaWiki:Listgrouprights-helppage}}|अतिरिक्त जानकारी]] भी उपलब्ध है।',
'listgrouprights-key' => '* <span class="listgrouprights-granted">दिए गए अधिकार</span>
* <span class="listgrouprights-revoked">हटाए गए अधिकार</span>',
'listgrouprights-group' => 'समूह',
'listgrouprights-rights' => 'अधिकार',
'listgrouprights-helppage' => 'Help:समूह अधिकार',
'listgrouprights-members' => '(सदस्य सूची)',
'listgrouprights-addgroup' => '{{PLURAL:$2|समूह}} जोड़ें: $1',
'listgrouprights-removegroup' => 'समूह {{PLURAL:$2|हटाएँ}}: $1',
'listgrouprights-addgroup-all' => 'सभी समूह जोड़ें',
'listgrouprights-removegroup-all' => 'सभी समूह हटाएँ',
'listgrouprights-addgroup-self' => 'अपने खाते में {{PLURAL:$2|समूह}} जोड़ें: $1',
'listgrouprights-removegroup-self' => ' अपने  खाते से {{PLURAL:$2|समूह}} हटाएँ: $1',
'listgrouprights-addgroup-self-all' => 'अपने खाते में सभी समूह शामिल करें',
'listgrouprights-removegroup-self-all' => 'अपने खाते से सभी समूह हटाएँ',

# E-mail user
'mailnologin' => 'पाने वाले का एड्रेस दिया नहीं',
'mailnologintext' => 'अन्य सदस्यों को इ-मेल भेजने के लिये [[Special:UserLogin|लॉग इन]] करना आवश्यक है और आपकी [[Special:Preferences|वरीयताओं]] में वैध ई-मेल पता होना आवश्यक है।',
'emailuser' => 'इस सदस्य को ई-मेल भेजें',
'emailuser-title-target' => 'इस {{GENDER:$1|सदस्य|सदस्या}} को ई-मेल करें।',
'emailuser-title-notarget' => 'सदस्य को ई-मेल करें',
'emailpage' => 'सदस्य को ई-मेल करें',
'emailpagetext' => 'नीचे दिए पर्चे को जरिए आप इस {{GENDER:$1|सदस्य}} को ई-मेल भेज सकते हैं।
आपने जो पता [[Special:Preferences|अपनी पसंद]] में दिया था वह इस ई-मेल के "भेजने वाले" के तौर पर आएगा, अतः प्राप्तकर्ता आपको सीधे जवाब दे सकेंगे।',
'usermailererror' => 'मेल ऑब्जेक्ट ने त्रुटि दी:',
'defemailsubject' => '{{SITENAME}} ई-मेल "$1" सदस्य से',
'usermaildisabled' => 'सदस्य ई-मेल अक्षम किया गया',
'usermaildisabledtext' => 'आप इस विकि पर ई-मेल अन्य सदस्यों को ई-मेल नहीं भेज सकते हैं',
'noemailtitle' => 'कोई ई-मेल एड्रेस नहीं',
'noemailtext' => 'इस सदस्य ने वैध ई-मेल पता नहीं दिया है।',
'nowikiemailtitle' => 'ई-मेल की अनुमति नहीं है',
'nowikiemailtext' => 'इस सदस्य ने अन्य सदस्यों से ई-मेल न प्राप्त करने का फ़ैसला लिया हुआ है।',
'emailnotarget' => 'प्राप्तकर्ता के लिए अस्तित्वहीन या अमान्य सदस्यनाम।',
'emailtarget' => 'प्राप्तकर्ता का सदस्यनाम भरें',
'emailusername' => 'सदस्यनाम:',
'emailusernamesubmit' => 'जमा करें',
'email-legend' => 'किसी और {{SITENAME}} सदस्य को ई-मेल भेजें',
'emailfrom' => 'प्रेषक:',
'emailto' => 'प्राप्तकर्ता:',
'emailsubject' => 'विषय:',
'emailmessage' => 'संदेश:',
'emailsend' => 'भेजें',
'emailccme' => 'मेरे ई-मेल की प्रति मुझे भी भेजें।',
'emailccsubject' => 'आपके ई-मेल की प्रति जो $1 को भेजा गया: $2',
'emailsent' => 'ई-मेल भेज दिया गया है।',
'emailsenttext' => 'आपका ई-मेल संदेश भेज दिया गया है।',
'emailuserfooter' => 'यह ई-मेल {{SITENAME}} की "सदस्य ई-मेल" सुविधा द्वारा $1 से $2 को भेजी गई थी।',

# User Messenger
'usermessage-summary' => 'प्रणाली सन्देश छोड़ रहा है।',
'usermessage-editor' => 'सिस्टम दूत',

# Watchlist
'watchlist' => 'मेरी ध्यानसूची',
'mywatchlist' => 'ध्यानसूची',
'watchlistfor2' => '$1 $2 के लिए',
'nowatchlist' => 'आपकी ध्यानसूची में कोई भी पृष्ठ नहीं हैं।',
'watchlistanontext' => 'अपनी ध्यानसूची में मौजूद पृष्ठ देखने या फिर संपादित करने के लिये कॄपया $1 करें।',
'watchnologin' => 'लॉग इन नहीं किया है',
'watchnologintext' => 'ध्यानसूची में बदलाव के लिये [[Special:UserLogin|लॉग इन]] करना आवश्यक है।',
'addwatch' => 'ध्यानसूची में जोड़ें',
'addedwatchtext' => 'आपकी [[Special:Watchlist|ध्यानसूची]] में "[[:$1]]" पृष्ठ जोड़ दिया गया है।
भविष्य में इस पृष्ठ तथा इसके वार्ता पृष्ठ में होने वाले बदलाव आपकी ध्यानसूची में दिखेंगे।',
'removewatch' => 'ध्यानसूची से हटाएँ',
'removedwatchtext' => '"[[:$1]]" नामक पृष्ठ को आपकी [[Special:Watchlist|ध्यानसूची]] से हटा दिया गया है।',
'watch' => 'ध्यान रखें',
'watchthispage' => 'इस पृष्ठ का ध्यान रखें',
'unwatch' => 'ध्यान हटाएँ',
'unwatchthispage' => 'ध्यानसूची से हटाएँ',
'notanarticle' => 'सामग्री पृष्ठ नहीं',
'notvisiblerev' => 'किसी अन्य सदस्य द्वारा किया अन्तिम अवतरण हटाया गया है',
'watchnochange' => 'दिये गये समय में आपके ध्यानसूची में मौजूद पृष्ठों में कोई भी बदलाव नहीं हुए हैं।',
'watchlist-details' => 'वार्ता पृष्ठों के अलावा {{PLURAL:$1|$1 पृष्ठ}} आपकी ध्यानसूची में हैं।',
'wlheader-enotif' => '* ई-मेल नोटिफिकेशन सक्षम हैं।',
'wlheader-showupdated' => "* पृष्ठ जो आपके द्वारा देखे जाने के बाद बदले गये हैं, '''बोल्ड''' दिखेंगे।",
'watchmethod-recent' => 'ध्यानसूची में दिये गये पृष्ठों में हाल में हुए बदलाव देख रहे हैं',
'watchmethod-list' => 'ध्यानसूची में दिये गये पृष्ठों में हाल में हुए बदलाव देख रहे हैं',
'watchlistcontains' => 'आपकी ध्यानसूची में $1 {{PLURAL:$1|पृष्ठ}} हैं।',
'iteminvalidname' => "'$1' के साथ समस्या, अवैध नाम...",
'wlnote' => "$3 को $4 बजे तक पिछले '''$2''' {{PLURAL:$2|घंटे|घंटों}} में {{PLURAL:$1|हुआ एक|हुए '''$1'''}} परिवर्तन निम्न {{PLURAL:$1|है|हैं}}।",
'wlshowlast' => 'पिछले $1 घंटे $2 दिन $3 देखें',
'watchlist-options' => 'ध्यानसूची विकल्प',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'ध्यान दे रहे हैं...',
'unwatching' => 'ध्यान हटा रहे हैं...',
'watcherrortext' => '"$1" के लिये आपकी ध्यानसूची सेटिंग बदलते समय त्रुटि हुई।',

'enotif_mailer' => '{{SITENAME}} सूचना इ-मेल कर्ता',
'enotif_reset' => 'सभी पृष्ठ देखे हुए दर्शाएँ',
'enotif_newpagetext' => 'यह नया पृष्ठ है।',
'enotif_impersonal_salutation' => '{{SITENAME}} सदस्य',
'changed' => 'परिवर्तित किया',
'created' => 'बनाया',
'enotif_subject' => '{{SITENAME}} पृष्ठ $PAGETITLE $PAGEEDITOR ने $CHANGEDORCREATED',
'enotif_lastvisited' => 'आपकी आखिरी भेंट के बाद हुए बदलाव देखने के लिये $1 देखें।',
'enotif_lastdiff' => 'इस बदलाव को देखने के लिये $1 देखें।',
'enotif_anon_editor' => 'अनामक सदस्य $1',
'enotif_body' => 'प्रिय $WATCHINGUSERNAME जी,


{{SITENAME}} का $PAGETITLE पृष्ठ $PAGEEDITDATE को $PAGEEDITOR द्वारा $CHANGEDORCREATED गया, कृपया ताज़े अवतरण के लिए $PAGETITLE_URL देखें।

$NEWPAGE

सम्पादन सारांश: $PAGESUMMARY $PAGEMINOREDIT

संपादक से संपर्क करें:
ई-मेल: $PAGEEDITOR_EMAIL
विकि: $PAGEEDITOR_WIKI

जब तक आप इस पृष्ठ पर फिर से नहीं जाते, तब तक और बदलाव होने पर भी आपको फिर से सूचना नहीं भेजी जाएगी।
आप चाहें तो अपनी ध्यानसूची में मौजूद सभी पन्नों के लिए सूचना चिन्ह को भी बदल सकते हैं।

आपकी सहायिका, {{SITENAME}} की सूचक प्रणाली

--
अपनी ई-मेल सूचना के जमाव बदलने के लिये देखें
{{canonicalurl:{{#special:Preferences}}}}

अपनी ध्यानसूची के जमाव बदलने के लिए देखें
{{canonicalurl:{{#special:EditWatchlist}}}}

इस पृष्ठ को अपनी ध्यानसूची से हटाने के लिये देखें
$UNWATCHURL

राय देने या अधिक सहायता पाने के लिए:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'पृष्ठ हटाएँ',
'confirm' => 'सुनिश्चित करें',
'excontent' => "पाठ था: '$1'",
'excontentauthor' => "पाठ था: '$1' (और सिर्फ '[[Special:Contributions/$2|$2]]' का योगदान था।)",
'exbeforeblank' => "खाली करने से पहले पाठ था: '$1'",
'exblank' => 'पृष्ठ खाली था',
'delete-confirm' => '"$1" को हटाएँ',
'delete-legend' => 'हटाएँ',
'historywarning' => "''' चेतावनी: ''' आप जो पृष्ठ हटाने जा रहे हैं उसके इतिहास में लगभग $1 {{PLURAL:$1|अवतरण}} हैं:",
'confirmdeletetext' => 'आप एक पृष्ठ को उसके सभी अवतरणों सहित हटाने जा रहे हैं।
जाँच लें कि आप ये करना चाहते हैं, आप इसके पर्निआमों से अवगत हैं, और आप ये [[{{MediaWiki:Policy-url}}|नीति]] के अनुसार कर रहे हैं।',
'actioncomplete' => 'कार्य पूर्ण',
'actionfailed' => 'क्रिया विफल',
'deletedtext' => '"$1" को हटाया गया है।
हाल में हटाये गये पृष्ठों की सूची के लिये $2 देखें।',
'dellogpage' => 'हटाने का लॉग',
'dellogpagetext' => 'नीचे हाल में हटाए गये पृष्ठों की सूची है।',
'deletionlog' => 'हटाने का लॉग',
'reverted' => 'पुराने अवतरण को पूर्ववत किया',
'deletecomment' => 'कारण:',
'deleteotherreason' => 'अन्य/अतिरिक्त कारण:',
'deletereasonotherlist' => 'अन्य कारण',
'deletereason-dropdown' => '*हटाने के सामान्य कारण
** लेखक की बिनती
** कॉपीराइट उल्लंघन
** बर्बरता',
'delete-edit-reasonlist' => 'हटाने के कारण संपादित करें',
'delete-toobig' => 'इस पृष्ठ का संपादन इतिहास $1 से अधिक {{PLURAL:$1|अवतरण}} होने की वजह से बहुत बड़ा है।
{{SITENAME}} के अनपेक्षित रूप से बंद होने से रोकने के लिये ऐसे पृष्ठों को हटाने की अनुमति नहीं है।',
'delete-warning-toobig' => 'इस पृष्ठ का संपादन इतिहास $1 से अधिक {{PLURAL:$1|अवतरण}} होने की वजह से बहुत बड़ा है।
इसे हटाने से {{SITENAME}} के डाटाबेस की गतिविधियों में व्यवधान आ सकता है;
कृपया सोच समझ कर आगे बढ़ें।',

# Rollback
'rollback' => 'संपादन वापिस लें',
'rollback_short' => 'वापिस लें',
'rollbacklink' => 'वापिस लें',
'rollbacklinkcount' => '$1 {{PLURAL:$1|सम्पादन}} वापिस लें',
'rollbacklinkcount-morethan' => '$1 से अधिक {{PLURAL:$1|सम्पादन}} वापिस लें',
'rollbackfailed' => 'वापिस लेना असफल रहा',
'cantrollback' => 'पुराने अवतरण को पूर्ववत नहीं कर सकते हैं;
इस पृष्ठ का अन्तिम योगदानकर्ता इस लेख का एकमात्र लेखक है।',
'alreadyrolled' => '[[User:$2|$2]] ([[User talk:$2|वार्ता]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) द्वारा किए गए  [[:$1]] के पिछले संपादन को वापिस पुरानी स्थिति पर नहीं लाया जा सकता है;
किसी और ने इस बीच या तो इस पृष्ठ को फिर से संपादित कर दिया है या पहले ही पृष्ठ पुरानी स्थिति पर लाया जा चुका है।

इस पृष्ठ का अन्तिम संपादन [[User:$3|$3]] ([[User talk:$3|वार्ता]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) ने किया है।',
'editcomment' => "संपादन सारांश था: \"''\$1''\"।",
'revertpage' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]]) के संपादनों को हटाकर [[User:$1|$1]] के अन्तिम अवतरण को पूर्ववत किया',
'revertpage-nouser' => '(सदस्य नाम हटाया गया है) द्वारा किए गए संपादन को वापिस पुरानी स्थिति में ला कर इसके पहले के [[User:$1|$1]] द्वारा बने अवतरण को फिर से ताज़ा अवतरण बनाया।',
'rollback-success' => '$1 के संपादन हटाए;
$2 द्वारा संपादित अन्तिम अवतरण को पुनर्स्थापित किया।',

# Edit tokens
'sessionfailure-title' => 'सत्र विफलता',
'sessionfailure' => 'ऐसा प्रतीत होता है कि आपके लॉगिन सत्र के साथ कोई समस्या है।
सत्र अपहरण से बचाने के लिए सावधानी के तौर पर आपका यह क्रियाकलाप रद्द कर दिया गया है।
कृपया पीछे जाएँ और पृष्ठ को पुनः लोड करें, तब दुबारा कोशिश करें।',

# Protect
'protectlogpage' => 'सुरक्षा लॉग',
'protectlogtext' => 'नीचे पृष्ठ सुरक्षा में हुए बदलावों की सूची है।
वर्तमान सुरक्षित पृष्ठों की सूची के लिए [[Special:ProtectedPages|सुरक्षित पृष्ठों की सूची]] देखें।',
'protectedarticle' => '"[[$1]]" सुरक्षित कर दिया',
'modifiedarticleprotection' => '"[[$1]]" के सुरक्षा-स्तर को बदला',
'unprotectedarticle' => '"[[$1]]" से सुरक्षा हटा दी गई',
'movedarticleprotection' => 'सुरक्षा स्तर "[[$2]]" से बदल कर  "[[$1]]" कर दिया गया है',
'protect-title' => '"$1" का सुरक्षा स्तर बदलें',
'protect-title-notallowed' => '"$1" का सुरक्षा स्तर देखें',
'prot_1movedto2' => '[[$1]] का नाम बदलकर [[$2]] कर दिया गया है',
'protect-badnamespace-title' => 'सुरक्षाहीन नामस्थान',
'protect-badnamespace-text' => 'इस नामस्थान में पृष्ठ सुरक्षित नहीं किये जा सकते हैं।',
'protect-legend' => 'सुरक्षा निर्धारित करें',
'protectcomment' => 'कारण:',
'protectexpiry' => 'समाप्ति:',
'protect_expiry_invalid' => 'समाप्ती समय गलत है।',
'protect_expiry_old' => 'समाप्ती समय बीत चुका है।',
'protect-unchain-permissions' => 'अन्य सुरक्षा विकल्प खोलें',
'protect-text' => "'''$1''' पृष्ठ का सुरक्षा-स्तर आप यहाँ देख सकते हैं और उसे बदल भी सकते हैं।",
'protect-locked-blocked' => "आप बाधित होने की स्थिति में सुरक्षा स्थर में परिवर्तन नहीं कर सकते।
पृष्ठ '''$1''' की वर्तमान स्थिति यह है:",
'protect-locked-dblock' => "डेटाबेस में सक्रिय लॉक होने की वजह से सुरक्षा स्तर में कोई परिवर्तन नहीं किया जा सकता।
पृष्ठ '''$1''' की वर्तमान स्थिति यह है:",
'protect-locked-access' => "आपको इस पृष्ठ का सुरक्षा-स्तर बदलने की अनुमति नहीं है।
'''$1''' का वर्तमान सुरक्षा-स्तर यह है:",
'protect-cascadeon' => 'यह पृष्ठ अभी सुरक्षित है क्योंकि यह {{PLURAL:$1|इस पृष्ठ की|इन पृष्ठों की}} सुरक्षा-सीढ़ी में है। आप इस पृष्ठ का सुरक्षा-स्तर बदल सकते हैं, पर उससे सुरक्षा-सीढ़ी में बदलाव नहीं होंगे।',
'protect-default' => 'सभी सदस्यों को अनुमति दें',
'protect-fallback' => '"$1" अधिकार आवश्यक है',
'protect-level-autoconfirmed' => 'नए व अपंजीकृत सदस्यों को रोकें',
'protect-level-sysop' => 'केवल प्रबन्धक',
'protect-summary-cascade' => 'सीढ़ी',
'protect-expiring' => 'समाप्ती $1 (UTC)',
'protect-expiring-local' => 'समाप्ती $1',
'protect-expiry-indefinite' => 'अनिश्चितकालीन',
'protect-cascade' => 'इस पृष्ठ से जुड़े हुए पृष्ठ सुरक्षित करें (सुरक्षा-सीढ़ी)',
'protect-cantedit' => 'आप इस पृष्ठ का सुरक्षा-स्तर बदल नहीं सकते क्योंकि आपको ऐसा करने का अधिकार नहीं है।',
'protect-othertime' => 'अन्य समय:',
'protect-othertime-op' => 'अन्य समय',
'protect-existing-expiry' => 'मौजूदा सुरक्षा सम्पात होने का समय: $3, $2',
'protect-otherreason' => 'अन्य/अतिरिक्त कारण:',
'protect-otherreason-op' => 'अन्य कारण',
'protect-dropdown' => '*सुरक्षा के आम कारण
**अत्यधिक बर्बरता 
**अत्यधिक स्पैम
**अफलदायी सम्पादन युद्ध
**अधिक यातायात वाला पृष्ठ',
'protect-edit-reasonlist' => 'सुरक्षा के कारण बदलें',
'protect-expiry-options' => '१ घंटा:1 hour,१ दिन:1 day,१ सप्ताह:1 week,२ सप्ताह:2 weeks,१ महीना:1 month,३ महीने:3 months,६ महीने:6 months,१ साल:1 year,हमेशा के लिए:infinite',
'restriction-type' => 'अधिकार:',
'restriction-level' => 'सुरक्षा-स्तर:',
'minimum-size' => 'न्यूनतम आकार',
'maximum-size' => 'अधिकतम आकार:',
'pagesize' => '(बाइट)',

# Restrictions (nouns)
'restriction-edit' => 'संपादन',
'restriction-move' => 'स्थानांतरण',
'restriction-create' => 'बनाएँ',
'restriction-upload' => 'अपलोड',

# Restriction levels
'restriction-level-sysop' => 'पूर्ण सुरक्षित',
'restriction-level-autoconfirmed' => 'अर्ध सुरक्षित',
'restriction-level-all' => 'कोई भी स्तर',

# Undelete
'undelete' => 'हटाया पृष्ठ देखें',
'undeletepage' => 'हटाए गए पृष्ठ देखें और पुनर्स्थापित करें',
'undeletepagetitle' => "'''नीचे [[:$1|$1]] के हटाए गए अवतरण दर्शाए गये हैं।'''",
'viewdeletedpage' => 'हटाए गए पृष्ठ देखें',
'undeletepagetext' => 'निम्न {{PLURAL:$1|$1 पृष्ठ|$1 पृष्ठों}} को हटा दिया गया है, लेकिन अभी ये लेखागार में हैं और पुनर्स्थापित किये जा सकते हैं।
लेखागार समय-समय पर साफ किये जाते हैं।',
'undelete-fieldset-title' => 'अवतरण पुरानी स्थिति पर लाएँ',
'undeleteextrahelp' => "पृष्ठ का संपूर्ण इतिहास वापस लाने के लिए सभी बक्सों से सही का निशान हटा दें और '''''{{int:undeletebtn}}''''' पर क्लिक करें।
चुनिंदा इतिहास को वापस लाने के लिए उन अवतरणों के बगल के बक्सों पर सही का निशान लगाएँ और '''''{{int:undeletebtn}}''''' पर क्लिक करें।",
'undeleterevisions' => '$1 {{PLURAL:$1|अवतरण}} लेखागार में हैं',
'undeletehistory' => 'यदि आप पृष्ठ को पुनर्स्थापित करते हैं तो सभी अवतरण इतिहास में पुनर्स्थापित हो जायेंगे।
हटाने के बाद यदि एक नया पृष्ठ उसी नाम से बनाया गया है तो पुनर्स्थापित अवतरण पिछले इतिहास में दर्शित होंगे।',
'undeleterevdel' => 'यदि पुनर्स्थापन के फलस्वरूप शीर्ष पृष्ठ या फ़ाइल अवतरण आंशिक रूप से मिट सकता है, तो इसे नहीं किया जायेगा।
ऐसी स्थिति में, आपको नवीनतम मिटाए गए अवतरण को बिना सही के निशान लगाये हुए या बिना छुपाये रखना होगा।',
'undeletehistorynoadmin' => 'यह पृष्ठ निकाल दिया गया है।
निकाले जाने का कारन नीचे सारांश में दिया गया है, और साथ ही उन सदस्यों के बारे में विस्तार भी दिया गया है, जिन्होंने निकाले जाने से पहले इस पृष्ठ को संपादित किया है।
इन हटाये गए अवतरणों के विद्यमान विषय वस्तु केवल प्रशासकों को ही उपलब्ध है।',
'undelete-revision' => '$1 ($4 को $5 बजे $3 द्वारा बनाया गया) का मिटाया हुआ संस्करण:',
'undeleterevision-missing' => 'अमान्य अथवा अनुपस्थित अवतरण।
या तो आप ग़लत सम्पर्क प्रयोग कर रहे हैं, या यह अवतरण पुनर्स्थापित किया जा चुका है, अथवा इसे लेखागार से निकाल दिया गया है।',
'undelete-nodiff' => 'पुरान अवतरण नहीं हैं।',
'undeletebtn' => 'वापस ले आयें',
'undeletelink' => 'देखें/पुरानी स्थिति पर लाएँ',
'undeleteviewlink' => 'देखें',
'undeletereset' => 'पूर्ववत करें',
'undeleteinvert' => 'चुनाव उलटें',
'undeletecomment' => 'टिप्पणी हटाना',
'undeletedrevisions' => '{{PLURAL:$1|एक रूपान्तर वापस लाया गया|$1 रूपान्तर वापस लाये गये}} है',
'undeletedrevisions-files' => '{{PLURAL:$1|1 अवतरण|$1 अवतरण}} और {{PLURAL:$2|1 फ़ाईल|$2 फ़ाइलें}} पुनर्स्थापित कर दियें',
'undeletedfiles' => '{{PLURAL:$1|1 फ़ाईल|$1 फ़ाईलें}} पुनर्स्थापित',
'cannotundelete' => 'पुनर्स्थापित नहीं कर सकें;
किसी और ने पहले ही पुनर्स्थापित कर दिया हों।',
'undeletedpage' => "'''$1 को पुनर्स्थापित कर दिया गया है'''

हाल में हटाये गये तथा पुनर्स्थापित किये गए पन्नों की जानकारी के लिये [[Special:Log/delete|हटाने की लॉग]] देखें।",
'undelete-header' => 'हाल में हटाये गये पृष्ठ देखने के लियें [[Special:Log/delete|हटाने की सूची]] देखें।',
'undelete-search-title' => 'हटाये गये पृष्ठ खोज़ें',
'undelete-search-box' => 'हटायें गयें पृष्ठ खोजें',
'undelete-search-prefix' => 'से शुरु होने पृष्ठ दर्शायें:',
'undelete-search-submit' => 'खोजें',
'undelete-no-results' => 'हटायें गयें पन्नोंके आर्चिव्हमें मेल खाने वाले पृष्ठ मिले नहीं।',
'undelete-filename-mismatch' => '$1 समयके फ़ाइलके हटाये गये अवतरणको पुनर्स्थापित नहीं किया जा सकता: फ़ाईल का नाम मेल नहीं खाता',
'undelete-bad-store-key' => '$1 समयका फ़ाईल अवतरण पुनर्स्थापित नहीं कर सकतें हैं: हटाने से पहले फ़ाईल अस्तित्वमें नहीं थी।',
'undelete-cleanup-error' => 'इस्तेमालमें न लाई गई "$1" आर्चिव्ह फ़ाईल हटाने में समस्या हुई हैं।',
'undelete-missing-filearchive' => 'सिचिका पुरालेख क्रमांक $1 को पुनर्स्थापित करने में असक्षम हैं, क्योंकि यह आँकड़ाकोष में उपलब्ध नहीं है।
या ऐसा भी हो सकता है कि इसे पहले से ही पुनर्स्थापित किया जा चुका हो।',
'undelete-error' => 'पृष्ठ अविलोपन में त्रुटि',
'undelete-error-short' => 'फ़ाईल पुनर्स्थापित करने में समस्या: $1',
'undelete-error-long' => 'फ़ाईल पुनर्स्थापित करने में आई हुई समस्याएं:

$1',
'undelete-show-file-confirm' => 'क्या आप वाकई फ़ाइल "<nowiki>$1</nowiki>" के $2 को $3 बजे बने, हटाए जा चुके अवतरण को देखना चाहते हैं?',
'undelete-show-file-submit' => 'हाँ',

# Namespace form on various pages
'namespace' => 'नामस्थान:',
'invert' => 'विपरीत प्रवरण',
'tooltip-invert' => 'चयनित नामस्थान (और संबद्ध नामस्थान यदि जाँच) के भीतर पृष्ठों में किए गए परिवर्तन छुपाने के लिए इस बक्से को चिह्नित करें',
'namespace_association' => 'सम्बद्ध नामस्थान',
'tooltip-namespace_association' => 'भी बात या विषय नाम स्थान चयनित नाम स्थान के साथ जुड़े को शामिल करने के लिए इस बक्से को चिह्नित करें।',
'blanknamespace' => '(मुख्य)',

# Contributions
'contributions' => 'सदस्य योगदान',
'contributions-title' => '$1 के योगदान',
'mycontris' => 'योगदान',
'contribsub2' => '$1 के लिये ($2)',
'nocontribs' => 'इन कसौटियों से मिलनेवाले बदलाव मिले नहीं।',
'uctop' => '(उपर)',
'month' => 'इस महिनेसे (और पुरानें):',
'year' => 'इस सालसे (और पुराने):',

'sp-contributions-newbies' => 'सिर्फ़ नये सदस्यों के योगदान दर्शायें',
'sp-contributions-newbies-sub' => 'नये सदस्योंके लिये',
'sp-contributions-newbies-title' => 'नए सदस्यों द्वारा योगदान',
'sp-contributions-blocklog' => 'ब्लॉक सूची',
'sp-contributions-deleted' => 'सदस्यों को योगदान जो हटाए जा चुके हैं',
'sp-contributions-uploads' => 'अपलोड',
'sp-contributions-logs' => 'चिट्ठे',
'sp-contributions-talk' => 'वार्ता',
'sp-contributions-userrights' => 'सदस्य अधिकार प्रबंधन',
'sp-contributions-blocked-notice' => 'यह सदस्य फ़िलहाल अवरोधित हैं। सदंर्भ के लिए ताज़ातरीन अवरोध चिट्ठा प्रविष्टि नीचे है:',
'sp-contributions-blocked-notice-anon' => 'यह आईपी पता अभी अवरोधित है। 
नवीनतम अवरोध अभिलेख प्रविष्टि सन्दर्भ के लिए नीचे दी गई है:',
'sp-contributions-search' => 'योगदान के लिये खोज',
'sp-contributions-username' => 'आईपी एड्रेस या सदस्यनाम:',
'sp-contributions-toponly' => 'केवल उन सम्पादनों को दिखाएँ जो नवीनतम संशोधन हैं',
'sp-contributions-submit' => 'खोजें',

# What links here
'whatlinkshere' => 'यहाँ के हवाले कहाँ कहाँ हैं',
'whatlinkshere-title' => '$1 से जुड़े हुए पृष्ठ',
'whatlinkshere-page' => 'पृष्ठ:',
'linkshere' => "नीचे दिये हुए पृष्ठ '''[[:$1]]''' से जुडते हैं:",
'nolinkshere' => "'''[[:$1]]''' को कुछभी जुडता नहीं हैं ।",
'nolinkshere-ns' => "चुने हुए नामस्थानसे '''[[:$1]]''' को जुडने वाले पृष्ठ नहीं हैं।",
'isredirect' => 'पुनर्निर्देशन पृष्ठ',
'istemplate' => 'मिलाईयें',
'isimage' => 'फ़ाइल प्रयोग',
'whatlinkshere-prev' => '{{PLURAL:$1|पिछला|पिछले $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|अगला|अगले $1}}',
'whatlinkshere-links' => '← कड़ियाँ',
'whatlinkshere-hideredirs' => '$1 पुनर्निर्देश',
'whatlinkshere-hidetrans' => '$1 ट्रान्स्क्ल्युजन्स',
'whatlinkshere-hidelinks' => '$1 कड़ियाँ',
'whatlinkshere-hideimages' => '$1 फ़ाइल लिंक',
'whatlinkshere-filters' => 'छन्ने',

# Block/unblock
'autoblockid' => 'स्वतः अवरोध #$1',
'block' => 'उपयोक्ता को अवरोधित करें।',
'unblock' => 'उपयोक्ता पर अवरोधण हटाएँ',
'blockip' => 'अवरोधित करें',
'blockip-title' => 'ब्लॉक उपयोगकर्ता',
'blockip-legend' => 'सदस्य को ब्लॉक करें',
'blockiptext' => 'विशिष्ठ IP पते अथवा सदस्य नाम को लिखने के अधिकार से बाध्य करने के लिए निम्न पत्र का प्रयोग करें।
यह सिर्फ बर्बरता को रोकने के लिए ही किया जाना चाहिए, और [[{{MediaWiki:Policy-url}}|नीति]] के अनुसार ही करना चाहिए।
नीचे विशिष्ठ कारण भी लिखें (उदाहरण के लिए, सटीक पृष्ठों को दर्शाते हुए, जिनमें बर्बरता की गई हो)।',
'ipadressorusername' => 'आईपी एड्रेस या सदस्यनाम:',
'ipbexpiry' => 'समाप्ति:',
'ipbreason' => 'कारण:',
'ipbreasonotherlist' => 'दूसरा कारण',
'ipbreason-dropdown' => '*अवरोधित करने के साधारण कारण
** अवैध सदस्यनाम
** एक से अधिक खातें खोलकर उनका दुरुपयोग करना
** गलत जानकारी भरना
** पृष्ठों में कचरा भरना
** पृष्ठों से सामग्री हटाना‍‍‍‍‍
** बाहरी जालस्थलों की फ़ालतू कड़ियां देना 
** सदस्यों को तंग करना',
'ipb-hardblock' => 'सत्राराम्भित प्रयोक्ताओं को इस आईपी पते का सम्पादन करने से रोकें',
'ipbcreateaccount' => 'खाते का निर्माण रोकें',
'ipbemailban' => 'सदस्य को इ-मेल भेजने से रोकें',
'ipbenableautoblock' => 'इस सदस्यद्वारा इस्तेमाल किया गया आखिरी आईपी एड्रेस और यहां से आगे इस सदस्य द्वारा इस्तेमालमें लाये जाने वाले सभी एड्रेस ब्लॉक करें।',
'ipbsubmit' => 'इस सदस्य को और बदलाव करने से रोकें',
'ipbother' => 'अन्य समय:',
'ipboptions' => '२ घंटे:2 hours,१ दिन:1 day,३ दिन:3 days,१ हफ्ता:1 week,२ हफ्ते:2 weeks,१ महिना:1 month,३ महिने:3 months,६ महिने:6 months,१ साल:1 year,हमेशा के लिये:infinite',
'ipbotheroption' => 'अन्य',
'ipbotherreason' => 'अन्य/दूसरा कारण:',
'ipbhidename' => 'संपादन व सूचियों से सदस्य नाम छिपाएँ',
'ipbwatchuser' => 'इस सदस्य के सदस्य तथा वार्ता पृष्ठ पर ध्यान रखें',
'ipb-disableusertalk' => 'इस प्रयोक्ता को अवरुद्ध होने पर स्वयं का वार्ता पृष्ठ सम्पादन करने से रोकें',
'ipb-change-block' => 'इन जमावों के साथ सदस्य को फिर से अवरोधित करें',
'ipb-confirm' => 'अवरोधण की पुष्टि करें',
'badipaddress' => 'अमान्य आईपी पता।',
'blockipsuccesssub' => 'अवरोधन सफल ।(संपादन करने से रोक दिया गया है)',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] को ब्लॉक किया जा चुका है।<br />
ब्लॉकों की समीक्षा के लिए [[Special:BlockList|ब्लॉक लॉग]] देखें।',
'ipb-blockingself' => 'आप खुद को अवरोधित कर रहे हैं! क्या आप वाकई ऐसा करना चाहते हैं?',
'ipb-edit-dropdown' => 'ब्लॉक कारण संपादित करें',
'ipb-unblock-addr' => '$1 को अनब्लॉक करें',
'ipb-unblock' => 'सदस्य अथवा आईपी एड्रेस को अनब्लॉक करें',
'ipb-blocklist' => 'सद्य ब्लॉक देखें',
'ipb-blocklist-contribs' => '$1 के लिए योगदान',
'unblockip' => 'सदस्य को अनब्लॉक करें',
'unblockiptext' => 'पहले ब्लॉक किये हुए आईपी एड्रेस या सदस्यनाम को अनब्लॉक करने के लिये नीचे दिया गया फार्म भरें।',
'ipusubmit' => 'यह अवरोध हटाएँ',
'unblocked' => '[[User:$1|$1]] को अनब्लॉक कर दिया हैं',
'unblocked-range' => '$1 को अनवरोधित किया गया',
'unblocked-id' => 'अवरोध $1 निकाल दिया गया है',
'blocklist' => 'अवरोधित उपयोक्ता',
'ipblocklist' => 'अवरोधित आईपी पते व सदस्यनाम',
'ipblocklist-legend' => 'अवरोधित सदस्य को खोजें',
'blocklist-userblocks' => 'खाते के अवरोध छिपाएं',
'blocklist-tempblocks' => 'अस्थाई अवरोध छिपाएं',
'blocklist-addressblocks' => 'एकल आईपी अवरोध छिपाएं',
'blocklist-rangeblocks' => 'श्रेणी ब्लॉक छुपाएँ',
'blocklist-timestamp' => 'टाइमस्टैम्प',
'blocklist-target' => 'लक्ष्य',
'blocklist-expiry' => 'अवसान',
'blocklist-by' => 'प्रशासक अवरुद्ध',
'blocklist-params' => 'अवरोध मापदण्ड',
'blocklist-reason' => 'कारण',
'ipblocklist-submit' => 'खोजें',
'ipblocklist-localblock' => 'स्थानीय अवरोध',
'ipblocklist-otherblocks' => 'आईपी ब्लॉक सूची से अन्य ब्लॉकों',
'infiniteblock' => 'इनफाईनाईट',
'expiringblock' => '$1 को $2 बजे मियाद खत्म होती है',
'anononlyblock' => 'केवल अनाम सदस्य',
'noautoblockblock' => 'स्वयंचलित ब्लॉक रद्द किये हैं',
'createaccountblock' => 'खाते निर्माण को रोक दिया हैं',
'emailblock' => 'ईमेल अवरोधित',
'blocklist-nousertalk' => 'अपना वार्ता पृष्ठ भी संपादित नहीं कर सकेंगे',
'ipblocklist-empty' => 'ब्लॉक सूची खाली हैं।',
'ipblocklist-no-results' => 'पूछे गये आईपी एड्रेस / सदस्यनाम पर ब्लॉक नहीं हैं।',
'blocklink' => 'अवरोधित करें',
'unblocklink' => 'अवरोध हटाएँ',
'change-blocklink' => 'विभाग बदलें',
'contribslink' => 'योगदान',
'emaillink' => 'ई-मेल भेजें',
'autoblocker' => 'आपका IP पता स्वत: बाध्य है, जो की हाल ही में "[[User:$1|$1]]" द्वारा प्रयोग किया गया है।
$1 को बाध्य करने का कारण है: "$2"',
'blocklogpage' => 'ब्लॉक सूची',
'blocklog-showlog' => 'इस प्रयोक्ता को पहले भी अवरोधित किया जा चुका है। 
सन्दर्भ के लिए अवरोधन अभिलेख नीचे दिया गया है:',
'blocklog-showsuppresslog' => 'यह प्रयोक्ता पहले भी अवरोधित किया जा चुका है।
यह दबाया गया लॉग सन्दर्भ के लिए उपलब्ध कया गया है:',
'blocklogentry' => '"[[$1]]" को $2 $3 तक बदलाव करने से रोक दिया गया है।',
'reblock-logentry' => '[[$1]] का अवरोध जमाव बदला गया, मियाद अब $2 $3 पर खत्म होगी',
'blocklogtext' => 'यह सदस्यों को ब्लॉक एवं अनब्लॉक करने के कार्यों का लॉग है।
स्वतः बाधित होने वाले IP पते इस सूची में उपलब्ध नहीं है।
वर्तमान में क्रियाशील प्रतिबंधों और ब्लॉकों की सूची के लिए [[Special:BlockList|ब्लॉक लॉग]] देखें।',
'unblocklogentry' => '$1 अनवरोधित',
'block-log-flags-anononly' => 'केवल अनाम सदस्य',
'block-log-flags-nocreate' => 'खाता निर्माण पर रोक',
'block-log-flags-noautoblock' => 'ऑटोब्लॉक बंद हैं',
'block-log-flags-noemail' => 'ई-मेल अवरुद्ध',
'block-log-flags-nousertalk' => 'अपना वार्ता पृष्ठ नहीं बदल सकते हैं',
'block-log-flags-angry-autoblock' => 'उन्नत स्व-अवरोध लागू',
'block-log-flags-hiddenname' => 'सदस्य नाम छिपा हुआ',
'range_block_disabled' => 'प्रबंधकोंको अब रेंज ब्लॉक करने की अनुमति नहीं हैं।',
'ipb_expiry_invalid' => 'अवैध समाप्ति कालावधी।',
'ipb_expiry_temp' => 'छुपायें हुए सदस्यनाम ब्लॉक्स हमेशा के लिये होने चाहिये।',
'ipb_hide_invalid' => 'इस खाते को छिपा नहीं पाए; संभव है कि इसमें बहुत अधिक संपादन हुए हों।',
'ipb_already_blocked' => '"$1" को पहलेसे ब्लॉक हैं',
'ipb-needreblock' => '$1 पहले ही अवरोधित है।
क्या आप अवरोध के जमाव बदलना चाहेंगे?',
'ipb-otherblocks-header' => 'अन्य  {{PLURAL:$1| block|blocks}}',
'unblock-hideuser' => 'आप इस प्रयोक्ता को अनवरोधित नहीं कर सकते, क्योंकि इनका प्रयोक्तानाम छिपा हुआ है।',
'ipb_cant_unblock' => 'समस्या: ब्लॉक ID $1 मिला नहीं। इसे पहले अनब्लॉक कर दिया गया हो सकता हैं।',
'ipb_blocked_as_range' => 'गलती: $1 यह आइपी सीधे बाधित नहीं है और अबाध्य नहीं किया जा सकता।
फिर भी, $2 प्रकार को बाध्य किया जा सकता है, जिनको अबाध्य किया जा सकता है।',
'ip_range_invalid' => 'गलत आईपी रेंज',
'ip_range_toolarge' => '/$1 से अधिक बड़े रेञ्ज ब्लॉकों की अनुमति नहीं है।',
'blockme' => 'मुझे ब्लॉक करो',
'proxyblocker' => 'प्रॉक्सी ब्लॉकर',
'proxyblocker-disabled' => 'यह कार्य रद्द कर दिया गया हैं।',
'proxyblockreason' => 'आपका IP पता बाधित किया जा चुका है क्योंकि यह एक मुक्त प्रतिनिधि है।
कृपया आप अपने इंटरनेट सेवा प्रदान करने वाले से या तकनीकी सहायक से सम्पर्क करें अथवा उन्हें इस भयावह सुरक्षा समस्या के बारे में सूचित करें।',
'proxyblocksuccess' => 'हो गया।',
'sorbsreason' => '{{SITENAME}} द्वारा इस्तेमालमें लाये जाने वाले DNSBL में आपके आईपी एड्रेसको ओपन प्रॉक्सीमें दर्शाया गया हैं।',
'sorbs_create_account_reason' => '{{SITENAME}} के DNSBL ने आपका आईपी एड्रेस ओपन प्रोक्सी करके सूचित किया हैं। आप खाता खोल नहीं सकतें।',
'cant-block-while-blocked' => 'आप खुद ही अवरोधित हैं इसलिए इस समय आप औरों को अवरोधित नहीं कर सकते हैं।',
'cant-see-hidden-user' => 'कठबोली छुपा उपयोगकर्ता देखना',
'ipbblocked' => 'आप अन्य प्रयोक्ताओं को अवरोधित या अनवरोधित नहीं कर सकते, क्योंकि आप स्वयं अवरोधित हैं',
'ipbnounblockself' => 'आपको स्वयं को अनवरोधित करने की अनुमति नहीं है',

# Developer tools
'lockdb' => 'डाटाबेस लॉक करें',
'unlockdb' => 'डाटाबेस अनलॉक करें',
'lockdbtext' => 'डेटाबेस में ताला लगाने से सभी सदस्य पृष्ठ संपादन, अपनी वरीयताओं में परिवर्तन, अपनी ध्यानसूची में संपादन, और अन्य चीजें जिनके लिए डेटाबेस में परिवर्तन करना होता है, उनसे वंचित हो जायेंगे।
कृपया यह सुनिश्चित करे की आप यह करना चाहते हैं, और आप अनुरक्षण के पश्चात ताला खोल देंगे।',
'unlockdbtext' => 'डेटाबेस का ताला खोलने से सभी सदस्य पृष्ठ संपादन, अपनी वरीयताओं में परिवर्तन, अपनी ध्यानसूची में संपादन, और अन्य चीजें जिनके लिए डेटाबेस में परिवर्तन करना होता है, की सक्षमता को पुनर्स्थापित कर लेंगे।
कृपया यह सुनिश्चित करे की आप यह करना चाहते हैं।',
'lockconfirm' => 'जी हां, मुझे सचमुच डाटाबेस को ताला लगाना हैं।',
'unlockconfirm' => 'जी हां, मुझे सचमुच डाटाबेस का ताला खोलना हैं।',
'lockbtn' => 'डाटाबेस लॉक करें',
'unlockbtn' => 'अनलॉक डाटाबेस',
'locknoconfirm' => 'आपने कन्फर्मेशन सन्दूकमें क्लिक नहीं किया हैं।',
'lockdbsuccesssub' => 'डाटाबेस को ताला लगाया गया हैं',
'unlockdbsuccesssub' => 'डाटाबेस का ताला निकाला गया हैं',
'lockdbsuccesstext' => 'डाटाबेस को ताला लगाया गया हैं।<br />
आपके द्वारा मेंटेनन्स पूरा होने के बाद [[Special:UnlockDB|ताला खोलना]] याद रखें।',
'unlockdbsuccesstext' => 'डाटाबेसका ताला खोल दिया गया हैं।',
'lockfilenotwritable' => 'डाटाबेस के लॉक फ़ाईलमें लिख नहीं पा रहें हैं।
डाटाबेस का ताला लगाने या खोलनेके लिये, इस फ़ाईलपर लिखा जाना जरूरी हैं।',
'databasenotlocked' => 'डाटाबेस को ताला नहीं लगाया गया हैं।',
'lockedbyandtime' => '(द्वारा {{GENDER:$1|$1}} पर $2 यहां $3)',

# Move page
'move-page' => '$1 ले जाएं',
'move-page-legend' => 'पृष्ठ स्थानांतरण',
'movepagetext' => "नीचे दिया हुआ पर्चा पृष्ठ का नाम बदल देगा, उसका सारा इतिहास भी नए नाम से दिखना शुरू हो जाएगा।
पुराना शीर्षक नये नाम को अनुप्रेषित करेगा ।
मूल शीर्षक की ओर ले जाने वाले अग्रेषणों को आप स्वचालित रूप से बदल सकते हैं।
यदि आप ऐसा नहीं करते हैं तो कृपया [[Special:DoubleRedirects|दोहरे]] पुनर्निर्देशण या [[Special:BrokenRedirects|टूटे पुनर्निर्देशन]] के लिए ज़रूर जाँच करें।
कड़ियाँ सही जगह इंगित करती रहें, यह सुनिश्चित करना आपकी जिम्मेदारी है।

अगर नये शीर्षक का लेख पहले से है तो स्थानांतरण '''नहीं''' होगा। पर अगर नये शीर्षक वाला लेख खाली है अथवा कहीं और अनुप्रेषित करता है और साथ ही उसके पुराने संस्करण नहीं हैं तो स्थानांतरण हो जायेगा ।
इसका मतलब कि यदि आपसे गलती हो जाए तो आप वापस पुराने नाम पर इस पृष्ठ का स्थानांतरण कर सकेंगे, और साथ ही आप किसी मौजूदा पृष्ठ के बदले यह स्थानांतरण नहीं कर सकते हैं।

'''चेतावनी!'''
यदि पृष्ठ काफ़ी लोकप्रिय है तो उसके लिए यह एक बहुत बड़ा व अकस्मात् परिवर्तन हो सकता है;
आगे बढ़ने से पहले इसका अंजाम अच्छी तरह समझ लें।

'''सूचना!'''
स्थानांतरण करनेसे कोई भी महत्वपूर्ण लेख में अनपेक्षित बदलाव हो सकते है ।
आपसे अनुरोध है कि आप इसके परिणाम जान लें ।",
'movepagetext-noredirectfixer' => "नीचे दिया हुआ पर्चा पृष्ठ का नाम बदल देगा, उसका सारा इतिहास भी नए नाम से दिखना शुरू हो जाएगा।
पुराना शीर्षक नये नाम को अनुप्रेषित करेगा ।
मूल शीर्षक की ओर ले जाने वाले अग्रेषणों को आप स्वचालित रूप से बदल सकते हैं।
यदि आप ऐसा नहीं करते हैं तो कृपया [[Special:DoubleRedirects|दोहरे]] पुनर्निर्देशण या [[Special:BrokenRedirects|टूटे पुनर्निर्देशन]] के लिए ज़रूर जाँच करें।
कड़ियाँ सही जगह इंगित करती रहें, यह सुनिश्चित करना आपकी जिम्मेदारी है।

अगर नये शीर्षक का लेख पहले से है तो स्थानांतरण '''नहीं''' होगा। पर अगर नये शीर्षक वाला लेख खाली है अथवा कहीं और अनुप्रेषित करता है और साथ ही उसके पुराने संस्करण नहीं हैं तो स्थानांतरण हो जायेगा ।
इसका मतलब कि यदि आपसे गलती हो जाए तो आप वापस पुराने नाम पर इस पृष्ठ का स्थानांतरण कर सकेंगे, और साथ ही आप किसी मौजूदा पृष्ठ के बदले यह स्थानांतरण नहीं कर सकते हैं।

'''चेतावनी!'''
यदि पृष्ठ काफ़ी लोकप्रिय है तो उसके लिए यह एक बहुत बड़ा व अकस्मात् परिवर्तन हो सकता है;
आगे बढ़ने से पहले इसका अंजाम अच्छी तरह समझ लें।

'''सूचना!'''
स्थानांतरण करनेसे कोई भी महत्वपूर्ण लेख में अनपेक्षित बदलाव हो सकते है ।
आपसे अनुरोध है कि आप इसके परिणाम जान लें ।",
'movepagetalktext' => "संबंधित वार्ता पृष्ठ इसके साथ स्थानांतरीत नहीं होगा '''अगर:'''
* आप पृष्ठ दुसरे नामस्थान में स्थानांतरीत कर रहें है
* इस नाम का वार्ता पृष्ठ पहलेसे बना हुवा है, या
* नीचे दिया हुआ चेक बॉक्स आपने निकाल दिया है ।

इन मामलोंमे आपको स्वयं यह पृष्ठ जोडने पड़ सकते है ।",
'movearticle' => 'पृष्ठ का नाम बदलें',
'moveuserpage-warning' => 'चाल उपयोगकर्ता चेतावनी पृष्ठ',
'movenologin' => 'लॉग इन नहीं किया है',
'movenologintext' => 'लेख स्थानान्तरित करने के लिये आपका [[Special:UserLogin|लॉग इन]] किया होना आवश्यक हैं।',
'movenotallowed' => 'आपको पृष्ठ स्थानांतरित करने की अनुमति नहीं है।',
'movenotallowedfile' => 'आपको फ़ाइलें स्थानांतरित करने की अनुमति नहीं है।',
'cant-move-user-page' => 'आपको सदस्य पृष्ठ स्थानांतरित करने की अनुमति नही है (सिवाय उप पन्नों के)।',
'cant-move-to-user-page' => 'आपको किसी पन्नो को सदस्य पृष्ठ पर ले जाने की अनुमति नहीं है (सिवाय सदस्य उप पृष्ठ के)',
'newtitle' => 'नये शीर्षक की ओर:',
'move-watch' => 'ध्यान रखें',
'movepagebtn' => 'नाम बदलें',
'pagemovedsub' => 'नाम बदल दिया गया है',
'movepage-moved' => '\'\'\'"$1" को "$2" पर ले जाया गया है\'\'\'',
'movepage-moved-redirect' => 'एक पुनर्निर्देशन भी निर्मित किया गया है।',
'movepage-moved-noredirect' => 'पुनर्निर्देशन पृष्ठ नहीं बनाया गया है।',
'articleexists' => 'इस नाम का एक पृष्ठ पहले से ही उपस्थित है, अथवा आप ने अमान्य नाम चुना है। कृपया दूसरा नाम चुनें।',
'cantmove-titleprotected' => 'नया शीर्षक बनाने से रोक होने के कारण, आप इस जगह पर कोई अन्य पृष्ठ स्थानांतरित नहीं कर सकतें हैं।',
'talkexists' => "'''पृष्ठ का नाम बदल दिया गया है, पर उससे संबंधित वार्ता पृष्ठ नहीं बदला गया है क्योंकि वह पहले से बना हुवा है ।
कृपया इसे स्वयं बदल दे ।'''",
'movedto' => 'को भेजा गया',
'movetalk' => 'सम्बन्धित वार्ता पृष्ठ भी बदलें',
'move-subpages' => 'उप पृष्ठ भी ले जाएँ ($1 तक)',
'move-talk-subpages' => 'वार्ता पृष्ठ के उप पृष्ठ भी ले जाएँ ($1 तक)',
'movepage-page-exists' => '$1 पृष्ठ पहले से अस्तित्वमें हैं और उसपर अपनेआप पुनर्लेखन नहीं कर सकतें।',
'movepage-page-moved' => '$1 पृष्ठ $2 नाम पर स्थानांतरित कर दिया गया है।',
'movepage-page-unmoved' => '$1 पृष्ठ $2 नाम पर स्थानांतरित नहीं किया जा सका।',
'movepage-max-pages' => '$1 की अधिकतम सीमा तक पृष्ठ स्थानांतरित कर {{PLURAL:$1|दिया गया है|दिये गये हैं}}, अब और पृष्ठ अपने-आप स्थानांतरित नहीं होंगे।',
'movelogpage' => 'स्थानान्तरण लॉग',
'movelogpagetext' => 'नीचे सभी स्थानान्तरणों की सूची दी गई है।',
'movesubpage' => '{{PLURAL:$1|उप पृष्ठ|उप पृष्ठ}}',
'movesubpagetext' => 'नीचे $1 {{PLURAL:$1|पृष्ठ दिखाया गया है, जो इस पृष्ठ का उप पृष्ठ है|पृष्ठ दिखाया गया है, जो इस पृष्ठ के उप पृष्ठ हैं}}।',
'movenosubpage' => 'इस पृष्ठ के कोई उपपृष्ठ नहीं हैं।',
'movereason' => 'कारण:',
'revertmove' => 'पुराने अवतरण पर ले जाएं',
'delete_and_move' => 'हटाएँ और नाम बदलें',
'delete_and_move_text' => '==हटाने की जरूरत==
लक्ष्य पृष्ठ "[[:$1]]" पहले से अस्तित्वमें हैं।
नाम बदलने के लिये क्या आप इसे हटाना चाहतें हैं?',
'delete_and_move_confirm' => 'जी हां, पृष्ठ हटाईयें',
'delete_and_move_reason' => 'स्थानांतरण करने के लिये जगह बनाई गयी है',
'selfmove' => 'स्रोत और लक्ष्य शीर्षक समान हैं;
पृष्ठ अपने ही जगह पर स्थानांतरित नहीं हो सकता।',
'immobile-source-namespace' => 'नामस्थान "$1" के पन्नों का स्थानंतरण नहीं किया जा सकता है।',
'immobile-target-namespace' => 'पन्नों को "$1" नामस्थान में नहीं ले जाया जा सकता है',
'immobile-target-namespace-iw' => 'अंतर विकि कड़ी पृष्ठ  ले जाने के लिए उचित लक्ष्य नहीं है।',
'immobile-source-page' => 'इस पृष्ठ का स्थानांतरण नहीं किया जा सकता है।',
'immobile-target-page' => 'इस गंतव्य शीर्षक पर नहीं ले जाया जा सकता है।',
'imagenocrossnamespace' => 'संचिका को ग़ैर-संचिका नामस्थान में स्थानांतरित नहीं किया जा सकता है',
'nonfile-cannot-move-to-file' => 'असञ्चिका को सञ्चिका नामस्थान में नहीं ले जाया जा सकता',
'imagetypemismatch' => 'संचिका का नया विस्तार उसकी किस्म से मेल नहीं खा रहा है',
'imageinvalidfilename' => 'लक्ष्यित संचिका नाम अवैध है',
'fix-double-redirects' => 'मूल शीर्षक तक जाने वाले सभी पुनर्निर्देशनों को भी बदलें',
'move-leave-redirect' => 'एक पुनर्निर्देशन पीछे छोड़ते जाएँ',
'protectedpagemovewarning' => "'''चेतावनी:''' यह पृष्ठ तालाबंद है अतः केवल वही सदस्य इनका स्थानांतरण कर सकते हैं जो प्रबंधक हों।
निम्न् तलिका मे ताजा सदस्यो कि जानकारी दि गयि है:",
'semiprotectedpagemovewarning' => "'''सूचना:''' यह पृष्ठ सुरक्षित कर दिया गया है और इसे केवल पंजीकृत सदस्य ही स्थानांतरित कर सकते हैं।
नवीनतम लॉग प्रविष्टि संदर्भ के लिये नीचे दी है:",
'move-over-sharedrepo' => '== फ़ाइल मौजूद है ==
[[:$1]] एक साझे भंडार पर मौजूद है। इस नाम पर स्थानांतरण से नई फ़ाइल साझा फ़ाइल को ओवरराइड करेगी।',
'file-exists-sharedrepo' => 'फ़ाइल रेपो साझा मौजूद',

# Export
'export' => 'पन्नों का निर्यात करें',
'exporttext' => 'आप विशिष्ठ पृष्ठ के विषय वस्तु और संपादन इतिहास को निर्यात कर सकते हैं अथवा पृष्ठों के समूह को कुछ XML में लपेट सकते हैं।
यह [[Special:Import|आयात पृष्ठ]] की सहायता से मीडियाविकी का प्रयोग करके दूसरी विकी से आयात किया जा सकता है।

पृष्ठों को निर्यात करने के लिए, नीचे विषय वस्तु संदूक में शीर्षक प्रवेश करें, एक शीर्षक प्रति पंक्ति, और चुने कि आप वर्त्तमान अवतरण के साथ पुराने अवतरण भी चाहते हैं कि नहीं, या पिछले संपादन के बारे में जानकारी के साथ केवल वर्त्तमान अवतरण चाहते हैं।

बाद वाली स्थिति के लिए आप एक सम्पर्क भी प्रयोग कर सकते हैं, उदाहरण के लिए, "[[{{MediaWiki:Mainpage}}]]" पृष्ठ के लिए [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]।',
'exportall' => 'सभी पृष्ठ निर्यात करें',
'exportcuronly' => 'पूरा इतिहास नहीं चाहियें, सिर्फ अभी का अवतरण अंतर्भूत करें',
'exportnohistory' => "----
'''सूचना:''' इस फॉर्म का इस्तेमाल कर पृष्ठका पूरा इतिहास निर्यात करना परफॉर्मेन्स के चलते रोक दिया गया हैं।",
'exportlistauthors' => 'प्रत्येक पृष्ठ के लिए योगदानकर्ताओं की एक पूरी सूची शामिल करें ।',
'export-submit' => 'निर्यात',
'export-addcattext' => 'इस श्रेणीसे पृष्ठ चुनें:',
'export-addcat' => 'चुनें',
'export-addnstext' => 'इस नामस्थान के पृष्ठ जोड़ें:',
'export-addns' => 'जोड़ें',
'export-download' => 'फ़ाईलके रुपमें सेव करें',
'export-templates' => 'टेम्प्लेटस भी जोडें',
'export-pagelinks' => 'जिन पन्नों के हवाले यहाँ हैं, उन्हें भी इस गहराई तक शामिल करें:',

# Namespace 8 related
'allmessages' => 'व्यवस्था संदेश',
'allmessagesname' => 'नाम',
'allmessagesdefault' => 'डिफॉल्ट पाठ',
'allmessagescurrent' => 'वर्तमान पाठ',
'allmessagestext' => 'ये मीडियाविकि नामस्थान में उपलब्ध प्रणाली संदेशों की एक सूची है। यदि आप सामान्य मीडियाविकि क्षेत्रीयकरण में योगदान देना चाहें तो कृपया [//www.mediawiki.org/wiki/Localisation मीडियाविकि क्षेत्रीयकरण] व [//translatewiki.net translatewiki.net] को देखें।',
'allmessagesnotsupportedDB' => "इस पृष्ठ का इस्तेमाल नहीं कर सकते क्योंकी '''\$wgUseDatabaseMessages''' बंद हैं।",
'allmessages-filter-legend' => 'छानें',
'allmessages-filter' => 'अनुकूलन स्थिति के आधार पर छानें:',
'allmessages-filter-unmodified' => 'अपरिवर्तित',
'allmessages-filter-all' => 'सभी',
'allmessages-filter-modified' => 'परिवर्तित',
'allmessages-prefix' => 'उपसर्ग के आधार पर छानें:',
'allmessages-language' => 'भाषा:',
'allmessages-filter-submit' => 'जाएँ',

# Thumbnails
'thumbnail-more' => 'बड़ा करें',
'filemissing' => 'फ़ाईल मिली नहीं',
'thumbnail_error' => 'थंबनेल बनानेमें असुविधा हुई है: $1',
'djvu_page_error' => 'DjVu पृष्ठ रेंजके बाहर हैं',
'djvu_no_xml' => 'DjVu फ़ाईलके लिये XML नहीं मिल पाया',
'thumbnail_invalid_params' => 'गलत अंगूठानख मापदण्ड',
'thumbnail_dest_directory' => 'लक्ष्य डाइरेक्टरी बना नहीं पा रहें हैं',
'thumbnail_image-type' => 'इस प्रकार की छवि समर्थित नहीं है',
'thumbnail_gd-library' => 'अवैध जीडी लाइब्रेरी जमाव: कार्यसमूह $1 मौजूद नहीं है',
'thumbnail_image-missing' => 'लगता है संचिका नामौजूद है: $1',

# Special:Import
'import' => 'पृष्ठ इम्पोर्ट करें',
'importinterwiki' => 'ट्रान्सविकि आयात',
'import-interwiki-text' => 'आयात करनेके लिये एक विकि और एक पृष्ठ चुनें।
अवतरणोंके दिनांक और संपादक का नाम जतन किया जायेगा.
सभी ट्रान्सविकि आयात क्रियायें [[Special:Log/import|आयात सूचीमें]] डाली गई हैं।',
'import-interwiki-source' => 'स्रोत विकि/पृष्ठ:',
'import-interwiki-history' => 'इस पृष्ठ के सभी इतिहास अवतरण कापी करें',
'import-interwiki-templates' => 'सभी साँचे शामिल करें',
'import-interwiki-submit' => 'आयात',
'import-interwiki-namespace' => 'गंतव्य नामस्थान:',
'import-upload-filename' => 'संचिका नाम:',
'import-comment' => 'टिप्पणी:',
'importtext' => 'कृपया स्रोत विकि से संचिका निर्यातित करने के लिए [[Special:Export|निर्यात सुविधा]] का इस्तेमाल करें।
इसे अपने संगणक पर सँजो के यहाँ चढ़ा दें।',
'importstart' => 'पृष्ठ आयात कर रहें हैं...',
'import-revision-count' => '$1 {{PLURAL:$1|अवतरण|अवतरण}}',
'importnopages' => 'आयात करने के लिये पृष्ठ नहीं हैं।',
'imported-log-entries' => 'आयातित $1 {{PLURAL:$1|लॉग प्रविष्टि|लॉग प्रविष्टियाँ}}.
जब कभी कोई फाइल आपको import करनी हो',
'importfailed' => 'इम्पोर्ट असफल रहा  $1',
'importunknownsource' => 'अज्ञात आयात स्रोत प्रकार',
'importcantopen' => 'आयात फ़ाईल खोल नहीं पायें',
'importbadinterwiki' => 'अवैध अन्तरविकि कड़ी',
'importnotext' => 'खाली अथवा पाठ नहीं',
'importsuccess' => 'इम्पोर्ट सफल हुआ !',
'importhistoryconflict' => 'उपलब्ध इतिहास अवतरण आपसमें विरोधी हैं (यह पृष्ठ पहलेसे आयात कर दिया गया होनेकी आशंका हैं)',
'importnosources' => 'कोई भी ट्रान्सविकी आयात स्रोत मिले नहीं और प्रत्यक्ष इतिहास अपलोड बन्द कर दिए गए हैं।',
'importnofile' => 'कोईभी आयात फ़ाईल अपलोड नहीं हुई हैं।',
'importuploaderrorsize' => 'आयात फ़ाईल अपलोड हुई नहीं। इसका आकार अनुमतिसे ज्यादा हैं।',
'importuploaderrorpartial' => 'आयात फ़ाईल अपलोड हुई नहीं। फ़ाईल आधी अधूरी अपलोड हुई।',
'importuploaderrortemp' => 'आयात फ़ाईल अपलोड हुई नहीं। एक अस्थायी डाइरेक्टरी नहीं मिल रहीं।',
'import-parse-failure' => 'XML आयात पार्स पूरा नहीं हुआ',
'import-noarticle' => 'आयात करने के लिये पृष्ठ नहीं!',
'import-nonewrevisions' => 'सभी अवतरण पहले ही आयात कर दिये गये हैं।',
'xml-error-string' => '$1 पंक्ति $2 में, कॉलम $3 (बाईट $4): $5',
'import-upload' => 'XML डाटा अपलोड करें',
'import-token-mismatch' => 'सत्र सामग्री खो गई है। 
कृपया पुनः प्रयास करें।',
'import-invalid-interwiki' => 'इस विकि से आयात नहीं हो सकता है।',
'import-error-edit' => 'पृष्ठ " $1 " आयातित नहीं किया जासकता है क्योंकि आपको उसे संपादित करने की अनुमति नहीं हैं।',
'import-error-create' => 'पृष्ठ " $1 " आयातित नहीं है क्योंकि आपको उसे बनाने की अनुमति नहीं हैं।',
'import-error-interwiki' => 'पृष्ठ "$1" आयात नहीं किया गया है क्योंकि इसका नाम अंतरविकी कड़ियाँ बनाने के लिए आरक्षित है।',
'import-error-special' => 'पृष्ठ "$1" आयात नहीं किया गया है क्योंकि यह एक ऐसे विशेष नामस्थान के अंतर्गत आता है जिसमें पृष्ठ नहीं बनाए जा सकते हैं।',
'import-error-invalid' => 'पृष्ठ "$1" आयात नहीं किया गया है क्योंकि इसका नाम अमान्य है।',

# Import log
'importlogpage' => 'आयात सूची',
'importlogpagetext' => 'अन्य विकियों से प्रबन्धकों द्वारा किए गए सम्पादन इतिहास के साथ होने वाले पृष्ठों का आयात।',
'import-logentry-upload' => 'सञ्चिका अपलोड करके [[$1]] का आयात किया',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|अवतरण|अवतरण}}',
'import-logentry-interwiki' => 'ट्रान्सविकि कर दिया $1',
'import-logentry-interwiki-detail' => '$2 से $1 {{PLURAL:$1|अवतरण|अवतरण}}',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'आपका प्रयोक्ता पृष्ठ',
'tooltip-pt-anonuserpage' => 'आप जिस आईपी से बदलाव कर रहें हैं उसका सदस्य पान',
'tooltip-pt-mytalk' => 'आपका वार्ता पृष्ठ',
'tooltip-pt-anontalk' => 'इस आईपी एड्रेससे हुए बदलावों के बारे में वार्ता',
'tooltip-pt-preferences' => 'आपकी वरीयताएँ',
'tooltip-pt-watchlist' => 'आपने ध्यान दिये हुए पन्नोंकी सूची',
'tooltip-pt-mycontris' => 'आपके योगदानों की सूची',
'tooltip-pt-login' => 'आपको सत्रारम्भ करने के लिए प्रोत्साहित किया जाता है; लेकिन यह अनिवार्य नहीं है',
'tooltip-pt-anonlogin' => 'आप लॉग इन करें, जबकि यह अत्यावश्यक नहीं हैं।',
'tooltip-pt-logout' => 'सत्रांत',
'tooltip-ca-talk' => 'सामग्री पृष्ठ के बारे में वार्तालाप',
'tooltip-ca-edit' => 'आप यह पृष्ठ बदल सकते हैं।
कृपया बदलाव संजोने से पहले झलक देखें।',
'tooltip-ca-addsection' => 'नया विभाग आरम्भ करें',
'tooltip-ca-viewsource' => 'यह पृष्ठ रक्षित हैं। आप इसका स्रोत देख सकते हैं।',
'tooltip-ca-history' => 'इस पृष्ठ के पुराने अवतरण',
'tooltip-ca-protect' => 'इस पृष्ठको सुरक्षित किजीयें',
'tooltip-ca-unprotect' => 'इस पृष्ठ की सुरक्षा बदलें ।',
'tooltip-ca-delete' => 'इस पृष्ठ को हटाएं',
'tooltip-ca-undelete' => 'इस पृष्ठको हटाने से पहले किये गये बदलाव पुनर्स्थापित करें',
'tooltip-ca-move' => 'यह पृष्ठ स्थानांतरित करें',
'tooltip-ca-watch' => 'इस पृष्ठ को अपनी ध्यानसूची में डालें',
'tooltip-ca-unwatch' => 'यह पृष्ठ अपने ध्यानसूचीसे हटाएं',
'tooltip-search' => '{{SITENAME}} में खोजें',
'tooltip-search-go' => 'अगर इस शीर्षक का पृष्ठ हैं तो उसपर चलें',
'tooltip-search-fulltext' => 'इस वाक्यांश को पन्नों में खोजें',
'tooltip-p-logo' => 'मुख पृष्ठ',
'tooltip-n-mainpage' => 'मुखपृष्ठ पर जाएँ',
'tooltip-n-mainpage-description' => 'मुखपृष्ठ पर जाएँ',
'tooltip-n-portal' => 'परियोजना के बारे में, आप क्या कर सकतें हैं, सहायता कहाँ से लें',
'tooltip-n-currentevents' => 'हालकी घटनाओं की पृष्ठभूमि प्राप्त करें',
'tooltip-n-recentchanges' => 'विकि में हाल में हुए बदलावों की सूची',
'tooltip-n-randompage' => 'किसी एक लेख पर जाएँ',
'tooltip-n-help' => 'पता लगाने का स्थान',
'tooltip-t-whatlinkshere' => 'यहाँ का हवाला देने वाले सभी विकि पन्नों की सूची',
'tooltip-t-recentchangeslinked' => 'यहाँ जुड़े हुए सभी पन्नों में हुए हाल के बदलाव',
'tooltip-feed-rss' => 'इस पृष्ठ की आरएसएस फ़ीड',
'tooltip-feed-atom' => 'इस पृष्ठ की अणु फ़ीड',
'tooltip-t-contributions' => 'इस सदस्यके योगदानकी सूची देखियें',
'tooltip-t-emailuser' => 'इस सदस्य को इमेल भेजें',
'tooltip-t-upload' => 'संचिका चढ़ाएँ',
'tooltip-t-specialpages' => 'सभी विशेष पृष्ठों की सूची',
'tooltip-t-print' => 'इस पृष्ठका छपानेलायक अवतरण',
'tooltip-t-permalink' => 'पृष्ठ के इस संस्करण की स्थायी कड़ी',
'tooltip-ca-nstab-main' => 'सामग्री वाला पृष्ठ देखें',
'tooltip-ca-nstab-user' => 'सदस्य पृष्ठ देखियें',
'tooltip-ca-nstab-media' => 'मीडिया पृष्ठ देखें',
'tooltip-ca-nstab-special' => 'यह एक खास पृष्ठ है, आप इसे बदल नहीं सकतें हैं',
'tooltip-ca-nstab-project' => 'प्रोजेक्ट पृष्ठ देखियें',
'tooltip-ca-nstab-image' => 'संचिका का पृष्ठ देखें',
'tooltip-ca-nstab-mediawiki' => 'प्रणाली सन्देश देखें',
'tooltip-ca-nstab-template' => 'टेम्प्लेट देखियें',
'tooltip-ca-nstab-help' => 'सहायता पृष्ठ पर जाईयें',
'tooltip-ca-nstab-category' => 'श्रेणियाँ पृष्ठ देखियें',
'tooltip-minoredit' => 'इसे छोटे बदलाव के तौर पर दर्ज करें',
'tooltip-save' => 'अपने बदलाव सँजोएँ',
'tooltip-preview' => 'अपने बदलावों की झलक देखें, कृपया सँजोने से पहले इसका इस्तेमाल करें!',
'tooltip-diff' => 'इस पाठ्यमें आपने किये हुए बदलाव देखें।',
'tooltip-compareselectedversions' => 'इस पृष्ठ के चुने हुए अवतरणों में अन्तर देखें।',
'tooltip-watch' => 'इस पृष्ठ को अपनी ध्यानसूची में डालें।',
'tooltip-watchlistedit-normal-submit' => 'पृष्ठ हटाएँ',
'tooltip-watchlistedit-raw-submit' => 'ध्यानसूची अपडेट करें',
'tooltip-recreate' => 'यह पृष्ठ पहले हटाया होने के बावजूद फिरसे बनायें',
'tooltip-upload' => 'अपलोड शुरू करें',
'tooltip-rollback' => '"वापस ले जाएँ" इस पृष्ठ के पिछले योगदाता के बदलाव एक ही चटके में ग़ायब कर देता है।',
'tooltip-undo' => '"पुरानी स्थिति पर लाएँ" इस बदलाव को वापस ले जा के संपादन पर्चे को झलक रीति में दिखलाता है।
इसके जरिए सारांश में पुरानी स्थिति में लाने का कारण लिखा जा सकता है।',
'tooltip-preferences-save' => 'वरीयताएं सहेजें',
'tooltip-summary' => 'एक संक्षिप्त सारांश दर्ज करें',

# Stylesheets
'common.css' => '/* यहां रखी css सभी त्वचाओंपर असर करेगी */',
'monobook.css' => '/* यहां रखी गई css मोनोबुक त्वचा का इस्तेमाल करने वाले सभी सदस्योंपर असर करेगी */',

# Scripts
'common.js' => '/* यहां लिखी गई जावास्क्रीप्ट सभी सदस्योंके लिये इस्तेमाल में लाई जायेगी। */',
'monobook.js' => '/* डिप्रिकेटेड;[[MediaWiki:common.js]] का इस्तेमाल करें */',

# Metadata
'notacceptable' => 'विकि सर्वर आपके क्लायंटको जिस प्रकारसे डाटा चाहिये उस प्रकारसे नहीं दे सकता।',

# Attribution
'anonymous' => '{{SITENAME}} के {{PLURAL:$1||}} बेनामी सदस्य',
'siteuser' => 'विकिपीडिया सदस्य  $1',
'anonuser' => '{{SITENAME}} अज्ञात उपयोगकर्ता $1',
'lastmodifiedatby' => 'इस पृष्ठ का आखिरी बदलाव $3 ने $2, $1 पर किया।',
'othercontribs' => '$1 के कार्य के अनुसार।',
'others' => 'अन्य',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|सदस्य|सदस्य}} $1',
'anonusers' => '{{SITENAME}} अनाम {{PLURAL:$2|सदस्य|सदस्य}} $1',
'creditspage' => 'पान श्रेय नामावली',
'nocredits' => 'इस पृष्ठ के लिये क्रेडिट जानकारी नहीं है।',

# Spam protection
'spamprotectiontitle' => 'स्पॅम सुरक्षा फिल्टर',
'spamprotectiontext' => 'आप जिस पृष्ठ को सँजोना चाहते थे उसे रद्दी सामग्री की छननी ने अवरोधित किया हुआ है।
यह संभवतः किसी कर्पसूचित बाहरी स्थल की कड़ी की वजह से हुआ है।',
'spamprotectionmatch' => 'नीचे दिये हुए पाठ को स्पॅम सुरक्षा फिल्टर द्वारा रोका गया था: $1',
'spambot_username' => 'मीडियाविकि स्पॅम स्वच्छता',
'spam_reverting' => '$1 को कड़ी ना होने वाले पुराने अवतरण को पुनर्स्थापित कर रहें हैं',
'spam_blanking' => 'सभी अवतरणोंमें $1 को कड़ियां हैं, पूरा पाठ निकाल रहें हैं',

# Info page
'pageinfo-title' => '"$1" के लिये जानकारी',
'pageinfo-not-current' => 'क्षमा करें, पुराने अवतरणों के लिए यह जानकारी प्रदान करना संभव नहीं है।',
'pageinfo-header-basic' => 'मूल जानकारी',
'pageinfo-header-edits' => 'सम्पादन इतिहास',
'pageinfo-header-restrictions' => 'पृष्ठ सुरक्षा',
'pageinfo-header-properties' => 'पृष्ठ जानकारी',
'pageinfo-display-title' => 'प्रदर्शित शीर्षक',
'pageinfo-default-sort' => 'डिफ़ॉल्ट सॉर्ट की',
'pageinfo-length' => 'पृष्ठ आकार (बाइट्स में)',
'pageinfo-article-id' => 'पृष्ठ आइ॰डी',
'pageinfo-views' => 'दर्शाव की संख्या',
'pageinfo-watchers' => 'पृष्ठ पर नज़र रखने वालों की संख्या',
'pageinfo-redirects-name' => 'इस पृष्ठ को पुनर्निर्देश',
'pageinfo-subpages-name' => 'इस पृष्ठ के उप-पृष्ठ',
'pageinfo-firstuser' => 'पृष्ठ निर्माता',
'pageinfo-firsttime' => 'पृष्ठ निर्माण तिथि',
'pageinfo-lastuser' => 'नवीनतम सम्पादक',
'pageinfo-lasttime' => 'नवीनतम सम्पादन तिथि',
'pageinfo-edits' => 'संपादन की कुल संख्या',
'pageinfo-authors' => 'लेखकों की संख्या',
'pageinfo-recent-edits' => 'हाल में हुए सम्पादनों की संख्या (पिछ्ले $1 में)',
'pageinfo-magic-words' => 'जादुई {{PLURAL:$1|शब्द}} ($1)',
'pageinfo-hidden-categories' => 'छुपी {{PLURAL:$1|श्रेणी|श्रेणियाँ}} ($1)',
'pageinfo-templates' => 'प्रयुक्त {{PLURAL:$1|साँचा|साँचे}} ($1)',

# Patrolling
'markaspatrolleddiff' => 'देख लिया ऐसा मार्क करें',
'markaspatrolledtext' => 'इस पृष्ठ को देख लिया ऐसा मार्क करें',
'markedaspatrolled' => 'देख लिया ऐसा मार्क करें',
'markedaspatrolledtext' => '[[:$1]] का चयनित अवतरण जाँचा हुआ चिन्हित किया गया।',
'rcpatroldisabled' => 'हाल में हुए बदलावों पर नजर रखना बंद कर दिया हैं',
'rcpatroldisabledtext' => 'हाल में हुए बदलावोंपर नजर रखने की सुविधा बंद कर दी ग‍ईं हैं।',
'markedaspatrollederror' => 'देख लिया ऐसा मार्क नहीं कर पायें',
'markedaspatrollederrortext' => 'नजर रखने के लिये आपको एक अवतरणको चुनना होगा।',
'markedaspatrollederror-noautopatrol' => 'आप खुद अपने बदलावोंपर नजर नहीं रख सकतें हैं।',

# Patrol log
'patrol-log-page' => 'नजर रखनेकी सूची',
'patrol-log-header' => 'यह निगरानी में बने संस्करणों का चिट्ठा है।',
'log-show-hide-patrol' => 'गश्ती अभिलेख $1',

# Image deletion
'deletedrevision' => 'पुराना अवतरण $1 हटा दिया',
'filedeleteerror-short' => 'फ़ाईल हटानेमें समस्या: $1',
'filedeleteerror-long' => 'फ़ाईल हटानेमें आईं समस्यायें:

$1',
'filedelete-missing' => 'फ़ाईल "$1" को हटा नहीं सकते, क्योकि यह अस्तित्व में नहीं हैं।',
'filedelete-old-unregistered' => 'बताया गया "$1" अवतरण डाटाबेस में मिला नहीं।',
'filedelete-current-unregistered' => 'बताई गई "$1" फ़ाईल डाटाबेसमें नहीं हैं।',
'filedelete-archive-read-only' => 'आर्चिव्ह डाइरेक्टरी "$1" पर वेबसर्वर लिख नहीं पा रहा हैं।',

# Browsing diffs
'previousdiff' => '← इससे पुराना बदलाव',
'nextdiff' => 'ताज़ा संपादन →',

# Media information
'mediawarning' => 'चेतावनी मीडिया',
'imagemaxsize' => "छवि आकार सीमा:<br />''(संचिका वर्णन पन्नों के लिए)''",
'thumbsize' => 'अंगूठानख आकार:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|पृष्ठ|पृष्ठ}}',
'file-info' => 'फ़ाईल का आकार: $1, MIME प्रकार: $2',
'file-info-size' => '$1 × $2 चित्रतत्व, संचिका का आकार: $3, माइम प्रकार: $4',
'file-info-size-pages' => '$1 × $2  पिक्सेल, फ़ाइल का आकार:  $3 , MIME प्रकार:  $4 ,  $5   {{PLURAL:$5| page|pages}}',
'file-nohires' => 'इससे ज्यादा रिज़ोल्यूशन उपलब्ध नहीं हैं.',
'svg-long-desc' => 'SVG फ़ाईल, साधारणत: $1 × $2 पीक्सेल्स, फ़ाईलका आकार: $3',
'show-big-image' => 'सम्पूर्ण रिज़ोल्यूशन',
'show-big-image-preview' => 'इस पूर्वावलोकन का आकार:  $1 ।',
'show-big-image-other' => 'अन्य  {{PLURAL:$2| resolution|resolutions}}:  $1 ।',
'show-big-image-size' => '$1 × $2  पिक्सेल',
'file-info-gif-looped' => 'चक्रित',
'file-info-gif-frames' => '$1 {{PLURAL:$1|ढाँचा|ढाँचे}}',
'file-info-png-looped' => 'चक्रित',
'file-info-png-repeat' => 'प्ले हो चुका  $1   {{PLURAL:$1| time|times}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|frame|frames}}',

# Special:NewFiles
'newimages' => 'नई फ़ाईल्सकी गैलरी',
'imagelisttext' => "नीचे $2 के नुसार '''$1''' {{PLURAL:$1|फ़ाईल दी है।|फ़ाईलें दी हुईं हैं।}}",
'newimages-summary' => 'यह खास पृष्ठ ताज़ातरीन चढ़ाई गई संचिकाएँ दर्शाता है।',
'newimages-legend' => 'छननी',
'newimages-label' => 'संचिका नाम (या उसका अंश):',
'showhidebots' => '(बोट्स $1)',
'noimages' => 'देखने के लिए कुछ नहीं है।',
'ilsubmit' => 'खोजें',
'bydate' => 'तिथि अनुसार',
'sp-newimages-showfrom' => '$2, $1 के बाद की फ़ाईलें दर्शायें',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 second|$1 seconds}}',
'minutes' => '{{PLURAL:$1|$1 minute|$1 minutes}}',
'hours' => '{{PLURAL:$1|$1 hour|$1 hours}}',
'days' => '{{PLURAL:$1|$1 day|$1 days}}',
'ago' => '$1 पहले',

# Bad image list
'bad_image_list' => 'प्रारूप इस प्रकार है:

केवल सूची के मद (* से शुरू होने वाली पंक्तियाँ) चुने जाएँगी।
किसी भी पक्ति में मौजूद पहली कड़ी, गलत संचिका की कड़ी होनी चाहिए
उसी पंक्ति मे आगे आने वाली अन्य कड़ियों को अपवाद माना जाता है, अर्थात् ये ऐसे पृष्ठ होंगे जिनके अंदर यह संचिका जड़ी हुई है।',

# Metadata
'metadata' => 'मेटाडाटा',
'metadata-help' => 'इस फ़ाइल में बढ़ाई हुई जानकारी हैं, हो सकता है कि यह फ़ाइल बनाने में इस्तेमाल किये गए स्कैनर अथवा कैमेरा से यह प्राप्त हुई हैं। अगर यह फ़ाइल बदलदी गई है तो यह जानकारी नई फ़ाइल से मेल नहीं खाने की आशंका है।',
'metadata-expand' => 'विस्तृत जानकारियां दिखाएं',
'metadata-collapse' => 'विस्तृत जानकारियां छिपाएं',
'metadata-fields' => 'जब मेटाडाटा तालिका को लघुरूप किया जाएगा तो इस सन्देश में सूचीबद्ध इएक्सआयएफ मेटाडाटा जानकारियां छवि प्रदर्शित होते समय सम्मिलित की जाएंगी।
अन्य डिफ़ॉल्ट रूप से छिपी रहेंगी।
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
'exif-imagewidth' => 'चौडाई',
'exif-imagelength' => 'ऊँचाई',
'exif-bitspersample' => 'प्रति घटक बीट्स',
'exif-compression' => 'कम्प्रेशन योजना',
'exif-photometricinterpretation' => 'पिक्सेल कॉम्पोझीशन',
'exif-orientation' => 'अभिविन्यास',
'exif-samplesperpixel' => 'घटकोंकी संख्या',
'exif-planarconfiguration' => 'डाटा रचना',
'exif-ycbcrsubsampling' => 'Y का C के साथ सबसॅम्पलींग अनुमान',
'exif-ycbcrpositioning' => 'Y और C का पोज़िशनिंग',
'exif-xresolution' => 'होरिज़ॉंटल रिज़ोल्यूशन',
'exif-yresolution' => 'व्हर्टिकल रिज़ोल्यूशन',
'exif-stripoffsets' => 'चित्र डाटा स्थान',
'exif-rowsperstrip' => 'हर स्ट्रीपमें कतारोंकी संख्या',
'exif-stripbytecounts' => 'कॉम्प्रेस्स्ड स्ट्रीपमें बाईट्स',
'exif-jpeginterchangeformat' => 'JPEG SOI के लिये ऑफसेट',
'exif-jpeginterchangeformatlength' => 'JPEG डाटाके बाईट्स',
'exif-whitepoint' => 'सफेद बिंदू क्रोमॅटिसिटी',
'exif-primarychromaticities' => 'क्रोमॅटिसिटीज ऑफ प्राईमारिटीज',
'exif-ycbcrcoefficients' => 'कलर स्पेस ट्रान्स्फॉर्मेशन मॅट्रीक्स कोएफिशीयंट्स',
'exif-referenceblackwhite' => 'काले और सफेद संदर्भ मूल्योंकी जोड़ी',
'exif-datetime' => 'फ़ाईल बदलाव दिनांक और समय',
'exif-imagedescription' => 'चित्र शीर्षक',
'exif-make' => 'कैमेरा उत्पादक',
'exif-model' => 'कैमेरा मॉडेल',
'exif-software' => 'इस्तेमाल किया हुआ सॉफ्टवेयर',
'exif-artist' => 'लेखक',
'exif-copyright' => 'कोपीराईट का धारक',
'exif-exifversion' => 'Exif अवतरण',
'exif-flashpixversion' => 'सपोर्टेड फ्लॅशपीक्स अवतरण',
'exif-colorspace' => 'रंग स्थान',
'exif-componentsconfiguration' => 'हर घटक का मतलब',
'exif-compressedbitsperpixel' => 'चित्र कॉम्प्रेशन मोड',
'exif-pixelydimension' => 'छवि चौड़ाई',
'exif-pixelxdimension' => 'छवि ऊँचाई',
'exif-usercomment' => 'सदस्य टिप्पणी',
'exif-relatedsoundfile' => 'संबंधित ध्वनी फ़ाईल',
'exif-datetimeoriginal' => 'डाटा बनाने का दिनांक और समय',
'exif-datetimedigitized' => 'डिजिटाईज़िंग का दिनांक और समय',
'exif-subsectime' => 'दिनांकसमय उपसेकंद',
'exif-subsectimeoriginal' => 'मूलदिनांकसमय उपसेकंड',
'exif-subsectimedigitized' => 'दिनांकसमयडिजिटाईज्ड उपसेकेंड',
'exif-exposuretime' => 'एक्स्पोज़र समय',
'exif-exposuretime-format' => '$1 सेकंड ($2)',
'exif-fnumber' => 'F संख्या',
'exif-exposureprogram' => 'एक्स्पोज़र प्रोग्रेम',
'exif-spectralsensitivity' => 'स्पेक्ट्रल सेन्सिटीव्हिटी',
'exif-isospeedratings' => 'ISO गती मूल्यमापन',
'exif-shutterspeedvalue' => 'APEX शटर गती',
'exif-aperturevalue' => 'APEX ऍपर्चर',
'exif-brightnessvalue' => 'APEX ब्राईटनेस',
'exif-exposurebiasvalue' => 'एक्सपोजर बायस',
'exif-maxaperturevalue' => 'ज्यादासे ज्यादा लॅंड ऍपर्चर',
'exif-subjectdistance' => 'सब्जेक्टसे अंतर',
'exif-meteringmode' => 'मीटरींग मोड',
'exif-lightsource' => 'प्रकाश स्रोत',
'exif-flash' => 'चौन्ध',
'exif-focallength' => 'लेन्स की फोकल लंबाई',
'exif-subjectarea' => 'सब्जेक्ट एरिया',
'exif-flashenergy' => 'फ्लॅश एनर्जी',
'exif-focalplanexresolution' => 'फोकल प्लेन x रिज़ोल्यूशन',
'exif-focalplaneyresolution' => 'फोकल प्लेन Y रिज़ोल्यूशन',
'exif-focalplaneresolutionunit' => 'फोकल प्लेन रिज़ोल्यूशन एकक',
'exif-subjectlocation' => 'सब्जेक्टका स्थान',
'exif-exposureindex' => 'एक्स्पोज़र इन्डेक्स',
'exif-sensingmethod' => 'सेन्सींग पद्धती',
'exif-filesource' => 'फ़ाईल स्रोत',
'exif-scenetype' => 'सीनका प्रकार',
'exif-customrendered' => 'कस्टम इमेज प्रोसेसिंग',
'exif-exposuremode' => 'एक्स्पोज़र मोड',
'exif-whitebalance' => 'व्हाईट बॅलन्स',
'exif-digitalzoomratio' => 'डिजिटल झूम अनुमान',
'exif-focallengthin35mmfilm' => '३५ मी.मी. फ़ील्ममें फोकल लंबाई',
'exif-scenecapturetype' => 'सीन कॅप्चर प्रकार',
'exif-gaincontrol' => 'सीन नियंत्रण',
'exif-contrast' => 'कॉन्ट्रास्ट',
'exif-saturation' => 'सॅचूरेशन',
'exif-sharpness' => 'शार्पनेस',
'exif-devicesettingdescription' => 'उपकरण रचना वर्णन',
'exif-subjectdistancerange' => 'सब्जेक्ट डिस्टन्स रेंज',
'exif-imageuniqueid' => 'यूनिक चित्र ID',
'exif-gpsversionid' => 'GPS टॅग अवतरण',
'exif-gpslatituderef' => 'उत्तर या दक्षिण अक्षांश',
'exif-gpslatitude' => 'अक्षांश',
'exif-gpslongituderef' => 'पूर्व या पश्चिम रेखांश',
'exif-gpslongitude' => 'रेखांश',
'exif-gpsaltituderef' => 'अल्टिट्यूड संदर्भ',
'exif-gpsaltitude' => 'अल्टिट्यूड',
'exif-gpstimestamp' => 'GPS समय (एटोमिक क्लॉक)',
'exif-gpssatellites' => 'मापनके लिये इस्तेमाल किया हुआ सैटेलाईट',
'exif-gpsstatus' => 'प्राप्तकर्ता की स्थिती',
'exif-gpsmeasuremode' => 'मेज़रमेंट मोड',
'exif-gpsdop' => 'मेज़रमेंट प्रिसिजन',
'exif-gpsspeedref' => 'गती एकक',
'exif-gpsspeed' => 'GPS रिसिवर की गती',
'exif-gpstrackref' => 'मूवमेंट दिशाके लिये संदर्भ',
'exif-gpstrack' => 'मूवमेंट डाइरेक्शन',
'exif-gpsimgdirectionref' => 'चित्रके दिशा का संदर्भ',
'exif-gpsimgdirection' => 'चित्र की दिशा',
'exif-gpsmapdatum' => 'जियोडेटिक सर्वे डाटा इस्तेमाल किया गया',
'exif-gpsdestlatituderef' => 'लक्ष्यके अक्षांशका संदर्भ',
'exif-gpsdestlatitude' => 'अक्षांश लक्ष्य',
'exif-gpsdestlongituderef' => 'लक्ष्यके रेखांशका संदर्भ',
'exif-gpsdestlongitude' => 'लक्ष्य का रेखांश',
'exif-gpsdestbearingref' => 'लक्ष्य के बियरींग का संदर्भ',
'exif-gpsdestbearing' => 'लक्ष्यका बेअरिंग',
'exif-gpsdestdistanceref' => 'लक्ष्य के अंतर का संदर्भ',
'exif-gpsdestdistance' => 'लक्ष्य का अंतर',
'exif-gpsprocessingmethod' => 'GPS प्रक्रीया पद्धतीका नाम',
'exif-gpsareainformation' => 'GPS विभागका नाम',
'exif-gpsdatestamp' => 'GPS दिनांक',
'exif-gpsdifferential' => 'GPS डिफरन्शियर करेक्शन',
'exif-jpegfilecomment' => 'JPEG फ़ाइल टिप्पणी',
'exif-keywords' => 'कीवर्ड',
'exif-worldregioncreated' => 'दुनिया क्षेत्र जहां ये चित्र लिया गया है',
'exif-countrycreated' => 'देश जहां ये चित्र लिया गया है',
'exif-countrycodecreated' => 'देश के लिए कोड जहां ये चित्र लिया गया है',
'exif-provinceorstatecreated' => 'प्रांत या राज्य जहां ये चित्र लिया गया है',
'exif-citycreated' => 'शहर जहां ये चित्र लिया गया है',
'exif-sublocationcreated' => 'यह चित्र शहर कि इस जगह जहा मे लिया गया था',
'exif-worldregiondest' => 'दुनिया का क्षेत्र दिखाया हे',
'exif-countrydest' => 'देश दिखया गया हे',
'exif-countrycodedest' => 'देश के लिए कोड दिखाया गया हे',
'exif-provinceorstatedest' => 'प्रांत या राज्य दिखाया गया हे',
'exif-citydest' => 'शहर दिखया गया हे',
'exif-sublocationdest' => 'शहर के एक जगह दिखाया गया हे',
'exif-objectname' => 'लघु शीर्षक',
'exif-specialinstructions' => 'विशेष निर्देश',
'exif-headline' => 'शीर्षक',
'exif-credit' => 'क्रेडिट/प्रदाता',
'exif-source' => 'स्रोत',
'exif-editstatus' => 'छवि की संपादकीय स्थिति',
'exif-urgency' => 'तात्कालिकता',
'exif-locationdest' => 'स्थान दिखाया गया हे',
'exif-locationdestcode' => 'स्थान का कोड दिखाया गया हे',
'exif-objectcycle' => ' दिन के समय जिस्केलियए यह मीडिया है',
'exif-contact' => 'संपर्क जानकारी',
'exif-writer' => 'लेखक',
'exif-languagecode' => 'भाषा',
'exif-iimversion' => 'आईआईएम संस्करण',
'exif-iimcategory' => 'श्रेणी',
'exif-iimsupplementalcategory' => 'पूरक श्रेणियाँ',
'exif-datetimeexpires' => 'के बाद का उपयोग न करें',
'exif-datetimereleased' => 'पर जारी',
'exif-originaltransmissionref' => 'मूल प्रसारण स्थान कोड',
'exif-identifier' => 'पहचानकर्ता',
'exif-lens' => 'इस्तेमाल किया गया लेंस',
'exif-serialnumber' => 'कैमरे का क्रमांक नंबर',
'exif-cameraownername' => 'कैमरे के मालिक',
'exif-label' => 'लेबल',
'exif-datetimemetadata' => 'दिनांक जब मेटाडेटा अंतिम बार संशोधित किया गया',
'exif-nickname' => 'छवि के अनौपचारिक नाम',
'exif-rating' => 'दर्ज़ा (5 से)',
'exif-rightscertificate' => 'अधिकार प्रबंधन प्रमाण पत्र',
'exif-copyrighted' => 'कॉपीराइट स्थिति',
'exif-copyrightowner' => 'कोपीराईट का धारक',
'exif-usageterms' => 'उपयोग के शर्ते',
'exif-webstatement' => 'ऑनलाइन कॉपीराइट वक्तव्य',
'exif-originaldocumentid' => 'मूल दस्तावेज़ का अनन्य पहचान',
'exif-licenseurl' => 'कॉपीराइट लाइसेंस के लिए यूआरएल',
'exif-morepermissionsurl' => 'वैकल्पिक सूचना लाइसेंस',
'exif-attributionurl' => 'जब यह काम दोबारा इश्तेमाल करें, कृपया लिंक करें',
'exif-preferredattributionname' => 'जब यह काम दोबारा इश्तेमाल करें, कृपया क्रेडिट करें',
'exif-pngfilecomment' => 'PNG फ़ाइल टिप्पणी',
'exif-disclaimer' => 'शर्त्तें',
'exif-contentwarning' => 'सामग्री चेतावनी',
'exif-giffilecomment' => 'GIF फ़ाइल टिप्पणी',
'exif-intellectualgenre' => 'आइटम का प्रकार',
'exif-subjectnewscode' => 'विषय कोड',
'exif-scenecode' => 'IPTC दृश्य कोड',
'exif-event' => 'चित्रित घटना',
'exif-organisationinimage' => 'चित्रित संगठन',
'exif-personinimage' => 'व्यक्ति चित्रण',
'exif-originalimageheight' => 'छवि की ऊँचाई उभरा होने से पहले',
'exif-originalimagewidth' => 'छवि की चौड़ाई उभरा होने से पहले',

# EXIF attributes
'exif-compression-1' => 'अनकॉम्प्रेस्स्ड',
'exif-compression-3' => 'CCITT ग्रुप 3 फ़ैक्स एनकोडिंग',
'exif-compression-4' => 'CCITT ग्रुप 4 फ़ैक्स एनकोडिंग',

'exif-copyrighted-true' => 'कॉपीराईट',
'exif-copyrighted-false' => 'पब्लिक डोमेन',

'exif-unknowndate' => 'अज्ञात तारीख',

'exif-orientation-1' => 'सामान्य',
'exif-orientation-2' => 'हॉरिज़ॉन्टली बदला',
'exif-orientation-3' => '180° घूमाया',
'exif-orientation-4' => 'वर्टिकली बदला',
'exif-orientation-5' => '90° CCW घूमाया और वर्टिकली बदला',
'exif-orientation-6' => '90° CCW घुमाया',
'exif-orientation-7' => '90° CW घूमाया और वर्टिकली बदला',
'exif-orientation-8' => '90° CW घुमाया',

'exif-planarconfiguration-1' => 'चंकी फ़रमैट',
'exif-planarconfiguration-2' => 'प्लेनर फ़रमैट',

'exif-componentsconfiguration-0' => 'अस्तित्वमें नहीं',

'exif-exposureprogram-0' => 'अव्यक्त',
'exif-exposureprogram-1' => 'मैन्युअल',
'exif-exposureprogram-2' => 'सामान्य प्रोग्रॅम',
'exif-exposureprogram-3' => 'ऍपर्चर प्राथमिकता',
'exif-exposureprogram-4' => 'शटर प्राथमिकता',
'exif-exposureprogram-5' => 'क्रियेटीव्ह कार्यक्रम (फ़ील्ड की डेप्थ की तरफ बायस्‌ड)',
'exif-exposureprogram-6' => 'ऐक्शन कार्यक्रम (शटर की गती की तरफ बायस्‌ड)',
'exif-exposureprogram-7' => 'पोर्ट्रेट मोड (क्लोज़‍अप फ़ोटो के लिये)',
'exif-exposureprogram-8' => 'लैंडस्केप मोड (बैकग्राउंड के साथ लैंडस्केप फोटो)',

'exif-subjectdistance-value' => '$1 मीटर',

'exif-meteringmode-0' => 'अज्ञात',
'exif-meteringmode-1' => 'ऍव्हरेज',
'exif-meteringmode-2' => 'सेंटरवेटेडएवरेज',
'exif-meteringmode-3' => 'स्पॉट',
'exif-meteringmode-4' => 'मल्टीस्पॉट',
'exif-meteringmode-5' => 'पॅटर्न',
'exif-meteringmode-6' => 'पार्शिअल',
'exif-meteringmode-255' => 'अन्य',

'exif-lightsource-0' => 'अज्ञात',
'exif-lightsource-1' => 'सूर्यप्रकाश',
'exif-lightsource-2' => 'फ्लूरोसेंट',
'exif-lightsource-3' => 'टंगस्ट्न (इनकॅन्‍डेसेंट प्रकाश)',
'exif-lightsource-4' => 'फ्लॅश',
'exif-lightsource-9' => 'अच्छा वातावरण',
'exif-lightsource-10' => 'बादलसे युक्त वातावरण',
'exif-lightsource-11' => 'शेड',
'exif-lightsource-12' => 'डेलाईट फ्लूरोसेंट (D 5700 – 7100K)',
'exif-lightsource-13' => 'डे व्हाईट फ्लूरोसेंट (N 4600 – 5400K)',
'exif-lightsource-14' => 'कूल व्हाईट फ्लूरोसेंट (W 3900 – 4500K)',
'exif-lightsource-15' => 'व्हाईट फ्लूरोसेंट (WW 3200 – 3700K)',
'exif-lightsource-17' => 'प्रमाण प्रकाश A',
'exif-lightsource-18' => 'प्रमाण प्रकाश B',
'exif-lightsource-19' => 'प्रमाण प्रकाश C',
'exif-lightsource-21' => 'D75',
'exif-lightsource-24' => 'ISO स्टूडीयो टंगस्टन',
'exif-lightsource-255' => 'अन्य प्रकाश स्रोत',

# Flash modes
'exif-flash-fired-0' => 'फ़्लैश नहीं चला',
'exif-flash-fired-1' => 'फ़्लैश चला',
'exif-flash-return-0' => 'कोई फ़्लैश वापसी पहचान सुविधा नहीं',
'exif-flash-return-2' => 'फ़्लैश वापसी की रोशनी नहीं मिली',
'exif-flash-return-3' => 'फ़्लैश वापसी की रोशनी मिली',
'exif-flash-mode-1' => 'फ़्लैश चलना लाज़मी',
'exif-flash-mode-2' => 'फ़्लैश न चलना लाज़मी',
'exif-flash-mode-3' => 'स्वचालित शैली',
'exif-flash-function-1' => 'कोई फ़्लैश सुविधा नहीं',
'exif-flash-redeye-1' => 'लाल-चक्षु घटाव शैली',

'exif-focalplaneresolutionunit-2' => 'इंच',

'exif-sensingmethod-1' => 'अव्यक्त',
'exif-sensingmethod-2' => 'वन चीप कलर एरीया सेन्सर',
'exif-sensingmethod-3' => 'टू चीप कलर एरीया सेन्सर',
'exif-sensingmethod-4' => 'थ्री चीप कलर एरीया सेन्सर',
'exif-sensingmethod-5' => 'कलर सिक्वेण्शीयल एरीया सेंसर',
'exif-sensingmethod-7' => 'ट्रायलिनीयर सेंसर',
'exif-sensingmethod-8' => 'कलर सिक्वेंशीयल लिनीयर सेन्सर',

'exif-filesource-3' => 'डिजिटल स्टिल कैमरा',

'exif-scenetype-1' => 'डायरेक्टली छायाचित्रीत चित्र',

'exif-customrendered-0' => 'सामान्य प्रक्रिया',
'exif-customrendered-1' => 'कस्टम प्रक्रिया',

'exif-exposuremode-0' => 'ऑटो एक्स्पोज़र',
'exif-exposuremode-1' => 'मैन्युअल एक्पोज़र',
'exif-exposuremode-2' => 'ऑटो ब्रॅकेट',

'exif-whitebalance-0' => 'ऑटो व्हाईट बैलेन्स',
'exif-whitebalance-1' => 'मॅन्यूअल व्हाईट बॅलेन्स',

'exif-scenecapturetype-0' => 'स्टॅन्डर्ड',
'exif-scenecapturetype-1' => 'लैंडस्केप',
'exif-scenecapturetype-2' => 'पोर्ट्रेट',
'exif-scenecapturetype-3' => 'नाईट सीन',

'exif-gaincontrol-0' => 'बिल्कुल नहीं',
'exif-gaincontrol-1' => 'लो गेन अप',
'exif-gaincontrol-2' => 'हाय गेन अप',
'exif-gaincontrol-3' => 'लो गेन डाउन',
'exif-gaincontrol-4' => 'हाय गेन डाउन',

'exif-contrast-0' => 'सामान्य',
'exif-contrast-1' => 'सॉफ्ट',
'exif-contrast-2' => 'हार्ड',

'exif-saturation-0' => 'सामान्य',
'exif-saturation-1' => 'निम्न सैचुरेशन',
'exif-saturation-2' => 'उच्च सैचुरेशन',

'exif-sharpness-0' => 'साधारण',
'exif-sharpness-1' => 'मृदू',
'exif-sharpness-2' => 'कठीन',

'exif-subjectdistancerange-0' => 'अज्ञात',
'exif-subjectdistancerange-1' => 'मैक्रो',
'exif-subjectdistancerange-2' => 'नजदीक से देखें',
'exif-subjectdistancerange-3' => 'दूर से देखें',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'उत्तरी अक्षांश',
'exif-gpslatitude-s' => 'दक्षिणी अक्षांश',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'पूर्वी रेखांश',
'exif-gpslongitude-w' => 'पश्चिमी रेखांश',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|meter|meters}} समुद्र स्तर से ऊपर',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|meter|meters}} समुद्र स्तर के नीचे',

'exif-gpsstatus-a' => 'मेज़रमेंट चल रहा हैं',
'exif-gpsstatus-v' => 'मेज़रमेंट इन्टरोपरेबिलीटी',

'exif-gpsmeasuremode-2' => '२-बाजूओंवाली मेज़रमेंट',
'exif-gpsmeasuremode-3' => '३-बाजूओंवाली मेज़रमेंट',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'कि.मी. प्रति घंटा',
'exif-gpsspeed-m' => 'मील प्रति घंटा',
'exif-gpsspeed-n' => 'गांठ',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'किलोमीटर',
'exif-gpsdestdistance-m' => 'मील की दूरी',
'exif-gpsdestdistance-n' => 'समुद्री मील',

'exif-gpsdop-excellent' => 'उत्कृष्ट ( $1 )',
'exif-gpsdop-good' => 'अच्छा ($1)',
'exif-gpsdop-moderate' => 'मध्यम ($1)',
'exif-gpsdop-fair' => 'निष्पक्ष ($1)',
'exif-gpsdop-poor' => 'गरिब ($1)',

'exif-objectcycle-a' => 'केवल सुबह',
'exif-objectcycle-p' => 'केवल शाम',
'exif-objectcycle-b' => 'दोनों सुबह और शाम',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'असली दिशा',
'exif-gpsdirection-m' => 'मैग्नेटिक दिशा',

'exif-ycbcrpositioning-1' => 'केंद्रित',

'exif-dc-contributor' => 'योगदानकर्ताएँ',
'exif-dc-coverage' => 'मीडिया के स्थानिक या लौकिक स्कोप',
'exif-dc-date' => 'दिनांक',
'exif-dc-publisher' => 'प्रकाशक',
'exif-dc-relation' => 'सम्बधित मीडिया',
'exif-dc-rights' => 'अधिकार',
'exif-dc-source' => 'मीडिया स्रोत',
'exif-dc-type' => 'मीडिया का प्रकार',

'exif-rating-rejected' => 'खारिज कर दियागया',

'exif-isospeedratings-overflow' => '६५५३५ से अधिक',

'exif-iimcategory-ace' => 'कला, संस्कृति और मनोरंजन',
'exif-iimcategory-clj' => 'अपराध और कानून',
'exif-iimcategory-dis' => 'आपदाओं और दुर्घटनाओं',
'exif-iimcategory-fin' => 'अर्थव्यवस्था और व्यापार',
'exif-iimcategory-edu' => 'शिक्षा',
'exif-iimcategory-evn' => 'पर्यावरण',
'exif-iimcategory-hth' => 'स्वास्थ्य',
'exif-iimcategory-hum' => 'मानवी रुचि',
'exif-iimcategory-lab' => 'श्रम',
'exif-iimcategory-lif' => 'जीवन शैली और अवकाश',
'exif-iimcategory-pol' => 'राजनीति',
'exif-iimcategory-rel' => 'धर्म और विश्वास',
'exif-iimcategory-sci' => 'विज्ञान और प्रौद्योगिकी',
'exif-iimcategory-soi' => 'सामाजिक मुद्दे',
'exif-iimcategory-spo' => 'खेल',
'exif-iimcategory-war' => 'युद्ध, संघर्ष और अशांति',
'exif-iimcategory-wea' => 'मौसम',

'exif-urgency-normal' => 'सामान्य ( $1 )',
'exif-urgency-low' => 'न्यूनतम ($1)',
'exif-urgency-high' => 'उच्चतम ($1)',
'exif-urgency-other' => 'यूज़र-डिफ़ाइंड प्राथमिकता ( $1 )',

# External editor support
'edit-externally' => 'बाहरी प्रणाली का उपयोग करते हुए इस सञ्चिका को सम्पादित करें ।',
'edit-externally-help' => '(और जानकारी के लिए [//www.mediawiki.org/wiki/Manual:External_editors जमाव निर्देश] देखें)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'सभी',
'namespacesall' => 'सभी',
'monthsall' => 'सभी',
'limitall' => 'सभी',

# E-mail address confirmation
'confirmemail' => 'ई-मेल प्रमाणित करे',
'confirmemail_noemail' => 'आपके [[Special:Preferences|सदस्य वरीयतायें]]में वैध इ-मेल एड्रेस नहीं दिया हुआ हैं।',
'confirmemail_text' => '{{SITENAME}} पर उपलब्ध इ-मेल सुविधाओंका लाभ उठाने के लिये प्रमाणित एड्रेस होना जरूरी हैं।
आपके एड्रेस पर एक कन्फर्मेशन कोड भेजने के लिये नीचे दिये बटन पर क्लिक करें।
उस मेल में एक कोड से लदी एक कड़ी होगी;
आपके इ-मेल के प्रमाणिकरण के लिये इसे अपने ब्राउज़रमें खोलें।',
'confirmemail_pending' => 'आपको पहलेसे ही एक कन्फर्मेशन कोड भेजा गया हैं;
अगर आपने हालमें खाता खोला हैं, तो नये कोड की माँग करने से पहले कुछ पल उसका इंतज़ार करें।',
'confirmemail_send' => 'एक कन्फर्मेशन कोड भेजें',
'confirmemail_sent' => 'कन्फर्मेशन इ-मेल भेज दिया।',
'confirmemail_oncreate' => 'आपके इ-मेल पते पर एक कन्फर्मेशन कोड भेजा गया हैं।
लॉग इन करने के लिये इसकी आवश्यकता नहीं हैं, पर इस विकिपर उपलब्ध इ-मेल आधारित सुविधाओंका इस्तेमाल करने के लिये यह देना जरूरी हैं।',
'confirmemail_sendfailed' => '{{SITENAME}} आपको पुष्टि डाक नहीं भेज पाई।
कृपया अपना डाक पता जाँच लें, कहीं उसमें अवैध  अक्षर तो नहीं हैं?

डाक प्रेषक ने कहा: $1',
'confirmemail_invalid' => 'गलत कन्फर्मेशन कोड।
कोड रद्द हो गया होने की संभावना हैं।',
'confirmemail_needlogin' => 'आपका इ-मेल पता प्रमाणित करने के लिये, आपको $1 करना पडेगा।',
'confirmemail_success' => 'आपका इ-मेल पता अभी प्रमाणित हो गया हैं।
अभी आप लॉग इन करके विकि का मज़ा ले सकतें हैं।',
'confirmemail_loggedin' => 'आपके इ-मेल एड्रेस का प्रमाणिकरण पूरा हो गया हैं।',
'confirmemail_error' => 'आपकी निश्चिती संजोते समय कुछ गलती हुई हैं।',
'confirmemail_subject' => '{{SITENAME}} इ-मेल एड्रेस प्रमाणिकरण',
'confirmemail_body' => 'किसीने, शायद आपने,  $1 आइपी एड्रेस से,
{{SITENAME}} पर  "$2" इस नाम से खाता खोलने की माँग की हैं।

यह खाता आपही का हैं और {{SITENAME}} पर उपलब्ध इ-मेल
सुविधा शुरू करने के लिये, नीचे दी गई कड़ी अपने ब्राउज़रमें खोलें:

$3

अगर यह माँग आपने *नहीं* की हैं, तो इसे रद्द करने के लिये
नीचे दी हुई कड़ी खोलें:

$5

यह निश्चिती कोड $4 को समाप्त हो जायेगा।',
'confirmemail_body_changed' => 'किसी ने, शायद आपने ही, आइ॰पी पते $1 से {{SITENAME}} पर "$2" सदस्य खाते का ई-मेल पता बदलकर ये पता दिया है।

इस बात की पुष्टि करने के लिये कि यह सदस्य खाता आपका ही है, और {{SITENAME}} पर ई-मेल सुविधाएँ पुनः शुरू करने के लिये निम्न लिंक अपने ब्राउज़र में खोलें:

$3

यदि यह सदस्य खाता आपका नहीं है, ई-मेल पुष्टि रद्द करने के लिये निम्न लिंक पर जाएँ:

$5

ये पुष्टिकरण लिंक $6 को $7 बजे के बाद काम नहीं करेंगे।',
'confirmemail_invalidated' => 'इ-मेल एड्रेस प्रमाणिकरण रद्द कर दिया गया हैं',
'invalidateemail' => 'इ-मेल प्रमाणिकरण रद्द करें',

# Scary transclusion
'scarytranscludedisabled' => '[आंतरविकि ट्रान्स्क्लुडिंग बंद हैं]',
'scarytranscludefailed' => '[$1 के लिये साँचा मँगा नहीं पाए]',
'scarytranscludetoolong' => '[यूआरएल बहुत लंबा है]',

# Delete conflict
'deletedwhileediting' => "'''Warning''': आपने जब से संपादन शुरू किया है, उसके बाद से यह पृष्ठ ही मिटा दिया गया है!",
'confirmrecreate' => "सदस्य [[User:$1|$1]] ([[User talk:$1|वार्ता]]) ने आपके द्वारा संपादन शुरू होने के बाद यह पृष्ठ निम्नलिखित कारण देकर हटाया हैं:
: ''$2''
क्या आप इसे फिरसे बनाना चाहतें हैं, इसकी निश्चिती करें।",
'confirmrecreate-noreason' => 'जब आपने इस पृष्ठ का सम्पादन शुरू किया था, उसके बाद से सदस्य [[User:$1|$1]] ([[User talk:$1|talk]]) ने इसे हटा दिया है।  कृपया पुष्टि करें कि आप इस पृष्ठ को पुनः बनाना चाहते हैं।',
'recreate' => 'फिरसे बनायें',

# action=purge
'confirm_purge_button' => 'ओके',
'confirm-purge-top' => 'क्या आप यह पृष्ठ का कैश ख़ाली करने चाहिए?',
'confirm-purge-bottom' => 'किसी पृष्ठ को मिटाने से संचिका साफ़ हो जाती है और इस वजह से ताज़ातरीन संस्करण प्रकट हो जाता है।',

# action=watch/unwatch
'confirm-watch-button' => 'ठीक है',
'confirm-watch-top' => 'इस पृष्ठ को अपने ध्यानसूची में जोड़ें?',
'confirm-unwatch-button' => 'ठीक है',
'confirm-unwatch-top' => 'यह पृष्ठ अपने ध्यानसूचीसे हटाएं?',

# Separators for various lists, etc.
'semicolon-separator' => ';',
'autocomment-prefix' => '-',

# Multipage image navigation
'imgmultipageprev' => '← पिछला पृष्ठ',
'imgmultipagenext' => 'अगला पृष्ठ →',
'imgmultigo' => 'जायें!',
'imgmultigoto' => '$1 पृष्ठ पर जायें',

# Table pager
'ascending_abbrev' => 'asc',
'descending_abbrev' => 'ज़ानकारी',
'table_pager_next' => 'अगला पृष्ठ',
'table_pager_prev' => 'पिछला पृष्ठ',
'table_pager_first' => 'पहला पृष्ठ',
'table_pager_last' => 'आखिरी पृष्ठ',
'table_pager_limit' => 'एक पृष्ठपर $1 आइटम दर्शायें',
'table_pager_limit_label' => 'आइटम प्रति पृष्ठ:',
'table_pager_limit_submit' => 'जायें',
'table_pager_empty' => 'रिज़ल्ट नहीं',

# Auto-summaries
'autosumm-blank' => 'पृष्ठ को खाली किया',
'autosumm-replace' => "पृष्ठ को '$1' से बदल रहा है।",
'autoredircomment' => '[[$1]] को अनुप्रेषित',
'autosumm-new' => "'$1' के साथ नया पृष्ठ बनाया",

# Size units
'size-bytes' => '$1 B',
'size-kilobytes' => '$1 KB',
'size-megabytes' => '$1 MB',
'size-gigabytes' => '$1 GB',

# Live preview
'livepreview-loading' => 'लोड हो रहा हैं...',
'livepreview-ready' => 'लोड हो रहा हैं... तैयार!',
'livepreview-failed' => 'लाइव झलक नहीं दिखा पायें। साधारण झलक देखें।',
'livepreview-error' => 'संपर्क नहीं कर पायें: $1 "$2"। साधारण झलक देखें।',

# Friendlier slave lag warnings
'lag-warn-normal' => 'पिछले $1 {{PLURAL:$1|सेकिंड|सेकिंड}} में हुए बदलाव संभवतः इस सूची में नहीं आएँगे।',
'lag-warn-high' => 'आँकड़ाकोष सेवक में अधिक देर की वजह से $1 {{PLURAL:$1|सेकिंड|सेकिंड}} से पहले तक के बदलाव ही इस सूची में निश्चित रूप से दिखेंगे।',

# Watchlist editor
'watchlistedit-numitems' => 'आपकी ध्यानसूची में {{PLURAL:$1|1 शीर्षक|$1 शीर्षक}} हैं, जिसमें वार्ता पृष्ठ नहीं गिनें हैं।',
'watchlistedit-noitems' => 'आपकी ध्यानसूचीमें शीर्षक नहीं हैं।',
'watchlistedit-normal-title' => 'ध्यानसूची बदलें',
'watchlistedit-normal-legend' => 'ध्यानसूची से शीर्षक हटायें',
'watchlistedit-normal-explain' => 'आपकी ध्यानसूची में सूचीबद्ध पृष्ठ नीचे दिये गये हैं।
पृष्ठ सूची से हटाने के लिये उसके आगे दिये बक्से पर क्लिक करें, और "{{int:Watchlistedit-normal-submit}}" पर क्लिक करें।
आप [[Special:EditWatchlist/raw|रॉ ध्यानसूची का संपादन]] भी कर सकते हैं।',
'watchlistedit-normal-submit' => 'शीर्षक हटायें',
'watchlistedit-normal-done' => 'आपकी ध्यानसूचीसे {{PLURAL:$1|1शीर्षक|$1 शीर्षक}} हटा दिये:',
'watchlistedit-raw-title' => 'कच्ची ध्यानसूची बदलें',
'watchlistedit-raw-legend' => 'कच्ची ध्यानसूची बदलें',
'watchlistedit-raw-explain' => 'आपकी ध्यानसूची में सूचीबद्ध पृष्ठ नीचे दिये गये हैं, और वे सूची से निकालकर या बढ़ाकर बदले जा सकते हैं;
हर लाइन पर एक शीर्षक देकर।
जब पूर्ण हो जाए, तो "{{int:Watchlistedit-raw-submit}}" पर क्लिक करें।
आप [[Special:EditWatchlist|स्टैण्डर्ड संपादक का इस्तेमाल]] भी कर सकते हैं।',
'watchlistedit-raw-titles' => 'शीर्षक:',
'watchlistedit-raw-submit' => 'ध्यानसूची अपडेट करें',
'watchlistedit-raw-done' => 'आपकी ध्यानसूची अपडेट कर दी गई हैं',
'watchlistedit-raw-added' => '{{PLURAL:$1|1शीर्षक|$1 शीर्षक}} बढा दिये:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1शीर्षक|$1 शीर्षक}} हटा दिये:',

# Watchlist editing tools
'watchlisttools-view' => 'आधारित बदलाव देखें',
'watchlisttools-edit' => 'ध्यानसूची देखें एवं संपादित करें',
'watchlisttools-raw' => 'रॉ ध्यानसूची देखें एवम्‌ संपादित करें',

# Iranian month names
'iranian-calendar-m1' => 'फ़र्वर्दिन',
'iranian-calendar-m2' => 'ओर्दिबेहेश्ट',
'iranian-calendar-m3' => 'खोर्दाद',
'iranian-calendar-m4' => 'तीर',
'iranian-calendar-m5' => 'मोर्दाद',
'iranian-calendar-m6' => 'शाहरीवार',
'iranian-calendar-m7' => 'मेहर',
'iranian-calendar-m8' => 'अबान',
'iranian-calendar-m9' => 'अज़र',
'iranian-calendar-m10' => 'डे',
'iranian-calendar-m11' => 'बाहमान',
'iranian-calendar-m12' => 'एसफण्ड (Esfand)',

# Hebrew month names
'hebrew-calendar-m1' => 'तिश्रेई (Tishrei)',
'hebrew-calendar-m2' => 'शेस्वान (Cheshvan)',
'hebrew-calendar-m3' => 'किस्लेव (Kislev)',
'hebrew-calendar-m4' => 'टेवेट (Tevet)',
'hebrew-calendar-m5' => 'शेवट (Shevat)',
'hebrew-calendar-m6' => 'अडार',
'hebrew-calendar-m6a' => 'अडार I (Adar)',
'hebrew-calendar-m6b' => 'अडार II (Adar)',
'hebrew-calendar-m7' => 'निसान (Nisan)',
'hebrew-calendar-m8' => 'अय्यर (Iyar)',
'hebrew-calendar-m9' => 'सिवान (Sivan)',
'hebrew-calendar-m10' => 'तामुज़ (Tamuz)',
'hebrew-calendar-m11' => 'एवी (Av)',
'hebrew-calendar-m12' => 'एलुल (Elul)',
'hebrew-calendar-m1-gen' => 'तिश्रेई (Tishrei)',
'hebrew-calendar-m2-gen' => 'शेस्वान (Cheshvan)',
'hebrew-calendar-m3-gen' => 'किस्लेव (Kislev)',
'hebrew-calendar-m4-gen' => 'टेवेट (Tevet)',
'hebrew-calendar-m5-gen' => 'शेवट (Shevat)',
'hebrew-calendar-m6-gen' => 'अडार',
'hebrew-calendar-m6a-gen' => 'अडार I (Adar)',
'hebrew-calendar-m6b-gen' => 'अडार II (Adar)',
'hebrew-calendar-m7-gen' => 'निसान (Nisan)',
'hebrew-calendar-m8-gen' => 'अय्यर (Iyar)',
'hebrew-calendar-m9-gen' => 'सिवान (Sivan)',
'hebrew-calendar-m10-gen' => 'तामुज़ (Tamuz)',
'hebrew-calendar-m11-gen' => 'एवी (Av)',
'hebrew-calendar-m12-gen' => 'एलुल (Elul)',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|वार्ता]])',

# Core parser functions
'unknown_extension_tag' => 'गलत एक्स्टेंशन टैग "$1"',
'duplicate-defaultsort' => '\'\'\'Warning:\'\'\' पुरानी मूल क्रमांकन कुंजी "$1" के बजाय अब मूल क्रमांकन कुंजी "$2" होगी।',

# Special:Version
'version' => 'रूपान्तर',
'version-extensions' => 'इन्स्टॉल की हुई एक्स्टेंशन',
'version-specialpages' => 'विशेष पृष्ठ',
'version-parserhooks' => 'पार्सर हूक',
'version-variables' => 'वेरिएबल',
'version-antispam' => 'अवांछित-ईमेल रोकथाम',
'version-skins' => 'त्वचाएं',
'version-other' => 'अन्य',
'version-mediahandlers' => 'मीडिया संचालक',
'version-hooks' => 'हूक',
'version-extension-functions' => 'विस्तार प्रकार्यात्मकता',
'version-parser-extensiontags' => 'पार्सर एक्स्टेंशन टैग',
'version-parser-function-hooks' => 'पार्सर कार्य हूक',
'version-hook-name' => 'हूक नाम',
'version-hook-subscribedby' => 'ने सदस्यत्व लिया',
'version-version' => '(अवतरण $1)',
'version-license' => 'अनुज्ञापत्र',
'version-poweredby-credits' => "इस विकि संचालित है  '''[//www.mediawiki.org/ MediaWiki]''', कॉपीराइट © 2001 - $1  $2 ।",
'version-poweredby-others' => 'अन्य',
'version-software' => 'इन्स्टॉल की हुई प्रणाली',
'version-software-product' => 'प्रोडक्ट',
'version-software-version' => 'अवतरण',
'version-entrypoints-header-url' => 'यू॰आर॰एल',

# Special:FilePath
'filepath' => 'सञ्चिका पथ',
'filepath-page' => 'सञ्चिका:',
'filepath-submit' => 'जाइए',
'filepath-summary' => 'यह विशेष पृष्ठ सञ्चिका का पूरा पथ देता है।
चित्र पूरे रिज़ोल्यूशन के साथ दिखता हैं, अन्य सञ्चिका प्रकार उनके सम्बन्धित प्रोग्रेम डाइरेक्टरी से आरम्भ होते हैं।',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'फ़ाईल द्विरावृत्ति खोजें',
'fileduplicatesearch-summary' => 'हैश वैल्यू के अनुसार फ़ाईल की द्विरावृत्ति खोजें।',
'fileduplicatesearch-legend' => 'द्विरावृत्ति के लिये खोजें',
'fileduplicatesearch-filename' => 'फ़ाईलनाम:',
'fileduplicatesearch-submit' => 'खोजें',
'fileduplicatesearch-info' => '$1 × $2 पीक्सेल<br />फ़ाईल का आकार: $3<br />MIME प्रकार: $4',
'fileduplicatesearch-result-1' => 'फ़ाईल  "$1" में द्विरावृत्ति नही हैं।',
'fileduplicatesearch-result-n' => 'फ़ाईल "$1" में {{PLURAL:$2|1 द्विरावृत्ति|$2 द्विरावृत्तियाँ}} मिले हैं।',
'fileduplicatesearch-noresults' => 'कोई फ़ाइल नाम "$1" मिला नहीं ।',

# Special:SpecialPages
'specialpages' => 'विशेष पृष्ठ',
'specialpages-note' => '----
* साधारण विशेष पृष्ठ।
* <span class="mw-specialpagerestricted">प्रतिबंधित विशेष पृष्ठ।</span>',
'specialpages-group-maintenance' => 'अनुरक्षण रिपोर्ट',
'specialpages-group-other' => 'अन्य विशेष पृष्ठ',
'specialpages-group-login' => 'सत्र आरम्भ / खाता खोलें',
'specialpages-group-changes' => 'हाल ही में हुए परिवर्तन एवं अभिलेख',
'specialpages-group-media' => 'मीडिया रिपोर्ट एवं अपलोड',
'specialpages-group-users' => 'सदस्य एवं अधिकार',
'specialpages-group-highuse' => 'अत्यधिक उपयोगी पृष्ठ',
'specialpages-group-pages' => 'पन्नों की सूचियाँ',
'specialpages-group-pagetools' => 'पृष्ठ औज़ार',
'specialpages-group-wiki' => 'विकि डाटा और औज़ार',
'specialpages-group-redirects' => 'विशेष पृष्ठ अनुप्रेषित कर रहें हैं',
'specialpages-group-spam' => 'स्पैम औज़ार',

# Special:BlankPage
'blankpage' => 'खाली पृष्ठ',
'intentionallyblankpage' => 'यह पृष्ठ जानबूझ कर खाली छोड़ा गया है।',

# External image whitelist
'external_image_whitelist' => ' #यह लाइन जैसी है वैसी ही छोड़ दें<pre>
 #नीचे रेगुलर एक्सप्रेशन के टुकड़े लिखें(बस वही हिस्सा जो // के बीच में आता है)
 #इन एक्सप्रेशन का बाहरी (hotlinked) छवियों के यू॰आर॰एल के साथ मिलान किया जाएगा
 #जो छवियाँ मिलान करेंगी, उन्हें प्रदर्शित किया जाएगा, अन्यथा केवल छवि की कड़ी दिखायी जाएगी
 # # से शुरू होने वाली लाइनें टिप्पणी मानी जाती हैं
 # इस केस-असंवेदी है

 #सब रेगुलर एक्सप्रेशन टुकड़े इस लाइन से ऊपर रखें। यह लाइन जैसी है वैसी ही छोड़ दें</pre>',

# Special:Tags
'tags' => 'वैध बदलाव चिप्पियाँ',
'tag-filter' => '[[Special:Tags|चिप्पी]] छननी:',
'tag-filter-submit' => 'छननी',
'tags-title' => 'चिप्पियाँ',
'tags-intro' => 'यह पृष्ठ अर्थ सहित वह चिप्पियाँ दर्शाता है जिनका कोई तंत्रांश किसी संपादन पर निशान लगाने के लिए इस्तेमाल कर सकता है।',
'tags-tag' => 'चिप्पी का नाम',
'tags-display-header' => 'बदलाव सूचियों में प्रदर्शन',
'tags-description-header' => 'अर्थ का पूरा वर्णन',
'tags-hitcount-header' => 'चिप्पी वाले बदलाव',
'tags-edit' => 'सम्पादन',
'tags-hitcount' => '$1 {{PLURAL:$1|बदलाव|बदलाव}}',

# Special:ComparePages
'comparepages' => 'पृष्ठों की तुलना करें',
'compare-selector' => 'पृष्ठ संशोधन की तुलना करें',
'compare-page1' => 'पृष्ठ १',
'compare-page2' => 'पृष्ठ २',
'compare-rev1' => 'पुनरीक्षण १',
'compare-rev2' => 'पुनरीक्षण २',
'compare-submit' => 'तुलना करें',
'compare-invalid-title' => 'आपके द्वारा निर्दिष्ट शीर्षक अमान्य है।',
'compare-title-not-exists' => 'आपके द्वारा निर्दिष्ट शीर्षक मौजूद नहीं है।',
'compare-revision-not-exists' => 'आपके द्वारा निर्दिष्ट संशोधन मौजूद नहीं है।',

# Database error messages
'dberr-header' => 'इस विकि को कुछ दिक्कत आ रही है',
'dberr-problems' => 'क्षमा करें! इस जालस्थल को कुछ तकनीकी परेशानियों का सामना करना पड़ रहा है।',
'dberr-again' => 'कुछ मिनट रुकने के बाद फिर से चढ़ाएँ।',
'dberr-info' => '(आँकड़ाकोष सेवक से संपर्क नहीं हो पा रहा:$1)',
'dberr-usegoogle' => 'इस बीच आप गूगल से खोज करने की कोशिश कर सकते हैं।',
'dberr-outofdate' => 'ध्यान दे, हो सकता है कि हमारी सामग्री से संबंधित उनकी सूची बासी हो।',
'dberr-cachederror' => 'यह अनुरोधित पन्ने की संचित प्रति है, हो सकता है यह ताज़ी न हो।',

# HTML forms
'htmlform-invalid-input' => 'आपके द्वारा प्रदत्त कुछ सामग्री में समस्याएँ हैं',
'htmlform-select-badoption' => 'आपके द्वारा निर्दिष्ट मान वैध विकल्प नहीं है।',
'htmlform-int-invalid' => 'आपके द्वारा निर्दिष्ट मान पूर्णांक नहीं है।',
'htmlform-float-invalid' => 'आपके द्वारा निर्दिष्ट मान संख्या नहीं है।',
'htmlform-int-toolow' => 'आपके द्वारा निर्दिष्ट मान न्यूनतम $1 से कम है',
'htmlform-int-toohigh' => 'आपके द्वारा निर्दिष्ट मान अधिकतम $1 से ज़्यादा है',
'htmlform-required' => 'इस मान की आवश्यकता है',
'htmlform-submit' => 'जमा करें',
'htmlform-reset' => 'बदलाव पुरानी स्थिति पर लाएँ',
'htmlform-selectorother-other' => 'अन्य',

# SQLite database support
'sqlite-has-fts' => '$1 पूर्ण पाठ खोज समर्थन के साथ',
'sqlite-no-fts' => '$1पूर्ण-पाठ खोज समर्थन के बिना',

# New logging system
'logentry-delete-delete' => '$1 ने पृष्ठ $3 हटा दिया',
'logentry-delete-restore' => '$1 बहाल पृष्ठ $3',
'logentry-delete-event' => '$1 changed दृश्यता के  {{PLURAL:$5|a log event|$5 log events}} पर $3: $4',
'logentry-delete-revision' => '$1 ने $3 पृष्ठ के {{PLURAL:$5|एक अवतरण|$5 अवतरणों}} की दृश्यता बदली: $4',
'logentry-delete-event-legacy' => '$1 ने $3 पृष्ठ पर लॉग क्रियाओं की दृश्यता बदली',
'logentry-delete-revision-legacy' => '$1 ने $3 पृष्ठ पर अवतरणों की दृश्यता बदली',
'logentry-suppress-delete' => '$1 suppressed पृष्ठ $3',
'logentry-suppress-event' => '$1 ने गुप्त रूप से $3 पृष्ठ पर निम्न {{PLURAL:$5|एक लॉग क्रिया|$5 लॉग क्रियाओं}} की दृश्यता बदली: $4',
'logentry-suppress-revision' => '$1 चुपके से  changed की दृश्यता {{PLURAL:$5|a revision|$5 revisions}} पृष्ठ पर $3: $4',
'logentry-suppress-event-legacy' => '$1 चुपके से  changed पर लॉग इन घटनाओं की दृश्यता $3',
'logentry-suppress-revision-legacy' => '$1 चुपके से changed पृष्ठ पर संशोधन की दृश्यता $3',
'revdelete-content-hid' => 'सामग्री छिपाई गई',
'revdelete-summary-hid' => 'सम्पादन सारांश छिपाया गया',
'revdelete-uname-hid' => 'सदस्यनाम छिपाया गया',
'revdelete-content-unhid' => 'सामग्री फिर से सार्वजनिक की गई',
'revdelete-summary-unhid' => 'सम्पादन सारांश फिर सार्वजनिक किया गया',
'revdelete-uname-unhid' => 'सदस्यनाम फिर सार्वजनिक किया गया',
'revdelete-restricted' => 'प्रबंधकोंको प्रतिबंधित किया',
'revdelete-unrestricted' => 'प्रबंधकोंके प्रबंधन हटायें',
'logentry-move-move' => '$1 ने $3 पृष्ठ $4 पर स्थानांतरित किया',
'logentry-move-move-noredirect' => '$1 ने $3 पर पुनर्निर्देश छोड़े बिना उसे $4 पर स्थानांतरित किया',
'logentry-move-move_redir' => '$1 ने $4 से पुनर्निर्देश हटाकर $3 को उसपर स्थानांतरित किया',
'logentry-move-move_redir-noredirect' => '$1 ने $4 से पुनार्निर्देश हटाकर $3 पर पुनर्निर्देश छोड़े बिना $3 को $4 पर स्थानांतरित किया',
'logentry-patrol-patrol' => '$1 ने $3 पृष्ठ के $4 अवतरण को देखा हुआ चिन्हित किया',
'logentry-patrol-patrol-auto' => '$1 ने $3 पृष्ठ के $4 अवतरण को स्वचालित रूप से देखा हुआ चिन्हित किया',
'logentry-newusers-newusers' => 'सदस्य खाता $1 बनाया गया',
'logentry-newusers-create' => 'सदस्य खाता $1 बनाया गया',
'logentry-newusers-create2' => 'सदस्य खाता $3 $1 द्वारा बनाया गया था',
'logentry-newusers-autocreate' => 'खाते $1 स्वचालित रूप से बनाया गया',
'newuserlog-byemail' => 'कूटशब्द इ-मेल द्वारा भेजा गया हैं',

# Feedback
'feedback-bugornote' => 'यदि आप किसी तकनीकी परेशानी को विस्तार से समझाने के लिये तैयार हैं तो कृपया [$1 बग फ़ाइल करें]।
यदि नहीं, तो आप नीचे दिये सरल फ़ॉर्म का प्रयोग कर सकते हैं। आपकी टिप्पणी आपके सदस्य नाम और आपके ब्राउज़र के नाम के सहित "[$3 $2]" पृष्ठ में जोड़ दी जाएगी।',
'feedback-subject' => 'विषय:',
'feedback-message' => 'संदेश:',
'feedback-cancel' => 'रद्द करें',
'feedback-submit' => 'प्रतिक्रिया भेजें',
'feedback-adding' => 'पृष्ठ पर प्रतिक्रिया जोड़ना ...',
'feedback-error1' => 'त्रुटि: न पहचाना गया परिणाम एपीआई से',
'feedback-error2' => 'त्रुटि: संपादन विफल रहा है',
'feedback-error3' => 'त्रुटि: एपीआई से कोई प्रतिक्रिया नहीं',
'feedback-thanks' => 'धन्यवाद! आपकी प्रतिक्रिया पृष्ठ में नियुक्त किया गया है "[ $2  $1 ]"।',
'feedback-close' => 'हो गया',
'feedback-bugcheck' => 'शानदार! जांच ले कहीं ये [ $1 known bugs] पहले से ही न हो ।',
'feedback-bugnew' => 'मैं जाँच कीया। एक नया बग रिपोर्ट करें',

# Search suggestions
'searchsuggest-search' => 'खोज',
'searchsuggest-containing' => '...से युक्त',

# API errors
'api-error-badaccess-groups' => 'आपको इस विकि के लिए फ़ाइलें अपलोड करने की अनुमति नहीं है.',
'api-error-badtoken' => 'आंतरिक त्रुटि: बुरी टोकन।',
'api-error-copyuploaddisabled' => 'URL द्वारा इस सर्वर पर अपलोड अक्षम है।',
'api-error-duplicate' => 'वहाँ {{PLURAL:$1| [ $2 अन्य फ़ाइल] | रहे हैं [ $2 कुछ अन्य फ़ाइलों]}} एक ही सामग्री के साथ साइट पर पहले से ही है.',
'api-error-duplicate-archive' => 'वहाँ {{PLURAL:$1|था [$2 कुछ अन्य फ़ाइल] |were [$2 कुछ अन्य फ़ाइलें]}}, पहले से ही {{PLURAL:$1|यह was|they थे}} परन्तु  हटा दिये गये',
'api-error-duplicate-archive-popup-title' => 'डुप्लिकेट {{PLURAL:$1| फ़ाइल | फ़ाइलें}} है कि पहले से ही हटा दिया गया है',
'api-error-duplicate-popup-title' => 'डुप्लिकेट {{PLURAL:$1| फ़ाइल | फ़ाइलें}}',
'api-error-empty-file' => 'प्रस्तुत फ़ाइल खाली था।',
'api-error-emptypage' => 'नए खाली पृष्ठ बनाने की अनुमति नहीं है।',
'api-error-fetchfileerror' => 'आंतरिक त्रुटि: जब फ़ाइल लाया जा रहा तो कुछ गलत हो गया था।',
'api-error-fileexists-forbidden' => '"$1" नाम की फ़ाइल पहले से मौजूद है और अधिलेखित नहीं की जा सकती।',
'api-error-fileexists-shared-forbidden' => '"$1" नाम की फ़ाइल पहले से साझे फ़ाइल भण्डार में मौजूद है, और अधिलेखित नहीं की जा सकती।',
'api-error-file-too-large' => 'प्रस्तुत फ़ाइल बहुत बड़ी थी।',
'api-error-filename-tooshort' => 'फ़ाइल का नाम बहुत छोटा है।',
'api-error-filetype-banned' => 'इस प्रकार की फ़ाइल पर प्रतिबंध लगा दिया है।',
'api-error-filetype-banned-type' => '$1 फ़ाइल {{PLURAL:$4|प्रकार|प्रकारों}} की अनुमति नहीं है। फ़ाइल प्रकार {{PLURAL:$3|जिसकी|जिनकी}} अनुमति है: $2।',
'api-error-filetype-missing' => 'फाईल की एक्सटेंशन लापता है.',
'api-error-hookaborted' => 'आपके द्वारा प्रयासरत संशोधन विस्तार हूक द्वारा निरस्त किया गया।',
'api-error-http' => 'आंतरिक त्रुटि: सर्वर से कनेक्ट करने में असमर्थ।',
'api-error-illegal-filename' => 'फ़ाइल नाम की अनुमति नहीं है।',
'api-error-internal-error' => 'आंतरिक त्रुटि: विकि पर अपने अपलोड प्रसंस्करण के साथ कुछ गलत हो गया था.',
'api-error-invalid-file-key' => 'आंतरिक त्रुटि: फ़ाइल अस्थायी भंडारण में नहीं पाया गया.',
'api-error-missingparam' => 'आंतरिक त्रुटि: अनुरोध पर  पैरामीटर लापता',
'api-error-missingresult' => 'आन्तरिक त्रुटि: यह प्रतिलिपि सफल निर्धारित नहीं हो सकी',
'api-error-mustbeloggedin' => 'आप फ़ाइलों को अपलोड करने के लिये आपको लॉग इन होना चाहिए.',
'api-error-mustbeposted' => 'आंतरिक त्रुटि: HTTP POST अनुरोध की आवश्यकता है.',
'api-error-noimageinfo' => 'अपलोड सफल, लेकिन सर्वर ने फ़ाइल के बारे में हमें कोई जानकारी नहीं दी.',
'api-error-nomodule' => 'आंतरिक त्रुटि: कोई अपलोड मॉड्यूल सेट नहीं',
'api-error-ok-but-empty' => 'आंतरिक त्रुटि: सर्वर से कोई जवाब नहीं.',
'api-error-overwrite' => 'मौजूदा फ़ाइल को अधिलेखित करने की अनुमति नहीं है',
'api-error-stashfailed' => 'आंतरिक त्रुटि: सर्वर अस्थाई फ़ाइल को संग्रहीत करने में विफल।',
'api-error-timeout' => 'सर्वर ने अपेक्षित समय के भीतर जवाब नहीं दिया',
'api-error-unclassified' => 'एक अज्ञात त्रुटि उत्पन्न हुई',
'api-error-unknown-code' => 'अज्ञात त्रुटि: " $1 "',
'api-error-unknown-error' => 'आंतरिक त्रुटि: आपकी फ़ाइल अपलोड करने का प्रयास करते समय कुछ गलत हो गया था।',
'api-error-unknown-warning' => 'अज्ञात चेतावनी: $1',
'api-error-unknownerror' => 'अज्ञात त्रुटि: " $1 "',
'api-error-uploaddisabled' => 'इस विकि पर अपलोड अक्षम है.',
'api-error-verification-error' => 'यह फ़ाइल दूषित हो सकती है, या गलत एक्सटेंशन है।',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|सॅकेंड}}',
'duration-minutes' => '$1 {{PLURAL:$1|मिनट}}',
'duration-hours' => '$1 {{PLURAL:$1|घंटा|घंटे}}',
'duration-days' => '$1 {{PLURAL:$1|दिन}}',
'duration-weeks' => '$1 {{PLURAL:$1|सप्ताह}}',
'duration-years' => '$1 {{PLURAL:$1|वर्ष}}',
'duration-decades' => '$1 {{PLURAL:$1|दशक}}',
'duration-centuries' => '$1 {{PLURAL:$1|शताब्दी}}',
'duration-millennia' => '$1 {{PLURAL:$1|सहस्राब्दी}}',

);
