<?php

/**
 * The redirector class.
 * Provides multiple options for redirection
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Application\Core;

class Redirector
{

    /**
     * Constructor
     *
     */
    public function __construct() {}

    /**
     * Redirect to the given location
     *
     * @param string $location
     */
    public function to($location, $query = "")
    {
        if (!empty($query)) {
            $query = '?' . http_build_query((array) $query, null, '&');
        }

        $response = new Response('', 302, ["Location" => $location . $query]);
        return $response;
    }

    /**
     * Redirect to the given location from the root
     *
     * @param string $location
     */
    public function root($location = "", $query = "")
    {
        return $this->to(PUBLIC_ROOT . $location, $query);
    }
}
