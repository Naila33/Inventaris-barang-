<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/phpqrcode/qrlib.php';

class Ciqrcode {

    public function generate($params = array())
    {
        QRcode::png(
            $params['data'],
            $params['savename'],
            isset($params['level']) ? $params['level'] : 'H',
            isset($params['size']) ? $params['size'] : 10
        );
        return true;
    }
}
