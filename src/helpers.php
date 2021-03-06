<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 27/04/16
 * Time: 06:06
 */

if (! function_exists('t')) {
    function t($txt)
    {
        $translated = app('translator')->trans('general.'.$txt);
        if (strpos($translated, 'general.')===false)
            return $translated;
        else
            return $txt;
    }
}

if (! function_exists('try_call')) {
    function try_call($functionName, $obj)
    {
        if (method_exists($obj, $functionName) && is_callable([$obj, $functionName]))
            return call_user_func([$obj, $functionName]);
        else {
            var_dump(get_class($obj).' dont have '.$functionName);
        }
    }
}
if (! function_exists('factory_orm_make')) {
    /**
     * Create a model factory builder for a given class, name, and amount.
     *
     * @param  dynamic  class|class,name|class,amount|class,name,amount
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    function factory_orm_make()
    {
        $arguments = func_get_args();

        $factory = app(\Illuminate\Database\Eloquent\Factory::class);

        if (isset($arguments[0]) && is_string($arguments[0])) $class = App::make($arguments[0]);
        if ($class instanceof \Doctrine\ORM\EntityRepository){
            if (isset($arguments[1]))
                return \League\FactoryMuffin\Facade::instance($class->model, $arguments[1]);
            else
                return \League\FactoryMuffin\Facade::instance($class->model);
        }elseif ($class instanceof \Illuminate\Database\Eloquent\Model){
            return factory($arguments[0])->make($arguments[1]);
        }else{
            dd('Error could not factory: '.$arguments[0]);
        }
    }
}
if (! function_exists('factory_orm_create')) {
    /**
     * Create a model factory builder for a given class, name, and amount.
     *
     * @param  dynamic  class|class,name|class,amount|class,name,amount
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    function factory_orm_create()
    {
        $arguments = func_get_args();

        $factory = app(\Illuminate\Database\Eloquent\Factory::class);

        if (isset($arguments[0]) && is_string($arguments[0])) {
            $class = App::make($arguments[0]);
            if ($class instanceof \Doctrine\ORM\EntityRepository){
                if (isset($arguments[1]))
                    return \League\FactoryMuffin\Facade::create($class->model, $arguments[1]);
                else
                    return \League\FactoryMuffin\Facade::create($class->model);
            }elseif ($class->model instanceof \Illuminate\Database\Eloquent\Model){
                return factory(get_class($class->model))->create();
            }else{
                dd('Error could not factory: '.$arguments[0]);
            }
        }
    }
}

if (!function_exists('check_orm')) {

    function check_orm($eloquent, $doctrine)
    {
        $env = env('ERPNET_ORM', 'eloquent');
        if ($env =='eloquent')
            return $eloquent;
        elseif($env=='doctrine')
            return function($app) use ($doctrine) {
                // This is what Doctrine's EntityRepository needs in its constructor.
                return new $doctrine['repository'](
                    $app['em'],
                    $app['em']->getClassMetaData($doctrine['entity'])
                );
            };
        else {
            throw new \InvalidArgumentException;
//            abort(403);
//            return false;
        }
    }
}

if (!function_exists('controller')) {
    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array   $options
     * @return void
     */
    function controller($name, $controller, array $options = [])
    {
        app('router')->controller($name, $controller, $options);
    }
}

if (!function_exists('getRender')) {
    function getRender(&$paginator)
    {
        return (!is_null($paginator)&&get_class($paginator)=='Illuminate\Pagination\Paginator')?$paginator->render():'';
    }
}

if (!function_exists('getTableCacheKey')) {
    function getTableCacheKey($table)
    {
        $sql = app('db')
            ->table($table)
            ->select(DB::raw('count(*) as register_count, MAX(updated_at)'))
//            ->toSQL();
            ->get();
        return md5(serialize($sql));
    }
}

