<?php

namespace InetStudio\AdminPanel\Controllers\Uploads;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadsController extends Controller
{
    /**
     * Загружаем файл во временную директорию.
     *
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        $transformName = str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $request->get('fieldName'));

        return \Plupload::receive($transformName, function ($file)
        {
            $tempName = Storage::disk('temp')->putFile('', $file, 'public');

            Storage::delete($file->path());

            return [
                'tempName' => $tempName,
                'tempPath' => url(Storage::disk('temp')->url($tempName)),
            ];
        });
    }
}
