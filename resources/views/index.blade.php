<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Users</h1>

    <table border="1">
        <thead>
            <th>User</th>
            <th>Projects</th>
        </thead>
        <tbody>
            @foreach (\App\User::with('projects')->get() as $user)
                <tr>
                    <td>{{ $user->username }}</td>
                    <td>
                        <ul>
                            @forelse ($user->projects as $project)
                                <li>{{ $project->title }}</li>
                            @empty
                                <li>None</li>
                            @endforelse
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <h1>Projects</h1>

    <table border="1">
        <thead>
            <th>Project</th>
            <th>Description</th>
            <th>Owner</th>
            <th>Members</th>
        </thead>
        <tbody>
            @foreach (\App\Models\Project::with('members')->get() as $project)
                <tr>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->description }}</td>
                    <td>{{ $project->owner->username }}</td>
                    <td>
                        <ul>
                            @forelse ($project->members as $member)
                                <li>{{ $member->username }}</li>
                            @empty
                                <li>None</li>
                            @endforelse

                            {{ $project->members_count }}
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
