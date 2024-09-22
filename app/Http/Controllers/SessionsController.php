<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Etudiant;
use App\Models\Presence;
use App\Models\Evenement;
use App\Models\Institution;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Jobs\UpdateColorsJob;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class SessionsController extends Controller
{


    public function index()
    {
        // Professeurs
        $totalProfessors = User::where('role', 'professeur')->count();
        $activeProfessors = User::where('role', 'professeur')->where('is_active', true)->count();
        $inactiveProfessors = User::where('role', 'professeur')->where('is_active', false)->count();

        // Cours
        $totalCourses = Evenement::count();
        $completedCourses = Evenement::where('color', 'green')->count();
        $pendingCourses = Evenement::where('color', 'blue')->count();
        $missedCourses = Evenement::where('color', 'red')->count();

        // Étudiants
        $totalStudents = Etudiant::count();
        $presentStudents = Presence::where('status', 'Présent(e)')->count();
        $absentStudents = Presence::where('status', 'Absent(e)')->count();

        // Institutions
        $totalInstitutions = Institution::count();
        $activeInstitutions = Institution::withCount('evenements')->orderBy('evenements_count', 'desc')->limit(5)->get();

        // Notifications
        $notifications = Notification::with('user')->get();

        //Messages
        $messages = Message::with('user')->get();

        // Graphiques
        $professorStats = [
            'Active' => $activeProfessors,
            'Inactive' => $inactiveProfessors,
        ];

        $courseStats = [
            'Completed' => $completedCourses,
            'Pending' => $pendingCourses,
            'Missed' => $missedCourses,
        ];

        $attendanceStats = [
            'Present' => $presentStudents,
            'Absent' => $absentStudents,
        ];

        $institutionStats = $activeInstitutions->mapWithKeys(function ($institution) {
            return [$institution->nom => $institution->evenements_count];
        });

        return view('dashboard', compact(
            'totalProfessors', 'activeProfessors', 'inactiveProfessors',
            'totalCourses', 'completedCourses', 'pendingCourses', 'missedCourses',
            'totalStudents', 'presentStudents', 'absentStudents',
            'totalInstitutions', 'activeInstitutions',
            'notifications', 'professorStats', 'courseStats', 'attendanceStats', 'institutionStats', 'messages'
        ));
    }



    public function create()
    {
        return view('session.login-session');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(Auth::attempt($attributes))
        {

            UpdateColorsJob::dispatch();

            $role = Auth::user()->role;
            $users = User::where('role', 'professeur')->get();

            if ($role == 'admin') {
                return redirect()->route('dashboard')->with('users', $users);
            } elseif ($role == 'professeur' && Auth::user()->is_active) {
                return redirect()->route('dashboard-du-prof');
             } else {
                return redirect()->route('desactiver');
             }

        }
        else{

            return back()->withErrors(['email'=>'Email ou password invalide.']);
        }
    }

    public function destroy()
    {

        Auth::logout();

        return redirect('/login')->with(['success'=>'You\'ve been logged out.']);
    }




}
