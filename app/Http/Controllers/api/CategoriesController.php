<?php

namespace App\Http\Controllers\api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Product;
use App\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    private $lang;

    public function __construct()
    {
        $this->lang = App::getLocale();
    }

    /**
     * categoriesList function
     *
     * @return josn
     * @author
     **/
    public function categoriesList(Request $request)
    {
        $getCategories = Category::has('subCategory')->whereHas('subCategory.products')->with('subCategory')->get(['id', 'name_ar', 'name_en', 'logo']);

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $getCategories]);
    }

    /**
     * subCategoriesList
     *
     * @return json
     * @author
     **/
    public function subCategoriesList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {
            $categories = SubCategory::where('category_id', $request->category_id)
                ->whereHas('products')
                ->with('childs')
                ->get(['id', 'category_id', 'name_ar', 'name_en']);

            return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $categories]);
        }
    }

    public function categoriesChild(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {
            $categories = SubCategory::where('father_id', $request->category_id)
                ->whereHas('products')
                ->with('childs')
                ->get(['id', 'category_id', 'name_ar', 'name_en']);

            return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $categories]);
        }
    }

    /**
     * subCategoriesList
     *
     * @return json
     * @author
     **/
    public function categoryProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subCategory_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'key' => 0, 'code' => 406, 'message' => $validator->errors()->all(), 'data' => []]);
        } else {
            $getProducts = Product::where('subCategory_id', $request->subCategory_id)
                ->has('photos')
                ->has('fields')
                ->with(['photos', 'fields'])
                ->get();

            return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $getProducts]);
        }
    }

    /**
     * Categories List gruop
     *
     * @return json
     * @author
     **/
    public function categories(Request $request)
    {
        $getCategories = Category::get(['id', 'name_ar', 'name_en', 'logo']);

        return response()->json(['status' => 'success', 'key' => 1, 'code' => 201, 'message' => '', 'data' => $getCategories]);

    }
}
