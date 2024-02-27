<?php


use App\Http\Controllers\GenerateArticleController;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;

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

Route::get('/generate-aritcle', function () {
    return view('generate-aritcle');
});
Route::get('/create', function () {
    return view('create');
});

Route::post('/convert-files', [GenerateArticleController::class, 'convertFiles'])->name('convert-files');
Route::get('/download-converted-file/{filename}', [GenerateArticleController::class, 'downloadConvertedFile'])->name('download-converted-file');

Route::get('/python_api',function()
{
    $client = new Client();
     $api_url = "http://127.0.0.1:5000/api";
     $res = $client->post($api_url,[
        'json' => [
            'text' => 'hello'
            ]
     ]);
     // Check if the request was successful
     if ($res->getStatusCode() == 200) {
        $data = json_decode($res->getBody(), true);
        dd($data);
    } else {
        // Handle error if needed
        dd("Error: " . $res->getStatusCode());
    }

});
Route::post('/generate-article', [GenerateArticleController::class, 'generateArticle'])->name('generateArticle');
