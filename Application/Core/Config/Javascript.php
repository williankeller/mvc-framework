<?php

/**
 * This file contains javascript configuration for the application.
 * It will be used by app/core/Config.php
 *
 * Copyright (C) 2018 MVC Framework.
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */
return array(
    /**
     * Public root used in ajax calls and redirection from client-side
     *
     */
    'root' => PUBLIC_ROOT,
    /**
     * Max file size, this is important to avoid overflow in files with big size.
     *
     */
    'fileSizeOverflow' => 10485760
);
