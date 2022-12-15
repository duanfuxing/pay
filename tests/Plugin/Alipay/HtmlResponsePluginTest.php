<?php

namespace duan617\Pay\Tests\Plugin\Alipay;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use duan617\Pay\Plugin\Alipay\HtmlResponsePlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class HtmlResponsePluginTest extends TestCase
{
    private $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new HtmlResponsePlugin();
    }

    public function testRedirect()
    {
        $rocket = new Rocket();
        $rocket->setRadar(new Request('GET', 'https://duan617.cn'))
                ->setPayload(new Collection(['name' => 'duan617']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertInstanceOf(ResponseInterface::class, $result->getDestination());
        self::assertArrayHasKey('Location', $result->getDestination()->getHeaders());
        self::assertEquals('https://duan617.cn?name=duan617', $result->getDestination()->getHeaderLine('Location'));
    }

    public function testRedirectIncludeMark()
    {
        $rocket = new Rocket();
        $rocket->setRadar(new Request('GET', 'https://duan617.cn?charset=utf8'))
            ->setPayload(new Collection(['name' => 'duan617']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertInstanceOf(ResponseInterface::class, $result->getDestination());
        self::assertArrayHasKey('Location', $result->getDestination()->getHeaders());
        self::assertEquals('https://duan617.cn?charset=utf8&name=duan617', $result->getDestination()->getHeaderLine('Location'));
    }

    public function testHtml()
    {
        $rocket = new Rocket();
        $rocket->setRadar(new Request('POST', 'https://duan617.cn'))
            ->setPayload(new Collection(['name' => 'duan617']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = (string) $result->getDestination()->getBody();

        self::assertInstanceOf(ResponseInterface::class, $result->getDestination());
        self::assertStringContainsString('alipay_submit', $contents);
        self::assertStringContainsString('duan617', $contents);
    }
}
