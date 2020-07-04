<?php
declare(strict_types=1);

namespace App\Service\Http;

use \App\Service\FormValidator;

class RequestValidator
{
    private $formValidator;

    public function __construct()
    {
        $this->formValidator = new FormValidator();
    }

    /////////// Front ///////////

    public function validateHome(Request $request): ?Request
    {
        if (!isset($request->getGet()[2])) {
            return $request;
        };
        return null;
    }

    public function validateShowSinglePost(Request $request): ?Request
    {
        if (isset($request->getGet()[1]) &&  ($request->getGet()[1] > 0)) {
            if (!isset($request->getGet()[2])) { // comment page number
                $request->setGet([
                    $request->getGet()[0],
                    $request->getGet()[1],
                    1,  // setting default value to 1
                ]);
                return $request;
            };
            if ($request->getGet()[2] > 0) {
                return $request;
            }
        };
        return null;
    }

    public function validateShowPostsPage(Request $request): ?Request
    {
        if (!isset($request->getGet()[1])) {
            $request->setGet([
                $request->getGet()[0],
                1,  // setting default value to 1
            ]);
            return $request;
        };
        if ($request->getGet()[1] > 0) {
            return $request;
        };
        return null;
    }

    public function validateShowLoginPage(Request $request): ?Request
    {
        if (!isset($request->getGet()[2])) {
            return $request;
        };
        return null;
    }

    public function validateshowSigninPage(Request $request): ?Request
    {
        if (!isset($request->getGet()[2])) {
            return $request;
        };
        return null;
    }

    public function validateContactForm(Request $request): ?Request
    {
        if (null === ($request->getGet())) {
            // sanitize input
            $request->setPost([
                'lastname' => $this->formValidator->sanitizeString($request->getPost()['lastname']),
                'firstname' => $this->formValidator->sanitizeString($request->getPost()['firstname']),
                'email' => $this->formValidator->sanitizeEmail($request->getPost()['email']),
                'message' => $this->formValidator->sanitizeString($request->getPost()['message'])
            ]);
              
            //validate input
            if (isset($request->getPost()['lastname']) && isset($request->getPost()['firstname']) && isset($request->getPost()['email']) && isset($request->getPost()['message']) && $this->formValidator->isEmail($request->getPost()['email'])) {
                return $request;
            }

            if (!isset($lastname) || !isset($firstname) || !isset($email) || !isset($message) || !$this->formValidator->isEmail($email)) {
                $_SESSION['error']="tous les champs ne sont pas remplis ou corrects";
                header('location: ');
                exit();
            }
        };
        return null;
    }

    public function validateLoginForm(Request $request): ?Request
    {
        if (!isset($request->getGet()[2])) {
            // sanitize input
            $request->setPost([
                'login' => $this->formValidator->sanitizeString($request->getPost()['login']),
                'password' => $request->getPost()['password'] // temporaire, need a hash + salt function
            ]);

            //validate password
            /*
                needs a password verify function /
            */                
            //validate input
            if (isset($request->getPost()['login']) && isset($request->getPost()['password'])) {
                return $request;
            }
            if (!isset($login) || !isset($password)) {
                $_SESSION['error']="tous les champs ne sont pas remplis ou corrects";
                header('location: login#login');
                exit();
            }
        };
        return null;
    }

    public function validateSigninForm(Request $request): ?Request
    {
        if (!isset($request->getGet()[2])) {
            // sanitize input
            $request->setPost([
                'login' => $this->formValidator->sanitizeString($request->getPost()['login']),
                'password' => $request->getPost()['password'], // temporaire, need a hash + salt function
                'email' => $this->formValidator->sanitizeEmail($request->getPost()['email'])
            ]);

            //validate password
            /*
                needs a password valid function /
                - check if password has a certain length
                - check if password is complex enought
                needs a login valid, to check is the login is already taken in DB
            */
              
            //validate input
            if (isset($request->getPost()['login']) && isset($request->getPost()['password']) && isset($request->getPost()['email']) && $this->formValidator->isEmail($request->getPost()['email'])) {
                return $request;
            }
            if (!isset($login) || !isset($password) || !isset($email) || !$this->formValidator->isEmail($email)) {
                $_SESSION['error']="tous les champs ne sont pas remplis ou corrects";
                header('location: signin#signin');
                exit();
            }
        };
        return null;
    }

    public function validateAddComment(Request $request): ?Request
    {
        if ($request->getPost() && isset($request->getGet()[1]) && ($request->getGet()[1] > 0)) {
            // sanitize input
            $request->setPost([
                'comment' => $this->formValidator->sanitizeTextArea($request->getPost()['comment']),
            ]);

            return $request;
        };

        /* temporaire, message à envoyer vers session

                exit avec message 'tous les champs ne sont pas remplis ou corrects' dans session
                ou vous n'êtes pas loggé
            }
        */
        return null;
    }


