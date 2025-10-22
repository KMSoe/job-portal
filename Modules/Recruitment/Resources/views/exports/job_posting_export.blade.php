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
                <th scope="col">Company</th>
                <th scope="col">Department</th>
                <th scope="col">Designation</th>
                <th scope="col">Title</th>
                <th scope="col">Experience Level</th>
                <th scope="col">Job Function</th>
                <th scope="col">Min Education Level</th>
                <th scope="col">Summary</th>
                <th scope="col">Open To</th>
                <th scope="col">Roles And Responsibilities</th>
                <th scope="col">Requirements</th>
                <th scope="col">What We Can Offer Benefits</th>
                <th scope="col">What We Can Offer Highlights</th>
                <th scope="col">What We Can Offer Career Opportunities</th>
                <th scope="col">Job Type</th>
                <th scope="col">Work Arrangement</th>
                <th scope="col">Location</th>
                <th scope="col">Skills</th>
                <th scope="col">Salary Type</th>
                <th scope="col">Currency Code</th>
                <th scope="col">Salary Notes</th>
                <th scope="col">Min Salary</th>
                <th scope="col">Max Salary</th>
                <th scope="col">No of Vacancies</th>
                <th scope="col">status</th>
                <th scope="col">Published At</th>
                <th scope="col">Deadline Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index=>$item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    {{-- Organizational Fields --}}
                    <td>{{ $item->company->name ?? 'N/A' }}</td>
                    <td>{{ $item->department->name ?? 'N/A' }}</td>
                    <td>{{ $item->designation->name ?? 'N/A' }}</td>

                    {{-- Job Details Fields --}}
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->experienceLevel->name ?? 'N/A' }}</td>
                    <td>{{ $item->jobFunction->name ?? 'N/A' }}</td>
                    <td>{{ $item->minimumEducationLevel->name ?? 'N/A' }}</td>
                    <td>{{ $item->summary }}</td>
                    <td>{{ $item->open_to }}</td>
                    <td>{{ $item->roles_and_responsibilities }}</td>
                    <td>{{ $item->requirements }}</td>

                    {{-- What We Can Offer Fields --}}
                    <td>{{ $item->what_we_can_offer_benefits }}</td>
                    <td>{{ $item->what_we_can_offer_highlights }}</td>
                    <td>{{ $item->what_we_can_offer_career_opportunities }}</td>

                    {{-- Type and Location Fields --}}
                    <td>{{ $item->job_type }}</td>
                    <td>{{ $item->work_arrangement }}</td>
                    <td>{{ $item->location }}</td>

                    {{-- Skills Field (Assuming 'skills' is a collection relationship) --}}
                    <td>
                        @if ($item->skills)
                            {{ $item->skills->pluck('name')->implode(', ') }}
                        @else
                            N/A
                        @endif
                    </td>

                    {{-- Compensation Fields --}}
                    <td>{{ $item->salary_type }}</td>
                    <td>{{ $item->salaryCurrency->code ?? 'N/A' }}</td>
                    <td>{{ $item->salary_notes ?? 'N/A' }}</td>
                    <td>{{ $item->min_salary ?? ($item->salary_amount ?? 'N/A') }}</td>
                    <td>{{ $item->max_salary ?? ($item->salary_amount ?? 'N/A') }}</td>

                    {{-- Status and Dates Fields --}}
                    <td>{{ $item->vacancies }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->published_at ? Carbon\Carbon::parse($item->published_at)->format('Y-m-d') : 'N/A' }}
                    </td>
                    <td>{{ $item->deadline_date ? Carbon\Carbon::parse($item->deadline_date)->format('Y-m-d') : 'N/A' }}
                    </td>
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
