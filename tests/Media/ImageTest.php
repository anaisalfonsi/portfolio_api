<?php

namespace App\Tests\Media;

/*use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Image;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testCreateImage(): void
    {
        $file = new UploadedFile('fixtures/files/image.png', 'image.png');
        $client = self::createClient();

        $client->request('POST', '/images', [
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                // If you have additional fields in your MediaObject entity, use the parameters.
                'parameters' => [
                    'title' => 'My file uploaded',
                ],
                'files' => [
                    'file' => $file,
                ],
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceItemJsonSchema(Image::class);
        $this->assertJsonContains([
            'title' => 'My file uploaded',
        ]);
    }
} */