<?php
namespace App\Traits;
use Plank\Mediable\Mediable;
trait Media
{
    use Mediable;
    public function media()
    {
        $media = $this->morphToMany(config('mediable.model'), 'mediable')
            ->withPivot('tag', 'order')
            ->orderBy('order');
        if (config('mediable.detach_on_soft_delete') == false) {
            $media->whereNull('deleted_at');
        }
        return $media;
    }
    public function attachMedia($media, $tags): void
    {
        $tags = (array)$tags;
        $increments = $this->getOrderValueForTags($tags);
        $ids = $this->extractPrimaryIds($media);
        foreach ($tags as $tag) {
            $attach = [];
            foreach ($ids as $id) {
                $attach[$id] = [
                    'company_id' => company_id(),
                    'created_from' => source_name(),
                    'created_by' => user_id(),
                    'tag' => $tag,
                    'order' => ++$increments[$tag],
                ];
            }
            $this->media()->attach($attach);
        }
        $this->markMediaDirty($tags);
    }
}
