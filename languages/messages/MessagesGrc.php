<?php
/** Ancient Greek (Ἀρχαία ἑλληνικὴ)
 *
 * @ingroup Language
 * @file
 *
 * @author LeighvsOptimvsMaximvs
 * @author Lefcant
 * @author AndreasJS
 * @author Neachili
 * @author SPQRobin
 * @author Nike
 * @author Yannos
 * @author Siebrand
 * @author Nychus
 */

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
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Ὑπογραμμίζειν συνδέσμους:',
'tog-highlightbroken'         => 'Μορφοῦν ἀπωλομένους συνδέσμους <a href="" class="new">οὐτωσί</a> (ἄλλως: οὐτωσί <a href="" class="internal">;</a>).',
'tog-justify'                 => 'Στοιχίζειν παραγράφους',
'tog-hideminor'               => 'Κρύπτειν μικρὰς ἐγγραφὰς ἐν προσφάταις ἀλλαγαῖς',
'tog-extendwatchlist'         => 'Ἐφορώμενα ἐκτείνειν ἵνα ἅπασαι φανῶσιν αἱ ἁρμόδιοι ἀλλαγαὶ',
'tog-usenewrc'                => 'Προσκεκοσμημέναι πρόσφαται ἀλλαγαί (JavaScript)',
'tog-numberheadings'          => 'Ἐξαριθμεῖν τίτλους αὐτομάτως',
'tog-showtoolbar'             => 'Δεικνύναι τὴν τῶν ἐργαλείων ἐπιμελείας μετόπην (JavaScript)',
'tog-editondblclick'          => 'Δέλτους δὶς θλιβείσας ἐπιμελεῖσθαι (JavaScript)',
'tog-editsection'             => 'Τμῆμα διὰ συνδέσμου [μεταγράφειν] μεταγράφειν παρέχειν',
'tog-editsectiononrightclick' => 'Τμῆμα μεταγράφειν παρέχειν <br /> διὰ τίτλον δεξιῷ ὀμφαλῷ θλίβειν (JavaScript)',
'tog-showtoc'                 => 'Δεικνύναι πίνακα περιεχομένων (ἐν δέλτοις περιεχούσαις πλείους τῶν 3 ἐπικεφαλίδων)',
'tog-rememberpassword'        => 'Ἐνθυμεῖσθαι σύνδεσίν μου ἐν τῷδε ὑπολογιστῇ',
'tog-editwidth'               => 'Πλαίσιον ἐπιμελείας εἰς πλήρες πλάτος',
'tog-watchcreations'          => 'Προστινθέναι τὰς δέλτους ἃς ποιῶ τοῖς ἐφορώμενοῖς ἔμου',
'tog-watchdefault'            => 'Προστινθέναι τὰς δέλτους ἃς μεταγράφω τοῖς ἐφορώμενοῖς ἔμου',
'tog-watchmoves'              => 'Προστινθέναι τὰς δέλτους ἃς κινῶ τοῖς ἐφορώμενοῖς ἔμου',
'tog-watchdeletion'           => 'Προστινθέναι τὰς δέλτους ἃς διαγράφω τοῖς ἐφορώμενοῖς ἔμου',
'tog-previewonfirst'          => 'Τῆς πρῶτης μεταγράφης, δεικνύναι τὸ προεπισκοπεῖν',
'tog-shownumberswatching'     => 'Δεικνύναι ἀριθμὸν παρακολουθούντων χρηστῶν',
'tog-watchlisthideown'        => 'Οὐ δηλοῦν τὰς ἐμὰς μεταβολὴς ἐν τὰ ἐφορώμενά μου',
'tog-watchlisthideminor'      => 'Οὐ δηλοῦν τὰς μικρὰς μεταβολὴς ἐν τὰ ἐφορώμενά μου',
'tog-showhiddencats'          => 'Κεκρυμμένας κατηγορίας δηλοῦν',

'underline-always' => 'Ἀεὶ',
'underline-never'  => 'Οὔποτε',

'skinpreview' => '(Προεπισκοπεῖν)',

