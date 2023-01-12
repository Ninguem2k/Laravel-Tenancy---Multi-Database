<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tenant;

class RegisterTenantController extends Controller
{
    public function register(){
        return view('auth.register-tenant');
    }

    public function store(Request $request){

        $tenant = Tenant::create($request->all());
    
        $tenant->createDomain(['domain'=>$request->domain]);
    
    }
}
