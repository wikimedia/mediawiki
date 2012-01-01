<?php
/** Basa Banyumasan (Basa Banyumasan)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Slamet Serayu (on map-bms.wikipedia.org)
 * @author StefanusRA
 * @author לערי ריינהארט
 */

$fallback = 'jv, id';

$messages = array(
# User preference toggles
'tog-underline'               => 'Garisen ngisoré pranala:',
'tog-highlightbroken'         => 'Format pranala tugel <a href="" class="new">kaya kiye</a> (pilihan: Kaya kiye<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Ratakna paragraf',
'tog-hideminor'               => 'Umpetna suntingan cilik nang owahan anyar',
'tog-hidepatrolled'           => 'Umpetna suntingan sing wis dipatroli nang owahan anyar',
'tog-newpageshidepatrolled'   => 'Umpetna kaca sing wis dipatroli sekang daftar kaca anyar',
'tog-extendwatchlist'         => 'Kembangna daftar pengawasan kanggo nidokna kabeh pangowahan, ora mung sing paling anyar thok',
'tog-usenewrc'                => 'Gunakna tampilan owahan anyar sing wis dikembangna (mbutuhna JavaScript)',
'tog-numberheadings'          => 'Aweh nomer judul secara otomatis',
'tog-showtoolbar'             => 'Tidhokna bilah alat penyuntingan',
'tog-editondblclick'          => 'Nyunting kaca nganggo dobel klik (mbutuhna JavaScript)',
'tog-editsection'             => 'Aktifna penyuntingan subbagian ngliwati pranala [sunting]',
'tog-editsectiononrightclick' => 'Aktifna penyuntingan subbagian nganggo klik-tengen nang judul bagian (mbutuhna JavaScript)',
'tog-showtoc'                 => 'Tidhokna daftar isine (kanggo kaca sing duwe lewih sekang 3 subbagian)',
'tog-rememberpassword'        => 'Emutna data login-ne inyong nang peramban kiye (kanggo paling suwe $1 {{PLURAL:$1|dina|dina}})',
'tog-watchcreations'          => 'Tambahna kaca gaweanne inyong nang daftar pangawasanne inyong',
'tog-watchdefault'            => 'Tambahna kaca sing tak-sunting maring daftar pangawasanne inyong',
'tog-watchmoves'              => 'Tambahna kaca sing tak-pindah maring daftar pangawasanne inyong',
'tog-watchdeletion'           => 'Tambahna kaca sing tak-busak maring daftar pangawasanne inyong',
'tog-minordefault'            => 'Otomatis nandani kabeh suntingan dadi suntingan cilik',
'tog-previewontop'            => 'Tidokna pratayang sedurunge kotak sunting',
'tog-previewonfirst'          => 'Tidokna pratayang nang suntingan sing pertama',
'tog-nocache'                 => 'Nonaktifna penyinggahan kaca peramban',
'tog-enotifwatchlistpages'    => 'Kirimna imel maring inyong angger kaca sing mlebu daftar pangawasanne inyong diowaih',
'tog-enotifusertalkpages'     => 'Kirimna imel maring inyong angger kaca dhiskusine inyong owah',
'tog-enotifminoredits'        => 'Kirimna imel maring inyong uga nek ana suntingan cilik',
'tog-enotifrevealaddr'        => 'Tidokna alamat imel-e inyong nang imel notifikasi',
'tog-shownumberswatching'     => 'Tidhokna jumlah pangawas',
'tog-oldsig'                  => 'Tapak asma sekiye:',
'tog-fancysig'                => 'Tapak asma dianggep dadi teks wiki (ora nganggo pranala otomatis)',
'tog-externaleditor'          => 'Gunakna editor eksternal secara gawan (kanggo sing ahli thok, perlu pengaturan mligi nang komputere rika. [//www.mediawiki.org/wiki/Manual:External_editors Informasi selengkape.])',
'tog-externaldiff'            => 'Gunakna diff eksternal secara gawan (kanggo sing ahli thok, perlu pengaturan mligi nang komputere rika. [//www.mediawiki.org/wiki/Manual:External_editors Informasi selengkape.])',
'tog-showjumplinks'           => 'Aktifna pranala pitulung "mlumpat maring"',
'tog-uselivepreview'          => 'Gunakna pratayang langsung (mbutuhna JavaScript) (egin jajalan)',
'tog-forceeditsummary'        => 'Emutna inyong anggere durung ngisi kotak ringkesan suntingan',
'tog-watchlisthideown'        => 'Umpetna suntingane inyong sekang daftar pangawasan',
'tog-watchlisthidebots'       => 'Umpetna suntingane bot sekang daftar pangawasan',
'tog-watchlisthideminor'      => 'Umpetna suntingan cilik sekang daftar pangawasan',
'tog-watchlisthideliu'        => 'Umpetna suntingane pangganggo sing mlebu log sekang daftar pangawasan',
'tog-watchlisthideanons'      => 'Umpetna suntingane panganggo anonim sekang daftar pangawasan',
'tog-watchlisthidepatrolled'  => 'Umpetna suntingan sing wis dipatroli sekang daftar pangawasan',
'tog-ccmeonemails'            => 'Kirimi inyong salinan imel sing tak-kirimna maring panganggo sejen',
'tog-diffonly'                => 'Aja tidokna isi kaca nang ngisor bedane suntingan',
'tog-showhiddencats'          => 'Tidokna kategori sing diumpetna',
'tog-norollbackdiff'          => 'Lirwakna perbedaan seuwise nglakokna pambalikan',

'underline-always'  => 'Saben',
'underline-never'   => 'Ora tau',
'underline-default' => 'Gawane peramban',

# Font style option in Special:Preferences
'editfont-style'     => 'Modhèl aksara (font) nang kotak suntingan:',
'editfont-default'   => 'Gawane peramban',
'editfont-monospace' => 'Aksara (font) Monospace',
'editfont-sansserif' => 'Aksara (font) Sans-serif',
'editfont-serif'     => 'Aksara (font) Serif',

# Dates
'sunday'        => 'Minggu',
'monday'        => 'Senen',
'tuesday'       => 'Selasa',
'wednesday'     => 'Rebo',
'thursday'      => 'Kemis',
'friday'        => 'Jemuwah',
'saturday'      => 'Setu',
'sun'           => 'Min',
'mon'           => 'Sen',
'tue'           => 'Sel',
'wed'           => 'Reb',
'thu'           => 'Kem',
'fri'           => 'Jem',
'sat'           => 'Set',
'january'       => 'Januari',
'february'      => 'Februari',
'march'         => 'Maret',
'april'         => 'April',
'may_long'      => 'Mei',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'Agustus',
'september'     => 'September',
'october'       => 'Oktober',
'november'      => 'November',
'december'      => 'Desember',
'january-gen'   => 'Januari',
'february-gen'  => 'Februari',
'march-gen'     => 'Maret',
'april-gen'     => 'April',
'may-gen'       => 'Mei',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'Agustus',
'september-gen' => 'September',
'october-gen'   => 'Oktober',
'november-gen'  => 'November',
'december-gen'  => 'Desember',
'jan'           => 'Jan',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Mei',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Agu',
'sep'           => 'Sep',
'oct'           => 'Okt',
'nov'           => 'Nov',
'dec'           => 'Des',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategori|Kategori}}',
'category_header'                => 'Kaca nang kategori "$1"',
'subcategories'                  => 'Subkategori',
'category-media-header'          => 'Media nang kategori "$1"',
'category-empty'                 => "''Kategori kiye sekiye ora nduwe artikel utawa média.''",
'hidden-categories'              => '{{PLURAL:$1|Kategori sing diumpetna|Kategori sing diumpetna}}',
'hidden-category-category'       => 'Kategori sing diumpetna',
'category-subcat-count'          => '{{PLURAL:$2|Kategori kiye mung nduwé subkategori kaya sing nang ngisor kiye.|Kategori kiye nduwe {{PLURAL:$1|subkategori|$1 subkategori}}, sekang total  $2.}}',
'category-subcat-count-limited'  => 'Kategori kiye duwe {{PLURAL:$1|subkategori|$1 subkategori}} yakuwe..',
'category-article-count'         => '{{PLURAL:$2|Kategori kiye mung nduweni kaca kiye.|Kategori kiye duwe {{PLURAL:$1|kaca|$1 kaca-kaca}} sekang gunggungé $2.}}',
'category-article-count-limited' => 'Kategori kiye duwe {{PLURAL:$1|siji kaca|$1 kaca-kaca}} nang ngisor kiye.',
'category-file-count'            => '{{PLURAL:$2|Kategori kiye mung nduweni kaca kiye.|Kategori kiye duwe {{PLURAL:$1|kaca|$1 kaca-kaca}}, sekang total $2.}}',
'category-file-count-limited'    => 'Kategori kiye duwe {{PLURAL:$1|siji berkas|$1 berkas-berkas}} nang ngisor kiye.',
'listingcontinuesabbrev'         => 'samb.',
'index-category'                 => 'Kaca sing diindhèks',
'noindex-category'               => 'Kaca sing ora diindhèks',
'broken-file-category'           => 'Kaca-kaca sing duwe pranala berkas bodhol',

