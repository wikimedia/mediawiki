<?php
/** Newari (नेपाल भाषा)
 *
 * @ingroup Language
 * @file
 *
 * @author Eukesh
 */

$namespaceNames = array(
	NS_MEDIA            => 'माध्यम',
	NS_SPECIAL          => 'विशेष',
	NS_MAIN             => '',
	NS_TALK             => 'खँलाबँला',
	NS_USER             => 'छ्येलेमि',
	NS_USER_TALK        => 'छ्येलेमि_खँलाबँला',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_खँलाबँला',
	NS_IMAGE            => 'किपा',
	NS_IMAGE_TALK       => 'किपा_खँलाबँला',
	NS_MEDIAWIKI        => 'मिडियाविकि',
	NS_MEDIAWIKI_TALK   => 'मिडियाविकि_खँलाबँला',
	NS_HELP             => 'ग्वाहालि',
	NS_HELP_TALK        => 'ग्वाहालि_खँलाबँला',
	NS_CATEGORY         => 'पुचः',
	NS_CATEGORY_TALK    => 'पुचः_खँलाबँला'
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

$messages = array(
# User preference toggles
'tog-underline'               => 'लिङ्कतेत अन्दरलाइन यानादिसँ:',
'tog-highlightbroken'         => 'स्यंगु लिङ्कतेत फर्‍म्याट यानादिसँ <a href="" class="new">like this</a> (alternative: like this<a href="" class="internal">?</a>).',
'tog-justify'                 => 'अनुच्छेद धंकादिसँ',
'tog-hideminor'               => 'न्हुगु हिलेज्याय् चिधंगु सम्पादन सुचुकादिसँ',
'tog-extendwatchlist'         => 'वाचलिस्टयात परिमार्जित याना सकल स्वेज्युगु हिलेज्या क्यनादिसँ',
'tog-usenewrc'                => 'एन्ह्यान्स्ड् न्हुगु हिलेज्या (जाभास्क्रिप्ट)',
'tog-numberheadings'          => 'अटो-ल्याखँ हेडिङ',
'tog-showtoolbar'             => 'सम्पादन टुलबार क्यनादिसँ (जाभास्क्रिप्ट)',
'tog-editondblclick'          => 'दबल क्लिकय् पौ सम्पादन यानादिसँ (जाभास्क्रिप्ट)',
'tog-editsection'             => '[सम्पादन] लिङ्कं सेक्सन सम्पादन यायेज्युगु यानादिसँ',
'tog-editsectiononrightclick' => 'सेक्सनया छ्यँआखले राइट क्लिक याना सेक्सन सम्पादन यायेज्युगु यानादिसँ (जाभास्क्रिप्ट)',
'tog-showtoc'                 => 'कन्टेण्टया धलः क्यनादिसँ (३गु स्वया अप्व शिर्षक दुगु पौया निंति)',
'tog-rememberpassword'        => 'जिगु लग इन थ्व कम्प्युतरय् लुमंकादिसँ',
'tog-editwidth'               => 'सम्पादन सन्दुकया ब्याः जायेधुंकल',
'tog-watchcreations'          => 'जिं देकागु / न्ह्यथनागु पौयात जिगु दृष्टिधलः(watchlist)य् तयादिसँ',
'tog-watchdefault'            => 'जिं सम्पादन यानागु पौयात जिगु वाचलिस्टय् तयादिसँ',
'tog-watchmoves'              => 'जिं संकागु (move) पौयात जिगु वाचलिस्टय् तयादिसँ',
'tog-watchdeletion'           => 'जिं हुयागु (delete) पौयात जिगु वाचलिस्टय् तयादिसँ',
'tog-minordefault'            => 'सकल सम्पादनतेत डिफल्टं चीधंगु यानादिसँ',
'tog-previewontop'            => 'सम्पादन सन्दुक स्वया न्ह्यः प्रिभ्यु क्यनादिसँ',
'tog-previewonfirst'          => 'न्हापाँगु सम्पादन स्वया न्ह्यः प्रिभ्यु क्यनादिसँ',
'tog-nocache'                 => 'पौ क्याशिङ (caching) डिजेबल यानादिसँ',
'tog-enotifwatchlistpages'    => 'जिगु वाचलिस्टया पौ सम्पादन जुइबिले जितः इ-मेल यानादिसँ',
'tog-enotifusertalkpages'     => 'जिगु खँल्हाबल्हा पौ सम्पादन जुइबिले जितः इ-मेल यानादिसँ',
'tog-enotifminoredits'        => 'पौया चीधंगु सम्पादनया निंतिं नं जितः इ-मेल यानादिसँ',
'tog-enotifrevealaddr'        => 'जिगु इ-मेल थाय्‌बाय्‌ नोटिफिकेसन इ-मेलय् क्यनादिसँ',
'tog-shownumberswatching'     => 'स्वयाच्वंपिं छ्यलामितेगु ल्याखँ क्यनादिसँ',
'tog-fancysig'                => 'कच्चा हस्ताक्षर (अटोम्याटिक लिङ्क मदेःकः)',
'tog-externaleditor'          => 'डिफल्टं एक्स्टर्नल एडिटर छ्यलादिसँ (एक्स्पर्टतेगु निंतिं जक्क, छिगु कम्प्युटरय् विषेश सेटिङ माः)',
'tog-externaldiff'            => 'एक्स्टर्नल डिफ् (diff) डिफल्टं छ्यलादिसँ (एक्स्पर्टतेगु निंतिं जक्क, छिगु कम्प्युटरय् विषेश सेटिङ माः)',
'tog-showjumplinks'           => '"जम्प टु" एसिसिबिलिटी लिङ्क इनेबल यानादिसँ',
'tog-uselivepreview'          => 'लाइभ प्रिभ्यु (जाभास्क्रिप्ट) इनेबल यानादिसँ (परिक्षणकाल)',
'tog-forceeditsummary'        => 'सम्पादन सार खालि त्वतिबिले जित सशंकित यानादिसँ',
'tog-watchlisthideown'        => 'जिगु सम्पादन वाचलिस्टय् सुचुकादिसँ',
'tog-watchlisthidebots'       => 'वाचलिस्टं बोत सम्पादन सुचुकादिसँ',
'tog-watchlisthideminor'      => 'वाचलिस्टं चीधंगु सम्पादन सुचुकादिसँ',
'tog-watchlisthideliu'        => 'वाचलिस्टं लग्ड इन छ्यलामितेगु सम्पादन सुचुकादिसँ',
'tog-watchlisthideanons'      => 'वाचलिस्टं अज्ञात छ्यलामिया सम्पादन सुचुकादिसँ',
'tog-nolangconversion'        => 'भेरियन्ट (variant) कन्भर्जन डिसेबल यानादिसँ',
'tog-ccmeonemails'            => 'जिं मेपिं छ्यलामितेगु छ्वइगु इ-मेलतेगु कपि जित नं छ्वयादिसँ',
'tog-diffonly'                => 'पाःगु (diffs) स्वया क्वेया पौया कण्टेण्ट क्यनादिमते',
'tog-showhiddencats'          => 'सुचुकातगु पुचःत क्यनादिसँ',
'tog-noconvertlink'           => 'लिङ्क शिर्षक हिलेज्या डिजेबल यानादिसँ',

'underline-always'  => 'न्ह्याबिलें',
'underline-never'   => 'नेभर',
'underline-default' => 'डिफल्ट ब्राउज यानादिसँ',

# Dates
'sunday'        => 'आइतबाः',
'monday'        => 'सोमबाः',
'tuesday'       => 'मङ्गलबाः',
'wednesday'     => 'बुधबाः',
'thursday'      => 'बिहिबाः',
'friday'        => 'शुक्रबाः',
'saturday'      => 'शनिबाः',
'sun'           => 'आइत',
'mon'           => 'सोम',
'tue'           => 'मङ्गल',
'wed'           => 'बुध',
'thu'           => 'बिहि',
'fri'           => 'शुक्र',
'sat'           => 'शनि',
'january'       => 'ज्यानुवरी',
'february'      => 'फेब्रुवरी',
'march'         => 'मार्च',
'april'         => 'अप्रिल',
'may_long'      => 'मे',
'june'          => 'जुन',
'july'          => 'जुलाइ',
'august'        => 'अगस्ट',
'september'     => 'सेप्टेम्बर',
'october'       => 'अक्टोबर',
'november'      => 'नोभेम्बर',
'december'      => 'डिसेम्बर',
'january-gen'   => 'ज्यानुवरी',
'february-gen'  => 'फ्रेब्रुवरी',
'march-gen'     => 'मार्च',
'april-gen'     => 'अप्रिल',
'may-gen'       => 'मे',
'june-gen'      => 'जुन',
'july-gen'      => 'जुलाइ',
'august-gen'    => 'अगस्ट',
'september-gen' => 'सेप्टेम्बर',
'october-gen'   => 'अक्टोबर',
'november-gen'  => 'नोभेम्बर',
'december-gen'  => 'डिसेम्बर',
'jan'           => 'ज्यानु',
'feb'           => 'फेब्',
'mar'           => 'मार्',
'apr'           => 'अप्रि',
'may'           => 'मे',
'jun'           => 'जुन',
'jul'           => 'जुल',
'aug'           => 'अग',
'sep'           => 'सेप्',
'oct'           => 'अक्ट्',
'nov'           => 'नोभ',
'dec'           => 'डिस',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|पुचः|पुचःतः}}',
'category_header'                => 'पुचः "$1"य् दुगु पौतः',
'subcategories'                  => 'उपपुचःतः',
'category-media-header'          => 'पुचः "$1"य् दुगु मिडिया',
'category-empty'                 => "''थ्व पुचले आःईले पौ वा मिदिया मदु।''",
'hidden-categories'              => '{{PLURAL:$1|गुप्त पुचः|गुप्त पुचःतः}}',
'hidden-category-category'       => 'गुप्त पुचःतः', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|थ्व पुचले बियातःगु उपपुचः जक्क दु।|थ्व पुचले $2 सकलय् बियातःगु {{PLURAL:$1|उपपुचः|$1 उपपुचःतः}} दु।}}',
'category-subcat-count-limited'  => 'थ्व पुचले बियातःगु {{PLURAL:$1|उपपुचः|$1 उपपुचःत}} दु।',
'category-article-count'         => '{{PLURAL:$2|थ्व पुचले क्वे बियातःगु पौ दु।|$2 सकलय् थ्व बियातःगु {{PLURAL:$1|पौ|$1 पौस}} थ्व पुचले दु।}}',
'category-article-count-limited' => 'थ्व बियातःगु {{PLURAL:$1|पौ|$1 पौस}} थ्व पुचले दु।',
'category-file-count'            => '{{PLURAL:$2|थ्व पुचले थ्व जक्क फाइल दु।|सकल $2य् क्वे बियातःगु {{PLURAL:$1|फाइल|$1 फाइलत}} थ्व पुचले दु।}}',

