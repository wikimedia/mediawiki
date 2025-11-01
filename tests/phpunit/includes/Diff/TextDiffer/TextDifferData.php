<?php

namespace MediaWiki\Tests\Diff\TextDiffer;

class TextDifferData {
	public const EXTERNAL = "- foo\n+ bar\n";

	public const PHP_TABLE = '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1"><!--LINE 1--></td>
<td colspan="2" class="diff-lineno"><!--LINE 1--></td></tr>
<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">foo</del></div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">bar</ins></div></td></tr>
';

	public const PHP_UNIFIED = '@@ -1,1 +1,1 @@
-foo
+bar
';

	public const WIKIDIFF2_TABLE = '<tr>
  <td colspan="2" class="diff-lineno"><!--LINE 1--></td>
  <td colspan="2" class="diff-lineno"><!--LINE 1--></td>
</tr>
<tr>
  <td colspan="2" class="diff-empty diff-side-deleted"></td>
  <td class="diff-marker" data-marker="+"></td>
  <td class="diff-addedline diff-side-added"><div>bar</div></td>
</tr>
<tr>
  <td class="diff-marker" data-marker="−"></td>
  <td class="diff-deletedline diff-side-deleted"><div>foo</div></td>
  <td colspan="2" class="diff-empty diff-side-added"></td>
</tr>
';

	public const WIKIDIFF2_INLINE = '<div class="mw-diff-inline-header"><!-- LINES 1,1 --></div>
<div class="mw-diff-inline-added"><ins>bar</ins></div>
<div class="mw-diff-inline-deleted"><del>foo</del></div>
';
}