# Dates
'sunday'        => 'Κυριακή',
'monday'        => 'Δευτέρα',
'tuesday'       => 'Τρίτη',
'wednesday'     => 'Τετάρτη',
'thursday'      => 'Πέμπτη',
'friday'        => 'Παρασκευή',
'saturday'      => 'Σάββατοv',
'sun'           => 'Κυ',
'mon'           => 'Δε',
'tue'           => 'Τρ',
'wed'           => 'Τε',
'thu'           => 'Πε',
'fri'           => 'Πα',
'sat'           => 'Σα',
'january'       => 'Ἰανουάριος',
'february'      => 'Φεβρουάριος',
'march'         => 'Μάρτιος',
'april'         => 'Ἀπρίλιος',
'may_long'      => 'Μάιος',
'june'          => 'Ἰούνιος',
'july'          => 'Ἰούλιος',
'august'        => 'Αὔγουστος',
'september'     => 'Σεπτέμβριος',
'october'       => 'Ὀκτώβριος',
'november'      => 'Νοέμβριος',
'december'      => 'Δεκέμβριος',
'january-gen'   => 'Ἰανουαρίου',
'february-gen'  => 'Φεβρουαρίου',
'march-gen'     => 'Μαρτίου',
'april-gen'     => 'Ἀπριλίου',
'may-gen'       => 'Μαΐου',
'june-gen'      => 'Ἰουνίου',
'july-gen'      => 'Ἰουλίου',
'august-gen'    => 'Αὐγούστου',
'september-gen' => 'Σεπτεμβρίου',
'october-gen'   => 'Ὀκτωβρίου',
'november-gen'  => 'Νοεμβρίου',
'december-gen'  => 'Δεκεμβρίου',
'jan'           => 'Ἰαν',
'feb'           => 'Φεβ',
'mar'           => 'Μάρ',
'apr'           => 'Ἀπρ',
'may'           => 'Μάι',
'jun'           => 'Ἰούν',
'jul'           => 'Ἰούλ',
'aug'           => 'Αὔγ',
'sep'           => 'Σεπτ',
'oct'           => 'Ὀκτ',
'nov'           => 'Νοέμ',
'dec'           => 'Δεκ',

# Categories related messages
'pagecategories'  => '{{PLURAL:$1|Γένος|Γένη}}',
'category_header' => 'Χρήματα ἐν γένει "$1"',
'subcategories'   => 'Ὑπογένη',

'about'          => 'Περὶ',
'article'        => 'ἡ χρῆμα',
'newwindow'      => '(ἀνοίξεται ἐν νέᾳ θυρίδι)',
'cancel'         => 'Ἀκυροῦν',
'qbfind'         => 'Εὑρίσκειν',
'qbbrowse'       => 'Νέμου',
'qbedit'         => 'Μεταγράφειν',
'qbpageoptions'  => 'Ἥδε ἡ δέλτος',
'qbmyoptions'    => 'Δέλτοι μου',
'qbspecialpages' => 'Εἰδικαὶ δέλτοι',
'moredotdotdot'  => 'πλέον...',
'mypage'         => 'Δέλτος μου',
'mytalk'         => 'Διάλεκτός μου',
'anontalk'       => 'Διάλεκτος πρὸ τοῦδε τοῦ IP',
'navigation'     => 'Πλοήγησις',
'and'            => 'καί',

# Metadata in edit box
'metadata_help' => 'Μεταδεδομένα:',