'about'         => 'Bab',
'article'       => 'Isi tulisan',
'newwindow'     => '(buka nang jendhéla anyar)',
'cancel'        => 'Ora Sida',
'moredotdotdot' => 'Liyané...',
'mypage'        => 'Kaca inyong',
'mytalk'        => 'Catetan inyong',
'anontalk'      => 'Dhiskusi IP kiye',
'navigation'    => 'pandhu arah',
'and'           => '&#32;lan',

# Cologne Blue skin
'qbfind'         => 'Goleti',
'qbbrowse'       => 'Jelajahi',
'qbedit'         => 'Sunting',
'qbpageoptions'  => 'Kaca kiye',
'qbpageinfo'     => 'Konteks kaca',
'qbmyoptions'    => 'Kaca-ne inyong',
'qbspecialpages' => 'Kaca-kaca astamiwa',
'faq'            => 'FAQ (Pitakonan sing sering ditakokna)',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Nambah topik',
'vector-action-delete'           => 'Busek',
'vector-action-move'             => 'Pindah',
'vector-action-protect'          => 'Reksa',
'vector-action-undelete'         => 'Batalna pambusakan',
'vector-action-unprotect'        => 'Owahi pangreksan',
'vector-simplesearch-preference' => 'Aktifna saran panggoletan sing wis disempurnakna (nang kulit Vector thok)',
'vector-view-create'             => 'Gawe',
'vector-view-edit'               => 'Sunting',
'vector-view-history'            => 'Sajarah kaca',
'vector-view-view'               => 'Waca',
'vector-view-viewsource'         => 'Deleng sumbere',
'actions'                        => 'Tindakan',
'namespaces'                     => 'Bilik jeneng',
'variants'                       => 'Varian',

'errorpagetitle'    => 'Kasalahan',
'returnto'          => 'Bali maring $1.',
'tagline'           => 'Sekang {{SITENAME}}',
'help'              => 'Rewang',
'search'            => 'golet tulisan',
'searchbutton'      => 'Goleti',
'go'                => 'golet',
'searcharticle'     => 'Nuju maring',
'history'           => 'sejarah kaca',
'history_short'     => 'Sejarah kaca',
'updatedmarker'     => 'diowahi wiwit kunjungan pungkasane inyong',
'printableversion'  => 'Edisi Cetak',
'permalink'         => 'Pranala permanèn',
'print'             => 'Nyetak',
'view'              => 'Deleng',
'edit'              => 'Sunting',
'create'            => 'Gawe',
'editthispage'      => 'Sunting kaca kiye',
'create-this-page'  => 'Gawe kaca kiye',
'delete'            => 'Busek',
'deletethispage'    => 'Busak kaca kiye',
'undelete_short'    => 'Batalna pambusakan $1 {{PLURAL:$1|suntingan|suntingan}}',
'viewdeleted_short' => 'Deleng {{PLURAL:$1|siji suntingan|$1 suntingan}} sing wis dibusak',
'protect'           => 'Direksa',
'protect_change'    => 'owahi',
'protectthispage'   => 'Reksa kaca kiye',
'unprotect'         => 'Owahi pangreksan',
'unprotectthispage' => 'Owahi pangreksane kaca kiye',
'newpage'           => 'Kaca anyar',
'talkpage'          => 'Dhiskusikna kaca kiye',
'talkpagelinktext'  => 'Dopokan',
'specialpage'       => 'Kaca khusus',
'personaltools'     => 'Piranti pribadi',
'postcomment'       => 'Bagéyan anyar',
'articlepage'       => 'Deleng isi tulisan',
'talk'              => 'bahas',
'views'             => 'Tampilan',
'toolbox'           => 'perangkat',
'userpage'          => 'Ndeleng kaca panganggo',
'projectpage'       => 'Deleng kaca proyèk',
'imagepage'         => 'Deleng kaca berkas',
'mediawikipage'     => 'Ndeleng kaca pesen sistem',
'templatepage'      => 'Ndeleng kaca cithakan',
'viewhelppage'      => 'Ndeleng kaca pitulung',
'categorypage'      => 'Deleng kaca kategori',
'viewtalkpage'      => 'Ndeleng kaca dhiskusi',
'otherlanguages'    => 'basa liya',
'redirectedfrom'    => '(Dialihna sekang $1)',
'redirectpagesub'   => 'Kaca pangalihan',
'lastmodifiedat'    => 'Kaca kiye nembe diowahi dong jam $2, tanggal $1.',
'viewcount'         => 'Kaca kiye uwis diakses ping {{PLURAL:$1|sepisan|$1}}',
'protectedpage'     => 'Kaca sing direksa',
'jumpto'            => 'Mlumpat maring:',
'jumptonavigation'  => 'navigasi',
'jumptosearch'      => 'goleti',
'view-pool-error'   => 'Nyuwun ngapuro, peladèn lagi sibuk wektu sekiye.
Kakèhan panganggo sing njajal mbukak kaca kiye.
Entèni sedhéla sadurungé njajal ngaksès kaca kiye maning .

