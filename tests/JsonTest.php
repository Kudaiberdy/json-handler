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
     * @return void
     */
    public function testValidate(): void
    {
        $validJson = file_get_contents(__DIR__ . '/fixtures/jsonValidate/validJson.json');
        $invalidJson = file_get_contents(__DIR__ . '/fixtures/jsonValidate/invalidJson.json');
        $rules = ['name', 'phone', 'country', 'region', 'numberrange', 'email'];
        $this->assertTrue(Json::validate($validJson, $rules));
        $this->assertFalse(Json::validate($invalidJson, $rules));
    }

    /**
     * @return array
     */
    public function additionProvider(): array
    {
        return [
            [
                "Anime",
                __DIR__ . '/fixtures/findInJson/testFile.json',
                __DIR__ . '/fixtures/findInJson/expected/expectAnime.json'
            ],
            [
                "testWord",
                __DIR__ . '/fixtures/findInJson/testFile.json',
                __DIR__ . '/fixtures/findInJson/expected/expectTest.json'
            ],
            [
                "Drama",
                __DIR__ . '/fixtures/findInJson/testFile.json',
                __DIR__ . '/fixtures/findInJson/expected/expectDrama.json'
            ]
        ];
    }
}
