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
'tog-underline'               => 'Liân-kiat oē té-sûn:',
'tog-highlightbroken'         => 'Khang-ia̍h ê liân-kiat <a href="" class="new">án-ne</a> hián-sī (mài chhiūⁿ án-ne<a href="" class="internal">?</a>).',
'tog-justify'                 => 'pâi-chê  tōaⁿ-lo̍h',
'tog-hideminor'               => 'Am chòe-kīn ê sió kái-piàn',
'tog-hidepatrolled'           => 'Am chòe-kīn sûn koè--ê  kái-piàn',
'tog-newpageshidepatrolled'   => 'Sin-ia̍h ê chheng-toaⁿ am sûn koè--ê',
'tog-extendwatchlist'         => 'Tián-khui kàm-sī-toaⁿ khoàⁿ só͘-ū ê kái-piàn, m̄-chí sī choè-kīn--ê',
'tog-usenewrc'                => 'Ēng ka-kiông pán khoàⁿ chòe-kīn ê kái-piàn (su-iàu JavaScript)',
'tog-numberheadings'          => 'Phiau-tê chū-tōng pian-hō',
'tog-showtoolbar'             => 'Hián-sī pian-chi̍p ke-si-tiâu (su-iàu JavaScript)',
'tog-editondblclick'          => 'Siang-ji̍h ia̍h-bīn to̍h ē-tàng pian-chi̍p (su-iàu JavaScript)',
'tog-editsection'             => 'Ji̍h [siu-kái] chit-ê liân-kiat to̍h ē-tàng pian-chi̍p toāⁿ-lo̍h',
'tog-editsectiononrightclick' => 'Chiàⁿ-ji̍h (right click) toāⁿ-lo̍h (section) phiau-tê to̍h ē-tàng pian-chi̍p toāⁿ-lo̍h (su-iàu JavaScript)',
'tog-showtoc'                 => 'Hián-sī bo̍k-chhù (3-ê phiau-tê í-siōng ê ia̍h)',
'tog-rememberpassword'        => 'Kì tiâu bi̍t-bé, āu-chōa iōng ( $1 {{PLURAL:$1|day|kang}} lāi)',
'tog-watchcreations'          => 'Kā goá khui ê ia̍h ka-ji̍p kàm-sī-toaⁿ lāi-té',
'tog-watchdefault'            => 'Kā goá pian-chi̍p kòe ê ia̍h ka-ji̍p kàm-sī-toaⁿ lāi-té',
'tog-watchmoves'              => 'Kā goá soá ê ia̍h ka-ji̍p kàm-sī-toaⁿ',
'tog-watchdeletion'           => 'Kā goá thâi tiāu ê ia̍h ka-ji̍p kàm-sī-toaⁿ',
'tog-minordefault'            => 'Chiām-tēng bī-lâi ê siu-kái lóng sī sió-siu-ká',
'tog-previewontop'            => 'Sûn-khoàⁿ ê lōe-iông tī pian-chi̍p keh-á thâu-chêng',
'tog-previewonfirst'          => 'Thâu-pái pian-chi̍p seng khoàⁿ-māi',
'tog-nocache'                 => 'Koaiⁿ-tiāu ia̍h ê cache',
'tog-enotifwatchlistpages'    => 'Kam-sī-tuann ū ē bûn-tsiunn nā ū kái-piàn, kià tiān-tsú-phue hōo guá.',
'tog-enotifusertalkpages'     => 'Guá ê thó-lūn ia̍h  nā ū lâng kái,  kià tiān-tsú-phue hōo guá.',
'tog-enotifminoredits'        => 'Sió pian-chi̍p mā kià tiān-tsú-phue hōo guá.',
'tog-enotifrevealaddr'        => 'Hō͘ pat-lâng khoàⁿ ê tio̍h oá ê tiān-chú-phoe tē-chí',
'tog-shownumberswatching'     => 'Hián-sī tng leh khoàⁿ ê iōng-chiá sò͘-bo̍k',
'tog-oldsig'                  => 'Chit-má ê chhiam-miâ:',
'tog-fancysig'                => 'Chhiam-miâ mài chò liân-kiat',
'tog-externaleditor'          => 'Iōng gōa-pō· pian-chi̍p-khì (kan-na hō͘ ko-chhiú, he ài tī lí ê tiān-náu koh siat-tēng. [http://www.mediawiki.org/wiki/Manual:External_editors Siông-chêng.])',
'tog-externaldiff'            => 'Iōng gōa-pō· diff (kan-na hō͘ ko-chhiú, he ài tī lí ê tiān-noá koh siat-tēng. [http://www.mediawiki.org/wiki/Manual:External_editors Siông-chêng.])',
'tog-showjumplinks'           => 'Hō͘ "thiàu khì" chit ê liân-chiap ē-sái',
'tog-uselivepreview'          => 'Ēng sui khoàⁿ-māi (ài ū JavaScript) (chhì-giām--ê)',
'tog-forceeditsummary'        => 'Pian-chi̍p khài-iàu bô thiⁿ ê sî-chūn, kā goá thê-chhéⁿ',
'tog-watchlisthideown'        => 'Kàm-sī-toaⁿ bián hián-sī goá ê pian-chi̍p',
'tog-watchlisthidebots'       => 'Kàm-sī-toaⁿ bián hián-sī ki-khì pian-chi̍p',
'tog-watchlisthideminor'      => 'Kàm-sī-toaⁿ bián hián-sī sió siu-kái',
'tog-watchlisthideliu'        => 'Kàm-sī-toaⁿ bián hián-sī iōng-chiá ū teng-ji̍p ê pian-chi̍p',
'tog-watchlisthideanons'      => 'Kàm-sī-toaⁿ bián hián-sī bû-bêng-sī ê pian-chi̍p',
'tog-watchlisthidepatrolled'  => 'Kàm-sī-toaⁿ bián hián-sī khoàⁿ-koè--ê pian-chi̍p',
'tog-ccmeonemails'            => 'Kià hō͘ pa̍t-lâng ê email sūn-soà kià copy hō͘ goá',
'tog-diffonly'                => 'Diff ē-pêng bián hián-sī ia̍h ê loē-iông',
'tog-showhiddencats'          => 'Hián-sī chhàng khí--lâi ê lūi-pia̍t',
'tog-norollbackdiff'          => 'ká tńg-khí liáu bián-koán cheng-chha goā-chē',

'underline-always'  => 'Tiāⁿ-tio̍h',
'underline-never'   => 'Tiāⁿ-tio̍h mài',
'underline-default' => 'Tòe liû-lám-khì ê default',

# Font style option in Special:Preferences
'editfont-style'     => 'Pian-chi̍p sî ēng ê jī-thé hêng-sek:',
'editfont-default'   => 'Tòe liû-lám-khì ê default',
'editfont-monospace' => 'Monospaced jī-thé',
'editfont-sansserif' => 'Sans-serif jī-thé',
'editfont-serif'     => 'Serif jī-thé',

# Dates
'sunday'        => 'Lé-pài',
'monday'        => 'Pài-it',
'tuesday'       => 'Pài-jī',
'wednesday'     => 'Pài-saⁿ',
'thursday'      => 'Pài-sì',
'friday'        => 'Pài-gō·',
'saturday'      => 'Pài-la̍k',
'sun'           => 'Lé-pài',
'mon'           => 'It',
'tue'           => 'Jī',
'wed'           => 'Saⁿ',
'thu'           => 'Sì',
'fri'           => 'Gō·',
'sat'           => 'La̍k',
'january'       => '1-goe̍h',
'february'      => '2-goe̍h',
'march'         => '3-goe̍h',
'april'         => '4-goe̍h',
'may_long'      => '5-goe̍h',
'june'          => '6-goe̍h',
'july'          => '7-goe̍h',
'august'        => '8-goe̍h',
'september'     => '9-goe̍h',
'october'       => '10-goe̍h',
'november'      => '11-goe̍h',
'december'      => '12-goe̍h',
'january-gen'   => 'Chiaⁿ-goe̍h',
'february-gen'  => 'Jī-goe̍h',
'march-gen'     => 'Saⁿ-goe̍h',
'april-gen'     => 'Sì-goe̍h',
'may-gen'       => 'Gō·-goe̍h',
'june-gen'      => 'La̍k-goe̍h',
'july-gen'      => 'Chhit-goe̍h',
'august-gen'    => 'Peh-goe̍h',
'september-gen' => 'Káu-goe̍h',
'october-gen'   => 'Cha̍p-goe̍h',
'november-gen'  => 'Cha̍p-it-goe̍h',
'december-gen'  => 'Cha̍p-jī-goe̍h',
'jan'           => '1g',
'feb'           => '2g',
'mar'           => '3g',
'apr'           => '4g',
'may'           => '5g',
'jun'           => '6g',
'jul'           => '7g',
'aug'           => '8g',
'sep'           => '9g',
'oct'           => '10g',
'nov'           => '11g',
'dec'           => '12g',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Lūi-pia̍t|ê lūi-pia̍t}}',
'category_header'                => 'Tī "$1" chit ê lūi-pia̍t ê bûn-chiuⁿ',
'subcategories'                  => 'Ē-lūi-pia̍t',
'category-media-header'          => 'Tī lūi-pia̍t "$1" ê mûi-thé',
'category-empty'                 => "''Chit-má chit ê lūi-pia̍t  bô ia̍h ia̍h-sī mûi-thé.''",
'hidden-categories'              => '{{PLURAL:$1|Hidden category|Chhàng khí-lâi ê lūi-pia̍t}}',
'hidden-category-category'       => 'Chhàng khí--lâi ê lūi piat',
'category-subcat-count'          => '{{PLURAL:$2|Chit ê lūi-piat chí-ū ē-bīn ê ē-lūi-pia̍t.|Chit ê lūi-piat ū ē-bīn {{PLURAL:$1| ê ē-lūi-piat|$1 ê ē-lūi-piat}}, choân-pō͘ $2 ê.}}',
'category-subcat-count-limited'  => 'Chit ê lūi-piat ū ē-bīn ê {{PLURAL:$1| ē-lūi-pia̍t|$1 ē-lūi-pia̍t}}.',
'category-article-count'         => '{{PLURAL:$2|Chit ê lūi-piat chí-ū ē-bīn ê ia̍h.|Ē-bīn {{PLURAL:$1|bīn ia̍h sī|$1bīn ia̍h sī}} tī chit lūi-pia̍t, choân-pō͘ $2 bīn ia̍h}}',
'category-article-count-limited' => 'Ē-bīn {{PLURAL:$1|ia̍h sī|$1 ia̍h sī }} tī chit ê lūi-pia̍t.',
'category-file-count'            => '{{PLURAL:$2|Chit ê lūi-piat chí-ū ē-bīn ê tóng-àn.|Ē-bīn {{PLURAL:$1| ê tóng-àn sī|$1 ê tóng-àn sī}} tī chit lūi-pia̍t, choân-pō͘ $2 ê tóng-àn}}',
'category-file-count-limited'    => 'Chit-má chit-ê lūi-pia̍t ū {{PLURAL:$1| ê tóng-àn}}',
'listingcontinuesabbrev'         => '(chiap-sòa thâu-chêng)',
'index-category'                 => 'Ū sik-ín ê ia̍h',
'noindex-category'               => 'Bī sik-ín ê ia̍h.',

'mainpagetext'      => "'''MediaWiki已經裝好矣。'''",
'mainpagedocfooter' => '請查看[http://meta.wikimedia.org/wiki/Help:Contents 用者說明書]的資料通使用wiki 軟體

== 入門 ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings 配置的設定]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki時常問答]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki的公布列單]',

'about'         => 'Koan-hē',
'article'       => 'Loē-iông ia̍h',
'newwindow'     => '(ē khui sin thang-á hián-sī)',
'cancel'        => 'Chhú-siau',
'moredotdotdot' => 'Iáu-ū',
'mypage'        => 'Góa ê ia̍h',
'mytalk'        => 'Góa ê thó-lūn',
'anontalk'      => 'Chit ê IP ê thó-lūn-ia̍h',
'navigation'    => 'Se̍h chām',
'and'           => '&#32;kap',

# Cologne Blue skin
'qbfind'         => 'Chhoé',
'qbbrowse'       => 'Liū-lám',
'qbedit'         => 'Siu-kái',
'qbpageoptions'  => 'Chit ia̍h',
'qbpageinfo'     => 'Bo̍k-lo̍k',
'qbmyoptions'    => 'Goá ê ia̍h',
'qbspecialpages' => 'Te̍k-sû-ia̍h',
'faq'            => 'Būn-tah',
'faqpage'        => 'Project:Būn-tah',

# Vector skin
'vector-action-addsection'       => 'Ke chi̍t-ê toān-lo̍h',
'vector-action-delete'           => 'Thâi',
'vector-action-move'             => 'Sóa khì',
'vector-action-protect'          => 'Pó-hō·',
'vector-action-undelete'         => 'chhú-siau thâi tiàu',
'vector-action-unprotect'        => 'Chhú-siau pó-hō·',
'vector-simplesearch-preference' => 'Chhái-iōng ka-kiông-pán ê chhiau-soh kiàn-gī ( chí hān tī Vector bīn-phoê)',
'vector-view-create'             => 'Khai-sí siá',
'vector-view-edit'               => 'Siu-kái',
'vector-view-history'            => 'khoàⁿ le̍k-sú',
'vector-view-view'               => 'Tha̍k',
'vector-view-viewsource'         => 'Khoàⁿ goân-sú lōe-iông',
'actions'                        => 'Tōng-chok',
'namespaces'                     => 'Miâ-khong-kan',
'variants'                       => 'piàn-thé',

'errorpagetitle'    => 'Chhò-gō·',
'returnto'          => 'Tò-tńg khì $1.',
'tagline'           => 'Ùi {{SITENAME}}',
'help'              => 'Soat-bêng-su',
'search'            => 'Chhiau-chhoē',
'searchbutton'      => 'Chhiau',
'go'                => 'Lâi-khì',
'searcharticle'     => 'Lâi-khì',
'history'           => 'Ia̍h le̍k-sú',
'history_short'     => 'le̍k-sú',
'updatedmarker'     => 'Téng hoê goá lâi chiah liáu ū kái koè--ê',
'info_short'        => '資訊',
'printableversion'  => 'Ìn-soat pán-pún',
'permalink'         => 'Éng-kiú liân-kiat',
'print'             => 'Ìn-soat',
'edit'              => 'Siu-kái',
'create'            => 'Khai-sí siá',
'editthispage'      => 'Siu-kái chit ia̍h',
'create-this-page'  => 'Khai-sí siá chit ia̍h',
'delete'            => 'Thâi',
'deletethispage'    => 'Thâi chit ia̍h',
'undelete_short'    => 'Kiù {{PLURAL:$1| ê siu-káit|$1  ê siu-kái}}',
'protect'           => 'Pó-hō·',
'protect_change'    => 'kái-piàn',
'protectthispage'   => 'Pó-hō· chit ia̍h',
'unprotect'         => 'Chhú-siau pó-hō·',
'unprotectthispage' => 'Chhú-siau pó-hō· chit ia̍h',
'newpage'           => 'Sin ia̍h',
'talkpage'          => 'Thó-lūn chit ia̍h',
'talkpagelinktext'  => 'thó-lūn',
'specialpage'       => 'Te̍k-sû-ia̍h',
'personaltools'     => 'Kò-jîn kang-khū',
'postcomment'       => 'Hoat-piáu phêng-lūn',
'articlepage'       => 'Khoàⁿ loē-iông ia̍h',
'talk'              => 'thó-lūn',
'views'             => 'Khoàⁿ',
'toolbox'           => 'Ke-si kheh-á',
'userpage'          => 'Khoàⁿ iōng-chiá ê Ia̍h',
'projectpage'       => 'Khoàⁿ sū-kang ia̍h',
'imagepage'         => 'Khoàⁿ tóng-àn ia̍h',
'mediawikipage'     => 'Khoàⁿ sìn-sit ia̍h',
'templatepage'      => 'Khoàⁿ pang-bô͘ ia̍h',
'viewhelppage'      => 'Khoàⁿ pang-chō͘ ia̍h',
'categorypage'      => 'Khoàⁿ lūi-pia̍t ia̍h',
'viewtalkpage'      => 'Khoàⁿ thó-lūn',
'otherlanguages'    => 'Kî-thaⁿ ê gí-giân',
'redirectedfrom'    => '(Tùi $1 choán--lâi)',
'redirectpagesub'   => 'Choán-ia̍h',
'lastmodifiedat'    => 'Chit ia̍h tī $1,  $2 ū kái--koè',
'viewcount'         => 'Pún-ia̍h kàu taⁿ ū {{PLURAL:$1| pái|$1 pái}}  ê sú-iōng.',
'protectedpage'     => 'Siū pó-hō͘ ê ia̍h',
'jumpto'            => 'Thiàu khì:',
'jumptonavigation'  => 'Se̍h chām',
'jumptosearch'      => 'chhiau-chhoē',
'view-pool-error'   => 'Pháiⁿ-sè, chit-má chú-ki siuⁿ koè bô-êng.
Siuⁿ koè chē lâng beh khoàⁿ chit ia̍h.
Chhiáⁿ sio-tán chi̍t-ē,  chiah koh lâi khoàⁿ chit ia̍h.

$1',
'pool-timeout'      => 'Chhiau-koè só-tēng ê sî-kan',
'pool-queuefull'    => 'Tūi-lia̍t pâi moá ah',
'pool-errorunknown' => 'M̄-chai siáⁿ chhò-gō͘',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'hían-sī',
'aboutpage'            => 'Project:koan-hē',
'copyright'            => 'Tī $1 tiâu-kiāⁿ chi hā khó sú-iōng loē-iông',
'copyrightpage'        => '{{ns:project}}:Pán-khoân',
'currentevents'        => 'Sin-bûn sū-kiāⁿ',
'currentevents-url'    => 'Project:Sin-bûn sū-kiāⁿ',
'disclaimers'          => 'Bô-hū-chek seng-bêng',
'disclaimerpage'       => 'Project:It-poaⁿ ê seng-bêng',
'edithelp'             => 'Án-choáⁿ siu-kái',
'edithelppage'         => 'Help:Pian-chi̍p',
'helppage'             => 'Help:Bo̍k-lio̍k',
'mainpage'             => 'Thâu-ia̍h',
'mainpage-description' => 'Thâu-ia̍h',
'policy-url'           => 'Project:Chèng-chhek',
'portal'               => 'Siā-lí mn̂g-chhùi-kháu',
'portal-url'           => 'Project:Siā-lí mn̂g-chhùi-kháu',
'privacy'              => 'Ín-su chèng-chhek',
'privacypage'          => 'Project:Ún-su chèng-chhek',

'badaccess'        => 'Siū-khoân chhò-ngō͘',
'badaccess-group0' => 'Lí bô ún-chún chò lí iau-kiû ê tōng-chok.',
'badaccess-groups' => 'Lí beh chò ê tōng-chok chí hān {{PLURAL:$2| tīn| tīn-goân chi it }}: $1 ê iōng-chiá.',

'versionrequired'     => 'Ài MediaWiki $1 ê pán-pún',
'versionrequiredtext' => 'Beh iōng chit ia̍h ài MediaWiki $1 ê pán-pún.
Chhiáⁿ khoàⁿ [[Special:Version|pán-pún ia̍h]].',

'ok'                      => 'Hó ah',
'retrievedfrom'           => 'Lâi-goân: "$1"',
'youhavenewmessages'      => 'Lí ū $1 ($2).',
'newmessageslink'         => 'sin sìn-sit',
'newmessagesdifflink'     => 'chêng 2 ê siu-tēng-pún ê diff',
'youhavenewmessagesmulti' => 'Lí tī $1 ū sin sìn-sit',
'editsection'             => 'siu-kái',
'editold'                 => 'siu-kái',
'viewsourceold'           => 'Khoàⁿ goân-sú lōe-iông',
'editlink'                => 'siu-kái',
'viewsourcelink'          => 'Khoàⁿ goân-sú lōe-iông',
'editsectionhint'         => 'Pian-chi̍p toān-lo̍h: $1',
'toc'                     => 'Bo̍k-lo̍k',
'showtoc'                 => 'khui',
'hidetoc'                 => 'siu',
'thisisdeleted'           => 'Khoàⁿ a̍h-sī kiù $1?',
'viewdeleted'             => 'Beh khoàⁿ $1？',
'restorelink'             => '{{PLURAL:$1|chi̍t ê thâi-tiàu ê pian-chi̍p|$1 thâi-tiàu ê pian-chi̍p}}',
'feedlinks'               => 'Tēng khoàⁿ:',
'feed-invalid'            => 'Bô-hāu ê tēng khoàⁿ lūi-hêng.',
'feed-unavailable'        => 'Bô thê-kiong liân-ha̍p tēng khoàⁿ.',
'site-rss-feed'           => '$1 ê RSS tēng khoàⁿ',
'site-atom-feed'          => '$1 ê Atom tēng-khoàⁿ',
'page-rss-feed'           => '"$1" ê RSS tēng khoàⁿ',
'page-atom-feed'          => '"$1" ê Atom tēng khoàⁿ',
'red-link-title'          => '$1 (bô hit ia̍h)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Bûn-chiuⁿ',
'nstab-user'      => 'Iōng-chiá ê ia̍h',
'nstab-media'     => 'Mûi-thé',
'nstab-special'   => 'Te̍k-sû-ia̍h',
'nstab-project'   => 'Sū-kang ia̍h',
'nstab-image'     => 'Tóng-àn',
'nstab-mediawiki' => 'Sìn-sit',
'nstab-template'  => 'Pang-bô·',
'nstab-help'      => 'Pang-chō͘ ia̍h',
'nstab-category'  => 'Lūi-pia̍t',