$1',
'pool-timeout'      => 'Kelangkung wekdal nengga kunci',
'pool-queuefull'    => 'Kumpulan antriane kebak',
'pool-errorunknown' => 'Kesalahan sing ora dingerteni sebabe',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Prakara {{SITENAME}}',
'aboutpage'            => 'Project:Prakara',
'copyright'            => 'Kabeh teks ana miturut $1.',
'copyrightpage'        => '{{ns:project}}:Hak cipta',
'currentevents'        => 'Kedaden Anyar',
'currentevents-url'    => 'Project:Prastawa sekiye',
'disclaimers'          => 'Pamaidonan',
'disclaimerpage'       => 'Project:Panyangkalan umum',
'edithelp'             => 'Pitulung panyuntingan',
'edithelppage'         => 'Help:Panyuntingan',
'helppage'             => 'Help:Isi',
'mainpage'             => 'Kaca Utama',
'mainpage-description' => 'Kaca Utama',
'policy-url'           => 'Project:Kabijakan',
'portal'               => 'Komunitas',
'portal-url'           => 'Project:Portal komunitas',
'privacy'              => 'Kebijakan privasi',
'privacypage'          => 'Project:Kabijakan privasi',

'badaccess'        => 'Kesalahan hak akses',
'badaccess-group0' => 'Rika ora olih nglakokna tindakan sing dejaluk Rika mau.',
'badaccess-groups' => 'Tindakan sing dejaluk Rika kuwe dibatesi mung nggo panganggo nang {{PLURAL:$2|klompok|salah siji klompok}}: $1.',

