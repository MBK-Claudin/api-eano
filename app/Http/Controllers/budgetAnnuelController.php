<?php

namespace App\Http\Controllers;

use App\Imports\budgetAnnuelImport;
use App\Models\budgetAnnuel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class budgetAnnuelController extends Controller
{
    public function insertBudgetAnnuel(Request $request){

        $document = $request->file('excel');
        $filePath = $document->store('documents', 'local');
        $fileUrl = Storage::url($filePath);

        $ptba = budgetAnnuel::create([
            'periode' => $request->periode,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'file_name' => $document->getClientOriginalName(),
            'file_path' => $filePath,
            'file_url' => $fileUrl,
        ]);

        $ptba->programme_id = $request->programme_id;
        $ptba->save();

        Excel::import(new budgetAnnuelImport($ptba->id), $request->file('excel'));

        return response()->json([
            'message' => 'Budget Annuel inserted successfully',
        ], 201);
    }
}
