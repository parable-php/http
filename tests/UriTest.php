<?php declare(strict_types=1);

namespace Parable\Http\Tests;

use Parable\Http\Uri;

class UriTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    protected $uri;

    public function setUp()
    {
        parent::setUp();

        $this->uri = new Uri('https://user:pass@devvoh.com:1337/parable?doc=intro#yes');
    }

    public function testGetUriBuildsTheRightUri()
    {
        self::assertSame('https://user:pass@devvoh.com:1337/parable?doc=intro#yes', $this->uri->getUriString());
    }

    public function testUriToString()
    {
        self::assertSame('https://user:pass@devvoh.com:1337/parable?doc=intro#yes', (string)$this->uri);
    }

    public function testGetScheme()
    {
        self::assertSame('https', $this->uri->getScheme());
    }

    public function testGetUser()
    {
        self::assertSame('user', $this->uri->getUser());
    }

    public function testGetPass()
    {
        self::assertSame('pass', $this->uri->getPass());
    }

    public function testGetHost()
    {
        self::assertSame('devvoh.com', $this->uri->getHost());
    }

    public function testGetPort()
    {
        self::assertSame(1337, $this->uri->getPort());
    }

    public function testGetPath()
    {
        self::assertSame('parable', $this->uri->getPath());
    }

    public function testGetQuery()
    {
        self::assertSame('doc=intro', $this->uri->getQuery());
    }

    public function testGetQueryArray()
    {
        self::assertSame(
            [
                'doc' => 'intro',
            ],
            $this->uri->getQueryArray()
        );
    }

    public function testGetQueryArrayEmptyIfNoQuery()
    {
        $uri = new Uri('https://devvoh.com');

        self::assertSame([], $uri->getQueryArray());
    }

    public function testGetFragment()
    {
        self::assertSame('yes', $this->uri->getFragment());
    }

    public function testWithScheme()
    {
        $uri = new Uri('http://devvoh.com');

        self::assertSame('http', $uri->getScheme());

        $uri = $uri->withScheme('https');

        self::assertSame('https', $uri->getScheme());

        self::assertSame('https://devvoh.com', (string)$uri);
    }

    public function testWithUser()
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getUser());

        $uri = $uri->withUser('user');

        self::assertSame('user', $uri->getUser());

        self::assertSame('http://user@devvoh.com', (string)$uri);
    }

    public function testWithUserAndPass()
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getUser());
        self::assertNull($uri->getPass());

        $uri = $uri->withUser('user');
        $uri = $uri->withPass('pass');

        self::assertSame('user', $uri->getUser());
        self::assertSame('pass', $uri->getPass());

        self::assertSame('http://user:pass@devvoh.com', (string)$uri);
    }

    public function testWithPassWithoutUser()
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getUser());
        self::assertNull($uri->getPass());

        $uri = $uri->withPass('pass');

        self::assertNull($uri->getUser());
        self::assertSame('pass', $uri->getPass());

        self::assertSame('http://devvoh.com', (string)$uri);
    }

    public function testWithHost()
    {
        $uri = new Uri('http://devvoh.com');

        self::assertSame('devvoh.com', $uri->getHost());

        $uri = $uri->withHost('github.com');

        self::assertSame('github.com', $uri->getHost());

        self::assertSame('http://github.com', (string)$uri);
    }

    public function testWithPort()
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getPort());

        $uri = $uri->withPort(8080);

        self::assertSame(8080, $uri->getPort());

        self::assertSame('http://devvoh.com:8080', (string)$uri);
    }

    public function testWithPath()
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getPath());

        $uri = $uri->withPath('parable-doc');

        self::assertSame('parable-doc', $uri->getPath());

        self::assertSame('http://devvoh.com/parable-doc', (string)$uri);
    }

    public function testWithQuery()
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getQuery());

        $uri = $uri->withQuery('anything-goes-here');

        self::assertSame('anything-goes-here', $uri->getQuery());

        self::assertSame('http://devvoh.com?anything-goes-here', (string)$uri);
    }

    public function testWithQueryArray()
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getQuery());

        $uri = $uri->withQueryArray([
            'test' => 'true',
        ]);

        self::assertSame('test=true', $uri->getQuery());

        self::assertSame('http://devvoh.com?test=true', (string)$uri);
    }

    public function testWithFragment()
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getFragment());

        $uri = $uri->withFragment('intro-here');

        self::assertSame('intro-here', $uri->getFragment());

        self::assertSame('http://devvoh.com#intro-here', (string)$uri);
    }
}
