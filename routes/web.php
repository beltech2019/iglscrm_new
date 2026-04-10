<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\SocialTicketController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SocialUserController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserAssignRuleController;
use App\Http\Controllers\PostAssignRuleController;
use App\Http\Controllers\UIComponentController;
use App\Http\Controllers\CsvImportController;
use App\Http\Controllers\WhatsAppBotController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\TicketResponseController;
use App\Http\Controllers\PreDefinedReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::get('/change-password', function () {
    return view('auth.change-password');
})->middleware('auth')->name('password.change');

// Route::get('/socialticket', function () {
//     return view('post.socialticket');
// });

// Route::get('/socialpost_inner', function () {
//     return view('post.socialpost_inner');
// });

Route::get('/change_log', function () {
    return view('post.change_log');
});

Route::get('/reports_home', function () {
    return view('post.reports_home');
});

Route::get('/leads_home', function () {
    return view('post.leads_home');
});

Route::get('/upload_import_file', function () {
    return view('post.upload_import_file');
});

Route::get('/report_ticket_status', function () {
    return view('post.report_ticket_status');
});

Route::get('/socialpost_innerpage', function () {
    return view('post.socialpost_innerpage');
});

Route::get('/busines_info', function () {
    return view('post.busines_info');
});

Route::get('/reportById', function () {
    return view('reports.reportById');
});

Route::controller(DashboardController::class)->group(function() {
    Route::get('/countDashboard', 'countDashboard')->name('countDashboard');
	  Route::get('/globalSearch', 'globalSearch')->name('globalSearch');
});

Route::controller(UIComponentController::class)->group(function() {
    Route::get('/accessRole', 'accessRole')->name('accessRole');
    Route::post('/createUpdateRoleAccess', 'createUpdateRoleAccess')->name('createUpdateRoleAccess');
	  Route::get('/getRoleWiseComponents/{id}', 'getRoleWiseComponents')->name('getRoleWiseComponents');
});

Route::controller(SocialUserController::class)->group(function() {
    Route::get('/userProfile/{id}', 'userProfile')->name('userProfile');
    Route::get('/getFavourite', 'getFavourite')->name('getFavourite');
    Route::post('/addFavourite', 'addFavourite')->name('addFavourite');
});

Route::controller(ReportsController::class)->group(function() {
    Route::get('/deleteReport/{id}', 'deleteReport')->name('deleteReport');
    Route::get('/getReportList', 'getReportList')->name('getReportList');
    Route::post('/createUpdateReport', 'createUpdateReport')->name('createUpdateReport');
    Route::post('/createUpdateReport/{id}', 'createUpdateReport')->name('createUpdateReport');
    Route::get('/createReport', 'createReport')->name('createReport');
	Route::get('/editreport/{id}', 'createReport')->name('updateReport');
	Route::get('/showReport/{id}', 'showReport')->name('showReport');
});

Route::controller(PostAssignRuleController::class)->group(function() {
    Route::get('/post_rule', 'getPostAssignRule')->name('getPostAssignRule');
    Route::get('/post_rule/{id}', 'getPostAssignRule')->name('getPostAssignRule');
    Route::post('/addPostAssignRule', 'addPostAssignRule')->name('addPostAssignRule');
    Route::post('/addPostAssignRule/{id}', 'addPostAssignRule')->name('addPostAssignRule');
    Route::get('/postAssignRuleList', 'postAssignRuleList')->name('postAssignRuleList');
    Route::get('/postRulePriority', 'postRulePriority')->name('postRulePriority');
    Route::post('/addPostAssignRulePriority', 'addPostAssignRulePriority')->name('addPostAssignRulePriority');
    Route::get('/deletePostRule/{id}', 'deletePostRule')->name('deletePostRule');
});

Route::controller(UserAssignRuleController::class)->group(function() {
    Route::get('/busines_rule', 'getUserAssignRule')->name('getUserAssignRule');
    Route::get('/busines_rule/{id}', 'getUserAssignRule')->name('getUserAssignRule');
    Route::post('/addUserAssignRule', 'addUserAssignRule')->name('addUserAssignRule');
    Route::post('/addUserAssignRule/{id}', 'addUserAssignRule')->name('addUserAssignRule');
    Route::get('/userAssignRuleList', 'userAssignRuleList')->name('userAssignRuleList');
    Route::get('/deleteRule/{id}', 'deleteRule')->name('deleteRule');
});

