<?php
/** Kara-Kalpak (Qaraqalpaqsha)
 *
 * @addtogroup Language
 *
 * @author Atabek
 * @author Jiemurat
 * @author AlefZet
 */

$linkTrail = '/^([a-zʻ`]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-showtoolbar'      => "O'zgertiw a'sbapların ko'rset (JavaScript)",
'tog-showtoc'          => "Mazmunın ko'rset (3-ten artıq bo'limi bar betlerge)",
'tog-rememberpassword' => "Menin' kirgenimdi usı kompyuterde saqlap qal",
'tog-previewonfirst'   => "Birinshi o'zgertiwde ko'rip shıq",

'underline-always' => "Ha'r dayım",
'underline-never'  => 'Hesh qashan',

'skinpreview' => '(Korip al)',

# Dates
'sunday'        => 'Ekshenbi',
'monday'        => "Du'yshenbi",
'tuesday'       => 'Siyshenbi',
'wednesday'     => "Sa'rshenbi",
'thursday'      => 'Piyshenbi',
'friday'        => 'Juma',
'saturday'      => 'Shenbi',
'sun'           => 'Eks',
'mon'           => 'Dsh',
'tue'           => 'Ssh',
'wed'           => "Sa'r",
'thu'           => 'Psh',
'fri'           => 'Jum',
'sat'           => 'Shn',
'january'       => 'Yanvar',
'february'      => 'Fevral',
'march'         => 'Mart',
'april'         => 'Aprel',
'may_long'      => 'May',
'june'          => 'İyun',
'july'          => 'İyul',
'august'        => 'Avgust',
'september'     => 'Sentyabr',
'october'       => 'Oktyabr',
'november'      => 'Noyabr',
'december'      => 'Dekabr',
'january-gen'   => "Yanvardın'",
'february-gen'  => "Fevraldın'",
'march-gen'     => "Marttın'",
'april-gen'     => "Apreldin'",
'may-gen'       => "Maydın'",
'june-gen'      => "İyunnin'",
'july-gen'      => "İyuldin'",
'august-gen'    => "Avgusttın'",
'september-gen' => "Sentyabrdin'",
'october-gen'   => "Oktyabrdin'",
'november-gen'  => "Noyabrdin'",
'december-gen'  => "Dekabrdin'",
'jan'           => 'Yan',
'feb'           => 'Fev',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'May',
'jun'           => 'İun',
'jul'           => 'İul',
'aug'           => 'Avg',
'sep'           => 'Sen',
'oct'           => 'Okt',
'nov'           => 'Noy',
'dec'           => 'Dek',

# Bits of text used by many pages
'categories'            => 'Kategoriyalar',
'pagecategories'        => '{{PLURAL:$1|Kategoriya|Kategoriyalar}}',
'category_header'       => '"$1" kategoriyasindag\'ı betler',
'subcategories'         => 'Podkategoriyalar',
'category-media-header' => '"$1" kategoriyasindag\'ı media',
'category-empty'        => "''Bul kategoriyada ha'zir hesh bet yamasa media joq''",

'mainpagetext' => "<big>'''MediaWiki tabıslı ornatıldı.'''</big>",

'about'          => "Tu'sindirme",
'article'        => "Mag'lıwmat beti",
'newwindow'      => "(jan'a aynada)",
'cancel'         => 'Biykar',
'qbfind'         => 'İzlew',
'qbbrowse'       => "Ko'riw",
'qbedit'         => "O'zgertiw",
'qbpageoptions'  => 'Usı bet',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => "Sizin' sazlawlarınız",
'qbspecialpages' => 'Arnawlı betler',
'moredotdotdot'  => "Ja'ne...",
'mypage'         => 'Shaxsıy bet',
'mytalk'         => "Menin' sa'wbetim",
'anontalk'       => "Usı IPg'a sa'wbet",
'navigation'     => 'Navigatsiya',

# Metadata in edit box
'metadata_help' => "Metamag'lıwmat:",