'errorpagetitle'    => 'Σφάλμα',
'returnto'          => 'Ἐπανέρχεσθαι εἰς $1.',
'tagline'           => 'Ἀπὸ {{SITENAME}}',
'help'              => 'Βοήθεια',
'search'            => 'Ζητεῖν',
'searchbutton'      => 'Ζητεῖν',
'go'                => 'Ἰέναι',
'searcharticle'     => 'Ἰέναι',
'history'           => 'Τὰ τῆς δέλτου πρότερα',
'history_short'     => 'Τὰ πρότερα',
'info_short'        => 'Μάθησις',
'printableversion'  => 'Ἐκτυπωτέα μορφὴ',
'permalink'         => 'Σύνδεσμος βέβαιος',
'print'             => 'Τυποῦν',
'edit'              => 'Μεταγράφειν',
'editthispage'      => 'Μεταγράφειν τήνδε τὴν δέλτον',
'delete'            => 'Σβεννύναι',
'deletethispage'    => 'Διαγράφειν τήνδε τὴν δέλτον',
'undelete_short'    => 'Ἐπανορθοῦν {{PLURAL:$1|ἕνα μεταγραφέν|$1 μεταγραφέντα}}',
'protect'           => 'Φυλλάττειν',
'protectthispage'   => 'Tήνδε τὴν δέλτον φυλάττειν',
'unprotect'         => 'μὴ φυλάττειν',
'unprotectthispage' => 'Tήνδε τὴν δέλτον  μὴ φυλάττειν',
'newpage'           => 'Δέλτος νέα',
'talkpage'          => 'Διαλέγε τήνδε τὴν δέλτον',
'talkpagelinktext'  => 'Διαλέγεσθαι',
'specialpage'       => 'Εἰδικὴ δέλτος',
'personaltools'     => 'Ἴδια ἐργαλεῖα',
'articlepage'       => 'Χρήματος δέλτον ὁρᾶν',
'talk'              => 'Διάλεξις',
'views'             => 'Ποσάκις ἔσκεπται',
'toolbox'           => 'Σιδηριοκάδος',
'userpage'          => 'Ὁρᾶν δέλτον χρωμένου',
'imagepage'         => 'Ὁρᾶν εἰκόνος δέλτον',
'mediawikipage'     => 'Ὁρᾶν δέλτον μηνυμάτων',
'viewhelppage'      => 'Ὁρᾶν βοηθείας δέλτον',
'categorypage'      => 'Ὁρᾶν γένους δέλτον',
'viewtalkpage'      => 'Ὁρᾶν διάλεκτον',
'otherlanguages'    => 'Βαρβαριστὶ',
'redirectedfrom'    => '(Ἀποστελτὸν παρὰ $1)',
'lastmodifiedat'    => 'Ἥδε ἡ δέλτος ὕστατον μετεβλήθη $2, $1.', # $1 date, $2 time
'protectedpage'     => 'Πεφυλαγμένη δέλτος',
'jumpto'            => 'Πηδᾶν πρὸς:',
'jumptonavigation'  => 'περιήγησις',
'jumptosearch'      => 'ἐρευνᾶν',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Περὶ {{SITENAME}}',
'aboutpage'            => 'Project:Περί',
'currentevents'        => 'Τὰ γιγνόμενα',
'currentevents-url'    => 'Project:Τὰ γιγνόμενα',
'disclaimers'          => 'Ἀποποιήσεις',
'disclaimerpage'       => 'Project:Γενικὴ ἀποποίησις',
'edithelp'             => 'Βοήθεια περὶ τοῦ μεταγράφειν',
'edithelppage'         => 'Help:Βοήθεια περὶ τοῦ μεταγράφειν',
'faq'                  => 'Τὰ πολλάκις αἰτηθέντα',
'faqpage'              => 'Project:Πολλάκις αἰτηθέντα',
'helppage'             => 'Help:Περιεχόμενα',
'mainpage'             => 'Κυρία Δέλτος',
'mainpage-description' => 'Κυρία Δέλτος',
'portal'               => 'Πύλη πολιτείας',
'privacy'              => 'Ἡ περὶ τῶν ἰδίων προαίρεσις',
'sitesupport'          => 'Δῶρα',

'badaccess-group0' => 'Οὐκ ἔξεστί σοι ταῦτα διαπράττειν.',

'ok'                  => 'εἶεν',
'retrievedfrom'       => 'ᾑρεθὲν ἀπὸ "$1"',
'youhavenewmessages'  => 'Ἔχεις $1 ($2).',
'newmessageslink'     => 'νέαι ἀγγελίαι',
'newmessagesdifflink' => 'ἐσχάτη μεταβολή',
'editsection'         => 'μεταγράφειν',
'editold'             => 'μεταγράφειν',
'editsectionhint'     => 'Μεταγράφειν τὸ μέρος: $1',
'toc'                 => 'Περιεχόμενα',
'showtoc'             => 'δεικνύναι',
'hidetoc'             => 'κρύπτειν',
'viewdeleted'         => 'Ὁρᾶν $1;',
'site-rss-feed'       => 'Ἡ τοῦ $1 Ρ.Σ.Σ.-παρασκευή',
'site-atom-feed'      => 'Ἡ τοῦ $1 Ἀτομο-παρασκευή',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Χρῆμα',
'nstab-user'      => 'Δέλτος χρωμένου',
'nstab-image'     => 'Εἰκών',
'nstab-mediawiki' => 'Ἀγγελία',
'nstab-template'  => 'Πρότυπον',
'nstab-help'      => 'Βοήθεια',
'nstab-category'  => 'Γένος',

