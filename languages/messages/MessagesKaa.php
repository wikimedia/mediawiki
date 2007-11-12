<?php
/** Kara-Kalpak (Qaraqalpaqsha)
 *
 * @addtogroup Language
 *
 * @author Atabek
 * @author Jiemurat
 */

$linkTrail = '/^([a-zʻ`]+)(.*)$/sDu';

$messages = array(
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
'sun'           => 'Eksh',
'mon'           => 'Dsh',
'tue'           => 'Ssh',
'wed'           => 'Srsh',
'thu'           => 'Psh',
'fri'           => 'Jm',
'sat'           => 'Sh',
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
'categories'      => 'Kategoriyalar',
'pagecategories'  => '{{PLURAL:$1|Kategoriya|Kategoriyalar}}',
'category_header' => '"$1" kategoriyasindag\'ı betler',
'subcategories'   => 'Podkategoriyalar',

'mainpagetext' => "<big>'''MediaWiki tabıslı ornatıldı.'''</big>",

'about'         => "Tu'sindirme",
'article'       => "Mag'lıwmat beti",
'newwindow'     => "(jan'a aynada)",
'cancel'        => 'Biykar',
'qbfind'        => 'İzlew',
'qbbrowse'      => "Ko'riw",
'qbedit'        => "O'zgertiw",
'qbpageoptions' => 'Usı bet',
'qbpageinfo'    => 'Kontekst',
'qbmyoptions'   => "Sizin' sazlawlarınız",
'moredotdotdot' => "Ja'ne...",
'mypage'        => 'Shaxsıy bet',
'mytalk'        => "Menin' sa'wbetim",
'anontalk'      => "Usı IPg'a sa'wbet",
'navigation'    => 'Navigatsiya',

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
'newpage'          => 'Taza bet',
'talkpagelinktext' => "Sa'wbet",
'postcomment'      => 'Kommentariy beriw',
'talk'             => 'Diskussiya',
'toolbox'          => "A'sbaplar",
'otherlanguages'   => 'Basqa tillerde',
'lastmodifiedat'   => "Bul bettin' aqırg'ı ma'rte o'zgertilgen waqtı: $2, $1.", # $1 date, $2 time
'viewcount'        => "Bul bet {{PLURAL:$1|bir ma'rte|$1 ma'rte}} ko'rip shıg'ılg'an.",
'jumptosearch'     => 'İzlew',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} haqqında',
'copyrightpagename' => "{{SITENAME}} proektinin' avtorlıq huquqları",
'copyrightpage'     => '{{ns:project}}:Avtorlıq huquqı',
'currentevents'     => "Ha'zirgi ha'diyseler",
'currentevents-url' => "{{ns:project}}:Ha'zirgi ha'diyseler",
'mainpage'          => 'Bas bet',
'policy-url'        => "{{ns:project}}:Qag'ıydalar",
'portal'            => "Ja'miyet portalı",
'portal-url'        => "{{ns:project}}:Ja'miyet Portalı",
'privacy'           => 'Konfidentsiallıq siyasatı',
'privacypage'       => '{{ns:project}}:Konfidentsiallıq siyasatı',

'versionrequired' => "MediaWikidin' $1 versiyası kerek",

'newmessageslink'     => "jan'a xabarlar",
'newmessagesdifflink' => "aqırg'ı o'zgeris",
'editsection'         => "o'zgertiw",
'editold'             => "o'zgertiw",
'toc'                 => 'Mazmunı',
'showtoc'             => "ko'rset",
'hidetoc'             => 'jasır',

# Short words for each namespace, by default used in the 'page' tab in monobook
'nstab-main'      => 'Maqala',
'nstab-user'      => 'Paydalanıwshı beti',
'nstab-project'   => 'Proekt beti',
'nstab-image'     => 'Fayl',
'nstab-mediawiki' => 'Xabar',
'nstab-template'  => 'Shablon',
'nstab-help'      => "Ja'rdem beti",
'nstab-category'  => 'Kategoriya',

