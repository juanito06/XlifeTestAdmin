<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CommentController extends Controller
{
    #[OA\Get(
        path: "/api/comments",
        summary: "Lista de comentarios",
        tags: ["Contenido"],
        parameters: [
            new OA\Parameter(name: "status", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "search", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "pageSize", in: "query", required: false, schema: new OA\Schema(type: "integer")),
        ],
        responses: [new OA\Response(response: 200, description: "Lista paginada de comentarios")]
    )]
    public function index(Request $request)
    {
        $query = Comment::with('user', 'post');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('body', 'like', '%' . $request->search . '%');
        }

        $comments = $query->paginate($request->get('pageSize', 20));

        return response()->json([
            'data' => $comments->map(function ($comment) {
                return [
                    'id'         => $comment->id,
                    'body'       => $comment->body,
                    'status'     => $comment->status,
                    'user'       => $comment->user?->name,
                    'post'       => $comment->post?->title,
                    'created_at' => $comment->created_at,
                ];
            }),
            'meta' => [
                'total'    => $comments->total(),
                'page'     => $comments->currentPage(),
                'pageSize' => $comments->perPage(),
                'lastPage' => $comments->lastPage(),
            ],
        ]);
    }
}