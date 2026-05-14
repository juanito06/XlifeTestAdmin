<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Session;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(title: "XLife Admin API", version: "1.0.0", description: "API REST para el Panel de Administración de XLife")]
#[OA\Server(url: "http://localhost:8000", description: "Servidor local")]
class UserController extends Controller
{
    #[OA\Get(
        path: "/api/users",
        summary: "Lista de usuarios",
        tags: ["Usuarios"],
        parameters: [
            new OA\Parameter(name: "status", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "search", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "pageSize", in: "query", required: false, schema: new OA\Schema(type: "integer")),
        ],
        responses: [new OA\Response(response: 200, description: "Lista paginada de usuarios")]
    )]
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate($request->get('pageSize', 20));

        return response()->json([
            'data' => $users->map(function ($user) {
                return [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'status'     => $user->status,
                    'country'    => $user->country,
                    'lastActive' => $user->last_login_at?->diffForHumans(),
                ];
            }),
            'meta' => [
                'total'    => $users->total(),
                'page'     => $users->currentPage(),
                'pageSize' => $users->perPage(),
                'lastPage' => $users->lastPage(),
            ],
        ]);
    }

    #[OA\Get(
        path: "/api/users/{id}",
        summary: "Detalle de un usuario",
        tags: ["Usuarios"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Detalle del usuario"),
            new OA\Response(response: 404, description: "Risorsa non trovata"),
        ]
    )]
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Risorsa non trovata'], 404);
        }

        return response()->json([
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'status'     => $user->status,
            'country'    => $user->country,
            'lastActive' => $user->last_login_at?->diffForHumans(),
            'created_at' => $user->created_at,
        ]);
    }

    #[OA\Get(
        path: "/api/users/{id}/posts",
        summary: "Posts de un usuario",
        tags: ["Usuarios"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Lista paginada de posts del usuario"),
            new OA\Response(response: 404, description: "Risorsa non trovata"),
        ]
    )]
    public function posts(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Risorsa non trovata'], 404);
        }

        $posts = $user->posts()->paginate($request->get('pageSize', 20));

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

    #[OA\Get(
        path: "/api/users/{id}/activity",
        summary: "Actividad por hora de un usuario (24 puntos)",
        tags: ["Usuarios"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
        ],
        responses: [
            new OA\Response(response: 200, description: "24 puntos de actividad por hora"),
            new OA\Response(response: 404, description: "Risorsa non trovata"),
        ]
    )]
    public function activity($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Risorsa non trovata'], 404);
        }

        $sessions = Session::where('user_id', $id)
            ->whereDate('started_at', today())
            ->get();

        $hours = [];
        for ($i = 0; $i < 24; $i++) {
            $hours[$i] = 0;
        }

        foreach ($sessions as $session) {
            $hour = (int) $session->started_at->format('G');
            $hours[$hour] += $session->duration_minutes;
        }

        $data = [];
        foreach ($hours as $hour => $minutes) {
            $data[] = [
                'hour'    => $hour,
                'minutes' => $minutes,
            ];
        }

        return response()->json(['data' => $data]);
    }
}