# Main script and global functions
'nosuchaction'      => 'Bô chit-khoán tōng-chok',
'nosuchactiontext'  => 'Hit ê URL só͘ chí-tēng ê tōng-chok bô-hāu.
Lí khó-lêng phah m̄-tio̍h URL, ia̍h sī ji̍h tio̍h chhò-ngō͘ ê liân-kiat.
Che mā khó-lêng sī {{SITENAME}} só͘ sú-iōng ê nńg-thé chhut būn-tê.',
'nosuchspecialpage' => 'Bô chit ê te̍k-sû-ia̍h',
'nospecialpagetext' => '<strong>Bô lí beh tih ê te̍k-sû-ia̍h。</strong>

[[Special:SpecialPages|{{int:specialpages}}]] sī só͘-ū ê te̍k-sû-ia̍h lia̍t-pió.',

# General errors
'error'                => 'Chhò-gō·',
'databaseerror'        => 'Chu-liāu-khò· chhò-gō·',
'dberrortext'          => 'Chu-liāu-khò͘ hoat-seng cha-sûn ê gí-hoat chhò-ngō͘.
Che khó-lêng sī nńg-thé ê chhò-ngō͘.
Téng chi̍t ê cha-sûn sī :
<blockquote><tt>$1</tt></blockquote>
tī hâm-sò͘  "<tt>$2</tt>".
Chu-liāu-khò͘ thoân hoê ê chhò-ngō͘ "<tt>$3: $4</tt>".',
'dberrortextcl'        => '發生一个查詢資料庫語法錯誤，頂一个欲查詢資料庫是：
"$1"
佇"$2"
資料庫送回一个錯誤"$3: $4"',
'laggedslavemode'      => "'''提醒你：'''這頁可能無包括最近改的。",
'readonly'             => 'Chu-liāu-khò· só tiâu leh',
'enterlockreason'      => 'Phah beh hong-só ê lí-iû, pau-koah ko͘-kè siáⁿ-mi̍h sî-chūn ē kái-tû hong-só.',
'readonlytext'         => 'Chu-liāu-khò· hiān-chú-sî só tiâu leh, bô khai-hòng hō· lâng siu-kái. Che tāi-khài sī in-ūi teh pān î-siu khang-khòe, oân-sêng liáu-āu èng-tong tō ē hôe-ho̍k chèng-siông. Hū-chek ê hêng-chèng jîn-oân lâu chit-ê soat-bêng: $1',
'missing-article'      => 'Chu-liāu-khò͘ chhoē bô ia̍h ê luē-iông, ia̍h ê miâ "$1" $2 .

Che it-poaⁿ sī in-ūi koè-sî ê cheng-chha ia̍h sī le̍k-sú liân-kiat ê ia̍h í-keng hông thâi tiàu.

Nā m̄-sī hit chióng chêng-hêng, lí khó-lêng tú tio̍h nńg-thé ê chhò-ngō͘. Chhiáⁿ pò hō͘ chi̍t ūi [[Special:ListUsers/sysop|koán-lí-goân]], ūi liân-kiat hiâ khì lâu thong-ti .',
'missingarticle-rev'   => '（修訂本#: $1）',
'missingarticle-diff'  => '(精差：$1, $2)',
'readonly_lag'         => '資料庫已經自動鎖牢咧，從屬資料庫伺服器當咧更新綴到主伺服器',
'internalerror'        => 'Loē-pō͘ ê chhò-ngō͘',
'internalerror_info'   => 'Loē-pō͘ ê chhò-ngō͘: $1',
'fileappenderrorread'  => 'Ka-ji̍p(append) ê sî bô-hoat-tō͘ thak "$1".',
'fileappenderror'      => 'Bô-hoat-tō͘ kā "$1" chiap khì "$2".',
'filecopyerror'        => 'Bô-hoat-tō· kā tóng-àn "$1" khó·-pih khì "$2".',
'filerenameerror'      => 'Bô-hoat-tō· kā tóng-àn "$1" kái-miâ chò "$2".',
'filedeleteerror'      => 'Bô-hoat-tō· kā tóng-àn "$1" thâi tiāu',
'directorycreateerror' => 'Bô-hoat-tō͘ khui bo̍k-lo̍k "$1".',
'filenotfound'         => 'Chhōe bô tóng-àn "$1".',
'fileexistserror'      => 'Bô-hoat-tō͘ chûn-ji̍p tóng-àn "$1": í-keng ū chit ê tóng-àn',
'unexpected'           => 'Koài-koài ê pió-tat: "$1"="$2"。',
'formerror'            => 'Chhò-gō·: bô-hoat-tō· kā pió sàng chhut khì.',
'badarticleerror'      => 'Bē-tàng tiàm chit ia̍h chip-hêng chit ê tōng-chok.',
'cannotdelete'         => 'Bô-hoat-tō· kā  "$1" hit ê ia̍h a̍h-sī iáⁿ-siōng thâi tiāu. (Khó-lêng pa̍t-lâng í-keng kā thâi tiāu ah.)',
'badtitle'             => 'M̄-chiâⁿ piau-tê',
'badtitletext'         => 'Iau-kiû ê piau-tê sī bô-hāu ê, khang ê, a̍h-sī liân-kiat chhò-gō· ê inter-language/inter-wiki piau-tê.',
'perfcached'           => 'Ē-kha ê chu-liāu tùi lâi--ê, só·-í bī-pit oân-choân hoán-èng siōng sin ê chōng-hóng.',
'perfcachedts'         => 'Ē-kha ê chu-liāu tùi lâi--ê, tī $1 keng-sin--koè.',
'querypage-no-updates' => 'Chit-má bē-sái kái chit ia̍h.
Chia ê chu-liāu bē-tàng sui tiông-sin chéng-lí.',
'wrong_wfQuery_params' => 'Chhò-ngō͘ ê chham-sò͘ chhoân hō͘ wfQuery（）<br />
Hâm-sò͘: $1<br />
Cha-sûn: $2',
'viewsource'           => 'Khoàⁿ goân-sú lōe-iông',
'viewsourcefor'        => '$1 ê',
'actionthrottled'      => 'Tōng-chok hông tóng leh.',
'actionthrottledtext'  => 'Ūi-tio̍h thê-hông lah-sap ê chhú-tì,  lí ū hông hān-chè tī té sî-kan lāi chò siuⁿ chē pái chit ê tōng-chok,  taⁿ lí í-keng chhiau-koè hān-chè.
Chhiáⁿ tī kúi hun-cheng hāu chiah koh chhì.',
'protectedpagetext'    => 'Chit ia̍h hông só tiâu leh, bē pian-chi̍p tit.',
'viewsourcetext'       => 'Lí ē-sái khoàⁿ ia̍h khó͘-pih chit ia̍h ê goân-sú loē-iông:',
'protectedinterface'   => 'Chit ia̍h thê-kiong nńg-thé kài-bīn ēng ê bûn-jī. Ūi beh ī-hông lâng chau-that, só͘-í ū siū tio̍h pó-hō͘.',
'editinginterface'     => "'''Sè-jī:''' Lí tng teh siu-kái 1 bīn thê-kiong nńg-thé kài-bīn bûn-jī ê ia̍h. 
Jīn-hô kái-piàn to ē éng-hióng tio̍h kî-thaⁿ iōng-chiá ê sú-iōng kài-bīn.
Nā ūi-tio̍h hoan-e̍k, chhiáⁿ khó-lū sú-iōng [http://translatewiki.net/wiki/Main_Page?setlang=nan translatewiki.net], MediaWiki ê chāi-tē hoà sū-kang.",
'sqlhidden'            => '(Tshàng SQL tsa-sûn)',
'cascadeprotected'     => 'Chit-ê ia̍h í-keng hông pó-hō͘ bē kái tit. In-ūi i tī ē-bīn {{PLURAL:$1|ê|ê}} liân-só pó-hō͘ lāi-té:
$2',
'namespaceprotected'   => "Lí bô khoân-lī kái '''$1'''  miâ-khong-kan ê ia̍h",
'customcssjsprotected' => '你無權限通改這頁，因為伊包括著其他用戶的個人設定。',
'ns-specialprotected'  => '特殊頁袂使改得',
'titleprotected'       => "這个標題已經予[[User:$1|$1]]保護牢咧袂使用。理由是''$2''。",

# Virus scanner
'virus-badscanner'     => "毋著的設定: 毋知影的病毒掃瞄器：''$1''",
'virus-scanfailed'     => '掃描失敗（號碼 $1）',
'virus-unknownscanner' => 'M̄-chai siáⁿ pēⁿ-to̍k:',

# Login and logout pages
'logouttext'                 => "'''Lí í-keng teng-chhut.'''

Lí ē-sái mài kì-miâ kè-siok sú-iōng {{SITENAME}}, mā ē-sái iōng kāng-ê a̍h-sī  pa̍t-ê sin-hūn [[Special:UserLogin|têng teng-ji̍p]].
Chhiaⁿ chù-ì: ū-kóa ia̍h ū khó-lêng khoàⁿ-tio̍h bē-su lí iû-goân teng-ji̍p tiong; che chi-iàu piàⁿ tiāu lí ê browser ê cache chiū ē chèng-siông.",
'welcomecreation'            => '==Hoan-gêng $1!==
Í-keng khui hó lí ê kháu-chō.  M̄-hó bē-kì-tit chhiâu lí tī [[Special:Preferences|{{SITENAME}} ê iōng-chiá siat-tēng]].',
'yourname'                   => 'Lí ê iōng-chiá miâ-chheng:',
'yourpassword'               => 'Lí ê bi̍t-bé:',
'yourpasswordagain'          => 'Têng phah bi̍t-bé:',
'remembermypassword'         => 'Kì tiâu góa ê bi̍t-bé (āu-chhiú teng-ji̍p iōng) (tī $1 {{PLURAL:$1|day|days}} kang lāi)',
'securelogin-stick-https'    => '登入後繼續以HTTPS連接',
'yourdomainname'             => '你的網域',
'externaldberror'            => '這可能是因為驗證資料庫錯誤，抑是你hông禁止改你的外部口座。',
'login'                      => 'Teng-ji̍p',
'nav-login-createaccount'    => 'Teng-ji̍p / khui sin kháu-chō',
'loginprompt'                => 'Thiⁿ ē-kha ê chu-liāu thang khui sin hō·-thâu a̍h-sī teng-ji̍p {{SITENAME}}.',
'userlogin'                  => 'Teng-ji̍p / khui sin kháu-chō',
'userloginnocreate'          => 'Teng-ji̍p',
'logout'                     => 'Teng-chhut',
'userlogout'                 => 'Teng-chhut',
'notloggedin'                => 'Bô teng-ji̍p',
'nologin'                    => "Bô poàⁿ ê kháu-chō? '''$1'''.",
'nologinlink'                => 'Khui 1 ê kháu-chō',
'createaccount'              => 'Khui sin kháu-chō',
'gotaccount'                 => "Í-keng ū kháu-chō? '''$1'''.",
'gotaccountlink'             => 'Teng-ji̍p',
'createaccountmail'          => 'Thàu koè tiān-chú-phoe',
'createaccountreason'        => 'Lí-iû:',
'badretype'                  => 'Lí su-ji̍p ê 2-cho· bi̍t-bé bô tùi.',
'userexists'                 => 'Lí beh ti̍h ê iōng-chiá miâ-chheng í-keng ū lâng iōng. Chhiáⁿ kéng pa̍t-ê miâ.',
'loginerror'                 => 'Teng-ji̍p chhò-gō·',
'createaccounterror'         => 'Bô hoat-tō͘ khui kháu-chō: $1',
'nocookiesnew'               => '口座開好矣，毋過你猶未登入，
{{SITENAME}}用cookies記錄用者，
你無拍開cookies功能，
請拍開，通記錄你的用者名稱佮密碼。',
'nocookieslogin'             => '{{SITENAME}}用 Cookies 記錄用戶，你共關掉，請拍開閣重新登入。',
'noname'                     => '你無拍一个有效的用者名稱。',
'loginsuccesstitle'          => 'Teng-ji̍p sêng-kong',
'loginsuccess'               => 'Lí hiān-chhú-sî í-keng teng-ji̍p {{SITENAME}} chò "$1".',
'nosuchuser'                 => 'Chia bô iōng-chiá hō-chò "$1". Miâ-jī  ū hun toā-siá, sio-siá . Chhiáⁿ kiám-cha lí ê phèng-im, a̍h-sī [[Special:UserLogin/signup|khui sin káu-chō]].',
'nosuchusershort'            => '無"$1"這个用者名，
對看覓，你拍的。',
'nouserspecified'            => 'Lí ài chí-tēng chi̍t ê iōng-chiá miâ.',
'login-userblocked'          => '這个用者已經hông封鎖，無允准登入。',
'wrongpassword'              => 'Lí su-ji̍p ê bi̍t-bé ū têng-tâⁿ. Chhiáⁿ têng chhì.',
'wrongpasswordempty'         => 'Bi̍t-bé keh-á khang-khang. Chhiáⁿ têng chhì.',
'passwordtooshort'           => '密碼至少愛有{{PLURAL:$1|一个字元|$1字元}}',
'password-name-match'        => '你的密碼袂使佮你的用者名稱相仝',
'password-login-forbidden'   => '這个用者名稱佮密碼已經hông禁止',
'mailmypassword'             => 'Kià sin bi̍t-bé hō· góa',
'passwordremindertitle'      => '{{SITENAME}} the-chheN li e bit-be',
'passwordremindertext'       => '有人（可能是你，佇$1 IP地址）已經佇{{SITENAME}}申請新密碼 （$4）。
用者"$2"的一个臨時密碼已經設定做"$3"。
若毋是你申請的，你需要馬上登入並且選擇一个新密碼。
你的臨時密碼會佇{{PLURAL:$5|一工|$5工}}內過期。

若是別人申請的，抑是你已經想起你的密碼，而且不想欲改，
你會使莫管這个信息而且繼續用你的舊密碼。',
'noemail'                    => 'Kì-lo̍k bô iōng-chiá "$1" ê e-mail chū-chí.',
'noemailcreate'              => '你愛提供一个有效的電子批地址',
'passwordsent'               => 'Ū kià sin bi̍t-bé khì "$1" chù-chheh ê e-mail chū-chí. Siu--tio̍h liáu-āu chhiáⁿ têng teng-ji̍p.',
'blocked-mailpassword'       => '你的IP地址hông封鎖，袂當編輯，為著安全起見，袂當使用密碼恢復功能。',
'eauthentsent'               => '一張確認的批已經寄去提示的電子批地址。
佇其它批寄去彼的口座進前，你愛先照彼張批的指示，才通確定彼个口座是你的。',
'throttled-mailpassword'     => '密碼提醒的資料已經佇{{PLURAL:$1|點鐘|$1點鐘}}前寄出。為著防止濫使用，限定佇{{PLURAL:$1|點鐘|$1點鐘}}內只通送一擺密碼提醒。',
'mailerror'                  => 'Kià phoe tú tio̍h chhò-gō·: $1',
'acct_creation_throttle_hit' => 'Tī koè-khì 24 tiám-cheng lāi,  ū chit ê iōng lí IP bāng-chí ê lâng í-keng khui {{PLURAL:$1|1 account|$1 kháu-chō}}. He sī hit ê sî-kan lāi thang chò ê.
Tiō-sī kóng, tī chit-má iōng chit ê IP bāng-chí ê lâng bē-sái koh khui jīm-hô kháu-chō.',
'emailauthenticated'         => 'Lí ê e-mail chū-chí tī $2 $3 khak-jīn sêng-kong.',
'emailnotauthenticated'      => 'Lí ê e-mail chū-chí iáu-bōe khak-jīn ū-hāu, só·-í ē--kha ê e-mail kong-lêng bē-ēng-tit.',
'noemailprefs'               => 'Tī lí ê siat-piān chí-tēng chi̍t ê tiān-chú-phoe tē-chí thang hō͘ chia ê kong-lêng ē-tàng ēng.',
'emailconfirmlink'           => 'Chhiáⁿ khak-jīn lí ê e-mail chū-chí ū-hāu',
'invalidemailaddress'        => '電子批的地址無正確，規格毋著，
請拍一个符合規格的地址抑是放空格。',
'accountcreated'             => '口座開好矣',
'accountcreatedtext'         => '$1的口座開好矣',
'createaccount-title'        => '佇{{SITENAME}}開好口座',
'createaccount-text'         => '有人佇{{SITENAME}}用你的電子批地址開一个名"$2"的口座($4)，密碼是 "$3"，
你這馬應該去登入，而且去改密碼。

若是彼个口座開毋著，你會使莫管這个訊息。',
'usernamehasherror'          => '用者名稱袂使有#字元',
'login-throttled'            => '你已經試傷濟擺登入的動作，
請小等一下才閣試。',
'loginlanguagelabel'         => '話語：$1',
'suspicious-userlogout'      => '你登出的要求已經被拒絕，因為伊看起來是對無連線的瀏覽器抑是快取代理傳送來的。',

# E-mail sending
'php-mail-error-unknown' => '佇PHP的 mail() 函數的未知錯誤',

# Password reset dialog
'resetpass'                 => 'Kái bi̍t-bé',
'resetpass_announce'        => '你是對一張電子批的臨時編碼登入的。欲完成登入，你愛佇遮設定新密碼：',
'resetpass_header'          => 'Kái káu-chō ê bi̍t-bé.',
'oldpassword'               => 'Kū bi̍t-bé:',
'newpassword'               => 'Sin bi̍t-bé:',
'retypenew'                 => 'Têng phah sin bi̍t-bé:',
'resetpass_submit'          => '設定密碼而且登入',
'resetpass_success'         => '你的密碼已經改成功！
這馬你咧登入...',
'resetpass_forbidden'       => 'Bi̍t-bé bē-sái piàn.',
'resetpass-no-info'         => '你愛登入了，才通直接進入這頁',
'resetpass-submit-loggedin' => 'Kái bi̍t-bé',
'resetpass-submit-cancel'   => 'Chhú-siau',
'resetpass-wrong-oldpass'   => '無效的臨時抑是現在的密碼，
你可能已經成功更過你的密碼，抑是申請一个新的臨時密碼。',
'resetpass-temp-password'   => 'Lîm-sî ê bi̍t-bé:',

# Edit page toolbar
'bold_sample'     => 'Chho·-thé bûn-jī',
'bold_tip'        => 'Chho·-thé jī',
'italic_sample'   => 'Chhú-thé ê bûn-jī',
'italic_tip'      => 'Chhú-thé jī',
'link_sample'     => 'Liân-kiat piau-tê',
'link_tip'        => 'Lōe-pō· ê liân-kiat',
'extlink_sample'  => 'http://www.example.com liân-kiat piau-tê',
'extlink_tip'     => 'Gōa-pō· ê liân-kiat (ē-kì-tit thâu-chêng ài ke http://)',
'headline_sample' => 'Thâu-tiâu bûn-jī',
'headline_tip'    => 'Tē-2-chân (level 2) ê phiau-tê',
'math_sample'     => 'Chia siá hong-thêng-sek',
'math_tip'        => '數學的公式 （LaTeX）',
'nowiki_sample'   => 'Chia siá bô keh-sek ê bûn-jī',
'nowiki_tip'      => '無照Wiki的規格',
'image_sample'    => 'Iann-siong-e-le.jpg',
'image_tip'       => 'Giap tī lāi-bīn ê iáⁿ-siōng',
'media_tip'       => '檔案連結',
'sig_tip'         => 'Lí ê chhiam-miâ kap sî-kan ìn-á',
'hr_tip'          => '橫線 （小心使用）',

# Edit pages
'summary'                          => 'Khài-iàu:',
'subject'                          => 'Tê-bo̍k/piau-tê:',
'minoredit'                        => 'Che sī sió siu-kái',
'watchthis'                        => 'Kàm-sī chit ia̍h',
'savearticle'                      => 'Pó-chûn chit ia̍h',
'preview'                          => 'Seng khoàⁿ-māi',
'showpreview'                      => 'Seng khoàⁿ-māi',
'showlivepreview'                  => '即時先看覓',
'showdiff'                         => 'Khòaⁿ kái-piàn ê pō·-hūn',
'anoneditwarning'                  => "'''Kéng-kò:''' Lí bô teng-ji̍p. Lí ê IP chū-chí ē kì tī pún ia̍h ê pian-chi̍p le̍k-sú lāi-bīn.",
'anonpreviewwarning'               => "''你並無登入，保存頁面的時陣，會共你的IP地址記錄佇這頁的編輯歷史。''",
'missingsummary'                   => "'''提醒：'''你無拍一个編輯標題，若你閣點「{{int:savearticle}}」一擺，你的編輯會無不帶標題保存起來。",
'missingcommenttext'               => '請佇下跤拍意見',
'missingcommentheader'             => "'''提醒：'''你無為你的意見寫一个標題，
若你閣點「{{int:savearticle}}」一擺，你的編輯會無帶標題保存起來。",
'summary-preview'                  => 'Khài-iàu ê preview:',
'subject-preview'                  => 'Ū-lám tê-bo̍k/piau-tê:',
'blockedtitle'                     => '用者hông封鎖',
'blockedtext'                      => "'''你的用者名稱抑是IP地址已經hông封鎖'''

這擺的封鎖是由$1所做的，
原因是''$2''。

* 這擺封鎖開始的時間是：$8
* 這擺封鎖到期的時間是：$6
* hông封鎖的用者：$7

妳會使聯絡$1抑是其他的[[{{MediaWiki:Grouppage-sysop}}|管理員]]來討論這擺封鎖。
除非你有佇你的[[Special:Preferences|口座設定]]當中，有設一个有效的電子批地址，若無，你是袂當使用「寄電子批予用者」的功能。若有，這个功能是無封鎖。
你這馬IP地址是$3，被封鎖用者ID是 #$5，
請佇你的詢問當中包括以上資料。",
'autoblockedtext'                  => "你的IP地址已經自動封鎖，因為彼个地址是一个予$1封鎖掉的用者咧用。

理由是：
：''$2''

* 這擺封鎖開始的時間是：$8
* 這擺封鎖到期的時間是：$6
* hông封鎖的用者：$7

你會使聯絡$1抑是其他的[[{{MediaWiki:Grouppage-sysop}}|管理員]]，討論這擺的封鎖。
除非你有佇你的[[Special:Preferences|用者設定]]當中，設一个有效的電子批地址，若無你是袂當使用「寄電子批予這个用戶」的功能。你並無hông封鎖寄電子批。

你這馬的IP地址是$3，被封鎖用者ID是 #$5，
請佇你的查詢當中，註明面頂所有的資料。",
'blockednoreason'                  => '無寫理由',
'blockedoriginalsource'            => "下跤顯示的是'''$1'''的原始碼：",
'blockededitsource'                => "你對'''$1'''所'''編輯'''的文字顯示佇下跤：",
'whitelistedittitle'               => 'Su-iàu teng-ji̍p chiah ē-sái siu-kái',
'whitelistedittext'                => 'Lí ài $1 chiah ē-sái siu-kái.',
'confirmedittext'                  => '佇改這頁進前，你愛確認你的電子批地址，
請透過[[Special:Preferences|用者設便]]的設定來驗證你的電子批地址。',
'nosuchsectiontitle'               => 'Chhoé bô toān-lo̍h',
'nosuchsectiontext'                => '你欲改的段落已經無佇咧，
可能佇你看頁面的時陣，已經徙位抑是刣掉。',
'loginreqtitle'                    => 'Su-iàu Teng-ji̍p',
'loginreqlink'                     => 'Teng-ji̍p',
'loginreqpagetext'                 => 'Lí ài $1 chiah thang khoàⁿ pat ia̍h.',
'accmailtitle'                     => 'Bi̍t-bé kià chhut khì ah.',
'accmailtext'                      => "Hō͘ [[User talk:$1|$1]] ê chi̍t ê iōng loān-sò͘ sán-seng ê bi̍t-bé í-keng kìa khì $2.

Kháu-chō ê sin bi̍t-bé thang tī teng-ji̍p liáu tī ''[[Special:ChangePassword|siu-kái bi̍t-bé]]'' ia̍h kái tiāu.",
'newarticle'                       => '(Sin)',
'newarticletext'                   => "Lí tòe 1 ê liân-kiat lâi kàu 1 bīn iáu-bōe chûn-chāi ê ia̍h. Beh khai-sí pian-chi̍p chit ia̍h, chhiáⁿ tī ē-kha ê bûn-jī keh-á lāi-té phah-jī. ([[{{MediaWiki:Helppage}}|Bo̍k-lio̍k]] kà lí án-choáⁿ chìn-hêng.) Ká-sú lí bô-tiuⁿ-tî lâi kàu chia, ē-sai chhi̍h liû-lám-khì ê '''téng-1-ia̍h''' tńg--khì.",
'anontalkpagetext'                 => "''Pún thó-lūn-ia̍h bô kò·-tēng ê kháu-chō/hō·-thâu, kan-na ū 1 ê IP chū-chí (chhin-chhiūⁿ 123.456.789.123). In-ūi bô kāng lâng tī bô kāng sî-chūn ū khó-lêng tú-hó kong-ke kāng-ê IP, lâu tī chia ê oē ū khó-lêng hō· bô kāng lâng ê! Beh pī-bián chit khoán būn-tê, ē-sái khì [[Special:UserLogin/signup|khui 1 ê hō·-thâu a̍h-sī teng-ji̍p]].''",
'noarticletext'                    => '這頁這馬無內容，
你會使佇別頁[[Special:Search/{{PAGENAME}}|搜揣這頁標題]]，
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 搜揣有關的記錄]，
抑是[{{fullurl:{{FULLPAGENAME}}|action=edit}} 編輯這頁]</span>。',
'noarticletext-nopermission'       => '這頁這馬無內容，
你會使佇別頁[[Special:Search/{{PAGENAME}}|揣這頁標題]]，
抑是<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}揣有關的記錄]</span>。',
'userpage-userdoesnotexist'        => '猶未有「$1」這个口座，
請佇開／改這頁進前先檢查一下。',
'userpage-userdoesnotexist-view'   => '用者口座「$1」猶未開',
'blocked-notice-logextract'        => '這个用者這馬hông封鎖，
下跤有最近封鎖的紀錄通參考：',
'clearyourcache'                   => "'''Chù-ì:''' Pó-chûn liáu-āu, tio̍h ē-kì leh kā liû-lám-khì ê cache piàⁿ tiāu chiah khoàⁿ-ē-tio̍h kái-piàn. 
*'''Firefox / Safari:''' chhi̍h tiâu \"Shift\" kâng-sî-chūn tiám-kik ''Reload/têng-sin chài-ji̍p'' a̍h-sī chhi̍h ''Ctrl-F5'' \"Ctrl-R\" kî-tiong chi̍t ê (''⌘-R'' tī Mac) 
* '''Google Chrome:''' chhi̍h ''Ctrl-Shift-R'' (''⌘-R-Shift-R'' tī Mac)
'''Internet Explorer :'''chhi̍h tiâu \"Ctrl\" kâng-sî-chūn tiám-kek ''Refresh/têng-sin chài-ji̍p'' a̍h-sī chhi̍h \"Ctrl-F5\" 
* '''Konqueror:'''  tiám-kek ''Reload/têng-sin chài-ji̍p'' a̍h-sī chhi̍h ''F5''
* '''Opera:''' piàⁿ-tiāu cache tī ''Tools(ke-si) → Preferences(siat-piān)''",
'usercssyoucanpreview'             => "'''Phiat-pō·''': Pó-chûn chìn-chêng ē-sái chhi̍h 'Seng khoàⁿ-māi' kiám-cha sin ê CSS.",
'userjsyoucanpreview'              => "'''Phiat-pō·''': Pó-chûn chìn-chêng ē-sái tiám-kek \"{{int:showpreview}}\" ; chhì lí ê sin JavaScript.",
'usercsspreview'                   => "'''Thê-chhíⁿ lí,  che chí-sī sian khoàⁿ-māi  lí ê su-jîn CSS'''
'''Che iáu-bōe pó-chûn--khí-lâi !'''",
'userjspreview'                    => "'''Sè-jī! Lí hiān-chú-sî chhì khoàⁿ--ê sī lí ka-kī ê javascript; che iáu-bōe pó-chûn--khí-lâi!'''",
'sitecsspreview'                   => "'''提醒你，這只是先看覓你的私人CSS'''
'''猶未保存！'''",
'sitejspreview'                    => "'''提醒你，這只是先看覓這个JavaScrpt程式'''
'''猶未保存！'''",
'userinvalidcssjstitle'            => "'''提醒：'''遐無面板\"\$1\"，
家己設的 .css 佮 .js 頁愛用小寫標題，親像，
{{ns:user}}:Foo/vector.css 無仝
{{ns:user}}:Foo/Vector.css。",
'updated'                          => '（改過矣）',
'note'                             => "'''Chù-ì:'''",
'previewnote'                      => "'''Thê-chhéⁿ lí che sī 1 bīn kiám-cha chho͘-phe ēng--ê \"seng-khoàⁿ-ia̍h\", iáu-bōe pó-chûn--khí-lâi!'''",
'previewconflict'                  => '這个先看覓會反應你文字編輯區的內容，顯示佇面頂。佇你保存了就會公開。',
'session_fail_preview'             => "'''Pháiⁿ-sè! Gún chiām-sî bô hoat-tō͘ chhú-lí lí ê pian-chi̍p (goân-in: \"phàng-kiàn sú-iōng kî-kan ê chu-liāu\"). Lô-hoân têng chhì khoàⁿ-māi. Ká-sú iû-goân bô-hāu, ē-sái teng-chhut koh-chài teng-ji̍p hoān-sè tō ē-tit kái-koat.'''",
'session_fail_preview_html'        => "'''歹勢！因為phàng見資料，阮無法度處理你的編輯。'''

