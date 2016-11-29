<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/11/2016
 * Time: 14:52
 */

namespace Biz\Task\Strategy;


use Biz\Course\Service\CourseService;
use Codeages\Biz\Framework\Service\Exception\AccessDeniedException;
use Codeages\Biz\Framework\Service\Exception\InvalidArgumentException;
use Topxia\Common\ArrayToolkit;

class BaseLearningStrategy
{
    public function __construct($biz)
    {
        $this->biz = $biz;
    }


    public function baseFindCourseItems($courseId)
    {
        $items = array();
        $tasks = $this->getTaskService()->findUserTasksFetchActivityAndResultByCourseId($courseId);
        foreach ($tasks as $task) {
            $task['itemType']            = 'task';
            $items["task-{$task['id']}"] = $task;
        }

        $chapters = $this->getChapterDao()->findChaptersByCourseId($courseId);
        foreach ($chapters as $chapter) {
            $chapter['itemType']               = 'chapter';
            $items["chapter-{$chapter['id']}"] = $chapter;
        }

        uasort($items, function ($item1, $item2) {
            return $item1['seq'] > $item2['seq'];
        });

        return $items;
    }

    public function baseCreateTask($fields)
    {
        if ($this->invalidTask($fields)) {
            throw new InvalidArgumentException('task is invalid');
        }

        if (!$this->getCourseService()->tryManageCourse($fields['fromCourseId'])) {
            throw new AccessDeniedException('无权创建任务');
        }

        $activity = $this->getActivityService()->createActivity($fields);

        $fields['activityId']    = $activity['id'];
        $fields['createdUserId'] = $activity['fromUserId'];
        $fields['courseId']      = $activity['fromCourseId'];
        $fields['seq']           = $this->getCourseService()->getNextCourseItemSeq($activity['fromCourseId']);
        $fields['number']        = $this->getTaskService()->getMaxNumberByCourseId($activity['fromCourseId']);

        $fields = ArrayToolkit::parts($fields, array(
            'courseId',
            'seq',
            'courseChapterId',
            'activityId',
            'title',
            'isFree',
            'isOptional',
            'startTime',
            'endTime',
            'status',
            'createdUserId'
        ));
        return $this->getTaskDao()->create($fields);
    }

    protected function invalidTask($task)
    {
        if (!ArrayToolkit::requireds($task, array(
            'title',
            'fromCourseId'
        ))
        ) {
            return true;
        }

        return false;
    }

    protected function getChapterDao()
    {
        return $this->biz->dao('Course:CourseChapterDao');
    }

    /**
     * @return TaskService
     */
    protected function getTaskService()
    {
        return $this->biz->service('Task:TaskService');
    }

    protected function getTaskDao()
    {
        return $this->biz->service('Task:TaskDao');
    }

    /**
     * @return CourseService
     */
    protected function getCourseService()
    {
        return $this->biz->service('Course:CourseService');
    }

    /**
     * @return ActivityService
     */
    protected function getActivityService()
    {
        return $this->biz->service('Activity:ActivityService');
    }

}