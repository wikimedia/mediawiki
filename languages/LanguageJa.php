<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */
global $IP;
require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesJa = array(
	NS_MEDIA          => "Media", /* Media */
	NS_SPECIAL        => "特別", /* Special */
	NS_MAIN           => "",
	NS_TALK           => "ノート", /* Talk */
	NS_USER           => "利用者", /* User */
	NS_USER_TALK      => "利用者‐会話", /* User_talk */
	NS_PROJECT        => $wgMetaNamespace, /* Wikipedia */
	NS_PROJECT_TALK   => "{$wgMetaNamespace}‐ノート", /* Wikipedia_talk */
	NS_IMAGE          => "画像", /* Image */
	NS_IMAGE_TALK     => "画像‐ノート", /* Image_talk */
	NS_MEDIAWIKI      => "MediaWiki", /* MediaWiki */
	NS_MEDIAWIKI_TALK => "MediaWiki‐ノート", /* MediaWiki_talk */
	NS_TEMPLATE       => "Template", /* Template */
	NS_TEMPLATE_TALK  => "Template‐ノート", /* Template_talk */
	NS_HELP           => "Help", /* Help */
	NS_HELP_TALK      => "Help‐ノート", /* Help_talk */
	NS_CATEGORY       => "Category", /* Category */
	NS_CATEGORY_TALK  => "Category‐ノート" /* Category_talk */
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsJa = array(
	"なし", "左端", "右端", "ウィンドウの左上に固定"
);

/* private */ $wgSkinNamesJa = array(
	'standard' => "標準",
	'nostalgia' => "ノスタルジア",
	'cologneblue' => "ケルンブルー",
) + $wgSkinNamesEn;

/* private */ $wgDateFormatsJa = array(
	MW_DATE_DEFAULT => '2001年1月15日 16:12 (デフォルト)',
	MW_DATE_ISO => '2001-01-15 16:12:34'
);

/* private */ $wgWeekdayAbbreviationsJa = array(
	"日", "月", "火", "水", "木", "金", "土"
);

/* private */ $wgAllMessagesJa = array(

# The navigation toolbar, int: is used here to make sure that the appropriate
# messages are automatically pulled from the user-selected language file.

/*
The sidebar for MonoBook is generated from this message, lines that do not
begin with * or ** are discarded, furthermore lines that do begin with ** and
do not contain | are also discarded, but don't depend on this behaviour for
future releases. Also note that since each list value is wrapped in a unique
XHTML id it should only appear once and include characters that are legal
XHTML id names.

Note to translators: Do not include this message in the language files you
submit for inclusion in MediaWiki, it should always be inherited from the
parent class in order maintain consistency across languages.
*/
# 'sidebar' => '',

# User preference toggles
'tog-underline' => 'リンクの下線:',
'tog-highlightbroken' => '未作成のページへのリンクをハイライトする',
'tog-justify' => '段落を均等割り付けする',
'tog-hideminor' => '最近更新したページから細部の編集を隠す',
'tog-usenewrc' => '最近更新したページを拡張する（対応していないブラウザもあります）',
'tog-numberheadings' => '見出しに番号を振る',
'tog-showtoolbar' => '編集ボタンを表示する',
'tog-editondblclick' => 'ダブルクリックで編集する (JavaScript)',
'tog-editsection' => 'セクション編集用リンクを有効にする',
'tog-editsectiononrightclick' => 'セクションタイトルの右クリックでセクション編集を行えるようにする (JavaScript)',
'tog-showtoc' => '目次を表示する (4つ以上の見出しがあるページ)',
'tog-rememberpassword' => 'セッションを越えてパスワードを記憶する',
'tog-editwidth' => 'テキストボックスを横幅いっぱいに表示する',
'tog-watchdefault' => '編集した記事をウォッチリストに追加する',
'tog-minordefault' => '細部の編集をデフォルトでチェックする',
'tog-previewontop' => 'プレビューをテキストボックスの前に配置する',
'tog-previewonfirst' => '編集時にはじめからプレビューを表示する',
'tog-nocache' => 'ページをキャッシュしない',
'tog-enotifwatchlistpages' => 'ページが更新されたときにメールを受け取る',
'tog-enotifusertalkpages' => '自分の会話ページが更新されたときにメールを受け取る',
'tog-enotifminoredits' => '細部の編集でもメールを受け取る',
'tog-enotifrevealaddr' => 'あなた以外に送られる通知メールにあなたのメールアドレスを記載する',
'tog-shownumberswatching' => 'ページをウォッチしているユーザー数を表示する',
'tog-fancysig' => '署名を自動的に利用者ページへリンクさせない',
'tog-externaleditor' => '編集に外部アプリケーションを使う',
'tog-externaldiff' => '差分表示に外部アプリケーションを使う',

'underline-always' => '常に付ける',
'underline-never' => '常に付けない',
'underline-default' => 'ブラウザに従う',

'skinpreview' => '(プレビュー)',

# dates
'sunday' => '日曜日',
'monday' => '月曜日',
'tuesday' => '火曜日',
'wednesday' => '水曜日',
'thursday' => '木曜日',
'friday' => '金曜日',
'saturday' => '土曜日',
'january' => '1月',
'february' => '2月',
'march' => '3月',
'april' => '4月',
'may_long' => '5月',
'june' => '6月',
'july' => '7月',
'august' => '8月',
'september' => '9月',
'october' => '10月',
'november' => '11月',
'december' => '12月',
'jan' => '1月',
'feb' => '2月',
'mar' => '3月',
'apr' => '4月',
'may' => '5月',
'jun' => '6月',
'jul' => '7月',
'aug' => '8月',
'sep' => '9月',
'oct' => '10月',
'nov' => '11月',
'dec' => '12月',
# Bits of text used by many pages:
#
'categories' => 'カテゴリ',
'category' => 'カテゴリ',
'category_header' => 'カテゴリ: “$1”',
'subcategories' => 'サブカテゴリ',


'linktrail' => '/^$/sD',
'linkprefix' => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpage' => 'メインページ',
'mainpagetext' => 'Wikiソフトウェアが正常にインストールされました。',
'mainpagedocfooter' => '[http://meta.wikimedia.org/wiki/MediaWiki_localization インターフェイスの変更方法]や、そのほかの使い方・設定に関しては[http://meta.wikimedia.org/wiki/Help:Contents ユーザーズガイド]を参照してください。',

'portal' => 'コミュニティ・ポータル',
'portal-url' => 'Project:コミュニティ・ポータル',
'about' => '解説',
'aboutsite' => '{{SITENAME}}について',
'aboutpage' => 'Project:About',
'article' => '本文',
'help' => 'ヘルプ',
'helppage' => 'Help:Contents',
'bugreports' => 'バグの報告',
'bugreportspage' => 'Project:バグの報告',
'sitesupport' => '寄付',
'sitesupport-url' => 'Project:Site support',
'faq' => 'FAQ',
'faqpage' => '{{ns:4}}:FAQ',
'edithelp' => '編集の仕方',
'newwindow' => '（新しいウィンドウが開きます）',
'edithelppage' => 'Help:編集の仕方',
'cancel' => '中止',
'qbfind' => '検索',
'qbbrowse' => '閲覧',
'qbedit' => '編集',
'qbpageoptions' => '個人用ツール',
'qbpageinfo' => 'ページ情報',
'qbmyoptions' => 'オプション',
'qbspecialpages' => '特別ページ',
'moredotdotdot' => 'すべて表示する',
'mypage' => 'マイ・ページ',
'mytalk' => 'マイ・トーク',
'anontalk' => 'このIPとの会話',
'navigation' => 'ナビゲーション',

# Metadata in edit box
'metadata' => 'メタデータ',
'metadata_page' => '{{ns:Project}}:Metadata',

'currentevents' => '最近の出来事',
'currentevents-url' => '最近の出来事',

'disclaimers' => '免責事項',
'disclaimerpage' => 'Project:免責事項',
'errorpagetitle' => 'エラー',
'returnto' => '$1 に戻る。',
'tagline' => '出典: {{SITENAME}}',
'whatlinkshere' => 'リンク元',
'help' => 'ヘルプ',
'search' => '検索',
'go' => '表示',
'history' => '履歴',
'history_short' => '履歴',
'info_short' => 'ページ情報',
'printableversion' => '印刷用バージョン',
'print' => 'Print',
'edit' => '編集',
'editthispage' => 'このページを編集',
'delete' => '削除',
'deletethispage' => 'このページを削除',
'undelete_short1' => '削除済1版',
'undelete_short' => '削除済$1版',
'protect' => '保護',
'protectthispage' => 'このページを保護',
'unprotect' => '保護解除',
'unprotectthispage' => 'ページ保護解除',
'newpage' => '新規ページ',
'talkpage' => 'このページのノート',
'specialpage' => '特別ページ',
'personaltools' => '個人用ツール',
'postcomment' => '新規にコメントを投稿',
'addsection' => '+',
'articlepage' => '項目を表示',
'subjectpage' => 'サブジェクト・ページ',
'talk' => 'ノート',
'views' => '表示',
'toolbox' => 'ツールボックス',
'userpage' => '利用者ページを表示',
'wikipediapage' => '{{SITENAME}}ページを表示',
'imagepage' => '画像のページを表示',
'viewtalkpage' => 'ノートを表示',
'otherlanguages' => '他の言語',
'redirectedfrom' => '（$1 から転送）',
'lastmodified' => '最終更新 $1。',
'viewcount' => 'このページは $1 回アクセスされました。',
'copyright' => 'Content is available under $1.',
'poweredby' => '{{SITENAME}} is powered by [http://www.mediawiki.org/ MediaWiki], an open source wiki engine.',
'printsubtitle' => '(From {{SERVER}}/)',
'protectedpage' => '保護されたページ',
'administrators' => '{{ns:Project}}:管理者',

'sysoptitle' => '管理者によるアクセスが必要',
'sysoptext' => 'あなたの要求した処理は管理者のみが実行できます。
$1を参照してください。',
'developertitle' => '開発者によるアクセスが必要',
'developertext' => 'あなたの要求した処理は開発者のみが実行できます。
$1 を参照してください。',

'badaccess' => '権限がありません',
'badaccesstext' => 'あなたの要求した処理は "$2" の権限を持ったユーザーのみが実行できます。詳しくは $1 を参照してください。',

'versionrequired' => 'MediaWiki Version $1 が必要',
'versionrequiredtext' => 'このページの利用には MediaWiki Version $1 が必要です。[[{{ns:Special}}:Version]]を確認してください。',

'nbytes' => '$1 バイト',
'ok' => 'OK',
'sitetitle' => '{{SITENAME}}',
'pagetitle' => '$1 - {{SITENAME}}',
'sitesubtitle' => '-',
'retrievedfrom' => ' "$1" より作成',
'newmessages' => '$1が届いています。',
'newmessageslink' => '新しいメッセージ',
'editsection' => '編集',
'toc' => '目次',
'showtoc' => '表示',
'hidetoc' => '非表示',
'thisisdeleted' => '$1 を参照または復帰する。',
'restorelink1' => '削除された 1 編集',
'restorelink' => '削除された $1 編集',
'feedlinks' => 'Feed:',
'sitenotice' => '-',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => '本文',
'nstab-user' => '利用者ページ',
'nstab-media' => 'Media',
'nstab-special' => '特別ページ',
'nstab-wp' => '解説',
'nstab-image' => '画像',
'nstab-mediawiki' => '定型文',
'nstab-template' => 'Template',
'nstab-help' => 'Help',
'nstab-category' => 'カテゴリ',

# Main script and global functions
#
'nosuchaction' => 'そのような動作はありません',
'nosuchactiontext' => 'このURIで指定された動作は{{SITENAME}}で認識できません。',
'nosuchspecialpage' => 'そのような特別ページはありません',
'nospecialpagetext' => '要求された特別ページは存在しません。有効な特別ページの一覧は[[{{ns:Special}}:Specialpages]]にあります。',

# General errors
#
'error' => 'エラー',
'databaseerror' => 'データベース・エラー',
'dberrortext' => 'データベース検索の文法エラー。これは恐らくソフトウェアのバグを表しています。

最後に実行を試みた問い合わせ: 
<blockquote><tt>$1</tt></blockquote>

from within function "<tt>$2</tt>". MySQL returned error "<tt>$3: $4</tt>".',
'dberrortextcl' => 'A database query syntax error has occurred.
The last attempted database query was:
"$1"
from within function "$2".
MySQL returned error "$3: $4".
',
'noconnect' => '申し訳ありません。何らかの問題によりデータベースに接続できません。<br/>$1',
'nodb' => 'データベース $1 を選択できません。',
'cachederror' => 'あなたがアクセスしたページのコピーを保存したものを表示しています。また、コピーは更新されません。',
'laggedslavemode' => '警告: ページに最新の編集が反映されていない可能性があります。反映されるまでしばらくお待ちください。',
'readonly' => 'データベースはロックされています',
'enterlockreason' => 'ロックする理由を入力してください。ロックが解除されるのがいつになるかの見積もりについても述べてください。',
'readonlytext' => 'データベースは現在、新しいページの追加や編集を受け付けない「ロック状態」になっています。これはおそらく定期的なメンテナンスのためで、メンテナンス終了後は正常な状態に復帰します。データベースをロックした管理者は次のような説明をしています:

$1

----
The database is currently locked to new entries and other modifications, probably for routine database maintenance, after which it will be back to normal. The administrator who locked it offered this explanation:

$1',
'missingarticle' => '<p>"$1" という題のページは見つかりませんでした。すでに削除された版を参照しようとしている可能性があります。これがソフトウェアのバグだと思われる場合は、URIと共に管理者に報告して下い。</p>
<p>The database did not find the text of a page that it should have found, named "$1". This is usually caused by following an outdated diff or history link to a page that has been deleted. If this is not the case, you may have found a bug in the software. Please report this to an administrator, making note of the URL.</p>',


'readonly_lag' => 'データベースはスレーブ・サーバがマスタ・サーバに同期するまで自動的にロックされています。しばらくお待ちください。

The database has been automatically locked while the slave database servers catch up to the master.',
'internalerror' => '内部処理エラー',
'filecopyerror' => 'ファイル "$1" から "$2" のコピーに失敗しました。',
'filerenameerror' => 'ファイル名を "$1" から "$2" へ変更できませんでした。',
'filedeleteerror' => 'ファイル "$1" の削除に失敗しました。',
'filenotfound' => 'ファイル "$1" が見つかりません。',
'unexpected' => '値が異常です: $1 = "$2"',
'formerror' => 'エラー: フォームの送信に失敗しました。',
'badarticleerror' => 'このページでは要求された処理を行えません。',
'cannotdelete' => '指定されたページ、または画像の削除に失敗しました。',
'badtitle' => 'ページタイトルの間違い',
'badtitletext' => '要求されたページは無効か、何もないか、正しくない inter-language または inter-wiki のタイトルです。',
'perfdisabled' => 'この機能はデータベースの負荷を軽くするために現在使えなくなっています。',
'perfdisabledsub' => 'ここには $1 のコピーを表示しています。',
'perfcached' => '以下のデータはキャッシュであり、しばらく更新されていません。',
'wrong_wfQuery_params' => 'Incorrect parameters to wfQuery()<br />
Function: $1<br />
Query: $2
',
'viewsource' => 'ソースを表示',
'protectedtext' => 'このページは編集できないように保護されています。これにはいくつか理由があります。詳しくは[[{{ns:Project}}:保護されたページ]]をご覧ください。

下にソースを表示しています。',

'sqlhidden' => '(SQL query hidden)',

# Login and logout pages
#
'logouttitle' => 'ユーザー ログアウト',
'logouttext' => '<p><strong>ログアウトしました。</strong>このまま{{SITENAME}}を匿名で使い続けることができます。もう一度ログインして元の、あるいは別のユーザーとして使うこともできます。</p>
<p>※いくつかのページはブラウザのキャッシュをクリアするまでログインしているかのように表示されることがあります。</p>',

'welcomecreation' => '== $1 さん、ようこそ! ==
あなたのアカウントができました。お好みに合わせて[[Special:Preferences|ユーザーオプション]]を変更することをお忘れなく。',


'loginpagetitle' => 'ユーザー ログイン',
'yourname' => 'あなたのユーザー名',
'yourpassword' => 'あなたのパスワード',
'yourpasswordagain' => 'パスワード再入力',
'newusersonly' => '（新規ユーザーのみ）',
'remembermypassword' => 'セッションを越えてパスワードを記憶する',
'yourdomainname' => 'あなたのドメイン',
'externaldberror' => '外部の認証データベースでエラーが発生たか、または外部アカウント情報の更新が許可されていません。',
'loginproblem' => '<b>ログインでエラーが発生しました。</b><br />再度実行してください。',
'alreadyloggedin' => '<strong>ユーザー $1 は、すでにログイン済みです。</strong><br />',

'login' => 'ログイン',
'loginprompt' => '{{SITENAME}}にログインするにはクッキーを有効にする必要があります。',
'userlogin' => 'ログインまたはアカウント作成',
'logout' => 'ログアウト',
'userlogout' => 'ログアウト',
'notloggedin' => 'ログインしていません',
'createaccount' => '新規アカウント作成',
'createaccountmail' => '電子メール経由',
'badretype' => '両方のパスワードが一致しません。',
'userexists' => 'そのユーザー名はすでに使われています。ほかの名前をお選びください。',
'youremail' => 'メールアドレス*',
'yourrealname' => 'あなたの本名*',
'yourlanguage' => 'インターフェース言語',
'yourvariant' => '字体変換',
'yournick' => 'ニックネーム（署名用）',
'email' => 'メールアドレス',
'emailforlost' => '<nowiki>*</nowiki>マークの項目は任意です。メールアドレスを入力すると、他の人があなたのメールアドレスを知ることなくあなたにメールを送れるようになります。またパスワードを忘れた際にメールでパスワードの再発行を受けられます。本名を入力すると、ページ・クレジットに利用者名（アカウント名）の代わりに本名が表示されます。',
'prefs-help-email-enotif' => 'このアドレスはあなたが有効にした各種メール通知の送信先としても利用されます。',
'prefs-help-realname' => '* 本名 (任意): 本名を入力すると、ページ・クレジットに利用者名（アカウント名）の代わりに本名が表示されます。',
'loginerror' => 'ログイン失敗',
'prefs-help-email' => '* メールアドレス (任意): メールアドレスを入力すると、他のユーザーがあなたの利用者ページまたは会話ページから、あなたの身元を知ることなく、あなたに連絡が取れるようになります。',
'nocookiesnew' => 'ユーザーのアカウントは作成されましたが、ログインしていません。{{SITENAME}}ではログインにクッキーを使います。あなたはクッキーを無効な設定にしているようです。クッキーを有効にしてから作成したユーザー名とパスワードでログインしてください。',
'nocookieslogin' => '{{SITENAME}}ではログインにクッキーを使います。あなたはクッキーを無効な設定にしているようです。クッキーを有効にして、もう一度試してください。',
'noname' => 'ユーザ名を正しく指定していません。',
'loginsuccesstitle' => 'ログイン成功',
'loginsuccess' => '{{SITENAME}} に "$1" としてログインしました。',
'nosuchuser' => '"$1" というユーザーは見当たりません。綴りが正しいことを再度確認するか、下記のフォームを使ってアカウントを作成してください。',
'nosuchusershort' => '"$1" というユーザーは見当たりません。綴りが正しいことを再度確認してください。',
'wrongpassword' => 'パスワードが間違っています。再度入力してください。',
'mailmypassword' => '新しいパスワードをメールで送る',
'passwordremindertitle' => 'Password reminder from {{SITENAME}}',
'passwordremindertext' => 'どなたか（$1 のIPアドレスの使用者）が{{SITENAME}}のログイン用パスワードの再発行を依頼しました。

ユーザー "$2" のパスワードを "$3" に変更しました。
ログインして別のパスワードに変更してください。',
'noemail' => 'ユーザー "$1" のメールアドレスは登録されていません。',
'passwordsent' => '新しいパスワードを "$1" さんの登録済みメールアドレスに送信しました。メールを受け取ったら、再度ログインしてください。',
'eauthentsent' => '指定されたメールアドレスにアドレス確認のためのメールを送信しました。このアカウントが本当にあなたのものであるか確認するため、あなたがメールの内容に従わない限り、その他のメールはこのアカウント宛には送信されません。',
# 'loginend' => '',
'mailerror' => 'メールの送信中にエラーが発生しました: $1',
'acct_creation_throttle_hit' => 'あなたは既に $1 アカウントを作成しています。これ以上作成できません。',
'emailauthenticated' => 'あなたのメールアドレスは $1 に認証されています。',
'emailnotauthenticated' => 'あなたのメールアドレスは<strong>認証されていません</strong>。認証されるまで以下のいかなるメールも送られません。',
'noemailprefs' => '<strong>メールアドレスが登録されていません</strong>。以下の機能は無効です。',
'emailconfirmlink' => '電子メールアドレスを確認する',
'invalidemailaddress' => '入力されたメールアドレスが正しい形式に従っていないため、受け付けられません。正しい形式で入力し直すか、メールアドレス欄を空にしてください。',

# Edit page toolbar
'bold_sample' => '強い強調（太字）',
'bold_tip' => '強い強調（太字）',
'italic_sample' => '弱い強調（斜体）',
'italic_tip' => '弱い強調（斜体）',
'link_sample' => '項目名',
'link_tip' => '内部リンク',
'extlink_sample' => 'http://www.example.com リンクのタイトル',
'extlink_tip' => '外部リンク（http:// を忘れずにつけてください）',
'headline_sample' => '見出し',
'headline_tip' => '標準の見出し',
'math_sample' => '\\\\int f(x)dx',
'math_tip' => '数式 (LaTeX)',
'nowiki_sample' => 'そのまま表示させたい文字を入力',
'nowiki_tip' => '入力文字をそのまま表示',
'image_sample' => 'Example.jpg',
'image_tip' => '埋め込み画像（[[画像:～]]に直してください）',
'media_sample' => 'Example.mp3',
'media_tip' => 'メディアファイル（音声）へのリンク',
'sig_tip' => '時刻つきの署名',
'hr_tip' => '水平線（利用は控えめに）',
# 'infobox' => '',
# alert box shown in browsers where text selection does not work, test e.g. with mozilla or konqueror
# 'infobox_alert' => '',

# Edit pages
#
'summary' => '編集内容の要約',
'subject' => '題名・見出し',
'minoredit' => 'これは細部の編集です',
'watchthis' => 'ウォッチリストに追加',
'savearticle' => '保存する',
'preview' => 'プレビュー',
'showpreview' => 'プレビューを実行',
'showdiff' => '差分を表示',
'blockedtitle' => '投稿ブロック',
'blockedtext' => 'ご使用のユーザー名またはIPアドレスは $1 によって投稿をブロックされています。その理由は次の通りです。
:$2

$1 または他の[[Project:管理者|管理者]]にこの件についてメールで問い合わせることができます。ただし、[[Special:Preferences|オプション]]に正しい電子メールアドレスが登録されていない場合、「このユーザーにメールを送信」機能が使えないことに注意してください。あなたのIPアドレスは「$3」です。問い合わせを行う際には、このIPアドレスを必ず書いてください。',


'whitelistedittitle' => '編集にはログインが必要',
'whitelistedittext' => 'このページを編集するには[[Special:Userlogin|ログイン]]する必要があります。',
'whitelistreadtitle' => '閲覧にはログインが必要',
'whitelistreadtext' => 'このページを閲覧するには[[Special:Userlogin|ログイン]]する必要があります。',
'whitelistacctitle' => 'アカウントの作成は許可されていません',
'whitelistacctext' => '{{SITENAME}}のアカウントを作成するには、適切な権限を持ったユーザーで[[Special:Userlogin|ログイン]]する必要があります。',
'loginreqtitle' => 'ログインが必要',
# 'loginreqtext' => '',
'accmailtitle' => 'パスワードを送信しました',
'accmailtext' => '"$1" のパスワードを $2 に送信しました。',
'newarticle' => '(新規)',
'newarticletext' => 'ページを新規に作成するには新しい内容を書き込んでください。',
'talkpagetext' => '<!-- MediaWiki:talkpagetext -->',
'anontalkpagetext' => "----
''これはアカウントをまだ作成していないか、あるいは使っていない匿名ユーザーのための会話ページです。{{SITENAME}}では匿名ユーザーの識別は利用者名の代わりに[[IPアドレス]]を用います。IPアドレスは何人かで共有されることがあります。もしも、あなたが匿名ユーザーで無関係なコメントがここに寄せられる場合は、[[Special:Userlogin|アカウントを作成するかログインして]]他の匿名ユーザーと間違えられないようにしてくださるようお願いします。",
'noarticletext' => '（{{SITENAME}}には現在この名前の項目はありません）',
'clearyourcache' => "'''注意:''' 保存した後、ブラウザのキャッシュをクリアする必要があります。'''Mozilla / Firefox / Safari:''' [Shift] を押しながら [再読み込み] をクリック、または [Shift]-[Ctrl]-[R] (Macでは [Cmd]-[Shift]-[R]); '''IE:''' [Ctrl] を押しながら [更新] をクリック、または [Ctrl]-[F5]; '''Konqueror:''' [再読み込み] をクリック、または [F5]; '''Opera:''' 「ツール」→「設定」からキャッシュをクリア。",
'usercssjsyoucanpreview' => '<strong>Tip:</strong> 「プレビューを実行」ボタンを使うと保存前に新しいスタイルシート・スクリプトをテストできます。',
'usercsspreview' => "'''あなたはユーザスタイルシートをプレビューしています。まだ保存されていないので注意してください。'''",
'userjspreview' => "'''あなたはユーザスクリプトをテスト・プレビューしています。まだ保存されていないので注意してください。'''",
'updated' => '（更新）',
'note' => '<strong>注釈:</strong> ',
'previewnote' => 'これはプレビューです。まだ保存されていません!',
'previewconflict' => 'このプレビューは、上の文章編集エリアの文章を保存した場合にどう見えるようになるかを示すものです。',
'editing' => '$1 を編集中',
'editingsection' => '$1 を編集中 (セクション)',
'editingcomment' => '$1 を編集中 (新規コメント)',
'editconflict' => '編集競合: $1',
'explainconflict' => 'あなたがこのページを編集し始めた後に、他の誰かがこのページを変更しました。上側のテキストエリアは現在の最新の状態です。あなたの編集していた文章は下側のテキストエリアに示されています。編集していた文章を、上側のテキストエリアの文章に組み込んでください。<strong>上側のテキストエリアの内容だけ</strong>が、「{{int:Savearticle}}」をクリックした時に実際に保存されます。',
'yourtext' => 'あなたの文章',
'storedversion' => '保存された版',
'nonunicodebrowser' => '<strong>警告: あなたの使用しているブラウザはUnicode互換ではありません。項目を編集する前にブラウザを変更してください。</strong>',
'editingold' => '<strong>警告: あなたはこのページの古い版を編集しています。もしこの文章を保存すると、この版以降に追加された全ての変更が無効になってしまいます。</strong>',
'yourdiff' => 'あなたの更新内容',
'copyrightwarning' => "'''■投稿する前に以下を確認してください■'''
* {{SITENAME}}に投稿された文書は、すべて$2（詳細は$1を参照）によって公開されることに同意してください。
* あなたの文章が他人によって自由に編集、配布されることを望まない場合は、投稿を控えてください。
* あなたの投稿する文章はあなた自身によって書かれたものであるか、パブリック・ドメインかそれに類する自由なリソースからの複製であることを約束してください。'''あなたが著作権を保持していない作品を許諾なしに投稿してはいけません！'''",
'copyrightwarning2' => "'''■投稿する前に以下を確認してください■'''
* あなたの文章が他人によって自由に編集、配布されることを望まない場合は、投稿を控えてください。
* あなたの投稿する文章はあなた自身によって書かれたものであるか、パブリック・ドメインかそれに類する自由なリソースからの複製であることを約束してください（詳細は$1を参照）。'''あなたが著作権を保持していない作品を許諾なしに投稿してはいけません！'''",
'longpagewarning' => "'''注意:''' このページのサイズは $1 キロバイトです。古いブラウザの一部に 32 キロバイト以上のページを編集すると問題が起きるものがあります。ページを節に分けることを検討してください。",
'readonlywarning' => '<strong>警告: データベースがメンテナンスのためにロックされたため、今は編集結果を正しく保存できません。文章をカットアンドペーストしてローカルファイルとして保存し、後ほど保存をやり直してください。</strong>',
'protectedpagewarning' => "'''警告:''' このページは保護されています。管理者しか編集できません。詳しくは[[Project:保護の方針|保護の方針]]を参照してください。",
'templatesused' => 'このページで使われているテンプレート:',

# History pages
#
'revhistory' => '改訂履歴',
'nohistory' => 'このページには変更履歴がありません。',
'revnotfound' => '要求された版が見つかりません。',
'revnotfoundtext' => '要求されたこのページの旧版は見つかりませんでした。このページにアクセスしたURLをもう一度確認してください。

The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.',
'loadhist' => '変更履歴の読み込み中',
'currentrev' => '最新版',
'revisionasof' => '$1の版',
'revisionasofwithlink' => '$1 の版; $2<br />$3 | $4',
'previousrevision' => '←前の版',
'nextrevision' => '次の版→',
'currentrevisionlink' => '最新版を表示',
'cur' => '最新版',
'next' => '次の版',
'last' => '前の版',
'orig' => '最古版',
'histlegend' => '凡例:（最新版）= 最新版との比較、（前の版）= 直前の版との比較、M = 細部の編集',
'history_copyright' => '-',
'deletedrev' => '[deleted]',
'histfirst' => '最古',
'histlast' => '最新',

# Diffs
#
'difference' => '（版間での差分）',
'loadingrev' => '差分をとるために古い版を読み込んでいます',
'lineno' => '$1 行',
'editcurrent' => 'このページの最新版を編集',
'selectnewerversionfordiff' => '比較する新しい版を選択',
'selectolderversionfordiff' => '比較する古い版を選択',
'compareselectedversions' => '選択した版同士を比較',

# Search results
#
'searchresults' => '検索結果',
'searchresulttext' => '{{SITENAME}}の検索に関する詳しい情報は、[[{{ns:Project}}:検索]]をご覧ください。',
'searchquery' => '問い合わせ: "$1"',
'badquery' => 'おかしな形式の検索問い合わせ',
'badquerytext' => '問い合わせを処理できませんでした。おそらく3文字未満の語を検索しようとしたためですが、まだ対応していません。例えば「魚 and and 大きさ」のように、表現を誤記しているのかもしれません。',
'matchtotals' => '"$1" を検索し、 $2 の項目名及び $3 ページの本文と一致しました。',
'nogomatch' => '[[$1|検索した名称のページ]]は存在しませんでした。全文検索を試みます。',
'titlematches' => '項目名と一致',
'notitlematches' => '項目名とは一致しませんでした',
'textmatches' => 'ページ内本文と一致',
'notextmatches' => 'ページ内本文とは一致しませんでした',
'prevn' => '前 $1',
'nextn' => '次 $1',
'viewprevnext' => '（$1）（$2）($3) を見る',
'showingresults' => '<b>$2</b> 件目から <b>$1</b> 件を表示しています。',
'showingresultsnum' => '<b>$2</b> 件目から <b>$3</b> 件を表示しています。',
'nonefound' => "'''※'''検索がうまくいかないのは、「ある」や「から」のような一般的な語で索引付けがされていないか、複数の検索語を指定している（全ての検索語を含むページだけが結果に示されます）などのためかもしれません。",
'powersearch' => '検索',
'powersearchtext' => '検索する名前空間 :<br />
$1<br />
$2リダイレクトを含める &nbsp; &nbsp; &nbsp; $3 $9',
'searchdisabled' => '<p>全文検索はサーバー負荷の都合から、一時的に使用停止しています。元に戻るまでGoogleでの全文検索を利用してください。検索結果は少し古い内容となります。</p>',

'googlesearch' => '<form method="get" action="http://www.google.com/search" id="googlesearch">
<table>
<tr><td>
<a href="http://www.google.com/"><img src="http://www.google.com/logos/Logo_40wht.gif" border="0" alt="Google" /></a>
</td><td>
<input type="hidden" name="domains" value="{{SERVER}}" />
<input type="hidden" name="ie" value="$2" />
<input type="hidden" name="oe" value="$2" />
<input type="text" name="q" size="31" maxlength="255" value="$1" />
<input type="submit" name="btnG" value="$3" /><br />
<small><input type="radio" name="sitesearch" value="" id="gWWW" style="vertical-align:middle"/><label for="gWWW">WWW</label>
<input type="radio" name="sitesearch" value="{{SERVER}}" checked="checked" id="gwiki" style="vertical-align:middle"/><label for="gwiki">{{SERVER}}/</label></small>
</td></tr>
</table>
</form>',

'blanknamespace' => '(標準)',

# Preferences page
#
'preferences' => 'オプション',
'prefsnologin' => 'ログインしていません',
'prefsnologintext' => 'ユーザーオプションを変更するためには、[[Special:Userlogin|ログイン]]する必要があります。',
'prefslogintext' => 'あなたは "$1" としてログインしています。あなたのユーザー番号は $2 です。

オプションの変更詳細については[[Project:オプションのヘルプ|オプションのヘルプ]]をご覧ください。',

'prefsreset' => 'ユーザー設定は初期化されました。',
'qbsettings' => 'クイックバー設定',
'changepassword' => 'パスワード変更',
'skin' => '外装',
'math' => '数式の表記方法',
'dateformat' => '日付の書式',
'math_failure' => '構文解析失敗',
'math_unknown_error' => '不明なエラー',
'math_unknown_function' => '不明な関数',
'math_lexing_error' => '字句解析エラー',
'math_syntax_error' => '構文エラー',
'math_image_error' => 'PNGへの変換に失敗しました。latex, dvips, gs, convertが正しくインストールされているか確認してください。',
'math_bad_tmpdir' => 'TeX一時ディレクトリを作成または書き込みできません',
'math_bad_output' => 'TeX出力用ディレクトリを作成または書き込みできません',
'math_notexvc' => 'texvcプログラムが見つかりません。math/READMEを読んで正しく設定してください。',
'prefs-personal' => '利用者の情報',
'prefs-rc' => '最近更新とスタブの表示',
'prefs-misc' => 'その他の設定',
'saveprefs' => '設定の保存',
'resetprefs' => '設定の初期化',
'oldpassword' => '古いパスワード',
'newpassword' => '新しいパスワード',
'retypenew' => '新しいパスワードを再入力してください',
'textboxsize' => '編集画面',
'rows' => '縦',
'columns' => '横',
'searchresultshead' => '検索結果の表示',
'resultsperpage' => '1ページあたりの表示件数',
'contextlines' => '1件あたりの行数',
'contextchars' => '1行あたりの文字数',
'stubthreshold' => 'スタブ表示にする閾値',
'recentchangescount' => '最近更新したページの表示件数',
'savedprefs' => 'ユーザー設定を保存しました',
'timezonelegend' => 'タイムゾーン',
'timezonetext' => 'UTCとあなたの地域の標準時間との差を入力してください（日本国内は9:00）。',
'localtime' => 'あなたの現在時刻',
'timezoneoffset' => '時差¹',
'servertime' => 'サーバの現在時刻',
'guesstimezone' => '自動設定',
'emailflag' => '他のユーザーからのメール送付を差し止める',
'defaultns' => '標準で検索する名前空間:',
'default' => 'デフォルト',
'files' => '画像等',

# User levels special page
#

# switching pan
# 'groups-lookup-group' => '',
# 'groups-group-edit' => '',
# 'editgroup' => '',
# 'addgroup' => '',

'userrights-lookup-user' => 'ユーザーの所属グループの管理',
'userrights-user-editname' => 'ユーザー名:',
'editusergroup' => '編集',

# group editing
# 'groups-editgroup' => '',
# 'groups-addgroup' => '',
# 'groups-editgroup-preamble' => '',
'groups-editgroup-name' => 'グループ名:',
# 'groups-editgroup-description' => '',
# 'savegroup' => '',
# 'groups-tableheader' => '',
# 'groups-existing' => '',
# 'groups-noname' => '',
# 'groups-already-exists' => '',
# 'addgrouplogentry' => '',
# 'changegrouplogentry' => '',
# 'renamegrouplogentry' => '',

# user groups editing
#
'userrights-editusergroup' => 'ユーザーの所属グループ',
'saveusergroups' => 'ユーザーの所属グループを保存',
'userrights-groupsmember' => '所属グループ:',
'userrights-groupsavailable' => '有効なグループ:',
'userrights-groupshelp' => 'このユーザーから削除したい、またはこのユーザーに追加したいグループを選択してください。選択されていないグループは変更されません。選択を解除するには [CTRL]+[左クリック] です。',
# 'userrights-logcomment' => '',

# Default group names and descriptions
#
# 'group-anon-name' => '',
# 'group-anon-desc' => '',
# 'group-loggedin-name' => '',
# 'group-loggedin-desc' => '',
# 'group-admin-name' => '',
# 'group-admin-desc' => '',
# 'group-bureaucrat-name' => '',
# 'group-bureaucrat-desc' => '',
# 'group-steward-name' => '',
# 'group-steward-desc' => '',


# Recent changes
#
'changes' => '更新',
'recentchanges' => '最近更新したページ',
'recentchanges-url' => 'Special:Recentchanges',
'recentchangestext' => '最近付け加えられた変更はこのページで確認できます。',
'rcloaderr' => '最近の更新情報をダウンロード中',
'rcnote' => '以下は最近 <strong>$2</strong> 日間に編集された <strong>$1</strong> ページです。（<strong>N</strong>=新規項目、<strong>M</strong>=細部の編集、日時はオプションで未設定ならUTC）',
'rcnotefrom' => '以下は <b>$2</b> までの更新です。（最大 <b>$1</b> 件）',
'rclistfrom' => '$1以後現在までの更新を表示',
'showhideminor' => '細部の編集を$1 | ボットによる編集を$2 | ログインユーザによる編集を$3 | パトロールされた編集を$4',
'rclinks' => '最近 $2 日間の $1 件分を表示する<br />$3',
'rchide' => 'in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.',
'rcliu' => '; $1 edits from logged in users',
'diff' => '差分',
'hist' => '履歴',
'hide' => '隠す',
'show' => '表示',
'tableform' => '表',
'listform' => '一覧',
'nchanges' => '$1件の変更',
'minoreditletter' => 'M',
'newpageletter' => 'N',
'sectionlink' => '→',
'number_of_watching_users_RCview' => '[$1]',
'number_of_watching_users_pageview' => '[$1人がウォッチしています]',

# Upload
#
'upload' => 'アップロード',
'uploadbtn' => 'アップロード',
'uploadlink' => '画像のアップロード',
'reupload' => '再アップロード',
'reuploaddesc' => 'アップロードのフォームへ戻る',
'uploadnologin' => 'ログインしていません',
'uploadnologintext' => 'ファイルをアップロードするには[[Special:Userlogin|ログイン]]する必要があります。',
'upload_directory_read_only' => 'アップロード先のディレクトリ ($1) にウェブサーバが書き込めません。',
'uploaderror' => 'アップロード エラー',
'uploadtext' => "ファイルを新しくアップロードする場合には、以下のフォームを利用してください。
* 過去にアップロードされた画像は[[Special:Imagelist|{{int:Imagelist}}]]で閲覧したり探したりできます。
* アップロードや削除は[[Special:Log|ログのページ]]に記録されます。
* 「{{int:Uploadbtn}}」ボタンを押すと、アップロードが完了します。
ページに画像を挿入するには '''<nowiki>[[{{ns:6}}:File.jpg]]</nowiki>''' や '''<nowiki>[[{{ns:6}}:File.png|thumb|代替テキスト]]</nowiki>''' といった書式を使います。画像ページではなくファイルに直接リンクするには '''<nowiki>[[{{ns:-2}}:File.ogg]]</nowiki>''' とします。",



'uploadlog' => 'アップロードログ',
'uploadlogpage' => 'アップロードログ',
'uploadlogpagetext' => '以下は最近のファイルのアップロードのログです。',
'filename' => 'ファイル名',
'filedesc' => 'ファイルの概要',
'filestatus' => '著作権情報',
'filesource' => 'ファイルの出典',
'copyrightpage' => '{{ns:Project}}:Copyrights',
'copyrightpagename' => '{{SITENAME}}の著作権',
'uploadedfiles' => 'アップロードされたファイル',
'ignorewarning' => '警告を無視し、保存してしまう',
'minlength' => 'ファイル名は3文字以上である必要があります。',
'illegalfilename' => 'ファイル名 "$1" にページ・タイトルとして使えない文字が含まれています。ファイル名を変更してからもう一度アップロードしてください。',
'badfilename' => 'ファイル名は "$1" へ変更されました。',
'badfiletype' => '".$1" は推奨されているファイルフォーマットではありません。',
'largefile' => 'ファイルサイズは $1 バイト以下に抑えることが推奨されています。このファイルは $2 バイトです。',
'emptyfile' => 'あなたがアップロードしようとしているファイルは内容が空であるか、もしくはファイル名の指定が間違っています。もう一度、ファイル名が正しいか、あるいはアップロードしようとしたファイルであるかどうかを確認してください。',
'fileexists' => 'この名前のファイルは既に存在しています。$1と置き換えるかどうかお確かめください。',
'successfulupload' => 'アップロード成功',
'fileuploaded' => 'ファイル "$1" は無事にアップロードされました。

画像詳細ページ $2 に行き、ファイルについての情報―出典、製作者や時期、その他知っている情報を書き込んでください。

この画像をページに貼り付ける際にはページ内に <tt><nowiki>[[</nowiki>{{ns:Image}}:$1|thumb|画像の説明]]</tt> を挿入してください。',
'uploadwarning' => 'アップロード 警告',
'savefile' => 'ファイルを保存',
'uploadedimage' => '"$1" をアップロードしました。',
'uploaddisabled' => '申し訳ありませんが、アップロードは現在使用できません。',
'uploadscripted' => 'このファイルはウェブブラウザが誤って解釈してしまうおそれのあるHTMLまたはスクリプトコードを含んでいます。',
'uploadcorrupt' => '指定したファイルは壊れているか拡張子が正しくありません。ファイルを確認の上再度アップロードをしてください。',
'uploadvirus' => 'このファイルにはウイルスが含まれています!! &nbsp;詳細: $1',
'sourcefilename' => 'ファイル名',
'destfilename' => '掲載するファイル名',

# Image list
#
'imagelist' => '画像リスト',
'imagelisttext' => '$1 枚の画像を $2 に表示しています',
'getimagelist' => '画像リストを取得',
'ilsubmit' => '検索',
'showlast' => '$2に $1 枚の画像を表示',
'byname' => '名前順',
'bydate' => '日付順',
'bysize' => 'サイズ順',
'imgdelete' => '削除',
'imgdesc' => '詳細',
'imglegend' => '凡例: （詳細）= 画像の詳細を表示/編集',
'imghistory' => '画像の履歴',
'revertimg' => '差戻',
'deleteimg' => '削除',
'deleteimgcompletely' => '全版削除',
'imghistlegend' => '凡例:（最新）= 最新版の画像、（削除）= この版の画像を削除、（差戻）= この版の画像に差し戻す<br />
<b>アップロードされた画像を見るには日付をクリックします。</b>',
'imagelinks' => 'リンク',
'linkstoimage' => 'この画像にリンクしているページの一覧:',
'nolinkstoimage' => 'この画像にリンクしているページはありません。',
'sharedupload' => 'このファイルは共有されており、他のプロジェクトで使用されている可能性があります。',
'shareduploadwiki' => '詳しい情報は$1を参照してください。',
'shareddescriptionfollows' => '-',
'noimage' => 'このファイル名の画像はありません。$1。',
'uploadnewversion' => '[$1 このファイルの新しいバージョンをアップロードする]',

# Statistics
#
'statistics' => 'アクセス統計',
'sitestats' => 'サイト全体の統計',
'userstats' => 'ユーザー登録統計',
'sitestatstext' => "データベース内には'''$1'''ページのデータがあります。この数字には「ノートページ」や「{{SITENAME}}関連のページ」、「書きかけのページ」、「リダイレクト」など、記事とはみなせないページが含まれています。これらを除いた、記事とみなされるページ数は約'''$2'''ページになります。

ページの総閲覧回数は'''$3'''回です。また、'''$4'''回の編集が行われました。平均すると、1ページあたり'''$5'''回の編集が行われ、1編集あたり'''$6'''回閲覧されています。",

'userstatstext' => "登録済みの利用者は'''$1'''人で、内'''$2'''人 ('''$4%''') が管理者権限を持っています。($3を参照)",

# Maintenance Page
#
'maintenance' => 'メンテナンスページ',
'maintnancepagetext' => 'このページには毎日のメンテナンスに便利なツールがあります。いくつかの機能はデータベースに負荷を与えるので、修正の度に再読み込みをしないでください。',
'maintenancebacklink' => 'メンテナンスページに戻る',
'disambiguations' => '曖昧さ回避ページ',
'disambiguationspage' => 'Template:Disambig',
'disambiguationstext' => '以下のページは<b>曖昧さ回避ページ</b>へリンクしています。これらのページはより適した主題のページへリンクされるべきです。<br />
$1 にリンクしているページは曖昧さ回避ページと見なされます。',
'doubleredirects' => '二重リダイレクト',
'doubleredirectstext' => '以下はリダイレクトにリンクしているリダイレクトの一覧です。最も左のリダイレクトは二番目のリダイレクトが指している、恐らく「真に」リダイレクトしたいページを指すよう、変更されるべきです。',
'brokenredirects' => '迷子のリダイレクト',
'brokenredirectstext' => '以下は存在しないページにリンクしているリダイレクトです。',
'selflinks' => '自己リンクのあるページ',
'selflinkstext' => '以下のページは本来あるべきでない、自分自身へのリンクを含んでいます。',
'mispeelings' => 'スペルミスのあるページ',
'mispeelingstext' => 'The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).',
'mispeelingspage' => 'List of common misspellings',
'missinglanguagelinks' => '言語間リンクのないページ',
'missinglanguagelinksbutton' => '次の言語への言語間リンクのないページを検索',
'missinglanguagelinkstext' => 'これらのページは $1 版へリンクしていません。リダイレクトとサブページは表示されません。',


# Miscellaneous special pages
#
'orphans' => '孤立しているページ',
'geo' => 'GEO coordinates',
'validate' => 'ページの評価',
'lonelypages' => '孤立しているページ',
'uncategorizedpages' => 'カテゴリ未導入のページ',
'uncategorizedcategories' => '上位カテゴリのないカテゴリ',
'unusedcategories' => '未使用のカテゴリ',
'unusedimages' => '使われていない画像',
'popularpages' => '人気のページ',
'nviews' => '$1 回表示',
'wantedpages' => '投稿が望まれているページ',
'mostlinked' => '最もリンクされているページ',
'nlinks' => '$1 個のリンク',
'allpages' => '全ページ',
'randompage' => 'おまかせ表示',
'randompage-url' => 'Special:Randompage',
'shortpages' => '短いページ',
'longpages' => '長いページ',
'deadendpages' => '有効なページへのリンクがないページ',
'listusers' => '登録ユーザーの一覧',
'specialpages' => '特別ページ',
'spheading' => '特別ページ',
'restrictedpheading' => '制限された特別ページ',
'protectpage' => 'ページの保護',
'recentchangeslinked' => 'リンク先の更新',
'rclsub' => '（"$1" からリンクされているページ）',
'debug' => 'デバッグ',
'newpages' => '新しいページ',
'ancientpages' => '更新されていないページ',
'intl' => '言語間リンク',
'move' => '移動',
'movethispage' => 'このページを移動',
'unusedimagestext' => '<p>他のウェブサイトがURLを直接用いて画像にリンクしている場合もあります。以下の画像一覧には、そのような形で利用されている画像が含まれている可能性があります。</p>',
'unusedcategoriestext' => '以下のカテゴリページはどの項目・カテゴリからも使われていません。',

'booksources' => '文献資料',
'categoriespagetext' => '{{SITENAME}}には以下のカテゴリが存在します。',
'data' => 'Data',
'userrights' => 'ユーザー権限の管理',
# 'groups' => '',

'booksourcetext' => '以下のリストは、新本、古本などを販売している外部サイトへのリンクです。あなたがお探しの本について、更に詳しい情報が提供されている場合もあります。',
'isbn' => 'ISBN',
# 'rfcurl' => '',
# 'pubmedurl' => '',
'alphaindexline' => '$1―$2',
'version' => 'バージョン情報',
'log' => 'ログ',
'alllogstext' => 'アップロード、削除、保護、投稿ブロック、権限変更のログがまとめて表示されています。ログの種類、実行した利用者、影響を受けたページ（利用者）による絞り込みができます。',

# Special:Allpages
'nextpage' => '次のページ（$1）',
'allpagesfrom' => '表示開始ページ:',
'allarticles' => '全ページ',
'allnonarticles' => '項目以外の全ページ',
'allinnamespace' => '全ページ ($1 名前空間)',
'allnotinnamespace' => '全ページ ($1 名前空間を除く)',
'allpagesprev' => '前へ',
'allpagesnext' => '次へ',
'allpagessubmit' => '表示',

# E this user
#
'mailnologin' => '送信先のアドレスがありません。',
'mailnologintext' => '他のユーザーに宛てにメールを送信するためには、[[Special:Userlogin|ログイン]]し、あなたのメールアドレスを[[Special:Preference|ユーザーオプション]]に設定する必要があります。',
'emailuser' => 'このユーザーにメールを送信',
'emailpage' => 'メール送信ページ',
'emailpagetext' => 'もしメールを送る先のユーザーが、有効なメールアドレスを
ユーザーオプションに登録してあれば、下のフォームを通じてメールを送ることができます。
あなたが登録したご自分のメールアドレスはFrom:の欄に自動的に組み込まれ、受け取った相手が
返事を出せるようになっています。',
'usermailererror' => 'メール送信時に以下のエラーが発生しました:',
'defemailsubject' => '{{SITENAME}} (ja) e-mail',
'noemailtitle' => '送り先のメールアドレスがありません。',
'noemailtext' => 'このユーザーは有効なメールアドレスを登録していないか、メールを受け取りたくないというオプションを選択しています。',
'emailfrom' => 'あなたのアドレス',
'emailto' => 'あて先',
'emailsubject' => '題名',
'emailmessage' => '本文',
'emailsend' => 'メール送信',
'emailsent' => 'メールを送りました',
'emailsenttext' => 'メールは無事送信されました。',

# Watchlist
#
'watchlist' => 'ウォッチリスト',
'watchlistsub' => '（ユーザー名 "$1"）',
'nowatchlist' => 'あなたのウォッチリストは空です。',
'watchnologin' => 'ログインしていません',
'watchnologintext' => 'ウォッチリストを変更するためには、[[Special:Userlogin|ログイン]]している必要があります。',
'addedwatch' => 'ウォッチリストに追加しました',
'addedwatchtext' => "ページ \"$1\" をあなたの[[Special:Watchlist|ウォッチリスト]]に追加しました。

このページと、付属のノートのページに変更があった際にはそれをウォッチリストで知ることができます。また、[[Special:Recentchanges|最近更新したページ]]ではウォッチリストに含まれているページは'''ボールド体'''で表示され、見つけやすくなります。

ウォッチリストから特定のページを削除したい場合には、サイドバーかタブにある \"ウォッチリストから削除\" のリンクをクリックしてください。",

'removedwatch' => 'ウォッチリストから削除しました',
'removedwatchtext' => 'ページ "$1" をウォッチリストから削除しました。',
'watch' => 'ウォッチリストに追加',
'watchthispage' => 'ウォッチリストに追加',
'unwatch' => 'ウォッチリストから削除',
'unwatchthispage' => 'ウォッチリストから削除',
'notanarticle' => 'これは記事ではありません。',
'watchnochange' => 'その期間内にウォッチリストにあるページはどれも編集されていません。',
'watchdetails' => '* ウォッチリストに入っているページ数（ノート除く）: $1
* [[Special:Watchlist/edit|ウォッチリストの一覧・編集]]',
'wlheader-enotif' => '* メール通知が有効になっています',
'wlheader-showupdated' => "* あなたが最後に訪問したあとに変更されたページは'''ボールド体'''で表示されます",
'watchmethod-recent' => 'ウォッチリストの中から最近編集されたものを抽出',
'watchmethod-list' => '最近編集された中からウォッチしているページを抽出',
'removechecked' => 'チェックした項目をウォッチリストから削除',
'watchlistcontains' => 'あなたのウォッチリストには $1 ページ登録されています。',
'watcheditlist' => 'ウォッチリストに登録しているページを文字コード順に表示しています。

チェックボックスにチェックし、「ウォッチリストから削除」のボタンをクリックするとウォッチリストから削除されます。

※ウォッチリストからページを削除すると、付随するノートページも削除されます。',
'removingchecked' => '要求された項目をウォッチリストから削除しています:',
'couldntremove' => '"$1" をウォッチリストから削除できません。',
'iteminvalidname' => '"$1" をウォッチリストから削除できません。ページ名が不正です。',
'wlnote' => '以下は最近 <strong>$2</strong> 時間に編集された <strong>$1</strong> ページです。',
'wlshowlast' => '最近の [$1時間] [$2日間] [$3] のものを表示する',
'wlsaved' => '現在、バックアップされたウォッチリストのみの表示となっています。',
'wlhideshowown' => '自分の編集を$1',
'wlshow' => '表示',
'wlhide' => '隠す',

'enotif_mailer' => '{{SITENAME}} Notification Mailer',
'enotif_reset' => 'すべてのページを訪問済みにする',
'enotif_newpagetext' => '(新規ページ)',
'changed' => '変更',
'created' => '作成',
'enotif_subject' => '{{SITENAME}} のページ "$PAGETITLE" が $PAGEEDITOR によって$CHANGEDORCREATEDされました',
'enotif_lastvisited' => '
あなたが最後に閲覧してからの差分を見るには以下のURLにアクセスしてください:
$1',
'enotif_body' => 'Dear $WATCHINGUSERNAME,

{{SITENAME}}のページ $PAGETITLE が $PAGEEDITDATE に
$PAGEEDITOR によって$CHANGEDORCREATEDされました。
現在の版を見るには次のURLにアクセスしてください:
$PAGETITLE_URL
$NEWPAGE

編集内容の要約: $PAGESUMMARY ($PAGEMINOREDIT)

投稿者:
メール: $PAGEEDITOR_EMAIL
ウィキ: $PAGEEDITOR_WIKI

あなたがこのページを訪れない限り、これ以上の通知は送信されません。
ウォッチリストからすべての通知フラグをリセットすることもできます。

                         {{SITENAME}} 通知システム

--
ウォッチリストの設定を変更する:
{{SERVER}}{{localurl:Special:Watchlist/edit}}

助けが必要ですか:
{{SERVER}}{{localurl:Help:Contents}}',









# Delete/protect/revert
#
'deletepage' => 'ページ削除',
'confirm' => '確認',
'excontent' => "内容: '$1'",
'excontentauthor' => "内容: '$1' (投稿者 $2 のみ)",
'exbeforeblank' => "白紙化前の内容: '$1'",
'exblank' => '白紙ページ',
'confirmdelete' => '削除の確認',
'deletesub' => '（"$1" を削除）',
'historywarning' => '警告: 削除しようとしているページには履歴があります:',
'confirmdeletetext' => '指定されたページまたは画像は、その更新履歴と共にデータベースから永久に削除されようとしています。あなたが削除を望んでおり、それがもたらす帰結を理解しており、かつあなたのしようとしていることが[[Project:Policy|基本方針]]に即したものであることを確認してください。',
'actioncomplete' => '完了しました',
'deletedtext' => '"$1" は削除されました。最近の削除に関しては $2 を参照してください。',
'deletedarticle' => '"$1" を削除しました。',
'dellogpage' => '削除記録',
'dellogpagetext' => '以下は最近の削除と復帰の記録です。',
'deletionlog' => '削除記録',
'reverted' => '以前のバージョンへの差し戻し (Reverted to earlier revision)',
'deletecomment' => '削除の理由',
'imagereverted' => '以前のバージョンへの差し戻しに成功しました。',
'rollback' => 'Roll back edits',
'rollback_short' => 'Rollback',
'rollbacklink' => 'rollback',
'rollbackfailed' => 'Rollback に失敗しました。',
'cantrollback' => '投稿者がただ一人であるため、編集を差し戻せません。',
'alreadyrolled' => 'ページ [[$1]] の [[User:$2|$2]] ([[User talk:$2|会話]] | [[Special:Contributions/$2|履歴]]) による編集の rollback に失敗しました。誰か他のユーザーが編集を行ったか rollback されたのかもしれません。

このページの最後の編集は [[User:$3|$3]] ([[User talk:$3|会話]] | [[Special:Contributions/$3|履歴]]) によるものです。',

#   only shown if there is an edit comment
'editcomment' => '編集内容の要約: <i>$1</i>',
'revertpage' => 'Reverted edit of $2, changed back to last version by $1',
'sessionfailure' => 'あなたのログイン・セッションに問題が発生しました。この動作はセッションハイジャックを防ぐために取り消されました。ブラウザの「戻る」を押してからページを再読込し、もう一度送信してください。',
'protectlogpage' => '保護記録',
'protectlogtext' => '以下はページの保護・保護解除の一覧です。詳細は[[{{ns:Project}}:保護されたページ]]を参照してください。',
'protectedarticle' => '"$1" を保護しました。',
'unprotectedarticle' => '"$1" の保護を解除しました。',
'protectsub' => '（"$1" の保護）',
'confirmprotecttext' => '本当にこのページを保護しますか?',
'confirmprotect' => '保護の確認',
'protectmoveonly' => '移動のみ差し止める',
'protectcomment' => '保護の理由',
'unprotectsub' => '（"$1" の保護解除）',
'confirmunprotecttext' => '本当にこのページの保護を解除しますか?',
'confirmunprotect' => '保護解除の確認',
'unprotectcomment' => '保護解除の理由',

# Undelete
'undelete' => '削除されたページを参照する',
'undeletepage' => '削除された編集の参照と復帰',
'undeletepagetext' => '以下のページは削除されていますが、アーカイブに残っているため、復帰できます。アーカイブは定期的に消去されます。',
'undeletearticle' => '削除済みページの復帰',
'undeleterevisions' => '($1 版が保存されています)',
'undeletehistory' => 'ページの復帰を行うと、履歴にある全ての編集が復帰します。削除後に同じ名前のページが作成されている場合、削除以前の編集は新しい履歴の前に表示され、最新版は置き換わりません。',
'undeleterevision' => '削除された $1 の版',
'undeletebtn' => '復帰!',
'undeletedarticle' => '"$1" を復帰しました。',
'undeletedrevisions' => '$1 版を復帰',
'undeletedtext' => '[[:$1|$1]]を無事復帰しました。最近の削除と復帰の記録は[[Special:Log/delete]]を参照してください。',

# Namespace form on various pages
'namespace' => '名前空間:',
'invert' => '選択した名前空間を隠す',

# Contributions
#
'contributions' => 'ユーザーの投稿記録',
'mycontris' => '自分の投稿記録',
'contribsub' => 'ユーザー名：$1',
'nocontribs' => 'ユーザーの投稿記録は見つかりませんでした。',
# 'ucnote' => '',
# 'uclinks' => '',
'uctop' => ' (top)',
'newbies' => '新規ユーザー',
'contribs-showhideminor' => '$1 minor edits',

# What links here
#
'whatlinkshere' => 'リンク元',
'notargettitle' => '対象となるページが存在しません',
'notargettext' => '対象となるページ又はユーザーが指定されていません',
'linklistsub' => 'リンクのリスト',
'linkshere' => '指定したページは以下のページからリンクされています',
'nolinkshere' => '指定されたページにリンクしているページはありません。',
'isredirect' => 'リダイレクトページ',

# Block/unblock IP
#
'blockip' => 'ユーザーのブロック',
'blockiptext' => '指定されたIPアドレスからの投稿をブロックすることができます。投稿ブロックは荒らしを防ぐためであり、[[Project:Policy|{{SITENAME}}の基本方針]]に従っているべきです。明確な理由を以下に記入してください（例えば、荒らされたページを引用する）。',
'ipaddress' => 'IPアドレス',
'ipadressorusername' => 'IPアドレス / ユーザー名',
'ipbexpiry' => '期間',
'ipbreason' => '理由',
'ipbsubmit' => 'このアドレスまたはユーザーを投稿ブロックする',
'ipbother' => '期間 (その他のとき)',
'ipboptions' => '2 hours:2 hours,1 day:1 day,3 days:3 days,1 week:1 week,2 weeks:2 weeks,1 month:1 month,3 months:3 months,6 months:6 months,1 year:1 year,無期限:infinite',
'ipbotheroption' => 'その他',
'badipaddress' => 'IPアドレスが異常です。',
'blockipsuccesssub' => 'ブロックに成功しました。',
'blockipsuccesstext' => 'IPアドレスまたはユーザー "$1" は投稿をブロックされました。<br/>
[[Special:Ipblocklist|投稿ブロック中のユーザーやIPアドレス]]で確認できます。',
'unblockip' => 'IPアドレスの投稿ブロックを解除する',
'unblockiptext' => '以下のフォームでIPアドレスまたはユーザーの投稿ブロックを解除できます。',
'ipusubmit' => 'このアドレスまたはユーザーの投稿ブロックを解除する',
'ipusuccess' => 'IPアドレス "$1" の投稿ブロックが解除されました。',
'ipblocklist' => '投稿ブロック中のユーザーやIPアドレス',
'blocklistline' => '$1, $2 は $3 をブロック （$4）',
'infiniteblock' => '無期限',
'expiringblock' => '$1 に解除',
'ipblocklistempty' => '投稿ブロック中のユーザーやIPアドレスはありません。',
'blocklink' => 'block',
'unblocklink' => 'ブロック解除',
'contribslink' => '投稿記録',
'autoblocker' => '投稿ブロックされているユーザー "$1" と同じIPアドレスのため、自動的にブロックされています。ブロックの理由は "$2" です。',
'blocklogpage' => '投稿ブロック記録',
'blocklogentry' => '"$1" を $2 ブロックしました',
'blocklogtext' => 'このページは投稿ブロックと解除の記録です。自動的に投稿ブロックされたIPアドレスは一覧されていません。現時点で有効な投稿ブロックは[[Special:Ipblocklist|投稿ブロック中のユーザーやIPアドレス]]をご覧ください。',
'unblocklogentry' => '"$1" をブロック解除しました',
'range_block_disabled' => '広域ブロックは無効に設定されています。',
'ipb_expiry_invalid' => '不正な期間です。',
'ip_range_invalid' => '不正なIPアドレス範囲です。',
'proxyblocker' => 'Proxy blocker',
'proxyblockreason' => 'Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or tech support and inform them of this serious security problem.

:あなたの使用しているIPアドレスはオープン・プロクシであるため投稿ブロックされています。あなたのインターネット・サービス・プロバイダ、もしくは技術担当者に連絡を取り、これが深刻なセキュリティ問題であることを伝えてください。',
'proxyblocksuccess' => 'Done.
',
'sorbs' => 'SORBS DNSBL',
'sorbsreason' => 'あなたのIPアドレスはオープンプロクシであると、[http://www.sorbs.net/ SORBS] DNSBLに掲載されています。',
'sorbs_create_account_reason' => 'あなたのIPアドレスがオープンプロクシであると、[http://www.sorbs.net/ SORBS] DNSBLに掲載されているため、アカウントを作成できません。',


# Developer tools
#
'lockdb' => 'データベースのロック',
'unlockdb' => 'データベースのロック解除',
'lockdbtext' => 'データベースをロックすると全てのユーザーはページを編集できなくなり、オプションを変更できなくなり、ウォッチリストを編集できなくなるなど、データベースに書き込む全ての作業ができなくなります。本当にデータベースをロックして良いかどうか確認してください。メンテナンスが終了したらロックを解除してください。',
'unlockdbtext' => 'データベースのロックを解除することでユーザーはページを編集できるようになり、オプションを変更できるようになり、ウォッチリストを編集できるようになりなど、データベースに書き込む全ての作業ができるようになります。本当にデータベースのロックを解除していいかどうか確認してください。',
'lockconfirm' => '本当にデータベースをロックする',
'unlockconfirm' => 'ロックを解除する',
'lockbtn' => 'ロック',
'unlockbtn' => 'ロック解除',
'locknoconfirm' => 'チェックボックスにチェックされていません。',
'lockdbsuccesssub' => 'データベースはロックされました。',
'unlockdbsuccesssub' => 'データベースのロックは解除されました',
'lockdbsuccesstext' => 'データベースはロックされました。メンテナンスが終了したら忘れずにロックを解除しましょう。',
'unlockdbsuccesstext' => 'データベースのロックは解除されました。',

# Make sysop
'makesysoptitle' => 'ユーザーを管理者にする',
'makesysoptext' => 'このフォームは通常のユーザーを管理者にするために使用します。管理者にするユーザー名を入力し、このユーザーを管理者にするボタンを押してください。',
'makesysopname' => 'ユーザー名:',
'makesysopsubmit' => 'このユーザーを管理者にする',
'makesysopok' => '<b>ユーザー "$1" を管理者にしました。</b>',
'makesysopfail' => '<b>ユーザー "$1" を管理者にできませんでした。ユーザー名を正しく入力していたかどうか確認してください。</b>',
'setbureaucratflag' => '"bureaucrat" フラグをセット',
'setstewardflag' => 'Stewardフラグをセット',
'bureaucratlog' => '権限変更記録',
'rightslogtext' => '以下はユーザーの権限変更の一覧です。',
'bureaucratlogentry' => '$1 の権限を "$2" から "$3" に変更しました。',
'rights' => '権限:',
'set_user_rights' => 'ユーザー権限の設定',
'user_rights_set' => '<b>ユーザー "$1" の権限が更新されました</b>',
'set_rights_fail' => '<b>ユーザー "$1" の権限を設定できませんでした。ユーザー名を正しく入力していたかどうか確認してください。</b>',
'makesysop' => 'ユーザーを管理者にする',
'already_sysop' => 'ユーザーは既に管理者です。',
'already_bureaucrat' => 'ユーザーは既にビューロクラットです。',
'already_steward' => 'ユーザーは既にStewardです。',

# Validation
'val_yes' => 'はい',
'val_no' => 'いいえ',
'val_of' => '$1点 ($2点中)',
'val_revision' => '版',
'val_time' => '時刻',
'val_user_stats_title' => '$1による評価',
'val_my_stats_title' => 'あなたの評価記録',
'val_list_header' => '<th>#</th><th>評価基準</th><th>範囲</th><th>操作</th>',
'val_add' => '追加',
'val_del' => '削除',
'val_show_my_ratings' => '自分の評価を表示',
'val_revision_number' => '版 #$1',
'val_warning' => "'''絶対に'''コミュニティの'''完全な合意無しに'''ここの記述を'''変更しないでください'''。",
'val_rev_for' => '$1 を評価中',
'val_details_th_user' => 'ユーザー $1',
'val_validation_of' => '"$1" に対する評価',
'val_revision_of' => '$1 の版',
'val_revision_changes_ok' => 'あなたの評価が記録されました。',
'val_rev_stats' => '"$1" に対する評価の一覧</a> <a href="$2">(この版)',
'val_revision_stats_link' => '詳細',
'val_iamsure' => 'これで採点する。',
'val_details_th' => '<sub>ユーザー</sub>＼<sup>評価基準</sup>',
'val_clear_old' => '古い版に対する自分の評価を取り消す',
'val_merge_old' => '"意見なし" とした箇所について以前の評価を引き継ぐ',
'val_form_note' => "----
'''ヒント:''' 「{{int:Val_merge_old}}」にチェックを入れると、このフォームで「意見なし」と選択した評価項目について、以前の版であなたが行った評価とコメントを引き継ぎます。つまり、あなたがこのページに対して、ある評価項目についてのみ評価を変更したい場合は、その評価項目でのみ、新しい評価値を選び、コメントを入力してください。そしてその他の評価項目を「意見なし」のままにしておけば、以前の評価がそのまま引き継がれます。",
'val_noop' => '意見なし',
'val_topic_desc_page' => 'Project:評価基準',
'val_votepage_intro' => 'このテキストを[{{SERVER}}{{localurl:MediaWiki:Val_votepage_intro}} 編集してください]。',
'val_percent' => '<b>$1%</b><br />($4名による<br />$3の評価のうち$2)',
'val_percent_single' => '<b>$1%</b><br />(1名による<br />$3の評価のうち$2)',
'val_total' => '合計',
'val_version' => '版',
'val_tab' => '評価',
'val_this_is_current_version' => 'これは最新版です',
'val_version_of' => '$1 の版',
'val_table_header' => '<tr><th>クラス</th>$1<th colspan=4>提案</th>$1<th>コメント</th></tr>',
'val_stat_link_text' => 'この項目に対する評価の一覧',
'val_view_version' => 'この版を表示',
'val_validate_version' => 'この版を評価する',
'val_user_validations' => 'このユーザーは$1ページで評価をしています。',
'val_no_anon_validation' => '評価をするには[[Special:Userlogin|ログイン]]する必要があります。',
'val_validate_article_namespace_only' => "評価は標準名前空間に対してのみ可能です。このページは'''標準名前空間のページではありません'''。",
'val_validated' => '評価を保存しました。',
'val_article_lists' => '評価された項目',
'val_page_validation_statistics' => '$1 に対する評価の一覧',

# Move page
#
'movepage' => 'ページの移動',
'movepagetext' => '下のフォームを利用すると、ページ名を変更し、その履歴も変更先へ移動することができます。古いページは変更先へのリダイレクトページとなります。ページの中身と変更前のページに張られたリンクは変わりません。ですから、二重になったり壊れてしまったリダイレクトをチェックする必要があります。

変更先がすでに存在する場合には、履歴が移動元ページへのリダイレクトただ一つである場合を除いて、移動できません。つまり、間違えてページ名を変更した場合には元に戻せます。

<strong>注意！</strong> よく閲覧されるページや、他の多くのページからリンクされているページを移動すると予期せぬ結果が起こるかもしれません。ページの移動に伴う影響をよく考えてから踏み切るようにしてください。',


'movepagetalktext' => '付随するノートのページがある場合には、基本的には、一緒に移動されることになります。

但し、以下の場合については別です。
*名前空間をまたがる移動の場合
*移動先に既に履歴のあるノートページが存在する場合
*下のチェックボックスのチェックマークを消した場合

これらの場合、ノートページを移動する場合には、別に作業する必要があります。',

'movearticle' => '移動するページ',
'movenologin' => 'ログインしていません',
'movenologintext' => 'ページを移動するためには、ユーザー登録の上、[[Special:Userlogin|ログイン]]している必要があります。',
'newtitle' => '新しいページ名',
'movepagebtn' => 'ページを移動',
'pagemovedsub' => '無事移動しました。',
'pagemovedtext' => 'ページ "[[$1]]" は "[[$2]]" に移動しました。',
'articleexists' => '指定された移動先には既にページが存在するか、名前が不適切です。',
'talkexists' => 'ページ自身は移動されましたが、付随のノートページは移動先のページが存在したため移動できませんでした。手動で内容を統合してください。',
'movedto' => '移動先:',
'movetalk' => 'ノートページが付随する場合には、それも一緒に移動する',
'talkpagemoved' => '付随のノートのページも移動しました。',
'talkpagenotmoved' => '付随のノートのページは<strong>移動されませんでした。</strong>',
'1movedto2' => '[[$1]] を [[$2]] へ移動',
'1movedto2_redir' => '[[$1]] をこのページあてのリダイレクト [[$2]] へ移動',
'movelogpage' => '移動記録',
'movelogpagetext' => '以下は最近のページ移動の記録です。',
'movereason' => '理由',
'revertmove' => '差し戻し',
'delete_and_move' => '削除して移動する',
'delete_and_move_text' => '== 削除が必要です ==
移動先 "[[$1]]" は既に存在しています。このページを移動のために削除しますか？',

'delete_and_move_reason' => '移動のための削除',
'selfmove' => '移動元と移動先のページ名が同じです。自分自身へは移動できません。',
'immobile_namespace' => '移動先のページ名は特別なページです。その名前空間にページを移動することはできません。',

# Export

'export' => 'ページデータの書き出し',
'exporttext' => 'ここでは単独のまたは複数のページのテキストと編集履歴をXMLの形で書き出すことができます。書き出されたXML文書はは他のMediaWikiで動いているウィキに取り込んだり、変換したり、個人的な楽しみに使ったりできます。

ページデータを書き出すには下のテキストボックスに書き出したいページのタイトルを一行に一ページずつ記入してください。また編集履歴とともに全ての古い版を含んで書き出すのか、最新版のみを書き出すのか選択してください。

後者のケースではリンクの形で使うこともできます。例: [[メインページ]]の最新版を取得するには[[{{ns:Special}}:Export/メインページ]]を使用します。',


'exportcuronly' => 'すべての履歴を含ませずに、最新版のみを書き出す',

# Namespace 8 related

'allmessages' => '表示メッセージ一覧',
'allmessagesname' => 'メッセージ名',
'allmessagesdefault' => '既定の文章',
'allmessagescurrent' => '現在の文章',
'allmessagestext' => 'これはMediaWiki名前空間にある全てのシステムメッセージの一覧です。',
'allmessagesnotsupportedUI' => 'このサイトでは、あなたの現在のインターフェイス言語 <b>$1</b> における {{ns:Special}}:Allmessages はサポートされていません。',
'allmessagesnotsupportedDB' => 'wgUseDatabaseMessages が無効のため、{{ns:Special}}:Allmessages はサポートされません。',

# Thumbnails

'thumbnail-more' => '拡大',
'missingimage' => '<b>Missing image</b><br /><i>$1</i>
',
'filemissing' => '<i>ファイルがありません</i>',

# Special:Import
'import' => 'ページデータの取り込み',
'importinterwiki' => 'Transwikiインポート',
'importtext' => '元となるウィキから {{ns:Special}}:Export を使ってXMLファイルを書き出し、それをここにアップロードしてください。',
'importfailed' => '取り込みに失敗しました: $1',
'importnotext' => '内容が空か、テキストがありません。',
'importsuccess' => '取り込みに成功しました。',
'importhistoryconflict' => '取り込み時にいくつかの版が競合しました（以前に同じページを取り込んでいませんか）。',
'importnosources' => 'Transwikiの読み込み元が定義されていないため、履歴の直接アップロードは無効になっています。',
'importnofile' => 'ファイルがアップロードされませんでした',
'importuploaderror' => 'ファイルの取り込みに失敗しました。恐らく、許可されている最大ファイルサイズより大きなファイルをアップロードしようとしています。',

# Keyboard access keys for power users
'accesskey-search' => 'f',
'accesskey-minoredit' => 'i',
'accesskey-save' => 's',
'accesskey-preview' => 'p',
'accesskey-diff' => 'd',
'accesskey-compareselectedversions' => 'v',

# tooltip help for some actions, most are in Monobook.js
'tooltip-search' => 'ウィキ内を検索 [alt-f]',
'tooltip-minoredit' => 'この編集を細部の変更とマーク [alt-i]',
'tooltip-save' => '編集を保存します。 [alt-s]',
'tooltip-preview' => '編集結果を確認します。保存前に是非使用してください。 [alt-p]',
'tooltip-diff' => 'あなたが編集した版の変更点を表示します。[alt-d]',
'tooltip-compareselectedversions' => '選択された二つの版の差分を表示します。 [alt-v]',
'tooltip-watch' => 'このページをウォッチリストへ追加します。 [alt-w]',

# stylesheets
'Monobook.css' => '/* edit this file to customize the monobook skin for the entire site */
#bodyContent { font-size:118% }',
#'Monobook.js' => '/* edit this file to change js things in the monobook skin */',

# Metadata
'nodublincore' => 'Dublin Core RDF metadata disabled for this server.',
'nocreativecommons' => 'Creative Commons RDF metadata disabled for this server.',
'notacceptable' => 'ウィキサーバーはあなたの使用しているクライアントが読める形式で情報を提供できません。',

# Attribution

'anonymous' => '{{SITENAME}}の匿名ユーザー',
'siteuser' => '{{SITENAME}}のユーザー$1',
'lastmodifiedby' => '最終更新は $2 による $1 の編集です。',
'and' => 'および',
'othercontribs' => '$1の版に基づきます。',
'others' => 'その他のユーザー',
'siteusers' => '{{SITENAME}}のユーザー$1',
'creditspage' => 'ページ・クレジット',
'nocredits' => 'このページには有効なクレジット情報がありません。',

# Spam protection

'spamprotectiontitle' => 'スパム防御フィルター',
'spamprotectiontext' => 'あなたが保存しようとしたページはスパム・フィルターによって保存をブロックされました。これは主に外部サイトへのリンクが原因です。',
'spamprotectionmatch' => '以下はスパム・フィルターによって検出されたテキストです: $1',
'subcategorycount' => 'このカテゴリには $1 のサブカテゴリがあります。',
'subcategorycount1' => 'このカテゴリには $1 のサブカテゴリがあります。',
'categoryarticlecount' => 'このカテゴリには $1 の項目があります。',
'categoryarticlecount1' => 'このカテゴリには $1 の項目があります。',
'usenewcategorypage' => '1

Set first character to "0" to disable the new category page layout.',
'listingcontinuesabbrev' => ' の続き',

# Info page
'infosubtitle' => 'ページ情報',
'numedits' => '編集数（項目）: $1',
'numtalkedits' => '編集数（ノート）: $1',
'numwatchers' => 'ウォッチしているユーザー数: $1',
'numauthors' => '投稿者数（項目）: $1',
'numtalkauthors' => '投稿者数（ノート）: $1',

# Math options
'mw_math_png' => '常にPNG',
'mw_math_simple' => 'シンプルな数式はHTML、それ以外はPNG',
'mw_math_html' => 'できる限りHTML、さもなければPNG',
'mw_math_source' => 'TeXのままにする (テキストブラウザ向け)',
'mw_math_modern' => '最近のブラウザで推奨',
'mw_math_mathml' => '可能ならばMathMLを使う (実験中の機能)',

# Patrolling
'markaspatrolleddiff' => 'パトロール済みにする',
'markaspatrolledlink' => "<div class='patrollink'>[$1]</div>",
'markaspatrolledtext' => 'この項目をパトロール済みにする',
'markedaspatrolled' => 'パトロール済みにしました',
'markedaspatrolledtext' => '選択された編集をパトロール済みにしました。',
'rcpatroldisabled' => 'RCパトロールが無効です',
'rcpatroldisabledtext' => '最近更新されたページのパトロール機能は現在無効になっています。',

# Monobook.js: tooltips and access keys for monobook
'Monobook.js' => "/* tooltips and access keys */
ta = new Object();
ta['pt-userpage'] = new Array('.','自分の利用者ページ'); 
ta['pt-anonuserpage'] = new Array('.','あなたのIPアドレス用の利用者ページ'); 
ta['pt-mytalk'] = new Array('n','自分の会話ページ'); 
ta['pt-anontalk'] = new Array('n','あなたのIPアドレスからなされた編集の会話ページ'); 
ta['pt-preferences'] = new Array('','オプションの変更'); 
ta['pt-watchlist'] = new Array('l','変更を監視しているページの一覧'); 
ta['pt-mycontris'] = new Array('y','自分の投稿記録'); 
ta['pt-login'] = new Array('o','ログインすることが推奨されますが、しなくても構いません。'); 
ta['pt-anonlogin'] = new Array('o','ログインすることが推奨されますが、しなくても構いません。'); 
ta['pt-logout'] = new Array('o','ログアウト'); 
ta['ca-talk'] = new Array('t','項目のノート'); 
ta['ca-edit'] = new Array('e','このページを編集できます。投稿の前に「プレビューを実行」ボタンを使ってください。'); 
ta['ca-addsection'] = new Array('+','このページにコメントを加える'); 
ta['ca-viewsource'] = new Array('e','このページは保護されています。ページのソースを閲覧できます。'); 
ta['ca-history'] = new Array('h','このページの過去の版'); 
ta['ca-protect'] = new Array('=','このページを保護'); 
ta['ca-delete'] = new Array('d','このページを削除'); 
ta['ca-undelete'] = new Array('d','削除されたページを復帰する'); 
ta['ca-move'] = new Array('m','このページを移動'); 
ta['ca-watch'] = new Array('w','このページをウォッチリストへ追加'); 
ta['ca-unwatch'] = new Array('w','このページをウォッチリストから外す'); 
ta['search'] = new Array('f','ウィキ内を検索'); 
ta['p-logo'] = new Array('','メインページ'); 
ta['n-mainpage'] = new Array('z','メインページに移動'); 
ta['n-portal'] = new Array('','このプロジェクトについて、あなたのできることを探す場所です'); 
ta['n-currentevents'] = new Array('','最近の出来事'); 
ta['n-recentchanges'] = new Array('r','最近更新が行われたページの一覧'); 
ta['n-randompage'] = new Array('x','ランダムに記事を選んで表示'); 
ta['n-help'] = new Array('','ヘルプ・使い方'); 
ta['n-sitesupport'] = new Array('','私たちをサポートしてください'); 
ta['t-whatlinkshere'] = new Array('j','このページにリンクしているページの一覧'); 
ta['t-recentchangeslinked'] = new Array('k','最近更新が行われたこのページのリンク先'); 
ta['feed-rss'] = new Array('','このページのRSSフィード'); 
ta['feed-atom'] = new Array('','このページのAtomフィード'); 
ta['t-contributions'] = new Array('','ユーザーの投稿記録'); 
ta['t-emailuser'] = new Array('','このユーザーにメールを送信'); 
ta['t-upload'] = new Array('u','画像やメディアファイルをアップロード'); 
ta['t-specialpages'] = new Array('q','特別ページの一覧'); 
ta['ca-nstab-main'] = new Array('c','本文を表示'); 
ta['ca-nstab-user'] = new Array('c','利用者ページを表示'); 
ta['ca-nstab-media'] = new Array('c','メディアページを表示'); 
ta['ca-nstab-special'] = new Array('','これは特別ページです。編集することはできません。'); 
ta['ca-nstab-wp'] = new Array('a','{{SITENAME}}ページを表示'); 
ta['ca-nstab-image'] = new Array('c','画像ページを表示'); 
ta['ca-nstab-mediawiki'] = new Array('c','定型文を表示'); 
ta['ca-nstab-template'] = new Array('c','テンプレートを表示'); 
ta['ca-nstab-help'] = new Array('c','ヘルプページを表示'); 
ta['ca-nstab-category'] = new Array('c','カテゴリページを表示');",

# image deletion
'deletedrevision' => '古い版 $1 を削除しました。',

# browsing diffs
'previousdiff' => '← 前の差分へ',
'nextdiff' => '次の差分へ →',

'imagemaxsize' => '画像ページで表示する画像の最大値:',
'thumbsize' => 'サムネイルの大きさ:',
'showbigimage' => '高解像度版をダウンロードする ($1x$2, $3 KB)',

'newimages' => '新着画像ギャラリー',
'noimages' => '画像がありません。',

# short names for language variants used for language conversion links.
# to disable showing a particular link, set it to 'disable', e.g.
# 'variantname-zh-sg' => 'disable',
'variantname-zh-cn' => '簡体 (中国)',
'variantname-zh-tw' => '正字 (台湾)',
'variantname-zh-hk' => '正字 (香港)',
'variantname-zh-sg' => '簡体 (シンガポール)',
'variantname-zh' => '無変換',

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'ユーザー名:',
'speciallogtitlelabel' => 'タイトル:',

'passwordtooshort' => 'パスワードが短すぎます。$1文字以上の文字列にしてください。',

# Media Warning
'mediawarning' => "'''警告:''' このファイルは悪意のあるコードを含んでいる可能性があり、実行するとコンピューターが危害を被る場合があります。
----",

'fileinfo' => '$1KB, MIMEタイプ: <code>$2</code>',

# Metadata
'metadata' => 'メタデータ',

# Exif tags
'exif-imagewidth' => '画像の幅',
'exif-imagelength' => '画像の高さ',
'exif-bitspersample' => 'ビット深度',
'exif-compression' => '圧縮形式',
'exif-photometricinterpretation' => '画素構成',
'exif-orientation' => '画像方向',
'exif-samplesperpixel' => 'コンポーネント数',
'exif-planarconfiguration' => 'データ格納形式',
'exif-ycbcrsubsampling' => 'YCCの画素構成（Cの間引き率）',
'exif-ycbcrpositioning' => 'YCCの画素構成（YとCの位置）',
'exif-xresolution' => '水平解像度',
'exif-yresolution' => '垂直解像度',
'exif-resolutionunit' => '解像度の単位',
'exif-stripoffsets' => '画像データの場所',
'exif-rowsperstrip' => 'ストリップのライン数',
'exif-stripbytecounts' => 'ストリップのデータ量',
'exif-jpeginterchangeformat' => 'JPEGのSOIへのオフセット',
'exif-jpeginterchangeformatlength' => 'JPEGデータのバイト数',
'exif-transferfunction' => '再生階調カーブ特性',
'exif-whitepoint' => '参照白色点の色度座標値',
'exif-primarychromaticities' => '原色の色度座標値',
'exif-ycbcrcoefficients' => '色変換マトリックス係数',
'exif-referenceblackwhite' => '参照黒色点値・参照白色点値',
'exif-datetime' => 'ファイル変更日時',
'exif-imagedescription' => '画像の説明',
'exif-make' => '画像入力機器のメーカー',
'exif-model' => '画像入力機器の機種',
'exif-software' => 'ファームウェアのバージョン',
'exif-artist' => '作成者',
'exif-copyright' => '著作権者',
'exif-exifversion' => 'EXIFバージョン',
'exif-flashpixversion' => '対応フラッシュピックスバージョン',
'exif-colorspace' => '色空間',
'exif-componentsconfiguration' => '各コンポーネントの構成',
'exif-compressedbitsperpixel' => '画像圧縮モード',
'exif-pixelydimension' => '実効画像幅',
'exif-pixelxdimension' => '実効画像高さ',
'exif-makernote' => 'メーカーノート',
'exif-usercomment' => 'ユーザーコメント',
'exif-relatedsoundfile' => '関連音声ファイル',
'exif-datetimeoriginal' => '画像データ生成日時',
'exif-datetimedigitized' => 'デジタルデータ作成日時',
'exif-subsectime' => 'ファイル変更日時 (秒未満)',
'exif-subsectimeoriginal' => '画像データ生成日時 (秒未満)',
'exif-subsectimedigitized' => 'デジタルデータ作成日時 (秒未満)',
'exif-exposuretime' => '露出時間',
'exif-fnumber' => 'F値',
'exif-exposureprogram' => '露出プログラム',
'exif-spectralsensitivity' => 'スペクトル感度',
'exif-isospeedratings' => 'ISOスピードレート',
'exif-oecf' => '光電変換関数',
'exif-shutterspeedvalue' => 'シャッタースピード',
'exif-aperturevalue' => '絞り値',
'exif-brightnessvalue' => '明るさ',
'exif-exposurebiasvalue' => '露出補正値',
'exif-maxaperturevalue' => 'レンズ最小F値',
'exif-subjectdistance' => '被写体距離',
'exif-meteringmode' => '測光方式',
'exif-lightsource' => '光源',
'exif-flash' => 'フラッシュ',
'exif-focallength' => 'レンズの焦点距離',
'exif-subjectarea' => '主要被写体の位置',
'exif-flashenergy' => 'フラッシュ強度',
'exif-spatialfrequencyresponse' => '空間周波数応答',
'exif-focalplanexresolution' => '水平方向の焦点面解像度',
'exif-focalplaneyresolution' => '垂直方向の焦点面解像度',
'exif-focalplaneresolutionunit' => '焦点面解像度の単位',
'exif-subjectlocation' => '被写体の場所',
'exif-exposureindex' => '露出インデックス',
'exif-sensingmethod' => 'センサー方式',
'exif-filesource' => 'ファイルソース',
'exif-scenetype' => 'シーンタイプ',
'exif-cfapattern' => 'CFAパターン',
'exif-customrendered' => '画像処理',
'exif-exposuremode' => '露出モード',
'exif-whitebalance' => 'ホワイトバランス',
'exif-digitalzoomratio' => 'デジタルズーム倍率',
'exif-focallengthin35mmfilm' => 'レンズの焦点距離（35mmフィルム換算）',
'exif-scenecapturetype' => '被写体の種別',
'exif-gaincontrol' => 'ゲインコントロール',
'exif-contrast' => 'コントラスト',
'exif-saturation' => '彩度',
'exif-sharpness' => 'シャープネス',
'exif-devicesettingdescription' => '機器設定',
'exif-subjectdistancerange' => '被写体距離の範囲',
'exif-imageuniqueid' => 'ユニーク画像ID',
'exif-gpsversionid' => 'GPSタグのバージョン',
'exif-gpslatituderef' => '北緯/南緯',
'exif-gpslatitude' => '緯度',
'exif-gpslongituderef' => '東経/西経',
'exif-gpslongitude' => '経度',
'exif-gpsaltituderef' => '高度の基準',
'exif-gpsaltitude' => '高度',
'exif-gpstimestamp' => 'GPS時刻（原子時計）',
'exif-gpssatellites' => '測位に用いた衛星信号',
'exif-gpsstatus' => 'GPS受信機の状態',
'exif-gpsmeasuremode' => 'GPS測位方法',
'exif-gpsdop' => '測位精度',
'exif-gpsspeedref' => '速度の単位',
'exif-gpsspeed' => '速度',
'exif-gpstrackref' => '進行方向の基準',
'exif-gpstrack' => '進行方向',
'exif-gpsimgdirectionref' => '撮影方向の基準',
'exif-gpsimgdirection' => '撮影方向',
'exif-gpsmapdatum' => '測地系',
'exif-gpsdestlatituderef' => '目的地の北緯/南緯',
'exif-gpsdestlatitude' => '目的地の緯度',
'exif-gpsdestlongituderef' => '目的地の東経/西経',
'exif-gpsdestlongitude' => '目的地の経度',
'exif-gpsdestbearingref' => '目的地の方角の基準',
'exif-gpsdestbearing' => '目的地の方角',
'exif-gpsdestdistanceref' => '目的地までの距離の単位',
'exif-gpsdestdistance' => '目的地までの距離',
'exif-gpsprocessingmethod' => 'GPS処理方法',
'exif-gpsareainformation' => 'GPSエリア名',
'exif-gpsdatestamp' => 'GPS測位日時',
'exif-gpsdifferential' => 'ディファレンシャル補正',

# Make & model, can be wikified in order to link to the camera and model name

'exif-make-value' => '$1',
'exif-model-value' => '$1',
'exif-software-value' => '$1',

# Exif attributes

'exif-compression-1' => '非圧縮',
'exif-compression-6' => 'JPEG圧縮',

'exif-photometricinterpretation-1' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-orientation-1' => '通常',
'exif-orientation-2' => '左右反転',
'exif-orientation-3' => '180°回転',
'exif-orientation-4' => '上下反転',
'exif-orientation-5' => '反時計回りに90°回転 上下反転',
'exif-orientation-6' => '時計回りに90°回転',
'exif-orientation-7' => '時計回りに90°回転 上下反転',
'exif-orientation-8' => '反時計回りに90°回転',

'exif-planarconfiguration-1' => '点順次フォーマット',
'exif-planarconfiguration-2' => '面順次フォーマット',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1' => 'sRGB',
'exif-colorspace-ffff.h' => 'その他',

'exif-componentsconfiguration-0' => 'なし',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => '未定義',
'exif-exposureprogram-1' => 'マニュアル',
'exif-exposureprogram-2' => 'ノーマルプログラム',
'exif-exposureprogram-3' => '露出優先',
'exif-exposureprogram-4' => 'シャッター速度優先',
'exif-exposureprogram-5' => 'クリエイティブプログラム',
'exif-exposureprogram-6' => 'アクションプログラム',
'exif-exposureprogram-7' => 'ポートレイトモード（近景）',
'exif-exposureprogram-8' => 'ランドスケープモード（遠景）',

'exif-subjectdistance-value' => '$1 メートル',

'exif-meteringmode-0' => '不明',
'exif-meteringmode-1' => '平均',
'exif-meteringmode-2' => '中央重点',
'exif-meteringmode-3' => 'スポット',
'exif-meteringmode-4' => 'マルチスポット',
'exif-meteringmode-5' => '分割測光',
'exif-meteringmode-6' => '部分測光',
'exif-meteringmode-255' => 'その他',

'exif-lightsource-0' => '不明',
'exif-lightsource-1' => '昼光',
'exif-lightsource-2' => '蛍光灯',
'exif-lightsource-3' => 'タングステン（白熱灯）',
'exif-lightsource-4' => 'フラッシュ',
'exif-lightsource-9' => '晴天',
'exif-lightsource-10' => '曇天',
'exif-lightsource-11' => '日陰',
'exif-lightsource-12' => '昼光色蛍光灯 (D 5700 - 7100K)',
'exif-lightsource-13' => '昼白色蛍光灯 (N 4600 - 5400K)',
'exif-lightsource-14' => '白色蛍光灯 (W 3900 - 4500K)',
'exif-lightsource-15' => '温白色蛍光灯 (WW 3200 - 3700K)',
'exif-lightsource-17' => '標準光A',
'exif-lightsource-18' => '標準光B',
'exif-lightsource-19' => '標準光C',
'exif-lightsource-20' => 'D55',
'exif-lightsource-21' => 'D65',
'exif-lightsource-22' => 'D75',
'exif-lightsource-23' => 'D50',
'exif-lightsource-24' => 'ISOスタジオタングステン',
'exif-lightsource-255' => 'その他',

'exif-focalplaneresolutionunit-2' => 'インチ',

'exif-sensingmethod-1' => '未定義',
'exif-sensingmethod-2' => '単板カラーセンサー',
'exif-sensingmethod-3' => '2板カラーセンサー',
'exif-sensingmethod-4' => '3板カラーセンサー',
'exif-sensingmethod-5' => '色順次カラーセンサー',
'exif-sensingmethod-7' => '3線リニアセンサー',
'exif-sensingmethod-8' => '色順次リニアセンサー',

'exif-filesource-3' => 'デジタルスチルカメラ',

'exif-scenetype-1' => '直接撮影された画像',

'exif-customrendered-0' => '通常',
'exif-customrendered-1' => 'カスタム',

'exif-exposuremode-0' => '自動',
'exif-exposuremode-1' => 'マニュアル',
'exif-exposuremode-2' => 'オートブラケット',

'exif-whitebalance-0' => '自動',
'exif-whitebalance-1' => 'マニュアル',

'exif-scenecapturetype-0' => '標準',
'exif-scenecapturetype-1' => '風景',
'exif-scenecapturetype-2' => '人物',
'exif-scenecapturetype-3' => '夜景',

'exif-gaincontrol-0' => 'なし',
'exif-gaincontrol-1' => '弱増感',
'exif-gaincontrol-2' => '強増感',
'exif-gaincontrol-3' => '弱減感',
'exif-gaincontrol-4' => '強増感',

'exif-contrast-0' => '標準',
'exif-contrast-1' => '軟調',
'exif-contrast-2' => '硬調',

'exif-saturation-0' => '標準',
'exif-saturation-1' => '低彩度',
'exif-saturation-2' => '高彩度',

'exif-sharpness-0' => '標準',
'exif-sharpness-1' => '弱',
'exif-sharpness-2' => '強',

'exif-subjectdistancerange-0' => '不明',
'exif-subjectdistancerange-1' => 'マクロ',
'exif-subjectdistancerange-2' => '近景',
'exif-subjectdistancerange-3' => '遠景',

// Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => '北緯',
'exif-gpslatitude-s' => '南緯',

// Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => '東経',
'exif-gpslongitude-w' => '西経',

'exif-gpsstatus-a' => '測位中',
'exif-gpsstatus-v' => '未測位',

'exif-gpsmeasuremode-2' => '2次元測位',
'exif-gpsmeasuremode-3' => '3次元測位',

// Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'キロメートル毎時',
'exif-gpsspeed-m' => 'マイル毎時',
'exif-gpsspeed-n' => 'ノット',

// Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '真方位',
'exif-gpsdirection-m' => '磁方位',

# external editor support
'edit-externally' => '外部アプリケーションを使ってこのファイルを編集する',
'edit-externally-help' => '詳しい情報は[http://meta.wikimedia.org/wiki/Help:External_editors 外部エディタに関する説明（英語）]をご覧ください。',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'すべて',
'imagelistall' => 'すべて',
'watchlistall1' => 'すべて',
'watchlistall2' => 'すべて',
'namespacesall' => 'すべて',

# E-mail address confirmation
'confirmemail' => 'メールアドレスの確認',
'confirmemail_text' => 'このウィキではメール通知を受け取る前にメールアドレスの確認が必要です。以下のボタンを押すと「{{int:Confirmemail_subject}}」という件名の確認メールがあなたのメールアドレスに送られます。メールには確認用コードを含むリンクが書かれています。そのリンクを開くことによってメールアドレスの正当性が確認されます。',
'confirmemail_send' => '確認用コードを送信する',
'confirmemail_sent' => '確認メールを送信しました。',
'confirmemail_sendfailed' => '確認メールを送信できませんでした。メールアドレスに不正な文字が含まれていないかどうか確認してください。',
'confirmemail_invalid' => '確認用コードが正しくありません。このコードは期限切れです。',
'confirmemail_success' => 'あなたのメールアドレスは確認されました。ログインしてウィキを楽しんでください。',
'confirmemail_loggedin' => 'あなたのメールアドレスは確認されました。',
'confirmemail_error' => 'あなたの確認を保存する際に内部エラーが発生しました。',

'confirmemail_subject' => '{{SITENAME}} e-mail address confirmation',
'confirmemail_body' => 'どなたか（IPアドレス $1 の使用者）がこのメールアドレスを使用して
{{SITENAME}} にアカウント "$2" を登録しました。

このアカウントが本当にあなたのものであるか確認し、
{{SITENAME}} のメール通知機能を有効にするために以下のURLにアクセスしてください:
$3

もし {{SITENAME}} について身に覚えがない場合は、リンクを開かないでください。
確認用コードは  $4 に期限切れになります。

-- 
{{SITENAME}} メールアドレス確認システム
{{SERVER}}/',




# Inputbox extension, may be useful in other contexts as well
'tryexact' => '一致する項目を検索',
'searchfulltext' => '全文検索',
'createarticle' => '項目を作成',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki transcluding is disabled]',
'scarytranscludefailed' => '[Template fetch failed for $1; sorry]',
'scarytranscludetoolong' => '[URL is too long; sorry]',

# Trackbacks
'trackbackbox' => '<div id="mw_trackbacks">
この項目へのトラックバック:
$1
</div>',
# 'trackback' => '',
# 'trackbackexcerpt' => '',
'trackbackremove' => ' ([$1 削除])',
'trackbacklink' => 'トラックバック',
'trackbackdeleteok' => 'トラックバックを削除しました。',

'unit-pixel' => 'px',

);

