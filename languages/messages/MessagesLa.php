<?php
/** Latin (lingua Latina)
  *
  * @addtogroup Language
  */

$skinNames = array(
	'standard' => 'Norma',
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

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',
	
	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',
	
	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i, Y xg j',
	
	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Nexus cum linea subscribere:',
'tog-highlightbroken'         => 'Formare nexos fractos <a href="" class="new">sici</a> (alioqui: sic<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Iustificare paragrapha',
'tog-hideminor'               => 'Celare recensiones minores in nuper mutatibus',
'tog-extendwatchlist'         => 'Extendere indicem paginarum custoditarum ut omnes emendationes monstrentur',
'tog-usenewrc'                => 'Nuper mutata amplificata (JavaScript)',
'tog-numberheadings'          => 'Numerare indices necessario',
'tog-showtoolbar'             => 'Instrumenta pro recensendo monstrare (JavaScript)',
'tog-editondblclick'          => 'Premere bis ad paginam recensendum (JavaScript)',
'tog-editsection'             => 'Licere paginarum partibus recensier via nexus [recensere]',
'tog-editsectiononrightclick' => 'Licere paginarum partibus recensier si<br />dextram murem premam in titulis partum (JavaScript)',
'tog-showtoc'                 => 'Indicem contenta monstrare (paginis in quibus sunt plus quam 3 partes)',
'tog-rememberpassword'        => 'Recordare tesseram inter conventa (utere cookies)',
'tog-editwidth'               => 'Capsa recensitorum totam latitudinem habet',
'tog-watchcreations'          => 'Paginas quas creavi in paginarum custoditarum indicem addere',
'tog-watchdefault'            => 'Paginas quas emendavi in paginarum custoditarum indicem addere',
'tog-watchmoves'              => 'Paginas quas movi in paginarum custoditarum indicem addere',
'tog-watchdeletion'           => 'Paginas quas delevi in paginarum custoditarum indicem addere',
'tog-minordefault'            => 'Notare omnes recensiones quasi minores',
'tog-previewontop'            => 'Monstrare praevisum ante capsam recensiti, non post ipsam',
'tog-previewonfirst'          => 'Praevisum monstrare recensione incipiente',
'tog-enotifwatchlistpages'    => 'Mittere mihi litteras electronicas si pagina a me custodita mutatur',
'tog-enotifusertalkpages'     => 'Mittere mihi litteras electronicas si mea disputatio mutatur',
'tog-enotifminoredits'        => 'Mittere mihi litteras electronicas etiam pro recensionibus minoribus',
'tog-enotifrevealaddr'        => 'Monstrare inscriptio mea electronica in nuntiis notificantibus',
'tog-shownumberswatching'     => 'Numerum usorum custodientium monstrare',
'tog-fancysig'                => 'Subscriptio cruda (sine nexu automatico)',
'tog-externaleditor'          => 'Utere editore externo semper',
'tog-externaldiff'            => 'Utere dissimilitudine externa semper',
'tog-uselivepreview'          => 'Praevisum viventem adhibere (JavaScript)',
'tog-forceeditsummary'        => 'Si recensionem non summatim descripsero, me roga si continuare velim',
'tog-watchlisthideown'        => 'Celare meas recensiones in paginarum custoditarum indice',
'tog-watchlisthidebots'       => 'Celare recensiones automatarias in paginarum custoditarum indice',
'tog-watchlisthideminor'      => 'Celare recensiones minores in paginarum custoditarum indice',

'underline-always'  => 'Semper',
'underline-never'   => 'Numquam',
'underline-default' => 'Defalta navigatri interretialis',

'skinpreview' => '(Praevisum)',

# Dates
'sunday'        => 'dies Solis',
'monday'        => 'dies Lunae',
'tuesday'       => 'dies Martis',
'wednesday'     => 'dies Mercurii',
'thursday'      => 'dies Iovis',
'friday'        => 'dies Veneris',
'saturday'      => 'dies Saturni',
'january'       => 'Ianuarius',
'february'      => 'Februarius',
'march'         => 'Martius',
'april'         => 'Aprilis',
'may_long'      => 'Maius',
'june'          => 'Iunius',
'july'          => 'Iulius',
'august'        => 'Augustus',
'january-gen'   => 'Ianuarii',
'february-gen'  => 'Februarii',
'march-gen'     => 'Martii',
'april-gen'     => 'Aprilis',
'may-gen'       => 'Maii',
'june-gen'      => 'Iunii',
'july-gen'      => 'Iulii',
'august-gen'    => 'Augusti',
'september-gen' => 'Septembris',
'october-gen'   => 'Octobris',
'november-gen'  => 'Novembris',
'december-gen'  => 'Decembris',
'jan'           => 'Ian',
'may'           => 'Mai',
'jun'           => 'Iun',
'jul'           => 'Iul',

# Bits of text used by many pages
'categories'      => 'Categoriae',
'pagecategories'  => '{{PLURAL:$1|Categoria|Categoriae}}',
'category_header' => 'Paginae in categoria "$1"',
'subcategories'   => 'Subcategoriae',

'about'          => 'De',
'article'        => 'Pagina contenta continens',
'newwindow'      => '(in fenestra nova aperietur)',
'cancel'         => 'Abrogare',
'qbfind'         => 'Invenire',
'qbbrowse'       => 'Perspicere',
'qbedit'         => 'Recensere',
'qbpageoptions'  => 'Optiones paginae',
'qbpageinfo'     => 'Indicium paginae',
'qbmyoptions'    => 'Optiones meae',
'qbspecialpages' => 'Paginae speciales',
'moredotdotdot'  => 'Plus...',
'mypage'         => 'Pagina mea',
'mytalk'         => 'Disputatum meum',
'anontalk'       => 'Disputatio huius IP',
'navigation'     => 'Navigatio',

'returnto'          => 'Redire ad $1.',
'tagline'           => 'E {{grammar:ablative|{{SITENAME}}}}',
'help'              => 'Adiutatum',
'search'            => 'Quaerere',
'searchbutton'      => 'Quaerere',
'go'                => 'Ire',
'searcharticle'     => 'Ire',
'history'           => 'Historia paginae',
'history_short'     => 'Historia',
'printableversion'  => 'Forma impressibilis',
'permalink'         => 'Nexus perpetuus',
'print'             => 'Imprimere',
'edit'              => 'Recensere',
'editthispage'      => 'Recensere hanc paginam',
'delete'            => 'Delere',
'deletethispage'    => 'Delere hanc paginam',
'protect'           => 'Protegere',
'protectthispage'   => 'Protegere hanc paginam',
'unprotect'         => 'Deprotegere',
'unprotectthispage' => 'Deprotegere hanc paginam',
'newpage'           => 'Nova pagina',
'talkpage'          => 'Disputare hanc paginam',
'specialpage'       => 'Pagina specialis',
'postcomment'       => 'Adnotare',
'articlepage'       => 'Videre rem',
'talk'              => 'Disputatio',
'toolbox'           => 'Arca ferramentorum',
'userpage'          => 'Videre paginam usoris',
'projectpage'       => 'Vide paginam coeptorum',
'imagepage'         => 'Videre paginam fasciculi',
'categorypage'      => 'Videre categoriam',
'viewtalkpage'      => 'Videre disputatum',
'otherlanguages'    => 'Linguis aliis',
'redirectedfrom'    => '(Redirectum de $1)',
'redirectpagesub'   => 'Pagina redirectionis',
'lastmodifiedat'    => 'Ultima mutatio: $2, $1.', # $1 date, $2 time
'protectedpage'     => 'Pagina protecta',
'jumpto'            => 'Salire ad:',
'jumptonavigation'  => 'navigationem',
'jumptosearch'      => 'quaerere',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'De {{grammar:ablative|{{SITENAME}}}}',
'aboutpage'         => 'Project:De {{grammar:ablative|{{SITENAME}}}}',
'bugreports'        => 'Renuntiare errores',
'bugreportspage'    => 'Project:Renuntiare errores',
'copyright'         => 'Res ad manum sub $1.',
'copyrightpagename' => '{{grammar:genitive|{{SITENAME}}}} verba privata',
'copyrightpage'     => 'Project:Verba privata',
'currentevents'     => 'Novissima',
'currentevents-url' => 'Nuntii',
'disclaimers'       => 'Repudiationes',
'edithelp'          => 'Opes pro recensendo',
'edithelppage'      => 'Project:Quam paginam recensere',
'faq'               => 'Quaestiones frequentes',
'faqpage'           => 'Project:Quaestiones frequentes',
'helppage'          => 'Project:Auxilium pro editione',
'mainpage'          => 'Pagina prima',
'portal'            => 'Porta communis',
'portal-url'        => 'Project:Porta communis',
'privacy'           => 'Consilium de secreto',
'sitesupport'       => 'Donationes',

'badaccess'        => 'Error permissu',
'badaccess-group0' => 'Non licet tibi actum quod petivisti agere.',
'badaccess-group1' => 'Actum quod petivisti solum potest agi ab usoribus ex grege $1.',
'badaccess-group2' => 'Actum quod petivisti solum potest agi ab usoribus ex uno gregum $1.',
'badaccess-groups' => 'Actum quod petivisti solum potest agi ab usoribus ex uno gregum $1.',

'retrievedfrom'       => 'Receptum de "$1"',
'youhavenewmessages'  => 'Habes $1 ($2).',
'newmessageslink'     => 'nuntia nova',
'newmessagesdifflink' => 'dissimilia post mutationem ultimam',
'editsection'         => 'recensere',
'editold'             => 'recensere',
'toc'                 => 'Index',
'showtoc'             => 'monstrare',
'hidetoc'             => 'celare',
'thisisdeleted'       => 'Videre aut restituere $1?',
'restorelink'         => '{{PLURAL:$1|unam emendationem deletam|$1 emendationes deletas}}',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'Res',
'nstab-user'      => 'Pagina usoris',
'nstab-media'     => 'Media',
'nstab-special'   => 'Specialis',
'nstab-project'   => 'Consilium',
'nstab-image'     => 'Fasciculus',
'nstab-mediawiki' => 'Nuntium',
'nstab-template'  => 'Formula',
'nstab-help'      => 'Help',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Actio non est',
'nosuchactiontext'  => 'Actio in URL designata non agnoscitur a hoc vici.',
'nosuchspecialpage' => 'Pagina specialis non est',
'nospecialpagetext' => 'Paginam specialem invalidam petivisti. Pro indice paginarum specialum validarum, vide [[Special:Specialpages|{{MediaWiki:specialpages}}]].',

# General errors
'databaseerror'      => 'Error in basi datorum',
'noconnect'          => 'Nos paenitet! {{SITENAME}} per aerumnas technicas agit, et server basis datorum invenire non potest. <br />
$1',
'cachederror'        => 'Quae sequuntur sunt ex exemplo conditivo paginae quaesitae, fortasse non recente.',
'badarticleerror'    => 'Haec actio non perfici potest in hac pagina.',
'cannotdelete'       => 'Pagina vel fasciculus deleri non potuit. (Fortasse usor alius iam deleverat.)',
'badtitle'           => 'Titulus malus',
'badtitletext'       => 'Nomen paginae quaestae fuit invalidum, vacuum, aut praeverbium interlingualem vel intervicialem habuit. Fortasse insunt una aut plus litterarum quae in titulis non possunt inscribier.',
'viewsource'         => 'Fontem videre',
'viewsourcefor'      => 'pro $1',
'protectedpagetext'  => 'Haec pagina protecta est, ut emendationes prohibeantur.',
'viewsourcetext'     => 'Fontem videas et exscribeas:',
'protectedinterface' => 'Haec pagina dat textum interfaciei pro logiciali, et est protecta ad vandalismum vetandum.',
'editinginterface'   => "'''Caveat censor:''' Emendas iam paginam quae textum interfaciei logicialem dat. Mutationes vultum {{grammar:genitive|{{SITENAME}}}} omnibus usoribus afficient.",

# Login and logout pages
'logouttitle'                => 'Conventum concludere',
'logouttext'                 => '<strong>Conventum tuum conclusum est.</strong><br />
Ignote continues {{grammar:ablative|{{SITENAME}}}} uti, aut conventum novum vel sub eodem vel novo nomine aperias. Nota bene paginas fortasse videantur quasi tuum conventum esset apertum, priusquam navigatrum purgaveris.',
'welcomecreation'            => '== Salve, $1! ==

Ratio tua iam creata est. Noli oblivisci praeferentias tuas mutare.',
'loginpagetitle'             => 'Conventum aperire',
'yourname'                   => 'Nomen tuum usoris',
'yourpassword'               => 'Tessera tua',
'yourpasswordagain'          => 'Tesseram tuam adfirmare',
'remembermypassword'         => 'Tesseram meam inter conventa memento',
'yourdomainname'             => 'Regnum tuum',
'loginproblem'               => '<b>Problema erat aperiens conventum tuum.</b><br />Conare denuo!',
'alreadyloggedin'            => '<strong>Usor $1, conventum tuum iam apertum est!</strong><br />',
'login'                      => 'Conventum aperire',
'loginprompt'                => 'Cookies potestatem facere debes ut conventum aperire.',
'userlogin'                  => 'Conventum aperire',
'logout'                     => 'Conventum concludere',
'userlogout'                 => 'Conventum concludere',
'notloggedin'                => 'Conventum non apertum est',
'nologin'                    => 'Num rationem non habes? $1!',
'nologinlink'                => 'Eam crea',
'createaccount'              => 'Rationem novam creare',
'gotaccount'                 => 'Habesne iam rationem? $1.',
'gotaccountlink'             => 'Conventum aperi',
'createaccountmail'          => 'ab inscriptione electronica',
'badretype'                  => 'Tesserae quas scripsisti inter se non congruunt.',
'userexists'                 => 'Nomen usoris quod selegisti iam est. Nomen usoris alium selige.',
'youremail'                  => 'Inscriptio tua electronica *:',
'username'                   => 'Nomen usoris:',
'uid'                        => 'ID usoris:',
'yourrealname'               => 'Nomen tuum verum *:',
'yourlanguage'               => 'Lingua tua:',
'yourvariant'                => 'Differentia',
'yournick'                   => 'Agnomen tuum (in subscriptionibus):',
'badsig'                     => 'Subscriptio cruda non est valida; scrutina HTML textos.',
'email'                      => 'Litterae electronicae',
'prefs-help-realname'        => '* Nomen verum (non necesse): si vis id dare, opera tua tibi ascribantur.',
'loginerror'                 => 'Error factus est in aperiendo conventum',
'prefs-help-email'           => '* Inscriptio tua electronica (non necesse): Sinit aliis tecum loqui per tuam paginam usoris, nisi te reveles.',
'nocookiesnew'               => "Ratio usoris creata est, sed conventum non apertum est. {{SITENAME}} ''Cookies'' utitur in usorum conventa aperiendo. Cookies tua debiles sunt. Eis potestatem fac, tum conventum aperi cum nomine usoris tesseraque tua nova.",
'nocookieslogin'             => "{{SITENAME}} ''Cookies'' utitur in usorum conventa aperiendo. Cookies tua debiles sunt. Eis potestatem fac, tum conare denuo.",
'noname'                     => 'Nominem usoris ratum non designavisti.',
'loginsuccesstitle'          => 'Conventum prospere apertum est',
'loginsuccess'               => 'Apud {{grammar:accusative|{{SITENAME}}}} agnosceris ut "$1".',
'nosuchuser'                 => 'Nomen usoris "$1" non est. Orthographiam confirma, aut novam rationem usoris crea.',
'nosuchusershort'            => 'Nomen usoris "$1" non est. Orthographiam confirma.',
'wrongpassword'              => 'Tessera quam scripsisti non constat. Conare denuo.',
'wrongpasswordempty'         => 'Tesseram vacuam scripsisti. Conare denuo.',
'mailmypassword'             => 'Tesseram novam per litteras electronicas petere',
'passwordremindertitle'      => 'Nova tessera apud {{grammar:accusative|{{SITENAME}}}}',
'passwordremindertext'       => 'Aliquis (tu probabiliter, cum loco de IP $1)
tesseram novam petivit pro {{grammar:ablative|{{SITENAME}}}} ($4).
Tessera usoris "$2" nunc est "$3".
Conventum aperias et statim tesseram tuam mutes.

Si non ipse hanc petitionem fecisti, aut si tesseram tuam
meministi et etiam nolis eam mutare, potes hunc nuntium
ignorare, et tessera seni uti continuare.',
'acct_creation_throttle_hit' => 'Nos paenitet, etiam rationes $1 creavisti. Plurimas non tibi licet creare.',
'emailauthenticated'         => 'Tua inscriptio electronica recognita est $1.',
'accountcreated'             => 'Ratio creata',
'accountcreatedtext'         => 'Ratio pro usore $1 creata est.',

# Password reset dialog
'resetpass' => 'Tesseram novam creare',

# Edit page toolbar
'bold_sample'    => 'Litterae pingues',
'bold_tip'       => 'Litterae pingues',
'italic_sample'  => 'Textus litteris italicis scriptus',
'italic_tip'     => 'Textus litteris italicis scriptus',
'link_sample'    => 'Titulum nexere',
'link_tip'       => 'Nexus internus',
'extlink_sample' => 'http://www.example.com titulus nexus externi',
'extlink_tip'    => 'Nexus externus (memento praefixi http://)',
'math_sample'    => 'Hic inscribe formulam',
'math_tip'       => 'Formula mathematica (LaTeX)',
'image_tip'      => 'Imago in pagina imposita',
'media_tip'      => 'Nexus ad fasciculum mediorum',
'sig_tip'        => 'Subscriptio tua cum indicatione temporis',
'hr_tip'         => 'Linea horizontalis (noli saepe uti)',

# Edit pages
'summary'                => 'Summarium',
'subject'                => 'Res/titulus',
'minoredit'              => 'Haec est recensio minor',
'watchthis'              => 'Custodire hanc paginam',
'savearticle'            => 'Servare hanc rem',
'preview'                => 'Praevidere',
'showpreview'            => 'Monstrare praevisum',
'showlivepreview'        => 'Monstrare praevisum viventem',
'showdiff'               => 'Mutata ostendere',
'anoneditwarning'        => "'''Monitio:''' Conventum tuum non apertum. Locus IP tuus in historia huius paginae notabitur.",
'missingcommenttext'     => 'Sententiam subter inscribe.',
'summary-preview'        => 'Praevisum summarii',
'subject-preview'        => 'Praevisum rei/tituli',
'blockedtitle'           => 'Usor obstructus est',
'blockedtext'            => "<big>'''Nomen usoris aut locus IP tuus obstructus est'''</big> a magistratu \$1.

Ratio data est: ''\$2''.

Potes ad \$1 aut [[{{MediaWiki:grouppage-sysop}}|magistratum]] alium nuntium mittere ad impedimentum disputandum.
Nota bene te non posse proprietate \"Litteras electronicas usori mittere\" uti, nisi tibi est inscriptio electronica confirmata apud [[Special:Preferences|praeferentias usoris tuas]]. Locus IP tuus est \$3, et numerus obstructionis est #\$5. Quaeso te eos scripturum si quaestiones ullas roges.",
'blockedoriginalsource'  => "Fons '''$1''' subter monstratur:",
'blockededitsource'      => "Textus '''tuarum emendationum''' in '''$1''' subter monstratur:",
'whitelistedittitle'     => 'Conventum aperiendum ut recenseas',
'whitelistedittext'      => 'Necesse est tibi $1 priusquam paginas recenseas.',
'whitelistreadtitle'     => 'Conventum aperiendum ut legas',
'whitelistreadtext'      => 'Necesse est tibi [[Special:Userlogin|conventum aperire]] priusquam paginas legas.',
'whitelistacctitle'      => 'Non licet tibi rationem creare',
'confirmedittitle'       => 'Adfirmanda est inscriptio tua electronica prisuquam recenseas',
'confirmedittext'        => 'Tua inscriptio electronica est adfirmanda priusquam paginas recenseas. Quaesumus eam selige et adfirma per tuas [[Special:Preferences|praeferentias]].',
'loginreqtitle'          => 'Conventum aperiendum',
'loginreqlink'           => 'conventum aperire',
'loginreqpagetext'       => 'Necesse est tibi $1 priusquam paginas alias legas.',
'accmailtitle'           => 'Tessera missa est.',
'accmailtext'            => 'Tessera usoris "$1" ad $2 missa est.',
'newarticle'             => '(Nova)',
'newarticletext'         => "Per nexum progressus es ad paginam quae nondum exsistit. Novam paginam si vis creare, in capsam infra praebitam scribe. Vide [[{{MediaWiki:helppage}}|paginam auxilii]] si plura cognoscere vis. Si hic es propter errorem, solum '''Retrorsum''' in navigatro tuo preme.",
'anontalkpagetext'       => "---- ''Haec est pagina disputationis usoris anonymi, solum a loco IP suo noti. Memento locos IP aliquando mutaturos, et a usoribus multis fortasse adhibitos. Si es usor ignotus, et tibi querulae sine ratione datae sunt, conventum [[Special:Userlogin|aperi vel crea]] ad confusionem solvendam. Nota locum IP tuum concelatum esse convento aperto si de rebus privatis tuis es sollicitatus.''",
'noarticletext'          => 'In hac pagina nondum litterae sunt. Potes etiam [[Special:Search/{{PAGENAME}}|hanc rem in aliis paginis quaerere]] aut [{{fullurl:{{FULLPAGENAME}}|action=edit}} hanc paginam creare].',
'updated'                => '(Novata)',
'note'                   => '<strong>Nota:</strong>',
'previewnote'            => '<strong>Memento hanc paginam solum praevisum esse, neque iam servatam!</strong>',
'editing'                => 'Recensio paginae "$1"',
'editinguser'            => 'Recensio <b>$1</b>',
'editingsection'         => 'Recensens $1 (partem)',
'editingcomment'         => 'Recensens $1 (adnotum)',
'editconflict'           => 'Contentio recensionis: $1',
'explainconflict'        => 'Alius hanc paginam mutavit postquam eadem mutare incipiebas.
Capsa superior paginae verba recentissima continet.
Mutationes tuae in capsa inferiore monstrantur.
Mutationes tuae in verbam superiorem adiungare debes.
<b>Solum</b> verba capsae superioris servabuntur quando "Servare hanc rem" premes.<br />',
'yourtext'               => 'Sententia tua',
'storedversion'          => 'Verba recentissima',
'nonunicodebrowser'      => '<strong>CAVETO: Navigatorium retiale tuum systemati UNICODE morem non gerit. Modum habemus quo commentationes sine damno recenseas: litterae non-ASCII in capsa sub veste hexadecimali ostendentur.</strong>',
'editingold'             => '<strong>MONITIO: Formam obsoletam huius paginae mutas.
Si eam servaveris, omnes mutationes recentiores obrogatae peribunt!</strong>',
'yourdiff'               => 'Dissimilitudo',
'copyrightwarning'       => 'Nota bene omnia contributa divulgari sub \'\'$2\'\' (vide singula apud $1).
Nisi vis verba tua crudelissime recenseri, mutari, et ad libidinem redistribui, noli ea submittere.<br />
Nobis etiam spondes te esse ipsum horum verborum scriptorem primum, aut ex opere in "dominio publico" exscripsisse.
<strong>NOLI OPERIBUS SUB IURE DIVULGANDI UTI SINE POTESTATE!</strong>',
'copyrightwarning2'      => 'Nota bene omnia contributa divulgari sub \'\'$2\'\' (vide singula apud $1).
Nisi vis verba tua crudelissime recenseri, mutari, et ad libidinem redistribui, noli ea submittere.<br />
Nobis etiam spondes te esse ipsum horum verborum scriptorem primum, aut ex opere in "dominio publico" exscripsisse.
<strong>NOLI OPERIBUS SUB IURE DIVULGANDI UTI SINE POTESTATE!</strong>',
'longpagewarning'        => 'MONITIO: Haec pagina est $1 chilioctetis longa;
aliquae navigatra paginas longiores quam 32 chiliocteti recensere non possunt.
Considera paginam in partes minores frangere.',
'protectedpagewarning'   => '<strong>CAVE: Haec pagina protecta est ut magistratus soli eam recenseant.</strong>',
'templatesused'          => 'Formulae hac in pagina adhibitae:',
'templatesusedpreview'   => 'Formulae hoc in praeviso adhibitae:',
'templatesusedsection'   => 'Formulae hac in parte adhibitae:',
'template-protected'     => '(protecta)',
'template-semiprotected' => '(semi-protecta)',

# "Undo" feature
'undo-summary' => 'abrogans recensionem $1 ab usore [[User:$2|$2]] ([[User talk:$2|Disputatio]] | [[Special:Contributions/$2|conlationes]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ratio creari non potest',
'cantcreateaccounttext'  => 'Creatio rationum ab hoc loco IP (<b>$1</b>) obstructa est, probabiliter ob vandalismum iteratum de tua schola aut provisore interretiali.',

# History pages
'revhistory'          => 'Historia formarum',
'viewpagelogs'        => 'Vide acta huius paginae',
'nohistory'           => 'Huic paginae non est historia.',
'revnotfound'         => 'Emendatio non inventa',
'revnotfoundtext'     => 'Emendatio quem rogavisti non inventa est. 
Confirma URL paginae.',
'loadhist'            => 'Onerans historiam paginae',
'currentrev'          => 'Emendatio recentissima',
'revisionasof'        => 'Emendatio ex $1',
'revision-info'       => 'Emendatio ex $1 ab $2',
'previousrevision'    => '← Emendatio senior',
'nextrevision'        => 'Emendatio novior →',
'currentrevisionlink' => 'Emendatio currens',
'cur'                 => 'nov',
'next'                => 'seq',
'last'                => 'prox',
'page_first'          => 'prim',
'page_last'           => 'ult',
'histlegend'          => 'Selige pro dissimilitudine: indica in botones radiales et "intrare" in claviatura imprime ut conferas.

Titulus: (nov) = dissimilis ab forma novissima, (prox) = dissimilis ab forma proxima, M = recensio minor',
'deletedrev'          => '[deleta]',

# Revision deletion
'revisiondelete' => 'Emendationem delere',

# Diffs
'difference'              => '(Dissimilitudo inter emendationes)',
'loadingrev'              => 'Onerans emendationem pro diss',
'editcurrent'             => 'Recensere formam recentissimam huius paginae',
'compareselectedversions' => 'Conferre versiones selectas',
'editundo'                => 'abrogare',

# Search results
'searchresults'         => 'Eventum investigationis',
'searchresulttext'      => 'Pro plurimis nuntiis de investigatione in {{grammar:ablative|{{SITENAME}}}}, vide [[{{MediaWiki:helppage}}|{{MediaWiki:help}}]].',
'searchsubtitle'        => "Pro investigatione '''[[:$1]]'''",
'searchsubtitleinvalid' => "Pro investigatione '''$1'''",
'badquery'              => 'Quaestio male formata',
'badquerytext'          => 'Investigatio tua procedere non poterat.
Fortasse verba minora quam tres litteras longa quaerere conatus es, vel fortasse error in quaestione erat. Conare denuo.',
'matchtotals'           => 'Investigatio "$1" indicibus $2 rerum 
et verbis $3 rerum congruit.',
'noexactmatch'          => "'''Nulla pagina cum titulo \"\$1\" exacto existit.''' Potes [[:\$1|eam creare]].",
'titlematches'          => 'Exaequata indicibus rerum',
'notitlematches'        => 'Nulla exaequata',
'prevn'                 => '$1 superiores',
'nextn'                 => '$1 proxima',
'viewprevnext'          => 'Videre ($1) ($2) ($3).',
'showingresults'        => 'Subter monstrans <b>$1</b> eventibus tenus incipiens ab #<b>$2</b>.',
'showingresultsnum'     => 'Subter monstrans <b>$3</b> eventus incipiens ab #<b>$2</b>.',
'nonefound'             => "'''Nota''': investigationes saepe infelices sunt propter verba frequentes huiusmodi \"que\" et \"illo\", aut quod plus unum verba quaerere designavisti (solae paginae qui tota verba investigationis continent in evento apparebit).",
'powersearch'           => 'Quaerere',
'powersearchtext'       => 'Quaerere in spatiis nominalibus:<br />$1<br />$2 Monstrare redirectiones<br />Quaerere $3 $9',
'searchdisabled'        => 'Per {{grammar:accusative|{{SITENAME}}}} ad tempus non potes quaerere. Interea per [http://www.google.com Googlem] quaeras. Nota indices {{grammar:genitive|{{SITENAME}}}} contentorum apud Googlem fortasse antiquiores esse.',

# Preferences page
'preferences'              => 'Praeferentiae',
'mypreferences'            => 'Praeferentiae meae',
'prefsnologin'             => 'Conventum non apertum',
'prefsnologintext'         => '[[Special:Userlogin|Conventum aperire]] debes ut praeferentiae tuae perscribere.',
'prefsreset'               => 'Praeferentiae tuae reperscriptus est.',
'qbsettings'               => 'Figuratio claustri celeris',
'qbsettings-none'          => 'Nullus',
'qbsettings-fixedleft'     => 'Constituere a sinistra',
'qbsettings-fixedright'    => 'Constituere a dextra',
'qbsettings-floatingleft'  => 'Innens a sinistra',
'qbsettings-floatingright' => 'Innens a dextra',
'changepassword'           => 'Mutare tesseram',
'skin'                     => 'Aspectum',
'math'                     => 'Interpretatio artis mathematicae',
'dateformat'               => 'Forma diei',
'datedefault'              => 'Nullum praeferentiae',
'datetime'                 => 'Dies et tempus',
'math_failure'             => 'Excutare non potest',
'math_unknown_error'       => 'error ignotus',
'math_unknown_function'    => 'functio ignota',
'prefs-personal'           => 'Minutiae rationis',
'prefs-rc'                 => 'Nuper mutata',
'prefs-watchlist'          => 'Paginae custoditae',
'prefs-watchlist-days'     => 'Numerus dierum displicandus in paginis tuis custoditis:',
'prefs-watchlist-edits'    => 'Numerus recensionum displicandus in paginis tuis custoditis extensis:',
'saveprefs'                => 'Servare praeferentias',
'resetprefs'               => 'Reddere praeferentias',
'oldpassword'              => 'Tessera vetus:',
'newpassword'              => 'Tessera nova:',
'retypenew'                => 'Adfirmare tesseram novam:',
'textboxsize'              => 'Mensura capsae verbi',
'rows'                     => 'Lineae:',
'columns'                  => 'Columnae:',
'searchresultshead'        => 'Figuratio eventorum investigationis',
'resultsperpage'           => 'Eventa per paginam:',
'contextlines'             => 'Lineae per eventum:',
'contextchars'             => 'Litterae contexti per lineam:',
'recentchangescount'       => 'Quantum rerum in nuper mutatis:',
'savedprefs'               => 'Praeferentiae tuae servatae sunt.',
'timezonetext'             => 'Scribere numerum horae inter horam tuam et illam moderatri (UTC).',
'localtime'                => 'Hora indigena',
'timezoneoffset'           => 'Dissimilitudo cinguli horae',
'servertime'               => 'Hora moderatri nunc est',
'guesstimezone'            => 'Hora ex navigatro scribere',
'allowemail'               => 'Sinere litteras electronicas mitti tuae inscriptioni electronicae',
'defaultns'                => 'Quaerere per haec spatia nominalia a defalta:',
'files'                    => 'Fasciculi',

# Groups
'group-sysop'      => 'Magistratus',
'group-bureaucrat' => 'Grapheocrates',

'group-sysop-member'      => 'Magistratus',
'group-bureaucrat-member' => 'Grapheocrates',

'grouppage-bot'        => '{{ns:project}}:Bots',
'grouppage-sysop'      => '{{ns:project}}:Magistratus',
'grouppage-bureaucrat' => '{{ns:project}}:Grapheocrates',

# User rights log
'rightslog'     => 'Index mutationum iuribus usorum',
'rightslogtext' => 'Haec est index mutationum iuribus usorum.',

# Recent changes
'recentchanges'     => 'Nuper mutata',
'recentchangestext' => 'Inspice mutationes recentes huic vici in hac pagina.',
'rcnote'            => 'Subter sunt <strong>$1</strong> nuperrime mutata in <strong>$2</strong> diebus proximis, ad $3 tempus.',
'rcnotefrom'        => 'Subter sunt <b>$1</b> nuperrime mutata in proxima <b>$2</b> die.',
'rclistfrom'        => 'Monstrare mutata nova incipiens ab $1',
'rcshowhideminor'   => '$1 recensiones minores',
'rcshowhideliu'     => '$1 usores notos',
'rcshowhideanons'   => '$1 usores ignotos',
'rcshowhidemine'    => '$1 conlationes meas',
'rclinks'           => 'Monstrare $1 nuperrime mutata in $2 diebus proximis.<br />$3',
'diff'              => 'diss',
'hide'              => 'celare',
'show'              => 'monstrare',
'rc_categories_any' => 'Ulla',

# Recent changes linked
'recentchangeslinked' => 'Nuper mutata annexorum',

# Upload
'upload'            => 'Fasciculos onerare',
'uploadbtn'         => 'Fasciculum onerare',
'reupload'          => 'Reonerare',
'reuploaddesc'      => 'Redire ad formulam onerationis.',
'uploadnologin'     => 'Conventum non apertum est',
'uploadnologintext' => '[[Special:Userlogin|Aperire conventum]] debes ut fasciculos oneres.',
'uploaderror'       => 'Error onerati',
'uploadtext'        => "Utere formam subter ad fasciculos onerandos. Ut videas aut quaeras fasciculos oneratos antea, adi [[Special:Imagelist|indicem fasciculorum oneratorum]].
Onerata et deleta in [[Special:Log/upload|notatione oneratorum]] notata sunt.

Ad imaginem includendum in pagina, utere nexum
'''<nowiki>[[</nowiki>{{ns:image}}:File.jpg]]''' aut
'''<nowiki>[[</nowiki>{{ns:image}}:File.png|verba alia]]''' aut
'''<nowiki>[[</nowiki>{{ns:media}}:File.ogg]]''' pro nexum directum ad fasciculum.",
'uploadlog'         => 'Notatio onerati',
'uploadlogpage'     => 'Notatio onerati',
'uploadlogpagetext' => 'Subter est index fasciculorum recentissimorum oneratorum.',
'filename'          => 'Nomen fasciculi:',
'filedesc'          => 'Descriptio',
'fileuploadsummary' => 'Descriptio:',
'filestatus'        => 'Locus verborum privatorum:',
'filesource'        => 'Fons:',
'uploadedfiles'     => 'Fasciculi onerati',
'ignorewarning'     => 'Ignorare monita et servare fasciculum.',
'ignorewarnings'    => 'Ignorare monita omnes',
'minlength'         => 'Nomina fasciculorum saltem tres litteras habere debent.',
'badfilename'       => 'Nomen fasciculi ad "$1" mutatum est.',
'large-file'        => 'Suasum est ut fasciculi $1 magnitudine non excedant; magnitudo huius fasciculi est $2.',
'successfulupload'  => 'Oneratum perfectum',
'uploadwarning'     => 'Monitus onerati',
'savefile'          => 'Servare fasciculum',
'uploadedimage'     => 'oneravit "[[$1]]"',
'uploadvirus'       => 'Fasciculi huic est virus! Singula: $1',
'watchthisupload'   => 'Custodire hanc paginam',

# Image list
'imagelist'           => 'Fasciculi',
'imagelisttext'       => "Subter est index {{plural:$1|'''unius''' fasciculi|'''$1''' fasciculorum}} digestus $2.",
'getimagelist'        => 'onerans indicem fasciculorum',
'ilsubmit'            => 'Quaerere',
'byname'              => 'ex nomine',
'bydate'              => 'ex die',
'bysize'              => 'ex magnitudine',
'imgfile'             => 'fasciculus',
'imglegend'           => 'Titulus: (desc) = monstrare vel recensere descriptionem fasciculi.',
'imghistory'          => 'Historia fasciculi',
'deleteimgcompletely' => 'Delere omnes emendationes huius fasciculi',
'imghistlegend'       => 'Titulus: (nov) = hic est fasciculus recentissimus, (del) = delere hanc formam veterem, (rev) = reverte ad hanc formam veterem.
<br /><i>Premere in diem ut fasciculum illo die oneratum vides.</i>',
'imagelinks'          => 'Nexus',
'linkstoimage'        => 'Paginae sequentes ad hunc fasciculum nectunt:',
'nolinkstoimage'      => 'Nullae paginae ad hunc fasciculum nectunt.',
'noimage'             => 'Fasciculus huius nominis non est. $1 potes.',
'noimage-linktext'    => 'Fasciculum huius nominis onerare',

# MIME search
'mimesearch' => 'Quaerere per MIME',

# Unwatched pages
'unwatchedpages' => 'Paginae incustoditae',

# List redirects
'listredirects' => 'Redirectiones',

# Unused templates
'unusedtemplates' => 'Formulae non in usu',

# Random redirect
'randomredirect' => 'Redirectio fortuita',

# Statistics
'statistics'    => 'Census',
'sitestats'     => 'Census {{grammar:genitive|{{SITENAME}}}}',
'userstats'     => 'Census usorum',
'sitestatstext' => "Basis datorum '''$1''' paginas habet.
Hic numerus paginas disputationum includit, paginas de {{grammar:ablative|{{SITENAME}}}}, stipulas, paginas redirectionum, et paginas alias quae probabiliter non sunt paginae contenta habentes legitimae.
His exclusis, sunt '''$2''' paginae quae contenta recta habere putantur.

'''$8''' imagines oneratae sunt.

Paginae '''$3''' visae sunt, et '''$4''' recensiones paginarum factae sunt 
ab initio huius vici.
Hoc aequat fere '''$5''' mutationes per paginam, et '''$6''' visae per mutationem. 

'''$7''' operationes etiam exspectant perfacier.",
'userstatstext' => "'''$1''' usores relati sunt,
quorum '''$2''' (vel '''$4%''') sunt $5.",

'disambiguations'      => 'Paginae disambiguationis',
'disambiguationspage'  => 'Template:Discretiva',
'disambiguations-text' => "Paginae subsequentes ad '''paginam discretivam''' nectunt. Ad aptam paginam nectere debent.<br />Pagina discretivam esse putatur si formulam adhibet ad quem [[MediaWiki:disambiguationspage]] nectit.",

'doubleredirects' => 'Redirectiones duplices',

'brokenredirects'        => 'Redirectiones fractae',
'brokenredirectstext'    => 'Redirectiones sequentes ad paginas inexistentes nectunt:',
'brokenredirects-edit'   => '(recensere)',
'brokenredirects-delete' => '(delere)',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|octetum|octeti}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categoriae}}',
'nlinks'                  => '$1 {{PLURAL:$1|nexus|nexus}}',
'nrevisions'              => '$1 {{PLURAL:$1|emendatio|emendationes}}',
'lonelypages'             => 'Paginae non annexae',
'uncategorizedpages'      => 'Paginae sine categoriis',
'uncategorizedcategories' => 'Categoriae sine categoriis',
'uncategorizedimages'     => 'Fasciculi sine categoriis',
'unusedcategories'        => 'Categoriae non in usu',
'unusedimages'            => 'Fasciculi non in usu',
'popularpages'            => 'Paginae saepe monstratae',
'wantedcategories'        => 'Categoriae desideratae',
'wantedpages'             => 'Paginae desideratae',
'mostlinked'              => 'Paginae maxime annexae',
'mostlinkedcategories'    => 'Categoriae maxime annexae',
'mostcategories'          => 'Paginae plurimis categoriis',
'mostimages'              => 'Fasciculi maxime annexi',
'mostrevisions'           => 'Paginae plurimum mutatae',
'allpages'                => 'Paginae omnes',
'prefixindex'             => 'Quaerere per praefixa',
'randompage'              => 'Pagina fortuita',
'shortpages'              => 'Paginae breves',
'longpages'               => 'Paginae longae',
'deadendpages'            => 'Paginae sine nexu',
'deadendpagestext'        => 'Paginae hae sequentes non nectunt ad alias paginas ullas.',
'protectedpages'          => 'Paginae protectae',
'protectedpagestext'      => 'Paginae sequentes protectae sunt a movendo ac recensendo',
'listusers'               => 'Usores',
'specialpages'            => 'Paginae speciales',
'spheading'               => 'Paginae speciales',
'restrictedpheading'      => 'Paginae speciales propriae',
'rclsub'                  => '(Paginis nexis ex "$1")',
'newpages'                => 'Paginae novae',
'newpages-username'       => 'Nomen usoris:',
'ancientpages'            => 'Paginae veterrimae',
'intl'                    => 'Nexus inter linguas',
'move'                    => 'Movere',
'movethispage'            => 'Movere hanc paginam',

