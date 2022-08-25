@extends('layouts.app', ['title' => __('Restaurant Menu Management')])
@section('admin_title')
    {{__('Menu')}}
@endsection

@section('css')
    <style>
        .sub-category-title {
            white-space: normal;
            word-break: break-word;
        }

        .sub-category-dropdown {
            text-align: right;
            margin-top: 7px;
        }
        .sub-category .card-title,.sub-category .sub-category-title {
            font-size: 14px;
        }

        .category_items_wrp .loading-spin {
            margin: 20px 0;
            vertical-align: center;
        }

        .category_items_wrp .loading-spin i{
            font-size: 35px;
            color: #6E69E4;
        }

        .category_bradecumn {
            display: flex;
            justify-content: space-between;
            width: 100%;
            padding: 0px 25px;
            margin: 10px 0;
        }

        .card-body .openSubCategory {
            cursor: pointer;
        }
    </style>
@endsection



@section('content')
    @include('items.partials.modals', ['restorant_id' => $restorant_id])

    <div class="header bg-gradient-primary pb-7 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
            <div class="row align-items-center py-4">
                <!--<div class="col-lg-6 col-7">
                </div>-->
                <div class="col-lg-12 col-12 text-right">
                    @if (isset($hasMenuPDf)&&$hasMenuPDf)
                        <a target="_blank" href="{{ route('menupdf.download')}}" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> {{ __('PDF Menu') }}</a>
                    @endif
                    <button class="btn btn-icon btn-1 btn-sm btn-info" type="button" data-toggle="modal" data-target="#modal-items-category" data-toggle="tooltip" data-placement="top" title="{{ __('Add new category')}}">
                        <span class="btn-inner--icon"><i class="fa fa-plus"></i> {{ __('Add new category') }}</span>
                    </button>
                    @if($canAdd)
                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-import-items" onClick=(setRestaurantId({{ $restorant_id }}))>
                        <span class="btn-inner--icon"><i class="fa fa-file-excel"></i> {{ __('Import from CSV') }}</span>
                    </button>
                    @endif
                    @if(config('settings.enable_miltilanguage_menus'))
                        @include('items.partials.languages')
                    @endif
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="mb-0">{{ __('Restaurant Menu Management') }} @if(config('settings.enable_miltilanguage_menus')) ({{ $currentLanguage}}) @endif</h3>
                                    </div>
                                    <div class="col-auto">
                                        <!--<button class="btn btn-icon btn-1 btn-sm btn-primary" type="button" data-toggle="modal" data-target="#modal-items-category" data-toggle="tooltip" data-placement="top" title="{{ __('Add new category')}}">
                                            <span class="btn-inner--icon"><i class="fa fa-plus"></i> {{ __('Add new category') }}</span>
                                        </button>
                                        @if($canAdd)
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-import-items" onClick=(setRestaurantId({{ $restorant_id }}))>
                                                <span class="btn-inner--icon"><i class="fa fa-file-excel"></i> {{ __('Import from CSV') }}</span>
                                            </button>
                                        @endif
                                        @if(config('settings.enable_miltilanguage_menus'))
                                            @include('items.partials.languages')
                                        @endif-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="col-12">
                        @include('partials.flash')
                    </div>
                    <div class="card-body">
                        @if(count($categories)==0)
                            <div class="col-lg-3" >
                                <a  data-toggle="modal" data-target="#modal-items-category" data-toggle="tooltip" data-placement="top" title="{{ __('Add new category')}}">
                                    <div class="card">
                                        <img class="card-img-top" src="{{ asset('images') }}/default/add_new_item.jpg" alt="...">
                                        <div class="card-body">
                                            <h3 class="card-title text-primary text-uppercase">{{ __('Add first category') }}</h3> 
                                        </div>
                                    </div>
                                </a>
                                <br />
                            </div>
                        @endif
                       
                        @foreach ($categories as $index => $category)
                        @if($category->active == 1)
                        <div class="alert alert-default category-item">
                            <div class="row">
                                <div class="col">
                                    <span class="h1 font-weight-bold mb-0 text-white category_name">{{ $category->name }}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="row">
                                        <script>
                                            function setSelectedCategoryId(id){
                                                $('#category_id').val(id);
                                            }

                                            function setRestaurantId(id){
                                                $('#res_id').val(id);
                                            }

                                        </script>
                                        
                                        @if($canAdd)
                                            <button class="btn btn-icon btn-1 btn-sm btn-primary addNewSubCategory" type="button" data-category="{{$category->id}}" data-toggle="modal" data-target="#modal-add-sub-category" data-restaurant="{{$restorant_id}}" data-toggle="tooltip" data-placement="top" title="{{ __('Add Sub Category') }} in {{$category->name}}">{{ __('Add Sub Category') }}</button>

                                            <button class="btn btn-icon btn-1 btn-sm btn-primary addNewItem" data-category="{{$category->id}}" type="button" data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top" title="{{ __('Add item') }} in {{$category->name}}" >
                                                <span class="btn-inner--icon"><i class="fa fa-plus"></i></span>
                                            </button>
                                        @else
                                            <a href="{{ route('plans.current')}}" class="btn btn-icon btn-1 btn-sm btn-warning" type="button"  >
                                                <span class="btn-inner--icon"><i class="fa fa-plus"></i> {{ __('Menu size limit reaced') }}</span>
                                            </a>
                                        @endif
                                        <button class="btn btn-icon btn-1 btn-sm btn-warning" type="button" id="edit" data-toggle="modal" data-target="#modal-edit-category" data-toggle="tooltip" data-placement="top" title="{{ __('Edit category') }} {{ $category->name }}" data-id="<?= $category->id ?>" data-name="<?= $category->name ?>" >
                                            <span class="btn-inner--icon"><i class="fa fa-edit"></i></span>
                                        </button>

                                        <form action="{{ route('categories.destroy', $category) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-icon btn-1 btn-sm btn-danger" type="button" onclick="confirm('{{ __("Are you sure you want to delete this category?") }}') ? this.parentElement.submit() : ''" data-toggle="tooltip" data-placement="top" title="{{ __('Delete') }} {{$category->name}}">
                                                <span class="btn-inner--icon"><i class="fa fa-trash"></i></span>
                                            </button>
                                        </form>

                                        @if(count($categories)>1)
                                            <div style="margin-left: 10px; margin-right: 10px">|</div>
                                        @endif

                                        <!-- UP -->
                                        @if ($index!=0)
                                        <a href="{{ route('items.reorder',['up'=>$category->id]) }}"  class="btn btn-icon btn-1 btn-sm btn-success" >
                                            <span class="btn-inner--icon"><i class="fas fa-arrow-up"></i></span>
                                        </a>
                                        @endif

                                        <!-- DOWN -->
                                        @if ($index+1!=count($categories))
                                            <a href="{{ route('items.reorder',['up'=>$categories[$index+1]->id]) }}" class="btn btn-icon btn-1 btn-sm btn-success">
                                                <span class="btn-inner--icon"><i class="fas fa-arrow-down"></i></span>
                                            </a>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($category->active == 1)
                        <div class="row justify-content-center category_items_wrp">
                            <div class="col-lg-12">
                                @if ($sub_categories->where('category_id',$category->id)->count() > 0)
                                    <div class="section-title mb-2">
                                        {{ __("Sub Categories: ") }}
                                    </div>
                                    <div class="row row-grid">
                                        @foreach ( $sub_categories as $singleItem)
                                            @if ($singleItem->category_id == $category->id)
                                                <div class="col-lg-3 h-100">
                                                    <a href="javascript:void(0)" class="openSubCategory" data-parent="{{ $singleItem->id }}" data-category="{{ $category->id }}" data-cat_name="{{ $category->name }}">
                                                        <div class="card">
                                                            <img class="card-img-top" src="{{ $singleItem->logom }}" alt="...">
                                                            <div class="sub-category-dropdown">
                                                                <a class="btn btn-sm btn-icon-only text-light" href="javascript:void(0)" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-v"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                                    <a class="dropdown-item addNewSubCategory" href="javascript:void(0)" data-toggle="modal" data-target="#modal-add-sub-category" data-category="{{ $category->id }}" data-restaurant="{{ $restorant_id }}" data-parent="{{ $singleItem->id }}" data-modal="modal-add-sub-category">{{ __('Add New Subcategory') }}</a>
                                                                    <a class="dropdown-item addNewItem" href="javascript:void(0)" data-category="{{ $category->id }}" data-parent="{{ $singleItem->id }}" data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top">{{ __('Add Item') }}</a>
                                                                    <a class="dropdown-item subCategoryEdit" href="javascript:void(0)" data-toggle="modal" data-target="#modal-edit-sub-category" data-toggle="tooltip" data-placement="top" data-sub="{{ $singleItem->id }}" data-name="{{ $singleItem->sub_category_name }}" data-desc="{{ $singleItem->sub_category_description }}" data-image="{{ $singleItem->logom }}">{{ __('Edit') }}</a>
                                                                    <a class="dropdown-item warning red subCategoryDelete" data-sub="{{ $singleItem->id }}" data-category="{{ $category->id }}"  href="javascript:void(0)">{{ __('Delete') }}</a>
                                                                </div>
                                                            </div>
                                                            <div class="card-body openSubCategory" data-parent="{{ $singleItem->id }}" data-category="{{ $category->id }}" data-cat_name="{{ $category->name }}">
                                                                <h3 class="card-title text-primary text-uppercase">{{ $singleItem->sub_category_name }}</h3>
                                                                <p class="card-text description mt-3">{{ $singleItem->sub_category_description }}</p>
                                                            </div>
                                                        </div>
                                                        <br/>
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                @if ($category->items->where('category_id',$category->id)->count() > 0 && $sub_categories->where('category_id',$category->id)->count() > 0)
                                    <hr>
                                @endif

                                @if ($category->items->where('category_id',$category->id)->count() > 0)
                                    <div class="section-title mb-2">
                                        {{ __("Items: ") }}
                                    </div>
                                @endif
                                <div class="row row-grid">
                                    @foreach ( $category->items as $item)
                                        @if ($item->category_type == 1)
                                            <div class="col-lg-3 mb-3">
                                                <a href="{{ route('items.edit', $item) }}">
                                                    <div class="card h-100">
                                                        <img class="card-img-top" src="{{ $item->logom }}" alt="...">
                                                        <div class="card-body">
                                                            <h3 class="card-title text-primary text-uppercase">{{ $item->name }}</h3>
                                                            <p class="card-text description mt-3">{{ $item->description }}</p>

                                                            <span class="badge badge-primary badge-pill">@money($item->price, config('settings.cashier_currency'),config('settings.do_convertion'))</span>

                                                            <p class="mt-3 mb-0 text-sm">
                                                                @if($item->available == 1)
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
                                        @endif
                                    @endforeach
                                    @if($canAdd)
                                    <div class="col-lg-3" >
                                        <a data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" class="addNewItem" data-category="{{$category->id}}">
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
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
  $("[data-target='#modal-edit-category']").on('click',function() {
    var id = $(this).attr('data-id');
    var name = $(this).attr('data-name');
    
    $('#cat_name').val(name);
    $("#form-edit-category").attr("action", "/categories/"+id);
})
</script>

