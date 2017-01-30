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

    
    /**
     * Checks if there's a row in users_typing
     * that was created in the last 10 seconds.
     * Fetches all such rows - they represent users
     * that have started typing in the past 10 seconds.
     */
    public function check_users_typing() {
        $this->load->model('users_typing_model');
        $user_ip = $this->input->ip_address();
        /**
         * Check rows created in last 10 seconds.
         */
        $date_str = date('Y-m-d H:i:s', time() - 10);
        $users_typing = $this->users_typing_model->get_many_by(array(
            'ip !=' => $user_ip, //Dont match current user IP
            'date > ' => $date_str //Last 10 seconds
        ));
        $this->json(1, null, $users_typing);
    }

    /*
     * Insert a row into users_typing so that check_users_typing()
     * can return appropriate rows.
     */
    public function user_typing() {
        $this->load->model('users_typing_model');
        $typing = $this->input->post('typing');
        $user_ip = $this->input->ip_address();
        $this->users_typing_model->insert(array(
            'ip' => $user_ip,
            'typing' => $typing,
        ));
    }
    
    
    public function insert_comment() {
        $this->load->model('comments_model');
        $id = $this->comments_model->insert(array(
            'description' => $this->input->post('description', TRUE),
            'item_id' => $this->input->post('item_id', TRUE),
        ));
        if($id) {
            $this->json(1, 'Success');
        } else {
            $this->json(0, 'Failure');
        }
    }

}
