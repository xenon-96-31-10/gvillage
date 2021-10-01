<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Document;

class DocumentController extends Controller
{
  public function getProfileDocuments(Request $request){
    $documents = Profile::find($request->id)->documents;
    return response()->json($documents);
  }
}
