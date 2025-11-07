<!DOCTYPE html>
<html>
<head>
    <title>Clinic Patient Registration</title>

    <style>

        button[type="submit"] {
            background-color: #076d15ff;
            color: white;
        }
    </style>
</head>

<body>
    <center>
        <h2><b>Clinic Patient Registration</b></h2>

        <table>
            <tr>
                <td>Full Name:<br>
                    <input type="text">
                </td>
            </tr>

            <tr>
                <td>Age:<br>
                    <input type="number">
                </td>
            </tr>

            <tr>
                <td>Phone Number:<br>
                    <input type="number">
                </td>
            </tr>

            <tr>
                <td>Email Address:<br>
                    <input type="text">
                </td>
            </tr>

            <tr>
                <td>Insurance Provider:<br>
                    <select>
                        <option>Select Provider</option>
                        <option>MetLife</option>
                        <option>Delta Life Insurance Co Ltd</option>
                        <option>Jiban Bima Corporation</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Insurance Policy Number:<br>
                    <input type="number">
                </td>
            </tr>

            <tr>
                <td>
                    <h1><b>Additional Information</b></h1>
                </td>
            </tr>

            <tr>
                <td>Username:<br>
                    <input type="text">
                </td>
            </tr>

            <tr>
                <td>Password:<br>
                    <input type="password">
                </td>
            </tr>

            <tr>
                <td>Confirm Password:<br>
                    <input type="password">
                </td>
            </tr>
        </table>

        <button type="submit">Register</button>

    </center>
</body>
</html>