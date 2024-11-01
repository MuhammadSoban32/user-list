<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function logout(){
        Auth::logout();
        return redirect("/login");
    }
    public function register(){
        return view("register");
    }
    public function login(){
        return view("login");
    }
    public function index(){
        return view("index");
    }
    public function users(){
        $user = User::get();
        // dd($user);
        return view("user.index",['users' => $user]);
    }

    public function create(){
        return view("user.create");
    }
    public function edit($id){
        $edit = User::find($id);
        //  dd($edit->name);

        return view("user.edit",["edit" => $edit]);
    }
    public function update(Request $request,$id){
        // dd($request->toArray());
        $validated = $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|max:25|unique:users,email,' . $request->id,
            'password' => 'required|min:6',
        ]);

        $user =  User::find($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        
        // Session()->flush('msg',"User Created Successfuly");
        return redirect('/')->with('msg','User Updated Successfuly');
    }
    public function delete(Request $request){
        // dd($request->toArray());
        $find = User::find($request->id);
         $name = $find->image;
        if (isset($name)) {
            $fname = public_path("images/" . $name);
            //  dd($fname);
             if (file_exists($fname)) {
                unlink(public_path($fname));
             }
        }
        $user =  User::destroy($request->id);
        return response()->json([
            'status' => true,
            'msg','User Deleted Successfuly'
        ]);
    }

    public function store(Request $request){
        // dd($request->toArray());
        $validated = $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|unique:users|max:25|email',
            'password' => 'required|min:6',
            'image' => 'required',
        ]);
        
        $imageName = time().'.'.$request->image->getClientOriginalExtension();
        $request->image->move(public_path("images"), $imageName);
        
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->image = $imageName;
        $user->password = Hash::make($request->password);
        $user->save();
        
        // Session()->flush('msg',"User Created Successfuly");
        return redirect('/')->with('msg','User Created Successfuly');
    }

    public function register_store(Request $request){
        // dd($request->toArray());
        $validated = $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|unique:users|max:25|email',
            'password' => 'required|min:6',
        ]);
        
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password =  Hash::make($request->password);
        $user->save();
        Auth::login($user);
        // if (Auth::login($user)) {
            return redirect()->route('users')->with('msg','User Login Successfuly');
        // }
        // Session()->flush('msg',"User Created Successfuly");
    }
    public function login_store(Request $request){
        // dd($request->toArray());
        $validated = $request->validate([
            'email' => 'required|exists:users|max:25|email',
            'password' => 'required|min:6',
        ]);
        
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('users')->with('msg','User Login Successfuly');
        }else{
            return redirect()->route('login')->with('msg','Please Enter Right Credentials')->withInput();
        }
        // Session()->flush('msg',"User Created Successfuly");
    }
}
