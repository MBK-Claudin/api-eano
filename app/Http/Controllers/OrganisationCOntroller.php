<?php

namespace App\Http\Controllers;

use App\Models\organisation;
use Illuminate\Http\Request;

class OrganisationCOntroller extends Controller
{
    public function organisations () {
        $org = organisation::get();
        if ($org) {
            return response()->json($org);
        }else {
            return response()->json(['message' => 'No organisation found'], 404);
        }
    }
}
