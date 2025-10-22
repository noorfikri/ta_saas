<?php

namespace App\Http\Controllers;

use App\Models\Instance;
use App\Services\InstanceService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstanceController extends Controller
{
    protected $provisioningService;

    public function __construct(InstanceService $provisioningService)
    {
        $this->provisioningService = $provisioningService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $instances = Auth::user()->instances()->get();
        return view('instance.index', compact('instances'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:instances,name',
        ]);

        $instance = Auth::user()->instances()->create([
            'name' => $validated['name'],
            'status' => 'PENDING',
        ]);

        $this->provisioningService->provisionInstance($instance);

        $message = 'Aplikasi sedang dibuat.';
        
        if ($request->ajax()) {
            return response()->json(['message' => $message, 'instance' => $instance]);
        }

        return redirect()->route('instances.index')->with(['success'=>true, 'msg'=>$message, 'instance'=>$instance]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Instance  $instance
     * @return \Illuminate\Http\Response
     */
    public function show(Instance $instance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Instance  $instance
     * @return \Illuminate\Http\Response
     */
    public function edit(Instance $instance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Instance  $instance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instance $instance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Instance  $instance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Instance $instance)
    {
        if (Auth::id() !== $instance->user_id) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Anda tidak dapat menghapus sistem yang bukan milik anda.'], 403);
            }
            return redirect()->route('instances.index')->with('failed', "Anda tidak dapat menghapus sistem yang bukan milik anda.");
        }

        $stackId = $instance->aws_stack_id;
        $tenantName = $instance->name;

        if ($stackId) {
            $instance->update(['status' => 'deleting']);
            $this->provisioningService->deprovisionInstance($stackId);
        } else {
           $instance->delete();
        }

        $message = "Penghapusan sistem untuk sistem '{$tenantName}' sedang berjalan.";
        
        if ($request->ajax()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->route('instances.index')->with('success', $message);
    }

    public function showCreate(Request $request){
        return response()->json(array(
            'status'=>'ok',
            'msg'=>view('instance.create')->render()
        ),200);
    }

    public function status()
    {
        $this->provisioningService->updateInstanceStatuses();
        $instances = Auth::user()->instances()->latest()->get();
        return response()->json(['instances' => $instances]);
    }
}
