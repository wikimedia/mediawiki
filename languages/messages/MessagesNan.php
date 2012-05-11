<?php
/** Min Nan Chinese (Bân-lâm-gú)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ianbu
 * @author Kaihsu
 */

$datePreferences = array(
	'default',
	'ISO 8601',
);
$defaultDateFormat = 'nan';
$dateFormats = array(
	'nan time' => 'H:i',
	'nan date' => 'Y-"nî" n-"goe̍h" j-"jἰt" (l)',
	'nan both' => 'Y-"nî" n-"goe̍h" j-"jἰt" (D) H:i',
);

$messages = array(
# User preference toggles
'tog-underline' => 'Liân-kiat oē té-sûn:',
'tog-justify' => '排齊段落',
'tog-hideminor' => 'Am chòe-kīn ê sió kái-piàn',
'tog-hidepatrolled' => '最近改的，若巡過就掩掉',
'tog-newpageshidepatrolled' => '若巡過的頁，就免佇新頁清單',
'tog-extendwatchlist' => 'Tián-khui kàm-sī-toaⁿ khoàⁿ só͘-ū ê kái-piàn, m̄-chí sī choè-kīn--ê',
'tog-usenewrc' => 'Ēng ka-kiông pán khoàⁿ chòe-kīn ê kái-piàn (su-iàu JavaScript)',
'tog-numberheadings' => 'Phiau-tê chū-tōng pian-hō',
'tog-showtoolbar' => 'Hián-sī pian-chi̍p ke-si-tiâu (su-iàu JavaScript)',
'tog-editondblclick' => 'Siang-ji̍h ia̍h-bīn to̍h ē-tàng pian-chi̍p (su-iàu JavaScript)',
'tog-editsection' => 'Ji̍h [siu-kái] chit-ê liân-kiat to̍h ē-tàng pian-chi̍p toāⁿ-lo̍h',
'tog-editsectiononrightclick' => 'Chiàⁿ-ji̍h (right click) toāⁿ-lo̍h (section) phiau-tê to̍h ē-tàng pian-chi̍p toāⁿ-lo̍h (su-iàu JavaScript)',
'tog-showtoc' => 'Hián-sī bo̍k-chhù (3-ê phiau-tê í-siōng ê ia̍h)',
'tog-rememberpassword' => 'Kì tiâu bi̍t-bé, āu-chōa iōng ( $1 {{PLURAL:$1|day|kang}} lāi)',
'tog-watchcreations' => 'Kā goá khui ê ia̍h ka-ji̍p kàm-sī-toaⁿ lāi-té',
'tog-watchdefault' => 'Kā goá pian-chi̍p kòe ê ia̍h ka-ji̍p kàm-sī-toaⁿ lāi-té',
'tog-watchmoves' => 'Kā goá soá ê ia̍h ka-ji̍p kàm-sī-toaⁿ',
'tog-watchdeletion' => 'Kā goá thâi tiāu ê ia̍h ka-ji̍p kàm-sī-toaⁿ',
'tog-minordefault' => 'Chiām-tēng bī-lâi ê siu-kái lóng sī sió-siu-ká',
'tog-previewontop' => 'Sûn-khoàⁿ ê lōe-iông tī pian-chi̍p keh-á thâu-chêng',
'tog-previewonfirst' => 'Thâu-pái pian-chi̍p seng khoàⁿ-māi',
'tog-nocache' => 'Koaiⁿ-tiāu ia̍h ê cache',
'tog-enotifwatchlistpages' => '監視單內底的文章若有人改，寄電子批予我',
'tog-enotifusertalkpages' => '我的討論頁若有人改，寄電子批予我',
'tog-enotifminoredits' => '頁若改一屑屑仔，嘛寄電子批予我',
'tog-enotifrevealaddr' => '予別人看會著我的電子批地址',
'tog-shownumberswatching' => '顯示有監視這頁的人數',
'tog-oldsig' => '這馬的簽名：',
'tog-fancysig' => 'Chhiam-miâ mài chò liân-kiat',
'tog-externaleditor' => 'Iōng gōa-pō· pian-chi̍p-khì (kan-na hō͘ ko-chhiú, he ài tī lí ê tiān-náu koh siat-tēng. [//www.mediawiki.org/wiki/Manual:External_editors Siông-chêng.])',
'tog-externaldiff' => 'Iōng gōa-pō· diff (kan-na hō͘ ko-chhiú, he ài tī lí ê tiān-noá koh siat-tēng. [//www.mediawiki.org/wiki/Manual:External_editors Siông-chêng.])',
'tog-showjumplinks' => '會使用"跳去"這个連結功能',
'tog-uselivepreview' => '用隨看覓（愛有JavaScript）（試驗的）',
'tog-forceeditsummary' => 'Pian-chi̍p khài-iàu bô thiⁿ ê sî-chūn, kā goá thê-chhéⁿ',
'tog-watchlisthideown' => 'Kàm-sī-toaⁿ bián hián-sī goá ê pian-chi̍p',
'tog-watchlisthidebots' => 'Kàm-sī-toaⁿ bián hián-sī ki-khì pian-chi̍p',
'tog-watchlisthideminor' => 'Kàm-sī-toaⁿ bián hián-sī sió siu-kái',
'tog-watchlisthideliu' => '登入用者的編輯免出現佇監視單',
'tog-watchlisthideanons' => '無名氏的編輯免出現佇監視單',
'tog-watchlisthidepatrolled' => '有巡過的編輯免出現佇監視單',
'tog-ccmeonemails' => '我寄予別人的電子批嘛寄一份予家己',
'tog-diffonly' => 'Diff ē-pêng bián hián-sī ia̍h ê loē-iông',
'tog-showhiddencats' => '顯示藏起來的類別',
'tog-norollbackdiff' => '佇絞轉去頁的時，免管精差有偌濟',

'underline-always' => 'Tiāⁿ-tio̍h',
'underline-never' => 'Tiāⁿ-tio̍h mài',
'underline-default' => 'Tòe liû-lám-khì ê default',

# Font style option in Special:Preferences
'editfont-style' => '編輯區用的字體型式：',
'editfont-default' => 'Tòe liû-lám-khì ê default',
'editfont-monospace' => 'Monospaced字體',
'editfont-sansserif' => 'Sans-serif jī-thé',
'editfont-serif' => 'Serif jī-thé',

# Dates
'sunday' => 'Lé-pài',
'monday' => 'Pài-it',
'tuesday' => 'Pài-jī',
'wednesday' => 'Pài-saⁿ',
'thursday' => 'Pài-sì',
'friday' => 'Pài-gō·',
'saturday' => 'Pài-la̍k',
'sun' => 'Lé-pài',
'mon' => 'It',
'tue' => 'Jī',
'wed' => 'Saⁿ',
'thu' => 'Sì',
'fri' => 'Gō·',
'sat' => 'La̍k',
'january' => '1-goe̍h',
'february' => '2-goe̍h',
'march' => '3-goe̍h',
'april' => '4-goe̍h',
'may_long' => '5-goe̍h',
'june' => '6-goe̍h',
'july' => '7-goe̍h',
'august' => '8-goe̍h',
'september' => '9-goe̍h',
'october' => '10-goe̍h',
'november' => '11-goe̍h',
'december' => '12-goe̍h',
'january-gen' => 'Chiaⁿ-goe̍h',
'february-gen' => 'Jī-goe̍h',
'march-gen' => 'Saⁿ-goe̍h',
'april-gen' => 'Sì-goe̍h',
'may-gen' => 'Gō·-goe̍h',
'june-gen' => 'La̍k-goe̍h',
'july-gen' => 'Chhit-goe̍h',
'august-gen' => 'Peh-goe̍h',
'september-gen' => 'Káu-goe̍h',
'october-gen' => 'Cha̍p-goe̍h',
'november-gen' => 'Cha̍p-it-goe̍h',
'december-gen' => 'Cha̍p-jī-goe̍h',
'jan' => '1g',
'feb' => '2g',
'mar' => '3g',
'apr' => '4g',
'may' => '5g',
'jun' => '6g',
'jul' => '7g',
'aug' => '8g',
'sep' => '9g',
'oct' => '10g',
'nov' => '11g',
'dec' => '12g',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Lūi-pia̍t|ê lūi-pia̍t}}',
'category_header' => 'Tī "$1" chit ê lūi-pia̍t ê bûn-chiuⁿ',
'subcategories' => 'Ē-lūi-pia̍t',
'category-media-header' => 'Tī lūi-pia̍t "$1" ê mûi-thé',
'category-empty' => "''Chit-má chit ê lūi-pia̍t  bô ia̍h ia̍h-sī mûi-thé.''",
'hidden-categories' => '{{PLURAL:$1|Hidden category|Chhàng khí-lâi ê lūi-pia̍t}}',
'hidden-category-category' => 'Chhàng khí--lâi ê lūi piat',
'category-subcat-count' => '{{PLURAL:$2|Chit ê lūi-piat chí-ū ē-bīn ê ē-lūi-pia̍t.|Chit ê lūi-piat ū ē-bīn {{PLURAL:$1| ê ē-lūi-piat|$1 ê ē-lūi-piat}}, choân-pō͘ $2 ê.}}',
'category-subcat-count-limited' => 'Chit ê lūi-piat ū ē-bīn ê {{PLURAL:$1| ē-lūi-pia̍t|$1 ē-lūi-pia̍t}}.',
'category-article-count' => '{{PLURAL:$2|Chit ê lūi-piat chí-ū ē-bīn ê ia̍h.|Ē-bīn {{PLURAL:$1|bīn ia̍h sī|$1bīn ia̍h sī}} tī chit lūi-pia̍t, choân-pō͘ $2 bīn ia̍h}}',
'category-article-count-limited' => 'Ē-bīn {{PLURAL:$1|ia̍h sī|$1 ia̍h sī }} tī chit ê lūi-pia̍t.',
'category-file-count' => '{{PLURAL:$2|Chit ê lūi-piat chí-ū ē-bīn ê tóng-àn.|Ē-bīn {{PLURAL:$1| ê tóng-àn sī|$1 ê tóng-àn sī}} tī chit lūi-pia̍t, choân-pō͘ $2 ê tóng-àn}}',
'category-file-count-limited' => 'Chit-má chit-ê lūi-pia̍t ū {{PLURAL:$1| ê tóng-àn}}',
'listingcontinuesabbrev' => '(chiap-sòa thâu-chêng)',
'index-category' => 'Ū sik-ín ê ia̍h',
'noindex-category' => 'Bī sik-ín ê ia̍h.',
'broken-file-category' => 'Sit-khì tóng-àn liân-kiat ê ia̍h.',

'about' => 'Koan-hē',
'article' => 'Loē-iông ia̍h',
'newwindow' => '(ē khui sin thang-á hián-sī)',
'cancel' => 'Chhú-siau',
'moredotdotdot' => '猶有',
'mypage' => 'Góa ê ia̍h',
'mytalk' => 'Góa ê thó-lūn',
'anontalk' => 'Chit ê IP ê thó-lūn-ia̍h',
'navigation' => 'Se̍h chām',
'and' => '&#32;kap',

# Cologne Blue skin
'qbfind' => 'Chhoé',
'qbbrowse' => 'Liū-lám',
'qbedit' => 'Siu-kái',
'qbpageoptions' => 'Chit ia̍h',
'qbpageinfo' => 'Bo̍k-lo̍k',
'qbmyoptions' => 'Goá ê ia̍h',
'qbspecialpages' => 'Te̍k-sû-ia̍h',
'faq' => 'Būn-tah',
'faqpage' => 'Project:Būn-tah',

# Vector skin
'vector-action-addsection' => 'Ke chi̍t-ê toān-lo̍h',
'vector-action-delete' => 'Thâi',
'vector-action-move' => 'Sóa khì',
'vector-action-protect' => 'Pó-hō·',
'vector-action-undelete' => 'chhú-siau thâi tiàu',
'vector-action-unprotect' => 'Chhú-siau pó-hō·',
'vector-simplesearch-preference' => 'Chhái-iōng ka-kiông-pán ê chhiau-soh kiàn-gī ( chí hān tī Vector bīn-phoê)',
'vector-view-create' => 'Khai-sí siá',
'vector-view-edit' => 'Siu-kái',
'vector-view-history' => 'khoàⁿ le̍k-sú',
'vector-view-view' => 'Tha̍k',
'vector-view-viewsource' => 'Khoàⁿ goân-sú lōe-iông',
'actions' => 'Tōng-chok',
'namespaces' => 'Miâ-khong-kan',
'variants' => 'piàn-thé',

'errorpagetitle' => 'Chhò-gō·',
'returnto' => 'Tò-tńg khì $1.',
'tagline' => 'Ùi {{SITENAME}}',
'help' => 'Soat-bêng-su',
'search' => 'Chhiau-chhoē',
'searchbutton' => 'Chhiau',
'go' => 'Lâi-khì',
'searcharticle' => 'Lâi-khì',
'history' => 'Ia̍h le̍k-sú',
'history_short' => 'le̍k-sú',
'updatedmarker' => 'Téng hoê goá lâi chiah liáu ū kái koè--ê',
'printableversion' => 'Ìn-soat pán-pún',
'permalink' => 'Éng-kiú liân-kiat',
'print' => 'Ìn-soat',
'view' => 'Khoàⁿ',
'edit' => 'Siu-kái',
'create' => 'Khai-sí siá',
'editthispage' => 'Siu-kái chit ia̍h',
'create-this-page' => 'Khai-sí siá chit ia̍h',
'delete' => 'Thâi',
'deletethispage' => 'Thâi chit ia̍h',
'undelete_short' => 'Kiù {{PLURAL:$1| ê siu-káit|$1  ê siu-kái}}',
'viewdeleted_short' => 'Khoàⁿ {{PLURAL:$1|chi̍t-ê thâi tiàu--ê pian-chi̍p|$1 ê thâi tiàu--ê pian-chi̍p}}',
'protect' => 'Pó-hō·',
'protect_change' => 'kái-piàn',
'protectthispage' => 'Pó-hō· chit ia̍h',
'unprotect' => 'Chhú-siau pó-hō·',
'unprotectthispage' => 'Chhú-siau pó-hō· chit ia̍h',
'newpage' => 'Sin ia̍h',
'talkpage' => 'Thó-lūn chit ia̍h',
'talkpagelinktext' => 'thó-lūn',
'specialpage' => 'Te̍k-sû-ia̍h',
'personaltools' => 'Kò-jîn kang-khū',
'postcomment' => 'Hoat-piáu phêng-lūn',
'articlepage' => 'Khoàⁿ loē-iông ia̍h',
'talk' => 'thó-lūn',
'views' => 'Khoàⁿ',
'toolbox' => 'Ke-si kheh-á',
'userpage' => 'Khoàⁿ iōng-chiá ê Ia̍h',
'projectpage' => 'Khoàⁿ sū-kang ia̍h',
'imagepage' => 'Khoàⁿ tóng-àn ia̍h',
'mediawikipage' => 'Khoàⁿ sìn-sit ia̍h',
'templatepage' => 'Khoàⁿ pang-bô͘ ia̍h',
'viewhelppage' => 'Khoàⁿ pang-chō͘ ia̍h',
'categorypage' => 'Khoàⁿ lūi-pia̍t ia̍h',
'viewtalkpage' => 'Khoàⁿ thó-lūn',
'otherlanguages' => 'Kî-thaⁿ ê gí-giân',
'redirectedfrom' => '(Tùi $1 choán--lâi)',
'redirectpagesub' => 'Choán-ia̍h',
'lastmodifiedat' => 'Chit ia̍h tī $1,  $2 ū kái--koè',
'viewcount' => 'Pún-ia̍h kàu taⁿ ū {{PLURAL:$1| pái|$1 pái}}  ê sú-iōng.',
'protectedpage' => 'Siū pó-hō͘ ê ia̍h',
'jumpto' => 'Thiàu khì:',
'jumptonavigation' => 'Se̍h chām',
'jumptosearch' => 'chhiau-chhoē',
'view-pool-error' => 'Pháiⁿ-sè, chit-má chú-ki siuⁿ koè bô-êng.
Siuⁿ koè chē lâng beh khoàⁿ chit ia̍h.
Chhiáⁿ sio-tán chi̍t-ē,  chiah koh lâi khoàⁿ chit ia̍h.

$1',
'pool-timeout' => 'Chhiau-koè só-tēng ê sî-kan',
'pool-queuefull' => 'Tūi-lia̍t pâi moá ah',
'pool-errorunknown' => 'M̄-chai siáⁿ chhò-gō͘',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'hían-sī',
'aboutpage' => 'Project:koan-hē',
'copyright' => 'Tī $1 tiâu-kiāⁿ chi hā khó sú-iōng loē-iông',
'copyrightpage' => '{{ns:project}}:Pán-khoân',
'currentevents' => 'Sin-bûn sū-kiāⁿ',
'currentevents-url' => 'Project:Sin-bûn sū-kiāⁿ',
'disclaimers' => 'Bô-hū-chek seng-bêng',
'disclaimerpage' => 'Project:It-poaⁿ ê seng-bêng',
'edithelp' => 'Án-choáⁿ siu-kái',
'edithelppage' => 'Help:Pian-chi̍p',
'helppage' => 'Help:Bo̍k-lio̍k',
'mainpage' => 'Thâu-ia̍h',
'mainpage-description' => 'Thâu-ia̍h',
'policy-url' => 'Project:Chèng-chhek',
'portal' => 'Siā-lí mn̂g-chhùi-kháu',
'portal-url' => 'Project:Siā-lí mn̂g-chhùi-kháu',
'privacy' => 'Ín-su chèng-chhek',
'privacypage' => 'Project:Ún-su chèng-chhek',

'badaccess' => 'Siū-khoân chhò-ngō͘',
'badaccess-group0' => 'Lí bô ún-chún chò lí iau-kiû ê tōng-chok.',
'badaccess-groups' => 'Lí beh chò ê tōng-chok chí hān {{PLURAL:$2| tīn| tīn-goân chi it }}: $1 ê iōng-chiá.',

'versionrequired' => 'Ài MediaWiki $1 ê pán-pún',
'versionrequiredtext' => 'Beh iōng chit ia̍h ài MediaWiki $1 ê pán-pún.
Chhiáⁿ khoàⁿ [[Special:Version|pán-pún ia̍h]].',

'ok' => 'Hó ah',
'retrievedfrom' => 'Lâi-goân: "$1"',
'youhavenewmessages' => 'Lí ū $1 ($2).',
'newmessageslink' => 'sin sìn-sit',
'newmessagesdifflink' => 'chêng 2 ê siu-tēng-pún ê diff',
'youhavenewmessagesmulti' => 'Lí tī $1 ū sin sìn-sit',
'editsection' => 'siu-kái',
'editold' => 'siu-kái',
'viewsourceold' => 'Khoàⁿ goân-sú lōe-iông',
'editlink' => 'siu-kái',
'viewsourcelink' => 'Khoàⁿ goân-sú lōe-iông',
'editsectionhint' => 'Pian-chi̍p toān-lo̍h: $1',
'toc' => 'Bo̍k-lo̍k',
'showtoc' => 'khui',
'hidetoc' => 'siu',
'collapsible-collapse' => 'Siu',
'collapsible-expand' => 'Khui',
'thisisdeleted' => 'Khoàⁿ a̍h-sī kiù $1?',
'viewdeleted' => 'Beh khoàⁿ $1？',
'restorelink' => '{{PLURAL:$1|chi̍t ê thâi-tiàu ê pian-chi̍p|$1 thâi-tiàu ê pian-chi̍p}}',
'feedlinks' => 'Tēng khoàⁿ:',
'feed-invalid' => 'Bô-hāu ê tēng khoàⁿ lūi-hêng.',
'feed-unavailable' => 'Bô thê-kiong liân-ha̍p tēng khoàⁿ.',
'site-rss-feed' => '$1 ê RSS tēng khoàⁿ',
'site-atom-feed' => '$1 ê Atom tēng-khoàⁿ',
'page-rss-feed' => '"$1" ê RSS tēng khoàⁿ',
'page-atom-feed' => '"$1" ê Atom tēng khoàⁿ',
'red-link-title' => '$1 (bô hit ia̍h)',
'sort-descending' => 'Hā-kàng pâi-lia̍t',
'sort-ascending' => 'Seng-koân pâi-lia̍t',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Bûn-chiuⁿ',
'nstab-user' => 'Iōng-chiá ê ia̍h',
'nstab-media' => 'Mûi-thé',
'nstab-special' => 'Te̍k-sû-ia̍h',
'nstab-project' => 'Sū-kang ia̍h',
'nstab-image' => 'Tóng-àn',
'nstab-mediawiki' => 'Sìn-sit',
'nstab-template' => 'Pang-bô·',
'nstab-help' => 'Pang-chō͘ ia̍h',
'nstab-category' => 'Lūi-pia̍t',

# Main script and global functions
'nosuchaction' => 'Bô chit-khoán tōng-chok',
'nosuchactiontext' => 'Hit ê URL só͘ chí-tēng ê tōng-chok bô-hāu.
Lí khó-lêng phah m̄-tio̍h URL, ia̍h sī ji̍h tio̍h chhò-ngō͘ ê liân-kiat.
Che mā khó-lêng sī {{SITENAME}} só͘ sú-iōng ê nńg-thé chhut būn-tê.',
'nosuchspecialpage' => 'Bô chit ê te̍k-sû-ia̍h',
'nospecialpagetext' => '<strong>Bô lí beh tih ê te̍k-sû-ia̍h。</strong>

[[Special:SpecialPages|{{int:specialpages}}]] sī só͘-ū ê te̍k-sû-ia̍h lia̍t-pió.',

# General errors
'error' => 'Chhò-gō·',
'databaseerror' => 'Chu-liāu-khò· chhò-gō·',
'dberrortext' => 'Chu-liāu-khò͘ hoat-seng cha-sûn ê gí-hoat chhò-ngō͘.
Che khó-lêng sī nńg-thé ê chhò-ngō͘.
Téng chi̍t ê cha-sûn sī :
<blockquote><tt>$1</tt></blockquote>
tī hâm-sò͘  "<tt>$2</tt>".
Chu-liāu-khò͘ thoân hoê ê chhò-ngō͘ "<tt>$3: $4</tt>".',
'dberrortextcl' => '發生一个查詢資料庫語法錯誤，頂一个欲查詢資料庫是：
"$1"
佇"$2"
資料庫送回一个錯誤"$3: $4"',
'laggedslavemode' => "'''提醒你：'''這頁可能無包括最近改的。",
'readonly' => 'Chu-liāu-khò· só tiâu leh',
'enterlockreason' => 'Phah beh hong-só ê lí-iû, pau-koah ko͘-kè siáⁿ-mi̍h sî-chūn ē kái-tû hong-só.',
'readonlytext' => 'Chu-liāu-khò· hiān-chú-sî só tiâu leh, bô khai-hòng hō· lâng siu-kái. Che tāi-khài sī in-ūi teh pān î-siu khang-khòe, oân-sêng liáu-āu èng-tong tō ē hôe-ho̍k chèng-siông. Hū-chek ê hêng-chèng jîn-oân lâu chit-ê soat-bêng: $1',
'missing-article' => 'Chu-liāu-khò͘ chhoē bô ia̍h ê luē-iông, ia̍h ê miâ "$1" $2 .

Che it-poaⁿ sī in-ūi koè-sî ê cheng-chha ia̍h sī le̍k-sú liân-kiat ê ia̍h í-keng hông thâi tiàu.

