<?php
/** Sundanese language file (Basa Sunda)
  *
  * Source: http://su.wikipedia.org/
  *
  * @addtogroup Language
  */
$fallback = 'id';

$namespaceNames = array(
	NS_MEDIA			=> 'Média',
	NS_SPECIAL			=> 'Husus',
	NS_MAIN				=> '',
	NS_TALK				=> 'Obrolan',
	NS_USER				=> 'Pamaké',
	NS_USER_TALK		=> 'Obrolan_pamaké',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK		=> 'Obrolan_$1',
	NS_IMAGE			=> 'Gambar',
	NS_IMAGE_TALK		=> 'Obrolan_gambar',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Obrolan_MediaWiki',
	NS_TEMPLATE			=> 'Citakan',
	NS_TEMPLATE_TALK	=> 'Obrolan_citakan',
	NS_HELP				=> 'Pitulung',
	NS_HELP_TALK		=> 'Obrolan_pitulung',
	NS_CATEGORY			=> 'Kategori',
	NS_CATEGORY_TALK	=> 'Obrolan_kategori',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Garis-handapan tumbu',
'tog-highlightbroken'         => 'Format tumbu pegat <a href="" class="new">kawas kieu</a> (atawa: kawas kieu<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Lempengkeun alinéa',
'tog-hideminor'               => 'Sumputkeun éditan minor dina nu anyar robah',
'tog-extendwatchlist'         => 'Legaan awaskeuneun ngarah sakabéh parobahanana katempo',
'tog-usenewrc'                => 'Nu anyar robah dina wanda séjén (JavaScript)',
'tog-numberheadings'          => 'Nomeran lulugu sacara otomatis',
'tog-showtoolbar'             => "Témbongkeun ''toolbar'' édit (JavaScript)",
'tog-editondblclick'          => 'Édit kaca ku klik ganda (JavaScript)',
'tog-editsection'             => 'Tambahkeun tumbu [édit] ngarah bisa ngarobah eusi bab',
'tog-editsectiononrightclick' => 'Fungsikeun ngédit sub-bagean kalawan klik-katuhu dina judul bagean (JavaScript)',
'tog-showtoc'                 => 'Témbongkeun daptar eusi<br />(pikeun kaca nu leuwih ti tilu subjudul)',
'tog-rememberpassword'        => 'Inget sandi nembus rintakan',
'tog-editwidth'               => 'Kotak édit sing lébar',
'tog-watchcreations'          => 'Awaskeun kaca jieunan kuring',
'tog-watchdefault'            => 'Tambahkeun kaca nu diédit ku anjeun kana awaskeuneun anjeun',
'tog-watchmoves'              => 'Awaskeun kaca nu dipindahkeun ku kuring',
'tog-watchdeletion'           => 'Awaskeun kaca nu dihapus ku kuring',
'tog-minordefault'            => 'Tandaan sadaya éditan salaku minor luyu jeung ti dituna',
'tog-previewontop'            => 'Témbongkeun sawangan méméh kotak édit (lain sanggeusna)',
'tog-previewonfirst'          => 'Témbongkeun sawangan dina éditan munggaran',
'tog-nocache'                 => 'Tumpurkeun <em>cache</em> kaca',
'tog-enotifwatchlistpages'    => 'Surélékan mun robah',
'tog-enotifusertalkpages'     => 'Mun kaca obrolan kuring robah, béjaan ngaliwatan surélék',
'tog-enotifminoredits'        => 'Béjaan ogé (ngaliwatan surélék) mun aya parobahan leutik dina kacana',
'tog-enotifrevealaddr'        => 'Témbongkeun alamat surélék kuring dina surat émbaran',
'tog-shownumberswatching'     => 'Témbongkeun jumlah nu ngawaskeun',
'tog-fancysig'                => 'Paraf kasar (tanpa tumbu otomatis)',
'tog-externaleditor'          => 'Pigunakeun éditor éksternal ti buhunna',
'tog-watchlisthideown'        => 'Sumputkeun éditan kuring dina daptar awaskeuneun',
'tog-watchlisthidebots'       => 'Sumputkeun éditan bot dina daptar awaskeuneun',
'tog-watchlisthideminor'      => 'Sumputkeun éditan leutik dina daptar awaskeuneun',
'tog-ccmeonemails'            => 'Kirimkeun ogé salinan surélékna ka alamat kuring',

'underline-always' => 'Salawasna',
'underline-never'  => 'Ulah',

'skinpreview' => '(Pramidang)',

# Dates
'sunday'        => 'Minggu',
'monday'        => 'Senén',
'tuesday'       => 'Salasa',
'wednesday'     => 'Rebo',
'thursday'      => 'Kemis',
'friday'        => 'Jumaah',
'saturday'      => 'Saptu',
'sun'           => 'Min',
'mon'           => 'Sen',
'tue'           => 'Sal',
'wed'           => 'Reb',
'thu'           => 'Kem',
'fri'           => 'Jum',
'sat'           => 'Sap',
'january'       => 'Januari',
'february'      => 'Pébruari',
'march'         => 'Maret',
'april'         => 'April',
'may_long'      => 'Méi',
'june'          => 'Juni',
'july'          => 'Juli',
'august'        => 'Agustus',
'september'     => 'Séptémber',
'october'       => 'Oktober',
'november'      => 'Nopémber',
'december'      => 'Désémber',
'january-gen'   => 'Januari',
'february-gen'  => 'Pébruari',
'march-gen'     => 'Maret',
'april-gen'     => 'April',
'may-gen'       => 'Méi',
'june-gen'      => 'Juni',
'july-gen'      => 'Juli',
'august-gen'    => 'Agustus',
'september-gen' => 'Séptémber',
'october-gen'   => 'Oktober',
'november-gen'  => 'Nopémber',
'december-gen'  => 'Désémber',
'jan'           => 'Jan',
'feb'           => 'Péb',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'Méi',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Ags',
'sep'           => 'Sép',
'oct'           => 'Okt',
'nov'           => 'Nop',
'dec'           => 'Dés',

# Bits of text used by many pages
'categories'            => 'Kategori',
'pagecategories'        => '{{PLURAL:$1|Kategori|Kategori}}',
'category_header'       => 'Artikel-artikel na kategori "$1"',
'subcategories'         => 'Subkategori',
'category-media-header' => 'Média dina kategori "$1"',

'mainpagetext'      => "''Software'' Wiki geus diinstal.",
'mainpagedocfooter' => "Mangga tingal ''[http://meta.wikimedia.org/wiki/MediaWiki_i18n documentation on customizing the interface]'' jeung [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Tungtunan Pamaké] pikeun pitulung maké jeung konfigurasi.",

'about'          => 'Ngeunaan',
'article'        => 'Kaca eusi',
'newwindow'      => '(buka na jandéla anyar)',
'cancel'         => 'Bolay',
'qbfind'         => 'Panggihan',
'qbbrowse'       => 'Sungsi',
'qbedit'         => 'Édit',
'qbpageoptions'  => 'Kaca ieu',
'qbpageinfo'     => 'Kontéx',
'qbmyoptions'    => 'Kaca kuring',
'qbspecialpages' => 'Kaca husus',
'moredotdotdot'  => 'Deui...',
'mypage'         => 'Kaca kuring',
'mytalk'         => 'Obrolan kuring',
'anontalk'       => 'Obrolan pikeun IP ieu',
'navigation'     => 'Tuduh Jalan',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Kasalahan',
'returnto'          => 'Balik deui ka $1.',
'tagline'           => 'Ti {{SITENAME}}',
'help'              => 'Pitulung',
'search'            => 'Sungsi',
'searchbutton'      => 'Téang',
'go'                => 'Jung',
'searcharticle'     => 'Jung',
'history'           => 'Jujutan kaca',
'history_short'     => 'Jujutan',
'info_short'        => 'Iber',
'printableversion'  => 'Vérsi citakeun',
'permalink'         => 'Tumbu permanén',
'print'             => 'Citak',
'edit'              => 'Édit',
'editthispage'      => 'Édit kaca ieu',
'delete'            => 'Hapus',
'deletethispage'    => 'Hapus kaca ieu',
'undelete_short'    => 'Bolaykeun ngahapus $1 éditan',
'protect'           => 'Konci',
'protect_change'    => 'rubah ngonci',
'protectthispage'   => 'Konci kaca ieu',
'unprotect'         => 'Buka konci',
'unprotectthispage' => 'Buka konci kaca ieu',
'newpage'           => 'Kaca anyar',
'talkpage'          => 'Sawalakeun kaca ieu',
'talkpagelinktext'  => 'Obrolan',
'specialpage'       => 'Kaca Husus',
'personaltools'     => 'Parabot pribadi',
'postcomment'       => 'Kirim koméntar',
'articlepage'       => 'Témbongkeun kaca eusi',
'talk'              => 'Sawala',
'views'             => 'Témbongan',
'toolbox'           => 'Kotak parabot',
'userpage'          => 'Témbongkeun kaca pamaké',
'projectpage'       => 'Témbongkeun kaca proyék',
'imagepage'         => 'Témbongkeun kaca gambar',
'mediawikipage'     => 'Témbongkeun kaca talatah',
'templatepage'      => 'Témbongkeun kaca citakan',
'viewhelppage'      => 'Témbongkeun kaca pitulung',
'categorypage'      => 'Témpo kaca kategori',
'viewtalkpage'      => 'Témbongkeun sawala',
'otherlanguages'    => 'Basa séjén',
'redirectedfrom'    => '(Dialihkeun ti $1)',
'redirectpagesub'   => 'Kaca alihan',
'lastmodifiedat'    => 'Kaca ieu panungtungan dirobah $2, $1.', # $1 date, $2 time
'viewcount'         => 'Kaca ieu geus dibuka $1 kali.',
'protectedpage'     => 'Kaca nu dikonci',
'jumpto'            => 'Luncat ka:',
'jumptonavigation'  => 'navigasi',
'jumptosearch'      => 'sungsi',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Ngeunaan {{SITENAME}}',
'aboutpage'         => '{{ns:project}}:Ngeunaan',
'bugreports'        => 'Laporan kutu',
'bugreportspage'    => 'Project:Laporan_kutu',
'copyright'         => 'Sadaya kandungan ieu loka ditangtayungan ku $1',
'copyrightpagename' => 'Hak cipta {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Hak cipta',
'currentevents'     => 'Keur lumangsung',
'currentevents-url' => 'Project:Keur lumangsung',
'disclaimers'       => 'Bantahan',
'disclaimerpage'    => '{{ns:project}}:Bantahan_umum',
'edithelp'          => 'Pitulung ngédit',
'edithelppage'      => 'Pitulung:Ngédit',
'faq'               => 'NLD',
'faqpage'           => '{{ns:project}}:NLD',
'helppage'          => '{{ns:project}}:Pitulung',
'mainpage'          => 'Tepas',
'portal'            => 'Panglawungan',
'portal-url'        => '{{ns:project}}: Panglawungan',
'privacy'           => 'Kawijakan privasi',
'privacypage'       => 'Project:Kawijakan privasi',
'sitesupport'       => 'Sumbangan',
'sitesupport-url'   => 'Project:Ngarojong loka',

'badaccess'        => 'Kasalahan widi',
'badaccess-group0' => 'Anjeun teu wenang ngalaksanakeun peta nu dipundut.',
'badaccess-group1' => 'Peta nu dipundut ngan bisa laksana pikeun pamaké ti gorombolan $1.',
'badaccess-group2' => 'Peta nu dipundut ngan bisa laksana pikeun pamaké ti salah sahiji gorombolan $1.',
'badaccess-groups' => 'Peta nu dipundut ngan bisa laksana pikeun pamaké ti salah sahiji gorombolan $1.',

'versionrequired'     => 'Butuh MediaWiki vérsi $1',
'versionrequiredtext' => 'Butuh MediaWiki vérsi $1 pikeun migunakeun ieu kaca. Mangga tingal [[Special:Version|kaca vérsi]]',

'ok'                  => 'Heug',
'pagetitle'           => '$1 - {{SITENAME}}',
'retrievedfrom'       => 'Disalin ti "$1"',
'youhavenewmessages'  => 'Anjeun boga $1 ($2).',
'newmessageslink'     => 'talatah anyar',
'newmessagesdifflink' => 'bédana ti nu saméméhna',
'editsection'         => 'édit',
'editold'             => 'édit',
'editsectionhint'     => 'Édit bab: $1',
'toc'                 => 'Daptar eusi',
'showtoc'             => 'témbongkeun',
'hidetoc'             => 'sumputkeun',
'thisisdeleted'       => 'Témbongkeun atawa simpen deui $1?',
'viewdeleted'         => 'Témbongkeun $1?',
'restorelink'         => '$1 éditan dihapus',
'feedlinks'           => 'Asupan:',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Artikel',
'nstab-user'      => 'Kaca pamaké',
'nstab-media'     => 'Média',
'nstab-special'   => 'Husus',
'nstab-project'   => 'Ngeunaan',
'nstab-image'     => 'Gambar',
'nstab-mediawiki' => 'Talatah',
'nstab-template'  => 'Citakan',
'nstab-help'      => 'Pitulung',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Teu aya peta kitu',
'nosuchactiontext'  => 'Peta nu diketik na URL teu dipikawanoh ku wiki',
'nosuchspecialpage' => 'Teu aya kaca husus nu kitu',
'nospecialpagetext' => 'Anjeun geus ménta kaca husus nu teu dipikawanoh ku wiki.',

# General errors
'error'            => 'Kasalahan',
'databaseerror'    => 'Kasalahan gudang data',
'dberrortext'      => 'Kasalahan rumpaka mundut databasis.
Ieu bisa nunjukkeun ayana kutu na parabot leuleusna.
Pamundut databasis nu panungtungan nyaéta:
<blockquote><tt>$1</tt></blockquote>
ti antara fungsi "<tt>$2</tt>".
Kasalahan MySQL nu mulang "<tt>$3: $4</tt>".',
'dberrortextcl'    => 'Kasalahan rumpaka mundut databasis.
Pamuncut databasis nu panungtungan nyaéta:
"$1"
ti antara fungsi "$2".
Kasalahan MySQL nu mulang "$3: $4".',
'noconnect'        => 'Punten! Wiki ngalaman sababaraha kasusah téhnis sarta teu bisa ngontak server pangkalan data.<br />$1',
'nodb'             => 'Teu bisa milih pangkalan data $1',
'cachederror'      => 'Kanggo kaca nu dipénta, di handap ieu mangrupa salinan ti nu aya, tiasa waé tos tinggaleun jaman.',
'laggedslavemode'  => 'Awas: kandungan kaca bisa baé teu mutahir.',
'readonly'         => 'pangkalan data dikonci',
'enterlockreason'  => 'Asupkeun alesan pikeun ngonci, kaasup kira-kira iraha konci ieu rék dibuka',
'readonlytext'     => 'pangkalan data kiwar keur di konci pikeun éntri anyar sarta parobahan séjénna, meureun pikeun pangropéa pangkalan datarutin, nu satutasna mah bakal normal deui. Kuncén nu ngonci ngécéskeun kieu:
<p>$1',
'missingarticle'   => "Pangkalan data teu manggihan téks kaca nu sakuduna aya, ngaranna \"\$1\".

<p>Kitu téh ilaharna mah alatan nuturkeun tumbu jujutan atawa diff bihari ka kaca nu geus dihapus.

<p>Mun lain kitu masalahna, jigana anjeun geus manggihan kutu na ''software''na. Mangga wartoskeun ka kuncén kalawan nuduhkeun URL-na.",
'readonly_lag'     => 'pangkalan datana sacara otomatis dikonci nalika server pangkalan data disalin kana master',
'internalerror'    => 'Kasalahan internal',
'filecopyerror'    => 'Teu bisa nyalin koropak "$1" ka "$2".',
'filerenameerror'  => 'Teu bisa ngaganti ngaran koropak "$1" jadi "$2".',
'filedeleteerror'  => 'Teu bisa ngahapus koropak "$1".',
'filenotfound'     => 'Teu bisa manggihan koropak "$1".',
'formerror'        => 'Kasalahan: teu bisa ngirim formulir',
'badarticleerror'  => 'Peta ieu teu bisa dipigawé na kaca ieu.',
'cannotdelete'     => 'Teu bisa ngahapus kaca atawa gambar nu dimaksud (bisa jadi geus aya nu ngahapus saméméhna).',
'badtitle'         => 'Judul goréng',
'badtitletext'     => 'Judul kaca nu dipénta teu sah, kosong, atawa judul antarbasa atawa antarwikina salah tumbu.',
'perfdisabled'     => 'Punten! Fungsi ieu pikeun samentawis ditumpurkeun sabab ngahambat pangkalan data nepi ka titik di mana teu saurang ogé bisa migunakeun wiki.',
'perfcached'       => 'Data di handap ieu sindangan sahingga--meureun--teu mutahir:',
'perfcachedts'     => 'Data di handap ieu mah sindangan, panungtungan diropéa téh $1.',
'viewsource'       => 'Témbongkeun sumber',
'viewsourcefor'    => 'pikeun $1',
'editinginterface' => "'''Perhatosan:''' Anjeun ngédit kaca nu dipaké pikeun nyadiakeun téks antarbeungeut pikeun parabot lemesna. Parobahan kana kaca ieu bakal mangaruhan panémbong antarbeungeut pamaké pikeun pamaké séjén.",

# Login and logout pages
'logouttitle'                => 'Kaluar log pamaké',
'logouttext'                 => 'Anjeun ayeuna geus kaluar log. Anjeun bisa neruskeun migunakeun {{SITENAME}} bari anonim, atawa bisa asup log deui maké pamaké nu sarua atawa nu béda. Perlu dicatet yén sababaraha kaca bakal terus némbongan saolah-olah anjeun asup log kénéh nepi ka anjeun ngosongkeun sindangan panyungsi anjeun.',
'welcomecreation'            => '<h2>Wilujeng sumping, $1!</h2><p>Rekening anjeun geus dijieun. Tong hilap ngarobih préferénsi {{SITENAME}} anjeun.',
'loginpagetitle'             => 'Asup log pamaké',
'yourname'                   => 'Ngaran pamaké anjeun',
'yourpassword'               => 'Sandi anjeun',
'yourpasswordagain'          => 'Ketik deui sandi anjeun',
'remembermypassword'         => 'Inget sandi kuring nembus rintakan.',
'yourdomainname'             => 'Domain anjeun',
'externaldberror'            => 'Aya kasalahan dina pangkalan data oténtikasi luar, atawa anjeun mémang teu diwenangkeun pikeun ngaropéa rekening luar anjeun.',
'loginproblem'               => "<b>Aya masalah na ''login'' anjeun.</b><br />Coba deui!",
'alreadyloggedin'            => '<strong>Pamaké $1, anjeun geus asup log!</strong><br />',
'login'                      => 'Asup log',
'loginprompt'                => "Anjeun kudu boga ''cookies'' sangkan bisa asup log ka {{SITENAME}}.",
'userlogin'                  => 'Nyieun rekening atawa asup log',
'logout'                     => 'Kaluar log',
'userlogout'                 => 'Kaluar log',
'notloggedin'                => 'Can asup log',
'nologin'                    => 'Teu gaduh rekening? $1.',
'nologinlink'                => 'Jieun rekening',
'createaccount'              => 'Jieun rekening anyar',
'gotaccount'                 => 'Geus boga rekening? $1.',
'gotaccountlink'             => 'Asup log',
'createaccountmail'          => 'ku surélék',
'badretype'                  => 'Sandi nu diasupkeun teu cocog.',
'userexists'                 => 'Ngaran pamaké nu diasupkeun ku anjeun geus aya nu maké. Mangga pilih ngaran nu séjén.',
'youremail'                  => 'Surélék anjeun*',
'username'                   => 'Landihan:',
'uid'                        => 'ID pamaké:',
'yourrealname'               => 'Ngaran anjeun*',
'yourlanguage'               => 'Basa antarbeungeut',
'yourvariant'                => 'Varian basa',
'yournick'                   => 'Landihan anjeun (pikeun tawis leungeun)',
'badsig'                     => 'Parafna teu valid; pariksa tag HTML-na geura.',
'email'                      => 'Surélék',
'prefs-help-realname'        => '* Ngaran asli (pilihan): mun anjeun milih ngeusian, bakal dipaké pikeun nandaan kontribusi anjeun.',
'loginerror'                 => 'Kasalahan asup log',
'prefs-help-email'           => '* Surélék (pilihan): batur bisa ngontak anjeun tina kaca pamaké atawa obrolanana tanpa kudu nyebutkeun idéntitas anjeun.',
'nocookiesnew'               => "Rekening pamaké geus dijieun, tapi anjeun can asup log. {{SITENAME}} maké ''cookies'' pikeun ngasupkeun log pamaké. Anjeun boga ''cookies'' nu ditumpurkeun. Mangga fungsikeun, teras asup log migunakeun ngaran pamaké sarta sandi nu anyar.",
'nocookieslogin'             => "{{SITENAME}} migunakeun ''cookies'' pikeun ngasupkeun pamaké kana log. Anjeun boga ''cookies'' nu ditumpurkeun. Mangga pungsikeun sarta cobian deui.",
'noname'                     => 'Anjeun teu nuliskeun ngaran pamaké nu sah.',
'loginsuccesstitle'          => 'Asup log geus hasil',
'loginsuccess'               => 'Anjeun ayeuna geus asup log ka {{SITENAME}} salaku "$1".',
'nosuchuser'                 => 'Teu aya pamaké nu ngaranna "$1". Pariksa éjahanana, atawa paké formulir di handap pikeun nyieun rekening pamaké anyar.',
'nosuchusershort'            => 'Taya pamaké nu ngaranna "$1", pariksa éjahanana!',
'nouserspecified'            => 'Anjeun kudu ngeusian ngaran landihan.',
'wrongpassword'              => 'Sandi nu diasupkeun teu cocog. Mangga cobian deui.',
'wrongpasswordempty'         => 'Sandina can kaeusian. Cobaan deui!',
'mailmypassword'             => 'Kirim sandi anyar ngaliwatan surélék',
'passwordremindertitle'      => 'Pangéling sandi ti {{SITENAME}}',
'passwordremindertext'       => 'Aya (jigana anjeun ti alamat IP $1) nu ménta sangkan dikiriman sandi anyar asup log {{SITENAME}} ($4). Sandi keur pamaké "$2" ayeuna nyaéta "$3". Anjeun kudu asup log sarta ngarobah sandi anjeun ayeuna.',
'noemail'                    => 'Teu aya alamat surélék karékam pikeun "$1".',
'passwordsent'               => 'Sandi anyar geus dikirim ka alamat surélék nu kadaptar pikeun "$1". Mangga asup log deui satutasna katarima.',
'blocked-mailpassword'       => 'Alamat IP anjeun dipeungpeuk, moal bisa ngédit, and so
is not allowed to use the password recovery function to prevent abuse.',
'eauthentsent'               => 'Surélék konfirmasi geus dikirim ka alamat bieu. Méméh aya surat séjén asup ka rekeningna, anjeun kudu nuturkeun pituduh na surélékna pikeun ngonfirmasi yén rekening éta téh bener nu anjeun.',
'mailerror'                  => 'Kasalahan ngirim surat: $1',
'acct_creation_throttle_hit' => 'Punten, anjeun geus nyieun $1 rekening, teu bisa nyieun deui.',
'emailauthenticated'         => 'Alamat surélék anjeun geus dioténtikasi $1.',
'emailnotauthenticated'      => 'Alamat surélék anjeun <strong>can dioténtikasi</strong>. Moal aya surélék nu bakal dikirim pikeun fitur-fitur di handap ieu.',
'noemailprefs'               => '<strong>Teu aya alamat surélék</strong>, fitur di handap moal bisa jalan.',
'emailconfirmlink'           => 'Konfirmasi alamat surélék anjeun',
'invalidemailaddress'        => 'Alamat surélék teu bisa ditarima sabab formatna salah. Mangga lebetkeun alamat nu formatna bener atawa kosongkeun.',
'accountcreated'             => 'Rekening ngeus di jieun.',
'accountcreatedtext'         => 'Rekening pamaké pikeun $1 geus dijieun.',

# Password reset dialog
'resetpass_text' => '<!-- Tambahkeun téks di dieu -->',

# Edit page toolbar
'bold_sample'     => 'Téks kandel',
'bold_tip'        => 'Téks kandel',
'italic_sample'   => 'Tulisan déngdék',
'italic_tip'      => 'Tulisan déngdék',
'link_sample'     => 'Judul tumbu',
'link_tip'        => 'Tumbu internal',
'extlink_sample'  => 'Judul tumbu http://www.conto.com',
'extlink_tip'     => 'Tumbu kaluar (inget awalan http://)',
'headline_sample' => 'Téks judul',
'headline_tip'    => 'Judul tingkat 2',
'math_sample'     => 'Asupkeun rumus di dieu',
'math_tip'        => 'Rumus matematis (LaTeX)',
'nowiki_sample'   => 'Asupkeun téks nu teu diformat di dieu',
'nowiki_tip'      => 'Format wiki tong diwaro',
'image_sample'    => 'Conto.jpg',
'media_sample'    => 'Conto.mp3',
'media_tip'       => 'Tumbu koropak média',
'sig_tip'         => 'Tawis leungeun anjeun tur cap wanci',
'hr_tip'          => 'Garis horizontal (use sparingly)',

# Edit pages
'summary'                   => 'Ringkesan',
'subject'                   => 'Jejer/Judul',
'minoredit'                 => 'Ieu éditan minor',
'watchthis'                 => 'Awaskeun kaca ieu',
'savearticle'               => 'Simpen',
'preview'                   => 'Sawangan',
'showpreview'               => 'Témbongkeun sawangan',
'showlivepreview'           => 'Sawangan langsung',
'showdiff'                  => 'Témbongkeun parobahan',
'anoneditwarning'           => "'''Perhatosan:''' Anjeun can asup log. IP anjeun kacateut dina jujutan kaca ieu",
'summary-preview'           => 'Ringkeusan sawangan',
'blockedtitle'              => 'Pamaké dipeungpeuk',
'blockedtext'               => "<big>'''Ngaran pamaké atawa alamat IP anjeun dipeungpeuk.'''</big>

Dipeungpeuk ku \$1. Alesanana:''\$2''.

dipeungpeuk kadaluarsa dina: \$6

Anjeun bisa nepungan \$1 atawa salasahiji [[{{MediaWiki:grouppage-sysop}}|kuncén]] séjén pikeun nyawalakeun hal ieu.

'''<u>Catet</u>''': yén anjeun teu bisa maké fungsi \"surélékan pamaké ieu\" mun anjeun teu ngadaptarkeun alamat surélék nu sah kana [[Special:Preferences|préferénsi pamaké]] anjeun.

Alamat IP anjeun \$3 jeung ID na \$5, lampirkeun informasi ieu dina unggal ''query'' anjeun.",
'blockedoriginalsource'     => "Sumber '''$1''' dipidangkeun di handap ieu:",
'blockededitsource'         => "Tulisan '''éditan anjeun''' dina '''$1''' dipidangkeun di handap ieu:",
'whitelistedittitle'        => 'Perlu asup log sangkan bisa ngédit',
'whitelistedittext'         => 'Anjeun kudu asup $1 sangkan bisa ngédit.',
'whitelistreadtitle'        => 'Perlu asup log pikeun maca',
'whitelistreadtext'         => 'Anjeun kudu asup [[Special:Userlogin|log]] sangkan bisa maca.',
'whitelistacctitle'         => 'Anjeun teu diwenangkeun nyieun rekening',
'whitelistacctext'          => 'Sangkan diwenangkeun nyieun rekening na wiki ieu, anjeun kudu asup [[Special:Userlogin|log]] sarta boga kawenangan nu cukup.',
'confirmedittext'           => 'Saméméh ngédit, kompirmasikeun heula alamat surélék anjeun. Mangga setél, lajeng sahkeun alamat surélék anjeun dina [[{{ns:special}}:Preferences|préferénsi pamaké]].',
'nosuchsectiontext'         => 'Anjeun geus nyoba ngarobah bab $1 nu sabenerna euweuh, ku kituna robahan anjeun teu bisa disimpen.',
'loginreqtitle'             => 'Kudu asup log',
'loginreqlink'              => 'asup log',
'loginreqpagetext'          => 'Mun hayang muka kaca séjénna, Anjeun kudu $1.',
'accmailtitle'              => 'Sandi geus dikirim.',
'accmailtext'               => "Sandi keur '$1' geus dikirim ka $2.",
'newarticle'                => '(Anyar)',
'newarticletext'            => "Anjeun geus nuturkeun tumbu ka kaca nu can aya.
Pikeun nyieun kaca, mimitian ku ngetik jeroeun kotak di handap
(tempo [[{{ns:project}}:Pitulung|kaca pitulung]] pikeun leuwih écés).
Mun anjeun ka dieu teu ngahaja, klik baé tombol '''back''' na panyungsi anjeun.",
'anontalkpagetext'          => "----''Ieu mangrupa kaca sawala pikeun pamaké anonim nu can (henteu) nyieun rekening, kusabab kitu [[alamat IP]] dipaké dina hal ieu pikeun nyirikeun anjeunna. Alamat IP ieu bisa dipaké ku sababaraha urang. Mun anjeun salasahiji pamaké anonim sarta ngarasa aya koméntar nu teu pakait geus ditujukeun ka anjeun, leuwih hadé [[Special:Userlogin|nyieun rekening atawa asup log]] sangkan teu pahili jeung pamaké anonim séjén.''",
'noarticletext'             => "Kiwari can aya téks na kaca ieu. Mun anjeun geus kungsi nyieun kaca ieu, coba fungsi [{{fullurl:{{FULLPAGENAME}}|action=edit}} edit].

'''{{ns:Project}} téh lain layanan iklan.''' Artikel promosi ngeunaan diri anjeun, babaturan anjeun, usaha anjeun; atawa artikel nu dijieun salaku bagian tina kampanyeu dagang atawa promosi, bisa dihapus luyu jeung [[{{ns:Project}}:Kawijakan ngahapus|kawijakan ngahapus]]. Pikeun leuwih jéntré, mangga aos [[{{ns:Project}}:Spam]].
</div>
</div>",
'clearyourcache'            => "'''Catetan:''' Sanggeus nyimpen, anjeun perlu ngosongkeun sindangan panyungsi anjeun pikeun nempo parobahanana:
'''Mozilla/Safari/Konqueror:''' pencét & tahan ''Shift'' bari ngaklik ''Reload'' (atawa pencét ''Ctrl-Shift-R''), '''IE:''' pencét ''Ctrl-F5'', '''Opera:''' pencét ''F5''.",
'usercssjsyoucanpreview'    => "<strong>Tip:</strong> Pigunakeun tombol 'Témbongkeun sawangan' pikeun nyoba css/js anyar anjeun méméh nyimpen.",
'usercsspreview'            => "'''Inget yén anjeun ukur nyawang css pamaké anjeun, can disimpen!'''",
'userjspreview'             => "'''Inget yén anjeun ukur nguji/nyawang ''javascript'' pamaké anjeun, can disimpen!'''",
'userinvalidcssjstitle'     => "'''Awas''': kulit \"\$1\" mah teu aya. Sing émut yén kaca .css jeung .js mah migunakeun aksara leutik dina judulna, contona baé {{ns:user}}:Foo/monobook.css lawan {{ns:user}}:Foo/Monobook.css.",
'updated'                   => '(Geus diropéa)',
'note'                      => '<strong>Catetan:</strong>',
'previewnote'               => 'Inget yén ieu ukur sawangan, can disimpen!',
'previewconflict'           => 'Sawangan ieu mangrupa eunteung pikeun téks na rohangan ngédit sakumaha bakal katémbong mun ku anjeun disimpen.',
'session_fail_preview'      => '<strong>Punten! Kami teu bisa ngolah éditan anjeun alatan leungitna data rintakan. Mangga cobian deui. Mun tetep teu bisa, cobi kaluar log lajeng lebet deui.</strong>',
'session_fail_preview_html' => "<strong>Punten! Kami teu bisa ngolah éditan anjeun sabab leungitna data rintakan.</strong>

''Kusabab ieu wiki ngawenangkeun dipakéna HTML atah, pramidangna disumputkeun pikeun nyegah panarajang JavaScript.''

<strong>Mun ieu éditan bener, mangga cobian deui. Mun tetep teu metu, cobi kaluar log heula, lajeng lebet deui.</strong>",
'importing'                 => 'Ngimpor $1',
'editing'                   => 'Ngédit $1',
'editinguser'               => 'Ngédit $1',
'editingsection'            => 'Ngédit $1 (bagian)',
'editingcomment'            => 'Ngédit $1 (pamanggih)',
'editconflict'              => 'Konflik éditan: $1',
'explainconflict'           => 'Aya nu geus ngarobah kaca ieu saprak anjeun mimiti ngédit. Téks béh luhur ngandung téks kaca nu aya kiwari, parobahan anjeun ditémbongkeun di béh handap. Anjeun kudu ngagabungkeun parobahan anjeun kana téks nu kiwari.
<b>Ngan</b> téks nu béh luhur nu bakal disimpen nalika anjeun mencét "Simpen".
<p>',
'yourtext'                  => 'Tulisan anjeun',
'storedversion'             => 'Vérsi nu disimpen',
'nonunicodebrowser'         => '<strong>AWAS: Panyungsi anjeung teu maké unicode, mangga robah heula méméh ngédit artikel.</strong>',
'editingold'                => '<strong>PERHATOSAN: Anjeun ngédit révisi kadaluwarsa kaca ieu. Mun ku anjeun disimpen, sagala parobahan nu dijieun sanggeus révisi ieu bakal leungit.</strong>',
'yourdiff'                  => 'Béda',
'copyrightwarning'          => "Perhatikeun yén sadaya kontribusi ka MediaWiki dianggap medal dina panangtayungan lisénsi $2 (tempo $1 pikeun jéntréna). Mun anjeun teu miharep tulisan anjeun dirobah sarta disebarkeun deui, ulah dilebetkeun ka dieu.<br />
Anjeun ogé jangji yén tulisan ieu dijieun ku sorangan, atawa disalin ti ''domain'' umum atawa sumberdaya bébas séjénna. <strong>ULAH NGASUPKEUN KARYA NU MIBANDA HAK CIPTA TANPA IDIN!</strong>
</div>",
'copyrightwarning2'         => 'Catet yén sadaya kontribusi ka {{SITENAME}} bisa diédit, dirobah, atawa dihapus ku kontributor séjén. Mun anjeun teu miharep tulisan anjeun dirobah, ulah ngintunkeun ka dieu.<br />
Anjeun ogé mastikeun yén ieu téh pituin tulisan anjeun, atawa salinan ti domain umum atawa sumberdaya bébas séjénna (tempo $1 pikeun écésna).
<strong>ULAH NGINTUNKEUN KARYA NU MIBANDA HAK CIPTA TANPA WIDI!</strong>',
'longpagewarning'           => 'PERHATOSAN: Kaca ieu panjangna $1 kilobytes; sababaraha panyungsi boga masalah dina ngédit kaca nu panjangna nepi ka 32kb. Please consider breaking the page into smaller sections.',
'readonlywarning'           => "PERHATOSAN: pangkalan data dikonci pikeun diropéa, anjeun moal bisa nyimpen éditan anjeun ayeuna. Cobi ''cut-n-paste'' téksna ka na koropak téks sarta simpen dina waktu séjén.",
'protectedpagewarning'      => "<strong>PERHATOSAN: Kaca ieu dikonci sahingga ngan bisa dirobah ku pamaké nu statusna kuncén. Pastikeun yén anjeun tumut kana [[{{ns:project}}:tungtunan_kaca_nu_dikonci'|tungtunan kaca nu dikonci]].</strong>",
'semiprotectedpagewarning'  => "'''Perhatoskeun''': ieu kaca dikonci sahingga ukur bisa dirobah ku pamaké nu geus asup log.",
'cascadeprotectedwarning'   => "'''Awas''': ieu kaca dikonci sahingga ukur bisa dirobah ku kuncén, sabab kaasup {{PLURAL:$1|kaca|kaca}} dina panyalindungan-ngaruntuy di handap ieu:",
'templatesused'             => 'Citaka nu dipaké na kaca ieu:',
'template-protected'        => '(dikunci)',

# "Undo" feature
'undo-summary' => '←Ngabatalkeun révisi $1 ku [[Special:Contributions/$2|$2]] ([[User talk:$2|Obrolan]])',

# Account creation failure
'cantcreateaccounttitle' => 'Rekening teu bisa dijieun',

# History pages
'revhistory'          => 'Jujutan révisi',
'viewpagelogs'        => 'Tempo log kaca ieu',
'nohistory'           => 'Teu aya jujutan édit pikeun kaca ieu.',
'revnotfound'         => 'Révisi teu kapanggih',
'revnotfoundtext'     => 'Révisi heubeul kaca nu dipénta ku anjeun teu bisa kapanggih.
Please check the URL you used to access this page.',
'loadhist'            => 'Keur ngamuat jujutan kaca',
'currentrev'          => 'Révisi kiwari',
'revisionasof'        => 'Révisi nurutkeun $1',
'previousrevision'    => '← Révisi leuwih heubeul',
'nextrevision'        => 'Révisi nu leuwih anyar →',
'currentrevisionlink' => 'Témbongkeun révisi kiwari',
'cur'                 => 'kiw',
'next'                => 'salajengna',
'last'                => 'ahir',
'orig'                => 'asli',
'histlegend'          => 'Pilihan béda: tandaan wadah buleud vérsina pikeun ngabandingkeun sarta pencét énter atawa tombol di handap.<br />
Katerangan: (kiw) = bédana jeung vérsi kiwari,
(ahir) = bédana jeung vérsi nu harita, m = éditan minor.',
'deletedrev'          => '[dihapus]',
'histfirst'           => 'Pangheubeulna',
'histlast'            => 'Pangahirna',
'historyempty'        => '(kosong)',

# Revision feed
'history-feed-title'          => 'Sajarah révisi',
'history-feed-description'    => 'Sajarah révisi kaca ieu di wiki',
'history-feed-item-nocomment' => '$1 dina $2', # user at time

# Revision deletion
'rev-deleted-comment'         => '(koméntar dihapus)',
'rev-deleted-user'            => '(ngaran pamaké geus dihapus)',
'rev-deleted-event'           => '(eusi dihapus)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Révisi kaca ieu geus dihapus tina arsip publik. Dadaranana meureun aya dina [{{fullurl:Husus:Log/delete|page={{PAGENAMEE}}}} log hapusan].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Révisi kaca ieu geus dihapus tina arsip publik. Tapi, salaku administrator dina loka ieu, anjeun bisa nempo; dadaranana meureun aya dina [{{fullurl:Husus:Log/delete|page={{PAGENAMEE}}}} log hapusan].
</div>',
'rev-delundel'                => 'témbongkeun/sumputkeun',
'revisiondelete'              => 'Hapus/bolay ngahapus révisi',
'revdelete-selected'          => "{{PLURAL:$2|Révisi pilihan|Révisi pilihan}} pikeun '''$1'''",
'revdelete-legend'            => 'Setél réstriksi révisi:',
'revdelete-hide-text'         => 'Sumputkeun téks révisi',
'revdelete-hide-comment'      => 'Sumputkeun koméntar ngédit',
'revdelete-hide-user'         => 'Sumputkeun ngaran pamaké/IP éditor',
'revdelete-hide-restricted'   => 'Larapkeun ieu réstriksi boh ka kuncén atawa nu séjénna',
'revdelete-suppress'          => 'Sumputkeun ogé ti kuncén',
'revdelete-hide-image'        => 'Sumputkeun eusi gambar',
'revdelete-log'               => 'Koméntar log:',

# Diffs
'difference'                => '(Béda antarrévisi)',
'loadingrev'                => 'ngamuat béda révisi',
'lineno'                    => 'Baris ka-$1:',
'editcurrent'               => 'Édit vérsi kiwari kaca ieu',
'selectnewerversionfordiff' => 'Pilih vérsi nu leuwih anyar pikeun babandingan',
'selectolderversionfordiff' => 'Pilih vérsi nu leuwih heubeul pikeun babandingan',
'compareselectedversions'   => 'Bandingkeun vérsi nu dipilih',
'editundo'                  => 'bolaykeun',

# Search results
'searchresults'         => 'Hasil néangan',
'searchresulttext'      => 'Pikeun iber nu leuwih lengkep ngeunaan nyaksrak di {{SITENAME}}, buka [[{{ns:project}}:Nyaksrak|Nyaksrak {{SITENAME}}]].',
'searchsubtitle'        => 'Pikeun pamundut "[[:$1]]"',
'searchsubtitleinvalid' => 'Pikeun pamundut "$1"',
'badquery'              => 'Pamundut néang formatna salah',
'badquerytext'          => "Kami teu bisa ngolah ''query'' anjeun, biasana sabab anjeun nyoba néang kecap nu ukur hiji/dua aksara, nu mémang can dirojong; bisa ogé alatan anjeun salah ngetik. Mangga cobian deui.",
'matchtotals'           => '\'\'Query\'\' "$1" cocog jeung $2 judul kaca sarta tulisan na $3 kaca.',
'noexactmatch'          => '<span style="font-size: 135%; font-weight: bold; margin-left: .6em">Teu aya kaca nu judulna kitu</span>

<span style="display: block; margin: 1.5em 2em">
Coba saksrak na téks lengkep, atawa \'\'\'[[<nowiki>$1</nowiki>|nyieun artikel nu judulna kitu]]\'\'\' atawa [[{{ns:project}}:Artikel pamundut|mundut dijieunna éta artikel]].

<span style="display:block; font-size: 89%; margin-left:.2em">Mangga sungsi {{SITENAME}} saméméh nyieun artikel anyar, pikeun ngahindarkeun artikel ganda nu ukur béda éjahan/ngaran.</span>
</span>',
'titlematches'          => 'Judul artikel nu cocog',
'notitlematches'        => 'Teu aya judul kaca nu cocog',
'textmatches'           => 'Téks kaca nu cocog',
'notextmatches'         => 'Teu aya téks kaca nu cocog',
'prevn'                 => '$1 saméméhna',
'nextn'                 => '$1 salajengna',
'viewprevnext'          => 'Témbongkeun ($1) ($2) ($3).',
'showingresults'        => 'Di handap ieu némbongkeun <b>$1</b> hasil tina #<b>$2</b>.',
'showingresultsnum'     => 'Di handap ieu némbongkeun <b>$3</b> hasil tina #<b>$2</b>.',
'nonefound'             => '<strong>Catetan</strong>: panéangan nu teu hasil mindeng disababkeun ku néang kecap umum kawas "ti" nu teu diasupkeun kana indéks, atawa alatan nangtukeun leuwih ti hiji istilah panéang (ngan kaca-kaca nu ngandung sakabéh istilah panéang nu bakal némbongan).',
'powersearch'           => 'Sungsi',
'powersearchtext'       => 'Sungsi na ngaranspasi:<br />
$1<br />
$2 Daptarkeun alihan &nbsp; Sungsi $3 $9',
'searchdisabled'        => '<p style="margin: 1.5em 2em 1em">Punten! Néangan téks lengkep di {{SITENAME}} kanggo samentawis ditumpurkeun pikeun alesan kinerja. Jalaran kitu, saheulaanan anjeun bisa nyungsi di Google di handap ieu.
<span style="font-size: 89%; display: block; margin-left: .2em">Catet yén indéxna ngeunaan eusi {{SITENAME}} bisa jadi teu mutahir.</span></p>',
'blanknamespace'        => '(Utama)',

# Preferences page
'preferences'           => 'Préferénsi',
'mypreferences'         => 'Préferénsi kuring',
'prefsnologin'          => 'Can asup log',
'prefsnologintext'      => 'Anjeun kudu [[Special:Userlogin|asup log]] pikeun ngatur préferénsi pamaké.',
'prefsreset'            => 'Préferénsi geus disét ulang tina arsip.',
'changepassword'        => 'Robah sandi',
'skin'                  => 'Kulit',
'dateformat'            => 'Format titimangsa',
'datedefault'           => 'Tanpa préferénsi',
'datetime'              => 'Titimangsa jeung wanci',
'math_unknown_error'    => 'Kasalahan teu kanyahoan',
'math_unknown_function' => 'fungsi teu kanyahoan',
'math_syntax_error'     => 'Kasalahan rumpaka',
'prefs-personal'        => 'Data pamaké',
'prefs-rc'              => 'Panémbong robahan anyar jeung tukung',
'prefs-misc'            => 'Pangaturan rupa-rupa',
'saveprefs'             => 'Simpen préferénsi',
'resetprefs'            => 'Sét ulang préferénsi',
'oldpassword'           => 'Sandi heubeul',
'newpassword'           => 'Sandi anyar',
'retypenew'             => 'Ketik ulang sandi',
'textboxsize'           => 'Ukuran kotak téks',
'rows'                  => 'Baris',
'columns'               => 'Kolom',
'searchresultshead'     => 'Aturan hasil néang',
'resultsperpage'        => 'Hasil nu ditémbongkeun per kaca',
'contextlines'          => 'Jumlah baris sakali némbongan',
'contextchars'          => 'Karakter kontéks per baris',
'recentchangescount'    => 'Jumlah judul nu anyar robah',
'savedprefs'            => 'Préferénsi anjeun geus disimpen.',
'timezonelegend'        => 'Wewengkon wanci',
'timezonetext'          => 'Asupkeun sabaraha jam bédana antara wanci di tempat anjeun jeung wanci server (UTC).',
'localtime'             => 'Témbongan wanci lokal',
'servertime'            => 'Waktu server ayeuna',
'guesstimezone'         => 'Eusian ti panyungsi',
'allowemail'            => 'Buka koropak pikeun nampa surélék ti nu séjén',
'defaultns'             => 'Téang ti antara spasingaran ieu luyu jeung ti dituna:',
'default'               => 'ti dituna',
'files'                 => 'Koropak',

# User rights
'userrights-lookup-user'     => 'Atur gorombolan pamaké',
'userrights-user-editname'   => 'Asupkeun landihan:',
'editusergroup'              => 'Édit Golongan Pamaké',
'userrights-editusergroup'   => 'Édit gorombolan pamaké',
'saveusergroups'             => 'Simpen Grup Pamaké',
'userrights-groupsmember'    => 'Anggota ti:',
'userrights-groupsavailable' => 'Gorombolan nu aya:',

# Groups
'group'            => 'Gorombolan:',
'group-bot'        => 'Bot',
'group-sysop'      => 'Kuncén',
'group-bureaucrat' => 'Birokrat',
'group-all'        => '(sadayana)',

'group-bot-member'        => 'Bot',
'group-sysop-member'      => 'Kuncén',
'group-bureaucrat-member' => 'Birokrat',

'grouppage-bot'        => '{{ns:project}}:Bot',
'grouppage-sysop'      => '{{ns:project}}:Kuncén',
'grouppage-bureaucrat' => '{{ns:project}}:Birokrat',

# User rights log
'rightslog'      => 'Log hak pamaké',
'rightslogtext'  => 'Ieu mangrupa log parobahan hak-hak pamaké.',
'rightslogentry' => 'ngarobah kaanggotaan grup pikeun $1 tina $2 jadi $3',
'rightsnone'     => '(euweuh)',

# Recent changes
'nchanges'                          => '$1 parobahan',
'recentchanges'                     => 'Nu anyar robah',
'recentchangestext'                 => 'Lacak parobahan ka wiki panganyarna na kaca ieu.',
'rcnote'                            => "Di handap ieu '''$1''' parobahan ahir na '''$2''' poé ieu tina $3.",
'rcnotefrom'                        => 'Di handap ieu parobahan saprak <b>$2</b> (nu ditémbongkeun nepi ka <b>$1</b>).',
'rclistfrom'                        => 'Témbongkeun nu anyar robah nepi ka $1',
'rcshowhideminor'                   => '$1 éditan minor',
'rcshowhidebots'                    => '$1 bot',
'rcshowhideliu'                     => '$1 pamaké nu asup log',
'rcshowhideanons'                   => '$1 pamaké anonim',
'rcshowhidepatr'                    => '$1 éditan kapatroli',
'rcshowhidemine'                    => '$1 éditan kuring',
'rclinks'                           => 'Témbongkeun $1 parobahan ahir dina $2 poé ahir<br />$3',
'diff'                              => 'béda',
'hist'                              => 'juj',
'hide'                              => 'sumputkeun',
'show'                              => 'témbongkeun',
'minoreditletter'                   => 's',
'newpageletter'                     => 'A',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 pamaké nu ngawaskeun]',

