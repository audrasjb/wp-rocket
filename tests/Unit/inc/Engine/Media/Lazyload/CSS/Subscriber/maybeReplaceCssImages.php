<?php

namespace WP_Rocket\Tests\Unit\inc\Engine\Media\Lazyload\CSS\Subscriber;

use Mockery;
use WP_Rocket\Engine\Common\Cache\FilesystemCache;
use WP_Rocket\Engine\Media\Lazyload\CSS\Front\Extractor;
use WP_Rocket\Engine\Media\Lazyload\CSS\Front\FileResolver;
use WP_Rocket\Engine\Media\Lazyload\CSS\Front\JsonFormatter;
use WP_Rocket\Engine\Media\Lazyload\CSS\Front\RuleFormatter;
use WP_Rocket\Engine\Media\Lazyload\CSS\Front\TagGenerator;
use WP_Rocket\Engine\Media\Lazyload\CSS\Subscriber;
use WP_Filesystem_Direct;

use WP_Rocket\Tests\Unit\TestCase;
use Brain\Monkey\Filters;

/**
 * @covers \WP_Rocket\Engine\Media\Lazyload\CSS\Subscriber::maybe_replace_css_images
 */
class Test_maybeReplaceCssImages extends TestCase {

	/**
	 * @var Extractor
	 */
	protected $extractor;

	/**
	 * @var RuleFormatter
	 */
	protected $rule_formatter;

	/**
	 * @var FileResolver
	 */
	protected $file_resolver;

	/**
	 * @var FilesystemCache
	 */
	protected $filesystem_cache;

	/**
	 * @var WP_Filesystem_Direct
	 */
	protected $filesystem;

	/**
	 * @var JsonFormatter
	 */
	protected $json_formatter;

	/**
	 * @var TagGenerator
	 */
	protected $tag_generator;

	/**
	 * @var Subscriber
	 */
	protected $subscriber;

	public function set_up() {
		parent::set_up();
		$this->extractor = Mockery::mock(Extractor::class);
		$this->rule_formatter = Mockery::mock(RuleFormatter::class);
		$this->file_resolver = Mockery::mock(FileResolver::class);
		$this->filesystem_cache = Mockery::mock(FilesystemCache::class);
		$this->filesystem = Mockery::mock(WP_Filesystem_Direct::class);
		$this->json_formatter = Mockery::mock(JsonFormatter::class);
		$this->tag_generator = Mockery::mock(TagGenerator::class);

		$this->subscriber = new Subscriber($this->extractor, $this->rule_formatter, $this->file_resolver, $this->filesystem_cache, $this->filesystem, $this->json_formatter, $this->tag_generator);
	}

    /**
     * @dataProvider configTestData
     */
    public function testShouldReturnAsExpected( $config, $expected )
    {
		Filters\expectApplied('rocket_generate_lazyloaded_css')->with($expected['data'])->andReturn($config['data']);
        $this->assertSame($expected['output'], $this->subscriber->maybe_replace_css_images($config['html']));
    }
}
