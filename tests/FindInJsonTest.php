<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use function App\Source\FindInJson\findInJson;

class FindInJsonTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testFindInJson(string $needle, string $haystack, string $expect): void
    {
        $actual = findInJson($needle, file_get_contents($haystack));
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
