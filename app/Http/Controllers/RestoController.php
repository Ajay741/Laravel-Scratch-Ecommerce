<?php

namespace App\Http\Controllers;

use App\Session;
use Illuminate\Http\Request;
use App\Models\restaurant;
use App\Models\Device;
use Crypt;

class RestoController extends Controller
{
    function index()
    {
        return view('home');
    }

    function list()
    {
        $data = restaurant::all();
        return view('list',["data"=>$data]);
    }

    function add(Request $req)
    {
        $resto = new restaurant;
        $resto->name=$req->input('name');
        $resto->email=$req->input('email');
        $resto->address=$req->input('address');
        $resto->save();

        $req->session()->flash('status','Restaurant Added Successfully!!!');

        return redirect('list');
    }

     function delete($id)
     {
        restaurant::find($id)->delete();
        return redirect('list');
     }
    function edit($id)
    {
        $data= restaurant::find($id);
        return view('edit',['data'=>$data]);
        
    }

    function update(Request $req)
    {
        $resto = restaurant::find($req->id);
        $resto->name=$req->input('name');
        $resto->email=$req->input('email');
        $resto->address=$req->input('address');
        $resto->save();

        $req->session()->flash('status','Restaurant Updated Successfully!!!');

        return redirect('list');
    }
    

    public function register(Request $req)
    {
        $user = new Device;
        $user->name=$req->input('name');
        $user->email=$req->input('email');
        $user->password=Crypt::encrypt($req->input('password'));
        $user->contact=$req->input('contact');
        $user->save();

        $req->session()->put('user',$req->input('name'));

        return redirect('login');
    }

    public function login(Request $req)
    {
        $user= device::where('email',$req->input('email'))->get();
        if(Crypt::decrypt($user[0]->password)==$req->input('password'))
        {
        $req->session()->put('user',$user[0]->name);

        return redirect('dashboard');
        }
    }
    function dashboard()
    {
        return view('dashboard');
    }

    // public function signOut() {
    //     Session::flush();
    //    Auth::logout();
   
    //     return redirect('login');
    // }
}
