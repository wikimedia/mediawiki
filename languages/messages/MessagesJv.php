<?php
/** Javanese (Basa Jawa)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Anggoro
 * @author Helix84
 * @author Kaganer
 * @author Meursault2004
 * @author NoiX180
 * @author Pras
 * @author Rex
 * @author StefanusRA
 * @author לערי ריינהארט
 */

$fallback = 'id';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Astamiwa',
	NS_TALK             => 'Dhiskusi',
	NS_USER             => 'Panganggo',
	NS_USER_TALK        => 'Dhiskusi_Panganggo',
	NS_PROJECT_TALK     => 'Dhiskusi_$1',
	NS_FILE             => 'Gambar',
	NS_FILE_TALK        => 'Dhiskusi_Gambar',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Dhiskusi_MediaWiki',
	NS_TEMPLATE         => 'Cithakan',
	NS_TEMPLATE_TALK    => 'Dhiskusi_Cithakan',
	NS_HELP             => 'Pitulung',
	NS_HELP_TALK        => 'Dhiskusi_Pitulung',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Dhiskusi_Kategori',
);

$namespaceAliases = array(
	'Gambar_Dhiskusi' => NS_FILE_TALK,
	'MediaWiki_Dhiskusi' => NS_MEDIAWIKI_TALK,
	'Cithakan_Dhiskusi' => NS_TEMPLATE_TALK,
	'Pitulung_Dhiskusi' => NS_HELP_TALK,
	'Kategori_Dhiskusi' => NS_CATEGORY_TALK,
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Garisen ngisoré pranala:',
'tog-highlightbroken'         => 'Format pranala tugel <a href="" class="new">kaya iki</a> (pilihan: kaya iki<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Ratakna paragraf',
'tog-hideminor'               => 'Delikna suntingan cilik ing owah-owahan pungkasan',
'tog-hidepatrolled'           => 'Delikna suntingan sing wis dipatroli ing kaca owah-owahan',
'tog-newpageshidepatrolled'   => 'Delikna kaca sing wis dipatroli saka daftar kaca anyar',
'tog-extendwatchlist'         => 'Kembangna daftar pangawasan kanggo nuduhaké kabèh pangowahan, ora mung sing paling anyar',
'tog-usenewrc'                => 'Owah-owahané paguyuban miturut kaca nèng owah-owahan anyar lan daptar panto (mbutuhaké JavaScript)',
'tog-numberheadings'          => 'Wènèhana nomer judul secara otomatis',
'tog-showtoolbar'             => 'Tuduhna <em>toolbar</em> (batang piranti) panyuntingan',
'tog-editondblclick'          => 'Sunting kaca nganggo klik ping loro (JavaScript)',
'tog-editsection'             => 'Fungsèkna panyuntingan sub-bagian ngliwati pranala [sunting]',
'tog-editsectiononrightclick' => 'Fungsèkna panyuntingan sub-bagian mawa klik-tengen ing judul bagian (JavaScript)',
'tog-showtoc'                 => 'Tuduhna daftar isi (kanggo kaca sing nduwé luwih saka 3 sub-bagian)',
'tog-rememberpassword'        => 'Émut tembung sandi kula ing peramban punika (salebeting $1 {{PLURAL:$1|dinten|dinten}})',
'tog-watchcreations'          => 'Tambahaké kaca sing tak gawé lan berkas sing tak unggah nèng daptar pangawasan',
'tog-watchdefault'            => 'Tambahaké kaca lan berkas sing tak sunting nèng daptar pangawasan',
'tog-watchmoves'              => 'Tambahaké kaca lan berkas sing tak pindhahaké nèng daptar pangawasan',
'tog-watchdeletion'           => 'Tambahaké kaca lan berkas sing tak busak nèng daptar pangawasan',
'tog-minordefault'            => 'Tandhanana kabèh suntingan dadi suntingan cilik secara baku',
'tog-previewontop'            => 'Tuduhna pratayang sadurungé kothak sunting lan ora sawisé',
'tog-previewonfirst'          => 'Tuduhna pratayang ing suntingan kapisan',
'tog-nocache'                 => 'Nonaktifaken penyinggahan kaca peramban',
'tog-enotifwatchlistpages'    => 'Kirimi kula layang èlèktronik yèn ana kaca utawa berkas nèng daptar pangawasanku sing diowah',
'tog-enotifusertalkpages'     => 'Kirimana aku layang e-mail yèn kaca dhiskusiku owah',
'tog-enotifminoredits'        => 'Kirimi kula layang èlèktronik uga yèn ana suntingan cilik saka kaca lan berkas',
'tog-enotifrevealaddr'        => 'Kirimana aku layang e-mail ing layang notifikasi',
'tog-shownumberswatching'     => 'Tuduhna cacahé pangawas',
'tog-oldsig'                  => 'Tapak asma sing ana:',
'tog-fancysig'                => 'Anggepen tapak asta minangka teks wiki (tanpa pranala otomatis)',
'tog-externaleditor'          => 'Pigunakaken program pangolah tembung jawi (namung tumrap ahli, perlu pangaturan mligi ing komputer panjenengan. 
[//www.mediawiki.org/wiki/Manual:External_editors Informasi sajangkepipun].)',
'tog-externaldiff'            => 'Pigunakaken diff eksternal sacara bektan (namung tumrap para ahli, perlu pangaturan mligi ing komputer panjenengan.
[//www.mediawiki.org/wiki/Manual:External_editors Informasi sajangkepipun].)',
'tog-showjumplinks'           => 'Aktifna pranala pambiyantu "langsung menyang"',
'tog-uselivepreview'          => 'Nganggoa pratayang langsung (JavaScript) (eksperimental)',
'tog-forceeditsummary'        => 'Élingna aku menawa kothak ringkesan suntingan isih kosong',
'tog-watchlisthideown'        => 'Delikna suntinganku ing daftar pangawasan',
'tog-watchlisthidebots'       => 'Delikna suntingan ing daftar pangawasan',
'tog-watchlisthideminor'      => 'Delikna suntingan kecil di daftar pangawasan',
'tog-watchlisthideliu'        => 'Ngumpetaké suntingan panganggo sing mlebu log seka daftar pangawasan',
'tog-watchlisthideanons'      => 'Ngumpetaké suntingan panganggo anonim seka daftar pangawasan',
'tog-watchlisthidepatrolled'  => 'Delikna suntingan sing wis dipatroli saka daftar pangawasan',
'tog-nolangconversion'        => 'Patènana konvèrsi varian',
'tog-ccmeonemails'            => 'Kirimana aku salinan layang e-mail sing tak-kirimaké menyang wong liya',
'tog-diffonly'                => 'Aja dituduhaké isi kaca ing ngisor bédané suntingan',
'tog-showhiddencats'          => 'Tuduhna kategori sing didelikaké',
'tog-norollbackdiff'          => 'Lirwaaké prabédan sawusé nglakokaké sawijining pambalikan.',

'underline-always'  => 'Tansah',
'underline-never'   => 'Ora',
'underline-default' => 'Miturut konfigurasi panjlajah wèb',

# Font style option in Special:Preferences
'editfont-style'     => 'Modhèl aksara (font) ing kotak suntingan:',
'editfont-default'   => 'Standar panjelajah wèb',
'editfont-monospace' => 'Aksara (font) Monospace',
'editfont-sansserif' => 'Aksara (font) Sans-serif',
'editfont-serif'     => 'Aksara (font) Serif',

# Dates
'sunday'        => 'Minggu',
'monday'        => 'Senèn',
'tuesday'       => 'Slasa',
'wednesday'     => 'Rebo',
'thursday'      => 'Kemis',
'friday'        => 'Jemuwah',
'saturday'      => 'Setu',
'sun'           => 'Min',
'mon'           => 'Sen',
'tue'           => 'Sel',
'wed'           => 'Rab',
'thu'           => 'Kam',
'fri'           => 'Jem',
'sat'           => 'Set',
'january'       => 'Januari',
'february'      => 'Fébruari',
'march'         => 'Maret',
'april'         => 'April',
'may_long'      => 'Méi',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'Agustus',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Désèmber',
'january-gen'   => 'Januari',
'february-gen'  => 'Fébruari',
'march-gen'     => 'Maret',
'april-gen'     => 'April',
'may-gen'       => 'Méi',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'Agustus',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Désèmber',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Méi',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Agu',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Des',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategori|Kategori}}',
'category_header'                => 'Artikel ing kategori "$1"',
'subcategories'                  => 'Subkategori',
'category-media-header'          => 'Média ing kategori "$1"',
'category-empty'                 => "''Kategori iki saiki ora ngandhut artikel utawa média.''",
'hidden-categories'              => '{{PLURAL:$1|Kategori sing didelikaké|Kategori sing didelikaké}}',
'hidden-category-category'       => 'Kategori sing didelikaké',
'category-subcat-count'          => '{{PLURAL:$2|Kategori iki namung nduwé subkategori ing ngisor ikit.|Dituduhaké {{PLURAL:$1|subkategori|$1 subkategori}} sing kalebu ing kategori iki saka total $2.}}',
'category-subcat-count-limited'  => "Kategori iki ora duwé {{PLURAL:$1|subkategori|$1 subkategori}} ''berikut''.",
'category-article-count'         => '{{PLURAL:$2|Kategori iki namung ndarbèni kaca iki.|Dituduhaké {{PLURAL:$1|kaca|$1 kaca-kaca}} sing kalebu ing kategori iki saka gunggungé $2.}}',
'category-article-count-limited' => 'Kategori iki ngandhut {{PLURAL:$1|kaca|$1 kaca-kaca}} sing kapacak ing ngisor iki.',
'category-file-count'            => '{{PLURAL:$2|Kategori iki namung nduwé berkas iki.|Dituduhaké {{PLURAL:$1|berkas|$1 berkas-berkas}} sing kalebu ing kategori iki saka gunggungé $2.}}',
'category-file-count-limited'    => 'Kategori iki ndarbèni {{PLURAL:$1|berkas|$1 berkas-berkas}} sing kapacak ing ngisor iki.',
'listingcontinuesabbrev'         => 'samb.',
'index-category'                 => 'Kaca sing diindhèks',
'noindex-category'               => 'Kaca sing ora diindhèks',
'broken-file-category'           => 'Kaca kanthi pranala gambar rusak',

'about'         => 'Prakara',
'article'       => 'Artikel',
'newwindow'     => '(buka ing jendhéla anyar)',
'cancel'        => 'Batalna',
'moredotdotdot' => 'Liyané...',
'mypage'        => 'Kacaku',
'mytalk'        => 'Gunemanku',
'anontalk'      => 'Dhiskusi IP puniki',
'navigation'    => 'Pandhu Arah',
'and'           => '&#32;Lan',

# Cologne Blue skin
'qbfind'         => 'Golèk',
'qbbrowse'       => 'Navigasi',
'qbedit'         => 'Sunting',
'qbpageoptions'  => 'Kaca iki',
'qbpageinfo'     => 'Kontèks kaca',
'qbmyoptions'    => 'Opsiku',
'qbspecialpages' => 'Kaca-kaca astaméwa',
'faq'            => 'FAQ (Pitakonan sing kerep diajokaké)',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Tambah topik',
'vector-action-delete'           => 'Busak',
'vector-action-move'             => 'Pindhahna',
'vector-action-protect'          => 'Reksa',
'vector-action-undelete'         => 'Batalna pambusakan',
'vector-action-unprotect'        => 'Owahi pangreksan',
'vector-simplesearch-preference' => 'Aktifaken pamrayogi pamadosan ingkang kasampurnakaken (namung kulit vektor)',
'vector-view-create'             => 'Gawé',
'vector-view-edit'               => 'Sunting',
'vector-view-history'            => 'Sajarah kaca',
'vector-view-view'               => 'Waca',
'vector-view-viewsource'         => 'Pirsani sumber',
'actions'                        => 'Tindakan',
'namespaces'                     => 'Ruang jeneng',
'variants'                       => 'Varian',

'errorpagetitle'    => 'Kasalahan',
'returnto'          => 'Bali menyang $1.',
'tagline'           => 'Saka {{SITENAME}}',
'help'              => 'Pitulung',
'search'            => 'Panggolèkan',
'searchbutton'      => 'Golèk',
'go'                => 'Nuju menyang',
'searcharticle'     => 'Nuju menyang',
'history'           => 'Vèrsi sadurungé',
'history_short'     => 'Vèrsi lawas',
'updatedmarker'     => 'diowahi wiwit kunjungan pungkasanku',
'printableversion'  => 'Versi cithak',
'permalink'         => 'Pranala permanèn',
'print'             => 'Cithak',
'view'              => 'Pirsani',
'edit'              => 'Sunting',
'create'            => 'Nggawé',
'editthispage'      => 'Sunting kaca iki',
'create-this-page'  => 'Nggawé kaca iki',
'delete'            => 'Busak',
'deletethispage'    => 'Busak kaca iki',
'undelete_short'    => 'Batal busak $1 {{PLURAL:$1|suntingan|suntingan}}',
'viewdeleted_short' => 'Pirsani {{PLURAL:$1|suntingan|suntingan}} ingkang sampun kabusak',
'protect'           => 'Reksanen',
'protect_change'    => 'ngowahi reksanan',
'protectthispage'   => 'Reksanen kaca iki',
'unprotect'         => 'Owahi pangreksan',
'unprotectthispage' => 'Owahi pangreksan kaca iki',
'newpage'           => 'Kaca anyar',
'talkpage'          => 'Dhiskusèkna kaca iki',
'talkpagelinktext'  => 'Wicara',
'specialpage'       => 'Kaca astaméwa',
'personaltools'     => 'Piranti pribadi',
'postcomment'       => 'Bagéyan anyar',
'articlepage'       => 'nDeleng artikel',
'talk'              => 'Dhiskusi',
'views'             => 'Tampilan',
'toolbox'           => 'Kothak piranti',
'userpage'          => 'Ndeleng kaca panganggo',
'projectpage'       => 'Ndeleng kaca proyèk',
'imagepage'         => 'Deleng kaca berkas',
'mediawikipage'     => 'Ndeleng kaca pesen sistem',
'templatepage'      => 'Ndeleng kaca cithakan',
'viewhelppage'      => 'Ndeleng kaca pitulung',
'categorypage'      => 'Ndeleng kaca kategori',
'viewtalkpage'      => 'Ndeleng kaca dhiskusi',
'otherlanguages'    => 'Basa liya',
'redirectedfrom'    => '(Dialihkan dari $1)',
'redirectpagesub'   => 'Kaca pangalihan',
'lastmodifiedat'    => 'Kaca iki diowahi pungkasané nalika $2, $1.',
'viewcount'         => 'Kaca iki wis tau diaksès cacahé ping {{PLURAL:$1|siji|$1}}.',
'protectedpage'     => 'Kaca sing direksa',
'jumpto'            => 'Langsung menyang:',
'jumptonavigation'  => 'navigasi',
'jumptosearch'      => 'golèk',
'view-pool-error'   => 'Nyuwun ngapuro, peladèn lagi sibuk wektu iki.
Kakèhan panganggo sing nyoba mbukak kaca iki.
Entèni sedhéla sadurungé nyoba ngaksès kaca iki manèh .

$1',
'pool-timeout'      => 'Kelangkung wekdal nengga kunci',
'pool-queuefull'    => 'Kempalan antrian kebak',
'pool-errorunknown' => 'Kalepata ingkang mboten dipun mangertosi',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Prakara {{SITENAME}}',
'aboutpage'            => 'Project:Prakara',
'copyright'            => 'Kabèh tèks kasedyakaké miturut $1.',
'copyrightpage'        => '{{ns:project}}:Hak cipta',
'currentevents'        => 'Prastawa saiki',
'currentevents-url'    => 'Project:Prastawa saiki',
'disclaimers'          => 'Pamaidonan',
'disclaimerpage'       => 'Project:Panyangkalan umum',
'edithelp'             => 'Pitulung panyuntingan',
'edithelppage'         => 'Help:panyuntingan',
'helppage'             => 'Help:Isi',
'mainpage'             => 'Kaca Utama',
'mainpage-description' => 'Kaca Utama',
'policy-url'           => 'Project:Kabijakan',
'portal'               => 'Gapura komunitas',
'portal-url'           => 'Project:Portal komunitas',
'privacy'              => 'Kebijakan privasi',
'privacypage'          => 'Project:Kabijakan privasi',

'badaccess'        => 'Aksès ora olèh',
'badaccess-group0' => 'Panjenengan ora pareng nglakokaké tindhakan sing panjenengan gayuh.',
'badaccess-groups' => 'Pratingkah panjenengan diwatesi tumrap panganggo ing {{PLURAL:$2|klompoké|klompoké}}: $1.',

'versionrequired'     => 'Dibutuhaké MediaWiki vèrsi $1',
'versionrequiredtext' => 'MediaWiki vèrsi $1 dibutuhaké kanggo nggunakaké kaca iki. Mangga mirsani [[Special:Version|kaca iki]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Sumber artikel iki saka kaca situs web: "$1"',
'youhavenewmessages'      => 'Panjenengan kagungan $1 ($2).',
'newmessageslink'         => 'warta énggal',
'newmessagesdifflink'     => 'mirsani bédané saka révisi sadurungé',
'youhavenewmessagesmulti' => 'Panjenengan olèh pesen-pesen anyar $1',
'editsection'             => 'sunting',
'editold'                 => 'sunting',
'viewsourceold'           => 'deleng sumber',
'editlink'                => 'sunting',
'viewsourcelink'          => 'deleng sumber',
'editsectionhint'         => 'Sunting bagian: $1',
'toc'                     => 'Bab lan paragraf',
'showtoc'                 => 'tuduhna',
'hidetoc'                 => 'delikna',
'collapsible-collapse'    => 'Singidaken',
'collapsible-expand'      => 'Tuduhna',
'thisisdeleted'           => 'Mirsani utawa mbalèkaké $1?',
'viewdeleted'             => 'Mirsani $1?',
'restorelink'             => '$1 {{PLURAL:$1|suntingan|suntingan}} sing wis kabusak',
'feedlinks'               => 'Asupan:',
'feed-invalid'            => 'Tipe permintaan asupan ora bener.',
'feed-unavailable'        => "Umpan sindikasi (''syndication feeds'') ora kasedyakaké",
'site-rss-feed'           => "$1 ''RSS Feed''",
'site-atom-feed'          => "$1 ''Atom Feed''",
'page-rss-feed'           => "\"\$1\" ''RSS Feed''",
'page-atom-feed'          => "\"\$1\" ''Atom Feed''",
'red-link-title'          => '$1 (kaca durung ana)',
'sort-descending'         => 'Urutaké medhun',
'sort-ascending'          => 'Urutaké munggah',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Panganggo',
'nstab-media'     => 'Media',
'nstab-special'   => 'Istiméwa',
'nstab-project'   => 'Proyek',
'nstab-image'     => 'Gambar',
'nstab-mediawiki' => 'Pariwara',
'nstab-template'  => 'Cithak',
'nstab-help'      => 'Pitulung',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Ora ana pratingkah kaya ngono',
'nosuchactiontext'  => 'Pratingkah sing dirinci déning URL ora sah.
Panjenengan manawa salah ketik nalika ngisi URL, utawa salah ngisi pranala.
Iki manawa uga nuduhaké anané kesalahan ing piranti alus sing dipigunakaké déning {{SITENAME}}.',
'nosuchspecialpage' => 'Ora ana kaca astaméwa kaya ngono',
'nospecialpagetext' => 'Panjenengan nyuwun kaca astaméwa sing ora sah. Daftar kaca astaméwa sing sah bisa dipirsani ing [[Special:SpecialPages|daftar kaca astaméwa]].',

# General errors
'error'                => 'Kasalahan',
'databaseerror'        => 'Kasalahan database',
'dberrortext'          => 'Ana kasalahan sintaks ing panyuwunan basis data.
Kasalahan iki mbokmenawa nuduhaké anané \'\'bug\'\' ing software.
Panyuwunan basis data sing pungkasan yakuwi: <blockquote><tt>$1</tt></blockquote>
saka jroning fungsi "<tt>$2</tt>".
Basis data ngasilaké kasalahan "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ana kasalahan sintaks ing panyuwunan basis data.
Panyuwunan basis data sing pungkasan iku:
"$1"
saka jroning fungsi "$2".
Basis data ngasilaké kasalahan "$3: $4".',
'laggedslavemode'      => 'Pènget: Kaca iki mbokmenawa isiné dudu pangowahan pungkasan.',
'readonly'             => 'Database dikunci',
'enterlockreason'      => 'Lebokna alesan panguncèn, kalebu uga prakiran kapan kunci bakal dibuka',
'readonlytext'         => 'Database lagi dikunci marang panampan anyar. Pangurus sing ngunci mènèhi katrangan kaya mangkéné: <p>$1',
'missing-article'      => "Basis data ora bisa nemokaké tèks kaca sing kuduné ana, yaiku \"\$1\" \$2.
Bab iki bisasané disebabaké déning pranala daluwarsa menyang revisi sadurungé kaca sing wis dibusak.
Yèn dudu iki panyebabé, panjenengan manawa bisa nemokaké kasalahan (''bug'') jroning piranti alus (''software''). Mangga dilapuraké bab iki menyang [[Special:ListUsers/sysop|administrator]], kanthi nyebutaké alamat URL sing dituju",
'missingarticle-rev'   => '(révisi#: $1)',
'missingarticle-diff'  => '(Béda: $1, $2)',
'readonly_lag'         => 'Database wis dikunci mawa otomatis sawetara database sékundhèr lagi nglakoni sinkronisasi mawa database utama',
'internalerror'        => 'Kasalahan internal',
'internalerror_info'   => 'Kaluputan internal: $1',
'fileappenderrorread'  => 'Ora bisa maca "$1" nalika nambahi',
'fileappenderror'      => 'Ora bisa nglebokaké "$1" menyang "$2".',
'filecopyerror'        => 'Ora bisa nulad berkas "$1" menyang "$2".',
'filerenameerror'      => 'Ora bisa ngowahi saka "$1" dadi "$2".',
'filedeleteerror'      => 'Ora bisa mbusak berkas "$1".',
'directorycreateerror' => 'Ora bisa nggawé dirèktori "$1".',
'filenotfound'         => 'Ora bisa nemokaké berkas "$1".',
'fileexistserror'      => 'Ora bisa nulis berkas "$1": berkas wis ana',
'unexpected'           => 'Biji (\'\'nilai\'\') ing njabaning jangkauan: "$1"="$2".',
'formerror'            => 'Kasalahan: Ora bisa ngirimaké formulir',
'badarticleerror'      => 'Pratingkah iku ora bisa katindhakaké ing kaca iki.',
'cannotdelete'         => 'Kaca utawa berkas "$1" ora bisa dibusak.
Manawa wis dibusak déning wong liya.',
'cannotdelete-title'   => 'Ora bisa mbusak kaca "$1"',
'badtitle'             => 'Judhulé ora sah',
'badtitletext'         => 'Judhul kaca sing panjenengan ora bisa dituduhaké, kosong, utawa dadi judhul antar-basa utawa judhul antar-wiki. Iku bisa uga ana  sawijining utawa luwih aksara sing ora bisa didadèkaké judhul.',
'perfcached'           => 'Data iki mung dijupuk saka papan singgahan lan mungkin ora kaanyaran. Maksimum {{PLURAL:$1|sak asil|$1 asil}} sumadhiya nèng papan singgahan.',
'perfcachedts'         => 'Data iki mung dijupuk saka papan singgahan lan mungkin dianyari pungkasan $1. Maksimum {{PLURAL:$4|sak asil|$4 asil}} sumadhiya nèng papan singgahan.',
'querypage-no-updates' => 'Update saka kaca iki lagi dipatèni. Data sing ana ing kéné saiki ora bisa bakal dibalèni unggah manèh.',
'wrong_wfQuery_params' => 'Parameter salah menyang wfQuery()<br />Fungsi: $1<br />Panyuwunan: $2',
'viewsource'           => 'Tuduhna sumber',
'viewsource-title'     => 'Delok sumberé $1',
'actionthrottled'      => 'Tindakan diwatesi',
'actionthrottledtext'  => 'Minangka sawijining pepesthèn anti-spam, panjenengan diwatesi nglakoni tindhakan iki sing cacahé kakèhan ing wektu cendhak.
Mangga dicoba manèh ing sawetara menit.',
'protectedpagetext'    => 'Kaca iki dikunci supaya ora disunting.',
'viewsourcetext'       => 'Panjenengan bisa mirsani utawa nulad sumber kaca iki:',
'viewyourtext'         => "Sampéyan bisa ndelok lan nyalin sumber '''suntingan Sampéyan''' nèng kaca iki:",
'protectedinterface'   => 'Kaca iki isiné tèks antarmuka sing dienggo software lan wis dikunci kanggo menghindari kasalahan.',
'editinginterface'     => "'''Pènget:''' Panjenengan nyunting kaca sing dianggo nyedyakaké tèks antarmuka kanggo piranti alus.
Pangowahan kaca iki bakal awèh pangaruh marang tampilan antarmuka panganggo kanggoné panganggo liya.
Kanggo terjemahan, mangga nganggo [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], proyèk lokalisasi MediaWiki.",
'sqlhidden'            => '(Panyuwunan SQL didelikaké)',
'cascadeprotected'     => 'Kaca iki wis direksa saka panyuntingan amerga disertakaké ing {{PLURAL:$1|kaca|kaca-kaca}} ngisor iki sing wis direksa mawa opsi "runtun" diaktifaké:
$2',
'namespaceprotected'   => "Panjenengan ora kagungan idin kanggo nyunting kaca ing bilik nama '''$1'''.",
'customcssprotected'   => 'Sampéyan ora dililakaké nyunting kaca CSS iki amarga kaisi pangaturan pribadi saka panganggo liya.',
'customjsprotected'    => 'Sampéyan ora dililakaké nyunting kaca JavaScript iki amarga kaisi pangaturan pribadi saka panganggo liya.',
'ns-specialprotected'  => 'Kaca ing bilik nama astaméwa utawa kusus, ora bisa disunting.',
'titleprotected'       => "Irah-irahan iki direksa ora olèh digawé déning [[User:$1|$1]].
Alesané yaiku ''$2''.",

# Virus scanner
'virus-badscanner'     => "Kasalahan konfigurasi: pamindai virus ora dikenal: ''$1''",
'virus-scanfailed'     => "''Pemindaian'' utawa ''scan'' gagal (kode $1)",
'virus-unknownscanner' => 'Antivirus ora ditepungi:',

# Login and logout pages
'logouttext'                 => "'''Sampéyan wis metu log'''

Sampéyan bisa nganggo {{SITENAME}} sacara anonim, utawa bisa [[Special:UserLogin|mlebu log manèh]] kanthi jeneng panganggo sing padha utawa beda.

Cathet yèn sapérangan kaca mungkin isih nampilaké tulisan yèn Sampéyan isih nèng njero log, kuwi bisa ilang yèn Sampéyan ngresiki ''cache'' pramban Sampéyan.",
'welcomecreation'            => '== Sugeng rawuh, $1! ==

Akun panjenengan wis kacipta. Aja lali nata konfigurasi [[Special:Preferences|preferensi {{SITENAME}}]] panjenengan.',
'yourname'                   => 'Asma pangangeman',
'yourpassword'               => 'Tembung sandhi:',
'yourpasswordagain'          => 'Balènana tembung sandhi',
'remembermypassword'         => 'Émut tembung sandi kula (salebeting $1 {{PLURAL:$1|dinten|dinten}})',
'securelogin-stick-https'    => 'Tetep kahubung dhumateng HTTPS sasampunipun mlebet log',
'yourdomainname'             => 'Dhomain panjenengan',
'externaldberror'            => 'Ana kasalahan otèntikasi basis dhata èksternal utawa panjenengan ora pareng nglakoni pemutakhiran marang akun èksternal panjenengan.',
'login'                      => 'Mlebu log',
'nav-login-createaccount'    => 'Log mlebu / nggawé rékening (akun)',
'loginprompt'                => "Panjenengan kudu ngaktifaké ''cookies'' supaya bisa mlebu (log in) ing {{SITENAME}}.",
'userlogin'                  => 'Mlebu log / gawé rékening (akun)',
'userloginnocreate'          => 'Mlebu log',
'logout'                     => 'Oncat',
'userlogout'                 => 'Metu log',
'notloggedin'                => 'Durung mlebu log',
'nologin'                    => "Durung kagungan asma panganggo? '''$1'''.",
'nologinlink'                => 'Ndaftaraké akun anyar',
'createaccount'              => 'Nggawé akun anyar',
'gotaccount'                 => "Wis kagungan akun? '''$1'''.",
'gotaccountlink'             => 'Mlebu',
'userlogin-resetlink'        => 'Lali rincian mlebu log Sampéyan?',
'createaccountmail'          => 'liwat layang e-mail',
'createaccountreason'        => 'Alesan:',
'badretype'                  => 'Sandhi panjenengan ora gathuk',
'userexists'                 => 'Jeneng panganggo sing dilebokaké lagi dianggo.
Mangga pilih jeneng liya.',
'loginerror'                 => 'Kasalahan mlebu log',
'createaccounterror'         => 'Ora bisa nyipta akun: $1',
'nocookiesnew'               => "Rékening utawa akun panganggo panjenengan wis digawé, nanging panjenengan durung mlebu log. {{SITENAME}} nggunakaké ''cookies'' kanggo  log panganggo. ''Cookies'' ing panjlajah wèb panjengengan dipatèni. Mangga diaktifaké lan mlebu log manèh mawa jeneng panganggo lan tembung sandhi panjenengan.",
'nocookieslogin'             => "{{SITENAME}} nggunakaké ''cookies'' kanggo log panganggoné. ''Cookies'' ing panjlajah wèb panjenengan dipatèni. Mangga ngaktifaké manèh lan coba manèh.",
'nocookiesfornew'            => 'Akun panganggé boten kadamel, amargi kita boten saged mesthèkaken sumberipun.
Pesthèkaken panjenengan sampun ngaktifaken kuki, lajeng amot malih kaca punika lan cobi malih.',
'noname'                     => 'Asma panganggo sing panjenengan pilih ora sah.',
'loginsuccesstitle'          => 'Bisa suksès mlebu log',
'loginsuccess'               => "'''Panjenengan saiki mlebu ing {{SITENAME}} kanthi asma \"\$1\".'''",
'nosuchuser'                 => 'Ora ana panganggo mawa asma "$1".
Jeneng panganggo iku mbédakaké kapitalisasi.
Coba dipriksa manèh pasang aksarané, utawa [[Special:UserLogin/signup|gawé akun anyar]].',
'nosuchusershort'            => 'Ora ana panganggo mawa asma "$1". Coba dipriksa manèh pasang aksarané (éjaané).',
'nouserspecified'            => 'Panjenengan kudu milih asma panganggo.',
'login-userblocked'          => 'Panganggé punika dipunblok. Login boten dipunidinaken',
'wrongpassword'              => 'Tembung sandhi sing dipilih salah. Mangga coba manèh.',
'wrongpasswordempty'         => 'Panjenengan ora milih tembung sandhi. Mangga dicoba manèh.',
'passwordtooshort'           => 'Tembung sesinglon paling sethithik cacahé {{PLURAL:$1|1 aksara|$1 aksara}}.',
'password-name-match'        => 'Tembung sandi panjenengan kudu béda karo jeneng panganggo panjenengan.',
'password-login-forbidden'   => 'Pangginaan nami panganggé lan sandi puniki sampun kapenggak.',
'mailmypassword'             => 'Kirim tembung sandhi anyar',
'passwordremindertitle'      => 'Pèngetan tembung sandhi saka {{SITENAME}}',
'passwordremindertext'       => 'Ana wong (mbokmanawa panjenengan dhéwé, saka alamat IP $1) nyuwun supaya dikirimi tembung sandhi anyar kanggo {{SITENAME}} ($4). Tembung sandi sawetara kanggo panganggo "$2" wis digawé lan saiki "$3". Yèn panjenengan pancèn nggayuh iki, mangga énggal mlebu log lan ngganti tembung sandi saiki.
Tembung sandi sawetara mau bakal kadaluwarsa ing {{PLURAL:$5|sadina|$5 dina}}.
Yèn wong liya sing nglakoni panyuwunan iki, utawa panjenengan éling tembung sandi panjenengan, lan ora kepéngin ngowahi, panjenengan ora usah nggubris pesen iki lan bisa tetep nganggo tembung sandi lawas.',
'noemail'                    => 'Ora ana alamat layang e-mail sing kacathet kanggo panganggo "$1".',
'noemailcreate'              => 'Panjenengan kudu maringi alamat e-mail sing absah',
'passwordsent'               => 'Tembung sandhi anyar wis dikirim menyang alamat layang e-mail panjenengan sing wis didaftar kanggo "$1". Mangga mlebu log manèh sawisé nampa e-mail iku.',
'blocked-mailpassword'       => "Alamat IP panjenengan diblokir saka panyuntingan, mulané panjenengan ora olèh nganggo fungsi pèngetan tembung sandhi kanggo ''mencegah penyalahgunaan''.",
'eauthentsent'               => 'Sawijining layang élèktronik (e-mail) kanggo ndhedhes (konfirmasi) wis dikirim menyang alamat layang élèktronik panjenengan. Panjenengan kudu nuruti instruksi sajroning layang iku kanggo ndhedhes yèn alamat iku bener kagungané panjenengan. {{SITENAME}} ora bakal ngaktifaké fitur layang élèktronik yèn langkah iki durung dilakoni.',
'throttled-mailpassword'     => 'Sawijining pènget tembung sandi wis dikirim, jroning {{PLURAL:$1|jam|$1 jam}} pungkasan iki.
Kanggo nyegah salah-guna, mung siji pènget tembung sandi waé sing bisa dikirim saben {{PLURAL:$1|jam|$1 jam}}.',
'mailerror'                  => 'Kasalahan ing ngirimaké layang e-mail: $1',
'acct_creation_throttle_hit' => 'Tamu ing wiki iki kanthi alamat IP sing padha karo panjenengan wis gawé {{PLURAL:$1|1 akun|$1 akun}} ing sadina pungkasan, nganti cacah maksimum sing diidinaké.
Amarga saka kuwi., tamu kanthi alamat IP iki ora bisa gawé akun manèh kanggo sauntara iki.',
'emailauthenticated'         => 'Alamat layang élèktronik (e-mail) panjenengan wis didhedhes (dikonfirmasi) ing $3, $2.',
'emailnotauthenticated'      => 'Alamat layang élèktronik panjenengan durung didhedhes (dikonfirmasi). Sadurungé didhedhes, panjenengan ora bisa nganggo fitur layang élèktronik (e-mail).',
'noemailprefs'               => 'Panjenengan kudu milih alamat e-mail supaya bisa nganggo fitur iki.',
'emailconfirmlink'           => 'Ndhedhes (konfirmasi) alamat e-mail panjenengan',
'invalidemailaddress'        => 'Alamat e-mail iki ora bisa ditampa amarga formaté ora bener. Tulung lebokna alamat mawa format sing bener utawa kosongaké waé isèn kasebut.',
'cannotchangeemail'          => 'Alamat layang èlèktronik akun ora bisa diganti nèng wiki iki.',
'accountcreated'             => 'Akun wis kacipta.',
'accountcreatedtext'         => 'Akun kanggo $1 wis kacipta.',
'createaccount-title'        => 'Gawé rékening kanggo {{SITENAME}}',
'createaccount-text'         => 'Ana wong sing nggawé sawijining akun utawa rékening kanggo alamat e-mail panjenengan ing {{SITENAME}} ($4) mawa jeneng "$2" lan tembung sandi "$3". Panjenengan disaranaké kanggo mlebu log lan ngganti tembung sandi panjenengan saiki.

Panjenengan bisa nglirwakaké pesen iki yèn akun utawa rékening iki digawé déné sawijining kaluputan.',
'usernamehasherror'          => 'Jeneng panganggo ora bisa ngandhut tandha pager',
'login-throttled'            => 'Panjenengan wis kakèhan njajal mlebu log.
Tulung nunggu dhisik sadurungé njajal manèh.',
'login-abort-generic'        => 'Sampéyan ora suksès mlebu log - Dibatalaké',
'loginlanguagelabel'         => 'Basa: $1',
'suspicious-userlogout'      => 'Panjaluk panjenengan supaya metu ditolak amarga katoné panjlajah internt utawa proksi panyinggah.',

# E-mail sending
'php-mail-error-unknown' => 'Kasalahan ora dingertèni nèng piguna mail() PHP.',
'user-mail-no-addy'      => 'Njajal ngirim layang èlèktronik tanpa alamat layang èlèktronik.',

# Change password dialog
'resetpass'                 => 'Ganti tembung sandi',
'resetpass_announce'        => 'Panjenengan wis mlebu log mawa kodhe sementara sing dikirim mawa e-mail. Menawa kersa nglanjutaké, panjenengan kudu milih tembung sandhi anyar ing kéné:',
'resetpass_text'            => '<!-- Tambahaké teks ing kéné -->',
'resetpass_header'          => 'Ganti tembung sandi akun',
'oldpassword'               => 'Tembung sandi lawas:',
'newpassword'               => 'Tembung sandi anyar:',
'retypenew'                 => 'Ketik ulang tembung sandi anyar:',
'resetpass_submit'          => 'Nata tembung sandhi lan mlebu log',
'resetpass_success'         => 'Tembung sandhi panjenengan wis suksès diowahi! Saiki mrosès mlebu log panjenengan...',
'resetpass_forbidden'       => 'Tembung sandhi ora bisa diganti',
'resetpass-no-info'         => 'Panjenengan kudu mlebu log kanggo ngaksès kaca iki sacara langsung.',
'resetpass-submit-loggedin' => 'Ganti tembung sandi',
'resetpass-submit-cancel'   => 'Batal',
'resetpass-wrong-oldpass'   => 'Tembung sandi ora sah.
Panjengen manawa wis kasil ganti tembung sandi utawa nyuwun tembung sandi sauntara sing anyar.',
'resetpass-temp-password'   => 'Tembung sandi sauntara:',

# Special:PasswordReset
'passwordreset'                    => 'Balèni setèl tembung sandhi',
'passwordreset-text'               => 'Ganepi pormulir iki kanggo nampa pangéling layang èlèktronik kanggo rincian akun Sampéyan.',
'passwordreset-legend'             => 'Balèni setèl tembung sandhi',
'passwordreset-disabled'           => 'Piranti kanggo mbalèni nyetèl tembung sandhi dipatèni nèng wiki iki.',
'passwordreset-pretext'            => '{{PLURAL:$1||Lebokaké siji bagéyan data ngisor iki}}',
'passwordreset-username'           => 'Jeneng panganggo:',
'passwordreset-domain'             => 'Domain:',
'passwordreset-capture'            => 'Delok layang èlèktronik sing diasilaké?',
'passwordreset-capture-help'       => 'Yèn Sampéyan nyentang kothak iki, layang èlèktronik (mawa tembung sandhi sawetara) bakal ditampilaké nèng Sampéyan lan uga dikirim nèng panganggo.',
'passwordreset-email'              => 'Alamat layang èlèktronik:',
'passwordreset-emailtitle'         => 'Rincian akun nèng {{SITENAME}}',
'passwordreset-emailelement'       => 'Jeneng panganggo: $1
Tembung sandhi sawetara: $2',
'passwordreset-emailsent'          => 'Layang èlèktronik pangèling wis dikirim.',
'passwordreset-emailsent-capture'  => 'Layang èlèktronik pangèling wis dikirim kaya ngisor iki.',
'passwordreset-emailerror-capture' => 'Layang èlèktronik pangèling ditampilaké nèng ngisor iki, nanging ora kasil dikirim: $1',

# Special:ChangeEmail
'changeemail'          => 'Ganti alamat layang èlèktronik',
'changeemail-header'   => 'Ganti alamat layang èlèktronik akun',
'changeemail-text'     => 'Rampungaké pormulir iki kanggo ngganti alamat layang èlèktronik Sampéyan. Sampéyan bakal butuh nglebokaké tembung sandhi Sampéyan kanggo pepesthèn owahan kuwi.',
'changeemail-no-info'  => 'Sampéyan kudu mlebu log kanggo ngaksès kaca iki langsung.',
'changeemail-oldemail' => 'Alamat layang èlèktronik saiki:',
'changeemail-newemail' => 'Alamat layang èlèktronik anyar:',
'changeemail-none'     => '(ora ana)',
'changeemail-submit'   => 'Ganti layang èlèktronik',
'changeemail-cancel'   => 'Batal',

# Edit page toolbar
'bold_sample'     => 'Tèks iki bakal dicithak kandel',
'bold_tip'        => 'Cithak kandel',
'italic_sample'   => 'Tèks iki bakal dicithak miring',
'italic_tip'      => 'Cithak miring',
'link_sample'     => 'Judhul pranala',
'link_tip'        => 'Pranala njero',
'extlink_sample'  => 'http://www.example.com judhul pranala',
'extlink_tip'     => 'Pranala njaba (aja lali wiwitan http:// )',
'headline_sample' => 'Tèks judhul',
'headline_tip'    => 'Subbagian tingkat 1',
'nowiki_sample'   => 'Tèks iki ora bakal diformat',
'nowiki_tip'      => 'Aja nganggo format wiki',
'image_sample'    => 'Conto.jpg',
'image_tip'       => 'Mènèhi gambar/berkas',
'media_sample'    => 'Conto.ogg',
'media_tip'       => 'Pranala berkas media',
'sig_tip'         => 'Tapak asta panjenengan mawa tandha wektu',
'hr_tip'          => 'Garis horisontal',

# Edit pages
'summary'                          => 'Ringkesan:',
'subject'                          => 'Subyek/judhul:',
'minoredit'                        => 'Iki suntingan cilik.',
'watchthis'                        => 'Awasana kaca iki',
'savearticle'                      => 'Simpen kaca',
'preview'                          => 'Pratayang',
'showpreview'                      => 'Mirsani pratayang',
'showlivepreview'                  => 'Pratayang langsung',
'showdiff'                         => 'Tuduhna pangowahan',
'anoneditwarning'                  => 'Panjenengan ora kadaftar mlebu. Alamat IP panjenengan bakal kacathet ing sajarah panyuntingan kaca iki.',
'anonpreviewwarning'               => "''Sampéyan durung mlebu log. Nyimpen bakal nyathet alamat IP Sampéyan nèng riwayat sunting kaca iki.''",
'missingsummary'                   => "'''Pènget:''' Panjenengan ora nglebokaké ringkesan panyuntingan. Menawa panjenengan mencèt tombol Simpen manèh, suntingan panjenengan bakal kasimpen tanpa ringkesan panyuntingan.",
'missingcommenttext'               => 'Tulung lebokna komentar ing ngisor iki.',
'missingcommentheader'             => "'''Pangéling:''' Sampéyan durung nyadhiyakaké judhul/jejer kanggo tanggepan iki.
Yèn Sampéyan klik \"{{int:savearticle}}\" manèh, suntingan Sampéyan bakal kasimpen tanpa kuwi.",
'summary-preview'                  => 'Pratayang ringkesan:',
'subject-preview'                  => 'Pratayang subyèk/judhul:',
'blockedtitle'                     => 'Panganggo diblokir',
'blockedtext'                      => "'''Asma panganggo utawa alamat IP panjenengan diblokir.'''

Blokir iki sing nglakoni $1.
Alesané ''$2''.

* Diblokir wiwit: $8
* Kadaluwarsa pemblokiran ing: $6
* Sing arep diblokir: $7

Panjenengan bisa ngubungi $1 utawa [[{{MediaWiki:Grouppage-sysop}}|pangurus liyané]] kanggo ngomongaké prakara iki.

Panjenengan ora bisa nggunakaké fitur 'Kirim layang e-mail panganggo iki' kejaba panjenengan wis nglebokaké alamat e-mail sing sah ing [[Special:Preferences|préferènsi]] panjenengan.

Alamat IP panjenengan iku $3, lan ID pamblokiran iku #$5.
Tulung kabèh informasi ing ndhuwur iki disertakaké ing saben pitakon panjenengan.",
'autoblockedtext'                  => 'Alamat IP panjenangan wis diblokir minangka otomatis amerga dienggo déning panganggo liyané. Pamblokiran dilakoni déning $1 mawa alesan:

:\'\'$2\'\'

* Diblokir wiwit: $8
* Blokir kadaluwarsa ing: $6
* Sing dikarepaké diblokir: $7

Panjenengan bisa ngubungi $1 utawa [[{{MediaWiki:Grouppage-sysop}}|pangurus liyané]] kanggo ngomongaké perkara iki.

Panjenengan ora bisa nganggo fitur "kirim e-mail panganggo iki" kejaba panjenengan wis nglebokaké alamat e-mail sing sah ing [[Special:Preferences|préferènsi]] panjenengan lan panjenengan wis diblokir kanggo nggunakaké.

ID pamblokiran panjenengan iku #$5 lan alamat IP panjenengan iku $3. Tulung sertakna informasi ing dhuwur kabèh iki saben ngajokaké pitakonan panjenengan. Matur nuwun.',
'blockednoreason'                  => 'ora ana alesan sing diwènèhaké',
'whitelistedittext'                => 'Panjenengan kudu $1 supaya bisa nyunting artikel.',
'confirmedittext'                  => 'Panjenengan kudu ndhedhes alamat e-mail dhisik sadurungé pareng nyunting sawijining kaca. Mangga nglebokaké lan validasi alamat e-mail panjenengan sadurungé nglakoni panyuntingan. Alamat e-mail sawisé bisa diowahi liwat [[Special:Preferences|kaca préférènsi]]',
'nosuchsectiontitle'               => 'Bagéan ora ditemokaké',
'nosuchsectiontext'                => 'Panjenengan nyoba nyunting sawijining bagéan sing ora ana.
Bagéan iki manawa wis dipindhah utawa dibusak nalika panjenengan buka.',
'loginreqtitle'                    => 'Mangga mlebu log',
'loginreqlink'                     => 'mlebu log',
'loginreqpagetext'                 => 'Panjenengan kudu $1 kanggo bisa mirsani kaca liyané.',
'accmailtitle'                     => 'Tembung sandhi wis dikirim.',
'accmailtext'                      => "Sawijining tembung sandi acak kanggo [[User talk:$1|$1]] wis digawé lan dikirim menyang $2.

Tembung sandi kanggo akun anyar iki bisa diganti ing kaca ''[[Special:ChangePassword|ganti tembung sandi]]'' sawisé mlebu log.",
'newarticle'                       => '(Anyar)',
'newarticletext'                   => "Katonané panjenengan ngetutaké pranala artikel sing durung ana.
Manawa kersa manulis artikel iki, manggaa. (Mangga mirsani [[{{MediaWiki:Helppage}}|Pitulung]] kanggo informasi sabanjuré).
Yèn ora sengaja tekan kéné, bisa ngeklik pencètan '''back''' waé ing panjlajah wèb panjenengan.",
'anontalkpagetext'                 => "---- ''Iki yaiku kaca dhiskusi sawijining panganggo anonim sing durung kagungan akun utawa ora nganggo akuné, dadi kita keeksa kudu nganggo alamat IP-né kanggo nepangi. Alamat IP kaya mengkéné iki bisa dienggo déning panganggo sing séjé-séjé. Yèn panjenengan pancèn panganggo anonim lan olèh komentar-komentar miring, mangga [[Special:UserLogin/signup|nggawé akun]] utawa [[Special:UserLogin|log mlebu]] supaya ora rancu karo panganggo anonim liyané ing mangsa ngarep.''",
'noarticletext'                    => 'Saiki ora ana tèks ing kaca iki. Panjenengan bisa [[Special:Search/{{PAGENAME}}|nglakoni panggolèkan kanggo judhul iki kaca iki]] ing kaca-kaca liyané, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|kaca={{urlencode:{{FULLPAGENAME}}}}}} nggolèki log kagandhèng],
utawa [{{fullurl:{{FULLPAGENAME}}|action=edit}} nyunting kaca iki]</span>.',
'noarticletext-nopermission'       => 'Saiki ora ana tèks ing kaca iki. 
Sampéyan bisa [[Special:Search/{{PAGENAME}}|nggolèki judhul kaca iki]] nèng kaca liya, 
utawa <span class="plainlinks">[{{fullurl:{{#Special:Log}}|kaca={{urlencode:{{FULLPAGENAME}}}}}} nggolèki log sing kaitan].',
'userpage-userdoesnotexist'        => 'Akun utawa rékening panganggo "<nowiki>$1</nowiki>" ora kadaftar.',
'userpage-userdoesnotexist-view'   => 'Panganggo "$1" ora kadhaptar.',
'blocked-notice-logextract'        => 'Panganggo iki saiki lagi diblokir.
Log pamblokiran pungkasan dituduhaké ing ngisor iki minangka bahan rujukan:',
'clearyourcache'                   => "'''Cathetan:''' Sawisé nyimpen préférènsi, panjenengan prelu ngresiki <em>cache</em> panjlajah wèb panjenengan kanggo mirsani pangowahan. '''Mozilla / Firefox / Safari:''' pencèt ''Ctrl-Shift-R'' (''Cmd-Shift-R'' pada Apple Mac); '''IE:''' tekan ''Ctrl-F5''; '''Konqueror:''': pencèt ''F5''; '''Opera''' resikana <em>cache</em> miturut menu ''Tools→Preferences''.",
'usercssyoucanpreview'             => "'''Tips:''' Gunakna tombol \"{{int:showpreview}}\" kanggo ngetès CSS anyar panjenengan sadurungé disimpen.",
'userjsyoucanpreview'              => "'''Tips:''' Gunakna tombol \"{{int:showpreview}}\" kanggo ngetès JavaScript anyar panjenengan sadurungé disimpen.",
'usercsspreview'                   => "'''Pèngeten yèn panjenengan namung mirsani pratilik CSS panjenengan.''''
'''Pratilik iku durung kasimpen!'''",
'userjspreview'                    => "'''Pèngeten yèn sing panjenengan pirsani namung pratilik JavaScript panjenengan, lan menawa pratilik iku dèrèng kasimpen!'''",
'sitecsspreview'                   => "'''Èling yèn Sampéyan mung ndelok pratayang CSS iki.'''
'''Iki durung disimpen!'''",
'sitejspreview'                    => "'''Èling yèn Sampéyan mung ndelok pratayang kodhé JavaScript iki.'''
'''Iki durung disimpen!'''",
'userinvalidcssjstitle'            => "'''Pènget:''' Kulit \"\$1\" ora ditemokaké. Muga dipèngeti yèn kaca .css lan .js nggunakaké huruf cilik, conto {{ns:user}}:Foo/vector.css lan dudu {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Dianyari)',
'note'                             => "'''Cathetan:'''",
'previewnote'                      => "'''Èling yèn Sampéyan mung ndelok pratayang.'''
Owahan Sampéyan durung kasimpen!",
'previewconflict'                  => 'Pratilik iki nuduhaké tèks ing bagian dhuwur kothak suntingan tèks kayadéné bakal katon yèn panjenengan bakal simpen.',
'session_fail_preview'             => "'''Nuwun sèwu, suntingan panjenengan ora bisa diolah amarga dhata sèsi kabusak.
Coba kirim dhata manèh. Yèn tetep ora bisa, coba log metua lan mlebu log manèh.''''''Amerga wiki iki marengaké panggunan kodhe HTML mentah, mula pratilik didhelikaké minangka pancegahan marang serangan JavaScript.'''
'''Menawa iki sawijining usaha panyuntingan sing sah, mangga dicoba manèh.
Yèn isih tetep ora kasil, cobanen metu log lan mlebu manèh.'''",
'session_fail_preview_html'        => "'''Nuwun sèwu! Kita ora bisa prosès suntingan panjenengan amerga data sési ilang.'''

''Amerga wiki iki ngidinaké panrapan HTML mentah, pratayang didelikaké minangka penggakan marang serangan Javascript.''

'''Yèn iki sawijining upaya suntingan sing absah, mangga dicoba manèh. Yèn isih tetep ora kasil, cobanen metu log utawa oncat lan mlebua manèh.'''",
'token_suffix_mismatch'            => "'''Suntingan panjenengan ditulak amerga aplikasi klièn panjenengan ngowahi karakter tandha wewacan ing suntingan. Suntingan iku ditulak kanggo untuk menggak kaluputan ing tèks artikel. Prekara iki kadhangkala dumadi yèn panjenengan ngangem dines layanan proxy anonim adhedhasar situs wèb sing duwé masalah.'''",
'edit_form_incomplete'             => "'''Sebagéyan pormulir suntingan ora tekan nèng sasana; cèk pindho yèn suntingan Sampéyan isih wutuh lan jajal manèh.'''",
'editing'                          => 'Nyunting $1',
'editingsection'                   => 'Nyunting $1 (bagian)',
'editingcomment'                   => 'Nyunting $1 (bagéyan anyar)',
'editconflict'                     => 'Konflik panyuntingan: $1',
'explainconflict'                  => "Wong liya wis nyunting kaca iki wiwit panjenengan mau nyunting.
Bagian dhuwur tèks iki ngamot tèks kaca vèrsi saiki.
Pangowahan sing panjenengan lakoni dituduhaké ing bagian ngisor tèks.
Panjenengan namung prelu nggabungaké pangowahan panjenengan karo tèks sing wis ana.
'''Namung''' tèks ing bagian dhuwur kaca sing bakal kasimpen menawa panjenengan mencèt \"{{int:savearticle}}\".",
'yourtext'                         => 'Tèks panjenengan',
'storedversion'                    => 'Versi sing kasimpen',
'nonunicodebrowser'                => "'''PÈNGET: Panjlajah wèb panjenengan ora ndhukung Unicode, mangga gantènana panjlajah wèb panjenengan sadurungé nyunting artikel.'''",
'editingold'                       => "'''PÈNGET:''' Panjenengan nyunting revisi lawas sawijining kaca. Yèn versi iki panjenengan simpen, mengko pangowahan-pangowahan sing wis digawé wiwit revisi iki bakal ilang.",
'yourdiff'                         => 'Prabédan',
'copyrightwarning'                 => "Tulung dipun-gatèkaké menawa kabèh sumbangsih utawa kontribusi kanggo {{SITENAME}} iku dianggep wis diluncuraké miturut $2 GNU (mangga priksanen $1 kanggo ditèlé).
Menawa panjenengan ora kersa menawa tulisan panjenengan bakal disunting karo disebar, aja didokok ing kéné.<br />
Panjenengan uga janji menawa apa-apa sing katulis ing kéné, iku karyané panjenengan dhéwé, utawa disalin saka sumber bébas. '''AJA NDOKOK KARYA SING DIREKSA DÉNING UNDHANG-UNDHANG HAK CIPTA TANPA IDIN!'''",
'copyrightwarning2'                => "Mangga digatèkaké yèn kabèh kontribusi marang  {{SITENAME}} bisa disunting, diowahi, utawa dibusak déning penyumbang liyané. Yèn panjenengan ora kersa yèn tulisan panjenengan bisa disunting wong liya, aja ngirim artikel panjenengan ing kéné.<br />Panjenengan uga janji yèn tulisan panjenengan iku kasil karya panjenengan dhéwé, utawa disalin saka sumber umum utawa sumber bébas liyané (mangga delengen $1 kanggo informasi sabanjuré). '''AJA NGIRIM KARYA SING DIREKSA DÉNING UNDHANG-UNDHANG HAK CIPTA TANPA IDIN!'''",
'longpageerror'                    => "'''Kasalahan: Tèks sing Sampéyan lebokaké dawané {{PLURAL:$1|sak kilobita|$1 kilobita}}, luwih dawa saka maksimal {{PLURAL:$2|sak kilobita|$2 kilobita}}.'''
Kuwi ora bisa disimpen.",
'readonlywarning'                  => "'''PÈNGET: Basis data lagi dikunci amerga ana pangopènan, dadi saiki panjenengan ora bisa nyimpen kasil panyuntingan panjenengan. Panjenengan mbokmenawa prelu mindhahaké kasil panyuntingan panjenengan iki menyang panggonan liya kanggo disimpen bésuk.'''

Pangurus sing ngunci basis data mènèhi katrangan kaya mengkéné: $1",
'protectedpagewarning'             => "'''PÈNGET:  Kaca iki wis dikunci dadi namung panganggo sing nduwé hak aksès pangurus baé sing bisa nyunting.'''
Entri cathetan pungkasan disadiakake ing ngisor kanggo referensi:",
'semiprotectedpagewarning'         => "'''Cathetan:''' Kaca iki lagi pinuju direksa, dadi namung panganggo kadaftar sing bisa nyunting.
Entri cathetan pungkasan disadiakake ing ngisor kanggo referensi:",
'cascadeprotectedwarning'          => "'''PÈNGET:''' Kaca iki wis dikunci dadi namung panganggo mawa hak aksès pangurus waé sing bisa nyunting, amerga kalebu {{PLURAL:$1|kaca|kaca-kaca}} ing ngisor iki sing wis direksa mawa opsi 'pangreksan runtun' diaktifaké:",
'titleprotectedwarning'            => "'''Pènget: Kaca iki wis dikunci saéngga perlu [[Special:ListGroupRights|hak mligi]] kanggo gawéné.'''
Entri cathetan pungkasan disadiakake ing ngisor kanggo referensi:",
'templatesused'                    => '{{PLURAL:$1|Cithakan|Cithakan}} sing dienggo ing kaca iki:',
'templatesusedpreview'             => '{{PLURAL:$1|Cithakan|Cithakan-cithakan}} sing dienggo ing pratilik iki:',
'templatesusedsection'             => '{{PLURAL:$1|Cithakan}} sing dienggo ding bagian iki:',
'template-protected'               => '(direksa)',
'template-semiprotected'           => '(semi-pangreksan)',
'hiddencategories'                 => 'Kaca iki sawijining anggota saka {{PLURAL:$1|1 kategori ndelik|$1 kategori-kategori ndelik}}:',
'edittools'                        => '<!-- Tèks ing ngisor iki bakal ditudhuhaké ing ngisoring isènan suntingan lan pangemotan.-->',
'nocreatetitle'                    => 'Panggawéan kaca anyar diwatesi',
'nocreatetext'                     => 'Situs iki ngwatesi kemampuan kanggo nggawé kaca anyar. Panjenengan bisa bali lan nyunting kaca sing wis ana, utawa mangga [[Special:UserLogin|mlebua log utawa ndaftar]]',
'nocreate-loggedin'                => 'Panjenengan ora kagungan idin kanggo nggawé kaca anyar.',
'sectioneditnotsupported-title'    => 'Panyuntingan bagéyan ora kasengkuyungan',
'sectioneditnotsupported-text'     => 'Panyuntingan sapérangan ora disengkuyung ing kaca suntingan iki.',
'permissionserrors'                => 'Kaluputan Idin Aksès',
'permissionserrorstext'            => 'Panjengan ora kagungan idin kanggo nglakoni sing panjenengan gayuh amerga {{PLURAL:$1|alesan|alesan-alesan}} iki:',
'permissionserrorstext-withaction' => 'Panjenengan ora duwé hak aksès kanggo $2, amarga {{PLURAL:$1|alasan|alasan}} ing ngisor iki:',
'recreate-moveddeleted-warn'       => "'''Pènget: Panjenengan gawé manèh sawijining kaca sing wis tau dibusak.'''

Mangga digagas manèh apa pantes nerusaké nyunting kaca iki.
Ing ngisor iki kapacak log pambusakan lan pamindhahan saka kaca iki:",
'moveddeleted-notice'              => 'Kaca iki wis dibusak.
Log pambusakan lan pamindhahan kaca iki disadiyakaké ing ngisor iki minangka réferènsi.',
'log-fulllog'                      => 'Pirsani kabèh log',
'edit-hook-aborted'                => 'Suntingan dibatalaké déning kait parser
Tanpa ana katrangan.',
'edit-gone-missing'                => 'Ora bisa nganyari kaca.
Katoné kaca iki wis dibusak.',
'edit-conflict'                    => 'Konflik panyuntingan.',
'edit-no-change'                   => 'Suntingan panjenengan dilirwakaké amerga panjenengan ora nglakoni pangowahan apa-apa ing tèks.',
'edit-already-exists'              => 'Ora bisa nggawé kaca anyar.
Amerga wis ana.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "Pènget: Kaca iki ngandhut kakèhan panggunan fungsi ''parser'' sing larang.

Sajatiné kuduné duwé kurang saka {{PLURAL:$2|panggilan|panggilan}}, saiki ana {{PLURAL:$1|$1 panggilan|$1 panggilan}}.",
'expensive-parserfunction-category'       => "Kaca-kaca mawa panggunan fungsi ''parser'' sing kakèhan",
'post-expand-template-inclusion-warning'  => 'Pènget: Cithakan klebu ukurané kegedhèn.
Sawetara cithakan bakal dilirwakaké.',
'post-expand-template-inclusion-category' => 'Kaca-kaca kanthi cithakan klebu ukuran sing ngluwihi wates',
'post-expand-template-argument-warning'   => 'Pènget: Kaca iki ngandhut saora-orané siji argumen cithakan kanthi ukuran èkspansi sing kegedhèn. Argumèn-argumèn kasebut wis dilirwakaké.',
'post-expand-template-argument-category'  => 'Kaca-kaca kanthi argumèn cithakan sing dilirwakaké',
'parser-template-loop-warning'            => "Ana ''loop'' cithakan: [[$1]]",
'parser-template-recursion-depth-warning' => "Wates ''recursion depth'' cithakan wis ngliwati ($1)",
'language-converter-depth-warning'        => 'Wates jeroné pangganti basa wis kapunjulen ($1)',

# "Undo" feature
'undo-success' => 'Suntingan iki bisa dibatalaké. Tulung priksa prabandhingan ing ngisor iki kanggo mesthèkaké yèn prakara iki pancèn sing bener panjenengan pèngin lakoni, banjur simpenen pangowahan iku kanggo ngrampungaké pambatalan suntingan.',
'undo-failure' => 'Suntingan iki ora bisa dibatalakén amerga ana konflik panyuntingan antara.',
'undo-norev'   => 'Suntingan iki ora bisa dibatalaké amerga ora ana utawa wis dibusak.',
'undo-summary' => '←Mbatalaké revisi $1 déning [[Special:Contributions/$2|$2]] ([[User talk:$2|Dhiskusi]])',

# Account creation failure
'cantcreateaccounttitle' => 'Akun ora bisa digawé',
'cantcreateaccount-text' => "Saka alamat IP iki ('''$1''') ora diparengaké nggawé akun utawa rékening. Sing mblokir utawa ora marengaké iku [[User:$3|$3]].

Alesané miturut $3 yaiku ''$2''",

# History pages
'viewpagelogs'           => 'Mirsani log kaca iki',
'nohistory'              => 'Ora ana sajarah panyuntingan kanggo kaca iki',
'currentrev'             => 'Revisi saiki',
'currentrev-asof'        => 'Révisi anyar dhéwé ing tanggal $1',
'revisionasof'           => 'Revisi per $1',
'revision-info'          => 'Revisi per $1; $2',
'previousrevision'       => '←Revisi sadurungé',
'nextrevision'           => 'Revisi sabanjuré→',
'currentrevisionlink'    => 'Revisi saiki',
'cur'                    => 'saiki',
'next'                   => 'sabanjuré',
'last'                   => 'akir',
'page_first'             => 'kapisan',
'page_last'              => 'pungkasan',
'histlegend'             => "Pilihen rong tombol radhio banjur pencèten tombol ''bandhingna'' kanggo mbandhingaké versi. Klik sawijining tanggal kanggo ndeleng versi kaca ing tanggal iku.<br />(skr) = prabédan karo vèrsi saiki, (akir) = prabédan karo vèrsi sadurungé, '''s''' = suntingan sithik, '''b''' = suntingan bot, → = suntingan bagian, ← = ringkesan otomatis",
'history-fieldset-title' => 'Njlajah sajarah vèrsi sadhurungé',
'history-show-deleted'   => 'Namung sing dibusak',
'histfirst'              => 'Suwé dhéwé',
'histlast'               => 'Anyar dhéwé',
'historysize'            => '($1 {{PLURAL:$1|bita|bita}})',
'historyempty'           => '(kosong)',

# Revision feed
'history-feed-title'          => 'Riwayat revisi',
'history-feed-description'    => 'Riwayat revisi kaca iki ing wiki',
'history-feed-item-nocomment' => '$1 ing $2',
'history-feed-empty'          => 'Kaca sing disuwun ora ditemokaké. Mbokmenawa wis dibusak saka wiki, utawa diwènèhi jeneng anyar. Coba [[Special:Search|golèka ing wiki]] kanggo kaca anyar sing rélevan.',

# Revision deletion
'rev-deleted-comment'         => '(ringkesan suntingan dibusak)',
'rev-deleted-user'            => '(jeneng panganggo dibusak)',
'rev-deleted-event'           => '(isi dibusak)',
'rev-deleted-user-contribs'   => '(jeneng panganggo utawa alamat IP dibusak - suntingan didhelikaké saka kontribusi)',
'rev-deleted-text-permission' => "Révisi kaca iki wis '''dibusak'''.
Princèné mbokmanawa kasedyakaké ing  [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pambusakan].",
'rev-deleted-text-unhide'     => "Benahan kaca iki wis '''dibusak'''.
Rincian bisa ditemokaké nèng [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pambusakan].
Sampéyan uga isih bisa [$1 ndelok benahan iki] yèn Sampéyan gelem.",
'rev-suppressed-text-unhide'  => "Benahan kaca iki wis '''dibrèdèl'''.
Rincian bisa ditemokaké nèng [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pambrèdèlan].
Sampéyan uga isih bisa [$1 ndelok benahan iki] yèn Sampéyan gelem.",
'rev-deleted-text-view'       => "Benahan kaca iki wis '''dibusak'''.
Sampéyan bisa ndelok iki; rinciané bisa ditemokaké nèng [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pambusakan].",
'rev-suppressed-text-view'    => "Benahan kaca iki wis '''dibrèdèl'''.
Sampéyan bisa ndelok iki; rinciané bisa ditemokaké nèng [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pambrèdèlan].",
'rev-deleted-no-diff'         => "Panjenengan ora bisa mirsani prabédan amarga siji saka révisiné wis '''dibusak'''.
Pricèné mbokmanawa isih ana ing [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pambusakan].",
'rev-suppressed-no-diff'      => "Sampéyan ora bisa ndelok prabédan iki amarga sawiji benahan wis '''dibusak'''.",
'rev-deleted-unhide-diff'     => "Sawiji benahan saka prabédan iki wis '''dibusak'''.
Rincian bisa ditemokaké nèng [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pambusakan].
Sampéyan uga isih bisa [$1 ndelok prabédan iki] yèn Sampéyan gelem.",
'rev-suppressed-unhide-diff'  => "Sawiji benahan saka prabédan iki wis '''dibrèdèl'''.
Rincian bisa ditemokaké nèng [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pambrèdèlan].
Sampéyan uga isih bisa [$1 ndelok prabédan iki] yèn Sampéyan gelem.",
'rev-deleted-diff-view'       => "Sawiji benahan saka prabédan iki wis '''dibusak'''.
Sampéyan isih bisa ndelok prabédan iki; rincian bisa ditemokaké nèng [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pambusakan].",
'rev-suppressed-diff-view'    => "Sawiji benahan saka prabédan iki wis '''dibrèdèl'''.
Sampéyan isih bisa ndelok prabédan iki; rincian bisa ditemokaké nèng [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} log pambrèdèlan].",
'rev-delundel'                => 'tuduhna/delikna',
'rev-showdeleted'             => 'tuduhna',
'revisiondelete'              => 'Busak/batal busak revisi',
'revdelete-nooldid-title'     => 'Target revisi ora ditemokaké',
'revdelete-nooldid-text'      => 'Panjenengan durung mènèhi target revisi kanggo nglakoni fungsi iki.',
'revdelete-nologtype-title'   => 'Tipe log ora diwènèhaké',
'revdelete-nologtype-text'    => 'Panjenengan ora mènèhi tipe log kanggo netepaké tindakan iki.',
'revdelete-nologid-title'     => 'Èntri log ora absah.',
'revdelete-nologid-text'      => 'Panjenengan manawa ora nyebutaké sawijining log prastawa targèt kanggo nglakokaké fungsi iki utawa èntri sing dimaksud ora ana.',
'revdelete-no-file'           => 'Berkas sing dituju ora ana.',
'revdelete-show-file-confirm' => 'Apa panjenengan yakin arep mirsani révisi sing wis kabusak saka berkas "<nowiki>$1</nowiki>" ing $2, jam $3?',
'revdelete-show-file-submit'  => 'Ya',
'revdelete-selected'          => "'''{{PLURAL:$2|Revisi kapilih|Revisi kapilih}} dari '''$1''''''",
'logdelete-selected'          => "'''{{PLURAL:$1|Log kapilih|Log kapilih}} kanggo:'''",
'revdelete-text'              => "'''Revisi lan tindhakan sing wis kabusak bakal tetep muncul ing kaca versi sadurungé lan log, nanging bagéyan isiné ora bisa diaksès déning publik.'''
Pangurus {{SITENAME}} liyané bakal tetep bisa ngaksès isi sing kadhelikaké iku lan bisa mbatalaké pambusakan ngliwati antarmuka sing padha, kajaba ana pawatesan liya saka operator situs.",
'revdelete-confirm'           => 'Mangga pesthèkaké yèn Sampéyan pancèn kudu nglakoni iki, yèn Sampéyan ngerti akibaté, lan yèn Sampéyan ngakoni iki cocok karo [[{{MediaWiki:Policy-url}}|kawicakan]].',
'revdelete-suppress-text'     => "Pandhelikan révisi '''mung''' bisa dipigunakaké kanggo kasus ing ngisor:
* Informasi pribadi sing kurang pantes
*: ''alamat omah lan nomer telepon, nomer kartu idhèntitas, lan sapanunggalané.''",
'revdelete-legend'            => 'Atur watesan:',
'revdelete-hide-text'         => 'Dhelikna tèks revisi',
'revdelete-hide-image'        => 'Dhelikna isi berkas',
'revdelete-hide-name'         => 'Dhelikna tindhakan lan targèt',
'revdelete-hide-comment'      => 'Tudhuhna/dhelikan ringkesan suntingan',
'revdelete-hide-user'         => 'Dhelikan jeneng panganggo/IP penyunting',
'revdelete-hide-restricted'   => 'Uga dhelikna data saka pangurus lan panganggo liyané',
'revdelete-radio-same'        => '(Aja diowahi)',
'revdelete-radio-set'         => 'Ya',
'revdelete-radio-unset'       => 'Ora',
'revdelete-suppress'          => 'Uga dhelikan saka pangurus',
'revdelete-unsuppress'        => 'Busak watesan ing revisi sing dibalèkaké',
'revdelete-log'               => 'Alesan:',
'revdelete-submit'            => 'Trapna ing {{PLURAL:$1|révisi|révisi}} kapilih',
'revdelete-success'           => "'''Kawujudan repisi sukses dianyari.'''",
'revdelete-failure'           => "'''Panampakan rèvisi ora bisa dianyari:'''
$1",
'logdelete-success'           => 'Aturan pandhelikan tindhakan bisa kasil ditrapaké.',
'logdelete-failure'           => "'''Aturan pandhelikan ora bisa disèt:'''
$1",
'revdel-restore'              => 'Ngowahi visiblitas (pangatonan)',
'revdel-restore-deleted'      => 'revisi kabusak',
'revdel-restore-visible'      => 'revisi kétok',
'pagehist'                    => 'Sajarah kaca',
'deletedhist'                 => 'Sajarah sing dibusak',
'revdelete-hide-current'      => 'Gagal ndhelikaké révisi tanggal $2, $1: iki arupa révisi paling anyar.
Révisi iki ora bisa didhelikaké.',
'revdelete-show-no-access'    => 'Gagal nampilaké révisi tanggal $1, jam $2: révisi iki wis ditandhani "kawates".
Panjenengan ora nduwèni aksès menyang révisi iki.',
'revdelete-modify-no-access'  => 'Gagal ngowahi révisi tanggal $1, jam $2: révisi iki wis ditandhani "kawates".
Panjenengan ora nduwèni aksès menyang révisi iki.',
'revdelete-modify-missing'    => 'Gagal ngowahi révisi ID $1: révisi iki ilang saka basis data!',
'revdelete-no-change'         => "'''Pènget:''' révisi tanggal $1, jam $2 wis nduwèni aturan pandhelikan kasebut.",
'revdelete-concurrent-change' => 'Gagal ngowahi révisi tanggal $1, jam $2: statusé mbokmanawa wis diowahi déning panganggo liya bebarengan karo panjenengan.
Mangga priksa cathetan log.',
'revdelete-only-restricted'   => 'Ora bisa ndhelikaké siji barang mawa tanggal $1 wanci $2: Sampéyan ora bisa ndhelikaké barang kuwi saka pangurus tanpa milih salah sawiji pilihan kanggo ndhelikaké sing liyané.',
'revdelete-reason-dropdown'   => '*Alesan mbusak sing umum
** Planggaran hak cipta
** Inpormasi pribadi sing ora patut
** Inpormasi sing potènsial ngrusak martabat',
'revdelete-otherreason'       => 'Alesan liya/tambahan:',
'revdelete-reasonotherlist'   => 'Alesan liya',
'revdelete-edit-reasonlist'   => 'Sunting alesan pambusakan',
'revdelete-offender'          => 'Revisi penulis:',

# Suppression log
'suppressionlog'     => "Log barang-barang sing didelikaké (''oversight'')",
'suppressionlogtext' => 'Ngisor iki daptar apa-apa waé sing wis dibusak lan diblokir kalebu kontèn sing didhelikaké saka para pangurus.
Delok [[Special:BlockList|daptar blokiran]] sing isiné daptar apa-apa waé sing lagi dilarang lan diblokir.',

# History merging
'mergehistory'                     => 'Gabung sejarah kaca',
'mergehistory-header'              => 'Ing kaca iki panjenengan bisa nggabung révisi-révisi sajarah saka sawijining kaca sumber menyang kaca anyar.
Pastèkna yèn owah-owahan iki bakal netepaké kasinambungan sajarah kaca.',
'mergehistory-box'                 => 'Gabungna revisi-revisi saka rong kaca:',
'mergehistory-from'                => 'Kaca sumber:',
'mergehistory-into'                => 'Kaca tujuan:',
'mergehistory-list'                => 'Sejarah suntingan bisa digabung',
'mergehistory-merge'               => 'Révisi-révisi sing kapacak ing ngisor iki saka [[:$1]] bisa digabungaké menyang [[:$2]].
Gunakna tombol radio kanggo nggabungaké révisi-révisi sing digawé sadurungé wektu tartamtu. Gatèkna, menawa nganggo pranala navigasi bakal ngesèt ulang kolom iki.',
'mergehistory-go'                  => 'Tuduhna suntingan-suntingan sing bisa digabung',
'mergehistory-submit'              => 'Gabung revisi',
'mergehistory-empty'               => 'Ora ana revisi sing bisa digabung.',
'mergehistory-success'             => '$3 {{PLURAL:$1|révisi|révisi}} saka [[:$1]] bisa suksès digabung menyang [[:$2]].',
'mergehistory-fail'                => 'Ora bisa nggabung sajarah, coba dipriksa manèh kacané lan paramèter wektuné.',
'mergehistory-no-source'           => 'Kaca sumber $1 ora ana.',
'mergehistory-no-destination'      => 'Kaca tujuan $1 ora ana.',
'mergehistory-invalid-source'      => 'Irah-irahan kaca sumber kudu irah-irahan utawa judhul sing bener.',
'mergehistory-invalid-destination' => 'Irah-irahan kaca tujuan kudu irah-irahan utawa judhul sing bener.',
'mergehistory-autocomment'         => 'Nggabung [[:$1]] menyang [[:$2]]',
'mergehistory-comment'             => 'Nggabung [[:$1]] menyang [[:$2]]: $3',
'mergehistory-same-destination'    => 'Jeneng kaca sumber lan tujuan ora kena padha',
'mergehistory-reason'              => 'Alesan:',

# Merge log
'mergelog'           => 'Gabung log',
'pagemerge-logentry' => 'nggabungaké [[$1]] menyang [[$2]] (révisi nganti tekan $3)',
'revertmerge'        => 'Batalna panggabungan',
'mergelogpagetext'   => 'Ing ngisor iki kapacak daftar panggabungan sajarah kaca ing kaca liyané.',

# Diffs
'history-title'            => 'Riwayat rèvisi saka "$1"',
'difference'               => '(Prabédan antarrevisi)',
'difference-multipage'     => '(Prabédhan antar kaca)',
'lineno'                   => 'Larikan $1:',
'compareselectedversions'  => 'Bandhingna vèrsi kapilih',
'showhideselectedversions' => 'Tampilaké/dhelikaké révisi kapilih',
'editundo'                 => 'batalna',
'diff-multi'               => '({{PLURAL:$1Siji rèvisi sedhengan|$1 rèvisi sedhengan}} déning {{PLURAL:$2|sak panganggo|$2 panganggo}} ora dituduhaké)',
'diff-multi-manyusers'     => '({{PLURAL:$1Siji rèvisi sedhengan|$1 rèvisi sedhengan}} déning luwih saka $2 {{PLURAL:$2|panganggo|panganggo}} ora dituduhaké)',

# Search results
'searchresults'                    => 'Kasil panggolèkan',
'searchresults-title'              => 'Kasil panggolèkan saka "$1"',
'searchresulttext'                 => 'Kanggo informasi sabanjuré ngenani panggolèkan ing {{SITENAME}}, mangga mirsani [[{{MediaWiki:Helppage}}|kaca pitulung]].',
'searchsubtitle'                   => 'Panjenengan nggolèki \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|kabèh kaca sing diwiwiti kanthi "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|kabèh kaca sing kagandhèng karo/menyang "$1"]])',
'searchsubtitleinvalid'            => "Panjenengan nggolèki '''$1'''",
'toomanymatches'                   => "Olèhé panjenengan golèk ngasilaké kakèhan pituwas, mangga nglebokaké ''query'' liyané",
'titlematches'                     => 'Irah-irahan artikel sing cocog',
'notitlematches'                   => 'Ora ana irah-irahan artikel sing cocog',
'textmatches'                      => 'Tèks artikel sing cocog',
'notextmatches'                    => 'Ora ana tèks kaca sing cocog',
'prevn'                            => '{{PLURAL:$1|$1}} sadurungé',
'nextn'                            => '{{PLURAL:$1|$1}} sabanjuré',
'prevn-title'                      => '$1 {{PLURAL:$1|asil|asil}} sadurungé',
'nextn-title'                      => '$1 {{PLURAL:$1|asil|asil}} sabanjuré',
'shown-title'                      => 'Tampilaké $1 {{PLURAL:$1|asil|asil}} saben kaca',
'viewprevnext'                     => 'Deleng ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Pilihan panggolèkan',
'searchmenu-exists'                => "'''Ana kaca kanthi jeneng \"[[\$1]]\" ing wiki iki'''",
'searchmenu-new'                   => "'''Gawé kaca \"[[:\$1]]\" ing wiki iki!'''",
'searchhelp-url'                   => 'Help:Isi',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Tlusuri kaca-kaca kanthi tembung-wiwitan iki]]',
'searchprofile-articles'           => 'Kaca isi',
'searchprofile-project'            => 'Kaca pitulung lan proyèk',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Kabèh',
'searchprofile-advanced'           => 'Tataran luwih dhuwur/maju',
'searchprofile-articles-tooltip'   => 'Panggolèkan ing $1',
'searchprofile-project-tooltip'    => 'Panggolèkan ing $1',
'searchprofile-images-tooltip'     => 'Panggolèkan berkas',
'searchprofile-everything-tooltip' => 'Panggolèkan kabèh isi (klebu kaca-kaca wicara)',
'searchprofile-advanced-tooltip'   => "Panggolèkan ing bilik jeneng biasa (''custom'')",
'search-result-size'               => '$1 ({{PLURAL:$2|1 tembung|$2 tembung}})',
'search-result-category-size'      => '{{PLURAL:$1|1 anggota|$1 anggota}} ({{PLURAL:$2|1 subkatégori|$2 subkatégori}}, {{PLURAL:$3|1 berkas|$3 berkas}})',
'search-result-score'              => 'Relevansi: $1%',
'search-redirect'                  => '(pangalihan $1)',
'search-section'                   => '(sèksi $1)',
'search-suggest'                   => 'Apa panjenengan kersané: $1',
'search-interwiki-caption'         => 'Proyèk-proyèk kagandhèng',
'search-interwiki-default'         => 'Pituwas $1:',
'search-interwiki-more'            => '(luwih akèh)',
'search-mwsuggest-enabled'         => 'mawa sugèsti',
'search-mwsuggest-disabled'        => 'ora ana sugèsti',
'search-relatedarticle'            => 'Kagandhèng',
'mwsuggest-disable'                => 'Patènana sugèsti AJAX',
'searcheverything-enable'          => 'Golèki ing kabèh bilik-jeneng',
'searchrelated'                    => 'kagandhèng',
'searchall'                        => 'kabèh',
'showingresults'                   => "Ing ngisor iki dituduhaké {{PLURAL:$1|'''1''' kasil|'''$1''' kasil}}, wiwitané saking #<strong>$2</strong>.",
'showingresultsnum'                => "Ing ngisor iki dituduhaké {{PLURAL:$3|'''1''' kasil|'''$3''' kasil}}, wiwitané saka #<strong>$2</strong>.",
'showingresultsheader'             => "{{PLURAL:$5|Asil '''$1''' saka '''$3'''|Asil '''$1 - $2''' saka '''$3'''}} kanggo '''$4'''",
'nonefound'                        => "'''Cathetan''': Namung sawetara bilik nama sing digolèki sacara baku. Coba seselana mawa awalan ''all:'' kanggo golèk kabèh isi (kalebu kaca dhiskusi, cithakan lsp.), utawa nganggo bilik nama sing dipèngèni minangka préfiks.",
'search-nonefound'                 => "Ora ana kasil sing cocog karo pitakonan (''query'').",
'powersearch'                      => 'Golèk (ing tataran sing luwih dhuwur/maju)',
'powersearch-legend'               => "Panggolèkan sabanjuré (''advance search'')",
'powersearch-ns'                   => 'Panggolèkan ing ruang jeneng:',
'powersearch-redir'                => 'Pratélan pangalihan',
'powersearch-field'                => 'Nggolèki',
'powersearch-togglelabel'          => 'Pilih:',
'powersearch-toggleall'            => 'Kabèh',
'powersearch-togglenone'           => 'Ora ana',
'search-external'                  => 'Panggolèkan èkstèrnal',
'searchdisabled'                   => 'Sawetara wektu iki panjenengan ora bisa nggolèk mawa fungsi golèk {{SITENAME}}. Kanggo saiki mangga panjenengan bisa golèk nganggo Google. Nanging isi indèks Google kanggo {{SITENAME}} bisa waé lawas lan durung dianyari.',

# Quickbar
'qbsettings'                => 'Pengaturan bar sidhatan',
'qbsettings-none'           => 'Ora ana',
'qbsettings-fixedleft'      => 'Tetep sisih kiwa',
'qbsettings-fixedright'     => 'Tetep sisih tengen',
'qbsettings-floatingleft'   => 'Ngambang sisih kiwa',
'qbsettings-floatingright'  => 'Ngambang sisih tengen',
'qbsettings-directionality' => 'Wis pesthi, gumantung saka wujud skrip basané Sampéyan',

# Preferences page
'preferences'                   => 'Preferensi (pilihan)',
'mypreferences'                 => 'Préferènsiku',
'prefs-edits'                   => 'Gunggungé suntingan:',
'prefsnologin'                  => 'Durung mlebu log',
'prefsnologintext'              => 'Panjenengan kudu <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}}| mlebu log]</span> kanggo ngowahin préferènsi njenengan.',
'changepassword'                => 'Ganti tembung sandi',
'prefs-skin'                    => 'Kulit',
'skin-preview'                  => 'Pratilik',
'datedefault'                   => 'Ora ana préferènsi',
'prefs-beta'                    => 'Piranti béta',
'prefs-datetime'                => 'Tanggal lan wektu',
'prefs-labs'                    => 'Piranti lab',
'prefs-personal'                => 'Profil panganggo',
'prefs-rc'                      => 'Owah-owahan pungkasan',
'prefs-watchlist'               => 'Dhaftar pangawasan',
'prefs-watchlist-days'          => 'Cacahé dina sing dituduhaké ing dhaftar pangawasan:',
'prefs-watchlist-days-max'      => 'Maksimum $1 {{PLURAL:$1|dina|dina}}',
'prefs-watchlist-edits'         => 'Cacahé suntingan maksimum sing dituduhaké ing dhaftar pangawasan sing luwih jangkep:',
'prefs-watchlist-edits-max'     => 'Gunggung maksimum: 1000',
'prefs-watchlist-token'         => 'Token pantauan:',
'prefs-misc'                    => 'Liya-liya',
'prefs-resetpass'               => 'Ganti tembung sandi',
'prefs-changeemail'             => 'Ganti alamat layang èlèktronik',
'prefs-setemail'                => 'Setèl alamat layang èlèktronik',
'prefs-email'                   => 'Opsi layang-e',
'prefs-rendering'               => 'Tampilan',
'saveprefs'                     => 'Simpen',
'resetprefs'                    => 'Resikana owah-owahan sing ora disimpen',
'restoreprefs'                  => 'Balèkna kabèh setèlan baku',
'prefs-editing'                 => 'Panyuntingan',
'prefs-edit-boxsize'            => 'Ukuran kothak panyuntingan.',
'rows'                          => 'Larikan:',
'columns'                       => 'Kolom:',
'searchresultshead'             => 'Panggolèkan',
'resultsperpage'                => 'Cacahing klik saben kaca:',
'stub-threshold'                => 'Ambang wates kanggo format <a href="#" class="stub">pranala rintisan</a>:',
'stub-threshold-disabled'       => 'Dipatèni',
'recentchangesdays'             => 'Cacahé dina sing dituduhaké ing owah-owahan pungkasan:',
'recentchangesdays-max'         => '(maksimum $1 {{PLURAL:$1|dina|dina}})',
'recentchangescount'            => 'Cacahé suntingan sing ditampilaké:',
'prefs-help-recentchangescount' => 'Iki klebu owah-owahan pungkasan, kaca sajarah, lan log.',
'prefs-help-watchlist-token'    => 'Ngisi kothak iki nganggo kunci wadi (PIN) bakal ngasilaké sindikasi RSS kanggo daftar pantauan panjenengan.
Sapa waé sing meruhi kunci iki bisa maca daftar pantauan panjenengan, mula pilihen isi sing aman.
Iki aji acak sing bisa panjenengan gunakaké: $1',
'savedprefs'                    => 'Préferènsi Panjenengan wis disimpen',
'timezonelegend'                => 'Zona wektu:',
'localtime'                     => 'Wektu saenggon:',
'timezoneuseserverdefault'      => 'Anggo gawan wiki ($1)',
'timezoneuseoffset'             => 'Liya (jelasna prabédan)',
'timezoneoffset'                => 'Prabédan¹:',
'servertime'                    => 'Wektu server:',
'guesstimezone'                 => 'Isinen saka panjlajah wèb',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amérika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Samodra Atlantika',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Eropah',
'timezoneregion-indian'         => 'Samodra Hindhia',
'timezoneregion-pacific'        => 'Samodra Pasifik',
'allowemail'                    => 'Marengaké panganggo liyané ngirim layang èlèktronik (email).',
'prefs-searchoptions'           => 'Opsi-opsi panggolèkan',
'prefs-namespaces'              => 'Ruang jeneng / Bilik jeneng',
'defaultns'                     => 'Utawa golèki ing bilik jeneng iki:',
'default'                       => 'baku',
'prefs-files'                   => 'Berkas',
'prefs-custom-css'              => 'CSS pribadi',
'prefs-custom-js'               => 'JS pribadi',
'prefs-common-css-js'           => 'CSS/JS didumaké kanggo kabèh kulit:',
'prefs-reset-intro'             => 'Panjenengan bisa migunakaké kaca iki kanggo mbalèkaké préferensi panjenengan marang setèlan baku situs.
Pembalikan ora bisa dibatalaké.',
'prefs-emailconfirm-label'      => 'Konfirmasi layang-e:',
'prefs-textboxsize'             => 'Ukuran kothak suntingan',
'youremail'                     => 'Layang élèktronik (E-mail):',
'username'                      => 'Asma panganggo:',
'uid'                           => 'ID panganggo:',
'prefs-memberingroups'          => 'Anggota {{PLURAL:$1|klompok|klompok-klompok}}:',
'prefs-registration'            => 'Wektu régistrasi:',
'yourrealname'                  => 'Asma sajatiné :',
'yourlanguage'                  => 'Basa sing dianggo:',
'yourvariant'                   => 'Werna basa isi:',
'prefs-help-variant'            => 'Varian utawa ortograpi sing Sampéyan pilih kanggo nampilaké kaca kontèn saka wiki iki.',
'yournick'                      => 'Asma sesinglon/samaran (kagem tapak asta):',
'prefs-help-signature'          => 'Komentar ing kaca wicara kudu ditapak astani nganggo "<nowiki>~~~~</nowiki>" sing bakal dikonvèrsi dadi tapak asta panjenengan lan tanggal wektu.',
'badsig'                        => 'Tapak astanipun klèntu; cèk rambu HTML.',
'badsiglength'                  => 'Tapak asta panjenengan kedawan.
Aja luwih saka {{PLURAL:$1|karakter|karakter}}.',
'yourgender'                    => 'Jinis kelamin:',
'gender-unknown'                => 'Ora dinyatakaké',
'gender-male'                   => 'Lanang',
'gender-female'                 => 'Wadon',
'prefs-help-gender'             => 'Opsional: dipigunakaké kanggo panyebutan jinis kelamin sing bener déning piranti alus.
Informasi iki bakal kabuka kanggo publik.',
'email'                         => 'Layang élèktronik (E-mail)',
'prefs-help-realname'           => '* <strong>Asma asli</strong> (ora wajib): menawa panjenengan maringi, asma asli panjenengan bakal digunakaké kanggo mènèhi akrédhitasi kanggo kasil karya tulis panjenengan.',
'prefs-help-email'              => 'Alamat layang èlèktronik sipaté mung pilihan, nanging dibutuhaké kanggo nyetèl ulang tembung sandhi yèn Sampéyan lali.',
'prefs-help-email-others'       => 'Sampéyan uga bisa milih kanggo ngidinaké wong liya ngubungi Sampéyan liwat layang èlèktronik sing ana ing kaca panganggo utawa kaca guneman.
Alamat layang èlèktronik Sampéyan ora dituduhaké nalika wong liya ngubungi Sampéyan.',
'prefs-help-email-required'     => 'Alamat layang-e dibutuhaké.',
'prefs-info'                    => 'Informasi dhasar',
'prefs-i18n'                    => 'Internasionalisasi',
'prefs-signature'               => 'Tapak asma',
'prefs-dateformat'              => 'Format tanggal',
'prefs-timeoffset'              => 'Format wektu',
'prefs-advancedediting'         => 'Opsi lanjutan',
'prefs-advancedrc'              => 'Opsi lanjutan',
'prefs-advancedrendering'       => 'Opsi lanjutan',
'prefs-advancedsearchoptions'   => 'Opsi lanjutan',
'prefs-advancedwatchlist'       => 'Opsi lanjutan',
'prefs-displayrc'               => 'Opsi tampilan',
'prefs-displaysearchoptions'    => 'Opsi tampilan',
'prefs-displaywatchlist'        => 'Opsi tampilan',
'prefs-diffs'                   => 'Prabédan',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'Alamat layang èlèktronik kayané sah',
'email-address-validity-invalid' => 'Lebokaké alamat layang èlèktronik sing sah',

# User rights
'userrights'                   => 'Manajemen hak panganggo',
'userrights-lookup-user'       => 'Ngatur kelompok panganggo',
'userrights-user-editname'     => 'Lebokna jeneng panganggo:',
'editusergroup'                => 'Sunting kelompok panganggo',
'editinguser'                  => "Ngganti hak panganggo '''[[User:$1|$1]]''' $2",
'userrights-editusergroup'     => 'Sunting kelompok panganggo',
'saveusergroups'               => 'Simpen kelompok panganggo',
'userrights-groupsmember'      => 'Anggota saka:',
'userrights-groupsmember-auto' => 'Anggota implisit saka:',
'userrights-groups-help'       => 'Panjenengan bisa ngowahi grup-grup sing ana panganggoné iki.
* Kothak sing dicenthang tegesé panganggo iki ana sajroné grup iku.
* Kothak sing ora dicenthang tegesé panganggo iku ora ana ing grup iku.
* Tandha bintang * tegesé panjenengan ora bisa ngilangi grup iku yèn wis tau nambah, utawa sawalikané.',
'userrights-reason'            => 'Alesan:',
'userrights-no-interwiki'      => 'Panjenengan ora ana hak kanggo ngowahi hak panganggo ing wiki liyané.',
'userrights-nodatabase'        => 'Basis data $1 ora ana utawa ora lokal.',
'userrights-nologin'           => 'Panjenengan kudu [[Special:UserLogin|mlebu log]] mawa nganggo akun utawa rékening pangurus supaya bisa ngowahi hak panganggo.',
'userrights-notallowed'        => 'Akun Sampéyan ora nduwé idin kanggo nambah utawa nyuda hak-hak panganggo.',
'userrights-changeable-col'    => 'Grup sing bisa panjenengan owahi',
'userrights-unchangeable-col'  => 'Grup sing ora bisa diowahi panjenengan',

# Groups
'group'               => 'Kelompok:',
'group-user'          => 'Para panganggo',
'group-autoconfirmed' => 'Panganggo sing otomatis didhedhes (dikonfirmasi)',
'group-bot'           => 'Bot',
'group-sysop'         => 'Pangurus',
'group-bureaucrat'    => 'Birokrat',
'group-suppress'      => "Para pangawas (''oversight'')",
'group-all'           => '(kabèh)',

'group-user-member'          => '{{GENDER:$1|panganggo}}',
'group-autoconfirmed-member' => '{{GENDER:$1|panganggo dipesthèni otomatis}}',
'group-bot-member'           => '{{GENDER:$1|bot}}',
'group-sysop-member'         => '{{GENDER:$1|pangurus}}',
'group-bureaucrat-member'    => '{{GENDER:$1|birokrat}}',
'group-suppress-member'      => '{{GENDER:$1|pangawasan}}',

'grouppage-user'          => '{{ns:project}}:Para panganggo',
'grouppage-autoconfirmed' => '{{ns:project}}:Panganggo sing otomatis didhedhes (dikonfirmasi)',
'grouppage-bot'           => '{{ns:project}}:Bot',
'grouppage-sysop'         => '{{ns:project}}:Pangurus',
'grouppage-bureaucrat'    => '{{ns:project}}:Birokrat',
'grouppage-suppress'      => '{{ns:project}}:Oversight',

# Rights
'right-read'                  => 'Maca kaca-kaca',
'right-edit'                  => 'Nyunting kaca-kaca',
'right-createpage'            => 'Nggawé kaca (sing dudu kaca dhiskusi)',
'right-createtalk'            => 'Nggawé kaca dhiskusi',
'right-createaccount'         => 'Nggawé rékening (akun) panganggo anyar',
'right-minoredit'             => 'Tandhanan suntingan minangka minor',
'right-move'                  => 'Pindhahna kaca',
'right-move-subpages'         => 'Pindhahaké kaca lan kabèh anak-kacané',
'right-move-rootuserpages'    => 'Pindhahaké kaca utama panganggo',
'right-movefile'              => 'Mindhah berkas',
'right-suppressredirect'      => 'Aja nggawé pangalihan saka kaca sing lawas yèn mindhah sawijining kaca',
'right-upload'                => 'Ngunggahaké berkas-berkas',
'right-reupload'              => 'Tindhihana sawijining berkas sing wis ana',
'right-reupload-own'          => 'Nimpa sawijining berkas sing wis ana lan diunggahaké déning panganggo sing padha',
'right-reupload-shared'       => 'Timpanana berkas-berkas ing khazanah binagi sacara lokal',
'right-upload_by_url'         => 'Ngunggahaké berkas saka sawijining alamat URL',
'right-purge'                 => "Kosongna ''cache'' situs iki kanggo sawijining kaca tanpa kaca konfirmasi",
'right-autoconfirmed'         => 'Sunting kaca-kaca sing disémi-reksa',
'right-bot'                   => 'Anggepen minangka prosès otomatis',
'right-nominornewtalk'        => "Suntingan sithik (''minor'') ora ngwetokaké prompt pesen anyar",
'right-apihighlimits'         => 'Nganggo wates sing luwih dhuwur ing kwéri API',
'right-writeapi'              => 'Migunakaké API panulisan',
'right-delete'                => 'Busak kaca-kaca',
'right-bigdelete'             => 'Busak kaca-kaca mawa sajarah panyuntingan sing gedhé',
'right-deleterevision'        => 'Busak lan batal busak révisi tartamtu kaca-kaca',
'right-deletedhistory'        => 'Ndeleng sajarah èntri-èntri kabusak, tanpa bisa ndeleng apa sing dibusak',
'right-deletedtext'           => 'Delok tèks kabusak lan panggantèn antara rèpisi kabusak',
'right-browsearchive'         => 'Golèk kaca-kaca sing wis dibusak',
'right-undelete'              => 'Batal busak sawijining kaca',
'right-suppressrevision'      => 'Ndeleng lan mbalèkaké révisi-révisi sing didelikaké saka para opsis',
'right-suppressionlog'        => 'Ndeleng log-log pribadi',
'right-block'                 => 'Blokir panganggo-panganggo liya saka panyuntingan',
'right-blockemail'            => 'Blokir sawijining panganggo saka ngirim e-mail',
'right-hideuser'              => 'Blokir jeneng panganggo, lan delikna saka umum',
'right-ipblock-exempt'        => 'Bypass pamblokiran IP, pamblokiran otomatis lan pamblokiran rangkéan',
'right-proxyunbannable'       => 'Bypass pamblokiran otomatis proxy-proxy',
'right-unblockself'           => 'Bukak blokirané dhéwéké',
'right-protect'               => 'Ganti tingkatan pangreksan lan sunting kaca-kaca sing direksa',
'right-editprotected'         => 'Sunting kaca-kaca sing direksa (tanpa pangreksan runtun)',
'right-editinterface'         => 'Sunting interface (antarmuka) panganggo',
'right-editusercssjs'         => 'Sunting berkas-berkas CSS lan JS panganggo liya',
'right-editusercss'           => 'Sunting berkas-berkas CSS panganggo liya',
'right-edituserjs'            => 'Sunting berkas-berkas JS panganggo liya',
'right-rollback'              => 'Sacara gelis mbalèkaké panganggo pungkasan sing nyunting kaca tartamtu',
'right-markbotedits'          => 'Tandhanana suntingan pambalèkan minangka suntingan bot',
'right-noratelimit'           => 'Ora dipengaruhi déning wates cacahing suntingan.',
'right-import'                => 'Impor kaca-kaca saka wiki liya',
'right-importupload'          => 'Impor kaca-kaca saka sawijining pangunggahan berkas',
'right-patrol'                => 'Tandhanana suntingan minangka wis dipatroli',
'right-autopatrol'            => 'Gawé supaya suntingan-suntingan ditandhani minangka wis dipatroli',
'right-patrolmarks'           => 'Ndeleng tandha-tandha patroli owah-owahan anyar',
'right-unwatchedpages'        => 'Tuduhna daftar kaca-kaca sing ora diawasi',
'right-mergehistory'          => 'Gabungna sajarah kaca-kaca',
'right-userrights'            => 'Sunting kabèh hak-hak panganggo',
'right-userrights-interwiki'  => 'Sunting hak-hak para panganggo ing situs-situs wiki liya',
'right-siteadmin'             => 'Kunci lan buka kunci basis data',
'right-override-export-depth' => "Èkspor kaca klebu kaca kagandhèng nganti tataran/''depth'' 5",
'right-sendemail'             => 'Ngirim layang listrik (e-mail) menyang panganggo liya',
'right-passwordreset'         => 'Delok layang èlèktronik panyetèlulangan tembung sandhi',

# User rights log
'rightslog'                  => 'Log pangowahan hak aksès',
'rightslogtext'              => 'Ing ngisor iki kapacak log pangowahan marang hak-hak panganggo.',
'rightslogentry'             => 'ngganti kaanggotan kelompok kanggo $1 saka $2 dadi $3',
'rightslogentry-autopromote' => 'otomatis ditawakaké saka $2 nèng $3',
'rightsnone'                 => '(ora ana)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'maca kaca iki',
'action-edit'                 => 'sunting kaca iki',
'action-createpage'           => 'nggawé kaca-kaca',
'action-createtalk'           => 'gawé kaca wicara anyar',
'action-createaccount'        => 'gawé akun panganggo iki',
'action-minoredit'            => 'nandhani minangka suntingan sithik',
'action-move'                 => 'alihna kaca iki',
'action-move-subpages'        => 'mindahaké kaca iki, lan kabèh anak-kacané',
'action-move-rootuserpages'   => 'mindhahaké kaca utama panganggo',
'action-movefile'             => 'pindhahna berkas iki',
'action-upload'               => 'ngunggahaké berkas iki',
'action-reupload'             => 'nindhih berkas sing wis ana',
'action-reupload-shared'      => 'nindhih berkas sing wis ana ing papan panyimpanan berkas sing dianggo bebarengan',
'action-upload_by_url'        => 'unggahna berkas iki saka sawijining alamat URL',
'action-writeapi'             => 'migunakaké API panulisan',
'action-delete'               => 'busak kaca iki',
'action-deleterevision'       => 'busak revisi iki',
'action-deletedhistory'       => 'pirsani sajarah kaca sing wis dibusak iki',
'action-browsearchive'        => 'nggolèki kaca-kaca sing wis dibusak',
'action-undelete'             => 'mbatalaké pambusakan kaca iki',
'action-suppressrevision'     => 'ninjo lan mbalèkaké revisi sing didhelikaké iki',
'action-suppressionlog'       => 'mirsani log pribadi iki',
'action-block'                => 'blok panganggo iki saka panyuntingan',
'action-protect'              => 'owahi tataran pangreksan kaca iki',
'action-rollback'             => 'gelis mbalèkaké suntingané panganggo pungkasan nèng sawijining saca',
'action-import'               => 'impor kaca iki saka wiki liya',
'action-importupload'         => 'impor kaca iki saka pamunggahan berkas',
'action-patrol'               => 'nandhani suntingan panganggo liya minangka wis kapriksa',
'action-autopatrol'           => 'nandhani suntingan panjenengan dhéwé minangka wis kapriksa',
'action-unwatchedpages'       => 'pirsani dhaftar kaca-kaca sing ora kaawasi',
'action-mergehistory'         => 'nggabungaké sajarah kaca iki',
'action-userrights'           => 'ngowahi kabèh hak panganggo',
'action-userrights-interwiki' => 'ngowahi hak aksès saka panganggo ing wiki liya',
'action-siteadmin'            => 'ngunci utawa mbukak kunci basis data',
'action-sendemail'            => 'kirim layang èlèktronik',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|pangowahan|owah-owahan}}',
'recentchanges'                     => 'Owah-owahan',
'recentchanges-legend'              => 'Pilihan owah-owahan pungkasan',
'recentchangestext'                 => 'Runutna owah-owahan pungkasan ing wiki iki ing kaca iki.',
'recentchanges-feed-description'    => "Urutna owah-owahan anyar ing wiki ing ''feed'' iki.",
'recentchanges-label-newpage'       => 'Suntingan iki gawé kaca anyar',
'recentchanges-label-minor'         => 'Iki suntingan sithik',
'recentchanges-label-bot'           => 'Suntingan iki diayahi déning bot',
'recentchanges-label-unpatrolled'   => 'Suntingan iki durung dipatroli',
'rcnote'                            => 'Ing ngisor iki kapacak {{PLURAL:$1|pangowahan|owah-owahan}} pungkasan ing  <strong>$2</strong> dina pungkasan ing $5, $4.',
'rcnotefrom'                        => 'Ing ngisor iki owah-owahan wiwit <strong>$2</strong> (kapacak nganti <strong>$1</strong> owah-owahan).',
'rclistfrom'                        => 'Saiki nuduhaké owah-owahan wiwit tanggal $1',
'rcshowhideminor'                   => '$1 suntingan sithik',
'rcshowhidebots'                    => '$1 bot',
'rcshowhideliu'                     => '$1 panganggo mlebu log',
'rcshowhideanons'                   => '$1 panganggo anonim',
'rcshowhidepatr'                    => '$1 suntingan sing dipatroli',
'rcshowhidemine'                    => '$1 suntinganku',
'rclinks'                           => 'Tuduhna owah-owahan pungkasan $1 ing $2 dina pungkasan iki.<br />$3',
'diff'                              => 'béda',
'hist'                              => 'sajarah',
'hide'                              => 'Delikna',
'show'                              => 'Tuduhna',
'minoreditletter'                   => 's',
'newpageletter'                     => 'A',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|cacahé sing ngawasi|cacahé sing ngawasi}}]',
'rc_categories'                     => 'Watesana nganti kategori (dipisah karo "|")',
'rc_categories_any'                 => 'Apa waé',
'rc-change-size-new'                => '$1 {{PLURAL:$1|bita|bita}} sakwisé diowah',
'newsectionsummary'                 => '/* $1 */ bagéyan anyar',
'rc-enhanced-expand'                => 'Tuduhaké princèn (merlokaké JavaScript)',
'rc-enhanced-hide'                  => 'Dhelikaké princèn',
'rc-old-title'                      => 'wigatiné digawé minangka "$1"',

