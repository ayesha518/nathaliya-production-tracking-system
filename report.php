<?php
session_start();
include(__DIR__ . '/config/db.php');

if(!isset($conn)){
    die("DB Connection Failed");
}

/* FILTER VALUES (SAFE) */
$from = $_GET['from'] ?? '';
$to   = $_GET['to'] ?? '';
$site = $_GET['site'] ?? '';
$buyer = $_GET['buyer'] ?? '';
$style = $_GET['style'] ?? '';
$color = $_GET['color'] ?? '';
$size  = $_GET['size'] ?? '';
$product = $_GET['product'] ?? '';

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Stock Report</title>

<style>
body{
    font-family:Arial;
    margin:0;
    background:#f4f6f9;
}

.navbar{
    background:#111827;
    color:#fff;
    padding:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.navbar a{
    color:#fff;
    text-decoration:none;
    padding:8px 15px;
    border-radius:5px;
}

.btn-dashboard{background:#16a34a;}
.btn-exit{background:#dc2626;}

.container{
    width:98%;
    margin:auto;
    margin-top:20px;
}

.filter-box{
    background:#fff;
    padding:15px;
    border-radius:10px;
    margin-bottom:15px;
}

input,select{
    padding:8px;
    margin:5px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
}

th,td{
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}

th{
    background:#111827;
    color:#fff;
}

.btn{
    padding:10px 15px;
    border:none;
    cursor:pointer;
    border-radius:5px;
}

.print{background:#2563eb;color:#fff;}
.pdf{background:#ef4444;color:#fff;}
.excel{background:#16a34a;color:#fff;}
.word{background:#7c3aed;color:#fff;}
</style>

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <h3>📊 STOCK REPORT SYSTEM</h3>

    <div>
        <a href="dashboard.php" class="btn-dashboard">Dashboard</a>
        <a href="logout.php" class="btn-exit">Exit</a>
    </div>
</div>

<div class="container">

<!-- FILTER -->
<div class="filter-box">

<form method="GET">

<input type="date" name="from" value="<?php echo $from; ?>">
<input type="date" name="to" value="<?php echo $to; ?>">

<select name="site">
    <option value="">Site</option>
    <option value="Fabric">Fabric</option>
    <option value="Sewing">Sewing</option>
    <option value="Packing">Packing</option>
</select>

<input type="text" name="buyer" placeholder="
<input type="text" name="style" placeholder="Style">
<input type="text" name="color" placeholder="Color">
<input type="text" name="size" placeholder="Size">
<input type="text" name="product" placeholder="Product">

<button class="btn print" name="filter">SEARCH</button>

</form>
</div>

<?php

$sql = "SELECT * FROM stock_in WHERE 1=1";

if($from && $to){
    $sql .= " AND date BETWEEN '$from' AND '$to'";
}
if($site){
    $sql .= " AND site='$site'";
}
if($buyer){
    $sql .= " AND buyer LIKE '%$buyer%'";
}
if($style){
    $sql .= " AND style LIKE '%$style%'";
}
if($color){
    $sql .= " AND color LIKE '%$color%'";
}
if($size){
    $sql .= " AND size LIKE '%$size%'";
}
if($product){
    $sql .= " AND product LIKE '%$product%'";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn,$sql);

?>

<!-- EXPORT BUTTONS -->
<div style="margin-bottom:10px;">

<button class="btn print" onclick="window.print()">PRINT</button>

<button class="btn pdf" onclick="alert('PDF export will add next step')">PDF</button>

<button class="btn excel" onclick="exportTable('excel')">EXCEL</button>

<button class="btn word" onclick="exportTable('word')">WORD</button>

</div>

<!-- TABLE -->
<table id="reportTable">

<tr>
<th>Invoice</th>
<th>Date</th>
<th>Site</th>
<th>Buyer</th>
<th>Style</th>
<th>Product</th>
<th>Color</th>
<th>Size</th>
<th>Qty</th>
<th>Supplier</th>
<th>Remark</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
<td><?= $row['invoice_no'] ?? '-' ?></td>
<td><?= $row['date'] ?? '-' ?></td>
<td><?= $row['site'] ?? '-' ?></td>
<td><?= $row['buyer'] ?? '-' ?></td>
<td><?= $row['style'] ?? '-' ?></td>
<td><?= $row['product'] ?? '-' ?></td>
<td><?= $row['color'] ?? '-' ?></td>
<td><?= $row['size'] ?? '-' ?></td>
<td><?= $row['qty'] ?? '-' ?></td>
<td><?= $row['supplier'] ?? '-' ?></td>
<td><?= $row['remark'] ?? '-' ?></td>
</tr>

<?php } ?>

</table>

</div>

<!-- EXPORT SCRIPT -->
<script>

function exportTable(type)
{
    let table = document.getElementById("reportTable").outerHTML;
    let data = '<html><head><meta charset="utf-8"></head><body>' + table + '</body></html>';

    let file = new Blob([data], {type: "application/vnd.ms-excel"});

    let a = document.createElement("a");

    a.href = URL.createObjectURL(file);

    if(type == "excel"){
        a.download = "stock_report.xls";
    }
    else if(type == "word"){
        a.download = "stock_report.doc";
    }

    a.click();
}

</script>

</body>
</html>
