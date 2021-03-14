<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">


        <!-- Styles -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body class="font-sans antialiased">
        <div class="row">
                <b>
                    REFRESHMOBILE
                </b>
        </div>
        <div class="row">
            <div class="col-12">
                <b>Date: </b>
                {{ date("d/m/Y")  }}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <b>Quote N: </b>
                {{ str_pad($quote->id, 6, "0", STR_PAD_LEFT) }}
            </div>
        </div>
                
        <hr />
        <table class="table table-striped pb-4">
            <thead>
                <tr>
                    <th class="text-right" scope="col">DEVICE</th>
                    <th class="text-right" scope="col">BASE PRICE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-right">
                        {{ $quote->device->model  }}
                    </td>
                    <td class="text-right">
                        $ {{ number_format($quote->storePrice, 2)  }}
                    </td>
                </tr>
            </tbody>
        </table>
        @if($quote->issues->count() > 0)
            <table class="table table-striped pb-4">
                <thead>
                    <tr>
                        <th scope="col">ISSUES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quote->issues as $issue)
                        <tr>
                            <td>
                                {{ $issue->name  }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <table class="table table-striped pb-4">
            <tr>
                <th scope="row" class="text-right">Total:</th>
                <td>
                    $ {{ number_format($quote->value, 2)  }}</td>
            </tr>
        </table>
    </body>
</html>