'errorpagetitle'   => 'Qatelik',
'returnto'         => '$1 betine qaytıw.',
'tagline'          => "{{SITENAME}} saytınan mag'lıwmat",
'help'             => 'Anıqlama',
'search'           => 'İzlew',
'searchbutton'     => 'İzlew',
'go'               => "O'tiw",
'searcharticle'    => "O'tiw",
'history'          => 'Bet tariyxı',
'history_short'    => 'Tariyx',
'info_short'       => "Mag'lıwmat",
'edit'             => "O'zgertiw",
'editthispage'     => "Usı betti o'zgertiw",
'delete'           => "O'shiriw",
'deletethispage'   => "Usı betti o'shiriw",
'protect'          => "Qorg'aw",
'protectthispage'  => "Bul betti qorg'aw",
'newpage'          => 'Taza bet',
'talkpage'         => 'Bul betti diskussiyalaw',
'talkpagelinktext' => "Sa'wbet",
'specialpage'      => 'Arnawlı bet',
'personaltools'    => "Paydalanıwshı a'sbapları",
'postcomment'      => 'Kommentariy beriw',
'articlepage'      => "Mag'lıwmat betin ko'riw",
'talk'             => 'Diskussiya',
'views'            => "Ko'rinis",
'toolbox'          => "A'sbaplar",
'userpage'         => "Paydalanıwshı betin ko'riw",
'projectpage'      => "Proekt betin ko'riw",
'imagepage'        => "Su'wret betin ko'riw",
'mediawikipage'    => "Xabar betin ko'riw",
'templatepage'     => "Shablon betin ko'riw",
'viewhelppage'     => "Anıqlama betin ko'riw",
'categorypage'     => "Kategoriya betin ko'riw",
'viewtalkpage'     => "Diskussiyanı ko'riw",
'otherlanguages'   => 'Basqa tillerde',
'lastmodifiedat'   => "Bul bettin' aqırg'ı ma'rte o'zgertilgen waqtı: $2, $1.", # $1 date, $2 time
'viewcount'        => "Bul bet {{PLURAL:$1|bir ma'rte|$1 ma'rte}} ko'rip shıg'ılg'an.",
'protectedpage'    => "Qorg'ang'an bet",
'jumpto'           => "Bug'an o'tiw:",
'jumptonavigation' => 'navigatsiya',
'jumptosearch'     => 'izlew',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} haqqında',
'aboutpage'         => "{{ns:project}}:Tu'sindirme",
'copyrightpagename' => "{{SITENAME}} proektinin' avtorlıq huquqları",
'copyrightpage'     => '{{ns:project}}:Avtorlıq huquqı',
'currentevents'     => "Ha'zirgi ha'diyseler",
'currentevents-url' => "Ha'zirgi ha'diyseler",
'edithelp'          => "O'zgertiw anıqlaması",
'edithelppage'      => "{{ns:help}}:O'zgertiw",
'helppage'          => '{{ns:help}}:Mazmunı',
'mainpage'          => 'Bas bet',
'policy-url'        => "{{ns:project}}:Qag'ıydalar",
'portal'            => "Ja'miyet portalı",
'portal-url'        => "{{ns:project}}:Ja'miyet Portalı",
'privacy'           => "Konfidentsiallıq qag'ıydası",
'privacypage'       => "{{ns:project}}:Konfidentsiallıq qag'ıydası",

'badaccess' => 'Ruxsatnama qateligi',

'versionrequired' => "MediaWikidin' $1 versiyası kerek",

'youhavenewmessages'      => 'Sizge $1 bar ($2).',
'newmessageslink'         => "jan'a xabarlar",
'newmessagesdifflink'     => "aqırg'ı o'zgeris",
'youhavenewmessagesmulti' => "$1 betinde sizge jan'a xabarlar bar",
'editsection'             => "o'zgertiw",
'editold'                 => "o'zgertiw",
'editsectionhint'         => "Bo'lim o'zgertiw: $1",
'toc'                     => 'Mazmunı',
'showtoc'                 => "ko'rset",
'hidetoc'                 => 'jasır',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Bet',
'nstab-user'      => 'Paydalanıwshı beti',
'nstab-media'     => 'Media beti',
'nstab-special'   => 'Arnawlı',
'nstab-project'   => 'Proekt beti',
'nstab-image'     => 'Fayl',
'nstab-mediawiki' => 'Xabar',
'nstab-template'  => 'Shablon',
'nstab-help'      => 'Anıqlama beti',
'nstab-category'  => 'Kategoriya',

# Main script and global functions
'nosuchspecialpage' => 'Bunday arnawlı bet joq',

