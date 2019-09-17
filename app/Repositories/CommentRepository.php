<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/9/16
 * Time: 15:10
 */

namespace App\Repositories;


use App\Models\Comment;

/**
 * Class CommentRepository
 * @package App\Repositories
 */
class CommentRepository
{
    private $comment;

    /**
     * CommentRepository constructor.
     * @param $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function find(int $id)
    {
        return $this->comment->find($id);
    }
}