''因為{{SITENAME}}有開放原始 HTML 碼，先看覓先看無，以防止 JavaScript 的攻擊。''

'''若這改編輯過程無問題，請閣試一改。若閣有問題，請[[Special:UserLogout|登出]]了後，才閣重登入。'''",
'token_suffix_mismatch'            => "'''因為你用者端的編輯毀損一寡標點符號字元，你的編輯無被接受。'''
這種情況會出現佇你用網路上匿名代理服務的時陣。",
'editing'                          => 'Siu-kái $1',
'editingsection'                   => 'Pian-chi̍p $1 (section)',
'editingcomment'                   => 'Teh pian-chi̍p $1 (lâu-oē)',
'editconflict'                     => 'Siu-kái sio-chhiong: $1',
'explainconflict'                  => "Ū lâng tī lí tng teh siu-kái pún-ia̍h ê sî-chūn oân-sêng kî-tha ê siu-kái.
Téng-koân ê bûn-jī-keh hián-sī hiān-chhú-sî siōng sin ê lōe-iông.
Lí ê kái-piàn tī ē-kha ê bûn-jī-keh. Lí su-iàu chiōng lí chò ê kái-piàn kap siōng sin ê lōe-iông chéng-ha̍p.
'''Kan-na''' téng-koân keh-á ê bûn-jī ē tī lí chhi̍h \"{{int:savearticle}}\" liáu-āu pó-chûn khí lâi.",
'yourtext'                         => 'Lí ê bûn-jī',
'storedversion'                    => 'Chu-liāu-khò· ê pán-pún',
'nonunicodebrowser'                => "'''提醒：你的瀏覽器佮Unicode編碼袂合。''
遮有一个工作區會使予你通安全編輯頁面: 
非ASCII字元會以十六進位編碼模式出現佇編輯框當中。",
'editingold'                       => "'''KÉNG-KÒ: Lí tng teh siu-kái chit ia̍h ê 1 ê kū siu-tēng-pún. Lí nā kā pó-chûn khí lâi, chit ê siu-tēng-pún sòa-āu ê jīm-hô kái-piàn ē bô khì.'''",
'yourdiff'                         => 'Chha-pia̍t',
'copyrightwarning'                 => "請注意你佇{{SITENAME}}的所有貢獻攏會照$2發布（看$1的說明）。
若你無希望你寫的文字hông隨意改抑是傳送，請毋莫佇遮送出。<br />
你嘛向阮保證你送出來的內容是你家己寫的，抑是對無版權抑有授權的遐抄來的。
'''毋通無授權就送出有版權作品！'''",
'copyrightwarning2'                => "請注意你佇{{SITENAME}}的所有貢獻，可能會予別的用者修改抑徙走，
若你無希望你寫的文字hông無情修改，就毋莫佇遮提交。<br />
你嘛向阮保證這是你家己寫的，抑是對無版權抑有授權(看$1的說明)的遐抄來的。
'''毋通無授權就送出有版權作品！'''",
'longpageerror'                    => "'''錯誤: 你送出來的文章長度有$1KB，這大過$2KB的上大界限。'''
伊無法度保存。",
'readonlywarning'                  => "'''CHÙ-Ì: Chu-liāu-khò· taⁿ só tiâu leh thang pān î-siu khang-khòe, só·-í lí hiān-chú-sî bô thang pó-chûn jīn-hô phian-chi̍p hāng-bo̍k. Lí ē-sái kā siong-koan pō·-hūn tah--ji̍p-khì 1-ê bûn-jī tóng-àn pó-chûn, āu-chhiú chiah koh kè-sio̍k.'''

Kā só tiâu ê koán-lí-goân ū lâu oē: $1",
'protectedpagewarning'             => "'''KÉNG-KÒ: Pún ia̍h só tiâu leh. Kan-taⁿ ū hêng-chèng te̍k-koân ê iōng-chiá (sysop) ē-sái siu-kái.'''
Ē-kha ū choè-kīn ê kì-lo̍k thang chham-khó:",
'semiprotectedpagewarning'         => "'''注意：'''這頁hông保護牢咧，只有有註冊的用者通編輯。
下跤有最近的記錄通參考：",
'cascadeprotectedwarning'          => "'''注意：'''這頁已經hông保護牢咧，只有有管理員權限的用者才有法度改，因為這頁佇{{PLURAL:$1|頁|頁}}的連鎖保護內底:",
'titleprotectedwarning'            => "'''注意：這頁已經hông保護牢咧，需要有[[Special:ListGroupRights|指定權限]]的才會當創建。'''
下跤有最近的記錄通參考：",
'templatesused'                    => 'Chit ia̍h iōng {{PLURAL:$1|Template|Templates}} chia ê pang-bô· :',
'templatesusedpreview'             => 'Chit ê preview iōng chia ê {{PLURAL:$1|Template|pang-bô͘}}',
'templatesusedsection'             => 'Chit ê toāⁿ-lo̍k iōng chia ê {{PLURAL:$1|Template|pang-bô͘}}',
'template-protected'               => '(pó-hō͘)',
'template-semiprotected'           => '(poàⁿ pó-hō͘)',
'hiddencategories'                 => '這頁是屬於{{PLURAL:$1|一个隱藏類別|$1个隱藏類別}}的成員：',
'nocreatetitle'                    => '欲創建頁hông限制',
'nocreatetext'                     => '{{SITENAME}}限制創建新頁的功能。你會當倒退佮改現有的頁，抑是[[Special:UserLogin|登入抑是開一个口座]]。',
'nocreate-loggedin'                => '你無授權去創建新頁。',
'sectioneditnotsupported-title'    => '編輯段落是袂當得',
'sectioneditnotsupported-text'     => '段落編輯佇這頁袂當得',
'permissionserrors'                => '授權錯誤',
'permissionserrorstext'            => '你無允准去做彼，因為下跤
{{PLURAL:$1|原因|原因}}:',
'permissionserrorstext-withaction' => 'Lí bô ún-chún chò $2, in-ūi ē-kha
{{PLURAL:$1|iân-kò͘|iân-kò͘}}:',
'recreate-moveddeleted-warn'       => "'''Sè-jī: Lí taⁿ chún-pī beh khui ê ia̍h, chêng bat hō͘ lâng thâi tiāu koè.''' 

Lí tio̍h chim-chiok soà-chiap pian-chi̍p chit ia̍h ê pit-iàu-sèng. 
Chia ū chit ia̍h ê san-tû kì-lo̍k hō͘ lí chham-khó:",
'moveddeleted-notice'              => '這頁已經hông刣掉，
刣掉佮徙走的記錄佇下跤通參考。',
'log-fulllog'                      => '看全部的記錄',
'edit-hook-aborted'                => '取消編輯，
無講啥物原因',
'edit-gone-missing'                => '無法度改新這頁，
伊可能拄hông刣掉。',
'edit-conflict'                    => 'Siu-kái sio-chhiong',
'edit-no-change'                   => '你的編輯閬過，因為攏無改著字。',
'edit-already-exists'              => '無法度開新頁，
已經有彼頁。',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''警示：'''這頁用傷濟擺函數呼叫。

伊應該少過{{PLURAL:$2|擺|擺}}，這馬有{{PLURAL:$1|擺|擺}}。",
'expensive-parserfunction-category'       => '用傷濟擺函數呼叫的頁',
'post-expand-template-inclusion-warning'  => "'''Kéng-pò:'''Pau ji̍t lâi ê pán-bôo sioⁿ koè tsē ia̍h tuā.
Ū chi̍t-koá-á ē bô pau ji̍t lâi.",
'post-expand-template-inclusion-category' => '頁的枋模所包的量已經超過',
'post-expand-template-argument-warning'   => "'''警示'''：這个頁至少包括一个枋模的參數超過展開時的大細。
遮的參數會忽略過。",
'post-expand-template-argument-category'  => '包括跳過枋模參數的頁面',
'parser-template-loop-warning'            => '踅圓框的枋模: [[$1]]',
'parser-template-recursion-depth-warning' => '已經超過枋模的recusion深度限制($1)',
'language-converter-depth-warning'        => '已經超過字詞轉換器的深度限制（$1）',

# "Undo" feature
'undo-success' => 'Pian-chi̍p í-keng chhú-siau. Chhiáⁿ khak-tēng, liáu-āu kā ē-kha ho̍k-goân ê kái-piàn pó-chûn--khí-lâi.',
'undo-failure' => 'Pian-chi̍p bē-tàng chhú-siau, in-ūi chhiong tio̍h kî-kan chhah-ji̍p ê pian-chi̍p.',
'undo-norev'   => '這个編輯袂當取消，因為無這个修訂本，抑是hông刣掉。',
'undo-summary' => 'Chhú-siau [[Special:Contributions/$2|$2]] ([[User talk:$2|thó-lūn]]) ê siu-tēng-pún $1',

# Account creation failure
'cantcreateaccounttitle' => '無法度開口座',
'cantcreateaccount-text' => "對這个 IP 地址 ('''$1''') 開口座已經予 [[User:$3|$3]] 禁止。

$3共禁止的原因是 ''$2''。",

# History pages
'viewpagelogs'           => 'Khoàⁿ chit ia̍h ê logs',
'nohistory'              => 'Chit ia̍h bô pian-chi̍p-sú.',
'currentrev'             => 'Hiān-chú-sî ê siu-tēng-pún',
'currentrev-asof'        => '$1的上新修訂本',
'revisionasof'           => '$1 ê siu-tēng-pún',
'revision-info'          => '$2佇$1的修訂本',
'previousrevision'       => '←Khah kū ê siu-tēng-pún',
'nextrevision'           => 'Khah sin ê siu-tēng-pún→',
'currentrevisionlink'    => 'khoàⁿ siōng sin ê siu-tēng-pún',
'cur'                    => 'taⁿ',
'next'                   => '下一个',
'last'                   => 'chêng',
'page_first'             => 'Tùi thâu-chêng',
'page_last'              => 'Tùi āu-piah',
'histlegend'             => 'Pán-pún pí-phēng: tiám-soán beh pí-phēng ê pán-pún ê liú-á, liáu-āu chhi̍h ENTER a̍h-sī ē-kha hit tè sì-kak.<br />Soat-bêng: (taⁿ) = kap siōng sin pán-pún pí-phēng, (chêng) = kap chêng-1-ê pán-pún pí-phēng, ~ = sió siu-kái.',
'history-fieldset-title' => '看歷史',
'history-show-deleted'   => '只有刣掉的',
'histfirst'              => 'Tùi thâu-chêng',
'histlast'               => 'Tùi āu-piah',
'historysize'            => '({{PLURAL:$1|1位元組|$1位元組}})',
'historyempty'           => '（空的）',