# Book sources
'booksources' => 'Librorum fontes',

'categoriespagetext' => 'Huic vici sunt hae categoriae.',
'userrights'         => 'Usorum potestas',
'alphaindexline'     => '$1 ad $2',
'version'            => 'Versio',

# Special:Log
'specialloguserlabel'  => 'Usor:',
'speciallogtitlelabel' => 'Titulus:',
'log'                  => 'Acta',
'alllogstext'          => 'Ostentantur mixte indices onerationum, deletionum, protectionum, obstructionum, et administratorum.
Adspectum graciliorem potes facere modum indicum, nomen usoris, vel paginam petitam seligendo.',

# Special:Allpages
'nextpage'          => 'Pagina proxima ($1)',
'prevpage'          => 'Pagina superior ($1)',
'allpagesfrom'      => 'Monstrare paginas ab:',
'allarticles'       => 'Omnes paginae',
'allinnamespace'    => 'Omnes paginae (in spatio nominali $1)',
'allnotinnamespace' => 'Omnes paginae (quibus in spatio nominali $1 exclusis)',
'allpagesprev'      => 'Superior',
'allpagesnext'      => 'Proxima',
'allpagessubmit'    => 'Ire',
'allpagesprefix'    => 'Monstrare paginas quibus est praeverbium:',
'allpagesbadtitle'  => 'Nomen paginae datum fuit invalidum aut praverbium interlinguale vel interviciale habuit. Fortasse insunt una aut plus litterarum quae in titulis non possunt inscribier.',