    ///////// Back //////////////

    public function validateShowPostsManager(Request $request): ?Request
    {
        if (!isset($request->getGet()[2])) { // page number
            $request->setGet([
                    $request->getGet()[0],
                    $request->getGet()[1],
                    1,  // setting default value to 1
                ]);
            return $request;
        };
        if ($request->getGet()[2] > 0) {
            return $request;
        }
        return null;
    }

    public function validateShowEditPost(Request $request): ?Request
    {
        if (isset($request->getGet()[2]) && ($request->getGet()[2] > 0)) {
            return $request;
        };
        return null;
    }

    public function validateModifyPost(Request $request): ?Request
    {
        if (isset($request->getGet()[2]) && ($request->getGet()[2] > 0)) {

            // sanitize input
            $request->setPost([
                'title' => $this->formValidator->sanitizeString($request->getPost()['title']),
                'chapo' => $this->formValidator->sanitizeString($request->getPost()['chapo']),
                'content' => $this->formValidator->sanitizeTextArea($request->getPost()['content']),
                'author' => $request->getPost()['author'],
            ]);
            if (isset($request->getPost()['title']) && isset($request->getPost()['chapo']) && isset($request->getPost()['content']) && isset($request->getPost()['author'])) {
                return $request;
            }
            /* temporaire, message à envoyer vers session
            if isset($request->getPost()['title']) && isset($request->getPost()['chapo']) && isset($request->getPost()['content']) {
                exit avec message 'tous les champs ne sont pas remplis ou corrects' dans session
            }
            */
        };
        return null;
    }

    public function validateDelete(Request $request): ?Request
    {
        if (isset($request->getGet()[2]) && ($request->getGet()[2] > 0)) {
            return $request;
        };
        return null;
    }

    public function validateShowAddPost(Request $request): ?Request
    {
        if (!isset($request->getGet()[2])) {
            return $request;
        };
        return null;
    }

    public function validateAddPost(Request $request): ?Request
    {
        if (($request->getGet()) !== null) {

            // sanitize input
            $request->setPost([
                'title' => $this->formValidator->sanitizeString($request->getPost()['title']),
                'chapo' => $this->formValidator->sanitizeString($request->getPost()['chapo']),
                'content' => $this->formValidator->sanitizeTextArea($request->getPost()['content']),
                'author' => $request->getPost()['author'],
            ]);
            if (isset($request->getPost()['title']) && isset($request->getPost()['chapo']) && isset($request->getPost()['content']) && isset($request->getPost()['author'])) {
                return $request;
            }
            /* temporaire, message à envoyer vers session
            if isset($request->getPost()['title']) && isset($request->getPost()['chapo']) && isset($request->getPost()['content']) {
                exit avec message 'tous les champs ne sont pas remplis ou corrects' dans session
            }
            */
        };
        return null;
    }

    public function validateShowCommentsManager(Request $request): ?Request
    {
        if (!isset($request->getGet()[2])) { // comment page number
            $request->setGet([
                $request->getGet()[0],
                $request->getGet()[1],
                1,  // setting default value to 1
            ]);
            return $request;
        };
        if (($request->getGet()[2] > 0)) {
            return $request;
        }
        return null;
    }

    public function validateApprove(Request $request): ?Request
    {
        if (isset($request->getGet()[2]) && ($request->getGet()[2] > 0)) { // commentId
            return $request;
        };
        return null;
    }

    public function validateRefuse(Request $request): ?Request
    {
        if (isset($request->getGet()[2]) && ($request->getGet()[2] > 0)) { // commentId
            return $request;
        };
        return null;
    }

    public function validateShowUsersManager(Request $request): ?Request
    {
        if (!isset($request->getGet()[2])) { // user manager page
            $request->setGet([
                $request->getGet()[0],
                $request->getGet()[1],
                1,  // setting default value to 1
            ]);
            return $request;
        };
        if (($request->getGet()[2] > 0)) {
            return $request;
        }
        return null;
    }

    public function validatePromote(Request $request): ?Request
    {
        if (isset($request->getGet()[2]) &&  ($request->getGet()[2] > 0)) { // UserId
            return $request;
        }
        return null;
    }

    public function validateDemote(Request $request): ?Request
    {
        if (isset($request->getGet()[2]) &&  ($request->getGet()[2] > 0)) { // UserId
            return $request;
        }
        return null;
    }

    ////////// Error ///////

    public function validateShow404(Request $request): ?Request
    {
        return $request;
    }
}
