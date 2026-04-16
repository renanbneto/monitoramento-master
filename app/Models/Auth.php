<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Auth
 * 
 * @property int $id
 * @property string $name
 * @property string $pass
 * @property string $nive
 * @property string $nome
 * @property string $ver
 * @property Carbon $data
 * @property string $user
 * @property string $email
 * @property string $contador
 *
 * @package App\Models
 */
class Auth extends Model
{
	protected $table = 'auth';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $dates = [
		'data'
	];

	protected $fillable = [
		'name',
		'pass',
		'nive',
		'nome',
		'ver',
		'data',
		'user',
		'email',
		'contador'
	];
}
