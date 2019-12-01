<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller {
	/**
	 * Display a listing of the resource.
	 */
	public function index() {
		$users = User::get();
		view( 'users', compact( 'users' ) );
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create() {
		view( 'users-create' );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
		// Get form info
		$this->validate( $request,
			[
				'name'     => 'required',
				'email'    => 'required',
				'password' => 'required',
			] );

		// Create User		
		$user = new User( [
			'name'     => $request->input( 'name' ),
			'email'    => $request->input( 'email' ),
			'password' => bcrypt( $request->input( 'password' ) ),
		] );

		$user->save();

		//return redirect('users')->with("status", "A user with ID: #$user->id has been created.");
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( $id ) {
		$user = Users::find( $id );
		view( 'users-view', compact( 'user' ) );

		//return $view->render();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( $id ) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int                      $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, $id ) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id ) {
		//
	}
}
