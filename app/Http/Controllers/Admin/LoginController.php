<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
	use AuthenticatesUsers {
		login as parentLogin;
		logout as performLogout;
	}

	protected $redirectTo = '/admin/main';
	//##################################################################################################################
	//##
	//## >> Method : Login
	//##
	//##################################################################################################################
	public function __construct()
	{
		//TODO Geust 로그인 체크
	}

	protected function guard()
	{
		return Auth::guard('admins');
	}

	//##################################################################################################################
	//##
	//## >> Method : Login
	//##
	//##################################################################################################################

	/**
	 * [View] 로그인 화면
	 * -----------------------------------------------------------------------------------------------------------------
	 */
	function login() {
		return view('admin.login.login');
	}

	/**
	 * [Proc] 로그인 처리
	 * -----------------------------------------------------------------------------------------------------------------
	 */
	function loginProc(AdminLoginRequest $request) {
		$mRedirect = $this->parentLogin($request);

		$vSaveEmail    = "";
		$vSavePassword = "";
		$isSaveEmail   = 0;
		$isAutoLogin   = 0;
		if($request->input('remember')) {
			$vSaveEmail = $request->input( 'email' );
			$isSaveEmail = 1;
		}

		if($request->input('auto_login')) {
			$vSaveEmail = $request->input( 'email' );
			$vSavePassword = $request->input( 'password' );
			$isSaveEmail = 1;
			$isAutoLogin = 1;
		}

		Cookie::queue(Cookie::make('email', $vSaveEmail, 45000));
		Cookie::queue(Cookie::make('password', $vSavePassword, 45000));
		Cookie::queue(Cookie::make('remember', $isSaveEmail, 45000));
		Cookie::queue(Cookie::make('auto_login', $isAutoLogin, 45000));

		return $mRedirect;
	}

	/**
	 * [Proc] 로그아웃 처리
	 * -----------------------------------------------------------------------------------------------------------------
	 */
	function logout() {
		$this->guard()->logout();
		return redirect('/admin/login');
	}


}
