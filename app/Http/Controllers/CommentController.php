<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\Comment;
use Exception;

class CommentController extends Controller
{

    /**
     * @param StoreCommentRequest $request
     * @param $postId
     * @return JsonResponse
     */
    public function store(StoreCommentRequest $request, $postId): JsonResponse
    {
        try{
            $comment = Comment::create([
                'post_id' => $postId,
                'user_id' => Auth::guard('api')->id(),
                'content' => $request->comment,
            ]);
            Cache::tags('posts')->flush();
            Cache::flush();
            return response()->json([
                'data'    => $comment,
                'message' => ''
            ], Response::HTTP_CREATED);
        } catch (Exception $exception){
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, $id)
    {

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try{
            Comment::destroy($id);
            Cache::tags('posts')->flush();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $exception){
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