'about'         => 'विषयक',
'qbfind'        => 'मालादिसँ',
'moredotdotdot' => 'अप्व॰॰॰',
'mypage'        => 'जिगु पौ',
'mytalk'        => 'जिगु खं',
'navigation'    => 'परिवहन',
'and'           => 'व',

'search'           => 'मालादिसं',
'searchbutton'     => 'मालादिसँ',
'go'               => 'झासँ',
'searcharticle'    => 'झासँ',
'history'          => 'पौया इतिहास',
'history_short'    => 'इतिहास',
'info_short'       => 'जानकारी',
'printableversion' => 'ध्वायेज्युगु संस्करण',
'print'            => 'ध्वानादिसँ',
'edit'             => 'सम्पादन',
'editthispage'     => 'थ्व पौ सम्पादन यानादिसं',
'newpage'          => 'न्हुगु पौ',
'talkpagelinktext' => 'खँल्हाबँल्हा',
'specialpage'      => 'विषेश पौ',
'personaltools'    => 'निजी ज्याब्व',
'talk'             => 'खँलाबँला',
'toolbox'          => 'ज्याब्व सन्दुक',
'projectpage'      => 'ज्याखँ पौ क्येनादिसँ',
'otherlanguages'   => 'मेमेगु भाषाय्',
'jumptosearch'     => 'मालादिसँ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}या बारेय्',
'aboutpage'            => 'Project:बारेय्',
'bugreports'           => 'बग रिपोर्ट',
'bugreportspage'       => 'Project:बग रिपोर्ट',
'copyright'            => 'कण्टेण्ट $1 कथं उपलब्ध दु।',
'copyrightpagename'    => '{{SITENAME}} लेखाधिकार',
'copyrightpage'        => '{{ns:project}}:लेखाधिकार',
'currentevents'        => 'जुयाच्वँगु घटना',
'currentevents-url'    => 'Project:जुयाच्वँगु घटना',
'disclaimers'          => 'डिस्क्लेमर्स',
'disclaimerpage'       => 'Project:साधारण डिस्क्लेमर्स',
'edithelp'             => 'सम्पादन ग्वहालि',
'edithelppage'         => 'Help:सम्पादन',
'faq'                  => 'आपालं न्यनिगु न्ह्यसः (FAQ)',
'faqpage'              => 'Project:आपालं न्यनिगु न्ह्यसःत (FAQ)',
'helppage'             => 'Help:धलःपौ',
'mainpage'             => 'मू पौ',
'mainpage-description' => 'मू पौ',
'policy-url'           => 'Project:नीति',
'portal'               => 'सामाजिक मूलुखा',
'portal-url'           => 'Project:सामाजिक मूलुखा',
'privacy'              => 'दुबिस्ता नियम',
'privacypage'          => 'Project:गुप्तता नियम',