# Recent changes linked
'recentchangeslinked' => 'Parobahan nu patali',

# Upload
'upload'                      => 'Muatkeun koropak',
'uploadbtn'                   => 'Muatkeun koropak',
'reupload'                    => 'Muat ulang',
'reuploaddesc'                => 'Balik ka formulir muatan.',
'uploadnologin'               => 'Can asup log',
'uploadnologintext'           => 'Anjeun kudu [[Special:Userlogin|asup log]] pikeun ngamuat koropak.',
'uploaderror'                 => 'Kasalahan muat',
'uploadtext'                  => "<strong>HEUP!</strong> Méméh anjeun ngamuat di dieu, pastikeun yén anjeun geus maca sarta tumut ka [[Special:Image_use_policy|kawijakan maké gambar]].
<p>Mun geus aya koropak na wiki nu ngaranna sarua jeung nu disebutkeun ku anjeun, koropak nu geus lila bakal diganti otomatis. Mangka, iwal ti pikeun ngaropéa hiji koropak, tangtu leuwih hadé mun anjeun mariksa heula bisi koropak nu sarupa geus aya.
<p>Pikeun némbongkeun atawa néang gambar-gambar nu pernah dimuat saméméhna, mangga lebet ka [[Special:Imagelist|daptar gambar nu dimuat]]. Muatan sarta hapusan kadaptar dina log [[{{ns:project}}:Upload_log|log muatan]].
</p><p>Paké formulir di handap pikeun ngamuat koropak gambar anyar pikeun ilustrasi kaca anjeun. Na kalolobaan panyungsi, anjeun bakal manggihan tombol \"Sungsi/''Browse''...\", nu bakal nganteur ka dialog muka-koropak nu baku na sistim operasi anjeun. Milih hiji koropak bakal ngeusian ngaran koropakna kana rohangan téks gigireun tombol nu tadi. Anjeun ogé kudu nyontréng kotak nu nandakeun yén anjeun teu ngarumpak hak cipta batur ku dimuatna ieu koropak. Pencét tombol \"Muatkeun/''Upload''\" pikeun ngeréngsékeun muatan. Prosés ieu bisa lila mun anjeun migunakeun sambungan internét nu lambat.
<p>Format nu dianjurkeun nyéta JPEG pikeun gambar fotografik, PNG pikeun hasil ngagambar sarta gambar séjénna, sarta OGG pikeun sora. Pilih ngaran koropak nu déskriptif sangkan teu ngalieurkeun. Pikeun ngasupkeun gambarna na kaca séjén, pigunakeun tumbu dina wujud <b>[[Image:file.jpg]]</b> atawa <b>[[Image:file.ogg]]</b> pikeun sora.
<p>Catet yén salaku kaca wiki, nu séjén bisa ngarobah atawa ngahapus muatan anjeun mun maranéhna nganggap ieu saluyu jeung kapentingan proyék, sarta anjeun bisa waé dipeungpeuk ti ngamuat koropak mun anjeun ngaruksak/ngaganggu sistim.",
'uploadlog'                   => 'log muatan',
'uploadlogpage'               => 'Log_muatan',
'uploadlogpagetext'           => 'Di handap mangrupa daptar muatan koropak nu panganyarna. Titimangsa nu katémbong dumasar titimangsa server (UTC).
<ul>
</ul>',
'filename'                    => 'Ngaran koropak',
'filedesc'                    => 'Ringkesna',
'fileuploadsummary'           => 'Ringkesan:',
'filestatus'                  => 'Status hak cipta',
'filesource'                  => 'Sumber',
'uploadedfiles'               => 'Koropak nu geus dimuat',
'ignorewarning'               => 'Ulah diwaro, simpen baé koropakna.',
'minlength'                   => 'Ngaran gambar sahenteuna kudu tilu aksara.',
'illegalfilename'             => 'Ngaran koropak "$1" ngandung aksara nu teu diwenangkeun pikeun judul kaca. Mangga gentos ngaranna tur cobi muatkeun deui.',
'badfilename'                 => 'Ngaran gambar geus dirobah jadi "$1".',
'largefileserver'             => 'Ieu koropak badag teuing, ngaleuwihan wates nu diwenangkeun ku server.',
'emptyfile'                   => "Koropak nu dimuatkeun ku anjeun jigana kosong. Hal ieu bisa jadi alatan sarupaning ''typo'' na ngaran koropakna. Mangga parios deui yén anjeun leres-leres hoyong ngamuat koropak éta.",
'fileexists'                  => 'Koropak nu ngaranna kieu geus aya, mangga parios $1 mun anjeun teu yakin rék ngaganti.',
'fileexists-forbidden'        => 'Koropak nu ngaranna ieu geus aya; mangga balik deui sarta muatkeun koropakna maké ngaran nu béda. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => "Koropak nu ngaranna ieu geus aya dina gudang koropak babagi (''shared file repository''); mangga balik deui sarta muatkeun koropak ieu maké ngaran nu béda. [[Image:$1|thumb|center|$1]]",
'successfulupload'            => 'Ngamuat geus hasil',
'fileuploaded'                => 'Koropak "$1" geus réngsé dimuat. Tuturkeun tumbu ieu: $2 pikeun kaca dadaran sarta iber ngeunaan koropakna, kayaning ti mana asalna, dijieun iraha jeung ku saha, sarta nu séjénna nu anjeun nyaho.',
'uploadwarning'               => 'Pépéling ngamuat',
'savefile'                    => 'Simpen koropak',
'uploadedimage'               => 'ngamuat "[[$1]]"',
'uploaddisabled'              => 'Punten, ngamuat ayeuna ditumpurkeun.',
'uploadscripted'              => "Koropak ieu ngandung kode HTML atawa skrip nu bisa dibaca ngaco ku panyungsi ramat (''web browser'').",
'uploadvirus'                 => 'Koropakna ngandung virus! Katrangan: $1',
'sourcefilename'              => 'Ngaran koropak sumber',
'destfilename'                => 'Ngaran koropak tujuan',
'watchthisupload'             => 'Awaskeun kaca ieu',
'filewasdeleted'              => 'Ngaran koropak ieu geus di hapus. Anjeun kudu ningali ka $1 sa acan muatkeun koropak deui',