'versionrequired'     => 'Dibutuhna MediaWiki versi $1',
'versionrequiredtext' => 'MediaWiki versi $1 dibutuhna nggo nggunakna kaca kiye.
Deleng [[Special:Version|kaca versi]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Ditampa sekang "$1"',
'youhavenewmessages'      => 'Rika duwe $1 ($2).',
'newmessageslink'         => 'pesen anyar',
'newmessagesdifflink'     => 'owahan keri dhewek',
'youhavenewmessagesmulti' => 'Rika olih pesen-pesen anyar nang $1',
'editsection'             => 'sunting',
'editold'                 => 'sunting',
'viewsourceold'           => 'deleng sumbere',
'editlink'                => 'sunting',
'viewsourcelink'          => 'deleng sumbere',
'editsectionhint'         => 'Sunting bagian: $1',
'toc'                     => 'Isi',
'showtoc'                 => 'tidokna',
'hidetoc'                 => 'umpetna',
'collapsible-collapse'    => 'Umpetna',
'collapsible-expand'      => 'Tidokna',
'thisisdeleted'           => 'Deleng apa mbalekna $1?',
'viewdeleted'             => 'Ndeleng $1?',
'restorelink'             => '{{PLURAL:$1|siji suntingan|$1 suntingan}} sing wis dibusak',
'feedlinks'               => 'Umpan:',
'feed-invalid'            => 'Tipe penjalukan umpan ora bener.',
'feed-unavailable'        => "Umpan sindikasi (''syndication feeds'') ora disediakna",
'site-rss-feed'           => "$1 ''RSS Feed''",
'site-atom-feed'          => '$1 Atom feed',
'page-rss-feed'           => "\"\$1\" ''RSS Feed''",
'page-atom-feed'          => "\"\$1\" ''Atom Feed''",
'red-link-title'          => '$1 (kaca ora ana)',
'sort-descending'         => "Diurutna mudun (''descending'')",
'sort-ascending'          => "Diurutna munggah (''ascending'')",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Kaca',
'nstab-user'      => 'Panganggo',
'nstab-media'     => 'Media',
'nstab-special'   => 'Astamiwa',
'nstab-project'   => 'Proyek',
'nstab-image'     => 'Berkas',
'nstab-mediawiki' => 'Pesen',
'nstab-template'  => 'Cithakan',
'nstab-help'      => 'Pitulung',
'nstab-category'  => 'Kategori:',

# Main script and global functions
'nosuchaction'      => 'Ora ana tindakan kaya kuwe',
'nosuchactiontext'  => "Tindakan sing dijaluk URL kuwe ora sah.
Rika ndeyan salah ngetikna URL, utawa ngetutna pranala sing ora bener.
Kiye bisa uga ngindikasikna nek ana ''bug'' nang piranti alus sing digunakna nang {{SITENAME}}.",
'nosuchspecialpage' => 'Ora ana kaca astamiwa kaya kuwe',
'nospecialpagetext' => '<strong>Rika njaluk kaca astamiwa sing ora sah.</strong>

Daftar kaca astamiwa sing sah teyeng dideleng nang [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Kasalahan',
'databaseerror'        => 'Kasalahan basis data',
'laggedslavemode'      => "'''Pènget:''' Kaca kiye mbokmenawa isiné dudu pangowahan pungkasan.",
'readonly'             => 'Basis data dikunci',
'enterlockreason'      => 'Lebokna alesan panguncèn, kalebu uga prakiran kapan kunci bakal dibuka',
'readonlytext'         => 'Basis data lagi dikunci sekang inputan anyar lan modifikasi liyane, ndeyane lagi ana perawatan basis data, seuwise kuwe tuli bakal balik normal maning.

Administrator sing ngunci kiye aweh katerangan kaya kiye: $1',
'missing-article'      => 'Basis data ora teyeng nemokna teks paca sing kudune ana, yaiku "$1" $2.

Kiye biasanye jalaran pranala daluwarsa maring revisi sedurunge sekang kaca sing wis dibusak.

Angger dudu kuwe sebabe, Rika ndeyan nemokna "bug" nang software. 
Monggo dilaporna maring [[Special:ListUsers/sysop|administrator]], karo nyebutna URL sing dituju.

A',
'missingarticle-rev'   => '(révisi#: $1)',
'missingarticle-diff'  => '(Béda: $1, $2)',
'readonly_lag'         => 'Basis data uwis dikunci otomatis sawetara basis data sekunder lagi nglakokna sinkronisasi karo basis data utama',
'internalerror'        => 'Kasalahan internal',
'internalerror_info'   => 'Kasalahan internal: $1',
'fileappenderrorread'  => 'Ora teyeng maca "$1" dong lagi nambahi.',
'fileappenderror'      => 'Ora teyeng nambahna "$1" maring "$2".',
'filecopyerror'        => 'Ora teyeng nyalin berkas "$1" maring "$2".',
'filerenameerror'      => 'Ora teyeng ngowahi jeneng berkas sekang "$1" dadi "$2".',
'filedeleteerror'      => 'Ora teyeng mbusak berkas "$1".',
'directorycreateerror' => 'Ora teyeng nggawé dirèktori "$1".',
'filenotfound'         => 'Ora teyeng nemokna berkas "$1".',
'fileexistserror'      => 'Ora teyeng nulis maring berkas "$1": Berkase wis ana.',
'unexpected'           => 'Nilai-ne nang jaba jangkauan: "$1"="$2".',
'formerror'            => 'Kasalahan: Ora teyeng ngirimna formulir.',
'badarticleerror'      => 'Tindakan kiye ora teyeng dilakokna nang kaca kiye.',
'cannotdelete'         => 'Kaca utawa berkas "$1" ora teyeng dibusek.
Kiye ndeyane anu wis dibusek nang wong sejen.',
'cannotdelete-title'   => 'Ora teyeng mbusek kaca "$1".',
'badtitle'             => 'Judul ora sah',
'badtitletext'         => 'Judul kaca sing dijaluk ora sah, kosong, utawa salah nyambungna judul antar-basa utawa antarwiki.
Kiya ndeyane ana siji utawa lewih karakter sing ora teyeng digunakna nang judul.',
'perfcached'           => "Data kiye dijikot sekang singgahan (''cache'') lan ndeyane dudu data pungkasan.",
'perfcachedts'         => "Data kiye dijikot sekang singgahan (''cache''), lan dianyarna keri dhewek dong $1.",
'querypage-no-updates' => 'Update nggo kaca kiye lagi dipateni.
Data sing ana nang kene sekiye ora teyeng dibaleni unggah maning.',
'wrong_wfQuery_params' => 'Parameter salah maring wfQuery()<br />
Fungsi: $1<br />
Panyuwunan: $2',
'viewsource'           => 'Deleng sumbere',
'viewsource-title'     => 'Deleng sumbere nggo $1',
'actionthrottled'      => 'Tindakan diwatesi',
'actionthrottledtext'  => 'Kanggo ngukur anti-spam, Rika diwatesi gole nglakoni tikdakan kiye keseringen nang wektu sing cendhak, lan Rika uwis nglewati watese kuwe.
Monggo dijajal maning nang sawetara menit.',
'protectedpagetext'    => 'Kaca kiye uwis dikunci ben ora teyeng disunting.',
'viewsourcetext'       => 'Rika teyeng ndeleng lan nyalin sumbere kaca kiye:',
'viewyourtext'         => "Rika teyeng ndeleng lan nyalin sumbere '''suntingane Rika''' nang kaca kiye:",
'protectedinterface'   => 'Kaca kiye isine teks antarmuka ding dienggo piranti lunak, lan uwis dikunci nggo menghindari kasalahan.',
'namespaceprotected'   => "Rika ora duwe hak akses kanggo nyunting kaca nang bilik jeneng '''$1'''.",
'customcssprotected'   => 'Rika ora duwe izin nggo nyunting kaca CSS kiye, jalaran isine pengaturan pribadine panganggo sejen.',
'customjsprotected'    => 'Rika ora duwe izin nggo nyunting kaca JavaScript kiye, jalaran isine pengaturan pribadine panganggo sejen.',
'ns-specialprotected'  => 'Kaca astaiwa ora teyeng disunting.',
'titleprotected'       => 'Judul kiye wis direksa ora olih digawe nang [[User:$1|$1]].
Alesane yakuwe "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Kasalahan konfigurasi: pamindai virus ora dikenal: ''$1''",
'virus-scanfailed'     => 'Pemindaian gagal (kode $1)',
'virus-unknownscanner' => 'Antivirus ora ditepungi:',

# Login and logout pages
'logouttext'               => "'''Rika uwis metu log sekang sistem.'''

Rika teyeng terus nggunakna {{SITENAME}} kanthi anonim, utawa Rika teyeng [[Special:UserLogin|mlebu log maning]] nganggo jeneng panganggo sing padha utawa sejene.
Digatekna ya, nek ana kaca sing esih terus nidokna nek rika esih mlebu log nnganti Rika mbusak singgahan nang panjelajah web-e Rika.",
'welcomecreation'          => '== Sugeng rawuh, $1! ==

Akun Rika uwis digawe. Aja kelalen nata konfigurasi [[Special:Preferences|preferensi {{SITENAME}}]] Rika.',
'yourname'                 => 'Jeneng panganggo:',
'yourpassword'             => 'Tembung sandhi:',
'yourpasswordagain'        => 'Balèni tembung sandhi:',
'remembermypassword'       => 'Emutna data login-ne inyong nang peramban kiye (kanggo paling suwe $1 {{PLURAL:$1|dina|dina}})',
'securelogin-stick-https'  => 'Tetep kahubung maring HTTPS seuwise mlebu log',
'yourdomainname'           => 'Domain Rika:',
'login'                    => 'Melebu',
'nav-login-createaccount'  => 'Mlebu / gawe kaca anggota (akun)',
'loginprompt'              => "Rika kudu ngaktifna ''cookies'' ben teyeng mlebu log maring {{SITENAME}}.",
'userlogin'                => 'Melebu / gawe kaca anggota (account)',
'userloginnocreate'        => 'Mlebu log',
'logout'                   => 'Metu Log',
'userlogout'               => 'Metu Log',
'notloggedin'              => 'Durung mlebu log',
'nologin'                  => 'Durung duwe akun? $1.',
'nologinlink'              => 'Gawe akun anyar',
'createaccount'            => 'Gawe akun anyar',
'gotaccount'               => 'Wis duwe akun? $1.',
'gotaccountlink'           => 'Mlebu log',
'userlogin-resetlink'      => 'Apa Rika kelalen info detil nggo mlebune?',
'createaccountmail'        => 'Liwat imel',
'createaccountreason'      => 'Alesan:',
'badretype'                => 'Tembung sandhi sing Rika lebokna ora gathuk.',
'userexists'               => 'Jeneng panganggo sing dilebokna uwis ana sing nganggo.
Monggo pilih jeneng liyane.',
'loginerror'               => 'Kasalahan mlebu log',
'createaccounterror'       => 'Ora teyeng gawe akun:$1',
'nocookiesnew'             => "Akunpanganggo wis digawe, tapi Rika durung mlebu log.
{{SITENAME}} nggunakna ''cookies'' kanggo log panganggo.
''Cookies'' nang panjlajah web Rika dipateni.
Monggo diaktifna, lan jajal mlebu log maning nganggo jeneng panganggo lan tembung sandhine Rika.",
'nocookieslogin'           => "{{SITENAME}} nggunakna ''cookies'' nggo log panganggone.
''Cookies'' nang panjlajah web Rika dipateni.
Monggo diaktfna lan jajal maning.",
'nocookiesfornew'          => "Akun panganggo ora digawe, jalaran inyong ora teyeng mastikna sumbere.
Pastekna Rika uwis ngaktifna ''cookies'', trus baleni muat kaca kiye maning lan jajal sepisan maning.",
'noname'                   => 'Jeneng panganggo sing Rika lebokna ora sah.',
'loginsuccesstitle'        => 'Sukses mlebu log',
'loginsuccess'             => "'''Rika sekiye mlebu log nang {{SITENAME}} nganggo jeneng \"\$1\".'''",
'nosuchuser'               => 'Ora ana panganggo sing jenenge "$1".
Jeneng panganggo kuwe mbedakna kapitalisasi.
Priksa maning ejaane Rika, utawa [[Special:UserLogin/signup|gawe akun anyar]]',
'nosuchusershort'          => 'Ora ana panganggo sing jenenge "$1".
Jajal dipriksa maning ejaane Rika.',
'nouserspecified'          => 'Rika kudu nglebokna jeneng panganggo.',
'login-userblocked'        => 'Panganggo kiye diblok. Ora olih mlebu log.',
'wrongpassword'            => 'Tembung sandhi sing dilebokna salah.
Monggo dijajal sepisan maning.',
'wrongpasswordempty'       => 'Rika ora nglebokna tembung sandhi.
Monggo dijajal sepisan maning.',
'passwordtooshort'         => 'Tembung sandhi kuwe paling ora cacahe {{PLURAL:$1|1 karakter|$1 karakter}}.',
'password-name-match'      => 'Tembung sandhi Rika kudu sejen karo jeneng panganggone Rika.',
'password-login-forbidden' => 'Jeneng panganggo lan tembung sandhi kiye ora olih dienggo.',
'mailmypassword'           => 'Imelna tembung sandhi anyar',
'passwordremindertitle'    => 'Tembung sandi anyar temporer kanggo {{SITENAME}}',

# Change password dialog
'resetpass'                 => 'Ganti tembung sandhi',
'resetpass_announce'        => 'Rika wis mlebu log karo kode sementara sing dikirim maring imel.
Nggo nerusna, Rika kudu nglebokna tembung sandhi anyar nang kene:',
'resetpass_header'          => 'Ganti tembung sandhine akun',
'oldpassword'               => 'Tembung sandi lawas:',
'newpassword'               => 'Tembung sandi anyar:',
'retypenew'                 => 'Ketik maning tembung sandhi:',
'resetpass_submit'          => 'Nata tembung sandhi lan mlebu log',
'resetpass_success'         => 'Tembung sandhi Rika wis sukses diowahi!
Sekiye mroses Rika mlebu log...',
'resetpass_forbidden'       => 'Tembung sandhi ora teyeng diganti',
'resetpass-no-info'         => 'Rika kudu mlebu log kanggo ngakses kaca kiye sacara langsung.',
'resetpass-submit-loggedin' => 'Ganti tembung sandhi',
'resetpass-submit-cancel'   => 'Batal',
'resetpass-wrong-oldpass'   => 'Tembung sandhi ora sah.
Rika ndeyan  uwis kasil ngganti tembung sandhine Rika utawa wis njaluk tembung sandhi sauntara sing anyar.',
'resetpass-temp-password'   => 'Tembung sandhi sauntara:',

# Special:PasswordReset
'passwordreset'                    => "Tembung sandhi di-''reset''",
'passwordreset-text'               => 'Lengkapi formulir kiye ben nampa imel ngelingna detil akune Rika.',
'passwordreset-legend'             => "Tembung sandhi di-''reset''",
'passwordreset-disabled'           => "''Reset'' tembung sandhi wis dipateni nang wiki kiye.",
'passwordreset-pretext'            => '{{PLURAL:$1||Lebokna salah siji data nang ngisor kiye}}',
'passwordreset-username'           => 'Jeneng panganggo:',
'passwordreset-domain'             => 'Domain:',
'passwordreset-capture'            => 'Deleng imel hasile?',
'passwordreset-capture-help'       => 'Angger Rika nyonteng kotak kiye, imel (sing isi tembung sandhi sauntara) bakal ditidokna maring Rika barengan karo dikirimna maring panganggo.',
'passwordreset-email'              => 'Alamat imel:',
'passwordreset-emailtitle'         => 'Detil akun nang {{SITENAME}}',
'passwordreset-emailelement'       => 'Jeneng panganggo: $1
Tembung sandhi sauntara: $2',
'passwordreset-emailsent'          => 'Imel nggo ngelingna uwis dikirim.',
'passwordreset-emailsent-capture'  => 'Imel kanggo ngelingna uwis dikirim, kaya sing ditidokna nang ngisor kiye.',
'passwordreset-emailerror-capture' => 'Imel nggo ngelingna uwis digawe, kaya sing ditidokna nang ngisor kiye, ningen ora teyeng dikirim maring panganggo: $1',

# Special:ChangeEmail
'changeemail'          => 'Ganti alamat imel',
'changeemail-header'   => 'Ganti alamat imel-e akun',
'changeemail-text'     => 'Rampungna formulir kiye kanggo ngganti alamat imel Rika. Rika bakal perlu nglebokna tembung sandhi Rika nggo konfirmasi owahan kiye.',
'changeemail-no-info'  => 'Rika kudu mlebu log kanggo ngakses kaca kiye sacara langsung.',
'changeemail-oldemail' => 'Alamat imel sekiye:',
'changeemail-newemail' => 'Alamat imel anyar:',
'changeemail-none'     => '(ora ana)',
'changeemail-submit'   => 'Ganti imel',
'changeemail-cancel'   => 'Ora sida',

# Edit page toolbar
'bold_sample'     => 'Tèks kiye bakal dicithak kandel',
'bold_tip'        => 'Cithak kandel',
'italic_sample'   => 'Teks kiye bakal dicithak miring',
'italic_tip'      => 'Teks kiye bakal dicithak miring',
'link_sample'     => 'Judhul pranala',
'link_tip'        => 'Pranala internal',
'extlink_sample'  => 'http://www.example.com judhul pranala',
'extlink_tip'     => 'Pranala njaba (aja kelalen wiwitan http:// )',
'headline_sample' => 'Tèks judhul',
'headline_tip'    => 'Subbagian tingkat 1',
'nowiki_sample'   => 'Lebokna teks sing ora bakal diformat nang kene',
'nowiki_tip'      => 'Aja nganggo format wiki',
'image_tip'       => 'Ngaweh berkas',
'media_tip'       => 'Pranala berkas media',
'sig_tip'         => 'Tapak astane Rika nganggo tandha wektu',
'hr_tip'          => 'Garis horisontal',

# Edit pages
'summary'                        => 'Ringkesan:',
'subject'                        => 'Subyek/judhul:',
'minoredit'                      => 'Kiye suntingan cilik',
'watchthis'                      => 'Awasana kaca kiye',
'savearticle'                    => 'Simpen',
'preview'                        => 'Pra tayang',
'showpreview'                    => 'Pra tayang',
'showlivepreview'                => 'Pratayang langsung',
'showdiff'                       => 'Deleng beda',
'anoneditwarning'                => 'Rika ora kadaftar mlebu.
Alamat IP-ne Rika bakal dicatet nang sajarah panyuntingane kaca kiye.',
'anonpreviewwarning'             => "''Rika durung mlebu log. Nyimpen kaca bakal nyatetna alamat IP-ne Rika nang riwayat suntingan kaca kiye.''",
'missingsummary'                 => "'''Pènget:''' Rika ora nglebokna ringkesan panyuntingan. 
Angger Rika mencèt tombol Simpen maning, suntingane Rika bakal kasimpen tanpa ringkesan panyuntingan.",
'missingcommenttext'             => 'Tulung lebokna komentar nang ngisor kiye.',
'missingcommentheader'           => "'''Pènget:''' Rika ora nglebokna subjek/judul nggo komentare Rika kiye. 
Angger Rika mencèt \"{{int:savearticle}}\" maning, suntingane Rika bakal kasimpen tanpa komentar kuwe.",
'summary-preview'                => 'Pratayang ringkesan:',
'subject-preview'                => 'Pratayang subyèk/judul:',
'blockedtitle'                   => 'Panganggo diblokir',
'blockednoreason'                => 'ora ana alesan sing diwènèhna',
'whitelistedittext'              => 'Rika kudu $1 ben teyeng nyunting artikel.',
'confirmedittext'                => 'Rika kudu konfirmasi alamat imel-e Rika sedurunge nyunting kaca.
Monggo lebokna lan validasi alamat imel-eRika liwat [[Special:Preferences|kaca preferensine]] Rika.',
'nosuchsectiontitle'             => 'Bagéan ora ditemokna',
'nosuchsectiontext'              => 'Rika njajal nyunting subbagéan sing ora ana.
Kiye ndeyan anu uwis dipindah utawa dibusek dong Rika lagi ndeleng kaca kiye.',
'loginreqtitle'                  => 'Kudu mlebu log disit',
'loginreqlink'                   => 'mlebu log',
'loginreqpagetext'               => 'Rika kudu $1 ben teyeng ndeleng kaca liyane.',
'accmailtitle'                   => 'Tembung sandhi wis dikirim.',
'accmailtext'                    => "Tembung sandhi acak kanggo [[User talk:$1|$1]] wis digawe lan dikirim maring $2.

Tembung sandhi kanggo akun anyarkiye teyeng diganti nang kaca ''[[Special:ChangePassword|ganti tembung sandhi]]'' seuwise mlebu log.",
'newarticle'                     => '(Anyar)',
'noarticletext'                  => 'Sekiye ora ana teks nang kaca kiye.
Rika teyeng [[Special:Search/{{PAGENAME}}|nggoleti judul kaca kiye]] nang kaca-kaca liyane,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} goleti log sing ana gandengane],
utawa [{{fullurl:{{FULLPAGENAME}}|action=edit}} nyunting kaca kiye]</span>.',
'noarticletext-nopermission'     => 'Sekiye ora ana teks nang kaca kiye.
Rika teyeng [[Special:Search/{{PAGENAME}}|nggoleti judul kaca kiye]] nang kaca-kaca liyane,
utawa <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} goleti log sing ana gandengane]</span>.',
'userpage-userdoesnotexist'      => "Akun panganggo ''<nowiki>$1</nowiki>'' ora kedaftar.
Monggo dipriksa angger Rika kepengin gawe/nyunting kaca kiye.",
'userpage-userdoesnotexist-view' => 'Panganggo "$1" ora kedaftar.',
'blocked-notice-logextract'      => 'Pangganggo kiye sekiye lagi diblokir.
Log pamblokiran pungkasan ditidokna nang ngisor kiye kanggo bahan rujukan:',
'previewnote'                    => "'''Eling ya kiye tembe pratayang thok.'''
Owahane Rika durung disimpen!",
'editing'                        => 'Nyunting $1',
'template-protected'             => '(direksa)',

