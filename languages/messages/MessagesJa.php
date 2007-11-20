<?php
/** Japanese (日本語)
 *
 * @addtogroup Language
 *
 * @author Suisui
 * @author Marine-Blue
 * @author Broad-Sky
 * @author Kahusi
 * @author G - ג
 * @author Nike
 * @author Siebrand
 * @author Hatukanezumi
 * @author SPQRobin
 * @author Emk
 */

$skinNames = array(
	'standard' => "標準",
	'nostalgia' => "ノスタルジア",
	'cologneblue' => "ケルンブルー",
);

$datePreferences = array(
	'default',
	'ISO 8601',
);

$defaultDateFormat = 'ja';

$dateFormats = array(
	'ja time' => 'H:i',
	'ja date' => 'Y年n月j日 (D)',
	'ja both' => 'Y年n月j日 (D) H:i',
);

$namespaceNames = array(
	NS_MEDIA          => "Media", /* Media */
	NS_SPECIAL        => "特別", /* Special */
	NS_MAIN           => "",
	NS_TALK           => "ノート", /* Talk */
	NS_USER           => "利用者", /* User */
	NS_USER_TALK      => "利用者‐会話", /* User_talk */
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => '$1‐ノート', /* Wikipedia_talk */
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
);

$messages = array(
# User preference toggles
'tog-underline'               => 'リンクの下線:',
'tog-highlightbroken'         => '未作成のページへのリンクをハイライトする',
'tog-justify'                 => '段落を均等割り付けする',
'tog-hideminor'               => '最近更新したページから細部の編集を隠す',
'tog-extendwatchlist'         => 'ウォッチリストを拡張する',
'tog-usenewrc'                => '最近更新したページを拡張する（ブラウザによっては使えないことがあります）',
'tog-numberheadings'          => '見出しに番号を振る',
'tog-showtoolbar'             => '編集ボタンを表示する',
'tog-editondblclick'          => 'ダブルクリックで編集する (JavaScript)',
'tog-editsection'             => 'セクション編集用リンクを有効にする',
'tog-editsectiononrightclick' => 'セクションタイトルの右クリックでセクション編集を行えるようにする (JavaScript)',
'tog-showtoc'                 => '目次を表示する (4つ以上の見出しがあるページ)',
'tog-rememberpassword'        => 'セッションを越えてパスワードを記憶する',
'tog-editwidth'               => 'テキストボックスを横幅いっぱいに表示する',
'tog-watchcreations'          => '自分で作成したページをウォッチリストに追加する',
'tog-watchdefault'            => '編集したページをウォッチリストに追加する',
'tog-watchmoves'              => '自分が移動したページをウォッチリストに追加する',
'tog-watchdeletion'           => '自分が削除したページをウォッチリストに追加する',
'tog-minordefault'            => '細部の編集をデフォルトでチェックする',
'tog-previewontop'            => 'プレビューをテキストボックスの前に配置する',
'tog-previewonfirst'          => '編集開始時にもプレビューを表示する',
'tog-nocache'                 => 'ページをキャッシュしない',
'tog-enotifwatchlistpages'    => 'ウォッチリストにあるページが更新されたときにメールを受け取る',
'tog-enotifusertalkpages'     => '自分の会話ページが更新されたときにメールを受け取る',
'tog-enotifminoredits'        => '細部の編集でもメールを受け取る',
'tog-enotifrevealaddr'        => 'あなた以外に送られる通知メールにあなたのメールアドレスを記載する',
'tog-shownumberswatching'     => 'ページをウォッチしている利用者数を表示する',
'tog-fancysig'                => '署名に利用者ページへの自動的なリンクを付けない（このチェックを付ける場合でも利用者ページへのリンクを外さないようにしましょう）',
'tog-externaleditor'          => '編集に外部アプリケーションを使う',
'tog-externaldiff'            => '差分表示に外部アプリケーションを使う',
'tog-showjumplinks'           => 'アクセシビリティのための "{{int:jumpto}}" リンクを有効にする',
'tog-uselivepreview'          => 'ライブプレビューを使用する (JavaScript, 試験中の機能)',
'tog-forceeditsummary'        => '要約欄が空欄の場合に警告する',
'tog-watchlisthideown'        => '自分の編集を表示しない',
'tog-watchlisthidebots'       => 'ボットによる編集を表示しない',
'tog-watchlisthideminor'      => '細部の編集を表示しない',
'tog-nolangconversion'        => '字形変換を無効にする',
'tog-ccmeonemails'            => '他ユーザーに送信したメールの控えを自分にも送る',
'tog-diffonly'                => '差分表示の下に記事本文を表示しない',

'underline-always'  => '常に付ける',
'underline-never'   => '常に付けない',
'underline-default' => 'WWWブラウザ既定',

'skinpreview' => '（プレビュー）',

# Dates
'sunday'        => '日曜日',
'monday'        => '月曜日',
'tuesday'       => '火曜日',
'wednesday'     => '水曜日',
'thursday'      => '木曜日',
'friday'        => '金曜日',
'saturday'      => '土曜日',
'sun'           => '日',
'mon'           => '月',
'tue'           => '火',
'wed'           => '水',
'thu'           => '木',
'fri'           => '金',
'sat'           => '土',
'january'       => '1月',
'february'      => '2月',
'march'         => '3月',
'april'         => '4月',
'may_long'      => '5月',
'june'          => '6月',
'july'          => '7月',
'august'        => '8月',
'september'     => '9月',
'october'       => '10月',
'november'      => '11月',
'december'      => '12月',
'january-gen'   => '1月',
'february-gen'  => '2月',
'march-gen'     => '3月',
'april-gen'     => '4月',
'may-gen'       => '5月',
'june-gen'      => '6月',
'july-gen'      => '7月',
'august-gen'    => '8月',
'september-gen' => '9月',
'october-gen'   => '10月',
'november-gen'  => '11月',
'december-gen'  => '12月',
'jan'           => '1月',
'feb'           => '2月',
'mar'           => '3月',
'apr'           => '4月',
'may'           => '5月',
'jun'           => '6月',
'jul'           => '7月',
'aug'           => '8月',
'sep'           => '9月',
'oct'           => '10月',
'nov'           => '11月',
'dec'           => '12月',

# Bits of text used by many pages
'categories'            => 'カテゴリ',
'pagecategories'        => 'カテゴリ',
'category_header'       => 'カテゴリ “$1” にあるページ',
'subcategories'         => 'サブカテゴリ',
'category-media-header' => 'カテゴリ “$1” にあるメディア',
'category-empty'        => 'このカテゴリにはページまたはメディアがひとつもありません。',

'mainpagetext'      => 'MediaWikiが正常にインストールされました。',
'mainpagedocfooter' => '[http://meta.wikimedia.org/wiki/MediaWiki_localisation インターフェースの変更方法]や、そのほかの使い方・設定に関しては[http://meta.wikimedia.org/wiki/Help:Contents ユーザーズガイド]を参照してください。',

'about'          => '解説',
'article'        => '本文',
'newwindow'      => '（新しいウィンドウが開きます）',
'cancel'         => '中止',
'qbfind'         => '検索',
'qbbrowse'       => '閲覧',
'qbedit'         => '編集',
'qbpageoptions'  => '個人用ツール',
'qbpageinfo'     => 'ページ情報',
'qbmyoptions'    => 'オプション',
'qbspecialpages' => '特別ページ',
'moredotdotdot'  => 'すべて表示する',
'mypage'         => 'マイ・ページ',
'mytalk'         => 'マイ・トーク',
'anontalk'       => 'このIP利用者の会話',
'navigation'     => 'ナビゲーション',

# Metadata in edit box
'metadata_help' => 'メタデータ（[[{{int:metadata-url}}]]を参照）',

'errorpagetitle'    => 'エラー',
'returnto'          => '$1 に戻る。',
'tagline'           => '出典: {{SITENAME}}',
'help'              => 'ヘルプ',
'search'            => '検索',
'searchbutton'      => '検索',
'go'                => '表示',
'searcharticle'     => '表示',
'history'           => '履歴',
'history_short'     => '履歴',
'updatedmarker'     => '最後の訪問から更新されています',
'info_short'        => 'ページ情報',
'printableversion'  => '印刷用バージョン',
'permalink'         => 'この版への固定リンク',
'print'             => '印刷',
'edit'              => '編集',
'editthispage'      => 'このページを編集',
'delete'            => '削除',
'deletethispage'    => 'このページを削除',
'undelete_short'    => '削除済$1版',
'protect'           => '保護',
'protect_change'    => '保護・解除',
'protectthispage'   => 'このページを保護',
'unprotect'         => '保護解除',
'unprotectthispage' => 'ページ保護解除',
'newpage'           => '新規ページ',
'talkpage'          => 'このページのノート',
'talkpagelinktext'  => '会話',
'specialpage'       => '特別ページ',
'personaltools'     => '個人用ツール',
'postcomment'       => '新規にコメントを投稿',
'articlepage'       => '項目を表示',
'talk'              => 'ノート',
'views'             => '表示',
'toolbox'           => 'ツールボックス',
'userpage'          => '利用者ページを表示',
'projectpage'       => 'プロジェクトページを表示',
'imagepage'         => '画像のページを表示',
'mediawikipage'     => 'インターフェースページを表示',
'templatepage'      => 'テンプレートページを表示',
'viewhelppage'      => 'ヘルプページを表示',
'categorypage'      => 'カテゴリページを表示',
'viewtalkpage'      => 'ノートを表示',
'otherlanguages'    => '他の言語',
'redirectedfrom'    => '（$1 から転送）',
'redirectpagesub'   => 'リダイレクトページ',
'lastmodifiedat'    => '最終更新 $1 $2', # $1 date, $2 time
'viewcount'         => 'このページは $1 回アクセスされました。',
'protectedpage'     => '保護されたページ',
'jumpto'            => '移動:',
'jumptonavigation'  => 'ナビゲーション',
'jumptosearch'      => '検索',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}}について',
'aboutpage'         => 'Project:{{SITENAME}}について',
'bugreports'        => 'バグの報告',
'bugreportspage'    => 'Project:バグの報告',
'copyright'         => 'コンテンツは$1のライセンスで利用することができます。',
'copyrightpagename' => '{{SITENAME}}の著作権',
'copyrightpage'     => 'Project:著作権',
'currentevents'     => '最近の出来事',
'currentevents-url' => '最近の出来事',
'disclaimers'       => '免責事項',
'disclaimerpage'    => 'Project:免責事項',
'edithelp'          => '編集の仕方',
'edithelppage'      => 'Help:編集の仕方',
'faqpage'           => 'Project:FAQ',
'helppage'          => 'Help:目次',
'mainpage'          => 'メインページ',
'policy-url'        => '{{ns:project}}:方針',
'portal'            => 'コミュニティ・ポータル',
'portal-url'        => 'Project:コミュニティ・ポータル',
'privacy'           => 'プライバシー・ポリシー',
'privacypage'       => 'Project:プライバシー・ポリシー',
'sitesupport'       => '寄付',
'sitesupport-url'   => 'Project:Site support',

'badaccess'        => '権限がありません',
'badaccess-group0' => 'あなたはこの処理を行う権限を持っていません。',
'badaccess-group1' => 'この処理は $1 の権限を持った利用者のみが実行できます。',
'badaccess-group2' => 'この処理は $1 のうちどちらかの権限を持った利用者のみが実行できます。',
'badaccess-groups' => 'この処理は $1 のうちいずれかの権限を持った利用者のみが実行できます。',

'versionrequired'     => 'MediaWiki バージョン $1 が必要',
'versionrequiredtext' => 'このページの利用には MediaWiki Version $1 が必要です。[[Special:Version|{{int:version}}]]を確認してください。',

'retrievedfrom'           => ' "$1" より作成',
'youhavenewmessages'      => 'あなた宛の$1が届いています。（$2）',
'newmessageslink'         => '新しいメッセージ',
'newmessagesdifflink'     => '差分',
'youhavenewmessagesmulti' => '$1 に新しいメッセージが届いています',
'editsection'             => '編集',
'editold'                 => '編集',
'editsectionhint'         => '節を編集: $1',
'toc'                     => '目次',
'showtoc'                 => '表示',
'hidetoc'                 => '非表示',
'thisisdeleted'           => '$1 を参照または復帰する。',
'viewdeleted'             => '$1の削除記録と履歴を確認する',
'restorelink'             => '削除された $1 編集',
'feedlinks'               => 'フィード:',
'feed-invalid'            => 'フィード形式の指定が間違っています。',
'site-rss-feed'           => '$1 をRSSフィード',
'site-atom-feed'          => '$1 をAtomフィード',
'page-rss-feed'           => '"$1" をRSSフィード',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => '本文',
'nstab-user'      => '利用者ページ',
'nstab-media'     => 'メディア',
'nstab-special'   => '特別ページ',
'nstab-project'   => '解説',
'nstab-image'     => '画像',
'nstab-mediawiki' => 'インターフェース',
'nstab-template'  => 'テンプレート',
'nstab-help'      => 'ヘルプ',
'nstab-category'  => 'カテゴリ',

# Main script and global functions
'nosuchaction'      => 'そのような動作はありません',
'nosuchactiontext'  => 'このURIで指定された動作は{{SITENAME}}で認識できません。',
'nosuchspecialpage' => 'そのような特別ページはありません',
'nospecialpagetext' => '要求された特別ページは存在しません。有効な特別ページの一覧は[[Special:Specialpages]]にあります。',

# General errors
'error'                => 'エラー',
'databaseerror'        => 'データベース・エラー',
'dberrortext'          => 'データベース検索の文法エラー。これは恐らくソフトウェアのバグを表しています。

最後に実行を試みた問い合わせ:
<blockquote><tt>$1</tt></blockquote>

from within function "<tt>$2</tt>". MySQL returned error "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'データベースクエリの文法エラーが発生しました。
----
A database query syntax error has occurred.
The last attempted database query was:
"$1"
from within function "$2".
MySQL returned error "$3: $4"',
'noconnect'            => '申し訳ありません。何らかの問題によりデータベースに接続できません。<br />$1',
'nodb'                 => 'データベース $1 を選択できません。',
'cachederror'          => 'あなたがアクセスしたページのコピーを保存したものを表示しています。また、コピーは更新されません。',
'laggedslavemode'      => '警告: ページに最新の編集が反映されていない可能性があります。反映されるまでしばらくお待ちください。',
'readonly'             => 'データベースはロックされています',
'enterlockreason'      => 'ロックする理由を入力してください。ロックが解除されるのがいつになるかの見積もりについても述べてください。',
'readonlytext'         => 'データベースは現在、新しいページの追加や編集を受け付けない「ロック状態」になっています。これはおそらく定期的なメンテナンスのためで、メンテナンス終了後は正常な状態に復帰します。データベースをロックしたサーバー管理者は次のような説明をしています:

$1',
'missingarticle'       => '<p>"$1" という題のページは見つかりませんでした。すでに削除された版を参照しようとしている可能性があります。これがソフトウェアのバグだと思われる場合は、URIと共にサーバー管理者に報告して下い。</p>',
'readonly_lag'         => 'データベースはスレーブ・サーバーがマスタ・サーバーに同期するまで自動的にロックされています。しばらくお待ちください。',
'internalerror'        => '内部処理エラー',
'internalerror_info'   => '内部エラー: $1',
'filecopyerror'        => 'ファイル "$1" から "$2" へのコピーに失敗しました。',
'filerenameerror'      => 'ファイル名を "$1" から "$2" へ変更できませんでした。',
'filedeleteerror'      => 'ファイル "$1" の削除に失敗しました。',
'directorycreateerror' => 'ディレクトリ "$1" を作成できません。',
'filenotfound'         => 'ファイル "$1" が見つかりません。',
'fileexistserror'      => '$1 への書き込みができません: ファイルが存在します',
'unexpected'           => '値が異常です: $1 = "$2"',
'formerror'            => 'エラー: フォームの送信に失敗しました。',
'badarticleerror'      => 'このページでは要求された処理を行えません。',
'cannotdelete'         => '指定されたページ、または画像の削除に失敗しました。',
'badtitle'             => 'ページタイトルの間違い',
'badtitletext'         => '要求されたページは無効か、何もないか、正しくない inter-language または inter-wiki のタイトルです。',
'perfdisabled'         => 'この機能はデータベースの負荷を軽くするために現在使えなくなっています。',
'perfcached'           => '以下のデータはキャッシュであり、しばらく更新されていません。',
'perfcachedts'         => '以下のデータは $1 に最終更新されたキャッシュです。',
'querypage-no-updates' => 'ページの更新は無効になっています。以下のデータの更新は現在行われていません。',
'wrong_wfQuery_params' => 'wfQuery()へ誤った引数が渡されました。<br />
関数: $1<br />
クエリ: $2',
'viewsource'           => 'ソースを表示',
'viewsourcefor'        => '$1 のソース',
'protectedpagetext'    => 'このページは編集できないように保護されています。',
'viewsourcetext'       => '以下にソースを表示しています:',
'protectedinterface'   => 'このページはソフトウェアのインターフェースに使用されるテキストが保存されており、問題回避のために保護されています。',
'editinginterface'     => "'''警告:''' あなたはソフトウェアのインターフェースに使用されているテキストを編集しています。このページの変更はすべての利用者に影響します。",
'sqlhidden'            => '（SQLクエリー非表示）',
'cascadeprotected'     => 'このページはカスケード保護されている以下のページから呼び出されているため、編集できないように保護されています。
$2',
'namespaceprotected'   => "'''$1''' 名前空間に属するページを編集する権限がありません。",
'customcssjsprotected' => 'このページはユーザーの環境設定を記録するページです。ユーザー本人以外は編集できません。',
'ns-specialprotected'  => '{{ns:special}}名前空間内にあるページは編集できません。',

