<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <table class="table table-responsive">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col">Stage</th>
                <th scope="col">Type</th>
                <th scope="col">Close Date</th>
                <th scope="col">Currency</th>
                <th scope="col">Amount</th>
                <th scope="col">Comapnies</th>
                <th scope="col">Contacts</th>
                <th scope="col">Description</th>
                <th scope="col">Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($deals as $index=>$value)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $value->code }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->stage }}</td>
                    <td>{{ $value->type }}</td>
                    <td>{{ $value->close_date }}</td>
                    <td>{{ $value->currency->name ?? '' }}</td>
                    <td>{{ number_format($value->amount) }}</td>
                    <td>
                        @forelse($value->companies as $company)
                            {{ $company->name }}
                            @if (!$loop->last)
                                ,
                            @endif
                        @empty
                            No Company
                        @endforelse
                    </td>
                    <td>
                        @forelse($value->contacts as $comtact)
                            {{ $comtact->full_name }}
                            @if (!$loop->last)
                                ,
                            @endif
                        @empty
                            No Contact
                        @endforelse
                    </td>
                    <td>{{ $value->description }}</td>
                    <td>{{ $value->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        No records exist!.
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>
</body>

</html>