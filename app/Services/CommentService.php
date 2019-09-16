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
     * 获取评论内容，用的骚操作，递归加引用
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

    /**
     * 获取评论内容，通俗易懂的方法，直接用递归
     * @param int $parent_id
     * @return array
     */
    public function getCommentBest($parent_id = 0)
    {
        $arrData = Comment::query()->where('parent_id', $parent_id)->latest()->get();
        if ($arrData->isEmpty()) {
            return [];
        }

        /**
         * 方案一，用集合的map()函数处理
         */
        $result = $arrData->map(function ($item) {
            $item->children = $this->getComment($item->id);
            return $item;
        });


        /**
         * 方案二，用foreach()处理
         */
        $result = [];
        foreach ($arrData as $item) {
            $item['children'] = $this->getComment($item->id);
            $result[] = $item;
        }

        /**
         * 方案三，用集合的each()函数处理
         */
//        Comment::query()->where('parent_id', $parent_id)->latest()->get()->each(function ($item) use (&$result) {
//            $item->children = $this->getComment($item->id);
//            $result[] = $item;
//        });

        return $result;
    }
}