# Recent changes linked
'recentchangeslinked'          => 'Pranala Pilihan',
'recentchangeslinked-feed'     => 'Pranala Pilihan',
'recentchangeslinked-toolbox'  => 'Pranala Pilihan',
'recentchangeslinked-title'    => 'Owah-owahan sing ana gandhèngané karo "$1"',
'recentchangeslinked-noresult' => 'Ora ana owah-owahan ing kaca-kaca kagandhèng iki salawasé periode sing wis ditemtokaké.',
'recentchangeslinked-summary'  => "Kaca astaméwa (kaca kusus) iki mènèhi daftar owah-owahan pungkasan ing kaca-kaca sing kagandhèng (utawa anggota sawijining kateogri). Kaca sing [[Special:Watchlist|panjenengan awasi]] ditandhani '''kandel'''.",
'recentchangeslinked-page'     => 'Jeneng kaca:',
'recentchangeslinked-to'       => 'Nuduhaké owah-owahan menyang kaca sing disambung menyang kaca-kaca iki',

# Upload
'upload'                      => 'Unggah',
'uploadbtn'                   => 'Unggahna berkas',
'reuploaddesc'                => 'Bali ing formulir pamotan',
'upload-tryagain'             => 'Kirim déskripsi berkas sing wis diowah',
'uploadnologin'               => 'Durung mlebu log',
'uploadnologintext'           => 'Panjenengan kudu [[Special:UserLogin|mlebu log]] supaya olèh ngunggahaké gambar utawa berkas liyané.',
'upload_directory_missing'    => 'Direktori pamunggahan ($1) ora ditemokaké lan ora bisa digawé déning server wèb.',
'upload_directory_read_only'  => 'Dirèktori pangunggahan ($1) ora bisa ditulis déning server wèb.',
'uploaderror'                 => 'Kaluputan pangunggahan berkas',
'upload-recreate-warning'     => "'''Pèngetan: Berkas mawa jeneng kuwi wis dibusak utawa disingkiraké.'''