# "Undo" feature
'undo-success' => 'Suntingan kiye teyeng dibatalna.
Monggo priksa perbandingan nang ngisor kiye ngo mastekna nek kiye pancen sing Rika arep lakoni, lan banjur simpen pangowahan kuwe nggo ngrampungna pambatalan suntingan.',
'undo-failure' => 'Suntingan kiye ora teyeng dibatalna jalaran ana konflik panyuntingan antara.',
'undo-norev'   => 'Suntingan kiye ora teyeng dibatalna jalaran wis ora ana utawa anu wis dibusek.',

# History pages
'viewpagelogs'           => 'Deleng log-e kaca kiye',
'currentrev-asof'        => 'Revisi paling anyar nang tanggal $1',
'revisionasof'           => 'Revisi per $1',
'revision-info'          => 'Revisi per $1; $2',
'previousrevision'       => 'Revisi sedurunge',
'nextrevision'           => 'Revisi lewih anyar',
'currentrevisionlink'    => 'Revisi sekiye',
'cur'                    => 'sekiye',
'last'                   => 'sedurunge',
'history-fieldset-title' => 'Njlajah sajarah vèrsi sadhurungé',
'history-show-deleted'   => 'Sing dibusak thok',

# Revision deletion
'rev-delundel'           => 'tidokna/umpetna',
'revdel-restore'         => 'Ngowahi visiblitas (pangatonan)',
'revdel-restore-deleted' => 'suntingan sing wis dibusak',
'revdel-restore-visible' => 'tampilan revisi',

