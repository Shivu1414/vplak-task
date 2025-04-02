<?php
session_start();
class searchController{

  public function index(){
    if($_SERVER['REQUEST_METHOD']==='POST'){
      $this->handle();
    }
    else{
      $this->showLogin();
    }
  }

  private function showLogin(){
    header("Location:http://localhost/vplak-task/view/orders.php");
  }

  private function handle(){
    require_once '../model/searchModel.php';
    $data=$_POST['searchBox'];
    if (isset($_POST['checkbox'])) {
        $selectedValue = $_POST['checkbox'];
    } else {
        echo "<script> confirm('No option selected.')</script>";
        header("Location:http://localhost/vplak-task/view/orders.php");
    }

    require_once '../helper/helperDb.php';
    $database=new dataBase();
    $conndb=$database->adminDbConn();
    if($selectedValue == "name"){
        $sql="SELECT o.id AS order_id, o.product_name, o.product_link, o.price, o.quantity, o.order_status, o.discount, o.delivery_charge, o.contact_number, o.email_id, o.date, o.time, o.payment_type, c.name AS customer_name, c.customer_contact, c.state, c.city, c.district, c.shopping_address FROM `order` o JOIN customer c ON o.email_id = c.email where c.name = ?";
        $stmt = $conndb->prepare($sql);
        $stmt->bind_param("s", $data);
    }
    else if($selectedValue == "orderId"){
        $sql="SELECT o.id AS order_id, o.product_name, o.product_link, o.price, o.quantity, o.order_status, o.discount, o.delivery_charge, o.contact_number, o.email_id, o.date, o.time, o.payment_type, c.name AS customer_name, c.customer_contact, c.state, c.city, c.district, c.shopping_address FROM `order` o JOIN customer c ON o.email_id = c.email where o.id = ?";
        $stmt = $conndb->prepare($sql);
        $stmt->bind_param("s", $data);
    }
    else if($selectedValue == "contactNumber"){
        $sql="SELECT o.id AS order_id, o.product_name, o.product_link, o.price, o.quantity, o.order_status, o.discount, o.delivery_charge, o.contact_number, o.email_id, o.date, o.time, o.payment_type, c.name AS customer_name, c.customer_contact, c.state, c.city, c.district, c.shopping_address FROM `order` o JOIN customer c ON o.email_id = c.email where o.contact_number = ?";
        $stmt = $conndb->prepare($sql);
        $stmt->bind_param("s", $data);
    }
    else{
        $sql="SELECT o.id AS order_id, o.product_name, o.product_link, o.price, o.quantity, o.order_status, o.discount, o.delivery_charge, o.contact_number, o.email_id, o.date, o.time, o.payment_type, c.name AS customer_name, c.customer_contact, c.state, c.city, c.district, c.shopping_address FROM `order` o JOIN customer c ON o.email_id = c.email where o.email_id = ?";
        $stmt = $conndb->prepare($sql);
        $stmt->bind_param("s", $data);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $rsearchOpt = $result->fetch_all(MYSQLI_ASSOC);

    if (!empty($rsearchOpt)) {
        $_SESSION['flag'] = 1;
        $_SESSION['order_data'] = $rsearchOpt;
            header("Location: ../view/orders.php");
        exit();
    } else {
        echo "No results found.";
    }
  }
}
$search = new searchController();
$search->index();
?>