<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket Price</title>

    <!-- Font Awesome for icons (optional, if you plan to use icons) -->
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
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 30px 0;
        }

        /* Container */
        .container {
            background-color: var(--primary-color);
            padding: 60px;
            border-radius: 15px;
            /* box-shadow: inset 5px 5px 15px var(--shadow-dark), inset -5px -5px 15px var(--shadow-light); */
            width: 90%;
            max-width: 600px;
            max-height: 1000px;
            color: var(--text-color);
        }

        h1 {
            margin-bottom: 30px;
            text-align: center;
            color: var(--text-color);
        }

        /* Success and Error Messages */
        .success-message {
            text-align: center;
            color: var(--success-color);
            margin-bottom: 20px;
            font-size: 18px;
        }

        .error-messages ul {
            color: var(--danger-color);
            list-style-type: none;
            padding: 0;
            margin: 0 0 15px;
        }

        .error-messages ul li {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .error-messages ul li::before {
            content: "\f071"; /* Font Awesome exclamation-circle icon */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            margin-right: 8px;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative; /* For positioning the custom arrow */
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: var(--text-color);
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="file"],
        select {
            width: 100%;
            padding: 10px 15px;
            border: none;
            border-radius: 20px;
            background-color: var(--secondary-color);
            color: var(--text-color);
            box-shadow: inset 0.5px 0.5px 1.5px var(--shadow-dark),
                inset -0.5px -0.5px 1.5px var(--shadow-light);
            font-size: 14px;
            outline: none;
            transition: box-shadow 0.3s, background-color 0.3s;
        }

        input[type="text"]::placeholder,
        input[type="number"]::placeholder,
        input[type="file"]::placeholder,
        select::placeholder {
            color: #aaa;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="file"]:focus,
        select:focus {
            box-shadow: 0 0 10px var(--info-color);
        }

        /* Styled Select */
        select {
            -webkit-appearance: none; /* Remove default arrow in Chrome */
            -moz-appearance: none; /* Remove default arrow in Firefox */
            appearance: none; /* Remove default arrow in IE */
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D'10'%20height%3D'6'%20viewBox%3D'0%200%2010%206'%20xmlns%3D'http%3A//www.w3.org/2000/svg'%3E%3Cpath%20d%3D'M0,0 L5,6 L10,0' fill='%23e0e0e0'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 10px 6px;
            padding-right: 40px; /* Space for the arrow */
        }

        /* Optional: For IE compatibility */
        select::-ms-expand {
            display: none;
        }

        /* Duration Inputs */
        .row {
            display: flex;
            gap: 15px;
        }

        .row input {
            flex: 1;
        }

        /* Submit Button */
        .submit-button {
            background-color: var(--accent-color);
            color: #fff;
            border: none;
            border-radius: 30px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            /* box-shadow: 5px 5px 15px var(--shadow-dark), -5px -5px 15px var(--shadow-light); */
            transition: box-shadow 0.3s, background-color 0.3s, color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .submit-button:hover {
            background-color: var(--button-hover-color);
            box-shadow: inset 2px 2px 5px var(--shadow-dark),
                inset -2px -2px 5px var(--shadow-light);
            color: #000;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .row {
                flex-direction: column;
            }
        }

        /* Optional: Add New Movie Link (if navigating from another page) */
        .add-link {
            text-align: center;
            margin-top: 20px;
        }

        .add-link a {
            display: inline-flex;
            align-items: center;
            padding: 12px 20px;
            background-color: var(--button-color);
            color: var(--text-color);
            text-decoration: none;
            border-radius: 30px;
            box-shadow: 5px 5px 15px var(--shadow-dark),
                -5px -5px 15px var(--shadow-light);
            transition: box-shadow 0.3s, background-color 0.3s, color 0.3s;
            cursor: pointer;
            font-weight: bold;
        }

        .add-link a:hover {
            box-shadow: inset 2px 2px 5px var(--shadow-dark),
                inset -2px -2px 5px var(--shadow-light);
            background-color: var(--button-hover-color);
            color: #fff;
        }

        .add-link a img {
            margin-right: 10px;
            filter: brightness(0) invert(1);
            /* Invert icon colors for visibility */
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Create seat</h1>

        <!-- Success Message -->
        @if(session()->has('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('seat.store') }}" onsubmit="return updateHiddenFields()" enctype="multipart/form-data">
            @csrf
            @method('post')  
            <input type="hidden" name="seat_code" id="seat_code" />  
            <input type="hidden" name="seat_no" id="seat_no" />

            <div class="form-group">
                <label for="seat_type">Seat Type</label>
                <select name="seat_type" id="seat_type" onchange="updateHiddenFields()" required aria-label="Seat Type">
                    <option value="" disabled selected>Select seat type</option>
                    <option value="Silver">Silver</option>
                    <option value="Gold">Gold</option>
                    <option value="Platinum">Platinum</option>
                </select>
            </div>

            <div class="form-group">
                <label for="seat_letter">Seat Letter</label>
                <input  
                    type="text" 
                    name="seat_letter" 
                    id="seat_letter" 
                    placeholder="Enter Seat Letter" 
                    oninput="updateHiddenFields()" 
                    required 
                    aria-label="Seat Letter"
                />
            </div>

            <div class="form-group">
                <label for="seat_digit">Seat Digit</label>
                <input 
                    type="number" 
                    name="seat_digit" 
                    id="seat_digit" 
                    placeholder="Enter Seat Digit" 
                    oninput="updateHiddenFields()" 
                    required 
                    aria-label="Seat Digit"
                />
            </div>

            <div class="form-group">
                <input type="submit" value="Save New Seat"/>
            </div>
        </form>
    </div>

    <script>
        function updateHiddenFields() {
            // Get input values
            const seatType = document.getElementById('seat_type').value;
            const seatLetter = document.getElementById('seat_letter').value.toUpperCase();
            const seatDigit = document.getElementById('seat_digit').value;

            // Validate inputs
            if (!seatType || !seatLetter || !seatDigit) {
                // Optionally, you can display a message or handle incomplete inputs
                return false; // Prevent form submission if needed
            }

            // Map seat type to first letter
            const seatTypeMap = {
                'Silver': 'S',
                'Gold': 'G',
                'Platinum': 'P'
            };
            const seatTypeInitial = seatTypeMap[seatType] || '';

            // Construct seat_no and seat_code
            const seatNo = `${seatLetter}-${seatDigit}`;
            const seatCode = `${seatTypeInitial}-${seatLetter}-${seatDigit}`;

            // Update hidden fields
            document.getElementById('seat_no').value = seatNo;
            document.getElementById('seat_code').value = seatCode;

            return true; // Allow form submission
        }
    </script>
</body>
</html>
