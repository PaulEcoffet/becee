<?php

namespace Becee\Hooks;

class FlashHook extends \QDE\Hook
{
    protected $app = null;

    public function __construct(\QDE\App &$app)
    {
        $this->app = $app;
    }

    public function getName()
    {
        return 'flash_hook';
    }

    public function runAscending($response)
    {
        $time = time();
        $to_send = array();
        $error = false;
        try
        {
            $all_variables = $this->app->getSession('flash_hook');
            if(isset($all_variables[$this->app->getRouteName()]))
            {
                $variables = $all_variables[$this->app->getRouteName()];
                foreach($variables as $key => $variable)
                {
                    if($time < $variable[1])
                    {
                        $to_send[$key] = $variable[0];
                    }
                }
                unset($all_variables[$this->app->getRouteName()]);
            }
            $this->app->setSession('flash_hook', $all_variables);
        }
        catch(\Exception $e)
        {
            $error = true;
        }
        if(!$error)
        {
            $response->setCustomVariable('flash', $to_send);
        }
        return $response;
    }

    public function setVariablesForPage($routename, $variables, $expiration=10)
    {
        $time = time();
        foreach($variables as &$variable)
        {
            $variable = array($variable, $time + $expiration);
        }
        try
        {
            $all_variables = $this->app->getSession('flash_hook');
        }
        catch(\Exception $e)
        {
            $all_variables = array();
        }
        $existing_var = (isset($all_variables[$routename]) ? $all_variables[$routename] : array());
        $all_variables[$routename] = array_merge($variables, $existing_var);
        $this->app->setSession('flash_hook', $all_variables);
    }
}
