<?php
One::setAlias('@one', dirname(dirname(__DIR__)) . '/one');
One::setAlias('@common', dirname(__DIR__));
One::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
One::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
One::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
One::setAlias('@themes', dirname(dirname(__DIR__)) . '/themes');
One::setAlias('@backendWebroot', dirname(dirname(__DIR__)) . '/public/admin');
One::setAlias('@frontendWebroot', dirname(dirname(__DIR__)) . '/public');