<script>
    $(document).on('click','.addNewSubCategory',function(event){
        event.preventDefault();
        var category = $(this).attr('data-category');
        var restaurant = $(this).attr('data-restaurant');
        var restaurant_input = `<input name="restaurant_id" id="restaurant_id" type="hidden" required value="${restaurant}">`;
        var parent_id = "";

        if($(this).attr('data-parent')) {
            var parent_id = $(this).attr('data-parent');
            var parent_input = `<input name="parent_id" id="parent_id" type="hidden" required value="${parent_id}">`;
            $('#modal-add-sub-category form').append(parent_input);
        }

        $('#modal-add-sub-category #category_id-sub_modal').val(category);
        $('#modal-add-sub-category form').append(restaurant_input);
    });

    // Ajax Request For Collect Information from db
    $(document).on('click','.openSubCategory',function(){
        var category = $(this).attr('data-category');
        var parent = $(this).attr('data-parent');

        var back_btn = '';
        if($(this).attr('data-back')) {
            var back_btn = 'clicked';
        }else {
            if(category == undefined || parent == undefined || category == "" || parent == "" ) {
                alert('Something Worng! Please try again.');
                return false;
            }
        }

        var category_item_wrp = $(this).parents('.category_items_wrp');
        category_item_wrp.css('height','500px');
        var loadingAnimation = `<div class="loading-spin" style="height:200px; display:none">
                                <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                            </div>`;
        category_item_wrp.html(loadingAnimation);
        category_item_wrp.children('.loading-spin').fadeIn();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.post("/sub-category/getItems", {category: category,parent:parent,back_click:back_btn}, function(data,status){
            if(status == "success") {
                if(data != 'error') {
                    category_item_wrp.html(data);
                    category_item_wrp.css('height','auto');
                    // category_item_wrp.fadeIn();
                }else {
                    category_item_wrp.html("<div class='text-danger text-center mt-3'>Something Worng! Please try again.</div>");
                }
            }else {
                alert('Something Worng! Please Try Again.')
            }
        });
    });

    // Add New Item
    $(document).on('click','.addNewItem',function(event) {
        event.preventDefault();
        var category = $(this).attr('data-category');
        $('#modal-new-item form #category_id').val(category);

        if($(this).attr('data-parent')) {
            var parent = $(this).attr('data-parent');
            var parent_input = `<input name="parent_id" id="parent_id" type="hidden" required value="${parent}">`;
            $('#modal-new-item form').append(parent_input);
        }else {
            $('#modal-new-item form #parent_id').remove();
        }

    });

    // add new item popup submit button click
    $(document).on('click','.add_new_item_submit_btn',function(event) {
        event.preventDefault();
        $('#add_new_item_form').submit();

        // when submit disable the submit button to prevent multiple clicks
        $(this).prop('disabled', true);
    });

    // Sub Category Edit
    $(document).on('click','.subCategoryEdit',function(event){
        // event.preventDefault();
        var subCategory = $(this).attr('data-sub');
        var subCatName = $(this).attr('data-name');
        var subCatDesc = $(this).attr('data-desc');
        var subCatImage = $(this).attr('data-image');

        $('#modal-edit-sub-category form #sub_category_id').val(subCategory);
        $('#modal-edit-sub-category form #sub_cat_name').val(subCatName);
        $('#modal-edit-sub-category form #sub_category_description').val(subCatDesc);
        $('#modal-edit-sub-category form .fileinput-preview img').attr('src',subCatImage);
    });

    // Sub Category Delete 
    $(document).on('click','.subCategoryDelete',function(){
        if(confirm('Are you sure you want to delete this Sub Category from Database? This will aslo delete all Items Under this Sub Category. This is irreversible step.') == true) {
            var subCatId = $(this).attr('data-sub');
            var category = $(this).attr('data-category');
            var formAction = "/sub-category/delete";
            var CSRF = '@csrf';

            var deleteForm = `<form method='post' id="sub-category-delete" action='${formAction}'>
                    ${CSRF}
                    <input type='hidden' name='sub_category_id' require value='${subCatId}'>
                    <input type='hidden' name='category_id' require value='${category}'>
                </form>`;

                $('body').append(deleteForm);
                $('#sub-category-delete').submit();
        }

    });
</script>


@endsection