# Login and logout pages
'logouttitle'                => 'ログアウト',
'logouttext'                 => '<p><strong>ログアウトしました。</strong>このまま{{SITENAME}}を匿名で使い続けることができます。もう一度ログインして元の、あるいは別の利用者として使うこともできます。</p>
<p>※いくつかのページはブラウザのキャッシュをクリアするまでログインしているかのように表示されることがあります。</p>',
'welcomecreation'            => '== $1 さん、ようこそ！ ==
あなたのアカウントができました。

お好みに合わせて[[Special:Preferences|オプション]]を変更することができます。',
'loginpagetitle'             => 'ログイン',
'yourname'                   => '利用者名',
'yourpassword'               => 'パスワード',
'yourpasswordagain'          => 'パスワード再入力',
'remembermypassword'         => 'セッションを越えてパスワードを記憶する',
'yourdomainname'             => 'あなたのドメイン',
'externaldberror'            => '外部の認証データベースでエラーが発生たか、または外部アカウント情報の更新が許可されていません。',
'loginproblem'               => '<b>ログインでエラーが発生しました。</b><br />再度実行してください。',
'login'                      => 'ログイン',
'loginprompt'                => '{{SITENAME}}にログインするにはクッキーを有効にする必要があります。',
'userlogin'                  => 'ログインまたはアカウント作成',
'logout'                     => 'ログアウト',
'userlogout'                 => 'ログアウト',
'notloggedin'                => 'ログインしていません',
'nologin'                    => 'アカウントはお持ちですか? $1',
'nologinlink'                => 'アカウントを作成',
'createaccount'              => 'アカウント作成',
'gotaccount'                 => 'すでにアカウントをお持ちの場合: $1',
'gotaccountlink'             => 'ログイン',
'createaccountmail'          => 'メールで送信',
'badretype'                  => '両方のパスワードが一致しません。',
'userexists'                 => 'その利用者名はすでに使われています。ほかの名前をお選びください。',
'youremail'                  => 'メールアドレス*:',
'username'                   => '利用者名:',
'uid'                        => '利用者ID:',
'yourrealname'               => '本名*:',
'yourlanguage'               => 'インターフェース言語:',
'yourvariant'                => '字体変換',
'yournick'                   => 'ニックネーム（署名用）:',
'badsig'                     => '署名が正しくありません。HTMLタグを見直してください。',
'badsiglength'               => '署名が長すぎます。$1文字以下である必要があります。',
'email'                      => 'メールアドレス',
'prefs-help-realname'        => '* 本名 (任意): 本名を入力すると、ページ・クレジットに利用者名（アカウント名）の代わりに本名が表示されます。',
'loginerror'                 => 'ログイン失敗',
'prefs-help-email'           => '* メールアドレス (任意): メールアドレスを入力すると、他の利用者からのウィキメールを受け取ることができるようになります。この時点ではあなたのメールアドレスはその利用者に知られることはありません。ただし、あなたから送信すれば、あなたのメールアドレスは先方に通知されます。',
'nocookiesnew'               => '利用者のアカウントは作成されましたが、ログインしていません。{{SITENAME}}ではログインにクッキーを使います。あなたはクッキーを無効な設定にしているようです。クッキーを有効にしてから作成した利用者名とパスワードでログインしてください。',
'nocookieslogin'             => '{{SITENAME}}ではログインにクッキーを使います。あなたはクッキーを無効な設定にしているようです。クッキーを有効にして、もう一度試してください。',
'noname'                     => '利用者名を正しく指定していません。',
'loginsuccesstitle'          => 'ログイン成功',
'loginsuccess'               => "'''{{SITENAME}} に \"\$1\" としてログインしました。'''",
'nosuchuser'                 => '"$1" という利用者は見当たりません。綴りが正しいことを再度確認するか、下記のフォームを使ってアカウントを作成してください。',
'nosuchusershort'            => '"$1" という利用者は見当たりません。綴りが正しいことを再度確認してください。',
'nouserspecified'            => '利用者名を指定してください。',
'wrongpassword'              => 'パスワードが間違っています。再度入力してください。',
'wrongpasswordempty'         => 'パスワードを空にすることはできません。再度入力してください。',
'passwordtooshort'           => 'パスワードが短すぎます。$1文字以上の文字列にしてください。',
'mailmypassword'             => '新しいパスワードをメールで送る',
'passwordremindertitle'      => '{{SITENAME}} パスワード再送通知',
'passwordremindertext'       => 'どなたか（$1 のIPアドレスの使用者）が{{SITENAME}} ($4) のログイン用パスワードの再発行を依頼しました。

利用者 "$2" のパスワードを "$3" に変更しました。
ログインして別のパスワードに変更してください。',
'noemail'                    => '利用者 "$1" のメールアドレスは登録されていません。',
'passwordsent'               => '新しいパスワードを "$1" さんの登録済みメールアドレスに送信しました。メールを受け取ったら、再度ログインしてください。',
'blocked-mailpassword'       => 'あなたの使用しているIPアドレスからの編集はブロックされています。悪用防止のため、パスワードの再発行は無効化されています。',
'eauthentsent'               => '指定されたメールアドレスにアドレス確認のためのメールを送信しました。このアカウントが本当にあなたのものであるか確認するため、あなたがメールの内容に従わない限り、その他のメールはこのアカウント宛には送信されません。',
'throttled-mailpassword'     => '新しいパスワードは $1 時間以内に送信済みです。悪用防止のため、パスワードは $1 時間間隔で再発行可能となります。',
'mailerror'                  => 'メールの送信中にエラーが発生しました: $1',
'acct_creation_throttle_hit' => 'あなたは既に $1 アカウントを作成しています。これ以上作成できません。',
'emailauthenticated'         => 'あなたのメールアドレスは $1 に確認されています。',
'emailnotauthenticated'      => 'あなたのメールアドレスは<strong>確認されていません</strong>。確認されるまで以下のいかなるメールも送られません。',
'noemailprefs'               => '<strong>これらの機能を有効にするにはメールアドレスを登録してください。</strong>',
'emailconfirmlink'           => 'メールアドレスを確認する',
'invalidemailaddress'        => '入力されたメールアドレスが正しい形式に従っていないため、受け付けられません。正しい形式で入力し直すか、メールアドレス欄を空にしてください。',
'accountcreated'             => 'アカウントを作成しました',
'accountcreatedtext'         => '利用者 $1 が作成されました。',
'loginlanguagelabel'         => '言語: $1',

# Password reset dialog
'resetpass'               => 'パスワードの再設定',
'resetpass_announce'      => 'メールで送信した臨時パスワードでログインしています。ログインを完了するには、新しいパスワードを設定しなおす必要があります。',
'resetpass_text'          => '<!-- ここにテキストを挿入 -->',
'resetpass_header'        => 'パスワードを設定しなおす',
'resetpass_submit'        => '再設定してログイン',
'resetpass_success'       => 'あなたのパスワードは変更されました。ログインしています...',
'resetpass_bad_temporary' => '無効な臨時パスワードです。パスワードは既に再設定されているか、再びパスワード通知メールが送信されています。',
'resetpass_forbidden'     => 'このウィキでは、パスワードの変更は許可されていません。',
'resetpass_missing'       => 'データがセットされていません。',

# Edit page toolbar
'bold_sample'     => '強い強調（太字）',
'bold_tip'        => '強い強調（太字）',
'italic_sample'   => '弱い強調（斜体）',
'italic_tip'      => '弱い強調（斜体）',
'link_sample'     => '項目名',
'link_tip'        => '内部リンク',
'extlink_sample'  => 'http://www.example.com リンクのタイトル',
'extlink_tip'     => '外部リンク（http:// を忘れずにつけてください）',
'headline_sample' => '見出し',
'headline_tip'    => '標準の見出し',
'math_sample'     => '\int f(x)dx',
'math_tip'        => '数式 (LaTeX)',
'nowiki_sample'   => 'そのまま表示させたい文字を入力',
'nowiki_tip'      => '入力文字をそのまま表示',
'image_tip'       => '画像の埋め込み',
'media_sample'    => 'Example.mp3',
'media_tip'       => 'メディアファイル（音声）へのリンク',
'sig_tip'         => '時刻つきの署名',
'hr_tip'          => '水平線（利用は控えめに）',

# Edit pages
'summary'                   => '編集内容の要約',
'subject'                   => '題名・見出し',
'minoredit'                 => 'これは細部の編集です',
'watchthis'                 => 'ウォッチリストに追加',
'savearticle'               => '保存する',
'preview'                   => 'プレビュー',
'showpreview'               => 'プレビューを実行',
'showlivepreview'           => 'ライブプレビュー',
'showdiff'                  => '差分を表示',
'anoneditwarning'           => 'あなたはログインしていません。このまま投稿を行った場合あなたのIPアドレスはこの項目の履歴に記録されます。',
'missingsummary'            => "'''注意:''' 要約欄が空欄です。投稿ボタンをもう一度押すと、要約なしのまま投稿されます。",
'missingcommenttext'        => '以下にコメントを入力してください。',
'missingcommentheader'      => "'''注意:''' 題名・見出しが空欄です。投稿ボタンをもう一度押すと、要約なしのまま投稿されます。",
'summary-preview'           => '要約のプレビュー',
'subject-preview'           => '題名・見出しのプレビュー',
'blockedtitle'              => '投稿ブロック',
'blockedtext'               => 'ご使用の利用者名またはIPアドレスは $1 によって投稿をブロックされています。その理由は次の通りです。
:$2

ブロック解除予定: $6

$1 または他の[[{{int:grouppage-sysop}}|{{int:group-sysop}}]]にこの件についてメールで問い合わせることができます。ただし、[[Special:Preferences|オプション]]に正しいメールアドレスが登録されていない場合、「{{int:emailuser}}」機能が使えないことに注意してください。

あなたのIPアドレスは「$3」、ブロックIDは &#x23;$5 です。問い合わせを行う際には、この情報を必ず書いてください。',
'autoblockedtext'           => 'ご利用のIPアドレスは $1 によって投稿をブロックされています。その理由は次の通りです。
:$2

ブロック解除予定: $6<br />
ブロック対象: $7

$1 または他の[[{{int:grouppage-sysop}}|{{int:group-sysop}}]]にこの件について問い合わせることができます。

ただし、[[Special:Preferences|オプション]]に正しいメールアドレスが登録されていない場合、「{{int:emailuser}}」機能が使えないことに注意してください。

あなたのブロックIDは &#x23;$5 です。問い合わせを行う際には、この情報を必ず書いてください。',
'blockedoriginalsource'     => "以下に '''$1''' のソースを示します:",
'blockededitsource'         => "'''$1''' への '''あなたの編集''' を以下に示します:",
'whitelistedittitle'        => '編集にはログインが必要',
'whitelistedittext'         => 'このページを編集するには $1 する必要があります。',
'whitelistreadtitle'        => '閲覧にはログインが必要',
'whitelistreadtext'         => 'このページを閲覧するには[[Special:Userlogin|ログイン]]する必要があります。',
'whitelistacctitle'         => 'アカウントの作成は許可されていません',
'whitelistacctext'          => '{{SITENAME}}のアカウントを作成するには、適切な権限を持った利用者名で[[Special:Userlogin|ログイン]]する必要があります。',
'confirmedittitle'          => '編集にはメールアドレスの確認が必要です。',
'confirmedittext'           => 'ページの編集を始める前にメールアドレスの確認をする必要があります。[[Special:Preferences|オプション]]でメールアドレスを設定し、確認を行ってください。',
'nosuchsectiontitle'        => 'セクションが存在しません',
'nosuchsectiontext'         => '指定されたセクションはありません。セクション $1 はありませんでしたので、セクション編集は無効となります。編集内容は保存されません。',
'loginreqtitle'             => 'ログインが必要',
'loginreqlink'              => 'ログイン',
'loginreqpagetext'          => '他のページを閲覧するには$1する必要があります。',
'accmailtitle'              => 'パスワードを送信しました',
'accmailtext'               => '"$1" のパスワードを $2 に送信しました。',
'newarticle'                => '（新規）',
'newarticletext'            => 'ページを新規に作成するには新しい内容を書き込んでください。',
'anontalkpagetext'          => "----
''これはアカウントをまだ作成していないか、あるいは使っていない匿名利用者のための会話ページです。{{SITENAME}}では匿名利用者の識別は利用者名のかわりにIPアドレスを用います。IPアドレスは何人かで共有されることがあります。もしも、あなたが匿名利用者で無関係なコメントがここに寄せられる場合は、[[Special:Userlogin|アカウントを作成するかログインして]]他の匿名利用者と間違えられないようにしてくださるようお願いします。",
'noarticletext'             => '現在このページには内容がありません。他のページから[[{{ns:special}}:Search/{{PAGENAME}}|このページタイトルを検索する]]か、[{{fullurl:{{FULLPAGENAME}}|action=edit}} このページを編集]できます。',
'clearyourcache'            => "'''お知らせ:''' 保存した後、ブラウザのキャッシュをクリアする必要があります。
* '''Mozilla / Firefox / Safari:''' [Shift] を押しながら [再読み込み] をクリック、または [Shift]-[Ctrl]-[R] （Macでは [Cmd]-[Shift]-[R]）
* '''IE:''' [Ctrl] を押しながら [更新] をクリック、または [Ctrl]-[F5]
* '''Konqueror:''' [再読み込み] をクリック、または [F5]
* '''Opera:''' 「ツール」→「設定」からキャッシュをクリア。",
'usercssjsyoucanpreview'    => '<strong>ヒント:</strong> 「{{int:showpreview}}」ボタンを使うと保存前に新しいスタイルシート・スクリプトをテストできます。',
'usercsspreview'            => "'''あなたはユーザースタイルシートをプレビューしています。まだ保存されていないので注意してください。'''",
'userjspreview'             => "'''あなたはユーザースクリプトをテスト・プレビューしています。まだ保存されていないので注意してください。'''",
'userinvalidcssjstitle'     => "'''警告:''' \"\$1\" という外装はありません。.css と .js ページを編集する際にはタイトルを小文字にすることを忘れないでください。例えば {{ns:user}}:Hoge/Monobook.css ではなく {{ns:user}}:Hoge/monobook.css となります。",
'updated'                   => '（更新）',
'note'                      => '<strong>お知らせ:</strong>',
'previewnote'               => 'これはプレビューです。まだ保存されていません!',
'previewconflict'           => 'このプレビューは、上の文章編集エリアの文章を保存した場合にどう見えるようになるかを示すものです。',
'session_fail_preview'      => '<strong>セッションが切断されたため編集を保存できません。もう一度やりなおしてください。それでも失敗する場合、ログアウトしてからログインし直してください。</strong>',
'session_fail_preview_html' => '<strong>セッションデータが見つからないため、あなたの編集を保存することができませんでした。</strong>

このウィキでは raw HTML の記述を許可しており、JavaScript でのアタックを予防するためにプレビューを隠しています。

