<?php
/** Maithili (मैथिली)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ashishanchinhar
 * @author Ggajendra
 * @author Manojberma77
 * @author Meno25
 * @author Rajesh
 * @author Umeshberma
 */

$fallback = 'hi';

$messages = array(
# User preference toggles
'tog-underline'               => 'लिंककेँ रेखांकित करू:',
'tog-highlightbroken'         => 'टूटल श्रृंखला <a href="" class="new">एना देखाऊ</a> (आकि फेर: एना देखाऊ<a href="" class="internal">?</a>).',
'tog-justify'                 => 'सुगढ़ बनाऊ',
'tog-hideminor'               => 'सन्निकट परिवर्त्तनमे छोट परिवर्त्तन नुकाऊ',
'tog-hidepatrolled'           => 'सन्निकट परिवर्त्तनमे छोट परिवर्त्तन नुकाऊ',
'tog-newpageshidepatrolled'   => 'नियंत्रित सम्पादनकेँ नव पन्ना सूचीसँ नुकाऊ',
'tog-extendwatchlist'         => 'ध्यानसूचीमे सभ परिवर्तन देखाऊ,खाली हालक परिवर्तन नै',
'tog-usenewrc'                => 'नीक सन्निकट परिवर्त्तन प्रयोग करू (जावास्क्रिप्ट चाही)',
'tog-numberheadings'          => 'शीर्षक स्वयं-क्रमांकित करू',
'tog-showtoolbar'             => 'संपादन ओजारपेटी देखाऊ (जावास्क्रीप्ट)',
'tog-editondblclick'          => 'दू बेर क्लीक कए पन्ना संपादित करू (जावास्क्रीप्ट)',
'tog-editsection'             => '[संपादित करू] श्रृंखला द्वारा विभाग संपादनक आज्ञा दिअ',
'tog-editsectiononrightclick' => 'ऐ खण्डक सम्पादन खण्डक शीर्षकेँ दहिन क्लिक कऽ सम्भव (जावास्क्रिप्ट चाही)',
'tog-showtoc'                 => 'अनुक्रम देखाऊ (जाहि पृष्ठ पर तीनसँ बेशी विभाग होए)',
'tog-rememberpassword'        => 'ऐ गवेषकपर हमर कूटशब्द (बेशीसं बेशी $1 {{PLURAL:$1|दिन धरि| कएक दिन धरि}}) मोन राखू',
'tog-watchcreations'          => 'हमर बनाओल पृष्ठ हमर साकांक्ष सूचीमे राखू',
'tog-watchdefault'            => 'हमर संपादित पृष्ठ हमर साकांक्ष सूचीमे देखाऊ',
'tog-watchmoves'              => 'हमरा द्वारा हटाओल पृष्ठ हमर साकांक्ष सूचीमे राखू',
'tog-watchdeletion'           => 'हमरा द्वारा हटाओल पृष्ठ हमर साकांक्ष सूचीमे राखू',
'tog-minordefault'            => 'हमर सभ सम्पादन पूर्वन्यस्त रूपेँ मामूली कहू',
'tog-previewontop'            => 'संपादन पेटीक ऊपर दृश्य देखाऊ',
'tog-previewonfirst'          => 'पहिल सम्पादनक बाद पूर्वावलोकन देखाउ',
'tog-nocache'                 => 'गवेषक पृष्ठ उपस्मृति अशक्त करू',
'tog-enotifwatchlistpages'    => 'जौं हमर ध्यानसूचीक कोनो पन्नामे परिवर्तन हुअए तँ हमरा ई-पत्र पठाउ',
'tog-enotifusertalkpages'     => 'हमर सदस्य वार्ता पृष्ठ पर भेल परिवर्त्तनक हेतु हमरा ई-मेल करथि',
'tog-enotifminoredits'        => 'छोट परिवर्त्तनक हेतु सेहो हमरा ई-मेल पठाऊ',
'tog-enotifrevealaddr'        => 'हमर ई-पत्र संकेत सूचना ई-पत्रमे देखाउ',
'tog-shownumberswatching'     => 'ध्यान राखैबला प्रयोक्ताक संख्या',
'tog-oldsig'                  => 'अखुनका दस्खतक प्रारूप',
'tog-fancysig'                => 'हस्ताक्षरकें विकिटेक्सटक रूपमे देखू (स्वचालित श्रृंखला हीन)',
'tog-externaleditor'          => "↓पूर्वनिर्धारित रूपेँ बाह्य सम्पादक क' उपयोग करू (केवल विशेषज्ञसभक लेल, एकरा लेल संगणक पर विशेष सेटिंग चाही। [http://www.mediawiki.org/wiki/Manual:External_editors आओर जानकारी।])",
'tog-externaldiff'            => 'पुरान संस्करणमे अंतर देखेबाक हेतु पूर्वनिविष्ट रूपमे बाहरक परिवर्तनक प्रयोग करू',
'tog-showjumplinks'           => 'करू "तड़पान" भेटैबला लिंक सभ',
'tog-uselivepreview'          => 'करू चल पूर्वावलोकन (जावास्क्रिप्ट चाही) (प्रायोगिक)',
'tog-forceeditsummary'        => 'हमरा सचेत करू जखन हम खाली सम्पादम सारांशमे जाइ',
'tog-watchlisthideown'        => 'हमर साकांक्ष सूचीसँ हमर सम्पादन नुकाउ',
'tog-watchlisthidebots'       => 'हमर साकांक्ष सूचीसँ स्वचालित सम्पादन हटाउ',
'tog-watchlisthideminor'      => 'हमर साकांक्ष सूचीसँ मामूली सम्पादन नुकाउ',
'tog-watchlisthideliu'        => 'साकांक्षसूचीसँ सम्प्रवेशित प्रयोक्ताक सम्पादन हटाउ',
'tog-watchlisthideanons'      => 'साकांक्षसूचीसँ अनाम प्रयोक्ताक सम्पादन हटाउ',
'tog-watchlisthidepatrolled'  => 'साकांक्ष सूचीसँ संचालित सम्पादन नुकाउ',
'tog-ccmeonemails'            => 'हमरा द्वारा दोसर प्रयोक्ताकेँ पठाओल ई-पत्रक कॉपी पठाउ',
'tog-diffonly'                => 'फाइल-अन्तर प्रणालीक नीचाँ पन्नाक सामिग्री नै देखाउ',
'tog-showhiddencats'          => 'नुकाएल संवर्ग देखाउ',
'tog-norollbackdiff'          => 'प्रत्यावर्तनक बाद फाइल-अन्तर प्रणालीकेँ बिसरू',

'underline-always'  => 'सदिखन',
'underline-never'   => 'कखनो नै',
'underline-default' => 'पूर्वन्यस्त गवेषक',

# Font style option in Special:Preferences
'editfont-style'     => 'सम्पादन क्षेत्र वर्णमुख प्रकार',
'editfont-default'   => 'पूर्वन्यस्त गवेषक',
'editfont-monospace' => 'समेटल वर्णमुख',
'editfont-sansserif' => 'शीर्षक वर्णमुख',
'editfont-serif'     => 'पाठ्य वर्णमुख',

# Dates
'sunday'        => 'रवि',
'monday'        => 'सोम',
'tuesday'       => 'मंगल',
'wednesday'     => 'बुध',
'thursday'      => 'बृहस्पति',
'friday'        => 'शुक्र',
'saturday'      => 'शनि',
'sun'           => 'रवि',
'mon'           => 'सोम',
'tue'           => 'मंगल',
'wed'           => 'बुध',
'thu'           => 'बृह.',
'fri'           => 'शुक्र',
'sat'           => 'शनि',
'january'       => 'जनवरी',
'february'      => 'फरबरी',
'march'         => 'मार्च',
'april'         => 'अप्रैल',
'may_long'      => 'मई',
'june'          => 'जून',
'july'          => 'जुलाइ',
'august'        => 'अगस्त',
'september'     => 'सितम्बर',
'october'       => 'अक्टूबर',
'november'      => 'नवंबर',
'december'      => 'दिसंबर',
'january-gen'   => 'जनवरी',
'february-gen'  => 'फरबरी',
'march-gen'     => 'मार्च',
'april-gen'     => 'अप्रैल',
'may-gen'       => 'मई',
'june-gen'      => 'जून',
'july-gen'      => 'जुलाई',
'august-gen'    => 'अगस्त',
'september-gen' => 'सितंबर',
'october-gen'   => 'अकटूबर',
'november-gen'  => 'नवंबर',
'december-gen'  => 'दिसंबर',
'jan'           => 'जन.',
'feb'           => 'फर.',
'mar'           => 'मा.',
'apr'           => 'अप.',
'may'           => 'मई',
'jun'           => 'जू.',
'jul'           => 'जु.',
'aug'           => 'अग.',
'sep'           => 'सित.',
'oct'           => 'अक्टू.',
'nov'           => 'नव.',
'dec'           => 'दिस.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|खाढी|कएटा खाढी}}',
'category_header'                => 'संवर्ग "$1" मे पन्ना सभ',
'subcategories'                  => 'उपसंवर्ग',
'category-media-header'          => 'संवर्ग "$1" मे मीडिया',
'category-empty'                 => "''ऐ संवर्गमे अखन कोनो पन्ना वा मीडिया नै अछि।''",
'hidden-categories'              => '{{PLURAL:$1|नुकाएल वर्ग|नुकाएल वर्ग }}',
'hidden-category-category'       => 'नुकाएल संवर्ग सभ',
'category-subcat-count'          => '{{PLURAL:$2| ऐ संवर्गक खाली ई सभ उप संवर्ग अछिइ।.|ऐ संवर्गमे ई सभ {{PLURAL:$1| उपसंवर्ग|$1 उपसंवर्ग सभ}}, ऐमे सँ $2 सभटा।}}',
'category-subcat-count-limited'  => 'ऐ संवर्गमे अछि {{PLURAL:$1|उपसंवर्ग|$1उपसंवर्ग सभ}}',
'category-article-count'         => '{{PLURAL:$2|ऐ संवर्गमे खाली ई पन्ना अछि।| ई {{PLURAL:$1|पन्ना अछि|$1 पन्ना सभ अछि}} ऐ संवर्गमे, जाइमे सँ $2 सभ।}}',
'category-article-count-limited' => 'ई {{PLURAL:$1|पन्ना अछि|$1 पन्ना सभ अछि}}',
'category-file-count'            => '{{PLURAL:$2| ऐ संवर्गमे मातर ई फाइल अछि।| ई {{PLURAL:$1|फाइल अछि|$1 फाइल सभ अछि}} ऐ संवर्गमे, कुल $2 सँ।}}',
'category-file-count-limited'    => 'ई {{PLURAL:$1|पन्ना अछि|$1 पन्ना सभ अछि}} ऐ संवर्गमे।',
'listingcontinuesabbrev'         => 'शेष आगाँ।',
'index-category'                 => 'क्रम कएल पन्ना सभ',
'noindex-category'               => 'क्रम नै कएल पन्ना सभ',
'broken-file-category'           => 'पन्ना सभ जाइमे फाइल लिंक सभ टूटल हुअए',

'mainpagetext' => "'''मीडियाविकी नीक जकाँ प्रस्थापित भेल।'''",

'about'         => 'विषयमे',
'article'       => 'विषय सूची पन्ना',
'newwindow'     => '(नव खिड़कीसँ खुजैछ)',
'cancel'        => 'समाप्त',
'moredotdotdot' => 'आर...',
'mypage'        => 'हमर पन्ना',
'mytalk'        => 'हमर वार्त्ता',
'anontalk'      => 'ऐ अनिकेत पता लेल विमर्श',
'navigation'    => 'संचार',
'and'           => '&#32;आर',

# Cologne Blue skin
'qbfind'         => 'ताकू',
'qbbrowse'       => 'गवेषण करू',
'qbedit'         => 'सम्पादन करू',
'qbpageoptions'  => 'ई पन्ना',
'qbpageinfo'     => 'विषय',
'qbmyoptions'    => 'हमर पन्ना सभ',
'qbspecialpages' => 'विशेष पन्ना सभ',
'faq'            => 'त्वरित प्रश्नोत्तरी',
'faqpage'        => 'Project: त्वरित प्रश्नोत्तरी',

# Vector skin
'vector-action-addsection' => 'विचार-बिन्दु जोड़ू',
'vector-action-delete'     => 'मेटाउ',
'vector-action-move'       => 'घसकाउ',
'vector-action-protect'    => 'रक्षण करू',
'vector-action-undelete'   => 'आपस लाउ',
'vector-action-unprotect'  => 'अरक्षित',
'vector-view-create'       => 'बनाउ',
'vector-view-edit'         => 'सम्पादन करू',
'vector-view-history'      => 'इतिहास देखू',
'vector-view-view'         => 'पढ़ू',
'vector-view-viewsource'   => 'जड़ि देखू',
'actions'                  => 'क्रिया सभ',
'namespaces'               => 'चेन्हासी समूह सभ',
'variants'                 => 'प्रकार सभ',

'errorpagetitle'    => 'गलती',
'returnto'          => '$1 पर घुरु।',
'tagline'           => 'कतयसँ {{SITENAME}}',
'help'              => 'मदति',
'search'            => 'ताकू',
'searchbutton'      => 'ताकू',
'go'                => 'जाउ',
'searcharticle'     => 'जाऊ',
'history'           => 'पन्नाक इतिहास',
'history_short'     => 'इतिहास',
'updatedmarker'     => 'हमर अन्तिम आगमनसँ पहिने अद्यतन कएल',
'info_short'        => 'सूचना',
'printableversion'  => 'प्रिंट करबा योग्य',
'permalink'         => 'स्थायी लिंक',
'print'             => 'छापू',
'view'              => 'देखू',
'edit'              => 'संपादन',
'create'            => 'बनाउ',
'editthispage'      => 'एहि पृष्ठक संपादन',
'create-this-page'  => 'ई पन्ना बनाउ',
'delete'            => 'मेटाउ',
'deletethispage'    => 'ई पन्ना मेटाउ',
'undelete_short'    => 'आपस आनू  {{PLURAL:$1|एक सम्पादनt|$1 सम्पादन सभ}}',
'viewdeleted_short' => 'देखू {{PLURAL:$1|एकटा मेटाएल सम्पादन|$1 मेटाएल सम्पादन सभ}}',
'protect'           => 'बचाउ',
'protect_change'    => 'बदलू',
'protectthispage'   => 'ाइ पन्नाक रक्षा करू',
'unprotect'         => 'रक्षा कवच हटाउ',
'unprotectthispage' => 'ऐ पन्नासँ रक्षा कवच हटाउ',
'newpage'           => 'नवका पन्ना',
'talkpage'          => 'एहि पृष्ठ पर वार्त्तालाप',
'talkpagelinktext'  => 'कहू',
'specialpage'       => 'विशेष पन्ना',
'personaltools'     => 'व्यक्तिगत उपकरण',
'postcomment'       => 'नव खण्ड',
'articlepage'       => 'विषय-सूची पन्ना देखू',
'talk'              => 'वार्तालाप',
'views'             => 'दृष्टि',
'toolbox'           => 'उपकरण-बक्सा',
'userpage'          => 'प्रयोक्ता पन्ना देखू',
'projectpage'       => 'परियोजना पन्ना देखू',
'imagepage'         => 'पन्नाक पृष्ठ देखू',
'mediawikipage'     => 'सन्देश पन्ना देखू',
'viewhelppage'      => 'सहायता पन्ना देखू',
'categorypage'      => 'संवर्ग पन्ना देखू',
'otherlanguages'    => 'दोसर भाषामे',
'redirectedfrom'    => '(एतयसँ बहटारल $1)',
'redirectpagesub'   => 'पन्नाकेँ पठाउ',
'lastmodifiedat'    => 'ई पन्ना अंतिम बेर संवर्धित भेल $1, केँ  $2 बजे।',
'protectedpage'     => 'संरक्षित पन्ना',
'jumpto'            => 'जाऊ:',
'jumptonavigation'  => 'हेलू',
'jumptosearch'      => 'ताकू',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'विषयमे {{SITENAME}}',
'aboutpage'            => 'Project:विवरण',
'copyright'            => '$1क अंतर्गत विषय सूची उपलब्ध अछि',
'copyrightpage'        => '{{ns:project}}:सर्वाधिकार',
'disclaimers'          => 'अनाधिकार घोषणा',
'disclaimerpage'       => 'Project:अनाधिकार घोषणा',
'edithelp'             => 'संपादन सहयोग',
'edithelppage'         => 'Help:संपादन',
'helppage'             => 'Help: विषय सूची',
'mainpage'             => 'सम्मुख पन्ना',
'mainpage-description' => 'सम्मुख पृष्ठ',
'policy-url'           => 'Project:नीति',
'portal'               => 'सामाजिक कोण',
'portal-url'           => 'Project:समूह कोण',
'privacy'              => 'गोपनीयताक नियम',
'privacypage'          => 'Project:गोपनीयता नियम',

'badaccess' => 'आज्ञा गल्ती',

'ok'                      => 'ठीक अछि',
'retrievedfrom'           => 'प्राप्ति स्थल "$1"',
'youhavenewmessages'      => 'अहाँ लग अछि $1 ($2).',
'newmessageslink'         => 'नव संदेश सभ',
'newmessagesdifflink'     => 'अन्तिम परिवर्तन',
'youhavenewmessagesmulti' => '$1 पर अहाँ लेल नव सन्देश अछि',
'editsection'             => 'संपादन करू',
'editold'                 => 'सम्पादित करू',
'viewsourceold'           => 'जड़ि देखू',
'editlink'                => 'सम्पादन करू',
'viewsourcelink'          => 'जड़ि देखू',
'editsectionhint'         => 'संपादन शाखा: $1',
'toc'                     => 'विषय-सूची',
'showtoc'                 => 'देखाऊ',
'hidetoc'                 => 'नुकाऊ',
'collapsible-collapse'    => 'भखड़ाउ',
'collapsible-expand'      => 'बढ़ाउ',
'thisisdeleted'           => 'देखू वा जाउ $1?',
'viewdeleted'             => 'देखू $1?',
'restorelink'             => '{{PLURAL:$1|एकटा मेटाएल सम्पादन|$1 मेटाएल सम्पादन सभ}}',
'feedlinks'               => 'सूचक:',
'feed-invalid'            => 'अमान्य सूचक प्रकार मासुल',
'feed-unavailable'        => 'ाधिकृत सूचक उपलब्ध नै अछि',
'site-rss-feed'           => '$1 आरएसएस फीड',
'site-atom-feed'          => '$1 अणु फीड',
'page-rss-feed'           => '"$1" आर.एस.एस. सूचना',
'page-atom-feed'          => '"$1" अणु सू़चना',
'red-link-title'          => '$1 (पृष्ठ उपलब्ध नै  अछि)',
'sort-descending'         => 'घटैत क्रममे छाँटू',
'sort-ascending'          => 'बढ़ैत क्रममे छाँटू',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'पृष्ठ',
'nstab-user'      => 'उपयोगकर्ताक पृष्ठ',
'nstab-media'     => 'मीडिया पन्ना',
'nstab-special'   => 'विशिष्ट पन्ना',
'nstab-project'   => 'परियोजना पन्ना',
'nstab-image'     => 'फाइल',
'nstab-mediawiki' => 'संदेश',
'nstab-template'  => 'नमूना',
'nstab-help'      => 'सहायता पन्ना',
'nstab-category'  => 'संवर्ग',

# Main script and global functions
'nosuchaction'      => 'एहेन कोनो क्रिया नै',
'nosuchspecialpage' => 'एहेन कोनो विशेष पन्ना नै',

# General errors
'error'                => 'भ्रम',
'databaseerror'        => 'दत्तनिधि भ्रम',
'laggedslavemode'      => "'''चेतौनी:''' पन्नापर सम्भव जे अद्यतन परिवर्तन नै हुअए।",
'readonly'             => 'दत्तनिधि प्रतिबन्धित',
'missing-article'      => 'दत्तनिधि पृष्ठक वांछित पाठ्य नै ताकि सकल, माने "$1" $2
एकर कारण कोनो पुरान फाइल चेन्हासी वा ऐतिहासिक लिंकक पाछाँ जाएब अछि, जे मेटा देल गेल छै।
जौं ई तकर कारण नै अछि,  तखन अहाँकेँ तंत्रांशमे कोनो दोष भेटल अछि।
एकर खबरि पहुँचाउ [[Special:ListUsers/sysop|administrator]], केँ, अपन सार्वत्रिक विभव संकेत सूचित करैत।',
'missingarticle-rev'   => '(संशोधन#: $1)',
'missingarticle-diff'  => '(फाइल-अन्तर प्रणाली: $1, $2)',
'internalerror'        => 'आन्तरिक भ्रम',
'internalerror_info'   => 'आन्तरिक भ्रम: $1',
'fileappenderrorread'  => '"$1"  केँ जोड़ै कालमे नै पढ़ि सकल',
'fileappenderror'      => '"$1" सँ "$2" केँ नै जोड़ि सकल।',
'filecopyerror'        => '"$1" सँ "$2" केँ नै अनुकृति कऽ सकल।',
'filerenameerror'      => '"$1" सँ "$2" केँ नै नाम बदलि सकल।',
'filedeleteerror'      => '"$1" केँ नै मेटा सकल।',
'directorycreateerror' => 'विभाग "$1" नै बना सकल।',
'filenotfound'         => 'फाइल "$1" नै ताकि सकल।',
'fileexistserror'      => 'फाइल "$1" पर लिखबामे अक्षम: फाइल अछि',
'unexpected'           => 'आसक विपरीत परिणाम: "$1"="$2"',
'formerror'            => 'फॉर्म नै पठा सकल',
'badarticleerror'      => 'ई क्रिया ऐ पन्नापर नै कएल जा सकैए।',
'badtitle'             => 'खराप शीर्षक',
'badtitletext'         => 'आग्रह कएल पन्नाक शीर्षक गलत, खाली, वा गलत सम्बन्धित अन्तर-न्हाषा अन्तर विकी शीर्षक छी। ई एक वा बेशी कलाकार युक्त भऽ सकैए जे शीर्षकमे प्रयुक्त नै कएल जा सकैए।',
'perfcached'           => 'ई दत्तांश उपस्मृतिक आधारपर अछि आ भऽ सकैए जे अद्यतन नै हुअए।',
'perfcachedts'         => 'ई दत्तांश उपस्मृतिमे अछि, आ एकर अन्तिम परिवर्धन भेल अछि $1 केँ।',
'viewsource'           => 'जड़ि देखू',
'viewsourcefor'        => '$1 लेल।',
'actionthrottled'      => 'क्रियाकेँ मोकल गेल',
'protectedpagetext'    => 'ई पन्ना सम्पादन रोकबा लेल संरक्षित अछि।',
'viewsourcetext'       => 'अहाँ ऐ पन्नाक जड़िकेँ देख आ अनुकृत कऽ सकै छी:',
'sqlhidden'            => '(नुकाएल एस.क्यू.एल. अभ्यर्थना)',
'namespaceprotected'   => "अहाँकेँ '''$1''' नाम-पेटारमे सम्पादनक अनुमति नै अछि।",
'customcssjsprotected' => 'अहाँकेँ ऐ पन्नाक सम्पादनक अधिकार नै अछि, कारण ई दोसर प्रयोक्ताक व्यक्तिगत प्रतीक छी।',
'ns-specialprotected'  => 'विशेष पन्ना सभकेँ सम्पादित नै कएल जा सकैए।',
'titleprotected'       => 'ऐ शीर्षकक निर्माण प्रतिबन्धित अछि [[User:$1|$1]] द्वारा।
कारण एतऽ देल अछि "\'\'$2\'\'"।',

# Virus scanner
'virus-badscanner'     => "खराप विन्यास: अज्ञात विषविधि बिम्बक: ''$1''",
'virus-scanfailed'     => 'बिम्ब विफल (विध्यादेश $1)',
'virus-unknownscanner' => 'अज्ञात विषविधि निरोधक',

# Login and logout pages
'yourname'                 => 'प्रयोक्ता:',
'yourpassword'             => 'कूटशब्द:',
'yourpasswordagain'        => 'कूटशब्द फेरसँ टाइप करू:',
'remembermypassword'       => 'हमर सम्प्रवेश ऐ गवेषकपर मोन राखू (बेशीसँ बेशी $1 {{PLURAL:$1|दिन|दिन}})',
'securelogin-stick-https'  => 'सम्प्रवेशक बाद एच.टी.टी.पी.एस.क लागिमे रहू',
'yourdomainname'           => 'अहाँक प्रभावक्षेत्र:',
'externaldberror'          => 'खाहे सत्यापन दतांश भ्रम छल वा अहाँ अपन बाह्य खाताकेँ अद्यतन करबामे असमर्थ छी।',
'login'                    => 'सम्प्रवेश',
'nav-login-createaccount'  => 'सदस्य लॉग इन',
'loginprompt'              => '{{अन्तर्जाल}} सम्प्रवेश लेल अहाँकेँ आवश्यक रूपेँ ज्ञापक सक्रिय करबाक चाही।',
'userlogin'                => 'लॉग इन / खेसरा बनाऊ',
'userloginnocreate'        => 'सम्प्रवेश',
'logout'                   => 'निष्क्रमण',
'userlogout'               => 'फेर आयब',
'notloggedin'              => 'सम्प्रवेशित नै छी',
'nologin'                  => 'खाता नै अछि? $1।',
'nologinlink'              => 'नव खाता खोलू',
'createaccount'            => 'खाता खोली',
'gotaccount'               => 'पहिनहियेसँ खाता अछि? $1',
'gotaccountlink'           => 'सम्प्रवेश',
'userlogin-resetlink'      => 'अपन सम्प्रवेश विवरण बिसरि गेलहुँ?',
'createaccountmail'        => 'ई-पत्र द्वारा',
'createaccountreason'      => 'कारण:',
'badretype'                => 'कूटशब्द जे अहाँ भरलहुँ से मेल नै खाइए।',
'userexists'               => 'जे प्रयोक्तानाम अहाँ भरलहुँ से पहिनहियेसँ प्रयोगमे अछि।
कृपा कऽ दोसर नामक चयन करू।',
'loginerror'               => 'सम्प्रवेश भ्रम',
'createaccounterror'       => 'खाता नै बना सकल: $1',
'noname'                   => 'अहाँ वैध प्रयोक्तानाम नै देने छी।',
'loginsuccesstitle'        => 'सम्प्रवेश सफल',
'loginsuccess'             => "'''अहाँ सम्प्रवेश केलहुँ {{अन्तर्जाल-पता}} \"\$1\".'''क रूपमे।",
'nosuchusershort'          => '"<nowiki>$1</nowiki>" नाम्ना कोनो प्रयोक्ता नै अछि।
अपन ह्रिजए सुधारू।',
'nouserspecified'          => 'अहाँकेँ एकटा प्रयोक्तानाम देबऽ पड़त।',
'login-userblocked'        => 'ई प्रयोक्ता प्रतिबन्धित अछि। सम्प्रवेशक अधिकार नै अछि।',
'wrongpassword'            => 'गलत कूटशब्द देल गेल।
फेरसँ प्रयास करू।',
'wrongpasswordempty'       => 'रिक्त कूटशब्द देल गेल।
फेरसँ प्रयास करू।',
'passwordtooshort'         => 'कूटशब्द कमसँ कम {{PLURAL:$1|1 वर्ण|$1 वर्णक}} हुअए।',
'password-name-match'      => 'अहाँक कूटशब्द अहाँक प्रयोक्तानामसँ भिन्न हेबाक चाही।',
'password-login-forbidden' => 'ऐ प्रयोक्तानाम आ कूटशब्दक प्रयोग प्रतिबन्धित अछि।',
'mailmypassword'           => 'नूतन कूटशब्द ई-पत्रसँ पठाउ',
'passwordremindertitle'    => 'नव अस्थायी कूटशब्द {{अन्तर्जाल-पता}} लेल।',

# Special:PasswordReset
'passwordreset-username'     => 'प्रयोक्तानाम',
'passwordreset-email'        => 'ई-पत्र संकेत',
'passwordreset-emailtitle'   => 'लेखा विवरण {{अन्तर्जालक नाम}}',
'passwordreset-emailtext-ip' => 'कियो (सम्भवतः अहाँ, अन्तर्जाल सेवा कल्पक $1 सँ) अपन लेखा विवरणक पुनःस्मरणक लेल अनुरोध केलहुँ ऐ लेल {{ अन्तर्जालक नाम}} ($4). ई प्रयोक्ता {{PLURAL:$3|लेखा अछि| लेखा सभ अछि}}
ऐ ई-पत्र संकेतसँ सम्बन्धित:

$2

{{PLURAL:$3|ई अल्पकालक कूटशब्द| ई सभ अल्पकालक कूटशब्द}} खतम भऽ जाएत {{PLURAL:$5|एक दिन|$ पाँच दिन}}.
अहाँ सम्प्रवेश करू आ एकटा नव कूटशब्द चुनू।. जौं कियो आन ई आग्रह केने अछि, वा अहाँकेँ अपन पुरान कूटशब्द मोन पड़ि गेल अछि , आ आब एकरा बदलबाक इच्छा नै राखै छी तँ अहाँ ऐ संदेशकेँ बिसरि जाउ आ अपन पुरान कूटशब्दक प्रयोग करैत रहू।',

# Edit page toolbar
'bold_sample'     => 'गँहीर लेखन',
'bold_tip'        => 'गँहीर लेखन',
'italic_sample'   => 'कटि लेखन',
'italic_tip'      => 'क़टि लेखन',
'link_sample'     => 'लिंक उपाधि',
'link_tip'        => 'अंतरंग इशारा',
'extlink_sample'  => 'http://www.example.com लिंक उपाधि',
'extlink_tip'     => 'बहरी लिंक (यादि राखू http:// उपसर्ग)',
'headline_sample' => 'मुख्यपंक्ति लेखन',
'headline_tip'    => 'स्तर 2 मुख्यपंक्ति',
'nowiki_sample'   => 'फॉर्मेट विहीन लेख एतय',
'nowiki_tip'      => 'विकी फॉरमेटिंगकेँ छोड़ू',
'image_tip'       => 'समाहित चित्र',
'media_tip'       => 'मीडिया फाइल लिंक',
'sig_tip'         => 'अहाँक हस्ताक्षर समयक मोहरक संग',
'hr_tip'          => 'अक्षांशीय पंक्ति (अल्प उपयोग)',

# Edit pages
'summary'                          => 'सारांश:',
'subject'                          => 'विषय/मुख्यपंक्ति:',
'minoredit'                        => 'अल्प संपादन',
'watchthis'                        => 'एहि पृष्ठकेँ देखू',
'savearticle'                      => 'पन्नाक रक्षण करू',
'preview'                          => 'पूर्वावलोकन',
'showpreview'                      => 'पूर्वप्रदर्शन',
'showdiff'                         => 'परिवर्त्तन देखाऊ',
'anoneditwarning'                  => "'''चेतौनी:''' अहाँ सम्प्रवेशित नै छी।
अहाँक अनिकेत ऐ पन्नाक सम्पादन इतिहासमे दर्ज कएल जाएत।",
'summary-preview'                  => 'सारांश पूर्वावलोकन',
'newarticle'                       => '(नव)',
'newarticletext'                   => 'अहाँ एहेन पन्नाक लिंकक अनुसरण कऽ आएल छी जे पन्ना अखन बनले नै अछि।
पन्ना बनेबाक लेल नीचाँक बक्शामे टाइप केनाइ शुरू करू (देखू [[{{MediaWiki:Helppage}}| सहायता पन्ना]] विषेष जानकारी लेल)।',
'noarticletext'                    => 'अखन ऐ पन्नापर कोनो पाठ नै अछि।
अहाँ [[Special:Search/{{PAGENAME}}|ऐ पन्नाक शीर्षकेँ ताकू]] आन पन्नापर,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} सम्बन्धी वृत्तलेख ताकू],
आकि [{{fullurl:{{FULLPAGENAME}}|action=edit}} ऐ पन्नाकेँ सम्पादित करू]</span>.',
'previewnote'                      => "'''मोन राखू ई मातर पूर्वावलोकन छी।'''
अहाँक परिवर्तन अखन धरि सँचिआएल नै गेल अछि!",
'editing'                          => 'सम्पादन होइए $1',
'editingsection'                   => 'सम्पादन कऽ रहल छी $1 (खण्ड)',
'copyrightwarning'                 => 'कृपा कय बुझू जे सभटा योगदान {{SITENAME}} ई बुझि कय देल जा रहल अछि जे ई निम्नांकितक अंतर्गत अछि $2 (देखू $1 जनकारीक हेतु). जौँ अहाँ चाहैत छी जी अहाँक रचना बिना रोकटोकक संपादित नहि हो किंवा बाँटल नहि जाय, तँ एकर योगदान एतय नहि करू। <br />
एतय अहाँ ईहो सप्पत खाइत छी जी ई अहाँक अपन रचना छी आकि अहाँ एकरा कोनो सार्वजनिक डोमेन किंवा ओह्ने कोनो मँगनीक संदर्भ-स्थलसँ कॉपी कएने छी।
< दृढ़> सर्वाधिकार सुरक्षित कार्य एतय नहि दी।!</दृढ़>',
'templatesused'                    => '{{PLURAL:$1|नमूना|नमूना सभ}} ऐ पन्नापर प्रयुक्त:',
'templatesusedpreview'             => '{{PLURAL:$1|मास्टरफाइल|सभटा मास्टरफाइल}} used in this preview:ऐ पूर्वप्रदर्शनमे प्रयुक्त :',
'template-protected'               => '(सुरक्षित)',
'template-semiprotected'           => '(अर्ध-रक्षित)',
'hiddencategories'                 => 'ई पन्ना सदस्य अछि {{PLURAL:$1|1 नुकाएल संवर्ग|$1 नुकाएल संवर्ग सभ}}:',
'permissionserrorstext-withaction' => 'अहाँके अनुमति नै अछि $2 लेल, ऐ लेल {{PLURAL:$1|कारण|कारण सभ}}:',

