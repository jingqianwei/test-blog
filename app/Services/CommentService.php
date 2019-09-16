<?php
/**
 * Created by PhpStorm.
 * User: chinwe.jing
 * Date: 2019/9/16
 * Time: 14:36
 */

namespace App\Services;


use App\Models\Comment;
use App\Repositories\CommentRepository;

class CommentService
{
    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * 获取评论内容
     * @param int $parent_id
     * @param array $result
     * @return array
     */
    public function getComment($parent_id = 0, &$result = [])
    {
        $arrData = Comment::query()->where('parent_id', $parent_id)->latest()->get();
        if ($arrData->isEmpty()) {
            return [];
        }

        foreach ($arrData as $item) {
            $tempArr = &$result[];
            $item['children'] = $this->getComment($item->id, $tempArr);
            $tempArr = $item;
        }

        return $result;
    }
}
