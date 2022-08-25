<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Items;
use App\SubCategory;
use Exception;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SubCategoryController extends Controller
{
    private $imagePath = 'uploads/sub-categories/';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $item = new SubCategory();
        $item->sub_category_name = strip_tags($request->sub_category_name);
        $item->sub_category_description = strip_tags($request->sub_category_description);
        $item->category_id = strip_tags($request->category_id);
        
        if($request->parent_id && $request->parent_id != "") {
            $item->parent_id = strip_tags($request->parent_id);
        }else {
            $item->parent_id = null;
        }

        if($request->restaurant_id == "" || !$request->restaurant_id) {
            return back();
        }
        $item->restaurant_id = strip_tags($request->restaurant_id);
        
        if ($request->hasFile('sub_category_image')) {
            $item->image = $this->saveImageVersions(
                $this->imagePath,
                $request->sub_category_image,
                [
                    ['name'=>'large', 'w'=>590, 'h'=>400],
                    //['name'=>'thumbnail','w'=>300,'h'=>300],
                    ['name'=>'medium', 'w'=>295, 'h'=>200],
                    ['name'=>'thumbnail', 'w'=>200, 'h'=>200],
                ]
            );
        }
        $item->save();

        return redirect()->route('items.index')->withStatus(__('Sub Category successfully Added.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = strip_tags($request->sub_category_id);

        $item = new SubCategory();
        $item = $item->find($id);
        $item->sub_category_name = strip_tags($request->sub_category_name);
        $item->sub_category_description = strip_tags($request->sub_category_description);

        $item_old_image = $item->image;

        if ($request->hasFile('sub_image')) {
            if ($request->hasFile('sub_image')) {
                $item->image = $this->saveImageVersions(
                    $this->imagePath,
                    $request->sub_image,
                    [
                        ['name'=>'large'],
                        //['name'=>'thumbnail','w'=>300,'h'=>300],
                        ['name'=>'medium', 'w'=>295, 'h'=>200],
                        ['name'=>'thumbnail', 'w'=>200, 'h'=>200],
                    ]
                );
            }
        }

        $update_result = $item->update();

        if($request->hasFile('sub_image')) {
            $file_type = ['large','medium','thumbnail'];
            if($update_result == true) {
                foreach($file_type as $file) {
                    $image_loc = $this->imagePath . $item_old_image . "_" . $file . ".jpg";
                    if(file_exists(public_path($image_loc))) {
                        File::delete($image_loc);
                    }
                }
            }
        }

        return redirect()->route('items.index')->withStatus(__('Sub Category successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if(!$request->sub_category_id || $request->sub_category_id == "") {
            return back()->with('error','Something Worng! Please try again.');
        }else {
            $sub_category_id = $request->sub_category_id;
        }

        $category_id = $request->category_id;

        // Check Child is available or not
        $child = SubCategory::where('parent_id',$sub_category_id)->get();
        if($child->count() != 0) {
            return back()->with('error',"You Can't Delete This Sub Category. Because it has Child. Please Delete The Child First.");
        }else {
            // Look for Items 
            $items_under_sub_delete = Items::where('parent_id','!=',null)
                                    ->where('category_id',$category_id)
                                    ->where('category_type',0)
                                    ->where('parent_id',$sub_category_id)
                                    ->delete();
            
            // Delete Sub Caetgory
            $image_name = SubCategory::find($sub_category_id)->image;
            if($image_name != null) {
                $file_type = ['large','medium','thumbnail'];
                foreach($file_type as $file) {
                    $image_loc = $this->imagePath . $image_name . "_" . $file . ".jpg";

                    if(File::exists(public_path($image_loc))) {

                        File::delete($image_loc);
                    }
                }
            }

            try{
                $delete_sub = SubCategory::find($sub_category_id)->delete();
            }catch(Exception $e) {
                return back()->with('error',"Something Worng! Please try again.");
            }

       
        }

       return redirect()->route('items.index')->withStatus(__('Sub Category Deleted Successfully!'));
    }

    public function indexAjax(Request $request)
    {

        $canAdd = auth()->user()->restorant->getPlanAttribute()['canAddNewItems'];

        $category_id = $request->category;
        $parent_id = $request->parent;
        $data = [];
        $data['back-btn-off'] = "";
        $data['sub-cat-btn-off'] = "";

        $get_restaurant_id = Categories::where('id',$category_id)->first()->restorant_id;

        if($request->back_click && $request->back_click == 'clicked') {
            // Get Parent Id
            $getSubCategory = SubCategory::find($parent_id);

            if($getSubCategory->parent_id == "" || $getSubCategory->parent_id == null) {
                // only main Category information show

                $sub_categories = SubCategory::where('restaurant_id',$get_restaurant_id)
                                            ->where('parent_id',null)
                                            ->where('category_id',$category_id)
                                            ->get();
                
                if($sub_categories == true) {
                    $data['sub-categories'] = $sub_categories;
                    $data['sub_success'] = "success";
                }else {
                    $data['error'] = "Something Worng! Please Try Again.";
                }

                // Get Items
                $getItems = Items::where('category_id',$category_id)
                            ->where('category_type',1)
                            ->where('parent_id',null)
                            ->orderBy('id','desc')
                            ->get();

                if($getItems == true) {
                    $data['items'] = $getItems;
                    $data['item_success'] = "success";
                }else {
                    $data['error'] = "Something Worng! Please Try Again.";
                }

                $data['back-btn-off'] = 'd-none';
                $data['sub-cat-btn-off'] = "d-none";

            }else {
                $parent_id = $getSubCategory->parent_id;

                $getSubCategories = SubCategory::where('restaurant_id',$get_restaurant_id)
                                            ->where('category_id',$category_id)
                                            ->where('parent_id',$parent_id)
                                            ->orderBy('id','desc')
                                            ->get();
    
                if($getSubCategories == true) {
                    $data['sub-categories'] = $getSubCategories;
                    $data['sub_success'] = "success";
                }else {
                    $data['error'] = "Something Worng! Please Try Again.";
                }


                $getItems = Items::where('category_id',$category_id)
                            ->where('category_type',0)
                            ->where('parent_id',$parent_id)
                            ->orderBy('id','desc')
                            ->get();
    
                if($getItems == true) {
                    $data['items'] = $getItems;
                    $data['item_success'] = "success";
                }else {
                    $data['error'] = "Something Worng! Please Try Again.";
                }

            }

        }else {
            if($category_id == "" || $parent_id == "") {
                $data['error'] = "Something Worng! Please Try Again.";
            }
    
            $getSubCategory = SubCategory::where('restaurant_id',$get_restaurant_id)
                                            ->where('category_id',$category_id)
                                            ->where('parent_id',$parent_id)
                                            ->orderBy('id','desc')
                                            ->get();
    
            if($getSubCategory == true) {
                $data['sub-categories'] = $getSubCategory;
                $data['sub_success'] = "success";
            }else {
                $data['error'] = "Something Worng! Please Try Again.";
            }
            
            $getItems = Items::where('category_id',$category_id)
                            ->where('category_type',0)
                            ->where('parent_id',$parent_id)
                            ->orderBy('id','desc')
                            ->get();
    
            if($getItems == true) {
                $data['items'] = $getItems;
                $data['item_success'] = "success";
            }else {
                $data['error'] = "Something Worng! Please Try Again.";
            }
        }

        $data['category_id'] = $category_id;
        $data['restaurant_id'] = $get_restaurant_id;
        $data['parent_id'] = $parent_id;

                                    
        return view('items.sub-category-ajax',[
            'data'=>$data,
            'canAdd'=>$canAdd
        ]);
    }
}