# History pages
'viewpagelogs'           => 'ऐ पन्नाक वृत्तलेख सभ देखू',
'currentrev-asof'        => '$1 क समकालिक तखुनका संशोधन',
'revisionasof'           => 'अंतिम परिवर्त्तन  $1',
'previousrevision'       => '←पुरान परिवर्त्तन',
'nextrevision'           => 'नूतन संशोधन →',
'currentrevisionlink'    => 'नूतन संशोधन',
'cur'                    => 'हीन',
'last'                   => 'अंतिम',
'histlegend'             => "फाइल तुलना तंत्रांशक चयन: संशोधन तुलनाक रेडियो बक्शाकेँ चिन्हित करू आ एन्टर बटन क्लिक करू वा सभसँ नीचाँक बटन क्लिक करू। <br />
कहबी: '''({{int:cur}})''' = अद्यतन संशोधनसँ अन्तर, '''({{int:last}})''' = अद्यतनसँ पहिलुका संशोधनसँ अन्तर, '''{{int:minoreditletter}}''' = मामूली सम्पादन।",
'history-fieldset-title' => 'इतिहास खंघारू',
'histfirst'              => 'सभसँ पुरान',
'histlast'               => 'आइ-काल्हिक',

# Revision deletion
'rev-delundel'   => 'देखाउ/ नुकाउ',
'revdel-restore' => 'दृष्टिकुशलता बदलू',

