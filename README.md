# XML Builder for PHP

A lightweight, fluent XML builder built on top of PHP's DOMDocument.

## Installation

```bash
composer require mmusaib/xml-builder
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

## 🚀 Streaming Large XML Files (Chunk-Safe Mode)

When generating very large XML files (hundreds of thousands or millions of nodes), keeping everything in memory using DOMDocument can cause memory exhaustion.
XmlBuilder supports streaming mode, allowing you to:
* Keep the root element constant
* Append children chunk by chunk
* Write directly to a file
* Keep memory usage extremely low

### Start Streaming
```php
$xml = new XmlBuilder('products');
$xml->startStream('products.xml');
```
This writes:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<products>
```

### Append Nodes Per Chunk
```php
foreach ($chunks as $chunk) {
    foreach ($chunk as $item) {
        $product = $xml->addElementToRoot('product');

        $xml->addElement($product, 'name', $item['name']);
        $xml->addElement($product, 'price', $item['price']);

        $xml->appendToStream($product);   // Writes & frees memory
    }
}
```
Each call:
* Writes <product>...</product> to file
* Removes it from memory immediately

### Finish Streaming
```php
$xml->endStream();
```
This closes the root element:
```php
</products>
```

## ✅ Why Use Streaming Mode?
| Problem                | Streaming Solves |
|------------------------|-----------------|
| Millions of records    | ✔               |
| Memory exhaustion      | ✔               |
| Chunked DB processing  | ✔               |
| Long-running jobs      | ✔               |



## Features
* Fluent XML creation
* CDATA support
* Attribute helpers
* Encoding-safe
* Pretty or compact output
* PSR-4 autoloading

## License
MIT
