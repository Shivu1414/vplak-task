<?php
session_start();
    require_once '../helper/helperDb.php';
    $database=new dataBase();
    $conn=$database->adminDbConn();
    if (isset($_SESSION['flag']) && $_SESSION['flag'] == 1) {
        $result = $_SESSION['order_data'];
    } else {
        $query = "SELECT 
            o.id AS order_id,
            o.product_name,
            o.product_link,
            o.price,
            o.quantity,
            o.order_status,
            o.discount,
            o.delivery_charge,
            o.contact_number,
            o.email_id,
            o.date,
            o.time,
            o.payment_type,
            c.name AS customer_name,
            c.customer_contact,
            c.state,
            c.city,
            c.district,
            c.shopping_address
        FROM 
            `order` o
        JOIN 
            customer c ON o.email_id = c.email";
    
        $result = $conn->query($query);    
    }

    function unsetOrderData() {
        unset($_SESSION['order_data']);
        unset($_SESSION['flag']);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_session'])) {
        unsetOrderData();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Details</title>
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/order.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
    <div class="main">
    <nav class="navbar navbar-expand-lg bg-dark nav-custom">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">VPLAK</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="#">PRODUCT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">BRAND</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">CATEGORY</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">BRAND CATEGORY</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">ORDER PANEL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">BAR CHART</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">EXCEL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">SEO TEXT</a>
                    </li>
                </ul>
                <span class="navbar-text text-white">
                    <h4>Logout</h4>
                </span>
            </div>
        </div>
    </nav>

    <div class="order-search container-md">
        <div class="search-heading"><h4>SEARCH ORDER</h4></div>

        <div class="search-bar-div">
            <label class="form-lable">Search Order</label>
            <div class="search-bar-border">
                <form method="post" action="../controller/searchController.php" enctype="multipart/form-data">
                <div class="search-bar-content">
                    <div class="sb-item">By</div>
                    <div class="sb-item">
                        <input type="radio" class="inp-check" id="id" name="checkbox" value="orderId" >
                        <label for="id" class="round-checkbox"></label>
                        Order Id
                    </div>
                    <div class="sb-item">
                        <input type="radio" id="phone" class="inp-check" name="checkbox" value="contactNumber" >
                        <label for="phone" class="round-checkbox"></label>
                        Mobile
                    </div>
                    <div class="sb-item">
                        <input type="radio" id="name" class="inp-check" name="checkbox" value="name" >
                        <label for="name" class="round-checkbox"></label>
                        Name
                    </div>
                    <div class="sb-item">
                        <input type="radio" id="mail" class="inp-check" name="checkbox" value="email" >
                        <label for="mail" class="round-checkbox"></label>
                        Email
                    </div>
                    <div class="sb-inp">
                        <input type="text" name="searchBox" class="inp-src" placeholder="Enter the search value">
                    </div>
                    <?php echo (!isset($_SESSION['flag']) || $_SESSION['flag'] != 1) ? '<div class="sb-item"><button type="submit" name="submit" class="src-btn">SEARCH ORDER</button></div>' : ''; ?>
                </div>
                </form>
                <?php if(isset($_SESSION['flag']) && $_SESSION['flag'] == 1 ){ ?>
                <form method="post" action="">
                    <div class="sb-item clr-btn"><button type="submit" name="clear_session" class="src-btn">Clear Search Data</button></div>
                </form>
                <?php } ?>        
            </div>
        </div>

        <?php
            if (isset($_SESSION['flag']) && $_SESSION['flag'] == 1)   {   
        ?>
            <div class="order-items-div">
            <?php
                foreach($result as $row) { 
            ?>
                <div class="orders-details-div">
                    <div class="order-status">
                        <div>
                            <b>Order Date</b><br>
                            <?php echo $row['date'];?><br>
                            <?php echo $row['time'];?>
                        </div>
                        <div>
                            <div class="cn"><?php echo $row['contact_number'];?></div>
                            <div class="pm"><?php echo $row['payment_type'];?></div>
                        </div>
                        <div><b>Buyer Details</b><br>
                            <div class=""><?php echo $row['customer_name'];?></div>
                            <div class=""><?php echo sprintf('%s %s', $row['state'], $row['city']); ?></div>
                            <div class=""><?php echo $row['customer_contact'];?></div>
                            <div class=""><?php echo $row['email_id'];?></div>
                        </div>
                        <div><b>Total : <?php echo $row['price'];?></b></div>
                        <div><button class="track">Track</button></div>
                        <div ><span class="invoice">Generate invoice</span></div>
                    </div>
                    <div class="order-detail">
                        <div class="p-d-div1">
                            <img src="../assets/img/<?php echo $row['product_name'];?>.jpeg" class="img-pdct" />
                        </div>
                        <div class="p-d-div2">
                            <span class="invoice"><?php echo $row['product_link'];?></span><br>
                            <div class="p-s-div">
                                <div>
                                    <div class="t-s">Model : </div>
                                    <div class="t-s">Price : Rs. <?php echo $row['price'];?></div>
                                    <div class="t-s">Date : <?php echo sprintf('%s %s', $row['date'], $row['time']); ?></div>
                                    <div class="t-s">Discount : <?php echo $row['price'];?></div>
                                </div>
                                <div>
                                    <div class="t-s">Qty : <?php echo $row['quantity'];?></div>
                                    <div class="t-s">Delivery Charge : 0</div>
                                    <div class="t-s">Status : <?php echo $row['order_status'];?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            <?php
              } 
            ?>
            </div>
        <?php
            }
            else{
        ?>
        <div class="order-items-div">
            <?php
                while($row = mysqli_fetch_assoc($result)) { 
            ?>
                <div class="orders-details-div">
                    <div class="order-status">
                        <div>
                            <b>Order Date</b><br>
                            <?php echo $row['date'];?><br>
                            <?php echo $row['time'];?>
                        </div>
                        <div>
                            <div class="cn"><?php echo $row['contact_number'];?></div>
                            <div class="pm"><?php echo $row['payment_type'];?></div>
                        </div>
                        <div><b>Buyer Details</b><br>
                            <div class=""><?php echo $row['customer_name'];?></div>
                            <div class=""><?php echo sprintf('%s %s', $row['state'], $row['city']); ?></div>
                            <div class=""><?php echo $row['customer_contact'];?></div>
                            <div class=""><?php echo $row['email_id'];?></div>
                        </div>
                        <div><b>Total : <?php echo $row['price'];?></b></div>
                        <div><button class="track">Track</button></div>
                        <div ><span class="invoice">Generate invoice</span></div>
                    </div>
                    <div class="order-detail">
                        <div class="p-d-div1">
                            <img src="../assets/img/<?php echo $row['product_name'];?>.jpeg" class="img-pdct" />
                        </div>
                        <div class="p-d-div2">
                            <span class="invoice"><?php echo $row['product_link'];?></span><br>
                            <div class="p-s-div">
                                <div>
                                    <div class="t-s">Model : </div>
                                    <div class="t-s">Price : Rs. <?php echo $row['price'];?></div>
                                    <div class="t-s">Date : <?php echo sprintf('%s %s', $row['date'], $row['time']); ?></div>
                                    <div class="t-s">Discount : <?php echo $row['price'];?></div>
                                </div>
                                <div>
                                    <div class="t-s">Qty : <?php echo $row['quantity'];?></div>
                                    <div class="t-s">Delivery Charge : 0</div>
                                    <div class="t-s">Status : <?php echo $row['order_status'];?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            <?php
              } 
            ?>
            </div>
        <?php
            }
        ?>
    </div>
    </div>
</body>
</html>