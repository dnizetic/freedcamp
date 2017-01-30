<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comments extends MY_Controller {

    public function show($item_id) {

        $this->load->model('comments_model');


        /**
         * Sort comments by date before fetching them.
         */
        $data['comments'] = $this->comments_model->order_by('date')->get_many_by(array(
            'item_id' => $item_id
        ));


        /**
         * Long polling needs to pass item id in order
         * to check for new rows.
         */
        $data['item_id'] = $item_id;


        /**
         * Get date field from last row. With this date we'll check for newly added rows.
         * If a row in DB has a greater date than this, then we'll know
         * it's a newly added row.
         */
        $comments = $data['comments'];
        if (count($comments) > 0) {
            $last_row_index = count($comments) - 1;
            $data['latest_date'] = $comments[$last_row_index]->date;
        } else {
            /**
             * Fallback gracefully in order to make everything
             * work properly even if there's no comments.
             */
            $data['latest_date'] = '0000-00-00';
        }

        $this->call_template('comments/show', $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */