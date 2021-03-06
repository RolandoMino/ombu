<?php namespace Ombu;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
Use Ombu\Observers\ModificaSlugObserver;
Use Str;
Use Ombu\SluggableTrait;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword,SluggableTrait; 

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * @return mixed
	 */
	public function roles()
	{
		return $this->belongsToMany('Role')->withTimestamps();
	}

	/**
	 * Does the user have a particular role?
	 *
	 * @param $name
	 * @return bool
	 */
	public function hasRole($name)
	{
		foreach ($this->roles as $role)
		{
			if ($role->name == $name) return true;
		}

		return false;
	}

	/**
	 * Assign a role to the user
	 *
	 * @param $role
	 * @return mixed
	 */
	public function assignRole($role)
	{
		return $this->roles()->attach($role);
	}

	/**
	 * Remove a role from a user
	 *
	 * @param $role
	 * @return mixed
	 */
	public function removeRole($role)
	{
		return $this->roles()->detach($role);
	}


	public static function boot()
	{
		parent::boot();

		User::observe(new ModificaSlugObserver);
	}



}
