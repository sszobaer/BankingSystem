<?php
require_once "../model/loan_applications.php";
require_once "../model/users.php";
require_once "../model/accounts.php";
require_once "../model/accepted_loans.php";
session_start();
// ZOBAER AHMED
function validateFirstName()
{
    $firstName = trim($_POST['firstName']);
    if ($firstName === "") {
        echo "First Name is required<br>";
        return false;
    }
    return true;
}

function validateLastName()
{
    $lastName = trim($_POST['lastName']);
    if ($lastName === "") {
        echo "Last Name is required<br>";
        return false;
    }
    return true;
}

function validateMobile()
{
    $mobile = trim($_POST['mobile']);
    if ($mobile === "") {
        echo "Mobile number is required<br>";
        return false;
    }
    if (strlen($mobile) != 11) {
        echo "Mobile number must be exactly 11 digits<br>";
        return false;
    }
    for ($i = 0; $i < 11; $i++) {
        if ($mobile[$i] < '0' || $mobile[$i] > '9') {
            echo "Mobile number must contain digits only<br>";
            return false;
        }
    }
    return true;
}

function validateEmployment()
{
    $employment = trim($_POST['employment']);
    if ($employment === "") {
        echo "Type of employment is required<br>";
        return false;
    }
    if ($employment !== 'salaried' && $employment !== 'self-employed' && $employment !== 'unemployed' && $employment !== 'retired') {
        echo "Invalid employment type selected<br>";
        return false;
    }
    return true;
}

function validateNidPassport()
{
    $nidPassport = trim($_POST['nid-passport']);
    if ($nidPassport === "") {
        echo "NID/Passport number is required<br>";
        return false;
    }
    if (strlen($nidPassport) < 6 || strlen($nidPassport) > 20) {
        echo "NID/Passport number must be 6-20 characters<br>";
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

function validateCurrency()
{
    $currency = trim($_POST['currency']);
    if ($currency === "") {
        echo "Currency is required<br>";
        return false;
    }
    if ($currency !== 'USD' && $currency !== 'EUR' && $currency !== 'BDT') {
        echo "Invalid currency selected<br>";
        return false;
    }
    return true;
}

function validateLoanType()
{
    $loanType = trim($_POST['loan-type']);
    if ($loanType === "") {
        echo "Loan type is required<br>";
        return false;
    }
    if ($loanType !== 'personal' && $loanType !== 'home' && $loanType !== 'auto' && $loanType !== 'business') {
        echo "Invalid loan type selected<br>";
        return false;
    }
    return true;
}

function validateMonthlyIncome()
{
    $monthlyIncome = trim($_POST['monthly-income']);
    if ($monthlyIncome === "") {
        echo "Monthly income is required<br>";
        return false;
    }
    if (!is_numeric($monthlyIncome) || $monthlyIncome <= 0) {
        echo "Monthly income must be a positive number<br>";
        return false;
    }
    return true;
}

function validateLoanAmount()
{
    $loanAmount = trim($_POST['loan-amount']);
    if ($loanAmount === "") {
        echo "Desired loan amount is required<br>";
        return false;
    }
    if (!is_numeric($loanAmount) || $loanAmount <= 0) {
        echo "Loan amount must be a positive number<br>";
        return false;
    }
    return true;
}

function validateTaxSlip()
{
    $taxSlip = trim($_POST['tax-slip']);
    if ($taxSlip === "") {
        echo "Tax slip number is required<br>";
        return false;
    }
    if (strlen($taxSlip) < 4 || strlen($taxSlip) > 20) {
        echo "Tax slip number must be 4-20 characters<br>";
        return false;
    }
    return true;
}

function validateTerms()
{
    if (!isset($_POST['terms'])) {
        echo "You must agree to the terms and conditions<br>";
        return false;
    }
    return true;
}

function loanApplicationController()
{
    return (
        validateFirstName() &&
        validateLastName() &&
        validateMobile() &&
        validateEmployment() &&
        validateNidPassport() &&
        validateEmail() &&
        validateCurrency() &&
        validateLoanType() &&
        validateMonthlyIncome() &&
        validateLoanAmount() &&
        validateTaxSlip() &&
        validateTerms()
    );
}

function pushLoanApplications()
{
    $employment = trim($_POST['employment']);
    $email = trim($_POST['email']);
    $currency = trim($_POST['currency']);
    $loanType = trim($_POST['loan-type']);
    $monthlyIncome = trim($_POST['monthly-income']);
    $loanAmount = trim($_POST['loan-amount']);
    $taxSlip = trim($_POST['tax-slip']);
    $password = $_SESSION['password'];
    $user = [
        'email' => $email,
        'password' => $password
    ];
    $userInfo = fetchUser($user);

    $loan = [
        'loan_id' => NULL,
        'employment_type' => $employment,
        'currency' => $currency,
        'loan_type' => $loanType,
        'monthly_income' => $monthlyIncome,
        'loan_amount' => $loanAmount,
        'acknowledgement_slip_no' => $taxSlip,
        'user_id' => $userInfo['user_id']
    ];

    $status = insertLoanApplications($loan);

    if ($status) {
        return true;
    } else {
        return false;
    }
}

function showLoanApplications()
{
    $loanApplications = fetchAllLoanApplication();
    return $loanApplications;
}

function approveLoanByAdmin(){
    $loanId = $_GET['approveLoan'];
    $loan = [
        'loan_id' => $loanId
    ];
    $loanInfo = fetchRequestedLoanById($loan);
    $user = [
        'user_id' => $loanInfo['user_id']
    ];
    $accountInfo = fetchAccountsByUserId($user);
    $userId = $loanInfo['user_id'];

    $acceptedLoan = [
        'accepted_loan_id' => NULL,
        'loan_id' => $loanId,
        'user_id' => $userId,
        'account_id' => $accountInfo['account_id'],
        'loan_amount' => $loanInfo['loan_amount']
    ];

    $accepted_loan = insertAcceptedLoan($acceptedLoan);

    $account = [
        'loan_amount' => $loanInfo['loan_amount'],
        'account_id' => $accountInfo['account_id']
    ];

    $insert_account = addLoanAmount($account);

    if($accepted_loan && $insert_account){
       header('location: ../view/loanManagement.php');
    } else{
        echo "Something went wrong";
    }

    

    
}
if($_SERVER["REQUEST_METHOD"] === "GET"){
    if(isset($_GET['approveLoan'])){
            approveLoanByAdmin();
        }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (loanApplicationController() && pushLoanApplications()) {
        header("Location: ../view/loanApplication.php");
        exit();
    }
}