# Merge log
'revertmerge' => 'Batalna panggabungan',

# Diffs
'history-title' => 'Sajarah revisi sekang "$1"',
'lineno'        => 'Baris $1:',
'editundo'      => 'batalna',

# Search results
'searchresults'                    => 'Hasile penggoletan',
'searchresults-title'              => 'Hasile penggoletan sekang "$1"',
'prevn'                            => '{{PLURAL:$1|$1}} sadurungé',
'nextn'                            => '{{PLURAL:$1|$1}} terusane',
'prevn-title'                      => '$1 {{PLURAL:$1|asil|asil}} sadurungé',
'nextn-title'                      => '$1 {{PLURAL:$1|asil|asil}} sabanjuré',
'shown-title'                      => 'Tidokna $1 {{PLURAL:$1|asil|asil}} saben kaca',
'viewprevnext'                     => 'Deleng ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-exists'                => "''' Ana kaca nganggo jeneng \"[[:\$1]]\" nang wiki kiye.'''",
'searchmenu-new'                   => "'''Gawe kaca \"[[:\$1]]\" nang wiki kiye!'''",
'searchprofile-articles'           => 'Isine kaca',
'searchprofile-project'            => 'Kaca pitulung lan proyèk',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Kabèh',
'searchprofile-advanced'           => 'Lanjutan',
'searchprofile-articles-tooltip'   => 'Panggolèkan nang $1',
'searchprofile-project-tooltip'    => 'Goleti nang $1',
'searchprofile-images-tooltip'     => 'Panggolèkan berkas',
'searchprofile-everything-tooltip' => 'Goleti kabeh isi (termasuke kaca dhiskusi)',
'searchprofile-advanced-tooltip'   => 'Goleti nang bilik jeneng biasa',
'search-result-size'               => '$1 ({{PLURAL:$2|1 tembung|$2 tembung}})',
'search-redirect'                  => '(pangalihan $1)',
'search-section'                   => '(bagiyan $1)',
'search-suggest'                   => 'Apa maksude Rika kuwe: $1',
'searchrelated'                    => 'kagandhèng',
'searchall'                        => 'kabèh',
'showingresultsheader'             => "{{PLURAL:$5|Asil '''$1''' sekang '''$3'''|Asil '''$1 - $2''' sekang '''$3'''}} kanggo '''$4'''",
'search-nonefound'                 => "Ora ana kasil sing cocog karo pitakonan (''query'').",

