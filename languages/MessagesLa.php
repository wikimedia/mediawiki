<?php
/** Latin (lingua Latina)
  *
  * @package MediaWiki
  * @subpackage Language
  */

$quickbarSettings = array(
	'Nullus', 'Constituere a sinistra', 'Constituere a dextra', 'Innens a sinistra'
);

$skinNames = array(
	'standard' => 'Norma',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Caerulus Colonia'
);

$namespaceNames = array(
	NS_SPECIAL        => 'Specialis',
	NS_MAIN           => '',
	NS_TALK           => 'Disputatio',
	NS_USER           => 'Usor',
	NS_USER_TALK      => 'Disputatio_Usoris',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Disputatio_{{grammar:genitive|$1}}',
	NS_IMAGE          => 'Imago',
	NS_IMAGE_TALK     => 'Disputatio_Imaginis',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Disputatio_MediaWiki',
	NS_TEMPLATE       => 'Formula',
	NS_TEMPLATE_TALK  => 'Disputatio_Formulae',
	NS_HELP           => 'Auxilium',
	NS_HELP_TALK      => 'Disputatio_Auxilii',
	NS_CATEGORY       => 'Categoria',
	NS_CATEGORY_TALK  => 'Disputatio_Categoriae',
);

$messages = array(
'tog-underline'         => 'Subscribere nexi',
'tog-highlightbroken'   => 'Formare nexos fractos <a href="" class="new">sici</a> (alioqui: sic<a href="" class="internal">?</a>).',
'tog-justify'           => 'Saepire capites',
'tog-hideminor'         => 'Celare mutationes recentes minores',
'tog-usenewrc'          => 'Mutationes recentes amplificatae (non efficit in tota navigatra)',
'tog-numberheadings'    => 'Numerare indices necessario',
'tog-editondblclick'    => 'Premere bis ut paginam recensere (uti JavaScript)',
'tog-rememberpassword'  => 'Recordari tesserae inter conventa (uti cookies)',
'tog-editwidth'         => 'Capsa recensitorum totam latitudinem habet',
'tog-watchdefault'      => 'Custodire res novas et mutatas',
'tog-minordefault'      => 'Notare totas mutations ut minor',
'tog-previewontop'      => 'Monstrare praevisus ante capsam recensiti, non post ipsam',
'sunday'                => 'dies Solis',
'monday'                => 'dies Lunae',
'tuesday'               => 'dies Martis',
'wednesday'             => 'dies Mercurii',
'thursday'              => 'dies Iovis',
'friday'                => 'dies Veneris',
'saturday'              => 'dies Saturni',
'january'               => 'Ianuarii',
'february'              => 'Februarii',
'march'                 => 'Martii',
'april'                 => 'Aprilis',
'may_long'              => 'Maii',
'june'                  => 'Iunii',
'july'                  => 'Iulii',
'august'                => 'Augusti',
'september'             => 'Septembris',
'october'               => 'Octobris',
'november'              => 'Novembris',
'december'              => 'Decembris',
'jan'                   => 'ian',
'feb'                   => 'feb',
'mar'                   => 'mar',
'apr'                   => 'apr',
'may'                   => 'mai',
'jun'                   => 'iun',
'jul'                   => 'iul',
'aug'                   => 'aug',
'sep'                   => 'sep',
'oct'                   => 'oct',
'nov'                   => 'nov',
'dec'                   => 'dec',
'categories'            => 'Categoriae',
'category_header'       => 'Paginae in categoria "$1"',
'subcategories'         => 'Categoriae inferiores',
'mainpage'              => 'Pagina prima',
'portal'                => 'Porta communis',
'portal-url'            => 'Project:Porta communis',
'about'                 => 'De {{SITENAME}}',
'aboutpage'             => 'Project:De {{SITENAME}}',
'help'                  => 'Adiutatum',
'helppage'              => 'Help:Auxilium pro editione',
'bugreports'            => 'Renuntiare errores',
'bugreportspage'        => 'Project:Renuntiare errores',
'sitesupport'           => 'Donationes',
'faq'                   => 'Quaestiones frequentes',
'faqpage'               => 'Project:Quaestiones frequentes',
'edithelp'              => 'Adjutatum ad recensere',
'edithelppage'          => 'Help:Quam paginam recensere',
'cancel'                => 'Abrogare',
'qbfind'                => 'Invenire',
'qbbrowse'              => 'Perspicere',
'qbedit'                => 'Recensere',
'qbpageoptions'         => 'Optiones paginae',
'qbpageinfo'            => 'Indicium paginae',
'qbmyoptions'           => 'Optiones meae',
'qbspecialpages'        => 'Paginae speciales',
'moredotdotdot'         => 'Plus...',
'mypage'                => 'Pagina mea',
'mytalk'                => 'Disputatum meum',
'anontalk'              => 'Disputatio huius IP',
'navigation'            => 'Navigatio',
'currentevents'         => 'Novissima',
'disclaimers'           => 'Repudiationes',
'tagline'               => 'E {{SITENAME}}',
'search'                => 'Quaerere',
'searchbutton'          => 'Quaerere',
'go'                    => 'Ire',
'searcharticle'                    => 'Ire',
'history'               => 'Historia',
'history_short'         => 'Historia',
'printableversion'      => 'Forma impressibilis',
'edit'                  => 'Recensere',
'editthispage'          => 'Recensere hanc paginam',
'delete'                => 'Delere',
'deletethispage'        => 'Delere hanc paginam',
'protect'               => 'Protegere',
'protectthispage'       => 'Protegere hanc paginam',
'unprotect'             => 'Deprotegere',
'unprotectthispage'     => 'Deprotegere hanc paginam',
'newpage'               => 'Nova pagina',
'talkpage'              => 'Disputare hanc paginam',
'specialpage'           => 'Pagina specialis',
'postcomment'           => 'Adnotare',
'articlepage'           => 'Videre rem',
'talk'                  => 'Disputatio',
'toolbox'               => 'Arca ferramentorum',
'userpage'              => 'Videre paginam usoris',
'imagepage'             => 'Videre pagina imaginis',
'viewtalkpage'          => 'Videre disputatum',
'otherlanguages'        => 'Aliae linguae',
'redirectedfrom'        => '(Redirectum de $1)',
'lastmodifiedat'          => 'Ultima mutatio: $2, $1.',
'viewcount'             => 'This page has been accessed $1 times.',
'copyright'             => 'Res ad manum sub $1.',
'protectedpage'         => 'Pagina protecta',
'retrievedfrom'         => 'Receptum de "$1"',
'newmessageslink'       => 'nuntios novos',
'editsection'           => 'recensere',
'toc'                   => 'Index',
'showtoc'               => 'monstrare',
'hidetoc'               => 'celare',
'thisisdeleted'         => 'Videre aut restituere $1?',
'restorelink'           => '$1 recensita deleta',
'nstab-main'            => 'Res',
'nstab-user'            => 'Pagina usoris',
'nstab-special'         => 'Specialis',
'nstab-project'         => 'Consilium',
'nstab-image'           => 'Imago',
'nosuchaction'          => 'Actio non est',
'nosuchactiontext'      => 'Actio in URL designata non agnoscitur a {{SITENAME}}.',
'nosuchspecialpage'     => 'Pagina specialis non est',
'nospecialpagetext'     => 'Paginam specialem a {{SITENAME}} ignotam petivisti',
'databaseerror'         => 'Error basis dati',
'cachederror'           => 'Quae sequuntur sunt ex exemplo conditivo paginae quaesitae, fortasse non recente.',
'cannotdelete'          => 'Pagina vel imago deleri non potuit. (Fortasse usor alius iam deleverat.)',
'perfcached'            => 'The following data is cached and may not be completely up to date:',
'viewsource'            => 'Fontem videre',
'logouttitle'           => 'Finis conventi',
'logouttext'            => 'Conventum tuum finivisti.
{{SITENAME}} sine nomine continuare usare potes, aut conventum 
novum aperire cum idem nomine aut ut alio usore.',
'welcomecreation'       => '<h2>Salve, $1!</h2>
<p>Ratio tua iam creata est.
Noli oblivisci praeferentias tuas mutare.',
'loginpagetitle'        => 'Aperire conventum',
'yourname'              => 'Nomen tuum usoris',
'yourpassword'          => 'Tessera tua',
'yourpasswordagain'     => 'Tesseram tuam adfirmare',
'remembermypassword'    => 'Tessera mea inter conventa memento',
'loginproblem'          => '<b>Problema erat aperiens conventum tuum.</b><br />Conare denuo!',
'alreadyloggedin'       => '<span style="color:#ff0000"><b>Usor $1, conventum tuum iam apertum est!</b></span><br />',
'login'                 => 'Aperire conventum',
'loginprompt'           => 'Cookies potestatem facere debes ut conventum aperire.',
'userlogin'             => 'Aperire conventum',
'logout'                => 'Finire conventum',
'userlogout'            => 'Finire conventum',
'notloggedin'           => 'Conventum non apertum est',
'createaccount'         => 'Creare ratio nova',
'createaccountmail'     => 'ab curso publico electronico',
'badretype'             => 'Tesserae quas scripsisti non inter se congruunt.',
'userexists'            => 'Nomen usoris quod selegisti iam est.',
'youremail'             => 'Inscriptio electronica tua',
'yourrealname'          => 'Nomen tuum verum*',
'yournick'              => 'Agnomen tuum (in subscriptiones)',
'loginerror'            => 'Error est in aperiens conventum',
'prefs-help-email'      => '* E-mail (optional): Enables others to contact you through your user or user_talk page without the need of revealing your identity.',
'nocookiesnew'          => 'Ratio usoris creatur est, sed conventum non apertum est. {{SITENAME}} Cookies utitur ut conventum aperire. Cookies tua debiles sunt. Ea potestatem fac, tum conventum aperire cum nomine usoris tesseraque tuis novis.',
'nocookieslogin'        => '{{SITENAME}} Cookies utitur ut conventum aperire. Cookies tua debiles sunt. Ea potestatem fac, tum conare denuo.',
'noname'                => 'Nominem usoris ratum non designavisti.',
'loginsuccesstitle'     => 'Conventum prospere apertum est.',
'loginsuccess'          => 'In {{SITENAME}} agnosceris ut "$1".',
'nosuchuser'            => 'Nomen usoris "$1" non est. 
Orthographiam confirma, aut novam rationem usoris crea.',
'wrongpassword'         => 'Tessera quam scripsisti non constat. Conare denuo.',
'mailmypassword'        => 'Nova tessera per cursum publicum electronicum rogare',
'passwordremindertitle' => 'Nova tessera in {{SITENAME}}',
'passwordremindertext'  => 'Aliquis (fortasse tu, cum loco de IP $1)

novam tesseram pro {{SITENAME}} rogavit.
Tessera pro usore "$2" nunc "$3" est.
Monemus qui novum conventum aperis et tesseram tuam mutas.',
'summary'               => 'Summarium',
'minoredit'             => 'Haec recensio minor est',
'watchthis'             => 'Custodire hanc paginam',
'savearticle'           => 'Servare hanc rem',
'preview'               => 'Praevidere',
'showpreview'           => 'Monstrare praevisum',
'showdiff'              => 'Mutata ostendere',
'anoneditwarning'       => 'You are not logged in. Your IP address will be recorded in this page\'s edit history.',
'blockedtitle'          => 'Usor obstructus est.',
'blockedtext'           => 'Nomen usoris tuum aut locus de IP obstructum est ab usore $1. Causa:<br />
\'\'$2\'\'
<p>Vel usorem $1 appellare potes, vel alios [[Project:Administratores|administratores]] si vis obstructionem disputare.</p>',
'whitelistedittext'     => 'You have to [[{{ns:special}}:Userlogin|login]] to edit pages.',
'loginreqlink'          => 'login',
'newarticle'            => '(Nova)',
'newarticletext'        => 'Per nexum progressus es ad paginam quae nondum exsistit. Novam paginam si vis creare, in capsam infra praebitam scribe. (Vide [[Project:Adjutatum|paginam auxilii]] si plura cognoscere vis.) Si hic es propter errorem, solum \'\'\'Retrorsum\'\'\' in navigatro tuo preme.',
'anontalkpagetext'      => '---- \'\'Haec est pagina disputationis usoris anonymi qui rationem nondum creavit, vel ratione creata non utitur. Non igitur nisi ex inscriptione IP eum agnoscere possumus. Memento insctriptiones IP posse pluribus hominibus pertinere.\'\'',
'noarticletext'         => 'In hac pagina nondum litterae sunt.',
'updated'               => '(Novata)',
'previewnote'           => 'Memento hanc paginam solum praevisum esse, neque iam servatam!',
'editing'               => 'Recensio paginae "$1"',
'editinguser'               => 'Recensio paginae "$1"',
'editconflict'          => 'Contentio recensionis: $1',
'explainconflict'       => 'Alius hanc paginam mutavit postquam eadem mutare incipebat.
Capsa superior paginae verba recentissima continet.
Mutationes tuae in capsa inferior monstrantur.
Mutationes tuae in verbam superiorem adiungare debes.
<b>Solum</b> verba capsae superioris servabuntur quando "Servare hanc rem" premes.
</p>',
'yourtext'              => 'Verba tua',
'storedversion'         => 'Verba recentissima',
'nonunicodebrowser'     => '<strong>CAVETO: Navigatorium retiale tuum systemati UNICODE morem non gerit. Modum habemus quo commentationes sine damno recenseas: litterae non-ASCII in capsa sub veste hexadecimali ostendentur.</strong>',
'editingold'            => '<strong>MONITIO: Formam obsoletam huius paginae mutas.
Si eam servabis, totae mutationes noviores amittentur.</strong>',
'yourdiff'              => 'Diversa',
'copyrightwarning'      => 'Nota omnia {{SITENAME}} contributa divulgata ac liberata esse habentur ex Potestatis tabulae liberae *** GNU (vide singula apud $1).
Nisi vis verba tua crudelissime recenseri, mutari et ad libidinem redistribui, noli ea submittere.<br />
Nobis etiam spondes te esse ipsum horum verborum scriptorem, nisi ex opere in "dominio publico" exscripsisti.
<strong>NOLI OPERIBUS SUB IURE DIVULGANDI UTI NISI POTESTATE FACTA!</strong>',
'longpagewarning'       => 'MONITIO: Haec pagina $1 kilobytes longa est;
aliqui navigatra paginas magniores quam 32 kilobytes longa.
Considera paginam in partes minores frangere.',
'templatesused'         => 'Formulae hac in pagina adhibitae:',
'revhistory'            => 'Historia formarum',
'nohistory'             => 'Haec pagina historiam non habet.',
'revnotfound'           => 'Emendatio non inventa.',
'revnotfoundtext'       => 'Emendatio qui rogavisti non inventa est. 
Confrima URL paginae.',
'loadhist'              => 'Onus historiae paginae',
'currentrev'            => 'Emendatio recentissima',
'revisionasof'          => 'Emendatio ex $1',
'cur'                   => 'nov',
'next'                  => 'seq',
'last'                  => 'prox',
'orig'                  => 'prim',
'histlegend'            => 'Titulus: (nov) = dissimilitudo de forma novissima,
(prox) = dissimilitudo cum forma proxima, M = mutatio minor',
'difference'            => '(Dissimilitudo inter emendationes)',
'loadingrev'            => 'Onus emendationis pro diss',
'editcurrent'           => 'Recensere formam recentissimam huius paginae',
'searchresults'         => 'Eventum investigationis',
'searchresulttext'      => 'Pro plure nuntii de investigatione {{SITENAME}}, vide $1.',
'searchsubtitle'           => 'Pro investigatione "[[:$1]]"',
'searchsubtitleinvalid'           => 'Pro investigatione "$1"',
'badquery'              => 'Investigatio formata male',
'badquerytext'          => 'Investigatio tua procedere non poterat.
Fortasse verba minora quam tres litteras longa quaerere conatus es, vel fortasse error in quaestione erat. Conare denuo.',
'matchtotals'           => 'Investigatio "$1" indicibus $2 rerum 
et verbis $3 rerum congruit.',
'noexactmatch'             => 'Nullae paginae cum hoc indice exacto est, conari totorum verborum quaerere.',
'titlematches'          => 'Exaequata indicibus rerum',
'notitlematches'        => 'Nulla exaequata',
'prevn'                 => '$1 proxima',
'nextn'                 => '$1 secuta',
'viewprevnext'          => 'Videre ($1) ($2) ($3).',
'showingresults'        => 'Monstrens <b>$1</b> eventa incipiens de <b>$2</b>.',
'showingresultsnum'     => 'Monstrens subter <b>$3</b> eventa incipiens cum #<b>$2</b>.',
'nonefound'             => '<strong>Nota</strong>: investigationes saepe infelices sunt propter verba frequentes huiusmodi "que" et "illo", aut quod plus unum verba quaerere designavisti (solae paginae qui tota verba investigationis continent in evento apparebit).',
'powersearch'           => 'Quaerere',
'powersearchtext'       => 'Quaerere in contexto :<br />
$1<br />
$2 Monstrare redirectiones   Quaerere $3 $9',
'searchdisabled'        => '<p>Quaerere ad tempum debilitata est. Sis Google aut Yahoo! usere.</p>',
'preferences'           => 'Praeferentiae',
'prefsnologin'          => 'Conventum non apertum',
'prefsnologintext'      => '[[Special:Userlogin|Conventum aperire]] debes ut praeferentiae tuae perscribere.',
'prefsreset'            => 'Praeferentiae tuae reperscriptus est.',
'qbsettings'            => 'Figuratio claustri celeris',
'changepassword'        => 'Mutare tesseram',
'skin'                  => 'Aspectum',
'math'                  => 'Interpretatio artis mathematicae',
'dateformat'            => 'Forma diei',
'math_failure'          => 'Excutare non potest',
'math_unknown_error'    => 'error ignotus',
'math_unknown_function' => 'functio ignota',
'saveprefs'             => 'Servare praeferentiae',
'resetprefs'            => 'Reddere praeferentiae',
'oldpassword'           => 'Tessera vetus',
'newpassword'           => 'Tessera nova',
'retypenew'             => 'Adfirmare tesseram novam',
'textboxsize'           => 'Mensura capsae verbi',
'rows'                  => 'Lineae',
'columns'               => 'Columnae',
'searchresultshead'     => 'Figuratio eventorum investigationis',
'resultsperpage'        => 'Eventa per paginam',
'contextlines'          => 'Lineae per eventum',
'contextchars'          => 'Litterae contexti per lineam',
'stubthreshold'         => 'Limen ostentationis rei parvae',
'recentchangescount'    => 'Quantum rerum in mutationibus recentibus',
'savedprefs'            => 'Praeferentiae tuae servatae sunt.',
'timezonetext'          => 'Scribere numerum horae inter horam tuam et illam moderatri (UTC).',
'localtime'             => 'Hora indigena',
'timezoneoffset'        => 'Dissimilitudo cinguli horae',
'servertime'            => 'Hora moderatri nunc est',
'guesstimezone'         => 'Hora ex navigatro scribere',
'changes'               => 'mutationes',
'recentchanges'         => 'Mutationes recentes',
'recentchangestext'     => 'Mutationes recentiores. 
Adde quae scis, sed memento addita tua mutari ameliorarique posse ab aliis utentibus. Cave ne aliorum iura (©) violes!',
'rcnote'                => 'Subter <strong>$1</strong> mutationes recentissimae sunt in <strong>$2</strong> diebus proximis.',
'rcnotefrom'            => 'Subter <b>$1</b> mutationes recentissimas sunt in proxima <b>$2</b> die.',
'rclistfrom'            => 'Monstrare mutationes novas incipiens ex $1',
'rclinks'               => 'Monstrare $1 mutationes recentissimas in $2 diebus proximis.<br />$3',
'diff'                  => 'diss',
'hide'                  => 'celare',
'show'                  => 'monstrare',
'upload'                => 'Onerare fascicula',
'uploadbtn'             => 'Onerare fascicula',
'reupload'              => 'Reonerare',
'reuploaddesc'          => 'Redire ad formulam onerationis.',
'uploadnologin'         => 'Conventum non apertum est',
'uploadnologintext'     => '[[Special:Userlogin|Aperire conventum]] debes ut fasciculos oneres.',
'uploaderror'           => 'Error onerati',
'uploadtext'            => '<strong>SISTERE!</strong> Ante hic oneras, lege et pare [[Project:Vonsilias de uso imaginum|consilias de {{SITENAME}} de uso imaginum]].<br />
Ut videre aut quaerere imagines oneratas antea,
adi [[Special:Imagelist|indicem imaginum oneratae]].
Onerata et deleta in [[Special:Log/upload|notationem oneratorum]] notata sunt.<br />
Utere formam subter onerare fasciculos novos.
Capsam desginare debes qui verba privata non uteris.
Preme "Onerare" pro onerate incipere.<br />
<br />
Formae antipositae sunt: JPEG pro imaginibus, PNG pro simulacris, et OGG pro sonis.
Nomina descriptiva utere, ut confusiones evitare.
Pro imaginem in rebus includere, nexum
* \'\'\'<nowiki>[[Image:File.jpg]]</nowiki>\'\'\'
* \'\'\'<nowiki>[[Image:File.png|verba alterna]]</nowiki>\'\'\'
aut pro sonis utere
* \'\'\'<nowiki>[[Media:File.ogg]]</nowiki>\'\'\'',
'uploadlog'             => 'Notatio onerati',
'uploadlogpage'         => 'Notatio onerati',
'uploadlogpagetext'     => 'Subter index onerati recentissimi est.
Totae horae in hora moderatri monstrantur (UTC).
<ul>
</ul>',
'filename'              => 'Nomen fasciculi',
'filedesc'              => 'Descriptio',
'filestatus'            => 'Locus verborum privatorum',
'filesource'            => 'Fons',
'copyrightpage'         => 'Project:Verba privata',
'copyrightpagename'     => '{{SITENAME}} verba privata',
'uploadedfiles'         => 'Fasciculi onerati',
'ignorewarning'         => 'Ignorare monita et servare fasciculum.',
'minlength'             => 'Nomines imaginum saltem tres litteras habere debent.',
'badfilename'           => 'Nomen imaginis ad "$1" mutatum est',
'badfiletype'           => '".$1" forma imaginis suasa non est.',
'largefile'             => 'Suasus est qui imagines 100kb non excedunt.',
'successfulupload'      => 'Oneratum perfectum',
'fileuploaded'          => 'Fasciculus "$1" sine problema oneratus est.
Premere hic: ($2) ut paginam descriptionis adire 
et fasciculum describere.',
'uploadwarning'         => 'Monitus onerati',
'savefile'              => 'Servare fasciculum',
'uploadedimage'         => '"$1" oneratus est',
'imagelist'             => 'Imagines',
'imagelisttext'         => 'Subter index $1 imaginum $2 digestus.',
'getimagelist'          => 'Adducere indicem imaginum.',
'ilsubmit'              => 'Quaerere',
'byname'                => 'ex nomine',
'bydate'                => 'ex die',
'bysize'                => 'ex magnitudine',
'imglegend'             => 'Titulus: (desc) = monstrare/mutare descriptionem imaginis',
'imghistory'            => 'Historia imaginis',
'imghistlegend'         => 'Titulus: (nov) = haec est imago recentissima, (del) = delere hanc formam vetus, (rev) = reverte ad hanc formam vetus.
<br /><i>Premere in diem ut imaginem in illum diem oneratum videre.</i>',
'imagelinks'            => 'Nexus ad imaginem',
'linkstoimage'          => 'Paginae insequentes huic imagini nectunt:',
'nolinkstoimage'        => 'Nullae paginae huic imagini nectunt.',
'sharedupload'          => 'This file is a shared upload and may be used by other projects.',
'mimesearch'            => 'Quaerere per MIME',
'unwatchedpages'        => 'Incustodita',
'listredirects'         => 'Index redirectionum',
'unusedtemplates'       => 'Formulae sine usu',
'randomredirect'        => 'Redirectio fortuita',
'statistics'            => 'Census',
'sitestats'             => 'Census accessi',
'userstats'             => 'Census usorum',
'sitestatstext'         => 'Basis dati <b>$1</b> habet.
Hic numerus paginas disputationum, includitIste numero include paginas de "discussion", paginas de {{SITENAME}}, res parvas, paginas redirectionum, et paginas alteras.
Hae excludens, <b>$2</b> paginae sunt.<p>
Paginae <b>$3</b> visae fuerunt, et <b>$4</b> mutationes paginae fuerunt
postquam aperitum moderatri novi (20 juli 2002).
Fere <b>$5</b> mutationes per pagina fuerunt, et <b>$6</b> visae per mutatione.',
'userstatstext'         => '<b>$1</b> usores relati sunt,
quorum <b>$2</b> administratores sunt (vide $3).',
'disambiguations'       => 'Paginae disambiguationis',
'disambiguationspage'   => 'Template:Discretiva',
'disambiguationstext'   => 'Paginae subsequentes ad <i>paginam discretivam</i> nectunt. Ad aptam paginam nectere debent.<br />Pagina discretivam esse putatur si $1 eam nectat. <br />Nexus sub aliis praefixis <i>non</i> hic indicantur.',
'doubleredirects'       => 'Redirectiones duplices',
'brokenredirects'       => 'Redirectiones fractae',
'brokenredirectstext'   => 'Redirectiones sequentes ad res inexistentes nectunt',
'nbytes'                => '$1 bytes',
'ncategories'           => '$1 categories',
'nlinks'                => '$1 nexus',
'nmembers'              => '$1 members',
'nrevisions'            => '$1 revisions',
'nviews'                => '$1 visae',
'lonelypages'           => 'Paginae orbatae',
'uncategorizedpages'    => 'Sine categoriis',
'uncategorizedcategories'=> 'Categoriae sine categoriis',
'unusedcategories'      => 'Categoriae vacuae',
'unusedimages'          => 'Imagines non in usu',
'popularpages'          => 'Paginae populares',
'wantedcategories'      => 'Categoriae desideratae',
'wantedpages'           => 'Paginae desideratae',
'mostlinked'            => 'Maxime annexa',
'mostlinkedcategories'  => 'Maxime annexae categoriae',
'mostcategories'        => 'Paginae plurimis categoriis',
'mostimages'            => 'Maxime annexae imagines',
'mostrevisions'         => 'Plurimum mutata',
'allpages'              => 'Omnes paginae',
'prefixindex'           => 'Praefixa',
'randompage'            => 'Pagina fortuita',
'shortpages'            => 'Paginae breves',
'longpages'             => 'Paginae longae',
'deadendpages'          => 'Fundulae',
'listusers'             => 'Usores',
'specialpages'          => 'Paginae speciales',
'spheading'             => 'Paginae speciales',
'restrictedpheading'    => 'Paginae speciales propriae',
'recentchangeslinked'   => 'Mutationes conlatae',
'rclsub'                => '(Paginis nexis ex "$1")',
'newpages'              => 'Paginae novae',
'ancientpages'          => 'Res veterrimae',
'move'                  => 'Movere',
'movethispage'          => 'Motare hanc paginam',
'booksources'           => 'Fontes librorum',
'version'               => 'Versio',
'log'                   => 'Acta',
'emailuser'             => 'Mittere cursum publicum electronicum huic usoro',
'emailpage'             => 'Mittere cursum publicum electronicum huic usori',
'emailpagetext'         => 'Si hic usor inscriptionem electronicam ratum in praeferentias usorum eius dedit, forma subter nuntium mittet.
Inscriptio electronica qui in praeferentiis tuis dedis ut "De" inscriptione apparebit.',
'noemailtitle'          => 'Nulla inscriptio electronica',
'noemailtext'           => 'Hic usor inscriptionem electronicam ratam non dedit, aut nuntios ex aliis usoribus non vult.',
'emailto'               => 'Ad',
'emailsubject'          => 'Res',
'emailmessage'          => 'Nuntius',
'emailsend'             => 'Mittere',
'emailsenttext'         => 'Nuntius tuus missus est.',
'watchlist'             => 'Paginae custoditae',
'nowatchlist'           => 'Nullas paginas custodis.',
'watchnologin'          => 'Conventum non apertum est',
'watchnologintext'      => '[[Special:Userlogin|Aperire conventum]] debes ut indicem paginarum custoditarum mutes.',
'addedwatch'            => 'Pagina custodita',
'addedwatchtext'        => '<p>Pagina "$1" in [[Special:Watchlist|indice paginarum custoditarum]] tuo est. Mutationes posthac huic paginae et paginae disputationis ibi notabuntur, et pagina <b>in nigro</b> apparebit in [[Special:Recentchanges|indice modificationum recentum]].</p>
<p>Si paginam de indice paginarum custoditarum removere vis, "Custodire non iam."</p>',
'removedwatch'          => 'Custoditum abrogatum est',
'removedwatchtext'      => 'Pagina "$1" custoditum non est iam.',
'watch'                 => 'custodire',
'watchthispage'         => 'Custodire hanc paginam',
'unwatch'               => 'Decustodire',
'unwatchthispage'       => 'Abrogare custoditum',
'notanarticle'          => 'Res non est',
'watchnochange'         => 'Nullae paginarum custoditarum tuarum recensitae sunt in hoc tempo.',
'watchdetails'          => '($1 paginae custoditae, sine paginas disputationis;
$2 paginae totae recensitae in hoc tempo;
$3...
[$4 monstrare et recensere indicem totum].)',
'watchmethod-recent'    => 'recensita recenta quaerens pro pagina custodita',
'watchmethod-list'      => 'paginas custoditas quaerens pro recensitis recentibus',
'removechecked'         => 'Removere paginas selectas ex indice paginarum custoditarum',
'watchlistcontains'     => 'Index paginarum custoditarum tuus $1 paginas habet.',
'watcheditlist'         => 'Hic est index alphabeticus paginarum custoditarum tui. Nota capsas paginarum qui removere vis ex index paginarum custoditarum et "removere" premere.',
'removingchecked'       => 'Removens res notatas ex indice paginarum custoditarum...',
'couldntremove'         => 'Paginam \'$1\' removere non posse...',
'iteminvalidname'       => 'Problema cum pagina \'$1\', nomen inritum...',
'wlnote'                => 'Subter proximae $1 mutationes sunt in proximis <b>$2</b> horis.',
'wlshowlast'            => 'Monstrare proximas $1 horas $2 dies $3',
'wlhideshowown'         => '$1 recensiones meas.',
'wlhideshowbots'        => '$1 recensiones automatarias.',
'deletepage'            => 'Delere paginam',
'confirm'               => 'Adfirmare',
'excontent'             => 'contenta erant:',
'excontentauthor'       => 'contenta erant: \'$1\' (et contributor unicus \'$2\' erat)',
'exbeforeblank'         => 'contentum ante vacuatum erat:',
'exblank'               => 'pagina vacuata erat',
'confirmdelete'         => 'Adfirmare deletio',
'deletesub'             => '(Deletio de "$1")',
'historywarning'        => 'Monitus: Paginam delebis historiam habet:',
'confirmdeletetext'     => 'Paginam imaginemve perpetuo delebis ex base datorum, cum tota historia eius. Adfirma quaeso te paginam delere velle, consequentias intellere, et deletionem [[Project:Consilium|Consilio {{SITENAME}}e]] congruere.',
'actioncomplete'        => 'Actio completa',
'deletedtext'           => '"$1" deletum est.
Vide $2 pro indice deletionum recentum.',
'dellogpage'            => 'Index deletionum',
'dellogpagetext'        => 'Subter index deletionum recentissimum est.
Totae horae in cingulo horis moderatri sunt (UTC).
<ul>
</ul>',
'deletionlog'           => 'index deletionum',
'reverted'              => 'Reversum ad recensitum proximum',
'deletecomment'         => 'Ratio deletionis',
'imagereverted'         => 'Reversum ad formam proximam',
'rollback'              => 'Reverti mutationes',
'rollbacklink'          => 'reverti',
'rollbackfailed'        => 'Reversum defecit',
'cantrollback'          => 'Mutatio reverti non posse; conlator proximus solus auctor huius rei est.',
'alreadyrolled'         => 'Ad mutationem proxima paginae "[[$1]]" ab usore "[[User:$2|$2]]" ([[User talk:$2|disputatio]]) reverti non potest; alius paginam iam recensuit vel revertit. Mutatio proxima ab usore "[[User:$3|$3]]" ([[User talk:$3|disputatio]]) effecta est.',
'editcomment'           => 'Dictum recensiti erat: "<i>$1</i>".',
'revertpage'            => 'Reverti ad mutationem proximam ab $1',
'protectlogpage'        => 'Index praesidii',
'protectlogtext'        => 'Subter index paginarum protectarum est. Vide [[Project:Pagina protecta]] si pluris nuntii eges.',
'protectedarticle'      => '[[$1]] protectum est',
'unprotectedarticle'    => '[[$1]] deprotectum est',
'protect-text'          => 'You may view and change the protection level here for the page <strong>$1</strong>. Please be sure you are following the [[Project:Protected page|project guidelines]].',
'undelete'              => 'Restituere paginam deletam',
'undeletepage'          => 'Videre et restituere paginas deletas',
'undeletepagetext'      => 'Paginae sequentes deletae sunt sed in tabulis sunt et eas restituere posse. Tabulae nonnumquam deleta est.',
'undeletearticle'       => 'Restituere rem deletam',
'undeleterevisions'     => '$1 recensita servata',
'undeletehistory'       => 'Si paginam restituis, tota recensita restituebuntur ad historiam.
Si paginam novam cum ipse nomine post deletionem creata est, recensita restituta in historia prior apparebit, et recensitum recentissimum paginae necessario non renovabitur.',
'undeleterevision'      => 'Recensitum deletum usque ab $1',
'undeletebtn'           => 'Restituere!',
'contributions'         => 'Conlationes usoris',
'mycontris'             => 'Conlationes meae',
'nocontribs'            => 'Nullae mutationes inventae sunt ex his indiciis.',
'ucnote'                => 'Subter <b>$1</b> mutationes proximae huius usoris sunt in <b>$2</b> die proximo.',
'uclinks'               => 'Videre $1 mutationes proximas; videre $2 dies proximos.',
'uctop'                 => ' (vertex)',
'whatlinkshere'         => 'Nexus ad hanc paginam',
'notargettitle'         => 'Nullus scopus',
'notargettext'          => 'Paginam aut usorem non notavis.',
'linklistsub'           => '(Index nexuum)',
'linkshere'             => 'Paginae sequentes huic paginae nectunt:',
'nolinkshere'           => 'Nullae paginae hic nectunt.',
'isredirect'            => 'pagina redirectionis',
'blockip'               => 'Obstruere locum IP',
'blockiptext'           => 'Forma infera utere ut quendam locum IP obstruas. Hoc non nisi secundum [[Project:Consilium|consilium {{SITENAME}}e]] fieri potest. Rationem certam subsribe.',
'ipaddress'             => 'Locus IP',
'ipbreason'             => 'Ratio',
'ipbsubmit'             => 'Obstruere hunc locum',
'badipaddress'          => 'Locus IP formatus malus est.',
'blockipsuccesssub'     => 'Locus obstructus est.',
'blockipsuccesstext'    => 'Locus IP [[Special:Contributions/$1|$1]] obstructus est.
<br />Vide [[Special:Ipblocklist|Indicem obstructorum IP]] ut obstructos revideas.',
'unblockip'             => 'Deobstruere locum IP',
'unblockiptext'         => 'Formam inferam usere ut locum IP deobstruere.',
'ipusubmit'             => 'Deobstruere hanc locum',
'ipblocklist'           => 'Index locorum IP obstructorum',
'blocklistline'         => '$1, $2 obstruxit $3 (exire $4)',
'blocklink'             => 'obstruere',
'unblocklink'           => 'deobstruere',
'contribslink'          => 'conlationes',
'blocklogpage'          => 'Index obstructorum',
'blocklogentry'         => 'obstructus "$1", exire $2',
'blocklogtext'          => 'Hic index obstructorum et deobstructorum est. Vide [[Special:Ipblocklist|Index locorum IP obstructorum]] pro index obstructorum.',
'unblocklogentry'       => 'deobstruxit "$1"',
'lockdb'                => 'Obstruere basem dati',
'unlockdb'              => 'Deobstruere basem dati',
'lockdbtext'            => 'Obstructio basis dati potestatem totorum usorum suspendebit paginas recensere et preferentiarum earum et paginarum custoditarum mutare.
Adfirmare qui basem dati obstruere vis, et que basem dati deobstruebis ut primum alimentum tuum finiveris.',
'lockconfirm'           => 'Basem dati obstruere volo.',
'unlockconfirm'         => 'Basem dati deobstruere volo.',
'lockbtn'               => 'Obstruere basem dati',
'unlockbtn'             => 'Deobstruere basem dati',
'locknoconfirm'         => 'Capsam non notavis.',
'lockdbsuccesssub'      => 'Basis dati obstructa est',
'unlockdbsuccesssub'    => 'Basis dati deobstructa est',
'lockdbsuccesstext'     => 'Basis dati de {{SITENAME}} obstructa est.
<br />Memento eam deobstruere ubi alimentum tuum finiveris.',
'unlockdbsuccesstext'   => 'Basis dati de {{SITENAME}} deobstructa est.',
'movepage'              => 'Motare paginam',
'movepagetext'          => 'Formam inferam utere ut paginam renominare et historia eius ad nominem novum motare. Index vetus paginam redirectionis ad indicem novum fiet. Nexus paginae veteris non mutabitur; redectiones duplices aut fractas [[Special:Maintenance|quaerere et figere]] debebis.

