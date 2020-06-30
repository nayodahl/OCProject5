<?php
declare(strict_types=1);

namespace App\Service\Http;

class RequestValidator
{
    //Front
    public function validateHome(Request $request): ?Request
    {
        if (!isset($request->getGet()[2]))
        { 
            return $request;
        };
    return null;
    }

    public function validateShowSinglePost(Request $request): ?Request
    {
        if (isset($request->getGet()[1]) &&  ($request->getGet()[1] > 0))
        { 
            if (!isset($request->getGet()[2]))
            { 
                return $request;
            };
            if ($request->getGet()[2] > 0 ){
                return $request;
            }
        };
    return null;
    }


    //Back
    public function validateShowPostsManager(Request $request): ?Request
        {
            if (!isset($request->getGet()[2]))
            { 
                return $request;
            };
            if (($request->getGet()[2] > 0 )) 
            {
                return $request;  
            }
        return null;
    }

    public function validateEditPost(Request $request): ?Request
    {
        if (isset($request->getGet()[2]) && ($request->getGet()[2] > 0 ))
        { 
            return $request;
        };
        return null;
    }

    public function validateAddPost(Request $request): ?Request
    {
        if (!isset($request->getGet()[2]))
        { 
            return $request;
        };
        return null;
    }

    public function validateShowCommentsManager(Request $request): ?Request
    {
        if (!isset($request->getGet()[2]))
        { 
            return $request;
        };
        if (($request->getGet()[2] > 0 )) 
        {
            return $request;  
        }
        return null;
    }

    public function validateShowUsersManager(Request $request): ?Request
    {
        if (!isset($request->getGet()[2]))
        { 
            return $request;
        };
        if (($request->getGet()[2] > 0 )) 
        {
            return $request;  
        }
        return null;
    }

}
