<?php
/** Achinese (Acèh)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Abi Azkia
 * @author Andri.h
 * @author Fadli Idris
 * @author Meno25
 * @author Si Gam Acèh
 */

$fallback = 'id';

$namespaceNames = array(
	NS_MEDIA            => 'Alat',
	NS_SPECIAL          => 'Kusuih',
	NS_TALK             => 'Marit',
	NS_USER             => 'Ureuëng_Nguy',
	NS_USER_TALK        => 'Marit_Ureuëng_Nguy',
	NS_PROJECT_TALK     => 'Marit_$1',
	NS_FILE             => 'Beureukaih',
	NS_FILE_TALK        => 'Marit_Beureukaih',
	NS_MEDIAWIKI        => 'AlatWiki',
	NS_MEDIAWIKI_TALK   => 'Marit_AlatWiki',
	NS_TEMPLATE         => 'Pola',
	NS_TEMPLATE_TALK    => 'Marit_Pola',
	NS_HELP             => 'Beunantu',
	NS_HELP_TALK        => 'Marit_Beunantu',
	NS_CATEGORY         => 'Kawan',
	NS_CATEGORY_TALK    => 'Marit_Kawan',
);

$namespaceAliases = array(
	'Istimewa'              => NS_SPECIAL,
	'Pembicaraan'           => NS_TALK,
	'Pengguna'              => NS_USER,
	'Pembicaraan_Pengguna'  => NS_USER_TALK,
	'Pembicaraan_$1'        => NS_PROJECT_TALK,
	'Berkas'                => NS_FILE,
	'Pembicaraan_Berkas'    => NS_FILE_TALK,
	'Pembicaraan_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Templat'               => NS_TEMPLATE,
	'Pembicaraan_Templat'   => NS_TEMPLATE_TALK,
	'Bantuan'               => NS_HELP,
	'Pembicaraan_Bantuan'   => NS_HELP_TALK,
	'Kategori'              => NS_CATEGORY,
	'Pembicaraan_Kategori'  => NS_CATEGORY_TALK,
	'Gambar_Pembicaraan'    => NS_FILE_TALK,
	'MediaWiki_Pembicaraan' => NS_MEDIAWIKI_TALK,
	'Templat_Pembicaraan'   => NS_TEMPLATE_TALK,
	'Bantuan_Pembicaraan'   => NS_HELP_TALK,
	'Kategori_Pembicaraan'  => NS_CATEGORY_TALK,
	'Gambar'                => NS_FILE,
	'Pembicaraan_Gambar'    => NS_FILE_TALK,
	'Bicara'                => NS_TALK,
	'Bicara_Pengguna'       => NS_USER_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Peuninah ganda' ),
	'BrokenRedirects'           => array( 'Peuninah reuloh' ),
	'Disambiguations'           => array( 'Hana jeulaih' ),
	'Userlogin'                 => array( 'Tamong log' ),
	'Userlogout'                => array( 'Teubiet log' ),
	'CreateAccount'             => array( 'Peugot nan' ),
	'Preferences'               => array( 'Geunalak' ),
	'Watchlist'                 => array( 'Dapeuta kalon' ),
	'Recentchanges'             => array( 'Neuubah baro' ),
	'Upload'                    => array( 'Pasoe' ),
	'Listfiles'                 => array( 'Dapeuta beureukaih' ),
	'Newimages'                 => array( 'Beureukaih baro' ),
	'Listusers'                 => array( 'Dapeuta ureueng nguy' ),
	'Listgrouprights'           => array( 'Dapeuta khut (hak) kawan' ),
	'Statistics'                => array( 'Keunira' ),
	'Randompage'                => array( 'On beurangkari' ),
	'Lonelypages'               => array( 'On hana soe po' ),
	'Uncategorizedpages'        => array( 'On hana roh lam kawan' ),
	'Uncategorizedcategories'   => array( 'Kawan hana roh lam kawan' ),
	'Uncategorizedimages'       => array( 'Beureukaih hana roh lam kawan' ),
	'Uncategorizedtemplates'    => array( 'Templat hana roh lam kawan' ),
	'Unusedcategories'          => array( 'Kawan soh' ),
	'Unusedimages'              => array( 'Beureukaih hana teunguy' ),
	'Wantedpages'               => array( 'On nyang geuh\'eut' ),
	'Wantedcategories'          => array( 'Kawan nyang geuh\'eut' ),
	'Wantedfiles'               => array( 'Beureukaih nyang geuh\'eut' ),
	'Wantedtemplates'           => array( 'Templat nyang geuh\'eut' ),
	'Mostlinked'                => array( 'On nyang paleng le geunguy' ),
	'Mostlinkedcategories'      => array( 'Kawan nyang paleng le geunguy' ),
	'Mostlinkedtemplates'       => array( 'Templat nyang paleng le geunguy' ),
	'Mostimages'                => array( 'Beureukaih nyang paleng le geunguy' ),
	'Mostcategories'            => array( 'Kawan paleng le' ),
	'Mostrevisions'             => array( 'Neuubah paleng le' ),
	'Fewestrevisions'           => array( 'Neuubah paleng dit' ),
	'Shortpages'                => array( 'On paneuek' ),
	'Longpages'                 => array( 'On panyang' ),
	'Newpages'                  => array( 'On baro' ),
	'Ancientpages'              => array( 'Teunuleh trep' ),
	'Deadendpages'              => array( 'On mate' ),
	'Protectedpages'            => array( 'On nyang geupeulindong' ),
	'Protectedtitles'           => array( 'Nan nyang geupeulindong' ),
	'Allpages'                  => array( 'Dapeuta on' ),
	'Prefixindex'               => array( 'Dapeuta neuaway' ),
	'Ipblocklist'               => array( 'Dapeuta neutheun' ),
	'Specialpages'              => array( 'On khusoih' ),
	'Contributions'             => array( 'Peuneugot ureueng nguy' ),
	'Emailuser'                 => array( 'Surat-e ureueng nguy' ),
	'Confirmemail'              => array( 'Peunyo surat-e' ),
	'Whatlinkshere'             => array( 'Hubong gisa' ),
	'Recentchangeslinked'       => array( 'Neuubah meuhubong' ),
	'Movepage'                  => array( 'Peupinah on' ),
	'Blockme'                   => array( 'Theun lon' ),
	'Booksources'               => array( 'Ne kitab' ),
	'Categories'                => array( 'Dapeuta kawan' ),
	'Export'                    => array( 'Peuteubiet' ),
	'Version'                   => array( 'Seunalen' ),
	'Allmessages'               => array( 'MandumPeusan' ),
	'Log'                       => array( 'Ceunatat' ),
	'Blockip'                   => array( 'Theun ureueng nguy' ),
	'Undelete'                  => array( 'Peubateue sampoh' ),
	'Import'                    => array( 'Peutamong' ),
	'Lockdb'                    => array( 'Gunci basis data' ),
	'Unlockdb'                  => array( 'Peuhah gunci basis data' ),
	'Userrights'                => array( 'Khut (hak) ureueng nguy' ),
	'MIMEsearch'                => array( 'Mita MIME' ),
	'FileDuplicateSearch'       => array( 'Mita beureukaih saban' ),
	'Unwatchedpages'            => array( 'On hana soe kalon' ),
	'Listredirects'             => array( 'Dapeuta peuninah' ),
	'Revisiondelete'            => array( 'Sampoh peugot ulang' ),
	'Unusedtemplates'           => array( 'Templat hana soe nguy' ),
	'Randomredirect'            => array( 'Peuninah beurangkari' ),
	'Mypage'                    => array( 'On lon' ),
	'Mytalk'                    => array( 'Peugah haba lon' ),
	'Mycontributions'           => array( 'Atra lon peugot' ),
	'Listadmins'                => array( 'Dapeuta ureueng uroh' ),
	'Listbots'                  => array( 'Dapeuta bot' ),
	'Popularpages'              => array( 'On meuceuhu' ),
	'Search'                    => array( 'Mita' ),
	'Resetpass'                 => array( 'Gantoe lageuem rahsia' ),
	'Withoutinterwiki'          => array( 'Hana interwiki' ),
	'MergeHistory'              => array( 'Riwayat peusapat' ),
	'Filepath'                  => array( 'Neuduek beureukaih' ),
	'Invalidateemail'           => array( 'Peubateue peusah surat-e' ),
	'Blankpage'                 => array( 'On soh' ),
	'LinkSearch'                => array( 'Mita hubong' ),
	'DeletedContributions'      => array( 'Peuneugot nyang geusampoh' ),
	'Tags'                      => array( 'Tag' ),
	'Activeusers'               => array( 'Ureueng nguy udep' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Bôh garéh yup bak hubông:',
'tog-highlightbroken'         => 'Beuntuk hubông reuloh <a href="" class="new">lagèë nyoë</a> (peuniléh: lagèë nyoë<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Peurata paragraf',
'tog-hideminor'               => 'Peusom neuandam bacut bak neuubah paléng barô',
'tog-hidepatrolled'           => 'Peusom neuandam teupatroli bak neuubah paléng barô',
'tog-newpageshidepatrolled'   => 'Peusom ôn teupatroli nibak dapeuta ôn barô',
'tog-extendwatchlist'         => 'Peuhah dapeuta keunalön keu peuleumah ban dum neuubah, kon nyang paléng barô mantöng',
'tog-usenewrc'                => 'Nguy neuleumah neuubah paléng barô tingkat lanjut (peureulèë JavaScript)',
'tog-numberheadings'          => 'Bôh numbô nan keudroë',
'tog-showtoolbar'             => 'Peuleumah <em>toolbar</em> (bateuëng alat) andam',
'tog-editondblclick'          => 'Andam ôn deungon duagoe klik',
'tog-editsection'             => 'Peujeuet andam bideueng rot hubong [andam]',
'tog-editsectiononrightclick' => 'Peujeuet andam bideueng ngon teugon blah uneun bak nan bideueng (peureulee JavaScript)',
'tog-showtoc'                 => 'Peuleumah dapeuta asoe (keu on-on nyang na leubeh nibak 3 boh aneuk ulee)',
'tog-rememberpassword'        => 'Ingat lageuëm rahsia lôn bak komputer nyoë',
'tog-watchcreations'          => 'Tamah on-on nyang lonpeugot u dapeuta kalon',
'tog-watchdefault'            => 'Tamah on-on nyang lon-andam u dapeuta kalon',
'tog-watchmoves'              => 'Tamah on-on nyang lonpeupinah u dapeuta kalon',
'tog-watchdeletion'           => 'Tamah on-on nyang lonsampoh u dapeuta kalon',
'tog-minordefault'            => 'Boh tanda mandum neuandam sibagoe neuandam bacut ngon baku',
'tog-previewontop'            => 'Peuleumah hase yoh goh kutak andam',
'tog-previewonfirst'          => 'Peuleumah hase bak neuandam phon',
'tog-nocache'                 => 'Peumate <em>cache</em> on',
'tog-enotifwatchlistpages'    => "Peu'ek surat-e keu lon meunyo saboh halaman nyang lonkalon meuubah",
'tog-enotifusertalkpages'     => "Peu'ek keu lon surat-e meunyo on marit lon meuubah",
'tog-enotifminoredits'        => "Peu'ek cit surat-e keu lon bak neuandam bacut",
'tog-enotifrevealaddr'        => 'Peuleumah alamat surat-e lon bak neubrithee surat-e',
'tog-shownumberswatching'     => 'Peuleumah jumeulah ureueng kalon',
'tog-oldsig'                  => 'Peuleumah tanda jaroe:',
'tog-fancysig'                => 'Peujeuet tanda jaroe sibagoe naseukah wiki (hana hubong keudroe)',
'tog-externaleditor'          => 'Nguy alat leumiek keu ngon peugot kata luwa',
'tog-externaldiff'            => 'Nguy alat leumiek luwa keu eu bida neuandam',
'tog-showjumplinks'           => 'Peuudep hubong keu ngon bantu "langsong u"',
'tog-uselivepreview'          => 'Nguy peuleumah hase langsong (JavaScript) (baci)',
'tog-forceeditsummary'        => 'Peuingat lon meunyo plok neuringkaih neuandam mantong soh',
'tog-watchlisthideown'        => 'Peusöm nyang lôn andam nibak dapeuta keunalön',
'tog-watchlisthidebots'       => 'Peusöm nyang teu andam nibak sagoö nyang bak dapeuta keunalön',
'tog-watchlisthideminor'      => 'Peusöm Andam Bacut bak dapeuta keunalön',
'tog-watchlisthideliu'        => 'Peusöm andam ureuëng nguy nyang tamöng nibak dapeuta keunalön',
'tog-watchlisthideanons'      => 'Peusöm andam ureuëng nguy hana taturi nibak dapeuta keunalön',
'tog-watchlisthidepatrolled'  => 'Peusom neuandam teukaway bak dapeuta keunalon',
'tog-ccmeonemails'            => "Peu'ek keu lon seunalen surat-e nyang lonpeu'ek keu ureueng la'en",

'underline-always' => 'Sabe',

# Dates
'sunday'        => 'Aleuhat',
'monday'        => 'Seulanyan',
'tuesday'       => 'Seulasa',
'wednesday'     => 'Rabu',
'thursday'      => 'Hameh',
'friday'        => "Jeumeu'at",
'saturday'      => 'Sabtu',
'sun'           => 'Aleu',
'mon'           => 'Seun',
'tue'           => 'Seul',
'wed'           => 'Rab',
'thu'           => 'Ham',
'fri'           => 'Jum',
'sat'           => 'Sab',
'january'       => 'Buleuën Sa',
'february'      => 'Buleuën Duwa',
'march'         => 'Buleuën Lhèë',
'april'         => 'Buleuën Peuët',
'may_long'      => 'Buleuën Limong',
'june'          => 'Buleuën Nam',
'july'          => 'Buleuën Tujôh',
'august'        => 'Buleuën Lapan',
'september'     => 'Buleuën Sikureuëng',
'october'       => 'Buleuën Siplôh',
'november'      => 'Buleuën Siblah',
'december'      => 'Buleuën Duwa Blah',
'january-gen'   => 'Buleuën Sa',
'february-gen'  => 'Buleuën Duwa',
'march-gen'     => 'Buleuën Lhèë',
'april-gen'     => 'Buleuën Peuët',
'may-gen'       => 'Buleuën Limong',
'june-gen'      => 'Buleuën Nam',
'july-gen'      => 'Buleuën Tujôh',
'august-gen'    => 'Buleuën Lapan',
'september-gen' => 'Buleuën Sikureuëng',
'october-gen'   => 'Buleuën Siplôh',
'november-gen'  => 'Buleuën Siblah',
'december-gen'  => 'Buleuën Duwa Blah',
'jan'           => 'Sa',
'feb'           => 'Duwa',
'mar'           => 'Lhèë',
'apr'           => 'Peuët',
'may'           => 'Lim',
'jun'           => 'Nam',
'jul'           => 'Tuj',
'aug'           => 'Lap',
'sep'           => 'Sik',
'oct'           => 'Sip',
'nov'           => 'Sib',
'dec'           => 'Dub',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Kawan|Kawan}}',
'category_header'        => 'Teunuléh lam kawan "$1"',
'subcategories'          => 'Aneuk kawan',
'category-media-header'  => 'Alat lam kawan "$1"',
'category-empty'         => "''Kawan nyoë jinoë hat hana teunuléh atawa media.''",
'hidden-categories'      => '{{PLURAL:$1|Kawan teusom|Kawan teusom}}',
'category-subcat-count'  => '{{PLURAL:$2|Kawan nyoë  cit na saboh yupkawan nyoë.|Kawan nyoë na {{PLURAL:$1|yupkawan|$1 yupkawan}} nyoë, dari ban dum $2.}}',
'category-article-count' => '{{PLURAL:$2|Kawan nyoë cit na saboh ôn nyoë.|Kawan nyoë na  {{PLURAL:$1|ôn|$1 ôn }}, dari ban dum $2.}}',
'listingcontinuesabbrev' => 'samb.',

