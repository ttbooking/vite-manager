<?php

namespace TTBooking\ViteManager\Tests\Unit;

use Illuminate\Support\Facades\Blade;
use TTBooking\ViteManager\Tests\TestCase;

class BladeHelperTest extends TestCase
{
    public function testEchosAreCompiled(): void
    {
        $this->assertSame('<?php echo app(\'vite\')->app()->toHtml(); ?>', Blade::compileString('@viteApp'));
        $this->assertSame('<?php echo app(\'vite\')->app()->toHtml(); ?>', Blade::compileString('@viteApp()'));
        $this->assertSame('<?php echo app(\'vite\')->app(\'app\')->toHtml(); ?>', Blade::compileString('@viteApp(\'app\')'));
    }
}
