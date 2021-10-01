<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\PagesController::class, 'showIndex'])->name('welcome');

Auth::routes();

//Для роли админа
Route::group(['middleware' => 'role:admin'], function() {
    Route::get('/admin', [App\Http\Controllers\Roles\AdminController::class, 'showIndex'])->name('admin');
});

//Для роли менеджера
Route::group(['middleware' => 'role:manager'], function() {
    Route::get('/manager', [App\Http\Controllers\Roles\ManagerController::class, 'showIndex'])->name('manager');
});

// Для роли диспетчера
Route::group(['middleware' => 'role:owner'], function() {
  Route::get('/owner', [App\Http\Controllers\Roles\OwnerController::class, 'showIndex'])->name('owner');
});

// Для роли охранник поста
Route::group(['middleware' => 'role:guard-post'], function() {
  Route::get('/guard-post/auth', [App\Http\Controllers\Roles\GuardPostController::class, 'showAuth'])->name('guardpost.auth');
  Route::post('/guard-post/auth', [App\Http\Controllers\Roles\GuardPostController::class, 'Auth']);

  Route::group(['middleware' => 'guardpost'], function() {
    Route::get('/guard-post', [App\Http\Controllers\Roles\GuardPostController::class, 'showIndex'])->name('guardpost');
    Route::post('/guard-post/logout', [App\Http\Controllers\Roles\GuardPostController::class, 'Logout'])->name('guardpost.logout');
  });
});

// Для роли диспетчера
Route::group(['middleware' => 'role:dispatcher'], function() {
  Route::get('/dispatcher', [App\Http\Controllers\Roles\DispatcherController::class, 'showIndex'])->name('dispatcher');
});

// Маршруты АПИ
Route::post('/api/check-code', [App\Http\Controllers\Api\ProfileController::class, 'checkCode'])->name('api.checkCode');
//Objects
Route::post('/api/get-projects', [App\Http\Controllers\Api\ProjectController::class, 'getProjects'])->name('api.getProjects');
Route::post('/api/get-project', [App\Http\Controllers\Api\ProjectController::class, 'getProject'])->name('api.getProject');
Route::post('/api/get-project-group', [App\Http\Controllers\Api\ProjectController::class, 'getProjectGroup'])->name('api.getProjectGroup');
Route::post('/api/get-projects-for-rate', [App\Http\Controllers\Api\ProjectController::class, 'getProjectsForRate'])->name('api.getProjectsForRate');
Route::post('/api/get-project-groups-for-rate', [App\Http\Controllers\Api\ProjectController::class, 'getProjectGroupsForRate'])->name('api.getProjectGroupsForRate');
Route::post('/api/get-projects-for-list', [App\Http\Controllers\Api\ProjectController::class, 'getProjectsForList'])->name('api.getProjectsForList');
Route::post('/api/get-project-groups-for-list', [App\Http\Controllers\Api\ProjectController::class, 'getProjectGroupsForList'])->name('api.getProjectGroupsForList');
Route::post('/api/get-option-projects', [App\Http\Controllers\Api\ProjectController::class, 'getOptionProjects'])->name('api.getOptionProjects');
Route::post('/api/get-family-projects', [App\Http\Controllers\Api\ProjectController::class, 'getFamilyProjects'])->name('api.getFamilyProjects');
Route::post('/api/get-project-groups', [App\Http\Controllers\Api\ProjectController::class, 'getProjectGroups'])->name('api.getProjectGroups');
Route::post('/api/get-project-types', [App\Http\Controllers\Api\ProjectController::class, 'getProjectTypes'])->name('api.getProjectTypes');

//PassRates
Route::post('/api/get-pass-rate', [App\Http\Controllers\Api\PassRateController::class, 'getRate'])->name('api.getPassRate');
Route::post('/api/get-pass-plans', [App\Http\Controllers\Api\PassRateController::class, 'getPlans'])->name('api.getPassRatePlans');
Route::post('/api/get-pass-plan', [App\Http\Controllers\Api\PassRateController::class, 'getPlan'])->name('api.getPassRatePlan');

