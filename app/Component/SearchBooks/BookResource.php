<?php
declare(strict_types=1);

namespace App\Component\SearchBooks;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'publisher' =>  $this->publisher()->name,
            'published' => $this->publication_date->format('Y-m-d'),
            'authors' => array_map(
                function ($item) { return $item->name; },
                $this->authors()->getResults()->getDictionary()
            ),
        ];
    }
}