'badaccess'        => 'पर्मिसन इरर',
'badaccess-group0' => 'छिं अनुरोध यानादिगु ज्या छिं याये मछिं।',
'badaccess-groups' => 'छिं अनुरोध यानादिगु ज्या {{PLURAL:$2|पुचः|पुचःतः}}: $1या छ्यलामिं जक्क याये दु।',

'versionrequired'     => 'मिडियाविकिया $1 संस्करण माःगु',
'versionrequiredtext' => 'थ्व पौ छ्यले यात मिडियाविकिया $1 संस्करण माः।
स्वयादिसँ [[विशेष:संस्करण|संस्करण पौ]]।',

'ok'                      => 'ज्यु',
'pagetitle'               => '$1 - {{SITENAME}}',
'newmessageslink'         => 'न्हुगु सन्देश',
'newmessagesdifflink'     => 'न्हापाया हिलेज्या',
'youhavenewmessagesmulti' => '$1य् छित न्हुगु सन्देश वगु दु',
'editsection'             => 'सम्पादन',
'editold'                 => 'सम्पादन',
'editsectionhint'         => 'खण्ड सम्पादन: $1',
'showtoc'                 => 'क्यनादिसँ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-mediawiki' => 'सन्देश',
'nstab-category'  => 'पुचः',