'about'         => 'Bhah',
'article'       => 'Teunuléh',
'newwindow'     => '(peuhah bak tingkap barô)',
'cancel'        => 'Peubateuë',
'moredotdotdot' => 'Lom...',
'mypage'        => 'Ôn lôn',
'mytalk'        => 'Peugah haba lôn',
'anontalk'      => 'Peugah haba IP nyoë.',
'navigation'    => 'Navigasi',
'and'           => '&#32;ngon',

# Cologne Blue skin
'qbfind'         => 'Mita',
'qbbrowse'       => 'Lop',
'qbedit'         => 'Andam',
'qbpageoptions'  => 'Ôn nyoe',
'qbpageinfo'     => 'Asoe ôn',
'qbmyoptions'    => 'Ôn lôn',
'qbspecialpages' => 'Ôn istimewa',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'   => 'Beunagi barô',
'vector-action-delete'       => 'Sampôh',
'vector-action-move'         => 'Peupinah',
'vector-action-protect'      => 'Peulindông',
'vector-action-undelete'     => 'Bateuë sampôh',
'vector-action-unprotect'    => 'Bateuë peulindông',
'vector-namespace-category'  => 'Kawan',
'vector-namespace-help'      => 'Ôn beunantu',
'vector-namespace-image'     => 'Beureukah',
'vector-namespace-main'      => 'Ôn',
'vector-namespace-media'     => 'Ôn media',
'vector-namespace-mediawiki' => 'Peusan',
'vector-view-create'         => 'Peugöt',
'vector-view-edit'           => 'Andam',
'vector-view-history'        => 'Riwayat away',
'vector-view-view'           => 'Baca',
'vector-view-viewsource'     => 'Eu nè',
'variants'                   => 'Varian',

