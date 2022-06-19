<?php

namespace PlugRoute\Test\Mock;

use PlugRoute\Http\Body\Content;
use PlugRoute\Http\Request;

class Creator
{
    public static function Request(): Request
    {
        $server = new Server();

        return new Request(
            new Get(),
            new Env(),
            new File(),
            new Cookie(),
            new Session(),
            $server,
            new Content($server)
        );
    }
}