# E-mail user
'emailuser'       => 'Litteras electronicas usori mittere',
'emailpage'       => 'Mittere litteras electronicas huic usori',
'emailpagetext'   => 'Si hic usor inscriptionem electronicam ratum in praeferentias usorum eius dedit, forma subter nuntium mittet.
Inscriptio electronica qui in praeferentiis tuis dedis ut "Ab" inscriptione apparebit. Hoc modo usor tibi respondere poterit.',
'defemailsubject' => '{{SITENAME}} - Litterae electronicae',
'noemailtitle'    => 'Nulla inscriptio electronica',
'noemailtext'     => 'Hic usor inscriptionem electronicam ratam non dedit, aut nuntia ab aliis usoribus non vult.',
'emailfrom'       => 'Ab',
'emailto'         => 'Ad',
'emailsubject'    => 'Res',
'emailmessage'    => 'Nuntium',
'emailsend'       => 'Mittere',
'emailsent'       => 'Litterae electronicae missae sunt',
'emailsenttext'   => 'Nuntium tuum missum est.',

# Watchlist
'watchlist'            => 'Paginae custoditae',
'mywatchlist'          => 'Paginae custoditae',
'watchlistfor'         => "(pro usore '''$1''')",
'nowatchlist'          => 'Nullas paginas custodis.',
'watchlistanontext'    => 'Necesse est $1 ad indicem paginarum custoditarum inspiciendum vel recensendum.',
'clearwatchlist'       => 'Indicem paginarum custoditarum purgare',
'watchlistcleartext'   => 'Certus esne ut has paginas removere vis?',
'watchnologin'         => 'Conventum non est apertum',
'watchnologintext'     => '[[Special:Userlogin|Conventum aperire]] debes ut indicem paginarum custoditarum mutes.',
'addedwatch'           => 'Pagina custodita',
'addedwatchtext'       => "Pagina \"[[:\$1]]\" in [[Special:Watchlist|paginas tuas custoditas]] addita est. Mutationes posthac huic paginae et paginae disputationis ibi notabuntur, et pagina '''litteris pinguibus''' apparebit in [[Special:Recentchanges|nuper mutatorum]] indice, ut sit facilius electu.