# General errors
'error'         => 'Σφάλμα',
'filenotfound'  => 'Γραφὴ "$1" οὐχ ηὑρέθη',
'viewsource'    => 'Πηγήν διασκοπεῖν',
'viewsourcefor' => 'Ὑπὲρ τοῦ $1',

# Login and logout pages
'welcomecreation'         => '== Χαῖρε, $1! ==

Λογισμὸν σὸν πεποίηται. Ἔχε μνήμην μεταβάλλειν τὰς τοῦ {{SITENAME}} αἱρέσεις σὰς.',
'yourname'                => 'Ὄνομα χρωμένου:',
'yourpassword'            => 'Σῆμα:',
'login'                   => 'Συνδεῖσθαι',
'nav-login-createaccount' => 'Συνδεῖσθαι/λογισμὸν ποιεῖν',
'userlogin'               => 'Συνδεῖσθαι/λογισμὸν ποιεῖν',
'logout'                  => 'Ἐξέρχεσθαι',
'userlogout'              => 'Ἐξέρχεσθαι',
'notloggedin'             => 'Οὐ συνδεδεμένος',
'nologinlink'             => 'Λογισμὸν ποιεῖν',
'createaccount'           => 'Λογισμὸν ποιεῖν',
'gotaccountlink'          => 'Συνδεῖσθαι',
'createaccountmail'       => 'ἠλεκτρονικῇ ἐπιστολῇ',
'youremail'               => 'Ἠλεκτρονικαὶ ἐπιστολαὶ:',
'username'                => 'Ὄνομα χρωμένου:',
'yourrealname'            => 'Τὸ ἀληθὲς ὄνομα:',
'yourlanguage'            => 'Γλῶττά σου:',
'yournick'                => 'Ἐπωνυμία:',
'email'                   => 'ἠλεκτρονική ἐπιστολή',
'loginerror'              => 'Ἡμάρτηκας περὶ τοῦ συνδεδεκαῖναι',
'loginsuccesstitle'       => 'Καλῶς συνδέδεσαι',
'loginsuccess'            => "'''συνδέδεσαι ἤδη τῷ {{SITENAME}} ὡς \"\$1\".'''",

# Edit page toolbar
'bold_sample' => 'Γράμματα παχέα',
'bold_tip'    => 'Γράμματα παχέα',

# Edit pages
'summary'            => 'Τὸ κεφάλαιον',
'minoredit'          => 'Μικρὰ ἥδε ἡ μεταβολή',
'watchthis'          => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'savearticle'        => 'Γράφειν τὴν δέλτον',
'preview'            => 'Τὸ προεπισκοπεῖν',
'showpreview'        => 'Προεπισκοπεῖν',
'showdiff'           => 'Δεικνύναι τὰς μεταβολάς',
'blockedtitle'       => 'Ἀποκεκλεισμένος ὁ χρώμενος',
'loginreqtitle'      => 'Δεῖ συνδεῖσθαι',
'loginreqlink'       => 'συνδεῖσθαι',
'newarticle'         => '(νέα)',
'editing'            => 'Μεταγράφων $1',
'editingsection'     => 'Μεταγράφων $1 (μέρος)',
'yourtext'           => 'Τὰ ὑπό σου γραφόμενα',
'yourdiff'           => 'Τὰ διαφέροντα',
'template-protected' => '(φυλλάττεται)',

# History pages
'currentrevisionlink' => 'Τὰ νῦν',
'cur'                 => 'ἡ νῦν',
'next'                => 'ἡ ἐχομένη',
'last'                => 'ἡ ὑστάτη',
'page_first'          => 'πρώτη',
'page_last'           => 'ἐσχάτη',
'histfirst'           => 'πρώτη',
'histlast'            => 'ἐσχάτη',

# Revision deletion
'rev-delundel' => 'δεικνύναι/κρύπτειν',

# Diffs
'lineno'   => 'Γραμμή $1·',
'editundo' => 'ἀγένητον τιθέναι',

# Search results
'prevn'        => 'πρότερον $1',
'nextn'        => 'τὸ $1 τὸ ἐχόμενον',
'viewprevnext' => 'Ἐπισκοπεῖν ($1) ($2) ($3)',
'powersearch'  => 'Ζητεῖν ἀναλυτικῶς',

