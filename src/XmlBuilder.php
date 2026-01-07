<?php
namespace mmusaib\XmlBuilder;

use DOMDocument;
use DOMElement;

class XmlBuilder {
    private DOMDocument $dom;
    private DOMElement $root;
    private string $inputEncoding = 'UTF-8';

    public function __construct(
        string $rootElement,
        string $version = '1.0',
        string $encoding = 'UTF-8'
    ) {
        libxml_use_internal_errors(true);

        $this->dom = new DOMDocument($version, $encoding);
        $this->dom->formatOutput = true;

        $this->root = $this->dom->createElement($rootElement);
        $this->dom->appendChild($this->root);
    }

    public function getRoot(): DOMElement
    {
        return $this->root;
    }

    public function setPrettyPrint(bool $enabled = true): self
    {
        $this->dom->formatOutput = $enabled;
        return $this;
    }

    public function setInputEncoding(string $encoding): self
    {
        $this->inputEncoding = $encoding;
        return $this;
    }

    public function addElement(
        DOMElement $parent,
        string $name,
        ?string $value = null
    ): DOMElement 
    {
        $element = $this->dom->createElement($name);

        if ($value !== null) {
            $element->nodeValue = $value;
        }

        $parent->appendChild($element);
        return $element;
    }

    public function addElementToRoot(
        string $name,
        ?string $value = null
    ): DOMElement 
    {
        return $this->addElement($this->root, $name, $value);
    }

    public function addCDATAElement(
        DOMElement $parent,
        string $name,
        ?string $value = null
    ): DOMElement 
    {
        $element = $this->addElement($parent, $name);

        if ($value !== null) {
            $value = mb_convert_encoding(
                $value,
                'UTF-8',
                $this->inputEncoding
            );
            $element->appendChild(
                $this->dom->createCDATASection($value)
            );
        }

        return $element;
    }

    public function addCDATAElements(
        DOMElement $parent,
        string $name,
        array $values
    ): void 
    {
        foreach ($values as $value) {
            $this->addCDATAElement($parent, $name, $value);
        }
    }

    public function addAttribute(
        DOMElement $node,
        string $name,
        string $value
    ): void 
    {
        $node->setAttribute($name, $value);
    }

    public function addAttributes(
        DOMElement $node,
        array $attributes
    ): void 
    {
        foreach ($attributes as $name => $value) {
            $node->setAttribute($name, (string) $value);
        }
    }

    public function getXml(): string
    {
        return $this->dom->saveXML();
    }

    public function saveToFile(string $filename): bool|int
    {
        return $this->dom->save($filename);
    }

    public function getErrors(): array
    {
        return libxml_get_errors();
    }
}