Si paginam ex indice paginarum custoditarum removere vis, imprime \"decustodire\" ab summa pagina.",
'removedwatch'         => 'Non iam custodita',
'removedwatchtext'     => 'Pagina "[[:$1]]" non iam custodita est.',
'watch'                => 'custodire',
'watchthispage'        => 'Custodire hanc paginam',
'unwatch'              => 'Decustodire',
'unwatchthispage'      => 'Abrogare custoditum',
'notanarticle'         => 'Res non est',
'watchnochange'        => 'Nullae paginarum custoditarum tuarum recensitae sunt in hoc tempore.',
'watchdetails'         => '* {{PLURAL:$1|$1 pagina custodita|$1 paginae custoditae}} sine paginis disputationis
* [[Special:Watchlist/edit|Monstrare et recensere indicem totum paginarum custoditarum]]
* [[Special:Watchlist/clear|{{MediaWiki:clearwatchlist}}]]',
'watchmethod-recent'   => 'recensita recenta quaerens pro pagina custodita',
'watchmethod-list'     => 'paginas custoditas quaerens pro recensitis recentibus',
'removechecked'        => 'Removere paginas selectas ex indice paginarum custoditarum',
'watchlistcontains'    => 'Index paginarum custoditarum tuus $1 paginas habet.',
'watcheditlist'        => 'Hic est litterarum ordine index tuarum paginarum custoditarum. Indica in capsis paginarum quas removere velis "removere" imprime. Nota quoque disputationes paginarum remotarum removendas esse.',
'removingchecked'      => 'Removens res notatas ex indice paginarum custoditarum...',
'couldntremove'        => "Pagina '$1' removeri non potuit...",
'iteminvalidname'      => "Aerumna cum pagina '$1', nomen non est rectum...",
'wlnote'               => 'Subter proximae $1 mutationes sunt in proximis <b>$2</b> horis.',
'wlshowlast'           => 'Monstrare proximas $1 horas $2 dies $3',
'wlsaved'              => 'Haec est versio servata indicis paginarum custoditarum tuae.',
'watchlist-show-bots'  => 'Monstrare recensiones automatarias',
'watchlist-hide-bots'  => 'Celare recensiones automatarias',
'watchlist-show-own'   => 'Monstrare recensiones meas',
'watchlist-hide-own'   => 'Celare recensiones meas',
'watchlist-show-minor' => 'Monstrare recensiones minores',
'watchlist-hide-minor' => 'Celare recensiones minores',
'wldone'               => 'Factum.',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Custodiens...',
'unwatching' => 'Decustodiens...',

