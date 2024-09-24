<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\AjouterController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\ConsulterController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\PresencePDFController;
use App\Http\Controllers\SalaryReportController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\AnneeAcademiqueController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
	Route::get('dashboard', [SessionsController::class, 'index'])->name('dashboard');
    route::post('/update-annee-academique', [AnneeAcademiqueController::class, 'update'])->name('update.annee_academique');
    Route::get('dashboard-du-prof', [ProfController::class, 'index'])->name('dashboard-du-prof');
    route::get('/recherche', [RechercheController::class, 'index'])->name('recherche.results');

    Route::get('ajouter/institution', [AjouterController::class, 'institution'])->name('ajouter.institution');
    Route::post('ajouter/institution', [AjouterController::class, 'institution_store'])->name('ajouter.institution.store');

    Route::get('ajouter/unite-enseignement', [AjouterController::class, 'unite_enseignement'])->name('ajouter.unite_enseignement');
    Route::post('ajouter/unite-enseignement', [AjouterController::class, 'unite_enseignement_store'])->name('ajouter.unite_enseignement.store');

    Route::get('ajouter/filiere', [AjouterController::class, 'filiere'])->name('ajouter.filiere');
    Route::post('ajouter/filiere', [AjouterController::class, 'filiere_store'])->name('ajouter.filiere.store');

    Route::get('ajouter/salle', [AjouterController::class, 'salle'])->name('ajouter.salle');
    Route::post('ajouter/salle', [AjouterController::class, 'salle_store'])->name('ajouter.salle.store');
    Route::post('/profile/update-photo', [InfoUserController::class, 'updatePhoto'])->name('profile.update-photo');


	Route::get('billing', function () {
		return view('billing');
	})->name('billing');

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');


	Route::get('rtl', function () {
		return view('rtl');
	})->name('rtl');



	Route::get('user-management', [UserController::class, 'index']
    )->name('user-management');

	Route::get('tables', function () {
		return view('tables');
	})->name('tables');

    Route::get('virtual-reality', function () {
		return view('virtual-reality');
	})->name('virtual-reality');


    Route::get('/logout', [SessionsController::class, 'destroy']);
    Route::get('/notes', [NotesController::class, 'index']);
    Route::get('/evenements-planifies', [EvenementController::class, 'evenements_blue']);

	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::get('/information-sur-un-utilisateur/{id}', [InfoUserController::class, 'find'])->name('info.user');
    Route::get('/login', function () {
		return view('dashboard');
	})->name('sign-up');

    Route::get('/salary-report', [SalaryReportController::class, 'index'])->name('salary.report');
    Route::get('/institutions', [InstitutionController::class, 'index']);
    Route::get('/evenements', [EvenementController::class, 'index']);
    Route::post('/evenements', [EvenementController::class, 'store'])->name('evenements.store');
    Route::get('evenements/{id}', [EvenementController::class, 'showForModif'])->name('evenements.show');
    Route::post('evenements/{id}', [EvenementController::class, 'update']);
    Route::delete('/evenements/{id}', [EvenementController::class, 'destroy']);
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/message/{message}', [UserController::class, 'destroyMessage'])->name('message.destroy');
    Route::patch('/evenements/{id}/start', [EvenementController::class, 'startEvent']);
    Route::post('/evenement/{id}/store-notes-and-comments', [EvenementController::class, 'storeNotesAndComments'])->name('evenement.storeNotesAndComments');
    Route::get('/commencer-cours/{id}', [EvenementController::class, 'show']);
    Route::put('/terminer/{id}', [EvenementController::class, 'terminer'])->name('evenement.terminer');
    Route::get('/get-students/{eventId}', [EvenementController::class, 'getStudents']);
    Route::get('/get-universite', [EvenementController::class, 'getUniversite']);
    Route::post('/save-attendance/{eventId}', [EvenementController::class, 'saveAttendance']);
    Route::get('/consulter-cours/{eventId}', [ConsulterController::class, 'index']);
    Route::get('/presence-pdf/{evenementId}', [PresencePDFController::class, 'generatePDF'])
        ->name('presence.pdf');
    Route::get('/presence/{evenementId}', [PresencePDFController::class, 'sansPDF'])
        ->name('sans.pdf');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::get('configuration-globale', [EvenementController::class, 'creation'])->name('evenements.creation');
    Route::post('evenement', [EvenementController::class, 'storage'])->name('evenements.storage');


});



Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('get.register');
    Route::post('/register', [RegisterController::class, 'store'])->name('post.register');
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');


});

Route::middleware('admin')->group(function () {
    Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');
});

Route::redirect('/dashboard', '/login');
Route::get('/login', function () {
    return view('session/login-session');
})->name('login');
Route::get('desactiver', function () {
    return view('desactive');
})->name('desactiver');

Route::get('ajouter/annee-scolaire', [AjouterController::class, 'annee'])->name('ajouter.annee_scolaire');
Route::post('ajouter/annee-scolaire', [AjouterController::class, 'annee_store'])->name('ajouter.annee_scolaire.store');
Route::post('ajouter/annee-scolaire2', [AjouterController::class, 'annee_store2'])->name('ajouter.annee_scolaire.store2');