'nolicense'          => 'Taya nu dipilih',
'upload_source_file' => ' (koropak dina komputer salira)',

# Image list
'imagelist'                 => 'Daptar gambar',
'imagelisttext'             => 'Di handap ieu daptar $1 gambar nu disusun $2.',
'imagelistforuser'          => 'Ukur némbongkeun gambar nu dimuatkeun ku $1.',
'getimagelist'              => 'mulut béréndélan koropak',
'ilsubmit'                  => 'Sungsi',
'showlast'                  => 'Témbongkeun $1 gambar ahir dumasar $2.',
'byname'                    => 'dumasar ngaran',
'bydate'                    => 'dumasar titimangsa',
'bysize'                    => 'dumasar ukuran',
'imgdelete'                 => 'hap',
'imgdesc'                   => 'dad',
'imgfile'                   => 'koropak',
'imglegend'                 => 'Katerangan: (desc) = témbongkeun/édit dadaran gambar.',
'imghistory'                => 'Jujutan gambar',
'revertimg'                 => 'blk',
'deleteimg'                 => 'hap',
'deleteimgcompletely'       => 'Hapus sadaya révisi',
'imghistlegend'             => 'Katerangan: (kiw) = ieu salaku gambar kiwari, (hps) = hapus vérsi heubeul ieu, (blk) = balikkeun ka vérsi heubeul ieu.
<br /><i>Klik na titimangsa pikeun nempo gambar nu dimuat poé éta</i>.',
'imagelinks'                => 'Tumbu gambar',
'linkstoimage'              => 'Kaca nu numbu ka gambar ieu:',
'nolinkstoimage'            => 'Teu aya kaca nu numbu ka gambar ieu.',
'sharedupload'              => "<br clear=both>
{| align=center border=0 cellpadding=3 cellspacing=3 style=\"border: solid #aaa 1px; background: #f9f9f9; font-size: 100%;\"
|-
| [[Gambar:Commons-logo.svg|20px|Lambang Wikimedia Commons]]
|Koropak ieu asalna ti ''[[Commons:Tepas|Wikimedia Commons]]''. Tempo '''[[Commons:Image:{{PAGENAME}}|kaca dadaranana]]''' pikeun leuwih jéntré.
|}
<br />",
'shareduploadwiki'          => 'Mangga aos $1 pikeun émbaran leuwih jéntré.',
'shareduploadwiki-linktext' => 'kaca pedaran koropak',
'noimage'                   => 'Euweuh koropak nu ngaranna kitu, anjeun bisa $1.',
'noimage-linktext'          => 'muatkeun',
'uploadnewversion-linktext' => 'ngamuatkeun vérsi anyar koropak ieu',
'imagelist_date'            => 'Titimangsa',
'imagelist_name'            => 'Ngaran',
'imagelist_user'            => 'Pamaké',
'imagelist_size'            => 'Badagna',
'imagelist_description'     => 'Pedaran',
'imagelist_search_for'      => 'Susud ngaran gambar:',