# General errors
'error'              => "Qa'telik",
'databaseerror'      => "Mag'lıwmatlar bazası qa'tesi",
'internalerror'      => "İshki qa'telik",
'internalerror_info' => "İshki qa'telik: $1",
'viewsourcefor'      => '$1 ushın',

# Login and logout pages
'logouttitle'        => "Shıg'ıw",
'loginpagetitle'     => 'Kiriw',
'yourname'           => 'Paydalanıwshı isimi:',
'yourpassword'       => 'Parol:',
'yourpasswordagain'  => "Paroldi qayta kiritin':",
'remembermypassword' => "Menin' kirgenimdi usı kompyuterde saqlap qal",
'yourdomainname'     => "Sizin' domen:",
'login'              => 'Kiriw',
'loginprompt'        => "{{SITENAME}} saytına kiriw ushın kukiler qosılg'an bolıwı kerek.",
'userlogin'          => 'Kiriw / akkaunt jaratıw',
'logout'             => "Shıg'ıw",
'userlogout'         => "Shıg'ıw",
'notloggedin'        => 'Kirilmegen',
'nologinlink'        => 'Akkaunt jarat',
'createaccount'      => 'Akkaunt jarat',
'gotaccountlink'     => 'Kiriw',
'createaccountmail'  => 'e-mail arqalı',
'badretype'          => 'Siz kiritken parol tuwra kelmedi.',
'userexists'         => "Siz kiritken paydalanıwshı isimi ba'nt. İltimas basqa isim kiritin'.",
'username'           => 'Paydalanıwshı isimi:',
'uid'                => 'Paydalanıwshı IDsı:',
'yourrealname'       => "Haqıyqıy isimin'iz:",
'yourlanguage'       => 'Til:',
'yournick'           => "Nik isimin'iz:",
'loginerror'         => 'Kiriwde qatelik',
'noname'             => 'Siz kiritken paydalanıwshı ismi qate.',
'loginsuccesstitle'  => "Kiriw tabıslı a'melge asırıldı",
'loginsuccess'       => "'''{{SITENAME}} saytına \"\$1\" paydalanıwshı ismi menen kirdin'iz.'''",
'nouserspecified'    => "Siz paydalanıwshı ismin ko'rsetpedin'iz.",
'wrongpassword'      => "Qate parol kiritlgen. Qaytadan kiritin'.",
'wrongpasswordempty' => "Parol kiritilmegen. Qaytadan ha'reket etin'.",
'mailmypassword'     => "Paroldi e-mailg'a jiberiw",
'mailerror'          => 'Xat jiberiwde qatelik juz berdi: $1',
'emailconfirmlink'   => "Elektron pochta adresin'izdi tastıyıqlan'",
'accountcreated'     => 'Akkaunt jaratıldı',
'accountcreatedtext' => '$1 paydalanıwshısına akkaunt jaratıldı.',
'loginlanguagelabel' => 'Til: $1',

# Password reset dialog
'resetpass_missing' => "Formada mag'lıwmat joq.",

# Edit page toolbar
'bold_sample'   => 'Yarım juwan tekst',
'bold_tip'      => 'Yarım juwan tekst',
'italic_sample' => 'Kursiv tekst',
'italic_tip'    => 'Kursiv tekst',
'link_sample'   => 'Burıwshı ataması',
'link_tip'      => 'İshki burıwshı',
'extlink_tip'   => "Sırtqı burıwshı (http:// baslaw kiritin')",
'math_sample'   => "Usı jerge formulanı jazın'",
'math_tip'      => 'Matematik formula (LaTeX)',
'media_tip'     => 'Media fayl burıwshısı',
'hr_tip'        => "Goriszont bag'ıtındag'ı sızıq (dım ko'p paydalanban')",

