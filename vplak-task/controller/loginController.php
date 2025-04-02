<?php
class loginController{

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

  }
}
?>