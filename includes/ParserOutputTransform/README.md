Output transformations pipelines for wikitext

The classes in this directory contains transformation pipelines for wikitext,
i.e. postprocessors for ParserOutput objects that either directly result from a
parse or are fetched from ParserCache.

The default pipeline is DefaultOutputTransform; it corresponds to what was previously
contained in ParserOutput::getText.