# Edit pages
'summary'                => 'Juwmaq',
'minoredit'              => "Bul kishkene o'zgertiw",
'savearticle'            => 'Betti saqla',
'preview'                => "Ko'rip shıg'ıw",
'showpreview'            => "Ko'rip shıg'ıw",
'showdiff'               => "O'zgerislerdi ko'rset",
'missingcommenttext'     => "Kommentariydi to'mende kiritin'.",
'summary-preview'        => "Juwmag'ın ko'rip shıg'ıw",
'blockedoriginalsource'  => "'''$1''' betinin' teksti
to'mende ko'rsetilgen:",
'whitelistedittitle'     => "O'zgertiw ushın sistemag'a kiriwin'iz kerek",
'whitelistreadtitle'     => "Oqıw ushın sistemag'a kiriwin'iz kerek",
'nosuchsectiontitle'     => "Bunday bo'lim joq",
'loginreqtitle'          => "Sistemag'a kiriw kerek",
'loginreqlink'           => 'Kiriw',
'accmailtitle'           => 'Parol jiberildi.',
'accmailtext'            => '"$1" paroli $2 g\'a jiberildi.',
'newarticle'             => '(Taza)',
'updated'                => "(Jan'alang'an)",
'editing'                => "$1 o'zgertilmekte",
'editinguser'            => "Paydalanıwshı <b>$1</b> o'zgertilmekte",
'editingsection'         => "$1 (bo'limi) o'zgertilmekte",
'editingcomment'         => "$1 (kommentariyi) o'zgertilmekte",
'yourtext'               => "Sizin' tekst",
'yourdiff'               => 'Parqlar',
'templatesused'          => "Bul bette qollanılg'an shablonlar:",
'templatesusedpreview'   => "Bul ko'rip shıg'ıw bette qollanılg'an shablonlar:",
'templatesusedsection'   => "Bul bo'limde qollanılg'an shablonlar:",
'template-protected'     => "(qorg'ang'an)",
'template-semiprotected' => "(yarım-qorg'ang'an)",
'nocreatetitle'          => 'Bet jaratıw sheklengen',
'permissionserrors'      => 'Ruxsatnamalar Qatelikleri',

# Account creation failure
'cantcreateaccounttitle' => 'Akkaunt jaratılmadı',

# History pages
'cur'          => "ha'zirgi",
'next'         => 'keyingi',
'last'         => "aqırg'ı",
'page_first'   => 'birinshi',
'page_last'    => "aqırg'ı",
'deletedrev'   => "[o'shirilgen]",
'histfirst'    => "En' aldıng'ısı",
'histlast'     => "En' aqırg'ısı",
'historysize'  => '({{PLURAL:$1|1 bayt|$1 bayt}})',
'historyempty' => '(bos)',

# Revision feed
'history-feed-item-nocomment' => "$2 waqtındag'ı $1", # user at time

# Revision deletion
'rev-delundel'     => "ko'rsetiw/jasırıw",
'revdelete-legend' => 'Sheklewlerdi belgilew:',

# Diffs
'lineno'   => '$1 qatar:',
'editundo' => 'qaytar',

# Search results
'prevn'        => "aldıng'ı $1",
'nextn'        => 'keyingi $1',
'viewprevnext' => "Ko'riw: ($1) ($2) ($3)",
'powersearch'  => 'İzlew',

# Preferences page
'prefsnologin'          => 'Kirilmegen',
'qbsettings-none'       => 'Hesh qanday',
'changepassword'        => "Paroldi o'zgertiw",
'skin'                  => "Sırtqı ko'rinis",
'math'                  => 'Formulalar',
'datetime'              => "Sa'ne ha'm waqıt",
'math_unknown_error'    => "belgisiz qa'telik",
'math_unknown_function' => 'belgisiz funktsiya',
'math_lexing_error'     => "leksikalıq qa'telik",
'math_syntax_error'     => "sintaksikalıq qa'telik",
'prefs-personal'        => 'Paydalanıwshı profaylı',
'prefs-misc'            => 'Basqa',
'saveprefs'             => 'Saqla',
'oldpassword'           => "Aldıng'ı parol:",
'newpassword'           => 'Taza parol:',
'retypenew'             => "Taza paroldi qayta kiritin':",
'textboxsize'           => "O'zgertiw",
'rows'                  => 'Qatarlar:',
'columns'               => "Bag'analar:",
'searchresultshead'     => 'İzlew',
'timezonelegend'        => 'Waqıt zonası',
'localtime'             => 'Jergilikli waqıt',
'servertime'            => "Serverdin' waqtı",
'files'                 => 'Fayllar',

# User rights
'userrights-user-editname' => "Paydalanıwshı ismin kiritin':",
'userrights-groupsmember'  => "Ag'zalıq toparı:",

# Groups
'group'            => 'Topar:',
'group-bot'        => 'Botlar',
'group-sysop'      => 'Administratorlar',
'group-bureaucrat' => 'Byurokratlar',
'group-all'        => "(ha'mmesi)",