# Preferences page
'preferences'        => 'Αἱρέσεις',
'mypreferences'      => 'Αἱρέσεις μου',
'prefs-edits'        => 'Τοσοῦται αἱ μεταβολαί:',
'prefsnologin'       => 'Οὐ συνδεδεμένος',
'qbsettings-none'    => 'Οὐδέν',
'math'               => 'Τὰ μαθηματικά',
'math_unknown_error' => 'ἄγνωστον σφάλμα',
'prefs-rc'           => 'Αἱ νέαι μεταβολαί',
'prefs-watchlist'    => 'Τὰ ἐφορώμενα',
'saveprefs'          => 'Γράφειν',
'textboxsize'        => 'Τὸ μεταγράφειν',
'searchresultshead'  => 'Ζητεῖν',

# Groups
'group-sysop'      => 'Γέρoντες',
'group-bureaucrat' => 'Ἔφοροι',

'group-sysop-member'      => 'Γέρων',
'group-bureaucrat-member' => 'Ἔφορος',

'grouppage-sysop'      => '{{ns:project}}:Γέροντες',
'grouppage-bureaucrat' => '{{ns:project}}:Ἔφοροι',

# User rights log
'rightsnone' => '(Οὐδέν)',

# Recent changes
'recentchanges'   => 'Αἱ νέαι μεταβολαί',
'rcshowhideminor' => '$1 μικραὶ μεταβολαὶ',
'rcshowhidebots'  => '$1 αὐτόματα',
'rcshowhideliu'   => '$1 χρωμένους συνδεδεμένους',
'rcshowhidemine'  => '$1 μεταβολὰς μου',
'diff'            => 'διαφ.',
'hist'            => 'Προτ.',
'hide'            => 'Κρύπτειν',
'show'            => 'Δεικνύναι',
'minoreditletter' => 'μ',
'newpageletter'   => 'Ν',
'boteditletter'   => 'α',

# Recent changes linked
'recentchangeslinked'       => 'Οἰκεῖαι μεταβολαί',
'recentchangeslinked-title' => 'Μεταβολαὶ οἰκεῖαι "$1"',

# Upload
'upload'            => 'Ἀνάγειν γραφήν',
'uploadnologin'     => 'Οὐ συνδεδεμένος',
'filedesc'          => 'Τὸ κεφάλαιον',
'fileuploadsummary' => 'Τὸ κεφάλαιον:',
'watchthisupload'   => 'Ἐφορᾶν τήνδε τὴν δέλτον',

# Special:Imagelist
'imagelist_name'        => 'Ὄνομα',
'imagelist_user'        => 'Χρώμενος',
'imagelist_size'        => 'Ὁπόσος',
'imagelist_description' => 'Διέξοδος',

# Image description page
'filehist'          => 'Τοῦ ἀρχείου συγγραφή',
'filehist-user'     => 'Χρώμενος',
'filehist-filesize' => 'Μέγεθος',
'filehist-comment'  => 'Σχόλιον',
'imagelinks'        => 'Σύνδεσμοι',

# MIME search
'mimesearch' => 'MIME Ζητεῖν',

# Unused templates
'unusedtemplateswlh' => 'οἱ σύνδεσμοι οἱ ἄλλοι',

# Random page
'randompage' => 'Δέλτος τυχοῦσα',

'brokenredirects-edit'   => '(μεταγράφειν)',
'brokenredirects-delete' => '(διαγράφειν)',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|βαίς|βαίτα}}',
'ncategories'  => '$1 {{PLURAL:$1|Γένος|Γένη}}',
'shortpages'   => 'Δέλτοι μικραί',
'longpages'    => 'Δέλτοι μακραί',
'specialpages' => ' Εἰδικαὶ δέλτοι',
'newpages'     => 'Δέλτοι νέαι',
'ancientpages' => 'Αἱ παλαιόταται δέλτοι',
'move'         => 'κινεῖν',
'movethispage' => 'Κινεῖν τήνδε τὴν δέλτον',

# Book sources
'booksources-go' => 'Ἰέναι',

# Special:Log
'specialloguserlabel' => 'Χρήστης·',
'log-search-submit'   => 'Ἰέναι',

# Special:Allpages
'allpages'       => 'Πᾶσαι αἱ δέλτοι',
'alphaindexline' => '$1 ἕως $2',
'allarticles'    => 'Πάντες γραφαί',
'allpagessubmit' => 'Ἰέναι',

