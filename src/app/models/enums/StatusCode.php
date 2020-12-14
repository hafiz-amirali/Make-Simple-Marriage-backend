<?php

namespace App\Models\Enums;

use App\Models\Enums\Enum;


// Encapsulating enumerated constants 
class StatusCode extends Enum { 
  const ok = 200; 
  const internal_server = 500; 
} 