<strong>この編集が問題ないものであるならば再度保存してください。それでもうまくいかない際には一度ログアウトして、もう一度ログインしてみてください。</strong>',
'token_suffix_mismatch'     => '<strong>あなたの使用しているクライアントが、エディット・トークン内の句読点を正しく処理していないことを確認しました。
このページの文章が破損するのを防ぐため、あなたの編集は反映されません。
問題のある匿名プロクシサービスを利用していると、この問題が起こることがあります。</strong>',
'editing'                   => '$1 を編集中',
'editinguser'               => '$1 を編集中',
'editingsection'            => '$1 を編集中（節単位編集）',
'editingcomment'            => '$1 を編集中（新規コメント）',
'editconflict'              => '編集競合: $1',
'explainconflict'           => 'あなたがこのページを編集し始めた後に、他の誰かがこのページを変更しました。上側のテキストエリアは現在の最新の状態です。あなたの編集していた文章は下側のテキストエリアに示されています。編集していた文章を、上側のテキストエリアの文章に組み込んでください。<strong>上側のテキストエリアの内容だけ</strong>が、「{{int:Savearticle}}」をクリックした時に実際に保存されます。',
'yourtext'                  => 'あなたの文章',
'storedversion'             => '保存された版',
'nonunicodebrowser'         => '<strong>警告: あなたの使用しているブラウザはUnicode互換ではありません。項目を編集する前にブラウザを変更してください。</strong>',
'editingold'                => '<strong>警告: あなたはこのページの古い版を編集しています。もしこの文章を保存すると、この版以降に追加された全ての変更が無効になってしまいます。</strong>',
'yourdiff'                  => 'あなたの更新内容',
'copyrightwarning'          => "'''■投稿する前に以下を確認してください■'''
* {{SITENAME}}に投稿された文書は、すべて$2（詳細は$1を参照）によって公開されることに同意してください。
* あなたの文章が他人によって自由に編集、配布されることを望まない場合は、投稿を控えてください。
* あなたの投稿する文章はあなた自身によって書かれたものであるか、パブリック・ドメインかそれに類する自由なリソースからの複製であることを約束してください。'''あなたが著作権を保持していない作品を許諾なしに投稿してはいけません!'''",
'copyrightwarning2'         => "'''■投稿する前に以下を確認してください■'''
* あなたの文章が他人によって自由に編集、配布されることを望まない場合は、投稿を控えてください。
* あなたの投稿する文章はあなた自身によって書かれたものであるか、パブリック・ドメインかそれに類する自由なリソースからの複製であることを約束してください（詳細は$1を参照）。'''あなたが著作権を保持していない作品を許諾なしに投稿してはいけません!'''",
'longpagewarning'           => "'''警告:''' このページのサイズは $1 キロバイトです。一部の古いブラウザでは 32 キロバイト以上のページを編集すると問題が起きるものがあります。ページを節に分けることを検討してください。",
'longpageerror'             => '<strong>エラー: あなたが投稿したテキストは $1 キロバイトの長さがあります。これは投稿できる最大の長さである $2 キロバイトを超えています。この編集を保存することはできません。</strong>',
'readonlywarning'           => '<strong>警告: データベースがメンテナンスのためにロックされています。現在は編集結果を保存できません。文章をカットアンドペーストしてローカルファイルとして保存し、後ほど保存をやり直してください。</strong>',
'protectedpagewarning'      => "'''警告:''' このページは保護されています。{{int:group-sysop}}しか編集できません。",
'semiprotectedpagewarning'  => "'''お知らせ:''' このページは登録利用者のみが編集できるよう保護されています。",
'cascadeprotectedwarning'   => "'''警告:''' このページはカスケード保護されている以下のページから呼び出されているため、{{int:group-sysop}}しか編集できません。",
'templatesused'             => 'このページで使われているテンプレート:',
'templatesusedpreview'      => 'このプレビューで使われているテンプレート:',
'templatesusedsection'      => 'この節で使われているテンプレート:',
'template-protected'        => '（保護）',
'template-semiprotected'    => '（半保護）',
'edittools'                 => '<!-- ここに書いたテキストは編集及びアップロードのフォームの下に表示されます。 -->',
'nocreatetitle'             => 'ページを作成できません',
'nocreatetext'              => 'このサイトではページの新規作成を制限しています。元のページに戻って既存のページを編集するか、[[Special:Userlogin|ログイン]]してください。',
'nocreate-loggedin'         => 'このウィキで新しいページを作成する権限がありません。',
'permissionserrors'         => '認証エラー',
'permissionserrorstext'     => 'あなたにはこのページの編集権限がありません。理由は以下の通りです:',
'recreate-deleted-warn'     => "'''警告:あなたは以前に削除されたページを再作成しようとしています。'''

このページの編集が適切であるかどうか確認してください。参考として以下にこのページの削除記録を表示しています:",

# "Undo" feature
'undo-success' => '編集の取り消しに成功しました。保存ボタンを押すと変更が確定されます。',
'undo-failure' => '中間の版での編集と競合したため、自動取り消しできませんでした。',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|会話]]) の $1 版の編集を取り消し',

# Account creation failure
'cantcreateaccounttitle' => 'アカウントを作成できません',
'cantcreateaccount-text' => "以下の理由により、このIPアドレス ('''$1''') からのアカウント作成が [[User:$3|$3]] によってブロックされています。

ブロック理由: ''$2''",

# History pages
'revhistory'          => '変更履歴',
'viewpagelogs'        => 'このページに関するログを見る',
'nohistory'           => 'このページには変更履歴がありません。',
'revnotfound'         => '要求された版が見つかりません。',
'revnotfoundtext'     => '要求されたこのページの旧版は見つかりませんでした。このページにアクセスしたURLをもう一度確認してください。',
'loadhist'            => '変更履歴の読み込み中',
'currentrev'          => '最新版',
'revisionasof'        => '$1の版',
'revision-info'       => '$1; $2 による版',
'previousrevision'    => '←前の版',
'nextrevision'        => '次の版→',
'currentrevisionlink' => '最新版を表示',
'cur'                 => '最新版',
'next'                => '次の版',
'last'                => '前の版',
'orig'                => '最古版',
'page_first'          => '先頭',
'page_last'           => '末尾',
'histlegend'          => '凡例:（最新版）= 最新版との比較、（前の版）= 直前の版との比較、<strong>{{int:minoreditletter}}</strong> = 細部の編集',
'deletedrev'          => '[削除済み]',
'histfirst'           => '最古',
'histlast'            => '最新',
'historysize'         => '（$1 バイト）',
'historyempty'        => '（空です）',

# Revision feed
'history-feed-title'          => '変更履歴',
'history-feed-description'    => 'このウィキのこのページに関する変更履歴',
'history-feed-item-nocomment' => '$2 における $1 による編集', # user at time
'history-feed-empty'          => '要求したページは存在しません。既に削除されたか移動された可能性があります。 [[Special:Search|このウィキの検索]]で関連する新しいページを探してみてください。',

# Revision deletion
'rev-deleted-comment'         => '（要約は削除されています）',
'rev-deleted-user'            => '（投稿者名は削除されています）',
'rev-deleted-event'           => '（記事は削除されています）',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
この版は公の履歴から削除されました。[{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} 削除記録]におそらくログがあります。</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
この版は公の履歴から削除されており、このサイトの{{int:group-sysop}}だけが内容を見ることができます。削除の詳細は[{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} 削除記録]を参照してください。
</div>',
'rev-delundel'                => '復帰/削除',
'revisiondelete'              => '版の削除と復帰',
'revdelete-nooldid-title'     => '対象版がありません',
'revdelete-nooldid-text'      => '操作の完了に必要な版が指定されていません。',
'revdelete-selected'          => '[[:$1]]の、以下の選択された版に対する操作:',
'logdelete-selected'          => '[[:$1]]の、以下の選択されたログに対する操作:',
'revdelete-text'              => '版の削除ではページの履歴にその版は表示されます。しかしその版に含まれるテキストにはアクセスできなくなります。

サーバー管理者にこれ以上の制限をかけられない限り、他の{{int:group-sysop}}は隠れた版を読んだり、元に戻したりできます。',
'revdelete-legend'            => '版の削除の適用',
'revdelete-hide-text'         => '版のテキストを隠す',
'revdelete-hide-name'         => '操作および対象を隠す',
'revdelete-hide-comment'      => '編集の要約を隠す',
'revdelete-hide-user'         => '版の投稿者を隠す',
'revdelete-hide-restricted'   => 'これらの制限を{{int:group-sysop}}にも適用する',
'revdelete-suppress'          => 'データを{{int:group-sysop}}からも隠す',
'revdelete-hide-image'        => 'ファイル内容を隠す',
'revdelete-unsuppress'        => '復帰版に対する制限を外す',
'revdelete-log'               => '要約:',
'revdelete-submit'            => '隠蔽の設定を適用',
'revdelete-logentry'          => '[[$1]]の版の削除情報を操作しました',
'logdelete-logentry'          => '[[$1]]の版の操作情報を変更しました',
'revdelete-logaction'         => '$1 版の状態を$2に変更しました',
'logdelete-logaction'         => '[[$3]]に対する$1操作の状態を$2に変更しました',
'revdelete-success'           => '版の隠蔽状態を変更しました',
'logdelete-success'           => '操作情報の隠蔽状態を変更しました',

# Oversight log
'oversightlog'    => '版隠蔽ログ',
'overlogpagetext' => '以下は{{int:group-sysop}}が最近隠蔽した版削除およびブロックの記録です。現時点で有効な投稿ブロックは[[Special:Ipblocklist|{{int:ipblocklist}}]]をご覧ください。',

# Diffs
'history-title'             => '$1 の変更履歴',
'difference'                => '版間での差分',
'loadingrev'                => '差分をとるために古い版を読み込んでいます',
'lineno'                    => '$1 行',
'editcurrent'               => 'このページの最新版を編集',
'selectnewerversionfordiff' => '比較する新しい版を選択',
'selectolderversionfordiff' => '比較する古い版を選択',
'compareselectedversions'   => '選択した版同士を比較',
'editundo'                  => '取り消し',
'diff-multi'                => '（間の $1 版分が非表示です）',

# Search results
'searchresults'         => '検索結果',
'searchresulttext'      => '{{SITENAME}}の検索に関する詳しい情報は、[[{{MediaWiki:helppage}}|{{int:help}}]]をご覧ください。',
'searchsubtitle'        => '問い合わせ："[[:$1]]" （[[Special:Prefixindex/$1|全ページ]]/[[Special:Whatlinkshere/$1|リンク元]]）',
'searchsubtitleinvalid' => '問い合わせ: "$1"',
'noexactmatch'          => '"$1" という名称のページは存在しませんでした。[[:$1|新規作成する]]。',
'titlematches'          => '項目名と一致',
'notitlematches'        => '項目名とは一致しませんでした',
'textmatches'           => 'ページ内本文と一致',
'notextmatches'         => 'ページ内本文とは一致しませんでした',
'prevn'                 => '前 $1',
'nextn'                 => '次 $1',
'viewprevnext'          => '（$1）（$2）（$3）を見る',
'showingresults'        => '<b>$2</b> 件目から <b>$1</b> 件を表示しています。',
'showingresultsnum'     => '<b>$2</b> 件目から <b>$3</b> 件を表示しています。',
'nonefound'             => "'''※'''検索がうまくいかないのは、「ある」や「から」のような一般的な語で索引付けがされていないか、複数の検索語を指定している（全ての検索語を含むページだけが結果に示されます）などのためかもしれません。",
'powersearch'           => '検索',
'powersearchtext'       => '検索する名前空間 :<br />
$1<br />
$2リダイレクトを含める &nbsp; &nbsp; &nbsp; $3 $9',
'searchdisabled'        => '<p>全文検索はサーバー負荷の都合から、一時的に使用停止しています。元に戻るまでGoogleでの全文検索を利用してください。検索結果は少し古い内容となります。</p>',

# Preferences page
'preferences'              => 'オプション',
'mypreferences'            => 'オプション',
'prefs-edits'              => '編集回数:',
'prefsnologin'             => 'ログインしていません',
'prefsnologintext'         => 'オプションを変更するためには、[[Special:Userlogin|ログイン]]する必要があります。',
'prefsreset'               => 'オプションは初期化されました。',
'qbsettings'               => 'クイックバー設定',
'qbsettings-none'          => 'なし',
'qbsettings-fixedleft'     => '左端',
'qbsettings-fixedright'    => '右端',
'qbsettings-floatingleft'  => 'ウィンドウの左上に固定',
'qbsettings-floatingright' => 'ウィンドウの右上に固定',
'changepassword'           => 'パスワード変更',
'skin'                     => '外装',
'math'                     => '数式',
'dateformat'               => '日付の書式',
'datedefault'              => '選択なし',
'datetime'                 => '日付と時刻',
'math_failure'             => '構文解析失敗',
'math_unknown_error'       => '不明なエラー',
'math_unknown_function'    => '不明な関数',
'math_lexing_error'        => '字句解析エラー',
'math_syntax_error'        => '構文エラー',
'math_image_error'         => 'PNGへの変換に失敗しました。latex, dvips, gs, convertが正しくインストールされているか確認してください。',
'math_bad_tmpdir'          => 'TeX一時ディレクトリを作成または書き込みできません',
'math_bad_output'          => 'TeX出力用ディレクトリを作成または書き込みできません',
'math_notexvc'             => 'texvcプログラムが見つかりません。math/READMEを読んで正しく設定してください。',
'prefs-personal'           => '利用者情報',
'prefs-rc'                 => '最近更新したページ',
'prefs-watchlist'          => 'ウォッチリスト',
'prefs-watchlist-days'     => 'ウォッチリストに表示する日数:',
'prefs-watchlist-edits'    => '拡張したウォッチリストに表示する件数:',
'prefs-misc'               => 'その他',
'saveprefs'                => '設定の保存',
'resetprefs'               => '設定の初期化',
'oldpassword'              => '古いパスワード:',
'newpassword'              => '新しいパスワード:',
'retypenew'                => '新しいパスワードを再入力:',
'textboxsize'              => '編集画面',
'rows'                     => '縦:',
'columns'                  => '横:',
'searchresultshead'        => '検索',
'resultsperpage'           => '1ページあたりの表示件数:',
'contextlines'             => '1件あたりの行数:',
'contextchars'             => '1行あたりの文字数:',
'stub-threshold'           => '<a href="#" class="stub">スタブリンク</a>として表示するしきい値:',
'recentchangesdays'        => '最近更新したページの表示日数:',
'recentchangescount'       => '最近更新したページの表示件数:',
'savedprefs'               => 'オプションを保存しました',
'timezonelegend'           => 'タイムゾーン',
'timezonetext'             => 'UTCとあなたの地域の標準時間との差を入力してください（日本国内は9:00）。',
'localtime'                => 'あなたの現在時刻',
'timezoneoffset'           => '時差¹',
'servertime'               => 'サーバーの現在時刻',
'guesstimezone'            => '自動設定',
'allowemail'               => '他の利用者からのメールの受け取りを許可する',
'defaultns'                => '標準で検索する名前空間:',
'default'                  => 'デフォルト',
'files'                    => '画像等',

# User rights
'userrights-lookup-user'      => '利用者の所属グループの管理',
'userrights-user-editname'    => '利用者名:',
'editusergroup'               => '編集',
'userrights-editusergroup'    => '利用者の所属グループ',
'saveusergroups'              => '利用者の所属グループを保存',
'userrights-groupsmember'     => '所属グループ:',
'userrights-groupsavailable'  => '有効なグループ:',
'userrights-groupshelp'       => 'この利用者から削除したい、またはこの利用者に追加したいグループを選択してください。選択されていないグループは変更されません。選択を解除するには [CTRL]+[左クリック] です。',
'userrights-reason'           => '変更理由:',
'userrights-available-none'   => '利用者の所属グループを変更することは出来ません。',
'userrights-available-add'    => '利用者をグループ $1に追加できます。',
'userrights-available-remove' => '利用者をグループ $1から削除できます。',

# Groups
'group'            => 'グループ:',
'group-bot'        => 'ボット',
'group-sysop'      => '管理者',
'group-bureaucrat' => 'ビューロクラット',
'group-all'        => '（すべて）',

'group-bot-member'        => '{{int:group-bot}}',
'group-sysop-member'      => '{{int:group-sysop}}',
'group-bureaucrat-member' => '{{int:group-bureaucrat}}',

'grouppage-bot'        => 'Project:{{int:group-bot}}',
'grouppage-sysop'      => 'Project:{{int:group-sysop}}',
'grouppage-bureaucrat' => 'Project:{{int:group-bureaucrat}}',

