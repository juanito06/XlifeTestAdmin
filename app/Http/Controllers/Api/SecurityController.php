<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\SecurityLog;
use App\Models\SecuritySetting;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SecurityController extends Controller
{
    #[OA\Get(
        path: "/api/security/logs",
        summary: "Logs de seguridad",
        tags: ["Seguridad"],
        parameters: [
            new OA\Parameter(name: "severity", in: "query", required: false, schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "pageSize", in: "query", required: false, schema: new OA\Schema(type: "integer")),
        ],
        responses: [new OA\Response(response: 200, description: "Lista paginada de logs")]
    )]
    public function logs(Request $request)
    {
        $query = SecurityLog::query();

        if ($request->has('severity')) {
            $query->where('severity', $request->severity);
        }

        $logs = $query->latest()->paginate($request->get('pageSize', 20));

        return response()->json([
            'data' => $logs->map(function ($log) {
                return [
                    'id'          => $log->id,
                    'severity'    => $log->severity,
                    'event'       => $log->event,
                    'description' => $log->description,
                    'ip_address'  => $log->ip_address,
                    'created_at'  => $log->created_at,
                ];
            }),
            'meta' => [
                'total'    => $logs->total(),
                'page'     => $logs->currentPage(),
                'pageSize' => $logs->perPage(),
                'lastPage' => $logs->lastPage(),
            ],
        ]);
    }

    #[OA\Get(
        path: "/api/security/blocked-ips",
        summary: "IPs bloqueadas",
        tags: ["Seguridad"],
        parameters: [
            new OA\Parameter(name: "search", in: "query", required: false, schema: new OA\Schema(type: "string")),
        ],
        responses: [new OA\Response(response: 200, description: "Lista de IPs bloqueadas")]
    )]
    public function blockedIps(Request $request)
    {
        $query = Blacklist::where('type', 'ip');

        if ($request->has('search')) {
            $query->where('value', 'like', '%' . $request->search . '%');
        }

        $ips = $query->paginate($request->get('pageSize', 20));

        return response()->json([
            'data' => $ips->map(function ($ip) {
                return [
                    'id'         => $ip->id,
                    'value'      => $ip->value,
                    'reason'     => $ip->reason,
                    'created_at' => $ip->created_at,
                ];
            }),
            'meta' => [
                'total'    => $ips->total(),
                'page'     => $ips->currentPage(),
                'pageSize' => $ips->perPage(),
                'lastPage' => $ips->lastPage(),
            ],
        ]);
    }

    #[OA\Get(
        path: "/api/security/blocked-phones",
        summary: "Teléfonos bloqueados",
        tags: ["Seguridad"],
        parameters: [
            new OA\Parameter(name: "search", in: "query", required: false, schema: new OA\Schema(type: "string")),
        ],
        responses: [new OA\Response(response: 200, description: "Lista de teléfonos bloqueados")]
    )]
    public function blockedPhones(Request $request)
    {
        $query = Blacklist::where('type', 'phone');

        if ($request->has('search')) {
            $query->where('value', 'like', '%' . $request->search . '%');
        }

        $phones = $query->paginate($request->get('pageSize', 20));

        return response()->json([
            'data' => $phones->map(function ($phone) {
                return [
                    'id'         => $phone->id,
                    'value'      => $phone->value,
                    'reason'     => $phone->reason,
                    'created_at' => $phone->created_at,
                ];
            }),
            'meta' => [
                'total'    => $phones->total(),
                'page'     => $phones->currentPage(),
                'pageSize' => $phones->perPage(),
                'lastPage' => $phones->lastPage(),
            ],
        ]);
    }

    #[OA\Get(
        path: "/api/security/settings",
        summary: "Configuración global de seguridad",
        tags: ["Seguridad"],
        responses: [new OA\Response(response: 200, description: "Configuración de seguridad")]
    )]
    public function settings()
    {
        $settings = SecuritySetting::first();
        return response()->json($settings);
    }
}