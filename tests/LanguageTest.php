<?php

/**
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Language\Tests;

use Joomla\Language\Language;
use Joomla\Language\Parser\IniParser;
use Joomla\Language\ParserRegistry;
use Joomla\Test\TestHelper;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Joomla\Language\Language.
 */
class LanguageTest extends TestCase
{
    /**
     * Test language object
     *
     * @var  Language
     */
    protected $object;

    /**
     * File loader registry
     *
     * @var  ParserRegistry
     */
    protected $parserRegistry;

    /**
     * Path to language folder used for testing
     *
     * @var  string
     */
    private $testPath;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return  void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->parserRegistry = new ParserRegistry();
        $this->parserRegistry->add(new IniParser());

        $this->testPath = __DIR__ . '/data';
        $this->object   = new Language($this->parserRegistry, $this->testPath, 'en-GB');
        $this->object->load();
    }

    /**
     * @testdox  Verify that Language is instantiated correctly
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatLanguageIsInstantiatedCorrectly()
    {
        $this->assertInstanceOf(Language::class, new Language($this->parserRegistry, $this->testPath));
    }

    /**
     * @testdox  Verify that Language::_() proxies to Language::translate()
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testUnderscoreMethodProxiesToTranslate()
    {
        $this->assertEmpty($this->object->_(''));
    }

    /**
     * @testdox  Verify that Language::translate() returns an empty string when one is input
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testTranslateReturnsEmptyStringWhenGivenAnEmptyString()
    {
        $this->assertEmpty($this->object->translate(''));
    }

    /**
     * @testdox  Verify that Language::translate() returns the correct string for a key
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testTranslateReturnsTheCorrectStringForAKey()
    {
        $this->assertSame('Bar', $this->object->translate('FOO'));
    }

    /**
     * @testdox  Verify that Language::translate() returns the correct string for a key in debug mode
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testTranslateReturnsTheCorrectStringForAKeyInDebugMode()
    {
        $this->object->setDebug(true);
        $this->assertSame('**Bar**', $this->object->translate('FOO'));
    }

    /**
     * @testdox  Verify that Language::translate() identifies a key as unknown in debug mode
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testTranslateIdentifiesAKeyAsUnknownInDebugMode()
    {
        $this->object->setDebug(true);
        $this->assertSame('??BAR??', $this->object->translate('BAR'));
    }

    /**
     * @testdox  Verify that Language::translate() returns a JavaScript safe string
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testTranslateReturnsAJavascriptSafeKey()
    {
        $this->assertSame('foobar\\\'s', $this->object->translate('foobar\'s', true));
    }

    /**
     * @testdox  Verify that Language::translate() returns a string without backslashes interpreted
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testTranslateReturnsAStringWithoutBackslashesInterpreted()
    {
        $this->assertSame('foobar\\\'s', $this->object->translate('foobar\'s', true, false));
    }

    /**
     * @testdox  Verify that Language::transliterate() calls defined transliterator
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testTransliterateCallsDefinedTransliterator()
    {
        $this->assertSame('Así', $this->object->transliterate('Así'));
    }

    /**
     * @testdox  Verify that Language::getPluralSuffixes() calls the defined method
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testGetPluralSuffixesCallsTheDefinedMethod()
    {
        $this->assertIsArray($this->object->getPluralSuffixes(1));
    }

    /**
     * @testdox  Verify that Language::exists() proxies to LanguageHelper::exists()
     *
     * @covers   Joomla\Language\Language
     * @covers   Joomla\Language\LanguageHelper::exists
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyExistsProxiesToLanguageHelper()
    {
        $this->assertTrue($this->object->exists('en-GB', $this->testPath));
    }

    /**
     * @testdox  Verify that Language::load() successfully loads the main language file
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyLoadSuccessfullyLoadsTheMainLanguageFile()
    {
        $this->assertTrue($this->object->load());
    }

    /**
     * @testdox  Verify that Language::load() successfully loads a language file
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyLoadSuccessfullyLoadsALanguageFile()
    {
        $this->assertTrue($this->object->load('good'));
    }

    /**
     * @testdox  Verify that Language::load() fails to load an extension language file with errors
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyLoadFailsToLoadAnExtensionLanguageFileWithErrors()
    {
        $this->assertFalse($this->object->load('bad'));
    }

    /**
     * @testdox  Verify that Language::load() successfully loads a language file
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyLoadLanguageSuccessfullyLoadsALanguageFile()
    {
        $this->assertTrue(TestHelper::invoke($this->object, 'loadLanguage', $this->testPath . '/good.ini'));
    }

    /**
     * @testdox  Verify that Language::parse() successfully parses a language file
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyParseSuccessfullyParsesALanguageFile()
    {
        $this->assertNotEmpty(TestHelper::invoke($this->object, 'parse', $this->testPath . '/good.ini'));
    }

    /**
     * @testdox  Verify that Language::parse() successfully parses a language file in debug mode
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyParseSuccessfullyParsesALanguageFileInDebugMode()
    {
        $this->object->setDebug(true);

        $this->assertNotEmpty(TestHelper::invoke($this->object, 'parse', $this->testPath . '/good.ini'));
    }

    /**
     * @testdox  Verify that Language::parse() fails to parse a language file with errors
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyParseFailsToParseALanguageFileWithErrors()
    {
        $this->assertEmpty(TestHelper::invoke($this->object, 'parse', $this->testPath . '/bad.ini'));
    }

    /**
     * @testdox  Verify that Language::parse() fails to parse a language file with errors in debug mode
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyParseFailsToParseALanguageFileWithErrorsInDebugMode()
    {
        $this->object->setDebug(true);

        $this->assertEmpty(TestHelper::invoke($this->object, 'parse', $this->testPath . '/bad.ini'));
    }

    /**
     * @testdox  Verify that Language::debugFile() finds no errors in a good file
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyDebugFileFindsNoErrorsInAGoodFile()
    {
        $this->assertSame(0, $this->object->debugFile($this->testPath . '/good.ini'));
    }

    /**
     * @testdox  Verify that Language::debugFile() finds errors in a bad file
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyDebugFileFindsErrorsInABadFile()
    {
        $this->assertGreaterThan(0, $this->object->debugFile($this->testPath . '/bad.ini'));
    }

    /**
     * @testdox  Verify that Language::get() returns the correct metadata
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetReturnsTheCorrectMetadata()
    {
        $this->assertEquals('en-GB', $this->object->get('tag'));
    }

    /**
     * @testdox  Verify that Language::get() returns the default if metadata does not exist
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetReturnsTheDefaultIfMetadataDoesNotExist()
    {
        $this->assertEquals('default', $this->object->get('doesnotexist', 'default'));
    }

    /**
     * @testdox  Verify that Language::getBasePath() returns the correct path
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetBasePathReturnsTheCorrectPath()
    {
        $this->assertSame($this->testPath, $this->object->getBasePath());
    }

    /**
     * @testdox  Verify that Language::getCallerInfo() returns an array
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyGetCallerInfoReturnsAnArray()
    {
        $this->assertIsArray(TestHelper::invoke($this->object, 'getCallerInfo'));
    }

    /**
     * @testdox  Verify that Language::getName() returns the correct metadata
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetNameReturnsTheCorrectMetadata()
    {
        $this->assertSame('English (United Kingdom)', $this->object->getName());
    }

    /**
     * @testdox  Verify that Language::getPaths() default returns an array
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetPathsDefaultReturnsAnArray()
    {
        $this->assertIsArray($this->object->getPaths());
    }

    /**
     * @testdox  Verify that Language::getPaths() returns null for an unloaded extension
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetPathsReturnsNullForAnUnloadedExtension()
    {
        $this->assertNull($this->object->getPaths('good'));
    }

    /**
     * @testdox  Verify that Language::getPaths() returns the extension path for a loaded extension
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetPathsReturnsTheExtensionPathForALoadedExtension()
    {
        $this->object->load('good');

        $this->assertIsArray($this->object->getPaths('good'));
    }

    /**
     * @testdox  Verify that Language::getErrorFiles() default returns an array
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetErrorFilesDefaultReturnsAnArray()
    {
        $this->assertIsArray($this->object->getErrorFiles());
    }

    /**
     * @testdox  Verify that Language::getTag() returns the correct metadata
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetTagReturnsTheCorrectMetadata()
    {
        $this->assertSame('en-GB', $this->object->getTag());
    }

    /**
     * @testdox  Verify that Language::isRTL() default returns false
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatIsRTLDefaultReturnsFalse()
    {
        $this->assertFalse($this->object->isRTL());
    }

    /**
     * @testdox  Verify that Language::setDebug() returns the previous debug state
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatSetDebugReturnsThePreviousDebugState()
    {
        $this->assertFalse($this->object->setDebug(true));
    }

    /**
     * @testdox  Verify that Language::getDebug() default returns false
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetDebugDefaultReturnsFalse()
    {
        $this->assertFalse($this->object->getDebug());
    }

    /**
     * @testdox  Verify that Language::setDefault() returns the previous default language
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatSetDefaultReturnsThePreviousDefaultLanguage()
    {
        $this->assertSame('en-GB', $this->object->setDefault('de-DE'));
    }

    /**
     * @testdox  Verify that Language::getDefault() default returns 'en-GB'
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyTheDefaultReturnForGetDefault()
    {
        $this->assertSame('en-GB', $this->object->getDefault());
    }

    /**
     * @testdox  Verify that Language::getOrphans() default returns an array
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetOrphansDefaultReturnsAnArray()
    {
        $this->assertIsArray($this->object->getOrphans());
    }

    /**
     * @testdox  Verify that Language::getUsed() default returns an array
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatGetUsedDefaultReturnsAnArray()
    {
        $this->assertIsArray($this->object->getUsed());
    }

    /**
     * @testdox  Verify that Language::hasKey() returns false for a non-existing language key
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyThatHasKeyReturnsFalseForANonExistingLanguageKey()
    {
        $this->assertFalse($this->object->hasKey('com_admin.key'));
    }

    /**
     * @testdox  Verify that Language::getMetadata() proxies to LanguageHelper::getMetadata()
     *
     * @covers   Joomla\Language\Language
     * @covers   Joomla\Language\LanguageHelper::getMetadata
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyGetMetadataProxiesToLanguageHelper()
    {
        $this->assertIsArray($this->object->getMetadata('en-GB', $this->testPath));
    }

    /**
     * @testdox  Verify that Language::getKnownLanguages() proxies to LanguageHelper::getKnownLanguages()
     *
     * @covers   Joomla\Language\Language
     * @covers   Joomla\Language\LanguageHelper::getKnownLanguages
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyGetKnownLanguagesProxiesToLanguageHelper()
    {
        $this->assertArrayHasKey('en-GB', $this->object->getKnownLanguages($this->testPath));
    }

    /**
     * @testdox  Verify that Language::getLanguagePath() proxies to LanguageHelper::getLanguagePath()
     *
     * @covers   Joomla\Language\Language
     * @covers   Joomla\Language\LanguageHelper::getLanguagePath
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyGetLanguagePathProxiesToLanguageHelper()
    {
        $this->assertSame($this->testPath . '/language', $this->object->getLanguagePath($this->testPath));
    }

    /**
     * @testdox  Verify that Language::getLanguage() default returns 'en-GB'
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyTheDefaultReturnForGetLanguage()
    {
        $this->assertSame('en-GB', $this->object->getLanguage());
    }

    /**
     * @testdox  Verify that Language::getLocale() default returns an array
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyTheDefaultReturnForGetLocale()
    {
        $this->assertIsArray($this->object->getLocale());
    }

    /**
     * @testdox  Verify that Language::getFirstDay() default returns an integer for the first day of the week
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyTheDefaultReturnForGetFirstDay()
    {
        $this->assertSame(0, $this->object->getFirstDay());
    }

    /**
     * @testdox  Verify that Language::getWeekEnd() default returns an array
     *
     * @covers   Joomla\Language\Language
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyTheDefaultReturnForGetWeekEnd()
    {
        $this->assertSame('0,6', $this->object->getWeekEnd());
    }

    /**
     * @testdox  Verify that Language::parseLanguageFiles() proxies to LanguageHelper::parseLanguageFiles()
     *
     * @covers   Joomla\Language\Language
     * @covers   Joomla\Language\LanguageHelper::parseLanguageFiles
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyParseLanguageFilesProxiesToLanguageHelper()
    {
        $this->assertIsArray($this->object->parseLanguageFiles($this->testPath));
    }

    /**
     * @testdox  Verify that Language::parseXMLLanguageFile() proxies to LanguageHelper::parseXMLLanguageFile()
     *
     * @covers   Joomla\Language\Language
     * @covers   Joomla\Language\LanguageHelper::parseXMLLanguageFile
     * @uses     Joomla\Language\LanguageFactory
     * @uses     Joomla\Language\LanguageHelper
     * @uses     Joomla\Language\MessageCatalogue
     * @uses     Joomla\Language\ParserRegistry
     * @uses     Joomla\Language\Parser\IniParser
     */
    public function testVerifyParseXMLLanguageFileProxiesToLanguageHelper()
    {
        $this->assertIsArray($this->object->parseXMLLanguageFile($this->testPath . '/language/en-GB/en-GB.xml'));
    }
}
