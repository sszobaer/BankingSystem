<?php
//ZOBAER AHMED
require_once "../Model/users.php";
function validateName()
{
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    if ($firstName === "" || $lastName === "") {
        echo "First and Last Name are required<br>";
        return false;
    }
    return true;
}

function validateEmail()
{
    $email = trim($_POST['email']);
    $atPosition = strpos($email, '@');
    $dotPosition = strrpos($email, '.');
    if ($email === "") {
        echo "Email is required<br>";
        return false;
    } else if (strpos($email, '@') === false || strpos($email, '.') === false) {
        echo "Email must contain @ and .<br>";
        return false;
    } else if ($atPosition < 1 || $dotPosition < $atPosition + 2 || $dotPosition + 1 >= strlen($email)) {
        echo "Invalid email format<br>";
        return false;
    }
    return true;
}

function validatePhone()
{
    $phone = trim($_POST['phone']);
    if ($phone == "") {
        echo "Phone number is required";
        return false;
    } else if (strlen($phone) != 11) {
        echo "Phone number must be exactly 11 digits";
        return false;
    }
    for ($i = 0; $i < strlen($phone); $i++) {
        if ($phone[$i] < "0" || $phone[$i] > "9") {
            echo "Phone number must contain digits only";
            return false;
            break;
        }
    }
    return true;
}

function validateDateofBirth()
{
    $dateOfBirth = trim($_POST['dob']);
    if ($dateOfBirth == "") {
        echo "Date of Birth is required<br>";
        return false;
    }
    return true;
}

function validateGender()
{
    if (!isset($_POST['gender']) || trim($_POST['gender']) == "") {
        echo "Gender is required<br>";
        return false;
    }
    return true;
}

function validateInitialDeposit()
{
    $initialDeposit = trim($_POST['initialDeposit']);
    if ($initialDeposit == "") {
        echo "Initial Deposit is required<br>";
        return false;
    } else if (!is_numeric($initialDeposit) || $initialDeposit < 0) {
        echo "Initial Deposit must be a valid number<br>";
        return false;
    }
    return true;
}
function validateNid()
{
    $nid = trim($_POST['nidNumber']);

    if (empty($nid)) {
        echo "Please enter your nid/passport number.";
        return false;
    } elseif (strlen($nid) !== 10) {
        echo "NID / Passport number must be 10 digits.";
        return false;
    } elseif (!ctype_digit($nid)) {
        echo "NID / Passport number must contain only digits.";
        return false;
    }
    return true;
}
function validatePresentAddress()
{
    $presentAddress = trim($_POST['presentAddress']);

    if ($presentAddress === "") {
        echo "Present address is required.";
        return false;
    }
    return true;
}
function validatePermanentAddress()
{
    $permanentAddress = trim($_POST['permanentAddress']);

    if ($permanentAddress === "") {
        echo "Permanent address is required.";
        return false;
    }
    return true;
}


function registrationController()
{
    return (
        validateName() &&
        validateEmail() &&
        validatePhone() &&
        validateDateofBirth() &&
        validateGender() &&
        validateNid() &&
        validatePresentAddress() &&
        validatePermanentAddress()
    );
}

function updateUserController() {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $nidNumber = trim($_POST['nidNumber']);
    $presentAddress = trim($_POST['presentAddress']);
    $permanentAddress = trim($_POST['permanentAddress']);
    $updatedAt = date("Y-m-d H:i:s");

    $user = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'phoneNo' => $phone,
        'dob' => $dob,
        'gender' => $gender,
        'nid/passport' => $nidNumber,
        'presentAddress' => $presentAddress,
        'permanentAddress' => $permanentAddress,
        'updatedAt' => $updatedAt
    ];

    $status = updateUser($user); 
    if($status) {
        return true;
    } else {
        echo "Failed to update user information<br>";
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (registrationController() && updateUserController()) {
        header('Location: ../view/login.php');
        exit();
    } else {
        echo "Invalid input<br>";
    }
}
?>