# MIME search
'mimesearch'         => 'Sungsi MIME',
'mimesearch-summary' => 'Ieu kaca bisa dipaké nyaring koropak dumasar tipeu MIME-na. Asupan: contenttype/subtype, contona <tt>image/jpeg</tt>.',
'mimetype'           => 'Tipeu MIME:',
'download'           => 'pulut',

# Unwatched pages
'unwatchedpages' => 'Kaca nu teu diawaskeun',

# List redirects
'listredirects' => 'Daptar alihan',

# Unused templates
'unusedtemplates'    => 'Citakan nu teu kapaké',
'unusedtemplateswlh' => 'tumbu lianna',

# Random redirect
'randomredirect' => 'Alihan acak',

# Statistics
'statistics'    => 'Statistik',
'sitestats'     => 'Statistika {{SITENAME}}',
'userstats'     => 'Statistik pamaké',
'sitestatstext' => "Jumlah-jamléh aya '''\$1''' kaca dina pangkalan data, kaasup kaca \"obrolan\", kaca-kaca ngeunaan MédiaWiki, kaca \"tukung\", alihan, sarta nu séjénna nu meureun teu kaasup artikel.  Lian ti nu éta, aya '''\$2''' kaca nu dianggap artikel nu bener.

'''\$8''' koropak geus dimuat.