# Preferences page
'mypreferences' => 'Preferensine Inyong',

# Recent changes
'nchanges'                        => '$1 {{PLURAL:$1|pangowahan|owah-owahan}}',
'recentchanges'                   => 'Pengobahan',
'recentchanges-label-newpage'     => 'Suntingan kiye gawé kaca anyar',
'recentchanges-label-minor'       => 'Kiye suntingan cilik',
'recentchanges-label-bot'         => 'Suntingan iki dilakokna déning bot',
'recentchanges-label-unpatrolled' => 'Suntingan kiye durung dipatroli',
'rcnotefrom'                      => 'Nang ngisor kiye owah-owahan wiwit <strong>$2</strong> (kapacak nganti <strong>$1</strong> owah-owahan).',
'rcshowhideminor'                 => '$1 suntingan cilik',
'rcshowhidebots'                  => '$1 bot',
'rcshowhideliu'                   => '$1 panganggo sing mlebu log',
'rcshowhideanons'                 => '$1 panganggo anonim',
'rcshowhidemine'                  => '$1 suntingane inyong',
'diff'                            => 'bédane',
'hist'                            => 'versi',
'hide'                            => 'Umpetna',
'show'                            => 'Tidokna',
'minoreditletter'                 => 'c',
'newpageletter'                   => 'A',
'boteditletter'                   => 'b',

# Recent changes linked
'recentchangeslinked'          => 'Pengobahan terkait',
'recentchangeslinked-feed'     => 'Pengobahan terkait',
'recentchangeslinked-toolbox'  => 'Pengobahan terkait',
'recentchangeslinked-noresult' => 'Ora ana owah-owahan nang kaca-kaca kagandhèng kiye salawasé periode sing wis ditemtokaké.',

# Upload
'upload'        => 'Unggah',
'uploadlogpage' => 'Log pangunggahan',
'uploadedimage' => 'ngunggahna"[[$1]]"',

'license' => 'Jenis lisènsi:',

# File description page
'file-anchor-link'       => 'Berkas',
'filehist'               => 'Sajarah kaca',
'filehist-help'          => 'Klik nang tanggal/wektu kanggo ndeleng berkas kiye nang wektu kuwe mau.',
'filehist-current'       => 'Sekiye',
'filehist-datetime'      => 'Tanggal/Wektu',
'filehist-thumb'         => "Miniatur (''thumbnail'')",
'filehist-user'          => 'Panganggo',
'filehist-dimensions'    => 'Ukuran',
'filehist-comment'       => 'Komentar',
'imagelinks'             => 'Pranala berkas',
'linkstoimage'           => '{{PLURAL:$1||}}$1 kaca kiye duwe pranala maring berkas kiye:',
'nolinkstoimage'         => 'Ora ana kaca sing nyambung maring berkas kiye.',
'sharedupload-desc-here' => 'Berkas kiye sekang $1 lan bisa baen digunakna nang proyek-proyek liyane.
Deskripsi sekang [$2 kaca deskripsine] ditidokna nang ngisor kiye.',

# Random page
'randompage' => 'Kaca Liya',

# Statistics
'statistics' => 'Statistik',

# Miscellaneous special pages
'nbytes'      => '$1 {{PLURAL:$1|bita|bita}}',
'prefixindex' => 'Kabèh kaca mawa ater-ater',
'newpages'    => 'Kaca anyar',
'move'        => 'Pindah',

# Special:Log
'log' => 'Log',

# Special:AllPages
'alphaindexline' => '$1 gutul $2',
'allpagessubmit' => 'Goleti',

# Special:Categories
'categories' => 'Kategori',

# Special:LinkSearch
'linksearch-line' => '$1 duwe pranala sekang  $2',

# Special:ListGroupRights
'listgrouprights-members' => '(daftar anggota)',

# Watchlist
'mywatchlist'       => 'Daftar pangawasane inyong',
'watchlistfor2'     => 'Kanggo $1 $2',
'watch'             => 'Pantau',
'unwatch'           => 'Batalna pantauan',
'watchlist-details' => 'Ana {{PLURAL:$1|$1 kaca|$1 kaca}} nang daftar pangawasane Rika, ningen ora ngitung kaca dhiskusi.',
'wlshowlast'        => 'Tidokna $1 jam $2 dina $3 pungkasan',
'watchlist-options' => 'Opsi daftar pangawasan',

# Delete
'actioncomplete' => 'Proses rampung',
'actionfailed'   => 'Tindakan gagal',
'dellogpage'     => 'Log pambusakan',

# Rollback
'rollbacklink' => 'balekna',

# Protect
'protectedarticle' => 'ngreksa "[[$1]]"',

# Undelete
'undeletelink'     => 'deleng/balekna',
'undeleteviewlink' => 'deleng',

# Namespace form on various pages
'namespace'      => 'Bilik jeneng:',
'invert'         => 'Balekna pilihan',
'blanknamespace' => '(Utama)',

# Contributions
'contributions' => 'Tulisan anggota',
'mycontris'     => 'Tulisan inyong',
'month'         => 'Sekang sasi (lan sadurungé):',
'year'          => 'Sekang taun (lan sadurunge):',

