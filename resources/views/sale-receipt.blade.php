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
    @isset($logo)
    <div class="row">
        <div class="col-12">
            <img src="data:image/png;base64,{{ $logo }}" alt="" style="max-width:6rem" />
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-12">
            <b>Date: </b>
            {{ date("d/m/Y")  }}
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <b>Receipt N: </b>
            {{ str_pad($sale->id, 6, "0", STR_PAD_LEFT) }}
        </div>
    </div>

    <hr />
    <table class="table table-striped pb-4">
        <thead>
            <tr>
                <th class="text-right" scope="col">DEVICE</th>
                <th class="text-right" scope="col">PRICE</th>
                <th class="text-right" scope="col">TAX</th>
                <th class="text-right" scope="col">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td class="text-right">
                    {{ $item["model"]  }}
                </td>
                <td class="text-right">
                    $ {{ number_format($item["discount"], 0)  }}
                </td>
                <td class="text-right">
                    $ {{ number_format($item["tax"], 0)  }}
                </td>
                <td class="text-right">
                    $ {{ number_format($item["subtotal"], 0)  }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-span-4 offset-md-8">
            <table class="table table-striped pb-4">
                <tr>
                    <td class="text-right">
                        <b>TOTAL:</b>
                        $ {{ number_format($total, 0)  }}
                    </td>
                </tr>
                <tr>
                </tr>
            </table>
        </div>
    </div>

    @isset($footer)
    <div class="row">
        <div class="col-12">
            {!! $footer !!}
        </div>
    </div>
    @endif
</body>

</html>