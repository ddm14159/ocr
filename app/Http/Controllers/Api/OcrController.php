<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OcrSpaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OcrController extends Controller
{
    public function parse(Request $request, OcrSpaceService $ocrService)
    {

        if (!$request->has('params')) {
            return '';
        }

        /*$request->validate([
            'image' => 'required|url'
        ]);*/

        $params = $request->get('params');
        $rectangles = $ocrService->createRectangles($params['coordinates'] ?? []);

        if ($rectangles === []) {
            throw new \Exception('Выберите область распознавания');
        }

        $file = $params['image'];
        $fileName = $params['name'];
        $imgSource = file_get_contents($file);

        if (!$imgSource) {
            Log::channel('ocr')->error('Не удалось прочитать изображение');
            throw new \Exception('Ошибка при распознавании изображения.');
        }

        $img = imagecreatefromstring($imgSource);
        if (!$img) {
            Log::channel('ocr')->error('Ошибка при создании GdImage');
            throw new \Exception('Ошибка при распознавании изображения.');
        }

        $parsedData = [];
        foreach ($rectangles as $rectangle) {
            try {
                $croppedImg = $ocrService->getCroppedContent($img, $rectangle);
                $parsedData[] = $ocrService->make($fileName, $croppedImg);
            } catch (\Exception $e) {
                Log::channel('ocr')->error($e->getMessage());
                return $e->getMessage();
            }
        }

        return json_encode(['text' => $parsedData]);
    }
}
