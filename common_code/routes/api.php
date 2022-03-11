<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderResourceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SiteScoreAhrefsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\CareerFairController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobSeekerQualificationController;
use App\Http\Controllers\JobSeekerExperienceController;
use App\Http\Controllers\JobSeekerQualificationMediaController;
use App\Http\Controllers\JobSeekerExperienceMediaController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AppVersionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['throttle:120,1', 'userAction']], function () {
    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/hi', [WishlistController::class, "hi"]);

    Route::post('/wishlists', [WishlistController::class, "store"]);
    Route::post('/wishlists/remove', [WishlistController::class, "destroy"]);


    Route::post('/carts', [CartController::class, "store"]);
    Route::post('/carts/remove', [CartController::class, "destroy"]);

    Route::resource('/order-resource', OrderResourceController::class);
    // Route::post('/register', [CustomerController::class, 'register'])->name('register');

    Route::post('/add-site-search', [DashboardController::class, "addCustomerSiteSearch"])->name('addCustomerSiteSearch');

    Route::post('/login', [AuthUserController::class, 'login'])->name('login.api');
    Route::post('/job-seeker/register', [AuthUserController::class, 'jobseekerRegister'])->name('register.seeker.api');
    Route::post('/forgot-password', [AuthUserController::class, 'forgotPasswordMobile'])->name('forgot-password.api');
    // Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');
    

    Route::get('/', [HomeController::class, "getHome"]);

    Route::get('/vacancies', [VacancyController::class, "getAllVacanciesByJobseeker"]);
    Route::get('/vacancies/{id}', [VacancyController::class, "show"]);
    Route::get('/companies', [CompanyController::class, "getAllCompaniesByJobseeker"]);
    Route::get('/companies/{id}', [CompanyController::class, "show"]);
    Route::get('/career-fairs', [CareerFairController::class, "getAllCareerFairs"])->name("all-career-fairs.api");
    Route::get('/career-fairs/{id}', [CareerFairController::class, "showPublic"])->name("view-career-fairs.api");
    Route::get('/career-fairs/{id}/companies', [CareerFairController::class, "viewCareerfairCompanies"])->name("view-career-fairs-companies.api");
    Route::get('/career-fairs/{id}/vacancies', [CareerFairController::class, "viewCareerfairVacancies"])->name("view-career-fairs-vacancies.api");
    Route::get('/country', [CountryController::class, "getAll"]);
    Route::get('/search', [VacancyController::class, "search"]);
    Route::get('/latest-version', [AppVersionController::class, "index"]);


    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.api');

        //======================================================================================
            //Jobseeker Routes
            //======================================================================================
            Route::post('/apply', [VacancyController::class, "applyVacancy"])->name("apply-vacancy.api");
            Route::get('/wishlist', [WishlistController::class, "myWishList"])->name("wishlist.api");
            Route::post('/wishlist', [WishlistController::class, "addToWishList"]); //Ajax
            Route::post('/wishlist/remove', [WishlistController::class, "removeFromWishList"]); //Ajax
            Route::post('/follow', [CompanyController::class, "addToFollowList"]); //Ajax
            Route::post('/unfollow', [CompanyController::class, "removeFromFollowList"]); //Ajax

            Route::get('/following-companies', [CompanyController::class, "myCompanyFollows"])->name("my-company-follows.api");
            Route::post('/introduce', [CompanyController::class, "introduceJobseeker"])->name("introduce-to-company.api");

            // api -------------------------
            Route::post('/add-education', [JobSeekerQualificationController::class, "addEducation"])->name('add-education.api'); //Ajax
            Route::post('/add-experience', [JobSeekerExperienceController::class, "addExperience"])->name('add-experience.api'); //Ajax
           
            // Route::get('/education/{id}/edit', [JobSeekerQualificationController::class, "editViewEducation"])->name('education-edit.api');
            Route::put('/education/{id}/edit', [JobSeekerQualificationController::class, "updateEducation"])->name('education-update.api');
            // Route::get('/experience/{id}/edit', [JobSeekerExperienceController::class, "editViewExperience"])->name('experience-edit.api');
            Route::put('/experience/{id}/edit', [JobSeekerExperienceController::class, "updateExperience"])->name('experience-update.api');
            // Route::get('/education-media/{id}', [JobSeekerQualificationMediaController::class, "educationMediaView"])->name('education-media-view.api');
            Route::post('/education-media', [JobSeekerQualificationMediaController::class, "uploadEducationMedias"])->name('upload-education-media.api');
            // Route::get('/experience-media/{id}', [JobSeekerExperienceMediaController::class, "experienceMediaView"])->name('experience-media-view.api');
            Route::post('/experience-media', [JobSeekerExperienceMediaController::class, "uploadExperienceMedias"])->name('upload-experience-media.api');
            Route::delete('/delete-education-media', [JobSeekerQualificationMediaController::class, "deleteEducationMedia"])->name('delete-education-media.api');
            Route::delete('/delete-experience-media', [JobSeekerExperienceMediaController::class, "deleteExperienceMedia"])->name('delete-experience-media.api');
            Route::delete('/delete-education', [JobSeekerQualificationController::class, "deleteEducation"])->name('delete-education.api');
            Route::delete('/delete-experience', [JobSeekerExperienceController::class, "deleteExperience"])->name('delete-experience.api');

            Route::get('/my-applications/{application}/status-history', [ApplicationController::class, "getJobSeekerApplicationStatusHistory"])->name("jobseeker-application-status-history.api");
            Route::get('/change-primary-education', [JobSeekerQualificationController::class, "changePrimaryEducationView"])->name("change-primary-education-view.api");
            Route::post('/store-primary-education', [JobSeekerQualificationController::class, "storePrimaryEducation"])->name("store-primary-education.api");
            // api -------------------------

            //======================================================================================
            //Jobseeker and company Routes
            //======================================================================================
        
            Route::get('/edit-profile', [UserController::class, "editProfile"])->name("edit-profile.api");
            Route::post('/update-profile', [UserController::class, "updateProfile"])->name("update-profile.api");
            Route::get('/my-applications', [ApplicationController::class, "getJobSeekerApplications"])->name("my-applications.api");
            Route::post('/apply-careerfiar', [CareerFairController::class, "applyCareerfiar"])->name("apply-careerfiar.api");
            Route::post('/update-profile/basic', [UserController::class, "updateProfileBasic"]);
            Route::post('/update-profile/password', [UserController::class, "updateProfilePassword"]);
            Route::post('/update-profile/primary-education', [UserController::class, "updateProfilePrimaryEducation"]);
            Route::post('/update-profile/cv', [UserController::class, "updateCV"]);
            Route::get('/download/cv', [UserController::class, "downloadCv"]);
            Route::post('/update-profile/field', [UserController::class, "updateFields"]);
            Route::get('/profile/terminate', [UserController::class, "terminateMyAccount"]);

            Route::get('/notification/get', [NotificationController::class, "index"]);

    });
});