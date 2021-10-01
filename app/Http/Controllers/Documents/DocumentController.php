<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;;
use Illuminate\Http\JsonResponse;
use App\Models\Profile;
use App\Models\Document;
use App\Traits\CreateItems;
use App\Traits\UpdateItems;
use Storage;
use File;
use ZipArchive;

class DocumentController extends Controller
{
    use CreateItems, UpdateItems;

    public function uploadDocument(Request $request){
      $request->validate([
            'file' => 'required|mimes:png,jpg,jpeg,pdf|max:2048'
          ],
          [
            'file.mimes' => 'Файл должен быть формата: png, jpg, jpeg, pdf.',
            'file.max' => 'Файл не должен превышать :max Кбайт.'
          ]);
      $profile = Profile::find($request->id);
      $this->createDocument($profile, $request->file, $request->name);
      return response()->json('200');
    }

    public function downloadDocumentsToZip(Request $request){
       $zip = new ZipArchive;
       $fileName = 'download.zip';
       if ($zip->open(storage_path('app/public/profiles/'.$request->id.'/documents/'.$fileName), ZipArchive::CREATE) === TRUE) {
           foreach ($request->docs as $doc) {
             $file = Document::find($doc);
             $path = $file->path;
             $filename = $file->file_name;
             $zip->addFile(storage_path('app/'.$path), $filename);
           }
           $zip->close();
       }
       return response()->download(storage_path('app/public/profiles/'.$request->id.'/documents/'.$fileName), $fileName)->deleteFileAfterSend(true);
    }

    public function deleteDocuments(Request $request){
      foreach ($request->docs as $doc) {
        $file = Document::find($doc);
        $path = $file->path;
        Storage::delete($path);
        $file->delete();
      }
      return response()->json('200');
    }
}
