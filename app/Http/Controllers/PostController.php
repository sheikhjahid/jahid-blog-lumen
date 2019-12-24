<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Post;
use Illuminate\Support\Facades\DB;
class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $post;
    public function __construct(Post $post)
    {
        $this->post = $post;
       
    }

    public function index()
    {
      
        try{
            $post = $this->post->orderBy('id','desc')->get();
        }
        catch(\Exception $e)
        {
            $post = $e->getMessage();
        }
        
        return response()->json($post);
    }

    
    public function single(int $id)
    {
        try{
            $post = $this->post->find($id);

            $this->post->where('id',$id)->update([
                'views' => $post->views + 1
            ]);
        }
        catch(\Exception $e)
        {
            $post = $e->getMessage();
        }
        

        return response()->json($post);
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try{

            $this->validate($request, [
                'title' => 'required'
            ]);
       
            $post = $this->post->create($request->all());
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
            $post = $e->getMessage();
        }
        

        return response()->json($post);
    }

    public function update(Request $request, int $id)
    {
        DB::beginTransaction();
        try{

            $this->validate($request, [
                'title' => 'required'
            ]);
    
            $post = $this->post->find($id);
    
            $post->update($request->all());

            DB::commit();

        }
        catch(\Exception $e)
        {
            DB::rollback();
            $post = $e->getMessage();
        }
    }

    public function delete(int $id)
    {
        try{

            $post = $this->post->find($id)->delete();
        
        }
        catch(\Exception $e)
        {
            $post = $e->getMessage();
        }

        if($post!=1)
        {
            return response()->json([
                'message' => 'Unable to delete'
            ],400);
        }

        return response()->json([
            'message' => 'Post data deleted successfully'
        ],204);

    }


}
