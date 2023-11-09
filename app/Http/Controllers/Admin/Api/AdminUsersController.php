<?php

namespace App\Http\Controllers\Admin\Api;

use App\Models\Admin\AdminUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminUserRequest;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use Illuminate\Support\Facades\Hash;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = AdminUser::simplePaginate(15);
        return response()->json($admins);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\StoreAdminUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminUserRequest $request)
    {
        $admin_user = AdminUser::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'status' => AdminUser::ACTIVE,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Admin user successfully added.',
            'data' => $admin_user->toArray()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\AdminUser  $admin_user
     * @return \Illuminate\Http\Response
     */
    public function show(AdminUser $admin_user)
    {
        return response()->json($admin_user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\UpdateAdminUserRequest  $request
     * @param  \App\Models\Admin\AdminUser  $admin_user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminUserRequest $request, AdminUser $admin_user)
    {
        $admin_user->first_name = $request?->first_name ?? $admin_user->first_name;
        $admin_user->last_name = $request?->last_name ?? $admin_user->last_name;
        $admin_user->email = $request?->email ?? $admin_user->email;
        $admin_user->status = $request?->status ?? $admin_user->status;
        $admin_user->save();

        return response()->json([
            'message' => 'Admin user successfully updated.',
            'data' => $admin_user->toArray()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\AdminUser  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminUser $admin_user)
    {
        $admin_user->delete();

        return response([
            'message' => 'Admin user successfully deleted.'
        ]);
    }
}