'errorpagetitle'   => 'Seunalah',
'returnto'         => 'Gisa u $1.',
'tagline'          => 'Nibak {{SITENAME}}',
'help'             => 'Beunantu',
'search'           => 'Mita',
'searchbutton'     => 'Mita',
'searcharticle'    => 'Jak u',
'history'          => 'Riwayat barosa',
'history_short'    => 'Atra u likôt',
'printableversion' => 'Seunalén citak',
'permalink'        => 'Hubông teutap',
'print'            => 'Cetak',
'edit'             => 'Andam',
'create'           => 'Peugöt',
'editthispage'     => 'Andam ôn nyoë',
'create-this-page' => 'Peugèt ôn nyoe',
'delete'           => 'Sampôh',
'deletethispage'   => 'Sampôh ôn nyoe',
'undelete_short'   => 'Bateuë sampôh {{PLURAL:$1|one edit|$1 edits}}',
'protect'          => 'Peulindông',
'protect_change'   => 'ubah',
'newpage'          => 'Ôn barô',
'talkpage'         => 'Peugah haba bhah ôn nyoë',
'talkpagelinktext' => 'Peugah haba',
'personaltools'    => 'Alat droë',
'talk'             => 'Peugah haba',
'views'            => 'Leumah',
'toolbox'          => 'Plôk alat',
'otherlanguages'   => 'Bahsa la’én',
'redirectedfrom'   => '(Geupeupinah nibak $1)',
'redirectpagesub'  => 'Ôn peupinah',
'lastmodifiedat'   => 'Ôn nyoë keuneulheuëh geu’ubah bak $2, $1.',
'jumpto'           => 'Langsông u:',
'jumptonavigation' => 'navigasi',
'jumptosearch'     => 'mita',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Bhah {{SITENAME}}',
'aboutpage'            => 'Project:Bhah Ôn',
'copyright'            => 'Asoë nyang na seusuai ngön $1.',
'copyrightpage'        => '{{ns:project}}:Hak karang',
'currentevents'        => 'Haba barô',
'currentevents-url'    => 'Project:Haba barô',
'disclaimers'          => 'Beunantah',
'disclaimerpage'       => 'Project:Beunantah umom',
'edithelp'             => 'Bantu andam',
'edithelppage'         => 'Help:Andam',
'helppage'             => 'Help:Asoë',
'mainpage'             => 'Ôn Keuë',
'mainpage-description' => 'Ôn Keuë',
'portal'               => 'Meusapat',
'portal-url'           => 'Project:Meusapat',
'privacy'              => 'Jaga rahsia',
'privacypage'          => 'Project:Jaga rahsia',

'badaccess' => 'Salah khut/hak tamöng',

'retrievedfrom'       => 'Meurumpok nibak "$1"',
'youhavenewmessages'  => 'Droëneuh   na $1 ($2).',
'newmessageslink'     => 'peusan barô',
'newmessagesdifflink' => 'neuubah keuneulheuëh',
'editsection'         => 'andam',
'editold'             => 'andam',
'viewsourceold'       => 'Eu nè',
'editlink'            => 'andam',
'viewsourcelink'      => 'eu nè',
'editsectionhint'     => 'Andam bideuëng: $1',
'toc'                 => 'Asoë',
'showtoc'             => 'peuleumah',
'hidetoc'             => 'peusom',
'site-rss-feed'       => 'Umpeuën RSS $1',
'site-atom-feed'      => 'Eumpeuën Atôm $1',
'page-rss-feed'       => 'Umpeuën RSS "$1"',
'page-atom-feed'      => 'Umpeuën Atom "$1"',
'red-link-title'      => '$1 (ôn goh na)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Ôn',
'nstab-user'     => 'Ureuëng nguy',
'nstab-media'    => 'Ôn media',
'nstab-special'  => 'Kusuih',
'nstab-project'  => 'Proyèk ôn',
'nstab-image'    => 'Beureukah',
'nstab-template' => 'Templat',
'nstab-category' => 'Kawan',

# General errors
'error'              => 'Kesalahan',
'databaseerror'      => 'Kesalahan basis data',
'missing-article'    => 'Basis data h’an jeuët jiteumèë naseukah nibak ôn nyang sipatôtjih na, nakeuh "$1" $2.

Nyoë biasajih sabab hubông useuëng u geunantoë away nyang ka teusampôh.

Meunyo kön nyoë sababjih, Droëneuh kadang ka neuteumèë saboh bug lam software. Neutulông peugah bhah nyoë bak salah sidroë [[Special:ListUsers/sysop|Nyang urôh]], ngön neupeugah alamat URL nyang neusaweuë.',
'missingarticle-rev' => '(revisi#: $1)',
'badtitle'           => 'Nan hana sah',
'badtitletext'       => 'Nan ôn nyang neulakèë hana sah, soh, atawa nan antarabahsa atawa antarawiki nyang salah sambông.',
'viewsource'         => 'Eu nè',
'viewsourcefor'      => 'keu $1',
'viewsourcetext'     => 'Droëneuh  jeuët neu’eu',

# Login and logout pages
'yourname'                => 'Nan ureuëng nguy:',
'yourpassword'            => 'Lageuëm rahsia:',
'remembermypassword'      => 'Ingat lageuëm rahsia lôn bak komputer nyoë',
'login'                   => 'Tamöng',
'nav-login-createaccount' => 'Tamöng / dapeuta',
'loginprompt'             => "Droëneuh payah neupeu’udép ''cookies'' beujeuët neutamong u {{SITENAME}}",
'userlogin'               => 'Tamöng / dapeuta',
'logout'                  => 'Teubiët',
'userlogout'              => 'Teubiët',
'nologin'                 => "Goh na nan ureuëng nguy? '''$1'''.",
'nologinlink'             => 'Peudapeuta nan barô',
'createaccount'           => 'Peudapeuta nan barô',
'gotaccount'              => "Ka lheuëh neudapeuta? '''$1'''.",
'gotaccountlink'          => 'Tamong',
'loginsuccesstitle'       => 'Meuhasé tamong',
'loginsuccess'            => "'''Droëneuh  jinoë ka neutamong di {{SITENAME}} sibagoë \"\$1\".'''",
'nosuchuser'              => 'Hana ureuëng nguy ngön nan "$1".
Nan ureuëng nguy jipeubida harah rayek.
Tulông neuparéksa keulayi neuija Droëneuh, atawa [[Special:UserLogin/signup|neudapeuta barô]].',
'nosuchusershort'         => 'Hana ureuëng nguy ngön nan "<nowiki>$1</nowiki>". 
Préksa keulayi neu’ija Droëneuh.',
'nouserspecified'         => 'Neupasoë nan Droëneuh.',
'wrongpassword'           => 'Lageuëm rahsia nyang neupasoë salah. Neubaci lom.',
'wrongpasswordempty'      => 'Droëneuh hana neupasoë lageuëm rahsia. Neubaci lom.',
'passwordtooshort'        => 'Lageuëm rahsia Droëneuh hana sah atawa paneuk that. 
Lageuëm rahsia paléng kureung {{PLURAL:$1|1 karakter|$1 karakter}} ngön beubida ngön nan Droëneuh.',
'mailmypassword'          => 'Kirém lageuëm rahsia barô',
'passwordremindertitle'   => 'Lageuëm rahsia seumeuntara barô keu {{SITENAME}}',
'passwordremindertext'    => 'Salah sidroë (kadang Droëneuh, ngön alamat IP $1) geulakèë kamoë keu meukirém lageuëm rahsia nyang barô keu {{SITENAME}} ($4). 
Lageuëm rahsia keu ureuëng nguy "$2" jinoë nakeuh "$3". 
Droëneuh geupeusaran keu neutamong sigra, lheuëh nyan neugantoë lageuëm rahsia.',
'noemail'                 => 'Hana alamat surat-e nyang teucatat keu ureuëng nguy "$1".',
'passwordsent'            => 'Lageuëm rahsia barô ka geukirém u surat-e nyang geupeudapeuta keu "$1". Neutamong teuma lheuëh neuteurimong surat-e nyan.',
'eauthentsent'            => 'Saboh surat èlèktronik keu peunyoë ka geukirém u alamat surat èlèktronik Droëneuh. Droëneuh beuneuseutöt préntah lam surat nyan keu neupeunyoë meunyo alamat nyan nakeuh beutôy atra Droëneuh. {{SITENAME}} h‘an geupeuudép surat Droëneuh meunyo langkah nyoë hana neupeulaku lom.',

