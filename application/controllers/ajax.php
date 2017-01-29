<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comments extends MY_Controller {

   
    public function show($item_id) {
        
        $this->load->model('comments_model');
        
        $data['comments'] = $this->comments_model->get_many_by(array(
            'item_id' => $item_id
        ));
        
        $this->load->view('comments/show', $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */