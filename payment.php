<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Information | RR Sarees</title>

<style>

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:radial-gradient(#fff,#ffc66b);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:40px;
}

.container{
    max-width:900px;
    width:100%;
    background:white;
    border-radius:15px;
    padding:40px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
}

h1{
    text-align:center;
    color:#652d35;
    margin-bottom:10px;
}

.thank-you{
    text-align:center;
    line-height:1.8;
    color:#444;
    margin-bottom:40px;
}

.contact-icons{
    display:flex;
    justify-content:center;
    gap:80px;
    margin-bottom:20px;
}

.contact-icons img{
    width:70px;
    height:70px;
}

.contact-links{
    display:flex;
    justify-content:center;
    gap:50px;
    text-align:center;
    flex-wrap:wrap;
}

.contact-links a{
    text-decoration:none;
    color:#652d35;
    font-weight:bold;
}

.account-box{
    background:#f8f8f8;
    padding:20px;
    border-radius:10px;
    margin-top:30px;
    text-align:center;
}

.account-box h2{
    color:#652d35;
}

.back-btn{
    display:block;
    width:fit-content;
    margin:30px auto 0;
    background:#652d35;
    color:white;
    padding:12px 25px;
    border-radius:6px;
    text-decoration:none;
}

.back-btn:hover{
    background:#4d2027;
}

</style>

</head>
<body>

<div class="container">

<h1>Thank You For Shopping With RR Sarees</h1>

<p class="thank-you">

Dear <?php echo htmlspecialchars($_SESSION['firstname']); ?>,

<br><br>

Thank you for choosing RR Sarees. We sincerely appreciate your support and trust in our products.

At present, our website does not yet include a fully automated payment gateway with encrypted online card processing and secure transaction handling. We apologize for this inconvenience.

To complete your order, please contact us using any of the methods below. Our team will respond promptly and guide you through the payment and order confirmation process.

We are continuously working to improve our services and hope to provide a complete online payment solution in the future.

</p>

<div class="account-box">
    <h2>MCB Account Details</h2>

    <p>
        Name: RR Sarees<br>
        Number: +230 5259 8971
    </p>
</div>

<div class="contact-icons">

    <div>
        <img src="assets/facebook.png" alt="Facebook">
    </div>

    <div>
        <img src="assets/whatsapp.png" alt="WhatsApp">
    </div>

    <div>
        <img src="assets/gmail.png" alt="Email">
    </div>

</div>

<div class="contact-links">

    <div>
        <a href="https://www.facebook.com/share/1CARyzEeDX/?mibextid=wwXIfr" target="_blank">
            Facebook Page
        </a>
    </div>

    <div>
        <a href="https://wa.me/23052598971" target="_blank">
            +230 5259 8971
        </a>
    </div>

    <div>
        <a href="mailto:devinasaw@gmail.com">
            devinasaw@gmail.com
        </a>
    </div>

</div>

<a href="catalogue2.php" class="back-btn">
    Continue Shopping
</a>

</div>

</body>
</html>