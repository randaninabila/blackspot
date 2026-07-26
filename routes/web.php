<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlankSpotController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GeospasialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\PublicApiController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\UsulanController;
use App\Http\Controllers\BackupController;

Route::get('/guest', function () {
    return view('guest');
});

// ============================================================
// ROOT
// ============================================================
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
});

// ============================================================
// AUTHENTICATION
// ============================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// FOTO SERVING ROUTE
// ============================================================
Route::get('/foto/{filename}', [BlankSpotController::class, 'servePhoto'])->name('foto.serve');

// ============================================================
// PUBLIC API ENDPOINTS (TANPA LOGIN)
// ============================================================
Route::prefix('api/public')->name('api.public.')->group(function () {
    Route::get('/stats', [PublicApiController::class, 'stats'])->name('stats');
    Route::get('/blank-spots', [PublicApiController::class, 'blankSpots'])->name('blank-spots');
    Route::get('/peta', [PublicApiController::class, 'peta'])->name('peta');
    Route::get('/dashboard', [PublicApiController::class, 'dashboard'])->name('dashboard');
});

// Usulan Bottom-Up Public
Route::post('/api/usulan', [UsulanController::class, 'store'])->name('usulan.store');

// ============================================================
// DEPENDENT DROPDOWN API (PUBLIC / ADMIN / USER)
// ============================================================
Route::get('/admin/api/kecamatan/{kabupaten_id}', [BlankSpotController::class, 'getKecamatan'])->name('admin.api.kecamatan');
Route::get('/admin/api/desa/{kecamatan_id}', [BlankSpotController::class, 'getDesa'])->name('admin.api.desa');

// ============================================================
// GEOSPASIAL PUBLIC
// ============================================================
Route::get('/geospasial', [GeospasialController::class, 'index'])->name('geospasial.index');
Route::get('/api/all-spots', [GeospasialController::class, 'getAllSpots'])->name('api.all.spots');
Route::get('/api/geospasial/kabupaten/{id}', [GeospasialController::class, 'getKabupatenData'])->name('api.geospasial.kabupaten');
Route::get('/map-v2', [MapController::class, 'index'])->name('map.index');

// ============================================================
// ADMIN ROUTES (ROLE: ADMIN)
// ============================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/add', [AdminController::class, 'addPage'])->name('add');
    Route::get('/detail/{kabupaten_id}', [AdminController::class, 'detailPage'])->name('detail');

    // Blank Spot CRUD Admin
    Route::get('/blank-spot', [BlankSpotController::class, 'adminIndex'])->name('blank-spot.index');
    Route::get('/blank-spot/create', [BlankSpotController::class, 'create'])->name('blank-spot.create');
    Route::post('/blank-spot', [BlankSpotController::class, 'store'])->name('blank-spot.store');
    Route::get('/blank-spot/{id}', [BlankSpotController::class, 'show'])->name('blank-spot.show');
    Route::get('/blank-spot/{id}/edit', [BlankSpotController::class, 'edit'])->name('blank-spot.edit');
    Route::put('/blank-spot/{id}', [BlankSpotController::class, 'update'])->name('blank-spot.update');
    Route::delete('/blank-spot/{id}', [BlankSpotController::class, 'destroy'])->name('blank-spot.destroy');

    // Workflow Validasi Admin
    Route::get('/validasi', [ValidationController::class, 'index'])->name('validasi.index');
    Route::get('/validasi/{id}', [ValidationController::class, 'show'])->name('validasi.show');
    Route::post('/validasi/{id}/setujui', [ValidationController::class, 'setujui'])->name('validasi.setujui');
    Route::post('/validasi/{id}/tolak', [ValidationController::class, 'tolak'])->name('validasi.tolak');
    Route::post('/validasi/{id}/revisi', [ValidationController::class, 'revisi'])->name('validasi.revisi');
    Route::get('/validasi/{id}/edit', [ValidationController::class, 'edit'])->name('validasi.edit');
    Route::put('/validasi/{id}', [ValidationController::class, 'update'])->name('validasi.update');
    Route::delete('/validasi/{id}', [ValidationController::class, 'destroy'])->name('validasi.destroy');

    Route::post('/validasi/massal/setujui', [ValidationController::class, 'massalSetujui'])->name('validasi.massal.setujui');
    Route::post('/validasi/massal/tolak', [ValidationController::class, 'massalTolak'])->name('validasi.massal.tolak');

    // Usulan Bottom-Up Management Admin
    Route::post('/usulan/{id}/approve', [UsulanController::class, 'approve'])->name('usulan.approve');
    Route::post('/usulan/{id}/reject', [UsulanController::class, 'reject'])->name('usulan.reject');

    // Backup & Restore System Admin
    Route::get('/backup/database', [BackupController::class, 'backupDb'])->name('backup.database');
    Route::get('/backup/photos', [BackupController::class, 'backupPhotos'])->name('backup.photos');

    // Geospasial Admin
    Route::get('/geospasial', [GeospasialController::class, 'index'])->name('geospasial.index');
    Route::get('/api/all-spots', [GeospasialController::class, 'getAllSpots'])->name('api.all.spots');

    // API Dropdown
    Route::get('/api/kecamatan/{kabupaten_id}', [BlankSpotController::class, 'getKecamatan'])->name('api.kecamatan');
    Route::get('/api/desa/{kecamatan_id}', [BlankSpotController::class, 'getDesa'])->name('api.desa');

    // Export Reports & Berita Acara
    Route::get('/export/pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/csv', [ExportController::class, 'exportCsv'])->name('export.csv');
    Route::get('/export/berita-acara/{id}', [ExportController::class, 'beritaAcaraPdf'])->name('export.berita-acara');
});

