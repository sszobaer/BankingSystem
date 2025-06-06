<?php
session_start();
//ZOBAER AHMED
require_once "../model/deposits.php";
require_once "../model/users.php";
require_once "../model/accounts.php";

function depositTypeValidation()
{
    $depositeType = trim($_POST["depositType"]);
    if (empty($depositeType)) {
        echo "Please select a deposit type.<br>";
        return false;
    }
    return true;
}
function tenureValidation()
{
    $depositType = isset($_POST["tenure"]);
    $fixedDeposit = $_POST["depositType"];
    $tenure = trim($_POST["tenure"]);
    if ($depositType && $fixedDeposit === "fixed") {
        if (empty($tenure)) {
            echo "Please select a tenure for fixed deposit.<br>";
            return false;
        }
    }
    return true;
}
function frequencyValidation()
{
    if (isset($_POST["depositType"]) && $_POST["depositType"] === "recurring") {
        if (empty($_POST["frequency"])) {
            echo "Please select a deposit frequency.<br>";
            return false;
        }
    }
    return true;
}
function depositMethodValidation()
{
    if (empty($_POST["depositMethod"])) {
        echo "Please select a deposit method.<br>";
        return false;
    }
    return true;
}
function currencyValidation()
{
    if (empty($_POST["currency"])) {
        echo "Please select a currency.<br>";
        return false;
    }
    return true;
}
function amountvalidation(){
    if (!isset($_POST["amount"]) || !isset($_POST["currency"])) {
        echo "Amount or currency field is missing.<br>";
        return false;
    }

    $amount = trim($_POST["amount"]);
    $currency = $_POST["currency"];

    if (empty($amount)) {
        echo "Please enter an amount.<br>";
        return false;
    }

    if (empty($currency)) {
        echo "Please select a currency.<br>";
        return false;
    }

    $decimalCount = 0;
    for ($i = 0; $i < strlen($amount); $i++) {
        if ($amount[$i] === ".") {
            $decimalCount++;
            if ($decimalCount > 1) {
                echo "Amount must be a valid decimal number.<br>";
                return false;
            }
        } elseif ($amount[$i] < "0" || $amount[$i] > "9") {
            if ($amount[$i] !== ".") {
                echo "Amount must be a valid number.<br>";
                return false;
            }
        }
    }

    $amountValue = floatval($amount);

    if ($amountValue <= 0) {
        echo "Amount must be a positive number.<br>";
        return false;
    }

    if (isset($_POST["depositType"])) {
        $depositType = $_POST["depositType"];

        if ($depositType === "fixed") {
            if (
                ($currency === "USD" && $amountValue < 1000) ||
                ($currency === "EUR" && $amountValue < 900) ||
                ($currency === "BDT" && $amountValue < 100000)
            ) {
                echo "Fixed deposit requires a minimum of $1000 (USD), €900 (EUR), or ৳100,000 (BDT).<br>";
                return false;
            }
        } elseif ($depositType === "recurring") {
            if (
                ($currency === "USD" && $amountValue > 5000) ||
                ($currency === "EUR" && $amountValue > 4500) ||
                ($currency === "BDT" && $amountValue > 500000)
            ) {
                echo "Recurring deposit limit: max $5000 (USD), €4500 (EUR), or ৳500,000 (BDT).<br>";
                return false;
            }
        }
    }

    if (
        ($currency === "USD" && $amountValue > 10000) ||
        ($currency === "EUR" && $amountValue > 9000) ||
        ($currency === "BDT" && $amountValue > 1000000)
    ) {
        echo "Amount cannot exceed $10,000 (USD), €9,000 (EUR), or ৳1,000,000 (BDT).<br>";
        return false;
    }

    return true;
}

function memoValidation()
{
    if (!empty($_POST["memo"])) {
        $memo = trim($_POST["memo"]);
        if (strlen($memo) > 50) {
            echo "Memo cannot exceed 50 characters.<br>";
            return false;
        }
    }
    return true;
}
function termsValidation()
{
    if (!isset($_POST["terms"])) {
        echo "You must agree to the terms and conditions.<br>";
        return false;
    }
    return true;
}