'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Administrator',
'group-bureaucrat-member' => 'Byurokrat',

'grouppage-bot'        => '{{ns:project}}:Botlar',
'grouppage-sysop'      => '{{ns:project}}:Administratorlar',
'grouppage-bureaucrat' => '{{ns:project}}:Byurokratlar',

# User rights log
'rightsnone' => '(hesh qanday)',

# Recent changes
'nchanges'          => "{{PLURAL:$1|1 o'zgeris|$1 o'zgeris}}",
'rcnote'            => "To'mende $3 waqtındag'ı aqırg'ı {{PLURAL:$2|ku'ndegi|'''$2''' ku'ndegi}} {{PLURAL:$1|'''1''' o'zgeris bar|aqırg'ı '''$1''' o'zgeris bar}}.",
'rclistfrom'        => "$1 waqtınan baslap jan'a o'zgerisler ko'rset",
'rcshowhideminor'   => "Kishkene o'zgertiwlerdi $1",
'rcshowhidebots'    => 'Botlardı $1',
'rcshowhideliu'     => 'Kirgenlerdi $1',
'rcshowhideanons'   => 'Kirmegenlerdi $1',
'rcshowhidemine'    => "O'zgertiwlerimdi $1",
'rclinks'           => "Aqırg'ı $2 ku'ndegi aqırg'ı $1 o'zgeris ko'rset<br />$3",
'diff'              => 'parq',
'hist'              => 'tar.',
'hide'              => 'jasır',
'show'              => "ko'rset",
'minoreditletter'   => 'k',
'newpageletter'     => 'T',
'boteditletter'     => 'b',
'rc_categories_any' => "Ha'r qanday",

# Upload
'uploadnologin'     => 'Kirilmegen',
'filename'          => 'Fayl atı',
'filedesc'          => 'Juwmaq',
'fileuploadsummary' => 'Juwmaq:',
'savefile'          => 'Fayldı saqla',

'upload-proto-error' => 'Nadurıs protokol',
'upload-file-error'  => "İshki qa'telik",

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URL tabılmadı',

'upload_source_file' => " (sizin' kompyuterin'izdegi fayl)",

# Image list
'imagelist'             => 'Fayllar dizimi',
'ilsubmit'              => 'İzlew',
'byname'                => 'at boyınsha',
'bydate'                => "sa'ne boyınsha",
'bysize'                => "ha'jmi boyınsha",
'imgfile'               => 'fayl',
'filehist'              => 'Fayl tariyxı',
'filehist-deleteall'    => "ha'mmesin o'shir",
'filehist-deleteone'    => "usını o'shiriw",
'filehist-current'      => "ha'zirgi",
'filehist-datetime'     => "Sa'ne/Waqıt",
'filehist-user'         => 'Paydalanıwshı',
'filehist-filesize'     => "Fayldın' ha'jmi",
'filehist-comment'      => 'Kommentariy',
'imagelinks'            => 'Burıwshılar',
'imagelist_date'        => "Sa'ne",
'imagelist_name'        => 'Atama',
'imagelist_user'        => 'Paydalnıwshı',
'imagelist_size'        => "Ha'jim",
'imagelist_description' => 'Juwmaq',

# File reversion
'filerevert-comment' => 'Kommentariy:',

# File deletion
'filedelete'         => "$1 di o'shir",
'filedelete-legend'  => "Fayldı o'shiriw",
'filedelete-comment' => 'Kommentariy:',
'filedelete-submit'  => "O'shiriw",
'filedelete-success' => "'''$1''' o'shiriw.",
'filedelete-nofile'  => "'''$1''' bul saytta joq.",

# MIME search
'mimesearch' => 'MIME izlew',

# Unused templates
'unusedtemplates'    => "Paydalanılmag'an shablonlar",
'unusedtemplateswlh' => 'basqa burıwshılar',

# Statistics
'statistics'             => 'Statistika',
'sitestats'              => '{{SITENAME}} statistikası',
'userstats'              => 'Paydalanıwshı statistikası',
'statistics-mostpopular' => "En' ko'p ko'rilgen betler",

'disambiguations' => "Ko'p ma'nisli betler",

'brokenredirects-edit'   => "(o'zgertiw)",
'brokenredirects-delete' => "(o'shiriw)",