Log pambusakan lan panyingkiran saka kaca iki sumadhiya nèng kéné:",
'uploadtext'                  => "Anggé formulir ing ngandhap punika kanggé nginggahaké gambar.
Kanggé mirsani utawi madosi gambar ingkang sampun dipununggah sakdèrèngipun pigunakaken [[Special:FileList|dhaftar berkas sing wis diunggah]], gambar ingkang dipununggah ulang ugi kadhaftar ing [[Special:Log/upload|log pangunggahan]], pambusakan ing [[Special:Log/delete|Log pambusakan]].

Kanggé nyertakaken gambar ing satunggiling kaca, pigunakaken pranala salah setunggal saking format ing ngandhap punika:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Berkas.jpg]]</nowiki></code>''' kanggé migunakaken versi pepak gambar
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Berkas.png|200px|thumb|left|tèks alt]]</nowiki></code>''' kanggé migunakaken gambar wiyaripun 200 piksel ing kothak ing sisih kiwa kanthi 'tèks alt' minangka panjelasan
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Berkas.ogg]]</nowiki></code>''' kanggé nggandhèng langsung dhumateng gambar tanpi nampilaké gambar",
'upload-permitted'            => 'Jenis berkas sing diidinaké: $1.',
'upload-preferred'            => 'Jenis berkas sing disaranaké: $1.',
'upload-prohibited'           => 'Jenis berkas sing dilarang: $1.',
'uploadlog'                   => 'log pangunggahan',
'uploadlogpage'               => 'Log pangunggahan',
'uploadlogpagetext'           => 'Ing ngisor iki kapacak log pangunggahan berkas sing anyar dhéwé.
Mangga mirsani [[Special:NewFiles|galeri berkas-berkas anyar]] kanggo pratélan visual.',
'filename'                    => 'Jeneng berkas',
'filedesc'                    => 'Ringkesan',
'fileuploadsummary'           => 'Ringkesan:',
'filereuploadsummary'         => 'Owah-owahan berkas:',
'filestatus'                  => 'Status hak cipta',
'filesource'                  => 'Sumber',
'uploadedfiles'               => 'Berkas sing wis diamot',
'ignorewarning'               => 'Lirwakna pèngetan lan langsung simpen berkas.',
'ignorewarnings'              => 'Lirwakna pèngetan apa waé',
'minlength1'                  => 'Jeneng berkas paling ora minimal kudu awujud saaksara.',
'illegalfilename'             => 'Jeneng berkas "$1" ngandhut aksara sing ora diparengaké ana sajroning irah-irahan kaca. Mangga owahana jeneng berkas iku lan cobanen  diunggahaké manèh.',
'filename-toolong'            => 'Jeneng berkas ora olèh luwih dawa saka 240 bita.',
'badfilename'                 => 'Berkas wis diowahi dados "$1".',
'filetype-mime-mismatch'      => 'Èkstènsi berkas ".$1" ora cocok karo jinis MIME sing kadètèk saka berkas ($2).',
'filetype-badmime'            => 'Berkas mawa tipe MIME "$1" ora pareng diunggahaké.',
'filetype-bad-ie-mime'        => 'Ora bisa ngunggahaké berkas iki amarga Internet Explorer ndhétèksi minangka "$1", sing ora diidinaké lan minangka tipe berkas sing nduwèni potènsi mbebayani.',
'filetype-unwanted-type'      => "'''\".\$1\"''' klebu jenis berkas sing ora diidinaké.
Luwih becik {{PLURAL:\$3|jinis berkas|Jinis-jinis berkas}} \$2.",
'filetype-banned-type'        => '\'\'\'".$1"\'\'\' {{PLURAL:$4|dudu jinis berkas sing dililakaké|dudu jinis berkas sing dililakaké}}.
{{PLURAL:$3|Berkas|Berkas}} sing dililakaké $2.',
'filetype-missing'            => 'Berkas ini ora duwé ekstènsi (contoné ".jpg").',
'empty-file'                  => 'Berkas sing Sampéyan kirim kosong.',
'file-too-large'              => 'Berkas sing Sampéyan kirim kagedhèn.',
'filename-tooshort'           => 'Jeneng berkas kacendhèken.',
'filetype-banned'             => 'Jinis berkas iki dilarang.',
'verification-error'          => 'Berkas iki ora lulus pangesahan.',
'hookaborted'                 => 'Pangowahan sing Sampéyan coba dibatalaké déning èkstènsi.',
'illegal-filename'            => 'Jeneng berkas ora dililakaké.',
'overwrite'                   => 'Nibani berkas sing wis ana ora dililakaké.',
'unknown-error'               => 'Ana masalah sing ora dingertèni.',
'tmp-create-error'            => 'Ora bisa nggawé berkas sawetara.',
'tmp-write-error'             => 'Ora bisa nulis berkas sawetara.',
'large-file'                  => 'Ukuran berkas disaranaké supaya ora ngluwihi $1 bita; berkas iki ukurané $2 bita.',
'largefileserver'             => 'Berkas iki luwih gedhé tinimbang sing bisa kaparengaké server.',
'emptyfile'                   => 'Berkas sing panjenengan unggahaké katoné kosong. Mbokmenawa iki amerga anané salah ketik ing jeneng berkas. Mangga dipastèkaké apa panjenengan pancèn kersa ngunggahaké berkas iki.',
'windows-nonascii-filename'   => 'Wiki iki ora nyengkuyung jeneng berkas mawa karakter kusus.',
'fileexists'                  => 'Sawijining berkas mawa jeneng iku wis ana, mangga dipriksa <strong>[[:$1]]</strong> yèn panjenengan ora yakin sumedya ngowahiné.
[[$1|thumb]]',
'filepageexists'              => 'Kaca dèskripsi kanggo berkas iki wis digawé ing <strong>[[:$1]]</strong>, nanging saiki iki ora ditemokaké berkas mawa jeneng iku. Ringkesan sing panjenengan lebokaké ora bakal metu ing kaca dèskripsi. Kanggo ngetokaké dèskripsi iki, panjenengan kudu nyunting sacara manual. [[$1|thumb]]',
'fileexists-extension'        => 'Berkas mawa jeneng sing padha wis ana: [[$2|thumb]]
* Jeneng berkas sing bakal diunggahaké: <strong>[[:$1]]</strong>
* Jeneng berkas sing wis ana: <strong>[[:$2]]</strong>
Mangga milih jeneng liya.',
'fileexists-thumbnail-yes'    => "Berkas iki katoné gambar mawa ukuran sing luwih cilik ''(thumbnail)''. [[$1|thumb]]
Tulung dipriksa berkas <strong>[[:$1]]</strong>.
Yèn berkas sing wis dipriksa iku padha, ora perlu panjenengan ngunggahaké vèrsi cilik liyané manèh.",
'file-thumbnail-no'           => 'Jeneng berkas diwiwiti kanthi <strong>$1</strong>. Katoné berkas iki sawijining gambar mawa ukuran sing dicilikaké <em>(thumbnail)</em>.
Yèn panjenengan kagungan vèrsi mawa résolusi kebak saka gambar iki, mangga diunggahaké. Yèn ora, tulung jeneng berkas diganti.',
'fileexists-forbidden'        => 'Sawijining berkas mawa jeneng iki wis ana, lan ora bisa ditindhes.
Yèn panjenengan isih arep ngunggahaké berkas panjenengan, supaya
mbalik lan gunakna jeneng liya.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Wis ana berkas liyané mawa jeneng sing padha ing gudhang berkas sing dianggo bebarengan.
Yèn isih ngersakaké ngunggahaké, mangga berkas diunggahaké manèh mawa jeneng liya. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Berkas iki duplikat utawa padha karo {{PLURAL:$1|berkas|berkas-berkas}} ing ngisor:',
'file-deleted-duplicate'      => 'Sawijining berkas persis berkas iki ([[:$1]]) wis tau dibusak. Mangga panjenengan priksani sajarah pambusakan berkas kasebut sadurungé nerusaké ngunggahaké berkas kuwi manèh.',
'uploadwarning'               => 'Pèngetan pangunggahan berkas',
'uploadwarning-text'          => 'Mangga owah katrangan berkas nèng ngisor lan coba manèh.',
'savefile'                    => 'Simpen berkas',
'uploadedimage'               => 'gambar "[[$1]]" kaunggahaké',
'overwroteimage'              => 'ngunggahaké vèrsi anyar saka "[[$1]]"',
'uploaddisabled'              => 'Nuwun sèwu, fasilitas pangunggahan dipatèni.',
'copyuploaddisabled'          => 'Ngunggah mawa URL dipatèni.',
'uploadfromurl-queued'        => 'Unggahan Sampéyan wis mlebu antrian.',
'uploaddisabledtext'          => 'Pangunggahan berkas ora diidinaké.',
'php-uploaddisabledtext'      => 'Pangunggahan berkas dipatèni ing PHP.
Mangga priksa panyetèlan pangunggahan berkas.',
'uploadscripted'              => 'Berkas iki ngandhut HTML utawa kode sing bisa diinterpretasi salah déning panjlajah wèb.',
'uploadvirus'                 => 'Berkas iki ngamot virus! Détil: $1',
'uploadjava'                  => 'Berkas kuwi berkas ZIP sing kaisi berkas .class Java.
Ngungga berkas Java ora dililakaké amarga bisa nyebabaké ngluwèhaké wates kamanan.',
'upload-source'               => 'Berkas sumber',
'sourcefilename'              => 'Jeneng berkas sumber',
'sourceurl'                   => 'URL sumber:',
'destfilename'                => 'Jeneng berkas sing dituju',
'upload-maxfilesize'          => 'Ukuran maksimal berkas: $1',
'upload-description'          => 'Katrangan berkas',
'upload-options'              => 'Opsi pangundhuhan',
'watchthisupload'             => 'Awasana berkas iki',
'filewasdeleted'              => 'Sawijining berkas mawa jeneng iki wis tau diunggahaké lan sawisé dibusak.
Mangga priksanen $1 sadurungé ngunggahaké berkas iku manèh.',
'filename-bad-prefix'         => "Jeneng berkas sing panjenengan unggahaké, diawali mawa '''\"\$1\"''', sing sawijining jeneng non-dèskriptif sing biasané diwènèhaké sacara otomatis déning kamera digital. Mangga milih jeneng liyané sing luwih dèskriptif kanggo berkas panjenengan.",
'upload-success-subj'         => 'Kasil diamot',
'upload-success-msg'          => 'Unggahan Sampéyan saka [$2] sukses. Kuwi sumadhiya nèng kéné: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Perkara pangunggahan',
'upload-failure-msg'          => 'Ana prakara karo pangunggahan panjenengan seka [$2]:
$1',
'upload-warning-subj'         => 'Pèngetan pangunggahan berkas',
'upload-warning-msg'          => 'Ana masalah ing pangunggahan panjenengan saka [$2]. Panjenengan bisa bali menyang [[Special:Upload/stash/$1|upload form]] kanggo mrantasi masalah iki',

