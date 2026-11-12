Local copies of external schemas that MediaWiki references, so that tests can resolve them
without network access. Each copy lives at the path of its URL without "https://", e.g.
`schema.wikimedia.org/repositories/mediawiki/api/fragments/error-v1.json` is a copy of
https://schema.wikimedia.org/repositories/mediawiki/api/fragments/error-v1.json.
Only `.json` and `.yaml` files are synced.

* Update all copies: `php maintenance/run.php syncExternalSchemas`
* Check for outdated copies: `php maintenance/run.php syncExternalSchemas --check`
* Add or update one copy: `php maintenance/run.php syncExternalSchemas <https:// URL>`

Published schemas may be referenced by clients, so do not change them. To change a
schema, publish it as a new versioned file (e.g. `error-v3.json`), add its copy here
and reference the new URL. Each schema's `$id` is the absolute URL it is published at.
