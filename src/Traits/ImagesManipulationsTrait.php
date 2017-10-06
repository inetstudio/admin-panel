<?php

namespace InetStudio\AdminPanel\Traits;

use Illuminate\Support\Facades\Storage;

trait ImagesManipulationsTrait
{
    /**
     * Сохраняем изображения.
     *
     * @param $item
     * @param $request
     * @param array $images
     */
    private function saveImages($item, $request, $images, $disk)
    {
        foreach ($images as $name) {
            $properties = $request->get($name);

            \Event::fire('inetstudio.images.cache.clear', $name.'_'.md5(get_class($item).$item->id));

            if (isset($properties['images'])) {
                $item->clearMediaCollectionExcept($name, $properties['images']);

                foreach ($properties['images'] as $image) {
                    if ($image['id']) {
                        $media = $item->media->find($image['id']);
                        $media->custom_properties = $image['properties'];
                        $media->save();
                    } else {
                        $filename = $image['filename'];

                        $file = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix().$image['tempname'];

                        $media = $item->addMedia($file)
                            ->withCustomProperties($image['properties'])
                            ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                            ->usingFileName($image['tempname'])
                            ->toMediaCollection($name, $disk);
                    }

                    $item->update([
                        $name => str_replace($image['src'], $media->getFullUrl('content_front'), $item[$name]),
                    ]);
                }
            } else {
                $manipulations = [];

                if (isset($properties['crop']) and config($disk.'.images.conversions')) {
                    foreach ($properties['crop'] as $key => $cropJSON) {
                        $cropData = json_decode($cropJSON, true);

                        foreach (config($disk.'.images.conversions.'.$name.'.'.$key) as $conversion) {

                            \Event::fire('inetstudio.images.cache.clear', $conversion['name'].'_'.md5(get_class($item).$item->id));

                            $manipulations[$conversion['name']] = [
                                'manualCrop' => implode(',', [
                                    round($cropData['width']),
                                    round($cropData['height']),
                                    round($cropData['x']),
                                    round($cropData['y']),
                                ]),
                            ];
                        }
                    }
                }

                if (isset($properties['tempname']) && isset($properties['filename'])) {
                    $image = $properties['tempname'];
                    $filename = $properties['filename'];

                    $item->clearMediaCollection($name);

                    array_forget($properties, ['tempname', 'temppath', 'filename']);
                    $properties = array_filter($properties);

                    $file = Storage::disk('temp')->getDriver()->getAdapter()->getPathPrefix().$image;

                    $media = $item->addMedia($file)
                        ->withCustomProperties($properties)
                        ->usingName(pathinfo($filename, PATHINFO_FILENAME))
                        ->usingFileName($image)
                        ->toMediaCollection($name, $disk);

                    $media->manipulations = $manipulations;
                    $media->save();

                } else {
                    $properties = array_filter($properties);

                    $media = $item->getFirstMedia($name);

                    if ($media) {
                        $media->custom_properties = $properties;
                        $media->manipulations = $manipulations;
                        $media->save();
                    }
                }
            }
        }
    }
}
