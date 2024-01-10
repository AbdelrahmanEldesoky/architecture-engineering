<?php

use App\Http\Controllers\api\Employee\GeneralRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['middleware' => [ 'json.response','from.header']], function () {

    Route::post('check', [\App\Http\Controllers\Api\Client\AuthController::class, 'check']);
    Route::post('confirm', [\App\Http\Controllers\Api\Client\AuthController::class, 'confirm']);
    Route::post('register', [\App\Http\Controllers\Api\Client\AuthController::class, 'register']);
    Route::post('branches', [\App\Http\Controllers\Api\Client\AuthController::class, 'branches']);

});

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//
//    return $request->user();
//});
$client_url = 'client';

Route::group([
    'prefix' => $client_url,
    'as' => 'client.',
    'middleware' => ['json.response','from.header']], function () {

    Route::post('/setting', [\App\Http\Controllers\Api\Client\SettingController::class, 'setting'])->name('setting');
    Route::post('/setting/get', [\App\Http\Controllers\Api\Client\SettingController::class, 'getSetting'])->name('getSetting');
    Route::post('/main-page', [\App\Http\Controllers\Api\Client\SettingController::class, 'mainPage'])->name('main-page');
    Route::post('/about-page', [\App\Http\Controllers\Api\Client\SettingController::class, 'aboutPage'])->name('about-page');

    Route::post('/subscribe', [\App\Http\Controllers\Api\Client\SettingController::class, 'subscribe'])->name('subscribe');
    Route::post('/contact', [\App\Http\Controllers\Api\Client\SettingController::class, 'contact'])->name('contact');
    Route::post('/service-request', [\App\Http\Controllers\Api\Client\SettingController::class, 'requestService'])->name('services.requests');

    Route::post('/services/list', [\App\Http\Controllers\Api\Client\ServiceController::class, 'list'])->name('services.list');
    Route::apiResource('services', \App\Http\Controllers\Api\Client\ServiceController::class);

    Route::get('/service-page', [\App\Http\Controllers\Api\Client\ServiceController::class , 'servicePage'] );

    Route::post('/company-projects/list', [\App\Http\Controllers\Api\Client\CompanyProjectController::class, 'list'])->name('services.list');
    Route::apiResource('company-projects', \App\Http\Controllers\Api\Client\CompanyProjectController::class);

    Route::post('/banners/list', [\App\Http\Controllers\Api\Client\BannerController::class, 'list'])->name('banners.list');
    Route::apiResource('banners', \App\Http\Controllers\Api\Client\BannerController::class);

    Route::post('/news/list', [\App\Http\Controllers\Api\Client\NewsController::class, 'list'])->name('news.list');
    Route::apiResource('news', \App\Http\Controllers\Api\Client\NewsController::class);
    Route::post('/icons/list', [\App\Http\Controllers\Api\Client\IconController::class, 'list'])->name('icons.list');
    Route::apiResource('icons', \App\Http\Controllers\Api\Client\IconController::class);
    Route::post('/members/list', [\App\Http\Controllers\Api\Client\MemberController::class, 'list'])->name('members.list');
    Route::apiResource('members', \App\Http\Controllers\Api\Client\MemberController::class);



    Route::group([
        'middleware' => ['auth:api-client','scopes:client']], function () {

        Route::post('/logout', [\App\Http\Controllers\Api\Client\AuthController::class, 'logout']);
        Route::get('/contract',[\App\Http\Controllers\Api\Client\ContractController::class,'index']);
        Route::post('/delete-account', [\App\Http\Controllers\Api\Client\DashboardController::class, 'deleteAccount']);

        Route::post('/', [\App\Http\Controllers\Api\Client\DashboardController::class, 'index'])->name('index');
        Route::post('/user', [\App\Http\Controllers\Api\Client\DashboardController::class, 'user'])->name('user');

        Route::post('/profile', [\App\Http\Controllers\Api\Client\DashboardController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [\App\Http\Controllers\Api\Client\DashboardController::class, 'updateProfile'])->name('profile.update');

        Route::get('/agreements', [\App\Http\Controllers\Api\GADA\AgreementsController::class, 'index'])->name('agreements');


    });
});

$employee_url = 'employee';

Route::group([
    'prefix' => $employee_url,
    'as' => 'employee.',
    'middleware' => ['json.response']], function () {

    Route::post('login', [\App\Http\Controllers\Api\Employee\AuthController::class, 'login']);
    Route::post('/support', [\App\Http\Controllers\Api\Employee\AuthController::class, 'support'])->name('support');


    Route::group([
        'middleware' => ['auth:api', 'scopes:employee','last_seen']], function () {
        Route::post('/logout', [\App\Http\Controllers\Api\Employee\AuthController::class, 'logout']);
        Route::post('/delete-account', [\App\Http\Controllers\Api\Employee\DashboardController::class, 'deleteAccount']);
        Route::post('/', [\App\Http\Controllers\Api\Employee\DashboardController::class, 'index'])->name('index');
        Route::post('/user', [\App\Http\Controllers\Api\Employee\DashboardController::class, 'user'])->name('user');
        Route::post('/requests/{employeeRequest}/update', [\App\Http\Controllers\Api\Employee\EmployeeRequestsController::class, 'editTimeEnterAndOut'])->name('requests-update ');

        Route::post('/requests', [\App\Http\Controllers\Api\Employee\EmployeeRequestsController::class, 'requests'])->name('requests');
        Route::get('/vacations', [\App\Http\Controllers\Api\Employee\EmployeeRelation::class, 'getMyVacation'])->name('employee.vacations');
        Route::post('/requests/create', [\App\Http\Controllers\Api\Employee\EmployeeRequestsController::class, 'createRequest'])->name('requests-create ');
        Route::post('/track', [\App\Http\Controllers\Api\Employee\EmployeeRequestsController::class, 'track'])->name('employee.track');
        Route::post('/employees', [\App\Http\Controllers\Api\Employee\EmployeeRequestsController::class, 'employees'])->name('employees');
        Route::get('/car-brand', [\App\Http\Controllers\Api\CarBrandController::class, 'index'])->name('employees');



        Route::get('/all-departments', [\App\Http\Controllers\Api\WorkAtController::class, 'getAllDepartments'])->name('departments');
        Route::get('/all-branches', [\App\Http\Controllers\Api\WorkAtController::class, 'getAllBranches'])->name('branches');
        Route::get('/all-managements', [\App\Http\Controllers\Api\WorkAtController::class, 'getAllManagements'])->name('managements');

//        Route::post('/profile', [\App\Http\Controllers\Api\Employee\DashboardController::class, 'profile'])->name('profile');
//        Route::post('/profile/update', [\App\Http\Controllers\Api\Employee\DashboardController::class, 'updateProfile'])->name('profile.update');

        Route::get('getDepartmentWithEmployee',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'getDepartment']);
        // Attendance
        Route::get('/get-attendance-info',[\App\Http\Controllers\Api\Employee\AttendanceController::class, 'getAttendanceInfo'])->name('attendance-info');
        Route::post('/attend', [\App\Http\Controllers\Api\Employee\AttendanceController::class,'storeAttendance'])->name('attend');


        Route::post('/attend/reports',[\App\Http\Controllers\Api\Employee\AttendanceController::class,'getAttendReport'])->name('attend-report');

        Route::get('/status',[\App\Http\Controllers\Api\Employee\AttendanceController::class,'getEmpStatus'])->name('emp-status');


        Route::get('/internal-news',[\App\Http\Controllers\Api\Employee\InternalNewsController::class, 'index'])->name('internal-news');
        Route::get('/notifications',[\App\Http\Controllers\Api\Employee\DashboardController::class, 'notifications'])->name('notifications');

        Route::post('/offline',[\App\Http\Controllers\Api\Employee\AttendanceController::class,'offline'])->name('offline');
        Route::get('/online',[\App\Http\Controllers\Api\Employee\AttendanceController::class,'online'])->name('online');
        Route::group(['prefix'=>'general-requests'],function(){
            Route::get('/requests',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'getAllRequestsWithDetails']);
            Route::get('/my-requests',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'getMyRequests']);
            Route::get('/last-advance',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'last_vance']);
            Route::get('/last-custody',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'last_Custody']);
            Route::get('/requests/{generalRequest}',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'getDetailsForRequest']);
            Route::post('/car/create',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'createCarMaintenance'])->name('general-request.car');
            Route::post('/work-need/create',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'createWorksNeeds'])->name('general-request.work-need');
            Route::post('/advance/create',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'createAdvance'])->name('general-request.advance');
            Route::post('/custody/create',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'createCustody'])->name('general-request.custody');
            Route::post('/vacation/create',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'createVacation'])->name('general-request.vacation');
            Route::post('/steps/create',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'createSteps'])->name('general-request.create-steps');
            Route::post('/steps',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'getSteps'])->name('general-request.steps');
            Route::post('/approve_steps/{generalRequest}',[\App\Http\Controllers\Api\Employee\GeneralRequestController::class,'approve_steps'])->name('general-request.approve');
        });
        ######################### Start Api Dashboard ################################################
        Route::group([
            'prefix' => 'client',
        ], function () {
            Route::get('/', [\App\Http\Controllers\Admin\Api\ClientController::class, 'index']);
            Route::post('/store', [\App\Http\Controllers\Admin\Api\ClientController::class, 'store']);
            Route::get('/edit', [\App\Http\Controllers\Admin\Api\ClientController::class, 'edit']);
            Route::get('/use', [\App\Http\Controllers\Admin\Api\ClientController::class, 'using']);
            Route::post('/update/{id}', [\App\Http\Controllers\Admin\Api\ClientController::class, 'update']);
            Route::post('/delete', [\App\Http\Controllers\Admin\Api\ClientController::class, 'destroy']);

            Route::group([
                'prefix' => 'order',
            ], function () {
                Route::get('/', [\App\Http\Controllers\Admin\Api\OrderClientController::class, 'index']);
                Route::post('/store', [\App\Http\Controllers\Admin\Api\OrderClientController::class, 'store']);
                Route::get('/use', [\App\Http\Controllers\Admin\Api\OrderClientController::class, 'using']);
                Route::post('/update/{id}', [\App\Http\Controllers\Admin\Api\OrderClientController::class, 'update']);
                Route::post('/delete', [\App\Http\Controllers\Admin\Api\OrderClientController::class, 'destroy']);
                Route::post('/addStep', [\App\Http\Controllers\Admin\Api\OrderClientController::class, 'addStep']);
                Route::get('/statusOrder', [\App\Http\Controllers\Admin\Api\OrderClientController::class, 'statusOrder']);
                Route::get('/showOrder', [\App\Http\Controllers\Admin\Api\OrderClientController::class, 'showOrder']);

                Route::group([
                    'prefix' => 'steps',
                ], function () {
                    Route::get('/', [\App\Http\Controllers\Admin\Api\OrderClientStepsController::class, 'index']);
                    Route::get('/use', [\App\Http\Controllers\Admin\Api\OrderClientStepsController::class, 'using']);
                    Route::post('/store', [\App\Http\Controllers\Admin\Api\OrderClientStepsController::class, 'store']);
                    Route::post('/update/{id}', [\App\Http\Controllers\Admin\Api\OrderClientStepsController::class, 'update']);
                    Route::post('/delete/{id}/{branch_id}', [\App\Http\Controllers\Admin\Api\OrderClientStepsController::class, 'destroy']);

                    Route::group([
                        'prefix' => 'forms',
                    ], function () {
                        Route::get('/', [\App\Http\Controllers\Admin\Api\OrderClientStepFormController::class, 'index']);
                        Route::get('/use', [\App\Http\Controllers\Admin\Api\OrderClientStepFormController::class, 'using']);
                        Route::post('/store', [\App\Http\Controllers\Admin\Api\OrderClientStepFormController::class, 'store']);
                        Route::post('/update/{id}', [\App\Http\Controllers\Admin\Api\OrderClientStepFormController::class, 'update']);
                        Route::post('/delete', [\App\Http\Controllers\Admin\Api\OrderClientStepFormController::class, 'destroy']);
                    });
                });
            });
        });

        Route::group([
            'prefix' => 'contract',
        ], function () {
            Route::get('use', [\App\Http\Controllers\Admin\Api\ContractController::class, 'using']);
            Route::get('/', [\App\Http\Controllers\Admin\Api\ContractController::class, 'index']);
            Route::get('/{id}', [\App\Http\Controllers\Admin\Api\ContractController::class, 'show']);
            Route::post('/store', [\App\Http\Controllers\Admin\Api\ContractController::class, 'store']);
            Route::post('/update/{id}', [\App\Http\Controllers\Admin\Api\ContractController::class, 'update']);
            Route::post('/delete', [\App\Http\Controllers\Admin\Api\ContractController::class, 'destroy']);

            Route::group([
                'prefix' => 'task',
            ], function () {
                Route::get('/use', [\App\Http\Controllers\Admin\Api\ContractTaskController::class, 'using']);

                Route::get('/{contractId}', [\App\Http\Controllers\Admin\Api\ContractTaskController::class, 'index']);
                Route::post('/store', [\App\Http\Controllers\Admin\Api\ContractTaskController::class, 'store']);
                Route::patch('/{id}', [\App\Http\Controllers\Admin\Api\ContractTaskController::class, 'update']);
                Route::delete('/{id}', [\App\Http\Controllers\Admin\Api\ContractTaskController::class, 'destroy']);

            });

            Route::group([
                'prefix' => 'payment',
            ], function () {
                Route::get('/use', [\App\Http\Controllers\Admin\Api\ContractPaymentController::class, 'using']);
                Route::get('/{contractId}', [\App\Http\Controllers\Admin\Api\ContractPaymentController::class, 'index']);
                Route::post('/store', [\App\Http\Controllers\Admin\Api\ContractPaymentController::class, 'store']);
                Route::patch('/{id}', [\App\Http\Controllers\Admin\Api\ContractPaymentController::class, 'update']);
                Route::delete('/{id}', [\App\Http\Controllers\Admin\Api\ContractPaymentController::class, 'destroy']);
            });

            Route::group([
                'prefix' => 'lever',
            ], function () {
                Route::get('/use', [\App\Http\Controllers\Admin\Api\ContractLeverController::class, 'using']);
                Route::get('/{contractId}', [\App\Http\Controllers\Admin\Api\ContractLeverController::class, 'index']);
                Route::post('/store', [\App\Http\Controllers\Admin\Api\ContractLeverController::class, 'store']);
                Route::patch('/{id}', [\App\Http\Controllers\Admin\Api\ContractLeverController::class, 'update']);
                Route::delete('/{id}', [\App\Http\Controllers\Admin\Api\ContractLeverController::class, 'destroy']);
            });
        });



        ######################### End Api Dashboard   ################################################

    });
});
