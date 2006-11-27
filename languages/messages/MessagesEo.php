<?php
$namespaceNames = array(
	NS_MEDIA          => 'Media',
	NS_SPECIAL        => 'Speciala',
	NS_MAIN           => '',
	NS_TALK           => 'Diskuto',
	NS_USER           => 'Vikipediisto', # FIXME: Generalize v-isto kaj v-io
	NS_USER_TALK      => 'Vikipediista_diskuto',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1_diskuto',
	NS_IMAGE          => 'Dosiero', #FIXME: Check the magic for Image: and Media:
	NS_IMAGE_TALK     => 'Dosiera_diskuto',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'MediaWiki_diskuto',
	NS_TEMPLATE       => 'Ŝablono',
	NS_TEMPLATE_TALK  => 'Ŝablona_diskuto',
	NS_HELP           => 'Helpo',
	NS_HELP_TALK      => 'Helpa_diskuto',
	NS_CATEGORY       => 'Kategorio',
	NS_CATEGORY_TALK  => 'Kategoria_diskuto',
);

$quickbarSettings =  array(
	'Nenia', 'Fiksiĝas maldekstre', 'Fiksiĝas dekstre', 'Ŝvebas maldekstre'
);

$skinNames = array(
	'standard' => 'Klasika',
	'nostalgia' => 'Nostalgio',
	'cologneblue' => 'Kolonja Bluo',
	'mono' => 'Senkolora',
	'monobook' => 'Librejo',
	'chick' => 'Kokido',
);

$separatorTransformTable = array(',' => ' ', '.' => ',' );

$datePreferences = false;
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j. M Y',
	'dmy both' => 'H:i, j. M Y',
);