Jumlah-jamléh geus aya '''\$3''' kaca ulasan sarta '''\$4''' éditan ti saprak Wiki ieu ngadeg. Jadi hartina aya rata-rata '''\$5''' éditan per kaca sarta '''\$6''' ulasan per édit.

[http://meta.wikimedia.org/wiki/Help:Job_queue Antrian job] adalah sebanyak '''\$7'''.",
'userstatstext' => "Aya '''$1''' pamaké nu kadaptar.
'''$2''' ($4) di antarana $5.",

'disambiguations'     => 'Kaca disambiguasi',
'disambiguationspage' => 'Project:Tumbu_ka_kaca_disambiguasi',

'doubleredirects'     => 'Alihan ganda',
'doubleredirectstext' => 'Unggal baris ngandung tumbu ka pangalihan kahiji jeung kadua, kitu ogé téks dina baris kahiji pangalihan kadua, nu biasana méré kaca tujuan nu bener, nu sakuduna ditujul dina pangalihan kahiji.',

'brokenredirects'        => 'Alihan buntu',
'brokenredirectstext'    => 'Alihan di handap numbu ka kaca nu teu aya.',
'brokenredirects-edit'   => '(édit)',
'brokenredirects-delete' => '(hapus)',

'withoutinterwiki'        => 'Kaca-kaca tanpa tumbu basa',
'withoutinterwiki-header' => 'Kaca-kaca di handap ieu teu numbu ka vérsi basa séjén:',

'fewestrevisions' => 'Artikel nu pangjarangna dirévisi',

# Miscellaneous special pages
'nbytes'                  => '$1 bait',
'ncategories'             => '$1 kategori',
'nlinks'                  => '$1 tumbu',
'nmembers'                => '$1 {{PLURAL:$1|pamaké|pamaké}}',
'nrevisions'              => '$1 révisi',
'nviews'                  => '$1 témbongan',
'specialpage-empty'       => 'Kaca ieu kosong.',
'lonelypages'             => 'Kaca-kaca nunggelis',
'lonelypagestext'         => 'Teu aya kaca di ieu wiki nu numbu ka kaca-kaca di handap ieu:',
'uncategorizedpages'      => 'Kaca nu can dikategorikeun',
'uncategorizedcategories' => 'Kategori nu can dikategorikeun',
'uncategorizedimages'     => 'Gambar nu can dikategorikeun',
'unusedcategories'        => 'Kategori nu teu kapaké',
'unusedimages'            => 'Gambar-gambar nu teu kapaké',
'popularpages'            => 'Kaca-kaca kawentar',
'wantedcategories'        => 'Kategori nu dipikabutuh',
'wantedpages'             => 'Kaca nu dipikabutuh',
'mostlinked'              => 'Nu panglobana numbu ka kaca séjén',
'mostlinkedcategories'    => 'Paling loba ditumbukeun ka kategori',
'mostcategories'          => 'Artikel nu paling loba ngandung kategori',
'mostimages'              => 'Nu panglobana numbu ka gambar',
'mostrevisions'           => 'Artikel nu pangmindengna dirévisi',
'allpages'                => 'Sadaya kaca',
'randompage'              => 'Kaca acak',
'shortpages'              => 'Kaca-kaca parondok',
'longpages'               => 'Kaca-kaca paranjang',
'deadendpages'            => 'Kaca buntu',
'deadendpagestext'        => 'Kaca-kaca di handap ieu teu numbu ka kaca séjén di ieu wiki:',
'protectedpages'          => 'Kaca-kaca nu dikonci',
'protectedpagestext'      => 'Kaca-kaca di handap ieu teu bisa dialihkeun atawa diédit',
'protectedpagesempty'     => 'Dina danget ieu, teu aya kaca nu dikonci dumasar kana ieu paraméter.',
'listusers'               => 'Daptar pamaké',
'specialpages'            => 'Kaca husus',
'spheading'               => 'Kaca husus pikeun sadaya pamaké',
'restrictedpheading'      => 'Kaca husus nu diwates',
'rclsub'                  => '(ka kaca nu numbu ti "$1")',
'newpages'                => 'Kaca anyar',
'newpages-username'       => 'Landihan:',
'ancientpages'            => 'Kaca pangheubeulna',
'intl'                    => 'Tumbu antarbasa',
'move'                    => 'Pindahkeun',
'movethispage'            => 'Pindahkeun kaca ieu',
'unusedimagestext'        => '<p>Perhatikeun yén jalaloka séjén bisa numbukeun ka hiji gambar ku URL langsung, sahingga masih didaptarkeun di dieu najan sabenerna dipaké.',
'unusedcategoriestext'    => 'Kaca kategori di handap ieu aya, tapi taya artikel nu diasupkeun kana kategori ieu.',

# Book sources
'booksources'               => 'Sumber buku',
'booksources-search-legend' => 'Sungsi sumber buku',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'Jung',
'booksources-text'          => 'Di handap ieu ngabéréndélkeun tumbu ka loka-loka nu ngical buku, boh nu anyar atawa loakan, nu sugan uninga kana buku anu nuju dipilari:',

'categoriespagetext' => 'Kategori-kategori di handap ieu aya na wiki.',
'data'               => 'Data',
'userrights'         => 'Manajemén hak pamaké',
'groups'             => 'Gorombolan pamaké',
'isbn'               => 'ISBN',
'alphaindexline'     => '$1 ka $2',
'version'            => 'Vérsi',

# Special:Log
'specialloguserlabel'  => 'Pamaké:',
'speciallogtitlelabel' => 'Judul:',
'log'                  => 'Log',
'log-search-submit'    => 'Jung',
'alllogstext'          => 'Témbongan gabungan log muatan, hapusan, koncian, peungpeukan, jeung kuncén. Bisa dipondokkeun ku cara milih tipe log, ngaran pamaké, atawa kaca nu dimaksud.',
'logempty'             => 'Taya item nu cocog dina log.',
'log-title-wildcard'   => 'Téangan judul nu dimimitian ku tulisan ieu',

# Special:Allpages
'nextpage'          => 'Kaca salajengna ($1)',
'prevpage'          => 'Kaca saméméhna ($1)',
'allpagesfrom'      => 'Pintonkeun kaca ti mimiti:',
'allarticles'       => 'Sadaya artikel',
'allinnamespace'    => 'Sadaya kaca ($1 ngaranspasi)',
'allnotinnamespace' => 'Sadaya kaca (teu na $1 ngaranspasi)',
'allpagesprev'      => 'Saméméhna',
'allpagesnext'      => 'Salajengna',
'allpagessubmit'    => 'Jung',
'allpagesprefix'    => 'Pintonkeun kaca dimimitian ku:',
'allpagesbadtitle'  => 'Judul kaca nu dibikeun teu bener atawa mibanda awalan antarbasa atawa antarwiki, nu ngandung karakter nu teu bisa dipaké dina judul.',

# Special:Listusers
'listusersfrom'      => 'Témbongkeun pamaké nu dimimitian ku',
'listusers-submit'   => 'Témbongkeun',
'listusers-noresult' => 'Teu kapendak.',

# E-mail user
'mailnologin'     => 'Euweuh alamat ngirim',
'mailnologintext' => "Anjeun kudu '''[[{{ns:special}}:Userlogin|asup log]]''' sarta boga alamat surélék nu sah na [[{{ns:special}}:Preferences|préferénsi]] anjeun sangkan bisa nyurélékan pamaké séjén.",
'emailuser'       => 'Surélékan pamaké ieu',
'emailpage'       => 'Surélékan pamaké',
'emailpagetext'   => 'Mun pamaké ieu ngasupkeun alamat surélék nu sah na préferénsi pamakéna, formulir di handap bakal ngirimkeun hiji surat. Alamat surélék nu ku anjeun diasupkeun kana préferénsi pamaké anjeun bakal katémbong salaku alamat "Ti" surélékna, sahingga nu dituju bisa males.',
'defemailsubject' => 'Surélék {{SITENAME}}',
'noemailtitle'    => 'Teu aya alamat surélék',
'noemailtext'     => 'Pamaké ieu teu méré alamat surélék nu sah atawa milih teu narima surélék ti pamaké séjén.',
'emailfrom'       => 'Ti',
'emailto'         => 'Ka',
'emailsubject'    => 'Ngeunaan',
'emailmessage'    => 'Surat',
'emailsend'       => 'Kirim',
'emailccme'       => 'Tembuskeun surat kuring kana surélék.',
'emailccsubject'  => 'Tembusan surat anjeun keur $1: $2',
'emailsent'       => 'Surélék geus dikirim',
'emailsenttext'   => 'Surélék anjeun geus dikirim.',

# Watchlist
'watchlist'            => 'Awaskeuneun',
'mywatchlist'          => 'Awaskeuneun',
'watchlistfor'         => "(keur '''$1''')",
'nowatchlist'          => 'Anjeun teu boga awaskeuneun.',
'watchlistcount'       => "'''Anjeun boga $1 awaskeuneun, kaasup kaca obrolan.'''",
'clearwatchlist'       => 'Kosongkeun daptar awaskeuneun',
'watchlistclearbutton' => 'Kosongkeun daptar awaskeuneun',
'watchnologin'         => 'Can asup log',
'watchnologintext'     => 'Anjeun kudu [[Special:Userlogin|asup log]] pikeun ngarobah awaskeuneun.',
'addedwatch'           => 'Geus ditambahkeun ka awaskeuneun',
'addedwatchtext'       => "Kaca \"\$1\" geus ditambahkeun ka [[Special:Watchlist|awaskeuneun]] anjeun.
Jaga, parobahan na kaca ieu katut kaca obrolanana bakal dibéréndélkeun di dinya, sarta kacana bakal katémbong '''dikandelan''' dina kaca [[Special:Recentchanges|Nu anyar robah]] sangkan leuwih gampang ngawaskeunana.

<p>Mun jaga anjeun moal deui ngawaskeun parobahan na kaca éta, klik tumbu \"Eureun ngawaskeun\" na lajursisi.",
'removedwatch'         => 'Dikaluarkeun ti awaskeuneun',
'removedwatchtext'     => 'Kaca "$1" geus dikaluarkeun ti awaskeuneun anjeun.',
'watch'                => 'awaskeun',
'watchthispage'        => 'Awaskeun kaca ieu',
'unwatch'              => 'Eureun ngawaskeun',
'unwatchthispage'      => 'Eureun ngawaskeun',
'notanarticle'         => 'Sanés kaca eusi',
'watchnochange'        => 'Sadaya awaseun anjeun taya nu diédit dina jangka wanci nu ditémbongkeun.',
'watchdetails'         => 'Aya $1 kaca nu ku anjeun diawaskeun (teu kaasup kaca obrolan/sawala); anjeun bisa [[Husus:Watchlist/edit|némbongkeun sarta ngédit béréndélan lengkepna]].',
'wlheader-enotif'      => '* Pangémbar surélék difungsikeun.',
'wlheader-showupdated' => "* Kaca nu robah ti panungtungan anjeun sindang ditémbongkeun kalawan '''kandel'''",
'watchmethod-recent'   => 'mariksa nu anyar robah na kaca nu diawaskeun',
'watchmethod-list'     => 'mariksa nu anyar robah na kaca nu diawaskeun',
'removechecked'        => 'Kaluarkeun nu dicontang tina awaskeuneun',
'watchlistcontains'    => 'Anjeun ngawaskeun $1 kaca.',
'watcheditlist'        => "Ieu daptar nurutkeun abjad kaca-kaca awaskeuneun anjeun. Contréng kotak kaca nu teu moal deui diawaskeun, teras klik tombol 'piceun nu dicontréng' na dadasar layar.",
'removingchecked'      => 'Ngaluarkeun kaca nu dipilih tina awaskeuneun...',
'couldntremove'        => "Teu bisa ngahapus '$1'...",
'iteminvalidname'      => "Masalah dina '$1', ngaran teu bener...",
'wlnote'               => 'Di handap ieu mangrupa $1 robahan ahir salila <b>$2</b> jam.',
'wlshowlast'           => 'Témbongkeun $1 jam $2 poé $3 ahir',
'wlsaved'              => 'Ieu mangrupa vérsi simpenan awaskeuneun anjeun.',
'watchlist-show-bots'  => 'Témbongkeun éditan bot',
'watchlist-hide-bots'  => 'Sumputkeun éditan bot',
'watchlist-show-own'   => 'Témbongkeun éditan kuring',
'watchlist-hide-own'   => 'Sumputkeun éditan kuring',
'watchlist-show-minor' => 'Témbongkeun éditan leutik',
'watchlist-hide-minor' => 'Sumputkeun éditan leutik',
'wldone'               => 'Anggeus.',

# Displayed when you click the "watch" button and it's in the process of watching
'watching' => 'Ngawaskeun...',

'enotif_mailer'      => 'Surat Émbaran {{SITENAME}}',
'enotif_reset'       => 'Tandaan sadaya kaca nu geus dilongok',
'enotif_newpagetext' => 'Kaca ieu anyar.',
'changed'            => 'geus robah',
'created'            => 'geus dijieun',
'enotif_subject'     => 'Kaca $PAGETITLE {{SITENAME}} geus $CHANGEDORCREATED ku $PAGEEDITOR',
'enotif_lastvisited' => 'Tempo $1 pikeun sadaya parobahan ti saprak anjeun ninggalkeun ieu kaca.',
'enotif_body'        => 'Sadérék $WATCHINGUSERNAME,

Kaca $PAGETITLE na {{SITENAME}} geus $CHANGEDORCREATED tanggal $PAGEEDITDATE ku $PAGEEDITOR. Mangga tingal {{SERVER}}{{localurl:$PAGETITLE_RAWURL}} pikeun vérsi kiwari.

$NEWPAGE

Ringkesan éditor: $PAGESUMMARY $PAGEMINOREDIT

Kontak éditor:
surat {{SERVER}}{{localurl:Husus:Emailuser|target=$PAGEEDITOR_RAWURL}}
wiki {{SERVER}}{{localurl:Pamaké:$PAGEEDITOR_RAWURL}}

Mun anjeun teu sindang deui ka ieu kaca, parobahan salajengna moal diémbarkeun. Anjeun bisa ogé nyetél deui umbul-umbul pikeun sadaya kaca nu aya na daptar awaseun anjeun.

             Sistim émbaran {{SITENAME}} pikeun anjeun

--
Pikeun ngarobah setélan dabtar awaseun anjeun, sindang ka {{SERVER}}{{localurl:Husus:Watchlist|edit=yes}}

Asupan jeung bantuan salajengna:
{{fullurl:{{MediaWiki:helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Hapus kaca',
'confirm'                     => 'Konfirmasi',
'excontent'                   => "eusina nu heubeul: '$1'",
'excontentauthor'             => "eusina: '$1' (nu dikontribusi ku '$2' wungkul)",
'exbeforeblank'               => "eusi méméh dikosongkeun nyéta: '$1'",
'exblank'                     => 'kaca ieu kosong',
'confirmdelete'               => 'Konfirmasi ngahapus',
'deletesub'                   => '(Ngahapus "$1")',
'historywarning'              => 'Perhatosan: Kaca nu rék dihapus mibanda',
'confirmdeletetext'           => 'Anjeun rék ngahapus hiji kaca atawa gambar katut jujutanana tina pangkalan data, mangga yakinkeun yén anjeun mémang niat midamel ieu, yén anjeun ngartos kana sagala konsékuénsina, sarta yén anjeun ngalakukeun ieu saluyu jeung [[{{MediaWiki:policy-url}}|kawijakan {{SITENAME}}]].',
'actioncomplete'              => 'Peta geus réngsé',
'deletedtext'                 => '"$1" geus dihapus. Tempo $2 pikeun rékaman hapusan anyaran ieu.',
'deletedarticle'              => 'ngahapus "$1"',
'dellogpage'                  => 'Log_hapusan',
'dellogpagetext'              => 'Di handap ieu daptar hapusan nu ahir-ahir, sakabéh wanci dumasar wanci server (UTC).
<ul>
</ul>',
'deletionlog'                 => 'log hapusan',
'reverted'                    => 'Malikkeun ka révisi nu ti heula',
'deletecomment'               => 'Alesan ngahapus',
'imagereverted'               => 'Malikkeun deui ka vérsi nu saméméhna geus réngsé.',
'rollback'                    => 'Balikkeun éditan',
'rollback_short'              => 'Balikkeun',
'rollbacklink'                => 'balikkeun',
'rollbackfailed'              => 'Gagal malikkeun',
'cantrollback'                => 'Éditan teu bisa dibalikkeun; kontribusi panungtung ngarupakeun hiji-hijina panulis kaca ieu.',
'alreadyrolled'               => 'Teu bisa mulangkeun édit ahir [[$1]] ku [[User:$2|$2]] ([[User talk:$2|Obrolan]]); geus aya nu ngédit atawa mulangkeun kacana.

Édit ahir ku [[User:$3|$3]] ([[User talk:$3|Obrolan]]).',
'editcomment'                 => 'Komentar ngéditna: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'Malikkeun éditan $2, diganti deui ka vérsi ahir ku $1',
'sessionfailure'              => 'Sigana aya masalah jeung termin log anjeun; peta ieu geus dibolaykeun salaku pépéling pikeun ngalawan ayana pangbajak. Mangga pencét "back" jeung muat ulang ti kaca asal anjeun, lajeng cobaan deui.',
'protectlogpage'              => 'Log_koncian',
'protectlogtext'              => 'Di handap ieu mangrupa daptar koncian kaca. Tempo [[{{ns:project}}:Kaca nu dikonci|kaca nu dikonci]] pikeun iber leuwih lengkep.',
'protectedarticle'            => 'ngonci $1',
'unprotectedarticle'          => 'muka konci $1',
'protectsub'                  => '(Ngonci "$1")',
'confirmprotect'              => 'Konfirmasi ngonci',
'protectcomment'              => 'Alesan ngonci',
'protectexpiry'               => 'Kadaluwarsa',
'protect_expiry_invalid'      => 'Waktu kadaluwarsa teu sah.',
'unprotectsub'                => '(Muka konci "$1")',
'protect-unchain'             => 'Buka konci pamindahan',
'protect-level-autoconfirmed' => 'Peungpeuk pamaké nu teu daptar',
'protect-level-sysop'         => 'Ngan bisa ku kuncén',
'protect-expiring'            => 'kadaluwarsa $1',
'restriction-type'            => 'Ngonci:',

# Restrictions (nouns)
'restriction-edit' => 'Édit',
'restriction-move' => 'Pindahkeun',

# Undelete
'undelete'               => 'Simpen deui kaca nu dihapus',
'undeletepage'           => 'Témbongkeun atawa simpen deui kaca nu geus dihapus',
'viewdeletedpage'        => 'Témbongkeun kaca nu dihapus',
'undeletepagetext'       => 'Kaca di handap ieu geus dihapus tapi masih kénéh aya na arsip sarta bisa disimpen deui. Arsip aya kalana dibersihan.',
'undeleterevisions'      => '$1 révisi diarsipkeun',
'undeletehistory'        => 'Mun anjeun nyimpen deui kacana, sadaya révisi bakal disimpen deui dina jujutan. Mun aya kaca anyar nu ngaranna sarua dijieun deui satutasna dihapus, révisi nu disimpen tadi bakal némbongan salaku jujutan nu ti heula, sarta révisi kiwari kaca nu hirup moal otomatis kaganti.',
'undeletehistorynoadmin' => 'Artikel ieu geus dihapus. Alesanana bisa dibaca dina katrangan di handap, katut saha waé nu geus ngédit ieu artikel saméméh dihapus. Téks aktual révisi nu geus dihapus ieu ngan bisa dibuka ku [[{{ns:project}}:Kuncén|kuncén]].',
'undeletebtn'            => 'Simpen deui!',
'undeletedarticle'       => 'disimpen "$1"',
'undeletedrevisions'     => '$1 révisi disimpen deui',
'undeletedfiles'         => '$1 koropak dibalikeun',
'undelete-search-box'    => 'Téang kaca nu dihapus',
'undelete-search-submit' => 'Téang',

# Namespace form on various pages
'namespace' => 'Ngaranspasi:',
'invert'    => 'Balikkeun pilihan',

# Contributions
'contributions' => 'Tulisan pamaké',
'mycontris'     => 'Tulisan kuring',
'contribsub2'   => 'Pikeun $1 ($2)',
'nocontribs'    => 'Taya robahan nu kapanggih cocog jeung patokan ieu.',
'ucnote'        => 'Di handap ieu mangrupa parobahan ahir <b>$1</b> pamaké salila <b>$2</b> poé ahir.',
'uclinks'       => 'Témbongkeun $1 parobahan ahir; témbongkeun $2 poé ahir.',
'uctop'         => ' (tempo)',

'sp-contributions-newest'      => 'Panganyarna',
'sp-contributions-oldest'      => 'Pangheubeulna',
'sp-contributions-newer'       => 'Leuwih anyar $1',
'sp-contributions-older'       => 'Leuwih heubeul $1',
'sp-contributions-newbies'     => 'Témbongkeun kontribusi ti rekening anyar',
'sp-contributions-newbies-sub' => 'Pikeun rekening anyar',
'sp-contributions-blocklog'    => 'Log peungpeuk',
'sp-contributions-search'      => 'Téang kontribusi',
'sp-contributions-username'    => 'Alamat IP atawa landihan:',
'sp-contributions-submit'      => 'Téang',

'sp-newimages-showfrom' => 'Témbongkeun gambar anyar ti $1',

# What links here
'whatlinkshere'      => 'Nu numbu ka dieu',
'notargettitle'      => 'Taya tujuleun',
'notargettext'       => 'Anjeun can nangtukeun hiji targét atawa pamaké pikeun migawé sangkan fungsi ieu jalan.',
'linklistsub'        => '(Daptar tumbu)',
'linkshere'          => "Kaca di handap ieu numbu ka '''[[:$1]]''':",
'nolinkshere'        => "Euweuh kaca nu numbu ka '''[[:$1]]'''.",
'nolinkshere-ns'     => "Euweuh kaca nu numbu ka '''[[:$1]]''' dina namespace nu dipilih.",
'isredirect'         => 'Kaca alihan',
'whatlinkshere-prev' => '$1 saméméhna',
'whatlinkshere-next' => '$1 salajengna',

# Block/unblock
'blockip'                     => 'Peungpeuk pamaké',
'blockiptext'                 => 'Paké formulir di handap pikeun meungpeuk aksés nulis ti alamat IP atawa ngaran pamaké husus. Ieu sakuduna ditujukeun pikeun nyegah vandalisme, sarta saluyu jeung [[{{MediaWiki:policy-url}}|kawijakan]]. Eusi alesan nu jéntré (misal, ngarujuk kaca tinangtu nu geus diruksak).',
'ipaddress'                   => 'Alamat IP/ngaran pamaké',
'ipadressorusername'          => 'Alamat IP atawa ngaran pamaké',
'ipbexpiry'                   => 'Kadaluarsa',
'ipbreason'                   => 'Alesan',
'ipbreasonotherlist'          => 'Alesan séjén',
'ipbanononly'                 => 'Ngan dipeungpeuk pamake teu daptar',
'ipbcreateaccount'            => 'Tong bisa nyieun rekening',
'ipbsubmit'                   => 'Peungpeuk pamaké ieu',
'ipbother'                    => 'Waktu séjén',
'ipboptions'                  => '2 jam:2 hours,sapoé:1 day,3 poé:3 days,saminggu:1 week,2 minggu:2 weeks,sabulan:1 month,3 bulan:3 months,6 bulan:6 months,sataun:1 year,tanpa wates:infinite',
'ipbotheroption'              => 'séjénna',
'ipbotherreason'              => 'Alesan séjén/tambahan',
'badipaddress'                => 'Alamat IP teu sah',
'blockipsuccesssub'           => 'Meungpeuk geus hasil',
'blockipsuccesstext'          => '"$1" dipeungpeuk.
<br />Tempo [[Special:Ipblocklist|daptar peungpeuk IP]] pikeun nempoan deui peungpeuk.',
'ipb-blocklist-addr'          => 'Tempo peungpeuk nu diteurapkeun keur $1',
'ipb-blocklist'               => 'Tempo peungpeuk nu diteurapkeun',
'unblockip'                   => 'Buka peungpeuk pamaké',
'unblockiptext'               => 'Paké formulir di handap pikeun mulangkeun aksés nulis ka alamat IP atawa ngaran pamaké nu saméméhna dipeungpeuk.',
'ipusubmit'                   => 'Buka peungpeuk pikeun pamaké ieu',
'unblocked'                   => 'peungpeuk ka [[User:$1|$1]] geus dicabut',
'unblocked-id'                => 'peungpeuk $1 geus dicabut',
'ipblocklist'                 => 'Daptar alamat IP jeung ngaran pamaké nu dipeungpeuk',
'ipblocklist-submit'          => 'Téang',
'blocklistline'               => '$1, $2 dipeungpeuk $3 (kadaluwarsa $4)',
'infiniteblock'               => 'tanpa wates',
'expiringblock'               => 'kadaluwarsa $1',
'anononlyblock'               => 'ngan nu teu daptar',
'createaccountblock'          => 'nyieun rekening dipeungpeuk',
'blocklink'                   => 'peungpeuk',
'unblocklink'                 => 'buka peungpeuk',
'contribslink'                => 'kontribusi',
'autoblocker'                 => 'Otomatis dipeungpeuk sabab alamat IP anjeun sarua jeung "$1". Alesan "$2".',
'blocklogpage'                => 'Log_peungpeuk',
'blocklogentry'               => 'meungpeuk "$1" nepi ka $2 $3',
'blocklogtext'                => 'Ieu mangrupa log peta meungpeuk jeung muka peungpeuk pamaké, teu kaasup alamat IP nu dipeungpeukna otomatis. Tempo [[Special:Ipblocklist|daptar peungpeuk IP]] pikeun daptar cegahan jeung peungpeuk.',
'unblocklogentry'             => 'peungpeuk dibuka "$1"',
'block-log-flags-anononly'    => 'pamaké anonim wungkul',
'block-log-flags-nocreate'    => 'Nyieun rekening ditumpurkeun',
'block-log-flags-noautoblock' => 'meungpeuk otomatis dipaéhan',
'range_block_disabled'        => 'Pangabisa kuncén pikeun nyieun sarupaning peungpeuk geus ditumpurkeun.',
'ipb_expiry_invalid'          => 'Wanci daluwarsa teu bener.',
'ipb_already_blocked'         => '"$1" geus dipeungpeuk',
'ip_range_invalid'            => 'Angka IP teu bener.',
'proxyblocker'                => 'Pameungpeuk proxy',
'proxyblockreason'            => "Alamat IP anjeun dipeungpeuk sabab mangrupa proxy muka. Mangga tepungan ''Internet service provider'' atanapi ''tech support'' anjeun, béjakeun masalah serius ieu.",
'proxyblocksuccess'           => 'Réngsé.',
'sorbsreason'                 => "Alamat IP anjeun kadaptar salaku ''open proxy'' dina [http://www.sorbs.net SORBS] DNSBL.",
'sorbs_create_account_reason' => "Alamat IP anjeun kadaptar salaku ''open proxy'' dina [http://www.sorbs.net SORBS] DNSBL. Anjeun teu bisa nyieun rekening",

# Developer tools
'lockdb'              => 'Konci pangkalan data',
'unlockdb'            => 'Buka konci pangkalan data',
'lockdbtext'          => 'Ngonci gudang data bakal numpurkeun kabisa sakabéh pamaké pikeun ngédit kaca, ngarobah préferénsina, ngédit awaskeuneunana, sarta hal séjén nu merlukeun parobahan na gudang data. Konfirmasikeun yén ieu nu dimaksud ku anjeun, sarta anjeun bakal muka konci gudang data nalika pangropéa anjeun geus réngsé.',
'unlockdbtext'        => 'Muka konci pangkalan data bakal mulangkeun kabisa sakabéh pamaké pikeun ngédit kaca, ngarobah préferénsina, ngédit awaskeuneunana, sarta hal-hal séjén nu merlukeun parobahan na pangkalan data. Pastikeun yén ieu ngarupakeun hal nu diniatkeun ku anjeun.',
'lockconfirm'         => 'Leres pisan, simkuring hoyong ngonci pangkalan data.',
'unlockconfirm'       => 'Muhun, kuring hayang muka konci pangkalan data.',
'lockbtn'             => 'Konci pangkalan data',
'unlockbtn'           => 'Buka konci pangkalan data',
'locknoconfirm'       => 'Anjeun teu nyontréngan kotak konfirmasi.',
'lockdbsuccesssub'    => 'pangkalan data geus hasil dikonci',
'unlockdbsuccesssub'  => 'Konci pangkalan data geus dibuka',
'lockdbsuccesstext'   => 'pangkalan data dikonci.
<br />Ulah poho muka konci mun geus bérés diropéa.',
'unlockdbsuccesstext' => 'pangkalan data geus teu dikonci.',

# Move page
'movepage'                => 'Pindahkeun kaca',
'movepagetext'            => "Migunakeun formulir di handap bakal ngaganti ngaran hiji kaca, mindahkeun sadaya jujutanana ka ngaran anyar.
Judul nu heubeul bakal jadi kaca alihan ka judul nu anyar.
Tumbu ka judul kaca nu heubeul mola robah; pastikeun yén anjeun [[Special:Maintenance|marios]] alihan ganda atawa alihan nu buntu.
Anjeun tanggel waler pikeun mastikeun yén tumbu-tumbu tetep nujul ka tempat nu sakuduna dituju.

Catet yén kacana '''moal''' pindah mun geus aya kaca na judul nu anyar, iwal mun kosong atawa mangrupa alihan sarta teu mibanda jujutan éditan heubeul. Ieu ngandung harti yén anjeun bisa ngaganti ngaran hiji kaca balik deui ka nu cikénéh diganti ngaranna mun anjeun nyieun kasalahan, sarta anjeun teu bisa  nimpah kaca nu geus aya.

<b>AWAS!</b> This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding.",
'movepagetalktext'        => "Kaca obrolan nu patali, mun aya, bakal sacara otomatis kapindahkeun, '''iwal:'''
*Anjeun mindahkeun kacana meuntas spasingaran nu béda,
*Kaca obrolan dina ngaran nu anyar geus aya eusian, atawa
*Anjeun teu nyontréngan kotak di handap.

Dina kajadian kitu, mun hayang (jeung perlu) anjeun kudu mindahkeun atawa ngagabungkeun kacana sacara manual.",
'movearticle'             => 'Pindahkeun kaca',
'movenologin'             => 'Can asup log',
'movenologintext'         => 'Anjeun kudu jadi pamaké nu kadaptar tur [[Special:Userlogin|asup log]] pikeun mindahkeun kaca.',
'newtitle'                => 'Ka judul anyar',
'move-watch'              => 'Awaskeuneun kaca ieu',
'movepagebtn'             => 'Pindahkeun kaca',
'pagemovedsub'            => 'Mindahkeun geus hasil!',
'pagemovedtext'           => 'Kaca "[[$1]]" dipindahkeun ka "[[$2]]".',
'articleexists'           => 'Kaca nu ngaranna kitu geus aya, atawa ngaran nu dipilih ku anjeun teu sah. Mangga pilih ngaran séjén.',
'talkexists'              => 'Kacana geus hasil dipindahkeun, ngan kaca obrolanana teu bisa dipindahkeun sabab geus aya nu anyar na judul anyar. Mangga gabungkeun sacara manual.',
'movedto'                 => 'dipindahkeun ka',
'movetalk'                => 'Mun bisa, kaca "obrolan" ogé pindahkeun.',
'talkpagemoved'           => 'Kaca obrolanana geus ogé dipindahkeun.',
'talkpagenotmoved'        => 'Kaca obrolan nu patali <strong>teu</strong> dipindahkeun.',
'1movedto2'               => 'mindahkeun [[$1]] ka [[$2]]',
'1movedto2_redir'         => '[[$1]] dipindahkeun ka [[$2]]',
'movelogpage'             => 'Log mindahkeun',
'movelogpagetext'         => 'Di handap ieu béréndélan kaca nu dipindahkeun.',
'movereason'              => 'Alesan',
'delete_and_move'         => 'Hapus jeung pindahkeun',
'delete_and_move_text'    => '==Merlukeun hapusan==

Artikel nu dituju "[[$1]]" geus aya. Badé dihapus baé sangkan bisa mindahkeun?',
'delete_and_move_confirm' => 'Enya, hapus kaca éta',
'delete_and_move_reason'  => 'Hapus sangkan bisa mindahkeun',
'selfmove'                => 'Judul sumber jeung tujuanana sarua, lain gé mindahkeun atuh!',
'immobile_namespace'      => 'Judul nu dituju kaasup kana tipe husus, teu bisa mindahkeun kaca ka ngaranspasi kitu.',

# Export
'export'            => 'Ékspor kaca',
'exporttext'        => 'Anjeun bisa ngékspor téks sarta jujutan éditan ti kaca tinangtu atawa ti sababaraha kaca nu ngagunduk na sababaraha XML; ieu salajengna tiasa diimpor ka wiki séjén nu ngajalankeun software MediaWiki, ditransformasikeun, atawa ukur disimpen pikeun kaperluan anjeun pribadi.',
'exportcuronly'     => 'Asupkeun ukur révisi kiwari, teu sakabéh jujutan',
'export-addcattext' => 'Tambahkeun kaca tina kategori:',
'export-addcat'     => 'Tambahkeun',

# Namespace 8 related
'allmessages'               => 'Sadaya pesen sistim',
'allmessagesname'           => 'Ngaran',
'allmessagesdefault'        => 'Téks ti dituna',
'allmessagescurrent'        => 'Téks kiwari',
'allmessagestext'           => 'Ieu mangrupa daptar talatah sistim nu aya na spasi ngaran MediaWiki:.',
'allmessagesnotsupportedUI' => 'Basa antarbeungeut anjeun kiwari <b>$1</b> teu dirojong ku Special:AllMessages na loka ieu.',
'allmessagesnotsupportedDB' => 'Special:AllMessages teu dirojong sabab wgUseDatabaseMessages pareum.',
'allmessagesfilter'         => 'Saringan ngaran talatah:',
'allmessagesmodified'       => 'Témbongkeun ukur nu robah',

# Thumbnails
'thumbnail-more'  => 'Gedéan',
'missingimage'    => '<b>Gambar leungit</b><br /><i>$1</i>',
'filemissing'     => 'Koropak leungit',
'thumbnail_error' => 'Kasalahan sawaktu nyieun gambar leutik (thumbnail): $1',

# Special:Import
'import'                => 'Impor kaca',
'importinterwiki'       => 'Impor transwiki',
'importtext'            => 'Mangga ékspor koropakna ti sumber nu dipaké ku wiki migunakeun fungsi Special:Export, simpen na piringan anjeun, teras muatkeun di dieu.',
'importfailed'          => 'Ngimpor gagal: $1',
'importbadinterwiki'    => 'Tumbu interwiki rusak',
'importnotext'          => 'Kosong atawa teu aya téks',
'importsuccess'         => 'Ngimpor geus hasil!',
'importhistoryconflict' => 'Aya révisi jujutan nu béntrok (may have imported this page before)',

# Import log
'import-logentry-upload-detail' => '$1 vérsi heubeul',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Kaca pamaké kuring',
'tooltip-pt-anonuserpage'         => 'Kaca pamaké pikeun IP nu ku anjeun keur diédit',
'tooltip-pt-mytalk'               => 'Kaca obrolan kuring',
'tooltip-pt-anontalk'             => 'Sawala ngeunaan éditan ti alamat IP ieu',
'tooltip-pt-preferences'          => 'Préferénsi kuring',
'tooltip-pt-watchlist'            => 'Daptar kaca nu diawaskeun ku anjeun parobahanana.',
'tooltip-pt-mycontris'            => 'Daptar kontribusi kuring',
'tooltip-pt-login'                => 'Anjeun leuwih hadé asup log, sanajan teu wajib.',
'tooltip-pt-anonlogin'            => 'Anjeun leuwih hadé asup log, sanajan teu wajib.',
'tooltip-pt-logout'               => 'Kaluar log',
'tooltip-ca-talk'                 => 'Sawala ngeunaan eusi kaca',
'tooltip-ca-edit'                 => 'Anjeun bisa ngédit kaca ieu. Mangga pigunakeun tombol sawangan saméméh nyimpen.',
'tooltip-ca-addsection'           => 'Tambihan koméntar kana sawala ieu.',
'tooltip-ca-viewsource'           => 'Kaca ieu dikonci, tapi anjeun masih bisa muka sumberna.',
'tooltip-ca-history'              => 'Vérsi heubeul kaca ieu.',
'tooltip-ca-protect'              => 'Konci kaca ieu',
'tooltip-ca-delete'               => 'Hapus kaca ieu',
'tooltip-ca-undelete'             => 'Simpen deui éditan kaca ieu nu geus dijieun saméméh dihapus',
'tooltip-ca-move'                 => 'Pindahkeun kaca ieu',
'tooltip-ca-watch'                => 'Tambahkeun kaca ieu kana awaskeuneun kuring',
'tooltip-ca-unwatch'              => 'Kaluarkeun kaca ieu tina awaskeuneun kuring',
'tooltip-search'                  => 'Sungsi wiki ieu [alt-f]',
'tooltip-p-logo'                  => 'Tepas',
'tooltip-n-mainpage'              => 'Sindang ka Tepas',
'tooltip-n-portal'                => 'Ngeunaan proyékna, naon nu bisa dipigawé, di mana néangan naon',
'tooltip-n-currentevents'         => 'Panggihan iber ngeunaan naon baé nu keur lumangsung',
'tooltip-n-recentchanges'         => 'Daptar nu anyar robah na wiki.',
'tooltip-n-randompage'            => 'Muatkeun kaca naon baé',
'tooltip-n-help'                  => 'Tempat pikeun néangan.',
'tooltip-t-whatlinkshere'         => 'Daptar kaca-kaca wiki nu numbu ka dieu',
'tooltip-t-recentchangeslinked'   => 'Nu anyar robah na kaca-kaca nu numbu ka dieu',
'tooltip-feed-rss'                => 'Asupan RSS pikeun kaca ieu',
'tooltip-feed-atom'               => 'Asupan atom pikeun kaca ieu',
'tooltip-t-contributions'         => 'Témbongkeun béréndélan kontribusi pamaké ieu',
'tooltip-t-emailuser'             => 'Kirim surélék ka pamaké ieu',
'tooltip-t-upload'                => 'Muatkeun koropak gambar atawa média',
'tooltip-t-specialpages'          => 'Daptar sadaya kaca husus',
'tooltip-ca-nstab-main'           => 'Témbongkeun eusi kaca',
'tooltip-ca-nstab-user'           => 'Témbongkeun kaca pamaké',
'tooltip-ca-nstab-media'          => 'Témbongkeun kaca média',
'tooltip-ca-nstab-special'        => 'Ieu kaca husus, anjeun teu bisa ngédit ku sorangan.',
'tooltip-ca-nstab-project'        => 'Témbongkeun kaca proyék',
'tooltip-ca-nstab-image'          => 'Témbongkeun kaca gambar',
'tooltip-ca-nstab-mediawiki'      => 'Témbongkeun pesen sistim',
'tooltip-ca-nstab-template'       => 'Témbongkeun citakan',
'tooltip-ca-nstab-help'           => 'Témbongkeun kaca pitulung',
'tooltip-ca-nstab-category'       => 'Témbongkeun kaca kategori',
'tooltip-minoredit'               => 'Tandaan ieu salaku éditan minor [alt-i]',
'tooltip-save'                    => 'Simpen parobahan anjeun [alt-s]',
'tooltip-preview'                 => 'Sawang heula robahan anjeun, pami tos leres mangga simpen! [alt-p]',
'tooltip-diff'                    => 'Témbongkeun parobahan mana nu geus dijieun. [alt-d]',
'tooltip-compareselectedversions' => 'Tempo béda antara dua vérsi kaca ieu nu dipilih [alt-v].',
'tooltip-watch'                   => 'Tambahkeun kaca ieu kana awaskeuneun kuring [alt-w]',

# Stylesheets
'common.css'   => "/* CSS nu di angé ku kabeh ''skin'' */",
'monobook.css' => "/* édit koropak ieu pikeun nyaluyukeun kulit ''monobook'' pikeun sakabéh situs */",

# Metadata
'notacceptable' => "''Server'' wiki teu bisa nyadiakeun data dina format nu bisa dibaca ku klien anjeun.",

# Attribution
'anonymous'        => 'Pamaké anonim {{SITENAME}}',
'siteuser'         => 'Pamaké $1 {{SITENAME}}',
'lastmodifiedatby' => 'Kaca ieu panungtungan dirobah $2, $1 ku $3.', # $1 date, $2 time, $3 user
'and'              => 'jeung',
'othercontribs'    => 'Dumasar karya $1.',
'others'           => 'Séjénna',
'siteusers'        => 'Pamaké $1 {{SITENAME}}',
'creditspage'      => 'Pangajén kaca',
'nocredits'        => 'Teu aya émbaran pangajén pikeun kaca ieu.',

# Spam protection
'spamprotectiontitle'    => 'Saringan spam',
'spamprotectiontext'     => 'Kaca nu rék disimpen dipeungpeuk ku saringan spam. Sigana mah ieu téh alatan tumbu ka loka luar.',
'subcategorycount'       => 'Aya $1 subkategori na kategori ieu.',
'categoryarticlecount'   => 'Aya $1 artikel na kategori ieu.',
'category-media-count'   => 'Aya $1 koropak dina kategori ieu.',
'listingcontinuesabbrev' => ' (samb.)',

# Info page
'infosubtitle'   => 'Iber pikeun kaca',
'numedits'       => 'Jumlah éditan (artikel): $1',
'numtalkedits'   => 'Jumlah éditan (kaca sawala): $1',
'numwatchers'    => 'Jumlah nu ngawaskeun: $1',
'numauthors'     => 'Jumlah pangarang nu béda (artikel): $1',
'numtalkauthors' => 'Jumlah pangarang nu béda (kaca sawala): $1',

# Math options
'mw_math_simple' => 'Mun basajan HTML, mun henteu PNG',
'mw_math_html'   => 'Mun bisa HTML, mun henteu PNG',
'mw_math_source' => 'Antep salaku TeX (pikeun panyungsi tulisan)',
'mw_math_modern' => 'Dianjurkeun pikeun panyungsi modérn',
'mw_math_mathml' => 'Mun bisa MathML (uji coba)',

# Patrolling
'markaspatrolleddiff'    => 'Tandaan salaku geus diriksa',
'markaspatrolledtext'    => 'Tandaan artikel ieu salaku geus diriksa',
'markedaspatrolled'      => 'Tandaan salaku geus diriksa',
'markedaspatrolledtext'  => 'Révisi nu dipilih geus ditandaan salaku geus diriksa.',
'rcpatroldisabled'       => 'Ronda Nu Anyar Robah ditumpurkeun',
'rcpatroldisabledtext'   => 'Fitur Ronda Nu Anyar Robah kiwari ditumpurkeun.',
'markedaspatrollederror' => 'Teu bisa nandaan geus dipatroli',

# Image deletion
'deletedrevision' => 'Révisi heubeul nu dihapus $1.',

# Browsing diffs
'previousdiff' => '← Ka béda saméméhna',
'nextdiff'     => 'Ka béda salajengna, jung →',

# Media information
'imagemaxsize' => 'Watesan gambar na kaca dadaran gambar nepi ka:',

'newimages'    => 'Galeri gambar anyar',
'showhidebots' => '($1 bot)',
'noimages'     => 'Taya nanaon.',

'passwordtooshort' => 'Sandi anjeun pondok teuing, sahanteuna kudu $1 karakter.',

# Metadata
'metadata-help' => 'Ieu koropak ngandung émbaran tambahan, nu sigana asalna tina kaméra digital atawa paminday nu dipaké pikeun ngadigitalkeunana. Mun ieu koropak geus dirobah tina bentuk aslina, datana bisa jadi teu bener.',

# EXIF tags
'exif-compression'            => 'Skéma komprési',
'exif-xresolution'            => 'Résolusi horizontal',
'exif-datetime'               => 'Wanci jeung titimangsa parobahan koropak',
'exif-artist'                 => 'Pangarang',
'exif-compressedbitsperpixel' => 'Mode komprési gambar',
'exif-usercomment'            => 'Koméntar pamaké',
'exif-datetimeoriginal'       => 'Titimangsa jeung wanci dijieunna data',
'exif-datetimedigitized'      => 'Titimangsa jeung wanci digitisasi',
'exif-contrast'               => 'Kontras',
'exif-gpstimestamp'           => 'Wanci GPS (jam atomik)',

'exif-componentsconfiguration-0' => 'euweuh',

'exif-customrendered-0' => 'Prosés normal',
'exif-customrendered-1' => 'Prosés biasa',

'exif-contrast-1' => 'Leuleus',
'exif-contrast-2' => 'Heuras',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilométer per jam',
'exif-gpsspeed-m' => 'Mil per jam',
'exif-gpsspeed-n' => 'Knot',

# External editor support
'edit-externally'      => 'Édit koropak ieu migunakeun aplikasi éksternal',
'edit-externally-help' => 'Tempo [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions] pikeun émbaran leuwih jéntré.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'sadaya',
'imagelistall'     => 'kabéh',
'watchlistall1'    => 'sadaya',
'watchlistall2'    => 'sadaya',
'namespacesall'    => 'kabéh',

# E-mail address confirmation
'confirmemail'            => 'Konfirmasi alamat surélék',
'confirmemail_text'       => 'Wiki ieu merlukeun anjeun sangkan méré konfirmasi alamat surélék saméméh migunakeun fitur surélék. Aktifkeun tombol di handap pikeun ngirimkeun surat konfirmasi ka alamat anjeun. Suratna ngandung tumbu nu ngandung sandina; muatkeun tumbuna kana panyungsi anjeun pikeun ngonfirmasi yén alamat surélék anjeun sah.',
'confirmemail_send'       => 'Kirimkeun surat konfirmasi sandi',
'confirmemail_sent'       => 'Surélék konfirmasi geus dikirim.',
'confirmemail_sendfailed' => 'Surat konfirmasi teu kakirim. Pariksa alamatna, bisi salah. Informasi: $1',
'confirmemail_invalid'    => 'Sandi konfirmasi salah, meureun alatan sandina geus kadaluwarsa.',
'confirmemail_success'    => 'Alamat surélék anjeun geus dikonfirmasi, ayeuna anjeun geus bisa migunakeun wikina.',
'confirmemail_loggedin'   => 'Alamat surélék anjeun geus dikonfirmasi.',
'confirmemail_error'      => 'Aya nu salah nalika nyimpen konfirmasi anjeun.',
'confirmemail_subject'    => 'Konfirmasi alamat surélék {{SITENAME}}',
'confirmemail_body'       => 'Aya, sigana mah anjeun ti alamat IP $1, geus ngadaptarkeun rekening "$2" maké alamat surélék ieu na {{SITENAME}}.

Pikeun mastikeun yén rekening ieu mémang kagungan sarta ngakifkeun fitur surélék di {{SITENAME}}, buka tumbu di handap ieu kana panyungsi/\'\'browser\'\' anjeun:

$3

Mun ieu *lain* anjeun, tumbuna ulah dituturkeun. Sandi konfirmasi ieu bakal kadaluwarsa $4.',

# Inputbox extension, may be useful in other contexts as well
'createarticle' => 'Jieun artikel',

# Trackbacks
'trackbackremove' => ' ([$1 Hapus])',

# Delete conflict
'deletedwhileediting' => 'Awas: kaca ieu geus dihapus nalika anjeun ngédit!',
'confirmrecreate'     => "Pamaké [[User:$1|$1]] ([[User talk:$1|ngobrol]]) geus ngahapus artikel ieu nalika anjeun ngédit kalawan alesan:
: ''$2''
mangga pastikeun yén anjeun rék nyieun deui artikel ieu.",

# HTML dump
'redirectingto' => 'Mindahkeun ka [[$1]]...',

# action=purge
'confirm_purge'        => 'Hapus sindangan kaca ieu?

$1',
'confirm_purge_button' => 'Heug',

'youhavenewmessagesmulti' => 'Anjeun boga talatah anyar di $1',

'articletitles' => "Artikel nu dimimitian ku ''$1''",
'hideresults'   => 'Sumputkeun hasil',

'loginlanguagelabel' => 'Basa: $1',

# Multipage image navigation
'imgmultipageprev' => '&larr; kaca saacana',
'imgmultipagenext' => 'kaca salajeungna &rarr;',
'imgmultigo'       => 'Téang!',
'imgmultigotopre'  => 'Ka kaca',

# Table pager
'ascending_abbrev'         => 'naék',
'descending_abbrev'        => 'turun',
'table_pager_next'         => 'Kaca salajeungna',
'table_pager_prev'         => 'Kaca saacana',
'table_pager_limit'        => 'Pidangkeun $1 éntri pér halaman',
'table_pager_limit_submit' => 'Téang',
'table_pager_empty'        => 'Nyamos',

# Auto-summaries
'autosumm-blank'   => 'Ngahapus eusi ti kaca',
'autosumm-replace' => "Ngaganti kaca ku '$1'",
'autoredircomment' => 'Mindahkeun ka [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Kaca anyar: $1',

# Live preview
'livepreview-failed' => 'Sawangan langsung gagal!
Coba ku sawangan normal.',
'livepreview-error'  => 'Gagal nyambungkeun: $1 "$2"
Coba ku sawangan normal.',

);

?>
