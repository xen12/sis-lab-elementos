<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Role;
use App\Http\Controllers\StudentTaskController;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        switch (Auth::user()->role_id) {
            case Role::ADMIN:
                return view('components.sections.adminSection');
                break;
            case Role::PROFESSOR:
                $controller = new GraphicController();
                return $controller->index();
                break;
            case Role::AUXILIAR:
                $controller = new AssistanceController();
                return $controller->index();
                break;
            case Role::STUDENT:
                $controller = new StudentTaskController();
                return $controller->index();
                break;
        }
    }
}
