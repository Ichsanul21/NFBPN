<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RegistrationsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PpdbExportController extends Controller
{
    public function __invoke(Request $request)
    {
        abort_unless($request->user()->can('ppdb.export'), 403);

        $filters = $request->only(['period_id', 'jenjang', 'status']);

        return Excel::download(
            new RegistrationsExport($filters),
            'ppdb-'.now()->format('Ymd-His').'.xlsx'
        );
    }
}