'upload-proto-error'        => 'Protokol ora bener',
'upload-proto-error-text'   => 'Pangunggahan jarah adoh mbutuhaké URL sing diawali karo <code>http://</code> utawa <code>ftp://</code>.',
'upload-file-error'         => 'Kaluputan internal',
'upload-file-error-text'    => 'Ana kaluputan internal nalika nyoba ngunggahaké berkas sauntara ing server.
Mangga kontak [[Special:ListUsers/sysop|pangurus]].',
'upload-misc-error'         => 'Kaluputan pamunggahan sing ora dimangertèni',
'upload-misc-error-text'    => 'Ana kaluputan sing ora diweruhi kadadéyan nalika pangunggahan. Mangga dipasthèkaké yèn URL kasebut iku absah lan bisa diaksès lan sawisé iku cobanen manèh. Yèn masalah iki isih ana, mangga kontak [[Special:ListUsers/sysop|pangurus sistem]].',
'upload-too-many-redirects' => 'URL ngandhut kakèhan pengalihan',
'upload-unknown-size'       => 'Ukuran ora diweruhi',
'upload-http-error'         => 'Ana kasalahan HTTP: $1',

# File backend
'backend-fail-stream'        => 'Ora bisa milikaké berkas "$1".',
'backend-fail-backup'        => 'Ora bisa nyadangaké berkas "$1".',
'backend-fail-notexists'     => 'Berkas $1 ora ana.',
'backend-fail-hashes'        => 'Ora bisa ngéntukaké has berkas kanggo mbandingaké.',
'backend-fail-notsame'       => 'Berkas nonidèntik wis ana nèng "$1".',
'backend-fail-invalidpath'   => '"$1" dudu jurusan nyimpen sing sah.',
'backend-fail-delete'        => 'Ora bisa mbusak berkas "$1".',
'backend-fail-alreadyexists' => 'Berkas "$1" wis ana.',
'backend-fail-store'         => 'Ora bisa nyèlèhaké berkas "$1" nèng "$2".',
'backend-fail-copy'          => 'Ora bisa nyalin berkas "$1" nèng "$2".',
'backend-fail-move'          => 'Ora bisa mindhahaké berkas "$1" nèng "$2".',
'backend-fail-opentemp'      => 'Ora bisa mbukak berkas sawetara.',
'backend-fail-writetemp'     => 'Ora bisa nulis berkas sawetara.',
'backend-fail-closetemp'     => 'Ora bisa nutup berkas sawetara.',
'backend-fail-read'          => 'Ora bisa maca berkas "$1".',
'backend-fail-create'        => 'Ora bisa nulis berkas "$1".',
'backend-fail-contenttype'   => 'Ora bisa nemtokaké jinisé kontèn saka berkas sing arep disimpen nèng "$1".',

# Lock manager
'lockmanager-notlocked'        => 'Ora bisa mbukak gembok "$1"; kuwi ora kagembok.',
'lockmanager-fail-closelock'   => 'Ora bisa nutup berkas gembok kanggo "$1".',
'lockmanager-fail-deletelock'  => 'Ora bisa mbusak berkas gembok kanggo "$1".',
'lockmanager-fail-acquirelock' => 'Ora bisa njaluk gembok kanggo "$1".',
'lockmanager-fail-openlock'    => 'Ora bisa mbukak berkas gembok kanggo "$1".',
'lockmanager-fail-releaselock' => 'Ora bisa ngetokaké gembok kanggo "$1".',
'lockmanager-fail-db-bucket'   => 'Ora bisa ngubungi cukup basis data gembok nèng èmbèr $1.',
'lockmanager-fail-db-release'  => 'Ora bisa nguculaké gembok neng basis data $1.',
'lockmanager-fail-svr-release' => 'Ora bisa nguculaké gembok neng sasana $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Ana kasalahan nalika mbukak berkas kanggo papriksan ZIP.',
'zip-wrong-format'    => 'Berkas sing diawèhaké dudu berkas ZIP.',
'zip-bad'             => 'Berkas rusak utawa berkas ZIP sing ora bisa diwaca.
Kuwi ora bisa kapriksa kanthi patut kanggo kamanan.',
'zip-unsupported'     => 'Berkasé kuwi berkas ZIP sing nganggo piranti ZIP sing ora kasengkuyung déning MediaWiki.
Kuwi ora bisa kapriksa kanthi patut kanggo kamanan.',

# Special:UploadStash
'uploadstash'          => 'Unggah pandhelikan',
'uploadstash-summary'  => 'Kaca iki nyadhiyakaké dalan nèng berkas-berkas sing wis diunggah (utawa lagi diunggah) naning durung diterbitaké nèng wiki. Berkas-berkas iki ora katon kanggo sapa waé nanging namung kanggo panganggo sing ngunggah waé.',
'uploadstash-clear'    => 'Busak berkas kadhelikaké',
'uploadstash-nofiles'  => 'Sampéyan ora nduwé berkas kadhelikaké.',
'uploadstash-badtoken' => 'Nglakoni iki ora suksès, mungkin amarga hak panyuntingan Sampéyan wis kedaluwarsa. Jajal manèh.',
'uploadstash-errclear' => 'Ngresiki berkas ora suksès.',
'uploadstash-refresh'  => 'Segeraké daptar berkas',
'invalid-chunk-offset' => 'Ganti rugi kethoka ora sah',

# img_auth script messages
'img-auth-accessdenied'     => 'Aksès ditulak',
'img-auth-nopathinfo'       => 'Kélangan PATH_INFO.
Sasana Sampéyan durung disetèl kanggo ngliwati inpormasi iki.
Mungkin amarga abasis-CGI lan ora bisa nyengkuyung img_auth.
Delok https://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'         => 'Alur sing dijaluk dudu dirèktori unggah kakonpigurasi.',
'img-auth-badtitle'         => 'Ora bisa mbangun judhul sah saka "$1".',
'img-auth-nologinnWL'       => 'Sampéyan durung mlebu log lan "$1" ora nèng daptar putih.',
'img-auth-nofile'           => 'Berkas "$1" ora ana.',
'img-auth-isdir'            => 'Sampéyan lagi njajal ngaksès dirèktori "$1".
Namung aksès berkas sing dililakaké.',
'img-auth-streaming'        => 'Striming "$1".',
'img-auth-public'           => 'Pungsi img_auth.php yakuwi ngetokaké berkas saka wiki pribadi.
Wiki iki ditata minangka wiki umum.
Kanggo kamanan paling apik, img_auth.php dipatèni.',
'img-auth-noread'           => 'Panganggo ora nduwé aksès kanggo maca "$1".',
'img-auth-bad-query-string' => "URL nduwèni ''query string'' sing ora sah.",

# HTTP errors
'http-invalid-url'      => 'URL ora absah: $1',
'http-invalid-scheme'   => 'URL mawa skéma "$1" ora disengkuyung.',
'http-request-error'    => 'Panjalukan HTTP gagal amarga kasalahan sing ora dingertèni.',
'http-read-error'       => 'Kasalahan maca HTTP.',
'http-timed-out'        => 'Panjalukan HTTP kliwat wates wektu.',
'http-curl-error'       => 'Kasalahan nalika njupuk URL: $1',
'http-host-unreachable' => 'Ora bisa ngranggèh URL.',
'http-bad-status'       => 'Ana masalah nalika njaluk HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL-é ora bisa dihubungi',
'upload-curl-error6-text'  => 'URL sing diwènèhaké ora bisa dihubungi.
Mangga dipriksa manèh yèn URL iku pancèn bener lan situs iki lagi aktif.',
'upload-curl-error28'      => 'Pangunggahan ngliwati wektu',
'upload-curl-error28-text' => 'Situsé kesuwèn sadurungé réaksi.
Mangga dipriksa menawa situsé aktif, nunggu sedélok lan coba manèh.
Mbok-menawa panjenengan bisa nyoba manèh ing wektu sing luwih longgar.',

'license'            => 'Jenis lisènsi:',
'license-header'     => 'Jinis lisènsi',
'nolicense'          => 'Durung ana sing dipilih',
'license-nopreview'  => '(Pratayang ora sumedya)',
'upload_source_url'  => ' (sawijining URL absah sing bisa diaksès publik)',
'upload_source_file' => ' (sawijining berkas ing komputeré panjenengan)',

# Special:ListFiles
'listfiles-summary'     => 'Kaca astamiwa nudhuhaké kabèk kaca kaunggah.
Yèn disaring déning panganggo, namung berkas mawa vèrsi paling anyar waé sing bakal katon.',
'listfiles_search_for'  => 'Golèk jeneng gambar:',
'imgfile'               => 'gambar',
'listfiles'             => 'Daftar gambar',
'listfiles_thumb'       => 'Gambar mini',
'listfiles_date'        => 'Tanggal',
'listfiles_name'        => 'Jeneng',
'listfiles_user'        => 'Panganggo',
'listfiles_size'        => 'Ukuran (bita)',
'listfiles_description' => 'Dèskripsi',
'listfiles_count'       => 'Vèrsi',

# File description page
'file-anchor-link'          => 'Gambar',
'filehist'                  => 'Sajarah berkas',
'filehist-help'             => 'Klik ing tanggal/wektu kanggo deleng berkas iki ing wektu iku.',
'filehist-deleteall'        => 'busaken kabèh',
'filehist-deleteone'        => 'busaken iki',
'filehist-revert'           => 'balèkna',
'filehist-current'          => 'saiki iki',
'filehist-datetime'         => 'Tanggal/Wektu',
'filehist-thumb'            => "Miniatur (''thumbnail'')",
'filehist-thumbtext'        => 'Miniatur kanggo versi ing $1',
'filehist-nothumb'          => 'Ora ana miniatur',
'filehist-user'             => 'Panganggo',
'filehist-dimensions'       => 'Ukuran',
'filehist-filesize'         => 'Gedhené berkas',
'filehist-comment'          => 'Komentar',
'filehist-missing'          => 'Berkas ilang',
'imagelinks'                => 'Panganggoan berkas',
'linkstoimage'              => 'Kaca-kaca sing kapacak iki duwé {{PLURAL:$1|pranala|$1 pranala}} menyang berkas iki:',
'linkstoimage-more'         => 'Luwih saka $1 {{PLURAL:$1|kaca|kaca-kaca}} nduwèni pranala menyang berkas iki.
Dhaftar ing ngisor nuduhaké {{PLURAL:$1|kaca pisanan kanthi pranala langsung|$1 kaca kanthi pranala langsung}} menyang berkas iki.
[[Special:WhatLinksHere/$2|dhaftar pepak]] uga ana.',
'nolinkstoimage'            => 'Ora ana kaca sing nyambung menyang berkas iki.',
'morelinkstoimage'          => 'Ndeleng [[Special:WhatLinksHere/$1|luwih akèh pranala]] menyang berkas iki.',
'linkstoimage-redirect'     => '$1 (alihan berkas) $2',
'duplicatesoffile'          => '{{PLURAL:$1|berkas ing ngisor arupa duplikat|$1 berkas ing ngisor arupa duplikat}} saka berkas iki ([[Special:FileDuplicateSearch/$2|luwih rinci]]):',
'sharedupload'              => 'Berkas iki saka $1 lan bisa digunakaké déning proyèk liya.',
'sharedupload-desc-there'   => 'Berkas iki asal saka $1 lan bisa dipigunakaké déning proyèk liya.
Mangga pirsani [$2 kaca dhèskripsi berkas] kanggo informasi sabanjuré.',
'sharedupload-desc-here'    => 'Berkas iki asal saka $1 lan bisa dipigunakaké déning proyèk liya.
Dhèskripsi saka [$2 kaca dhèskripsiné] kapacak ing ngisor iki.',
'filepage-nofile'           => 'Ora ana berkas nganggo jeneng iki.',
'filepage-nofile-link'      => 'Ora ana berkas nganggo jeneng iki, nanging panjenengan bisa [$1 ngunggahaké].',
'uploadnewversion-linktext' => 'Unggahna vèrsi sing luwih anyar tinimbang gambar iki',
'shared-repo-from'          => 'saka $1',
'shared-repo'               => 'sawijining panyimpenan kanggo bebarengan',

# File reversion
'filerevert'                => 'Balèkna $1',
'filerevert-legend'         => 'Balèkna berkas',
'filerevert-intro'          => "Panjenengan mbalèkaké '''[[Media:$1|$1]]''' menyang [vèrsi $4 ing $3, $2].",
'filerevert-comment'        => 'Alesan:',
'filerevert-defaultcomment' => 'Dibalèkaké menyang vèrsi ing $2, $1',
'filerevert-submit'         => 'Balèkna',
'filerevert-success'        => "'''[[Media:$1|$1]]''' wis dibalèkaké menyang [vèrsi $4 ing $3, $2].",
'filerevert-badversion'     => 'Ora ana vèrsi lokal sadurungé saka berkas iki mawa stèmpel wektu sing dikarepaké.',

# File deletion
'filedelete'                   => 'Mbusak $1',
'filedelete-legend'            => 'Mbusak berkas',
'filedelete-intro'             => "Panjenengan bakal mbusak berkas '''[[Media:$1|$1]]''' sekaliyan kabèh riwayaté.",
'filedelete-intro-old'         => "Panjenengan mbusak vèrsi '''[[Media:$1|$1]]''' per [$4 $3, $2].",
'filedelete-comment'           => 'Alesan:',
'filedelete-submit'            => 'Busak',
'filedelete-success'           => "'''$1''' wis dibusak.",
'filedelete-success-old'       => "Berkas '''[[Media:$1|$1]]''' vèrsi $3, $2 wis dibusak.",
'filedelete-nofile'            => "'''$1''' ora ana.",
'filedelete-nofile-old'        => "Ora ditemokaké arsip vèrsi saka '''$1''' mawa atribut sing diwènèhaké.",
'filedelete-otherreason'       => 'Alesan tambahan/liya:',
'filedelete-reason-otherlist'  => 'Alesan liya',
'filedelete-reason-dropdown'   => '*Alesan pambusakan
** Nglanggar hak cipta
** Berkas duplikat',
'filedelete-edit-reasonlist'   => 'Sunting alesan pambusakan',
'filedelete-maintenance'       => 'Pambusakan lan pambalikan berkas kanggo sawetara dipatèni salawas ana pangruwatan.',
'filedelete-maintenance-title' => 'Ora bisa mbusak berkas',

# MIME search
'mimesearch'         => 'Panggolèkan MIME',
'mimesearch-summary' => 'Kaca iki nyedyaké fasilitas nyaring berkas miturut tipe MIME-né. Lebokna: contenttype/subtype, contoné <code>image/jpeg</code>.',
'mimetype'           => 'Tipe MIME:',
'download'           => 'undhuh',

# Unwatched pages
'unwatchedpages' => 'Kaca-kaca sing ora diawasi',

# List redirects
'listredirects' => 'Daftar pengalihan',

# Unused templates
'unusedtemplates'     => 'Cithakan sing ora dienggo',
'unusedtemplatestext' => 'Kaca iki ngamot kabèh kaca ing bilik jeneng {{ns:template}} sing ora dianggo ing kaca ngendi waé.
Priksanen dhisik pranala-pranala menyang cithakan iki sadurungé mbusak.',
'unusedtemplateswlh'  => 'pranala liya-liyané',

# Random page
'randompage'         => 'Sembarang kaca',
'randompage-nopages' => 'Ora ana kaca ing {{PLURAL:$2||}}bilik jeneng iki:$1.',

# Random redirect
'randomredirect'         => 'Pangalihan sembarang',
'randomredirect-nopages' => 'Ora ana pangalihan ing bilik jeneng "$1".',

