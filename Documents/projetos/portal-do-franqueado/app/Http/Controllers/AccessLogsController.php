<?php

namespace App\Http\Controllers;

use App\ACL\Recurso;
use Illuminate\Http\Request;
use App\Models\Historiae\AccessLog;
use Illuminate\Contracts\Routing\ResponseFactory;

class AccessLogsController extends Controller
{
    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected $response;

    /**
     * Instantiate a new controller instance.
     *
     * @return  void
     */
    public function __construct(ResponseFactory $response)
    {
        $this->response = $response;
        $this->middleware('acl:' . Recurso::SUPER_ADMIN);
    }

    /**
     * Display a listing of the resource.
     *
     * @return  \Illuminate\Http\Response
     */
    public function index()
    {
        $accesses = AccessLog::with('user')->orderBy('created_at', 'desc');

        if (request()->has('filters')) {
            $filters = collect(request('filters'))->reject(function ($v) {
                return ! strlen($v);
            });

            if ($filters->has('url')) {
                $accesses = $accesses->where('url', 'LIKE', "%{$filters->get('url')}%");
            }

            if ($filters->has('domain')) {
                $accesses = $accesses->where('domain', 'LIKE', "%{$filters->get('domain')}%");
            }

            if ($filters->has('method')) {
                $accesses = $accesses->where('method', $filters->get('method'));
            }

            if ($filters->has('user_id')) {
                $accesses = $accesses->where('user_id', $filters->get('user_id'));
            }

            if ($filters->has('status')) {
                $accesses = $accesses->where('status', $filters->get('status'));
            }

            if ($filters->has('ip')) {
                $accesses = $accesses->where('ip', $filters->get('ip'));
            }

            if ($filters->has('start_at')) {
                $accesses = $accesses->whereDate('created_at', '>=', date($filters->get('start_at')));
            }

            if ($filters->has('end_at')) {
                $accesses = $accesses->whereDate('created_at', '<=', date($filters->get('end_at')));
            }
        }

        return $this->response->view('historiae.accesses', [
            'methods' => AccessLog::select('method')->distinct()->pluck('method'),
            'statuses' => AccessLog::select('status')->orderBy('status')->distinct()->pluck('status'),
            'domains' => AccessLog::select('domain')->orderBy('domain')->distinct()->pluck('domain'),
            'users' => AccessLog::with('user')->select('user_id')->distinct()->where('user_id', '!=', null)->get()->pluck('user.nome', 'user_id')->sort(),
            'accesses' => $accesses->paginate(15),
        ]);
    }
}
