<?php
declare(strict_types=1);

namespace App\Controller\BackOffice;

use \App\View\View;
use \App\Model\Repository\PostRepository;
use \App\Model\Manager\PostManager;
use \App\Model\Repository\CommentRepository;
use \App\Model\Manager\CommentManager;
//use \App\Model\Repository\UserRepository;
//use \App\Model\Manager\UserManager;
use \App\Service\Http\Request;

class BackController
{
    private $renderer;
    private $postRepo;
    private $postManager;
    private $commentRepo;
    private $commentManager;

    public function __construct()
    {
        $this->renderer = new View();
        $this->postRepo = new PostRepository();
        $this->postManager = new PostManager($this->postRepo);
        $this->commentRepo = new CommentRepository();
        $this->commentManager = new CommentManager($this->commentRepo);
    }

    // Render Posts Manager page (default)
    public function showPostsManager(Request $request): void
    {
        $currentPage=1;
        // validating $_GET
        if (isset($request->getGet()[2]) && ($request->getGet()[2] > 0)) {
            $currentPage=((int)$request->getGet()[2]);
        };

        $listPosts = $this->postManager->getPosts();

        // Some calculation for the pager for Posts page
        $limit = 4; // number of Posts per page to display
        $totalItems = count($listPosts); // total number of Posts
        $totalPages = ceil($totalItems / $limit);
        if ($currentPage > $totalPages) {
            $currentPage=$totalPages;
        };
        $offset = ($currentPage - 1) * $limit; // offset, to determine the number of the first Post to display
        $itemsList = array_splice($listPosts, (int)$offset, $limit);

        $this->renderer->render('backoffice/PostsManager.twig', [
            'listposts' => $itemsList,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
            ]);
    }
}
