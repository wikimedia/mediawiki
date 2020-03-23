-- (T218446) Make page.page_restrictions nullable in preparation for dropping it
ALTER TABLE /*_*/page
    CHANGE COLUMN page_restrictions page_restrictions TINYBLOB NULL;
