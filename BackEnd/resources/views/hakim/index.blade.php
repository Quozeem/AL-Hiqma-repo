<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<meta name="csrf-token" content="{{csrf_token()}}"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        form {
            width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #f7f7f7;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    
        .form-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
    
        .form-group label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }
    
        .form-group select {
            width: 48%; /* Set width to ensure the select boxes are side by side */
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
            appearance: none;
        }
    
        button {
            display: inline-block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
    
        button:hover {
            background-color: #0056b3;
        }
    
        select:focus, button:focus {
            outline: none;
            border-color: #007bff;
        }
        /* Style for the table */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 16px;
    text-align: left;
}

/* Add border to the table and cells */
table, th, td {
    border: 1px solid #ddd;
    padding: 12px;
}

/* Styling the table head */
thead {
    background-color: #f2f2f2;
    font-weight: bold;
}

thead th {
    background-color: #4CAF50; /* Optional: Add a background color to the header */
    color: white; /* Optional: Set the text color to white */
    padding-top: 12px;
    padding-bottom: 12px;
}

/* Zebra stripes for table rows */
tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Hover effect for table rows */
tbody tr:hover {
    background-color: #f1f1f1;
}

    </style>
</head>
<body>
    <form method="post" action="{{ route('filterEnrollments') }}">
        @csrf
        <div class="form-group">
<label for="faculty">Faculty</label>
<select id='faculty'>
    @foreach($faculties as $faculty)
    <option value="{{ $faculty['id'] }}">
       {{ $faculty['title']}}
</option>
@endforeach
</select>

<label for="Program">Program</label>
<select id="program" name='program_id'>
    <option value="">Select Program</option> <!-- Default program option -->
</select>

<label for="Session">Session</label>
<select name='session_id'>
    @foreach($sessions as $session)
    <option value="{{ $session['id'] }}">
       {{ $session['title']}}
</option>
@endforeach
</select>

<label for="Sememster">Sememster</label>
<select name='semester_id'>
    @foreach($semesters as $semester)
    <option value="{{ $semester['id'] }}">
       {{ $semester['title']}}
</option>
@endforeach
</select>

<label for="Section">Section</label>
<select name='section_id'>
    @foreach($sections as $section)
    <option value="{{ $section['id'] }}">
       {{ $section['title']}}
</option>
@endforeach

</select>
        </div>
<button type='submit'>Filter</button>
</form>
@if(isset($data))
  <h3 style="text-align:center">AL - HIQMA </h3>
<table>
    <thead><tr>
        <td>Student Name</td>
        <td>Student Reg</td>
        <td>Program</td>
        @foreach($subjects as $subject)
        <td>{{ $subject['subject'] }} ({{ $subject['shortcode'] }})</td>
    @endforeach
    <td>Average Score</td>
    <td>Grade</td>
    <td>Ramark</td>
</tr>
</thead>
    <tbody>
        @foreach($data as $student)
        <tr>
            <td>{{ $student['student_name'] }}</td>
            <td>{{ $student['student_id'] }}</td>
            <td>{{ $student['programs'] }}</td>
            @foreach($subjects as $subject)
            @php
                // Find the subject's score for this student
                $score = collect($student['subjects'])->firstWhere('subject', $subject['subject'])['total_score'] ?? 'N/A';
            @endphp
            <td>{{ $score }}/100</td> <!-- Display the score for the subject -->
        @endforeach
        <td>{{ $student['averageScore'] }}</td>
        <td>{{ $student['cpga'] }}</td>
        <td>{{ $student['remark'] }}</td>
    </tr>
    @endforeach

</tbody>
</table>
@endif
<script>
    // AJAX get program based on selected faculty
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // When faculty is selected
        $('#faculty').on('change', function() {
            var facultyId = $(this).val(); // Get the selected faculty ID


            $.ajax({
                url: "{{ route('faculty') }}/" + facultyId, // Add the faculty ID to the URL
                type: 'GET',
                success: function(response) {
                    if (response) {


                        // Clear previous options
                        $('#program').empty();

                        // Populate program options from the response
                        $.each(response, function(key, program) {
                            $('#program').append('<option value="' + program.id + '">' + program.title + '</option>');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error: " + status + error);
                }
            });
        });
    });
</script>



</html>
</body>