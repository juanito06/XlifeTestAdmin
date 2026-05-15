<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OA;

class StatsController extends Controller
{
    #[OA\Get(
        path: "/api/stats/overview",
        summary: "KPIs principales de la plataforma",
        tags: ["Estadísticas"],
        responses: [new OA\Response(response: 200, description: "Totales en tiempo real")]
    )]
    public function overview()
    {
        return response()->json([
            'total_users'    => User::count(),
            'posts_today'    => Post::whereDate('created_at', today())->count(),
            'active_reports' => Report::where('status', 'pending')->count(),
            'active_users'   => User::where('status', 'active')->count(),
        ]);
    }

    #[OA\Get(
        path: "/api/stats/user-growth",
        summary: "Crecimiento de usuarios por rango",
        tags: ["Estadísticas"],
        parameters: [
            new OA\Parameter(name: "range", in: "query", required: false, schema: new OA\Schema(type: "string", enum: ["day", "week", "month", "year"])),
        ],
        responses: [new OA\Response(response: 200, description: "Datos de crecimiento")]
    )]
    public function userGrowth(Request $request)
    {
        $range = $request->get('range', 'month');

        $data = match($range) {
            'day'   => User::select(DB::raw("strftime('%H', created_at) as label"), DB::raw('count(*) as count'))
                           ->whereDate('created_at', today())
                           ->groupBy('label')->orderBy('label')->get(),
            'week'  => User::select(DB::raw("strftime('%w', created_at) as label"), DB::raw('count(*) as count'))
                           ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                           ->groupBy('label')->orderBy('label')->get(),
            'year'  => User::select(DB::raw("strftime('%m', created_at) as label"), DB::raw('count(*) as count'))
                           ->whereYear('created_at', now()->year)
                           ->groupBy('label')->orderBy('label')->get(),
            default => User::select(DB::raw("strftime('%d', created_at) as label"), DB::raw('count(*) as count'))
                           ->whereMonth('created_at', now()->month)
                           ->groupBy('label')->orderBy('label')->get(),
        };

        return response()->json(['data' => $data]);
    }

    #[OA\Get(
        path: "/api/stats/geographic",
        summary: "Distribución geográfica de usuarios",
        tags: ["Estadísticas"],
        responses: [new OA\Response(response: 200, description: "Usuarios por país")]
    )]
    public function geographic()
    {
        $data = User::select('country', DB::raw('count(*) as count'))
            ->groupBy('country')
            ->orderByDesc('count')
            ->get();

        return response()->json(['data' => $data]);
    }

    #[OA\Get(
        path: "/api/stats/viral-posts",
        summary: "Posts ordenados por viral score",
        tags: ["Estadísticas"],
        parameters: [
            new OA\Parameter(name: "pageSize", in: "query", required: false, schema: new OA\Schema(type: "integer")),
        ],
        responses: [new OA\Response(response: 200, description: "Posts virales paginados")]
    )]
    public function viralPosts(Request $request)
    {
        $posts = Post::select('*', DB::raw('(likes * 2 + shares * 5 + views) / 10.0 as viral_score'))
            ->orderByDesc('viral_score')
            ->paginate($request->get('pageSize', 20));

        return response()->json([
            'data' => $posts->map(function ($post) {
                return [
                    'id'          => $post->id,
                    'title'       => $post->title,
                    'views'       => $post->views,
                    'likes'       => $post->likes,
                    'shares'      => $post->shares,
                    'viral_score' => round($post->viral_score, 2),
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