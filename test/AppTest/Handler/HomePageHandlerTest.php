<?php

declare(strict_types=1);

namespace AppTest\Handler;

use App\Handler\HomePageHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Mezzio\Router\RouterInterface;
use Mezzio\Template\TemplateRendererInterface;
use Override;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class HomePageHandlerTest extends TestCase
{
    protected RouterInterface&MockObject $router;

    #[Override]
    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
    }

    public function testReturnsHtmlResponseWhenTemplateRendererProvided(): void
    {
        $template = $this->createMock(TemplateRendererInterface::class);
        $template
            ->expects($this->once())
            ->method('render')
            ->with('app::parcel-tracker-search-form', $this->isType('array'))
            ->willReturn('');

        $homePage = new HomePageHandler($template);

        $response = $homePage->handle(
            $this->createMock(ServerRequestInterface::class)
        );

        self::assertInstanceOf(HtmlResponse::class, $response);
    }
}
