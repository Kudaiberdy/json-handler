<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Utilities\Json;

class JsonTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testFindInJson(string $needle, string $haystack, string $expect): void
    {
        $json = file_get_contents($haystack);
        $actual = Json::search($needle, $json);

        $this->assertJsonStringEqualsJsonFile($expect, $actual);
    }

    /**
     * @return array
     */
    public function additionProvider(): array
    {
        return [
            [
                "Anime",
                __DIR__ . '/fixtures/testFile.json',
                __DIR__ . '/fixtures/expected/expectAnime.json'
            ],
            [
                "testWord",
                __DIR__ . '/fixtures/testFile.json',
                __DIR__ . '/fixtures/expected/expectTest.json'
            ],
            [
                "Drama",
                __DIR__ . '/fixtures/testFile.json',
                __DIR__ . '/fixtures/expected/expectDrama.json'
            ]
        ];
    }
}
