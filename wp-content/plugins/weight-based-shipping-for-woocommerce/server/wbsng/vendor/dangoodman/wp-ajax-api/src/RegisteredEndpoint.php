<?php declare(strict_types=1);

namespace WbsngVendors\Dgm\WpAjaxApi;


interface RegisteredEndpoint
{
    public function url(array $params = []);
}