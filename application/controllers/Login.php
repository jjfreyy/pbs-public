<?php
class Login extends CI_Controller {
  function __construct() {
    parent::__construct();
  }

  public function index() {
    $this->load->view('v_login');
  }

  public function process_login() {
    if (isset($_POST['login_btn'])) {
      $username = sanitize($this->input->post('username'));
      $password = sanitize($this->input->post('password'));
      $user = $this->pbs->tuser_get(array("filter" => array("username" => $username, "pass" => $password)));
      if ($user->num_rows() !== 1) {
        $errors[] = 'Username atau password salah. <br />';
      }

      if (isset($errors)) {
        prepare_flashdata(array("report", get_form_report("error", $errors)));
        redirect('login');
      } else {
        if (check_file("activation.txt")) {
          if (!isset($_SESSION)) {
            session_start();
          }

          $app_name = explode(" ", file_get_contents("activation.txt"))[0];
          $this->session->username = $user->row()->username;
          $this->session->lev = $user->row()->lev;
          $app_list = $this->session->app_list;
          if (!isset($app_list) || !in_array($app_name, $app_list)) $app_list[] = $app_name;
          $this->session->app_list = $app_list;
          redirect('home');
        } else {
          echo "<script>alert('Maaf, anda tidak dapat mengakses program ini.');window.location.href='" .base_url(). "'</script>";
        }

      }
    } else {
      redirect(get_error_page());
    }
  }
}
