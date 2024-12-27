<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\StorePostRequest;
use App\Services\ElasticsearchService;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\PostResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Post;
use Exception;

class PostController extends Controller
{
    protected $elasticsearch;

    /**
     * @param ElasticsearchService $elasticsearch
     */
    public function __construct(ElasticsearchService $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try{
            $cacheKey = 'posts_page_' . request('page', 1);

            $posts = Cache::tags("posts")->remember($cacheKey, 3600, function (){
                return Post::with('comments.user')->published()->latest()->paginate(10);
            });

            return response()->json([
                'data'    => PostResource::collection($posts),
                'message' => ""
            ]);
        } catch (
        Exception $exception){
            return response()->json([
                'data'    => [],
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param StorePostRequest $request
     * @return JsonResponse
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        try{
            if ($request->hasFile('image')){
                $fileName = Str::random(40) . '.' . $request->file('image')->getClientOriginalExtension();
                $path     = $request->file('image')->storeAs('images', $fileName, 'public');
            } else{
                $path = null;
            }
            $published_at = strtotime($request->published_at);
            $status       = ( $published_at > strtotime('now') )? 0: 1;
            $post         = Post::create([
                'user_id'          => Auth::Guard('api')->id(),
                'title'            => $request->title,
                'excerpt'          => $request->excerpt,
                'description'      => $request->description,
                'image'            => $path,
                'keywords'         => $request->keywords,
                'meta_title'       => $request->meta_title,
                'meta_description' => $request->meta_description,
                'published_at'     => $request->published_at,
                'status'           => $status,
            ]);

            // Clear blog list cache to reflect new post
            Cache::tags('posts')->flush();

            return response()->json([
                'data'    => new PostResource($post),
                'message' => ""
            ], 201);
        } catch (Exception $exception){
            return response()->json([
                'data'    => [],
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try{
            $cacheKey = "post_{$id}";

            $post = Cache::remember($cacheKey, 3600, function () use ($id){
                return Post::with('comments')->findOrFail($id);
            });

            return response()->json([
                'data'    => new PostResource($post),
                'message' => ''
            ]);
        } catch (Exception $exception){
            return response()->json([
                'data'    => [],
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @param UpdatePostRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdatePostRequest $request, $id): JsonResponse
    {
        try{
            $post = Post::findOrFail($id);

            if ($request->hasFile('image')){
                $fileName = Str::random(40) . '.' . $request->file('image')->getClientOriginalExtension();
                $path     = $request->file('image')->storeAs('images', $fileName, 'public');
            } else{
                $path = null;
            }
            $published_at = strtotime($request->published_at);
            $status       = ( $published_at > strtotime('now') )? 0: 1;
            $post->update([
                'user_id'          => Auth::Guard('api')->id(),
                'title'            => $request->title,
                'excerpt'          => $request->excerpt,
                'description'      => $request->description,
                'image'            => $path,
                'keywords'         => $request->keywords,
                'meta_title'       => $request->meta_title,
                'meta_description' => $request->meta_description,
                'published_at'     => $request->published_at,
                'status'           => $status,
            ]);


            // Invalidate cache for this post and blog list
            Cache::forget("post_{$id}");
            Cache::tags('posts')->flush();

            return response()->json([
                'data'    => new PostResource($post),
                'message' => ''
            ]);
        } catch (Exception $exception){
            return response()->json([
                'data'    => [],
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try{
            Post::destroy($id);

            // Invalidate cache for this post and blog list
            Cache::forget("post_{$id}");
            Cache::tags('posts')->flush();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $exception){
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        try{
            $query = $request->input('query');

            $results = $this->elasticsearch->search('posts', [
                'query' => [
                    'multi_match' => [
                        'query'  => $query,
                        'fields' => ['title^2', 'keywords', 'meta_title', 'meta_description'],
                    ],
                ],
            ]);

            return response()->json([
                'data'    => PostResource::collection($results),
                'message' => ''
            ]);
        } catch (Exception $exception){
            return response()->json([
                'data'    => [],
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
