<?php
/** Hindi (हिन्दी)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
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
	'DoubleRedirects'           => array( 'दुगुने_पुनर्निर्देश', 'दुगुने_अनुप्रेष' ),
	'EditWatchlist'             => array( 'ध्यानसूची_सम्पादन', 'ध्यानसूची_संपादन', 'ध्यानसूची_सम्पादन_करें' ),
	'Emailuser'                 => array( 'ईमेल_करें', 'सदस्य_को_ईमेल_करें' ),
	'ExpandTemplates'           => array( 'साँचे_खोलें', 'साँचे_बढ़ाएँ' ),
	'Export'                    => array( 'निर्यात' ),
	'Fewestrevisions'           => array( 'न्यूनतम_अवतरण', 'कम_सम्पादित_पृष्ठ' ),
	'FileDuplicateSearch'       => array( 'फ़ाइल_प्रति_खोज', 'फाइल_प्रति_खोज', 'संचिका_प्रति_खोज' ),
	'Filepath'                  => array( 'फ़ाइल_पथ', 'फाइल_पथ', 'संचिका_पथ' ),
	'Import'                    => array( 'आयात' ),
	'Invalidateemail'           => array( 'अप्रमाणित_ईमेल', 'अमान्य_ईमेल', 'ईमेल_अमान्य_करें' ),
	'JavaScriptTest'            => array( 'जावा_स्क्रिप्ट_परीक्षा' ),
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
	'Mostinterwikis'            => array( 'ज़्यादा_इंटेर्विकियाँ' ),
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

$magicWords = array(
	'redirect'                  => array( '0', '#अनुप्रेषित', '#REDIRECT' ),
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
$linkTrail = "/^([a-z\x{0900}-\x{0963}\x{0966}-\x{A8E0}-\x{A8FF}]+)(.*)$/sDu";

$digitGroupingPattern = "##,##,###";

