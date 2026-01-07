<?php

use mmusaib\XmlBuilder\XmlBuilder;
use PHPUnit\Framework\TestCase;

class XmlBuilderTest extends TestCase
{
    /** @test */
    public function it_creates_root_element()
    {
        $xml = new XmlBuilder('root');

        $output = $xml->getXml();

        $this->assertStringContainsString('<root>', $output);
        $this->assertStringContainsString('</root>', $output);
    }

    /** @test */
    public function it_adds_child_elements_to_root()
    {
        $xml = new XmlBuilder('root');

        $xml->addElementToRoot('child', 'value');

        $this->assertStringContainsString(
            '<child>value</child>',
            $xml->getXml()
        );
    }

    /** @test */
    public function it_adds_attributes_to_elements()
    {
        $xml = new XmlBuilder('root');
        $child = $xml->addElementToRoot('child');

        $xml->addAttribute($child, 'id', '10');
        $xml->addAttribute($child, 'type', 'example');

        $this->assertStringContainsString(
            '<child id="10" type="example"/>',
            $xml->getXml()
        );
    }

    /** @test */
    public function it_adds_cdata_elements()
    {
        $xml = new XmlBuilder('root');

        $xml->addCDATAElement(
            $xml->getRoot(),
            'content',
            '<strong>HTML Content</strong>'
        );

        $this->assertStringContainsString(
            '<![CDATA[<strong>HTML Content</strong>]]>',
            $xml->getXml()
        );
    }

    /** @test */
    public function it_adds_multiple_cdata_elements()
    {
        $xml = new XmlBuilder('root');

        $xml->addCDATAElements(
            $xml->getRoot(),
            'item',
            ['one', 'two', 'three']
        );

        $output = $xml->getXml();

        $this->assertStringContainsString('<item><![CDATA[one]]></item>', $output);
        $this->assertStringContainsString('<item><![CDATA[two]]></item>', $output);
        $this->assertStringContainsString('<item><![CDATA[three]]></item>', $output);
    }

    /** @test */
    public function it_can_disable_pretty_print()
    {
        $xml = new XmlBuilder('root');
        $xml->setPrettyPrint(false);

        $xml->addElementToRoot('child', 'value');

        $output = $xml->getXml();

        // Compact output should not contain newlines
        $this->assertStringNotContainsString("\n", $output);
    }

    /** @test */
    public function it_saves_xml_to_file()
    {
        $xml = new XmlBuilder('root');
        $xml->addElementToRoot('child', 'value');

        $file = sys_get_temp_dir() . '/xml_builder_test.xml';

        $result = $xml->saveToFile($file);

        $this->assertFileExists($file);
        $this->assertGreaterThan(0, $result);

        unlink($file);
    }
}
