<?php
/**
 * Created by Alaa mohammed.
 * User: alaa
 * Date: 23/07/19
 * Time: 07:59 م
 */

namespace Alaame\Setting\Traits;


use Alaame\Setting\Repositories\Repository;
use Alaame\Setting\Services\ServiceInterface;

trait Injectable
{
    public static function service($name)
    {
        return ($instance = app($name)) instanceof ServiceInterface ? $instance : null;
    }

    public static function repository($name) {
        return ($instance = app($name)) instanceof Repository ? $instance : null;

    }
}
