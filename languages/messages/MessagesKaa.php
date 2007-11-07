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

# Bits of text used by many pages
'categories'      => 'Kategoriyalar',
'pagecategories'  => '{{PLURAL:$1|Kategoriya|Kategoriyalar}}',
'category_header' => '"$1" kategoriyasindag\'ı maqalalar',
'subcategories'   => 'Podkategoriyalar',

'mainpagetext' => "<big>'''MediaWiki tabıslı ornatıldı.'''</big>",

'about'         => "Tu'sindirme",
'article'       => 'Maqala',
'newwindow'     => "(jan'a aynada)",
'cancel'        => 'Biykar',
'qbfind'        => 'İzlew',
'qbbrowse'      => "Ko'riw",
'qbedit'        => "O'zgertiw",
'qbpageoptions' => 'This page',
'qbpageinfo'    => 'Maqala haqqında',
'qbmyoptions'   => "Sizin' sazlawlarınız",
'moredotdotdot' => "Ja'ne...",
'mypage'        => 'Shaxsıy bet',
'mytalk'        => "Menin' sa'wbetim",
'anontalk'      => "Usı IPg'a sa'wbet",
'navigation'    => 'Navigatsiya',

# Metadata in edit box
'metadata_help' => "Metamag'lıwmat:",

'errorpagetitle' => 'Qatelik',
'returnto'       => '$1 betine qaytıw.',
'tagline'        => "{{SITENAME}} saytınan mag'lıwmat",
'help'           => "Ja'rdem",
'search'         => 'İzlew',
'searchbutton'   => 'İzlew',
'go'             => "O'tiw",
'searcharticle'  => "O'tiw",
'history'        => 'Tariyx',
'history_short'  => 'Tariyx',
'info_short'     => "Mag'lıwmat",
'edit'           => "O'zgertiw",
'editthispage'   => "Usı betti o'zgertiw",
'delete'         => "O'shiriw",
'deletethispage' => "Usı betti o'shiriw",
'newpage'        => 'Taza maqala',
'postcomment'    => 'Kommentariy beriw',
'talk'           => 'Diskussiya',
'toolbox'        => "A'sbaplar",
'otherlanguages' => 'Basqa tillerde',
'jumptosearch'   => 'İzlew',

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

'newmessagesdifflink' => "aqırg'ı o'zgeris",
'toc'                 => 'Mazmunı',
'showtoc'             => "ko'rset",
'hidetoc'             => 'jasır',

# Short words for each namespace, by default used in the 'article' tab in monobook
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
'gotaccountlink'     => 'Kiriw',
'createaccountmail'  => 'e-mail arqalı',
'badretype'          => 'Siz kiritken parol tuwra kelmedi.',
'userexists'         => "Siz kiritken paydalanıwshı isimi ba'nt. İltimas basqa isim kiritin'.",
'username'           => 'Paydalanıwshı isimi:',
'uid'                => 'Paydalanıwshı IDsı:',
'yourrealname'       => "Haqıyqıy isimin'iz:",
'yourlanguage'       => 'Til:',
'yournick'           => "Nik isimin'iz:",
'loginsuccesstitle'  => "Kiriw tabıslı a'melge asırıldı",
'loginsuccess'       => "'''{{SITENAME}}g'a \"\$1\" paydalanıwshı atı menen kirdin'iz.'''",
'emailconfirmlink'   => "Elektron pochta adresin'izdi tastıyıqlan'",
'accountcreated'     => 'Akkaunt jaratıldı',
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
'savearticle'           => 'Betti saqlaw',
'preview'               => "Ko'rip shıg'ıw",
'showpreview'           => "Ko'rip shıg'ıw",
'showdiff'              => "O'zgerislerdi ko'rsetiw",
'blockedoriginalsource' => "'''$1''' betinin' teksti
to'mende ko'rsetilgen:",
'nosuchsectiontitle'    => "Bunday bo'lim joq",
'newarticle'            => '(Taza)',
'yourtext'              => "Sizin' tekst",
'templatesused'         => "Bul bette qollanılg'an shablonlar:",
'templatesusedsection'  => "Bul bo'limde qollanılg'an shablonlar:",

# History pages
'next'         => 'keyingi',
'last'         => "aqırg'ı",
'page_first'   => 'birinshi',
'page_last'    => "aqırg'ı",
'historyempty' => '(bos)',

# Revision deletion
'rev-delundel'     => "ko'rsetiw/jasırıw",
'revdelete-legend' => 'Sheklewlerdi belgilew:',

# Diffs
'lineno' => '$1 qatar:',

# Search results
'prevn' => "aldıng'ı $1",
'nextn' => 'keyingi $1',

# Preferences page
'changepassword' => "Paroldi o'zgertiw",
'skin'           => "Sırtqı ko'rinis",
'math'           => 'Formulalar',
'oldpassword'    => "Aldıng'ı parol:",
'newpassword'    => 'Taza parol:',
'retypenew'      => "Taza paroldi qayta kiritin':",
'rows'           => 'Qatarlar:',
'columns'        => "Bag'analar:",
'files'          => 'Fayllar',

# Groups
'group-bot'        => 'Botlar',
'group-sysop'      => 'Administratorlar',
'group-bureaucrat' => 'Byurokratlar',
'group-all'        => "(ha'mmesi)",

'group-bot-member' => 'Bot',

);