Route::controller(LeadController::class)->group(function() {
	 Route::get('/getLeads', 'getLeads')->name('getLeads');
     Route::get('/getRecentLeads', 'getRecentLeads')->name('getRecentLeads');
     Route::get('/generateLead/{id}', 'generateLead')->name('generateLead');
     Route::get('/leadReply/{id}', 'leadReply')->name('leadReply');
     Route::get('/getLeadById/{id}', 'getLeadById')->name('getLeadById');
	 Route::get('/getLeads/{id}', 'getLeads')->name('getLeadss');
	 Route::get('/createLead', 'createLead')->name('createLead');
	 Route::get('/createLead/{id}', 'createLead')->name('createLead');
	 Route::post('/deleteAllLead', 'deleteAllLead')->name('deleteAllLead');
	 Route::get('/deleteLead/{id}', 'deleteLead')->name('deleteLead');
     Route::post('/createUpdateLead', 'createUpdateLead')->name('createUpdateLead');
	 Route::post('/createUpdateLead/{id}', 'createUpdateLead')->name('createUpdateLead');
     Route::get('/createUpdateLead/{id}', 'createUpdateLead')->name('createUpdateLead');
     Route::get('/generateTicketFromLead/{id}', 'generateTicketFromLead')->name('generateTicketFromLead');
     Route::get('/postResponse', 'postResponse')->name('postResponse');
     Route::get('/deleteattachmentfromLead/{id}', 'deleteattachmentfromLead')->name('deleteattachmentfromLead');
});

Route::controller(SocialTicketController::class)->group(function() {
    Route::get('/deleteTicket/{id}', 'deleteTicket')->name('deleteTicket');
    Route::post('/updateTicket/{id}', 'updateTicket')->name('updateTicket');
    Route::get('/updateTicket/{id}', 'updateTicket')->name('updateTicket');
    Route::get('/generateTicket/{id}', 'generateTicket')->name('generateTicket');
    Route::get('/getSocialTicket', 'getSocialTicket')->name('getSocialTicket');
    Route::get('/getSocialTicket/{id}', 'getSocialTicket')->name('getSocialTicketId');
    Route::get('/getRecentSocialTicket', 'getRecentSocialTicket')->name('getRecentSocialTicket');
    Route::get('/getSocialTicketById/{id}', 'getSocialTicketById')->name('getSocialTicketById');
    Route::get('/editTicket/{id}', 'editTicket')->name('editTicket');
	Route::post('/createUpdateActivity', 'createUpdateActivity')->name('createUpdateActivity');
    Route::post('/createUpdateActivity/{id}', 'createUpdateActivity')->name('createUpdateActivity');
    Route::get('/ticketReply/{id}', 'ticketReply')->name('ticketReply');
    Route::get('/markDuplicate/{id}', 'markDuplicate')->name('markDuplicate');
    Route::post('/deleteAllTicket', 'deleteAllTicket')->name('deleteAllTicket');
    Route::post('/updateTicketBtText/{id}', 'updateTicketBtText')->name('updateTicketBtText');
    Route::get('/get-suboptions/{option}', 'getSubOptions')->name('getSubOptions');
    Route::get('/deleteattachmentfromticket/{id}', 'deleteattachmentfromticket')->name('deleteattachmentfromticket');
});

