<?php declare(strict_types=1);

namespace Parable\Http\Tests;

use Parable\Http\Uri;
use PHPUnit\Framework\TestCase;

class UriTest extends TestCase
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

    public function testGetUriStringBuildsTheRightUri(): void
    {
        self::assertSame('https://user:pass@devvoh.com:1337/parable?doc=intro#yes', $this->uri->getUriString());
    }

    public function testGetUriBaseStringBuildsOnlyTheBase(): void
    {
        self::assertSame('https://user:pass@devvoh.com:1337', $this->uri->getUriBaseString());
    }

    public function testGetUriRestStringBuildsOnlyTheRest(): void
    {
        self::assertSame('parable?doc=intro#yes', $this->uri->getUriRestString());
    }

    public function testUriToString(): void
    {
        self::assertSame('https://user:pass@devvoh.com:1337/parable?doc=intro#yes', (string)$this->uri);
    }

    public function testGetScheme(): void
    {
        self::assertSame('https', $this->uri->getScheme());
    }

    public function testGetUser(): void
    {
        self::assertSame('user', $this->uri->getUser());
    }

    public function testGetPass(): void
    {
        self::assertSame('pass', $this->uri->getPass());
    }

    public function testGetHost(): void
    {
        self::assertSame('devvoh.com', $this->uri->getHost());
    }

    public function testGetPort(): void
    {
        self::assertSame(1337, $this->uri->getPort());
    }

    public function testGetPath(): void
    {
        self::assertSame('parable', $this->uri->getPath());
    }

    public function testGetQuery(): void
    {
        self::assertSame('doc=intro', $this->uri->getQuery());
    }

    public function testGetQueryArray(): void
    {
        self::assertSame(
            [
                'doc' => 'intro',
            ],
            $this->uri->getQueryArray()
        );
    }

    public function testGetQueryArrayEmptyIfNoQuery(): void
    {
        $uri = new Uri('https://devvoh.com');

        self::assertSame([], $uri->getQueryArray());
    }

    public function testGetFragment(): void
    {
        self::assertSame('yes', $this->uri->getFragment());
    }

    public function testWithScheme(): void
    {
        $uri = new Uri('http://devvoh.com');

        self::assertSame('http', $uri->getScheme());

        $uri = $uri->withScheme('https');

        self::assertSame('https', $uri->getScheme());

        self::assertSame('https://devvoh.com', (string)$uri);
    }

    public function testWithUser(): void
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getUser());

        $uri = $uri->withUser('user');

        self::assertSame('user', $uri->getUser());

        self::assertSame('http://user@devvoh.com', (string)$uri);
    }

    public function testWithUserAndPass(): void
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

    public function testWithPassWithoutUser(): void
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getUser());
        self::assertNull($uri->getPass());

        $uri = $uri->withPass('pass');

        self::assertNull($uri->getUser());
        self::assertSame('pass', $uri->getPass());

        self::assertSame('http://devvoh.com', (string)$uri);
    }

    public function testWithHost(): void
    {
        $uri = new Uri('http://devvoh.com');

        self::assertSame('devvoh.com', $uri->getHost());

        $uri = $uri->withHost('github.com');

        self::assertSame('github.com', $uri->getHost());

        self::assertSame('http://github.com', (string)$uri);
    }

    public function testWithPort(): void
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getPort());

        $uri = $uri->withPort(8080);

        self::assertSame(8080, $uri->getPort());

        self::assertSame('http://devvoh.com:8080', (string)$uri);
    }

    public function testWithPath(): void
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getPath());

        $uri = $uri->withPath('parable-doc');

        self::assertSame('parable-doc', $uri->getPath());

        self::assertSame('http://devvoh.com/parable-doc', (string)$uri);
    }

    public function testWithQuery(): void
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getQuery());

        $uri = $uri->withQuery('anything-goes-here');

        self::assertSame('anything-goes-here', $uri->getQuery());

        self::assertSame('http://devvoh.com/?anything-goes-here', (string)$uri);
    }

    public function testWithQueryArray(): void
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getQuery());

        $uri = $uri->withQueryArray([
            'test' => 'true',
        ]);

        self::assertSame('test=true', $uri->getQuery());

        self::assertSame('http://devvoh.com/?test=true', (string)$uri);
    }

    public function testWithFragment(): void
    {
        $uri = new Uri('http://devvoh.com');

        self::assertNull($uri->getFragment());

        $uri = $uri->withFragment('intro-here');

        self::assertSame('intro-here', $uri->getFragment());

        self::assertSame('http://devvoh.com/#intro-here', (string)$uri);
    }

    public function testWithPortNull(): void
    {
        self::assertSame('https://user:pass@devvoh.com/parable?doc=intro#yes', $this->uri->withPort(null)->getUriString());
    }

    public function testWithUserNull(): void
    {
        self::assertSame('https://devvoh.com:1337/parable?doc=intro#yes', $this->uri->withUser(null)->getUriString());
    }

    public function testWithPassNull(): void
    {
        self::assertSame('https://user@devvoh.com:1337/parable?doc=intro#yes', $this->uri->withPass(null)->getUriString());
    }

    public function testWithPathNull(): void
    {
        self::assertSame('https://user:pass@devvoh.com:1337/?doc=intro#yes', $this->uri->withPath(null)->getUriString());
    }

    public function testWithQueryNull(): void
    {
        self::assertSame('https://user:pass@devvoh.com:1337/parable#yes', $this->uri->withQuery(null)->getUriString());
    }

    public function testWithQueryArrayEmpty(): void
    {
        self::assertSame('https://user:pass@devvoh.com:1337/parable#yes', $this->uri->withQueryArray([])->getUriString());
    }

    public function testWithFragmentNull(): void
    {
        self::assertSame('https://user:pass@devvoh.com:1337/parable?doc=intro', $this->uri->withFragment(null)->getUriString());
    }
}
