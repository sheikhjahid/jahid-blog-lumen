<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\JWTAuth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;
    protected $jwt;
    public function __construct(User $user, JWTAuth $jwt)
    {
        $this->user = $user;  
        $this->jwt = $jwt;
    }


    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {

            if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        return response()->json([
            compact('token')
        ]);

    }

    public function index()
    {
       
       try{

            $user = $this->user->where('id','!=',$this->jwt->user()->id)->get();
        }
        catch(\Exception $e)
        {
            $user = $e->getMessage();
        }

        return response()->json($user);
    }

    
    public function profile()
    {
        try{

            $user = $this->user->where('id',$this->jwt->user()->id)->with('posts')->first();
        
        }
        catch(\Exception $e)
        {
            $user = $e->getMessage();
        }

        return response()->json($user);
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try{
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required'
            ]);
    
            // $request['api_token'] = Hash::make($request->email);
            $request['password'] = Hash::make($request->password);
        
            $user = $this->user->create($request->all());
            DB::commit();

        }
        catch(\Exception $e)
        {
            DB::rollback();
            $user = $e->getMessage();
        }
        
        return response()->json($user);
        
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try{

            $this->validate($request, [
                'name' => 'required',
            ]);
    
            $user = $this->user->find($this->jwt->user()->id);
                
            $user->update($request->all());
            DB::commit();
            
        }
        catch(\Exception $e)
        {
            DB::rollback();
            $user = $e->getMessage();
        }

        return response()->json($user);
    }

    public function delete()
    {
        try{

            $user = $this->user->where('id',$this->jwt->user()->id)->delete();
        }
        catch(\Exception $e)
        {
            $user = $e->getMessage();
        }
        
        if($user!=1)
        {
            return response()->json([
                'message' => 'Unable to delete'
            ], 400);
        }

        return response()->json([
            'message' => 'Post data deleted successfully'
        ],204);

    }

    public function logout()
    {
        try{

            $response = $this->jwt->parseToken()->invalidate();
        }
        catch(\Exception $e)
        {
            $response =  $e->getMessage();
        }

        return response()->json([
            'message' => 'Logged-out successfully'
        ]);
    }

}