# General errors
'error'              => "Qa'telik",
'databaseerror'      => "Mag'lıwmatlar bazası qa'tesi",
'internalerror'      => "İshki qa'telik",
'internalerror_info' => "İshki qa'telik: $1",

# Login and logout pages
'logouttitle'        => "Shıg'ıw",
'loginpagetitle'     => 'Kiriw',
'yourname'           => 'Paydalanıwshı ismi:',
'yourpassword'       => 'Parol:',
'yourpasswordagain'  => "Paroldi qayta kiritin':",
'remembermypassword' => "Menin' loginimdi usı kompyuterde saqlap qal",
'yourdomainname'     => "Sizin' domen:",
'login'              => 'Kiriw',
'loginprompt'        => "{{SITENAME}}g'a kiriw ushın kukiler qosılg'an bolıwı kerek.",
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
'loginsuccess'       => "'''{{SITENAME}}g'a \"\$1\" paydalanıwshı atı menen kirdin'iz.'''",
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
'math_sample'   => "Usı jerge formulanı jazın'",
'math_tip'      => 'Matematik formula (LaTeX)',
'hr_tip'        => "Goriszont bag'ıtındag'ı sızıq (dım ko'p paydalanban')",

# Edit pages
'summary'                => 'Juwmaq',
'savearticle'            => 'Betti saqlaw',
'preview'                => "Ko'rip shıg'ıw",
'showpreview'            => "Ko'rip shıg'ıw",
'showdiff'               => "O'zgerislerdi ko'rsetiw",
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
'yourtext'               => "Sizin' tekst",
'yourdiff'               => 'Parqlar',
'templatesused'          => "Bul bette qollanılg'an shablonlar:",
'templatesusedsection'   => "Bul bo'limde qollanılg'an shablonlar:",
'template-protected'     => "(qorg'ang'an)",
'template-semiprotected' => "(yarım-qorg'ang'an)",
'nocreatetitle'          => 'Bet jaratıw sheklengen',
'permissionserrors'      => 'Ruxsatnamalar Qatelikleri',

# Account creation failure
'cantcreateaccounttitle' => 'Akkaunt jaratıw',

# History pages
'next'         => 'keyingi',
'last'         => "aqırg'ı",
'page_first'   => 'birinshi',
'page_last'    => "aqırg'ı",
'deletedrev'   => "[o'shirilgen]",
'histfirst'    => "En' aldıng'ısı",
'histlast'     => "En' aqırg'ısı",
'historyempty' => '(bos)',

# Revision deletion
'rev-delundel'     => "ko'rsetiw/jasırıw",
'revdelete-legend' => 'Sheklewlerdi belgilew:',

# Diffs
'lineno'   => '$1 qatar:',
'editundo' => 'qaytar',

# Search results
'prevn'       => "aldıng'ı $1",
'nextn'       => 'keyingi $1',
'powersearch' => 'İzlew',

# Preferences page
'prefsnologin'          => 'Kirilmegen',
'changepassword'        => "Paroldi o'zgertiw",
'skin'                  => "Sırtqı ko'rinis",
'math'                  => 'Formulalar',
'datetime'              => "Sa'ne ha'm waqıt",
'math_unknown_error'    => "belgisiz qa'telik",
'math_unknown_function' => 'belgisiz funktsiya',
'math_lexing_error'     => "leksikalıq qa'telik",
'math_syntax_error'     => "sintaksikalıq qa'telik",
'prefs-personal'        => 'Paydalanıwshı profaylı',
'saveprefs'             => 'Saqlaw',
'oldpassword'           => "Aldıng'ı parol:",
'newpassword'           => 'Taza parol:',
'retypenew'             => "Taza paroldi qayta kiritin':",
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
'rc_categories_any' => "Ha'r qanday",