# User rights log
'rightslog'      => '利用者権限変更記録',
'rightslogtext'  => '以下は利用者の権限変更の一覧です。',
'rightslogentry' => '$1 の権限を $2 から $3 へ変更しました。',
'rightsnone'     => '（権限なし）',

# Recent changes
'nchanges'                          => '$1 回の更新',
'recentchanges'                     => '最近更新したページ',
'recentchangestext'                 => '最近の更新はこのページから確認できます。',
'recentchanges-feed-description'    => '最近付け加えられた変更はこのフィードで確認できます。',
'rcnote'                            => '以下は $3 までの <strong>$2</strong> 日間に編集された <strong>$1</strong> ページです（<strong>{{int:newpageletter}}</strong>=新規項目、<strong>{{int:minoreditletter}}</strong>=細部の編集、<strong>{{int:boteditletter}}</strong>=ボットの編集、日時はオプションで未設定ならUTC）',
'rcnotefrom'                        => '以下は <b>$2</b> までの更新です。（最大 <b>$1</b> 件）',
'rclistfrom'                        => '$1以後現在までの更新を表示',
'rcshowhideminor'                   => '細部の編集を$1',
'rcshowhidebots'                    => 'ボットの編集を$1',
'rcshowhideliu'                     => '登録利用者の編集を$1',
'rcshowhideanons'                   => '匿名利用者の編集を$1',
'rcshowhidepatr'                    => 'パトロールされた編集を$1',
'rcshowhidemine'                    => '自分の編集を$1',
'rclinks'                           => '最近 $2 日間の $1 件分を表示する<br />$3',
'diff'                              => '差分',
'hist'                              => '履歴',
'hide'                              => '隠す',
'show'                              => '表示',
'minoreditletter'                   => 'M',
'number_of_watching_users_pageview' => '[$1人がウォッチしています]',
'rc_categories'                     => 'カテゴリを制限（"|" で区切る）',
'rc_categories_any'                 => 'すべて',
'newsectionsummary'                 => '/* $1 */ 新しい節',

# Recent changes linked
'recentchangeslinked'          => 'リンク先の更新状況',
'recentchangeslinked-title'    => '$1 からリンクされているページの更新状況',
'recentchangeslinked-noresult' => '指定期間中に指定ページのリンク先に更新はありませんでした。',
'recentchangeslinked-summary'  => "この特別ページはリンク先の更新状況です。あなたのウォッチリストにあるページは'''太字'''で表示されます。",