# General errors
'laggedslavemode' => 'चेतावनी: पतिइ न्हुगु अपदेत मदेफु ।',
'readonly'        => 'देताबेस संरक्षित',
'internalerror'   => 'इन्तरनल इरर',
'viewsource'      => 'स्रोत स्वयादिसँ',

# Login and logout pages
'loginpagetitle'    => 'छ्य्‌लामि दुहां झासँ',
'yourname'          => 'छ्य्‌लामि नां:',
'yourpassword'      => 'दुथखँग्वः (पासवर्द):',
'yourpasswordagain' => 'दुथखँग्वः हानं तियादिसँ:',
'yourdomainname'    => 'छिगु दोमेन:',
'login'             => 'दुहां वनेगु',
'logout'            => 'पिने झासँ',
'userlogout'        => 'पिने झासँ',
'nologinlink'       => 'खाता न्ह्यथनादिसँ',
'createaccount'     => 'खाता चायेकादिसँ',
'youremail'         => 'इ-मेल:',
'username'          => 'छ्य्‌लामि नां:',
'yourrealname'      => 'वास्तविक नां:',
'yourlanguage'      => 'भाषा:',
'accountcreated'    => 'खाता न्ह्येथन',

# Edit pages
'savearticle'   => 'पौ मुंकादिसं',
'preview'       => 'स्वयादिसं',
'newarticle'    => '(न्हु)',
'note'          => '<strong>होस यानादिसँ:</strong>',
'previewnote'   => '<strong>थ्व पूर्वालोकन जक्क ख। छिं यानादिगु सम्पादन स्वथंगु मदुनि!</strong>',
'editing'       => '$1 सम्पादन जुयाच्वँगु दु',
'editconflict'  => 'सम्पादन द्वंगु दु: $1',
'yourtext'      => 'छिगु आखः',
'storedversion' => 'स्वथनातगु संस्करण',

# History pages
'revisionasof'     => '$1 तक्कया संस्करण',
'previousrevision' => '←पुलांगु संस्करण',

# Search results
'powersearch' => 'मालादिसँ',

# Preferences page
'mypreferences'  => 'जिगु प्राथमिकता',
'changepassword' => 'पासवर्द हिलादिसँ',
'math'           => 'गणित',
'datetime'       => 'दिं व ई',
'prefs-personal' => 'छ्य्‌लामि प्रोफाइल',
'saveprefs'      => 'स्वथनादिसँ',

# User rights
'userrights-user-editname' => 'छपू छ्य्‌लामि नां तयादिसँ:',

# Groups
'group-bot' => 'बोत',

# Recent changes
'recentchanges' => 'न्हुगु हिलेज्या',
'show'          => 'क्यनादिसँ',

# Upload
'upload' => 'फाइल अपलोड',

# Random page
'randompage' => 'छगु च्वसुइ येंकादिसं',

# Statistics
'statistics' => 'तथ्याङ्क',

'withoutinterwiki-submit' => 'क्यनादिसँ',

# Miscellaneous special pages
'newpages-username' => 'छ्येलेमि नां:',

# Special:AllPages
'allpages'    => 'सकल पौत',
'nextpage'    => 'मेगु पौ ($1)',
'allarticles' => 'सकल च्वसुत',

# Special:Categories
'categories' => 'पुचःत',

# Special:Log/newusers
'newuserlog-create-entry' => 'न्हुम्ह छ्येलेमि',

# Restrictions (nouns)
'restriction-edit' => 'सम्पादन',

# Namespace form on various pages
'namespace'      => 'नेमस्पेस:',
'blanknamespace' => '(मू)',

# Contributions
'mycontris' => 'जिगु योगदान',

# What links here
'whatlinkshere' => 'थन छु स्वाई',

# Attribution
'others' => 'मेमेगु',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'सकल',

# Auto-summaries
'autosumm-new' => 'न्हुगु पौ: $1',

# Special:SpecialPages
'specialpages' => 'विषेश पौत:',

);