'changed' => 'mutata',
'created' => 'creata',

# Delete/protect/revert
'deletepage'             => 'Delere paginam',
'confirm'                => 'Adfirmare',
'excontent'              => "contenta erant: '$1'",
'excontentauthor'        => "contenta erant: '$1' (et contributor unicus erat '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "contenta priusquam pagina facta vacua erant: '$1'",
'exblank'                => 'pagina vacua erat',
'confirmdelete'          => 'Adfirmare deletionem',
'deletesub'              => '(Deletio de "$1")',
'historywarning'         => 'Monitio: Pagina quam delere vis historiam habet:',
'confirmdeletetext'      => 'Paginam imaginemve perpetuo delebis ex base datorum, cum tota historia eius.
Adfirma quaeso te paginam delere velle, consequentias intellere, et deletionem  [[{{MediaWiki:policy-url}}|consilio]] congruere.',
'actioncomplete'         => 'Actum perfectum',
'deletedtext'            => '"$1" deletum est.
Vide $2 pro indice deletionum recentum.',
'deletedarticle'         => 'delevit "[[$1]]"',
'dellogpage'             => 'Index deletionum',
'dellogpagetext'         => 'Subter est index deletionum recentissimarum.',
'deletionlog'            => 'index deletionum',
'reverted'               => 'Reversum ad emendationem proximam',
'deletecomment'          => 'Ratio deletionis',
'imagereverted'          => 'Prospere reversum est ad formam proximam.',
'rollback'               => 'Reverti mutationes',
'rollback_short'         => 'Reverti',
'rollbacklink'           => 'reverti',
'rollbackfailed'         => 'Reversum defecit',
'cantrollback'           => 'Haec non potest reverti; conlator proximus solus auctor huius rei est.',
'alreadyrolled'          => 'Ad mutationem proximam paginae [[:$1]] ab usore [[User:$2|$2]] ([[User talk:$2|Disputatio]]) reverti non potest; alius paginam iam recensuit vel revertit.