# Statistics
'statistics'                   => 'Statistik',
'statistics-header-pages'      => 'Statistik kaca',
'statistics-header-edits'      => 'Statistik panyuntingan',
'statistics-header-views'      => 'Statistik penampilan',
'statistics-header-users'      => 'Statistik panganggo',
'statistics-header-hooks'      => 'Statistik liya',
'statistics-articles'          => 'Kaca-kaca isi',
'statistics-pages'             => 'Gunggung kaca',
'statistics-pages-desc'        => 'Kabèh kaca ing wiki iki, klebu kaca wicara, pangalihan, lan liya-liyané.',
'statistics-files'             => 'Berkas sing diunggahaké',
'statistics-edits'             => 'Gunggung suntingan wiwit {{SITENAME}} diwiwiti',
'statistics-edits-average'     => 'Rata-rata suntingan saben kaca',
'statistics-views-total'       => 'Gunggung panampilan kaca',
'statistics-views-total-desc'  => 'Delokan nèng kaca sing ora ana lan kaca kusus ora kalebu',
'statistics-views-peredit'     => 'Gunggung/cacahing panampilan saben suntingan',
'statistics-users'             => 'Gunggung [[Special:ListUsers|panganggo kadaftar]]',
'statistics-users-active'      => 'Para panganggo aktif',
'statistics-users-active-desc' => 'Panganggo sing ngayahi aktivitas jroning {{PLURAL:$1|dia|$1 dina}} pungkasan',
'statistics-mostpopular'       => 'Kaca sing paling akèh dideleng',

'disambiguations'      => 'Kaca sing kaubung nèng kaca disambiguasi',
'disambiguationspage'  => 'Template:Disambig',
'disambiguations-text' => "Kaca-kaca iki kaisi paling ora sak pranala nuju '''kaca disambiguasi'''.
Mungkin kuduné diubungaké nèng kaca sing luwih pantes.<br />
Kaca kaanggep kaca disambiguasi yèn kuwi nganggo templat sing kaubung saka [[MediaWiki:Disambiguationspage]].",

'doubleredirects'                   => 'Pangalihan dobel',
'doubleredirectstext'               => 'Kaca iki ngandhut daftar kaca sing ngalih ing kaca pangalihan liyané.
Saben baris ngandhut pranala menyang pangalihan kapisan lan kapindho, sarta tujuan saka pangalihan kapindho, sing biasané kaca tujuan sing "sajatiné", yakuwi pangalihan kapisan kuduné dialihaké menyang kaca tujuan iku.
Jeneng sing wis <del>dicorèk</del> tegesé wis rampung didandani.',
'double-redirect-fixed-move'        => '[[$1]] wis kapindhahaké, saiki dadi kaca peralihan menyang [[$2]]',
'double-redirect-fixed-maintenance' => 'Mbenakaké rong pangalihan saka [[$1]] nèng [[$2]].',
'double-redirect-fixer'             => 'Révisi pangalihan',

'brokenredirects'        => 'Pangalihan rusak',
'brokenredirectstext'    => 'Pengalihan ing ngisor iki tumuju menyang kaca sing ora ana:',
'brokenredirects-edit'   => 'sunting',
'brokenredirects-delete' => 'busak',

'withoutinterwiki'         => 'Kaca tanpa pranala antarbasa',
'withoutinterwiki-summary' => 'Kaca-kaca iki ora nduwé pranala menyang vèrsi ing  basa liyané:',
'withoutinterwiki-legend'  => 'Préfiks',
'withoutinterwiki-submit'  => 'Tuduhna',

'fewestrevisions' => 'Artikel mawa owah-owahan sithik dhéwé',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bita|bita}}',
'ncategories'             => '$1 {{PLURAL:$1|kategori|kategori}}',
'nlinks'                  => '$1 {{PLURAL:$1|pranala|pranala}}',
'nmembers'                => '$1 {{PLURAL:$1|anggota|anggota}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisi|revisi}}',
'nviews'                  => 'Wis kaping $1 {{PLURAL:$1|dituduhaké|dituduhaké}}',
'nimagelinks'             => 'Kanggo nèng {{PLURAL:$1|kaca|kaca}}',
'ntransclusions'          => 'kanggo nèng $1 {{PLURAL:$1|kaca|kaca}}',
'specialpage-empty'       => 'Ora ana sing perlu dilaporaké.',
'lonelypages'             => 'Kaca tanpa dijagani',
'lonelypagestext'         => 'Kaca-kaca ing ngisor iki ora ana sing nyambung menyang kaca liyané ing {{SITENAME}}.',
'uncategorizedpages'      => 'Kaca sing ora dikategorisasi',
'uncategorizedcategories' => 'Kategori sing ora dikategorisasi',
'uncategorizedimages'     => 'Berkas sing ora dikategorisasi',
'uncategorizedtemplates'  => 'Cithakan sing ora dikategorisasi',
'unusedcategories'        => 'Kategori sing ora dienggo',
'unusedimages'            => 'Berkas sing ora dienggo',
'popularpages'            => 'Kaca populèr',
'wantedcategories'        => 'Kategori sing diperlokaké',
'wantedpages'             => 'Kaca sing dipèngini',
'wantedpages-badtitle'    => 'Judhul ora valid ing sèt asil: $1',
'wantedfiles'             => 'Berkas sing diperlokaké',
'wantedfiletext-cat'      => "Berkas iki dianggo nanging ora ana. Berkas saka panyimpenan asing mungkin kadaptar tinimbang ana kasunyatan. Saben ''positip salah'' bakal <del>diorèk</del>. Lan, kaca sing nyartakaké berkas sing ora ana bakal kadaptar nèng [[:$1]].",
'wantedfiletext-nocat'    => "Berkas iki dianggo nanging ora ana. Berkas saka panyimpenan asing mungkin kadaptar tinimbang ana kasunyatan. Saben ''positip salah'' bakal <del>diorèk</del>.",
'wantedtemplates'         => 'Cithakan sing diperlokaké',
'mostlinked'              => 'Kaca sing kerep dhéwé dituju',
'mostlinkedcategories'    => 'Kategori sing kerep dhéwé dienggo',
'mostlinkedtemplates'     => 'Cithakan sing kerep dhéwé dienggo',
'mostcategories'          => 'Kaca sing kategoriné akèh dhéwé',
'mostimages'              => 'Berkas sing kerep dhéwé dienggo',
'mostrevisions'           => 'Kaca mawa pangowahan sing akèh dhéwé',
'prefixindex'             => 'Kabèh kaca mawa ater-ater',
'prefixindex-namespace'   => 'Kabèh kaca mawa ater-ater (bilik jeneng $1)',
'shortpages'              => 'Kaca cendhak',
'longpages'               => 'Kaca dawa',
'deadendpages'            => 'Kaca-kaca buntu (tanpa pranala)',
'deadendpagestext'        => 'kaca-kaca iki ora nduwé pranala tekan ngendi waé ing wiki iki..',
'protectedpages'          => 'Kaca sing direksa',
'protectedpages-indef'    => 'Namung pangreksan ora langgeng waé',
'protectedpages-cascade'  => 'Amung kaca rineksan kang runtut',
'protectedpagestext'      => 'Kaca-kaca sing kapacak iki direksa déning pangalihan utawa panyuntingan.',
'protectedpagesempty'     => 'Saat ini tidak ada halaman yang sedang dilindungi.',
'protectedtitles'         => 'Irah-irahan sing direksa',
'protectedtitlestext'     => 'Irah-irahan sing kapacak ing ngisor iki direksa lan ora bisa digawé',
'protectedtitlesempty'    => 'Ora ana irah-irahan utawa judhul sing direksa karo paramèter-paramèter iki.',
'listusers'               => 'Daftar panganggo',
'listusers-editsonly'     => 'Tampilaké mung panganggo sing nduwèni kontribusi',
'listusers-creationsort'  => 'Urut miturut tanggal digawé',
'usereditcount'           => '$1 {{PLURAL:$1|suntingan|suntingan}}',
'usercreated'             => '{{GENDER:$3|Digawé}} $1 wanci $2',
'newpages'                => 'Kaca anyar',
'newpages-username'       => 'Asma panganggo:',
'ancientpages'            => 'Kaca-kaca langkung sepuh',
'move'                    => 'Pindhahen',
'movethispage'            => 'Pindhahna kaca iki',
'unusedimagestext'        => 'Berkas-berkas sing kapacak iki ana nanging ora dienggo ing kaca apa waé.
Tulung digatèkaké yèn situs wèb liyané mbok-menawa bisa nyambung ing sawijining berkas sacara langsung mawa URL langsung, lan berkas-berkas kaya mengkéné iku mbok-menawa ana ing daftar iki senadyan ora dienggo aktif manèh.',
'unusedcategoriestext'    => 'Kategori iki ana senadyan ora ana artikel utawa kategori liyané sing nganggo.',
'notargettitle'           => 'Ora ana sasaran',
'notargettext'            => 'Panjenengan ora nemtokaké kaca utawa panganggo tujuan fungsi iki.',
'nopagetitle'             => 'Kaca tujuan ora ditemokaké',
'nopagetext'              => 'Kaca sing panjenengan tuju ora ditemokaké.',
'pager-newer-n'           => '{{PLURAL:$1|1 luwih anyar|$1 luwih anyar}}',
'pager-older-n'           => '{{PLURAL:$1|1 luwih lawas|$1 luwih lawas}}',
'suppress'                => "Pangawas (''oversight'')",
'querypage-disabled'      => 'Kaca kusus iki dipatèni kanggo alesan kinerja.',

# Book sources
'booksources'               => 'Sumber buku',
'booksources-search-legend' => 'Golèk ing sumber buku',
'booksources-go'            => 'Golèk',
'booksources-text'          => 'Ing ngisor iki kapacak daftar pranala menyang situs liyané sing ngadol buku anyar lan bekas, lan mbok-menawa uga ndarbèni informasi sabanjuré ngenani buku-buku sing lagi panjenengan golèki:',
'booksources-invalid-isbn'  => 'ISBN sing diwènèhaké katonané ora valid; priksa kasalahan penyalinan saka sumber asli.',

# Special:Log
'specialloguserlabel'  => 'Panampil:',
'speciallogtitlelabel' => 'Patujon (judhul utawa panganggo) :',
'log'                  => 'Log',
'all-logs-page'        => 'Kabèh log publik',
'alllogstext'          => 'Gabungan tampilam kabèh log sing ana ing {{SITENAME}}.
Panjenengan bisa mbatesi tampilan kanthi milih jinis log, jeneng panganggo (sènsitif aksara gedhé/cilik), utawa kaca sing magepokan (uga sènsitif aksara gedhé/cilik).',
'logempty'             => 'Ora ditemokaké èntri log sing pas.',
'log-title-wildcard'   => 'Golèk irah-irahan utawa judhul sing diawali mawa tèks kasebut',

# Special:AllPages
'allpages'          => 'Kabèh kaca',
'alphaindexline'    => '$1 tumuju $2',
'nextpage'          => 'Kaca sabanjuré ($1)',
'prevpage'          => 'Kaca sadurungé ($1)',
'allpagesfrom'      => 'Kaca-kaca kawiwitan kanthi:',
'allpagesto'        => 'Tampilaké kaca dipungkasi ing:',
'allarticles'       => 'Kabèh artikel',
'allinnamespace'    => 'Kabeh kaca ($1 namespace)',
'allnotinnamespace' => 'Sedaya kaca (mboten panggènan asma $1)',
'allpagesprev'      => 'Sadèrèngipun',
'allpagesnext'      => 'Sabanjuré',
'allpagessubmit'    => 'Madosi',
'allpagesprefix'    => 'Kapacak kaca-kaca ingkang mawi ater-ater:',
'allpagesbadtitle'  => 'Irah-irahan (judhul) ingkang dipun-gunaaken boten sah utawi nganggé ater-ater (awalan) antar-basa utawi antar-wiki. Irah-irahan punika saged ugi nganggé setunggal aksara utawi luwih ingkang boten saged kagunaaken dados irah-irahan.',
'allpages-bad-ns'   => '{{SITENAME}} ora duwé bilik nama "$1".',

# Special:Categories
'categories'                    => 'Daftar kategori',
'categoriespagetext'            => '{{PLURAL:$1|kategori ing ngisor iki ngandhut|kategori ing ngisor iki ngandhut}} kaca utawa media.
[[Special:UnusedCategories|Kategori sing ora dianggo]] ora ditampilaké ing kéné.
Deleng uga [[Special:WantedCategories|kategori sing diperlokaké]].',
'categoriesfrom'                => 'Tampilaké kategori-kategori diwiwiti saka:',
'special-categories-sort-count' => 'urutna miturut angka',
'special-categories-sort-abc'   => 'urutna miturut abjad',

# Special:DeletedContributions
'deletedcontributions'             => 'Kontribusi panganggo sing dibusak',
'deletedcontributions-title'       => 'Kontribusi panganggo sing dibusak',
'sp-deletedcontributions-contribs' => 'kontribusi',

# Special:LinkSearch
'linksearch'       => 'Golèkan pranala njaba',
'linksearch-pat'   => 'Pola panggolèkan:',
'linksearch-ns'    => 'Bilik nama:',
'linksearch-ok'    => 'Golèk',
'linksearch-text'  => "''Wildcards'' kaya ta \"*.wikipedia.org\" bisa dienggo.<br />Protokol sing disengkuyung: <code>\$1</code>",
'linksearch-line'  => '$1 disambung saka $2',
'linksearch-error' => "''Wildcards'' namung bisa dienggo ing bagéyan awal saka jeneng host.",

# Special:ListUsers
'listusersfrom'      => 'Tuduhna panganggo sing diawali karo:',
'listusers-submit'   => 'Tuduhna',
'listusers-noresult' => 'Panganggo ora ditemokaké.',
'listusers-blocked'  => '(diblokir)',

# Special:ActiveUsers
'activeusers'            => 'Dhaptar panganggo aktif',
'activeusers-intro'      => 'Iki daptar panganggo sing katon lakuné ing $1 {{PLURAL:$1|dina|dina}} kapungkur.',
'activeusers-count'      => '$1 {{PLURAL:$1|suntingan|suntingan}} ing {{PLURAL:$3|dina|$3 dina}} pungkasan',
'activeusers-from'       => 'Tampilna panganggo wiwit saka:',
'activeusers-hidebots'   => 'Delikna bot',
'activeusers-hidesysops' => 'Delikna pangurus',
'activeusers-noresult'   => 'Panganggo ora ditemokaké.',

# Special:Log/newusers
'newuserlogpage'     => 'Log panganggo anyar',
'newuserlogpagetext' => 'Ing ngisor iki kapacak log pandaftaran panganggo anyar.',

