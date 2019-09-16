<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(commentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index(Request $request)
    {
        $parent_id = $request->input('parent_id', 2);
        // 难理解方法 $this->commentService->getComment($parent_id);
        return $this->commentService->getCommentBest($parent_id);
    }
}