//Profiles
Route::post('/api/get-profiles', [App\Http\Controllers\Api\ProfileController::class, 'getProfiles'])->name('api.getProfiles');
Route::post('/api/get-available-profiles', [App\Http\Controllers\Api\ProfileController::class, 'getAvailableProfiles'])->name('api.getAvailableProfiles');
Route::post('/api/get-current-applicant-id', [App\Http\Controllers\Api\ProfileController::class, 'getCurrentApplicantId'])->name('api.getCurrentApplicantId');
Route::post('/api/get-applicants', [App\Http\Controllers\Api\ProfileController::class, 'getApplicants'])->name('api.getApplicans');
Route::post('/api/get-profile', [App\Http\Controllers\Api\ProfileController::class, 'getProfile'])->name('api.getProfile');

Route::post('/api/get-profile-documents', [App\Http\Controllers\Api\DocumentController::class, 'getProfileDocuments'])->name('api.getProfileDocuments');

//Cars
Route::post('/api/get-profile-cars', [App\Http\Controllers\Api\CarController::class, 'getProfileCars'])->name('api.ProfileCars');
Route::post('/api/get-cars', [App\Http\Controllers\Api\CarController::class, 'getCars'])->name('api.getcars');
Route::post('/api/get-car', [App\Http\Controllers\Api\CarController::class, 'getCar'])->name('api.getcar');
//Mechanizms
Route::post('/api/get-profile-mechanizms', [App\Http\Controllers\Api\MechanizmController::class, 'getProfileMechanizms'])->name('api.ProfileMechanizms');
Route::post('/api/get-mechanizms', [App\Http\Controllers\Api\MechanizmController::class, 'getMechanizms'])->name('api.getMechanizms');
Route::post('/api/get-mechanizm', [App\Http\Controllers\Api\MechanizmController::class, 'getMechanizm'])->name('api.getmechanizm');

Route::post('/api/get-full-pass-list', [App\Http\Controllers\Api\PassController::class, 'getFullPassList'])->name('api.getfullpasslist');
Route::post('/api/get-report-pass-list', [App\Http\Controllers\Api\PassController::class, 'getReportPassList'])->name('api.getReportPassList');
Route::post('/api/get-pass', [App\Http\Controllers\Api\PassController::class, 'getPass'])->name('api.getpass');

Route::post('/api/get-roles', [App\Http\Controllers\Api\RoleController::class, 'getRoles'])->name('api.getRoles');
Route::post('/api/get-roles-and-permissions', [App\Http\Controllers\Api\RoleController::class, 'getRolesAndPermissions'])->name('api.getRolesAndPermissions');

Route::post('/api/get-personal-account', [App\Http\Controllers\Api\PersonalAccountController::class, 'getPersonalAccount'])->name('api.getPersonalAccount');
Route::post('/api/get-personal-account-report', [App\Http\Controllers\Api\PersonalAccountController::class, 'getPersonalAccountReport'])->name('api.getPersonalAccountReport');
Route::post('/api/access-refill-personal-account', [App\Http\Controllers\Api\PersonalAccountController::class, 'checkAccessToRefill'])->name('api.checkAccessToRefill');
Route::post('/api/access-create-personal-account', [App\Http\Controllers\Api\PersonalAccountController::class, 'checkAccessToCreate'])->name('api.checkAccessToCreate');
Route::post('/api/get-current-role', [App\Http\Controllers\Api\RoleController::class, 'getCurrentRole'])->name('api.getCurrentRole');
Route::post('/api/get-users', [App\Http\Controllers\Api\UserController::class, 'getUsers'])->name('api.getUsers');
Route::post('/api/get-user', [App\Http\Controllers\Api\UserController::class, 'getUser'])->name('api.getUser');
Route::post('/api/get-user-profiles', [App\Http\Controllers\Api\UserController::class, 'getProfiles'])->name('api.getUserProfiles');
Route::post('/api/get-organizations', [App\Http\Controllers\Api\OrganizationController::class, 'getOrganizations'])->name('api.getOrganizations');
Route::post('/api/get-families', [App\Http\Controllers\Api\FamilyController::class, 'getFamilies'])->name('api.getFamilies');

