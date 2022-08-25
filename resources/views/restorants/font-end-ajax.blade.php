<?php
    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     }
?>

@if ($data['sub_success'])
    <div class="category_bradecumn">
        <button class="getChildrenItems btn btn-info {{ $data['back-btn-off'] }}" data-back="true" data-parent="{{ $data['parent_id'] }}" data-category="{{ $data['category_id'] }}" data-cat_name="{{ $data['category-name'] }}" data-key="{{ $data['key'] }}">Back</button>
    </div>

@endif

<div class="sub-categories-wrp {{ clean(str_replace(' ', '', strtolower($data['category-name'])).strval($data['key'])) }}">
    @if ($data['sub-categories']->count() > 0)
        <div class="sub-category-title mb-2">
            {{ __("Sub Categories: ") }}
        </div>
    @endif
    <div class="row">
        @if ($data['sub-categories']->count() > 0)
            @foreach ($data['sub-categories'] as $singleItem)
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 sub-category-item">
                    <div class="strip h-100 getChildrenItems" data-category="{{ $data['category_id'] }}" data-parent="{{ $singleItem['id'] }}" data-cat_name="{{ $data['category-name'] }}" data-key="{{ $data['key'] }}">
                        @if(!empty($singleItem['image']))
                        <figure>
                            <a href="javascript:void(0)"><img src="{{ $singleItem['logom'] }}" loading="lazy" class="img-fluid lazy" alt=""></a>
                        </figure>
                        @endif
                        <div class="res_title"><b><a href="javascript:void(0)">{{ $singleItem['sub_category_name'] }}</a></b></div>
                        <div class="res_description">{{ $singleItem['sub_category_description']}}</div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<div class="category-wrp {{ clean(str_replace(' ', '', strtolower($data['category-name'])).strval($data['key'])) }}">
    @if ($data['items']->count() > 0)
        <div class="sub-category-title mb-2 text-right">
            {{ __("Items: ") }}
        </div>
    @endif
    <div class="row">
        @if ($data['items']->count() > 0)
            @foreach ($data['items'] as $item)
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="strip">
                        @if(!empty($item['image']))
                        <figure>
                            <a onClick="setCurrentItem({{ $item['id'] }})" href="javascript:void(0)"><img src="{{ $item['logom'] }}" loading="lazy" data-src="{{ config('global.restorant_details_image') }}" class="img-fluid lazy" alt=""></a>
                        </figure>
                        @endif
                        <div class="res_title"><b><a onClick="setCurrentItem({{ $item['id'] }})" href="javascript:void(0)">{{ $item['name'] }}</a></b></div>
                        <div class="res_description">{{ $item['short_description']}}</div>
                        <div class="row">
                            <div class="col-4"><div class="res_mimimum">@money($item['price'], config('settings.cashier_currency'),config('settings.do_convertion'))</div></div>
                            <div class="col-8">
                                <div class="allergens" style="text-align: right;">
                                    @foreach ($data['allergens'] as $allergen)
                                    <div class='allergen' data-toggle="tooltip" data-placement="bottom" title="{{$allergen['title']}}" >
                                        <img  src="{{$allergen['image_link']}}" />
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
                {!! "<div class='text-center font-weight-bold m-auto' style='margin-bottom:70px !important'>No Items Found!</div>" !!}
        @endif
    </div>
</div>