class LanguageJa extends LanguageUtf8 {

	function getNamespaces() {
		global $wgNamespaceNamesJa;
		return $wgNamespaceNamesJa;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsJa;
		return $wgQuickbarSettingsJa;
	}

	function getSkinNames() {
		global $wgSkinNamesJa;
		return $wgSkinNamesJa;
	}

	function getDateFormats() {
		global $wgDateFormatsJa;
		return $wgDateFormatsJa;
	}
	
	function date( $ts, $adj = false, $format = true, $tc = false ) {
		global $wgWeekdayAbbreviationsJa;
		
		if ( $adj ) { $ts = $this->userAdjust( $ts, $tc ); }
		$datePreference = $this->dateFormat( $format );
		
		if( $datePreference == MW_DATE_ISO ) {
			$d = substr($ts, 0, 4). '-' . substr($ts, 4, 2). '-' .
					substr($ts, 6, 2);
			return $d;
		}
		
		$year = (int)substr( $ts, 0, 4 );
		$month = (int)substr( $ts, 4, 2 );
		$mday = (int)substr( $ts, 6, 2 );
		$hour = (int)substr( $ts, 8, 2 );
		$minute = (int)substr( $ts, 10, 2 );
		$second = (int)substr( $ts, 12, 2 );
		
		$time = mktime( $hour, $minute, $second, $month, $mday, $year );
		$date = getdate( $time );
		
		$d = $year . "年" .
				$this->getMonthAbbreviation( $month ) .
				$mday . "日 (" .
				$wgWeekdayAbbreviationsJa[ $date['wday'] ]. ")";
		return $d;
	}

