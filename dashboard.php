<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

include('config/db.php');

/* TOTAL PRODUCTS */
$p = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total FROM products
"));

/* STOCK IN TOTAL */
$si = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(qty),0) AS total 
FROM stock_in_details
"));

/* STOCK OUT TOTAL */
$so = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT IFNULL(SUM(qty),0) AS total 
FROM stock_out_details
"));

/* BALANCE STOCK */
$balance = $si['total'] - $so['total'];

/* LOW STOCK ALERT */
$l = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM products
WHERE reorder_level > 0 AND reorder_level <= 10
"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<style>

/* RESET */
body{
    margin:0;
    font-family:Arial;
    background:#eef2f7;
}

/* WRAPPER */
.wrapper{
    display:flex;
}

/* SIDEBAR */
.sidebar{
    width:240px;
    height:100vh;
    background:#0b1220;
    position:fixed;
    left:0;
    top:0;
    padding:20px;
    color:white;
}

/* LOGO AREA (BIG + CENTER FIX) */
.logo-area{
    text-align:center;
    padding:20px 10px;
    margin-bottom:20px;
    border-bottom:1px solid #1f2937;
}

/* BIG LOGO */
.logo-area img{
    width:140px;   /* 🔥 BIG SIZE */
    height:140px;  /* 🔥 BIG SIZE */
    border-radius:50%;
    object-fit:cover;
    border:4px solid #2563eb;
    background:white;
    padding:5px;
    box-shadow:0 5px 15px rgba(0,0,0,0.4);
}

/* MAIN TITLE UNDER LOGO */
.logo-area h2{
    margin-top:12px;
    font-size:18px;
    color:white;
}

/* MENU */
.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:12px;
    margin-bottom:8px;
    border-radius:8px;
    background:#1f2937;
    transition:0.3s;
}

/* HOVER */
.sidebar a:hover{
    background:#2563eb;
    transform:translateX(5px);
}

/* MAIN */
.main{
    margin-left:240px;
    width:100%;
}

/* TOP BAR */
.topbar{
    background:#111827;
    color:white;
    padding:15px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    position:sticky;
    top:0;
    z-index:1000;
}

/* LOGOUT */
.logout{
    background:#ef4444;
    color:white;
    padding:8px 14px;
    text-decoration:none;
    border-radius:6px;
}

.logout:hover{
    background:#dc2626;
}

/* CONTENT */
.content{
    padding:25px;
}

/* CARD GRID */
.card-container{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

/* CARD */
.card{
    background:white;
    padding:25px;
    border-radius:14px;
    text-align:center;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
    transition:0.3s;
}

/* CARD HOVER */
.card:hover{
    transform:translateY(-8px);
}

/* COLORS */
.blue{ background:#e0f2fe; }
.green{ background:#dcfce7; }
.red{ background:#fee2e2; }

/* TEXT */
.card h3{
    font-size:15px;
    color:#333;
}

.card p{
    font-size:32px;
    font-weight:bold;
    color:#111;
}

/* ACTIVE MENU */
.sidebar a.active{
    background:#2563eb;
}
</style>

</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">

       

        <a href="dashboard.php" class="active">📊 Dashboard</a>
        <a href="products/product_dashbord.php">📦 Products</a>
        <a href="stock/stock_in.php">⬆ Stock In</a>
        <a href="stock/stock_out.php">⬇ Stock Out</a>
        <a href="stock/search.php">📑 Serch details</a>
        <a href="report.php">📑 Report</a>
    </div>

    <!-- MAIN -->
    <div class="main">

        <div class="topbar">
            <h3>Dashboard Overview</h3>
            <a href="logout.php" class="logout" onclick="return confirm('Are you sure logout?')">Logout</a>
        </div>

        <div class="content">

            <div class="card-container">

                <div class="card">
                    <h3>📦 Total Products</h3>
                    <p><?php echo $p['total']; ?></p>
                </div>             
                <div class="card blue">
                    <h3>⬆ Total Stock In</h3>
                    <p><?php echo $si['total']; ?></p>
                </div>
                <div class="card blue">
                    <h3>⬇ Total Stock Out</h3>
                    <p><?php echo $so['total']; ?></p>
                </div>
                <div class="card green">
                    <h3>💰 Current Balance</h3>
                    <p><?php echo $balance; ?></p>
                </div>
                <div class="card red">
                    <h3>⚠ Low Stock Alert</h3>
                    <p><?php echo $l['total']; ?></p>
                </div>
                
            </div>

        </div>

    </div>

</div>

</body>
</html>