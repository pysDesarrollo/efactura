<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome() {
		return View::make('hello');
	}

	public function showLogin() {
		return View::make('pages.login');
	}

	public function doLogin() {
		$rules = array(
			'username' => 'required',
			'password' => 'required|alphaNum|min:5'
		);
		
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()){
			return Redirect::to('login')
				->withErrors($validator)
				->withInput(Input::all());
		} else {
			//$auth = User::where('usu_ruc', '=', Input::get('username'))->where('password', '=', Hash::make(Input::get('password')))->first();
//			$auth = User::where('usu_ruc', '=', Input::get('username'))->where('password', '=', Input::get('password'))->first();
            $user = User::where('usu_ruc', '=', Input::get('username'))->first();
            if(isset($user)) {
                if($user->password == Input::get('password')) { // If their password is still MD5
                    $user->password = Hash::make(Input::get('password')); // Convert to new format
                    $user->save();
                    Auth::login($user);
                    return Redirect::to('/');
                }else{

                    if(Hash::check(Input::get('password'),Hash::make(Input::get('password')))) {
                        Auth::login($user);
            	        return Redirect::to('/');
                    }else{
                        return Redirect::to('login');
                    }
                }
            }else{
                return Redirect::to('login');
            }
//        	if($auth){
//            	Auth::login($auth);
//            	return Redirect::to('/');
//        	}
//        	else
//        	{
//            	return Redirect::to('login');
//        	}
		}
	}

	public function doLogout() {
		Auth::logout();
		return Redirect::to('/');
	}	

	public function doReports() {
		return View::make('pages.reportes.create');
	}
}
