<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Budget / Expense Tracker — Dashboard</title>
<style>
* { 
    margin: 0; 
    padding: 0; 
    box-sizing: border-box; 
    font-family: Arial, sans-serif;
}
body { 
    background: #F7F2FF;
    display: flex; 
    justify-content: center; 
    align-items: flex-start; 
    padding: 30px; 
}
.app {
    width: 100%;
    max-width: 900px;
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
}
.nav {
    width: 100%;
    background: white;
    border-radius: 10px;
    padding: 12px 18px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}
.nav .brand { 
    font-weight: 700; 
    color: #6A4C93; 
    font-size: 18px; 
}
.nav .links a {
    margin-left: 14px;
    text-decoration: none;
    color: #6A4C93;
    font-weight: 600;
}
.container {
    width: 100%;
    gap: 25px;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.06);
}
h2 { 
    text-align: left; 
    margin-bottom: 14px; 
    color:#2b2b2b; 
    font-size: 22px; 
    font-family: Verdana, Geneva, sans-serif; 
}
.grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 20px;
}
.card {
    background: #fff;
    border-radius: 12px;
    padding: 18px;
    border: 1px solid #e6e6e6;
    box-shadow: 0 6px 18px rgba(0,0,0,0.02);
}
.balance-box {
    background: linear-gradient(135deg,#6A4C93,#D9B4F5);
    color: white;
    padding: 18px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 16px;
}
.balance-box h3 { 
    font-size: 14px;
    margin-bottom: 6px; 
    opacity: 0.95;
}
.balance-box p {
    font-size: 26px; 
    font-weight: 700;
    margin: 0; 
}
form {
    margin-top: 6px;
}
label {
    display: block; 
    margin: 10px 0 6px; 
    font-weight: 700; 
    color:#333; 
    font-size:14px;
}
input, select, button {
    width: 100%;
    padding: 10px; 
    border-radius: 8px; 
    border: 1px solid #d6e0ee; 
    font-size: 14px;
    margin-top: 12px;
}
input:focus, select:focus {
    outline: none; 
    border-color: #6A4C93; 
    box-shadow: 0 0 0 4px rgba(106,76,147,0.08); 
}
button {
    margin-top: 12px;
    padding: 11px; 
    background: #6A4C93; 
    color: white;
    border: none; 
    border-radius: 8px; 
    cursor:pointer; 
    font-weight:700;
}
button:hover { 
    background: #5b3a7c; 
}
.muted { 
    color: #666; 
    font-size: 13px; 
    text-align: center; 
    padding: 12px 0; 
}
.summary {
    display:flex;
    flex-direction:column;
    gap:12px;
}
.summary .box {
    background:#fff; 
    border-radius:10px; 
    padding:12px; 
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    text-align:center;
}
.summary .box h4 { 
    margin-bottom:6px; 
    font-size:13px; 
    color:#444; 
}
.summary .box p { 
    font-size:18px; 
    font-weight:700; 
    margin:0; 
}
.summary .box a { 
    color: #6A4C93; 
    text-decoration: none;
    font-weight: 700;
}
@media (max-width: 880px) {
    .grid { grid-template-columns: 1fr; }
    .nav { flex-direction: column; gap:10px; align-items:flex-start; }
    .nav .links { display:flex; flex-wrap:wrap; }
}
</style>
</head>
<body>
<div class="app">
    <div class="nav">
        <div class="brand">Track smarter & Spend better With Trackexpa!</div>
        <div class="links">
            <a href="logout.php" style="float:right; margin:10px;">Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Dashboard</h2>
        <div class="grid">
            <div class="card">
                <div class="balance-box">
                    <h3>Total Balance</h3>
                    <p id="balanceValue">₹0.00</p>
                </div>

                <form id="entryForm" onsubmit="return false;">
                    <label for="titleSelect">Expense Title</label>
                    <select id="titleSelect" name="titleSelect">
                        <option value="Groceries">Groceries</option>
                        <option value="Shopping">Shopping</option>
                        <option value="House Tax">House Tax</option>
                        <option value="Electricity Bill">Electricity Bill</option>
                        <option value="Water Bill">Water Bill</option>
                        <option value="Network Recharge">Network Recharge</option>
                        <option value="Fuel">Fuel</option>
                        <option value="Medical">Medical</option>
                        <option value="Entertainment">Entertainment</option>
                        <option value="Dining Out">Dining Out</option>
                        <option value="Subscriptions">Subscriptions</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Education">Education</option>
                        <option value="Travel">Travel</option>
                        <option value="Other">Other</option>
                    </select>
                    <input type="text" id="customTitle" name="customTitle" placeholder="Enter custom expense" style="display:none; margin-top:10px;" />

                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" placeholder="e.g., 500" step="0.01" />

                    <label for="date">Date</label>
                    <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" />

                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>

                    <button type="button" id="addBtn">Add Entry</button>
                </form>
            </div>

            <aside class="summary">
                <div class="box">
                    <h4>Quick Actions</h4>
                    <p><a href="history.html">View History</a></p>
                </div>

                <div class="box">
                    <h4>View Charts</h4>
                    <p><a href="piechart.html">Open Pie Chart</a></p>
                </div>
            </aside>
        </div>
        <div class="muted" id="dashboardStatus">TrackExpa at your service ;)</div>
    </div>
