<?php

while (true) {
    // Run Laravel scheduler
    passthru('php artisan schedule:run');

    // Wait for 60 seconds
    sleep(60);
}