# Revision feed
'history-feed-title'          => '修改的歷史',
'history-feed-description'    => '這頁佇本站的修改歷史',
'history-feed-item-nocomment' => '$1 tī $2',
'history-feed-empty'          => '無你欲挃的頁，
伊可能hông刣掉抑是改名，
試[[Special:Search|搜揣本站]]，通創建新頁。',

# Revision deletion
'rev-deleted-comment'         => '（編輯概要已經清掉）',
'rev-deleted-user'            => '用者名稱已經清掉',
'rev-deleted-event'           => '動作的記錄已經清掉',
'rev-deleted-user-contribs'   => '[用者名稱抑是IP地址已經徙掉 - 佇貢獻當中隱藏編輯]',
'rev-deleted-text-permission' => "這頁的修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]，有詳細的訊息。",
'rev-deleted-text-unhide'     => "這頁的修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]，
若你欲繼續行，你照仝會使[$1看這个修訂本]。",
'rev-suppressed-text-unhide'  => "這頁的修訂本已經hông'''壓縮掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 壓縮記錄]，
若你欲繼續行，你照仝會使[$1看這个修訂本]。",
'rev-deleted-text-view'       => "這頁的修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]，有詳細的訊息。",
'rev-suppressed-text-view'    => "這頁的修訂本已經hông'''壓縮掉'''。
你會使佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 壓縮記錄]看詳細。",
'rev-deleted-no-diff'         => "你無法度看精差，因為其中一个修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]有通看詳細。",
'rev-suppressed-no-diff'      => "你無法度看精差，因為其中一个修訂本已經hông'''刣掉\"。",
'rev-deleted-unhide-diff'     => "欲做精差比並的一个修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]，
若你欲繼續行，你照仝會使[$1看這个精差比並]。",
'rev-suppressed-unhide-diff'  => '精差比並的其中一个修訂本已經hông壓縮掉。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 壓縮記錄]通看詳細，
若你欲繼續行，你照仝會使[$1看這个精差比並]。',
'rev-deleted-diff-view'       => "欲做精差比並的一个修訂本已經hông'''刣掉'''。
佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 刣掉記錄]，通看這个精差比並。",
'rev-suppressed-diff-view'    => "欲做精差比並的一个修訂本已經hông'''壓縮掉'''。
你會使佇[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 壓縮記錄]，看這个精差比並。",
'rev-delundel'                => '顯示／掩',
'rev-showdeleted'             => '顯示',
'revisiondelete'              => '刣掉/取消刣掉 修訂本',
'revdelete-nooldid-title'     => '目標是無效的修訂本',
'revdelete-nooldid-text'      => '你欲用這个功能進前無指定欲改的修訂本，抑是無你指定的修訂本，抑是你欲改現時的版本隱藏起來。',
'revdelete-nologtype-title'   => '無指定記錄的類型',
'revdelete-nologtype-text'    => '你無指定佗一个記錄類型欲做這个動作',
'revdelete-nologid-title'     => '無效的記錄項目',
'revdelete-nologid-text'      => '你無指定佗一个記錄項目欲進行這个動作，抑是無你指定的項目。',
'revdelete-no-file'           => '無你指定的檔案',
'revdelete-show-file-confirm' => '你敢確定欲看"<nowiki>$1</nowiki>"佇 $2 $3 刣掉的修訂本？',
'revdelete-show-file-submit'  => '是',
'revdelete-selected'          => "'''[[:$1]]{{PLURAL:$2|所選的修訂本|所選的修訂本}}：'''",
'logdelete-selected'          => "'''{{PLURAL:$1|所選的記錄項目|所選的記錄項目}}：'''",
'revdelete-text'              => "'''佇頁面歷史佮記錄猶看有刣掉的修訂本佮彼件物，毋過內容部份是無予大眾看。'''
佇{{SITENAME}}的其他管理員是會當看隱藏的內容，而且除非有另外附加的限制，伊用這个仝款介面通取消刣掉。",
'revdelete-confirm'           => '請確定你欲按呢做，你嘛了解後果，而且你欲做的這个動作符合[[{{MediaWiki:Policy-url}}|政策]]。',
'revdelete-suppress-text'     => "掩崁'''只'''佇下跤情況下才使用:
* 可能是誹謗信息
* 無適當的個人資料
*：''厝的地址、電話號碼、社會安全號碼抑身份證號碼等等。''",
'revdelete-legend'            => '設定通看的制限',
'revdelete-hide-text'         => '隱藏修訂本文本',
'revdelete-hide-image'        => '隱藏檔案內容',
'revdelete-hide-name'         => '隱藏動作佮目標',
'revdelete-hide-comment'      => '隱藏編輯概要',
'revdelete-hide-user'         => '隱藏編輯者的名稱抑 IP 地址',
'revdelete-hide-restricted'   => '對系統管理員佮其他人攏掩崁資料',
'revdelete-radio-same'        => '（毋共改）',
'revdelete-radio-set'         => '是',
'revdelete-radio-unset'       => '毋是',
'revdelete-suppress'          => '對系統管理員佮其他人攏掩崁資料',
'revdelete-unsuppress'        => '共恢復的修訂本徙掉限制',
'revdelete-log'               => '理由：',
'revdelete-submit'            => '對所選的{{PLURAL:$1|修訂本}}來施實',
'revdelete-logentry'          => '改"[[$1]]"修訂本的可見性質',
'logdelete-logentry'          => '改"[[$1]]"的一个事件可見性質',
'revdelete-success'           => "'''改修訂本是毋是通予人看，已經改好矣'''",
'revdelete-failure'           => "'''改修訂本是毋是通予人看的動作無成功'''
$1",
'logdelete-success'           => "'''事件的可見性質已經成功設定'''",
'logdelete-failure'           => "'''事件的可見性質無法度設定：'''
$1",
'revdel-restore'              => '改敢看會著',
'revdel-restore-deleted'      => '刣掉去的修訂本',
'revdel-restore-visible'      => '看會著的修訂本',
'pagehist'                    => '頁的歷史',
'deletedhist'                 => '已經刣掉的歷史',
'revdelete-content'           => '內容',
'revdelete-summary'           => '編輯概要',
'revdelete-uname'             => '用者名稱',
'revdelete-restricted'        => '已經共限制用佇管裡員',
'revdelete-unrestricted'      => '徙走對管裡員的限制',
'revdelete-hid'               => '隱藏 $1',
'revdelete-unhid'             => '無隱藏 $1',
'revdelete-log-message'       => '$1的{{PLURAL:$2|个|个}}修訂本',
'logdelete-log-message'       => '$1的{{PLURAL:$2|項|項}}事件',
'revdelete-hide-current'      => '當咧隱藏佇$1 $2的項目錯誤：這是這馬的修訂本，袂使隱藏。',
'revdelete-show-no-access'    => '當咧顯示佇$1 $2的項目錯誤：這个項目已經標示做"有限制"，
你袂當處理。',
'revdelete-modify-no-access'  => "當欲改$1 $2項目的錯誤：這个項目已經標示做''有限制''，
你袂當處理。",
'revdelete-modify-missing'    => '當咧改項目編號 $1錯誤：伊對資料庫當中消失！',
'revdelete-no-change'         => "'''提醒'''：佇$1 $2的項目已經有人請求可見性質的設定。",
'revdelete-concurrent-change' => '錯誤佇欲改$1 $2的項目：當你欲改伊的設定時，已經有另外的人共改過。
請檢查記錄。',
'revdelete-only-restricted'   => '錯誤佇欲隱藏$1 $2的項目時發生：你袂當一方面選擇一項另外的可見性質，閣不准管理員看彼項目。',
'revdelete-reason-dropdown'   => '*捷用的刣掉理由
** 侵犯版權
** 不適合的個人資料
** 可能是誹謗資料',
'revdelete-otherreason'       => '其他／另外的理由：',
'revdelete-reasonotherlist'   => '其他理由',
'revdelete-edit-reasonlist'   => '編輯刣掉的理由',
'revdelete-offender'          => '修訂本的編輯者：',

# Suppression log
'suppressionlog'     => '隱藏記錄',
'suppressionlogtext' => '下跤是管理員有插手著的刣掉、封鎖清單。
參看[[Special:BlockList|IP封鎖名單]]有現此時禁止佮封鎖的名單。',

# History merging
'mergehistory'                     => '合併兩个頁的修改歷史:',
'mergehistory-header'              => '這頁通予你合併一个頁的歷史到另外一个新的頁。
會當予這改變更通接紲歷史頁。',
'mergehistory-box'                 => '合併兩个頁的修訂本:',
'mergehistory-from'                => '來源頁：',
'mergehistory-into'                => '目標頁：',
'mergehistory-list'                => '可以合併的編輯歷史',
'mergehistory-merge'               => '下跤[[:$1]]的修訂本會使合併到[[:$2]]。用彼个選項鈕仔去合併只有佇指定時間進前所創建的修訂本。愛注意的是若使用導航連結就會重設這一欄。',
'mergehistory-go'                  => '顯示通合併的編輯',
'mergehistory-submit'              => '合併修訂本',
'mergehistory-empty'               => '無修訂本通合併',
'mergehistory-success'             => '[[:$1]]的{{PLURAL:$3|篇|篇}}修訂本已經成功合併到[[:$2]]。',
'mergehistory-fail'                => '無法度進行歷史的合併，請重新檢查彼頁佮時間參數。',
'mergehistory-no-source'           => '無$1這个來源頁',
'mergehistory-no-destination'      => '無$1這个目標頁',
'mergehistory-invalid-source'      => '來源頁愛有一个有效的標題',
'mergehistory-invalid-destination' => '目標頁愛有一个有效的標題',
'mergehistory-autocomment'         => '已經合併[[:$1]]到[[:$2]]',
'mergehistory-comment'             => '已經合併[[:$1]]到[[:$2]]: $3',
'mergehistory-same-destination'    => '來源頁佮目標頁袂使相仝',
'mergehistory-reason'              => '理由：',

# Merge log
'mergelog'           => '合併記錄',
'pagemerge-logentry' => '已經共[[$1]]合併到[[$2]] （修訂本到$3）',
'revertmerge'        => '取消合併',
'mergelogpagetext'   => '下跤是最近共一頁的歷史合併到另一个的列表',

# Diffs
'history-title'            => '改"$1"的歷史',
'difference'               => '(Bô kâng pán-pún ê cheng-chha)',
'difference-multipage'     => '（頁中間的精差）',
'lineno'                   => 'Tē $1 chōa:',
'compareselectedversions'  => 'Pí-phēng soán-te̍k ê pán-pún',
'showhideselectedversions' => '顯示／隱藏 選定的修訂版本',
'editundo'                 => 'chhú-siau',
'diff-multi'               => '（由{{PLURAL:$2|个用者|$2个用者}}的{{PLURAL:$1|一个中央修訂本|$1个中央修訂本}}無顯示）',
'diff-multi-manyusers'     => '（{{PLURAL:$2|个用者|$2个用者}}的{{PLURAL:$1|一个中途修訂本|$1个中途修訂本}}無顯示）',

# Search results
'searchresults'                    => 'Kiám-sek kiat-kó',
'searchresults-title'              => 'Chhoé "$1" ê kiat-kó',
'searchresulttext'                 => 'Koan-hē kiám-sek {{SITENAME}} ê siông-sè pō·-sò·, chhiáⁿ chham-khó [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => '揣\'\'\'[[:$1]]\'\'\'（[[Special:Prefixindex/$1|所有以 "$1" 做頭的頁]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|所有連結到 "$1" 的頁]]）',
'searchsubtitleinvalid'            => '揣"$1"',
'toomanymatches'                   => '揣著傷濟，請試另外一款方式',
'titlematches'                     => 'Phiau-tê ū-tùi ê bûn-chiuⁿ',
'notitlematches'                   => 'Bô sio-tùi ê ia̍h-piau-tê',
'textmatches'                      => 'Lōe-iông ū-tùi ê bûn-chiuⁿ',
'notextmatches'                    => 'Bô sio-tùi ê bûn-chiuⁿ lōe-iông',
'prevn'                            => 'chêng {{PLURAL:$1|$1}} hāng',
'nextn'                            => 'āu {{PLURAL:$1|$1}} hāng',
'prevn-title'                      => '前$1个{{PLURAL:$1|結果|結果}}',
'nextn-title'                      => '後$1个{{PLURAL:$1|結果|結果}}',
'shown-title'                      => 'Múi ia̍h hián-sī $1 {{PLURAL:$1|kiat-kó|kiat-kó}}',
'viewprevnext'                     => 'Khoàⁿ ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => '搜揣的選項',
'searchmenu-exists'                => "'''佇這个wiki遐，有一个頁叫做「[[:$1]]」'''",
'searchmenu-new'                   => "'''佇這个 wiki建立「[[:$1]]」這个頁！'''",
'searchhelp-url'                   => 'Help:Bo̍k-lio̍k',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|看頁標題頭前相仝的頁]]',
'searchprofile-articles'           => 'Loē-iông ia̍h',
'searchprofile-project'            => '幫助佮事工頁',
'searchprofile-images'             => 'To-mûi-thé',
'searchprofile-everything'         => 'Só͘-ū ê',
'searchprofile-advanced'           => 'chìn-chi̍t-pō͘',
'searchprofile-articles-tooltip'   => 'Tī $1 chhoé',
'searchprofile-project-tooltip'    => '揣$1內底的',
'searchprofile-images-tooltip'     => 'Chhoé tóng-àn',
'searchprofile-everything-tooltip' => '搜索全部（包括討論頁）',
'searchprofile-advanced-tooltip'   => '佇自定的名空間中搜揣',
'search-result-size'               => '$1 ({{PLURAL:$2|1 jī-goân|$2 jī-goân}})',
'search-result-category-size'      => '{{PLURAL:$1|一个成員|$1成員}} ({{PLURAL:$2|一个下類別|$2个下類別}}，{{PLURAL:$3|一个檔案|$3个檔案}})',
'search-result-score'              => '相關度: $1%',
'search-redirect'                  => '（改向 $1）',
'search-section'                   => '(toān-lo̍h $1)',
'search-suggest'                   => '你是欲：$1',
'search-interwiki-caption'         => '姊妹事工',
'search-interwiki-default'         => '$1項結果:',
'search-interwiki-more'            => '（閣有）',
'search-mwsuggest-enabled'         => '有建議',
'search-mwsuggest-disabled'        => '無建議',
'search-relatedarticle'            => '相關的',
'mwsuggest-disable'                => '停掉AJAX的建議',
'searcheverything-enable'          => '揣所有的名空間',
'searchrelated'                    => '相關的',
'searchall'                        => 'choân-pō·',
'showingresults'                   => "Ē-kha tùi #'''$2''' khai-sí hián-sī {{PLURAL:$1| hāng| hāng}} kiat-kó.",
'showingresultsnum'                => "Ē-kha tùi #'''$2''' khai-sí hián-sī {{PLURAL:$3| hāng| hāng}} kiat-kó.",
'showingresultsheader'             => "對'''$4'''的{{PLURAL:$5|第'''$1'''到第'''$3'''項結果|第'''$1 - $2'''項，總共'''$3'''項結果}}",
'nonefound'                        => "'''注意'''：只有一寡名空間是預設會去揣。試''all:''去揣所有的頁（包括討論頁、枋模等等），抑是頭前指定名空間。",
'search-nonefound'                 => '揣無欲愛的',
'powersearch'                      => 'Kiám-sek',
'powersearch-legend'               => 'Kiám-sek',
'powersearch-ns'                   => '佇下跤的名空間揣：',
'powersearch-redir'                => '轉頁清單',
'powersearch-field'                => '揣',
'powersearch-togglelabel'          => '選定：',
'powersearch-toggleall'            => '所有的',
'powersearch-togglenone'           => '無',
'search-external'                  => '外部的搜揣',
'searchdisabled'                   => '{{SITENAME}}因為性能方面的原因，全文搜揣已經暫時停用。你會使暫時透過Google搜揣。請注意怹的索引可能過時。',

# Quickbar
'qbsettings'               => 'Quickbar ê siat-tēng',
'qbsettings-none'          => '無',
'qbsettings-fixedleft'     => '倒手爿固定',
'qbsettings-fixedright'    => '正手爿固定',
'qbsettings-floatingleft'  => '倒手爿無固定',
'qbsettings-floatingright' => '正手爿無固定',

# Preferences page
'preferences'                   => 'Siat-tēng',
'mypreferences'                 => 'Góa ê siat-tēng',
'prefs-edits'                   => '編輯幾擺：',
'prefsnologin'                  => 'Bô teng-ji̍p',
'prefsnologintext'              => 'Lí it-tēng ài <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} teng-ji̍p]</span> chiah ē-tàng chhiâu iōng-chiá ê siat-tēng.',
'changepassword'                => 'Oāⁿ bi̍t-bé',
'prefs-skin'                    => 'Phôe',
'skin-preview'                  => 'Chhì khoàⁿ',
'prefs-math'                    => 'Sò·-ha̍k ê rendering',
'datedefault'                   => 'Chhìn-chhái',
'prefs-datetime'                => 'Ji̍t-kî kap sî-kan',
'prefs-personal'                => 'Iōng-chiá chu-liāu',
'prefs-rc'                      => 'Chòe-kīn ê kái-piàn & stub ê hián-sī',
'prefs-watchlist'               => 'Kàm-sī-toaⁿ',
'prefs-watchlist-days'          => 'Kàm-sī-toaⁿ hián-sī kúi kang lāi--ê:',
'prefs-watchlist-days-max'      => '上濟七工',
'prefs-watchlist-edits'         => 'Khok-chhiong ê kàm-sī-toaⁿ tio̍h hián-sī kúi hāng pian-chi̍p:',
'prefs-watchlist-edits-max'     => '上大的數目：1000',
'prefs-watchlist-token'         => '監視列表的密鑰：',
'prefs-misc'                    => 'Kî-thaⁿ ê siat-tēng',
'prefs-resetpass'               => '改密碼',
'prefs-email'                   => '電子批的選項',
'prefs-rendering'               => '外觀',
'saveprefs'                     => 'Pó-chûn siat-tēng',
'resetprefs'                    => 'Têng siat-tēng',
'restoreprefs'                  => '全部攏恢復做設便的',
'prefs-editing'                 => 'Pian-chi̍p',
'prefs-edit-boxsize'            => '編輯框的寸尺',
'rows'                          => 'Chōa:',
'columns'                       => 'Nôa',
'searchresultshead'             => 'Chhiau-chhōe kiat-kó ê siat-tēng',
'resultsperpage'                => '1 ia̍h hián-sī kúi kiāⁿ:',
'contextlines'                  => '1 kiāⁿ hián-sī kúi chōa:',
'contextchars'                  => '1 chōa hián-sī kúi jī ê chêng-āu-bûn:',
'stub-threshold'                => '<a href="#" class="stub">短頁連結</a>的門檻值 （位元組）:',
'stub-threshold-disabled'       => '莫用',
'recentchangesdays'             => 'Hián-sī kúi ji̍t chòe-kīn ê kái-piàn:',
'recentchangesdays-max'         => '上濟$1{{PLURAL:$1|工|工}}',
'recentchangescount'            => 'Beh hián-sī kúi tiâu chòe-kīn kái--ê:',
'prefs-help-recentchangescount' => '這包括最近改的、頁的歷史佮記錄',
'prefs-help-watchlist-token'    => '佇這个欄位加入一个密鑰，伊佇你訂看監視清單 RSS內底嘛會產生。
任何人若知影這个欄位的密鑰，就會當看你的監視清單，請選一个安全的數字。
遮有一个隨意產生的數字你通用：$1',
'savedprefs'                    => 'Lí ê iōng-chiá siat-tēng í-keng pó-chûn khí lâi ah.',
'timezonelegend'                => 'Sî-khu',
'localtime'                     => 'Chāi-tē sî-kan sī:',
'timezoneuseserverdefault'      => '用伺服器設便的',
'timezoneuseoffset'             => '其他 （指定偏差量）',
'timezoneoffset'                => 'Sî-chha¹:',
'servertime'                    => 'Server sî-kan hiān-chāi sī:',
'guesstimezone'                 => 'Tùi liû-lám-khì chhau--lâi',
'timezoneregion-africa'         => '非洲',
'timezoneregion-america'        => '美洲',
'timezoneregion-antarctica'     => '南極洲',
'timezoneregion-arctic'         => '北極',
'timezoneregion-asia'           => '亞洲',
'timezoneregion-atlantic'       => '大西洋',
'timezoneregion-australia'      => '澳洲',
'timezoneregion-europe'         => '歐洲',
'timezoneregion-indian'         => '印度洋',
'timezoneregion-pacific'        => '太平洋',
'allowemail'                    => 'Ún-chún pa̍t-ê iōng-chiá kià email kòe-lâi',
'prefs-searchoptions'           => '搜揣的選項',
'prefs-namespaces'              => '名空間',
'defaultns'                     => 'Tī chiah ê miâ-khong-kan chhiau-chhōe:',
'default'                       => '設便',
'prefs-files'                   => 'Tóng-àn',
'prefs-custom-css'              => ' 家己設的CSS',
'prefs-custom-js'               => ' 家己設的JavaScript',
'prefs-common-css-js'           => '共 CSS/JavaScript 分享佇所有的外觀：',
'prefs-reset-intro'             => '你會當用這頁去改做原本設便的。
這个動作無法度取消。',
'prefs-emailconfirm-label'      => '電子批的確定：',
'prefs-textboxsize'             => '編輯框的大細',
'youremail'                     => 'Lí ê email:',
'username'                      => '用者名稱：',
'uid'                           => '用者編號：',
'prefs-memberingroups'          => '{{PLURAL:$1|這陣人|這陣人}}的成員：',
'prefs-registration'            => '註冊時間：',
'yourrealname'                  => 'Lí ê chin miâ:',
'yourlanguage'                  => 'Kài-bīn gú-giân:',
'yourvariant'                   => '頁內容的語文：',
'yournick'                      => 'Lí ê sió-miâ (chhiam-miâ iōng):',
'prefs-help-signature'          => '佇討論頁的評論應該愛用「<nowiki>~~~~</nowiki>」簽名，彼會轉變做你的簽名佮戳印一个時間。',
'badsig'                        => '錯誤的原始簽名，
請檢查HTML標籤。',
'badsiglength'                  => '你的簽名傷過長，
伊的長度袂使超過{{PLURAL:$1|个|个}}字元。',
'yourgender'                    => '性別：',
'gender-unknown'                => '無表明',
'gender-male'                   => '查埔',
'gender-female'                 => '查某',
'prefs-help-gender'             => '選項：用佇軟體的性別指定，
這項資料會公開。',
'email'                         => '電子批',
'prefs-help-realname'           => '你的真實名字無一定愛，
若你欲提供，伊會附佇你貢 獻的作品。',
'prefs-help-email'              => '電子批地址無一定愛，毋過若你袂記得密碼的時陣，閣欲重設密碼就需要，
你無需要公開家己的身分，你會當用你的用者頁、用者討論頁來予別人佮你連絡。',
'prefs-help-email-required'     => '愛有電子批地址',
'prefs-info'                    => '基本資料',
'prefs-i18n'                    => '國際化',
'prefs-signature'               => '簽名',
'prefs-dateformat'              => '顯示日期的規格',
'prefs-timeoffset'              => '佮標準時間的偏差',
'prefs-advancedediting'         => '進一步的選項',
'prefs-advancedrc'              => '進一步的選項',
'prefs-advancedrendering'       => '進一步的選項',
'prefs-advancedsearchoptions'   => '進一步的選項',
'prefs-advancedwatchlist'       => '進一步的選項',
'prefs-displayrc'               => '顯示的選項',
'prefs-displaysearchoptions'    => '顯示的選項',
'prefs-displaywatchlist'        => '顯示的選項',
'prefs-diffs'                   => '精差',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => '電子批地址看起來是有效的',
'email-address-validity-invalid' => '拍一个有效的電子批地址',

