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
        $usersManager = $request->getManager('users');
        $error = false;
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
                    $user = $usersManager->insertUser($data);
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
            return new \QDE\Responses\TwigResponse('flash.html.twig', array('path' => 'home', 'info' => 'Successful inscription'));
        else
            return new \QDE\Responses\TwigResponse('flash.html.twig', array('path' => 'user_signup', 'info' => $errorMessage));
    }
    public function logInAction($request)
    {
        $UsersManager = $request->getManager('users');
        return new \QDE\Responses\TwigResponse('login.html.twig', array());
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
            return new \QDE\Responses\TwigResponse('flash.html.twig', array('path' => 'home', 'info' => 'Login successful'));
        }
        return new \QDE\Responses\TwigResponse('login.html.twig', array('info' => 'Incorrect email or password'));
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