# Merge log
'revertmerge' => 'नै मिज्झर',

# Diffs
'history-title'           => '"$1" क संशोधन इतिहास',
'difference'              => '(नव संशोधन सभक बीच अन्तर)',
'lineno'                  => 'पंक्त्ति $1:',
'compareselectedversions' => 'चयन कएल संशोधन सभक तुलना करू',
'editundo'                => 'असंपादन',

# Search results
'searchresults'             => 'तकबाक फलाफल',
'searchresults-title'       => 'तकबाक फलाफल "$1" लेल',
'searchresulttext'          => 'तकबा लेल विशेष सूचना {{अन्तर्जालक नाम}}, देखू [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => 'अहाँ तकलौं ऐ लेल \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|"$1" सँ शुरू होइबला सभा पृष्ठ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|सभ लिंक जकर लागि अछि "$1" सँ ]])',
'searchsubtitleinvalid'     => "अहाँ तकलहुँ '''$1''' लेल",
'notitlematches'            => 'कोनो पन्नाक शीर्ष मेल नै खाइए',
'notextmatches'             => 'पन्नाक पाठक किछु मेल नै खाइए',
'prevn'                     => 'पछिला {{PLURAL:$1|$1}}',
'nextn'                     => 'आगाँ {{PLURAL:$1|$1}}',
'viewprevnext'              => 'देखू  ($1 {{int:pipe-separator}} $2) ($3)',
'search-result-size'        => '$1 ({{PLURAL:$2|1 शब्द|$2 शब्द सभ}})',
'search-redirect'           => '(रस्ता बदलेन $1)',
'search-section'            => '(शाखा $1)',
'search-suggest'            => 'अहाँ मोने अछि जे:$1',
'search-interwiki-caption'  => 'सम्बन्धित परियोजना सभ',
'search-interwiki-default'  => '$1 सभटा परिणाम:',
'search-interwiki-more'     => '(आर)',
'search-mwsuggest-enabled'  => 'सलाहक संग',
'search-mwsuggest-disabled' => 'कोनो सलाह नै',
'nonefound'                 => "'''टिप्पणी''': मातर किछुए निर्धारक मूलभूत रूपेँ ताकल जाइए।
सभ सामिग्रीमे (माने मंतव्य पन्ना, नमूना, इत्यादि) तकबाले अपन उत्कंठामे उपसर्ग ''all:'' लगाउ , नै तँ इच्छित निर्धारककेँ उपसर्ग सन प्रयुक्त करू।",
'powersearch'               => 'त्वरित खोज',
'powersearch-legend'        => 'विशेष खोज',
'powersearch-ns'            => 'निर्धारकमे खोज',
'powersearch-redir'         => 'रस्ता बदलेनक सूची',
'powersearch-field'         => 'ऐ लेल ताकू',

