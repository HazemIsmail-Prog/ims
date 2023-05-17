<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $store->name }}</title>
    <style>
        .header-title {
            border-bottom: 1px solid #ccc;
            padding-bottom: 0.5rem;
        }

        .header-details {
            font-size: 0.9rem;
            line-height: 1.5rem;
            color: #2d2d2d;
            margin-bottom: 0.5rem;
        }

        .table {
            width: 100%;
            font-size: 0.875rem;
            line-height: 1.25rem;
            color: #2d2d2d;
        }

        .thead {
            font-size: 0.75rem;
            line-height: 1rem;
            color: rgb(55, 65, 81);
            text-transform: uppercase;
            background-color: rgb(249 250 251);

        }

        .th,
        .td {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
            text-align: start;
            border: 1px solid #ccc;
        }

        .text-center {
            text-align: center;
        }

        .text-start {
            text-align: start;
        }

        .text-end {
            text-align: end;
        }
    </style>
</head>

<body>
    <h1 class="header-title">{{ $store->name }}</h1>
    {{-- <div class="header-details">
        <table>
            <tr>
                <td>Date</td>
                <td><strong>: {{ $transaction->date->format('d-m-Y') }}</strong></td>
            </tr>
            <tr>
                <td>Type</td>
                <td><strong>: {{ $transaction->type }}</strong></td>
            </tr>
            <tr>
                <td>From</td>
                <td><strong>: {{ $transaction->source_store->name }}</strong></td>
            </tr>
            <tr>
                <td>To</td>
                <td><strong> : {{ $transaction->destination_store->name }}</strong></td>
            </tr>
            @if ($transaction->notes)
                <tr>
                    <td>Notes</td>
                    <td><strong>: {{ $transaction->notes }}</strong></td>
                </tr>
            @endif
        </table>
    </div> --}}

    <table class="table">
        <thead class="thead">
            <tr>
                <th class="th text-start">Item</th>
                <th class="th text-center">Unit</th>
                <th class="th text-center">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $row)
                <tr>
                    <td class="td text-start">{{ $row['name'] }}</td>
                    <td class="td text-center">{{ $row['unit'] }}</td>
                    <td class="td text-center">{{ $row['total_in'] - $row['total_out'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
