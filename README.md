<h1 align="center">PhpAidc LabelPrinter</h1>

<p align="center">
    <a href="https://packagist.org/packages/php-aidc/label-printer"><img src="https://img.shields.io/packagist/v/php-aidc/label-printer.svg?style=flat" alt="Latest Version on Packagist" /></a>
    <a href="https://github.com/php-aidc/label-printer/actions?workflow=tests"><img src="https://github.com/php-aidc/label-printer/workflows/tests/badge.svg" alt="Testing" /></a>
    <a href="https://scrutinizer-ci.com/g/php-aidc/label-printer"><img src="https://img.shields.io/scrutinizer/g/php-aidc/label-printer.svg?style=flat" alt="Quality Score" /></a>
    <a href="https://scrutinizer-ci.com/g/php-aidc/label-printer/?branch=master"><img src="https://img.shields.io/scrutinizer/coverage/g/php-aidc/label-printer/master.svg?style=flat" alt="Code Coverage" /></a>
    <a href="https://packagist.org/packages/php-aidc/label-printer"><img src="https://poser.pugx.org/php-aidc/label-printer/downloads?format=flat" alt="Total Downloads"></a>
    <a href="https://raw.githubusercontent.com/php-aidc/label-printer/master/LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-428F7E" alt="License MIT"></a>
</p>

PhpAidc LabelPrinter is a library that help you create and print labels on printers that support
Direct Protocol, Fingerprint, TSPL/TSPL2 languages (Honeywell, Intermec, TSC) via TCP/IP.

---

- [Requirements](#requirements)
- [Installation](#installation)
- [Basic usage](#basic-usage)

## Requirements
- PHP 7.1+
- ext-mbstring

## Installation

LabelPrinter is installed via [Composer](https://getcomposer.org/):
```bash
composer require php-aidc/label-printer
```

You can of course also manually edit your composer.json file
```json
{
    "require": {
       "php-aidc/label-printer": "v0.1"
    }
}
```

## Basic usage

> Some TSPL2-like printers, such as Atol BP41/Rongta RP410, do not support all TSPL2 features.

##### Read data from printer

```php
use PhpAidc\LabelPrinter\Printer;
use PhpAidc\LabelPrinter\Connector\NetworkConnector;

$printer = new Printer(new NetworkConnector('192.168.x.x'));

\var_dump($printer->ask('? VERSION$(0)'));
// "Direct Protocol  10.15.017559   \r\n"
```

##### Create and print label
```php
use PhpAidc\LabelPrinter\Enum\Unit;
use PhpAidc\LabelPrinter\Enum\Anchor;
use PhpAidc\LabelPrinter\Enum\Charset;
use PhpAidc\LabelPrinter\Printer;
use PhpAidc\LabelPrinter\Label\Label;
use PhpAidc\LabelPrinter\Label\Element;
use PhpAidc\LabelPrinter\Connector\NetworkConnector;

$printer = new Printer(new NetworkConnector('192.168.x.x'));

$label = Label::create(Unit::MM(), 43, 25)
    ->charset(Charset::UTF8())
    ->add(Element::textBlock(168, 95, 'Hello!', 'Univers', 8)->box(338, 100, 0)->anchor(Anchor::CENTER()))
    ->add(Element::barcode(10, 10, '123456', 'CODE93')->height(60));

$printer->print($label);
```

##### Add elements for a specific language
```php
use PhpAidc\LabelPrinter\Label\Label;
use PhpAidc\LabelPrinter\Label\Element;
use PhpAidc\LabelPrinter\Language\Tspl;
use PhpAidc\LabelPrinter\Language\Fingerprint;

$label = Label::create()
    ->when(Fingerprint::class, static function (Label $label) {
        $label->add(Element::textLine(168, 95, 'Hello!', 'Univers', 8));
    })
    ->when(Tspl::class, static function (Label $label) {
        $label->add(Element::textLine(10, 10, 'Hello!', 'ROMAN.TTF', 8));
    });
```

##### Print images
```php
use PhpAidc\LabelPrinter\Label\Label;
use PhpAidc\LabelPrinter\Label\Element;
use PhpAidc\LabelPrinter\Language\Tspl;
use PhpAidc\LabelPrinter\Language\Fingerprint;

$image = new \Imagick('gift.svg');
$image->scaleImage(100, 100);

$label = Label::create()
    ->when(Fingerprint::class, static function (Label $label) {
        // from printer's memory — png, bmp, pcx
        $label->add(Element::intImage(10, 10, 'GLOBE.1'));
        // from filesystem
        $label->add(Element::extImage(10, 10, \realpath('alien.png')));
    })
    ->when(Tspl::class, static function (Label $label) {
        // from printer's memory — bmp, pcx
        $label->add(Element::intImage(10, 10, 'ALIEN.BMP'));
    })
    // from filesystem via Imagick — any supported types
    ->add(Element::bitmap(50, 10, $image));
```

##### Print text with emulation
```php
use PhpAidc\LabelPrinter\Label\Label;
use PhpAidc\LabelPrinter\Label\Element;

$label = Label::create()
    ->add(Element::textLine(10, 10, 'Hello!', '/path/to/font/roboto.ttf', 20)->emulate())
    ->add(Element::textBlock(100, 10, 'Hello again!', '/path/to/font/roboto.ttf', 20)->box(300, 20)->emulate());
```
Text will be drawn with Imagick and printed as bitmap.

##### Specify the number of copies
```php
use PhpAidc\LabelPrinter\Label\Label;
use PhpAidc\LabelPrinter\Label\Element;

$label = Label::create()
    ->add(Element::textLine(168, 95, 'Hello!', 'Univers', 8))
    ->copies(3)
;
```

##### Batch printing
```php
use PhpAidc\LabelPrinter\Printer;
use PhpAidc\LabelPrinter\Label\Batch;
use PhpAidc\LabelPrinter\Label\Label;
use PhpAidc\LabelPrinter\Label\Element;
use PhpAidc\LabelPrinter\Connector\NetworkConnector;

$batch = (new Batch())
    ->add(Label::create()->add(Element::textLine(168, 95, 'Hello!', 'Univers', 8)))
    ->add(Label::create()->add(Element::textLine(168, 95, 'Bye!', 'Univers', 8)))
;

(new Printer(new NetworkConnector('192.168.x.x')))->print($label);
```

## License

The PhpAidc LabelPrinter is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

> Some ideas taken from [mike42/escpos-php](https://github.com/mike42/escpos-php).
