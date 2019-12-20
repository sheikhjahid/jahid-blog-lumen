<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;  
    }

    public function index()
    {
       
        $user = $this->user->where('is_admin',false)->orderBy('id','desc')->get();
        return response()->json($user);
    }

    
    public function single(int $id)
    {
        $user = $this->user->find($id);

        return response()->json($user);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required'
        ]);

        $request['api_token'] = Hash::make($request->email);
        $request['password'] = Hash::make($request->password);
    
        $user = $this->user->create($request->all());

        return response()->json($user);
        
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $user = $this->user->find($id);

        $user->update($request->all());

        return response()->json([
            'data' => $user
        ]);
    }

    public function delete(int $id)
    {
        $user = $this->user->find($id)->delete();

        if($user!=1)
        {
            return response()->json([
                'message' => 'Unable to delete'
            ]);
        }

        return response()->json([
            'message' => 'Post data deleted successfully'
        ],204);

    }


}