# Password reset dialog
'retypenew' => 'Pasoë keulayi lageuëm rahsia barô:',

# Edit page toolbar
'bold_sample'     => 'Citak teubay naseukah nyoë',
'bold_tip'        => 'Citak teubay',
'italic_sample'   => 'Citak singèt naseukah nyoë',
'italic_tip'      => 'Citak singèt',
'link_sample'     => 'Nan hubông',
'link_tip'        => 'Hubông dalam',
'extlink_sample'  => 'http://www.example.com nan hubông',
'extlink_tip'     => 'Hubông luwa (bèk tuwoë bôh http:// bak away)',
'headline_sample' => 'Naseukah nan',
'headline_tip'    => 'Aneuk beunagi tingkat 1',
'math_sample'     => 'Pasoë rumuh nyoë pat',
'math_tip'        => 'Rumuh matematik (LaTeX)',
'nowiki_sample'   => 'Bèk format naseukah nyoë',
'nowiki_tip'      => 'Bèk seutot beuntuk wiki',
'image_tip'       => 'Pasoë beureukah',
'media_tip'       => 'Hubông beureukah alat',
'sig_tip'         => 'Tanda jaroë Droëneuh  ngön tanda watèë',
'hr_tip'          => 'Garéh data',

# Edit pages
'summary'                          => 'Reuningkah:',
'subject'                          => 'Bhah/nan:',
'minoredit'                        => 'Nyoë lôn andam bacut',
'watchthis'                        => 'Kalön ôn nyoë',
'savearticle'                      => 'Keubah ôn',
'preview'                          => 'Eu dilèë',
'showpreview'                      => 'Peuleumah hasé',
'showdiff'                         => 'Peuleumah neu’ubah',
'anoneditwarning'                  => 'Droëneuh   hana teudapeuta tamong. Alamat IP Droëneuh   teucatat lam tarèh (riwayat away) ôn nyoë.',
'summary-preview'                  => 'Eu dilèë reuningkah:',
'blockedtext'                      => "'''Nan ureuëng nguy atawa alamat IP Droëneuh  ka geutheun.''' 

Geutheun lé $1. Dalèh jih nakeuh ''$2''. 

* Geutheun yôh: $8 
* Neutheun maté tanggay bak: $6 
* Nyang geutheun: $7 

Droëneuh   jeuët neutanyong bak $1 atawa [[{{MediaWiki:Grouppage-sysop}}|nyang urôh nyang la’én]] keu peugah haba bhah nyoë.

Droëneuh   h’an jeuët neunguy alat 'Kirém surat-e ureuëng nguy nyoë' keucuali ka neupasoë alamat surat-e nyang sah di [[Special:Preferences|Geunalak]] Droëneuh ngön Droëneuh ka geutheun keu nguy nyan.

