<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Models\Document;

// COMPONENTES
use App\Livewire\IpercForm;
use App\Livewire\AtsForm;
use App\Livewire\ChecklistForm;
use App\Livewire\PetarForm;
use App\Livewire\PetsManager;
use App\Livewire\SupervisorInbox;
use App\Livewire\DocumentHistory;
use App\Livewire\AnnouncementsBoard;

// ALMACÉN
use App\Livewire\Warehouse\Catalog;
use App\Livewire\Warehouse\MyRequests;
use App\Livewire\Warehouse\ManageRequests;

// ADMIN Y SETUP
use App\Livewire\Admin\CreateCompany;
use App\Livewire\Company\StaffMan;
use App\Livewire\Setup\SetupPassword;
use App\Livewire\Setup\SetupTerms;
use App\Livewire\Actions\Logout;

Route::view('/', 'welcome');

// 1. CONFIGURACIÓN (SOLO AUTH)
Route::middleware(['auth'])->group(function () {
    Route::get('/setup/password', SetupPassword::class)->name('setup.password');
    Route::get('/setup/terms', SetupTerms::class)->name('setup.terms');
    Route::get('/hub', function () { return view('hub'); })->name('hub');
});

// 2. SISTEMA (PROTEGIDO)
Route::middleware(['auth', 'verified', \App\Http\Middleware\CheckFirstLogin::class])->group(function () {

    // DASHBOARD
    Route::get('dashboard', function () {
        $user = auth()->user();
        if ($user->role === 'supervisor') {
            $allDocs = Document::with('user')->get();
            $pendientes = $allDocs->where('status', 'En espera')->count();
            $aprobados  = $allDocs->where('status', 'Aprobado')->count();
            $rechazados = $allDocs->where('status', 'Rechazado')->count();
            $topAreas = $allDocs->groupBy(fn($d) => $d->content['lugar'] ?? 'Sin definir')->map->count()->sortDesc()->take(5);
            $topPeligros = $allDocs->groupBy(fn($d) => $d->content['peligro'] ?? 'Sin definir')->map->count()->sortDesc()->take(5);
            $topWorkers = $allDocs->groupBy('user.name')->map->count()->sortDesc()->take(5);
            $docStats = $allDocs->groupBy('type')->map(function ($docs) {
                $total = $docs->count();
                $rejected = $docs->where('status', 'Rechazado')->count();
                return ['total' => $total, 'rejected' => $rejected, 'rate' => $total > 0 ? round(($rejected / $total) * 100) : 0];
            })->sortByDesc('total');
            return view('dashboard', compact('pendientes', 'aprobados', 'rechazados', 'topAreas', 'topPeligros', 'topWorkers', 'docStats'));
        } else {
            $pendientes = Document::where('user_id', $user->id)->where('status', 'En espera')->count();
            $aprobados  = Document::where('user_id', $user->id)->where('status', 'Aprobado')->count();
            $rechazados = Document::where('user_id', $user->id)->where('status', 'Rechazado')->count();
            return view('dashboard', compact('pendientes', 'aprobados', 'rechazados'));
        }
    })->name('dashboard');

    // RUTAS ALMACÉN
    Route::get('/almacen/catalogo', Catalog::class)->name('warehouse.catalog');
    Route::get('/almacen/mis-pedidos', MyRequests::class)->name('warehouse.requests');
    Route::get('/almacen/gestion', ManageRequests::class)->name('warehouse.manage');
    Route::get('/almacen', function () { return redirect()->route('warehouse.catalog'); })->name('warehouse.index');

    // --- EXPORTAR KARDEX CON FILTRO FECHAS ---
    Route::get('/almacen/exportar-kardex', function (\Illuminate\Http\Request $request) {
        if (auth()->user()->warehouse_role !== 'admin' && auth()->user()->role !== 'supervisor') abort(403);

        $from = $request->query('from') ? \Carbon\Carbon::parse($request->query('from'))->startOfDay() : now()->startOfMonth();
        $to = $request->query('to') ? \Carbon\Carbon::parse($request->query('to'))->endOfDay() : now()->endOfDay();

        $movements = \App\Models\WarehouseMovement::with(['item', 'user', 'request.user'])
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at', 'asc')
            ->get();

        $csvName = 'Kardex_' . $from->format('d-m') . '_al_' . $to->format('d-m') . '.csv';
        $headers = ["Content-type" => "text/csv; charset=UTF-8", "Content-Disposition" => "attachment; filename=$csvName", "Pragma" => "no-cache", "Cache-Control" => "must-revalidate, post-check=0, pre-check=0", "Expires" => "0"];
        
        $callback = function() use($movements) {
            $file = fopen('php://output', 'w'); fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['FECHA', 'HORA', 'TIPO', 'PRODUCTO', 'CANTIDAD', 'RESPONSABLE', 'DETALLE']);
            foreach ($movements as $mov) {
                $solicitante = $mov->request && $mov->request->user ? $mov->request->user->name : 'Ajuste';
                fputcsv($file, [
                    $mov->created_at->format('d/m/Y'), 
                    $mov->created_at->format('H:i:s'),
                    $mov->type, 
                    $mov->item->nombre ?? 'Eliminado', 
                    $mov->quantity, 
                    $mov->user->name, // Almacenero
                    $mov->reason . ' (Solicitante: ' . $solicitante . ')'
                ]);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    })->name('warehouse.kardex');

    // OTRAS RUTAS
    Route::get('/super-admin/crear-empresa', CreateCompany::class)->name('admin.create-company');
    Route::get('/personal', StaffMan::class)->name('company.staff');
    Route::get('iperc/crear', IpercForm::class)->name('iperc.create');
    Route::get('ats/crear', AtsForm::class)->name('ats.create');
    Route::get('checklist/crear', ChecklistForm::class)->name('checklist.create');
    Route::get('petar/crear', PetarForm::class)->name('petar.create');
    Route::get('/pets', PetsManager::class)->name('pets.index');
    Route::get('/supervisor/bandeja', SupervisorInbox::class)->name('supervisor.inbox');
    Route::get('/historial', DocumentHistory::class)->name('history');
    Route::get('/anuncios', AnnouncementsBoard::class)->name('anuncios');
    Route::get('/documento/{id}/imprimir', function ($id) {
        $document = \App\Models\Document::findOrFail($id);
        return view('documents.print', compact('document'));
    })->name('document.print');
    Route::get('/almacen/catalogo', \App\Livewire\Warehouse\Catalog::class)->name('warehouse.catalog');
    Route::get('/almacen', function () {
        return redirect()->route('warehouse.catalog');
    })->name('warehouse.index');
    Route::get('/exportar-reporte', function () {
        $documents = Document::with(['user', 'supervisor'])->latest()->get();
        $csvFileName = 'Reporte_SST_' . date('d-m-Y') . '.csv';
        $headers = ["Content-type" => "text/csv; charset=UTF-8", "Content-Disposition" => "attachment; filename=$csvFileName", "Pragma" => "no-cache", "Cache-Control" => "must-revalidate, post-check=0, pre-check=0", "Expires" => "0"];
        $callback = function() use($documents) {
            $file = fopen('php://output', 'w'); fputs($file, "\xEF\xBB\xBF"); 
            fputcsv($file, ['ID', 'FECHA', 'TIPO', 'TRABAJADOR', 'AREA', 'PELIGRO', 'ESTADO']);
            foreach ($documents as $doc) {
                fputcsv($file, [$doc->id, $doc->created_at->format('d/m/Y'), $doc->type, $doc->user->name, $doc->content['lugar']??'-', $doc->content['peligro']??'-', $doc->status]);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    })->name('export.csv');


    Route::view('profile', 'profile')->name('profile');
});

Route::post('logout', function (Logout $logout) {
    $logout();
    return redirect('/');
})->middleware('auth')->name('logout');

require __DIR__.'/auth.php';