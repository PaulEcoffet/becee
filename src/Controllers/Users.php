<?php

namespace Becee\Controllers;

class Users
{
    public function registerAction($request, $city="Bordeaux")
    {
        $LocationManager = $request->getManager('location');
        return new \QDE\Responses\TwigResponse('add_user.html.twig', array('countries' => $LocationManager->getCountries('nicename')));
    }
    public function registerProcessingAction($request)
    {
        $UsersManager = $request->getManager('users');
        $user = $UsersManager->insertUser($request->getPost());
        return new \QDE\Responses\TwigResponse('flash.html.twig', array('path' => 'home', 'info' => 'Successful inscription'));
    }
    public function logInAction($request)
    {
        $UsersManager = $request->getManager('users');
        return new \QDE\Responses\TwigResponse('login.html.twig', array());
    }
    public function logInProcessingAction($request)
    {
        $UsersManager = $request->getManager('users');
        $CurrentUserManager = $request->getManager('currentuser');
        $post = $request->getPost();
        $user = $UsersManager -> checkValidAuth($post['email'], $post['password']);
        if(isset($user))
        {
            $CurrentUserManager -> connectUser($user);
            return new \QDE\Responses\TwigResponse('flash.html.twig', array('path' => 'home', 'info' => 'Login successful'));
        }
        return new \QDE\Responses\TwigResponse('flash.html.twig', array('path' => 'home', 'info' => 'Login or password incorrect'));
    }
    public function logOutAction($request)
    {
        $CurrentUserManager = $request->getManager('currentuser');
        $CurrentUserManager -> disconnectUser();
        return new \QDE\Responses\TwigResponse('flash.html.twig', array('path' => 'home', 'info' => 'Logout successful'));
    }
    public function managerAction($request)
    {
        $UsersManager = $request->getManager('users');
        return new \QDE\Responses\TwigResponse('user_manager.html.twig', array('section' => $request->getParamsUri('section')));
    }
}
