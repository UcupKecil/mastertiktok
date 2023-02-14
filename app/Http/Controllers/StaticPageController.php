<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StaticPageController extends Controller
{
    // NOTE GET /
    public function index()
    {
        $courses = DB::table('courses')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $data = [
            'courses'   => $courses,
            'title'     => 'Home'
        ];

        return view('pages.FE.index', $data);
    }
    // NOTE GET /course/{slug}
    public function course($slug)
    {
        $course = DB::table('courses')
            ->where('slug', $slug)
            ->first();

        if (!$slug) return abort(404);

        $videos = DB::table('course_videos')
            ->where('course_id', $course->id)
            ->get();

        $data = [
            'course'    => $course,
            'title'     => $course->name,
            'videos'    => $videos,
        ];

        return view('pages.FE.course.single', $data);
    }
    // NOTE GET /dashboard
    public function dashboard()
    {
        $user = DB::table('user_details')->where('user_id', Auth::user()->id)->first();

        $js = 'components.scripts.BE.' . strtolower(Auth::user()->getRoleNames()[0]) . '.dashboard';

        $data = [
            'js'    => $js,
            'title' => 'Dashboard',
            'user'  => $user,
        ];

        return view('pages.BE.' . strtolower(Auth::user()->getRoleNames()[0]) . '.dashboard', $data);
    }
    // NOTE GET /auth/login
    public function login()
    {
        $data = [
            'title' => 'Login'
        ];

        return view('pages.FE.auth.login', $data);
    }
    // NOTE GET /auth/register
    public function register()
    {
        if (!Session::get('course')) return redirect()->back();

        $course = DB::table('courses')->where('id', Session::get('course'))->first();

        if (!$course) return redirect()->back();

        $data = [
            'title'     => 'Register',
            'course'    => $course,
        ];

        return view('pages.FE.auth.register', $data);
    }
}
