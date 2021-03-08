<?php

namespace PHPMaker2021\perpusupdate;

/**
 * Captcha interface
 */
interface CaptchaInterface
{

    public function getHtml();

    public function getConfirmHtml();

    public function validate();

    public function getScript($formName);
}
