<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use App\Services\FileUploadService;

class UserController extends Controller
{
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
        $data = new User();
        $data->name = $request->get('name');
        $data->email = $request->get('email');
        $data->contact_number = $request->get('contact_number');
        $data->address = $request->get('address');
        $data->password = bcrypt($request->get('password'));
        $data->category = $request->get('category');
        $data->remember_token = Str::random(10);

        $image = $request->file('image');
        if($image){;
            $data->image = App::call([new FileUploadService, 'uploadFile'], ['file' => $image, 'filename' => $data->name, 'folder' => 'user']);
        }

        $data->save();

        return redirect()->route('users.index')->with('status','Akun dengan nama: '.$data->name.' berhasil dibuat');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->contact_number = $request->get('contact_number');
        $user->address = $request->get('address');

        $image = $request->file('image');
        if ($image) {
            $user->profile_picture = App::call([new FileUploadService, 'uploadFile'], ['file' => $image, 'filename' => $user->name, 'folder' => 'user']);
        }

        $user->save();

        return redirect()->to('admin/profile')->with('status','Akun anda berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->id == Auth()->user()->id){
            return redirect()->route('users.index')->with('error','Akun tidak dapat dihapus, karena sedang digunakan');
        }
        try{
            $user->delete();
            return redirect()->route('users.index')->with('status','Akun dengan nama: '.$user->name.' telah dihapus');
        }catch(\Exception $e){
            return redirect()->route('users.index')->with('error','Akun tidak dapat dihapus, Pesan Error: '.$e->getMessage());
        }
    }
}