Alamat IP Droëneuh nakeuh $3, ngön ID neutheun nakeuh $5. Tulông peuseureuta salah saboh atawa ban duwa beurita nyoë bak tiëp teunanyöng nyang neupeugöt.",
'newarticle'                       => '(Barô)',
'newarticletext'                   => "Droëneuh   ka neuseutot u ôn nyang goh na. Keu peugöt ôn nyan, neukeutik asoë ôn di  kutak di yup nyoë (ngiëng [[{{MediaWiki:Helppage}}|ôn bantu]] keu beurita leubèh lanjut). Meunyo Droëneuh  hana neusaja ka trôk keunoë, teugon '''back''' nyang na bak layeuë.",
'noarticletext'                    => 'Hana naseukah jinoë lam ôn nyoë.
Ji Droëneuh jeuët [[Special:Search/{{PAGENAME}}|neumita keu nan ôn nyoë]] bak ôn-ôn la’én, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} log nyang na hubôngan], atawa [{{fullurl:{{FULLPAGENAME}}|action=edit}} neu\'andam ôn nyoë]</span>.',
'updated'                          => '(Seubarô)',
'note'                             => "'''Ceunatat:'''",
'previewnote'                      => "'''Beu neuingat meunyo nyoë goh lom neukeubah!'''",
'editing'                          => 'Andam $1',
'editingsection'                   => 'Andam $1 (bideuëng)',
'copyrightwarning'                 => "Beuneuingat bahwa ban mandum nyang Droëneuh   tuléh keu {{SITENAME}} geukira geupeuteubiët di yup $2 (ngiëng $1 keu leubèh jeulah). Meunyoë Droëneuh h‘an neutém teunuléh Droëneuh  ji’andam ngön jiba ho ho la’én, bèk neupasoë teunuléh Droëneuh  keunoë.<br />Droëneuh  neumeujanji chit meunyoë teunuléh nyoë nakeuh atra neutuléh keudroë, atawa neucok nibak nè nè atra umôm atawa nè bibeuëh la’én.
'''BÈK NEUPASOË TEUNULÉH NYANG GEUPEULINDÔNG HAK KARANG NYANG HANA IDIN'''",
'longpagewarning'                  => "'''INGAT: Ôn nyoë panyangjih nakeuh $1 kilobit; ladôm alat rawoh web kadang na masalah bak ji’andam ôn nyang panyangjih 32 kb atawa leubèh. Beu neupeutimang keu neuplah jeuët padum boh beunagi nyang leubèh cut. '''",
'templatesused'                    => '{{PLURAL:$1|Templat|Templat}} nyang geunguy bak ôn nyoë:',
'templatesusedpreview'             => '{{PLURAL:$1|Templat|Templat}} nyang geunguy bak eu dilèë nyoë:',
'template-protected'               => '(geulindông)',
'template-semiprotected'           => '(siteungoh-lindông)',
'hiddencategories'                 => 'Ôn nyoë nakeuh anggèëta nibak {{PLURAL:$1|1 kawan teusom |$1 kawan teusom}}:',
'nocreatetext'                     => '{{SITENAME}} ka jikot bak peugöt ôn barô. Ji Droëneuh   jeuët neuriwang teuma ngön neu’andam ôn nyang ka na, atawa [[Special:UserLogin|neutamong atawa neudapeuta]].',
'permissionserrorstext-withaction' => 'Droëneuh hana hak tamöng keu $2, muroë {{PLURAL:$1|choë|choë}} nyoë:',
'recreate-moveddeleted-warn'       => "'''Ingat: Droëneuh  teungoh neupeugöt ulang saboh ôn nyang ka tom geusampôh. ''',

Neutimang-timang dilèë peuë ék patôt neupeulanjut atra nyang teungoh neu’andam.
Nyoë pat nakeuh log seunampôh nibak ôn nyoë:",
'moveddeleted-notice'              => 'Ôn nyoë ka geusampôh. Meunyo peureulèë, nyoë pat nakeuh log seunampôh ôn nyoë.',

# History pages
'viewpagelogs'           => 'Eu log ôn nyoë',
'currentrev'             => 'Geunantoë jinoë',
'currentrev-asof'        => 'Geunantoë paléng barô bak $1',
'revisionasof'           => 'Gantoë tiëp $1',
'revision-info'          => 'Geunantoë tiëp $1; $2',
'previousrevision'       => '←Geunantoë sigohlomjih',
'nextrevision'           => 'Geunantoë lheuëh nyan→',
'currentrevisionlink'    => 'Geunantoë jinoë',
'cur'                    => 'jin',
'last'                   => 'akhé',
'page_first'             => 'phôn',
'page_last'              => 'keuneulheuëh',
'histlegend'             => "Piléh duwa teuneugön radiô, lheuëh nyan teugön teuneugön ''peubandéng'' keu peubandéng seunalén. Teugön saboh tanggay keu eu seunalén ôn bak tanggay nyan.<br />(skr) = bida ngön seunalén jinoë, (akhé) = bida ngön seunalén sigohlomjih. '''b''' = andam bacut, '''b''' = andam bot, → = andam bideuëng, ← = reuningkah keudroë",
'history-fieldset-title' => 'Jeulajah riwayat away',
'histfirst'              => 'Paléng trép',
'histlast'               => 'Paléng barô',

# Revision feed
'history-feed-item-nocomment' => '$1 bak $2',

# Revision deletion
'rev-delundel'   => 'peuleumah/peusom',
'revdel-restore' => 'Ubah leumah',

# Merge log
'revertmerge' => 'Hana jadèh peugabông',

# Diffs
'history-title'           => 'Riwayat geunantoë nibak "$1"',
'difference'              => '(Bida antara geunantoë)',
'lineno'                  => 'Baréh $1:',
'compareselectedversions' => 'Peubandéng curak teupiléh',
'editundo'                => 'peubateuë',
'diff-multi'              => '({{PLURAL:$1|Sa|$1}} geunantoë antara hana geupeuleumah.)',

# Search results
'searchresults'             => 'Hasé mita',
'searchresults-title'       => 'Hasé mita keu "$1"',
'searchresulttext'          => 'Keu beurita leubèh le bhah meunita bak {{SITENAME}}, eu [[{{MediaWiki:Helppage}}|ôn beunantu]].',
'searchsubtitle'            => 'Droëneuh neumita \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|ban dum ôn nyang geupuphôn ngön "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|bandum ôn nyang teuhubông u "$1"]])',
'searchsubtitleinvalid'     => "Droëneuh neumita '''$1'''",
'notitlematches'            => 'Hana nan ôn nyang pah',
'notextmatches'             => 'Hana naseukah ôn nyang pah',
'prevn'                     => '{{PLURAL:$1|$1}} sigohlomjih',
'nextn'                     => '{{PLURAL:$1|$1}} lheuëh nyan',
'viewprevnext'              => 'Eu ($1 {{int:pipe-separator}} $2)($3)',
'searchhelp-url'            => 'Help:Asoë',
'search-result-size'        => '$1 ({{PLURAL:$2|1 kata|$2 kata}})',
'search-redirect'           => '(peuninah $1)',
'search-section'            => '(bagian $1)',
'search-suggest'            => 'Kadang meukeusud Droëneuh nakeuh: $1',
'search-interwiki-caption'  => 'Buët la’én',
'search-interwiki-default'  => 'Hasé $1:',
'search-interwiki-more'     => '(lom)',
'search-mwsuggest-enabled'  => 'ngon saran',
'search-mwsuggest-disabled' => 'hana saran',
'nonefound'                 => "'''Ceunatat''': Cit ladôm ruweuëng nyang seucara baku geupeutamöng lam meunita. Ci neupuphôn leunakèë Droëneuh ngön ''all:'' keu mita ban dum asoë (rôh cit ôn peugah haba, tèmplat, ngön nyang la’én (nnl)), atawa neunguy ruweuëng nan nyang neumeuh’eut sibagoë neu’away.",
'powersearch'               => 'Mita lanjut',
'powersearch-legend'        => 'Mita lanjut',
'powersearch-ns'            => 'Mita bak ruweuëng nan:',
'powersearch-redir'         => 'Dapeuta peuninah',
'powersearch-field'         => 'Mita',

# Preferences page
'preferences'         => 'Galak',
'mypreferences'       => 'Lôn galak',
'prefs-rc'            => 'Ban meuubah',
'yourrealname'        => 'Nan aseuli:',
'prefs-help-realname' => '* Nan aseuli hana meucéh neupasoë.
Meunyo neupasoë, euntreuk nan Droëneuh nyan geupeuleumah mangat jitupeuë soë nyang tuléh.',

# Groups
'group-sysop' => 'Ureuëng urôh',

'grouppage-sysop' => '{{ns:project}}:Ureuëng urôh',

# User rights log
'rightslog' => 'Log neuubah hak peuhah',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'andam ôn nyoë',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|neu’ubah|neu’ubah}}',
'recentchanges'                  => 'Neuubah barô',
'recentchanges-legend'           => 'Peuniléh neuubah paléng barô',
'recentchanges-feed-description' => 'Peutumèë neu’ubah paléng barô lam wiki bak eumpeuën nyoë.',
'rcnote'                         => "Di yup nyoë nakeuh {{PLURAL:$1|nakeuh '''1''' neu’ubah paléng barô |nakeuh '''$1''' neu’ubah paléng barô}} lam {{PLURAL:$2|'''1''' uroë|'''$2''' uroë}} nyoë, trôk ‘an $5, $4.",
'rcnotefrom'                     => 'Di yup nyoë nakeuh neu’ubah yôh <strong>$2</strong> (geupeuleumah trôh ‘an <strong>$1</strong> neu’ubah).',
'rclistfrom'                     => 'Peuleumah neuubah paléng barô yôh $1 kön',
'rcshowhideminor'                => '$1 andam bacut',
'rcshowhidebots'                 => '$1 bot',
'rcshowhideliu'                  => '$1 ureuëng nguy tamong',
'rcshowhideanons'                => '$1 ureuëng nguy hana nan',
'rcshowhidepatr'                 => '$1 andam teurunda',
'rcshowhidemine'                 => '$1 atra lôn andam',
'rclinks'                        => 'Peuleumah $1 neuubah paléng barô lam $2 uroë nyoë<br />$3',
'diff'                           => 'bida',
'hist'                           => 'riwayat',
'hide'                           => 'Peusom',
'show'                           => 'Peuleumah',
'minoreditletter'                => 'b',
'newpageletter'                  => 'B',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Peuleumah neurinci (peureulèë JavaScript)',
'rc-enhanced-hide'               => 'Peusom neurinci',

# Recent changes linked
'recentchangeslinked'          => 'Neuubah meuhubông',
'recentchangeslinked-feed'     => 'Seundi meuhubông',
'recentchangeslinked-toolbox'  => 'Seundi meuhubông',
'recentchangeslinked-title'    => 'Neuubah nyang meuhubông ngön $1',
'recentchangeslinked-noresult' => 'Hana neu’ubah bak ôn-ôn meuhubông silawét masa nyang ka geupeuteuntèë.',
'recentchangeslinked-summary'  => "Ôn husôh nyoë geupeuleumah dapeuta neu’ubah keuneulheuëh bak ôn ôn meuhubông. Ôn nyang neukalön geubri tanda ngön '''citak teubay'''.",
'recentchangeslinked-page'     => 'Nan ôn:',
'recentchangeslinked-to'       => 'Peuleumah neu’ubah nibak ôn-ôn nyang meusambông ngön ôn nyang geubri',

# Upload
'upload'        => 'Peutamong',
'uploadbtn'     => 'Peutamong beureukah',
'uploadlogpage' => 'Log peutamöng',
'uploadedimage' => 'peutamöng "[[$1]]"',

# Special:ListFiles
'listfiles' => 'Dapeuta beureukah',