# Special:Categories
'categories' => 'Γένη',

# E-mail user
'emailmessage' => 'Ἀγγελία',
'emailsend'    => 'Πέμπειν',

# Watchlist
'watchlist'            => 'Τὰ ἐφορώμενά μου',
'mywatchlist'          => 'Τὰ ἐφορώμενά μου',
'watchlistfor'         => "(πρό '''$1''')",
'watchnologin'         => 'Οὐ συνδεδεμένος',
'watch'                => 'Ἐφορᾶν',
'watchthispage'        => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'unwatch'              => 'Ἀνεφορᾶν',
'notanarticle'         => 'οὐ χρῆμα δέλτος',
'watchlist-hide-bots'  => 'Κρύπτειν τὰς ὑπ᾿ αὐτομάτων μεταβολάς',
'watchlist-show-own'   => 'Δεικνύναι τοὺς ἐράνους μου',
'watchlist-hide-own'   => 'Κρύπτειν τὰς ὑπ᾿ ἐμοῦ μεταβολάς',
'watchlist-show-minor' => 'Δεικνύναι τὰς μικρὰς μεταβολάς',
'watchlist-hide-minor' => 'Κρύπτειν τὰς μικρὰς μεταβολάς',

# Delete/protect/revert
'deletepage'      => 'Διαγράφειν τὴν δέλτον',
'confirm'         => 'Κυροῦν',
'exblank'         => 'δέλτος κενὴ ἦν',
'protectcomment'  => 'Σχόλιον:',
'protect-default' => '(κριτήριον)',

# Restrictions (nouns)
'restriction-edit' => 'Μεταγράφειν',
'restriction-move' => 'Kινεῖν',

# Restriction levels
'restriction-level-sysop'         => 'πλήρως διαφυλαττομένη',
'restriction-level-autoconfirmed' => 'ἡμιδιαφυλαττομένη',

# Undelete
'viewdeletedpage'        => 'Δεικνύναι διαγραφείσας δέλτους',
'undeletebtn'            => 'Ἀνορθοῦν',
'undelete-search-submit' => 'Ζητεῖν',

# Namespace form on various pages
'blanknamespace' => '(Κυρία γραφή)',

# Contributions
'contributions' => 'Ἔρανοι χρωμένου',
'mycontris'     => 'Ἔρανοί μου',
'uctop'         => '(ἄκρον)',
'month'         => 'Μήν:',
'year'          => 'Ἔτος:',

'sp-contributions-submit' => 'Ζητεῖν',

# What links here
'whatlinkshere'       => 'Τὰ ἐνθάδε ἄγοντα',
'whatlinkshere-title' => 'Δέλτοι ἐζεύγμεναι ὑπο $1',
'whatlinkshere-prev'  => '{{PLURAL:$1|πρότερον|Τὰ $1 πρότερα}}',
'whatlinkshere-next'  => '{{PLURAL:$1|ἑξῆς|οἱ $1 ἑξαῖ}}',
'whatlinkshere-links' => '← σύνδεσμοι',

# Block/unblock
'ipboptions'         => 'βʹ ὥραι:2 hours,αʹ ἡμέρα day:1 day,γʹ ἡμέραι:3 days,ζʹ ἡμέραι:1 week,ιδʹ ἡμέραι:2 weeks,αʹ μήν:1 month,γʹ μήνες:3 months,ϝʹ μήνες:6 months,αʹ ἔτος:1 year,ἄπειρον:infinite', # display1:time1,display2:time2,...
'ipblocklist-submit' => 'Ζητεῖν',
'infiniteblock'      => 'ἄπειρον',
'blocklink'          => 'ἀποκλῃειν',
'unblocklink'        => 'χαλᾶν',
'contribslink'       => 'Ἔρανοι',

# Move page
'move-page-legend' => 'Κινεῖν τὴν δέλτον',
'movearticle'      => 'Κινεῖν τὴν δέλτον:',
'movenologin'      => 'Οὐ συνδεδεμένος',
'move-watch'       => 'Ἑφορᾶν τήνδε τὴν δέλτον',
'movereason'       => 'Ἀπολογία:',

# Namespace 8 related
'allmessagesname' => 'Ὄνομα',