Mutatio proxima ab usore [[User:$3|$3]] ([[User talk:$3|Disputatio]]) effecta est.',
'editcomment'            => 'Dictum recensiti erat: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'             => 'Reverti recensiones ab usore [[User:$2|$2]] ([[User talk:$2|Disputatio]] | [[Special:Contributions/$2|conlationes]]) ad mutationem proximam ab [[User:$1|$1]]',
'protectlogpage'         => 'Index protectionum',
'protectlogtext'         => 'Subter est index protectionum et deprotectionum paginarum. Vide [[Special:Protectedpages|indicem paginarum nunc protectarum]].',
'protectedarticle'       => 'protegit "[[$1]]"',
'unprotectedarticle'     => 'deprotegit "[[$1]]"',
'protectsub'             => '(Protegere "$1")',
'confirmprotect'         => 'Protectionem adfirmare',
'protectcomment'         => 'Ratio protegendo',
'protectexpiry'          => 'Exitus',
'protect_expiry_invalid' => 'Tempus exeundo invalidum fuit.',
'unprotectsub'           => '(Deprotegere "$1")',
'protect-level-sysop'    => 'Magistratus soli',
'protect-expiring'       => 'exit $1',

# Restrictions (nouns)
'restriction-edit' => 'Recensio',
'restriction-move' => 'Motio',

# Restriction levels
'restriction-level-sysop'         => 'protecta',
'restriction-level-autoconfirmed' => 'semi-protecta',

# Undelete
'undelete'               => 'Paginam restituere',
'undeletepage'           => 'Videre et restituere paginas deletas',
'viewdeletedpage'        => 'Paginas deletas inspicere',
'undeletepagetext'       => 'Paginae sequentes deletae sunt sed in tabulis sunt et eas restituere posse. Tabulae nonnumquam deletae sunt.',
'undeleterevisions'      => '$1 {{PLURAL:$1|emendatio servata|emendationes servatae}}',
'undeletehistory'        => 'Si paginam restituis, tota recensita restituentur ad historiam.
Si pagina nova cum ipso nomine post deletionem creata est, recensita restituta in historia prior apparebit, et recensitum recentissimum paginae necessario non renovabitur.',
'undelete-revision'      => 'Emendatio deleta paginae $1 ex $2:',
'undeletebtn'            => 'Restituere',
'undeletedarticle'       => 'restituit "[[$1]]"',
'cannotundelete'         => 'Abrogatio deletionis fefellit; fortasse alterus iam paginam restituit.',
'undelete-header'        => 'Pro paginis nuper deletis, vide [[Special:Log/delete|indicem deletionum]].',
'undelete-search-box'    => 'Quaerere inter paginas iam deletas',
'undelete-search-prefix' => 'Monstrare paginas quibus est praeverbium:',
'undelete-search-submit' => 'Quaerere',
'undelete-no-results'    => 'Nullae paginae inventae sunt ex his indicibus deletionum.',

# Namespace form on various pages
'namespace' => 'Spatium nominale:',
'invert'    => 'Selectionem invertere',

# Contributions
'contributions' => 'Conlationes usoris',
'mycontris'     => 'Conlationes meae',
'contribsub2'   => 'Pro $1 ($2)',
'nocontribs'    => 'Nullae mutationes inventae sunt ex his indiciis.',
'ucnote'        => 'Subter sunt <b>$1</b> mutationes proximae huius usoris in <b>$2</b> diebus proximis.',
'uclinks'       => 'Videre $1 mutationes proximas; videre $2 dies proximos.',
'uctop'         => ' (vertex)',

'sp-contributions-blocklog' => 'Acta obstructionum',

# What links here
'whatlinkshere' => 'Nexus ad paginam',
'notargettitle' => 'Nullus scopus',
'notargettext'  => 'Paginam aut usorem non notavisti.',
'linklistsub'   => '(Index nexuum)',
'linkshere'     => "Paginae sequentes ad '''[[:$1]]''' nectunt:",
'nolinkshere'   => "Nullae paginae ad '''[[:$1]]''' nectunt.",
'isredirect'    => 'pagina redirectionis',
'istemplate'    => 'inclusio',

