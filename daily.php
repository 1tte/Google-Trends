<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Trends</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .typewriter {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border-right: 3px solid #000;
            animation: typing 3.5s steps(40, end), blink-caret 0.75s step-end infinite;
        }

        @keyframes typing {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }

        @keyframes blink-caret {
            from, to {
                border-color: transparent;
            }
            50% {
                border-color: black;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Google Trends</h1>
        <form id="trendForm">
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" class="form-control" name="date" required value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <select id="country" class="form-control" name="country" required>
                    <option value="JP">Japan</option>
                    <option value="ID">Indonesia</option>
                    <option value="MY">Malaysia</option>
                    <option value="IN">India</option>
                    <option value="US">United States</option>
                    <option value="GB">United Kingdom</option>
                    <option value="CA">Canada</option>
                    <option value="AU">Australia</option>
                    <option value="BR">Brazil</option>
                    <option value="FR">France</option>
                    <option value="DE">Germany</option>
                    <option value="IT">Italy</option>
                    <option value="ES">Spain</option>
                    <option value="NL">Netherlands</option>
                    <option value="RU">Russia</option>
                    <option value="ZA">South Africa</option>
                    <option value="KR">South Korea</option>
                    <option value="SG">Singapore</option>
                    <option value="MX">Mexico</option>
                    <option value="AR">Argentina</option>
                    <option value="CL">Chile</option>
                    <option value="CO">Colombia</option>
                    <option value="PE">Peru</option>
                    <option value="EG">Egypt</option>
                    <option value="TR">Turkey</option>
                    <option value="SA">Saudi Arabia</option>
                    <option value="AE">United Arab Emirates</option>
                    <option value="NZ">New Zealand</option>
                    <option value="IE">Ireland</option>
                    <option value="PT">Portugal</option>
                    <option value="SE">Sweden</option>
                    <option value="NO">Norway</option>
                    <option value="DK">Denmark</option>
                    <option value="FI">Finland</option>
                    <option value="PL">Poland</option>
                    <option value="GR">Greece</option>
                    <option value="HU">Hungary</option>
                    <option value="CZ">Czech Republic</option>
                    <option value="RO">Romania</option>
                    <option value="BG">Bulgaria</option>
                    <option value="LT">Lithuania</option>
                    <option value="LV">Latvia</option>
                    <option value="EE">Estonia</option>
                    <option value="UA">Ukraine</option>
                    <option value="BY">Belarus</option>
                    <option value="KZ">Kazakhstan</option>
                    <option value="UZ">Uzbekistan</option>
                    <option value="PK">Pakistan</option>
                    <option value="BD">Bangladesh</option>
                    <option value="LK">Sri Lanka</option>
                    <option value="NP">Nepal</option>
                    <option value="MV">Maldives</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" id="fetch-btn">Fetch Trends</button>
            <button type="button" class="btn btn-success" id="save-btn">Save</button>
        </form>

        <div id="result" class="mt-4"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#trendForm').on('submit', function(e) {
                e.preventDefault();

                const date = $('#date').val();
                const country = $('#country').val();

                $('#typing-effect').text('Fetching trends...'); // Start typing effect

                $.ajax({
                    url: 'trends_daily.php',
                    type: 'POST',
                    data: { date: date, country: country },
                    success: function(response) {
                        // Display the result
                        $('#result').html('<pre>' + response + '</pre>');
                        $('#typing-effect').text(''); // Stop typing effect
                    },
                    error: function() {
                        $('#result').html('<div class="alert alert-danger">An error occurred while fetching trends.</div>');
                        $('#typing-effect').text(''); // Stop typing effect
                    }
                });
            });

            $('#save-btn').on('click', function() {
                const date = $('#date').val();
                const country = $('#country').val();

                $.ajax({
                    url: 'trends_daily.php',
                    type: 'POST',
                    data: { date: date, country: country, save: 'true' },
                    success: function(response) {
                        $('#result').html('<div class="alert alert-success">' + response + '</div>');
                    },
                    error: function() {
                        $('#result').html('<div class="alert alert-danger">An error occurred while saving trends.</div>');
                    }
                });
            });
        });
    </script>
</body>

</html>