# Special:ListGroupRights
'listgrouprights'                      => 'Hak-hak grup panganggo',
'listgrouprights-summary'              => 'Ing ngisor iki kapacak dhaftar grup panganggo sing didéfinisi ing wiki iki, kanthi hak-hak aksès gandhèngané.
Informasi tambahan perkara hak-hak individual bisa ditemokaké ing [[{{MediaWiki:Listgrouprights-helppage}}|kéné]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Hak sing diidinaké</span>
* <span class="listgrouprights-revoked">Hak sing dijabel</span>',
'listgrouprights-group'                => 'Grup',
'listgrouprights-rights'               => 'Hak-hak',
'listgrouprights-helppage'             => 'Help:Hak-hak grup',
'listgrouprights-members'              => '(daftar anggota)',
'listgrouprights-addgroup'             => 'Bisa nambahaké {{PLURAL:$2|klompok|klompok}}: $1',
'listgrouprights-removegroup'          => 'Bisa mbusak {{PLURAL:$2|klompok|klompok}}: $1',
'listgrouprights-addgroup-all'         => 'Bisa nambahaké kabèh klompok',
'listgrouprights-removegroup-all'      => 'Bisa mbusak kabèh klompok',
'listgrouprights-addgroup-self'        => 'Nambahaké {{PLURAL:$2|klompok|klompok}} menyang akuné dhéwé: $1',
'listgrouprights-removegroup-self'     => 'Mbusak {{PLURAL:$2|klompok|klompok}} saka akuné dhéwé: $1',
'listgrouprights-addgroup-self-all'    => 'Nambahaké kabèh grup menyang akuné dhéwé',
'listgrouprights-removegroup-self-all' => 'Mbusak kabèh klompok saka akuné dhéwé',

# E-mail user
'mailnologin'          => 'Ora ana alamat layang e-mail',
'mailnologintext'      => 'Panjenengan kudu [[Special:UserLogin|mlebu log]] lan kagungan alamat e-mail sing sah ing [[Special:Preferences|preféèrensi]] yèn kersa ngirim layang e-mail kanggo panganggo liya.',
'emailuser'            => 'Kirim e-mail panganggo iki',
'emailpage'            => 'Kirimi panganggo iki layang e-mail',
'emailpagetext'        => 'Panjenengan bisa migunakaké formulir ing ngisor kanggo ngirim layang-e marang panganggo iki.
Alamat layang-e sing panjenengan lebokaké ing [[Special:Preferences|préferèsi panjenengan]] bakal dadi alamat "Saka" jroning layang-e kasebut, mula panampa layang-e bakal bisa mbalesi langsung menyang panjenengan.',
'usermailererror'      => 'Kaluputan obyèk layang:',
'defemailsubject'      => '{{SITENAME}} layang èlèktronik saka panganggo "$1"',
'usermaildisabled'     => 'E-mail panganggo dinonaktifaké',
'usermaildisabledtext' => 'Sampéyan ora bisa ngirim layang èlèktronik nèng panganggo liya nèng wiki iki',
'noemailtitle'         => 'Ora ana alamat layang e-mail',
'noemailtext'          => 'Panganggo iki ora mènèhi alamat layang-e sing absah.',
'nowikiemailtitle'     => 'Layang-e ora diidinaké',
'nowikiemailtext'      => 'Panganggo iki wis milih ora nampa layang-e saka panganggo liya.',
'emailnotarget'        => 'Jeneng panganggo panampa ora ana utawa ora sah.',
'emailtarget'          => 'Lebokaké jeneng panganggo panampa',
'emailusername'        => 'Jeneng panganggo:',
'emailusernamesubmit'  => 'Kirim',
'email-legend'         => 'Ngirim layang-e katujokaké panganggo  {{SITENAME}} liyané',
'emailfrom'            => 'Saka:',
'emailto'              => 'Kanggo:',
'emailsubject'         => 'Prekara:',
'emailmessage'         => 'Pesen:',
'emailsend'            => 'Kirim',
'emailccme'            => 'Kirimana aku salinan pesenku.',
'emailccsubject'       => 'Salinan pesen panjenengan kanggo $1: $2',
'emailsent'            => 'Layang e-mail wis dikirim',
'emailsenttext'        => 'Layang e-mail panjenengan wis dikirim.',
'emailuserfooter'      => 'Layang-e iki dikirimaké déning $1 marang $2 migunakaké fungsi "Layangpanganggo" ing {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Tinggalaké layang sistem.',
'usermessage-editor'  => 'Pawartaning layang sistem',

# Watchlist
'watchlist'            => 'Daftar artikel pilihan',
'mywatchlist'          => 'Daftar pangawasanku',
'watchlistfor2'        => 'Kanggo $1 $2',
'nowatchlist'          => 'Daftar pangawasan panjenengan kosong.',
'watchlistanontext'    => 'Mangga $1 kanggo mirsani utawa nyunting daftar pangawasan panjenengan.',
'watchnologin'         => 'Durung mlebu log',
'watchnologintext'     => 'Panjenengan kudu [[Special:UserLogin|mlebu log]] kanggo ngowahi daftar artikel pilihan.',
'addwatch'             => 'Tambah nèng daptar pangawasan',
'addedwatchtext'       => "Kaca \"[[:\$1]]\" wis ditambahaké menyang [[Special:Watchlist|daftar pangawasan]].
Owah-owahan sing dumadi ing tembé ing kaca iku lan kaca dhiskusi sing kagandhèng, bakal dipacak ing kéné, lan kaca iku bakal dituduhaké '''kandel''' ing [[Special:RecentChanges|daftar owah-owahan iku]] supados luwih gampang katon.",
'removewatch'          => 'Singkiraké saka daptar pangawasan',
'removedwatchtext'     => 'Kaca "[[:$1]]" wis dibusak saka [[Special:Watchlist|daftar pangawasan]].',
'watch'                => 'tutana',
'watchthispage'        => 'Periksa kaca iki',
'unwatch'              => 'Ora usah ngawasaké manèh',
'unwatchthispage'      => 'Batalna olèhé ngawasi kaca iki',
'notanarticle'         => 'Dudu kaca artikel',
'notvisiblerev'        => 'Révisi wis dibusak',
'watchnochange'        => 'Ora ana kaca ing daftar pangawasan panjenengan sing diowahi ing mangsa wektu sing dipilih.',
'watchlist-details'    => 'Ngawasaké {{PLURAL:$1|$1 kaca|$1 kaca}}, ora kalebu kaca-kaca dhiskusi.',
'wlheader-enotif'      => '* Notifikasi e-mail diaktifaké.',
'wlheader-showupdated' => "* Kaca-kaca sing wis owah wiwit ditiliki panjenengan kaping pungkasan, dituduhaké mawa '''aksara kandel'''",
'watchmethod-recent'   => 'priksa daftar owah-owahan anyar kanggo kaca sing diawasi',
'watchmethod-list'     => 'priksa kaca sing diawasi kanggo owah-owahan anyar',
'watchlistcontains'    => 'Daftar pangawasan panjenengan isiné ana $1 {{PLURAL:$1|kaca|kaca}}.',
'iteminvalidname'      => "Ana masalah karo '$1', jenengé ora absah...",
'wlnote'               => "Ngisor iki {{PLURAL:$1|owahan pungkasan|'''$1''' owahan pungkasan}} {{PLURAL:$2|jam|'''$2''' jam}} kapungkur, per $3, $4.",
'wlshowlast'           => 'Tuduhna $1 jam $2 dina $3 pungkasan',
'watchlist-options'    => 'Opsi daftar pangawasan',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'Ngawasi...',
'unwatching'     => 'Ngilangi pangawasan...',
'watcherrortext' => 'Ana kasalahan nalika ngganti pangaturan daptar pangawasan Sampéyan kanggo "$1".',

'enotif_mailer'                => 'Pangirim Notifikasi {{SITENAME}}',
'enotif_reset'                 => 'Tandhanana kabèh kaca sing wis ditiliki',
'enotif_newpagetext'           => 'Iki sawijining kaca anyar.',
'enotif_impersonal_salutation' => 'Panganggo {{SITENAME}}',
'changed'                      => 'kaubah',
'created'                      => 'kadamel',
'enotif_subject'               => 'Kaca $PAGETITLE ing {{SITENAME}} wis $CHANGEDORCREATED déning $PAGEEDITOR',
'enotif_lastvisited'           => 'Deleng $1 kanggo kabèh owah-owahan wiwit pungkasan panjenengan niliki.',
'enotif_lastdiff'              => 'Tilikana $1 kanggo mirsani owah-owahan iki.',
'enotif_anon_editor'           => 'panganggo anonim $1',
'enotif_body'                  => 'Sing minulya $WATCHINGUSERNAME,

Kaca $PAGETITLE ing {{SITENAME}} wis $CHANGEDORCREATED ing $PAGEEDITDATE déning $PAGEEDITOR, mangga mirsani $PAGETITLE_URL kanggo vèrsi pungkasan.

$NEWPAGE

Sajarah suntingan: $PAGESUMMARY $PAGEMINOREDIT

Hubungana panyunting:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Kita ora bakal ngandhani manèh yèn diowahi manèh, kejaba panjenengan wis mirsani kaca iku. Panjenengan uga bisa mbusak tandha notifikasi kanggo kabèh kaca pangawasan ing daftar pangawasan panjenengan.

             Sistém notifikasi {{SITENAME}}

--
Kanggo ngowahi préferènsi ing daftar pangawasan panjenengan, mangga mirsani
{{canonicalurl:{{#special:EditWatchlist}}}}

Umpan balik lan pitulung sabanjuré:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Busak kaca',
'confirm'                => 'Dhedhes (konfirmasi)',
'excontent'              => "isi sadurungé: '$1'",
'excontentauthor'        => "isiné mung arupa: '$1' (lan siji-sijiné sing nyumbang yaiku '$2')",
'exbeforeblank'          => "isi sadurungé dikosongaké: '$1'",
'exblank'                => 'kaca kosong',
'delete-confirm'         => 'Busak "$1"',
'delete-legend'          => 'Busak',
'historywarning'         => "'''Pènget''': Kaca sing bakal panjenengan busak ana sajarahé kanthi $1 {{PLURAL:$1|révisi|révisi}}:",
'confirmdeletetext'      => 'Panjenengan bakal mbusak kaca utawa berkas iki minangka permanèn karo kabèh sajarahé saka basis data. Pastèkna dhisik menawa panjenengan pancèn nggayuh iki, ngerti kabèh akibat lan konsekwènsiné, lan apa sing bakal panjenengan tumindak iku cocog karo [[{{MediaWiki:Policy-url}}|kawicaksanan {{SITENAME}}]].',
'actioncomplete'         => 'Proses tuntas',
'actionfailed'           => 'Tindakan gagal',
'deletedtext'            => '"$1" sampun kabusak. Coba pirsani $2 kanggé log paling énggal kaca ingkang kabusak.',
'dellogpage'             => 'Cathetan pambusakan',
'dellogpagetext'         => 'Ing ngisor iki kapacak log pambusakan kaca sing anyar dhéwé.',
'deletionlog'            => 'Cathetan sing dibusak',
'reverted'               => 'Dibalèkaké ing revisi sadurungé',
'deletecomment'          => 'Alesan:',
'deleteotherreason'      => 'Alesan liya utawa tambahan:',
'deletereasonotherlist'  => 'Alesan liya',
'deletereason-dropdown'  => '*Alesan pambusakan
** Disuwun sing nulis
** Nglanggar hak cipta
** Vandalisme',
'delete-edit-reasonlist' => 'Sunting alesan pambusakan',
'delete-toobig'          => 'Kaca iki ndarbèni sajarah panyuntingan sing dawa, yaiku ngluwihi $1 {{PLURAL:$1|revision|révisi}}.
Pambusakan kaca sing kaya mangkono mau wis ora diparengaké kanggo menggak anané karusakan ing {{SITENAME}}.',
'delete-warning-toobig'  => 'Kaca iki duwé sajarah panyuntingan sing dawa, luwih saka $1 {{PLURAL:$1|révisi|révisi}}.
Mbusak kaca iki bisa ngrusak operasi basis data ing {{SITENAME}};
kudu ngati-ati.',

# Rollback
'rollback'          => 'Mbalèkaké suntingan',
'rollback_short'    => 'Balèkna',
'rollbacklink'      => 'balèaké',
'rollbackfailed'    => 'Pambalèkan gagal dilakoni',
'cantrollback'      => 'Ora bisa mbalèkaké suntingan; panganggo pungkasan iku siji-sijiné penulis artikel iki.',
'alreadyrolled'     => 'Ora bisa mbalèkaké suntingan pungkasan [[:$1]] déning [[User:$2|$2]] ([[User talk:$2|Wicara]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); wong liya wis nyunting utawa mbalèkaké kaca artikel iku.

Suntingan pungkasan dilakoni déning [[User:$3|$3]] ([[User talk:$3|Wicara]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Ringkesan suntingan yaiku: \"''\$1''\".",
'revertpage'        => 'Suntingan [[Special:Contributions/$2|$2]] ([[User talk:$2|dhiskusi]]) dipunwangsulaken dhateng ing vèrsi pungkasan déning [[User:$1|$1]]',
'revertpage-nouser' => 'Suntingan dibalèkaké déning (jeneng panganggo dibusak) nèng benahan pungkasan déning [[User:$1|$1]]',
'rollback-success'  => 'Suntingan dibalèkaké déning $1;
diowahi bali menyang vèrsi pungkasan déning $2.',

# Edit tokens
'sessionfailure-title' => 'Sèsi gagal',
'sessionfailure'       => 'Katoné ana masalah karo sèsi log panjenengan; log panjenengan wis dibatalaké kanggo nyegah pambajakan. Mangga mencèt tombol "back" lan unggahaké manèh kaca sadurungé mlebu log, lan coba manèh.',

# Protect
'protectlogpage'              => 'Log pangreksan',
'protectlogtext'              => 'Ngisor iki daptar owahan saka panjagan kaca.
Delok [[Special:ProtectedPages|daptar kaca sing dijaga]] kanggo daptar panjagan kaca paling anyar.',
'protectedarticle'            => 'ngreksa "[[$1]]"',
'modifiedarticleprotection'   => 'ngowahi tingkat pangreksan "[[$1]]"',
'unprotectedarticle'          => 'nyingkiraké panjagan saka "[[$1]]"',
'movedarticleprotection'      => 'mindhahaké pangaturan protèksi saka "[[$2]]" menyang "[[$1]]"',
'protect-title'               => 'Ngowahi tingkatan pangreksan kanggo "$1"',
'protect-title-notallowed'    => 'Delok undhaké panjagan saka "$1"',
'prot_1movedto2'              => '$1 dialihaké menyang $2',
'protect-badnamespace-title'  => 'Bilik jeneng sing ora bisa dijagani',
'protect-badnamespace-text'   => 'Kaca nèng bilik jeneng iki ora bisa dijagani.',
'protect-legend'              => 'Konfirmasi pangreksan',
'protectcomment'              => 'Alesan:',
'protectexpiry'               => 'Kadaluwarsa:',
'protect_expiry_invalid'      => 'Wektu kadaluwarsané ora sah.',
'protect_expiry_old'          => 'Wektu kadaluwarsané kuwi ana ing jaman biyèn.',
'protect-unchain-permissions' => 'Urubaké pilihan panjagan sabanjuré',
'protect-text'                => "Panjenengan bisa mirsani utawa ngganti tingkatan pangreksan kanggo kaca '''$1''' ing kéné.",
'protect-locked-blocked'      => "Panjenengan ora bisa ngganti tingkat pangreksan yèn lagi diblokir.
Ing ngisor iki kapacak konfigurasi saiki iki kanggo kaca '''$1''':",
'protect-locked-dblock'       => "Tingkat pangreksan ora bisa diganti amerga anané panguncèn aktif basis data.
Ing ngisor iki kapacak konfigurasi kanggo kaca '''$1''':",
'protect-locked-access'       => "Akun utawa rékening panjenengan ora awèh idin kanggo ngganti tingkat pangreksan kaca. Ing ngisor iki kapacak konfigurasi saiki iki kanggo kaca '''$1''':",
'protect-cascadeon'           => 'Kaca iki lagi direksa amerga disertakaké ing {{PLURAL:$1|kaca|kaca-kaca}} sing wis direksa mawa pilihan pangreksan runtun diaktifaké. Panjenengan bisa ngganti tingkat pangreksan kanggo kaca iki, nanging perkara iku ora awèh pengaruh pangreksan runtun.',
'protect-default'             => 'Idinaké kabèh panganggo',
'protect-fallback'            => 'Perlu idin hak aksès "$1"',
'protect-level-autoconfirmed' => 'Blokir panganggo anyar lan ora kadhaptar',
'protect-level-sysop'         => 'Namung opsis (operator sistem)',
'protect-summary-cascade'     => 'runtun',
'protect-expiring'            => 'kadaluwarsa $1 (UTC)',
'protect-expiring-local'      => 'kedaluwarsa $1',
'protect-expiry-indefinite'   => 'salawasé',
'protect-cascade'             => 'Reksanen kabèh kaca sing kalebu ing kaca iki (pangreksan runtun).',
'protect-cantedit'            => 'Panjenengan ora pareng ngowahi tingkatan pangreksan kaca iki amerga panjenengan ora kagungan idin nyunting kaca iki.',
'protect-othertime'           => 'Wektu liya:',
'protect-othertime-op'        => 'wektu liya',
'protect-existing-expiry'     => 'Wektu kadaluwarsa saiki: $3, $2',
'protect-otherreason'         => 'Alesan liya/tambahan:',
'protect-otherreason-op'      => 'Alesan liya',
'protect-dropdown'            => '*Alesan umum pangreksan
** Vandalisme makaping-kaping
** Spam makaping-kaping
** Perang suntingan
** Kaca kerep disunting',
'protect-edit-reasonlist'     => 'Nyunting alesan reksan',
'protect-expiry-options'      => '1 jam:1 hour,1 dina:1 day,1 minggu:1 week,2 minggu:2 weeks,1 sasi:1 month,3 sasi:3 months,6 sasi:6 months,1 taun:1 year,tanpa wates:infinite',
'restriction-type'            => 'Pangreksan:',
'restriction-level'           => 'Tingkatan pambatesan:',
'minimum-size'                => 'Ukuran minimum',
'maximum-size'                => 'Ukuran maksimum:',
'pagesize'                    => '(bita)',

# Restrictions (nouns)
'restriction-edit'   => 'Panyuntingan',
'restriction-move'   => 'Pamindhahan',
'restriction-create' => 'Gawé',
'restriction-upload' => 'Unggah',

# Restriction levels
'restriction-level-sysop'         => 'pangreksan kebak',
'restriction-level-autoconfirmed' => 'pangreksan sémi',
'restriction-level-all'           => 'kabèh tingkatan',

# Undelete
'undelete'                     => 'Kembalikan halaman yang telah dihapus',
'undeletepage'                 => 'Lihat dan kembalikan halaman yang telah dihapus',
'undeletepagetitle'            => "'''Ing ngisor iki kapacak daftar révisi sing dibusak saka [[:$1]]'''.",
'viewdeletedpage'              => 'Deleng kaca sing wis dibusak',
'undeletepagetext'             => '{{PLURAL:$1|kaca iki wis dibusak nanging isih|$1 kaca iki wis dibusak nanging isih}} ana ing arsip lan bisa dibalèkaké.
Arsip bisa diresiki sakala-kala.',
'undelete-fieldset-title'      => 'Mulihaké rèvisi',
'undeleteextrahelp'            => "Kanggo mbalèkaké kabèh sajarah kaca, kothongaké kabèh kothak-cèk lan klik '''''Balèkna'''''.
Kanggo nglakoni pambalèkan pinilih, conthèngen kothak-cèk  sing magepokan karo révisi sing dipéngini lan klik '''''Balèkna'''''.
Mencèt tombol '''''Reset''''' bakal ngosongaké isi komentar lan kabèh kothak-cèk.",
'undeleterevisions'            => '$1 {{PLURAL:$1|révisi|révisi}} diarsipaké',
'undeletehistory'              => 'Yèn panjenengan mbalèkaké kaca, kabèh révisi bakal dibalèkaké jroning sajarah.
Yèn sawijining kaca anyar kanthi jeneng sing padha wis digawé wiwit nalika pambusakan, révisi sing wis dibalèkaké bakal katon jroning sajarah sadurungé.',
'undeleterevdel'               => 'Pambatalan pambusakan ora bakal dilakokaké yèn bab iku bakal ngakibataké révisi pungkasan kaca dadi sabagéyan kabusak.
Ing kasus kaya mengkono, panjenengan kudu ngilangaké cèk utawa mbusak pandelikan révisi kabusak sing anyar dhéwé.',
'undeletehistorynoadmin'       => 'Kaca iki wis dibusak.
Alesané dituduhaké ing ringkesan ing ngisor iki, karo détail para panganggo sing wis nyunting kaca iki sadurungé dibusak.
Isi pungkasan tèks iki wis dibusak lan namung bisa dideleng para pangurus.',
'undelete-revision'            => 'Révisi sing wis dibusak saka $1 (ing $5, $4) déning $3:',
'undeleterevision-missing'     => 'Revisi salah utawa ora ditemokaké.
Panjenengan mbokmenawa ngetutaké pranala sing salah, utawa revisi iku wis dipulihaké utawa diguwang saka arsip.',
'undelete-nodiff'              => 'Ora ditemokaké révisi sing luwih lawas.',
'undeletebtn'                  => 'Balèkna!',
'undeletelink'                 => 'pirsani/balèkna',
'undeleteviewlink'             => 'pirsani',
'undeletereset'                => "''Reset''",
'undeleteinvert'               => 'Walik pilihan',
'undeletecomment'              => 'Alesan:',
'undeletedrevisions'           => '$1 {{PLURAL:$1|révisi|révisi}} wis dibalèkaké',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|révisi|révisi}} lan $2 berkas dibalèkaké',
'undeletedfiles'               => '$1 {{PLURAL:$1|berkas|berkas}} dibalèkaké',
'cannotundelete'               => 'Olèhé mbatalaké pambusakan gagal;
mbokmenawa wis ana wong liya sing luwih dhisik nglakoni pambatalan.',
'undeletedpage'                => "'''$1 bisa dibalèkaké'''

Delengen [[Special:Log/delete|log pambusakan]] kanggo data pambusakan lan pambalèkan.",
'undelete-header'              => 'Mangga mirsani [[Special:Log/delete|log pambusakan]] kanggo daftar kaca sing lagi waé dibusak.',
'undelete-search-title'        => 'Golèk kaca kabusak',
'undelete-search-box'          => 'Golèk kaca-kaca sing wis dibusak',
'undelete-search-prefix'       => 'Tuduhna kaca sing diwiwiti karo:',
'undelete-search-submit'       => 'Golèk',
'undelete-no-results'          => 'Ora ditemokaké kaca sing cocog ing arsip pambusakan.',
'undelete-filename-mismatch'   => 'Ora bisa mbatalaké pambusakan révisi berkas mawa tandha wektu $1: jeneng berkas ora padha',
'undelete-bad-store-key'       => 'Ora bisa mbatalaké pambusakan révisi berkas mawa tandha wektu $1: berkas ilang sadurungé dibusak.',
'undelete-cleanup-error'       => 'Ana kaluputan nalika mbusak arsip berkas "$1" sing ora dienggo.',
'undelete-missing-filearchive' => 'Ora bisa mbalèkaké arsip bekas mawa ID $1 amerga ora ana ing basis data.
Berkas iku mbok-menawa wis dibusak.',
'undelete-error'               => 'Kasalahan mbalèkaké kaca',
'undelete-error-short'         => 'Kaluputan olèhé mbatalaké pambusakan: $1',
'undelete-error-long'          => 'Ana kaluputan nalika mbatalaké pambusakan berkas:

$1',
'undelete-show-file-confirm'   => 'Apa panjenengan yakin arep mirsani révisi berkas "<nowiki>$1</nowiki>" sing wis kabusak ing $2 jam $3?',
'undelete-show-file-submit'    => 'Ya',

# Namespace form on various pages
'namespace'                     => 'Bilik nama (bilik jeneng):',
'invert'                        => 'Balèkna pilihan',
'tooltip-invert'                => 'Centhang kothak iki kanggo ndhelikaké owahan saka kaca-kaca nèng njero bilik jeneng kapilih (lan bilik jeneng kakait yèn dicenthang)',
'namespace_association'         => 'Bilik jeneng kakait',
'tooltip-namespace_association' => 'Centhang kothak iki kanggo nglebokaké uga bilik jeneng gumenan utawa subyèk sing kakait karo bilik jeneng kapilih',
'blanknamespace'                => '(Utama)',

# Contributions
'contributions'       => 'Sumbangan panganggo',
'contributions-title' => 'Kontribusi panganggo kanggo $1',
'mycontris'           => 'Kontribusiku',
'contribsub2'         => 'Kanggo $1 ($2)',
'nocontribs'          => 'Ora ditemokaké owah-owahan sing cocog karo kritéria kasebut iku.',
'uctop'               => ' (dhuwur)',
'month'               => 'Wiwit sasi (lan sadurungé):',
'year'                => 'Wiwit taun (lan sadurungé):',

'sp-contributions-newbies'             => 'Namung panganggo-panganggo anyar',
'sp-contributions-newbies-sub'         => 'Kanggo panganggo anyar',
'sp-contributions-newbies-title'       => 'Kontribusi panganggo anyar',
'sp-contributions-blocklog'            => 'Log pemblokiran',
'sp-contributions-deleted'             => 'kontribusi panganggo sing dibusak',
'sp-contributions-uploads'             => 'unggahan',
'sp-contributions-logs'                => 'log',
'sp-contributions-talk'                => 'wicara',
'sp-contributions-userrights'          => 'pengaturan hak panganggo',
'sp-contributions-blocked-notice'      => 'Panganggo iki lagi diblokir.
Èntri log blokiran pungkasan sumadhiya nèng ngisor kanggo rujukan:',
'sp-contributions-blocked-notice-anon' => 'Alamat IP iki lagi diblokir.
Èntri log blokiran pungkasan sumadhiya nèng ngisor kanggo rujukan:',
'sp-contributions-search'              => 'Golèk kontribusi',
'sp-contributions-username'            => 'Alamat IP utawa jeneng panganggo:',
'sp-contributions-toponly'             => 'Tuduhaké was suntingan saka benahan pungkasan',
'sp-contributions-submit'              => 'Golèk',

# What links here
'whatlinkshere'            => 'Pranala balik',
'whatlinkshere-title'      => 'Kaca-kaca sing duwé pranala menyang "$1"',
'whatlinkshere-page'       => 'Kaca:',
'linkshere'                => "Kaca-kaca iki nduwé pranala menyang '''[[:$1]]''':",
'nolinkshere'              => "Ora ana kaca sing nduwé pranala menyang '''[[:$1]]'''.",
'nolinkshere-ns'           => " Ora ana kaca sing nduwé pranala menyang '''[[:$1]]''' ing bilik jeneng sing kapilih.",
'isredirect'               => 'kaca pangalihan',
'istemplate'               => 'karo cithakan',
'isimage'                  => 'pranala berkas',
'whatlinkshere-prev'       => '{{PLURAL:$1|sadurungé|$1 sadurungé}}',
'whatlinkshere-next'       => '{{PLURAL:$1|sabanjuré|$1 sabanjuré}}',
'whatlinkshere-links'      => '← pranala',
'whatlinkshere-hideredirs' => '$1 pangalihan-pangalihan',
'whatlinkshere-hidetrans'  => '$1 transklusi',
'whatlinkshere-hidelinks'  => 'pranala-pranala $1',
'whatlinkshere-hideimages' => '$1 pranala-pranala berkas',
'whatlinkshere-filters'    => 'Filter-filter',

# Block/unblock
'autoblockid'                     => 'Blokir otomatis #$1',
'block'                           => 'Blokir panganggo',
'unblock'                         => 'Uculaké blokirané panganggo',
'blockip'                         => 'Blokir panganggo',
'blockip-title'                   => 'Blokir panganggo',
'blockip-legend'                  => 'Blokir panganggo',
'blockiptext'                     => 'Enggonen formulir ing ngisor iki kanggo mblokir sawijining alamat IP utawa panganggo supaya ora bisa nyunting kaca.
Prekara iki perlu dilakoni kanggo menggak vandalisme, lan miturut [[{{MediaWiki:Policy-url}}|kawicaksanan {{SITENAME}}]].
Lebokna alesan panjenengan ing ngisor iki (contoné njupuk conto kaca sing wis tau dirusak).',
'ipadressorusername'              => 'Alamat IP utawa jeneng panganggo',
'ipbexpiry'                       => 'Kadaluwarsa',
'ipbreason'                       => 'Alesan:',
'ipbreasonotherlist'              => 'Alesan liya',
'ipbreason-dropdown'              => '*Alesan umum mblokir panganggo
** Mènèhi informasi palsu
** Ngilangi isi kaca
** Spam pranala menyang situs njaba
** Nglebokaké tulisan ngawur ing kaca
** Tumindak intimidasi/nglècèhaké
** Nyalahgunakaké sawetara akun utawa rékening
** Jeneng panganggo ora layak',
'ipb-hardblock'                   => 'Alangi panganggo sing wis mlebu log nyunting saka alamat IP iki',
'ipbcreateaccount'                => 'Penggak nggawé akun utawa rékening',
'ipbemailban'                     => 'Penggak panganggo ngirim layang e-mail',
'ipbenableautoblock'              => 'Blokir alamat IP pungkasan sing dienggo déning pengguna iki sacara otomatis, lan kabèh alamat sabanjuré sing dicoba arep dienggo nyunting.',
'ipbsubmit'                       => 'Kirimna',
'ipbother'                        => 'Wektu liya',
'ipboptions'                      => '2 jam:2 hours,1 dina:1 day,3 dina:3 days,1 minggu:1 week,2 minggu:2 weeks,1 sasi:1 month,3 sasi:3 months,6 sasi:6 months,1 taun:1 year,tanpa wates:infinite',
'ipbotheroption'                  => 'liyané',
'ipbotherreason'                  => 'Alesan liya/tambahan',
'ipbhidename'                     => 'Delikna jeneng panganggo saka suntingan lan pratélan',
'ipbwatchuser'                    => 'Ngawasi kaca panganggo lan kaca-kaca dhiskusi panganggo iki',
'ipb-disableusertalk'             => 'Alangi panganggo iki nyunting kaca gunemané nalika diblokir',
'ipb-change-block'                => 'Blokir manèh panganggo kanthi sèting iki',
'ipb-confirm'                     => 'Pesthèkaké blokir',
'badipaddress'                    => 'Alamat IP klèntu',
'blockipsuccesssub'               => 'Pemblokiran suksès',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] wis diblokir.<br />
Delok [[Special:BlockList|daptar blokir]] kanggo ninjo blokiran.',
'ipb-blockingself'                => 'Sampéyan arep mblokir Sampéyan dhéwé! Sampéyan yakin arep nglakoni kuwi?',
'ipb-confirmhideuser'             => 'Sampéyan arep mblokir panganggo mawa piranti "dhelikaké panganggo" isih murub. Iki bakal nyegah jeneng panganggo ana ing kabèh daptar lan èntri log. Sampéyan yakin arep nglakoni kuwi?',
'ipb-edit-dropdown'               => 'Sunting alesan pamblokiran',
'ipb-unblock-addr'                => 'Ilangna blokir $1',
'ipb-unblock'                     => 'Ilangna blokir sawijining panganggo utawa alamat IP',
'ipb-blocklist'                   => 'Ndeleng blokir sing lagi ditrapaké',
'ipb-blocklist-contribs'          => 'Kontribusi kanggo $1',
'unblockip'                       => 'Jabel blokir marang alamat IP utawa panganggo',
'unblockiptext'                   => 'Nggonen formulir ing ngisor iki kanggo mbalèkaké aksès nulis sawijining alamt IP utawa panganggo sing sadurungé diblokir.',
'ipusubmit'                       => 'Ilangna blokir iki',
'unblocked'                       => 'Blokir marang [[User:$1|$1]] wis dijabel',
'unblocked-range'                 => '$1 ora diblokir manèh',
'unblocked-id'                    => 'Blokir $1 wis dijabel',
'blocklist'                       => 'Panganggo diblokir',
'ipblocklist'                     => 'Panganggo diblokir',
'ipblocklist-legend'              => 'Golèk panganggo sing diblokir',
'blocklist-userblocks'            => 'Dhelikaké blokiran akun',
'blocklist-tempblocks'            => 'Dhelikaké blokiran sawetara',
'blocklist-addressblocks'         => 'Dhelikaké blokiran IP tunggal',
'blocklist-rangeblocks'           => 'Dhelikaké adohé blokiran',
'blocklist-timestamp'             => 'Cap wektu',
'blocklist-target'                => 'Patujon',
'blocklist-expiry'                => 'Kedaluwarsa',
'blocklist-by'                    => 'Pangurus pamblokir',
'blocklist-params'                => 'Paramèter blokiran',
'blocklist-reason'                => 'Alesan',
'ipblocklist-submit'              => 'Golèk',
'ipblocklist-localblock'          => 'Blokade lokal',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Blokiran|Blokiran}} liya',
'infiniteblock'                   => 'salawasé',
'expiringblock'                   => 'kadaluwarsa ing $1, $2',
'anononlyblock'                   => 'namung anon',
'noautoblockblock'                => 'pamblokiran otomatis dipatèni',
'createaccountblock'              => 'ndamelipun akun dipunblokir',
'emailblock'                      => 'layang e-mail diblokir',
'blocklist-nousertalk'            => 'ora éntuk nyunting kaca gunemané dhéwé',
'ipblocklist-empty'               => 'Daftar pamblokiran kosong.',
'ipblocklist-no-results'          => 'alamat IP utawa panganggo sing disuwun ora diblokir.',
'blocklink'                       => 'blokir',
'unblocklink'                     => 'jabel blokir',
'change-blocklink'                => 'owahi blokir',
'contribslink'                    => 'sumbangan',
'emaillink'                       => 'kirim layang èlèktronik',
'autoblocker'                     => 'Panjenengan otomatis dipun-blok amargi nganggé alamat protokol internet (IP) ingkang sami kaliyan "[[User:$1|$1]]". Alesanipun $1 dipun blok inggih punika "\'\'\'$2\'\'\'"',
'blocklogpage'                    => 'Log pamblokiran',
'blocklog-showlog'                => 'Panganggo iki wis tau diblokir sakdurungé.
Log blokiran sumadhiya nèng ngisor kanggo rujukan:',
'blocklog-showsuppresslog'        => 'Panganggo iki wis tau diblokir lan didhelikaké sakdurungé.
Log brèdèlan sumadhiya nèng ngisor kanggo rujukan:',
'blocklogentry'                   => 'mblokir "[[$1]]" dipun watesi wedalipun $2 $3',
'reblock-logentry'                => 'Ngowahi sèting pamblokiran [[$1]] kanthi wektu daluwarsa $2 $3',
'blocklogtext'                    => 'Ing ngisor iki kapacak log pamblokiran lan panjabelan blokir panganggo.
Alamat IP sing diblokir sacara otomatis ora ana ing daftar iki.
Mangga mirsani [[Special:BlockList|daftar alamat IP sing diblokir]] kanggo daftar blokir pungkasan.',
'unblocklogentry'                 => 'njabel blokir "$1"',
'block-log-flags-anononly'        => 'namung panganggo anonim waé',
'block-log-flags-nocreate'        => 'opsi nggawé akun utawa rékening dipatèni',
'block-log-flags-noautoblock'     => 'blokir otomatis dipatèni',
'block-log-flags-noemail'         => 'e-mail diblokir',
'block-log-flags-nousertalk'      => 'ora éntuk nyunting kaca gunemané dhéwé',
'block-log-flags-angry-autoblock' => 'paningkatan sistem pamblokiran otomatis wis diaktifaké',
'block-log-flags-hiddenname'      => 'jeneng panganggo didhelikaké',
'range_block_disabled'            => 'Fungsi pamblokir blok IP kanggo para opsis dipatèni.',
'ipb_expiry_invalid'              => 'Wektu kadaluwarsa ora absah.',
'ipb_expiry_temp'                 => 'Pamblokiran tumrap jeneng panganggo sing didhelikaké kudu permanèn.',
'ipb_hide_invalid'                => 'Ora bisa ndhelikaké akun iki; manawa wis kakèhan suntingan.',
'ipb_already_blocked'             => '"$1" wis diblokir',
'ipb-needreblock'                 => '$1 wis diblokir. Apa panjenengan sedya ngowahi patrapan blokiran kasebut?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Blokiran|Blokiran}} liya',
'unblock-hideuser'                => 'Sampéyan ora bisa mbukak blokiran panganggo iki amarga jeneng panganggoné didhelikaké.',
'ipb_cant_unblock'                => 'Kaluputan: Blokir mawa ID $1 ora ditemokaké. Blokir iku mbok-menawa wis dibuka.',
'ipb_blocked_as_range'            => 'Kaluputan: IP $1 ora diblokir sacara langsung lan ora bisa dijabel blokiré. IP $1 diblokir mawa bagéyan saka pamblokiran kelompok IP $2, sing bisa dijabel pamblokirané.',
'ip_range_invalid'                => 'Blok IP ora absah.',
'ip_range_toolarge'               => 'Jangkahé blokiran luwih gedhé saka /$1 ora dililakaké.',
'blockme'                         => 'Blokiren aku',
'proxyblocker'                    => 'Pamblokir proxy',
'proxyblocker-disabled'           => 'Fungsi iki saiki lagi dipatèni.',
'proxyblockreason'                => "Alamat IP panjenengan wis diblokir amerga alamat IP panjenengan iku ''open proxy''.
Mangga ngubungi sing nyedyakaké dines internèt panjenengan utawa pitulungan tèknis lan aturana masalah kaamanan sérius iki.",
'proxyblocksuccess'               => 'Bubar.',
'sorbsreason'                     => "Alamat IP panjenengan didaftar minangka ''open proxy'' ing DNSBL.",
'sorbs_create_account_reason'     => "Alamat IP panjenengan didaftar minangka ''open proxy'' ing DNSBL. Panjenengan ora bisa nggawé akun utawa rékening.",
'cant-block-while-blocked'        => 'Panjenengan ora bisa mblokir panganggo liya nalika panjenengan dhéwé pinuju diblokir.',
'cant-see-hidden-user'            => 'Panganggo sing Sampéyan coba blokir wis kablokir lan didhelikaké.
Amarga Sampéyan ora nduwé hak ndhelikaké panganggo, Sampéyan ora bisa ndelok utawa nyunting blokiran panganggo.',
'ipbblocked'                      => 'Sampéyan ora bisa mblokir utawa mbukak blokiran panganggo liya amarga Sampéyan dhéwé diblokir',
'ipbnounblockself'                => 'Sampéyan ora dililakaké mbukak blokirané Sampéyan',

# Developer tools
'lockdb'              => 'Kunci basis data',
'unlockdb'            => 'Buka kunci basis data',
'lockdbtext'          => 'Ngunci basis data bakal menggak kabèh panganggo kanggo nyunting kaca, ngowahi préferènsi panganggo, nyunting daftar pangawasan, lan prekara-prekara liyané sing merlokaké owah-owahan basis data. Pastèkna yèn iki pancèn panjenengan gayuh, lan yèn panjenengan ora lali mbuka kunci basis data sawisé pangopènan rampung.',
'unlockdbtext'        => 'Mbuka kunci basis data bakal mbalèkaké kabèh panganggo bisa nyunting kaca manèh, ngowahi préferènsi panganggo, nyunting daftar pangawasan, lan prekara-prekara liyané sing merlokaké pangowahan marang basis data.
Tulung pastèkna yèn iki pancèn sing panjenengan gayuh.',
'lockconfirm'         => 'Iya, aku pancèn péngin ngunci basis data.',
'unlockconfirm'       => 'Iya, aku pancèn péngin tmbuka kunci basis data.',
'lockbtn'             => 'Kunci basis data',
'unlockbtn'           => 'Buka kunci basis data',
'locknoconfirm'       => 'Panjenengan ora mènèhi tandha cèk ing kothak konfirmasi.',
'lockdbsuccesssub'    => 'Bisa kasil ngunci basis data',
'unlockdbsuccesssub'  => 'Bisa kasil buka kunci basis data',
'lockdbsuccesstext'   => 'Basis data wis dikunci.
<br />Pastèkna panjenengan [[Special:UnlockDB|mbuka kunciné]] sawisé pangopènan bubar.',
'unlockdbsuccesstext' => 'Kunci basis data wis dibuka.',
'lockfilenotwritable' => 'Berkas kunci basis data ora bisa ditulis. Kanggo ngunci utawa mbuka basis data, berkas iki kudu ditulis déning server wèb.',
'databasenotlocked'   => 'Basis data ora dikunci.',
'lockedbyandtime'     => '(déning {{GENDER:$1|$1}} tanggal $2 wanci $3)',

# Move page
'move-page'                    => 'Pindhahna $1',
'move-page-legend'             => 'Mindhah kaca',
'movepagetext'                 => "Formulir ing ngisor iki bakal ngowahi jeneng sawijining kaca, mindhah kabèh sajarahé menyang kaca sing anyar. Irah-irahan utawa judhul sing lawas bakal dadi kaca pangalihan menyang irah-irahan sing anyar. Pranala menyang kaca sing lawas ora bakal diowahi; dadi pastèkna dhisik mriksa pangalihan [[Special:DoubleRedirects|dobel]] utawa [[Special:BrokenRedirects|pangalihan sing rusak]] sawisé pamindhahan. Panjenengan sing tanggung jawab mastèkaké menawa kabèh pranala-pranala tetep nyambung ing kaca panujon kaya samesthiné.

Gatèkna yèn kaca iki '''ora''' bakal dipindhah yèn wis ana kaca liyané sing nganggo irah-irahan sing anyar, kejaba kaca iku kosong utawa ora nduwé sajarah panyuntingan. Dadi tegesé panjenengan bisa ngowahi jeneng kaca iku manèh kaya sedyakala menawa panjenengan luput, lan panjenengan ora bisa nimpani kaca sing wis ana.

'''PÈNGET!'''
Perkara iki bisa ngakibataké owah-owahan sing drastis lan ora kaduga kanggo kaca-kaca sing populèr;
pastekaké dhisik panjenengan ngerti konsekwènsi saka panggayuh panjenengan sadurungé dibanjuraké.",
'movepagetalktext'             => "Kaca dhiskusi sing kagandhèng uga bakal dipindhahaké sacara otomatis '''kejaba yèn:'''

*Sawijining kaca dhiskusi sing ora kosong wis ana sangisoring irah-irahan (judhul) anyar, utawa
*Panjenengan ora maringi tandha cèk ing kothak ing ngisor iki.

Ing kasus-kasus iku, yèn panjenengan gayuh, panjenengan bisa mindhahaké utawa nggabung kaca iku sacara manual.",
'movearticle'                  => 'Pindhah kaca',
'moveuserpage-warning'         => "'''Pèngetan:''' Sampéyan arep mindhahaké kaca panganggo. Mangga cathet yèn namung kaca sing bakal dipindhahaké lan panganggo '''ora''' bakal diganti jenengé.",
'movenologin'                  => 'Durung mlebu log',
'movenologintext'              => 'Panjenengan kudu dadi panganggo sing wis ndaftar lan wis [[Special:UserLogin|mlebu log]] kanggo mindhah kaca.',
'movenotallowed'               => 'Panjenengan ora pareng ngalihaké kaca.',
'movenotallowedfile'           => 'Panjenengan ora duwé hak kanggo mindhahaké berkas.',
'cant-move-user-page'          => 'Panjenengan ora nduwèni hak aksès kanggo mindhahaké kaca panganggo (kapisah saka anak-kaca).',
'cant-move-to-user-page'       => 'Panjenengan ora nduwèni hak aksès kanggo mindhahaké kaca menyang sawijining kaca panganggoa (kajaba menyang anak-kaca panganggo).',
'newtitle'                     => 'Menyang irah-irahan utawa judhul anyar:',
'move-watch'                   => 'Awasna kaca iki',
'movepagebtn'                  => 'Pindhahna kaca',
'pagemovedsub'                 => 'Bisa kasil dipindhahaké',
'movepage-moved'               => '\'\'\'"$1" dipindhahaké menyang "$2".\'\'\'',
'movepage-moved-redirect'      => 'Kaca pengalihan wis kacipta.',
'movepage-moved-noredirect'    => 'Kanggo gawé pengalihan wis ditahan.',
'articleexists'                => 'Satunggalipun kaca kanthi asma punika sampun wonten, utawi asma ingkang panjenengan pendhet mboten leres. Sumangga nyobi asma sanèsipun.',
'cantmove-titleprotected'      => 'Panjenengan ora bisa mindhahaké kaca iki menyang lokasi iki, amerga irah-irahan tujuan lagi direksa; ora olèh digawé',
'talkexists'                   => 'Kaca iku kasil dipindhahaké, nanging kaca dhiskusi saka kaca iku ora bisa dipindhahaké amerga wis ana kaca dhiskusi ing irah-irahan (judhul) sing anyar. Mangga kaca-kaca dhiskusi wau digabung sacara manual.',
'movedto'                      => 'dipindhah menyang',
'movetalk'                     => 'Pindahna kaca dhiskusi sing ana gandhèngané.',
'move-subpages'                => 'Pindhahna anak-kaca (nganti $1)',
'move-talk-subpages'           => 'Pindhahna anak-kaca saka kaca wicara (nganti $1)',
'movepage-page-exists'         => 'Kaca $1 wis ana lan ora bisa ditindhes sacara otomatis.',
'movepage-page-moved'          => 'Kaca $1 wis dipindhah menyang $2.',
'movepage-page-unmoved'        => 'Kaca $1 ora bisa dialihaké menyang $2.',
'movepage-max-pages'           => 'Paling akèh $1 {{PLURAL:$1|kaca|kaca}} wis dialihaké lan ora ana manèh sing bakal dialihaké sacara otomatis.',
'movelogpage'                  => 'Log pamindhahan',
'movelogpagetext'              => 'Ing ngisor iki kapacak log pangalihan kaca.',
'movesubpage'                  => '{{PLURAL:$1|Anak-kaca|Anak-kaca}}',
'movesubpagetext'              => 'Kaca iki nduwèni $1 {{PLURAL:$1|anak-kaca|anak-kaca}} kaya kapacak ing ngisor.',
'movenosubpage'                => 'Kaca iki ora duwé anak-kaca.',
'movereason'                   => 'Alesan:',
'revertmove'                   => 'balèkaké',
'delete_and_move'              => 'busak lan kapindahaken',
'delete_and_move_text'         => '== Perlu mbusak ==

Artikel sing dituju, "[[:$1]]", wis ana isiné.
Apa panjenengan kersa mbusak iku supaya kacané bisa dialihaké?',
'delete_and_move_confirm'      => 'Ya, busak kaca iku.',
'delete_and_move_reason'       => 'Dibusak kanggo jaga-jaga ananing pamindhahan saka "[[$1]]"',
'selfmove'                     => 'Pangalihan kaca ora bisa dilakoni amerga irah-irahan utawa judhul sumber lan tujuané padha.',
'immobile-source-namespace'    => 'Ora bisa mindhahaké kaca jroning bilik jeneng "$1"',
'immobile-target-namespace'    => 'Ora bisa mindhahaké kaca menyang bilik jeneng "$1"',
'immobile-target-namespace-iw' => 'Pranala interwiki dudu target sing sah kanggo pamindhahan kaca.',
'immobile-source-page'         => 'Kaca iki ora bisa dipindhahaké.',
'immobile-target-page'         => 'Ora bisa mindhahaké menyang irah-irahan tujuan kasebut.',
'imagenocrossnamespace'        => 'Ora bisa mindhahaké gambar menyang bilik nama non-gambar',
'nonfile-cannot-move-to-file'  => 'Ora bisa mindhahaké non-berkas nèng bilik jeneng berkas',
'imagetypemismatch'            => 'Èkstènsi anyar berkas ora cocog karo jenisé',
'imageinvalidfilename'         => 'Jeneng berkas tujuan ora sah',
'fix-double-redirects'         => 'Dandani kabèh pangalihan gandha sing tumuju marang irah-irahan asli',
'move-leave-redirect'          => 'Gawé pangalihan menyang irah-irahan anyar',
'protectedpagemovewarning'     => "'''Pènget:''' Kaca iki wis dikunci dadi mung panganggo sing nduwé hak aksès pangurus baé sing bisa mindhahaké.
Cathetan entri pungkasan disadiakaké ing ngisor kanggo referensi:",
'semiprotectedpagemovewarning' => "'''Cathetan:''' Kaca iki wis direksa saéngga mung panganggo kadhaptar sing bisa mindhahaké.
Entri cathetan pungkasan disadiakake ing ngisor kanggo referensi:",
'move-over-sharedrepo'         => '== Berkas wis ana ==
[[:$1]] ana ing panyimpenan bebarengan. Mindhahaké berkas mawa judul iki bakal nibani berkas bebarengan.',
'file-exists-sharedrepo'       => 'Jeneng berkas kapilih wis ana kanggo nèng panyimpenan bebarengan.
Mangga pilih jeneng liya.',

# Export
'export'            => 'Ekspor kaca',
'exporttext'        => 'Panjenengan bisa ngèkspor tèks lan sajarah panyuntingan sawijining kaca tartamtu utawa sawijining sèt kaca awujud XML tartamtu. Banjur iki bisa diimpor ing wiki liyané nganggo MediaWiki nganggo fasilitas [[Special:Import|impor kaca]].

Kanggo ngèkspor kaca-kaca artikel, lebokna irah-irahan utawa judhul sajroning kothak tèks ing ngisor iki, irah-irahan utawa judhul siji per baris, lan pilihen apa panjenengan péngin ngèkspor jangkep karo vèrsi sadurungé, utawa namung vèrsi saiki mawa cathetan panyuntingan pungkasan.

Yèn panjenengan namun péngin ngimpor vèrsi pungkasan, panjenengan uga bisa nganggo pranala kusus, contoné [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] kanggo ngèkspor artikel "[[{{MediaWiki:Mainpage}}]]".',
'exportall'         => 'Ngèkspor kabèh kaca',
'exportcuronly'     => 'Namung èkspor révisi saiki, dudu kabèh vèrsi lawas',
'exportnohistory'   => "----
'''Cathetan:''' Ngèkspor kabèh sajarah suntingan kaca ngliwati formulir iki wis dinon-aktifaké déning alesan kinerja.",
'exportlistauthors' => 'Lebokaké daptar jangkep kontributor kanggo pendhak kaca',
'export-submit'     => 'Èkspor',
'export-addcattext' => 'Tambahna kaca saka kategori:',
'export-addcat'     => 'Tambahna',
'export-addnstext'  => 'Nambahaké kaca saka bilik jeneng:',
'export-addns'      => 'Tambah',
'export-download'   => 'Simpen minangka berkas',
'export-templates'  => 'Kalebu cithakan-cithakan',
'export-pagelinks'  => 'Katutna kaca kagandhèng nganti jeroné:',

# Namespace 8 related
'allmessages'                   => 'Kabèh laporan sistém',
'allmessagesname'               => 'Asma (jeneng)',
'allmessagesdefault'            => 'Tèks baku',
'allmessagescurrent'            => 'Tèks saiki',
'allmessagestext'               => 'Iki dhaptar kabèh pesen saka sistem sing ana ing bilik jeneng MediaWiki.
Mangga pirsani [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] lan [//translatewiki.net translatewiki.net] yèn panjenengan arep kontribusi ing lokalisasi generik MediaWiki.',
'allmessagesnotsupportedDB'     => "Kaca iki ora bisa dienggo amerga '''\$wgUseDatabaseMessages''' dipatèni.",
'allmessages-filter-legend'     => 'Penyaring',
'allmessages-filter'            => 'Saring nganggo kahanan kustomisasi:',
'allmessages-filter-unmodified' => 'Ora diowahi',
'allmessages-filter-all'        => 'Kabèh',
'allmessages-filter-modified'   => 'Diowahi',
'allmessages-prefix'            => 'Saring nganggo ater-ater:',
'allmessages-language'          => 'Basa:',
'allmessages-filter-submit'     => 'Tumuju menyang',

# Thumbnails
'thumbnail-more'           => 'Gedhèkna',
'filemissing'              => 'Berkas ora ditemokaké',
'thumbnail_error'          => "Kaluputan nalika nggawé gambar cilik (''thumbnail''): $1",
'djvu_page_error'          => "Kaca DjVu ana ing sajabaning ranggèhan (''range'')",
'djvu_no_xml'              => 'Ora bisa njupuk XML kanggo berkas DjVu',
'thumbnail-temp-create'    => 'Ora bisa nggawé berkas gambar mini sawetara',
'thumbnail-dest-create'    => 'Ora bisa nyimpen bambar mini nèng papan patujon',
'thumbnail_invalid_params' => "Paramèter gambar cilik (''thumbnail'') ora absah",
'thumbnail_dest_directory' => 'Ora bisa nggawé dirèktori tujuan',
'thumbnail_image-type'     => 'Tipe gambar ora didhukung',
'thumbnail_gd-library'     => 'Konfigurasi pustaka GD ora pepak: fungsi $1 ilang',
'thumbnail_image-missing'  => 'Berkas katonané ilang: $1',

# Special:Import
'import'                     => 'Impor kaca',
'importinterwiki'            => 'Impor transwiki',
'import-interwiki-text'      => 'Pilih sawijining wiki lan irah-irahan kaca sing arep diimpor.
Tanggal révisi lan jeneng panyunting bakal dilestarèkaké.
Kabèh aktivitas impor transwiki bakal dilog ing [[Special:Log/import|log impor]].',
'import-interwiki-source'    => 'Kaca/sumber wiki:',
'import-interwiki-history'   => 'Tuladen kabèh vèrsi lawas saka kaca iki',
'import-interwiki-templates' => 'Katutna kabèh cithakan',
'import-interwiki-submit'    => 'Impor',
'import-interwiki-namespace' => 'Bilik jeneng tujuan:',
'import-upload-filename'     => 'Jeneng berkas:',
'import-comment'             => 'Komentar:',
'importtext'                 => 'Mangga èkspor berkas saka wiki sumber nganggo [[Special:Export|prangkat èkspor]].
Simpen nèng komputer Sampéyan lan unggaha nèng kéné.',
'importstart'                => 'Ngimpor kaca...',
'import-revision-count'      => '$1 {{PLURAL:$1|révisi|révisi-révisi}}',
'importnopages'              => 'Ora ana kaca kanggo diimpor.',
'imported-log-entries'       => 'Ngimpor $1 {{PLURAL:$1|èntri log|èntri log}}.',
'importfailed'               => 'Impor gagal: $1',
'importunknownsource'        => 'Sumber impor ora ditepungi',
'importcantopen'             => 'Berkas impor ora bisa dibukak',
'importbadinterwiki'         => 'Pranala interwiki rusak',
'importnotext'               => 'Kosong utawa ora ana tèks',
'importsuccess'              => 'Impor suksès!',
'importhistoryconflict'      => 'Ana konflik révisi sajarah (mbok-menawa tau ngimpor kaca iki sadurungé)',
'importnosources'            => 'Ora ana sumber impor transwiki sing wis digawé lan pangunggahan sajarah sacara langsung wis dinon-aktifaké.',
'importnofile'               => 'Ora ana berkas sumber impor sing wis diunggahaké.',
'importuploaderrorsize'      => 'Pangunggahan berkas impor gagal. Ukuran berkas ngluwihi ukuran sing diidinaké.',
'importuploaderrorpartial'   => 'Pangunggahan berkas impor gagal. Namung sabagéyan berkas sing kasil bisa diunggahaké.',
'importuploaderrortemp'      => 'Pangunggahan berkas gagal. Sawijining dirèktori sauntara sing dibutuhaké ora ana.',
'import-parse-failure'       => 'Prosès impor XML gagal',
'import-noarticle'           => 'Ora ana kaca sing bisa diimpor!',
'import-nonewrevisions'      => 'Kabèh révisi sadurungé wis tau diimpor.',
'xml-error-string'           => '$1 ing baris $2, kolom $3 (bita $4): $5',
'import-upload'              => 'Ngunggahaké data XML',
'import-token-mismatch'      => 'Kélangan data sèsi. Mangga dijajal manèh.',
'import-invalid-interwiki'   => 'Ora bisa ngimport saka wiki sing kapilih.',
'import-error-edit'          => 'Kaca "$1" ora diimpor amarga Sampéyan ora dililakaké nyunting kuwi.',
'import-error-create'        => 'Kaca "$1" ora diimpor amarga Sampéyan ora dililakaké nggawé kuwi.',
'import-error-interwiki'     => 'Kaca "$1" ora diimpor amarga jenengé dicadhangaké kango pranala njaba (interwiki).',
'import-error-special'       => 'Kaca "$1" ora diimpor amarga kuwi kalebu nèng bilik jeneng kusus sing ora nglilakaké anané kaca.',
'import-error-invalid'       => 'Kaca "$1" ora diimpor amarga jenengé ora sah.',

# Import log
'importlogpage'                    => 'Log impor',
'importlogpagetext'                => 'Impor administratif kaca-kaca mawa sajarah panyuntingan saka wiki liya.',
'import-logentry-upload'           => 'ngimpor [[$1]] mawa pangunggahan berkas',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|révisi|révisi}}',
'import-logentry-interwiki'        => 'wis nge-transwiki $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|révisi}} saka $2',

# JavaScriptTest
'javascripttest'                           => 'Panjajalan JavaScript',
'javascripttest-disabled'                  => 'Fungsi iki durung diurubaké nèng wiki iki.',
'javascripttest-title'                     => 'Nglakokaké pangujian $1',
'javascripttest-pagetext-noframework'      => 'Kaca iki disadhiyakaké kanggo nglakokaké panjajalan JavaScript.',
'javascripttest-pagetext-unknownframework' => 'Rangka kerja panjajalan ora dingertèni "$1".',
'javascripttest-pagetext-frameworks'       => 'Mangga pilih sawiji saka rangka kerja panjajalan iki: $1',
'javascripttest-pagetext-skins'            => 'Pilih kulit kanggo nglakokaké panjajalan mawa:',
'javascripttest-qunit-intro'               => 'Delok [dhokumèntasi panjajalan $1] nèng mediawiki.org.',
'javascripttest-qunit-heading'             => 'Rangkéan panjajalan MediaWiki JavaScript QUnit',

# Tooltip help for the actions
'tooltip-pt-userpage'                 => 'Kaca panganggo panjenengan',
'tooltip-pt-anonuserpage'             => 'Kaca panganggo IP panjenengan',
'tooltip-pt-mytalk'                   => 'Kaca wicara panjenengan',
'tooltip-pt-anontalk'                 => 'Dhiskusi perkara suntingan saka alamat IP iki',
'tooltip-pt-preferences'              => 'Préferènsiku',
'tooltip-pt-watchlist'                => 'Daftar kaca sing tak-awasi.',
'tooltip-pt-mycontris'                => 'Daftar kontribusi panjenengan',
'tooltip-pt-login'                    => 'Panjenengan diaturi mlebu log, nanging ora dikudokaké.',
'tooltip-pt-anonlogin'                => 'Panjenengan disaranaké mlebu log, nanging ora diwajibaké.',
'tooltip-pt-logout'                   => 'Log metu (oncat)',
'tooltip-ca-talk'                     => 'Dhiskusi perkara isi',
'tooltip-ca-edit'                     => 'Sunting kaca iki. Nganggoa tombol pratayang sadurungé nyimpen.',
'tooltip-ca-addsection'               => 'Miwiti bagèyan anyar',
'tooltip-ca-viewsource'               => 'Kaca iki direksa. Panjenengan namung bisa mirsani sumberé.',
'tooltip-ca-history'                  => 'Vèrsi-vèrsi sadurungé saka kaca iki.',
'tooltip-ca-protect'                  => 'Reksa kaca iki',
'tooltip-ca-unprotect'                => 'Ganti panjagan kaca iki',
'tooltip-ca-delete'                   => 'Busak kaca iki',
'tooltip-ca-undelete'                 => 'Balèkna suntingan ing kaca iki sadurungé kaca iki dibusak',
'tooltip-ca-move'                     => 'Pindhahen kaca iki',
'tooltip-ca-watch'                    => 'Tambahna kaca iki ing daftar pangawasan panjenengan',
'tooltip-ca-unwatch'                  => 'Busak kaca iki saka daftar pangawasan panjenengan',
'tooltip-search'                      => 'Golek ing situs {{SITENAME}} iki',
'tooltip-search-go'                   => 'Lungaa ing kaca mawa jeneng persis iki, yèn anaa',
'tooltip-search-fulltext'             => 'Golèk kaca sing duwé tèks kaya mangkéné',
'tooltip-p-logo'                      => 'Kaca Utama',
'tooltip-n-mainpage'                  => 'Nuwèni Kaca Utama',
'tooltip-n-mainpage-description'      => 'Pirsani Kaca Utama',
'tooltip-n-portal'                    => 'Perkara proyèk, apa sing bisa panjenengan gayuh, lan ing ngendi golèk apa-apa',
'tooltip-n-currentevents'             => 'Temokna informasi perkara prastawa anyar',
'tooltip-n-recentchanges'             => 'Daftar owah-owahan anyar ing wiki.',
'tooltip-n-randompage'                => 'Tuduhna sembarang kaca',
'tooltip-n-help'                      => 'Papan kanggo golèk pitulung.',
'tooltip-t-whatlinkshere'             => 'Daftar kabèh kaca wiki sing nyambung menyang kaca iki',
'tooltip-t-recentchangeslinked'       => 'Owah-owahan pungkasan kaca-kaca sing duwé pranala menyang kaca iki',
'tooltip-feed-rss'                    => "''RSS feed'' kanggo kaca iki",
'tooltip-feed-atom'                   => "''Atom feed'' kanggo kaca iki",
'tooltip-t-contributions'             => 'Deleng daftar kontribusi panganggo iki',
'tooltip-t-emailuser'                 => 'Kirimna e-mail menyang panganggo iki',
'tooltip-t-upload'                    => 'Ngunggah gambar utawa berkas média',
'tooltip-t-specialpages'              => 'Daftar kabèh kaca astaméwa (kaca kusus)',
'tooltip-t-print'                     => 'Vèrsi cithak kaca iki',
'tooltip-t-permalink'                 => 'Pranala permanèn kanggo révisi kaca iki',
'tooltip-ca-nstab-main'               => 'Ndeleng kaca artikel',
'tooltip-ca-nstab-user'               => 'Deleng kaca panganggo',
'tooltip-ca-nstab-media'              => 'Ndeleng kaca média',
'tooltip-ca-nstab-special'            => 'Iki kaca astaméwa utawa kaca kusus sing ora bisa disunting',
'tooltip-ca-nstab-project'            => 'Deleng kaca proyèk',
'tooltip-ca-nstab-image'              => 'Deleng kaca berkas',
'tooltip-ca-nstab-mediawiki'          => 'Ndeleng pesenan sistém',
'tooltip-ca-nstab-template'           => 'Deleng cithakan',
'tooltip-ca-nstab-help'               => 'Mirsani kaca pitulung',
'tooltip-ca-nstab-category'           => 'Deleng kaca kategori',
'tooltip-minoredit'                   => 'Tandhanana minangka suntingan cilik',
'tooltip-save'                        => 'Simpen owah-owahan panjenengan',
'tooltip-preview'                     => 'Pratayang owah-owahan panjenengan, tulung nganggo fungsi iki sadurungé nyimpen!',
'tooltip-diff'                        => 'Tuduhna owah-owahan panjenengan ing tèks iki.',
'tooltip-compareselectedversions'     => 'Delengen prabédan antara rong vèrsi kaca iki sing dipilih.',
'tooltip-watch'                       => 'Tambahna kaca iki ing daftar pangawasan panjenengan',
'tooltip-watchlistedit-normal-submit' => 'Singkiraké judhul',
'tooltip-watchlistedit-raw-submit'    => 'Anyari daptar pangawasan',
'tooltip-recreate'                    => 'Gawéa kaca iki manèh senadyan tau dibusak',
'tooltip-upload'                      => 'Miwiti pangunggahan',
'tooltip-rollback'                    => 'Mbalèkaké suntingan-suntingan ing kaca iki menyang kontributor pungkasan nganggo sak klik.',
'tooltip-undo'                        => 'Mbalèkaké révisi iki lan mbukak kothak panyuntingan jroning mode pratayang. Wènèhi kasempatan kanggo ngisi alesan ing kothak ringkesan.',
'tooltip-preferences-save'            => 'Simpen préperensi',
'tooltip-summary'                     => 'Lebkaké ringkesan cedhèk',

# Metadata
'notacceptable' => 'Server wiki ora bisa nyedyakaké data sajroning format sing bisa diwaca déning klièn panjenengan.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Panganggo|panganggo}} anon ing {{SITENAME}}.',
'siteuser'         => 'Panganggo {{SITENAME}} $1',
'anonuser'         => 'Panganggo anonim {{SITENAME}} $1',
'lastmodifiedatby' => 'Kaca iki pungkasan diowahi  $2, $1 déning $3.',
'othercontribs'    => 'Adhedhasar karyané $1.',
'others'           => 'liya-liyané',
'siteusers'        => '{{PLURAL:$2|Panganggo|Panganggo-panganggo}} {{SITENAME}} $1',
'anonusers'        => '{{PLURAL:$2|Panganggo|Panganggo}} anonim {{SITENAME}} $1',
'creditspage'      => 'Informasi para panulis kaca',
'nocredits'        => 'Ora ana informasi ngenani para panulis ing kaca iki.',

# Spam protection
'spamprotectiontitle' => 'Filter anti-spam',
'spamprotectiontext'  => 'Kaca sing arep panjenengan simpen diblokir déning filter spam.
Mbokmanawa iki disebabaké anané pranala jaba sing klebu daftar ireng.',
'spamprotectionmatch' => 'Tèks sing kapacak iki mancing filter spam kita: $1',
'spambot_username'    => 'Resik-resik spam MediaWiki',
'spam_reverting'      => 'Mbalèkaké menyang vèrsi pungkasan sing ora ana pranalané menyang $1',
'spam_blanking'       => 'Kabèh révisi sing duwé pranala menyang $1, pangosongan',

# Info page
'pageinfo-title'            => 'Inpormasi kanggo "$1"',
'pageinfo-header-edits'     => 'Suntingan',
'pageinfo-header-watchlist' => 'Daptar pangawasan',
'pageinfo-header-views'     => 'Delokan',
'pageinfo-subjectpage'      => 'Kaca',
'pageinfo-talkpage'         => 'Kaca guneman',
'pageinfo-watchers'         => 'Cacahing pangawas',
'pageinfo-edits'            => 'Cacahing suntingan',
'pageinfo-authors'          => 'Cacahing beda-beda panganggit',
'pageinfo-views'            => 'Cacahing delokan',
'pageinfo-viewsperedit'     => 'Delokan per suntingan',

# Patrolling
'markaspatrolleddiff'                 => 'Tandhanana wis dipatroli',
'markaspatrolledtext'                 => 'Tandhanana artikel iki wis dipatroli',
'markedaspatrolled'                   => 'Ditandhani wis dipatroli',
'markedaspatrolledtext'               => 'Révisi sing dipilih ngenani [[:$1]] wis ditandhani minangka dipatroli.',
'rcpatroldisabled'                    => 'Patroli owah-owahan pungkasan dipatèni',
'rcpatroldisabledtext'                => 'Fitur patroli owah-owahan pungkasan lagi dipatèni.',
'markedaspatrollederror'              => 'Ora bisa awèh tandha wis dipatroli',
'markedaspatrollederrortext'          => 'Panjenengan kudu nentokaké sawijining révisi kanggo ditandhani minangka sing dipatroli.',
'markedaspatrollederror-noautopatrol' => 'Panjenengan ora pareng nandhani suntingan panjenengan dhéwé minangka dipatroli.',

# Patrol log
'patrol-log-page'      => 'Log patroli',
'patrol-log-header'    => 'Iki log revisi sing wis dipatroli.',
'log-show-hide-patrol' => '$1 log patroli',

# Image deletion
'deletedrevision'                 => 'Revisi lawas sing dibusak $1.',
'filedeleteerror-short'           => 'Kaluputan nalika mbusak berkas: $1',
'filedeleteerror-long'            => 'Ana kaluputan nalika mbusak berkas:

$1',
'filedelete-missing'              => 'Berkas "$1" ora bisa dibusak amerga ora ditemokaké.',
'filedelete-old-unregistered'     => 'Révisi berkas "$1" sing diwènèhaké ora ana sajroning basis data.',
'filedelete-current-unregistered' => 'Berkas sing dispésifikasi "$1" ora ana sajroning basis data.',
'filedelete-archive-read-only'    => 'Dirèktori arsip "$1" ora bisa ditulis déning server wèb.',

# Browsing diffs
'previousdiff' => '← Panyuntingan sadurungé',
'nextdiff'     => 'Panyuntingan sing luwih anyar →',

# Media information
'mediawarning'           => "'''Pèngetan''': Jinis berkas iki mungkin isiné kodhé mbebayani.
Yèn dilakokaké, sistem Sampéyan bisa kaserang.",
'imagemaxsize'           => "Wates ukuran gambar:<br />''(kanggo kaca dhèskripsi berkas)''",
'thumbsize'              => 'Ukuran gambar cilik (thumbnail):',
'widthheightpage'        => '$1 × $2, $3 {{PLURAL:$3|kaca|kaca}}',
'file-info'              => 'ukuran berkas: $1, tipe MIME: $2',
'file-info-size'         => '$1 × $2 piksel, ukuran berkas: $3, tipe MIME: $4',
'file-info-size-pages'   => '$1 × $2 piksel, gedhéné berkas: $3, jinisé MIME: $4, $5 {{PLURAL:$5|kaca|kaca}}',
'file-nohires'           => 'Ora ana résolusi sing luwih dhuwur.',
'svg-long-desc'          => 'Berkas SVG, nominal $1 × $2 piksel, gedhené berkas: $3',
'show-big-image'         => 'Résolusi kebak',
'show-big-image-preview' => 'Gedhéné pratayang iki: $1',
'show-big-image-other'   => '{{PLURAL:$2|Résolusi|Résolusi}} liya: $1.',
'show-big-image-size'    => '$1 × $2 piksel',
'file-info-gif-looped'   => 'mubeng',
'file-info-gif-frames'   => '$1 {{PLURAL:$1|rangka|rangka}}',
'file-info-png-looped'   => 'mubeng',
'file-info-png-repeat'   => 'diputer {{PLURAL:$1|ping|ping}} $1',
'file-info-png-frames'   => '$1 {{PLURAL:$1|rangka|rangka}}',

# Special:NewFiles
'newimages'             => 'Galeri berkas anyar',
'imagelisttext'         => "Ing ngisor iki kapacak daftar '''$1''' {{PLURAL:$1|berkas|berkas}} sing diurutaké $2.",
'newimages-summary'     => 'Kaca astaméwa utawa kusus iki nuduhaké daftar berkas anyar dhéwé sing diunggahaké.',
'newimages-legend'      => 'Filter',
'newimages-label'       => 'Jeneng berkas (utawa sapérangan seka jeneng berkas):',
'showhidebots'          => '($1 bot)',
'noimages'              => 'Ora ana sing dideleng.',
'ilsubmit'              => 'Golek',
'bydate'                => 'miturut tanggal',
'sp-newimages-showfrom' => 'Tuduhna gambar anyar wiwit saka $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 detik|$1 detik}}',
'minutes' => '{{PLURAL:$1|$1 menit|$1 menit}}',
'hours'   => '{{PLURAL:$1|$1 jam|$1 jam}}',
'days'    => '{{PLURAL:$1|$1 dina|$1 dina}}',
'ago'     => '$1 kapungkur',

# Bad image list
'bad_image_list' => "Formaté kaya mengkéné:

Namung butir daftar (baris sing diawali mawa tandha *) sing mèlu diitung. Pranala kapisan ing sawijining baris kudu pranala ing berkas sing ala.
Pranala-pranala sabanjuré ing baris sing padha dianggep minangka ''pengecualian'', yaiku artikel sing bisa nuduhaké berkas iku.",

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Berkas iki ngandhut informasi tambahan sing mbokmenawa ditambahaké déning kamera digital utawa ''scanner'' sing dipigunakaké kanggo nggawé utawa olèhé digitalisasi berkas. Yèn berkas iki wis dimodifikasi, detail sing ana mbokmenawa ora sacara kebak nuduhaké informasi saka gambar sing wis dimodifikasi iki.",
'metadata-expand'   => 'Tuduhna detail tambahan',
'metadata-collapse' => 'Delikna detail tambahan',
'metadata-fields'   => 'Entri lapangan-lapangan metadata sing kapacak iki bakal dituduhaké ing kaca informasi gambar yèn tabèl metadata didhelikaké. Entri liyané minangka baku bakal didhelikaké.
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
'exif-imagewidth'                  => 'Jembar',
'exif-imagelength'                 => 'Dhuwur',
'exif-bitspersample'               => 'Bit per komponèn',
'exif-compression'                 => 'Skéma komprèsi',
'exif-photometricinterpretation'   => 'Komposisi piksel',
'exif-orientation'                 => 'Orièntasi',
'exif-samplesperpixel'             => 'Cacah komponèn',
'exif-planarconfiguration'         => 'Pangaturan data',
'exif-ycbcrsubsampling'            => 'Rasio subsampling Y ke C',
'exif-ycbcrpositioning'            => 'Pandokokan Y lan C',
'exif-xresolution'                 => 'Résolusi horisontal',
'exif-yresolution'                 => 'Résolusi vèrtikal',
'exif-stripoffsets'                => 'Lokasi data gambar',
'exif-rowsperstrip'                => 'Cacah baris per strip',
'exif-stripbytecounts'             => 'Bita per strip komprèsi',
'exif-jpeginterchangeformat'       => 'Ofset menyang JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bita data JPEG',
'exif-whitepoint'                  => 'Kromatisitas titik putih',
'exif-primarychromaticities'       => 'Kromatisitas werna primer',
'exif-ycbcrcoefficients'           => 'Koèfisièn matriks transformasi papan werna',
'exif-referenceblackwhite'         => 'Wiji réferènsi pasangan ireng putih',
'exif-datetime'                    => 'Tanggal lan wektu pangowahan berkas',
'exif-imagedescription'            => 'Judhul gambar',
'exif-make'                        => 'Produsèn kamera',
'exif-model'                       => 'Modhèl kamera',
'exif-software'                    => 'Perangkat lunak',
'exif-artist'                      => 'Prodhusèn',
'exif-copyright'                   => 'Sing ndarbèni hak cipta',
'exif-exifversion'                 => 'Vèrsi Exif',
'exif-flashpixversion'             => 'Dukungan versi Flashpix',
'exif-colorspace'                  => 'Papan werna',
'exif-componentsconfiguration'     => 'Teges saben komponèn',
'exif-compressedbitsperpixel'      => 'Modhe komprèsi gambar',
'exif-pixelydimension'             => 'Jembaré gambar',
'exif-pixelxdimension'             => 'Dhuwuré gambar',
'exif-usercomment'                 => 'Komentar panganggo',
'exif-relatedsoundfile'            => 'Berkas audio sing kagandhèng',
'exif-datetimeoriginal'            => 'Tanggal lan wektu nggawé data',
'exif-datetimedigitized'           => 'Tanggal lan wektu dhigitalisasi',
'exif-subsectime'                  => 'Subdetik DateTime',
'exif-subsectimeoriginal'          => 'Subdetik DateTimeOriginal',
'exif-subsectimedigitized'         => 'Subdetik DateTimeDigitized',
'exif-exposuretime'                => 'Wektu pajanan',
'exif-exposuretime-format'         => '$1 detik ($2)',
'exif-fnumber'                     => 'Wiji F',
'exif-exposureprogram'             => 'Program pajanan',
'exif-spectralsensitivity'         => 'Sènsitivitas spèktral',
'exif-isospeedratings'             => 'Rating kacepetan ISO',
'exif-shutterspeedvalue'           => 'Cepeté rana APEX',
'exif-aperturevalue'               => 'Bukakan APEX',
'exif-brightnessvalue'             => 'Kapadhangan APEX',
'exif-exposurebiasvalue'           => 'Bias pajanan',
'exif-maxaperturevalue'            => 'Bukaan tanah maksimum',
'exif-subjectdistance'             => 'Jarak subjèk',
'exif-meteringmode'                => 'Modhe pangukuran',
'exif-lightsource'                 => 'Sumber cahya',
'exif-flash'                       => 'Kilas',
'exif-focallength'                 => 'Jarak fokus lènsa',
'exif-subjectarea'                 => 'Wilayah subjèk',
'exif-flashenergy'                 => 'Énèrgi kilas',
'exif-focalplanexresolution'       => 'Résolusi bidang fokus X',
'exif-focalplaneyresolution'       => 'Résolusi bidang fokus Y',
'exif-focalplaneresolutionunit'    => 'Unit résolusi bidang fokus',
'exif-subjectlocation'             => 'Lokasi subjèk',
'exif-exposureindex'               => 'Indhèks pajanan',
'exif-sensingmethod'               => 'Métodhe pangindran',
'exif-filesource'                  => 'Sumber berkas',
'exif-scenetype'                   => 'Tipe panyawangan',
'exif-customrendered'              => 'Prosès nggawé gambar',
'exif-exposuremode'                => 'Modhe pajanan',
'exif-whitebalance'                => 'Kaseimbangan putih',
'exif-digitalzoomratio'            => 'Rasio pambesaran digital',
'exif-focallengthin35mmfilm'       => 'Dhawa fokus ing fil 35 mm',
'exif-scenecapturetype'            => 'Tipe panangkepan',
'exif-gaincontrol'                 => 'Kontrol panyawangan',
'exif-contrast'                    => 'Kontras',
'exif-saturation'                  => 'Saturasi',
'exif-sharpness'                   => 'Kalandhepan',
'exif-devicesettingdescription'    => 'Dhèskripsi pangaturan piranti',
'exif-subjectdistancerange'        => 'Jarak subjèk',
'exif-imageuniqueid'               => 'ID unik gambar',
'exif-gpsversionid'                => 'Vèrsi tag GPS',
'exif-gpslatituderef'              => 'Lintang Lor utawa Kidul',
'exif-gpslatitude'                 => 'Lintang',
'exif-gpslongituderef'             => 'Bujur Wétan utawa Kulon',
'exif-gpslongitude'                => 'Bujur',
'exif-gpsaltituderef'              => 'Réferènsi dhuwur',
'exif-gpsaltitude'                 => 'Dhuwuré',
'exif-gpstimestamp'                => 'Wektu GPS (jam atom)',
'exif-gpssatellites'               => 'Satelit kanggo pangukuran',
'exif-gpsstatus'                   => 'Status panrima',
'exif-gpsmeasuremode'              => 'Modhe pangukuran',
'exif-gpsdop'                      => 'Katepatan pangukuran',
'exif-gpsspeedref'                 => 'Unit kacepetan',
'exif-gpsspeed'                    => 'Kacepetan panrima GPS',
'exif-gpstrackref'                 => 'Réferènsi arah obah',
'exif-gpstrack'                    => 'Arah obah',
'exif-gpsimgdirectionref'          => 'Réferènsi arah gambar',
'exif-gpsimgdirection'             => 'Arah gambar',
'exif-gpsmapdatum'                 => 'Data survéi géodèsi',
'exif-gpsdestlatituderef'          => 'Réferènsi lintang saka patujon',
'exif-gpsdestlatitude'             => 'Lintang tujuan',
'exif-gpsdestlongituderef'         => 'Réferènsi bujur saka patujon',
'exif-gpsdestlongitude'            => 'Bujur tujuan',
'exif-gpsdestbearingref'           => 'Réferènsi bearing of destination',
'exif-gpsdestbearing'              => 'Arah tujuan',
'exif-gpsdestdistanceref'          => 'Réferènsi jarak saka patujon',
'exif-gpsdestdistance'             => 'Jarak saka patujon',
'exif-gpsprocessingmethod'         => 'Jeneng métodhe prosès GPS',
'exif-gpsareainformation'          => 'Jeneng wilayah GPS',
'exif-gpsdatestamp'                => 'Tanggal GPS',
'exif-gpsdifferential'             => 'Korèksi diférènsial GPS',
'exif-jpegfilecomment'             => 'Tanggepan berkas JPEG',
'exif-keywords'                    => 'Tembung kunci',
'exif-worldregioncreated'          => 'Dhaèrahing donya ing endi gambar dijupuk',
'exif-countrycreated'              => 'Nagara ing endi gambar dijupuk',
'exif-countrycodecreated'          => 'Kodhe kanggo nagara ing endi gambar dijupuk',
'exif-provinceorstatecreated'      => 'Propinsi utawa nagara bagéyan ing endi gambar dujupuk',
'exif-citycreated'                 => 'Kutha ing endi gambar dijupuk',
'exif-sublocationcreated'          => 'Dhaérahing kutha ing endi gambar dijupuk',
'exif-worldregiondest'             => 'Wewengkon dunya katampilaké',
'exif-countrydest'                 => 'Nagara katampilaké',
'exif-countrycodedest'             => 'Kodhe nagara katampilaké',
'exif-provinceorstatedest'         => 'Propinsi utawa nagara bagéyan katampilaké',
'exif-citydest'                    => 'Kutha katampilaké',
'exif-sublocationdest'             => 'Dhaèrahé kutha katampilaké',
'exif-objectname'                  => 'Judhul cendhèk',
'exif-specialinstructions'         => 'Prèntah kusus',
'exif-headline'                    => 'Warta utama',
'exif-credit'                      => 'Krédit/Panyadhiya',
'exif-source'                      => 'Sumber',
'exif-editstatus'                  => 'Status kapanyuntingan gambar',
'exif-urgency'                     => 'Kawigatèn',
'exif-fixtureidentifier'           => 'Jeneng pikstur',
'exif-locationdest'                => 'Panggon digambaraké',
'exif-locationdestcode'            => 'Kodhe dhaérah kagambaraké',
'exif-objectcycle'                 => 'Wektu katujon mèdia kuwi',
'exif-contact'                     => 'Inpormasi kontak',
'exif-writer'                      => 'Panulis',
'exif-languagecode'                => 'Basa',
'exif-iimversion'                  => 'Vèrsi IIM',
'exif-iimcategory'                 => 'Katègori',
'exif-iimsupplementalcategory'     => 'Katègori tambahan',
'exif-datetimeexpires'             => 'Aja dianggo sakbaré',
'exif-datetimereleased'            => 'Dimetukaké ing',
'exif-originaltransmissionref'     => 'Kodhe panggon transmisi asli',
'exif-identifier'                  => 'Pangenal',
'exif-lens'                        => 'Lénsa sing dianggo',
'exif-serialnumber'                => 'Nomer seri kaméra',
'exif-cameraownername'             => 'Sing nduwé kaméra',
'exif-label'                       => 'Labèl',
'exif-datetimemetadata'            => 'Tanggal pungkasan metadata diowah',
'exif-nickname'                    => 'Jeneng ora resminé gambar',
'exif-rating'                      => 'Biji (saka 5)',
'exif-rightscertificate'           => 'Sertipikat pranata hak',
'exif-copyrighted'                 => 'Status hak cipta',
'exif-copyrightowner'              => 'Sing ndarbèni hak cipta',
'exif-usageterms'                  => 'Katemton panganggoan',
'exif-webstatement'                => 'Pranyatan hak cipta online',
'exif-originaldocumentid'          => 'ID unik dokumèn asli',
'exif-licenseurl'                  => 'URL kanggo lisènsi hak cipta',
'exif-morepermissionsurl'          => 'Inpormasi lisènsi alternatip',
'exif-attributionurl'              => 'Nalika nganggo manèh karya iki, mangga ubungaké nèng',
'exif-preferredattributionname'    => 'Nalika nganggo manèh karya iki, mangga awèhi krèdit',
'exif-pngfilecomment'              => 'Tanggepan berkas PNG',
'exif-disclaimer'                  => 'Pamaidonan',
'exif-contentwarning'              => 'Pèngetan kontèn',
'exif-giffilecomment'              => 'Tanggepan berkas GIF',
'exif-intellectualgenre'           => 'Jinis barang',
'exif-subjectnewscode'             => 'Aturan jejer',
'exif-scenecode'                   => 'Aturan adegan IPTC',
'exif-event'                       => 'Kadadéan digambaraké',
'exif-organisationinimage'         => 'Organisasi digambaraké',
'exif-personinimage'               => 'Uwong digambaraké',
'exif-originalimageheight'         => 'Dhuwuré gambar sakdurungé dikethok',
'exif-originalimagewidth'          => 'Jembaré gambar sakdurungé dikethok',

# EXIF attributes
'exif-compression-1' => 'Ora dikomprèsi',

'exif-copyrighted-true'  => 'Mawa hak cipta',
'exif-copyrighted-false' => 'Domain umum',

'exif-unknowndate' => 'Tanggal ora dingertèni',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Baliken sacara horisontal',
'exif-orientation-3' => 'Diputer 180°',
'exif-orientation-4' => 'Baliken sacara vèrtikal',
'exif-orientation-5' => 'Diputer 90° nglawan arah dom jam dan dibalik sacara vèrtikal',
'exif-orientation-6' => 'Puter 90° lawan arah dom jam',
'exif-orientation-7' => 'Diputer 90° miturut arah dom jam lan diwalik sacara vèrtikal',
'exif-orientation-8' => 'Puter 90° saarah dom jam',

'exif-planarconfiguration-1' => "format ''chunky'' (kumothak)",
'exif-planarconfiguration-2' => 'format planar',

'exif-colorspace-65535' => 'Ora dikalibrasi',

'exif-componentsconfiguration-0' => 'ora ana',

'exif-exposureprogram-0' => 'Ora didéfinisi',
'exif-exposureprogram-1' => 'Mawa tangan (manual)',
'exif-exposureprogram-2' => 'Program normal',
'exif-exposureprogram-3' => 'Prioritas diafragma',
'exif-exposureprogram-4' => 'Prioritas panutup',
'exif-exposureprogram-5' => "Program kréatif (condong menyang jroning bilik (''depth of field''))",
'exif-exposureprogram-6' => 'Program aksi (condhong marang kacepetan rana)',
'exif-exposureprogram-7' => "Modus potret (kanggo foto ''closeup'' mawa latar wuri ora fokus)",
'exif-exposureprogram-8' => "Modus pamandhangan (''landscape'') (kanggo foto pamandhangan mawa latar wuri fokus)",

'exif-subjectdistance-value' => '$1 mèter',

'exif-meteringmode-0'   => 'Ora dingertèni',
'exif-meteringmode-1'   => 'Rata-rata',
'exif-meteringmode-2'   => 'Rata-rataAbobot',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Pola utawa patron multi-sègmèn',
'exif-meteringmode-6'   => 'Parsial (sabagéyan)',
'exif-meteringmode-255' => 'Liya-liyané',

'exif-lightsource-0'   => 'Ora dingertèni',
'exif-lightsource-1'   => 'Cahya srengéngé',
'exif-lightsource-2'   => 'Cahya néon',
'exif-lightsource-3'   => 'Wolfram (cahya pijer)',
'exif-lightsource-4'   => 'Blitz',
'exif-lightsource-9'   => 'Hawa apik',
'exif-lightsource-10'  => 'Hawa apedhut',
'exif-lightsource-11'  => 'Bayangan',
'exif-lightsource-12'  => 'Fluorescent cahya pepadhang awan (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescent putih pepadhang awan (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescent putih éyup (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluorescent putih (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Cahya standar A',
'exif-lightsource-18'  => 'Cahya standar B',
'exif-lightsource-19'  => 'Cahya standar C',
'exif-lightsource-24'  => 'ISO studio tungsten',
'exif-lightsource-255' => 'Sumber cahya liya',

# Flash modes
'exif-flash-fired-0'    => 'Lampu kilat ora murub',
'exif-flash-fired-1'    => 'Lampu kilat murub',
'exif-flash-return-0'   => 'ora ana fungsi panditèksian strobo balik',
'exif-flash-return-2'   => 'lampu strobo balik ora kaditèksi',
'exif-flash-return-3'   => 'lampu strobo balik kaditèksi',
'exif-flash-mode-1'     => 'lampu kilat diperlokaké',
'exif-flash-mode-2'     => 'lampu kilat dipatèni',
'exif-flash-mode-3'     => 'modus otomatis',
'exif-flash-function-1' => "Ora ana fungsi lampu blitz (''flash'')",
'exif-flash-redeye-1'   => 'modus réduksi mata-abang',

'exif-focalplaneresolutionunit-2' => 'inci',

'exif-sensingmethod-1' => 'Ora didéfinisi',
'exif-sensingmethod-2' => 'Sènsor aréa werna sa-tugelan',
'exif-sensingmethod-3' => 'Sènsor aréa werna rong tugelan',
'exif-sensingmethod-4' => 'Sènsor aréa werna telung tugelan',
'exif-sensingmethod-5' => 'Sènsor aréa werna urut-urutan',
'exif-sensingmethod-7' => 'Sènsor trilinéar',
'exif-sensingmethod-8' => 'Sènsor linéar werna urut-urutan',

'exif-filesource-3' => 'Kaméra meneng digital',

'exif-scenetype-1' => 'Gambar foto langsung',

'exif-customrendered-0' => 'Prosès normal',
'exif-customrendered-1' => 'Prosès kustom',

'exif-exposuremode-0' => 'Pajanan (èkspos) otomatis',
'exif-exposuremode-1' => 'Pajanan (èkspos) manual',
'exif-exposuremode-2' => 'Brakèt otomatis',

'exif-whitebalance-0' => "Kababagan (''kasaimbangan'') putih otomatis",
'exif-whitebalance-1' => 'Kababagan (kasaimbangan) putih manual',

'exif-scenecapturetype-0' => 'Standar',
'exif-scenecapturetype-1' => "Dawa (''landscape'')",
'exif-scenecapturetype-2' => 'Potrèt',
'exif-scenecapturetype-3' => 'Pamandhangan wengi',

'exif-gaincontrol-0' => 'Ora ana',
'exif-gaincontrol-1' => 'Puncak-puncak ngisor munggah',
'exif-gaincontrol-2' => 'Puncak-puncak dhuwur munggah',
'exif-gaincontrol-3' => 'Puncak-puncak ngisor medhun',
'exif-gaincontrol-4' => 'Puncak-puncak dhuwur medhun',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Lembut',
'exif-contrast-2' => 'Atos',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturasi ngisor',
'exif-saturation-2' => 'Saturasi dhuwur',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Lembut',
'exif-sharpness-2' => 'Atos',

'exif-subjectdistancerange-0' => 'Ora dimangertèni',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Katon cedhak',
'exif-subjectdistancerange-3' => 'Katon adoh',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Lintang lor',
'exif-gpslatitude-s' => 'Lintang kidul',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Bujur wétan',
'exif-gpslongitude-w' => 'Bujur kulon',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|mèter|mèter}} ndhuwur segara',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|mèter|mèter}} ngisor segara',