# Preferences page
'preferences'    => 'विकल्प',
'mypreferences'  => 'हमर खासमखास',
'yourgender'     => 'पुरुख आकि स्त्री',
'gender-unknown' => 'अज्ञात',

# Groups
'group-sysop' => 'माइनजन',

'grouppage-sysop' => '{{ns:project}}:माइनजन सभ',

# User rights log
'rightslog' => 'प्रयोक्ता अधिकार वृत्तलेख',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'ऐ पन्नाकेँ सम्पादित करू',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|परिवर्त्तन|परिवर्त्तन}}',
'recentchanges'                  => 'लगक परिवर्तन सभ',
'recentchanges-legend'           => 'नव परिवर्तन सभक विकल्प सभ',
'recentchanges-feed-description' => 'ऐ सूचना-तंत्रांशमे विकीमे भेल सभसँ लगक परिवर्तन ताकू',
'rcnote'                         => "$5, $4 तक पहिलुका {{PLURAL:$2|'''१''' दिन|'''$2''' दिन}}मे  {{PLURAL:$1|भेल '''१''' अंतिम बदलाव इस प्रकार है| हुए '''$1''' बदलाव एना अछि}}।",
'rclistfrom'                     => '$1 सँ शुरू भेल नव परिवर्तन देखू',
'rcshowhideminor'                => '$1 अल्प संपादन',
'rcshowhidebots'                 => '$1 स्वचालक सभ',
'rcshowhideliu'                  => '$1 सम्प्रवेशित प्रयोक्ता सभ',
'rcshowhideanons'                => '$1 अज्ञात प्रयोक्ता सभ',
'rcshowhidemine'                 => '$1 हमर सम्पादन सभ',
'rclinks'                        => 'देखाऊ अंतिम $1 परिवर्त्तन अंतिम $2 दिनमे<br />$3',
'diff'                           => 'अंतर',
'hist'                           => 'इति.',
'hide'                           => 'नुकाऊ',
'show'                           => 'देखाउ',
'minoreditletter'                => 'अ',
'newpageletter'                  => 'न',
'boteditletter'                  => 'ब',
'rc-enhanced-expand'             => 'वर्णन देखाउ (जावास्क्रिप्ट चाही)',
'rc-enhanced-hide'               => 'वर्णन नुकाउ',

