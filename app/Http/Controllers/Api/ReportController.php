<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ReportController extends Controller
{
    #[OA\Get(
        path: "/api/reports",
        summary: "Lista de reportes",
        tags: ["Moderación"],
        parameters: [
            new OA\Parameter(name: "status", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "type", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "pageSize", in: "query", required: false, schema: new OA\Schema(type: "integer")),
        ],
        responses: [new OA\Response(response: 200, description: "Lista paginada de reportes")]
    )]
    public function index(Request $request)
    {
        $query = Report::with('user');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $reports = $query->paginate($request->get('pageSize', 20));

        return response()->json([
            'data' => $reports->map(function ($report) {
                return [
                    'id'         => $report->id,
                    'type'       => $report->type,
                    'status'     => $report->status,
                    'reason'     => $report->reason,
                    'user'       => $report->user?->name,
                    'created_at' => $report->created_at,
                ];
            }),
            'meta' => [
                'total'    => $reports->total(),
                'page'     => $reports->currentPage(),
                'pageSize' => $reports->perPage(),
                'lastPage' => $reports->lastPage(),
            ],
        ]);
    }

    #[OA\Get(
        path: "/api/reports/summary",
        summary: "Resumen de reportes por tipo",
        tags: ["Moderación"],
        responses: [new OA\Response(response: 200, description: "Conteo de reportes por tipo")]
    )]
    public function summary()
    {
        $types = ['spam', 'harassment', 'misinformation', 'nudity', 'other'];

        $data = [];
        foreach ($types as $type) {
            $data[] = [
                'id'    => $type,
                'label' => ucfirst($type),
                'count' => Report::where('type', $type)->count(),
            ];
        }

        return response()->json($data);
    }
}