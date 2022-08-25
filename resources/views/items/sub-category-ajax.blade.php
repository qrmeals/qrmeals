
@if ($data['sub_success'])
    <div class="category_bradecumn">
        <button class="openSubCategory btn btn-info {{ $data['back-btn-off'] }}" data-back="true" data-parent="{{ $data['parent_id'] }}" data-category="{{ $data['category_id'] }}">Back</button>
        <button class="btn btn-primary btn-sm addNewSubCategory {{ $data['sub-cat-btn-off'] }}" data-toggle="modal" data-target="#modal-add-sub-category" data-modal="modal-add-sub-category" data-category="{{ $data['category_id'] }}" data-restaurant="{{ $data['restaurant_id'] }}" data-parent="{{ $data['parent_id'] }}">Add New Sub Category</button>
    </div>
    <div class="col-lg-12">
        @if ($data['sub-categories']->count() > 0)
            <div class="section-title mb-2">
                {{ __("Sub Categories: ") }}
            </div>
            <div class="row row-grid"> 
                @foreach ($data['sub-categories'] as $singleItem)
                    <div class="col-lg-3">
                        <a href="javascript:void(0)" class="openSubCategory" id="view" data-parent="{{ $singleItem['id'] }}" data-category="{{ $data['category_id'] }}">
                            <div class="card h-100">
                                <img class="card-img-top" src="{{ $singleItem['logom'] }}" alt="...">
                                <div class="sub-category-dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" href="javascript:void(0)" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        <a class="dropdown-item addNewSubCategory" href="javascript:void(0)" data-toggle="modal" data-target="#modal-add-sub-category" data-category="{{ $data['category_id'] }}" data-restaurant="{{ $data['restaurant_id'] }}" data-parent="{{ $singleItem['id'] }}" data-modal="modal-add-sub-category">{{ __('Add New Subcategory') }}</a>
                                        <a class="dropdown-item addNewItem" href="javascript:void(0)" data-category="{{ $data['category_id'] }}" data-parent="{{ $singleItem['id'] }}" data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top">{{ __('Add Item') }}</a>
                                        <a class="dropdown-item subCategoryEdit" href="javascript:void(0)" data-toggle="modal" data-target="#modal-edit-sub-category" data-toggle="tooltip" data-placement="top" data-sub="{{ $singleItem['id'] }}">{{ __('Edit') }}</a>
                                        <a class="dropdown-item warning red subCategoryDelete" data-sub="{{ $singleItem['id'] }}" data-category="{{ $data['category_id'] }}"  href="javascript:void(0)">{{ __('Delete') }}</a>
                                    </div>
                                </div>
                                <div class="card-body openSubCategory" data-parent="{{ $singleItem['id'] }}" data-category="{{ $data['category_id'] }}">
                                    <h3 class="card-title text-primary text-uppercase">{{ $singleItem['sub_category_name'] }}</h3>
                                    <p class="card-text description mt-3">{{ $singleItem['sub_category_description'] }}</p>
                                </div>
                            </div>
                            <br/>
                        </a>
                    </div>
                @endforeach
            </div>  
        @else
        {!! "<div class='my-4 text-dark font-weight-bold'>Sub Category Not Found!</div>" !!}
        @endif

        @if ($data['sub-categories']->count() > 0 && $data['items']->count() > 0)
            <hr>
        @endif
        
        @if ($data['items']->count() > 0)
            <div class="section-title mb-2">
                {{ __("Items: ") }}
            </div>
            <div class="row row-grid"> 
                @foreach ($data['items'] as $singleItem)
                    <div class="col-lg-3 h-100">
                        <a href="{{ route('items.edit', $singleItem) }}">
                            <div class="card">
                                <img class="card-img-top" src="{{ $singleItem['logom'] }}" alt="...">
                                <div class="card-body">
                                    <h3 class="card-title text-primary text-uppercase">{{ $singleItem['name'] }}</h3>
                                    <p class="card-text description mt-3">{{ $singleItem['description'] }}</p>

                                    <span class="badge badge-primary badge-pill">@money($singleItem['price'], config('settings.cashier_currency'),config('settings.do_convertion'))</span>

                                    <p class="mt-3 mb-0 text-sm">
                                        @if($singleItem['available'] == 1)
                                        <span class="text-success mr-2">{{ __("AVAILABLE") }}</span>
                                        @else
                                        <span class="text-danger mr-2">{{ __("UNAVAILABLE") }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <br/>
                        </a>
                    </div>
                @endforeach
                @if($canAdd)
                    <div class="col-lg-3" >
                        <a data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" class="addNewItem" data-parent="{{ $data['parent_id'] }}" data-category="{{ $data['category_id'] }}">
                            <div class="card">
                                <img class="card-img-top" src="{{ asset('images') }}/default/add_new_item.jpg" alt="...">
                                <div class="card-body">
                                    <h3 class="card-title text-primary text-uppercase">{{ __('Add item') }}</h3>
                                </div>
                            </div>
                        </a>
                        <br />
                    </div>
                @endif
            </div> 
        @else
            {!! "<div class='my-4 text-dark font-weight-bold'>Item Not Found!</div>" !!}

            @if($canAdd)
                <div class="col-lg-3" >
                    <a data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" class="addNewItem" data-parent="{{ $data['parent_id'] }}" data-category="{{ $data['category_id'] }}">
                        <div class="card">
                            <img class="card-img-top" src="{{ asset('images') }}/default/add_new_item.jpg" alt="...">
                            <div class="card-body">
                                <h3 class="card-title text-primary text-uppercase">{{ __('Add item') }}</h3>
                            </div>
                        </div>
                    </a>
                    <br />
                </div>
            @endif
        @endif

    </div>

@else
{{ 'error' }}
@endif