# Recent changes linked
'recentchangeslinked'         => 'संबंधित परिवर्त्तन',
'recentchangeslinked-feed'    => 'संबंधित परिवर्त्तन',
'recentchangeslinked-toolbox' => 'संबंधित परिवर्त्तन',
'recentchangeslinked-title'   => '"$1" मे भेल परिवर्तन',
'recentchangeslinked-summary' => "ई विशेष पन्नासँ सम्बद्ध पन्ना सभमे (आकि कोनो विशेष वर्गक समूहमे) भेल परिवर्तनक सूची छी ।
[[Special:Watchlist|your watchlist]]  पर पन्नासभ '''गाढ़''' अछि।",
'recentchangeslinked-page'    => 'पन्नाक नाम',
'recentchangeslinked-to'      => 'देल पन्नाक सम्बन्धी पन्नामे परिवर्तन देखाउ',

# Upload
'upload'        => 'फाइल अपलोड करू',
'uploadbtn'     => 'फाइल अपलोड',
'uploadlogpage' => 'उपारोपण वृत्तलेख',
'uploadedimage' => 'अपलोड भेल "[[$1]]"',

# File description page
'filehist'                  => 'फाइल इतिहास',
'filehist-help'             => 'तखुनका तिथि/ समए पर क्लिक करू जखुनका फाइल देखबाक अछि',
'filehist-current'          => 'अखुनका',
'filehist-datetime'         => 'तिथि/ समए',
'filehist-thumb'            => 'लघुचित्र',
'filehist-thumbtext'        => 'तखुनका लघुचित्र $1',
'filehist-user'             => 'प्रयोक्ता',
'filehist-dimensions'       => 'बीम',
'filehist-comment'          => 'समीक्षा',
'imagelinks'                => 'फाइलक लिंक',
'linkstoimage'              => 'ऐ {{PLURAL:$1|पन्नाक लागि |$1 पन्नाक लागि}} ऐ फाइलसँ:',
'sharedupload'              => 'ई फाइल $1 सँ अछि आ दोसर प्रकल्प लेल प्रयोग कएल जा सकैए।',
'uploadnewversion-linktext' => 'ऐ फाइलक नव संस्करणक उपारोपण',

