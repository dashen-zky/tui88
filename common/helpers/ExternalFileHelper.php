<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/30/16 4:40 PM
 */

namespace common\helpers;

use Yii;

/**
 * Class ExternalFileHelper
 * @package wom\helpers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class ExternalFileHelper
{
    // 图片所属类型
    const FILE_CATE_PLAN_ORDER = 'order';
    const FILE_CATE_MEDIA_VIDEO = 'media_video';
    const FILE_CATE_MEDIA_WEIBO = 'media_weibo';
    const FILE_CATE_MEDIA_WEIXIN = 'media_weixin';

    /**
     * 获取外部文件的根目录(绝对路径)
     * @return mixed
     */
    public static function getExternalFileAbsoluteRootDirectory()
    {
        return Yii::$app->params['common.external_file.root_dir.absolute'];
    }

    /**
     * 获取外部文件的根目录(相对路径)
     * @return mixed
     */
    public static function getExternalFileRelativeRootDirectory()
    {
        return Yii::$app->params['common.external_file.root_dir.relative'];
    }

    /**
     * 首页图片存放目录(绝对路径)
     * @return mixed
     */
    public static function getHomePageAbsoluteDirectory()
    {
        return Yii::$app->params['common.external_file.home_page.absolute'];
    }

    /**
     * 首页图片存放目录(相对路径)
     * @return mixed
     */
    public static function getHomePageRelativeDirectory()
    {
        return Yii::$app->params['common.external_file.home_page.relative'];
    }

    /**
     * 视频资源图片目录(绝对路径)
     * @return mixed
     */
    public static function getMediaVideoAbsoluteDirectory()
    {
        return Yii::$app->params['common.external_file.media_video.absolute'];
    }

    /**
     * 视频资源图片目录(相对路径)
     * @return mixed
     */
    public static function getMediaVideoRelativeDirectory()
    {
        return Yii::$app->params['common.external_file.media_video.relative'];
    }

    /**
     * 视频资源图片目录(绝对路径)
     * @return mixed
     */
    public static function getPlanOrderAbsoluteDirectory()
    {
        return Yii::$app->params['common.external_file.plan_order.absolute'];
    }

    /**
     * 视频资源图片目录(相对路径)
     * @return mixed
     */
    public static function getPlanOrderRelativeDirectory()
    {
        return Yii::$app->params['common.external_file.plan_order.relative'];
    }

    /**
     * 其他资源图片目录(绝对路径)
     * @return mixed
     */
    public static function getOtherAbsoluteDirectory()
    {
        return Yii::$app->params['common.external_file.other.absolute'].Yii::$app->user->identity->uuid.'/';
    }

    /**
     * 其他资源图片目录(相对路径)
     * @return mixed
     */
    public static function getOtherRelativeDirectory()
    {
        return Yii::$app->params['common.external_file.other.relative'];
    }

}