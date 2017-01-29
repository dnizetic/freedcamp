<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax extends MY_Controller {

    
    /**
     * Checks whether there are new comments in the table
     * by checking if there is a comment with newer date
     * than latest_date.
     */
    public function check_new_comments() {

        $this->load->model('comments_model');

        //Date of the latest comment user has on screen.
        $latest_date = $this->input->post('latest_date', TRUE);
        $item_id = $this->input->post('item_id', TRUE);

        $new_comments = $this->comments_model->get_new_comments($latest_date, $item_id);
        $num_new = count($new_comments);

        if ($num_new > 0) {
            $this->json(1, null, $new_comments);
        } else {
            //No new comments
            $this->json(0, null);
        }
    }

}
