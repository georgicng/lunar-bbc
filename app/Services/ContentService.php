<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Page;
use App\Http\Resources\ProductResource;

class ContentService
{
    protected array $typeMap= [];

    protected array $blockMap = [];
    
    public function __construct() {
        $this->blockMap = [ 
            'features' => function (array $block): array { 
                //logger()->info($block);
                return  collect($block['feature'])->map(fn ($item) => ['title'=> $item['feature_title'],'caption'=> $item['feature_caption']])->toArray();
            },
            'newArrivals' => function (array $block): array { 
                return  [
                    'title' => $block['arrival-title'],
                    'subtitle'=> $block['new-subtitle'],
                    'products' => collect($block['feature'])->map(function ($product) {
                        $product = Product::find($product['new-product']);
                        return ProductResource::make($product);
                    })
                ]; 
            },
        ];

        $this->typeMap = [ 
            'Lunar\\FieldTypes\\Text' => function ($content): string {
                return $content['value']; 
            }, 
        ];
    }

    public function resolveBlocks($blocks)
    {
        //$blocks = json_decode($blocks, true);
        return collect($blocks)->map(function ($block) {
            
            logger()->info($block);
            if (isset($this->blockMap[$block['type']])) {
                return ['type'=> $block['type'], 'data' => ($this->blockMap[$block['type']])($block['data'])]; 
            }
            logger()->info($block);
            return $block; 
        });
    }

    public function resolveMeta($meta)
    {

        return $meta;
        /* return collect($meta)->mapWithKeys(function (\Illuminate\Support\Collection $item, string $key) {
            logger()->info($item);
            return [$key => $this->typeMap[$item['field_type']]($item)];
        }); */
    }

    public function getHome(): array
    {
        $page = Page::find(1);
        return [
            'meta' => $this->resolveMeta([$page->attribute_data]),
            'blocks' => $this->resolveBlocks($page->blocks),
        ];
    }
}