function depositController()
{
    return (
        (
            depositTypeValidation() &&
            tenureValidation() &&
            currencyValidation() &&
            amountValidation() &&
            memoValidation() &&
            termsValidation()
        ) ||
        (
            depositTypeValidation() &&
            frequencyValidation() &&
            currencyValidation() &&
            amountValidation() &&
            memoValidation() &&
            termsValidation()
        ) ||
        (
            depositTypeValidation() &&
            depositMethodValidation() &&
            currencyValidation() &&
            amountValidation() &&
            memoValidation() &&
            termsValidation()
        )

    );
}

function handleDeposits(){
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
    $user = [
        'email' => $email,
        'password' => $password
    ];
    $userInfo = fetchUser($user);

    // print_r($userInfo);
    // echo "<br><br>";
    
    $deposit = [
        'user_id' => $userInfo['user_id']
    ];
    
    // print_r($deposit);
    
    $accountInfo = fetchAccountsByUserId($deposit);

    // echo "<br><br>";
    // print_r($accountInfo);
    $deposit_amount = trim($_POST['amount']);

    $account_Id = $accountInfo['account_id'];
    $account = [
        'account_id' => $account_Id,
        'deposit_amount'=> $deposit_amount
    ];
    $user = [
        'user_id' => $userInfo['user_id'],
        'deposit_amount' => $deposit_amount
    ];
    $status_for_users = additionFromAccountBalanceInUsers($user);
    $status_for_accounts = additionFromAccountBalanceInAccount($account);

    // echo "<br><br>";
    // print_r($status_for_accounts);
    // echo "<br><br>";
    // print_r($status_for_users);

    if($status_for_accounts && $status_for_users){
        return true;
    } else {
        echo "Error updating account balance.";
        return false;
    }
}

function pushDepositData()
{
    $depositType = trim($_POST["depositType"]);
    $depositMethod = trim($_POST["depositMethod"]);
    $currency = trim($_POST["currency"]);
    $amount = floatval(trim($_POST["amount"]));
    $memo = isset($_POST["memo"]) ? trim($_POST["memo"]) : null;

    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
    $user = [
        'email' => $email,
        'password' => $password
    ];
    $userInfo = fetchUser($user);

    $account = [
        'user_id' => $userInfo['user_id']
    ];
    $accountInfo = fetchAccountsByUserId($account);

    $deposit = [
        'account_no' => $accountInfo['account_number'],
        'deposit_type' => $depositType,
        'deposit_method' => $depositMethod,
        'currency' => $currency,
        'amount_per_deposit' => $amount,
        'deposit_amount' => $amount,
        'memo' => $memo,
        'user_id' => $userInfo['user_id'],
        'account_id' => $accountInfo['account_id'],
        'tenure' => isset($_POST["tenure"]) ? trim($_POST["tenure"]) : null,
        'frequency' => isset($_POST["frequency"]) ? trim($_POST["frequency"]) : null
    ];

    //Call the pre func to add the amount in account and users table
    if(!handleDeposits()){
        return false;
    }

    $status = insertDeposit($deposit);

    if ($status) {
        return true;
    } else {
        echo "Error inserting deposit: " . mysqli_error(getConnection());
        return false;
    }
}

function getDepositById() {
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];

    $user = [
        'email' => $email,
        'password' => $password
    ];

    $userInfo = fetchUser($user);

    if (!$userInfo || !isset($userInfo['user_id'])) {
        echo "User not found.";
        return;
    }

    $deposit = [
        'user_id' => $userInfo['user_id']
    ];

    $status = fetchDepositById($deposit);

    if ($status) {
        // print_r($status['total_deposit']);
        return json_encode($status);
    } else {
        echo "No deposit found.";
    }
}
if($_SERVER["REQUEST_METHOD"] === "GET"){
    echo getDepositById();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (depositController() && pushDepositData()) {
        header("location:../view/userDashboard.php");
    }
    
}