Nā m̄-sī hit chióng chêng-hêng, lí khó-lêng tú tio̍h nńg-thé ê chhò-ngō͘. Chhiáⁿ pò hō͘ chi̍t ūi [[Special:ListUsers/sysop|koán-lí-goân]], ūi liân-kiat hiâ khì lâu thong-ti .',
'missingarticle-rev' => '（修訂本#: $1）',
'missingarticle-diff' => '(精差：$1, $2)',
'readonly_lag' => '佇附屬資料庫伺服器對主伺服器來更新的時陣，資料庫就已經自動鎖牢咧。',
'internalerror' => 'Loē-pō͘ ê chhò-ngō͘',
'internalerror_info' => 'Loē-pō͘ ê chhò-ngō͘: $1',
'fileappenderrorread' => 'Ka-ji̍p(append) ê sî bô-hoat-tō͘ thak "$1".',
'fileappenderror' => 'Bô-hoat-tō͘ kā "$1" chiap khì "$2".',
'filecopyerror' => 'Bô-hoat-tō· kā tóng-àn "$1" khó·-pih khì "$2".',
'filerenameerror' => 'Bô-hoat-tō· kā tóng-àn "$1" kái-miâ chò "$2".',
'filedeleteerror' => 'Bô-hoat-tō· kā tóng-àn "$1" thâi tiāu',
'directorycreateerror' => 'Bô-hoat-tō͘ khui bo̍k-lo̍k "$1".',
'filenotfound' => 'Chhōe bô tóng-àn "$1".',
'fileexistserror' => 'Bô-hoat-tō͘ chûn-ji̍p tóng-àn "$1": í-keng ū chit ê tóng-àn',
'unexpected' => 'Koài-koài ê pió-tat: "$1"="$2"。',
'formerror' => 'Chhò-gō·: bô-hoat-tō· kā pió sàng chhut khì.',
'badarticleerror' => 'Bē-tàng tiàm chit ia̍h chip-hêng chit ê tōng-chok.',
'cannotdelete' => 'Bô-hoat-tō· kā  "$1" hit ê ia̍h a̍h-sī iáⁿ-siōng thâi tiāu. (Khó-lêng pa̍t-lâng í-keng kā thâi tiāu ah.)',
'cannotdelete-title' => '無法度共"$1"這頁刣掉',
'badtitle' => 'M̄-chiâⁿ piau-tê',
'badtitletext' => 'Iau-kiû ê piau-tê sī bô-hāu ê, khang ê, a̍h-sī liân-kiat chhò-gō· ê inter-language/inter-wiki piau-tê.',
'perfcached' => 'Ē-kha ê chu-liāu ùi khoài-chhú(cache) lâi--ê, só·-í khó-lêng m̄-sī siōng sin ê. Khoài-chhú lāi-té siōng chē khǹg {{PLURAL:$1| chi̍t tiâu|$1 tiâu}}.',
'perfcachedts' => 'Ē-kha ê chu-liāu ùi khoài-chhú(cache) lâi--ê, tī $1 keng-sin--koè. Khoài-chhú lāi-té siōng chē khǹg {{PLURAL:$4| chi̍t tiâu |$4 tiâu}}.',
'querypage-no-updates' => 'Chit-má bē-sái kái chit ia̍h.
Chia ê chu-liāu bē-tàng sui tiông-sin chéng-lí.',
'wrong_wfQuery_params' => 'Chhò-ngō͘ ê chham-sò͘ chhoân hō͘ wfQuery（）<br />
Hâm-sò͘: $1<br />
Cha-sûn: $2',
'viewsource' => 'Khoàⁿ goân-sú lōe-iông',
'viewsource-title' => '看$1的內容',
'actionthrottled' => 'Tōng-chok hông tóng leh.',
'actionthrottledtext' => 'Ūi-tio̍h thê-hông lah-sap ê chhú-tì,  lí ū hông hān-chè tī té sî-kan lāi chò siuⁿ chē pái chit ê tōng-chok,  taⁿ lí í-keng chhiau-koè hān-chè.
Chhiáⁿ tī kúi hun-cheng hāu chiah koh chhì.',
'protectedpagetext' => 'Chit ia̍h hông só tiâu leh, bē pian-chi̍p tit.',
'viewsourcetext' => 'Lí ē-sái khoàⁿ ia̍h khó͘-pih chit ia̍h ê goân-sú loē-iông:',
'viewyourtext' => "你會使共'''你的編輯'''的內容拷備來這頁：",
'protectedinterface' => 'Chit ia̍h thê-kiong nńg-thé kài-bīn ēng ê bûn-jī. Ūi beh ī-hông lâng chau-that, só͘-í ū siū tio̍h pó-hō͘.',
'editinginterface' => "'''Sè-jī:''' Lí tng teh siu-kái 1 bīn thê-kiong nńg-thé kài-bīn bûn-jī ê ia̍h. 
Jīn-hô kái-piàn to ē éng-hióng tio̍h kî-thaⁿ iōng-chiá ê sú-iōng kài-bīn.
Nā ūi-tio̍h hoan-e̍k, chhiáⁿ khó-lū sú-iōng [//translatewiki.net/wiki/Main_Page?setlang=nan translatewiki.net], MediaWiki ê chāi-tē hoà sū-kang.",
'sqlhidden' => '(Tshàng SQL tsa-sûn)',
'cascadeprotected' => 'Chit-ê ia̍h í-keng hông pó-hō͘ bē kái tit. In-ūi i tī ē-bīn {{PLURAL:$1|ê|ê}} liân-só pó-hō͘ lāi-té:
$2',
'namespaceprotected' => "Lí bô khoân-lī kái '''$1'''  miâ-khong-kan ê ia̍h",
'customcssprotected' => '你無權限通改這CSS頁面，因為伊包括著其他用戶的個人設定。',
'customjsprotected' => '你無權限通改這javaScript頁面，因為伊包括著其他用戶的個人設定。',
'ns-specialprotected' => '特殊頁袂使改得',
'titleprotected' => "這个標題已經予[[User:$1|$1]]保護牢咧袂使用。理由是''$2''。",
'filereadonlyerror' => '無法度改"$1" 這个檔案，因為"$2"這个儲存庫佇讀的模式。
共封鎖的管理員有解說講："$3"。',

# Virus scanner
'virus-badscanner' => "毋著的設定: 毋知影的病毒掃瞄器：''$1''",
'virus-scanfailed' => '掃描失敗（號碼 $1）',
'virus-unknownscanner' => 'M̄-chai siáⁿ pēⁿ-to̍k:',

# Login and logout pages
'logouttext' => "'''Lí í-keng teng-chhut.'''

Lí ē-sái mài kì-miâ kè-siok sú-iōng {{SITENAME}}, mā ē-sái iōng kāng-ê a̍h-sī  pa̍t-ê sin-hūn [[Special:UserLogin|têng teng-ji̍p]].
Chhiaⁿ chù-ì: ū-kóa ia̍h ū khó-lêng khoàⁿ-tio̍h bē-su lí iû-goân teng-ji̍p tiong; che chi-iàu piàⁿ tiāu lí ê browser ê cache chiū ē chèng-siông.",
'welcomecreation' => '==Hoan-gêng $1!==
Í-keng khui hó lí ê kháu-chō.  M̄-hó bē-kì-tit chhiâu lí tī [[Special:Preferences|{{SITENAME}} ê iōng-chiá siat-tēng]].',
'yourname' => 'Lí ê iōng-chiá miâ-chheng:',
'yourpassword' => 'Lí ê bi̍t-bé:',
'yourpasswordagain' => 'Têng phah bi̍t-bé:',
'remembermypassword' => 'Kì tiâu góa ê bi̍t-bé (āu-chhiú teng-ji̍p iōng) (tī $1 {{PLURAL:$1|day|days}} kang lāi)',
'securelogin-stick-https' => '登入後繼續以HTTPS連接',
'yourdomainname' => '你的網域',
'externaldberror' => '這可能是因為驗證資料庫錯誤，抑是你hông禁止改你的外部口座。',
'login' => 'Teng-ji̍p',
'nav-login-createaccount' => 'Teng-ji̍p / khui sin kháu-chō',
'loginprompt' => 'Thiⁿ ē-kha ê chu-liāu thang khui sin hō·-thâu a̍h-sī teng-ji̍p {{SITENAME}}.',
'userlogin' => 'Teng-ji̍p / khui sin kháu-chō',
'userloginnocreate' => '登入',
'logout' => 'Teng-chhut',
'userlogout' => 'Teng-chhut',
'notloggedin' => 'Bô teng-ji̍p',
'nologin' => "Bô poàⁿ ê kháu-chō? '''$1'''.",
'nologinlink' => 'Khui 1 ê kháu-chō',
'createaccount' => 'Khui sin kháu-chō',
'gotaccount' => "Í-keng ū kháu-chō? '''$1'''.",
'gotaccountlink' => 'Teng-ji̍p',
'userlogin-resetlink' => '袂記得你登入的資料？',
'createaccountmail' => 'Thàu koè tiān-chú-phoe',
'createaccountreason' => '理由：',
'badretype' => 'Lí su-ji̍p ê 2-cho· bi̍t-bé bô tùi.',
'userexists' => 'Lí beh ti̍h ê iōng-chiá miâ-chheng í-keng ū lâng iōng. Chhiáⁿ kéng pa̍t-ê miâ.',
'loginerror' => 'Teng-ji̍p chhò-gō·',
'createaccounterror' => 'Bô hoat-tō͘ khui kháu-chō: $1',
'nocookiesnew' => '口座開好矣，毋過你猶未登入，
{{SITENAME}}用cookies記錄用者，
你無拍開cookies功能，
請拍開，通記錄你的用者名稱佮密碼。',
'nocookieslogin' => '{{SITENAME}}用 Cookies 記錄用戶，你共關掉，請拍開閣重新登入。',
'nocookiesfornew' => '這个用者口座猶未開，阮無法度確認伊的來源，
請確定你您已經拍開cookies功能了，重新載入這頁閣重試。',
'noname' => '你無拍一个有效的用者名稱。',
'loginsuccesstitle' => 'Teng-ji̍p sêng-kong',
'loginsuccess' => 'Lí hiān-chhú-sî í-keng teng-ji̍p {{SITENAME}} chò "$1".',
'nosuchuser' => 'Chia bô iōng-chiá hō-chò "$1". Miâ-jī  ū hun toā-siá, sio-siá . Chhiáⁿ kiám-cha lí ê phèng-im, a̍h-sī [[Special:UserLogin/signup|khui sin káu-chō]].',
'nosuchusershort' => 'Bô "$1" chit ê iōng-chiá miâ.
Tùi khoàⁿ-māi,  lí phah--ê.',
'nouserspecified' => 'Lí ài chí-tēng chi̍t ê iōng-chiá miâ.',
'login-userblocked' => '這个用者已經hông封鎖，無允准登入。',
'wrongpassword' => 'Lí su-ji̍p ê bi̍t-bé ū têng-tâⁿ. Chhiáⁿ têng chhì.',
'wrongpasswordempty' => 'Bi̍t-bé keh-á khang-khang. Chhiáⁿ têng chhì.',
'passwordtooshort' => '密碼至少愛有{{PLURAL:$1|一个字元|$1字元}}',
'password-name-match' => '你的密碼袂使佮你的用者名稱相仝',
'password-login-forbidden' => '這个用者名稱佮密碼已經hông禁止',
'mailmypassword' => 'Kià sin bi̍t-bé hō· góa',
'passwordremindertitle' => '{{SITENAME}} the-chheN li e bit-be',
'passwordremindertext' => '有人（可能是你，佇$1 IP地址）已經佇{{SITENAME}}申請新密碼 （$4）。
用者"$2"的一个臨時密碼已經設定做"$3"。
若毋是你申請的，你需要馬上登入並且選擇一个新密碼。
你的臨時密碼會佇{{PLURAL:$5|一工|$5工}}內過期。

若是別人申請的，抑是你已經想起你的密碼，而且不想欲改，
你會使莫管這个信息而且繼續用你的舊密碼。',
'noemail' => 'Kì-lo̍k bô iōng-chiá "$1" ê e-mail chū-chí.',
'noemailcreate' => '你愛提供一个有效的電子批地址',
'passwordsent' => 'Ū kià sin bi̍t-bé khì "$1" chù-chheh ê e-mail chū-chí. Siu--tio̍h liáu-āu chhiáⁿ têng teng-ji̍p.',
'blocked-mailpassword' => '你的IP地址hông封鎖，袂當編輯，為著安全起見，袂當使用密碼恢復功能。',
'eauthentsent' => '一張確認的批已經寄去提示的電子批地址。
佇其它批寄去彼的口座進前，你愛先照彼張批的指示，才通確定彼个口座是你的。',
'throttled-mailpassword' => '密碼提醒的資料已經佇{{PLURAL:$1|點鐘|$1點鐘}}前寄出。為著防止濫使用，限定佇{{PLURAL:$1|點鐘|$1點鐘}}內只通送一擺密碼提醒。',
'mailerror' => 'Kià phoe tú tio̍h chhò-gō·: $1',
'acct_creation_throttle_hit' => 'Tī koè-khì 24 tiám-cheng lāi,  ū chit ê iōng lí IP bāng-chí ê lâng í-keng khui {{PLURAL:$1|1 account|$1 kháu-chō}}. He sī hit ê sî-kan lāi thang chò ê.
Tiō-sī kóng, tī chit-má iōng chit ê IP bāng-chí ê lâng bē-sái koh khui jīm-hô kháu-chō.',
'emailauthenticated' => 'Lí ê e-mail chū-chí tī $2 $3 khak-jīn sêng-kong.',
'emailnotauthenticated' => 'Lí ê e-mail chū-chí iáu-bōe khak-jīn ū-hāu, só·-í ē--kha ê e-mail kong-lêng bē-ēng-tit.',
'noemailprefs' => 'Tī lí ê siat-piān chí-tēng chi̍t ê tiān-chú-phoe tē-chí thang hō͘ chia ê kong-lêng ē-tàng ēng.',
'emailconfirmlink' => 'Chhiáⁿ khak-jīn lí ê e-mail chū-chí ū-hāu',
'invalidemailaddress' => '電子批的地址無正確，規格毋著，
請拍一个符合規格的地址抑是放空格。',
'cannotchangeemail' => '口座的e-mail住址無法度佇這个wiki改',
'emaildisabled' => '這个網站袂當寄電子批。',
'accountcreated' => '口座開好矣',
'accountcreatedtext' => '$1的口座開好矣',
'createaccount-title' => '佇{{SITENAME}}開好口座',
'createaccount-text' => '有人佇{{SITENAME}}用你的電子批地址開一个名"$2"的口座($4)，密碼是 "$3"，
你這馬應該去登入，而且去改密碼。

若是彼个口座開毋著，你會使莫管這个訊息。',
'usernamehasherror' => '用者名稱袂使有#字元',
'login-throttled' => '你已經試傷濟擺登入的動作，
請小等一下才閣試。',
'login-abort-generic' => '你的登入無成功，中途退出。',
'loginlanguagelabel' => '話語：$1',
'suspicious-userlogout' => '你登出的要求已經被拒絕，因為伊看起來是對無連線的瀏覽器抑是快取代理傳送來的。',

# E-mail sending
'php-mail-error-unknown' => '佇PHP的 mail() 函數的未知錯誤',
'user-mail-no-addy' => 'Siūⁿ beh kià tiān-chú-phoe, m̄-koh bô siá tē-chí.',

# Change password dialog
'resetpass' => 'Kái bi̍t-bé',
'resetpass_announce' => '你是對一張電子批的臨時編碼登入的。欲完成登入，你愛佇遮設定新密碼：',
'resetpass_header' => 'Kái káu-chō ê bi̍t-bé.',
'oldpassword' => 'Kū bi̍t-bé:',
'newpassword' => 'Sin bi̍t-bé:',
'retypenew' => 'Têng phah sin bi̍t-bé:',
'resetpass_submit' => '設定密碼而且登入',
'resetpass_success' => '你的密碼已經改成功！
這馬你咧登入...',
'resetpass_forbidden' => 'Bi̍t-bé bē-sái piàn.',
'resetpass-no-info' => '你愛登入了，才通直接進入這頁',
'resetpass-submit-loggedin' => 'Kái bi̍t-bé',
'resetpass-submit-cancel' => 'Chhú-siau',
'resetpass-wrong-oldpass' => '無效的臨時抑是現在的密碼，
你可能已經成功更過你的密碼，抑是申請一个新的臨時密碼。',
'resetpass-temp-password' => 'Lîm-sî ê bi̍t-bé:',

# Special:PasswordReset
'passwordreset' => 'Têng siat bi̍t-bé',
'passwordreset-text' => '完成這个表，就通收著一封提醒你口座詳情的電子批。',
'passwordreset-legend' => 'Têng siat bi̍t-bé',
'passwordreset-disabled' => '佇這个Wiki已經禁止重設密碼',
'passwordreset-pretext' => '{{PLURAL:$1||拍下跤資料內底的一个}}',
'passwordreset-username' => 'Lí ê iōng-chiá miâ-chheng:',
'passwordreset-domain' => '網域：',
'passwordreset-capture' => '敢欲看產生的電子批？',
'passwordreset-capture-help' => '若你選這个框，電子批（包括臨時的密碼）會予你看著，而且傳送予用者。',
'passwordreset-email' => 'Tiān-chú-phoe tē-chí:',
'passwordreset-emailtitle' => '佇{{SITENAME}}面頂的的口座詳細',
'passwordreset-emailtext-ip' => '有人（可能是你，對$1這IP）要求發一个{{SITENAME}}（$4）口座詳情的提示。彼个用戶{{PLURAL:$3|是|是}}佮下跤電子批地址有關係：

$2

{{PLURAL:$3|這个臨時密碼|遮的臨時密碼}}會佇{{PLURAL:$5|一工 |$5工}}內到期。
你這馬應該登入，而且選擇一个新密碼。若是別人做的要求，抑是你已經記
起來你的密碼，你閣無想欲改，你會當免管這个信息，而且繼續用你的密碼。',
'passwordreset-emailtext-user' => '佇{{SITENAME}}的用者$1要求發一个{{SITENAME}}（$4）口座詳情的提示。彼个用者{{PLURAL:$3|是|是}}佮下跤電子批地址有關係：

$2

{{PLURAL:$3|這个臨時密碼|遮的臨時密碼}}會佇{{PLURAL:$5|一工 |$5工}}內到期。
你這馬應該登入，而且選一个新密碼。若是別人做的要求，抑是你已經記
起來你的密碼，你閣無想欲改，你會當免管這个信息，而且繼續用你的密碼。',
'passwordreset-emailelement' => 'Iōng-chiá: $1
Lîm-sî ê bi̍t-bé: $2',
'passwordreset-emailsent' => 'Chit hong thê-chhíⁿ ê  tiān-chú-phoe í-keng kià chhut.',
'passwordreset-emailsent-capture' => '一張提醒的電子批已經寄出，佇下面通看著。',
'passwordreset-emailerror-capture' => '一張提醒的電子批已經寫好，佇下面通看著，毋過送袂到用者: $1。',

# Special:ChangeEmail
'changeemail' => 'Kái tiān-chú-phoe ê tē-chí',
'changeemail-header' => '改口座的電子批地址。',
'changeemail-text' => '共這个表寫了，才通改你的電子批地址，你嘛愛拍密碼來確定你欲改。',
'changeemail-no-info' => '你愛登入了，才通直接進入這頁。',
'changeemail-oldemail' => 'Chit-má ê E-mail tē-chí:',
'changeemail-newemail' => 'Sin E-mail ê chū-chí:',
'changeemail-none' => '（無）',
'changeemail-submit' => '改電子批',
'changeemail-cancel' => 'Chhú-siau',

# Edit page toolbar
'bold_sample' => 'Chho·-thé bûn-jī',
'bold_tip' => 'Chho·-thé jī',
'italic_sample' => 'Chhú-thé ê bûn-jī',
'italic_tip' => 'Chhú-thé jī',
'link_sample' => 'Liân-kiat piau-tê',
'link_tip' => 'Lōe-pō· ê liân-kiat',
'extlink_sample' => 'http://www.example.com liân-kiat piau-tê',
'extlink_tip' => 'Gōa-pō· ê liân-kiat (ē-kì-tit thâu-chêng ài ke http://)',
'headline_sample' => 'Thâu-tiâu bûn-jī',
'headline_tip' => 'Tē-2-chân (level 2) ê phiau-tê',
'nowiki_sample' => 'Chia siá bô keh-sek ê bûn-jī',
'nowiki_tip' => '無照Wiki的規格',
'image_sample' => 'Iann-siong-e-le.jpg',
'image_tip' => 'Giap tī lāi-bīn ê iáⁿ-siōng',
'media_tip' => '檔案連結',
'sig_tip' => 'Lí ê chhiam-miâ kap sî-kan ìn-á',
'hr_tip' => '橫線 （小心使用）',

# Edit pages
'summary' => 'Khài-iàu:',
'subject' => 'Tê-bo̍k/piau-tê:',
'minoredit' => 'Che sī sió siu-kái',
'watchthis' => 'Kàm-sī chit ia̍h',
'savearticle' => 'Pó-chûn chit ia̍h',
'preview' => 'Seng khoàⁿ-māi',
'showpreview' => 'Seng khoàⁿ-māi',
'showlivepreview' => '即時先看覓',
'showdiff' => 'Khòaⁿ kái-piàn ê pō·-hūn',
'anoneditwarning' => "'''Kéng-kò:''' Lí bô teng-ji̍p. Lí ê IP chū-chí ē kì tī pún ia̍h ê pian-chi̍p le̍k-sú lāi-bīn.",
'anonpreviewwarning' => "''你並無登入，保存頁面的時陣，會共你的IP地址記錄佇這頁的編輯歷史。''",
'missingsummary' => "'''提醒：'''你無拍一个編輯標題，若你閣點「{{int:savearticle}}」一擺，你的編輯會無不帶標題保存起來。",
'missingcommenttext' => '請佇下跤拍意見',
'missingcommentheader' => "'''提醒：'''你無為你的意見寫一个標題，
若你閣點「{{int:savearticle}}」一擺，你的編輯會無帶標題保存起來。",
'summary-preview' => 'Khài-iàu ê preview:',
'subject-preview' => 'Ū-lám tê-bo̍k/piau-tê:',
'blockedtitle' => '用者hông封鎖',
'blockedtext' => "'''你的用者名稱抑是IP地址已經hông封鎖'''

這擺的封鎖是由$1所做的，
原因是''$2''。

* 這擺封鎖開始的時間是：$8
* 這擺封鎖到期的時間是：$6
* hông封鎖的用者：$7

妳會使聯絡$1抑是其他的[[{{MediaWiki:Grouppage-sysop}}|管理員]]來討論這擺封鎖。
除非你有佇你的[[Special:Preferences|口座設定]]當中，有設一个有效的電子批地址，若無，你是袂當使用「寄電子批予用者」的功能。若有，這个功能是無封鎖。
你這馬IP地址是$3，被封鎖用者ID是 #$5，
請佇你的詢問當中包括以上資料。",
'autoblockedtext' => "你的IP地址已經自動封鎖，因為彼个地址是一个予$1封鎖掉的用者咧用。

理由是：
：''$2''

* 這擺封鎖開始的時間是：$8
* 這擺封鎖到期的時間是：$6
* hông封鎖的用者：$7

你會使聯絡$1抑是其他的[[{{MediaWiki:Grouppage-sysop}}|管理員]]，討論這擺的封鎖。
除非你有佇你的[[Special:Preferences|用者設定]]當中，設一个有效的電子批地址，若無你是袂當使用「寄電子批予這个用戶」的功能。你並無hông封鎖寄電子批。

你這馬的IP地址是$3，被封鎖用者ID是 #$5，
請佇你的查詢當中，註明面頂所有的資料。",
'blockednoreason' => '無寫理由',
'whitelistedittext' => 'Lí ài $1 chiah ē-sái siu-kái.',
'confirmedittext' => '佇改這頁進前，你愛確認你的電子批地址，
請透過[[Special:Preferences|用者設便]]的設定來驗證你的電子批地址。',
'nosuchsectiontitle' => 'Chhoé bô toān-lo̍h',
'nosuchsectiontext' => '你欲改的段落已經無佇咧，
可能佇你看頁面的時陣，已經徙位抑是刣掉。',
'loginreqtitle' => 'Su-iàu Teng-ji̍p',
'loginreqlink' => 'Teng-ji̍p',
'loginreqpagetext' => 'Lí ài $1 chiah thang khoàⁿ pat ia̍h.',
'accmailtitle' => 'Bi̍t-bé kià chhut khì ah.',
'accmailtext' => "Hō͘ [[User talk:$1|$1]] ê chi̍t ê iōng loān-sò͘ sán-seng ê bi̍t-bé í-keng kìa khì $2.

Kháu-chō ê sin bi̍t-bé thang tī teng-ji̍p liáu tī ''[[Special:ChangePassword|siu-kái bi̍t-bé]]'' ia̍h kái tiāu.",
'newarticle' => '(Sin)',
'newarticletext' => "Lí tòe 1 ê liân-kiat lâi kàu 1 bīn iáu-bōe chûn-chāi ê ia̍h. Beh khai-sí pian-chi̍p chit ia̍h, chhiáⁿ tī ē-kha ê bûn-jī keh-á lāi-té phah-jī. ([[{{MediaWiki:Helppage}}|Bo̍k-lio̍k]] kà lí án-choáⁿ chìn-hêng.) Ká-sú lí bô-tiuⁿ-tî lâi kàu chia, ē-sai chhi̍h liû-lám-khì ê '''téng-1-ia̍h''' tńg--khì.",
'anontalkpagetext' => "''Pún thó-lūn-ia̍h bô kò·-tēng ê kháu-chō/hō·-thâu, kan-na ū 1 ê IP chū-chí (chhin-chhiūⁿ 123.456.789.123). In-ūi bô kāng lâng tī bô kāng sî-chūn ū khó-lêng tú-hó kong-ke kāng-ê IP, lâu tī chia ê oē ū khó-lêng hō· bô kāng lâng ê! Beh pī-bián chit khoán būn-tê, ē-sái khì [[Special:UserLogin/signup|khui 1 ê hō·-thâu a̍h-sī teng-ji̍p]].''",
'noarticletext' => '這頁這馬無內容，
你會使佇別頁[[Special:Search/{{PAGENAME}}|搜揣這頁標題]]，
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 搜揣有關的記錄]，
抑是[{{fullurl:{{FULLPAGENAME}}|action=edit}} 編輯這頁]</span>。',
'noarticletext-nopermission' => '這頁這馬無內容，
你會使佇別頁[[Special:Search/{{PAGENAME}}|揣這頁標題]]，
抑是<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}揣有關的記錄]</span>。',
'userpage-userdoesnotexist' => '猶未有「$1」這个口座，
請佇開／改這頁進前先檢查一下。',
'userpage-userdoesnotexist-view' => '用者口座「$1」猶未開',
'blocked-notice-logextract' => '這个用者這馬hông封鎖，
下跤有最近封鎖的紀錄通參考：',
'clearyourcache' => "'''Chù-ì:''' Pó-chûn liáu-āu, tio̍h ē-kì leh kā liû-lám-khì ê cache piàⁿ tiāu chiah khoàⁿ-ē-tio̍h kái-piàn. 
*'''Firefox / Safari:''' chhi̍h tiâu \"Shift\" kâng-sî-chūn tiám-kik ''Reload/têng-sin chài-ji̍p'' a̍h-sī chhi̍h ''Ctrl-F5'' \"Ctrl-R\" kî-tiong chi̍t ê (''⌘-R'' tī Mac) 
* '''Google Chrome:''' chhi̍h ''Ctrl-Shift-R'' (''⌘-R-Shift-R'' tī Mac)
'''Internet Explorer :'''chhi̍h tiâu \"Ctrl\" kâng-sî-chūn tiám-kek ''Refresh/têng-sin chài-ji̍p'' a̍h-sī chhi̍h \"Ctrl-F5\" 
* '''Konqueror:'''  tiám-kek ''Reload/têng-sin chài-ji̍p'' a̍h-sī chhi̍h ''F5''
* '''Opera:''' piàⁿ-tiāu cache tī ''Tools(ke-si) → Preferences(siat-piān)''",
'usercssyoucanpreview' => "'''Phiat-pō·''': Pó-chûn chìn-chêng ē-sái chhi̍h 'Seng khoàⁿ-māi' kiám-cha sin ê CSS.",
'userjsyoucanpreview' => "'''Phiat-pō·''': Pó-chûn chìn-chêng ē-sái tiám-kek \"{{int:showpreview}}\" ; chhì lí ê sin JavaScript.",
'usercsspreview' => "'''Thê-chhíⁿ lí,  che chí-sī sian khoàⁿ-māi  lí ê su-jîn CSS'''
'''Che iáu-bōe pó-chûn--khí-lâi !'''",
'userjspreview' => "'''Sè-jī! Lí hiān-chú-sî chhì khoàⁿ--ê sī lí ka-kī ê javascript; che iáu-bōe pó-chûn--khí-lâi!'''",
'sitecsspreview' => "'''提醒你，這只是先看覓你的私人CSS'''
'''猶未保存！'''",
'sitejspreview' => "'''提醒你，這只是先看覓這个JavaScrpt程式'''
'''猶未保存！'''",
'userinvalidcssjstitle' => "'''提醒：'''遐無面板\"\$1\"，
家己設的 .css 佮 .js 頁愛用小寫標題，親像，
{{ns:user}}:Foo/vector.css 無仝
{{ns:user}}:Foo/Vector.css。",
'updated' => '（改過矣）',
'note' => "'''Chù-ì:'''",
'previewnote' => "'''Thê-chhéⁿ lí, che chí-sī  hō͘ lí sian khoàⁿ chi̍t-ē.'''
Lí kái--ê iáu-bōe pó-chûn--khí-lâi !",
'continue-editing' => '繼續編輯',
'previewconflict' => '這个先看覓會反應你文字編輯區的內容，顯示佇面頂。佇你保存了就會公開。',
'session_fail_preview' => "'''Pháiⁿ-sè! Gún chiām-sî bô hoat-tō͘ chhú-lí lí ê pian-chi̍p (goân-in: \"phàng-kiàn sú-iōng kî-kan ê chu-liāu\"). Lô-hoân têng chhì khoàⁿ-māi. Ká-sú iû-goân bô-hāu, ē-sái teng-chhut koh-chài teng-ji̍p hoān-sè tō ē-tit kái-koat.'''",
'session_fail_preview_html' => "'''歹勢！因為phàng見資料，阮無法度處理你的編輯。'''

''因為{{SITENAME}}有開放原始 HTML 碼，先看覓先看無，以防止 JavaScript 的攻擊。''

'''若這改編輯過程無問題，請閣試一改。若閣有問題，請[[Special:UserLogout|登出]]了後，才閣重登入。'''",
'token_suffix_mismatch' => "'''因為你用者端的編輯毀損一寡標點符號字元，你的編輯無被接受。'''
這種情況會出現佇你用網路上匿名代理服務的時陣。",
'edit_form_incomplete' => "'''一寡部份的編輯無送到伺服器，請檢查你的編輯是毋是完整，才閣試。'''",
'editing' => 'Siu-kái $1',
'creating' => '當咧建立$1',
'editingsection' => 'Pian-chi̍p $1 (section)',
'editingcomment' => 'Teh pian-chi̍p $1 (lâu-oē)',
'editconflict' => 'Siu-kái sio-chhiong: $1',
'explainconflict' => "Ū lâng tī lí tng teh siu-kái pún-ia̍h ê sî-chūn oân-sêng kî-tha ê siu-kái.
Téng-koân ê bûn-jī-keh hián-sī hiān-chhú-sî siōng sin ê lōe-iông.
Lí ê kái-piàn tī ē-kha ê bûn-jī-keh. Lí su-iàu chiōng lí chò ê kái-piàn kap siōng sin ê lōe-iông chéng-ha̍p.
'''Kan-na''' téng-koân keh-á ê bûn-jī ē tī lí chhi̍h \"{{int:savearticle}}\" liáu-āu pó-chûn khí lâi.",
'yourtext' => 'Lí ê bûn-jī',
'storedversion' => 'Chu-liāu-khò· ê pán-pún',
'nonunicodebrowser' => "'''提醒：你的瀏覽器佮Unicode編碼袂合。''
遮有一个工作區會使予你通安全編輯頁面: 
非ASCII字元會以十六進位編碼模式出現佇編輯框當中。",
'editingold' => "'''KÉNG-KÒ: Lí tng teh siu-kái chit ia̍h ê 1 ê kū siu-tēng-pún. Lí nā kā pó-chûn khí lâi, chit ê siu-tēng-pún sòa-āu ê jīm-hô kái-piàn ē bô khì.'''",
'yourdiff' => 'Chha-pia̍t',
'copyrightwarning' => "請注意你佇{{SITENAME}}的所有貢獻攏會照$2發布（看$1的說明）。
若你無希望你寫的文字hông隨意改抑是傳送，請毋莫佇遮送出。<br />
你嘛向阮保證你送出來的內容是你家己寫的，抑是對無版權抑有授權的遐抄來的。
'''毋通無授權就送出有版權作品！'''",
'copyrightwarning2' => "請注意你佇{{SITENAME}}的所有貢獻，可能會予別的用者修改抑徙走，
若你無希望你寫的文字hông無情修改，就毋莫佇遮提交。<br />
你嘛向阮保證這是你家己寫的，抑是對無版權抑有授權(看$1的說明)的遐抄來的。
'''毋通無授權就送出有版權作品！'''",
'longpageerror' => "'''錯誤: 你送出來的文章長度有{{PLURAL:$1|1 KB|$1 KB}} ，這大過{{PLURAL:$2|1 KB|$2 KB}}的上大界限。'''
伊無法度保存。",
'readonlywarning' => "'''CHÙ-Ì: Chu-liāu-khò· taⁿ só tiâu leh thang pān î-siu khang-khòe, só·-í lí hiān-chú-sî bô thang pó-chûn jīn-hô phian-chi̍p hāng-bo̍k. Lí ē-sái kā siong-koan pō·-hūn tah--ji̍p-khì 1-ê bûn-jī tóng-àn pó-chûn, āu-chhiú chiah koh kè-sio̍k.'''

Kā só tiâu ê koán-lí-goân ū lâu oē: $1",
'protectedpagewarning' => "'''KÉNG-KÒ: Pún ia̍h só tiâu leh. Kan-taⁿ ū hêng-chèng te̍k-koân ê iōng-chiá (sysop) ē-sái siu-kái.'''
Ē-kha ū choè-kīn ê kì-lo̍k thang chham-khó:",
'semiprotectedpagewarning' => "'''注意：'''這頁hông保護牢咧，只有有註冊的用者通編輯。
下跤有最近的記錄通參考：",
'cascadeprotectedwarning' => "'''注意：'''這頁已經hông保護牢咧，只有有管理員權限的用者才有法度改，因為這頁佇{{PLURAL:$1|頁|頁}}的連鎖保護內底:",
'titleprotectedwarning' => "'''注意：這頁已經hông保護牢咧，需要有[[Special:ListGroupRights|指定權限]]的才會當創建。'''
下跤有最近的記錄通參考：",
'templatesused' => 'Chit ia̍h iōng {{PLURAL:$1|Template|Templates}} chia ê pang-bô· :',
'templatesusedpreview' => 'Chit ê preview iōng chia ê {{PLURAL:$1|Template|pang-bô͘}}',
'templatesusedsection' => 'Chit ê toāⁿ-lo̍k iōng chia ê {{PLURAL:$1|Template|pang-bô͘}}',
'template-protected' => '(pó-hō͘)',
'template-semiprotected' => '(poàⁿ pó-hō͘)',
'hiddencategories' => '這頁是屬於{{PLURAL:$1|一个隱藏類別|$1个隱藏類別}}的成員：',
'nocreatetitle' => '欲創建頁hông限制',
'nocreatetext' => '{{SITENAME}}限制創建新頁的功能。你會當倒退佮改現有的頁，抑是[[Special:UserLogin|登入抑是開一个口座]]。',
'nocreate-loggedin' => '你無授權去創建新頁。',
'sectioneditnotsupported-title' => '編輯段落是袂當得',
'sectioneditnotsupported-text' => '段落編輯佇這頁袂當得',
'permissionserrors' => '授權錯誤',
'permissionserrorstext' => '你無允准去做彼，因為下跤
{{PLURAL:$1|原因|原因}}:',
'permissionserrorstext-withaction' => 'Lí bô ún-chún chò $2, in-ūi ē-kha
{{PLURAL:$1|iân-kò͘|iân-kò͘}}:',
'recreate-moveddeleted-warn' => "'''Sè-jī: Lí taⁿ chún-pī beh khui ê ia̍h, chêng bat hō͘ lâng thâi tiāu koè.''' 

