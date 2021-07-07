<?php

namespace App\Http\Controllers\Dashboard;

use App\user;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use Intervention\Image\Facades\Image;



class UserController extends Controller
{

    public function __construct()
    {
        //create read update delete
        $this->middleware(['permission:read_users'])->only('index');
        $this->middleware(['permission:create_users'])->only('create');
        $this->middleware(['permission:update_users'])->only('edit');
        $this->middleware(['permission:delete_users'])->only('destroy');

    }//end of constructor
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $users = User::whereRoleIs('admin')->where(function ($q) use ($request) {

            return $q->when($request->search, function ($query) use ($request) {

                return $query->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%');

            });

        })->latest()->paginate(5);
        return view('dashboard.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request,[
        //     'first_name'=>'required',
        //     'last_name'=>'required',
        //     'email'=>'required',
        //     'password'=>'required',
        //     'password_confirmation'=>'required',
        // ]);


       $request->validate([
             'first_name'=>'required',
             'last_name'=>'required',
             'email'=>'required|unique:users',

            'password'=>'required',
             'password_confirmation'=>'required',
             'permissions'=>'required',
         ]);
         // $request->image->getClientOriginalName();
         // $request->image->getClientOriginalName();
       // return (public_path('uploads/users_images/'. $request->image->hashName()));

        // return asset(public_path('uploads/users_images/'. $request->image->hashName()));
      $request_data=$request->except('password', 'password_confirmation', 'permissions','image');
       $request_data['password'] =bcrypt($request->password);
       if ($request->image) {


        Image::make($request->image)
            ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(public_path('uploads/users_images/'. $request->image->hashName()));

        $request_data['image'] = $request->image->hashName();

    }//end of if
       $user = User::create($request_data);
        $user->attachRole('admin');
        $user->syncPermissions($request->permissions);

        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(user $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $user=user::with('permissions')->find($id);
        return view('dashboard.users.edit',['user'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, user $user)
    {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email'=> ['required', Rule::unique('users')->ignore($user->id)],
        ]);
          $request_data= $request->except(['permissions','image']);
           if ($request->image) {

            Storage::disk('public_uploads')->delete('/users_images/'.$user->image);

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(public_path('uploads/users_images/'. $request->image->hashName()));

            $request_data['image'] = $request->image->hashName();

        }//end of if

          $user->update($request_data);

          session()->flash('success',__('site.updated_successfully'));
          return redirect()->route('dashboard.users.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(user $user)
    {
        if ($user->image) {

            Storage::disk('public_uploads')->delete('/users_images/'.$user->image);

        }//end of if

        $user->delete();
        session()->flash('success',__('site.deleted.successfully'));
        return redirect()->route('dashboard.users.index');
    }
}
