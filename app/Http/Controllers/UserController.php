<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
// use DB;
// use Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //to jest według przykładu
        // $users = User::whereNull('approved_at')->get();
        // return view('users', compact('users'));
        // if($request->man == 0){
            $data = User::orderBy('id','ASC')->paginate(15);
            return view('users.index',compact('data'))
                ->with('i', ($request->input('page', 1) - 1) * 15);
        // }else{
            // // a to po modyfikacji aby się otwierało na mojej stronie, wystarczy wpisac users.index
            // $data = User::whereNull('approved_at')->get();
            // return view('users2', compact('data'))
            //     ->with('i', ($request->input('page', 1) - 1) * 15);
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => ''
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('admin.users.index')
                        ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        request()->validate([
            'currentPage' => 'integer',
            'page' => 'integer',
        ]);
        $user = User::find($id);
        if(request('currentPage')){
            $page = request('currentPage');
        }elseif(request('page')){
            $page = request('page');
        }else{
            $page = 1;
        }

        return view('users.show',compact('user','page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        request()->validate([
            'currentPage' => 'integer',
            'page' => 'integer',
        ]);
        if(request('currentPage')){
            $page = request('currentPage');
        }elseif(request('page')){
            $page = request('page');
        }else{
            $page = 1;
        }
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.edit',compact('user','roles','userRole','page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => '',
            'page' => 'integer',
        ]);
        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        if($request->has('email_verified_at')){ //kiedy true - zaznaczone
            $input['email_verified_at'] = now();
        }elseif(!$request->has('email_verified_at')){ //kiedy false - nie zaznaczone
            $input['email_verified_at'] = null;
        }
        if($request->has('approved_at')){ //kiedy true - zaznaczone
            $this->approve($request,$id); //wywołanie funkcji która jest na dole
        }elseif(!$request->has('approved_at')){ //kiedy false - nie zaznaczone
            $this->notapprove($id);
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));
        $page = $input['page'];

        $data = User::orderBy('id','ASC')->paginate(15);
        return redirect()->route('admin.users.index', compact('page','data'))
                        ->with('success','Dane '.$user->name.' o id: '.$user->id.' uaktualnione.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();


        $data = User::orderBy('id','ASC')->paginate(15);
        return redirect()->route('admin.users.index', compact('data'))
                        ->with('success','User deleted successfully');
    }


    public function approve(Request $request, $user_id)
    {
        request()->validate([
            'currentPage' => 'integer',
            'page' => 'integer',
        ]);
        if(request('currentPage')){
            $page = request('currentPage');
        }elseif(request('page')){
            $page = request('page');
        }else{
            $page = 1;
        }
        $user = User::findOrFail($user_id);
        // $user->update(['approved_at' => now()]);
        $user->approved_at = now();
        $user->save();

        // return redirect()->route('admin.users.index')->withMessage('User approved successfully');
        $data = User::orderBy('id','ASC')->paginate(15);

        return redirect()->route('admin.users.index', compact('page','data'))
            ->with('success','Użytkownik nr '.$user_id.' zatwierdzony');
    }


    public function notapprove($user_id)
    {
        $user = User::findOrFail($user_id);
        // $user->update(['approved_at' => null]);
        $user->approved_at = null;
        $user->save();

        // return redirect()->route('admin.users.index')->withMessage('User approved successfully');
        $data = User::orderBy('id','ASC')->paginate(15);

            return view('users.index',compact('data'))
            ->with('success','User approved successfully');
    }
}
