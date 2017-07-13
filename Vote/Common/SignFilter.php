<?php

class Common_SignFilter implements PhalApi_Filter {

    public function check() {
        if (DI()->request->get('__debug__') == '1') {
            return;
        }

        if (DI()->request->get('sign') != 'phalapi') {
            throw new PhalApi_Exception_BadRequest(T('wrong sign'));
        }
    }
}