Lí tio̍h chim-chiok soà-chiap pian-chi̍p chit ia̍h ê pit-iàu-sèng. 
Chia ū chit ia̍h ê san-tû kì-lo̍k hō͘ lí chham-khó:",
'moveddeleted-notice' => '這頁已經予人刣掉，
下跤有刣掉佮徙走的記錄通參考。',
'log-fulllog' => '看全部的記錄',
'edit-hook-aborted' => '取消編輯，
無講啥物原因',
'edit-gone-missing' => '無法度改新這頁，
伊可能拄hông刣掉。',
'edit-conflict' => 'Siu-kái sio-chhiong',
'edit-no-change' => '你的編輯閬過，因為攏無改著字。',
'edit-already-exists' => '無法度開新頁，
已經有彼頁。',
'defaultmessagetext' => 'Siat piān ê bûn-jī',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''警示：'''這頁用傷濟擺函數呼叫。

伊應該少過{{PLURAL:$2|擺|擺}}，這馬有{{PLURAL:$1|擺|擺}}。",
'expensive-parserfunction-category' => '用傷濟擺函數呼叫的頁',
'post-expand-template-inclusion-warning' => "'''Kéng-pò:'''Pau ji̍t lâi ê pán-bôo sioⁿ koè tsē ia̍h tuā.
Ū chi̍t-koá-á ē bô pau ji̍t lâi.",
'post-expand-template-inclusion-category' => '頁的枋模所包的量已經超過',
'post-expand-template-argument-warning' => "'''警示'''：這个頁至少包括一个枋模的參數超過展開時的大細。
遮的參數會忽略過。",
'post-expand-template-argument-category' => '包括跳過枋模參數的頁面',
'parser-template-loop-warning' => '踅圓框的枋模: [[$1]]',
'parser-template-recursion-depth-warning' => '已經超過枋模的recusion深度限制($1)',
'language-converter-depth-warning' => '已經超過字詞轉換器的深度限制（$1）',

# "Undo" feature
'undo-success' => 'Pian-chi̍p í-keng chhú-siau. Chhiáⁿ khak-tēng, liáu-āu kā ē-kha ho̍k-goân ê kái-piàn pó-chûn--khí-lâi.',
'undo-failure' => 'Pian-chi̍p bē-tàng chhú-siau, in-ūi chhiong tio̍h kî-kan chhah-ji̍p ê pian-chi̍p.',
'undo-norev' => '這个編輯袂當取消，因為無這个修訂本，抑是hông刣掉。',
'undo-summary' => 'Chhú-siau [[Special:Contributions/$2|$2]] ([[User talk:$2|thó-lūn]]) ê siu-tēng-pún $1',

# Account creation failure
'cantcreateaccounttitle' => '無法度開口座',
'cantcreateaccount-text' => "對這个 IP 地址 ('''$1''') 開口座已經予 [[User:$3|$3]] 禁止。

$3共禁止的原因是 ''$2''。",

# History pages
'viewpagelogs' => 'Khoàⁿ chit ia̍h ê logs',
'nohistory' => 'Chit ia̍h bô pian-chi̍p-sú.',
'currentrev' => 'Hiān-chú-sî ê siu-tēng-pún',
'currentrev-asof' => '$1的上新修訂本',
'revisionasof' => '$1 ê siu-tēng-pún',
'revision-info' => '$2佇$1的修訂本',
'previousrevision' => '←Khah kū ê siu-tēng-pún',
'nextrevision' => 'Khah sin ê siu-tēng-pún→',
'currentrevisionlink' => 'khoàⁿ siōng sin ê siu-tēng-pún',
'cur' => 'taⁿ',
'next' => '下一个',
'last' => 'chêng',
'page_first' => 'Tùi thâu-chêng',
'page_last' => 'Tùi āu-piah',
'histlegend' => 'Pán-pún pí-phēng: tiám-soán beh pí-phēng ê pán-pún ê liú-á, liáu-āu chhi̍h ENTER a̍h-sī ē-kha hit tè sì-kak.<br />Soat-bêng: (taⁿ) = kap siōng sin pán-pún pí-phēng, (chêng) = kap chêng-1-ê pán-pún pí-phēng, ~ = sió siu-kái.',
'history-fieldset-title' => '看歷史',
'history-show-deleted' => '只有刣掉的',
'histfirst' => 'Tùi thâu-chêng',
'histlast' => 'Tùi āu-piah',
'historysize' => '({{PLURAL:$1|1位元組|$1位元組}})',
'historyempty' => '（空的）',

# Revision feed
'history-feed-title' => '修改的歷史',
'history-feed-description' => '這頁佇本站的修改歷史',
'history-feed-item-nocomment' => '$1 tī $2',
'history-feed-empty' => '無你欲挃的頁，
伊可能hông刣掉抑是改名，
試[[Special:Search|搜揣本站]]，通創建新頁。',

# Revision deletion
'rev-deleted-comment' => '（編輯概要已經清掉）',
'rev-deleted-user' => '用者名稱已經清掉',
'rev-deleted-event' => '動作的記錄已經清掉',
'rev-deleted-user-contribs' => '[用者名稱抑是IP地址已經徙掉 - 佇貢獻當中隱藏編輯]',
'rev-deleted-text-permission' => "這頁的修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]，有詳細的訊息。",
'rev-deleted-text-unhide' => "這頁的修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]，
若你欲繼續行，你照仝會使[$1看這个修訂本]。",
'rev-suppressed-text-unhide' => "這頁的修訂本已經hông'''壓縮掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 壓縮記錄]，
若你欲繼續行，你照仝會使[$1看這个修訂本]。",
'rev-deleted-text-view' => "這頁的修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]，有詳細的訊息。",
'rev-suppressed-text-view' => "這頁的修訂本已經hông'''壓縮掉'''。
你會使佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 壓縮記錄]看詳細。",
'rev-deleted-no-diff' => "你無法度看精差，因為其中一个修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]有通看詳細。",
'rev-suppressed-no-diff' => "你無法度看精差，因為其中一个修訂本已經hông'''刣掉\"。",
'rev-deleted-unhide-diff' => "欲做精差比並的一个修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]，
若你欲繼續行，你照仝會使[$1看這个精差比並]。",
'rev-suppressed-unhide-diff' => '精差比並的其中一个修訂本已經hông壓縮掉。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 壓縮記錄]通看詳細，
若你欲繼續行，你照仝會使[$1看這个精差比並]。',
'rev-deleted-diff-view' => "欲做精差比並的一个修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]，通看這个精差比並。",
'rev-suppressed-diff-view' => "欲做精差比並的一个修訂本已經hông'''壓縮掉'''。
你會使佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 壓縮記錄]，看這个精差比並。",
'rev-delundel' => '顯示／掩',
'rev-showdeleted' => '顯示',
'revisiondelete' => '刣掉/取消刣掉 修訂本',
'revdelete-nooldid-title' => '目標是無效的修訂本',
'revdelete-nooldid-text' => '你欲用這个功能進前無指定欲改的修訂本，抑是無你指定的修訂本，抑是你欲改現時的版本隱藏起來。',
'revdelete-nologtype-title' => '無指定記錄的類型',
'revdelete-nologtype-text' => '你無指定佗一个記錄類型欲做這个動作',
'revdelete-nologid-title' => '無效的記錄項目',
'revdelete-nologid-text' => '你無指定佗一个記錄項目欲進行這个動作，抑是無你指定的項目。',
'revdelete-no-file' => '無你指定的檔案',
'revdelete-show-file-confirm' => '你敢確定欲看"<nowiki>$1</nowiki>"佇 $2 $3 刣掉的修訂本？',
'revdelete-show-file-submit' => '是',
'revdelete-selected' => "'''[[:$1]]{{PLURAL:$2|所選的修訂本|所選的修訂本}}：'''",
'logdelete-selected' => "'''{{PLURAL:$1|所選的記錄項目|所選的記錄項目}}：'''",
'revdelete-text' => "'''佇頁面歷史佮記錄猶看有刣掉的修訂本佮彼件物，毋過內容部份是無予大眾看。'''
佇{{SITENAME}}的其他管理員是會當看隱藏的內容，而且除非有另外附加的限制，伊用這个仝款介面通取消刣掉。",
'revdelete-confirm' => '請確定你欲按呢做，你嘛了解後果，而且你欲做的這个動作符合[[{{MediaWiki:Policy-url}}|政策]]。',
'revdelete-suppress-text' => "掩崁'''只'''佇下跤情況下才使用:
* 可能是誹謗信息
* 無適當的個人資料
*：''厝的地址、電話號碼、社會安全號碼抑身份證號碼等等。''",
'revdelete-legend' => '設定通看的制限',
'revdelete-hide-text' => '隱藏修訂本文本',
'revdelete-hide-image' => '隱藏檔案內容',
'revdelete-hide-name' => '隱藏動作佮目標',
'revdelete-hide-comment' => '隱藏編輯概要',
'revdelete-hide-user' => '隱藏編輯者的名稱抑 IP 地址',
'revdelete-hide-restricted' => '對系統管理員佮其他人攏掩崁資料',
'revdelete-radio-same' => '（毋共改）',
'revdelete-radio-set' => '是',
'revdelete-radio-unset' => '毋是',
'revdelete-suppress' => '對系統管理員佮其他人攏掩崁資料',
'revdelete-unsuppress' => '共恢復的修訂本徙掉限制',
'revdelete-log' => '理由：',
'revdelete-submit' => '對所選的{{PLURAL:$1|修訂本}}來施實',
'revdelete-success' => "'''改修訂本是毋是通予人看，已經改好矣'''",
'revdelete-failure' => "'''改修訂本是毋是通予人看的動作無成功'''
$1",
'logdelete-success' => "'''事件的可見性質已經成功設定'''",
'logdelete-failure' => "'''事件的可見性質無法度設定：'''
$1",
'revdel-restore' => '改敢看會著',
'revdel-restore-deleted' => '刣掉去的修訂本',
'revdel-restore-visible' => '看會著的修訂本',
'pagehist' => '頁的歷史',
'deletedhist' => '已經刣掉的歷史',
'revdelete-hide-current' => '當咧隱藏佇$1 $2的項目錯誤：這是這馬的修訂本，袂使隱藏。',
'revdelete-show-no-access' => '當咧顯示佇$1 $2的項目錯誤：這个項目已經標示做"有限制"，
你袂當處理。',
'revdelete-modify-no-access' => "當欲改$1 $2項目的錯誤：這个項目已經標示做''有限制''，
你袂當處理。",
'revdelete-modify-missing' => '當咧改項目編號 $1錯誤：伊對資料庫當中消失！',
'revdelete-no-change' => "'''提醒'''：佇$1 $2的項目已經有人請求可見性質的設定。",
'revdelete-concurrent-change' => '錯誤佇欲改$1 $2的項目：當你欲改伊的設定時，已經有另外的人共改過。
請檢查記錄。',
'revdelete-only-restricted' => '錯誤佇欲隱藏$1 $2的項目時發生：你袂當一方面選擇一項另外的可見性質，閣不准管理員看彼項目。',
'revdelete-reason-dropdown' => '*捷用的刣掉理由
** 侵犯版權
** 不適合的個人資料
** 可能是誹謗資料',
'revdelete-otherreason' => '其他／另外的理由：',
'revdelete-reasonotherlist' => '其他理由',
'revdelete-edit-reasonlist' => '編輯刣掉的理由',
'revdelete-offender' => '修訂本的編輯者：',

# Suppression log
'suppressionlog' => '隱藏記錄',
'suppressionlogtext' => '下跤是管理員為著藏文章所做的刣掉，抑封鎖的清單。
若欲看這馬禁止使用、封鎖的清單，請看[[Special:BlockList|封鎖清單]]。',

# History merging
'mergehistory' => '合併兩个頁的修改歷史:',
'mergehistory-header' => '這頁通予你合併一个頁的歷史到另外一个新的頁。
會當予這改變更通接紲歷史頁。',
'mergehistory-box' => '合併兩个頁的修訂本:',
'mergehistory-from' => '來源頁：',
'mergehistory-into' => '目標頁：',
'mergehistory-list' => '可以合併的編輯歷史',
'mergehistory-merge' => '下跤[[:$1]]的修訂本會使合併到[[:$2]]。用彼个選項鈕仔去合併只有佇指定時間進前所創建的修訂本。愛注意的是若使用導航連結就會重設這一欄。',
'mergehistory-go' => '顯示通合併的編輯',
'mergehistory-submit' => '合併修訂本',
'mergehistory-empty' => '無修訂本通合併',
'mergehistory-success' => '[[:$1]]的{{PLURAL:$3|篇|篇}}修訂本已經成功合併到[[:$2]]。',
'mergehistory-fail' => '無法度進行歷史的合併，請重新檢查彼頁佮時間參數。',
'mergehistory-no-source' => '無$1這个來源頁',
'mergehistory-no-destination' => '無$1這个目標頁',
'mergehistory-invalid-source' => '來源頁愛有一个有效的標題',
'mergehistory-invalid-destination' => '目標頁愛有一个有效的標題',
'mergehistory-autocomment' => '已經合併[[:$1]]到[[:$2]]',
'mergehistory-comment' => '已經合併[[:$1]]到[[:$2]]: $3',
'mergehistory-same-destination' => '來源頁佮目標頁袂使相仝',
'mergehistory-reason' => '理由：',

# Merge log
'mergelog' => '合併記錄',
'pagemerge-logentry' => '已經共[[$1]]合併到[[$2]] （修訂本到$3）',
'revertmerge' => '取消合併',
'mergelogpagetext' => '下跤是最近共一頁的歷史合併到另一个的列表',

# Diffs
'history-title' => '改"$1"的歷史',
'difference-multipage' => '（頁中間的精差）',
'lineno' => 'Tē $1 chōa:',
'compareselectedversions' => 'Pí-phēng soán-te̍k ê pán-pún',
'showhideselectedversions' => '顯示／隱藏 選定的修訂版本',
'editundo' => 'chhú-siau',
'diff-multi' => '（由{{PLURAL:$2|个用者|$2个用者}}的{{PLURAL:$1|一个中央修訂本|$1个中央修訂本}}無顯示）',
'diff-multi-manyusers' => '（{{PLURAL:$2|个用者|$2个用者}}的{{PLURAL:$1|一个中途修訂本|$1个中途修訂本}}無顯示）',

# Search results
'searchresults' => 'Kiám-sek kiat-kó',
'searchresults-title' => 'Chhoé "$1" ê kiat-kó',
'searchresulttext' => 'Koan-hē kiám-sek {{SITENAME}} ê siông-sè pō·-sò·, chhiáⁿ chham-khó [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => '揣\'\'\'[[:$1]]\'\'\'（[[Special:Prefixindex/$1|所有以 "$1" 做頭的頁]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|所有連結到 "$1" 的頁]]）',
'searchsubtitleinvalid' => '揣"$1"',
'toomanymatches' => '揣著傷濟，請試另外一款方式',
'titlematches' => 'Phiau-tê ū-tùi ê bûn-chiuⁿ',
'notitlematches' => 'Bô sio-tùi ê ia̍h-piau-tê',
'textmatches' => 'Lōe-iông ū-tùi ê bûn-chiuⁿ',
'notextmatches' => 'Bô sio-tùi ê bûn-chiuⁿ lōe-iông',
'prevn' => 'chêng {{PLURAL:$1|$1}} hāng',
'nextn' => 'āu {{PLURAL:$1|$1}} hāng',
'prevn-title' => '前$1个{{PLURAL:$1|結果|結果}}',
'nextn-title' => '後$1个{{PLURAL:$1|結果|結果}}',
'shown-title' => 'Múi ia̍h hián-sī $1 {{PLURAL:$1|kiat-kó|kiat-kó}}',
'viewprevnext' => 'Khoàⁿ ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend' => '搜揣的選項',
'searchmenu-exists' => "'''佇這个wiki遐，有一个頁叫做「[[:$1]]」'''",
'searchmenu-new' => "'''佇這个 wiki建立「[[:$1]]」這个頁！'''",
'searchhelp-url' => 'Help:Bo̍k-lio̍k',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|看頁標題頭前相仝的頁]]',
'searchprofile-articles' => 'Loē-iông ia̍h',
'searchprofile-project' => '幫助佮事工頁',
'searchprofile-images' => 'To-mûi-thé',
'searchprofile-everything' => 'Só͘-ū ê',
'searchprofile-advanced' => 'chìn-chi̍t-pō͘',
'searchprofile-articles-tooltip' => 'Tī $1 chhoé',
'searchprofile-project-tooltip' => '揣$1內底的',
'searchprofile-images-tooltip' => 'Chhoé tóng-àn',
'searchprofile-everything-tooltip' => '搜索全部（包括討論頁）',
'searchprofile-advanced-tooltip' => '佇自定的名空間中搜揣',
'search-result-size' => '$1 ({{PLURAL:$2|1 jī-goân|$2 jī-goân}})',
'search-result-category-size' => '{{PLURAL:$1|一个成員|$1成員}} ({{PLURAL:$2|一个下類別|$2个下類別}}，{{PLURAL:$3|一个檔案|$3个檔案}})',
'search-result-score' => '相關度: $1%',
'search-redirect' => '（改向 $1）',
'search-section' => '(toān-lo̍h $1)',
'search-suggest' => '你是欲：$1',
'search-interwiki-caption' => '姊妹事工',
'search-interwiki-default' => '$1項結果:',
'search-interwiki-more' => '（閣有）',
'search-mwsuggest-enabled' => '有建議',
'search-mwsuggest-disabled' => '無建議',
'search-relatedarticle' => '相關的',
'mwsuggest-disable' => '停掉AJAX的建議',
'searcheverything-enable' => '揣所有的名空間',
'searchrelated' => '相關的',
'searchall' => 'choân-pō·',
'showingresults' => "Ē-kha tùi #'''$2''' khai-sí hián-sī {{PLURAL:$1| hāng| hāng}} kiat-kó.",
'showingresultsnum' => "Ē-kha tùi #'''$2''' khai-sí hián-sī {{PLURAL:$3| hāng| hāng}} kiat-kó.",
'showingresultsheader' => "對'''$4'''的{{PLURAL:$5|第'''$1'''到第'''$3'''項結果|第'''$1 - $2'''項，總共'''$3'''項結果}}",
'nonefound' => "'''注意'''：只有一寡名空間是預設會去揣。試''all:''去揣所有的頁（包括討論頁、枋模等等），抑是頭前指定名空間。",
'search-nonefound' => '揣無欲愛的',
'powersearch' => 'Kiám-sek',
'powersearch-legend' => 'Kiám-sek',
'powersearch-ns' => '佇下跤的名空間揣：',
'powersearch-redir' => '轉頁清單',
'powersearch-field' => '揣',
'powersearch-togglelabel' => '選定：',
'powersearch-toggleall' => '所有的',
'powersearch-togglenone' => '無',
'search-external' => '外部的搜揣',
'searchdisabled' => '{{SITENAME}}因為性能方面的原因，全文搜揣已經暫時停用。你會使暫時透過Google搜揣。請注意怹的索引可能過時。',

# Quickbar
'qbsettings' => 'Quickbar ê siat-tēng',
'qbsettings-none' => '無',
'qbsettings-fixedleft' => '倒手爿固定',
'qbsettings-fixedright' => '正手爿固定',
'qbsettings-floatingleft' => '倒手爿無固定',
'qbsettings-floatingright' => '正手爿無固定',
'qbsettings-directionality' => '固定，照你話語文字的方向。',

# Preferences page
'preferences' => 'Siat-tēng',
'mypreferences' => 'Góa ê siat-tēng',
'prefs-edits' => '編輯幾擺：',
'prefsnologin' => 'Bô teng-ji̍p',
'prefsnologintext' => 'Lí it-tēng ài <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} teng-ji̍p]</span> chiah ē-tàng chhiâu iōng-chiá ê siat-tēng.',
'changepassword' => 'Oāⁿ bi̍t-bé',
'prefs-skin' => 'Phôe',
'skin-preview' => 'Chhì khoàⁿ',
'datedefault' => 'Chhìn-chhái',
'prefs-beta' => 'Beta版功能',
'prefs-datetime' => 'Ji̍t-kî kap sî-kan',
'prefs-labs' => '試驗中的功能',
'prefs-personal' => 'Iōng-chiá chu-liāu',
'prefs-rc' => 'Chòe-kīn ê kái-piàn & stub ê hián-sī',
'prefs-watchlist' => 'Kàm-sī-toaⁿ',
'prefs-watchlist-days' => 'Kàm-sī-toaⁿ hián-sī kúi kang lāi--ê:',
'prefs-watchlist-days-max' => '上濟$1 {{PLURAL:$1|工|工}}',
'prefs-watchlist-edits' => 'Khok-chhiong ê kàm-sī-toaⁿ tio̍h hián-sī kúi hāng pian-chi̍p:',
'prefs-watchlist-edits-max' => '上大的數目：1000',
'prefs-watchlist-token' => '監視列表的密鑰：',
'prefs-misc' => 'Kî-thaⁿ ê siat-tēng',
'prefs-resetpass' => '改密碼',
'prefs-changeemail' => '改電子批的地址',
'prefs-setemail' => '設定一个電子批地址',
'prefs-email' => '電子批的選項',
'prefs-rendering' => '外觀',
'saveprefs' => 'Pó-chûn siat-tēng',
'resetprefs' => 'Têng siat-tēng',
'restoreprefs' => '全部攏恢復做設便的',
'prefs-editing' => 'Pian-chi̍p',
'prefs-edit-boxsize' => '編輯框的寸尺',
'rows' => 'Chōa:',
'columns' => 'Nôa',
'searchresultshead' => 'Chhiau-chhōe kiat-kó ê siat-tēng',
'resultsperpage' => '1 ia̍h hián-sī kúi kiāⁿ:',
'stub-threshold' => '<a href="#" class="stub">短頁連結</a>的門檻值 （位元組）:',
'stub-threshold-disabled' => '莫用',
'recentchangesdays' => 'Hián-sī kúi ji̍t chòe-kīn ê kái-piàn:',
'recentchangesdays-max' => 'siōng-choē $1 {{PLURAL:$1|kang|kang}}',
'recentchangescount' => 'Beh hián-sī kúi tiâu chòe-kīn kái--ê:',
'prefs-help-recentchangescount' => '這包括最近改的、頁的歷史佮記錄',
'prefs-help-watchlist-token' => '佇這个欄位加入一个密鑰，伊佇你訂看監視清單 RSS內底嘛會產生。
任何人若知影這个欄位的密鑰，就會當看你的監視清單，請選一个安全的數字。
遮有一个隨意產生的數字你通用：$1',
'savedprefs' => 'Lí ê iōng-chiá siat-tēng í-keng pó-chûn khí lâi ah.',
'timezonelegend' => 'Sî-khu',
'localtime' => 'Chāi-tē sî-kan sī:',
'timezoneuseserverdefault' => '使用Wiki設便的($1)',
'timezoneuseoffset' => '其他 （指定偏差量）',
'timezoneoffset' => 'Sî-chha¹:',
'servertime' => 'Server sî-kan hiān-chāi sī:',
'guesstimezone' => 'Tùi liû-lám-khì chhau--lâi',
'timezoneregion-africa' => '非洲',
'timezoneregion-america' => '美洲',
'timezoneregion-antarctica' => '南極洲',
'timezoneregion-arctic' => '北極',
'timezoneregion-asia' => '亞洲',
'timezoneregion-atlantic' => '大西洋',
'timezoneregion-australia' => '澳洲',
'timezoneregion-europe' => '歐洲',
'timezoneregion-indian' => '印度洋',
'timezoneregion-pacific' => '太平洋',
'allowemail' => 'Ún-chún pa̍t-ê iōng-chiá kià email kòe-lâi',
'prefs-searchoptions' => '搜揣的選項',
'prefs-namespaces' => '名空間',
'defaultns' => 'Tī chiah ê miâ-khong-kan chhiau-chhōe:',
'default' => '設便',
'prefs-files' => 'Tóng-àn',
'prefs-custom-css' => ' 家己設的CSS',
'prefs-custom-js' => ' 家己設的JavaScript',
'prefs-common-css-js' => '共 CSS/JavaScript 分享佇所有的外觀：',
'prefs-reset-intro' => '你會當用這頁去改做原本設便的。
這个動作無法度取消。',
'prefs-emailconfirm-label' => '電子批的確定：',
'prefs-textboxsize' => '編輯框的大細',
'youremail' => 'Lí ê email:',
'username' => '用者名稱：',
'uid' => '用者編號：',
'prefs-memberingroups' => '{{PLURAL:$1|這陣人|這陣人}}的成員：',
'prefs-registration' => '註冊時間：',
'yourrealname' => 'Lí ê chin miâ:',
'yourlanguage' => 'Kài-bīn gú-giân:',
'yourvariant' => '頁內容的語文：',
'prefs-help-variant' => '你希望這个Wiki的內容顯示的時陣所使用的語文',
'yournick' => 'Lí ê sió-miâ (chhiam-miâ iōng):',
'prefs-help-signature' => '佇討論頁的評論應該愛用「<nowiki>~~~~</nowiki>」簽名，彼會轉變做你的簽名佮戳印一个時間。',
'badsig' => '錯誤的原始簽名，
請檢查HTML標籤。',
'badsiglength' => '你的簽名傷過長，
伊的長度袂使超過{{PLURAL:$1|个|个}}字元。',
'yourgender' => '性別：',
'gender-unknown' => '無表明',
'gender-male' => '查埔',
'gender-female' => '查某',
'prefs-help-gender' => '選項：用佇軟體的性別指定，
這項資料會公開。',
'email' => '電子批',
'prefs-help-realname' => '你的真實名字無一定愛，
若你欲提供，伊會附佇你貢 獻的作品。',
'prefs-help-email' => 'Tiān-chú-phoe ê chū-chí m̄-sī it-tēng ài, m̄-koh tī lí bē-kì bi̍t-bé beh tîng siat-tīng tō ài.',
'prefs-help-email-others' => 'Lí ē-sái thàu--koè lí ê ia̍h , thó-lūn-ia̍h ê liân kiat hō͘ lâng ēng e-mail kah lí liân-lo̍k.
Tī pat-lâng liân-lo̍k lí ê sî-chūn bē kā e-mail tsū-tsí siá chhut--lâi.',
'prefs-help-email-required' => '愛有電子批地址',
'prefs-info' => '基本資料',
'prefs-i18n' => '國際化',
'prefs-signature' => '簽名',
'prefs-dateformat' => '顯示日期的規格',
'prefs-timeoffset' => '佮標準時間的偏差',
'prefs-advancedediting' => '進一步的選項',
'prefs-advancedrc' => '進一步的選項',
'prefs-advancedrendering' => '進一步的選項',
'prefs-advancedsearchoptions' => '進一步的選項',
'prefs-advancedwatchlist' => '進一步的選項',
'prefs-displayrc' => '顯示的選項',
'prefs-displaysearchoptions' => '顯示的選項',
'prefs-displaywatchlist' => '顯示的選項',
'prefs-diffs' => '精差',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => '電子批地址看起來是有效的',
'email-address-validity-invalid' => '拍一个有效的電子批地址',

# User rights
'userrights' => '用者的權限管理',
'userrights-lookup-user' => '管理用者的分組',
'userrights-user-editname' => '輸入一个用者名稱：',
'editusergroup' => '設定用者的分組',
'editinguser' => "改用者'''[[User:$1|$1]]'''$2 的使用權利",
'userrights-editusergroup' => '設定用者的分組',
'saveusergroups' => '保存用者的分組',
'userrights-groupsmember' => '成員：',
'userrights-groupsmember-auto' => '自本的成員：',
'userrights-groups-help' => '你會當改用者所屬的分組：
* 頭前有勾起來的代表用者屬的分組
* 頭前無勾起來的代表用者無屬彼个分組
* 有 * 的項目，表示你會當加，袂當共減倒轉來，抑是會當共減，袂當共加倒轉來',
'userrights-reason' => '理由：',
'userrights-no-interwiki' => '你無權去設定其它wiki上的用者權利。',
'userrights-nodatabase' => '無$1資料庫抑是非本地的',
'userrights-nologin' => '你愛管理員的口座[[Special:UserLogin|登入]]了後，才會當指定用者權利。',
'userrights-notallowed' => '你口座的無授權你會當加添用者權利',
'userrights-changeable-col' => '你會當改的分組',
'userrights-unchangeable-col' => '你袂當改的分組',

# Groups
'group' => '分組：',
'group-user' => '用者：',
'group-autoconfirmed' => '自動確認的用者',
'group-bot' => '機器人',
'group-sysop' => '管理員',
'group-bureaucrat' => '行政人員',
'group-suppress' => '監督',
'group-all' => '（全部）',

'group-user-member' => '{{GENDER:$1|用者}}',
'group-autoconfirmed-member' => '{{GENDER:$1|自動確認的用者}}',
'group-bot-member' => '{{GENDER:$1|機器人}}',
'group-sysop-member' => '{{GENDER:$1|管理員}}',
'group-bureaucrat-member' => '{{GENDER:$1|監督人員}}',
'group-suppress-member' => '{{GENDER:$1|監督}}',

'grouppage-user' => '{{ns:project}}:用者',
'grouppage-autoconfirmed' => '{{ns:project}}:自動確認的用者',
'grouppage-bot' => '{{ns:project}}:機器人',
'grouppage-sysop' => '{{ns:project}}:Hêng-chèng jîn-oân',
'grouppage-bureaucrat' => '{{ns:project}}:行政人員',
'grouppage-suppress' => '{{ns:project}}:監督',

