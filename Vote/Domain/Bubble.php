<?php


class Domain_Bubble {

    public function insertTags($UserID,$tagIDs) {
        $model = new Model_MiniTags();
        return $model->insertTags($UserID,$tagIDs);
    }

    public  function getTags($UserID){
        $model = new Model_MiniTags();
        return $model->getTags($UserID);
    }

    public function insertBubbleInfo($parameter) {
        $model = new Model_MiniBubble();
        return $model->insertBubbleInfo($parameter);
    }

    public function getBubbleInfo($UserID) {
        $model = new Model_MiniBubble();
        return $model->getBubbleInfo($UserID);
    }

    public function getAroundInfo($UserID,$distance,$longtitude,$latitude) {
        $model = new Model_MiniBubble();
        return $model->getAroundInfo($UserID,$distance,$longtitude,$latitude);
    }

    public function  getMatchPercnt($ansUserID,$BubbleID,$Useranswer){
        $model = new Model_Minimatch();
        return $model->getMatchPercnt($ansUserID,$BubbleID,$Useranswer);
    }

    public function  getBubbleInfobyBid($BubbleID){
        $model = new Model_MiniBubble();
        return $model->getBubbleInfobyBid($BubbleID);
    }

    public function  getPersonAllvalidBubbles($BubbleID){
        $model = new Model_MiniBubble();
        return $model->getPersonAllvalidBubbles($BubbleID);
    }


}
