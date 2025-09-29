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
                <th scope="col">Label</th>
                <th scope="col">Type</th>
                <th scope="col">Group</th>
                <th scope="col">Field Type</th>
                <th scope="col">Field Type Default Value</th>
                <th scope="col">Created By</th>
                <th scope="col">Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($properties as $index=>$value)
                @php
                    $field_type = $value->field_type;
                    $default_value = Modules\CRM\App\Enum\CustomPropertyFieldType::getDefaultValueColumnName($field_type);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $value->label }}</td>
                    <td>{{ $value->type->name }}</td>
                    <td>{{ $value->group->name }}</td>
                    <td>{{ $value->field_type  }}</td>
                    <td>{{ $default_value }}</td>
                    <td>{{ $value->createdBy->name ?? '' }}</td>
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