Paginam \'\'\'non\'\'\' movebitur si pagina sub indice novo iam est, nisi vacuata est aut pagina redirectionis est et nulla historia habet.

<b>MONITUM!</b> Haec mutatio vehemens et improvisa potest pro pagina populare; adfirmare qui consequentias intelleges ante procedere.',
'movepagetalktext'      => 'Pagina disputationis huius paginae, si est, etiam necessario motabitur \'\'\'nisi\'\'\':
*Contexti transmoves,
*Pagina disputationis non vacuata iam est, aut
*Capsam subter non nota.

Ergo paginam manu motare debebis, si vis.',
'movearticle'           => 'Motare paginam',
'movenologin'           => 'Conventum non apertum',
'movenologintext'       => '[[Special:Userlogin|Rationem usoris]] habere debes ut paginam motare.',
'newtitle'              => 'Ad indicem novum',
'movepagebtn'           => 'Motare paginam',
'pagemovedsub'          => 'Pagina motata est.',
'pagemovedtext'         => 'Pagina "[[$1]]" motata est ad "[[$2]]".',
'articleexists'         => 'Pagina cum hoc nomine iam est, aut nomen selectum non ratum est.
Selege nominem altera.',
'talkexists'            => 'Paginam motata est, sed paginam disputationis non motata est quod paginam ibi iam est. Eam manu motare debebis.',
'movedto'               => 'motata ad',
'movetalk'              => 'Motare etiam paginam disputationis, si est.',
'talkpagemoved'         => 'Pagina disputationis etiam motata est.',
'talkpagenotmoved'      => 'Pagina disputationis \'\'\'non\'\'\' motata est.',
'movereason'            => 'Ratio',
'export'                => 'Paginas exportare',
'allmessages'           => 'Nuntii systematis',
'allmessagestext'       => 'Hic est index totorum nuntiorum in MediaWiki',
'allmessagesnotsupportedUI'=> 'Your current interface language <b>$1</b> is not supported by special:Allmessages at this site.',
'allmessagesnotsupportedDB'=> 'special:Allmessages not supported because wgUseDatabaseMessages is off.',
'import'                => 'Paginas importare',
'tooltip-save'          => 'Servare mutationes tuas [alt-s]',
'tooltip-diff'          => 'Show which changes you made to the text. [alt-d]',
'and'                   => 'et',
'subcategorycount'      => 'Huic categoriae {{PLURAL:$1|una categoria inferiora est|$1 categoriae inferiores sunt}}.',
'categoryarticlecount'  => 'Huic categoriae {{PLURAL:$1|una pagina est|$1 paginae sunt}}.',
'mw_math_png'           => 'Semper vertere PNG',
'mw_math_simple'        => 'HTML si admodum simplex, alioqui PNG',
'mw_math_html'          => 'HTML si fieri potest, alioqui PNG',
'mw_math_source'        => 'Stet ut TeX (pro navigatri texti)',
'mw_math_modern'        => 'Commendatum pro navigatri recentes',
'mw_math_mathml'        => 'MathML',
'Monobook.js'           => '/* tooltips and access keys */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Pagina usoris mea\'); 
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'The user page for the ip you\'re editing as\'); 
ta[\'pt-mytalk\'] = new Array(\'n\',\'Disputatum meum\'); 
ta[\'pt-anontalk\'] = new Array(\'n\',\'Discussion about edits from this ip address\'); 
ta[\'pt-preferences\'] = new Array(\'\',\'Praeferentiae meae\'); 
ta[\'pt-watchlist\'] = new Array(\'l\',\'Paginae quae custodis\'); 
ta[\'pt-mycontris\'] = new Array(\'y\',\'Index conlationum mearum\'); 
ta[\'pt-login\'] = new Array(\'o\',\'Te conventum aperire hortamur, non autem requisitum\'); 
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Te conventum aperire hortamur, non autem requisitum\'); 
ta[\'pt-logout\'] = new Array(\'o\',\'Finire conventum\'); 
ta[\'ca-talk\'] = new Array(\'t\',\'Disputatio de hac pagina\'); 
ta[\'ca-edit\'] = new Array(\'e\',\'Hanc paginam recensere potes\'); 
ta[\'ca-addsection\'] = new Array(\'+\',\'Huic disputationi adnotare\'); 
ta[\'ca-viewsource\'] = new Array(\'e\',\'Haec pagina protecta est\'); 
ta[\'ca-history\'] = new Array(\'h\',\'Historia huius paginae\'); 
ta[\'ca-protect\'] = new Array(\'=\',\'Protegere hanc paginam\'); 
ta[\'ca-delete\'] = new Array(\'d\',\'Delere hanc paginam\'); 
ta[\'ca-undelete\'] = new Array(\'d\',\'Reficere hanc pagina deleta\'); 
ta[\'ca-move\'] = new Array(\'m\',\'Movere hanc paginam\'); 
ta[\'ca-nomove\'] = new Array(\'\',\'Tibi movere hanc paginam non licet\'); 
ta[\'ca-watch\'] = new Array(\'w\',\'Custodire hanc paginam\'); 
ta[\'ca-unwatch\'] = new Array(\'w\',\'Decustodire hanc paginam\'); 
ta[\'search\'] = new Array(\'f\',\'Quaerere hanc wiki\'); 
ta[\'p-logo\'] = new Array(\'\',\'Pagina prima\'); 
ta[\'n-mainpage\'] = new Array(\'z\',\'Invisere paginae primae\'); 
ta[\'n-portal\'] = new Array(\'\',\'De {{SITENAME}}\'); 
ta[\'n-currentevents\'] = new Array(\'\',\'Eventa novissima\'); 
ta[\'n-recentchanges\'] = new Array(\'r\',\'Index mutationum recentum\'); 
ta[\'n-randompage\'] = new Array(\'x\',\'Invisere paginae fortuitae\'); 
ta[\'n-help\'] = new Array(\'\',\'Adiutatum de hac wiki\'); 
ta[\'n-sitesupport\'] = new Array(\'\',\'Adiuvare hanc wiki\'); 
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Index paginarum quae hic nectunt\'); 
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Mutationes recentes in paginis quae hic nectunt\'); 
ta[\'feed-rss\'] = new Array(\'\',\'RSS feed\'); 
ta[\'feed-atom\'] = new Array(\'\',\'Atom feed\'); 
ta[\'t-contributions\'] = new Array(\'\',\'Index conlationum huius usoris\'); 
ta[\'t-emailuser\'] = new Array(\'\',\'Mittere cursum publicum electronicum huic usoro\'); 
ta[\'t-upload\'] = new Array(\'u\',\'Onerare fascicula\'); 
ta[\'t-specialpages\'] = new Array(\'q\',\'Index paginarum specialium\'); 
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Videre paginam\'); 
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Videre paginam usoris\'); 
ta[\'ca-nstab-media\'] = new Array(\'c\',\'View the media page\'); 
ta[\'ca-nstab-special\'] = new Array(\'\',\'Haec paginam specialis est, paginam ipsam tibi recensere not licet\'); 
ta[\'ca-nstab-project\'] = new Array(\'a\',\'View the project page\'); 
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Videre paginam imaginem\'); 
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'View the system message\'); 
ta[\'ca-nstab-template\'] = new Array(\'c\',\'View the template\'); 
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Videre paginam adiutatam\'); 
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Videre paginam categoriam\');',
'newimages'             => 'Pinacotheca imaginum novarum',
'exif-pixelxdimension'  => 'Valind image height',
'watchlistall1'         => 'omnes',
'watchlistall2'         => 'omnes',
);
?>
