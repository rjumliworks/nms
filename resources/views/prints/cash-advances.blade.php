<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cash Advances Report</title>
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
        <h1>NMS &mdash; Cash Advances Report</h1>
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
                <th>Recipient</th>
                <th>Category</th>
                <th class="number">Amount</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
                <tr>
                    <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                    <td>{{ $loan->trip->code ?? '-' }}</td>
                    <td>{{ $loan->employee->name ?? '-' }}</td>
                    <td>{{ $loan->category->name ?? '-' }}</td>
                    <td class="number">{{ number_format($loan->amount, 2) }}</td>
                    <td class="center">
                        @if($loan->is_cancelled) Cancelled
                        @elseif($loan->is_paid) Paid
                        @else Unpaid
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">No cash advances found for this period.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="center">Total (excluding cancelled)</td>
                <td class="number">{{ number_format($total, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
