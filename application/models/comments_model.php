<?php

class Comments_model extends MY_Model {

    protected $_table = 'comments';
    protected $primary_key = 'commentid';

    /**
     * Returns all comments entered after $latest_date.
     * @param type $current_num
     * @param type $item_id
     * @return type
     */
    public function get_new_comments($latest_date, $item_id) {

        $comments = $this->order_by('date')->get_many_by(array(
            'item_id' => $item_id,
            'date >' => $latest_date
        ));

        return $comments;
    }

}

?>