# File description page
'file-anchor-link'          => 'Beureukah',
'filehist'                  => 'Riwayat beureukah',
'filehist-help'             => 'Teugon bak tanggay/watèë keu eu beureukah nyoë ‘oh watèë nyan.',
'filehist-current'          => 'jinoë hat',
'filehist-datetime'         => 'Tanggay/Watèë',
'filehist-thumb'            => 'Beuntuk ubeut',
'filehist-thumbtext'        => 'Beuntuk ubeut keu seunalén tiëp $1',
'filehist-user'             => 'Ureuëng nguy',
'filehist-dimensions'       => 'Dimènsi',
'filehist-filesize'         => 'Rayek beureukah',
'filehist-comment'          => 'Tapeusé',
'imagelinks'                => 'Hubông beureukah',
'linkstoimage'              => 'Ôn di yup nyoë na {{PLURAL:$1|hubông|$1 hubông}} u beureukah nyoë:',
'nolinkstoimage'            => 'Hana ôn nyang na hubông u beureukah nyoë.',
'sharedupload'              => 'Beureukah nyoë dari $1 ngön kadang geunguy lé buët-buët la’én.',
'uploadnewversion-linktext' => 'Peulöt seunalén nyang leubèh barô nibak beureukah nyoë.',

# MIME search
'mimesearch' => 'Mita MIME',

# List redirects
'listredirects' => 'Dapeuta peuninah',

# Unused templates
'unusedtemplates' => 'Templat nyang hana geunguy',

# Random page
'randompage' => 'Ôn beurangkari',

# Random redirect
'randomredirect' => 'Peuninah saban sakri',

# Statistics
'statistics' => 'Keunira',

'disambiguations' => 'Ôn disambiguasi',

'doubleredirects' => 'Peuninah ganda',

'brokenredirects' => 'Peuninah reulöh',

'withoutinterwiki' => 'Ôn tan na hubông bahsa',

'fewestrevisions' => 'Teunuléh ngön neu’ubah paléng dit',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bit|bit}}',
'nlinks'                  => '$1 {{PLURAL:$1|hubông|hubông}}',
'nmembers'                => '$1 {{PLURAL:$1|asoë|asoë}}',
'lonelypages'             => 'Ôn tan hubông balék',
'uncategorizedpages'      => 'Ôn nyang hana rôh lam kawan',
'uncategorizedcategories' => 'Kawan nyang hana rôh lam kawan',
'uncategorizedimages'     => 'Beureukah nyang hana rôh lam kawan',
'uncategorizedtemplates'  => 'Templat nyang hana rôh lam kawan',
'unusedcategories'        => 'Kawan nyang hana geunguy',
'unusedimages'            => 'Beureukah nyang hana geunguy',
'wantedcategories'        => 'Kawan nyang geuhawa',
'wantedpages'             => 'Ôn nyang geuh‘eut',
'mostlinked'              => 'Ôn nyang paléng kayém geusaweuë',
'mostlinkedcategories'    => 'Kawan nyang paléng kayém geunguy',
'mostlinkedtemplates'     => 'Templat nyang paléng kayém geunguy',
'mostcategories'          => 'Teunuléh ngön kawan paléng le',
'mostimages'              => 'Beureukah nyang paléng kayém geunguy',
'mostrevisions'           => 'Teunuléh ngön neu’ubah paléng le',
'prefixindex'             => 'Ban dum ôn ngön haraih away',
'shortpages'              => 'Ôn paneuk',
'longpages'               => 'Ôn panyang',
'deadendpages'            => 'Ôn buntu',
'protectedpages'          => 'Ôn nyang geulindông',
'listusers'               => 'Dapeuta ureuëng nguy',
'newpages'                => 'Ôn barô',
'ancientpages'            => 'Teunuléh away',
'move'                    => 'Peupinah',
'movethispage'            => 'Peupinah ôn nyoë',
'pager-newer-n'           => '{{PLURAL:$1|1 leubèh barô |$1 leubèh barô}}',
'pager-older-n'           => '{{PLURAL:$1|1 leubèh trép|$1 leubèh trép}}',

# Book sources
'booksources'               => 'Nè kitab',
'booksources-search-legend' => 'Mita bak nè kitab',
'booksources-go'            => 'Mita',

# Special:Log
'specialloguserlabel'  => 'Ureuëng nguy:',
'speciallogtitlelabel' => 'Nan:',
'log'                  => 'Log',
'all-logs-page'        => 'Ban dum log',

# Special:AllPages
'allpages'       => 'Dapeuta ôn',
'alphaindexline' => '$1 u $2',
'nextpage'       => 'Ôn lheuëh nyan ($1)',
'prevpage'       => 'Ôn sigohlomjih ($1)',
'allpagesfrom'   => 'Peuleumah ôn peuphôn nibak:',
'allpagesto'     => 'Peuleumah ôn geupeuakhé bak:',
'allarticles'    => 'Dapeuta teunuléh',
'allpagessubmit' => 'Mita',
'allpagesprefix' => 'Peuleumah ôn ngön harah phôn:',

# Special:Categories
'categories' => 'Dapeuta kawan',

# Special:LinkSearch
'linksearch' => 'Hubông luwa',

# Special:Log/newusers
'newuserlogpage'          => 'ureuëng nguy barô',
'newuserlog-create-entry' => 'dapeuta jeuët anggèëta',

# Special:ListGroupRights
'listgrouprights-members' => '(dapeuta anggèëta)',

# E-mail user
'emailuser' => 'Surat-e ureuëng nguy',

# Watchlist
'watchlist'         => 'Dapeuta keunalön lôn',
'mywatchlist'       => 'Keunalön lôn',
'watchlistfor'      => "(keu '''$1''')",
'addedwatch'        => 'Ka geupeutamah u dapeuta kalön',
'addedwatchtext'    => "Ôn \"[[:\$1]]\" ka geupeutamah u [[Special:Watchlist|dapeuta keunalön]] Droëneuh. Neu’ubah-neu’ubah bak masa u keuë bak ôn nyan ngön bak ôn peugah habajih, euntreuk leumah nyoë pat. Ôn nyan euntreuk geupeuleumah ''teubay'' bak [[Special:RecentChanges|dapeuta neu’ubah paléng barô]] mangat leubèh mudah leumah.",
'removedwatch'      => 'Ka geusampôh nibak dapeuta keunalön',
'removedwatchtext'  => 'Ôn "[[:$1]]" ka geusampôh nibak [[Special:Watchlist|dapeuta keunalön]] Droëneuh.',
'watch'             => 'Kalön',
'watchthispage'     => 'Kalön ôn nyoë',
'unwatch'           => 'Bateuë kalön',
'watchlist-details' => '{{PLURAL:$1|$1 ôn|$1 ôn}} geukalön, hana kira ôn peugah haba.',
'wlshowlast'        => 'Peuleumah $1 jeum $2 uroë $3 keuneulheuëh',
'watchlist-options' => 'Peuniléh dapeuta kalön',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Kalön...',
'unwatching' => 'Hana kalön...',

# Delete
'deletepage'            => 'Sampôh ôn',
'historywarning'        => 'Ingat: Ôn nyang hawa neusampôh na riwayat:',
'confirmdeletetext'     => 'Droëneuh neuk neusampôh ôn atawa beureukah nyoë keu sabé. Meunan cit ban mandum riwayatjih nibak basis data. Neupeupaseuti meunyo Droëneuh cit keubiët meung neusampôh, neutupeuë ban mandum akébatjih, ngön peuë nyang neupeulaku nyoë nakeuh meunurôt [[{{MediaWiki:Policy-url}}|kebijakan{{SITENAME}}]].',
'actioncomplete'        => 'Seuleusoë',
'deletedtext'           => '"<nowiki>$1</nowiki>" ka geusampôh. Eu $2 keu log paléng barô bak ôn nyang ka geusampôh.',
'deletedarticle'        => 'sampôh "[[$1]]"',
'dellogpage'            => 'Log seunampoh',
'deletecomment'         => 'Choë:',
'deleteotherreason'     => 'Nyang la’én/choë la’én:',
'deletereasonotherlist' => 'Choë la’én',

# Rollback
'rollbacklink' => 'pulang',

