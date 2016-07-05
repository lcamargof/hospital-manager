<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;

class UserController extends Controller {

   /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index() {
      if(Auth::user()->role == 'master') {
         return view('admin.adminlayout')
            ->with('location', 'users')
            ->nest('content', 'admin.usersIndex', array('users' => User::with('doctor')->get()));
      } else {
         return response('Unauthorized.', 401);
      }
   }

   /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request, $id)
   {
      $this->validate($request, [
         'password' => 'required'
      ]);

      $user = User::find($id);
      $user->password = bcrypt($request->password);
      $user->save();

      return 'success';
   }
}

?>