# User rights
'userrights'                   => '用者的權限管理',
'userrights-lookup-user'       => '管理用者的陣營',
'userrights-user-editname'     => '輸入一个用者名稱：',
'editusergroup'                => '設定用者的陣營',
'editinguser'                  => "當咧改用者'''[[User:$1|$1]]''' （[[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]）的使用權",
'userrights-editusergroup'     => '設定用者的陣營',
'saveusergroups'               => '保存用者的陣營',
'userrights-groupsmember'      => '成員：',
'userrights-groupsmember-auto' => '自本的成員：',
'userrights-groups-help'       => '你會動改用者所屬的陣營：
* 頭前有勾起來的代表用者屬彼陣營
* 頭前無勾起來的代表用者無屬彼陣營
* 有 * 的項目，表示你會當加袂當減倒轉來，抑是會當減袂當加倒轉來',
'userrights-reason'            => '理由：',
'userrights-no-interwiki'      => '你無權去設定其它wiki上的用者權利。',
'userrights-nodatabase'        => '無$1資料庫抑是非本地的',
'userrights-nologin'           => '你愛管理員的口座[[Special:UserLogin|登入]]了後，才會當指定用者權利。',
'userrights-notallowed'        => '你口座的無授權你會當加添用者權利',
'userrights-changeable-col'    => '你會當改的陣營',
'userrights-unchangeable-col'  => '你袂當改的陣營',

# Groups
'group'               => '陣營：',
'group-user'          => '用者：',
'group-autoconfirmed' => '自動確認的用者',
'group-bot'           => '機器人',
'group-sysop'         => '管理員',
'group-bureaucrat'    => '行政人員',
'group-suppress'      => '監督',
'group-all'           => '（全部）',

'group-user-member'          => '用者',
'group-autoconfirmed-member' => '自動確認的用者',
'group-bot-member'           => '機器人',
'group-sysop-member'         => '管理員',
'group-bureaucrat-member'    => '行政人員',
'group-suppress-member'      => '監督',

'grouppage-user'          => '{{ns:project}}:用者',
'grouppage-autoconfirmed' => '{{ns:project}}:自動確認的用者',
'grouppage-bot'           => '{{ns:project}}:機器人',
'grouppage-sysop'         => '{{ns:project}}:Hêng-chèng jîn-oân',
'grouppage-bureaucrat'    => '{{ns:project}}:行政人員',
'grouppage-suppress'      => '{{ns:project}}:監督',

# Rights
'right-read'                  => '看頁',
'right-edit'                  => '改頁',
'right-createpage'            => '開新頁（無包括討論頁）',
'right-createtalk'            => '開新討論頁',
'right-createaccount'         => '開新用者口座',
'right-minoredit'             => '標示做小編輯',
'right-move'                  => '徙頁',
'right-move-subpages'         => '徙頁，連伊的次頁',
'right-move-rootuserpages'    => '徙用者root的頁',
'right-movefile'              => '徙檔案',
'right-suppressredirect'      => '徙頁的時陣，無共原本的頁改做轉向頁',
'right-upload'                => '上載檔案',
'right-reupload'              => '取代原本的檔案',
'right-reupload-own'          => '取代別人上載的原本檔案',
'right-reupload-shared'       => '莫用共用媒體檔案庫上的檔案',
'right-upload_by_url'         => '對一个網址(URL)上載檔案',
'right-purge'                 => '直接清掉網站頁的cache，毋免閣確定',
'right-autoconfirmed'         => '編輯半保護的頁',
'right-bot'                   => '看做是一个自動程序',
'right-nominornewtalk'        => '佇討論頁的小編輯無發新訊息',
'right-apihighlimits'         => '佇API查詢的時陣，用較懸的限制量',
'right-writeapi'              => '使用API編寫',
'right-delete'                => '刣頁',
'right-bigdelete'             => '刣掉頁的誠濟歷史',
'right-deleterevision'        => '刣掉佮取消刣掉頁的指定修訂本',
'right-deletedhistory'        => '看已經刣掉的歷史項目，不包括相關的文本',
'right-deletedtext'           => '看已經刣掉修訂本當中，刣掉的文字佮變化',
'right-browsearchive'         => '揣刣掉的頁',
'right-undelete'              => '共刣掉的頁救倒轉來',
'right-suppressrevision'      => '恢復由管理員隱藏掉的修訂本',
'right-suppressionlog'        => '看私人的記錄',
'right-block'                 => '封鎖其他用者，予怹袂當編輯',
'right-blockemail'            => '封鎖一个用者，予伊袂當寄電子批',
'right-hideuser'              => '封鎖一个用者名稱，無對大眾公開',
'right-ipblock-exempt'        => '跳過IP封鎖、自動封鎖佮範圍封鎖',
'right-proxyunbannable'       => '跳過Proxy的自動封鎖',
'right-unblockself'           => '取消怹的封鎖',
'right-protect'               => '改保護層級而且編輯hông保護的頁',
'right-editprotected'         => '編輯保護中的頁（無連鎖保護）',
'right-editinterface'         => '編輯用者介面',
'right-editusercssjs'         => '編輯其他用者的CSS佮JavaScript檔案',
'right-editusercss'           => '編輯其他用者的CSS檔案',
'right-edituserjs'            => '編輯其他用者的JavaScript檔案',
'right-rollback'              => '共某一頁的頂一个用戶所做的編輯鉸轉去',
'right-markbotedits'          => '共復原編輯標示做機械人編輯',
'right-noratelimit'           => '無受著頻率限制的影響',
'right-import'                => '對別个Wiki匯入頁',
'right-importupload'          => '對一个上載檔案匯入頁',
'right-patrol'                => '共其它的編輯攏標示做已巡過',
'right-autopatrol'            => '家己的編輯自動標示做巡過',
'right-patrolmarks'           => '看最近巡查編輯的標記',
'right-unwatchedpages'        => '看頁無人監視的清單',
'right-trackback'             => '送出一个trackback',
'right-mergehistory'          => '相佮一寡頁的歷史',
'right-userrights'            => '編輯所有用者的權利限制',
'right-userrights-interwiki'  => '編輯對其它wiki來的用者權限',
'right-siteadmin'             => '封鎖閣開鎖資料庫',
'right-reset-passwords'       => '重設定其他用者的密碼',
'right-override-export-depth' => '輸出頁，包括連到的頁到5層深',
'right-sendemail'             => '寄電子批予其他用者',

# User rights log
'rightslog'      => '用者使用權記錄',
'rightslogtext'  => 'Chit-ê log lia̍t-chhut kái-piàn iōng-chiá koân-lī ê tōng-chok.',
'rightslogentry' => '共 $1 的權利限制對 $2 改做 $3',
'rightsnone'     => '（無）',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => '看這頁',
'action-edit'                 => 'Siu-kái chit ia̍h',
'action-createpage'           => '開新頁',
'action-createtalk'           => '開討論頁',
'action-createaccount'        => '開這个用者口座',
'action-minoredit'            => '標示做小編輯',
'action-move'                 => '徙這頁',
'action-move-subpages'        => '徙這頁，佮伊的次頁',
'action-move-rootuserpages'   => '徙用者root的頁',
'action-movefile'             => '徙這个檔案',
'action-upload'               => '上載這个檔案',
'action-reupload'             => '取代原本的檔案',
'action-reupload-shared'      => '莫用共用媒體檔案庫面頂的檔案',
'action-upload_by_url'        => '對一个網址(URL)上載這个檔案',
'action-writeapi'             => '使用API編寫',
'action-delete'               => '刣掉這頁',
'action-deleterevision'       => '刣掉這个修訂本',
'action-deletedhistory'       => '看這个頁hông刣掉的歷史',
'action-browsearchive'        => '揣刣掉的頁',
'action-undelete'             => '共刣掉的頁救倒轉來',
'action-suppressrevision'     => '看而且取消這个藏起來的修訂本',
'action-suppressionlog'       => '看這个私人記錄',
'action-block'                => '封鎖這个用者，予伊袂當編輯',
'action-protect'              => '改這頁的保護層級',
'action-import'               => '對別个Wiki匯入這頁',
'action-importupload'         => '對一个上載檔案匯入這頁',
'action-patrol'               => '標示其它的編輯是巡過的',
'action-autopatrol'           => '你的編輯標示做已巡查過',
'action-unwatchedpages'       => '看無予人監視的頁列單',
'action-trackback'            => '送交一个trackback',
'action-mergehistory'         => '相佮這頁的歷史',
'action-userrights'           => '編輯所有用者的權限',
'action-userrights-interwiki' => '編輯對其它wiki來的用者權限',
'action-siteadmin'            => '封鎖抑開鎖資料庫',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|改|改}}',
'recentchanges'                     => 'Chòe-kīn ê kái-piàn',
'recentchanges-legend'              => '最近編輯的選項',
'recentchangestext'                 => '佇這頁，看阮這个Wiki最近改的',
'recentchanges-feed-description'    => '訂看這个Wiki最近改的',
'recentchanges-label-newpage'       => 'Chit ê siu-kái ē sán-seng sin ia̍h',
'recentchanges-label-minor'         => 'Che sī sió siu-kái',
'recentchanges-label-bot'           => '這个編輯是機器人做的',
'recentchanges-label-unpatrolled'   => '這个編輯猶未巡過',
'rcnote'                            => "下面是佇$4 $5，最近{{PLURAL:$2|工|'''$2'''工}}內的{{PLURAL:$1|'''1'''改|頂'''$1'''改}}修改記錄。",
'rcnotefrom'                        => 'Ē-kha sī <b>$2</b> kàu taⁿ ê kái-piàn (ke̍k-ke hián-sī <b>$1</b> hāng).',
'rclistfrom'                        => 'Hián-sī tùi $1 kàu taⁿ ê sin kái-piàn',
'rcshowhideminor'                   => '$1 sió siu-kái',
'rcshowhidebots'                    => '$1機器人所做的',
'rcshowhideliu'                     => '$1 teng-ji̍p ê iōng-chiá',
'rcshowhideanons'                   => '$1 bû-bêng-sī',
'rcshowhidepatr'                    => '$1巡過的編輯',
'rcshowhidemine'                    => '$1 góa ê pian-chi̍p',
'rclinks'                           => 'Hían-sī $2 ji̍t lāi siōng sin ê $1 hāng kái-piàn<br />$3',
'diff'                              => 'Cheng-chha',
'hist'                              => 'ls',
'hide'                              => 'am',
'show'                              => 'hían-sī',
'minoreditletter'                   => '~',
'newpageletter'                     => '!',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1个愛注意的{{PLURAL:$1|用者|用者}}]',
'rc_categories'                     => '分類界線（以"|"分開）',
'rc_categories_any'                 => '任何',
'newsectionsummary'                 => '/* $1 */ 新段落',
'rc-enhanced-expand'                => '看內容（愛有JavaScript）',
'rc-enhanced-hide'                  => '藏內容',

# Recent changes linked
'recentchangeslinked'          => 'Siong-koan ê kái-piàn',
'recentchangeslinked-feed'     => 'Siong-koan ê kái-piàn',
'recentchangeslinked-toolbox'  => 'Siong-koan ê kái-piàn',
'recentchangeslinked-title'    => '連到「$1」的頁閣有改過的',
'recentchangeslinked-noresult' => 'Lí chí-tēng ê tiâu-kiaⁿ lāi-té chhōe bô jīn-hô kái-piàn.',
'recentchangeslinked-summary'  => "這是一个特殊頁排列出一个頁伊連結的頁佇最近有改過（抑是指定分類的成員）。
佇[[Special:Watchlist|你的監視單]]內底的頁會以'''粗體'''顯示。",
'recentchangeslinked-page'     => 'Ia̍h ê miâ:',
'recentchangeslinked-to'       => '顯示連到我拍入的頁名閣有改過的',

# Upload
'upload'                      => 'Kā tóng-àn chiūⁿ-bāng',
'uploadbtn'                   => 'Kā tóng-àn chiūⁿ-bāng',
'reuploaddesc'                => 'Tò khì sàng-chiūⁿ-bāng ê pió.',
'upload-tryagain'             => '送出改過了後的檔案描述',
'uploadnologin'               => 'Bô teng-ji̍p',
'uploadnologintext'           => 'Bô [[Special:UserLogin|teng-ji̍p]] bē-sái-tit kā tóng-àn sàng-chiūⁿ-bāng.',
'upload_directory_missing'    => '無上傳的目錄（$1），彼袂當由網頁伺服器建立。',
'upload_directory_read_only'  => '無上載目錄（$1），抑是網頁伺服器無權寫入',
'uploaderror'                 => 'Upload chhò-gō·',
'upload-recreate-warning'     => "'''注意：一个仝名的檔案捌hông刣掉抑是徙去別位。'''

這頁的刣掉、徙振動記錄佇下跤通參考：",
'uploadtext'                  => "用下跤的表來共檔案上載。
若欲看抑是揣往過上載的檔案，會使進入[[Special:FileList|檔案上載清單]]，（重）上載嘛會記錄佇[[Special:Log/upload|上傳記錄]]，若刣掉就會記錄佇[[Special:Log/delete|刪除記錄]]。

若欲佇頁加入檔案，用下跤的一種方式來連結：
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>'''使用檔案的完整版本
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|替換文字]]</nowiki></tt>'''用一个囥佇倒爿的一个200 像素圖相框，「替換文字」做說明
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>'''直接連結到檔案，毋過無顯示檔案",
'upload-permitted'            => '准許的檔案類型: $1',
'upload-preferred'            => '建議的檔案類型: $1',
'upload-prohibited'           => '禁止的檔案類型: $1。',
'uploadlog'                   => '上載記錄',
'uploadlogpage'               => '上載記錄',
'uploadlogpagetext'           => 'Í-hā sī chòe-kīn sàng-chiūⁿ-bāng ê tóng-àn ê lia̍t-toaⁿ.',
'filename'                    => 'Tóng-àn',
'filedesc'                    => 'Khài-iàu',
'fileuploadsummary'           => 'Khài-iàu:',
'filereuploadsummary'         => '改換檔案的說明:',
'filestatus'                  => '版權狀況:',
'filesource'                  => '來源：',
'uploadedfiles'               => 'Tóng-àn í-keng sàng chiūⁿ-bāng',
'ignorewarning'               => 'Mài chhap kéng-kò, kā tóng-àn pó-chûn khí lâi.',
'ignorewarnings'              => 'Mài chhap kéng-kò',
'minlength1'                  => '檔案的名上少愛有一字',
'illegalfilename'             => '檔案名“$1”有袂用得用佇標題的字，
請改名了後重新上載。',
'badfilename'                 => 'Iáⁿ-siōng ê miâ í-keng kái chò "$1".',
'filetype-mime-mismatch'      => '副檔名的類型尾無合MIME類型。',
'filetype-badmime'            => 'MIME類別"$1"的檔案袂當上載',
'filetype-bad-ie-mime'        => '袂當上載這个檔案，因為 Internet Explorer 會共伊偵測做 "$1"，彼種袂使，可能是有所危害的檔案類型。',
'filetype-unwanted-type'      => "'''\".\$1\"'''是袂當上載的檔案類型，
適當的{{PLURAL:\$3|檔案類型|檔案類型}}是\$2。",
'filetype-banned-type'        => "'''\".\$1\"'''是袂當上載的檔案類型，
會當的{{PLURAL:\$3|檔案類型|檔案類型}}是\$2。",
'filetype-missing'            => '彼个檔案名稱無副檔名 （親像 ".jpg"）。',
'empty-file'                  => '你送出來的檔案是空的',
'file-too-large'              => '你送出來的檔案傷過大',
'filename-tooshort'           => '檔案名傷短',
'filetype-banned'             => '這類的檔案被禁止',
'verification-error'          => '這个檔案無通過驗證',
'hookaborted'                 => '你欲做的編輯因為擴展鈎(extension hook)去跳開。',
'illegal-filename'            => '無合用的檔案名稱',
'overwrite'                   => '袂使覆寫已經佇咧的檔案',
'unknown-error'               => '發生一个不知的錯誤',
'tmp-create-error'            => '無法度建立臨時檔案',
'tmp-write-error'             => '寫入臨時檔案的時陣發生錯誤',
'large-file'                  => '建議檔案的大小袂當超過 $1，本檔案大小是 $2。',
'largefileserver'             => '這个檔案比伺服器配置所允許的較大。',
'emptyfile'                   => '你欲上載的檔案敢若是空的，
這有可能是拍毋著檔案名稱，
請檢查你確定是欲上載這个檔案。',
'fileexists'                  => "已經有一个仝名的檔案，你若無確定你欲要共改，請檢查'''<tt>[[:$1]]</tt>'''。 [[$1|thumb]]",
'filepageexists'              => "這个檔案的描述頁已經佇'''<tt>[[:$1]]</tt>'''建立，毋過這个名稱的檔案猶未有，
你所輸入的概要袂顯示佇彼个描述頁當中，若欲概要佇遐看會著，你愛手動編輯。
[[$1|thumb]]",
'fileexists-extension'        => "一个親像檔名的檔案已經佇咧: [[$2|thumb]]
* 上載檔案的檔名: '''<tt>[[:$1]]</tt>'''
* 這馬檔案的檔名: '''<tt>[[:$2]]</tt>'''
請選一个無仝的名。",
'fileexists-thumbnail-yes'    => "這个檔案若親像是一幅圖的縮小版本''（縮圖）''。 [[$1|thumb]]
請檢查檔案'''<tt>[[:$1]]</tt>'''，
若檢查的檔案是仝幅圖的縮圖，就毋免閣上載一幅縮圖。",
'file-thumbnail-no'           => "以'''<tt>$1</tt>'''做名的檔案，
伊敢若是某幅圖的縮小版本''（縮圖）''。
你欲就上載完整大小的版本，若無請改檔案名稱。",
'fileexists-forbidden'        => '已經有一个仝名的檔案，而且袂檔覆寫，
若你欲上載你的檔案，請退倒轉去，閣用一个新名來。
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => '已經有一个仝名的檔案佇分享檔案庫，
若你欲上載你的檔案，請退倒轉去，閣用一个新名來。
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => '這个檔案佮下跤的{{PLURAL:$1|个檔案|个檔案}}是仝款的：',
'file-deleted-duplicate'      => '一个仝名的檔案 （[[:$1]]） 佇進前捌予人刣掉，
你應當佇欲閣重新上載進前，先檢查彼个檔案的刣掉記錄。',
'uploadwarning'               => 'Upload kéng-kò',
'uploadwarning-text'          => '請改下跤的檔案描述才閣試',
'savefile'                    => 'Pó-chûn tóng-àn',
'uploadedimage'               => 'thoân "[[$1]]" chiūⁿ-bāng',
'overwroteimage'              => '已經上載「[[$1]]」的新版本',
'uploaddisabled'              => 'Pháiⁿ-sè, sàng chiūⁿ-bāng ê kong-lêng bô khui.',
'copyuploaddisabled'          => '袂當透過網址上載',
'uploadfromurl-queued'        => '你的上載已經咧排隊',
'uploaddisabledtext'          => '袂當上載檔案',
'php-uploaddisabledtext'      => '佇PHP袂當上載檔案，
請檢查file_uploads 設定。',
'uploadscripted'              => '這个檔案內底有HTML抑是腳本代碼，網路瀏覽器可能會錯誤翻譯。',
'uploadvirus'                 => '彼个檔案有一个病毒！
細情：$1',
'upload-source'               => '來源檔案',
'sourcefilename'              => 'Tóng-àn goân miâ:',
'sourceurl'                   => '來源網址(URL)：',
'destfilename'                => 'Tóng-àn sin miâ:',
'upload-maxfilesize'          => '檔案上大：$1',
'upload-description'          => '檔案說明',
'upload-options'              => '上載選項',
'watchthisupload'             => 'Kàm-sī chit ê tóng-àn',
'filewasdeleted'              => '進前有上載一个仝名的檔案，而且後來予人刣掉，
佇欲閣上載進前，你應該先檢查$1。',
'upload-wasdeleted'           => "'''細膩：你今準備欲上載的檔案，前捌予人刣掉過。'''