# Protect
'protectlogpage'              => 'Log lindông',
'protectedarticle'            => 'peulindông "[[$1]]"',
'modifiedarticleprotection'   => 'Ubah tingkat lindông "[[$1]]"',
'prot_1movedto2'              => 'peupinah [[$1]] u [[$2]]',
'protectcomment'              => 'Choë:',
'protectexpiry'               => 'Maté tanggay:',
'protect_expiry_invalid'      => 'Watèë maté tanggay hana sah.',
'protect_expiry_old'          => 'Watèë maté tanggay nakeuh bak masa u likôt.',
'protect-text'                => "Droëneuh jeuët neu’eu atawa neugantoë tingkat lindông keu ôn '''<nowiki>$1</nowiki>''' nyoë pat.",
'protect-locked-access'       => "Nan dapeuta Droëneuh hana hak keu jak gantoë tingkat lindông ôn. Nyoë pat nakeuh konfigurasi atra jinoë keu ôn '''$1''':",
'protect-cascadeon'           => 'Ôn nyoë teungöh geulindông kareuna geupeuseureuta lam {{PLURAL:$1|ôn|ôn-ôn}} nyoë nyang ka geulindông ngön peuniléh lindông meuturôt geupeuudép.
Droëneuh jeuët neugantoë tingkat lindông keu ôn nyoë, tapi nyan hana peungarôh keu lindông meuturôt.',
'protect-default'             => 'Peuidin ban dum ureuëng nguy',
'protect-fallback'            => 'Peureulèë hak peuhah "$1"',
'protect-level-autoconfirmed' => 'Theun ureuëng nguy barô ngön hana teudapeuta',
'protect-level-sysop'         => 'Ureuëng urôh mantöng',
'protect-summary-cascade'     => 'riti',
'protect-expiring'            => 'maté tanggay $1 (UTC)',
'protect-cascade'             => 'Peulindông ban mandum ôn nyang rôh lam ôn nyoë (lindông meuturôt).',
'protect-cantedit'            => 'Droëneuh h‘an jeuët neu’ubah tingkat lindông ôn nyoë kareuna Droëneuh hana hak keu neupeulaku nyan.',
'protect-expiry-options'      => '1 jeum:1 hour,1 uroë:1 day,1 minggu:1 week,2 minggu:2 weeks,1 buleuën:1 month,3 buleuën:3 months,6 buleuën:6 months,1 thôn:1 year,sabé:infinite',
'restriction-type'            => 'Lindông:',
'restriction-level'           => 'Tingkat:',

# Undelete
'undeletebtn'      => 'Peuriwang!',
'undeletelink'     => 'eu/peuriwang',
'undeletedarticle' => '"$1" ka geupeuriwang',

# Namespace form on various pages
'namespace'      => 'Ruweuëng nan:',
'invert'         => 'Peubalék peuniléh',
'blanknamespace' => '(Utama)',

# Contributions
'contributions'       => 'Peuneugöt',
'contributions-title' => 'Peuneugöt ureuëng nguy keu $1',
'mycontris'           => 'Peuneugöt lôn',
'contribsub2'         => 'Keu $1 ($2)',
'uctop'               => '(ateuëh)',
'month'               => 'Yôh buleuën (ngön yôh goh lom nyan)',
'year'                => 'Yôh thôn (ngön yôh goh lom nyan)',

'sp-contributions-newbies'     => 'Keu ureuëng-ureuëng nyang ban nguy mantöng',
'sp-contributions-newbies-sub' => 'Keu ureuëng nguy barô',
'sp-contributions-blocklog'    => 'Log peutheun',
'sp-contributions-talk'        => 'Peugah haba',
'sp-contributions-search'      => 'Mita soë nyang tuléh',
'sp-contributions-username'    => 'Alamat IP atawa nan ureuëng nguy:',
'sp-contributions-submit'      => 'Mita',

# What links here
'whatlinkshere'            => 'Hubông balék',
'whatlinkshere-title'      => 'Ôn nyang na hubông u $1',
'whatlinkshere-page'       => 'Ôn:',
'linkshere'                => "Ôn-ôn nyoë meuhubông u '''[[:$1]]''':",
'nolinkshere'              => "Hana ôn nyang teuhubông u '''[[:$1]]'''.",
'isredirect'               => 'ôn peupinah',
'istemplate'               => 'deungön seunaleuëk',
'isimage'                  => 'hubông beureukah',
'whatlinkshere-prev'       => '$1 {{PLURAL:$1|sigohlomjih|sigohlomjih}}',
'whatlinkshere-next'       => '$1 {{PLURAL:$1|lheuëh nyan|lheuëh nyan}}',
'whatlinkshere-links'      => '← hubông',
'whatlinkshere-hideredirs' => '$1 peuninah',
'whatlinkshere-hidetrans'  => '$1 transklusi',
'whatlinkshere-hidelinks'  => '$1 hubông',
'whatlinkshere-filters'    => 'Saréng',

# Block/unblock
'blockip'                  => 'Theun ureuëng nguy',
'ipboptions'               => '2 jeum:2 hours,1 uroë:1 day,3 uroë:3 days,1 minggu:1 week,2 minggu:2 weeks,1 buleuën:1 month,3 buleuën:3 months,6 buleuën:6 months,1 thôn:1 year,sabé:infinite',
'ipblocklist'              => 'Dapeuta neutheun',
'blocklink'                => 'theun',
'unblocklink'              => 'peugadöh theun',
'change-blocklink'         => 'ubah theun',
'contribslink'             => 'peuneugöt',
'blocklogpage'             => 'Log peutheun',
'blocklogentry'            => 'theun [[$1]] ngön watèë maté tanggay $2 $3',
'unblocklogentry'          => 'peugadöh theun "$1"',
'block-log-flags-nocreate' => 'pumeugöt nan geupumaté',

# Move page
'movepagetext'     => "Formulir di yup nyoë geunguy keu jak ubah nan saboh ôn ngön jak peupinah ban dum data riwayat u nan barô. Nan nyang trép euntreuk jeuët keu ôn peupinah u nan nyang barô. Hubông u nan trép hana meu’ubah. Neupeupaseuti keu neupréksa peuninah ôn nyang reulöh atawa meuganda lheuëh neupinah. Droëneuh nyang mat tanggông jaweuëb keu neupeupaseuti meunyo hubông laju teusambông u ôn nyang patôt.

Beuneuingat that meunyo ôn '''h’an''' jan geupeupinah meunyo ka na ôn nyang geunguy nan barô, keucuali meunyo ôn nyan soh atawa nakeuh ôn peuninah ngön hana riwayat andam. Nyoë areutijih Droëneuh jeuët neu’ubah nan ôn keulayi lagèë söt meunyo Droëneuh neupeugöt seunalah, ngön Droëneuh h‘an jeuët neutimpa ôn nyang ka na.
'''INGAT'''
Nyoë jeuët geupeuakébat neu’ubah nyang h’an neuduga ngön kreuëh ngön bacah keu ôn nyang meuceuhu. Neupeupaseuti Droëneuh meuphôm akébat nibak buët nyoë sigohlom neulanjut.",
'movepagetalktext' => "Ôn peugah haba nyang na hubôngan euntreuk teupinah keudroë '''keucuali meunyo:'''

*Saboh ôn peugah haba nyang hana soh ka na di yup nan barô, atawa
*Droëneuh hana neubôh tanda cunténg bak kutak di yup nyoë

Lam masalah nyoë, meunyo neuhawa, Droëneuh jeuët neupeupinah atawa neupeugabông ôn keudroë.",
'movearticle'      => 'Peupinah ôn:',
'newtitle'         => 'U nan barô:',
'move-watch'       => 'Kalön ôn nyoë',
'movepagebtn'      => 'Peupinah ôn',
'pagemovedsub'     => 'Peupinah meuhasé',
'movepage-moved'   => '\'\'\'"$1" ka geupeupinah u "$2".\'\'\'',
'articleexists'    => 'Ôn ngön nan nyan ka na atawa nan nyang neupiléh hana sah. Neupiléh nan la’én.',
'talkexists'       => 'Ôn nyan ka geupeupinah, tapi ôn peugah haba bak ôn nyan h‘an jeuët geupeupinah kareuna ka na ôn peugah haba bak nan barô. Neupeusapat mantöng ôn ôn peugah haba nyan keudroë.',
'movedto'          => 'geupeupinah u',
'movetalk'         => 'Peupinah ôn peugah haba nyang na hubôngan.',
'1movedto2'        => 'peupinah [[$1]] u [[$2]]',
'1movedto2_redir'  => 'pupinah [[$1]] u [[$2]] röt peuninah',
'movelogpage'      => 'Log pinah',
'movereason'       => 'Choë:',
'revertmove'       => 'peuriwang',

