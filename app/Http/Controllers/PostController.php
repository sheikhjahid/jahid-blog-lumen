<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Post;
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
       
        $post = $this->post->orderBy('id','desc')->get();
        return response()->json($post);
    }

    
    public function single(int $id)
    {
        $post = $this->post->find($id);

        return response()->json($post);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

           
        $post = $this->post->create($request->all());

        return response()->json($post);
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $post = $this->post->find($id);

        $post->update($request->all());

        return response()->json([
            'data' => $post
        ]);
    }

    public function delete(int $id)
    {
        $post = $this->post->find($id)->delete();

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
