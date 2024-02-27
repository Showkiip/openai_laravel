<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Converted File</title>
    <link href="https://fonts.bunny.net/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #loader {
            display: none;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="max-w-6xl w-full mx-auto sm:px-6 lg:px-8 space-y-4 py-4">
                <div class="text-center text-gray-800 dark:text-gray-300 py-4">
                    <h1 class="text-7xl font-bold">Upload PDF</h1>
                </div>
                <div class="w-full rounded-md bg-white border-2 border-gray-600 p-4 min-h-[60px] h-full text-gray-600">
                    <div class="w-full">
                        {{-- use select for type --}}
                        <select name="type" id="type"
                            class="block w-full px-3 py-2 mt-1 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="pdftodocx">PDF 2 DOCX</option>
                            <option value="docxtopdf">DOCX 2 PDF</option>
                        </select>
                        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Choose a file
                        </label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input type="file" name="file" id="file"
                                class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300">
                        </div>
                        <div class="mt-2">
                            <button type="button" id="uploadBtn"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <span id="loader"></span> Upload
                            </button>
                        </div>
                        <div id="errorMessage" class="mt-2 text-red-500"></div>
                    </div>
                </div>
            </div>
        </form>

        <div id="convertedFileInfo" class="hidden mt-4">
            <h2 class="text-2xl font-bold">Converted File:</h2>
            <p id="convertedFileName"></p>
            <a id="downloadLink" href="#" download="converted_file.docx"
                class="inline-block mt-2 px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300">
                Download
            </a>
        </div>

        <script>
            $(document).ready(function() {
                $('#uploadBtn').on('click', function() {
                    $('#uploadBtn').attr('disabled', true);
                    $('#loader').show();
                    $('#convertedFileInfo').addClass('hidden');
                    $('#errorMessage').text('');
                    var formData = new FormData($('#uploadForm')[0]);
                    $.ajax({
                        url: "{{ route('convert-files') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                        },
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#loader').hide();
                            $('#uploadBtn').attr('disabled', false);
                            $('#convertedFileName').text(response.convertedFileName);
                            $('#downloadLink').attr('href', response.downloadLink);
                            $('#convertedFileInfo').removeClass('hidden');
                            $('#file').val('');
                        },
                        error: function(xhr) {
                            $('#loader').hide();
                            $('#uploadBtn').attr('disabled', false);
                            $('#errorMessage').text('Error: ' + xhr.responseText);
                        }
                    });
                });
            });
        </script>
    </div>

</body>

</html>
