<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/
 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ManageUsersController extends Controller
{
    public function index (){
         return view('theme::pages.admin.manageusers',['users' => User::latest()->paginate(10)]);
    }

      public function store(Request $request){
        $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required',
            'limit_device' => 'required|numeric|max:10',
            'active_subscription' => 'required|',

        ]);

        if($request->active_subscription == 'active'){
            $request->validate([
               'subscription_expired' => 'required|date',
            ]);

            // subscription expired must be greater than today
            if($request->subscription_expired < date('Y-m-d')){
                return redirect()->back()->with('alert' , ['type' => 'danger', 'msg' => __('Subscription expired must be greater than today')]);
            }
        }
         
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->api_key = Str::random(32);
        $user->chunk_blast = 0;
        $user->limit_device = $request->limit_device;
        $user->active_subscription = $request->active_subscription;
        $user->subscription_expired = $request->subscription_expired ?? null;
        $user->save();
        return redirect()->back()->with('alert', ['type' => 'success', 'msg' => __('User created successfully')]);
         
    }

     public function edit(){
        $id = request()->id;
        $user = User::find($id);
        // return data user to ajax
       return json_encode($user);
    }
    public function update(Request $request){
        
        $request->validate([
            'username' => 'required|unique:users,username,'.$request->id,
            'email' => 'required|unique:users,email,'.$request->id,
            'limit_device' => 'required|numeric|max:10',
            'active_subscription' => 'required|',

        ]);
        if($request->active_subscription == 'active'){
            $request->validate([
               'subscription_expired' => 'required|date',
            ]);

            // subscription expired must be greater than today
            if($request->subscription_expired < date('Y-m-d')){
                return redirect()->back()->with('alert' , ['type' => 'danger', 'msg' => __('Subscription expired must be greater than today')]);
            }
        }
       
        if($request->password != ''){
            $request->validate([
                'password' => 'min:6',
            ]);
        }
        $user = User::find($request->id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password != '' ? bcrypt($request->password) : $user->password;
        $user->limit_device = $request->limit_device;
        $user->active_subscription = $request->active_subscription;
        $user->subscription_expired = $request->subscription_expired ?? null;
        $user->save();
        return redirect()->back()->with('alert', ['type' => 'success', 'msg' => __('User updated successfully')]);
    }

    public function delete($id){
        $user = User::find($id);
        if($user->level == 'admin'){
            return redirect()->back()->with('alert', ['type' => 'danger', 'msg' => __('You can not delete admin')]);
        }
        
        $user->delete();
        return redirect()->back()->with('alert', ['type' => 'success', 'msg' => __('User deleted successfully')]);
    }
}
  
?>