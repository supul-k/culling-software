<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class PhotoUploadController extends Controller
{
    public function upload(Request $request)
    {
        $files = $request->file('files');

        $albumId = time();

        foreach ($files as $file) {

            $path = Storage::putFile('public/photos', $file);

            $photo = new Photo();
            $photo->user_id = 1;
            $photo->caption = $file->getClientOriginalName();
            $photo->path = $path;
            $photo->album_id = $albumId;
            $photo->save();
        }

        $uploadedPhotos = Photo::where('album_id', $albumId)->get();

        return view('portfolio')->with('photos', $uploadedPhotos);
    }


    public function process(Request $request)
    {
        $filesToDeleteGood = Storage::disk('local')->files('/ML_Project/good');
        foreach ($filesToDeleteGood as $file) {
            Storage::disk('local')->delete($file);
        }

        $filesToDeleteBad = Storage::disk('local')->files('/ML_Project/bad');
        foreach ($filesToDeleteBad as $file) {
            Storage::disk('local')->delete($file);
        }

        $albumId = $request->input('album_id');

        $uploadedPhotos = Photo::where('album_id', $albumId)->get();

        // Move uploaded photos to the ML project's images folder
        foreach ($uploadedPhotos as $photo) {
            $path = $photo->getAttribute('path');
            $filename = str_replace('public/photos/', '', $path);
            $filePath = 'photos/' . $filename;
            $destinationFilePath = '/ML_Project/images/' . $filename;
            // $fileContents = Storage::disk('public')->get($filePath);
            Storage::disk('local')->copy($path, $destinationFilePath);
        }

        $scriptPath = app_path('ML_Project' . DIRECTORY_SEPARATOR . 'new.py');
        $result = shell_exec("python " . $scriptPath . " 2>&1");
        $outputLines = array_filter(explode("\n", $result));
        // dd($outputLines);
        $lastLine = end($outputLines);
        $value = intval(trim($lastLine));

        // Check the value and load the appropriate view
        if ($value === 1) {

            $filesToDeleteImages = Storage::disk('local')->files('/ML_Project/images');
            foreach ($filesToDeleteImages as $file) {
                Storage::disk('local')->delete($file);
            }

            $filesToDeletePhotos = Storage::disk('local')->files('public/photos');
            foreach ($filesToDeletePhotos as $file) {
                Storage::disk('local')->delete($file);
            }

            $imageDirectory = storage_path('app\public\ML_Project\good');
            return view('finalPortfolio')->with('imageDirectory', $imageDirectory);
        } else {
            return view('failure_view');
        }
    }

    public function downloadImages(Request $request)
    {
        $folderPath = public_path('ML_Project/good');

        $files = Storage::disk('public')->files('ML_Project/good');

        $zip = new \ZipArchive();
        $zipFileName = public_path('images.zip'); // Full path to the ZIP archive
        if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            // Add each file to the ZIP archive
            foreach ($files as $file) {
                // Get the file name from the full path
                $fileName = basename($file);
                // Add the file to the ZIP archive
                $zip->addFile($folderPath . DIRECTORY_SEPARATOR . $fileName, $fileName);
            }

            $zip->close();

            if (file_exists($zipFileName)) {
                // Download the ZIP file
                return response()->download($zipFileName)->deleteFileAfterSend();
            } else {
                // If ZIP archive creation fails, show an error message or redirect as needed
                return redirect()->back()->with('error', 'Failed to create ZIP archive.');
            }
        } else {
            // If ZIP archive creation fails, show an error message or redirect as needed
            return redirect()->back()->with('error', 'Failed to create ZIP archive.');
        }
    }
}