# Block/unblock
'blockip'                  => 'Usorem obstruere',
'blockiptext'              => 'Forma infera utere ut quendam usorem vel locum IP obstruas ne plus scribere potest.
Hoc non nisi secundum [[{{MediaWiki:policy-url}}|consilium]] fieri potest.
Rationem certam subscribe.',
'ipaddress'                => 'Locus IP',
'ipadressorusername'       => 'Locus IP aut nomen usoris',
'ipbexpiry'                => 'Exitus',
'ipbreason'                => 'Ratio',
'ipbsubmit'                => 'Obstruere hunc locum',
'badipaddress'             => 'Locus IP male formatus',
'blockipsuccesssub'        => 'Locus prospere obstructus est',
'blockipsuccesstext'       => '[[Special:Contributions/$1|$1]] obstructus est.
<br />Vide [[Special:Ipblocklist|indicem usorum obstructorum]] ut obstructos revideas.',
'ipb-unblock-addr'         => 'Deobstruere $1',
'ipb-unblock'              => 'Deobstruere nomen usoris vel locum IP',
'unblockip'                => 'Deobstruere locum IP',
'unblockiptext'            => 'Formam inferam usere ut locum IP deobstruere.',
'ipusubmit'                => 'Deobstruere hanc locum',
'ipblocklist'              => 'Usores obstructi',
'blocklistline'            => '$1, $2 obstruxit $3 (exire $4)',
'infiniteblock'            => 'infinita',
'expiringblock'            => 'exit $1',
'anononlyblock'            => 'solum usores ignoti',
'createaccountblock'       => 'Creatio rationum obstructa',
'blocklink'                => 'obstruere',
'unblocklink'              => 'deobstruere',
'contribslink'             => 'conlationes',
'autoblocker'              => 'Obstructus es automatice quia "[[User:$1|$1]]" nuper tuum locum IP adhibuit. Ratio data ob obstructionem usoris $1 est "\'\'\'$2\'\'\'".',
'blocklogpage'             => 'Index obstructionum',
'blocklogentry'            => 'obstruxit "[[$1]]", exire $2 $3',
'blocklogtext'             => 'Hic est index actorum obstructionis deobstructionisque. Loci IP qui automatice obstructi sunt non enumerantur. Vide [[Special:Ipblocklist|indicem usorum locorumque IP obstructorum]] pro indice toto.',
'unblocklogentry'          => 'deobstruxit "$1"',
'block-log-flags-nocreate' => 'creatio rationum obstructa',
'ipb_expiry_invalid'       => 'Tempus exeundo invalidum fuit.',
'proxyblocksuccess'        => 'Factum.',

# Developer tools
'lockdb'              => 'Basem datorum obstruere',
'unlockdb'            => 'Basem datorum deobstruere',
'lockdbtext'          => 'Obstructio basis datorum potestatem omnium usorum suspendebit paginas recensendi et praeferentiarum earum et indicem paginarum custoditarum mutandi.
Adfirma te basem datorum obstruere velle, et te dein basem datorum deobstruendum.',
'lockconfirm'         => 'Ita, vere basem datorum obstruere volo.',
'unlockconfirm'       => 'Ita, vere basem datorum deobstruere volo.',
'lockbtn'             => 'Basem datorum obstruere',
'unlockbtn'           => 'Basem datorum deobstruere',
'locknoconfirm'       => 'Capsam non notavis.',
'lockdbsuccesssub'    => 'Basis datorum prospere obstructa est',
'unlockdbsuccesssub'  => 'Basis datorum prospere deobstructa est',
'lockdbsuccesstext'   => 'Basis datorum obstructa est.
<br />Memento eam dein [[Special:Unlockdb|deobstruere]].',
'unlockdbsuccesstext' => 'Basis datorum deobstructa est.',
'databasenotlocked'   => 'Basis datorum non obstructa est.',

# Move page
'movepage'                => 'Paginam movere',
'movepagetext'            => "Formam inferam utere ad paginam renominandum et ad historiam eius ad nomen novum movendum.
Index vetus paginam redirectionis ad indicem novum fiet.
Nexus ad paginam veterem non mutabuntur;
redectiones duplices aut fractas quaerere et figere debebis.

Pagina '''non''' movebitur si pagina sub indice novo iam est, nisi est vacua aut pagina redirectionis et nullam historiam habet.

<b>MONITUM!</b>
Haec mutatio vehemens et improvisa potest esse pro pagina populare;
adfirma te consequentias intellegere antequam procedis.",
'movepagetalktext'        => "Pagina disputationis huius paginae, si est, etiam necessario motabitur '''nisi''':

*Disputatio sub paginae novae nomine contenta habet, aut
*Capsam subter non nota.

Ergo manu necesse disputationes motare vel contribuere erit, si vis.",
'movearticle'             => 'Paginam movere',
'movenologin'             => 'Conventum non apertum',
'movenologintext'         => 'Rationem usoris habere et [[Special:Userlogin|conventum aperire]] debes ad movendum paginam.',
'newtitle'                => 'Ad indicem novum',
'move-watch'              => 'Hanc paginam custodire',
'movepagebtn'             => 'Paginam movere',
'pagemovedsub'            => 'Pagina mota est',
'pagemovedtext'           => 'Pagina "[[$1]]" mota est ad "[[$2]]".',
'articleexists'           => "'''Non licet hanc paginam movere:''' pagina cum hoc nomine iam est, aut invalidum est nomen electum. 

Quaesumus, nomen alterum elege aut opem pete [[{{MediaWiki:grouppage-sysop}}|magistratum]].",
'talkexists'              => "'''Pagina prospere mota est, sed pagina disputationis not moveri potuit quia iam est pagina disputationis sub titulo novo. Disputationes recensendo iunge.'''",
'movedto'                 => 'mota ad',
'movetalk'                => 'Movere etiam paginam disputationis',
'talkpagemoved'           => 'Pagina disputationis etiam mota est.',
'talkpagenotmoved'        => 'Pagina disputationis <strong>non</strong> mota est.',
'1movedto2'               => 'movit [[$1]] ad [[$2]]',
'1movedto2_redir'         => 'movit [[$1]] ad [[$2]] praeter redirectionem',
'movereason'              => 'Ratio',
'revertmove'              => 'reverti',
'delete_and_move'         => 'Delere et movere',
'delete_and_move_text'    => '==Deletio necesse est==

Quaesitum "[[$1]]" etiam existit. Vin tu eam delere ut moveas?',
'delete_and_move_confirm' => 'Ita, paginam delere',
'delete_and_move_reason'  => 'Deleta ut moveatur',

# Export
'export'        => 'Paginas exportare',
'export-submit' => 'Exportare',

# Namespace 8 related
'allmessages'               => 'Nuntia systematis',
'allmessagesname'           => 'Nomen',
'allmessagesdefault'        => 'Textus originalis',
'allmessagescurrent'        => 'Textus recens',
'allmessagestext'           => 'Hic est index omnium nuntiorum in MediaWiki.',
'allmessagesnotsupportedUI' => 'Apud hunc situm linguam <b>$1</b> tuae interfaciei non sustinet pagina {{ns:special}}:Allmessages.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' non adhibier potest, quia '''\$wgUseDatabaseMessages''' non iam agitur.",
'allmessagesfilter'         => 'Colum nominibus nuntiorum:',
'allmessagesmodified'       => 'Ea modificata sola monstrare',