# Random page
'randompage' => 'अव्यवस्थित पृष्ठ',

# Statistics
'statistics' => 'सांख्यिकी',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|बाइट|बाइट्स}}',
'nmembers'      => '$1 {{PLURAL:$1|सदस्य|सदस्य सभ}}',
'prefixindex'   => 'उपसर्गक संग सभटा पृष्ठ',
'newpages'      => 'नव पन्ना सभ',
'move'          => 'हटाउ',
'movethispage'  => 'ऐ पृष्ठकेँ घसकाउ',
'pager-newer-n' => '{{PLURAL:$1|नव 1|नव $1}}',
'pager-older-n' => '{{PLURAL:$1|साबिक 1|साबिक $1}}',

# Book sources
'booksources'               => 'किताबक सन्दर्भ सभ',
'booksources-search-legend' => 'किताबक सन्दर्भक लेल ताकू',
'booksources-go'            => 'चलू',

# Special:Log
'log'           => 'वृत्तलेख सभ',
'all-logs-page' => 'सभटा लॉग',

# Special:AllPages
'allpages'       => 'सभ पन्ना',
'alphaindexline' => '$1 to $2',
'prevpage'       => 'पहिलुका पन्ना ($1)',
'allpagesfrom'   => 'पन्ना प्रदर्शन प्रारम्भ भेल:',
'allpagesto'     => 'एतऽ खतम होमएबला पन्नाक प्रदर्शन करू:',
'allarticles'    => 'सभटा पन्ना',
'allpagessubmit' => 'जाउ',

# Special:LinkSearch
'linksearch' => 'बाहरक सम्बन्ध',

# Special:Log/newusers
'newuserlogpage'          => 'प्रयोक्ता रचना वृत्तलेख',
'newuserlog-create-entry' => 'नव प्रयोक्ता खाता',

# Special:ListGroupRights
'listgrouprights-members' => '(सदस्यक सूची)',

# E-mail user
'emailuser' => 'ऐ प्रयोक्ताकेँ ई-पत्र पठाउ',

# Watchlist
'watchlist'         => 'हमर साकांक्षसूची',
'mywatchlist'       => 'हमर साकांक्ष-सूची',
'addedwatch'        => 'साकांक्ष सूचीमे जोड़ू',
'addedwatchtext'    => "पन्ना \"[[:\$1]]\" अहाँक [[Special:Watchlist|साकांक्ष सूची]] मे जोड़ल गेल।
ऐ पन्नामे भविष्यक परिवर्तन आ एकर सम्बन्धित चौबटिया पन्ना एतए सूचीबद्ध रहत, आ पन्ना [[Special:RecentChanges|हालक परिवर्तन]]मे '''गाढ़''' देखाएत , जइसँ आसानीसँ एकरा चिन्हल जा सकत।",
'removedwatch'      => 'साकांक्ष सूचीसँ हटाएल गेल',
'removedwatchtext'  => 'पन्ना "[[:$1]]" हटाएल गेल [[Special:Watchlist|अहाँक साकांक्षसूची]] सँ।',
'watch'             => 'ताकिमे',
'watchthispage'     => 'ऐ पृष्ठपर नजरि राखू',
'unwatch'           => 'छोड़ू',
'watchlist-details' => '{{PLURAL:$1|$1 पन्ना|$1 पन्ना सभ}} अहाँक साकांक्षसूचीमे, चौबटिया पन्ना नै गानल गेल।',
'wlshowlast'        => 'देखाउ अन्तिम $1 घण्टा $2 दिन $3',
'watchlist-options' => 'साकांक्षसूचीक विकल्प सभ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ताकिमे...',
'unwatching' => 'छोड़ल ...',

# Delete
'deletepage'            => 'पन्ना मेटाउ',
'confirmdeletetext'     => 'अहाँ सभटा इतिहासक संग ऐ पन्नाकेँ हटाबऽ जा रहल छी।
अहाँ ई सुनिश्चित करू जे अहाँ ई करऽ चाहै छी, अहाँकेँ एकर परिणामक अवगति अछि आ अहाँ ई ऐ [[{{MediaWiki:Policy-url}}|नीति]] क अनुसार कऽ रहल छी।',
'actioncomplete'        => 'क्रिया पूर्ण',
'deletedtext'           => '"<nowiki>$1</nowiki>" केँ मेटा देल गेल अछि।
देखू $2 हालक मेटाएल सामिग्रीक अभिलेख लेल।',
'deletedarticle'        => 'मेटाएल "[[$1]]"',
'dellogpage'            => 'मेटाएल सामिग्रीक वृत्तलेख',
'deletecomment'         => 'कारण:',
'deleteotherreason'     => 'दोसर/ अतिरिक्त कारण:',
'deletereasonotherlist' => 'दोसर कारण',

# Rollback
'rollbacklink' => 'प्रत्यावर्तन',

# Protect
'protectlogpage'              => 'सुरक्षा लॉग',
'protectedarticle'            => 'रक्षित "[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]" लेल बदलैत रक्षा स्तर',
'prot_1movedto2'              => '[[$1]] गेल एतय [[$2]]',
'protectcomment'              => 'कारण:',
'protectexpiry'               => 'खतम हएत:',
'protect_expiry_invalid'      => 'खतम हेबाक समए सही नै अछि।',
'protect_expiry_old'          => 'खतम हेबाक समए भूतमे अछि।',
'protect-text'                => "अहाँ पन्नाक रक्षा स्तरकेँ एतए देखि आ परिवर्तित कऽ सकै छी '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "अहाँक खाता अहाँकेँ रक्षा स्तरमे परिवर्तनक अधिकार नै दैत अछि।
एतए '''$1'''पन्नाक वर्तमान परिस्थिति देल गेल अछि:",
'protect-cascadeon'           => 'ई पन्ना अखन रक्षित अछि कारण ई ऐ मे सम्मिलित अछि {{PLURAL:$1|पन्ना, जे अछि|पन्ना सभ, जे सभ अछि}} तराउपड़ी रक्षण लागू।
अहाँ ऐ पन्नाक रक्षा स्तरकेँ बदलि सकै छी, मुदा ताइ सँ तराउपड़ी रक्षापर असर नै पड़त।',
'protect-default'             => 'सभ प्रयोक्ताकेँ अधिकार दिअ',
'protect-fallback'            => '"$1" अनुमति चाही',
'protect-level-autoconfirmed' => 'नव आ अपंजीकृत प्रयोक्ताकेँ प्रतिबन्धित करू',
'protect-level-sysop'         => 'माइनजन मात्र',
'protect-summary-cascade'     => 'तराउपड़ी',
'protect-expiring'            => 'खतम हएत $1 (UTC)',
'protect-cascade'             => 'रक्षित पन्ना ऐ पन्नापर संकलित अछि (तराउपड़ी रक्षा)',
'protect-cantedit'            => 'अहाँ ऐ पन्नाक रक्षा स्तरमे परिवर्तन नै कऽ सकै छी, कारण अहाँकेँ एकरा सम्पादित करबाक अनुमति नै अछि।',
'restriction-type'            => 'अनुमति:',
'restriction-level'           => 'अवरोध स्तर:',

# Undelete
'undeletelink'     => 'देखू/ पहिने जकाँ',
'undeletedarticle' => 'फेरसँ ओहिना "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'चेन्हासी समूह',
'invert'         => 'उनटा चयन',
'blanknamespace' => '(मुख्य)',

