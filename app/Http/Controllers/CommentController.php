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
        return $this->commentService->getComment($parent_id);
    }
}
