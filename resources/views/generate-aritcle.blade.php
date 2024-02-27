<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Writebot - AI Writing Assistant for Bloggers</title>

    <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Space+Grotesk:wght@400;600;700&display=swap" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: "Space Grotesk", sans-serif;
        }

        .title:empty:before {
            content: attr(data-placeholder);
            color: gray;
        }
    </style>

    <script src="https://unpkg.com/marked" defer></script>
</head>

<body class="antialiased">
    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
        <div class="max-w-6xl w-full mx-auto sm:px-6 lg:px-8 space-y-4 py-4">
            <div class="text-center text-gray-800 dark:text-gray-300 py-4">
                <h1 class="text-7xl font-bold">Generate Articles</h1>
            </div>

            <div class="w-full rounded-md bg-white border-2 border-gray-600 p-4 min-h-[60px] h-full text-gray-600">
                <form class="inline-flex gap-2 w-full" id="interview-questions-search">
                    @csrf
                    <input required name="title" class="w-full outline-none text-2xl font-bold"
                        placeholder="Type your article title..." />
                    <button type="submit" class="rounded-md bg-emerald-500 px-4 py-2 text-white font-semibold">
                        Generate
                    </button>
                </form>
            </div>
            <div class="w-full rounded-md bg-white border-2 border-gray-600 p-4 min-h-[720px] h-full text-gray-600">
                <textarea class="min-h-[720px] h-full w-full outline-none" spellcheck="false" id="search-results">
                </textarea>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism.css">



    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/plugins/autoloader/prism-autoloader.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/components/prism-markup.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/components/prism-clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/plugins/toolbar/prism-toolbar.min.js"></script>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/plugins/custom-class/prism-custom-class.min.js">
    </script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/plugins/data-uri-highlight/prism-data-uri-highlight.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.20.0/plugins/diff-highlight/prism-diff-highlight.min.js">
    </script>
 {{-- wants submit form using ajax call --}}
 <script>
    $(document).ready(function() {
        $('#interview-questions-search').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('generateArticle') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#search-results').html(response);
                    Prism.highlightAll();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });
        Prism.highlightAll();
    });
</script>
</body>

</html>