# Upload
'upload'                      => 'アップロード',
'uploadbtn'                   => 'アップロード',
'reupload'                    => '再アップロード',
'reuploaddesc'                => 'アップロードのフォームへ戻る',
'uploadnologin'               => 'ログインしていません',
'uploadnologintext'           => 'ファイルをアップロードするには[[Special:Userlogin|ログイン]]する必要があります。',
'upload_directory_read_only'  => 'アップロード先のディレクトリ ($1) にウェブサーバーが書き込めません。',
'uploaderror'                 => 'アップロード エラー',
'uploadtext'                  => "ファイルを新しくアップロードする場合には、以下のフォームを利用してください。
* 過去にアップロードされた画像は[[Special:Imagelist|{{int:imagelist}}]]で閲覧したり探したりできます。
* アップロードや削除は[[Special:Log|ログ]]に記録されます。
* 「{{int:uploadbtn}}」ボタンを押すと、アップロードが完了します。
ページに画像を挿入するには
* '''<nowiki>[[</nowiki>{{ns:image}}:File.jpg<nowiki>]]</nowiki>'''
* '''<nowiki>[[</nowiki>{{ns:image}}:File.png|thumb|代替テキスト<nowiki>]]</nowiki>'''
といった書式を使います。<br />画像ページではなくファイルに直接リンクするには
* '''<nowiki>[[</nowiki>{{ns:media}}:File.ogg<nowiki>]]</nowiki>'''
とします。",
'uploadlog'                   => 'アップロードログ',
'uploadlogpage'               => 'アップロード記録',
'uploadlogpagetext'           => '以下は最近のファイルのアップロードのログです。',
'filename'                    => 'ファイル名',
'filedesc'                    => 'ファイルの概要',
'fileuploadsummary'           => 'ファイルの概要:',
'filestatus'                  => '著作権情報',
'filesource'                  => 'ファイルの出典',
'uploadedfiles'               => 'アップロードされたファイル',
'ignorewarning'               => '警告を無視し、保存してしまう',
'ignorewarnings'              => '警告を無視',
'minlength1'                  => 'ファイル名は1文字以上である必要があります。',
'illegalfilename'             => 'ファイル名 "$1" にページ・タイトルとして使えない文字が含まれています。ファイル名を変更してからもう一度アップロードしてください。',
'badfilename'                 => 'ファイル名は "$1" へ変更されました。',
'filetype-badmime'            => 'MIME タイプ "$1" のファイルのアップロードは許可されていません。',
'filetype-badtype'            => "'''\".\$1\"''' は非推奨の拡張子です。
: 推奨されている拡張子の一覧: \$2",
'filetype-missing'            => 'ファイルに拡張子 (".jpg" など）がありません。',
'large-file'                  => 'ファイルサイズは $1 バイト以下に抑えることが推奨されています。このファイルは $2 バイトです。',
'largefileserver'             => 'ファイルが大きすぎます。サーバー設定で許されている最大値を超過しました。',
'emptyfile'                   => 'あなたがアップロードしようとしているファイルは内容が空であるか、もしくはファイル名の指定が間違っています。もう一度、ファイル名が正しいか、あるいはアップロードしようとしたファイルであるかどうかを確認してください。',
'fileexists'                  => 'この名前のファイルは既に存在しています。$1と置き換えるかどうかお確かめください。',
'fileexists-extension'        => '類似した名前のファイルが既に存在しています:<br />
アップロード中のファイル: <strong><tt>$1</tt></strong><br />
既存のファイル: <strong><tt>$2</tt></strong><br />
ファイルが本当に違うものであるか、確認してください。',
'fileexists-thumb'            => "<center>'''既存の画像'''</center>",
'fileexists-thumbnail-yes'    => 'このファイルは既存の画像のサイズ縮小版（サムネール）である可能性があります。以下のファイルを確認してください: <strong><tt>$1</tt></strong><br />
確認した画像がオリジナルサイズにおける元画像である場合、追加でサムネールを登録する必要はありません。',
'file-thumbnail-no'           => 'ファイル名が <strong><tt>$1</tt></strong> から始まっています。画像が縮小版（サムネール）である場合があります。
より高精細な画像をお持ちの場合は、フルサイズ版をアップロードしてください。そうでない場合はファイル名を変更してください。',
'fileexists-forbidden'        => 'この名前のファイルは既に存在しています。前のページに戻り、別のファイル名でアップロードし直してください。
[[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'この名前のファイルは共有リポジトリに既に存在しています。前のページに戻り、別のファイル名でアップロードし直してください。
[[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'アップロード成功',
'uploadwarning'               => 'アップロード 警告',
'savefile'                    => 'ファイルを保存',
'uploadedimage'               => '"$1" をアップロードしました。',
'overwroteimage'              => '"[[$1]]"の新しい版をアップロードしました',
'uploaddisabled'              => '申し訳ありませんが、アップロードは現在使用できません。',
'uploaddisabledtext'          => 'このウィキではファイルのアップロードは禁止されています。',
'uploadscripted'              => 'このファイルはウェブブラウザが誤って解釈してしまうおそれのあるHTMLまたはスクリプトコードを含んでいます。',
'uploadcorrupt'               => '指定したファイルは壊れているか拡張子が正しくありません。ファイルを確認の上再度アップロードをしてください。',
'uploadvirus'                 => 'このファイルにはウイルスが含まれています!! &nbsp;詳細: $1',
'sourcefilename'              => 'ファイル名',
'destfilename'                => '掲載するファイル名',
'watchthisupload'             => '画像をウォッチ',
'filewasdeleted'              => 'この名前のファイルは一度アップロードされその後削除されています。アップロードの前に$1を確認してみてください。',
'upload-wasdeleted'           => "'''警告:あなたは過去に削除されたファイルをアップロードしようとしています。'''

このままアップロードを行うことが適切かどうか確認してください。参考として以下にこのファイルの削除記録を表示しています:",
'filename-bad-prefix'         => 'アップロードしようとしている <strong>"$1"</strong> のファイル名が、デジタルカメラによって自動的に付与されるような名称となっています。どのようなファイルであるのか、ファイル名を見ただけでも分かるような名称にしてください。',

'upload-proto-error'      => '不正なプロトコル',
'upload-proto-error-text' => 'アップロード元のURLは <code>http://</code> か <code>ftp://</code> で始まっている必要があります。',
'upload-file-error'       => '内部エラー',
'upload-file-error-text'  => 'サーバーの内部エラーのため、一時ファイルの作成に失敗しました。システムの管理者に連絡してください。',
'upload-misc-error'       => '不明なエラー',
'upload-misc-error-text'  => 'アップロード時に不明なエラーが検出されました。指定したURLがアクセス可能で有効なものであるかを再度確認してください。それでもこのエラーが発生する場合は、システムの管理者に連絡してください。',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URLに到達不能',
'upload-curl-error6-text'  => '指定したURLに到達できませんでした。URLが正しいものであるか、指定したサイトが現在使用可能かを再度確認してください。',
'upload-curl-error28'      => 'タイムアウト',
'upload-curl-error28-text' => '相手サイトからの応答がありませんでした。指定したサイトが現在使用可能かを確認した上で、しばらく待ってもう一度お試しください。また、インターネットが混雑していない時間帯に実行することを推奨します。',

'license'            => 'ライセンス',
'nolicense'          => 'ライセンス情報を選択してください:',
'license-nopreview'  => '（プレビューはありません）',
'upload_source_url'  => '（インターネット上のURL）',
'upload_source_file' => '（あなたのコンピューター上のファイル）',

# Image list
'imagelist'                 => '画像リスト',
'imagelisttext'             => '$1 枚の画像を $2 に表示しています',
'getimagelist'              => '画像リストを取得',
'ilsubmit'                  => '検索',
'showlast'                  => '$2に $1 枚の画像を表示',
'byname'                    => '名前順',
'bydate'                    => '日付順',
'bysize'                    => 'サイズ順',
'imgdelete'                 => '削除',
'imgdesc'                   => '詳細',
'imgfile'                   => 'ファイル',
'filehist'                  => 'ファイルの履歴',
'filehist-help'             => '過去の版のファイルを参照するには、日付/時刻の列にあるリンクをクリックしてください。',
'filehist-deleteall'        => '全て削除',
'filehist-deleteone'        => '削除する',
'filehist-revert'           => '差し戻す',
'filehist-current'          => '現在の版',
'filehist-datetime'         => '日付/時刻',
'filehist-user'             => '利用者',
'filehist-dimensions'       => '解像度',
'filehist-filesize'         => 'ファイルサイズ',
'filehist-comment'          => 'コメント',
'imagelinks'                => 'リンク',
'linkstoimage'              => 'この画像にリンクしているページの一覧:',
'nolinkstoimage'            => 'この画像にリンクしているページはありません。',
'sharedupload'              => 'このファイルは共有されており、他のプロジェクトで使用されている可能性があります。',
'shareduploadwiki'          => '詳しい情報は$1を参照してください。',
'shareduploadwiki-linktext' => 'ファイルの詳細ページ',
'noimage'                   => 'このファイル名の画像はありません。$1。',
'noimage-linktext'          => 'このファイル名でアップロードする',
'uploadnewversion-linktext' => 'このファイルの新しいバージョンをアップロードする',
'imagelist_date'            => '日時',
'imagelist_name'            => '名前',
'imagelist_user'            => '利用者',
'imagelist_size'            => 'サイズ（バイト）',
'imagelist_description'     => '概要',
'imagelist_search_for'      => '画像名で検索:',

# File reversion
'filerevert'                => '$1 を差し戻す',
'filerevert-legend'         => 'ファイルを差し戻す',
'filerevert-comment'        => 'コメント:',
'filerevert-defaultcomment' => '$1 $2 の版へ差し戻し',
'filerevert-submit'         => '差し戻す',

# File deletion
'filedelete'             => '$1の削除',
'filedelete-legend'      => 'ファイルの削除',
'filedelete-intro'       => "あなたは'''[[Media:$1|$1]]'''を削除しようとしています。",
'filedelete-intro-old'   => '<span class="plainlinks">あなたは\'\'\'[[Media:$1|$1]]\'\'\'の[$4 $3, $2]の版を削除しようとしています。</span>',
'filedelete-comment'     => 'コメント:',
'filedelete-submit'      => '削除する',
'filedelete-success'     => "'''$1''' は削除されました。",
'filedelete-success-old' => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' の $2 $3 の版は削除されています。</span>',
'filedelete-nofile'      => "'''$1''' はこのサイト上には存在しません。",
'filedelete-nofile-old'  => "指定された属性を持つ'''$1'''の古い版は存在しません。",
'filedelete-iscurrent'   => 'このファイルの最新版を削除しようとしています。直前の版に差し戻してください。',

# MIME search
'mimesearch'         => 'MIMEタイプ検索',
'mimesearch-summary' => '指定したMIMEタイプに合致するファイルを検索します。contenttype/subtype の形式で指定してください（例: <tt>image/jpeg</tt>）。',
'mimetype'           => 'MIMEタイプ:',
'download'           => 'ダウンロード',

# Unwatched pages
'unwatchedpages' => 'ウォッチされていないページ',

# List redirects
'listredirects' => 'リダイレクトの一覧',

# Unused templates
'unusedtemplates'     => '使われていないテンプレート',
'unusedtemplatestext' => 'このページでは {{ns:template}} 名前空間にあって他のページから使用されていないものを一覧にしています。削除する前にリンク元を確認してください。',
'unusedtemplateswlh'  => 'リンク元',

# Random page
'randompage'         => 'おまかせ表示',
'randompage-nopages' => 'この名前空間にはページはありません。',

# Random redirect
'randomredirect'         => 'おまかせリダイレクト',
'randomredirect-nopages' => 'この名前空間にはリダイレクトはありません。',

# Statistics
'statistics'             => 'アクセス統計',
'sitestats'              => 'サイト全体の統計',
'userstats'              => '利用者登録統計',
'sitestatstext'          => "データベース内には'''$1'''ページのデータがあります。この数字には「ノートページ」や「{{SITENAME}}関連のページ」、「書きかけのページ」、「リダイレクト」など、記事とはみなせないページが含まれています。これらを除いた、記事とみなされるページ数は約'''$2'''ページになります。

'''$8'''個のファイルがアップロードされました。

ページの総閲覧回数は'''$3'''回です。また、'''$4'''回の編集が行われました。平均すると、1ページあたり'''$5'''回の編集が行われ、1編集あたり'''$6'''回閲覧されています。

[http://meta.wikimedia.org/wiki/Help:Job_queue job queue] の長さは '''$7''' です。",
'userstatstext'          => "登録済みの利用者は'''$1'''人で、内'''$2'''人 ('''$4%''') が$5権限を持っています。($3を参照)",
'statistics-mostpopular' => '最も閲覧されているページ',

'disambiguations'      => '曖昧さ回避ページ',
'disambiguationspage'  => 'Template:Aimai',
'disambiguations-text' => "以下のページは'''曖昧さ回避ページ'''へリンクしています。これらのページはより適した主題のページへリンクされるべきです。<br />
[[MediaWiki:disambiguationspage]] からリンクされたテンプレートを使用しているページは曖昧さ回避ページと見なされます。",

'doubleredirects'     => '二重リダイレクト',
'doubleredirectstext' => '各列は最初及び2つ目のリダイレクトへのリンクが記されています。2つ目のそれ同様、最初のものを本来のページへリダイレクトしなおしてください。',

'brokenredirects'        => '迷子のリダイレクト',
'brokenredirectstext'    => '以下は存在しないページにリンクしているリダイレクトです。',
'brokenredirects-edit'   => '(編集)',
'brokenredirects-delete' => '(削除)',

'withoutinterwiki'        => '言語間リンクを持たないページ',
'withoutinterwiki-header' => '以下のページには多言語版へのリンクがありません:',

'fewestrevisions' => '編集履歴の少ないページ',

# Miscellaneous special pages
'nbytes'                  => '$1 バイト',
'ncategories'             => '$1 のカテゴリ',
'nlinks'                  => '$1 個のリンク',
'nmembers'                => '$1 項目',
'nrevisions'              => '$1 の版',
'nviews'                  => '$1 回表示',
'specialpage-empty'       => '合致するものがありません。',
'lonelypages'             => '孤立しているページ',
'lonelypagestext'         => '以下のページは、どこからもリンクされていない孤立したページです。',
'uncategorizedpages'      => 'カテゴリ未導入のページ',
'uncategorizedcategories' => 'カテゴリ未導入のカテゴリ',
'uncategorizedimages'     => 'カテゴリ未導入の画像',
'uncategorizedtemplates'  => 'カテゴリ未導入のテンプレート',
'unusedcategories'        => '使われていないカテゴリ',
'unusedimages'            => '使われていない画像',
'popularpages'            => '人気のページ',
'wantedcategories'        => '作成が望まれているカテゴリ',
'wantedpages'             => '投稿が望まれているページ',
'mostlinked'              => '被リンクの多いページ',
'mostlinkedcategories'    => '項目の多いカテゴリ',
'mostlinkedtemplates'     => '使用箇所の多いテンプレート',
'mostcategories'          => 'カテゴリの多い項目',
'mostimages'              => 'リンクの多い画像',
'mostrevisions'           => '版の多い項目',
'allpages'                => '全ページ',
'prefixindex'             => '全ページ (ページ指定)',
'shortpages'              => '短いページ',
'longpages'               => '長いページ',
'deadendpages'            => '有効なページへのリンクがないページ',
'deadendpagestext'        => '以下のページは、このウィキの他のページにリンクしていないページです。',
'protectedpages'          => '保護されているページ',
'protectedpagestext'      => '以下のページは移動や編集が禁止されています。',
'protectedpagesempty'     => '現在保護中のページがありません。',
'listusers'               => '登録利用者の一覧',
'specialpages'            => '特別ページ',
'spheading'               => '特別ページ',
'restrictedpheading'      => '制限のある特別ページ',
'rclsub'                  => '"$1" からリンクされているページ',
'newpages'                => '新しいページ',
'newpages-username'       => '利用者名:',
'ancientpages'            => '更新されていないページ',
'intl'                    => '言語間リンク',
'move'                    => '移動',
'movethispage'            => 'このページを移動',
'unusedimagestext'        => '<p>他のウェブサイトがURLを直接用いて画像にリンクしている場合もあります。以下の画像一覧には、そのような形で利用されている画像が含まれている可能性があります。</p>',
'unusedcategoriestext'    => '以下のカテゴリページはどの項目・カテゴリからも使われていません。',
'notargettitle'           => '対象となるページが存在しません',
'notargettext'            => '対象となるページ又は利用者が指定されていません',

# Book sources
'booksources'               => '文献資料',
'booksources-search-legend' => '文献資料を検索',
'booksources-go'            => '検索',
'booksources-text'          => '以下のリストは、新本、古本などを販売している外部サイトへのリンクです。あなたがお探しの本について、更に詳しい情報が提供されている場合もあります。',

'categoriespagetext' => '{{SITENAME}}には以下のカテゴリが存在します。',
'data'               => 'データ',
'userrights'         => '利用者権限の管理',
'groups'             => 'ユーザーグループ',
'alphaindexline'     => '$1―$2',
'version'            => 'バージョン情報',

# Special:Log
'specialloguserlabel'  => '利用者名:',
'speciallogtitlelabel' => 'タイトル:',
'log'                  => 'ログ',
'all-logs-page'        => '全てのログ',
'log-search-legend'    => 'ログの検索',
'log-search-submit'    => '検索',
'alllogstext'          => 'アップロード、削除、保護、投稿ブロック、権限変更のログがまとめて表示されています。ログの種類、実行した利用者、影響を受けたページ（利用者）による絞り込みができます。',
'logempty'             => '条件にマッチする記録はありません。',
'log-title-wildcard'   => 'この文字列で始まるタイトルを検索する',

# Special:Allpages
'nextpage'          => '次のページ（$1）',
'prevpage'          => '前のページ（$1）',
'allpagesfrom'      => '表示開始ページ:',
'allarticles'       => '全ページ',
'allinnamespace'    => '全ページ ($1 名前空間)',
'allnotinnamespace' => '全ページ ($1 名前空間を除く)',
'allpagesprev'      => '前へ',
'allpagesnext'      => '次へ',
'allpagessubmit'    => '表示',
'allpagesprefix'    => '次の文字列から始まるページを表示:',
'allpagesbadtitle'  => '指定したタイトルは無効か、正しくない inter-language または inter-wiki のタイトルです。ページタイトルに使用できない文字が含まれている可能性があります。',
'allpages-bad-ns'   => '{{SITENAME}}に "$1" という名前空間はありません。',

# Special:Listusers
'listusersfrom'      => 'この文字から表示:',
'listusers-submit'   => '表示',
'listusers-noresult' => '利用者が見つかりませんでした。大文字・小文字の区別を確認してください。',

# E-mail user
'mailnologin'     => 'メールアドレスの記載がありません。',
'mailnologintext' => '他の利用者宛てにメールを送信するためには、[[Special:Userlogin|ログイン]]し、あなたのメールアドレスを[[Special:Preferences|オプション]]に設定する必要があります。',
'emailuser'       => 'この利用者にメールを送信',
'emailpage'       => 'メール送信ページ',
'emailpagetext'   => 'メールを送る先の利用者が有効なメールアドレスを{{int:preferences}}で登録していれば、下のフォームを通じてメールを送ることができます。
あなたが登録したご自分のメールアドレスはFrom:の欄に自動的に組み込まれ、受け取った相手が返事を出せるようになっています。',
'usermailererror' => 'メール送信時に以下のエラーが発生しました:',
'defemailsubject' => '{{SITENAME}} (ja) e-mail',
'noemailtitle'    => '送り先のメールアドレスがありません。',
'noemailtext'     => 'この利用者は有効なメールアドレスを登録していないか、メールを受け取りたくないというオプションを選択しています。',
'emailfrom'       => 'あなたのアドレス',
'emailto'         => 'あて先',
'emailsubject'    => '題名',
'emailmessage'    => '本文',
'emailsend'       => 'メール送信',
'emailccme'       => '自分宛に控えを送信する',
'emailccsubject'  => '$1宛てウィキメールの控え: $2',
'emailsent'       => 'メールを送りました',
'emailsenttext'   => 'メールは無事送信されました。',

# Watchlist
'watchlist'            => 'ウォッチリスト',
'mywatchlist'          => 'ウォッチリスト',
'watchlistfor'         => "'''$1'''",
'nowatchlist'          => 'あなたのウォッチリストは空です。',
'watchlistanontext'    => 'ウォッチリストを確認あるいは編集するには $1 してください。',
'watchnologin'         => 'ログインしていません',
'watchnologintext'     => 'ウォッチリストを変更するためには、[[Special:Userlogin|ログイン]]している必要があります。',
'addedwatch'           => 'ウォッチリストに追加しました',
'addedwatchtext'       => "ページ \"\$1\" をあなたの[[Special:Watchlist|ウォッチリスト]]に追加しました。

このページと、付属のノートのページに変更があった際にはそれをウォッチリストで知ることができます。また、[[Special:Recentchanges|最近更新したページ]]ではウォッチリストに含まれているページは'''ボールド体'''で表示され、見つけやすくなります。

ウォッチリストから特定のページを削除したい場合には、サイドバーかタブにある \"{{int:unwatch}}\" のリンクをクリックしてください。",
'removedwatch'         => 'ウォッチリストから削除しました',
'removedwatchtext'     => 'ページ "$1" をウォッチリストから削除しました。',
'watch'                => 'ウォッチリストに追加',
'watchthispage'        => 'ウォッチリストに追加',
'unwatch'              => 'ウォッチリストから削除',
'unwatchthispage'      => 'ウォッチリストから削除',
'notanarticle'         => 'これは記事ではありません。',
'watchnochange'        => 'その期間内にウォッチリストにあるページはどれも編集されていません。',
'watchlist-details'    => '* ウォッチリストに入っているページ数（ノート除く）: $1.',
'wlheader-enotif'      => '* メール通知が有効になっています',
'wlheader-showupdated' => "* あなたが最後に訪問したあとに変更されたページは'''ボールド体'''で表示されます",
'watchmethod-recent'   => 'ウォッチリストの中から最近編集されたものを抽出',
'watchmethod-list'     => '最近編集された中からウォッチしているページを抽出',
'watchlistcontains'    => 'あなたのウォッチリストには $1 ページ登録されています。',
'iteminvalidname'      => '"$1" をウォッチリストから削除できません。ページ名が不正です。',
'wlnote'               => '以下は最近 <strong>$2</strong> 時間に編集された <strong>$1</strong> ページです。',
'wlshowlast'           => '最近の [$1時間] [$2日間] [$3] のものを表示する',
'watchlist-show-bots'  => 'ボットの編集を表示',
'watchlist-hide-bots'  => 'ボットの編集を隠す',
'watchlist-show-own'   => '自分の編集を表示',
'watchlist-hide-own'   => '自分の編集を隠す',
'watchlist-show-minor' => '細部の編集を表示',
'watchlist-hide-minor' => '細部の編集を隠す',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'ウォッチリストに追加しています...',
'unwatching' => 'ウォッチリストから削除しています...',

'enotif_mailer'                => '{{SITENAME}} 通知メール',
'enotif_reset'                 => 'すべてのページを訪問済みにする',
'enotif_newpagetext'           => '(新規ページ)',
'enotif_impersonal_salutation' => '{{SITENAME}} 利用者',
'changed'                      => '変更',
'created'                      => '作成',
'enotif_subject'               => '{{SITENAME}} のページ "$PAGETITLE" が $PAGEEDITOR によって$CHANGEDORCREATEDされました',
'enotif_lastvisited'           => '
あなたが最後に閲覧してからの差分を見るには以下のURLにアクセスしてください:
$1',
'enotif_lastdiff'              => '
変更内容を見るには以下のURLにアクセスしてください:
$1',
'enotif_anon_editor'           => '匿名利用者 $1',
'enotif_body'                  => 'Dear $WATCHINGUSERNAME,

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
{{fullurl:Special:Watchlist/edit}}

助けが必要ですか:
{{fullurl:Help:Contents}}',

# Delete/protect/revert
'deletepage'                  => 'ページを削除',
'confirm'                     => '確認',
'excontent'                   => "内容: '$1'",
'excontentauthor'             => "内容: '$1' (投稿者 [[Special:Contributions/$2|$2]] のみ)",
'exbeforeblank'               => "白紙化前の内容: '$1'",
'exblank'                     => '白紙ページ',
'confirmdelete'               => '削除の確認',
'deletesub'                   => '"$1" を削除',
'historywarning'              => '警告: 削除しようとしているページには履歴があります:',
'confirmdeletetext'           => '指定されたページまたは画像は、その変更履歴と共にデータベースから永久に削除されようとしています。あなたが削除を望んでおり、それがもたらす帰結を理解しており、かつあなたのしようとしていることが[[{{int:policy-url}}|方針]]に即したものであることを確認してください。',
'actioncomplete'              => '完了しました',
'deletedtext'                 => '"$1" は削除されました。最近の削除に関しては $2 を参照してください。',
'deletedarticle'              => '"$1" を削除しました。',
'dellogpage'                  => '削除記録',
'dellogpagetext'              => '以下は最近の削除と復帰の記録です。',
'deletionlog'                 => '削除記録',
'reverted'                    => '以前のバージョンへの差し戻し',
'deletecomment'               => '削除の理由',
'rollback'                    => '編集の差し戻し',
'rollback_short'              => '差し戻し',
'rollbacklink'                => '差し戻し',
'rollbackfailed'              => '差し戻しに失敗しました',
'cantrollback'                => '投稿者がただ一人であるため、編集を差し戻せません。',
'alreadyrolled'               => 'ページ [[:$1]] の [[User:$2|$2]] ([[User_talk:$2|会話]] | [[Special:Contributions/$2|履歴]]) による編集の差し戻しに失敗しました。誰か他の利用者が編集を行ったか差し戻しされたのかもしれません。

このページの最後の編集は [[User:$3|$3]] ([[User_talk:$3|会話]] | [[Special:Contributions/$3|履歴]]) によるものです。',
'editcomment'                 => '編集内容の要約: <i>$1</i>', # only shown if there is an edit comment
'revertpage'                  => '[[Special:Contributions/$2|$2]] ([[User talk:$2|会話]]) による編集を $1 による版へ差し戻し',
'rollback-success'            => '$2 による編集を $1 による版へと差し戻しました。',
'sessionfailure'              => 'あなたのログイン・セッションに問題が発生しました。この動作はセッションハイジャックを防ぐために取り消されました。ブラウザの「戻る」を押してからページを再読込し、もう一度送信してください。',
'protectlogpage'              => '保護記録',
'protectlogtext'              => '以下はページの保護・保護解除の一覧です。',
'protectedarticle'            => '"[[$1]]" を保護しました。',
'modifiedarticleprotection'   => '"[[$1]]" の保護レベルを変更しました。',
'unprotectedarticle'          => '"[[$1]]" の保護を解除しました。',
'protectsub'                  => '"$1" の保護',
'confirmprotect'              => '保護の確認',
'protectcomment'              => '保護・保護解除の理由',
'protectexpiry'               => '期間',
'protect_expiry_invalid'      => '期間の指定が無効です。',
'protect_expiry_old'          => '保護期限が過去の時刻です。',
'unprotectsub'                => '"$1" の保護解除',
'protect-unchain'             => '移動権限を操作',
'protect-text'                => "ページ \"'''\$1'''\" の保護レベルを表示・操作できます。",
'protect-locked-blocked'      => 'あなたはブロックされているため、保護レベルを変更できません。
現在のページ<strong>$1</strong>の状態は以下の通りです:',
'protect-locked-dblock'       => '現在データベースがロックされているため保護レベルを変更できません
現在のページ<strong>$1</strong>の状態は以下の通りです:',
'protect-locked-access'       => 'あなたのアカウントはページの保護レベルを変更する権限を持っていません。
現在のページ<strong>$1</strong>の状態は以下の通りです:',
'protect-cascadeon'           => 'このページはカスケード保護されている以下のページから呼び出されているため、編集できないように保護されています。保護レベルを変更することは可能ですが、このカスケード保護には影響しません。',
'protect-default'             => '（解除）',
'protect-fallback'            => '"$1" 権限が必要です',
'protect-level-autoconfirmed' => '新規利用者と匿名利用者を禁止',
'protect-level-sysop'         => '{{int:group-sysop}}のみ',
'protect-summary-cascade'     => 'カスケード',
'protect-expiring'            => '$1 に解除',
'protect-cascade'             => 'カスケード保護 - このページで取り込んでいる全ての他ページも保護されます。',
'restriction-type'            => '制限:',
'restriction-level'           => '保護レベル:',
'minimum-size'                => '最小サイズ',
'maximum-size'                => '最大サイズ',
'pagesize'                    => '（バイト）',

# Restrictions (nouns)
'restriction-edit' => '編集',
'restriction-move' => '移動',

# Restriction levels
'restriction-level-sysop'         => '全保護',
'restriction-level-autoconfirmed' => '半保護',
'restriction-level-all'           => 'すべて',

# Undelete
'undelete'                     => '削除されたページを参照する',
'undeletepage'                 => '削除された編集の参照と復帰',
'viewdeletedpage'              => '削除されたページの削除記録と履歴',
'undeletepagetext'             => '以下のページは削除されていますが、アーカイブに残っているため、復帰できます。アーカイブは定期的に消去されます。',
'undeleteextrahelp'            => '全ての版を復帰する場合は、全ての版のチェックボックスを選択していない状態で「{{int:undeletebtn}}」ボタンをクリックしてください。
特定の版を復帰する場合は、復帰する版のチェックボックスを選択した状態で「{{int:undeletebtn}}」ボタンをクリックしてください。
「{{int:undeletereset}}」ボタンををクリックするとコメント欄と全てのチェックボックスがクリアされます。',
'undeleterevisions'            => '$1版保管',
'undeletehistory'              => 'ページの復帰を行うと、通常は履歴にある全ての編集が復帰します。特定版の復帰を行う場合は、{{int:undeletebtn}}ボタンを押す前に復帰対象版のチェックボックスを選択してください。',
'undeletehistorynoadmin'       => '過去にこのページの全てもしくは一部が削除されています。以下に示すのは削除記録と削除された版の履歴です。削除された各版の内容は{{int:group-sysop}}のみが閲覧できます。',
'undelete-revision'            => '$1 の削除された版 $2 :',
'undeleterevision-missing'     => '無効、あるいは誤った版です。当該版は既に復帰されたか、アーカイブから削除された可能性があります。',
'undeletebtn'                  => '復帰',
'undeletereset'                => 'リセット',
'undeletecomment'              => 'コメント:',
'undeletedarticle'             => '"$1" を復帰しました。',
'undeletedrevisions'           => '$1 版を復帰しました。',
'undeletedrevisions-files'     => '$1 版のページと $2 ファイルを復帰しました',
'undeletedfiles'               => '$1 ファイルを復帰しました',
'cannotundelete'               => '復帰に失敗しました。誰かがすでにこのページを復帰しています。',
'undeletedpage'                => "<big>'''$1 を復帰しました。'''</big>

最近の削除と復帰については[[Special:Log/delete|削除記録]]を参照してください。",
'undelete-header'              => '最近の削除されたページは[[Special:Log/delete|削除記録]]で確認できます。',
'undelete-search-box'          => '削除されたページを検索',
'undelete-search-prefix'       => '表示するページ名の先頭:',
'undelete-search-submit'       => '検索',
'undelete-no-results'          => '一致する削除済みページのアーカイブが見つかりませんでした。',
'undelete-filename-mismatch'   => '$1 版のファイルを復帰できません: ファイル名が一致しません',
'undelete-bad-store-key'       => '$1 版のファイルを復帰できません: 削除前にファイルが失われています。',
'undelete-cleanup-error'       => '使用されていないログファイル "$1" の削除中にエラーが発生しました。',
'undelete-missing-filearchive' => 'ID $1 の記録がデータベースに存在しないため復帰できません。既に復帰されている可能性があります。',
'undelete-error-short'         => 'ファイル復帰エラー: $1',
'undelete-error-long'          => '$1 の復帰中にエラーが発生しました',

# Namespace form on various pages
'namespace'      => '名前空間:',
'invert'         => '選択した名前空間を隠す',
'blanknamespace' => '（標準）',

# Contributions
'contributions' => '利用者の投稿記録',
'mycontris'     => '自分の投稿記録',
'contribsub2'   => '利用者名: $1 ($2)',
'nocontribs'    => '利用者の投稿記録は見つかりませんでした。',
'ucnote'        => '利用者 <b>$1</b> によるここ <b>$2</b> 日間の編集です。',
'uclinks'       => '過去 $2 日間の $1 編集',
'uctop'         => '（最新）',
'month'         => '月:',
'year'          => '年:',

'sp-contributions-newest'      => '最新',
'sp-contributions-oldest'      => '最古',
'sp-contributions-newer'       => '前 $1',
'sp-contributions-older'       => '次 $1',
'sp-contributions-newbies'     => '新規利用者の投稿のみ表示',
'sp-contributions-newbies-sub' => '新規利用者',
'sp-contributions-blocklog'    => '投稿ブロック記録',
'sp-contributions-search'      => '投稿履歴の検索',
'sp-contributions-username'    => '利用者名または IPアドレス:',
'sp-contributions-submit'      => '検索',

'sp-newimages-showfrom' => '$1 以後現在までの新着画像を表示',

# What links here
'whatlinkshere'       => 'リンク元',
'whatlinkshere-title' => '$1 へリンクしているページ',
'linklistsub'         => 'リンクの一覧',
'linkshere'           => '[[:$1]] は以下のページからリンクされています',
'nolinkshere'         => '[[:$1]] にリンクしているページはありません。',
'nolinkshere-ns'      => "指定された名前空間中で、'''[[:$1]]''' にリンクしているページはありません。",
'isredirect'          => 'リダイレクトページ',
'istemplate'          => 'テンプレート呼出',
'whatlinkshere-prev'  => '前 $1',
'whatlinkshere-next'  => '次 $1',
'whatlinkshere-links' => '← リンク',

# Block/unblock
'blockip'                     => '投稿ブロック',
'blockiptext'                 => '指定した利用者やIPアドレスからの投稿をブロックすることができます。',
'ipaddress'                   => 'IPアドレス',
'ipadressorusername'          => '利用者名 / IPアドレス',
'ipbexpiry'                   => '期間',
'ipbreason'                   => '理由',
'ipbreasonotherlist'          => 'その他',
'ipbreason-dropdown'          => '
*一般
** 虚偽情報の掲載
** ページ内容の除去
** スパム外部リンクの追加
** いたずら
** 嫌がらせ
** 複数アカウントの不正利用
** 不適切な利用者名',
'ipbanononly'                 => '匿名利用者のみブロック',
'ipbcreateaccount'            => 'アカウント作成をブロック',
'ipbemailban'                 => 'メール送信をブロック',
'ipbenableautoblock'          => 'この利用者が最後に使用したIPアドレスを自動的にブロック（ブロック後に使用したIPアドレスも含む）',
'ipbsubmit'                   => '投稿ブロックする',
'ipbother'                    => '期間 (その他のとき)',
'ipboptions'                  => '15分:15 minutes,30分:30 minutes,2時間:2 hours,1日:1 day,3日:3 days,1週間:1 week,2週間:2 weeks,1ヶ月:1 month,3ヶ月:3 months,6ヶ月:6 months,1年:1 year,無期限:infinite',
'ipbotheroption'              => 'その他',
'ipbotherreason'              => '理由（その他/追加）',
'ipbhidename'                 => '利用者名/IPを{{int:blocklogpage}}、{{int:ipblocklist}}、{{int:listusers}}などに載せない',
'badipaddress'                => 'IPアドレスが異常です。',
'blockipsuccesssub'           => 'ブロックに成功しました。',
'blockipsuccesstext'          => '利用者またはIPアドレス [[User:$1|$1]]（[[User talk:$1|会話]]|[[Special:Contributions/$1|履歴]]） の投稿をブロックしました。<br /> [[Special:Ipblocklist|{{int:ipblocklist}}]]で確認できます。',
'ipb-edit-dropdown'           => 'ブロック理由を編集する',
'ipb-unblock-addr'            => '$1 のブロックを解除',
'ipb-unblock'                 => '利用者またはIPアドレスのブロックを解除する',
'ipb-blocklist-addr'          => '$1 の現在有効なブロックを表示',
'ipb-blocklist'               => '現在有効なブロックを表示',
'unblockip'                   => '投稿ブロックを解除する',
'unblockiptext'               => '以下のフォームで利用者またはIPアドレスの投稿ブロックを解除できます。',
'ipusubmit'                   => '投稿ブロックを解除する',
'unblocked'                   => '[[User:$1|$1]] の投稿ブロックを解除しました',
'unblocked-id'                => 'ブロック $1 は解除されました',
'ipblocklist'                 => '投稿ブロック中の利用者やIPアドレス',
'ipblocklist-legend'          => 'ブロック中の利用者を検索',
'ipblocklist-username'        => 'ユーザー名またはIPアドレス:',
'ipblocklist-submit'          => '検索',
'blocklistline'               => '$1, $2 は $3 をブロック （$4）',
'infiniteblock'               => '無期限',
'expiringblock'               => '$1 に解除',
'anononlyblock'               => '匿名のみ',
'noautoblockblock'            => '自動ブロックなし',
'createaccountblock'          => 'アカウント作成のブロック',
'emailblock'                  => 'メール送信のブロック',
'ipblocklist-empty'           => '{{int:ipblocklist}}はありません。',
'ipblocklist-no-results'      => '指定されたIPアドレスまたは利用者名はブロックされていません。',
'blocklink'                   => 'ブロック',
'unblocklink'                 => 'ブロック解除',
'contribslink'                => '投稿記録',
'autoblocker'                 => '投稿ブロックされている利用者 "$1" と同じIPアドレスのため、自動的にブロックされています。ブロックの理由は "$2" です。',
'blocklogpage'                => '投稿ブロック記録',
'blocklogentry'               => '"$1" を $2 ブロックしました $3',
'blocklogtext'                => 'このページは投稿ブロックと解除の記録です。自動的に投稿ブロックされたIPアドレスは記録されていません。現時点で有効な投稿ブロックは[[Special:Ipblocklist|{{int:ipblocklist}}]]をご覧ください。',
'unblocklogentry'             => '"$1" をブロック解除しました',
'block-log-flags-anononly'    => '匿名のみ',
'block-log-flags-nocreate'    => 'アカウント作成のブロック',
'block-log-flags-noautoblock' => '自動ブロック無効',
'block-log-flags-noemail'     => 'メール送信のブロック',
'range_block_disabled'        => '広域ブロックは無効に設定されています。',
'ipb_expiry_invalid'          => '不正な期間です。',
'ipb_already_blocked'         => '"$1" は既にブロックされています。',
'ipb_cant_unblock'            => 'エラー: ブロックされた ID $1 が見つかりません。おそらく既にブロック解除されています。',
'ip_range_invalid'            => '不正なIPアドレス範囲です。',
'proxyblocker'                => 'プロクシブロッカー',
'proxyblockreason'            => 'Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or tech support and inform them of this serious security problem.

:あなたの使用しているIPアドレスはオープン・プロクシであるため投稿ブロックされています。あなたのインターネット・サービス・プロバイダ、もしくは技術担当者に連絡を取り、これが深刻なセキュリティ問題であることを伝えてください。',
'proxyblocksuccess'           => '終了しました。',
'sorbsreason'                 => 'あなたのIPアドレスはオープンプロクシであると、DNSBLに掲載されています。',
'sorbs_create_account_reason' => 'あなたのIPアドレスがオープンプロクシであると DNSBLに掲載されているため、アカウントを作成できません。',

# Developer tools
'lockdb'              => 'データベースのロック',
'unlockdb'            => 'データベースのロック解除',
'lockdbtext'          => 'データベースをロックすると全ての利用者はページを編集できなくなり、オプションを変更できなくなり、ウォッチリストを編集できなくなるなど、データベースに書き込む全ての作業ができなくなります。本当にデータベースをロックして良いかどうか確認してください。メンテナンスが終了したらロックを解除してください。',
'unlockdbtext'        => 'データベースのロックを解除することで利用者はページを編集できるようになり、オプションを変更できるようになり、ウォッチリストを編集できるようになるなど、データベースに書き込む全ての作業ができるようになります。本当にデータベースのロックを解除していいかどうか確認してください。',
'lockconfirm'         => '本当にデータベースをロックする',
'unlockconfirm'       => 'ロックを解除する',
'lockbtn'             => 'ロック',
'unlockbtn'           => 'ロック解除',
'locknoconfirm'       => 'チェックボックスにチェックされていません。',
'lockdbsuccesssub'    => 'データベースはロックされました。',
'unlockdbsuccesssub'  => 'データベースのロックは解除されました',
'lockdbsuccesstext'   => 'データベースをロックしました。メンテナンスが終了したら忘れずにロックを解除してください。',
'unlockdbsuccesstext' => 'データベースのロックは解除されました。',
'lockfilenotwritable' => 'データベースのロックファイルに書き込めません。データベースのロック・解除をするには、サーバー上のロックファイルに書き込める必要があります。',
'databasenotlocked'   => 'データベースはロックされていません。',

# Move page
'movepage'                => 'ページの移動',
'movepagetext'            => '下のフォームを利用すると、ページ名を変更し、その履歴も変更先へ移動することができます。古いページは変更先へのリダイレクトページとなります。ページの中身と変更前のページに張られたリンクは変わりません。ですから、二重になったり壊れてしまったリダイレクトをチェックする必要があります。

移動先がすでに存在する場合には、履歴が移動元ページへのリダイレクトただ一つである場合を除いて移動できません。つまり、間違えてページ名を変更した場合には元に戻せます。

<strong>注意！</strong>
よく閲覧されるページや、他の多くのページからリンクされているページを移動すると予期せぬ結果が起こるかもしれません。ページの移動に伴う影響をよく考えてから踏み切るようにしてください。',
'movepagetalktext'        => '付随するノートのページがある場合には、基本的には、一緒に移動されることになります。

但し、以下の場合については別です。
*名前空間をまたがる移動の場合
*移動先に既に履歴のあるノートページが存在する場合
*下のチェックボックスのチェックマークを消した場合

これらの場合、ノートページを移動する場合には、別に作業する必要があります。',
'movearticle'             => '移動するページ',
'movenologin'             => 'ログインしていません',
'movenologintext'         => 'ページを移動するためには、アカウント作成の上、[[Special:Userlogin|ログイン]]している必要があります。',
'movenotallowed'          => 'このウィキでページを移動する権限がありません。',
'newtitle'                => '新しいページ名',
'move-watch'              => '移動するページをウォッチ',
'movepagebtn'             => 'ページを移動',
'pagemovedsub'            => '無事移動しました。',
'movepage-moved'          => '<big>\'\'\'"$1" は "$2" へ移動されました。\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => '指定された移動先には既にページが存在するか、名前が不適切です。',
'talkexists'              => 'ページ自身は移動されましたが、付随のノートページは移動先のページが存在したため移動できませんでした。手動で内容を統合してください。',
'movedto'                 => '移動先:',
'movetalk'                => 'ノートページが付随する場合には、それも一緒に移動する',
'talkpagemoved'           => '付随のノートのページも移動しました。',
'talkpagenotmoved'        => '付随のノートのページは<strong>移動されませんでした。</strong>',
'1movedto2'               => 'ページ [[$1]] を [[$2]] へ移動',
'1movedto2_redir'         => 'ページ [[$1]] をこのページあてのリダイレクト [[$2]] へ移動',
'movelogpage'             => '移動記録',
'movelogpagetext'         => '以下はページ移動の記録です。',
'movereason'              => '理由',
'revertmove'              => '差し戻し',
'delete_and_move'         => '削除して移動する',
'delete_and_move_text'    => '== 削除が必要です ==
移動先 "[[$1]]" は既に存在しています。このページを移動のために削除しますか?',
'delete_and_move_confirm' => 'ページ削除の確認',
'delete_and_move_reason'  => '移動のための削除',
'selfmove'                => '移動元と移動先のページ名が同じです。自分自身へは移動できません。',
'immobile_namespace'      => '移動先のページ名は特別なページです。その名前空間にページを移動することはできません。',

# Export
'export'            => 'ページデータの書き出し',
'exporttext'        => 'ここでは単独のまたは複数のページのテキストと編集履歴をXMLの形で書き出すことができます。書き出されたXML文書は他のMediaWikiで動いているウィキに取り込んだり、変換したり、個人的な楽しみに使ったりできます。

ページデータを書き出すには下のテキストボックスに書き出したいページのタイトルを一行に一ページずつ記入してください。また編集履歴とともに全ての古い版を含んで書き出すのか、最新版のみを書き出すのか選択してください。

後者のケースではリンクの形で使うこともできます。例: [[メインページ]]の最新版を取得するには[[Special:Export/メインページ]]を使用します。',
'exportcuronly'     => 'すべての履歴を含ませずに、最新版のみを書き出す',
'exportnohistory'   => "'''お知らせ:''' パフォーマンス上の理由により、このフォームによるページの完全な履歴の書き出しは行えません。",
'export-submit'     => '書き出し',
'export-addcattext' => 'カテゴリ内のページを対象に加える。 Category:',
'export-addcat'     => '追加',
'export-download'   => '書き出した結果をファイルに保存する',

# Namespace 8 related
'allmessages'               => '表示メッセージの一覧',
'allmessagesname'           => 'メッセージ名',
'allmessagesdefault'        => '既定の文章',
'allmessagescurrent'        => '現在の文章',
'allmessagestext'           => 'これは{{ns:mediawiki}}名前空間にある全てのシステムメッセージの一覧です。',
'allmessagesnotsupportedDB' => 'wgUseDatabaseMessages が無効のため、[[Special:Allmessages]] はサポートされません。',
'allmessagesfilter'         => 'メッセージ名フィルタ:',
'allmessagesmodified'       => '条件に当てはまるものを表示',

# Thumbnails
'thumbnail-more'           => '拡大',
'missingimage'             => '<b>以下の画像が見つかりません。</b><br /><i>$1</i>',
'filemissing'              => '<i>ファイルがありません</i>',
'thumbnail_error'          => 'サムネイルの作成中にエラーが発生しました: $1',
'djvu_page_error'          => '指定ページ数はDjVuページ範囲を越えています',
'djvu_no_xml'              => 'DjVuファイルのXMLデータを取得できません',
'thumbnail_invalid_params' => 'サムネイルの指定パラメータが不正です',
'thumbnail_dest_directory' => '出力ディレクトリを作成できません',

# Special:Import
'import'                     => 'ページデータの取り込み',
'importinterwiki'            => 'Transwikiインポート',
'import-interwiki-text'      => 'インポートするウィキとページ名を選択してください。変更履歴の日付と編集者が保存されます。すべてのtranswikiは[[Special:Log/import|インポート記録]]に記録されます。',
'import-interwiki-history'   => 'このページの全ての版を複製する',
'import-interwiki-submit'    => '取り込み',
'import-interwiki-namespace' => '次の名前空間に取り込む:',
'importtext'                 => '元となるウィキから {{ns:special}}:Export を使ってXMLファイルを書き出し、ここでアップロードしてください。',
'importstart'                => 'ページを取り込んでいます...',
'import-revision-count'      => '$1 版',
'importnopages'              => 'インポートするページがありません',
'importfailed'               => '取り込みに失敗しました: $1',
'importunknownsource'        => 'インポートするソースのファイルタイプが不明です',
'importcantopen'             => 'インポートするファイルを開けません',
'importbadinterwiki'         => 'interwiki リンクが正しくありません',
'importnotext'               => '内容が空か、テキストがありません。',
'importsuccess'              => '取り込みに成功しました。',
'importhistoryconflict'      => '取り込み時にいくつかの版が競合しました（以前に同じページを取り込んでいませんか）。',
'importnosources'            => 'Transwikiの読み込み元が定義されていないため、履歴の直接アップロードは無効になっています。',
'importnofile'               => 'ファイルがアップロードされませんでした',
'importuploaderror'          => 'ファイルの取り込みに失敗しました。恐らく、許可されている最大ファイルサイズより大きなファイルをアップロードしようとしています。',

# Import log
'importlogpage'                    => 'インポート記録',
'importlogpagetext'                => '以下は管理者による他ウィキからのページデータの取り込み記録です。',
'import-logentry-upload'           => 'ファイルのアップロードにより [[$1]] をインポートしました',
'import-logentry-upload-detail'    => '$1 版',
'import-logentry-interwiki'        => '$1 をtranswikiしました',
'import-logentry-interwiki-detail' => '$2 の $1 版',

# Tooltip help for the actions
'tooltip-pt-userpage'             => '自分の利用者ページ',
'tooltip-pt-anonuserpage'         => 'あなたのIPアドレス用の利用者ページ',
'tooltip-pt-mytalk'               => '自分の会話ページ',
'tooltip-pt-anontalk'             => 'あなたのIPアドレスからなされた編集の会話ページ',
'tooltip-pt-preferences'          => 'オプションの変更',
'tooltip-pt-watchlist'            => '変更を監視しているページの一覧',
'tooltip-pt-mycontris'            => '自分の投稿記録',
'tooltip-pt-login'                => 'ログインすることが推奨されますが、しなくても構いません。',
'tooltip-pt-anonlogin'            => 'ログインすることが推奨されますが、しなくても構いません。',
'tooltip-pt-logout'               => 'ログアウト',
'tooltip-ca-talk'                 => '項目のノート',
'tooltip-ca-edit'                 => 'このページを編集できます。投稿の前に「{{int:showpreview}}」ボタンを使ってください。',
'tooltip-ca-addsection'           => 'このページにコメントを加える',
'tooltip-ca-viewsource'           => 'このページは保護されています。ページのソースを閲覧できます。',
'tooltip-ca-history'              => 'このページの過去の版',
'tooltip-ca-protect'              => 'このページを保護',
'tooltip-ca-delete'               => 'このページを削除',
'tooltip-ca-undelete'             => '削除されたページを復帰する',
'tooltip-ca-move'                 => 'このページを移動',
'tooltip-ca-watch'                => 'このページをウォッチリストに追加',
'tooltip-ca-unwatch'              => 'このページをウォッチリストから外す',
'tooltip-search'                  => 'ウィキ内を検索',
'tooltip-p-logo'                  => 'メインページ',
'tooltip-n-mainpage'              => 'メインページに移動',
'tooltip-n-portal'                => 'このプロジェクトについて、あなたのできることを探す場所です',
'tooltip-n-currentevents'         => '最近の出来事',
'tooltip-n-recentchanges'         => '最近更新が行われたページの一覧',
'tooltip-n-randompage'            => 'ランダムに記事を選んで表示',
'tooltip-n-help'                  => 'ヘルプ・使い方',
'tooltip-n-sitesupport'           => '私たちをサポートしてください',
'tooltip-t-whatlinkshere'         => 'このページにリンクしているページの一覧',
'tooltip-t-recentchangeslinked'   => '最近更新が行われたこのページのリンク先',
'tooltip-feed-rss'                => 'このページのRSSフィード',
'tooltip-feed-atom'               => 'このページのAtomフィード',
'tooltip-t-contributions'         => '利用者の投稿記録',
'tooltip-t-emailuser'             => '{{int:emailuser}}',
'tooltip-t-upload'                => '画像やメディアファイルをアップロード',
'tooltip-t-specialpages'          => '特別ページの一覧',
'tooltip-t-print'                 => 'このページの印刷用バージョン',
'tooltip-t-permalink'             => 'この版への固定リンク',
'tooltip-ca-nstab-main'           => '本文を表示',
'tooltip-ca-nstab-user'           => '利用者ページを表示',
'tooltip-ca-nstab-media'          => 'メディアページを表示',
'tooltip-ca-nstab-special'        => 'これは特別ページです。編集することはできません。',
'tooltip-ca-nstab-project'        => 'プロジェクトページを表示',
'tooltip-ca-nstab-image'          => '画像ページを表示',
'tooltip-ca-nstab-mediawiki'      => 'インターフェースを表示',
'tooltip-ca-nstab-template'       => 'テンプレートを表示',
'tooltip-ca-nstab-help'           => 'ヘルプページを表示',
'tooltip-ca-nstab-category'       => 'カテゴリページを表示',
'tooltip-minoredit'               => 'この編集を細部の変更とマーク',
'tooltip-save'                    => '編集を保存します。',
'tooltip-preview'                 => '編集結果を確認します。保存前に是非使用してください。',
'tooltip-diff'                    => 'あなたが編集した版の変更点を表示します。',
'tooltip-compareselectedversions' => '選択された二つの版の差分を表示します。',
'tooltip-watch'                   => 'このページをウォッチリストへ追加します。',
'tooltip-recreate'                => 'このままこのページを新規作成する',
'tooltip-upload'                  => 'アップロードを開始',

# Stylesheets
'common.css'   => '/* ここに書いた CSS は全ての外装に反映されます */',
'monobook.css' => '/* ここに書いた CSS は Monobook 外装に反映されます */',

# Scripts
'common.js'   => '/* ここに書いた JavaScript は全てのページ上で実行されます */',
'monobook.js' => '/* こちらは廃止されました; [[MediaWiki:Common.js]]をお使いください */',

# Metadata
'nodublincore'      => 'このサーバーでは Dublin Core RDF メタデータが許可されていません。',
'nocreativecommons' => 'このサーバーではクリエイティブ・コモンズの RDF メタデータが許可されていません。',
'notacceptable'     => 'ウィキサーバーはあなたの使用しているクライアントが読める形式で情報を提供できません。',

# Attribution
'anonymous'        => '{{SITENAME}}の匿名利用者',
'siteuser'         => '{{SITENAME}}の利用者$1',
'lastmodifiedatby' => '最終更新は $3 による $2, $1 の編集です。', # $1 date, $2 time, $3 user
'and'              => 'および',
'othercontribs'    => '$1の版に基づきます。',
'others'           => 'その他の利用者',
'siteusers'        => '{{SITENAME}}の利用者$1',
'creditspage'      => 'ページ・クレジット',
'nocredits'        => 'このページには有効なクレジット情報がありません。',

# Spam protection
'spamprotectiontitle'    => 'スパム防御フィルター',
'spamprotectiontext'     => 'あなたが保存しようとしたページはスパム・フィルターによって保存をブロックされました。これは主に外部サイトへのリンクが原因です。',
'spamprotectionmatch'    => '以下はスパム・フィルターによって検出されたテキストです: $1',
'subcategorycount'       => 'このカテゴリには $1 のサブカテゴリがあります。',
'categoryarticlecount'   => 'このカテゴリには $1 のページがあります。',
'category-media-count'   => 'このカテゴリには $1 のファイルがあります。',
'listingcontinuesabbrev' => 'の続き',
'spambot_username'       => 'MediaWiki スパム除去',
'spam_reverting'         => '$1 へのリンクを含まない以前の版に差し戻し',
'spam_blanking'          => 'すべての版から $1 へのリンクを削除',

# Info page
'infosubtitle'   => 'ページ情報',
'numedits'       => '編集数（項目）: $1',
'numtalkedits'   => '編集数（ノート）: $1',
'numwatchers'    => 'ウォッチしている利用者数: $1',
'numauthors'     => '投稿者数（項目）: $1',
'numtalkauthors' => '投稿者数（ノート）: $1',

# Math options
'mw_math_png'    => '常にPNG',
'mw_math_simple' => 'シンプルな数式はHTML、それ以外はPNG',
'mw_math_html'   => 'できる限りHTML、さもなければPNG',
'mw_math_source' => 'TeXのままにする (テキストブラウザ向け)',
'mw_math_modern' => '最近のブラウザで推奨',
'mw_math_mathml' => '可能ならばMathMLを使う (実験中の機能)',

# Patrolling
'markaspatrolleddiff'                 => 'パトロール済みにする',
'markaspatrolledtext'                 => 'この項目をパトロール済みにする',
'markedaspatrolled'                   => 'パトロール済みにしました。',
'markedaspatrolledtext'               => '選択された編集をパトロール済みにしました。',
'rcpatroldisabled'                    => 'RCパトロールが無効です',
'rcpatroldisabledtext'                => '最近更新されたページのパトロール機能は現在無効になっています。',
'markedaspatrollederror'              => 'パトロール済みにできません。',
'markedaspatrollederrortext'          => 'パトロール済みにするためにはどの版かを指定する必要があります。',
'markedaspatrollederror-noautopatrol' => '自分自身による編集をパトロール済みにする権限がありません。',

# Patrol log
'patrol-log-page' => 'パトロール記録',
'patrol-log-line' => '$2 の $1 をパトロール済みにマーク$3',
'patrol-log-auto' => '（自動）',
'patrol-log-diff' => '第$1版',

# Image deletion
'deletedrevision'                 => '古い版 $1 を削除しました',
'filedeleteerror-short'           => 'ファイル削除エラー: $1',
'filedeleteerror-long'            => '$1 の削除中にエラーが発生しました',
'filedelete-missing'              => 'ファイル"$1"は存在しないため、削除することができません。',
'filedelete-old-unregistered'     => '指定されたファイルの "$1" 版はデータベースにありません。',
'filedelete-current-unregistered' => '指定されたファイル"$1"はデータベース内にはありません。',
'filedelete-archive-read-only'    => 'ログ用ディレクトリ "$1" は、ウェブサーバーにより書き込み不可となっています。',

# Browsing diffs
'previousdiff' => '←前の差分',
'nextdiff'     => '次の差分→',

# Media information
'mediawarning'         => "'''警告:''' このファイルは悪意のあるコードを含んでいる可能性があり、実行するとコンピューターが危害を被る場合があります。
----",
'imagemaxsize'         => '画像ページで表示する画像の最大サイズ:',
'thumbsize'            => 'サムネイルの大きさ:',
'widthheightpage'      => '$1×$2, $3 ページ',
'file-info'            => '(ファイルサイズ: $1, MIMEタイプ: $2)',
'file-info-size'       => '($1 × $2 ピクセル, ファイルサイズ: $3, MIMEタイプ: $4)',
'file-nohires'         => '<small>高精細度の画像はありません。</small>',
'svg-long-desc'        => '(SVGファイル, $1 × $2 ピクセル, ファイルサイズ: $3)',
'show-big-image'       => '高解像度での画像',
'show-big-image-thumb' => '<small>このプレビューのサイズ: $1 × $2 pixels</small>',

# Special:Newimages
'newimages'    => '新規画像展示室',
'showhidebots' => '（ボットを$1）',
'noimages'     => '画像がありません。',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-cn' => '簡体（中国）',
'variantname-zh-tw' => '正字（台湾）',
'variantname-zh-hk' => '正字（香港）',
'variantname-zh-sg' => '簡体（シンガポール）',
'variantname-zh'    => '無変換',

# Metadata
'metadata'          => 'メタデータ',
'metadata-help'     => 'このファイルはデジタルカメラ・スキャナなどが付加した追加情報を含んでいます。このファイルがオリジナルの状態から変更されている場合、いくつかの項目は変更を完全に反映していないかもしれません。',
'metadata-expand'   => '拡張項目を表示',
'metadata-collapse' => '拡張項目を隠す',
'metadata-fields'   => 'ここに挙げたEXIF情報のフィールドのみが標準で表示されます。
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => '画像の幅',
'exif-imagelength'                 => '画像の高さ',
'exif-bitspersample'               => 'ビット深度',
'exif-compression'                 => '圧縮形式',
'exif-photometricinterpretation'   => '画素構成',
'exif-orientation'                 => '画像方向',
'exif-samplesperpixel'             => 'コンポーネント数',
'exif-planarconfiguration'         => 'データ格納形式',
'exif-ycbcrsubsampling'            => 'YCCの画素構成（Cの間引き率）',
'exif-ycbcrpositioning'            => 'YCCの画素構成（YとCの位置）',
'exif-xresolution'                 => '水平解像度',
'exif-yresolution'                 => '垂直解像度',
'exif-resolutionunit'              => '解像度の単位',
'exif-stripoffsets'                => '画像データの場所',
'exif-rowsperstrip'                => 'ストリップのライン数',
'exif-stripbytecounts'             => 'ストリップのデータ量',
'exif-jpeginterchangeformat'       => 'JPEGのSOIへのオフセット',
'exif-jpeginterchangeformatlength' => 'JPEGデータのバイト数',
'exif-transferfunction'            => '再生階調カーブ特性',
'exif-whitepoint'                  => '参照白色点の色度座標値',
'exif-primarychromaticities'       => '原色の色度座標値',
'exif-ycbcrcoefficients'           => '色変換マトリックス係数',
'exif-referenceblackwhite'         => '参照黒色点値・参照白色点値',
'exif-datetime'                    => 'ファイル変更日時',
'exif-imagedescription'            => '画像の説明',
'exif-make'                        => '画像入力機器のメーカー',
'exif-model'                       => '画像入力機器の機種',
'exif-software'                    => 'ファームウェアのバージョン',
'exif-artist'                      => '作成者',
'exif-copyright'                   => '著作権者',
'exif-exifversion'                 => 'Exifバージョン',
'exif-flashpixversion'             => '対応フラッシュピックスバージョン',
'exif-colorspace'                  => '色空間',
'exif-componentsconfiguration'     => '各コンポーネントの構成',
'exif-compressedbitsperpixel'      => '画像圧縮モード',
'exif-pixelydimension'             => '実効画像幅',
'exif-pixelxdimension'             => '実効画像高さ',
'exif-makernote'                   => 'メーカーノート',
'exif-usercomment'                 => 'ユーザーコメント',
'exif-relatedsoundfile'            => '関連音声ファイル',
'exif-datetimeoriginal'            => '画像データ生成日時',
'exif-datetimedigitized'           => 'デジタルデータ作成日時',
'exif-subsectime'                  => 'ファイル変更日時 (秒未満)',
'exif-subsectimeoriginal'          => '画像データ生成日時 (秒未満)',
'exif-subsectimedigitized'         => 'デジタルデータ作成日時 (秒未満)',
'exif-exposuretime'                => '露出時間',
'exif-exposuretime-format'         => '$1秒 ($2)',
'exif-fnumber'                     => 'F値',
'exif-exposureprogram'             => '露出プログラム',
'exif-spectralsensitivity'         => 'スペクトル感度',
'exif-isospeedratings'             => 'ISOスピードレート',
'exif-oecf'                        => '光電変換関数',
'exif-shutterspeedvalue'           => 'シャッタースピード',
'exif-aperturevalue'               => '絞り値',
'exif-brightnessvalue'             => '明るさ',
'exif-exposurebiasvalue'           => '露出補正値',
'exif-maxaperturevalue'            => 'レンズ最小F値',
'exif-subjectdistance'             => '被写体距離',
'exif-meteringmode'                => '測光方式',
'exif-lightsource'                 => '光源',
'exif-flash'                       => 'フラッシュ',
'exif-focallength'                 => 'レンズの焦点距離',
'exif-subjectarea'                 => '主要被写体の位置',
'exif-flashenergy'                 => 'フラッシュ強度',
'exif-spatialfrequencyresponse'    => '空間周波数応答',
'exif-focalplanexresolution'       => '水平方向の焦点面解像度',
'exif-focalplaneyresolution'       => '垂直方向の焦点面解像度',
'exif-focalplaneresolutionunit'    => '焦点面解像度の単位',
'exif-subjectlocation'             => '被写体の場所',
'exif-exposureindex'               => '露出インデックス',
'exif-sensingmethod'               => 'センサー方式',
'exif-filesource'                  => 'ファイルソース',
'exif-scenetype'                   => 'シーンタイプ',
'exif-cfapattern'                  => 'CFAパターン',
'exif-customrendered'              => '画像処理',
'exif-exposuremode'                => '露出モード',
'exif-whitebalance'                => 'ホワイトバランス',
'exif-digitalzoomratio'            => 'デジタルズーム倍率',
'exif-focallengthin35mmfilm'       => 'レンズの焦点距離（35mmフィルム換算）',
'exif-scenecapturetype'            => '被写体の種別',
'exif-gaincontrol'                 => 'ゲインコントロール',
'exif-contrast'                    => 'コントラスト',
'exif-saturation'                  => '彩度',
'exif-sharpness'                   => 'シャープネス',
'exif-devicesettingdescription'    => '機器設定',
'exif-subjectdistancerange'        => '被写体距離の範囲',
'exif-imageuniqueid'               => 'ユニーク画像ID',
'exif-gpsversionid'                => 'GPSタグのバージョン',
'exif-gpslatituderef'              => '北緯/南緯',
'exif-gpslatitude'                 => '緯度',
'exif-gpslongituderef'             => '東経/西経',
'exif-gpslongitude'                => '経度',
'exif-gpsaltituderef'              => '高度の基準',
'exif-gpsaltitude'                 => '高度',
'exif-gpstimestamp'                => 'GPS時刻（原子時計）',
'exif-gpssatellites'               => '測位に用いた衛星信号',
'exif-gpsstatus'                   => 'GPS受信機の状態',
'exif-gpsmeasuremode'              => 'GPS測位方法',
'exif-gpsdop'                      => '測位精度',
'exif-gpsspeedref'                 => '速度の単位',
'exif-gpsspeed'                    => '速度',
'exif-gpstrackref'                 => '進行方向の基準',
'exif-gpstrack'                    => '進行方向',
'exif-gpsimgdirectionref'          => '撮影方向の基準',
'exif-gpsimgdirection'             => '撮影方向',
'exif-gpsmapdatum'                 => '測地系',
'exif-gpsdestlatituderef'          => '目的地の北緯/南緯',
'exif-gpsdestlatitude'             => '目的地の緯度',
'exif-gpsdestlongituderef'         => '目的地の東経/西経',
'exif-gpsdestlongitude'            => '目的地の経度',
'exif-gpsdestbearingref'           => '目的地の方角の基準',
'exif-gpsdestbearing'              => '目的地の方角',
'exif-gpsdestdistanceref'          => '目的地までの距離の単位',
'exif-gpsdestdistance'             => '目的地までの距離',
'exif-gpsprocessingmethod'         => 'GPS処理方法',
'exif-gpsareainformation'          => 'GPSエリア名',
'exif-gpsdatestamp'                => 'GPS測位日時',
'exif-gpsdifferential'             => 'ディファレンシャル補正',

# EXIF attributes
'exif-compression-1' => '非圧縮',
'exif-compression-6' => 'JPEG圧縮',

'exif-unknowndate' => '不明な日付',

'exif-orientation-1' => '通常', # 0th row: top; 0th column: left
'exif-orientation-2' => '左右反転', # 0th row: top; 0th column: right
'exif-orientation-3' => '180°回転', # 0th row: bottom; 0th column: right
'exif-orientation-4' => '上下反転', # 0th row: bottom; 0th column: left
'exif-orientation-5' => '反時計回りに90°回転 上下反転', # 0th row: left; 0th column: top
'exif-orientation-6' => '時計回りに90°回転', # 0th row: right; 0th column: top
'exif-orientation-7' => '時計回りに90°回転 上下反転', # 0th row: right; 0th column: bottom
'exif-orientation-8' => '反時計回りに90°回転', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => '点順次フォーマット',
'exif-planarconfiguration-2' => '面順次フォーマット',

'exif-colorspace-ffff.h' => 'その他',

'exif-componentsconfiguration-0' => 'なし',

'exif-exposureprogram-0' => '未定義',
'exif-exposureprogram-1' => 'マニュアル',
'exif-exposureprogram-2' => 'ノーマルプログラム',
'exif-exposureprogram-3' => '露出優先',
'exif-exposureprogram-4' => 'シャッター速度優先',
'exif-exposureprogram-5' => 'クリエイティブ・プログラム',
'exif-exposureprogram-6' => 'アクション・プログラム',
'exif-exposureprogram-7' => 'ポートレイトモード（近景）',
'exif-exposureprogram-8' => 'ランドスケープモード（遠景）',

'exif-subjectdistance-value' => '$1 メートル',

'exif-meteringmode-0'   => '不明',
'exif-meteringmode-1'   => '平均',
'exif-meteringmode-2'   => '中央重点',
'exif-meteringmode-3'   => 'スポット',
'exif-meteringmode-4'   => 'マルチスポット',
'exif-meteringmode-5'   => '分割測光',
'exif-meteringmode-6'   => '部分測光',
'exif-meteringmode-255' => 'その他',

'exif-lightsource-0'   => '不明',
'exif-lightsource-1'   => '昼光',
'exif-lightsource-2'   => '蛍光灯',
'exif-lightsource-3'   => 'タングステン（白熱灯）',
'exif-lightsource-4'   => 'フラッシュ',
'exif-lightsource-9'   => '晴天',
'exif-lightsource-10'  => '曇天',
'exif-lightsource-11'  => '日陰',
'exif-lightsource-12'  => '昼光色蛍光灯 (D 5700 - 7100K)',
'exif-lightsource-13'  => '昼白色蛍光灯 (N 4600 - 5400K)',
'exif-lightsource-14'  => '白色蛍光灯 (W 3900 - 4500K)',
'exif-lightsource-15'  => '温白色蛍光灯 (WW 3200 - 3700K)',
'exif-lightsource-17'  => '標準光A',
'exif-lightsource-18'  => '標準光B',
'exif-lightsource-19'  => '標準光C',
'exif-lightsource-24'  => 'ISOスタジオタングステン',
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
'exif-gaincontrol-4' => '強減感',

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

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => '北緯',
'exif-gpslatitude-s' => '南緯',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => '東経',
'exif-gpslongitude-w' => '西経',

'exif-gpsstatus-a' => '測位中',
'exif-gpsstatus-v' => '未測位',

'exif-gpsmeasuremode-2' => '2次元測位',
'exif-gpsmeasuremode-3' => '3次元測位',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'キロメートル毎時',
'exif-gpsspeed-m' => 'マイル毎時',
'exif-gpsspeed-n' => 'ノット',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '真方位',
'exif-gpsdirection-m' => '磁方位',

# External editor support
'edit-externally'      => '外部アプリケーションを使ってこのファイルを編集する',
'edit-externally-help' => '詳しい情報は[http://meta.wikimedia.org/wiki/Help:External_editors 外部エディタに関する説明（英語）]をご覧ください。',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'すべて',
'imagelistall'     => 'すべて',
'watchlistall2'    => 'すべて',
'namespacesall'    => 'すべて',
'monthsall'        => 'すべて',

# E-mail address confirmation
'confirmemail'            => 'メールアドレスの確認',
'confirmemail_noemail'    => '[[{{ns:special}}:Preferences|オプション設定]]で有効なメールアドレスが指定されていません。',
'confirmemail_text'       => 'このウィキではメール通知を受け取る前にメールアドレスの確認が必要です。以下のボタンを押すと「{{int:Confirmemail_subject}}」という件名の確認メールがあなたのメールアドレスに送られます。メールには確認用コードを含むリンクが書かれています。そのリンクを開くことによってメールアドレスの正当性が確認されます。',
'confirmemail_pending'    => '<div class="error">
確認メールは既に送信されています。あなたがこのアカウントを作成したばかりであれば、数分待って既にメールが送信されていないかを確かめてください。
</div>',
'confirmemail_send'       => '確認用コードを送信する',
'confirmemail_sent'       => '確認メールを送信しました。',
'confirmemail_oncreate'   => 'メールアドレスの正当性を確認するためのコードを含んだメールを送信しました。この確認を行わなくてもログインはできますが、確認するまでメール通知の機能は無効化されます。',
'confirmemail_sendfailed' => '確認メールを送信できませんでした。メールアドレスに不正な文字が含まれていないかどうか確認してください。

メールサーバーからの返答: $1',
'confirmemail_invalid'    => '確認用コードが正しくありません。このコードは期限切れです。',
'confirmemail_needlogin'  => 'メールアドレスを確認するために$1が必要です。',
'confirmemail_success'    => 'あなたのメールアドレスは確認されました。ログインしてウィキを使用できます。',
'confirmemail_loggedin'   => 'あなたのメールアドレスは確認されました。',
'confirmemail_error'      => 'あなたの確認を保存する際に内部エラーが発生しました。',
'confirmemail_subject'    => '{{SITENAME}} メールアドレスの確認',
'confirmemail_body'       => 'This is a E-mail confirmation of *{{SITENAME}}*.
If you can not read this message below,
you can not read wikimail either.
Then, please change a mailer
or address which can read UTF-8 mail, and retry.
Thank you.

--

どなたか（IPアドレス $1 の使用者）がこのメールアドレスを
{{SITENAME}} のアカウント "$2" に登録しました。

このアカウントがあなたのものであるか確認してください。
あなたの登録したアカウントであるならば、{{SITENAME}}
のメール通知機能を有効にするために、以下のURLにアクセスしてください:

$3

もし {{SITENAME}} について身に覚えがない場合は、リンクを開かないでください。
確認用コードは $4 に期限切れになります。

-- 
{{SITENAME}}
{{SERVER}}/',

# Scary transclusion
'scarytranscludedisabled' => '[Interwiki transcluding は無効になっています]',
'scarytranscludefailed'   => '[テンプレート $1 の取得に失敗しました]',
'scarytranscludetoolong'  => '[URLが長すぎます]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
この項目へのトラックバック:
$1
</div>',
'trackbackremove'   => ' ([$1 削除])',
'trackbacklink'     => 'トラックバック',
'trackbackdeleteok' => 'トラックバックを削除しました。',

# Delete conflict
'deletedwhileediting' => "'''警告:''' このページはあなたが編集し始めた後、削除されました!!",
'confirmrecreate'     => 'あなたがこのページを編集し始めた後に、このページは[[User:$1|$1]] ([[User_talk:$1|会話]]) によって削除されました。その理由は次の通りです:
:$2
このままこのページを新規作成して良いか確認してください。',
'recreate'            => '新規作成する',

# HTML dump
'redirectingto' => '[[$1]]へ転送しています...',

# action=purge
'confirm_purge'        => 'ページのキャッシュを破棄します。よろしいですか?

$1',
'confirm_purge_button' => 'はい',

# AJAX search
'searchcontaining' => "'''$1''' を含むページの検索。",
'searchnamed'      => "ページ名が '''$1''' の項目の検索。",
'articletitles'    => "''$1'' からはじまる項目",
'hideresults'      => '結果を隠す',

# Multipage image navigation
'imgmultipageprev'   => '&larr; 前ページ',
'imgmultipagenext'   => '次ページ &rarr;',
'imgmultigo'         => '表示',
'imgmultigotopost'   => 'ページ目を',
'imgmultiparseerror' => '画像ファイルが壊れているか正しくないため、ページのリストを生成できませんでした。',

# Table pager
'ascending_abbrev'         => '昇順',
'descending_abbrev'        => '降順',
'table_pager_next'         => '次のページ',
'table_pager_prev'         => '前のページ',
'table_pager_first'        => '最初のページ',
'table_pager_last'         => '最後のページ',
'table_pager_limit'        => '1ページに $1 個表示',
'table_pager_limit_submit' => '実行',
'table_pager_empty'        => '結果なし',

# Auto-summaries
'autosumm-blank'   => 'ページの白紙化',
'autosumm-replace' => "ページの置換: '$1'",
'autoredircomment' => '[[$1]]へのリダイレクト',
'autosumm-new'     => "新しいページ: '$1'",

# Size units
'size-bytes'     => '$1 バイト',
'size-kilobytes' => '$1 キロバイト',
'size-megabytes' => '$1 メガバイト',
'size-gigabytes' => '$1 ギガバイト',

# Live preview
'livepreview-loading' => '読み込み中…',
'livepreview-ready'   => '読み込み中… 完了',
'livepreview-failed'  => 'ライブプレビューが失敗しました。
通常のプレビューを試みてください。',
'livepreview-error'   => '接続に失敗しました: $1 "$2"
通常のプレビューを試みてください。',

# Friendlier slave lag warnings
'lag-warn-normal' => 'この一覧には$1秒前までの編集が反映されていない可能性があります。',
'lag-warn-high'   => 'データベースサーバの負荷のため同期が遅れています。この一覧には$1秒前までの編集が反映されていない可能性があります。',

# Watchlist editor
'watchlistedit-numitems'       => 'あなたのウォッチリストには $1タイトルが登録されています（ノートページを除く）。',
'watchlistedit-noitems'        => 'あなたのウォッチリストには、現在タイトルがありません。',
'watchlistedit-normal-title'   => 'ウォッチリストの編集',
'watchlistedit-normal-legend'  => 'ウォッチリストからタイトルを削除',
'watchlistedit-normal-explain' => 'あなたのウォッチリストにあるタイトルが以下に表示されています。タイトルの横にあるチェックボックスにチェックを入れ、「{{int:watchlistedit-normal-submit}}」を選べば削除できます。また、[[Special:Watchlist/raw|一覧をテキストで編集]]したり、[[Special:Watchlist/clear|タイトルをすべて削除]]することもできます。',
'watchlistedit-normal-submit'  => 'タイトルの削除',
'watchlistedit-normal-done'    => 'あなたのウォッチリストから $1 タイトルを削除しました:',
'watchlistedit-raw-title'      => 'ウォッチリストをテキストで編集',
'watchlistedit-raw-legend'     => 'ウォッチリストをテキストで編集',
'watchlistedit-raw-explain'    => 'あなたのウォッチリストにあるタイトルが以下に表示されています。1行につき1つのタイトルを表し、タイトルを追加・削除することにより編集できます。編集を反映させる時は "{{int:Watchlistedit-raw-submit}}" を選びます。この編集方法の他に、[[Special:Watchlist/edit|標準的なエディタ]]も利用できます。',
'watchlistedit-raw-titles'     => 'タイトル:',
'watchlistedit-raw-submit'     => 'ウォッチリストを更新',
'watchlistedit-raw-done'       => 'あなたのウォッチリストを更新しました。',
'watchlistedit-raw-added'      => '$1 タイトルを追加しました:',
'watchlistedit-raw-removed'    => '$1 タイトルを削除しました:',

# Watchlist editing tools
'watchlisttools-view' => 'ウォッチリストの確認',
'watchlisttools-edit' => 'ウォッチリストの編集',
'watchlisttools-raw'  => 'ウォッチリストをテキストで編集',

# Iranian month names
'iranian-calendar-m1'  => 'イラン歴第1月',
'iranian-calendar-m2'  => 'イラン歴第2月',
'iranian-calendar-m3'  => 'イラン歴第3月',
'iranian-calendar-m4'  => 'イラン歴第4月',
'iranian-calendar-m5'  => 'イラン歴第5月',
'iranian-calendar-m6'  => 'イラン歴第6月',
'iranian-calendar-m7'  => 'イラン歴第7月',
'iranian-calendar-m8'  => 'イラン歴第8月',
'iranian-calendar-m9'  => 'イラン歴第9月',
'iranian-calendar-m10' => 'イラン歴第10月',
'iranian-calendar-m11' => 'イラン歴第11月',
'iranian-calendar-m12' => 'イラン歴第12月',

);
