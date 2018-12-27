<?php
/**
*   JyukyuInfDispAllUkeireModel
*
*   @version 180911
*/
namespace jyukyu_inf\model;

use jyukyu_inf\model\JyukyuInfDispUkeireModel;
use jyukyu_inf\model\PostJyukyuInfDispUkeire;

class JyukyuInfDispAllUkeireModel extends JyukyuInfDispUkeireModel
{
    /**
    *   保存
    *
    *   @param PostJyukyuInfDispAllUkeire $post
    */
    public function setData(PostJyukyuInfDispUkeire $post)
    {
        array_map(
            function ($no_jyukyu, $no_rsuryo) use ($post) {
                $post->no_jyukyu = $no_jyukyu;
                $post->no_rsuryo = $no_rsuryo;
                parent::setData($post);
            },
            explode(',', $post->nm_target),
            explode(',', $post->nm_target_suryo)
        );
    }
}
