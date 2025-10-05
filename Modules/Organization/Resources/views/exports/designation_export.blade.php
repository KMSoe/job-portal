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
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Created By</th>
                <th scope="col">Created At</th>
                <th scope="col">Updated By</th>
                <th scope="col">Updated At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index=>$item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <th>{{ $item->createdBy?->name ?? '-' }} </th>
                    <th>{{ $item->created_at === null ? '' : $item->created_at->format('d-m-Y') }}, {{ $item->created_at === null ? '' : $item->created_at->format('H:i:s') }}</th>
                    <th>{{ $item->updatedBy?->name ?? '-' }} </th>
                    <th>{{ $item->updated_at === null ? '' : $item->updated_at->format('d-m-Y') }}, {{ $item->updated_at === null ? '' : $item->updated_at->format('H:i:s') }}</th>
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