<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $reportTitle ?? 'Sales Report' }}</title>
    <style>
        html * {
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            font-size: 11px;
            color: #222;
        }
        .header {
            text-align: center;
            margin-bottom: 14px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
        }
        .header p {
            font-size: 11px;
            margin: 2px 0 0;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 0.5px solid #999;
            padding: 4px 6px;
        }
        th {
            background-color: #eee;
            text-align: left;
        }
        td.number, th.number {
            text-align: right;
        }
        td.center, th.center {
            text-align: center;
        }
        tfoot td {
            font-weight: bold;
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>NMS &mdash; {{ $reportTitle ?? 'Sales Report' }}</h1>
        <p>{{ $monthLabel }} {{ $year }}</p>
        @if(!empty($tripKeyword))
            <p>Trip: {{ $tripKeyword }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Trip</th>
                <th>Buyer</th>
                <th>Truck</th>
                <th class="number">Total</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sales as $sale)
                <tr>
                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                    <td>{{ $sale->trip->code ?? '-' }}</td>
                    <td>{{ $sale->buyer->name ?? '-' }}</td>
                    <td>{{ $sale->truck->name ?? '-' }}</td>
                    <td class="number">{{ number_format($sale->total, 2) }}</td>
                    <td class="center">{{ $sale->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">No sales found for this period.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="center">Total</td>
                <td class="number">{{ number_format($total, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
