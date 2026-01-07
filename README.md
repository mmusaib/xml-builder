# XML Builder for PHP

A lightweight, fluent XML builder built on top of PHP's DOMDocument.

## Installation

```bash
composer require musaib/xml-builder
```

## Usage

```php
use mmusaib\XmlBuilder\XmlBuilder;

$xml = new XmlBuilder('products');

$product = $xml->addElementToRoot('product');
$xml->addAttributes($product, [
    'id' => 101,
    'type' => 'digital'
]);

$xml->addElement($product, 'name', 'Premium Course');
$xml->addCDATAElement(
    $product,
    'description',
    '<strong>High quality content</strong>'
);

echo $xml->getXml();
```

## Features
* Fluent XML creation
* CDATA support
* Attribute helpers
* Encoding-safe
* Pretty or compact output
* PSR-4 autoloading

## License
MIT
