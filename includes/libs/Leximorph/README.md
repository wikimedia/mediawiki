# Leximorph

This library provides language-specific text transformations, including pluralization, grammatical inflections, gender-based selection, formality variations, and bidirectional text handling.

## Usage

Below is a simple example of how to use the library:

```php
use Wikimedia\Leximorph\Manager;

// Create a Manager for English:
$managerEn = new Manager( 'en' );

// Bidirectional text transformation (language‑neutral)
$bidiHandler = $managerEn->getBidi();
echo $bidiHandler->process( 'Hello World' );
// Expected output: "\xE2\x80\xAAHello World\xE2\x80\xAC"

// Plural transformation for English
$pluralHandler = $managerEn->getPlural();
echo $pluralHandler->process( 3, [ 'article', 'articles' ] );
// Expected output: "articles"

// Gender-based transformation (language‑neutral)
$genderHandler = $managerEn->getGender();
echo $genderHandler->process( 'female', [ 'he', 'she', 'they' ] );
// Expected output: "she"

// For formal language transformation, create a Manager for German:
$managerDe = new Manager( 'de' );
$formalHandler = $managerDe->getFormal();
echo $formalHandler->process( [ 'Du hast', 'Sie haben' ] );
// Expected output: "Sie haben"

// For grammatical transformations, create a Manager for Russian:
$managerRu = new Manager( 'ru' );
$grammarHandler = $managerRu->getGrammar();
echo $grammarHandler->process( 'Википедия', 'genitive' );
// Expected output: "Википедии"
```