'withoutinterwiki' => 'Til burıwshılar joq betler',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|1 bayt|$1 bayt}}',
'nlinks'                  => '{{PLURAL:$1|1 burıwshı|$1 burıwshı}}',
'uncategorizedpages'      => "Kategoriyalanılmag'an betler",
'uncategorizedcategories' => "Kategoriyalanılmag'an kategoriyalar",
'uncategorizedimages'     => "Kategoriyalanılmag'an su'wretler",
'uncategorizedtemplates'  => "Kategoriyalanılmag'an shablonlar",
'unusedcategories'        => "Paydalanılmag'an kategoriyalar",
'unusedimages'            => "Paydalanılmag'an fayllar",
'wantedcategories'        => "Talap qılıng'an kategoriyalar",
'wantedpages'             => "Talap qılıng'an betler",
'allpages'                => "Ha'mme betler",
'shortpages'              => "En' qısqa betler",
'longpages'               => "En' uzın betler",
'protectedpages'          => "Qorg'ang'an betler",
'listusers'               => 'Paydalanıwshı dizimi',
'specialpages'            => 'Arnawlı betler',
'newpages'                => "En' taza betler",
'ancientpages'            => "En' ko'ne betler",
'move'                    => "Ko'shiriw",
'movethispage'            => "Bul betti ko'shiriw",

# Book sources
'booksources-search-legend' => 'Kitap haqqında informatsiya izlew',
'booksources-go'            => "O'tiw",

'categoriespagetext' => 'Wikide usı kategoriyalar bar.',
'data'               => "Mag'lıwmatlar",
'userrights'         => 'Paydalanıwshı huqıqların basqarıw',
'groups'             => 'Paydalanıwshı toparları',
'alphaindexline'     => '$1 — $2',
'version'            => 'Versiya',

# Special:Log
'specialloguserlabel'  => 'Paydalanıwshı:',
'speciallogtitlelabel' => 'Atama:',
'log-search-submit'    => "O'tiw",

# Special:Allpages
'nextpage'       => 'Keyingi bet ($1)',
'prevpage'       => "Aldıng'ı bet ($1)",
'allarticles'    => "Ha'mme betler",
'allpagesprev'   => "Aldıng'ı",
'allpagesnext'   => 'Keyingi',
'allpagessubmit' => "O'tiw",

# Special:Listusers
'listusers-submit'   => "Ko'rset",
'listusers-noresult' => 'Paydalanıwshı tabılmadı.',

# E-mail user
'emailuser'       => 'Xat jiberiw',
'emailpage'       => "Paydalanıwshıg'a e-mail jiberiw",
'defemailsubject' => '{{SITENAME}} e-mail',
'noemailtitle'    => 'E-mail adresi joq',
'emailfrom'       => 'Kimnen',
'emailto'         => 'Kimge',

# Watchlist
'watchlistfor'         => "('''$1''' ushın)",
'notanarticle'         => "Mag'lıwmat beti emes",
'wlshowlast'           => "Aqırg'ı $1 saatın, $2 ku'nin, $3 ko'rset",
'watchlist-show-bots'  => "Bot o'zgertiwlerin' ko'rset",
'watchlist-hide-bots'  => "Bot o'zgertiwlerin' jasır",
'watchlist-show-own'   => "O'zgertiwlerimdi ko'rset",
'watchlist-hide-own'   => "O'zgertiwlerimdi jasır",
'watchlist-show-minor' => "Kishkene o'zgertiwlerdi ko'rset",
'watchlist-hide-minor' => "Kishkene o'zgertiwlerdi jasır",

'enotif_newpagetext'           => 'Bul taza bet.',
'enotif_impersonal_salutation' => '{{SITENAME}} paydalanıwshısı',
'changed'                      => "o'zgertilgen",
'created'                      => "jaratılg'an",

