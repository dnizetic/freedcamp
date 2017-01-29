<?php

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $libs = array(
            'template',
        );
        $this->load->library($libs);
    }

    protected function call_template($view_path, $data) {
        $this->template->write_view('content', $view_path, $data);
        $this->template->render();
    }

    /**
     * Standard JSON response
     * @param type $status
     * @param type $message 
     */
    function json($status, $message, $data = [], $code = 200) {
        $this->output
                ->set_content_type('application/json')
                ->set_status_header($code)
                ->set_output(json_encode(array(
                    'status' => $status,
                    'message' => $message,
                    'data' => $data
        )));
    }

}
