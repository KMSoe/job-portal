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
                <th scope="col">Country</th>
                <th scope="col">Indsutry</th>
                <th scope="col">Account Owner</th>
                <th scope="col">Number of Employees</th>
                <th scope="col">Brand Value</th>
                <th scope="col">Tier</th>
                <th scope="col">Completed Project</th>
                <th scope="col">Working Project</th>
                <th scope="col">Account Revenue</th>
                <th scope="col">Annual Revenue</th>
                <th scope="col">Address</th>
                <th scope="col">City</th>
                <th scope="col">Email</th>
                <th scope="col">Hotline</th>
                <th scope="col">First Project Created</th>
                <th scope="col">Domain</th>
                <th scope="col">Description</th>
                <th scope="col">Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index=>$item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $value->code }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->country->name ?? '' }}</td>
                    <td>{{ $value->industry->name ?? '' }}</td>
                    <td>{{ $value->accountOwner->name ?? '' }}</td>
                    <td>{{ $value->number_of_employees }}</td>
                    <td>{{ $value->brand_value }}</td>
                    <td>{{ $value->tier }}</td>
                    <td>
                        @forelse($value->completedProjects as $project)
                            {{ $project->name }}
                            @if (!$loop->last)
                                ,
                            @endif
                        @empty
                            No Project
                        @endforelse
                    </td>
                    <td>
                        @forelse($value->workingProjects as $project)
                            {{ $project->name }}
                            @if (!$loop->last)
                                ,
                            @endif
                        @empty
                            No Project
                        @endforelse
                    </td>
                    <td>{{ $value->accountRevenueCurrency->name ?? '' }} {{ $value->account_revenue }}</td>
                    <td>{{ $value->annualRevenueCurrency->name ?? '' }} {{ $value->annual_revenue }}</td>
                    <td>{{ $value->address }}</td>
                    <td>{{ $value->city }}</td>
                    <td>{{ $value->email }}</td>
                    <td>{{ $value->hotline_prefix . $value->hotline_number }}</td>
                    <td>{{ $value->first_project_created }}</td>
                    <td>{{ $value->domain_name }}</td>
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
