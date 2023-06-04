<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OcrSpaceService
{
    private const URL = 'https://api.ocr.space/parse/image';
    private const API_KEY = 'K82799867888957';
    private const ENGINE_VERSION = 2;
    private const REQUEST_PARAMS = [
        'apikey' => self::API_KEY,
        'OCREngine' => self::ENGINE_VERSION,
    ];
    private const TMP_IMAGE_PATH = '../storage/files/tmp.png';

    /**
     * @param string $imgName
     * @param string $imgData
     * @return array
     * @throws \Exception
     */
    public function make(string $imgName, string $imgData): array
    {
        $response = Http::attach('file', $imgData, $imgName)
            ->post(self::URL, self::REQUEST_PARAMS);

        $data = json_decode($response->body(), true);
        Log::channel('ocr')->info($data);

        if ($data['IsErroredOnProcessing']) {
            throw new \Exception('Ошибка при распознавании изображения.');
        }

        $lines = $data['ParsedResults'][0]['TextOverlay']['Lines'] ?? [];
        if ($lines === []) {
            throw new \Exception('Не удалось распознать изображение.');
        }

        return array_map(fn($line) => $line['LineText'], $lines);

    }

    /**
     * @param array $coordinates
     * @return array
     */
    public function createRectangles(array $coordinates): array
    {
        $rectangles = [];

        foreach ($coordinates as $coordinate) {
            $rectangles[] = [
                'x' => $coordinate[0],
                'y' => $coordinate[1],
                'width' => $coordinate[2],
                'height' => $coordinate[3]
            ];
        }

        return $rectangles;
    }

    /**
     * @param \GdImage $img
     * @param array $rectangle
     * @return string
     * @throws \Exception
     */
    public function getCroppedContent(\GdImage $img, array $rectangle): string
    {
        $croppedImg = imagecrop($img, $rectangle);

        if (!$croppedImg) {
            throw new \Exception('Ошибка при распознавании изображения.');
        }

        if (!imagepng($croppedImg, self::TMP_IMAGE_PATH)) {
            throw new \Exception('Ошибка при распознавании изображения.');
        }

        return file_get_contents(self::TMP_IMAGE_PATH);
    }
}