# Delete/protect/revert
'deletepage'          => "Betti o'shiriw",
'confirm'             => 'Tastıyıqlaw',
'exblank'             => 'bet bos edi',
'confirmdelete'       => "O'shiriwdi tastıyıqlan'",
'deletesub'           => '("$1" o\'shirilmekte)',
'deletedtext'         => "\"\$1\" o'shirildi.
Aqırg'ı o'shirilgenlerdin' dizimin ko'riw ushin \$2 ni qaran'",
'deletedarticle'      => '"[[$1]]" o\'shirildi',
'dellogpage'          => "O'shirilgenler dizimi",
'dellogpagetext'      => "To'mende en' aqırg'ı o'shirilgenlerdin' dizimi keltirilgen",
'deletionlog'         => "o'shirilgenler dizimi",
'deletecomment'       => "O'shiriwdin' sebebi",
'protectedarticle'    => '"[[$1]]" qorg\'adı',
'protectcomment'      => 'Kommentariy:',
'protect-level-sysop' => 'Tek administratorlar',
'restriction-type'    => 'Ruxsatnama:',
'pagesize'            => '(bayt)',

# Restrictions (nouns)
'restriction-edit' => "O'zgertiw",
'restriction-move' => "Ko'shiriw",

# Restriction levels
'restriction-level-autoconfirmed' => "yarım-qorg'ang'an",

# Undelete
'undelete'               => "O'shirilgen betlerdi ko'riw",
'undeletepage'           => "O'shirilgen betlerdi ko'riw ha'm qayta tiklew",
'viewdeletedpage'        => "O'shirilgen betlerdi ko'riw",
'undeletebtn'            => 'Qayta tiklew',
'undeletecomment'        => 'Kommentariy:',
'undeletedarticle'       => '"[[$1]]" qayta tiklendi',
'undelete-search-box'    => "O'shirilgen betlerdi izlew",
'undelete-search-submit' => 'İzlew',

# Contributions
'contribsub2' => '$1 ushın ($2)',
'uctop'       => " (aqırg'ı)",

'sp-contributions-newest'      => "En' taza",
'sp-contributions-oldest'      => "En' ko'ne",
'sp-contributions-newbies-sub' => 'Taza akkauntlar ushın',
'sp-contributions-username'    => 'IP Adres yamasa paydalanıwshı atı:',
'sp-contributions-submit'      => 'İzlew',

# What links here
'whatlinkshere-page'  => 'Bet:',
'linklistsub'         => '(Burıwshılar dizimi)',
'whatlinkshere-prev'  => "{{PLURAL:$1|aldıng'ı|aldıng'ı $1}}",
'whatlinkshere-next'  => '{{PLURAL:$1|keyingi|keyingi $1}}',
'whatlinkshere-links' => '← burıwshılar',

# Block/unblock
'ipaddress'            => 'IP Adres:',
'ipadressorusername'   => 'IP Adres yamasa paydalanıwshı atı:',
'ipbreason'            => 'Sebep:',
'ipbreasonotherlist'   => 'Basqa sebep',
'ipbother'             => 'Basqa waqıt:',
'ipboptions'           => "2 saat:2 hours,1 ku'n:1 day,3 ku'n:3 days,1 ha'pte:1 week,2 h'apte:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 jil:1 year,sheksiz:infinite",
'ipbotheroption'       => 'basqa',
'ipbotherreason'       => 'Basqa/qosımsha sebep:',
'ipblocklist-username' => 'Paydalanıwshı atı yamasa IP adres:',
'ipblocklist-submit'   => 'İzlew',

# Move page
'movepage'                => "Betti ko'shiriw",
'movearticle'             => "Betti ko'shiriw:",
'newtitle'                => 'Taza atama:',
'movepagebtn'             => "Betti ko'shiriw",
'pagemovedsub'            => "Tabıslı ko'shirildi",
'movepage-moved'          => "<big>'''\"\$1\" beti \"\$2\" degenge ko'shirildi'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movedto'                 => "betke ko'shirildi",
'1movedto2'               => "[[$1]] beti [[$2]] degenge ko'shirildi",
'movelogpagetext'         => "To'mende ko'shirilgen betlerdin' dizimi keltirilgen.",
'movereason'              => 'Sebep:',
'delete_and_move_confirm' => "Awa, bul betti o'shiriw",

# Namespace 8 related
'allmessages'         => 'Sistema xabarları',
'allmessagesname'     => 'Atama',
'allmessagesmodified' => "Tek o'zgertilgendi ko'rset",

# Thumbnails
'thumbnail-more' => "U'lkeytiw",
'filemissing'    => 'Fayl tabılmadı',