'sp-contributions-newbies'  => 'Tidokna kontribusine panganggo anyar thok',
'sp-contributions-search'   => 'Goleti kontribusine',
'sp-contributions-username' => 'Alamat IP utawa jeneng panganggo:',
'sp-contributions-submit'   => 'Goleti',

# What links here
'whatlinkshere'            => 'Pranala Kaca Kiye',
'nolinkshere'              => "Ora ana kaca sing nduwé pranala maring '''[[:$1]]'''.",
'whatlinkshere-hideimages' => '$1 pranala berkas',

# Block/unblock
'ipboptions'       => '2 jam:2 hours,1 dina:1 day,3 dina:3 days,1 minggu:1 week,2 minggu:2 weeks,1 sasi:1 month,3 sasi:3 months,6 sasi:6 months,1 taun:1 year,tanpa wates:infinite',
'blocklink'        => 'blokir',
'unblocklink'      => 'ilangna blokir',
'change-blocklink' => 'owahi blokir',
'contribslink'     => 'kontrib',
'blocklogpage'     => 'Log pamblokiran',
'blocklogentry'    => 'mblokir [[$1]] nganti gutul $2 $3',

# Move page
'revertmove' => 'Balekna',

# Export
'export' => 'Ekspor kaca',

# Thumbnails
'thumbnail-more' => 'Gedhèkna',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Kaca panganggone Rika',
'tooltip-pt-mytalk'              => 'Kaca dhiskusine Rika',
'tooltip-pt-preferences'         => 'Preferensine Rika',
'tooltip-pt-watchlist'           => 'Daftar kaca sing Rika awasi owah-owahane',
'tooltip-pt-mycontris'           => 'Daftar kontribusine Rika',
'tooltip-pt-login'               => 'Rika diajak kon mlebu log; senajan kuwe ora kudu.',
'tooltip-pt-logout'              => 'Metu Log',
'tooltip-ca-talk'                => 'Dhiskusi bab isine kaca kiye',
'tooltip-ca-edit'                => 'Rika teyeng nyunting kaca kiye. Mongo gunakna tombol tidhokna sedurunge nyimpen',
'tooltip-ca-addsection'          => 'Molai bageyan anyar',
'tooltip-ca-viewsource'          => 'Kaca kiye direksa.
Rika mung teyeng deleng sumbere thok',
'tooltip-ca-history'             => 'Versi-versi sedurunge sekang kaca kiye',
'tooltip-ca-move'                => 'Pindahna kaca kiye',
'tooltip-ca-watch'               => 'Tambahna kaca kiye maring daftar pengawasane Rika',
'tooltip-search'                 => 'Goleti {{SITENAME}}',
'tooltip-search-go'              => 'Lunga maring kaca sing jenenge padha plek kaya kiye angger ana',
'tooltip-search-fulltext'        => 'Goleti kaca sing duwe teks kaya kiye',
'tooltip-p-logo'                 => 'Mampir ming kaca utama',
'tooltip-n-mainpage'             => 'Mampir ming kaca utama',
'tooltip-n-mainpage-description' => 'Mampir ming kaca utama',
'tooltip-n-portal'               => 'Perkara proyek kiye, apa sing teyeng rika lakokna, lan nang endi angger arep ngoleti apa-apa',
'tooltip-n-currentevents'        => 'Temokna informasi bab prastawa anyar',
'tooltip-n-recentchanges'        => 'Daftar owahan anyar nang wiki',
'tooltip-n-randompage'           => 'Tidokna sembarang kaca',
'tooltip-n-help'                 => 'Panggonan nggo ngoleti pitulung',
'tooltip-t-whatlinkshere'        => 'Daftar kabeh kaca wiki sing duwe pranala maring kaca kiye',
'tooltip-t-recentchangeslinked'  => 'Owahan anyar nang kaca sing gandeng karo kaca kiye',
'tooltip-feed-atom'              => "''Atom feed'' kanggo kaca kiye",
'tooltip-t-emailuser'            => 'Kirimna e-mail maring panganggo kiye',
'tooltip-t-upload'               => 'Unggahna gambar utawa berkas media',
'tooltip-t-specialpages'         => 'Daftar kabeh kaca astamiwa',
'tooltip-t-print'                => 'Versi cithak kaca kiye',
'tooltip-t-permalink'            => 'Pranala permanen maring revisi kaca kiye',
'tooltip-ca-nstab-main'          => 'Deleng isi tulisan',
'tooltip-ca-nstab-project'       => 'Deleng kaca proyèk',
'tooltip-ca-nstab-image'         => 'Deleng kaca berkas',
'tooltip-ca-nstab-template'      => 'Deleng cithakan',
'tooltip-ca-nstab-category'      => 'Deleng kaca kategori',
'tooltip-minoredit'              => 'Tandani nek kiye kuwe suntingan cilik',
'tooltip-save'                   => 'Simpen owah-owahane Rika',
'tooltip-preview'                => 'Pratayang owah-owahane Rika, tulung kiye digunakna sedurunge nyimpen!',
'tooltip-diff'                   => 'Tidokna owah-owahane Rika maring teks kiye',
'tooltip-watch'                  => 'Tambahna kaca kiye maring daftar pengawasane Rika',
'tooltip-rollback'               => 'Balekna suntingan-suntingan nang kaca kiye maring kontributor pungkasan nganggo sa-klik-an baen',
'tooltip-undo'                   => 'Mbatalna revisi kiye lan mbukak kotak suntingan nganggo mode pratayang. Kiye ngaweh kesempatan nggo ngisi alesan nang kotak ringkesan.',

# Media information
'file-info-size' => '$1 × $2 piksel, ukuran berkas: $3, tipe MIME: $4',
'show-big-image' => 'Résolusi kebak',

# Bad image list
'bad_image_list' => 'Formate kaya kiye:

Mung butir daftar (baris sing diawali karo tanda*) sing melu diitung.
Pranala disit dhewek nang baris kuwe kudu pranala maring berkas sing ala.
Pranala seteruse nang baris sing padha dianggep dadi "pengecualian", yakuwe artikel sing bisa nampilna berkas kuwe mau.',

# Metadata
'metadata' => 'Metadata',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'kabèh',
'namespacesall' => 'kabèh',
'monthsall'     => 'kabèh',

# Watchlist editing tools
'watchlisttools-view' => 'Tidokna owahan sing ana gandhèngané',
'watchlisttools-edit' => 'Tidokna lan sunting daftar pangawasan',
'watchlisttools-raw'  => 'Sunting daftar pangawasan mentah',

# Special:SpecialPages
'specialpages' => 'Kaca-kaca khusus',

# Special:Tags
'tag-filter' => 'Filter [[Special:Tags|Tag]]:',

);