'exif-gpsstatus-a' => 'Pangukuran lagi dilakoni',
'exif-gpsstatus-v' => 'Interoperabilitas pangukuran',

'exif-gpsmeasuremode-2' => 'Pangukuran 2-dimènsi',
'exif-gpsmeasuremode-3' => 'Pangukuran 3-dimènsi',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilométer per jam',
'exif-gpsspeed-m' => 'Mil per jam',
'exif-gpsspeed-n' => 'Knot',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilomèter',
'exif-gpsdestdistance-m' => 'Mil',
'exif-gpsdestdistance-n' => 'Mil segara',

'exif-gpsdop-excellent' => 'Apik banget ($1)',
'exif-gpsdop-good'      => 'Apik ($1)',
'exif-gpsdop-moderate'  => 'Sedhengan ($1)',
'exif-gpsdop-fair'      => 'Cukup ($1)',
'exif-gpsdop-poor'      => 'Èlèk ($1)',

'exif-objectcycle-a' => 'Èsuk thok',
'exif-objectcycle-p' => 'Mbengi thok',
'exif-objectcycle-b' => 'Èsuk lan mbengi',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Arah sejati',
'exif-gpsdirection-m' => 'Arah magnètis',

'exif-ycbcrpositioning-1' => 'Kapusat',