# Special:Import
'import'                     => 'Paginas importare',
'import-interwiki-submit'    => 'Importare',
'import-interwiki-namespace' => 'Transferre paginas in spatium nominale:',
'importbadinterwiki'         => 'Nexus intervicus malus',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Pagina usoris mea',
'tooltip-pt-mytalk'               => 'Disputatum meum',
'tooltip-pt-preferences'          => 'Praeferentiae meae',
'tooltip-pt-watchlist'            => 'Paginae quae custodis ut eorum mutationes facilius vides',
'tooltip-pt-mycontris'            => 'Index conlationum mearum',
'tooltip-pt-login'                => 'Te conventum aperire hortamur, non autem requisitum',
'tooltip-pt-anonlogin'            => 'Te conventum aperire hortamur, non autem requisitum',
'tooltip-pt-logout'               => 'Conventum concludere',
'tooltip-ca-talk'                 => 'Disputatio de hac pagina',
'tooltip-ca-edit'                 => 'Hanc paginam recensere potes. Quaesumus praevisum inspice antequam servas.',
'tooltip-ca-addsection'           => 'Huic disputationi adnotare',
'tooltip-ca-viewsource'           => 'Haec pagina protecta est. Fontem inspicere potes.',
'tooltip-ca-history'              => 'Emendationes huius paginae veteres',
'tooltip-ca-protect'              => 'Protegere hanc paginam',
'tooltip-ca-delete'               => 'Delere hanc paginam',
'tooltip-ca-undelete'             => 'Restituere emendationes huic paginae conlatas antequam haec pagina deleta esset',
'tooltip-ca-move'                 => 'Movere hanc paginam',
'tooltip-ca-watch'                => 'Addere hanc paginam tuis paginis custoditis',
'tooltip-ca-unwatch'              => 'Removere hanc paginam ex tuis paginis custoditis',
'tooltip-search'                  => 'Quaerere aliquid in {{grammar:ablative|{{SITENAME}}}}',
'tooltip-p-logo'                  => 'Pagina prima',
'tooltip-n-mainpage'              => 'Ire ad paginam primam',
'tooltip-n-portal'                => 'De hoc incepto',
'tooltip-n-currentevents'         => 'Eventa novissima',
'tooltip-n-recentchanges'         => 'Index nuper mutatorum in hac vici',
'tooltip-n-randompage'            => 'Ire ad paginam fortuitam',
'tooltip-n-help'                  => 'Adiutatum de hoc vici',
'tooltip-n-sitesupport'           => 'Adiuvare hunc vici',
'tooltip-t-whatlinkshere'         => 'Index paginarum quae hic nectunt',
'tooltip-t-recentchangeslinked'   => 'Nuper mutata in paginis quibus haec pagina nectit',
'tooltip-feed-rss'                => 'RSS feed',
'tooltip-feed-atom'               => 'Atom feed',
'tooltip-t-contributions'         => 'Videre conlationes huius usoris',
'tooltip-t-emailuser'             => 'Mittere litteras electronicas huic usori',
'tooltip-t-upload'                => 'Fasciculos vel imagines onerare',
'tooltip-t-specialpages'          => 'Index paginarum specialium',
'tooltip-ca-nstab-main'           => 'Videre paginam',
'tooltip-ca-nstab-user'           => 'Videre paginam usoris',
'tooltip-ca-nstab-special'        => 'Haec est pagina specialis. Pagina ipsa recenseri non potest.',
'tooltip-ca-nstab-project'        => 'Videre paginam inceptorum',
'tooltip-ca-nstab-image'          => 'Videre paginam imaginis',
'tooltip-ca-nstab-mediawiki'      => 'Videre nuntium systematis',
'tooltip-ca-nstab-template'       => 'Videre formulam',
'tooltip-ca-nstab-help'           => 'Videre paginam adiutatam',
'tooltip-ca-nstab-category'       => 'Videre paginam categoriae',
'tooltip-minoredit'               => 'Indicare hanc recensionem minorem',
'tooltip-save'                    => 'Servare mutationes tuas',
'tooltip-preview'                 => 'Praevidere mutationes tuas, quaesumus hoc utere antequam servas!',
'tooltip-diff'                    => 'Monstrare mutationes textui tuas',
'tooltip-compareselectedversions' => 'Videre dissimilitudinem inter ambas emendationes selectas huius paginae',
'tooltip-watch'                   => 'Addere hanc paginam tuis paginis custoditis',
'tooltip-recreate'                => 'Recreare hanc paginam etiamsi deleta est',

# Attribution
'anonymous'        => 'Usor ignotus {{grammar:genitive|{{SITENAME}}}}',
'lastmodifiedatby' => 'Ultima mutatio: $2, $1 ab $3.', # $1 date, $2 time, $3 user
'and'              => 'et',

# Spam protection
'subcategorycount'     => 'Huic categoriae {{PLURAL:$1|est una subcategoria|sunt $1 subcategoriae}}.',
'categoryarticlecount' => 'Huic categoriae {{PLURAL:$1|est una pagina|sunt $1 paginae}}.',
'category-media-count' => 'Huic categoriae {{PLURAL:$1|est unus fasciculus|sunt $1 fasciculi}}.',

# Math options
'mw_math_png'    => 'Semper vertere PNG',
'mw_math_simple' => 'HTML si admodum simplex, alioqui PNG',
'mw_math_html'   => 'HTML si fieri potest, alioqui PNG',
'mw_math_source' => 'Stet ut TeX (pro navigatri texti)',
'mw_math_modern' => 'Commendatum pro navigatri recentes',
'mw_math_mathml' => 'MathML',

# Image deletion
'deletedrevision' => 'Delevit emendationem $1 veterem.',

# Browsing diffs
'previousdiff' => '← Dissimilitudo superior',
'nextdiff'     => 'Dissimilitudo proxima →',

# Media information
'imagemaxsize' => 'Terminare imagines in paginis imaginum ad:',
'thumbsize'    => 'Magnitudo pollicisunguis:',

'newimages' => 'Fasciculi novi',
'noimages'  => 'Nullum videndum.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'omnes',
'watchlistall1'    => 'omnes',
'watchlistall2'    => 'omnes',

# E-mail address confirmation
'confirmemail'            => 'Inscriptionem electronicam adfirmare',
'confirmemail_noemail'    => 'Non est tibi inscriptio electronica valida in [[Special:Preferences|tuis praeferentiis]] posita.',
'confirmemail_text'       => 'Hoc vici te postulat inscriptionem tuam electronicam adfirmare priusquam proprietatibus litterarum electronicarum fruaris. Imprime botonem subter ut nuntium adfirmationis tibi mittatur. Nuntio nexus inerit quod est scribendus in tuo navigatro interretiali ut validum adfirmes tuam inscriptionem electronicam.',
'confirmemail_send'       => 'Mittere codicem adfirmationis',
'confirmemail_sent'       => 'Missae sunt litterae electronicae adfirmationis.',
'confirmemail_sendfailed' => 'Litteras electronicas adfirmationis non potuimus mittere. Inspice inscriptionem tuam electronicam ut errores invenias.

Nuntius reddidit: $1',
'confirmemail_invalid'    => 'Codex adfirmationis invalidus. Fortasse id exitum est.',
'confirmemail_needlogin'  => 'Necesse est tibi $1 ut inscriptionem tuam electronicam adfirmes.',
'confirmemail_success'    => 'Tua inscriptio electronica adfirmata est. Libenter utaris {{grammar:ablative|{{SITENAME}}}}.',
'confirmemail_loggedin'   => 'Inscriptio tua electronica iam adfirmata est.',
'confirmemail_error'      => 'Aliquid erravit quando adfirmationem tuam servabamus.',
'confirmemail_subject'    => '{{SITENAME}} - Adfirmatio inscriptionis electronicae',
'confirmemail_body'       => 'Aliquis (tu probabiliter, cum loco de IP $1) rationem "$2" creavit apud {{grammar:accusative|{{SITENAME}}}} sub hac inscriptione electronica.

Ut adfirmas te esse ipsum et proprietates inscriptionum electronicarum licere fieri apud {{grammar:accusative|{{SITENAME}}}}, hunc nexum aperi in tuo navigatro interretiali:

$3

Si *non* tu hoc fecisti, noli nexum sequi. Hic codex adfirmationis exibit $4.',

# Inputbox extension, may be useful in other contexts as well
'createarticle' => 'Paginam creare',

# Delete conflict
'deletedwhileediting' => 'Caveat censor: Haec pagina deleta est postquam inceperis eam recensere!',
'confirmrecreate'     => "Usor [[User:$1|$1]] ([[User talk:$1|disputatio]]) delevit hanc paginam postquam eam emendare inceperis cum ratione:
: ''$2''
Quaesumus, adfirma ut iterum hanc paginam crees.",
'recreate'            => 'Recreare',

# action=purge
'confirm_purge_button' => 'Licet',

'youhavenewmessagesmulti' => 'Habes nuntia nova in $1',

'articletitles' => "Paginae ab ''$1''",

'loginlanguagelabel' => 'Lingua: $1',

# Multipage image navigation
'imgmultipageprev'   => '← pagina superior',
'imgmultipagenext'   => 'pagina proxima →',
'imgmultigo'         => 'I!',
'imgmultigotopre'    => 'Ire ad paginam',
'imgmultiparseerror' => 'Imago corrupta vel invalida videtur, ergo {{SITENAME}} indicem paginarum extrahere non potest.',

# Table pager
'table_pager_next' => 'Pagina proxima',
'table_pager_prev' => 'Pagina superior',

# Auto-summaries
'autosumm-blank'   => 'paginam vacuavit',
'autosumm-replace' => "multa contenta ex pagina removit, contenta nova: '$1'",
'autoredircomment' => 'Redirigens ad [[$1]]', # This should be changed to the new naming convention, but existed beforehand
'autosumm-new'     => 'Nova pagina: $1',

# Size units
'size-bytes'     => '$1 octeti',
'size-kilobytes' => '$1 chiliocteti',
'size-megabytes' => '$1 megaocteti',
'size-gigabytes' => '$1 gigaocteti',

# Live preview
'livepreview-loading' => 'Onerans…',
'livepreview-ready'   => 'Onerans… Factum!',

);


