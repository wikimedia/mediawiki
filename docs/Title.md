The MediaWiki software's "Title" class represents article titles, which are used
for many purposes: as the human-readable text title of the article, in the URL
used to access the article, the wikitext link to the article, the key into the
article database, and so on. The class in instantiated from one of these forms
and can be queried for the others, and for other attributes of the title. This
is intended to be an immutable "value" class, so there are no mutator functions.

To get a new instance, call Title::newFromText(). Once instantiated, the
non-static accessor methods can be used, such as getText(), getDBkey(),
getNamespace(), etc. Note that Title::newFromText() may return false if the text
is illegal according to the rules below.

The prefix rules: a title consists of an optional interwiki prefix (such as "m:"
for meta or "de:" for German), followed by an optional namespace, followed by
the remainder of the title. Both interwiki prefixes and namespace prefixes have
the same rules: they contain only letters, digits, space, and underscore, must
start with a letter, are case insensitive, and spaces and underscores are
interchangeable. Prefixes end with a ":". A prefix is only recognized if it is
one of those specifically allowed by the software. For example, "de:name" is a
link to the article "name" in the German Wikipedia, because "de" is recognized
as one of the allowable interwikis. The title "talk:name" is a link to the
article "name" in the "talk" namespace of the current wiki, because "talk" is a
recognized namespace. Both may be present, and if so, the interwiki must
come first, for example, "m:talk:name". If a title begins with a colon as its
first character, no prefixes are scanned for, and the colon is just removed.
Note that because of these rules, it is possible to have articles with colons in
their names. "E. Coli 0157:H7" is a valid title, as is "2001: A Space Odyssey",
because "E. Coli 0157" and "2001" are not valid interwikis or namespaces.

It is not possible to have an article whose bare name includes a namespace or
interwiki prefix.

An initial colon in a title listed in wiki text may however suppress special
handling for interlanguage links, image links, and category links. It is also
used to indicate the main namespace in template inclusions.

Once prefixes have been stripped, the rest of the title processed this way:

* Spaces and underscores are treated as equivalent and each  is converted to the
  other in the appropriate context (underscore in URL and database keys, spaces
  in plain text).
* Multiple consecutive spaces are converted to a single space.
* Leading or trailing space is removed.
* If $wgCapitalLinks is enabled (the default), the first letter is  capitalised,
  using the capitalisation function of the content language object.
* The unicode characters LRM (U+200E) and RLM (U+200F) are silently stripped.
* Invalid UTF-8 sequences or instances of the replacement character (U+FFFD) are
  considered illegal.
* A percent sign followed by two hexadecimal characters is illegal
* Anything that looks like an XML/HTML character reference is illegal
* Any character not matched by the $wgLegalTitleChars regex is illegal
* Zero-length titles (after whitespace stripping) are illegal

All titles except special pages must be less than 255 bytes when encoded with
UTF-8, because that is the size of the database field. Special page titles may
be up to 512 bytes.

Note that Unicode Normal Form C (NFC) is enforced by MediaWiki's user interface
input functions, and so titles will typically be in this form.

getArticleID() needs some explanation: for "internal" articles, it should return
the "page_id" field if the article exists, else it returns 0. For all external
articles it returns 0. All of the IDs for all instances of Title created during
a request are cached, so they can be looked up quickly while rendering wiki text
with lots of internal links. See LinkCache.md.
