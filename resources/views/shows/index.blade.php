<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Table</title>

    <!-- Font Awesome for icons -->
    <link 
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-p6qD4WmF1g4p8qPQ5cM+PEOj8EeA0bg65dwZ2rBt+9v9V/GMq3O36RlhjzQpYYzTCnzqqe/GJZy43k5BSYyxzg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />

    <style>
        /* CSS Variables for Neumorphic Black and Gray Theme */
        :root {
            --background-color: #121212;
            --primary-color: #1e1e1e;
            --secondary-color: #2e2e2e;
            --text-color: #e0e0e0;
            --accent-color: #4CAF50;
            --button-color: #2e2e2e;
            --button-hover-color: #3e3e3e;
            --border-color: #555;
            --success-color: #4CAF50;
            --danger-color: #FF5555;
            --info-color: #2196F3;
            --muted-color: #777;
            --shadow-light: #2b2b2b;
            --shadow-dark: #0c0c0c;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        h1 {
            margin: 20px 0;
            text-align: center;
        }

        /* Success Message */
        .success-message {
            text-align: center; 
            color: green; 
            margin-bottom: 10px;
        }

        /* Slider Controls Container */
        .slider-controls {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 80%;
            margin: 0 auto 20px auto;
        }

        /* Slider Container */
        .slider-container {
            width: 80%;
            overflow: hidden;   /* Hides overflow for slider effect */
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .slider-wrapper {
            display: flex;
            transition: transform 0.5s ease;
            margin: 0; 
            padding: 0;
            justify-content: center;
        }
        .slider-item {
            flex: 0 0 auto;
            width: 180px;
            margin: 10px 5px; /* gap between items */
            text-align: center;
        }
        .slider-item img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .slider-item a {
            display: block;
            margin-top: 5px;
            text-decoration: none;
            color: #007BFF;
        }
        .slider-item a:hover {
            text-decoration: underline;
        }

        /* Slider Control Buttons (light gray background, black icons) */
        .slider-control-btn {
            background-color: #ccc;
            color: #000;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            line-height: 40px;
            opacity: 0.8;
            transition: opacity 0.3s;
            margin: 0 10px;
        }
        .slider-control-btn:hover {
            opacity: 1;
        }
        .slider-control-btn i {
            color: #000;
        }

        /* Add New Movie Button */
        .add-link {
            text-align: center;
            margin: 20px 0;
        }
        .add-link a {
            display: inline-block;
            padding: 8px 12px;
            background-color: rgb(178, 201, 163); 
            color: #333;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            margin-left: 1450px;
        }
        .add-link a:hover {
            background-color: rgb(35, 86, 60);
            color: rgb(237, 240, 239);
        }
        .add-link a i {
            margin-right: 5px;
            
        }

        /* Search Bar with Status and Date Range Filter */
        .search-bar {
            width: 80%;
            margin: 20px auto;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px; /* Adds space between elements */
            flex-wrap: wrap; /* Allows wrapping on smaller screens */
        }
        .search-bar .filter-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .search-bar input,
        .search-bar select,
        .search-bar input[type="date"] {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .search-bar input:focus,
        .search-bar select:focus,
        .search-bar input[type="date"]:focus {
            outline: none;
            border-color: #007BFF;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            width: 80%;
            font-size: 16px;
            text-align: center;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 15px;
        }
        th {
            background-color: rgb(0, 0, 0);
            font-weight: bold;
            text-align: center;
            color: #fff;
        }

        /* Buttons in table */
        .btn-edit {
            background-color: #007BFF;
            padding: 8px 25px;
            border: none;
            color: white;
            border-radius: 7px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-delete {
            background-color: #FF0000;
            padding: 8px 15px;
            border: none;
            color: white;
            border-radius: 7px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-view {
            background-color: rgb(55, 130, 63);
            padding: 8px 25px;
            border: none;
            color: white;
            border-radius: 7px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-edit:hover,
        .btn-delete:hover,
        .btn-view:hover {
            opacity: 0.8;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 13px;
            text-align: center;
            width: 100px;
            font-weight: bold;
            color: black;
        }
        .status-active {
            background-color: rgb(172, 236, 143);
        }
        .status-inactive {
            background-color: rgb(234, 106, 15);
        }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; 
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 300px; /* Could be more or less, depending on screen size */
            border-radius: 15px;
            text-align: center;
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: #000;
            text-decoration: none;
        }

        .modal-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }

        .modal-actions button {
            width: 100px;
        }

        /* Pagination and Rows per Page */
        .pagination-container {
            width: 80%;
            margin: 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .rows-per-page {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .rows-per-page select {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .pagination {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .pagination button {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .pagination button.active {
            background-color: #007BFF;
            color: #fff;
            border-color: #007BFF;
        }
        .pagination button:hover:not(.active) {
            background-color: #f1f1f1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .slider-container {
                width: 100%;
            }
            .slider-item {
                width: 100px;
            }
            table {
                font-size: 12px;
            }
            .add-link {
                margin-left: 0;
                text-align: center;
            }
            .search-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            .search-bar .filter-group {
                width: 100%;
                justify-content: space-between;
            }
            .search-bar input,
            .search-bar select,
            .search-bar input[type="date"] {
                width: 100%;
            }
            .pagination-container {
                flex-direction: column;
                align-items: flex-start;
            }
            .rows-per-page, .pagination {
                width: 100%;
                justify-content: flex-start;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Show Table</h1>
    <div class="container">
        <!-- Success Message -->
        @if(session()->has('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Add New Show Button -->
        <div class="add-link">
            <a href="{{ route('show.create') }}">
                <img src="icons/icons8-add-24.png" alt="Add" /> Add New Show
            </a>
        </div>
        <table id="movieTable">
            <thead>
            <tr>
               <th>ID</th> 
               <th>Date</th> 
               <th>Time</th>
               <th>movie_code</th> 
               <th>movie_name</th> 
               <th>movie_poster</th>  
               <th>Edit</th>
               <th>Delete</th>
               <th>View</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shows as $show)
                <tr>
                    <td class="movie-name">{{$show->id}}</td> 
                    <td class="movie-name">{{$show->date}}</td>
                    <td class="movie-name">{{$show->time}}</td>
                    <td class="movie-name">{{$show->movie_code}}</td>
                    <td class="movie-name">{{$show->movie_name}}</td>
                    <td>
                        <img 
                            src="{{ $show->poster ? asset('storage/' . $show->poster) : asset('images/default-poster.jpg') }}" 
                            alt="Poster" 
                            style="max-width: 100px; height: auto;" 
                        />
                    </td>
                    <td>
                        <form method="GET" action="{{ route('show.edit', ['show' => $show]) }}">
                            <button type="submit">Edit</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="{{route('show.destroy', ['show' => $show])}}">
                            @csrf
                            @method('delete')
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                    <td>
                        <form method="GET" action="{{ route('show.detail', ['show' => $show]) }}">
                            <button type="submit">View</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal" style="display: none;">
        <div class="modal-content" style="
            background-color: var(--primary-color);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 5px 5px 15px var(--shadow-dark), -5px -5px 15px var(--shadow-light);
            color: var(--text-color);
            text-align: center;
        ">
            <span class="close-button" style="
                color: #ffffff;
                float: right;
                font-size: 24px;
                font-weight: bold;
                cursor: pointer;
            ">&times;</span>
            <p>Are you sure you want to delete this show?</p>
            <div style="margin: 20px 0;">
                <img src="icons/icons8-delete (1).gif" alt="Delete" style="width: 30px; height: 30px;" />
            </div>
            <div class="modal-actions" style="display: flex; justify-content: center; gap: 20px;">
                <button id="confirmDelete" class="action-button btn-delete" style="width: 100px;">Confirm</button>
                <button id="cancelDelete" class="action-button btn-view" style="width: 100px;">Cancel</button>
            </div>
        </div>
    </div>

    <!-- JavaScript for Delete Confirmation Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Modal Elements
            const deleteModal = document.getElementById('deleteModal');
            const closeButton = deleteModal.querySelector('.close-button');
            const cancelDeleteButton = document.getElementById('cancelDelete');
            const confirmDeleteButton = document.getElementById('confirmDelete');
            let formToSubmit = null;

            // Function to open the modal
            function openModal(form) {
                deleteModal.style.display = 'block';
                formToSubmit = form;
            }

            // Function to close the modal
            function closeModal() {
                deleteModal.style.display = 'none';
                formToSubmit = null;
                confirmDeleteButton.disabled = false;
            }

            // Event listener for delete buttons
            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    const form = e.target.closest('form');
                    openModal(form);
                });
            });

            // Event listener for confirm delete
            confirmDeleteButton.addEventListener('click', () => {
                if (formToSubmit) {
                    // Optionally disable the confirm button to prevent multiple clicks
                    confirmDeleteButton.disabled = true;
                    formToSubmit.submit();
                }
            });

            // Event listener for cancel delete
            cancelDeleteButton.addEventListener('click', () => {
                closeModal();
            });

            // Event listener for close button (X)
            closeButton.addEventListener('click', () => {
                closeModal();
            });

            // Close modal when clicking outside the modal content
            window.addEventListener('click', (event) => {
                if (event.target == deleteModal) {
                    closeModal();
                }
            });
        });
    </script>
</body>
</html>
