<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trips Report</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>NMS &mdash; Trips Report</h1>
        <p>{{ $monthLabel }} {{ $year }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Date</th>
                <th>Boat</th>
                <th class="center">Carriers</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trips as $trip)
                <tr>
                    <td>{{ $trip->code }}</td>
                    <td>{{ $trip->date }}</td>
                    <td>{{ $trip->boat->name ?? '-' }}</td>
                    <td class="center">{{ $trip->carriers->count() }}</td>
                    <td class="center">{{ $trip->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">No trips found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
