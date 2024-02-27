<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use OpenAI;
use Illuminate\Support\Facades\Storage;


class GenerateArticleController extends Controller
{
    public function generateArticle(Request $request)
    {
        $title = $request->title;
        $client = OpenAI::client(env('OPEN_AI_TOKEN'));
        $result = $client->completions()->create([

            'model' => 'gpt-3.5-turbo-instruct',
            'prompt' => sprintf('Write article about: %s', $title),
            'temperature' => 0.7,
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
            'best_of' => 1,
            'max_tokens' => 600,

        ]);
        $content = $result['choices'][0]['text'];
        $highlightedContent = $this->highlightCodeBlocks($content);

        return response()->json($highlightedContent);
    }

    function highlightCodeBlocks($content)
    {
        // Regular expression to match content inside triple backticks
        $pattern = '/```(.*?)```/s';

        // Callback function to wrap the matched content with <pre> and <code> tags
        $callback = function ($matches) {
            return '<pre><code class="language-php">' . htmlspecialchars(trim($matches[1])) . '</code></pre>';
        };

        // Perform the replacement
        $highlightedContent = preg_replace_callback($pattern, $callback, $content);

        return $highlightedContent;
    }

    public function convertFiles(Request $request)
    {
        //validate


        $file = $request->file('file');

        $client = new Client();
        $api_url = "http://127.0.0.1:5000/convert";
        $res = $client->post($api_url, [
            'json' => [
                'file' => base64_encode(file_get_contents($file->path())),
                'type' => $request->type,
            ],
        ]);
        if ($res->getStatusCode() == 200) {
            $data = json_decode($res->getBody(), true);
            $decodedContent = base64_decode($data['converted_file']);
            $tempFilePath = tempnam(sys_get_temp_dir(), 'converted_file_');
            file_put_contents($tempFilePath, $decodedContent);
            // Get the original filename (optional)
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // Save the converted file to the storage disk
            if ($request->type == "pdftodocx") {
                $convertedFileName = $originalFilename . '_converted.docx';
            } elseif ($request->type == "docxtopdf") {

                $convertedFileName = $originalFilename . '_converted.pdf';
            }

            Storage::put($convertedFileName, $decodedContent);

            // Return the view with the converted file information
            return response()->json([
                'convertedFileName' => $convertedFileName,
                'downloadLink' => route('download-converted-file', ['filename' => $convertedFileName]),
            ]);
            // return response()->download($tempFilePath, 'converted_file.docx')->deleteFileAfterSend();
        } else {
            return response()->json(['error' => 'Error: ' . $res->getStatusCode()], 500);
        }
    }

    public function downloadConvertedFile($filename)
    {
        // Retrieve the file from the storage disk
        $filePath = Storage::path($filename);

        // Return the file for download
        return response()->download($filePath);
    }
}