	function time( $ts, $adj = false, $format = true, $tc = false ) {
		if ( $adj ) { $ts = $this->userAdjust( $ts, $tc ); }
		$datePreference = $this->dateFormat( $format );
		
		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		if ( $datePreference == MW_DATE_ISO ) {
			$t .= ':' . substr( $ts, 12, 2 );
		}
		
		return $t;
	}

	function timeanddate( $ts, $adj = false, $format = true, $tc = false ) {
		return $this->date( $ts, $adj, $format, $tc ) . " " . $this->time( $ts, $adj, $format, $tc );
	}

	function getMessage( $key ) {
		global $wgAllMessagesJa;
		if(array_key_exists($key, $wgAllMessagesJa))
			return $wgAllMessagesJa[$key];
		else
			return parent::getMessage($key);
	}

	function stripForSearch( $string ) {
		# MySQL fulltext index doesn't grok utf-8, so we
		# need to fold cases and convert to hex
		global $wikiLowerChars;
		$s = $string;

		# Strip known punctuation ?
		#$s = preg_replace( '/\xe3\x80[\x80-\xbf]/', '', $s ); # U3000-303f

		# Space strings of like hiragana/katakana/kanji
		$hiragana = '(?:\xe3(?:\x81[\x80-\xbf]|\x82[\x80-\x9f]))'; # U3040-309f
		$katakana = '(?:\xe3(?:\x82[\xa0-\xbf]|\x83[\x80-\xbf]))'; # U30a0-30ff
		$kanji = '(?:\xe3[\x88-\xbf][\x80-\xbf]'
			. '|[\xe4-\xe8][\x80-\xbf]{2}'
			. '|\xe9[\x80-\xa5][\x80-\xbf]'
			. '|\xe9\xa6[\x80-\x99])';
			# U3200-9999 = \xe3\x88\x80-\xe9\xa6\x99
		$s = preg_replace( "/({$hiragana}+|{$katakana}+|{$kanji}+)/", ' $1 ', $s );

		# Double-width roman characters: ff00-ff5f ~= 0020-007f
		$s = preg_replace( '/\xef\xbc([\x80-\xbf])/e', 'chr((ord("$1") & 0x3f) + 0x20)', $s );
		$s = preg_replace( '/\xef\xbd([\x80-\x99])/e', 'chr((ord("$1") & 0x3f) + 0x60)', $s );

		# Do general case folding and UTF-8 armoring
		return LanguageUtf8::stripForSearch( $s );
	}

	# Italic is not appropriate for Japanese script
	# Unfortunately most browsers do not recognise this, and render <em> as italic
	function emphasize( $text ) {
		return $text;
	}
}

?>
