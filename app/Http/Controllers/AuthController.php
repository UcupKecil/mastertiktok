<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //NOTE POST /auth/login
    public function login(Request $request)
    {
        try {
            $rules = [
                'email'     => 'required|email|exists:users,email',
                'password'  => 'required|min:8'
            ];

            $messages = [
                'email.required'    => 'Email wajib diisi',
                'email.email'       => 'Email tidak valid',
                'email.exists'      => 'Email tidak terdaftar',
                'password.required' => 'Password wajib diisi',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all);
            }

            if ($request->has('remember')) {
                $remember = true;
            } else {
                $remember = false;
            }

            $data = [
                'email'     => $request->email,
                'password'  => $request->password,
            ];

            Auth::attempt($data, $remember);

            if (Auth::check()) {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->back()->withInput()->withErrors(['error' => 'Password salah!']);
            }
        } catch (Exception $e) {
            if (env(APP_ENV) == 'local') return dd($e);

            return abort(500);
        }
    }
    //NOTE GET /auth/logout
    function logout()
    {
        Auth::logout();

        Session::flush();

        return redirect()->to('/auth/login');
    }
    // NOTE GET /auth/register/{slug}
    public function setCourse($slug)
    {
        $valid = DB::table('courses')->where('slug', $slug)->first();

        if ($valid) Session::put('course', $valid->id);

        return redirect('/auth/register');
    }
    //NOTE POST /auth/register
    public function register(Request $request)
    {
        try {
            $rules = [
                'name'      => 'required',
                'email'     => 'required|email|unique:users,email',
                'password'  => 'required|min:8',
                'phone'     => 'required|unique:user_details,phone',
            ];

            $messages = [
                'name.required'     => 'Nama wajib diisi',
                'email.required'    => 'Email wajib diisi',
                'email.email'       => 'Email tidak valid',
                'email.unique'      => 'Email sudah terdaftar',
                'password.required' => 'Password wajib diisi',
                'password.min'      => 'Password harus mengandung lebih dari 8 karakter',
                'phone.required'    => 'Nomor telepon wajib diisi',
                'phone.unique'      => 'Nomor telepon sudah terdaftar',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all);
            }

            DB::transaction(function () use ($request) {
                $course = DB::table('courses')->where('id', Session::get('course'))->first();

                $referred_by = Session::get('referral') ?? null;

                if ($referred_by) {
                    $referred_by = DB::table('user_details')
                        ->where('uid', $referred_by)
                        ->first()
                        ->user_id;
                }

                $data   = [
                    'email'         => $request->email,
                    'name'          => $request->name,
                    'password'      => Hash::make($request->password)
                ];

                $user   = User::create($data);

                $user->syncRoles('Member');

                DB::table('user_details')->insert([
                    'created_at'    => date('Y-m-d H:i:s'),
                    'phone'         => $request->phone,
                    'uid'           => generateUid(),
                    'user_id'       => $user->id,
                ]);

                DB::table('orders')->insert([
                    'course_id'     => Session::get('course'),
                    'created_at'    => date('Y-m-d H:i:s'),
                    'referred_by'   => $referred_by,
                    'user_id'       => $user->id,
                    'total'         => $course->price
                ]);
            });

            Session::flush();

            $data = [
                'email'     => $request->email,
                'password'  => $request->password,
            ];

            Auth::attempt($data);

            if (Auth::check()) {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->back()->withInput()->withErrors(['error' => 'Password salah!']);
            }
        } catch (Exception $e) {
            if (env(APP_ENV) == 'local') return dd($e);

            return abort(500);
        }
    }
}
