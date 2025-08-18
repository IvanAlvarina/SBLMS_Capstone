<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RegisteredUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendStudentCredentialsMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index()
    {
        return view('UserManagement.UserManagementView');
    }

    public function getUsers(Request $request)
    {
        $query = RegisteredUsers::where('account_status', 'Approved');

        $totalData = $query->count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where('fullname', 'like', "%{$search}%");
            $query->orWhere('student_no', 'like', "%{$search}%");
            $query->orWhere('email', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['fullname', 'role', 'student_no', 'email', 'address', 'status'];
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // Pagination
        $users = $query
                ->offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();

        $data = $users->map(function ($user) {
            return [
                'fullname' => $user->fullname,
                'student_no'  => $user->student_no,
                'email'        => $user->email,
                'address'     => $user->address ?? 'N/A',
                'role'        => '<span class="badge bg-label-primary">'
                                    . e($user->role) .
                                '</span>',
                'account_status'       => '<span class="badge bg-label-success">'
                                    . e($user->account_status) .
                                '</span>',
                'action'       => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="">
                                <i class="ti ti-pencil me-1"></i> Edit
                            </a>
                            <a class="dropdown-item text-danger" href="javascript:void(0);" 
                            >
                                <i class="ti ti-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>'
            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    public function getUsersForApproval(Request $request)
    {
        $query = RegisteredUsers::where('account_status', 'Pending');

        $totalData = $query->count();

        // Search filter
        if ($search = $request->input('search.value')) {
            $query->where('fullname', 'like', "%{$search}%");
            $query->orWhere('student_no', 'like', "%{$search}%");
            $query->orWhere('email', 'like', "%{$search}%");
        }

        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $orderColIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columns = ['fullname', 'role', 'student_no', 'email', 'address', 'status'];
            $query->orderBy($columns[$orderColIndex], $orderDir);
        }

        // Pagination
        $users = $query
                ->offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();

        $data = $users->map(function ($user) {
            return [
                'fullname' => $user->fullname,
                'student_no'  => $user->student_no,
                'email'        => $user->email,
                'address'     => $user->address ?? 'N/A',
                'role'        => '<span class="badge bg-label-primary">'
                                    . e($user->role) .
                                '</span>',
                'account_status'       => '<span class="badge bg-label-danger">'
                                    . e($user->account_status) .
                                '</span>',
                'action'       => '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="' . route('user-management.approve', $user->id) . '">
                                <i class="ti ti-pencil me-1"></i> Approve
                            </a>

                            <a class="dropdown-item text-danger" href="javascript:void(0);" 
                            >
                                <i class="ti ti-trash me-1"></i> Delete
                            </a>
                        </div>
                    </div>'
            ];
        });

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    public function forApprovalIndex()
    {
        return view('UserManagement.PendingApprovalView');
    }

    public function approveUser($id)
    {
        $user = RegisteredUsers::findOrFail($id);

        // Generate a random 8-character password
        $plainPassword = Str::random(8);

        // Save hashed password
        $user->password = Hash::make($plainPassword);
        $user->account_status = 'Approved';
        $user->save();

        // Send credentials email
        Mail::to($user->email)->send(new SendStudentCredentialsMail(
            $user->fullname,
            $user->student_no,
            $plainPassword
        ));

        return redirect()->back()->with('success', 'User approved and credentials sent!');
    }

}