'exif-dc-contributor' => 'Kontributor',
'exif-dc-coverage'    => 'Cakepan latar utawa wektu média',
'exif-dc-date'        => 'Tanggal',
'exif-dc-publisher'   => 'Panyithak',
'exif-dc-relation'    => 'Média kakait',
'exif-dc-rights'      => 'Hak',
'exif-dc-source'      => 'Mèdia sumber',
'exif-dc-type'        => 'Jinisé média',

'exif-rating-rejected' => 'Ditolak',

'exif-isospeedratings-overflow' => 'Luwih saka 65535',

'exif-iimcategory-ace' => 'Seni, budhaya lan dolanan',
'exif-iimcategory-clj' => 'Kriminal lan ukum',
'exif-iimcategory-dis' => 'Musibah lan kacilakan',
'exif-iimcategory-fin' => 'Èkonomi lan bisnis',
'exif-iimcategory-edu' => 'Pandhidhikan',
'exif-iimcategory-evn' => 'Lingkungan',
'exif-iimcategory-hth' => 'Kasehatan',
'exif-iimcategory-hum' => 'Kasenengan manungsa',
'exif-iimcategory-lab' => 'Buruh',
'exif-iimcategory-lif' => 'Gaya urip lan peprèian',
'exif-iimcategory-pol' => 'Politik',
'exif-iimcategory-rel' => 'Agama lan kapitayan',
'exif-iimcategory-sci' => 'Èlmu lan tehnologi',
'exif-iimcategory-soi' => 'Bab masarakat',
'exif-iimcategory-spo' => 'Krida',
'exif-iimcategory-war' => 'Perang, cengkah, rusuh',
'exif-iimcategory-wea' => 'Mangsa',

'exif-urgency-normal' => 'Sedhengan ($1)',
'exif-urgency-low'    => 'Cendhèk ($1)',
'exif-urgency-high'   => 'Dhuwur ($1)',
'exif-urgency-other'  => 'Prioritas sing ditetepaké panganggo ($1)',

# External editor support
'edit-externally'      => 'Sunting berkas iki mawa aplikasi jaba',
'edit-externally-help' => '(Deleng [//www.mediawiki.org/wiki/Manual:External_editors instruksi pangaturan] kanggo informasi sabanjuré)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'kabèh',
'namespacesall' => 'kabèh',
'monthsall'     => 'kabèh',
'limitall'      => 'kabèh',

# E-mail address confirmation
'confirmemail'             => 'Konfirmasi alamat e-mail',
'confirmemail_noemail'     => 'Panjenengan ora maringi alamat e-mail sing absah ing [[Special:Preferences|préferènsi]] panjenengan.',
'confirmemail_text'        => '{{SITENAME}} ngwajibaké panjenengan ndhedhes utawa konfirmasi alamat e-mail panjenengan sadurungé bisa nganggo fitur-fitur e-mail.
Pencèten tombol ing ngisor iki kanggo ngirim sawijining kode konfirmasi arupa sawijining pranala;
Tuladen pranala iki ing panjlajah wèb panjenengan kanggo ndhedhes yèn alamat e-mail panjenengan pancèn bener.',
'confirmemail_pending'     => 'Sawijining kode konfirmasi wis dikirim menyang alamat e-mail panjenengan;
yèn panjenengan lagi waé nggawé akun utawa rékening panjenengan, mangga nunggu sawetara menit nganti layang iku tekan sadurungé nyuwun kode anyar manèh.',
'confirmemail_send'        => 'Kirim kode konfirmasi',
'confirmemail_sent'        => 'E-mail mawa kode konfirmasi wis dikirim.',
'confirmemail_oncreate'    => 'Sawijining kode pandhedhesan (konfirmasi) wis dikirim menyang alamat e-mail panjenengan.
Kode iki ora dibutuhaké kanggo log mlebu, nanging dibutuhaké sadurungé nganggo kabèh fitur sing nganggo e-mail ing wiki iki.',
'confirmemail_sendfailed'  => '{{SITENAME}} ora bisa ngirim layang e-mail konfirmaside.
Mangga dipriksa mbok-menawa ana aksara ilegal ing alamat e-mail panjenengan.

Pangirim mènèhi informasi: $1',
'confirmemail_invalid'     => 'Kode konfirmasi salah. Kode iku mbok-menawa wis kadaluwarsa.',
'confirmemail_needlogin'   => 'Panjenengan kudu ndhedhes (konfirmasi) $1 alamat layang e-mail panjenengan.',
'confirmemail_success'     => 'Alamat e-mail panjenengan wis dikonfirmasi.
Saiki panjenengan bisa log mlebu lan wiwit nganggo wiki.',
'confirmemail_loggedin'    => 'Alamat e-mail panjenengan wis dikonfirmasi.',
'confirmemail_error'       => 'Ana kaluputan nalika nyimpen konfirmasi panjenengan.',
'confirmemail_subject'     => 'Konfirmasi alamat e-mail {{SITENAME}}',
'confirmemail_body'        => 'Sawijining wong, mbokmenawa panjenengan dhéwé, saka alamat IP $1, wis ndaftaraké akun "$2" mawa alamat e-mail iki ing {{SITENAME}}. Bukaka pranala iki ing panjlajah wèb panjenengan.

$3

Yèn panjenengan *ora tau* ndaftar akun iki, tutna pranala ing ngisor iki kanggo mbatalaké konfirmasi alamat e-mail:

$5

Konfirmasi iki bakal kadaluwarsa ing $4.',
'confirmemail_invalidated' => 'Pandhedhesan (konfirmasi) alamat e-mail batal',
'invalidateemail'          => 'Batalna pandhedhesan (konfirmasi) e-mail',

# Scary transclusion
'scarytranscludedisabled' => '[Transklusi cithakan interwiki dipatèni]',
'scarytranscludefailed'   => '[Olèhé njupuk cithakan $1 gagal]',
'scarytranscludetoolong'  => '[URL-é kedawan]',

# Delete conflict
'deletedwhileediting'      => "'''Pènget''': Kaca iki wis kabusak sawisé panjenengan miwiti nyunting!",
'confirmrecreate'          => "Panganggo [[User:$1|$1]] ([[User talk:$1|Wicara]]) wis mbusak kaca iki nalika panjenengan miwiti panyuntingan mawa alesan:
: ''$2''
Mangga didhedhes (dikonfirmasi) menawa panjenengan kersa nggawé ulang kaca iki.",
'confirmrecreate-noreason' => 'Panganggo [[User:$1|$1]] ([[User talk:$1|wicara]]) mbusak kaca iki sakbaré Sampéyan lekas nyunting. Mangga pesthèkaké yèn Sampéyan pancen pingin tenan nggawé manèh kaca iki.',
'recreate'                 => 'Gawé ulang',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => "Busak ''cache'' kaca iki?",
'confirm-purge-bottom' => 'Ngresiki kaca bakal sekaligus mbusak singgahan lan nampilaké vèrsi kaca pungkasan.',

# action=watch/unwatch
'confirm-watch-button'   => 'Oké',
'confirm-watch-top'      => 'Tambahaké kaca iki nènh daptar pangawasan Sampéyan?',
'confirm-unwatch-button' => 'Oké',
'confirm-unwatch-top'    => 'Singkiraké kaca iki saka daptar pangawasan Sampéyan?',

# Multipage image navigation
'imgmultipageprev' => '&larr; kaca sadurungé',
'imgmultipagenext' => 'kaca sabanjuré →',
'imgmultigo'       => 'Golèk!',
'imgmultigoto'     => 'Lungaa menyang kaca $1',

# Table pager
'ascending_abbrev'         => 'unggah',
'descending_abbrev'        => 'mudhun',
'table_pager_next'         => 'Kaca sabanjuré',
'table_pager_prev'         => 'Kaca sadurungé',
'table_pager_first'        => 'Kaca kapisan',
'table_pager_last'         => 'Kaca pungkasan',
'table_pager_limit'        => 'Tuduhna $1 entri per kaca',
'table_pager_limit_label'  => 'Barang per kaca:',
'table_pager_limit_submit' => 'Golèk',
'table_pager_empty'        => 'Ora ditemokaké',

# Auto-summaries
'autosumm-blank'   => 'Ngothongaké kaca',
'autosumm-replace' => "←Ngganti kaca karo '$1'",
'autoredircomment' => '←Ngalihaké menyang [[$1]]',
'autosumm-new'     => "Gawé kaca sing isi '$1'",

# Live preview
'livepreview-loading' => 'Ngunggahaké…',
'livepreview-ready'   => 'Ngunggahaké… Rampung!',
'livepreview-failed'  => 'Pratayang langsung gagal! Coba karo pratayang normal.',
'livepreview-error'   => 'Gagal nyambung: $1 "$2"
Cobanen mawa pratayang normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Owah-owahan pungkasan sing luwih anyar tinimbang $1 {{PLURAL:$1|detik|detik}} mbokmanawa ora metu ing pratélan iki.',
'lag-warn-high'   => "Amarga gedhéné ''lag'' basis data server, owah-owahan pungkasan sing luwih anyar saka $1 {{PLURAL:$1|detik|detik}} mbokmanawa ora metu ing daftar iki.",

# Watchlist editor
'watchlistedit-numitems'       => 'Daftar pangawasan panjenengan ngandhut {{PLURAL:$1|1 irah-irahan|$1 irah-irahan}}, ora kalebu kaca-kaca dhiskusi.',
'watchlistedit-noitems'        => 'Daftar pangawasan panjenengan kosong.',
'watchlistedit-normal-title'   => 'Sunting daftar pangawasan',
'watchlistedit-normal-legend'  => 'Busak irah-irahan saka daftar pangawasan',
'watchlistedit-normal-explain' => 'Irah-irahan utawa judhul ing daftar pangawasan panjenengan kapacak ing ngisor iki.
Kanggo mbusak sawijining irah-irahan, kliken kothak ing pinggiré, lan banjur kliken "Busak judhul".
Panjenengan uga bisa [[Special:EditWatchlist/raw|nyunting daftar mentah]].',
'watchlistedit-normal-submit'  => 'Busak irah-irahan',
'watchlistedit-normal-done'    => 'Irah-irahan {{PLURAL:$1|siji|$1}} wis dibusak saka daftar pangawasan panjenengan:',
'watchlistedit-raw-title'      => 'Sunting daftar mentah',
'watchlistedit-raw-legend'     => 'Sunting daftar mentah',
'watchlistedit-raw-explain'    => 'Irah-irahan ing daftar pangawasan panjenengan kapacak ing ngisor iki, lan bisa diowahi mawa nambahaké utawa mbusak daftar; sairah-irahan saban barisé.
Yèn wis rampung, anyarana kaca daftar pangawasan iki.
Panjenengan uga bisa [[Special:EditWatchlist|nganggo éditor standar panjenengan]].',
'watchlistedit-raw-titles'     => 'Irah-irahan:',
'watchlistedit-raw-submit'     => 'Anyarana daftar pangawasan',
'watchlistedit-raw-done'       => 'Daftar pangawasan panjenengan wis dianyari.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 irah-irahan wis|$1 irah-irahan wis}} ditambahaké:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 irah-irahan wis|$1 irah-irahan wis}} diwetokaké:',

# Watchlist editing tools
'watchlisttools-view' => 'Tuduhna owah-owahan sing ana gandhèngané',
'watchlisttools-edit' => 'Tuduhna lan sunting daftar pangawasan',
'watchlisttools-raw'  => 'Sunting daftar pangawasan mentah',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|wicara]])',

# Core parser functions
'unknown_extension_tag' => 'Tag èkstènsi ora ditepungi "$1"',
'duplicate-defaultsort' => 'Pènget: Kunci pilih asal (\'\'Default sort key\'\') "$2" nggantèkaké kunci pilih asal sadurungé "$1".',

# Special:Version
'version'                       => 'Versi',
'version-extensions'            => 'Èkstènsi sing wis diinstalasi',
'version-specialpages'          => 'Kaca astaméwa (kaca kusus)',
'version-parserhooks'           => 'Canthèlan parser',
'version-variables'             => 'Variabel',
'version-antispam'              => 'Pambendhung spam',
'version-skins'                 => 'Kulit',
'version-other'                 => 'Liyané',
'version-mediahandlers'         => 'Pananganan média',
'version-hooks'                 => 'Canthèlan-canthèlan',
'version-extension-functions'   => 'Fungsi-fungsi èkstènsi',
'version-parser-extensiontags'  => 'Rambu èkstènsi parser',
'version-parser-function-hooks' => 'Canthèlan fungsi parser',
'version-hook-name'             => 'Jeneng canthèlan',
'version-hook-subscribedby'     => 'Dilanggani déning',
'version-version'               => '(Vèrsi $1)',
'version-license'               => 'Lisènsi',
'version-poweredby-credits'     => "Wiki iki disengkuyung déning '''[//www.mediawiki.org/ MediaWiki]''', hak cipta © 2001-$1 $2.",
'version-poweredby-others'      => '[{{SERVER}}{{SCRIPTPATH}}/KRÈDIT liyané]',
'version-software'              => "''Software'' wis diinstalasi",
'version-software-product'      => 'Prodhuk',
'version-software-version'      => 'Vèrsi',

# Special:FilePath
'filepath'         => 'Lokasi berkas',
'filepath-page'    => 'Berkas:',
'filepath-submit'  => 'Golèk',
'filepath-summary' => 'Kaca astaméwa utawa kusus iki nuduhaké jalur pepak sawijining berkas.
Gambar dituduhaké mawa résolusi kebak lan tipe liyané berkas bakal dibuka langsung mawa program kagandhèng.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Golèk berkas duplikat',
'fileduplicatesearch-summary'   => 'Golèk duplikat berkas adhedhasar biji hash-é.',
'fileduplicatesearch-legend'    => 'Golèk duplikat',
'fileduplicatesearch-filename'  => 'Jeneng berkas:',
'fileduplicatesearch-submit'    => 'Golèk',
'fileduplicatesearch-info'      => '$1 × $2 piksel<br />Ukuran berkas: $3<br />Tipe MIME: $4',
'fileduplicatesearch-result-1'  => 'Berkas "$1" ora duwé duplikat idèntik.',
'fileduplicatesearch-result-n'  => 'Berkas "$1" ora ndarbèni {{PLURAL:$2|1 duplikat idèntik|$2 duplikat idèntik}}.',
'fileduplicatesearch-noresults' => 'Ora ana berkas mawa jeneng "$1" ditemokaké.',

# Special:SpecialPages
'specialpages'                   => 'Kaca istiméwa',
'specialpages-note'              => '----
* Kaca astamiwa biasa.
* <span class="mw-specialpagerestricted">Kaca astamiwa kawatesan.</span>',
'specialpages-group-maintenance' => 'Lapuran pangopènan',
'specialpages-group-other'       => 'Kaca-kaca astaméwa liyané',
'specialpages-group-login'       => 'Mlebu log / nggawé akun',
'specialpages-group-changes'     => 'Owah-owahan pungkasan lan log',
'specialpages-group-media'       => 'Lapuran média lan pangunggahan',
'specialpages-group-users'       => 'Panganggo lan hak-haké',
'specialpages-group-highuse'     => 'Kaca-kaca sing akèh dienggo',
'specialpages-group-pages'       => 'Dhaptar kaca',
'specialpages-group-pagetools'   => 'Piranti-piranti kaca',
'specialpages-group-wiki'        => 'Data lan piranti wiki',
'specialpages-group-redirects'   => 'Ngalihaké kaca astamèwa',
'specialpages-group-spam'        => 'Piranit spam',

# Special:BlankPage
'blankpage'              => 'Kaca kosong',
'intentionallyblankpage' => 'Kaca iki disengajakaké kosong',

# External image whitelist
'external_image_whitelist' => ' #Umbarna larikan iki apa anané<pre>
#Pigunakaké fragmèn èksprèsi regular (mung bagéyan ing antara //) ing ngisor
#Fragmèn ini bakal dicocogaké karo URL saka gambar-gambar èksternal
#Fragmèn sing cocog bakal ditampilaké minangka gambar, yèn ora mung pranala menyang gambar waé sing ditampilaké
#Larikan sing diwiwiti nganggo # dianggep minangka komentar
#Iki ora mbédakaké aksara gedhé/cilik
#Dèlèhna kabèh fragmèn èksprèsi regular sadhuwuré larikan iki. Umbarna larikan iki apa anané</pre>',

# Special:Tags
'tags'                    => 'Tag pangowahan sing absah',
'tag-filter'              => 'Filter [[Special:Tags|Tag]]:',
'tag-filter-submit'       => 'Penyaring',
'tags-title'              => 'Tag',
'tags-intro'              => 'Kaca iki ndhaptar tag sing bisa ditandhani déning piranti alus tumrap sawijining suntingan lan maknané.',
'tags-tag'                => 'Jeneng tag',
'tags-display-header'     => 'Tampilan ing dhaptar owah-owahan',
'tags-description-header' => 'Dhèskripsi pepak saka makna',
'tags-hitcount-header'    => 'Owah-owahan mawa tag',
'tags-edit'               => 'sunting',
'tags-hitcount'           => '$1 {{PLURAL:$1|pangowahan|pangowahan}}',

# Special:ComparePages
'comparepages'                => 'Bandhingna kaca',
'compare-selector'            => 'Bandhingna révisi kaca',
'compare-page1'               => 'Kaca 1',
'compare-page2'               => 'Kaca 2',
'compare-rev1'                => 'Révisi 1',
'compare-rev2'                => 'Révisi 2',
'compare-submit'              => 'Bandingaké',
'compare-invalid-title'       => 'Judhul sing Sampéyan awèhaké ora sah.',
'compare-title-not-exists'    => 'Judhul sing Sampéyan jaluk ora ana.',
'compare-revision-not-exists' => 'Benahan sing Sampéyan jaluk ora ana.',

# Database error messages
'dberr-header'      => 'Wiki iki duwé masalah',
'dberr-problems'    => 'Nyuwun ngapura! Situs iki ngalami masalah tèknis.',
'dberr-again'       => 'Coba nunggu sawetara menit lan unggahna manèh.',
'dberr-info'        => '(Ora bisa nyambung menyang peladèn basis data: $1)',
'dberr-usegoogle'   => 'Panjenengan bisa nyoba nggolèki nganggo Google kanggo sauntara wektu.',
'dberr-outofdate'   => 'Perlu diweruhi yèn indhèks isi kita manawa wis kadaluwarsa.',
'dberr-cachederror' => 'Iki sawijining salinan kasimpen kaca sing dijaluk, lan manawa dudu sing paling anyar.',

# HTML forms
'htmlform-invalid-input'       => 'Ana masalah jroning sawetara input panjenengan',
'htmlform-select-badoption'    => 'Aji sing panjenengan lebokaké ora absah',
'htmlform-int-invalid'         => 'Aji sing panjenengan lebokaké dudu angka wutuh (integer).',
'htmlform-float-invalid'       => 'Sing panjenengan lebokaké dudu angka.',
'htmlform-int-toolow'          => 'Aji sing panjenengan lebokaké keciliken ing sangisoré aji minimum $1',
'htmlform-int-toohigh'         => 'Aji sing panjenengan lebokaké kegedhèn ngluwihi aji maksimum $1',
'htmlform-required'            => 'Nilé iki dibutuhaké',
'htmlform-submit'              => 'Kirim',
'htmlform-reset'               => 'Batalna pangowahan',
'htmlform-selectorother-other' => 'Liya',

# SQLite database support
'sqlite-has-fts' => '$1 mawa sengkuyungan golèkan tèks jangkep',
'sqlite-no-fts'  => '$1 tanpa sengkuyungan golèkan tèks jangkep',

# New logging system
'logentry-delete-delete'              => '$1 mbusak kaca $3',
'logentry-delete-restore'             => '$1 mbalèkaké kaca $3',
'logentry-delete-event'               => '$1 ngganti patampilan {{PLURAL:$5|sak kadadéan log|$5 kadadéan log}} nèng $3: $4',
'logentry-delete-revision'            => '$1 ngganti patampilan {{PLURAL:$5|sak pambenahan|$5 pambenahan}} nèng kaca $3: $4',
'logentry-delete-event-legacy'        => '$1 ngganti patampilan saka kadadéan log nèng $3',
'logentry-delete-revision-legacy'     => '$1 ngganti patampilan saka pambenahan nèng kaca $3',
'logentry-suppress-delete'            => '$1 neken kaca $3',
'logentry-suppress-event'             => '$1 ndhelik-ndhelik ngganti patampilan saka {{PLURAL:$5|sak kadadéan log|$5 kadadéan log}} nèng $3: $4',
'logentry-suppress-revision'          => '$1 ndhelik-ndhelik ngganti patampilan saka {{PLURAL:$5|sak pambenahan|$5 pambenahan}} nèng kaca $3: $4',
'logentry-suppress-event-legacy'      => '$1 ndhelik-ndhelik ngganti patampilan saka kadadéan log nèng $3',
'logentry-suppress-revision-legacy'   => '$1 ndhelik-ndhelik ngganti patampilan saka pambenahan nèng kaca $3',
'revdelete-content-hid'               => 'kontèn didhelikaké',
'revdelete-summary-hid'               => 'ringkesan suntingan didhelikaké',
'revdelete-uname-hid'                 => 'jeneng panganggo didhelikaké',
'revdelete-content-unhid'             => 'kontèn dituduhaké',
'revdelete-summary-unhid'             => 'ringkesan suntingan dituduhaké',
'revdelete-uname-unhid'               => 'jeneng panganggo dituduhaké',
'revdelete-restricted'                => 'rèstriksi ditrapaké marang para opsis',
'revdelete-unrestricted'              => 'rèstriksi marang para opsis dijabel',
'logentry-move-move'                  => '$1 mindhahaké kaca $3 nèng $4',
'logentry-move-move-noredirect'       => '$1 mindhahaké kaca $3 nèng $4 tanpa nginggalaké pangalihan',
'logentry-move-move_redir'            => '$1 mindhahaké kaca $3 nèng $4 ngliwati pangalihan',
'logentry-move-move_redir-noredirect' => '$1 mindhahaké kaca $3 nèng $4 ngliwati pangalihan tanpa nginggalaké pangalihan',
'logentry-patrol-patrol'              => '$1 nandhai benahan $4 saka kaca $3 kaawasi',
'logentry-patrol-patrol-auto'         => '$1 otomatis nandhai benahan $4 saka kaca $3 kaawasai',
'logentry-newusers-newusers'          => '$1 nggawé akun panganggo',
'logentry-newusers-create'            => '$1 nggawé akun panganggo',
'logentry-newusers-create2'           => '$1 nggawé akun panganggo $3',
'logentry-newusers-autocreate'        => 'Akun $1 digawé otomatis',
'newuserlog-byemail'                  => 'tembung sandhi wis dikirim liwat e-mail',

# Feedback
'feedback-bugornote' => 'Yèn Sampéyan siap njelasaké masalah tèhnis kanthi rinci mangga [$1 laporaké bug].
Utawa, Sampéyan bisa nganggo pormulir gampang ngisor. Tanggepan Sampéyan bakal ditambahaké nèng kaca "[$3 $2]", bebarengan karo jeneng panganggo Sampéyan lan pramban sing Sampéyan anggo.',
'feedback-subject'   => 'Jejer:',
'feedback-message'   => 'Layang:',
'feedback-cancel'    => 'Batal',
'feedback-submit'    => 'Kirim Lebon Saran',
'feedback-adding'    => 'Nambahaké lebon saran nèng kaca...',
'feedback-error1'    => 'Kasalahan: Asil ora dikenal saka API',
'feedback-error2'    => 'Kasalahan: Gagal nyunting',
'feedback-error3'    => 'Kasalahan: Ora ana tanggepan saka API',
'feedback-thanks'    => 'Nuwun! Lebon saran Sampéyan wis dipasang nèng kacané "[$2 $1]".',
'feedback-close'     => 'Rampung',
'feedback-bugcheck'  => 'Apik! Pesthèké kuwi dudu sawijining [$1 bug sing dingertèni].',
'feedback-bugnew'    => 'Aku wis mriksa. Kandakaké bug anyar',

# API errors
'api-error-badaccess-groups'              => 'Sampéyan ora dililakaké ngunggah berkas nèng wiki iki.',
'api-error-badtoken'                      => 'Kasalahan njero: Token èlèk.',
'api-error-copyuploaddisabled'            => 'Ngunggah saka URL dipatèni nèng sasana iki.',
'api-error-duplicate'                     => 'Ana {{PLURAL:$1|[$2 berkas liya]|[$2 pirang-pirang berkas liya]}} sing wis ana nèng situsé saha isiné padha.',
'api-error-duplicate-archive'             => 'Ana {{PLURAL:$1|[$2 berkas liya]|[$2 pirang-pirang berkas liya]}} sing wis ana nèng situsé saha isiné padha, nanging {{PLURAL:$1|kuwi|kuwi kabèh}} wis dibusak.',
'api-error-duplicate-archive-popup-title' => 'Gandhakaké {{PLURAL:$1berkas sing wis|berkas sing wis}} dibusak.',
'api-error-duplicate-popup-title'         => 'Gandhakaké {{PLURAL:$1berkas|berkas}}.',
'api-error-empty-file'                    => 'Berkas sing Sampéyan kirim kosong.',
'api-error-emptypage'                     => 'Nggawé kaca kosong anyar ora dilikaké.',
'api-error-fetchfileerror'                => 'Kasalahan njero: Ana sing salah nalika ngètukaké berkas iki.',
'api-error-file-too-large'                => 'Berkas sing Sampéyan kirim kagedhèn.',
'api-error-filename-tooshort'             => 'Jeneng berkas kacendhèken.',
'api-error-filetype-banned'               => 'Jinis berkas iki dilarang.',
'api-error-filetype-missing'              => 'Jeneng berkas ora nduwèni èkstènsi.',
'api-error-hookaborted'                   => 'Pangowahan sing Sampéyan coba dibatalaké déning èkstènsi.',
'api-error-http'                          => 'Kasalahan njero: Ora bisa ngubungi sasana.',
'api-error-illegal-filename'              => 'Jeneng berkas ora dililakaké.',
'api-error-internal-error'                => 'Kasalahan njero: Ana sing salah saka pamrosèsan unggahan Sampéyan nèng wiki.',
'api-error-invalid-file-key'              => 'Kasalahan njero: Berkas ora ditemokaké nèng panyimpenan sawetara.',
'api-error-missingparam'                  => 'Kasalahan njero: Paramètèr panjalukan ilang.',
'api-error-missingresult'                 => 'Kasalahan njero: Ora bisa mesthèkaké yèn nyaliné suksès.',
'api-error-mustbeloggedin'                => 'Sampéyan kudu mlebu log kanggo ngunggah berkas.',
'api-error-mustbeposted'                  => 'Kasalahan njero: Panjalukan mbutuhaké HTTP POST.',
'api-error-noimageinfo'                   => 'Ngunggah suksès. nanging sasana ora ngawèhi awak dhéwé katrangan bab berkas kuwi.',
'api-error-nomodule'                      => 'Kasalahan njero: Ora ana modul ngunggah sing dipatrapaké.',
'api-error-ok-but-empty'                  => 'Kasalahan njero: Ora ana tanggepan saka sasana.',
'api-error-overwrite'                     => 'Nibani berkas sing wis ana ora dililakaké.',
'api-error-stashfailed'                   => 'Kasalahan njero: Sasana gagal nyèlèhaké berkas sawetara.',
'api-error-timeout'                       => 'Sasana ora nanggepi nèng wektu sing karepaké.',
'api-error-unclassified'                  => 'Ana masalah sing ora dingertèni.',
'api-error-unknown-code'                  => 'Kasalahan ora dingertèni: "$1".',
'api-error-unknown-error'                 => 'Kasalahan njero: Ana sing salah nalika njajal ngunggah berkas Sampéyan.',
'api-error-unknown-warning'               => 'Pèngetan ora dingertèni: "$1".',
'api-error-unknownerror'                  => 'Kasalahan ora dingertèni: "$1".',
'api-error-uploaddisabled'                => 'Piranti ngunggah dipatèni nèng wiki iki.',
'api-error-verification-error'            => 'Berkas iki mungkin rusak, utawa nduwéni èkstènsi salah.',

);
