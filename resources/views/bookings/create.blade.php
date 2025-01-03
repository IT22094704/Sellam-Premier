@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Book Your Seats</h1>

    <!-- Date Selection -->
    <label for="date">Select Date:</label>
    <input type="date" id="date" name="date">
    <button class="button" id="fetch-shows">Find Shows</button>

    <!-- Show Time Selection -->
    <div id="show-times" style="display: none;">
        <h3>Select Show Time</h3>
        <ul id="shows-list"></ul>
    </div>
</div>

<script>
    let showsData = []; // Declare a global array to store shows data.

    document.getElementById('fetch-shows').addEventListener('click', function () {
        const date = document.getElementById('date').value;

        fetch("{{ route('booking.getShows') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ date, _token: "{{ csrf_token() }}" })
        })
        .then(response => response.json())
        .then(data => {
            showsData = data; // Assign data to the global array
            const showList = document.getElementById('shows-list');
            showList.innerHTML = '';
            data.forEach(show => {
                const li = document.createElement('li');
                li.textContent = `${show.time} - ${show.movie_name}`;
                li.dataset.showId = show.id;
                const button = document.createElement('button');
                button.textContent = 'Select Show';
                button.classList.add('button');
                button.addEventListener('click', () => {
                    window.location.href = `/booking/create/${show.id}`;
                });
                li.appendChild(button);
                showList.appendChild(li);
            });
            document.getElementById('show-times').style.display = 'block';
        });
    });
</script>
@endsection
