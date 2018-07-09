<?php
declare(strict_types=1);
namespace Viserio\Component\Exception\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Viserio\Component\Container\Container;
use Viserio\Component\Exception\Displayer\HtmlDisplayer;
use Viserio\Component\Exception\Displayer\JsonApiDisplayer;
use Viserio\Component\Exception\Displayer\JsonDisplayer;
use Viserio\Component\Exception\Displayer\SymfonyDisplayer;
use Viserio\Component\Exception\Displayer\ViewDisplayer;
use Viserio\Component\Exception\Displayer\WhoopsPrettyDisplayer;
use Viserio\Component\Exception\Filter\CanDisplayFilter;
use Viserio\Component\Exception\Filter\ContentTypeFilter;
use Viserio\Component\Exception\Filter\VerboseFilter;
use Viserio\Component\Exception\Http\Handler;
use Viserio\Component\Exception\Provider\HttpExceptionServiceProvider;
use Viserio\Component\Filesystem\Provider\FilesServiceProvider;
use Viserio\Component\HttpFactory\Provider\HttpFactoryServiceProvider;
use Viserio\Component\Log\Provider\LoggerServiceProvider;
use Viserio\Component\View\Provider\ViewServiceProvider;

/**
 * @internal
 */
final class HttpExceptionServiceProviderTest extends TestCase
{
    public function testProvider(): void
    {
        $container = new Container();
        $container->register(new HttpExceptionServiceProvider());
        $container->register(new ViewServiceProvider());
        $container->register(new FilesServiceProvider());
        $container->register(new LoggerServiceProvider());
        $container->register(new HttpFactoryServiceProvider());
        $container->instance('config', [
            'viserio' => [
                'exception' => [
                    'env'   => 'dev',
                    'debug' => false,
                    'http'  => [
                        'default_displayer' => '',
                    ],
                ],
                'view' => [
                    'paths' => [],
                ],
                'log' => [
                    'env' => 'dev',
                ],
            ],
        ]);

        static::assertInstanceOf(HtmlDisplayer::class, $container->get(HtmlDisplayer::class));
        static::assertInstanceOf(JsonDisplayer::class, $container->get(JsonDisplayer::class));
        static::assertInstanceOf(JsonApiDisplayer::class, $container->get(JsonApiDisplayer::class));
        static::assertInstanceOf(SymfonyDisplayer::class, $container->get(SymfonyDisplayer::class));
        static::assertInstanceOf(ViewDisplayer::class, $container->get(ViewDisplayer::class));
        static::assertInstanceOf(WhoopsPrettyDisplayer::class, $container->get(WhoopsPrettyDisplayer::class));

        static::assertInstanceOf(VerboseFilter::class, $container->get(VerboseFilter::class));
        static::assertInstanceOf(CanDisplayFilter::class, $container->get(CanDisplayFilter::class));
        static::assertInstanceOf(ContentTypeFilter::class, $container->get(ContentTypeFilter::class));

        static::assertInstanceOf(Handler::class, $container->get(Handler::class));
    }
}
