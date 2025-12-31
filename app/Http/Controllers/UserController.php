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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,',
            'password' => 'required|string|min:8',
        ]);

        $data = new User();
        $data->name = $request->get('name');
        $data->email = $request->get('email');
        $data->password = bcrypt($request->get('password'));
        $data->remember_token = Str::random(10);

        $data->save();

        return redirect('/login')->with('status','Akun dengan nama: '.$data->name.' berhasil dibuat');
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        try{
            $user->name = $request->get('name');
            $user->email = $request->get('email');

            if ($request->filled('current_password') || $request->filled('new_password') || $request->filled('new_password_confirmation')) {
                $request->validate([
                    'current_password' => ['required', 'current_password'],
                    'new_password' => ['required', 'min:8', 'confirmed'],
                ]);

                $user->password = bcrypt($request->get('new_password'));
            }

            $user->save();

            return redirect()->to('admin/profile')->with('status','Akun anda berhasil diperbarui');
        }
        catch(\Exception $e){
            return redirect()->to('admin/profile')->with('status','Terjadi kesalahan dalam perubahan Akun, Perubahan Akun Digagalkan');
        }
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