# Contributions
'contributions'       => 'प्रयोक्ताक योगदान सभ',
'contributions-title' => '$1 लेल प्रयोक्ताक अवदान',
'mycontris'           => 'हमर योगदान',
'contribsub2'         => '$1 ($2) लेल',
'uctop'               => '(शिखर)',
'month'               => 'माससँ (आ पहिने)',
'year'                => 'ऐ साल (आ पहिने)',

'sp-contributions-newbies'  => 'नव खाताक अवदानकेँ देखाउ',
'sp-contributions-blocklog' => 'प्रतिबन्धबला वृत्तलेख',
'sp-contributions-talk'     => 'कहू',
'sp-contributions-search'   => 'अवदानक लेल ताकू',
'sp-contributions-username' => 'अनिकेत संकेत वा प्रयोक्तानाम:',
'sp-contributions-submit'   => 'ताकू',

# What links here
'whatlinkshere'            => 'एतय कोन लिंक अछि',
'whatlinkshere-title'      => '"$1" सँ सम्बन्धित पन्ना सभ',
'whatlinkshere-page'       => 'पन्ना:',
'linkshere'                => "ई सभ पन्ना सम्बन्धित अछि '''[[:$1]]''':",
'isredirect'               => 'पन्नाकेँ घुराउ',
'istemplate'               => 'परागत',
'isimage'                  => 'सम्बन्धित चित्र',
'whatlinkshere-prev'       => '{{PLURAL:$1|पहिलुका|पहिलुका सभ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|अगुलका|अगुलका $1}}',
'whatlinkshere-links'      => '← लिंक',
'whatlinkshere-hideredirs' => '$1 घुरबैए',
'whatlinkshere-hidetrans'  => '$1 परागत',
'whatlinkshere-hidelinks'  => '$1 सम्बन्ध सभ',
'whatlinkshere-filters'    => 'चलनी सभ',

# Block/unblock
'blockip'                  => 'प्रयोक्ताकेँ प्रतिबन्धित करू',
'ipboptions'               => '2 घण्टा:2 hours,1 दिन:1 day,3 दिन:3 days,1 सप्ताह:1 week,2 सप्ताह:2 weeks,1 मास:1 month,3 मास:3 months,6 मास:6 months,1 साल:1 year,अनिश्चित:infinite',
'ipblocklist'              => 'प्रतिबन्धित प्रयोक्ता सभ',
'blocklink'                => 'प्रतिबंधित',
'unblocklink'              => 'नै बारल',
'change-blocklink'         => 'खण्ड बदलू',
'contribslink'             => 'योगदान',
'blocklogpage'             => 'प्रतिबन्धित वृत्तलेख',
'blocklogentry'            => 'प्रतिबन्धित [[$1]] एकर अन्तिम तिथि अछि $2 $3',
'unblocklogentry'          => 'प्रतिबन्ध हटाएल $1',
'block-log-flags-nocreate' => 'लेखा निर्माण अशक्त कएल',

# Move page
'movepagetext'     => "नीचाँक फॉर्मक प्रयोग पन्नाक नाम बदलि देत, एकर सभटा इतिहासकेँ नव नामक अन्तर्गत राखि देत।
पुरान शीर्षक नव पन्ना लेल एकटा घुरबैबला पन्ना बनि जाएत।
अहाँ घुरबैबला पन्नाकेँ अद्यतन कऽ सकै छी जे मूल शीर्षकपर स्वचालित रूपेँ जाइत अछि।
जौं अहाँ ई नै करबाक निर्णय करै छी, निश्चय करू तकबा लेल [[Special:DoubleRedirects|double]] वा
[[Special:BrokenRedirects|broken redirects]]
अहाँ ऐ लेल जिम्मीदार छी जे सम्बन्धित लिंक ओतै जाए जतए ओकरा जेबाक चाही।

मोन राखू कि पन्ना '''नै''' घसकाउ जौं नव शीर्षकपर पहिनहियेसँ पन्ना अछि, आ तखने ई करू जखन ओ खाली हुअए वा ओ एकटा घुमबैबला पन्ना हुअए वा ओइ पन्नाक कोनो भूतकालक सम्पादन इतिहास नै हुअए।
एकर माने भेल जे अहाँ कोनो पन्नाक नाम परिवर्तन कऽ पाछाँ लऽ जा सकै छी जतए एकर नाममे परिवर्तन कएल गेल रहए जौं अहाँसँ गलती भेल अछि, आ अहाँ ओइ पन्नाकेँ फेरसँ दोबारा नै लिख सकै छी।


'''चेतौनी!'''
ई एकटा लोकप्रिय पन्नाक लेल एकटा भयंकर आ बिना आशाक कएल परिवर्तन भऽ सकैए।
आगाँ बढ़ैसँ पहिने अहाँ ई सुनिश्चित करू जे अहाँ एकर परिणाम बुझै छी।",
'movepagetalktext' => "सम्बन्धित चौबटिया पन्ना स्वचालित रूपेँ घसकत एकर संग '''जौं:'''
*एकटा खाली-नै चौबटिया पन्ना पहिनहियेसँ नव नामक संग अछि, वा
*अहाँ नीचाँक बॉक्स टिक हटा दी।

