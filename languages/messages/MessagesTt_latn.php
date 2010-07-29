<?php
/** Tatar (Latin) (Татарча/Tatarça (Latin))
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Albert Fazlî
 * @author Don Alessandro
 * @author KhayR
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Maxsus',
	NS_TALK             => 'Bäxäs',
	NS_USER             => 'Qullanuçı',
	NS_USER_TALK        => 'Qullanuçı_bäxäse',
	NS_PROJECT_TALK     => '$1_bäxäse',
	NS_FILE             => 'Fayl',
	NS_FILE_TALK        => 'Fayl_bäxäse',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_bäxäse',
	NS_TEMPLATE         => 'Ürnäk',
	NS_TEMPLATE_TALK    => 'Ürnäk_bäxäse',
	NS_HELP             => 'Yärdäm',
	NS_HELP_TALK        => 'Yärdäm_bäxäse',
	NS_CATEGORY         => 'Törkem',
	NS_CATEGORY_TALK    => 'Törkem_bäxäse',
);

$namespaceAliases = array(
	'Äğzä'             => NS_USER,
	'Äğzä_bäxäse'      => NS_USER_TALK,
	'Räsem'            => NS_FILE,
	'Räsem_bäxäse'     => NS_FILE_TALK,
);

$datePreferences = false;

$defaultDateFormat = 'dmy';

$dateFormats = array(
        'mdy time' => 'H:i',
        'mdy date' => 'M j, Y',
        'mdy both' => 'H:i, M j, Y',
        'dmy time' => 'H:i',
        'dmy date' => 'j M Y',
        'dmy both' => 'j M Y, H:i',
        'ymd time' => 'H:i',
        'ymd date' => 'Y M j',
        'ymd both' => 'H:i, Y M j',
        'ISO 8601 time' => 'xnH:xni:xns',
        'ISO 8601 date' => 'xnY-xnm-xnd',
        'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$magicWords = array(
	'redirect'              => array( '0', '#yünältü', '#REDIRECT' ),
	'notoc'                 => array( '0', '__ETYUQ__', '__NOTOC__' ),
	'forcetoc'              => array( '0', '__ETTIQ__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__ET__', '__TOC__' ),
	'noeditsection'         => array( '0', '__BÜLEMTÖZÄTÜYUQ__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'AĞIMDAĞI_AY', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'AĞIMDAĞI_AY_İSEME', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'AĞIMDAĞI_AY_İSEME_GEN', 'CURRENTMONTHNAMEGEN' ),
	'currentday'            => array( '1', 'AĞIMDAĞI_KÖN', 'CURRENTDAY' ),
	'currentdayname'        => array( '1', 'AĞIMDAĞI_KÖN_İSEME', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'AĞIMDAĞI_YIL', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'AĞIMDAĞI_WAQIT', 'CURRENTTIME' ),
	'numberofarticles'      => array( '1', 'MÄQÄLÄ_SANI', 'NUMBEROFARTICLES' ),
	'pagename'              => array( '1', 'BİTİSEME', 'PAGENAME' ),
	'namespace'             => array( '1', 'İSEMARA', 'NAMESPACE' ),
	'subst'                 => array( '0', 'TÖPÇEK:', 'SUBST:' ),
	'img_right'             => array( '1', 'uñda', 'right' ),
	'img_left'              => array( '1', 'sulda', 'left' ),
	'img_none'              => array( '1', 'yuq', 'none' ),
	'int'                   => array( '0', 'EÇKE:', 'INT:' ),
	'sitename'              => array( '1', 'SÄXİFÄİSEME', 'SITENAME' ),
	'ns'                    => array( '0', 'İA:', 'NS:' ),
	'localurl'              => array( '0', 'URINLIURL:', 'LOCALURL:' ),
	'localurle'             => array( '0', 'URINLIURLE:', 'LOCALURLE:' ),
);

$fallback8bitEncoding = "windows-1254";

$linkTrail = '/^([a-zäçğıñöşü“»]+)(.*)$/sDu';

$messages = array(
# Dates
'sunday'    => 'Yäkşämbe',
'monday'    => 'Düşämbe',
'tuesday'   => 'Sişämbe',
'wednesday' => 'Çärşämbe',
'thursday'  => 'Pänceşämbe',
'friday'    => 'Comğa',
'saturday'  => 'Şimbä',
'sun'       => 'Yäk',
'mon'       => 'Düş',
'tue'       => 'Siş',
'wed'       => 'Çär',
'thu'       => 'Pän',
'fri'       => 'Com',
'sat'       => 'Şim',
'january'   => 'Ğínwar',
'february'  => 'Febräl',
'march'     => 'Mart',
'april'     => 'Äpril',
'june'      => 'Yün',
'july'      => 'Yül',
'september' => 'Sentäber',
'october'   => 'Öktäber',
'november'  => 'Nöyäber',
'december'  => 'Dekäber',
'jan'       => 'Ğín',
'apr'       => 'Äpr',
'jun'       => 'Yün',
'jul'       => 'Yül',
'sep'       => 'Sen',
'oct'       => 'Okt',
'nov'       => 'Noy',
'dec'       => 'Dek',

# Categories related messages
'pagecategories'  => '{{PLURAL:$1|Cíıntıq|Cíıntıqlar}}',
'category_header' => '«$1» cíıntığınıñ mäqäläläre',
'subcategories'   => 'Eçke cíıntıqlar',

'linkprefix' => '/^(.*?)([a-zäçğıñöşüA-ZÄÇĞİÑÖŞÜ«„]+)$/sDu',

'about'         => 'Turında',
'article'       => 'Eçtälek bite',
'newwindow'     => '(yaña täräzädä açılır)',
'cancel'        => 'Kiräkmi',
'moredotdotdot' => 'Kübräk...',
'mypage'        => 'Bitem',
'mytalk'        => 'Bäxäsem',
'anontalk'      => 'Bu IP turında bäxäs',
'navigation'    => 'Küçü',
'and'           => '&#32;wä',

# Cologne Blue skin
'qbfind'         => 'Tap',
'qbbrowse'       => 'Qaraw',
'qbedit'         => 'Üzgärtü',
'qbpageoptions'  => 'Bu bit',
'qbpageinfo'     => 'Eçtälek',
'qbmyoptions'    => 'Bitlärem',
'qbspecialpages' => 'Maxsus bitlär',
'faq'            => 'YBS',
'faqpage'        => 'Project:YBS',

'errorpagetitle'    => 'Xata',
'returnto'          => '«$1» bitenä qaytu.',
'tagline'           => "{{SITENAME}}'dan",
'help'              => 'Yärdäm',
'search'            => 'Ezläw',
'searchbutton'      => 'Ezläw',
'go'                => 'Küç',
'searcharticle'     => 'Küç',
'history'           => 'Bit taríxı',
'history_short'     => 'Taríx',
'info_short'        => 'Belem',
'printableversion'  => 'Bastırulı yurama',
'permalink'         => 'Üzgärmäs bäy',
'edit'              => 'Üzgärtü',
'editthispage'      => 'Bit üzgärtü',
'delete'            => 'Beter',
'deletethispage'    => 'Beter bu bitne',
'protect'           => 'Yaqla',
'protectthispage'   => 'Yaqla bu bitne',
'unprotect'         => 'İreklä',
'unprotectthispage' => 'İreklä bu biten',
'newpage'           => 'Yaña bit',
'talkpage'          => 'Bit turında bäxäs',
'talkpagelinktext'  => 'bäxäs',
'specialpage'       => 'Maxsus Bit',
'personaltools'     => 'Şäxes qoralı',
'postcomment'       => 'Yazma qaldıru',
'articlepage'       => 'Eçtälek biten kürü',
'talk'              => 'Bäxäs',
'toolbox'           => 'Äsbäptirä',
'userpage'          => 'Äğzä biten qaraw',
'imagepage'         => 'Räsem biten qaraw',
'viewtalkpage'      => 'Bäxäsen qaraw',
'otherlanguages'    => 'Başqa tellärdä',
'redirectedfrom'    => '(«$1» bitennän yünältelde)',
'lastmodifiedat'    => 'Betniñ soñğı özgerişi $2, $1 bolğan.',
'protectedpage'     => 'Yaqlanğan bit',
'jumpto'            => 'Küç:',
'jumptosearch'      => 'ezläw',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} Turında',
'aboutpage'            => 'Project:Turında',
'copyright'            => 'Eçtälek $1 buyınça ireşüle.',
'copyrightpage'        => '{{ns:project}}:Qälämxaq',
'currentevents'        => 'Xäzerge waqíğalar',
'currentevents-url'    => 'Project:Xäzerge waqíğalar',
'edithelp'             => 'Üzgärtü xaqında',
'edithelppage'         => 'Help:Üzgärtü',
'helppage'             => 'Help:Eçtälek',
'mainpage'             => 'Başbit',
'mainpage-description' => 'Başbit',
'portal'               => 'Cämğiät üzäge',
'portal-url'           => 'Project:Cämğiät Üzäge',

'retrievedfrom'   => 'Bu bitneñ çığanağı: "$1"',
'newmessageslink' => 'yaña xäbär',
'editsection'     => 'üzgärtü',
'editold'         => 'üzgärtü',
'toc'             => 'Eçtälek tezmäse',
'showtoc'         => 'kürsät',
'hidetoc'         => 'yäşer',
'thisisdeleted'   => 'Qaraw/torğızu: $1',
'feedlinks'       => 'Tasma:',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Mäqälä',
'nstab-user'      => 'Äğzä bite',
'nstab-media'     => 'Media bite',
'nstab-special'   => 'Maxsus',
'nstab-project'   => 'Proyekt bite',
'nstab-image'     => 'Räsem',
'nstab-mediawiki' => 'Sätir',
'nstab-template'  => 'Äzerlämä',
'nstab-help'      => 'Yärdäm',
'nstab-category'  => 'Törkäm',

# Main script and global functions
'nosuchaction'      => 'Andí ğämäl barlıqta yuq',
'nosuchspecialpage' => 'Andí maxsus bit yuq',

# General errors
'error'           => 'Xata',
'databaseerror'   => 'Biremlek xatası',
'readonly'        => 'Biremlek yabılğan ide',
'internalerror'   => 'Eçke xata',
'filecopyerror'   => 'Bu «$1» biremen «$2» iseme belän küpli almím.',
'filerenameerror' => 'Bu «$1» biremen «$2» iseme belän küçerä almím.',
'filedeleteerror' => 'Bu «$1» biremen beterep bulmí.',
'filenotfound'    => 'Bu «$1» biremen tabalmím.',
'formerror'       => 'Xata: formını künderä almím',
'badtitle'        => 'Yaraqsız başlıq',
'perfcached'      => 'Astağı belem alxäterdän alındı wä anıñ xäzerge xäl belän turı kilmäwe bar:',
'perfcachedts'    => '$1 çağında bolğan torış asılında yasalğan tizme bo.',
'viewsource'      => 'Mäqälä çığanağı',

# Login and logout pages
'welcomecreation'       => "== Räxim it, $1! ==

Sineñ xísabıñ yasaldı. {{SITENAME}}'dağı köyläwläreñne dä üzgärtergä onıtma.",
'yourname'              => 'İreşü isemeñ',
'yourpassword'          => 'Sersüzeñ',
'yourpasswordagain'     => 'Sersüz qabat',
'remembermypassword'    => 'Tanı mine kergändä.',
'login'                 => 'İreşü',
'userlogin'             => 'Xísap yasaw yä ki kerü',
'logout'                => 'Çığu',
'userlogout'            => 'Çığu',
'notloggedin'           => 'Kermädeñ äle',
'createaccount'         => 'Yaña xísap yasaw',
'createaccountmail'     => 'email buyınça',
'badretype'             => 'Kertelgän sersüzeñ kileşmi.',
'userexists'            => 'Äle genä kertkäneñ äğzä iseme qullanıla inde. Başqa isem sayla zínhar.',
'loginerror'            => 'Kerü xatası',
'loginsuccesstitle'     => 'Uñışlı kergänbez',
'loginsuccess'          => "Sin {{SITENAME}}'ğa «$1» atama belän kergän buldıñ.",
'wrongpassword'         => 'Sin kertän sersüz xatalı axrısı. Tağın kertep qara zínhar.',
'mailmypassword'        => 'Yaña sersüzne xat belän cibär',
'passwordremindertitle' => '{{SITENAME}} sersüz xäterlätkeçe',
'passwordsent'          => 'Yaña sersüz «$1» terkälüendä kertelgän e-mail buyınça cibärelde.
Anı alğaç monda tağın kerep qara.',
'mailerror'             => 'Xat künderü xatası: $1',

# Password reset dialog
'oldpassword' => 'İske sersüz',
'newpassword' => 'Yaña sersüz',
'retypenew'   => 'Yaña sersüz (qabat)',

# Edit page toolbar
'bold_sample'     => 'Qalın mäten',
'bold_tip'        => 'Qalın mäten',
'italic_sample'   => 'Awışlı mäten',
'italic_tip'      => 'Awışlı mäten',
'link_sample'     => 'Läñker başlığı',
'link_tip'        => 'Eçke läñker',
'extlink_sample'  => 'http://www.example.com läñker başlığı',
'extlink_tip'     => 'Tışqı läñker (alğı http:// quşımtasın onıtma)',
'headline_sample' => 'Başlıq mätene',
'headline_tip'    => '2. däräcäle başlıq',
'math_sample'     => 'Formulnı monda kert',
'math_tip'        => 'İsäpläw formulı (LaTeX)',
'nowiki_sample'   => 'Taqır mäten urnaştıram',
'nowiki_tip'      => 'Wiki-qalıp eşkärtmäskä',
'image_sample'    => 'Mísal.jpg',
'image_tip'       => 'Quşılğan räsem',
'media_sample'    => 'Mísal.ogg',
'sig_tip'         => 'Ímzañ belän zaman/waqıt tamğası',
'hr_tip'          => 'Yatma sızıq (siräk qullan)',

# Edit pages
'summary'            => 'Yomğaq:',
'subject'            => 'Ni turında/başlıq:',
'minoredit'          => 'Bu waq-töyäk üzgärmä genä',
'watchthis'          => 'Bitne küzätep torası',
'savearticle'        => 'Saqla biremne',
'preview'            => 'Küzläw',
'showpreview'        => 'Qarap alu...',
'blockedtitle'       => 'Qullanuçı tíıldı',
'whitelistedittitle' => 'Üzgärtü öçen, kerü täläp itelä',
'loginreqtitle'      => 'Kerergä Kiräk',
'loginreqlink'       => 'keräse',
'accmailtitle'       => 'Sersüz künderelde.',
'accmailtext'        => "Bu '$1' öçen digän sersüz '$2' adrésına cibärelde.",
'newarticle'         => '(Yaña)',
'clearyourcache'     => "'''İskärmä:''' Saqlawdan soñ, üzgärmälärne kürü öçen browserıñnıñ alxäteren buşatası bar: '''Mozilla:''' click ''reload''(yä ki ''ctrl-r''), '''IE / Opera:''' ''ctrl-f5'', '''Safari:''' ''cmd-r'', '''Konqueror''' ''ctrl-r''.",
'updated'            => '(Yañartıldı)',
'note'               => "'''İskärmä:'''",
'editing'            => 'Üzgärtü: $1',
'editconflict'       => 'Üzgärtü qíınlığı: $1',
'yourtext'           => 'Mäteneñ',
'storedversion'      => 'Saqlanğan yurama',
'editingold'         => "'''KİSÄTMÄ: Sin bu bitneñ iskergän yuramasın üzgärtäsen.
Ägär sin monı saqlísıñ ikän, şul yuramadan soñ yasalğan üzgärmälär yuğalır.'''",
'yourdiff'           => 'Ayırmalar',
'longpagewarning'    => "KİSÄTMÄ: Bu bit zurlığı $1 kB; qayber browserlarda 32 kB'tan da zurraq bulğan bitlärne kürsätkändä qíınlıqlar bula.
Zínhar, bu bitneñ wağraq kisäklärgä bülü turında uylap qara.",
'template-protected' => '(yaqlanmış)',

# History pages
'currentrev' => 'Ağımdağı yurama',
'cur'        => 'xäzer',
'next'       => 'kiläse',
'last'       => 'soñğı',

# Diffs
'difference'              => '(Yuramalar ayırması)',
'lineno'                  => '$1. yul:',
'compareselectedversions' => 'Saylanğan yurama çağıştıru',

# Search results
'searchresults'  => 'Ezläw näticäse',
'titlematches'   => 'Mäqälä başlığı kileşä',
'notitlematches' => 'Kileşkän bit başlığı yuq',
'notextmatches'  => 'Kileşkän bit mätene yuq',
'prevn'          => 'uzğan {{PLURAL:$1|$1}}',
'nextn'          => 'kiläse {{PLURAL:$1|$1}}',
'viewprevnext'   => 'Kürsätäse: ($1 {{int:pipe-separator}} $2) ($3)',
'searchhelp-url' => 'Help:Eçtälek',
'powersearch'    => 'Ezläw',

# Quickbar
'qbsettings' => 'Tiztirä caylawı',

# Preferences page
'preferences'        => 'Köyläwem',
'mypreferences'      => 'Köyläwem',
'prefsnologin'       => 'Kermägänseñ',
'changepassword'     => 'Sersüz üzgärtü',
'prefs-skin'         => 'Tışlaw',
'skin-preview'       => 'Küzläw',
'prefs-misc'         => 'Başqa köyläwlär',
'saveprefs'          => 'Saqla köyläwlärne',
'resetprefs'         => 'Awdar köyläwne',
'prefs-editing'      => 'Mätenqır ülçäme',
'rows'               => 'Yul:',
'columns'            => 'Buy:',
'searchresultshead'  => 'Ezläw',
'resultsperpage'     => 'Bit sayın näticä sanı',
'recentchangescount' => 'Soñğı üzgärtmä tezmäsendä başlıq sanı',
'savedprefs'         => 'Köyläwläreñ saqlandı.',
'timezonelegend'     => 'Waqıt quşağı',
'localtime'          => 'Cirle waqıt belän kürsätäse',
'timezoneoffset'     => 'Çigenü',
'servertime'         => 'Serverda xäzerge waqıt',
'guesstimezone'      => 'Browserdan alası',
'defaultns'          => 'Ğädättä bu isemarada ezlise:',
'default'            => 'töpcay',
'prefs-files'        => 'Fayllar',
'youremail'          => "Email'ıñ*",
'yourrealname'       => 'Çın isemeñ*',
'yournick'           => 'Atamañ:',

# Recent changes
'recentchanges'     => 'Soñğı üzgärtmälär',
'recentchangestext' => 'Bu bittä wikidä bulğan iñ soñğı üzgärtmäläre kürsätelä.',
'rcnotefrom'        => 'Asta <b>$2</b> zamanınnan soñ bulğan üzgärtmälär (<b>$1</b> tikle).',
'rclistfrom'        => '$1 zamannan soñ bulğan üzgärtmälär.',
'rcshowhideminor'   => 'keçe üzgärtmä $1',
'rcshowhidebots'    => 'bot $1',
'rcshowhideliu'     => 'tanılğanın $1',
'rcshowhideanons'   => 'tanılmağanın $1',
'rcshowhidemine'    => 'üzem üzgärtkänem $1',
'rclinks'           => 'Soñğı $2 kön eçendä bulğan $1 üzgärtmä<br />$3',
'diff'              => 'ayırma',
'hist'              => 'taríx',
'hide'              => 'yäşer',
'show'              => 'kürsät',
'minoreditletter'   => 'w',
'newpageletter'     => 'Y',

# Recent changes linked
'recentchangeslinked'         => 'Bäyle üzgärmä',
'recentchangeslinked-feed'    => 'Bäyle üzgärmä',
'recentchangeslinked-toolbox' => 'Bäyle üzgärmä',
'recentchangeslinked-title'   => '$1 belän bäyle üzgärmä',

# Upload
'upload'              => 'Birem yökläw',
'uploadbtn'           => 'Yöklä biremne',
'reuploaddesc'        => 'Yökläw bitenä qaytu.',
'uploadnologin'       => 'Kermädeñ',
'uploadnologintext'   => 'Birem yökläw öçen, säxifägä isem belän [[Special:UserLogin|keräse]].',
'uploaderror'         => 'Yökläw xatası',
'uploadlog'           => 'yökläw könlege',
'uploadlogpage'       => 'Yökläw_könlege',
'uploadlogpagetext'   => 'Asta soñğı arada yöklängän birem tezmäse kiterelä.',
'filename'            => 'Birem iseme',
'filedesc'            => 'Yomğaq',
'fileuploadsummary'   => 'Yomğaq:',
'filestatus'          => 'Qälämxaq xäläte:',
'filesource'          => 'Çığanaq:',
'uploadedfiles'       => 'Yöklängän biremnär',
'ignorewarning'       => 'Kisätmägä qaramíçı biremne härxäldä saqla.',
'badfilename'         => 'Räsem iseme «$1» itep üzgärtelde.',
'uploadwarning'       => 'Yökläw kisätmäse',
'savefile'            => 'Saqla biremne',
'uploadedimage'       => 'yöklände "$1"',
'uploaddisabled'      => 'Ğafu it, yökläw sünderelgän kileş tora.',
'upload-success-subj' => 'Yökläw uñışlı uzdı',

# Special:ListFiles
'imgfile'   => 'fayl',
'listfiles' => 'Räsem tezmäse',

# File description page
'file-anchor-link'    => 'Räsem',
'filehist-dimensions' => 'Zurlıq',
'filehist-comment'    => 'Açıqlama',
'imagelinks'          => 'Räsem läñkerläre',

# File reversion
'filerevert-comment' => 'Açıqlama:',

# File deletion
'filedelete-submit'           => 'Beter',
'filedelete-reason-otherlist' => 'Başqa sebep',
'filedelete-reason-dropdown'  => '*Beterergä tüp säbäp
** Qälämxaqq buzılışı
** Qabatlanğan birem',

# Unused templates
'unusedtemplates' => 'Qullanılmağan ürnäklär',

# Random page
'randompage' => 'Berär bit kürü',

# Statistics
'statistics'              => 'Nöfüs',
'statistics-header-users' => 'Qullanuçı nöfüse',

'disambiguations' => 'Saylaqbit tezmäse',

'doubleredirects' => 'Küpmälle yünältü',

'brokenredirects'        => 'Watıq Yünältülär',
'brokenredirectstext'    => 'Kiläse yünältülär bulmağan bitlärgä qarílar.',
'brokenredirects-edit'   => 'üzgärtü',
'brokenredirects-delete' => 'beter',

# Miscellaneous special pages
'lonelypages'             => 'Yätim bitlär',
'uncategorizedpages'      => 'Cíıntıqlanmağan bitlär',
'uncategorizedcategories' => 'Cıyıntıqqa salınmağan cıyıntıq',
'uncategorizedimages'     => 'Cıyıntıqqa salınmağan berim',
'uncategorizedtemplates'  => 'Cıyıntıqqa salınmağan örçitme',
'unusedcategories'        => 'Totılmağan cıyıntıq',
'unusedimages'            => 'Qullanılmağan räsemnär',
'popularpages'            => 'Ğämäli bitlär',
'wantedcategories'        => 'Yaratası cıyıntıq tezmäse',
'wantedpages'             => 'Kiräkle bitlär',
'shortpages'              => 'Qısqa bitlär',
'longpages'               => 'Ozın bitlär',
'deadendpages'            => 'Başqa bitkä bäyle bulmağanı',
'listusers'               => 'Äğzä isemlege',
'newpages'                => 'Yaña bitlär',
'ancientpages'            => 'İñ iske bitlär',
'move'                    => 'Küçerü',
'movethispage'            => 'Bu bit küçerü',
'notargettitle'           => 'Maqsatsız',

# Book sources
'booksources' => 'Kitap çığanağı',

# Special:Log
'log'           => 'Köndäleklär',
'all-logs-page' => 'Barlıq köndelik',

# Special:AllPages
'allpages'     => 'Bar bitlär',
'nextpage'     => 'Kiläse bit ($1)',
'prevpage'     => 'Ütkän bit ($1)',
'allpagesfrom' => 'Mondıy başlanğan bitlär:',

# Special:Categories
'categories'         => 'Cíıntıqlar',
'categoriespagetext' => "Bu wiki'dä kiläse cíıntıqlar bar.",

# Special:ListUsers
'listusers-submit' => 'Kürsät',

# E-mail user
'emailuser'     => 'E-mail cibärü',
'emailpage'     => 'E-mail cibärü',
'noemailtitle'  => 'E-mail adres kürsätelmäde',
'emailfrom'     => 'Kemnän',
'emailto'       => 'Kemgä',
'emailsubject'  => 'Ni turında',
'emailmessage'  => 'Xäbär',
'emailsend'     => 'Cibär',
'emailsent'     => 'E-mail künderelde',
'emailsenttext' => "E-mail'ıñ künderelde.",

# Watchlist
'watchlist'        => 'Saqtezmäm',
'mywatchlist'      => 'Saqtezmäm',
'nowatchlist'      => 'Saqtezmäñdä kertemnär yuq.',
'watchnologin'     => 'Kermädeñ',
'watchnologintext' => 'Saqtezmäñ üzgärtü öçen, säxifägä isem belän [[Special:UserLogin|keräse]].',
'addedwatch'       => 'Saqtezmägä quşıldı',
'removedwatch'     => 'Saqtezmädän salındı',
'removedwatchtext' => '«[[:$1]]» atlı bit saqtezmäñnän töşerelde.',
'watch'            => 'Saqlaw',
'watchthispage'    => 'Bitne küzätep torası',
'notanarticle'     => 'Eçtälek belän bit tügel',

# Delete
'deletepage'            => 'Beter bitne',
'confirm'               => 'Raslaw',
'excontentauthor'       => "soñğı eçtälege: '$1' ('[[Special:Contributions/$2|$2]]' ğına qatnaşqan)",
'exblank'               => 'bit buş ide',
'delete-confirm'        => '«$1» beterü',
'delete-legend'         => 'Beterü',
'historywarning'        => 'Íğtíbar: Beterergä telägän biteneñ üz taríxı bar:',
'actioncomplete'        => 'Ğämäl tämam',
'deletedtext'           => '«<nowiki>$1</nowiki>» beterelgän buldı.
Soñğı beterülär $2 bitendä terkälenä.',
'deletedarticle'        => '«$1» beterelde',
'dellogpage'            => 'Beterü_köndälege',
'deletionlog'           => 'beterü köndälege',
'reverted'              => 'Aldağı yuramanı qaytart',
'deletecomment'         => 'Säbäp',
'deleteotherreason'     => 'Başqa/üstämä säbäp:',
'deletereasonotherlist' => 'Başqa säbäp',
'deletereason-dropdown' => '*Beterergä tüp säbäp
** Yazğanı soradı
** Qälämxaqq buzılışı
** Buzıp yörüçi eşe',

# Rollback
'editcomment' => "Bu üzgärtü taswírı: \"''\$1''\".",

# Protect
'protectlogpage'     => 'Yaqlaw_köndälege',
'protectedarticle'   => '[[$1]] yaqlandı',
'unprotectedarticle' => '[[$1]] ireklände',
'protect-title'      => '«$1» yaqlaw',
'prot_1movedto2'     => '$1 moña küçte: $2',
'protect-legend'     => 'Yaqlawnı raslaw',
'protectcomment'     => 'Säbäp',
'protectexpiry'      => 'İske bulaçaq:',

# Undelete
'undelete'         => 'Beterelgän bit torğızu',
'undeletebtn'      => 'Torğız!',
'undeletedarticle' => '«$1» torğızıldı',

# Namespace form on various pages
'namespace'      => 'At-alan:',
'invert'         => 'Saylanğannı äylän',
'blanknamespace' => '(Töp)',

# Contributions
'contributions' => 'Äğzä qatnaşuı',
'mycontris'     => 'Qatnaşuım',
'contribsub2'   => '$1 ($2) öçen',
'uctop'         => ' (soñ)',

'sp-contributions-talk' => 'bäxäs',

# What links here
'whatlinkshere' => 'Kem bäyle moña',
'isredirect'    => 'küçerelü bite',

# Block/unblock
'blockip'            => 'Qullanuçı tíu',
'ipaddress'          => 'IP Adres/äğzäisem',
'ipbexpiry'          => 'İskerer',
'ipbreason'          => 'Säbäp',
'ipbsubmit'          => 'Bu keşene tíu',
'badipaddress'       => 'Xatalı IP adrésı',
'blockipsuccesssub'  => 'Tíu uzdı',
'unblockip'          => 'Äğzäne irekläw',
'ipusubmit'          => 'Bu adresnı irekläw',
'ipblocklist'        => 'Tíılğan IP/äğzä tezmäse',
'infiniteblock'      => 'äytelmägän',
'blocklink'          => 'tíu',
'contribslink'       => 'qatnaşuı',
'blocklogpage'       => 'Tíu_köndälege',
'ipb_expiry_invalid' => 'İskärü waqıtı xatalı.',
'ip_range_invalid'   => 'Xatalı IP arası.',
'proxyblocker'       => 'Proxy tíu',
'proxyblocksuccess'  => 'Buldı.',

# Developer tools
'lockdb'              => 'Biremlekne yozaqlaw',
'unlockdb'            => 'Biremlek irekläw',
'lockconfirm'         => 'Äye, min biremlekne çınlap ta yozaqlarğa buldım.',
'lockbtn'             => 'Biremlekne yozaqlaw',
'lockdbsuccesssub'    => 'Biremlek yözaqlandı',
'unlockdbsuccesssub'  => 'Biremlek yozağı salındı',
'unlockdbsuccesstext' => 'Bu biremlek yozağı salınğan ide.',

# Move page
'move-page-legend' => 'Bit küçerü',
'movearticle'      => 'Küçeräse bit',
'movenologin'      => 'Kermädeñ',
'newtitle'         => 'Yaña başlıq',
'movepagebtn'      => 'Küçer bitne',
'pagemovedsub'     => 'Küçerü uñışlı uzdı',
'articleexists'    => 'Andí atlı bit bar inde,
yä isä saylanğan isem yaraqsız buldı. Başqa isem sayla zínhar.',
'movedto'          => 'küçerelde:',
'movetalk'         => 'Mömkin bulsa, «bäxäs» biten dä küçer.',
'1movedto2'        => '[[$1]] moña küçte: [[$2]]',
'1movedto2_redir'  => '[[$1]] moña küçte: [[$2]] (yünältü aşa)',

# Namespace 8 related
'allmessages'        => 'Säxifäneñ bar sätirläre',
'allmessagesname'    => 'Atalış',
'allmessagesdefault' => 'Töpcay yazma',
'allmessagescurrent' => 'Eligi yazma',
'allmessagestext'    => 'Bu säxifäneñ MediaWiki: atarasında bulğan yazmalar tezmäse.',

# Thumbnails
'thumbnail-more' => 'Zuraytası',

# Special:Import
'import'         => 'Bitlärne yökläw',
'import-comment' => 'Açıqlama:',
'importfailed'   => 'Yökläw xatası: $1',
'importnotext'   => 'Buş yä ki mäten tügel',
'importsuccess'  => 'Yökläw uñışlı buldı!',

# Tooltip help for the actions
'tooltip-pt-userpage'        => 'Şäxsi bitem',
'tooltip-pt-mytalk'          => 'Bäxäs bitem',
'tooltip-pt-preferences'     => 'Köyläwlärem',
'tooltip-pt-mycontris'       => 'Qatnaşuım tezmäse',
'tooltip-pt-logout'          => 'Çığış',
'tooltip-ca-addsection'      => 'Bu bäxästä yazma östäw.',
'tooltip-ca-viewsource'      => 'Bu bit yaqlanğan ide. Anıñ çığanağın kürä alasıñ.',
'tooltip-ca-history'         => 'Bu bitneñ soñğı yuramaları.',
'tooltip-ca-protect'         => 'Bu bit yaqlaw',
'tooltip-ca-delete'          => 'Bu bit beterü',
'tooltip-ca-move'            => 'Bu bit küçerü',
'tooltip-ca-watch'           => 'Bu bitne saqtezmägä östäw',
'tooltip-ca-unwatch'         => 'Bu bitne saqtezmädän töşerü',
'tooltip-search'             => 'Äydä, ezlä monı',
'tooltip-p-logo'             => 'Başbit',
'tooltip-n-mainpage'         => 'Başbitkä küçü',
'tooltip-n-randompage'       => 'Berär nindi bit kürsätä',
'tooltip-feed-rss'           => 'Bu bitneñ RSS tasması',
'tooltip-feed-atom'          => 'Bu bitneñ Atom tasması',
'tooltip-t-specialpages'     => 'Bar maxsus bitlär tezmäse',
'tooltip-ca-nstab-main'      => 'Bu bit eçtälegen kürü',
'tooltip-ca-nstab-user'      => 'Bu äğzä biten kürü',
'tooltip-ca-nstab-media'     => 'Bu media biten kürü',
'tooltip-ca-nstab-special'   => 'Bu bit maxsus, wä sin anı üzgärtä almísıñ.',
'tooltip-ca-nstab-project'   => 'Proékt biten kürü',
'tooltip-ca-nstab-image'     => 'Bu räsem biten kürü',
'tooltip-ca-nstab-mediawiki' => 'Bu säxifä sätiren kürü',
'tooltip-ca-nstab-template'  => 'Bu qalıpnı kürü',
'tooltip-ca-nstab-help'      => 'Bu yärdäm biten kürü',
'tooltip-minoredit'          => 'Bu üzgärtmä waq-töyäk dip bilgelä',
'tooltip-save'               => 'Üzgärtüne saqlaw',

# Attribution
'anonymous'     => "{{SITENAME}}'nıñ tanılmağan kerüçe",
'siteuser'      => '{{SITENAME}} ägzäse $1',
'othercontribs' => '«$1» eşenä nigezlänä.',
'others'        => 'başqalar',
'siteusers'     => '{{SITENAME}} ägzäse $1',
'creditspage'   => 'Bit yasawında qatnaşqan',

# Spam protection
'spamprotectiontitle' => 'Çüpläwdän saqlanu eläge',

# Info page
'infosubtitle' => 'Bit turında',

# Math errors
'math_failure'          => 'Uqí almadım',
'math_unknown_error'    => 'tanılmağan xata',
'math_unknown_function' => 'tanılmağan funksí',
'math_lexing_error'     => 'nöhü xatası',
'math_syntax_error'     => 'nöhü xatası',

# Media information
'show-big-image' => 'Tulı zurlığı',

# Special:NewFiles
'ilsubmit' => 'Ezläw',
'bydate'   => 'waqıt buyınça',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'barlıq',

# Multipage image navigation
'imgmultipageprev' => '← ütkän bit',
'imgmultipagenext' => 'kiläse bit →',

# Table pager
'table_pager_next'         => 'Kiläse bit',
'table_pager_prev'         => 'Ütkän bit',
'table_pager_first'        => 'Berençe bit',
'table_pager_last'         => 'Soñğı bet',
'table_pager_limit_submit' => 'Eyde',

# Auto-summaries
'autosumm-new' => 'Yeni bet: $1',

# Special:Version
'version' => 'Yurama',

# Special:SpecialPages
'specialpages' => 'Maxsus bitlär',

);
