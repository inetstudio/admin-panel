<?php

namespace InetStudio\AdminPanel\Services\Front\SEO;

use Arcanedev\SeoHelper\Entities\Title;
use Arcanedev\SeoHelper\Entities\Keywords;
use Arcanedev\SeoHelper\Entities\MiscTags;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Arcanedev\SeoHelper\Entities\Description;
use Arcanedev\SeoHelper\Entities\OpenGraph\Graph;

class SEOService
{
    /**
     * Получаем мета теги страницы.
     *
     * @param $object
     * @return array|mixed
     */
    public function getTags($object): array
    {
        $tags = [];

        if ($object) {
            $cacheKey = 'SEOService_getTags_'.md5(get_class($object).$object->id);

            $tags = \Cache::tags(['seo'])->remember($cacheKey, 1440, function () use ($object) {
                $meta = $object->meta->pluck('value', 'key')->toArray();

                $data = [];

                $data['title'] = (isset($meta['title'])) ? Title::make($meta['title'], '', '')->setLast()->setMax(999) : (($object->title) ? Title::make($object->title, '', '')->setLast()->setMax(999) : '');

                $data['description'] = (isset($meta['description'])) ? Description::make($meta['description'])->setMax(999) : '';

                $data['keywords'] = (isset($meta['keywords'])) ? Keywords::make($meta['keywords']) : '';

                $data['robots'] = (isset($meta['robots'])) ? new MiscTags([
                    'default'   => [
                        'robots' => $meta['robots'],
                    ],
                ]) : '';

                $data['webmasters'] = Webmasters::make([
                    'yandex' => config('services.yandex.webmaster.verification_code'),
                    'google' => config('services.google.webmaster.verification_code'),
                ]);

                $data['openGraph'] = new Graph([
                    'type' => 'website',
                    'site-name' => config('app.name'),
                    'title' => (isset($meta['og:title'])) ? $meta['og:title'] : (($data['title']) ? $data['title']->getTitleOnly() : ''),
                    'description' => (isset($meta['og:description'])) ? $meta['og:description'] : (($data['description']) ? $data['description']->getContent() : ''),
                    'properties' => [
                        'url' => ($object->slug == 'index') ? url('/') : url($object->href),
                        'image' => ($object->hasMedia('og_image')) ? url($object->getFirstMediaUrl('og_image', 'og_image_default')) : (($object->hasMedia('preview')) ? url($object->getFirstMediaUrl('preview', 'preview_3_2')) : ''),
                    ],
                ]);

                return $data;
            });
        }

        $tags['csrf-token'] = $this->getCSRFMeta();

        return $tags;
    }

    /**
     * Возвращаем CSRF мета тег.
     *
     * @return MiscTags
     */
    public function getCSRFMeta()
    {
        return new MiscTags([
            'default' => [
                'csrf-token' => csrf_token(),
            ],
        ]);
    }
}