# Thumbnails
'thumbnail-more' => 'Αὐξάνειν',

# Tooltip help for the actions
'tooltip-pt-userpage'       => 'Τὴν δέλτον χρωμένου ἐμήν',
'tooltip-pt-mytalk'         => 'Διάλεκτός μου',
'tooltip-pt-preferences'    => 'Αἱρέσεις μου',
'tooltip-pt-watchlist'      => 'Κατάλογος τῶν ἐφορωμένων μου',
'tooltip-pt-mycontris'      => 'Κατάλογος τῶν ἔρανων μου',
'tooltip-pt-logout'         => 'Ἐξέρχεσθαι',
'tooltip-ca-talk'           => 'Διάλεκτος περὶ τῆς δέλτου',
'tooltip-ca-edit'           => 'Ἔξεστι σοι μεταγράφειν τήνδε τὴν δέλτον. Προεπισκοπεῖν πρὶν ἂν γράφῃς τὴν δέλτον.',
'tooltip-ca-protect'        => 'Ἀμύνειν τῇδε τῇ δέλτῳ',
'tooltip-ca-delete'         => 'Διαγράφειν τήνδε τὴν δέλτον',
'tooltip-ca-move'           => 'Κινεῖν τήνδε τὴν δέλτον',
'tooltip-ca-watch'          => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'tooltip-search'            => 'Ζητεῖν {{SITENAME}}',
'tooltip-p-logo'            => 'Δέλτος Μεγίστη',
'tooltip-n-mainpage'        => 'Πορεύεσθαι τὴν κυρίαν Δέλτον',
'tooltip-n-randompage'      => 'Τινὰ γραφὴν χύδην δηλοῦν.',
'tooltip-n-help'            => 'Μάθησις περὶ τῆσδε Οὐίκεως',
'tooltip-n-sitesupport'     => 'Τρέφειν ἡμᾶς',
'tooltip-ca-nstab-main'     => 'χρῆμα δέλτον ὁρᾶν',
'tooltip-ca-nstab-user'     => 'Δέλτος χρωμένου ὁρᾶν',
'tooltip-ca-nstab-category' => 'Ἐπισκοπεῖν τὴν τῆς κατηγορίας δέλτον',
'tooltip-minoredit'         => 'Δεικνύναι ἥδε ἡ μεταβολή μικρά εἴναι',
'tooltip-save'              => 'Γράφειν τὰς μεταβολάς σου',

# Attribution
'others' => 'ἄλλοι',

# Browsing diffs
'previousdiff' => '← ἡ μεταβολὴ ἡ προτέρη',
'nextdiff'     => 'ἡ μεταβολὴ ἡ ἐχομένη →',

# Media information
'show-big-image-thumb' => '<small>Τοῦδε προεπισκοπεῖν μέγεθος: $1 × $2 εἰκονοστοιχεία</small>',

# Special:Newimages
'ilsubmit' => 'Ζητεῖν',

'exif-componentsconfiguration-0' => 'Οὐκ ἔστι',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Πᾶσαι',
'imagelistall'     => 'Πᾶσαι',
'watchlistall2'    => 'Πάντα',
'namespacesall'    => 'πάντα',
'monthsall'        => 'ἅπαντες',

# Trackbacks
'trackbackremove' => ' ([$1 Διαγράφειν])',

# action=purge
'confirm_purge_button' => 'εἶεν',

# Multipage image navigation
'imgmultipageprev' => '← Δέλτος προτέρα',
'imgmultipagenext' => 'Δέλτος ἡ ἐχομένη →',
'imgmultigo'       => 'Ἰέναι!',

# Table pager
'table_pager_next'         => 'Δέλτος ἡ ἐχομένη',
'table_pager_prev'         => 'Δέλτος προτέρα',
'table_pager_first'        => 'Δέλτος πρώτη',
'table_pager_last'         => 'Δέλτος ἐσχάτη',
'table_pager_limit_submit' => 'Ἰέναι',

# Auto-summaries
'autosumm-new' => 'Δέλτος νέα: $1',

# Size units
'size-bytes'     => '$1 Δ',
'size-kilobytes' => '$1 ΧΔ',
'size-megabytes' => '$1 ΜΔ',
'size-gigabytes' => '$1 ΓΔ',

# Watchlist editor
'watchlistedit-raw-titles' => 'Τίτλοι:',

);
