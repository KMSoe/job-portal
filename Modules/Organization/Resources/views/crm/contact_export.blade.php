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
                <th scope="col">Country</th>
                <th scope="col">name</th>
                <th scope="col">Contact Owner</th>
                <th scope="col">Job Title</th>
                <th scope="col">Completed Projects</th>
                <th scope="col">Working Projects</th>
                <th scope="col">Contact Type</th>
                <th scope="col">email</th>
                <th scope="col">Personal Email</th>
                <th scope="col">address</th>
                <th scope="col">Mobile Phone</th>
                <th scope="col">Office Phone</th>
                <th scope="col">First Contact Date</th>
                <th scope="col">Last Contact Date</th>
                <th scope="col">Estimate Conversation</th>
                <th scope="col">Remark</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contacts as $index=>$value)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $value->code }}</td>
                    <td>{{ $value->country->name ?? '' }}</td>
                    <td>{{ $value->honorific . ' ' . $value->first_name . ' ' . $value->last_name }}</td>
                    <td>{{ $value->contactOwner->name ?? '' }}</td>
                    <td>{{ $value->job_title }}</td>
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
                    <td>{{ $value->contact_type }}</td>
                    <td>{{ $value->email }}</td>
                    <td>{{ $value->personal_email }}</td>
                    <td>{{ $value->address }}</td>
                    <td>{{ $value->mobile_phone_prefix . $value->mobile_phone_number }}</td>
                    <td>{{ $value->office_phone_prefix . $value->office_phone_number }}</td>
                    <td>{{ $value->first_contact_date }}</td>
                    <td>{{ $value->last_contact_date }}</td>
                    <td>{{ $value->estimate_conversation }}</td>
                    <td>{{ $value->remark }}</td>
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
