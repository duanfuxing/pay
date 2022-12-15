<?php

namespace duan617\Pay\Tests\Plugin;

use duan617\Pay\Contract\ParserInterface;
use duan617\Pay\Exception\InvalidConfigException;
use duan617\Pay\Parser\NoHttpRequestParser;
use duan617\Pay\Pay;
use duan617\Pay\Plugin\ParserPlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\Stubs\FooPackerStub;
use duan617\Pay\Tests\TestCase;

class ParserPluginTest extends TestCase
{
    /**
     * @var \duan617\Pay\Plugin\ParserPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ParserPlugin();
    }

    public function testPackerWrong()
    {
        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(InvalidConfigException::INVALID_PACKER);

        $rocket = new Rocket();
        $rocket->setDirection(FooPackerStub::class);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPackerDefault()
    {
        Pay::set(ParserInterface::class, NoHttpRequestParser::class);

        $rocket = new Rocket();

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($rocket, $result);
    }

    public function testPackerObject()
    {
        Pay::set(ParserInterface::class, new NoHttpRequestParser());

        $rocket = new Rocket();

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($rocket, $result);
    }
}
