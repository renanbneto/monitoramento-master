<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class Autorizacao
{
    /**
     * The gate instance.
     *
     * @var \Illuminate\Contracts\Auth\Access\Gate
     */
    protected $gate;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $ability
     * @param  array|null  ...$models
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

    private function autorizar($necessarias,$possuidas)
    {
        foreach ($necessarias as $necessaria) {
            foreach ($possuidas as $possuida){
                if($possuida === $necessaria)
                    return true;
            }
        }
        return false;
    }

    public function handle($request, Closure $next, $ability, ...$models)
    {

        $necessarias = Str::of($ability)->explode(';');
        $possuidas = Str::of(Session::get('autorizacoes'))->explode(';');

        if(!$this->autorizar($necessarias,$possuidas)){
            return redirect('home')->with('error', 'Um problema ocorreu !'); //  Não autorizado
        }

        return $next($request);
    }

    /**
     * Get the arguments parameter for the gate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array|null  $models
     * @return \Illuminate\Database\Eloquent\Model|array|string
     */
    protected function getGateArguments($request, $models)
    {
        if (is_null($models)) {
            return [];
        }

        return collect($models)->map(function ($model) use ($request) {
            return $model instanceof Model ? $model : $this->getModel($request, $model);
        })->all();
    }

    /**
     * Get the model to authorize.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $model
     * @return \Illuminate\Database\Eloquent\Model|string
     */
    protected function getModel($request, $model)
    {
        if ($this->isClassName($model)) {
            return trim($model);
        } else {
            return $request->route($model, null) ?:
                ((preg_match("/^['\"](.*)['\"]$/", trim($model), $matches)) ? $matches[1] : null);
        }
    }

    /**
     * Checks if the given string looks like a fully qualified class name.
     *
     * @param  string  $value
     * @return bool
     */
    protected function isClassName($value)
    {
        return strpos($value, '\\') !== false;
    }
}
