<?php

include 'src/bundles/Senso/ClassLoader.php';

(new Senso\Application(
                Senso\Application::ENV_DEV,
                Senso\Application::ENGINE_TEMPLATE
        ));