$messages = array(
'tog-underline'         => 'Substreku ligilojn',
'tog-highlightbroken'   => 'Ruĝigu ligilojn al neekzistantaj paĝoj',
'tog-justify'           => 'Alkadrigu liniojn',
'tog-hideminor'         => 'Kaŝu malgrandajn redaktetojn ĉe <i>Lastaj ŝanĝoj</i>',
'tog-usenewrc'          => 'Novstila Lastaj Ŝanĝoj (bezonas JavaSkripton)',
'tog-numberheadings'    => 'Aŭtomate numeru sekciojn',
'tog-showtoolbar'       => 'Montru eldonilaron',
'tog-editondblclick'    => 'Redaktu per duobla alklako (JavaScript)',
'tog-editsection'       => 'Montru [redaktu]-ligiloj por sekcioj',
'tog-editsectiononrightclick'=> 'Redaktu sekciojn per dekstra musklako',
'tog-showtoc'           => 'Montru liston de enhavoj',
'tog-rememberpassword'  => 'Memoru mian pasvorton',
'tog-editwidth'         => 'Redaktilo estu plenlarĝa',
'tog-watchcreations'    => 'Aldonu de mi kreitajn paĝojn al mia atentaro',
'tog-watchdefault'      => 'Priatentu paĝojn de vi redaktintajn',
'tog-minordefault'      => 'Marku ĉiujn redaktojn malgrandaj',
'tog-previewontop'      => 'Montru antaŭrigardon antaŭ redaktilo',
'tog-previewonfirst'    => 'Montru antaŭrigardon je unua redakto',
'tog-nocache'           => 'Malaktivigu kaŝmemorigon de paĝoj.',
'tog-enotifwatchlistpages'=> 'Sendu al mi retmesaĝon kiam tiu paĝo estas ŝanĝita',
'tog-enotifusertalkpages'=> 'Sendu al mi retmesaĝon kiam mia diskutpaĝo estas ŝanĝita',
'tog-shownumberswatching'=> 'Montru la nombron da priatentaj uzantoj',
'tog-fancysig'          => 'Simpla subskribo (sen aŭtomata ligo)',
'tog-externaleditor'    => 'Uzu defaŭlte eksteran tekstprilaborilon',
'tog-externaldiff'      => 'Uzu defaŭlte eksteran ŝanĝmontrilon',
'tog-showjumplinks'     => 'Ebligi alirligojn "salti al" 
<!-- Bonvolu kontroli ĉu ĝustas la traduko de : Enable "jump to" accessibility links -->',
'tog-watchlisthideown'  => 'Kaŝu miajn redaktojn de la atentaro',
'tog-watchlisthidebots' => 'Kaŝu bot-redaktojn de la atentaro',
'underline-always'      => 'Ĉiam',
'underline-never'       => 'Neniam',
'underline-default'     => 'Defaŭlte laŭ foliumilo',
'skinpreview'           => '(Antaŭrigardo)',
'sunday'                => 'dimanĉo',
'monday'                => 'lundo',
'tuesday'               => 'mardo',
'wednesday'             => 'merkredo',
'thursday'              => 'ĵaŭdo',
'friday'                => 'vendredo',
'saturday'              => 'sabato',
'january'               => 'januaro',
'february'              => 'februaro',
'march'                 => 'marto',
'april'                 => 'aprilo',
'may_long'              => 'majo',
'june'                  => 'junio',
'july'                  => 'julio',
'august'                => 'aŭgusto',
'september'             => 'septembro',
'october'               => 'oktobro',
'november'              => 'novembro',
'december'              => 'decembro',
'may'                   => 'Maj',
'aug'                   => 'Aŭg',
'oct'                   => 'Okt',
'categories'            => 'Kategorioj',
'pagecategories'        => '{{PLURAL:$1|Kategorio|Kategorioj}}',
'category_header'       => 'Artikoloj en kategorio "$1"',
'subcategories'         => 'Subkategorioj',
'mainpage'              => 'Ĉefpaĝo',
'mainpagetext'          => 'Vikisoftvaro sukcese instaliĝis.',
'mainpagedocfooter'     => 'Consult the [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide User\'s Guide] for information on using the wiki software.

== Getting started ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]
* [http://mail.wikipedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',
'portal'                => 'Komunuma portalo',
'portal-url'            => 'Project:Komunuma portalo',
'about'                 => 'Enkonduko',
'aboutsite'             => 'Pri {{SITENAME}}',
'aboutpage'             => '{{SITENAME}}:Enkonduko',
'article'               => 'Artikolo',
'help'                  => 'Helpo',
'helppage'              => 'Help:Enhavo',
'bugreports'            => 'Raportu cimojn',
'bugreportspage'        => 'Project:Raportu cimojn',
'sitesupport'           => 'Subteno',
'sitesupport-url'       => 'Project:Subteno',
'faq'                   => 'Oftaj demandoj',
'faqpage'               => 'Project:Oftaj demandoj',
'edithelp'              => 'Helpo pri redaktado',
'newwindow'             => '(en nova fenestro)',
'edithelppage'          => 'Help:Kiel redakti paĝon',
'cancel'                => 'Nuligu',
'qbfind'                => 'Trovu',
'qbbrowse'              => 'Foliumado',
'qbedit'                => 'Redaktado',
'qbpageoptions'         => 'Paĝagado',
'qbpageinfo'            => 'Paĝinformoj',
'qbmyoptions'           => 'Personaĵoj',
'qbspecialpages'        => 'Specialaj paĝoj',
'moredotdotdot'         => 'Pli...',
'mypage'                => 'Mia paĝo',
'mytalk'                => 'Mia diskuto',
'anontalk'              => 'Diskutpaĝo por tiu ĉi IP',
'navigation'            => 'Navigado',
'currentevents'         => 'Aktualaĵoj',
'currentevents-url'     => 'Aktualaĵoj',
'disclaimers'           => 'Malgarantio',
'disclaimerpage'        => 'Malgarantia paĝo',
'privacy'               => 'Regularo pri respekto de la privateco',
'privacypage'           => 'Project:Respekto de la privateco',
'errorpagetitle'        => 'Eraro',
'returnto'              => 'Revenu al $1.',
'tagline'               => 'El {{SITENAME}}',
'search'                => 'Serĉu',
'searchbutton'          => 'Serĉu',
'go'                    => 'Ek!',
'searcharticle'                    => 'Ek!',
'history'               => 'Malnovaj versioj',
'history_short'         => 'Historio',
'updatedmarker'         => 'ĝisdatita de post mia lasta vizito',
'info_short'            => 'Informo',
'printableversion'      => 'Presebla versio',
'permalink'             => 'Konstanta ligilo',
'edit'                  => 'Redaktu',
'editthispage'          => 'Redaktu la paĝon',
'delete'                => 'Forigu',
'deletethispage'        => 'Forigu la paĝon',
'undelete_short'        => 'Malforigu $1 redaktojn',
'protect'               => 'Protektu',
'protectthispage'       => 'Protektu la paĝon',
'unprotect'             => 'Malprotektu',
'unprotectthispage'     => 'Malprotektu la paĝon',
'newpage'               => 'Nova paĝo',
'talkpage'              => 'Diskutu la paĝon',
'specialpage'           => 'Speciala Paĝo',
'personaltools'         => 'Personaj iloj',
'postcomment'           => 'Afiŝu komenton',
'articlepage'           => 'Vidu la artikolon',
'talk'                  => 'Diskuto',
'views'                 => 'Vidoj',
'toolbox'               => 'Iloj',
'userpage'              => 'Vidu personan paĝon',
'imagepage'             => 'Vidu dosieropaĝon',
'viewtalkpage'          => 'Vidu diskutopaĝon',
'otherlanguages'        => 'Aliaj lingvoj',
'redirectedfrom'        => '(Alidirektita el $1)',
'redirectpagesub'       => 'Redirekta paĝo',
'lastmodifiedat'          => 'Laste redaktita je $2, $1.',
'viewcount'             => 'Montrita $1-foje.',
'copyright'             => 'La enhavo estas havebla sub $1.',
'protectedpage'         => 'Protektita paĝo',
'jumpto'                => 'Saltu al:',
'jumptonavigation'      => 'navigado',
'jumptosearch'          => 'serĉo',
'badaccess'             => 'Vi ne havas sufiĉe da redaktorajtoj por tiu paĝo.',
'versionrequired'       => 'Versio $1 de MediaWiki nepras',
'versionrequiredtext'   => 'La versio $1 de MediaWiki estas necesa por uzi ĉi tiun paĝon. Vidu [[Special:Version]]',
'ok'                    => 'Ek!',
'retrievedfrom'         => 'Elŝutita el  "$1"',
'youhavenewmessages'    => 'Por vi estas $1 ($2).',
'newmessageslink'       => 'nova mesaĝo',
'newmessagesdifflink'   => 'ŝanĝoj kompare kun antaŭlasta versio',
'editsection'           => '<small>redaktu</small>',
'editsectionhint'       => 'Redaktu sekcion: $1',
'toc'                   => 'Enhavo',
'showtoc'               => 'montru',
'hidetoc'               => 'kaŝu',
'thisisdeleted'         => 'Vidu aŭ restarigu $1?',
'viewdeleted'           => 'Rigardu $1?',
'restorelink'           => '$1 forigita(j)n versio(j)n',
'feedlinks'             => 'Nutro:',
'nstab-main'            => 'Artikolo',
'nstab-user'            => '**** root {{lcfirst:ns:user}}a / Vikipediista paĝo',
'nstab-media'           => 'Media paĝo',
'nstab-special'         => 'Speciala',
'nstab-image'           => 'Bildo',
'nstab-mediawiki'       => 'Sistema mesaĝo',
'nstab-template'        => 'Ŝablono',
'nstab-help'            => 'Helpo',
'nstab-category'        => 'Kategorio',
'nosuchaction'          => 'Ne ekzistas tia ago',
'nosuchactiontext'      => 'La agon (\'action\') nomitan de la URL
ne agnoskas la programaro de {{SITENAME}}',
'nosuchspecialpage'     => 'Ne ekzistas tia speciala paĝo',
'nospecialpagetext'     => 'Vi petis [[Special:Specialpages|specialan paĝon]] kiun ne agnoskas la programaro de {{SITENAME}}.',
'error'                 => 'Eraro',
'databaseerror'         => 'Datumbaza eraro',
'dberrortext'           => 'Sintakseraro okazis en informpeto al la datumaro.
Jen la plej laste provita informmendo:
<blockquote><tt>$1</tt></blockquote>
el la funkcio "<tt>$2</tt>".
MySQL redonis eraron  "<tt>$3: $4</tt>".',
'dberrortextcl'         => 'Okazis sintaksa eraro en la informpeto al la datumaro.
La lasta provita peto estis:
"$1"
el la funkcio "$2".
\'\'MySQL\'\' resendis la erarmesaĝon "$3: $4".',
'noconnect'             => 'Neeblis konekti al la datumbazo; estas ia erarao aŭ oni riparadas la servilon.
<br />
$1',
'nodb'                  => 'Neeblis elekti datumaron $1',
'cachederror'           => 'Intertempe, jen konservita kopio de la petita paĝo (ĝi eble ne estas ĝisdata).',
'readonly'              => 'Datumaro ŝlosita, nurlega',
'enterlockreason'       => 'Bonvolu klarigi, kial oni ŝlosas la datumaron, kaj
la estimatan tempon de malŝlosado.',
'readonlytext'          => 'La datumaro de {{SITENAME}} estas nun ŝlosita kontraŭ
novaj aldonaj kaj aliaj ŝanĝoj, probable pro laŭkutima flegado de la datumaro.
Bonvolu reprovu post iom da tempo.

La ŝlosinto lasis la jenan mesaĝon:
<p>$1</p>',
'missingarticle'        => 'La datumbazo ne trovis la tekston de
artikolo, kiun ĝi devus trovi, nomita "$1".
Ĉi tio ne estas eraro de la datumbazo, sed probable cimo en la programo.
Bonvolu raporti ĉi tion al iu sistemestro, kaj rimarkigi la retadreson (URL).',
'internalerror'         => 'Interna eraro',
'filecopyerror'         => 'Neeblis kopii dosieron  "$1" al "$2".',
'filerenameerror'       => 'Neeblis alinomi dosieron "$1" al "$2".',
'filedeleteerror'       => 'Neeblis forigi dosieron "$1".',
'filenotfound'          => 'Neeblis trovi dosieron "$1".',
'unexpected'            => 'Neatendita valuto: "$1"="$2".',
'formerror'             => 'Eraro: neeblis liveri formulon',
'badarticleerror'       => 'Tiun ĉi agon oni ne povas apliki al tiu ĉi artikolo.',
'cannotdelete'          => 'Neeblis forigi la elektitan paĝon aŭ dosieron.',
'badtitle'              => 'Nevalida titolo',
'badtitletext'          => 'La petita paĝotitolo estas nevalida, malplena, aŭ
malĝuste ligita interlingva aŭ intervikia titolo.',
'perfdisabled'          => 'Ni petas pardonon! La petita funkcio estas malebligita
provizore por konservi la rapidecon de la servilo.',
'perfdisabledsub'       => 'Jen konservita kopio laŭ $1:',
'perfcached'            => 'La sekvantaj informoj venas el kaŝmemoro kaj eble ne estas ĝisdataj :',
'wrong_wfQuery_params'  => 'Malĝustaj parametroj por wfQuery()<br />
Funkcio: $1<br />
Peto: $2',
'viewsource'            => 'Vidu vikitekston',
'viewsourcefor'         => 'por $1',
'protectedtext'         => 'Tiu ĉi paĝo estas ŝlosita kontraŭ redaktado; estas diversaj eblaj kialoj por tio. Bv legi [[Project:Ŝlositaj paĝoj]].

Vi ja rajtas vidi kaj kopii la fontotekston de la vikipaĝo:',
'editinginterface'      => '\'\'\'Atentu:\'\'\' Vi redaktas paĝon, kiu estas uzata kiel interfaca teksto por la softvaro. Ŝanĝoj de tiu ĉi teksto povas ŝanĝi aspekton de la interfaco por aliaj uzantoj.',
'logouttitle'           => 'Elsalutu!',
'logouttext'            => '<strong>Vi elsalutis kaj finis vian seancon.</strong><br />
Vi rajtas daŭre vikiumi sennome, aŭ vi povas reensaluti kiel la sama aŭ kiel alia uzanto.',
'welcomecreation'       => '<h2>Bonvenon, $1!</h2> Via konto estas kreita.
<span style="color:#ff0000">Ne forgesu fari viajn {{SITENAME}}-preferojn!</span>',
'loginpagetitle'        => 'Ensalutu / enskribu',
'yourname'              => 'Via salutnomo',
'yourpassword'          => 'Via pasvorto',
'yourpasswordagain'     => 'Retajpu pasvorton',
'remembermypassword'    => 'Rememoru mian pasvorton.',
'yourdomainname'        => 'Via domajno',
'externaldberror'       => 'Aŭ estis datenbaza eraro rilate al ekstera aŭtentikigado, aŭ vi ne permesas ĝisdatigi vian eksteran konton.',
'loginproblem'          => '<b>Okazis problemo pri via ensalutado.</b><br />Bonvolu reprovi!',
'alreadyloggedin'       => '<strong>$1, vi jam estas ensalutinta!</strong><br />',
'login'                 => 'Ensalutu',
'loginprompt'           => 'Necesas ke via foliumilo permesu kuketojn por ensaluti en la {{SITENAME}}.',
'userlogin'             => 'Ensalutu',
'logout'                => 'Elsalutu',
'userlogout'            => 'Elsalutu',
'notloggedin'           => 'Ne ensalutinta',
'nologin'               => 'Ĉu vi ne jam havas salutnomon? $1.',
'nologinlink'           => 'Kreu konton',
'createaccount'         => 'Kreu novan konton',
'createaccountmail'     => 'retpoŝte',
'badretype'             => 'La pasvortoj kiujn vi tajpis ne egalas.',
'userexists'            => 'Jam estas uzanto kun la nomo kiun vi elektis. Bonvolu elekti alian nomon.',
'youremail'             => 'Via retpoŝtadreso',
'username'              => 'Salutnomo:',
'uid'                   => 'Uzantnumero:',
'yourrealname'          => 'Vera nomo¹',
'yourlanguage'          => 'Lingvo',
'yourvariant'           => 'Varianto',
'yournick'              => 'Via kaŝnomo (por subskriboj)',
'badsig'                => 'La kruda identigaĵo nevalidas; kontrolu la HTML-etikedojn.',
'email'                 => 'Retpoŝto',
'prefs-help-realname'   => '* Vera nomo (opcia) : se vi elektas sciigi ĝin, ĝi estos uzita por aŭtorigi vin pri viaj kontribuoj.',
'loginerror'            => 'Ensaluta eraro',
'prefs-help-email'      => '* Retpoŝto (opcia) : ebligas al aliaj kontakti vin tra via uzantpaĝo aŭ diskutpaĝo sen neceso malkaŝi vian identecon.',
'nocookiesnew'          => 'La uzantokonto estis kreita sed vi ne estas ensalutinta. *** E-igo lcfirst {{SITENAME}} uzas kuketojn por akcepti uzantojn. Kuketoj esta malaktivigitaj ĉe vi. Bonvolu aktivigi ilin kaj ensalutu per viaj novaj salutnomo kaj pasvorto.',
'nocookieslogin'        => '{{SITENAME}} uzas kuketojn por akcepti uzantojn. Kuketoj esta malaktivigitaj ĉe vi. Bonvolu aktivigi ilin kaj provu denove.',
'noname'                => 'Vi ne tajpis validan salutnomon.',
'loginsuccesstitle'     => 'Ensalutado sukcesis',
'loginsuccess'          => 'Vi nun estas en la {{SITENAME}} kiel uzanto "$1".',
'nosuchuser'            => 'Neniu uzanto nomiĝas "$1".
Bonvolu kontroli vian literumadon, aŭ uzu la malsupran formularon por krei novan konton.',
'nosuchusershort'       => 'Ne ekzistas uzanto kun la nomo "$1". Bonvolu kontroli vian ortografion.',
'wrongpassword'         => 'Vi tajpis malĝustan pasvorton. Bonvolu provi denove.',
'wrongpasswordempty'    => 'Vi tajpis malplenan pasvorton. Bonvolu provi denove.',
'mailmypassword'        => 'Retpoŝtu al mi novan pasvorton',
'passwordremindertitle' => 'Rememorigo el {{SITENAME}} pri perdita pasvorto',
'passwordremindertext'  => 'Iu (probable vi, el IP-adreso $1)
petis, ke ni sendu al vi novan pasvorton por ensaluti {{SITENAME}}n ($4).
La pasvorto por uzanto "$2" nun estas "$3".
Ni rekomendas, ke vi nun ensalutu kaj ŝanĝu vian pasvorton.',
'noemail'               => 'Retpoŝtadreso ne estas registrita por uzanto "$1".',
'passwordsent'          => 'Oni sendis novan pasvorton al la retpoŝtadreso
registrita por "$1".
Bonvolu saluti denove ricevinte ĝin.',
'eauthentsent'          => 'Konfirma retmesaĝo estas sendita al la nomita retadreso. Antaŭ ol iu ajn alia mesaĝo estos sendita al la konto, vi devos sekvi la instrukciojn en la mesaĝo por konfirmi ke la konto ja estas la via.',
'acct_creation_throttle_hit'=> 'Ni pardonpetas - vi jam kreis $1 kontojn. Vi ne povas krei pli.',
'emailauthenticated'    => 'Via retpoŝta adreso estis autentikigita ĉe $1.',
'emailnotauthenticated' => 'Via retadreso ne jam estas aŭtentigita. Neniu retmesaĝo estos sendita al iu el la sekvantaj adresoj.',
'emailconfirmlink'      => 'Konfirmu vian retpoŝtan adreson',
'invalidemailaddress'   => 'La retpoŝt-adreso ne estas akceptebla ĉar ĝi ŝajne havas nevalidan formaton. Bonvole entajpu ĝust-formatan adreson, aŭ malplenigu la zonon.',
'accountcreated'        => 'Konto kreita',
'accountcreatedtext'    => 'La uzanto-konto por $1 estas kreita.',
'bold_sample'           => 'Grasa teksto',
'bold_tip'              => 'Grasa teksto',
'italic_sample'         => 'Kursiva teksto',
'italic_tip'            => 'Kursiva teksto',
'link_sample'           => 'Ligtitolo',
'link_tip'              => 'Interna ligo',
'extlink_sample'        => 'http://www.ekzemplo.com ligtitolo',
'extlink_tip'           => 'Ekstera ligo (memoru http:// prefikson)',
'headline_sample'       => 'Titola teksto',
'headline_tip'          => 'Titololinio je dua nivelo',
'math_sample'           => 'Enmetu formulon ĉi tien',
'math_tip'              => 'Matematika formulo (LaTeX)',
'nowiki_sample'         => '	 Enmetu ne formatitan tekston ĉi tien',
'nowiki_tip'            => 'Ignoru vikiformatadon',
'image_sample'          => 'Ekzemplo.jpg',
'image_tip'             => 'Enŝutita bildo',
'media_sample'          => 'Ekzemplo.mp3',
'media_tip'             => 'Ligo al dosiero sona ...',
'sig_tip'               => 'Via subskribo kun tempstampo',
'hr_tip'                => 'Horizontala linio (uzu ŝpareme)',
'summary'               => 'Resumo',
'subject'               => 'Temo/subtitolo',
'minoredit'             => 'Ĉi tiu ŝanĝo estas redakteto',
'watchthis'             => 'Atentadu la artikolon',
'savearticle'           => 'Konservu ŝanĝojn',
'preview'               => 'Antaŭrigardo',
'showpreview'           => 'Antaŭrigardu',
'showdiff'              => 'Montru ŝanĝojn',
'anoneditwarning'       => 'Vi ne estas ensalutinta. Via IP-adreso enregistriĝos en la ŝango-historio de tiu ĉi paĝo.',
'missingsummary'        => '\'\'\'Rememorigilo:\'\'\' Vi ne provizis redaktan resumon. Se vi alklakos denove la savan butonon, via redaktaĵo estos storata sen resumo.',
'missingcommenttext'    => 'Bonvolu entajpi komenton malsupre.',
'blockedtitle'          => 'La uzanto estas forbarita.',
'blockedtext'           => 'Via konto aŭ IP-adreso estis forbarita fare de $1,
kiu priskribis la kialon jene:<br />
$2<br />
Vi rajtas kontakti tiun administranton por pridiskuti la forbaradon.

Via IP-adreso estas $3. Bonvolu mencii ĝin en ajna plendo.',
'whitelistedittitle'    => 'Ensalutado devigata por redakti',
'whitelistedittext'     => 'Vi devas $1 por redakti paĝojn.',
'whitelistreadtitle'    => 'Ensalutado devigata por legi',
'whitelistreadtext'     => 'Vi devas [[Special:Userlogin|ensaluti]] por legi paĝojn.',
'whitelistacctitle'     => 'Vi ne rajtas krei konton',
'whitelistacctext'      => 'Por rajti krei konton en ĉi tiu vikio vi devas [[Special:Userlogin|ensaluti]] kaj havi la taŭgajn permesojn.',
'confirmedittitle'      => 'Nepras konfirmi per retpoŝto por redakti',
'confirmedittext'       => 'Vi devas konfirmi vian retpoŝtan adreson antaŭ ol redakti paĝojn. Bonvolu agordi kaj validigi vian retadreson per viaj [[Special:Preferences|uzulaj preferoj]].',
'loginreqtitle'         => 'Nepre ensalutu',
'loginreqlink'          => 'login',
'loginreqpagetext'      => 'Vi devas $1 por rigardi aliajn paĝojn.',
'accmailtitle'          => 'Pasvorto sendita.',
'accmailtext'           => 'La pasvorto por \'$1\' estis sendita al  $2.',
'newarticle'            => '(Nova)',
'newarticletext'        => 'Vi sekvis ligilon al paĝo jam ne ekzistanta. Se vi volas krei ĝin, ektajpu sube (vidu la [[Project:Helpo|helpopaĝo]] por klarigoj.) Se vi malintence alvenis ĉi tien, simple alklaku la retrobutonon de via retumilo.',
'anontalkpagetext'      => '---- \'\'Jen diskutopaĝo por iu anonima kontribuanto kiu ne jam kreis konton aŭ ne uzas ĝin. Ni tial devas uzi la cifran [[IP-adreso]] por identigi lin. la sama [[IP-adreso]] povas estis samtempte uzata de pluraj uzantoj. Se vi estas anonimulo kaj preferus eviti tiajn mistrafajn komentojn kaj konfuziĝon kun aliaj anonimuloj de via retejo, bonvolu [[Special:Userlogin|krei konton aŭ ensaluti]].\'\'',
'noarticletext'         => '(La paĝo nun estas malplena. Se vi ĵus kreis tiun ĉi paĝon klaku [{{fullurl:{{FULLPAGENAME}}|action=purge}} ĉi tien].)',
'clearyourcache'        => '\'\'\'Notu:\'\'\' Post konservado vi forviŝu la kaŝmemoron de via foliumilo por vidi la ŝanĝojn : \'\'\'Mozilo:\'\'\' alklaku \'\'Reŝarĝi\'\' (aŭ \'\'Stir-Shift-R\'\'), \'\'\'IE / Opera:\'\'\' \'\'Stir-F5\'\', \'\'\'Safari:\'\'\' \'\'Cmd-R\'\', \'\'\'Konqueror\'\'\' \'\'Stir-R\'\'.',
'usercssjsyoucanpreview'=> '<strong>Konsileto:</strong> Uzu la "Antaŭrigardan" butonon por provi vian novan css/js antaŭ konservi.',
'usercsspreview'        => '\'\'\'Memoru ke vi nur antaŭrigardas vian uzulan [[CSS]]. Ĝi ne jam estas konservita!\'\'\'',
'userjspreview'         => '\'\'\'Memoru ke vi nun nur provas kaj antaŭrigardas vian uzantan javaskripton, ĝi ne estas jam konservita\'\'\'',
'updated'               => '(Ŝanĝo registrita)',
'note'                  => '<strong>Noto:</strong>',
'previewnote'           => 'Memoru, ke ĉi tio estas nur antaŭrigardo kaj ankoraŭ ne konservita!',
'session_fail_preview'  => '<strong>Bedaŭrinde ne eblis trakti vian redakton pro manko de sesiaj datenoj. Bonvolu provi refoje. Se ankoraŭ ne efikas post tio, elsalutu kaj poste re-ensalutu.</strong>',
'previewconflict'       => 'La jena antaŭrigardo montras la tekston el la supra tekstujo,
kiel ĝi aperos se vi elektos konservi la paĝon.',
'editing'               => 'Redaktante $1',
'editinguser'               => 'Redaktante $1',
'editingsection'        => 'Redaktante $1 (sekcion)',
'editingcomment'        => 'Redaktante $1 (komenton)',
'editconflict'          => 'Redakta konflikto: $1',
'explainconflict'       => 'Iu alia ŝanĝis la paĝon post kiam vi ekredaktis.
La supra tekstujo enhavas la aktualan tekston de la artikolo.
Viaj ŝanĝoj estas en la malsupra tekstujo.
Vi devas mem kunfandi viajn ŝanĝojn kaj la jaman tekston.
<b>Nur</b> la teksto en la supra tekstujo estos konservita kiam
vi alklakos "Konservu".<br />',
'yourtext'              => 'Via teksto',
'storedversion'         => 'Registrita versio',
'nonunicodebrowser'     => '<strong>ATENTU: Via foliumilo ne eltenas unikodon, bonvolu ŝanĝi ĝin antaŭ ol redakti artikolon.</strong>',
'editingold'            => '<strong>AVERTO: Vi nun redaktas malnovan version de tiu ĉi artikolo.
Se vi konservos vian redakton, ĉiuj ŝanĝoj faritaj post tiu versio perdiĝos.</strong>',
'yourdiff'              => 'Malsamoj',
'copyrightwarning'      => 'Bonvolu noti, ke ĉiu kontribuaĵo al la {{SITENAME}} estu rigardata kiel eldonita laŭ $2 (vidu je $1). Se vi volas, ke via verkaĵo ne estu redaktota senkompate kaj disvastigota laŭvole, ne alklaku "Konservu".<br />
Vi ankaŭ ĵuras, ke vi mem verkis la tekston, aŭ ke vi kopiis ĝin el fonto senkopirajta.
<strong>NE UZU KOPIRAJTAJN VERKOJN SENPERMESE!</strong>',
'copyrightwarning2'     => 'Bonvolu noti ke ĉiuj kontribuoj al {{SITENAME}} povas esti reredaktita, ŝanĝita aŭ forigita de aliaj kontribuantoj. Se vi ne deziras ke viaj verkoj estu senkompate reredaktitaj, ne publikigu ilin ĉi tie.  <br />
Vi ankaŭ promesu al ni ke vi verkis tion mem aŭ kopiis el publika domajno aŭ simila libera fonto (vidu $1 por detaloj). 
<strong>NE PROPONU KOPIRAJTITAJN VERKOJN SEN PERMESO! </strong>',
'longpagewarning'       => '<strong>AVERTO: Tiu ĉi paĝo longas $1 kilobitokojn; kelkaj retumiloj
povas fuŝi redaktante paĝojn je longo proksime aŭ preter 32kb.
Se eble, bonvolu disigi la paĝon al malpli grandajn paĝerojn.</strong>',
'longpageerror'         => '<strong>Eraro: La teksto, kiun vi prezentis, longas $1 kilobajtojn, kio estas pli longa ol la maksimumo de $2 kilobajtoj. Ĝi ne povas esti storata.</strong>',
'readonlywarning'       => '<strong>AVERTO: La datumbazo estas ŝlosita por teknika laboro;
pro tio neeblas nun konservi vian redaktadon. Vi povas elkopii kaj englui
la tekston al tekstdosiero por poste reenmeti ĝin al la vikio.</strong>',
'protectedpagewarning'  => '<strong>AVERTO: Tiu ĉi paĝo estas ŝlosita kontraŭ redaktado krom de administrantoj (t.e., vi). Bv certiĝi, ke vi sekvas la normojn de la komunumo per via redaktado. Vidu [[Project:Ŝlositaj paĝoj|Ŝlositaj paĝoj]].</strong>',
'semiprotectedpagewarning'=> '\'\'\'Notu:\'\'\' Ĉi paĝo estas protektita tiel ke nur [[Special:Userlogin|ensalutintaj]] uzuloj povas redakti ĝin.',
'templatesused'         => 'Ŝablonoj uzitaj sur ĉi paĝo:',
'revhistory'            => 'Historio de redaktoj',
'nohistory'             => 'Ne ekzistas historio de redaktoj por ĉi tiu paĝo.',
'revnotfound'           => 'Ne ekzistas malnova versio de la artikolo',
'revnotfoundtext'       => 'Ne eblis trovi malnovan version de la artikolo kiun vi petis.
Bonvolu kontroli la retadreson (URL) kiun vi uzis por atingi la paĝon.\b',
'loadhist'              => 'Ŝarĝas redaktohistorion',
'currentrev'            => 'Aktuala versio',
'revisionasof'          => 'Kiel registrite je $1',
'previousrevision'      => '← Antaŭa versio',
'nextrevision'          => 'Sekva versio →',
'currentrevisionlink'   => 'vidu nunan version',
'cur'                   => 'nun',
'next'                  => 'sekv',
'last'                  => 'ant',
'histlegend'            => 'Klarigo: (nun) = vidu malsamojn kompare kun la nuna versio,
(ant) = malsamojn kompare kun la antaŭa versio, M = malgranda redakteto',
'deletedrev'            => '[forigita]',
'histfirst'             => 'plej frua',
'histlast'              => 'plej lasta',
'difference'            => '(Malsamoj inter versioj)',
'loadingrev'            => 'ŝarĝas version por malsamoj',
'lineno'                => 'Linio $1:',
'editcurrent'           => 'Redaktu la nunan version de la paĝo',
'selectnewerversionfordiff'=> 'Elektu la pli novan version por kompari.',
'selectolderversionfordiff'=> 'Elektu malpli novan version por kompari.',
'compareselectedversions'=> 'Komparu la selektitajn versiojn',
'searchresults'         => 'Serĉrezultoj',
'searchresulttext'      => 'Por pliaj informoj kiel priserĉi la {{SITENAME}}n, vidu [[Project:Serĉado|serĉi en {{SITENAME}}]].',
'searchsubtitle'           => 'Serĉmendo "[[:$1]]"',
'searchsubtitleinvalid'           => 'Serĉmendo "$1"',
'badquery'              => 'Misformita serĉmendo',
'badquerytext'          => 'Via serĉmendo ne estis plenumebla.
Eble vi provis serĉi vorton kun malpli ol tri literoj.
Tion la oni ankoraŭ ne povas fari. Ankaŭ eblas, ke vi mistajpis la
esprimon. Bonvolu reserĉi.',
'matchtotals'           => 'La serĉmendo "$1" liveris $2 artikolojn laŭ titolo
kaj $3 artikolojn laŭ enhavo.',
'titlematches'          => 'Trovitaj laŭ titolo',
'notitlematches'        => 'Neniu trovita laŭ titolo',
'textmatches'           => 'Trovitaj laŭ enhavo',
'notextmatches'         => 'Neniu trovita laŭ enhavo',
'prevn'                 => '$1 antaŭajn',
'nextn'                 => '$1 sekvajn',
'viewprevnext'          => 'Montru ($1) ($2) ($3).',
'showingresults'        => 'Montras <b>$1</b> trovitajn ekde la <b>$2</b>-a.',
'showingresultsnum'     => 'Montras <b>$3</b> trovitajn ekde la <b>$2</b>-a.',
'nonefound'             => '<strong>Noto</strong>: malsukcesaj serĉoj ofte
okazas ĉar oni serĉas tro da ofte uzataj vortoj, kiujn ne enhavas la indekso,
aŭ ĉar oni petas tro da serĉvortoj (nur paĝoj kiuj enhavas ĉiun serĉvorton
montriĝos en la rezulto).',
'powersearch'           => 'Trovu',
'powersearchtext'       => '
Serĉu en sekcioj: :<br />
$1<br />
$2 Kun alidirektiloj   Serĉu $3 $9',
'searchdisabled'        => '<p>Oni provizore malŝaltis serĉadon per la plenteksta
indekso pro troŝarĝita servilo. Intertempe, vi povas serĉi per <i>guglo</i> aŭ per <i>jahu!</i>:</p>',
'blanknamespace'        => '(Artikoloj)',
'preferences'           => 'Preferoj',
'prefsnologin'          => 'Ne jam salutis!',
'prefsnologintext'      => '[[Special:Userlogin|Ensalutu]] kaj vi povos ŝanĝi viajn preferojn.',
'prefsreset'            => 'Preferoj reprenitaj el la registro.',
'qbsettings'            => 'Preferoj pri ilaro',
'changepassword'        => 'Ŝanĝu pasvorton',
'skin'                  => 'Aspekto',
'math'                  => 'Tradukas matematikaĵon',
'dateformat'            => 'Datformato',
'datedefault'           => 'Nenia prefero',
'datetime'              => 'Dato kaj horo',
'math_failure'          => 'Malsukcesis analizi formulon',
'math_unknown_error'    => 'Nekonata eraro',
'math_unknown_function' => 'Nekonata funkcio',
'math_lexing_error'     => 'Leksika analizo malsukcesis',
'math_syntax_error'     => 'Sintakseraro',
'math_image_error'      => 'Konverto al PNG malsukcesis',
'prefs-personal'        => 'Uzulaj datumoj',
'prefs-rc'              => 'Lastaj ŝanĝoj kaj elmontro de stumpoj',
'prefs-misc'            => 'Miksitaĵoj',
'saveprefs'             => 'Konservu preferojn',
'resetprefs'            => 'Restarigi antaŭajn preferojn',
'oldpassword'           => 'Malnova pasvorto',
'newpassword'           => 'Nova pasvorto',
'retypenew'             => 'Retajpu novan pasvorton',
'textboxsize'           => 'Grandeco de redakta tekstujo',
'rows'                  => 'Linioj',
'columns'               => 'Kolumnoj',
'searchresultshead'     => 'Agordaĵoj pri serĉorezulto',
'resultsperpage'        => 'Montru trovitajn po',
'contextlines'          => 'Montru liniojn el paĝoj po',
'contextchars'          => 'Montru literojn el linioj ĝis po',
'stubthreshold'         => 'Indiku paĝojn malpli grandajn ol',
'recentchangescount'    => 'Montru kiom da titoloj en \'Lastaj ŝanĝoj\'',
'savedprefs'            => 'Viaj preferoj estas konservitaj.',
'timezonelegend'        => 'Horzono',
'timezonetext'          => 'Indiku je kiom da horoj via
loka horzono malsamas disde tiu de la servilo (UTC).
Ekzemple, por la Centra Eŭropa Horzono, indiku "1" vintre aŭ "2" dum somertempo.',
'localtime'             => 'Loka horzono',
'timezoneoffset'        => 'Malsamo',
'servertime'            => 'Loka horzono (UTC)',
'guesstimezone'         => 'Plenigita el la foliumilo',
'allowemail'            => 'Ricevu retmesaĝojn de aliaj uzantoj.',
'defaultns'             => 'Serĉu la jenajn sekciojn:',
'default'               => 'defaŭlte',
'files'                 => 'Dosieroj',
'userrights-lookup-user'=> 'Administru uzantogrupojn',
'userrights-user-editname'=> 'Entajpu uzantonomon:',
'editusergroup'         => 'Redaktu Uzantgrupojn',
'userrights-editusergroup'=> 'Redaktu uzantogrupojn.',
'saveusergroups'        => 'Konservu uzulan grupon',
'userrights-groupsmember'=> 'Membro de:',
'userrights-groupsavailable'=> 'Disponeblaj grupoj:',
'userrights-groupshelp' => 'Selektu grupojn el kiuj vi volas forigi aŭ al kiuj vi volas aldoni uzanton. Neselektitaj grupoj ne estos ŝanĝitaj. Vi povas malselekti grupon per STR.',
'group'                 => 'Grupo:',
'group-sysop'           => 'Sisopoj',
'group-bureaucrat'      => 'Burokratoj',
'group-all'             => '(ĉiuj)',
'group-sysop-member'    => 'Sisopo',
'group-bureaucrat-member'=> 'Burokrato',
'grouppage-bureaucrat'  => 'Project:Burokratoj',
'changes'               => 'ŝanĝoj',
'recentchanges'         => 'Lastaj ŝanĝoj',
'recentchangestext'     => '\'\'\'[[{{ns:project}}:Bonvenon al la {{SITENAME}}|Bonvenon al la {{SITENAME}}]]!\'\'\' Sekvu la plej lastajn ŝanĝojn en la {{SITENAME}} per ĉi tiu paĝo.
Utile povas esti legi ĉi tiujn paĝojn: [[{{ns:project}}:Oftaj demandoj|Oftaj demandoj]], ****',
'rcnote'                => 'Jen la plej lastaj <strong>$1</strong> ŝanĝoj dum la lastaj <strong>$2</strong> tagoj gxis la <strong>$3</strong>.',
'rcnotefrom'            => 'Jen la ŝanĝoj ekde <b>$2</b> (lastaj ĝis <b>$1</b>).',
'rclistfrom'            => 'Montru novajn ŝanĝojn ekde $1',
'rcshowhideminor'       => '$1 redaktetojn',
'rcshowhidebots'        => '$1 robotojn',
'rcshowhideliu'         => '$1 ensalutantojn',
'rcshowhideanons'       => '$1 anonimajn redaktojn',
'rcshowhidepatr'        => '$1 patrolitajn redaktojn',
'rcshowhidemine'        => '$1 miajn redaktojn',
'rclinks'               => 'Montru $1 lastajn ŝanĝojn; montru la ŝanĝojn dum la $2 lastaj tagoj.<br />$3',
'diff'                  => 'malsamoj',
'hist'                  => 'historio',
'hide'                  => 'kaŝu',
'show'                  => 'montru',
'minoreditletter'       => 'M',
'upload'                => 'Alŝutu dosieron',
'uploadbtn'             => 'Alŝutu dosieron',
'reupload'              => 'Realŝutu',
'reuploaddesc'          => 'Revenu al la alŝuta formularo.',
'uploadnologin'         => 'Ne ensalutinta',
'uploadnologintext'     => 'Se vi volas alŝuti dosierojn, vi devas [[Special:Userlogin|ensaluti]].',
'upload_directory_read_only'=> 'La TTT-servilo ne povas alskribi la alŝuto-dosierujon ($1).',
'uploaderror'           => 'Eraro okazis dum alŝuto',
'uploadtext'            => '<p>Por okulumi aŭ serĉi jam alŝutitajn dosierojn, aliru la [[Special:Imagelist|liston de alŝutaĵoj]]. Ĉiuj alŝutoj kaj forigoj estas registrataj en la [[Special:Log/upload|alŝuta loglibro]].</p>

<p>Uzu ĉi tiun formularon por alŝuti novajn bildojn kaj aliajn dosierojn por ilustrado de viaj artikoloj. Ĉe kutimaj retumiloj, vi vidos ĉi-sube butonon "Foliumi..." aŭ simile; tiu malfermas la dosierelektilon de via operaciumo. Kiam vi elektos dosieron, ĝia nomo plenigos la tekstujon apud la butono. Vi ankaŭ nepre devas klakjesi la skatolon por aserti, ke vi ne malobeas la leĝan kopirajton de aliuloj per alŝuto de la dosiero. Por plenumi la alŝutadon, alklaku la butono "Alŝutu". Tio ĉi eble iomete longe daŭros, se estas granda dosiero kaj se via interreta konekto malrapidas.</p>

<p>La dosiertipoj preferataj ĉe {{SITENAME}} estas JPEG por fotografaĵoj, PNG por grafikaĵoj, diagramoj, ktp; kaj OGG por sonregistraĵoj. Bonvolu doni al via dosiero nomon informan, por eviti konfuzon. Por enmeti la dosieron en artikolon, skribu ligilon laŭ la formoj</p>

* <nowiki>[[Image:Dosiero.jpg]]</nowiki>
* <nowiki>[[Image:Bildo.png|teksto por retumiloj negrafikaj]]</nowiki>
aŭ por sono
* <nowiki>[[Media:Dosiero.ogg]]</nowiki>

<p>Bonvolu rimarki, ke same kiel artikoloj en la {{SITENAME}}, aliaj uzantoj rajtas redakti, anstataŭigi, aŭ forigi viajn alŝutaĵojn se ili pensas, ke tio servus la vikion. Se vi aĉe misuzas la sistemon, eblas ke vi estos forbarita.</p>',
'uploadlog'             => 'loglibro de alŝutaĵoj',
'uploadlogpage'         => 'Loglibro_de_alŝutaĵoj',
'uploadlogpagetext'     => 'Jen la plej laste alŝutitaj dosieroj.
Ĉiuj tempoj montriĝas laŭ la horzono UTC.
<ul>
</ul>',
'filename'              => 'Dosiernomo',
'filedesc'              => 'Priskribo',
'fileuploadsummary'     => 'Resumo:',
'filestatus'            => 'Kopirajta statuso',
'filesource'            => 'Fonto',
'copyrightpage'         => 'Project:Kopirajto',
'copyrightpagename'     => 'permesilo **** GFDL **** uzata por la {{SITENAME}}',
'uploadedfiles'         => 'Alŝutitaj dosieroj',
'ignorewarning'         => 'Ignoru averton kaj konservu dosieron ĉiukaze',
'ignorewarnings'        => 'Ignoru ĉiajn avertojn',
'minlength'             => 'Dosiernomo devas havi pli ol du literojn.',
'illegalfilename'       => 'La dosiernomo $1 entenas karaktrojn kiuj ne estas permesitaj en paĝaj titoloj. Bonvolu renomi la dosieron kaj provu denove alŝuti ĝin.',
'badfilename'           => 'Dosiernomo estis ŝanĝita al "$1".',
'badfiletype'           => '".$1" estas neakceptata dosiertipo.',
'largefile'             => 'Oni rekomendas, ke dosieroj ne superu grandon de $1 bitokoj; tiu ĉi enhavas $2 bitokojn.',
'largefileserver'       => 'Ĉi tiu dosiero estas pli granda ol permesas la servilaj preferoj.',
'emptyfile'             => 'La dosiero kiun vi alŝutis ŝajnas malplena. Tio povas esti kaŭzita sde tajperaro en la titolo. Bonvolu kontroli ĉu vi vere volas alŝuti tiun dosieron.',
'fileexists'            => 'Dosiero kun tia ĉi nomo jam ekzistas. Bonvolu kontroli $1 krom se vi certas ke vi konscie volas ŝanĝi ĝuste tiun.',
'fileexists-forbidden'  => 'Dosiero kun tia ĉi nomo jam ekzistas; bonvole realŝutu ĉi tiun dosieron per nova nomo. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden'=> 'Dosiero kun tia ĉi nomo jam ekzistas en la komuna dosiero-deponejo; bonvole realŝutu ĉi tiun dosieron per nova nomo. [[Image:$1|thumb|center|$1]]',
'successfulupload'      => 'Alŝuto sukcesis!',
'fileuploaded'          => 'Vi sukcese alŝutis dosieron "$1".
Bonvolu sekvi la jenan ligilo: ($2) al la priskrib-paĝo kaj
verki iom da informo pri la dosiero. Ekzemple, de kie ĝi devenas;
kiam ĝi estis kreita, kaj kiu kreis ĝin; kaj ion ajn, kion vi scias pri ĝi.',
'uploadwarning'         => 'Averto',
'savefile'              => 'Konservu dosieron',
'uploadedimage'         => 'alŝutis "[[$1]]"',
'uploaddisabled'        => 'Ni petas pardonon, sed oni malebligis alŝutadon.',
'uploaddisabledtext'    => 'Alŝutado de dosieroj estas malfunkciigita je tiu ĉi vikio.',
'uploadscripted'        => 'HTML-aĵo aŭ skriptokodaĵo troviĝas en tiu ĉi dosiero, kiun TTT-foliumilo eble interpretus erare.',
'uploadcorrupt'         => 'La dosiero estas difektita aŭ havas malĝustan finaĵon. Bonvolu kontroli la dosieron kaj refoje alŝuti ĝin.',
'uploadvirus'           => 'Viruso troviĝas en la dosiero! Detaloj: $1',
'sourcefilename'        => 'Fonta dosiernomo',
'destfilename'          => 'Celdosiernomo',
'imagelist'             => 'Listo de alŝutitaj dosieroj',
'imagelisttext'         => 'Jen listo de $1 alŝutaĵoj, ordigitaj laŭ $2.',
'getimagelist'          => 'akiras dosierliston',
'ilsubmit'              => 'Trovu!',
'showlast'              => 'Montru la $1 lastajn bildojn laŭ $2.',
'byname'                => 'nomo',
'bydate'                => 'dato',
'bysize'                => 'grandeco',
'imgdelete'             => 'forigu',
'imgdesc'               => 'pri',
'imglegend'             => '(pri) = montru/redaktu priskribon de dosiero.',
'imghistory'            => 'Historio de alŝutoj',
'revertimg'             => 'res',
'deleteimg'             => 'for',
'deleteimgcompletely'   => 'for',
'imghistlegend'         => '(nun) = ĉi tiu estas la nuna versio de la dosiero, (for) = forigu
ĉi tiun malnovan version, (res) = restarigu ĉi tiun malnovan version.
<br /><i>Por vidi la dosieron laŭdate, alklaku la daton</i>.',
'imagelinks'            => 'Ligiloj al la dosiero',
'linkstoimage'          => 'La jenaj paĝoj ligas al ĉi tiu dosiero:',
'nolinkstoimage'        => 'Neniu paĝo ligas al ĉi tiu dosiero.',
'sharedupload'          => 'This file is a shared upload and may be used by other projects.',
'noimage'               => 'Ne ekzistas dosiero kun tia nomo vi povas [$1 alŝuti ĝin].',
'noimage-linktext'      => 'alŝutu ĝin',
'uploadnewversion-linktext'=> 'Alŝutu novan version de ĉi tiu dosiero',
'mimesearch'            => 'MIME-serĉilo',
'download'              => 'elŝutu',
'unwatchedpages'        => 'Neatentataj paĝoj',
'listredirects'         => 'Listo de redirektiloj',
'unusedtemplates'       => 'Neuzitaj ŝablonoj',
'unusedtemplatestext'   => 'Ĉi paĝo listigas ĉiujn paĝojn en la nomspaco "Ŝablono" kiuj ne estas enmetitaj en alia paĝo. Bonvolu kontroli aliajn ligilojn al la ŝablonoj antaŭ ol forigi ilin.',
'unusedtemplateswlh'    => 'aliaj ligiloj',
'randomredirect'        => 'Hazarda alidirekto',
'statistics'            => 'Statistiko',
'sitestats'             => 'Pri la retejo',
'userstats'             => 'Pri la uzantaro',
'sitestatstext'         => 'Troviĝas en nia datumaro sume \'\'\'$1\'\'\' paĝoj.
Tiu nombro enhavas "diskutpaĝojn", paĝojn pri {{SITENAME}}, "artikoletetojn", alidirektilojn, kaj aliajn, kiuj eble ne vere estas artikoloj. Malatentante ilin, oni povas nombri \'\'\'$2\'\'\' probablajn ĝustajn artikolojn.

\'\'\'$8\'\'\' dosieroj estis alŝutitaj.

Oni vidis sume \'\'\'$3\'\'\' paĝojn, kaj redaktis sume \'\'\'$4\'\'\' plural paĝojn
ekde la starigo de la vikio.
Tio estas meznombre po unu paĝo por \'\'\'$5\'\'\' paĝoj viditaj, kaj por \'\'\'$6\'\'\' redaktoj.

La nuna longeco de la [http://meta.wikimedia.org/wiki/Help:Job_queue laborenda vico] estas \'\'\'$7\'\'\'.',
'userstatstext'         => 'Enskribiĝis \'\'\'$1\'\'\' uzantoj. El tiuj, \'\'\'$2\'\'\' (aŭ \'\'\'$4%\'\'\') estas administrantoj (vidu $3).',
'disambiguations'       => 'Misligitaj apartigiloj',
'disambiguationspage'   => 'Template:Apartigilo',
'disambiguationstext'   => 'La jenaj paĝoj alligas <i>paĝon-apartigilon</i>. Ili devus anstataŭe alligi la ĝustan temon.<br />Oni konsideras tiujn paĝojn, kiujn alligas $1 apartigiloj.<br />Ligado el ne-artikolaj sekcioj <i>ne</i> listiĝas ĉi tie.',
'doubleredirects'       => 'Duoblaj alidirektadoj',
'doubleredirectstext'   => '<b>Atentu:</b> Eblas, ke la jena listo enhavas falsajn rezultojn. Ĝenerale, tio signifas, ke estas plua teksto kun ligiloj post la #REDIRECT.<br />
Ĉiu linio montras ligilojn ĉe la unua kaj dua alidirektadoj, kaj la unua linio de la teksto de la dua alidirektado, kiu ĝenerale montras la "veran" artikolon, kiu devus celi la unuan alidirektadon.',
'brokenredirects'       => 'Rompitaj alidirektadoj',
'brokenredirectstext'   => 'La jenaj alidirektadoj ligas al neekzistantaj artikoloj.',
'nbytes'                => '$1 {{PLURAL:$1|bitoko|bitokoj}}',
'ncategories'           => '$1 categories',
'nlinks'                => '$1 {{PLURAL:$1|ligilo|ligiloj}}',
'nrevisions'            => '$1 {{PLURAL:$1|revizio|revizioj}}',
'nviews'                => '$1-foje',
'lonelypages'           => 'Neligitaj paĝoj',
'uncategorizedpages'    => 'Neenkategoriitaj paĝoj',
'uncategorizedcategories'=> 'Neenkategoriitaj kategorioj',
'unusedcategories'      => 'Neuzitaj kategorioj',
'unusedimages'          => 'Neuzataj bildoj',
'popularpages'          => 'Plej vizitataj paĝoj',
'wantedcategories'      => 'Dezirataj kategorioj',
'wantedpages'           => 'Dezirataj paĝoj',
'mostlinked'            => 'Plej ligitaj paĝoj',
'mostlinkedcategories'  => 'Plej ligitaj kategorioj',
'mostcategories'        => 'Artikoloj kun la plej multaj kategorioj',
'mostimages'            => 'Plej ligitaj bildoj',
'mostrevisions'         => 'Artikoloj kun la plej multaj revizioj',
'allpages'              => 'Ĉiuj paĝoj',
'prefixindex'           => 'Indeksa prefikso',
'randompage'            => 'Hazarda paĝo',
'shortpages'            => 'Paĝetoj',
'longpages'             => 'Paĝegoj',
'deadendpages'          => 'Seneliraj paĝoj',
'listusers'             => 'Uzantaro',
'specialpages'          => 'Specialaj paĝoj',
'spheading'             => 'Specialaj paĝoj',
'restrictedpheading'    => 'Alirlimigitaj specialaj paĝoj',
'recentchangeslinked'   => 'Rilataj paĝoj',
'rclsub'                => '(al paĝoj ligitaj de "$1")',
'newpages'              => 'Novaj paĝoj',
'ancientpages'          => 'Plej malnovaj artikoloj',
'intl'                  => 'Interlingvaj ligiloj',
'move'                  => 'Movu',
'movethispage'          => 'Movu la paĝon',
'unusedimagestext'      => 'Notu, ke aliaj TTT-ejoj, ekzemple
la alilingvaj {{SITENAME}}j, povas rekte ligi al dosiero per URL.
Tio ne estus enkalkutita en la jena listo.',
'unusedcategoriestext'  => 'La paĝoj de la sekvanta kategorio jam ekzistas, sed neniu alia artikolo aŭ kategorio rilatas al ĝi.',
'booksources'           => 'Libroservoj',
'categoriespagetext'    => 'La sekvantaj kategorioj ekzistas jam en la vikio.',
'userrights'            => 'Prizorgo de uzulaj rajtoj',
'groups'                => 'Uzulaj grupoj',
'booksourcetext'        => 'Jen ligilaro al aliaj TTT-ejoj, kiuj vendas librojn,
kaj/aŭ informumos pri la libro ligita.
La {{SITENAME}} ne estas komerce ligita al tiuj vendejoj, kaj la listo ne estu
komprenata kiel rekomendo aŭ reklamo.',
'alphaindexline'        => '$1 ĝis $2',
'version'               => 'Versio',
'log'                   => 'Loglibroj',
'alllogstext'           => 'Suma kompilaĵo de ĉiuj alŝutoj, forigoj, protektoj, blokadoj kaj agoj de administrantoj. Vi povas pliprecizigi la kompilaĵon laŭ loglibra tipo, **** vikipediista **** nomo aŭ koncernita paĝo.',
'nextpage'              => 'Sekvanta paĝo ($1)',
'allpagesfrom'          => 'Montru paĝojn ekde :',
'allarticles'           => 'Ĉiuj artikoloj',
'allinnamespace'        => 'Ĉiuj paĝoj ($1 nomspaco)',
'allnotinnamespace'     => 'Ĉiuj paĝoj (ne en nomspaco $1)',
'allpagesprev'          => 'Antaŭen',
'allpagesnext'          => 'Sekven',
'allpagessubmit'        => 'Ek!',
'allpagesprefix'        => 'Montru paĝojn kun prefikso:',
'mailnologin'           => 'Neniu alsendota adreso',
'mailnologintext'       => 'Vi nepre estu [[Special:Userlogin|salutanta]] kaj havanta validan retpoŝtadreson en viaj [[Special:Preferences|preferoj]] por retpoŝti al aliaj uzantoj.',
'emailuser'             => 'Retpoŝtu',
'emailpage'             => 'Retpoŝtu',
'emailpagetext'         => 'Se la alsendota uzanto donis validan retpoŝtadreson en la preferoj, vi povas sendi unu mesaĝon per la jena formulo. La retpoŝtadreso, kiun vi metis en la preferoj, aperos kiel "El"-adreso de la poŝto, por ke la alsendonto povos respondi.',
'usermailererror'       => 'Resendita retmesaĝa erarsubjekto :',
'defemailsubject'       => '{{SITENAME}} ****-retmesaĝo',
'noemailtitle'          => 'Neniu retpoŝtadreso',
'noemailtext'           => 'Ĉi tiu uzanto aŭ ne donis validan retpoŝtadreson aŭ elektis ne ricevi retpoŝton de aliaj uzantoj.',
'emailfrom'             => 'El',
'emailto'               => 'Al',
'emailsubject'          => 'Subjekto',
'emailmessage'          => 'Mesaĝo',
'emailsend'             => 'Sendu',
'emailsent'             => 'Retmesaĝo sendita',
'emailsenttext'         => 'Via retmesaĝo estas sendita.',
'watchlist'             => 'Atentaro',
'nowatchlist'           => 'Vi ne jam elektis priatenti iun ajn paĝon.',
'watchlistcount'        => '\'\'\'Vi atentas $1 aĵojn en via atentaro, inkluzive de diskutpaĝoj.\'\'\'',
'clearwatchlist'        => 'Malplenigu atentaron',
'watchlistcleartext'    => 'Ĉu vi certas, ke vi volas forigi ilin?',
'watchlistclearbutton'  => 'Malplenigi atentaron',
'watchlistcleardone'    => 'Via atentaro estis malplenigita. $1 eroj estis forigitaj.',
'watchnologin'          => 'Ne ensalutinta',
'watchnologintext'      => 'Nepras [[Special:Userlogin|ensaluti]] por ŝanĝi vian atentaron.',
'addedwatch'            => 'Aldonis al atentaro',
'addedwatchtext'        => 'La paĝo "[[:$1]]" estis aldonita al via [[Special:Watchlist|atentaro]]. Estontaj ŝanĝoj de tiu ĉi paĝo aperos en \'\'\'grasa tiparo\'\'\' en la [[Special:Recentchanges|listo de Lastaj Ŝanĝoj]], kaj estos listigitaj en via atentaro. Se vi poste volos forigi la paĝon el via atentaro, alklaku "Malatentu paĝon" en la ilobreto.',
'removedwatch'          => 'Forigis el atentaro',
'removedwatchtext'      => 'La paĝo "[[:$1]]" estas forigita el via atentaro.',
'watch'                 => 'Atentu',
'watchthispage'         => 'Priatentu paĝon',
'unwatch'               => 'Malatentu',
'unwatchthispage'       => 'Malatentu paĝon',
'notanarticle'          => 'Ne estas artikolo',
'watchnochange'         => 'Neniu artikolo en via atentaro redaktiĝis dum la prispektita tempoperiodo.',
'watchdetails'          => '(Vi priatentas $1 paĝojn [krom diskutopaĝoj];
laste $2 paĝoj entute redaktiĝis en la vikio; $3...
[$4 redaktu vian atentaron].)',
'wlheader-enotif'       => '* Retpoŝta sciigo estas ebligita',
'wlheader-showupdated'  => '* Montriĝas per \'\'\'dikaj literoj\'\'\' tiuj paĝoj, kiujn oni ŝanĝis ekde kiam vi laste vizitis ilin',
'watchmethod-recent'    => 'traserĉas lastajn redaktojn',
'watchmethod-list'      => 'traserĉas priatentitajn',
'removechecked'         => 'Forprenu elektitajn el la listo',
'watchlistcontains'     => 'Via atentaro enhavas $1 paĝojn.',
'watcheditlist'         => 'Jen listo de ĉiu paĝtitolo en via atentaro.
Elektu forigotajn paĝojn kaj alklaku "forprenu elektitajn" sube.',
'removingchecked'       => 'Forprenas elektitajn...',
'couldntremove'         => 'Neeblas forigi titolon "$1"...',
'iteminvalidname'       => 'Ia eraro pri "$1", nevalida titolo...',
'wlnote'                => 'Jen la plej lastaj $1 redaktoj dum la lastaj <b>$2</b> horoj.',
'wlshowlast'            => 'Montru el lastaj $1 horoj $2 tagoj $3',
'wlsaved'               => 'Jen konservita versio de via atentaro.',
'wlhideshowown'         => '$1 miajn redaktojn.',
'wlhideshowbots'        => '$1 robotajn redaktojn',
'wldone'                => 'Farita.',
'enotif_mailer'         => 'Averta retmesaĝo de {{SITENAME}}',
'enotif_reset'          => 'Marku ĉiujn vizititajn paĝojn',
'enotif_newpagetext'    => 'Tiu ĉi estas nova paĝo',
'changed'               => 'ŝanĝita',
'enotif_subject'        => 'la paĝo $PAGETITLE de {{SITENAME}} estis $CHANGEDORCREATED de $PAGEEDITOR',
'enotif_lastvisited'    => 'Vidu $1 por ĉiuj ŝanĝoj de post via lasta vizito.',
'enotif_body'           => 'Kara $WATCHINGUSERNAME,

la paĝo $PAGETITLE de {{SITENAME}} estis $CHANGEDORCREATED je $PAGEEDITDATE de $PAGEEDITOR, vidu {{fullurl:$PAGETITLE RAWURL}} por la nuna versio.

$NEWPAGE

Redakta resumo : $PAGESUMMARY $PAGEMINOREDIT

Kontaktu la redaktinton:
retpoŝto {{fullurl:Special:Emailuser/$PAGEEDITOR RAWURL}}
vikio {{fullurl:User:$PAGEEDITOR RAWURL}}

Ne estos aliaj avertoj kaze de sekvaj ŝanĝoj krom se vi vizitas la paĝon. Vi povas ankaŭ malaktivigi la avertsignalon por ĉiuj priatentitaj paĝoj de via atentaro.

             Sincere via, la avertsistemo de {{SITENAME}}

--
Por ŝanĝi la elektojn de via atentaro, bv viziti
{{fullurl:Special:Watchlist/edit}}

Reagoj kaj plia helpo :
{{fullurl:Help:Enhavo}}',
'deletepage'            => 'Forigu paĝon',
'confirm'               => 'Konfirmu',
'excontent'             => 'enhavis: \'$1\'',
'excontentauthor'       => 'la enteno estis : \'$1\' (kaj la sola kontribuinto estis \'$2\')',
'exbeforeblank'         => 'antaŭ malplenigo enhavis: \'$1\'',
'exblank'               => 'estis malplena',
'confirmdelete'         => 'Konfirmu forigadon',
'deletesub'             => '(Forigas "$1")',
'historywarning'        => 'Averto: la forigota paĝo havas historion:',
'confirmdeletetext'     => 'Vi forigos la artikolon aŭ dosieron kaj forviŝos ĝian tutan historion el la datumaro.<br /> Bonvolu konfirmi, ke vi vere intencas tion, kaj ke vi komprenas la sekvojn, kaj ke vi ja sekvas la [[Project:Reguloj pri forigado|regulojn pri forigado]].',
'actioncomplete'        => 'Ago farita',
'deletedtext'           => '"$1" estas forigita.
Vidu la paĝon $2 por registro de lastatempaj forigoj.',
'deletedarticle'        => 'forigis "$1"',
'dellogpage'            => 'Loglibro de forigoj',
'dellogpagetext'        => 'Jen listo de la plej lastaj forigoj el la datumaro.
Ĉiuj tempoj sekvas la horzonon UTC.
<ul>
</ul>',
'deletionlog'           => 'listo de forigoj',
'reverted'              => 'Restarigis antaŭan version',
'deletecomment'         => 'Kialo por forigo',
'imagereverted'         => 'Restarigo de antaŭa versio sukcesis.',
'rollback'              => 'Restarigu antaŭan redakton',
'rollback_short'        => 'Restarigo',
'rollbacklink'          => 'restarigu antaŭan',
'rollbackfailed'        => 'Restarigo malsukcesis',
'cantrollback'          => 'Neeblas restarigi antaŭan redakton; la redaktinto lasta estas la sola de la paĝo.',
'alreadyrolled'         => 'Ne eblas restarigi la lastan redakton de [[:$1]] de la [[User:$2|$2]] ([[User talk:$2|diskuto]]) pro tio, ke oni intertempe redaktis la paĝon. La lasta redaktinto estas [[User:$3|$3]] ([[User talk:$3|diskuto]]).',
'editcomment'           => 'La komento estis: \'<i>$1</i>\'.',
'revertpage'            => 'Restarigis redaktojn de [[Special:Contributions/$2|$2]] ([[User talk:$2|diskuto]]); restarigis al la lasta versio de [[User:$1|$1]]',
'sessionfailure'        => 'Ŝajnas ke estas problemo kun via ensalutado;
Ĉi ago estis nuligita por malhelpi fiensalutadon.
Bonvolu alklalki la reirbutonon kaj reŝarĝi la paĝon el kiu vi venas, kaj provu denove.',
'protectlogpage'        => 'Protektloglibro',
'protectlogtext'        => 'Sube estas listo de paĝ-ŝlosoj kaj malŝlosoj.
Vidu [[Project:Ŝlositaj paĝoj]] por pli da informoj.',
'protectedarticle'      => 'protektita [[:$1]]',
'unprotectedarticle'    => 'malprotektita [[$1]]',
'protectsub'            => '(Protektante "$1")',
'confirmprotecttext'    => 'Ĉu vi vere volas protekti ĉi paĝon ?',
'confirmprotect'        => 'Konfirmu protektadon',
'protectmoveonly'       => 'Protektu nur kontraŭ movoj',
'protectcomment'        => 'Kialo por protekti',
'unprotectsub'          => '(Malprotektanta "$1")',
'confirmunprotecttext'  => 'Ĉu vi vere volas malprotekti ĉi paĝon ?',
'confirmunprotect'      => 'Konfirmu malprotektadon',
'unprotectcomment'      => 'Kialo de malprotekto',
'protect-unchain'       => 'Malŝlosu movpermesojn',
'protect-text'          => 'Vi povas ĉi tie vidi kaj ŝanĝi la protektnivelon de la paĝo [[$1]]. Bonvolu certiĝi ke vi respektas la [[Project:Protektitaj paĝoj|gvidliniojn de la projekto]].',
'protect-viewtext'      => 'Via konto ne havas rajtojn por ŝanĝi la protektnivelon de la paĝo. Jen la nunaj ecoj <!--  settings --> por la paĝo [[$1]]',
'protect-default'       => '(defaŭlte)',
'protect-level-autoconfirmed'=> 'Bloki neensalutintajn uzantojn',
'protect-level-sysop'   => 'Nur administrantoj',
'undelete'              => 'Restarigu forigitan paĝon',
'undeletepage'          => 'Montru kaj restarigu forigitajn paĝojn',
'viewdeletedpage'       => 'Rigardu forigitajn paĝojn',
'undeletepagetext'      => 'La jenaj paĝoj estis forigitaj, sed ankoraŭ restas arkivitaj,
kaj oni povas restarigi ilin. La arkivo povas esti malplenigita periode.',
'undeleteextrahelp'     => 'Por restarigi la tuton de la paĝo, marku neniun markobutonon kaj klaku la butonon \'\'\'\'\'Restarigu\'\'\'\'\'. Por restarigi selektitajn versiojn de la paĝo, marku la butonojn konformajn al la dezirataj versioj, kaj klaku la butonon \'\'\'\'\'Restarigu\'\'\'\'\'. Klako je \'\'\'\'\'Restarigu\'\'\'\'\' malplenigos la komentozonon kaj malmarkos ĉiujn la markobutonojn.',
'undeletearticle'       => 'Restarigu forigitan artikolon',
'undeleterevisions'     => '$1 versioj arkivitaj',
'undeletehistory'       => 'Se vi restarigos la paĝon, ĉiuj versioj estos restarigitaj
en la historio. Se nova paĝo kun la sama nomo estis kreita post la forigo, la restarigitaj
versioj aperos antaŭe en la historio, kaj la aktuala versio ne estos anstataŭigita.',
'undeletehistorynoadmin'=> 'Ĉi tiu artikolo estis forigita. La kaŭzo por la forigo estas montrata en la malsupra resumo, kune kun detaloj pri la uzantoj, kiuj redaktis ĉi tiun paĝon antaŭ la forigo. La aktuala teksto de ĉi tiuj forigitaj revizioj estas atingebla nur por administrantoj.',
'undeleterevision'      => 'Forigita versio de $1',
'undeletebtn'           => 'Restarigu!',
'undeletereset'         => 'Reŝarĝu',
'undeletecomment'       => 'Komento:',
'undeletedarticle'      => 'restarigis "$1"',
'undeletedrevisions'    => '$1 restarigita(j) versio(j)',
'undeletedrevisions-files'=> '$1 revizioj kaj $2 dosiero(j) restarigitaj',
'undeletedfiles'        => '$1 dosiero(j) restarigita(j)',
'undeletedpage'         => '<big>\'\'\'$1 estis restarigita\'\'\'</big>

Konsultu la [[Special:Log/delete|deletion log]] por protokolo pri la lastatempaj forigoj kaj restarigoj.',
'namespace'             => 'Nomspaco:',
'invert'                => 'Inversu selektaĵon',
'contributions'         => 'Kontribuoj de uzanto',
'mycontris'             => 'Miaj kontribuoj',
'contribsub'            => 'De $1',
'nocontribs'            => 'Trovis neniajn redaktojn laŭ tiu kriterio.',
'ucnote'                => 'Jen la <b>$1</b> lastaj redaktoj de tiu uzanto dum la <b>$2</b> lastaj tagoj.',
'uclinks'               => 'Montru la $1 lastajn redaktojn; montru la $2 lastajn tagojn.',
'uctop'                 => ' (lasta)',
'newbies'               => 'novaĵoj',
'sp-newimages-showfrom' => 'Montru novajn bildojn komencante de $1',
'sp-contributions-newest'=> 'Plej novaj',
'sp-contributions-oldest'=> 'Plej malnovaj',
'sp-contributions-newer'=> '$1 pli novajn',
'sp-contributions-older'=> '$1 pli malnovajn',
'sp-contributions-newbies-sub'=> 'Kontribuoj de novaj uzuloj. Forigitaj paĝoj ne estas montritaj.',
'whatlinkshere'         => 'Ligiloj ĉi tien',
'notargettitle'         => 'Sen celpaĝo',
'notargettext'          => 'Vi ne precizigis, kiun paĝon aŭ uzanton priumi.',
'linklistsub'           => '(Listo de ligiloj)',
'linkshere'             => 'La jenaj paĝoj ligas ĉi tien:',
'nolinkshere'           => 'Neniu paĝo ligas ĉi tien.',
'isredirect'            => 'alidirekto',
'blockip'               => 'Forbaru IP-adreson/nomon',
'blockiptext'           => 'Per jena formularo vi povas forpreni de ajna nomo aŭ IP-adreso la rajton skribi en la vikio. Oni faru tion \'\'nur\'\' por eviti vandalismon, kaj sekvante la [[Project:Reguloj pri forbarado|regulojn pri forbarado]]. Klarigu la precizan kialon malsupre (ekzemple, citu paĝojn, kiuj estis vandaligitaj).',
'ipaddress'             => 'IP-adreso/nomo',
'ipadressorusername'    => 'IP adreso aŭ uzula nomo',
'ipbexpiry'             => 'Blokdaŭro',
'ipbreason'             => 'Kialo',
'ipbsubmit'             => 'Forbaru la adreson',
'ipbother'              => 'Alia daŭro',
'ipboptions'            => '2 horoj:2 hours,1 tago:1 day,3 tagoj:3 days,1 semajno:1 week,2 semajnoj:2 weeks,1 monato:1 month,3 monatoj:3 months,6 monatoj:6 months,1 jaro:1 year,porĉiam:infinite',
'ipbotheroption'        => 'alia',
'badipaddress'          => 'Neniu uzanto, aŭ la IP-adreso estas misformita.',
'blockipsuccesssub'     => 'Oni sukcese forbaris la adreson/nomon.',
'blockipsuccesstext'    => '"$1" estas forbarita. <br />Vidu la [[Special:Ipblocklist|liston de IP-forbaroj]].',
'unblockip'             => 'Malforbaru IP-adreson/nomon',
'unblockiptext'         => 'Per la jena formulo vi povas repovigi al iu
forbarita IP-adreso/nomo la povon enskribi en la vikio.',
'ipusubmit'             => 'Malforbaru la adreson',
'ipblocklist'           => 'Listo de forbaritaj IP-adresoj/nomoj',
'blocklistline'         => 'Je $1, $2 forbaris $3 ($4)',
'infiniteblock'         => 'senfina',
'expiringblock'         => 'finiĝas je $1',
'ipblocklistempty'      => 'La blokada listo estas malplena.',
'blocklink'             => 'forbaru',
'unblocklink'           => 'malforbaru',
'contribslink'          => 'kontribuoj',
'autoblocker'           => 'Provizore forbarita aŭtomate pro tio, ke vi uzas la saman [[IP-adreso]]n kiel "$1", kiu estis forbarita pro : "$2".',
'blocklogpage'          => 'Forbarlibro',
'blocklogentry'         => 'forbaris "$1" por daŭro de "$2"',
'blocklogtext'          => 'Ĉi tio estas loglibro pri uzanto-forbaraj kaj malforbaraj agoj. Aŭtomate forbaritaj IP adresoj ne estas listigitaj. Vidu la [[Special:Ipblocklist|IP forbarliston]] por ĉi-momente fobaritaj uzulantoj kaj IPoj.',
'unblocklogentry'       => '$1 estis malbarita',
'ipb_expiry_invalid'    => 'Nevalida blokdaŭro.',
'lockdb'                => 'Ŝlosi datumaron',
'unlockdb'              => 'Malŝlosi datumaron',
'lockdbtext'            => 'Se vi ŝlosos la datumaron, tio malebligos al ĉiuj uzantoj
redakti paĝojn, ŝanĝi preferojn, priumi atentarojn, kaj fari diversajn aliajn
aferojn, por kiuj nepras ŝanĝi la datumaron.
Bonvolu certigu, ke vi efektive intencas tion fari, kaj ke vi ja malŝlosos
la datumaron post ol vi finos vian riparadon.',
'unlockdbtext'          => 'Se vi malŝlosos la datumaron, tio reebligos al ĉiuj uzantoj
redakti paĝojn, ŝanĝi preferojn, priumi la atentaron, kaj fari aliajn aferojn,
por kiuj nepras ŝanĝi al la datumaro.
Bonvolu certigu, ke vi efektive intencas tion fari.',
'lockconfirm'           => 'Jes, mi vere volas ŝlosi la datumaron.',
'unlockconfirm'         => 'Jes, mi vere volas malŝlosi la datumaron.',
'lockbtn'               => 'Ŝlosi datumaron',
'unlockbtn'             => 'Malŝlosi datumaron',
'locknoconfirm'         => 'Vi ne konfirmis.',
'lockdbsuccesssub'      => 'Datumaro ŝlosita',
'unlockdbsuccesssub'    => 'Datumaro malŝlosita',
'lockdbsuccesstext'     => 'La datumaro de {{SITENAME}} estas ŝlosita.
<br />Ne forgesu malŝlosi ĝin post kiam vi finos la riparadon.',
'unlockdbsuccesstext'   => 'La datumaro de {{SITENAME}} estas malŝlosita.',
'makesysoptitle'        => 'Igu uzanton administranto',
'makesysoptext'         => 'Ĉi formularo estas uzita de burokratoj por igi ordinarajn uzantojn administrantoj. 
Bonvlolu tajpi la nomon de la uzanto en la skatoleton kaj premu la butonon por igi la uzanton administranto.',
'makesysopname'         => 'Nomo de la uzanto :',
'makesysopsubmit'       => 'Igu ĉi uzanton administranto',
'makesysopok'           => '<b>Uzanto "$1" nun estas administranto</b>',
'makesysopfail'         => '<b>Uzanto "$1" ne povis esti admnistrantigita. (Ĉu vi ĝuste tajis ties nomon ?)</b>',
'setbureaucratflag'     => 'Aldonu burokratan markilon',
'rightslogtext'         => 'Ĉi tio estas loglibro de uzulaj rajtŝanĝoj.',
'rightslogentry'        => 'ŝanĝis grupan membrecon por $1 de $2 al $3',
'rights'                => 'Rajtoj:',
'set_user_rights'       => 'Ŝanĝu uzulajn rajtojn',
'user_rights_set'       => '<b>Uzulaj rajtoj por "$1" ĝisdatigitaj</b>',
'set_rights_fail'       => '<b>Uzantorajtoj por "$1" ne povis esti difinataj. (Ĉu vi entajpis la nomon korekte?)</b>',
'makesysop'             => 'Igu uzanton administranto',
'already_sysop'         => 'Tiu ĉi uzanto jam estas administranto.',
'already_bureaucrat'    => 'Tiu ĉi uzanto jam estas burokrato',
'movepage'              => 'Movu paĝon',
'movepagetext'          => 'Per la jena formulo vi povas ŝanĝi la nomon de iu paĝo, kunportante
ĝian historion de redaktoj je la nova nomo.
La antaŭa titolo fariĝos alidirektilo al la nova titolo.
Ligiloj al la antaŭa titolo <i>ne</i> estos ŝanĝitaj; uzu
la riparilojn kaj zorgilojn por certigi,
ke ne restos duoblaj aŭ fuŝitaj alidirektiloj.
Kiel movanto, vi respondecas pri ĝustigado de fuŝitaj ligiloj.

Notu, ke la paĝo \'\'\'ne\'\'\' estos movita se jam ekzistas paĝo
ĉe la nova titolo, krom se ĝi estas malplena aŭ alidirektilo
al ĉi tiu paĝo, kaj sen antaŭa redaktohistorio. Pro tio, vi ja
povos removi la paĝon je la antaŭa titolo se vi mistajpus, kaj
neeblas ke vi neintence forviŝus ekzistantan paĝon per movo.

<b>AVERTO!</b>
Tio povas esti drasta kaj neatendita ŝanĝo por populara paĝo;
bonvolu certigi vin, ke vi komprenas ties konsekvencojn antaŭ
ol vi antaŭeniru.',
'movepagetalktext'      => 'La movo aŭtomate kunportos la diskuto-paĝon, se tia ekzistas, \'\'\'krom se:\'\'\'
*Vi movas la paĝon tra nomspacoj (ekz de \'\'Nomo\'\' je \'\'User:Nomo\'\'),
*Ne malplena diskuto-paĝo jam ekzistas je la nova nomo, aŭ
*Vi malelektas la suban ŝaltilon.

Tiujokaze, vi nepre permane kunigu la diskuto-paĝojn se vi tion deziras.',
'movearticle'           => 'Movu paĝon',
'movenologin'           => 'Ne ensalutinta',
'movenologintext'       => 'Vi nepre estu registrita uzanto kaj [[Special:Userlogin|ensalutu]] por rajti movi paĝojn.',
'newtitle'              => 'Al nova titolo',
'movepagebtn'           => 'Movu paĝon',
'pagemovedsub'          => 'Sukcesis movi',
'pagemovedtext'         => 'Paĝo "[[$1]]" estas movita al "[[$2]]".',
'articleexists'         => 'Paĝo kun tiu nomo jam ekzistas, aŭ la nomo kiun vi elektis ne validas.
Bonvolu elekti alian nomon.',
'talkexists'            => 'Oni ja sukcesis movi la paĝon mem, sed
ne movis la diskuto-paĝon ĉar jam ekzistas tia ĉe la nova titolo.
Bonvolu permane kunigi ilin.',
'movedto'               => 'movita al',
'movetalk'              => 'Movu ankaŭ la "diskuto"-paĝon, se ĝi ekzistas.',
'talkpagemoved'         => 'Ankaŭ la diskutpaĝo estas movita.',
'talkpagenotmoved'      => 'La diskutpaĝo <strong>ne</strong> estas movita.',
'1movedto2'             => '[[:$1|$1]] movita al [[:$2|$2]]',
'1movedto2_redir'       => '[[:$1|$1]] movita al [[:$2|$2]], redirekto lasita',
'movelogpage'           => 'Loglibro de paĝmovoj',
'movelogpagetext'       => 'Jen listo de movitaj paĝoj',
'movereason'            => 'Kialo',
'revertmove'            => 'restarigu',
'delete_and_move'       => 'Forigu kaj movu',
'delete_and_move_text'  => '==Forigo nepras==

La celartikolo "[[$1]]" jam ekzistas. Ĉu vi volas forigi ĝin por krei spacon por la movo?',
'delete_and_move_confirm'=> 'Jes, forigu la paĝon',
'delete_and_move_reason'=> 'Forigita por ebligi movon',
'selfmove'              => 'Font- kaj cel-titoloj samas; ne eblas movi paĝon sur ĝin mem.',
'immobile_namespace'    => 'La celtitolo estas de speciala speco; ne eblas movi paĝojn en tiun nomspacon.',
'export'                => 'Eksportu paĝojn',
'exporttext'            => 'Vi povas eksporti la tekston kaj la redaktohistorion de aparta paĝo aŭ de paĝaro kolektita en ia XML ; tio povas esti importita en alian programon funkciantan per MediaWiki-softvaro, ŝanĝita, aŭ nur prenita por propra privata uzo.',
'exportcuronly'         => 'Entenas nur la aktualan version, ne la malnovajn.',
'allmessages'           => 'Ĉiuj mesaĝoj',
'allmessagesname'       => 'Nomo',
'allmessagesdefault'    => 'Defaŭlta teksto',
'allmessagescurrent'    => 'Nuna teksto',
'allmessagestext'       => 'Ĉi tio estas listo de ĉiuj mesaĝoj haveblaj en la MediaWiki: nomspaco',
'allmessagesnotsupportedUI'=> 'La nuna lingvo de interfaco <b>$1</b> ne estas subtenata en Special:Allmessages de tiu ĉi paĝaro.',
'allmessagesnotsupportedDB'=> 'Speciala:Allmessages ne subtenata ĉar la variablo wgUseDatabaseMessages estas malkonektita.',
'allmessagesfilter'     => 'Filtrilo laŭ racia esprimo :',
'allmessagesmodified'   => 'Montru nur ŝanĝitajn',
'thumbnail-more'        => 'Pligrandigu',
'missingimage'          => '<b>Mankanta bildo</b><br /><i>$1</i>',
'filemissing'           => 'Mankanta dosiero',
'thumbnail_error'       => 'Okazis eraro kreante antaŭvidan bildeton: $1',
'import'                => 'Importitaj paĝoj',
'importinterwiki'       => 'Transvikia importo',
'importtext'            => 'Bonvole eksportu la dosieron el la fonta vikio per la ilo Speciala:Export, konservu ĝin sur via disko kaj poste alŝutu ĝin tien ĉi.',
'importfailed'          => 'Malsukcesis la importo: $1',
'importnotext'          => 'Malplena aŭ senteksta',
'importsuccess'         => 'La importo sukcesis!',
'importhistoryconflict' => 'Malkongrua historia versio ekzistas (eble la paĝo importiĝis antaŭe)',
'importnosources'       => 'Neniu transvikia importfonto estis difinita kaj rekta historio de alŝutoj estas malaktivigita.',
'tooltip-search'        => 'Traserĉu ĉi tiun vikion [alt-f]',
'tooltip-minoredit'     => 'Marku tiun ŝanĝon kiel malgrava [alt-i]',
'tooltip-save'          => 'Konservu viajn ŝanĝojn [alt-s]',
'tooltip-preview'       => 'Antaŭrigardu viajn ŝanĝojn. Bonvolu uzi tion antaŭ ol konservi ilin! [alt-p]',
'tooltip-diff'          => 'Show which changes you made to the text. [alt-v]',
'tooltip-compareselectedversions'=> 'Vidu la malsamojn inter ambaŭ selektitaj versioj de ĉi paĝo. [alt-v]',
'tooltip-watch'         => 'Aldonu ĉi paĝon al via atentaro [alt-w]',
'Monobook.css'          => '/* CSS placed here will affect users of the Monobook skin */',
'anonymous'             => 'Anonima(j) uzanto(j) de {{SITENAME}}',
'siteuser'              => '{{SITENAME}} uzanto $1',
'lastmodifiedatby'        => 'Ĉi paĝo estis laste ŝanĝita je $2, $1 de $3.',
'and'                   => 'kaj',
'othercontribs'         => 'Bazita sur la laboro de $1.',
'others'                => 'aliaj',
'siteusers'             => '{{SITENAME}} uzanto(j) $1',
'spamprotectiontitle'   => 'Filtrilo kontraŭ spamo',
'spamprotectiontext'    => 'La paĝo kiun vi trovis konservi estis blokita per la spam-filtrilo. Ĉi tia eraro estas kaŭzata pro ekstera ligilo al malpermesata retejo.',
'spamprotectionmatch'   => 'La jena teksto ekagigis la spam-filtrilon: $1',
'subcategorycount'      => 'Estas {{PLURAL:$1|unu subkategorio|$1 subkategorioj}} en tiu kategorio.',
'categoryarticlecount'  => 'Estas {{PLURAL:$1|unu artikolo|$1 artikoloj}} en tiu kategorio.',
'listingcontinuesabbrev'=> ' daŭrigo',
'spambot_username'      => 'Trudmesaĝa forigo de MediaWiki',
'spam_reverting'        => 'Restarigo de lasta versio ne entenante ligilojn al $1',
'spam_blanking'         => 'Forviŝo de ĉiuj versioj entenate ligilojn al $1',
'infosubtitle'          => 'Informoj por paĝo',
'mw_math_png'           => 'Ĉiam krei PNG-bildon',
'mw_math_simple'        => 'HTMLigu se simple, aŭ PNG',
'mw_math_html'          => 'HTMLigu se eble, aŭ PNG',
'mw_math_source'        => 'Lasu TeX-fonton (por tekstfoliumiloj)',
'mw_math_modern'        => 'Rekomendita por modernaj foliumiloj',
'mw_math_mathml'        => 'MathML seeble (provizora)',
'markaspatrolleddiff'   => 'Marku kiel patrolita',
'markaspatrolledtext'   => 'Marku ĉi artikolon patrolita',
'markedaspatrolled'     => 'Markita kiel patrolita',
'markedaspatrolledtext' => 'La elektita versio estas markita kiel patrolita.',
'rcpatroldisabled'      => 'Patrolado de lastaj ŝanĝoj malaktivigita',
'rcpatroldisabledtext'  => 'La funkcio patrolado de la lastaj ŝanĝoj estas nun malaktivigita.',
'Monobook.js'           => '/* iletikedoj kaj rektaj klavoj */   
 var ta = new Object();
 ta[\'pt-userpage\'] = new Array(\'.\',\'Mia uzantopaĝo\');
 ta[\'pt-anonuserpage\'] = new Array(\'.\',\'La uzantopaĝo por la IP adreso sub kiu vi estas redaktanta\');  
 ta[\'pt-mytalk\'] = new Array(\'n\',\'Mia diskutpaĝo\');
 ta[\'pt-anontalk\'] = new Array(\'n\',\'Diskuto pri redaktoj sub tiu ĉi IP adreso\');
 ta[\'pt-preferences\'] = new Array(\'\',\'Miaj preferoj\');
 ta[\'pt-watchlist\'] = new Array(\'l\',\'Listo de paĝoj kies ŝanĝojn vi priatentas.\');
 ta[\'pt-mycontris\'] = new Array(\'y\',\'Listo de miaj kontribuoj\');
 ta[\'pt-login\'] = new Array(\'o\',\'Vi estas invitita ensaluti, tamen ne estas devige.\');
 ta[\'pt-anonlogin\'] = new Array(\'o\',\'Vi estas invitita ensaluti, tamen ne estas devige.\');
 ta[\'pt-logout\'] = new Array(\'o\',\'Elsalutu\');
 ta[\'ca-talk\'] = new Array(\'t\',\'Diskuto pri la artikolo\');
 ta[\'ca-edit\'] = new Array(\'e\',\'Vi povas redakti tiun ĉi paĝon. Bv uzi la antaŭvidbutonon antaŭ ol konservi.\');
 ta[\'ca-addsection\'] = new Array(\'+\',\'Aldonu komenton al tiu diskuto.\');
 ta[\'ca-viewsource\'] = new Array(\'e\',\'Tiu paĝo estas protektita. Vi povas nur rigardi ties fonton.\'); 
 ta[\'ca-history\'] = new Array(\'h\',\'Antaŭaj versioj de tiu ĉi paĝo.\');
 ta[\'ca-protect\'] = new Array(\'=\',\'Protektu tiun ĉi paĝon\');
 ta[\'ca-delete\'] = new Array(\'d\',\'Forigu tiun ĉi paĝon\');
 ta[\'ca-undelete\'] = new Array(\'d\',\'Restarigu la redaktojn faritajn al tiu ĉi paĝo antaŭ ties forigo\');
 ta[\'ca-move\'] = new Array(\'m\',\'Movu tiun ĉi paĝon\');
 ta[\'ca-nomove\'] = new Array(\'\',\'Vi ne rajtas movi tiun ĉi paĝon\');
 ta[\'ca-watch\'] = new Array(\'w\',\'Aldonu tiun ĉi paĝon al via atentaro\');
 ta[\'ca-unwatch\'] = new Array(\'w\',\'Forigu tiun ĉi paĝon el via atentaro\');
 ta[\'search\'] = new Array(\'f\',\'Traserĉu tiun ĉi vikion\');
 ta[\'p-logo\'] = new Array(\'\',\'Ĉefpaĝo\');
 ta[\'n-mainpage\'] = new Array(\'z\',\'Vizitu la Ĉefpaĝon\');
 ta[\'n-portal\'] = new Array(\'\',\'Pri la projekto, kion vi povas fari, kie vi povas trovi ion\');
 ta[\'n-currentevents\'] = new Array(\'\',\'Trovu fonajn informojn pri nunaj eventoj\');
 ta[\'n-recentchanges\'] = new Array(\'r\',\'Listo de la lastaj ŝanĝoj en la vikio.\');
 ta[\'n-randompage\'] = new Array(\'x\',\'Vidu hazardan paĝon\');
 ta[\'n-help\'] = new Array(\'\',\'Serĉopaĝo.\');
 ta[\'n-sitesupport\'] = new Array(\'\',\'Subtenu nin per mono\');
 ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Listo de ĉiuj vikiaj paĝoj kij ligas ĉi tien\');
 ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Lastaj ŝanĝoj en paĝoj kiuj ligas al tiu ĉi paĝo\');
 ta[\'feed-rss\'] = new Array(\'\',\'RSS-fonto por tiu ĉi paĝo\');
 ta[\'feed-atom\'] = new Array(\'\',\'Atom-fonto por ĉi paĝo\');
 ta[\'t-contributions\'] = new Array(\'\',\'Vidu la liston de kontribuoj de tiu ĉi uzanto\');
 ta[\'t-emailuser\'] = new Array(\'\',\'Sendu retmesaĝon al tiu ĉi uzanto\');
 ta[\'t-upload\'] = new Array(\'u\',\'Alŝutu bildojn aŭ dosierojn\');
 ta[\'t-specialpages\'] = new Array(\'q\',\'Listo de ĉiuj specialaj paĝoj\');
 ta[\'ca-nstab-main\'] = new Array(\'c\',\'Vidu la artikolon\');
 ta[\'ca-nstab-user\'] = new Array(\'c\',\'Vidu la personan paĝon de la uzanto\');
 ta[\'ca-nstab-media\'] = new Array(\'c\',\'Vidu la paĝon de la dosiero\');
 ta[\'ca-nstab-special\'] = new Array(\'\',\'Estas speciala paĝo, vi ne rajtas redakti ĝin.\');
 ta[\'ca-nstab-project\'] = new Array(\'a\',\'Vidu la paĝon de la projekto\');
 ta[\'ca-nstab-image\'] = new Array(\'c\',\'Vidu la paĝon de la bildo\');
 ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Vidu la sisteman mesaĝon\');
 ta[\'ca-nstab-template\'] = new Array(\'c\',\'Vidu la ŝablonon\');
 ta[\'ca-nstab-help\'] = new Array(\'c\',\'Vidu la helppaĝon\');
 ta[\'ca-nstab-category\'] = new Array(\'c\',\'Vidu la paĝon de kategorioj\');',
'deletedrevision'       => 'Forigita malnova versio $1.',
'previousdiff'          => '← Iru al antaŭa ŝanĝo',
'nextdiff'              => 'Iru al sekvanta ŝanĝo →',
'imagemaxsize'          => 'Elmontru bildojn en bildpriskribaj paĝoj je maksimume :',
'thumbsize'             => 'Grandeco de bildetoj :',
'showbigimage'          => 'Elŝutu version altdistingive ($1 X $2, $3 KB)',
'newimages'             => 'Aro da novaj bildoj',
'noimages'              => 'Nenio videbla.',
'specialloguserlabel'   => 'Uzanto:',
'speciallogtitlelabel'  => 'Titolo:',
'passwordtooshort'      => 'Via pasvorto estas tro mallonga. Ĝi entenu minimume $1 karaktrojn.',
'mediawarning'          => '\'\'\'Warning\'\'\': This file may contain malicious code, by executing it your system may be compromised.
<hr />',
'metadata-expand'       => 'Montru etendajn detalojn',
'metadata-collapse'     => 'Kaŝu etendajn detalojn',
'exif-artist'           => 'Kreinto',
'exif-pixelxdimension'  => 'Valind image height',
'exif-aperturevalue'    => 'Aperturo',
'exif-brightnessvalue'  => 'Heleco',
'exif-contrast'         => 'Kontrasto',
'exif-componentsconfiguration-0'=> 'ne ekzistas',
'edit-externally'       => 'Ŝanĝu ĉi dosieron per ekstera softvaro',
'edit-externally-help'  => 'Vidu la [http://meta.wikimedia.org/wiki/Help:External_editors instalinstrukciojn] por pliaj informoj \'\'(angle)\'\'.',
'recentchangesall'      => 'ĉiuj',
'imagelistall'          => 'ĉiuj',
'watchlistall1'         => 'ĉiuj',
'watchlistall2'         => 'ĉiuj',
'namespacesall'         => 'ĉiuj',
'confirmemail'          => 'Konfirmu retpoŝtadreson',
'confirmemail_text'     => 'Ĉi tiu vikio postulas ke vi validigu vian retadreson antaŭ ol uzadi la retmesaĝpreferojn. Bonvolu alklaki la suban butonon por sendi konfirmesaĝon al via adreso. La mesaĝo entenos ligilon kun kodo; bonvolu alŝuti la ligilon en vian foliumilon por konfirmi ke via retadreso validas.',
'confirmemail_send'     => 'Retmesaĝi konfirmkodon',
'confirmemail_sent'     => 'Konfirma retmesaĝo estas sendita.',
'confirmemail_sendfailed'=> 'Ne eblis sendi konfirmretmesaĝon. Bonvolu kontroli ĉu en la adreso ne estus nevalidaj karaktroj.',
'confirmemail_invalid'  => 'Nevalida konfirmkodo. La kodo eble ne plu validas.',
'confirmemail_needlogin'=> 'Vi devas $1 por konfirmi vian retpoŝtan adreson.',
'confirmemail_success'  => 'Via retadreso estas konfirmita. Vi povas nun ensaluti kaj ĝui la vikion.',
'confirmemail_loggedin' => 'Via retadreso estas nun konfirmita.',
'confirmemail_error'    => 'Io misokazis dum konservo de via konfirmo.',
'confirmemail_body'     => 'Iu, verŝajne vi ĉe la IP-adreso $1, enregistrigis per tiu 
ĉi retpoŝtadreso la konton "$2" ĉe {{SITENAME}}.

Malfermu tiun ĉi ligon en via retumilo, por konfirmi ke la
konto ja apartenas al vi kaj por malŝlosi retpoŝtajn
kapablojn ĉe {{SITENAME}}:

$3

Se vi ne mendis ĉi tiun mesaĝon, ne alklaku la ligon. Tiu
ĉi konfirmokodo eksvalidiĝos je $4.',
'tryexact'              => 'Provu ekzaktan trafon',
'searchfulltext'        => 'Serĉu plentekste',
'createarticle'         => 'Kreu artikolon',
'scarytranscludetoolong'=> '[Bedaŭrinde la URL estas tro longa]',
'trackbackbox'          => '<div id=\'mw_trackbacks\'>
Postspuroj por ĉi artikolo:<br />p
$1
</div>',
'trackbackremove'       => ' ([$1 Forigu])',
'trackbacklink'         => 'Postspurado',
'trackbackdeleteok'     => 'La postspurado esti sukcese forigita.',
'deletedwhileediting'   => 'Averto: Oni forigis ĉi tiun paĝon post tiam, kiam vi ekredaktis ĝin!',
'recreate'              => 'Rekreu',
'redirectingto'         => 'Redirektante al [[:$1]]...',
'confirm_purge'         => 'Ĉu forviŝiĝu la enhavo de tiu ĉi paĝo?

$1',
'confirm_purge_button'  => 'Bone',
'youhavenewmessagesmulti'=> 'Vi havas novajn mesaĝojn ĉe $1',
'articletitles'         => 'Artikoloj komencante de \'\'$1\'\'',
'hideresults'           => 'Kaŝu rezultojn',
);
?>