你著斟酌上載這个檔案的必要性，
遮有彼个檔案予人刣掉的記錄予你參考：",
'filename-bad-prefix'         => "你上載的檔案名是以'''「$1」'''做頭，這一般是數位相機自動編的，彼無啥意義，
請替你的檔案號一个較有意義的名。",
'upload-success-subj'         => 'Sàng-chiūⁿ-bāng sêng-kong',
'upload-success-msg'          => '你對[$2]遐的上載已經成功，伊佇：[[:{{ns:file}}:$1]]',
'upload-failure-subj'         => '上載問題',
'upload-failure-msg'          => '你[$2]的上載出現問題：

$1',
'upload-warning-subj'         => '上載警示',
'upload-warning-msg'          => '你對[$2]遐的上載出問題，你會當回轉去[[Special:Upload/stash/$1|上載表]]修改問題。',

'upload-proto-error'        => '毋著的協議(protocol)',
'upload-proto-error-text'   => '遠程上載愛網址(URL)是以 <code>http://</code> 抑 <code>ftp://</code> 做頭。',
'upload-file-error'         => '內部的錯誤',
'upload-file-error-text'    => '佇伺服器欲開一个臨時檔案的時陣，發生一个內部錯誤，
請佮[[Special:ListUsers/sysop|管理員]]聯絡。',
'upload-misc-error'         => '毋知原因的上載錯誤',
'upload-misc-error-text'    => '佇上載的時陣發生錯誤，毋知啥原因。
請確認網址(URL)是正確的，了才閣試。
若猶閣有問題，請聯絡[[Special:ListUsers/sysop|管理員]]。',
'upload-too-many-redirects' => '網址(URL)包傷濟个轉向',
'upload-unknown-size'       => '大小毋知',
'upload-http-error'         => '發生一个HTTP錯誤：$1',

# img_auth script messages
'img-auth-accessdenied'     => '拒絕讀寫',
'img-auth-nopathinfo'       => '無PATH_INFO資料，
你的伺服器猶未設定這个資料，
伊可能是CGI的款，無支援img_auth，
請看http://www.mediawiki.org/wiki/Manual:Image_Authorization。',
'img-auth-notindir'         => '你欲用的路徑無佇事先設定的上載目錄當中。',
'img-auth-badtitle'         => '無法度對"$1"產生一个有效的標題',
'img-auth-nologinnWL'       => '你猶未登入，"$1"無佇白名單(whitelist)面頂。',
'img-auth-nofile'           => '無"$1"這个檔案',
'img-auth-isdir'            => '你想欲讀目錄"$1"，
毋過只會當讀檔案。',
'img-auth-streaming'        => '當咧串流(streaming)"$1"',
'img-auth-public'           => 'img_auth.php的功能是予私用wiki通輸出檔案，
這個wiki的設定是一个公共wiki，
為著安全因素，img_auth.php已經停用。',
'img-auth-noread'           => '用者無授權去讀"$1"',
'img-auth-bad-query-string' => '網址(URL)有無效的查詢字串',

# HTTP errors
'http-invalid-url'      => '無效的網址(URL)：$1',
'http-invalid-scheme'   => '無支援有「$1」的網址(URL)',
'http-request-error'    => 'HTTP請求失敗，毋知啥物原因的錯誤。',
'http-read-error'       => 'HTTP讀了錯誤',
'http-timed-out'        => 'HTTP請求已經超過時間',
'http-curl-error'       => '取網址(URL)的時陣有錯誤：$1',
'http-host-unreachable' => '連袂到網址(URL)',
'http-bad-status'       => '欲做HTTP的時陣出現問題：$1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => '連袂到網址(URL)',
'upload-curl-error6-text'  => '提供的網址(URL)無法連結，
請確定網址是正確的而且網站有開。',
'upload-curl-error28'      => '上載已經超過時間',
'upload-curl-error28-text' => '網站的回應傷久，
請確定彼个網站有開，抑小等一下才閣試，
你會使佇較閒的時陣才試。',

'license'            => 'Siū-khoân:',
'license-header'     => 'Siū-khoân',
'nolicense'          => '無選半項',
'license-nopreview'  => '（袂當先看覓）',
'upload_source_url'  => ' （一个有效閣開放予大眾的網址(URL)）',
'upload_source_file' => '（佇你電腦的一个檔案）',

# Special:ListFiles
'listfiles-summary'     => '這个特殊頁顯示所有上載的檔案，
若有過濾用者，只有彼个用者上載閣上新的版本才顯示。',
'listfiles_search_for'  => '照檔案名稱揣：',
'imgfile'               => '檔案',
'listfiles'             => 'Iáⁿ-siōng lia̍t-toaⁿ',
'listfiles_thumb'       => '小圖',
'listfiles_date'        => 'Ji̍t-kî',
'listfiles_name'        => 'Miâ',
'listfiles_user'        => 'Iōng-chiá',
'listfiles_size'        => 'Toā-sè',
'listfiles_description' => 'Soat-bêng',
'listfiles_count'       => '版本',

# File description page
'file-anchor-link'          => 'Tóng-àn',
'filehist'                  => 'Tóng-àn ê le̍k-sú',
'filehist-help'             => '揤日期／時間就通看彼時陣的檔案',
'filehist-deleteall'        => '全部刣掉',
'filehist-deleteone'        => '刣掉',
'filehist-revert'           => '回轉',
'filehist-current'          => 'hiān-chāi',
'filehist-datetime'         => 'Ji̍t-kî/ Sî-kan',
'filehist-thumb'            => '小圖',
'filehist-thumbtext'        => '細張圖佇$1的版本',
'filehist-nothumb'          => '無小圖',
'filehist-user'             => 'Iōng-chiá',
'filehist-dimensions'       => '長闊',
'filehist-filesize'         => '檔案大細',
'filehist-comment'          => '註釋',
'filehist-missing'          => '檔案無看',
'imagelinks'                => 'Ēng tio̍h ê  tóng-àn',
'linkstoimage'              => 'Ē-bīn ê {{PLURAL:$1|ia̍h liân kàu|$1 ia̍h liân kàu}}  chit ê tóng-àn:',
'linkstoimage-more'         => '超過$1{{PLURAL:$1|頁連接|頁連接}}到這个檔案，
下跤只是連接到這个檔案的{{PLURAL:$1|頭頁連結|頭$1頁連結}}清單，
有一个[[Special:WhatLinksHere/$2|全部的清單]]。',
'nolinkstoimage'            => 'Bô poàⁿ ia̍h liân kàu chit tiuⁿ iáⁿ-siōng.',
'morelinkstoimage'          => '看連接到這个檔案的[[Special:WhatLinksHere/$1|其他連結]]',
'redirectstofile'           => '下跤{{PLURAL:$1|个|个}}轉向頁連接到這个檔案：',
'duplicatesoffile'          => '下跤{{PLURAL:$1|个|个}}檔案佮這个仝款（[[Special:FileDuplicateSearch/$2|詳細]]）：',
'sharedupload'              => '這个檔案是對$1遐來的，伊可能用佇別个事工。',
'sharedupload-desc-there'   => '這个檔案對$1遐來的，伊可能用佇別个事工，
請看[$2 檔案說明]以了解進一步訊息。',
'sharedupload-desc-here'    => '這个檔案是對$1遐來的，伊可能嘛用佇別的事工，
伊[$2 檔案說明頁]的說明佇下跤。',
'filepage-nofile'           => '無這个名的檔案',
'filepage-nofile-link'      => '無這个名的檔案，你會使 [$1上載]。',
'uploadnewversion-linktext' => '上載這个檔案的新版本',
'shared-repo-from'          => '來自 $1',
'shared-repo'               => '一個共享的檔案庫',

# File reversion
'filerevert'                => '回轉$1',
'filerevert-legend'         => '回轉檔案',
'filerevert-intro'          => "你當咧回轉檔案'''[[Media:$1|$1]]'''到[$4佇$2 $3的版本]。",
'filerevert-comment'        => '理由：',
'filerevert-defaultcomment' => '已經回轉到$1 $2的版本',
'filerevert-submit'         => '回轉',
'filerevert-success'        => "'''[[Media:$1|$1]]'''已經回轉到[$4 佇$2 $3的版本]。",
'filerevert-badversion'     => '這个檔案所提供的時間截記，無進前的本地版本。',

# File deletion
'filedelete'                  => '刣掉$1',
'filedelete-legend'           => '刣掉檔案',
'filedelete-intro'            => "你當咧刣掉檔案'''[[Media:$1|$1]]'''，佮伊的歷史。",
'filedelete-intro-old'        => "你當咧刣掉'''[[Media:$1|$1]]'''佇[$4 $2 $3]的版本",
'filedelete-comment'          => '理由：',
'filedelete-submit'           => '刣掉',
'filedelete-success'          => "'''$1'''已經刣掉",
'filedelete-success-old'      => "'''[[Media:$1|$1]]'''佇$2 $3 的版本已經刣掉",
'filedelete-nofile'           => "無'''$1'''這个",
'filedelete-nofile-old'       => "揣無'''$1'''指定的保存版本",
'filedelete-otherreason'      => '其他／另外的理由：',
'filedelete-reason-otherlist' => '其他理由',
'filedelete-reason-dropdown'  => '*一般刣掉的理由
** 違反著作權
** 相仝',
'filedelete-edit-reasonlist'  => '編輯刣掉的理由',

# MIME search
'mimesearch' => 'MIME chhiau-chhoē',
'download'   => '下載',

# Unwatched pages
'unwatchedpages' => 'Bô lâng kàm-sī ê ia̍h',

# List redirects
'listredirects' => 'Lia̍t-chhut choán-ia̍h',

# Unused templates
'unusedtemplates'    => 'Bô iōng ê pang-bô·',
'unusedtemplateswlh' => '其他的連結',

# Random page
'randompage'         => 'Sûi-chāi kéng ia̍h',
'randompage-nopages' => '下面無頁
{{PLURAL:$2|名空間|名空間}}：$1.',

# Random redirect
'randomredirect' => 'Sûi-chāi choán-ia̍h',

# Statistics
'statistics'              => 'Thóng-kè',
'statistics-header-pages' => '頁的統計',
'statistics-header-edits' => '改的統計',
'statistics-header-views' => '看的統計',
'statistics-header-users' => 'Iōng-chiá thóng-kè sò·-ba̍k',
'statistics-header-hooks' => '其他的統計',
'statistics-articles'     => '內容頁',
'statistics-pages-desc'   => '佇Wiki所有的頁，包括討論頁、轉頁等等。',
'statistics-files'        => '上載檔案',
'statistics-mostpopular'  => '上濟人看的頁',

'disambiguations'     => 'Khu-pia̍t-ia̍h',
'disambiguationspage' => 'Template:disambig
Template:KhPI
Template:Khu-pia̍t-iah
Template:Khu-pia̍t-ia̍h',

'doubleredirects' => 'Siang-thâu choán-ia̍h',

'brokenredirects'        => 'Choán-ia̍h kò·-chiòng',
'brokenredirectstext'    => 'Í-hā ê choán-ia̍h liân kàu bô chûn-chāi ê ia̍h:',
'brokenredirects-edit'   => '修改',
'brokenredirects-delete' => '刣掉',

'withoutinterwiki'         => 'Bô gí-giân liân-kiat ê ia̍h',
'withoutinterwiki-summary' => 'Ē-kha ê ia̍h bô kî-thaⁿ gí-giân pán-pún ê liân-kiat:',
'withoutinterwiki-submit'  => '顯示',

'fewestrevisions' => 'Siōng bô siu-tēng ê bûn-chiuⁿ',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|jī-goân|jī-goân}}',
'ncategories'             => '$1 {{PLURAL:$1|ê lūi-pia̍t |ê lūi-pia̍t}}',
'nlinks'                  => '$1 ê liân-kiat',
'nmembers'                => '$1 {{PLURAL:$1|成員|成員}}',
'nrevisions'              => '$1 ê siu-tēng-pún',
'lonelypages'             => 'Ko·-ia̍h',
'uncategorizedpages'      => 'Bô lūi-pia̍t ê ia̍h',
'uncategorizedcategories' => 'Bô lūi-pia̍t ê lūi-pia̍t',
'uncategorizedimages'     => 'Bô lūi-pia̍t ê iáⁿ-siōng',
'uncategorizedtemplates'  => 'Bô lūi-pia̍t ê pang-bô͘',
'unusedcategories'        => 'Bô iōng ê lūi-pia̍t',
'unusedimages'            => 'Bô iōng ê iáⁿ-siōng',
'popularpages'            => 'Sî-kiâⁿ ê ia̍h',
'wantedcategories'        => 'wantedcategories',
'wantedpages'             => 'Beh ti̍h ê ia̍h',
'wantedfiles'             => '欲挃的檔案',
'wantedtemplates'         => '欲挃的枋模',
'mostlinked'              => 'Siōng chia̍p liân-kiat ê ia̍h',
'mostlinkedcategories'    => 'Siōng chia̍p liân-kiat ê lūi-pia̍t',
'mostlinkedtemplates'     => 'Siōng chia̍p liân-kiat ê pang-bô͘',
'mostcategories'          => 'Siōng chē lūi-pia̍t ê ia̍h',
'mostimages'              => 'Siōng chia̍p liân-kiat ê iáⁿ-siōng',
'mostrevisions'           => 'Siōng chia̍p siu-kái ê ia̍h',
'prefixindex'             => 'Só͘-ū chiàu sû-thâu sek-ín liáu ê  ia̍h',
'shortpages'              => 'Té-ia̍h',
'longpages'               => '長頁',
'deadendpages'            => 'Khu̍t-thâu-ia̍h',
'deadendpagestext'        => 'Ē-kha ê ia̍h bô liân kàu wiki lāi-té ê kî-thaⁿ ia̍h.',
'protectedpages'          => 'Siū pó-hō͘ ê ia̍h',
'protectedpagestext'      => 'Ē-kha ê ia̍h siū pó-hō͘, bē-tit soá-ūi ia̍h pian-chi̍p',
'listusers'               => 'Iōng-chiá lia̍t-toaⁿ',
'usercreated'             => ' {{GENDER:$3|}}佇$1 $2創建',
'newpages'                => 'Sin ia̍h',
'newpages-username'       => 'Iōng-chiá miâ-chheng:',
'ancientpages'            => 'Kó·-ia̍h',
'move'                    => 'Sóa khì',
'movethispage'            => 'Sóa chit ia̍h',
'unusedimagestext'        => 'Ē-kha ê tóng-àn bô poàⁿ ia̍h ū teh iōng. M̄-koh ia̍h lâu leh. 
Chhiáⁿ chù-ì: kî-thaⁿ ê bāng-chām ū khó-lêng iōng URL ti̍t-chiap liân kàu iáⁿ-siōng, só·-í sui-jiân bô teh iōng, mā sī ē lia̍t tī chia.',
'unusedcategoriestext'    => 'Ū ē-kha chiah-ê lūi-pia̍t-ia̍h, m̄-koh bô kî-thaⁿ ê bûn-chiuⁿ a̍h-sī lūi-pia̍t lī-iōng.',
'pager-newer-n'           => '{{PLURAL:$1|較新一个|較新$1个 }}',
'pager-older-n'           => '{{PLURAL:$1|較舊一个|較舊$1个}}',

# Book sources
'booksources'               => 'Tô͘-su chu-liāu',
'booksources-search-legend' => '揣圖書資料',
'booksources-go'            => '來去',

# Special:Log
'specialloguserlabel'  => 'Iōng-chiá:',
'speciallogtitlelabel' => 'Bo̍k-piau (sû-tiâu ia̍h iōng-chiá) :',
'log'                  => '記錄',
'logempty'             => 'Log lāi-bīn bô sio-tùi ê hāng-bo̍k.',

# Special:AllPages
'allpages'          => 'Só·-ū ê ia̍h',
'alphaindexline'    => '$1 kàu $2',
'nextpage'          => 'Āu 1 ia̍h ($1)',
'prevpage'          => '前一頁（$1）',
'allpagesfrom'      => 'Tùi chit ia̍h khai-sí hián-sī:',
'allarticles'       => 'Só·-ū ê bûn-chiuⁿ',
'allinnamespace'    => 'Só·-ū ê ia̍h ($1 miâ-khong-kan)',
'allnotinnamespace' => 'Só·-ū ê ia̍h (bô tī $1 miâ-khong-kan)',
'allpagesprev'      => 'Téng 1 ê',
'allpagesnext'      => 'ē 1 ê',
'allpagessubmit'    => 'Lâi-khì',

# Special:Categories
'categories'         => 'Lūi-pia̍t',
'categoriespagetext' => 'Chit ê wiki ū ē-kha chia ê lūi-pia̍t.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:DeletedContributions
'deletedcontributions'       => 'Hō͘ lâng thâi tiāu ê kòng-hiàn',
'deletedcontributions-title' => 'Hō͘ lâng thâi tiāu ê kòng-hiàn',

# Special:LinkSearch
'linksearch'      => 'Chhiau-chhoē chām-goā liân-kiat',
'linksearch-ns'   => '名空間：',
'linksearch-line' => '$1 是對$2連接來的',

# Special:ListUsers
'listusers-submit'   => '顯示',
'listusers-noresult' => '揣無用者',

# Special:ActiveUsers
'activeusers'            => '有咧活動的用者清單',
'activeusers-intro'      => '這是佇過去$1 {{PLURAL:$1|工y|工}}有做過一寡活動的用者清單。',
'activeusers-hidebots'   => '掩機器人',
'activeusers-hidesysops' => '掩管理員',

# Special:Log/newusers
'newuserlogpage'              => '用者建立的記錄',
'newuserlogpagetext'          => '這是開用者口座的記錄',
'newuserlog-byemail'          => '用電子批寄密碼',
'newuserlog-create-entry'     => '新用者口座',
'newuserlog-create2-entry'    => '開一个$1的新口座',
'newuserlog-autocreate-entry' => '口座已經自動開好',

# Special:ListGroupRights
'listgrouprights'         => '用者陣權利',
'listgrouprights-members' => '(成員列單)',

