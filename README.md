# About

This is a [Lorem ipsum](http://www.lipsum.com/) generator written in PHP. It is based on the work of [Mathew Tinsley](http://tinsology.net/) and has been refactored so it can be used as a composer module and to match the PSR codestyle. [The original code can be found here](http://tinsology.net/scripts/php-lorem-ipsum-generator/), or by looking at [the very first commit](https://github.com/classmarkets/LipsumGenerator/commit/aa7d9690c7cfddb029004f4b03e15d0f1a44dca9).

Text generation is based on a fixed dictionary, and randomized using a Gaussian distribution of the word length. Supported output formats are HTML paragraphs (`<p/>`), preformatted and unformated plain text. Words per paragraph and number of paragraphs can be configured.

Once again, all credits for the implementation go to [Mathew Tinsley](http://tinsology.net/), unless he stole it, in which case I don't mind taking the credit myself :)

# Usage:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/classmarkets/LipsumGenerator"
        }
    ],
    "require": {
        "classmarkets/lipsum-generator": "*"
    }
}
```

```php
<?php
use Classmarkets\LipsumGenerator;

$wordPerParagraph = 100;
$numberOfParagaphs = 1;

$generator          = new LipsumGenerator($wordsPerParagraph);

$htmlLipsum         = $generator->getContent($numberOfParagraphs, LipsumGenerator::FORMAT_HTML);
$preFormattedLipsum = $generator->getContent($numberOfParagraphs, LipsumGenerator::FORMAT_TEXT);
$plainLipsum        = $generator->getContent($numberOfParagraphs, LipsumGenerator::FORMAT_PLAIN);
```