</div>

<script>
const titleSelect = document.getElementById('titleSelect');
const customTitle = document.getElementById('customTitle');
titleSelect.addEventListener('change', function() {
    if (this.value === 'Other') {
        customTitle.style.display = 'block';
        customTitle.focus();
    } else {
        customTitle.style.display = 'none';
        customTitle.value = '';
    }
});

document.getElementById("addBtn").addEventListener("click", function () {
    let title = (titleSelect.value === 'Other') ? customTitle.value.trim() : titleSelect.value;
    const amountEl = document.getElementById("amount");
    const typeEl = document.getElementById("type");
    const dateEl = document.getElementById("date"); // <-- ADDED: Get date element

    const amount = amountEl.value.trim();
    const type = typeEl.value;
    const entryDate = dateEl.value; // <-- ADDED: Get date value

    if (!title || !amount || !entryDate) { // <-- UPDATED VALIDATION
        alert("Please fill out all fields (Title, Amount, and Date)");
        return;
    }

    const formData = new FormData();
    formData.append("title", title);
    formData.append("amount", amount);
    formData.append("type", type);
    formData.append("entry_date", entryDate); // <-- ADDED: Append entry_date

    fetch("expenses.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data && data.status === "success") {
            // Reset form
            titleSelect.value = 'Groceries';
            customTitle.value = '';
            customTitle.style.display = 'none';
            amountEl.value = '';
            typeEl.value = 'expense';
            dateEl.value = '<?php echo date('Y-m-d'); ?>'; // Reset date input
            loadBalanceOnly();
            document.getElementById("dashboardStatus").textContent = "Entry added successfully!";
        } else {
            document.getElementById("dashboardStatus").textContent = data && data.message ? data.message : "Failed to add entry";
        }
    })
    .catch(err => {
        console.error("Fetch error:", err);
        document.getElementById("dashboardStatus").textContent = "Error connecting to server";
    });
});

function loadBalanceOnly() {
    fetch("fetch_expenses.php")
        .then(res => res.json())
        .then(data => {
            let balance = 0;
            if (Array.isArray(data)) {
                data.forEach(item => {
                    const amt = parseFloat(item.amount) || 0;
                    balance += (item.type === "income") ? amt : -amt;
                });
            }
            document.getElementById("balanceValue").textContent = "₹" + balance.toFixed(2);
        })
        .catch(err => console.error("Load balance error:", err));
}

loadBalanceOnly();
</script>
</body>
</html>