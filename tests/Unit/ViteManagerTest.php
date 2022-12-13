<?php

namespace TTBooking\ViteManager\Tests\Unit;

use ReflectionObject;
use TTBooking\ViteManager\Facades\Vite;
use TTBooking\ViteManager\Tests\TestCase;

class ViteManagerTest extends TestCase
{
    public function testItCreatesInstances()
    {
        $this->assertSame(Vite::app(), Vite::app());
        $this->assertSame(Vite::app('app1'), Vite::app('app1'));
        $this->assertNotSame(Vite::app('app2'), Vite::app('app3'));
    }

    public function testItCanBeConfiguredWithArray()
    {
        $config = [
            'nonce' => 'expected-nonce',
            'integrity_key' => 'some-integrity-key',
            'entry_points' => ['resources/js/app.js'],
            'hot_file' => 'cold',
            'build_directory' => 'build/packages',
            'manifest_filename' => 'oktoberfest.json',
            'tag_attributes' => [
                'script' => ['type' => 'text/javascript', 'nomodule'],
                'style' => ['type' => 'text/css'],
                'preload' => ['rel' => 'dummy'],
            ],
        ];

        $vite = Vite::configure($config);

        $this->assertEquals($vite->cspNonce(), $config['nonce']);
        $this->assertEquals($this->getViteProperty($vite, 'integrityKey'), $config['integrity_key']);
        $this->assertEquals($this->getViteProperty($vite, 'entryPoints'), $config['entry_points']);
        $this->assertEquals($vite->hotFile(), $config['hot_file']);
        $this->assertEquals($this->getViteProperty($vite, 'buildDirectory'), $config['build_directory']);
        $this->assertEquals($this->getViteProperty($vite, 'manifestFilename'), $config['manifest_filename']);
        $this->assertEquals($this->getViteProperty($vite, 'scriptTagAttributesResolvers')[0](), $config['tag_attributes']['script']);
        $this->assertEquals($this->getViteProperty($vite, 'styleTagAttributesResolvers')[0](), $config['tag_attributes']['style']);
        $this->assertEquals($this->getViteProperty($vite, 'preloadTagAttributesResolvers')[0](), $config['tag_attributes']['preload']);
    }

    protected function getViteProperty($vite, $propertyName)
    {
        $property = (new ReflectionObject($vite))->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($vite);
    }
}
