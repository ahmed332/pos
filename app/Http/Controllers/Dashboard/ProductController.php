<?php

namespace App\Http\Controllers\Dashboard;

use App\models\product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\productRequest;
use App\models\Category;
use Illuminate\Support\Facades\Storage;
use Symfony\Contracts\Service\Attribute\Required;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rule;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)

    {

        $products = Product::when($request->search, function ($q) use ($request) {

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->when($request->category_id, function ($q) use ($request) {

            return $q->where('category_id', $request->category_id);

        })->latest()->paginate(5);
         $categories=Category::all();
        return view('dashboard.products.index',compact('products','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories =Category::all();
        return view('dashboard.products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['sale_price'=>'required'],[],[]);

        // $rules = [
        //     'category_id' => 'required'
        // ];

        // foreach (config('translatable.locales') as $locale) {

        //     $rules += [$locale . '.name' => 'required|unique:product_translations,name'];
        //     $rules += [$locale . '.description' => 'required'];

        // }//end of  for each

        // $rules += [
        //     'purchase_price' => 'required',
        //     'sale_price' => 'required',
        //     'stock' => 'required',
        // ];
        // $messages=
        // $request->validate($rules,[],'');
        $request_data=$request->all();
         if ($request->image) {


            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/product_images/'. $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

         }//end of if


    product::create( $request_data);

    session()->flash('success', __('site.added_successfully'));
    return redirect()->route('dashboard.products.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(product $product)
    {
        $categories=Category::all();
        return view('dashboard.products.edit',compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, product $product)
    {

        $request->validate([
            'ar.name'=>['required', Rule::unique('product_translations', 'name')->ignore($product->id, 'product_id')],
            'en.name'=>['required', Rule::unique('product_translations', 'name')->ignore($product->id, 'product_id')],
            'ar.description'=>['required', Rule::unique('product_translations', 'description')->ignore($product->id, 'product_id')],
            'en.description'=>['required', Rule::unique('product_translations', 'description')->ignore($product->id, 'product_id')],
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',
        ],[
            'ar.name.required'=>trans('site.ar-name-required'),
            'en.name.required'=>trans('site.En.name.required'),
            'ar.description.required'=>trans('site.ar-description-required'),
            'En-description.required'=>trans('site.En-description-required'),
            'purchase_price_required'=>trans('site.purchase_price_required'),
            'sale_price.required' =>trans('site.sale_price_required'),

            'stock' => 'required',
        ],[]);
         $request_data=$request->except(['image']);
        Storage::disk('public_uploads')->delete('/uploads_images/'.$product->image);

        Image::make($request->image)
            ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(public_path('uploads/product_images/'. $request->image->hashName()));

        $request_data['image'] = $request->image->hashName();
            $product->update($request_data);
            session()->flash('success', __('site.updated_successfully'));
            return redirect()->route('dashboard.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(product $product)
    {
        Storage::disk('public_uploads')->delete('/product_images/'.$product->image);
        $product->delete();
        session()->flash('success',__('site.delete_succussfuly'));
        return redirect()->route('dashboard.products.index');

    }
}