# E-mail user
'mailnologin'     => 'Bô siu-phoe ê chū-chí',
'mailnologintext' => 'Lí it-tēng ài [[Special:UserLogin|teng-ji̍p]] jī-chhiáⁿ ū 1 ê ū-hāu ê e-mail chū-chí tī lí ê [[Special:Preferences|iōng-chiá siat-tēng]] chiah ē-tàng kià e-mail hō· pa̍t-ūi iōng-chiá.',
'emailuser'       => 'Kià e-mail hō· iōng-chiá',
'emailpage'       => 'E-mail iōng-chiá',
'emailpagetext'   => 'Ká-sú chit ê iōng-chiá ū siat-tēng 1 ê ū-hāu ê e-mail chū-chí, lí tō ē-tàng ēng ē-kha chit tiuⁿ FORM hoat sìn-sek hō· i. Lí siat-tēng ê e-mail chū-chí ē chhut-hiān tī e-mail ê "Kià-phoe-jîn" (From) hit ūi. Án-ne siu-phoe-jîn chiah ū hoat-tō· kā lí hôe-phoe.',
'noemailtitle'    => 'Bô e-mail chū-chí',
'noemailtext'     => 'Chit ūi iōng-chiá pēng-bô lâu ū-hāu ê e-mail chū-chí.',
'nowikiemailtext' => '這个用者無欲收電子批。',
'emailfrom'       => 'Lâi chū:',
'emailto'         => 'Khì hō·:',
'emailsubject'    => 'Tê-bo̍k:',
'emailmessage'    => 'Sìn-sit:',
'emailsend'       => 'Sàng chhut-khì',
'emailccme'       => '共我的訊息用電子批寄一份予我',
'emailsent'       => 'E-mail sàng chhut-khì ah',
'emailsenttext'   => 'Lí ê e-mail í-keng sàng chhut-khì ah.',

# User Messenger
'usermessage-summary' => '留系統信息',
'usermessage-editor'  => '系統信息',

# Watchlist
'watchlist'          => 'Kàm-sī-toaⁿ',
'mywatchlist'        => 'Góa ê kàm-sī-toaⁿ',
'watchlistfor2'      => '予$1 $2',
'nowatchlist'        => 'Lí ê kàm-sī-toaⁿ bô pòaⁿ hāng.',
'watchnologin'       => 'Bô teng-ji̍p',
'watchnologintext'   => 'Lí it-tēng ài [[Special:UserLogin|teng-ji̍p]] chiah ē-tàng siu-kái lí ê kàm-sī-toaⁿ.',
'addedwatch'         => 'Í-keng ka-ji̍p kàm-sī-toaⁿ',
'addedwatchtext'     => "\"[[:\$1]]\" chit ia̍h í-keng ka-ji̍p lí ê [[Special:Watchlist|kàm-sī-toaⁿ]]. Bī-lâi chit ia̍h a̍h-sī siong-koan ê thó-lūn-ia̍h nā ū kái-piàn, ē lia̍t tī hia. Tông-sî tī [[Special:RecentChanges|Chòe-kīn ê kái-piàn]] ē iōng '''chho·-thé''' hián-sī ia̍h ê piau-tê, án-ne khah bêng-hián. Ká-sú lí beh chiōng chit ia̍h tùi lí ê kàm-sī-toaⁿ tû tiāu, khì khòng-chè-tiâu chhi̍h \"Mài kàm-sī\" chiū ē-sái-tit.",
'removedwatch'       => 'Í-keng tùi kàm-sī-toaⁿ tû tiāu',
'removedwatchtext'   => '"[[:$1]]" chit ia̍h í-keng tùi lí ê kàm-sī-toaⁿ tû tiāu.',
'watch'              => 'kàm-sī',
'watchthispage'      => 'Kàm-sī chit ia̍h',
'unwatch'            => 'Mài kàm-sī',
'unwatchthispage'    => 'Mài koh kàm-sī',
'notanarticle'       => '毋是內容頁面',
'watchnochange'      => 'Lí kàm-sī ê hāng-bo̍k tī hián-sī ê sî-kî í-lāi lóng bô siu-kái kòe.',
'watchlist-details'  => 'Kàm-sī-toaⁿ ū {{PLURAL:$1|$1 ia̍h|$1 ia̍h}}, thó-lūn-ia̍h bô sǹg chāi-lāi.',
'watchmethod-recent' => 'tng teh kíam-cha choè-kīn ê siu-kái, khoàⁿ ū kàm-sī ê ia̍h bô',
'watchmethod-list'   => 'tng teh kiám-cha kàm-sī ê ia̍h khoàⁿ chòe-kīn ū siu-kái bô',
'watchlistcontains'  => 'Lí ê kàm-sī-toaⁿ siu {{PLURAL:$1|ia̍h|ia̍h}} .',
'wlnote'             => "Ē-kha sī '''$2''' tiám-cheng í-lāi siōng sin ê $1 ê kái-piàn.",
'wlshowlast'         => 'Hián-sī chêng $1 tiám-cheng $2 ji̍t $3',
'watchlist-options'  => '監視單的選項',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => '共監視',
'unwatching' => '莫監視',

'enotif_reset'       => '共全部的頁攏當做巡過',
'enotif_newpagetext' => '這是新的一頁',
'changed'            => '改過',
'created'            => '寫過',
'enotif_subject'     => '佇{{SITENAME}}的$PAGETITLE這頁捌予$CHANGEDORCREATED$PAGEEDITOR',
'enotif_lastvisited' => '看$1，自你頂回來到今所有改的',
'enotif_lastdiff'    => '看$1這回改的',
'enotif_anon_editor' => '無名氏用者$1',

# Delete
'deletepage'             => 'Thâi ia̍h',
'confirm'                => 'Khak-tēng',
'excontent'              => "lōe-iông sī: '$1'",
'excontentauthor'        => "loē-iông sī: '$1' (î-it ê kòng-hiàn-chiá sī '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "chìn-chêng ê lōe-iông sī: '$1'",
'exblank'                => 'ia̍h khang-khang',
'delete-confirm'         => '刣掉$1',
'delete-legend'          => '刣掉',
'historywarning'         => 'Kéng-kò: Lí beh thâi ê ia̍h ū {{PLURAL:$1| ê siu-tèng le̍k-sú|ê siu-tèng le̍k-sú}}:',
'confirmdeletetext'      => 'Lí tih-beh kā 1 ê ia̍h a̍h-sī iáⁿ-siōng (pau-koat siong-koan ê le̍k-sú) éng-kiú tùi chu-liāu-khò· thâi tiāu. Chhiáⁿ khak-tēng lí àn-sǹg án-ne chò, jī-chhiáⁿ liáu-kái hiō-kó, jī-chhiáⁿ bô ûi-hoán [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'         => 'Chip-hêng sêng-kong',
'actionfailed'           => '做無成',
'deletedtext'            => '"<nowiki>$1</nowiki>" í-keng thâi tiāu. Tùi $2 khoàⁿ-ē-tio̍h chòe-kīn thâi ê kì-lo̍k.',
'deletedarticle'         => 'Thâi tiāu "[[$1]]"',
'suppressedarticle'      => '共"[[$1]]"崁掉',
'dellogpage'             => '刣掉的記錄',
'dellogpagetext'         => 'Í-hā lia̍t chhut chòe-kīn thâi tiāu ê hāng-bo̍k.',
'deletionlog'            => '刣掉的記錄',
'reverted'               => '轉轉去前一个版本',
'deletecomment'          => 'Lí-iû:',
'deleteotherreason'      => '其他／另外的理由：',
'deletereasonotherlist'  => '其他的理由',
'deletereason-dropdown'  => '*一般刣掉的理由
** 作者的要求
** 違反著作權
** 破壞',
'delete-edit-reasonlist' => '編輯刣掉的理由',