# Rights
'right-read' => '看頁',
'right-edit' => '改頁',
'right-createpage' => '開新頁（無包括討論頁）',
'right-createtalk' => '開新討論頁',
'right-createaccount' => '開新用者口座',
'right-minoredit' => '標示做小編輯',
'right-move' => '徙頁',
'right-move-subpages' => '徙頁，連伊的次頁',
'right-move-rootuserpages' => '徙用者root的頁',
'right-movefile' => '徙檔案',
'right-suppressredirect' => '徙頁的時陣，無共原本的頁改做轉頁',
'right-upload' => '上載檔案',
'right-reupload' => '取代原本的檔案',
'right-reupload-own' => '取代別人上載的原本檔案',
'right-reupload-shared' => '莫用共用媒體檔案庫上的檔案',
'right-upload_by_url' => '對一个網址(URL)上載檔案',
'right-purge' => '直接清掉網站頁的cache，毋免閣確定',
'right-autoconfirmed' => '編輯半保護的頁',
'right-bot' => '看做是一个自動程序',
'right-nominornewtalk' => '佇討論頁的小編輯無發新訊息',
'right-apihighlimits' => '佇API查詢的時陣，用較懸的限制量',
'right-writeapi' => '用API編寫',
'right-delete' => '刣頁',
'right-bigdelete' => '刣掉頁的誠濟歷史',
'right-deleterevision' => '刣掉佮取消刣掉頁的指定修訂本',
'right-deletedhistory' => '看已經刣掉的歷史項目，無包括相關的文本',
'right-deletedtext' => '看已經刣掉修訂本當中，刣掉的文字佮變化',
'right-browsearchive' => '揣刣掉的頁',
'right-undelete' => '共刣掉的頁救倒轉來',
'right-suppressrevision' => '恢復由管理員隱藏掉的修訂本',
'right-suppressionlog' => '看私人的記錄',
'right-block' => '封鎖其他用者，予怹袂當編輯',
'right-blockemail' => '封鎖一个用者，予伊袂當寄電子批',
'right-hideuser' => '封鎖一个用者名稱，無對大眾公開',
'right-ipblock-exempt' => '跳過IP封鎖、自動封鎖佮範圍封鎖',
'right-proxyunbannable' => '跳過Proxy的自動封鎖',
'right-unblockself' => '取消怹的封鎖',
'right-protect' => '改保護層級而且編輯hông保護的頁',
'right-editprotected' => '編輯保護中的頁（無連鎖保護）',
'right-editinterface' => '編輯用者介面',
'right-editusercssjs' => '編輯其他用者的CSS佮JavaScript檔案',
'right-editusercss' => '編輯其他用者的CSS檔案',
'right-edituserjs' => '編輯其他用者的JavaScript檔案',
'right-rollback' => '共某一頁的頂一个用戶所做的編輯鉸轉去',
'right-markbotedits' => '共復原編輯標示做機械人編輯',
'right-noratelimit' => '無受著頻率限制的影響',
'right-import' => '對別个Wiki匯入頁',
'right-importupload' => '對一个上載檔案匯入頁',
'right-patrol' => '共其它的編輯攏標示做已巡過',
'right-autopatrol' => '家己的編輯自動標示做巡過',
'right-patrolmarks' => '看最近巡查編輯的標記',
'right-unwatchedpages' => '看頁無人監視的清單',
'right-mergehistory' => '相佮一寡頁的歷史',
'right-userrights' => '編輯所有用者的權利限制',
'right-userrights-interwiki' => '編輯對其它wiki來的用者權限',
'right-siteadmin' => '封鎖閣開鎖資料庫',
'right-override-export-depth' => '輸出頁，包括連到的頁到5層深',
'right-sendemail' => '寄電子批予其他用者',
'right-passwordreset' => '看重設密碼的電子批',

# User rights log
'rightslog' => '用者使用權記錄',
'rightslogtext' => 'Chit-ê log lia̍t-chhut kái-piàn iōng-chiá koân-lī ê tōng-chok.',
'rightslogentry' => '共 $1 的權利限制對 $2 改做 $3',
'rightslogentry-autopromote' => '自動對$2提升至$3',
'rightsnone' => '（無）',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => '看這頁',
'action-edit' => 'Siu-kái chit ia̍h',
'action-createpage' => '開新頁',
'action-createtalk' => '開討論頁',
'action-createaccount' => '開這个用者口座',
'action-minoredit' => '標示做小編輯',
'action-move' => '徙這頁',
'action-move-subpages' => '徙這頁，佮伊的次頁',
'action-move-rootuserpages' => '徙用者root的頁',
'action-movefile' => '徙這个檔案',
'action-upload' => '上載這个檔案',
'action-reupload' => '取代原本的檔案',
'action-reupload-shared' => '莫用共用媒體檔案庫面頂的檔案',
'action-upload_by_url' => '對一个網址(URL)上載這个檔案',
'action-writeapi' => '使用API編寫',
'action-delete' => '刣掉這頁',
'action-deleterevision' => '刣掉這个修訂本',
'action-deletedhistory' => '看這頁予人刣掉的歷史',
'action-browsearchive' => '揣刣掉的頁',
'action-undelete' => '共刣掉的頁救倒轉來',
'action-suppressrevision' => '看而且取消這个藏起來的修訂本',
'action-suppressionlog' => '看這个私人記錄',
'action-block' => '封鎖這个用者，予伊袂當編輯',
'action-protect' => '改這頁的保護層級',
'action-rollback' => '共某一頁的頂一个用戶所做的編輯鉸轉去',
'action-import' => '對別个Wiki匯入這頁',
'action-importupload' => '對一个上載檔案匯入這頁',
'action-patrol' => '標示其它的編輯是巡過的',
'action-autopatrol' => '你的編輯標示做已巡查過',
'action-unwatchedpages' => '看無予人監視的頁列單',
'action-mergehistory' => '相佮這頁的歷史',
'action-userrights' => '編輯所有用者的權限',
'action-userrights-interwiki' => '編輯對其它wiki來的用者權限',
'action-siteadmin' => '封鎖抑開鎖資料庫',
'action-sendemail' => '寄電子批',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|kái|kái}}',
'recentchanges' => 'Chòe-kīn ê kái-piàn',
'recentchanges-legend' => '最近編輯的選項',
'recentchangestext' => '佇這頁，看阮這个Wiki最近改的',
'recentchanges-feed-description' => '訂看這个Wiki最近改的',
'recentchanges-label-newpage' => 'Chit ê siu-kái ē sán-seng sin ia̍h',
'recentchanges-label-minor' => 'Che sī sió siu-kái',
'recentchanges-label-bot' => '這个編輯是機器人做的',
'recentchanges-label-unpatrolled' => '這个編輯猶未巡過',
'rcnote' => "下面是佇$4 $5，最近{{PLURAL:$2|工|'''$2'''工}}內的{{PLURAL:$1|'''1'''改|頂'''$1'''改}}修改記錄。",
'rcnotefrom' => 'Ē-kha sī <b>$2</b> kàu taⁿ ê kái-piàn (ke̍k-ke hián-sī <b>$1</b> hāng).',
'rclistfrom' => 'Hián-sī tùi $1 kàu taⁿ ê sin kái-piàn',
'rcshowhideminor' => '$1 sió siu-kái',
'rcshowhidebots' => '$1機器人所做的',
'rcshowhideliu' => '$1 teng-ji̍p ê iōng-chiá',
'rcshowhideanons' => '$1 bû-bêng-sī',
'rcshowhidepatr' => '$1巡過的編輯',
'rcshowhidemine' => '$1 góa ê pian-chi̍p',
'rclinks' => 'Hían-sī $2 ji̍t lāi siōng sin ê $1 hāng kái-piàn<br />$3',
'diff' => 'Cheng-chha',
'hist' => 'ls',
'hide' => 'am',
'show' => 'hían-sī',
'minoreditletter' => '~',
'newpageletter' => '!',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[$1个愛注意的{{PLURAL:$1|用者|用者}}]',
'rc_categories' => '分類界線（以"|"分開）',
'rc_categories_any' => '任何',
'rc-change-size-new' => '改了後有$1 {{PLURAL:$1|字元|字元}} 。',
'newsectionsummary' => '/* $1 */ 新段落',
'rc-enhanced-expand' => '看內容（愛有JavaScript）',
'rc-enhanced-hide' => '藏內容',
'rc-old-title' => '原本用"$1"開頁',

# Recent changes linked
'recentchangeslinked' => 'Siong-koan ê kái-piàn',
'recentchangeslinked-feed' => 'Siong-koan ê kái-piàn',
'recentchangeslinked-toolbox' => 'Siong-koan ê kái-piàn',
'recentchangeslinked-title' => '佮「$1」有關係的修改',
'recentchangeslinked-noresult' => 'Lí chí-tēng ê tiâu-kiaⁿ lāi-té chhōe bô jīn-hô kái-piàn.',
'recentchangeslinked-summary' => "這是佮指定的頁面有連結、閣最近有改過的別頁清單（抑是指定分類的成員）。
佇[[Special:Watchlist|你的監視單]]內底的頁會用'''粗體'''顯示。",
'recentchangeslinked-page' => 'Ia̍h ê miâ:',
'recentchangeslinked-to' => '顯示另外拍入頁伊的相關修改',

# Upload
'upload' => 'Kā tóng-àn chiūⁿ-bāng',
'uploadbtn' => 'Kā tóng-àn chiūⁿ-bāng',
'reuploaddesc' => 'Tò khì sàng-chiūⁿ-bāng ê pió.',
'upload-tryagain' => '送出改過了後的檔案描述',
'uploadnologin' => 'Bô teng-ji̍p',
'uploadnologintext' => 'Bô [[Special:UserLogin|teng-ji̍p]] bē-sái-tit kā tóng-àn sàng-chiūⁿ-bāng.',
'upload_directory_missing' => '無上傳的目錄（$1），彼袂當由網頁伺服器建立。',
'upload_directory_read_only' => '無上載目錄（$1），抑是網頁伺服器無權寫入',
'uploaderror' => 'Upload chhò-gō·',
'upload-recreate-warning' => "'''注意：一个仝名的檔案捌hông刣掉抑是徙去別位。'''

這頁有刣掉佮徙走的記錄通參考：",
'uploadtext' => "用下跤的表來共檔案上載。
若欲看，抑是揣往過上載的檔案，會使入去[[Special:FileList|檔案上載清單]]。上載嘛會記錄佇[[Special:Log/upload|上載記錄]]，若刣掉就會記錄佇[[Special:Log/delete|刣掉記錄]]。

上載後，若欲佇頁加入檔案，會使用下跤的一種方式來連結：
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>'''使用檔案的完整版本
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|替換文字]]</nowiki></tt>'''用一个囥佇倒爿的一个200 像素圖相框，「替換文字」做說明
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>'''直接連結到檔案，毋過無顯示檔案",
'upload-permitted' => '通用的檔案類型: $1',
'upload-preferred' => '建議的檔案類型: $1',
'upload-prohibited' => '禁止的檔案類型: $1。',
'uploadlog' => '上載記錄',
'uploadlogpage' => '上載記錄',
'uploadlogpagetext' => 'Í-hā sī chòe-kīn sàng-chiūⁿ-bāng ê tóng-àn ê lia̍t-toaⁿ.',
'filename' => 'Tóng-àn',
'filedesc' => 'Khài-iàu',
'fileuploadsummary' => 'Khài-iàu:',
'filereuploadsummary' => '改換檔案的說明:',
'filestatus' => '版權狀況:',
'filesource' => '來源：',
'uploadedfiles' => 'Tóng-àn í-keng sàng chiūⁿ-bāng',
'ignorewarning' => 'Mài chhap kéng-kò, kā tóng-àn pó-chûn khí lâi.',
'ignorewarnings' => 'Mài chhap kéng-kò',
'minlength1' => '檔案的名上少愛有一字',
'illegalfilename' => '檔案名“$1”有袂用得用佇標題的字，
請改名了後重新上載。',
'filename-toolong' => '檔案的名長度袂使超過240位元組',
'badfilename' => 'Iáⁿ-siōng ê miâ í-keng kái chò "$1".',
'filetype-mime-mismatch' => '副檔名 ".$1" 佮 ($2)的MIME類型無合。',
'filetype-badmime' => 'MIME類別"$1"的檔案袂當上載',
'filetype-bad-ie-mime' => '袂當上載這个檔案，因為 Internet Explorer 會共伊偵測做 "$1"，彼種袂使，可能是有所危害的檔案類型。',
'filetype-unwanted-type' => "'''\".\$1\"'''是袂當上載的檔案類型，
適當的{{PLURAL:\$3|檔案類型|檔案類型}}是\$2。",
'filetype-banned-type' => "	'''「.$1」'''{{PLURAL:$4|毋是會用得的檔案類型|毋是會用得的檔案類型}}。 
會用得的{{PLURAL:$3|檔案類型|檔案類型}} $2。",
'filetype-missing' => '彼个檔案名稱無副檔名 （親像 ".jpg"）。',
'empty-file' => '你送出來的檔案是空的',
'file-too-large' => '你送出來的檔案傷過大',
'filename-tooshort' => '檔案名傷短',
'filetype-banned' => '這類的檔案被禁止',
'verification-error' => '這个檔案無通過驗證',
'hookaborted' => '你欲做的編輯因為擴展鈎(extension hook)去跳開。',
'illegal-filename' => '無合用的檔案名稱',
'overwrite' => '袂使覆寫已經佇咧的檔案',
'unknown-error' => '有一个無啥清楚的錯誤。',
'tmp-create-error' => '無法度建立臨時檔案',
'tmp-write-error' => '寫入臨時檔案的時陣發生錯誤',
'large-file' => '建議檔案的大小袂當超過 $1，本檔案大小是 $2。',
'largefileserver' => '這个檔案比伺服器配置所允許的較大。',
'emptyfile' => '你欲上載的檔案敢若是空的，
這有可能是拍毋著檔案名稱，
請檢查你確定是欲上載這个檔案。',
'windows-nonascii-filename' => '本維基的檔案名稱袂當有特殊的字',
'fileexists' => "已經有一个仝名的檔案，你若無確定你欲要共改，請檢查'''<tt>[[:$1]]</tt>'''。 [[$1|thumb]]",
'filepageexists' => "這个檔案的描述頁已經佇'''<tt>[[:$1]]</tt>'''建立，毋過這个名稱的檔案猶未有，
你所輸入的概要袂顯示佇彼个描述頁當中，若欲概要佇遐看會著，你愛手動編輯。
[[$1|thumb]]",
'fileexists-extension' => "一个親像檔名的檔案已經佇咧: [[$2|thumb]]
* 上載檔案的檔名: '''<tt>[[:$1]]</tt>'''
* 這馬檔案的檔名: '''<tt>[[:$2]]</tt>'''
請選一个無仝的名。",
'fileexists-thumbnail-yes' => "這个檔案若親像是一幅圖的縮小版本''（縮圖）''。 [[$1|thumb]]
請檢查檔案'''<tt>[[:$1]]</tt>'''，
若檢查的檔案是仝幅圖的縮圖，就毋免閣上載一幅縮圖。",
'file-thumbnail-no' => "以'''<tt>$1</tt>'''做名的檔案，
伊敢若是某幅圖的縮小版本''（縮圖）''。
你欲就上載完整大小的版本，若無請改檔案名稱。",
'fileexists-forbidden' => '已經有一个仝名的檔案，而且袂檔覆寫，
若你欲上載你的檔案，請退倒轉去，閣用一个新名來。
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => '已經有一个仝名的檔案佇分享檔案庫，
若你欲上載你的檔案，請退倒轉去，閣用一个新名來。
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => '這个檔案佮下跤的{{PLURAL:$1|个檔案|个檔案}}是仝款的：',
'file-deleted-duplicate' => '一个仝名的檔案 （[[:$1]]） 佇進前捌予人刣掉，
你應當佇欲閣重新上載進前，先檢查彼个檔案的刣掉記錄。',
'uploadwarning' => 'Upload kéng-kò',
'uploadwarning-text' => '請改下跤的檔案描述才閣試',
'savefile' => 'Pó-chûn tóng-àn',
'uploadedimage' => 'thoân "[[$1]]" chiūⁿ-bāng',
'overwroteimage' => '已經上載「[[$1]]」的新版本',
'uploaddisabled' => 'Pháiⁿ-sè, sàng chiūⁿ-bāng ê kong-lêng bô khui.',
'copyuploaddisabled' => '袂當透過網址上載',
'uploadfromurl-queued' => '你的上載已經咧排隊',
'uploaddisabledtext' => '袂當上載檔案',
'php-uploaddisabledtext' => '佇PHP袂當上載檔案，
請檢查file_uploads 設定。',
'uploadscripted' => '這个檔案內底有HTML抑是腳本代碼，網路瀏覽器可能會錯誤翻譯。',
'uploadvirus' => '彼个檔案有一个病毒！
細情：$1',
'uploadjava' => '彼个檔案是有 Java  .class 的 ZIP 檔案，
袂當上載 Java 檔案，是因為怹可能會閃過系統安全關卡。',
'upload-source' => '來源檔案',
'sourcefilename' => 'Tóng-àn goân miâ:',
'sourceurl' => '來源網址(URL)：',
'destfilename' => 'Tóng-àn sin miâ:',
'upload-maxfilesize' => '檔案上大：$1',
'upload-description' => '檔案說明',
'upload-options' => '上載選項',
'watchthisupload' => 'Kàm-sī chit ê tóng-àn',
'filewasdeleted' => '進前有上載一个仝名的檔案，而且後來予人刣掉，
佇欲閣上載進前，你應該先檢查$1。',
'filename-bad-prefix' => "你上載的檔案名是以'''「$1」'''做頭，這一般是數位相機自動編的，彼無啥意義，
請替你的檔案號一个較有意義的名。",
'upload-success-subj' => 'Sàng-chiūⁿ-bāng sêng-kong',
'upload-success-msg' => '你對[$2]遐的上載已經成功，伊佇：[[:{{ns:file}}:$1]]',
'upload-failure-subj' => '上載問題',
'upload-failure-msg' => '你[$2]的上載出現問題：

$1',
'upload-warning-subj' => '上載警示',
'upload-warning-msg' => '你對[$2]遐的上載出問題，你會當回轉去[[Special:Upload/stash/$1|上載表]]修改問題。',

'upload-proto-error' => '毋著的協議(protocol)',
'upload-proto-error-text' => '遠程上載愛網址(URL)是以 <code>http://</code> 抑 <code>ftp://</code> 做頭。',
'upload-file-error' => '內部的錯誤',
'upload-file-error-text' => '佇伺服器欲開一个臨時檔案的時陣，發生一个內部錯誤，
請佮[[Special:ListUsers/sysop|管理員]]聯絡。',
'upload-misc-error' => '毋知原因的上載錯誤',
'upload-misc-error-text' => '佇上載的時陣發生錯誤，毋知啥原因。
請確認網址(URL)是正確的，了才閣試。
若猶閣有問題，請聯絡[[Special:ListUsers/sysop|管理員]]。',
'upload-too-many-redirects' => '網址(URL)包傷濟个轉向',
'upload-unknown-size' => '大小毋知',
'upload-http-error' => '發生一个HTTP錯誤：$1',
'upload-copy-upload-invalid-domain' => '無開放對這个網站(domain)上載檔案。',

# File backend
'backend-fail-stream' => '無法度串流檔案$1',
'backend-fail-backup' => '無法度備份檔案$1',
'backend-fail-notexists' => '無$1這个檔案',
'backend-fail-hashes' => '無法度讀著檔案散列值(hashe)通比並',
'backend-fail-notsame' => '已經有$1仝名、無仝款的檔案。',
'backend-fail-invalidpath' => '$1這个囥的路徑怪怪',
'backend-fail-delete' => 'Bô-hoat-tō· kā tóng-àn "$1" thâi tiāu',
'backend-fail-alreadyexists' => '已經有$1這个檔案。',
'backend-fail-store' => '無法度恢復佇$2的檔案$1。',
'backend-fail-copy' => '無法度共佇$1的檔案khop去$2。',
'backend-fail-move' => '無法度共佇$1的檔案徙去$2。',
'backend-fail-opentemp' => '無法度建立臨時檔案',
'backend-fail-writetemp' => '無法度寫入去臨時檔案',
'backend-fail-closetemp' => '無法度徙掉臨時檔案',
'backend-fail-read' => '無法度讀$1這个檔案',
'backend-fail-create' => '無法度建立$1這个檔案。',
'backend-fail-maxsize' => '無法度建立$1檔案，因為伊超過{{PLURAL:$2|$2位元|$2位元}}。',
'backend-fail-readonly' => '囥「$1」的位，這馬只會當讀，因為「$2」。',
'backend-fail-synced' => '"$1"這个檔案佇內部的囥位無一致。',
'backend-fail-connect' => '無法度連接到囥"$1"的位。',
'backend-fail-internal' => '囥"$1"的位有一寡問題。',
'backend-fail-contenttype' => '無法度確定欲囥佇"$1"的檔案內容類型。',
'backend-fail-batchsize' => '囥位一批$1个檔案
{{PLURAL:$1|遍動作|遍動作}}，上濟$2遍{{PLURAL:$2|動作|動作}}。',

# File journal errors
'filejournal-fail-dbconnect' => '無法度連接到佇囥位"$1"的資料庫。',
'filejournal-fail-dbquery' => '無法度更新佇囥位"$1"的資料庫。',

# Lock manager
'lockmanager-notlocked' => '無法度開鎖"$1"，伊無予人封鎖牢咧。',
'lockmanager-fail-closelock' => '無法度共卡牢咧的檔案 "$1"收起來。',
'lockmanager-fail-deletelock' => '無法度共卡牢咧的檔案 "$1"刣掉。',
'lockmanager-fail-acquirelock' => '無法度套牢檔案 "$1"。',
'lockmanager-fail-openlock' => '無法度開"$1"這个hông套牢的檔案。',
'lockmanager-fail-releaselock' => '無法度解套 "$1"。',
'lockmanager-fail-db-bucket' => '佇$1資料桶，提無夠愛套牢的資料。',
'lockmanager-fail-db-release' => '無法度共佇伺服器$1的套牢釋放掉。',
'lockmanager-fail-svr-release' => '無法度共佇伺服器$1的套牢釋放掉。',

# ZipDirectoryReader
'zip-file-open-error' => '佇拍開檔案的ZIP檢查時陣，拄著一个問題。',
'zip-wrong-format' => '指定的檔案毋是一个ZIP檔案。',
'zip-bad' => '檔案已經歹去抑是無法度讀的ZIP檔案，
伊無法正確來檢查，看有妥當無。',
'zip-unsupported' => '這个是一个 ZIP 檔案，伊用著 MediaWiki 無支持的ZIP功能，
伊袂當正確檢查看有妥當無。',

# Special:UploadStash
'uploadstash' => '上載囥位',
'uploadstash-summary' => '這个頁面提供的檔案已經上載（抑是當咧上載），毋過猶未佇wiki發布，遮的檔案除了上載的用者以外，別人看袂著。',
'uploadstash-clear' => '清掉囥咧的檔案',
'uploadstash-nofiles' => '你無囥咧的檔案。',
'uploadstash-badtoken' => '彼个動作做無成功，可能是你的編輯資料已經過期，請閣試一擺。',
'uploadstash-errclear' => '欲清掉檔案無成功。',
'uploadstash-refresh' => '更新檔案清單。',
'invalid-chunk-offset' => '無效的區位偏移量',