# Upload
'uploadnologin'     => 'Kirilmegen',
'filename'          => 'Fayl atı',
'filedesc'          => 'Juwmaq',
'fileuploadsummary' => 'Juwmaq:',
'savefile'          => 'Fayldı saqlaw',

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
'filehist-deleteall'    => "ha'mmesin o'shir",
'filehist-deleteone'    => "usını o'shiriw",
'filehist-current'      => "ha'zirgi",
'filehist-datetime'     => "Sa'ne/Waqıt",
'filehist-user'         => 'Paydalanıwshı',
'filehist-filesize'     => "Fayldın' ha'jmi",
'filehist-comment'      => 'Kommentariy',
'imagelinks'            => 'Burıwshılar',
'imagelist_date'        => "Sa'ne",
'imagelist_name'        => 'İsim',
'imagelist_user'        => 'Paydalnıwshı',
'imagelist_size'        => "Ha'jim",
'imagelist_description' => 'Tusindirme',

# File reversion
'filerevert-comment' => 'Kommentariy:',

# File deletion
'filedelete'         => "$1 di o'shir",
'filedelete-legend'  => "Fayldı o'shiriw",
'filedelete-comment' => 'Kommentariy:',
'filedelete-submit'  => "O'shiriw",
'filedelete-success' => "'''$1''' o'shiriw.",
'filedelete-nofile'  => "'''$1''' bul saytta joq.",

# Unused templates
'unusedtemplateswlh' => 'basqa burıwshılar',

# Statistics
'statistics'             => 'Statistika',
'sitestats'              => '{{SITENAME}} statistikası',
'userstats'              => 'Paydalanıwshı statistikası',
'statistics-mostpopular' => "En' ko'p ko'rilgen betler",

'disambiguations' => "Ko'p ma'nisli terminlerdi sa'wlelendiriwshi betlet",

'brokenredirects-edit'   => "(o'zgertiw)",
'brokenredirects-delete' => "(o'shiriw)",

# Miscellaneous special pages
'unusedcategories' => "Paydalanılmag'an kategoriyalar",
'unusedimages'     => "Paydalanılmag'an fayllar",
'wantedcategories' => "Talap qılıng'an kategoriyalar",
'wantedpages'      => "Talap qılıng'an betler",
'allpages'         => "Ha'mme betler",
'shortpages'       => 'Qısqa maqalalar',
'longpages'        => 'Uzın maqalalar',
'listusers'        => 'Paydalanıwshı dizimi',
'newpages'         => 'Taza maqalalar',
'move'             => "Ko'shiriw",
'movethispage'     => "Bul betti ko'shiriw",

# Book sources
'booksources-search-legend' => 'Kitap haqqında informatsiya izlew',
'booksources-go'            => "O'tiw",

'categoriespagetext' => 'Wikide usı kategoriyalar bar.',
'data'               => "Mag'lıwmatlar",
'userrights'         => 'Paydalanıwshı huqıqların basqarıw',
'groups'             => 'Paydalanıwshı toparları',
'alphaindexline'     => "$1 ni $2 g'a",
'version'            => 'Versiya',

# Special:Log
'specialloguserlabel'  => 'Paydalanıwshı:',
'speciallogtitlelabel' => 'Atama:',
'log-search-submit'    => "O'tiw",

# Special:Allpages
'nextpage'       => 'Keyingi bet ($1)',
'prevpage'       => "Aldıng'ı bet ($1)",
'allarticles'    => "Ha'mme maqalalar",
'allpagesprev'   => "Aldıng'ı",
'allpagesnext'   => 'Keyingi',
'allpagessubmit' => "O'tiw",

# Special:Listusers
'listusers-submit'   => "Ko'rsetiw",
'listusers-noresult' => 'Paydalanıwshı tabılmadı.',

