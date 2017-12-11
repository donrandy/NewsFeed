<?php

//error_reporting(E_ALL & ~E_NOTICE);

class UserController {

	function __construct($action) {
		global $rep,$views,$contents;
		$errors = array();

		try {

			switch($action) {

				case NULL:
					$this->displayAllNews();
					break;

				case "search":
					$this->search();
					break;

				case "login":
					$this->login();
					break;

				case "signin":
					$this->signin();
					break;

				default:
					$errors[] =	"Bad request";
					require($rep.$views['error']);
			}
		}
		catch (Exception $e) {
			$errors[] =	"Error : " . $e;
			require($rep.$views['error']);
		}
		exit(0);
	}

	private function login() : void {
		global $rep,$views,$contents, $admin;
		require($rep.$views['login']);
	}

	private function signin() : void {
		global $rep,$views,$contents, $admin;
		if(isset($_POST['inputUsername']) && isset($_POST['inputPassword'])){

			if(AdminModel::connect($_POST['inputUsername'], $_POST['inputPassword']) == false) {
				$wrong=true;
				require($rep.$views['login']);
			}
			else
				header('Location: index.php');
		}
	}

	protected function search() : void {
		global $rep,$views,$contents, $admin;
		if(isset($_POST['keyWord']) && !empty($_POST['keyWord'])){
			$page=Cleaner::CleanInt($_GET['page']);
			$keyWord=Cleaner::CleanString($_POST['keyWord']);
			$allNews=UserModel::getNewsByKeyWord($keyWord, $page);
			$nbNews = UserModel::getNbNewsByKeyword($keyWord);
			require($rep.$views['home']);
		}
		else
			header('Location: index.php');
	}

	protected function displayAllNews() : void {
			global $rep,$views,$contents, $admin;
			$page=Cleaner::CleanInt($_GET['page']);
			$allNews = UserModel::getAllNews($page);
			$nbNews = UserModel::getNbNews();
			require($rep.$views['home']);
	}
}