# img_auth script messages
'img-auth-accessdenied' => '拒絕讀寫',
'img-auth-nopathinfo' => '欠PATH_INFO，
你的伺服器無設講免這个資料，
伊它可能是因為是CGI的，而且不支源img_auth，
會使參考[https://www.mediawiki.org/wiki/Manual:Image_Authorization 圖片認證。]',
'img-auth-notindir' => '你欲用的路徑無佇事先設定的上載目錄當中。',
'img-auth-badtitle' => '無法度對"$1"產生一个有效的標題',
'img-auth-nologinnWL' => '你猶未登入，"$1"無佇白名單(whitelist)面頂。',
'img-auth-nofile' => '無"$1"這个檔案',
'img-auth-isdir' => '你想欲讀目錄"$1"，
毋過只會當讀檔案。',
'img-auth-streaming' => '當咧串流(streaming)"$1"',
'img-auth-public' => 'img_auth.php的功能是予私用wiki通輸出檔案，
這個wiki的設定是一个公共wiki，
為著安全因素，img_auth.php已經停用。',
'img-auth-noread' => '用者無授權去讀"$1"',
'img-auth-bad-query-string' => '網址(URL)有無效的查詢字串',

# HTTP errors
'http-invalid-url' => '無效的網址(URL)：$1',
'http-invalid-scheme' => '無支援有「$1」的網址(URL)',
'http-request-error' => 'HTTP請求失敗，毋知啥物原因的錯誤。',
'http-read-error' => 'HTTP讀了錯誤',
'http-timed-out' => 'HTTP請求已經超過時間',
'http-curl-error' => '取網址(URL)的時陣有錯誤：$1',
'http-host-unreachable' => '連袂到網址(URL)',
'http-bad-status' => '欲做HTTP的時陣出現問題：$1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => '連袂到網址(URL)',
'upload-curl-error6-text' => '提供的網址(URL)無法連結，
請確定網址是正確的而且網站有開。',
'upload-curl-error28' => '上載已經超過時間',
'upload-curl-error28-text' => '網站的回應傷久，
請確定彼个網站有開，抑小等一下才閣試，
你會使佇較閒的時陣才試。',

'license' => 'Siū-khoân:',
'license-header' => 'Siū-khoân',
'nolicense' => '無選半項',
'license-nopreview' => '（袂當先看覓）',
'upload_source_url' => ' （一个有效閣開放予大眾的網址(URL)）',
'upload_source_file' => '（佇你電腦的一个檔案）',

# Special:ListFiles
'listfiles-summary' => '這个特殊頁顯示所有上載的檔案，
若有過濾用者，只有彼个用者上載閣上新的版本才顯示。',
'listfiles_search_for' => '照檔案名稱揣：',
'imgfile' => '檔案',
'listfiles' => 'Iáⁿ-siōng lia̍t-toaⁿ',
'listfiles_thumb' => '小圖',
'listfiles_date' => 'Ji̍t-kî',
'listfiles_name' => 'Miâ',
'listfiles_user' => 'Iōng-chiá',
'listfiles_size' => 'Toā-sè',
'listfiles_description' => 'Soat-bêng',
'listfiles_count' => '版本',

# File description page
'file-anchor-link' => 'Tóng-àn',
'filehist' => 'Tóng-àn ê le̍k-sú',
'filehist-help' => '揤日期／時間就通看彼時陣的檔案',
'filehist-deleteall' => '全部刣掉',
'filehist-deleteone' => '刣掉',
'filehist-revert' => '回轉',
'filehist-current' => 'hiān-chāi',
'filehist-datetime' => 'Ji̍t-kî/ Sî-kan',
'filehist-thumb' => '小圖',
'filehist-thumbtext' => '細張圖佇$1的版本',
'filehist-nothumb' => '無小圖',
'filehist-user' => 'Iōng-chiá',
'filehist-dimensions' => '長闊',
'filehist-filesize' => '檔案大細',
'filehist-comment' => '註釋',
'filehist-missing' => '檔案無看',
'imagelinks' => 'Ēng tio̍h ê  tóng-àn',
'linkstoimage' => 'Ē-bīn ê {{PLURAL:$1|ia̍h liân kàu|$1 ia̍h liân kàu}}  chit ê tóng-àn:',
'linkstoimage-more' => '超過$1{{PLURAL:$1|頁連接|頁連接}}到這个檔案，
下跤只是連接到這个檔案的{{PLURAL:$1|頭頁連結|頭$1頁連結}}清單，
有一个[[Special:WhatLinksHere/$2|全部的清單]]。',
'nolinkstoimage' => 'Bô poàⁿ ia̍h liân kàu chit tiuⁿ iáⁿ-siōng.',
'morelinkstoimage' => '看連接到這个檔案的[[Special:WhatLinksHere/$1|其他連結]]',
'linkstoimage-redirect' => '$1 （檔案轉向） $2',
'duplicatesoffile' => '下跤{{PLURAL:$1|个|个}}檔案佮這个仝款（[[Special:FileDuplicateSearch/$2|詳細]]）：',
'sharedupload' => '這个檔案是對$1遐來的，伊可能用佇別个事工。',
'sharedupload-desc-there' => '這个檔案對$1遐來的，伊可能用佇別个事工，
請看[$2 檔案說明]以了解進一步訊息。',
'sharedupload-desc-here' => '這个檔案是對$1遐來的，伊可能嘛用佇別的事工，
伊[$2 檔案說明頁]的說明佇下跤。',
'sharedupload-desc-edit' => '這个檔案是對$1遐來的，嘛可能用佇別个事工，
你可能想欲改伊[$2說明頁]的說明。',
'sharedupload-desc-create' => '這个檔案是對$1遐來的，嘛可能用佇別个事工，
你會當改伊的[$2說明]。',
'filepage-nofile' => '無這个名的檔案',
'filepage-nofile-link' => '無這个名的檔案，你會使 [$1上載]。',
'uploadnewversion-linktext' => '上載這个檔案的新版本',
'shared-repo-from' => '來自 $1',
'shared-repo' => '一個共享的檔案庫',

# File reversion
'filerevert' => '回轉$1',
'filerevert-legend' => '回轉檔案',
'filerevert-intro' => "你當咧回轉檔案'''[[Media:$1|$1]]'''到[$4佇$2 $3的版本]。",
'filerevert-comment' => '理由：',
'filerevert-defaultcomment' => '已經回轉到$1 $2的版本',
'filerevert-submit' => '回轉',
'filerevert-success' => "'''[[Media:$1|$1]]'''已經回轉到[$4 佇$2 $3的版本]。",
'filerevert-badversion' => '這个檔案所提供的時間截記，無進前的本地版本。',

# File deletion
'filedelete' => '刣掉$1',
'filedelete-legend' => '刣掉檔案',
'filedelete-intro' => "你當咧刣掉檔案'''[[Media:$1|$1]]'''，佮伊的歷史。",
'filedelete-intro-old' => "你當咧刣掉'''[[Media:$1|$1]]'''佇[$4 $2 $3]的版本",
'filedelete-comment' => '理由：',
'filedelete-submit' => '刣掉',
'filedelete-success' => "'''$1'''已經刣掉",
'filedelete-success-old' => "'''[[Media:$1|$1]]'''佇$2 $3 的版本已經刣掉",
'filedelete-nofile' => "無'''$1'''這个",
'filedelete-nofile-old' => "揣無'''$1'''指定的保存版本",
'filedelete-otherreason' => '其他／另外的理由：',
'filedelete-reason-otherlist' => '其他理由',
'filedelete-reason-dropdown' => '*一般刣掉的理由
** 違反著作權
** 相仝',
'filedelete-edit-reasonlist' => '編輯刣掉的理由',
'filedelete-maintenance' => '佇維護的時陣，暫時袂當刣掉檔案佮救倒轉來檔案。',
'filedelete-maintenance-title' => '袂當刣掉檔案',

# MIME search
'mimesearch' => 'MIME chhiau-chhoē',
'mimesearch-summary' => '這个頁面有用MIME類型的檔案過濾器，
輸入︰內容類型/次類型，親像 <tt>image/jpeg</tt>。',
'mimetype' => 'MIME 類型：',
'download' => '下載',

# Unwatched pages
'unwatchedpages' => 'Bô lâng kàm-sī ê ia̍h',

# List redirects
'listredirects' => 'Lia̍t-chhut choán-ia̍h',

# Unused templates
'unusedtemplates' => 'Bô iōng ê pang-bô·',
'unusedtemplatestext' => '這个頁面排列出佇{{ns:template}}名空間內底，閣無予別頁面用著的頁。
請會記得佇刣掉遮的枋模進前，看有別的連接鏈連著。',
'unusedtemplateswlh' => '其他的連結',

# Random page
'randompage' => 'Sûi-chāi kéng ia̍h',
'randompage-nopages' => '下面無頁
{{PLURAL:$2|名空間|名空間}}：$1.',

# Random redirect
'randomredirect' => 'Sûi-chāi choán-ia̍h',
'randomredirect-nopages' => '佇 "$1" 名空間內底無轉向的頁。',

# Statistics
'statistics' => 'Thóng-kè',
'statistics-header-pages' => '頁的統計',
'statistics-header-edits' => '改的統計',
'statistics-header-views' => '看的統計',
'statistics-header-users' => 'Iōng-chiá thóng-kè sò·-ba̍k',
'statistics-header-hooks' => '其他的統計',
'statistics-articles' => '內容頁',
'statistics-pages' => '文章',
'statistics-pages-desc' => '佇Wiki所有的頁，包括討論頁、轉頁等等。',
'statistics-files' => '上載檔案',
'statistics-edits' => '自設立{{SITENAME}}以後，對頁的編輯總數',
'statistics-edits-average' => '每頁的平均編輯數量',
'statistics-views-total' => '看的總量',
'statistics-views-total-desc' => '看空頁抑是特殊頁的數量無算在內。',
'statistics-views-peredit' => '佇編輯的時陣看的數量',
'statistics-users' => '已經註冊[[Special:ListUsers|用者]]',
'statistics-users-active' => '猶咧出工的用者',
'statistics-users-active-desc' => '佇前{{PLURAL:$1|一工|$1工}}有操作過的用者。',
'statistics-mostpopular' => '上濟人看的頁',

'disambiguations' => 'Khu-pia̍t-ia̍h',
'disambiguationspage' => 'Template:disambig
Template:KhPI
Template:Khu-pia̍t-iah
Template:Khu-pia̍t-ia̍h',
'disambiguations-text' => "下面的頁攏有連接到'''區別頁'''，
In應該連接到適當的頁面。<br />一个頁面若有用[[MediaWiki:Disambiguationspage]]內底的枋模，就會算做是區別頁。",

'doubleredirects' => 'Siang-thâu choán-ia̍h',
'doubleredirectstext' => '這个頁排列出所有轉向去到捌个的轉頁，
每一列有轉向去第一个佮第二个轉頁的連結，佮第二个轉頁的目標，彼个目標一般著是應該的頁面， 第一个轉向連結應該去的所在。
<del>拍叉的</del>是已經處理好的項目。',
'double-redirect-fixed-move' => '[[$1]]已經徙位，
伊這馬轉去[[$2]]。',
'double-redirect-fixed-maintenance' => '修改對[[$1]]到[[$2]]的兩擺轉向。',
'double-redirect-fixer' => '轉向的改向',

'brokenredirects' => 'Choán-ia̍h kò·-chiòng',
'brokenredirectstext' => 'Í-hā ê choán-ia̍h liân kàu bô chûn-chāi ê ia̍h:',
'brokenredirects-edit' => '修改',
'brokenredirects-delete' => '刣掉',

'withoutinterwiki' => 'Bô gí-giân liân-kiat ê ia̍h',
'withoutinterwiki-summary' => 'Ē-kha ê ia̍h bô kî-thaⁿ gí-giân pán-pún ê liân-kiat:',
'withoutinterwiki-legend' => '前綴',
'withoutinterwiki-submit' => '顯示',

'fewestrevisions' => 'Siōng bô siu-tēng ê bûn-chiuⁿ',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|jī-goân|jī-goân}}',
'ncategories' => '$1 {{PLURAL:$1|ê lūi-pia̍t |ê lūi-pia̍t}}',
'nlinks' => '$1 {{PLURAL:$1|ê|ê}} liân-kiat',
'nmembers' => '$1 {{PLURAL:$1|成員|成員}}',
'nrevisions' => '$1 {{PLURAL:$1|ê|ê}} siu-tēng-pún',
'nviews' => '看$1{{PLURAL:$1|擺|擺}}',
'nimagelinks' => '用佇$1 {{PLURAL:$1|篇文章|篇文章}}',
'ntransclusions' => '用佇$1 {{PLURAL:$1|篇文章|篇文章}}',
'specialpage-empty' => '這个報表空空。',
'lonelypages' => 'Ko·-ia̍h',
'lonelypagestext' => '下跤的頁面無予佇{{SITENAME}}的其它頁面連結抑是用著。',
'uncategorizedpages' => 'Bô lūi-pia̍t ê ia̍h',
'uncategorizedcategories' => 'Bô lūi-pia̍t ê lūi-pia̍t',
'uncategorizedimages' => 'Bô lūi-pia̍t ê iáⁿ-siōng',
'uncategorizedtemplates' => 'Bô lūi-pia̍t ê pang-bô͘',
'unusedcategories' => 'Bô iōng ê lūi-pia̍t',
'unusedimages' => 'Bô iōng ê iáⁿ-siōng',
'popularpages' => 'Sî-kiâⁿ ê ia̍h',
'wantedcategories' => 'wantedcategories',
'wantedpages' => 'Beh ti̍h ê ia̍h',
'wantedpages-badtitle' => '佇清單內底的怪怪標題：$1',
'wantedfiles' => '欲挃的檔案',
'wantedfiletext-cat' => '下跤是無彼个檔案，毋過有頁面用著。有个佇外部檔案庫嘛可能寫佇清單，彼種失誤應該<del>排除</del>。另外，頁面包的檔案若無，嘛會寫佇[[:$1]]清單。',
'wantedfiletext-nocat' => '下跤的是有頁面用著，毋過無彼个檔案。有个佇外部檔案庫嘛可能寫出來，彼種失誤應該<del>排除</del>。',
'wantedtemplates' => '欲挃的枋模',
'mostlinked' => 'Siōng chia̍p liân-kiat ê ia̍h',
'mostlinkedcategories' => 'Siōng chia̍p liân-kiat ê lūi-pia̍t',
'mostlinkedtemplates' => 'Siōng chia̍p liân-kiat ê pang-bô͘',
'mostcategories' => 'Siōng chē lūi-pia̍t ê ia̍h',
'mostimages' => 'Siōng chia̍p liân-kiat ê iáⁿ-siōng',
'mostrevisions' => 'Siōng chia̍p siu-kái ê ia̍h',
'prefixindex' => 'Só͘-ū chiàu sû-thâu sek-ín liáu ê  ia̍h',
'prefixindex-namespace' => '照頭排的所有頁面（$1名空間）',
'shortpages' => 'Té-ia̍h',
'longpages' => '長頁',
'deadendpages' => 'Khu̍t-thâu-ia̍h',
'deadendpagestext' => 'Ē-kha ê ia̍h bô liân kàu wiki lāi-té ê kî-thaⁿ ia̍h.',
'protectedpages' => 'Siū pó-hō͘ ê ia̍h',
'protectedpages-indef' => '干焦無限期保護的頁',
'protectedpages-cascade' => '干焦連鎖保護的頁',
'protectedpagestext' => 'Ē-kha ê ia̍h siū pó-hō͘, bē-tit soá-ūi ia̍h pian-chi̍p',
'protectedpagesempty' => '照遐的參數保護的，這馬無半頁。',
'protectedtitles' => '保護牢著的標題',
'protectedtitlestext' => '下跤的標題袂當寫。',
'protectedtitlesempty' => '照遐的參數保護的標題，這馬無半頁。',
'listusers' => 'Iōng-chiá lia̍t-toaⁿ',
'listusers-editsonly' => '干焦顯示有改過的用者',
'listusers-creationsort' => '照開始寫的日期排',
'usereditcount' => '改過$1{{PLURAL:$1|擺|擺}}',
'usercreated' => ' {{GENDER:$3|}}佇$1 $2創建',
'newpages' => 'Sin ia̍h',
'newpages-username' => 'Iōng-chiá miâ-chheng:',
'ancientpages' => 'Kó·-ia̍h',
'move' => 'Sóa khì',
'movethispage' => 'Sóa chit ia̍h',
'unusedimagestext' => 'Ē-kha ê tóng-àn bô poàⁿ ia̍h ū teh iōng. M̄-koh ia̍h lâu leh. 
Chhiáⁿ chù-ì: kî-thaⁿ ê bāng-chām ū khó-lêng iōng URL ti̍t-chiap liân kàu iáⁿ-siōng, só·-í sui-jiân bô teh iōng, mā sī ē lia̍t tī chia.',
'unusedcategoriestext' => 'Ū ē-kha chiah-ê lūi-pia̍t-ia̍h, m̄-koh bô kî-thaⁿ ê bûn-chiuⁿ a̍h-sī lūi-pia̍t lī-iōng.',
'notargettitle' => '無目標',
'notargettext' => '你無指定目標頁面抑是用者通做這个動作',
'nopagetitle' => '無這个目標頁',
'nopagetext' => '無你指定的目標頁。',
'pager-newer-n' => '{{PLURAL:$1|較新一个|較新$1个 }}',
'pager-older-n' => '{{PLURAL:$1|較舊一个|較舊$1个}}',
'suppress' => '監督',
'querypage-disabled' => '這个特殊頁因為效能的原因已經無咧用。',

# Book sources
'booksources' => 'Tô͘-su chu-liāu',
'booksources-search-legend' => '揣圖書資料',
'booksources-go' => '來去',
'booksources-text' => '下跤是連接去賣新冊抑舊冊網站的清單，並而可能有你欲揣的冊的其他資料：',
'booksources-invalid-isbn' => '提供的ISBN號碼無正確，請檢查拷備來源是毋是有錯誤。',

# Special:Log
'specialloguserlabel' => '操作者：',
'speciallogtitlelabel' => 'Bo̍k-piau (sû-tiâu ia̍h iōng-chiá) :',
'log' => '記錄',
'all-logs-page' => '所有公開的記錄',
'alllogstext' => '顯示所有佇 {{SITENAME}} 有提供的記錄，
你會當看你所選的記錄類別、用者名稱（大小寫有差）抑是相關的頁（大小寫有差）。',
'logempty' => 'Log lāi-bīn bô sio-tùi ê hāng-bo̍k.',
'log-title-wildcard' => '去揣以這个文字做頭的標題',

# Special:AllPages
'allpages' => 'Só·-ū ê ia̍h',
'alphaindexline' => '$1 kàu $2',
'nextpage' => 'Āu 1 ia̍h ($1)',
'prevpage' => '前一頁（$1）',
'allpagesfrom' => 'Tùi chit ia̍h khai-sí hián-sī:',
'allpagesto' => '顯示到這頁：',
'allarticles' => 'Só·-ū ê bûn-chiuⁿ',
'allinnamespace' => 'Só·-ū ê ia̍h ($1 miâ-khong-kan)',
'allnotinnamespace' => 'Só·-ū ê ia̍h (bô tī $1 miâ-khong-kan)',
'allpagesprev' => 'Téng 1 ê',
'allpagesnext' => 'ē 1 ê',
'allpagessubmit' => 'Lâi-khì',
'allpagesprefix' => '顯示頁標題有：',
'allpagesbadtitle' => '指定的頁面標題無適當，抑是有用著別个語言抑是別个Wiki。
伊可能是有一字抑一字以上的字是袂當用佇標題。',
'allpages-bad-ns' => '佇{{SITENAME}}無"$1"這个名空間。',
'allpages-hide-redirects' => '掩轉頁',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => '你當咧看這頁的快取(cached)版本，彼可能是第$1舊的。',
'cachedspecial-viewing-cached-ts' => '你當咧看這頁的快取版本，彼可能佮這馬的無仝款。',
'cachedspecial-refresh-now' => '看上新。',

# Special:Categories
'categories' => 'Lūi-pia̍t',
'categoriespagetext' => 'Ē-kha {{PLURAL:$1| ê ūi-pia̍t|ê ūi-pia̍t}} ū ia̍h ia̍h-sī mûi-thé.
[[Special:UnusedCategories|Bô iōng tio̍h ê ūi-pia̍t]] tō bô tī chiah hián-sī.
Lēng-goā thang chham-khó [[Special:WantedCategories|beh ti̍h ê lūi-pia̍t]].',
'categoriesfrom' => 'Tùi chit ê lūi-pia̍t khai-sí hián-sī:',
'special-categories-sort-count' => '按數量排',
'special-categories-sort-abc' => '按字母排',

# Special:DeletedContributions
'deletedcontributions' => 'Hō͘ lâng thâi tiāu ê kòng-hiàn',
'deletedcontributions-title' => 'Hō͘ lâng thâi tiāu ê kòng-hiàn',
'sp-deletedcontributions-contribs' => '貢獻',

# Special:LinkSearch
'linksearch' => 'Chhoē chām-goā ê liân-kiat',
'linksearch-pat' => '揣的方式：',
'linksearch-ns' => '名空間：',
'linksearch-ok' => '揣',
'linksearch-text' => '會當用親像“*.wikipedia.org”的萬用字元，
上少愛對上頂層的網域，親像“*.org”。<br />
支援的協議：<tt>$1</tt>（莫加佇你的搜揣）。',
'linksearch-line' => '$1 是對$2連接來的',
'linksearch-error' => '萬用字元干焦會當用佇主機名的頭前。',

# Special:ListUsers
'listusersfrom' => '對這个用者開始顯示：',
'listusers-submit' => '顯示',
'listusers-noresult' => '揣無用者',
'listusers-blocked' => '（封鎖牢咧）',

# Special:ActiveUsers
'activeusers' => '有咧活動的用者清單',
'activeusers-intro' => '這是佇過去$1 {{PLURAL:$1|工y|工}}有做過一寡活動的用者清單。',
'activeusers-count' => '佇{{PLURAL:$3|一工|$3工}}內的$1改編輯',
'activeusers-from' => '對這个用者開始顯示：',
'activeusers-hidebots' => '掩機器人',
'activeusers-hidesysops' => '掩管理員',
'activeusers-noresult' => '揣無用者',

# Special:Log/newusers
'newuserlogpage' => '用者建立的記錄',
'newuserlogpagetext' => '這是開用者口座的記錄',

# Special:ListGroupRights
'listgrouprights' => '用者陣的權利',
'listgrouprights-summary' => '下跤是佇這个wiki分的用者陣清單，佮相關的使用權。
每一陣的權利，通去看[[{{MediaWiki:Listgrouprights-helppage}}|其他資料]]。',
'listgrouprights-key' => '* <span class="listgrouprights-granted">授權的權利</span>
* <span class="listgrouprights-revoked">扣除的權利</span>',
'listgrouprights-group' => '分組',
'listgrouprights-rights' => '權利',
'listgrouprights-helppage' => 'Help:分組的權利',
'listgrouprights-members' => '(成員列單)',
'listgrouprights-addgroup' => '加入的{{PLURAL:$2|个|个}}組: $1',
'listgrouprights-removegroup' => '徙走的{{PLURAL:$2|个|个}}組: $1',
'listgrouprights-addgroup-all' => '加入所有的組',
'listgrouprights-removegroup-all' => '離開所有的組',
'listgrouprights-addgroup-self' => '共家己加入去{{PLURAL:$2|个|个}}組：$1',
'listgrouprights-removegroup-self' => '共家己對{{PLURAL:$2|个|个}}組徙走：$1',
'listgrouprights-addgroup-self-all' => '共家己加入所有的組',
'listgrouprights-removegroup-self-all' => '共家己對所有的組徙走',

# E-mail user
'mailnologin' => 'Bô siu-phoe ê chū-chí',
'mailnologintext' => 'Lí it-tēng ài [[Special:UserLogin|teng-ji̍p]] jī-chhiáⁿ ū 1 ê ū-hāu ê e-mail chū-chí tī lí ê [[Special:Preferences|iōng-chiá siat-tēng]] chiah ē-tàng kià e-mail hō· pa̍t-ūi iōng-chiá.',
'emailuser' => 'Kià e-mail hō· iōng-chiá',
'emailpage' => 'E-mail iōng-chiá',
'emailpagetext' => 'Lí ē-tàng iōng ē-kha ê pió kià chi̍t tiuⁿ phe hō͘ chit ê iōng-chiá.
Lí ê [[Special:Preferences|siat-tēng]] ê tiān-chú-phe tē-chí ē chhut-hiān tī tiān-chú-phe ê "Kià-phe-chiá" (From) hit ūi. Án-ne siu-phe-chiá chiah ū hoat-tō· kā lí hôe-phe.',
'usermailererror' => '退批錯誤：',
'defemailsubject' => '{{SITENAME}}的用者 $1 送的電子批',
'usermaildisabled' => '你的電子批已經停掉',
'usermaildisabledtext' => '你袂當佇這个wiki寄批予別人',
'noemailtitle' => 'Bô e-mail chū-chí',
'noemailtext' => 'Chit ūi iōng-chiá pēng-bô lâu ū-hāu ê e-mail chū-chí.',
'nowikiemailtitle' => '無電子批',
'nowikiemailtext' => '這个用者無欲收電子批。',
'emailnotarget' => '無彼个收批的人，抑是收批的用者名稱毋著。',
'emailtarget' => '拍入欲收批的用者名稱',
'emailusername' => '用者名稱：',
'emailusernamesubmit' => '送出',
'email-legend' => '送一張電子批去予佇{{SITENAME}}的另外一位用者',
'emailfrom' => 'Lâi chū:',
'emailto' => 'Khì hō·:',
'emailsubject' => 'Tê-bo̍k:',
'emailmessage' => 'Sìn-sit:',
'emailsend' => 'Sàng chhut-khì',
'emailccme' => '共我的訊息用電子批寄一份予我',
'emailccsubject' => '你送予$1訊息的副本：$2',
'emailsent' => 'E-mail sàng chhut-khì ah',
'emailsenttext' => 'Lí ê e-mail í-keng sàng chhut-khì ah.',
'emailuserfooter' => '這張由$1寄予$2的電子批已經用{{SITENAME}}的「電子批用者」功能送出。',

# User Messenger
'usermessage-summary' => '留系統信息',
'usermessage-editor' => '系統信息',

# Watchlist
'watchlist' => 'Kàm-sī-toaⁿ',
'mywatchlist' => 'Góa ê kàm-sī-toaⁿ',
'watchlistfor2' => '予$1 $2',
'nowatchlist' => 'Lí ê kàm-sī-toaⁿ bô pòaⁿ hāng.',
'watchlistanontext' => '請$1去看抑是改你的監視清單。',
'watchnologin' => 'Bô teng-ji̍p',
'watchnologintext' => 'Lí it-tēng ài [[Special:UserLogin|teng-ji̍p]] chiah ē-tàng siu-kái lí ê kàm-sī-toaⁿ.',
'addwatch' => '加入去監視單',
'addedwatchtext' => "\"[[:\$1]]\" chit ia̍h í-keng ka-ji̍p lí ê [[Special:Watchlist|kàm-sī-toaⁿ]]. Bī-lâi chit ia̍h a̍h-sī siong-koan ê thó-lūn-ia̍h nā ū kái-piàn, ē lia̍t tī hia. Tông-sî tī [[Special:RecentChanges|Chòe-kīn ê kái-piàn]] ē iōng '''chho·-thé''' hián-sī ia̍h ê piau-tê, án-ne khah bêng-hián. Ká-sú lí beh chiōng chit ia̍h tùi lí ê kàm-sī-toaⁿ tû tiāu, khì khòng-chè-tiâu chhi̍h \"Mài kàm-sī\" chiū ē-sái-tit.",
'removewatch' => '對監視單徙走',
'removedwatchtext' => '"[[:$1]]" chit ia̍h í-keng tùi lí ê [[Special:Watchlist|kàm-sī-toaⁿ]] soá cháu.',
'watch' => 'kàm-sī',
'watchthispage' => 'Kàm-sī chit ia̍h',
'unwatch' => 'Mài kàm-sī',
'unwatchthispage' => 'Mài koh kàm-sī',
'notanarticle' => '毋是內容頁面',
'notvisiblerev' => '別个用者的頂一个修訂本已經予人刣掉',
'watchnochange' => 'Lí kàm-sī ê hāng-bo̍k tī hián-sī ê sî-kî í-lāi lóng bô siu-kái kòe.',
'watchlist-details' => 'Kàm-sī-toaⁿ ū {{PLURAL:$1|$1 ia̍h|$1 ia̍h}}, thó-lūn-ia̍h bô sǹg chāi-lāi.',
'wlheader-enotif' => '*會當用電子批通知',
'wlheader-showupdated' => '自你頂回看的、到今有改過的會用較大烏字顯示',
'watchmethod-recent' => 'tng teh kíam-cha choè-kīn ê siu-kái, khoàⁿ ū kàm-sī ê ia̍h bô',
'watchmethod-list' => 'tng teh kiám-cha kàm-sī ê ia̍h khoàⁿ chòe-kīn ū siu-kái bô',
'watchlistcontains' => 'Lí ê kàm-sī-toaⁿ siu {{PLURAL:$1|ia̍h|ia̍h}} .',
'iteminvalidname' => "項目'$1'有問題，名稱無適當...",
'wlnote' => "Ē-kha sī tī $3, $4 chìn-chêng {{PLURAL:chi tiám-cheng|'''$2''' tiám-cheng}} í-lâi ê {{PLURAL:$1| chi̍t piàn|'''$1''' piàn}} siu-kái.",
'wlshowlast' => 'Hián-sī chêng $1 tiám-cheng $2 ji̍t $3',
'watchlist-options' => '監視單的選項',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => '共監視',
'unwatching' => '莫監視',
'watcherrortext' => '佇你改"$1"監視設定的時陣，發生一个問題',

'enotif_mailer' => '{{SITENAME}} 的電子批通知系統',
'enotif_reset' => '共全部的頁攏當做巡過',
'enotif_newpagetext' => '這是新的一頁',
'enotif_impersonal_salutation' => '{{SITENAME}}用者',
'changed' => '改過',
'created' => '寫過',
'enotif_subject' => '佇{{SITENAME}}的$PAGETITLE這頁捌予$CHANGEDORCREATED$PAGEEDITOR',
'enotif_lastvisited' => '看$1，自你頂回來到今所有改的',
'enotif_lastdiff' => '看$1這回改的',
'enotif_anon_editor' => '無名氏用者$1',
'enotif_body' => '敬愛的$WATCHINGUSERNAME：


{{SITENAME}}的$PAGETITLE頁面已經佇$PAGEEDITDATE予$PAGEEDITOR$CHANGEDORCREATED，請看 $PAGETITLE_URL 這个這馬的版本。

$NEWPAGE

編輯的摘要：$PAGESUMMARY $PAGEMINOREDIT

聯絡這位編輯者：

電子批：$PAGEEDITOR_EMAIL
本站：$PAGEEDITOR_WIKI

以後佇你閣看這頁進前，若有閣改過，嘛袂通知你。
你會當共你的監視表重設頁面的通知記號。

{{SITENAME}}通知系統敬上

