<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PostController extends Controller
{
    #[OA\Get(
        path: "/api/posts",
        summary: "Lista de posts",
        tags: ["Contenido"],
        parameters: [
            new OA\Parameter(name: "status", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "search", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "pageSize", in: "query", required: false, schema: new OA\Schema(type: "integer")),
        ],
        responses: [new OA\Response(response: 200, description: "Lista paginada de posts")]
    )]
    public function index(Request $request)
    {
        $query = Post::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $posts = $query->paginate($request->get('pageSize', 20));

        return response()->json([
            'data' => $posts->map(function ($post) {
                return [
                    'id'          => $post->id,
                    'title'       => $post->title,
                    'status'      => $post->status,
                    'views'       => $post->views,
                    'likes'       => $post->likes,
                    'shares'      => $post->shares,
                    'viral_score' => $post->viral_score,
                    'user'        => $post->user?->name,
                    'created_at'  => $post->created_at,
                ];
            }),
            'meta' => [
                'total'    => $posts->total(),
                'page'     => $posts->currentPage(),
                'pageSize' => $posts->perPage(),
                'lastPage' => $posts->lastPage(),
            ],
        ]);
    }
}