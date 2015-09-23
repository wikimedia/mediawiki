<?php
namespace JsonSchema\Tests\Uri;

use JsonSchema\Uri\UriResolver;

class UriResolverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->resolver = new UriResolver();
    }

    public function testParse()
    {
        $this->assertEquals(
            array(
                'scheme'    => 'http',
                'authority' => 'example.org',
                'path'      => '/path/to/file.json'
            ),
            $this->resolver->parse('http://example.org/path/to/file.json')
        );
    }

    public function testParseAnchor()
    {
        $this->assertEquals(
            array(
                'scheme'    => 'http',
                'authority' => 'example.org',
                'path'      => '/path/to/file.json',
                'query'     => '',
                'fragment'  => 'foo'
            ),
            $this->resolver->parse('http://example.org/path/to/file.json#foo')
        );
    }

    public function testCombineRelativePathWithBasePath()
    {
        $this->assertEquals(
            '/foo/baz.json',
            UriResolver::combineRelativePathWithBasePath(
                'baz.json',
                '/foo/bar.json'
            )
        );
    }

    public function testCombineRelativePathWithBasePathAbsolute()
    {
        $this->assertEquals(
            '/baz/data.json',
            UriResolver::combineRelativePathWithBasePath(
                '/baz/data.json',
                '/foo/bar.json'
            )
        );
    }

    public function testCombineRelativePathWithBasePathRelativeSub()
    {
        $this->assertEquals(
            '/foo/baz/data.json',
            UriResolver::combineRelativePathWithBasePath(
                'baz/data.json',
                '/foo/bar.json'
            )
        );
    }

    public function testCombineRelativePathWithBasePathNoPath()
    {
        //needed for anchor-only urls
        $this->assertEquals(
            '/foo/bar.json',
            UriResolver::combineRelativePathWithBasePath(
                '',
                '/foo/bar.json'
            )
        );
    }

    public function testResolveAbsoluteUri()
    {
        $this->assertEquals(
            'http://example.org/foo/bar.json',
            $this->resolver->resolve(
                'http://example.org/foo/bar.json',
                null
            )
        );
    }

    /**
     * @expectedException JsonSchema\Exception\UriResolverException
     */
    public function testResolveRelativeUriNoBase()
    {
        $this->assertEquals(
            'http://example.org/foo/bar.json',
            $this->resolver->resolve(
                'bar.json',
                null
            )
        );
    }

    public function testResolveRelativeUriBaseDir()
    {
        $this->assertEquals(
            'http://example.org/foo/bar.json',
            $this->resolver->resolve(
                'bar.json',
                'http://example.org/foo/'
            )
        );
    }

    public function testResolveRelativeUriBaseFile()
    {
        $this->assertEquals(
            'http://example.org/foo/bar.json',
            $this->resolver->resolve(
                'bar.json',
                'http://example.org/foo/baz.json'
            )
        );
    }

    public function testResolveAnchor()
    {
        $this->assertEquals(
            'http://example.org/foo/bar.json#baz',
            $this->resolver->resolve(
                '#baz',
                'http://example.org/foo/bar.json'
            )
        );
    }

    public function testResolveAnchorWithFile()
    {
        $this->assertEquals(
            'http://example.org/foo/baz.json#baz',
            $this->resolver->resolve(
                'baz.json#baz',
                'http://example.org/foo/bar.json'
            )
        );
    }
    public function testResolveAnchorAnchor()
    {
        $this->assertEquals(
            'http://example.org/foo/bar.json#bazinga',
            $this->resolver->resolve(
                '#bazinga',
                'http://example.org/foo/bar.json#baz'
            )
        );
    }

    public function testResolveEmpty()
    {
        $this->assertEquals(
            'http://example.org/foo/bar.json',
            $this->resolver->resolve(
                '',
                'http://example.org/foo/bar.json'
            )
        );
    }
}
?>
