<?php

namespace Becee\Controllers;

class Users
{
    public function registerAction($request, $city="Bordeaux")
    {
        try
        {
            $flash = $request->getCustomVariable('flash');
        }
        catch (\Exception $e)
        {
            $flash = array();
        }
        $LocationManager = $request->getManager('location');
        return new \QDE\Responses\TwigResponse('add_user.html.twig',
            array('countries' => $LocationManager->getCountries('nicename'),
                'flash' => $flash));
    }
    public function registerProcessingAction($request)
    {
        $usersManager = $request->getManager('users');
        $error = false;
        $errorMessage = '';
        $data = array();
        try
        {
            $data['name'] = htmlspecialchars($request->getPost('name'));
            $data['password'] = $request->getPost('password');
            $verifypassword = $request->getPost('verifypassword');
            $data['email'] = htmlentities($request->getPost('email'));
            $country = $request->getPost('country'); // TODO USELESS

        }
        catch (Exception $e)
        {
            $error = true;
            $errorMessage = 'A field was not properly filled';
        }
        if (!$error)
        {
            if($data['password'] === $verifypassword)
            {
                // TODO Email verification with a preg_match
                if($usersManager->getUserByMail($data['email']) === false)
                {
                    $user = $usersManager->insertUser($data); //TODO Shall we connect the user?
                }
                else
                {
                    $error = true;
                    $errorMessage = 'This email is already used';
                }
            }
            else
            {
                $error = true;
                $errorMessage = 'Passwords are not the same';
            }

        }
        if(!$error)
            return new \QDE\Responses\RedirectResponse('home');
        else
            return new \QDE\Responses\RedirectResponse('user_signup', null, array('error' => $errorMessage));
    }
    public function logInAction($request)
    {
        try
        {
            $flash = $request->getCustomVariable('flash');
        }
        catch (\Exception $e)
        {
            $flash = array();
        }
        $UsersManager = $request->getManager('users');
        return new \QDE\Responses\TwigResponse('login.html.twig', array('flash' => $flash));
    }
    public function logInProcessingAction($request)
    {
        $UsersManager = $request->getManager('users');
        $CurrentUserManager = $request->getManager('CurrentUser');
        $post = $request->getPost();
        $user = $UsersManager -> checkValidAuth($post['email'], $post['password']);
        if(isset($user))
        {
            $CurrentUserManager -> connectUser($user);
            return new \QDE\Responses\RedirectResponse('home');
        }
        return new \QDE\Responses\RedirectResponse('user_login', null, array('error' => 'Invalid email/password combination'));
    }
    public function logOutAction($request)
    {
        $CurrentUserManager = $request->getManager('CurrentUser');
        $CurrentUserManager -> disconnectUser();
        return new \QDE\Responses\TwigResponse('flash.html.twig', array('path' => 'home', 'info' => 'Logout successful'));
    }
    public function managerAction($request)
    {
        $UsersManager = $request->getManager('users');
        return new \QDE\Responses\TwigResponse('user_manager.html.twig', array('section' => $request->getParamsUri('section')));
    }
}