--
欲改你的電子批設定，請看
{{canonicalurl:{{#special:Preferences}}}}

欲改你的監視表設定，請看
{{canonicalurl:{{#special:EditWatchlist}}}}

欲對你的監視單徙掉某頁，請看
$UNWATCHURL

回饋佮進一步的幫助：
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage' => 'Thâi ia̍h',
'confirm' => 'Khak-tēng',
'excontent' => "lōe-iông sī: '$1'",
'excontentauthor' => "loē-iông sī: '$1' (î-it ê kòng-hiàn-chiá sī '[[Special:Contributions/$2|$2]]')",
'exbeforeblank' => "chìn-chêng ê lōe-iông sī: '$1'",
'exblank' => 'ia̍h khang-khang',
'delete-confirm' => '刣掉$1',
'delete-legend' => '刣掉',
'historywarning' => 'Kéng-kò: Lí beh thâi ê ia̍h ū {{PLURAL:$1| ê siu-tèng le̍k-sú|ê siu-tèng le̍k-sú}}:',
'confirmdeletetext' => 'Lí tih-beh kā 1 ê ia̍h a̍h-sī iáⁿ-siōng (pau-koat siong-koan ê le̍k-sú) éng-kiú tùi chu-liāu-khò· thâi tiāu. Chhiáⁿ khak-tēng lí àn-sǹg án-ne chò, jī-chhiáⁿ liáu-kái hiō-kó, jī-chhiáⁿ bô ûi-hoán [[{{MediaWiki:Policy-url}}]].',
'actioncomplete' => 'Chip-hêng sêng-kong',
'actionfailed' => '做無成',
'deletedtext' => '"$1" í-keng thâi tiāu. Tùi $2 khoàⁿ-ē-tio̍h chòe-kīn thâi ê kì-lo̍k.',
'dellogpage' => '刣掉的記錄',
'dellogpagetext' => 'Í-hā lia̍t chhut chòe-kīn thâi tiāu ê hāng-bo̍k.',
'deletionlog' => '刣掉的記錄',
'reverted' => '轉轉去前一个版本',
'deletecomment' => 'Lí-iû:',
'deleteotherreason' => '其他／另外的理由：',
'deletereasonotherlist' => '其他的理由',
'deletereason-dropdown' => '*一般刣掉的理由
** 作者的要求
** 違反著作權
** 破壞',
'delete-edit-reasonlist' => '編輯刣掉的理由',
'delete-toobig' => '這个頁面有誠濟的編輯歷史，超過$1{{PLURAL:$1|擺|擺}}的修改。
為著防止意外佇{{SITENAME}}造成擾亂，欲刣掉這款的頁面有限制。',
'delete-warning-toobig' => '這頁有誠濟修改歷史，超過$1改的{{PLURAL:$1|修訂本|修訂本}}。
共伊刣掉可能會破壞{{SITENAME}}的資料庫運作；愛細膩操作。',

# Rollback
'rollback' => 'Kā siu-kái ká tńg khì',
'rollback_short' => 'Ká tńg khì',
'rollbacklink' => 'ká tńg khì',
'rollbackfailed' => 'Ká bē tńg khì',
'cantrollback' => 'Bô-hoat-tō· kā siu-kái ká-tńg--khì; téng ūi kòng-hiàn-chiá sī chit ia̍h î-it ê chok-chiá.',
'alreadyrolled' => 'Bô-hoat-tō· kā [[User:$2|$2]] ([[User talk:$2|Thó-lūn]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) tùi [[:$1]] ê siu-kái ká-tńg-khì; 
í-keng ū lâng siu-kái a̍h-sī ká-tńg chit ia̍h. 
Téng 1 ūi siu-kái-chiá sī [[User:$3|$3]] ([[User talk:$3|talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "Pian-chi̍p kài-iàu sī: \"''\$1''\".",
'revertpage' => '回轉[[Special:Contributions/$2|$2]]（[[User talk:$2|對話]]）的編輯到頂一个[[User:$1|$1]]的修訂版本',
'revertpage-nouser' => '回轉（無用者名）的編輯到頂一个[[User:$1|$1]]的修訂版本',
'rollback-success' => '回轉$1的編輯，
轉轉去頂一个$2的修訂版本。',

# Edit tokens
'sessionfailure-title' => '登入的資訊失效',
'sessionfailure' => '你的登入連線敢若有問題，
為著防止連線被駭客(hijack)，這个操作已經取消，
請先轉去前一頁，重新載入彼頁，才閣試。',

# Protect
'protectlogpage' => '保護的記錄',
'protectlogtext' => '下跤是保護頁有改過的清單，
請參考[[Special:ProtectedPages|保護頁清單]]看這馬有保護的頁。',
'protectedarticle' => 'pó-hō͘ "[[$1]]"',
'modifiedarticleprotection' => '改"[[$1]]"的保護等級',
'unprotectedarticle' => '已經解除"[[$1]]"的保護',
'movedarticleprotection' => '已經共"[[$2]]"的保護設定徙去"[[$1]]"',
'protect-title' => 'Kái "$1" ê pó-hō· tíng-kip.',
'protect-title-notallowed' => '看"$1"的保護等級',
'prot_1movedto2' => '[[$1]] sóa khì tī [[$2]]',
'protect-badnamespace-title' => '袂當保護的名空間',
'protect-badnamespace-text' => '佇這个名空間的頁面袂當共保護。',
'protect-legend' => 'Khak-tēng beh pó-hō·',
'protectcomment' => 'Lí-iû:',
'protectexpiry' => '到期：',
'protect_expiry_invalid' => '到期時間毋著',
'protect_expiry_old' => '到期時間已經過去',
'protect-unchain-permissions' => '解除更加保護的選項',
'protect-text' => "你會當佇遮看佮改頁面的'''$1'''保護等級。",
'protect-locked-blocked' => "你袂當佇封鎖的時陣改保護等級，
下跤是'''$1'''這馬的保護等級:",
'protect-locked-dblock' => "佇資料庫鎖牢咧的時陣，袂當改保護等級。
下面是'''$1'''這頁這馬的保護等級:",
'protect-locked-access' => "你的口座無改保護等級的權利，
下面是'''$1'''這頁這馬的保護等級:",
'protect-cascadeon' => '這頁這馬予人保護牢咧，因為伊包括佇下面{{PLURAL:$1|一个|幾个}}頁面的連鎖保護牢咧，
你會當改這頁的保護等級，毋過對連鎖保護無影響。',
'protect-default' => '所有用者攏會當',
'protect-fallback' => '要求會當"$1"',
'protect-level-autoconfirmed' => '禁止新的佮猶未註冊的用者',
'protect-level-sysop' => '干焦管理員',
'protect-summary-cascade' => '連鎖',
'protect-expiring' => '佇$1會過期',
'protect-expiring-local' => '佇$1到期',
'protect-expiry-indefinite' => '無限',
'protect-cascade' => 'Cascading protection - pó-hō͘ jīm-hô pau-hâm tī chit ia̍h ê ia̍h.',
'protect-cantedit' => '你袂當改這頁的保護層級，因為你無授權共改。',
'protect-othertime' => '其他的時間：',
'protect-othertime-op' => '其他的時間',
'protect-existing-expiry' => '到期的時間: $2 $3',
'protect-otherreason' => '其他／另外的理由：',
'protect-otherreason-op' => '其他的理由',
'protect-dropdown' => '*一般保護的理由
** 過量的破壞
** 過量的灌水
** 無生產量的編輯戰
** 高流量頁面',
'protect-edit-reasonlist' => '編輯保護的理由',
'protect-expiry-options' => '一點鐘:1 hour,一工:1 day,一禮拜:1 week,兩禮拜:2 weeks,一個月:1 month,三個月:3 months,六個月:6 months,1年:1 year,無限:infinite',
'restriction-type' => '允准：',
'restriction-level' => '限制層級：',
'minimum-size' => '上細',
'maximum-size' => '上大：',
'pagesize' => '（位元組）',

# Restrictions (nouns)
'restriction-edit' => 'Siu-kái',
'restriction-move' => 'Sóa khì',
'restriction-create' => '開始寫',
'restriction-upload' => '上載',

# Restriction levels
'restriction-level-sysop' => '全保護',
'restriction-level-autoconfirmed' => '半保護',
'restriction-level-all' => '任何一級',

# Undelete
'undelete' => 'Kiù thâi tiāu ê ia̍h',
'undeletepage' => 'Khoàⁿ kap kiù thâi tiāu ê ia̍h',
'undeletepagetitle' => "'''下跤包括[[:$1]]的刣掉修訂本'''",
'viewdeletedpage' => '看刣掉的頁',
'undeletepagetext' => '下跤的{{PLURAL:$1|篇頁|篇頁}}已經予人刣掉，毋過猶留佇檔案庫，而且會使救倒轉來。
檔案庫內底可能會定時清掉。',
'undelete-fieldset-title' => '恢復修訂本',
'undeleteextrahelp' => "欲恢復頁面的全部歷史，就共所有的選格仔留空白，閣點擊 '''''{{int:undeletebtn}}''''' ，
欲恢復某一个版本，就共彼个版本進前的選格仔選起來，閣點擊'''''{{int:undeletebtn}}''''' 。",
'undeleterevisions' => '$1{{PLURAL:$1|版本|版本}}的保存檔',
'undeletehistory' => '若你共頁面恢復，所有的修訂本嘛會恢復佇歷史頁。
若佇這頁刣掉了後，已經有一个仝名的新頁建立，按呢恢復的修訂本會囥佇歷史的頭前。',
'undeleterevdel' => '若會變做上新的頁抑是修訂本已經部份刣掉，就無法共刣掉的頁搝倒轉來。
若拄著這種情形，你莫共上新的修訂本選起來抑是莫共藏起來。',
'undeletehistorynoadmin' => '這頁已經予人刣掉，
刣掉的原因顯示佇下面的編輯摘要，猶有刣掉進前，有編輯這頁的用者明細。
遮的修訂本的文字只有管理員才會當看。',
'undelete-revision' => '$1予$3（佇$4 $5）刣掉的修訂本。',
'undeleterevision-missing' => '毋著抑是無去的修訂本，
你的連結毋著，抑是彼个修訂本己經對保管庫轉回抑徙掉。',
'undelete-nodiff' => '無頂一个修訂本。',
'undeletebtn' => '恢復',
'undeletelink' => '看／恢復',
'undeleteviewlink' => 'Khoàⁿ',
'undeletereset' => '設便',
'undeleteinvert' => '選項以外',
'undeletecomment' => '理由：',
'undeletedrevisions' => '{{PLURAL:$1|1个|$1个}}修訂本已經恢復',
'undeletedrevisions-files' => '{{PLURAL:$1|1个|$1个}}版訂本佮{{PLURAL:$2|1个|$2个}}檔案已經恢復',
'undeletedfiles' => '{{PLURAL:$1|1个|$1个}}檔案已經恢復',
'cannotundelete' => '恢復刣掉的頁失敗，
有別人可能已經先共恢復。',
'undeletedpage' => "'''$1已經恢復'''

參考[[Special:Log/delete|刣掉記錄]]有最近刣掉佮恢復的記錄。",
'undelete-header' => '看[[Special:Log/delete|刣掉記錄]]有寫最近刣掉的頁。',
'undelete-search-title' => '揣刣掉的頁',
'undelete-search-box' => '揣刣掉的頁',
'undelete-search-prefix' => '對這頁開始顯示：',
'undelete-search-submit' => '揣',
'undelete-no-results' => '佇刣掉頁的文件內底無彼頁。',
'undelete-filename-mismatch' => '無法度恢復時間戳印是$1的修訂本：檔案名稱無合。',
'undelete-bad-store-key' => '無法度恢復時間戳印是$1的修訂本：檔案佇刣掉進前就無去。',
'undelete-cleanup-error' => '佇刣掉無咧用的歷史檔案"$1"的時陣，有錯誤。',
'undelete-missing-filearchive' => '因為資料庫內底無ID $1的歷史檔案，無法度共恢復，
伊可能已經予人恢復。',
'undelete-error' => '刣掉的頁欲恢復有錯誤',
'undelete-error-short' => '刣掉的檔案欲恢復有錯誤：$1',
'undelete-error-long' => '刣掉的檔案欲恢復的時陣有錯誤：

$1',
'undelete-show-file-confirm' => '你敢確定欲看"<nowiki>$1</nowiki>"佇 $2 $3 刣掉的修訂本？',
'undelete-show-file-submit' => '是',

# Namespace form on various pages
'namespace' => 'Miâ-khong-kan:',
'invert' => 'Soán-hāng í-gōa',
'tooltip-invert' => '鉤選這个框仔會共所選的名空間內底有改的頁掩起來（佮相關有選的命空間）',
'namespace_association' => '相關的名空間',
'tooltip-namespace_association' => '鉤選這个框仔，嘛會包括討論名空間抑頁空間，佮伊的相關名空間',
'blanknamespace' => '(Thâu-ia̍h)',

# Contributions
'contributions' => 'Iōng-chiá ê kòng-hiàn',
'contributions-title' => '用者佇$1的貢獻',
'mycontris' => 'Góa ê kòng-hiàn',
'contribsub2' => '$1的貢獻（$2）',
'nocontribs' => 'Chhōe bô tiâu-kiāⁿ ū-tùi ê hāng-bo̍k.',
'uctop' => '(siōng téng ê)',
'month' => 'Kàu tó 1 kó͘ goe̍h ûi-chí:',
'year' => 'Kàu tó 1 nî ûi-chí:',

'sp-contributions-newbies' => 'Kan-taⁿ hián-sī sin kháu-chō ê kòng-kiàn',
'sp-contributions-newbies-sub' => 'Sin lâi--ê',
'sp-contributions-newbies-title' => '新用者的貢獻',
'sp-contributions-blocklog' => '封鎖記錄',
'sp-contributions-deleted' => 'Hō͘ lâng thâi tiāu ê kòng-hiàn',
'sp-contributions-uploads' => '上載',
'sp-contributions-logs' => '記錄',
'sp-contributions-talk' => 'thó-lūn',
'sp-contributions-userrights' => '用者的使用權管理',
'sp-contributions-blocked-notice' => '這个用者這馬hông封鎖，
下跤有最近封鎖的紀錄通參考：',
'sp-contributions-blocked-notice-anon' => '這个IP地址這馬予人封鎖咧，
下跤有最近封鎖的紀錄通參考：',
'sp-contributions-search' => 'Chhoē chhut kòng-kiàn',
'sp-contributions-username' => 'IP Chū-chí a̍h iōng-chiá miâ:',
'sp-contributions-toponly' => '干焦看頂一回改的',
'sp-contributions-submit' => 'Chhoē',

# What links here
'whatlinkshere' => 'Tó-ūi liân kàu chia',
'whatlinkshere-title' => '連到"$1"的頁',
'whatlinkshere-page' => '頁：',
'linkshere' => "Í-hā '''[[:$1]]''' liân kàu chia:",
'nolinkshere' => "Bô poàⁿ ia̍h liân kàu '''[[:$1]]'''.",
'nolinkshere-ns' => '佇所選的名空間內底，無頁面連結到[[:$1]]。',
'isredirect' => 'choán-ia̍h',
'istemplate' => '包括',
'isimage' => '檔案連結',
'whatlinkshere-prev' => '{{PLURAL:$1|chêng|chêng $1 ê}}',
'whatlinkshere-next' => '{{PLURAL:$1|āu|āu $1 ê}}',
'whatlinkshere-links' => '← Liân kàu chia',
'whatlinkshere-hideredirs' => '$1 改向',
'whatlinkshere-hidetrans' => '$1包括',
'whatlinkshere-hidelinks' => '$1 連到遮',
'whatlinkshere-hideimages' => '$1圖像的連結',
'whatlinkshere-filters' => '過濾器',

# Block/unblock
'autoblockid' => '自動封鎖 #$1',
'block' => '封鎖用者',
'unblock' => '解除對用者的封鎖',
'blockip' => 'Hong-só iōng-chiá',
'blockip-title' => '封鎖用者',
'blockip-legend' => '封鎖用者',
'blockiptext' => '用下跤的表來封鎖某一个IP地址抑是用者名稱的寫作。
這只會當為著防止破壞，佮符合[[{{MediaWiki:Policy-url}}|守則]]的情況下才會當按呢做。
佇下跤寫一个具體的理由（親像，指出一个予人破壞的頁）。',
'ipadressorusername' => 'IP Chū-chí a̍h iōng-chiá miâ:',
'ipbexpiry' => '到期：',
'ipbreason' => 'Lí-iû:',
'ipbreasonotherlist' => '其他理由',
'ipbreason-dropdown' => '*一般封鎖的理由
** 寫假資料
** 共頁的內容徙掉
** 連結到外部廣告
** 佇頁面亂使寫
** 威脅的行為／騷擾別人
** 亂使用濟的口座
** 袂當接受的用者名稱',
'ipb-hardblock' => '防止有登入的用者對這个IP地址做編輯',
'ipbcreateaccount' => '防止建立新口座',
'ipbemailban' => '封鎖一个用者去寄電子批',
'ipbenableautoblock' => '自動封鎖這个用者頂一回用的IP地址，佮後來遐的想欲編輯的IP地址',
'ipbsubmit' => 'Hong-só chit ūi iōng-chiá',
'ipbother' => '其他時間：',
'ipboptions' => '兩點鐘:2 hours,一工:1 day,三工:3 days,一禮拜:1 week,兩禮拜:2 weeks,一個月:1 month,兩個月:3 months,六個月:6 months,一年:1 year,永久:infinite',
'ipbotheroption' => '其他',
'ipbotherreason' => '其他／另外的理由：',
'ipbhidename' => '佇編輯佮清單共用者名稱藏起來',
'ipbwatchuser' => '看這个用者的用者頁佮討論頁',
'ipb-disableusertalk' => '禁止這个用者佇封鎖的時陣修改家己的討論頁',
'ipb-change-block' => '照遮的設定閣共用者封鎖',
'ipb-confirm' => '確定封鎖',
'badipaddress' => 'Bô-hāu ê IP chū-chí',
'blockipsuccesssub' => 'Hong-só sêng-kong',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] í-keng pī hong-só. <br />Khì [[Special:BlockList|hong-só lia̍t-toaⁿ]] thang khoàⁿ pī hong-só ê .',
'ipb-blockingself' => '你欲封鎖你家己！你敢確定欲按呢做？',
'ipb-confirmhideuser' => '你是欲封鎖一个用者佮隱藏伊的用者名稱，這會隱藏用者名稱出現佇所有的表佮記錄的項目當中，你敢確定欲按呢做？',
'ipb-edit-dropdown' => '編輯封鎖的理由',
'ipb-unblock-addr' => '解除封鎖$1',
'ipb-unblock' => '拍開一个用者名稱抑是IP地址的封鎖',
'ipb-blocklist' => '看這馬的封鎖',
'ipb-blocklist-contribs' => '$1的貢獻',
'unblockip' => '解除對用者的封鎖',
'unblockiptext' => '用下跤的表，來恢復進前予人封鎖的IP地址、抑是用者名稱的寫作',
'ipusubmit' => 'Chhú-siau chit ê hong-só',
'unblocked' => '[[User:$1|$1]] 已經解除封鎖。',
'unblocked-range' => '$1已經解除封鎖',
'unblocked-id' => '對$1的封鎖已經徙掉',
'blocklist' => '封鎖牢咧的用者',
'ipblocklist' => 'Siū hong-só ê iōng-chiá',
'ipblocklist-legend' => '揣一个封鎖的用者',
'blocklist-userblocks' => '隱藏口座的封鎖',
'blocklist-tempblocks' => '隱藏臨時的封鎖',
'blocklist-addressblocks' => '隱藏孤一个的IP封鎖',
'blocklist-rangeblocks' => '隱藏區段IP的封鎖',
'blocklist-timestamp' => '戳印的時間',
'blocklist-target' => '目標',
'blocklist-expiry' => '到期',
'blocklist-by' => '封鎖管理員',
'blocklist-params' => '封鎖的參數',
'blocklist-reason' => '理由',
'ipblocklist-submit' => '揣',
'ipblocklist-localblock' => '本地封鎖',
'ipblocklist-otherblocks' => '其他的{{PLURAL:$1|封鎖|封鎖}}',
'infiniteblock' => '無限',
'expiringblock' => '佇$1  $2 到期',
'anononlyblock' => '只限無名氏',
'noautoblockblock' => '自動封鎖袂當用',
'createaccountblock' => '停止開口座',
'emailblock' => '電子批封鎖牢咧',
'blocklist-nousertalk' => '袂當改家己的討論頁',
'ipblocklist-empty' => '封鎖清單空的',
'ipblocklist-no-results' => '請求的IP地址抑是用者名稱無予人封鎖牢咧。',
'blocklink' => 'hong-só',
'unblocklink' => '取消封鎖',
'change-blocklink' => '改封鎖',
'contribslink' => 'kòng-hiàn',
'emaillink' => '寄電子批',
'autoblocker' => 'Chū-tōng kìm-chí lí sú-iōng, in-ūi lí kap "[[User:$1|$1]]" kong-ke kāng 1 ê IP chū-chí.
$1 ê kìm-chí lí-iû sī in-ūi "$2".',
'blocklogpage' => '封鎖記錄',
'blocklog-showlog' => '這个用者進前予人封鎖牢咧，
下跤有封鎖的記錄會當參考：',
'blocklog-showsuppresslog' => '這个用者進前予人封鎖牢咧閣共隱藏，
下跤有封鎖的記錄會當參考：',
'blocklogentry' => 'hong-só [[$1]], siat kî-hān chì $2 $3',
'reblock-logentry' => '改[[$1]]的封鎖到期時間 $2 $3',
'blocklogtext' => 'Chit-ê kì-lio̍k lia̍t-chhut hong-só/khui-só ê tōng-chok. Chū-tōng block ê IP tē-chí bô lia̍t--chhut-lâi ([[Special:BlockList|hong-só chheng-toaⁿ]] ū hiān-chú-sî ū-hāu ê kìm-chí hong-só o·-miâ-toaⁿ).',
'unblocklogentry' => '解除封鎖$1',
'block-log-flags-anononly' => '只會當是無名氏用者',
'block-log-flags-nocreate' => 'Khui kháu-chō thêng-iōng ah',
'block-log-flags-noautoblock' => '自動封鎖袂當用',
'block-log-flags-noemail' => '電子批封鎖牢咧',
'block-log-flags-nousertalk' => '袂當改家己的討論頁',
'block-log-flags-angry-autoblock' => '已經有加強版的自動封鎖',
'block-log-flags-hiddenname' => '用者名稱藏起來矣',
'range_block_disabled' => '管理員使用區段IP封鎖的功能已經停用。',
'ipb_expiry_invalid' => '到期的時間毋著',
'ipb_expiry_temp' => '隱藏用者名稱的封鎖是永久性的。',
'ipb_hide_invalid' => '無法度封鎖這个口座，伊可能做過誠濟擺的編輯。',
'ipb_already_blocked' => '"$1"是封鎖牢咧',
'ipb-needreblock' => '$1已經封鎖牢咧，你敢欲敢這个設定？',
'ipb-otherblocks-header' => '其他的{{PLURAL:$1|封鎖|封鎖}}',
'unblock-hideuser' => '你無法度解封這个用者，因為in的名稱予人隱藏起來。',
'ipb_cant_unblock' => '錯誤：無$1的封鎖，伊可能已經解除封鎖。',
'ipb_blocked_as_range' => '錯誤: IP地址$1無予人直接封鎖，所以無通解除封鎖。
毋過，伊佇$2範圍內底，彼範圍為當共解除封鎖。',
'ip_range_invalid' => '毋著的網址(IP)範圍',
'ip_range_toolarge' => '超過 /$1 的封鎖範圍是袂當的。',
'blockme' => '封鎖我',
'proxyblocker' => '代理封鎖器',
'proxyblocker-disabled' => '這个功能袂當用。',
'proxyblockreason' => '你的IP地址是一个開放的代理，伊已經予人封鎖。
請聯絡你的網路服務提供商、抑是你單位的技術支援者，閣共in講這个嚴重的安全問題。',
'proxyblocksuccess' => '完成。',
'sorbsreason' => '你的IP地址佇{{SITENAME}}是當做DNSBL的開放代理服務器之一。',
'sorbs_create_account_reason' => '你的IP地址佇{{SITENAME}}是當做DNSBL的開放代理服務器之一。
你袂當建立口座',
'cant-block-while-blocked' => '你若予人封鎖牢咧，你就袂封鎖別个用者。',
'cant-see-hidden-user' => '你想欲封鎖的用者已經予人封鎖抑是隱藏，
因為你無授權隱藏用戶，你袂當看抑是改這个用者的封鎖。',
'ipbblocked' => '你袂當封鎖抑是解除封鎖別个用者，因為你本身就封鎖牢咧。',
'ipbnounblockself' => '你袂當對家己解除封鎖',

# Developer tools
'lockdb' => '封鎖資料庫',
'unlockdb' => '解除對資料庫的封鎖',
'lockdbtext' => '封鎖資料庫會停止所有的用者去改頁、改設定、改監視單佮其他佇資料庫的修改，
請確定你欲按呢做，閣愛佇你維修了解除封鎖。',
'unlockdbtext' => '解除封鎖會予所有的用者通編輯、改設定、改監視單佮其他通改資料庫的代誌，
請確認這是你欲做的動作。',
'lockconfirm' => '是，我確實欲封鎖資料庫。',
'unlockconfirm' => '是，我確實欲解除封鎖資料庫。',
'lockbtn' => '封鎖資料庫',
'unlockbtn' => '解除對資料庫的封鎖',
'locknoconfirm' => 'Lí bô kau "khak-tēng" ê keh-á.',
'lockdbsuccesssub' => '資料庫封鎖成功',
'unlockdbsuccesssub' => '已經共資料庫的封鎖解除',
'lockdbsuccesstext' => '資料庫已經封鎖牢咧。<br />
維修了，愛會記得[[Special:UnlockDB|解除封鎖]]。',
'unlockdbsuccesstext' => '資料庫已經解除封鎖',
'lockfilenotwritable' => '資料庫的記錄檔案袂當寫入去，
欲封鎖抑解除封鎖，需要網路伺服器愛會當寫入。',
'databasenotlocked' => '資料庫無封鎖牢咧。',
'lockedbyandtime' => '（ {{GENDER:$1|$1}}佇$2 $3做的）',

# Move page
'move-page' => '徙$1',
'move-page-legend' => 'Sóa ia̍h',
'movepagetext' => "Ē-kha chit ê pió iōng lâi kái 1 ê ia̍h ê piau-tê (miâ-chheng); só·-ū siong-koan ê le̍k-sú ē tòe leh sóa khì sin piau-tê.
Kū piau-tê ē chiâⁿ-chò 1 ia̍h choán khì sin piau-tê ê choán-ia̍h.
Liân khì kū piau-tê ê liân-kiat (link) bē khì tāng--tio̍h; ē-kì-tit chhiau-chhōe [[Special:DoubleRedirects|siang-thâu (double)]] ê a̍h-sī [[Special:BrokenRedirects|kò·-chiòng ê choán-ia̍h]].
Lí ū chek-jīm khak-tēng liân-kiat kè-sio̍k liân tio̍h ūi.

Sin piau-tê nā í-keng tī leh (bô phian-chi̍p koè ê khang ia̍h, choán-ia̍h bô chún-sǹg), tō bô-hoat-tō· soá khì hia.
Che piaú-sī nā ū têng-tâⁿ, ē-sái kā sin ia̍h soà tńg-khì goân-lâi ê kū ia̍h.

'''SÈ-JĪ!'''
Tùi chē lâng tha̍k ê ia̍h lâi kóng, soá-ūi sī toā tiâu tāi-chì.
Liâu--lo̍h-khì chìn-chêng, chhiáⁿ seng khak-tēng lí ū liáu-kái chiah-ê hiō-kó.",
'movepagetext-noredirectfixer' => "用下跤的表通改頁的名，閣改伊的歷史版本徙去新的，
舊名稱這頁會轉向新頁，
嘛愛去檢查看有[[Special:DoubleRedirects|轉兩遍]]，抑是[[Special:BrokenRedirects|轉無去]]，
你有責任確定連接有指到應該去的位。

請注意若新名稱的頁已經佇咧，徙的動作'''袂做'''，除非彼是空的抑是轉頁閣無編輯過，
這表示，你若創毋著，你會當改倒轉去，而且袂去崁掉一个存在的頁。

'''注意！'''
這佇熱門的頁是一个激烈、意外的改變，佇你做進前，請你確定你了解這个後果。",
'movepagetalktext' => "Siong-koan ê thó-lūn-ia̍h (chún ū) oân-nâ ē chū-tōng tòe leh sóa-ūi. Í-hā ê chêng-hêng '''bô chún-sǹg''': *Beh kā chit ia̍h tùi 1 ê miâ-khong-kan (namespace) soá khì lēng-gōa 1 ê miâ-khong-kan, *Sin piau-tê í-keng ū iōng--kòe ê thó-lūn-ia̍h, he̍k-chiá *Ē-kha ê sió-keh-á bô phah-kau. Í-siōng ê chêng-hêng nā-chún tī leh, lí chí-hó iōng jîn-kang ê hong-sek sóa ia̍h a̍h-sī kā ha̍p-pèng (nā ū su-iàu).",
'movearticle' => 'Sóa ia̍h:',
'moveuserpage-warning' => "'''注意：'''你咧徙用著的頁，請注意這干焦徙振動頁，''無''改用者名。",
'movenologin' => 'Bô teng-ji̍p',
'movenologintext' => 'Lí it-tēng ài sī chù-chheh ê iōng-chiá jī-chhiáⁿ ū [[Special:UserLogin|teng-ji̍p]] chiah ē-tàng sóa ia̍h.',
'movenotallowed' => '你無授權通去徙頁',
'movenotallowedfile' => '你無授權通去徙檔案',
'cant-move-user-page' => '你無授權通去徙用者頁（無包括伊的下頁）',
'cant-move-to-user-page' => '你無授權通去徙用者頁（下頁例外）',
'newtitle' => 'Khì sin piau-tê:',
'move-watch' => 'Kàm-sī chit ia̍h',
'movepagebtn' => 'Sóa ia̍h',
'pagemovedsub' => 'Sóa-ūi sêng-kong',
'movepage-moved' => '\'\'\'"$1" 已經徙去 "$2"\'\'\'',
'movepage-moved-redirect' => '已經建立一个轉向的頁。',
'movepage-moved-noredirect' => '建立轉頁無成。',
'articleexists' => 'Kāng miâ ê ia̍h í-keng tī leh, a̍h-sī lí kéng ê miâ bô-hāu. Chhiáⁿ kéng pa̍t ê miâ.',
'cantmove-titleprotected' => '你袂當徙頁去這位，因為新名稱的建立予人保護牢咧。',
'talkexists' => "'''Ia̍h ê loē-bûn ū soá cháu, m̄-koh siong-koan ê thó-lūn-ia̍h bô toè leh soá, in-ūi sin piau-tê pun-té tō ū hit ia̍h. Chhiáⁿ iōng jîn-kang ê hoat-tō· kā ha̍p-pèng.'''",
'movedto' => 'sóa khì tī',
'movetalk' => 'Sūn-sòa sóa thó-lūn-ia̍h',
'move-subpages' => '徙子頁（上到$1頁）',
'move-talk-subpages' => '徙討論頁的子頁（上到$1頁）',
'movepage-page-exists' => '頁面 $1 已經佇咧，袂當自動崁過。',
'movepage-page-moved' => '$1 í-keng sóa khì tī $2.',
'movepage-page-unmoved' => '$1這頁袂當徙去$2',
'movepage-max-pages' => '上濟$1{{PLURAL:$1|頁|頁}}已經徙位，袂有閣甲濟會自動徙位。',
'movelogpage' => '徙位記錄',
'movelogpagetext' => 'Ē-kha lia̍t-chhut hông soá-ūi ê ia̍h.',
'movesubpage' => '{{PLURAL:$1|子頁|子頁}}',
'movesubpagetext' => '這頁有$篇{{PLURAL:$1|子頁|子頁}}佇下跤。',
'movenosubpage' => '這頁無下頁',
'movereason' => 'Lí-iû:',
'revertmove' => '回轉',
'delete_and_move' => '刣掉而且徙走',
'delete_and_move_text' => '==需要刣掉==
目標頁面"[[:$1]]"已經有矣，
你敢真正欲為著徙頁共彼頁刣掉？',
'delete_and_move_confirm' => '無毋著，共刣掉彼頁。',
'delete_and_move_reason' => '為著徙位，[[$1]]已經刣掉。',
'selfmove' => 'Goân piau-tê kap sin piau-tê sio-siâng; bô hoat-tō· sóa.',
'immobile-source-namespace' => '佇"$1"名空間內底袂使徙頁。',
'immobile-target-namespace' => '袂當共頁徙去$1名空間。',
'immobile-target-namespace-iw' => '跨維基的連結袂當用佇徙頁。',
'immobile-source-page' => '這頁袂當徙振動。',
'immobile-target-page' => '無法度徙去指定的標題',
'imagenocrossnamespace' => '檔案只會當佇"檔案"名空間內底徙位。',
'nonfile-cannot-move-to-file' => '袂當共毋是檔案的物件徙來"檔案"名空間。',
'imagetypemismatch' => '新檔案尾的類型無符合伊的類型。',
'imageinvalidfilename' => '目標的檔案名稱無適當',
'fix-double-redirects' => '改新所有指到原本標題的轉向。',
'move-leave-redirect' => '留一个轉向',
'protectedpagemovewarning' => "'''KÉNG-KÒ: Pún ia̍h só tiâu leh. Kan-taⁿ ū hêng-chèng te̍k-koân ê iōng-chiá (sysop) ē-sái soá tín-tāng.'''
Ē-kha ū choè-kīn ê kì-lio̍k thang chham-khó:",
'semiprotectedpagemovewarning' => "'''注意：'''這頁予人保護牢咧，只有有註冊的用者通徙振動，
下跤有最近的記錄通參考：",
'move-over-sharedrepo' => '== 檔案已經存在 ==
[[:$1]]已經佇共享資源，共檔案徙到這个標題會蓋掉共享的檔案。',
'file-exists-sharedrepo' => '仝名的檔案已經佇共享資源，
請用另外一个檔案名稱。',

# Export
'export' => 'Su-chhut ia̍h',
'exporttext' => '你會當共某一頁抑是一組頁的文字佮修改歷史以 XML 格式輸出，
按呢就會當佇別个用MediaWiki的Wiki網站，佇[[Special:Import|輸入頁]]做輸入。

欲輸出頁面，請佇下跤的文字框拍頁的標題，每一逝一个標題，閣選擇你敢欲這馬的修訂本佮所有過去的修訂本、頁的歷史項目，抑是這馬的修訂本佮上尾的編輯信息。

另外你嘛會當連結輸出檔案，親像你會當用[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]輸出「[[{{MediaWiki:Mainpage}}]]」這頁。',
'exportall' => '輸出所有的頁',
'exportcuronly' => 'Hān hiān-chhú-sî ê siu-téng-pún, mài pau-koat kui-ê le̍k-sú',
'exportnohistory' => "----
'''注意：'''因為性能的原因，對這个表輸出頁面的全部歷史已經停止。",
'exportlistauthors' => '包括每一頁的貢獻者全部清單',
'export-submit' => '輸出',
'export-addcattext' => '對這類別加頁面：',
'export-addcat' => '加頁面',
'export-addnstext' => '對這个名空間加頁面：',
'export-addns' => '加頁面',
'export-download' => '保存做檔案',
'export-templates' => '包括枋模',
'export-pagelinks' => '包刮到第幾層的轉頁：',

# Namespace 8 related
'allmessages' => 'Hē-thóng sìn-sit',
'allmessagesname' => 'Miâ',
'allmessagesdefault' => 'Siat piān ê bûn-jī',
'allmessagescurrent' => 'Bo̍k-chêng ê bûn-jī',
'allmessagestext' => 'Che sī MediaWiki: miâ-khong-kan lāi-té ê hē-thóng sìn-sit chheng-toaⁿ.
Lí nā beh tàu saⁿ-kang hoan-e̍k. Chhiáⁿ kàu [//www.mediawiki.org/wiki/Localisation MediaWiki chāi-tè-hoà] kap [//translatewiki.net translatewiki.net] bāng-chām.',
'allmessagesnotsupportedDB' => "這頁袂當用得，因為'''\$wgUseDatabaseMessages'''已經停用。",
'allmessages-filter-legend' => '過濾器',
'allmessages-filter' => '以家己設的去過濾：',
'allmessages-filter-unmodified' => '無修改過',
'allmessages-filter-all' => '全部',
'allmessages-filter-modified' => '修改',
'allmessages-prefix' => '欲做過濾的頭前文字：',
'allmessages-language' => '話語：',
'allmessages-filter-submit' => '來去',

# Thumbnails
'thumbnail-more' => 'Hòng-tōa',
'filemissing' => 'Bô tóng-àn',
'thumbnail_error' => '產生小圖時錯誤：$1',
'djvu_page_error' => 'DjVu頁面超出範圍',
'djvu_no_xml' => '無法度對DjVu檔案內底取得XML',
'thumbnail-temp-create' => '無法度建立臨時的小圖檔案',
'thumbnail-dest-create' => '袂當共小圖囥去欲囥的位',
'thumbnail_invalid_params' => '無適當的小圖參數',
'thumbnail_dest_directory' => '無法度建立目標的目錄',
'thumbnail_image-type' => '圖相的類型無支援',
'thumbnail_gd-library' => '未完成的GD設定: 欠功能$1',
'thumbnail_image-missing' => '檔案敢若無看：$1',

# Special:Import
'import' => 'Su-ji̍p ia̍h',
'importinterwiki' => '跨 wiki 輸入',
'import-interwiki-text' => '選一个Wiki佮頁標題來輸入，
修訂本日期佮修改者名稱會保留，
所有跨Wiki的輸入動作會記佇[[Special:Log/import|輸入記錄]]。',
'import-interwiki-source' => '來源Wiki/頁面：',
'import-interwiki-history' => '拷備這頁的所有修訂本',
'import-interwiki-templates' => '包括所有的枋模',
'import-interwiki-submit' => '輸入',
'import-interwiki-namespace' => '目標名空間：',
'import-upload-filename' => '檔案名稱：',
'import-comment' => '註釋：',
'importtext' => '請佇來源的Wiki，使用[[Special:Export|輸出功能]]輸出檔案，
匟入去你的電腦了，閣共上載到遮。',
'importstart' => '當咧輸入頁面...',
'import-revision-count' => '$1份{{PLURAL:$1|修訂本|修訂本}}',
'importnopages' => '無頁通輸入。',
'imported-log-entries' => '輸入的$1 {{PLURAL:$1|log entry|記錄條目}}。',
'importfailed' => '輸入失敗: <nowiki>$1</nowiki>',
'importunknownsource' => '毋捌的輸入來源類型',
'importcantopen' => '無法度拍開輸入的檔案',
'importbadinterwiki' => '毋著的跨Wiki連結',
'importnotext' => '空的抑是無字',
'importsuccess' => '輸入完成！',
'importhistoryconflict' => '有衝突的修訂本佇咧（可能是進前捌輸入這頁）',
'importnosources' => '毋捌定義跨Wiki輸入的來源，而且直接上載歷史是袂當用。',
'importnofile' => '無輸入的檔案有上載。',
'importuploaderrorsize' => '輸入的檔案上載失敗，
檔案大過通上載的量。',
'importuploaderrorpartial' => '上載輸入檔案已經失敗。
只有部份檔案已經上載。',
'importuploaderrortemp' => '上載輸入檔案已經失敗。
臨時檔案鋏仔已經無去。',
'import-parse-failure' => 'XML輸入語法失敗',
'import-noarticle' => '無頁通輸入！',
'import-nonewrevisions' => '所有的修訂本進前已經輸入了。',
'xml-error-string' => '$1佇$2行，$3欄 (位元組$4)：$5',
'import-upload' => '上載XML資料',
'import-token-mismatch' => '失去連線的資料，
請閣試一擺。',
'import-invalid-interwiki' => '袂使對所指定的Wiki輸入。',
'import-error-edit' => '"$1"這頁無輸入，因為你無允准通共改。',
'import-error-create' => '"$1"這頁無輸入，因為你無允准通建立。',
'import-error-interwiki' => '"$1"這頁無輸入，因為彼个名稱已經保留予外部連結（跨Wiki連結）。',
'import-error-special' => '無共頁面"$1"輸入，因為名稱是留予名空間，袂當用佇頁面。',
'import-error-invalid' => '無輸入"$1"這頁，因為名稱無適合。',

# Import log
'importlogpage' => '輸入記錄',
'importlogpagetext' => '管理上的輸入別个wiki頁面佮編輯歷史。',
'import-logentry-upload' => '透過上載檔案輸入[[$1]]',
'import-logentry-upload-detail' => '$1份{{PLURAL:$1|修訂本|修訂本}}',
'import-logentry-interwiki' => '跨Wiki的$1',
'import-logentry-interwiki-detail' => '對$2來的$1份{{PLURAL:$1|修訂本|修訂本}}',

# JavaScriptTest
'javascripttest' => 'JavaScript試用',
'javascripttest-disabled' => '這个功能袂當用。',
'javascripttest-title' => '試用$1',
'javascripttest-pagetext-noframework' => '這頁市保留予試用JavaScrips。',
'javascripttest-pagetext-unknownframework' => '未知的試用架構"$1"。',
'javascripttest-pagetext-frameworks' => '請選下跤的一个試用架構：$1',
'javascripttest-pagetext-skins' => '揣一个外皮來試：',
'javascripttest-qunit-intro' => '看mediawiki.org的[$1 試用說明]',
'javascripttest-qunit-heading' => 'MediaWiki JavaScript QUnit 試驗套件',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Lí chit ê iōng-chiá ê ia̍h',
'tooltip-pt-anonuserpage' => '你編輯本站所用IP所對應的用者頁',
'tooltip-pt-mytalk' => 'Lí ê thó-lūn ia̍h',
'tooltip-pt-anontalk' => '這个IP地址為著編輯所做的討論',
'tooltip-pt-preferences' => 'Lí ê siat-tēng',
'tooltip-pt-watchlist' => '你監視的頁有改過的列表',
'tooltip-pt-mycontris' => 'Lí ê kòng-hiàn lia̍t-toaⁿ',
'tooltip-pt-login' => 'Hi-bāng lí teng-ji̍p; m̄-ko bô kiông-chè',
'tooltip-pt-anonlogin' => 'Hi-bāng lí teng-ji̍p; m̄-ko bô kiông-chè',
'tooltip-pt-logout' => 'Teng-chhut',
'tooltip-ca-talk' => 'Loē-iông ê thó-lūn',
'tooltip-ca-edit' => 'Lí ē-sái kái chit ia̍h. Beh chhûn chìn-chiân, chhiáⁿ chhi̍h  sing-khoàⁿ-māi ê liú-á',
'tooltip-ca-addsection' => '寫新的一段',
'tooltip-ca-viewsource' => 'Chit ia̍h pó-hō͘ tiâu leh.
Lí ē-sái khoàⁿ i ê goân-sú-bé.',
'tooltip-ca-history' => 'Chit ia̍h ê chá-chêng pán-pún',
'tooltip-ca-protect' => '保護這頁',
'tooltip-ca-unprotect' => '改這頁的保護',
'tooltip-ca-delete' => 'Thâi chit ia̍h',
'tooltip-ca-undelete' => '恢復這頁予人刣掉進前的編輯',
'tooltip-ca-move' => '徙這頁',
'tooltip-ca-watch' => '共這頁加入你的監視單',
'tooltip-ca-unwatch' => 'Lí ê kàm-sī-toaⁿ soá tiàu chit ia̍h.',
'tooltip-search' => 'Chhoé {{SITENAME}}',
'tooltip-search-go' => '跳去佮這完全仝名的頁',
'tooltip-search-fulltext' => 'Chhoé ū chia-ê jī ê ia̍h',
'tooltip-p-logo' => 'Khì thâu-ia̍h',
'tooltip-n-mainpage' => 'Khì thâu-ia̍h',
'tooltip-n-mainpage-description' => 'Khì thâu-ia̍h',
'tooltip-n-portal' => 'Koan-hē chit ê sū-kang, lí ē-tāng chò siáⁿ, khì tó-ūi chhoé',
'tooltip-n-currentevents' => 'Thê-kiong hiān-sî sin-bûn ê poē-kéng chu-liāu',
'tooltip-n-recentchanges' => 'Choè-kīn tī wiki ū kái--koè ê lia̍t-toaⁿ',
'tooltip-n-randompage' => 'Chhìn-chhái hian chi̍t ia̍h',
'tooltip-n-help' => 'Beh chhoé ê só͘-chāi',
'tooltip-t-whatlinkshere' => 'Só͘-ū liân kàu chia ê liat-toaⁿ',
'tooltip-t-recentchangeslinked' => 'Liân kàu chit ia̍h koh choè-kīn ū kái koè--ê',
'tooltip-feed-rss' => '訂看這頁的RSS',
'tooltip-feed-atom' => '這頁有Atom訂看的',
'tooltip-t-contributions' => 'Khoàⁿ chit ê iōng-chiá ê kòng-hiàn lia̍t-toaⁿ',
'tooltip-t-emailuser' => '寄一張e-mail予這个用者',
'tooltip-t-upload' => 'Í-keng sàng chiūⁿ-bāng ê tóng-àn',
'tooltip-t-specialpages' => 'Só͘-ū te̍k-sû-ia̍h ê lia̍t-toaⁿ',
'tooltip-t-print' => 'Chit ia̍h ê ìn-soat pán-pún',
'tooltip-t-permalink' => 'Chi̍t ia̍h kái--koè pán-pún ê éng-kiú liân-kiat',
'tooltip-ca-nstab-main' => 'khoàⁿ ia̍h ê loē-iông',
'tooltip-ca-nstab-user' => 'Khoàⁿ iōng-chiá ê Ia̍h',
'tooltip-ca-nstab-media' => '看媒體頁',
'tooltip-ca-nstab-special' => '這是一篇特殊頁，你袂當編輯。',
'tooltip-ca-nstab-project' => '看事工頁',
'tooltip-ca-nstab-image' => 'Khoàⁿ tóng-àn ia̍h',
'tooltip-ca-nstab-mediawiki' => '看系統訊息',
'tooltip-ca-nstab-template' => '看枋模',
'tooltip-ca-nstab-help' => '看幫贊頁',
'tooltip-ca-nstab-category' => 'Khoàⁿ lūi-pia̍t ia̍h',
'tooltip-minoredit' => '共這做一个小修改記號',
'tooltip-save' => 'Pó-chhûn lí chò ê kái-piàn',
'tooltip-preview' => 'Chhiáⁿ tī pó-chûn chìn-chêng,  sian khoàⁿ lí chò ê kái-piàn !',
'tooltip-diff' => '顯示你對這頁所改的',
'tooltip-compareselectedversions' => '看選擇的兩个修訂本差偌濟',
'tooltip-watch' => '共這頁加入你的監視單',
'tooltip-watchlistedit-normal-submit' => '莫監視',
'tooltip-watchlistedit-raw-submit' => '改監視單',
'tooltip-recreate' => '重建立頁，就算講伊欲予人刣掉',
'tooltip-upload' => '開始上載',
'tooltip-rollback' => 'Ji̍h "Hoê-choán" ē-sái thè tńg-khì téng-chi̍t-ê kái ê lâng ê ia̍h.',
'tooltip-undo' => '『取消』會使回轉這个編輯而且會使先看覓編輯的結果，閣會使佇概要加入原因。',
'tooltip-preferences-save' => '保存設定',
'tooltip-summary' => 'Siá chi̍t-ê kán-tan soat-bêng',

# Metadata
'notacceptable' => '網站伺服器無提供你客戶端通讀的資料格式。',

# Attribution
'anonymous' => '{{SITENAME}} ê {{PLURAL:$1|ê bô kì-miâ ê iōng-chiá|ê bô kì-miâ ê iōng-chiá}} .',
'siteuser' => '{{SITENAME}} iōng-chiá $1',
'anonuser' => '{{SITENAME}}的無名氏用者 $1',
'lastmodifiedatby' => '這頁頂回佇$1 $2予$3改過。',
'othercontribs' => 'Kin-kù $1 ê kòng-hiàn.',
'others' => '其他',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|iōng-chiá|iōng-chiá}} $1',
'anonusers' => '{{SITENAME}}無名氏{{PLURAL:$2|用者|用者}}$1',
'creditspage' => '頁面感謝',
'nocredits' => '這頁無感謝名單的信息。',

# Spam protection
'spamprotectiontitle' => '垃圾過濾器',
'spamprotectiontext' => '你欲保存的文字予垃圾過濾器阻擋。
這可能是因為有一个連接是連到其他的黑名單網站。',
'spamprotectionmatch' => '下跤的文字引起垃圾過濾器：$1',
'spambot_username' => 'MediaWiki 廣告清除',
'spam_reverting' => '恢復到頂一个無連結到$1的修訂本',
'spam_blanking' => '所有有連結到$1的修訂本，清掉當中',

# Info page
'pageinfo-title' => '"$1"的資訊',
'pageinfo-header-edits' => '修改',
'pageinfo-header-watchlist' => '監視單',
'pageinfo-header-views' => '看',
'pageinfo-subjectpage' => '頁',
'pageinfo-talkpage' => '討論頁',
'pageinfo-watchers' => '監視的人數',
'pageinfo-edits' => '編輯幾擺',
'pageinfo-authors' => '幾个作者',
'pageinfo-views' => '看幾擺',
'pageinfo-viewsperedit' => '看每一个編輯',

# Patrolling
'markaspatrolleddiff' => 'Phiau-sī sûn--kòe',
'markaspatrolledtext' => 'kā chit ia̍h kì-hō chò sûn--koè = 共這頁記號做巡過',
'markedaspatrolled' => 'kì-hō chò sûn--koè = 記號做巡過',
'markedaspatrolledtext' => 'Soán-te̍k  ê siu-tēng-pún [[:$1]]  í-keng kì-hō chò sûn--kòe.',
'rcpatroldisabled' => '巡最近改的功能已經關掉',
'rcpatroldisabledtext' => '巡最近改過的功能這馬停用。',
'markedaspatrollederror' => '袂使記號做巡查過',
'markedaspatrollederrortext' => '你愛指定一个修訂本是巡過的',
'markedaspatrollederror-noautopatrol' => '你袂當記號你家己改的修訂本是巡過的',

# Patrol log
'patrol-log-page' => '巡查記錄',
'patrol-log-header' => '這是一个已經巡查過的修訂本記錄',
'log-show-hide-patrol' => '$1巡查記錄',

# Image deletion
'deletedrevision' => 'Kū siu-tēng-pún $1 thâi-tiāu ā.',
'filedeleteerror-short' => '欲刣掉檔案的時陣有錯誤：$1',
'filedeleteerror-long' => '佇欲刣掉檔案的時陣有錯誤：

$1',
'filedelete-missing' => '"$1"這个檔案袂當刣掉，無彼个檔案。',
'filedelete-old-unregistered' => '指定的"$1"檔案修訂本無佇資料庫內底。',
'filedelete-current-unregistered' => '指定的"$1"檔案無佇資料庫內底。',
'filedelete-archive-read-only' => '佇網站伺服器的存檔目錄 "$1" 袂當寫入去。',

# Browsing diffs
'previousdiff' => '← Khì chêng 1 ê siu-kái',
'nextdiff' => 'Khì āu 1 ê siu-kái →',

# Media information
'mediawarning' => "'''注意'''：這款檔案類型可能有惡意的資料。
執行彼，可能對你的系統帶來危害。",
'imagemaxsize' => "Iáⁿ-siōng toā-sè ê hān-chè:<br />''(ēng tī tóng-àn soeh-bêng-ia̍h)''",
'thumbsize' => 'Sok-tô· (thumbnail) jōa tōa tiuⁿ:',
'widthheightpage' => '$1 × $2， {{PLURAL:$3|頁|頁}}',
'file-info' => '檔案大細：$1，MIME類型：$2',
'file-info-size' => '$1 × $2  像素，檔案大細：$3，MIME類型：$4',
'file-info-size-pages' => '$1 × $2 像素，檔案大細: $3，檔案類型: $4, $5 {{PLURAL:$5|頁|頁}}',
'file-nohires' => 'Bô khah koân ê kái-sek-tō͘.',
'svg-long-desc' => 'SVG 檔案，一般的長闊：$1 × $2 像素，檔案大小：$3',
'show-big-image' => '檔案解析度',
'show-big-image-preview' => '這張先看覓的大細：$1',
'show-big-image-other' => '其他{{PLURAL:$2|解析度|解析度}}：$1。',
'show-big-image-size' => '$1 × $2像素',
'file-info-gif-looped' => '循環',
'file-info-gif-frames' => '$1{{PLURAL:$1|幅|幅}}',
'file-info-png-looped' => '循環',
'file-info-png-repeat' => '播送$1 {{PLURAL:$1|擺|擺}}',
'file-info-png-frames' => '$1{{PLURAL:$1|幅|幅}}',

# Special:NewFiles
'newimages' => 'Sin iáⁿ-siōng oē-lóng',
'imagelisttext' => "Í-hā sī '''$1''' {{PLURAL:$1|tiuⁿ|tiuⁿ}} iáⁿ-siōng ê lia̍t-toaⁿ, chiàu $2 pâi-lia̍t.",
'newimages-summary' => '這个特殊頁顯示頂一个上載的檔案。',
'newimages-legend' => '過濾器',
'newimages-label' => '檔案名稱（抑伊的部份名稱）',
'showhidebots' => '($1機器人)',
'noimages' => '無物件通看。',
'ilsubmit' => 'Kiám-sek',
'bydate' => 'chiàu ji̍t-kî',
'sp-newimages-showfrom' => ' 顯示$2, $1後尾的新檔案',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1秒|$1秒}}',
'minutes' => '{{PLURAL:$1|$1分鐘|$1分鐘}}',
'hours' => '{{PLURAL:$1|$1點鐘|$1點鐘}}',
'days' => '{{PLURAL:$1|$1工|$1工}}',
'ago' => '$1進前',

# Bad image list
'bad_image_list' => '規格照下跤：

只有（以 * 做頭）排列出的項目會處理。
每一逝的第一个連結是bad file的連結。
了後仝一逝後壁的連結會看做是例外，也就是彼个檔案會使佇佗位的頁面通顯示。',

# Metadata
'metadata' => '元資訊',
'metadata-help' => '這个檔案有其他的資訊，可能是翕相機抑是掃描器寫的，
若檔案有人改過，一寡說明就無完全反應改過的檔案',
'metadata-expand' => 'Hián-sī iù-chiat',
'metadata-collapse' => 'Am iù-chiat',
'metadata-fields' => '這个信息所排來出的影相元資料，是會佇欲顯示元資料表的時陣顯示。
其他的元資料是先藏起來。
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
'exif-imagewidth' => '闊',
'exif-imagelength' => '懸',
'exif-bitspersample' => '代表每一个像素色水的位元數',
'exif-compression' => '壓縮方式',
'exif-photometricinterpretation' => '像素合成',
'exif-orientation' => '方向',
'exif-samplesperpixel' => '像素數目',
'exif-planarconfiguration' => '資料排列',
'exif-ycbcrsubsampling' => '黃色對洋紅的二次抽樣比率',
'exif-ycbcrpositioning' => 'Y佮C定位',
'exif-xresolution' => '水平方向數目',
'exif-yresolution' => '直的方向數目',
'exif-stripoffsets' => '圖像資料區',
'exif-rowsperstrip' => '每帶幾逝',
'exif-stripbytecounts' => '每一條壓縮帶的位元組',
'exif-jpeginterchangeformat' => 'JPEG SOI 偏徙',
'exif-jpeginterchangeformatlength' => 'JPEG 資料的位元組',
'exif-whitepoint' => '白點的色度',
'exif-primarychromaticities' => '主要的色度',
'exif-ycbcrcoefficients' => '色水空間轉換矩陣係數',
'exif-referenceblackwhite' => '烏白參照數值對',
'exif-datetime' => '檔案改的日期佮時間',
'exif-imagedescription' => '影相標題',
'exif-make' => '相機製造商',
'exif-model' => '相機款式',
'exif-software' => '使用的軟體',
'exif-artist' => '著作者',
'exif-copyright' => '著作權所有人',
'exif-exifversion' => 'Exif 版本',
'exif-flashpixversion' => '支援的 Flashpix 版本',
'exif-colorspace' => '顏色空間',
'exif-componentsconfiguration' => '每一个成分的意思',
'exif-compressedbitsperpixel' => '圖像的壓縮模式',
'exif-pixelydimension' => '圖像闊度',
'exif-pixelxdimension' => '圖像懸度',
'exif-usercomment' => '用者的說明',
'exif-relatedsoundfile' => '相關的聲音檔案',
'exif-datetimeoriginal' => '產生資料的時間',
'exif-datetimedigitized' => '數位化的時間',
'exif-subsectime' => '日期分秒',
'exif-subsectimeoriginal' => '原本的日期時間秒',
'exif-subsectimedigitized' => '數位化的時間秒',
'exif-exposuretime' => '曝光時間',
'exif-exposuretime-format' => '$1 秒 （$2）',
'exif-fnumber' => '光圈（F數值）',
'exif-exposureprogram' => '曝光模式',
'exif-spectralsensitivity' => '感光',
'exif-isospeedratings' => 'ISO 速率',
'exif-shutterspeedvalue' => 'APEX快門速度',
'exif-aperturevalue' => 'APEX光圈',
'exif-brightnessvalue' => 'APEX光度',
'exif-exposurebiasvalue' => '曝光補償',
'exif-maxaperturevalue' => '上大陸地光圈',
'exif-subjectdistance' => '物距',
'exif-meteringmode' => '測量模式',
'exif-lightsource' => '光源',
'exif-flash' => '閃光燈',
'exif-focallength' => '焦距',
'exif-subjectarea' => '主體區域',
'exif-flashenergy' => '閃光燈強度',
'exif-focalplanexresolution' => 'X軸焦面的解析度',
'exif-focalplaneyresolution' => 'Y軸焦面的解析度',
'exif-focalplaneresolutionunit' => '焦平面的解析度單位',
'exif-subjectlocation' => '主題位置',
'exif-exposureindex' => '曝光指數',
'exif-sensingmethod' => '感光模式',
'exif-filesource' => '檔案源',
'exif-scenetype' => '場景類型',
'exif-customrendered' => '自訂的圖像處理',
'exif-exposuremode' => '曝光模式',
'exif-whitebalance' => '白平衡',
'exif-digitalzoomratio' => '數字變焦比率',
'exif-focallengthin35mmfilm' => '35公厘的底片焦距',
'exif-scenecapturetype' => '情景的攝影類型',
'exif-gaincontrol' => '場景控制',
'exif-contrast' => '對比度',
'exif-saturation' => '飽水度',
'exif-sharpness' => '銳化',
'exif-devicesettingdescription' => '設定裝置的說明',
'exif-subjectdistancerange' => '主體距離範圍',
'exif-imageuniqueid' => '獨一的影像編碼',
'exif-gpsversionid' => 'GPS 標籤（tag）版本',
'exif-gpslatituderef' => '北緯抑南緯',
'exif-gpslatitude' => '緯度',
'exif-gpslongituderef' => '東經抑西經',
'exif-gpslongitude' => '經度',
'exif-gpsaltituderef' => '海拔正負參照',
'exif-gpsaltitude' => '海拔',
'exif-gpstimestamp' => 'GPS 時間（原子時鐘）',
'exif-gpssatellites' => '測量用的衛星',
'exif-gpsstatus' => '接收器狀態',
'exif-gpsmeasuremode' => '測量模式',
'exif-gpsdop' => '測量精度',
'exif-gpsspeedref' => '速度單位',
'exif-gpsspeed' => 'GPS 接收器速度',
'exif-gpstrackref' => '運動方位參照',
'exif-gpstrack' => '運動方位',
'exif-gpsimgdirectionref' => '圖像方位參照',
'exif-gpsimgdirection' => '圖像方位',
'exif-gpsmapdatum' => '使用地理測繪數據',
'exif-gpsdestlatituderef' => '目標緯度參照',
'exif-gpsdestlatitude' => '目標緯度',
'exif-gpsdestlongituderef' => '目標經度參照',
'exif-gpsdestlongitude' => '目標經度',
'exif-gpsdestbearingref' => '目標方位參照',
'exif-gpsdestbearing' => '目標方位',
'exif-gpsdestdistanceref' => '目標距離參照',
'exif-gpsdestdistance' => '目標距離',
'exif-gpsprocessingmethod' => 'GPS 處理方法名稱',
'exif-gpsareainformation' => 'GPS 區域名稱',
'exif-gpsdatestamp' => 'GPS 日期',
'exif-gpsdifferential' => 'GPS 偏差修正',
'exif-jpegfilecomment' => 'JPEG 檔案註解',
'exif-keywords' => '關鍵字',
'exif-worldregioncreated' => '翕的所在',
'exif-countrycreated' => '翕的國家',
'exif-countrycodecreated' => '翕的國家編碼',
'exif-provinceorstatecreated' => '翕的省抑是州',
'exif-citycreated' => '翕的都市',
'exif-sublocationcreated' => '翕的行政區',
'exif-worldregiondest' => '顯示所在',
'exif-countrydest' => '顯示國家',
'exif-countrycodedest' => '顯示國家編碼',
'exif-provinceorstatedest' => '顯示省抑州',
'exif-citydest' => '顯示都市',
'exif-sublocationdest' => '顯示行政區',
'exif-objectname' => '標題簡稱',
'exif-specialinstructions' => '特別的說明',
'exif-headline' => '標題',
'exif-credit' => '署名/提供者',
'exif-source' => '來源',
'exif-editstatus' => '圖像的編輯狀態',
'exif-urgency' => '緊急性',
'exif-fixtureidentifier' => '配備名稱',
'exif-locationdest' => '位置說明',
'exif-locationdestcode' => '位置的編碼',
'exif-objectcycle' => '媒體的時間',
'exif-contact' => '聯絡資料',
'exif-writer' => '作者',
'exif-languagecode' => '話語',
'exif-iimversion' => 'IIM版本',
'exif-iimcategory' => '類別',
'exif-iimsupplementalcategory' => '補充的分類',
'exif-datetimeexpires' => '佇這日以後莫用',
'exif-datetimereleased' => '發表佇',
'exif-originaltransmissionref' => '原傳輸位置的編碼',
'exif-identifier' => '標識符號',
'exif-lens' => '用的鏡頭',
'exif-serialnumber' => '相機的號碼',
'exif-cameraownername' => '相機的主人',
'exif-label' => '標籤',
'exif-datetimemetadata' => '頂回改元數據的日期',
'exif-nickname' => '非正式的圖像名稱',
'exif-rating' => '評分（上懸5分）',
'exif-rightscertificate' => '權利管理證書',
'exif-copyrighted' => '版權狀況',
'exif-copyrightowner' => '版權所有人',
'exif-usageterms' => '使用條款',
'exif-webstatement' => '網上的版權說明',
'exif-originaldocumentid' => '原本文件的唯一鑑識碼',
'exif-licenseurl' => '版權授權的連結',
'exif-morepermissionsurl' => '其他的許可信息',
'exif-attributionurl' => '利用這个作品的時陣，請連結到',
'exif-preferredattributionname' => '利用這个作品的時陣，請共掛名',
'exif-pngfilecomment' => 'PNG檔案註解',
'exif-disclaimer' => '無負責聲明',
'exif-contentwarning' => '內容警告',
'exif-giffilecomment' => 'GIF檔案註解',
'exif-intellectualgenre' => '項目的類型',
'exif-subjectnewscode' => '主題代碼',
'exif-scenecode' => 'IPTC現場代碼',
'exif-event' => '事件的描述',
'exif-organisationinimage' => '組織的描述',
'exif-personinimage' => '所描述的人',
'exif-originalimageheight' => '佇剪裁進前的懸度',
'exif-originalimagewidth' => '佇剪裁進前的闊度',

# EXIF attributes
'exif-compression-1' => '無壓縮',
'exif-compression-2' => 'CCITT第3組一維修改霍夫曼進程長度編碼',
'exif-compression-3' => 'CCITT第3組傳真編碼',
'exif-compression-4' => 'CCITT第4組傳真編碼',

'exif-copyrighted-true' => '版權保護',
'exif-copyrighted-false' => '公共領域',

'exif-unknowndate' => '毋知日期',

'exif-orientation-1' => '一般',
'exif-orientation-2' => '兩爿相換',
'exif-orientation-3' => '踅180度',
'exif-orientation-4' => '面頂下跤相換',
'exif-orientation-5' => '倒踅90度，閣面頂下跤相換',
'exif-orientation-6' => '倒踅90度',
'exif-orientation-7' => '正踅90度，閣面頂下跤相換',
'exif-orientation-8' => '正踅90度',

'exif-planarconfiguration-1' => '矮肥格式',
'exif-planarconfiguration-2' => '平的格式',

'exif-colorspace-65535' => '色水無校正過',

'exif-componentsconfiguration-0' => '無彼个',

'exif-exposureprogram-0' => '無定義',
'exif-exposureprogram-1' => '說明書',
'exif-exposureprogram-2' => '一般方式',
'exif-exposureprogram-3' => '光圈優先',
'exif-exposureprogram-4' => '快門優先',
'exif-exposureprogram-5' => '藝術模式（景深優先）',
'exif-exposureprogram-6' => '運動模式（快門速度優先）',
'exif-exposureprogram-7' => '肖像模式（適合背景佇焦距以外的近距離攝影）',
'exif-exposureprogram-8' => '風景模式（適合背景佇焦距內的風景攝影）',

'exif-subjectdistance-value' => '$1公尺',

'exif-meteringmode-0' => '無清楚',
'exif-meteringmode-1' => '平均',
'exif-meteringmode-2' => '中心加權平均',
'exif-meteringmode-3' => '點',
'exif-meteringmode-4' => '多點',
'exif-meteringmode-5' => '模式',
'exif-meteringmode-6' => '局部',
'exif-meteringmode-255' => '其他',

'exif-lightsource-0' => '毋知',
'exif-lightsource-1' => '日光',
'exif-lightsource-2' => '螢光燈',
'exif-lightsource-3' => '電火球',
'exif-lightsource-4' => '閃光燈',
'exif-lightsource-9' => '好天',
'exif-lightsource-10' => '烏雲',
'exif-lightsource-11' => '深色有影',
'exif-lightsource-12' => '日光螢光燈（色溫 D 5700    7100K）',
'exif-lightsource-13' => '溫白色螢光燈（N 4600    5400K）',
'exif-lightsource-14' => '冷白色螢光燈（W 3900    4500K）',
'exif-lightsource-15' => '白色螢光 （WW 3200    3700K）',
'exif-lightsource-17' => '標準燈光A',
'exif-lightsource-18' => '標準燈光B',
'exif-lightsource-19' => '標準燈光C',
'exif-lightsource-24' => 'ISO攝影棚鎢燈',
'exif-lightsource-255' => '其他光源',

# Flash modes
'exif-flash-fired-0' => '閃光燈無閃',
'exif-flash-fired-1' => '有閃閃光燈',
'exif-flash-return-0' => '無頻閃觀測器功能',
'exif-flash-return-2' => '頻閃觀測器無測著光',
'exif-flash-return-3' => '頻閃觀測器有測著光',
'exif-flash-mode-1' => '一定閃閃光燈',
'exif-flash-mode-2' => '一定無閃閃光燈',
'exif-flash-mode-3' => '自動模式',
'exif-flash-function-1' => '無閃光燈功能',
'exif-flash-redeye-1' => '紅眼消退模式',

'exif-focalplaneresolutionunit-2' => '英吋',

'exif-sensingmethod-1' => '無定義',
'exif-sensingmethod-2' => '一塊彩色區域偵測器',
'exif-sensingmethod-3' => '兩塊彩色區域偵測器',
'exif-sensingmethod-4' => '三塊彩色區域偵測器',
'exif-sensingmethod-5' => '連續彩色區域偵測器',
'exif-sensingmethod-7' => '三線偵測器',
'exif-sensingmethod-8' => '連續彩色線性偵測器',

'exif-filesource-3' => '數位相機',

'exif-scenetype-1' => '直接翕相相片',

'exif-customrendered-0' => '一般的方法',
'exif-customrendered-1' => '家己設計的方法',

'exif-exposuremode-0' => '自動曝光',
'exif-exposuremode-1' => '手動曝光',
'exif-exposuremode-2' => '自動曝光感測調整',

'exif-whitebalance-0' => '自動白平衡',
'exif-whitebalance-1' => '手動白平衡',

'exif-scenecapturetype-0' => '標準',
'exif-scenecapturetype-1' => '風景',
'exif-scenecapturetype-2' => '肖像',
'exif-scenecapturetype-3' => '夜景',

'exif-gaincontrol-0' => '無',
'exif-gaincontrol-1' => '加一屑',
'exif-gaincontrol-2' => '加較濟',
'exif-gaincontrol-3' => '減一屑',
'exif-gaincontrol-4' => '減較濟',

'exif-contrast-0' => '一般',
'exif-contrast-1' => '柔',
'exif-contrast-2' => '利',

'exif-saturation-0' => '一般',
'exif-saturation-1' => '低飽滿度',
'exif-saturation-2' => '高飽滿度',

'exif-sharpness-0' => '一般',
'exif-sharpness-1' => '柔',
'exif-sharpness-2' => '利',

'exif-subjectdistancerange-0' => '無清楚',
'exif-subjectdistancerange-1' => '倚咧',
'exif-subjectdistancerange-2' => '近看',
'exif-subjectdistancerange-3' => '遠看',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => '北緯',
'exif-gpslatitude-s' => '南緯',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => '東經',
'exif-gpslongitude-w' => '西經',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '海拔$1 {{PLURAL:$1|公尺|公尺}}',
'exif-gpsaltitude-below-sealevel' => '海拔負1{{PLURAL:$1|公尺|公尺}}',

'exif-gpsstatus-a' => '測量當中',
'exif-gpsstatus-v' => '互相測量',

'exif-gpsmeasuremode-2' => '二維測量',
'exif-gpsmeasuremode-3' => '三維測量',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => '每點鐘的公里數',
'exif-gpsspeed-m' => '每點鐘的英里數',
'exif-gpsspeed-n' => '每點鐘的海里數（節）',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => '公里',
'exif-gpsdestdistance-m' => '英里',
'exif-gpsdestdistance-n' => '海里',

'exif-gpsdop-excellent' => '優（$1）',
'exif-gpsdop-good' => '良（$1）',
'exif-gpsdop-moderate' => '中度（$1）',
'exif-gpsdop-fair' => '一般（$1）',
'exif-gpsdop-poor' => '差（$1）',

'exif-objectcycle-a' => '只有早起',
'exif-objectcycle-p' => '只有暗時',
'exif-objectcycle-b' => '通早起佮暗時',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '真正的方位',
'exif-gpsdirection-m' => '地磁的方位',

'exif-ycbcrpositioning-1' => '靠中央',
'exif-ycbcrpositioning-2' => '聯合選址',

'exif-dc-contributor' => '貢獻者',
'exif-dc-coverage' => '媒體的時間抑空間性',
'exif-dc-date' => '日期',
'exif-dc-publisher' => '出版者',
'exif-dc-relation' => '相關的媒體',
'exif-dc-rights' => '權利',
'exif-dc-source' => '媒體的來源',
'exif-dc-type' => '媒體的類型',

'exif-rating-rejected' => '拒絕',

'exif-isospeedratings-overflow' => '大過65535',

'exif-iimcategory-ace' => '藝術、文化佮娛樂',
'exif-iimcategory-clj' => ' 犯罪佮法律',
'exif-iimcategory-dis' => '災害佮意外',
'exif-iimcategory-fin' => '經濟佮商業',
'exif-iimcategory-edu' => '教育',
'exif-iimcategory-evn' => '環境',
'exif-iimcategory-hth' => '健康',
'exif-iimcategory-hum' => '人類的利益',
'exif-iimcategory-lab' => '勞工',
'exif-iimcategory-lif' => '生活佮休閒',
'exif-iimcategory-pol' => '政治',
'exif-iimcategory-rel' => '宗教佮信仰',
'exif-iimcategory-sci' => '科學佮技術',
'exif-iimcategory-soi' => '社會議題',
'exif-iimcategory-spo' => '運動',
'exif-iimcategory-war' => '戰爭、衝突佮動亂',
'exif-iimcategory-wea' => '氣候',

'exif-urgency-normal' => '一般（$1）',
'exif-urgency-low' => '低（$1）',
'exif-urgency-high' => '懸（$1）',
'exif-urgency-other' => '用者定義的重要性（$1）',

# External editor support
'edit-externally' => 'Iōng gōa-pō· èng-iōng nńg-thé pian-chi̍p chit-ê tóng-àn',
'edit-externally-help' => '(Khoàⁿ [//www.mediawiki.org/wiki/Manual:External_editors siat-tēng soat-bêng] ê chu-liāu.)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'choân-pō͘',
'namespacesall' => 'choân-pō·',
'monthsall' => 'choân-pō͘',
'limitall' => '全部',

# E-mail address confirmation
'confirmemail' => 'Khak-jīn e-mail chū-chí',
'confirmemail_noemail' => '佇你的[[Special:Preferences|設定]]，你無設一个會用得的電子批地址。',
'confirmemail_text' => 'Sú-iōng e-mail kong-lêng chìn-chêng tio̍h seng khak-jīn lí ê e-mail chū-chí ū-hāu. Chhi̍h ē-pêng hit-ê liú-á thang kià 1 tiuⁿ khak-jīn phoe hō· lí. Hit tiuⁿ phoe lāi-bīn ū 1 ê te̍k-sû liân-kiat. Chhiáⁿ iōng liû-lám-khì khui lâi khoàⁿ, án-ne tō ē-tit khak-jīn lí ê chū-chí ū-hāu.',
'confirmemail_pending' => '確定的編碼已經用電子批寄予你，
若你才拄開你的口座，佇你欲閣愛新確定的編碼進前，你會使先等幾分鐘，等批送到。',
'confirmemail_send' => 'Kià khak-jīn phoe',
'confirmemail_sent' => 'Khak-jīn phoe kià chhut-khì ah.',
'confirmemail_oncreate' => '一个確認代碼已經送去你的電子批地址，
這个代碼無需要登入，毋過你若欲用著wiki的電子批功能，你就需要提供這个代碼。',
'confirmemail_sendfailed' => '{{SITENAME}}無法寄你確定資料的批，
請檢查你的電子批地址是毋是有怪字。

送批系統的退回通知：$1',
'confirmemail_invalid' => 'Bô-hāu ê khak-jīn pian-bé. Pian-bé khó-lêng í-keng kòe-kî.',
'confirmemail_needlogin' => '你愛$1去確定你的電子批地址。',
'confirmemail_success' => 'í ê e-mail chū-chí khak-jīn oân-sêng. Lí ē-sái teng-ji̍p, khai-sí hiáng-siū chit ê wiki.',
'confirmemail_loggedin' => 'Lí ê e-mail chū-chí í-keng khak-jīn ū-hāu.',
'confirmemail_error' => 'Pó-chûn khak-jīn chu-sìn ê sî-chūn hoat-seng būn-tê.',
'confirmemail_subject' => '{{SITENAME}} e-mail chu-chi khak-jin phoe',
'confirmemail_body' => 'Ū lâng (IP $1, tāi-khài sī lí pún-lâng) tī {{SITENAME}} ēng chit-ê e-mail chū-chí chù-chheh 1 ê kháu-chō "$2".

Chhiáⁿ khui ē-kha chit-ê liân-kiat, thang khak-jīn chit-ê kháu-chō si̍t-chāi sī lí ê:

$3

Nā-chún *m̄-sī* lí, chhiáⁿ khui ē-kha chit-ê liân-kiat,  chhú-siau khak-jīn ê e-mail.  

$5

Chit tiuⁿ phoe ê khak-jīn-bé ē chū-tōng tī $4 kòe-kî.',
'confirmemail_body_changed' => 'Ū lâng (IP $1, tāi-khài sī lí pún-lâng) tī {{SITENAME}} ēng chit-ê e-mail chū-chí chù-chheh 1 ê kháu-chō "$2".

Chhiáⁿ khui ē-kha chit-ê liân-kiat, thang khak-jīn chit-ê kháu-chō si̍t-chāi sī lí ê:

$3

Nā-chún *m̄-sī* lí, chhiáⁿ khui ē-kha chit-ê liân-kiat,  chhú-siau khak-jīn ê e-mail.  

$5

Chit tiuⁿ phoe ê khak-jīn-bé ē chū-tōng tī $4 kòe-kî.',
'confirmemail_body_set' => 'Ū lâng (IP $1, tāi-khài sī lí pún-lâng) tī {{SITENAME}} ēng chit-ê e-mail chū-chí chù-chheh 1 ê kháu-chō "$2".

Chhiáⁿ khui ē-kha chit-ê liân-kiat, thang khak-jīn chit-ê kháu-chō si̍t-chāi sī lí ê:

$3

Nā-chún *m̄-sī* lí, chhiáⁿ khui ē-kha chit-ê liân-kiat,  chhú-siau khak-jīn ê e-mail.  

$5

Chit tiuⁿ phoe ê khak-jīn-bé ē chū-tōng tī $4 kòe-kî.',
'confirmemail_invalidated' => '電子批的確認已經取消。',
'invalidateemail' => '取消電子批的確認。',

# Scary transclusion
'scarytranscludedisabled' => '[跨wiki的轉換代碼袂當用]',
'scarytranscludefailed' => '[讀$1模板失敗]',
'scarytranscludetoolong' => '[URL 地址傷長]',

# Delete conflict
'deletedwhileediting' => "'''注意'''：這頁佇你開始改了後，已經予人刣掉！",
'confirmrecreate' => "用者[[User:$1|$1]] ([[User talk:$1|talk]])佇你開始改這頁了後，刣掉這頁，原因是：
: ''$2''
請你確定欲閣建立這頁。",
'confirmrecreate-noreason' => '用者[[User:$1|$1]] （[[User talk:$1|討論]]）佇你開始改這頁了後，刣掉這頁。請你確定敢欲重建立這頁。',
'recreate' => '重做',

# action=purge
'confirm_purge_button' => '好矣',
'confirm-purge-top' => 'Kā chit ia̍h ê cache piàⁿ tiāu?',
'confirm-purge-bottom' => '清理一頁會共快取(cache)摒掉，閣一定顯示上新的修訂版本。',

# action=watch/unwatch
'confirm-watch-button' => '好',
'confirm-watch-top' => '共這頁加入去你的監視單？',
'confirm-unwatch-button' => '好',
'confirm-unwatch-top' => '共這頁對你的監視單徙走?',

# Multipage image navigation
'imgmultipageprev' => '前一頁',
'imgmultipagenext' => '後一頁',
'imgmultigo' => '來去',
'imgmultigoto' => '來去$1這頁',

# Table pager
'ascending_abbrev' => '細到大',
'descending_abbrev' => '大到細',
'table_pager_next' => 'Aū-chi̍t-ia̍h',
'table_pager_prev' => 'Téng-chi̍t-ia̍h',
'table_pager_first' => 'Thâu-chi̍t-ia̍h',
'table_pager_last' => 'Siāng-bóe-ia̍h',
'table_pager_limit' => 'Múi 1 ia̍h hián-sī $1 hāng',
'table_pager_limit_label' => '每頁的項目：',
'table_pager_limit_submit' => 'Lâi-khì',
'table_pager_empty' => '無結果',

# Auto-summaries
'autosumm-blank' => 'Kā ia̍h ê loē-iông the̍h tiāu',
'autosumm-replace' => '用"$1"共內容換掉',
'autoredircomment' => 'Choán khì [[$1]]',
'autosumm-new' => 'Sin ia̍h: $1...',

# Live preview
'livepreview-loading' => '當咧讀',
'livepreview-ready' => '讀....好矣！',
'livepreview-failed' => 'Live先看覓失敗!
試一般的先看覓。',
'livepreview-error' => '連接失敗: $1 "$2"，
試一般的先看覓。',

# Friendlier slave lag warnings
'lag-warn-normal' => '佇過去$1{{PLURAL:$1|秒|秒}}新改的，可能無寫佇這个清單。',
'lag-warn-high' => '因為資料庫的過度延遲，過去$1{{PLURAL:$1|秒|秒}}內的修改無一定會顯示佇清單內底。',

# Watchlist editor
'watchlistedit-numitems' => 'Lí ê kàm-sī-toaⁿ ū {{PLURAL:$1|$1 ia̍h|$1 ia̍h}}, thó-lūn-ia̍h bô sǹg chāi-lāi.',
'watchlistedit-noitems' => '你的監視單無半項。',
'watchlistedit-normal-title' => '改監視單',
'watchlistedit-normal-legend' => '共文章標題對監視單徙走',
'watchlistedit-normal-explain' => '你監視的文章標題顯示佇下跤。若欲徙走一个標題，點擊伊邊仔的框仔，閣點擊「{{int:Watchlistedit-normal-submit}}」。你嘛會當[[Special:EditWatchlist/raw|編輯原始監視清單]]。',
'watchlistedit-normal-submit' => 'Mài kàm-sī',
'watchlistedit-normal-done' => 'Í-keng ū {{PLURAL:$1| ia̍h| ia̍h}} ùi lí ê kám-sī-toaⁿ soá cháu:',
'watchlistedit-raw-title' => '改進前的監視單',
'watchlistedit-raw-legend' => '改進前的監視單',
'watchlistedit-raw-explain' => '下跤是你監視文章的標題，你會當透過改這个表去加入抑是徙走標題；一逝一个標題。
改了後，點擊 {{int:Watchlistedit-raw-submit}}。
你嘛會當去用 [[Special:EditWatchlist|標準編輯器]]。',
'watchlistedit-raw-titles' => '標題：',
'watchlistedit-raw-submit' => '改監視單',
'watchlistedit-raw-done' => '你的監視單有改新。',
'watchlistedit-raw-added' => '已經加入{{PLURAL:$1|1个|$个}}標題:',
'watchlistedit-raw-removed' => '已經徙走{{PLURAL:$1|1个|$个}}標題:',

# Watchlist editing tools
'watchlisttools-view' => '看相關的修改',
'watchlisttools-edit' => 'Khoàⁿ koh kái kàm-sī-toaⁿ',
'watchlisttools-raw' => 'Kái tshing-chheng ê kàm-sī-toaⁿ',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]]（[[{{ns:user_talk}}:$1|留話]]）',

# Core parser functions
'unknown_extension_tag' => '無清楚的擴展標籤 "$1"',
'duplicate-defaultsort' => '\'\'\'Thê-chhíⁿ lí:\'\'\'Siat-piān ê pâi-lia̍t hong-sek "$2" thè-oāⁿ chìn-chêng ê siat-piān ê pâi-lia̍t hong-sek "$1".',

# Special:Version
'version' => 'Pán-pún',
'version-extensions' => '已經裝的擴展',
'version-specialpages' => '特殊頁',
'version-parserhooks' => '語法鈎',
'version-variables' => '變數',
'version-antispam' => '防止廣告',
'version-skins' => '皮',
'version-other' => '其他',
'version-mediahandlers' => '媒體處理器',
'version-hooks' => '鈎',
'version-extension-functions' => '擴展函數',
'version-parser-extensiontags' => '語法擴展標籤',
'version-parser-function-hooks' => '語法函數鈎',
'version-hook-name' => '鈎名',
'version-hook-subscribedby' => '用佇',
'version-version' => '（版本 $1）',
'version-license' => '授權',
'version-poweredby-credits' => "這个 Wiki 是由 '''[//www.mediawiki.org/ MediaWiki]''' 驅動，版權所有 © 2001-$1 $2。",
'version-poweredby-others' => '其他',
'version-license-info' => 'MediaWiki是自由的軟體；你會當照自由軟體基金會所發佈的GNU通用公共授權條款規定，來發佈閣／抑修改本程式；無論你根據的是本授權的第二版抑是（你家己選擇的）日後的版本。

MediaWiki是為著使用的目的才發佈，毋過無負任何擔保責任；也無對適售性抑是特定目的適用性的默示性擔保。詳情請看GNU通用公共授權。

你應該有收著附佇本程式的[{{SERVER}}{{SCRIPTPATH}}/COPYING GNU通用公共授權的副本]；若無，請寫批到自由軟件基金會：51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA，抑是[//www.gnu.org/licenses/old-licenses/gpl-2.0.html 上網閱覽]。',
'version-software' => '已經安裝的軟體',
'version-software-product' => '產品',
'version-software-version' => '版本',
'version-entrypoints' => '進入點網址',
'version-entrypoints-header-entrypoint' => '進入點',
'version-entrypoints-header-url' => '網址',

# Special:FilePath
'filepath' => 'Tóng-àn ê soàⁿ-lō·',
'filepath-page' => '檔案：',
'filepath-submit' => '來去',
'filepath-summary' => '這个特殊頁會送回一个檔案的完整路徑。
圖像會用完整的解析度顯示，其它的檔案類型嘛會用相關的程式啟動。',

# Special:FileDuplicateSearch
'fileduplicatesearch' => '揣有仝款的檔案',
'fileduplicatesearch-summary' => '照亂數值去揣仝款的檔案。',
'fileduplicatesearch-legend' => '揣仝款的',
'fileduplicatesearch-filename' => '檔案名稱：',
'fileduplicatesearch-submit' => '揣',
'fileduplicatesearch-info' => '$1 × $2 像素<br />檔案大細：$3<br />MIME類型：$4',
'fileduplicatesearch-result-1' => '檔案 "$1" 無有完全相仝的。',
'fileduplicatesearch-result-n' => '檔案 "$1" 有{{PLURAL:$2|1个完全相仝的|$2个完全相仝的}}。',
'fileduplicatesearch-noresults' => '揣無叫"$1"的檔案。',

# Special:SpecialPages
'specialpages' => 'Te̍k-sû-ia̍h',
'specialpages-note' => '----
* 一般的特殊頁。
* <span class="mw-specialpagecached">有限制的特殊頁。</span>',
'specialpages-group-maintenance' => '維修報告',
'specialpages-group-other' => '其他的特殊頁',
'specialpages-group-login' => '登入',
'specialpages-group-changes' => '最近改的記錄',
'specialpages-group-media' => '媒體報告佮上載',
'specialpages-group-users' => '用者佮使用權',
'specialpages-group-highuse' => '捷捷用著的頁面',
'specialpages-group-pages' => '頁的清單',
'specialpages-group-pagetools' => '頁的家私',
'specialpages-group-wiki' => 'Wiki資料佮家私',
'specialpages-group-redirects' => '共特殊頁轉向',
'specialpages-group-spam' => '反垃圾工具',

# Special:BlankPage
'blankpage' => '空的頁',
'intentionallyblankpage' => '這頁是刁持留空的。',

# External image whitelist
'external_image_whitelist' => ' #留佮這行仝款的字<pre>
#佇下面（//的中間部份）拍正規表示式部份
#遮會佮外部（已經超連結的）影相相配合
#遐有相配合著會會顯示做影相，若無就只會顯示做連結
#有 # 做頭的行會當做是註解
#大小寫無差

#佇這行面頂拍所有的正規表示式部份，留佮這行仝款的字</pre>',

# Special:Tags
'tags' => '有效的標籤',
'tag-filter' => '[[Special:Tags|標籤]]過濾器:',
'tag-filter-submit' => '過濾器',
'tags-title' => '標籤',
'tags-intro' => '這頁是標籤的清單，標示編輯的動作佮意義。',
'tags-tag' => '標籤名稱',
'tags-display-header' => '佇修改清單的出現方式',
'tags-description-header' => '意思的完整解說',
'tags-hitcount-header' => '有貼標籤的修改',
'tags-edit' => '修改',
'tags-hitcount' => '$1 {{PLURAL:$1|改|改}}',

# Special:ComparePages
'comparepages' => '比並頁',
'compare-selector' => '比並頁的修訂本',
'compare-page1' => '第一頁',
'compare-page2' => '第二頁',
'compare-rev1' => '第一修訂本',
'compare-rev2' => '第二修訂本',
'compare-submit' => '比較',
'compare-invalid-title' => '你指定的標題無適當。',
'compare-title-not-exists' => '無你指定的標題',
'compare-revision-not-exists' => '無你指定的修訂本',

# Database error messages
'dberr-header' => '這个Wiki遇著問題',
'dberr-problems' => '失禮！
這馬這个站有技術上的問題。',
'dberr-again' => '先等幾分鐘，才閣載入',
'dberr-info' => '（無法連接到資料庫伺服器: $1）',
'dberr-usegoogle' => '佇這馬，你會當先透過 Google 揣。',
'dberr-outofdate' => '請注意，in索引出來的內容可能毋是上新的。',
'dberr-cachederror' => '這是你欲挃的頁的快取副本，而且可能毋是上新的。',

# HTML forms
'htmlform-invalid-input' => '你拍的內底有一寡問題。',
'htmlform-select-badoption' => '你寫的數量，無適合。',
'htmlform-int-invalid' => '你寫的毋是數量。',
'htmlform-float-invalid' => '你寫的毋是數量。',
'htmlform-int-toolow' => '你寫的數量低過上細的量 $1。',
'htmlform-int-toohigh' => '你寫的數量超過上大的量 $1。',
'htmlform-required' => '這个數量愛寫',
'htmlform-submit' => '送出',
'htmlform-reset' => '取消修改',
'htmlform-selectorother-other' => '其他',

# SQLite database support
'sqlite-has-fts' => '帶全文搜揣的版本$1',
'sqlite-no-fts' => '無帶全文搜揣的版本$1',

# New logging system
'logentry-delete-delete' => '$1刣掉頁面$3',
'logentry-delete-restore' => '$1恢復頁面$3',
'logentry-delete-event' => '$1已經改$3內底{{PLURAL:$5|項|項}}記錄的可見性：$4',
'logentry-delete-revision' => '$1改$3內底{{PLURAL:$5|$5个修訂本|$5个修訂本}}的可見性：$4',
'logentry-delete-event-legacy' => '$1改頁$3的記錄事件的可見性　',
'logentry-delete-revision-legacy' => '$1改頁$3的修訂本可見性　',
'logentry-suppress-delete' => '$1藏掉頁面$3',
'logentry-suppress-event' => '$1私下改$3的{{PLURAL:$5|$5項紀錄|$5項紀錄}}的可見性：$4',
'logentry-suppress-revision' => '$1私下改$3的{{PLURAL:$5|$5个修訂本|$5个修訂本}}的可見性：$4',
'logentry-suppress-event-legacy' => '$1私下改頁$3可見性的記錄事件',
'logentry-suppress-revision-legacy' => '$1私下改頁$3修訂本的可見性',
'revdelete-content-hid' => '內容藏起來',
'revdelete-summary-hid' => '編輯藏起的摘要',
'revdelete-uname-hid' => '共用者名稱藏起來',
'revdelete-content-unhid' => '恢復內容',
'revdelete-summary-unhid' => '編輯恢復的摘要',
'revdelete-uname-unhid' => '恢復用者名稱',
'revdelete-restricted' => '已經共限制用佇管裡員',
'revdelete-unrestricted' => '徙走對管裡員的限制',
'logentry-move-move' => '$1共頁$3徙去$4',
'logentry-move-move-noredirect' => '$1共頁面$3徙去$4，閣無留轉頁',
'logentry-move-move_redir' => '$1透過轉向，共頁面$3徙去$4',
'logentry-move-move_redir-noredirect' => '$1透過轉向，共$3頁面徙去$4，無留轉頁',
'logentry-patrol-patrol' => '$1共$3頁的$4修訂本記做巡過',
'logentry-patrol-patrol-auto' => '$1自動共頁面$3的版本$4記做巡過',
'logentry-newusers-newusers' => '$1建立一个用者口座',
'logentry-newusers-create' => '$1建立一个用者口座',
'logentry-newusers-create2' => '$1建立口座$3',
'logentry-newusers-autocreate' => '口座$1已經自動建立',
'newuserlog-byemail' => '用電子批寄密碼',

# Feedback
'feedback-bugornote' => '若你欲詳細寫一个技術問題，請[$1 報告一隻臭蟲]。
抑是，你會當用下跤簡單的表，你的意見會加佇頁面“[$3 $2]”，而且有你的用戶名佮你用的佗一種瀏覽器。',
'feedback-subject' => '題目：',
'feedback-message' => '信息：',
'feedback-cancel' => '取消',
'feedback-submit' => '送出回饋',
'feedback-adding' => '當咧加回饋到頁面...',
'feedback-error1' => '錯誤：對API送來的結果（無法判斷）。',
'feedback-error2' => '錯誤：編輯失敗',
'feedback-error3' => '錯誤：API 無回應',
'feedback-thanks' => '多謝，你的回饋已經貼佇"[$2 $1]"的頁面。',
'feedback-close' => '完成',
'feedback-bugcheck' => '誠好，拄檢查過，彼無佇[$1發現過的臭蟲]內底。',
'feedback-bugnew' => '我已經檢查過。報告一个新臭蟲。',

# API errors
'api-error-badaccess-groups' => '你無允准上載檔案到這个Wiki網站。',
'api-error-badtoken' => '內部錯誤：標記失效。',
'api-error-copyuploaddisabled' => '佇這个伺服器無用透過網址(URL)上載的功能。',
'api-error-duplicate' => '佇網站內底另外有{{PLURAL:$1|[$2个]|[$2个]}}仝款的檔案。',
'api-error-duplicate-archive' => '佇網站內底{{PLURAL:$1|[$2个]|[$2个]}}仝款的檔案，毋過已經刣掉。',
'api-error-duplicate-archive-popup-title' => '仝款的{{PLURAL:$1|檔案|檔案}}已經共刣掉。',
'api-error-duplicate-popup-title' => '仝款的 {{PLURAL:$1|檔案|檔案}}。',
'api-error-empty-file' => '你送出來的檔案是空的。',
'api-error-emptypage' => '袂當開空頁。',
'api-error-fetchfileerror' => '內部錯誤：掠檔案的時陣有一寡問題。',
'api-error-file-too-large' => '你送出來的檔案傷過大。',
'api-error-filename-tooshort' => '檔案名傷短。',
'api-error-filetype-banned' => '這類的檔案被禁止。',
'api-error-filetype-missing' => '檔案名稱尾仔欠類型。',
'api-error-hookaborted' => '你欲做的編輯因為擴展鈎(extension hook)去跳開。',
'api-error-http' => '內部錯誤：連接袂到伺服器。',
'api-error-illegal-filename' => '無合用的檔案名稱。',
'api-error-internal-error' => '內部錯誤：佇處理你的上載的時陣，這个Wiki拄著一寡問題。',
'api-error-invalid-file-key' => '內部錯誤：佇臨時囥位揣無檔案。',
'api-error-missingparam' => '內部錯誤：請求欠參數。',
'api-error-missingresult' => '內部錯誤：無確定拷備是毋是有成功。',
'api-error-mustbeloggedin' => '你愛登入才通上載檔案。',
'api-error-mustbeposted' => '內部錯誤：請求愛用HTTP POST。',
'api-error-noimageinfo' => '上載有成功，毋過伺服器無予咱彼个檔案的任何資料。',
'api-error-nomodule' => '內部錯誤：無掛上載套件。',
'api-error-ok-but-empty' => '內部錯誤：伺服器無回應。',
'api-error-overwrite' => '袂使覆寫已經佇咧的檔案',
'api-error-stashfailed' => '內部錯誤：伺服器做無到保存臨時檔案。',
'api-error-timeout' => '伺服器佇預期的時間內無回應。',
'api-error-unclassified' => '有一个無啥清楚的錯誤。',
'api-error-unknown-code' => '毋知的錯誤："$1"。',
'api-error-unknown-error' => '內部錯誤：佇欲上載你檔案的時陣有一寡問題。',
'api-error-unknown-warning' => '毋知的警告："$1"。',
'api-error-unknownerror' => '毋知的錯誤："$1"。',
'api-error-uploaddisabled' => '佇這个Wiki袂當上載。',
'api-error-verification-error' => '這个檔案可能已經毀掉，抑是檔案尾仔名稱毋著。',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|秒|秒}}',
'duration-minutes' => '$1 {{PLURAL:$1|分鐘|分鐘}}',
'duration-hours' => '$1 {{PLURAL:$1|點鐘|點鐘}}',
'duration-days' => '$1 {{PLURAL:$1|工|工}}',
'duration-weeks' => '$1 {{PLURAL:$1|禮拜|禮拜}}',
'duration-years' => '$1 {{PLURAL:$1|冬|冬}}',
'duration-decades' => '$1 {{PLURAL:$1|十冬|十冬}}',
'duration-centuries' => '$1 {{PLURAL:$1|百年|百年}}',
'duration-millennia' => '$1 {{PLURAL:$1|千年|千年}}',

);