# Tooltip help for the actions
'tooltip-pt-userpage'        => "Menin' paydalanıwshı betim",
'tooltip-pt-mytalk'          => "Menin' sa'wbetim",
'tooltip-pt-anontalk'        => "Bul IP adresten o'zgertiwler haqqında diskussiya",
'tooltip-pt-logout'          => "Shıg'ıw",
'tooltip-ca-talk'            => "Mag'lıwmat beti haqqında diskussiya",
'tooltip-ca-protect'         => "Bul betti qorg'aw",
'tooltip-ca-delete'          => "Bul betti o'shiriw",
'tooltip-ca-move'            => "Bul betti ko'shiriw",
'tooltip-search'             => '{{SITENAME}} saytınan izlew',
'tooltip-p-logo'             => 'Bas bet',
'tooltip-n-mainpage'         => "Bas betke o'tiw",
'tooltip-t-emailuser'        => "Usı paydalanıwshıg'a e-mail jiberiw",
'tooltip-t-specialpages'     => "Ha'mme arnawlı bet dizimi",
'tooltip-ca-nstab-main'      => "Mag'lıwmat betin ko'riw",
'tooltip-ca-nstab-user'      => "Paydalanıwshı betin ko'riw",
'tooltip-ca-nstab-media'     => "Media betin ko'riw",
'tooltip-ca-nstab-project'   => "Proekt betin ko'riw",
'tooltip-ca-nstab-image'     => "Su'wret betin ko'riw",
'tooltip-ca-nstab-mediawiki' => "Sistema xabarın ko'riw",
'tooltip-ca-nstab-template'  => "Shablondı ko'riw",
'tooltip-ca-nstab-help'      => "Anıqlama betin ko'riw",
'tooltip-ca-nstab-category'  => "Kategoriya betin ko'riw",
'tooltip-save'               => "O'zgertiwlerin'izdi saqla",

# Attribution
'others'      => 'basqalar',
'creditspage' => 'Bet avtorları',

# Spam protection
'subcategorycount'       => 'Bul kategoriyasinda {{PLURAL:$1|bir podkategoriya bar|$1 podkategoriya bar}}.',
'categoryarticlecount'   => 'Bul kategoriyada {{PLURAL:$1|bir bet bar|$1 bet bar}}.',
'listingcontinuesabbrev' => 'dawamı',

# Browsing diffs
'previousdiff' => "← Aldıng'ı parq",
'nextdiff'     => 'Keyingi parq →',

# Media information
'show-big-image-thumb' => "<small>Bul ko'rip shıg'ıw su'wret ha'jmi: $1 × $2 piksel</small>",

# Special:Newimages
'showhidebots' => '(botlardı $1)',

# Metadata
'metadata'          => "Metamag'lıwmat",
'metadata-expand'   => "Qosımsha mag'lıwmatlardı ko'rset",
'metadata-collapse' => "Qosımsha mag'lıwmatlardi jasır",

# EXIF tags
'exif-imagewidth'       => 'Yeni:',
'exif-imagelength'      => "Uzunlıg'ı",
'exif-imagedescription' => "Su'wret ataması",
'exif-artist'           => 'Avtor',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => "ha'mmesin",
'imagelistall'     => "ha'mme",
'watchlistall2'    => "ha'mmesin",
'namespacesall'    => "ha'mmesi",
'monthsall'        => "ha'mme",

# E-mail address confirmation
'confirmemail'          => 'E-mail adresin tastıyıqlaw',
'confirmemail_success'  => "Sizin' e-mail adresin'iz tastıyıqlandı, endi wikige kiriwin'iz mu'mkin.",
'confirmemail_loggedin' => "Sizin' e-mail adresin'iz endi tastıyıqlandı.",

# Delete conflict
'recreate' => 'Qaytadan jaratıw',

# action=purge
'confirm_purge_button' => 'OK',

# Multipage image navigation
'imgmultipageprev' => "← aldıng'ı bet",
'imgmultipagenext' => 'keyingi bet →',
'imgmultigo'       => "O'tiw!",
'imgmultigotopre'  => "Betke o'tiw",

# Table pager
'table_pager_next'         => 'Keyingi bet',
'table_pager_prev'         => "Aldıng'ı bet",
'table_pager_first'        => 'Birinshi bet',
'table_pager_last'         => "Aqırg'ı bet",
'table_pager_limit_submit' => "O'tiw",

# Auto-summaries
'autosumm-new' => 'Taza bet: $1',

# Watchlist editor
'watchlistedit-raw-titles' => 'Atamalar:',

);