Route::controller(TweetController::class)->group(function() {
    Route::get('/deletePost/{id}', 'deletePost')->name('deletePost');
	Route::get('/getSocialPostById/{id}', 'getSocialPostById')->name('getSocialPostById');
	Route::get('/dashboard', 'dashboard')->name('dashboard');
	Route::get('/', 'dashboard')->name('home');
    Route::get('/getUserbyusername', 'getUserbyusername')->name('getUserbyusername');
    Route::get('/recentdashboard', 'recentdashboard')->name('recentdashboard');
    Route::post('/manualAddSocailPost', 'manualAddSocailPost')->name('manualAddSocailPost');
    Route::post('/manualAddSocailPost/{id}', 'manualAddSocailPost')->name('manualAddSocailPost');
    Route::get('/manualAddSocailPost/{id}', 'manualAddSocailPost')->name('manualAddSocailPost');
    Route::post('/deleteAll', 'deleteAll')->name('deleteAll');
	Route::get('/getTweetIds', 'getTweetIds')->name('getTweetIds');
	Route::get('/tweetLogList/{id}', 'tweetLogList')->name('tweetLogList');
	Route::get('/tweetLogList/{id}/{type}', 'tweetLogList')->name('tweetLogList');
    Route::get('/createpost', 'createpost')->name('editSocialPost');
    Route::get('/postreply/{id}', 'postreply')->name('postreply');
    Route::get('/createpost/{id}', 'createpost')->name('editSocialPost');
    Route::post('/postTweet', 'postTweet')->name('postTweet');
    Route::get('/uploadDoc', 'uploadDoc')->name('uploadDoc');
    Route::get('/getColumnInformation', 'getColumnInformation')->name('getColumnInformation');
    Route::post('/replyTweetId', 'replyTweetId')->name('replyTweetId');
    Route::get('/replyTweetId', 'replyTweetId')->name('replyTweetId');
    Route::post('/replyTweetId/{id}', 'replyTweetId')->name('replyTweetId');
    Route::get('/getDMByTwitter', 'getDMByTwitter')->name('getDMByTwitter');
	Route::get('/getDMByfacebook', 'getDMByfacebook')->name('getDMByfacebook');
	Route::post('/showHideColumn', 'showHideColumn')->name('showHideColumn');
    Route::get('/deleteattachment/{id}', 'deleteattachment')->name('deleteattachment');
});

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::get('/register/{id}', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::post('/store/{id}', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::get('/admin-management', 'adminManagement')->name('adminManagement');
    Route::get('/user-list', 'userList')->name('userList');
    Route::get('/update-config', 'updateConfig')->name('updateConfig');
    Route::post('/update-config', 'updateConfig')->name('updateConfig');
    Route::get('/socialPlatform', 'socialPlatform')->name('socialPlatform');
    Route::post('/socialPlatform', 'socialPlatform')->name('socialPlatform');
    Route::post('/authenticate', 'authenticate')->name('authenticate');

    Route::post('/logout', 'logout')->name('logout');
    Route::post('/password', 'password')->name('password');
    Route::post('/changepassword', 'changepassword')->name('changepassword');
    Route::post('/update-password', 'updatePassword')->name('update-password');
});

Route::controller(CsvImportController::class)->group(function() {

    Route::get('/import', 'showImportForm')->name('import');
    Route::post('/import', 'import')->name('import');
});

Route::controller(TemplateController::class)->group(function() {

    Route::get('/createUpdateTemplate', 'createUpdateTemplate')->name('createUpdateTemplate');
    Route::get('/createUpdateTemplate/{id}', 'createUpdateTemplate')->name('createUpdateTemplate');
    Route::post('/storeTemplate', 'storeTemplate')->name('storeTemplate');
    Route::post('/storeTemplate/{id}', 'storeTemplate')->name('storeTemplate');
    Route::get('/getTemplateList', 'getTemplateList')->name('getTemplateList');
    Route::get('/deleteTemplate/{id}', 'deleteTemplate')->name('deleteTemplate');

    
});
Route::controller(PreDefinedReportController::class)->group(function() {
    // Route::post('/getSocialPostReport', 'getSocialPostReport')->name('getSocialPostReport');
    Route::get('/getSocialPostReport', 'getSocialPostReport')->name('getSocialPostReport');
    // Route::post('/getSocialTicketReport', 'getSocialTicketReport')->name('getSocialTicketReport');
    Route::get('/getSocialTicketReport', 'getSocialTicketReport')->name('getSocialTicketReport');
    Route::get('/getLeadsReport', 'getLeadsReport')->name('getLeadsReport');
    Route::get('/getadminupdatebyReport', 'getadminupdatebyReport')->name('getadminupdatebyReport');
    Route::get('/getadmindeletebyReport', 'getadmindeletebyReport')->name('getadmindeletebyReport');
    Route::get('/getViewLogReport', 'getViewLogReport')->name('getViewLogReport');
    Route::get('/getadmincreatebyReport', 'getadmincreatebyReport')->name('getadmincreatebyReport');
});

Route::controller(TicketResponseController::class)->group(function() {

    Route::post('/createSapTicket/{id}', 'createSapTicket')->name('createSapTicket');
    Route::get('/{id}/getSapTicketStatus', 'getSapTicketStatus')->name('getSapTicketStatus');
});