ताइ परिस्थितिमे, अहाँकेँ अपनेसँ पन्नाकेँ, आवश्यकतानुसार, घसकाबऽ वा मिज्झर करऽ पड़त।",
'movearticle'      => 'पन्ना घसकाउ:',
'newtitle'         => 'नव शीर्षकपर:',
'move-watch'       => 'जड़ि पन्ना आ छीप पन्ना देखू',
'movepagebtn'      => 'पन्ना घसकाउ',
'pagemovedsub'     => 'घसकल',
'movepage-moved'   => '\'\'\'"$1" घसकाएल गेल "$2"\'\'\' पर',
'articleexists'    => 'ओइ नामक एकटा पन्ना पहिनहियेसँ अछि, वा जे नाम अहाँ चयन केने छी से वांछित नै अछि। 
कृपा कऽ दोसर नामक चयन करू।',
'talkexists'       => "'''ई पन्ना स्वयं घसकाएल गेल, मुदा चौबटिया पन्ना नै घसकाओल जा सकल कारण नव शीर्षकपर एकटा एहने पहिनहियेसँ अछि।
कृपा कऽ एकरा सभकेँ अपनेसँ मिज्झर करू।'''",
'movedto'          => 'घसकाएल गेल',
'movetalk'         => 'सम्बन्धित चौबटिया पन्नाकेँ घसकाउ',
'1movedto2'        => '[[$1]] गेल एतय [[$2]]',
'1movedto2_redir'  => 'घसकाएल [[$1]] सँ [[$2]] घुरैसँ फराक',
'movelogpage'      => 'वृत्तलेख हटाउ',
'movereason'       => 'कारण:',
'revertmove'       => 'फेरसँ वएह',

# Export
'export' => 'पन्ना सभकेँ पठाउ',

# Thumbnails
'thumbnail-more' => 'पैघ',

# Import log
'importlogpage' => 'लॉगक आयात',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'अहाँक खेसरा पन्ना',
'tooltip-pt-mytalk'               => 'अहाँक वार्त्ता पृष्ठ',
'tooltip-pt-preferences'          => 'हमर मोनपसंद',
'tooltip-pt-watchlist'            => 'पन्ना सभ जकर परिवर्त्तन पर अहाँक नजरि अछि',
'tooltip-pt-mycontris'            => 'अहाँक योगदानक सूची',
'tooltip-pt-login'                => 'लॉग इन करब नीक, परञ्च आवश्यक नहि.',
'tooltip-pt-logout'               => 'फेर आयब',
'tooltip-ca-talk'                 => 'विषयसूचीक पन्नाक संबंधमे वर्त्तालाप',
'tooltip-ca-edit'                 => 'अहाँ एहि पन्नाकेँ संपादित कए सकैत छी। कृपया सुरक्षित करबासँ पहिने पूर्वप्रदर्शन बटम उपयोग करू।',
'tooltip-ca-addsection'           => 'नव खण्ड शुरू करू',
'tooltip-ca-viewsource'           => 'ऐ पन्नापर वरदहस्त छै।
अहाँ एकर जड़ि देख सकै छी।',
'tooltip-ca-history'              => 'ऐ पृष्ठक पहिलुका परिवर्तन सभ',
'tooltip-ca-protect'              => 'ऐ पन्नाकेँ बचाउ',
'tooltip-ca-delete'               => 'ऐ पन्नाकेँ मेटाउ',
'tooltip-ca-move'                 => 'ऐ पृष्ठकेँ हटाउ',
'tooltip-ca-watch'                => 'आइ पन्नाकेँ अपन साकांक्षसूचीमे राखू',
'tooltip-ca-unwatch'              => 'ऐ पन्नाकेँ हमर साकांक्ष सूचीसँ हटाउ',
'tooltip-search'                  => 'ताकू {{SITENAME}}',
'tooltip-search-go'               => 'पृष्ठपर जाऊ जौं एनमेन पृष्ठ रहए',
'tooltip-search-fulltext'         => 'ऐ जानकारीले ताकू पृष्ठ सभमे ताकू',
'tooltip-n-mainpage'              => 'मुख्य-पृष्ठ केँ देखू',
'tooltip-n-mainpage-description'  => 'मुख्या पन्नापर जाउ',
'tooltip-n-portal'                => 'प्रोजेक्टक विषयमे,अहाँ की कए सकैत छी,वस्तु प्राप्ति स्थल',
'tooltip-n-currentevents'         => 'लगक घटनाक विषयमे आधार सूचना प्राप्त करू।',
'tooltip-n-recentchanges'         => 'विकीमे लगक परिवर्त्तनक सूची.',
'tooltip-n-randompage'            => 'कोनो अनिर्धारित पन्ना लोड करू',
'tooltip-n-help'                  => 'प्राप्त करबाक स्थान.',
'tooltip-t-whatlinkshere'         => 'सभटा विकी-पन्नाक सूची जकर एतय लिंक अछि',
'tooltip-t-recentchangeslinked'   => 'ऐ पृष्ठक लागिक पन्नामे भेल नव परिवर्तन',
'tooltip-feed-rss'                => 'ऐ पन्ना लेल आर.एस.एस. सूचना',
'tooltip-feed-atom'               => 'ऐ पन्ना लेल अणु समदिया',
'tooltip-t-contributions'         => 'ऐ प्रयोक्ताक योगदानक सूची देखू',
'tooltip-t-emailuser'             => 'ऐ प्रयोक्ताकेँ ई-पत्र पठाउ',
'tooltip-t-upload'                => 'चित्र आकि मीडिया फाइलकेँ अपलोड करू',
'tooltip-t-specialpages'          => 'सभटा विशेष पन्नाक सूची',
'tooltip-t-print'                 => 'ऐ पृष्ठक छपैबला रूप',
'tooltip-t-permalink'             => 'पन्नाक ऐ संवर्धनक स्थायी लिंक',
'tooltip-ca-nstab-main'           => 'विषय सूचीबला पन्ना देखू',
'tooltip-ca-nstab-user'           => 'प्रयोक्ता पन्नाकेँ देखू',
'tooltip-ca-nstab-special'        => 'ई एकटा विशिष्ट पन्ना छी, अहाँ अही पन्नाकेँ संपादित नै कऽ सकै छी',
'tooltip-ca-nstab-project'        => 'परियोजना पन्ना देखू',
'tooltip-ca-nstab-image'          => 'पन्नाक पृष्ठ देखू',
'tooltip-ca-nstab-template'       => 'नमूना देखू',
'tooltip-ca-nstab-category'       => 'संवर्ग पन्ना देखू',
'tooltip-minoredit'               => 'एकरा मामली सम्पादन चिन्हित करू',
'tooltip-save'                    => 'अपन परिवर्त्तनके सुरक्षित करू',
'tooltip-preview'                 => 'परिवर्त्तनक प्रदर्शन, संजोगबाक पहिने एकर प्रयोग करू!',
'tooltip-diff'                    => 'देखाऊ जे परिवर्त्तन अहाँ एहि लेखमे कएलहुँ।',
'tooltip-compareselectedversions' => 'ऐ पन्नाक दू टा चयन कएल संशोधनक बीचक अन्तर देखू',
'tooltip-watch'                   => 'ऐ पन्नाकेँ अपन साकांक्ष सूचीमे जोड़ू',
'tooltip-rollback'                => '"प्रत्यावर्तन" ऐ पन्नाक अन्तिम योगदा करैबलाक सम्पादन (सम्पादन सभ) केँ एक क्लिकमे पुरान जगहपर लऽ जाउ',
'tooltip-undo'                    => '"फेरसँ वएह" सम्पादनकेँ पूर्वस्थितिमे लऽ जाइए आ पूर्वावलोकन अवस्थामे सम्पादन फॉर्म खोलैए। ई सारांशमे कारण जोड़बाक विकल्प दैत अछि।',

# Browsing diffs
'previousdiff' => 'पुरान सम्पादन',
'nextdiff'     => 'नव सम्पादन',

# Media information
'file-info-size' => '$1 × $2 चित्राणु, फाइल आकार: $3, माइम प्रकार: $4',
'file-nohires'   => '<छोट>ऐसँ बेशी आनन्तर्य उपलब्ध नै अछि।</छोट>',
'svg-long-desc'  => 'एस.वी.जी. फाइल, मामूली रूपमे $1 × $2 चित्रकण, फाइलक आकार: $3',
'show-big-image' => 'पूर्ण आनन्तर्य',

# Bad image list
'bad_image_list' => 'फॉर्मेट निम्न प्रकारेँ अछि:

मात्र सूचीबद्ध सामग्री (* सँ प्रारम्भ होय बला पंक्त्ति) विचारनीय अछि। पंक्त्तिक प्रथम लिंक आवश्यक रूपसँ खराब चित्रक लिंक होयबाक चाही।

ओही पंक्त्तिक कोनो आर लिंक अपवाद स्वरूप अछि, उदाहरणस्वरूप पन्ना जतय चित्र पंक्त्तिअहि पर होय।',

# Metadata
'metadata'          => 'प्रदत्तांश',
'metadata-help'     => 'ई फाइल अतिरिक्त सूचना दैत अछि, सम्भवतः ई अंकीय कैमरा वा स्कैनर द्वारा बनाएल वा अंकण कए जोड़ल गेल अछि।
जौं फाइलकेँ मूल रूपसँ परिवर्धित कएल गेल हएत तँ किछु विवरण पूर्ण रूपसँ परिवर्धित फाइलमे नै देखाएल गेल हएत।',
'metadata-expand'   => 'बढ़ाओल विवरण देखाउ।',
'metadata-collapse' => 'विस्तृत विवरण नुकाउ',
'metadata-fields'   => 'चित्र प्रदत्तांश क्षेत्र सभ जे ऐ संदेशमे संकलित अछि चित्र पन्ना प्रदर्शनमे लेल जाएत जखन प्रदत्तांश सारणी क्षतिग्रस्त हएत।  
आन सभ पूर्वनिधारित रूपेँ नुका जाएत।
* बनाउ
* प्रारूप
* मूल समए काल
* प्रायोगिक काल
* एफ. संख्या
* आइ.एस.ओ. गति प्रमाण
* दृश्यपथ नाप
* कलाकार
* सर्वाधिकार
* चित्र विवरण
* जी.पी.एस. देशांतर
* जी.पी.एस.अक्षांश
* जी.पी.एस. लम्बाकार',

# External editor support
'edit-externally'      => 'ऐ फाइलकेँ बाहरी अनुप्रयोगसँ हटाउ',
'edit-externally-help' => '(देखू [http://www.mediawiki.org/wiki/Manual:External_editors setup instructions] विषेष जानकारी लेल)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'सभ',
'namespacesall' => 'सभटा',
'monthsall'     => 'सभ',

# Watchlist editing tools
'watchlisttools-view' => 'सम्बन्धित परिवर्तन सभकेँ देखू',
'watchlisttools-edit' => 'साकांक्षसूचीकेँ देखू आ सम्पादित करू',
'watchlisttools-raw'  => 'काँच साकांक्षसूची संपादित करू',

# Special:Version
'version'              => 'संस्करण',
'version-extensions'   => 'संस्करणक आगाँ',
'version-specialpages' => 'खास पन्ना',

# Special:SpecialPages
'specialpages' => 'विशेष पन्ना',

# HTML forms
'htmlform-submit'              => 'दिअ',
'htmlform-reset'               => 'परिवर्तन खतम करू',
'htmlform-selectorother-other' => 'आन',

# SQLite database support
'sqlite-has-fts' => '$1 पूर्ण-पाठ खोज सहायता',
'sqlite-no-fts'  => '$1 बिन पूर्ण-पाठ खोज सहायताक',

);