# E-mail user
'emailuser'       => "Usı paydalanıwshıg'a e-mail jiberiw",
'emailpage'       => "Paydalanıwshıg'a e-mail jiberiw",
'defemailsubject' => '{{SITENAME}} e-mail',
'noemailtitle'    => 'E-mail adresi joq',
'emailfrom'       => 'Kimnen',
'emailto'         => 'Kimge',

# Watchlist
'notanarticle' => 'Maqala emes',

'enotif_newpagetext'           => 'Bul taza bet.',
'enotif_impersonal_salutation' => '{{SITENAME}} paydalanıwshısı',

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
'protect-level-sysop' => 'Tek administratorlar',
'pagesize'            => '(bayt)',

# Undelete
'undelete'               => "O'shirilgen betlerdi ko'riw",
'undeletepage'           => "O'shirilgen betlerdi ko'riw ha'm qayta tiklew",
'viewdeletedpage'        => "O'shirilgen betlerdi ko'riw",
'undeletebtn'            => 'Qayta tiklew',
'undeletedarticle'       => '"[[$1]]" qayta tiklendi',
'undelete-search-box'    => "O'shirilgen betlerdi izlew",
'undelete-search-submit' => 'İzlew',

# Contributions
'uctop' => " (aqırg'ı)",

'sp-contributions-username' => 'IP Adres yamasa paydalanıwshı atı:',
'sp-contributions-submit'   => 'İzlew',

# Block/unblock
'ipaddress'            => 'IP Adres:',
'ipadressorusername'   => 'IP Adres yamasa paydalanıwshı atı:',
'ipbreason'            => 'Sebep:',
'ipbreasonotherlist'   => 'Basqa sebep',
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
'1movedto2'               => "[[$1]] [[$2]]ge ko'shirildi",
'movelogpagetext'         => "To'mende ko'shirilgen betlerdin' dizimi keltirilgen.",
'movereason'              => 'Sebep:',
'delete_and_move_confirm' => "Awa, bul betti o'shiriw",

# Thumbnails
'thumbnail-more' => "U'lkeytiw",
'filemissing'    => 'Fayl tabılmadı',

# Tooltip help for the actions
'tooltip-pt-userpage'       => "Menin' paydalanıwshı betim",
'tooltip-pt-logout'         => "Shıg'ıw",
'tooltip-ca-delete'         => "Bul betti o'shiriw",
'tooltip-ca-move'           => "Bul betti ko'shiriw",
'tooltip-p-logo'            => 'Bas bet',
'tooltip-n-mainpage'        => "Bas betke o'tiw",
'tooltip-ca-nstab-user'     => "Paydalanıwshı betin ko'riw",
'tooltip-ca-nstab-media'    => "Media betin ko'riw",
'tooltip-ca-nstab-project'  => "Proekt betin ko'riw",
'tooltip-ca-nstab-image'    => "Su'wret betin ko'riw",
'tooltip-ca-nstab-template' => "Shablondı ko'riw",
'tooltip-ca-nstab-help'     => "Ja'rdem betin ko'riw",
'tooltip-ca-nstab-category' => "Kategoriya betin ko'riw",

# Spam protection
'listingcontinuesabbrev' => 'dawamı',

# Metadata
'metadata'          => "Metamag'lıwmat",
'metadata-expand'   => "Qosımsha mag'lıwmatlardı ko'rset",
'metadata-collapse' => "Qosımsha mag'lıwmatlardi jasır",

# EXIF tags
'exif-imagewidth'       => 'Yeni:',
'exif-imagelength'      => "Uzunlıg'ı",
'exif-imagedescription' => "Su'wret ataması",
'exif-artist'           => 'Avtor',

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
'table_pager_next'  => 'Keyingi bet',
'table_pager_prev'  => "Aldıng'ı bet",
'table_pager_first' => 'Birinshi bet',
'table_pager_last'  => "Aqırg'ı bet",

# Auto-summaries
'autosumm-new' => 'Taza bet: $1',

);