# Rollback
'rollback'          => 'Kā siu-kái ká tńg khì',
'rollback_short'    => 'Ká tńg khì',
'rollbacklink'      => 'ká tńg khì',
'rollbackfailed'    => 'Ká bē tńg khì',
'cantrollback'      => 'Bô-hoat-tō· kā siu-kái ká-tńg--khì; téng ūi kòng-hiàn-chiá sī chit ia̍h î-it ê chok-chiá.',
'alreadyrolled'     => 'Bô-hoat-tō· kā [[User:$2|$2]] ([[User talk:$2|Thó-lūn]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) tùi [[:$1]] ê siu-kái ká-tńg-khì; 
í-keng ū lâng siu-kái a̍h-sī ká-tńg chit ia̍h. 
Téng 1 ūi siu-kái-chiá sī [[User:$3|$3]] ([[User talk:$3|talk]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Pian-chi̍p kài-iàu sī: \"''\$1''\".",
'revertpage'        => '回轉[[Special:Contributions/$2|$2]]（[[User talk:$2|對話]]）的編輯到頂一个[[User:$1|$1]]的修訂版本',
'revertpage-nouser' => '回轉（無用者名）的編輯到頂一个[[User:$1|$1]]的修訂版本',
'rollback-success'  => '回轉$1的編輯，
轉轉去頂一个$2的修訂版本。',

# Edit tokens
'sessionfailure-title' => '登入的資訊失效',
'sessionfailure'       => '你的登入連線敢若有問題，
為著防止連線被駭客(hijack)，這个操作已經取消，
請先轉去前一頁，重新載入彼頁，才閣試。',

# Protect
'protectlogpage'              => '保護的記錄',
'protectlogtext'              => '下跤是保護頁有改過的清單，
請參考[[Special:ProtectedPages|保護頁清單]]看這馬有保護的頁。',
'protectedarticle'            => 'pó-hō͘ "[[$1]]"',
'modifiedarticleprotection'   => '改"[[$1]]"的保護等級',
'unprotectedarticle'          => '已經解除"[[$1]]"的保護',
'movedarticleprotection'      => '已經共"[[$2]]"的保護設定徙去"[[$1]]"',
'protect-title'               => 'Pó-hō· "$1"',
'prot_1movedto2'              => '[[$1]] sóa khì tī [[$2]]',
'protect-legend'              => 'Khak-tēng beh pó-hō·',
'protectcomment'              => 'Lí-iû:',
'protectexpiry'               => '到期：',
'protect_expiry_invalid'      => '到期時間毋著',
'protect_expiry_old'          => '到期時間已經過去',
'protect-unchain-permissions' => '解除更加保護的選項',
'protect-text'                => "你會當佇遮看佮改'''<nowiki>$1</nowiki>'''的保護等級。",
'protect-locked-blocked'      => "你袂當佇封鎖的時陣改保護等級，
下跤是'''$1'''這馬的保護等級:",
'protect-cascade'             => 'Cascading protection - pó-hō͘ jīm-hô pau-hâm tī chit ia̍h ê ia̍h.',
'protect-othertime'           => '其他的時間：',
'protect-othertime-op'        => '其他的時間',
'protect-otherreason'         => '其他／另外的理由：',
'protect-otherreason-op'      => '其他的理由',
'restriction-type'            => '允准：',
'restriction-level'           => '限制層級：',
'minimum-size'                => '上細',
'maximum-size'                => '上大：',

# Restrictions (nouns)
'restriction-edit'   => 'Siu-kái',
'restriction-move'   => 'Sóa khì',
'restriction-create' => '開始寫',
'restriction-upload' => '上載',

# Restriction levels
'restriction-level-sysop'         => '全保護',
'restriction-level-autoconfirmed' => '半保護',
'restriction-level-all'           => '任何一級',

# Undelete
'undelete'            => 'Kiù thâi tiāu ê ia̍h',
'undeletepage'        => 'Khoàⁿ kap kiù thâi tiāu ê ia̍h',
'undeletepagetitle'   => "'''下跤包括[[:$1]]的刣掉修訂本'''",
'viewdeletedpage'     => '看刣掉的頁',
'undeletepagetext'    => '下跤的{{PLURAL:$1|篇頁|篇頁}}已經予人刣掉，毋過猶留佇檔案庫，而且會使救倒轉來。
檔案庫內底可能會定時清掉。',
'undeletelink'        => '看／恢復',
'undeleteviewlink'    => 'Khoàⁿ',
'undeletereset'       => '設便',
'undeletecomment'     => '理由：',
'undeletedarticle'    => 'kiù "[[$1]]"',
'undelete-search-box' => '揣刣掉的頁',

# Namespace form on various pages
'namespace'      => 'Miâ-khong-kan:',
'invert'         => 'Soán-hāng í-gōa',
'blanknamespace' => '(Thâu-ia̍h)',

# Contributions
'contributions'       => 'Iōng-chiá ê kòng-hiàn',
'contributions-title' => '用者佇$1的貢獻',
'mycontris'           => 'Góa ê kòng-hiàn',
'contribsub2'         => '$1的貢獻（$2）',
'nocontribs'          => 'Chhōe bô tiâu-kiāⁿ ū-tùi ê hāng-bo̍k.',
'uctop'               => '(siōng téng ê)',
'month'               => 'Kàu tó 1 kó͘ goe̍h ûi-chí:',
'year'                => 'Kàu tó 1 nî ûi-chí:',

'sp-contributions-newbies'     => 'Kan-taⁿ hián-sī sin kháu-chō ê kòng-kiàn',
'sp-contributions-newbies-sub' => 'Sin lâi--ê',
'sp-contributions-blocklog'    => '封鎖記錄',
'sp-contributions-deleted'     => 'Hō͘ lâng thâi tiāu ê kòng-hiàn',
'sp-contributions-uploads'     => '上載',
'sp-contributions-logs'        => '記錄',
'sp-contributions-talk'        => 'thó-lūn',
'sp-contributions-search'      => 'Chhoē chhut kòng-kiàn',
'sp-contributions-username'    => 'IP Chū-chí a̍h iōng-chiá miâ:',
'sp-contributions-toponly'     => '干焦看頂一回改的',
'sp-contributions-submit'      => 'Chhoē',

# What links here
'whatlinkshere'            => 'Tó-ūi liân kàu chia',
'whatlinkshere-title'      => '連到"$1"的頁',
'whatlinkshere-page'       => '頁：',
'linkshere'                => "Í-hā '''[[:$1]]''' liân kàu chia:",
'nolinkshere'              => "Bô poàⁿ ia̍h liân kàu '''[[:$1]]'''.",
'isredirect'               => 'choán-ia̍h',
'istemplate'               => '包括',
'isimage'                  => '檔案連結',
'whatlinkshere-prev'       => '{{PLURAL:$1|chêng|chêng $1 ê}}',
'whatlinkshere-next'       => '{{PLURAL:$1|āu|āu $1 ê}}',
'whatlinkshere-links'      => '← Liân kàu chia',
'whatlinkshere-hideredirs' => '$1 改向',
'whatlinkshere-hidetrans'  => '$1包括',
'whatlinkshere-hidelinks'  => '$1 連到遮',
'whatlinkshere-hideimages' => '$1圖像的連結',
'whatlinkshere-filters'    => '過濾器',

# Block/unblock
'blockip'                      => 'Hong-só iōng-chiá',
'blockip-title'                => '封鎖用者',
'blockip-legend'               => '封鎖用者',
'ipadressorusername'           => 'IP Chū-chí a̍h iōng-chiá miâ:',
'ipbexpiry'                    => '到期：',
'ipbreason'                    => 'Lí-iû:',
'ipbreasonotherlist'           => '其他理由',
'ipbsubmit'                    => 'Hong-só chit ūi iōng-chiá',
'ipbother'                     => '其他時間：',
'ipboptions'                   => '兩點鐘:2 hours,一工:1 day,三工:3 days,一禮拜:1 week,兩禮拜:2 weeks,一個月:1 month,兩個月:3 months,六個月:6 months,一年:1 year,永久:infinite',
'ipbotherreason'               => '其他／另外的理由：',
'badipaddress'                 => 'Bô-hāu ê IP chū-chí',
'blockipsuccesssub'            => 'Hong-só sêng-kong',
'blockipsuccesstext'           => '[[Special:Contributions/$1|$1]] í-keng pī hong-só. <br />Khì [[Special:IPBlockList|IP hong-só lia̍t-toaⁿ]] review hong-só ê IP.',
'unblockip'                    => '解除對用者的封鎖',
'ipusubmit'                    => 'Chhú-siau chit ê hong-só',
'ipblocklist'                  => 'Siū hong-só ê iōng-chiá',
'ipblocklist-legend'           => '揣一个封鎖的用者',
'ipblocklist-username'         => '用者名稱抑是網路地址(IP)：',
'ipblocklist-sh-userblocks'    => '$1口座封鎖',
'ipblocklist-sh-tempblocks'    => '$1暫時封鎖',
'ipblocklist-sh-addressblocks' => '$1單一IP封鎖',
'ipblocklist-submit'           => '揣',
'blocklink'                    => 'hong-só',
'unblocklink'                  => '取消封鎖',
'change-blocklink'             => '改封鎖',
'contribslink'                 => 'kòng-hiàn',
'autoblocker'                  => 'Chū-tōng kìm-chí lí sú-iōng, in-ūi lí kap "$1" kong-ke kāng 1 ê IP chū-chí (kìm-chí lí-iû "$2").',
'blocklogpage'                 => '封鎖記錄',
'blocklogentry'                => 'hong-só [[$1]], siat kî-hān chì $2 $3',
'blocklogtext'                 => 'Chit-ê log lia̍t-chhut block/unblock ê tōng-chok. Chū-tōng block ê IP chū-chí bô lia̍t--chhut-lâi ([[Special:IPBlockList]] ū hiān-chú-sî ū-hāu ê block/ban o·-miâ-toaⁿ).',
'unblocklogentry'              => '解除封鎖$1',
'block-log-flags-anononly'     => '只會當是無名氏用者',
'block-log-flags-nocreate'     => 'Khui kháu-chō thêng-iōng ah',
'block-log-flags-noautoblock'  => '自動封鎖袂當用',
'block-log-flags-noemail'      => '電子批封鎖牢咧',
'block-log-flags-nousertalk'   => '袂當改家己的討論頁',
'block-log-flags-hiddenname'   => '用者名稱藏起來矣',
'ipb_expiry_invalid'           => '到期的時間毋著',
'ipb_already_blocked'          => '"$1"是封鎖牢咧',
'ip_range_invalid'             => '毋著的網址(IP)範圍',
'blockme'                      => '封鎖我',
'proxyblocker-disabled'        => '這个功能袂當用。',

# Developer tools
'lockbtn'             => '封鎖資料庫',
'unlockbtn'           => '解除對資料庫的封鎖',
'locknoconfirm'       => 'Lí bô kau "khak-tēng" ê keh-á.',
'lockdbsuccesssub'    => '資料庫封鎖成功',
'unlockdbsuccesssub'  => '已經共資料庫的封鎖解除',
'unlockdbsuccesstext' => '資料庫已經解除封鎖',
'databasenotlocked'   => '資料庫無封鎖牢咧。',

# Move page
'move-page'              => '徙$1',
'move-page-legend'       => 'Sóa ia̍h',
'movepagetext'           => "Ē-kha chit ê form> iōng lâi kái 1 ê ia̍h ê piau-tê (miâ-chheng); só·-ū siong-koan ê le̍k-sú ē tòe leh sóa khì sin piau-tê.
Kū piau-tê ē chiâⁿ-chò 1 ia̍h choán khì sin piau-tê ê choán-ia̍h.
Liân khì kū piau-tê ê liân-kiat (link) bē khì tāng--tio̍h; ē-kì-tit chhiau-chhōe siang-thâu (double) ê a̍h-sī kò·-chiòng ê choán-ia̍h.
Lí ū chek-jīm khak-tēng liân-kiat kè-sio̍k liân tio̍h ūi.

Sin piau-tê nā í-keng tī leh (bô phian-chi̍p koè ê khang ia̍h, choán-ia̍h bô chún-sǹg), tō bô-hoat-tō· soá khì hia.
Che piaú-sī nā ū têng-tâⁿ, ē-sái kā sin ia̍h soà tńg-khì goân-lâi ê kū ia̍h.

'''SÈ-JĪ!'''
Tùi chē lâng tha̍k ê ia̍h lâi kóng, soá-ūi sī toā tiâu tāi-chì.
Liâu--lo̍h-khì chìn-chêng, chhiáⁿ seng khak-tēng lí ū liáu-kái chiah-ê hiō-kó.",
'movepagetalktext'       => "Siong-koan ê thó-lūn-ia̍h (chún ū) oân-nâ ē chū-tōng tòe leh sóa-ūi. Í-hā ê chêng-hêng '''bô chún-sǹg''': *Beh kā chit ia̍h tùi 1 ê miâ-khong-kan (namespace) soá khì lēng-gōa 1 ê miâ-khong-kan, *Sin piau-tê í-keng ū iōng--kòe ê thó-lūn-ia̍h, he̍k-chiá *Ē-kha ê sió-keh-á bô phah-kau. Í-siōng ê chêng-hêng nā-chún tī leh, lí chí-hó iōng jîn-kang ê hong-sek sóa ia̍h a̍h-sī kā ha̍p-pèng (nā ū su-iàu).",
'movearticle'            => 'Sóa ia̍h:',
'movenologin'            => 'Bô teng-ji̍p',
'movenologintext'        => 'Lí it-tēng ài sī chù-chheh ê iōng-chiá jī-chhiáⁿ ū [[Special:UserLogin|teng-ji̍p]] chiah ē-tàng sóa ia̍h.',
'movenotallowed'         => '你無授權通去徙頁',
'movenotallowedfile'     => '你無授權通去徙檔案',
'cant-move-user-page'    => '你無授權通去徙用者頁（無包括伊的下頁）',
'cant-move-to-user-page' => '你無授權通去徙用者頁（下頁例外）',
'newtitle'               => 'Khì sin piau-tê:',
'move-watch'             => 'Kàm-sī chit ia̍h',
'movepagebtn'            => 'Sóa ia̍h',
'pagemovedsub'           => 'Sóa-ūi sêng-kong',
'articleexists'          => 'Kāng miâ ê ia̍h í-keng tī leh, a̍h-sī lí kéng ê miâ bô-hāu. Chhiáⁿ kéng pa̍t ê miâ.',
'talkexists'             => "'''Ia̍h ê loē-bûn ū soá cháu, m̄-koh siong-koan ê thó-lūn-ia̍h bô toè leh soá, in-ūi sin piau-tê pun-té tō ū hit ia̍h. Chhiáⁿ iōng jîn-kang ê hoat-tō· kā ha̍p-pèng.'''",
'movedto'                => 'sóa khì tī',
'movetalk'               => 'Sūn-sòa sóa thó-lūn-ia̍h',
'movepage-page-moved'    => '$1 í-keng sóa khì tī $2.',
'movepage-page-unmoved'  => '$1這頁袂當徙去$2',
'1movedto2'              => '[[$1]] sóa khì tī [[$2]]',
'1movedto2_redir'        => '[[$1]] sóa khì [[$2]] (choán-ia̍h thiàu kòe)',
'movelogpage'            => '徙位記錄',
'movelogpagetext'        => 'Ē-kha lia̍t-chhut hông soá-ūi ê ia̍h.',
'movenosubpage'          => '這頁無下頁',
'movereason'             => 'Lí-iû:',
'revertmove'             => '回轉',
'selfmove'               => 'Goân piau-tê kap sin piau-tê sio-siâng; bô hoat-tō· sóa.',

# Export
'export'        => 'Su-chhut ia̍h',
'exportcuronly' => 'Hān hiān-chhú-sî ê siu-téng-pún, mài pau-koat kui-ê le̍k-sú',

# Namespace 8 related
'allmessages'               => 'Hē-thóng sìn-sit',
'allmessagesname'           => 'Miâ',
'allmessagesdefault'        => 'Siat piān ê bûn-jī',
'allmessagescurrent'        => 'Bo̍k-chêng ê bûn-jī',
'allmessagestext'           => 'Chia lia̍t chhut só·-ū tī MediaWiki: miâ-khong-kan ê hē-thóng sìn-sit.',
'allmessages-filter-all'    => '全部',
'allmessages-language'      => '話語：',
'allmessages-filter-submit' => '來去',

# Thumbnails
'thumbnail-more'  => 'Hòng-tōa',
'filemissing'     => 'Bô tóng-àn',
'thumbnail_error' => '產生小圖時錯誤：$1',

# Special:Import
'import'                 => 'Su-ji̍p ia̍h',
'import-upload-filename' => '檔案名稱：',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Lí chit ê iōng-chiá ê ia̍h',
'tooltip-pt-mytalk'               => 'Lí ê thó-lūn ia̍h',
'tooltip-pt-preferences'          => 'Lí ê siat-tēng',
'tooltip-pt-watchlist'            => '你監視的頁有改過的列表',
'tooltip-pt-mycontris'            => 'Lí ê kòng-hiàn lia̍t-toaⁿ',
'tooltip-pt-login'                => 'Hi-bāng lí teng-ji̍p; m̄-ko bô kiông-chè',
'tooltip-pt-logout'               => 'Teng-chhut',
'tooltip-ca-talk'                 => 'Loē-iông ê thó-lūn',
'tooltip-ca-edit'                 => 'Lí ē-sái kái chit ia̍h. Beh chhûn chìn-chiân, chhiáⁿ chhi̍h  sing-khoàⁿ-māi ê liú-á',
'tooltip-ca-addsection'           => '寫新的一段',
'tooltip-ca-viewsource'           => 'Chit ia̍h pó-hō͘ tiâu leh.
Lí ē-sái khoàⁿ i ê goân-sú-bé.',
'tooltip-ca-history'              => 'Chit ia̍h ê chá-chêng pán-pún',
'tooltip-ca-protect'              => '保護這頁',
'tooltip-ca-delete'               => 'Thâi chit ia̍h',
'tooltip-ca-move'                 => '徙這頁',
'tooltip-ca-watch'                => '共這頁加入你的監視單',
'tooltip-ca-unwatch'              => 'Lí ê kàm-sī-toaⁿ soá tiàu chit ia̍h.',
'tooltip-search'                  => 'Chhoé {{SITENAME}}',
'tooltip-search-go'               => '跳去佮這完全仝名的頁',
'tooltip-search-fulltext'         => 'Chhoé ū chia-ê jī ê ia̍h',
'tooltip-p-logo'                  => 'Khì thâu-ia̍h',
'tooltip-n-mainpage'              => 'Khì thâu-ia̍h',
'tooltip-n-mainpage-description'  => 'Khì thâu-ia̍h',
'tooltip-n-portal'                => 'Koan-hē chit ê sū-kang, lí ē-tāng chò siáⁿ, khì tó-ūi chhoé',
'tooltip-n-currentevents'         => 'Thê-kiong hiān-sî sin-bûn ê poē-kéng chu-liāu',
'tooltip-n-recentchanges'         => 'Choè-kīn tī wiki ū kái--koè ê lia̍t-toaⁿ',
'tooltip-n-randompage'            => 'Chhìn-chhái hian chi̍t ia̍h',
'tooltip-n-help'                  => 'Beh chhoé ê só͘-chāi',
'tooltip-t-whatlinkshere'         => 'Só͘-ū liân kàu chia ê liat-toaⁿ',
'tooltip-t-recentchangeslinked'   => 'Liân kàu chit ia̍h koh choè-kīn ū kái koè--ê',
'tooltip-feed-atom'               => '這頁有Atom訂看的',
'tooltip-t-contributions'         => 'Khoàⁿ chit ê iōng-chiá ê kòng-hiàn lia̍t-toaⁿ',
'tooltip-t-emailuser'             => '寄一張e-mail予這个用者',
'tooltip-t-upload'                => 'Í-keng sàng chiūⁿ-bāng ê tóng-àn',
'tooltip-t-specialpages'          => 'Só͘-ū te̍k-sû-ia̍h ê lia̍t-toaⁿ',
'tooltip-t-print'                 => 'Chit ia̍h ê ìn-soat pán-pún',
'tooltip-t-permalink'             => 'Chi̍t ia̍h kái--koè pán-pún ê éng-kiú liân-kiat',
'tooltip-ca-nstab-main'           => 'khoàⁿ ia̍h ê loē-iông',
'tooltip-ca-nstab-user'           => 'Khoàⁿ iōng-chiá ê Ia̍h',
'tooltip-ca-nstab-special'        => '這是一篇特殊頁，你袂當編輯。',
'tooltip-ca-nstab-project'        => '看事工頁',
'tooltip-ca-nstab-image'          => 'Khoàⁿ tóng-àn ia̍h',
'tooltip-ca-nstab-mediawiki'      => '看系統訊息',
'tooltip-ca-nstab-template'       => '看枋模',
'tooltip-ca-nstab-help'           => '看幫贊頁',
'tooltip-ca-nstab-category'       => 'Khoàⁿ lūi-pia̍t ia̍h',
'tooltip-minoredit'               => '共這做一个小修改記號',
'tooltip-save'                    => 'Pó-chhûn lí chò ê kái-piàn',
'tooltip-preview'                 => 'Chhiáⁿ tī pó-chûn chìn-chêng,  sian khoàⁿ lí chò ê kái-piàn !',
'tooltip-diff'                    => '顯示你對這頁所改的',
'tooltip-compareselectedversions' => '看選擇的兩个修訂本差偌濟',
'tooltip-watch'                   => '共這頁加入你的監視單',
'tooltip-rollback'                => 'Ji̍h "Hoê-choán" ē-sái thè tńg-khì téng-chi̍t-ê kái ê lâng ê ia̍h.',
'tooltip-undo'                    => '『取消』會使回轉這个編輯而且會使先看覓編輯的結果，閣會使佇概要加入原因。',
'tooltip-preferences-save'        => '保存設定',
'tooltip-summary'                 => 'Siá chi̍t-ê kán-tan soat-bêng',

# Attribution
'anonymous'     => '{{SITENAME}} bô kì-miâ ê iōng-chiá',
'siteuser'      => '{{SITENAME}} iōng-chiá $1',
'othercontribs' => 'Kin-kù $1 ê kòng-hiàn.',
'siteusers'     => '{{SITENAME}} iōng-chiá $1',

# Info page
'infosubtitle' => '頁的資料',
'numedits'     => '改幾擺（頁）： $1',
'numtalkedits' => '改幾擺（討論頁）：$1',

# Math options
'mw_math_png'    => 'Tiāⁿ-tio̍h iōng PNG render',
'mw_math_simple' => 'Tân-sûn ê chêng-hêng iōng HTML; kî-thaⁿ iōng PNG',
'mw_math_html'   => 'Chīn-liōng iōng HTML; kî-thaⁿ iōng PNG',
'mw_math_source' => 'Î-chhî TeX ê keh-sek (khah ha̍h bûn-jī-sek ê liû-lám-khì)',
'mw_math_modern' => 'Kiàn-gī hiān-tāi liû-lám-khì kéng che',
'mw_math_mathml' => 'Chīn-liōng iōng MathML (chhì-giām-sèng--ê)',

# Math errors
'math_failure'          => '解析失敗',
'math_unknown_error'    => '毋知啥物錯誤',
'math_unknown_function' => '毋知啥物函數',
'math_lexing_error'     => '句法錯誤',
'math_syntax_error'     => '語法錯誤',
'math_image_error'      => 'PNG 轉換失敗；請檢查看有正確安裝 latex, dvipng（或dvips + gs + convert）無？',
'math_bad_tmpdir'       => '無法度寫入抑是建立數學公式的臨時目錄',
'math_bad_output'       => '無法度寫入抑是建立數學公式的輸出目錄',
'math_notexvc'          => '無看"texvc"執行檔案；請看 math/README 做配置',

# Patrolling
'markaspatrolleddiff'                 => 'Phiau-sī sûn--kòe',
'markaspatrolledtext'                 => '共這頁記號做巡過',
'markedaspatrolled'                   => '記號做巡過',
'markedaspatrolledtext'               => 'Soán-te̍k  ê siu-tēng-pún [[:$1]]  í-keng kì-hō chò sûn--kòe.',
'rcpatroldisabled'                    => '巡最近改的功能已經關掉',
'markedaspatrollederror'              => '袂使記號做巡查過',
'markedaspatrollederrortext'          => '你愛指定一个修訂本是巡過的',
'markedaspatrollederror-noautopatrol' => '你袂當記號你家己改的修訂本是巡過的',

# Patrol log
'patrol-log-page'      => '巡查記錄',
'patrol-log-header'    => '這是一个已經巡查過的修訂本記錄',
'patrol-log-line'      => '$2的版本$1已經記號做巡查過$3',
'patrol-log-auto'      => '（自動）',
'patrol-log-diff'      => '修訂本 $1',
'log-show-hide-patrol' => '$1巡查記錄',

# Image deletion
'deletedrevision'       => 'Kū siu-tēng-pún $1 thâi-tiāu ā.',
'filedeleteerror-short' => '欲刣掉檔案的時陣有錯誤：$1',

# Browsing diffs
'previousdiff' => '← Khì chêng 1 ê siu-kái',
'nextdiff'     => 'Khì āu 1 ê siu-kái →',

# Media information
'imagemaxsize'         => 'Iáⁿ-siōng biô-su̍t-ia̍h ê tô· ke̍k-ke hián-sī jōa tōa tiuⁿ:',
'thumbsize'            => 'Sok-tô· (thumbnail) jōa tōa tiuⁿ:',
'file-info-size'       => '$1 × $2  像素，檔案大細：$3，MIME類型：$4',
'file-nohires'         => '<small>Bô khah koân ê kái-sek-tō͘.</small>',
'svg-long-desc'        => 'SVG 檔案，一般的長闊：$1 × $2 像素，檔案大小：$3',
'show-big-image'       => '檔案解析度',
'show-big-image-thumb' => '<small>Chit tiuⁿ ū-lám tô͘ (preview) ê toā-sè: $1 × $2 pixel</small>',

# Special:NewFiles
'newimages'     => 'Sin iáⁿ-siōng oē-lóng',
'imagelisttext' => "Í-hā sī '''$1''' tiuⁿ iáⁿ-siōng ê lia̍t-toaⁿ, $2 pâi-lia̍t.",
'ilsubmit'      => 'Kiám-sek',
'bydate'        => 'chiàu ji̍t-kî',

# Bad image list
'bad_image_list' => '規格照下跤：

只有（以 * 做頭）排列出的項目會處理。
每一逝的第一个連結是bad file的連結。
了後仝一逝後壁的連結會看做是例外，也就是彼个檔案會使佇佗位的頁面通顯示。',

# Metadata
'metadata'          => '元資訊',
'metadata-help'     => '這个檔案有其他的資訊，可能是翕相機抑是掃描器寫的，
若檔案有人改過，一寡說明就無完全反應改過的檔案',
'metadata-expand'   => 'Hián-sī iù-chiat',
'metadata-collapse' => 'Am iù-chiat',

# EXIF tags
'exif-imagedescription' => '影相標題',
'exif-make'             => '相機製造商',
'exif-model'            => '相機款式',
'exif-artist'           => '著作者',
'exif-copyright'        => '著作權所有人',

# External editor support
'edit-externally'      => 'Iōng gōa-pō· èng-iōng nńg-thé pian-chi̍p chit-ê tóng-àn',
'edit-externally-help' => '(Khoàⁿ [http://www.mediawiki.org/wiki/Manual:External_editors siat-tēng soat-bêng] ê chu-liāu.)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'choân-pō·',
'imagelistall'     => '全部',
'watchlistall2'    => 'choân-pō͘',
'namespacesall'    => 'choân-pō·',
'monthsall'        => 'choân-pō͘',
'limitall'         => '全部',

# E-mail address confirmation
'confirmemail'              => 'Khak-jīn e-mail chū-chí',
'confirmemail_text'         => 'Sú-iōng e-mail kong-lêng chìn-chêng tio̍h seng khak-jīn lí ê e-mail chū-chí ū-hāu. Chhi̍h ē-pêng hit-ê liú-á thang kià 1 tiuⁿ khak-jīn phoe hō· lí. Hit tiuⁿ phoe lāi-bīn ū 1 ê te̍k-sû liân-kiat. Chhiáⁿ iōng liû-lám-khì khui lâi khoàⁿ, án-ne tō ē-tit khak-jīn lí ê chū-chí ū-hāu.',
'confirmemail_send'         => 'Kià khak-jīn phoe',
'confirmemail_sent'         => 'Khak-jīn phoe kià chhut-khì ah.',
'confirmemail_invalid'      => 'Bô-hāu ê khak-jīn pian-bé. Pian-bé khó-lêng í-keng kòe-kî.',
'confirmemail_success'      => 'í ê e-mail chū-chí khak-jīn oân-sêng. Lí ē-sái teng-ji̍p, khai-sí hiáng-siū chit ê wiki.',
'confirmemail_loggedin'     => 'Lí ê e-mail chū-chí í-keng khak-jīn ū-hāu.',
'confirmemail_error'        => 'Pó-chûn khak-jīn chu-sìn ê sî-chūn hoat-seng būn-tê.',
'confirmemail_subject'      => '{{SITENAME}} e-mail chu-chi khak-jin phoe',
'confirmemail_body'         => 'Ū lâng (IP $1, tāi-khài sī lí pún-lâng) tī {{SITENAME}} ēng chit-ê e-mail chū-chí chù-chheh 1 ê kháu-chō "$2".

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
'confirmemail_body_set'     => 'Ū lâng (IP $1, tāi-khài sī lí pún-lâng) tī {{SITENAME}} ēng chit-ê e-mail chū-chí chù-chheh 1 ê kháu-chō "$2".

Chhiáⁿ khui ē-kha chit-ê liân-kiat, thang khak-jīn chit-ê kháu-chō si̍t-chāi sī lí ê:

$3

Nā-chún *m̄-sī* lí, chhiáⁿ khui ē-kha chit-ê liân-kiat,  chhú-siau khak-jīn ê e-mail.  

$5

Chit tiuⁿ phoe ê khak-jīn-bé ē chū-tōng tī $4 kòe-kî.',
'invalidateemail'           => '取消電子批的確認。',

# action=purge
'confirm-purge-top' => 'Kā chit ia̍h ê cache piàⁿ tiāu?',

# Multipage image navigation
'imgmultigo'   => '來去',
'imgmultigoto' => '來去$1這頁',

# Table pager
'table_pager_next'         => 'Aū-chi̍t-ia̍h',
'table_pager_prev'         => 'Téng-chi̍t-ia̍h',
'table_pager_first'        => 'Thâu-chi̍t-ia̍h',
'table_pager_last'         => 'Siāng-bóe-ia̍h',
'table_pager_limit'        => 'Múi 1 ia̍h hián-sī $1 hāng',
'table_pager_limit_submit' => 'Lâi-khì',

# Auto-summaries
'autosumm-blank'   => 'Kā ia̍h ê loē-iông the̍h tiāu',
'autoredircomment' => 'Choán khì [[$1]]',
'autosumm-new'     => 'Sin ia̍h: $1...',

# Live preview
'livepreview-loading' => '當咧讀',
'livepreview-ready'   => '讀....好矣！',

# Watchlist editor
'watchlistedit-numitems'      => 'Lí ê kàm-sī-toaⁿ ū $1 ia̍h, thó-lūn-ia̍h bô sǹg chāi-lāi.',
'watchlistedit-normal-submit' => 'Mài kàm-sī',
'watchlistedit-normal-done'   => 'Í-keng ū {{PLURAL:$1| ia̍h| ia̍h}} ùi lí ê kám-sī-toaⁿ soá cháu:',
'watchlistedit-raw-titles'    => '標題：',
'watchlistedit-raw-done'      => '你的監視單有改新。',

# Watchlist editing tools
'watchlisttools-view' => '看相關的修改',
'watchlisttools-edit' => 'Khoàⁿ koh kái kàm-sī-toaⁿ',
'watchlisttools-raw'  => 'Kái tshing-chheng ê kàm-sī-toaⁿ',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'Thê-chhíⁿ lí:\'\'\'Siat-piān ê pâi-lia̍t hong-sek "$2" thè-oāⁿ chìn-chêng ê siat-piān ê pâi-lia̍t hong-sek "$1".',

# Special:Version
'version'                  => 'Pán-pún',
'version-specialpages'     => '特殊頁',
'version-skins'            => '皮',
'version-license'          => '授權',
'version-software-version' => '版本',

# Special:FilePath
'filepath'        => 'Tóng-àn ê soàⁿ-lō·',
'filepath-submit' => '來去',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => '檔案名稱：',

# Special:SpecialPages
'specialpages'                   => 'Te̍k-sû-ia̍h',
'specialpages-group-maintenance' => '維修報告',
'specialpages-group-other'       => '其他的特殊頁',
'specialpages-group-login'       => '登入',
'specialpages-group-changes'     => '最近改的記錄',
'specialpages-group-wiki'        => 'Wiki資料佮家私',

# Special:BlankPage
'blankpage'              => '空的頁',
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
'tag-filter' => '[[Special:Tags|標籤]]過濾器:',
'tags-edit'  => '修改',

# Special:ComparePages
'compare-page1'  => '第一頁',
'compare-page2'  => '第二頁',
'compare-rev1'   => '第一修訂本',
'compare-rev2'   => '第二修訂本',
'compare-submit' => '比較',

# Database error messages
'dberr-header'   => '這个Wiki遇著問題',
'dberr-problems' => '失禮！
這馬這个站有技術上的問題。',

# HTML forms
'htmlform-invalid-input'       => '你拍的內底有一寡問題。',
'htmlform-select-badoption'    => '你寫的數量，無適合。',
'htmlform-int-invalid'         => '你寫的毋是數量。',
'htmlform-float-invalid'       => '你寫的毋是數量。',
'htmlform-int-toolow'          => '你寫的數量低過上細的量 $1。',
'htmlform-int-toohigh'         => '你寫的數量超過上大的量 $1。',
'htmlform-required'            => '這个數量愛寫',
'htmlform-selectorother-other' => '其他',

);