if (! function_exists('secure_route')) {
    /**
     * Generate a URL to a named route.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $absolute
     * @param  \Illuminate\Routing\Route  $route
     * @return string
     */
    function secure_route($name, $parameters = [], $route = null)
    {
        if (config('delivery.forceSiteSSL'))
            return secure_url(app('url')->route($name, $parameters, false, $route));
        else
            return url(app('url')->route($name, $parameters, false, $route));
    }
}

if ( ! function_exists('link_to_route_sort_by')){
    function link_to_route_sort_by($route, $column, $body, array $params=array(), array $attributes = array()){
        $params['sortBy']=$column;
        $params['direction']=isset($params['direction'])?!$params['direction']:false;
//        dd($params);
        return link_to_route($route, $body, $params, $attributes);
    }
}

if ( ! function_exists('link_to_delivery_logo')){
    function link_to_delivery_logo($img, array $params=array(), array $attributes = array()){
        $body = app('html')->image($img, trans('delivery.nav.logoAlt'), [
            'title'=>trans('delivery.nav.logoTitle'),
            'style'=>'width: 150px; max-height: 100%;']);
//        return link_to_route('delivery.index', $body, $params, $attributes);
        return sprintf(  link_to_route('delivery.index', '%s', $params, $attributes), $body );
    }
}

if ( ! function_exists('link_to_route_social_button')){
    function link_to_route_social_button($route, $body, array $params=array(), array $attributes = array()){
        return sprintf(link_to_route($route, '%s', $params, $attributes), $body );
    }
}

if ( ! function_exists('labelEx')){
    function labelEx($name, $value = null, $options = array()){
        return sprintf( app('form')->label($name, '%s', $options), $value );
//        return app('form')->label('cep',trans('modelPartner.attributes.cep').'<span style="color:red;">*</span>:');
    }
}

if ( ! function_exists('formatBRL')){
    function formatBRL($valor = 0){
        //if (empty($valor)) return app('currency')->convert(0)->from('BRL')->format();
//        return app('currency')->convert($valor)->from('BRL')->format();
        return app('currency')->format($valor);
    }
}

if ( ! function_exists('formatDateTranslated')){
    function formatDateTranslated($valor = 0, $format=null){
        $fmt = new \IntlDateFormatter( \App::getLocale() ,\IntlDateFormatter::FULL, \IntlDateFormatter::FULL, null, null, $format);
//        dd($fmt->format($valor));
        return $fmt->format($valor);
    }
}

if ( ! function_exists('formatPercent')){
    function formatPercent($valor = 0){
        return ( app('formatPercent')->format($valor));
    }
}

if ( ! function_exists('array_search_second_level')){
    function array_search_second_level($array, $key, $value){
        $encontrou=false;
        foreach ($array as $sub_array){
            if (isset($sub_array[$key]) && $sub_array[$key]==$value) $encontrou=true;
        }
        return $encontrou;
    }
}

if ( ! function_exists('setTraffic')){
    function setTraffic() {
        if (config('delivery.storeTraffic')){
            $serverInfo = [
                $_SERVER['HTTP_HOST'],
                $_SERVER['HTTP_USER_AGENT'],
                $_SERVER['SERVER_NAME'],
                $_SERVER['SERVER_ADDR'],
                $_SERVER['SERVER_PORT'],
                $_SERVER['REMOTE_PORT'],
//                $_SERVER['REQUEST_SCHEME'],
                $_SERVER['REQUEST_METHOD'],
                $_SERVER['QUERY_STRING'],
                $_SERVER['REQUEST_URI'],
                $_SERVER['SCRIPT_NAME'],
                $_SERVER['PHP_SELF'],
                $_SERVER['REQUEST_TIME_FLOAT'],
                $_SERVER['REQUEST_TIME'],
            ];
            if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) array_push($serverInfo,$_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $attributes = [
                'user_info' => Auth::guest() ? 'Guest' : Auth::user()->toJson(),
                'session_id' => app('session')->getId(),
                'remote_address' => $_SERVER['REMOTE_ADDR'],
                'server_info' => json_encode($serverInfo),
            ];
            \App\Models\Traffic::create($attributes);
        }

    }
}