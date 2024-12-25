<?php

namespace App\Tests\Music\Provider;

use App\Music\Provider\Deezer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DeezerTest extends TestCase
{
    readonly private Deezer $deezer;
    readonly private MockHttpClient $httpClient;
    protected function setUp(): void
    {
        $this->httpClient = new MockHttpClient();
        $this->deezer = new Deezer($this->httpClient);
    }

    private function badURLs():array{
        return [
            'empty' => [''],
            'deezer bad URL' => ['https://www.deezer.com/bad_url'],
            'spotify URL' => ['https://open.spotify.com/intl-fr/track/2hsLpiKNkWpd4e9QuVdhar'],
        ];
    }

    /**
     * @dataProvider badURLs
     */
    public function testBadUrls($url): void
    {
        $actual = $this->deezer->url($url);

        self::assertNull($actual);
    }

    private function urls():array{
        return [
            'deezer track URL' => ['https://www.deezer.com/us/track/69838319', 'track', 69838319],
            'deezer track URL with query string' => ['https://www.deezer.com/us/track/69838319?host=7544282&utm_campaign=clipboard-generic&utm_source=user_sharing&utm_content=album-596777432&deferredFl=1', 'track', 69838319],
            'deezer album URL' => ['https://www.deezer.com/us/album/596777432', 'album', 596777432],
            'deezer album URL with query string' => ['https://www.deezer.com/us/album/596777432?host=7544282&utm_campaign=clipboard-generic&utm_source=user_sharing&utm_content=album-596777432&deferredFl=1', 'album', 596777432],
            'deezer share track URL' => ['https://deezer.page.link/AqR3WdsSiDn19WaW9', 'track', 69838319],
            'deezer share album URL' => ['https://deezer.page.link/jVqRCZQjj62xwGGR6', 'album', 596777432],
        ];
    }
    /** @dataProvider urls */
    public function testURLs($url, $type, $id): void
    {
        $this->httpClient->setResponseFactory(function ($method, $url, $options) use ($type, $id): MockResponse {

            if (str_contains($url, 'https://deezer.page.link')) {

                return new MockResponse(json_encode([]), [
                    'http_code' => 302,
                    'response_headers' => [
                        'location' => "https://www.deezer.com/$type/$id",
                    ]
                ]);
            }

            $this->assertSame('GET', $method);
            $this->assertSame("https://api.deezer.com/$type/$id", $url);

            return new MockResponse(json_encode([
                'title' => "$type $id",
                'link' => "https://www.deezer.com/XXXXX/$id",
                'type' => $type,
                'album' => ['cover_medium' => "https://www.deezer.com/XXXXX/$id.jpg", 'title' => "album XXX"],
                'artist' => ['name' => 'The artist'],
            ]));
        });

        $actual = $this->deezer->url($url);

        self::assertSame($type, $actual->type);
        self::assertSame("$type $id", $actual->title);
        self::assertSame('The artist', $actual->artist);
    }
}
