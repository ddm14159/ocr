<?php

namespace App\Http\Controllers;

use App\Services\OcrSpaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OcrController extends Controller
{

    public function index(Request $request, OcrSpaceService $ocrService)
    {
        if (!$request->hasFile('image')) {
            return Inertia::render('Index');
        }

        $request->validate([
            'image' => 'required|file|image'
        ]);

        $rectangles = $ocrService->createRectangles($request->get('coordinates') ?? []);
        if ($rectangles === []) {
            throw new \Exception('Выберите область распознавания');
        }

        $file = $request->file('image');
        $fileName = $file->getClientOriginalName();
        $imgSource = file_get_contents($file->getRealPath());

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
                return Inertia::render('Index', ['error' => $e->getMessage()]);
            }
        }

        return Inertia::render('Index', ['text' => $parsedData]);

    }
}