# Export
'export' => 'Èkspor ôn',

# Namespace 8 related
'allmessages' => 'Peusan sistem',

# Thumbnails
'thumbnail-more'  => 'Peurayek',
'thumbnail_error' => 'Salah bak peugöt gamba cut: $1',

# Import log
'importlogpage' => 'Log impor',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Ôn ureuëng nguy Droëneuh',
'tooltip-pt-mytalk'               => 'Ôn peugah haba Droëneuh',
'tooltip-pt-preferences'          => 'Geunalak lôn',
'tooltip-pt-watchlist'            => 'Dapeuta ôn nyang lôn kalön',
'tooltip-pt-mycontris'            => 'Dapeuta peuneugöt Droëneuh',
'tooltip-pt-login'                => 'Droën geupeusaran keu tamong log, bahpih nyan hana geupeuwajéb.',
'tooltip-pt-logout'               => 'Teubiët',
'tooltip-ca-talk'                 => 'Peugah haba ôn asoë',
'tooltip-ca-edit'                 => 'Andam ôn nyoë. Nguy tumbôy euë dilèë yôh goh lom keumeubah.',
'tooltip-ca-addsection'           => 'Puphôn beunagi barô',
'tooltip-ca-viewsource'           => 'Ôn nyoë geupeulindông. 
Droëneuh cit jeuët neu’eu nèjih.',
'tooltip-ca-history'              => 'Seunalén-seunalén sigohlomjih nibak ôn nyoë',
'tooltip-ca-protect'              => 'Peulindông ôn nyoë',
'tooltip-ca-delete'               => 'Sampôh ôn nyoë',
'tooltip-ca-move'                 => 'Peupinah ôn nyoë',
'tooltip-ca-watch'                => 'Peutamah ôn nyoë u dapeuta kalön Droëneuh',
'tooltip-ca-unwatch'              => 'Sampôh ôn nyoë nibak dapeuta keunalön Droëneuh',
'tooltip-search'                  => 'Mita lam {{SITENAME}} nyoë',
'tooltip-search-go'               => 'Mita saboh ôn ngon nan nyang peureuséh lagèë nyoë meunyo na',
'tooltip-search-fulltext'         => 'Mita ôn nyang na asoë lagèë nyoë',
'tooltip-n-mainpage'              => 'Jak u Ôn Keuë',
'tooltip-n-mainpage-description'  => 'Saweuë Ôn Keuë',
'tooltip-n-portal'                => 'Bhah buët, peuë nyang jeuët neupeulaku, pat tamita sipeuë hay',
'tooltip-n-currentevents'         => 'Mita beurita nyang paléng barô',
'tooltip-n-recentchanges'         => 'Dapeuta nyang ban meu’ubah lam wiki.',
'tooltip-n-randompage'            => 'Peuleumah beurangkari ôn',
'tooltip-n-help'                  => 'Bak mita bantu.',
'tooltip-t-whatlinkshere'         => 'Dapeuta mandum ôn wiki nyang na hubông u ôn nyoë',
'tooltip-t-recentchangeslinked'   => 'Neuubah barô ôn-ôn nyang na hubông u ôn nyoë',
'tooltip-feed-rss'                => 'Umpeuën RSS keu ôn nyoë',
'tooltip-feed-atom'               => 'Umpeuën Atom keu ôn nyoë',
'tooltip-t-contributions'         => 'Eu dapeuta nyang ka geutuléh lé ureuëng nguy nyoë',
'tooltip-t-emailuser'             => 'Kirém surat-e u ureuëng nguy nyoë',
'tooltip-t-upload'                => 'Peutamong gamba atawa beureukah alat',
'tooltip-t-specialpages'          => 'Dapeuta ban dum ôn husôh',
'tooltip-t-print'                 => 'Seunalén citak ôn nyoë',
'tooltip-t-permalink'             => '
Hubông teutap keu revisi ôn nyoë',
'tooltip-ca-nstab-main'           => 'Eu ôn asoë',
'tooltip-ca-nstab-user'           => 'Eu ôn ureuëng nguy',
'tooltip-ca-nstab-special'        => 'Nyoë nakeuh ôn husôh nyang h’an jeuët geu’andam.',
'tooltip-ca-nstab-project'        => 'Eu ôn buët',
'tooltip-ca-nstab-image'          => 'Eu ôn beureukah',
'tooltip-ca-nstab-template'       => 'Eu templat',
'tooltip-ca-nstab-help'           => 'Eu ôn beunantu',
'tooltip-ca-nstab-category'       => 'Eu ôn kawan',
'tooltip-minoredit'               => 'Bôh tanda keu nyoë sibagoë andam bacut',
'tooltip-save'                    => 'Keubah neuubah Droëneuh',
'tooltip-preview'                 => 'Peuleumah neuubah Droëneuh, nguy nyoë sigohlom keubah!',
'tooltip-diff'                    => 'Peuleumah neuubah nyang ka Droëneuh peugöt',
'tooltip-compareselectedversions' => 'Ngiëng bida antara duwa curak ôn nyang jipilèh.',
'tooltip-watch'                   => 'Peutamah ôn nyoë u dapeuta keunalön Droëneuh',
'tooltip-rollback'                => 'Peuriwang neu’andam-neu’andam bak ôn nyoë u nyang tuléh keuneulheuëh lam sigo teugön',
'tooltip-undo'                    => 'Peuriwang geunantoë nyoë ngön peuhah plôk neu’andam ngön cara eu dilèë. Choë jeuët geupeutamah bak plôk reuningkah.',

# Browsing diffs
'previousdiff' => '← Bida away',
'nextdiff'     => 'Geunantoë lheuëh nyan →',

# Media information
'file-info-size'       => '($1 × $2 piksel, rayek beureukah: $3, MIME jeunèh: $4)',
'file-nohires'         => '<small>Hana resolusi nyang leubèh manyang.</small>',
'svg-long-desc'        => '(Beureukah SVG, nominal $1 x $2 piksel, rayek beureukah: $3)',
'show-big-image'       => 'Resolusi peunoh',
'show-big-image-thumb' => '<small>Rayek atra nyoë: $1 x $2 piksel</small>',

# Special:NewFiles
'newimages' => 'Beureukah barô',

# Bad image list
'bad_image_list' => 'Beuntukjih lagèë di miyub nyoë:

Cit buté dapeuta (baréh nyang geupeuphôn ngon tanda *) nyang geukira. Hubông phôn bak saboh baréh beukeu hubông u beureukah nyang brôk.
Hubông-hubông lheuëh nyan bak baréh nyang saban geukira sibagoë keucuali, nakeu teunuléh nyang jeuët peuleumah beureukah nyan.',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Beureukah nyoë na beurita tambahan nyang mungkén geutamah lé kamèra digital atawa peuminday nyang geunguy keu peugöt atawa peudigitalisasi beureukah. Meunyo beureukah nyoë ka geu’ubah, tapeusili nyang na mungkén hana seucara peunoh meurefleksikan beurita nibak gamba nyang ka geu’ubah nyoë.',
'metadata-expand'   => 'Peuleumah tapeusili teunamah',
'metadata-collapse' => 'Peusom tapeusili teunamah',
'metadata-fields'   => 'Èntri mètadata EXIF nyoë keuneuk geupeuleumah bak ôn beurita gamba meunyo tabel mètadata geupeusom. Èntri la’én seucara baku keuneuk geupeusom.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Andam beureukah nyoë ngön aplikasi luwa',
'edit-externally-help' => '(Ngiëng [http://meta.wikimedia.org/wiki/Help:External_editors arah atô] keu beurita leubèh lanjôt)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ban dum',
'namespacesall' => 'ban dum',
'monthsall'     => 'ban dum',

# Watchlist editing tools
'watchlisttools-view' => 'Peuleumah neuubah meuhubông',
'watchlisttools-edit' => 'Peuleumah ngön andam dapeuta kaeunalön',
'watchlisttools-raw'  => 'Andam dapeuta keunalön meuntah',

# Special:Version
'version' => 'Curak',

# Special:SpecialPages
'specialpages' => 'Ôn husôh',

);