//Activities
Route::post('/api/get-activities', [App\Http\Controllers\Api\ActivityController::class, 'getActivities'])->name('api.getActivities');
//Equipments
Route::post('/api/get-equipments', [App\Http\Controllers\Api\EquipmentController::class, 'getEquipments'])->name('api.getEquipments');
//Guards
Route::post('/api/get-guard-posts', [App\Http\Controllers\Api\GuardPostController::class, 'getGuardPosts'])->name('api.getGuardPosts');
Route::post('/api/get-guard-posts-for-list', [App\Http\Controllers\Api\GuardPostController::class, 'getGuardPostsForList'])->name('api.getGuardPostsForList');
Route::post('/api/get-guard-post', [App\Http\Controllers\Api\GuardPostController::class, 'getGuardPost'])->name('api.getGuardPost');
//Notifications
Route::post('/api/get-notifications', [App\Http\Controllers\Api\UserController::class, 'getNotifications'])->name('api.getNotifications');

//Для авторизованных пользователей
Route::group(['middleware' => 'auth'], function() {
  // Пользователи сайта
  Route::get('/user', [App\Http\Controllers\Users\UserController::class, 'showUpdateForm'])->name('user.updateForm');
  Route::post('/update-login-password', [App\Http\Controllers\Users\UserController::class, 'updateUser'])->name('user.update');

  //Профили
  Route::get('/control-profiles', [App\Http\Controllers\Profiles\ProfileController::class, 'controlProfiles'])->middleware('permission_through_role:control-profiles')->name('profiles.control');
  Route::get('/view-profiles', [App\Http\Controllers\Profiles\ProfileController::class, 'viewProfiles'])->middleware('permission_through_role:view-profiles')->name('profiles.view');
  Route::get('/profile', [App\Http\Controllers\Profiles\ProfileController::class, 'showProfile'])->name('profile');

  //Проекты
  Route::get('/control-projects', [App\Http\Controllers\Projects\ProjectController::class, 'controlProjects'])->middleware('permission_through_role:control-projects')->name('projects.control');
  Route::post('/project/update', [App\Http\Controllers\Projects\ProjectController::class, 'update'])->middleware('permission_through_role:control-projects')->name('project.update');
  Route::post('/project/create', [App\Http\Controllers\Projects\ProjectController::class, 'create'])->middleware('permission_through_role:control-projects')->name('project.create');

  //Охранные посты
  Route::get('/control-guardposts', [App\Http\Controllers\GuardPosts\GuardPostController::class, 'controlGuardPosts'])->middleware('permission_through_role:control-guardposts')->name('guardposts.control');
  Route::post('/guardpost/update', [App\Http\Controllers\GuardPosts\GuardPostController::class, 'update'])->middleware('permission_through_role:control-guardposts')->name('guardposts.update');
  Route::post('/guardpost/create', [App\Http\Controllers\GuardPosts\GuardPostController::class, 'create'])->middleware('permission_through_role:control-guardposts')->name('guardposts.create');
  Route::get('/view-guardposts', [App\Http\Controllers\GuardPosts\GuardPostController::class, 'viewGuardPosts'])->middleware('permission_through_role:view-guardposts')->name('guardposts.view');

  //Группы проектов
  Route::get('/control-project-groups', [App\Http\Controllers\ProjectGroups\ProjectGroupController::class, 'controlProjectGroups'])->middleware('permission_through_role:control-project-groups')->name('projectGroups.control');
  Route::post('/project-group/update', [App\Http\Controllers\ProjectGroups\ProjectGroupController::class, 'update'])->middleware('permission_through_role:control-project-groups')->name('projectGroup.update');
  Route::post('/project-group/create', [App\Http\Controllers\ProjectGroups\ProjectGroupController::class, 'create'])->middleware('permission_through_role:control-project-groups')->name('projectGroup.create');

  Route::post('/profile/fast-create', [App\Http\Controllers\Profiles\ProfileController::class, 'fastCreate'])->name('profile.fastCreate');

  Route::get('/profile/create', [App\Http\Controllers\Profiles\ProfileController::class, 'showCreateForm'])->middleware('permission_through_role:create-profile')->name('profile.showCreateForm');
  Route::post('/profile/create', [App\Http\Controllers\Profiles\ProfileController::class, 'regProfile'])->middleware('permission_through_role:create-profile')->name('profile.create');

  Route::post('/profile/update-contacts', [App\Http\Controllers\Profiles\ProfileController::class, 'updateContacts'])->name('profile.updateContacts');
  Route::post('/profile/update-role', [App\Http\Controllers\Profiles\ProfileController::class, 'updateRole'])->name('profile.updateRole');
  Route::post('/profile/upload-avatar', [App\Http\Controllers\Profiles\ProfileController::class, 'uploadAvatar'])->name('profile.uploadAvatar');
  Route::post('/profile/delete-avatar', [App\Http\Controllers\Profiles\ProfileController::class, 'deleteAvatar'])->name('profile.deleteAvatar');
  Route::post('/profile/update', [App\Http\Controllers\Profiles\ProfileController::class, 'update'])->name('profile.update');

  Route::post('/document/upload-document', [App\Http\Controllers\Documents\DocumentController::class, 'uploadDocument'])->name('profile.uploadDocument');
  Route::post('/document/download-documents-to-zip', [App\Http\Controllers\Documents\DocumentController::class, 'downloadDocumentsToZip'])->name('downloadDocumentsToZip');
  Route::post('/document/delete-documents', [App\Http\Controllers\Documents\DocumentController::class, 'deleteDocuments'])->name('deleteDocuments');

  //ЛС
  Route::get('/personal-account/report', [App\Http\Controllers\PersonalAccounts\PersonalAccountController::class, 'showReport'])->name('account.Report');
  Route::post('/personal-account/refill', [App\Http\Controllers\PersonalAccounts\PersonalAccountController::class, 'refill'])->middleware('permission_through_role:refill-personal-account')->name('account.refill');
  Route::post('/personal-account/create', [App\Http\Controllers\PersonalAccounts\PersonalAccountController::class, 'create'])->middleware('permission_through_role:create-personal-account')->name('account.create');

  //Авто
  Route::get('/cars', [App\Http\Controllers\Cars\CarController::class, 'showControlPage'])->middleware('permission_through_role:control-cars')->name('cars.control');
  Route::post('/car/create', [App\Http\Controllers\Cars\CarController::class, 'create'])->name('car.create');
  Route::post('/car/update', [App\Http\Controllers\Cars\CarController::class, 'update'])->name('car.update');
  Route::post('/car/delete', [App\Http\Controllers\Cars\CarController::class, 'delete'])->name('car.delete');
  Route::post('/car/add-to-organization', [App\Http\Controllers\Cars\CarController::class, 'addCarToOrganization'])->name('car.addCarToOrganization');
  Route::post('/car/change-type', [App\Http\Controllers\Cars\CarController::class, 'changeCarType'])->name('car.changeCarType');

  Route::get('/mechanizms', [App\Http\Controllers\Mechanizms\MechanizmController::class, 'showControlPage'])->middleware('permission_through_role:control-mechanizms')->name('mechanizms.control');
  Route::post('/mechanizm/create', [App\Http\Controllers\Mechanizms\MechanizmController::class, 'create'])->name('mechanizm.create');
  Route::post('/mechanizm/update', [App\Http\Controllers\Mechanizms\MechanizmController::class, 'update'])->name('mechanizm.update');
  Route::post('/mechanizm/delete', [App\Http\Controllers\Mechanizms\MechanizmController::class, 'delete'])->name('mechanizm.delete');
  Route::post('/mechanizm/add-to-organization', [App\Http\Controllers\Mechanizms\MechanizmController::class, 'addMechanizmToOrganization'])->name('car.addMechanizmToOrganization');

  // Управление пропусками
  Route::group(['middleware' => 'permission_through_role:control-passes'], function() {
    Route::get('/passes', [App\Http\Controllers\Pass\PassController::class, 'showIndex'])->name('passes');
    Route::post('/pass/checkin', [App\Http\Controllers\Pass\PassController::class, 'checkIn'])->name('pass.checkin');
    Route::post('/pass/checkout', [App\Http\Controllers\Pass\PassController::class, 'checkOut'])->name('pass.checkout');
    Route::post('/pass/delete-from-list', [App\Http\Controllers\Pass\PassController::class, 'deleteFromList'])->name('pass.deletefromlist');
    Route::post('/pass/delete', [App\Http\Controllers\Pass\PassController::class, 'deletePass'])->name('pass.delete');
    Route::post('/pass/repeat-temporary', [App\Http\Controllers\Pass\PassController::class, 'repeatTemporary'])->name('pass.repeattemporary');
  });

  Route::group(['middleware' => 'permission_through_role:view-passes'], function() {
    Route::get('/pass-report', [App\Http\Controllers\Pass\PassController::class, 'showReport'])->name('passes.report');
  });

  Route::post('/pass/create-comment', [App\Http\Controllers\Pass\PassController::class, 'createComment'])->name('pass.createComment');
  Route::get('/testpasses', [App\Http\Controllers\Pass\PassController::class, 'showDraggableIndex'])->name('dragablepasses');



  // Компоненты для создания временного пропуска
  Route::group(['middleware' => 'permission_through_role:create-temporary-pass'], function() {
    Route::get('/create/temporary-pass', [App\Http\Controllers\Pass\PassController::class, 'showTemporaryCreateForm'])->name('create.temporarypass');
    Route::post('/create/pass/temporary', [App\Http\Controllers\Pass\PassController::class, 'Create']);
  });

  // Компоненты для создания Постоянного пропуска
  Route::group(['middleware' => 'permission_through_role:create-permanent-pass'], function() {
    Route::get('/create/permanent-pass', [App\Http\Controllers\Pass\PassController::class, 'showPermanentCreateForm'])->name('create.permanentpass');
    Route::post('/create/pass/permanent', [App\Http\Controllers\Pass\PassController::class, 'Create']);
  });

  //Контроль тарифов
  Route::group(['middleware' => 'permission_through_role:control-pass-rate-plan'], function() {
    Route::get('/control/pass-rate-plans', [App\Http\Controllers\PassRate\PassRateController::class, 'controlPassRatePlans'])->name('passRatePlans.control');
    Route::post('/create/pass-rate-plan', [App\Http\Controllers\PassRate\PassRateController::class, 'Create'])->name('passRatePlan.create');
    Route::post('/update/default-pass-rate-plan', [App\Http\Controllers\PassRate\PassRateController::class, 'UpdateDefault'])->name('passRatePlanDefault.update');
    Route::post('/delete/pass-rate-plan', [App\Http\Controllers\PassRate\PassRateController::class, 'DeletePlan'])->name('passRatePlan.delete');
    Route::post('/delete/pass-rate', [App\Http\Controllers\PassRate\PassRateController::class, 'DeleteRate'])->name('passRate.delete');
    Route::post('/update/pass-rate-plan', [App\Http\Controllers\PassRate\PassRateController::class, 'updatePlan'])->name('passRatePlan.update');
  });

  // Просмотр тарифных планов
  Route::group(['middleware' => 'permission_through_role:view-pass-rate-plan'], function() {
    Route::get('/show/pass-rate-plans', [App\Http\Controllers\PassRate\PassRateController::class, 'showPassRatePlans'])->name('passRatePlans.show');
  });

});

Route::post('/xvib2xnh3420go61rfb2489smefpdtgo7vzdx815lmu59iuiqw/webhook', [App\Http\Controllers\Botman\TelegramController::class, 'handle'])->name('telegram');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
