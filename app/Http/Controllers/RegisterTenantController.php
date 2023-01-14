<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tenant;
use App\Http\Requests\RegisterTenantRequest;

class RegisterTenantController extends Controller
{
    public function register()
    {
        return view('auth.register-tenant');
    }

    public function store(RegisterTenantRequest $request)
    {
        $tenant = Tenant::create($request->validated());
    
        $tenant->createDomain(['domain'=>$request->domain]);
        
        return redirect(tenant_route($tenant->domains()->first()->domain, 'login'));
    }
}
