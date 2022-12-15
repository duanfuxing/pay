<?php

namespace duan617\Pay\Tests\Plugin\Unipay;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use duan617\Pay\Plugin\Unipay\HtmlResponsePlugin;
use duan617\Pay\Rocket;
use duan617\Pay\Tests\TestCase;
use Yansongda\Supports\Collection;

class HtmlResponsePluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new HtmlResponsePlugin();
    }

    public function testHtml()
    {
        $rocket = new Rocket();
        $rocket->setRadar(new Request('POST', 'https://duan617.cn'))
            ->setPayload(new Collection(['name' => 'duan617']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = (string) $result->getDestination()->getBody();

        self::assertInstanceOf(ResponseInterface::class, $result->getDestination());
        self::assertStringContainsString('pay_form', $contents);
        self::assertStringContainsString('duan617', $contents);
    }
}