// ============================================================
// VERIFIKATOR KABUPATEN/KOTA ROUTES
// ============================================================
Route::middleware(['auth'])->prefix('verifikator')->name('verifikator.')->group(function () {
    Route::get('/validasi', [VerifikasiController::class, 'index'])->name('index');
    Route::post('/validasi/{id}/verify', [VerifikasiController::class, 'verify'])->name('verify');
});

// ============================================================
// USER / OPERATOR ROUTES (ROLE: OPERATOR KABUPATEN)
// ============================================================
Route::middleware(['auth', 'operator'])->prefix('user')->name('user.')->group(function () {

    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/add', [UserController::class, 'addPage'])->name('add');
    Route::get('/detail/{kabupaten_id}', [UserController::class, 'detailPage'])->name('detail');

    // Blank Spot CRUD Operator
    Route::get('/blank-spot', [BlankSpotController::class, 'userIndex'])->name('blank-spot.index');
    Route::get('/blank-spot/create', [BlankSpotController::class, 'userCreate'])->name('blank-spot.create');
    Route::post('/blank-spot', [BlankSpotController::class, 'userStore'])->name('blank-spot.store');
    Route::get('/blank-spot/{id}', [BlankSpotController::class, 'userShow'])->name('blank-spot.show');
    Route::get('/blank-spot/{id}/edit', [BlankSpotController::class, 'userEdit'])->name('blank-spot.edit');
    Route::put('/blank-spot/{id}', [BlankSpotController::class, 'userUpdate'])->name('blank-spot.update');
    Route::delete('/blank-spot/{id}', [BlankSpotController::class, 'userDestroy'])->name('blank-spot.destroy');

    // API Dropdown
    Route::get('/api/kecamatan/{kabupaten_id}', [BlankSpotController::class, 'getKecamatan'])->name('api.kecamatan');
    Route::get('/api/desa/{kecamatan_id}', [BlankSpotController::class, 'getDesa'])->name('api.desa');

    // Export Reports Operator
    Route::get('/export/pdf', [ExportController::class, 'exportPdfUser'])->name('export.pdf');
    Route::get('/export/excel', [ExportController::class, 'exportExcelUser'])->name('export.excel');
    Route::get('/export/csv', [ExportController::class, 'exportCsvUser'])->name('export.csv');
    Route::get('/export/berita-acara/{id}', [ExportController::class, 'beritaAcaraPdf'])->name('export.berita-acara');

    // API Filter Geospasial User
    Route::get('/api/filter-geospasial', [UserController::class, 'filterGeospasial'])->name('api.filter.geospasial');
});

// ============================================================
// WILAYAH SLUG ROUTE
// ============================================================
Route::get('/wilayah/{slug}', [AdminController::class, 'detailWilayah'])->name('wilayah.detail');