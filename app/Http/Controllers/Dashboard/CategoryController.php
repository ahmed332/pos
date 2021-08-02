<?php

namespace App\Http\Controllers\Dashboard;

use App\models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $category = category::when($request->search,function($q) use( $request){
           return $q->where('slug','like','%'. $request->search .'%');
        })->latest()->paginate(5);
        return view('dashboard.categories.index' ,['categories'=>$category]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
       // $this->validate(request(),['name'=>'required', ]);



        Category::create($request->all());
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.categories.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('dashboard.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {


      // $request->validate('name' => 'required|unique:category_translations,name,' . $category->id);
      //$request->validate(['name'=> ['required', Rule::unique('categories')->ignore($category->id)],
//]);

//$request->validate(['ar.name'=>'required|unique:category_translations,name,' . $category->id]);

    $category->update($request->all());
    session()->flash('success', __('site.updated_successfully'));
    return redirect()->route('dashboard.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        $category->delete();
        session()->flash('success',__('site.deleted.successfully'));
        return redirect()->route('dashboard.categories.index');    }
}
