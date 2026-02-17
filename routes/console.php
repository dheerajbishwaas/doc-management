<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('documents:cleanup')->daily();
